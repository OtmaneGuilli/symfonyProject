<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\Client;
use App\Form\FactureType;
use App\Repository\FactureRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/facture')]
class FactureController extends AbstractController
{
    #[Route('/', name: 'app_facture_index', methods: ['GET'])]
    public function index(FactureRepository $factureRepository): Response
    {
        $user = $this->getUser();

        $factures = $factureRepository->createQueryBuilder('f')
            ->join('f.client', 'c')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        return $this->render('facture/index.html.twig', [
            'factures' => $factures,
        ]);
    }

    #[Route('/new', name: 'app_facture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, ClientRepository $clientRepository): Response
    {
        $facture = new Facture();

        $form = $this->createForm(FactureType::class, $facture, [
            'user' => $this->getUser()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client = $facture->getClient();
            if ($client->getUser() !== $this->getUser()) {
                throw $this->createAccessDeniedException();
            }

            // Génération automatique du numéro de facture
            $now = new \DateTime();
            $prefix = 'FAC-' . $now->format('Y');
            $lastFacture = $em->getRepository(Facture::class)
                ->createQueryBuilder('f')
                ->where('f.numeroFacture LIKE :prefix')
                ->setParameter('prefix', $prefix . '%')
                ->orderBy('f.id', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();

            $lastNumber = 1;
            if ($lastFacture) {
                $parts = explode('-', $lastFacture->getNumeroFacture());
                $lastNumber = intval(end($parts)) + 1;
            }

            $numero = sprintf('%s-%05d', $prefix, $lastNumber);
            $facture->setNumeroFacture($numero);

            $em->persist($facture);
            $em->flush();

            $this->addFlash('success', "Facture créée avec le numéro <strong>$numero</strong>.");

            return $this->redirectToRoute('app_facture_index');
        }

        return $this->render('facture/new.html.twig', [
            'facture' => $facture,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_facture_show', methods: ['GET'])]
    public function show(Facture $facture): Response
    {
        if ($facture->getClient()->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('facture/show.html.twig', [
            'facture' => $facture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_facture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facture $facture, EntityManagerInterface $em): Response
    {
        if ($facture->getClient()->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(FactureType::class, $facture, [
            'user' => $this->getUser()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Facture mise à jour avec succès.');
            return $this->redirectToRoute('app_facture_index');
        }

        return $this->render('facture/edit.html.twig', [
            'facture' => $facture,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_facture_delete', methods: ['POST'])]
    public function delete(Request $request, Facture $facture, EntityManagerInterface $em): Response
    {
        if ($facture->getClient()->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete' . $facture->getId(), $request->request->get('_token'))) {
            $em->remove($facture);
            $em->flush();
            $this->addFlash('success', 'Facture supprimée avec succès.');
        }

        return $this->redirectToRoute('app_facture_index');
    }

    #[Route('/client/{id}', name: 'app_facture_by_client', methods: ['GET'])]
    public function byClient(Client $client): Response
    {
        if ($client->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('facture/by_client.html.twig', [
            'client' => $client,
            'factures' => $client->getFactures(),
        ]);
    }
}

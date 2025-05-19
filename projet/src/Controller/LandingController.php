<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class LandingController extends AbstractController
{
    #[Route('/home', name: 'landing_home')]
    public function home(): Response
    {
        return $this->render('landingPage/home.html.twig');
    }
}

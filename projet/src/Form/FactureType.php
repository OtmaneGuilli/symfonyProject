<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Facture;
use App\Repository\ClientRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('numeroFacture', TextType::class)
            ->add('dateFacturation', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('montant', NumberType::class)
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'Payée' => 'Payée',
                    'Partiellement payée' => 'Partiellement payée',
                    'Non payée' => 'Non payée',
                ],
                'placeholder' => 'Choisir un état',
            ])
            ->add('note', TextareaType::class, [
                'required' => false,
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'raisonSociale',
                'query_builder' => function (ClientRepository $repo) use ($options) {
                    return $repo->createQueryBuilder('c')
                        ->where('c.user = :user')
                        ->setParameter('user', $options['user']);
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
            'user' => null, // pour filtrer dynamiquement les clients
        ]);
    }
}

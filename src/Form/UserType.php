<?php
// src/Form/UserType.php

use App\Entity\User;
use App\Entity\Abonnement;
use Doctrine\DBAL\Types\ArrayType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('email', EmailType::class)
            ->add('roles', ChoiceType::class, [
              'choices' => [
                  'ROLE_ADMIN' => 'ROLE_ADMIN',
                  'ROLE_USER' => 'ROLE_USER',
                  'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN',
                  // Add more roles here as needed
              ],
              'multiple' => true,
              'expanded' => true,
          ])     
          ->add('Abonnement', EntityType::class, [
            'class'=>  Abonnement::class,
            'choice_label' => 'libelleAbonnement',
         ])       
          ->add('is_verified', CheckboxType::class, [
            'required' => true,])
          ->add('save', SubmitType::class, ['label' => 'Save'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
?>
<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\Security;


class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $userId = $options['userId'];

        $builder
            ->add('videoscom', EntityType::class, [
                'class' => 'App\Entity\Video',
                'choice_label' => 'titre',
                'label' => ' ',
                'disabled' => 'false'   
            ])
            ->add('userId', TextType::class, [
                'data' => $userId,
            ])
            // ->add('id', IntegerType::class, [
            //     'data' => $userId,
            //     'class' => User::class,
            //     'label' => 'User ID',
            //     'disabled' => true,
            // ])
            ->add('texte', TextareaType::class, [
                'label' => ' '
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => 'Ajouter un commentaire'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
            'user' => null,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Commentaire;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class CommentaireType extends AbstractType
{
      public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('texte', TextareaType::class, [
              'attr' => [
                'rows' => 1, // Définir le nombre de lignes souhaité
                'cols'=> 100,
                'style' => 'resize: none; width: 100%; max-width: 100%;',
                'maxlength' => 255, // Définir la longueur maximale du texte
                'placeholder' => 'Saisir votre commentaire ici..' // Définir le placeholder
              ],
              'label' => false
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
    ]);
    }
}

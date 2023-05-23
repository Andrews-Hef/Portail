<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoriePrefType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('categoriePref', EntityType::class, [
            'class' => Categorie::class,
            'choice_label' => 'libelleCategorie',
            'expanded' => true,
            'multiple' => true,
        ])
        //j'ai supprimé les champs choix type video et categorie pour les remettre plus tard
        ->add('submit',SubmitType::class,[
            'attr'=>[
                'class'=>'btn btn-primary',
            ],'label'=>'Ajouter la vidéo'
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
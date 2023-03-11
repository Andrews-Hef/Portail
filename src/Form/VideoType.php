<?php

namespace App\Form;

use App\Entity\TypeVideo;
use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('qualite')
            ->add('TypeVideo', EntityType::class, [
               'class'=>  TypeVideo::class,
               'choice_label' => 'libelleTypeVideo',
            ])
            ->add('url',UrlType::class)
            ->add("annee")
            ->add("image")
            ->add("description",TextareaType::class)
            //j'ai supprimé les champs choix type video et categorie pour les remettre plus tard
            ->add('submit',SubmitType::class,[
                'attr'=>[
                    'class'=>'btn btn-primary',
                ],'label'=>'add my vidéo'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}

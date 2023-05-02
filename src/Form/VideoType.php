<?php

namespace App\Form;

use App\Entity\Video;
use App\Entity\Categorie;
use App\Entity\TypeVideo;
use App\Form\CategorieType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

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
            ->add('url',UrlType::class, ['label'=>'Url'])
            ->add("annee")
            ->add("image")
            ->add("description",TextareaType::class)
            
            ->add('categories', EntityType::class, [
              'class' => Categorie::class,
              'multiple' => true,
              'expanded' => true,
              'choice_label' => 'libelleCategorie', // ou une autre propriété de la catégorie que vous souhaitez afficher comme choix
          ])
            //j'ai supprimé les champs choix type video et categorie pour les remettre plus tard
            ->add('submit',SubmitType::class,[
                'attr'=>[
                    'class'=>'btn btn-primary',
                ],'label'=>'Ajouter la vidéo'
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

<?php

namespace App\Form;

use App\Entity\TypeVideo;
use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('qualite')
            ->add('TypeVideo', ChoiceType::class, [
                'choices'  => [
                    'Film' => typeVideo.libelleTypeVideo ,
                    'Anime' => 3,
                    'Serie' =>2 ,
                ],
            ])
            ->add('url')
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

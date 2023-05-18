<?php

namespace App\Form;


use App\Entity\TypeVideo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options,): void
    {
    
        $builder
            ->add('libelleTypeVideo')
            /*->add('videos',CollectionType::class, [
                'mapped'=> false,
            ]) */
        
        ->add('submit',SubmitType::class,[
          'attr'=>[
              'class'=>'btn btn-primary',
          ],'label'=>'Ajouter le Type'
      ])
  ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TypeVideo::class,
        ]);
    }
}

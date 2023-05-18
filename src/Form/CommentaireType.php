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
      public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $userId = $options['user_id'];


        $builder
            ->add('videoscom', EntityType::class, [
                'class' => 'App\Entity\Video',
                'choice_label' => 'titre',
                'label' => ' ',
                'disabled' => 'false'   
            ])
            ->add('users', EntityType::class, [
              'class' => User::class,
              'choice_label' => 'username', // Remplacez par le champ souhaité pour l'affichage
              'choice_value' => 'id', // Utilisez l'ID de l'utilisateur comme valeur
              'multiple' => true,
              'expanded' => true, // Facultatif : pour afficher les choix sous forme de cases à cocher
              'label' => 'Utilisateurs',
              'query_builder' => function (EntityRepository $er) use ($userId) {
                  return $er->createQueryBuilder('u')
                      ->andWhere('u.id = :userId')
                      ->setParameter('userId', $userId);
              }
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

            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
              $form = $event->getForm();
              $userId = $options['user_id'];
  
              $userRepository = $this->entityManager->getRepository(User::class);
              $user = $userRepository->find($userId);
  
              $form->get('users')->setData([$user]);
          });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
      $resolver->setDefaults([
        'data_class' => Commentaire::class,
    ]);

    $resolver->setRequired('user_id');
    }
}

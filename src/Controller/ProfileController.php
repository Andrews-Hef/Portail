<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InfoType;
use App\Form\MailType;
use App\Form\CategoriePrefType;
use App\Repository\UserRepository;
use App\Repository\CategorieRepository;
use App\Repository\TypeVideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/profil', name: 'profile_')]
class ProfileController extends AbstractController
{
  private $categories;
  private $typesVideos;
    
    public function __construct(CategorieRepository $cateRepo, TypeVideoRepository $typeRepo)
    {
        $this->categories = $cateRepo->findAll();
        $this->typesVideos = $typeRepo->findAll();
    }
    
    #[Route('/', name: 'index')]
    public function index(Security $security, CategorieRepository $cateRepo, TypeVideoRepository $typeRepo): Response
    {
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        $user = $security->getUser();
        $categories = $cateRepo->findAll();
        $typesVideos = $typeRepo->findAll();
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'Profil de l\'utilisateur',
            'categories' => $categories,
            'typesVideos' => $typesVideos,
            'user' => $user,
        ]);
    }

    #[Route('/modifMail/{id}', name: 'modifMail')]
    public function modifMail(Security $security,Int $id, EntityManagerInterface $manager, UserRepository $repoUser, Request $request): Response
    {
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        $user = $security->getUser();

        if($user->getId() == $id){
          $userIndex = $repoUser->findOneBy(['id' => $id]);
          $form = $this->createForm(MailType::class,$userIndex);
          $form->handleRequest($request);
          if($form->isSubmitted() && $form->isValid()) {
              $userIndex = $form->getData();
              
              $manager->persist($userIndex);
              $manager->flush();
              
              $this->addFlash("success","Le mail a bien été modifié !");
  
             return $this->redirectToRoute("profile_index");
          }
        }
        else{
          $this->addFlash("danger","Vous n'avez pas le droit d'accéder à ce compte !");
  
             return $this->redirectToRoute("profile_index");
        }
        
        return $this->render('profile/editMail.html.twig',[
          'form'=>$form->createView(),
          'categories' => $categories,
          'typesVideos' => $typesVideos,
      ]);
    }


    #[Route('/modifInfo/{id}', name: 'modifInfo')]
    public function modifInfo(Security $security,Int $id, EntityManagerInterface $manager, UserRepository $repoUser, Request $request): Response
    {
      $categories = $this->categories;
      $typesVideos = $this->typesVideos;
      $user = $security->getUser();

      if($user->getId() == $id){
        $userIndex = $repoUser->findOneBy(['id' => $id]);
        $form = $this->createForm(InfoType::class,$userIndex);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $userIndex = $form->getData();
            
            $manager->persist($userIndex);
            $manager->flush();
            
            $this->addFlash("success","Les informations ont bien été modifié !");

           return $this->redirectToRoute("profile_index");
        }
      }
      else{
        $this->addFlash("danger","Vous n'avez pas le droit d'accéder à ce compte !");

           return $this->redirectToRoute("profile_index");
      }
      
      return $this->render('profile/editInfo.html.twig',[
        'form'=>$form->createView(),
        'categories' => $categories,
        'typesVideos' => $typesVideos,
    ]);
    }


    #[Route('/modifCategoriesPref/{id}', name: 'catpref', methods: ['get', 'post'])]
    public function edit(Security $security,Int $id,EntityManagerInterface $entityManager,Request $request): Response{
        $user = $security->getUser();
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        $form = $this->createForm(CategoriePrefType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $categories = $form->get('categoriePref')->getData();
            // Supprimer toutes les catégories associées à l'utilisateur
            foreach ($user->getCategoriePref() as $categorie) {
              $categorie->addUser($user);
            }
            $entityManager->persist($user);

            $entityManager->flush();
        
            
            $this->addFlash("success","Video bien modifiée");

           return $this->redirectToRoute("profile_index");
        }

        return $this->render('profile/editCatePref.html.twig',[
            'form'=>$form->createView(),
            'categories' => $categories,
            'typesVideos' => $typesVideos
        ]);
    }
    
}
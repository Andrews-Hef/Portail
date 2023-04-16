<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Form\AbonnementType;
use App\Repository\CategorieRepository;
use App\Repository\TypeVideoRepository;
use App\Repository\AbonnementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AbonnementController extends AbstractController
{
  private $categories;
  private $typesVideos;
    
    public function __construct(CategorieRepository $cateRepo, TypeVideoRepository $typeRepo)
    {
        $this->categories = $cateRepo->findAll();
        $this->typesVideos = $typeRepo->findAll();
    }
    
    #[Route('/abonnement', name: 'app_abonnement')]
    public function index(AbonnementRepository $repoAbo): Response
    {   
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        $abonnements = $repoAbo->findAll();
        return $this->render('abonnement/index.html.twig', [
            'abonnements'=>$abonnements,
            'categories' => $categories,
            'typesVideos' => $typesVideos
        ]);
    }


    #[Route('/abonnement/new', name:'abonnement.new', methods: ['get', 'post'])]
    public function new(Request $request,EntityManagerInterface $manager): Response{
       
        $abo = new Abonnement();
        
        $form = $this->createForm(AbonnementType::class,$abo);

        //send request to the database
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $abo = $form->getData();
            
            $manager->persist($abo);
            $manager->flush();
            
            $this->addFlash("success","Abonnement added successfully");

           return $this->redirectToRoute("Abonnement.index");
        }
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        return $this->render('abonnement/new.html.twig',[
            'form'=>$form->createView(),
            'categories' => $categories,
            'typesVideos' => $typesVideos
        ]);
    }

    #[Route('/abonnement/edit/{id}', name: 'abonnement.edit', methods: ['get', 'post'])]
    public function edit(AbonnementRepository $repoAbo,Int $id,EntityManagerInterface $manager,Request $request): Response{

        $abo = $repoAbo->findOneBy(["id" => $id ]);
        $form = $this->createForm(AbonnementType::class,$abo);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $abo = $form->getData();
            
            $manager->persist($abo);
            $manager->flush();
            
            $this->addFlash("success","Abonnement updated successfully");

           return $this->redirectToRoute("Abonnement.index");
        }
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        return $this->render('abonnement/edit.html.twig',[
            'form'=>$form->createView(),
            'categories' => $categories,
            'typesVideos' => $typesVideos
        ]);
    }
    #[Route('/abonnement/delete/{id}', name: 'abonnement.del', methods:['GET'])]
    public function delete(EntityManagerInterface $manager,Abonnement $abonnement) :Response{

        if(!$abonnement){
            $this->addFlash("Warning ","abonnement have not been deleted :( ");
            return $this->redirectToRoute("abonnement.index");
        }
        $manager->remove($abonnement);
        $manager->flush();
        $this->addFlash("success","abonnement delete successfully :)");
        return $this->redirectToRoute("abonnement.index");
    }
}

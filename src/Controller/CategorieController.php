<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(CategorieRepository $repoCate): Response
    {
        $categories = $repoCate->findAll();
        return $this->render('categorie/index.html.twig', [
           'categories'=> $categories
        ]);
    }


    #[Route('/categorie/new', name:'categorie.new', methods: ['get', 'post'])]
    public function new(Request $request,EntityManagerInterface $manager): Response{
        //create a new video 
        $categorie = new categorie();
        //bind form with videoType reference
        $form = $this->createForm(CategorieType::class,$categorie);

        //send request to the database
        $form->handleRequest($request);
        //if is submitt is clicked and all valid in form
        if($form->isSubmitted() && $form->isValid()) {
            $categorie = $form->getData();
            //manager send new video "object" in the database
            $manager->persist($categorie);
            $manager->flush();
            
            $this->addFlash("success","categorie added successfully");

           return $this->redirectToRoute("categorie.index");
        }
        return $this->render('categorie/new.html.twig',[
            'form'=>$form->createView(),
        ]);
    }

    #[Route('/categorie/edit/{id}', name: 'categorie.edit', methods: ['get', 'post'])]
    public function edit(CategorieRepository $repoCate,Int $id,EntityManagerInterface $manager,Request $request): Response{

        $categorie = $repoCate->findOneBy(["id" => $id ]);
        $form = $this->createForm(CategorieType::class,$categorie);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $categorie = $form->getData();
            
            $manager->persist($categorie);
            $manager->flush();
            
            $this->addFlash("success","categorie updated successfully");

           return $this->redirectToRoute("categorie.index");
        }

        return $this->render('categorie/edit.html.twig',[
            'form'=>$form->createView(),
        ]);
    }

    #[Route('/categorie/delete/{id}', name: 'categorie.del', methods:['GET'])]
    public function delete(EntityManagerInterface $manager,Categorie $categorie) :Response{

        if(!$categorie){
            $this->addFlash("Warning ","categorie have not been deleted :( ");
            return $this->redirectToRoute("categorie.index");
        }
        $manager->remove($categorie);
        $manager->flush();
        $this->addFlash("success","categorie delete successfully :)");
        return $this->redirectToRoute("categorie.index");
    }
}

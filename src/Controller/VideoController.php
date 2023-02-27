<?php

namespace App\Controller;

use App\Form\VideoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\VideoRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Video;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class VideoController extends AbstractController
{
    #[Route('/video',name:'video.index')]
    public function index(VideoRepository $repoVideo): Response
    {
        $videos = $repoVideo->findAll();
    
        return $this->render('video/index.html.twig', [
            'videos' => $videos
        ]);
    }

    #[Route('/video/new', name: 'video.new', methods: ['get', 'post'])]
    public function new(Request $request,EntityManagerInterface $manager): Response{
        //create a new video 
        $video = new Video();
        //bind form with videoType reference
        $form = $this->createForm(VideoType::class,$video);

        //send request to the database
        $form->handleRequest($request);
        //if is submitt is clicked and all valid in form
        if($form->isSubmitted() && $form->isValid()) {
            $video = $form->getData();
            //manager send new video "object" in the database
            $manager->persist($video);
            $manager->flush();
            
            $this->addFlash("success","Video added successfully");

           return $this->redirectToRoute("video.index");
        }
        return $this->render('video/new.html.twig',[
            'form'=>$form->createView(),
        ]);

    }

    #[Route('/video/edit/{id}', name: 'video.edit', methods: ['get', 'post'])]
    public function edit(VideoRepository $repoVideo,Int $id,EntityManagerInterface $manager,Request $request): Response{

        $video = $repoVideo->findOneBy(["id" => $id ]);
        $form = $this->createForm(VideoType::class,$video);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $video = $form->getData();
            //manager send new video "object" in the database
            $manager->persist($video);
            $manager->flush();
            
            $this->addFlash("success","Video updated successfully");

           return $this->redirectToRoute("video.index");
        }

        return $this->render('video/edit.html.twig',[
            'form'=>$form->createView(),
        ]);
    }

    #[Route('/video/delete/{id}', name: 'video.del', methods:['GET'])]
    public function delete(EntityManagerInterface $manager,Video $video) :Response{

        if(!$video){
            $this->addFlash("Warning ","Video have not been deleted :( ");
            return $this->redirectToRoute("video.index");
        }
        $manager->remove($video);
        $manager->flush();
        $this->addFlash("success","Video delete successfully :)");
        return $this->redirectToRoute("video.index");
    }
}

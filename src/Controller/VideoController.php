<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[Route('/video/show/{id}',name:'video.show')]
    public function showFilm(VideoRepository $repoVideo, Int $id): Response
    {

        $video = $repoVideo->findOneBy(["id" => $id ]);
        return $this->render('video/showVideo.html.twig',[
            'video' => $video
        ]);
    }

    #[Route('/video/new', name:'video.new', methods: ['get', 'post'])]
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

    #[Route('/autocomplete_titres', name: 'autocomplete_titres')]

    public function autocompleteTitres(Request $request, EntityManagerInterface $entityManager)
  {
      $term = $request->query->get('term');
      // Exemple de requête pour récupérer les titres de la base de données
      $titres = $entityManager->getRepository(Video::class)
          ->createQueryBuilder('v')
          ->select('v.titre')
          ->where('v.titre LIKE :term')
          ->setParameter('term', '%'.$term.'%')
          ->getQuery()
          ->getResult();
  
      $results = array_map(fn($titre) => ['value' => $titre['titre']], $titres);
      return new JsonResponse($results);
  }

  }

<?php

namespace App\Controller\Admin;

use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\CategorieRepository;
use App\Repository\TypeVideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', name: 'admin_')]
class MainAdminController extends AbstractController
{
  private $categories;
  private $typesVideos;
    
    public function __construct(CategorieRepository $cateRepo, TypeVideoRepository $typeRepo)
    {
        $this->categories = $cateRepo->findAll();
        $this->typesVideos = $typeRepo->findAll();
    }
    
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        return $this->render('admin/index.html.twig', [
          'categories' => $categories,
          'typesVideos' => $typesVideos
        ]);
    }


    #[Route('/adminvideo/new', name:'adminvideonew', methods: ['get', 'post'])]
    public function new(Request $request,EntityManagerInterface $manager): Response{
        //create a new video 
        $video = new Video();
        //bind form with videoType reference
        $form = $this->createForm(VideoType::class,$video);
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        //send request to the database
        $form->handleRequest($request);
        //if is submitt is clicked and all valid in form
        if($form->isSubmitted() && $form->isValid()) {
            $video = $form->getData();
            $categories = $form->get('categories')->getData();
        // Ajouter les catégories sélectionnées à la vidéo
        foreach ($video->getCategories() as $categorie) {
          $categorie->addVideo($video);
        }

        $manager->persist($video);
        $manager->flush();
                    
            $this->addFlash("success","Video added successfully");

           return $this->redirectToRoute("video.index");
        }
        return $this->render('admin/films/addFilm.html.twig',[
            'form'=>$form->createView(),
            'categories' => $categories,
            'typesVideos' => $typesVideos
        ]);
    }
}
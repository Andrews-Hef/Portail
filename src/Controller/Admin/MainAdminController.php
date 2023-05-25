<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Video;
use App\Form\TypeType;
use App\Form\VideoType;
use App\Entity\Categorie;
use App\Entity\TypeVideo;
use App\Form\CategorieType;
use App\Repository\AbonnementRepository;
use App\Repository\VideoRepository;
use App\Repository\CategorieRepository;
use App\Repository\CinemaRepository;
use App\Repository\TypeVideoRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
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


    #[Route('/adminvideo/liste', name: 'listeVideo')]
    public function listeVideo(VideoRepository $repoVideo): Response
    {
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        $videos = $repoVideo->findAll();
        return $this->render('admin/films/listefFilm.html.twig', compact('videos', 'typesVideos', 'categories'),
      );
    }

    #[Route('/adminvideo/listeCategories', name: 'listeCategories')]
    public function listeCategories(CategorieRepository $repoCate): Response
    {
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        $categoriesAll = $repoCate->findAll();
        return $this->render('admin/categories/listeCategories.html.twig', compact('categoriesAll', 'typesVideos', 'categories'),
      );
    }

    #[Route('/adminvideo/listeTypes', name: 'listeTypes')]
    public function listeTypes(TypeVideoRepository $repoType): Response
    {
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        $typeAll = $repoType->findAll();
        return $this->render('admin/types/listeTypes.html.twig', compact('typeAll', 'typesVideos', 'categories'),
      );
    }




    #[Route('/adminvideo/categorie/new', name:'categorie.new', methods: ['get', 'post'])]
    public function newCategorie(Request $request,EntityManagerInterface $manager): Response{
        //create a new video 
        $categorie = new categorie();
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
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

           return $this->redirectToRoute("admin_listeCategories");
        }
        return $this->render('admin/categories/new.html.twig',[
            'form'=>$form->createView(),
            'categories' => $categories,
            'typesVideos' => $typesVideos
        ]);
    }

    #[Route('/adminvideo/categorie/edit/{id}', name: 'categorie.edit', methods: ['get', 'post'])]
    public function editCategorie(CategorieRepository $repoCate,Int $id,EntityManagerInterface $manager,Request $request,Security $security): Response{

        $categorie = $repoCate->findOneBy(["id" => $id ]);
        $form = $this->createForm(CategorieType::class,$categorie);


        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $categorie = $form->getData();
            
            $manager->persist($categorie);
            $manager->flush();
            
            $this->addFlash("success","La catégorie a bien été modifiée !");

           return $this->redirectToRoute("admin_listeCategories");
        }
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        return $this->render('admin/categories/edit.html.twig',[
            'form'=>$form->createView(),
            'categories' => $categories,
            'typesVideos' => $typesVideos,
        ]);
    }

    #[Route('/adminvideo/categorie/delete/{id}', name: 'categorie.delete', methods:['GET'])]
    public function deleteCategorie(EntityManagerInterface $manager,Categorie $categorie) :Response{

        if(!$categorie){
            $this->addFlash("Warning ","categorie have not been deleted :( ");
            return $this->redirectToRoute("admin_listeCategories");
        }
        $manager->remove($categorie);
        $manager->flush();
        $this->addFlash("success","categorie delete successfully :)");
        return $this->redirectToRoute("admin_listeCategories");
    }


    #[Route('/adminvideo/type/delete/{id}', name: 'type.delete', methods:['GET'])]
    public function deleteType(EntityManagerInterface $manager,TypeVideo $type) :Response{

        if(!$type){
            $this->addFlash("Warning "," Le type n'a pas pu être supprimé :( ");
            return $this->redirectToRoute("admin_listeTypes");
        }
        $manager->remove($type);
        $manager->flush();
        $this->addFlash("success","Type bien supprimé :)");
        return $this->redirectToRoute("admin_listeTypes");
    }

    #[Route('/adminvideo/type/edit/{id}', name: 'type.edit', methods: ['get', 'post'])]
    public function editType(TypeVideoRepository $repoType, Int $id,EntityManagerInterface $manager,Request $request): Response{

        $type = $repoType->findOneBy(["id" => $id ]);
        $form = $this->createForm(TypeType::class, $type);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $type = $form->getData();
            
            $manager->persist($type);
            $manager->flush();
            
            $this->addFlash("success","La catégorie a bien été modifiée !");

           return $this->redirectToRoute("admin_listeTypes");
        }
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        return $this->render('admin/types/edit.html.twig',[
            'form'=>$form->createView(),
            'categories' => $categories,
            'typesVideos' => $typesVideos
        ]);
    }

    #[Route('/adminvideo/type/new', name:'type.new', methods: ['get', 'post'])]
    public function newType(Request $request,EntityManagerInterface $manager): Response{
        //create a new video 
        $type = new TypeVideo();
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        //bind form with videoType reference
        $form = $this->createForm(TypeType::class, $type);

        //send request to the database
        $form->handleRequest($request);
        //if is submitt is clicked and all valid in form
        if($form->isSubmitted() && $form->isValid()) {
            $type = $form->getData();
            //manager send new video "object" in the database
            $manager->persist($type);
            $manager->flush();
            
            $this->addFlash("success","categorie added successfully");

           return $this->redirectToRoute("admin_listeTypes");
        }
        return $this->render('admin/types/new.html.twig',[
            'form'=>$form->createView(),
            'categories' => $categories,
            'typesVideos' => $typesVideos
        ]);
    }


    #[Route('/video/delete/{id}', name: 'video.del', methods:['GET'])]
    public function delete(EntityManagerInterface $manager,Video $video) :Response{
        
        if(!$video){
            $this->addFlash("Warning ","La vidéo n'a pas pu être supprimée:( ");
            return $this->redirectToRoute("admin_listeVideo");
        }
        $manager->remove($video);
        $manager->flush();
        $this->addFlash("success","Video bien supprimée :)");
        return $this->redirectToRoute("admin_listeVideo");
    }



    #[Route('/video/edit/{id}', name: 'video.edit', methods: ['get', 'post'])]
    public function edit(VideoRepository $repoVideo,Int $id,EntityManagerInterface $manager,Request $request): Response{

        $video = $repoVideo->findOneBy(["id" => $id ]);
        $form = $this->createForm(VideoType::class,$video);
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $video = $form->getData();
            $categories = $form->get('categories')->getData();
            //manager send new video "object" in the database
            foreach ($video->getCategories() as $categorie) {
              $categorie->addVideo($video);
            }
            $manager->persist($video);
            $manager->flush();
            
            $this->addFlash("success","Video bien modifiée");

           return $this->redirectToRoute("admin_listeVideo");
        }

        return $this->render('admin/films/edit.html.twig',[
            'form'=>$form->createView(),
            'categories' => $categories,
            'typesVideos' => $typesVideos
        ]);
    }

    #[Route('/stats', name: 'stats')]
    public function stats(UserRepository $repoUser, AbonnementRepository $repoAbo, CategorieRepository $cate, CinemaRepository $cine, TypeVideoRepository $repoType, VideoRepository $repoVideo): Response
    {
        $nbUser = $repoUser->countUsers();
        $nbAbo = $repoAbo->countAbonnements();
        $nbCine = $cine->countCinemas();
        $nbCategorie = $cate->countCategories();
        $nbType = $repoType->countTypes();
        $nbVideo = $repoVideo->countVideos();
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        $categoriesWithVideoCount = $repoVideo->getCategoriesWithVideoCount();

        return $this->render('admin/stats.html.twig', [
          'categories' => $categories,
          'typesVideos' => $typesVideos,
          'nbUser' => $nbUser,
          'nbCategorie' => $nbCategorie,
          'nbCine' => $nbCine,
          'nbAbo' => $nbAbo,
          'nbType' => $nbType,
          'nbVideo' => $nbVideo,
          'categoriesWithVideoCount' => $categoriesWithVideoCount,
        ]);
    }
}
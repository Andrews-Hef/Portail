<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Video;
use App\Form\VideoType;
use App\Entity\Categorie;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\UserRepository;
use App\Repository\VideoRepository;
use App\Repository\CategorieRepository;
use App\Repository\TypeVideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VideoController extends AbstractController
{
  private $categories;
  private $typesVideos;
    
    public function __construct(CategorieRepository $cateRepo, TypeVideoRepository $typeRepo)
    {
        $this->categories = $cateRepo->findAll();
        $this->typesVideos = $typeRepo->findAll();

    }
    
    #[Route('/video',name:'video.index')]
    public function index(VideoRepository $repoVideo): Response
    {
        $videos = $repoVideo->findAll();
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        return $this->render('video/index.html.twig', [
            'videos' => $videos,
            'categories' => $categories,
            'typesVideos' => $typesVideos
        ]);
    }

    #[Route('/video/show/{id}',name:'video.show')]
    public function showFilm(VideoRepository $repoVideo, CommentaireRepository $repoCom, Int $id, Request $request, EntityManagerInterface $manager, CategorieRepository $repoCate, UserRepository $repoUser, Security $security): Response
    {
      $user = $security->getUser();
      $dateDuJour = new \DateTime();
      if($user != null){
        if($user->getDateFinAbonnement() < $dateDuJour && $user->getAbonnement() != null){
          $user->setAbonnement(null);
          $user->setDateFinAbonnement(null);
          $manager->persist($user);
          $manager->flush();
        }
      }
        $videosAll = $repoVideo->findAll();
        $commentaires = $repoCom->findByVideoscom($id);
        $categories = $repoCate->findAll();
        $video = $repoVideo->findVideoById($id);
        $commentaire = new Commentaire();
        $commentaire->setVideoscom($video);
        $commentaire->setUsers($user);
        
        $categories = $video->getCategories();

        if (!$categories->isEmpty()) {
            $firstCategory = $categories->first();
            $user->addCategoriePref($firstCategory);
        }

        if ($firstCategory != null) {
          $cateprefuser = $video->getCategories($firstCategory);
        }
        

        if($user != null){
          if (!$user->getVideoPref()->contains($video)) {
            $user->addVideoPref($video);
            $manager->persist($user);
            $manager->flush();
          }
        }

        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $video = $form->getData();
            $manager->persist($commentaire);
            $manager->flush();

            return $this->redirectToRoute('video.show', ['id' => $id]);
        }
        $typesVideos = $this->typesVideos;
        return $this->render('video/showVideo.html.twig',[
            'video' => $video,
            'categories' => $categories,
            'form' => $form->createView(),
            'typesVideos' => $typesVideos,
            'user' => $user,
            'commentaire' => $commentaires,
            'videosAll' => $videosAll
        ]);
    }

    #[Route('/video/new', name:'video.new', methods: ['get', 'post'])]
    public function new(Request $request,EntityManagerInterface $manager): Response{
        // Creation d'un objet video
        $video = new Video();
        
        // Relie le formulaire au type de video
        $form = $this->createForm(VideoType::class,$video);
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        // Demande de requête a la bdd
        $form->handleRequest($request);
        // Si le form est bien valide, envoie les données a la bdd
        if($form->isSubmitted() && $form->isValid()) {
            $video = $form->getData();
            // Le manager envoie l'objet video dans la bdd
            $manager->persist($video);
            $manager->flush();
            
            $this->addFlash("success","Video added successfully");

           return $this->redirectToRoute("video.index");
        }
        return $this->render('video/new.html.twig',[
            'form'=>$form->createView(),
            'categories' => $categories,
            'typesVideos' => $typesVideos
        ]);
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

    #[Route('/video/comdel/{id}', name: 'com_del.del', methods:['GET'])]
    public function comdelete(EntityManagerInterface $manager, Commentaire $commentaire) :Response{
        
        $id = $commentaire->getVideoscom();

        if(!$commentaire){
            $this->addFlash("Warning ","Video have not been deleted :( ");
            return $this->redirectToRoute("video.index");
        }
        
        // Vérifier si l'ID de la vidéo est valide
        $videoId = $id->getId();
        $video = $manager->getRepository(Video::class)->find($videoId);
        if (!$video) {
            $this->addFlash("warning", "Video not found");
            return $this->redirectToRoute("video.index");
        }

        $manager->remove($commentaire);
        $manager->flush();
        $this->addFlash("success","Commentaire supprimé !");
        return $this->redirectToRoute('video.show', ['id' => $videoId]);
    }


    #[Route('/video/delete/{id}', name: 'video.del', methods:['GET'])]
    public function delete(EntityManagerInterface $manager,Video $video) :Response{
        
        if(!$video){
            $this->addFlash("Warning ","Video have not been deleted :( ");
            return $this->redirectToRoute("video.index");
        }
        $manager->remove($video);
        $manager->flush();
        $this->addFlash("success","Video deleted successfully :)");
        return $this->redirectToRoute("video.index");
    }
    
    #[Route('/autocomplete_titres', name: 'autocomplete_titres')]

    public function autocompleteTitres(Request $request, EntityManagerInterface $entityManager)
  {
    $term = $request->query->get('term');
    // Exemple de requête pour récupérer les titres de la base de données
    $videos = $entityManager->getRepository(Video::class)
    ->createQueryBuilder('v')
    ->select('v.id, v.titre')
    ->where('v.titre LIKE :term')
    ->setParameter('term', '%'.$term.'%')
    ->getQuery()
    ->getResult();
  
  $results = array_map(fn($video) => ['id' => $video['id'], 'value' => $video['titre']], $videos);
  return new JsonResponse($results);
  }

}

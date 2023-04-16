<?php

namespace App\Controller;

use App\Entity\Video;
use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\VideoRepository;
use App\Repository\CategorieRepository;
use App\Repository\TypeVideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
  private $categories;
  private $typesVideos;
    
    public function __construct(CategorieRepository $cateRepo, TypeVideoRepository $typeRepo)
    {
        $this->categories = $cateRepo->findAll();
        $this->typesVideos = $typeRepo->findAll();
    }


    #[Route('/categorie/new', name:'categorie.new', methods: ['get', 'post'])]
    public function new(Request $request,EntityManagerInterface $manager): Response{
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

           return $this->redirectToRoute("categorie.index");
        }
        return $this->render('categorie/new.html.twig',[
            'form'=>$form->createView(),
            'categories' => $categories,
            'typesVideos' => $typesVideos
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
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        return $this->render('categorie/edit.html.twig',[
            'form'=>$form->createView(),
            'categories' => $categories,
            'typesVideos' => $typesVideos
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


    
    #[Route('/categorie_show/{idCategorie}', name: 'categorieShow')]
    public function allFilmCategorieVoulu(VideoRepository $repoVideo, $idCategorie, CategorieRepository $repoCate, Request $request)
    {
      $categories = $repoCate->findAll();
      $titres = $request->get('titre');
      $videos = $repoVideo->findVideoAllFilmFromOneCategorie($idCategorie, $titres);
      $laCategorie = $repoCate->findLaCategorie($idCategorie);
      $typesVideos = $this->typesVideos;
      return $this->render('catalogue/catalogueShow.html.twig', [
          'videos' => $videos,
          'categories' => $categories,
          'controller_name' => 'CategorieController',
          'idCategorie' => $idCategorie,
          'typesVideos' => $typesVideos,
          'laCategorie' => $laCategorie
      ]);
  }
  
    #[Route('/ma-route', name: 'test')]
    public function yourAction(Request $request, VideoRepository $repoVideo)
  {
      if ($request->isXmlHttpRequest()) {
          $inputValue = $request->request->get('inputValue');
          $videos = $repoVideo->findVideoByName($inputValue);
          $categories = $this->categories;
          $typesVideos = $this->typesVideos;
          // Do something with the input value
  
          // Return a JSON response to the client
          return new JsonResponse([
              'success' => true,
              'data' => [
                'videos' => $videos,
                'categories' => $categories,
                'typesVideos' => $typesVideos,
                'content' => $this->renderView('catalogue/test2.html.twig', compact('videos'))
                  // Return any data that you want to send back to the client
              ]
          ]);
      }
      $categories = $this->categories;
      $typesVideos = $this->typesVideos;
      return $this->render('catalogue/test2.html.twig', [
        'controller_name' => 'AccueilController',
        'categories' => $categories,
        'typesVideos' => $typesVideos
      ]);
  }

  #[Route('/autocomplete_titres2', name: 'autocomplete_titres2')]

  public function autocompleteTitres(Request $request, EntityManagerInterface $entityManager)
{
    $idCategorie = $request->get('idCategorie');
    $term = $request->query->get('term');
    // Exemple de requête pour récupérer les titres de la base de données
    $videos  = $entityManager->getRepository(Video::class)
        ->createQueryBuilder('v')
        ->select('v.id, v.titre')
        ->leftJoin('v.categories', 'r')
        ->where('v.titre LIKE :term')
        ->setParameter('term', '%'.$term.'%')
        ->andWhere('r.id = :cateId')
        ->setParameter('cateId', $idCategorie)
        ->getQuery()
        ->getResult();

    $results = array_map(fn($video) => ['id' => $video['id'], 'value' => $video['titre']], $videos);
return new JsonResponse($results);
}
    
}

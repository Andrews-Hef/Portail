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

class TypeController extends AbstractController
{
  private $categories;
  private $typesVideos;
    
    public function __construct(CategorieRepository $cateRepo, TypeVideoRepository $typeRepo)
    {
        $this->categories = $cateRepo->findAll();
        $this->typesVideos = $typeRepo->findAll();
    }


    #[Route('/type_show/{idType}', name: 'typeShow')]
    public function allFilmTypeVoulu(VideoRepository $repoVideo, $idType, CategorieRepository $repoCate, Request $request, TypeVideoRepository $typeRepo)
    {
      $categories = $repoCate->findAll();
      $titres = $request->get('titre');
      $videos = $repoVideo->findVideoAllFilmFromOneType($idType, $titres);
      $leType = $typeRepo->findLeType($idType);
      $typesVideos = $this->typesVideos;
      return $this->render('catalogue/catalogueTypeShow.html.twig', [
          'videos' => $videos,
          'categories' => $categories,
          'controller_name' => 'TypeController',
          'idType' => $idType,
          'typesVideos' => $typesVideos,
          'leType' => $leType
      ]);
  }
  
    #[Route('/ma-route2', name: 'test2')]
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
                'content' => $this->renderView('catalogue/test3.html.twig', compact('videos'))
                  // Return any data that you want to send back to the client
              ]
          ]);
      }
      $categories = $this->categories;
      $typesVideos = $this->typesVideos;
      return $this->render('catalogue/test2.html.twig', [
        'controller_name' => 'TypeController',
        'categories' => $categories,
        'typesVideos' => $typesVideos
      ]);
  }

  #[Route('/autocomplete_titres3', name: 'autocomplete_titres3')]

  public function autocompleteTitres(Request $request, EntityManagerInterface $entityManager)
{
    $idType = $request->get('idType');
    $term = $request->query->get('term');
    // Exemple de requête pour récupérer les titres de la base de données
    $videos  = $entityManager->getRepository(Video::class)
        ->createQueryBuilder('v')
        ->select('v.id, v.titre')
        ->leftJoin('v.typeVideo', 'r')
        ->where('v.titre LIKE :term')
        ->setParameter('term', '%'.$term.'%')
        ->andWhere('r.id = :typeId')
        ->setParameter('typeId', $idType)
        ->getQuery()
        ->getResult();

    $results = array_map(fn($video) => ['id' => $video['id'], 'value' => $video['titre']], $videos);
return new JsonResponse($results);
}

}
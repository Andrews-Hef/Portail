<?php

namespace App\Controller;

use App\Entity\Video;
use App\Repository\VideoRepository;
use App\Repository\CategorieRepository;
use App\Repository\TypeVideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
  #[Route('/', name: 'accueil')]
  public function accueil(VideoRepository $repoVideo, CategorieRepository $repoCategorie, TypeVideoRepository $repoTypeVideo, Request $request)
  {
    $videos = $repoVideo->findAll();
    $categories = $repoCategorie->findAll();
    $typesVideos = $repoTypeVideo->findAll();
    $allFilm = $repoVideo->findVideoAllFilmDemo();
    $allSerie = $repoVideo->findVideoAllSerieDemo();
    $allAnime =$repoVideo->findVideoAllAnimeDemo();
    

      return $this->render('accueil/index.html.twig', [
        'videos' => $videos,
        'categories' => $categories,
        'typesVideos' => $typesVideos,
        'allFilm' => $allFilm,
        'allSerie' => $allSerie,
        'allAnime' => $allAnime,
        'controller_name' => 'AccueilController',
      ]);
  }

  #[Route('/catalogue', name: 'catalogue')]
  public function allVideo(VideoRepository $repoVideo, CategorieRepository $repoCategorie, TypeVideoRepository $repoTypeVideo, Request $request , CacheInterface $cache, ?Profiler $profiler)
  {
    // On définit le nombre d'éléments par page
    $limit = 10;

    // On récupère le numéro de page
    $page = (int)$request->query->get("page", 1);

    // On récupère les filtres
    $filters = $request->get("categories");
    $typeFilters = $request->get('typesVideos');
    $titres = $request->get('titre');

    $videos = $repoVideo->findAllVideoCategories($page, $limit, $filters, $typeFilters, $titres);

    $total = $repoVideo->getTotalAnnonces($filters, $typeFilters, $titres);

    if($request->get('ajax')){
      return new JsonResponse([
          'content' => $this->renderView('catalogue/_content.html.twig', compact('videos', 'total', 'limit', 'page', 'titres'))
      ]);
  }

    // On va chercher toutes les catégories
    $categories = $cache->get('categories_list', function(ItemInterface $item) use($repoCategorie){
      $item->expiresAfter(3600);

      return $repoCategorie->findAll();
    });

    $typesVideos = $cache->get('types_list', function(ItemInterface $item) use($repoTypeVideo){
      $item->expiresAfter(3600);

      return $repoTypeVideo->findAll();
    });

      return $this->render('catalogue/catalogue.html.twig', [
        'videos' => $videos,
        'categories' => $categories,
        'typesVideos' => $typesVideos,
        'total' => $total,
        'limit' => $limit,
        'page' => $page,
        'controller_name' => 'AccueilController',
      ]);
  }

  #[Route('/catalogue/film', name: 'catalogueFilm')]
  public function allFilm(VideoRepository $repoVideo)
  {
    $videos = $repoVideo->findVideoAllFilm();
      return $this->render('catalogue/catalogueFilm.html.twig', [
        'videos' => $videos,
        'controller_name' => 'AccueilController',
      ]);
  }

  #[Route('/catalogue/serie', name: 'catalogueSerie')]
  public function allSerie(VideoRepository $repoVideo)
  {
    $videos = $repoVideo->findVideoAllSerie();
      return $this->render('catalogue/catalogueSerie.html.twig', [
        'videos' => $videos,
        'controller_name' => 'AccueilController',
      ]);
  }

  #[Route('/catalogue/anime', name: 'catalogueAnime')]
  public function allAnime(VideoRepository $repoVideo)
  {
    $videos = $repoVideo->findVideoAllAnime();
      return $this->render('catalogue/catalogueAnime.html.twig', [
        'videos' => $videos,
        'controller_name' => 'AccueilController',
      ]);
  }








  #[Route('/your-route', name: 'test')]
  public function yourAction(Request $request, VideoRepository $repoVideo)
{
    if ($request->isXmlHttpRequest()) {
        $inputValue = $request->request->get('inputValue');
        $videos = $repoVideo->findVideoByName($inputValue);

        // Do something with the input value

        // Return a JSON response to the client
        return new JsonResponse([
            'success' => true,
            'data' => [
              'videos' => $videos,
              'content' => $this->renderView('catalogue/test.html.twig', compact('videos'))
                // Return any data that you want to send back to the client
            ]
        ]);
    }
    return $this->render('catalogue/test.html.twig', [
      'controller_name' => 'AccueilController',
    ]);
}








#[Route('/catalogue/test', name: 'cataTest')]
  public function allVideoTest(VideoRepository $repoVideo, CategorieRepository $repoCategorie, TypeVideoRepository $repoTypeVideo, Request $request , CacheInterface $cache, ?Profiler $profiler)
  {
    // On définit le nombre d'éléments par page
    $limit = 10;

    // On récupère le numéro de page
    $page = (int)$request->query->get("page", 1);

    
    
    $filters = $request->get('categoriesForm1');
    $typeFilters = $request->get('categoriesForm2');



    $titres = $request->get('titre');
    

    
    $videos = $repoVideo->findAllVideoCategories($page, $limit, $filters, $typeFilters, $titres);

    $total = $repoVideo->getTotalAnnonces($filters, $typeFilters, $titres);

    if($request->get('ajax')){
      return new JsonResponse([
          'content' => $this->renderView('catalogue/_content.html.twig', compact('videos', 'total', 'limit', 'page', 'titres', 'typeFilters', 'filters'))
      ]);
  }

    // On va chercher toutes les catégories
    $categories = $cache->get('categories_list', function(ItemInterface $item) use($repoCategorie){
      $item->expiresAfter(3600);

      return $repoCategorie->findAll();
    });

    $typesVideos = $cache->get('types_list', function(ItemInterface $item) use($repoTypeVideo){
      $item->expiresAfter(3600);

      return $repoTypeVideo->findAll();
    });

      return $this->render('catalogue/testCatalogue.html.twig', [
        'videos' => $videos,
        'categories' => $categories,
        'typesVideos' => $typesVideos,
        'total' => $total,
        'limit' => $limit,
        'page' => $page,
        'controller_name' => 'cataTest',
      ]);
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
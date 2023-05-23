<?php

namespace App\Controller;

use App\Entity\Video;
use App\Repository\VideoRepository;
use App\Repository\CategorieRepository;
use App\Repository\TypeVideoRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
  private $categories;
  private $typesVideos;
    
    public function __construct(CategorieRepository $cateRepo, TypeVideoRepository $typeRepo)
    {
        $this->categories = $cateRepo->findAll();
        $this->typesVideos = $typeRepo->findAll();
    }
  #[Route('/', name: 'accueil')]
  public function accueil(VideoRepository $repoVideo, CategorieRepository $repoCategorie, EntityManagerInterface $manager, TypeVideoRepository $repoTypeVideo, Request $request, Security $security, UserRepository $repoUser)
  {
    $videos = $repoVideo->findAll();
    $categories = $repoCategorie->findAll();
    $typesVideos = $repoTypeVideo->findAll();
    $allFilm = $repoVideo->findVideoAllFilmDemo();
    $allSerie = $repoVideo->findVideoAllSerieDemo();
    $allAnime =$repoVideo->findVideoAllAnimeDemo();
    $user = $security->getUser();
    $dateDuJour = new \DateTime();
    if($user != null){
      $maxViewedCategories = $repoUser->findCategoriesWithMaxViewsByUser($user->getId());
      if($maxViewedCategories!= null){
      $videoReccomand = $repoVideo->findVideoRecommand($maxViewedCategories);
      shuffle($videoReccomand);
    }
    else{
      $videoReccomand = null;
    }
    }
    else{
      $maxViewedCategories=null;
      $videoReccomand=null;
    }
    


    if($user != null){
      if($user->getDateFinAbonnement() < $dateDuJour && $user->getAbonnement() != null){
        $user->setAbonnement(null);
        $user->setDateFinAbonnement(null);
        $manager->persist($user);
        $manager->flush();
      }
    }

      return $this->render('accueil/index.html.twig', [
        'videos' => $videos,
        'categories' => $categories,
        'typesVideos' => $typesVideos,
        'allFilm' => $allFilm,
        'allSerie' => $allSerie,
        'allAnime' => $allAnime,
        'controller_name' => 'AccueilController',
        'maxViewedCategories' => $maxViewedCategories,
        'videoReccomand' => $videoReccomand,  
      ]);
  }






#[Route('/catalogue', name: 'cataTest')]
  public function allVideoTest(VideoRepository $repoVideo, CategorieRepository $repoCategorie, 
  TypeVideoRepository $repoTypeVideo, Request $request , CacheInterface $cache, ?Profiler $profiler)
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

      return $this->render('catalogue/catalogue.html.twig', [
        'videos' => $videos,
        'categories' => $categories,
        'typesVideos' => $typesVideos,
        'total' => $total,
        'limit' => $limit,
        'page' => $page,
        'controller_name' => 'cataTest',
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




#[Route('/confidentialite', name: 'confidentialite')]
  public function confidentialite(CategorieRepository $repoCategorie, TypeVideoRepository $repoTypeVideo)
  {
    $categories = $repoCategorie->findAll();
    $typesVideos = $repoTypeVideo->findAll();
    

      return $this->render('legal/confidentialite.html.twig', [
        'categories' => $categories,
        'typesVideos' => $typesVideos,
        'controller_name' => 'AccueilController',
      ]);
  }


  #[Route('/mentionsLegales', name: 'mentionsLegales')]
  public function mentionsLegales(CategorieRepository $repoCategorie, TypeVideoRepository $repoTypeVideo)
  {
    $categories = $repoCategorie->findAll();
    $typesVideos = $repoTypeVideo->findAll();
    

      return $this->render('legal/mentionsLegales.html.twig', [
        'categories' => $categories,
        'typesVideos' => $typesVideos,
        'controller_name' => 'AccueilController',
      ]);
  }

  #[Route('/infoLegales', name: 'infoLegales')]
  public function infoLegales(CategorieRepository $repoCategorie, TypeVideoRepository $repoTypeVideo)
  {
    $categories = $repoCategorie->findAll();
    $typesVideos = $repoTypeVideo->findAll();
    

      return $this->render('legal/infoLegales.html.twig', [
        'categories' => $categories,
        'typesVideos' => $typesVideos,
        'controller_name' => 'AccueilController',
      ]);
  }

}
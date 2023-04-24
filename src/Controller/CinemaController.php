<?php

namespace App\Controller;

use App\Service\CallApiService;
use App\Repository\CategorieRepository;
use App\Repository\TypeVideoRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CinemaController extends AbstractController
{
    private $categories;
    private $typesVideos;
      
      public function __construct(CategorieRepository $cateRepo, TypeVideoRepository $typeRepo)
      {
          $this->categories = $cateRepo->findAll();
          $this->typesVideos = $typeRepo->findAll();
      }
 
    #[Route('/cinema', name: 'app_cinema')]
    public function index(CallApiService $apiService): Response
    {   
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        $results=$apiService->getNowPlaying();
        $allCinema=$apiService->getallCinema();
        return $this->render('cinema/index.html.twig', [
            'controller_name' => 'CinemaController',
            'allCinema'=>$allCinema,
            'nowPlaying'=> $results,           
            'categories' => $categories,
            'typesVideos' => $typesVideos
        ]);
    }
    #[Route('/cinema/allCinema', name: 'cinema.findAll')]
    public function findCine(CallApiService $apiService): Response
    {   
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        $results=$apiService->getallCinema();
        return $this->render('cinema/findCine.html.twig', [
            'controller_name' => 'CinemaController',
            'allCinema'=>$results,
            'categories' => $categories,
            'typesVideos' => $typesVideos
        ]);
    }

    #[Route('/cinema/movie/{id}', name: 'cinema.MovieView')]
    public function getOneMovie(CallApiService $apiService,$id): Response
    {   
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        
        $results=$apiService->getOneMovie($id);
        return $this->render('cinema/movie.html.twig', [
            'controller_name' => 'CinemaController',
            'Movie'=>$results,
            'categories' => $categories,
            'typesVideos' => $typesVideos
        ]);
    }
     
}

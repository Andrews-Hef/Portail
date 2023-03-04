<?php

namespace App\Controller;

use App\Repository\VideoRepository;
use App\Repository\CategorieRepository;
use App\Repository\TypeVideoRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
  #[Route('/', name: 'accueil')]
  public function accueil(VideoRepository $repoVideo, CategorieRepository $repoCategorie, TypeVideoRepository $repoTypeVideo)
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
        'typesVideo' => $typesVideos,
        'allFilm' => $allFilm,
        'allSerie' => $allSerie,
        'allAnime' => $allAnime,
        'controller_name' => 'AccueilController',
      ]);
  }

  #[Route('/catalogue/', name: 'catalogue')]
  public function allVideo(VideoRepository $repoVideo)
  {
    $videos = $repoVideo->findAll();
      return $this->render('catalogue/catalogue.html.twig', [
        'videos' => $videos,
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


}

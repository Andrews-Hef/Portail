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

      return $this->render('accueil/index.html.twig', [
        'videos' => $videos,
        'categories' => $categories,
        'typesVideo' => $typesVideos,
        'controller_name' => 'AccueilController',

      ]);
  }
}

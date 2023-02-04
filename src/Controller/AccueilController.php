<?php

namespace App\Controller;

use App\Repository\VideoRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
  #[Route('/', name: 'accueil')]
  public function accueil(VideoRepository $repoVideo)
  {
    $videos = $repoVideo->findAll();

      return $this->render('accueil/index.html.twig', [
        'videos' => $videos,
        'controller_name' => 'AccueilController',

      ]);
  }
}

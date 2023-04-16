<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\TypeVideoRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/profil', name: 'profile_')]
class ProfileController extends AbstractController
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
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'Profil de l\'utilisateur',
            'categories' => $categories,
            'typesVideos' => $typesVideos
        ]);
    }
}
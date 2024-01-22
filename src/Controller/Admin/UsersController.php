<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\CategorieRepository;
use App\Repository\TypeVideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/utilisateurs', name: 'admin_users_')]
class UsersController extends AbstractController
{
  private $categories;
  private $typesVideos;
    
    public function __construct(CategorieRepository $cateRepo, TypeVideoRepository $typeRepo)
    {
        $this->categories = $cateRepo->findAll();
        $this->typesVideos = $typeRepo->findAll();
    }
    
    #[Route('/', name: 'index')]
    public function index(UserRepository $usersRepository): Response
    {
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        $users = $usersRepository->findAll();
        return $this->render('admin/users/index.html.twig', compact('users', 'typesVideos', 'categories'),
      );
    }

    #[Route('/editUser/{id}', name: 'user_edit')]
    public function edit(Request $request, UserRepository $repoUser, EntityManagerInterface $manager, Int $id)
    {
      $user = $repoUser->findOneBy(["id" => $id ]);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $user = $form->getData();
          
            $manager->persist($user);
            $manager->flush();

            $this->addFlash("success","user updated successfully");
            return $this->redirectToRoute('admin_users_index');
        }
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        return $this->render('admin/users/edit.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories,
            'typesVideos' => $typesVideos
        ]);
    }
}
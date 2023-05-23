<?php

namespace App\Controller;

use DateTime;
use App\Entity\Abonnement;
use App\Service\JWTService;
use App\Form\AbonnementType;
use App\Service\SendMailService;
use App\Repository\VideoRepository;
use App\Repository\CategorieRepository;
use App\Repository\TypeVideoRepository;
use App\Repository\AbonnementRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security\UserAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class AbonnementController extends AbstractController
{
  private $categories;
  private $typesVideos;
    
    public function __construct(CategorieRepository $cateRepo, TypeVideoRepository $typeRepo)
    {
        $this->categories = $cateRepo->findAll();
        $this->typesVideos = $typeRepo->findAll();
    }
    
    #[Route('/abonnement', name: 'app_abonnement')]
    public function index(AbonnementRepository $repoAbo): Response
    {   
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        $abonnements = $repoAbo->findAll();
        return $this->render('abonnement/index.html.twig', [
            'abonnements'=>$abonnements,
            'categories' => $categories,
            'typesVideos' => $typesVideos
        ]);
    }


    #[Route('/abonnement/new', name:'abonnement.new', methods: ['get', 'post'])]
    public function new(Request $request,EntityManagerInterface $manager): Response{
       
        $abo = new Abonnement();
        
        $form = $this->createForm(AbonnementType::class,$abo);

        //send request to the database
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $abo = $form->getData();
            
            $manager->persist($abo);
            $manager->flush();
            
            $this->addFlash("success","Abonnement added successfully");

           return $this->redirectToRoute("Abonnement.index");
        }
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        return $this->render('abonnement/new.html.twig',[
            'form'=>$form->createView(),
            'categories' => $categories,
            'typesVideos' => $typesVideos
        ]);
    }

    #[Route('/abonnement/edit/{id}', name: 'abonnement.edit', methods: ['get', 'post'])]
    public function edit(AbonnementRepository $repoAbo,Int $id,EntityManagerInterface $manager,Request $request): Response{

        $abo = $repoAbo->findOneBy(["id" => $id ]);
        $form = $this->createForm(AbonnementType::class,$abo);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $abo = $form->getData();
            
            $manager->persist($abo);
            $manager->flush();
            
            $this->addFlash("success","Abonnement updated successfully");

           return $this->redirectToRoute("Abonnement.index");
        }
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        return $this->render('abonnement/edit.html.twig',[
            'form'=>$form->createView(),
            'categories' => $categories,
            'typesVideos' => $typesVideos
        ]);
    }
    #[Route('/abonnement/delete/{id}', name: 'abonnement.del', methods:['GET'])]
    public function delete(EntityManagerInterface $manager,Abonnement $abonnement) :Response{

        if(!$abonnement){
            $this->addFlash("Warning ","abonnement have not been deleted :( ");
            return $this->redirectToRoute("abonnement.index");
        }
        $manager->remove($abonnement);
        $manager->flush();
        $this->addFlash("success","abonnement delete successfully :)");
        return $this->redirectToRoute("abonnement.index");
    }


    #[Route('/abonnement/listeAbonnements', name: 'listeAbonnements')]
    public function listeAbonnement(AbonnementRepository $repoAbo): Response
    {
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        $allAbonnement = $repoAbo->findAll();
        return $this->render('boutique/index.html.twig', compact('allAbonnement', 'typesVideos', 'categories'),
      );
    }

    #[Route('/abonnement/paiement/{id}', name: 'paiement')]
    public function paiement(AbonnementRepository $repoAbo, int $id): Response
    {
        $abo = $repoAbo->findOneBy(["id" => $id ]);
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        $allAbonnement = $repoAbo->findAll();
        return $this->render('boutique/paiement.html.twig', compact('allAbonnement', 'typesVideos', 'categories', 'abo'),
      );
    }


    #[Route('/paiementAccept/{id}', name: 'paiementAccept')]
    public function paiementAccept(VideoRepository $repoVideo, CategorieRepository $repoCategorie, EntityManagerInterface $manager, TypeVideoRepository $repoTypeVideo, AbonnementRepository $repoAbo,Request $request, EntityManagerInterface $entityManager, SendMailService $mail, Security $security, int $id, UserRepository $repoUser): Response
    {
      $videos = $repoVideo->findAll();
      $categories = $repoCategorie->findAll();
      $typesVideos = $repoTypeVideo->findAll();
      $allFilm = $repoVideo->findVideoAllFilmDemo();
      $allSerie = $repoVideo->findVideoAllSerieDemo();
      $allAnime =$repoVideo->findVideoAllAnimeDemo();
      $user = $security->getUser();
      $abo = $repoAbo->findOneBy(["id" => $id ]);
      $prix = $abo->getPrix();
      $titre = $abo->getLibelleAbonnement();
      $mail->send(
        'demoineret.denis78@gmail.com',
        $user->getEmail(),
        'Activation de votre compte sur le site Portail',
        'paiementAccept',
        compact('user', 'prix', 'titre')
      );

      $dateDuJour = new DateTime();

      // Ajouter un mois à la date du jour
      $dateDuJour->modify('+1 month');
      
      $user->setAbonnement($abo);
      $user->setDateFinAbonnement($dateDuJour);
      $entityManager->persist($user);
      $entityManager->flush();

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

      $allAbonnement = $repoAbo->findAll();

        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        $this->addFlash('success', 'Votre abonnement a bien été ajouté!');
        return $this->render('accueil/index.html.twig', [
            'categories' => $categories,
            'typesVideos' => $typesVideos,
            'allAbonnement' => $allAbonnement,
            'allFilm' => $allFilm,
            'allSerie' => $allSerie,
            'allAnime' => $allAnime,
            'videos' => $videos,
            'videoReccomand' => $videoReccomand

        ]);
    }
}

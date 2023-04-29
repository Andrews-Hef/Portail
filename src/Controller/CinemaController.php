<?php

namespace App\Controller;

use App\Form\ResearchFilmType;
use App\Service\CallApiService;
use App\Repository\CategorieRepository;
use App\Repository\TypeVideoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
    public function index(CallApiService $apiService,Request $request): Response
    {   
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;

        //api request
        $results=$apiService->getNowPlaying();
        $allCinema=$apiService->getallCinema();
        ///////////
            //data for  form
            $defaultData = ['message' => 'Type your message here'];
            $form = $this->createFormBuilder($defaultData)
            ->add('title', TextType::class)
            ->add('send', SubmitType::class)
            ->getForm();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // data is an array with "name", "email", and "message" keys
                $data = $form->getData();
                $data= $data['title'];
                return $this->redirectToRoute("cinema.researchMovie",['data'=>$data]);
            } 
            
        return $this->render('cinema/index.html.twig', [
            'controller_name' => 'CinemaController',
            'form'=>$form->createView(),
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
    #[Route('/cinema/researchCine', name: 'cinema.researchCine')]
    public function researchCine(): Response
    {   
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        return $this->render('cinema/researchCine.html.twig', [
            'controller_name' => 'CinemaController',
            'categories' => $categories,
            'typesVideos' => $typesVideos
        ]);
    }
    #[Route('/cinema/researchMovie', name: 'cinema.researchMovie')]
    public function researchMovie(CallApiService $apiService,Request $request): Response
    {   
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        //retrieve data from form 
        $Name=$request->query->get('data');
        //$Name=$Name['title'];
        $results=$apiService->researchMovie($Name);
        return $this->render('cinema/researchMovie.html.twig', [
            'controller_name' => 'CinemaController',
            'categories' => $categories,
            'typesVideos' => $typesVideos,
            'researchResults'=>$results
        ]);
    }
     
}

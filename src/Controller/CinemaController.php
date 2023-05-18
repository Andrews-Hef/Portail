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
use App\Repository\CinemaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class CinemaController extends AbstractController
{
    private $categories;
    private $typesVideos;

    //variable to keep track cinema information 
      
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
            //data for  form

            
        return $this->render('cinema/index.html.twig', [
            'controller_name' => 'CinemaController',
            'allCinema'=>$allCinema,
            'nowPlaying'=> $results,           
            'categories' => $categories,
            'typesVideos' => $typesVideos
        ]);
    }
    #[Route('/cinema/allCinema', name: 'cinema.findAll')]
    public function findCine(CallApiService $apiService,CinemaRepository $repoCine): Response
    {   
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        //$results=$apiService->getallCinema();
        $results= $repoCine->findAll();
        return $this->render('cinema/findCine.html.twig', [
            'controller_name' => 'CinemaController',
            'allCinema'=>$results,
            'categories' => $categories,
            'typesVideos' => $typesVideos,

        ]);
    }
    #[Route('/cinema/onecine', name: 'cinema.findOne')]
    public function findoneCine(Request $request): Response
    {   
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        // we retrieve data given in path invocation function
        $idCine=$request->query->get('id');
        $Name=$request->query->get('Name');
        $adr=$request->query->get('adresse');

        $defaultData = ['message' => 'Type your message here'];
        $form = $this->createFormBuilder($defaultData)
        ->add('title', TextType::class)
        ->add('id',HiddenType::class,[
            'data'=> $idCine,
        ])
        ->add('send', SubmitType::class)

        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            $id = $data['id'];
            $data= $data['title'];

            return $this->redirectToRoute("cinema.researchMovie",['data'=>$data,'id'=>$id]);
        } 

        return $this->render('cinema/oneCine.html.twig', [
            'controller_name' => 'CinemaController',
            'form'=>$form->createView(),
            'categories' => $categories,
            'typesVideos' => $typesVideos,
            'Adresse'=> $adr,
            'Name'=> $Name

        ]);
    }
    #

    #[Route('/cinema/movie/{id}', name: 'cinema.MovieView')]
    public function getOneMovie(CallApiService $apiService,$id,Request $request): Response
    {   
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;

        //retrieve cinema id to made final request
        $idCine=$request->query->get('idcine');

        $results=$apiService->getOneMovie($id);
        return $this->render('cinema/movie.html.twig', [
            'controller_name' => 'CinemaController',
            'Movie'=>$results,
            'categories' => $categories,
            'typesVideos' => $typesVideos,
            'idCine'=>$idCine
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
        $idCine=$request->query->get('id');
        $results=$apiService->researchMovie($Name);


        return $this->render('cinema/researchMovie.html.twig', [
            'controller_name' => 'CinemaController',
            'categories' => $categories,
            'typesVideos' => $typesVideos,
            'researchResults'=>$results,
            'idCine'=> $idCine
        ]);
    }
     
}

<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use App\Form\ResearchFilmType;
use App\Service\CallApiService;
use App\Service\SendMailService;
use App\Repository\CinemaRepository;
use App\Repository\CategorieRepository;
use App\Repository\TypeVideoRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


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
    public function index(CallApiService $apiService,Request $request,CinemaRepository $repoCine): Response
    {   
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;

        //api request
        $results=$apiService->getNowPlaying();


            
        return $this->render('cinema/index.html.twig', [
            'controller_name' => 'CinemaController',
            'nowPlaying'=> $results,           
            'categories' => $categories,
            'typesVideos' => $typesVideos
        ]);
    }
    /**
     * liste de tout nos cinema
     *
     * @param CallApiService $apiService
     * @param CinemaRepository $repoCine
     * @return Response
     */
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
    /**
     * page d'un cinema
     *
     * @param Request $request
     * @return Response
     */
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

    /**
     * affiche un film
     */
    #[Route('/cinema/movie/{id}', name: 'cinema.MovieView')]
    public function getOneMovie(CallApiService $apiService,$id,Request $request,CinemaRepository $repoCine): Response
    {   
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;

        //retrieve cinema id to made final request
        $idCine=$request->query->get('idcine');
        $cinema=$repoCine->find($idCine);

        $weekSchedule = $this->generateWeekSchedule();
        //dd($weekSchedule);
        $results=$apiService->getOneMovie($id);
        return $this->render('cinema/movie.html.twig', [
            'controller_name' => 'CinemaController',
            'Movie'=>$results,
            'categories' => $categories,
            'typesVideos' => $typesVideos,
            'cinema'=>$cinema,
            'weekSchedule'=> $weekSchedule
        ]);
    }

    /**
     * recherche une liste de film correspondant a la selection
     *
     * @param CallApiService $apiService
     * @param Request $request
     * @return Response
     */
    #[Route('/cinema/researchMovie', name: 'cinema.researchMovie')]
    public function researchMovie(CallApiService $apiService,Request $request): Response
    {   
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        //retrieve data from form 
        $Name=$request->query->get('data');

        //once we retrieve cinema id we do findByID
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

    #[Route('/cinema/booking', name: 'cinema.booking')]
    public function booking(Request $request): Response
    {   
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;
        $horaire = $request->query->get('seance');
        $titre = $request->query->get('titre');


        return $this->render('cinema/cinemaPayment.html.twig', [
            'controller_name' => 'CinemaController',
            'categories' => $categories,
            'typesVideos' => $typesVideos,
            'horaire'=> $horaire,
            'titre'=>$titre
            
        ]);
    }

    #[Route('/cinema/booked', name: 'cinema.booked')]
    public function booked(Request $request,Security $security,SendMailService $mail){
        $categories = $this->categories;
        $typesVideos = $this->typesVideos;

        $horaire = $request->query->get('horaire');
        $titre = $request->query->get('titre');
        $user = $security->getUser();
        $mail->send(
            'demoineret.denis78@gmail.com',
            $user->getEmail(),
            'Comfirmation réservation séance',
            'resa',
            compact('user', 'horaire', 'titre')
          );

        return $this->render('cinema/cinemaPayer.html.twig', [
            'controller_name' => 'CinemaController',
            'categories' => $categories,
            'typesVideos' => $typesVideos
        ]);
    }
     
    /**
     *  used in oneMovie retrieve  a list of 7 days with hours between 10h and 22h(cinema horaires)
     *
     * @return list of day and hours
     */
    function generateWeekSchedule() {
        //commence a la date d'aujourd'hui
        $startOfWeek = new DateTime();
        $startOfWeek->setTime(10, 0, 0);
        //Defini la fin une semaine après
        $endOfWeek = (clone $startOfWeek)->modify('+6 days');
        
        //defini l'interval 1h30
        $interval = new DateInterval('PT1H30M');
        
        //grand tableau ou seront stocké les jours
        $schedule = array();
        $currentDateTime = $startOfWeek;
        
        while ($currentDateTime <= $endOfWeek) {
            $currentHour = $currentDateTime->format('G');

            // si l'heure est entre 10h et 22h 
            if ($currentHour >= 10 && $currentHour <= 22) {
                //Créer un nouveau tableau pour chaque  jour de la semaine
                $daySchedule = array();
                $daySchedule['date'] = clone $currentDateTime;//ajoute la date d'auj
                $daySchedule['times'] = array();
    
                $currentTime = clone $currentDateTime;

                /**
                 * format('G') pour extraire l'heure sans le zéro de début ("09" devient "9", "14" reste "14").
                 * tant que l'heure actuelle est entre 10 et 22h   separé par des interval d'1h30 l'ajoute  a la liste
                 */
                while ($currentTime->format('G') >= 10 && $currentTime->format('G') <= 22) {
                    $daySchedule['times'][] = clone $currentTime;
                    $currentTime->add($interval);
                }
    
                $schedule[] = $daySchedule;//rajoute un nouveau jour a l'emploi du temps
                
            }
    
            $currentDateTime->add(new DateInterval('P1D'));// increment d'un jour
        }

        return $schedule;
    }
}

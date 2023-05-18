<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiService{
    


    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    /**
     * retrieve a l'affiche film
     *
     * @return void
     */
    public function getNowPlaying(){
        $response = $this->client->request(
            'GET',
            'https://api.themoviedb.org/3/movie/now_playing?api_key=6691789e696e2c66d756e2cfb9e26f41&language=fr-FR&page=1'
        );
        $content = $response->getContent();
        $machin=json_decode($content);
        $machin=$machin->results;
        return $machin;
    }
    public function getallCinema(){
        $response = $this->client->request(
            'GET',
            'https://data.culture.gouv.fr/api/records/1.0/search/?dataset=etablissements-cinematographiques&q=&lang=fr&rows=10'
        );
        $content = $response->getContent();
        $machin=json_decode($content);
        $machin=$machin->records;

        return $machin;
    }

    public function getOneMovie(Int $id){
        $response = $this->client->request(
            'GET',
            'https://api.themoviedb.org/3/movie/'.$id.'?api_key=6691789e696e2c66d756e2cfb9e26f41&language=fr-FR'
        );
        $content = $response->getContent();
        $machin=json_decode($content);
        

        return $machin;
    }

    public function researchMovie(String $string){
        $response = $this->client->request(
            'GET',
            'https://api.themoviedb.org/3/search/movie?api_key=6691789e696e2c66d756e2cfb9e26f41&language=en-US&query='.$string.'&page=1&include_adult=false'
        );
        $content = $response->getContent();
        $machin=json_decode($content);
        $machin=$machin->results;

        return $machin;
    }
    public function similarTo(Int $id){
        $response = $this->client->request(
            'GET',
            'https://api.themoviedb.org/3/movie/'.$id.'/similar?api_key=6691789e696e2c66d756e2cfb9e26f41&language=fr-FR&page=1'
        );
        $content = $response->getContent();
        $machin=json_decode($content);
        $machin=$machin->results;
    }


    public function popularMovies(){
        $response = $this->client->request(
            'GET',
            'https://api.themoviedb.org/3/movie/popular?api_key=6691789e696e2c66d756e2cfb9e26f41&language=fr-FR&page=1'
        );
        $content = $response->getContent();
        $machin=json_decode($content);
        $machin=$machin->results;
    }
}
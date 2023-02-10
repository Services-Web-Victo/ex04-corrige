<?php

namespace App\Domain\Movie\Service;

use App\Domain\Movie\Repository\MovieRepository;

/**
 * Service.
 */
final class MovieUpdate
{
    /**
     * @var MovieRepository
     */
    private $repository;

    public function __construct(MovieRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Modification d'un film dans la base de données.
     * 
     * @param int $id Le id du film à modifier
     * @param array $data Les informations à modifier
     *
     * @return array Le film ajouté
     */
    public function updateMovie(int $id, array $data): array
    {

        // L'idée avec la méthode PUT est que si la ressource à modifier n'existe pas, on doit la créer.
        
        // Teste si le film existe dans la base de données
        $oldMovie = $this->repository->selectMovieById($id);
        $codeStatus = 200;

        if(empty($oldMovie)) {
            // Création d'un nouveau film
            $movies = $this->repository->createMovie($data); 
            $codeStatus = 201;   
        } else {
            // Modification du film existant
            $movies = $this->repository->updateMovie($id, $data);
        }

        $resultat = [
            "movie" => $movies,
            "codeStatus" => $codeStatus
        ];

        return $resultat;
    }


}

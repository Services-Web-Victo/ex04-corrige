<?php

namespace App\Domain\Movie\Service;

use App\Domain\Movie\Repository\MovieRepository;

/**
 * Service.
 */
final class MovieView
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
     * Sélectionne tous les films.
     *
     * @return array La liste des films
     */
    public function viewAllMovies(): array
    {

        $movies = $this->repository->selectAllMovies();

        // Tableau qui contient la réponse à retourner à l'usager
        $resultat = [
            "movies" => $movies
        ];

        return $resultat;
    }

    /**
     * Sélectionne un film selon son id.
     *
     * @return array Les informations d'un film
     */
    public function viewMovieById(int $movieId): array
    {

        $movies = $this->repository->selectMovieById($movieId);

        return $movies;
    }


}

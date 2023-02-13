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
    public function viewAllMovies(array $queryParams): array
    {

        $nbMoviesByPage = 50;
        $page = $queryParams['page'] ?? 1;
        // C'était pas demandé mais ici j'offre la possibilité d'avoir plus d'un genre
        // s'il y a deux genres de spécifiés, le film doit comporté les deux pour être affiché (ET)
        // Explode converti les valeurs de mon string genre séparée par des virgules en un tableau dont chaque
        // valeur sera un item.
        $genres = explode(",",$queryParams['genre']) ?? [];
        $indexFilmDebut = ($page - 1) * $nbMoviesByPage;

        $movies = $this->repository->selectAllMovies($genres);
        $nbMovies = count($movies);
        $moviesSliced = array_slice($movies, $indexFilmDebut, $nbMoviesByPage);

        // Tableau qui contient la réponse à retourner à l'usager
        $resultat = [
            "movies" => $moviesSliced,
            "genre" => $genres,
            "nombreFilmsTotal" => count($movies),
            "page" => (int)$page,
            "totalPage" => ceil($nbMovies / $nbMoviesByPage)
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

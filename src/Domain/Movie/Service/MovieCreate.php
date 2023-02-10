<?php

namespace App\Domain\Movie\Service;

use App\Domain\Movie\Repository\MovieRepository;

/**
 * Service.
 */
final class MovieCreate
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
     * Ajout d'un film dans la base de données.
     * 
     * @param array $data Les informations à ajouter
     *
     * @return array Le film ajouté
     */
    public function addMovie(array $data): array
    {

        $movies = $this->repository->createMovie($data);

        return $movies ?? [];
    }


}

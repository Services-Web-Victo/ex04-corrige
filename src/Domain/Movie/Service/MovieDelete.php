<?php

namespace App\Domain\Movie\Service;

use App\Domain\Movie\Repository\MovieRepository;
use stdClass;

/**
 * Service.
 */
final class MovieDelete
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
     * Supprime un film dans la base de données.
     * 
     * @param int $idMovie Le id du film à supprimer
     *
     * @return array Le film supprimé
     */
    public function removeMovie(int $idMovie): array
    {

        // Je vais chercher les informations du film à supprimer
        // Pour valider qu'il existe bien et aussi pour les retourner à l'usager après la suppression
        $movieToDelete = $this->repository->selectMovieById($idMovie);
        // Par défaut le code de statut sera 200 - Succès
        $codeStatus = 200;

        // Si le film n'existe pas on change pour le code 404, sinon on supprime le film
        if(empty($movieToDelete)) {
            $codeStatus = 404;
        } else {
            $this->repository->deleteMovie($idMovie);
        }
        
        // Si le film n'existe pas, on retourne un objet vide
        // J'ai créer un tableau avec le film et le code de statut pour pouvoir les retourner tous les deux avec ma fonction
        $resultat = [
            "movie" => empty($movieToDelete) ? new stdClass : $movieToDelete,
            "codeStatus" => $codeStatus
        ];

        return $resultat;
    }


}

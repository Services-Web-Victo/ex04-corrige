<?php

namespace App\Domain\Movie\Service;

use App\Domain\Movie\Repository\MovieRepository;
use App\Factory\LoggerFactory;
use Psr\Log\LoggerInterface;
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
    
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(MovieRepository $repository, LoggerFactory $loggerFactory)
    {
        $this->repository = $repository;
        $this->logger = $loggerFactory
            ->addFileHandler('MoviesLog.log')
            ->createLogger('deleteMovie');
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
            if($this->repository->deleteMovie($idMovie)) {
                $this->logger->info('Le film "' . $movieToDelete['series_title'] . '" id [' . $idMovie . '] a été supprimé.');
            };
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

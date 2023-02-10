<?php

namespace App\Action\Movie;

use App\Domain\Movie\Service\MovieView;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class MovieViewAction
{
    private $movieView;

    public function __construct(MovieView $movieView)
    {
        $this->movieView = $movieView;
    }

    public function __invoke(
        ServerRequestInterface $request, 
        ResponseInterface $response
    ): ResponseInterface {

        $resultat = $this->movieView->viewAllMovies();

        // Construit la réponse HTTP
        $response->getBody()->write((string)json_encode($resultat));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}

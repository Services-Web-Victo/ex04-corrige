<?php

namespace App\Action\Movie;

use App\Domain\Movie\Service\MovieCreate;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class MovieCreateAction
{
    private $movieCreate;

    public function __construct(MovieCreate $movieCreate)
    {
        $this->movieCreate = $movieCreate;
    }

    public function __invoke(
        ServerRequestInterface $request, 
        ResponseInterface $response
    ): ResponseInterface {

        // Récupération des données du corps de la requête
        $data = (array)$request->getParsedBody();

        $resultat = $this->movieCreate->addMovie($data);

        // Construit la réponse HTTP
        $response->getBody()->write((string)json_encode($resultat));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }
}

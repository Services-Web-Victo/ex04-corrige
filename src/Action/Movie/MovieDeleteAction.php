<?php

namespace App\Action\Movie;

use App\Domain\Movie\Service\MovieDelete;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class MovieDeleteAction
{
    private $movieDelete;

    public function __construct(MovieDelete $movieDelete)
    {
        $this->movieDelete = $movieDelete;
    }

    public function __invoke(
        ServerRequestInterface $request, 
        ResponseInterface $response
    ): ResponseInterface {

        // Récupération du paramètre de route 'id'
        $id = $request->getAttribute('id', 0);

        $resultat = $this->movieDelete->removeMovie($id);

        // Construit la réponse HTTP
        $response->getBody()->write((string)json_encode($resultat["movie"]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($resultat["codeStatus"]);
    }
}

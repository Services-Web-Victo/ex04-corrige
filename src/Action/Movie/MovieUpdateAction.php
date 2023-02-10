<?php

namespace App\Action\Movie;

use App\Domain\Movie\Service\MovieUpdate;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class MovieUpdateAction
{
    private $movieUpdate;

    public function __construct(MovieUpdate $movieUpdate)
    {
        $this->movieUpdate = $movieUpdate;
    }

    public function __invoke(
        ServerRequestInterface $request, 
        ResponseInterface $response
    ): ResponseInterface {

        // Récupération des données du corps de la requête
        $data = (array)$request->getParsedBody();
        // Récupération du paramètre de route 'id'
        $id = $request->getAttribute('id', 0);


        $resultat = $this->movieUpdate->updateMovie($id, $data);

        // Construit la réponse HTTP
        $response->getBody()->write((string)json_encode($resultat["movie"]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($resultat["codeStatus"]);
    }
}

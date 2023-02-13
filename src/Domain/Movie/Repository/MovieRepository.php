<?php

namespace App\Domain\Movie\Repository;

use PDO;

/**
 * Repository.
 */
class MovieRepository
{
    /**
     * @var PDO The database connection
     */
    private $connection;

    /**
     * Constructor.
     *
     * @param PDO $connection The database connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Sélectionne la liste de tous les films
     * 
     * @param array $genre Le genre des films à afficher, tous par défaut
     * 
     * @return array
     */
    public function selectAllMovies(array $genres): array
    {
        $sql = "SELECT * FROM imdb_top";
        
        if(!empty($genres)) {
            // Ici implode reconverti fusionne toutes les valeurs de mon tableau en les séparant par le texte
            // %' AND genre LIKE '%
            // Avec une concaténation on aura le string complet pour faire le WHERE: 
            // WHERE genre LIKE '%item1%' AND genre LIKE '%item2%' ...
            $sql .= " WHERE genre LIKE '%" . implode("%' AND genre LIKE '%", $genres) . "%'";
        }        

        $query = $this->connection->prepare($sql);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * Sélectionne les informations d'un film
     * 
     * @param int $movieId Le id du film à afficher
     * 
     * @return array Les informations du films
     */
    public function selectMovieById(int $movieId): array
    {
        $sql = "SELECT * FROM imdb_top WHERE id = :id;";
        $params = [
            'id' => $movieId
        ];

        $query = $this->connection->prepare($sql);
        $query->execute($params);

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result[0] ?? [];
    }



    /**
     * Ajoute un film
     * 
     * @param array $data Les données du film
     * 
     * @return array Les informations du film ajouté avec son id
     */
    public function createMovie(array $data): array
    {
        $sql = "INSERT INTO imdb_top (poster_link, series_title, released_year, certificate, runtime, genre, imdb_rating, overview, meta_score, director, star1, star2, star3, star4, no_of_votes, gross)
                VALUES (:poster_link, :series_title, :released_year, :certificate, :runtime, :genre, :imdb_rating, :overview, :meta_score, :director, :star1, :star2, :star3, :star4, :no_of_votes, :gross);
        ";
        
        $params = [
            "poster_link" => $data['poster_link'] ?? "",
            "series_title"=> $data['series_title'] ?? "",
            "released_year"=> $data['released_year'] ?? null,
            "certificate"=> $data['certificate'] ?? "",
            "runtime"=> $data['runtime'] ?? "",
            "genre"=> $data['genre'] ?? "",
            "imdb_rating"=> $data['imdb_rating'] ?? null,
            "overview"=> $data['overview'] ?? "",
            "meta_score"=> $data['meta_score'] ?? null,
            "director"=> $data['director'] ?? "",
            "star1"=> $data['star1'] ?? "",
            "star2"=> $data['star2'] ?? "",
            "star3"=> $data['star3'] ?? "",
            "star4"=> $data['star4'] ?? "",
            "no_of_votes"=> $data['no_of_votes'] ?? null,
            "gross"=> $data['gross'] ?? "" 
        ];

        $query = $this->connection->prepare($sql);
        // L'insertion est fait ici
        $query->execute($params);
        // Je récupère le id qui vient d'être créé
        $movieId = $this->connection->lastInsertId();
        // Je veux retourner à l'usager le film créé avec le nouveau id, je me sers de la fonction que j'ai 
        // déjà pour sélectionner un film par son id. En même temps ça me prouve qu'il est bien créé.
        // C'est pas à toute épreuve comme gestion d'erreur mais pour l'instant on ne s'en occupe pas.
        $result = $this->selectMovieById($movieId);

        return $result;
    }

    /**
     * Modifie un film
     * 
     * @param int $id Le id du film à modifier
     * @param array $data Les données du film à modifier
     * 
     * @return array Le film modifié
     */
    public function updateMovie(int $id, array $data): array
    {
        
        $sql = "UPDATE imdb_top
                SET poster_link = :poster_link, 
                    series_title = :series_title, 
                    released_year = :released_year, 
                    certificate = :certificate, 
                    runtime = :runtime, 
                    genre = :genre, 
                    imdb_rating = :imdb_rating, 
                    overview = :overview, 
                    meta_score = :meta_score, 
                    director = :director, 
                    star1 = :star1, 
                    star2 = :star2, 
                    star3 = :star3, 
                    star4 = :star4, 
                    no_of_votes = :no_of_votes, 
                    gross = :gross
                WHERE id = :id;";
        
        $params = [
            "id" => $id,
            "poster_link" => $data['poster_link'] ?? "",
            "series_title"=> $data['series_title'] ?? "",
            "released_year"=> $data['released_year'] ?? null,
            "certificate"=> $data['certificate'] ?? "",
            "runtime"=> $data['runtime'] ?? "",
            "genre"=> $data['genre'] ?? "",
            "imdb_rating"=> $data['imdb_rating'] ?? null,
            "overview"=> $data['overview'] ?? "",
            "meta_score"=> $data['meta_score'] ?? null,
            "director"=> $data['director'] ?? "",
            "star1"=> $data['star1'] ?? "",
            "star2"=> $data['star2'] ?? "",
            "star3"=> $data['star3'] ?? "",
            "star4"=> $data['star4'] ?? "",
            "no_of_votes"=> $data['no_of_votes'] ?? null,
            "gross"=> $data['gross'] ?? "" 
        ];
        
        $query = $this->connection->prepare($sql);
        $query->execute($params);

        $result = $this->selectMovieById($id);

        return $result;
    }

    /**
     * Supprime un film selon son id
     *
     * @param int $movieId Le id du film à supprimer
     *
     * @return bool La suppression à réussi
     */
    public function deleteMovie(int $movieId): bool
    {
        $params = ['id' => $movieId];
        $sql = "DELETE FROM imdb_top WHERE id = :id";

        $query = $this->connection->prepare($sql);
        $result = $query->execute($params);

        /*
        J'ai laissé ce bout de code en commentaire. C'est une façon de récupérer les erreurs sql s'il y en a.
        plus de détail ici : https://www.php.net/manual/fr/pdo.errorinfo.php
        La ligne avec le "logger" sert à écrire dans un fichier de log l'erreur. Si on veut s'en servir il y a une déclaration a
        faire dans le constructeur de la classe. Voir les notes de cours sur les fichiers de logs.
        */
        // $errorInfo = $query->errorInfo();
        // if($errorInfo[0] != 0) {
        //     $this->logger->error($errorInfo[2]);
        // }

        return $result;
    }
}


<?php

declare(strict_types=1);

use App\Application\Repositories\ArtistRepository;
use App\Application\Repositories\AlbumRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app) {

    /*
    |--------------------------------------------------------------------------
    | ARTISTS
    |--------------------------------------------------------------------------
    */

    // GET tous les artistes
    $app->get('/artists', function (
        Request $request,
        Response $response
    ) use ($app) {

        $repository = $app->getContainer()->get(
            ArtistRepository::class
        );

        $artists = $repository->getAll();

        $response->getBody()->write(
            json_encode($artists)
        );

        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    });


    // GET rechercher des artistes
    // IMPORTANT : cette route doit être AVANT /artists/{id}
    $app->get('/artists/search', function (
        Request $request,
        Response $response
    ) use ($app) {

        $params = $request->getQueryParams();

        $name = $params['name'] ?? '';

        $repository = $app->getContainer()->get(
            ArtistRepository::class
        );

        $artists = $repository->search($name);

        $response->getBody()->write(
            json_encode($artists)
        );

        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    });


    // GET un artiste
    $app->get('/artists/{id}', function (
        Request $request,
        Response $response,
        array $args
    ) use ($app) {

        $repository = $app->getContainer()->get(
            ArtistRepository::class
        );

        $artist = $repository->getById(
            (int) $args['id']
        );

        if ($artist === null) {
            $response->getBody()->write(
                json_encode([
                    'error' => 'Artist not found'
                ])
            );

            return $response
                ->withStatus(404)
                ->withHeader(
                    'Content-Type',
                    'application/json'
                );
        }

        $response->getBody()->write(
            json_encode($artist)
        );

        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    });


    // POST créer un artiste
    $app->post('/artists', function (
        Request $request,
        Response $response
    ) use ($app) {

        $params = (array) $request->getParsedBody();

        $name = $params['Name'] ?? null;
        $annee = $params['Annee'] ?? null;
        $description = $params['Description'] ?? null;

        if (
            $name === null ||
            $annee === null ||
            $description === null
        ) {
            $response->getBody()->write(
                json_encode([
                    'error' => 'Name, Annee and Description are required'
                ])
            );

            return $response
                ->withStatus(400)
                ->withHeader(
                    'Content-Type',
                    'application/json'
                );
        }

        $repository = $app->getContainer()->get(
            ArtistRepository::class
        );

        $id = $repository->create(
            (string) $name,
            (string) $annee,
            (string) $description
        );

        $response->getBody()->write(
            json_encode([
                'message' => 'Artist created',
                'id' => $id
            ])
        );

        return $response
            ->withStatus(201)
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    });


    // PUT modifier un artiste
    $app->put('/artists/{id}', function (
        Request $request,
        Response $response,
        array $args
    ) use ($app) {

        $params = (array) $request->getParsedBody();

        $name = $params['Name'] ?? null;
        $annee = $params['Annee'] ?? null;
        $description = $params['Description'] ?? null;

        if (
            $name === null ||
            $annee === null ||
            $description === null
        ) {
            $response->getBody()->write(
                json_encode([
                    'error' => 'Name, Annee and Description are required'
                ])
            );

            return $response
                ->withStatus(400)
                ->withHeader(
                    'Content-Type',
                    'application/json'
                );
        }

        $repository = $app->getContainer()->get(
            ArtistRepository::class
        );

        $artist = $repository->getById(
            (int) $args['id']
        );

        if ($artist === null) {
            $response->getBody()->write(
                json_encode([
                    'error' => 'Artist not found'
                ])
            );

            return $response
                ->withStatus(404)
                ->withHeader(
                    'Content-Type',
                    'application/json'
                );
        }

        $repository->update(
            (int) $args['id'],
            (string) $name,
            (string) $annee,
            (string) $description
        );

        $response->getBody()->write(
            json_encode([
                'message' => 'Artist updated'
            ])
        );

        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    });


    // DELETE supprimer un artiste
    $app->delete('/artists/{id}', function (
        Request $request,
        Response $response,
        array $args
    ) use ($app) {

        $repository = $app->getContainer()->get(
            ArtistRepository::class
        );

        $artist = $repository->getById(
            (int) $args['id']
        );

        if ($artist === null) {
            $response->getBody()->write(
                json_encode([
                    'error' => 'Artist not found'
                ])
            );

            return $response
                ->withStatus(404)
                ->withHeader(
                    'Content-Type',
                    'application/json'
                );
        }

        $repository->delete(
            (int) $args['id']
        );

        $response->getBody()->write(
            json_encode([
                'message' => 'Artist deleted'
            ])
        );

        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    });


    /*
    |--------------------------------------------------------------------------
    | ALBUMS
    |--------------------------------------------------------------------------
    */

    // GET tous les albums
    $app->get('/albums', function (
        Request $request,
        Response $response
    ) use ($app) {

        $repository = $app->getContainer()->get(
            AlbumRepository::class
        );

        $albums = $repository->getAll();

        $response->getBody()->write(
            json_encode($albums)
        );

        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    });


    // GET rechercher des albums
    // IMPORTANT : cette route doit être AVANT /albums/{id}
    $app->get('/albums/search', function (
        Request $request,
        Response $response
    ) use ($app) {

        $params = $request->getQueryParams();

        $titre = $params['titre'] ?? '';

        $repository = $app->getContainer()->get(
            AlbumRepository::class
        );

        $albums = $repository->search($titre);

        $response->getBody()->write(
            json_encode($albums)
        );

        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    });


    // GET moyenne des notes d'un album
    $app->get('/albums/{id}/average-rating', function (
        Request $request,
        Response $response,
        array $args
    ) use ($app) {

        $repository = $app->getContainer()->get(
            AlbumRepository::class
        );

        $album = $repository->getAverageRating(
            (int) $args['id']
        );

        if (empty($album)) {
            $response->getBody()->write(
                json_encode([
                    'error' => 'Album not found'
                ])
            );

            return $response
                ->withStatus(404)
                ->withHeader(
                    'Content-Type',
                    'application/json'
                );
        }

        $response->getBody()->write(
            json_encode($album)
        );

        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    });


    // GET les notes d'un album
    $app->get('/albums/{id}/ratings', function (
        Request $request,
        Response $response,
        array $args
    ) use ($app) {

        $pdo = $app->getContainer()->get(\PDO::class);

        $stmt = $pdo->prepare(
            'SELECT * FROM ratings
             WHERE Albums_idAlbums = :id'
        );

        $stmt->execute([
            'id' => (int) $args['id']
        ]);

        $ratings = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $response->getBody()->write(
            json_encode($ratings)
        );

        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    });


    // GET les albums d'un artiste
    $app->get('/artists/{id}/albums', function (
        Request $request,
        Response $response,
        array $args
    ) use ($app) {

        $repository = $app->getContainer()->get(
            AlbumRepository::class
        );

        $albums = $repository->getByArtistId(
            (int) $args['id']
        );

        $response->getBody()->write(
            json_encode($albums)
        );

        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    });


    // GET un album
    $app->get('/albums/{id}', function (
        Request $request,
        Response $response,
        array $args
    ) use ($app) {

        $repository = $app->getContainer()->get(
            AlbumRepository::class
        );

        $album = $repository->getById(
            (int) $args['id']
        );

        if ($album === null) {
            $response->getBody()->write(
                json_encode([
                    'error' => 'Album not found'
                ])
            );

            return $response
                ->withStatus(404)
                ->withHeader(
                    'Content-Type',
                    'application/json'
                );
        }

        $response->getBody()->write(
            json_encode($album)
        );

        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    });


    // POST créer un album
    $app->post('/albums', function (
        Request $request,
        Response $response
    ) use ($app) {

        $params = (array) $request->getParsedBody();

        $titre = $params['Titre'] ?? null;
        $artistId = $params['Artist_idArtist'] ?? null;

        if ($titre === null || $artistId === null) {
            $response->getBody()->write(
                json_encode([
                    'error' => 'Titre and Artist_idArtist are required'
                ])
            );

            return $response
                ->withStatus(400)
                ->withHeader(
                    'Content-Type',
                    'application/json'
                );
        }

        $repository = $app->getContainer()->get(
            AlbumRepository::class
        );

        $id = $repository->create(
            (string) $titre,
            (int) $artistId
        );

        $response->getBody()->write(
            json_encode([
                'message' => 'Album created',
                'id' => $id
            ])
        );

        return $response
            ->withStatus(201)
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    });


    // PUT modifier un album
    $app->put('/albums/{id}', function (
        Request $request,
        Response $response,
        array $args
    ) use ($app) {

        $params = (array) $request->getParsedBody();

        $titre = $params['Titre'] ?? null;
        $artistId = $params['Artist_idArtist'] ?? null;

        if ($titre === null || $artistId === null) {
            $response->getBody()->write(
                json_encode([
                    'error' => 'Titre and Artist_idArtist are required'
                ])
            );

            return $response
                ->withStatus(400)
                ->withHeader(
                    'Content-Type',
                    'application/json'
                );
        }

        $repository = $app->getContainer()->get(
            AlbumRepository::class
        );

        $album = $repository->getById(
            (int) $args['id']
        );

        if ($album === null) {
            $response->getBody()->write(
                json_encode([
                    'error' => 'Album not found'
                ])
            );

            return $response
                ->withStatus(404)
                ->withHeader(
                    'Content-Type',
                    'application/json'
                );
        }

        $repository->update(
            (int) $args['id'],
            (string) $titre,
            (int) $artistId
        );

        $response->getBody()->write(
            json_encode([
                'message' => 'Album updated'
            ])
        );

        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    });


    // DELETE supprimer un album
    $app->delete('/albums/{id}', function (
        Request $request,
        Response $response,
        array $args
    ) use ($app) {

        $repository = $app->getContainer()->get(
            AlbumRepository::class
        );

        $album = $repository->getById(
            (int) $args['id']
        );

        if ($album === null) {
            $response->getBody()->write(
                json_encode([
                    'error' => 'Album not found'
                ])
            );

            return $response
                ->withStatus(404)
                ->withHeader(
                    'Content-Type',
                    'application/json'
                );
        }

        $repository->delete(
            (int) $args['id']
        );

        $response->getBody()->write(
            json_encode([
                'message' => 'Album deleted'
            ])
        );

        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    });


    /*
    |--------------------------------------------------------------------------
    | RATINGS
    |--------------------------------------------------------------------------
    */

    // GET toutes les notes
    $app->get('/ratings', function (
        Request $request,
        Response $response
    ) use ($app) {

        $pdo = $app->getContainer()->get(\PDO::class);

        $stmt = $pdo->query(
            'SELECT * FROM ratings'
        );

        $ratings = $stmt->fetchAll(
            \PDO::FETCH_ASSOC
        );

        $response->getBody()->write(
            json_encode($ratings)
        );

        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    });


    // GET une note
    $app->get('/ratings/{id}', function (
        Request $request,
        Response $response,
        array $args
    ) use ($app) {

        $pdo = $app->getContainer()->get(\PDO::class);

        $stmt = $pdo->prepare(
            'SELECT * FROM ratings
             WHERE idRatings = :id'
        );

        $stmt->execute([
            'id' => (int) $args['id']
        ]);

        $rating = $stmt->fetch(
            \PDO::FETCH_ASSOC
        );

        if (!$rating) {
            $response->getBody()->write(
                json_encode([
                    'error' => 'Rating not found'
                ])
            );

            return $response
                ->withStatus(404)
                ->withHeader(
                    'Content-Type',
                    'application/json'
                );
        }

        $response->getBody()->write(
            json_encode($rating)
        );

        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    });


    // POST créer une note
    $app->post('/ratings', function (
        Request $request,
        Response $response
    ) use ($app) {

        $params = (array) $request->getParsedBody();

        $grade = $params['Grade'] ?? null;
        $albumId = $params['Albums_idAlbums'] ?? null;

        if ($grade === null || $albumId === null) {
            $response->getBody()->write(
                json_encode([
                    'error' => 'Grade and Albums_idAlbums are required'
                ])
            );

            return $response
                ->withStatus(400)
                ->withHeader(
                    'Content-Type',
                    'application/json'
                );
        }

        $pdo = $app->getContainer()->get(\PDO::class);

        $stmt = $pdo->prepare(
            'INSERT INTO ratings
             (Grade, Albums_idAlbums)
             VALUES (:grade, :albumId)'
        );

        $stmt->execute([
            'grade' => $grade,
            'albumId' => $albumId
        ]);

        $id = (int) $pdo->lastInsertId();

        $response->getBody()->write(
            json_encode([
                'message' => 'Rating created',
                'id' => $id
            ])
        );

        return $response
            ->withStatus(201)
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    });


    // PUT modifier une note
    $app->put('/ratings/{id}', function (
        Request $request,
        Response $response,
        array $args
    ) use ($app) {

        $params = (array) $request->getParsedBody();

        $grade = $params['Grade'] ?? null;
        $albumId = $params['Albums_idAlbums'] ?? null;

        if ($grade === null || $albumId === null) {
            $response->getBody()->write(
                json_encode([
                    'error' => 'Grade and Albums_idAlbums are required'
                ])
            );

            return $response
                ->withStatus(400)
                ->withHeader(
                    'Content-Type',
                    'application/json'
                );
        }

        $pdo = $app->getContainer()->get(\PDO::class);

        $stmt = $pdo->prepare(
            'UPDATE ratings
             SET Grade = :grade,
                 Albums_idAlbums = :albumId
             WHERE idRatings = :id'
        );

        $stmt->execute([
            'id' => (int) $args['id'],
            'grade' => $grade,
            'albumId' => $albumId
        ]);

        $response->getBody()->write(
            json_encode([
                'message' => 'Rating updated'
            ])
        );

        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    });


    // DELETE supprimer une note
    $app->delete('/ratings/{id}', function (
        Request $request,
        Response $response,
        array $args
    ) use ($app) {

        $pdo = $app->getContainer()->get(\PDO::class);

        $stmt = $pdo->prepare(
            'DELETE FROM ratings
             WHERE idRatings = :id'
        );

        $stmt->execute([
            'id' => (int) $args['id']
        ]);

        $response->getBody()->write(
            json_encode([
                'message' => 'Rating deleted'
            ])
        );

        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    });


    /*
    |--------------------------------------------------------------------------
    | ANCIENNE ROUTE
    |--------------------------------------------------------------------------
    */

    $app->get('/GetAllArtist', function (
        Request $request,
        Response $response
    ) use ($app) {

        $repository = $app->getContainer()->get(
            ArtistRepository::class
        );

        $artists = $repository->getAll();

        $response->getBody()->write(
            json_encode($artists)
        );

        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    });
};
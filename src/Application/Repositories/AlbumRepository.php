<?php

declare(strict_types=1);

namespace App\Application\Repositories;

class AlbumRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Récupérer tous les albums
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query(
            'SELECT * FROM albums'
        );

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer un album par son ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM albums
             WHERE idAlbums = :id'
        );

        $stmt->execute([
            'id' => $id
        ]);

        $album = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $album ?: null;
    }

    /**
     * Rechercher des albums par titre
     */
    public function search(string $titre): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM albums
             WHERE Titre LIKE :titre
             ORDER BY Titre ASC'
        );

        $stmt->execute([
            'titre' => '%' . $titre . '%'
        ]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les albums d'un artiste
     */
    public function getByArtistId(int $artistId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM albums
             WHERE Artist_idArtist = :artistId'
        );

        $stmt->execute([
            'artistId' => $artistId
        ]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Créer un album
     */
    public function create(
        string $titre,
        int $artistId
    ): int {
        $stmt = $this->pdo->prepare(
            'INSERT INTO albums
             (Titre, Artist_idArtist)
             VALUES (:titre, :artistId)'
        );

        $stmt->execute([
            'titre' => $titre,
            'artistId' => $artistId
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Modifier un album
     */
    public function update(
        int $id,
        string $titre,
        int $artistId
    ): bool {
        $stmt = $this->pdo->prepare(
            'UPDATE albums
             SET Titre = :titre,
                 Artist_idArtist = :artistId
             WHERE idAlbums = :id'
        );

        return $stmt->execute([
            'id' => $id,
            'titre' => $titre,
            'artistId' => $artistId
        ]);
    }

    /**
     * Supprimer un album
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            'DELETE FROM albums
             WHERE idAlbums = :id'
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }

    /**
     * Calculer la moyenne des notes d'un album
     */
    public function getAverageRating(int $albumId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT
                a.idAlbums,
                a.Titre,
                AVG(r.Grade) AS averageRating,
                COUNT(r.idRatings) AS ratingCount
             FROM albums a
             LEFT JOIN ratings r
                ON r.Albums_idAlbums = a.idAlbums
             WHERE a.idAlbums = :id
             GROUP BY a.idAlbums, a.Titre'
        );

        $stmt->execute([
            'id' => $albumId
        ]);

        $album = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$album) {
            return [];
        }

        $album['averageRating'] = $album['averageRating'] !== null
            ? (float) $album['averageRating']
            : null;

        $album['ratingCount'] = (int) $album['ratingCount'];

        return $album;
    }
}

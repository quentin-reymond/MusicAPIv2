<?php

declare(strict_types=1);

namespace App\Application\Repositories;

class ArtistRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Récupérer tous les artistes
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query(
            'SELECT * FROM artists'
        );

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer un artiste par son ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM artists
             WHERE idArtist = :id'
        );

        $stmt->execute([
            'id' => $id
        ]);

        $artist = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $artist ?: null;
    }

    /**
     * Rechercher des artistes par nom
     */
    public function search(string $name): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM artists
             WHERE Name LIKE :name
             ORDER BY Name ASC'
        );

        $stmt->execute([
            'name' => '%' . $name . '%'
        ]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Créer un artiste
     */
    public function create(
        string $name,
        string $annee,
        string $description
    ): int {
        $stmt = $this->pdo->prepare(
            'INSERT INTO artists
             (Name, Annee, Description)
             VALUES (:name, :annee, :description)'
        );

        $stmt->execute([
            'name' => $name,
            'annee' => $annee,
            'description' => $description
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Modifier un artiste
     */
    public function update(
        int $id,
        string $name,
        string $annee,
        string $description
    ): bool {
        $stmt = $this->pdo->prepare(
            'UPDATE artists
             SET Name = :name,
                 Annee = :annee,
                 Description = :description
             WHERE idArtist = :id'
        );

        return $stmt->execute([
            'id' => $id,
            'name' => $name,
            'annee' => $annee,
            'description' => $description
        ]);
    }

    /**
     * Supprimer un artiste
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            'DELETE FROM artists
             WHERE idArtist = :id'
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }
}
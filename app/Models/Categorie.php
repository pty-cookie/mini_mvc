<?php

declare(strict_types=1);

namespace Mini\Models;

use Mini\Core\Database;
use PDO;

final class Categorie
{
    private $id;
    private $nom;
    private $description;

    public function getAll(): array
    {
        $pdo = Database::getPDO();
        $sql = "SELECT * FROM categorie ORDER BY nom";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $pdo = Database::getPDO();
        $sql = "SELECT * FROM categorie WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): bool
    {
        $pdo = Database::getPDO();
        $sql = "INSERT INTO categorie (nom, description) VALUES (:nom, :description)";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'nom' => $data['nom'],
            'description' => $data['description'] ?? null
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $pdo = Database::getPDO();
        $sql = "UPDATE categorie SET nom = :nom, description = :description WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'nom' => $data['nom'],
            'description' => $data['description'] ?? null
        ]);
    }

    public function delete(int $id): bool
    {
        $pdo = Database::getPDO();
        $sql = "DELETE FROM categorie WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // Getters et Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    public function getNom() { return $this->nom; }
    public function setNom($nom) { $this->nom = $nom; }
    public function getDescription() { return $this->description; }
    public function setDescription($description) { $this->description = $description; }
}

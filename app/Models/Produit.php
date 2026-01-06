<?php

declare(strict_types=1);

namespace Mini\Models;

use Mini\Core\Database;
use PDO;

final class Produit
{
    private $id;
    private $nom;
    private $description;
    private $prix;
    private $stock;
    private $image_url;
    private $categorie_id;

    public function getAll(): array
    {
        $pdo = Database::getPDO();
        $sql = "SELECT p.*, c.nom as categorie_nom 
                FROM produit p 
                LEFT JOIN categorie c ON p.categorie_id = c.id 
                ORDER BY p.id DESC";
        
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $pdo = Database::getPDO();
        $sql = "SELECT p.*, c.nom as categorie_nom 
                FROM produit p 
                LEFT JOIN categorie c ON p.categorie_id = c.id 
                WHERE p.id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getByCategorie(int $categorieId): array
    {
        $pdo = Database::getPDO();
        $sql = "SELECT * FROM produit WHERE categorie_id = :categorie_id ORDER BY nom";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['categorie_id' => $categorieId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        $pdo = Database::getPDO();
        $sql = "INSERT INTO produit (nom, description, prix, stock, image_url, categorie_id) 
                VALUES (:nom, :description, :prix, :stock, :image_url, :categorie_id)";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'nom' => $data['nom'],
            'description' => $data['description'] ?? null,
            'prix' => $data['prix'],
            'stock' => $data['stock'],
            'image_url' => $data['image_url'] ?? '',
            'categorie_id' => $data['categorie_id'] ?? null
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $pdo = Database::getPDO();
        $sql = "UPDATE produit 
                SET nom = :nom, description = :description, prix = :prix, 
                    stock = :stock, image_url = :image_url, categorie_id = :categorie_id 
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'nom' => $data['nom'],
            'description' => $data['description'] ?? null,
            'prix' => $data['prix'],
            'stock' => $data['stock'],
            'image_url' => $data['image_url'] ?? '',
            'categorie_id' => $data['categorie_id'] ?? null
        ]);
    }

    public function delete(int $id): bool
    {
        $pdo = Database::getPDO();
        $sql = "DELETE FROM produit WHERE id = :id";
        
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
    public function getPrix() { return $this->prix; }
    public function setPrix($prix) { $this->prix = $prix; }
    public function getStock() { return $this->stock; }
    public function setStock($stock) { $this->stock = $stock; }
    public function getImageUrl() { return $this->image_url; }
    public function setImageUrl($image_url) { $this->image_url = $image_url; }
    public function getCategorieId() { return $this->categorie_id; }
    public function setCategorieId($categorie_id) { $this->categorie_id = $categorie_id; }
}

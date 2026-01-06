<?php

declare(strict_types=1);

namespace Mini\Models;

use Mini\Core\Database;
use PDO;

final class Commande
{
    private $id;
    private $user_id;
    private $statut;
    private $total;
    private $created_at;
    private $updated_at;

    public function getAll(): array
    {
        $pdo = Database::getPDO();
        $sql = "SELECT c.*, u.nom as user_nom, u.email as user_email 
                FROM commande c 
                LEFT JOIN user u ON c.user_id = u.id 
                ORDER BY c.created_at DESC";
        
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $pdo = Database::getPDO();
        $sql = "SELECT c.*, u.nom as user_nom, u.email as user_email 
                FROM commande c 
                LEFT JOIN user u ON c.user_id = u.id 
                WHERE c.id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getByUserId(int $userId): array
    {
        $pdo = Database::getPDO();
        $sql = "SELECT * FROM commande WHERE user_id = :user_id ORDER BY created_at DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        $pdo = Database::getPDO();
        $sql = "INSERT INTO commande (user_id, statut, total) 
                VALUES (:user_id, :statut, :total)";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'user_id' => $data['user_id'],
            'statut' => $data['statut'] ?? 'en_attente',
            'total' => $data['total'] ?? 0.00
        ]);
    }

    public function updateStatus(int $id, string $status): bool
    {
        $pdo = Database::getPDO();
        $sql = "UPDATE commande SET statut = :statut WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'statut' => $status
        ]);
    }

    public function delete(int $id): bool
    {
        $pdo = Database::getPDO();
        $sql = "DELETE FROM commande WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function getProduitsByCommande(int $commandeId): array
    {
        $pdo = Database::getPDO();
        $sql = "SELECT cp.*, p.nom as produit_nom, p.description as produit_description 
                FROM commande_produit cp 
                LEFT JOIN produit p ON cp.product_id = p.id 
                WHERE cp.commande_id = :commande_id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['commande_id' => $commandeId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Getters et Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    public function getUserId() { return $this->user_id; }
    public function setUserId($user_id) { $this->user_id = $user_id; }
    public function getStatut() { return $this->statut; }
    public function setStatut($statut) { $this->statut = $statut; }
    public function getTotal() { return $this->total; }
    public function setTotal($total) { $this->total = $total; }
    public function getCreatedAt() { return $this->created_at; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }
    public function getUpdatedAt() { return $this->updated_at; }
    public function setUpdatedAt($updated_at) { $this->updated_at = $updated_at; }
}

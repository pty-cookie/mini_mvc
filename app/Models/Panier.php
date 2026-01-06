<?php

declare(strict_types=1);

namespace Mini\Models;

use Mini\Core\Database;
use PDO;

final class Panier
{
    private $id;
    private $user_id;
    private $product_id;
    private $quantite;
    private $created_at;
    private $updated_at;

    public function saveCartToDatabase(int $userId, array $cart): bool
    {
        $pdo = Database::getPDO();
        
        // Supprimer d'abord l'ancien panier
        $this->clearUserCart($userId);
        
        // Insérer les nouveaux articles
        foreach ($cart as $productId => $quantity) {
            $sql = "INSERT INTO panier (user_id, product_id, quantite) 
                    VALUES (:user_id, :product_id, :quantite)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantite' => $quantity
            ]);
        }
        
        return true;
    }

    public function loadCartFromDatabase(int $userId): array
    {
        $pdo = Database::getPDO();
        $sql = "SELECT product_id, quantite FROM panier WHERE user_id = :user_id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        
        $cart = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cart[$row['product_id']] = $row['quantite'];
        }
        
        return $cart;
    }

    public function clearUserCart(int $userId): bool
    {
        $pdo = Database::getPDO();
        $sql = "DELETE FROM panier WHERE user_id = :user_id";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute(['user_id' => $userId]);
    }

    public function addToCart(int $userId, int $productId, int $quantity): bool
    {
        $pdo = Database::getPDO();
        
        // Vérifier si le produit est déjà dans le panier
        $sql = "SELECT id, quantite FROM panier WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existing) {
            // Mettre à jour la quantité
            $newQuantity = $existing['quantite'] + $quantity;
            $sql = "UPDATE panier SET quantite = :quantite, updated_at = CURRENT_TIMESTAMP 
                    WHERE user_id = :user_id AND product_id = :product_id";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                'quantite' => $newQuantity,
                'user_id' => $userId,
                'product_id' => $productId
            ]);
        } else {
            // Ajouter nouvel article
            $sql = "INSERT INTO panier (user_id, product_id, quantite) 
                    VALUES (:user_id, :product_id, :quantite)";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantite' => $quantity
            ]);
        }
    }

    public function updateCartItem(int $userId, int $productId, int $quantity): bool
    {
        $pdo = Database::getPDO();
        
        if ($quantity <= 0) {
            // Supprimer l'article
            $sql = "DELETE FROM panier WHERE user_id = :user_id AND product_id = :product_id";
        } else {
            // Mettre à jour la quantité
            $sql = "UPDATE panier SET quantite = :quantite, updated_at = CURRENT_TIMESTAMP 
                    WHERE user_id = :user_id AND product_id = :product_id";
        }
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'quantite' => $quantity,
            'user_id' => $userId,
            'product_id' => $productId
        ]);
    }

    public function removeFromCart(int $userId, int $productId): bool
    {
        $pdo = Database::getPDO();
        $sql = "DELETE FROM panier WHERE user_id = :user_id AND product_id = :product_id";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
    }

    // Getters et Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    public function getUserId() { return $this->user_id; }
    public function setUserId($user_id) { $this->user_id = $user_id; }
    public function getProductId() { return $this->product_id; }
    public function setProductId($product_id) { $this->product_id = $product_id; }
    public function getQuantite() { return $this->quantite; }
    public function setQuantite($quantite) { $this->quantite = $quantite; }
    public function getCreatedAt() { return $this->created_at; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }
    public function getUpdatedAt() { return $this->updated_at; }
    public function setUpdatedAt($updated_at) { $this->updated_at = $updated_at; }
}

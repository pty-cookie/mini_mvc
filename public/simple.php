<?php
session_start();

// Simuler des données
$users = [
    ['id' => 1, 'nom' => 'toto', 'email' => 'toto@toto.toto'],
    ['id' => 2, 'nom' => 'tata', 'email' => 'tata@tata.toto']
];

$produits = [
    ['id' => 1, 'nom' => 'Ordinateur portable', 'description' => 'Ordinateur portable haute performance', 'prix' => 1299.99, 'stock' => 15],
    ['id' => 2, 'nom' => 'Souris', 'description' => 'Souris pour PC', 'prix' => 40.00, 'stock' => 52],
    ['id' => 3, 'nom' => 'Stylo', 'description' => 'Stylo 4 couleurs', 'prix' => 4.00, 'stock' => 60]
];

$categories = [
    ['id' => 1, 'nom' => 'Électronique', 'description' => 'Produits électroniques et gadgets'],
    ['id' => 2, 'nom' => 'Vêtements', 'description' => 'Vêtements et accessoires de mode']
];

$commandes = [
    ['id' => 1, 'total' => 136.00, 'created_at' => '2025-12-17 13:46:46', 'statut' => 'validee'],
    ['id' => 2, 'total' => 1327.99, 'created_at' => '2025-12-17 14:14:16', 'statut' => 'validee']
];

$isLoggedIn = isset($_SESSION['user_id']);
$currentUser = $isLoggedIn ? ['nom' => $_SESSION['user_name'] ?? 'Utilisateur'] : null;

// Inclure la vue
include dirname(__DIR__) . '/app/Views/home/dashboard.php';
?>

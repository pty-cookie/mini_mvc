<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /mini_mvc/public/cart.php');
    exit;
}

$product_id = $_POST['product_id'] ?? null;
$action = $_POST['action'] ?? null;

if (!$product_id || !$action || !isset($_SESSION['cart'][$product_id])) {
    header('Location: /mini_mvc/public/cart.php');
    exit;
}

require dirname(__DIR__) . '/vendor/autoload.php';
use Mini\Models\Produit;

$produitModel = new Produit();
$produit = $produitModel->getById((int)$product_id);

if (!$produit) {
    header('Location: /mini_mvc/public/cart.php');
    exit;
}

$current_quantity = $_SESSION['cart'][$product_id];

switch ($action) {
    case 'increase':
        if ($current_quantity < $produit['stock']) {
            $_SESSION['cart'][$product_id]++;
        }
        break;
    case 'decrease':
        if ($current_quantity > 1) {
            $_SESSION['cart'][$product_id]--;
        } else {
            // Si quantité = 0, supprimer du panier
            unset($_SESSION['cart'][$product_id]);
        }
        break;
}

// Sauvegarder le panier en base de données si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    $panierModel = new \Mini\Models\Panier();
    if (isset($_SESSION['cart'][$product_id])) {
        $panierModel->updateCartItem($_SESSION['user_id'], $product_id, $_SESSION['cart'][$product_id]);
    } else {
        $panierModel->removeFromCart($_SESSION['user_id'], $product_id);
    }
}

header('Location: /mini_mvc/public/cart.php');
exit;
?>

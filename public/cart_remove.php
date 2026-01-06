<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /mini_mvc/public/cart.php');
    exit;
}

$product_id = $_POST['product_id'] ?? null;

if (!$product_id || !isset($_SESSION['cart'][$product_id])) {
    header('Location: /mini_mvc/public/cart.php');
    exit;
}

unset($_SESSION['cart'][$product_id]);

// Sauvegarder le panier en base de données si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    $panierModel = new \Mini\Models\Panier();
    $panierModel->removeFromCart($_SESSION['user_id'], $product_id);
}

header('Location: /mini_mvc/public/cart.php');
exit;
?>

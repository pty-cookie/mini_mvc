<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /mini_mvc/public/products.php');
    exit;
}

$product_id = $_POST['product_id'] ?? null;
$quantity = $_POST['quantity'] ?? 1;

if (!$product_id || !is_numeric($product_id)) {
    header('Location: /mini_mvc/public/products.php');
    exit;
}

require dirname(__DIR__) . '/vendor/autoload.php';
use Mini\Models\Produit;

$produitModel = new Produit();
$produit = $produitModel->getById((int)$product_id);

if (!$produit) {
    header('Location: /mini_mvc/public/products.php');
    exit;
}

if ($produit['stock'] <= 0) {
    header('Location: /mini_mvc/public/product_detail.php?id=' . $product_id . '&error=out_of_stock');
    exit;
}

// Ajouter au panier
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] += $quantity;
} else {
    $_SESSION['cart'][$product_id] = $quantity;
}

// Vérifier que la quantité ne dépasse pas le stock
if ($_SESSION['cart'][$product_id] > $produit['stock']) {
    $_SESSION['cart'][$product_id] = $produit['stock'];
}

// Sauvegarder le panier en base de données si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    $panierModel = new \Mini\Models\Panier();
    $panierModel->addToCart($_SESSION['user_id'], $product_id, $_SESSION['cart'][$product_id]);
}

header('Location: /mini_mvc/public/cart.php');
exit;
?>

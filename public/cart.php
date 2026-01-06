<?php
session_start();
require dirname(__DIR__) . '/vendor/autoload.php';

use Mini\Models\Produit;

$isLoggedIn = isset($_SESSION['user_id']);

// Récupérer le panier
$cart = $_SESSION['cart'] ?? [];
$cart_count = 0;
$cart_total = 0.00;
$cart_items = [];

if (!empty($cart)) {
    $produitModel = new Produit();
    $product_ids = array_keys($cart);
    
    foreach ($product_ids as $product_id) {
        $product = $produitModel->getById((int)$product_id);
        if ($product) {
            $quantity = $cart[$product_id];
            $subtotal = $product['prix'] * $quantity;
            
            $cart_items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
            
            $cart_count += $quantity;
            $cart_total += $subtotal;
        }
    }
}

$_SESSION['cart_count'] = $cart_count;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier - Boutique E-Commerce</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .nav-buttons {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            gap: 10px;
        }

        .nav-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            font-size: 0.9em;
        }

        .nav-btn:hover {
            background: white;
            color: #4facfe;
            transform: translateY(-2px);
        }

        .cart-content {
            padding: 40px;
        }

        .empty-cart {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-cart-icon {
            font-size: 4em;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-cart h2 {
            color: #666;
            margin-bottom: 20px;
        }

        .empty-cart p {
            color: #999;
            margin-bottom: 30px;
        }

        .btn-shop {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-shop:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 172, 254, 0.3);
        }

        .cart-items {
            margin-bottom: 30px;
        }

        .cart-item {
            display: grid;
            grid-template-columns: 100px 1fr auto auto auto;
            gap: 20px;
            align-items: center;
            padding: 20px;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .item-image {
            width: 100px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-info h3 {
            color: #333;
            margin-bottom: 5px;
        }

        .item-info p {
            color: #666;
            font-size: 0.9em;
        }

        .item-quantity {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-btn {
            width: 30px;
            height: 30px;
            border: 1px solid #e9ecef;
            background: white;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .quantity-btn:hover {
            background: #f8f9fa;
        }

        .quantity-input {
            width: 50px;
            text-align: center;
            padding: 5px;
            border: 1px solid #e9ecef;
            border-radius: 5px;
        }

        .item-price {
            font-weight: bold;
            color: #495057;
            min-width: 80px;
            text-align: right;
        }

        .item-subtotal {
            font-weight: bold;
            color: #28a745;
            min-width: 100px;
            text-align: right;
        }

        .remove-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .remove-btn:hover {
            background: #c82333;
        }

        .cart-summary {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            margin-top: 30px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 1.1em;
        }

        .summary-row.total {
            font-size: 1.3em;
            font-weight: bold;
            color: #28a745;
            border-top: 2px solid #e9ecef;
            padding-top: 15px;
        }

        .checkout-section {
            margin-top: 30px;
            text-align: center;
        }

        .btn-checkout {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 25px;
            font-weight: bold;
            font-size: 1.1em;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);
        }

        .btn-checkout:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
        }

        .login-prompt {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 30px;
        }

        .login-prompt a {
            color: #856404;
            font-weight: bold;
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .cart-item {
                grid-template-columns: 80px 1fr;
                gap: 15px;
            }
            
            .item-quantity,
            .item-price,
            .item-subtotal,
            .remove-btn {
                grid-column: 2;
                justify-self: start;
                margin-top: 10px;
            }
            
            .nav-buttons {
                position: static;
                display: flex;
                justify-content: center;
                flex-wrap: wrap;
                gap: 10px;
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🛒 Votre Panier</h1>
            
            <div class="nav-buttons">
                <a href="/mini_mvc/public/products.php" class="nav-btn">🏪 Boutique</a>
                <?php if ($isLoggedIn): ?>
                    <a href="/mini_mvc/public/" class="nav-btn">🏠 Accueil</a>
                <?php endif; ?>
            </div>
        </header>

        <div class="cart-content">
            <?php if (empty($cart_items)): ?>
                <div class="empty-cart">
                    <div class="empty-cart-icon">🛒</div>
                    <h2>Votre panier est vide</h2>
                    <p>Découvrez nos produits et remplissez votre panier !</p>
                    <a href="/mini_mvc/public/products.php" class="btn-shop">🛍️ Commencer shopping</a>
                </div>
            <?php else: ?>
                <?php if (!$isLoggedIn): ?>
                    <div class="login-prompt">
                        ⚠️ Connectez-vous pour pouvoir finaliser votre commande.
                        <a href="/mini_mvc/public/login">Se connecter</a> ou 
                        <a href="/mini_mvc/public/register">Créer un compte</a>
                    </div>
                <?php endif; ?>

                <div class="cart-items">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="cart-item">
                            <img src="<?= htmlspecialchars($item['product']['image_url'] ?? 'https://via.placeholder.com/100x80') ?>" 
                                 alt="<?= htmlspecialchars($item['product']['nom']) ?>" class="item-image">
                            
                            <div class="item-info">
                                <h3><?= htmlspecialchars($item['product']['nom']) ?></h3>
                                <p><?= htmlspecialchars($item['product']['description'] ?? '') ?></p>
                            </div>
                            
                            <div class="item-quantity">
                                <form method="POST" action="/mini_mvc/public/cart_update.php" style="display: flex; align-items: center; gap: 10px;">
                                    <input type="hidden" name="product_id" value="<?= $item['product']['id'] ?>">
                                    <button type="submit" name="action" value="decrease" class="quantity-btn">-</button>
                                    <input type="number" name="quantity" class="quantity-input" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['product']['stock'] ?>" readonly>
                                    <button type="submit" name="action" value="increase" class="quantity-btn">+</button>
                                </form>
                            </div>
                            
                            <div class="item-price">
                                <?= number_format($item['product']['prix'], 2) ?> €
                            </div>
                            
                            <div class="item-subtotal">
                                <?= number_format($item['subtotal'], 2) ?> €
                            </div>
                            
                            <form method="POST" action="/mini_mvc/public/cart_remove.php">
                                <input type="hidden" name="product_id" value="<?= $item['product']['id'] ?>">
                                <button type="submit" class="remove-btn">🗑️</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-summary">
                    <div class="summary-row">
                        <span>Nombre d'articles :</span>
                        <span><?= $cart_count ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Sous-total :</span>
                        <span><?= number_format($cart_total, 2) ?> €</span>
                    </div>
                    <div class="summary-row">
                        <span>Livraison :</span>
                        <span>Gratuite</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total :</span>
                        <span><?= number_format($cart_total, 2) ?> €</span>
                    </div>
                </div>

                <div class="checkout-section">
                    <?php if ($isLoggedIn): ?>
                        <form method="POST" action="/mini_mvc/public/checkout.php">
                            <button type="submit" class="btn-checkout">
                                🚀 Valider la commande
                            </button>
                        </form>
                    <?php else: ?>
                        <button class="btn-checkout" disabled>
                            🔒 Connectez-vous pour commander
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

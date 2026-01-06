<?php
session_start();
require dirname(__DIR__) . '/vendor/autoload.php';

use Mini\Models\Produit;

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: /mini_mvc/public/products.php');
    exit;
}

$produitModel = new Produit();
$produit = $produitModel->getById((int)$_GET['id']);

if (!$produit) {
    header('Location: /mini_mvc/public/products.php');
    exit;
}

$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($produit['nom']) ?> - Boutique E-Commerce</title>
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

        .product-detail {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            padding: 40px;
        }

        .product-image-section {
            text-align: center;
        }

        .product-image {
            width: 100%;
            max-width: 500px;
            height: 400px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .product-info-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .product-title {
            font-size: 2.5em;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }

        .product-price {
            font-size: 2em;
            color: #28a745;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .product-stock {
            font-size: 1.2em;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
        }

        .in-stock {
            background: #d4edda;
            color: #155724;
        }

        .out-of-stock {
            background: #f8d7da;
            color: #721c24;
        }

        .product-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
            font-size: 1.1em;
        }

        .product-meta {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .meta-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
        }

        .meta-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .meta-label {
            font-weight: bold;
            color: #495057;
        }

        .meta-value {
            color: #6c757d;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            font-size: 1.1em;
        }

        .btn-back {
            background: #6c757d;
            color: white;
        }

        .btn-back:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-add-cart {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .btn-add-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);
        }

        .btn-add-cart:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
        }

        .quantity-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .quantity-label {
            font-weight: bold;
            color: #495057;
        }

        .quantity-input {
            width: 80px;
            padding: 10px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1.1em;
            text-align: center;
        }

        .quantity-input:focus {
            outline: none;
            border-color: #4facfe;
        }

        @media (max-width: 768px) {
            .product-detail {
                grid-template-columns: 1fr;
                padding: 20px;
                gap: 20px;
            }
            
            .product-title {
                font-size: 2em;
            }
            
            .product-price {
                font-size: 1.5em;
            }
            
            .nav-buttons {
                position: static;
                display: flex;
                justify-content: center;
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🛍️ Détails du Produit</h1>
            
            <div class="nav-buttons">
                <a href="/mini_mvc/public/products.php" class="nav-btn">🏪 Retour boutique</a>
                <a href="/mini_mvc/public/cart.php" class="nav-btn">🛒 Panier</a>
                <?php if ($isLoggedIn): ?>
                    <a href="/mini_mvc/public/" class="nav-btn">🏠 Accueil</a>
                <?php endif; ?>
            </div>
        </header>

        <div class="product-detail">
            <div class="product-image-section">
                <img src="<?= htmlspecialchars($produit['image_url'] ?? 'https://via.placeholder.com/500x400') ?>" 
                     alt="<?= htmlspecialchars($produit['nom']) ?>" class="product-image">
            </div>
            
            <div class="product-info-section">
                <h2 class="product-title"><?= htmlspecialchars($produit['nom']) ?></h2>
                <div class="product-price"><?= number_format($produit['prix'], 2) ?> €</div>
                
                <div class="product-stock <?= $produit['stock'] > 0 ? 'in-stock' : 'out-of-stock' ?>">
                    <?php if ($produit['stock'] > 0): ?>
                        ✅ En stock (<?= $produit['stock'] ?> unités disponibles)
                    <?php else: ?>
                        ❌ Rupture de stock
                    <?php endif; ?>
                </div>
                
                <div class="product-description">
                    <?= htmlspecialchars($produit['description'] ?? 'Découvrez ce produit exceptionnel de grande qualité. Parfait pour répondre à tous vos besoins.') ?>
                </div>
                
                <div class="product-meta">
                    <div class="meta-item">
                        <span class="meta-label">Référence :</span>
                        <span class="meta-value">#<?= str_pad($produit['id'], 4, '0', STR_PAD_LEFT) ?></span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Catégorie :</span>
                        <span class="meta-value"><?= htmlspecialchars($produit['categorie_nom'] ?? 'Non classé') ?></span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Disponibilité :</span>
                        <span class="meta-value"><?= $produit['stock'] > 0 ? 'Immédiate' : 'Rupture' ?></span>
                    </div>
                </div>
                
                <?php if ($produit['stock'] > 0): ?>
                    <form method="POST" action="/mini_mvc/public/cart_add.php">
                        <input type="hidden" name="product_id" value="<?= $produit['id'] ?>">
                        
                        <div class="quantity-section">
                            <span class="quantity-label">Quantité :</span>
                            <input type="number" name="quantity" class="quantity-input" value="1" min="1" max="<?= $produit['stock'] ?>">
                        </div>
                        
                        <div class="action-buttons">
                            <a href="/mini_mvc/public/products.php" class="btn btn-back">← Continuer shopping</a>
                            <button type="submit" class="btn btn-add-cart">
                                🛒 Ajouter au panier
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="action-buttons">
                        <a href="/mini_mvc/public/products.php" class="btn btn-back">← Continuer shopping</a>
                        <button class="btn btn-add-cart" disabled>
                            ❌ Indisponible
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

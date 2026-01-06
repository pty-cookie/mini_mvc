<?php
session_start();
require dirname(__DIR__) . '/vendor/autoload.php';

use Mini\Models\Produit;
use Mini\Models\Categorie;

$produitModel = new Produit();
$categorieModel = new Categorie();

$produits = $produitModel->getAll();
$categories = $categorieModel->getAll();

$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique E-Commerce - Nos Produits</title>
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

        .auth-buttons {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
        }

        .login-btn, .register-btn {
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

        .login-btn:hover, .register-btn:hover {
            background: white;
            color: #4facfe;
            transform: translateY(-2px);
        }

        .user-info {
            position: absolute;
            top: 20px;
            right: 20px;
            text-align: right;
        }

        .welcome-msg {
            display: block;
            color: white;
            font-size: 0.9em;
            margin-bottom: 5px;
            opacity: 0.9;
        }

        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: white;
            color: #4facfe;
            transform: translateY(-2px);
        }

        .cart-info {
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .cart-link {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .cart-link:hover {
            background: white;
            color: #4facfe;
            transform: translateY(-2px);
        }

        .filters {
            padding: 30px;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .filter-btn {
            background: white;
            border: 2px solid #e9ecef;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .filter-btn:hover, .filter-btn.active {
            background: #4facfe;
            color: white;
            border-color: #4facfe;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            padding: 30px;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #f8f9fa;
        }

        .product-info {
            padding: 20px;
        }

        .product-title {
            font-size: 1.3em;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .product-description {
            color: #666;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .product-price {
            font-size: 1.5em;
            color: #28a745;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .product-stock {
            color: #6c757d;
            margin-bottom: 15px;
            font-size: 0.9em;
        }

        .product-actions {
            display: flex;
            gap: 10px;
        }

        .btn-details, .btn-add-cart {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
        }

        .btn-details {
            background: #6c757d;
            color: white;
        }

        .btn-details:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-add-cart {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .btn-add-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-add-cart:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
        }

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: 1fr;
                padding: 20px;
            }
            
            .auth-buttons, .user-info, .cart-info {
                position: static;
                display: flex;
                justify-content: center;
                margin: 10px 0;
            }
            
            header h1 {
                font-size: 1.8em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🛍️ Notre Boutique</h1>
            <p>Découvrez nos produits exceptionnels</p>
            
            <div class="cart-info">
                <a href="/mini_mvc/public/cart.php" class="cart-link">
                    🛒 Panier
                    <?php if (isset($_SESSION['cart_count'])): ?>
                        (<?= $_SESSION['cart_count'] ?>)
                    <?php endif; ?>
                </a>
            </div>
            
            <?php if ($isLoggedIn): ?>
                <div class="user-info">
                    <span class="welcome-msg">👤 Bienvenue, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Utilisateur') ?>!</span>
                    <a href="/mini_mvc/public/logout" class="logout-btn">🚪 Se déconnecter</a>
                </div>
            <?php else: ?>
                <div class="auth-buttons">
                    <a href="/mini_mvc/public/login" class="login-btn">🔑 Se connecter</a>
                    <a href="/mini_mvc/public/register" class="register-btn">📝 S'inscrire</a>
                </div>
            <?php endif; ?>
        </header>

        <div class="filters">
            <div class="filter-buttons">
                <button class="filter-btn active" data-category="all">Tous les produits</button>
                <?php foreach ($categories as $categorie): ?>
                    <button class="filter-btn" data-category="<?= $categorie['id'] ?>">
                        <?= htmlspecialchars($categorie['nom']) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="products-grid">
            <?php foreach ($produits as $produit): ?>
                <div class="product-card" data-category="<?= $produit['categorie_id'] ?? 'all' ?>">
                    <img src="<?= htmlspecialchars($produit['image_url'] ?? 'https://via.placeholder.com/300x200') ?>" 
                         alt="<?= htmlspecialchars($produit['nom']) ?>" class="product-image">
                    
                    <div class="product-info">
                        <h3 class="product-title"><?= htmlspecialchars($produit['nom']) ?></h3>
                        <p class="product-description"><?= htmlspecialchars($produit['description'] ?? 'Découvrez ce produit exceptionnel') ?></p>
                        <div class="product-price"><?= number_format($produit['prix'], 2) ?> €</div>
                        <div class="product-stock">
                            <?php if ($produit['stock'] > 0): ?>
                                ✅ En stock (<?= $produit['stock'] ?> unités)
                            <?php else: ?>
                                ❌ Rupture de stock
                            <?php endif; ?>
                        </div>
                        <div class="product-actions">
                            <a href="/mini_mvc/public/product_detail.php?id=<?= $produit['id'] ?>" class="btn-details">
                                👁️ Détails
                            </a>
                            <?php if ($produit['stock'] > 0): ?>
                                <form method="POST" action="/mini_mvc/public/cart_add.php" style="flex: 1;">
                                    <input type="hidden" name="product_id" value="<?= $produit['id'] ?>">
                                    <button type="submit" class="btn-add-cart">
                                        🛒 Ajouter
                                    </button>
                                </form>
                            <?php else: ?>
                                <button class="btn-add-cart" disabled>
                                    ❌ Indisponible
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        // Filtrage par catégorie
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Retirer la classe active de tous les boutons
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                // Ajouter la classe active au bouton cliqué
                this.classList.add('active');
                
                const category = this.dataset.category;
                const products = document.querySelectorAll('.product-card');
                
                products.forEach(product => {
                    if (category === 'all' || product.dataset.category === category) {
                        product.style.display = 'block';
                    } else {
                        product.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>

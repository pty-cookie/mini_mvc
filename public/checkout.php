<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: /mini_mvc/public/login');
    exit;
}

// Vérifier si le panier n'est pas vide
if (empty($_SESSION['cart'])) {
    header('Location: /mini_mvc/public/cart.php');
    exit;
}

require dirname(__DIR__) . '/vendor/autoload.php';
use Mini\Models\Produit;
use Mini\Models\Commande;

$produitModel = new Produit();
$commandeModel = new Commande();

// Récupérer les détails du panier
$cart = $_SESSION['cart'];
$cart_items = [];
$total_amount = 0.00;

foreach ($cart as $product_id => $quantity) {
    $product = $produitModel->getById((int)$product_id);
    if ($product && $product['stock'] >= $quantity) {
        $subtotal = $product['prix'] * $quantity;
        $cart_items[] = [
            'product' => $product,
            'quantity' => $quantity,
            'subtotal' => $subtotal
        ];
        $total_amount += $subtotal;
    }
}

if (empty($cart_items)) {
    header('Location: /mini_mvc/public/cart.php');
    exit;
}

// Créer la commande
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Créer la commande principale
        $commande_data = [
            'user_id' => $_SESSION['user_id'],
            'statut' => 'validee',
            'total' => $total_amount
        ];
        
        if ($commandeModel->create($commande_data)) {
            // Récupérer l'ID de la dernière commande
            $pdo = \Mini\Core\Database::getPDO();
            $commande_id = $pdo->lastInsertId();
            
            // Ajouter les produits à la commande
            foreach ($cart_items as $item) {
                $sql = "INSERT INTO commande_produit (commande_id, product_id, quantite, prix_unitaire) 
                        VALUES (:commande_id, :product_id, :quantite, :prix_unitaire)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'commande_id' => $commande_id,
                    'product_id' => $item['product']['id'],
                    'quantite' => $item['quantity'],
                    'prix_unitaire' => $item['product']['prix']
                ]);
                
                // Mettre à jour le stock
                $new_stock = $item['product']['stock'] - $item['quantity'];
                $update_sql = "UPDATE produit SET stock = :stock WHERE id = :id";
                $update_stmt = $pdo->prepare($update_sql);
                $update_stmt->execute([
                    'stock' => $new_stock,
                    'id' => $item['product']['id']
                ]);
            }
            
            // Sauvegarder le panier
            $panierModel = new \Mini\Models\Panier();
            $panierModel->saveUserCart($_SESSION['user_id'], $cart);
            
            // Vider le panier
            unset($_SESSION['cart']);
            unset($_SESSION['cart_count']);
            
            // Vider aussi le panier en base de données
            $panierModel->clearUserCart($_SESSION['user_id']);
            
            // Rediriger vers la page de confirmation
            header('Location: /mini_mvc/public/order_confirmation.php?id=' . $commande_id);
            exit;
        }
    } catch (Exception $e) {
        $error = "Une erreur est survenue lors de la validation de votre commande.";
    }
}

$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation de Commande - Boutique E-Commerce</title>
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
            max-width: 800px;
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

        .checkout-content {
            padding: 40px;
        }

        .section-title {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4facfe;
        }

        .order-summary {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .summary-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            font-size: 1.2em;
            font-weight: bold;
            color: #28a745;
        }

        .product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: white;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 1px solid #e9ecef;
        }

        .product-info {
            flex: 1;
        }

        .product-name {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .product-details {
            color: #666;
            font-size: 0.9em;
        }

        .product-price {
            font-weight: bold;
            color: #495057;
            margin-right: 20px;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        .checkout-form {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #4facfe;
        }

        .btn-section {
            text-align: center;
            margin-top: 30px;
        }

        .btn {
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

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            margin-right: 15px;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        @media (max-width: 768px) {
            .checkout-content {
                padding: 20px;
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
            <h1>🚀 Validation de Commande</h1>
            
            <div class="nav-buttons">
                <a href="/mini_mvc/public/cart.php" class="nav-btn">🛒 Retour panier</a>
                <a href="/mini_mvc/public/products.php" class="nav-btn">🏪 Boutique</a>
            </div>
        </header>

        <div class="checkout-content">
            <?php if (isset($error)): ?>
                <div class="error-message">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <h2 class="section-title">📋 Récapitulatif de votre commande</h2>
            
            <div class="order-summary">
                <?php foreach ($cart_items as $item): ?>
                    <div class="product-item">
                        <div class="product-info">
                            <div class="product-name"><?= htmlspecialchars($item['product']['nom']) ?></div>
                            <div class="product-details">
                                Quantité: <?= $item['quantity'] ?> × <?= number_format($item['product']['prix'], 2) ?> €
                            </div>
                        </div>
                        <div class="product-price">
                            <?= number_format($item['subtotal'], 2) ?> €
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <div class="summary-item">
                    <span>Total de la commande:</span>
                    <span><?= number_format($total_amount, 2) ?> €</span>
                </div>
            </div>

            <h2 class="section-title">👤 Informations de livraison</h2>
            
            <div class="checkout-form">
                <form method="POST">
                    <div class="form-group">
                        <label for="address">Adresse de livraison</label>
                        <textarea id="address" name="address" rows="3" required 
                                  placeholder="Entrez votre adresse complète"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">Notes (optionnel)</label>
                        <textarea id="notes" name="notes" rows="2" 
                                  placeholder="Instructions spéciales pour la livraison..."></textarea>
                    </div>
                    
                    <div class="btn-section">
                        <a href="/mini_mvc/public/cart.php" class="btn btn-secondary">← Modifier le panier</a>
                        <button type="submit" class="btn">🚀 Confirmer la commande</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

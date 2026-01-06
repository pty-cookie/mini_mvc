<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /mini_mvc/public/login');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: /mini_mvc/public/');
    exit;
}

require dirname(__DIR__) . '/vendor/autoload.php';
use Mini\Models\Commande;

$commandeModel = new Commande();
$commande = $commandeModel->getById((int)$_GET['id']);

if (!$commande || $commande['user_id'] != $_SESSION['user_id']) {
    header('Location: /mini_mvc/public/');
    exit;
}

// Récupérer les produits de la commande
$commande_products = $commandeModel->getProduitsByCommande($commande['id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande Confirmée - Boutique E-Commerce</title>
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 40px;
            text-align: center;
            position: relative;
        }

        header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .success-icon {
            font-size: 4em;
            margin-bottom: 20px;
            animation: bounce 1s ease-in-out;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-20px);
            }
            60% {
                transform: translateY(-10px);
            }
        }

        .confirmation-content {
            padding: 40px;
        }

        .order-info {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .info-label {
            font-weight: bold;
            color: #495057;
        }

        .info-value {
            color: #6c757d;
        }

        .order-status {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.9em;
        }

        .status-validee {
            background: #d4edda;
            color: #155724;
        }

        .products-list {
            margin-bottom: 30px;
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
        }

        .total-section {
            background: #e8f5e8;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 30px;
        }

        .total-amount {
            font-size: 1.5em;
            font-weight: bold;
            color: #28a745;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 172, 254, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .confirmation-content {
                padding: 20px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="success-icon">✅</div>
            <h1>Commande Confirmée !</h1>
            <p>Merci pour votre achat. Votre commande a été validée avec succès.</p>
        </header>

        <div class="confirmation-content">
            <div class="order-info">
                <h2 style="margin-bottom: 20px; color: #333;">📋 Détails de la commande</h2>
                
                <div class="info-row">
                    <span class="info-label">Numéro de commande :</span>
                    <span class="info-value">#<?= str_pad($commande['id'], 4, '0', STR_PAD_LEFT) ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Date :</span>
                    <span class="info-value"><?= date('d/m/Y à H:i', strtotime($commande['created_at'])) ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Statut :</span>
                    <span class="order-status status-validee">Validée</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Client :</span>
                    <span class="info-value"><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                </div>
            </div>

            <h2 style="margin-bottom: 20px; color: #333;">📦 Articles commandés</h2>
            
            <div class="products-list">
                <?php foreach ($commande_products as $item): ?>
                    <div class="product-item">
                        <div class="product-info">
                            <div class="product-name"><?= htmlspecialchars($item['produit_nom']) ?></div>
                            <div class="product-details">
                                Quantité: <?= $item['quantite'] ?> × <?= number_format($item['prix_unitaire'], 2) ?> €
                            </div>
                        </div>
                        <div class="product-price">
                            <?= number_format($item['quantite'] * $item['prix_unitaire'], 2) ?> €
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="total-section">
                <div class="total-amount">
                    Total payé : <?= number_format($commande['total'], 2) ?> €
                </div>
            </div>

            <div class="action-buttons">
                <a href="/mini_mvc/public/products.php" class="btn btn-primary">
                    🛍️ Continuer shopping
                </a>
                <a href="/mini_mvc/public/" class="btn btn-secondary">
                    🏠 Mon espace
                </a>
            </div>
        </div>
    </div>
</body>
</html>

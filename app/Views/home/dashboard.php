<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Mini MVC</title>
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

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
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

        .register-btn {
            background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.7);
        }

        .register-btn:hover {
            background: white;
            color: #764ba2;
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
            margin-right: 10px;
        }

        .nav-btn:hover {
            background: white;
            color: #4facfe;
            transform: translateY(-2px);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            transform: translateY(0);
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        .stat-card h3 {
            font-size: 1.2em;
            margin-bottom: 10px;
            opacity: 0.9;
        }

        .stat-card .number {
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .data-section {
            padding: 30px;
        }

        .data-section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.8em;
            border-bottom: 3px solid #4facfe;
            padding-bottom: 10px;
        }

        .data-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .data-card {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .data-card:hover {
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .data-card h4 {
            color: #495057;
            margin-bottom: 10px;
            font-size: 1.1em;
        }

        .data-card p {
            color: #6c757d;
            margin: 5px 0;
        }

        .price {
            color: #28a745;
            font-weight: bold;
            font-size: 1.2em;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status.validee {
            background: #d4edda;
            color: #155724;
        }

        .status.en_attente {
            background: #fff3cd;
            color: #856404;
        }

        .status.annulee {
            background: #f8d7da;
            color: #721c24;
        }

        footer {
            background: #343a40;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .data-grid {
                grid-template-columns: 1fr;
            }
            
            header h1 {
                font-size: 1.8em;
            }
            
            .logout-btn, .auth-buttons, .user-info {
                position: static;
                display: block;
                width: fit-content;
                margin: 20px auto 0;
                text-align: center;
            }
            
            .auth-buttons {
                justify-content: center;
                gap: 10px;
            }
            
            .welcome-msg {
                text-align: center;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🛍️ Tableau de Bord E-Commerce</h1>
            <p>Bienvenue sur votre plateforme de gestion</p>
            
            <?php if ($isLoggedIn): ?>
                <div class="user-info">
                    <span class="welcome-msg">👤 Bienvenue, <?= htmlspecialchars($currentUser['nom'] ?? 'Utilisateur') ?>!</span>
                    <a href="/mini_mvc/public/logout" class="logout-btn">🚪 Se déconnecter</a>
                </div>
            <?php else: ?>
                <div class="auth-buttons">
                    <a href="/mini_mvc/public/login" class="login-btn">🔑 Se connecter</a>
                    <a href="/mini_mvc/public/register" class="register-btn">📝 S'inscrire</a>
                </div>
            <?php endif; ?>
            <div class="cart-info">
                <a href="/mini_mvc/public/products.php" class="nav-btn">🏪 Boutique</a>
                <a href="/mini_mvc/public/cart.php" class="cart-link">
                    🛒 Panier
                    <?php if (isset($_SESSION['cart_count'])): ?>
                        (<?= $_SESSION['cart_count'] ?>)
                    <?php endif; ?>
                </a>
            </div>
        </header>

        <section class="stats-grid">
            <div class="stat-card">
                <h3>👥 Utilisateurs</h3>
                <div class="number"><?= count($users) ?></div>
                <p>Total inscrits</p>
            </div>
            <div class="stat-card">
                <h3>📦 Produits</h3>
                <div class="number"><?= count($produits) ?></div>
                <p>En catalogue</p>
            </div>
            <div class="stat-card">
                <h3>🏷️ Catégories</h3>
                <div class="number"><?= count($categories) ?></div>
                <p>Disponibles</p>
            </div>
            <div class="stat-card">
                <h3>💰 Commandes</h3>
                <div class="number"><?= count($commandes) ?></div>
                <p>Vos commandes</p>
            </div>
        </section>

        <section class="data-section">
            <h2>📦 Produits Récents</h2>
            <div class="data-grid">
                <?php foreach (array_slice($produits, 0, 6) as $produit): ?>
                    <div class="data-card">
                        <h4><?= htmlspecialchars($produit['nom']) ?></h4>
                        <p><?= htmlspecialchars($produit['description'] ?? 'Pas de description') ?></p>
                        <p class="price"><?= number_format($produit['prix'], 2) ?> €</p>
                        <p>📊 Stock: <?= $produit['stock'] ?> unités</p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="data-section">
            <h2>🏷️ Catégories</h2>
            <div class="data-grid">
                <?php foreach ($categories as $categorie): ?>
                    <div class="data-card">
                        <h4><?= htmlspecialchars($categorie['nom']) ?></h4>
                        <p><?= htmlspecialchars($categorie['description'] ?? 'Pas de description') ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <?php if (!empty($commandes)): ?>
        <section class="data-section">
            <h2>📋 Vos Commandes</h2>
            <div class="data-grid">
                <?php foreach ($commandes as $commande): ?>
                    <div class="data-card">
                        <h4>Commande #<?= $commande['id'] ?></h4>
                        <p class="price"><?= number_format($commande['total'], 2) ?> €</p>
                        <p>Date: <?= date('d/m/Y', strtotime($commande['created_at'])) ?></p>
                        <span class="status <?= $commande['statut'] ?>"><?= str_replace('_', ' ', $commande['statut']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <footer>
            <p>&copy; 2026 Mini MVC E-Commerce. Tous droits réservés.</p>
        </footer>
    </div>
</body>
</html>

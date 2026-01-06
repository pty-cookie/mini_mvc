<?php
session_start();

// Inclure l'autoload de Composer
require dirname(__DIR__) . '/vendor/autoload.php';

// Router simplifié
$request_uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Nettoyer l'URI pour enlever le dossier mini_mvc/public/
$base_path = str_replace('/mini_mvc/public', '', $request_uri);
$path = parse_url($base_path, PHP_URL_PATH) ?? '/';

// Alternative : si ça ne marche pas, essayer avec cette approche
if (strpos($request_uri, '/mini_mvc/public') !== false) {
    $path = str_replace('/mini_mvc/public', '', parse_url($request_uri, PHP_URL_PATH));
} else {
    $path = parse_url($request_uri, PHP_URL_PATH) ?? '/';
}

// Définir l'état de connexion pour toutes les pages
$isLoggedIn = isset($_SESSION['user_id']);
$currentUser = null;

// Router simple
switch ($path) {
    case '/':
        // Page d'accueil - Tableau de bord
        $userModel = new \Mini\Models\User();
        $produitModel = new \Mini\Models\Produit();
        $categorieModel = new \Mini\Models\Categorie();
        $commandeModel = new \Mini\Models\Commande();
        
        $users = $userModel->getAll();
        $produits = $produitModel->getAll();
        $categories = $categorieModel->getAll();
        
        if ($isLoggedIn) {
            $currentUser = array_filter($users, fn($u) => $u['id'] == $_SESSION['user_id']);
            $currentUser = array_values($currentUser)[0] ?? null;
            $commandes = $commandeModel->getByUserId($_SESSION['user_id']);
        } else {
            $commandes = $commandeModel->getAll();
        }
        
        include dirname(__DIR__) . '/app/Views/home/dashboard.php';
        break;
        
    case '/login':
        if ($isLoggedIn) {
            header('Location: /mini_mvc/public/');
            exit;
        }
        include dirname(__DIR__) . '/app/Views/auth/login.php';
        break;
        
    case '/auth/authenticate':
        if ($method === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $userModel = new \Mini\Models\User();
            $users = $userModel->getAll();
            
            $user = null;
            foreach ($users as $u) {
                if ($u['email'] === $email) {
                    $user = $u;
                    break;
                }
            }
            
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nom'];
                $_SESSION['user_email'] = $user['email'];
                
                // Restaurer le panier depuis la base de données
                $panierModel = new \Mini\Models\Panier();
                $savedCart = $panierModel->loadCartFromDatabase($user['id']);
                
                if (!empty($savedCart)) {
                    $_SESSION['cart'] = $savedCart;
                    
                    // Recalculer le nombre d'articles
                    $cart_count = array_sum($savedCart);
                    $_SESSION['cart_count'] = $cart_count;
                }
                
                header('Location: /mini_mvc/public/');
                exit;
            } else {
                $error = 'Email ou mot de passe incorrect.';
                include dirname(__DIR__) . '/app/Views/auth/login.php';
            }
        }
        break;
        
    case '/register':
        if ($isLoggedIn) {
            header('Location: /mini_mvc/public/');
            exit;
        }
        include dirname(__DIR__) . '/app/Views/auth/register.php';
        break;
        
    case '/auth/store':
        if ($method === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';
            
            if (empty($nom) || empty($email) || empty($password)) {
                $error = 'Veuillez remplir tous les champs.';
                include dirname(__DIR__) . '/app/Views/auth/register.php';
                break;
            }
            
            if ($password !== $password_confirm) {
                $error = 'Les mots de passe ne correspondent pas.';
                include dirname(__DIR__) . '/app/Views/auth/register.php';
                break;
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Format d\'email invalide.';
                include dirname(__DIR__) . '/app/Views/auth/register.php';
                break;
            }
            
            $user = new \Mini\Models\User();
            $user->setNom($nom);
            $user->setEmail($email);
            
            if ($user->save()) {
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['user_name'] = $user->getNom();
                $_SESSION['user_email'] = $user->getEmail();
                
                // Restaurer le panier depuis la base de données (au cas où il y en avait un avant)
                $panierModel = new \Mini\Models\Panier();
                $savedCart = $panierModel->loadCartFromDatabase($user->getId());
                
                if (!empty($savedCart)) {
                    $_SESSION['cart'] = $savedCart;
                    
                    // Recalculer le nombre d'articles
                    $cart_count = array_sum($savedCart);
                    $_SESSION['cart_count'] = $cart_count;
                }
                
                header('Location: /mini_mvc/public/');
                exit;
            } else {
                $error = 'Erreur lors de la création du compte.';
                include dirname(__DIR__) . '/app/Views/auth/register.php';
            }
        }
        break;
        
    case '/logout':
    case '/home/logout':
        // Sauvegarder le panier avant déconnexion si l'utilisateur est connecté
        if ($isLoggedIn && !empty($_SESSION['cart'])) {
            $panierModel = new \Mini\Models\Panier();
            $panierModel->saveCartToDatabase($_SESSION['user_id'], $_SESSION['cart']);
        }
        
        session_start();
        session_destroy();
        header('Location: /mini_mvc/public/');
        exit;
        break;
        
    default:
        http_response_code(404);
        echo '<h1>404 - Page non trouvée</h1>';
        echo '<p><a href="/mini_mvc/public/">Retour à l\'accueil</a></p>';
        break;
}
?>
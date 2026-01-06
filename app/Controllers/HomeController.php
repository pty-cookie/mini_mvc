<?php

// Active le mode strict pour la vérification des types
declare(strict_types=1);
// Déclare l'espace de noms pour ce contrôleur
namespace Mini\Controllers;
// Importe la classe de base Controller du noyau
use Mini\Core\Controller;
use Mini\Models\User;
use Mini\Models\Produit;
use Mini\Models\Categorie;
use Mini\Models\Commande;

// Déclare la classe finale HomeController qui hérite de Controller
final class HomeController extends Controller
{
    // Déclare la méthode d'action par défaut qui ne retourne rien
    public function index(): void
    {
        // Démarrer la session
        session_start();
        
        // Vérifier si l'utilisateur est connecté
        $isLoggedIn = isset($_SESSION['user_id']);
        $currentUser = null;
        
        if ($isLoggedIn) {
            // Récupérer les informations de l'utilisateur connecté
            $userModel = new User();
            $users = $userModel->getAll();
            $currentUser = array_filter($users, fn($u) => $u['id'] == $_SESSION['user_id']);
            $currentUser = array_values($currentUser)[0] ?? null;
        } else {
            $currentUser = null;
        }
        
        // Récupérer les données depuis les modèles
        $userModel = new User();
        $produitModel = new Produit();
        $categorieModel = new Categorie();
        $commandeModel = new Commande();
        
        $users = $userModel->getAll();
        $produits = $produitModel->getAll();
        $categories = $categorieModel->getAll();
        
        // Récupérer les commandes de l'utilisateur connecté ou toutes les commandes
        $commandes = [];
        if ($isLoggedIn) {
            $commandes = $commandeModel->getByUserId($_SESSION['user_id']);
        } else {
            $commandes = $commandeModel->getAll();
        }
        
        // Afficher le tableau de bord
        $this->render('home/dashboard', params: [
            'users' => $users,
            'produits' => $produits,
            'categories' => $categories,
            'commandes' => $commandes,
            'isLoggedIn' => $isLoggedIn,
            'currentUser' => $currentUser
        ]);
    }

    public function users(): void
    {
        // Récupère tous les utilisateurs
        $users = User::getAll();
        
        // Définit le header Content-Type pour indiquer que la réponse est du JSON
        header('Content-Type: application/json; charset=utf-8');
        
        // Encode les données en JSON et les affiche
        echo json_encode($users, JSON_PRETTY_PRINT);
    }

    public function showCreateUserForm(): void
    {
        // Affiche le formulaire de création d'utilisateur
        $this->render('home/create-user', params: [
            'title' => 'Créer un utilisateur'
        ]);
    }

    public function createUser(): void
    {
        // Définit le header Content-Type pour indiquer que la réponse est du JSON
        header('Content-Type: application/json; charset=utf-8');
        
        // Vérifie que la méthode HTTP est POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée. Utilisez POST.'], JSON_PRETTY_PRINT);
            return;
        }
        
        // Récupère les données JSON du body de la requête
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Si pas de JSON, essaie de récupérer depuis $_POST
        if ($input === null) {
            $input = $_POST;
        }
        
        // Valide les données requises
        if (empty($input['nom']) || empty($input['email'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Les champs "nom" et "email" sont requis.'], JSON_PRETTY_PRINT);
            return;
        }
        
        // Valide le format de l'email
        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['error' => 'Format d\'email invalide.'], JSON_PRETTY_PRINT);
            return;
        }
        
        
        // Crée une nouvelle instance User
        $user = new User();
        $user->setNom($input['nom']);
        $user->setEmail($input['email']);
        
        // Sauvegarde l'utilisateur
        if ($user->save()) {
            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'Utilisateur créé avec succès.',
                'user' => [
                    'nom' => $user->getnom(),
                    'email' => $user->getEmail()
                ]
            ], JSON_PRETTY_PRINT);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la création de l\'utilisateur.'], JSON_PRETTY_PRINT);
        }
    }

    public function logout(): void
    {
        session_start();
        session_destroy();
        header('Location: /');
        exit;
    }
}
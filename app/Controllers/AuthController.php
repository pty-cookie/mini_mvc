<?php

declare(strict_types=1);

namespace Mini\Controllers;

use Mini\Core\Controller;
use Mini\Models\User;

final class AuthController extends Controller
{
    public function login(): void
    {
        session_start();
        
        // Si déjà connecté, rediriger vers l'accueil
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }
        
        $this->render('auth/login', params: [
            'title' => 'Connexion'
        ]);
    }
    
    public function authenticate(): void
    {
        session_start();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée.'], JSON_PRETTY_PRINT);
            return;
        }
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $this->render('auth/login', params: [
                'title' => 'Connexion',
                'error' => 'Veuillez remplir tous les champs.'
            ]);
            return;
        }
        
        // Pour l'instant, on simule une connexion simple
        // Dans une vraie application, il faudrait vérifier le mot de passe hashé
        $userModel = new User();
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
            
            header('Location: /');
            exit;
        } else {
            $this->render('auth/login', params: [
                'title' => 'Connexion',
                'error' => 'Email ou mot de passe incorrect.'
            ]);
        }
    }
    
    public function register(): void
    {
        session_start();
        
        // Si déjà connecté, rediriger vers l'accueil
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }
        
        $this->render('auth/register', params: [
            'title' => 'Inscription'
        ]);
    }
    
    public function store(): void
    {
        session_start();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée.'], JSON_PRETTY_PRINT);
            return;
        }
        
        $nom = $_POST['nom'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        
        if (empty($nom) || empty($email) || empty($password)) {
            $this->render('auth/register', params: [
                'title' => 'Inscription',
                'error' => 'Veuillez remplir tous les champs.'
            ]);
            return;
        }
        
        if ($password !== $password_confirm) {
            $this->render('auth/register', params: [
                'title' => 'Inscription',
                'error' => 'Les mots de passe ne correspondent pas.'
            ]);
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->render('auth/register', params: [
                'title' => 'Inscription',
                'error' => 'Format d\'email invalide.'
            ]);
            return;
        }
        
        // Créer l'utilisateur
        $user = new User();
        $user->setNom($nom);
        $user->setEmail($email);
        
        if ($user->save()) {
            // Connecter automatiquement l'utilisateur
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['user_name'] = $user->getNom();
            $_SESSION['user_email'] = $user->getEmail();
            
            header('Location: /');
            exit;
        } else {
            $this->render('auth/register', params: [
                'title' => 'Inscription',
                'error' => 'Erreur lors de la création du compte.'
            ]);
        }
    }
}

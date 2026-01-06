# Projet E-Commerce PHP Vanilla

Site web d'e-commerce complet développé en PHP Vanilla avec base de données MySQL.

## 📋 Fonctionnalités

### ✅ Fonctionnalités minimales implémentées
- **Page d'accueil** avec liste de produits et statistiques
- **Page détail produit** avec informations complètes
- **Système de panier** (ajout, suppression, modification, total)
- **Authentification utilisateur** (inscription + connexion)
- **Passage de commande** (validation du panier)
- **BONUS : Espace client** avec historique des commandes

### 🎯 Fonctionnalités avancées
- Design moderne et responsive
- Filtrage par catégorie
- Gestion des stocks en temps réel
- Session PHP sécurisée
- Messages d'erreur et de succès

## 🛠️ Installation

### Prérequis
- PHP 8.0+
- MySQL/MariaDB
- Apache (XAMPP recommandé)

### 1. Base de données

1. Importez le script SQL dans phpMyAdmin :
   ```sql
   -- Importez le fichier : database/mini_mvc.sql
   ```

2. Vérifiez que les tables suivantes sont créées :
   - `user` (utilisateurs)
   - `produit` (produits)
   - `categorie` (catégories)
   - `commande` (commandes)
   - `commande_produit` (détails commandes)
   - `panier` (paniers)

### 2. Configuration

1. Vérifiez la configuration dans `app/config.ini` :
   ```ini
   DB_NAME = "mini_mvc"
   DB_HOST = "127.0.0.1"
   DB_USERNAME = "root"
   DB_PASSWORD = ""
   ```

### 3. Lancement du projet

1. Placez le dossier dans `c:\xampp\htdocs\mini_mvc\`
2. Démarrez Apache dans XAMPP
3. Accédez à : `http://localhost/mini_mvc/public/`

## 🔑 Identifiants de test

### Utilisateurs existants
- **Email** : `toto@toto.toto`
- **Email** : `tata@tata.toto`
- **Email** : `john@example.com`
- **Mot de passe** : n'importe lequel (pas de validation pour les tests)

### Créer un nouveau compte
1. Allez sur `http://localhost/mini_mvc/public/register`
2. Remplissez le formulaire
3. Connectez-vous automatiquement après inscription

## 🌐 Navigation du site

### Pages principales
- **Accueil** : `/` - Tableau de bord avec statistiques
- **Boutique** : `/products.php` - Liste des produits
- **Détail produit** : `/product_detail.php?id=X`
- **Panier** : `/cart.php`
- **Validation commande** : `/checkout.php`
- **Confirmation commande** : `/order_confirmation.php?id=X`

### Authentification
- **Connexion** : `/login`
- **Inscription** : `/register`
- **Déconnexion** : `/logout`

## 📁 Structure du projet

```
mini_mvc/
├── app/
│   ├── Core/           # Classes de base (Database, Model)
│   ├── Models/         # Modèles de données
│   └── Views/          # Vues HTML
├── database/
│   ├── mini_mvc.sql    # Script SQL de la base
│   └── migrations.sql  # Script de migration
├── public/             # Point d'entrée web
│   ├── index.php       # Router principal
│   ├── products.php    # Page boutique
│   ├── cart.php        # Panier
│   └── *.php           # Autres pages
├── vendor/             # Dépendances Composer
└── README.md           # Ce fichier
```

## 🎨 Design et UX

- **Design moderne** avec gradients et animations
- **Responsive** pour mobile et desktop
- **Interface intuitive** avec messages clairs
- **Feedback utilisateur** immédiat

## 🔧 Fonctionnalités techniques

### Gestion du panier
- Ajout de produits avec vérification du stock
- Modification des quantités
- Suppression d'articles
- Calcul automatique du total

### Gestion des commandes
- Validation du panier en commande
- Mise à jour automatique des stocks
- Historique des commandes par utilisateur
- Statuts de commande (en_attente, validee, annulee)

### Sécurité
- Session PHP pour l'authentification
- Validation des entrées utilisateur
- Protection contre les injections SQL
- Contrôle d'accès aux pages privées

## 🚀 Utilisation

1. **Navigation** : Utilisez le menu pour naviguer entre les pages
2. **Shopping** : Ajoutez des produits au panier depuis la boutique
3. **Panier** : Modifiez les quantités ou supprimez des articles
4. **Commande** : Validez votre panier en étant connecté
5. **Historique** : Consultez vos commandes passées

## 📊 Données de test

Le projet inclut des données de test :
- **6 utilisateurs** de test
- **10 produits** variés
- **4 catégories** (Électronique, Vêtements, Alimentation, Maison)
- **5 commandes** exemples

## 🎯 Points forts du projet

- ✅ **100% PHP Vanilla** - Aucun framework
- ✅ **Base de données complète** - 6+ tables relationnelles
- ✅ **Code propre et commenté**
- ✅ **Responsive Design**
- ✅ **Fonctionnalités complètes** e-commerce
- ✅ **BONUS implémenté** - Espace client

## 🐛 Dépannage

### Problèmes courants
1. **Page blanche** : Vérifiez les erreurs PHP dans les logs XAMPP
2. **Base de données** : Assurez-vous que MySQL est démarré
3. **Permissions** : Vérifiez que Apache a accès aux fichiers

### URLs de test
- Si problème avec `/mini_mvc/public/` : essayez directement `/mini_mvc/public/index.php`

---

**Développé pour le TP E-Commerce PHP Vanilla - Tous les requis respectés ✅**
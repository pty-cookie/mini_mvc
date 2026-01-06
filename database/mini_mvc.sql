-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 06 jan. 2026 à 22:08
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mini_mvc`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id`, `nom`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Électronique', 'Produits électroniques et gadgets', '2025-12-17 13:26:06', '2025-12-17 13:26:06'),
(2, 'Vêtements', 'Vêtements et accessoires de mode', '2025-12-17 13:26:06', '2025-12-17 13:26:06'),
(3, 'Alimentation', 'Produits alimentaires et boissons', '2025-12-17 13:26:06', '2025-12-17 13:26:06'),
(4, 'Maison & Jardin', 'Articles pour la maison et le jardin', '2025-12-17 13:26:06', '2025-12-17 13:26:06');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `statut` enum('en_attente','validee','annulee') DEFAULT 'en_attente',
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id`, `user_id`, `statut`, `total`, `created_at`, `updated_at`) VALUES
(1, 1, 'validee', 136.00, '2025-12-17 13:46:46', '2025-12-17 13:46:46'),
(2, 1, 'validee', 1327.99, '2025-12-17 14:14:16', '2025-12-17 14:14:16'),
(3, 1, 'validee', 3903.97, '2025-12-17 14:54:00', '2025-12-17 14:54:00'),
(4, 1, 'validee', 200.00, '2025-12-19 11:16:45', '2025-12-19 11:16:45'),
(5, 1, 'validee', 14299.89, '2025-12-19 11:36:28', '2025-12-19 11:36:28');

-- --------------------------------------------------------

--
-- Structure de la table `commande_produit`
--

CREATE TABLE `commande_produit` (
  `id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT 1,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande_produit`
--

INSERT INTO `commande_produit` (`id`, `commande_id`, `product_id`, `quantite`, `prix_unitaire`, `created_at`) VALUES
(1, 1, 5, 3, 40.00, '2025-12-17 13:46:46'),
(2, 1, 9, 4, 4.00, '2025-12-17 13:46:46'),
(3, 2, 10, 1, 1299.99, '2025-12-17 14:14:16'),
(4, 2, 9, 7, 4.00, '2025-12-17 14:14:16'),
(5, 3, 9, 1, 4.00, '2025-12-17 14:54:00'),
(6, 3, 10, 3, 1299.99, '2025-12-17 14:54:00'),
(7, 4, 5, 5, 40.00, '2025-12-19 11:16:45'),
(8, 5, 10, 11, 1299.99, '2025-12-19 11:36:28');

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

CREATE TABLE `panier` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id` int(11) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image_url` text NOT NULL,
  `categorie_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `nom`, `description`, `prix`, `stock`, `image_url`, `categorie_id`) VALUES
(1, 'stylo', 'stylo 4 couleurs', 4.00, 60, 'https://dxbyzx5id4chj.cloudfront.net/pub/media/catalog/product/7/9/3/5/1/0/P_79351083_1.jpg', NULL),
(2, 'stylo', 'stylo 4 couleurs', 4.00, 60, 'https://dxbyzx5id4chj.cloudfront.net/pub/media/catalog/product/7/9/3/5/1/0/P_79351083_1.jpg', NULL),
(3, 'Ordinateur portable', 'Ordinateur portable haute performance', 1299.99, 15, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQTWutpVKkOXMLZI3D6QrLOcttVB2KoNJu1qA&s', NULL),
(4, 'stylo', 'stylo 4 couleurs', 4.00, 60, 'https://dxbyzx5id4chj.cloudfront.net/pub/media/catalog/product/7/9/3/5/1/0/P_79351083_1.jpg', NULL),
(5, 'Souris', 'Souris pour PC', 40.00, 52, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQx3qARXQdkyZYhkR35oeGLmLoIWjODSqbNbA&s', NULL),
(6, 'test', 'test', 40.00, 0, 'http://test.com', NULL),
(7, 'test', 'test', 0.00, 0, 'http://test.com', NULL),
(8, 'stylo', 'test', 4.00, 44, 'https://dxbyzx5id4chj.cloudfront.net/pub/media/catalog/product/7/9/3/5/1/0/P_79351083_1.jpg', NULL),
(9, 'stylo', 'test', 4.00, 32, 'https://dxbyzx5id4chj.cloudfront.net/pub/media/catalog/product/7/9/3/5/1/0/P_79351083_1.jpg', NULL),
(10, 'Ordinateur portable', 'Ordinateur portable haute performance', 1299.99, 0, 'https://static.fnac-static.com/multimedia/Images/FR/MDM/a1/14/52/22156449/1540-1/tsp20250801182017/PC-Portable-Lenovo-IdeaPad-Slim-3-15IAH8-15-6-Intel-Core-i5-16-Go-RAM-512-Go-D-Gris.jpg', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `nom`, `email`) VALUES
(1, 'toto', 'toto@toto.toto'),
(2, 'tata', 'tata@tata.tata'),
(3, 'John Doe', 'john@example.com'),
(4, 'test', 'test@test.fr'),
(5, 'Toto', 'toto@toto.fr'),
(6, 'test', 'test@test.fr');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_commande_user` (`user_id`);

--
-- Index pour la table `commande_produit`
--
ALTER TABLE `commande_produit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_commande_produit_commande` (`commande_id`),
  ADD KEY `fk_commande_produit_produit` (`product_id`);

--
-- Index pour la table `panier`
--
ALTER TABLE `panier`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  ADD KEY `fk_panier_produit` (`product_id`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_produit_categorie` (`categorie_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `commande_produit`
--
ALTER TABLE `commande_produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `panier`
--
ALTER TABLE `panier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `fk_commande_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `commande_produit`
--
ALTER TABLE `commande_produit`
  ADD CONSTRAINT `fk_commande_produit_commande` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_commande_produit_produit` FOREIGN KEY (`product_id`) REFERENCES `produit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `fk_panier_produit` FOREIGN KEY (`product_id`) REFERENCES `produit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_panier_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `fk_produit_categorie` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

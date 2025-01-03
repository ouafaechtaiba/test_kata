-- Créer la table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    firstname VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Créer la table des produits
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    category VARCHAR(100),
    price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL,
    internalReference VARCHAR(100),
    shellId INT NOT NULL,
    inventoryStatus ENUM('INSTOCK', 'LOWSTOCK', 'OUTOFSTOCK') NOT NULL,
    rating DECIMAL(2, 1),
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Créer la table des paniers
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,  -- Utilise l'ID de l'utilisateur comme identifiant
    productId INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE,  -- Suppression du panier si l'utilisateur est supprimé
    FOREIGN KEY (productId) REFERENCES products(id) ON DELETE CASCADE  -- Suppression du produit du panier si le produit est supprimé
);

-- Créer la table des listes d'envies
CREATE TABLE IF NOT EXISTS wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,  -- Utilise l'ID de l'utilisateur comme identifiant
    productId INT NOT NULL,
    FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE,  -- Suppression de la liste d'envies si l'utilisateur est supprimé
    FOREIGN KEY (productId) REFERENCES products(id) ON DELETE CASCADE  -- Suppression du produit de la liste d'envies si le produit est supprimé
);


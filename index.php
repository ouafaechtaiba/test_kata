<?php
require_once 'productController.php';
require_once 'userController.php';
require_once 'cartController.php';

// En-têtes de la réponse pour indiquer que l'on renvoie du JSON
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Récupérer la méthode HTTP de la requête
$method = $_SERVER['REQUEST_METHOD'];

// Récupérer l'URL de la requête
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

// Route des produits
if ($request[0] === 'products') {
    if ($method === 'GET' && !isset($request[1])) {
        getProducts(); // Récupérer tous les produits
    } elseif ($method === 'GET' && isset($request[1])) {
        getProductById($request[1]); // Récupérer un produit par ID
    } elseif ($method === 'POST') {
        createProduct(); // Créer un nouveau produit
    } elseif ($method === 'PUT' && isset($request[1])) {
        updateProduct($request[1]); // Mettre à jour un produit
    } elseif ($method === 'DELETE' && isset($request[1])) {
        deleteProduct($request[1]); // Supprimer un produit
    }
}

// Route des utilisateurs
elseif ($request[0] === 'account' && $method === 'POST') {
    createAccount(); // Créer un compte utilisateur
} elseif ($request[0] === 'token' && $method === 'POST') {
    login(); // Authentifier l'utilisateur
}

// Route des paniers
elseif ($request[0] === 'cart') {
    if ($method === 'POST') {
        addToCart(); // Ajouter un produit au panier
    } elseif ($method === 'GET') {
        getCart(); // Obtenir les produits dans le panier
    } elseif ($method === 'DELETE') {
        removeFromCart(); // Retirer un produit du panier
    }
} else {
    echo json_encode(['message' => 'Route not found']);
}
?>

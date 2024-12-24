<?php
require_once 'config.php';
require_once 'Cart.php';
require_once 'jwtHelper.php';

// Récupérer tous les éléments du panier d'un utilisateur
function getCart() {
    // Vérifier le token JWT
    $token = getBearerToken();
    $decoded = verifyJWT($token);
    
    if (!$decoded) {
        echo json_encode(['message' => 'Unauthorized']);
        return;
    }

    global $pdo;
    $cartItems = Cart::getCartByUserId($pdo, $decoded->email);
    echo json_encode($cartItems);
}

// Ajouter un produit au panier
function addToCart() {
    // Vérifier le token JWT
    $token = getBearerToken();
    $decoded = verifyJWT($token);
    
    if (!$decoded) {
        echo json_encode(['message' => 'Unauthorized']);
        return;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['productId']) || empty($data['quantity'])) {
        echo json_encode(['message' => 'Invalid data']);
        return;
    }

    $cart = new Cart(null, $decoded->email, $data['productId'], $data['quantity']);
    $cartId = Cart::addToCart($pdo, $cart);
    echo json_encode(['message' => 'Product added to cart', 'id' => $cartId]);
}

// Mettre à jour la quantité d'un produit dans le panier
function updateCart($id) {
    // Vérifier le token JWT
    $token = getBearerToken();
    $decoded = verifyJWT($token);
    
    if (!$decoded) {
        echo json_encode(['message' => 'Unauthorized']);
        return;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['quantity'])) {
        echo json_encode(['message' => 'Invalid data']);
        return;
    }

    Cart::updateCart($pdo, $id, $data['quantity']);
    echo json_encode(['message' => 'Cart updated']);
}

// Supprimer un produit du panier
function removeFromCart($id) {
    // Vérifier le token JWT
    $token = getBearerToken();
    $decoded = verifyJWT($token);
    
    if (!$decoded) {
        echo json_encode(['message' => 'Unauthorized']);
        return;
    }

    Cart::removeFromCart($pdo, $id);
    echo json_encode(['message' => 'Product removed from cart']);
}
?>


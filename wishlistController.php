<?php
require_once 'config.php';
require_once 'wishlist.php';
require_once 'jwtHelper.php';

// Récupérer tous les produits dans la liste de souhaits d'un utilisateur
function getWishlist() {
    // Vérifier le token JWT
    $token = getBearerToken();
    $decoded = verifyJWT($token);
    
    if (!$decoded) {
        echo json_encode(['message' => 'Unauthorized']);
        return;
    }

    global $pdo;
    $wishlistItems = Wishlist::getWishlistByUserId($pdo, $decoded->email);
    echo json_encode($wishlistItems);
}

// Ajouter un produit à la liste de souhaits
function addToWishlist() {
    // Vérifier le token JWT
    $token = getBearerToken();
    $decoded = verifyJWT($token);
    
    if (!$decoded) {
        echo json_encode(['message' => 'Unauthorized']);
        return;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['productId'])) {
        echo json_encode(['message' => 'Invalid data']);
        return;
    }

    $wishlist = new Wishlist(null, $decoded->email, $data['productId']);
    $wishlistId = Wishlist::addToWishlist($pdo, $wishlist);
    echo json_encode(['message' => 'Product added to wishlist', 'id' => $wishlistId]);
}

// Supprimer un produit de la liste de souhaits
function removeFromWishlist($id) {
    // Vérifier le token JWT
    $token = getBearerToken();
    $decoded = verifyJWT($token);
    
    if (!$decoded) {
        echo json_encode(['message' => 'Unauthorized']);
        return;
    }

    Wishlist::removeFromWishlist($pdo, $id);
    echo json_encode(['message' => 'Product removed from wishlist']);
}
?>


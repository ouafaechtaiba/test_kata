<?php
require_once 'config.php';

// Ajouter un produit à la liste d'envies
function addToWishlist() {
    $data = json_decode(file_get_contents('php://input'), true);
    $token = getBearerToken();
    $decoded = verifyJWT($token);

    if (!$decoded) {
        echo json_encode(['message' => 'Unauthorized']);
        return;
    }

    if (empty($data['productId'])) {
        echo json_encode(['message' => 'Invalid data']);
        return;
    }

    $userId = $decoded->email; // Utiliser l'email de l'utilisateur comme identifiant

    $stmt = $pdo->prepare("INSERT INTO wishlist (userId, productId) VALUES (?, ?)");
    $stmt->execute([$userId, $data['productId']]);

    echo json_encode(['message' => 'Product added to wishlist']);
}

// Obtenir les produits dans la liste d'envies
function getWishlist() {
    $token = getBearerToken();
    $decoded = verifyJWT($token);

    if (!$decoded) {
        echo json_encode(['message' => 'Unauthorized']);
        return;
    }

    $userId = $decoded->email;

    $stmt = $pdo->prepare("SELECT * FROM wishlist WHERE userId = ?");
    $stmt->execute([$userId]);
    $wishlistItems = $stmt->fetchAll();

    echo json_encode($wishlistItems);
}

// Retirer un produit de la liste d'envies
function removeFromWishlist() {
    $data = json_decode(file_get_contents('php://input'), true);
    $token = getBearerToken();
    $decoded = verifyJWT($token);

    if (!$decoded) {
        echo json_encode(['message' => 'Unauthorized']);
        return;
    }

    if (empty($data['productId'])) {
        echo json_encode(['message' => 'Invalid data']);
        return;
    }

    $userId = $decoded->email;

    $stmt = $pdo->prepare("DELETE FROM wishlist WHERE userId = ? AND productId = ?");
    $stmt->execute([$userId, $data['productId']]);

    echo json_encode(['message' => 'Product removed from wishlist']);
}
?>

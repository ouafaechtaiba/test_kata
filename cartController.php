<?php
require_once 'config.php';

// Ajouter un produit au panier
function addToCart() {
    $data = json_decode(file_get_contents('php://input'), true);
    $token = getBearerToken();
    $decoded = verifyJWT($token);

    if (!$decoded) {
        echo json_encode(['message' => 'Unauthorized']);
        return;
    }

    if (empty($data['productId']) || empty($data['quantity'])) {
        echo json_encode(['message' => 'Invalid data']);
        return;
    }

    $userId = $decoded->email; // Utiliser l'email pour identifier l'utilisateur

    $stmt = $pdo->prepare("INSERT INTO cart (userId, productId, quantity) VALUES (?, ?, ?)");
    $stmt->execute([$userId, $data['productId'], $data['quantity']]);

    echo json_encode(['message' => 'Product added to cart']);
}

// Obtenir les produits dans le panier
function getCart() {
    $token = getBearerToken();
    $decoded = verifyJWT($token);

    if (!$decoded) {
        echo json_encode(['message' => 'Unauthorized']);
        return;
    }

    $userId = $decoded->email;

    $stmt = $pdo->prepare("SELECT * FROM cart WHERE userId = ?");
    $stmt->execute([$userId]);
    $cartItems = $stmt->fetchAll();

    echo json_encode($cartItems);
}

// Retirer un produit du panier
function removeFromCart() {
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

    $stmt = $pdo->prepare("DELETE FROM cart WHERE userId = ? AND productId = ?");
    $stmt->execute([$userId, $data['productId']]);

    echo json_encode(['message' => 'Product removed from cart']);
}
?>

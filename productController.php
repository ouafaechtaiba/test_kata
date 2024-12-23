<?php
require_once 'config.php';
require_once 'Product.php';
require_once 'jwtHelper.php';

// Récupérer tous les produits
function getProducts() {
    global $pdo;
    $products = Product::getAllProducts($pdo);
    echo json_encode($products);
}

// Récupérer un produit par son ID
function getProductById($id) {
    global $pdo;
    $product = Product::getProductById($pdo, $id);
    if ($product) {
        echo json_encode($product);
    } else {
        echo json_encode(['message' => 'Product not found']);
    }
}

// Créer un nouveau produit (uniquement pour admin)
function createProduct() {
    // Vérifier le token JWT
    $token = getBearerToken();
    $decoded = verifyJWT($token);
    
    if (!$decoded || $decoded->email !== 'admin@admin.com') {
        echo json_encode(['message' => 'Unauthorized']);
        return;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['code']) || empty($data['name']) || empty($data['price'])) {
        echo json_encode(['message' => 'Invalid data']);
        return;
    }

    $product = new Product(
        null, 
        $data['code'], 
        $data['name'], 
        $data['description'], 
        $data['image'], 
        $data['category'], 
        $data['price'], 
        $data['quantity'], 
        $data['internalReference'], 
        $data['shellId'], 
        $data['inventoryStatus'], 
        $data['rating'], 
        time(), 
        time()
    );

    $productId = Product::createProduct($pdo, $product);
    echo json_encode(['message' => 'Product created', 'id' => $productId]);
}

// Mettre à jour un produit (uniquement pour admin)
function updateProduct($id) {
    // Vérifier le token JWT
    $token = getBearerToken();
    $decoded = verifyJWT($token);
    
    if (!$decoded || $decoded->email !== 'admin@admin.com') {
        echo json_encode(['message' => 'Unauthorized']);
        return;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $product = new Product(
        $id,
        $data['code'], 
        $data['name'], 
        $data['description'], 
        $data['image'], 
        $data['category'], 
        $data['price'], 
        $data['quantity'], 
        $data['internalReference'], 
        $data['shellId'], 
        $data['inventoryStatus'], 
        $data['rating'], 
        time(), 
        time()
    );

    Product::updateProduct($pdo, $id, $product);
    echo json_encode(['message' => 'Product updated']);
}

// Supprimer un produit (uniquement pour admin)
function deleteProduct($id) {
    // Vérifier le token JWT
    $token = getBearerToken();
    $decoded = verifyJWT($token);
    
    if (!$decoded || $decoded->email !== 'admin@admin.com') {
        echo json_encode(['message' => 'Unauthorized']);
        return;
    }

    Product::deleteProduct($pdo, $id);
    echo json_encode(['message' => 'Product deleted']);
}

// Fonction pour obtenir le token JWT de l'en-tête Authorization
function getBearerToken() {
    $headers = apache_request_headers();
    if (isset($headers['Authorization'])) {
        $matches = [];
        preg_match('/Bearer (.+)/', $headers['Authorization'], $matches);
        return $matches[1] ?? null;
    }
    return null;
}
?>

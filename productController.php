<?php
require_once 'config.php';
require_once 'Product.php';
require_once 'jwtHelper.php';

// Récupérer tous les produits
function getProducts() {
    global $pdo;
    
    try {
        $products = Product::getAllProducts($pdo);
        echo json_encode($products);
    } catch (Exception $e) {
        echo json_encode(['message' => 'Error fetching products', 'error' => $e->getMessage()]);
    }
}

// Récupérer un produit par son ID
function getProductById($id) {
    global $pdo;
    
    try {
        $product = Product::getProductById($pdo, $id);
        if ($product) {
            echo json_encode($product);
        } else {
            echo json_encode(['message' => 'Product not found']);
        }
    } catch (Exception $e) {
        echo json_encode(['message' => 'Error fetching product', 'error' => $e->getMessage()]);
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

    // Récupérer les données du produit depuis la requête
    $data = json_decode(file_get_contents('php://input'), true);

    // Validation des données du produit
    if (empty($data['code']) || empty($data['name']) || empty($data['price']) || empty($data['quantity'])) {
        echo json_encode(['message' => 'Invalid data, code, name, price, and quantity are required']);
        return;
    }

    try {
        // Création de l'objet produit
        $product = new Product(
            null, 
            $data['code'], 
            $data['name'], 
            $data['description'] ?? '', 
            $data['image'] ?? '', 
            $data['category'] ?? 'Other', 
            $data['price'], 
            $data['quantity'], 
            $data['internalReference'] ?? '', 
            $data['shellId'] ?? null, 
            $data['inventoryStatus'] ?? 'INSTOCK', 
            $data['rating'] ?? 0, 
            time(), 
            time()
        );

        // Sauvegarde du produit dans la base de données
        $productId = Product::createProduct($pdo, $product);
        echo json_encode(['message' => 'Product created successfully', 'id' => $productId]);
    } catch (Exception $e) {
        echo json_encode(['message' => 'Error creating product', 'error' => $e->getMessage()]);
    }
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

    // Récupérer les données du produit depuis la requête
    $data = json_decode(file_get_contents('php://input'), true);

    // Validation des données du produit
    if (empty($data['code']) || empty($data['name']) || empty($data['price']) || empty($data['quantity'])) {
        echo json_encode(['message' => 'Invalid data, code, name, price, and quantity are required']);
        return;
    }

    try {
        // Mise à jour de l'objet produit
        $product = new Product(
            $id, 
            $data['code'], 
            $data['name'], 
            $data['description'] ?? '', 
            $data['image'] ?? '', 
            $data['category'] ?? 'Other', 
            $data['price'], 
            $data['quantity'], 
            $data['internalReference'] ?? '', 
            $data['shellId'] ?? null, 
            $data['inventoryStatus'] ?? 'INSTOCK', 
            $data['rating'] ?? 0, 
            time(), 
            time()
        );

        // Mise à jour du produit dans la base de données
        Product::updateProduct($pdo, $id, $product);
        echo json_encode(['message' => 'Product updated successfully']);
    } catch (Exception $e) {
        echo json_encode(['message' => 'Error updating product', 'error' => $e->getMessage()]);
    }
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

    try {
        // Suppression du produit de la base de données
        Product::deleteProduct($pdo, $id);
        echo json_encode(['message' => 'Product deleted successfully']);
    } catch (Exception $e) {
        echo json_encode(['message' => 'Error deleting product', 'error' => $e->getMessage()]);
    }
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


<?php
class Product {
    public $id;
    public $code;
    public $name;
    public $description;
    public $image;
    public $category;
    public $price;
    public $quantity;
    public $internalReference;
    public $shellId;
    public $inventoryStatus;
    public $rating;
    public $createdAt;
    public $updatedAt;

    public function __construct($id, $code, $name, $description, $image, $category, $price, $quantity, $internalReference, $shellId, $inventoryStatus, $rating, $createdAt, $updatedAt) {
        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
        $this->description = $description;
        $this->image = $image;
        $this->category = $category;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->internalReference = $internalReference;
        $this->shellId = $shellId;
        $this->inventoryStatus = $inventoryStatus;
        $this->rating = $rating;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    // Récupération de tous les produits
    public static function getAllProducts($pdo) {
        $stmt = $pdo->query("SELECT * FROM products");
        return $stmt->fetchAll();
    }

    // Récupérer un produit par son ID
    public static function getProductById($pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Créer un nouveau produit
    public static function createProduct($pdo, $product) {
        $stmt = $pdo->prepare("INSERT INTO products (code, name, description, image, category, price, quantity, internalReference, shellId, inventoryStatus, rating, createdAt, updatedAt) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $product->code, 
            $product->name, 
            $product->description, 
            $product->image, 
            $product->category, 
            $product->price, 
            $product->quantity, 
            $product->internalReference, 
            $product->shellId, 
            $product->inventoryStatus, 
            $product->rating, 
            $product->createdAt, 
            $product->updatedAt
        ]);
        return $pdo->lastInsertId();  // Retourne l'ID du produit inséré
    }

    // Mettre à jour un produit existant
    public static function updateProduct($pdo, $id, $product) {
        $stmt = $pdo->prepare("UPDATE products SET code = ?, name = ?, description = ?, image = ?, category = ?, price = ?, quantity = ?, internalReference = ?, shellId = ?, inventoryStatus = ?, rating = ?, updatedAt = ? WHERE id = ?");
        $stmt->execute([
            $product->code, 
            $product->name, 
            $product->description, 
            $product->image, 
            $product->category, 
            $product->price, 
            $product->quantity, 
            $product->internalReference, 
            $product->shellId, 
            $product->inventoryStatus, 
            $product->rating, 
            $product->updatedAt, 
            $id
        ]);
    }

    // Supprimer un produit par son ID
    public static function deleteProduct($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>

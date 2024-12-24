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

    public static function getAllProducts($pdo) {
        $stmt = $pdo->query("SELECT * FROM products");
        return $stmt->fetchAll();
    }

    public static function getProductById($pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public static function createProduct($pdo, Product $product) {
        $stmt = $pdo->prepare("INSERT INTO products (code, name, description, image, category, price, quantity, internalReference, shellId, inventoryStatus, rating, createdAt, updatedAt) 
                               VALUES (:code, :name, :description, :image, :category, :price, :quantity, :internalReference, :shellId, :inventoryStatus, :rating, :createdAt, :updatedAt)");
        $stmt->execute([
            'code' => $product->code,
            'name' => $product->name,
            'description' => $product->description,
            'image' => $product->image,
            'category' => $product->category,
            'price' => $product->price,
            'quantity' => $product->quantity,
            'internalReference' => $product->internalReference,
            'shellId' => $product->shellId,
            'inventoryStatus' => $product->inventoryStatus,
            'rating' => $product->rating,
            'createdAt' => $product->createdAt,
            'updatedAt' => $product->updatedAt,
        ]);
        return $pdo->lastInsertId();
    }

    public static function updateProduct($pdo, $id, Product $product) {
        $stmt = $pdo->prepare("UPDATE products SET 
                               code = :code, 
                               name = :name, 
                               description = :description, 
                               image = :image, 
                               category = :category, 
                               price = :price, 
                               quantity = :quantity, 
                               internalReference = :internalReference, 
                               shellId = :shellId, 
                               inventoryStatus = :inventoryStatus, 
                               rating = :rating, 
                               updatedAt = :updatedAt 
                               WHERE id = :id");
        $stmt->execute([
            'code' => $product->code,
            'name' => $product->name,
            'description' => $product->description,
            'image' => $product->image,
            'category' => $product->category,
            'price' => $product->price,
            'quantity' => $product->quantity,
            'internalReference' => $product->internalReference,
            'shellId' => $product->shellId,
            'inventoryStatus' => $product->inventoryStatus,
            'rating' => $product->rating,
            'updatedAt' => $product->updatedAt,
            'id' => $id
        ]);
    }

    public static function deleteProduct($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
?>


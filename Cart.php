<?php
class Cart {
    public $id;
    public $userId;
    public $productId;
    public $quantity;

    public function __construct($id, $userId, $productId, $quantity) {
        $this->id = $id;
        $this->userId = $userId;
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    // Récupérer les éléments du panier d'un utilisateur
    public static function getCartByUserId($pdo, $userId) {
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE userId = :userId");
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll();
    }

    // Ajouter un produit au panier
    public static function addToCart($pdo, Cart $cart) {
        $stmt = $pdo->prepare("INSERT INTO cart (userId, productId, quantity) VALUES (:userId, :productId, :quantity)");
        $stmt->execute([
            'userId' => $cart->userId,
            'productId' => $cart->productId,
            'quantity' => $cart->quantity
        ]);
        return $pdo->lastInsertId();
    }

    // Mettre à jour la quantité d'un produit dans le panier
    public static function updateCart($pdo, $id, $quantity) {
        $stmt = $pdo->prepare("UPDATE cart SET quantity = :quantity WHERE id = :id");
        $stmt->execute(['id' => $id, 'quantity' => $quantity]);
    }

    // Supprimer un produit du panier
    public static function removeFromCart($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
?>

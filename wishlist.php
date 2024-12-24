<?php
class Wishlist {
    public $id;
    public $userId;
    public $productId;

    public function __construct($id, $userId, $productId) {
        $this->id = $id;
        $this->userId = $userId;
        $this->productId = $productId;
    }

    // Récupérer les produits dans la liste de souhaits d'un utilisateur
    public static function getWishlistByUserId($pdo, $userId) {
        $stmt = $pdo->prepare("SELECT * FROM wishlist WHERE userId = :userId");
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll();
    }

    // Ajouter un produit à la liste de souhaits
    public static function addToWishlist($pdo, Wishlist $wishlist) {
        $stmt = $pdo->prepare("INSERT INTO wishlist (userId, productId) VALUES (:userId, :productId)");
        $stmt->execute([
            'userId' => $wishlist->userId,
            'productId' => $wishlist->productId
        ]);
        return $pdo->lastInsertId();
    }

    // Supprimer un produit de la liste de souhaits
    public static function removeFromWishlist($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM wishlist WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
?>

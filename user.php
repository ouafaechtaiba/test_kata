<?php
class User {
    public $id;
    public $username;
    public $firstname;
    public $email;
    public $password;

    // Constructeur de la classe User
    public function __construct($id, $username, $firstname, $email, $password) {
        $this->id = $id;
        $this->username = $username;
        $this->firstname = $firstname;
        $this->email = $email;
        $this->password = $password;
    }

    // Créer un utilisateur
    public static function createUser($pdo, $user) {
        $stmt = $pdo->prepare("INSERT INTO users (username, firstname, email, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user->username, $user->firstname, $user->email, $user->password]);
    }

    // Vérifier les informations de l'utilisateur lors de la connexion
    public static function getUserByEmail($pdo, $email) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
}
?>

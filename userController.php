<?php
require_once 'config.php';
require_once 'jwtHelper.php';

// Créer un nouveau compte utilisateur
function createAccount() {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['username']) || empty($data['firstname']) || empty($data['email']) || empty($data['password'])) {
        echo json_encode(['message' => 'Invalid data']);
        return;
    }

    $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, firstname, email, password) VALUES (?, ?, ?, ?)");
    $stmt->execute([$data['username'], $data['firstname'], $data['email'], $passwordHash]);

    echo json_encode(['message' => 'Account created']);
}

// Authentifier un utilisateur et générer un token JWT
function login() {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['email']) || empty($data['password'])) {
        echo json_encode(['message' => 'Invalid credentials']);
        return;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($data['password'], $user['password'])) {
        echo json_encode(['message' => 'Invalid credentials']);
        return;
    }

    $token = generateJWT($user['email']);
    echo json_encode(['token' => $token]);
}
?>

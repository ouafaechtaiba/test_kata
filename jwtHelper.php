<?php
use \Firebase\JWT\JWT;

$secretKey = 'your_secret_key'; // Clé secrète pour signer les tokens

// Fonction pour générer un token JWT
function generateJWT($email) {
    global $secretKey;
    
    $issuedAt = time();
    $expirationTime = $issuedAt + 3600;  // Le token expire dans 1 heure
    $payload = array(
        "email" => $email,
        "iat" => $issuedAt,
        "exp" => $expirationTime
    );
    
    return JWT::encode($payload, $secretKey);
}

// Fonction pour vérifier un token JWT
function verifyJWT($token) {
    global $secretKey;

    try {
        return JWT::decode($token, $secretKey, array('HS256'));
    } catch (Exception $e) {
        return null;
    }
}
?>

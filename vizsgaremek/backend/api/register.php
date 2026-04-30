<?php

header("Content-Type: application/json");

require_once __DIR__ . "/../config/database.php";

// csak POST kérést engedünk
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Csak POST kérés engedélyezett"]);
    exit;
}

// bejövő adatok (JSON-ből)
$data = json_decode(file_get_contents("php://input"), true);

// ellenőrzés
if (!isset($data['name'], $data['email'], $data['password'])) {
    echo json_encode(["error" => "Hiányzó adatok"]);
    exit;
}

$name = $data['name'];
$email = $data['email'];
$password = $data['password'];

// jelszó titkosítás
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {

    // SQL beszúrás
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

    // előkészítés (biztonságos!)
    $stmt = $pdo->prepare($sql);

    // végrehajtás
    $stmt->execute([$name, $email, $hashedPassword]);

    echo json_encode(["message" => "Sikeres regisztráció"]);

} catch (PDOException $e) {

    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
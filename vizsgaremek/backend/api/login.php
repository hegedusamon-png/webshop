<?php

header("Content-Type: application/json");

require_once __DIR__ . "/../config/database.php";

// csak POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Csak POST kérés"]);
    exit;
}

// adat beolvasás
$data = json_decode(file_get_contents("php://input"), true);

// ellenőrzés
if (!isset($data['email'], $data['password'])) {
    echo json_encode(["error" => "Hiányzó adatok"]);
    exit;
}

$email = $data['email'];
$password = $data['password'];

try {

    // felhasználó lekérdezése
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // ha nincs ilyen user
    if (!$user) {
        echo json_encode(["error" => "Hibás email vagy jelszó"]);
        exit;
    }

    // jelszó ellenőrzés
    if (password_verify($password, $user['password'])) {

        echo json_encode([
    "user" => [
        "id" => $user['id'],
        "name" => $user['name'],
        "email" => $user['email'],
        "role" => $user['role'] 
    ]
]);

    } else {
        echo json_encode(["error" => "Hibás email vagy jelszó"]);
    }

} catch (PDOException $e) {

    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
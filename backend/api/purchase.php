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
if (!isset($data['user_id'], $data['product_id'])) {
    echo json_encode(["error" => "Hiányzó adatok"]);
    exit;
}

$user_id = $data['user_id'];
$product_id = $data['product_id'];

try {

    // beszúrás
    $sql = "INSERT INTO orders (user_id, product_id) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $product_id]);

    echo json_encode(["message" => "Sikeres vásárlás"]);

} catch (PDOException $e) {

    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
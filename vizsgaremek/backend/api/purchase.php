<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");

require_once __DIR__ . "/../config/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Csak POST kérés"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['user_id'], $data['product_id'])) {
    echo json_encode(["error" => "Hiányzó adatok"]);
    exit;
}

$user_id = $data['user_id'];
$product_id = $data['product_id'];

try {

    // termék árának lekérése
    $sql = "SELECT price FROM products WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo json_encode(["error" => "Termék nem található"]);
        exit;
    }

    $total_price = $product['price'];

    // rendelés létrehozása
    $sql = "INSERT INTO orders (user_id, total_price) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $total_price]);

    $order_id = $pdo->lastInsertId();

    // rendelési tétel létrehozása
    $sql = "INSERT INTO order_items (order_id, product_id) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$order_id, $product_id]);

    echo json_encode([
        "message" => "Sikeres vásárlás"
    ]);

} catch (PDOException $e) {

    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
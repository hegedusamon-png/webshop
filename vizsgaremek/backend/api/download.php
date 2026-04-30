<?php

header("Content-Type: application/json");

require_once __DIR__ . "/../config/database.php";

// csak POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Csak POST kérés"]);
    exit;
}

// adatok beolvasása
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['user_id'], $data['product_id'])) {
    echo json_encode(["error" => "Hiányzó adatok"]);
    exit;
}

$user_id = $data['user_id'];
$product_id = $data['product_id'];

try {

    // ellenőrizzük, hogy megvette-e
    $sql = "
        SELECT * FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        WHERE o.user_id = ? AND oi.product_id = ?
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $product_id]);

    $order = $stmt->fetch();

    if (!$order) {
        echo json_encode(["error" => "Nem vásároltad meg ezt a terméket"]);
        exit;
    }

    // lekérjük a termék fájlt
    $sql = "SELECT file_path FROM products WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$product_id]);

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo json_encode(["error" => "Termék nem található"]);
        exit;
    }

    echo json_encode([
        "message" => "Letöltés engedélyezve",
        "file" => $product['file_path']
    ]);

} catch (PDOException $e) {

    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
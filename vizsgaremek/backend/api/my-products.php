<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once __DIR__ . "/../config/database.php";

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $data['user_id'];

$sql = "
SELECT p.id, p.title, p.file_path
FROM products p
JOIN order_items oi ON p.id = oi.product_id
JOIN orders o ON o.id = oi.order_id
WHERE o.user_id = ?
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($products);
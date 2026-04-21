<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once __DIR__ . "/../config/database.php";

$data = json_decode(file_get_contents("php://input"), true);

$title = $data['title'];
$price = $data['price'];
$file = $data['file_path'];

$sql = "INSERT INTO products (title, price, file_path) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$title, $price, $file]);

echo json_encode(["message" => "Termék hozzáadva"]);
<?php

// JSON válasz
header("Content-Type: application/json");

// adatbázis kapcsolat betöltése
require_once __DIR__ . "/../config/database.php";

// SQL lekérdezés
$sql = "SELECT * FROM products";

try {

    // lekérdezés futtatása
    $stmt = $pdo->query($sql);

    // adatok kiolvasása
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // JSON válasz
    echo json_encode($products, JSON_PRETTY_PRINT);

} catch(PDOException $e) {

    // hiba esetén
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
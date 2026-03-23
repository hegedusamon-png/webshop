<?php

// Adatbázis adatok
$host = "localhost";
$dbname = "webshop";
$username = "root";
$password = "";

try {

    // Kapcsolódás az adatbázishoz
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password
    );

    // Hibakezelés bekapcsolása
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {

    // Hiba kiírása
    echo "Database connection failed: " . $e->getMessage();

}
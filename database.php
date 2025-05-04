<?php
declare(strict_types=1);

$host = $_ENV['DB_HOST'];     
$dbname = $_ENV['DB_NAME'];  
$username = $_ENV['DB_USER'];       
$password = $_ENV['DB_PASSWORD'];          

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    echo "Подключение успешно установлено к БД\n";
} catch(PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}




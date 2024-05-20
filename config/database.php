<?php
$host = 'localhost'; // Хост
$dbname = 'feedback_db'; // Имя базы данных
$username = 'root'; // Имя пользователя
$password = ''; // Пароль

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

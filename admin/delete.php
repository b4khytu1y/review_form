<?php
session_start();
include_once "../config/database.php";

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("location: login.php");
    exit;
}

if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $query = "DELETE FROM feedbacks WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $param_id);
    $param_id = trim($_GET["id"]);

    if ($stmt->execute()) {
        header("location: index.php");
        exit();
    } else {
        echo "Ой! Что-то пошло не так. Пожалуйста попробуйте еще раз.";
    }
} else {
    die("Ошибка при удалении: неправильный запрос.");
}
?>

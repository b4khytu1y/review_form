<?php
session_start();
include_once "../config/database.php";

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['status'])) {
    $feedback_id = $_POST['id'];
    $new_status = $_POST['status'];

    $query = "UPDATE feedbacks SET status = :status WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $feedback_id);
    $stmt->bindParam(':status', $new_status);

    if ($stmt->execute()) {
        echo "<script>alert('Статус отзыва обновлен.'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Ошибка при обновлении статуса отзыва.'); window.location.href='index.php';</script>";
    }
} else {
    header("location: index.php");
    exit;
}

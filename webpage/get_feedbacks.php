<?php
session_start();
include_once "../config/database.php";

$query = "SELECT name, email, message, image, created_at FROM feedbacks WHERE status='accepted' ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($feedbacks);
?>

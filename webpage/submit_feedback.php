<?php
session_start();
include_once "../config/database.php";

$response = array('success' => false, 'message' => '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    $image = '';

    if ($_FILES['image']['size'] > 0) {
        if ($_FILES['image']['size'] > 1048576) {
            $response['message'] = 'Размер изображения не должен превышать 1MB';
            echo json_encode($response);
            exit;
        } else {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($_FILES['image']['type'], $allowed_types)) {
                $image = 'uploads/' . basename($_FILES['image']['name']);
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $image)) {
                    $response['message'] = 'Ошибка при загрузке изображения';
                    echo json_encode($response);
                    exit;
                }
            } else {
                $response['message'] = 'Недопустимый формат изображения';
                echo json_encode($response);
                exit;
            }
        }
    }

    $query = "INSERT INTO feedbacks (name, email, message, image) VALUES (:name, :email, :message, :image)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':message', $message);
    $stmt->bindParam(':image', $image);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Отзыв отправлен успешно! Ожидает модерации.';
    } else {
        $response['message'] = 'Ошибка при отправке отзыва.';
    }
}

echo json_encode($response);
?>

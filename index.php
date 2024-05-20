<?php
session_start();
include_once "config/database.php";

// Обработка формы обратной связи
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    $image = '';

    // Валидация и загрузка изображения
    if ($_FILES['image']['size'] > 0) {
        if ($_FILES['image']['size'] > 1048576) {
            echo "<script>alert('Размер изображения не должен превышать 1MB');</script>";
        } else {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($_FILES['image']['type'], $allowed_types)) {
                $image = 'uploads/' . basename($_FILES['image']['name']);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $image)) {
                    echo "<script>alert('Изображение загружено успешно.');</script>";
                } else {
                    echo "<script>alert('Ошибка при загрузке изображения.');</script>";
                }
            } else {
                echo "<script>alert('Недопустимый формат изображения');</script>";
            }
        }
    }

    // Сохранение отзыва в базе данных
    $query = "INSERT INTO feedbacks (name, email, message, image) VALUES (:name, :email, :message, :image)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':message', $message);
    $stmt->bindParam(':image', $image);
    if ($stmt->execute()) {
        echo "<script>alert('Отзыв отправлен успешно! Ожидает модерации.');</script>";
    } else {
        echo "<script>alert('Ошибка при отправке отзыва.');</script>";
    }
}

// Получение отзывов
$query = "SELECT * FROM feedbacks WHERE status='accepted' ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отзывы</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        
        
        <h1>Оставить отзыв</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <label>Имя:</label><br>
            <input type="text" name="name" required><br>
            <label>Email:</label><br>
            <input type="email" name="email" required><br>
            <label>Сообщение:</label><br>
            <textarea name="message" required></textarea><br>
            <label>Изображение (не более 1MB):</label><br>
            <input type="file" name="image"><br><br>
            <button type="submit">Отправить</button>
        </form>
    </div>
</body>
</html>

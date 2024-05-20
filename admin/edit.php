<?php
session_start();
include_once "../config/database.php";

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("location: login.php");
    exit;
}

$feedback_id = isset($_GET['id']) ? $_GET['id'] : die('Ошибка: Не указан ID.');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    $status = trim($_POST['status']);
    $image = $_FILES['image']['name'];

    if ($image) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "<script>alert('Файл не является изображением.');</script>";
            $uploadOk = 0;
        }

        if ($_FILES["image"]["size"] > 1048576) {
            echo "<script>alert('Файл слишком большой.');</script>";
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "<script>alert('Разрешены только JPG, JPEG, PNG и GIF файлы.');</script>";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "<script>alert('Ошибка при загрузке файла.');</script>";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                echo "<script>alert('Ошибка при загрузке файла.');</script>";
            } else {
                echo "<script>alert('Файл ". htmlspecialchars( basename( $_FILES["image"]["name"])). " был загружен.');</script>";

            }
        }
    }

    $query = "UPDATE feedbacks SET name = :name, email = :email, message = :message, status = :status, modified_at = NOW()";
    if ($image) {
        $query .= ", image = :image";
    }
    $query .= " WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':message', $message);
    $stmt->bindParam(':status', $status);
    if ($image) {
        $stmt->bindParam(':image', $target_file);
    }
    $stmt->bindParam(':id', $feedback_id);

    if ($stmt->execute()) {
        echo "<script>alert('Отзыв обновлен успешно!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Ошибка при обновлении отзыва.');</script>";
    }
}


$query = "SELECT * FROM feedbacks WHERE id = :id LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $feedback_id);
$stmt->execute();
$feedback = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$feedback) {
    die('Ошибка: Отзыв не найден.');
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование отзыва</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Редактирование отзыва</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$feedback_id}"); ?>" method="post" enctype="multipart/form-data">
        <label>Имя:</label><br>
        <input type="text" name="name" value="<?php echo htmlspecialchars($feedback['name']); ?>" required><br>
        <label>Email:</label><br>
        <input type="email" name="email" value="<?php echo htmlspecialchars($feedback['email']); ?>" required><br>
        <label>Сообщение:</label><br>
        <textarea name="message" required><?php echo htmlspecialchars($feedback['message']); ?></textarea><br>
        <label>Статус:</label><br>
        <select name="status">
            <option value="accepted" <?php echo $feedback['status'] == 'accepted' ? 'selected' : ''; ?>>Принят</option>
            <option value="rejected" <?php echo $feedback['status'] == 'rejected' ? 'selected' : ''; ?>>Отклонен</option>
        </select><br>
        <label>Фото:</label><br>
        <input type="file" name="image"><br><br>
        <button type="submit">Обновить</button>
    </form>
</body>
</html>

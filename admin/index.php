<?php
session_start();
include_once "../config/database.php";

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("location: login.php");
    exit;
}

try {
    $stmt = $conn->prepare("SELECT id, name, email, message, image, status, modified_at FROM feedbacks ORDER BY created_at DESC");
    $stmt->execute();
    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление отзывами</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Управление отзывами</h1>
    <table border="1" cellpadding="5" cellspacing="0" style="width: 100%;">
        <thead>
            <tr>
                <th>Имя</th>
                <th>Email</th>
                <th>Сообщение</th>
                <th>Изображение</th>
                <th>Статус</th>
                <th>Изменен</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($feedbacks as $feedback): ?>
                <tr>
                    <td><?php echo htmlspecialchars($feedback['name']); ?></td>
                    <td><?php echo htmlspecialchars($feedback['email']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($feedback['message'])); ?></td>
                    <td><?php echo $feedback['image'] ? '<img src="../'.htmlspecialchars($feedback['image']).'" alt="Image" style="max-width:100px;">' : ''; ?></td>
                    <td><?php echo ($feedback['status'] === 'accepted' ? 'Принят' : 'Отклонен'); ?></td>
                    <td><?php echo $feedback['modified_at'] ? 'изменен администратором' : ''; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $feedback['id']; ?>">Редактировать</a>
                        <a href="delete.php?id=<?php echo $feedback['id']; ?>" onclick="return confirm('Вы уверены?');">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="logout.php">Выход</a>

</body>
</html>

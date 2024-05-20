<?php
session_start();
include_once "config/database.php";

// Получение всех принятых отзывов
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
        <h1>Отзывы</h1>
        <div id="feedbacks">
            <?php foreach ($feedbacks as $feedback): ?>
                <div class="feedback-card">
                    <div class="feedback-header">
                        <strong><?php echo htmlspecialchars($feedback['name']); ?></strong> (<?php echo htmlspecialchars($feedback['email']); ?>)
                    </div>
                    <div class="feedback-message">
                        <?php echo nl2br(htmlspecialchars($feedback['message'])); ?>
                    </div>
                    <?php if ($feedback['image']): ?>
                        <div class="feedback-image">
                            <img src="<?php echo htmlspecialchars($feedback['image']); ?>" alt="Изображение">
                        </div>
                    <?php endif; ?>
                    <div class="feedback-footer">
                        <small>Оставлено: <?php echo htmlspecialchars($feedback['created_at']); ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>

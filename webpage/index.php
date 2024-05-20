<?php
session_start();
include_once "../config/database.php";
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Онлайн Курсы</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Онлайн Курсы</h1>
            <nav>
                <ul>
                    <li><a href="#" data-page="home">Главная</a></li>
                    <li><a href="#" data-page="reviews">Отзывы</a></li>
                    <li><a href="#" data-page="contact">Контакты</a></li>
                </ul>
            </nav>
        </header>
        <main id="content">
        </main>
        <footer>
            <p>&copy; 2024</p>
        </footer>
    </div>

    <script>
        $(document).ready(function() {
            loadPage('home');

            $('nav a').on('click', function(e) {
                e.preventDefault();
                var page = $(this).data('page');
                loadPage(page);
            });

            function loadPage(page) {
                $.ajax({
                    url: page + '.php',
                    type: 'GET',
                    success: function(data) {
                        $('#content').html(data);
                    },
                    error: function() {
                        $('#content').html('<p>Ошибка загрузки страницы.</p>');
                    }
                });
            }
        });
    </script>
</body>
</html>

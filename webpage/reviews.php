<div class="reviews">
    <h2>Отзывы</h2>
    <div id="feedbacks"></div>
    <h3>Оставить отзыв</h3>
    <form id="feedbackForm" enctype="multipart/form-data">
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

    <script>
        $(document).ready(function() {
            // Загрузка отзывов при загрузке страницы
            loadFeedbacks();

            // Обработчик отправки формы
            $('#feedbackForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: 'submit_feedback.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert(response.message);
                        if (response.success) {
                            $('#feedbackForm')[0].reset();
                            loadFeedbacks();
                        }
                    },
                    error: function() {
                        alert('Ошибка при отправке отзыва.');
                    }
                });
            });

            // Функция для загрузки отзывов
            function loadFeedbacks() {
                $.ajax({
                    url: 'get_feedbacks.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var feedbacksHtml = '';
                        $.each(data, function(index, feedback) {
                            feedbacksHtml += '<div class="feedback-card">';
                            feedbacksHtml += '<div class="feedback-header"><strong>' + feedback.name + '</strong> (' + feedback.email + ')</div>';
                            feedbacksHtml += '<div class="feedback-message">' + feedback.message + '</div>';
                            if (feedback.image) {
                                feedbacksHtml += '<div class="feedback-image"><img src="' + feedback.image + '" alt="Изображение"></div>';
                            }
                            feedbacksHtml += '<div class="feedback-footer"><small>Оставлено: ' + feedback.created_at + '</small></div>';
                            feedbacksHtml += '</div>';
                        });
                        $('#feedbacks').html(feedbacksHtml);
                    },
                    error: function() {
                        alert('Ошибка при загрузке отзывов.');
                    }
                });
            }
        });
    </script>
</div>

<?php
session_start();
include_once "../config/database.php";

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("location: index.php");
    exit;
}

$username = $password = "";
$username_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["username"]))) {
        $username_err = "Пожалуйста, введите имя пользователя.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Пожалуйста, введите пароль.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($password_err)) {
        $query = "SELECT id, username, password FROM admins WHERE username = :username";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bindParam(":username", $username);
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $id = $row["id"];
                        $hashed_password = $row["password"];
                        if (md5($password) === $hashed_password) {
                            session_start();
                            $_SESSION["admin_logged_in"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            header("location: index.php");
                        } else {
                            $login_err = "Неправильное имя пользователя или пароль.";
                        }
                    }
                } else {
                    $login_err = "Неправильное имя пользователя или пароль.";
                }
            } else {
                echo "Что-то пошло не так. Пожалуйста, попробуйте еще раз.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход для администратора</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <div class="login-wrapper">
        <h2>Вход для администратора</h2>
        <p>Пожалуйста, введите свои учетные данные для входа.</p>
        <?php 
        if (!empty($login_err)) {
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Имя пользователя</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err;?></span>
            </div>
            <div class="form-group">
                <label>Пароль</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err;?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Войти">
            </div>
        </form>
    </div>    
</body>
</html>

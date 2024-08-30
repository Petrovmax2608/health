<?php
// Подключение к базе данных
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'hdb');
$mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Проверяем соединение на ошибки
if ($mysql->connect_error) {
    die("Ошибка подключения: " . $mysql->connect_error);
}

// Проверка, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверяем, что поля логина и пароля заполнены
    if (isset($_POST['username']) && isset($_POST['password'])) {
     // Получаем введенные данные из формы
$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role']; // новое поле для роли

// Регистрируем нового пользователя
$insert_query = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";

        
        // Проверяем длину логина и пароля
        if (strlen($username) < 5) {
            echo "<script>alert('Логин должен быть не менее 5 символов.'); window.history.back();</script>";
            exit();
        }

        if (strlen($password) < 8) {
            echo "<script>alert('Пароль должен быть не менее 8 символов.'); window.history.back();</script>";
            exit();
        }

        // Проверяем, существует ли пользователь с таким же логином
        $check_query = "SELECT * FROM users WHERE username='$username'";
        $check_result = $mysql->query($check_query);
        if ($check_result->num_rows > 0) {
            echo "<script>alert('Пользователь с таким логином уже существует.'); window.history.back();</script>";
        } else {
            // Регистрируем нового пользователя
            $insert_query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
            if ($mysql->query($insert_query) === TRUE) {
                echo "<script>alert('Регистрация успешна.'); setTimeout(function() { window.location.href = 'login.html'; }, 2000);</script>";
            } else {
                echo "<script>alert('Ошибка регистрации: " . $mysql->error . "'); window.history.back();</script>";
            }
        }
    } else {
        // Выводим сообщение об ошибке, если поля не были заполнены
        echo "<script>alert('Поля логина и пароля должны быть заполнены.'); window.history.back();</script>";
    }
}
?>

<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        // Подключение к базе данных
        define('DB_HOST', 'localhost');
        define('DB_USER', 'root');
        define('DB_PASSWORD', '');
        define('DB_NAME', 'hdb');
        $mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
        if ($mysql->connect_error) {
            die("Ошибка подключения: " . $mysql->connect_error);
        }

        // Запрос к базе данных для проверки логина и пароля
        $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = $mysql->query($query);

        if ($result->num_rows > 0) {
            // Авторизация прошла успешно
            $_SESSION['username'] = $username;
            header("Location: main.php"); // Перенаправление на главную страницу
            exit();
        } else {
            // Неверный логин или пароль
            echo "Неверный логин или пароль.";
        }
    } else {
        // Поля логина и пароля не были заполнены
        echo "Поля логина и пароля должны быть заполнены.";
    }
}

// Вывод информации о пользователе, если он авторизован
if (isset($_SESSION['username'])) {
    echo "Привет, " . $_SESSION['username'] . "!"; // Вывод имени пользователя
} else {
    echo "Привет, гость!";
}
?>

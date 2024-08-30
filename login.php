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
$user_data = $result->fetch_assoc(); // Получаем данные пользователя из результата запроса
$_SESSION['username'] = $username;
$_SESSION['user_id'] = $user_data['user_id']; // Сохраняем user_id в сессию
$_SESSION['role'] = $user_data['role']; // Сохраняем роль в сессию

            echo "<script>alert('Авторизация успешна.'); window.location.href = 'main.php';</script>";
            exit();
        } else {
            // Неверный логин или пароль
            echo "<script>alert('Неверный логин или пароль.'); window.history.back();</script>";
        }
    } else {
        // Поля логина и пароля не были заполнены
        echo "<script>alert('Поля логина и пароля должны быть заполнены.'); window.history.back();</script>";
    }
}

// Вывод информации о пользователе, если он авторизован
if (isset($_SESSION['username'])) {
    echo "Привет, " . $_SESSION['username'] . "!"; // Вывод имени пользователя
} else {
    echo "Привет, гость!";
}
?>

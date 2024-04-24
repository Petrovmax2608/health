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

// Проверяем, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверяем, что поля логина и пароля заполнены
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Получаем введенные данные из формы
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        // Проверяем, существует ли пользователь с таким же логином
        $check_query = "SELECT * FROM users WHERE username='$username'";
        $check_result = $mysql->query($check_query);
        if ($check_result->num_rows > 0) {
            echo "Пользователь с таким логином уже существует.";
        } else {
            // Регистрируем нового пользователя
            $insert_query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
            if ($mysql->query($insert_query) === TRUE) {
                echo "Регистрация успешна.";
                // Дополнительные действия, если нужно
            } else {
                echo "Ошибка регистрации: " . $mysql->error;
            }
        }
    } else {
        // Выводим сообщение об ошибке, если поля не были заполнены
        echo "Поля логина и пароля должны быть заполнены.";
    }
}
?>

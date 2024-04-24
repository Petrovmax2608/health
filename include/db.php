<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'hdb');
$mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Проверяем соединение на ошибки
if ($mysql->connect_error) {
    die("Ошибка подключения: " . $mysql->connect_error);
}

// Устанавливаем кодировку UTF-8
if (!$mysql->set_charset("utf8")) {
    printf("Ошибка при установке кодировки UTF-8: %s\n", $mysql->error);
    exit();
}
?>

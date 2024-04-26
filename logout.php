<?php
// Начать сеанс
session_start();

// Удалить все переменные сессии
$_SESSION = array();

// Если у вас используется cookies сессии, удалите их
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Уничтожить сессию
session_destroy();

// Перенаправить на страницу main.php
header("Location: login.html");
exit;
?>

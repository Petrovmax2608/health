<?php
session_start();
if (isset($_SESSION['username'])) {
    echo '<span>Вы вошли как: ' . $_SESSION['username']. '</span>';
    echo '<a href="logout.php">Выход</a>';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Вредные привычки</title>
  <link rel="stylesheet" href="styles/stylesquit.css">
  <link rel="stylesheet" href="styles/styleheader.css">
  <link rel="stylesheet" href="styles/timer.css">
  <link rel="icon" href="include/logo.png" type="image/x-icon">
</head>
<body class="page">
<header class="header-container">
  <div class="logo">
    <img src="include/logo.png" alt="Логотип">
  </div>
  <nav class="nav">
    <ul> <!-- Закрываем ul тег правильно -->
      <li><a href="login.html">Вход</a></li>
      <li><a href="main.php">Калькулятор калорий</a></li>
      <li><a href="quit.php">Привычки</a></li>
      <li><a href="#">Услуги</a></li>
    </ul>
  </nav>
  
  <div class="user-info">
    <?php
    // Проверяем, установлен ли идентификатор пользователя в сессии
    if (isset($_SESSION['user_id'])) {
        // Если идентификатор пользователя установлен, передаем его в JavaScript
        echo '<span>Вы вошли как: ' . $_SESSION['username']. '</span>';
        echo '<a href="logout.php">Выход</a>';
        // Передаем значение сессии напрямую в JavaScript
        echo '<script>console.log("userId:", ' . json_encode($_SESSION['user_id']) . ');</script>';
    } else {
        echo '<span>Вы вошли как: ' . $_SESSION['username']. '</span>';
        echo '<a href="logout.php">Выход</a>';
    }
    ?>
  </div>
</header>
<div class="container">
    <h1>Таймер</h1>
    <div id="timer">00:00:00:00</div>
    <!-- Используем userId вместо передачи PHP-кода в атрибуте onclick -->
    <?php
    // Определяем переменную userId и устанавливаем ее в null, если сессионная переменная не установлена
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null';
    ?>

<button id="start-btn" data-user-id="<?php echo $userId; ?>">Начать</button>
<button id="giveup-btn" data-user-id="<?php echo $userId; ?>">Сдаться</button>
</div>
<script src="include/timer.js" defer></script>
</body>
</html>

<?php
} else {
    echo '<span>Вы вошли как: Гость  </span>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Вредные привычки</title>
  <link rel="stylesheet" href="styles/stylesquit.css">
  <link rel="stylesheet" href="styles/styleheader.css">
  <link rel="stylesheet" href="styles/timer.css">
  <link rel="icon" href="include/logo.png" type="image/x-icon">
</head>
<body class="page">
<header class="header-container">
  <div class="logo">
    <img src="include/logo.png" alt="Логотип">
  </div>
  <nav class="nav">
    <ul> <!-- Закрываем ul тег правильно -->
      <li><a href="login.html">Вход</a></li>
      <li><a href="main.php">Калькулятор калорий</a></li>
      <li><a href="quit.php">Привычки</a></li>
      <li><a href="#">Услуги</a></li>
    </ul>
  </nav>
  
  <div class="user-info">
    <?php
    echo '<span>Вы вошли как: Гость  </span>';
    ?>
  </div>
<?php
echo '<div class="user-info">';
if (isset($_SESSION['user_id'])) {
    // Если идентификатор пользователя установлен, передаем его в JavaScript
    echo '<span>Вы вошли как: ' . $_SESSION['username']. '</span>';
    echo '<a href="logout.php">Выход</a>';
    // Передаем значение сессии напрямую в JavaScript
    echo '<script>console.log("userId:", ' . json_encode($_SESSION['user_id']) . ');</script>';
} else {
    echo '<span>Вы вошли как: ' . $_SESSION['username']. '</span>';
    echo '<a href="logout.php">Выход</a>';
}
echo '</div>';
?>
echo '<script>console.log("userId in PHP:", ' . json_encode($_SESSION['user_id']) . ');</script>';

</header>
<div class="container">
    <h1>Таймер</h1>
    <div>Таймер доступен только для зарегистрированных пользователей</div>
</div>
</body>
</html>
<?php
}
?>

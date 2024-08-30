<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Таблица лидеров</title>
  <link rel="stylesheet" href="styles/stylesquit.css">
  <link rel="stylesheet" href="styles/styleheader.css">
  <link rel="stylesheet" href="styles/timer.css">
  <link rel="stylesheet" href="styles/stylesleaders.css">
  <link rel="icon" href="include/logo.png" type="image/x-icon">
</head>
<body class="page">
<header class="header-container">
  <div class="logo">
    <img src="include/logo.png" alt="Логотип">
  </div>
  <nav class="nav">
    <ul>
      <li><a href="login.html">Вход</a></li>
      <li><a href="main.php">Калькулятор калорий</a></li>
      <li><a href="quit.php">Привычки</a></li>
      <li><a href="leaders.php">Лидеры</a></li>
	  <li><a href="nutrition.php">Питание</a></li>
    </ul>
  </nav>
  <div class="user-info">
    <?php
    if (isset($_SESSION['username'])) {
        echo '<span>Вы вошли как: ' . $_SESSION['username']. '</span>';
        echo '<a href="logout.php">Выход</a>';
    } else {
        echo '<span>Вы вошли как: Гость  </span>';
    }
    ?>
  </div>
</header>
<div class="container">
    <div class="results-container">
        <h1>Таблица лидеров</h1>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "hdb";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Количество результатов на странице (по умолчанию 10)
        $results_per_page = 10;
        if (isset($_GET['results_per_page'])) {
            $results_per_page = $_GET['results_per_page'];
        }

        // Определение текущей страницы
        $page = 1;
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        }

        $start = ($page - 1) * $results_per_page;

        // Запрос данных с учетом пагинации
        $query = "SELECT u.username, ua.start_date, ua.giveup_date,
                  IFNULL(ua.days_without_habit, DATEDIFF(CURDATE(), ua.start_date)) AS elapsed_days
                  FROM user_attempts AS ua
                  LEFT JOIN users AS u ON ua.user_id = u.user_id 
                  ORDER BY elapsed_days DESC
                  LIMIT $start, $results_per_page";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            // Определение общего ранжирования для добавления номера строки
            $rank_start = $start + 1;

            echo '<table class="leader-table">';
            echo '<tr><th>Место</th><th>Имя пользователя</th><th>Дата начала</th><th>Дата окончания</th><th>Прошло дней</th></tr>';

            while($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td class="rank">' . $rank_start++ . '</td>';
                echo '<td class="username">' . $row['username'] . '</td>';
                echo '<td class="start-date">' . $row['start_date'] . '</td>';
                echo '<td class="giveup-date">' . ($row['giveup_date'] ? $row['giveup_date'] : 'Не сдался') . '</td>';
                echo '<td class="elapsed-days">' . $row['elapsed_days'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';

            // Кнопки для выбора количества результатов на странице
            echo '<div class="pagination">';
            echo '<button class="results-button" onclick="window.location.href=\'leaders.php?results_per_page=10&page=1\'">10</button>';
            echo '<button class="results-button" onclick="window.location.href=\'leaders.php?results_per_page=25&page=1\'">25</button>';
            echo '<button class="results-button" onclick="window.location.href=\'leaders.php?results_per_page=50&page=1\'">50</button>';
            echo '</div>';

            // Получение общего количества записей
            $query = "SELECT COUNT(*) AS total FROM user_attempts";
            $result = $conn->query($query);
            $row = $result->fetch_assoc();
            $total_pages = ceil($row['total'] / $results_per_page);

            // Пагинация
            echo '<div class="pagination">';
            if ($page > 1) {
                echo '<button class="nav-button" onclick="window.location.href=\'leaders.php?page=' . ($page - 1) . '&results_per_page=' . $results_per_page . '\'">Предыдущая</button>';
            } else {
                echo '<button class="nav-button disabled" disabled>Предыдущая</button>';
            }
            for ($i = 1; $i <= $total_pages; $i++) {
                echo '<button class="page-button' . ($i == $page ? ' active' : '') . '" onclick="window.location.href=\'leaders.php?page=' . $i . '&results_per_page=' . $results_per_page . '\'">' . $i . '</button>';
            }
            if ($page < $total_pages) {
                echo '<button class="nav-button" onclick="window.location.href=\'leaders.php?page=' . ($page + 1) . '&results_per_page=' . $results_per_page . '\'">Следующая</button>';
            } else {
                echo '<button class="nav-button disabled" disabled>Следующая</button>';
            }
            echo '</div>';
        } else {
            echo 'Нет данных для отображения';
        }

        $conn->close();
        ?>
    </div>
</div>
</body>
</html>

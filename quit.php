<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Вредные привычки</title>
  <link rel="stylesheet" href="styles/stylesquit.css">
  <link rel="stylesheet" href="styles/styleheader.css">
  <link rel="stylesheet" href="styles/tablequitstyle.css">
  <link rel="stylesheet" href="styles/timer.css">
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
        echo '<span>Вы вошли как: ' . $_SESSION['username'] . '</span>';
        echo '<a href="logout.php">Выход</a>';
    } else {
        echo '<span>Вы вошли как: Гость</span>';
    }
    ?>
  </div>
</header>
<div class="container">
    <h1>Таймер</h1>
    <?php
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; 

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "hdb";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $query = "SELECT start_date, giveup_date, days_without_habit FROM user_attempts WHERE user_id = $user_id";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            echo '<table>';
            echo '<tr><th>Дата начала</th><th>Дата окончания</th><th>Дней без привычки</th></tr>';
            $active_attempt_exists = false;
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['start_date'] . '</td>';
                echo '<td>' . ($row['giveup_date'] ? $row['giveup_date'] : 'Текущая попытка') . '</td>';
                echo '<td>' . ($row['giveup_date'] ? $row['days_without_habit'] : 'В процессе') . '</td>';
                echo '</tr>';
                if (!$row['giveup_date']) {
                    $active_attempt_exists = true;
                }
            }
            echo '</table>';
            if ($active_attempt_exists) {
                echo '<button id="giveup-btn" data-user-id="' . $user_id . '">Сдаться</button>';
            } else {
                echo '<button id="start-btn" data-user-id="' . $user_id . '">Начать</button>';
            }
        } else {
            echo '<div>Начните бросать вредную привычку прямо сейчас!</div>';
            echo '<button id="start-btn" data-user-id="' . $user_id . '">Начать</button>';
        }

        $conn->close();
    } else {
        echo '<div>Таймер доступен только для зарегистрированных пользователей</div>';
    }
    ?>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const container = document.querySelector(".container");

    container.addEventListener("click", function(event) {
        const userId = event.target.dataset.userId;
        const currentDate = new Date().toISOString().slice(0, 10);

        if (event.target && event.target.id === "start-btn") {
            fetch('include/save_start_date.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    start_date: currentDate
                }),
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Start date saved successfully:', data);
                location.reload();
            })
            .catch(error => {
                console.error('Error saving start date:', error);
            });
        } else if (event.target && event.target.id === "giveup-btn") {
            fetch('include/save_giveup_date.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    giveup_date: currentDate
                }),
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Giveup date saved successfully:', data);
                location.reload();
            })
            .catch(error => {
                console.error('Error saving giveup date:', error);
            });
        }
    });
});
</script>
</body>
</html>

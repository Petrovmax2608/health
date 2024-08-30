<?php
session_start();
if (!isset($_SESSION['role'])) {
    die("Вы не авторизованы");
}

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

// Обработка добавления, удаления и изменения продуктов
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_SESSION['role'] == 'модератор' || $_SESSION['role'] == 'администратор') {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];
            if ($action == 'add') {
                $product_name = $_POST['product_name'];
                $calories = $_POST['calories'];
                $proteins = $_POST['proteins'];
                $fats = $_POST['fats'];
                $carbs = $_POST['carbs'];
                $insert_query = "INSERT INTO products (product_name, calories, proteins, fats, carbs) VALUES ('$product_name', '$calories', '$proteins', '$fats', '$carbs')";
                $mysql->query($insert_query);
            } elseif ($action == 'delete') {
                $product_id = $_POST['product_id'];
                $delete_query = "DELETE FROM products WHERE product_id='$product_id'";
                $mysql->query($delete_query);
            } elseif ($action == 'edit') {
                $product_id = $_POST['product_id'];
                $product_name = $_POST['product_name'];
                $calories = $_POST['calories'];
                $proteins = $_POST['proteins'];
                $fats = $_POST['fats'];
                $carbs = $_POST['carbs'];
                $update_query = "UPDATE products SET product_name='$product_name', calories='$calories', proteins='$proteins', fats='$fats', carbs='$carbs' WHERE product_id='$product_id'";
                $mysql->query($update_query);
            }
        }
    }
}

// Обработка изменения ролей пользователей
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_role']) && $_SESSION['role'] == 'администратор') {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['new_role'];
    $update_role_query = "UPDATE users SET role='$new_role' WHERE user_id='$user_id'";
    $mysql->query($update_role_query);
}

// Параметры для пагинации
$limit = isset($_GET['limit']) && in_array($_GET['limit'], [10, 25, 50]) ? (int)$_GET['limit'] : 25;
$page = isset($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Получение общего количества продуктов
$total_query = "SELECT COUNT(*) AS total FROM products";
$total_result = $mysql->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $limit);

// Получение списка продуктов с учетом пагинации
$products_query = "SELECT * FROM products LIMIT $limit OFFSET $offset";
$products_result = $mysql->query($products_query);

// Получение списка пользователей для администраторов
$users_result = null;
if ($_SESSION['role'] == 'администратор') {
    $users_query = "SELECT user_id, username, role FROM users";
    $users_result = $mysql->query($users_query);
}
?>


<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Питание</title>
  <link rel="stylesheet" href="styles/styleheader.css">
  <link rel="stylesheet" href="styles/stylesmain.css">
  <link rel="stylesheet" href="styles/nutri.css">
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
      <?php if ($_SESSION['role'] == 'администратор'): ?>
        <li><a href="#users">Пользователи</a></li>
      <?php endif; ?>
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

<main>
  <div class="results-container">
    <h1>Список продуктов</h1>
    <table class="leader-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Название продукта</th>
          <th>Калорийность</th>
          <th>Белки</th>
          <th>Жиры</th>
          <th>Углеводы</th>
          <?php if ($_SESSION['role'] == 'модератор' || $_SESSION['role'] == 'администратор'): ?>
            <th>Действия</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $products_result->fetch_assoc()): ?>
          <tr>
            <td><?php echo $row['product_id']; ?></td>
            <td><?php echo $row['product_name']; ?></td>
            <td><?php echo $row['calories']; ?></td>
            <td><?php echo $row['proteins']; ?></td>
            <td><?php echo $row['fats']; ?></td>
            <td><?php echo $row['carbs']; ?></td>
            <?php if ($_SESSION['role'] == 'модератор' || $_SESSION['role'] == 'администратор'): ?>
              <td>
                <form method="post" style="display:inline;">
                  <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                  <input type="hidden" name="action" value="delete">
                </form>
                <form method="post" style="display:inline;">
                  <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                  <input type="hidden" name="action" value="edit">
                  <input type="text" name="product_name" value="<?php echo $row['product_name']; ?>">
                  <input type="text" name="calories" value="<?php echo $row['calories']; ?>"> </br>
                  <input type="text" name="proteins" value="<?php echo $row['proteins']; ?>">
                  <input type="text" name="fats" value="<?php echo $row['fats']; ?>">
                  <input type="text" name="carbs" value="<?php echo $row['carbs']; ?>"> </br>
                  <button type="submit">Сохранить</button>
				  <button type="submit">Удалить</button>
                </form>
              </td>
            <?php endif; ?>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <?php if ($_SESSION['role'] == 'модератор' || $_SESSION['role'] == 'администратор'): ?>
      <h2>Добавить продукт</h2>
      <form method="post">
        <input type="hidden" name="action" value="add">
        <label for="product_name">Название продукта:</label>
        <input type="text" name="product_name" id="product_name" required>
        <label for="calories">Калорийность:</label>
        <input type="number" name="calories" id="calories" required>
        <label for="proteins">Белки:</label>
        <input type="number" name="proteins" id="proteins" required>
        <label for="fats">Жиры:</label>
        <input type="number" name="fats" id="fats" required>
        <label for="carbs">Углеводы:</label>
        <input type="number" name="carbs" id="carbs" required> </br>
        <button type="submit">Добавить</button>
      </form>
    <?php endif; ?>
    
    <!-- Buttons for changing the number of results per page -->
    <div class="pagination">
      <button class="results-button" onclick="window.location.href='?limit=10&page=1'">10</button>
      <button class="results-button" onclick="window.location.href='?limit=25&page=1'">25</button>
      <button class="results-button" onclick="window.location.href='?limit=50&page=1'">50</button>
    </div>

    <!-- Пагинация -->
    <div class="pagination">
      <a href="?page=<?php echo $page - 1; ?>&limit=<?php echo $limit; ?>" class="pagination-button <?php if ($page == 1) echo 'disabled'; ?>">&laquo; Предыдущая</a>
      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?php echo $i; ?>&limit=<?php echo $limit; ?>" class="pagination-button <?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
      <?php endfor; ?>
      <?php if ($page < $total_pages): ?>
        <a href="?page=<?php echo $page + 1; ?>&limit=<?php echo $limit; ?>" class="pagination-button">Следующая &raquo;</a>
      <?php endif; ?>
    </div>
  </div>

  <?php if ($_SESSION['role'] == 'администратор' && $users_result): ?>
    <div id="users" class="results-container">
      <h1>Управление пользователями</h1>
      <table class="leader-table">
        <thead>
          <tr>
            <th>ID пользователя</th>
            <th>Имя пользователя</th>
            <th>Роль</th>
            <th>Действия</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($user = $users_result->fetch_assoc()): ?>
            <tr>
              <td><?php echo $user['user_id']; ?></td>
              <td><?php echo $user['username']; ?></td>
              <td><?php echo $user['role']; ?></td>
              <td>
                <form method="post" style="display:inline;">
                  <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                  <select name="new_role">
                    <option value="пользователь" <?php if ($user['role'] == 'пользователь') echo 'selected'; ?>>Пользователь</option>
                    <option value="модератор" <?php if ($user['role'] == 'модератор') echo 'selected'; ?>>Модератор</option>
                    <option value="администратор" <?php if ($user['role'] == 'администратор') echo 'selected'; ?>>Администратор</option>
                  </select>
                  <button type="submit" name="change_role">Изменить роль</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</main>
</body>
</html>

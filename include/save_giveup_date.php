<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(403);
        echo json_encode(array("error" => "Пользователь не аутентифицирован"));
        exit();
    }
    $input = json_decode(file_get_contents('php://input'), true);
    $user_id = $_SESSION['user_id'];
    $giveup_date = $input['giveup_date'];
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hdb";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $stmt_select = $conn->prepare("SELECT attempt_id, start_date FROM user_attempts WHERE user_id = ? ORDER BY attempt_id DESC LIMIT 1");
    $stmt_select->bind_param("i", $user_id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $attempt_id = $row['attempt_id'];
        $start_date = $row['start_date'];
        $days_without_habit = (strtotime($giveup_date) - strtotime($start_date)) / (60 * 60 * 24);
        $stmt_update = $conn->prepare("UPDATE user_attempts SET giveup_date = ?, days_without_habit = ? WHERE attempt_id = ?");
        $stmt_update->bind_param("sii", $giveup_date, $days_without_habit, $attempt_id);
        if ($stmt_update->execute() === TRUE) {
            echo json_encode(array("success" => true));
        } else {
            http_response_code(500);
            echo json_encode(array("error" => "Ошибка при обновлении данных в базе данных"));
        }
        $stmt_update->close();
    } else {
        http_response_code(404);
        echo json_encode(array("error" => "Запись не найдена"));
    }
    $stmt_select->close();
    $conn->close();
} else {
    http_response_code(405);
    echo json_encode(array("error" => "Метод не поддерживается"));
}
?>

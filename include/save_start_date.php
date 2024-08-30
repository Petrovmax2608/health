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
    $start_date = $input['start_date'];
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hdb";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt_insert = $conn->prepare("INSERT INTO user_attempts (user_id, start_date) VALUES (?, ?)");
    $stmt_insert->bind_param("is", $user_id, $start_date);

    if ($stmt_insert->execute() === TRUE) {
        echo json_encode(array("success" => true));
    } else {
        http_response_code(500);
        echo json_encode(array("error" => "Ошибка при сохранении данных в базе данных"));
    }

    $stmt_insert->close();
    $conn->close();
} else {
    http_response_code(405);
    echo json_encode(array("error" => "Метод не поддерживается"));
}
?>

<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(403);
        echo json_encode(array("error" => "Пользователь не аутентифицирован"));
        exit();
    }
    $user_id = $_SESSION['user_id'];
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $stmt = $conn->prepare("SELECT start_date FROM habits WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $start_date = $row['start_date'];
        echo json_encode(array("success" => true, "start_date" => $start_date));
    } else {
        echo json_encode(array("success" => true, "start_date" => null));
    }
    $stmt->close();
    $conn->close();
} else {
    http_response_code(405);
    echo json_encode(array("error" => "Метод не поддерживается"));
}
?>

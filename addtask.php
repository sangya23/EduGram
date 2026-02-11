<?php
session_start();
header('Content-Type: application/json');


if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit();
}

$conn = new mysqli("localhost", "root", "", "EDUGRAM");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => $conn->connect_error]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $_SESSION['user_id']; 
$task = $conn->real_escape_string($data['task']);
$due = $conn->real_escape_string($data['due']);

$stmt = $conn->prepare("INSERT INTO tasks (user_id, task, due) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $task, $due);

if ($stmt->execute()) {
    echo json_encode(["id" => $stmt->insert_id, "success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}

$stmt->close();
$conn->close();
?>
<?php
session_start();
header('Content-Type: application/json');

// Check authentication
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit();
}

$conn = new mysqli("localhost","root","","EDUGRAM");

if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed']);
    exit();
}

$user_id = $_SESSION['user_id']; // ✅ FIXED: Use actual logged-in user

$stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$tasks = [];
while($row = $result->fetch_assoc()){
    $tasks[] = $row;
}

echo json_encode($tasks);

$stmt->close();
$conn->close();
?>
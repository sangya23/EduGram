<?php
$conn = new mysqli("localhost", "root", "", "EDUGRAM");
$data = json_decode(file_get_contents("php://input"), true);

$userId = 1;
$dob = $data['dob'];
$education = $data['education'];
$major = $data['major'];


$check = $conn->query("SELECT id FROM users WHERE id = $userId");

if ($check->num_rows > 0) {
    $stmt = $conn->prepare("UPDATE users SET dob=?, education=?, major=? WHERE id=?");
    $stmt->bind_param("sssi", $dob, $education, $major, $userId);
} else {
    
    $stmt = $conn->prepare("INSERT INTO users (id, dob, education, major, email, full_name) VALUES (?, ?, ?, ?, 'user@example.com', 'User')");
    $stmt->bind_param("isss", $userId, $dob, $education, $major);
}

$success = $stmt->execute();
echo json_encode(["success" => $success]);
?>
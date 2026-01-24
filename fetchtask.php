<?php
$conn = new mysqli("localhost","root","","EDUGRAM");

$user_id = 1;

$result = $conn->query("SELECT * FROM tasks WHERE user_id=$user_id");

$tasks = [];
while($row = $result->fetch_assoc()){
    $tasks[] = $row;
}

echo json_encode($tasks);

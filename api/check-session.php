<?php
require_once 'db.php';
header('Content-Type: application/json');

if (isLoggedIn()) {
    $user = getCurrentUser();
    echo json_encode([
        'logged_in' => true,
        'name' => $user['name'],
        'email' => $user['email']
    ]);
} else {
    echo json_encode(['logged_in' => false]);
}
?>
<?php

session_start();


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


header('Content-Type: application/json');


$host = "localhost";
$user = "root";
$pass = "";
$dbname = "edugram";

try {
    
    $pdo = new PDO("mysql:host=$host", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

   
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    $pdo->exec("USE `$dbname`");

    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS study_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            study_date DATE NOT NULL,
            study_minutes INT DEFAULT 0,
            UNIQUE KEY unique_log (user_id, study_date),
            INDEX idx_user_date (user_id, study_date)
        )
    ");

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "DB Connection Error: " . $e->getMessage()]);
    exit;
}


if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

$user_id = (int)$_SESSION['user_id'];


$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'log_time') {
   
    $minutes = (int)($_POST['minutes'] ?? 0);
    $date = date('Y-m-d');

    if ($minutes > 0) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO study_logs (user_id, study_date, study_minutes) 
                VALUES (:uid, :date, :mins) 
                ON DUPLICATE KEY UPDATE study_minutes = study_minutes + :mins
            ");
            $stmt->execute([':uid' => $user_id, ':date' => $date, ':mins' => $minutes]);
            echo json_encode([
                "status" => "success", 
                "logged" => $minutes,
                "user_id" => $user_id,
                "date" => $date
            ]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => "warning", "message" => "No minutes to log"]);
    }

} elseif ($action === 'get_report') {
    
    try {
        $stmt = $pdo->prepare("
            SELECT study_date, study_minutes 
            FROM study_logs 
            WHERE user_id = :uid 
            AND study_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) 
            ORDER BY study_date ASC
        ");
        $stmt->execute([':uid' => $user_id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        
        echo json_encode([
            "status" => "success",
            "user_id" => $user_id,
            "data" => $data
        ]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    
} else {
    echo json_encode(["status" => "error", "message" => "Invalid action specified"]);
}
?>
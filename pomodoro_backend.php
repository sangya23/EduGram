<?php
// pomodoro_backend.php
// 1. Enable Error Reporting to catch issues
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Set JSON Header
header('Content-Type: application/json');

// --- DATABASE CONFIGURATION ---
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "edugram"; // Ensure this matches your Assignment.php DB name

try {
    // 3. Connect to MySQL (without selecting DB first)
    $pdo = new PDO("mysql:host=$host", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // 4. Create Database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    $pdo->exec("USE `$dbname`");

    // 5. Create Table if it doesn't exist
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS study_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT DEFAULT 1,
            study_date DATE NOT NULL,
            study_minutes INT DEFAULT 0,
            UNIQUE KEY unique_log (user_id, study_date)
        )
    ");

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "DB Connection Error: " . $e->getMessage()]);
    exit;
}

// --- HANDLE ACTIONS ---
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; 

if ($action === 'log_time') {
    // Add minutes to today's record
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
            echo json_encode(["status" => "success", "logged" => $minutes]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => "warning", "message" => "No minutes to log"]);
    }

} elseif ($action === 'get_report') {
    // Fetch last 30 days
    try {
        $stmt = $pdo->prepare("
            SELECT study_date, study_minutes 
            FROM study_logs 
            WHERE user_id = :uid AND study_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) 
            ORDER BY study_date ASC
        ");
        $stmt->execute([':uid' => $user_id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid action specified"]);
}
?>
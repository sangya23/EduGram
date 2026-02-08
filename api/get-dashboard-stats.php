<?php
// api/get-dashboard-stats.php
// Customized for YOUR database structure

session_start();
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'edugram';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Not logged in']);
        exit;
    }
    
    $user_id = $_SESSION['user_id'];
    
    // Count pending assignments (using is_done column from YOUR database)
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM assignments WHERE user_id = ? AND is_done = 0");
    $stmt->execute([$user_id]);
    $pendingAssignments = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Count upcoming exams (exams with future dates)
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM exams WHERE user_id = ? AND exam_date >= CURDATE()");
    $stmt->execute([$user_id]);
    $upcomingExams = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Count all tasks for completed todos count
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM tasks WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $completedTodos = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    echo json_encode([
        'success' => true,
        'stats' => [
            'pendingAssignments' => $pendingAssignments,
            'upcomingExams' => $upcomingExams,
            'completedTodos' => $completedTodos
        ]
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
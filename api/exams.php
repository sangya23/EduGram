<?php
// api/exams.php
// Customized for YOUR database structure
// Your table has: id, user_id, exam_date, subject, exam_type, full_marks, achieved_marks, goal_score, notes

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
    $method = $_SERVER['REQUEST_METHOD'];
    
    // GET - Fetch all exams for current user
    if ($method === 'GET') {
        $stmt = $pdo->prepare("SELECT id, exam_date, subject, exam_type as title, notes, 
                                      COALESCE(achieved_marks, 0) as achieved_marks, 
                                      COALESCE(full_marks, 100) as full_marks,
                                      '09:00:00' as exam_time
                               FROM exams 
                               WHERE user_id = ? 
                               ORDER BY exam_date ASC");
        $stmt->execute([$user_id]);
        $exams = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'exams' => $exams]);
    }
    
    // POST - Add new exam
    elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['title']) || !isset($data['subject']) || !isset($data['exam_date'])) {
            echo json_encode(['success' => false, 'message' => 'Missing fields']);
            exit;
        }
        
        $exam_time = isset($data['exam_time']) ? $data['exam_time'] : '09:00:00';
        $exam_type = $data['title'];
        $notes = isset($data['notes']) ? $data['notes'] : '';
        
        $stmt = $pdo->prepare("INSERT INTO exams (user_id, exam_date, subject, exam_type, notes) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $data['exam_date'], $data['subject'], $exam_type, $notes]);
        
        echo json_encode(['success' => true, 'message' => 'Exam added', 'id' => $pdo->lastInsertId()]);
    }
    
    // DELETE - Remove exam
    elseif ($method === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['id'])) {
            echo json_encode(['success' => false, 'message' => 'Missing exam ID']);
            exit;
        }
        
        $stmt = $pdo->prepare("DELETE FROM exams WHERE id = ? AND user_id = ?");
        $stmt->execute([$data['id'], $user_id]);
        
        echo json_encode(['success' => true, 'message' => 'Exam deleted']);
    }
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
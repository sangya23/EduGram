<?php


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
    
 
    if ($method === 'GET') {
        $stmt = $pdo->prepare("SELECT id, title, subject, priority, due_date, is_done as completed FROM assignments WHERE user_id = ? ORDER BY due_date ASC");
        $stmt->execute([$user_id]);
        $assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'assignments' => $assignments]);
    }
    
    
    elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['title']) || !isset($data['subject']) || !isset($data['due_date'])) {
            echo json_encode(['success' => false, 'message' => 'Missing fields']);
            exit;
        }
        
        $priority = isset($data['priority']) ? $data['priority'] : 'Medium';
        
        $stmt = $pdo->prepare("INSERT INTO assignments (user_id, title, subject, priority, due_date, is_done) VALUES (?, ?, ?, ?, ?, 0)");
        $stmt->execute([$user_id, $data['title'], $data['subject'], $priority, $data['due_date']]);
        
        echo json_encode(['success' => true, 'message' => 'Assignment added', 'id' => $pdo->lastInsertId()]);
    }
    
    
    elseif ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['id'])) {
            echo json_encode(['success' => false, 'message' => 'Missing assignment ID']);
            exit;
        }
        
        
        if (isset($data['completed'])) {
            $stmt = $pdo->prepare("UPDATE assignments SET is_done = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$data['completed'], $data['id'], $user_id]);
        }
        
        echo json_encode(['success' => true, 'message' => 'Assignment updated']);
    }
    
   
    elseif ($method === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['id'])) {
            echo json_encode(['success' => false, 'message' => 'Missing assignment ID']);
            exit;
        }
        
        $stmt = $pdo->prepare("DELETE FROM assignments WHERE id = ? AND user_id = ?");
        $stmt->execute([$data['id'], $user_id]);
        
        echo json_encode(['success' => true, 'message' => 'Assignment deleted']);
    }
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
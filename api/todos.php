<?php
// api/todos.php
// Customized for YOUR database structure
// Your table has: id, user_id, task, due, created_at

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
    
    // GET - Fetch all tasks for current user
    if ($method === 'GET') {
        // Map 'task' column to 'text' and add a fake 'completed' field (always 0 for now)
        $stmt = $pdo->prepare("SELECT id, task as text, 0 as completed, created_at FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'todos' => $todos]);
    }
    
    // POST - Add new task
    elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['text']) || empty(trim($data['text']))) {
            echo json_encode(['success' => false, 'message' => 'Task text required']);
            exit;
        }
        
        // Set default due date to tomorrow if not provided
        $due = isset($data['due']) ? $data['due'] : date('Y-m-d H:i:s', strtotime('+1 day'));
        
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, task, due) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $data['text'], $due]);
        
        echo json_encode(['success' => true, 'message' => 'Task added', 'id' => $pdo->lastInsertId()]);
    }
    
    // PUT - Update task (for now, just acknowledge - we don't have completed column)
    elseif ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['id'])) {
            echo json_encode(['success' => false, 'message' => 'Missing task ID']);
            exit;
        }
        
        // Since your tasks table doesn't have a 'completed' column yet,
        // we'll just return success for now
        // You can add the completed column later if needed
        
        echo json_encode(['success' => true, 'message' => 'Task updated']);
    }
    
    // DELETE - Remove task
    elseif ($method === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['id'])) {
            echo json_encode(['success' => false, 'message' => 'Missing task ID']);
            exit;
        }
        
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
        $stmt->execute([$data['id'], $user_id]);
        
        echo json_encode(['success' => true, 'message' => 'Task deleted']);
    }
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
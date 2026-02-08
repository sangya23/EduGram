<?php
// api/get_profile.php
// FIXED VERSION - Session starts FIRST, then checks POST data

// 1. Start session FIRST (before any output)
session_start();

// 2. Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

// 3. Include database connection
require_once 'db.php';

// 4. Get user_id from POST data OR session
$input = json_decode(file_get_contents('php://input'), true);
$user_id = $input['user_id'] ?? null;

// If not in POST, try session
if (!$user_id && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

// If still no user_id, return error
if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'User ID required - not authenticated']);
    exit;
}

try {
    // Fetch user data from users table and join with questionnaire
    $stmt = $pdo->prepare("
        SELECT u.id, u.name, u.email, u.created_at,
               q.education_level, q.current_year, q.major_subject, 
               q.study_hours_daily, q.goals, q.challenges
        FROM users u
        LEFT JOIN user_questionnaire q ON u.id = q.user_id
        WHERE u.id = ?
    ");
    
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // CRITICAL: Check if questionnaire is completed
        
        $questionnaire_completed = !is_null($user['education_level']) && 
                                   !empty($user['education_level']);
        
        // Format the response
        $response = [
            'success' => true,
            'profile' => [
                'id' => (int)$user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'member_since' => $user['created_at'],
                
                // THIS IS THE CRITICAL FIELD
                'questionnaire_completed' => $questionnaire_completed,
                
                'education_level' => $user['education_level'],
                'current_year' => $user['current_year'] ? (int)$user['current_year'] : null,
                'major_subject' => $user['major_subject'],
                'study_hours_daily' => $user['study_hours_daily'] ? (int)$user['study_hours_daily'] : null,
                'goals' => $user['goals'],
                'challenges' => $user['challenges']
            ]
        ];
        
        echo json_encode($response);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch profile: ' . $e->getMessage()
    ]);
}
?>
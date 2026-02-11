<?php

session_start();


header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');


require_once 'db.php';

$input = json_decode(file_get_contents('php://input'), true);
$user_id = $input['user_id'] ?? null;


if (!$user_id && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}


if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'User ID required - not authenticated']);
    exit;
}

try {
    
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
       
        $questionnaire_completed = !is_null($user['education_level']) && 
                                   !empty($user['education_level']);
        
    
        $response = [
            'success' => true,
            'profile' => [
                'id' => (int)$user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'member_since' => $user['created_at'],
                
  
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
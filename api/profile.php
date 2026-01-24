<?php
// api/get_profile.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Database connection
$host = 'localhost';
$dbname = 'edugram';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Get JSON data
$data = json_decode(file_get_contents('php://input'), true);
$user_id = $data['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'User ID required']);
    exit;
}

try {
    // Fetch user data with questionnaire data (LEFT JOIN in case questionnaire not completed)
    $stmt = $pdo->prepare("
        SELECT 
            u.id,
            u.name,
            u.email,
            u.created_at,
            q.education_level,
            q.current_year,
            q.major_subject,
            q.study_hours_daily,
            q.goals,
            q.challenges,
            q.updated_at as profile_updated_at
        FROM users u
        LEFT JOIN user_questionnaire q ON u.id = q.user_id
        WHERE u.id = ?
    ");
    
    $stmt->execute([$user_id]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($profile) {
        // Format the response
        $response = [
            'success' => true,
            'profile' => [
                'id' => $profile['id'],
                'name' => $profile['name'],
                'email' => $profile['email'],
                'member_since' => $profile['created_at'],
                'questionnaire_completed' => !is_null($profile['education_level']),
                'education_level' => $profile['education_level'],
                'current_year' => $profile['current_year'],
                'major_subject' => $profile['major_subject'],
                'study_hours_daily' => $profile['study_hours_daily'],
                'goals' => $profile['goals'],
                'challenges' => $profile['challenges'],
                'profile_updated_at' => $profile['profile_updated_at']
            ]
        ];
        
        echo json_encode($response);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error fetching profile: ' . $e->getMessage()]);
}
?>
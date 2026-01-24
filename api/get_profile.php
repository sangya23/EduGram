<?php
// api/save_questionnaire.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Include database connection
require_once 'api/db.php';

// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
$requiredFields = ['user_id', 'education_level', 'current_year', 'major_subject', 'study_hours_daily', 'goals'];
foreach ($requiredFields as $field) {
    if (!isset($data[$field]) || empty($data[$field])) {
        echo json_encode([
            'success' => false,
            'message' => "Field '$field' is required"
        ]);
        exit;
    }
}

$userId = intval($data['user_id']);
$educationLevel = trim($data['education_level']);
$currentYear = intval($data['current_year']);
$majorSubject = trim($data['major_subject']);
$studyHoursDaily = intval($data['study_hours_daily']);
$goals = trim($data['goals']);
$challenges = isset($data['challenges']) ? trim($data['challenges']) : '';

try {
    // Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    
    if (!$stmt->fetch()) {
        echo json_encode([
            'success' => false,
            'message' => 'User not found'
        ]);
        exit;
    }
    
    // Check if questionnaire already exists for this user
    $stmt = $pdo->prepare("SELECT id FROM user_questionnaire WHERE user_id = ?");
    $stmt->execute([$userId]);
    
    if ($stmt->fetch()) {
        // Update existing questionnaire
        $stmt = $pdo->prepare("
            UPDATE user_questionnaire 
            SET education_level = ?, 
                current_year = ?, 
                major_subject = ?, 
                study_hours_daily = ?, 
                goals = ?, 
                challenges = ?
            WHERE user_id = ?
        ");
        $stmt->execute([
            $educationLevel,
            $currentYear,
            $majorSubject,
            $studyHoursDaily,
            $goals,
            $challenges,
            $userId
        ]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Questionnaire updated successfully'
        ]);
    } else {
        // Insert new questionnaire
        $stmt = $pdo->prepare("
            INSERT INTO user_questionnaire 
            (user_id, education_level, current_year, major_subject, study_hours_daily, goals, challenges) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $userId,
            $educationLevel,
            $currentYear,
            $majorSubject,
            $studyHoursDaily,
            $goals,
            $challenges
        ]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Questionnaire saved successfully'
        ]);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to save questionnaire: ' . $e->getMessage()
    ]);
}
?>
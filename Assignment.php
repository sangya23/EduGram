<?php
// Session and user check
session_start();
require_once 'api/google_config.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'Assignment.php';
    header("Location: " . getAuthUrl());
    exit();
}

$user_id = $_SESSION['user_id'];

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "edugram";

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    $pdo->exec("USE $dbname");

    // ✅ FIXED: Add user_id to subjects table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS subjects (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            name VARCHAR(100) NOT NULL,
            color VARCHAR(20) NOT NULL,
            UNIQUE KEY unique_user_subject (user_id, name)
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS assignments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            subject VARCHAR(100) NOT NULL,
            priority VARCHAR(20),
            due_date DATE,
            is_done TINYINT DEFAULT 0
        )
    ");
} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}


if (isset($_POST['action'])) {
    header("Content-Type: application/json");

    switch ($_POST['action']) {

        case 'get_subjects':
            // ✅ FIXED: Only get subjects for this user
            $stmt = $pdo->prepare("SELECT * FROM subjects WHERE user_id = ? ORDER BY name");
            $stmt->execute([$user_id]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            exit;

        case 'add_subject':
            // ✅ FIXED: Include user_id when adding subject
            $stmt = $pdo->prepare("INSERT INTO subjects(user_id, name, color) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $_POST['name'], $_POST['color']]);
            echo json_encode(["success" => true]);
            exit;

        case 'delete_subject':
            // ✅ FIXED: Only delete if it belongs to this user
            $stmt = $pdo->prepare("DELETE FROM subjects WHERE id = ? AND user_id = ?");
            $stmt->execute([$_POST['id'], $user_id]);
            echo json_encode(["success" => true]);
            exit;

        case 'get_tasks':
            // ✅ Filter by user_id
            $stmt = $pdo->prepare("SELECT * FROM assignments WHERE user_id = ? ORDER BY due_date");
            $stmt->execute([$user_id]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            exit;

        case 'add_task':
            // ✅ Include user_id
            $stmt = $pdo->prepare("
                INSERT INTO assignments(user_id, title, subject, priority, due_date)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $user_id,
                $_POST['title'],
                $_POST['subject'],
                $_POST['priority'],
                $_POST['due_date']
            ]);
            echo json_encode(["success" => true]);
            exit;

        case 'done_task':
            // ✅ Only update if it belongs to this user
            $stmt = $pdo->prepare("UPDATE assignments SET is_done=1 WHERE id=? AND user_id=?");
            $stmt->execute([$_POST['id'], $user_id]);
            echo json_encode(["success" => true]);
            exit;

        case 'delete_task':
            // ✅ Only delete if it belongs to this user
            $stmt = $pdo->prepare("DELETE FROM assignments WHERE id=? AND user_id=?");
            $stmt->execute([$_POST['id'], $user_id]);
            echo json_encode(["success" => true]);
            exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment Section</title>


    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assignmentstyle.css" />
    
</head>
<body>

<div class="main">
    <div class="sidebar">
        <div class="sidebar-header">
            <h1>Edu-Gram</h1>
        </div>
        <div class="sidebar-nav">
            <a class="nav-item" href="index.html">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a class="nav-item active" href="Assignment.php">
                <i class="fas fa-tasks"></i>
                <span>Assignments</span>
            </a>
            <a class="nav-item" href="exam_list.php">
                <i class="fas fa-graduation-cap"></i>
                <span>Exams</span>
            </a>
            <a class="nav-item" href="Techniques.php">
                <i class="fas fa-lightbulb"></i>
                <span>Techniques</span>
            </a>
            <a class="nav-item" href="pomodoroindex.php">
                <i class="fas fa-clock"></i>
                <span>Pomodoro Timer</span>
            </a>
            <a class="nav-item" href="to-dolist.php">
                <i class="fas fa-list-check"></i>
                <span>To-Do List</span>
            </a>
            <a class="nav-item" href="help.php">
                <i class="fas fa-question-circle"></i>
                <span>Help</span>
            </a>
        </div>

    </div>

    <div class="content">
        <h1>Assignments</h1>
        <div class="add-box">
            <input type="text" id="taskInput" placeholder="Assignment title">
            <select id="subjectSelect"></select>
            <select id="prioritySelect">
                <option>Low</option>
                <option selected>Medium</option>
                <option>High</option>
            </select>
            <input type="date" id="dueDate">
            <button class="add" onclick="addTask()">Add Assignment</button>
        </div>
        <div class="header-row" style="display: flex; justify-content: space-between; align-items: center;">
            <h1>Assignments</h1>
            
            <div class="sort-dropdown">
                <button class="dropbtn"><i class="fas fa-sort-amount-down"></i> Sort By</button>
                <div class="dropdown-content">
                    <a href="#" onclick="sortTasks('subject')">Subject</a>
                    <a href="#" onclick="sortTasks('due_date')">Due Date</a>
                    <a href="#" onclick="sortTasks('priority')">Priority</a>
                </div>
            </div>
        </div>

        <div class="assignment-sections">
            <div class="section-container">
                <h2 class="section-title assigned-title">Assigned 📝</h2>
                <div id="assignedList"></div>
            </div>

            <div class="section-container">
                <h2 class="section-title late-title">Overdue ⚠️</h2>
                <div id="lateList"></div>
            </div>

            <div class="section-container">
                <h2 class="section-title turnedin-title">Turned In ✅</h2>
                <div id="turnedinList"></div>
            </div>
        </div>
    </div>

    <!-- MANAGE SUBJECT BOX -->
    <div class="subject-list">
        <h3>Manage Subjects</h3>
        <button onclick="openSubjectPopup()">Add Subject</button>
        <div id="subjectList"></div>
    </div>

   

    
</div>

<!-- POPUP -->
<div id="subjectOverlay" class="popup-overlay" onclick="closeSubjectPopup()"></div>
<div id="subjectCard" class="popup-card">
    <span class="close-btn" onclick="closeSubjectPopup()">&times;</span>
    <h3>Add Subject</h3>
    <input type="text" id="newSubjectName" placeholder="Subject name">
    <input type="color" id="newSubjectColor" value="#f28b82">
    <button onclick="addSubject()">Save Subject</button>
</div>


<script src="assignmentjs.js"></script>
</body>
</html>
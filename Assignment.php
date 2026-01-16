<?php
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

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS subjects (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) UNIQUE NOT NULL,
            color VARCHAR(20) NOT NULL
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS assignments (
            id INT AUTO_INCREMENT PRIMARY KEY,
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
            echo json_encode(
                $pdo->query("SELECT * FROM subjects ORDER BY name")
                    ->fetchAll(PDO::FETCH_ASSOC)
            );
            exit;

        case 'add_subject':
            $stmt = $pdo->prepare("INSERT INTO subjects(name,color) VALUES (?,?)");
            $stmt->execute([$_POST['name'], $_POST['color']]);
            echo json_encode(["success" => true]);
            exit;

        case 'delete_subject':
            $stmt = $pdo->prepare("DELETE FROM subjects WHERE id=?");
            $stmt->execute([$_POST['id']]);
            echo json_encode(["success" => true]);
            exit;

        case 'get_tasks':
            echo json_encode(
                $pdo->query("SELECT * FROM assignments ORDER BY due_date")
                    ->fetchAll(PDO::FETCH_ASSOC)
            );
            exit;

        case 'add_task':
            $stmt = $pdo->prepare("
                INSERT INTO assignments(title, subject, priority, due_date)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $_POST['title'],
                $_POST['subject'],
                $_POST['priority'],
                $_POST['due_date']
            ]);
            echo json_encode(["success" => true]);
            exit;

        case 'done_task':
            $stmt = $pdo->prepare("UPDATE assignments SET is_done=1 WHERE id=?");
            $stmt->execute([$_POST['id']]);
            echo json_encode(["success" => true]);
            exit;

        case 'delete_task':
            $stmt = $pdo->prepare("DELETE FROM assignments WHERE id=?");
            $stmt->execute([$_POST['id']]);
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
            <h1>Edu-gram</h1>
        </div>
        <div class="sidebar-nav">
            <a class="nav-item" href="#">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a class="nav-item active" href="Assignment.php">
                <i class="fas fa-file-alt"></i>
                <span>Assignments</span>
            </a>
            <a class="nav-item" href="#">
                <i class="fas fa-clipboard-list"></i>
                <span>Exams</span>
            </a>
            <a class="nav-item" href="pomodoroindex.php">
                <i class="fas fa-clock"></i>
                <span>Pomodoro Timer</span>
            </a>
            <a class="nav-item" href="to-dolist.php">
                <i class="fas fa-tasks"></i>
                <span>To-Do List</span>
            </a>
            <a class="nav-item" href="Techniques.php">
                <i class="fas fa-lightbulb"></i>
                <span>Techniques</span>
            </a>
            <a class="nav-item " href="profile.php">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
            <a class="nav-item" href="#">
                <i class="fas fa-question-circle"></i>
                <span>Help</span>
            </a>
        </div>
        <div class="sidebar-footer">
            <a class="nav-item" href="#">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
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
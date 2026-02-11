<?php
session_start();
require_once 'api/google_config.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'to-dolist.php';
    header("Location: " . getGoogleLoginUrl());
    exit();
}

$userId = $_SESSION['user_id'];
$userName = $_SESSION['user_name'] ?? 'User';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List - Edu-Gram</title>
    <script src="edugram(todoprofile).js"></script>
    <link rel="stylesheet" href="edugram(todoprofile).css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1>Edu-Gram</h1>
            </div>
            <div class="sidebar-nav">
                <a class="nav-item" href="index.html">
                    <i class="fas fa-home"></i>
                    <span>Home</span>
                </a>
                <a class="nav-item" href="Assignment.php">
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
                <a class="nav-item active" href="to-dolist.php">
                    <i class="fas fa-list-check"></i>
                    <span>To-Do List</span>
                </a>
                <a class="nav-item" href="help.php">
                    <i class="fas fa-question-circle"></i>
                    <span>Help</span>
                </a>
            </div>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>To-Do List ✅</h1>
                <p>Organize your daily tasks and stay productive</p>
            </div>

            <div class="todo">
                <div class="todo-box">
                    <input type="text" id="task-input" placeholder="Enter task">
                    <input type="datetime-local" id="deadline-input">
                    <button id="add-task-btn">Add</button>

                    <ul id="task-list"></ul>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
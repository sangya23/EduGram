<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}


$host="localhost"; 
$user="root"; 
$pass=""; 
$db="edugram";  

$conn=new mysqli($host,$user,$pass,$db);
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

$user_id = $_SESSION['user_id'];

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $exam_date=$_POST['exam_date'];
    $subject=$_POST['subject'];
    $exam_type=$_POST['exam_type'];
    $full_marks=$_POST['full_marks']!==""?$_POST['full_marks']:NULL;
    $achieved=$_POST['achieved_marks']!==""?$_POST['achieved_marks']:NULL;
    $goal_score=$_POST['goal_score']!==""?$_POST['goal_score']:NULL;
    $notes=$_POST['notes'];
    

    $check=$conn->prepare("SELECT id FROM exams WHERE exam_date=? AND subject=? AND exam_type=? AND user_id=?");
    $check->bind_param("sssi",$exam_date,$subject,$exam_type,$user_id);
    $check->execute();
    $check->store_result();

    if($check->num_rows>0){
        header("Location: exam_list.php?error=duplicate");
        exit;
    }
    $check->close();


    $stmt=$conn->prepare("INSERT INTO exams (user_id,exam_date,subject,exam_type,full_marks,achieved_marks,goal_score,notes) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param("isssddds",$user_id,$exam_date,$subject,$exam_type,$full_marks,$achieved,$goal_score,$notes);
    $stmt->execute();
    $stmt->close();

    header("Location: exam_list.php?added=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Exam - Edu-Gram</title>
<link rel="stylesheet" href="exam_style.css">
<link rel="stylesheet" href="common-style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="dark">

<div class="dashboard-container">
    <div class="sidebar">
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
            <a class="nav-item active" href="exam_list.php">
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
    
    <main class="main-content">
        <div class="page-header">
            <h1>Add New Exam 📝</h1>
            <p>Track your exam schedule and goals</p>
        </div>

        <div style="max-width: 600px; margin: 0 auto;">
            <form method="POST" class="exam-form">
                <label>Exam Date:
                    <input type="date" name="exam_date" required>
                </label>
                
                <label>Subject:
                    <input type="text" name="subject" placeholder="e.g., Mathematics, Physics" required>
                </label>
                
                <label>Exam Type:
                    <select name="exam_type" required>
                        <option value="">Select Exam Type</option>
                        <option value="Unit Test">Unit Test</option>
                        <option value="Terminal Exam">Terminal Exam</option>
                        <option value="Final Exam">Final Exam</option>
                        <option value="Internal Exam">Internal Exam</option>
                        <option value="End Semester Exam">End Semester Exam</option>
                        <option value="Other">Other</option>
                    </select>
                </label>
                
                <label>Full Marks:
                    <input type="number" name="full_marks" step="0.01" placeholder="e.g., 100">
                </label>
                
                <label>Achieved Marks (if already taken):
                    <input type="number" name="achieved_marks" step="0.01" placeholder="Leave empty if not taken yet">
                </label>
                
                <label>Goal Score:
                    <input type="number" name="goal_score" step="0.01" placeholder="e.g., 85">
                </label>
                
                <label>Notes:
                    <textarea name="notes" rows="4" placeholder="Add any notes or reminders..."></textarea>
                </label>
                
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" class="btn" style="flex: 1;">
                        <i class="fas fa-plus"></i> Add Exam
                    </button>
                    <a href="exam_list.php" class="btn" style="flex: 1; background: #6c757d; text-align: center; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>

<script src="exam_script.js"></script>
</body>
</html>
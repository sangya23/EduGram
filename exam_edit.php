<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

// ✅ FIXED: Changed database from 'exam_tracker' to 'edugram'
$host="localhost"; 
$user="root"; 
$pass=""; 
$db="edugram";

$conn=new mysqli($host,$user,$pass,$db);
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

$user_id = $_SESSION['user_id'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id <= 0) {
    header("Location: exam_list.php");
    exit;
}

// Get exam data (only if it belongs to this user)
$stmt = $conn->prepare("SELECT * FROM exams WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$exam = $result->fetch_assoc();
$stmt->close();

if(!$exam) {
    header("Location: exam_list.php?error=notfound");
    exit;
}

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $exam_date=$_POST['exam_date'];
    $subject=$_POST['subject'];
    $exam_type=$_POST['exam_type'];
    $full_marks=$_POST['full_marks']!==""?$_POST['full_marks']:NULL;
    $achieved=$_POST['achieved_marks']!==""?$_POST['achieved_marks']:NULL;
    $goal_score=$_POST['goal_score']!==""?$_POST['goal_score']:NULL;
    $notes=$_POST['notes'];

    // Update exam (only if it belongs to this user)
    $stmt=$conn->prepare("UPDATE exams SET exam_date=?,subject=?,exam_type=?,full_marks=?,achieved_marks=?,goal_score=?,notes=? WHERE id=? AND user_id=?");
    $stmt->bind_param("sssdddsii",$exam_date,$subject,$exam_type,$full_marks,$achieved,$goal_score,$notes,$id,$user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: exam_list.php?updated=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Exam - Edu-Gram</title>
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
            <h1>Edit Exam ✏️</h1>
            <p>Update exam details</p>
        </div>

        <div style="max-width: 600px; margin: 0 auto;">
            <form method="POST" class="exam-form">
                <label>Exam Date:
                    <input type="date" name="exam_date" value="<?= htmlspecialchars($exam['exam_date']) ?>" required>
                </label>
                
                <label>Subject:
                    <input type="text" name="subject" value="<?= htmlspecialchars($exam['subject']) ?>" required>
                </label>
                
                <label>Exam Type:
                    <select name="exam_type" required>
                        <option value="">Select Exam Type</option>
                        <option value="Unit Test" <?= $exam['exam_type']=='Unit Test'?'selected':'' ?>>Unit Test</option>
                        <option value="Terminal Exam" <?= $exam['exam_type']=='Terminal Exam'?'selected':'' ?>>Terminal Exam</option>
                        <option value="Final Exam" <?= $exam['exam_type']=='Final Exam'?'selected':'' ?>>Final Exam</option>
                        <option value="Internal Exam" <?= $exam['exam_type']=='Internal Exam'?'selected':'' ?>>Internal Exam</option>
                        <option value="End Semester Exam" <?= $exam['exam_type']=='End Semester Exam'?'selected':'' ?>>End Semester Exam</option>
                        <option value="Other" <?= $exam['exam_type']=='Other'?'selected':'' ?>>Other</option>
                    </select>
                </label>
                
                <label>Full Marks:
                    <input type="number" name="full_marks" step="0.01" value="<?= $exam['full_marks'] ?>">
                </label>
                
                <label>Achieved Marks:
                    <input type="number" name="achieved_marks" step="0.01" value="<?= $exam['achieved_marks'] ?>">
                </label>
                
                <label>Goal Score:
                    <input type="number" name="goal_score" step="0.01" value="<?= $exam['goal_score'] ?>">
                </label>
                
                <label>Notes:
                    <textarea name="notes" rows="4"><?= htmlspecialchars($exam['notes']) ?></textarea>
                </label>
                
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" class="btn" style="flex: 1;">
                        <i class="fas fa-save"></i> Update Exam
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
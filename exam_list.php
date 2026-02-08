<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = basename($_SERVER['PHP_SELF']);
    header("Location: " . getGoogleLoginUrl());
    exit();
}

// Get user info
$user_name = $_SESSION['name'] ?? 'User';
$user_email = $_SESSION['email'] ?? 'user@email.com';

// ✅ FIXED: Changed database from 'exam_tracker' to 'edugram'
$host="localhost"; 
$user="root"; 
$pass=""; 
$db="edugram";

$conn=new mysqli($host,$user,$pass,$db);
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

// Get user_id from session
$user_id = $_SESSION['user_id'];

if(isset($_GET['delete'])){
    $id=(int)$_GET['delete'];
	if($id>0){
        // Delete only if exam belongs to this user
        $conn->query("DELETE FROM exams WHERE id=$id AND user_id=$user_id");
        header("Location: exam_list.php?deleted=1");
        exit;
	}
}

$filter=$_GET['filter']??'all';
$type_filter=$_GET['exam_type']??'all';
$sort=$_GET['sort']??'upcoming_first';

switch($sort){
    case 'date_desc': $order="exam_date DESC, subject ASC"; break;
    case 'subject_asc': $order="subject ASC, exam_date ASC"; break;
    case 'subject_desc': $order="subject DESC, exam_date ASC"; break;
    case 'date_asc': $order="exam_date ASC, subject ASC"; break;
    case 'upcoming_first':
    default: $order="CASE WHEN exam_date >= CURDATE() THEN 0 ELSE 1 END ASC, exam_date ASC, subject ASC";
}

// Get only this user's exams
$result=$conn->query("SELECT * FROM exams WHERE user_id=$user_id ORDER BY $order");
$today=date("Y-m-d");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Exam List - Edu-Gram</title>
<link rel="stylesheet" href="exam_style.css">
<link rel="stylesheet" href="common-style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    /* Profile positioning CSS */
    .top-bar {
        position: relative;
        background: transparent;
        padding: 20px 40px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-bottom: -60px;
        z-index: 100;
    }
    
    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(255, 255, 255, 0.1);
        padding: 8px 15px;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .user-info:hover {
        background: rgba(255, 255, 255, 0.2);
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #0f3460, #4a90e2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
    }
    
    .user-details {
        display: flex;
        flex-direction: column;
        color: white;
    }
    
    .user-details .name {
        font-weight: 600;
        font-size: 14px;
    }
    
    .user-details .email {
        font-size: 12px;
        opacity: 0.9;
    }
    
    .user-dropdown-icon {
        color: white;
        font-size: 12px;
    }
</style>
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
        <!-- ✅ PROFILE SECTION - NOW ON TOP RIGHT -->
        
        
        <div class="page-header">
            <h1>Assessment Preparation 📋</h1>
            <p>Prepare smarter, revise faster, and stay confident for your exams.</p>
        </div>

<?php if(isset($_GET['updated'])): ?>
  <div class="toast success">&#10003; Exam updated successfully!</div>
<?php elseif(isset($_GET['added'])): ?>
  <div class="toast success">&#10003; Exam added successfully!</div>
<?php elseif(isset($_GET['deleted'])): ?>
  <div class="toast error">&#10006; Exam deleted successfully!</div>
<?php elseif(isset($_GET['error']) && $_GET['error']=='duplicate'): ?>
  <div class="toast error">&#10006; Exam already exists!</div>
<?php endif; ?>

<div class="filter-bar">
  <form method="GET">
    <select name="filter" onchange="this.form.submit()">
      <option value="all" <?= $filter=='all'?'selected':'' ?>>All</option>
      <option value="upcoming" <?= $filter=='upcoming'?'selected':'' ?>>Upcoming</option>
      <option value="done" <?= $filter=='done'?'selected':'' ?>>Done</option>
    </select>

    <select name="exam_type" onchange="this.form.submit()">
      <option value="all" <?= $type_filter=='all'?'selected':'' ?>>All Types</option>
      <option value="Unit Test" <?= $type_filter=='Unit Test'?'selected':'' ?>>Unit Test</option>
      <option value="Terminal Exam" <?= $type_filter=='Terminal Exam'?'selected':'' ?>>Terminal Exam</option>
      <option value="Final Exam" <?= $type_filter=='Final Exam'?'selected':'' ?>>Final Exam</option>
      <option value="Internal Exam" <?= $type_filter=='Internal Exam'?'selected':'' ?>>Internal Exam</option>
      <option value="End Semester Exam" <?= $type_filter=='End Semester Exam'?'selected':'' ?>>End Semester Exam</option>
      <option value="Other" <?= $type_filter=='Other'?'selected':'' ?>>Other</option>
    </select>

    <select name="sort" onchange="this.form.submit()">
      <option value="upcoming_first" <?= $sort=='upcoming_first'?'selected':'' ?>>Upcoming First</option>
      <option value="date_asc" <?= $sort=='date_asc'?'selected':'' ?>>Date &#8593; </option>
      <option value="date_desc" <?= $sort=='date_desc'?'selected':'' ?>>Date &#8595;</option>
      <option value="subject_asc" <?= $sort=='subject_asc'?'selected':'' ?>>Subject A &#8594; Z</option>
      <option value="subject_desc" <?= $sort=='subject_desc'?'selected':'' ?>>Subject Z &#8594; A</option>
    </select>
  </form>
</div>

<div class="exam-container">
<?php 
if($result && $result->num_rows > 0) {
    while($row=$result->fetch_assoc()): 
      $status=($row['exam_date']<$today)?'done':'upcoming';
      if($filter!='all' && $filter!=$status) continue;
      if($type_filter!='all' && $row['exam_type']!=$type_filter) continue;
?>
  <div class="exam-card">
    <div class="exam-header">
      <strong><?= htmlspecialchars($row['subject']) ?></strong>
      <span class="status <?= $status ?>"><?= ucfirst($status) ?></span>
    </div>
    <div class="exam-details">
      <p><strong>Date:</strong> <?= $row['exam_date'] ?></p>
      <p><strong>Type:</strong> <?= $row['exam_type'] ?></p>
      <p><strong>Full Marks:</strong> <?= $row['full_marks']??'-' ?></p>
      <p><strong>Achieved:</strong> <?= $row['achieved_marks']??'-' ?></p>
      <p><strong>Goal:</strong> <?= $row['goal_score']??'-' ?></p>
      <p class="notes"><strong>Notes:</strong> <?= $row['notes'] ?></p>
      <p><strong>Recorded:</strong> <?= $row['record'] ?></p>
      <p><strong>Needed:</strong>
        <?php
        if($row['goal_score']!==NULL && $row['achieved_marks']!==NULL){
          $needed=$row['goal_score']-$row['achieved_marks'];
          echo ($needed<=0)?"<span class='tick'>&#10003; Goal reached</span>":number_format($needed,2);
        } else echo "-";
        ?>
      </p>
    </div>
    <div class="exam-actions">
      <a href="exam_edit.php?id=<?= $row['id'] ?>" class="btn edit-btn">Edit</a>
      <a href="javascript:void(0)" onclick="confirmDelete('exam_list.php?delete=<?= $row['id'] ?>')" class="btn delete-btn">Delete</a>
    </div>
  </div>
<?php 
    endwhile;
} else {
    echo '<div style="text-align: center; padding: 40px; color: white;">No exams found. <a href="exam_form.php" style="color: #4a90e2;">Add your first exam!</a></div>';
}
?>
</div>

<p style="text-align:center;"><a href="exam_form.php" class="nav-btn">Add New Exam</a></p>
    </main>
</div>

<script src="exam_script.js"></script>
</body>
</html>
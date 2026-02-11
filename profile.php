
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edu-gram Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="edugram(todoprofile).css">
    <script src="edugram(todoprofile).js"></script>
</head>

<?php
$conn = new mysqli("localhost", "root", "", "EDUGRAM");

$userId = 1; 

$sql = "SELECT full_name, email, dob, education, major FROM users WHERE id = $userId";
$result = $conn->query($sql);
$user = $result->fetch_assoc();


if (!$user) {
    $user = [
        'name' =>'Not set',
        'email' => 'Not set',
        'dob' => '',
        'education' => '',
        'major' => ''
    ];
    $isIncomplete = true;
} else {
    
    $isIncomplete = (empty($user['dob']) || empty($user['education']));
}
?>
<body>


    <div class="dashboard-container">

    
        <div class="outergrid">
                <aside class="sidebar">
                    <div class="sidebar-header">
                        <h1>Edu-gram</h1>
                    </div>
                    <div class="sidebar-nav">
                        <a class="nav-item" href="#">
                            <i class="fas fa-home"></i>
                            <span>Home</span>
                        </a>
                        <a class="nav-item" href="Assignment.php">
                            <i class="fas fa-file-alt"></i>
                            <span>Assignments</span>
                        </a>
                        <a class="nav-item" href="#">
                            <i class="fas fa-clipboard-list"></i>
                            <span>Exams</span>
                        </a>
                        <a class="nav-item active" href="Techniques.php">
                            <i class="fas fa-lightbulb"></i>
                            <span>Techniques</span>
                        </a>
                        <a class="nav-item" href="pomodoroindex.php">
                            <i class="fas fa-clock"></i>
                            <span>Pomodoro Timer</span>
                        </a>
                        <a class="nav-item" href="to-dolist.php">
                            <i class="fas fa-tasks"></i>
                            <span>To-Do List</span>
                        </a>
                        <a class="nav-item" href="#">
                            <i class="fas fa-question-circle"></i>
                            <span>Help</span>
                        </a>
                    </div>

                </aside>
        </div>

    
        <div class="main-content">

            <div class="profile-block">
                <h2>Profile Information</h2>
            <p id="name">Name: <?php echo $user['full_name']; ?></p>    
            <p id="email">Email Address: <?php echo $user['email']; ?></p>
            <p id="dob">Date of Birth: <?php echo $user['dob']; ?></p>
            <p id="education">Education: <?php echo $user['education']; ?></p>
            <p id="major">Major Subject: <?php echo $user['major']; ?></p>
                <button id="edit-btn">Edit</button>
                <button id="save-btn" style="display:none;">Save</button>
            </div>
            
        </div>


    </div>


</body>
</html>

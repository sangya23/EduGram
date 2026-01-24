<html>
    <head>
        <script src="edugram(todoprofile).js"></script>
      <link rel="stylesheet" href="edugram(todoprofile).css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
    <body>
        <div class="dashboard-container">

 
    <div class="sidebar">
        <div class="sidebar-header">
            <h1>EDU-GRAM</h1>
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
    <a class="nav-item" href="pomodoroindex.php">
        <i class="fas fa-clock"></i>
        <span>Pomodoro Timer</span>
    </a>
    <a class="nav-item active" href="to-dolist.php">
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
    

    
    <div class="main-content">
        <div class="todo">
            <div class="todo-box">
    <h2>To-Do List</h2>

    <input type="text" id="task-input" placeholder="Enter task">
    <input type="datetime-local" id="deadline-input">
    <button id="add-task-btn">Add</button>
    

    <ul id="task-list"></ul>
</div>

      
        </div>
        </div>
</div>
    </body>
</html>
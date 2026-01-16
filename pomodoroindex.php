<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pomodoro Timer</title>
  <link rel="stylesheet" href="pomodorostyle.css"/>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
</head>
<body>
  <section class="header">
    <nav>
      <a href="#"><h1>Pomodoro</h1></a>
    </nav>
  </section>
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
      <a class="nav-item active" href="pomodoroindex.php">
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
    <div class="container pomodoro">
      <div class="pannel">
        <p id="work" class="active">Work</p>
        <p id="break">Break</p>
        <p id="longBreak">Long Break</p>
      </div>

      <div class="timer">
        <p id="minutes">25</p><p>:</p><p id="seconds">00</p>
      </div>

      <div class="controls">
        <button id="start" class="btn">START</button>
        <button id="pause" class="btn">PAUSE</button>
        <button id="reset" class="btn">RESET</button>
      </div>

      <div class="settings">
        <label>Work: <input id="workInput" type="number" value="25" min="1"/></label>
        <label>Break: <input id="breakInput" type="number" value="5" min="1"/></label>
        <label>Long Break: <input id="longBreakInput" type="number" value="15" min="1"/></label>
      </div>
    </div>

  <audio id="alarmSound" src="alert.mp3" preload="auto"></audio>
  <script src="pomodoroscript.js"></script>
</body>
</html>

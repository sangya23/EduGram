<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pomodoro Timer</title>
  <link rel="stylesheet" href="pomodorostyle.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
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
            <a class="nav-item" href="exam_list.php">
                <i class="fas fa-graduation-cap"></i>
                <span>Exams</span>
            </a>
            <a class="nav-item" href="Techniques.php">
                <i class="fas fa-lightbulb"></i>
                <span>Techniques</span>
            </a>
            <a class="nav-item active" href="pomodoroindex.php">
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
        <h1>Pomodoro Timer ⏰</h1>
        <p>Boost your productivity with focused study sessions</p>
      </div>

    <div class="container pomodoro">
      
      <div class="side-tools">
        
        <div class="tool-wrapper">
            <div class="tool-icon" id="musicIcon" title="Focus Music">
                <i class="fas fa-music"></i>
            </div>
            <div class="music-dropdown" id="musicDropdown">
                <h4>Focus Music</h4>
                <div class="music-list">
                    <div class="music-option" data-src="focus music/rain.mp3">Rain Sounds</div>
                    <div class="music-option" data-src="focus music/lofi-beat.mp3">Lofi Beats</div>
                    <div class="music-option" data-src="focus music/Classical piano.mp3">Soft Piano</div>
                    <div class="music-option" data-src="focus music/Forest.mp3">Forest</div>
                    <div class="music-option" data-src="focus music/Cafe-sound.mp3">Cafe Ambience</div>
                    <div class="music-option" data-src="focus music/Japanese garden.mp3">Japanese Garden</div>
                    <div class="music-option" data-src="focus music/Typing-sound.mp3">Typing Sound</div>
                </div>
                <div class="music-controls">
                    <button id="togglePlay"><i class="fas fa-play"></i></button>
                    <input type="range" id="volSlider" min="0" max="1" step="0.1" value="0.5">
                </div>
            </div>
        </div>

        <div class="tool-wrapper">
            <div class="tool-icon" id="reportBtn" title="Study Report">
                <i class="fas fa-chart-bar"></i>
            </div>
        </div>
      </div>

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
    </main>
  </div>

  <div id="reportModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2><i class="fas fa-chart-line"></i> Study Progress</h2>
        <div class="chart-container">
            <canvas id="studyChart"></canvas>
        </div>
        <p class="stats-text">Total Streak: <span id="streakDisplay" style="color:#4da6ff">0</span> Days</p>
    </div>
  </div>
    <audio id="alarmSound" src="alert.mp3" preload= "auto"> </audio>
    <audio id="bgMusic" loop></audio>
  
  <script src="pomodoroscript.js"></script>
</body>
</html>
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pomodoro Timer</title>
  <link rel="stylesheet" href="style.css"/>
</head>
<body>
  <section class="header">
    <nav>
      <a href="#"><h1>Pomodoro</h1></a>
    </nav>
  </section>

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
  <script src="script.js"></script>
</body>
</html>

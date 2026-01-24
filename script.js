// Elements
const workTitle = document.getElementById('work');
const breakTitle = document.getElementById('break');
const longBreakTitle = document.getElementById('longBreak');
const minutesDisplay = document.getElementById('minutes');
const secondsDisplay = document.getElementById('seconds');

const startBtn = document.getElementById('start');
const pauseBtn = document.getElementById('pause');
const resetBtn = document.getElementById('reset');

const workInput = document.getElementById('workInput');
const breakInput = document.getElementById('breakInput');
const longBreakInput = document.getElementById('longBreakInput');

const alarmSound = document.getElementById('alarmSound');

// State
let timer = null;
let mode = 'work';
let workSessions = 0;
let remainingMinutes = null;
let remainingSeconds = null;

// Helpers
function updateDisplay(mins, secs) {
  minutesDisplay.textContent = String(Math.max(0, mins)).padStart(2, '0');
  secondsDisplay.textContent = String(Math.max(0, secs)).padStart(2, '0');
}

function getDurations() {
  return {
    workTime: parseInt(workInput.value) || 25,
    breakTime: parseInt(breakInput.value) || 5,
    longBreakTime: parseInt(longBreakInput.value) || 15
  };
}

function setActiveTab(tab) {
  workTitle.classList.remove('active');
  breakTitle.classList.remove('active');
  longBreakTitle.classList.remove('active');
  if (tab === 'work') workTitle.classList.add('active');
  if (tab === 'break') breakTitle.classList.add('active');
  if (tab === 'longBreak') longBreakTitle.classList.add('active');
}

// Core timer
function startTimer() {
  clearInterval(timer);

  const { workTime, breakTime, longBreakTime } = getDurations();

  let minutes, seconds;

  // Resume if paused
  if (remainingMinutes !== null && remainingSeconds !== null) {
    minutes = remainingMinutes;
    seconds = remainingSeconds;
    remainingMinutes = null;
    remainingSeconds = null;
  } else {
    minutes =
      mode === 'work' ? workTime :
      mode === 'break' ? breakTime : longBreakTime;
    minutes -= 1;
    seconds = 59;
  }

  timer = setInterval(() => {
    updateDisplay(minutes, seconds);
    seconds -= 1;

    if (seconds < 0) {
      minutes -= 1;
      seconds = 59;
    }

    if (minutes < 0) {
      clearInterval(timer);
      try { alarmSound.play(); } catch (_) {}

      if (mode === 'work') {
        workSessions += 1;
        mode = (workSessions % 4 === 0) ? 'longBreak' : 'break';
      } else {
        mode = 'work';
      }

      setActiveTab(mode);
      const nextMinutes =
        mode === 'work' ? workTime :
        mode === 'break' ? breakTime : longBreakTime;
      updateDisplay(nextMinutes, 0);
    }
  }, 1000);
}

function pauseTimer() {
  clearInterval(timer);
  remainingMinutes = parseInt(minutesDisplay.textContent);
  remainingSeconds = parseInt(secondsDisplay.textContent);
}

function resetTimer() {
  clearInterval(timer);
  remainingMinutes = null;
  remainingSeconds = null;
  mode = 'work';
  setActiveTab('work');
  const { workTime } = getDurations();
  updateDisplay(workTime, 0);
  alarmSound.pause();
  alarmSound.currentTime = 0;
}

// Events
startBtn.addEventListener('click', startTimer);
pauseBtn.addEventListener('click', pauseTimer);
resetBtn.addEventListener('click', resetTimer);

workTitle.addEventListener('click', () => {
  clearInterval(timer);
  remainingMinutes = null;
  remainingSeconds = null;
  mode = 'work';
  setActiveTab('work');
  const { workTime } = getDurations();
  updateDisplay(workTime, 0);
});

breakTitle.addEventListener('click', () => {
  clearInterval(timer);
  remainingMinutes = null;
  remainingSeconds = null;
  mode = 'break';
  setActiveTab('break');
  const { breakTime } = getDurations();
  updateDisplay(breakTime, 0);
});

longBreakTitle.addEventListener('click', () => {
  clearInterval(timer);
  remainingMinutes = null;
  remainingSeconds = null;
  mode = 'longBreak';
  setActiveTab('longBreak');
  const { longBreakTime } = getDurations();
  updateDisplay(longBreakTime, 0);
});

// Initial display
updateDisplay(parseInt(workInput.value) || 25, 0);

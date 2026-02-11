
const minutesDisplay = document.getElementById('minutes');
const secondsDisplay = document.getElementById('seconds');
const startBtn = document.getElementById('start');
const pauseBtn = document.getElementById('pause');
const resetBtn = document.getElementById('reset');
const alarmSound = document.getElementById('alarmSound'); 

const workTitle = document.getElementById('work');
const breakTitle = document.getElementById('break');
const longBreakTitle = document.getElementById('longBreak');


const musicIcon = document.getElementById('musicIcon');
const musicDropdown = document.getElementById('musicDropdown');
const musicOptions = document.querySelectorAll('.music-option');
const togglePlayBtn = document.getElementById('togglePlay');
const volSlider = document.getElementById('volSlider');
const bgMusic = document.getElementById('bgMusic');


const reportBtn = document.getElementById('reportBtn');
const reportModal = document.getElementById('reportModal');
const closeModal = document.querySelector('.close-modal');
const streakDisplay = document.getElementById('streakDisplay');


let timer = null;
let mode = 'work';
let workSessions = 0;
let remainingMinutes = null;
let remainingSeconds = null;


musicIcon.addEventListener('click', (e) => {
    e.stopPropagation();
    musicDropdown.classList.toggle('show');
});

document.addEventListener('click', (e) => {
    if (!musicIcon.contains(e.target) && !musicDropdown.contains(e.target)) {
        musicDropdown.classList.remove('show');
    }
});

musicOptions.forEach(option => {
    option.addEventListener('click', () => {
        musicOptions.forEach(opt => opt.classList.remove('selected'));
        option.classList.add('selected');

        const src = option.getAttribute('data-src');
        bgMusic.src = src;
        bgMusic.play();
        togglePlayBtn.innerHTML = '<i class="fas fa-pause"></i>';
    });
});

togglePlayBtn.addEventListener('click', () => {
    if (!bgMusic.src) return;
    
    if (bgMusic.paused) {
        bgMusic.play();
        togglePlayBtn.innerHTML = '<i class="fas fa-pause"></i>';
    } else {
        bgMusic.pause();
        togglePlayBtn.innerHTML = '<i class="fas fa-play"></i>';
    }
});

volSlider.addEventListener('input', (e) => {
    bgMusic.volume = e.target.value;
});

/* --- TIMER LOGIC --- */
function updateDisplay(mins, secs) {
  minutesDisplay.textContent = String(Math.max(0, mins)).padStart(2, '0');
  secondsDisplay.textContent = String(Math.max(0, secs)).padStart(2, '0');
}

function getDurations() {
  return {
    workTime: parseInt(document.getElementById('workInput').value) || 25,
    breakTime: parseInt(document.getElementById('breakInput').value) || 5,
    longBreakTime: parseInt(document.getElementById('longBreakInput').value) || 15
  };
}

function startTimer() {
  clearInterval(timer);
  const { workTime, breakTime, longBreakTime } = getDurations();
  let minutes, seconds;

  if (remainingMinutes !== null) {
    minutes = remainingMinutes;
    seconds = remainingSeconds;
    remainingMinutes = null; remainingSeconds = null;
  } else {
    minutes = mode === 'work' ? workTime : mode === 'break' ? breakTime : longBreakTime;
    minutes -= 1;
    seconds = 59;
  }

  if (mode === 'work' && bgMusic.src && bgMusic.paused) {
      bgMusic.play();
      togglePlayBtn.innerHTML = '<i class="fas fa-pause"></i>';
  } else if (mode !== 'work') {
      bgMusic.pause();
      togglePlayBtn.innerHTML = '<i class="fas fa-play"></i>';
  }

  timer = setInterval(() => {
    updateDisplay(minutes, seconds);
    
    
    if (mode === 'work' && seconds === 0) {
        logStudyTime(1); 
    }

    seconds -= 1;
    if (seconds < 0) {
      minutes -= 1;
      seconds = 59;
    }

    if (minutes < 0) {
      clearInterval(timer);
      if(alarmSound) { try { alarmSound.play(); } catch (_) {} }
      
      mode = getNextMode();
      
      if (mode !== 'work') {
          bgMusic.pause();
          togglePlayBtn.innerHTML = '<i class="fas fa-play"></i>';
      } 
      
      setActiveTab(mode);
      const nextMins = mode === 'work' ? workTime : mode === 'break' ? breakTime : longBreakTime;
      updateDisplay(nextMins, 0);
    }
  }, 1000);
}

function pauseTimer() {
  clearInterval(timer);
  remainingMinutes = parseInt(minutesDisplay.textContent);
  remainingSeconds = parseInt(secondsDisplay.textContent);
  
  bgMusic.pause();
  togglePlayBtn.innerHTML = '<i class="fas fa-play"></i>';
}

function resetTimer() {
  clearInterval(timer);
  remainingMinutes = null;
  mode = 'work';
  setActiveTab('work');
  updateDisplay(getDurations().workTime, 0);
  
  bgMusic.pause();
  bgMusic.currentTime = 0;
  togglePlayBtn.innerHTML = '<i class="fas fa-play"></i>';
}

function setActiveTab(tab) {
    [workTitle, breakTitle, longBreakTitle].forEach(t => t.classList.remove('active'));
    document.getElementById(tab).classList.add('active');
}

function getNextMode() {
  if (mode === 'work') {
    workSessions++;
    return (workSessions % 4 === 0) ? 'longBreak' : 'break';
  }
  return 'work';
}

[workTitle, breakTitle, longBreakTitle].forEach(title => {
    title.addEventListener('click', () => {
        clearInterval(timer);
        mode = title.id;
        setActiveTab(mode);
        remainingMinutes = null;
        
        if (mode !== 'work') {
            bgMusic.pause();
            togglePlayBtn.innerHTML = '<i class="fas fa-play"></i>';
        }

        const d = getDurations();
        const t = mode === 'work' ? d.workTime : mode === 'break' ? d.breakTime : d.longBreakTime;
        updateDisplay(t, 0);
    });
});

startBtn.addEventListener('click', startTimer);
pauseBtn.addEventListener('click', pauseTimer);
resetBtn.addEventListener('click', resetTimer);

/* --- BACKEND CONNECTION - COMPLETELY FIXED --- */

function logStudyTime(minutes) {
    const formData = new FormData();
    formData.append('action', 'log_time');
    formData.append('minutes', minutes);

    fetch('pomodoro_backend.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        try {
            const data = JSON.parse(text);
            if(data.status !== 'success') {
                console.warn("DB Log Warning:", data.message);
            } else {
                console.log("✓ Logged", minutes, "minute(s) for user", data.user_id);
            }
        } catch (e) {
            console.error("Backend Error (Invalid JSON):", text);
        }
    })
    .catch(err => console.error("Network Error:", err));
}


reportBtn.addEventListener('click', () => {
    reportModal.style.display = "block";
    loadChartData();
});

closeModal.addEventListener('click', () => {
    reportModal.style.display = "none";
});

window.onclick = function(event) {
    if (event.target == reportModal) {
        reportModal.style.display = "none";
    }
}

let myChart = null;

function loadChartData() {
    console.log('📊 Loading study report...');
    
    fetch('pomodoro_backend.php?action=get_report')
    .then(response => response.text())
    .then(text => {
        console.log('Raw backend response:', text);
        try {
            const response = JSON.parse(text);
            console.log('Parsed response:', response);
            
            
            let data = [];
            let userId = null;
            
            if (response.status === 'success') {
               
                data = response.data || [];
                userId = response.user_id;
            } else if (response.status === 'error') {
                console.error("❌ Report Error:", response.message);
                renderChart([], []);
                if(streakDisplay) streakDisplay.innerText = '0';
                return;
            } else if (Array.isArray(response)) {
                
                data = response;
            }
            
            console.log('Study data for user', userId, ':', data);
            
            if (data.length === 0) {
                console.log('No study data found');
                renderChart([], []);
                if(streakDisplay) streakDisplay.innerText = '0';
                return;
            }

            const labels = data.map(entry => entry.study_date);
            const minutes = data.map(entry => entry.study_minutes);
            const hours = minutes.map(m => (m / 60).toFixed(1));

            if(streakDisplay) streakDisplay.innerText = data.length;

            console.log('Rendering chart with labels:', labels, 'hours:', hours);
            renderChart(labels, hours);
            
        } catch (e) {
            console.error("❌ JSON Parse Error:", e);
            console.error("Response text was:", text);
        }
    })
    .catch(err => console.error("❌ Network Error:", err));
}

function renderChart(labels, data) {
    const ctx = document.getElementById('studyChart').getContext('2d');
    
    if (myChart) {
        myChart.destroy();
    }

    myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels.length > 0 ? labels : ['No Data'],
            datasets: [{
                label: 'Hours Studied',
                data: data.length > 0 ? data : [0],
                backgroundColor: 'rgba(77, 166, 255, 0.5)',
                borderColor: '#4da6ff',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: '#ccc' },
                    grid: { color: 'rgba(255,255,255,0.1)' }
                },
                x: {
                    ticks: { color: '#ccc' },
                    grid: { display: false }
                }
            },
            plugins: {
                legend: { labels: { color: 'white' } }
            }
        }
    });
    
    console.log('✓ Chart rendered successfully');
}
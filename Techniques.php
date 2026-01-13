<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Study Techniques</title>

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="technistyle.css" />
</head>
<body>

<div class="outergrid">
    <aside class="sidebar">
        <div class="sidebar-header">
            <h1>Edu-gram</h1>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-item" onclick="switchPage('home')">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </div>
            <div class="nav-item">
                <i class="fas fa-tasks"></i>
                <a href="Assignment.php" style="text-decoration:none;color:inherit;">Assignments</a>
            </div>
            <div class="nav-item" onclick="switchPage('exams')">
                <i class="fas fa-graduation-cap"></i>
                <span>Exams</span>
            </div>
            <div class="nav-item" onclick="switchPage('pomodoro')">
                <i class="fas fa-clock"></i>
                <span>Pomodoro Timer</span>
            </div>
            <div class="nav-item" onclick="switchPage('todo')">
                <i class="fas fa-list-check"></i>
                <span>To-Do List</span>
            </div>
            <div class="nav-item active" >
                <i class="fas fa-lightbulb"></i>
                <a href="Techniques.php" style="text-decoration:none;color:inherit;">Techniques</a>
            </div>
            <div class="nav-item" onclick="switchPage('profile')">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </div>
            <div class="nav-item" onclick="switchPage('help')">
                <i class="fas fa-question-circle"></i>
                <span>Help</span>
            </div>
            <div class="nav-item" onclick="handleLogout()">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </div>
        </nav>

    </aside>
    
    <div class="main-content">
        <h1 style="color: white; margin-bottom: 20px;">Effective Study Techniques 📚</h1>
        <div class="grid">
            <div class="card" onclick="flipCard(this)">
                <div class="inner-card">
                    <div class="front g1">
                        <span class="material-symbols-outlined icon">timer</span>
                        <div class="card-title">Pomodoro Technique</div>
                        <div class="card-text">Focus in short timed intervals.</div>
                    </div>
                    <div class="back">
                        Work for 25 minutes, then take a 5-minute break.  
                        After four cycles, take a longer break.  
                        Helps maintain focus and prevent burnout.
                    </div>
                </div>
            </div>

            <div class="card" onclick="flipCard(this)">
                <div class="inner-card">
                    <div class="front g2">
                        <span class="material-symbols-outlined icon">neurology</span>
                        <div class="card-title">Active Recall</div>
                        <div class="card-text">Strengthen memory with self-testing.</div>
                    </div>
                    <div class="back">
                        Close your notes and test yourself on the topic.  
                        Proven to improve long-term retention.
                    </div>
                </div>
            </div>

            <div class="card" onclick="flipCard(this)">
                <div class="inner-card">
                    <div class="front g3">
                        <span class="material-symbols-outlined icon">event_repeat</span>
                        <div class="card-title">Spaced Repetition</div>
                        <div class="card-text">Review at strategic intervals.</div>
                    </div>
                    <div class="back">
                        Study the same content again after increasing gaps  
                        (Day 1 → Day 3 → Day 7 → Day 14).  
                        Excellent for long-term memory.
                    </div>
                </div>
            </div>

            <div class="card" onclick="flipCard(this)">
                <div class="inner-card">
                    <div class="front g4">
                        <span class="material-symbols-outlined icon">school</span>
                        <div class="card-title">Feynman Technique</div>
                        <div class="card-text">Learn by teaching simply.</div>
                    </div>
                    <div class="back">
                        Explain the topic as if teaching a child.  
                        If you struggle, identify gaps and simplify further.  
                        Great for deep understanding.
                    </div>
                </div>
            </div>

            <div class="card" onclick="flipCard(this)">
                <div class="inner-card">
                    <div class="front g5">
                        <span class="material-symbols-outlined icon">contract_edit</span>
                        <div class="card-title">Blurting Method</div>
                        <div class="card-text">Write everything you remember.</div>
                    </div>
                    <div class="back">
                        Read your material once. Then, without looking,  
                        “blurt” everything you remember onto paper.  
                        Compare and fill in gaps.
                    </div>
                </div>
            </div>

            <div class="card" onclick="flipCard(this)">
                <div class="inner-card">
                    <div class="front g6">
                        <span class="material-symbols-outlined icon">cognition_2</span>
                        <div class="card-title">Mind Mapping</div>
                        <div class="card-text">Visual learning through diagrams.</div>
                    </div>
                    <div class="back">
                        Create a visual diagram connecting concepts.  
                        Helps with big-picture understanding  
                        and boosts memory using visual cues.
                    </div>
                </div>
            </div>

            <div class="card" onclick="flipCard(this)">
                <div class="inner-card">
                    <div class="front g1">
                        <span class="material-symbols-outlined icon">waves</span>
                        <div class="card-title">Chunking Technique</div>
                        <div class="card-text">Group information into smaller chunks.</div>
                    </div>
                    <div class="back">
                        Break down large amounts of information into smaller, manageable units (chunks).  
                        This makes complex information easier to remember and process.
                    </div>
                </div>
            </div>

            <div class="card" onclick="flipCard(this)">
                <div class="inner-card">
                    <div class="front g2">
                        <span class="material-symbols-outlined icon">favorite</span>
                        <div class="card-title">Mindfulness & Meditation</div>
                        <div class="card-text">Improve focus and reduce stress.</div>
                    </div>
                    <div class="back">
                        Practice mindfulness or meditation for a few minutes before studying to reduce anxiety.  
                        Helps you stay calm, focused, and enhances cognitive function.
                    </div>
                </div>
            </div>

            <div class="card" onclick="flipCard(this)">
                <div class="inner-card">
                    <div class="front g3">
                        <span class="material-symbols-outlined icon">swap_horizontal_circle</span>
                        <div class="card-title">Interleaving Practice</div>
                        <div class="card-text">Switch between different topics.</div>
                    </div>
                    <div class="back">
                        Study different subjects or topics in alternating sessions.  
                        This improves problem-solving skills and helps the brain make connections between concepts.
                    </div>
                </div>
            </div>

            <div class="card" onclick="flipCard(this)">
                <div class="inner-card">
                    <div class="front g4">
                        <span class="material-symbols-outlined icon">comment</span>
                        <div class="card-title">Self-Explanation</div>
                        <div class="card-text">Explain material in your own words.</div>
                    </div>
                    <div class="back">
                        Explain the material to yourself as if you're teaching it to someone else.  
                        This process helps you internalize concepts and improve understanding.
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<script>
    function flipCard(card) {
        card.classList.toggle("flipped");
    }

    function switchPage(page) {
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });
    event.target.closest('.nav-item').classList.add('active');
    loadPage(page);
}

function loadPage(page) {
    const contentArea = document.getElementById('contentArea');
    
    const pages = {
        home: `
            <div class="page-header">
                <h1>Welcome to Edu-gram, ${currentUser.name}! 🎉</h1>
                <p>Your personalized dashboard to manage your studies effectively.</p>
            </div>
        `,
        assignments: `
            <div class="page-header">
                <h1>Assignment Tracker 📝</h1>
                <p>Manage and track your assignments</p>
            </div>
            <div class="content-placeholder">
                <i class="fas fa-tasks placeholder-icon"></i>
                <p>Assignment tracking features coming soon...</p>
            </div>
        `,
        exams: `
            <div class="page-header">
                <h1>Exam Tracker 🎓</h1>
                <p>Keep track of your upcoming exams</p>
            </div>
            <div class="content-placeholder">
                <i class="fas fa-graduation-cap placeholder-icon"></i>
                <p>Exam tracking features coming soon...</p>
            </div>
        `,
        pomodoro: `
            <div class="page-header">
                <h1>Pomodoro Timer ⏰</h1>
                <p>Boost your productivity with focused study sessions</p>
            </div>
            <div class="content-placeholder">
                <i class="fas fa-clock placeholder-icon"></i>
                <p>Pomodoro timer coming soon...</p>
            </div>
        `,
        todo: `
            <div class="page-header">
                <h1>To-Do List ✅</h1>
                <p>Organize your daily tasks</p>
            </div>
            <div class="content-placeholder">
                <i class="fas fa-list-check placeholder-icon"></i>
                <p>To-Do list features coming soon...</p>
            </div>
        `,
        profile: `
            <div class="page-header">
                <h1>Profile 👤</h1>
                <p>Manage your account settings</p>
            </div>
            <div class="profile-card">
                <div class="profile-avatar-large">
                    <i class="fas fa-user"></i>
                </div>
                <h2>${currentUser.name}</h2>
                <p>${currentUser.email}</p>
            </div>
        `,
        help: `
            <div class="page-header">
                <h1>Help & Support 🆘</h1>
                <p>Get assistance and learn how to use Edu-gram</p>
            </div>
            <div class="content-placeholder">
                <i class="fas fa-question-circle placeholder-icon"></i>
                <p>Help documentation coming soon...</p>
            </div>
        `
    };
    
    contentArea.innerHTML = pages[page] || pages.home;
}
</script>

</body>
</html>

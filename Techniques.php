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
            <a class="nav-item active" href="Techniques.php">
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

</script>

</body>
</html>

<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & Support - Edu-Gram</title>
    <link rel="stylesheet" href="help-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1>Edu-Gram</h1>
            </div>
            <nav class="sidebar-nav">
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
                <a class="nav-item" href="pomodoroindex.php">
                    <i class="fas fa-clock"></i>
                    <span>Pomodoro Timer</span>
                </a>
                <a class="nav-item" href="to-dolist.php">
                    <i class="fas fa-list-check"></i>
                    <span>To-Do List</span>
                </a>
                <a class="nav-item active" href="help.php">
                    <i class="fas fa-question-circle"></i>
                    <span>Help</span>
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>Help & Support </h1>
                <p>Get assistance and learn how to use Edu-Gram</p>
            </div>

            <div class="help-grid">
                <div class="help-card">
                    <div class="help-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <h3>Getting Started</h3>
                    <p>Welcome to Edu-Gram! Use the sidebar to navigate between different features. Start by adding your assignments and exams to stay organized.</p>
                </div>

                <div class="help-card">
                    <div class="help-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h3>Managing Assignments</h3>
                    <p>Click on "Assignments" to add and track your homework. Set due dates and mark them as complete when done.</p>
                </div>

                <div class="help-card">
                    <div class="help-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Using Pomodoro Timer</h3>
                    <p>The Pomodoro technique helps you focus. Work for 25 minutes, then take a 5-minute break. The timer helps you stay on track.</p>
                </div>

                <div class="help-card">
                    <div class="help-icon">
                        <i class="fas fa-list-check"></i>
                    </div>
                    <h3>To-Do Lists</h3>
                    <p>Organize your daily tasks with our to-do list feature. Add tasks, set deadlines, and check them off as you complete them.</p>
                </div>

                <div class="help-card">
                    <div class="help-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3>Study Techniques</h3>
                    <p>Explore proven study techniques like Active Recall, Spaced Repetition, and the Feynman Technique to improve your learning.</p>
                </div>

                <div class="help-card">
                    <div class="help-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Exam Tracking</h3>
                    <p>Keep track of upcoming exams with dates and times. Never miss an important test with our exam tracker.</p>
                </div>

                <div class="help-card">
                    <div class="help-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3>Contact Support</h3>
                    <p>Need more help? Email us at <strong>edugram31@gmail.com</strong> or visit our FAQ page for more detailed information.</p>
                </div>

                <div class="help-card">
                    <div class="help-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3>Tips & Tricks</h3>
                    <p>Make the most of Edu-Gram by exploring all features. Customize your study schedule and use the Pomodoro timer for better focus.</p>
                </div>
            </div>

            <div class="faq-section">
                <h2>Frequently Asked Questions</h2>
                <div class="faq-item">
                    <h4><i class="fas fa-chevron-right"></i> How do I add an assignment?</h4>
                    <p>Navigate to the Assignments page and click "Add Assignment". Fill in the title, subject, and due date.</p>
                </div>
                <div class="faq-item">
                    <h4><i class="fas fa-chevron-right"></i> Can I customize the Pomodoro timer?</h4>
                    <p>Yes! You can adjust work, break, and long break durations in the timer settings.</p>
                </div>
                <div class="faq-item">
                    <h4><i class="fas fa-chevron-right"></i> Is my data saved automatically?</h4>
                    <p>Yes, all your assignments, exams, and to-do items are saved automatically to your account.</p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
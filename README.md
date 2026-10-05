# 🎓 EduGram

**EduGram** is a web-based student help app that keeps everything a student needs in one place: to-dos, assignments, exam preparation, a Pomodoro focus timer, study techniques and an AI study assistant.

Built with **HTML, CSS and JavaScript** on the frontend and **PHP + MySQL** on the backend.

---

## ✨ Features

| Feature | Description |
|---|---|
| 🔐 **Authentication** | Sign up / log in with email and password, or with **Google Sign-In** |
| 🔑 **Password reset** | Forgot-password flow with a one-time password (OTP) sent by email (PHPMailer) |
| 📝 **Questionnaire & profile** | Onboarding questionnaire (education, major, etc.) and an editable profile dashboard |
| ✅ **To-Do list** | Add, view and delete tasks with due dates |
| 📚 **Assignments** | Track assignments by status: *Assigned*, *Overdue* and *Turned In* |
| 📋 **Exam preparation** | Add, edit and list upcoming exams by subject |
| ⏰ **Pomodoro timer** | Focus timer with alert sound, study-time logging and ambient focus music (café, rain, forest, lo-fi, classical piano, Japanese garden, typing) |
| 💡 **Study techniques** | A guide to effective study methods |
| 🤖 **AI chatbot** | In-app study assistant powered by the Groq API |
| ❓ **Help & Support** | FAQ page, plus Privacy Policy and Terms of Service pages |

---

## 🛠️ Tech Stack

- **Frontend:** HTML5, CSS3, vanilla JavaScript
- **Backend:** PHP (PDO / MySQLi)
- **Database:** MySQL
- **Email:** [PHPMailer](https://github.com/PHPMailer/PHPMailer) over Gmail SMTP
- **Auth:** PHP sessions + Google OAuth 2.0
- **AI:** [Groq API](https://console.groq.com/) (OpenAI-compatible chat completions)

---

## 📁 Project Structure

```
EduGram/
├── index.html                # Landing page
├── login.php / signup.php    # Authentication
├── google-callback.php       # Google OAuth callback
├── forgot-password.php       # Request OTP
├── reset-password.php        # Verify OTP
├── set-password.php          # Set a new password
├── questionnaire.html        # Onboarding questionnaire
├── profile.php               # User dashboard
├── to-dolist.php             # To-do list (addtask / fetchtask / deletetask)
├── Assignment.php            # Assignment tracker
├── exam_list.php             # Exams (exam_form.php / exam_edit.php)
├── pomodoroindex.php         # Pomodoro timer (pomodoro_backend.php)
├── Techniques.php            # Study techniques
├── help.php                  # Help & support
├── aichatbot.js / .css       # AI chatbot widget
├── api/                      # JSON endpoints, DB + email + Google config
├── PHPMailer/                # Email library
├── focus music/              # Ambient audio for the timer
├── database/                 # Database files
└── to_dolist.sql             # Base SQL schema
```

---

## 🚀 Getting Started

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) (or any stack with **PHP 7.4+**, **Apache** and **MySQL/MariaDB**)
- A modern web browser

### Installation

1. **Clone the repository** into your web server's root folder (e.g. `htdocs` for XAMPP):
   ```bash
   git clone https://github.com/sangya23/EduGram.git
   cd EduGram
   ```
   > The project expects to be served from `http://localhost/edugram/`, so name the folder `edugram`.

2. **Start Apache and MySQL** from the XAMPP control panel.

3. **Create the database.** Open phpMyAdmin (`http://localhost/phpmyadmin`) and either import `to_dolist.sql`, or create a database named `edugram` manually:
   ```sql
   CREATE DATABASE IF NOT EXISTS edugram;
   ```

4. **Configure database access** in `api/db.php` (defaults are `localhost`, user `root`, empty password, database `edugram`).

5. **Configure the optional services** (see below).

6. **Open the app:** <http://localhost/edugram/>

---

## ⚙️ Configuration



| Service | File | What to set |
|---|---|---|
| **Database** | `api/db.php` | `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME` |
| **Email (OTP)** | `api/email_config.php` | Your Gmail address and a [Gmail App Password](https://support.google.com/accounts/answer/185833) |
| **Google Sign-In** | `api/google_config.php` | Client ID, client secret, and redirect URI from the [Google Cloud Console](https://console.cloud.google.com/apis/credentials) (default redirect: `http://localhost/edugram/google-callback.php`) |
| **AI chatbot** | `aichatbot.js` | Your own [Groq API key](https://console.groq.com/keys) |

The core features (to-dos, assignments, exams, Pomodoro) work without the email, Google and AI configuration. Those only power password reset, Google login and the chatbot.

---

## 🗄️ Database Overview

The app uses the following tables:

`users` · `tasks` · `assignments` · `exams` · `subjects` · `study_logs` · `study_minutes` · `user_questionnaire` · `password_reset_tokens`



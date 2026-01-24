CREATE DATABASE IF NOT EXISTS EDUGRAM;
USE EDUGRAM;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    dob DATE NULL,
    education VARCHAR(100) NULL,
    major VARCHAR(100) NULL
);

CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    task VARCHAR(255) NOT NULL,
    due DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Dummy user for testing
INSERT IGNORE INTO users (id, full_name, email) VALUES (1, 'Test User', 'test@example.com');
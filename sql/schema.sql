-- Nairobi Securities Exchange (NSE) MKU Club Platform Schema

CREATE DATABASE IF NOT EXISTS nse_club_db;
USE nse_club_db;

-- 1. Users Table (Membership Portal & Three-Tier Access Control)
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    user_role ENUM('USER', 'MEMBER', 'ADMIN') DEFAULT 'USER',
    learning_tier ENUM('BEGINNER', 'INTERMEDIATE', 'ADVANCED', 'GRADUATE') DEFAULT 'BEGINNER',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Virtual Portfolio Tracker Table
CREATE TABLE IF NOT EXISTS portfolio_trades (
    trade_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    ticker_symbol VARCHAR(10) NOT NULL,
    quantity INT NOT NULL,
    trade_type ENUM('BUY', 'SELL') DEFAULT 'BUY',
    trade_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
);

-- 3. Resource Centre Table (Digital Library)
CREATE TABLE IF NOT EXISTS resources (
    resource_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    category ENUM('WEBINAR', 'CONSTITUTION', 'FINANCIALS', 'MINUTES', 'BOOK') NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Learning Hub - Quizzes Table
CREATE TABLE IF NOT EXISTS quizzes (
    quiz_id INT AUTO_INCREMENT PRIMARY KEY,
    level ENUM('BEGINNER', 'INTERMEDIATE', 'ADVANCED') NOT NULL,
    question_text TEXT NOT NULL,
    option_a VARCHAR(255) NOT NULL,
    option_b VARCHAR(255) NOT NULL,
    option_c VARCHAR(255) NOT NULL,
    option_d VARCHAR(255) NOT NULL,
    correct_option ENUM('A', 'B', 'C', 'D') NOT NULL
);
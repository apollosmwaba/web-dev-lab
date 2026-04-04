-- Student Suggestion WebApp Database Schema

CREATE DATABASE IF NOT EXISTS student_suggestions CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE student_suggestions;

-- Users table (Admin/Staff)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('president', 'committee_member', 'person_incharge') NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Suggestions table
CREATE TABLE suggestions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_code VARCHAR(20) UNIQUE NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(100),
    urgency_level ENUM('low', 'medium', 'high') DEFAULT 'medium',
    status ENUM('new', 'in_review', 'resolved', 'actioned') DEFAULT 'new',
    is_anonymous BOOLEAN DEFAULT TRUE,
    student_name VARCHAR(100),
    student_email VARCHAR(100),
    file_path VARCHAR(255),
    file_original_name VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    resolved_at TIMESTAMP NULL,
    resolved_by INT NULL,
    FOREIGN KEY (resolved_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Admin responses/replies table
CREATE TABLE admin_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    suggestion_id INT NOT NULL,
    user_id INT NOT NULL,
    response_text TEXT NOT NULL,
    is_anonymous BOOLEAN DEFAULT FALSE,
    is_internal_note BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (suggestion_id) REFERENCES suggestions(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Response templates table
CREATE TABLE response_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    template_name VARCHAR(100) NOT NULL,
    template_content TEXT NOT NULL,
    category VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Draft submissions table
CREATE TABLE drafts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(100) NOT NULL,
    title VARCHAR(200),
    description TEXT,
    category VARCHAR(100),
    urgency_level ENUM('low', 'medium', 'high') DEFAULT 'medium',
    student_name VARCHAR(100),
    student_email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, email, password_hash, full_name, role) VALUES
('admin', 'admin@university.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'president');

-- Insert sample response templates
INSERT INTO response_templates (template_name, template_content, category) VALUES
('Acknowledgment', 'Thank you for your suggestion. We have received it and will review it shortly.', 'General'),
('Under Review', 'Your suggestion is currently under review by our committee. We will update you soon.', 'General'),
('Resolved', 'Your suggestion has been addressed. Thank you for helping us improve.', 'General'),
('Need More Info', 'We need more information about your suggestion. Please provide additional details.', 'Follow-up');

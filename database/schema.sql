
DROP TABLE IF EXISTS audit_logs;
DROP TABLE IF EXISTS procurement_resources;
DROP TABLE IF EXISTS training_participants;
DROP TABLE IF EXISTS training_records;
DROP TABLE IF EXISTS training_needs;
DROP TABLE IF EXISTS assessments;
DROP TABLE IF EXISTS skills;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS staff;
DROP TABLE IF EXISTS departments;

-- Departments Table
CREATE TABLE departments (
    department_id INT AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Staff Table
CREATE TABLE staff (
    staff_id INT AUTO_INCREMENT PRIMARY KEY,
    staff_no VARCHAR(50) NOT NULL UNIQUE,
    full_name VARCHAR(150) NOT NULL,
    department_id INT NOT NULL,
    position VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(department_id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Users Table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'hr', 'ict', 'procurement', 'staff') NOT NULL,
    staff_id INT DEFAULT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ICT Skills Catalogue
CREATE TABLE skills (
    skill_id INT AUTO_INCREMENT PRIMARY KEY,
    skill_name VARCHAR(100) NOT NULL,
    description TEXT,
    required_level TINYINT NOT NULL COMMENT '1=Basic, 2=Intermediate, 3=Advanced'
) ENGINE=InnoDB;

-- Skills Assessments Table
CREATE TABLE assessments (
    assessment_id INT AUTO_INCREMENT PRIMARY KEY,
    staff_id INT NOT NULL,
    skill_id INT NOT NULL,
    current_level TINYINT NOT NULL COMMENT '1=Basic, 2=Intermediate, 3=Advanced',
    assessed_by VARCHAR(100) NOT NULL,
    assessment_date DATE NOT NULL,
    notes TEXT,
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(skill_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Training Needs Table
CREATE TABLE training_needs (
    need_id INT AUTO_INCREMENT PRIMARY KEY,
    staff_id INT NOT NULL,
    skill_id INT DEFAULT NULL,
    training_title VARCHAR(200) NOT NULL,
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    status ENUM('pending', 'scheduled', 'completed', 'cancelled') DEFAULT 'pending',
    recommendation TEXT,
    created_by VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(skill_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Procurement Resources Table
CREATE TABLE procurement_resources (
    resource_id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(150) NOT NULL,
    category VARCHAR(100) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    estimated_cost DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    status ENUM('requested', 'approved', 'procured', 'delivered') DEFAULT 'requested',
    requested_by VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Seed Reference Departments
INSERT INTO departments (department_id, department_name) VALUES 
(1, 'ICT Department'), 
(2, 'Human Resource'), 
(3, 'Academics & Pedagogy'), 
(4, 'Procurement & Supplies'), 
(5, 'Administration');

-- Seed Skills
INSERT INTO skills (skill_id, skill_name, description, required_level) VALUES 
(1, 'Spreadsheet Management', 'Data analysis, formulas, financial formatting, and pivot tables', 2),
(2, 'Cybersecurity Awareness', 'Phishing awareness, password hygiene, and data protection', 2),
(3, 'LMS & E-Learning Portal', 'Moodle portal management, course uploading, and student grading', 3),
(4, 'Network Troubleshooting', 'Basic LAN diagnostics, Wi-Fi setup, and IP configuration', 2);

-- Seed Staff Records (Admin, HR, ICT, Procurement, plus Users)
INSERT INTO staff (staff_id, staff_no, full_name, department_id, position, email, phone) VALUES 
(1, 'EMP000', 'System Administrator', 1, 'ICT Admin', 'admin@kstvet.ac.ke', '0700000000'),
(2, 'EMP001', 'HR Officer', 2, 'Head of Human Resources', 'hr@kstvet.ac.ke', '0711000001'),
(3, 'EMP002', 'ICT Officer', 1, 'Senior ICT Officer', 'ict@kstvet.ac.ke', '0711000002'),
(4, 'EMP003', 'Procurement Officer', 4, 'Procurement Manager', 'procurement@kstvet.ac.ke', '0711000003'),
(5, 'EMP004', 'Staff Trainer 01', 3, 'Electrical Engineering Trainer', 'trainer01@kstvet.ac.ke', '0722000001'),
(6, 'EMP005', 'Staff Trainer 02', 3, 'Mechanical Engineering Trainer', 'trainer02@kstvet.ac.ke', '0722000002');

-- Seed Users (Passwords hashed with bcrypt)
INSERT INTO users (username, password_hash, role, staff_id, status) VALUES 
('admin', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'admin', 1, 'active'),
('hr_user', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'hr', 2, 'active'),
('ict_user', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'ict', 3, 'active'),
('procurement_user', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'procurement', 4, 'active'),
('user01', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'staff', 5, 'active'),
('user02', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'staff', 6, 'active');

-- Seed Initial Procurement Resources
INSERT INTO procurement_resources (item_name, category, quantity, estimated_cost, status, requested_by) VALUES
('Computer Lab Projector & Screen', 'ICT Equipment', 2, 85000.00, 'approved', 'ict_user'),
('Advanced Excel & Data Analytics Manuals', 'Training Materials', 25, 37500.00, 'procured', 'hr_user');

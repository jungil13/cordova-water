-- Cordova Water System Inc. - Database Schema
-- Run this file to set up your MySQL database

CREATE DATABASE IF NOT EXISTS cordova_water;
USE cordova_water;

-- Users table (OAuth + email/password)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    picture VARCHAR(500) DEFAULT NULL,
    provider ENUM('google', 'facebook', 'local') DEFAULT 'local',
    provider_id VARCHAR(255) DEFAULT NULL,
    role ENUM('user', 'staff', 'admin') DEFAULT 'user',
    address VARCHAR(500) DEFAULT NULL,
    phone VARCHAR(50) DEFAULT NULL,
    password_hash VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_provider (provider, provider_id),
    INDEX idx_email (email)
);

-- Service requests
CREATE TABLE IF NOT EXISTS service_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    fullname VARCHAR(255) NOT NULL,
    address VARCHAR(500) NOT NULL,
    contact VARCHAR(50) NOT NULL,
    service_type VARCHAR(100) NOT NULL,
    details TEXT DEFAULT NULL,
    status ENUM('pending', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    assigned_to INT NULL,
    admin_notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_created (created_at)
);

-- Billing records (water consumption & charges)
CREATE TABLE IF NOT EXISTS billing (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    account_number VARCHAR(50) DEFAULT NULL,
    period_start DATE NOT NULL,
    period_end DATE NOT NULL,
    consumption_cbm DECIMAL(10,2) NOT NULL DEFAULT 0,
    amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    status ENUM('unpaid', 'paid', 'overdue') DEFAULT 'unpaid',
    due_date DATE DEFAULT NULL,
    paid_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_status (user_id, status),
    INDEX idx_due (due_date)
);

-- Payments
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    billing_id INT NULL,
    amount DECIMAL(12,2) NOT NULL,
    method ENUM('gcash', 'palawan', 'cash', 'bank') DEFAULT 'cash',
    reference VARCHAR(255) DEFAULT NULL,
    proof_of_payment VARCHAR(500) DEFAULT NULL,
    status ENUM('pending', 'confirmed', 'failed') DEFAULT 'pending',
    notes TEXT DEFAULT NULL,
    confirmed_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (billing_id) REFERENCES billing(id) ON DELETE SET NULL,
    FOREIGN KEY (confirmed_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_created (created_at)
);

-- Water rates (reference)
CREATE TABLE IF NOT EXISTS water_rates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    min_cbm INT NOT NULL,
    max_cbm INT NOT NULL,
    rate_per_cbm DECIMAL(10,2) NOT NULL,
    description VARCHAR(255) DEFAULT NULL
);

-- Insert default water rates
INSERT INTO water_rates (min_cbm, max_cbm, rate_per_cbm, description) VALUES
(0, 5, 220, 'Minimum charge (0-5 m³)'),
(6, 10, 48, '6-10 m³ per cubic meter'),
(11, 20, 54, '11-20 m³ per cubic meter'),
(21, 30, 65, '21-30 m³ per cubic meter'),
(31, 9999, 92, '31+ m³ per cubic meter');

-- Run setup.php to create admin user (admin@cordovawater.com / admin123)

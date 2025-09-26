-- Database setup script for JoJo Smart Parking System
-- Run this script to create the required database and tables

-- Create database
CREATE DATABASE IF NOT EXISTS jojo;
USE jojo;

-- Create parking_slots table
CREATE TABLE IF NOT EXISTS parking_slots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slot_number INT NOT NULL UNIQUE,
    status ENUM('empty', 'occupied') NOT NULL DEFAULT 'empty',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert initial parking slots
INSERT INTO parking_slots (slot_number, status) VALUES 
(1, 'empty'),
(2, 'empty'),
(3, 'empty')
ON DUPLICATE KEY UPDATE status = VALUES(status);

-- Create system_status table for gate status
CREATE TABLE IF NOT EXISTS system_status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entrance_gate ENUM('open', 'closed') NOT NULL DEFAULT 'closed',
    exit_gate ENUM('open', 'closed') NOT NULL DEFAULT 'closed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert initial system status
INSERT INTO system_status (entrance_gate, exit_gate) VALUES ('closed', 'closed');

-- Create access_logs table
CREATE TABLE IF NOT EXISTS access_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    card_uid VARCHAR(50) NOT NULL,
    action VARCHAR(50) NOT NULL,
    status VARCHAR(50) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create indexes for better performance
CREATE INDEX idx_access_logs_timestamp ON access_logs(timestamp);
CREATE INDEX idx_access_logs_card_uid ON access_logs(card_uid);

-- Show tables
SHOW TABLES;

-- Show table structures
DESCRIBE parking_slots;
DESCRIBE system_status;
DESCRIBE access_logs;

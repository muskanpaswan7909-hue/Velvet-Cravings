-- Database Setup for Velvet Cravings
CREATE DATABASE IF NOT EXISTS `velvet_cravings`;
USE `velvet_cravings`;

-- TABLE FOR CUSTOMERS (LOGIN/REGISTER)
CREATE TABLE IF NOT EXISTS `customers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `phone` VARCHAR(15) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- TABLE FOR ORDERS
CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `product` VARCHAR(255) NOT NULL,
    `qty` INT NOT NULL DEFAULT 1,
    `address` TEXT NOT NULL,
    `status` VARCHAR(50) DEFAULT 'Pending',
    `order_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- TABLE FOR ADMIN
CREATE TABLE IF NOT EXISTS `admin` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL
);

-- Default Admin Credential
INSERT IGNORE INTO `admin` (`username`, `password`) VALUES ('admin', 'admin123');

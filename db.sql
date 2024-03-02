-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Server version: 10.4.6-MariaDB
-- PHP Version: 8.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Table structure for table `users`
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

-- AUTO_INCREMENT for table `users`
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

-- Add new columns to users table and drop name column
ALTER TABLE `users`
ADD COLUMN `first_name` VARCHAR(255) NOT NULL AFTER `password`,
ADD COLUMN `last_name` VARCHAR(255) NOT NULL AFTER `first_name`,
ADD COLUMN `email` VARCHAR(255) NOT NULL AFTER `last_name`;

-- Create Events Table
CREATE TABLE events (
    event_id INT PRIMARY KEY AUTO_INCREMENT,
    event_name VARCHAR(255) NOT NULL,
    max_attendees INT NOT NULL,
    ticket_price_vip DECIMAL(10, 2) NOT NULL,
    ticket_price_regular DECIMAL(10, 2) NOT NULL,
    event_date DATE NOT NULL
);

-- Create Bookings Table
CREATE TABLE Bookings (
    booking_id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT,
    user_id INT,
    ticket_type ENUM('VIP', 'Regular') NOT NULL,
    num_tickets INT NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(event_id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

ALTER TABLE users
DROP COLUMN name;

-- Modify Events Table
ALTER TABLE events
ADD COLUMN event_date DATE NOT NULL,
ADD COLUMN event_time TIME NOT NULL,
ADD COLUMN event_location VARCHAR(255) NOT NULL;

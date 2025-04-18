-- Database creation script for UgandaMove
-- File: database.sql

-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS uganda_move;
USE uganda_move;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    remember_token VARCHAR(100) NULL
);

-- Transport types
CREATE TABLE IF NOT EXISTS transport_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    icon VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cities/Locations
CREATE TABLE IF NOT EXISTS cities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    latitude DECIMAL(10, 8) NULL,
    longitude DECIMAL(11, 8) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Companies
CREATE TABLE IF NOT EXISTS companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    logo VARCHAR(255) NULL,
    contact_email VARCHAR(100) NULL,
    contact_phone VARCHAR(20) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Routes
CREATE TABLE IF NOT EXISTS routes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transport_type_id INT NOT NULL,
    company_id INT NOT NULL,
    from_city_id INT NOT NULL,
    to_city_id INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    departure_time TIME NOT NULL,
    arrival_time TIME NOT NULL,
    duration VARCHAR(50) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (transport_type_id) REFERENCES transport_types(id),
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (from_city_id) REFERENCES cities(id),
    FOREIGN KEY (to_city_id) REFERENCES cities(id)
);

-- Schedules
CREATE TABLE IF NOT EXISTS schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    route_id INT NOT NULL,
    date DATE NOT NULL,
    total_seats INT NOT NULL,
    available_seats INT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (route_id) REFERENCES routes(id)
);

-- Bookings
CREATE TABLE IF NOT EXISTS bookings (
    id VARCHAR(20) PRIMARY KEY,  -- Custom booking ID like UM1234
    user_id INT NOT NULL,
    schedule_id INT NOT NULL,
    passengers INT NOT NULL DEFAULT 1,
    total_price DECIMAL(10, 2) NOT NULL,
    payment_method ENUM('mtn', 'airtel', 'card') NOT NULL,
    payment_phone VARCHAR(20) NULL,
    payment_status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    booking_status ENUM('confirmed', 'cancelled', 'completed') DEFAULT 'confirmed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (schedule_id) REFERENCES schedules(id)
);

-- Journey tracking
CREATE TABLE IF NOT EXISTS journey_tracking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id VARCHAR(20) NOT NULL,
    current_latitude DECIMAL(10, 8) NULL,
    current_longitude DECIMAL(11, 8) NULL,
    progress_percent INT NOT NULL DEFAULT 0,
    eta TIME NULL,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id)
);

-- Journey updates
CREATE TABLE IF NOT EXISTS journey_updates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    journey_id INT NOT NULL,
    update_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    update_info TEXT NOT NULL,
    FOREIGN KEY (journey_id) REFERENCES journey_tracking(id)
);

-- Populate transport types
INSERT INTO transport_types (name, icon) VALUES 
('bus', 'fas fa-bus'),
('train', 'fas fa-train'),
('ferry', 'fas fa-ship');

-- Populate cities
INSERT INTO cities (name) VALUES 
('kampala'),
('entebbe'),
('jinja'),
('mbale'),
('mbarara'),
('gulu');

-- Populate companies
INSERT INTO companies (name) VALUES 
('Link Bus'),
('Gateway Bus'),
('YY Coaches'),
('Uganda Railways'),
('Post Bus');

-- Sample routes (you would need to get the IDs from the previous inserts)
-- This is simplified for the example - in production you'd use variables or a proper script
INSERT INTO routes (transport_type_id, company_id, from_city_id, to_city_id, price, departure_time, arrival_time, duration) VALUES 
(1, 1, 1, 2, 10000, '10:30:00', '11:30:00', '1 hour'),
(1, 2, 1, 2, 12000, '11:00:00', '12:00:00', '1 hour'),
(2, 4, 1, 3, 15000, '09:00:00', '11:00:00', '2 hours'),
(1, 3, 1, 5, 25000, '08:00:00', '12:00:00', '4 hours'),
(1, 2, 1, 6, 35000, '07:00:00', '13:00:00', '6 hours');
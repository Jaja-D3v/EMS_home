CREATE DATABASE ems_db;

USE ems_db;


CREATE TABLE role_tbl (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name ENUM('admin', 'user', 'inspector') NOT NULL UNIQUE
);

CREATE TABLE users_tbl (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_code VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (role_id) REFERENCES role_tbl(role_id)
);


CREATE TABLE fire_extinguishers_tbl (
    extinguisher_id INT AUTO_INCREMENT PRIMARY KEY,
    extinguisher_code VARCHAR(50) NOT NULL UNIQUE,
    type VARCHAR(100) NOT NULL,
    capacity VARCHAR(50) NOT NULL,
    location VARCHAR(255) NOT NULL,
    date_acquired DATE,
    expiration_date DATE NOT NULL,
    status ENUM('active', 'expired', 'maintenance') NOT NULL DEFAULT 'active',
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


INSERT INTO fire_extinguishers_tbl
(extinguisher_code, type, capacity, location, date_acquired, expiration_date, status)
VALUES

-- DUMMY DATA 
('E01-001', 'ABC Dry Chemical', '10 lbs', 'Building A - 1st Floor', '2023-01-15', '2027-01-15', 'active'),
('E01-002', 'ABC Dry Chemical', '10 lbs', 'Building A - 1st Floor', '2023-03-10', '2027-03-10', 'active'),
('E01-003', 'CO2', '5 lbs', 'Building A - 2nd Floor', '2022-06-20', '2026-06-20', 'active'),
('E01-004', 'CO2', '10 lbs', 'Building A - 2nd Floor', '2024-02-05', '2028-02-05', 'active'),
('E01-005', 'ABC Dry Chemical', '10 lbs', 'Building A - 3rd Floor', '2023-08-12', '2027-08-12', 'active'),

('E02-001', 'ABC Dry Chemical', '20 lbs', 'Building B - 1st Floor', '2022-11-18', '2026-11-18', 'active'),
('E02-002', 'CO2', '5 lbs', 'Building B - 1st Floor', '2024-01-25', '2028-01-25', 'active'),
('E02-003', 'Foam', '9 L', 'Building B - 2nd Floor', '2023-05-14', '2027-05-14', 'active'),
('E02-004', 'ABC Dry Chemical', '10 lbs', 'Building B - 2nd Floor', '2022-09-30', '2026-09-30', 'active'),
('E02-005', 'CO2', '10 lbs', 'Building B - 3rd Floor', '2021-12-10', '2025-12-10', 'maintenance'),

('E03-001', 'ABC Dry Chemical', '10 lbs', 'Building C - Lobby', '2023-02-20', '2027-02-20', 'active'),
('E03-002', 'ABC Dry Chemical', '20 lbs', 'Building C - 1st Floor', '2024-04-15', '2028-04-15', 'active'),
('E03-003', 'CO2', '5 lbs', 'Building C - 1st Floor', '2022-07-08', '2026-07-08', 'active'),
('E03-004', 'Foam', '9 L', 'Building C - 2nd Floor', '2023-10-05', '2027-10-05', 'active'),
('E03-005', 'ABC Dry Chemical', '10 lbs', 'Building C - 3rd Floor', '2021-08-22', '2025-08-22', 'expired'),

('E04-001', 'CO2', '10 lbs', 'Warehouse - Main Entrance', '2024-03-18', '2028-03-18', 'active'),
('E04-002', 'ABC Dry Chemical', '20 lbs', 'Warehouse - Storage Area', '2022-10-12', '2026-10-12', 'active'),
('E04-003', 'ABC Dry Chemical', '10 lbs', 'Warehouse - Loading Area', '2023-06-25', '2027-06-25', 'active'),
('E04-004', 'Foam', '9 L', 'Parking Area', '2022-05-17', '2026-05-17', 'active'),
('E04-005', 'CO2', '5 lbs', 'Security Office', '2024-06-30', '2028-06-30', 'active');
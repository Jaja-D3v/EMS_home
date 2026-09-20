CREATE DATABASE ems_db;

USE ems_db;

CREATE TABLE users_tbl (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_code VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (role_id) REFERENCES role_tbl(role_id)
);


CREATE TABLE role_tbl (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name ENUM('admin', 'user', 'inspector') NOT NULL UNIQUE
);
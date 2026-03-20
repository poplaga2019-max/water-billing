CREATE DATABASE water_db;
USE water_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(50),
    role VARCHAR(20)
);

INSERT INTO users (username, password, role) VALUES
('admin', '1234', 'admin'),
('staff', '1234', 'staff');

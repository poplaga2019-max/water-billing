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

CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    address TEXT,
    meter_no VARCHAR(50),
    last_unit INT DEFAULT 0
);

INSERT INTO customers (name, address, meter_no, last_unit) VALUES
('สมชาย', 'บ้านเลขที่ 12', 'M001', 120),
('สมหญิง', 'บ้านเลขที่ 15', 'M002', 90);
CREATE TABLE bills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    old_unit INT,
    new_unit INT,
    used_unit INT,
    amount INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

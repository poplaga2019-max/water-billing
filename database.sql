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
CREATE TABLE water_rates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    min_unit INT,
    max_unit INT,
    price_per_unit INT
);

INSERT INTO water_rates (min_unit, max_unit, price_per_unit) VALUES
(1, 50, 5),
(51, 100, 7),
(101, 9999, 10);
ALTER TABLE bills ADD status VARCHAR(20) DEFAULT 'unpaid';
ALTER TABLE customers ADD username VARCHAR(50), ADD password VARCHAR(50);

UPDATE customers SET username='home1', password='1234' WHERE id=1;
UPDATE customers SET username='home2', password='1234' WHERE id=2;
ALTER TABLE bills ADD slip VARCHAR(255);

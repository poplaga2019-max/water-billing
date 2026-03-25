CREATE DATABASE water_db;
USE water_db;

-- ================= USERS =================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(50),
    role ENUM('admin','staff')
);

INSERT INTO users (username,password,role) VALUES
('admin','1234','admin'),
('staff','1234','staff');


-- ================= CUSTOMERS =================
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    address TEXT,
    meter_no VARCHAR(50),
    last_unit INT DEFAULT 0,

    username VARCHAR(50),
    password VARCHAR(50),

    lat VARCHAR(50),
    lng VARCHAR(50)
);

INSERT INTO customers (name,address,meter_no,last_unit,username,password,lat,lng) VALUES
('สมชาย','บ้านเลขที่ 12','M001',120,'home1','1234','16.812345','100.256789'),
('สมหญิง','บ้านเลขที่ 15','M002',90,'home2','1234','16.813000','100.257000');


-- ================= WATER RATE =================
CREATE TABLE water_rates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    min_unit INT,
    max_unit INT,
    price_per_unit INT
);

INSERT INTO water_rates (min_unit,max_unit,price_per_unit) VALUES
(1,50,5),
(51,100,7),
(101,9999,10);


-- ================= BILLS =================
CREATE TABLE bills (
    id INT AUTO_INCREMENT PRIMARY KEY,

    customer_id INT,
    old_unit INT,
    new_unit INT,
    used_unit INT,
    amount INT,

    status ENUM('pending','verify','paid') DEFAULT 'pending',
    slip VARCHAR(255),

    bill_date DATE,
    billing_cycle VARCHAR(10),
    is_locked TINYINT DEFAULT 0,

    staff_id INT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- ================= SETTINGS =================
CREATE TABLE settings (
    name VARCHAR(50) PRIMARY KEY,
    value TEXT
);

INSERT INTO settings (name,value) VALUES
('site_name','ระบบประปาหมู่บ้าน'),
('logo','uploads/logo.png'),
('promptpay','0801234567'),

('telegram_token',''),
('telegram_meter',''),
('telegram_payment',''),
('telegram_chat_finance',''),

('cycle_day','1');


-- ================= OPTIONAL (LOG KPI) =================
CREATE TABLE logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

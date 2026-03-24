CREATE DATABASE water_db;
USE water_db;

-- 👤 ผู้ใช้งาน
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(50),
    role VARCHAR(20)
);

INSERT INTO users (username, password, role) VALUES
('admin', '1234', 'admin'),
('staff', '1234', 'staff');


-- 👨‍👩‍👧‍👦 ลูกบ้าน + GPS
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

INSERT INTO customers (name, address, meter_no, last_unit, username, password, lat, lng) VALUES
('สมชาย', 'บ้านเลขที่ 12', 'M001', 120, 'home1', '1234', '16.812345', '100.256789'),
('สมหญิง', 'บ้านเลขที่ 15', 'M002', 90, 'home2', '1234', '16.813000', '100.257000');


-- 💧 ค่าน้ำขั้นบันได
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


-- 🧾 บิล (เวอร์ชันองค์กร)
CREATE TABLE bills (
    id INT AUTO_INCREMENT PRIMARY KEY,

    customer_id INT,
    old_unit INT,
    new_unit INT,
    used_unit INT,
    amount INT,

    status VARCHAR(20) DEFAULT 'unpaid',
    slip VARCHAR(255),

    bill_date DATE,                -- วันที่บิล (ล็อครอบ)
    billing_cycle VARCHAR(10),     -- YYYY-MM
    is_locked TINYINT DEFAULT 0,   -- ปิดรอบ

    staff_id INT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- ⚙️ ตั้งค่า
CREATE TABLE settings (
    id INT PRIMARY KEY,

    site_name VARCHAR(255),
    logo VARCHAR(255),

    telegram_token VARCHAR(255),
    telegram_meter VARCHAR(50),
    telegram_payment VARCHAR(50),

    cycle_day INT DEFAULT 1
);

INSERT INTO settings (
    id, site_name, logo,
    telegram_token, telegram_meter, telegram_payment,
    cycle_day
) VALUES (
    1,
    'ระบบประปาหมู่บ้าน',
    '',
    '',
    '',
    '',
    1
);

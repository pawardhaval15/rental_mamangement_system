CREATE DATABASE rent_management;

USE rent_management;

-- Admin Table
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Tenant Details Table
CREATE TABLE tenants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tenant_name VARCHAR(100),
    address TEXT,
    mobile_no VARCHAR(15),
    email VARCHAR(100),
    property_name VARCHAR(100),
    property_type VARCHAR(100),
    property_location TEXT,
    property_owners TEXT,
    monthly_rent INT(100),
    deposit INT(100),
    rent_status VARCHAR(100),
    amount_paid INT(100),
    amount_pending INT(100),
    payment_mode VARCHAR(100),
    transaction_details TEXT,
    bank_name VARCHAR(100),
    property_area INT(100),
    electricity_provider VARCHAR(100),
    rent_pay_date DATE,
    cma_charges DECIMAL(10, 2),
    gst_percentage DECIMAL(5, 2),
    rent_increase_yearly DECIMAL(5, 2)
);

-- Insert Admin User
INSERT INTO admin (username, password) VALUES
('admin', PASSWORD('admin123'));

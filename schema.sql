CREATE DATABASE IF NOT EXISTS rent_management;

USE rent_management;

-- Admin Table
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Tenant Details Table
CREATE TABLE IF NOT EXISTS tenants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code TEXT,
    site_name VARCHAR(100),

    property_type VARCHAR(100),
    property_location TEXT,
    property_address TEXT,
    pincode INT,
    property_owners TEXT,
    owners_details VARCHAR(200),
    tenant_name VARCHAR(100),
    address TEXT,
    mobile_no VARCHAR(15),
    email VARCHAR(100),
    monthly_rent INT,
    deposit INT,
    maintenance_charges INT,
    online_paid INT,
    cash_paid INT,
    muncipal_tax DECIMAL(5, 2),
    cma_charges DECIMAL(5, 2),
    electricity_charges INT,
    electricity_provider VARCHAR(100),
    water_charges DECIMAL(5, 2),
    bank_no VARCHAR(100),  -- Change to VARCHAR to accommodate alphanumeric values
    bank_name VARCHAR(100),
    bank_ifsc VARCHAR(100),
    bank_details VARCHAR(100),
    agreement_date DATE,
    agreement_expiring DATE,
    fitout_time DATE,
    rent_start_date DATE
);

ALTER TABLE tenants
ADD COLUMN increased_rent DECIMAL(10, 2),
ADD COLUMN agreement_years INT,
ADD COLUMN yearly_escalation_percentage DECIMAL(5, 2);



-- Insert Admin User
INSERT INTO admin (username, password) VALUES
('admin', PASSWORD('admin123'));

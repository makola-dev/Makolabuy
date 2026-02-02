-- Shipping System Migration for Makola Marketplace
-- Adds comprehensive shipping functionality

-- Add shipping fields to orders table (conditional to avoid duplicate column errors)
SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'orders' AND column_name = 'shipping_method') = 0,
    'ALTER TABLE orders ADD COLUMN shipping_method VARCHAR(50) DEFAULT \'standard\' AFTER shipping_address;',
    'SELECT "Column shipping_method already exists" as message;'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'orders' AND column_name = 'shipping_cost') = 0,
    'ALTER TABLE orders ADD COLUMN shipping_cost DECIMAL(10,2) DEFAULT 0.00 AFTER shipping_method;',
    'SELECT "Column shipping_cost already exists" as message;'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'orders' AND column_name = 'tracking_number') = 0,
    'ALTER TABLE orders ADD COLUMN tracking_number VARCHAR(100) AFTER shipping_cost;',
    'SELECT "Column tracking_number already exists" as message;'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'orders' AND column_name = 'shipping_carrier') = 0,
    'ALTER TABLE orders ADD COLUMN shipping_carrier VARCHAR(50) AFTER tracking_number;',
    'SELECT "Column shipping_carrier already exists" as message;'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'orders' AND column_name = 'estimated_delivery') = 0,
    'ALTER TABLE orders ADD COLUMN estimated_delivery DATE AFTER shipping_carrier;',
    'SELECT "Column estimated_delivery already exists" as message;'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'orders' AND column_name = 'actual_delivery') = 0,
    'ALTER TABLE orders ADD COLUMN actual_delivery TIMESTAMP NULL AFTER estimated_delivery;',
    'SELECT "Column actual_delivery already exists" as message;'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'orders' AND column_name = 'delivery_confirmed') = 0,
    'ALTER TABLE orders ADD COLUMN delivery_confirmed TINYINT(1) DEFAULT 0 AFTER actual_delivery;',
    'SELECT "Column delivery_confirmed already exists" as message;'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Create shipping_rates table for seller shipping configuration
CREATE TABLE IF NOT EXISTS shipping_rates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    region VARCHAR(100) NOT NULL,
    weight_from DECIMAL(5,2) DEFAULT 0.00,
    weight_to DECIMAL(5,2) DEFAULT 999.99,
    shipping_method VARCHAR(50) DEFAULT 'standard',
    rate DECIMAL(10,2) NOT NULL,
    estimated_days INT DEFAULT 3,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_seller (seller_id),
    INDEX idx_region (region),
    INDEX idx_method (shipping_method)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create shipping_tracking table for detailed tracking history
CREATE TABLE IF NOT EXISTS shipping_tracking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    tracking_number VARCHAR(100),
    carrier VARCHAR(50),
    status VARCHAR(50) NOT NULL,
    location VARCHAR(255),
    description TEXT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    INDEX idx_order (order_id),
    INDEX idx_tracking (tracking_number),
    INDEX idx_carrier (carrier),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add weight and dimensions to products table
ALTER TABLE products
ADD COLUMN weight DECIMAL(5,2) DEFAULT 0.00 AFTER stock,
ADD COLUMN length DECIMAL(5,2) DEFAULT 0.00 AFTER weight,
ADD COLUMN width DECIMAL(5,2) DEFAULT 0.00 AFTER length,
ADD COLUMN height DECIMAL(5,2) DEFAULT 0.00 AFTER width;

-- Insert default shipping rates for Ghana (sample data)
-- First, ensure the admin user exists (seller_id = 1)
INSERT IGNORE INTO users (id, username, email, password, full_name, role, seller_verified) VALUES
(1, 'admin', 'admin@makola.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin', 1);

-- Now insert default shipping rates
INSERT INTO shipping_rates (seller_id, region, weight_from, weight_to, shipping_method, rate, estimated_days, is_active) VALUES
-- Admin/default rates (seller_id = 1 for admin user)
(1, 'Greater Accra', 0.00, 1.00, 'standard', 15.00, 2, 1),
(1, 'Greater Accra', 1.01, 5.00, 'standard', 25.00, 2, 1),
(1, 'Greater Accra', 5.01, 10.00, 'standard', 35.00, 3, 1),
(1, 'Greater Accra', 0.00, 5.00, 'express', 45.00, 1, 1),
(1, 'Greater Accra', 0.00, 10.00, 'yango', 25.00, 1, 1),
(1, 'Ashanti Region', 0.00, 1.00, 'standard', 20.00, 3, 1),
(1, 'Ashanti Region', 1.01, 5.00, 'standard', 35.00, 3, 1),
(1, 'Ashanti Region', 5.01, 10.00, 'standard', 50.00, 4, 1),
(1, 'Ashanti Region', 0.00, 5.00, 'express', 65.00, 2, 1),
(1, 'Ashanti Region', 0.00, 10.00, 'yango', 35.00, 1, 1),
(1, 'Other Regions', 0.00, 1.00, 'standard', 25.00, 4, 1),
(1, 'Other Regions', 1.01, 5.00, 'standard', 45.00, 4, 1),
(1, 'Other Regions', 5.01, 10.00, 'standard', 65.00, 5, 1),
(1, 'Other Regions', 0.00, 5.00, 'express', 85.00, 3, 1),
(1, 'Other Regions', 0.00, 10.00, 'yango', 45.00, 1, 1);

-- Update existing orders to have default shipping method
UPDATE orders SET shipping_method = 'standard' WHERE shipping_method IS NULL;
UPDATE orders SET shipping_cost = 0.00 WHERE shipping_cost IS NULL;

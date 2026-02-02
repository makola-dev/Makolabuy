-- Profile Enhancement Migration
-- Makola Marketplace

-- User Addresses Table
CREATE TABLE IF NOT EXISTS user_addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    address_type ENUM('home', 'work', 'other') DEFAULT 'home',
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address_line1 VARCHAR(255) NOT NULL,
    address_line2 VARCHAR(255),
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100),
    postal_code VARCHAR(20),
    country VARCHAR(100) DEFAULT 'Ghana',
    is_default TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_default (user_id, is_default)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Payment Methods Table
CREATE TABLE IF NOT EXISTS payment_methods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    method_type ENUM('card', 'mobile_money') DEFAULT 'card',
    provider VARCHAR(50), -- visa, mastercard, mtn, vodafone, airtel
    last_four VARCHAR(4),
    card_holder_name VARCHAR(100),
    expiry_month TINYINT,
    expiry_year SMALLINT,
    mobile_number VARCHAR(20),
    is_default TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_default (user_id, is_default)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data for testing
INSERT INTO user_addresses (user_id, address_type, full_name, phone, address_line1, city, country, is_default) VALUES
(1, 'home', 'Admin User', '+233241234567', '123 Main Street', 'Accra', 'Ghana', 1)
ON DUPLICATE KEY UPDATE user_id=user_id;

INSERT INTO payment_methods (user_id, method_type, provider, last_four, card_holder_name, expiry_month, expiry_year, is_default) VALUES
(1, 'card', 'visa', '4242', 'Admin User', 12, 2025, 1),
(1, 'mobile_money', 'mtn', NULL, NULL, NULL, NULL, '+233241234567', 0)
ON DUPLICATE KEY UPDATE user_id=user_id;

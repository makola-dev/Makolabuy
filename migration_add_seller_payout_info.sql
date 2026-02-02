-- Migration: Add seller payout information table
-- This table stores payout details for sellers

CREATE TABLE IF NOT EXISTS seller_payout_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL UNIQUE,
    payout_method ENUM('paystack', 'bank', 'mobile_money') DEFAULT 'paystack',
    bank_name VARCHAR(255),
    account_number VARCHAR(100),
    account_name VARCHAR(255),
    mobile_money_provider VARCHAR(50),
    mobile_money_number VARCHAR(20),
    paystack_recipient_code VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_seller (seller_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


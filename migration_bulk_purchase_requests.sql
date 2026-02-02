-- Migration: Create bulk_purchase_requests table
CREATE TABLE IF NOT EXISTS bulk_purchase_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company VARCHAR(255) NOT NULL,
    contact_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    quantity INT NOT NULL,
    products TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

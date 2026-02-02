-- Migration: Add Multiple Images Support for Products
-- Run this SQL file to add support for multiple product images

-- Create product_images table
CREATE TABLE IF NOT EXISTS product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product (product_id),
    INDEX idx_display_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Migrate existing single images to product_images table
INSERT INTO product_images (product_id, image_path, display_order)
SELECT id, image, 0
FROM products
WHERE image IS NOT NULL AND image != '';


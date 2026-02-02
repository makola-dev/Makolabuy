-- Makola Database Schema
-- Created for Jumia/eBay/AliExpress style marketplace

-- Create database (uncomment if needed)
-- CREATE DATABASE IF NOT EXISTS makola_db;
-- USE makola_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('buyer', 'seller', 'admin') DEFAULT 'buyer',
    seller_verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Subcategories table
CREATE TABLE IF NOT EXISTS subcategories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_category (category_id),
    UNIQUE KEY unique_subcategory (category_id, slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    category_id INT NOT NULL,
    subcategory_id INT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    image VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
    FOREIGN KEY (subcategory_id) REFERENCES subcategories(id) ON DELETE SET NULL,
    INDEX idx_seller (seller_id),
    INDEX idx_category (category_id),
    INDEX idx_subcategory (subcategory_id),
    INDEX idx_status (status),
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Product images table (for multiple images per product)
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

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    shipping_address TEXT NOT NULL,
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    payment_reference VARCHAR(255),
    order_status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_buyer (buyer_id),
    INDEX idx_order_number (order_number),
    INDEX idx_payment_status (payment_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Order items table (stores commission per item)
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    seller_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    commission_rate DECIMAL(5, 2) DEFAULT 10.00,
    commission_amount DECIMAL(10, 2) NOT NULL,
    seller_earnings DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_order (order_id),
    INDEX idx_seller (seller_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Commissions table (for tracking commission history)
CREATE TABLE IF NOT EXISTS commissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_item_id INT NOT NULL,
    seller_id INT NOT NULL,
    order_id INT NOT NULL,
    commission_amount DECIMAL(10, 2) NOT NULL,
    seller_earnings DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'paid') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_item_id) REFERENCES order_items(id) ON DELETE CASCADE,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE RESTRICT,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE RESTRICT,
    INDEX idx_seller (seller_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user (password: admin123)
-- Password hash for 'admin123' using password_hash()
INSERT INTO users (username, email, password, full_name, role, seller_verified) VALUES
('admin', 'admin@makola.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin', 1)
ON DUPLICATE KEY UPDATE username=username;

-- Insert sample categories
INSERT INTO categories (name, slug, description) VALUES
('Electronics', 'electronics', 'Electronic devices and gadgets'),
('Fashion', 'fashion', 'Clothing and accessories'),
('Home & Garden', 'home-garden', 'Home improvement and garden supplies'),
('Sports & Outdoors', 'sports-outdoors', 'Sports equipment and outdoor gear'),
('Books', 'books', 'Books and literature'),
('Toys & Games', 'toys-games', 'Toys and games for all ages')
ON DUPLICATE KEY UPDATE name=name;

-- Insert sample subcategories
INSERT INTO subcategories (category_id, name, slug, description) VALUES
-- Fashion subcategories
((SELECT id FROM categories WHERE slug = 'fashion'), 'Men\'s Wear', 'mens-wear', 'Men\'s clothing and apparel'),
((SELECT id FROM categories WHERE slug = 'fashion'), 'Women\'s Wear', 'womens-wear', 'Women\'s clothing and apparel'),
((SELECT id FROM categories WHERE slug = 'fashion'), 'Jewelries', 'jewelries', 'Jewelry and accessories'),
((SELECT id FROM categories WHERE slug = 'fashion'), 'Shoes & Bags', 'shoes-bags', 'Footwear and bags'),
((SELECT id FROM categories WHERE slug = 'fashion'), 'Others', 'fashion-others', 'Other fashion items'),

-- Electronics subcategories
((SELECT id FROM categories WHERE slug = 'electronics'), 'Mobile Phones', 'mobile-phones', 'Smartphones and mobile devices'),
((SELECT id FROM categories WHERE slug = 'electronics'), 'Computers & Laptops', 'computers-laptops', 'Computers, laptops and accessories'),
((SELECT id FROM categories WHERE slug = 'electronics'), 'TV & Audio', 'tv-audio', 'Televisions and audio equipment'),
((SELECT id FROM categories WHERE slug = 'electronics'), 'Cameras', 'cameras', 'Cameras and photography equipment'),
((SELECT id FROM categories WHERE slug = 'electronics'), 'Others', 'electronics-others', 'Other electronic devices'),

-- Home & Garden subcategories
((SELECT id FROM categories WHERE slug = 'home-garden'), 'Furniture', 'furniture', 'Home furniture'),
((SELECT id FROM categories WHERE slug = 'home-garden'), 'Kitchen & Dining', 'kitchen-dining', 'Kitchen and dining items'),
((SELECT id FROM categories WHERE slug = 'home-garden'), 'Home Decor', 'home-decor', 'Home decoration items'),
((SELECT id FROM categories WHERE slug = 'home-garden'), 'Garden Tools', 'garden-tools', 'Garden and outdoor tools'),
((SELECT id FROM categories WHERE slug = 'home-garden'), 'Others', 'home-garden-others', 'Other home and garden items'),

-- Sports & Outdoors subcategories
((SELECT id FROM categories WHERE slug = 'sports-outdoors'), 'Fitness Equipment', 'fitness-equipment', 'Fitness and exercise equipment'),
((SELECT id FROM categories WHERE slug = 'sports-outdoors'), 'Outdoor Gear', 'outdoor-gear', 'Camping and outdoor gear'),
((SELECT id FROM categories WHERE slug = 'sports-outdoors'), 'Sports Apparel', 'sports-apparel', 'Sports clothing and apparel'),
((SELECT id FROM categories WHERE slug = 'sports-outdoors'), 'Water Sports', 'water-sports', 'Water sports equipment'),
((SELECT id FROM categories WHERE slug = 'sports-outdoors'), 'Others', 'sports-others', 'Other sports and outdoor items'),

-- Books subcategories
((SELECT id FROM categories WHERE slug = 'books'), 'Fiction', 'fiction', 'Fiction books'),
((SELECT id FROM categories WHERE slug = 'books'), 'Non-Fiction', 'non-fiction', 'Non-fiction books'),
((SELECT id FROM categories WHERE slug = 'books'), 'Educational', 'educational', 'Educational and textbooks'),
((SELECT id FROM categories WHERE slug = 'books'), 'Children\'s Books', 'childrens-books', 'Books for children'),
((SELECT id FROM categories WHERE slug = 'books'), 'Others', 'books-others', 'Other books'),

-- Toys & Games subcategories
((SELECT id FROM categories WHERE slug = 'toys-games'), 'Action Figures', 'action-figures', 'Action figures and collectibles'),
((SELECT id FROM categories WHERE slug = 'toys-games'), 'Board Games', 'board-games', 'Board games and puzzles'),
((SELECT id FROM categories WHERE slug = 'toys-games'), 'Educational Toys', 'educational-toys', 'Educational and learning toys'),
((SELECT id FROM categories WHERE slug = 'toys-games'), 'Video Games', 'video-games', 'Video games and consoles'),
((SELECT id FROM categories WHERE slug = 'toys-games'), 'Others', 'toys-games-others', 'Other toys and games')
ON DUPLICATE KEY UPDATE name=name;


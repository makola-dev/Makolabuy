-- Migration: Add Subcategories Support
-- Run this SQL file if you already have the database set up
-- This adds the subcategories table and updates the products table

-- Create subcategories table
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

-- Add subcategory_id column to products table
ALTER TABLE products 
ADD COLUMN subcategory_id INT NULL AFTER category_id,
ADD FOREIGN KEY (subcategory_id) REFERENCES subcategories(id) ON DELETE SET NULL,
ADD INDEX idx_subcategory (subcategory_id);

-- Insert sample subcategories
INSERT INTO subcategories (category_id, name, slug, description) VALUES
-- Fashion subcategories
((SELECT id FROM categories WHERE slug = 'fashion' LIMIT 1), 'Men\'s Wear', 'mens-wear', 'Men\'s clothing and apparel'),
((SELECT id FROM categories WHERE slug = 'fashion' LIMIT 1), 'Women\'s Wear', 'womens-wear', 'Women\'s clothing and apparel'),
((SELECT id FROM categories WHERE slug = 'fashion' LIMIT 1), 'Jewelries', 'jewelries', 'Jewelry and accessories'),
((SELECT id FROM categories WHERE slug = 'fashion' LIMIT 1), 'Shoes & Bags', 'shoes-bags', 'Footwear and bags'),
((SELECT id FROM categories WHERE slug = 'fashion' LIMIT 1), 'Others', 'fashion-others', 'Other fashion items'),

-- Electronics subcategories
((SELECT id FROM categories WHERE slug = 'electronics' LIMIT 1), 'Mobile Phones', 'mobile-phones', 'Smartphones and mobile devices'),
((SELECT id FROM categories WHERE slug = 'electronics' LIMIT 1), 'Computers & Laptops', 'computers-laptops', 'Computers, laptops and accessories'),
((SELECT id FROM categories WHERE slug = 'electronics' LIMIT 1), 'TV & Audio', 'tv-audio', 'Televisions and audio equipment'),
((SELECT id FROM categories WHERE slug = 'electronics' LIMIT 1), 'Cameras', 'cameras', 'Cameras and photography equipment'),
((SELECT id FROM categories WHERE slug = 'electronics' LIMIT 1), 'Others', 'electronics-others', 'Other electronic devices'),

-- Home & Garden subcategories
((SELECT id FROM categories WHERE slug = 'home-garden' LIMIT 1), 'Furniture', 'furniture', 'Home furniture'),
((SELECT id FROM categories WHERE slug = 'home-garden' LIMIT 1), 'Kitchen & Dining', 'kitchen-dining', 'Kitchen and dining items'),
((SELECT id FROM categories WHERE slug = 'home-garden' LIMIT 1), 'Home Decor', 'home-decor', 'Home decoration items'),
((SELECT id FROM categories WHERE slug = 'home-garden' LIMIT 1), 'Garden Tools', 'garden-tools', 'Garden and outdoor tools'),
((SELECT id FROM categories WHERE slug = 'home-garden' LIMIT 1), 'Others', 'home-garden-others', 'Other home and garden items'),

-- Sports & Outdoors subcategories
((SELECT id FROM categories WHERE slug = 'sports-outdoors' LIMIT 1), 'Fitness Equipment', 'fitness-equipment', 'Fitness and exercise equipment'),
((SELECT id FROM categories WHERE slug = 'sports-outdoors' LIMIT 1), 'Outdoor Gear', 'outdoor-gear', 'Camping and outdoor gear'),
((SELECT id FROM categories WHERE slug = 'sports-outdoors' LIMIT 1), 'Sports Apparel', 'sports-apparel', 'Sports clothing and apparel'),
((SELECT id FROM categories WHERE slug = 'sports-outdoors' LIMIT 1), 'Water Sports', 'water-sports', 'Water sports equipment'),
((SELECT id FROM categories WHERE slug = 'sports-outdoors' LIMIT 1), 'Others', 'sports-others', 'Other sports and outdoor items'),

-- Books subcategories
((SELECT id FROM categories WHERE slug = 'books' LIMIT 1), 'Fiction', 'fiction', 'Fiction books'),
((SELECT id FROM categories WHERE slug = 'books' LIMIT 1), 'Non-Fiction', 'non-fiction', 'Non-fiction books'),
((SELECT id FROM categories WHERE slug = 'books' LIMIT 1), 'Educational', 'educational', 'Educational and textbooks'),
((SELECT id FROM categories WHERE slug = 'books' LIMIT 1), 'Children\'s Books', 'childrens-books', 'Books for children'),
((SELECT id FROM categories WHERE slug = 'books' LIMIT 1), 'Others', 'books-others', 'Other books'),

-- Toys & Games subcategories
((SELECT id FROM categories WHERE slug = 'toys-games' LIMIT 1), 'Action Figures', 'action-figures', 'Action figures and collectibles'),
((SELECT id FROM categories WHERE slug = 'toys-games' LIMIT 1), 'Board Games', 'board-games', 'Board games and puzzles'),
((SELECT id FROM categories WHERE slug = 'toys-games' LIMIT 1), 'Educational Toys', 'educational-toys', 'Educational and learning toys'),
((SELECT id FROM categories WHERE slug = 'toys-games' LIMIT 1), 'Video Games', 'video-games', 'Video games and consoles'),
((SELECT id FROM categories WHERE slug = 'toys-games' LIMIT 1), 'Others', 'toys-games-others', 'Other toys and games')
ON DUPLICATE KEY UPDATE name=name;


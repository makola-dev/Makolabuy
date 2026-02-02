-- Update banners table to add missing columns
-- These columns are used in index.php but were missing from the original table

ALTER TABLE banners 
ADD COLUMN IF NOT EXISTS subtitle VARCHAR(255) AFTER title,
ADD COLUMN IF NOT EXISTS description TEXT AFTER subtitle,
ADD COLUMN IF NOT EXISTS button_text VARCHAR(100) DEFAULT 'Shop Now' AFTER link_url;

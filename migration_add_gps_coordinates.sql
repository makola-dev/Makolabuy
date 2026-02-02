-- Migration: Add GPS coordinates to orders table
-- This allows storing latitude and longitude for delivery tracking

ALTER TABLE orders 
ADD COLUMN IF NOT EXISTS latitude DECIMAL(10, 8) NULL AFTER shipping_address,
ADD COLUMN IF NOT EXISTS longitude DECIMAL(11, 8) NULL AFTER latitude,
ADD INDEX idx_location (latitude, longitude);





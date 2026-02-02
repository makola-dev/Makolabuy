-- Migration: Add Google OAuth fields to users table
-- This migration adds fields for Google OAuth authentication

ALTER TABLE users
ADD COLUMN google_id VARCHAR(255) UNIQUE NULL AFTER seller_verified,
ADD COLUMN google_email VARCHAR(255) UNIQUE NULL AFTER google_id,
ADD COLUMN auth_provider ENUM('local', 'google') DEFAULT 'local' AFTER google_email,
ADD COLUMN profile_picture VARCHAR(500) NULL AFTER auth_provider,
ADD COLUMN INDEX idx_google_id (google_id),
ADD COLUMN INDEX idx_google_email (google_email);

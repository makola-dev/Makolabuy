-- Add hero banner to the database
-- Make sure the migration_professional_features.sql has been run first to create the banners table

-- Insert the hero banner
INSERT INTO banners (title, image, link_url, display_order, is_active, start_date, end_date) 
VALUES (
    'Welcome to Makola',
    'hero banner.jpeg',
    'index.php?page=home',
    1,
    1,
    NOW(),
    NULL
);

-- You can customize the banner by updating these values:
-- title: The main heading text that appears on the banner
-- image: The filename (already set to 'hero banner.jpeg')
-- link_url: Where the "Shop Now" button will link to
-- display_order: Order in carousel (1 = first)
-- is_active: 1 = active, 0 = inactive
-- start_date: When to start showing (NOW() = immediately)
-- end_date: When to stop showing (NULL = never expires)

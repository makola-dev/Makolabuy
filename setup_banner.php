<?php
/**
 * One-time script to add hero banner
 * After running this once, you can delete this file
 */

require_once 'config/db.php';

$conn = getDBConnection();

// First, check if banners table exists
$check_table = $conn->query("SHOW TABLES LIKE 'banners'");
if ($check_table->num_rows === 0) {
    die("ERROR: The 'banners' table doesn't exist. Please run migration_professional_features.sql first.");
}

// Check if banner already exists
$check_banner = $conn->query("SELECT * FROM banners WHERE image = 'hero banner.jpeg'");

if ($check_banner->num_rows > 0) {
    echo "Banner already exists in the database!<br>";
    echo "Visit your homepage to see it: <a href='index.php'>Go to Homepage</a>";
} else {
    // Insert the banner
    $sql = "INSERT INTO banners (title, subtitle, description, image, link_url, button_text, display_order, is_active, start_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    
    $title = "Welcome to Makola";
    $subtitle = "Your Premier Online Marketplace";
    $description = "Discover amazing products from trusted sellers across Ghana";
    $image = "hero banner.jpeg";
    $link_url = "index.php?page=home";
    $button_text = "Shop Now";
    $display_order = 1;
    $is_active = 1;
    
    $stmt->bind_param("ssssssis", $title, $subtitle, $description, $image, $link_url, $button_text, $display_order, $is_active);
    
    if ($stmt->execute()) {
        echo "<h2 style='color: green;'>✓ Banner added successfully!</h2>";
        echo "<p>Your banner has been added to the database.</p>";
        echo "<p>Visit your homepage to see it: <a href='index.php'>Go to Homepage</a></p>";
        echo "<hr>";
        echo "<p><strong>Note:</strong> You can delete this file (setup_banner.php) now.</p>";
    } else {
        echo "<h2 style='color: red;'>✗ Error adding banner</h2>";
        echo "<p>Error: " . $stmt->error . "</p>";
    }
    
    $stmt->close();
}

closeDBConnection($conn);
?>

<?php
/**
 * Add Holi Banner to Database
 */

require_once 'config/db.php';

$conn = getDBConnection();

// Check if this banner already exists
$check = $conn->query("SELECT * FROM banners WHERE image = 'hero banner.png'");

if ($check->num_rows > 0) {
    echo "<h2 style='color: orange;'>Banner already exists!</h2>";
    echo "<p>The Holi banner is already in the database.</p>";
    echo "<p><a href='check_banners.php'>Check banner status</a> | <a href='index.php'>View Homepage</a></p>";
} else {
    // Add the new banner
    $sql = "INSERT INTO banners (title, subtitle, description, image, link_url, button_text, display_order, is_active, start_date, end_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    $title = "Get Glowing This Holi!";
    $subtitle = "Flat 10% Discount on All Products";
    $description = "Enjoy a flat 10% discount on all products. Pamper your skin with the care it deserves.";
    $image = "hero banner.png";
    $link_url = "index.php?promo=holi10";
    $button_text = "Shop Now";
    $display_order = 2; // This will be the second banner
    $is_active = 1;
    $start_date = "2026-03-02 00:00:00";
    $end_date = "2026-03-28 23:59:59";
    
    $stmt->bind_param("sssssssiiss", $title, $subtitle, $description, $image, $link_url, $button_text, $display_order, $is_active, $start_date, $end_date);
    
    if ($stmt->execute()) {
        echo "<h2 style='color: green;'>✓ Holi Banner Added Successfully!</h2>";
        echo "<p>Your Holi promotion banner has been added to the carousel.</p>";
        echo "<p><strong>Details:</strong></p>";
        echo "<ul>";
        echo "<li>Title: Get Glowing This Holi!</li>";
        echo "<li>Valid: March 02, 2026 - March 28, 2026</li>";
        echo "<li>Display Order: 2 (second in carousel)</li>";
        echo "<li>Promo Code: HOLI10</li>";
        echo "</ul>";
        echo "<hr>";
        echo "<p><a href='index.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>View Homepage</a></p>";
        echo "<p><small>The banner will automatically rotate in the carousel. Click the dots or arrows to switch between banners.</small></p>";
    } else {
        echo "<h2 style='color: red;'>✗ Error</h2>";
        echo "<p>Error: " . $stmt->error . "</p>";
    }
    
    $stmt->close();
}

closeDBConnection($conn);
?>

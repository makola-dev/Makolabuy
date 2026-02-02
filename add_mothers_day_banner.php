<?php
/**
 * Add Mother's Day Banner to Database
 */

require_once 'config/db.php';

$conn = getDBConnection();

// Check if this banner already exists
$check = $conn->query("SELECT * FROM banners WHERE image = 'hero banner 2.png'");

if ($check->num_rows > 0) {
    echo "<h2 style='color: orange;'>Banner already exists!</h2>";
    echo "<p>The Mother's Day banner is already in the database.</p>";
    echo "<p><a href='check_banners.php'>Check banner status</a> | <a href='index.php'>View Homepage</a></p>";
} else {
    // Add the new banner
    $sql = "INSERT INTO banners (title, subtitle, description, image, link_url, button_text, display_order, is_active, start_date, end_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    $title = "Mother's Day Sale";
    $subtitle = "Celebrate Mom with Special Offers";
    $description = "Up to 30% off on selected items. Show your love with the perfect gift.";
    $image = "hero banner 2.png";
    $link_url = "index.php?promo=mothersday";
    $button_text = "Shop Now";
    $display_order = 3; // This will be the third banner
    $is_active = 1;
    $start_date = "2026-01-18 00:00:00"; // Today
    $end_date = NULL; // No expiry
    
    $stmt->bind_param("ssssssiss", $title, $subtitle, $description, $image, $link_url, $button_text, $display_order, $is_active, $start_date, $end_date);
    
    if ($stmt->execute()) {
        echo "<h2 style='color: green;'>✓ Mother's Day Banner Added Successfully!</h2>";
        echo "<p>Your Mother's Day Sale banner has been added to the carousel.</p>";
        echo "<p><strong>Details:</strong></p>";
        echo "<ul>";
        echo "<li>Title: Mother's Day Sale</li>";
        echo "<li>Discount: Up to 30% Off</li>";
        echo "<li>Display Order: 3 (third in carousel)</li>";
        echo "<li>Status: Active</li>";
        echo "</ul>";
        echo "<hr>";
        echo "<p>You now have <strong>3 banners</strong> rotating in your carousel:</p>";
        echo "<ol>";
        echo "<li>Welcome to Makola</li>";
        echo "<li>Get Glowing This Holi (10% Off)</li>";
        echo "<li>Mother's Day Sale (Up to 30% Off)</li>";
        echo "</ol>";
        echo "<hr>";
        echo "<p><a href='index.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>View Homepage</a> ";
        echo "<a href='check_banners.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-left: 10px;'>Check All Banners</a></p>";
        echo "<p><small>The banners will automatically rotate every few seconds. Use the dots or arrows to navigate between them.</small></p>";
    } else {
        echo "<h2 style='color: red;'>✗ Error</h2>";
        echo "<p>Error: " . $stmt->error . "</p>";
    }
    
    $stmt->close();
}

closeDBConnection($conn);
?>

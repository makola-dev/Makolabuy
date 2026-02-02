<?php
/**
 * Banner Diagnostic Script
 * This will show you all banners in the database and why they may or may not be displaying
 */

require_once 'config/db.php';

$conn = getDBConnection();

// Check if banners table exists
$check_table = $conn->query("SHOW TABLES LIKE 'banners'");
if ($check_table->num_rows === 0) {
    die("ERROR: The 'banners' table doesn't exist.");
}

echo "<h2>Banner Diagnostic Report</h2>";
echo "<hr>";

// Get all banners
$query = "SELECT * FROM banners ORDER BY display_order";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    echo "<p style='color: red;'>No banners found in the database!</p>";
} else {
    echo "<p style='color: green;'>Found " . $result->num_rows . " banner(s) in the database:</p>";
    echo "<hr>";
    
    while ($banner = $result->fetch_assoc()) {
        echo "<div style='border: 1px solid #ccc; padding: 15px; margin-bottom: 20px;'>";
        echo "<h3>Banner ID: " . $banner['id'] . "</h3>";
        
        // Check each condition
        $issues = [];
        $is_active = $banner['is_active'] == 1;
        $start_date_ok = empty($banner['start_date']) || strtotime($banner['start_date']) <= time();
        $end_date_ok = empty($banner['end_date']) || strtotime($banner['end_date']) >= time();
        $image_exists = file_exists(__DIR__ . '/assets/img/banners/' . $banner['image']);
        
        echo "<table border='1' cellpadding='5' style='width: 100%;'>";
        echo "<tr><td><strong>Title:</strong></td><td>" . htmlspecialchars($banner['title'] ?? 'N/A') . "</td></tr>";
        echo "<tr><td><strong>Image:</strong></td><td>" . htmlspecialchars($banner['image']) . "</td></tr>";
        echo "<tr><td><strong>Link URL:</strong></td><td>" . htmlspecialchars($banner['link_url'] ?? 'N/A') . "</td></tr>";
        echo "<tr><td><strong>Display Order:</strong></td><td>" . $banner['display_order'] . "</td></tr>";
        
        echo "<tr><td><strong>Is Active:</strong></td><td style='color: " . ($is_active ? 'green' : 'red') . "'>" . ($is_active ? 'YES ✓' : 'NO ✗') . " (value: " . $banner['is_active'] . ")</td></tr>";
        
        echo "<tr><td><strong>Start Date:</strong></td><td style='color: " . ($start_date_ok ? 'green' : 'red') . "'>";
        if (empty($banner['start_date'])) {
            echo "Not set (OK) ✓";
        } else {
            echo htmlspecialchars($banner['start_date']) . " " . ($start_date_ok ? '✓' : '✗ (Future date!)');
        }
        echo "</td></tr>";
        
        echo "<tr><td><strong>End Date:</strong></td><td style='color: " . ($end_date_ok ? 'green' : 'red') . "'>";
        if (empty($banner['end_date'])) {
            echo "Not set (OK) ✓";
        } else {
            echo htmlspecialchars($banner['end_date']) . " " . ($end_date_ok ? '✓' : '✗ (Expired!)');
        }
        echo "</td></tr>";
        
        echo "<tr><td><strong>Image File Exists:</strong></td><td style='color: " . ($image_exists ? 'green' : 'red') . "'>" . ($image_exists ? 'YES ✓' : 'NO ✗') . "</td></tr>";
        
        echo "</table>";
        
        // Determine if this banner will show
        $will_show = $is_active && $start_date_ok && $end_date_ok && $image_exists;
        
        echo "<p style='font-size: 18px; font-weight: bold; color: " . ($will_show ? 'green' : 'red') . "'>";
        echo "Status: " . ($will_show ? '✓ WILL DISPLAY' : '✗ WILL NOT DISPLAY');
        echo "</p>";
        
        if (!$will_show) {
            echo "<p><strong>Issues preventing display:</strong></p><ul>";
            if (!$is_active) echo "<li>Banner is not active (is_active = 0)</li>";
            if (!$start_date_ok) echo "<li>Start date is in the future</li>";
            if (!$end_date_ok) echo "<li>End date has passed (expired)</li>";
            if (!$image_exists) echo "<li>Image file not found in assets/img/banners/</li>";
            echo "</ul>";
            
            echo "<p><strong>To fix:</strong></p>";
            if (!$is_active) {
                echo "<p>Run this SQL: <code>UPDATE banners SET is_active = 1 WHERE id = " . $banner['id'] . ";</code></p>";
            }
            if (!$start_date_ok) {
                echo "<p>Run this SQL: <code>UPDATE banners SET start_date = NOW() WHERE id = " . $banner['id'] . ";</code></p>";
            }
            if (!$end_date_ok) {
                echo "<p>Run this SQL: <code>UPDATE banners SET end_date = NULL WHERE id = " . $banner['id'] . ";</code></p>";
            }
        }
        
        echo "</div>";
    }
}

echo "<hr>";
echo "<p><a href='index.php'>← Back to Homepage</a></p>";

closeDBConnection($conn);
?>

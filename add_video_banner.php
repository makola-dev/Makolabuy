<?php
/**
 * Add Video Banner to Database
 * Upload your MP4 video to assets/img/banners/ first, then update the filename below
 */

require_once 'config/db.php';

// ============================================
// CONFIGURATION - EDIT THIS SECTION
// ============================================
$video_filename = "banner-video.mp4";  // Change this to your video filename
$banner_title = "Video Promotion";
$banner_subtitle = "Watch Our Latest Offers";
$banner_description = "Discover amazing deals and products";
$link_url = "index.php";
$button_text = "Shop Now";
$display_order = 4;  // Change this if needed
// ============================================

$conn = getDBConnection();

// Check if this banner already exists
$check = $conn->query("SELECT * FROM banners WHERE image = '" . $conn->real_escape_string($video_filename) . "'");

if ($check->num_rows > 0) {
    echo "<h2 style='color: orange;'>⚠ Video Banner Already Exists!</h2>";
    echo "<p>A banner with filename '<strong>" . htmlspecialchars($video_filename) . "</strong>' already exists in the database.</p>";
    echo "<p><a href='check_banners.php'>Check banner status</a> | <a href='index.php'>View Homepage</a></p>";
} else {
    // Check if video file exists
    $video_path = __DIR__ . '/assets/img/banners/' . $video_filename;
    if (!file_exists($video_path)) {
        echo "<h2 style='color: red;'>✗ Video File Not Found!</h2>";
        echo "<p>Please upload your video file to: <code>assets/img/banners/" . htmlspecialchars($video_filename) . "</code></p>";
        echo "<p><strong>Instructions:</strong></p>";
        echo "<ol>";
        echo "<li>Save your MP4 video (muted/no sound recommended)</li>";
        echo "<li>Upload it to: <code>C:\\xampp\\htdocs\\Makola\\assets\\img\\banners\\</code></li>";
        echo "<li>Make sure the filename matches: <code>" . htmlspecialchars($video_filename) . "</code></li>";
        echo "<li>Refresh this page</li>";
        echo "</ol>";
        echo "<hr>";
        echo "<p><a href='check_banners.php'>View All Banners</a></p>";
        exit;
    }
    
    // Add the video banner
    $sql = "INSERT INTO banners (title, subtitle, description, image, link_url, button_text, display_order, is_active, start_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    
    $is_active = 1;
    
    $stmt->bind_param("ssssssii", $banner_title, $banner_subtitle, $banner_description, $video_filename, $link_url, $button_text, $display_order, $is_active);
    
    if ($stmt->execute()) {
        echo "<h2 style='color: green;'>✓ Video Banner Added Successfully!</h2>";
        echo "<p>Your video banner has been added to the carousel.</p>";
        
        echo "<div style='background: #f0f8ff; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
        echo "<p><strong>📹 Video Banner Details:</strong></p>";
        echo "<ul>";
        echo "<li><strong>Filename:</strong> " . htmlspecialchars($video_filename) . "</li>";
        echo "<li><strong>Title:</strong> " . htmlspecialchars($banner_title) . "</li>";
        echo "<li><strong>Display Order:</strong> " . $display_order . "</li>";
        echo "<li><strong>Status:</strong> Active ✓</li>";
        echo "<li><strong>Features:</strong> Auto-play, Muted, Looping</li>";
        echo "</ul>";
        echo "</div>";
        
        echo "<div style='background: #fff3cd; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
        echo "<p><strong>💡 Video Banner Tips:</strong></p>";
        echo "<ul>";
        echo "<li>Videos auto-play muted and loop continuously</li>";
        echo "<li>Recommended: Keep videos under 10MB for faster loading</li>";
        echo "<li>Recommended resolution: 1920x600 or 1920x800 pixels</li>";
        echo "<li>Works on all devices including mobile</li>";
        echo "<li>Mix and match: You can have both image and video banners!</li>";
        echo "</ul>";
        echo "</div>";
        
        echo "<hr>";
        echo "<p>";
        echo "<a href='index.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-right: 10px;'>🏠 View Homepage</a> ";
        echo "<a href='check_banners.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>📋 Check All Banners</a>";
        echo "</p>";
        
    } else {
        echo "<h2 style='color: red;'>✗ Error Adding Banner</h2>";
        echo "<p>Error: " . $stmt->error . "</p>";
    }
    
    $stmt->close();
}

closeDBConnection($conn);
?>

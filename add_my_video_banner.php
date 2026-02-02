<?php
/**
 * Add Your Video Banner to Database
 */

require_once 'config/db.php';

$conn = getDBConnection();

// Video configuration
$video_filename = "banner-video.mp4";

// Check if this banner already exists
$check = $conn->query("SELECT * FROM banners WHERE image = 'banner-video.mp4'");

if ($check->num_rows > 0) {
    echo "<h2 style='color: orange;'>⚠ Video Banner Already Exists!</h2>";
    echo "<p>Your video banner is already in the database.</p>";
    echo "<p><a href='check_banners.php'>Check banner status</a> | <a href='index.php'>View Homepage</a></p>";
} else {
    // Check if video file exists
    $video_path = __DIR__ . '/assets/img/banners/' . $video_filename;
    if (!file_exists($video_path)) {
        echo "<h2 style='color: red;'>✗ Video File Not Found!</h2>";
        echo "<p>Looking for: <code>" . $video_path . "</code></p>";
        exit;
    }
    
    // Get video file size
    $file_size = filesize($video_path);
    $file_size_mb = round($file_size / 1048576, 2);
    
    // Add the video banner
    $sql = "INSERT INTO banners (title, subtitle, description, image, link_url, button_text, display_order, is_active, start_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    
    $title = "Shop Makola";
    $subtitle = "Your Premier Marketplace";
    $description = "Discover amazing products from trusted sellers";
    $link_url = "index.php";
    $button_text = "Explore Now";
    $display_order = 4;
    $is_active = 1;
    
    $stmt->bind_param("ssssssii", $title, $subtitle, $description, $video_filename, $link_url, $button_text, $display_order, $is_active);
    
    if ($stmt->execute()) {
        echo "<!DOCTYPE html>";
        echo "<html><head>";
        echo "<style>
            body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
            .success-box { background: #d4edda; border: 2px solid #28a745; padding: 30px; border-radius: 10px; }
            .info-box { background: #f0f8ff; border: 2px solid #0dcaf0; padding: 20px; border-radius: 10px; margin: 20px 0; }
            .tip-box { background: #fff3cd; border: 2px solid #ffc107; padding: 20px; border-radius: 10px; margin: 20px 0; }
            h2 { color: #28a745; margin-top: 0; }
            ul { line-height: 1.8; }
            .btn { display: inline-block; padding: 12px 24px; margin: 10px 10px 10px 0; background: #007bff; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
            .btn:hover { background: #0056b3; }
            .btn-success { background: #28a745; }
            .btn-success:hover { background: #1e7e34; }
            .video-icon { font-size: 48px; text-align: center; margin: 20px 0; }
        </style>
        </head><body>";
        
        echo "<div class='success-box'>";
        echo "<div class='video-icon'>🎬</div>";
        echo "<h2>✓ Video Banner Added Successfully!</h2>";
        echo "<p style='font-size: 18px;'>Your video banner is now live in the carousel!</p>";
        echo "</div>";
        
        echo "<div class='info-box'>";
        echo "<h3>📹 Video Banner Details:</h3>";
        echo "<ul>";
        echo "<li><strong>Filename:</strong> " . htmlspecialchars($video_filename) . "</li>";
        echo "<li><strong>File Size:</strong> " . $file_size_mb . " MB</li>";
        echo "<li><strong>Title:</strong> " . htmlspecialchars($title) . "</li>";
        echo "<li><strong>Display Order:</strong> " . $display_order . " (4th position)</li>";
        echo "<li><strong>Status:</strong> <span style='color: #28a745; font-weight: bold;'>Active ✓</span></li>";
        echo "<li><strong>Auto-play:</strong> Yes (muted)</li>";
        echo "<li><strong>Looping:</strong> Yes</li>";
        echo "<li><strong>Mobile Support:</strong> Yes</li>";
        echo "</ul>";
        echo "</div>";
        
        echo "<div class='tip-box'>";
        echo "<h3>🎯 Your Complete Banner Carousel:</h3>";
        echo "<p>You now have 4 banners rotating on your homepage:</p>";
        echo "<ol>";
        echo "<li>🖼️ Welcome Banner (image)</li>";
        echo "<li>🖼️ Holi Promotion - 10% Off (image)</li>";
        echo "<li>🖼️ Mother's Day Sale - 30% Off (image)</li>";
        echo "<li>🎬 <strong>Video Banner - NEW!</strong> (auto-playing video)</li>";
        echo "</ol>";
        echo "<p><strong>Features:</strong></p>";
        echo "<ul>";
        echo "<li>✓ Auto-rotate every few seconds</li>";
        echo "<li>✓ Navigation dots to jump to any banner</li>";
        echo "<li>✓ Left/Right arrows to navigate</li>";
        echo "<li>✓ Swipe support on mobile devices</li>";
        echo "<li>✓ Video plays automatically when slide appears</li>";
        echo "<li>✓ Fully responsive on all devices</li>";
        echo "</ul>";
        echo "</div>";
        
        echo "<div style='text-align: center; margin-top: 30px;'>";
        echo "<a href='index.php' class='btn'>🏠 View Homepage</a> ";
        echo "<a href='check_banners.php' class='btn btn-success'>📋 Check All Banners</a>";
        echo "</div>";
        
        echo "<p style='text-align: center; margin-top: 30px; color: #666;'><small>Tip: Hard refresh your browser (Ctrl+Shift+R) if you don't see the video immediately.</small></p>";
        
        echo "</body></html>";
        
    } else {
        echo "<h2 style='color: red;'>✗ Error Adding Banner</h2>";
        echo "<p>Error: " . $stmt->error . "</p>";
    }
    
    $stmt->close();
}

closeDBConnection($conn);
?>

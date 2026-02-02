<?php
/**
 * Debug Video Banner - Check what's in the database and test the path
 */

require_once 'config/db.php';
require_once 'config/paths.php';

$conn = getDBConnection();

echo "<!DOCTYPE html>";
echo "<html><head>";
echo "<style>
    body { font-family: monospace; padding: 20px; background: #f5f5f5; }
    .section { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; border: 1px solid #ddd; }
    h2 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
    .success { color: #28a745; }
    .error { color: #dc3545; }
    .path { background: #f0f0f0; padding: 5px 10px; border-radius: 4px; font-weight: bold; }
    table { width: 100%; border-collapse: collapse; margin: 10px 0; }
    td, th { padding: 8px; border: 1px solid #ddd; text-align: left; }
    th { background: #007bff; color: white; }
    video { max-width: 100%; height: auto; }
</style>
</head><body>";

echo "<h1>🔍 Video Banner Debug Report</h1>";

// Check BASE_PATH
echo "<div class='section'>";
echo "<h2>1. BASE_PATH Configuration</h2>";
echo "<p>BASE_PATH = <span class='path'>" . BASE_PATH . "</span></p>";
echo "</div>";

// Check file system
echo "<div class='section'>";
echo "<h2>2. File System Check</h2>";
$video_file = __DIR__ . '/assets/img/banners/banner-video.mp4';
if (file_exists($video_file)) {
    $file_size = filesize($video_file);
    $file_size_mb = round($file_size / 1048576, 2);
    echo "<p class='success'>✓ Video file EXISTS on server</p>";
    echo "<p>Path: <span class='path'>$video_file</span></p>";
    echo "<p>Size: <strong>$file_size_mb MB</strong></p>";
} else {
    echo "<p class='error'>✗ Video file NOT FOUND</p>";
    echo "<p>Looking for: <span class='path'>$video_file</span></p>";
}
echo "</div>";

// Check database
echo "<div class='section'>";
echo "<h2>3. Database Check</h2>";
$query = "SELECT * FROM banners WHERE image LIKE '%video%' OR image LIKE '%.mp4%'";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    echo "<p class='success'>✓ Found " . $result->num_rows . " video banner(s) in database</p>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Title</th><th>Image</th><th>Active</th><th>Display Order</th></tr>";
    
    $video_banner = null;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
        echo "<td><strong>" . htmlspecialchars($row['image']) . "</strong></td>";
        echo "<td>" . ($row['is_active'] ? '✓ Active' : '✗ Inactive') . "</td>";
        echo "<td>" . $row['display_order'] . "</td>";
        echo "</tr>";
        if (!$video_banner) $video_banner = $row;
    }
    echo "</table>";
} else {
    echo "<p class='error'>✗ No video banners found in database</p>";
}
echo "</div>";

// Generate expected URL
if (isset($video_banner)) {
    echo "<div class='section'>";
    echo "<h2>4. Expected Video URL</h2>";
    $expected_url = BASE_PATH . 'assets/img/banners/' . $video_banner['image'];
    echo "<p>URL that should be generated:</p>";
    echo "<p class='path'>" . htmlspecialchars($expected_url) . "</p>";
    
    // Full URL
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $full_url = $protocol . '://' . $host . $expected_url;
    echo "<p>Full URL:</p>";
    echo "<p class='path'>" . htmlspecialchars($full_url) . "</p>";
    echo "</div>";
    
    // Test video rendering
    echo "<div class='section'>";
    echo "<h2>5. Video Test</h2>";
    echo "<p>Attempting to render video with the same code used in carousel:</p>";
    echo "<video class='d-block w-100' autoplay muted loop playsinline controls style='max-width: 800px; background: #000;'>";
    echo "<source src='" . BASE_PATH . "assets/img/banners/" . htmlspecialchars($video_banner['image']) . "' type='video/mp4'>";
    echo "Your browser does not support the video tag.";
    echo "</video>";
    echo "<p><small>If you see video controls above, the video is loading correctly. If not, there's a path issue.</small></p>";
    echo "</div>";
}

// List all files in banners directory
echo "<div class='section'>";
echo "<h2>6. All Files in Banners Directory</h2>";
$banners_dir = __DIR__ . '/assets/img/banners/';
$files = scandir($banners_dir);
echo "<ul>";
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        $file_path = $banners_dir . $file;
        $file_size = filesize($file_path);
        $file_size_kb = round($file_size / 1024, 2);
        echo "<li><strong>$file</strong> ($file_size_kb KB)</li>";
    }
}
echo "</ul>";
echo "</div>";

closeDBConnection($conn);

echo "<div style='text-align: center; margin: 30px 0;'>";
echo "<a href='index.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>← Back to Homepage</a>";
echo "</div>";

echo "</body></html>";
?>

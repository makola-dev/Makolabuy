<?php
/**
 * Test Quick View Endpoint
 * Debug script to check what quickView.php returns
 */
require_once 'config/db.php';
require_once 'config/paths.php';

$product_id = $_GET['id'] ?? 1; // Default to product ID 1

echo "<h2>Testing Quick View for Product ID: $product_id</h2>";

// Call the quickView controller
$url = BASE_PATH . 'controllers/quickView.php?id=' . $product_id;
echo "<p><strong>URL:</strong> <a href='$url' target='_blank'>$url</a></p>";

// Fetch the response
$response = @file_get_contents($url);
if ($response === false) {
    echo "<p style='color: red;'><strong>Error:</strong> Could not fetch response. Check if the URL is accessible.</p>";
    exit;
}

echo "<h3>Raw JSON Response:</h3>";
echo "<pre style='background: #f5f5f5; padding: 15px; border-radius: 5px; overflow-x: auto;'>";
echo htmlspecialchars($response);
echo "</pre>";

// Decode and display
$data = json_decode($response, true);
if ($data && isset($data['product'])) {
    $product = $data['product'];
    echo "<h3>Parsed Product Data:</h3>";
    echo "<ul>";
    echo "<li><strong>ID:</strong> " . htmlspecialchars($product['id']) . "</li>";
    echo "<li><strong>Title:</strong> " . htmlspecialchars($product['title']) . "</li>";
    echo "<li><strong>Image Field:</strong> " . htmlspecialchars($product['image'] ?? 'NOT SET') . "</li>";
    echo "<li><strong>Images Array:</strong> ";
    if (isset($product['images']) && is_array($product['images'])) {
        echo "<ul>";
        foreach ($product['images'] as $idx => $img) {
            echo "<li>[$idx] " . htmlspecialchars($img ?: '(empty)') . "</li>";
        }
        echo "</ul>";
    } else {
        echo "NOT SET or NOT ARRAY";
    }
    echo "</li>";
    echo "<li><strong>Price:</strong> ₵" . number_format($product['price'], 2) . "</li>";
    echo "</ul>";
    
    // Test image paths
    echo "<h3>Image Paths Test:</h3>";
    $basePath = BASE_PATH;
    echo "<p><strong>BASE_PATH:</strong> " . htmlspecialchars($basePath) . "</p>";
    
    if (isset($product['images']) && is_array($product['images'])) {
        foreach ($product['images'] as $idx => $img) {
            if (!empty($img)) {
                $imgPath = $basePath . 'assets/img/products/' . $img;
                echo "<p><strong>Image $idx:</strong> " . htmlspecialchars($img) . "</p>";
                echo "<p><strong>Full Path:</strong> <a href='$imgPath' target='_blank'>$imgPath</a></p>";
                echo "<p><strong>File Exists:</strong> " . (file_exists('assets/img/products/' . $img) ? 'YES ✓' : 'NO ✗') . "</p>";
                if (file_exists('assets/img/products/' . $img)) {
                    echo "<p><img src='$imgPath' style='max-width: 200px; border: 1px solid #ddd; padding: 5px;' alt='Test Image'></p>";
                }
                echo "<hr>";
            }
        }
    }
    
    if (!empty($product['image'])) {
        $imgPath = $basePath . 'assets/img/products/' . $product['image'];
        echo "<p><strong>Single Image Field:</strong> " . htmlspecialchars($product['image']) . "</p>";
        echo "<p><strong>Full Path:</strong> <a href='$imgPath' target='_blank'>$imgPath</a></p>";
        echo "<p><strong>File Exists:</strong> " . (file_exists('assets/img/products/' . $product['image']) ? 'YES ✓' : 'NO ✗') . "</p>";
        if (file_exists('assets/img/products/' . $product['image'])) {
            echo "<p><img src='$imgPath' style='max-width: 200px; border: 1px solid #ddd; padding: 5px;' alt='Test Image'></p>";
        }
    }
} else {
    echo "<p style='color: red;'><strong>Error:</strong> Invalid JSON or missing product data.</p>";
}
?>

<?php
/**
 * Test Product Description
 * Debug script to check if descriptions are being saved
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

if (!isLoggedIn() || !isSeller()) {
    die('Unauthorized');
}

$conn = getDBConnection();
$seller_id = getUserId();

// Get the most recent product
$stmt = $conn->prepare("SELECT id, title, description, status, created_at FROM products WHERE seller_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>Recent Products - Description Check</h2>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr><th>ID</th><th>Title</th><th>Description</th><th>Status</th><th>Created</th></tr>";

while ($product = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($product['id']) . "</td>";
    echo "<td>" . htmlspecialchars($product['title']) . "</td>";
    echo "<td style='max-width: 400px;'>";
    if (!empty($product['description'])) {
        echo "<strong>Length:</strong> " . strlen($product['description']) . " chars<br>";
        echo "<strong>Preview:</strong> " . htmlspecialchars(substr($product['description'], 0, 100)) . "...";
    } else {
        echo "<span style='color: red;'>EMPTY/NULL</span>";
    }
    echo "</td>";
    echo "<td>" . htmlspecialchars($product['status']) . "</td>";
    echo "<td>" . htmlspecialchars($product['created_at']) . "</td>";
    echo "</tr>";
}

echo "</table>";

$stmt->close();
closeDBConnection($conn);
?>

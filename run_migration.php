<?php
/**
 * Run Profile Tables Migration
 * Makola - Creates user_addresses and user_payment_methods tables
 */
require_once 'config/db.php';
require_once 'config/paths.php';

$conn = getDBConnection();

if (!$conn) {
    die("Database connection failed");
}

echo "<h2>Running Profile Tables Migration</h2>";

// Read migration file
$migration_sql = file_get_contents(__DIR__ . '/migration_profile_tables.sql');

// Split by semicolons and execute each statement
$statements = array_filter(
    array_map('trim', explode(';', $migration_sql)),
    function($stmt) {
        return !empty($stmt) && 
               !preg_match('/^--/', $stmt) && 
               !preg_match('/^CREATE TABLE IF NOT EXISTS/', $stmt) === false;
    }
);

// Actually, let's execute the full SQL
$statements = [
    "CREATE TABLE IF NOT EXISTS user_addresses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        label VARCHAR(50) DEFAULT 'Home',
        full_name VARCHAR(255) NOT NULL,
        phone VARCHAR(20),
        address_line1 VARCHAR(255) NOT NULL,
        address_line2 VARCHAR(255),
        city VARCHAR(100) NOT NULL,
        region VARCHAR(100) NOT NULL,
        postal_code VARCHAR(20),
        is_default TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_user (user_id),
        INDEX idx_default (user_id, is_default)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    "CREATE TABLE IF NOT EXISTS user_payment_methods (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        type ENUM('card', 'mobile_money') NOT NULL,
        card_number VARCHAR(20),
        card_holder VARCHAR(255),
        expiry_month INT,
        expiry_year INT,
        mobile_money_number VARCHAR(20),
        mobile_money_provider VARCHAR(50),
        is_default TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_user (user_id),
        INDEX idx_default (user_id, is_default)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
];

$success_count = 0;
$error_count = 0;

foreach ($statements as $index => $sql) {
    if (empty(trim($sql))) continue;
    
    echo "<p>Executing statement " . ($index + 1) . "...</p>";
    
    if ($conn->multi_query($sql)) {
        do {
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->next_result());
        
        echo "<p style='color: green;'>✓ Statement " . ($index + 1) . " executed successfully</p>";
        $success_count++;
    } else {
        echo "<p style='color: red;'>✗ Error executing statement " . ($index + 1) . ": " . $conn->error . "</p>";
        $error_count++;
    }
}

// Verify tables were created
echo "<h3>Verification</h3>";

$tables_to_check = ['user_addresses', 'user_payment_methods'];

foreach ($tables_to_check as $table) {
    $check = $conn->query("SHOW TABLES LIKE '$table'");
    if ($check && $check->num_rows > 0) {
        echo "<p style='color: green;'>✓ Table '$table' exists</p>";
        
        // Show columns
        $columns = $conn->query("SHOW COLUMNS FROM $table");
        echo "<ul>";
        while ($col = $columns->fetch_assoc()) {
            echo "<li>" . $col['Field'] . " (" . $col['Type'] . ")</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>✗ Table '$table' does not exist</p>";
    }
}

closeDBConnection($conn);

echo "<h3>Migration Complete</h3>";
echo "<p>Success: $success_count | Errors: $error_count</p>";
echo "<p><a href='profile.php'>Go to Profile Page</a></p>";
?>

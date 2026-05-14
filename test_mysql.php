<?php
// Test MySQL connection
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=taxlegal_blog', 'root', 'root75');
    echo "MySQL connection successful!\n";
    
    // Test if we can create tables
    $pdo->exec("CREATE TABLE IF NOT EXISTS test_table (id INT AUTO_INCREMENT PRIMARY KEY)");
    echo "Table creation successful!\n";
    
    $pdo->exec("DROP TABLE IF EXISTS test_table");
    echo "Table drop successful!\n";
    
} catch (PDOException $e) {
    echo "MySQL connection failed: " . $e->getMessage() . "\n";
}

// Check available PDO drivers
echo "Available PDO drivers: " . implode(', ', PDO::getAvailableDrivers()) . "\n";
?>

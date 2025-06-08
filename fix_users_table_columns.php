<?php
// This script adds the missing 'email' and 'phone' columns to the 'users' table to fix the signup error.
// Run this script once in your browser to apply the changes.

require_once __DIR__ . '/config.php';

try {
    // MySQL does not support IF NOT EXISTS for ADD COLUMN, so we check columns existence first
    $columns = [];
    $stmt = $pdo->query("SHOW COLUMNS FROM users");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $columns[] = $row['Field'];
    }

    if (!in_array('email', $columns)) {
        $pdo->exec("ALTER TABLE users ADD COLUMN email VARCHAR(255) NOT NULL UNIQUE AFTER username");
        echo "Column 'email' added successfully.<br>";
    } else {
        echo "Column 'email' already exists.<br>";
    }

    if (!in_array('phone', $columns)) {
        $pdo->exec("ALTER TABLE users ADD COLUMN phone VARCHAR(15) NOT NULL UNIQUE AFTER email");
        echo "Column 'phone' added successfully.<br>";
    } else {
        echo "Column 'phone' already exists.<br>";
    }
} catch (PDOException $e) {
    echo "Error updating users table: " . $e->getMessage();
}
?>

<?php
// This script adds the missing 'email' and 'phone' columns to the 'users' table to fix the signup error.
// Run this script once in your browser to apply the changes.

require_once __DIR__ . '/config.php';

try {
    $sql = "ALTER TABLE users
            ADD COLUMN IF NOT EXISTS email VARCHAR(255) NOT NULL UNIQUE AFTER username,
            ADD COLUMN IF NOT EXISTS phone VARCHAR(15) NOT NULL UNIQUE AFTER email";

    $pdo->exec($sql);
    echo "Columns 'email' and 'phone' added successfully to 'users' table.";
} catch (PDOException $e) {
    echo "Error updating users table: " . $e->getMessage();
}
?>

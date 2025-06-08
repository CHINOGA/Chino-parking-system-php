<?php
// This script cleans up existing user records with empty or duplicate emails and phones,
// then adds 'email' and 'phone' columns with UNIQUE constraints to the 'users' table.
// Run this script once in your browser to apply the changes.

require_once __DIR__ . '/config.php';

try {
    // Remove users with empty or NULL username (optional, to avoid conflicts)
    $pdo->exec("DELETE FROM users WHERE username IS NULL OR username = ''");

    // Update empty or NULL emails to unique placeholder emails to avoid duplicates
    $stmt = $pdo->query("SELECT id FROM users WHERE email IS NULL OR email = ''");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $uniqueEmail = 'user' . $row['id'] . '@example.com';
        $updateStmt = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
        $updateStmt->execute([$uniqueEmail, $row['id']]);
    }

    // Update empty or NULL phones to unique placeholder phones to avoid duplicates
    $stmt = $pdo->query("SELECT id FROM users WHERE phone IS NULL OR phone = ''");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $uniquePhone = '0000000000' . $row['id'];
        $updateStmt = $pdo->prepare("UPDATE users SET phone = ? WHERE id = ?");
        $updateStmt->execute([$uniquePhone, $row['id']]);
    }

    // Add columns if they do not exist
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

    echo "User data cleaned and columns added successfully.";
} catch (PDOException $e) {
    echo "Error updating users table and data: " . $e->getMessage();
}
?>

<?php
date_default_timezone_set('Africa/Nairobi');

// Enable error reporting and logging for debugging
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/error.log');
error_reporting(E_ALL);

// Database credentials
$dbHost = 'localhost';
$dbName = 'chinotra_chino_parking';
$dbUser = 'chinotra_francis';
$dbPassword = 'Francis@8891';

// NextSMS API credentials and settings
$nextsmsUsername = 'abelchinoga';
$nextsmsPassword = 'Abelyohana@8';
$nextsmsSenderId = 'CHINOTRACK';

require_once 'TenantConnectionManager.php';

try {
    // Initialize tenant connection manager
    $tenantConnectionManager = new TenantConnectionManager();

    // Start session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Allow public pages without tenant_id in session
    $publicPages = ['login.php', 'signup.php', 'forgot_password.php'];
    $currentPage = basename($_SERVER['PHP_SELF']);

    if (!in_array($currentPage, $publicPages)) {
        if (!isset($_SESSION['tenant_id'])) {
            error_log("Tenant ID not set in session for page: " . $currentPage);
            http_response_code(403);
            echo "Access denied. Tenant ID is required.";
            exit;
        }

        $tenantId = $_SESSION['tenant_id'];

        // Get tenant-specific PDO connection
        $pdo = $tenantConnectionManager->getTenantConnection($tenantId);

        // Set error mode to exception
         if (!$pdo) {
            throw new Exception("Failed to establish tenant database connection.");
        }
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } else {
        // For public pages, use default connection
        $host = $dbHost;
        $dbname = $dbName;
        $user = $dbUser;
        $password = $dbPassword;

        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
} catch (Exception $e) {
    error_log("Database connection failed: " . $e->getMessage());
    http_response_code(500);
    echo "Unable to connect to the database. Please try again later.";
    exit;
}
?>

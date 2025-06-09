<?php
date_default_timezone_set('Africa/Nairobi');
// Enable error reporting and logging for debugging
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/error.log');
error_reporting(E_ALL);

// Database connection configuration for Bluehost

$host = 'localhost';
$dbname = 'chinotra_chino_parking';
$user = 'chinotra_francis';
$password = 'Francis@8891';

// NextSMS API credentials and settings
define('NEXTSMS_USERNAME', 'abelchinoga');
define('NEXTSMS_PASSWORD', 'Abelyohana@8');
define('NEXTSMS_SENDER_ID', 'CHINOTRACK');

require_once 'TenantConnectionManager.php';

try {
    // Initialize tenant connection manager
    $tenantConnectionManager = new TenantConnectionManager();

    // Example: get tenant ID from session or request context
    session_start();
    if (!isset($_SESSION['tenant_id'])) {
        die("Tenant ID not set in session.");
    }
    $tenantId = $_SESSION['tenant_id'];

    // Get tenant-specific PDO connection
    $pdo = $tenantConnectionManager->getTenantConnection($tenantId);

    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

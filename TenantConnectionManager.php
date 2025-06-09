<?php
class TenantConnectionManager {
    private $mainDb;
    private $tenantConnections = [];

    public function __construct() {
        // Main DB connection to fetch tenant info
        $host = 'localhost';
        $dbname = 'chinotra_chino_parking';
        $user = 'chinotra_francis';
        $password = 'Francis@8891';

        try {
            $this->mainDb = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
            $this->mainDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Main database connection failed: " . $e->getMessage());
        }
    }

    public function getTenantConnection($tenantId) {
        if (isset($this->tenantConnections[$tenantId])) {
            return $this->tenantConnections[$tenantId];
        }

        // Fetch tenant database name from tenants table
        $stmt = $this->mainDb->prepare("SELECT user_database FROM tenants WHERE id = ?");
        $stmt->execute([$tenantId]);
        $tenantDbName = $stmt->fetchColumn();

        if (!$tenantDbName) {
            throw new Exception("Tenant database not found for tenant ID: " . $tenantId);
        }

        // Create new PDO connection for tenant database
        try {
            // Adjust credentials as needed per tenant
            $tenantUser = 'tenant_user';
            $tenantPassword = 'tenant_password';

            $tenantDb = new PDO("mysql:host=localhost;dbname=$tenantDbName;charset=utf8mb4", $tenantUser, $tenantPassword);
            $tenantDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->tenantConnections[$tenantId] = $tenantDb;
            return $tenantDb;
        } catch (PDOException $e) {
            throw new Exception("Tenant database connection failed: " . $e->getMessage());
        }
    }
}
?>

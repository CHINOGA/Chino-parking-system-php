-- Drop foreign key constraints if they exist to avoid conflicts
-- Note: MySQL does not support 'IF EXISTS' for DROP FOREIGN KEY, so we check first

SET @fk_name = (SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'users'
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'
                AND CONSTRAINT_NAME = 'fk_users_tenant');

IF @fk_name IS NOT NULL THEN
    ALTER TABLE users DROP FOREIGN KEY fk_users_tenant;
END IF;

SET @fk_name = (SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'vehicles'
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'
                AND CONSTRAINT_NAME = 'fk_vehicles_tenant');

IF @fk_name IS NOT NULL THEN
    ALTER TABLE vehicles DROP FOREIGN KEY fk_vehicles_tenant;
END IF;

SET @fk_name = (SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'parking_entries'
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'
                AND CONSTRAINT_NAME = 'fk_parking_entries_tenant');

IF @fk_name IS NOT NULL THEN
    ALTER TABLE parking_entries DROP FOREIGN KEY fk_parking_entries_tenant;
END IF;

SET @fk_name = (SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'revenue'
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'
                AND CONSTRAINT_NAME = 'fk_revenue_tenant');

IF @fk_name IS NOT NULL THEN
    ALTER TABLE revenue DROP FOREIGN KEY fk_revenue_tenant;
END IF;

-- Add tenant_id columns if they do not exist
ALTER TABLE users
ADD COLUMN IF NOT EXISTS tenant_id INT NOT NULL DEFAULT 1 AFTER id;

ALTER TABLE vehicles
ADD COLUMN IF NOT EXISTS tenant_id INT NOT NULL DEFAULT 1 AFTER id;

ALTER TABLE parking_entries
ADD COLUMN IF NOT EXISTS tenant_id INT NOT NULL DEFAULT 1 AFTER id;

ALTER TABLE revenue
ADD COLUMN IF NOT EXISTS tenant_id INT NOT NULL DEFAULT 1 AFTER id;

-- Insert default tenant record if not exists
INSERT INTO tenants (id, name)
SELECT 1, 'Default Tenant'
WHERE NOT EXISTS (SELECT 1 FROM tenants WHERE id = 1);

-- Update existing records to have tenant_id = 1 if tenant_id is NULL or invalid
UPDATE users u
LEFT JOIN tenants t ON u.tenant_id = t.id
SET u.tenant_id = 1
WHERE t.id IS NULL OR u.tenant_id IS NULL;

UPDATE vehicles v
LEFT JOIN tenants t ON v.tenant_id = t.id
SET v.tenant_id = 1
WHERE t.id IS NULL OR v.tenant_id IS NULL;

UPDATE parking_entries p
LEFT JOIN tenants t ON p.tenant_id = t.id
SET p.tenant_id = 1
WHERE t.id IS NULL OR p.tenant_id IS NULL;

UPDATE revenue r
LEFT JOIN tenants t ON r.tenant_id = t.id
SET r.tenant_id = 1
WHERE t.id IS NULL OR r.tenant_id IS NULL;

-- Add foreign key constraints
ALTER TABLE users
ADD CONSTRAINT fk_users_tenant FOREIGN KEY (tenant_id) REFERENCES tenants(id);

ALTER TABLE vehicles
ADD CONSTRAINT fk_vehicles_tenant FOREIGN KEY (tenant_id) REFERENCES tenants(id);

ALTER TABLE parking_entries
ADD CONSTRAINT fk_parking_entries_tenant FOREIGN KEY (tenant_id) REFERENCES tenants(id);

ALTER TABLE revenue
ADD CONSTRAINT fk_revenue_tenant FOREIGN KEY (tenant_id) REFERENCES tenants(id);

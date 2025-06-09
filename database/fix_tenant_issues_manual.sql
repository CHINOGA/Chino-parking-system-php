-- Drop foreign key constraints if they exist to avoid conflicts
ALTER TABLE users DROP FOREIGN KEY IF EXISTS fk_users_tenant;
ALTER TABLE vehicles DROP FOREIGN KEY IF EXISTS fk_vehicles_tenant;
ALTER TABLE parking_entries DROP FOREIGN KEY IF EXISTS fk_parking_entries_tenant;
ALTER TABLE revenue DROP FOREIGN KEY IF EXISTS fk_revenue_tenant;

-- Add tenant_id column to users table if it does not exist
ALTER TABLE users
ADD COLUMN tenant_id INT NOT NULL DEFAULT 1 AFTER id;

-- Add tenant_id column to vehicles table if it does not exist
ALTER TABLE vehicles
ADD COLUMN tenant_id INT NOT NULL DEFAULT 1 AFTER id;

-- Add tenant_id column to parking_entries table if it does not exist
ALTER TABLE parking_entries
ADD COLUMN tenant_id INT NOT NULL DEFAULT 1 AFTER id;

-- Add tenant_id column to revenue table if it does not exist
ALTER TABLE revenue
ADD COLUMN tenant_id INT NOT NULL DEFAULT 1 AFTER id;

-- Insert default tenant record with id=1 if not exists
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

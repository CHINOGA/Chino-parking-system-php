-- Insert default tenant record with id=1
INSERT INTO tenants (id, name) VALUES (1, 'Default Tenant')
ON DUPLICATE KEY UPDATE name = 'Default Tenant';

-- Update existing users to have tenant_id = 1 if tenant_id is NULL or invalid
UPDATE users u
LEFT JOIN tenants t ON u.tenant_id = t.id
SET u.tenant_id = 1
WHERE t.id IS NULL OR u.tenant_id IS NULL;

-- Now add the foreign key constraint safely
ALTER TABLE users
ADD CONSTRAINT fk_users_tenant FOREIGN KEY (tenant_id) REFERENCES tenants(id);

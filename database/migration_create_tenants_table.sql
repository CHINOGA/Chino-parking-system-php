CREATE TABLE tenants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE users
ADD COLUMN tenant_id INT NOT NULL DEFAULT 1 AFTER id,
ADD CONSTRAINT fk_users_tenant FOREIGN KEY (tenant_id) REFERENCES tenants(id);

ALTER TABLE vehicles
ADD COLUMN tenant_id INT NOT NULL DEFAULT 1 AFTER id,
ADD CONSTRAINT fk_vehicles_tenant FOREIGN KEY (tenant_id) REFERENCES tenants(id);

ALTER TABLE parking_entries
ADD COLUMN tenant_id INT NOT NULL DEFAULT 1 AFTER id,
ADD CONSTRAINT fk_parking_entries_tenant FOREIGN KEY (tenant_id) REFERENCES tenants(id);

ALTER TABLE revenue
ADD COLUMN tenant_id INT NOT NULL DEFAULT 1 AFTER id,
ADD CONSTRAINT fk_revenue_tenant FOREIGN KEY (tenant_id) REFERENCES tenants(id);

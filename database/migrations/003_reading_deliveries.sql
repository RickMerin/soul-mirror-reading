ALTER TABLE leads
    ADD COLUMN mirror_block_slug VARCHAR(40) NULL AFTER gender;

CREATE TABLE IF NOT EXISTS reading_deliveries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    purchase_id BIGINT UNSIGNED NOT NULL,
    lead_id BIGINT UNSIGNED NOT NULL,
    s3_object_key VARCHAR(255) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    generated_at DATETIME NULL,
    emailed_at DATETIME NULL,
    error_message TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_reading_deliveries_purchase_id FOREIGN KEY (purchase_id) REFERENCES purchases(id) ON DELETE CASCADE,
    CONSTRAINT fk_reading_deliveries_lead_id FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE,
    UNIQUE KEY uniq_reading_deliveries_purchase (purchase_id),
    KEY idx_reading_deliveries_lead_status (lead_id, status),
    KEY idx_reading_deliveries_status_created (status, created_at)
);

CREATE TABLE IF NOT EXISTS purchases (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    lead_id BIGINT UNSIGNED NOT NULL,
    clickbank_receipt VARCHAR(120) NULL,
    txn_type VARCHAR(40) NULL,
    status VARCHAR(40) NOT NULL DEFAULT 'pending',
    currency CHAR(3) NULL,
    amount DECIMAL(10,2) NULL,
    items_json JSON NOT NULL,
    raw_ins_json JSON NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_purchases_lead_id FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE,
    UNIQUE KEY uniq_purchases_receipt (clickbank_receipt),
    KEY idx_purchases_lead_status (lead_id, status),
    KEY idx_purchases_created_at (created_at)
);

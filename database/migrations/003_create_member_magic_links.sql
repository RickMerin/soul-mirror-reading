CREATE TABLE IF NOT EXISTS member_magic_links (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    lead_id BIGINT UNSIGNED NOT NULL,
    token_hash CHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    used_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_member_magic_links_lead_id FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE,
    UNIQUE KEY uniq_member_magic_links_token_hash (token_hash),
    KEY idx_member_magic_links_lead_id (lead_id),
    KEY idx_member_magic_links_expires_used (expires_at, used_at)
);

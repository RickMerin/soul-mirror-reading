CREATE TABLE IF NOT EXISTS leads (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid CHAR(36) NOT NULL,
    email VARCHAR(254) NOT NULL,
    name VARCHAR(120) NOT NULL,
    dob VARCHAR(10) NOT NULL,
    gender VARCHAR(16) NOT NULL,
    cards_json JSON NOT NULL,
    reading_payload_json JSON NULL,
    funnel_step VARCHAR(64) NOT NULL DEFAULT 'unlock-reading',
    landing_source VARCHAR(120) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_leads_uuid (uuid),
    UNIQUE KEY uniq_leads_email (email),
    KEY idx_leads_created_at (created_at)
);

ALTER TABLE purchases
    ADD COLUMN access_until DATETIME NULL AFTER status;

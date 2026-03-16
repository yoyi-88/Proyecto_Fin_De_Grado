ALTER TABLE users 
ADD reset_token VARCHAR(64) NULL DEFAULT NULL AFTER password,
ADD reset_token_expires_at DATETIME NULL DEFAULT NULL AFTER reset_token;
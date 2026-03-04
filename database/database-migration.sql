-- ============================================================
-- Admin Panel Migration
-- Run once on the production database before using /panel/
-- ============================================================

-- Step 1: Add password_hash column to admin_users
ALTER TABLE `admin_users`
  ADD COLUMN `password_hash` VARCHAR(255) NOT NULL DEFAULT ''
  AFTER `email`;

-- Step 2: Insert first superadmin account
-- Generate a bcrypt hash locally with:
--   php -r "echo password_hash('YourChosenPassword', PASSWORD_BCRYPT);"
-- Then replace REPLACE_WITH_BCRYPT_HASH below.

INSERT INTO `admin_users` (email, password_hash, full_name, role, is_active)
VALUES (
    'admin@samutkarshias.in',
    'REPLACE_WITH_BCRYPT_HASH',
    'Admin',
    'superadmin',
    1
);

-- ============================================================
-- Admin Panel Migration
-- Run once on the production database before using /panel/
-- ============================================================

-- Step 1: Add password_hash column to admin_users
ALTER TABLE `admin_users`
  ADD COLUMN `password_hash` VARCHAR(255) NOT NULL DEFAULT ''
  AFTER `email`;

-- Step 2: Insert superadmin account
INSERT INTO `admin_users` (email, password_hash, full_name, role, is_active)
VALUES (
    'admin@samutkarshias.in',
    '$2y$12$YigbXkmec88TE5Q/GvhbeepV6Q7gTJsRsXP4.vGlFKUNWI1H8h4Du',
    'Admin',
    'superadmin',
    1
);

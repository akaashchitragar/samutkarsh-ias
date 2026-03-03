-- ============================================================
--  Samutkarsh IAS Academy — MySQL Schema
--  Run this once on your cPanel MySQL database.
--  Compatible with MySQL 5.7+ / MariaDB 10.3+
--
--  Enrollment tables (3–6) are used by:
--  - sections/admissions.php (multi-step enrolment form)
--  - submit_enrollment.php (course → table mapping)
--  Course mapping: Shraddha-Medha → shraddhamedha_enrollments;
--  Utkarsh → utkarsh_enrollments; IAS Coaching & Comprehensive Program
--  → comprehensive_enrollments; Mentorship Program → mentorship_enrollments.
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- 1. CENTERS
-- ============================================================
CREATE TABLE IF NOT EXISTS `centers` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(120) NOT NULL,
  `address`     VARCHAR(255) NOT NULL,
  `city`        VARCHAR(80)  NOT NULL,
  `state`       VARCHAR(80)  NOT NULL DEFAULT 'Karnataka',
  `pincode`     VARCHAR(10)  NOT NULL,
  `phone`       VARCHAR(20)  NOT NULL,
  `email`       VARCHAR(150)          DEFAULT NULL,
  `manager`     VARCHAR(120)          DEFAULT NULL,
  `capacity`    SMALLINT UNSIGNED     DEFAULT NULL,
  `status`      ENUM('active','inactive','maintenance') NOT NULL DEFAULT 'active',
  `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Used by admissions form (sections/admissions.php) "Preferred Study Centre" dropdown.
INSERT INTO `centers` (`name`, `address`, `city`, `pincode`, `phone`) VALUES
  ('Hubballi (Main Campus)', 'KLE Tech, BVB Campus, Vidyanagar', 'Hubballi', '580031', '+91 96634 24767'),
  ('Gangavati',              'Main Road, Gangavati',               'Gangavati', '583227', '+91 96634 24767'),
  ('Sirsi',                  'Main Road, Sirsi',                  'Sirsi', '581401', '+91 96634 24767'),
  ('Kumta',                  'Main Road, Kumta',                  'Kumta', '581323', '+91 96634 24767'),
  ('Raichuru',               'Station Road, Raichur',             'Raichuru', '584101', '+91 96634 24767'),
  ('Bellari',                'Main Road, Bellari',                'Bellari', '583101', '+91 96634 24767'),
  ('Hagari Bommanhalli',     'Hagari Bommanhalli',                 'Hagari Bommanhalli', '583138', '+91 96634 24767'),
  ('Belagavi',               'Club Road, Near RPD Circle',        'Belagavi', '590006', '+91 96634 24767'),
  ('Bengaluru',              '5th Block, 1097, 18th B Main Rd, Rajajinagar', 'Bengaluru', '560010', '+91 96634 24767');


-- ============================================================
-- 2. INQUIRIES (Callback request form)
-- ============================================================
CREATE TABLE IF NOT EXISTS `inquiries` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `full_name`  VARCHAR(100) NOT NULL,
  `phone`      VARCHAR(15)  NOT NULL,
  `email`      VARCHAR(150)          DEFAULT NULL,
  `course`     VARCHAR(100) NOT NULL,
  `center`     VARCHAR(120) NOT NULL,
  `message`    TEXT                  DEFAULT NULL,
  `is_called`  TINYINT(1)  NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_inquiries_phone      ON `inquiries` (`phone`);
CREATE INDEX idx_inquiries_course     ON `inquiries` (`course`);
CREATE INDEX idx_inquiries_created_at ON `inquiries` (`created_at`);


-- ============================================================
-- 3. ENROLLMENTS — Shraddha-Medha (Classes 6–9)
-- ============================================================
-- Source: sections/admissions.php (multi-step form), submit_enrollment.php.
-- Course "Shraddha-Medha" maps here. Form fields: full_name, father_name,
-- mother_name (optional, can be ''), address, date_of_birth, gender,
-- school_name, class_standard, center, father_phone, mother_phone, email, caste_category.
CREATE TABLE IF NOT EXISTS `shraddhamedha_enrollments` (
  `id`             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `full_name`      VARCHAR(100) NOT NULL,
  `father_name`    VARCHAR(100) NOT NULL,
  `mother_name`    VARCHAR(100) NOT NULL DEFAULT '',
  `address`        TEXT         NOT NULL,
  `date_of_birth`  DATE                  DEFAULT NULL,
  `gender`         ENUM('Male','Female','Other') NOT NULL,
  `school_name`    VARCHAR(150) NOT NULL,
  `class_standard` VARCHAR(50)  NOT NULL,
  `center`         VARCHAR(120) NOT NULL,
  `father_phone`   VARCHAR(15)  NOT NULL,
  `mother_phone`   VARCHAR(15)           DEFAULT NULL,
  `email`          VARCHAR(150)          DEFAULT NULL,
  `caste_category` VARCHAR(50)           DEFAULT NULL,
  `status`         ENUM('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at`     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_sm_enroll_email      ON `shraddhamedha_enrollments` (`email`);
CREATE INDEX idx_sm_enroll_center     ON `shraddhamedha_enrollments` (`center`);
CREATE INDEX idx_sm_enroll_created_at ON `shraddhamedha_enrollments` (`created_at`);


-- ============================================================
-- 4. ENROLLMENTS — Utkarsh Program (College Level)
-- ============================================================
-- Source: sections/admissions.php, submit_enrollment.php. Course "Utkarsh" maps here.
-- Form fields: full_name, father_name, mother_name, address, date_of_birth, gender,
-- college_name, stream, center, phone_number, whatsapp_number, email, district, caste_category.
CREATE TABLE IF NOT EXISTS `utkarsh_enrollments` (
  `id`             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `full_name`      VARCHAR(100) NOT NULL,
  `father_name`    VARCHAR(100) NOT NULL,
  `mother_name`    VARCHAR(100) NOT NULL,
  `address`        TEXT         NOT NULL,
  `date_of_birth`  DATE                  DEFAULT NULL,
  `gender`         ENUM('Male','Female','Other') NOT NULL,
  `college_name`   VARCHAR(150) NOT NULL,
  `stream`         VARCHAR(100) NOT NULL,
  `center`         VARCHAR(120) NOT NULL,
  `phone_number`   VARCHAR(15)  NOT NULL,
  `whatsapp_number` VARCHAR(15)          DEFAULT NULL,
  `email`          VARCHAR(150)          DEFAULT NULL,
  `district`       VARCHAR(80)           DEFAULT NULL,
  `caste_category` VARCHAR(50)           DEFAULT NULL,
  `status`         ENUM('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at`     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_utkarsh_email      ON `utkarsh_enrollments` (`email`);
CREATE INDEX idx_utkarsh_center     ON `utkarsh_enrollments` (`center`);
CREATE INDEX idx_utkarsh_created_at ON `utkarsh_enrollments` (`created_at`);


-- ============================================================
-- 5. ENROLLMENTS — Comprehensive Program / IAS Coaching
-- ============================================================
-- Source: sections/admissions.php, submit_enrollment.php.
-- Courses "Comprehensive Program" and "IAS Coaching" map here.
-- Form fields: same as Utkarsh (college_name, stream, phone_number, whatsapp_number, etc.).
CREATE TABLE IF NOT EXISTS `comprehensive_enrollments` (
  `id`             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `full_name`      VARCHAR(100) NOT NULL,
  `father_name`    VARCHAR(100) NOT NULL,
  `mother_name`    VARCHAR(100) NOT NULL,
  `address`        TEXT         NOT NULL,
  `date_of_birth`  DATE                  DEFAULT NULL,
  `gender`         ENUM('Male','Female','Other') NOT NULL,
  `college_name`   VARCHAR(150) NOT NULL,
  `stream`         VARCHAR(100) NOT NULL,
  `center`         VARCHAR(120) NOT NULL,
  `phone_number`   VARCHAR(15)  NOT NULL,
  `whatsapp_number` VARCHAR(15)          DEFAULT NULL,
  `email`          VARCHAR(150)          DEFAULT NULL,
  `district`       VARCHAR(80)           DEFAULT NULL,
  `caste_category` VARCHAR(50)           DEFAULT NULL,
  `status`         ENUM('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at`     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_comp_email      ON `comprehensive_enrollments` (`email`);
CREATE INDEX idx_comp_center     ON `comprehensive_enrollments` (`center`);
CREATE INDEX idx_comp_created_at ON `comprehensive_enrollments` (`created_at`);


-- ============================================================
-- 6. ENROLLMENTS — Mentorship Program
-- ============================================================
-- Source: sections/admissions.php, submit_enrollment.php. Course "Mentorship Program" maps here.
-- Form fields: same as Utkarsh; college_name and stream are optional (nullable).
CREATE TABLE IF NOT EXISTS `mentorship_enrollments` (
  `id`             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `full_name`      VARCHAR(100) NOT NULL,
  `father_name`    VARCHAR(100) NOT NULL,
  `mother_name`    VARCHAR(100) NOT NULL,
  `address`        TEXT         NOT NULL,
  `date_of_birth`  DATE                  DEFAULT NULL,
  `gender`         ENUM('Male','Female','Other') NOT NULL,
  `college_name`   VARCHAR(150)          DEFAULT NULL,
  `stream`         VARCHAR(100)          DEFAULT NULL,
  `center`         VARCHAR(120) NOT NULL,
  `phone_number`   VARCHAR(15)  NOT NULL,
  `whatsapp_number` VARCHAR(15)          DEFAULT NULL,
  `email`          VARCHAR(150)          DEFAULT NULL,
  `district`       VARCHAR(80)           DEFAULT NULL,
  `caste_category` VARCHAR(50)           DEFAULT NULL,
  `status`         ENUM('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at`     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_ment_email      ON `mentorship_enrollments` (`email`);
CREATE INDEX idx_ment_center     ON `mentorship_enrollments` (`center`);
CREATE INDEX idx_ment_created_at ON `mentorship_enrollments` (`created_at`);


-- ============================================================
-- 7. ADMISSIONS — Paid (with transaction IDs) — shared structure
-- ============================================================
-- Shraddha-Medha paid admissions
CREATE TABLE IF NOT EXISTS `shraddhamedha_admissions` (
  `id`                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `full_name`            VARCHAR(100) NOT NULL,
  `father_name`          VARCHAR(100) NOT NULL,
  `mother_name`          VARCHAR(100) NOT NULL,
  `address`              TEXT         NOT NULL,
  `date_of_birth`        DATE                  DEFAULT NULL,
  `gender`               ENUM('Male','Female','Other') NOT NULL,
  `school_name`          VARCHAR(150) NOT NULL,
  `class_standard`       VARCHAR(50)  NOT NULL,
  `center`               VARCHAR(120) NOT NULL,
  `father_phone`         VARCHAR(15)  NOT NULL,
  `mother_phone`         VARCHAR(15)           DEFAULT NULL,
  `email`                VARCHAR(150)          DEFAULT NULL,
  `caste_category`       VARCHAR(50)           DEFAULT NULL,
  `transaction_id`       VARCHAR(100) NOT NULL UNIQUE,
  `admission_fee`        DECIMAL(10,2)         DEFAULT NULL,
  `admission_date`       DATE                  DEFAULT NULL,
  `status`               ENUM('active','inactive','cancelled') NOT NULL DEFAULT 'active',
  `payment_status`       ENUM('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `payment_amount`       DECIMAL(10,2)         DEFAULT NULL,
  `payment_date`         DATETIME              DEFAULT NULL,
  `payment_method`       VARCHAR(50)           DEFAULT 'phonepe',
  `created_at`           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_sm_adm_tx_id      ON `shraddhamedha_admissions` (`transaction_id`);
CREATE INDEX idx_sm_adm_email      ON `shraddhamedha_admissions` (`email`);
CREATE INDEX idx_sm_adm_center     ON `shraddhamedha_admissions` (`center`);
CREATE INDEX idx_sm_adm_payment    ON `shraddhamedha_admissions` (`payment_status`);
CREATE INDEX idx_sm_adm_created_at ON `shraddhamedha_admissions` (`created_at`);

-- Utkarsh paid admissions
CREATE TABLE IF NOT EXISTS `utkarsh_admissions` (
  `id`                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `full_name`            VARCHAR(100) NOT NULL,
  `father_name`          VARCHAR(100) NOT NULL,
  `mother_name`          VARCHAR(100) NOT NULL,
  `address`              TEXT         NOT NULL,
  `date_of_birth`        DATE                  DEFAULT NULL,
  `gender`               ENUM('Male','Female','Other') NOT NULL,
  `college_name`         VARCHAR(150) NOT NULL,
  `stream`               VARCHAR(100) NOT NULL,
  `center`               VARCHAR(120) NOT NULL,
  `father_phone`         VARCHAR(15)  NOT NULL,
  `mother_phone`         VARCHAR(15)           DEFAULT NULL,
  `email`                VARCHAR(150)          DEFAULT NULL,
  `caste_category`       VARCHAR(50)           DEFAULT NULL,
  `transaction_id`       VARCHAR(100) NOT NULL UNIQUE,
  `admission_fee`        DECIMAL(10,2)         DEFAULT NULL,
  `admission_date`       DATE                  DEFAULT NULL,
  `status`               ENUM('active','inactive','cancelled') NOT NULL DEFAULT 'active',
  `payment_status`       ENUM('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `payment_amount`       DECIMAL(10,2)         DEFAULT NULL,
  `payment_date`         DATETIME              DEFAULT NULL,
  `payment_method`       VARCHAR(50)           DEFAULT 'phonepe',
  `created_at`           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_ut_adm_tx_id      ON `utkarsh_admissions` (`transaction_id`);
CREATE INDEX idx_ut_adm_email      ON `utkarsh_admissions` (`email`);
CREATE INDEX idx_ut_adm_center     ON `utkarsh_admissions` (`center`);
CREATE INDEX idx_ut_adm_payment    ON `utkarsh_admissions` (`payment_status`);
CREATE INDEX idx_ut_adm_created_at ON `utkarsh_admissions` (`created_at`);

-- Comprehensive Program paid admissions
CREATE TABLE IF NOT EXISTS `comprehensive_admissions` (
  `id`                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `full_name`            VARCHAR(100) NOT NULL,
  `father_name`          VARCHAR(100) NOT NULL,
  `mother_name`          VARCHAR(100) NOT NULL,
  `address`              TEXT         NOT NULL,
  `date_of_birth`        DATE                  DEFAULT NULL,
  `gender`               ENUM('Male','Female','Other') NOT NULL,
  `college_name`         VARCHAR(150) NOT NULL,
  `stream`               VARCHAR(100) NOT NULL,
  `center`               VARCHAR(120) NOT NULL,
  `father_phone`         VARCHAR(15)  NOT NULL,
  `mother_phone`         VARCHAR(15)           DEFAULT NULL,
  `email`                VARCHAR(150)          DEFAULT NULL,
  `caste_category`       VARCHAR(50)           DEFAULT NULL,
  `transaction_id`       VARCHAR(100) NOT NULL UNIQUE,
  `admission_fee`        DECIMAL(10,2)         DEFAULT NULL,
  `admission_date`       DATE                  DEFAULT NULL,
  `status`               ENUM('active','inactive','cancelled') NOT NULL DEFAULT 'active',
  `payment_status`       ENUM('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `payment_amount`       DECIMAL(10,2)         DEFAULT NULL,
  `payment_date`         DATETIME              DEFAULT NULL,
  `payment_method`       VARCHAR(50)           DEFAULT 'phonepe',
  `created_at`           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_comp_adm_tx_id      ON `comprehensive_admissions` (`transaction_id`);
CREATE INDEX idx_comp_adm_email      ON `comprehensive_admissions` (`email`);
CREATE INDEX idx_comp_adm_center     ON `comprehensive_admissions` (`center`);
CREATE INDEX idx_comp_adm_payment    ON `comprehensive_admissions` (`payment_status`);
CREATE INDEX idx_comp_adm_created_at ON `comprehensive_admissions` (`created_at`);

-- Mentorship Program paid admissions
CREATE TABLE IF NOT EXISTS `mentorship_admissions` (
  `id`                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `full_name`            VARCHAR(100) NOT NULL,
  `father_name`          VARCHAR(100) NOT NULL,
  `mother_name`          VARCHAR(100) NOT NULL,
  `address`              TEXT         NOT NULL,
  `date_of_birth`        DATE                  DEFAULT NULL,
  `gender`               ENUM('Male','Female','Other') NOT NULL,
  `college_name`         VARCHAR(150)          DEFAULT NULL,
  `stream`               VARCHAR(100)          DEFAULT NULL,
  `center`               VARCHAR(120) NOT NULL,
  `father_phone`         VARCHAR(15)  NOT NULL,
  `mother_phone`         VARCHAR(15)           DEFAULT NULL,
  `email`                VARCHAR(150)          DEFAULT NULL,
  `caste_category`       VARCHAR(50)           DEFAULT NULL,
  `transaction_id`       VARCHAR(100) NOT NULL UNIQUE,
  `admission_fee`        DECIMAL(10,2)         DEFAULT NULL,
  `admission_date`       DATE                  DEFAULT NULL,
  `status`               ENUM('active','inactive','cancelled') NOT NULL DEFAULT 'active',
  `payment_status`       ENUM('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `payment_amount`       DECIMAL(10,2)         DEFAULT NULL,
  `payment_date`         DATETIME              DEFAULT NULL,
  `payment_method`       VARCHAR(50)           DEFAULT 'phonepe',
  `created_at`           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_ment_adm_tx_id      ON `mentorship_admissions` (`transaction_id`);
CREATE INDEX idx_ment_adm_email      ON `mentorship_admissions` (`email`);
CREATE INDEX idx_ment_adm_center     ON `mentorship_admissions` (`center`);
CREATE INDEX idx_ment_adm_payment    ON `mentorship_admissions` (`payment_status`);
CREATE INDEX idx_ment_adm_created_at ON `mentorship_admissions` (`created_at`);


-- ============================================================
-- 8. RESULTS (PDF exam results)
-- ============================================================
CREATE TABLE IF NOT EXISTS `results` (
  `id`             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `course_name`    ENUM(
                     'Shraddha-Medha',
                     'Utkarsh',
                     'Comprehensive Program',
                     'Mentorship Program',
                     'IAS Coaching'
                   ) NOT NULL,
  `title`          VARCHAR(255) NOT NULL,
  `description`    TEXT                  DEFAULT NULL,
  `pdf_url`        VARCHAR(500) NOT NULL,
  `exam_date`      DATE                  DEFAULT NULL,
  `result_type`    ENUM(
                     'Entrance Exam',
                     'Mock Test',
                     'Final Exam',
                     'Selection List',
                     'Merit List'
                   ) NOT NULL DEFAULT 'Mock Test',
  `center_id`      INT UNSIGNED          DEFAULT NULL,
  `published_date` DATE         NOT NULL DEFAULT (CURRENT_DATE),
  `is_active`      TINYINT(1)  NOT NULL DEFAULT 1,
  `created_at`     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_results_center FOREIGN KEY (`center_id`) REFERENCES `centers`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_results_course         ON `results` (`course_name`);
CREATE INDEX idx_results_type           ON `results` (`result_type`);
CREATE INDEX idx_results_published      ON `results` (`published_date`);
CREATE INDEX idx_results_active         ON `results` (`is_active`);
CREATE INDEX idx_results_center         ON `results` (`center_id`);
CREATE INDEX idx_results_created_at     ON `results` (`created_at`);


-- ============================================================
-- 9. TESTIMONIALS
-- ============================================================
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`          VARCHAR(100) NOT NULL,
  `batch`         VARCHAR(100) NOT NULL,
  `photo_url`     VARCHAR(500)          DEFAULT NULL,
  `quote`         TEXT         NOT NULL,
  `rating`        TINYINT UNSIGNED NOT NULL DEFAULT 5,
  `is_active`     TINYINT(1)  NOT NULL DEFAULT 1,
  `display_order` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `created_at`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `testimonials` (`name`, `batch`, `photo_url`, `quote`, `rating`, `display_order`) VALUES
  ('Ananya Rao',    'IAS 2022 Batch', 'https://i.pravatar.cc/80?img=47',
   'The mentorship program at Samutkarsh was a game changer for me. The faculty\'s personal attention helped me overcome my fear of answer writing.', 5, 1),
  ('Rahul Kulkarni','KAS Rank 4',     'https://i.pravatar.cc/80?img=12',
   'Detailed study material and rigorous mock tests mirrored the actual exam environment perfectly. Highly recommended for serious aspirants.', 5, 2),
  ('Priya Desai',   'IPS 2023 Batch', 'https://i.pravatar.cc/80?img=44',
   'The library facilities and the peer group motivation kept me going during the tough phases of preparation. Thank you Samutkarsh!', 5, 3);


-- ============================================================
-- 10. ADMIN USERS (for future admin panel)
-- ============================================================
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `email`      VARCHAR(150) NOT NULL UNIQUE,
  `full_name`  VARCHAR(100) NOT NULL,
  `role`       ENUM('superadmin','admin','viewer') NOT NULL DEFAULT 'admin',
  `is_active`  TINYINT(1)  NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 11. ADMIN OTPs (email-based login)
-- ============================================================
CREATE TABLE IF NOT EXISTS `admin_otps` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `email`      VARCHAR(150) NOT NULL,
  `otp`        CHAR(6)      NOT NULL,
  `expires_at` DATETIME     NOT NULL,
  `used`       TINYINT(1)  NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_otps_email      ON `admin_otps` (`email`);
CREATE INDEX idx_otps_expires_at ON `admin_otps` (`expires_at`);

SET FOREIGN_KEY_CHECKS = 1;
-- ============================================================
-- Schema complete. Total tables: 11
-- ============================================================

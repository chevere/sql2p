CREATE TABLE IF NOT EXISTS `company_employee` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `datetime_utc` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `name` VARCHAR(255) NOT NULL,
    `initials` VARCHAR(10) NULL,
    `phone_number` VARCHAR(100) NULL DEFAULT NULL,
    `photo_url` TEXT NULL DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `hourly_rate` INT UNSIGNED NULL DEFAULT NULL,
    `is_clocked_in` TINYINT(1) NOT NULL DEFAULT 0,
    `jobs_total` INT UNSIGNED NOT NULL DEFAULT 0,
    `seconds_total` BIGINT UNSIGNED NOT NULL DEFAULT 0.0,
    `last_seen_datetime_utc` DATETIME NULL DEFAULT NULL,
    `last_seen_coordinates` POINT NULL DEFAULT NULL,
    `comments_made_total` INT NOT NULL DEFAULT 0,
    `login_method` ENUM('email', 'badge') NULL DEFAULT NULL,
    `email` VARCHAR(255) NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

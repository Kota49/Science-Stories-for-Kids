-- -------------------------------------------
SET AUTOCOMMIT=0;
START TRANSACTION;
SET SQL_QUOTE_SHOW_CREATE = 1;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
-- -------------------------------------------
-- -------------------------------------------
-- -------------------------------------------

-- TABLE `tbl_book_category`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_book_category`;
CREATE TABLE IF NOT EXISTS `tbl_book_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
   `title` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL default '',
  `state_id` int NOT NULL default 1,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_book_category_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_book_category_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_book`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_book`;
CREATE TABLE IF NOT EXISTS `tbl_book` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL default '',
  `category_id` int NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci  NULL ,
  `image_file` varchar(255)  NULL default '',
  `age` varchar(16) NOT NULL default '',
  `price_id` int NOT NULL default 0,
  `price` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL default '',
  `type_id` int NOT NULL default 1,
  `state_id` int NOT NULL default 1,
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_book_category_id` (`category_id`),
  KEY `fk_book_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_book_category_id` FOREIGN KEY (`category_id`) REFERENCES `tbl_book_category` (`id`),
  CONSTRAINT `fk_book_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- -- -------------------------------------------

-- -------------------------------------------
-- TABLE `tbl_book_page`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_book_page`;
CREATE TABLE IF NOT EXISTS `tbl_book_page` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci  NULL ,
  `image_file` varchar(255) COLLATE utf8mb4_unicode_ci not NULL ,
  `page_image` varchar(255) COLLATE utf8mb4_unicode_ci  default NULL ,
  `book_id` int NOT NULL,
  `type_id` int NOT NULL default 1,
  `state_id` int NOT NULL default 1,
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_book_page_book_id` (`book_id`),
  KEY `fk_book_page_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_book_page_book_id` FOREIGN KEY (`book_id`) REFERENCES `tbl_book` (`id`),
  CONSTRAINT `fk_book_page_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- -- -------------------------------------------

-- -------------------------------------------
-- TABLE `tbl_book_audio`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_book_audio`;
CREATE TABLE IF NOT EXISTS `tbl_book_audio` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `description` longtext COLLATE utf8mb4_unicode_ci NULL ,
  `book_id` int NOT NULL,
  `page_id` int NOT NULL,
  `state_id` int NOT NULL default 1,
  `type_id` int NOT NULL default 1,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_book_audio_book_id` (`book_id`),
  KEY `fk_book_audio_created_by_id` (`created_by_id`),
  KEY `fk_book_audio_page_id` (`page_id`),
  CONSTRAINT `fk_book_audio_book_id` FOREIGN KEY (`book_id`) REFERENCES `tbl_book`(`id`),
  CONSTRAINT `fk_book_audio_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user`(`id`),
  CONSTRAINT `fk_book_audio_page_id` FOREIGN KEY (`page_id`) REFERENCES `tbl_book_page`(`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- -- -------------------------------------------
-- -------------------------------------------
-- TABLE `tbl_book_payment`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_book_payment`;
CREATE TABLE IF NOT EXISTS `tbl_book_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `book_id` int NOT NULL,	
  `amount` varchar(255) DEFAULT NULL,
  `currency` varchar(125) NOT NULL, 
  `transaction_id` varchar(255) DEFAULT NULL,
  `payer_id` varchar(255) DEFAULT NULL,
  `value` text DEFAULT NULL,
  `gateway_type` int(11) DEFAULT NULL,
  `payment_status` int(11) DEFAULT NULL,
  `type_id` int NOT NULL default 1,
  `state_id` int NOT NULL default 1,
  `created_by_id` int NOT NULL,
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_payment_created_by_id` (`created_by_id`),
  KEY `fk_payment_book_id` (`book_id`),
  CONSTRAINT `fk_payment_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`),
  CONSTRAINT `fk_payment_book_id` FOREIGN KEY (`book_id`) REFERENCES `tbl_book` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------
-- TABLE `tbl_book_parental_code`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_book_parental_code`;
CREATE TABLE IF NOT EXISTS `tbl_book_parental_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int NOT NULL,	
  `lock` int NOT NULL default 1,
  `type_id` int NOT NULL default 1,
  `state_id` int NOT NULL default 1,
  `created_by_id` int NOT NULL,
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX (`book_id`),
  KEY `fk_book_parental_code_created_by_id` (`created_by_id`),
  KEY `fk_book_parental_code_book_id` (`book_id`),
  CONSTRAINT `fk_book_parental_code_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`),
  CONSTRAINT `fk_book_parental_code_book_id` FOREIGN KEY (`book_id`) REFERENCES `tbl_book` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
COMMIT;
-- --------------------------------------------------------------------------------------
-- END BACKUP
-- -------------------------------------------

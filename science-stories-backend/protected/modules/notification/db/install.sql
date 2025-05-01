
-- -------------------------------------------
SET AUTOCOMMIT=0;
START TRANSACTION;
SET SQL_QUOTE_SHOW_CREATE = 1;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
-- -------------------------------------------
-- -------------------------------------------

-- -------------------------------------------
-- TABLE `tbl_notification`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_notification`;
CREATE TABLE IF NOT EXISTS `tbl_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(1024) NOT NULL,
  `description` text DEFAULT NULL,
  `model_id` int(11) NOT NULL,
  `model_type` varchar(256) NOT NULL,
  `is_read` tinyint(2) DEFAULT '0',
  `state_id` int(11) DEFAULT '0',
  `type_id` int(11) DEFAULT '0',
  `created_on` datetime NOT NULL,
  `to_user_id` int(11) DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------
-- TABLE `tbl_notification_push_notification`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_notification_push_notification`;
CREATE TABLE IF NOT EXISTS `tbl_notification_push_notification` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text DEFAULT NULL,
  `role_type` int DEFAULT '1',
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `type_id` int DEFAULT '0',
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `state_id` (`state_id`),
  KEY `FK_notification_push_notification_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_notification_push_notification_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -- -------------------------------------------
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
COMMIT;
-- --------------------------------------------------------------------------------------
-- END BACKUP
-- -------------------------------------------

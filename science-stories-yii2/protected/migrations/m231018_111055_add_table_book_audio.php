<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author    : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
use yii\db\Migration;

/**
 * php console.php module/migrate
 */
class m231018_111055_add_table_book_audio extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->getTableSchema('{{%book_audio}}');
        if (! isset($table)) {
            $this->execute("DROP TABLE IF EXISTS `tbl_book_audio`;
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
  KEY `fk_book_category_id` (`created_by_id`),
  CONSTRAINT `fk_book_audio_book_id` FOREIGN KEY (`book_id`) REFERENCES `tbl_book`(`id`),
  CONSTRAINT `fk_book_audio_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user`(`id`),
  CONSTRAINT `fk_book_audio_page_id` FOREIGN KEY (`page_id`) REFERENCES `tbl_book_page`(`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->getTableSchema('{{%book_audio}}');
        if (isset($table)) {
            $this->dropTable('{{%book_audio}}');
        }
    }
}
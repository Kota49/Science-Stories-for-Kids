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
class m240226_170233_add_tbl_book_parental_code extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->getTableSchema('{{%book_parental_code}}');
        if (! isset($table)) {
            $this->execute("DROP TABLE IF EXISTS `tbl_book_parental_code`;
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->getTableSchema('{{%book_parental_code}}');
        if (isset($table)) {
            $this->dropTable('{{%book_parental_code}}');
        }
    }
}
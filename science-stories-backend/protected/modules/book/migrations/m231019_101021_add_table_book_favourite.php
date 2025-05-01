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
 *   php console.php module/migrate 
 */
class m231019_101021_add_table_book_favourite extends Migration{
    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%book_favourite}}');

        if (! isset($table)) {
            $this->execute("DROP TABLE IF EXISTS `tbl_book_favourite`;
CREATE TABLE IF NOT EXISTS `tbl_book_favourite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model_id` int(11) NOT NULL,
  `model_type` varchar(256) NOT NULL,
  `title` varchar(256) NOT NULL,
  `state_id` int(11) DEFAULT 1,
  `type_id` int(11)  DEFAULT 0,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_book_favourite_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_book_favourite_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%book_favourite}}');
        if (isset($table)) {
            $this->dropTable('{{%book_favourite}}');
        }
    }
}
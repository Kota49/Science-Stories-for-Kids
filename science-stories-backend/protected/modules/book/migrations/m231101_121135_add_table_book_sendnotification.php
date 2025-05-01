<?php
 /**
 *@copyright : OZVID Technologies Pvt. Ltd. < www.ozvid.com >
 *@author    : Shiv Charan Panjeta < shiv@ozvid.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of OZVID Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
 
use yii\db\Migration;
/**
 *   php console.php module/migrate 
 */
class m231101_121135_add_table_book_sendnotification extends Migration{
    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%book_sendnotification}}');

        if (! isset($table)) {
            $this->execute("DROP TABLE IF EXISTS `tbl_book_sendnotification`;
CREATE TABLE IF NOT EXISTS `tbl_book_sendnotification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL ,
  `title` varchar(256) NOT NULL,
  `state_id` int(11) DEFAULT 0,
  `type_id` int(11)  DEFAULT 0,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_book_sendnotification_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_book_sendnotification_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%book_sendnotification}}');
        if (isset($table)) {
            $this->dropTable('{{%book_sendnotification}}');
        }
    }
}
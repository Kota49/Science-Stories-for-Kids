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
class m240103_160113_add_push_notification extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->getTableSchema('{{%notification_push_notification}}');

        if (! isset($table)) {
            $this->execute("DROP TABLE IF EXISTS `tbl_notification_push_notification`;
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
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    /**
     *
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%notification_push_notification}}');
        if (isset($table)) {
            $this->dropTable('{{%notification_push_notification}}');
        }
    }
}
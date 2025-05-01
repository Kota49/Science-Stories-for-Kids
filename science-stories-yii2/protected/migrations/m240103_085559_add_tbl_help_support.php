<?php
use yii\db\Migration;

/**
 * Class m240103_085559_add_tbl_help_support
 */
class m240103_085559_add_tbl_help_support extends Migration
{

    /**
     *
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = Yii::$app->db->getTableSchema('{{%help_support}}');
        if (! isset($table)) {
            $this->execute("DROP TABLE IF EXISTS `tbl_help_support`;
       CREATE TABLE IF NOT EXISTS `tbl_help_support` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
      `message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `image_file` varchar (255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `type_id` int(11) DEFAULT '0',
      `state_id` int(11) DEFAULT '0',
      `created_on` datetime NOT NULL,
      `created_by_id` int(11) NOT NULL,
       PRIMARY KEY (`id`),
      INDEX (`title`),
      INDEX (`state_id`),
      INDEX (`created_on`),
      INDEX(`created_by_id`),
      KEY `fk_help_support_created_by_id` (`created_by_id`),
      CONSTRAINT `fk_help_support_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
     ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->getTableSchema('{{%help_support}}');
        if (isset($table)) {
            $this->dropTable('{{%help_support}}');
        }
    }
}

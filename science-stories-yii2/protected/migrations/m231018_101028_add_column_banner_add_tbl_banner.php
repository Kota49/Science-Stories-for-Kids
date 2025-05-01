<?php
use yii\db\Migration;

/**
 * Class m230929_084200_add_tbl_banner
 */
class m231018_101028_add_column_banner_add_tbl_banner extends Migration
{

    /**
     * php console.php module/migrate
     */
    public function safeUp()
    {
        $table = Yii::$app->db->getTableSchema('{{%banner}}');
        if (! isset($table)) {
            $this->execute("DROP TABLE IF EXISTS `tbl_banner`;
CREATE TABLE IF NOT EXISTS `tbl_banner` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL default '',
  `description` text COLLATE utf8mb4_unicode_ci  NULL ,
  `image_file` varchar(255)  NULL default '',
  `type_id` int NOT NULL default 1,
  `state_id` int NOT NULL default 1,
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_banner_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->getTableSchema('{{%banner}}');
        if (isset($table)) {
            $this->dropTable('{{%banner}}');
        }
    }
}
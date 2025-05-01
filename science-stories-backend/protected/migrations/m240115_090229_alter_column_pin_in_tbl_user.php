<?php
use yii\db\Migration;

/**
 * Class m240115_090229_alter_column_pin_in_tbl_user
 */
class m240115_090229_alter_column_pin_in_tbl_user extends Migration
{

    /**
     *
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%user}}');
        if (! isset($table->columns['pin'])) {
            $this->execute("ALTER TABLE `tbl_user` CHANGE `pin` `pin` VARCHAR(6) NOT NULL DEFAULT '0'; ;
             ");
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%user}}');
        if (isset($table->columns['pin'])) {
            $this->dropColumn('%user', 'pin');
        }
    }
}

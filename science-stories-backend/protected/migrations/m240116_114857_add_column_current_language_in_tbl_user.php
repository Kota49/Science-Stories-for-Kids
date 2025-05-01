<?php
use yii\db\Migration;

/**
 * Class m240116_114857_add_column_current_language_in_tbl_user
 */
class m240116_114857_add_column_current_language_in_tbl_user extends Migration
{

    /**
     *
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%user}}');
        if (! isset($table->columns['current_language'])) {
            $this->addColumn('{{%user}}', 'current_language', $this->string(32)
                ->defaultValue(NULL));
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%user}}');
        if (isset($table->columns['current_language'])) {
            $this->dropColumn('{{%user}}', 'current_language');
        }
    }
}

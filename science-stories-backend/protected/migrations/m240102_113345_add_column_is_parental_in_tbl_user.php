<?php
use yii\db\Migration;

/**
 * Class m240102_113345_add_column_is_parental_in_tbl_user
 */
class m240102_113345_add_column_is_parental_in_tbl_user extends Migration
{

    /**
     *
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%user}}');
        if (! isset($table->columns['is_parental'])) {
            $this->addColumn('{{%user}}', 'is_parental', $this->integer()
                ->defaultValue(NULL));
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%user}}');
        if (isset($table->columns['is_parental'])) {
            $this->dropColumn('{{%user}}', 'is_parental');
        }
    }
}

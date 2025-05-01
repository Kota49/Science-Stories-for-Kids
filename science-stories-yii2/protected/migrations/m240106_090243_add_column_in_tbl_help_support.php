<?php
use yii\db\Migration;

/**
 * Class m240106_090243_add_column_in_tbl_help_support
 */
class m240106_090243_add_column_in_tbl_help_support extends Migration
{

    /**
     *
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%help_support}}');
        if (! isset($table->columns['email'])) {
            $this->addColumn('{{%help_support}}', 'email', $this->string(255)
                ->defaultValue(NULL));
        }

        if (! isset($table->columns['contact_no'])) {
            $this->addColumn('{{%help_support}}', 'contact_no', $this->integer()
                ->defaultValue(NULL));
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%help_support}}');
        if (isset($table->columns['email'])) {
            $this->dropColumn('{{%help_support}}', 'email');
        }

        if (isset($table->columns['contact_no'])) {
            $this->dropColumn('{{%help_support}}', 'contact_no');
        }
    }
}

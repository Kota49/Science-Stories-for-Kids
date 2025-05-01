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
class m231019_141052_add_column_image_file_tbl_book_audio extends Migration{
    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%book_audio}}');
        if (! isset($table->columns['image_file'])) {
            $this->addColumn('{{%book_audio}}', 'image_file', $this->string(255)
                ->defaultValue(NULL));
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%book_audio}}');
        if (isset($table->columns['image_file'])) {
            $this->dropColumn('{{%book_audio}}', 'image_file');
        }
    }
}
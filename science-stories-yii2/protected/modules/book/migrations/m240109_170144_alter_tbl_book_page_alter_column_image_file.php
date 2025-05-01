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
class m240109_170144_alter_tbl_book_page_alter_column_image_file extends Migration
{
	public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%book_page}}');
        if ( isset($table->columns['image_file'])) {
            $this->alterColumn('{{%book_page}}', 'image_file', $this->string(255)
                ->defaultValue(null));
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%book_page}}');
        if ( isset($table->columns['image_file'])) {
            $this->alterColumn('{{%book_page}}', 'image_file', $this->string(255)
                ->notNull());
        }
    }
}

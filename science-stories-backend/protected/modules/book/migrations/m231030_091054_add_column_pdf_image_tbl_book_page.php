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
class m231030_091054_add_column_pdf_image_tbl_book_page extends Migration{
    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%book_page}}');
        if (! isset($table->columns['page_image'])) {
            $this->addColumn('{{%book_page}}', 'page_image', $this->string(255)
                ->defaultValue(NULL));
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%book_page}}');
        if (isset($table->columns['page_image'])) {
            $this->dropColumn('{{%book_page}}', 'page_image');
        }
    }
}
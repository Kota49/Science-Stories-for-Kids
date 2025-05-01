<?php
/**
 *@copyright : OZVID Technologies Pvt. Ltd. < www.OZVID.com >
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
 * php console.php module/migrate
 */
class m220621_090646_m220326_101259_install_faq extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->getTableSchema('{{%faq}}');

        if (! $table) {
            $sql = file_get_contents(__DIR__ . '/../db/install.sql');
            $this->execute($sql);
        }
    }

    public function safeDown()
    {
        // cannot remove tables for now.
    }
}

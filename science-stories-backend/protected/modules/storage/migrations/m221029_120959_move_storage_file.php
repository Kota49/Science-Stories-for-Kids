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
 * php console.php module/migrate
 */
class m221029_120959_move_storage_file extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->getTableSchema('{{%storage_file}}');

        if ($table) {
            $tableold = Yii::$app->db->getTableSchema('{{%file}}');

            if ($tableold) {
                $sql = 'DROP TABLE {{%storage_file}}; ALTER TABLE {{%file}} RENAME TO {{%storage_file}};';
                $this->execute($sql);
            }
        }
    }

    public function safeDown()
    {
        echo "m220929_120959_install_storage migrating down by doing nothing....\n";
    }
}
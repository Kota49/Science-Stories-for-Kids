<?php
/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\module\Generator */

echo "<?php\n";
?>
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
class <?= $generator->migrateName ?> extends <?= 'Migration'?>
{

    public function safeUp()
    {
        
        $dbfile = __DIR__ . '/../db/install.sql';
        if (is_file($dbfile)) {
        	echo "<?= $generator->migrateName ?> installing module.... :$dbfile \n";
            $sql = file_get_contents( $dbfile);
            $this->execute($sql);
        }
    }
    
    public function safeDown()
    {
        echo "<?= $generator->migrateName ?> migrating down by doing nothing....\n";
    }
}
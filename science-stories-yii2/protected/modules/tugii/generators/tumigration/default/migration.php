<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator app\modules\tugii\generators\tumigration\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */
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
 
use <?= 'yii\db\Migration' ?>;
/**
 *   php console.php module/migrate 
 */
class <?= $migrateName ?> extends <?= 'Migration'?>
{
    public function safeUp()
	{
                <?php if ($sql_up):?>
                    $this->execute("<?php echo ($sql_up); ?>");
                <?php endif; ?>
                }
                
	public function safeDown()
	{

<?php if (!$enableDown): ?>
		echo "<?php echo $migrateName; ?> migrating down by doing nothing....\n";
<?php else: ?>
                $this->execute("<?php echo ($sql_down); ?>");
<?php endif; ?>

	}
}
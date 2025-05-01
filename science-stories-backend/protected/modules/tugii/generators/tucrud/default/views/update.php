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
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams ();

echo "<?php\n";
?>


/* @var $this yii\web\View */
/* @var $model <?=ltrim ( $generator->modelClass, '\\' )?> */

<?php if(!empty($generator->moduleID)){?>
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->moduleID)))) ?>, 'url' => ['/<?php echo $generator->moduleID;?>']];
<?php }?>$this->params['breadcrumbs'][] = ['label' => <?=$generator->generateString ( Inflector::pluralize ( Inflector::camel2words ( StringHelper::basename ( $generator->modelClass ) ) ) )?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model-><?=$generator->getNameAttribute ()?>, 'url' => ['view', <?=$urlParams?>]];
$this->params['breadcrumbs'][] = <?=$generator->generateString ( 'Update' )?>;
?>
<div class="wrapper">
	<div class="card">
		<div class="<?=Inflector::camel2id ( StringHelper::basename ( $generator->modelClass ) )?>-update">
			<?="<?= "?> \app\components\PageHeader::widget(['model' => $model]); ?>
		</div>
	</div>
	<div class="card">
		<?="<?= "?>$this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>


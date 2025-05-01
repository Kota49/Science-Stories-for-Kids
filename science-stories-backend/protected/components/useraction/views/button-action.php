<?php

/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
use app\components\TActiveForm;
use yii\helpers\Html;
?>
<div class="clearfix"></div>
<div class="card">
	<div class="card-header">
		<h3>User Actions</h3>
	</div>
	<div class="user-actions-view card-body">
		<div class="form">

    <?php $form = TActiveForm::begin(['id' => 'user-actions-form',]); ?>
		<?= $title?>
		<div class="btn-group float-end">


	<?php

foreach ($allowed as $id => $act) {

    if ($id != $model->{$attribute}) {
        $button = $buttons[$id];
        echo '';
        echo Html::submitButton($button, array(
            'name' => 'workflow',
            'value' => $id,
            'class' => 'btn btn-' . $this->context->getButtonColor($button).' mb-2 me-1 rounded-2'
        ));
        echo '';
    }
}

?>
	
	</div>
<?php TActiveForm::end(); ?>
</div>
	</div>
</div>

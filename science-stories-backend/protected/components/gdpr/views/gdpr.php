<?php
/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author     : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */

use app\components\TActiveForm;
use yii\helpers\Html;
?>
<div class="bg-bottom">
	<div >
		<p class="line_h mb-10">
		<?= $description?></p>
	    <?php
    $form = TActiveForm::begin([
        'id' => 'cookies-actions-form'
    ]);
    echo Html::submitButton('Agree', array(
        'name' => 'accept',
        'value' => 'Accept',
        'class' => 'btn btn-success btn-sm ms-0 ms-sm-3 p-1 px-sm-3 py-sm-1',
        'id' => 'information-form-submit'
    ));
    TActiveForm::end();
    ?>
   </div>
</div>

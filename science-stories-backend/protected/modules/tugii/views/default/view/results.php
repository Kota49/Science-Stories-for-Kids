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
 
/* @var $this yii\web\View */
/* @var $generator yii\gii\Generator */
/* @var $results string */
/* @var $hasError boolean */
?>
<div class="default-view-results">
    <?php
				if ($hasError) {
					echo '<div class="alert alert-danger">There was something wrong when generating the code. Please check the following messages.</div>';
				} else {
					echo '<div class="alert alert-success">' . $generator->successMessage () . '</div>';
				}
				?>
    <pre><?= nl2br($results) ?></pre>
</div>

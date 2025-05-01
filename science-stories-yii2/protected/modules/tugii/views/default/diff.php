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
/* @var $diff mixed */
?>
<div class="default-diff">
    <?php if ($diff === false): ?>
        <div class="alert alert-danger">Diff is not supported for this file type.</div>
    <?php elseif (empty($diff)): ?>
        <div class="alert alert-success">Identical.</div>
    <?php else: ?>
        <div class="content"><?= $diff ?></div>
    <?php endif; ?>
</div>

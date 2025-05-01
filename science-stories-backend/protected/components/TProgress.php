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
namespace app\components;

use yii\bootstrap5\Progress;

/**
 * Manage the progress bar and data
 */
class TProgress extends Progress
{

    public function init()
    {
        parent::init();

        $this->percent = intval($this->percent);
        if (empty($this->label)) {
            $this->label = $this->percent . '%';
        } elseif (is_numeric($this->label)) {
            $this->label = $this->label . '%';
        }
        $this->options['id'] = $this->getId();
        $this->barOptions['class'] = $this->getClass();
    }

    public function getClass()
    {
        $per = $this->percent;
        if ($per >= 80) {
            $context = 'progress-bar bg-success';
        } elseif ($per >= 50 && $per < 80) {
            $context = 'progress-bar bg-warning';
        } else {
            $context = 'progress-bar bg-danger';
        }
        return $context;
    }
}


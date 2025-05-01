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
namespace app\components;

use yii\console\Controller;
use app\components\helpers\TArrayHelper;
use app\components\helpers\TLogHelper;

/**
 *
 * {@inheritdoc}
 *
 */
class TConsoleController extends Controller
{

    use TLogHelper;

    public $force = false;

    public $dryRun = false;

    public $limit = 1000;

    public $offset = 0;

    public $job = null;

    public function options($actionID)
    {
        return TArrayHelper::merge(parent::options($actionID), [
            'dryRun',
            'force',
            'limit',
            'offset',
            'job'
        ]);
    }

    public function optionAliases()
    {
        return TArrayHelper::merge(parent::optionAliases(), [
            'd' => 'dryRun',
            'f' => 'force',
            'l' => 'limit',
            'o' => 'offset',
            'j' => 'job'
        ]);
    }

    public static function shellExec($strings)
    {
        self::log('shell Exec : ' . $strings);
        echo shell_exec($strings);
    }
}


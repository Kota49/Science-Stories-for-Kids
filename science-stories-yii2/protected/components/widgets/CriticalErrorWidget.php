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
namespace app\components\widgets;

use app\components\TBaseWidget;
use app\components\helpers\TLogHelper;
use app\models\User;
use app\modules\logger\models\Log as LoggerLog;
use app\modules\smtp\models\EmailQueue;
use yii\helpers\Html;
use app\modules\smtp\models\Account;

/**
 * Check the critical errors in project
 * 
 */
class CriticalErrorWidget extends TBaseWidget
{
    use TLogHelper;

    public $message = '';

    public function init()
    {
        parent::init();
        $this->visible = User::isAdmin();
    }

    /**
     * Find count of pending emails in Email Queue
     *
     * @param string $created_on
     *
     */
    public function getPendingEmailsCount()
    {
        $hours = 1;
        $emailcount = 0;

        if (\Yii::$app->hasModule('smtp')) {

            $emailquery = EmailQueue::find();
            // if forced dont check state
            $emailquery->where([
                'in',
                'state_id',
                [
                    EmailQueue::STATE_PENDING
                ]
            ]);
            $emailquery->andWhere([
                '>',
                'created_on',
                date('Y-m-d H:i:s', strtotime("-$hours hours"))
            ]);

            $emailquery->orderBy('created_on DESC');

            $emailcount = $emailquery->count();

            if ($emailcount > 0) {
                $this->message = 'Email pending: ' . $emailcount . ' Last  :' . $emailquery->one()->linkify();
            }
        }
        return $emailcount;
    }

    /**
     * Find count of logger errors
     *
     * @param string $created_on
     *
     */
    public function getLoggerErrorsCount()
    {
        $hours = 1;
        $loggercount = 0;

        if (\Yii::$app->hasModule('logger')) {
            $loggerquery = LoggerLog::find();

            $loggerquery->andWhere([
                '>',
                'created_on',
                date('Y-m-d H:i:s', strtotime("-$hours hours"))
            ]);

            $loggerquery->orderBy('created_on DESC');

            $loggercount = $loggerquery->count();

            if ($loggercount > 0) {
                $this->message = 'Logger errors: ' . $loggercount . ' Last error :' . $loggerquery->one()->linkify();
            }
        }
        return $loggercount;
    }

    /**
     * Check the scheduler status enable or disable
     *
     * @param string $created_on
     * @param integer $state_id
     *
     */
    public function isSchedulerStatusWorking()
    {
        $hours = 1;
        $count = 0;

        $settings = new \app\modules\scheduler\models\SettingsForm();

        if (! $settings->enableScheduler) {
            self::log("Scheduler not enabled");
            $this->message = 'Schedular not enabled.';
            return false;
        }

        $query = \app\modules\scheduler\models\Log::find();

        // if forced dont check state
        $query->where([
            'in',
            'state_id',
            [
                \app\modules\scheduler\models\Log::STATE_COMPLETED
            ]
        ]);
        $query->andWhere([
            '>',
            'created_on',
            date('Y-m-d H:i:s', strtotime("-$hours hours"))
        ]);

        $query->orderBy('created_on DESC');

        $count = $query->count();

        if ($count == 0) {
            $this->message = 'Schedular not running.';
        }

        return $count != 0;
    }

    /**
     * Find count of scheduler errors
     *
     * @param string $created_on
     */
    public function getSchedulerErrorsCount()
    {
        $hours = 1;
        $count = 0;

        $query = \app\modules\scheduler\models\Log::find();

        // if forced dont check state
        $query->where([
            'in',
            'state_id',
            [
                \app\modules\scheduler\models\Log::STATE_FAILED
            ]
        ]);
        $query->andWhere([
            '>',
            'created_on',
            date('Y-m-d H:i:s', strtotime("-$hours hours"))
        ]);
        $query->orderBy('created_on DESC');

        $count = $query->count();
        if ($count > 0) {
            $this->message = 'Schedular errors: ' . $count . ' Last error :' . $query->one()->linkify();
        }

        return $count;
    }

    /**
     * Check the enable emails
     *
     * @param string $created_on
     * @param integer $state_id
     *
     */
    public function isEmailStatusWorking()
    {
        $settings = new \app\modules\smtp\models\SettingsForm();

        if (! $settings->enableEmails) {
            self::log("SMTP not enabled");
            $this->message = 'SMTP not enabled.';
            return false;
        }

        $query = Account::find();

        // if forced dont check state
        $query->where([
            'in',
            'state_id',
            [
                Account::STATE_ACTIVE
            ]
        ]);

        $query->orderBy('created_on DESC');

        $count = $query->count();

        if ($count == 0) {
            $this->message = 'SMTP Email account not set.';
        }
        return $count != 0;
    }

    /**
     * show errors of scheduler,logger and emails count
     */
    public function renderHtml()
    {

        $html = '';
      
        if (\Yii::$app->hasModule('scheduler')) {
            if (! $this->isSchedulerStatusWorking() || $this->getSchedulerErrorsCount()) {
               
               
                $html .=  Html::beginTag('div', [
                    'class' => 'alert alert-danger'
                ]);

                $html .= $this->message;
                $html .=  Html::endTag('div');
                
            }
        }

     
        // logger check
        if ($this->getLoggerErrorsCount()) {
     
            $html .= Html::beginTag('div', [
                'class' => 'alert alert-danger'
            ]);
            $html .= $this->message;
            $html .= Html::endTag('div');
        }
        if (! $this->isEmailStatusWorking() || $this->getPendingEmailsCount()) {
       
            $html .= Html::beginTag('div', [
                'class' => 'alert alert-warning'
            ]);
            $html .= $this->message;
            $html .= Html::endTag('div');

        }
        if ( !empty($html))
        {
            $html =   Html::beginTag('div', [
                'class' => 'card mx-3 mt-3 mb-0'
            ]) 
            .Html::beginTag('div', [
                'class' => 'card-body pb-1'
            ])

            . $html 
            .Html::endTag('div').Html::endTag('div');

            
        }
        $html .= '<div class="clearfix"></div>';
  
        echo $html;
    }
}

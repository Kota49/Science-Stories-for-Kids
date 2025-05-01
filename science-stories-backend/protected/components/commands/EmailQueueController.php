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
namespace app\components\commands;

use app\components\TConsoleController;
use Yii;
use function app\components\commands\EmailQueueController\actionClear as date;
use app\models\EmailQueue;
use app\models\File;
use app\modules\comment\models\Comment;
use yii\base\Exception;
use yii\console\ExitCode;

/**
 * EmailQueueController implements the backup email commands.
 */
class EmailQueueController extends TConsoleController
{

    /**
     * Send pending emails
     *
     * @param number $m
     */
    public function actionSend($m = 3)
    {
        $enableEmails = Yii::$app->settings->getValue('enableEmails');
        
        if (! $enableEmails) {
            self::log("Emails not enabled");
            return ExitCode::OK;
        }
        
        $query = EmailQueue::find()->where([
            'state_id' => EmailQueue::STATE_PENDING
        ])
            ->orderBy('id asc')
            ->limit(100);

        self::log("Sending up emails : " . $query->count());
        foreach ($query->each() as $email) {
            self::log("Sending  email :" . $email->id . ' - ' . $email);
            try {
                $email->sendNow();
            } catch (Exception $e) {
                echo $e->getMessage();
                echo $e->getTraceAsString();
            }
        }
    }

    /**
     * Clear all emails
     */
    public function actionTruncate()
    {
        $query = EmailQueue::find()->orderBy('id asc');

        EmailQueue::log("Cleaning up emails : " . $query->count());
        $query->limit(100);
        foreach ($query->each() as $email) {
            EmailQueue::log("Deleting  email :" . $email->id . ' - ' . $email);
            try {
                $email->delete();
            } catch (Exception $e) {
                echo $e->getMessage();
                echo $e->getTraceAsString();
            }
        }
        File::deleteRelatedAll([
            'model_type' => EmailQueue::class
        ]);
        Comment::deleteRelatedAll([
            'model_type' => EmailQueue::class
        ]);

        EmailQueue::truncate();
    }

    /**
     * Clear already sent emails
     *
     * @param number $m
     */
    public function actionClear($m = 3)
    {
        $query = EmailQueue::find()->where([
            'state_id' => EmailQueue::STATE_SENT
        ])
            ->andWhere([
            '<',
            'date_sent',
            date('Y-m-d H:i:s', strtotime("-$m months"))
        ])
            ->orderBy('id asc');

        EmailQueue::log("Cleaning up emails : " . $query->count());
        foreach ($query->each() as $email) {
            EmailQueue::log("Deleting  email :" . $email->id . ' - ' . $email);
            try {
                $email->delete();
            } catch (Exception $e) {
                echo $e->getMessage();
                echo $e->getTraceAsString();
            }
        }
        if ($m == 0) {
            EmailQueue::truncate();
        }
    }
}


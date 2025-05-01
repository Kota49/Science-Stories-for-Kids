<?php
namespace app\modules\notification\commands;

use app\components\TConsoleController;
use app\models\EmailQueue;
use app\modules\emailreader\models\Email;
use app\modules\emailreader\models\EmailAccount;
use app\modules\smtp\models\Account;
use yii\base\Exception;
use app\modules\notification\models\Notification;
use app\modules\notification\models\SettingsForm;
use yii\console\ExitCode;

class NotificationController extends TConsoleController
{

    /**
     * Clean old Notifications
     */
    public function actionClean()
    {
        $query = Notification::find();

        $query->limit(1000)->orderBy('id asc');

        self::log("Cleaning up  Notifications: " . $query->count());
        foreach ($query->each() as $notification) {
            self::log("Deleting Notification :" . $notification->id . ' - ' . $notification);
            try {
                $notification->delete();
            } catch (Exception $e) {
                echo $e->getMessage();
                echo $e->getTraceAsString();
            }
        }
    }

    /**
     * Delete emails
     *
     * @param boolean $truncate
     * @return number
     */
    public function actionClear($truncate = false)
    {
        $query = Notification::find()->orderBy('id ASC');

        self::log("Cleaning up  Notifications: " . $query->count());

        foreach ($query->batch() as $models) {
            foreach ($models as $model) {
                self::log('Deleting :' . $model->id);
                $model->delete();
            }
        }

        if ($truncate) {
            Notification::truncate();
        }
        return 0;
    }

    /**
     * Expire old Notifications after 1 hour
     */
    public function actionExpire()
    {
        self::log("Expire");

        $moduleSettings = new SettingsForm();

        if (! $moduleSettings->enable) {
            self::log("Module not enabled");
            return ExitCode::OK;
        }

        $hours = $moduleSettings->clearSentAfterHours;

        if ($this->force) {
            $hours = 0; // sync now
        }

        $query = Notification::find();

        // check emails every m minutes
        $query->andWhere([
            '<',
            'created_on',
            date('Y-m-d H:i:s', strtotime("-$hours hours"))
        ]);

        $query->limit(1000)->orderBy('id asc');

        self::log("Cleaning up  Notifications: " . $query->count());
        foreach ($query->each() as $notification) {
            self::log("Deleting Notification :" . $notification->id . ' - ' . $notification);
            try {
                $notification->delete();
            } catch (Exception $e) {
                echo $e->getMessage();
                echo $e->getTraceAsString();
            }
        }
    }
}


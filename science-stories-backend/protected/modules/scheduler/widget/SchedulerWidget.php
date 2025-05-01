<?php
namespace app\modules\scheduler\widget;

use app\components\TBaseWidget;
use app\components\helpers\TLogHelper;
use app\models\User;
use app\modules\scheduler\models\Log;
use app\modules\scheduler\models\SettingsForm;
use yii\helpers\Html;

class SchedulerWidget extends TBaseWidget
{
    use TLogHelper;

    public $message = '';

    private $settings = null;

    public function init()
    {
        parent::init();
        $this->visible = User::isAdmin();

        $this->settings = new SettingsForm();
        if (! $this->settings->enableScheduler) {
            self::log("Scheduler not enabled");
            $this->visible = false;
        }
    }

    public function getErrorsCount()
    {
        $hours = 1;

        $query = Log::find();
        // if forced dont check state
        $query->where([
            'in',
            'state_id',
            [
                Log::STATE_FAILED
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

    public function isStatusWorking()
    {
        $hours = 1;

        $settings = new SettingsForm();

        if (! $settings->enableScheduler) {
            self::log("Scheduler not enabled");
            $this->visible = false;
        }
        $query = Log::find();

        // if forced dont check state
        $query->where([
            'in',
            'state_id',
            [
                Log::STATE_COMPLETED,
                Log::STATE_FAILED
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

    public function renderHtml()
    {
        if (! $this->isStatusWorking() || $this->getErrorsCount()) {

            echo Html::beginTag('div', [
                'class' => 'alert-wrapper'
            ]);
            echo Html::beginTag('div', [
                'class' => 'alert alert-danger'
            ]);
            echo $this->message;
            echo Html::endTag('div');
            echo Html::endTag('div');
            echo '<div class="clearfix"></div><br/>';
        }
    }
}

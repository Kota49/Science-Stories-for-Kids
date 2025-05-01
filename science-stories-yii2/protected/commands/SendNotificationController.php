<?php
/**
 *@copyright : OZVID Technologies Pvt. Ltd. < www.ozvid.com >
 *@author	 : Shiv Charan Panjeta < shiv@ozvid.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of OZVID Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\commands;

use app\components\TConsoleController;
use app\modules\book\models\Sendnotification;
use app\modules\book\models\Detail;
use app\models\User;
use app\modules\notification\models\Notification;
use app\modules\notification\models\PushNotification;

class SendNotificationController extends TConsoleController
{

    private $models = [

        'app\modules\feature\models\Feature'
    ];

    public static function log($string)
    {
        echo $string . PHP_EOL;
    }

    /* Send Book Notification */
    public function actionSendNotification()
    {
        $booknotifications = Sendnotification::find()->where([
            'state_id' => Sendnotification::STATE_INACTIVE
        ]);

        foreach ($booknotifications->each() as $booknotification) {

            $book = Detail::findOne($booknotification->book_id);

            $users = User::findActive()->andWhere([
                'role_id' => User::ROLE_USER
            ]);

            foreach ($users->each() as $user) {

                Notification::create([
                    'to_user_id' => $user->id,
                    'title' => 'New Book Launch',
                    'description' => 'New Book Is Added In The list Please Read And Give Comments',
                    'model' => $book,
                    'created_by_id' => $booknotification->created_by_id
                ]);
            }
            $booknotification->state_id = Sendnotification::STATE_ACTIVE;
            $booknotification->updateAttributes([
                'state_id'
            ]);
        }
    }

    /**
     * send push notification for all users
     */
    public function actionSend()
    {
        $notificationModel = PushNotification::find()->where([
            'state_id' => PushNotification::STATE_PENDING
        ]);

        $user = User::findOne([
            'role_id' => User::ROLE_ADMIN
        ]);
        foreach ($notificationModel->each() as $list) {

            $user_ids = User::findActive()->select('id')->column();
            
            
            foreach ($user_ids as $id) {
                
                $to_user = User::findOne($id);

                Notification::create([
                    'to_user_id' => $id,
                    'created_by_id' => $user->id,
                    'title' => $list->removeTags($list->title),
                    'model_id' => $list->id,
                    'type_id' => User::STATE_ACTIVE,
                    'model'=> $to_user,
                    'model_type' => PushNotification::className()
                ], false);
                self::log("Send notification to user ".$id);
            }
            $list->state_id = PushNotification::STATE_SENT;
            $list->updateAttributes([
                'state_id'
            ]);
        }
    }
}


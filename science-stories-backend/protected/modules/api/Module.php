<?php
/**
 *@copyright : OZVID Technologies Pvt. Ltd. < www.ozvid.com >
 *@author	 : Shiv Charan Panjeta < shiv@ozvid.com >
 */
namespace app\modules\api;

use app\modules\api\models\AccessToken;
use app\components\TModule;

/**
 * Api module definition class
 */
class Module extends TModule
{

    /**
     *
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\api\controllers';

    /**
     *
     * @inheritdoc
     */
    public function init()
    {
        $this->controllerMap = [
            'log' => [
                'class' => 'app\modules\logger\controllers\LogController'
            ],
            'rating' => [
                'class' => 'app\modules\book\api\controllers\BookRatingController'
            ],
            'favourite' => [
                'class' => 'app\modules\book\api\controllers\BookFavouriteController'
            ],
            'purchase' => [
                'class' => 'app\modules\book\api\controllers\BookPaymentController'
            ],
            'like' => [
                'class' => 'app\modules\book\api\controllers\BookLikeController'
            ],
            'notification' => [
                'class' => 'app\modules\book\api\controllers\NotificationController'
            ]
        ];
    }

    public static function getRules()
    {
        return [
            [
                'class' => 'yii\rest\UrlRule',
                'pluralize' => false,
                'controller' => [
                    'api/user',
                    'api/post'
                ]
            ]
        ];
    }

    public static function dbFile()
    {
        return __DIR__ . '/db/install.sql';
    }

    public static function beforeDelete($user_id)
    {
        AccessToken::deleteRelatedAll([
            'created_by_id' => $user_id
        ]);
    }
}

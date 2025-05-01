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
namespace app\modules\book\api\controllers;

use app\components\filters\AccessControl;
use app\components\filters\AccessRule;
use app\models\User;
use app\modules\api\components\ApiBaseController;
use app\modules\rating\models\Rating;
use Yii;
use yii\data\ActiveDataProvider;
use app\modules\book\models\Detail;
use app\modules\book\models\Payment;
use yii\data\ArrayDataProvider;
use app\modules\notification\models\Notification;

class BookPaymentController extends ApiBaseController
{

    public $modelClass = "app\modules\book\models\Payment";

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class
                ],
                'rules' => [
                    [
                        'actions' => [
                            'book-purchase',
                            'purchase-list'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isUser();
                        }
                    ]
                ]
            ]
        ];
    }

    /**
     *
     * @OA\Post(path="/purchase/book-purchase",
     *   summary="",
     *   tags={"Purchase"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *    @OA\Parameter(
     *     name="lang",
     *     in="header",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="book_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Add rating of the charging space",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionBookPurchase($book_id, $currency = null)
    {
        $this->setStatus(400);
        $data = [];
        $payment = new Payment();
        $post = \Yii::$app->request->post();

        $model = Detail::findOne($book_id);

        if (empty($model)) {
            $data['message'] = User::getMessage("Concept not found");
            return $data;
        } else {

            $payment->book_id = $book_id;
            $payment->amount = $model->price;
            $payment->currency = '$';
            $payment->transaction_id = rand(100000000, 999999999);
            $payment->title = User::getMessage('Payment Added');
            $payment->description = User::getMessage('Payment Successfully Done');
            $payment->state_id = Detail::STATE_ACTIVE;
            $payment->created_by_id = Yii::$app->user->id;

            if ($payment->save()) {

               /*  Notification::create([
                    'to_user_id' => \Yii::$app->user->id,
                    'title' => 'Book Purchased',
                    'model' => $model,
                    'created_by_id' => \Yii::$app->user->id
                ]); */

                $this->setStatus(200);
                $data['message'] = User::getMessage('Book purchased successfully');
                $data['detail'] = $payment->asJson();
            } else {
                $data['message'] = $payment->getErrors();
            }
        }

        return $data;
    }

    /**
     *
     * @OA\Post(path="/purchase/purchase-list",
     *   summary="",
     *   tags={"Purchase"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *    @OA\Parameter(
     *     name="lang",
     *     in="header",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Rating List of the charging space",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionPurchaseList($page = null)
    {
        $dataProvider = [];
        $this->setStatus(400);

        $bookList = Payment::find()->select('book_id')
            ->my()
            ->column();

        $query = Detail::find()->where([
            'in',
            'id',
            $bookList
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'page' => $page,
                'pageSize' => 20
            ]
        ]);

        $this->setStatus(200);
        return $dataProvider;
    }
}

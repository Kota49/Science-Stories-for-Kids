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

class BookRatingController extends ApiBaseController
{

    public $modelClass = "app\modules\rating\models\Rating";

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
                            'book-rating',
                            'book-rating-list'
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
     * Student give rating on Books after completing reading
     */
    /**
     *
     * @OA\Post(path="/rating/book-rating",
     *   summary="",
     *   tags={"Rating"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *     @OA\Parameter(
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
     *    @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *           required={"Rating[rating]"},
     *              @OA\Property(property="Rating[rating]",type="integer", example="5",description="Rating"),
     *              @OA\Property(property="Rating[comment]",type="string",example="good",description="Reviews"),
     *
     *           ),
     *       ),
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
    public function actionBookRating($book_id)
    {
        $this->setStatus(400);
        $data = [];
        $ratings = new Rating();

        $post = \Yii::$app->request->post();
        $model = Detail::findOne($book_id);

        if (empty($model)) {
            $data['message'] = User::getMessage("Concept not found");
            return $data;
        }
        if ($ratings->load($post)) {

            $existingRating = Rating::findActive()->andWhere([
                'model_id' => $book_id
            ])
                ->my()
                ->one();

            if ($existingRating) {
                $existingRating->updateAttributes([
                    'rating' => $ratings->rating,
                    'comment' => $ratings->comment
                ]);
                $data['message'] = User::getMessage('Rating updated successfully');
                $data['detail'] = $existingRating->asJson();
            } else {
                $ratings->model_id = $book_id;
                $ratings->model_type = Detail::class;
                $ratings->rating = $ratings->rating;
                $ratings->comment = $ratings->comment;
                $ratings->title = User::getMessage('Book rating');
                $ratings->state_id = Detail::STATE_ACTIVE;
                $ratings->created_by_id = Yii::$app->user->id;
                if ($ratings->save()) {
                    /* Notification::create([
                        'to_user_id' => \Yii::$app->user->id,
                        'title' => 'Book Purchased',
                        'model' => $model,
                        'created_by_id' => \Yii::$app->user->id
                    ]); */
                    $this->setStatus(200);
                    $data['message'] = User::getMessage('Rating added successfully');
                    $data['detail'] = $ratings->asJson();
                } else {
                    $data['message'] = $ratings->getErrors();
                }
            }
        } else {
            $data['message'] = \Yii::t('app', 'Data Not Posted');
        }
        return $data;
    }

    /**
     * this api returns all ratings of specific lession if ratings are not added on lession then returns empty array
     */

    /**
     *
     * @OA\Post(path="/rating/book-rating-list",
     *   summary="",
     *   tags={"Rating"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *     @OA\Parameter(
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
    public function actionBookRatingList($book_id, $page = null)
    {
        $dataProvider = [];
        $this->setStatus(400);

        $rating_list = Rating::find()->andWhere([
            'model_id' => $book_id
        ])->active();

        if (! empty($rating_list)) {
            $dataProvider = new ActiveDataProvider([
                'query' => $rating_list,
                'pagination' => [
                    'page' => $page,
                    'pageSize' => 20
                ]
            ]);
        }
        $this->setStatus(200);
        return $dataProvider;
    }
}

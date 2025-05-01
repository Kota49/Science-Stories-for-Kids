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
use Yii;
use yii\data\ActiveDataProvider;
use app\modules\book\models\Detail;
use app\modules\book\models\Payment;
use yii\data\ArrayDataProvider;
use app\modules\book\models\Favourite;
use app\modules\book\models\Like;
use app\modules\notification\models\Notification;

class BookLikeController extends ApiBaseController
{

    public $modelClass = "app\modules\book\models\Like";

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
                            'book-like',
                            'like-list'
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
     * Student add Favourites on Books after completing reading
     */
    /**
     *
     * @OA\GET(path="/like/book-like",
     *   summary="",
     *   tags={"Like"},
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
     *     description="Add Like of the charging space",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionBookLike($book_id)
    {
        $this->setStatus(400);
        $data = [];
        $likes = new Like();
        $model = Detail::findOne($book_id);

        if (empty($model)) {
            $data['message'] = User::getMessage("Concept not found");
            return $data;
        }

        $existingLikes = Like::find()->where([
            'model_id' => $book_id
        ])
            ->my()
            ->one();

        if (! empty($existingLikes)) {

            if ($existingLikes->state_id == User::STATE_ACTIVE) {

                $existingLikes->state_id = User::STATE_INACTIVE;
                $existingLikes->delete();
                $this->setStatus(200);
                $data['message'] = User::getMessage('Remove From Like');
            } else {

                $existingLikes->state_id = User::STATE_ACTIVE;
                $this->setStatus(200);
                $data['message'] = User::getMessage('Add To Like');
            }

            $existingLikes->updateAttributes([
                'state_id'
            ]);

            $data['detail'] = $existingLikes->asJson();
        } else {
            $likes->model_id = $book_id;
            $likes->model_type = Detail::class;
            $likes->title = User::getMessage('Book Like');
            $likes->state_id = Detail::STATE_ACTIVE;
            $likes->created_by_id = Yii::$app->user->id;
            if ($likes->save()) {
                $this->setStatus(200);
                $data['message'] = User::getMessage('Add To Likes');
                $data['detail'] = $likes->asJson();
            } else {
                $data['message'] = $likes->getErrors();
            }
        }

        return $data;
    }

    /**
     * this api returns all ratings of specific lession if ratings are not added on lession then returns empty array
     */

    /**
     *
     * @OA\Post(path="/like/like-list",
     *   summary="",
     *   tags={"Like"},
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
    public function actionLikeList($page = null)
    {
        $dataProvider = [];
        $this->setStatus(400);

        $bookList = Like::findActive()->select('model_id')
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

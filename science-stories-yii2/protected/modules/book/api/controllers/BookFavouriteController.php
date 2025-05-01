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
use app\modules\book\models\Payment;
use yii\data\ArrayDataProvider;
use app\modules\book\models\Favourite;
use app\modules\book\models\Detail;
use app\modules\book\models\BookPage;
use app\modules\notification\models\Notification;

class BookFavouriteController extends ApiBaseController
{

    public $modelClass = "app\modules\book\models\Favourite";

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
                            'book-favourite',
                            'favourite-list',
                            'book-page',
                            'book-page-list'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isuser();
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
     * @OA\GET(path="/favourite/book-favourite",
     *   summary="",
     *   tags={"Favourite"},
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
    public function actionBookFavourite($book_id)
    {
        $this->setStatus(400);
        $data = [];
        $favourites = new Favourite();
        $model = Detail::findOne($book_id);

        if (empty($model)) {
            $data['message'] = User::getMessage("Concept not found");
            return $data;
        }

        $existingFavourites = Favourite::find()->where([
            'model_id' => $book_id
        ])
            ->my()
            ->one();

        if (! empty($existingFavourites)) {

            if ($existingFavourites->state_id == User::STATE_ACTIVE) {

                $existingFavourites->state_id = User::STATE_INACTIVE;
                $existingFavourites->delete();

                $this->setStatus(200);

                $data['message'] = User::getMessage('Remove From Favourites');
            } else {

                $existingFavourites->state_id = User::STATE_ACTIVE;

                $this->setStatus(200);

                $data['message'] = User::getMessage('Add To Favourites');
            }

            $existingFavourites->updateAttributes([
                'state_id'
            ]);

            $data['detail'] = $existingFavourites->asJson();
        } else {
            $favourites->model_id = $book_id;
            $favourites->model_type = Detail::class;
            $favourites->title = User::getMessage('Book Favourite');
            $favourites->state_id = Detail::STATE_ACTIVE;
            $favourites->created_by_id = Yii::$app->user->id;
            if ($favourites->save()) {
                $this->setStatus(200);
                $data['message'] = User::getMessage('Add To Favourites');
                $data['detail'] = $favourites->asJson();
            } else {
                $data['message'] = $favourites->getErrors();
            }
        }

        return $data;
    }

    /**
     * this api returns all ratings of specific lession if ratings are not added on lession then returns empty array
     */

    /**
     *
     * @OA\Post(path="/favourite/favourite-list",
     *   summary="",
     *   tags={"Favourite"},
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
    public function actionFavouriteList($page = null)
    {
        $dataProvider = [];
        $this->setStatus(400);

        $bookList = Favourite::findActive()->select('model_id')
            ->my()
            ->andWhere([
            '!=',
            'model_type',
            BookPage::class
        ])
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

    /**
     *
     * @OA\Post(path="/favourite/book-page-list",
     *   summary="",
     *   tags={"Favourite"},
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
    public function actionBookPageList($page = null)
    {
        $dataProvider = [];
        $this->setStatus(400);

        $bookList = Favourite::findActive()->select('model_id')
            ->andWhere([
            'model_type' => BookPage::class
        ])
            ->my()
            ->column();

        $query = BookPage::find()->where([
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

    /**
     *
     * @OA\GET(path="/favourite/book-page",
     *   summary="",
     *   tags={"Favourite"},
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
     *     name="book_page_id",
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
    public function actionBookPage($book_page_id)
    {
        $this->setStatus(400);
        $data = [];
        $favourites = new Favourite();
        $model = BookPage::findOne($book_page_id);

        if (empty($model)) {
            $data['message'] = User::getMessage("Concept not found");
            return $data;
        }

        $existingFavourites = Favourite::find()->where([
            'model_id' => $book_page_id,
            'model_type' => BookPage::class
        ])
            ->my()
            ->one();

        if (! empty($existingFavourites)) {

            if ($existingFavourites->state_id == User::STATE_ACTIVE) {

                $existingFavourites->state_id = User::STATE_INACTIVE;
                $existingFavourites->delete();

                $this->setStatus(200);

                $data['message'] = User::getMessage('Removed From Bookmark');
            } else {

                $existingFavourites->state_id = User::STATE_ACTIVE;

                $this->setStatus(200);

                $data['message'] = User::getMessage('Add To Bookmark');
            }

            $existingFavourites->updateAttributes([
                'state_id'
            ]);

            $data['detail'] = $existingFavourites->asJson();
        } else {
            $favourites->model_id = $book_page_id;
            $favourites->model_type = BookPage::class;
            $favourites->title = User::getMessage('Book Marked');
            $favourites->state_id = BookPage::STATE_ACTIVE;
            $favourites->created_by_id = Yii::$app->user->id;
            if ($favourites->save()) {
                $this->setStatus(200);
                $data['message'] = User::getMessage('Add To Bookmark');
                $data['detail'] = $favourites->asJson();
            } else {
                $data['message'] = $favourites->getErrors();
            }
        }

        return $data;
    }
}

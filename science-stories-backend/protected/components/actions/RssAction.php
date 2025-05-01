<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\components\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use app\components\helpers\TFileHelper;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * Export action
 *
 * public function actions()
 * {
 * return [
 *
 * 'rss' => [
 * 'class' => 'app\components\actions\RssAction',
 * 'modelClass' => Gateway::class,
 * ]
 * ];
 * }
 */
class RssAction extends Action
{

    /**
     *
     * @var string name of the model
     */
    public $modelClass;

    /**
     *
     * @var integer limit field name
     */
    public $limit = 100;

    /**
     * Run the action
     *
     * @param $id integer
     *            id of model to be loaded
     *            
     * @throws \yii\web\MethodNotAllowedHttpException
     * @throws \yii\base\InvalidConfigException
     * @return mixed
     */
    public function run()
    {
        if (empty($this->modelClass) || ! class_exists($this->modelClass)) {
            throw new InvalidConfigException("Model class doesn't exist");
        }
        /* @var $modelClass \yii\db\ActiveRecord */
        $modelClass = $this->modelClass;

        $query = $modelClass::findActive()->orderBy('id DESC')->limit($this->limit);

        Yii::$app->response->format = \yii\web\Response::FORMAT_XML;

        ?><rss version="2.0"> 
	<channel>

<title>Blog</title>

<atom:link href="<?php echo Url::current([],true)?>" rel="self"
	type="application/rss+xml" />

<link>
    <?php echo Url::home(true)?>
    </link>

<description>Blog</description> <language>en</language> <copyright><?php echo Yii::$app->params['company']?></copyright>

        <?php if ($query->count() > 0){ ?>

            <?php foreach ($query->each() as $post){ ?>

                <item>

                <title><?php echo Html::encode($post->title); ?></title>
                
                <description><?php echo $post->getSeoContent(300); ?></description> <img
                	src="<?php echo $post->getImageUrl(false, true) ?>"
                	alt="<?php echo $post->title ?>" />
                
                <link><?php echo $post->getAbsoluteUrl() ?></link>
                
                <pubDate> <?php echo $post->getBlogdate()?></pubDate>
                
                </item>

            <?php } ?>

        <?php } ?>

  	</channel> 
</rss>

<?php
    }
}
?>

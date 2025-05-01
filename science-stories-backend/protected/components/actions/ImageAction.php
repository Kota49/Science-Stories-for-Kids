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

use Imagine\Image\ManipulatorInterface;
use app\models\File;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\imagine\Image;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use app\components\helpers\TLogHelper;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Box;
use app\components\helpers\TFileHelper;
use Yii;

/**
 * Image action
 *
 * public function actions()
 * {
 * return [
 *
 * 'image' => [
 * 'class' => 'app\components\actions\ImageAction',
 * 'modelClass' => Post::class,
 * 'attribute'=> 'image_file,
 * 'default' => true [or] \Yii::$app->view->theme->basePath . '/img/default.jpg'
 * ]
 * ];
 * }
 */
class ImageAction extends Action
{

    use TLogHelper;

    /**
     *
     * @var string name of the model
     */
    public $modelClass;

    /**
     *
     * @var string model attribute
     */
    public $attribute = 'image_file';

    /**
     *
     * @var string image path
     */
    public $isAbsolutePath = false;

    /**
     *
     * @var string default attribute
     */
    public $default;

    /**
     *
     * @var boolean automatic
     */
    public $automatic = false;

    /**
     *
     * @var string pk field name
     */
    public $primaryKey = 'id';

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
    public function run($id, $file = null, $thumbnail = false)
    {
        if (Yii::$app->request->getIsPost()) {
            throw new MethodNotAllowedHttpException();
        }
        $id = (int) $id;

        if (empty($this->modelClass) || ! class_exists($this->modelClass)) {
            throw new InvalidConfigException("Model class doesn't exist");
        }
        /* @var $modelClass \yii\db\ActiveRecord */
        $modelClass = $this->modelClass;

        $model = $modelClass::find()->where([
            $this->primaryKey => $id
        ]);

        $model = $model->one();
        if (is_null($model)) {
            throw new NotFoundHttpException("Model  doesn't exist");
        }

        if (! $model->hasProperty($this->attribute)) {
            throw new InvalidConfigException("Attribute doesn't exist");
        }

        $attribute = $this->attribute;

        if ($model instanceof File) {
            $file = $model->getDownloadedPath();
        }
        if (is_numeric($model->$attribute)) {
            $f = File::findOne($model->$attribute);
            if ($f) {
                $file = $f->getDownloadedPath();
            }
        } elseif (empty($model->$attribute)) {
            if (isset($this->default)) {
                if (is_file($this->default)) {
                    $file = $this->default;
                } else {
                    $file = \Yii::$app->view->theme->basePath . '/img/default.jpg';
                }
            }
            if ($this->automatic) {
                $file = $this->saveImageWithText((string) $model);
            }
        } else {
            if ($this->isAbsolutePath) {
                $file = $model->$attribute;
            } else {
                $file = UPLOAD_PATH . $model->$attribute;
            }
        }

        if (empty($file) || ! is_file($file)) {
            throw new NotFoundHttpException(Yii::t('app', "File not found :" . $file));
        }
        $mime = TFileHelper::getMimeType($file);
        // skip png
        if (! stristr($mime, 'image/gif') && $thumbnail) {
            $h = is_numeric($thumbnail) ? $thumbnail : 100;

            $thumb_path = Yii::$app->runtimePath . '/thumbnails';

            if (! is_dir($thumb_path)) {
                TFileHelper::createDirectory($thumb_path);
            }

            $thumb_path_file = $thumb_path . '/' . $h . '_' . $id . basename($file);
            if (is_file($thumb_path_file)) {
                $file = $thumb_path_file;
            } else {
                try {
                    $img = Image::thumbnail($file, null, $h, ManipulatorInterface::THUMBNAIL_INSET);

                    $img->save($thumb_path_file);
                    $file = $thumb_path_file;
                } catch (\Imagine\Exception\RuntimeException $e) {
                    // echo $e->getMessage();
                } catch (\Imagine\Exception\InvalidArgumentException $e) {
                    // echo $e->getMessage();
                }
            }
        }
        @ob_clean();
        return Yii::$app->response->sendFile($file);
    }

    public function saveImageWithText($sText, $width = 1600, $height = 900, $font = null)
    {
        $image_p = imagecreatetruecolor($width, $height);

        // Prepare font size and colors
        $text_color = imagecolorallocate($image_p, 0, 0, 0);
        $bg_color = imagecolorallocate($image_p, 218, 236, 255);
        $font_size = 60;

        if (is_null($font)) {
            $font = Yii::$app->view->theme->basePath . '/fonts/Roboto-Regular.ttf';
        }

        if (! file_exists($font)) {
            self::error('De fontbestand dat u heeft aangegeven is niet gevonden.');
        }

        // Set the offset x and y for the text position
        $offset_x = 0;
        $offset_y = 400;

        // Set text to be write on image

        // Get the size of the text area
        $dims = imagettfbbox($font_size, 0, $font, $sText);

        // Add text background
        imagefilledrectangle($image_p, 0, 0, $width, $height, $bg_color);

        // Add text
        imagettftext($image_p, $font_size, 0, $offset_x, $offset_y, $text_color, $font, $sText);

        // Save the picture
        $filename = TFileHelper::getTempDirectory() . '/text.jpg';

        imagejpeg($image_p, $filename);

        // Clear
        imagedestroy($image_p);
        return $filename;
    }

    public function saveImageWithText2($sText, $width = 1600, $height = 900, $font = null)
    {
        $palette = new RGB();
        $color = $palette->color(Image::$thumbnailBackgroundColor, Image::$thumbnailBackgroundAlpha);

        // create empty image to preserve aspect ratio of thumbnail
        $image = Image::getImagine()->create(new Box($width, $height), $color);

        if (is_null($font)) {
            $font = Yii::$app->view->theme->basePath . '/fonts/Roboto-Regular.ttf';
        }

        if (! file_exists($font)) {
            self::error('De fontbestand dat u heeft aangegeven is niet gevonden.');
        }

        // Set the offset x and y for the text position
        $offset_x = 5;
        $offset_y = 10;

        $image = Image::text($image, $sText, $font, [
            $offset_x,
            $offset_y
        ]);

        // Save the picture
        $filename = TFileHelper::getTempDirectory() . '/text.jpg';

        $image->save($filename);
        return $filename;
    }
}

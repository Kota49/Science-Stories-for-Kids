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
namespace app\modules\storage\widgets;

use app\modules\storage\assets\UploadAsset;
use yii\base\Widget;
use yii\helpers\Url;

/**
 * CropImageUpload renders a jCrop plugin for image crop.
 *
 * @see http://deepliquid.com/content/Jcrop.html
 * @link https://github.com/karpoff/yii2-crop-image-upload
 * @package karpoff\icrop
 */

/**
 *
 * var
 * manualUploader = new qq.FineUploader({
 * element: document.getElementById("fineuploader-container"),
 * request: {
 * endpoint: "/vendor/fineuploader/php-traditional-server/endpoint.php"
 * },
 * deleteFile: {
 * enabled: true,
 * endpoint: "/vendor/fineuploader/php-traditional-server/endpoint.php"
 * },
 * chunking: {
 * enabled: true,
 * concurrent: {
 * enabled: true
 * },
 * success: {
 * endpoint: "/vendor/fineuploader/php-traditional-server/endpoint.php?done"
 * }
 * },
 * resume: {
 * enabled: true
 * },
 * retry: {
 * enableAuto: true,
 * showButton: true
 * }
 * });
 *
 * @param $options['template'] =
 *            qq-template-gallery, qq-template-manual-trigger
 * @param string $options['url']
 *            = Url for upload files
 * @param array $options['extensions']
 *            = validation for file extension ['jpeg', 'jpg', 'gif', 'png','sql']
 * @param string $options['limit']
 *            = File limit
 * @param string $options['size']
 *            = File size
 */
class UploaderWidget extends Widget
{

    public $id = 'file-uploader';

    public $model;

    public $createUserId = null;

    public $typeId = null;

    public $options;

    /**
     *
     * @inheritdoc
     */
    public function run()
    {
        $view = $this->getView();
        $assets = UploadAsset::register($view);
        $this->options = [
            'assets' => $assets
        ];

        if (! isset($this->options['url'])) {
            $this->options['url'] = Url::toRoute([
                '/storage/file/upload',
                'model_id' => $this->model->id,
                'model_type' => get_class($this->model)
            ]);
        }
        if (! isset($this->options['deleteUrl'])) {
            $this->options['deleteUrl'] = Url::toRoute([
                '/storage/file/delete'
            ]);
        }
        if (! isset($this->options['template'])) {
            $this->options['template'] = 'qq-template-gallery';
        }

        if (! isset($this->options['extensions'])) {
            $this->options['extensions'] = [
                'jpeg',
                'jpg',
                'gif',
                'png',
                'zip',
                'js',
                'json',
                'css',
                'sql',
                'php',
                'pdf',
                'html',
                'htm'
            ];
        }

        $this->renderHtml();
    }

    public function renderHtml()
    {
        echo $this->render('_upload_drag_drop_view', [
            'id' => $this->id,
            'options' => $this->options
        ]);
    }
} 
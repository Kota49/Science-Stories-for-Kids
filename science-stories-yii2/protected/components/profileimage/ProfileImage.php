<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
namespace app\components\profileimage;

use app\components\TBaseWidget;

/**
 * Profile Image Widget crop and upload image
 *
 * ProfileImage::widget([
 * 'model' =>$model, (current model)
 * 'uploadUrl' => ['/candidate/profile/image-upload'] (url of action image-upload defined in controller)
 * ])
 */
class ProfileImage extends TBaseWidget
{

    public $model;

    public $uploadUrl;
    
    public $isWebCam = false;

    public function init()
    {
        parent::init();
        if (empty($this->uploadUrl)) {
            $this->uploadUrl = [
                '/' . $this->model->getControllerID() . '/image-upload'
            ];
        }
    }

    public function renderHtml()
    {
        if($this->isWebCam){
            echo $this->render('view', [
                'model' => $this->model,
                'uploadUrl' => $this->uploadUrl
            ]);
        }else{
            echo $this->render('_image', [
                'model' => $this->model,
                'uploadUrl' => $this->uploadUrl
            ]);
        }
    }
}

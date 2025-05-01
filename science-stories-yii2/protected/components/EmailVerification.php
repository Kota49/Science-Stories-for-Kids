<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author    : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\components;

use app\models\User;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\HttpException;

/**
 * Verify the emails
 */
class EmailVerification extends TBaseWidget
{

    /**
     *
     * @inheritdoc
     *
     */
    public function init()
    {
        parent::init();

        if (User::isAdmin() || Yii::$app->user->identity->email_verified) {
            $this->visible = false;
        }
    }

    /**
     * Check if email verified or not
     */
    public static function checkIfVerified()
    {
        if (Yii::$app->user->identity->email_verified) {
            return true;
        }
        if (Yii::$app->controller && in_array(\Yii::$app->controller->id, [

            'log',
            'site',
            'user',
            'dashboard'
        ])) {
            return true;
        }
        if (Yii::$app->module && in_array(\Yii::$app->module->id, [
            'shadow'
        ])) {
            return true;
        }
        throw new HttpException(403, Yii::t('app', 'You are not allowed to access this page. Please verify your email to proceed.'));
    }

    /**
     * return html
     */
    function renderHtml()
    {
        ?>

<div class="wrapper">

	<div class="card verfication-card">

		<header class="card-header email-verfication">
			<h3>Email Verification</h3>
		</header>
		<div class="card-body">
			<div class="row align-items-center">
				<div class="col-md-8">
					<p>
						<b> We have sent a verification mail to <a
							href="mailto:<?= \Yii::$app->user->identity->email ?>"><?= \Yii::$app->user->identity->email ?></a>.
							Please check your Inbox or spam. If	you have not received the email,  click  <?=  Html::a(("Resend"),Url::toRoute('/user/email-resend'),['class'=>'btn btn-primary']); ?>
					</b>
					</p>
					<p>
						<b> If	you wish to change the email,  click <?=  Html::a(("Update Profile"),['/user/update', 'id'=>\Yii::$app->user->id],['target' =>'_blank','class'=>'btn btn-primary']); ?>
			        </b>
					</p>

				</div>

			</div>
		</div>
	</div>

</div>

<?php
    }
}
    
    


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
namespace app\components\widgets;

use app\components\TActiveForm;
use app\components\TBaseWidget;
use app\components\helpers\TArrayHelper;
use Yii;
use yii\helpers\Html;
use app\modules\company\models\Company;

/**
 * Widget to allow selecting and adding contacts
 *
 * @author shivc
 *        
 */
class CompanySelect extends TBaseWidget
{

    public $contact;

    public $dataProvider;

    protected function getActiveCompanyOptions()
    {
        $query = Company::find()->where([
            'in',
            'state_id',
            [
                Company::STATE_ACTIVE
            ]
        ]);
        return TArrayHelper::merge([
            0 => 'All'
        ], Company::listData($query->all()));
    }

    protected function getCompany()
    {
        return Company::findOne(Yii::$app->user->company);
    }

    public function renderHtml()
    {
        $company_id = Yii::$app->request->post('company_id');

        Yii::info('company_id=' . $company_id);
        if (isset($company_id) && is_numeric($company_id)) {

            $company = Company::findOne($company_id);
            if ($company && $company->isActive()) {
                Yii::$app->user->setCompany($company_id);
                \Yii::$app->controller->refresh();
                return '';
            } else {
                Yii::error('company not found =' . $company_id);
                Yii::$app->user->setCompany(null);
                \Yii::$app->controller->refresh();
            }
        }
        TActiveForm::begin([
            'id' => 'company-select-form',
            'layout' => 'inline'
        ]);
        ?>

<div class="form-group">
<?php
        echo Html::dropDownList('company_id', $this->company, ($this->getActiveCompanyOptions()), [
            'class' => 'form-control',
            'onchange' => 'this.form.submit()'
        ]);
        ?>
		</div>


<?php
        TActiveForm::end();
    }
}


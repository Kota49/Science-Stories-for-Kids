<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
/* @var $this yii\web\View */
/* @var $model app\modules\book\models\Payment */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Books'), 'url' => ['/book']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = (string)$model;
?>
<div class="wrapper">
   <div class="card">
      <div class="payment-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
   </div>
   <div class="card">
      <div class="card-body">
         <?php echo \app\components\TDetailView::widget([
         'id'	=> 'payment-detail-view',
         'model' => $model,
         'attributes' => [
                     'id',
            /*'title',*/
            'email:email',
            /*'description:html',*/
            [
            			'attribute' => 'book_id',
            			'format'=>'raw',
            			'value' => $model->getRelatedDataLink('book_id'),
            			],
            'amount',
            'currency',
            'transaction_id',
            'payer_id',
            'value:html',
            'gateway_type',
            'payment_status',
            [
			'attribute' => 'type_id',
			'value' => $model->getType(),
			],
            /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
            [
            			'attribute' => 'created_by_id',
            			'format'=>'raw',
            			'value' => $model->getRelatedDataLink('created_by_id'),
            			],
            'created_on:datetime',
         ],
         ]) ?>
         <?php  echo $model->description;?>
         <?php         echo UserAction::widget ( [
         'model' => $model,
         'attribute' => 'state_id',
         'states' => $model->getStateOptions ()
         ] );
         ?>
      </div>
   </div>
      <div class="card">
      <div class="card-body">
         <div
            class="payment-panel">
            <?php
            $this->context->startPanel();
                        $this->context->addPanel('Feeds', 'feeds', 'Feed',$model /*,null,true*/);
                        $this->context->endPanel();
            ?>
         </div>
      </div>
   </div>
        
  <?php echo CommentsWidget::widget(['model'=>$model]); ?>
  </div>
<div class="card feature-widget-view">
	<div class="card-header">

		<?= $type?>
	</div>
	<div class="card-body">

		<div class="content-list content-image menu-action-right">
			<ul class="list-wrapper list-unstyled">

<?php
echo \yii\widgets\ListView::widget([
    'dataProvider' => $posts,
    
    'summary' => false,
    
    'itemOptions' => [
        'class' => 'item'
    ],
    'itemView' => '_view',
    'options' => [
        'class' => 'list-view comment-list'
    ]
]);
?>
</ul>

		</div>

	</div>
</div>
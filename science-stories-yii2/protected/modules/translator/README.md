<p align="center">
    <a href="http://toxsl.com" target="_blank">
        <img src="https://toxsl.com/themes/toxsl/img/toxsl_logo.png" width="400" alt="Yii Framework" />
    </a>
</p>

Yii 2 Translator Module .

It will give an option to admin accounts to update strings under /protected/messages folder from his admin panel .
You will be able to give an option the admin to add n number of language to his project .

Installation
------------

- The minimum required PHP version of Yii is PHP 5.4.
- It works best with PHP 7.

```
Inside your web.php 

$config['modules']['translator'] = [
    'class' => 'app\modules\translator\Module'
];

### Language Translation CMD
php console.php message/extract @app/config/i18n.php 
```

Widgets Installation
------------
- With TactiveForm :- 

```
<?=TranslatorWidget::widget(['type' => TranslatorWidget::TYPE_FORM,'model' => $model,'attribute' => 'category_name','form' => $form])?>
```

- With Displaying data in GridView :- 

```
  		  [
                'attribute' => 'category_name',
                'format' => 'raw',
                'value' => function ($data) {
                    return TranslatorWidget::widget([
                    'type' => TranslatorWidget::TYPE_DISPLAY,
                    'model' => $data,
                    'attribute' => 'category_name'
                ]);
                }
            ],
```

- With Displaying data in DetailView :- 

```
  		  [
                'attribute' => 'category_name',
                'format' => 'raw',
                'value' => TranslatorWidget::widget([
                    'type' => TranslatorWidget::TYPE_DISPLAY,
                    'model' => $model,
                    'attribute' => 'category_name'
                ])
            ],
```

- For you controller actions Add/update :- 
- Paste this set of code after saving your model and rest widget will do it's job :)

```

  	if ($model->load($post) && $model->save()) {
            TranslatorWidget::widget([
                'type' => TranslatorWidget::TYPE_SAVE,
                'model' => $model,
                'dataAttribute' => [
                    'category_name'
                ]
            ]);
            return $this->redirect($model->getUrl());
        }
```

- For Displaying :- 

```
  <?=TranslatorWidget::widget(['type' => TranslatorWidget::TYPE_DISPLAY,'model' => $category,'attribute' => 'category_name']);?>

```


  

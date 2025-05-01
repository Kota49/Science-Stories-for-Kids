<?php
use yii\helpers\Inflector;

/**
 *
 * @copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * @author : Shiv Charan Panjeta < shiv@toxsl.com >
 *        
 *         All Rights Reserved.
 *         Proprietary and confidential : All information contained herein is, and remains
 *         the property of ToXSL Technologies Pvt. Ltd. and its partners.
 *         Unauthorized copying of this file, via any medium is strictly prohibited.
 *        
 */

/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator app\modules\tugii\generators\tumigration\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */
echo "<?php\n";
?>
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
 

namespace <?=$generator->ns?>;

use Yii;
use app\models\Feed;
<?php
$hasDone = [];
foreach ($relations as $name => $relation) {
    if (! in_array($relation[1], $hasDone)) {
        $class_list = explode(' ', Inflector::camel2words($relation[1]));
        if (Yii::$app->hasModule(lcfirst($class_list[0]))) {
            $part1 = array_shift($class_list);
            $module = lcfirst($part1);
            $classname = empty($class_list) ? $part1 : implode('', $class_list);

            echo "use app\\modules\\{$module}\\models\\{$classname}  as {$relation[1]};\n";
        } else {
            echo "use $generator->appNs\\$relation[1];\n";
        }
    }
    $hasDone[] = $relation[1];
}
?>

use yii\helpers\ArrayHelper;


/**
* This is the model class for table "<?=$tableName?>".
*
<?php

$columns = $tableSchema->columns;
?>
<?php

foreach ($columns as $column) {
    ?>
    * @property <?="{$column->phpType} \${$column->name}\n"?>
<?php
}
?>
<?php

if (! empty($relations)) {

    foreach ($relations as $name => $relation) {
        ?>
	* @property <?=$relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n"?>
    <?php
    }
    ?>
<?php
}

?>
*/


class <?=$className?> extends <?='\\' . ltrim($generator->baseClass, '\\') . "\n"?>
{
<?php
$representing = array_keys($columns)[1];
foreach ($columns as $column) {
    if (preg_match('/^(name|title)/i', $column->name))
        $representing = $column->name;
}
?>
	public  function __toString()
	{
		return (string)$this-><?=$representing?>;
	}
<?php

foreach ($columns as $column) :
    if (preg_match('/^(status_id|state_id)/i', $column->name)) :
        ?>
	const STATE_INACTIVE 	= 0;
	const STATE_ACTIVE	 	= 1;
	const STATE_DELETED 	= 2;

	public static function getStateOptions()
	{
		return [
				self::STATE_INACTIVE		=> "New",
				self::STATE_ACTIVE 			=> "Active" ,
				self::STATE_DELETED 		=> "Deleted",
		];
	}
	public function getState()
	{
		$list = self::getStateOptions();
		return isset($list [$this-><?=$column->name?>])?$list [$this-><?=$column->name?>]:'Not Defined';

	}
	public function getStateBadge()
	{
		$list = [
				self::STATE_INACTIVE 		=> "secondary",
				self::STATE_ACTIVE 			=> "success" ,
				self::STATE_DELETED 		=> "danger",
		];
		return isset($list[$this-><?=$column->name?>])?\yii\helpers\Html::tag('span', $this->getState(), ['class' => 'badge badge-' . $list[$this-><?=$column->name?>]]):'Not Defined';
	}
    public static function getActionOptions()
    {
        return [
            self::STATE_INACTIVE => "Deactivate",
            self::STATE_ACTIVE => "Activate",
            self::STATE_DELETED => "Delete"
        ];
    }

	<?php endif;

    if (preg_match('/(_id)$/i', $column->name)) :

        if (! preg_match('/(state_id|created_by)/i', $column->name)) {

            $key = $generator->getCamelCaseColumn($column->name);

            ?>public static function get<?=$key?>Options()
	{
		return ["TYPE1","TYPE2","TYPE3"];
			<?php

            if (isset($relations[$key])) {
                ?>
		//return self::listData ( <?=$relations[$key][1]?>::findActive ()->all () );
<?php
            }
            ?>
	}

	<?php

            if (! isset($relations[$key])) {
                ?>
 	public function get<?=$key?>()
	{
		$list = self::get<?=$key?>Options();
		return isset($list [$this-><?=$column->name?>])?$list [$this-><?=$column->name?>]:'Not Defined';

	}
	<?php
            }
            ?>
	<?php
        }
        ?>
	<?php endif;

    if (preg_match('/(_on|_date|_time|user_id|manager_id|created_by)/i', $column->name)) {
        $valid_columns[] = $column;
    }
endforeach
;
?>
<?php

if (! empty($valid_columns)) :
    ?>public function beforeValidate()
	{
		if($this->isNewRecord)
		{
	<?php

    foreach ($valid_columns as $column) :
        ?>
	<?php

        if (preg_match('/(updated_on|approve_time|update_time|create_time|created_on)/i', $column->name)) {
            ?>		if ( empty( $this-><?php

            echo $column->name?> )){ $this-><?php

            echo $column->name?> = \date( 'Y-m-d H:i:s');}
	<?php
        }
        if (preg_match('/(manager_id|user_id|created_by)/i', $column->name)) {
            ?>		if ( empty( $this-><?php

            echo $column->name?> )){ $this-><?php

            echo $column->name?> = self::getCurrentUser();
            }
	<?php
        }
        if (preg_match('/(_date)/i', $column->name)) {
            ?>		if ( empty( $this-><?php

            echo $column->name?> )) {$this-><?php

            echo $column->name?> = date( 'Y-m-d');}
	<?php
        }
    endforeach
    ;
    ?>
		}else{
	<?php

    foreach ($valid_columns as $column) :
        ?>
	<?php

        if (preg_match('/(updated_on|approve_time|update_time)/i', $column->name)) {
            ?>		$this-><?php

            echo $column->name?> = date( 'Y-m-d H:i:s');
<?php
        }
    endforeach
    ;
    ?>
		}
		return parent::beforeValidate();
	}
<?php endif;

?>


	/**
	* @inheritdoc
	*/
	public static function tableName()
	{
		return '<?=$generator->generateTableName($tableName)?>';
	}
<?php

if ($generator->db !== 'db') :
    ?>

    /**
    * @return \yii\db\Connection the database connection used by this AR class.
    */
    public static function getDb()
    {
    	return Yii::$app->get('<?=$generator->db?>');
    }
<?php endif;

?>

	/**
	* @inheritdoc
	*/
	public function rules()
	{
		return [<?="\n            " . implode(",\n            ", $rules) . "\n        "?>];
	}

	/**
	* @inheritdoc
	*/


	public function attributeLabels()
	{
		return [
		<?php

foreach ($labels as $name => $label) :
    ?>
		    <?="'$name' => " . $generator->generateString($label) . ",\n"?>
		<?php
endforeach
;
?>
		];
	}
<?php

foreach ($relations as $name => $relation) :
    ?>

    /**
    * @return \yii\db\ActiveQuery
    */
    public function get<?=$name?>()
    {
    	<?=$relation[0] . "\n"?>
    }
<?php
endforeach
;
?>
<?php

?>
    public static function getHasManyRelations()
    {
    	$relations = [];

<?php

if (isset($relationsList['hasMany']))
    foreach ($relationsList['hasMany'] as $key => $relation) :
        ?>
		$relations['<?=$key?>'] = ['<?=lcfirst($relation[0])?>','<?=$relation[1]?>','<?=$relation[2]?>','<?=$relation[3]?>'];
<?php
    endforeach
;
?>
    	$relations['feeds'] = [
            'feeds',
            'Feed',
            'model_id'
        ];
		return $relations;
	}
    public static function getHasOneRelations()
    {
    	$relations = [];
<?php

if (isset($relationsList['hasOne']))
    foreach ($relationsList['hasOne'] as $key => $relation) :
        ?>
		$relations['<?=$key?>'] = ['<?=lcfirst($relation[0])?>','<?=$relation[1]?>','<?=$relation[2]?>'];
<?php
    endforeach
;
?>
		return $relations;
	}

	public function beforeDelete() {
	    if (! parent::beforeDelete()) {
            return false;
        }
        //TODO : start here
<?php

if (isset($relationsList['hasMany'])) {
    foreach ($relationsList['hasMany'] as $key => $relation) {
        ?>
		<?=$relation[1]?>::deleteRelatedAll(['<?=$relation[3]?>'=>$this-><?=$relation[2]?>]);
<?php
    }
}
?>


<?php

foreach ($columns as $column) {
    ?>
<?php

    if (strpos($column->name, 'file')) {

        ?>
       // Delete actual file
        $filePath = UPLOAD_PATH . $this-><?=$column->name?>;
        
        if(is_file($filePath))
        {
        	unlink( $filePath );
        }

<?php
    }
}
?>

		return true;
	}
	
  	public function beforeSave($insert)
    {
        if (! parent::beforeSave($insert)) {
            return false;
        }
        //TODO : start here
        
        return true;
    }
    public function asJson($with_relations=false)
	{
		$json = [];
<?php

foreach ($columns as $column) :
    ?>
<?php

    if (strpos($column->name, 'file')) {
        ?>		if(isset($this-><?php

        echo $column->name?>))
			$json['<?php

        echo $column->name?>'] 	= Url::toRoute('<?=strtolower($className)?>/image',['id'=>$this->id,'file'=>$this-><?=$column->name?>]);
<?php
    } else {
        $match = '/^(updated_on|update_time|actual_start|actual_end|password|passcode|activation_key)/i';
        if (! preg_match($match, $column->name)) {
            ?>			$json['<?php

            echo $column->name?>'] 	= $this-><?php

            echo $column->name?>;
<?php
        }
    }
    ?>
<?php
endforeach
;
?>
			if ($with_relations)
		    {
<?php

foreach ($relations as $name => $relation) :
    ?>
				// <?=lcfirst($name)?>

				$list = $this-><?=lcfirst($name)?>;

				if ( is_array($list))
				{
					$relationData = array_map( function($item)
                        					{
                        					 	return $item->asJson();
                        					},$list);
			
					$json['<?=lcfirst($name)?>'] 	= $relationData;
				}
				else
				{
					$json['<?=lcfirst($name)?>'] 	= $list;
				}
<?php
endforeach
;
?>
			}
		return $json;
	}
	
	<?php

if ($generator->moduleName != null) :
    ?>
	
	public function getControllerID()
    {
        return '/<?=$generator->moduleName?>/' . parent::getControllerID() ;
    }
	
	<?php endif;

?>
	
    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        $states = array_keys(self::getStateOptions());
        for ($i = 0; $i < $count; $i ++) {
            $model = new self();
            $model->loadDefaultValues();
			<?php
$fieldMatch = '/^(id|updated_on|update_time|actual_start|actual_end|password|passcode|activation_key|created_on|create_time|created_by)/i';

foreach ($columns as $column) {
    $attribute = $column->name;

    if (preg_match($fieldMatch, $attribute)) {
        continue;
    }

    if ($attribute == 'state_id') {
        echo "			\$model->state_id = \$states[rand(0,count(\$states))];\n";
    } else {
        echo "			\$model->$attribute = " . $generator->getFieldtestdata($column) . ";\n";
    }
}

?>
        	$model->save();
        }
	}
    public static function addData($data)
    {
    	if (self::find()->count() != 0)
    	{
            return;
        }
        
        $faker = \Faker\Factory::create();
        foreach( $data as $item) {
            $model = new self();
            $model->loadDefaultValues();
<?php
$fieldMatch = '/^(id|updated_on|update_time|actual_start|actual_end|password|passcode|activation_key|created_on|create_time|created_by)/i';

foreach ($columns as $column) {
    $attribute = $column->name;

    if (preg_match($fieldMatch, $attribute)) {
        continue;
    }

    if ($attribute == 'state_id') {
        echo "			\$model->state_id = self::STATE_ACTIVE;\n";
    } else {
        ?>
                    
                    	$model-><?=$attribute?> = isset($item['<?=$attribute?>'])?$item['<?=$attribute?>']:<?php

        echo $generator->getFieldtestdata($column)?>;
                   <?php
    }
}

?>
        	$model->save();
        }
	}
	
	public function isAllowed()
    {
        if (User::isAdmin())
            return true;
        if ($this->hasAttribute('created_by_id') && $this->created_by_id == Yii::$app->user->id) {
            return true;
        }

        return User::isUser();
    }
    
	public function afterSave($insert, $changedAttributes)
    {
        return parent::afterSave($insert, $changedAttributes);
    }
}

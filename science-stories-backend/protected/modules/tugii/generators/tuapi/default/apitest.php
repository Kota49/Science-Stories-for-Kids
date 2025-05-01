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
echo '<?php ';
?>
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
<?php

$api_test_array = array();
foreach ($generator->getTableSchema()->columns as $column) :

    if ($column->name != 'id') :
        $api_test_array[$generator->ModelID . '[' . $column->name . ']'] = $generator->getFieldtestdata($generator->modelClass, $column);
	
	
         endif;

endforeach
;
?> 

return [
	"<?= $generator->controllerID?>" => [
		"POST:create" => [
            'params' => [
		
<?php
echo PHP_EOL;
foreach ($api_test_array as $key => $value) :
    echo '			"' . $key . '" => ' . $value . ',' . PHP_EOL;
endforeach
;
?>			]
],
		"POST:update/{id}"=>  [
            'params' => [		
		<?php

echo PHP_EOL;
foreach ($api_test_array as $key => $value) :
    echo '			"' . $key . '" => ' . $value . ',' . PHP_EOL;
endforeach
;
?>			]
],
		"GET:index" => [
            ],
		"GET:{id}" =>  [
            ],
		"DELETE:{id}" =>  [
            ],
	]
];
?>

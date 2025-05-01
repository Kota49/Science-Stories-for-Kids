<?php
/**
 *@copyright : OZVID Technologies Pvt. Ltd. < www.ozvid.com >
 *@author    : Shiv Charan Panjeta < shiv@ozvid.com >
 */
return [

    'class' => 'app\components\TUrlManager',

    'rules' => [

        [
            'pattern' => 'features',
            'route' => 'feature'
        ],
        [
            'pattern' => 'aboutus',
            'route' => 'site/about'
        ],
        [
            'pattern' => 'contactus',
            'route' => 'site/contact'
        ],
        [
            'pattern' => 'signup',
            'route' => 'user/signup'
        ],
        '<controller:file>/<action:files>/<file>' => '<controller>/<action>',
        '<controller:[A-Za-z-]+>/<id:\d+>/<title>' => '<controller>/view',
        '<controller:[A-Za-z-]+>/<id:\d+>' => '<controller>/view',
        '<controller:[A-Za-z-]+>/<action:[A-Za-z-]+>/<id:\d+>/<title>' => '<controller>/<action>',
        '<controller:[A-Za-z-]+>/<action:[A-Za-z-]+>/<id:\d+>' => '<controller>/<action>',
        '<action:about|contact|privacy|guidelines|copyright|notice|faq|terms|pricing>' => 'site/<action>'
    ]
];
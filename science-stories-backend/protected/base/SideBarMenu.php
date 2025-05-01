<?php

/**
 *
 *@copyright : OZVID Technologies Pvt. Ltd. < www.ozvid.com >
 *@author     : Shiv Charan Panjeta < shiv@ozvid.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of OZVID Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
namespace app\base;

use app\models\User;
use Yii;

trait SideBarMenu
{

    public function getItems()
    {
        if (YII_ENV == 'dev') {
            return $this->renderNavUpdate();
        }
        return Yii::$app->cache->getOrSet('menu_user_id_' . Yii::$app->user->id, function () {
            return $this->renderNavUpdate();
        });
    }

    public function renderNavUpdate()
    {
        $nav_left = [

            self::addMenu(Yii::t('app', 'Dashboard'), '//', 'tachometer', (! User::isGuest())),
            self::adddivider(),
            'Manage' => self::addMenu(Yii::t('app', 'Manage'), '#', 'tasks', User::isManager(), [
                self::addMenu(Yii::t('app', 'Users'), '//user', 'user', (User::isManager())),
                self::addMenu(Yii::t('app', 'Activities'), '//feed/index/', 'tasks', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Page'), '//page/', 'file-text-o', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Login History'), '//login-history/', 'history', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Backup'), '//backup/', 'download', User::isAdmin()),
                self::adddivider(),
                self::addModule('settings'),
                self::adddivider(),
                'Storage List' => self::addMenu(\Yii::t('app', 'Storage List'), '#', 'archive', User::isManager(), [
                    self::addMenu(\Yii::t('app', 'Home'), '/storage/default/index', 'home', User::isManager()),
                    self::addMenu(\Yii::t('app', 'Providers'), '/storage/provider', 'list', User::isManager()),
                    self::addMenu(\Yii::t('app', 'Files'), '/storage/file', 'list', User::isManager()),
                    self::addMenu(\Yii::t('app', 'Types'), '/storage/type', 'list', User::isManager())
                ]),
                self::adddivider(),
                self::addModule('scheduler'),
                self::adddivider(),
                self::addModule('logger'),
                self::addModule('translator')
            ], true),
            self::adddivider(),

            'Bulk Notification' => self::addMenu(Yii::t('app', 'Bulk Notification'), '#', 'bell', User::isManager(), [
                self::addMenu(Yii::t('app', 'Create Notification'), '//notification/push-notification/send-notification', 'bell', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Notification History'), '//notification', 'tasks', User::isAdmin())
            ], true),
            'Book Management' => self::addMenu(Yii::t('app', 'Book Management'), '#', 'book', User::isManager(), [
                self::addMenu(Yii::t('app', 'Categories'), '//book/category', 'list', (User::isManager())),
                self::addMenu(Yii::t('app', 'Books'), '//book/detail', 'book', (User::isManager())),
                self::addMenu(Yii::t('app', 'Book Pages'), '//book/book-page', 'file', (User::isManager())),
                self::addMenu(Yii::t('app', 'Book Audio'), '//book/audio', 'file-audio-o', (User::isManager()))
            ], true),
            'Reports' => self::addMenu(Yii::t('app', 'Reports'), '#', 'file', User::isManager(), [
                self::addMenu(\Yii::t('app', 'Admin Sale Report'), '//book/payment/sale-report', 'bar-chart', User::isManager())
            ], true),

            self::adddivider(),
          /*   'Notifications' => self::addMenu(Yii::t('app', 'Notifications'), '#', 'book', User::isManager(), [
                self::addModule('notification')
            ], true), */
            self::adddivider(),
            'FAQ' => self::addMenu(Yii::t('app', 'FAQ'), '#', 'question-circle', User::isManager(), [
                self::addModule('faq')
            ], true),
            self::adddivider(),
            'Communications' => self::addMenu(Yii::t('app', 'Communications'), '#', 'signal', User::isManager(), [
                self::addModule('smtp')
            ], true),

            self::adddivider(),
            'Content Management' => self::addMenu(Yii::t('app', 'Content Management'), '#', 'folder', User::isManager(), [
                self::addModule('feature')
            ], true),
            self::addMenu(Yii::t('app', 'Banners'), '//banner/', 'image', (! User::isGuest())),
            self::addMenu(Yii::t('app', 'Help & Supports'), '//help-support/', 'info-circle', (! User::isGuest()))
            // self::addMenu(Yii::t('app', 'Promocode'), '//book/promocode', 'gift', (! User::isGuest())),
        ];

        $this->nav_left = $nav_left;
        return $this->nav_left;
    }

    public static function addmenu($label, $link, $icon, $visible = null, $list = null, $submenu = false)
    {
        if (! $visible)
            return null;
        $item = [

            'label' => '<i class="fa fa-' . $icon . '"></i> <span>' . $label . '</span>',
            'url' => [
                $link
            ]
        ];
        if ($list != null) {
            $item['options'] = [
                'class' => 'menu-list nav-item'
            ];

            $item['items'] = $list;
        }

        if ($submenu) {
            $item['options'] = [
                'class' => 'sub-menu-list nav-item'
            ];

            $item['items'] = $list;
        }
        return $item;
    }

    public static function addModule($m, $list = null, $setting = true)
    {
        if (! \Yii::$app->hasModule($m)) {
            return null;
        }
        if ($list == null) {
            $class = "\\app\\modules\\$m\\Module";
            if (class_exists($class) && method_exists($class, 'subNav')) {
                $list = $class::subNav();
            }
        }
        if ($list != null) {
            $list['items'][] = self::addMenu(\Yii::t('app', 'Settings'), '//settings/variable?m=' . $m, 'lock', User::isAdmin());
            return $nav_left[$m] = $list;
        }
    }

    public static function adddivider($visible = true)
    {
        $item = [];
        if ($visible) {
            $item = [
                'label' => '<div class="sidebar-divider"></div>'
            ];
        }
        return $item;
    }
}

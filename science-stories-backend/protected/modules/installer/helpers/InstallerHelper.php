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
namespace app\modules\installer\helpers;

use Yii;

class InstallerHelper
{

    public static function log($strings)
    {
        if (php_sapi_name() == "cli") {
            echo $strings . PHP_EOL;
        } else {
            Yii::debug($strings);
        }
    }

    public static function setCookie()
    {
        $file = (DB_CONFIG_PATH . '/web.php');
        $content = file_get_contents($file);
        
        if (strpos($content, '__DUMMY__') !== false) {
            $newContent = str_replace("__DUMMY__", \Yii::$app->security->generateRandomString(), $content);
            file_put_contents($file, $newContent);
            // echo $newContent;
        }
        return true;
    }

    public static function execSql($sqlArray)
    {
        $message = '';
        $cmd = \Yii::$app->db->createCommand($sqlArray);
        
        try {
            $cmd->execute();
            $message = 'ok';
        } catch (\Exception $e) {
            $message .= $e->getMessage();
            self::log(__FUNCTION__ . " :DB:" . $message . " failed.");
        }
        return $message;
    }

    public static function execSqlFiles($sqlfiles)
    {
        $out = 'NOK';
        $dbFiles = [];
        if (! empty($sqlfiles)) {
            foreach ($sqlfiles as $file) {
                $sqlFile = Yii::getAlias($file);
                if (is_file($sqlFile) && (! in_array($sqlFile, $dbFiles))) {
                    $dbFiles[] = $sqlFile;
                }
            }
        }
        $dbFiles = array_merge($dbFiles, InstallerHelper::moduleDbFiles());
        
        if (! empty($dbFiles)) {
            foreach ($dbFiles as $file) {
                if (is_file($file)) {
                    //self::log(__FUNCTION__ . " :DB: installing =>" . $file );
                    $sqlArray = file_get_contents($file);
                    $message = self::execSql($sqlArray);
                    self::log(__FUNCTION__ . " :DB:" . $file . ' ==>                    ' . $message);
                    $out .= $message;
                } else {
                    self::log(__FUNCTION__ . " :DB:" . $file . " not exists.");
                }
            }
        }
        
        return $out;
    }

    public static function moduleDbFiles()
    {
        $config = include (DB_CONFIG_PATH . 'web.php');
        $dbFiles = [];
        if (! empty($config['modules'])) {
            foreach ($config['modules'] as $modules) {
                $class = isset($modules['class']) ? $modules['class'] : null;
                
                if (class_exists("$class") && method_exists($class, 'dbFile')) {
                    $files = $class::dbFile();
                    if (! is_array($files))
                        $files = [
                            $files
                        ];
                    $dbFiles = array_merge($dbFiles, $files);
                }
            }
        }
        return $dbFiles;
    }

    public static function moduleExts()
    {
        $config = include (DB_CONFIG_PATH . 'web.php');
        $dbExts = [];
        if (! empty($config['modules'])) {
            foreach ($config['modules'] as $module) {
                $class = isset($module['class']) ? $module['class'] : null;
                
                if (class_exists("$class") && method_exists($class, 'getExts')) {
                    $dbExts = array_merge($dbExts, $class::getExts());
                }
            }
        }
        return $dbExts;
    }

    public static function modulePkgs()
    {
        $config = include (DB_CONFIG_PATH . 'web.php');
        $dbPkgs = [];
        if (! empty($config['modules'])) {
            foreach ($config['modules'] as $module) {
                $class = isset($module['class']) ? $module['class'] : null;
                
                if (class_exists("$class") && method_exists($class, 'getPkgs')) {
                    $dbPkgs = array_merge($dbPkgs, $class::getPkgs());
                }
            }
        }
        return $dbPkgs;
    }
}
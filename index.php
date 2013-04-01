<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

$yii = dirname(__FILE__) . '/vendor/yiisoft/yii/framework/yii.php';
$configCommonPath = dirname(__FILE__) . '/protected/config/common.php';
$configPath = dirname(__FILE__) . '/protected/config/main.php';
require_once($yii);
$configCommon = require($configCommonPath);
$config = require($configPath);
Yii::createWebApplication(CMap::mergeArray($configCommon, $config))->run();
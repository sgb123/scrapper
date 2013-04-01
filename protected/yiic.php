<?php
$yii = dirname(__FILE__) . '/../vendor/yiisoft/yii/framework/yii.php';
$configCommonPath = dirname(__FILE__) . '/config/common.php';
$configConsolePath = dirname(__FILE__) . '/config/console.php';
require_once($yii);
$configCommon = require($configCommonPath);
$configConsole = require($configConsolePath);
$config = CMap::mergeArray($configConsole, $configCommon);
$yiic = dirname(__FILE__) . '/../vendor/yiisoft/yii/framework/yiic.php';
require_once($yiic);
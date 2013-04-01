<?php
$cfgLocalPath = dirname(__FILE__) . '/common-local.php';
$cfgLocal = file_exists($cfgLocalPath) ? require($cfgLocalPath) : array();
return CMap::mergeArray(array(
    'basePath' => dirname(__FILE__) . '/..',
    'components' => array(
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=intelius',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ),
    ),
    'name' => 'Scrapper',
    'params' => array(
        'inteliusUsername' => '',
        'inteliusPassword' => '',
    ),
), $cfgLocal);
<?php
$cfgLocalPath = dirname(__FILE__) . '/console-local.php';
$cfgLocal = file_exists($cfgLocalPath) ? require($cfgLocalPath) : array();
return CMap::mergeArray(array(
    'preload' => array('log'),
    'components' => array(
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),
    ),
), $cfgLocal);
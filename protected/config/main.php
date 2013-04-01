<?php
$cfgLocalPath = dirname(__FILE__) . '/main-local.php';
$cfgLocal = file_exists($cfgLocalPath) ? require($cfgLocalPath) : array();
return CMap::mergeArray(array(
    'preload' => array(
        'bootstrap',
        'log',
    ),
    'import' => array(
        'application.models.*',
        'application.components.*',
        'ext.bootstrap.widgets.TbForm',
        'ext.lib.*',
    ),
    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => false,
            'ipFilters' => array('127.0.0.1', '::1'),
            'generatorPaths' => array(
                'bootstrap.gii'
            ),
        ),
    ),
    'components' => array(
        'bootstrap' => array(
            'class' => 'ext.bootstrap.components.Bootstrap',
            'responsiveCss' => true,
        ),
        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
        ),
        'user' => array(
            'allowAutoLogin' => true,
        ),
    ),
), $cfgLocal);
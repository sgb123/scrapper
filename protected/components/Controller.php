<?php
class Controller extends CController
{
    /**
     * @var string
     */
    public $layout = '//layouts/main';
    /**
     * @var array
     */
    public $menu = array();
    /**
     * @var array
     */
    public $breadcrumbs = array();

    /**
     * @return array
     */
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    /**
     * @return array
     */
    public function accessRules()
    {
        return array('deny',
            'users' => array('*'),
        );
    }
}
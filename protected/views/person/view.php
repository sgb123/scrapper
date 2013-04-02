<?php
/**
 * @var $this PersonController
 * @var $model Person
 */

$this->pageTitle = $model->fullName;
$this->breadcrumbs = array(
    Yii::t('app', 'Person search') => array('/person/index'),
    $this->pageTitle,
);
?>
<h1><?php echo $this->pageTitle; ?></h1>
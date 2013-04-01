<?php
/**
 * @var $this SiteController
 * @var TbForm $form
 */

$this->pageTitle = Yii::t('app', 'Login');
$this->breadcrumbs = array(
    $this->pageTitle,
);
?>
    <h1><?php echo $this->pageTitle; ?></h1>
<?php echo $form; ?>
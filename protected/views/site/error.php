<?php
/**
 * @var $this SiteController
 * @var $message string
 */
$this->pageTitle = Yii::t('app', 'Error');
$this->breadcrumbs = array(
    $this->pageTitle,
);
?>
<h2><?php echo $this->pageTitle; ?></h2>
<div class="error">
    <?php echo CHtml::encode($message); ?>
</div>
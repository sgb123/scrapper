<?php
/**
 * @var $this Controller
 * @var $content string
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo Yii::app()->name; ?></title>
    <style>
        body {
            padding-top: 60px;
        }
    </style>
</head>
<body>
<?php
$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => Yii::app()->name,
    'items' => array(
        array(
            'class' => 'bootstrap.widgets.TbMenu',
            'items' => array(
                array('label' => Yii::t('app', 'Home'), 'url' => array('/site/index')),
                array('label' => Yii::t('app', 'Person search'), 'url' => array('/person/index'),
                    'active' => $this->id == 'person', 'visible' => !Yii::app()->user->isGuest),
                array('label' => Yii::t('app', 'Login'), 'url' => array('/site/login'),
                    'visible' => Yii::app()->user->isGuest),
                array('label' => Yii::t('app', 'Logout'), 'url' => array('/site/logout'),
                    'visible' => !Yii::app()->user->isGuest),
            ),
        ),
    ),
));
?>
<div class="container">
    <?php
    $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
        'links' => $this->breadcrumbs,
    ));
    echo $content;
    ?>
</div>
</body>
</html>

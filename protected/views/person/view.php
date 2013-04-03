<?php
/**
 * @var $this PersonController
 * @var $model Person
 */

/** @var $assetsManager CClientScript */
$assetsManager = Yii::app()->clientScript;
$assetsManager->registerCoreScript('jquery');

$this->pageTitle = $model->fullName;
$this->breadcrumbs = array(
    Yii::t('app', 'Person search') => array('/person/index'),
    $this->pageTitle,
);
?>
<h1><?php echo $this->pageTitle; ?></h1>
<?php
$this->beginWidget('bootstrap.widgets.TbBox', array(
    'title' => Yii::t('app', 'General info'),
));
$this->widget('bootstrap.widgets.TbDetailView', array(
    'attributes' => array(
        'first_name',
        'last_name',
        'age',
    ),
    'data' => $model,
));
$this->endWidget();
?>
<div class="row">
    <div class="span4">
        <?php
        $this->beginWidget('bootstrap.widgets.TbBox', array(
            'title' => Yii::t('app', 'Phones'),
        ));
        $phones = array();
        foreach ($model->addresses as $addressModel) {
            foreach ($addressModel->phones as $phoneModel) {
                $phones[] = CHtml::encode($phoneModel->full);
            }
        }
        echo implode('<br>', $phones);
        $this->endWidget();
        ?>
    </div>
    <div class="span4">
        <?php
        $this->beginWidget('bootstrap.widgets.TbBox', array(
            'title' => Yii::t('app', 'Emails'),
        ));
        $emails = array();
        foreach ($model->emails as $emailModel) {
            $emails[] = CHtml::encode($emailModel->email);
        }
        echo implode('<br>', $emails);
        $this->endWidget();
        ?>
    </div>
    <div class="span4">
        <?php
        $this->beginWidget('bootstrap.widgets.TbBox', array(
            'title' => Yii::t('app', 'Relatives'),
        ));
        $childPersons = array();
        foreach ($model->childPersons as $childPersonModel) {
            $childPersons[] = CHtml::link(CHtml::encode($childPersonModel->fullName),
                array('/person/view', 'id' => $childPersonModel->id));
        }
        echo implode('<br>', $childPersons);
        $this->endWidget();
        ?>
    </div>
</div>
<div class="row">
    <div class="span4">
        <?php
        $this->beginWidget('bootstrap.widgets.TbBox', array(
            'title' => Yii::t('app', 'Addresses'),
        ));
        ?>
        <?php foreach ($model->addresses as $addressModel): ?>
            <div class="well address"
                 data-address-url="<?php echo CHtml::encode($this->createUrl('/person/viewAddress', array(
                     'id' => $addressModel->id
                 ))); ?>">
                <?php
                echo CHtml::encode($addressModel->line_1) . '<br>' . CHtml::encode($addressModel->line_2);
                ?>
            </div>
        <?php endforeach; ?>
        <?php $this->endWidget(); ?>
    </div>
    <div id="address-info" class="span8">
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.address').click(function () {
            $('.address').removeClass('address-selected');
            $(this).addClass('address-selected');
            $.get($(this).attr('data-address-url'), function (data) {
                $('#address-info').html(data);
            });
        });
    });
</script>
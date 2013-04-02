<?php
/**
 * @var $this PersonController
 * @var PersonSearchForm $personSearchForm
 * @var CActiveDataProvider $dataProvider
 */

$this->pageTitle = Yii::t('app', 'Person search');
$this->breadcrumbs = array(
    $this->pageTitle,
);
?>
    <h1><?php echo $this->pageTitle; ?></h1>
<?php
/** @var $form TbActiveForm */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm');
?>
    <label><?php echo Yii::t('app', 'General info'); ?></label>
    <div class="controls controls-row">
        <?php
        echo $form->textField($personSearchForm, 'firstName', array(
            'class' => 'span2',
            'placeholder' => $personSearchForm->getAttributeLabel('firstName'),
        ));
        echo $form->textField($personSearchForm, 'lastName', array(
            'class' => 'span2',
            'placeholder' => $personSearchForm->getAttributeLabel('lastName'),
        ));
        echo $form->textField($personSearchForm, 'cityState', array(
            'class' => 'span2',
            'placeholder' => $personSearchForm->getAttributeLabel('cityState'),
        ));
        ?>
    </div>
    <label><?php echo Yii::t('app', 'Phone'); ?></label>
    <div class="controls controls-row">
        <?php
        echo $form->textField($personSearchForm, 'phoneAreaCode', array(
            'class' => 'span2',
            'placeholder' => $personSearchForm->getAttributeLabel('phoneAreaCode'),
        ));
        echo $form->textField($personSearchForm, 'phonePrefix', array(
            'class' => 'span2',
            'placeholder' => $personSearchForm->getAttributeLabel('phonePrefix'),
        ));
        echo $form->textField($personSearchForm, 'phoneExchange', array(
            'class' => 'span2',
            'placeholder' => $personSearchForm->getAttributeLabel('phoneExchange'),
        ));
        ?>
    </div>
<?php
echo $form->textFieldRow($personSearchForm, 'email', array(
    'class' => 'span2',
    'placeholder' => $personSearchForm->getAttributeLabel('email'),
));
?>
    <div class="form-actions">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'label' => Yii::t('app', 'Search'),
        ));
        $this->endWidget();
        ?>
    </div>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'columns' => array(
        'first_name',
        'last_name',
        'age',
        array(
            'class' => 'CButtonColumn',
            'template' => '{view}',
        ),
    ),
    'dataProvider' => $dataProvider,
));
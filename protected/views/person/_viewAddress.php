<?php
/**
 * @var $this PersonController
 * @var $model Address
 */
?>
<div class="row">
    <div class="span4">
        <?php
        $this->beginWidget('bootstrap.widgets.TbBox', array(
            'title' => Yii::t('app', 'Owner info'),
        ));
        if ($model->addressOwner) {
            $this->widget('bootstrap.widgets.TbDetailView', array(
                'attributes' => array(
                    array(
                        'name' => 'full_name',
                        'type' => 'raw',
                        'value' => implode('<br>', explode(';', CHtml::encode($model->addressOwner->full_name))),
                    ),
                    array(
                        'name' => 'est_market_value',
                        'value' => Yii::app()->numberFormatter->formatCurrency($model->addressOwner->est_market_value, 'USD'),
                    ),
                    array(
                        'name' => 'tax_amount',
                        'value' => Yii::app()->numberFormatter->formatCurrency($model->addressOwner->tax_amount, 'USD'),
                    ),
                    'tax_year',
                ),
                'data' => $model->addressOwner,
            ));
        }
        $this->endWidget();
        ?>
    </div>
    <div class="span4">
        <?php
        $this->beginWidget('bootstrap.widgets.TbBox', array(
            'title' => Yii::t('app', 'Property details'),
        ));
        if ($model->addressDetails) {
            $this->widget('bootstrap.widgets.TbDetailView', array(
                'attributes' => array(
                    array(
                        'name' => 'acres',
                        'value' => Yii::app()->numberFormatter->formatDecimal($model->addressDetails->acres),
                    ),
                    array(
                        'name' => 'bedrooms',
                        'value' => Yii::app()->numberFormatter->formatDecimal($model->addressDetails->bedrooms),
                    ),
                    array(
                        'name' => 'bathrooms',
                        'value' => Yii::app()->numberFormatter->formatDecimal($model->addressDetails->bathrooms),
                    ),
                    'built_year',
                    array(
                        'name' => 'land_area',
                        'value' => Yii::app()->numberFormatter->formatDecimal($model->addressDetails->land_area),
                    ),
                    array(
                        'name' => 'living_area',
                        'value' => Yii::app()->numberFormatter->formatDecimal($model->addressDetails->living_area),
                    ),
                ),
                'data' => $model->addressDetails,
            ));
        }
        $this->endWidget();
        ?>
    </div>
</div>
<div class="row">
    <div class="span4">
        <?php
        $this->beginWidget('bootstrap.widgets.TbBox', array(
            'title' => Yii::t('app', 'Census data'),
        ));
        if ($model->addressCensusData) {
            $this->widget('bootstrap.widgets.TbDetailView', array(
                'attributes' => array(
                    array(
                        'name' => 'households',
                        'value' => Yii::app()->numberFormatter->formatDecimal($model->addressCensusData->households),
                    ),
                    array(
                        'name' => 'families',
                        'value' => Yii::app()->numberFormatter->formatPercentage($model->addressCensusData->families),
                    ),
                    array(
                        'name' => 'male',
                        'value' => Yii::app()->numberFormatter->formatPercentage($model->addressCensusData->male),
                    ),
                    array(
                        'name' => 'female',
                        'value' => Yii::app()->numberFormatter->formatPercentage($model->addressCensusData->male),
                    ),
                ),
                'data' => $model->addressCensusData,
            ));
        }
        $this->endWidget();
        ?>
    </div>
    <div class="span4">
        <?php
        $this->beginWidget('bootstrap.widgets.TbBox', array(
            'title' => Yii::t('app', 'Education'),
        ));
        if ($model->addressEducation) {
            $this->widget('bootstrap.widgets.TbDetailView', array(
                'attributes' => array(
                    array(
                        'name' => 'high_school',
                        'value' => Yii::app()->numberFormatter->formatPercentage($model->addressEducation->high_school),
                    ),
                    array(
                        'name' => 'college',
                        'value' => Yii::app()->numberFormatter->formatPercentage($model->addressEducation->college),
                    ),
                    array(
                        'name' => 'graduate',
                        'value' => Yii::app()->numberFormatter->formatPercentage($model->addressEducation->graduate),
                    ),
                ),
                'data' => $model->addressEducation,
            ));
        }
        $this->endWidget();
        ?>
    </div>
</div>
<div class="row">
    <div class="span4">
        <?php
        $this->beginWidget('bootstrap.widgets.TbBox', array(
            'title' => Yii::t('app', 'Income'),
        ));
        if ($model->addressIncome) {
            $this->widget('bootstrap.widgets.TbDetailView', array(
                'attributes' => array(
                    array(
                        'name' => 'average',
                        'value' => Yii::app()->numberFormatter->formatCurrency($model->addressIncome->average, 'USD'),
                    ),
                    array(
                        'name' => 'less_10',
                        'value' => Yii::app()->numberFormatter->formatPercentage($model->addressIncome->less_10),
                    ),
                    array(
                        'name' => 'slice_10_15',
                        'value' => Yii::app()->numberFormatter->formatPercentage($model->addressIncome->slice_10_15),
                    ),
                    array(
                        'name' => 'slice_15_25',
                        'value' => Yii::app()->numberFormatter->formatPercentage($model->addressIncome->slice_15_25),
                    ),
                    array(
                        'name' => 'slice_25_35',
                        'value' => Yii::app()->numberFormatter->formatPercentage($model->addressIncome->slice_25_35),
                    ),
                    array(
                        'name' => 'slice_35_50',
                        'value' => Yii::app()->numberFormatter->formatPercentage($model->addressIncome->slice_35_50),
                    ),
                    array(
                        'name' => 'slice_50_75',
                        'value' => Yii::app()->numberFormatter->formatPercentage($model->addressIncome->slice_50_75),
                    ),
                    array(
                        'name' => 'slice_75_100',
                        'value' => Yii::app()->numberFormatter->formatPercentage($model->addressIncome->slice_75_100),
                    ),
                    array(
                        'name' => 'slice_100_150',
                        'value' => Yii::app()->numberFormatter->formatPercentage($model->addressIncome->slice_100_150),
                    ),
                    array(
                        'name' => 'slice_150_200',
                        'value' => Yii::app()->numberFormatter->formatPercentage($model->addressIncome->slice_150_200),
                    ),
                    array(
                        'name' => 'more_200',
                        'value' => Yii::app()->numberFormatter->formatPercentage($model->addressIncome->more_200),
                    ),
                ),
                'data' => $model->addressIncome,
            ));
        }
        $this->endWidget();
        ?>
    </div>
    <div class="span4">
        <?php
        $this->beginWidget('bootstrap.widgets.TbBox', array(
            'title' => Yii::t('app', 'Neighbors'),
        ));
        $addressNeighbors = array();
        foreach ($model->addressNeighbors as $addressNeighborModel) {
            $addressNeighbors[] = CHtml::encode($addressNeighborModel->full_name);
        }
        echo implode('<br>', $addressNeighbors);
        $this->endWidget();
        ?>
    </div>
</div>
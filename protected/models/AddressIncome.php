<?php
/**
 * @property integer $id
 * @property integer $average
 * @property float $less_10
 * @property float $slice_10_15
 * @property float $slice_15_25
 * @property float $slice_25_35
 * @property float $slice_35_50
 * @property float $slice_50_75
 * @property float $slice_75_100
 * @property float $slice_100_150
 * @property float $slice_150_200
 * @property float $more_200
 * @property Address[] $addresses
 */
class AddressIncome extends CActiveRecord
{
    /**
     * @param string $className
     * @return AddressIncome
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string
     */
    public function tableName()
    {
        return 'address_income';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('average, less_10, slice_10_15, slice_15_25, slice_25_35, slice_35_50, slice_50_75, slice_75_100, slice_100_150, slice_150_200, more_200', 'required'),
            array('average', 'numerical', 'integerOnly' => true),
            array('less_10, slice_10_15, slice_15_25, slice_25_35, slice_35_50, slice_50_75, slice_75_100, slice_100_150, slice_150_200, more_200', 'numerical'),
            array('id, average, less_10, slice_10_15, slice_15_25, slice_25_35, slice_35_50, slice_50_75, slice_75_100, slice_100_150, slice_150_200, more_200', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array
     */
    public function relations()
    {
        return array(
            'addresses' => array(self::HAS_MANY, 'Address', 'address_income'),
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'average' => Yii::t('app', 'Average'),
            'less_10' => Yii::t('app', 'Less $10K'),
            'slice_10_15' => Yii::t('app', '$10K to $14.999'),
            'slice_15_25' => Yii::t('app', '$15K to $24.999'),
            'slice_25_35' => Yii::t('app', '$25K to $34.999'),
            'slice_35_50' => Yii::t('app', '$35K to $49.999'),
            'slice_50_75' => Yii::t('app', '$50K to $74.999'),
            'slice_75_100' => Yii::t('app', '$75K to $99.999'),
            'slice_100_150' => Yii::t('app', '$100K to 149.999'),
            'slice_150_200' => Yii::t('app', '$150K to 200.999'),
            'more_200' => Yii::t('app', '$200K or more'),
        );
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('average', $this->average);
        $criteria->compare('less_10', $this->less_10);
        $criteria->compare('slice_10_15', $this->slice_10_15);
        $criteria->compare('slice_15_25', $this->slice_15_25);
        $criteria->compare('slice_25_35', $this->slice_25_35);
        $criteria->compare('slice_35_50', $this->slice_35_50);
        $criteria->compare('slice_50_75', $this->slice_50_75);
        $criteria->compare('slice_75_100', $this->slice_75_100);
        $criteria->compare('slice_100_150', $this->slice_100_150);
        $criteria->compare('slice_150_200', $this->slice_150_200);
        $criteria->compare('more_200', $this->more_200);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
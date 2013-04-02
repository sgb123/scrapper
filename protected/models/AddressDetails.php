<?php
/**
 * @property integer $id
 * @property float $acres
 * @property integer $bedrooms
 * @property integer $bathrooms
 * @property integer $built_year
 * @property integer $land_area
 * @property integer $living_area
 * @property Address[] $addresses
 */
class AddressDetails extends CActiveRecord
{
    /**
     * @param string $className
     * @return AddressDetails
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
        return 'address_details';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('bedrooms, bathrooms, built_year, land_area, living_area', 'numerical', 'integerOnly' => true),
            array('acres', 'numerical'),
            array('id, acres, bedrooms, bathrooms, built_year, land_area, living_area', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array
     */
    public function relations()
    {
        return array(
            'addresses' => array(self::HAS_MANY, 'Address', 'address_details_id'),
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'acres' => Yii::t('app', 'Acres'),
            'bedrooms' => Yii::t('app', 'Bedrooms'),
            'bathrooms' => Yii::t('app', 'Bathrooms'),
            'built_year' => Yii::t('app', 'Built year'),
            'land_area' => Yii::t('app', 'Land area'),
            'living_area' => Yii::t('app', 'Living area'),
        );
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('acres', $this->acres);
        $criteria->compare('bedrooms', $this->bedrooms);
        $criteria->compare('bathrooms', $this->bathrooms);
        $criteria->compare('built_year', $this->built_year);
        $criteria->compare('land_area', $this->land_area);
        $criteria->compare('living_area', $this->living_area);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
<?php
/**
 * @property integer $id
 * @property integer $households
 * @property float $families
 * @property float $male
 * @property float $female
 * @property Address[] $addresses
 */
class AddressCensusData extends CActiveRecord
{
    /**
     * @param string $className
     * @return AddressCensusData
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
        return 'address_census_data';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('households, families, male, female', 'required'),
            array('households', 'numerical', 'integerOnly' => true),
            array('families, male, female', 'numerical'),
            array('id, households, families, male, female', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array
     */
    public function relations()
    {
        return array(
            'addresses' => array(self::HAS_MANY, 'Address', 'address_census_data_id'),
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'households' => Yii::t('app', 'Households'),
            'families' => Yii::t('app', 'Families'),
            'male' => Yii::t('app', 'Male'),
            'female' => Yii::t('app', 'Female'),
        );
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('households', $this->households);
        $criteria->compare('families', $this->families);
        $criteria->compare('male', $this->male);
        $criteria->compare('female', $this->female);
        $criteria->compare('address_census_data_id', $this->address_census_data_id);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
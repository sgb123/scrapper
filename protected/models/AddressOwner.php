<?php
/**
 * @property integer $id
 * @property string $full_name
 * @property integer $est_market_value
 * @property integer $tax_amount
 * @property integer $tax_year
 * @property Address[] $addresses
 */
class AddressOwner extends CActiveRecord
{
    /**
     * @param string $className
     * @return AddressOwner
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
        return 'address_owner';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('full_name, est_market_value, tax_amount, tax_year', 'required'),
            array('est_market_value, tax_amount, tax_year', 'numerical', 'integerOnly' => true),
            array('full_name', 'length', 'max' => 255),
            array('id, full_name, est_market_value, tax_amount, tax_year', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array
     */
    public function relations()
    {
        return array(
            'addresses' => array(self::HAS_MANY, 'Address', 'address_owner_id'),
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'full_name' => Yii::t('app', 'Full name'),
            'est_market_value' => Yii::t('app', 'Est market value'),
            'tax_amount' => Yii::t('app', 'Tax amount'),
            'tax_year' => Yii::t('app', 'Tax year'),
        );
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('full_name', $this->full_name, true);
        $criteria->compare('est_market_value', $this->est_market_value);
        $criteria->compare('tax_amount', $this->tax_amount);
        $criteria->compare('tax_year', $this->tax_year);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
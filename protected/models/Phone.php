<?php
/**
 * @property integer $id
 * @property integer $areaCode
 * @property integer $prefix
 * @property integer $exchange
 * @property Address[] $addresses
 */
class Phone extends CActiveRecord
{
    /**
     * @param string $className
     * @return Phone
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
        return 'phone';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('areaCode, prefix, exchange', 'required'),
            array('areaCode, prefix, exchange', 'numerical', 'integerOnly' => true),
            array('id, areaCode, prefix, exchange', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array
     */
    public function relations()
    {
        return array(
            'addresses' => array(self::HAS_MANY, 'Address', 'phone_id'),
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'areaCode' => Yii::t('app', 'Area code'),
            'prefix' => Yii::t('app', 'Prefix'),
            'exchange' => Yii::t('app', 'Exchange'),
        );
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('areaCode', $this->areaCode);
        $criteria->compare('prefix', $this->prefix);
        $criteria->compare('exchange', $this->exchange);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
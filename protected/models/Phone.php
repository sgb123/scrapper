<?php
/**
 * @property integer $id
 * @property integer $area_code
 * @property integer $prefix
 * @property integer $exchange
 * @property integer $address_id
 * @property Address $address
 * @property string $full
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
     * @param int $areaCode
     * @param int $prefix
     * @param int $exchange
     * @return Phone
     */
    public static function getOrAdd($areaCode, $prefix, $exchange)
    {
        $model = self::model()->findByAttributes(array(
            'area_code' => $areaCode,
            'prefix' => $prefix,
            'exchange' => $exchange,
        ));
        if ($model) {
            return $model;
        }
        $model = new self();
        $model->area_code = $areaCode;
        $model->prefix = $prefix;
        $model->exchange = $exchange;
        $model->save();
        return $model;
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
            array('area_code, prefix, exchange', 'required'),
            array('area_code, prefix, exchange, address_id', 'numerical', 'integerOnly' => true),
            array('id, area_code, prefix, exchange, address_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array
     */
    public function relations()
    {
        return array(
            'address' => array(self::BELONGS_TO, 'Address', 'address_id'),
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'area_code' => Yii::t('app', 'Area code'),
            'prefix' => Yii::t('app', 'Prefix'),
            'exchange' => Yii::t('app', 'Exchange'),
            'address_id' => Yii::t('app', 'Address'),
        );
    }

    public function getFull()
    {
        return '(' . $this->area_code . ') ' . $this->prefix . '-' . $this->exchange;
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('area_code', $this->area_code);
        $criteria->compare('prefix', $this->prefix);
        $criteria->compare('exchange', $this->exchange);
        $criteria->compare('address_id', $this->address_id);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
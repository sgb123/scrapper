<?php
/**
 * @property integer $id
 * @property string $full_name
 * @property integer $address_id
 * @property Address $address
 */
class AddressNeighbor extends CActiveRecord
{
    /**
     * @param string $className
     * @return AddressNeighbor
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
        return 'address_neighbor';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('full_name, address_id', 'required'),
            array('address_id', 'numerical', 'integerOnly' => true),
            array('full_name', 'length', 'max' => 255),
            array('id, full_name, address_id', 'safe', 'on' => 'search'),
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
            'full_name' => Yii::t('app', 'Full name'),
            'address_id' => Yii::t('app', 'Address'),
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
        $criteria->compare('address_id', $this->address_id);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
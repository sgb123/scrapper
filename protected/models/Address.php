<?php
/**
 * @property integer $id
 * @property string $addressUrl
 * @property string $line_1
 * @property string $line_2
 * @property integer $phone_id
 * @property integer $person_id
 * @property Person $person
 * @property Phone $phone
 */
class Address extends CActiveRecord
{
    /**
     * @param string $className
     * @return Address
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
        return 'address';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('addressUrl, line_1, line_2, person_id', 'required'),
            array('phone_id, person_id', 'numerical', 'integerOnly' => true),
            array('addressUrl, line_1, line_2', 'length', 'max' => 255),
            array('id, addressUrl, line_1, line_2, phone_id, person_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array
     */
    public function relations()
    {
        return array(
            'person' => array(self::BELONGS_TO, 'Person', 'person_id'),
            'phone' => array(self::BELONGS_TO, 'Phone', 'phone_id'),
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'addressUrl' => Yii::t('app', 'Address url'),
            'line_1' => Yii::t('app', 'Line 1'),
            'line_2' => Yii::t('app', 'Line 2'),
            'phone_id' => Yii::t('app', 'Phone'),
            'person_id' => Yii::t('app', 'Person'),
        );
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('addressUrl', $this->addressUrl, true);
        $criteria->compare('line_1', $this->line_1, true);
        $criteria->compare('line_2', $this->line_2, true);
        $criteria->compare('phone_id', $this->phone_id);
        $criteria->compare('person_id', $this->person_id);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
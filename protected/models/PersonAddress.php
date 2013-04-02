<?php
/**
 * @property integer $id
 * @property integer $person_id
 * @property integer $address_id
 * @property Address $address
 * @property Person $person
 */
class PersonAddress extends CActiveRecord
{
    /**
     * @param int $personId
     * @param int $addressId
     * @return CActiveRecord|PersonAddress
     */
    public static function getOrAdd($personId, $addressId)
    {
        $model = self::model()->findByAttributes(array(
            'person_id' => $personId,
            'address_id' => $addressId,
        ));
        if ($model) {
            return $model;
        }
        $model = new self();
        $model->person_id = $personId;
        $model->address_id = $addressId;
        $model->save();
        return $model;
    }

    /**
     * @param string $className
     * @return PersonAddress
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
        return 'person_address';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('person_id, address_id', 'required'),
            array('person_id, address_id', 'numerical', 'integerOnly' => true),
            array('id, person_id, address_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array
     */
    public function relations()
    {
        return array(
            'address' => array(self::BELONGS_TO, 'Address', 'address_id'),
            'person' => array(self::BELONGS_TO, 'Person', 'person_id'),
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'person_id' => Yii::t('app', 'Person'),
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
        $criteria->compare('person_id', $this->person_id);
        $criteria->compare('address_id', $this->address_id);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
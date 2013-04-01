<?php
/**
 * @property integer $id
 * @property string $profile_url
 * @property string $first_name
 * @property string $last_name
 * @property integer $age
 * @property Address[] $addresses
 */
class Person extends CActiveRecord
{
    /**
     * @param string $className
     * @return Person
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
        return 'person';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('profile_url, first_name, last_name', 'required'),
            array('age', 'numerical', 'integerOnly' => true),
            array('profile_url, first_name, last_name', 'length', 'max' => 255),
            array('id, profile_url, first_name, last_name, age', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array
     */
    public function relations()
    {
        return array(
            'addresses' => array(self::HAS_MANY, 'Address', 'person_id'),
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'profile_url' => Yii::t('app', 'Profile url'),
            'first_name' => Yii::t('app', 'First name'),
            'last_name' => Yii::t('app', 'Last name'),
            'age' => Yii::t('app', 'Age'),
        );
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('profile_url', $this->profile_url, true);
        $criteria->compare('first_name', $this->first_name, true);
        $criteria->compare('last_name', $this->last_name, true);
        $criteria->compare('age', $this->age);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
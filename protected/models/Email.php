<?php
/**
 * @property integer $id
 * @property string $email
 * @property integer $person_id
 * @property Person $person
 */
class Email extends CActiveRecord
{
    /**
     * @param string $className
     * @return Email
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
        return 'email';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('email, person_id', 'required'),
            array('person_id', 'numerical', 'integerOnly' => true),
            array('email', 'length', 'max' => 255),
            array('id, email, person_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array
     */
    public function relations()
    {
        return array(
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
            'email' => Yii::t('app', 'Email'),
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
        $criteria->compare('email', $this->email, true);
        $criteria->compare('person_id', $this->person_id);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
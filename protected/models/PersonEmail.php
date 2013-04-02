<?php
/**
 * @property integer $id
 * @property integer $person_id
 * @property integer $email_id
 * @property Email $email
 * @property Person $person
 */
class PersonEmail extends CActiveRecord
{
    /**
     * @param int $personId
     * @param int $emailId
     * @return CActiveRecord|PersonEmail
     */
    public static function getOrAdd($personId, $emailId)
    {
        $model = self::model()->findByAttributes(array(
            'person_id' => $personId,
            'email_id' => $emailId,
        ));
        if ($model) {
            return $model;
        }
        $model = new self();
        $model->person_id = $personId;
        $model->email_id = $emailId;
        $model->save();
        return $model;
    }

    /**
     * @param string $className
     * @return PersonEmail
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
        return 'person_email';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('person_id, email_id', 'required'),
            array('person_id, email_id', 'numerical', 'integerOnly' => true),
            array('id, person_id, email_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array
     */
    public function relations()
    {
        return array(
            'email' => array(self::BELONGS_TO, 'Email', 'email_id'),
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
            'email_id' => Yii::t('app', 'Email'),
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
        $criteria->compare('email_id', $this->email_id);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
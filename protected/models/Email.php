<?php
/**
 * @property integer $id
 * @property string $email
 * @property integer $person_id
 * @property PersonEmail[] $personRelations
 * @property Person[] $persons
 */
class Email extends CActiveRecord
{
    /**
     * @param string $email
     * @return CActiveRecord|Email
     */
    public static function getOrAdd($email)
    {
        $model = self::model()->findByAttributes(array(
            'email' => $email,
        ));
        if ($model) {
            return $model;
        }
        $model = new self();
        $model->email = $email;
        $model->save();
        return $model;
    }

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
            array('email', 'required'),
            array('email', 'length', 'max' => 255),
            array('id, email', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array
     */
    public function relations()
    {
        return array(
            'personRelations' => array(self::HAS_MANY, 'PersonEmail', 'email_id'),
            'persons' => array(self::MANY_MANY, 'Person', 'person_email(email_id, person_id)'),
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
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
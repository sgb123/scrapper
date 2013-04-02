<?php
/**
 * @property integer $id
 * @property string $profile_url
 * @property string $first_name
 * @property string $last_name
 * @property integer $age
 * @property string $processed
 * @property Address[] $addresses
 * @property Email[] $emails
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
     * @param array $data
     * @return Person
     * @throws CException
     */
    public static function processInteliusData($data)
    {
        if (!isset($data['profileUrl']) || !isset($data['firstName']) || !isset($data['lastName']) ||
            !isset($data['age'])
        ) {
            throw new CException();
        }
        $model = self::model()->findByAttributes(array(
            'first_name' => $data['firstName'],
            'last_name' => $data['lastName'],
            'age' => $data['age'],
        ));
        if ($model) {
            return $model;
        }
        $model = new self();
        $model->profile_url = $data['profileUrl'];
        $model->first_name = $data['firstName'];
        $model->last_name = $data['lastName'];
        $model->age = $data['age'];
        $model->save();
        return $model;
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
            array('first_name, last_name', 'length', 'max' => 255),
            array('processed', 'safe'),
            array('id, profile_url, first_name, last_name, age, processed', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array
     */
    public function relations()
    {
        return array(
            'addresses' => array(self::HAS_MANY, 'Address', 'person_id'),
            'emails' => array(self::HAS_MANY, 'Email', 'person_id'),
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
            'processed' => Yii::t('app', 'Processed'),
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
        $criteria->compare('processed', $this->processed, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function process()
    {
        // @todo Person::process()
    }
}
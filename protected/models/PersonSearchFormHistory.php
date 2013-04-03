<?php
/**
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $city_state
 * @property integer $phone_area_code
 * @property integer $phone_prefix
 * @property integer $phone_exchange
 * @property string $email
 * @property string $updated
 */
class PersonSearchFormHistory extends CActiveRecord
{
    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $cityState
     * @param integer $phoneAreaCode
     * @param integer $phonePrefix
     * @param integer $phoneExchange
     * @param string $email
     * @return PersonSearchFormHistory
     */
    public static function getOrAdd($firstName, $lastName, $cityState, $phoneAreaCode, $phonePrefix, $phoneExchange,
                                    $email)
    {
        $attributes = array(
            'first_name' => $firstName,
            'last_name' => $lastName,
            'city_state' => $cityState,
            'phone_area_code' => $phoneAreaCode,
            'phone_prefix' => $phonePrefix,
            'phone_exchange' => $phoneExchange,
            'email' => $email,
        );
        $model = self::model()->findByAttributes($attributes);
        if ($model) {
            return $model;
        }
        $model = new self();
        $model->attributes = $attributes;
        $model->save();
        return $model;

    }

    /**
     * @param string $className
     * @return PersonSearchFormHistory
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
        return 'person_search_form_history';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('updated', 'required'),
            array('phone_area_code, phone_prefix, phone_exchange', 'numerical', 'integerOnly' => true),
            array('first_name, last_name, city_state, email', 'length', 'max' => 255),
            array('updated', 'safe'),
            array('first_name, last_name, city_state, phone_area_code, phone_prefix, phone_exchange, email', 'default',
                'setOnEmpty' => true, 'value' => null),
            array('id, first_name, last_name, city_state, phone_area_code, phone_prefix, phone_exchange, email, updated',
                'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array
     */
    public function relations()
    {
        return array();
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'first_name' => Yii::t('app', 'First name'),
            'last_name' => Yii::t('app', 'Last name'),
            'city_state' => Yii::t('app', 'City, state'),
            'phone_area_code' => Yii::t('app', 'Phone area code'),
            'phone_prefix' => Yii::t('app', 'Phone prefix'),
            'phone_exchange' => Yii::t('app', 'Phone exchange'),
            'email' => Yii::t('app', 'Email'),
            'updated' => Yii::t('app', 'Updated'),
        );
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('first_name', $this->first_name, true);
        $criteria->compare('last_name', $this->last_name, true);
        $criteria->compare('city_state', $this->city_state, true);
        $criteria->compare('phone_area_code', $this->phone_area_code);
        $criteria->compare('phone_prefix', $this->phone_prefix);
        $criteria->compare('phone_exchange', $this->phone_exchange);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('updated', $this->updated, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
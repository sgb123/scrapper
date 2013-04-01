<?php
/**
 * @property integer $id
 * @property string $username
 * @property string $password
 */
class User extends CActiveRecord
{
    /**
     * @param string $className
     * @return User
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
        return 'user';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('username, password', 'required'),
            array('username, password', 'length', 'max' => 255),
            array('id, username, password', 'safe', 'on' => 'search'),
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
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
        );
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
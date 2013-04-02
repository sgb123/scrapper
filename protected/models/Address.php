<?php
/**
 * @property integer $id
 * @property string $address_url
 * @property string $line_1
 * @property string $line_2
 * @property PersonAddress[] $personAddresses
 * @property Address[] $persons
 * @property Phone[] $phones
 */
class Address extends CActiveRecord
{
    /**
     * @param string $addressUrl
     * @param string $line1
     * @param string $line2
     * @return Address
     */
    public static function getOrAdd($addressUrl, $line1, $line2)
    {
        $model = self::model()->findByAttributes(array(
            'address_url' => $addressUrl,
        ));
        if ($model) {
            return $model;
        }
        $model = new self();
        $model->address_url = $addressUrl;
        $model->line_1 = $line1;
        $model->line_2 = $line2;
        $model->save();
        return $model;
    }

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
            array('address_url, line_1, line_2', 'required'),
            array('address_url, line_1, line_2', 'length', 'max' => 255),
            array('id, address_url, line_1, line_2', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array
     */
    public function relations()
    {
        return array(
            'personAddresses' => array(self::HAS_MANY, 'PersonAddress', 'address_id'),
            'persons' => array(self::MANY_MANY, 'Person', 'person_address(address_id, person_id)'),
            'phones' => array(self::HAS_MANY, 'Phone', 'address_id'),
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'address_url' => Yii::t('app', 'Address url'),
            'line_1' => Yii::t('app', 'Line 1'),
            'line_2' => Yii::t('app', 'Line 2'),
        );
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('address_url', $this->address_url, true);
        $criteria->compare('line_1', $this->line_1, true);
        $criteria->compare('line_2', $this->line_2, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
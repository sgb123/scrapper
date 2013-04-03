<?php
/**
 * @property integer $id
 * @property string $address_url
 * @property string $line_1
 * @property string $line_2
 * @property integer $address_owner_id
 * @property integer $address_details_id
 * @property integer $address_census_data_id
 * @property integer $address_education_id
 * @property integer $address_income_id
 * @property AddressIncome $addressIncome
 * @property AddressEducation $addressEducation
 * @property AddressCensusData $addressCensusData
 * @property AddressDetails $addressDetails
 * @property AddressOwner $addressOwner
 * @property AddressNeighbor[] $addressNeighbors
 * @property PersonAddress[] $personRelations
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
            array('address_owner_id, address_details_id, address_census_data_id, address_education_id, address_income_id',
                'numerical', 'integerOnly' => true),
            array('address_url, line_1, line_2', 'length', 'max' => 255),
            array('id, address_url, line_1, line_2, address_details_id, address_owner_id, address_census_data_id, address_education_id, address_income_id',
                'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array
     */
    public function relations()
    {
        return array(
            'addressIncome' => array(self::BELONGS_TO, 'AddressIncome', 'address_income_id'),
            'addressEducation' => array(self::BELONGS_TO, 'AddressEducation', 'address_education_id'),
            'addressCensusData' => array(self::BELONGS_TO, 'AddressCensusData', 'address_census_data_id'),
            'addressDetails' => array(self::BELONGS_TO, 'AddressDetails', 'address_details_id'),
            'addressOwner' => array(self::BELONGS_TO, 'AddressOwner', 'address_owner_id'),
            'addressNeighbors' => array(self::HAS_MANY, 'AddressNeighbor', 'address_id'),
            'personRelations' => array(self::HAS_MANY, 'PersonAddress', 'address_id'),
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
            'address_owner_id' => Yii::t('app', 'Address owner'),
            'address_details_id' => Yii::t('app', 'Address details'),
            'address_census_data_id' => Yii::t('app', 'Address census data'),
            'address_education_id' => Yii::t('app', 'Address education'),
            'address_income_id' => Yii::t('app', 'Address income'),
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
        $criteria->compare('address_owner_id', $this->address_owner_id);
        $criteria->compare('address_details_id', $this->address_details_id);
        $criteria->compare('address_education_id', $this->address_education_id);
        $criteria->compare('address_income_id', $this->address_income_id);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
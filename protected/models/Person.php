<?php
/**
 * @property integer $id
 * @property string $profile_url
 * @property string $first_name
 * @property string $last_name
 * @property integer $age
 * @property string $processed
 * @property PersonAddress[] $addressRelations
 * @property Address[] $addresses
 * @property PersonEmail[] $emailRelations
 * @property Email[] $emails
 * @property PersonPerson[] $parentPersonRelations
 * @property Person[] $parentPersons
 * @property PersonPerson[] $childPersonRelations
 * @property Person[] $childPersons
 * @property string fullName
 */
class Person extends CActiveRecord
{
    /**
     * @param array $data
     * @return Person
     * @throws CException
     */
    public static function processInteliusData($data)
    {
        if (empty($data['profileUrl']) || empty($data['firstName']) || empty($data['lastName']) ||
            empty($data['age'])
        ) {
            throw new CException();
        }
        return self::getOrAdd($data['profileUrl'], $data['firstName'], $data['lastName'], $data['age']);
    }

    public static function getOrAdd($profileUrl, $firstName, $lastName, $age)
    {
        $model = self::model()->findByAttributes(array(
            'first_name' => $firstName,
            'last_name' => $lastName,
            'age' => $age,
        ));
        if ($model) {
            return $model;
        }
        $model = new self();
        $model->profile_url = $profileUrl;
        $model->first_name = $firstName;
        $model->last_name = $lastName;
        $model->age = $age;
        $model->save();
        return $model;
    }

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
            'addressRelations' => array(self::HAS_MANY, 'PersonAddress', 'person_id'),
            'addresses' => array(self::MANY_MANY, 'Address', 'person_address(person_id, address_id)'),
            'emailRelations' => array(self::HAS_MANY, 'PersonEmail', 'person_id'),
            'emails' => array(self::MANY_MANY, 'Email', 'person_email(person_id, email_id)'),
            'parentPersonRelations' => array(self::HAS_MANY, 'PersonPerson', 'child_person_id'),
            'parentPersons' => array(self::MANY_MANY, 'Person', 'person_person(child_person_id, parent_person_id)'),
            'childPersonRelations' => array(self::HAS_MANY, 'PersonPerson', 'parent_person_id'),
            'childPersons' => array(self::MANY_MANY, 'Person', 'person_person(parent_person_id, child_person_id)'),
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

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
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
        $intelius = new Intelius(Yii::app()->params['inteliusUsername'], Yii::app()->params['inteliusPassword']);
        $data = $intelius->processProfile($this->profile_url);
        if (!empty($data['age'])) {
            $this->age = $data['age'];
        }
        if (!empty($data['emails'])) {
            foreach ($data['emails'] as $email) {
                PersonEmail::getOrAdd($this->id, Email::getOrAdd($email)->id);
            }
        }
        if (!empty($data['addresses'])) {
            foreach ($data['addresses'] as $address) {
                $addressModel = Address::model()->getOrAdd($address['addressUrl'], $address['line1'], $address['line2']);
                if (!empty($address['phone'])) {
                    $phoneModel = Phone::getOrAdd($address['phone']['areaCode'], $address['phone']['prefix'],
                        $address['phone']['exchange']);
                    $phoneModel->address_id = $addressModel->id;
                    $phoneModel->save();
                }
                PersonAddress::getOrAdd($this->id, $addressModel->id);
                if (!empty($address['owner'])) {
                    $addressOwnerModel = new AddressOwner();
                    $addressOwnerModel->full_name = $address['owner']['fullName'];
                    $addressOwnerModel->est_market_value = $address['owner']['estMarketValue'];
                    $addressOwnerModel->tax_amount = $address['owner']['taxAmount'];
                    $addressOwnerModel->tax_year = $address['owner']['taxYear'];
                    $addressOwnerModel->save();
                    $addressModel->address_owner_id = $addressOwnerModel->id;
                }
                if (!empty($address['details'])) {
                    $addressDetailsModel = new AddressDetails();
                    $addressDetailsModel->acres = $address['details']['acres'];
                    $addressDetailsModel->bedrooms = $address['details']['bedrooms'];
                    $addressDetailsModel->bathrooms = $address['details']['bathrooms'];
                    $addressDetailsModel->built_year = $address['details']['builtYear'];
                    $addressDetailsModel->land_area = $address['details']['landArea'];
                    $addressDetailsModel->land_area = $address['details']['livingArea'];
                    $addressDetailsModel->save();
                    $addressModel->address_details_id = $addressDetailsModel->id;
                }
                if (!empty($address['neighbors'])) {
                    foreach ($address['neighbors'] as $neighbor) {
                        // @todo Many to many (?)
                        $neighborModel = new AddressNeighbor();
                        $neighborModel->full_name = $neighbor;
                        $neighborModel->address_id = $addressModel->id;
                        $neighborModel->save();
                    }
                }
                if (!empty($address['censusData'])) {
                    $addressCensusData = new AddressCensusData();
                    $addressCensusData->households = $address['censusData']['households'];
                    $addressCensusData->families = $address['censusData']['families'];
                    $addressCensusData->male = $address['censusData']['male'];
                    $addressCensusData->female = $address['censusData']['female'];
                    $addressCensusData->save();
                    $addressModel->address_census_data_id = $addressCensusData->id;
                }
                if (!empty($address['education'])) {
                    $addressEducationModel = new AddressEducation();
                    $addressEducationModel->high_school = $address['education']['highSchool'];
                    $addressEducationModel->college = $address['education']['college'];
                    $addressEducationModel->graduate = $address['education']['graduate'];
                    $addressEducationModel->save();
                    $addressModel->address_education_id = $addressEducationModel->id;
                }
                if (!empty($address['income'])) {
                    $addressIncomeModel = new AddressIncome();
                    $addressIncomeModel->average = $address['income']['average'];
                    $addressIncomeModel->less_10 = $address['income']['less_10'];
                    $addressIncomeModel->slice_10_15 = $address['income']['slice_10_15'];
                    $addressIncomeModel->slice_15_25 = $address['income']['slice_15_25'];
                    $addressIncomeModel->slice_25_35 = $address['income']['slice_25_35'];
                    $addressIncomeModel->slice_35_50 = $address['income']['slice_35_50'];
                    $addressIncomeModel->slice_50_75 = $address['income']['slice_50_75'];
                    $addressIncomeModel->slice_75_100 = $address['income']['slice_75_100'];
                    $addressIncomeModel->slice_100_150 = $address['income']['slice_100_150'];
                    $addressIncomeModel->slice_150_200 = $address['income']['slice_150_200'];
                    $addressIncomeModel->more_200 = $address['income']['more_200'];
                    $addressIncomeModel->save();
                    $addressModel->address_income_id = $addressIncomeModel->id;
                }
                $addressModel->save();
            }
        }
        if (!empty($data['relatives'])) {
            foreach ($data['relatives'] as $relative) {
                PersonPerson::getOrAdd($this->id, self::processInteliusData($relative)->id);
            }
        }
        $this->processed = new CDbExpression('current_timestamp');
        $this->save();
    }
}
<?php
class PersonSearchForm extends CFormModel
{
    /**
     * @var string
     */
    public $firstName;
    /**
     * @var string
     */
    public $lastName;
    /**
     * @var string
     */
    public $cityState;
    /**
     * @var string
     */
    public $phoneAreaCode;
    /**
     * @var string
     */
    public $phonePrefix;
    /**
     * @var string
     */
    public $phoneExchange;
    /**
     * @var string
     */
    public $email;

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('firstName, lastName, cityState, phoneAreaCode, phonePrefix, phoneExchange, email', 'safe'),
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'firstName' => Yii::t('app', 'First name'),
            'lastName' => Yii::t('app', 'Last name'),
            'cityState' => Yii::t('app', 'City, State'),
            'phoneAreaCode' => Yii::t('app', 'Phone area code'),
            'phonePrefix' => Yii::t('app', 'Phone prefix'),
            'phoneExchange' => Yii::t('app', 'Phone exchange'),
            'email' => Yii::t('app', 'Email'),
        );
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        $intelius = new Intelius(Yii::app()->params['inteliusUsername'], Yii::app()->params['inteliusPassword']);
        $data = array();
        if ($this->firstName || $this->lastName || $this->cityState) {
            $data = CMap::mergeArray($data, $intelius->searchByName($this->firstName, $this->lastName, $this->cityState));
        }
        if ($this->phoneAreaCode && $this->phonePrefix && $this->phoneExchange) {
            $data = CMap::mergeArray($data,
                $intelius->searchByPhone($this->phoneAreaCode, $this->phonePrefix, $this->phoneExchange));
        }
        if ($this->email) {
            $data = CMap::mergeArray($data, $intelius->searchByEmail($this->email));
        }
        $modelIds = array();
        foreach ($data as $dataItem) {
            $modelIds[] = Person::processInteliusData($dataItem)->id;
        }
        $criteria = new CDbCriteria();
        if ($modelIds) {
            $criteria->addInCondition('t.id', $modelIds);
        }
        $criteria->compare('t.first_name', $this->firstName, true);
        $criteria->compare('t.last_name', $this->lastName, true);
        if ($this->email) {
            $criteria->with['emails'] = array(
                'condition' => 'emails.email LIKE :email',
                'params' => array('email' => $this->email),
            );
        }
        return new CActiveDataProvider('Person', array(
            'criteria' => $criteria,
            'sort' => false,
        ));
    }
}
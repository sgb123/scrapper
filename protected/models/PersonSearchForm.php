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
            array('phoneAreaCode, phonePrefix, phoneExchange', 'numerical', 'integerOnly' => true),
            array('firstName, lastName, cityState, email', 'length', 'max' => 255),
            array('firstName, lastName, cityState, phoneAreaCode, phonePrefix, phoneExchange, email', 'default',
                'setOnEmpty' => true, 'value' => null),
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
            'cityState' => Yii::t('app', 'City, state'),
            'phoneAreaCode' => Yii::t('app', 'Phone area code'),
            'phonePrefix' => Yii::t('app', 'Phone prefix'),
            'phoneExchange' => Yii::t('app', 'Phone exchange'),
            'email' => Yii::t('app', 'Email'),
        );
    }

    /**
     * @return CSqlDataProvider
     */
    public function search()
    {
        if (!$this->validate()) {
            return null;
        }
        $historyModel = PersonSearchFormHistory::getOrAdd($this->firstName, $this->lastName, $this->cityState,
            $this->phoneAreaCode, $this->phonePrefix, $this->phoneExchange, $this->email);
        if (!$historyModel->updated ||
            strtotime($historyModel->updated) < time() - Yii::app()->params['inteliusUpdatePeriod']
        ) {
            $intelius = new Intelius(Yii::app()->params['inteliusUsername'], Yii::app()->params['inteliusPassword']);
            $data = array();
            if ($this->firstName || $this->lastName || $this->cityState) {
                $data = CMap::mergeArray($data,
                    $intelius->searchByName($this->firstName, $this->lastName, $this->cityState));
            }
            if ($this->phoneAreaCode && $this->phonePrefix && $this->phoneExchange) {
                $data = CMap::mergeArray($data,
                    $intelius->searchByPhone($this->phoneAreaCode, $this->phonePrefix, $this->phoneExchange));
            }
            if ($this->email) {
                $data = CMap::mergeArray($data, $intelius->searchByEmail($this->email));
            }
            foreach ($data as $dataItem) {
                Person::processInteliusData($dataItem);
            }
            $historyModel->updated = new CDbExpression('current_timestamp');
            $historyModel->save();
        }
        $params = array(
            'firstName' => '%' . $this->firstName . '%',
            'lastName' => '%' . $this->lastName . '%',
            'cityState' => '%' . $this->cityState . '%',
            'phoneAreaCode' => $this->phoneAreaCode,
            'phonePrefix' => $this->phonePrefix,
            'phoneExchange' => $this->phoneExchange,
            'email' => '%' . $this->email . '%',
        );
        $sql = 'SELECT p.*
                  FROM person p
                LEFT JOIN person_address pa
                  ON pa.person_id = p.id
                LEFT JOIN address a
                  ON a.id = pa.address_id
                LEFT JOIN phone ph
                  ON ph.address_id = a.id
                LEFT JOIN person_email pe
                  ON pe.person_id = p.id
                LEFT JOIN email e
                  ON e.id = pe.email_id
                WHERE
                  p.first_name LIKE :firstName AND p.last_name LIKE :lastName AND
                    (:cityState = \'%%\' or a.line_2 LIKE :cityState) AND
                    (:phoneAreaCode IS null OR ph.area_code = :phoneAreaCode) AND
                    (:phonePrefix IS null OR ph.prefix = :phonePrefix) AND
                    (:phoneExchange IS null OR ph.exchange = :phoneExchange) AND
                    (:email = \'%%\' OR e.email LIKE :email)
                GROUP BY p.id';
        $count = Yii::app()->db->createCommand('SELECT count(*) FROM (' . $sql . ') temp')->queryScalar($params);
        return new CSqlDataProvider($sql, array(
            'params' => $params,
            'totalItemCount' => $count,
        ));
    }
}
<?php
/**
 * @property integer $id
 * @property float $high_school
 * @property float $college
 * @property float $graduate
 * @property Address[] $addresses
 */
class AddressEducation extends CActiveRecord
{
    /**
     * @param string $className
     * @return AddressEducation
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
        return 'address_education';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('high_school, college, graduate', 'required'),
            array('high_school, college, graduate', 'numerical'),
            array('id, high_school, college, graduate', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array
     */
    public function relations()
    {
        return array(
            'addresses' => array(self::HAS_MANY, 'Address', 'address_education_id'),
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'high_school' => 'High School',
            'college' => 'College',
            'graduate' => 'Graduate',
        );
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('high_school', $this->high_school);
        $criteria->compare('college', $this->college);
        $criteria->compare('graduate', $this->graduate);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
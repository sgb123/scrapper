<?php
/**
 * @property integer $id
 * @property integer $parent_person_id
 * @property integer $child_person_id
 * @property Person $childPerson
 * @property Person $parentPerson
 */
class PersonPerson extends CActiveRecord
{
    /**
     * @param int $parentPersonId
     * @param int $childPersonId
     * @return CActiveRecord|PersonPerson
     */
    public static function getOrAdd($parentPersonId, $childPersonId)
    {
        $model = self::model()->findByAttributes(array(
            'parent_person_id' => $parentPersonId,
            'child_person_id' => $childPersonId,
        ));
        if ($model) {
            return $model;
        }
        $model = new self();
        $model->parent_person_id = $parentPersonId;
        $model->child_person_id = $childPersonId;
        $model->save();
        return $model;
    }

    /**
     * @param string $className
     * @return PersonPerson
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
        return 'person_person';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('parent_person_id, child_person_id', 'required'),
            array('parent_person_id, child_person_id', 'numerical', 'integerOnly' => true),
            array('id, parent_person_id, child_person_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array
     */
    public function relations()
    {
        return array(
            'childPerson' => array(self::BELONGS_TO, 'Person', 'child_person_id'),
            'parentPerson' => array(self::BELONGS_TO, 'Person', 'parent_person_id'),
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'parent_person_id' => 'Parent Person',
            'child_person_id' => 'Child Person',
        );
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('parent_person_id', $this->parent_person_id);
        $criteria->compare('child_person_id', $this->child_person_id);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
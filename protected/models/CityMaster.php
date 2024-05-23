<?php

/**
 * This is the model class for table "city_master".
 *
 * The followings are the available columns in table 'city_master':
 * @property integer $ctm_id
 * @property integer $ctm_city_id
 * @property integer $ctm_state_id
 * @property integer $ctm_region_id
 * @property string $ctm_zone_id
 * @property string $ctm_mzone_id
 * @property integer $ctm_active
 * @property string $ctm_create_date
 * @property string $ctm_update_date
 */
class CityMaster extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'city_master';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('ctm_city_id, ctm_create_date, ctm_update_date', 'required'),
            array('ctm_city_id, ctm_state_id, ctm_region_id, ctm_active', 'numerical', 'integerOnly' => true),
            array('ctm_zone_id, ctm_mzone_id', 'length', 'max' => 5000),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('ctm_id, ctm_city_id, ctm_state_id, ctm_region_id, ctm_zone_id, ctm_mzone_id, ctm_active, ctm_create_date, ctm_update_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'ctm_id'          => 'Ctm',
            'ctm_city_id'     => 'Ctm City',
            'ctm_state_id'    => 'Ctm State',
            'ctm_region_id'   => 'Ctm Region',
            'ctm_zone_id'     => 'Ctm Zone',
            'ctm_mzone_id'    => 'Ctm Mzone',
            'ctm_active'      => '0 => inactive, 1 => active',
            'ctm_create_date' => 'Ctm Create Date',
            'ctm_update_date' => 'Ctm Update Date',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('ctm_id', $this->ctm_id);
        $criteria->compare('ctm_city_id', $this->ctm_city_id);
        $criteria->compare('ctm_state_id', $this->ctm_state_id);
        $criteria->compare('ctm_region_id', $this->ctm_region_id);
        $criteria->compare('ctm_zone_id', $this->ctm_zone_id, true);
        $criteria->compare('ctm_mzone_id', $this->ctm_mzone_id, true);
        $criteria->compare('ctm_active', $this->ctm_active);
        $criteria->compare('ctm_create_date', $this->ctm_create_date, true);
        $criteria->compare('ctm_update_date', $this->ctm_update_date, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CityMaster the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getByCity($city)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('ctm_city_id', $city);
        return $this->find($criteria);
    }

}

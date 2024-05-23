<?php

/**
 * This is the model class for table "dynamic_uncommon_route".
 *
 * The followings are the available columns in table 'dynamic_uncommon_route':
 * @property integer $dur_id
 * @property integer $dur_zone_id
 * @property string $dur_zone_name
 * @property integer $dur_booking_count
 * @property integer $dur_vendor_count
 * @property string $dur_createdate
 * @property string $dur_updatedate
 * @property integer $dur_active
 */
class DynamicUncommonRoute extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'dynamic_uncommon_route';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('dur_createdate, dur_updatedate', 'required'),
            array('dur_zone_id, dur_booking_count, dur_vendor_count, dur_active', 'numerical', 'integerOnly' => true),
            array('dur_zone_name', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('dur_id, dur_zone_id, dur_zone_name, dur_booking_count, dur_vendor_count, dur_createdate, dur_updatedate, dur_active', 'safe', 'on' => 'search'),
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
            'dur_id'            => 'Dur',
            'dur_zone_id'       => 'Zone Id',
            'dur_zone_name'     => 'Zone Name',
            'dur_booking_count' => 'Booking count',
            'dur_vendor_count'  => 'Vendor Count',
            'dur_createdate'    => 'Create date',
            'dur_updatedate'    => 'Update date',
            'dur_active'        => 'Dur Active',
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

        $criteria->compare('dur_id', $this->dur_id);
        $criteria->compare('dur_zone_id', $this->dur_zone_id);
        $criteria->compare('dur_zone_name', $this->dur_zone_name, true);
        $criteria->compare('dur_booking_count', $this->dur_booking_count);
        $criteria->compare('dur_vendor_count', $this->dur_vendor_count);
        $criteria->compare('dur_createdate', $this->dur_createdate, true);
        $criteria->compare('dur_updatedate', $this->dur_updatedate, true);
        $criteria->compare('dur_active', $this->dur_active);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return DynamicUncommonRoute the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getDURP(Quote &$quoteModel)
    {
        $fromCity = $quoteModel->sourceCity;
        $fromZone = Zones::model()->getByCityId($fromCity);
        $fromZone = $fromZone != null && $fromZone != "" ? $fromZone : "-1";
        DBUtil::getINStatement($fromZone, $bindString, $params);
        $sql      = "SELECT dur_id,dur_surge_factor FROM dynamic_uncommon_route WHERE 1 AND dynamic_uncommon_route.dur_active=1 AND dynamic_uncommon_route.dur_zone_id IN ({$bindString})  AND dynamic_uncommon_route.dur_surge_factor>1 ORDER BY  dynamic_uncommon_route.dur_surge_factor DESC";
        $row      = DBUtil::queryRow($sql, DBUtil::SDB(), $params, 60 * 60 * 24 * 1, CacheDependency::Type_Surge);
        return $row;
    }

}

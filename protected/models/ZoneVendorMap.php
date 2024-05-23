<?php

/**
 * This is the model class for table "zone_vendor_map".
 *
 * The followings are the available columns in table 'zone_vendor_map':
 * @property integer $zvm_id
 * @property integer $zvm_zon_id
 * @property integer $zvm_vnd_id
 * @property integer $zvm_zone_type
 * @property string $zvm_created_date
 * @property string $zvm_modified_date
 * @property integer $zvm_active
 */
class ZoneVendorMap extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'zone_vendor_map';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('zvm_zon_id, zvm_vnd_id,zvm_zone_type', 'required'),
            array('zvm_zon_id, zvm_vnd_id, zvm_zone_type, zvm_active', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('zvm_id, zvm_zon_id, zvm_vnd_id, zvm_zone_type, zvm_created_date, zvm_modified_date, zvm_active', 'safe', 'on' => 'search'),
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
            'zvm_id'            => 'Zvm',
            'zvm_zon_id'        => 'Zvm Zon',
            'zvm_vnd_id'        => 'Zvm Vnd',
            'zvm_zone_type'     => '1=>Home Zone, 2=>Accepted Zone',
            'zvm_created_date'  => 'Zvm Created Date',
            'zvm_modified_date' => 'Zvm Modified Date',
            'zvm_active'        => 'Zvm Active',
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

        $criteria->compare('zvm_id', $this->zvm_id);
        $criteria->compare('zvm_zon_id', $this->zvm_zon_id);
        $criteria->compare('zvm_vnd_id', $this->zvm_vnd_id);
        $criteria->compare('zvm_zone_type', $this->zvm_zone_type);
        $criteria->compare('zvm_created_date', $this->zvm_created_date, true);
        $criteria->compare('zvm_modified_date', $this->zvm_modified_date, true);
        $criteria->compare('zvm_active', $this->zvm_active);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ZoneVendorMap the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

//    /*
//     * This function is used to get multiple selection vendor mapped to zone
//     * return query Objects 
//     */
//
//    public function getZoneVendorMapped()
//    {
//        $quote = '"';
//        $sql   = "SELECT
//                vnp_home_zone as HomeZone,
//                temp.vnp_accepted_zones as AcceptedZone,
//                vendors.vnd_id,
//                temp.vnp_vnd_id
//                FROM vendors
//                INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id = vendors.vnd_id  AND vnd_id=vnd_ref_code
//                INNER JOIN 
//                (
//                  SELECT vnp_accepted_zones,vnp_vnd_id
//                  FROM vendor_pref
//                  CROSS JOIN JSON_TABLE(CONCAT('[$quote', REPLACE(vnp_accepted_zone, $quote,$quote, '$quote,$quote'), '$quote]'),$quote$[*]$quote COLUMNS (vnp_accepted_zones VARCHAR(255) PATH $quote$$quote)) jsontable                
//                  WHERE 1 and vnp_accepted_zones <> ''                    
//                ) AS temp on temp.vnp_vnd_id= vendors.vnd_id  AND vnd_id=vnd_ref_code
//                WHERE 1";
//        return DBUtil::query($sql, DBUtil::SDB());
//    }

    /*
     * This function is used to get multiple selection vendor mapped to zone
     * return query Objects 
     */

    public function getZoneVendorMapped()
    {
        $sql = "SELECT
                vnp_home_zone as HomeZone,
                vnp_accepted_zone as AcceptedZone,
                vendors.vnd_id
                FROM vendors
                INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id = vendors.vnd_id  AND vnd_id=vnd_ref_code 
                WHERE 1 AND vnd_active=1";
        return DBUtil::query($sql, DBUtil::SDB());
    }

}

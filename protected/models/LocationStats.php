<?php

/**
 * This is the model class for table "location_stats".
 *
 * The followings are the available columns in table 'location_stats':
 * @property integer $lcs_id
 * @property string $lcs_date
 * @property integer $loc_time
 * @property integer $lcs_zone_id
 * @property integer $loc_zone_type
 * @property string $lcs_vendor_id
 * @property string $lcs_created_at
 * @property integer $lcs_active
 */
class LocationStats extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'location_stats';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('lcs_zone_id', 'required'),
            array('lcs_id, lcs_zone_id, lcs_active', 'numerical', 'integerOnly' => true),
            array('lcs_date, lcs_vendor_id', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('lcs_id, lcs_date,loc_time,lcs_zone_id,loc_zone_type,lcs_vendor_id,lcs_created_at, lcs_active', 'safe', 'on' => 'search'),
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
            'lcs_id'        => 'Lcs',
            'lcs_date'      => 'Lcs Date',
            'loc_time'      => 'Lcs Time',
            'lcs_zone_id'   => 'Lcs zone',
            'loc_zone_type' => 'Zone Type',
            'lcs_vendor_id' => 'Lcs Vendor',
            'lcs_created_at' => 'Lcs Created At',
            'lcs_active'     => 'Lcs Active',
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

        $criteria->compare('lcs_id', $this->lcs_id);
        $criteria->compare('lcs_date', $this->lcs_date, true);
        $criteria->compare('loc_time', $this->loc_time);
        $criteria->compare('lcs_zone_id', $this->lcs_zone_id);
        $criteria->compare('loc_zone_type', $this->loc_zone_type, true);       
        $criteria->compare('lcs_vendor_id', $this->lcs_vendor_id, true);        
        $criteria->compare('lcs_created_at', $this->lcs_created_at, true);
        $criteria->compare('lcs_active', $this->lcs_active);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return LocationStats the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function getVendorList($type = 1)
    {
        $groupby = $type == 1 ? "loc_zone_id" : "loc_hzone_id";
        $where   = $type == 1 ? "loc_zone_id>0" : "loc_hzone_id>0";
        $select  = $type == 1 ? "loc_zone_id" : "loc_hzone_id";
        $sql     = "SELECT
                    $select  AS ZoneId,
                    $type as type,
                    CURDATE() AS date,
                    HOUR(DATE_SUB(NOW(),INTERVAL 1 HOUR)) AS hour,
                    GROUP_CONCAT(DISTINCT IF(location.loc_entity_type = 2, loc_entity_id, NULL)) AS vendorIds                       
                    FROM location                        
                    WHERE 1 AND loc_time BETWEEN CONCAT(CURDATE(),' ',HOUR(DATE_SUB(NOW(),INTERVAL 1 HOUR)),':00:00')  AND CONCAT(CURDATE(),' ',HOUR(NOW()),':00:00') AND loc_entity_type=2 AND $where
                    GROUP BY $groupby";
        return DBUtil::query($sql, DBUtil::SDB());
    }

    /**
     * This function is used for saving all vendor count/vendor ids for given zone
     * @param type array
     * @return type boolean 
     */
    public static function add($value)
    {
        $success               = false;
        $model                 = new LocationStats();
        $model->lcs_date       = $value['date'];
        $model->loc_time       = $value['hour'];
        $model->lcs_zone_id    = $value['ZoneId'];
        $model->loc_zone_type  = $value['type'];
        $model->lcs_vendor_id  = json_encode(array('vendorIds' => $value['vendorIds']));
        $model->lcs_created_at = DBUtil::getCurrentTime();
        $model->lcs_active     = 1;
        if ($model->validate())
        {
            if ($model->save())
            {
                $success = true;
            }
            else
            {
                $getErrors = json_encode($model->getErrors());
            }
        }
        else
        {
            $getErrors = json_encode($model->getErrors());
        }
        return $success;
    }

}

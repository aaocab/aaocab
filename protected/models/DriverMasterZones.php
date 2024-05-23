<?php

/**
 * This is the model class for table "driver_master_zones".
 *
 * The followings are the available columns in table 'driver_master_zones':
 * @property integer $dmz_id
 * @property integer $dmz_driver_id
 * @property integer $dmz_mzone_id
 * @property integer $dmz_source_count
 * @property integer $dmz_destination_count
 * @property string $dmz_create_date
 * @property string $dmz_modified_date
 * @property integer $dmz_active
 */
class DriverMasterZones extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'driver_master_zones';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('dmz_driver_id', 'required'),
            array('dmz_driver_id, dmz_mzone_id, dmz_source_count, dmz_destination_count, dmz_active', 'numerical', 'integerOnly' => true),
            array('dmz_create_date, dmz_modified_date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('dmz_id, dmz_driver_id, dmz_mzone_id, dmz_source_count, dmz_destination_count, dmz_create_date, dmz_modified_date, dmz_active', 'safe', 'on' => 'search'),
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
            'dmz_id'                => 'Dmz',
            'dmz_driver_id'         => 'Dmz Driver',
            'dmz_mzone_id'          => 'Dmz Mzone',
            'dmz_source_count'      => 'Dmz Source Count',
            'dmz_destination_count' => 'Dmz Destination Count',
            'dmz_create_date'       => 'Dmz Create Date',
            'dmz_modified_date'     => 'Dmz Modified Date',
            'dmz_active'            => 'Dmz Active',
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

        $criteria->compare('dmz_id', $this->dmz_id);
        $criteria->compare('dmz_driver_id', $this->dmz_driver_id);
        $criteria->compare('dmz_mzone_id', $this->dmz_mzone_id);
        $criteria->compare('dmz_source_count', $this->dmz_source_count);
        $criteria->compare('dmz_destination_count', $this->dmz_destination_count);
        $criteria->compare('dmz_create_date', $this->dmz_create_date, true);
        $criteria->compare('dmz_modified_date', $this->dmz_modified_date, true);
        $criteria->compare('dmz_active', $this->dmz_active);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return DriverMasterZones the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function getList($drvId)
    {
        $where = ($drvId > 0) ? " AND drivers.drv_id=$drvId" : "";
        $sql   = "SELECT 
                    temp.drv_id,
                    temp.MZoneId,
                    SUM(temp.SourceZone) AS SourceZoneCnt,
                    SUM(temp.DestinationZone) AS DestinationZoneCnt
                    FROM 
                    (
                        SELECT 
                        drivers.drv_id,
                        zone_cities.zct_masterzone_id AS MZoneId,
                        COUNT(zct_masterzone_id)  AS SourceZone,
                        0 AS  DestinationZone
                        from booking
                        INNER JOIN booking_cab ON booking_cab.bcb_id=booking.bkg_bcb_id  AND bcb_driver_id >0
                        INNER JOIN drivers ON drivers.drv_id=booking_cab.bcb_driver_id
                        INNER JOIN zone_cities on zone_cities.zct_cty_id=bkg_from_city_id
                        WHERE 1 AND  DATE_ADD(bkg_pickup_date,INTERVAL bkg_trip_duration MINUTE)<=NOW() AND bkg_status IN (5,6,7)  $where
                        GROUP BY drivers.drv_id,zone_cities.zct_masterzone_id

                        UNION 

                        SELECT 
                        drivers.drv_id,
                        zone_cities.zct_masterzone_id AS MZoneId,
                        0  AS SourceZone,
                        COUNT(zone_cities.zct_masterzone_id)  AS DestinationZone
                        FROM booking
                        INNER JOIN booking_cab ON booking_cab.bcb_id=booking.bkg_bcb_id  AND bcb_driver_id >0
                        INNER JOIN drivers ON drivers.drv_id=booking_cab.bcb_driver_id
                        INNER JOIN zone_cities on zone_cities.zct_cty_id=bkg_to_city_id
                        WHERE 1 AND  DATE_ADD(bkg_pickup_date,INTERVAL bkg_trip_duration MINUTE)<=NOW() AND bkg_status IN (5,6,7)  $where
                        GROUP BY  drivers.drv_id,zone_cities.zct_masterzone_id
                    ) temp 
                    WHERE 1 AND temp.MZoneId>0 GROUP BY temp.drv_id,temp.MZoneId";
        return DBUtil::query($sql, DBUtil::SDB());
    }

    public function getbyDriverId($drvId, $mzoneId)
    {
        $criteria = new CDbCriteria;
        $criteria->compare('dmz_driver_id', $drvId);
        $criteria->compare('dmz_mzone_id', $mzoneId);
        $criteria->compare('dmz_active', 1);
        $model    = $this->find($criteria);
        if ($model)
        {
            return $model;
        }
        else
        {
            return false;
        }
    }

    /**
     * This function is used to calculate  all the driver-mzone relationship with source/destination count
     * @param integer $driverId
     * @return boolean
     */
    public function updateDetails($driverId = 0)
    {
        $success = false;
        $rows    = DriverMasterZones::getList($driverId);
        foreach ($rows as $row)
        {
            try
            {
                $model = DriverMasterZones::model()->getbyDriverId($row['drv_id'], $row['MZoneId']);
                if (!$model)
                {
                    $model                        = new DriverMasterZones();
                    $model->dmz_driver_id         = $row['drv_id'];
                    $model->dmz_mzone_id          = $row['MZoneId'];
                    $model->dmz_source_count      = $row['SourceZoneCnt'];
                    $model->dmz_destination_count = $row['DestinationZoneCnt'];
                    $model->dmz_create_date       = DBUtil::getCurrentTime();
                    $model->dmz_modified_date     = DBUtil::getCurrentTime();
                    $model->dmz_active            = 1;
                }
                else
                {

                    $model->dmz_source_count      = $row['SourceZoneCnt'];
                    $model->dmz_destination_count = $row['DestinationZoneCnt'];
                    $model->dmz_modified_date     = DBUtil::getCurrentTime();
                    $model->dmz_active            = 1;
                }
                if ($model->save())
                {
                    $success = true;
                }
            }
            catch (Exception $ex)
            {
                Logger::error($ex->getMessage());
            }
        }
        return $success;
    }

}

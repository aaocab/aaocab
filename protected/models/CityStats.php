<?php

/**
 * This is the model class for table "city_stats".
 *
 * The followings are the available columns in table 'city_stats':
 * @property integer $cst_cty_id
 * @property integer $cst_active_vendor_no
 * @property integer $cst_freezed_vendor_no
 * @property integer $cst_pending_aapr_vendor_no
 * @property integer $cst_tot_drivers
 * @property integer $cst_booking_enq_30days
 * @property integer $cst_booking_enq_7days
 * @property integer $cst_booking_served_30days
 * @property integer $cst_booking_served_7days
 * @property integer $cst_avg_rating
 * @property string $cst_created_date
 * @property string $cst_modified_date
 */
class CityStats extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'city_stats';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cst_cty_id', 'required'),
			array('cst_cty_id, cst_active_vendor_no, cst_freezed_vendor_no, cst_pending_aapr_vendor_no, cst_tot_drivers, cst_booking_enq_30days, cst_booking_enq_7days, cst_booking_served_30days, cst_booking_served_7days', 'numerical', 'integerOnly' => true),
			array('cst_modified_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cst_cty_id, cst_active_vendor_no, cst_freezed_vendor_no, cst_pending_aapr_vendor_no, cst_tot_drivers, cst_booking_enq_30days, cst_booking_enq_7days, cst_booking_served_30days, cst_booking_served_7days, cst_avg_rating, cst_created_date, cst_modified_date', 'safe', 'on' => 'search'),
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
			'cst_cty_id' => 'Cst Cty',
			'cst_active_vendor_no' => 'Cst Active Vendor No',
			'cst_freezed_vendor_no' => 'Cst Freezed Vendor No',
			'cst_pending_aapr_vendor_no' => 'Cst Pending Aapr Vendor No',
			'cst_tot_drivers' => 'Cst Tot Drivers',
			'cst_booking_enq_30days' => 'Cst Booking Enq 30days',
			'cst_booking_enq_7days' => 'Cst Booking Enq 7days',
			'cst_booking_served_30days' => 'Cst Booking Served 30days',
			'cst_booking_served_7days' => 'Cst Booking Served 7days',
			'cst_avg_rating' => 'Cst Avg Rating',
			'cst_created_date' => 'Cst Created Date',
			'cst_modified_date' => 'Cst Modified Date',
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

		$criteria->compare('cst_cty_id', $this->cst_cty_id);
		$criteria->compare('cst_active_vendor_no', $this->cst_active_vendor_no);
		$criteria->compare('cst_freezed_vendor_no', $this->cst_freezed_vendor_no);
		$criteria->compare('cst_pending_aapr_vendor_no', $this->cst_pending_aapr_vendor_no);
		$criteria->compare('cst_tot_drivers', $this->cst_tot_drivers);
		$criteria->compare('cst_booking_enq_30days', $this->cst_booking_enq_30days);
		$criteria->compare('cst_booking_enq_7days', $this->cst_booking_enq_7days);
		$criteria->compare('cst_booking_served_30days', $this->cst_booking_served_30days);
		$criteria->compare('cst_booking_served_7days', $this->cst_booking_served_7days);
		$criteria->compare('cst_avg_rating', $this->cst_avg_rating);
		$criteria->compare('cst_created_date', $this->cst_created_date, true);
		$criteria->compare('cst_modified_date', $this->cst_modified_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CityStats the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getVendorCount()
	{
		$sql = "
			SELECT DISTINCT cty_id, cty_name, COUNT(vnd.vnd_id),
			SUM(IF(vnd.vnd_active = 1, 1, 0)) AS ActiveVendor,
			SUM(IF(vnd.vnd_active = 3, 1, 0)) AS PendingVendor,
			SUM(IF(vnp_is_freeze = 1, 1, 0)) AS FreezedVendor   
			FROM cities
		    JOIN `contact` ON `ctt_city` = `cty_id`
		    JOIN contact_profile cp ON cp.cr_contact_id = contact.ctt_id AND cp.cr_status =1 AND contact.ctt_id= contact.ctt_ref_code AND contact.ctt_active = 1
		    JOIN vendors vnd ON vnd.vnd_id = cp.cr_is_vendor AND vnd.vnd_id = vnd.vnd_ref_code
		    JOIN `vendor_pref` ON vnp_vnd_id = vnd.vnd_id
			WHERE cty_service_active = 1 AND cty_active = 1 
			GROUP BY cty_id";
		$res = DBUtil::queryAll($sql, DBUtil::SDB());
		return $res;
	}

	public function getDriverCount()
	{
		$sql = "SELECT   DISTINCT cty_id, cty_name, COUNT(DISTINCT d2.drv_id), SUM(IF(d2.drv_active = 1, 1, 0)) AS ActiveDriver
				FROM     cities
						 JOIN `contact` ON `ctt_city` = `cty_id`
						 JOIN `drivers` ON drv_contact_id = ctt_id
						 JOIN drivers d2 on d2.drv_id = drivers.drv_ref_code
				WHERE    cty_service_active = 1 AND cty_active = 1
				GROUP BY cty_id";
		$res = DBUtil::queryAll($sql, DBUtil::SDB());
		return $res;
	}

	public function getBookingCount()
	{
		$sql = "SELECT cty_id, cty_name,
SUM(IF(bkg_create_date > DATE_SUB(CURDATE(), INTERVAL 30 DAY),1,0)) as 30daysCount,
SUM(IF(bkg_create_date > DATE_SUB(CURDATE(), INTERVAL 7 DAY),1,0)) as 7daysCount,
SUM(IF(bkg_pickup_date > DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND bkg_status IN(6,7),1,0)  ) as 30daysServedCount,
SUM(IF(bkg_pickup_date > DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND bkg_status IN(6,7),1,0) ) as 7daysServedCount FROM  cities
LEFT JOIN booking ON bkg_from_city_id = cty_id
WHERE cty_service_active = 1 AND cty_active = 1 GROUP BY cty_id";
		$res = DBUtil::queryAll($sql, DBUtil::SDB());
		return $res;
	}

	public function getRatingCount()
	{
		$sql = "SELECT cty_id ,cty_name, AVG(rtg_customer_overall)as rating
FROM  cities 
JOIN booking ON booking.bkg_from_city_id = cities.cty_id AND booking.bkg_status IN(6,7)
JOIN ratings ON ratings.rtg_booking_id = booking.bkg_id AND rtg_customer_overall > 0 AND rtg_active=1
WHERE cty_service_active = 1 AND cty_active = 1 
GROUP BY cty_id";
		$res = DBUtil::queryAll($sql, DBUtil::SDB());
		return $res;
	}

	public function getStatsData()
	{
		$sql = "SELECT   cty.cty_id ctyid, cty.cty_name, drv.*, vnd.*, bkg.*, rtg.*
	     from     cities cty
         LEFT JOIN (SELECT   DISTINCT cty_id, COUNT(drv_id), SUM(IF(drv_active = 1, 1, 0)) AS ActiveDriver
                    FROM     cities
                             LEFT JOIN `contact` ON `ctt_city` = `cty_id`
                             LEFT JOIN `drivers` ON drv_contact_id = ctt_id
                    WHERE    cty_service_active = 1 AND cty_active = 1
                    GROUP BY cty_id) drv
           ON cty.cty_id = drv.cty_id
         LEFT JOIN (SELECT   DISTINCT cty_id, COUNT(vnd_id), SUM(IF(vnd_active = 1, 1, 0)) AS ActiveVendor, SUM(IF(vnd_active = 3, 1, 0)) AS PendingVendor, SUM(IF(vnp_is_freeze = 1, 1, 0)) AS FreezedVendor
                    FROM     cities
                             LEFT JOIN `contact` ON `ctt_city` = `cty_id`
                             LEFT JOIN `vendors` ON vnd_contact_id = ctt_id
                             JOIN `vendor_pref` ON vnp_vnd_id = vnd_id
                    WHERE    cty_service_active = 1 AND cty_active = 1
                    GROUP BY cty_id) vnd
           ON cty.cty_id = vnd.cty_id
         LEFT JOIN (SELECT   cty_id, SUM(IF(bkg_create_date > DATE_SUB(CURDATE(), INTERVAL 30 DAY), 1, 0)) as 30daysCount, SUM(IF(bkg_create_date > DATE_SUB(CURDATE(), INTERVAL 7 DAY), 1, 0)) as 7daysCount, SUM(IF(bkg_pickup_date > DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND bkg_status IN (6, 7), 1, 0)) as 30daysServedCount, SUM(IF(bkg_pickup_date > DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND bkg_status IN (6, 7), 1, 0)) as 7daysServedCount
                    FROM     cities LEFT JOIN booking ON bkg_from_city_id = cty_id
                    WHERE    cty_service_active = 1 AND cty_active = 1
                    GROUP BY cty_id) bkg
           ON cty.cty_id = bkg.cty_id
         LEFT JOIN (SELECT   cty_id, AVG(rtg_customer_overall) as rating
                    FROM     cities
                             LEFT JOIN booking ON booking.bkg_from_city_id = cities.cty_id AND booking.bkg_status IN (6, 7)
                             JOIN ratings ON ratings.rtg_booking_id = booking.bkg_id AND rtg_customer_overall > 0 AND rtg_active = 1
                    WHERE    cty_service_active = 1 AND cty_active = 1
                    GROUP BY cty_id) rtg
           ON cty.cty_id = rtg.cty_id
		WHERE    cty.cty_service_active = 1 AND cty.cty_active = 1 AND cty_id iS NOT NULL
		GROUP BY ctyid";
		$res = DBUtil::queryAll($sql, DBUtil::SDB());
		return $res;
	}

}

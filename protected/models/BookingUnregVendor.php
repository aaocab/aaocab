<?php

/**
 * This is the model class for table "booking_unreg_vendor".
 *
 * The followings are the available columns in table 'booking_unreg_vendor':
 * @property integer $buv_id
 * @property integer $buv_uo_id
 * @property integer $buv_bkg_id
 * @property integer $buv_bcb_id
 * @property integer $buv_vendor_id
 * @property integer $buv_bid_amount
 * @property integer $buv_is_add
 * @property integer $buv_is_apply
 * @property string $buv_apply_date
 * @property integer $buv_sent_by
 * @property integer $buv_zon_id
 * @property string $buv_bkg_pickup_date
 * @property string $buv_created_at
 * @property integer $buv_active
 * 
 * The followings are the available model relations:
 * @property UnregisterOperator $buvUo
 * @property Booking $buvBkg
 * @property UnregVendorRequest $buvVendor
 * @property BookingCab $buvBcb
 */
class BookingUnregVendor extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_unreg_vendor';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('buv_uo_id, buv_bkg_id', 'required'),
			array('buv_uo_id, buv_bkg_id, buv_bcb_id, buv_vendor_id, buv_bid_amount, buv_is_apply, buv_sent_by, buv_zon_id, buv_active', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('buv_id, buv_uo_id, buv_bkg_id, buv_bcb_id, buv_vendor_id, buv_bid_amount, buv_zon_id, buv_bkg_pickup_date, buv_is_add, buv_is_apply, buv_apply_date, buv_sent_by, buv_created_at, buv_active', 'safe', 'on' => 'search'),
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
			'buvUo'	 => array(self::BELONGS_TO, 'UnregisterOperator', 'buv_uo_id'),
			'buvBkg' => array(self::BELONGS_TO, 'Booking', 'buv_bkg_id'),
			'buvBcb' => array(self::BELONGS_TO, 'BookingCab', 'buv_bcb_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'buv_id'		 => 'Buv',
			'buv_uo_id'		 => 'Buv Uo',
			'buv_bkg_id'	 => 'Buv Bkg',
			'buv_bcb_id'	 => 'Buv Bcb',
			'buv_vendor_id'	 => 'Buv Vendor',
			'buv_bid_amount' => 'Buv Bid Amount',
			'buv_is_apply'	 => 'Buv Is Apply',
			'buv_apply_date' => 'Buv Apply Date',
			'buv_sent_by'	 => 'Buv Sent By',
			'buv_created_at' => 'Buv Created At',
			'buv_active'	 => 'Buv Active',
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

		$criteria->compare('buv_id', $this->buv_id);
		$criteria->compare('buv_uo_id', $this->buv_uo_id);
		$criteria->compare('buv_bkg_id', $this->buv_bkg_id);
		$criteria->compare('buv_bcb_id', $this->buv_bcb_id);
		$criteria->compare('buv_vendor_id', $this->buv_vendor_id);
		$criteria->compare('buv_bid_amount', $this->buv_bid_amount);
		$criteria->compare('buv_is_apply', $this->buv_is_apply);
		$criteria->compare('buv_apply_date', $this->buv_apply_date, true);
		$criteria->compare('buv_sent_by', $this->buv_sent_by);
		$criteria->compare('buv_zon_id', $this->buv_zon_id);
		$criteria->compare('buv_created_at', $this->buv_created_at, true);
		$criteria->compare('buv_active', $this->buv_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingUnregVendor the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getbyUoBkgId($uoId, $bkgId)
	{
		$criteria	 = new CDbCriteria;
		$criteria->compare('buv_uo_id', $uoId);
		$criteria->compare('buv_bkg_id', $bkgId);
		$model		 = $this->find($criteria);
		if ($model)
		{
			return $model;
		}
		else
		{
			return false;
		}
	}

	public function findPhoneByBkgZone($bkgId, $zone, $limit)
	{
		$sql		 = "SELECT
						uo_id,
						uo_name,
						uo_phone,
						$zone as uo_zone
					FROM
						`unregister_operator`
					LEFT JOIN `unregister_ops_zones` ON unregister_ops_zones.uoz_uo_id = unregister_operator.uo_id 
					WHERE
						uo_active = 1 AND uo_id NOT IN 
						(
							SELECT
								buv_uo_id
							FROM
								`booking_unreg_vendor`
							LEFT JOIN `booking` ON booking.bkg_id = booking_unreg_vendor.buv_bkg_id
							WHERE
								booking_unreg_vendor.buv_bkg_id = '$bkgId' AND booking.bkg_active = 1 AND booking.bkg_status = 2
							GROUP BY
								booking_unreg_vendor.buv_uo_id
						) 
						AND
						(
							unregister_ops_zones.uoz_area_type = 3 AND FIND_IN_SET('$zone',unregister_ops_zones.uoz_area_id)
						)
					GROUP BY unregister_operator.uo_id LIMIT 0,$limit";
		$recordSet	 = DBUtil::queryAll($sql);
		return $recordSet;
	}

	public function getRequestList($bkgID, $fromCity = '', $toCity = '', $frmZoneId)
	{
		$sql			 = "SELECT
				booking_unreg_vendor.*,
				unreg_vendor_request.uvr_id,
				unreg_vendor_request.uvr_vnd_name,
				unreg_vendor_request.uvr_vnd_city_id,
				unreg_vendor_request.uvr_vnd_phone,
				unreg_vendor_request.uvr_vnd_address,
				unreg_vendor_request.uvr_bid_amount,
				cities.cty_name
				FROM
					`booking_unreg_vendor`
				LEFT JOIN `unreg_vendor_request` ON booking_unreg_vendor.buv_vendor_id = unreg_vendor_request.uvr_id 
				AND unreg_vendor_request.uvr_active=1 
				LEFT JOIN `cities` ON cities.cty_id = unreg_vendor_request.uvr_vnd_city_id 
				LEFT JOIN `booking` ON booking.bkg_id = booking_unreg_vendor.buv_bkg_id
				WHERE
				(
					booking_unreg_vendor.buv_bkg_id = '$bkgID' OR booking_unreg_vendor.buv_bkg_id IN 
					(
						SELECT booking.bkg_id 
						FROM `booking` 
						INNER JOIN `booking_unreg_vendor` ON booking_unreg_vendor.buv_bkg_id=booking.bkg_id 
						LEFT JOIN `zone_cities` ON zone_cities.zct_cty_id=booking.bkg_from_city_id  AND zone_cities.zct_active=1
						WHERE booking.bkg_status=2 
						AND booking.bkg_id <> '$bkgID' 
						AND booking.bkg_pickup_date BETWEEN DATE_SUB(NOW(),INTERVAL 4 HOUR) AND DATE_ADD(NOW(),INTERVAL 8 HOUR)	
						AND zone_cities.zct_zon_id IN ('$frmZoneId')
						GROUP BY booking.bkg_id 
					)
				)
				AND booking_unreg_vendor.buv_is_apply = 0 
				AND booking_unreg_vendor.buv_is_add = 0
				AND booking_unreg_vendor.buv_active = 1 
				AND unreg_vendor_request.uvr_id IS NOT NULL";
		//	echo $sql;exit();
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['vndname'],
				'defaultOrder'	 => $defaultOrder],
			'pagination'	 => ['pageSize' => 20],
		]);

		return $dataprovider;
	}

}

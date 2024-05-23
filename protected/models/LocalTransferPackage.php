<?php

/**
 * This is the model class for table "local_transfer_package".
 *
 * The followings are the available columns in table 'local_transfer_package':
 * @property integer $ltp_id
 * @property integer $ltp_city_id
 * @property integer $ltp_type
 * @property integer $ltp_transfer_type
 * @property integer $ltp_vehicle_type
 * @property integer $ltp_vendor_amount
 * @property integer $ltp_total_fare
 * @property integer $ltp_minimum_km
 * @property double $ltp_extra_per_km_rate
 * @property integer $ltp_parking_charge
 * @property integer $ltp_parking_included
 * @property integer $ltp_partner_id
 * @property integer $ltp_active
 * @property string $ltp_log
 * @property string $ltp_created_on
 * @property string $ltp_modified_on
 */
class LocalTransferPackage extends CActiveRecord
{
	public $is_b2c;
	public $transferTypes			 = ['1' => 'From the Local', '2' => 'To the Local'];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'local_transfer_package';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ltp_city_id, ltp_transfer_type, ltp_vehicle_type, ltp_vendor_amount, ltp_total_fare, ltp_minimum_km, ltp_extra_per_km_rate, ltp_modified_on', 'required'),
			array('ltp_city_id, ltp_type, ltp_transfer_type, ltp_vehicle_type, ltp_vendor_amount, ltp_total_fare, ltp_minimum_km, ltp_parking_charge, ltp_partner_id, ltp_active', 'numerical', 'integerOnly'=>true),
			array('ltp_extra_per_km_rate', 'numerical'),
			array('ltp_log', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ltp_id, ltp_city_id, ltp_type, ltp_transfer_type, ltp_vehicle_type, ltp_vendor_amount, ltp_total_fare, ltp_minimum_km, ltp_extra_per_km_rate, ltp_parking_charge, ltp_partner_id, ltp_active, ltp_log, ltp_created_on, ltp_modified_on, ltp_parking_included', 'safe', 'on'=>'search'),
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
			'ltp_id' => 'Ltp',
			'ltp_city_id' => 'Ltp City',
			'ltp_type' => 'Ltp Type',
			'ltp_transfer_type' => 'Ltp Transfer Type',
			'ltp_vehicle_type' => 'Vehicle Type',
			'ltp_vendor_amount' => 'Vendor Amount',
			'ltp_total_fare' => 'Total Fare',
			'ltp_minimum_km' => 'Minimum Km',
			'ltp_extra_per_km_rate' => 'Extra Per Km Rate',
			'ltp_parking_charge' => 'Entry Fee',
			'ltp_parking_included' => 'Parking Included',
			'ltp_partner_id' => 'Ltp Partner',
			'ltp_active' => 'Ltp Active',
			'ltp_log' => 'Ltp Log',
			'ltp_created_on' => 'Ltp Created On',
			'ltp_modified_on' => 'Ltp Modified On',
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

		$criteria=new CDbCriteria;

		$criteria->compare('ltp_id',$this->ltp_id);
		$criteria->compare('ltp_city_id',$this->ltp_city_id);
		$criteria->compare('ltp_type',$this->ltp_type);
		$criteria->compare('ltp_transfer_type',$this->ltp_transfer_type);
		$criteria->compare('ltp_vehicle_type',$this->ltp_vehicle_type);
		$criteria->compare('ltp_vendor_amount',$this->ltp_vendor_amount);
		$criteria->compare('ltp_total_fare',$this->ltp_total_fare);
		$criteria->compare('ltp_minimum_km',$this->ltp_minimum_km);
		$criteria->compare('ltp_extra_per_km_rate',$this->ltp_extra_per_km_rate);
		$criteria->compare('ltp_parking_charge',$this->ltp_parking_charge);
		$criteria->compare('ltp_partner_id',$this->ltp_partner_id);
		$criteria->compare('ltp_active',$this->ltp_active);
		$criteria->compare('ltp_log',$this->ltp_log,true);
		$criteria->compare('ltp_created_on',$this->ltp_created_on,true);
		$criteria->compare('ltp_modified_on',$this->ltp_modified_on,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LocalTransferPackage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getRates($partnerId, $poiCityId, $transferType, $cabType, $distance, $type)
	{
		$params	 = ['cabType'		 => $cabType,
			'airportId'		 => $poiCityId,
			'transferType'	 => $transferType,
			'partnerId'		 => $partnerId,
			'distance'		 => $distance,
			'type' => $type];
		$sql	 = "SELECT   *, IF(ltp_minimum_km <= :distance, 1, 0) AS sortRank,
					IF(ltp_partner_id IS NULL, 1, 2) AS partnerRank

				FROM     local_transfer_package pat
				WHERE    ltp_vehicle_type = :cabType 
					AND ltp_city_id = :airportId 
					AND ltp_transfer_type = :transferType 
					AND (ltp_partner_id = :partnerId  || ltp_partner_id IS NULL)
					AND ltp_active = 1 AND ltp_type = :type
				ORDER BY partnerRank DESC, sortRank DESC, abs(ltp_minimum_km - :distance) ASC LIMIT 1
				";
		$data	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params//, 600, CacheDependency::Type_Rates
		);
		return $data;
	}

	/**
	 * 
	 * @param array $arr
	 * @param boolean $type
	 * @return \CSqlDataProvider
	 */
	public static function getList($arr = [], $type = false)
	{
		$wherecond = '';
		if (!empty($arr) && count($arr) > 0)
		{
			$partnerId		 = $arr['ltp_partner_id'];
			$cityId			 = $arr['ltp_city_id'];
			$vehicleType	 = $arr['ltp_vehicle_type'];
			$ltpTransferType = $arr['ltp_transfer_type'];
			$isB2c			 = isset($arr['is_b2c'][0]);
			if (!empty($partnerId) && $partnerId != "")
			{
				$wherecond .= ' AND ltp_partner_id = ' . $partnerId;
			}
			if ($isB2c)
			{
				$wherecond .= ' AND ltp_partner_id IS NULL';
			}
			if (!empty($cityId) && $cityId != "")
			{
				$wherecond .= ' AND ltp_city_id = ' . $cityId;
			}
			if (!empty($vehicleType) && count($vehicleType) > 0)
			{
				$vehicleTypestr	 = implode(",", $vehicleType);
				$wherecond		 .= ' AND ltp_vehicle_type IN (' . $vehicleTypestr . ') ';
			}
			if (!empty($ltpTransferType) && count($ltpTransferType) > 0)
			{
				$wherecond .= ' AND ltp_transfer_type = ' . $ltpTransferType;
			}
		}
		
		$caselocaltype = "CASE WHEN (ltp.ltp_transfer_type = 1 and ltp.ltp_type = 1) THEN 'From Railway' "
                         . " WHEN (ltp.ltp_transfer_type = 1 and ltp.ltp_type = 2) THEN 'From BusStop'"
						 . " WHEN (ltp.ltp_transfer_type = 2 and ltp.ltp_type = 1) THEN 'To Railway'"
						 . " WHEN (ltp.ltp_transfer_type = 2 and ltp.ltp_type = 2) THEN 'To BusStop' END as transferType";
		$sql			 = "SELECT ltp.`ltp_id`,
						case ltp.`ltp_type` WHEN 1 THEN 'Railway' WHEN 2 THEN 'BUS' END as localType,
						ltp.`ltp_parking_included`,
						ltp.`ltp_city_id`,
						ltp.`ltp_transfer_type`,
						$caselocaltype,
						ltp.`ltp_vehicle_type`,
						ltp.`ltp_vendor_amount`,
						ltp.`ltp_total_fare`,
						ltp.`ltp_minimum_km`,
						ltp.`ltp_extra_per_km_rate`,
						ltp.`ltp_partner_id`,
						concat(agt.agt_company, ' (', agt.agt_fname, ' ', agt.agt_lname, ')') partnerName,
						svc.scv_label AS vehicleType,
						cty.cty_name AS localName
				 FROM   local_transfer_package  ltp
						LEFT JOIN agents agt ON agt.agt_id = ltp.ltp_partner_id
						JOIN svc_class_vhc_cat svc ON svc.scv_id = ltp.ltp_vehicle_type
						JOIN cities cty ON cty.cty_id = ltp.ltp_city_id
				 WHERE  ltp_active = 1 $wherecond
				";
		if ($type == false)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'sort'			 => ['defaultOrder'	 => 'ltp_id DESC',
					'attributes'	 => ['localName', 'partnerName', 'vehicleType']],
				'pagination'	 => ['pageSize' => 50],
					//	'params'		 => $params,
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql);
		}
	}
}

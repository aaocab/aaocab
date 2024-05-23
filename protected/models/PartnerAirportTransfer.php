<?php

/**
 * This is the model class for table "partner_airport_transfer".
 *
 * The followings are the available columns in table 'partner_airport_transfer':
 * @property integer $pat_id
 * @property integer $pat_city_id
 * @property integer $pat_transfer_type
 * @property integer $pat_vehicle_type
 * @property integer $pat_vendor_amount
 * @property integer $pat_total_fare
 * @property integer $pat_minimum_km
 * @property double $pat_extra_per_km_rate
 * @property integer $pat_partner_id
 * @property integer $pat_active
 * @property string $pat_log
 * @property string $pat_created_on
 * @property string $pat_modified_on
 */
class PartnerAirportTransfer extends CActiveRecord
{

	public $is_b2c;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'partner_airport_transfer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pat_city_id, pat_transfer_type, pat_vehicle_type, pat_vendor_amount, pat_total_fare, pat_minimum_km, pat_extra_per_km_rate', 'required'),
			array('pat_city_id, pat_transfer_type, pat_vehicle_type, pat_vendor_amount, pat_total_fare, pat_minimum_km, pat_active', 'numerical', 'integerOnly' => true),
			array('pat_extra_per_km_rate', 'numerical'),
			array('pat_log', 'length', 'max' => 5000),
			array('pat_modified_on, pat_partner_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pat_id, pat_city_id, pat_transfer_type, pat_vehicle_type, pat_vendor_amount, pat_total_fare, pat_minimum_km, pat_extra_per_km_rate, pat_partner_id, pat_active, pat_log, pat_created_on, pat_modified_on', 'safe', 'on' => 'search'),
			
			array('pat_city_id, pat_transfer_type, pat_vehicle_type, pat_vendor_amount, pat_total_fare, pat_minimum_km, pat_extra_per_km_rate', 'required', 'on' => 'update'),
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
			'pat_id'				 => 'Pat',
			'pat_city_id'			 => 'City',
			'pat_transfer_type'		 => 'Transfer Type',
			'pat_vehicle_type'		 => 'Vehicle Type',
			'pat_vendor_amount'		 => 'Vendor Amount',
			'pat_total_fare'		 => 'Total Fare',
			'pat_minimum_km'		 => 'Minimum Km',
			'pat_extra_per_km_rate'	 => 'Extra Km Rate',
			'pat_partner_id'		 => 'Partner',
			'pat_active'			 => 'Active',
			'pat_log'				 => 'Log',
			'pat_created_on'		 => 'Created On',
			'pat_modified_on'		 => 'Modified On',
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

		$criteria->compare('pat_id', $this->pat_id);
		$criteria->compare('pat_city_id', $this->pat_city_id);
		$criteria->compare('pat_transfer_type', $this->pat_transfer_type);
		$criteria->compare('pat_vehicle_type', $this->pat_vehicle_type);
		$criteria->compare('pat_vendor_amount', $this->pat_vendor_amount);
		$criteria->compare('pat_total_fare', $this->pat_total_fare);
		$criteria->compare('pat_minimum_km', $this->pat_minimum_km);
		$criteria->compare('pat_extra_per_km_rate', $this->pat_extra_per_km_rate);
		$criteria->compare('pat_partner_id', $this->pat_partner_id);
		$criteria->compare('pat_active', $this->pat_active);
		$criteria->compare('pat_log', $this->pat_log, true);
		$criteria->compare('pat_created_on', $this->pat_created_on, true);
		$criteria->compare('pat_modified_on', $this->pat_modified_on, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PartnerAirportTransfer the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getRates($partnerId, $airportId, $transferType, $cabType, $distance)
	{
		$params	 = ['cabType'		 => $cabType,
			'airportId'		 => $airportId,
			'transferType'	 => $transferType,
			'partnerId'		 => $partnerId,
			'distance'		 => $distance];
		$sql	 = "SELECT
						*
					FROM
						((
							SELECT
								*,
								IF(pat_minimum_km <= :distance, 1, 0) AS sortRank,
								IF(pat_partner_id IS NULL, 1, 2) AS partnerRank,
								(
									pat_total_fare + GREATEST((:distance - pat_minimum_km),
									0) * pat_extra_per_km_rate
								) AS netFare
							FROM
								partner_airport_transfer pat
							WHERE
								pat_vehicle_type = :cabType AND pat_city_id = :airportId AND pat_transfer_type = :transferType AND(
									pat_partner_id = :partnerId || pat_partner_id IS NULL
								) AND pat_active = 1 AND pat_minimum_km <= :distance
							ORDER BY
								partnerRank
							DESC LIMIT 1)
						UNION
							(
							SELECT
								*,
								IF(pat_minimum_km <= :distance, 1, 0) AS sortRank,
								IF(pat_partner_id IS NULL, 1, 2) AS partnerRank,
								(
									pat_total_fare + GREATEST((:distance - pat_minimum_km),
									0) * pat_extra_per_km_rate
								) AS netFare
							FROM
								partner_airport_transfer pat
							WHERE
								pat_vehicle_type = :cabType AND pat_city_id = :airportId AND pat_transfer_type = :transferType AND(
									pat_partner_id = :partnerId || pat_partner_id IS NULL
								) AND pat_active = 1 AND pat_minimum_km >= :distance
							ORDER BY
								partnerRank
							DESC
						LIMIT 1)) a
						ORDER BY partnerRank DESC, netFare ASC";

		if ($partnerId	== 18190 && $airportId	 == 472078)
		{

			Logger::trace($sql . " \n params: " . json_encode($params));
		}
		$data = DBUtil::queryRow($sql, DBUtil::SDB(), $params//, 600, CacheDependency::Type_Rates
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
			$partnerId		 = $arr['pat_partner_id'];
			$cityId			 = $arr['pat_city_id'];
			$vehicleType	 = $arr['pat_vehicle_type'];
			$patTransferType = $arr['pat_transfer_type'];
			$isB2c			 = isset($arr['is_b2c'][0]);
			if (!empty($partnerId) && $partnerId != "")
			{
				$wherecond .= ' AND pat_partner_id = ' . $partnerId;
			}
			if ($isB2c)
			{
				$wherecond .= ' AND pat_partner_id IS NULL';
			}
			if (!empty($cityId) && $cityId != "")
			{
				$wherecond .= ' AND pat_city_id = ' . $cityId;
			}
			if (!empty($vehicleType) && count($vehicleType) > 0)
			{
				$vehicleTypestr	 = implode(",", $vehicleType);
				$wherecond		 .= ' AND pat_vehicle_type IN (' . $vehicleTypestr . ') ';
			}
			if (!empty($patTransferType) && count($patTransferType) > 0)
			{
				$wherecond .= ' AND pat_transfer_type = ' . $patTransferType;
			}
		}
		$transferTypes	 = Booking::model()->transferTypes;
		$caseSelector	 = "CASE pat.`pat_transfer_type` ";
		foreach ($transferTypes as $k => $y)
		{
			$caseSelector .= ' WHEN ' . $k . " THEN '" . $y . "' ";
		}
		$caseSelector	 .= "END as transferType";
		$sql			 = "SELECT pat.`pat_id`,
						pat.`is_airport_fee_included`,
						pat.`pat_city_id`,
						pat.`pat_transfer_type`,
						$caseSelector,
						pat.`pat_vehicle_type`,
						pat.`pat_vendor_amount`,
						pat.`pat_total_fare`,
						pat.`pat_minimum_km`,
						pat.`pat_extra_per_km_rate`,
						pat.`pat_partner_id`,
						concat(agt.agt_company, ' (', agt.agt_fname, ' ', agt.agt_lname, ')') partnerName,
						svc.scv_label AS vehicleType,
						cty.cty_name AS airportName
				 FROM   partner_airport_transfer  pat
						LEFT JOIN agents agt ON agt.agt_id = pat.pat_partner_id
						JOIN svc_class_vhc_cat svc ON svc.scv_id = pat.pat_vehicle_type
						JOIN cities cty ON cty.cty_id = pat.pat_city_id
				 WHERE  pat_active = 1 $wherecond
				";
		if ($type == false)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'sort'			 => ['defaultOrder'	 => 'pat_id DESC',
					'attributes'	 => ['airportName', 'partnerName', 'vehicleType']],
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

	public function getLog($patId)
	{
		$params	 = ["patid" => $patId];
		$qry	 = "select pat_log from partner_airport_transfer where pat_id=:patid";
		return DBUtil::queryScalar($qry, DBUtil::SDB(), $params);
	}

}

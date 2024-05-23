<?php

/**
 * This is the model class for table "custom_quote".
 *
 * The followings are the available columns in table 'custom_quote':
 * @property integer $cqt_id
 * @property integer $cqt_from_city
 * @property string $cqt_description
 * @property string $cqt_pickup_date
 * @property integer $cqt_cab_type
 * @property integer $cqt_no_of_days
 * @property string $cqt_booking_type
 * @property integer $cqt_active
 * @property integer $cqt_user_entity_type
 * @property integer $cqt_user_entity_id
 * @property string $cqt_created
 *
 * The followings are the available model relations:
 * @property Cities $cqtFromCity
 * @property VendorQuote[] $vendorQuotes
 */
class CustomQuote extends CActiveRecord
{

	public $cqt_pickup_date_date, $cqt_pickup_date_time, $source_region, $source_city, $no_of_days, $includeExpired;
	public $booking_type = [
		'One Way'			 => 'One Way',
		'Return/Multi City'	 => 'Return/Multi City',
		//'Multi city'		 => 'Multi city',
		'Airport Transfer'	 => 'Airport Transfer',
		'Package'			 => 'Package',
		'Shuttle'			 => 'Shuttle'
	];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'custom_quote';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
		return array(
			array('cqt_from_city, cqt_description,cqt_cab_type, cqt_pickup_date,cqt_booking_type,cqt_no_of_days, cqt_user_entity_type, cqt_user_entity_id', 'required'),
			array('cqt_from_city, cqt_cab_type,cqt_no_of_days,  cqt_active, cqt_user_entity_type, cqt_user_entity_id', 'numerical', 'integerOnly' => true),
			array('cqt_description', 'length', 'max' => 5000),
			// The following rule is used by search().
// @todo Please remove those attributes that should not be searched.
			array('cqt_id, cqt_from_city, cqt_description, cqt_pickup_date, cqt_cab_type, cqt_active, cqt_created', 'safe', 'on' => 'search'),
			array('cqt_id, cqt_from_city, cqt_description, cqt_pickup_date, cqt_cab_type, cqt_active, cqt_created,cqt_pickup_date_date, cqt_pickup_date_time,cqt_booking_type,cqt_no_of_days,source_region, cqt_user_entity_type, cqt_user_entity_id,no_of_days,includeExpired,source_city', 'safe'),
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
			'cqtFromCity'	 => array(self::BELONGS_TO, 'Cities', 'cqt_from_city'),
			'vendorQuotes'	 => array(self::HAS_MANY, 'VendorQuote', 'vqt_cqt_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cqt_id'				 => 'Id',
			'cqt_from_city'			 => 'From City',
			'cqt_description'		 => 'Description',
			'cqt_pickup_date'		 => 'Pickup Date',
			'cqt_cab_type'			 => 'Cab Type',
			'cqt_no_of_days'		 => 'No Of Days',
			'cqt_booking_type'		 => 'Booking Type',
			'cqt_active'			 => 'Active',
			'cqt_user_entity_type'	 => 'Cqt User Entity Type',
			'cqt_user_entity_id'	 => 'Cqt User Entity',
			'cqt_created'			 => 'Created',
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

		$criteria->compare('cqt_id', $this->cqt_id);
		$criteria->compare('cqt_from_city', $this->cqt_from_city);
		$criteria->compare('cqt_description', $this->cqt_description, true);
		$criteria->compare('cqt_pickup_date', $this->cqt_pickup_date, true);
		$criteria->compare('cqt_cab_type', $this->cqt_cab_type);
		$criteria->compare('cqt_no_of_days', $this->cqt_no_of_days);
		$criteria->compare('cqt_booking_type', $this->cqt_booking_type, true);
		$criteria->compare('cqt_active', $this->cqt_active);
		$criteria->compare('cqt_user_entity_type', $this->cqt_user_entity_type);
		$criteria->compare('cqt_user_entity_id', $this->cqt_user_entity_id);
		$criteria->compare('cqt_created', $this->cqt_created, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CustomQuote the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getAdminId()
	{
		$sql		 = "SELECT adm.adm_id admid,concat(adm.adm_fname,' ',adm.adm_lname) FullName FROM custom_quote cqt INNER JOIN admins adm ON cqt.cqt_user_entity_id = adm.adm_id and cqt.cqt_user_entity_type=4";
		$data		 = DBUtil::query($sql);
		$adminArr	 = [];
		foreach ($data as $value)
		{
			$adminArr[$value['admid']] = $value['FullName'];
		}
		return $adminArr;
	}

	public static function fetchList($qry = [])
	{
		$regionId		 = (isset($qry['source_region'])) ? $qry['source_region'] : "";
		$cityId			 = (isset($qry['source_city'])) ? $qry['source_city'] : "";
		$bookingType	 = (isset($qry['cqt_booking_type'])) ? $qry['cqt_booking_type'] : "";
		$cabType		 = (isset($qry['cqt_cab_type'])) ? $qry['cqt_cab_type'] : "";
		$dayCount		 = (isset($qry['no_of_days'])) ? $qry['no_of_days'] : "";
		$adminId		 = (isset($qry['cqt_user_entity_id'])) ? $qry['cqt_user_entity_id'] : "";
		$includeExpired	 = ( $qry['includeExpired'] == 0 ) ? true : false;
		$regionList		 = States::model()->findRegionName();

		$regionCase = '(CASE stt.stt_zone';
		foreach ($regionList as $key => $value)
		{
			$regionCase .= " WHEN $key  THEN '$value'";
		}
		$regionCase	 .= " END) regionName";
		$sql		 = "SELECT cqt.*,cty.cty_name, vc.vct_label cabtype,vqt.vqt_cqt_id,concat(adm.adm_fname,' ',adm.adm_lname) FullName,
                stt.stt_zone region,$regionCase,
				sum(if(vqt.vqt_status=1,1,0)) cnt_vqt_accepted,
				sum(if(vqt.vqt_status=0,1,0)) cnt_vqt_denied,
				if(cqt_pickup_date < NOW(),0,1) futureDate
				FROM   custom_quote cqt 
				JOIN cities cty ON cty.cty_id = cqt.cqt_from_city
                JOIN states stt ON stt.stt_id = cty.cty_state_id 
				INNER JOIN svc_class_vhc_cat scvhc ON scvhc.scv_id = cqt.cqt_cab_type
				INNER JOIN vehicle_category vc ON scvhc.scv_vct_id = vc.vct_id
				INNER JOIN service_class sc ON scvhc.scv_scc_id = sc.scc_id
				LEFT JOIN vendor_quote vqt ON cqt.cqt_id = vqt.vqt_cqt_id  
                LEFT JOIN admins adm ON cqt.cqt_user_entity_id = adm.adm_id and cqt.cqt_user_entity_type=4 
				WHERE cqt.cqt_active=1";

		if ($includeExpired)
		{
			$sql .= " AND cqt_pickup_date > NOW() ";
		}
		if ($regionId != "")
		{
			$sql .= " AND stt.stt_zone = '$regionId'";
		}
		if ($cityId != "")
		{
			$sql .= " AND cqt.cqt_from_city = '$cityId'";
		}
		if ($bookingType != "")
		{
			$sql .= " AND cqt_booking_type = '$bookingType'";
		}
		if ($cabType != "")
		{
			$sql .= " AND cqt_cab_type = $cabType";
		}
		if ($qry['no_of_days'] != "")
		{
			$sql .= " AND cqt_no_of_days = '$dayCount'";
		}
		if ($qry['cqt_user_entity_id'] != "")
		{
			$sql .= " AND cqt_user_entity_id = '$adminId'";
		}
		$sql			 .= " GROUP BY cqt_id ";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) a")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['cty_name', 'cabtype', 'cqt_pickup_date'],
				'defaultOrder'	 => 'futureDate DESC, cqt_pickup_date ASC'], 'pagination'	 => ['pageSize' => 20],
		]);
		return $dataprovider;
	}

	public function getListForVendor($vndid, $qryVal = '', $getCount = false, $pageSize = 1, $pageCount = 1)
	{
		$param	 = ['vndId' => $vndid];
		$where	 = '';
		if ($qryVal != '')
		{
			DBUtil::getLikeStatement($qryVal, $bindString, $param1);
			$where	 = " AND ( 
					cty_name LIKE $bindString OR
					vc.vct_label LIKE $bindString OR
					cqt_description LIKE $bindString OR
					cqt_booking_type LIKE $bindString 					
					) ";
			$param	 = array_merge($param, $param1);
		}
		$limit1 = (($pageCount - 1) * $pageSize);

		$dataTable = "custom_quote cqt					
					INNER JOIN cities cty ON cty.cty_id = cqt.cqt_from_city
					INNER JOIN zone_cities zct ON zct.zct_cty_id=cqt.cqt_from_city
					INNER JOIN vendor_pref vnp ON find_in_set(zct.zct_zon_id,vnp_accepted_zone)
					INNER JOIN svc_class_vhc_cat scvhc ON scvhc.scv_id = cqt.cqt_cab_type
					INNER JOIN vehicle_category vc ON scvhc.scv_vct_id = vc.vct_id
					INNER JOIN service_class sc ON scvhc.scv_scc_id = sc.scc_id 
					LEFT JOIN vendor_quote vqt ON  cqt.cqt_id = vqt.vqt_cqt_id AND vqt.vqt_vendor_id = vnp_vnd_id							  			
					WHERE cqt.cqt_active = 1 AND vnp_vnd_id=:vndId  AND (vqt_cqt_id IS NULL OR vqt_status  = 1) 
						AND cqt_pickup_date > NOW() $where
					 ";


		$sql		 = "SELECT cqt.cqt_id, cty.cty_name, cqt_cab_type cabtype,vc.vct_label, cqt_pickup_date,cqt.cqt_description,
					cqt.cqt_booking_type,cqt.cqt_no_of_days
					FROM $dataTable
					GROUP BY cqt_id
					ORDER BY cqt_pickup_date ASC 
					LIMIT $limit1,$pageSize ";
		$countSql	 = "SELECT count(distinct cqt.cqt_id) 	FROM $dataTable ";
		if ($getCount)
		{
			$result = DBUtil::queryScalar($countSql, DBUtil::SDB(), $param);
		}
		else
		{
			$result = DBUtil::queryAll($sql, DBUtil::SDB(), $param);
		}
		return $result;
	}

	public static function getCitList()
	{
		$sql		 = "    SELECT DISTINCT cqt_from_city, cty_name
    FROM custom_quote cqt
         JOIN cities ON cities.cty_id = cqt.cqt_from_city";
		$resultSet	 = DBUtil::query($sql, DBUtil::SDB());
		$cityArr	 = [];
		foreach ($resultSet as $value)
		{
			$cityArr[$value['cqt_from_city']] = $value['cty_name'];
		}
		return $cityArr;
	}

}

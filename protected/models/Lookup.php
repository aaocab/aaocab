<?php

/**
 * This is the model class for table "lookup".
 *
 * The followings are the available columns in table 'lookup':
 * @property integer $lkp_id
 * @property string $lkp_desc
 * @property string $lkp_user_desc
 * @property integer $lkp_value
 * @property integer $lkp_group
 * @property string $lkp_category
 * @property integer $lkp_active
 */
class Lookup extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lookup';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lkp_desc, lkp_user_desc, lkp_value, lkp_group, lkp_category', 'required'),
			array('lkp_value, lkp_group, lkp_active', 'numerical', 'integerOnly' => true),
			array('lkp_desc, lkp_user_desc, lkp_category', 'length', 'max' => 255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lkp_id, lkp_desc, lkp_user_desc, lkp_value, lkp_group, lkp_category, lkp_active', 'safe', 'on' => 'search'),
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
			'lkp_id'		 => 'Id',
			'lkp_desc'		 => 'Desc',
			'lkp_user_desc'	 => 'User Desc',
			'lkp_value'		 => 'Value',
			'lkp_group'		 => 'Group',
			'lkp_category'	 => 'Category',
			'lkp_active'	 => 'Active',
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

		$criteria->compare('lkp_id', $this->lkp_id);
		$criteria->compare('lkp_desc', $this->lkp_desc, true);
		$criteria->compare('lkp_user_desc', $this->lkp_user_desc, true);
		$criteria->compare('lkp_value', $this->lkp_value);
		$criteria->compare('lkp_group', $this->lkp_group);
		$criteria->compare('lkp_category', $this->lkp_category, true);
		$criteria->compare('lkp_active', $this->lkp_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Lookup the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getList()
	{
		$list = [
			'surge'								 => 'Surge',
			'dashboard'							 => 'Dashboard',
			'routes'							 => 'Routes',
			'rates'								 => 'Rates',
			'routePages'						 => 'Route Page',
			'booktaxiGetQuote'					 => 'Route Page Rates',
			'price_city_rules'					 => 'Price City Rules',
			'pricerule'							 => 'Price Rule',
			'cities'							 => 'Cities',
			'lookupCities'						 => 'City List',
			'vendor'							 => 'Vendor',
			'vehicle'							 => 'Vehicle',
			'promo'								 => 'Promo',
			'cabtypes'							 => 'Cab Types',
			'TransactionStats'					 => 'Transaction Stats',
			'package'							 => 'Package',
			'getTopRatings'						 => 'Testimonial',
			'pages'								 => 'Pages',
			'HomePage'							 => 'Home Page',
			'db'								 => 'DB Schema',
			'getexcludedCabTypes_availableCabs'	 => 'Excluded Cab Types',
			'zones'								 => 'Zones',
			'checkAdminAccess'					 => 'Admin Roles',
			'reportDashboard'					 => 'Report Dashboard'
		];
		return $list;
	}

	public function getJSONRoutes($arr = [])
	{
		//$carList = $this->getVehicleTypeList();
		$arrJSON = array();
		foreach ($arr as $val)
		{
			$arrJSON[] = array("id" => $val['rut_id'], "text" => $val['rut_name']);
		}
		$data = CJSON::encode($arrJSON);
		return $data;
	}

	public static function getBankList()
	{

		$list = [
			''		 => 'Select Bank',
			'280'	 => 'Allahabad Bank',
			'1790'	 => 'Andhra Bank',
			'50'	 => 'Axis Bank',
			'340'	 => 'Bank of Bahrain and Kuwait',
			'310'	 => 'Bank of Baroda',
			'240'	 => 'Bank of India',
			'750'	 => 'Bank of Maharashtra',
			'930'	 => 'Canara Bank',
			'1130'	 => 'Catholic Syrian Bank',
			'740'	 => 'Central Bank of India',
			'440'	 => 'City Union Bank',
			'120'	 => 'Corporation Bank',
			'540'	 => 'DCB BANK Personal',
			'330'	 => 'Deutsche Bank',
			'370'	 => 'Dhanlaxmi Bank',
			'270'	 => 'Federal Bank',
			'300'	 => 'HDFC Bank Retail',
			'10'	 => 'ICICI Bank',
			'2180'	 => 'IDFC Bank',
			'490'	 => 'Indian Bank',
			'420'	 => 'Indian Overseas NetBanking',
			'860'	 => 'Indusind Bank',
			'350'	 => 'Jammu and Kashmir Bank',
			'140'	 => 'Karnataka Bank',
			'760'	 => 'Karur Vysya Bank',
			'910'	 => 'Kotak Mahindra Bank',
			'2370'	 => 'Lakshmi Vilas',
			'160'	 => 'Oriental Bank Of Commerce',
			'1220'	 => 'Punjab National Bank',
			'2390'	 => 'Punjab and Sind Bank',
			'1500'	 => 'RBL Bank Limited',
			'1700'	 => 'Shamrao Vithal Co-op. Bank Ltd',
			'180'	 => 'South Indian Bank',
			'450'	 => 'Standard Chartered Bank',
			'530'	 => 'State Bank of India',
			'1880'	 => 'Syndicate Bank',
			'620'	 => 'Tamilnad Mercantile Bank',
			'2030'	 => 'Uco Bank',
			'190'	 => 'Union Bank of India',
			'570'	 => 'United Bank Of India',
			'200'	 => 'Vijaya Bank',
			'130'	 => 'Yes Bank',
		];
		return $list;
	}

	public static function getCardBankList()
	{
		$list = [
			''		 => 'Select Card',
			'3070'	 => 'VISA MASTER MAESTRO CREDIT CARD',
			'3080'	 => 'VISA MASTER MAESTRO DEBIT CARD',
			'8770'	 => 'IMSL EBS RUPAY CREDIT CARD',
			'8790'	 => 'IMSL EBS RUPAY DEBIT CARD',
			'2410'	 => 'UPI',
			'460'	 => 'I-Cash Card',
		];
		return $list;
	}

	/**
	 * This function is used for finding the VcVtMapping
	 * @param type $vctId
	 * @param type $vhtId
	 * @return string|int
	 */
	public function findVcVtMapping($vctId, $vhtId)
	{
		if (empty($vctId) || empty($vhtId))
		{
			return "";
		}

		$checkExists = "
			SELECT vcvvhc.vcv_id vcvId, vcvvhc.vcv_active isActive
			FROM vehicle_category vc
				 INNER JOIN vcv_cat_vhc_type vcvvhc 
				  ON vcvvhc.vcv_vct_id = vc.vct_id
				 INNER JOIN vehicle_types vt 
				  ON vt.vht_id = vcvvhc.vcv_vht_id
			WHERE  vcvvhc.vcv_vct_id = $vctId
				  AND vcvvhc.vcv_vht_id = $vhtId
		";

		$arrVtVcDetails = DBUtil::queryAll($checkExists, DBUtil::SDB());

		$returnObject = new stdClass();
		if (empty($arrVtVcDetails))
		{
			$returnObject->vcvId	 = 0;
			$returnObject->isActive	 = 0;

			return $returnObject;
		}
		else
		{
			$returnObject->vcvId	 = $arrVtVcDetails[0]["vcvId"];
			$returnObject->isActive	 = $arrVtVcDetails[0]["isActive"];

			return $returnObject;
		}
	}

	/**
	 * This function is used for finding the service class and vehicle category exists or not
	 * @param type $vctId
	 * @param type $sccId
	 * @return string|int
	 */
	public function findScVcMapping($vctId, $sccId)
	{
		if (empty($vctId) || empty($sccId))
		{
			return "";
		}

		$checkExists = "
			SELECT svcvhc.scv_id scvId, svcvhc.scv_active isActive
			FROM service_class sc
				 INNER JOIN svc_class_vhc_cat svcvhc 
				  ON svcvhc.scv_scc_id = sc.scc_id
				 INNER JOIN vehicle_category vc 
				  ON vc.vct_id = svcvhc.scv_vct_id
			WHERE svcvhc.scv_vct_id = $vctId
				  AND svcvhc.scv_scc_id = $sccId
		";

		$arrScVcDetails = DBUtil::queryAll($checkExists, DBUtil::SDB());

		$returnObject = new stdClass();
		if (empty($arrScVcDetails))
		{
			$returnObject->scvId	 = 0;
			$returnObject->isActive	 = 0;

			return $returnObject;
		}
		else
		{
			$returnObject->scvId	 = $arrScVcDetails[0]["scvId"];
			$returnObject->isActive	 = $arrScVcDetails[0]["isActive"];

			return $returnObject;
		}
	}

	public static function updateDetails($receivedData)
	{
		$decodedData = json_decode($receivedData);

		if ($decodedData->type == 1)
		{
			$return = VcvCatVhcType::updateVcVtMapping($decodedData);
		}

		if ($decodedData->type == 2)
		{
			$return = SvcClassVhcCat::updateScVcMapping($decodedData);
		}

		return $return;
	}

	public function priceRuleEntry()
	{
		$areaId		 = [1, 2, 3, 4, 5, 6, 7];
		$sql		 = "SELECT * FROM `svc_class_vhc_cat` WHERE scv_scc_id = 1";
		$scvRecord	 = DBUtil::queryAll($sql, DBUtil::SDB());
		foreach ($scvRecord as $scvData)
		{
			foreach ($areaId as $area)
			{
				$sqlTemp	 = "SELECT apr_id, apr_oneway_id, apr_return_id, apr_multitrip_id, apr_airport_id, apr_dr_4_40, apr_dr_8_80, apr_dr_12_120 FROM  area_price_rule apr where apr.apr_area_type = 4 AND apr.apr_cab_type = $scvData[scv_id] AND apr.apr_area_id = $area AND apr.apr_is_complete = 0";
				$recordApr	 = DBUtil::queryAll($sqlTemp, DBUtil::SDB());
				$aprId		 = $recordApr[0]['apr_id'];
				$prrId		 = [];
				if ($recordApr[0] != '')
				{
					foreach ($recordApr[0] as $key => $record)
					{
						if ($key != 'apr_id')
						{
							$query		 = "select * from price_rule WHERE prr_id = $record";
							$data		 = DBUtil::queryRow($query, DBUtil::SDB());
							$cabType	 = $data['prr_cab_type'];
							$getSvcId	 = 0;
							if ($cabType != '')
							{
								$getSvcId = $this->getScvIdByClassCategory(2, $cabType);
							}
//							$extraMinAmt	 = 0;
//							$extraRatePerKm	 = 1;
//							if ($data['prr_min_base_amount'] != 0)
//							{
//								$extraMinAmt = 100;
//							}
							$model									 = new PriceRule();
							$model									 = ServiceClass::model()->getPriceRuleWithMarkUp($getSvcId, $model);
							$model->prr_cab_type					 = $getSvcId;
							$model->prr_cab_desc					 = $data['prr_cab_type'] . '|' . $data['prr_trip_type'];
							$model->prr_trip_type					 = $data['prr_trip_type'];
							//$model->prr_rate_per_km					 = $data['prr_rate_per_km'] + $extraRatePerKm;
							//$model->prr_rate_per_minute				 = $data['prr_rate_per_minute'] + $extraRatePerKm;
							//$model->prr_rate_per_km_extra			 = $data['prr_rate_per_km_extra'] + $extraRatePerKm;
							//$model->prr_rate_per_minute_extra		 = $data['prr_rate_per_minute_extra'] + $extraRatePerKm;
							$model->prr_min_km						 = $data['prr_min_km'];
							$model->prr_min_duration				 = $data['prr_min_duration'];
							//$model->prr_min_base_amount				 = $data['prr_min_base_amount'];
							$model->prr_min_km_day					 = $data['prr_min_km_day'];
							$model->prr_max_km_day					 = $data['prr_max_km_day'];
							//$model->prr_day_driver_allowance		 = $data['prr_day_driver_allowance'];
							//$model->prr_night_driver_allowance		 = $data['prr_night_driver_allowance'];
							$model->prr_driver_allowance_km_limit	 = $data['prr_driver_allowance_km_limit'];
							$model->prr_night_start_time			 = $data['prr_night_start_time'];
							$model->prr_night_end_time				 = $data['prr_night_end_time'];
							$model->prr_calculation_type			 = $data['prr_calculation_type'];
							$model->prr_min_pickup_duration			 = $data['prr_min_pickup_duration'];

							if ($model->save())
							{
								$prrId[]					 = $model->prr_id;
								$aprModel					 = AreaPriceRule::model()->findByPk($aprId);
								$aprModel->apr_is_complete	 = 1;
								$aprModel->save();
							}
						}
					}
					if ($prrId[4] == '')
					{
						$prrId[4] = 0;
					}
					if ($prrId[5] == '')
					{
						$prrId[5] = 0;
					}
					if ($prrId[6] == '')
					{
						$prrId[6] = 0;
					}
					$getSvcId					 = $this->getScvIdByClassCategory(2, $scvData[scv_vct_id]);
					$aprModel					 = new AreaPriceRule();
					$aprModel->apr_area_type	 = 4;
					$aprModel->apr_area_id		 = $area;
					$aprModel->apr_cab_type		 = $getSvcId;
					$aprModel->apr_oneway_id	 = $prrId[0];
					$aprModel->apr_return_id	 = $prrId[1];
					$aprModel->apr_multitrip_id	 = $prrId[2];
					$aprModel->apr_airport_id	 = $prrId[3];
					$aprModel->apr_dr_4_40		 = $prrId[4];
					$aprModel->apr_dr_8_80		 = $prrId[5];
					$aprModel->apr_dr_12_120	 = $prrId[6];
					if ($aprModel->save())
					{
						echo 'AreaId:' . $aprModel->apr_id . '<br>';
					}
				}
			}
		}
	}

	public function getScvIdByClassCategory($sccClass, $sccVctId)
	{
		$sql = "select scv_id 
                FROM svc_class_vhc_cat
				WHERE scv_vct_id = $sccVctId AND scv_scc_id = $sccClass";
		return DBUtil::command($sql, DBUtil::SDB())->queryScalar();
	}

}

<?php

/**
 * This is the model class for table "rate".
 *
 * The followings are the available columns in table 'rate':
 * @property integer $rte_id
 * @property integer $rte_vehicletype_id
 * @property integer $rte_vehicletype_id_bk
 * @property integer $rte_route_id
 * @property integer $rte_trip_type
 * @property integer $rte_excl_amount

 * @property integer $rte_toll_tax
 * @property integer $rte_state_tax
 * @property integer $rte_vendor_amount
 * @property integer $rte_minimum_markup
 * @property integer $rte_night_charge
 * @property string $rte_create_date
 * @property string $rte_update_date
 * @property integer $rte_status
 * @property string $rte_log
 *
 * The followings are the available model relations:
 * @property Route $rteRoute
 * @property SvcClassVhcCat $svcClassVhcCat
 */
class Rate extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rate';
	}

	public $vht_car_type, $cabDefaultMarkup;

	public function defaultScope()
	{
		$arr = array(
			'condition' => "rte_status=1",
		);
		return $arr;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rte_vehicletype_id, rte_route_id,rte_vendor_amount', 'required'),
			array('rte_vehicletype_id, rte_vehicletype_id_bk, rte_route_id, rte_minimum_markup, rte_trip_type, rte_excl_amount,  rte_toll_tax, rte_state_tax, rte_vendor_amount, rte_night_charge, rte_status', 'numerical', 'integerOnly' => true),
			array('rte_log', 'length', 'max' => 4000),
			array('rte_create_date,rte_update_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rte_id, rte_vehicletype_id, rte_minimum_markup, rte_vehicletype_id_bk, rte_route_id, rte_trip_type, rte_excl_amount,  rte_toll_tax, rte_state_tax, rte_vendor_amount, rte_night_charge, rte_create_date,rte_update_date, rte_status, rte_log', 'safe', 'on' => 'search'),
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
			'rteRoute'		 => array(self::BELONGS_TO, 'Route', 'rte_route_id'),
			'svcClassVhcCat' => array(self::BELONGS_TO, 'SvcClassVhcCat', 'rte_vehicletype_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'rte_id'			 => 'Rate ID',
			'rte_vehicletype_id' => 'Vehicletype',
			'rte_route_id'		 => 'Route',
			'rte_trip_type'		 => 'Trip Type',
			'rte_excl_amount'	 => 'Excl Amount',
			'rte_toll_tax'		 => 'Toll Tax',
			'rte_state_tax'		 => 'State Tax',
			'rte_vendor_amount'	 => 'Vendor Amount',
			'rte_minimum_markup' => 'Minimum Markup',
			'rte_night_charge'	 => 'Night Charge',
			'rte_create_date'	 => 'Create Date',
			'rte_update_date'	 => 'Update Date',
			'rte_status'		 => 'Status',
			'rte_log'			 => 'Log',
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
		$criteria->compare('rte_id', $this->rte_id);
		$criteria->compare('rte_vehicletype_id', $this->rte_vehicletype_id);
		$criteria->compare('rte_route_id', $this->rte_route_id);
		$criteria->compare('rte_trip_type', $this->rte_trip_type);
		$criteria->compare('rte_excl_amount', $this->rte_excl_amount);
		$criteria->compare('rte_toll_tax', $this->rte_toll_tax);
		$criteria->compare('rte_state_tax', $this->rte_state_tax);
		$criteria->compare('rte_vendor_amount', $this->rte_vendor_amount);
		$criteria->compare('rte_minimum_markup', $this->rte_minimum_markup);
		$criteria->compare('rte_night_charge', $this->rte_night_charge);
		$criteria->compare('rte_create_date', $this->rte_create_date, true);
		$criteria->compare('rte_update_date', $this->rte_update_date, true);
		$criteria->compare('rte_status', $this->rte_status);
		$criteria->compare('rte_log', $this->rte_log, true);
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Rate the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function beforeSave()
	{
		if ($this->rte_vendor_amount >= 1 && $this->rte_vendor_amount != '')
		{
			return true;
		}
		else
		{
			$this->addError("rte_vendor_amount", "Vender amount must be greather Than 0");
			return false;
		}
		return parent::beforeSave();
	}

	//+ CODE BLOCK START

	/**
	 * This function is used for fetching the vehicle details based on the route id passed as argument
	 * And also the vehicle details whose rates are not yet defined
	 * 
	 * @param [int] $routeId
	 * @return $arrVehicleDetails
	 */
	public function getVehicleDetailsByRoute($routeId)
	{
		$getVehicleDetailsQuery = "SELECT
			        scvhc.scv_id,
                    scvhc.scv_label,
					vc.vct_id,
					vc.vct_label,
					sc.scc_label,
					r.rte_amount,
					r.rte_id,
					r.rte_vehicletype_id,
					r.rte_route_id,
					r.rte_trip_type,
					r.rte_log,
					r.rte_toll_tax,
					r.rte_state_tax,
					r.rte_vendor_amount,
					r.rte_night_charge,
					r.rte_minimum_markup
			 FROM `svc_class_vhc_cat` scvhc
				  INNER JOIN vehicle_category vc ON scvhc.scv_vct_id = vc.vct_id
				  INNER JOIN service_class sc ON scvhc.scv_scc_id = sc.scc_id
				  LEFT JOIN rate r ON r.rte_vehicletype_id = scvhc.scv_id  	AND r.rte_status = 1 AND r.rte_route_id = $routeId
			 WHERE  scvhc.scv_active = 1
				   AND vc.vct_active = 1
				   AND sc.scc_active = 1
			 ORDER BY scvhc.scv_id ASC";

		$arrVehicleDetails = DBUtil::queryAll($getVehicleDetailsQuery, DBUtil::SDB());

		return $arrVehicleDetails;
	}

	//- CODE BLOCK END

	public function getByRouteArr($rut_id)
	{
		$rates	 = $this->getByRoute($rut_id);
		$arr	 = [];
		foreach ($rates as $v)
		{
			$arr[$v->scv_id] = $v;
		}
		return $arr;
	}

	/**
	 * This model function is used for fetching the rate list
	 * 
	 * Case 1: If Search parameters not set, All values will be fetched
	 * Case 2: If Search parameters are set, All values will be fetched based on that
	 * 
	 * @param type $requestDetails
	 * @return \CSqlDataProvider
	 */
	public static function fetchRouteDetalis($requestDetails = null)
	{
		$fromCityId		 = $requestDetails["fromCityId"];
		$toCityId		 = $requestDetails["toCityId"];
		$svcId			 = $requestDetails["svcId"];
		$routeCityId	 = $requestDetails["routeCityId"];
		$sccId			 = $requestDetails["sccId"];
		$sourceZone		 = $requestDetails["sourcezone"];
		$destinationZone = $requestDetails["destinationZone"];

		$fetchVehicleDetailsQueries = "
			SELECT  scvhc.scv_id AS svcId,
					sc.scc_id AS sccId,
					vc.vct_id AS vctId,
					r.rte_id AS rateId,
					rt.rut_id AS routeId,
					r.rte_route_id AS rateRouteId,
					scc_label AS sccLabel,
					vct_label AS vcLabel,
					vct_label AS vehicleModel,
					tc.cty_name AS routeCityName,
					fc.cty_name As routeFromName,
					r.rte_amount AS amount,
					r.rte_toll_tax AS rateTollTax,
					r.rte_state_tax AS rateStateTax,
					r.rte_vendor_amount AS rateVendorAmount,
					rt.rut_create_date AS routeCreateDate,
					rt.rut_modified_on AS routeModifiedDate
			FROM `svc_class_vhc_cat` scvhc
			  INNER JOIN vehicle_category vc ON scvhc.scv_vct_id = vc.vct_id
			  INNER JOIN service_class sc ON scvhc.scv_scc_id = sc.scc_id
			  INNER JOIN rate r ON r.rte_vehicletype_id = scvhc.scv_id 
			  INNER JOIN route rt ON rt.rut_id = r.rte_route_id
			  INNER JOIN cities tc ON tc.cty_id = rt.rut_to_city_id
			  INNER JOIN cities fc ON fc.cty_id = rt.rut_from_city_id
			  
			  INNER JOIN zone_cities fzc ON fzc.zct_cty_id=rt.rut_from_city_id AND fzc.zct_active=1
			  INNER JOIN zone_cities tzc ON tzc.zct_cty_id=rt.rut_to_city_id AND tzc.zct_active=1
			  INNER JOIN zones fz ON fz.zon_id=fzc.zct_zon_id
			  INNER JOIN zones tz ON tz.zon_id=tzc.zct_zon_id
			  WHERE     scvhc.scv_active = 1
			   AND vc.vct_active = 1
			   AND sc.scc_active = 1
			   AND r.rte_status = 1 AND rt.rut_active = 1
		";

		$fetchVehicleDetailsCountQueries = "
					SELECT  COUNT(*)
					FROM `svc_class_vhc_cat` scvhc
					INNER JOIN vehicle_category vc ON scvhc.scv_vct_id = vc.vct_id
					INNER JOIN service_class sc ON scvhc.scv_scc_id = sc.scc_id
					INNER JOIN rate r ON r.rte_vehicletype_id = scvhc.scv_id 
					INNER JOIN route rt ON rt.rut_id = r.rte_route_id
					INNER JOIN cities tc ON tc.cty_id = rt.rut_to_city_id
					INNER JOIN cities fc ON fc.cty_id = rt.rut_from_city_id
					INNER JOIN zone_cities fzc ON fzc.zct_cty_id=rt.rut_from_city_id AND fzc.zct_active=1
					INNER JOIN zone_cities tzc ON tzc.zct_cty_id=rt.rut_to_city_id AND tzc.zct_active=1
					INNER JOIN zones fz ON fz.zon_id=fzc.zct_zon_id
					INNER JOIN zones tz ON tz.zon_id=tzc.zct_zon_id
					WHERE     scvhc.scv_active = 1
					AND vc.vct_active = 1
					AND sc.scc_active = 1
					AND r.rte_status = 1 AND rt.rut_active = 1";

		if (!empty($fromCityId))
		{
			$fetchVehicleDetailsQueries		 .= "
				AND rt.rut_from_city_id = $fromCityId
			";
			$fetchVehicleDetailsCountQueries .= "
				AND rt.rut_from_city_id = $fromCityId
			";
		}

		if (!empty($toCityId))
		{
			$fetchVehicleDetailsQueries		 .= "
				AND rt.rut_to_city_id = $toCityId
			";
			$fetchVehicleDetailsCountQueries .= "
				AND rt.rut_to_city_id = $toCityId
			";
		}

		if (!empty($routeCityId))
		{
			$fetchVehicleDetailsQueries		 .= "
				AND rt.rut_to_city_id = $routeCityId OR rt.rut_from_city_id = $routeCityId
			";
			$fetchVehicleDetailsCountQueries .= "
				AND rt.rut_to_city_id = $routeCityId OR rt.rut_from_city_id = $routeCityId
			";
		}

		if (!empty($svcId))
		{
			$fetchVehicleDetailsQueries .= "
				AND scvhc.scv_id = $svcId
			";

			$fetchVehicleDetailsCountQueries .= "
				AND scvhc.scv_id = $svcId
			";
		}

		if (!empty($sccId))
		{
			$fetchVehicleDetailsQueries		 .= "
				AND scvhc.scv_scc_id = $sccId
			";
			$fetchVehicleDetailsCountQueries .= "
				AND scvhc.scv_scc_id = $sccId
			";
		}

		if (!empty($sourceZone))
		{
			$fetchVehicleDetailsQueries .= " AND fzc.zct_zon_id = $sourceZone";
			$fetchVehicleDetailsCountQueries .= " AND fzc.zct_zon_id = $sourceZone";
		}
		if (!empty($destinationZone))
		{
			$fetchVehicleDetailsQueries .= " AND tzc.zct_zon_id = $destinationZone";
			$fetchVehicleDetailsCountQueries .= " AND tzc.zct_zon_id = $destinationZone";
		}

//		$fetchVehicleDetailsQueries .= "
//			ORDER BY rte_amount DESC
//		";

		$count			 = DBUtil::command($fetchVehicleDetailsCountQueries, DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($fetchVehicleDetailsQueries,
				[
			"totalItemCount" => $count,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes'	 => ['sccLabel', 'vcLabel', 'routeFromName', 'routeCityName', 'rte_vendor_amount', 'rateStateTax', 'rte_toll_tax', 'rte_amount', 'routeCreateDate', 'routeModifiedDate'],
					'defaultOrder'	 => 'sccLabel DESC'
				],
			"pagination"	 =>["pageSize" => 50],
		]);

		return $dataprovider;
	}

	/**
	 * @deprecated since version 02-10-2019
	 * direct use no function needed
	 */
	public function checkExisting($rutid, $vhtid)
	{
		exit;
		$criteria = new CDbCriteria;
		if ($rutid != '' && $vhtid != '')
		{
			$criteria->compare('rte_route_id', $rutid);
			$criteria->compare('rte_vehicletype_id', $vhtid);
			$rmodel = $this->find($criteria);
			return $rmodel;
		}
	}

	public function fetchExclRatebyRutnVht($rutid, $vhtid)
	{

		$criteria = new CDbCriteria;
		if ($rutid != '' && $vhtid != '')
		{
			$criteria->compare('rte_route_id', $rutid);
			$criteria->compare('rte_vehicletype_id', $vhtid);
			$criteria->order = 'rte_create_date DESC';
			$rmodel			 = $this->find($criteria);
			return $rmodel->rte_excl_amount;
		}
		else
		{
			return 0;
		}
	}

	public function getCabDetailsbyCities($scity, $dcity)
	{
		if ($scity != '' && $dcity != '')
		{
			$criteria			 = new CDbCriteria();
			$criteria->select	 = ['rte_vendor_amount', 'rte_toll_tax', 'rte_state_tax', 'rte_minimum_markup'];
			$criteria->compare('rteRoute.rut_from_city_id', $scity);
			$criteria->compare('rteRoute.rut_to_city_id', $dcity);
			$criteria->with		 = [
				'svcClassVhcCat.scc_VehicleCategory' => ['select' => 'vct_label,vct_desc,vct_capacity,vct_image'],
				'svcClassVhcCat.scc_ServiceClass'	 => ['select' => 'scc_label'],
				'rteRoute'
			];
			$criteria->order	 = 'rte_vendor_amount ASC';
			$rtt				 = $this->findAll($criteria);
			return $rtt;
		}
		return FALSE;
	}

	public function fetchCabDetailsByCites($requestDetails = null)
	{
		if (empty($requestDetails))
		{
			return 0;
		}

		$fromCityId	 = $requestDetails["fromCityId"];
		$toCityId	 = $requestDetails["toCityId"];

		if (empty($fromCityId) || empty($toCityId))
		{
			return 0;
		}

		$fetchDetails = "
			SELECT  scvhc.scv_id,
					vc.vct_id,
					r.rte_id,
					rt.rut_id,
					r.rte_route_id,
					scc_label, 
					vct_label,
					r.rte_amount,
					r.rte_toll_tax,
					r.rte_state_tax,
					r.rte_vendor_amount,
					rt.*,
					rt.rut_create_date
			FROM `svc_class_vhc_cat` scvhc
			INNER JOIN vehicle_category vc ON scvhc.scv_vct_id = vc.vct_id
			INNER JOIN service_class sc ON scvhc.scv_scc_id = sc.scc_id
			INNER JOIN rate r ON r.rte_vehicletype_id = scvhc.scv_id 
			INNER JOIN route rt ON rt.rut_id = r.rte_route_id
			WHERE     scvhc.scv_active = 1
				   AND vc.vct_active = 1
				   AND sc.scc_active = 1
				   AND r.rte_status = 1 
				   AND rt.rut_from_city_id = $fromCityId
				   AND rt.rut_to_city_id = $toCityId
		    ORDER BY rte_amount DESC
		";

		$arrVehicleDetails = DBUtil::queryAll($fetchDetails, DBUtil::SDB());

		return $arrVehicleDetails;
	}

	public function getCabDetailsbyCitiesList($scity, $dcity)
	{
		$rtt		 = $this->getCabDetailsbyCities($scity, $dcity);
		$rateData	 = [];
		foreach ($rtt as $val)
		{
			$rateData[] = [
				'cab_id'			 => $val->svcClassVhcCat->scv_id,
				'cab_type'			 => $val->svcClassVhcCat->scc_VehicleCategory->vct_label,
				'image_path'		 => $val->svcClassVhcCat->scc_VehicleCategory->vct_image,
				'cab_model'			 => strtoupper($val->svcClassVhcCat->scc_ServiceClass->scc_label) . ' (' . $val->svcClassVhcCat->scc_VehicleCategory->vct_label . ' ' . $val->svcClassVhcCat->scc_VehicleCategory->vct_desc . ')',
				'cab_capacity'		 => $val->svcClassVhcCat->scc_VehicleCategory->vct_capacity,
				'cab_vendor_amount'	 => $val->rte_vendor_amount,
				'cab_toll_tax'		 => $val->rte_toll_tax,
				'cab_state_tax'		 => $val->rte_state_tax,
				'cab_base_amount'	 => $val->rte_vendor_amount - $val->rte_toll_tax - $val->rte_state_tax
			];
		}
		return $rateData;
	}

	public function getCarModelbyrut($rutid = 0)
	{
		$vlist		 = SvcClassVhcCat::model()->getVctSvcList("list");
		$criteria	 = new CDbCriteria();
		if ($rutid != 0)
		{
			$criteria->compare('rte_route_id', $rutid);
			$criteria->with = ['svcClassVhcCat'];
		}
		$vht	 = $this->findAll($criteria);
		$data	 = [];
		foreach ($vht as $val)
		{
			$data[$val->rte_vehicletype_id] = $vlist[$val->rte_vehicletype_id];
		}
		return $data;
	}

	public function getRatebyCitiesnVehicletype($fcity, $tcity, $vehtypeid)
	{
		$rate			 = 0;
		$criteria		 = new CDbCriteria();
		$criteria->compare('rteRoute.rut_from_city_id', $fcity);
		$criteria->compare('rteRoute.rut_to_city_id', $tcity);
		$criteria->compare('rte_vehicletype_id', $vehtypeid);
		$criteria->with	 = ['svcClassVhcCat', 'rteRoute'];
		$rtt			 = $this->find($criteria);
		if ($rtt)
		{
			$rate = $rtt;
		}
		return $rtt;
	}

	/**
	 * @deprecated since version 02-10-2019
	 * @ignore 
	 * @author Ramala
	 */
	public function getRouteRatebyCitiesnVehicletype1($fcity, $tcity, $vehtypeid)
	{
		exit;
		if ($fcity != '' && $tcity != '' && $vehtypeid != '')
		{
			$model = $this->getRatebyCitiesnVehicletype($fcity, $tcity, $vehtypeid);
			return $model->rte_amount;
		}
		else
		{
			return 0;
		}
	}

	public function getRateLog($rteid)
	{
		$qry	 = "select rte_log from rate where rte_id = " . $rteid;
		$logList = DBUtil::queryRow($qry);
		return $logList;
	}

	public function getCabDetailsbyCitiesArr($scity, $dcity)
	{
		$rtt		 = $this->getCabDetailsbyCities($scity, $dcity);
		$rateData	 = [];
		foreach ($rtt as $val)
		{
			$rateData[] = [
				'cab_id'			 => $val->svcClassVhcCat->scv_id,
				'cab_type'			 => $val->svcClassVhcCat->scc_VehicleCategory->vct_label,
				'image_path'		 => $val->svcClassVhcCat->scc_VehicleCategory->vct_image,
				'cab_model'			 => strtoupper($val->svcClassVhcCat->scc_ServiceClass->scc_label) . ' (' . $val->svcClassVhcCat->scc_VehicleCategory->vct_label . ' ' . $val->svcClassVhcCat->scc_VehicleCategory->vct_desc . ')',
				'cab_capacity'		 => $val->svcClassVhcCat->scc_VehicleCategory->vct_capacity,
				'cab_vendor_amount'	 => $val->rte_vendor_amount,
				'cab_toll_tax'		 => $val->rte_toll_tax,
				'cab_state_tax'		 => $val->rte_state_tax,
				'cab_base_amount'	 => $val->rte_vendor_amount - $val->rte_toll_tax - $val->rte_state_tax
			];
		}
		return $rateData;
	}

	public function getCabDetailsbyCitiesAgentArr($scity, $dcity, $agent_id)
	{
		$rtt		 = $this->getCabDetailsbyCities($scity, $dcity);
		$rateData	 = [];
		foreach ($rtt as $val)
		{
			$cab_vendor_amount	 = $val->rte_vendor_amount;
			$cab_toll_tax		 = $val->rte_toll_tax;
			$cab_state_tax		 = $val->rte_state_tax;
			$gozo_base_amount	 = $val->rte_vendor_amount - $val->rte_toll_tax - $val->rte_state_tax;
			//$gozo_base_amount = Booking::model()->getAmountExcludingTax($val->rte_amount);
			$tax_rate			 = Filter::getServiceTaxRate();
			//$tax_rate			 = BookingInvoice::getGstTaxRate($agent_id);
			
			$gozo_markup		 = 0;
			$agent_markup		 = 0;
			if ($agent_id)
			{
				$agent_model	 = Agents::model()->findByPk($agent_id);
				$commisionType	 = $agent_model->agt_commission_value;
				$commision		 = $agent_model->agt_commission | 0;
				if ($agent_model->agt_type == 1 || $agent_model->agt_type == 0)
				{
					$commision = 0;
				}
				$gozoCommisionType	 = $agent_model->agt_gozo_commission_value;
				$gozoCommision		 = $agent_model->agt_gozo_commission | 0;
				$agent_markup		 = ($commisionType == 1) ? round(($commision * $gozo_base_amount) / 100) : $commision;
				$gozo_markup		 = ($gozoCommisionType == 1) ? round(($gozoCommision * $gozo_base_amount) / 100) : $gozoCommision;
			}
			$base_amount	 = $gozo_base_amount + $agent_markup + $gozo_markup;
			$service_tax	 = round($base_amount * $tax_rate / 100);
			$total_amount	 = $base_amount + $service_tax;
			$rateData[]		 = [
				'cab_id'			 => $val->svcClassVhcCat->scv_id,
				'cab_type'			 => $val->svcClassVhcCat->scc_VehicleCategory->vct_label,
				'image_path'		 => $val->svcClassVhcCat->scc_VehicleCategory->vct_image,
				'cab_model'			 => strtoupper($val->svcClassVhcCat->scc_ServiceClass->scc_label) . ' (' . $val->svcClassVhcCat->scc_VehicleCategory->vct_label . ' ' . $val->svcClassVhcCat->scc_VehicleCategory->vct_desc . ')',
				'cab_capacity'		 => $val->svcClassVhcCat->scc_VehicleCategory->vct_capacity,
				'cab_vendor_amount'	 => $val->rte_vendor_amount,
				'cab_toll_tax'		 => $val->rte_toll_tax,
				'cab_state_tax'		 => $val->rte_state_tax,
				'cab_base_amount'	 => $val->rte_vendor_amount - $val->rte_toll_tax - $val->rte_state_tax
			];
		}
		return $rateData;
	}

	public function getRateListAgent()
	{
		$qry		 = "SELECT 
						c1.cty_id  AS from_city_id,
						c1.cty_name AS from_city_name,
						c2.cty_id  AS to_city_id,c2.cty_name AS to_city_name,
						scvc.scv_id AS cab_type_id, vc.vct_label AS cab_model,
						vc.vct_image   AS cab_image,
						rte_amount  AS amount
			    FROM rate
                LEFT JOIN route ON rte_route_id=rut_id
						LEFT JOIN svc_class_vhc_cat AS scvc  ON rte_vehicletype_id = scvc.scv_id  AND scvc.scv_active = 1
						LEFT JOIN vehicle_category AS vc     ON vc.vct_id = scvc.scv_vct_id   AND vc.vct_active = 1
						LEFT JOIN service_class AS sc        ON sc.scc_id = scvc.scv_scc_id  AND sc.scc_active = 1
                LEFT JOIN cities c1 ON rut_from_city_id=c1.cty_id
                LEFT JOIN cities c2 ON rut_to_city_id=c2.cty_id
						WHERE rte_status = 1
							AND scvc.scv_active = 1
							AND rut_active = 1
							AND c1.cty_active = 1
							AND c2.cty_active = 1";
		$recordset	 = DBUtil::queryAll($qry);
		return $recordset;
	}

	public function getCabRatebyCities($scity, $dcity)
	{
		$qry		 = "SELECT  
						rut_id,
            c1.cty_garage_address AS from_address,
            c2.cty_garage_address AS to_address,
            c1.cty_name AS from_city_name,
						c2.cty_name AS to_city_name,
						scvc.scv_id AS cab_type_id,
						rte_vendor_amount AS rtVndamount 
						FROM rate
                INNER JOIN route ON rte_route_id=rut_id AND rut_active=1
						INNER JOIN svc_class_vhc_cat AS scvc  ON rte_vehicletype_id = scvc.scv_id  AND scvc.scv_active = 1
                INNER JOIN cities c1 ON rut_from_city_id=c1.cty_id AND c1.cty_active=1
                INNER JOIN cities c2 ON rut_to_city_id=c2.cty_id AND c2.cty_active=1
						WHERE rte_status=1	AND rut_from_city_id=$scity AND rut_to_city_id=$dcity";
		$recordset	 = DBUtil::queryAll($qry);
		return $recordset;
	}

	public function getTopReportList()
	{
		$sql		 = "SELECT c1.cty_name as from_city, c1.cty_id as from_city_id, c2.cty_id as to_city_id, c2.cty_name as to_city, route.rut_estm_distance, COUNT(DISTINCT b1.bkg_id) as total_booking_count, SUM(IF(b1.bkg_status IN (2,3,5,6,7),1,0)) as active_bookings, SUM(IF(b1.bkg_status IN (9),1,0)) as cancelled_bookings
            FROM route
            join cities c1 on `rut_from_city_id`= c1.cty_id AND c1.cty_active =1 AND c1.cty_service_active=1
            join cities c2 on `rut_to_city_id`= c2.cty_id AND c2.cty_active =1 AND c1.cty_service_active=1
            join booking b1 on b1.bkg_from_city_id = rut_from_city_id AND b1.bkg_to_city_id = rut_to_city_id
            where rut_active = 1 AND c1.cty_id <> c2.cty_id AND route.rut_estm_distance < 300
            GROUP BY rut_id ORDER BY total_booking_count DESC ";
		$recordset	 = DBUtil::queryAll($sql);
		return $recordset;
	}

//    public function getCheapRateByRouteId($routeId) {
//        $sql = "SELECT * FROM `rate` WHERE rate.rte_route_id=$routeId ORDER BY rate.rte_amount ASC LIMIT 0,1";
//        return DBUtil::queryRow($sql);
//    }
	public function getCheapRateByRouteId($routeId)
	{
		$sql = "SELECT * FROM `rate` WHERE rate.rte_route_id=$routeId ORDER BY rate.rte_vendor_amount ASC LIMIT 0,1";
		return DBUtil::queryRow($sql);
	}

	public function getBase()
	{
		$vndAmount	 = $this->rte_vendor_amount;
		$tollTax	 = $this->rte_toll_tax;
		$stateTax	 = $this->rte_state_tax;
		$baseAmount	 = $vndAmount - $tollTax - $stateTax;
		return $baseAmount;
	}

	public function fetchRatebyRutnVht($rutid, $vhtid)
	{

		$criteria = new CDbCriteria;
		if ($rutid != '' && $vhtid != '')
		{
			$criteria->compare('rte_route_id', $rutid);
			$criteria->compare('rte_vehicletype_id', $vhtid);
			$criteria->order = 'rte_create_date DESC';
			$rmodel			 = $this->find($criteria);
			return $rmodel->rte_amount;
		}
		else
		{
			return 0;
		}
	}

	public static function getlastUpdated($fromCity, $toCity, $cabType, $tripType = '')
	{
		if ($fromCity != '' && $toCity != '')
		{
			$sql_vehicle_type	 = ($cabType != '') ? " AND (rte_vehicletype_id={$cabType} OR rte_vehicletype_id IS NULL)" : "";
			$sql_trip_type		 = ($tripType != '') ? " AND (rte_trip_type={$tripType} OR rte_trip_type IS NULL)" : "";

			$sql = "SELECT rte.rte_id, rte_update_date, rte_log,rut_from_city_id,rut_to_city_id
				FROM   route rut JOIN rate rte ON rte.rte_route_id = rut.rut_id
				WHERE  rut.rut_from_city_id = $fromCity AND rut.rut_to_city_id = $toCity $sql_vehicle_type $sql_trip_type AND rte_status = 1 AND  rte_create_date < (NOW()- INTERVAL 30 DAY)  ";

			$result = DBUtil::queryRow($sql);

			$result['rut_from_city_id'] . ' - ' . $result['rut_to_city_id'] . ' - ';
			$modifiedDate = $result['rte_update_date'];

			if ($result['rte_log'] != '')
			{
				$rtLog = CJSON::decode($result['rte_log']);
				foreach ($rtLog as $logVal)
				{
					$modifiedDate = ($modifiedDate < $logVal[1]) ? $logVal[1] : $modifiedDate;
				}
			}
		}
		return $modifiedDate;
	}

	/**
	 * @param integer $routeId route Id for rate
	 * @param array $newRates new rates entered
	 * @param array $oldRates previous rates if exists if not then array of vehicle categories
	 * @param boolean $returnCheck if true update same rates for return route 
	 * @since version 24/06/2021
	 * @author Ramala
	 */
	public static function addRates($routeId, $newRates = [], $oldRates = [], $returnCheck = false)
	{
		$returnSet	 = new ReturnSet();
		$returnSet->setStatus(true);
		$transaction = DBUtil::beginTransaction();
		try
		{
			/* updating rates */
			foreach ($oldRates as $value)
			{
				//check if vehicle category checkbox checked in form
				if ($newRates[$value['scv_id']]['scv_id'] <= 0)
				{
					continue;
				}
				if ($value['rte_vendor_amount'] == $newRates[$value['scv_id']]['rte_vendor_amount'] &&
						$value['rte_toll_tax'] == $newRates[$value['scv_id']]['rte_toll_tax'] &&
						$value['rte_state_tax'] == $newRates[$value['scv_id']]['rte_state_tax'] &&
						$value['rte_minimum_markup'] == $newRates[$value['scv_id']]['rte_minimum_markup'])
				{
					continue;
				}
				//check if vendor amount sets 0 for an exist record then deactivate that record
				if ($value['rte_id'] > 0 && $newRates[$value['scv_id']]['rte_vendor_amount'] == 0)
				{
					$rateModel				 = Rate::model()->findByPk($value['rte_id']);
					$rateModel->rte_status	 = 0;
					$rateModel->save();
					continue;
				}
				if ($value['rte_id'] != "")
				{
					//update existing
					$rateModel	 = Rate::model()->findByPk($value['rte_id']);
					$logJson	 = self::getLogJson($rateModel->rte_log, $value, $newRates[$value['scv_id']]);
				}
				else
				{
					//add new
					$rateModel = new Rate();
				}
				$rateModel->rte_route_id		 = $routeId;
				$rateModel->rte_vendor_amount	 = $newRates[$value['scv_id']]['rte_vendor_amount'];
				$rateModel->rte_toll_tax		 = $newRates[$value['scv_id']]['rte_toll_tax'];
				$rateModel->rte_state_tax		 = $newRates[$value['scv_id']]['rte_state_tax'];
				$rateModel->rte_minimum_markup	 = $newRates[$value['scv_id']]['rte_minimum_markup'];
				$rateModel->rte_vehicletype_id	 = $value['scv_id'];
				$rateModel->rte_update_date		 = DBUtil::getCurrentTime();
				if ($logJson)
				{
					$rateModel->rte_log = $logJson;
				}
				$rateModel->rte_status = 1;
				if (!$rateModel->save())
				{
					$returnSet->setStatus(false);
					$returnSet->setErrors($rateModel->errors);
					return $returnSet;
				}
			}
			/* updating rates for return route */
			if ($returnCheck)
			{
				$data		 = Route::model()->find("rut_id=:route", array("route" => $routeId));
				$fromcity	 = $data->rut_from_city_id;
				$tocity		 = $data->rut_to_city_id;
				$returnRoute = Route::model()->getbyCities($tocity, $fromcity);
				if ($returnRoute)
				{
					$oldRates	 = Rate::model()->getVehicleDetailsByRoute($returnRoute['rut_id']);
					$returnSet	 = self::addRates($returnRoute['rut_id'], $newRates, $oldRates);
				}
			}
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet->setMessage($e->getMessage());
			DBUtil::rollbackTransaction($transaction);
			return $returnSet;
		}
		DBUtil::commitTransaction($transaction);
		return $returnSet;
	}

	/**
	 * 
	 * @param type $oldLogData old log if already exists in database
	 * @param type $oldData old data before changes
	 * @param type $newData new data after changes
	 * @return boolean or JSON
	 * @since version 24/06/2021
	 * @author Ramala
	 */
	public static function getLogJson($oldLogData = "", $oldData, $newData)
	{
		if (!empty($oldData))
		{
			$newLogData = array
				(
				0	 => Yii::app()->user->getId(),
				1	 => date("Y-m-d H:i:s"),
				2	 => 'VendorAmount:' . $oldData['rte_vendor_amount'] . ' -> ' . $newData['rte_vendor_amount'],
				3	 => 'TollTax:' . $oldData['rte_toll_tax'] . ' -> ' . $newData['rte_toll_tax'],
				4	 => 'Statetax:' . $oldData['rte_state_tax'] . ' -> ' . $newData['rte_state_tax'],
				5	 => 'Min Markup:' . $oldData['rte_minimum_markup'] . ' -> ' . $newData['rte_minimum_markup']
			);
			if ($oldLogData != "" && $oldLogData != "null")
			{
				$decodedRemark = CJSON::decode($oldLogData);
				array_unshift($decodedRemark, $newLogData);
			}
			else
			{
				$decodedRemark = $newLogData;
			}
			if (count($decodedRemark) > 5)
			{
				$arrMax = array_splice($decodedRemark, -5, 5);
				return CJSON::encode($arrMax);
			}
			return CJSON::encode($decodedRemark);
		}

		return false;
	}

	public static function lastModifidedDays($fromZone, $toZone, $vehicleId)
	{
		try
		{
			$sql = 'SELECT 
                DATEDIFF(NOW(), rte_update_date) AS days
                FROM route
                INNER JOIN rate ON rate.rte_route_id=route.rut_id   AND rate.rte_status=1 AND route.rut_active=1
                INNER JOIN cities c1 ON c1.cty_id=route.rut_from_city_id AND c1.cty_active=1
                INNER JOIN cities c2 ON c2.cty_id=route.rut_to_city_id AND c2.cty_active=1
                INNER JOIN zone_cities zc1 ON c1.cty_id=zc1.zct_cty_id AND zc1.zct_active=1
                INNER JOIN zone_cities zc2 ON c2.cty_id=zc2.zct_cty_id AND zc2.zct_active=1
                INNER JOIN zones z1 ON z1.zon_id=zc1.zct_zon_id AND z1.zon_active=1
                INNER JOIN zones z2 ON z2.zon_id=zc2.zct_zon_id AND z2.zon_active=1
                WHERE 1 
                AND z1.zon_id=:fromZone
                AND z2.zon_id=:toZone
                AND rate.rte_vehicletype_id=:vehicleId
                AND rate.rte_update_date BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 60 DAY)," 00:00:00") AND CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY)," 23:59:59")
                ORDER BY days ASC LIMIT 0,1';
			return DBUtil::queryScalar($sql, DBUtil::SDB(), ['fromZone' => $fromZone, 'toZone' => $toZone, 'vehicleId' => $vehicleId]);
		}
		catch (Exception $ex)
		{
			Filter::writeToConsole($ex->getMessage());
			Filter::writeToConsole(json_encode(['fromZone' => $fromZone, 'toZone' => $toZone, 'vehicleId' => $vehicleId]));
		}
	}

	public static function lastRateUpdateDays($quoteModel)
	{
		$scv_id					 = $quoteModel->cabType;
		$tripType				 = $quoteModel->tripType;
		$fromCity				 = $quoteModel->sourceCity;
		$toCity					 = $quoteModel->destinationCity;
		$lastModifiedRateDate	 = Rate::getlastUpdated($fromCity, $toCity, $scv_id, $tripType);
		$days					 = (Filter::getTimeDiff(date("Y-m-d H:i:s"), $lastModifiedRateDate) / 1440);
		return $days > 0 ? $days : 0;
	}

}

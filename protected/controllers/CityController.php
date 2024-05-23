<?php

use CJSON;

include_once(dirname(__FILE__) . '/BaseController.php');

class CityController extends BaseController
{

	public $newHome	 = '';
	public $layout	 = '//layouts/column1';
	public $afterVal = '';
	public $email_receipient;

	public function filters()
	{
		return array(
			array(
				'application.filters.HttpsFilter + create, signin',
				'bypass' => false),
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
			array(
				'RestfullYii.filters.ERestFilter +
                REST.GET, REST.PUT, REST.POST, REST.DELETE, REST.OPTIONS'
			),
		);
	}

	public function actions()
	{
		$pass = uniqid(rand(), TRUE);
		return array(
			'oauth'		 => array(
// the list of additional properties of this action is below
				'class'				 => 'ext.hoauth.HOAuthAction',
				// Yii alias for your user's model, or simply class name, when it already on yii's import path
// default value of this property is: User
				'model'				 => 'Users',
				'alwaysCheckPass'	 => false,
				// map model attributes to attributes of user's social profile
// model attribute => profile attribute
// the list of avaible attributes is below
				'attributes'		 => array(
					'usr_email'			 => 'email',
					'username'			 => 'displayName',
					// you can also specify additional values,
// that will be applied to your model (eg. account activation status)
					'usr_email_verify'	 => 1,
					'user_type'			 => 1,
					'new_password'		 => $pass,
					'repeat_password'	 => $pass,
					'tnc'				 => 1,
				),
			),
			// this is an admin action that will help you to configure HybridAuth
// (you must delete this action, when you'll be ready with configuration, or
// specify rules for admin role. User shouldn't have access to this action!)
			'oauthadmin' => array(
				'class' => 'ext.hoauth.HOAuthAdminAction',
			),
			'REST.'		 => 'RestfullYii.actions.ERestActionProvider',
		);
	}

	public function hoauthAfterLogin(Users $user, $isNewUser)
	{
		
	}

	public function accessRules()
	{
		return array(
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array(),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('citylinks', 'cityplaces','cityId', 'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users'		 => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array(),
				'users'		 => array('admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function restEvents()
	{
		$this->onRest('req.cors.access.control.allow.methods', function() {
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest('post.filter.req.auth.user', function($validation) {
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();

			$ri = array('/source_citylist', '/destination_citylist1', '/getdetails');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.post.source_citylist.render', function() {
			$last_updated	 = Yii::app()->request->getParam('last_update');
			$cities			 = Cities::model()->getSourceList($last_updated);
			if ($cities)
			{
				$result = ['success' => true, 'cities' => $cities];
			}
			else
			{
				$result = ['success' => false, 'errors' => ['1' => 'Something went wrong']];
			}
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => array(
							'result' => $result,
						)
			]);
		});
		
		$this->onRest('req.get.getdetails.render', function() {
			$id	 = Yii::app()->request->getParam('cid');
			$city		 = \Stub\common\Cities::getGeometricDetails($id);

			if ($city)
			{
				$result = ['success' => true, 'data' => $city];
			}
			else
			{
				$result = ['success' => false, 'errors' => ['1' => 'Something went wrong']];
			}
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => $result
			]);
		});

		$this->onRest('req.get.destination_citylist1.render', function() {
			$scity		 = Yii::app()->request->getParam('scity');
			$forAirport	 = Yii::app()->request->getParam('forAirport');
			if ($forAirport == 'true')
			{
				$forAirport	 = true;
				$maxDistance = Yii::app()->params['airportCityRadius'];
			}
			else
			{
				$maxDistance = 500;
				$forAirport	 = false;
			}
			$cityArrays	 = Cities::model()->getNearestCitiesDistanceListbyId($scity, $maxDistance, $forAirport);
			$counter	 = 0;
			foreach ($cityArrays as $cityArray)
			{
				$counter++;
				if (count($cityArrays) == $counter)
				{
					$cities .= $cityArray['cty_id'];
				}
				else
				{
					$cities .= $cityArray['cty_id'] . ", ";
				}
			}
			if ($cities)
			{
				$result = ['success' => true, 'cities' => [0 => ['city_list' => $cities]]];
			}
			else
			{
				$result = ['success' => false, 'errors' => ['1' => 'Something went wrong']];
			}
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => array(
							'result' => $result,
						)
			]);
		});
	}

	public function actionCitylinks()
	{
		$this->checkForMobileTheme();
		$cityid	 = Yii::app()->request->getParam('cid');
		$cat	 = Yii::app()->request->getParam('cat');
		$model	 = new CityLinks();

		if (isset($_POST['CityLinks']))
		{
			$model->cln_user_id	 = Yii::app()->user->getId();
			$model->cln_user_ip	 = \Filter::getUserIP();
			$model->cln_category = Yii::app()->request->getParam('cat');
			$model->cln_city_id	 = Yii::app()->request->getParam('city_id');
			$model->cln_datetime = new CDbExpression('NOW()');
			$model->attributes	 = $_POST['CityLinks'];

			$model->save();
			echo json_encode(['success' => true]);
			Yii::app()->end();
		}

		$cdata			 = array();
		$cdata['cid']	 = $cityid;
		$cdata['cat']	 = $cat;

		$this->renderPartial('addlinks', array('model' => $model, 'cdata' => $cdata), false, true);
	}

	public function actionCityplaces()
	{
		$this->checkForMobileTheme();
		$cityid	 = Yii::app()->request->getParam('cid');
		$cat	 = Yii::app()->request->getParam('cat');
		$model	 = new CityPlaces();

		if (isset($_POST['CityPlaces']))
		{
			$model->cpl_user_id	 = Yii::app()->user->getId();
			$model->cpl_user_ip	 = \Filter::getUserIP();
			$model->cpl_category = Yii::app()->request->getParam('cat');
			$model->cpl_city_id	 = Yii::app()->request->getParam('city_id');
			$model->cpl_datetime = new CDbExpression('NOW()');
			$model->attributes	 = $_POST['CityPlaces'];

			$model->save();
			echo json_encode(['success' => true]);
			Yii::app()->end();
		}

		$cdata			 = array();
		$cdata['cid']	 = $cityid;
		$cdata['cat']	 = $cat;

		$this->renderPartial('addplace', array('model' => $model, 'cdata' => $cdata), false, true);
	}
	public function actionCitystat()
	{
		$vendorCount	 = CityStats::model()->getVendorCount();
		$DriverCount	 = CityStats::model()->getDriverCount();
		$BookingCount	 = CityStats::model()->getBookingCount();
		$ratingCount	 = CityStats::model()->getRatingCount();

		foreach ($vendorCount as $value)
		{
			$cityID	 = $value['cty_id'];
			$model	 = CityStats::model()->findByPk($cityID);
			if (!$model)
			{
				$model				 = new CityStats();
				$model->cst_cty_id	 = $cityID;
			}

			$model->cst_active_vendor_no		 = $value['ActiveVendor'];
			$model->cst_freezed_vendor_no		 = $value['FreezedVendor'];
			$model->cst_pending_aapr_vendor_no	 = $value['PendingVendor'];
			$model->save();
		}

		foreach ($DriverCount as $value)
		{
			$cityID	 = $value['cty_id'];
			$model	 = CityStats::model()->findByPk($cityID);
			if (!$model)
			{
				$model				 = new CityStats();
				$model->cst_cty_id	 = $cityID;
			}
			$model->cst_tot_drivers = $value['ActiveDriver'];
			$model->save();
		}

		foreach ($BookingCount as $value)
		{
			$cityID	 = $value['cty_id'];
			$model	 = CityStats::model()->findByPk($cityID);
			if (!$model)
			{
				$model				 = new CityStats();
				$model->cst_cty_id	 = $cityID;
			}
			$model->cst_booking_enq_30days		 = $value['30daysCount'];
			$model->cst_booking_enq_7days		 = $value['7daysCount'];
			$model->cst_booking_served_30days	 = $value['30daysServedCount'];
			$model->cst_booking_served_7days	 = $value['7daysServedCount'];
			$model->save();
		}
		foreach ($ratingCount as $value)
		{
			$cityID	 = $value['cty_id'];
			$model	 = CityStats::model()->findByPk($cityID);
			if (!$model)
			{
				$model				 = new CityStats();
				$model->cst_cty_id	 = $cityID;
			}
			$model->cst_avg_rating = $value['rating'];
			$model->save();
		}
	}

	public function actionUpdateCityStatData()
	{
		$statCount = CityStats::model()->getStatsData();
		$i=0;$j=0;
		foreach ($statCount as $value)
		{
			$cityID	 = $value['ctyid'];
			$model	 = CityStats::model()->findByPk($cityID);
			if (!$model)
			{
				$model				 = new CityStats();
				$model->cst_cty_id	 = $cityID;
			}

			$model->cst_active_vendor_no		 = $value['ActiveVendor'];
			$model->cst_freezed_vendor_no		 = $value['FreezedVendor'];
			$model->cst_pending_aapr_vendor_no	 = $value['PendingVendor'];
			$model->cst_tot_drivers				 = $value['ActiveDriver'];
			$model->cst_booking_enq_30days		 = $value['30daysCount'];
			$model->cst_booking_enq_7days		 = $value['7daysCount'];
			$model->cst_booking_served_30days	 = $value['30daysServedCount'];
			$model->cst_booking_served_7days	 = $value['7daysServedCount'];
			$model->cst_avg_rating				 = $value['rating'];
			if($model->save()){
				 $i++;
			}else{
				$j++;
			}
		}
		echo $i.' Saved and '.$j.' not';
	}


	
	public function actionCityId()
	{
		Logger::profile("Started {". json_encode($_REQUEST)."}");
		$cLat				 = Yii::app()->request->getParam('cLat', 0.0);
		$cLong				 = Yii::app()->request->getParam('cLong', 0.0);
		$placeId			 = Yii::app()->request->getParam('placeId');
		$formattedAddress	 = Yii::app()->request->getParam('formattedAddress');
		$types				 = Yii::app()->request->getParam('types');
		$isAirport			 = Yii::app()->request->getParam('isAirport', 0);
		$ctyId				 = Yii::app()->request->getParam('ctyId', 0);
		if($ctyId > 0){
			$ctyArr = Cities::getCtyLatLongByCtyId($ctyId);
			Logger::profile("getCtyLatLongByCtyId Found");
		}
		else if($cLat > 0.0 && $cLong > 0.0)
		{
			/* @var $place \Stub\common\Place */
			$place				 = new \Stub\common\Place();
			$placeObj			 = $place->init($cLat, $cLong, $placeId, $formattedAddress, $types);
			$latLongModel		 = LatLong::getDataByLatLongBound($placeObj);
			Logger::profile("LatLong::getDataByLatLongBound Done");
			if($latLongModel == false || $latLongModel == null)
			{
				$ctyArr['ctyId'] = 0;
				goto result;
			}
			$ctyModel			 = Cities::model()->findByPk($latLongModel->ltg_city_id);
			$ctyArr['ctyId']	 = $latLongModel->ltg_city_id;
			$ctyArr['isAirport'] = $ctyModel->cty_is_airport;
			$ctyArr['isPoiType'] = $ctyModel->cty_poi_type;
			$ctyArr['ctyLat']	 = $placeObj->coordinates->latitude;
			$ctyArr['ctyLong']	 = $placeObj->coordinates->longitude;
			$ctyArr['grageAdd']	 = '';
		}
		Logger::profile("Done");
		result:
		echo json_encode($ctyArr);
	}

}

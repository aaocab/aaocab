<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class TrackController extends BaseController
{
	public function filters()
	{
		return array
		(
			array
			(
				"application.filters.HttpsFilter + create",
				"bypass" => false
			),
			"accessControl", // perform access control for CRUD operations
			"postOnly + delete", // we only allow deletion via POST request
			array
			(
				"RestfullYii.filters.ERestFilter +
                REST.GET, REST.PUT, REST.POST, REST.DELETE, REST.OPTIONS"
			),
		);
	}

	public function actions()
	{
		return array
		(
			"REST." => "RestfullYii.actions.ERestActionProvider",
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array
		(
			array
			("allow", // allow all users to perform "index" and "view" actions
				"actions"	 => array(),
				"users"		 => array("@"),
			),
			array
			("allow", // allow authenticated user to perform "create" and "update" actions
				"actions"	 => array
				(
					"REST.GET", "REST.PUT", "REST.POST", "REST.DELETE", "REST.OPTIONS", "uploads"
				),
				"users"		 => array("*"),
			),
			array
			("allow", // allow admin user to perform "admin" and "delete" actions
				"actions"	 => array(),
				"users"		 => array("admin"),
			),
			array
			("deny", // deny all users
				"users" => array("*"),
			),
		);
	}

	/**
	 * This holds the REST API Events that's needs to be used
	 */
	public function restEvents()
	{
		$this->onRest("req.cors.access.control.allow.methods", function() 
		{
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest("req.post.syncBookingTrackDetails.render", function()
		{
			return $this->renderJSON($this->syncTrackDetails());
		});
		
		$this->onRest("req.post.syncDriverTrackDetails.render", function () {
            return $this->renderJSON($this->syncDriverTrackDetails());
        });

//		$this->onRest("req.post.drvArrived.render", function () {
//            return $this->renderJSON($this->drvArrived());
//        });
//
//		$this->onRest("req.post.tripStart.render", function () {
//            return $this->renderJSON($this->tripStart());
//        });
//		
//		$this->onRest("req.post.tripEnd.render", function () {
//            return $this->renderJSON($this->tripEnd());
//        });	
//
//		$this->onRest("req.post.quickRideUpdateLastLocation.render", function () {
//            return $this->renderJSON($this->quickRideUpdateLastLocation());
//        });
	}
	
	/**
	 * This function handles the booking track sync
	 * @return array
	 */
	public function syncTrackDetails()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$jsonMapper	 = new JsonMapper();
		$jsonObj	 = CJSON::decode($data, false);

		if(empty($jsonObj))
		{
			exit;
		}

		return BookingTrack::syncTrackDetails($jsonObj);
	} 

	/**
	 * This function is used to update track events
	 * return Data
	 */
	public function syncDriverTrackDetails()
	{
		$data		 = Yii::app()->request->rawBody;
		$jsonValue	 = CJSON::decode($data, false);
		$jsonObj	 = $jsonValue->data;

		/** @var Booking $model */
		$model		 = Booking::model()->findByBookingid($jsonObj->orderReferenceNumber);
		if(!$model || $model == null)
		{
			return false;
		}
		$operatorId	 = Operator::getOperatorId($model->bkg_booking_type);
		$objOperator = Operator::getInstance($operatorId);
		
		/* @var $objOperator Operator */
		$objOperator = $objOperator->syncRideData($model->bkg_id, $operatorId, $jsonObj);
		return $objOperator;
	}

}
?>



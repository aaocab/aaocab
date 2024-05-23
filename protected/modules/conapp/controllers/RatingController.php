<?php

use Ratings;

include_once(dirname(__FILE__) . '/BaseController.php');

class RatingController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout		 = 'column1';
	public $email_receipient, $useUserReturnUrl;
	public $current_page = '';

	//public $layout = '//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			array(
				'application.filters.HttpsFilter + create',
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
		return array(
			'REST.' => 'RestfullYii.actions.ERestActionProvider',
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array(),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array(
					'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
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
			$ri	 = array('/getAttributes', '/customer');

			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		// rating customer :: almost done
		$this->onRest('req.post.customer.render', function() {
			return $this->renderJSON($this->customerRating());
		});

		// rating getAttributes ::  done
		$this->onRest('req.get.getAttributes.render', function() {
			return $this->renderJSON($this->getAttributesList());
		});

		// rating getAttributesList :: almost done
		$this->onRest('req.post.getAttributesList.render', function() {
			return $this->renderJSON($this->getAttributesListV1());
		});
	}

	/**
	 * 
	 * @return returnSet
	 * @throws Exception\
	 */
	public function customerRating()
	{
		$returnSet	 = new ReturnSet();
		/* @var $model AppTokens */
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$model	= AppTokens::validateToken($token);			
			$data	=	Yii::app()->request->rawBody;  // '{"review":"Hello Rituparna","overall":4,"recommend":4,"bookingId":750095,"platform":3,"car":{"comments":"very wrost car","star":1,"good_attrs":[46,78],"bad_attrs":[50,36]},"csr":{"comments":"very good","star":4,"good_attrs":[75],"bad_attrs":[32,62,35]},"driver":{"comments":"Good","star":4,"good_attrs":[17],"bad_attrs":[22,23,25,77,13]}}';
			if (!$data)
			{
				throw new Exception("Invalid Request", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			/* @var $obj Stub\consumer\RatingRequest */
			$obj		 = $jsonMapper->map($jsonObj, new \Stub\consumer\RatingRequest());
			/** @var $model \Ratings */
			$model		 = $obj->getModel();
			if (!$model)
			{
				throw new Exception("Invalid Data: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$linkExpire      = Ratings::isLinkExpired(($model->rtg_booking_id));
			
			if ($linkExpire ==1 )
			{
				throw new Exception("Sorry this link has been expired",ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
				//$linkExpier = 1;
			}
			$isRated = Ratings::isRatingPosted($model->rtg_booking_id);
			if ($isRated == true)
			{
				throw new Exception("Rating already posted.", ReturnSet::ERROR_INVALID_DATA);
			}
			if ($model->rtg_booking_id > 0)
			{
				if ($model->rtg_platform == Ratings::PLATFORM_IOS_APP)
				{
					$model->rtg_platform = Ratings::PLATFORM_IOS_APP;
				}
				else
				{
					$model->rtg_platform = Ratings::PLATFORM_ANDROID_APP;
				}
			}
			$uniqueId = Booking::model()->generateLinkUniqueid($model->rtg_booking_id);
			$data	 = array(
				'rtg_booking_id'		 => $model->rtg_booking_id,
				'rtg_customer_recommend' => $model->rtg_customer_recommend,
				'rtg_customer_overall'	 => $model->rtg_customer_overall,
				'rtg_customer_review'	 => $model->rtg_customer_review,
				'rtg_platform'			 => $model->rtg_platform,
				'rtg_customer_driver'	 => $model->rtg_customer_driver,
				'rtg_driver_cmt'		 => $model->rtg_driver_cmt,
				'rtg_customer_car'		 => $model->rtg_customer_car,
				'rtg_car_cmt'			 => $model->rtg_car_cmt,
				'rtg_customer_csr'		 => $model->rtg_customer_csr,
				'rtg_csr_cmt'			 => $model->rtg_csr_cmt,
				'rtg_car_good_attr'		 => $model->rtg_car_good_attr,
				'rtg_car_bad_attr'		 => $model->rtg_car_bad_attr,
				'rtg_csr_good_attr'		 => $model->rtg_csr_good_attr,
				'rtg_csr_bad_attr'		 => $model->rtg_csr_bad_attr,
				'rtg_driver_good_attr'	 => $model->rtg_driver_good_attr,
				'rtg_driver_bad_attr'	 => $model->rtg_driver_bad_attr,
				'rtg_platform'			 => $model->rtg_platform,
				'uniqueId'				 => $uniqueId,
			);
			$result	 = Ratings::model()->addRatingForCustomer($data);
			if ($result['success'] == true)
			{
				
			
				
				$returnSet->setStatus(true);
				$returnSet->setData([
					'booking_id'	 => (int) $result['booking_id'],
					'tripAdviser'	 => $result['tripAdviser'],
					'uniqueId'		 => $result['uniqueId']
				]);
				$returnSet->setMessage('Rating done Successfully.');
				if($linkExpier==1)
				{
					$returnSet->setMessage('Thanks for your feedback.
The review period for this trip has expired since the trip was completed more than 30 days ago. We are capturing your feedback and like all reviews we get, we will also use this information to learn & improve our services.
However, since the accounts for this trip have already been settled with the taxi operator, our ability to take corrective action (if any) in this particular case may be severely limited.
In the future, please provide your trips review feedback soon after trip completion.');
				}
			}
			else
			{
				throw new Exception("Unable to save rating data. ", ReturnSet::ERROR_VALIDATION);
			}
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
		}
		return ($returnSet);
	}


	/**
	 * 
	 * @return returnSet
	 * @throws Exception\
	 */
	public function getAttributesList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$attrModel = RatingAttributes::model()->ratingAttributes();
			if (!$attrModel)
			{
				throw new Exception("Invalid Attritutes : ", ReturnSet::ERROR_INVALID_DATA);
			}
			/* @var $obj \Stub\consumer\ReviewAttributeResponse */
			$response	 = new \Stub\consumer\ReviewAttributeResponse();
			$response->setAttrData($attrModel);
			$response	 = Filter::removeNull($response->attributes);
			if ($response)
			{
				$returnSet->setStatus(true);
				$returnSet->setData($response);
			}
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return ($returnSet);
	}
	
	/** 
	 * 
	 * @return
	 * @throws Exception\
	 */
	public function getAttributesListV1()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data = Yii::app()->request->rawBody;
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			if (property_exists($jsonObj, 'id'))
			{
				$model = \Booking::model()->findByPk($jsonObj->id);
			}
			else if (property_exists($jsonObj, 'code'))
			{
				$model = \Booking::model()->getByCode($jsonObj->code);
			}

			if (!$model)
			{
				throw new Exception("Invalid Booking", ReturnSet::ERROR_INVALID_DATA);
			}
			$attrData = RatingAttributes::model()->ratingAttributes();
			
			/* @var $obj \Stub\consumer\ReviewAttributeResponse */
			$response	 = new \Stub\consumer\ReviewAttributeResponse();
			$response->setAttrDataV1($model, $attrData);
			if ($response)
			{
				$returnSet->setStatus(true);
				$returnSet->setData($response);
			}
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
		}
		return ($returnSet);
	}

}

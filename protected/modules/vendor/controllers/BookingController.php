<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class BookingController extends BaseController
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
		$this->onRest('req.cors.access.control.allow.methods', function () {
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest('post.filter.req.auth.user', function ($validation) {
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri	 = array();

			foreach($ri as $value)
			{
				if(strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.get.bookingCategory.render', function () {
			return $this->renderJSON($this->bookingCategory());
		});

		/**
		 * old service : tripDetails        (  method : POST  )
		 * new service : vendorTripDetails  (  method : POST )
		 */
		$this->onRest('req.post.vendorTripDetails.render', function () {
			return $this->renderJSON($this->vendorTripDetails());
		});

		/*		 *
		 * old service : vendor_account_trip        (  method : POST  )
		 * new service : vendorAccountTrip  (  method : POST )
		 */
		$this->onRest('req.post.vendorAccountTrip.render', function () {
			return $this->renderJSON($this->vendorAccountTrip());
		});

		/*
		 * Old Services: booking_details
		 * New services:bookingDetails
		 */
		$this->onRest('req.post.bookingDetails.render', function () {
			return $this->renderJSON($this->bookingDetails());
		});
		/*
		 * Old Services: change_ride_status
		  New services:changeRideStatus
		 * 
		 */
		$this->onRest('req.post.changeRideStatus.render', function () {
			return $this->renderJSON($this->changeRideStatus());
		});
		/*
		 * Old Services: vendor_trip_complete
		  New services:tripComplete
		 * 
		 */
		$this->onRest('req.post.tripComplete.render', function () {
			return $this->renderJSON($this->tripComplete());
		});
		/*
		 * Old Services: vendor_pending_request
		  New services:vendorPendingRequest
		 * 
		 */
		/**
		 * @deprecated since version 21-10-2020
		 * @author madhumita
		 *  New services:vendorPendingRequestV2
		 */
		$this->onRest('req.post.vendorPendingRequest.render', function () {
			return $this->renderJSON($this->vendorPendingRequest());
		});

		#New service started from 21-10-2020

		$this->onRest('req.post.vendorPendingRequestV2.render', function () {
			return $this->renderJSON($this->vendorPendingRequestV2());
		});

		/*
		 * old service : vendor_unassign  (method : POST)
		 * new service : vendorUnassign	 (method : POST)
		 */
		$this->onRest('req.post.vendorUnassign.render', function () {
			return $this->renderJSON($this->vendorUnassign());
		});

		/*
		 *  old service : customer_no_show   (method : POST)
		 *  new service : customerNoShow	 (method : POST)
		 */
		$this->onRest('req.post.customerNoShow.render', function () {
			return $this->renderJSON($this->customerNoShow());
		});

		/*
		 *  old service : saveVoucherDocs       (method : POST)
		 *  new service : uploadVendorVoucher	(method : POST)
		 */
		$this->onRest('req.post.uploadVendorVoucher.render', function () {

			return $this->renderJSON($this->uploadVendorVoucher());
		});

		/*
		 *  old service : saveVoucherDocs        (method : POST)
		 *  new service : saveVendorVoucherFile	 (method : POST)
		 */
		$this->onRest('req.post.saveVendorVoucherFile.render', function () {

			return $this->renderJSON($this->saveVendorVoucherFile());
		});

		/*
		 *  old service : removeVendorDocs      (method : POST)
		 *  new service : removeVoucher	(method : POST)
		 */
		$this->onRest('req.post.removeVoucher.render', function () {

			return $this->renderJSON($this->removeVoucher());
		});
		/*

		 *  new service : getDestinationNoteList	(method : POST)
		 */
		$this->onRest('req.post.getDestinationNoteList.render', function () {

			return $this->renderJSON($this->getDestinationNoteList());
		});

		/*
		 *  new service : addDestinationNoteList	(method : POST)
		 */
		$this->onRest('req.post.addDestinationNoteList.render', function () {

			return $this->renderJSON($this->addDestinationNoteList());
		});

		/*
		 *  new service : showDestinationArea	(method : POST)
		 */
		$this->onRest('req.get.showDestinationArea.render', function () {

			return $this->renderJSON($this->showDestinationArea());
		});

		/*
		 *  new service : showVendorBid rank	(method : Get)
		 */
		$this->onRest('req.post.showBidRank.render', function () {

			return $this->renderJSON($this->showBidRank());
		});

		$this->onRest('req.post.vendor_cust_request.render', function () {

			Logger::create('20 vendor_cust_p ', CLogger::LEVEL_TRACE);
			$token = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);

			$vendorId			 = UserInfo::getEntityId();
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$status				 = $data1['status'];
			$time				 = $data1['time'];
			$filterFlag			 = CJSON::decode($data1['filters'], true);
			Logger::create('REQUEST;;==>' . CJSON::encode($data1));
			/** $filterFlag 1=>Upcoming Trips,2=> Trips Started,3=> Completed Trips, 4=>CNG allowed */
			$vendorModel		 = Booking::model()->getcustDetails($vendorId, $status, $time, $filterFlag);

			if($vendorModel != [])
			{
				$success = true;
				$error	 = null;
			}
			else
			{
				$success = false;
				$error	 = "No records found";
			}
			Logger::create('CUST DETAILS success' . $success . $status . $time . '===========>: \n\t\t' . json_encode($vendorModel));

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'error'		 => $error,
					'data'		 => array_filter($vendorModel),
				)
			]);
		});
		/**
		 * @deprecated from 11-07-2022
		 * * author Madhumita
		 * New function tripDetailsV1
		 * @return $this
		 */
		$this->onRest('req.post.trip_details.render', function () {

			$returnSet	 = new ReturnSet();
			$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result		 = Vendors::model()->authoriseVendor($token);
			if($result)
			{
				$processSyncData = Yii::app()->request->getParam('data');
				Logger::trace("<===Requset===>" . $processSyncData);
				$data			 = CJSON::decode($processSyncData, true);
				$tripId			 = $data['trip_id'];
				$status			 = $data['status'];
				$vendorId		 = Yii::app()->user->getEntityId();
				$model			 = Booking::getTripDetails1($tripId, $status, $vendorId, 1);

				if($model != [])
				{
					$userInfo->userType = UserInfo::getUserType(); //Vendor type = 2

					if($model[0]['is_biddable'] == 1)
					{
						$statModel			 = VendorStats::model()->getbyVendorId($vendorId);
						$dependency			 = $statModel->vrs_dependency;
						$calculateDependency = ($dependency == '' ? 0 : $dependency);
						if($calculateDependency < 0)
						{
							$model[0]['dependency_msg'] = "Your dependability score is very low. If you deny this booking after you direct accept, you will be penalized.Partners with high dependability can direct accept without risk of denial penalty.";
						}
					}
					$bkg_id		 = $model[0][bkg_id];
					$noteArrList = DestinationNote::model()->showNoteApi($bkg_id, $userInfo->userType);
					$countNote	 = count($noteArrList);
					if($countNote > 0)
					{
						$model[0]['isDestinationNote'] = 1;
					}
					else
					{
						$model[0]['isDestinationNote'] = 0;
					}
					if($model[0]['vht_make'] != '' || $model[0]['vht_make'] != NULL)
					{
						$vhtTypeModel = '-' . $model[0]['vht_make'] . ' ' . $model[0]['vht_model'];
					}
					$model[0]['cab_model'] = $model[0]['cab_model'] . ' (' . $model[0]['cab_lavel'] . $vhtTypeModel . ')';
					if($model[0]['cab_lavel'] == 'Select' || $model[0]['cab_lavel'] == 'Select Plus')
					{
						$model[0]['cab_model']	 = $model[0]['vht_model'];
						$model[0]['cab_lavel']	 = 'Select';
					}
					if($model[0]['scc_id'] == 2)
					{
						$model[0]['is_cng_allowed'] = '2';
					}
					if($model[0]['bkg_status'] > 5)
					{
						$model[0]['bkg_user_name']			 = null;
						$model[0]['bkg_contact_no']			 = null;
						$model[0]['bkg_user_fname']			 = null;
						$model[0]['bkg_user_lname']			 = null;
						$model[0]['bkg_contact_no']			 = null;
						$model[0]['bkg_alternate_contact']	 = null;
						$model[0]['bkg_user_email']			 = null;
					}

					$returnSet->setStatus(true);
					$returnSet->setData($model, false);
				}
				else
				{
					$returnSet->setErrors("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
				}
			}
			else
			{
				$returnSet->setErrors("Unauthorised Vendor", ReturnSet::ERROR_UNAUTHORISED_VENDOR);
			}
			Logger::trace("<===Response===>" . json_encode($returnSet));
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $returnSet->getStatus(),
					'error'		 => $returnSet->getErrors(),
					'data'		 => $returnSet->getData(),
				)
			]);
		});

		/**
		 * New function for Trip details 
		 * for temporary use need to modify through stub
		 * Create Date 11-07-2022
		 * 
		 */
		$this->onRest('req.post.tripDetailsV1.render', function () {

			$returnSet	 = new ReturnSet();
			$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result		 = Vendors::model()->authoriseVendor($token);
			$data		 = null;
			try
			{
				if(!$result)
				{
					$errorMsg = "Unauthorised Vendor";
					throw new Exception($errorMsg, ReturnSet::ERROR_UNAUTHORISED);
				}
				$data1 = Yii::app()->request->getParam('data');
				if($data1 == "")
				{
					$errorMsg = "Invalid Request";
					throw new Exception($errorMsg, ReturnSet::ERROR_INVALID_DATA);
				}

				$jsonMapper	 = new JsonMapper();
				$jsonObj	 = CJSON::decode($data1, false);
				$tripId		 = $jsonObj->trip_id;
				$status		 = $jsonObj->status;
				if(!$tripId)
				{
					$errorMsg = "Trip id not found";
					throw new Exception($errorMsg, ReturnSet::ERROR_INVALID_DATA);
				}
				$dependency_msg	 = "";
				$vendorId		 = UserInfo::getEntityId();
				$model			 = Booking::getTripDetails1($tripId, $status, $vendorId, 1);
				if(!$model)
				{
					$errorMsg = "Trip detail not found";
					throw new Exception($errorMsg, ReturnSet::ERROR_NO_RECORDS_FOUND);
				}
				$bookingList		 = $model[0]->bkg_id;
				$directAcptAmount	 = BookingVendorRequest::getDirectAcceptAmount($vendorId, $tripId);
				$statModel			 = VendorStats::model()->getbyVendorId($vendorId);
				$dependency			 = $statModel->vrs_dependency;

				if($model != [])
				{
					if($model[0]['bkg_booking_type'] == 4 || $model[0]['bkg_booking_type'] == 12)
					{
						if($dependency < 60)
						{
							$model[0]['is_biddable'] = (int) 1;
						}
					}
					if($model[0]['is_biddable'] == 0)
					{
						$recommended_vendor_amount = ( $directAcptAmount > 0 ? $directAcptAmount : $model[0]['recommended_vendor_amount']);
					}
					else
					{
						$recommended_vendor_amount = $model[0]['recommended_vendor_amount'];
					}

					$getBidRange = BookingVendorRequest::getBidRange($recommended_vendor_amount, $model[0]['maxAllowableVendorAmount']);
					$returnSet->setStatus(true);
					$userType	 = UserInfo::getUserType(); //Vendor type = 2
					$bkg_id		 = $model[0]['bkg_id'];
					$noteArrList = DestinationNote::model()->showNoteApi($bkg_id, $userType);
					$countNote	 = count($noteArrList);
					if($countNote > 0)
					{
						$model[0]['isDestinationNote'] = 1;
					}
					else
					{
						$model[0]['isDestinationNote'] = 0;
					}
					if($model[0]['bkg_booking_type'] == 2 || $model[0]['bkg_booking_type'] == 3)
					{
						$bidAlertMsg = "Customers have the freedom to enhance their journey by modifying their route or including new locations, cities or sightseeing spots or local attractions during the ride within the designated timeframe. Customers will not be charged extra for any travel within the quoted distance. If the total distance exceed the initial quoted distance, an extra km charge will be applied.";
					}
					if($model[0]['is_biddable'] == 0)
					{
						$calculateDependency = ($dependency == '' ? 0 : $dependency);
						if($calculateDependency < 0)
						{
							$dependency_msg = "Your dependability score is very low. If you deny this booking after you direct accept, penalty will apply. Only Partners with high dependability can direct accept without risk of denial penalty.";
						}
					}

					$model[0]['cab_model']		 = $model[0]['cab_label'];
					$model[0]['cab_type_tier']	 = $model[0]['cab_label_with_class'];

					if($model[0]['scc_id'] == 2)
					{
						$model[0]['is_cng_allowed'] = '2';
					}
					$returnSet->setStatus(true);
				}

				$data = ['tripId'					 => $model[0]['bcb_id'],
					'routeName'					 => $model[0]['bkg_route_city_names'],
					'isBiddable'				 => $model[0]['is_biddable'],
					'dependency_msg'			 => $dependency_msg,
					'maxBidAmount'				 => $model[0]['max_bid_amount'],
					'recommendedVendorAmount'	 => $recommended_vendor_amount,
					'minBidAmount'				 => $model[0]['min_bid_amount'],
					'bidAlertMsg'				 => $bidAlertMsg,
					'bvrBidAmount'				 => ($model[0]['bvr_bid_amount'] == null ? 0 : $model[0]['bvr_bid_amount']),
					'paymentDue'				 => $model[0]['payment_due'],
					'paymentMsg'				 => $model[0]['payment_msg'],
					'isAssured'					 => $model[0]['is_assured'],
					'finalTripType'				 => $model[0]['bkg_booking_type'],
					'isFlexxi'					 => $model[0]['isFlexxi'],
					'vendorAmount'				 => $model[0]['vendor_ammount'],
					'acceptAmount'				 => $directAcptAmount,
					'isCngAllowed'				 => $model[0]['is_cng_allowed'],
					'bidRange'					 => $getBidRange,
					'cancelSlabs'				 => $model[0]['cancelSlabs'],
					'pickupTime'				 => $model[0]['bkg_pickup_date'],
					'tripCompletionTime'		 => $model[0]['trip_completion_time'],
					'bookingList'				 => $model
				];
			}
			catch(Exception $ex)
			{
				$returnSet = ReturnSet::setException($ex);
			}

			$response = ['success'	 => $returnSet->getStatus(),
				'error'		 => $errorMsg,
				'data'		 => $data];
			return CJSON::encode($response);
		});

		$this->onRest('req.post.gnowTripDetails.render', function () {
			return $this->renderJSON($this->gnowTripDetails());
		});

		$this->onRest('req.post.gnowAllocatedBidDetails.render', function () {
			return $this->renderJSON($this->gnowAllocatedBidDetails());
		});

		$this->onRest('req.post.gnowSnoozeNotification.render', function () {
			return $this->renderJSON($this->gnowSnoozeNotification());
		});
		$this->onRest('req.get.gnowDenyReasons.render', function () {
			return $this->renderJSON($this->gnowDenyReasons());
		});

		$this->onRest('req.post.trip_details_calculation.render', function () {
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if($result)
			{
				$processSyncData		 = Yii::app()->request->getParam('data');
				$data					 = CJSON::decode($processSyncData, true);
				$vendorId				 = Yii::app()->user->getEntityId();
				$bkg_id					 = $data['bkg_id'];
				$bcb_id					 = $data['bcb_id'];
				$extra_toll_tax			 = $data['bkg_extra_toll_tax'];
				$extra_state_tax		 = $data['bkg_extra_state_tax'];
				$parking_charge			 = $data['bkg_parking_charge'];
				$extra_total_km			 = $data['bkg_extra_km'];
				$bkgExtraMin			 = $data['bkg_extra_min'];
				$bkgExtraMinCharges		 = $data['bkg_extra_per_min_charge'];
				$rate_per_km_extra		 = "";
				$service_tax_rate		 = "";
				$due_amount				 = 0;
				$is_duty_slip_required	 = 0;
				$model					 = Booking::model()->findByPk($bkg_id);
				if($model != NULL)
				{
					$rate_per_km_extra		 = $model->bkgInvoice->bkg_rate_per_km_extra;
					$service_tax_rate		 = $model->bkgInvoice->bkg_service_tax_rate;
					$extra_toll_tax			 = $extra_toll_tax + ($extra_toll_tax * $model->bkgInvoice->bkg_service_tax_rate) / 100;
					$extra_state_tax		 = $extra_state_tax + ($extra_state_tax * $model->bkgInvoice->bkg_service_tax_rate) / 100;
					$parking_charge			 = $parking_charge + ($parking_charge * $model->bkgInvoice->bkg_service_tax_rate) / 100;
					$extra_km_charge		 = $extra_total_km * $rate_per_km_extra;
					$extra_km_charge		 = $extra_km_charge + ($extra_km_charge * $model->bkgInvoice->bkg_service_tax_rate) / 100;
					$extra_charge			 = ($extra_toll_tax + $extra_state_tax + $parking_charge + $extra_km_charge );
					$due_amount				 = $model->bkgInvoice->bkg_due_amount + $extra_charge;
					$is_duty_slip_required	 = $model->bkgPref->bkg_duty_slip_required;
					$bkgTotalExtraMinCharge	 = ($bkgExtraMin > 0 && $bkgExtraMinCharges > 0) ? round($bkgExtraMin * $bkgExtraMinCharges) : 0;
					$success				 = true;
					$error					 = null;
				}
				else
				{
					$success = false;
					$error	 = "No records found";
				}
			}
			else
			{
				$success = false;
				$error	 = "Unauthorised Vendor";
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'					 => $success,
					'error'						 => $error,
					'bkg_id'					 => $bkg_id,
					'bcb_id'					 => $bcb_id,
					'bkg_extra_km'				 => $extra_total_km,
					'bkg_rate_per_km_extra'		 => $rate_per_km_extra,
					'bkg_service_tax_rate'		 => $service_tax_rate,
					'bkg_extra_toll_tax'		 => $extra_toll_tax,
					'bkg_extra_state_tax'		 => $extra_state_tax,
					'bkg_parking_charge'		 => $parking_charge,
					'bkg_extra_km_charge'		 => $extra_km_charge,
					'bkg_due_amount'			 => floor($due_amount),
					'duty_slip_required'		 => $is_duty_slip_required,
					'bkg_extra_min'				 => $bkgExtraMin,
					'bkg_extra_total_min_charge' => $bkgTotalExtraMinCharge
				)
			]);
		});

		$this->onRest('req.get.vendor_version_check.render', function () {

			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if($result == true)
			{
				$vendorId		 = UserInfo::getEntityId();
				$versionCheck	 = VendorAgreement::model()->checkVersionStatusByVndId($vendorId);
				if($versionCheck > 0)
				{
					$success = true;
					$error	 = [];
				}
				else
				{
					$success = false;
					$error	 = 'Version not matched';
				}
			}
			else
			{
				$success = false;
				$error	 = 'Unauthorised Vendor';
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'error'		 => $error
				)
			]);
		});
		/* @deprecated
		 * 
		 * new version vendorPendingRequest
		 */

		$this->onRest('req.post.vendor_pending_request.render', function () {

			Logger::create('19 vendor_pending_request ', CLogger::LEVEL_TRACE);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);

			if($result == true)
			{
				try
				{
					$processSyncdata = Yii::app()->request->getParam('data');
					Logger::create('vendor pending request=====>' . $processSyncdata, CLogger::LEVEL_TRACE);
					$data			 = CJSON::decode($processSyncdata, true);

					#echo $data;
					$sort			 = $data['sort'];
					$page_no		 = (int) $data['page_no'];
					$search_txt		 = trim($data['search_txt']);
					$page_number	 = ($page_no > 0) ? $page_no : 0;
					$filter			 = (!(isset($data['filter'])) || $data['filter'] == '') ? null : $data['filter'];
					//$vendorId		 = Yii::app()->user->getId();
					$vendorId		 = UserInfo::getEntityId();
					$versionCheck	 = VendorAgreement::model()->checkVersionStatusByVndId($vendorId);
					$success		 = false;
					$total_count	 = 0;
					if($versionCheck > 0)
					{
						$vendorModel = BookingVendorRequest::model()->getRequestedListNew1($vendorId, $sort, $page_number, $total_count, $search_txt, $filter);
						$count		 = count(BookingVendorRequest::model()->getRequestedListNew1($vendorId, $sort, -1, $total_count, $search_txt, $filter));
						//print_r();exit;
						//$vendorModel[0]['cab_lavel']= 'Select';
						//$count = count($vendorModel);
						if($count != 0)
						{
							$pageCount = ceil($count / 30);
						}
						if($vendorModel != [])
						{
							$success = true;
							$error	 = null;
						}
						else
						{
							$error = "No records found";
						}
					}
					else
					{
						$error = 'Version not matched';
					}
				}
				catch(Exception $e)
				{
					Logger::create("vendor_pending_request error occurred: " . $e->getMessage());
					$error	 = "Something went wrong";
					$success = false;
				}
			}
			else
			{
				$success = true;
				$error	 = 'Vendor Unauthorised';
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'		 => $success,
					'error'			 => $error,
					'data'			 => $vendorModel,
					'versionCheck'	 => $versionCheck,
					'count'			 => $count,
					'total_pages'	 => $pageCount
				)
			]);
		});

		/*
		 * @deprecated vendor_account_trip 
		 * 
		 * new services vendorAccountTrip
		 */
		$this->onRest('req.post.vendor_account.render', function () {

			Logger::create('28 vendor_account ', CLogger::LEVEL_TRACE);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if($result == true)
			{
				$vendorId			 = UserInfo::getEntityId();
				//$date1			 = Yii::app()->request->getParam('date1');
				//$date2			 = Yii::app()->request->getParam('date2');
				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				$date1				 = $data1['date1'];
				$date2				 = $data1['date2'];

				$newDate1		 = ($date1 != '') ? date('Y-m-d', strtotime($date1)) : date('Y-m-d', strtotime("-30 days"));
				$newDate2		 = ($date2 != '') ? date('Y-m-d', strtotime($date2)) : date('Y-m-d');
				$transLists		 = AccountTransDetails::vendorTransactionList($vendorId, $newDate1, $newDate2, '1');
				//            for ($i = 0; $i < count($transLists); $i++)
				//           {
				//               if ($transLists[$i]['adt_remarks'] == '')
				//                {
				//                    $transLists[$i]['adt_remarks'] = ($transLists[$i]['bkg_booking_id'] == NULL) ? 'NA' : $transLists[$i]['bkg_booking_id'] . " " . $transLists[$i]['from_city'];
				//               }
				//           }
				$vendorAmount	 = AccountTransDetails::model()->calAmountByVendorId($vendorId);
				//$vendorAmount['vendor_amount'] = 10000;
				//$vendorAmount['vendor_amount_type'] = 'Payable';
				$success		 = true;
			}
			else
			{
				$success = false;
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'		 => $success,
					'data'			 => $transLists,
					'vendorAmount'	 => $vendorAmount,
				)
			]);
		});
		/*
		 * @deprecated vendor_account_trip 
		 * 
		 * new services vendorAccountTrip
		 */

		$this->onRest('req.post.vendor_account_trip.render', function () {
			Logger::create('29 vendor_account_trip ', CLogger::LEVEL_TRACE);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if(!$result)
			{
				throw new Exception("Vendor Unauthorised.", ReturnSet::ERROR_UNAUTHORISED);
			}
			if($result == true)
			{
				$vendorId = UserInfo::getEntityId();
				//$date1		 = Yii::app()->request->getParam('date1');
				//$date2		 = Yii::app()->request->getParam('date2');
				//$flag		 = Yii::app()->request->getParam('viewFlag');			


				$process_sync_data = Yii::app()->request->getParam('data');
				Logger::create("Request =>" . $process_sync_data, CLogger::LEVEL_TRACE);

				$data1	 = CJSON::decode($process_sync_data, true);
				$date1	 = $data1['date1'];
				$date2	 = $data1['date2'];
				$flag	 = $data1['viewFlag'];
				$tripId	 = $data1['tripId'];

				$newDate1	 = ($date1 != '') ? date('Y-m-d', strtotime($date1)) : date('Y-m-d', strtotime("-30 days"));
				$newDate2	 = ($date2 != '') ? date('Y-m-d', strtotime($date2)) : date('Y-m-d');

				if($flag == 2)
				{
					/** @var CDbDataReader $resultset */
					$resultset	 = AccountTransDetails::vendorTransactionList1($vendorId, $newDate1, $newDate2, $tripId);
					$tripArr	 = $resultset->readAll();
				}
				else if($flag == 3)
				{
					$tripArr = AccountTransDetails::vendorTransactionList($vendorId, $newDate1, $newDate2, '1', '', 37);
				}
				else
				{
					$tripArr = AccountTransDetails::vendorTransactionList($vendorId, $newDate1, $newDate2, '1');
				}

				$vendorAmount						 = AccountTransDetails::model()->calAmountByVendorId($vendorId);
				$vendorAmount['vnd_security_amount'] = AccountTransDetails::model()->calAmntByVendorReffBoth($vendorId);
				$tdsAmount							 = AccountTransDetails::model()->calTdsByVendorId($vendorId);
				$success							 = true;
			}
			else
			{
				$success = false;
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'		 => $success,
					'data'			 => $tripArr,
					'vendorAmount'	 => $vendorAmount,
					'tdsAmount'		 => $tdsAmount
				)
			]);
		});
		/* @deprecated function
		 * New function request_status_change1
		 * 
		 */
		$this->onRest('req.post.request_status_change.render', function () {

			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);

			if($result == true)
			{
				$vendorId	 = UserInfo::getEntityId();
				$success	 = false;
				$error		 = 'Something went wrong';
				//$accept_bkg_id	 = Yii::app()->request->getParam('accept_bkg_id');
				//$deny_bkg_id	 = Yii::app()->request->getParam('deny_bkg_id');

				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				Logger::trace('<===Request===>' . $process_sync_data);
				$accept_bkg_id		 = $data1['accept_bkg_id'];
				$deny_bkg_id		 = $data1['deny_bkg_id'];

				if($accept_bkg_id > 0)
				{
					ReturnSet::setException(new Exception("Deprecated request_status_change used: \n {$process_sync_data}", ReturnSet::ERROR_INVALID_DATA));

					$model = Booking::model()->findByPk($accept_bkg_id);
					if($model != '')
					{
						if($model->bkg_status == 2)
						{
							$type	 = 'vendor';
							$bkid	 = Booking::model()->assignVendor($accept_bkg_id, $vendorId, '', $type);
							if($bkid)
							{
								$bvr_model = BookingVendorRequest::model()->findByBookingIdAndVendorId($accept_bkg_id, $vendorId);
								if(count($bvr_model) == 1)
								{
									$bvr_model->bvr_assigned	 = 1;
									$bvr_model->bvr_assigned_at	 = new CDbExpression('NOW()');
									$bvr_model->bvr_active		 = 0;
									$row						 = BookingVendorRequest::model()->updateListByBooking($accept_bkg_id);
									if($bvr_model->save() == true && $row > 0)
									{
										$success = true;
										$error	 = '';
									}
								}
							}
						}
						else
						{
							if($model->bkg_status != 2)
							{
								$model->addError('bkg_id', "Oops! The booking has been taken by another service provider. Please try to be quick next time");
								$error = $model->getErrors();
							}
						}
					}
				}
				if($deny_bkg_id > 0)
				{
					$bvr_model = BookingVendorRequest::model()->findByBookingIdAndVendorId($deny_bkg_id, $vendorId);
					if(count($bvr_model) == 1)
					{
						$bvr_model->bvr_accepted	 = 2;
						$bvr_model->bvr_assigned	 = 2;
						$bvr_model->bvr_assigned_at	 = new CDbExpression('NOW()');
						$bvr_model->bvr_accepted_at	 = new CDbExpression('NOW()');
						if($bvr_model->save())
						{
							$success = true;
							$error	 = '';
						}
					}
				}
			}
			else
			{
				$success = false;
				$error	 = "Unauthorised Vendor";
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $error,
				)
			]);
		});
		/**
		 * @deprecated function
		 * new function created at 07-06-2023
		 * new function acceptBooking
		 */
		$this->onRest('req.post.request_status_change1.render', function () {

			$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result		 = Vendors::model()->authoriseVendor($token);
			//$spicejetId = 30239;// For local
			$spicejetId	 = 34928; //For production
			$returnSet	 = new ReturnSet();
			if($result == true)
			{
				$success			 = false;
				$error				 = '';
				$processSyncData	 = Yii::app()->request->getParam('data');
				$data				 = CJSON::decode($processSyncData, true);
				Logger::trace('<===Request===>' . $processSyncData);
				$accept_bcb_id		 = $data['accept_bcb_id'];
				$deny_bcb_id		 = $data['deny_bcb_id'];
				$accptVendorAmount	 = ceil($data['accept_amount']);

				$vendorId	 = UserInfo::getEntityId();
				$userInfo	 = UserInfo::getInstance();
				$tripId		 = '';
				if($accept_bcb_id > 0)
				{
					$tripId		 = $accept_bcb_id;
					$trasaction	 = DBUtil::beginTransaction();
					try
					{
						/** @var BookingCab $cabModel */
						if($accptVendorAmount == '' || $accptVendorAmount == 0)
						{
							$error = "Please check your app version. Cannot accept this request";
							throw new Exception($error, ReturnSet::ERROR_INVALID_DATA);
						}
						$cabModel	 = BookingCab::model()->findByPk($accept_bcb_id);
						$bModels	 = $cabModel->bookings;

						if(COUNT($bModels) == 0)
						{
							$bModels			 = BookingSmartmatch::model()->getBookings($accept_bcb_id);
							$cabModel->bookings	 = $bModels;
						}

						$vendorModel	 = Vendors::model()->findByPk($vendorId);
						$securityAmount	 = $vendorModel->vendorStats->vrs_security_amount;
						$codFreeze		 = $vendorModel->vendorPrefs->vnp_cod_freeze;
						$dependencyScore = $vendorModel->vendorStats->vrs_dependency;
						foreach($bModels as $bModel)
						{
							/* if ($bModel->bkg_agent_id == $spicejetId && $bModel->bkg_reconfirm_flag <> 1)
							  {
							  $bModel->addError('bkg_id', "Sorry! This booking is not confirmed yet");
							  $error = $bModel->getErrors();
							  throw new Exception(json_encode($error), 1);
							  } */
//							if ($securityAmount < 0 && $codFreeze == 1)
//							{
//								$bModel->addError('bkg_id', "Your security amount is low and Your Gozo Account is freezed. You do not have permission to serve that booking.");
//								$error = $bModel->getErrors();
//								throw new Exception(json_encode($error), 1);
//							}
//							if ($securityAmount > 0 && $codFreeze == 1 && $bModel->bkgInvoice->bkg_corporate_remunerator != 2)
//							{
//
//								$bModel->addError('bkg_id', "Your Gozo Account is freezed.You do not have permission to serve that booking.");
//								$error = $bModel->getErrors();
//								throw new Exception(json_encode($error), 1);
//							}
							if($vendorModel->vendorPrefs->vnp_low_rating_freeze == 1 || $vendorModel->vendorPrefs->vnp_doc_pending_freeze == 1 || $vendorModel->vendorPrefs->vnp_manual_freeze == 1)
							{
								$bModel->addError("bkg_id", "Your account is freezed. Cannot doing direct accept");
								$error = $bModel->getErrors();
								throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
							}
							if($bModel->bkg_reconfirm_flag != 1)
							{
								$bModel->addError('bkg_id', "Sorry! This booking is not confirmed yet");
								$error	 = $bModel->getErrors();
								$errorId = 1;
								throw new Exception(json_encode($error), 1);
							}
							$bookingType = $bModel->bkg_booking_type;
							$dataCount	 = VendorPref::checkApprovedService($vendorId, $bookingType);
							if($dataCount < 1)
							{
								if($bookingType == 4 || $bookingType == 12)
								{
									if($dependencyScore >= 60)
									{
										goto skip;
									}
									else
									{
										$bModel->addError("bkg_id", "You do not have permission to accept this booking due to low dependency score.");
										$error	 = $bModel->getErrors();
										$errorId = 2;
										throw new Exception(json_encode($error), 1);
									}
								}
								else
								{
									$errorId = 3;
									$bModel->addError("bkg_id", "You do not have permission to serve this booking.");
									$error	 = $bModel->getErrors();
									throw new Exception(json_encode($error), 1);
								}
							}

							skip:
							$isVendorUnassigned = BookingLog::isVendorUnAssigned($vendorId, $bModel->bkg_id);
							if($isVendorUnassigned)
							{
								$errorId = 4;
								$bModel->addError("bkg_id", "You already denied this booking. Cannot bid on it again.");
								$error	 = $bModel->getErrors();
								throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
							}

							if($bModel->bkg_status != 2)
							{
								$errorId = 5;
								$bModel->addError('bkg_id', "Oops! This booking is already assigned.");
								$error	 = $bModel->getErrors();
								throw new Exception(json_encode($error), 1);
								//break;
							}
							if($bModel->bkgPref->bkg_block_autoassignment == 1)
							{
								$errorId = 6;
								$bModel->addError('bkg_id', "Oops! This booking cannot be directly accepted .");
								$error	 = $bModel->getErrors();
								throw new Exception(json_encode($error), 1);
							}

							/* if (!Vehicles::checkVehicleAvailability($vendorId, $cabModel->bcb_start_time, $cabModel->bcb_end_time, $bModel->bkg_from_city_id, $bModel->bkg_to_city_id, $bModel->bkgSvcClassVhcCat->scv_vct_id))
							  {
							  $bModel->addError('bkg_id', "Oops! You have no cab for this booking");
							  $error = $bModel->getErrors();
							  throw new Exception(json_encode($error), 1);
							  } */
							if(!Drivers::checkDriverAvailability($vendorId, $cabModel->bcb_start_time, $cabModel->bcb_end_time))
							{
								$errorId = 7;
								$bModel->addError('bkg_id', "Oops! You have no driver for this booking");
								$error	 = $bModel->getErrors();
								throw new Exception(json_encode($error), 1);
							}
							$booking_class = $bModel->bkgSvcClassVhcCat->scc_ServiceClass->scc_id;
							if(!Vehicles::checkVehicleclass($vendorId, $booking_class))
							{
								$errorId = 8;
								$bModel->addError('bkg_id', "Oops! You have no cab in same class of this booking");
								$error	 = $bModel->getErrors();
								throw new Exception(json_encode($error), 1);
							}
							$chkOutStanding	 = VendorStats::frozenOutstanding($vendorId);
							$gozoNeedToPay	 = BookingInvoice::getVendorReceivable($accept_bcb_id, $accptVendorAmount);

							if(($chkOutStanding > 500 && $gozoNeedToPay < 500) || $chkOutStanding > 3000)
							{
								$errorId = 9;
								$bModel->addError('bkg_id', "Oops! Your payment is overdue. Please settle your Gozo accounts ASAP.");
								$error	 = $bModel->getErrors();
								throw new Exception(json_encode($error), 1);
							}

							$criticalityScore	 = $bModel->bkgPref->bkg_critical_score;
							$dependencyStatus	 = VendorStats::checkDependency($criticalityScore, $vendorId);
							if(!$dependencyStatus)
							{
								$errorId = 10;
								$bModel->addError('bkg_id', "Dependability score low. Direct accept not available for you. To improve dependability score, do not refuse booking after you accept.");
								$error	 = $bModel->getErrors();
								throw new Exception(json_encode($error), 1);
							}
						}
						$status = BookingVendorRequest::DirectAccept($accptVendorAmount, $vendorId, $accept_bcb_id, $userInfo);
						if($status == true)
						{
							DBUtil::commitTransaction($trasaction);
							$success = true;
						}
						if($return["errors"] != "")
						{
							$success = false;
							$error	 = $return["errors"]->bcb_vendor_id[0];
							throw new Exception($error, ReturnSet::ERROR_INVALID_DATA);
						}
						$returnSet->setStatus(true);
					}
					catch(Exception $e)
					{
						DBUtil::rollbackTransaction($trasaction);
						$returnSet = ReturnSet::setException($e);
					}
				}

				if($deny_bcb_id > 0)
				{
					$tripId		 = $deny_bcb_id;
					$success	 = false;
					$bvrModels	 = BookingVendorRequest::model()->findByBcbIdAndVendorId($deny_bcb_id, $vendorId);
					if(count($bvrModels) >= 1)
					{
						foreach($bvrModels as $bvrModel)
						{
							$bvrModel->bvr_bid_amount	 = 0;
							$bvrModel->bvr_accepted		 = 2;
							$bvrModel->bvr_assigned		 = 2;
							$bvrModel->bvr_assigned_at	 = new CDbExpression('NOW()');
							$bvrModel->bvr_accepted_at	 = new CDbExpression('NOW()');
							$success					 = $bvrModel->save();
							if(!$success)
							{
								$error = $bvrModel->getErrors();
								break;
							}
						}
					}
					else
					{
						$success = BookingVendorRequest::model()->createRequest(0, $deny_bcb_id, $vendorId, 'deny');
						if(!$success)
						{
							$error = 'Bid deny failed';
						}
					}
					if($success)
					{
						$bcabModel	 = BookingCab::model()->findByPk($deny_bcb_id);
						$bModels	 = $bcabModel->bookings;
						$eventId	 = BookingLog::BID_DENY;
						$desc		 = "Bid denied by vendor.";
						foreach($bModels as $bookingModel)
						{
							BookingLog::model()->createLog($bookingModel->bkg_id, $desc, $userInfo, $eventId);
						}
					}
				}
			}
			else
			{
				$success = false;
				$error	 = 'Unauthorised vendor';
			}
			Logger::create('request_status_change1 result data: ' . $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => array(
							'success'	 => $success,
							'errors'	 => $error,
						)
			]));
			if(!$returnSet->getStatus() && $tripId > 0 && $returnSet->hasErrors())
			{
				$errors = $returnSet->getErrors();

				$errorDesc	 = implode('; ', $errors);
				$errorDesc	 .= implode('; ', $errors);
				$bcabModel	 = BookingCab::model()->findByPk($tripId);
				$bcabModel->logFailedVendorAssignment($errorDesc, $userInfo, $vendorId);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errorDesc,
				)
			]);
		});
		/**
		 * new function acceptBooking
		 * old one request_status_change1
		 */
		$this->onRest('req.post.acceptBooking.render', function () {

			$token				 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result				 = Vendors::model()->authoriseVendor($token);
			//$spicejetId = 30239;// For local
			$spicejetId			 = 34928; //For production
			$securityAmountFlag	 = 0;
			$returnSet			 = new ReturnSet();
			$lAmount			 = 0;

			if($result == true)
			{
				$success			 = false;
				$error				 = '';
				$processSyncData	 = Yii::app()->request->getParam('data');
				$data				 = CJSON::decode($processSyncData, true);
				Logger::trace('<===Request===>' . $processSyncData);
				$accept_bcb_id		 = $data['accept_bcb_id'];
				$deny_bcb_id		 = $data['deny_bcb_id'];
				$accptVendorAmount	 = ceil($data['accept_amount']);
				$forceFlag			 = $data['force_flag'];

				$vendorId	 = UserInfo::getEntityId();
				$userInfo	 = UserInfo::getInstance();
				$tripId		 = '';
				if($accept_bcb_id > 0)
				{
					$tripId = $accept_bcb_id;

					try
					{
						/** @var BookingCab $cabModel */
						if($accptVendorAmount == '' || $accptVendorAmount == 0)
						{
							$error = "Please check your app version. Cannot accept this request";
							throw new Exception($error, ReturnSet::ERROR_INVALID_DATA);
						}
						$cabModel	 = BookingCab::model()->findByPk($accept_bcb_id);
						$bModels	 = $cabModel->bookings;

						if(COUNT($bModels) == 0)
						{
							$bModels			 = BookingSmartmatch::model()->getBookings($accept_bcb_id);
							$cabModel->bookings	 = $bModels;
						}

						if($bModels[0]->bkg_bcb_id != $tripId)
						{
							$error = "Sorry! This trip no longer exists. Please refresh your screen.";
							throw new Exception(json_encode($error), ReturnSet::ERROR_INVALID_DATA);
						}
						$vendorModel	 = Vendors::model()->findByPk($vendorId);
						$dependencyScore = $vendorModel->vendorStats->vrs_dependency;
						$vnpCodFreeze	 = $vendorModel->vendorPrefs->vnp_cod_freeze;
						foreach($bModels as $bModel)
						{
							//cashbooking validation 

							$cashBkgValidation = BookingInvoice::checkCODBkg($bModel->bkg_id, $vnpCodFreeze);

							if($cashBkgValidation == false)
							{

								$error = "Sorry! You do not have permission to accept cash booking.";
								throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
							}

							$calculateLockedAmount = BookingInvoice::calculateLockAmount($bModel->bkg_id, $accptVendorAmount, $vendorId);

							if($bModel->bkg_reconfirm_flag <> 1)
							{

								$error = "Sorry! This booking is not confirmed yet.";
								throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
							}
							$bookingType = $bModel->bkg_booking_type;
							$dataCount	 = VendorPref::checkApprovedService($vendorId, $bookingType);

							if($dataCount < 1)
							{
								if($bookingType == 4 || $bookingType == 12)
								{
									if($dependencyScore >= 60)
									{
										goto skip;
									}
									else
									{

										$error = "You do not have permission to serve this booking.";
										throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
									}
								}
								else
								{

									$error = "You do not have permission to serve this booking";
									throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
								}
							}
							skip:
							$isApproveCar		 = $isApproveDriver	 = $isDocApprove		 = $isApproveBooking	 = false;
							if(($vendorModel->vnd_active == 1) || ($vendorModel->vendorPrefs->vnp_is_orientation > 0))
							{
								$isDocApprove = true;
							}
							$isApproveCar	 = ($vendorModel->vendorStats->vrs_approve_car_count > 0) ? true : false;
							$isApproveDriver = ($vendorModel->vendorStats->vrs_approve_driver_count > 0) ? true : false;

							if($isDocApprove == false)
							{
								$error = "Check documents. Your documents are missing or not yet approved.";
								throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
							}

							if($isApproveCar == false)
							{
								$error = "Get 1 car approved before we can send you business.";
								throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
							}

							if($isApproveDriver == false)
							{
								$error = "Get 1 driver approved before we can send you business.";
								throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
							}

							$isVendorUnassigned = BookingLog::isVendorUnAssigned($vendorId, $bModel->bkg_id);

							if($isVendorUnassigned)
							{

								$error = "You already denied this booking. Cannot bid on it again.";
								throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
							}
							if($vendorModel->vendorPrefs->vnp_low_rating_freeze == 1 || $vendorModel->vendorPrefs->vnp_doc_pending_freeze == 1 || $vendorModel->vendorPrefs->vnp_manual_freeze == 1)
							{

								$error = "Your account is freezed. Cannot doing direct accept.";
								throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
							}


							if($bModel->bkg_status != 2)
							{

								$error = "Oops! This booking is already assigned.";
								throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
								//break;
							}
							if($bModel->bkgPref->bkg_block_autoassignment == 1)
							{

								$error = "Oops! This booking cannot be directly accepted ..";
								throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
							}
							if(!Drivers::checkDriverAvailability($vendorId, $cabModel->bcb_start_time, $cabModel->bcb_end_time))
							{

								$error = "Oops! You have no driver for this booking";
								throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
							}
							$booking_class = $bModel->bkgSvcClassVhcCat->scc_ServiceClass->scc_id;
							if(!Vehicles::checkVehicleclass($vendorId, $booking_class))
							{

								$error = "Oops! You have no cab in same class of this bookingg";
								throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
							}

							$chkOutStanding	 = VendorStats::frozenOutstanding($vendorId);
							$gozoNeedToPay	 = BookingInvoice::getVendorReceivable($accept_bcb_id, $accptVendorAmount);

							/* if (($chkOutStanding > 500 && $gozoNeedToPay < 500) || $chkOutStanding > 3000)
							  {
							  $bModel->addError('bkg_id', "Oops! Your payment is overdue. Please settle your Gozo accounts ASAP.");

							  $error = $bModel->getErrors();
							  throw new Exception(json_encode($error), 1);
							  } */


							$criticalityScore	 = $bModel->bkgPref->bkg_critical_score;
							$dependencyStatus	 = VendorStats::checkDependency($criticalityScore, $vendorId);

							if(!$dependencyStatus)
							{

								$error = "Dependability score low. Direct accept not available for you. To improve dependability score, do not refuse booking after you accept.";
								throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
							}

							if($calculateLockedAmount > 50)
							{

								$lAmount			 = ceil($calculateLockedAmount / 50) * 50;
								$lAmount			 = max($lAmount, 100);
								$securityAmountFlag	 = 1;
								$securityContent	 = "You can not accept this booking as your account balance is low. Please pay ₹ $lAmount to accept this booking.";

								throw new Exception(json_encode($securityContent), ReturnSet::ERROR_VALIDATION);
							}
						}
						$status = BookingVendorRequest::DirectAccept($accptVendorAmount, $vendorId, $accept_bcb_id, $userInfo);
						if($status == true)
						{

							$success = true;
						}
						error:
						if($return["errors"] != "")
						{
							$success = false;
							$error	 = $return["errors"]->bcb_vendor_id[0];
							throw new Exception($error, ReturnSet::ERROR_INVALID_DATA);
						}
						$returnSet->setStatus(true);
					}
					catch(Exception $e)
					{

						$returnSet = ReturnSet::setException($e);
						//$errors['bkg_id']	 = $e->getMessage();
					}
				}

				if($deny_bcb_id > 0)
				{
					$tripId		 = $deny_bcb_id;
					$success	 = false;
					$bvrModels	 = BookingVendorRequest::model()->findByBcbIdAndVendorId($deny_bcb_id, $vendorId);
					if(count($bvrModels) >= 1)
					{
						foreach($bvrModels as $bvrModel)
						{
							$bvrModel->bvr_bid_amount	 = 0;
							$bvrModel->bvr_accepted		 = 2;
							$bvrModel->bvr_assigned		 = 2;
							$bvrModel->bvr_assigned_at	 = new CDbExpression('NOW()');
							$bvrModel->bvr_accepted_at	 = new CDbExpression('NOW()');
							$success					 = $bvrModel->save();
							if(!$success)
							{
								$errors['bkg_id'] = $bvrModel->getErrors();
								break;
							}
						}
					}
					else
					{
						$success = BookingVendorRequest::model()->createRequest(0, $deny_bcb_id, $vendorId, 'deny');
						if(!$success)
						{
							$errors['bkg_id'] = 'Bid deny failed';
						}
					}
					if($success)
					{
						$bcabModel	 = BookingCab::model()->findByPk($deny_bcb_id);
						$bModels	 = $bcabModel->bookings;
						$eventId	 = BookingLog::BID_DENY;
						$desc		 = "Bid denied by vendor.";
						foreach($bModels as $bookingModel)
						{
							BookingLog::model()->createLog($bookingModel->bkg_id, $desc, $userInfo, $eventId);
						}
					}
				}
			}
			else
			{
				$success = false;
				$error	 = 'Unauthorised vendor';
			}


			if(!$returnSet->getStatus() && $tripId > 0 && $returnSet->hasErrors())
			{
				$errors		 = $returnSet->getErrors();
				$errorDesc	 = implode('; ', $errors);

				$errors		 = "Fail to accept bid: (" . $errorDesc . ") (Accept amount: " . $accptVendorAmount . ")";
				$bcabModel	 = BookingCab::model()->findByPk($tripId);
				$bcabModel->logFailedVendorAssignment($errorDesc, $userInfo, $vendorId);
			}
			#$errorDesc	 = implode(',', $errors['bkg_id']);
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'		 => $success,
					'error'			 => $errors,
					'securityFlag'	 => $securityAmountFlag,
					'message'		 => $errors,
					'securityAmount' => $lAmount,
				)
			]);
		});

		$this->onRest('req.post.bidAcceptGnow.render', function () {
			return $this->renderJson($this->bidAcceptGnow());
		});

		$this->onRest('req.post.bidDenyGnow.render', function () {
			return $this->renderJson($this->bidDenyGnow());
		});

		$this->onRest('req.post.gnowReadyToGo.render', function () {
			return $this->renderJson($this->gnowReadyToGo());
		});

		$this->onRest('req.post.gnowSomeProblemToGo.render', function () {
			return $this->renderJson($this->gnowSomeProblemToGo());
		});

		$this->onRest('req.post.bidSnoozeGnow.render', function () {
			return $this->renderJson($this->bidSnoozeGnow());
		});

		$this->onRest('req.post.confirmTrip.render', function () {
			return $this->renderJson($this->confirmTrip());
		});
		/**
		 * @deprecated since version 27-05-2020
		 * @author soumyajit
		 */
		$this->onRest('req.post.vendor_request_completed.render', function () {
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if($result == true)
			{
				$vendorId = UserInfo::getEntityId();
				//$status		 = Yii::app()->request->getParam('status');
				//$vendorId	 = Yii::app()->user->getId();
				//$page_no	 = Yii::app()->request->getParam('page_no');

				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				$status				 = $data1['status'];
				$page_no			 = $data1['page_no'];

				$count = Booking::model()->getcustDetailsCount($vendorId, $status);
				if($page_no != 0)
				{

					$vendorModel = Booking::model()->getcustCompleted($vendorId, $status, $page_no);
					if($vendorModel != [])
					{
						$success = true;
						$error	 = null;
					}
					else
					{
						$success = false;
						$error	 = "No records found";
					}
				}
				else
				{
					$vendorModel = Booking::model()->getcustCompleted($vendorId, $status);
					if($vendorModel != [])
					{
						$success = true;
						$error	 = null;
					}
					else
					{
						$success = false;
						$error	 = "No records found";
					}
					if($count['count'] != 0)
					{
						$pageCount = round((int) $count['count'] / 20);
					}
				}
			}
			else
			{
				$success = false;
				$error	 = 'Unauthorised Vendor';
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'		 => $success,
					'error'			 => $error,
					'data'			 => $vendorModel,
					'total_pages'	 => $pageCount,
					'count'			 => $count['count'],
				)
			]);
		});

		/**
		 * @deprecated since version 10-10-2019
		 * @author ramala
		 */
		$this->onRest('req.post.vendor_request_completed1.render', function () {
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if($result == true)
			{
				//$status		 = Yii::app()->request->getParam('status');
				$vendorId = UserInfo::getEntityId();
				//$page_no	 = Yii::app()->request->getParam('page_no');

				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				$status				 = $data1['status'];
				$page_no			 = $data1['page_no'];

				$count = Booking::model()->getcustDetailsCount1($vendorId, $status);
				if($page_no != 0)
				{

					$vendorModel = Booking::model()->getcustCompleted1($vendorId, $status, $page_no);
					if($vendorModel != [])
					{
						$success = true;
						$error	 = null;
					}
					else
					{
						$success = false;
						$error	 = "No records found";
					}
				}
				else
				{
					$vendorModel = Booking::model()->getcustCompleted1($vendorId, $status);
					if($vendorModel != [])
					{
						$success = true;
						$error	 = null;
					}
					else
					{
						$success = false;
						$error	 = "No records found";
					}
					if($count['count'] != 0)
					{
						$pageCount = round((int) $count['count'] / 20);
					}
				}
			}
			else
			{
				$success = false;
				$error	 = "Vendor Unauthorised";
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'		 => $success,
					'error'			 => $error,
					'data'			 => $vendorModel,
					'total_pages'	 => $pageCount,
					'count'			 => $count['count'],
				)
			]);
		});
		/**
		 * @deprecated 
		  New services : vendorAssignDriver
		 */
		$this->onRest('req.post.vendor_assigncabdriver.render', function () {

			Logger::create('27 vendor_assigncabdriver ', CLogger::LEVEL_TRACE);
			$token = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			try
			{
				$result = Vendors::model()->authoriseVendor($token);
				if($result == true)
				{
					$success = false;
					$errors	 = [];
					/* $bcb_id		 = Yii::app()->request->getParam('bcb_id');
					  $vehicle_id	 = Yii::app()->request->getParam('vehicle_id');
					  $driver_id	 = Yii::app()->request->getParam('driver_id');
					  $driver_no	 = Yii::app()->request->getParam('driver_no'); */

					$process_sync_data	 = Yii::app()->request->getParam('data');
					$data1				 = CJSON::decode($process_sync_data, true);
					Logger::trace('<===Request===>' . $process_sync_data);
					$bcb_id				 = $data1['bcb_id'];
					$vehicle_id			 = $data1['vehicle_id'];
					$driver_id			 = $data1['driver_id'];
					$driver_no			 = $data1['driver_no'];

					if(empty($bcb_id))
					{
						throw new Exception("Invalid booking ID: ", ReturnSet::ERROR_INVALID_DATA);
					}
					if(empty($vehicle_id))
					{
						throw new Exception("Invalid cab: ", ReturnSet::ERROR_INVALID_DATA);
					}
					if(empty($driver_id))
					{
						throw new Exception("Invalid driver name: ", ReturnSet::ERROR_INVALID_DATA);
					}
					if(empty($driver_no))
					{
						throw new Exception("Invalid driver phone: ", ReturnSet::ERROR_INVALID_DATA);
					}

					Vehicles::approveVehicleStatus($vehicle_id);

					/* @var $cabmodel  BookingCab */
					$cabmodel = BookingCab::model()->findByPk($bcb_id);

					//			if($cabmodel->bookings->bkgAgent->agt_payment_collect_flag != 1 )
					//			{
					//if ($cabmodel != '')
					if(!empty($cabmodel))
					{

						$cabmodel->bookings[0]->bkg_id;
						$pickupdt		 = $cabmodel->bookings[0]->bkg_pickup_date;
						$lastEvent		 = $cabmodel->bookings[0]->bkgTrack->btk_last_event;
						$isCngAllowed	 = $cabmodel->bookings[0]->bkgPref->bkg_cng_allowed;
						$dateDiff		 = ceil((strtotime(date('Y-m-d H:i:s')) - strtotime($pickupdt)) / 60);

						if($dateDiff >= 0)
						{
							$success = false;
							$cabmodel->addError('bkg_pickup', 'Pickup time has passed. Please contact support...');
							//	$errors	 = ['bkg_pickup' => 'Time over.'];
							if(in_array($lastEvent, [101, 203, 201]))
							{
								$cabmodel->addError('bkg_event', 'Booking is already in ' . BookingTrackLog::model()->event[$lastEvent]);
								//	$errors = ['bkg_event' => "Booking is already in " . BookingTrackLog::model()->event[$lastEvent]] . " status.";
							}
							$errors = $cabmodel->getErrors();
							goto skipAllCode;
						}

						$vendorId = UserInfo::getEntityId();

						if($cabmodel->bcb_vendor_id == $vendorId)
						{
							$cabmodel->bcb_cab_id		 = $vehicle_id;
							$cabmodel->bcb_driver_phone	 = $driver_no;
							$cabmodel->bcb_driver_id	 = $driver_id;
							$cabmodel->event_by			 = 2;
							$type_id					 = $cabmodel->bookings[0]->bkg_vehicle_type_id;
							//$cabtypeModel				 = VehicleTypes::model()->findByPk($type_id);
							$cabtypeModel				 = SvcClassVhcCat::model()->findByPk($type_id);
							$modelVehicles				 = Vehicles::model()->findByPk($cabmodel->bcb_cab_id);
							$modelDriver				 = Drivers::model()->findByPk($cabmodel->bcb_driver_id);
							if($modelVehicles->vhc_approved == 3)
							{
								$cabmodel->addError('bcb_cab_id', "Cab not approved. Cannot assign.");
								$success = false;
								$errors	 = $cabmodel->getErrors();
								goto skipAllCode;
							}
							if($modelDriver->drv_approved == 3)
							{
								$modelDriver->addError('bcb_driver_id', "Driver not approved. Cannot assign.");
								$success = false;
								$errors	 = $modelDriver->getErrors();
								goto skipAllCode;
							}
							$isCng				 = $modelVehicles->vhc_has_cng;
							$hasRooftopCarrier	 = $modelVehicles->vhc_has_rooftop_carrier;
							$cab_type			 = $cabtypeModel->scv_vct_id;
							$sccClass			 = $cabtypeModel->scv_scc_id;
							if($isCngAllowed == 0)
							{
								if($sccClass == 2 && $isCng == 1)
								{
									$cabmodel->addError('bcb_cab_id', "CNG cab not allowed for this booking.");
									$data = ['success' => false, 'errors' => $cabmodel->getErrors()];
									echo json_encode($data);
									Yii::app()->end();
								}
							}

							$cabmodel->chk_user_msg	 = array(0, 1);  // sms for user and driver
							$success				 = $cabmodel->assigncabdriver($vehicle_id, $driver_id, $cab_type, UserInfo::getInstance());

							$errors = $cabmodel->getErrors();
						}
						else
						{
							$cabmodel->addError('bcb_id', 'Booking not active');
							$errors = $cabmodel->getErrors();
						}
					}
					else
					{
						$errors = ['bcb_id' => 'Booking not active'];
					}
					//			}
					//			else
					//			{
					//			$success = false;
					//			$errors	 = "Driver assignment not allowed.";
					//			}			
				}
				else
				{
					$success = false;
					$errors	 = "Vendor unauthorised";
				}
				skipAllCode:
			}
			catch(Exception $ex)
			{
				$success = false;
				$errors	 = $ex->getMessage();
				Logger::trace('<===Error===>' . $errors . '<===Error Code===>' . $ex->getCode());
			}
			Logger::trace('<===Response===>' . json_encode(['success' => $success, 'errors' => $errors, 'bcb_id' => $cabmodel->bcb_id]));
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'bcb_id'	 => $cabmodel->bcb_id,
				)
			]);
		});

		/*
		 * old service : vendor_assigncabdriver  (method : POST)
		 * new service : vendorAssignDriver	 (method : POST)
		 */
		$this->onRest('req.post.vendorAssignDriver.render', function () {
			return $this->renderJSON($this->vendorAssignDriver());
		});

		/**
		 * Vendor unassign booking
		 */
		$this->onRest('req.get.vendor_unassign.render', function () {

			$transaction = DBUtil::beginTransaction();

			try
			{
				$success	 = false;
				$userInfo	 = UserInfo::getInstance();

				$process_sync_data = Yii::app()->request->getParam('data');
				Logger::trace("<===Request===>" . $process_sync_data);

				$data = CJSON::decode($process_sync_data, true);

				$bcbId		 = $data['bcb_id'];
				$reason		 = ((!$data['reason'] || $data['reason'] == '') ? 'App' : $data['reason']);
				$reasonId	 = 7;

				if(!$bcbId)
				{
					throw new Exception("Invalid Trip Id !!!");
				}

				$bcbBkgFirstModel = BookingCab::getFirstBkgByTripId($bcbId, array(3, 5));
				if(!$bcbBkgFirstModel)
				{
					throw new Exception("Invalid Trip !!!");
				}

				$bkgId			 = $bcbBkgFirstModel['bkg_id'];
				$pickupTimeDiff	 = $bcbBkgFirstModel['pickupTimeDiff'];

				if($pickupTimeDiff <= 30)
				{
					$success				 = false;
					$message				 = "FAIL!! Cannot reject at last minute. Contact support.";
					$errors['checktime'][]	 = 'FAIL!! Cannot reject at last minute. Contact support.';
				}
				else
				{
					$bcbModel = BookingCab::model()->findByPk($bcbId);

					$result = Booking::model()->canVendor($bcbId, $reason, $userInfo, array(), $reasonId);
				}

				if($result['success'])
				{


					// Booking auto cancel flag
					$bkgModel = Booking::model()->findByPk($bkgId);
					if($pickupTimeDiff <= 120)
					{
						$bkgModel->bkgPref->bkg_autocancel = 1;
						$bkgModel->bkgPref->save();
					}

					$pickup_date						 = $bkgModel->bkg_pickup_date;
					$vendorId							 = UserInfo::getEntityId();
					$res								 = BookingCab::lastminCancelFlagUpdate($pickup_date, $bcbId, $vendorId);
					$bcbModel->bcb_vendor_cancel_type	 = $res;

					// Booking Cab
					$bcbModel->bcb_denied_reason_id	 = $reasonId;
					$res							 = $bcbModel->save();
					if(!$res)
					{
						$success				 = false;
						$message				 = "Failed to update BookingCab Model";
						$errors['checktime'][]	 = $bcbModel->getErrors();
						DBUtil::rollbackTransaction($transaction);
					}


					// Vendor Profile
					VendorProfile::addCancelAttr($bkgModel, $bcbModel, $reason);

					$success				 = true;
					$message				 = "OK. Booking unassigned";
					$errors['checktime'][]	 = " ";
					DBUtil::commitTransaction($transaction);
				}
				else
				{
					if($result['errors'])
					{
						$errors['checktime'][] = $result['errors'];
					}

					$success = false;
					$message = " ";
					DBUtil::rollbackTransaction($transaction);
				}
			}
			catch(Exception $ex)
			{
				$success				 = false;
				$message				 = $ex->getMessage();
				$errors['checktime'][]	 = $message;
				DBUtil::rollbackTransaction($transaction);
			}

			response:
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'message'	 => $message,
					'bcb_id'	 => $bcbId,
				)
			]);
		});

		/*
		 * @deprecated
		 * new service : vendorUnassign	 (method : POST)
		 */
		$this->onRest('req.get.vendor_unassign_old.render', function () {

			Logger::create('23 vendor_unassign ', CLogger::LEVEL_TRACE);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if($result == true)
			{
				$success1 = false;

				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				$bcb_id				 = $data1['bcb_id'];
				$reason				 = $data1['reason'];

				$userInfo = UserInfo::getInstance();

				Logger::create('BCB ID ===========>: \n\t\t' . $bcb_id, CLogger::LEVEL_TRACE);

				$modelCab = Booking::model()->getDetailbyId($bcb_id);

//			$vendorAssignDt = $modelCab->bkgTrail->bkg_assigned_at;
//			$pickupDt = $modelCab->bkg_pickup_date;
				$pickupDt		 = $modelCab[0]['pickup_datetime'];
				$bkgId1			 = $modelCab[0]['bkg_id'];
				//$dateDiff	 = ceil((strtotime($pickupDt) - strtotime(date('Y-m-d H:i:s'))) / 60);
				$dateDiff		 = $modelCab[0]['diff'];
				$bookingCabModel = BookingCab::model()->findByPk($bcb_id);

				$bModels	 = $bookingCabModel->bookings;
				$bkgids		 = '';
				$bookingIds	 = [];
				foreach($bModels as $bModel)
				{
					$bookingIds	 = $bModel->bkg_id;
					$bkgids		 .= $bModel->bkg_booking_id . ', ';
				}
				$bkgids = rtrim($bkgids, ', ');
				if(!$reason)
				{
					$reason = "App";
				}
				$transaction = Yii::app()->db->beginTransaction();
				try
				{

					$reasonId = 7;
					if($dateDiff == NULL || $dateDiff == "")
					{
						$errors['checktime'][]	 = 'This trip is no longer valid.';
						$success1				 = false;
						$message				 = "This trip is no longer valid.";
						goto response;
					}
					if($dateDiff <= 30)
					{
						$success1				 = false;
						$message				 = "FAIL!! Cannot reject at last minute. Contact support.";
						$errors['checktime'][]	 = 'FAIL!! Cannot reject at last minute. Contact support.';
					}
					else
					{
						$result = Booking::model()->canVendor($bcb_id, $reason, $userInfo, $bookingIds, $reasonId);
					}



					if($result['success'])
					{
						$bookingCabModel->bcb_denied_reason_id	 = $reasonId;
						$vendorId								 = $bookingCabModel->bcb_vendor_id ? $bookingCabModel->bcb_vendor_id : $user_id;

						$firstBookingID	 = $modelCab[0]['bkg_id'];
						$bookingModel	 = Booking::model()->findByPk($firstBookingID);
						if($dateDiff >= 119)
						{

							$bookingModel->bkgPref->bkg_autocancel = 1;
							$bookingModel->bkgPref->save();
						}

						if(!$bookingCabModel->save())
						{
							$success1				 = false;
							$message				 = "Failed to update BookingCab Model";
							$errors['checktime'][]	 = $bookingCabModel->getErrors();
							$transaction->rollback();
						}

						$bookingCabModel->save();
						$step								 = BookingCab::model()->getVendorUnassignStep($bcb_id);
						$modifyUnassignModeDate				 = BookingCab::modifyUnassignMode($step, $bcb_id);
						$vendorId							 = Ratings::model()->getVendorIdByBookingId($modelCab[0]['bkg_id']);
						$vendorProfile						 = new VendorProfile();
						$vendorProfile->vnp_user_id			 = $vendorId['vnd_id'];
						$vendorProfile->vnp_booking_id		 = $modelCab[0]['bkg_id'];
						$vendorProfile->vnp_attribute_type	 = VendorProfile::TYPE_DENIALS;
						$vendorProfile->vnp_value_str		 = $reason;
						$vendorProfile->vnp_value_int		 = $bcb_id;
						$vendorProfile->save();

						$dateDiff							 = round((strtotime($modelCab[0]['pickup_datetime']) - strtotime(date('Y-m-d H:i:s'))) / (60 * 60));
						$vendorProfile						 = new VendorProfile();
						$vendorProfile->vnp_user_id			 = $vendorId['vnd_id'];
						$vendorProfile->vnp_booking_id		 = $modelCab[0]['bkg_id'];
						$vendorProfile->vnp_attribute_type	 = VendorProfile::TYPE_DENIALS_TO_PICKUP;
						$vendorProfile->vnp_value_str		 = 'Denials to Pickup';
						$vendorProfile->vnp_value_int		 = $dateDiff;
						$vendorProfile->save();

						$assignmentTime						 = VendorsLog::model()->getVendorAssignmentTime($modelCab[0]['bkg_id']);
						$assignmentDateTime					 = round((strtotime(date('Y-m-d H:i:s')) - strtotime($assignmentTime['vlg_created'])) / (60 * 60));
						$vendorProfile						 = new VendorProfile();
						$vendorProfile->vnp_user_id			 = $vendorId['vnd_id'];
						$vendorProfile->vnp_booking_id		 = $modelCab[0]['bkg_id'];
						$vendorProfile->vnp_attribute_type	 = VendorProfile::TYPE_DENIALS_FROM_ASSIGNMENT;
						$vendorProfile->vnp_value_str		 = 'Denials to Assignment';
						$vendorProfile->vnp_value_int		 = $assignmentDateTime;
						$vendorProfile->save();
						/* if ($vendorId != '')
						  {
						  $notificationMassage = "This week, you have denied duty at the last minute. This will cause your vendor rating to drop. Too many denials can increase the risk of your account getting blocked";
						  $notificationTitle	 = "Booking unassigned by vendor";
						  $payLoadData		 = ['tripId' => $bcb_id, 'EventCode' => Booking::CODE_VENDOR_DENY];
						  $success			 = AppTokens::model()->notifyVendor($vendorId['vnd_id'], $payLoadData, $notificationMassage, $notificationTitle);
						  } */

						$success1				 = true;
						$message				 = "OK. Booking unassigned.";
						$errors['checktime'][]	 = " ";
						$transaction->commit();
					}
					else
					{
						if($result['errors'])
						{
							$err = $result['errors'];
						}
						else
						{
							$err = " ";
						}

						$success1				 = false;
						$message				 = " ";
						$errors['checktime'][]	 = $err;
						Logger::trace("<===ErrResponse===>" . json_encode($err));
						$transaction->rollback();
					}
				}
				catch(Exception $e)
				{
					$success1				 = false;
					$message				 = "Unknown Exception";
					$errors['checktime'][]	 = $e;
					$transaction->rollback();
					ReturnSet::setException($e);
				}
			}
			else
			{
				$success1				 = false;
				$errors['checktime'][]	 = 'Vendor Unauthorised';
			}
			response:
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success1,
					'errors'	 => $errors,
					'message'	 => $message,
					'bcb_id'	 => $bcb_id,
				)
			]);
		});

		/* @deprecated
		 * 
		 * New services bookingDetails	
		 */
		$this->onRest('req.post.booking_details.render', function () {

			Logger::create('21 booking_details ', CLogger::LEVEL_TRACE);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if($result == true)
			{
				//$bcb_id	 = Yii::app()->request->getParam('bcb_id');				
				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				$bcb_id				 = $data1['bcb_id'];

				$model = Booking::model()->getDetailbyId($bcb_id);
				// Logger::create('$result:: =>' . json_encode($model), CLogger::LEVEL_TRACE);
				if($model != '')
				{
					$result = ['success' => true, 'model' => $model];
				}
				else
				{
					$result = ['success' => false, 'model' => []];
				}
			}
			else
			{
				$result = ['success' => false, 'model' => []];
			}


			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $result,
			]);
		});

		$this->onRest('req.post.booking_details1.render', function () {
			$bkg_booking_id		 = Yii::app()->request->getParam('bkg_booking_id');
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$bkg_booking_id		 = $data1['bkg_booking_id'];

			$model = Booking::model()->getDetailbyId1($bkg_booking_id);
			if($model != '')
			{
				$result = ['success' => true, 'model' => $model];
			}
			else
			{
				$result = ['success' => false, 'model' => []];
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $result,
			]);
		});

		$this->onRest('req.post.customer_no_show.render', function () {

			Logger::create('24 customer_no_show ', CLogger::LEVEL_TRACE);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if($result == true)
			{
				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				$bkg_id				 = $data1['bkg_id'];

				$model		 = Booking::model()->findByPk($bkg_id);
				$oldModel	 = $model;
				$userInfo	 = UserInfo::getInstance();
				//Logger::create('BOOKING ID' . $bkg_id, CLogger::LEVEL_TRACE);

				$model->bkgTrack->bkg_is_no_show = 1;
				if($model->bkgTrack->save())
				{
					//                $vendorProfile = new VendorProfile();
					//                $vendorProfile->vnp_user_id = $model->bkg_user_id;
					//                $vendorProfile->vnp_booking_id = $bkg_id;
					//                $vendorProfile->vnp_attribute_type = VendorProfile::TYPE_NO_SHOW;
					//                $vendorProfile->vnp_value_str = 'No Show';
					//                $vendorProfile->vnp_value_int = $model->bkg_bcb_id;
					//                $vendorProfile->save();

					$customerProfile					 = new CustomerProfile();
					$customerProfile->csp_user_id		 = $model->bkgUserInfo->bkg_user_id;
					$customerProfile->csp_booking_id	 = $bkg_id;
					$customerProfile->csp_attribute_type = CustomerProfile::TYPE_NO_SHOW;
					$customerProfile->csp_value_str		 = 'No Show';
					$customerProfile->csp_value_int		 = $model->bkg_bcb_id;
					$customerProfile->save();

					$success						 = true;
					$errors							 = [];
					$eventId						 = BookingLog::NO_SHOW;
					$desc							 = "Marked Customer as no-show.";
					$params['blg_booking_status']	 = $model->bkg_status;
					BookingLog::model()->createLog($bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
					BookingTrackLog::model()->add(2, $bkg_id, 204);
				}
				else
				{
					$success = false;
					$errors	 = "Error! Failed to set no-show";
				}
			}
			else
			{
				$success = false;
				$errors	 = "Driver not authorised";
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
				)
			]);
		});
		/**
		 * @deprecated function
		 * new function created at 07-06-2023
		 * new function bidBooking
		 */
		$this->onRest('req.post.bid_amount.render', function () {
			Logger::create('Enter Bid or Re-Bid ', CLogger::LEVEL_TRACE);
			$token = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);

			Logger::create('Token ===> ' . $token, CLogger::LEVEL_INFO);
			$authoriseVendor = Vendors::model()->authoriseVendor($token);
			Logger::create('Authorise Vendor ===========> ' . $authoriseVendor, CLogger::LEVEL_INFO);
			$processSyncData = Yii::app()->request->getParam('data');
			//$processSyncData = '{"bcb_id":"1003506","bid_amount":"4000"}';
			$data			 = CJSON::decode($processSyncData, true);
			$bcb_id			 = $data['bcb_id'];
			$bid_amount		 = $data['bid_amount'];
			if($authoriseVendor == true)
			{

				$success		 = true;
				$errors			 = [];
				$vendorId		 = UserInfo::getEntityId();
				Logger::trace("<===Request===>" . $processSyncData . '===' . $vendorId);
				$transaction	 = DBUtil::beginTransaction();
				/* @var $vendorModel Vendors */
				$vendorModel	 = Vendors::model()->findByPk($vendorId);
				$securityAmount	 = $vendorModel->vendorStats->vrs_security_amount;
				$dependencyScore = $vendorModel->vendorStats->vrs_dependency;
				$codFreeze		 = $vendorModel->vendorPrefs->vnp_cod_freeze;

				$isApproveCar		 = $isApproveDriver	 = $isDocApprove		 = $isApproveBooking	 = false;
				$bookingCabModel	 = BookingCab::model()->findByPk($bcb_id);
				$bookingModels		 = $bookingCabModel->bookings;
//				if($securityAmount<0 && $codFreeze==1)
//				{
//					DBUtil::rollbackTransaction($transaction);
//					$errors[]	 = "Your security amount is low and Your Gozo Account is freezed. You do not have permission to serve that booking.";
//					$success	 = false;
//					goto result;
//				}
//				if($securityAmount>0 && $codFreeze==1 && $bookingModels->bkgInvoice->bkg_corporate_remunerator != 2)
//				{
//					
//					DBUtil::rollbackTransaction($transaction);
//					$errors[]	 = "Your Gozo Account is freezed.You do not have permission to serve that booking.";
//					$success	 = false;
//					goto result;
//				}
				if(($vendorModel->vnd_active == 1) || ($vendorModel->vendorPrefs->vnp_is_orientation > 0))
				{
					$isDocApprove = true;
				}
				$isApproveCar	 = ($vendorModel->vendorStats->vrs_approve_car_count > 0) ? true : false;
				$isApproveDriver = ($vendorModel->vendorStats->vrs_approve_driver_count > 0) ? true : false;

				if($isDocApprove == false)
				{
					DBUtil::rollbackTransaction($transaction);
					$errorId	 = 1;
					$errors[]	 = "Check documents. Your documents are missing or not yet approved.";
					$success	 = false;
					goto result;
				}

				if($isApproveCar == false)
				{
					$errorId	 = 2;
					DBUtil::rollbackTransaction($transaction);
					$errors[]	 = "Get 1 car approved before we can send you business.";
					$success	 = false;
					goto result;
				}
				if($isApproveDriver == false)
				{
					$errorId	 = 3;
					DBUtil::rollbackTransaction($transaction);
					$errors[]	 = "Get 1 driver approved before we can send you business.";
					$success	 = false;
					goto result;
				}
				if($vendorModel->vnd_active == 2)
				{
					$errorId	 = 4;
					DBUtil::rollbackTransaction($transaction);
					$errors[]	 = "Your Gozo account is blocked. Please contact Gozo vendor team.";
					$success	 = false;
					goto result;
				}

				#$car_arr		 = explode(",", $carType);
				#$carValArr		 = BookingVendorRequest::carAccess($car_arr);
				#$carVal = implode(",",$carValArr);


				foreach($bookingModels as $bModel)
				{
					$booking_class	 = $bModel->bkgSvcClassVhcCat->scc_ServiceClass->scc_id;
					$bookingType	 = $bModel->bkg_booking_type;
					if(!Vehicles::checkVehicleclass($vendorId, $booking_class))
					{
						//$errors[]	 = "ops! You have no cab for this particular booking class.";
						$errorId	 = 5;
						$errors[]	 = "Check your approved cars. None in this service class";
						$success	 = false;
						goto result;
					}
					$dataCount = VendorPref::checkApprovedService($vendorId, $bookingType);
					if($dataCount < 1)
					{
						if(($bookingType == 4 || $bookingType == 12)) // all vendor can able to bid for airport
						{
							goto skip;
						}
						$errorId	 = 6;
						$errors[]	 = "No permisssion to serve this booking";
						$success	 = false;
						goto result;
					}
					skip:

					$check_availability = Vehicles::checkVehicleAvailability($vendorId, $bookingCabModel->bcb_start_time, $bookingCabModel->bcb_end_time, $bModel->bkg_from_city_id, $bModel->bkg_to_city_id, $bModel->bkgSvcClassVhcCat->scv_vct_id);
					if($check_availability != "")
					{
						$errors[]	 = $check_availability;
						$success	 = false;
						goto result;
					}
				}
				if($bookingModels == '' || $bookingModels == [] || $bookingModels == 0)
				{
					$bookingModels = BookingSmartmatch::model()->getBookings($bcb_id);
					if(count($bookingModels) > 0)
					{
						$success = true;
					}
					else
					{
						DBUtil::rollbackTransaction($transaction);
						$errors[]	 = "Sorry!! No booking found";
						$success	 = false;
					}
				}

				if($success)
				{
					$arrAllowedBids			 = $bookingCabModel->getMinMaxAllowedBidAmount();
					$checkPreviousBidAmount	 = BookingVendorRequest::calculateBidAmount($vendorId, $bcb_id);
					$previousBidAmount		 = $checkPreviousBidAmount['bvr_bid_amount'];

					#$minBidAmount = round($bookingCabModel->bcb_vendor_amount * 0.70);
					#if ($minBidAmount <= $bid_amount)
					if(($bid_amount >= $arrAllowedBids['minBid'] && ($bid_amount <= $arrAllowedBids['maxBid'] || $arrAllowedBids['maxBid'] == 0)) || ( $bid_amount < $previousBidAmount && $bid_amount >= $arrAllowedBids['minBid'] ))
					{
						$success = true;
						foreach($bookingModels as $bModel)
						{

							$isVendorUnassigned = BookingLog::isVendorUnAssigned($vendorId, $bModel->bkg_id);
							if($isVendorUnassigned)
							{
								$errorId	 = 7;
								$errors[]	 = "You were unassigned from / denied this trip before. So you cannot bid on it again.";
							}
							if(count($errors) > 0)
							{
								DBUtil::rollbackTransaction($transaction);
								$success = false;
								break;
							}
							if($bModel->bkg_reconfirm_flag <> 1 || $bModel->bkgPref->bkg_block_autoassignment == 1)
							{

								goto skipDirectAccept;
							}
							if(($bookingType == 4 || $bookingType == 12) && $dependencyScore <= 60) // if dependency less than 60 no direct accept.
							{
								goto skipDirectAccept;
							}
							$checkOutstanding = VendorStats::checkOutstanding($bcb_id); // this checking remove for security amount feature in vendor app

							if($checkOutstanding)
							{
								//check vendor due status

								$chekVendorDue = BookingCab::model()->chkVendorDue($bcb_id);

								if($chekVendorDue)
								{
									goto skipDirectAccept;
								}
							}
						}
						if($success)
						{
							$userInfo			 = UserInfo::getInstance();
							//if bid amount same or lower than accepted amount start here
							$directAcptAmount	 = 0;
							$directAcptAmount	 = BookingVendorRequest::getDirectAcceptAmount($vendorId, $bcb_id);

							if($bid_amount <= $directAcptAmount)
							{

								// according to AK direct accept logic for depency score change on 10/10/2022
								$criticalityScore	 = $bModel->bkgPref->bkg_critical_score;
								$dependencyStatus	 = VendorStats::checkDependency($criticalityScore, $vendorId);

								if(!$dependencyStatus)
								{
									goto skipDirectAccept;
								}

								$status = BookingVendorRequest::DirectAccept($bid_amount, $vendorId, $bcb_id, $userInfo);
								if($status == true)
								{
									DBUtil::commitTransaction($transaction);
									$dirctActStatus = 1;

									//Vendor bid request
									$bidRequest	 = new Stub\vendor\BidRequest();
									$bidRequest	 = $bidRequest->setData($bookingModels[0]->bkg_id, $bcb_id, $vendorId, $bid_amount, $bookingModels[0]->bkg_pickup_date, $bookingModels[0]->bkgInvoice->bkg_toll_tax, $bookingModels[0]->bkgInvoice->bkg_state_tax, $bookingModels[0]->bkg_trip_distance, $bookingModels[0]->bkgPref->bpr_row_identifier);
									$bidResponse = Filter::removeNull($bidRequest);
									IRead::setVendorBidRequest($bidResponse);
									//Vendor bid request


									goto end;
								}
								else
								{
									goto skipDirectAccept;
								}
							}
							//if bid amount same or lower than accepted amount end here
							skipDirectAccept:
							$result = BookingVendorRequest::model()->createRequest($bid_amount, $bcb_id, $vendorId);
							if($result)
							{
								$vendorStat				 = VendorStats::model()->getbyVendorId($vendorId);
								$vendorStat->vrs_tot_bid = $vendorStat->vrs_tot_bid + 1;
								$vendorStat->save();

								try
								{
									//Vendor bid request
									$bidRequest	 = new Stub\vendor\BidRequest();
									$bidRequest	 = $bidRequest->setData($bookingModels[0]->bkg_id, $bcb_id, $vendorId, $bid_amount, $bookingModels[0]->bkg_pickup_date, $bookingModels[0]->bkgInvoice->bkg_toll_tax, $bookingModels[0]->bkgInvoice->bkg_state_tax, $bookingModels[0]->bkg_trip_distance, $bookingModels[0]->bkgPref->bpr_row_identifier);
									$bidResponse = Filter::removeNull($bidRequest);
									IRead::setVendorBidRequest($bidResponse);
									//Vendor bid request
								}
								catch(Exception $ex)
								{
									ReturnSet::setException($ex);
								}

								$eventId = BookingLog::BID_SET;
								$desc	 = "Bid of ₹" . $bid_amount . " provided.";
								$message = "Your bid is accepted.";
								foreach($bookingModels as $bookingModel)
								{
									$res = BookingLog::model()->createLog($bookingModel->bkg_id, $desc, $userInfo, $eventId);
									if(!$res)
									{

										$msg = "Your bid related data not added in booking log. " . $processSyncData;
										Logger::error(new Exception($msg, ReturnSet::ERROR_FAILED));
									}
								}
								DBUtil::commitTransaction($transaction);
							}
							else
							{
								DBUtil::rollbackTransaction($transaction);
								$success	 = false;
								$errors[]	 = "Something went wrong";
							}
						}
					}
					else
					{
						DBUtil::rollbackTransaction($transaction);
						#$errors[]	 = "Bid Amount should be greater than or equal to " . $minBidAmount;
						$errors[]	 = "Bid amount out of range (too low or too high)";
						$success	 = false;
					}
				}
			}
			else
			{
				DBUtil::rollbackTransaction($transaction);
				$success	 = false;
				$errors[]	 = "Vendor Unauthorised";
			}
			result:
			$errors		 = implode(', ', $errors);
			$resultVar	 = "success => " . $success . "errors => " . $errors;
			Logger::trace('Result ===========>: \n\t\t' . $resultVar);

			if($errors != '')
			{
				/** @var BookingCab $bcbModel */
				$bcbModel = BookingCab::model()->findByPk($bcb_id);
				$bcbModel->logFailedVendorAssignment($errors, $userInfo, $vendorId);
			}



			end:
			if($dirctActStatus == 1)
			{
				$eventId = BookingLog::VENDOR_ASSIGNED;
				$message = "The booking is assigned to you";
				$desc	 = "Vendor accept amount: ₹" . $directAcptAmount . " Vendor bid amount: ₹" . $bid_amount . ". Booking is direct accepted ";
				$res	 = BookingLog::model()->createLog($bookingModels[0]->bkg_id, $desc, $userInfo, $eventId);
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'		 => $success,
					'errors'		 => $errors,
					'message'		 => $message,
					'payment_due'	 => $payment_due,
					'payment_msg'	 => $payment_msg
				)
			]);
		});
		/**
		 * New function bidBooking old one bid_amount
		 */
		$this->onRest('req.post.bidBooking.render', function () {
			$transaction		 = null;
			$processSyncData	 = Yii::app()->request->getParam('data');
			$securityAmountFlag	 = 0;
			$data				 = CJSON::decode($processSyncData, true);
			$bcb_id				 = $data['bcb_id'];
			$bid_amount			 = $data['bid_amount'];
			$lAmount			 = 0;

			$success	 = true;
			$errors		 = [];
			$vendorId	 = UserInfo::getEntityId();
			Logger::trace("<===Request===>" . $processSyncData . '===' . $vendorId);

			/* @var $vendorModel Vendors */
			$vendorModel	 = Vendors::model()->findByPk($vendorId);
			$securityAmount	 = $vendorModel->vendorStats->vrs_security_amount;

			$dependencyScore = $vendorModel->vendorStats->vrs_dependency;
			$vnpCodFreeze	 = $vendorModel->vendorPrefs->vnp_cod_freeze;

			$isApproveCar		 = $isApproveDriver	 = $isDocApprove		 = $isApproveBooking	 = false;
			$bookingCabModel	 = BookingCab::model()->findByPk($bcb_id);
			$bookingModels		 = $bookingCabModel->bookings;
			$bookingId			 = $bookingModels[0]->bkg_id;
			if($bookingModels[0]->bkg_bcb_id != $bcb_id)
			{
				$error = "Sorry! This trip no longer exists. Please refresh your screen.";
				throw new Exception(json_encode($error), ReturnSet::ERROR_INVALID_DATA);
			}
			$cashBkgValidation = BookingInvoice::checkCODBkg($bookingId, $vnpCodFreeze);
			if($cashBkgValidation == false)
			{
				$errors[]	 = "No permisssion to serve cash booking.";
				$success	 = false;
				goto endpoint;
			}

			if(($vendorModel->vnd_active == 1) || ($vendorModel->vendorPrefs->vnp_is_orientation > 0))
			{
				$isDocApprove = true;
			}
			$isApproveCar	 = ($vendorModel->vendorStats->vrs_approve_car_count > 0) ? true : false;
			$isApproveDriver = ($vendorModel->vendorStats->vrs_approve_driver_count > 0) ? true : false;

			if($isDocApprove == false)
			{
				$errors[]	 = "Check documents. Your documents are missing or not yet approved.";
				$success	 = false;
			}
			$is_car = VendorStats::model()->statusCheckVehicle($vendorId);

			if($is_car < 1)
			{

				$errors[]	 = "Your have no registered or approved car in our list. Please register your car with document to increase the chance of winning this bid.";
				$success	 = false;
			}
			$is_driver = VendorStats::model()->statusCheckDriver($vendorId);
			if($is_driver < 1)
			{

				$errors[]	 = "Your have no registered or approved driver in our list. Please register your driver with document to increase the chance of winning this bid.";
				$success	 = false;
			}
			if($isApproveCar == false)
			{
				$errors[]	 = "Get 1 car approved before we can send you business.";
				$success	 = false;
			}
			if($isApproveDriver == false)
			{
				$errors[]	 = "Get 1 driver approved before we can send you business.";
				$success	 = false;
			}
			if($vendorModel->vnd_active == 2)
			{
				$errors[]	 = "Your Gozo account is blocked. Please contact Gozo vendor team.";
				$success	 = false;
			}

			foreach($bookingModels as $bModel)
			{
				$booking_class	 = $bModel->bkgSvcClassVhcCat->scc_ServiceClass->scc_id;
				$bookingType	 = $bModel->bkg_booking_type;

				$dataCount = VendorPref::checkApprovedService($vendorId, $bookingType);
				if($dataCount < 1)
				{
					if(($bookingType == 4 || $bookingType == 12)) // all vendor can able to bid for airport
					{
						goto skip;
					}

					$errors[] = "No permisssion to serve this booking";
					goto result;
				}
				skip:

				$check_availability = Vehicles::checkVehicleAvailability($vendorId, $bookingCabModel->bcb_start_time, $bookingCabModel->bcb_end_time, $bModel->bkg_from_city_id, $bModel->bkg_to_city_id, $bModel->bkgSvcClassVhcCat->scv_vct_id);
				if($check_availability != "" && $isApproveCar == true)
				{
					$errors[]	 = $check_availability;
					$success	 = false;
				}
			}

			if($bookingModels == '' || $bookingModels == [] || $bookingModels == 0)
			{
				$bookingModels = BookingSmartmatch::model()->getBookings($bcb_id);
				if(count($bookingModels) > 0)
				{
					$success = true;
				}
				else
				{
					$errors[]	 = "Sorry!! No booking found";
					$success	 = false;
					goto result;
				}
			}

			$arrAllowedBids			 = $bookingCabModel->getMinMaxAllowedBidAmount();
			$checkPreviousBidAmount	 = BookingVendorRequest::calculateBidAmount($vendorId, $bcb_id);
			$previousBidAmount		 = $checkPreviousBidAmount['bvr_bid_amount'];

			#$minBidAmount = round($bookingCabModel->bcb_vendor_amount * 0.70);
			#if ($minBidAmount <= $bid_amount)
			if(($bid_amount < $arrAllowedBids['minBid'] || ($bid_amount > $arrAllowedBids['maxBid'] && $arrAllowedBids['maxBid'] > 0)) || ( $bid_amount < $arrAllowedBids['minBid'] ))
			{
				$errors[]	 = "Bid amount out of range (too low or too high)";
				$success	 = false;
				goto result;
			}
			foreach($bookingModels as $bModel)
			{

				$isVendorUnassigned = BookingLog::isVendorUnAssigned($vendorId, $bModel->bkg_id);
				if($isVendorUnassigned)
				{
					$errors[] = "You were unassigned from / denied this trip before. So you cannot bid on it again.0";
				}
				if(count($errors) > 0)
				{
					$success = false;
				}
				if($bModel->bkg_reconfirm_flag <> 1 || $bModel->bkgPref->bkg_block_autoassignment == 1)
				{
					$success = false;
				}
				if(($bookingType == 4 || $bookingType == 12) && $dependencyScore <= 60) // if dependency less than 60 no direct accept.
				{
					$success = false;
				}
				if($securityAmount < 1000)
				{
					$success = false;
				}
			}

			try
			{
				$userInfo			 = UserInfo::getInstance();
				//if bid amount same or lower than accepted amount start here
				$directAcptAmount	 = 0;
				$directAcptAmount	 = BookingVendorRequest::getDirectAcceptAmount($vendorId, $bcb_id);

				// according to AK direct accept logic for depency score change on 10/10/2022
				$criticalityScore		 = $bModel->bkgPref->bkg_critical_score;
				$dependencyStatus		 = VendorStats::checkDependency($criticalityScore, $vendorId);
				$calculateLockedAmount	 = BookingInvoice::calculateLockAmount($bookingId, $bid_amount, $vendorId);

				if(!$dependencyStatus)
				{
					$errors[]	 = "Dependency score is low";
					$success	 = false;
				}
				if($calculateLockedAmount > 25)
				{
					$success = false;
				}
				endpoint:
				if(!$success || $bid_amount > $directAcptAmount)
				{
					goto skipDirectAccept;
				}
				$transaction = DBUtil::beginTransaction();
				$status		 = BookingVendorRequest::DirectAccept($bid_amount, $vendorId, $bcb_id, $userInfo);
				if(!$status)
				{
					goto skipDirectAccept;
				}
				$dirctActStatus	 = 1;
				$eventId		 = BookingLog::VENDOR_ASSIGNED;
				$message		 = "The booking is assigned to you";
				$desc			 = "Vendor accept amount: ₹" . $directAcptAmount . " Vendor bid amount: ₹" . $bid_amount . ". Booking is direct accepted ";
				$res			 = BookingLog::model()->createLog($bookingModels[0]->bkg_id, $desc, $userInfo, $eventId);
				//Vendor bid request
				$bidRequest		 = new Stub\vendor\BidRequest();
				$bidRequest		 = $bidRequest->setData($bookingModels[0]->bkg_id, $bcb_id, $vendorId, $bid_amount, $bookingModels[0]->bkg_pickup_date, $bookingModels[0]->bkgInvoice->bkg_toll_tax, $bookingModels[0]->bkgInvoice->bkg_state_tax, $bookingModels[0]->bkg_trip_distance, $bookingModels[0]->bkgPref->bpr_row_identifier);
				$bidResponse	 = Filter::removeNull($bidRequest);
				IRead::setVendorBidRequest($bidResponse);
				//Vendor bid request
				goto end;

				//if bid amount same or lower than accepted amount end here
				skipDirectAccept:
				$result = BookingVendorRequest::model()->createRequest($bid_amount, $bcb_id, $vendorId);
				if(!$result)
				{
					Logger::trace("Errors: " . json_encode($errors));
					throw new Exception("Something went wrong", ReturnSet::ERROR_FAILED);
				}

				$vendorStat				 = VendorStats::model()->getbyVendorId($vendorId);
				$vendorStat->vrs_tot_bid = $vendorStat->vrs_tot_bid + 1;
				$vendorStat->save();

				try
				{
					//Vendor bid request
					$bidRequest	 = new Stub\vendor\BidRequest();
					$bidRequest	 = $bidRequest->setData($bookingModels[0]->bkg_id, $bcb_id, $vendorId, $bid_amount, $bookingModels[0]->bkg_pickup_date, $bookingModels[0]->bkgInvoice->bkg_toll_tax, $bookingModels[0]->bkgInvoice->bkg_state_tax, $bookingModels[0]->bkg_trip_distance, $bookingModels[0]->bkgPref->bpr_row_identifier);
					$bidResponse = Filter::removeNull($bidRequest);
					IRead::setVendorBidRequest($bidResponse);
					//Vendor bid request
				}
				catch(Exception $ex)
				{
					ReturnSet::setException($ex);
				}

				$eventId = BookingLog::BID_SET;
				$desc	 = "Bid of ₹" . $bid_amount . " provided.";
				$message = "Your bid has been accepted.";

				if($calculateLockedAmount > 25)
				{
					$securityAmountFlag = 1;

					$lAmount	 = ceil($calculateLockedAmount / 25) * 25;
					$lAmount	 = max($lAmount, 100);
					$errors[]	 = "Your low account balance. Please pay ₹ $lAmount to increase the chance of winning this bid.";
				}



				if(count($errors) > 0)
				{
					$message = "Your bid has been accepted but the chances of winning it is low due to the reason given below: \n " . implode(",\n", $errors);
				}

				foreach($bookingModels as $bookingModel)
				{
					if(count($errors) > 0)
					{
						$desc .= "\n " . implode(",\n", $errors);
					}
					$res = BookingLog::model()->createLog($bookingModel->bkg_id, $desc, $userInfo, $eventId);
					if(!$res)
					{
						Logger::trace($desc);
						$msg = "Your bid related data not added in booking log. Booking ID: {$bookingModel->bkg_id}";
						Logger::error(new Exception($msg, ReturnSet::ERROR_FAILED));
					}
				}

				$success = true;
				result:

				$errors		 = implode(', ', $errors);
				$resultVar	 = "success => " . $success . "errors => " . $errors;
				Logger::trace('Result: ' . $resultVar);

				if($errors != '')
				{
					$errors		 = "Fail to assign bid: (" . $errors . ")";
					$bcbModel	 = BookingCab::model()->findByPk($bcb_id);
					$bcbModel->logFailedVendorAssignment($errors, $userInfo, $vendorId);
				}
				end:
				DBUtil::commitTransaction($transaction);
			}
			catch(Exception $exc)
			{

				DBUtil::rollbackTransaction($transaction);
				$returnSet	 = ReturnSet::setException($exc);
				$message	 = $returnSet->getMessage();
				$errors		 = $returnSet->getErrors();
				$errors		 = implode(', ', $errors);
				$success	 = false;
			}


			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'		 => $success,
					'error'			 => $errors,
					'message'		 => $message,
					'securityFlag'	 => $securityAmountFlag,
					'securityAmount' => $lAmount,
				)
			]);
		});

		/* @deprecated change_ride_status
		 * new service changeRideStatus
		 */
		$this->onRest('req.post.change_ride_status.render', function () {

			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);

			if($result)
			{

				$success			 = false;
				$process_sync_data	 = Yii::app()->request->getParam('data');
//$process_sync_data='{"bkg_id":"1476725","bcb_id":"1565710","flag":"1"}';
				$data1				 = CJSON::decode($process_sync_data, true);
				$bkg_id				 = $data1['bkg_id'];
				$bcb_id				 = $data1['bcb_id'];
				$trip_otp			 = $data1['trip_otp'];
				$flag				 = $data1['flag'];
				$model				 = Booking::model()->findByPk($bkg_id);
				if(!$model || !$bkg_id)
				{
					$success = false;
					$errors	 = 'Booking not found';
					goto result;
				}
				else
				{
					$pickupDate			 = $model->bkg_pickup_date;
					$estimateStart		 = date("Y-m-d H:i:s", strtotime($pickupDate . "-30 minutes"));
					$nowTime			 = date("Y-m-d H:i:s");
					$showStartTime		 = date("jS F, Y g:i A", strtotime($estimateStart));
					$estimateComplete	 = date("Y-m-d H:i:s", strtotime($pickupDate . "+ $model->bkg_trip_duration minutes"));
					if($estimateStart > $nowTime)
					{
						$errors = "Trip start allowed after " . $showStartTime;
						goto result;
					}
					else
					{
						if($model->bkgTrack->bkg_ride_start == 1)
						{
							$errors = "Trip is already started";
							goto result;
						}
						else
						{
							if($flag == 0 && $estimateComplete > $nowTime)
							{
								$platform		 = TripOtplog::Platform_PARTNERAPP;
								$bookingTrack	 = $model->bkgTrack;
								$returnSet		 = $bookingTrack->startTrip($platform, $trip_otp);
								$success		 = $returnSet->getStatus();
								$errors			 = $desc			 = $returnSet->getData()['message'];
							}
							else
							{
								$platform		 = TripOtplog::Platform_PARTNERAPP;
								$userInfo		 = UserInfo::getInstance();
								$bookingTrack	 = $model->bkgTrack;
								$returnSet		 = $bookingTrack->startOverDueTrip($bkg_id, $platform, $userInfo);
								$success		 = $returnSet->getStatus();
								$errors			 = $desc			 = $returnSet->getData()['message'];
							}
						}
					}
				}
			}
			result:
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
				)
			]);
		});

		$this->onRest('req.post.vendor_extra_request.render', function () {

			Logger::create('26 vendor_extra_request ', CLogger::LEVEL_TRACE);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if($result == true)
			{
				$success			 = false;
				$errors				 = "Something went wrong";
				//$vendorId			 = Yii::app()->user->getId();
				$vendorId			 = UserInfo::getEntityId();
				$process_sync_data	 = Yii::app()->request->getParam('data');
				//$process_sync_data = '{"bkg_id":"228779","bcb_id":"234278","bkg_extra_charge":"123.0","bkg_parking_charge":"50","bkg_extra_toll_tax":"20","bkg_extra_state_tax":"10","bkg_extra_total_km":"13","bkg_vendor_actual_collected":"3300"}';
				//$process_sync_data	 = '{"bkg_id":"426175","bcb_id":"447535","bkg_extra_charge":"137.0","bkg_parking_charge":"0","bkg_extra_toll_tax":"0","bkg_extra_state_tax":"0","bkg_extra_total_km":"13","bkg_vendor_actual_collected":"1059"}';
				//$process_sync_data	 = '{"bkg_id":"425581","bcb_id":"446694","bkg_extra_charge":"100.0","bkg_parking_charge":"50","bkg_extra_toll_tax":"0","bkg_extra_state_tax":"0","bkg_extra_total_km":"10","bkg_vendor_actual_collected":"4000"}';
				//Logger::create('POST DATA  ===========>: \n\t\t' . $process_sync_data, CLogger::LEVEL_TRACE);
				$data				 = CJSON::decode($process_sync_data, true);
				$model				 = Booking::model()->findByPk($data['bkg_id']);

				$pickupDate				 = $model->bkg_pickup_date;
				$pickupDuration			 = ROUND($model->bkg_trip_duration / 2);
				$estimateHalfPickup		 = date("Y-m-d H:i:s", strtotime($pickupDate . "+$pickupDuration minutes"));
				$estimatetime			 = date("jS F, Y g:i A", strtotime($estimateHalfPickup));
				//exit;
				$nowTime				 = date("Y-m-d H:i:s");
				$bkgId					 = $data['bkg_id'];
				$bkgExtraCharge			 = floor($model->bkgInvoice->bkg_rate_per_km_extra * $data['bkg_extra_km']);
				$bkgExtraTotalKm		 = $data['bkg_extra_km'];
				$bkgExtraTollTax		 = $data['bkg_extra_toll_tax'];
				$bkgExtraStateTax		 = $data['bkg_extra_state_tax'];
				$bkgParkingCharge		 = $data['bkg_parking_charge'];
				$vendorActualCollected	 = $data['bkg_vendor_actual_collected'];
				$bkgExtraMin			 = $data['bkg_extra_min'];
				$bkgExtraMinCharges		 = $data['bkg_extra_per_min_charge'];
				$bkgDueAmount			 = round($model->bkgInvoice->bkg_due_amount);
				if($estimateHalfPickup > $nowTime)
				{
					$error = "Trip can be marked complete after " . $estimateHalfPickup;
				}
				else
				{
					if($vendorActualCollected >= $bkgDueAmount)
					{
						/* @var $modelsub BookingSub */
						$modelsub = new BookingSub();
						if($modelsub->addExtraCharges($bkgId, $bkgExtraCharge, $bkgExtraTotalKm, $bkgExtraTollTax, $bkgExtraStateTax, $bkgParkingCharge, UserInfo::getInstance(), $vendorActualCollected, $bkgExtraMin, $bkgExtraMinCharges) == true)
						{
							if(Booking::model()->tripMarkComplete($bkgId, 2))
							{
								$success = true;
								$error	 = null;
							}
							else
							{
								$success = false;
								$error	 = "Something went wrong";
							}
						}
					}
					else
					{
						$error = "Cash to collect is ₹" . $bkgDueAmount;
					}
				}
			}
			else
			{
				$success = false;
				$error	 = "Vendor Unauthorised";
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'error'		 => $error
				)
			]);
		});
		$this->onRest('req.post.syncChat.render', function () {
			return $this->syncChat();
		});
		$this->onRest('req.post.syncMessage.render', function () {
				return $this->syncChat(); 
		});

		$this->onRest('req.post.syncMessageold.render', function () {

			//Logger::create('62 syncMessage ', CLogger::LEVEL_TRACE);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if($result == true)
			{
				$success = false;
				$errors	 = '';
				try
				{
					$userInfo			 = UserInfo::getInstance();
					$userInfo->userType	 = UserInfo::TYPE_VENDOR;

					$process_sync_data = Yii::app()->request->getParam('data');

					$data1	 = CJSON::decode($process_sync_data, true);
					$bkgId	 = $data1['bkg_id'];
					$msg	 = $data1['msg'];
					$chtId	 = $data1['cht_id'];
					$chlId	 = $data1['chl_id'];

					if($chtId == NULL)
					{
						$chtId = 0;
					}
					if($chlId == NULL)
					{
						$chlId = 0;
					}


					$isDriver	 = 0;
					$isConsumer	 = 0;
					$isVendor	 = 1;
					$entityType	 = 0;

					$arrData			 = array();
					$arrData['source']	 = 'vendor';

					$transaction = Yii::app()->db->beginTransaction();
					if(!$process_sync_data)
					{
						throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
					}
					if(isset($msg) && $msg != '')
					{
						$syncData	 = ['bkgId' => $bkgId, 'msg' => $msg, 'isDriver' => $isDriver, 'isVendor' => $isVendor, 'isConsumer' => $isConsumer];
						//Logger::create('POST DATA ===========>: \n\t\t' . $syncData . " == " . $userInfo->userType, CLogger::LEVEL_TRACE);
						$msgObj		 = new ChatLog();
						$result		 = $msgObj->addMessage($bkgId, $msg, $userInfo, $isDriver, $isVendor, $isConsumer, $entityType, $arrData);
						//$result			 = $msgObj->addMessageV1($message, $refId, $refType, $userInfo, $roomEntityInfo	 = [], $is_driver		 = 0, $is_vendor		 = 0, $is_customer	 = 0, $arrData);
						if($result['success'] == true)
						{
							$success = true;
							$transaction->commit();
						}
						else if($result['success'] == false)
						{
							$errors = $result['errors'];
							throw new Exception("Not Validate.\n\t\t" . json_encode($errors));
						}
					}
					$msgList = ChatLog::model()->getMessagesByBkg($bkgId, $entityType, $isVendor, $isDriver, $isConsumer, $chtId, $chlId);
					//Logger::create('Query DATA ===========>: \n\t\t' . var_dump($msgList) . " == " . $userInfo->userType, CLogger::LEVEL_TRACE);
					if(count($msgList) > 0)
					{
						$success = true;
					}
				}
				catch(Exception $e)
				{
					Logger::create("Errors.\n\t\t" . $e->getMessage(), CLogger::LEVEL_ERROR);
					$errors = "Errors is :" . $e->getMessage();
					$transaction->rollback();
				}
			}
			else
			{
				$success = false;
				$errors	 = "Vendor Unauthorised";
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'		 => $success,
					'errors'		 => $errors,
					'isChatLoaded'	 => (($chtId > 0) ? true : false),
					'msgList'		 => $msgList
				)
			]);
		});
		// Save voucher for vendor
		$this->onRest('req.post.saveVoucherDocs.render', function () {

			Logger::create('64 saveVoucherDocs ', CLogger::LEVEL_TRACE);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if($result == true)
			{
				$success = false;
				$errors	 = [];

				//$voucher_type		 = Yii::app()->request->getParam('voucher_type');
				//$bkg_id				 = Yii::app()->request->getParam('bkg_id');

				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				$voucher_type		 = $data1['voucher_type'];
				$bkg_id				 = $data1['bkg_id'];

				$userId				 = UserInfo::getUserId();
				$vendorId			 = UserInfo::getEntityId();
				//			$voucher_type		 = 2;//Yii::app()->request->getParam('voucher_type');
				//			$bkg_id				 = 473079;//Yii::app()->request->getParam('bkg_id');
				//			$vendor_id			 = 43;//Yii::app()->user->getId();
				$voucher_file		 = $_FILES['voucher']['name'];
				$voucher_file_tmp	 = $_FILES['voucher']['tmp_name'];
				$voucher_file_size	 = $_FILES['voucher']['size'];
				$process_sync_data	 = $voucher_type . "," . $bkg_id . "," . $voucher_file;
				Logger::create('POST DATA ===========>: \n\t\t' . $process_sync_data, CLogger::LEVEL_TRACE);
				$transaction		 = DBUtil::beginTransaction();
				try
				{
					$appToken	 = AppTokens::model()->getByUserTypeAndUserId($userId, 2);
					$result		 = $this->saveBookingVendor($voucher_file, $voucher_file_tmp, $bkg_id, $voucher_type);
					if($result['path'] == '')
					{
						$error = "Voucher upload failed. Try again.";
						throw new Exception($error);
					}
					$params					 = ['bkg_id' => $bkg_id, 'voucher_type' => $voucher_type, 'voucher_path' => $result['path'], 'device_id' => $appToken['apt_device_uuid']];
					/* @var $model BookingPayDocs */
					$model					 = new BookingPayDocs();
					$model->bpay_date		 = new CDbExpression('NOW()');
					$model->bpay_bkg_id		 = $params['bkg_id'];
					$model->bpay_type		 = $params['voucher_type'];
					$model->bpay_image		 = $params['voucher_path'];
					$model->bpay_device_id	 = $params['device_id'];
					$model->bpay_status		 = 1;
					if($model->validate())
					{
						if($model->save())
						{
							$userInfo		 = UserInfo::getInstance();
							$voucherTypeName = $model->getTypeByVoucherId($model->bpay_type);
							$desc			 = "Voucher Uploaded -->" . $voucherTypeName;
							$success		 = BookingLog::model()->createLog($bkg_id, $desc, $userInfo, BookingLog::VOUCHER_UPLOAD);
							if($success == true)
							{
								$success = DBUtil::commitTransaction($transaction);
							}
							else
							{
								$getErrors = "Errors in booking log.";
								throw new Exception($getErrors);
							}
							if($success)
							{
								$errors		 = [];
								$returnData	 = ['bkg_id' => (int) $bkg_id, 'voucher_type' => (int) $voucher_type, 'voucher_path' => $result['path'], 'bpay_id' => $model['bpay_id']];
							}
						}
					}
					else
					{
						$getErrors = $model->getErrors();
						throw new Exception("Validation errors :: " . $getErrors);
					}
				}
				catch(Exception $ex)
				{
					DBUtil::rollbackTransaction($transaction);
					$errors = $getErrors;
				}
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'data'		 => ($success) ? $returnData : ''
				)
			]);
			//Remove voucher 
		});

		$this->onRest('req.post.listVendorDocs.render', function () {
			$success = false;
			$errors	 = [];
			//$bkg_id			 = Yii::app()->request->getParam('bkg_id');

			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$bkg_id				 = $data1['bkg_id'];

			$vendorDoclist = BookingPayDocs::model()->getVendorDocList($bkg_id);
			if($vendorDoclist)
			{
				$success = true;
				$errors	 = [];
				$docList = $vendorDoclist;
			}
			else
			{
				$success = false;
				$errors	 = 'No document found';
				$docList = "";
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'data'		 => ($success) ? $docList : ''
				)
			]);
		});

		$this->onRest('req.post.removeVendorDocs.render', function () {

			Logger::create('65 removeVendorDocs ', CLogger::LEVEL_TRACE);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if($result == true)
			{
				$success = false;
				$errors	 = [];

				//$bpay_id					 = Yii::app()->request->getParam('bpay_id');
				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				$bpay_id			 = $data1['bpay_id'];

				$removeVoucher				 = BookingPayDocs::model()->findByPk($bpay_id);
				$removeVoucher->bpay_status	 = 0;
				if($removeVoucher->save())
				{
					unlink(Yii::app()->basePath . "/../" . $removeVoucher->bpay_image);
					$success = true;
					$errors	 = [];
				}
			}
			else
			{
				$success = false;
				$errors	 = "Vendor Unauthorised";
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors
				)
			]);
		});

		// save voucher from vendor end

		$this->onRest('req.post.saveVendorVoucher.render', function () {
			$success	 = false;
			$message	 = [];
			$userInfo	 = UserInfo::getInstance();
			//$voucher_type		 = Yii::app()->request->getParam('voucher_type');
			//$bkg_id				 = Yii::app()->request->getParam('bkg_id');

			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$voucher_type		 = $data1['voucher_type'];
			$bkg_id				 = $data1['bkg_id'];

			$vendor_id = $userInfo->userId;

			$voucher_file		 = $_FILES['voucher']['name'];
			$voucher_file_tmp	 = $_FILES['voucher']['tmp_name'];
			$voucher_file_size	 = $_FILES['voucher']['size'];
			$process_sync_data	 = "Voucher Type : " . $voucher_type . " , BKG ID : " . $bkg_id . ", Voucher FILE : " . $voucher_file . " , Vendor Id : " . $vendor_id;
			//print_r($process_sync_data);exit;
			Logger::create('saveVendorVoucher get data: ' . $process_sync_data, CLogger::LEVEL_TRACE);

			$transaction = DBUtil::beginTransaction();
			try
			{
				$model	 = new BookingPayDocs();
				//$result = BookingPayDocs::model()->saveBookingVendor($voucher_file, $voucher_file_tmp, $bkg_id, $voucher_type);
				$result	 = $this->saveBookingVendor($voucher_file, $voucher_file_tmp, $bkg_id, $voucher_type);
				Logger::create("Upload Data.\n\t\t" . $result, CLogger::LEVEL_INFO);
				if($vendor_id == '' || $vendor_id == NULL)
				{
					$getErrors = "Vendor ID not found.";
					throw new Exception($getErrors);
				}
				$appToken = AppTokens::model()->getByUserTypeAndUserId($vendor_id, 2);
				if($result['path'] == '')
				{
					$getErrors = "Voucher upload failed. Try again.";
					throw new Exception($getErrors);
				}
				$params					 = ['bkg_id' => $bkg_id, 'voucher_type' => $voucher_type, 'voucher_path' => $result['path'], 'vendor_id' => $appToken['apt_device_uuid']];
				$model->bpay_date		 = new CDbExpression('NOW()');
				$model->bpay_bkg_id		 = $params['bkg_id'];
				$model->bpay_type		 = $params['voucher_type'];
				$model->bpay_app_type	 = 2;
				$model->bpay_image		 = $params['voucher_path'];
				$model->bpay_device_id	 = $params['vendor_id'];
				$model->bpay_status		 = 1;

				if($model->validate())
				{
					if($model->save())
					{
						$voucherTypeName = $model->getTypeByVoucherId($model->bpay_type);
						$desc			 = "Voucher Type : " . $voucherTypeName;
						$success		 = BookingLog::model()->createLog($bkg_id, $desc, $userInfo, BookingLog::VOUCHER_UPLOAD, false, false);
						if($success == true)
						{
							$success = DBUtil::commitTransaction($transaction);
							if($success)
							{
								$message	 = [];
								$returnData	 = ['bkg_id' => (int) $bkg_id, 'voucher_type' => (int) $voucher_type, 'voucher_path' => $result['path'], 'bpay_id' => $model['bpay_id']];
								Logger::create("Final Data.\n\t\t" . json_encode($returnData), CLogger::LEVEL_INFO);
							}
						}
						else
						{
							$getErrors = "Errors in booking log.";
							throw new Exception($getErrors);
						}
					}
				}
				else
				{
					$getErrors = $model->getErrors();
					throw new Exception("Validation errors :: " . $getErrors);
				}
			}
			catch(Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				$message = $getErrors;
				Logger::create("Errors.\n\t\t" . $ex->getMessage(), CLogger::LEVEL_ERROR);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $message,
					'data'		 => ($success) ? $returnData : ''
				)
			]);
		});

		$this->onRest('req.post.removeVendorVoucher.render', function () {
			$success			 = false;
			$errors				 = [];
			//$bpay_id		 = Yii::app()->request->getParam('bpay_id');
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$bpay_id			 = $data1['bpay_id'];

			//$bpay_id		 = 238;
			$removeVoucher	 = BookingPayDocs::model()->findByPk($bpay_id);
			$vendorId		 = $removeVoucher->bpayBkg->bkgBcb->bcb_vendor_id;
			$userInfo		 = UserInfo::getInstance();
			Logger::create("removeVendorVoucher data: " . json_encode($userInfo), CLogger::LEVEL_INFO);

			$removeVoucher->bpay_status = 0;
			if($removeVoucher->save())
			{

				$bkg_id			 = $removeVoucher->bpay_bkg_id;
				$voucherTypeName = BookingPayDocs::model()->getTypeByVoucherId($removeVoucher->bpay_type);
				$desc			 = "Voucher Type : " . $voucherTypeName;
				$success		 = BookingLog::model()->createLog($bkg_id, $desc, $userInfo, BookingLog::VOUCHER_DELETED, false, false);

				//unlink(Yii::app()->basePath . "/../" . $removeVoucher->bpay_image);
				//$success = true;
				$errors = [];
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors
				)
			]);
		});

		/* @deprecated 
		 * new function tripComplete
		 */
		$this->onRest('req.post.vendor_trip_complete.render', function () {

			Logger::create('24 vendor trip complete ', CLogger::LEVEL_TRACE);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if($result)
			{
				//$bkg_id				 = Yii::app()->request->getParam('bkg_id');
				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				$bkg_id				 = $data1['bkg_id'];

				$success = Booking::model()->tripMarkComplete($bkg_id);
				if(!$success['success'])
				{
					$errors = "Something went wrong";
				}
			}
			else
			{
				$success = false;
				$errors	 = "Vendor Unauthorised";
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
				)
			]);
		});

		$this->onRest('req.get.bidStatusList.render', function () {
			return $this->renderJSON($this->bidStatusList());
		});
		$this->onRest('req.post.gnowBidStatusList.render', function () {
			return $this->renderJSON($this->bidStatusList());
		});
	}

	public function vendorTripDetails()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$data = Yii::app()->request->rawBody; //{"bookingId":1566990,"status":"1", id:1478149}   {"bookingId":1566990,"status":"1"}
			if(!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			$vendorId	 = UserInfo::getEntityId();
			$userId		 = UserInfo::getUserId();
			if(!$vendorId)
			{
				throw new Exception("Unauthorized Vendor", ReturnSet::ERROR_INVALID_DATA);
			}
			if(!$userId)
			{
				throw new Exception("Unauthorized User", ReturnSet::ERROR_INVALID_DATA);
			}
			$data = Booking::model()->getTripDetails1($jsonObj->bookingId, $jsonObj->status, $vendorId, 1);

			if(!$data)
			{
				$returnSet->setMessage("No record found.");
			}
			else
			{
				foreach($data as $res)
				{
					$model					 = Booking::model()->findByPk($res['bkg_id']);
					/* @var $response \Stub\vendor\TripDetailsResponse */
					$res					 = new \Stub\vendor\TripDetailsResponse();
					$res->setData($model);
					$returnSet->setStatus(true);
					$response				 = Filter::removeNull($response);
					$responsedt->dataList[]	 = $res;
				}
				$response = $responsedt;
				$returnSet->setData($response);
			}
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function saveBookingVendor($image, $imagetmp, $bkgId, $type)
	{
		try
		{
			$path = "";
			if($image != '')
			{
				$image = $bkgId . "-" . $type . "-" . date('YmdHis') . "." . $image;

				$dir = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
				if(!is_dir($dir))
				{
					mkdir($dir);
				}
				$dirFolderName = $dir . DIRECTORY_SEPARATOR . 'bookings';
				if(!is_dir($dirFolderName))
				{
					mkdir($dirFolderName);
				}
				$dirByBookingId = $dirFolderName . DIRECTORY_SEPARATOR . $bkgId;
				if(!is_dir($dirByBookingId))
				{
					mkdir($dirByBookingId);
				}
				$file_path = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'bookings' . DIRECTORY_SEPARATOR . $bkgId;

				$file_name	 = basename($image);
				$f			 = $file_path;
				$file_path	 = $file_path . DIRECTORY_SEPARATOR . $file_name;

				file_put_contents(PUBLIC_PATH . '/testFile.txt', $f . ' ==== ' . $file_name);
				Yii::log("Image Path: \n\t Temp: " . $imagetmp . "\n\t Path: " . $f, CLogger::LEVEL_INFO, 'system.api.images');
				if(Vehicles::model()->img_resize($imagetmp, 3500, $f, $file_name))
				{
					$path	 = substr($file_path, strlen(PUBLIC_PATH));
					$result	 = ['path' => $path];
				}
			}
		}
		catch(Exception $e)
		{
			Yii::log("Exception thrown: \n\t" . $e->getTraceAsString(), CLogger::LEVEL_ERROR, "system.api.images");
			Yii::log("Data Received: \n\t" . serialize(filter_input_array(INPUT_POST)), CLogger::LEVEL_ERROR, "system.api.images");
			throw $e;
		}
		return $result;
	}

	public function vendorAssignDriver()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data = Yii::app()->request->rawBody;
			if(!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			$obj		 = $jsonMapper->map($jsonObj, new \Stub\vendor\Assign());
			$model		 = $obj->getData();
			/** @var BookingCab $cabmodel */
			$cabmodel	 = BookingCab::model()->findByPk($model->bcb_id);

			if(empty($cabmodel))
			{
				$cabmodel->event_by			 = 2;
				$cabmodel->bcb_cab_id		 = $model->bcb_cab_id;
				$cabmodel->bcb_driver_phone	 = $model->bcb_driver_phone;
				$cabmodel->bcb_driver_id	 = $model->bcb_driver_id;

				$bookingId		 = $cabmodel->bcb_bkg_id1;
				$bookingmodel	 = Booking::model()->findByPk($bookingId);

				$vehicleTypeId			 = $cabmodel->bookings[0]->bkg_vehicle_type_id;
				$cabtypeModel			 = SvcClassVhcCat::model()->findByPk($vehicleTypeId);
				$cabmodel->chk_user_msg	 = array(0, 1);  // sms for user and driver

				$success = $cabmodel->assigncabdriver($model->bcb_cab_id, $model->bcb_driver_id, $cabtypeModel->scv_vct_id, UserInfo::getInstance());

				if($success)
				{
					$returnSet->setStatus(true);
					$data = array('bookingCabId' => $cabmodel->bcb_cab_id);
					$returnSet->setData($data);
				}
				else
				{
					$returnSet->setStatus(false);
					$errors = $cabmodel->getErrors();
					if(!empty($errors['bcb_cab_id']))
					{
						//$returnSet->setMessage($errors['bcb_cab_id'][0]);
						throw new Exception($errors['bcb_cab_id'][0], ReturnSet::ERROR_FAILED);
					}
					else if(!$errors['bcb_vendor_id'])
					{
						//$returnSet->setMessage($errors['bkg_driver_cab_message'][0]);
						throw new Exception($errors['bcb_vendor_id'][0], ReturnSet::ERROR_FAILED);
					}
					else
					{
						throw new Exception($errors['bkg_driver_cab_message'][0], ReturnSet::ERROR_FAILED);
					}
				}
			}
			else
			{
				throw new Exception("Booking no longer active", ReturnSet::ERROR_FAILED);
			}
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function vendorUnassign()
	{
		$returnSet = new ReturnSet();

		$data = Yii::app()->request->rawBody;
		if(!$data)
		{
			throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
		}
		try
		{

			//$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			$bcb_id		 = $jsonObj->bcbId;
			$reason		 = $jsonObj->reason;
			$modelCab	 = Booking::model()->getDetailbyId($bcb_id);
			$pickupDt	 = $modelCab[0]['pickup_datetime'];
			$bkgId1		 = $modelCab[0]['bkg_id'];
			$dateDiff	 = $modelCab[0]['diff'];
			if($dateDiff <= 30)
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage("FAIL!! Cannot reject at last minute. Contact support.");
			}
			else
			{
				$bookingCabModel				 = BookingCab::model()->findByPk($bcb_id);
				$model							 = Booking::model()->findByPk($bkgId1);
				$reasonId						 = 7;
				$modelcab->bcb_denied_reason_id	 = $reasonId;
				$result							 = Booking::model()->canVendor($bcb_id, $reason, UserInfo::getInstance(), [$bkgId1], $reason);
				if($result['success'])
				{
					$bookingCabModel->bcb_denied_reason_id	 = $reasonId;
					$vendorId								 = $bookingCabModel->bcb_vendor_id ? $bookingCabModel->bcb_vendor_id : $user_id;

					$firstBookingID	 = $modelCab[0]['bkg_id'];
					$bookingModel	 = Booking::model()->findByPk($firstBookingID);
					if($dateDiff >= 119)
					{

						$bookingModel->bkgPref->bkg_autocancel = 1;
						$bookingModel->bkgPref->save();
					}

					if(!$bookingCabModel->save())
					{
						$success1				 = false;
						$message				 = "Failed to update BookingCab Model";
						$errors['checktime'][]	 = $bookingCabModel->getErrors();
						$transaction->rollback();
					}

					$bookingCabModel->save();

					$vendorId							 = Ratings::model()->getVendorIdByBookingId($modelCab[0]['bkg_id']);
					$vendorProfile						 = new VendorProfile();
					$vendorProfile->vnp_user_id			 = $vendorId['vnd_id'];
					$vendorProfile->vnp_booking_id		 = $modelCab[0]['bkg_id'];
					$vendorProfile->vnp_attribute_type	 = VendorProfile::TYPE_DENIALS;
					$vendorProfile->vnp_value_str		 = $reason;
					$vendorProfile->vnp_value_int		 = $bcb_id;
					$vendorProfile->save();

					$dateDiff							 = round((strtotime($modelCab[0]['pickup_datetime']) - strtotime(date('Y-m-d H:i:s'))) / (60 * 60));
					$vendorProfile						 = new VendorProfile();
					$vendorProfile->vnp_user_id			 = $vendorId['vnd_id'];
					$vendorProfile->vnp_booking_id		 = $modelCab[0]['bkg_id'];
					$vendorProfile->vnp_attribute_type	 = VendorProfile::TYPE_DENIALS_TO_PICKUP;
					$vendorProfile->vnp_value_str		 = 'Denials to Pickup';
					$vendorProfile->vnp_value_int		 = $dateDiff;
					$vendorProfile->save();

					$assignmentTime						 = VendorsLog::model()->getVendorAssignmentTime($modelCab[0]['bkg_id']);
					$assignmentDateTime					 = round((strtotime(date('Y-m-d H:i:s')) - strtotime($assignmentTime['vlg_created'])) / (60 * 60));
					$vendorProfile						 = new VendorProfile();
					$vendorProfile->vnp_user_id			 = $vendorId['vnd_id'];
					$vendorProfile->vnp_booking_id		 = $modelCab[0]['bkg_id'];
					$vendorProfile->vnp_attribute_type	 = VendorProfile::TYPE_DENIALS_FROM_ASSIGNMENT;
					$vendorProfile->vnp_value_str		 = 'Denials to Assignment';
					$vendorProfile->vnp_value_int		 = $assignmentDateTime;
					$vendorProfile->save();
					$returnSet->setStatus(true);
					$data								 = array('bcb_cab_id' => $bcb_id);
					$returnSet->setData($data);
					$returnSet->setMessage("Booking unassigned. [WARNING: Too many trips rejected after accept. Your partner rating is falling. No penalty applied. Account may get blocked if trip rejection continues]");
				}
				else
				{

					throw new Exception(json_encode($result["errors"]));
				}
			}
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function customerNoShow()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		Vendors::model()->authoriseVendor($token);
		try
		{
			$data = Yii::app()->request->rawBody;
			if(!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			$jsonObj	 = CJSON::decode($data, false);
			$vendorId	 = UserInfo::getEntityId();
			$userId		 = UserInfo::getUserId();
			$bkg_id		 = $jsonObj->bookingId;
			if(!$vendorId)
			{
				throw new Exception("Unauthorized Vendor.", ReturnSet::ERROR_UNAUTHORISED);
			}
			if(!$userId)
			{
				throw new Exception("Unauthorized User.", ReturnSet::ERROR_UNAUTHORISED);
			}
			$model		 = Booking::model()->findByPk($bookingId);
			$oldModel	 = $model;
			$userInfo	 = UserInfo::getInstance();
			if($model->bkgTrack->bkg_is_no_show == 1)
			{
				throw new Exception("Customer was already marked no-show", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
			}
			$model->bkgTrack->bkg_is_no_show = 1;
			if($model->bkgPref->save())
			{
				$customerProfile					 = new CustomerProfile();
				$customerProfile->csp_user_id		 = $model->bkgUserInfo->bkg_user_id;
				$customerProfile->csp_booking_id	 = $bookingId;
				$customerProfile->csp_attribute_type = CustomerProfile::TYPE_NO_SHOW;
				$customerProfile->csp_value_str		 = 'No Show';
				$customerProfile->csp_value_int		 = $model->bkg_bcb_id;
				$customerProfile->save();

				$success						 = true;
				$errors							 = [];
				$eventId						 = BookingLog::NO_SHOW;
				$desc							 = "Marked customer no-show";
				$params['blg_booking_status']	 = $model->bkg_status;
				BookingLog::model()->createLog($bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
				$eventCode						 = BookingTrack::NO_SHOW;
				BookingTrackLog::model()->add(2, $bkg_id, $eventCode);

				$returnSet->setStatus(true);
				$returnSet->setMessage($desc);
			}
			else
			{
				throw new Exception("Something went wrong.", ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function vendorAccountTrip()
	{
		$returnSet = new ReturnSet();

		$data = Yii::app()->request->rawBody;
		if(!$data)
		{
			throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
		}
		else
		{
			Logger::create("Request =>" . $data, CLogger::LEVEL_TRACE);
			$vendorId	 = UserInfo::getEntityId();
			$jsonObj	 = CJSON::decode($data, false);
			$date1		 = $jsonObj->date1;
			$date2		 = $jsonObj->date2;
			$flag		 = $jsonObj->viewFlag;

			$newDate1	 = ($date1 != '') ? date('Y-m-d', strtotime($date1)) : date('Y-m-d', strtotime("-30 days"));
			$newDate2	 = ($date2 != '') ? date('Y-m-d', strtotime($date2)) : date('Y-m-d');
			if($flag == 2)
			{
				$resultset	 = AccountTransDetails::vendorTransactionList1($vendorId, $newDate1, $newDate2, '1');
				$tripArr	 = $resultset->readAll();
			}
			else
			{
				$tripArr = AccountTransDetails::vendorTransactionList($vendorId, $newDate1, $newDate2, '1');
			}

			$vendorAmount					 = AccountTransDetails::model()->calAmountByVendorId($vendorId);
			$vendorAmount['securyAmount']	 = AccountTransDetails::model()->calAmntByVendorReffBoth($vendorId);
			$response						 = new \Stub\vendor\AccountTripResponse();
			$response->setData($tripArr, $vendorAmount);
			$response						 = Filter::removeNull($response);
			$returnSet->setStatus(true);
			$returnSet->setData($response);
		}
		return $returnSet;
	}

	public function bookingDetails()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data = Yii::app()->request->rawBody;
			if(!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$vendorId	 = UserInfo::getEntityId();
			$userId		 = UserInfo::getUserId();
			if(!$vendorId)
			{
				throw new Exception("Unauthorized Vendor", ReturnSet::ERROR_UNAUTHORISED);
			}
			if(!$userId)
			{
				throw new Exception("Unauthorized User", ReturnSet::ERROR_UNAUTHORISED);
			}
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			$model		 = Booking::model()->getDetailbyId($jsonObj->tripId);
			if(!$model)
			{
				throw new Exception("No Record Found.", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$response	 = new \Stub\booking\BookingDetailsResponse();
			$response->setData($model[0]);
			$response	 = Filter::removeNull($response);
			$returnSet->setStatus(true);
			$returnSet->setData($response);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function changeRideStatus()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data = Yii::app()->request->rawBody;
			if(!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			$obj		 = $jsonMapper->map($jsonObj, new \Stub\vendor\ChangeRideStatus());
			$modelData	 = $obj->getModel();
			$model		 = Booking::model()->findByPk($modelData->bkg_id);
			$bkg_id		 = $model->bkg_id;
			if(!$model)
			{
				throw new Exception("No Record Found.", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			$pickupDate = $model->bkg_pickup_date;

			$estimateStart		 = date("Y-m-d H:i:s", strtotime($pickupDate . "-30 minutes"));
			$nowTime			 = date("Y-m-d H:i:s");
			$showStartTime		 = date("jS F, Y g:i A", strtotime($estimateStart));
			$estimateComplete	 = date("Y-m-d H:i:s", strtotime($pickupDate . "+ $model->bkg_trip_duration minutes"));
			if($estimateStart > $nowTime)
			{
				$errors = "Trip can be started after" . $showStartTime;
				throw new Exception("Trip can be started after", ReturnSet::ERROR_INVALID_DATA);
			}
			if($model->bkgTrack->bkg_ride_start == 1)
			{
				$errors = "Trip is already started";
				throw new Exception($errors, ReturnSet::ERROR_INVALID_DATA);
			}
			if($jsonObj->flag == 0 && $estimateComplete > $nowTime)
			{
				$platform		 = TripOtplog::Platform_PARTNERAPP;
				$bookingTrack	 = $model->bkgTrack;
				$returnSet		 = $bookingTrack->startTrip($platform, $modelData->bkgTrack->bkg_trip_otp);
				$success		 = $returnSet->setStatus(true);
				$returnSet->setMessage($returnSet->getData()['message']);
				$returnSet->setData('');
			}
			else
			{
				$platform		 = TripOtplog::Platform_PARTNERAPP;
				$userInfo		 = UserInfo::getInstance();
				$bookingTrack	 = $model->bkgTrack;
				$returnSet		 = $bookingTrack->startOverDueTrip($bkg_id, $platform, $userInfo);
				$returnSet->getStatus();
				$returnSet->setMessage($returnSet->getData()['message']);
				$returnSet->setData('');
			}
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	public function tripComplete()
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			$data = Yii::app()->request->rawBody;
			if(!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj = CJSON::decode($data, false);
			$bkg_id	 = $jsonObj->bookingId;
			$success = Booking::model()->tripMarkComplete($bkg_id);
			if($success == false)
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage('Error! Trip not marked as complete');
			}
			else
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage('Trip marked complete');
			}
			DBUtil::commitTransaction($transaction);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	public function bookingCategory()
	{
		$returnSet	 = new ReturnSet();
		$dataObj	 = new Stub\common\Booking();
		$dataObj	 = $dataObj->setBookingCategory();
		#print_r($dataObj);
		$response	 = Filter::removeNull($dataObj);
		if(!$response)
		{
			throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
		}
		$returnSet->setStatus(true);
		$returnSet->setData($response);
		return $returnSet;
	}

	/**
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function uploadVendorVoucher()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			AppTokens::validateToken($token);
			Logger::create("Token : " . $token, CLogger::LEVEL_INFO);
			$data = Yii::app()->request->rawBody;
			if(!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Vendor Voucher Details Request : " . $data, CLogger::LEVEL_INFO);
			$jsonObj			 = CJSON::decode($data, false);
			$userInfo			 = UserInfo::getInstance();
			$userInfo->userId	 = UserInfo::getUserId();
			$userInfo->userType	 = UserInfo::getUserType();
			$userInfo->platform	 = 2;
			$jsonMapper			 = new JsonMapper();
			$response			 = [];
			foreach($jsonObj as $event)
			{
				/** @var \Stub\booking\SyncRequest $obj */
				$voucherDt				 = new \Stub\booking\SyncRequest();
				$obj					 = $jsonMapper->map($event, $voucherDt);
				$eventModel				 = $obj->getModel($userInfo);
				$eventResponse			 = $eventModel->handleEvents();
				$res					 = new \Stub\booking\SyncResponse();
				$res->setData($eventResponse, $eventModel);
				$responsedt->dataList[]	 = $res;
			}
			$response	 = $responsedt;
			$data		 = Filter::removeNull($response);
			if(!$response->dataList[0]->status)
			{
				$returnSet->setMessage("Voucher already uploaded.");
			}
			$returnSet->setStatus(true);
			$returnSet->setData($data);
			Logger::create("Vendor Voucher Details Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function saveVendorVoucherFile()
	{
		$returnSet = new ReturnSet();
		try
		{
			$image				 = $_FILES['img1']['name'];
			$imagetmp			 = $_FILES['img1']['tmp_name'];
			$result				 = [];
			$userInfo			 = UserInfo::getInstance();
			$userInfo->userId	 = empty($userInfo->userId) ? 123 : $userInfo->userId;
			$userInfo->userType	 = UserInfo::getUserType(); //Vendor type = 2
			$userInfo->platform	 = 2; //Platform type =2
			$jsonMapper			 = new JsonMapper();
			Logger::create("Vendor Voucher File Request : " . $imagetmp, CLogger::LEVEL_INFO);
			if($image != '')
			{
				$fileChecksum	 = md5_file($_FILES['img1']['tmp_name']);
				$dataDetails[]	 = BookingTrack::model()->uploadFiles($image, $imagetmp, $fileChecksum, $userInfo->userType, $userInfo->platform);
			}
			$response = [];
			foreach($dataDetails as $res)
			{
				$result					 = $res['model'];
				$message				 = $res['message'];
				$appSyncId				 = $res['appSyncId'];
				$res					 = new \Stub\common\Document();
				$res->setDocModelData($result, $appSyncId, $message);
				$responsedt->dataList[]	 = $res;
			}
			$response	 = $responsedt;
			$data		 = Filter::removeNull($response);
			$returnSet->setStatus(true);
			$returnSet->setData($data);
			Logger::create("Vendor Voucher File Response  : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function removeVoucher()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			AppTokens::validateToken($token);
			Logger::create("Token : " . $token, CLogger::LEVEL_INFO);
			$data = Yii::app()->request->rawBody;
			if(!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Remove Vendor Voucher Details Request : " . $data, CLogger::LEVEL_INFO);
			$userInfo			 = UserInfo::getInstance();
			$userId				 = $userInfo->userId	 = empty($userInfo->userId) ? 123 : $userInfo->userId;
			if(!$userId)
			{
				throw new Exception("Invalid User.", ReturnSet::ERROR_UNAUTHORISED);
			}
			$userInfo->userType	 = UserInfo::getUserType(); //Vendor type = 2
			$userInfo->platform	 = 2; //Platform type =2
			$jsonObj			 = CJSON::decode($data, false);
			$jsonMapper			 = new JsonMapper();
			/** @var $obj \Stub\vendor\RemoveVoucherRequest */
			$obj				 = $jsonMapper->map($jsonObj, new \Stub\vendor\RemoveVoucherRequest());
			$model				 = $obj->getModel($model);
			$result				 = BookingPayDocs::model()->removeVoucher($model->btl_appsync_id, $model->payDocModel->bpay_bkg_id, $userInfo->platform, $model->payDocModel->bpay_checksum);
			$message			 = $result['message'];
			if(!$result['success'])
			{
				throw new Exception($message, ReturnSet::ERROR_FAILED);
			}
			$returnSet->setStatus(true);
			$returnSet->setMessage($message);
			Logger::create("Vendor Voucher File Remove Response  : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function getDestinationNoteList()
	{
		$returnSet = new ReturnSet();
		//$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			//AppTokens::validateToken($token);
			$data = Yii::app()->request->rawBody;
			if(!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj = CJSON::decode($data, false);
			$bkg_id	 = $jsonObj->bookingId;
			if($bkg_id == "")
			{
				$trip_id = $jsonObj->tripId;
				$bkgArr	 = BookingCab::model()->getBkgIdByTripId($trip_id);
				$bkg_id	 = $bkgArr['bkg_ids'];
			}

			if($bkg_id == "")
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}

			Logger::create("Show Detination Request : " . $data, CLogger::LEVEL_INFO);
			$userInfo	 = UserInfo::getInstance();
			$userId		 = $userInfo->userId;
			//$userId				 = $userInfo->userId	 = empty($userInfo->userId) ? 123 : $userInfo->userId;
			if(!$userId)
			{
				throw new Exception("Invalid User.", ReturnSet::ERROR_UNAUTHORISED);
			}
			$userInfo->userType	 = UserInfo::getUserType(); //Vendor type = 2
			$userInfo->platform	 = 2; //Platform type =2	

			$noteArrList = DestinationNote::model()->showNoteApi($bkg_id, $userInfo->userType);
			$response	 = [];
			$jsonMapper	 = new JsonMapper();
			if($noteArrList != false)
			{
				/** @var $res \Stub\common\DestinationNote */
				$res		 = new \Stub\common\DestinationNote();
				$responseDt	 = $res->getData($noteArrList);
			}
			foreach($responseDt as $res)
			{
				$responsedt->dataList[] = $res;
			}
			$response = $responsedt;
			//print_r($response);
			if(!empty($response))
			{
				$returnSet->setData($response);
			}
			else
			{
				$returnSet->setMessage("No Travel advisories found.");
			}

			$returnSet->setStatus(true);

			Logger::create("Show Detination Response  : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function addDestinationNoteList()
	{
		$returnSet = new ReturnSet();
		try
		{

			$data = Yii::app()->request->rawBody;
			if(!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);

			/** @var \Stub\common\DestinationNote $obj */
			$obj					 = $jsonMapper->map($jsonObj, new Stub\common\DestinationNote());
			Logger::profile("Request Mapped");
			/** @var DestinationNote $model */
			$model					 = $obj->getModel();
			$userInfo				 = UserInfo::getInstance();
			$model->dnt_created_by	 = UserInfo::getUserId();

			$model->dnt_created_by_role	 = 2;
			$fromDate					 = $model->dnt_valid_from_date;
			$fromTime					 = $model->dnt_valid_from_time;
			$model->dnt_valid_from		 = $fromDate . ' ' . $fromTime;

			$toDate				 = $model->dnt_valid_to_date;
			$toTime				 = $model->dnt_valid_to_time;
			$model->dnt_valid_to = $toDate . ' ' . $toTime;

			$model->scenario = 'addValid';
			$errors			 = CActiveForm::validate($model);
			if($errors != "[]")
			{
				$errors = $model->getErrors();
				throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
			}
			if($model->save())
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Destination note added");
			}
		}
		catch(Exception $ex)
		{
			$returnSet	 = ReturnSet::setException($ex);
			$errorMsg	 = "Please enter valid data and try again";
			$returnSet->setMessage($errorMsg);
		}

		return $returnSet;
	}

	public function showDestinationArea()
	{
		$returnSet = new ReturnSet();
		//$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{


			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data				 = CJSON::decode($process_sync_data, true);
			$areaType			 = $data['areaType'];
			$searchTxt			 = $data['search_txt'];

			$showArr = DestinationNote::model()->getAreaList($areaType, $searchTxt);

			$res					 = new \Stub\common\DestinationNote();
			$res->setAreaData($showArr);
			$responsedt->dataList	 = $showArr;
			$response				 = $responsedt;
			$data					 = Filter::removeNull($response);
			$returnSet->setStatus(true);
			$returnSet->setData($data);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function showBidRank()
	{
		$returnSet = new ReturnSet();

		try
		{
			//$process_sync_data	 = Yii::app()->request->getParam('data');
			$process_sync_data	 = Yii::app()->request->rawBody;
			$data				 = CJSON::decode($process_sync_data, true);

			$tripId		 = $data['id'];
			$getBkgId	 = BookingCab::getBkgIdByTripId($tripId);
			$bookingIds	 = explode(",", $getBkgId[bkg_ids]);
			$bookingId	 = $bookingIds[0];

			$vendorId	 = UserInfo::getEntityId();
			$showArr	 = BookingVendorRequest::showBidRank($bookingId, $vendorId);

			if(!empty($showArr))
			{

				$returnSet->setStatus(true);
				$returnSet->setMessage($showArr);
			}
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	  @deprecated
	 * New Function vendorPendingRequestV2
	 */
	public function vendorPendingRequest()
	{

		$returnSet		 = new ReturnSet();
		$processSyncdata = Yii::app()->request->rawBody;

		//$processSyncdata = Yii::app()->request->rawBody;
		if(!$processSyncdata)
		{
			throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
		}
		else
		{
			try
			{
				$jsonMapper	 = new JsonMapper();
				$jsonObj	 = CJSON::decode($processSyncdata, false);
				$obj		 = $jsonMapper->map($jsonObj, new Stub\vendor\VendorPendingRequest());
				$model		 = $obj->setData();

				//$vendorId		 = Yii::app()->user->getId();
				$vendorId		 = UserInfo::getEntityId();
				$versionCheck	 = VendorAgreement::model()->checkVersionStatusByVndId($vendorId);
				$total_count	 = 0;
				$offSetCount	 = 30;
				//$page_number	 = ($page_no > 0) ? $page_no : 0;
				$success		 = false;
				$page_number	 = $model->page;
				$page_number	 = ($page_number > 0 ? ($page_number + 1) : 1);

				if($versionCheck > 0)
				{

					$vendorModel = BookingVendorRequest::getPendingRequest($vendorId, $page_number, $model, $offSetCount);
					if($model->bidStatus == 2)
					{

						foreach($vendorModel as $k => $vendor)
						{

							if($vendor['bvr_bid_amount'] != 0)
							{

								unset($vendorModel[$k]);
							}
						}
					}


					$total_data	 = BookingVendorRequest::getPendingRequest($vendorId, 0, $model, 0);
					$count		 = count($total_data);

					//$count = count($vendorModel);
					if($count != 0)
					{
						$pageCount = ceil($count / $offSetCount);
					}

					if($vendorModel != [])
					{
						$response = new \Stub\vendor\VendorPendingRequest();

						$responsedt = $response->getData($vendorModel);

						$data				 = Filter::removeNull($responsedt);
						$data->pageSize		 = (int) $pageCount;
						$data->totalCount	 = (int) $count;
						$data->versionCheck	 = $versionCheck;
						$returnSet->setStatus(true);
						$returnSet->setData($data);
					}
					else
					{
						$returnSet->setStatus(false);
						$error = "No records found";
						$returnSet->setMessage($error);
					}
				}
				else
				{
					$returnSet->setStatus(true);
					$error = 'Version not matched';
					$returnSet->setMessage($error);
				}
			}
			catch(Exception $e)
			{
				Logger::create("vendor_pending_request error occurred: " . $e->getMessage());
				$error	 = "Something went wrong";
				$success = false;
			}
			return $returnSet;
		}
	}

	public function vendorPendingRequestV2()
	{
		$returnSet		 = new ReturnSet();
		$processSyncdata = Yii::app()->request->rawBody;
		if(!$processSyncdata)
		{
			throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
		}
		else
		{
			try
			{
				$jsonMapper		 = new JsonMapper();
				$jsonObj		 = CJSON::decode($processSyncdata, false);
				$obj			 = $jsonMapper->map($jsonObj, new Stub\vendor\VendorPendingRequest());
				$model			 = $obj->setData();
				$vendorId		 = UserInfo::getEntityId();
				$versionCheck	 = VendorAgreement::model()->checkVersionStatusByVndId($vendorId);
				$offSetCount	 = $model->page_size;
				$success		 = false;
				$page_number	 = $model->page;
				if($versionCheck > 0)
				{
					$vendorModel = BookingVendorRequest::getPendingRequestV2($vendorId, $page_number, $model, $offSetCount);

					if($vendorModel->getRowCount() > 0)
					{
						$response			 = new \Stub\vendor\VendorPendingRequest();
						$responsedt			 = $response->getData($vendorModel, $vendorId, $model);
						$data				 = Filter::removeNull($responsedt);
						$data->versionCheck	 = $versionCheck;

						$returnSet->setStatus(true);
						$returnSet->setData($data);
					}
					else
					{
						$returnSet->setStatus(false);
						$error = "No records found";
						$returnSet->setMessage($error);
					}
				}
				else
				{
					$returnSet->setStatus(true);
					$error = 'Version mismatch';
					$returnSet->setMessage($error);
				}
			}
			catch(Exception $e)
			{
				Logger::create("vendor_pending_request error occurred: " . $e->getMessage());
				$error	 = "Something went wrong";
				$success = false;
			}
			return $returnSet;
		}
	}

	public function bidAcceptGnow()
	{
		$returnSet		 = new ReturnSet();
		$token			 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$result			 = Vendors::model()->authoriseVendor($token);
		$bidModel		 = false;
		$bModel			 = null;
		$error			 = '';
		$formData		 = Yii::app()->request->getParam('data');
		$rawData		 = Yii::app()->request->rawBody;
		$processSyncData = $formData . $rawData;
//			$data			 = CJSON::decode($processSyncData, true);
		Logger::trace('<===Request===>' . $processSyncData);

		$transaction = DBUtil::beginTransaction();

		try
		{
			if(!$result)
			{
				throw new Exception("Unauthorised vendor", ReturnSet::ERROR_UNAUTHORISED);
			}
			if(!$processSyncData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($processSyncData, false);
			/** @var \Stub\vendor\GnowBid $obj */
			$obj		 = $jsonMapper->map($jsonObj, new \Stub\vendor\GnowBid());

			$gnbid = $obj->setData();

			$bcbId			 = $gnbid->tripId; //$data['tripId'];
			$bidAmount		 = ceil($gnbid->bidAmount); //ceil($data['bidAmount']);
			$vendorId		 = UserInfo::getEntityId();
			$driverId		 = $gnbid->driverId;  //$data['driverId'];
			$cabId			 = $gnbid->cabId; // $data['cabId'];
			$reachMinutes	 = $gnbid->reachingAfterMinutes; //$data['reachingAfterMinutes'];
			$drvPhone		 = $gnbid->driverMobile; //$data['driverMobile'];


			/** @var BookingCab $cabModel */
			if($bcbId == '' || $bcbId == 0)
			{
				$error	 = "Invalide data";
				$errorId = 1;
				throw new Exception(json_encode($error), ReturnSet::ERROR_INVALID_DATA);
			}
			if($driverId == '' || $cabId == '')
			{
				$error	 = "Please provide driver and cab details";
				$errorId = 2;
				throw new Exception(json_encode($error), ReturnSet::ERROR_INVALID_DATA);
			}
			$dataRow = BookingVendorRequest::getPreferredVendorbyBooking($bcbId);
			if(isset($dataRow['bvr_vendor_id']) && $dataRow['bvr_vendor_id'] != $vendorId)
			{
				$errorId = 3;
				$error	 = "Booking already assigned to other partner";
				throw new Exception(json_encode($error), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
			}
			$cabModel = BookingCab::model()->findByPk($bcbId);

			if($bidAmount == '' || $bidAmount == 0)
			{
				$errorId = 4;
				$error	 = "Please re-check the bid amount.";
				throw new Exception(json_encode($error), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
			}


			$lastOffer = BookingVendorRequest::getMinimumGNowOfferAmountbyVendor($bcbId, $vendorId);
			if($lastOffer && $lastOffer <= $bidAmount)
			{
				$errorId = 5;
				$error	 = "Current bid is higher than your previous bid(s). Try again.";
				throw new Exception(json_encode($error), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
			}

			$bModels = $cabModel->bookings;
			$bkgId	 = $bModels[0]->bkg_id;

			////Abhishek bhaiya told to restrict the offer amount
			$isAdminGozoNow = ($bModels[0]->bkgPref->bkg_is_gozonow == 2) ? 1 : 0;

			$maxAllowableVndAmt	 = $cabModel->bcb_max_allowable_vendor_amount;
			$maxVndAmt			 = ($maxAllowableVndAmt > 0) ? $maxAllowableVndAmt : $cabModel->bcb_vendor_amount;

			$arrAllowedBids = $cabModel->getMinMaxAllowedBidAmount();
			#if ($maxVndAmt < $bidAmount && $isAdminGozoNow == 1)
			if($arrAllowedBids['minBid'] > $bidAmount)
			{
				$errorId = 6;
				$error	 = "Bid amount is too small. Check your bid.";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}
			if($arrAllowedBids['maxBid'] < $bidAmount)
			{
				$errorId = 7;
				$error	 = "Bid is much higher than other vendors. No chance of winning.";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}

			Filter::parsePhoneNumber($drvPhone, $code, $driverMobile);
			if($driverMobile == '')
			{
				$errorId = 8;
				$error	 = "Please provide valid driver mobile number";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}
			if($reachMinutes == '' || $reachMinutes == 0)
			{
				$errorId = 9;
				$error	 = "Please enter the valid duration by which you will reach";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}

			$dnow = Filter::getDBDateTime();

			$reachingAT = date('Y-m-d H:i:s', strtotime($dnow . '+' . $reachMinutes . ' MINUTE'));

			$params				 = [
				'tripId'			 => $bcbId,
				'bkgId'				 => $bkgId,
				'bidAmount'			 => $bidAmount,
				'isAccept'			 => true,
				'driverId'			 => $driverId,
				'driverMobile'		 => $driverMobile,
				'cabId'				 => $cabId,
				'reachingAtMinutes'	 => $reachMinutes,
				'reachingAtTime'	 => $reachingAT
			];
			/** @var BookingCab $cabModel */
			$cabModel->scenario	 = 'assigncabdriver';

			$cabModel->bcb_driver_phone	 = $driverMobile;
			$cabModel->bcb_cab_id		 = $cabId;
			$cabModel->bcb_driver_id	 = $driverId;
			$cab_type					 = $bModels[0]->bkgSvcClassVhcCat->scv_vct_id;

			if($cabModel->bcbCab->vhc_approved != 1)
			{
				$errorId = 9;
				$error	 = "Cab is not approved";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}

			if(!$cabModel->bcbCab->getVehicleApproveStatus())
			{
				$errorId = 10;
				$error	 = "Cab is freezed";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}

			if(!$cabModel->bcbDriver->getDriverApproveStatus())
			{
				$errorId = 11;
				$error	 = "Driver is not approved";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}
			$vvhcModel = VendorVehicle::model()->findByVndVhcId($vendorId, $cabId);
			if(!$vvhcModel && $vvhcModel->vvhc_active != 1)
			{
				$errorId = 12;
				$error	 = "Cab is not attached with you. Please sign LOU.";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}

			if($cab_type != '')
			{
				$cabModel->pre_cab_type	 = $cab_type;
				$cabModel->post_cab_type = $cabModel->bcbCab->vhcType->vht_VcvCatVhcType->vcv_vct_id;
			}

			Preg_match("/\d*(\d{10})/", $cabModel->bcb_driver_phone, $match);
			if(empty($match))
			{
				$errorId = 13;
				$cabModel->addError('bcb_driver_id', 'Driver Phone No is missing.');
				return false;
			}

			$cabModel->bcb_driver_phone = $match[1];

			$cabModel->bcb_cab_number	 = strtoupper($cabModel->bcbCab->vhc_number);
			$cabModel->bcb_trip_status	 = BookingCab::STATUS_CAB_DRIVER_ASSIGNED;
			$bModels[0]->bkg_status		 = 3;
			$validated					 = $cabModel->validate();
			if(!$validated)
			{
				$errorsList = $cabModel->getErrors();
				throw new Exception(json_encode($errorsList), ReturnSet::ERROR_VALIDATION);
			}

			foreach($bModels as $bModel)
			{
				$bModel->refresh();
				$isVendorUnassigned = BookingLog::isVendorUnAssigned($vendorId, $bModel->bkg_id);
				if($isVendorUnassigned)
				{
					$errorId = 14;
					$error	 = "You were unassigned from / denied this trip before. So you cannot bid on it again.";
					throw new Exception(json_encode($error), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
				}

				if(strtotime($bModel->bkg_pickup_date) + 4500 < strtotime($reachingAT) || strtotime($bModel->bkg_pickup_date) < strtotime($dnow))
				{
					$errorId = 15;
					$error	 = "Oops! Looks like you will not reach the pickup ontime";
					throw new Exception(json_encode($error), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
				}

				if($bModel->bkg_status != 2)
				{
					$errorId = 16;
					$error	 = "Oops! The booking is already taken by another partner. Please be quicker next time";
					throw new Exception(json_encode($error), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
				}
//				if ($bModel->bkgPref->bkg_block_autoassignment == 1)
//				{
//					$errorId = 17;
//					$error	 = "Oops! This booking cannot be direct accepted.";
//					throw new Exception(json_encode($error), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
//				}

				if(!Drivers::checkDriverAvailability($vendorId, $cabModel->bcb_start_time, $cabModel->bcb_end_time))
				{
					$errorId = 19;
					$error	 = "Oops! You have no driver for this booking";
					throw new Exception(json_encode($error), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
				}
				$booking_class = $bModel->bkgSvcClassVhcCat->scc_ServiceClass->scc_id;
				if(!Vehicles::checkVehicleclass($vendorId, $booking_class))
				{
					$errorId = 20;
					$error	 = "Oops! You have no cab matching this booking class";
					throw new Exception(json_encode($error), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
				}
				$chkOutStanding = VendorStats::frozenOutstanding($vendorId);
				if($chkOutStanding > 1500)
				{
					$errorId = 21;
					$error	 = "Oops! Your payment is overdue. Please settle your Gozo accounts.";

					throw new Exception(json_encode($error), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
				}
			}


			$bidModel = BookingVendorRequest::storeGNowRequest($params, $vendorId);

			if($bidModel->bvr_id > 0)
			{

				$drvCntId		 = ContactProfile::getByEntityId($driverId, UserInfo::TYPE_DRIVER);
				$drvCntDetails	 = Contact::getContactDetails($drvCntId);
				$driverName		 = $drvCntDetails['ctt_first_name'] . ' ' . $drvCntDetails['ctt_last_name'];
				if(empty(trim($driverName)))
				{
					$drvDetails	 = Drivers::getDriverInfo($driverId);
					$driverName	 = $drvDetails['drv_name'];
				}
				$cabDetails	 = Vehicles::getDetailbyid($cabId);
				$cabNumber	 = $cabDetails['vhc_number'];
				$desc		 = "Vendor offer received: Bid amount = &#x20B9;$bidAmount, reaching at = $reachingAT, cab number = $cabNumber, driver name = $driverName ($drvPhone)";
				BookingLog::model()->createLog($bModel->bkg_id, $desc, UserInfo::getInstance(), BookingLog::BID_SET, false);
			}
			DBUtil::commitTransaction($transaction);

			$returnSet->setStatus(true);
			$returnSet->setMessage("Request processed successfully");
		}
		catch(Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($e);
		}
		if(!$returnSet->getStatus() && $returnSet->hasErrors() && $cabModel)
		{
			/** @var BookingCab $bcbModel */
			$errors		 = $returnSet->getErrors();
			$errorDesc	 = implode('; ', $errors);
			$cabModel->logFailedVendorAssignment($errorDesc, UserInfo::getInstance(), $vendorId, $bidAmount);
		}

		Logger::trace("<===Response===>" . json_encode($returnSet));
		try
		{
			if(($bidModel->bvr_id > 0) && $bModel->bkgPref->bkg_is_gozonow == 1)
			{
				$result		 = BookingTrail::notifyConsumerForMissedNewGnowOffers($bModel->bkg_id);
				$emailObj	 = new emailWrapper();
				$emailResult = $emailObj->mailGnowOfferReceived($bModel->bkg_id);

				notificationWrapper::customerNotifyBookingForGNow($bidModel);
			}
		}
		catch(Exception $ex)
		{
			Logger::exception($ex);
		}
		return $returnSet;
	}

	public function bidDenyGnow()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$result		 = Vendors::model()->authoriseVendor($token);

		if($result == true)
		{
			$success		 = false;
			$error			 = '';
			$formData		 = Yii::app()->request->getParam('data');
			$rawData		 = Yii::app()->request->rawBody;
			$processSyncData = $formData . $rawData;
			$data			 = CJSON::decode($processSyncData, true);
			Logger::trace('<===Request===>' . $processSyncData);
			$bcbId			 = $data['tripId'];
			$reasonId		 = $data['reasonId'];
			$vendorId		 = UserInfo::getEntityId();
			$userInfo		 = UserInfo::getInstance();

			$transaction = DBUtil::beginTransaction();
			try
			{
				/** @var BookingCab $cabModel */
				$cabModel = BookingCab::model()->findByPk($bcbId);
				if(!$cabModel)
				{
					throw new Exception(json_encode("Invalid trip."), ReturnSet::ERROR_VALIDATION);
				}

				$bkgModel = Booking::model()->getByTripId($bcbId);

				if($bkgModel->getRowCount() == 0)
				{
					throw new Exception(json_encode("This trip is no longer exist."), ReturnSet::ERROR_VALIDATION);
				}

				$bModels = $cabModel->bookings;
				$bkgId	 = $bModels[0]->bkg_id;

				$params		 = [
					'tripId'	 => $bcbId,
					'bkgId'		 => $bkgId,
					'reasonId'	 => $reasonId,
					'isAccept'	 => false
				];
				Logger::trace('<===process Request===>' . CJSON::encode($params));
				$bvrModels	 = BookingVendorRequest::storeGNowRequest($params, $vendorId);

				if($bvrModels)
				{
					$returnSet->setStatus(true);
					$returnSet->setMessage("Request processed successfully");
				}

				$ntlId = NotificationLog::getIdForGozonow($vendorId, $bcbId);
				if($ntlId > 0)
				{
					$ntlDataArr	 = ['id' => $ntlId, 'isRead' => 1];
					$resultData	 = NotificationLog::updateReadNotification($ntlDataArr);
				}

				Logger::trace('<===response===>' . CJSON::encode($returnSet));
				DBUtil::commitTransaction($transaction);
				$desc = "Vendor denied offer";
				BookingLog::model()->createLog($bkgId, $desc, $userInfo, BookingLog::BID_DENY, false);
			}
			catch(Exception $e)
			{
				DBUtil::rollbackTransaction($transaction);
				$returnSet = ReturnSet::setException($e);
			}
		}
		else
		{
			$success = false;
			$error	 = 'Unauthorised vendor';
		}

		return $returnSet;
	}

	public function gnowTripDetails()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$result		 = Vendors::model()->authoriseVendor($token);
		try
		{
			if($result)
			{

				$formData		 = Yii::app()->request->getParam('data');
				$rawData		 = Yii::app()->request->rawBody;
				$processSyncData = $formData . $rawData;
				Logger::trace("<===Requset===>" . $processSyncData);
				$data			 = CJSON::decode($processSyncData, true);
				$tripId			 = $data['trip_id'];
				$status			 = $data['status'];

				$vendorId = UserInfo::getEntityId();

				$dataRow = BookingVendorRequest::getPreferredVendorbyBooking($tripId);
				if(isset($dataRow['bvr_vendor_id']) && $dataRow['bvr_vendor_id'] != $vendorId)
				{
					$error = "Booking already assigned to other vendor";
					throw new Exception(json_encode($error), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
//					$returnSet->setErrors("Booking already assigned to other vendor", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
				}

				$isAccessible = BookingCab::checkVendorTripRelation($tripId, $vendorId);
				if(!$isAccessible)
				{
//				throw new Exception("Not authorised to proceed", ReturnSet::ERROR_UNAUTHORISED);
				}
				$model = Booking::model()->findByAttributes(['bkg_bcb_id' => $tripId]);
//			$model	 = Booking::getGNOwTripDetails($tripId, $status, $vendorId, 1);
				if($model->bkg_status != 2)
				{
					$error = "Booking is not in assignable state";
					throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
				}
				$ntlId = NotificationLog::getIdForGozonow($vendorId, $tripId);
				if($ntlId > 0)
				{
					$ntlDataArr	 = ['id' => $ntlId, 'isRead' => 1];
					$resultData	 = NotificationLog::updateReadNotification($ntlDataArr);
				}

				$countGNowBids		 = BookingVendorRequest::countGnowBid($vendorId);
				$isFirstTimeBidder	 = ($countGNowBids > 0) ? 0 : 1;
				if($model != [])
				{
					$response					 = new \Stub\common\Booking();
					$response->setDetails($model);
					$response->maxServiceFee	 = 199;
					$response->isFirstTimeBidder = $isFirstTimeBidder;

					$noteArrList = \DestinationNote::model()->showNoteApi($model->bkg_id, $showNoteTo	 = 2);
					if($noteArrList != false || $noteArrList != NULL)
					{
						$res		 = new \Stub\common\DestinationNote();
						$responseDt	 = $res->getData($noteArrList);
						foreach($responseDt as $res)
						{
							$responseDt->dataList[] = $res;
						}
					}
					$response->destinationNote = $responseDt;

//				$res1 = new \Stub\vendor\TripDetailsResponse();
//				$res1->setData($model);
					$rtArr = json_decode($model->bkg_route_city_names);

					$response->routeName = implode(' - ', $rtArr);
					$returnSet->setData($response);
					$returnSet->setStatus(true);
				}
				else
				{
					$returnSet->setErrors("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
				}
			}
			else
			{
				$returnSet->setErrors("Unauthorised Vendor", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		catch(Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
//		Logger::trace("<===Response===>" . json_encode($returnSet));
		return $returnSet;
	}

	public function bidStatusList()
	{
		$returnSet = new ReturnSet();

		$token = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$vendorId = UserInfo::getEntityId();

			if(!$vendorId)
			{
				throw new Exception("Unauthorized Vendor", ReturnSet::ERROR_INVALID_DATA);
			}
			$rawData	 = Yii::app()->request->rawBody;
			$filter		 = CJSON::decode($rawData, false);
			$pageSize	 = 20;

			$data = BookingVendorRequest::getBidStatusByVendor($vendorId, $pageSize, $filter);

			if($data->getRowCount() == 0)
			{
				throw new Exception("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$res = new \Stub\vendor\GnowBid();
			$res->getBidList($data);
			$returnSet->setData($res);
			$returnSet->setStatus(true);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function gnowReadyToGo()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$vendorId	 = UserInfo::getEntityId();
			$userInfo	 = UserInfo::getInstance();
			if(!$vendorId)
			{
				throw new Exception("Unauthorized Vendor", ReturnSet::ERROR_INVALID_DATA);
			}
			$rawData		 = Yii::app()->request->rawBody;
			$data			 = CJSON::decode($rawData, true);
			$tripId			 = $data['tripId'];
			$isAccessible	 = BookingCab::checkVendorTripRelation($tripId, $vendorId);
			if(!$isAccessible)
			{
				throw new Exception("Not authorised to proceed", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
			}
			$dataRow = BookingVendorRequest::getPreferredVendorbyBooking($tripId);
			if(isset($dataRow['bvr_vendor_id']) && $dataRow['bvr_vendor_id'] != $vendorId)
			{
				throw new Exception("Booking already assigned to other vendor", ReturnSet::ERROR_VALIDATION);
			}


			$bcabModel	 = BookingCab::model()->findByPk($tripId);
			$bModels	 = $bcabModel->bookings;
			$eventId	 = BookingLog::ONTHEWAY_FOR_PICKUP;
			$desc		 = "Driver is ready to go for pickup";

			foreach($bModels as $bookingModel)
			{
				BookingTrack::updateVendorReadyToPickupConfirmation($bookingModel->bkg_id, 1);
				BookingLog::model()->createLog($bookingModel->bkg_id, $desc, $userInfo, $eventId);
			}
			$returnSet->setStatus(true);
			$returnSet->setMessage('Thank You, Customer is waiting at pickup point');
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function gnowSomeProblemToGo()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$vendorId	 = UserInfo::getEntityId();
			$userInfo	 = UserInfo::getInstance();
			if(!$vendorId)
			{
				throw new Exception("Unauthorized Vendor", ReturnSet::ERROR_INVALID_DATA);
			}
			$rawData		 = Yii::app()->request->rawBody;
			$data			 = CJSON::decode($rawData, true);
			$tripId			 = $data['tripId'];
			$isAccessible	 = BookingCab::checkVendorTripRelation($tripId, $vendorId);
			if(!$isAccessible)
			{
				throw new Exception("Not authorised to proceed", ReturnSet::ERROR_UNAUTHORISED);
			}
			$dataRow = BookingVendorRequest::getPreferredVendorbyBooking($tripId);
			if(isset($dataRow['bvr_vendor_id']) && $dataRow['bvr_vendor_id'] != $vendorId)
			{
				throw new Exception("Booking already assigned to other vendor", ReturnSet::ERROR_VALIDATION);
			}
			$bcabModel	 = BookingCab::model()->findByPk($tripId);
			$bModels	 = $bcabModel->bookings;

			foreach($bModels as $bookingModel)
			{
				BookingTrack::updateVendorReadyToPickupConfirmation($bookingModel->bkg_id, 2);
			}
            
            
//            if($bcabModel->bookings[0]->bkg_cav_id != null && $bcabModel->bookings[0]->bkg_cav_id > 0)
//            {   
//                ##############################FOR FLASH BOOKING #############################################
//                $reason = "Booking unassigned for flash booking";
//                $reasonId = 7;
//                $bkgId = $bcabModel->bookings[0]->bkg_id;
//                BookingCab::unassignFlashBooking($tripId, $bkgId, $reason, $reasonId);
//                ##############################FOR FLASH BOOKING #############################################
//            }
            $returnSet = ServiceCallQueue::autoFURGozoNow($tripId);
            $result					 = $returnSet->getData();
			$result['isNewFollowup'] = true;
			$returnSet->setData($result);
			if($returnSet->getStatus())
			{
				$returnSet->setMessage('Request for call back is generated.');
			}
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function gnowAllocatedBidDetails()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$result		 = Vendors::model()->authoriseVendor($token);
		try
		{
			if(!$result)
			{
				throw new Exception("Unauthorised Vendor", ReturnSet::ERROR_UNAUTHORISED_VENDOR);
			}
			$formData		 = Yii::app()->request->getParam('data');
			$rawData		 = Yii::app()->request->rawBody;
			$processSyncData = $formData . $rawData;
			Logger::trace("<===Requset===>" . $processSyncData);
			$data			 = CJSON::decode($processSyncData, true);
			$tripId			 = $data['trip_id'];

			$vendorId	 = UserInfo::getEntityId();
			$model		 = Booking::model()->findByAttributes(['bkg_bcb_id' => $tripId]);

			if(!$model)
			{
				throw new Exception("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$dataRow = BookingVendorRequest::getPreferredVendorbyBooking($tripId);
			if(isset($dataRow['bvr_vendor_id']) && $dataRow['bvr_vendor_id'] != $vendorId)
			{
				throw new Exception(json_encode("Booking already assigned to other vendor"), ReturnSet::ERROR_VALIDATION);
			}
			if(!in_array($model->bkg_status, [3, 5]))
			{
				throw new Exception(json_encode("Booking is not in assigned state"), ReturnSet::ERROR_VALIDATION);
			}
			$isAccessible = BookingCab::checkVendorTripRelation($tripId, $vendorId);
			if(!$isAccessible)
			{
				throw new Exception("Not authorised to proceed", ReturnSet::ERROR_UNAUTHORISED);
			}
			if($model != [])
			{
				$response = new \Stub\common\Booking();
				$response->setAllocatedGnowData($model);

				$gnowBid			 = new \Stub\vendor\GnowBid();
				$gnowBid->setAllocatedBidData($dataRow);
				$response->bidInfo	 = $gnowBid;
				$rtArr				 = json_decode($model->bkg_route_city_names);

				$response->routeName = implode(' - ', $rtArr);
				$returnSet->setData($response);
				$returnSet->setStatus(true);
			}
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function tripDetailsV2()
	{
		$returnSet = new ReturnSet();

		$data = Yii::app()->request->getParam('data');
		try
		{
			if($data == "")
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			$tripId		 = $jsonObj->trip_id;
			$status		 = $jsonObj->status;

			$vendorId	 = UserInfo::getEntityId();
			$data		 = Booking::getTripDetails1($tripId, $status, $vendorId, 1);
			if(!$data)
			{
				$returnSet->setMessage("No Record Found.");
			}
			else
			{
				$res = new \Stub\vendor\TripDetailsResponse();
				$res->setTripData($data);
				print_r($res);
//				foreach ($data as $res)
//				{
//					$model					 = Booking::model()->findByPk($res['bkg_id']);
//					/* @var $response \Stub\vendor\TripDetailsResponse */
//					$res					 = new \Stub\vendor\TripDetailsResponse();
//					$res->setTripData($model);
//					$returnSet->setStatus(true);
//					$response				 = Filter::removeNull($response);
//					$responsedt->dataList[]	 = $res;
//				}
				$returnSet->setStatus(true);
				#$response = $responsedt;
				$returnSet->setData($res);
			}
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * gnowSnoozeNotification 
	 * @return type
	 * @throws Exception
	 */
	public function bidSnoozeGnow()
	{
		$returnSet	 = new ReturnSet();
		$rawData	 = Yii::app()->request->rawBody;
		$data		 = CJSON::decode($rawData, true);
		try
		{
			if($data == "")
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$vendorId	 = UserInfo::getEntityId();
			$userInfo	 = UserInfo::getInstance();

			if(!$vendorId)
			{
				throw new Exception("Unauthorized Vendor", ReturnSet::ERROR_INVALID_DATA);
			}

			$tripId			 = $data['tripId'];
			$currentTime	 = date("Y-m-d H:i:s");
			$snoozeTime		 = $data['snoozeTime'];
			$convertedTime	 = date('Y-m-d H:i:s', strtotime('+' . $snoozeTime . ' min', strtotime($currentTime)));

			$snoozeTime = $convertedTime;

			if(BookingVendorRequest::addSnoozeTime($tripId, $snoozeTime, $vendorId))
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage('Request processed successfully');
			}
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function gnowDenyReasons()
	{
		$returnSet = new ReturnSet();

		try
		{

			$denyReason = Config::get('booking.gozoNow.denyReason');
			/* $res					 = new \Stub\common\DestinationNote();
			  $res->setAreaData($showArr);
			  $responsedt->dataList	 = $showArr;
			  $response				 = $responsedt;
			  $data					 = Filter::removeNull($response);
			  $returnSet->setStatus(true);
			  $returnSet->setData($data); */
			if(!empty($denyReason))
			{
				$showArrVal	 = CJSON::decode($denyReason);
				$res		 = new \Stub\common\DenyReasons();
				$res->setReasonData($showArrVal);
				$responsedt	 = $res;

				$response	 = $responsedt;
				$data		 = Filter::removeNull($response);
				$returnSet->setStatus(true);
				$returnSet->setData($data);
			}
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function gnowSnoozeNotification()
	{
		$returnSet	 = new ReturnSet();
		$rawData	 = Yii::app()->request->rawBody;
		$data		 = CJSON::decode($rawData, true);
		Logger::trace("<===Requset===>" . $data);
		try
		{
			if($data == "")
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$vendorId	 = UserInfo::getEntityId();
			$userInfo	 = UserInfo::getInstance();

			if(!$vendorId)
			{
				throw new Exception("Unauthorized Vendor", ReturnSet::ERROR_INVALID_DATA);
			}

			$tripId			 = $data['tripId'];
			$startTime		 = date("Y-m-d H:i:s");
			$snoozeTime		 = $data['snoozeTime'];
			$convertedTime	 = date('Y-m-d H:i:s', strtotime('+' . $snoozeTime . ' min', strtotime($startTime)));

			$snoozeTime = $convertedTime;

			if(BookingVendorRequest::addSnoozeTime($tripId, $snoozeTime, $vendorId))
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage('Request processed successfully');
			}
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used to assign operator chauffeur
	 * @return data
	 */
	public function confirmTrip()
	{
		$data		 = Yii::app()->request->rawBody;
		$jsonValue	 = CJSON::decode($data, false);
		$jsonObj	 = $jsonValue->data;
		$model		 = Booking::model()->findByBookingid($jsonObj->orderReferenceNumber);
		if(!$model || $model == null)
		{
			return false;
		}

		$operatorId	 = Operator::getOperatorId($model->bkg_booking_type);
		$objOperator = Operator::getInstance($operatorId);

		/* @var $objOperator Operator */
		$objOperator = $objOperator->confirmTrip($model->bkg_id, $operatorId, $jsonObj);
		return $objOperator;
	}

	public function syncChat()
	{
		$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$result	 = Vendors::model()->authoriseVendor($token);
		if($result == true)
		{
			$success	 = false;
			$errors		 = '';
			$transaction = NULL;
			try
			{
				$userInfo			 = UserInfo::getInstance();
				$userInfo->userType	 = UserInfo::TYPE_VENDOR;
			//	$entityId			 = UserInfo::getEntityId();

				$process_sync_data = Yii::app()->request->getParam('data');

				$data1	 = CJSON::decode($process_sync_data, true);
				$refId	 = $data1['bkg_id'];
				$refType = Chats::REF_TYPE_BOOKING;

				$message = $data1['msg'];
				$chtId	 = $data1['cht_id'] | 0;
				$chlId	 = $data1['chl_id'] | 0;

				$isDriver	 = 0;
				$isConsumer	 = 0;
				$isVendor	 = 1;

				$arrData			 = array();
				$arrData['source']	 = 'vendor';

				$transaction = DBUtil::beginTransaction();
				if(!$process_sync_data)
				{
					throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
				}
				if(isset($message) && $message != '')
				{
					//	$syncData		 = ['bkgId' => $bkgId, 'msg' => $msg, 'isDriver' => $isDriver, 'isVendor' => $isVendor, 'isConsumer' => $isConsumer];
					//Logger::create('POST DATA ===========>: \n\t\t' . $syncData . " == " . $userInfo->userType, CLogger::LEVEL_TRACE);
					$msgObj	 = new ChatLog();
					//	$result			 = $msgObj->addMessage($bkgId, $msg, $userInfo, $isDriver, $isVendor, $isConsumer, $entityType, $arrData);
					$result	 = ChatLog::addMessageV1($message, $refId, $refType, $userInfo, $isDriver, $isVendor, $isConsumer, $arrData);
					if($result['success'] == true)
					{
						$success = true;
						DBUtil::commitTransaction($transaction);
					}
					else if($result['success'] == false)
					{
						$errors = $result['errors'];
						throw new Exception("Not Validate.\n\t\t" . json_encode($errors));
					}
				}
				$msgList = ChatLog::getMessagesByRef($refId, $refType, $userInfo, $isVendor, $isDriver, $isConsumer, $chtId, $chlId);
				//Logger::create('Query DATA ===========>: \n\t\t' . var_dump($msgList) . " == " . $userInfo->userType, CLogger::LEVEL_TRACE);
				if(count($msgList) > 0)
				{
					$success = true;
				}
			}
			catch(Exception $e)
			{
				Logger::create("Errors.\n\t\t" . $e->getMessage(), CLogger::LEVEL_ERROR);
				$errors = "Errors is :" . $e->getMessage();
				DBUtil::rollbackTransaction($transaction);
			}
		}
		else
		{
			$success = false;
			$errors	 = "Vendor Unauthorised";
		}
		return $this->renderJSON([
					'type'	 => 'raw',
					'data'	 => array(
						'success'		 => $success,
						'errors'		 => $errors,
						'isChatLoaded'	 => (($chtId > 0) ? true : false),
						'msgList'		 => $msgList
					)
		]);
	}
}

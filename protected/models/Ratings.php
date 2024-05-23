<?php

/**
 * This is the model class for table "ratings".
 *
 * The followings are the available columns in table 'ratings':
 * @property integer $rtg_id
 * @property integer $rtg_booking_id
 * @property integer $rtg_customer_recommend
 * @property integer $rtg_customer_overall
 * @property integer $rtg_customer_driver
 * @property integer $rtg_customer_csr
 * @property integer $rtg_customer_car
 * @property string $rtg_customer_review
 * @property string $rtg_customer_date
 * @property string $rtg_review_approved_by
 * @property integer $rtg_csr_customer
 * @property integer $rtg_csr_vendor
 * @property string $rtg_csr_review
 * @property string $rtg_csr_date
 * @property string $rtg_csr_id
 * @property integer $rtg_vendor_customer
 * @property integer $rtg_vendor_csr
 * @property string $rtg_vendor_review
 * @property string $rtg_vendor_date
 * @property integer $rtg_active
 * @property integer $rtg_platform
 * @property string $rtg_customer_reply
 * @property integer $rtg_customer_reply_by
 * @property string $rtg_customer_reply_datetime
 * @property integer $rtg_customer_reply_status
 * @property string $rtg_vendor_reply
 * @property integer $rtg_vendor_reply_by
 * @property string $rtg_vendor_reply_datetime
 * @property integer $rtg_vendor_reply_status
 * @property integer $rtg_driver_ontime
 * @property integer $rtg_driver_softspokon
 * @property integer $rtg_driver_respectfully
 * @property integer $rtg_driver_helpful
 * @property integer $rtg_driver_safely
 * @property integer $rtg_driver_vendor_mismatch
 * @property string $rtg_driver_cmt
 * @property integer $rtg_car_clean
 * @property integer $rtg_car_good_cond
 * @property integer $rtg_car_commercial
 * @property integer $rtg_car_vendor_mismatch
 * @property string $rtg_car_cmt
 * @property integer $rtg_csr_polite
 * @property integer $rtg_csr_well_communicate
 * @property integer $rtg_csr_professional
 * @property string $rtg_csr_cmt
 * @property integer $rtg_platform_exp
 * @property string $rtg_platform_exp_cmt
 * @property string $rtg_review_desc
 * @property string $rtg_driver_good_attr
 * @property string $rtg_driver_bad_attr
 * @property string $rtg_csr_good_attr
 * @property string $rtg_csr_bad_attr
 * @property string $rtg_car_good_attr
 * @property string $rtg_car_bad_attr
 * @property string $rtg_penalty_attr
 * @property string $rtg_bonus_attr
 * The followings are the available model relations:
 * @property Booking $rtgBooking
 * @property Admins $rtgReviewApprovedBy
 * @property Admins $rtgCsr
 */
class Ratings extends CActiveRecord
{

	public $vnd_id, $vnd_region;

	const PLATFORM_FRONT_END	 = 1;
	const PLATFORM_BACK_END	 = 2;
	const PLATFORM_ANDROID_APP = 3;
	const PLATFORM_IOS_APP	 = 4;
	const PLATFORM_SYSTEM		 = 5;
	const NoRating			 = 15;

	public $rtg_create_date1;
	public $rtg_create_date2;
	public $category,$strTags;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ratings';
	}

	public $customer_email, $vendor_email, $customer_name, $vendor_name, $booking_id, $channel_partner_id,
			$rtg_date1, $rtg_date2, $detractors, $passives, $promotors, $nps, $responded, $rtg_driver_name, $rtg_inactive, $groupvar, $bkgtypes;

	public function defaultScope()
	{
		// 'condition' => "rtg_active=1",
		$arr = array();
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
			//array('rtg_booking_id', 'required'),
			array('rtg_booking_id, rtg_customer_overall,rtg_customer_recommend,', 'required', 'on' => 'custRating'),
			array('rtg_booking_id, rtg_csr_customer,rtg_csr_customer', 'required', 'on' => 'csrRating'),
			array('rtg_customer_reply, rtg_customer_reply_by, rtg_customer_reply_datetime, rtg_customer_reply_status', 'required', 'on' => 'replycustomer'),
			array('rtg_vendor_reply, rtg_vendor_reply_by, rtg_vendor_reply_datetime, rtg_vendor_reply_status', 'required', 'on' => 'replyvendor'),
			array('rtg_booking_id,rtg_customer_recommend, rtg_customer_overall, rtg_customer_driver, rtg_customer_csr, rtg_customer_car, rtg_csr_customer, rtg_csr_vendor, rtg_vendor_customer, rtg_vendor_csr, rtg_active', 'numerical', 'integerOnly' => true),
			array('rtg_csr_review, rtg_vendor_review', 'length', 'max' => 1000),
			array('rtg_customer_review', 'length', 'max' => 6000),
			array('rtg_review_approved_by', 'length', 'max' => 11),
			array('rtg_csr_id', 'length', 'max' => 10),
			array('rtg_customer_date, rtg_csr_date, rtg_vendor_date', 'safe'),
			array('rtg_customer_review, rtg_csr_review, rtg_vendor_review'
				, 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rtg_id, rtg_booking_id, rtg_customer_overall, rtg_customer_driver,
                 rtg_customer_csr, rtg_customer_car, rtg_customer_review, rtg_customer_date,rtg_review_approved_by,
                 rtg_csr_customer, rtg_csr_vendor, rtg_csr_review,rtg_customer_recommend,
                 rtg_csr_date, rtg_csr_id, rtg_vendor_customer, rtg_vendor_csr, rtg_vendor_review, rtg_vendor_date, rtg_active, rtg_platform, rtg_customer_reply, rtg_customer_reply_by, rtg_customer_reply_datetime, rtg_customer_reply_status, rtg_vendor_reply, rtg_vendor_reply_by, rtg_vendor_reply_datetime, rtg_vendor_reply_status, vnd_id,
                 rtg_driver_ontime,rtg_driver_softspokon,rtg_driver_respectfully,rtg_driver_helpful,rtg_driver_safely,rtg_driver_vendor_mismatch,rtg_driver_cmt,rtg_car_clean,rtg_car_good_cond,rtg_car_commercial,rtg_car_vendor_mismatch,rtg_car_cmt,rtg_csr_polite,rtg_csr_well_communicate,rtg_csr_professional,rtg_csr_cmt,rtg_platform_exp,rtg_platform_exp_cmt,rtg_review_desc,
				 rtg_driver_good_attr,rtg_driver_bad_attr,rtg_csr_good_attr,rtg_csr_bad_attr,rtg_car_good_attr,rtg_car_bad_attr,vnd_region,channel_partner_id,rtg_penalty_attr,rtg_bonus_attr', 'safe', 'on' => 'search'),
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
			'rtgBooking'			 => array(self::BELONGS_TO, 'Booking', 'rtg_booking_id'),
			'rtgReviewApprovedBy'	 => array(self::BELONGS_TO, 'Admins', 'rtg_review_approved_by'),
			'rtgCsr'				 => array(self::BELONGS_TO, 'Admins', 'rtg_csr_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'rtg_id'				 => 'Rtg',
			'rtg_booking_id'		 => 'Booking',
			'rtg_customer_recommend' => 'How likely are you to recommend Gozo to your friends and family?',
			'rtg_customer_overall'	 => 'Your comments about the overall trip',
			'rtg_customer_driver'	 => 'Driver Experience',
			'rtg_customer_csr'		 => 'Call Center Experience',
			'rtg_customer_car'		 => 'Car Experience',
			'rtg_customer_review'	 => 'Overall trip comments',
			'rtg_customer_date'		 => 'Review Date',
			'rtg_platform'			 => 'Rating Platform',
			'rtg_review_approved_by' => 'Review Approved By',
			'rtg_csr_customer'		 => 'CSR Rating for Customer Experience',
			'rtg_csr_vendor'		 => 'CSR Rating for Vendor',
			'rtg_csr_review'		 => 'CSR Review',
			'rtg_csr_date'			 => 'Csr Date',
			'rtg_csr_id'			 => 'Csr',
			'rtg_vendor_customer'	 => 'Vendor Customer',
			'rtg_vendor_csr'		 => 'Vendor Csr',
			'rtg_vendor_review'		 => 'Vendor Review',
			'rtg_vendor_date'		 => 'Vendor Date',
			'rtg_vendor_reply'		 => 'Vendor Reply',
			'rtg_customer_reply'	 => 'Customer Reply',
			'rtg_driver_cmt'		 => 'Any other comments about Driver',
			'rtg_car_cmt'			 => 'Any other comments about Car experience',
			'rtg_csr_cmt'			 => 'Any other comments about Customer service',
			'rtg_platform_exp_cmt'	 => 'Comments about our website & app',
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

		$criteria->compare('rtg_id', $this->rtg_id);
		$criteria->compare('rtg_booking_id', $this->rtg_booking_id);
		$criteria->compare('rtg_customer_overall', $this->rtg_customer_overall);
		$criteria->compare('rtg_customer_driver', $this->rtg_customer_driver);
		$criteria->compare('rtg_customer_csr', $this->rtg_customer_csr);
		$criteria->compare('rtg_customer_car', $this->rtg_customer_car);
		$criteria->compare('rtg_customer_review', $this->rtg_customer_review, true);
		$criteria->compare('rtg_customer_date', $this->rtg_customer_date, true);
		$criteria->compare('rtg_review_approved_by', $this->rtg_review_approved_by, true);
		$criteria->compare('rtg_csr_customer', $this->rtg_csr_customer);
		$criteria->compare('rtg_csr_vendor', $this->rtg_csr_vendor);
		$criteria->compare('rtg_csr_review', $this->rtg_csr_review, true);
		$criteria->compare('rtg_csr_date', $this->rtg_csr_date, true);
		$criteria->compare('rtg_csr_id', $this->rtg_csr_id, true);
		$criteria->compare('rtg_vendor_customer', $this->rtg_vendor_customer);
		$criteria->compare('rtg_vendor_csr', $this->rtg_vendor_csr);
		$criteria->compare('rtg_vendor_review', $this->rtg_vendor_review, true);
		$criteria->compare('rtg_vendor_date', $this->rtg_vendor_date, true);
		$criteria->compare('rtg_active', $this->rtg_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ratings the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 
	 * @param type $data
	 * @return boolean | array
	 * @throws Exception
	 */
	public function addRatingForCustomer($data)
	{
		//Logger::create("Rating Attr ================> " . json_encode($data), CLogger::LEVEL_INFO);
		$success		 = false;
		$tripAdviser	 = false;
		$rtgBookingId	 = 0;
		$userInfo		 = UserInfo::getInstance();
		try
		{


			/* @var $model Ratings */
			$model = Ratings::model()->getRatingbyBookingId($data['rtg_booking_id']);
			if ($model == '')
			{
				$model = new Ratings();
			}
			$uniqueId						 = $data['uniqueId'];
			$model->rtg_booking_id			 = $data['rtg_booking_id'];
			$model->rtg_customer_overall	 = $data['rtg_customer_overall'];
			$model->rtg_customer_recommend	 = $data['rtg_customer_recommend'];
			$model->rtg_customer_driver		 = $data['rtg_customer_driver'];
			$model->rtg_customer_csr		 = $data['rtg_customer_csr'];
			$model->rtg_customer_car		 = $data['rtg_customer_car'];
			$model->rtg_customer_review		 = $data['rtg_customer_review'];
			$model->rtg_driver_cmt			 = $data['rtg_driver_cmt'];
			$model->rtg_car_cmt				 = $data['rtg_car_cmt'];
			$model->rtg_csr_cmt				 = $data['rtg_csr_cmt'];
			$model->rtg_platform_exp		 = $data['rtg_platform_exp'];
			$model->rtg_platform_exp_cmt	 = $data['rtg_platform_exp_cmt'];
			$model->rtg_customer_date		 = new CDbExpression('NOW()');
			$model->rtg_driver_good_attr	 = ($data['rtg_customer_driver'] < 5) ? $data['rtg_driver_good_attr'] : null;
			$model->rtg_driver_bad_attr		 = ($data['rtg_customer_driver'] < 5) ? $data['rtg_driver_bad_attr'] : null;
			$model->rtg_csr_good_attr		 = ($data['rtg_customer_csr'] < 5) ? $data['rtg_csr_good_attr'] : null;
			$model->rtg_csr_bad_attr		 = ($data['rtg_customer_csr'] < 5) ? $data['rtg_csr_bad_attr'] : null;
			$model->rtg_car_good_attr		 = ($data['rtg_customer_car'] < 5) ? $data['rtg_car_good_attr'] : null;
			$model->rtg_car_bad_attr		 = ($data['rtg_customer_car'] < 5) ? $data['rtg_car_bad_attr'] : null;
			$model->rtg_platform			 = $data['rtg_platform'];
			$isEntityData					 = false;
			if (($model->rtg_customer_driver == '' || $model->rtg_customer_driver == NULL) && ($model->rtg_customer_csr == '' || $model->rtg_customer_csr == NULL) && ($model->rtg_customer_car == '' || $model->rtg_customer_car == NULL))
			{
				$isEntityData = true;
			}

			if ($model->rtg_customer_overall >= 4 && $isEntityData == true)
			{
				$model->rtg_customer_driver	 = $model->rtg_customer_overall;
				$model->rtg_customer_car	 = $model->rtg_customer_overall;
				$model->rtg_customer_csr	 = $model->rtg_customer_overall;
			}
			if ($model->validate() && $model->save())
			{
				$bkgUserId	 = $model->rtgBooking->bkgUserInfo->bkg_user_id;
				$desc		 = "Customer Review entered";
				BookingLog::model()->createLog($model->rtg_booking_id, $desc, $userInfo, BookingLog::BOOKING_REVIEWED_BY_USER);

				if (($model->rtg_customer_overall < 4 && $model->rtg_customer_overall != '' && $model->rtg_customer_overall > 0))
				{
					$desc_payment		 = "Vendor payment stopped due to low customer ratings. Escalated to Customer advocacy team";
					$escalation_level	 = 3;
					$assigned_lead		 = 158;
					$assigned_team		 = 14;
					/* Auto FUR Poor Rating Start */
					$count				 = ServiceCallQueue::countQueueByBkgId($model->rtg_booking_id, ServiceCallQueue::TYPE_ADVOCACY, "notRating");
					if ($count == 0)
					{
						ServiceCallQueue::autoFURRating($model->rtg_booking_id);
					}
					$countNotRating	 = ServiceCallQueue::countQueueByBkgId($model->rtg_booking_id, ServiceCallQueue::TYPE_ADVOCACY, "notRating");
					$countIsRated	 = ServiceCallQueue::countQueueByBkgId($model->rtg_booking_id, ServiceCallQueue::TYPE_ADVOCACY, "IsRated");
					if ($countNotRating == 1 && $countIsRated == 1)
					{
						$scqId = ServiceCallQueue::getScqDetailsForRating($model->rtg_booking_id);
						if ($scqId > 0)
						{
							ServiceCallQueue::updateStatus($scqId, 10, 0, "CBR expired. No action taken");
						}
					}
					/* 	Auto FUR Poor Rating  ENDS */


					/* Auto FUR Poor Rating for Vendor Advocacy Start */
//					$count = ServiceCallQueue::countQueueByBkgId($model->rtg_booking_id, ServiceCallQueue::TYPE_VENDOR_ADVOCACY);
//					if ($count == 0)
//					{
//						ServiceCallQueue::autoFURRatingVendorAdvocacy($model->rtg_booking_id);
//					}
					/* 	Auto FUR Poor Rating for Vendor Advocacy ENDS */

					BookingSub::blockVendorPayment($model->rtgBooking->bkg_bcb_id, $model->rtg_booking_id, 1, $desc_payment, $escalation_level, $assigned_lead, $assigned_team);
				}

				$ratingData	 = Ratings::model()->getDriverVendorDetailsById($model->rtg_id);
				$Info		 = Users::info($ratingData['bkg_user_id']);

				$ratingData['rtg_driver_good_attr']	 = $model->rtg_driver_good_attr;
				$ratingData['rtg_driver_bad_attr']	 = $model->rtg_driver_bad_attr;
				$ratingData['rtg_csr_good_attr']	 = $model->rtg_csr_good_attr;
				$ratingData['rtg_csr_bad_attr']		 = $model->rtg_csr_bad_attr;
				$ratingData['rtg_car_good_attr']	 = $model->rtg_car_good_attr;
				$ratingData['rtg_car_bad_attr']		 = $model->rtg_car_bad_attr;
				$ratingData['bkg_contact_gozo']		 = $model->rtgBooking->bkgPref->bkg_contact_gozo;
				$ratingData['total_trip_by_car']	 = Vehicles::totalTrips($ratingData['vhc_id']);
				$ratingData['first_trip_date']		 = $Info['firstTripDate'];
				$ratingData['last_trip_date']		 = $Info['lastTripDate'];
				$ratingData['user_rating']			 = $Info['rating'];
				$ratingData['total_trip']			 = $Info['totalTrips'];

				/**
				 * if driver and cab get 5 star rating driver & vendor coin will be added
				 */
//				if($model->rtg_customer_driver == 5 && $model->rtg_customer_car == 5)
//				{
//				   VendorCoins::earnCoin($ratingData['vnd_id'], VendorCoins::TYPE_RATING, $model->rtg_booking_id);
//				}
//				if($model->rtg_customer_driver == 5)
//				{
//					DriverCoins::earnCoin($ratingData['drv_id'], DriverCoins::TYPE_RATING, $model->rtg_booking_id);
//				}

				$cusOverallExp = (trim($model->rtg_customer_overall) == '4' || trim($model->rtg_customer_overall) == '5') ? 1 : 0;

				CustomerProfile::updateAttr($bkgUserId, $model->rtg_booking_id, CustomerProfile::TYPE_REVIEW, $model->rtg_customer_review, $model->rtg_customer_overall);

				if ($model->rtg_customer_driver <= 2 && $model->rtg_customer_driver != '' && $model->rtg_customer_driver > 0)
				{
					$params = ['drv_id' => $ratingData['drv_id'], 'bkg_booking_id' => $ratingData['rtg_booking_id'], 'rtg_customer_car' => $ratingData['rtg_customer_car']];
				}
				if ($model->rtg_customer_car <= 2 && $model->rtg_customer_car != '' && $model->rtg_customer_car > 0)
				{
					$params = ['vhc_id' => $ratingData['vhc_id'], 'bkg_booking_id' => $ratingData['rtg_booking_id'], 'rtg_customer_car' => $ratingData['rtg_customer_car']];
				}
				if ($model->rtg_customer_car != '' && $model->rtg_customer_car != 0)
				{
					if ($model->rtg_customer_car < 3)
					{
						VendorProfile::updateAttr($ratingData['vnd_id'], $model->rtg_booking_id, VendorProfile::TYPE_BAD_CAR, $model->rtg_customer_car, '');
					}
				}
				if ($model->rtg_car_commercial != '' && $model->rtg_car_commercial != 0)
				{
					VendorProfile::updateAttr($ratingData['vnd_id'], $model->rtg_booking_id, VendorProfile::TYPE_PRIVATE_CAR, $model->rtg_car_commercial, '');
				}
				if ($model->rtg_car_vendor_mismatch != '' && $model->rtg_car_vendor_mismatch != 0)
				{
					VendorProfile::updateAttr($ratingData['vnd_id'], $model->rtg_booking_id, VendorProfile::TYPE_NOT_MATCH, $model->rtg_car_vendor_mismatch, '');
				}
				if ($model->rtg_customer_driver != '' && $model->rtg_customer_driver != '')
				{
					if ($model->rtg_customer_driver < 3)
					{
						VendorProfile::updateAttr($ratingData['vnd_id'], $model->rtg_booking_id, VendorProfile::TYPE_BAD_DRIVER, $model->rtg_customer_driver, '');
					}
				}
				if ($model->rtg_vendor_review != '')
				{
					VendorProfile::updateAttr($ratingData['vnd_id'], $model->rtg_booking_id, VendorProfile::TYPE_RATING, '0', $model->rtg_vendor_review);
				}
				if ($bookModel->bkgBcb->bcb_driver_id != '' && $bookModel->bkgBcb->bcb_driver_id != NULL)
				{
					$model->updateDriverInfo($bookModel->bkgBcb->bcb_driver_id);
				}
				if ($bookModel->bkgBcb->bcb_vendor_id != '' && $bookModel->bkgBcb->bcb_vendor_id != NULL)
				{
					$model->updateVendorInfo($bookModel->bkgBcb->bcb_vendor_id);
				}
				if ($bookModel->bkgBcb->bcb_cab_id != '' && $bookModel->bkgBcb->bcb_cab_id != NULL)
				{
					$model->updateCabInfo($bookModel->bkgBcb->bcb_cab_id);
				}
				$rtgBookingId	 = $model->rtg_booking_id;
				$emailCom		 = new emailWrapper();
				if ($cusOverallExp == 1)
				{
					$emailCom->reviewStarNotification($model->rtg_id, $uniqueId);
					$tripAdviser = true;
				}
				$emailCom->reviewNotification($model->rtg_id, $ratingData);
				$emailCom->reviewNotificationDriver($model->rtg_id, $uniqueId);
				if ($ratingData['drv_id'] != '' || $ratingData['drv_id'] != NULL)
				{
					$notificationId	 = substr(round(microtime(true) * 1000), -5);
					$payLoadData	 = ['EventCode' => Booking::CODE_DRIVER_RATING_RECIEVED];
					$message		 = "Rating recieved for Booking ID: " . $model->rtgBooking->bkg_booking_id . ".";
					$success		 = AppTokens::model()->notifyDriver($ratingData['drv_id'], $payLoadData, $notificationId, $message, NULL, "GozoCabs");
				}
				$emailCom->reviewNotificationVendor($model->rtg_id, $uniqueId);
				$comment		 = Ratings::model()->getCommentByOverallRating($model->rtg_customer_overall);
				$sentMessage	 = $model->rtg_customer_overall . ' (' . $comment . ') review received for booking ID ' . $model->rtgBooking->bkg_booking_id;
				$payLoadData	 = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
				$tokenSuccess	 = AppTokens::model()->notifyVendor($model->rtgBooking->bkgBcb->bcb_vendor_id, $payLoadData, $sentMessage, "Rating Received");
				if (isset($model->rtg_customer_overall) && $model->rtg_customer_overall == 5)
				{
					smsWrapper::compensateDriverForOnRating('91', $model->rtg_id);
				}
				if (isset($model->rtg_customer_driver) && ($model->rtg_customer_driver == 5 || $model->rtg_customer_driver == 4) && $model->rtgBooking->bkgBcb->bcb_driver_id > 0 && ($model->rtg_driver_bad_attr == NULL || $model->rtg_driver_bad_attr == ''))
				{
					$isDrvAppUsed = self::checkDriverAppUseBooking($model->rtg_booking_id);
					if ($isDrvAppUsed)
					{
						$bkgId		 = $model->rtg_booking_id;
						$bonusAmount = 50;
						$driverId	 = $model->rtgBooking->bkgBcb->bcb_driver_id;
						if ($model->rtg_driver_good_attr != '' || $model->rtg_driver_good_attr != NULL)
						{
							$model->addDriverBonusByCustomerReview();
						}
						if ($model->rtg_driver_good_attr == '' || $model->rtg_driver_good_attr == NULL && $model->rtg_customer_driver == 5)
						{
							$removeDriverBonus	 = AccountTransactions::model()->removeDriverBonus($bkgId);
							$addDriverBonus		 = AccountTransactions::model()->AddDriverBonus($bonusAmount, $bkgId, $driverId, UserInfo::getInstance(), 1);
						}
						Logger::create("Driver Account ================> " . json_encode(['bonusAmount' => $bonusAmount, 'bkgId' => $bkgId, 'driverId' => $driverId]), CLogger::LEVEL_INFO);
					}
				}
				if ($model->rtg_driver_bad_attr != NULL || $model->rtg_car_bad_attr != NULL)
				{
					$model->addPenaltyByCustomerReview();
				}
				$message			 = '';
				$success			 = true;
				VendorCoins::processCoinForBooking($model->rtg_booking_id);
				$bookingUserModel	 = BookingUser::model()->find('bui_bkg_id=:bkgid', ['bkgid' => $model->rtg_booking_id]);
				QrCode::processData($bookingUserModel->bkg_user_id);
			}
			else
			{
				$getErrors = "Not validate : " . $model->getErrors();
				throw new Exception($getErrors);
			}
		}
		catch (Exception $ex)
		{
			$message = $ex->getMessage();
		}
		$data = ['success'		 => $success,
			'booking_id'	 => $rtgBookingId,
			'tripAdviser'	 => $tripAdviser,
			'uniqueId'		 => $uniqueId,
			'overallRating'	 => $model->rtg_customer_overall,
			'message'		 => $message];
		return $data;
	}

	/**
	 * 
	 * @param type $data
	 * @return boolean
	 */
	public function addCustomerRating($data)
	{
		$booking_id	 = $data['rtg_booking_id'];
		$model		 = Ratings::model()->find("rtg_booking_id=$booking_id");
		if ($model == null)
		{
			$model = new Ratings();
		}
		$model->attributes			 = $data;
		$model->rtg_customer_date	 = new CDbExpression('NOW()');
		if ($model->save())
		{
			$customerProfile					 = new CustomerProfile();
			$customerProfile->csp_user_id		 = $model->rtgBooking->bkgUserInfo->bkg_user_id;
			$customerProfile->csp_booking_id	 = $model->rtg_booking_id;
			$customerProfile->csp_attribute_type = CustomerProfile::TYPE_REVIEW;
			$customerProfile->csp_value_str		 = $model->rtg_customer_review;
			$customerProfile->csp_value_int		 = $model->rtg_customer_overall;
			$customerProfile->save();

			if ($model->rtg_customer_car != '' && $model->rtg_customer_car != 0)
			{
				$vendorId = Ratings::model()->getVendorIdByBookingId($model->rtg_booking_id);
				if ($model->rtg_customer_car < 3)
				{
					$vendorProfile						 = new VendorProfile();
					$vendorProfile->vnp_user_id			 = $vendorId['vnd_id'];
					$vendorProfile->vnp_booking_id		 = $model->rtg_booking_id;
					$vendorProfile->vnp_attribute_type	 = VendorProfile::TYPE_BAD_CAR;
					$vendorProfile->vnp_value_int		 = $model->rtg_customer_car;
					$vendorProfile->save();
				}
			}
			if ($model->rtg_car_commercial != '' && $model->rtg_car_commercial != 0)
			{
				$vendorId							 = Ratings::model()->getVendorIdByBookingId($model->rtg_booking_id);
				$vendorProfile						 = new VendorProfile();
				$vendorProfile->vnp_user_id			 = $vendorId['vnd_id'];
				$vendorProfile->vnp_booking_id		 = $model->rtg_booking_id;
				$vendorProfile->vnp_attribute_type	 = VendorProfile::TYPE_PRIVATE_CAR;
				$vendorProfile->vnp_value_int		 = $model->rtg_car_commercial;
				$vendorProfile->save();
			}
			if ($model->rtg_car_vendor_mismatch != '' && $model->rtg_car_vendor_mismatch != 0)
			{
				$vendorId							 = Ratings::model()->getVendorIdByBookingId($model->rtg_booking_id);
				$vendorProfile						 = new VendorProfile();
				$vendorProfile->vnp_user_id			 = $vendorId['vnd_id'];
				$vendorProfile->vnp_booking_id		 = $model->rtg_booking_id;
				$vendorProfile->vnp_attribute_type	 = VendorProfile::TYPE_NOT_MATCH;
				$vendorProfile->vnp_value_int		 = $model->rtg_car_vendor_mismatch;
				$vendorProfile->save();
			}
			if ($model->rtg_customer_driver != '' && $model->rtg_customer_driver != '')
			{

				if ($model->rtg_customer_driver < 3)
				{
					$vendorId							 = Ratings::model()->getVendorIdByBookingId($model->rtg_booking_id);
					$vendorProfile						 = new VendorProfile();
					$vendorProfile->vnp_user_id			 = $vendorId['vnd_id'];
					$vendorProfile->vnp_booking_id		 = $model->rtg_booking_id;
					$vendorProfile->vnp_attribute_type	 = VendorProfile::TYPE_BAD_DRIVER;
					$vendorProfile->vnp_value_int		 = $model->rtg_customer_driver;
					$vendorProfile->save();
				}
			}
			if ($model->rtg_vendor_review != '')
			{
				$vendorId							 = Ratings::model()->getVendorIdByBookingId($model->rtg_booking_id);
				$vendorProfile						 = new VendorProfile();
				$vendorProfile->vnp_user_id			 = $vendorId['vnd_id'];
				$vendorProfile->vnp_booking_id		 = $model->rtg_booking_id;
				$vendorProfile->vnp_attribute_type	 = VendorProfile::TYPE_RATING;
				$vendorProfile->vnp_value_str		 = $model->rtg_vendor_review;
				$vendorProfile->save();
			}
			return true;
		}
		return false;
	}

	public function getRatingbyBookingId($bkgid)
	{
		$criteria	 = new CDbCriteria;
		$criteria->compare('rtg_booking_id', $bkgid);
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

	public static function isRatingPosted($bkgId)
	{
		$sql	 = "SELECT COUNT(1) as cnt FROM `ratings` WHERE ratings.rtg_booking_id='$bkgId' AND ratings.rtg_customer_date IS NOT NULL ";
		$cnt	 = DBUtil::command($sql)->queryScalar();
		$success = ($cnt > 0) ? true : false;
		return $success;
	}

	public function getCustRatingbyBookingId($bkgid)
	{
		$sql = "SELECT * FROM `ratings` WHERE ratings.rtg_booking_id='$bkgid' AND ratings.rtg_customer_date IS NOT NULL LIMIT 0,1";
		$row = DBUtil::queryRow($sql, DBUtil::SDB());
		if (isset($row['rtg_id']) && $row['rtg_id'] > 0)
		{
			return $row;
		}
		else
		{
			return false;
		}
	}

	public function getTopRatings($limit = "", $type = 1)
	{
		$sql			 = "SELECT
			        rtg_booking_id,
					rtg_customer_review, 
					UPPER(CONCAT(trim(SUBSTRING(bkg_user_fname, 1, 1)), '', trim(SUBSTRING(booking_user.bkg_user_lname, 1, 1)))) as initial,
					CONCAT(trim(booking_user.bkg_user_fname), ' ', trim(booking_user.bkg_user_lname)) as user_name,
					bkg_booking_type,
					rtg_customer_date
                    FROM ratings
					INNER JOIN booking ON rtg_booking_id=bkg_id AND rtg_customer_overall>=4 AND rtg_customer_review_approve_status=1
                    INNER JOIN booking_user ON bui_bkg_id=bkg_id
					WHERE 1 and (rtg_customer_overall>=4 AND rtg_customer_review_approve_status=1 AND rtg_active=1) and LENGTH(rtg_customer_review)>10
				";
		$limit			 = $limit == "" ? 18 : $limit;
		$defaultOrder	 = $type == 1 ? " rtg_customer_date DESC,rtg_customer_overall DESC " : " rtg_customer_overall DESC, rtg_customer_date DESC ";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) a", DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => [
				'attributes'	 => array(),
				'defaultOrder'	 => $defaultOrder
			],
			'pagination'	 => ['pageSize' => $limit],
		]);
		return $dataprovider;
	}

	public function getTopRatings1($limit = '9', $type = 1)
	{
		$sql	 = "SELECT   
					rtg_id, 
					rtg_active, 
					booking.bkg_booking_id, 
					rtg_customer_review, 
					rtg_customer_overall, 
					CONCAT(trim(booking_user.bkg_user_fname), ' ', trim(booking_user.bkg_user_lname)) AS user_name,
					UPPER(CONCAT(trim(SUBSTRING(bkg_user_fname, 1, 1)), '', trim(SUBSTRING(booking_user.bkg_user_lname, 1, 1)))) AS initial,
					bkg_booking_type, 
					rtg_customer_date ,
					Replace(Replace(Replace(Replace(booking.bkg_route_city_names,'[',''),']',''),'\"',''),',',' - ') AS cities
					FROM     ratings
					INNER JOIN booking ON rtg_booking_id = bkg_id AND rtg_customer_overall >= 4 AND rtg_customer_review_approve_status = 1
					INNER JOIN booking_user ON bui_bkg_id = bkg_id
					WHERE    LENGTH(rtg_customer_review) >= 100 AND ratings.rtg_active = 1
					ORDER BY rtg_customer_overall DESC, rtg_customer_date DESC
					LIMIT    0,$limit";
		$rows	 = DBUtil::queryAll($sql, DBUtil::SDB());
		return $rows;
	}

	public function getTop3Ratings($route, $limit = '3')
	{
		$rutModel	 = Route::model()->getByName($route);
		$fromCityId	 = $rutModel->rut_from_city_id;
		$toCityId	 = $rutModel->rut_to_city_id;
		if ($fromCityId == '' || $toCityId == '')
		{
			return false;
		}
		$sqlQuery1	 = "SELECT 
						rtg_id, 
						rtg_active,
						booking.bkg_booking_id,
						rtg_customer_review, 
						CONCAT(trim(bkg_user_fname), ' ', trim(booking_user.bkg_user_lname)) as user_name, 
						UPPER(CONCAT(trim(SUBSTRING(bkg_user_fname, 1, 1)), '', trim(SUBSTRING(booking_user.bkg_user_lname, 1, 1)))) as initial,
						bkg_booking_type, 
						rtg_customer_date,
						Replace(Replace(Replace(Replace(booking.bkg_route_city_names,'[',''),']',''),'\"',''),',',' - ') AS cities
						FROM ratings
						INNER JOIN booking ON rtg_booking_id=bkg_id AND rtg_customer_overall>=4 AND rtg_customer_review_approve_status=1
						INNER JOIN booking_user ON bui_bkg_id=bkg_id
						WHERE LENGTH(rtg_customer_review)>10 AND ratings.rtg_active=1
						AND (booking.bkg_from_city_id=$fromCityId AND booking.bkg_to_city_id=$toCityId)
						ORDER BY rtg_customer_overall DESC, rtg_customer_date DESC
		                LIMIT 0,$limit";
		$recordSet1	 = DBUtil::queryAll($sqlQuery1, DBUtil::SDB());
		if (count($recordSet1) == $limit)
		{
			return $recordSet1;
		}
		$sqlQuery2	 = "SELECT 
						rtg_id, 
						rtg_active, 
						booking.bkg_booking_id, 
						rtg_customer_review, 
						CONCAT(trim(bkg_user_fname), ' ', trim(booking_user.bkg_user_lname)) as user_name, 
						UPPER(CONCAT(trim(SUBSTRING(bkg_user_fname, 1, 1)), '',trim(SUBSTRING(booking_user.bkg_user_lname, 1, 1)))) as initial,
						bkg_booking_type, 
						rtg_customer_date,
						Replace(Replace(Replace(Replace(booking.bkg_route_city_names,'[',''),']',''),'\"',''),',',' - ') AS cities
						FROM ratings
						INNER JOIN booking ON rtg_booking_id=bkg_id AND rtg_customer_overall>=4 AND rtg_customer_review_approve_status=1
						INNER JOIN booking_user ON bui_bkg_id=bkg_id
						WHERE LENGTH(rtg_customer_review)>10 AND ratings.rtg_active=1
						AND ((booking.bkg_from_city_id=$fromCityId AND booking.bkg_to_city_id=$toCityId) ||(booking.bkg_to_city_id=$fromCityId AND booking. bkg_from_city_id =$toCityId))
						ORDER BY rtg_customer_overall DESC, rtg_customer_date DESC
						LIMIT 0,$limit";
		$recordSet2	 = DBUtil::queryAll($sqlQuery2, DBUtil::SDB());
		if (count($recordSet2) == $limit)
		{
			return $recordSet2;
		}
		$sqlQuery3	 = "SELECT 
						rtg_id,
						rtg_active,
						booking.bkg_booking_id, rtg_customer_review, 
						CONCAT(trim(bkg_user_fname), ' ',trim(booking_user.bkg_user_lname)) as user_name,
						UPPER(CONCAT(trim(SUBSTRING(bkg_user_fname, 1, 1)), '',trim(SUBSTRING(booking_user.bkg_user_lname, 1, 1)))) as initial,
						bkg_booking_type,
						rtg_customer_date,
					    CONCAT(c1.cty_name, ' - ', GROUP_CONCAT(c2.cty_name SEPARATOR ' - ')) as cities
						FROM ratings
						INNER JOIN booking ON rtg_booking_id=bkg_id AND rtg_customer_overall>=4 AND rtg_customer_review_approve_status=1
						INNER JOIN booking_user ON bui_bkg_id=bkg_id
						INNER JOIN booking_route ON brt_bkg_id=bkg_id AND brt_active=1
						INNER JOIN cities c1 ON c1.cty_id=bkg_from_city_id
						INNER JOIN cities c2 ON c2.cty_id=brt_to_city_id
						INNER JOIN `zone_cities` zc1 ON zc1.zct_cty_id=bkg_from_city_id AND zc1.zct_active=1
						INNER JOIN `zone_cities` zc2 ON zc2.zct_cty_id=brt_to_city_id AND zc2.zct_active=1
						WHERE LENGTH(rtg_customer_review)>10 AND ratings.rtg_active=1
						AND zc1.zct_zon_id IN
						(
							SELECT zone_cities.zct_zon_id
							FROM `zone_cities` WHERE zone_cities.zct_active=1
							AND zone_cities.zct_cty_id=$fromCityId
						)
						GROUP BY rtg_id
						ORDER BY rtg_customer_overall DESC, rtg_customer_date DESC, brt_id ASC
						LIMIT 0,$limit";
		$recordSet3	 = DBUtil::queryAll($sqlQuery3, DBUtil::SDB());
		if (count($recordSet3) == $limit)
		{
			return $recordSet3;
		}
		$sqlQuery4	 = "SELECT rtg_id, rtg_active, booking.bkg_booking_id, rtg_customer_review, CONCAT(trim(bkg_user_fname), ' ',
                    trim(booking_user.bkg_user_lname)) as user_name, UPPER(CONCAT(trim(SUBSTRING(bkg_user_fname, 1, 1)), '',
                    trim(SUBSTRING(booking_user.bkg_user_lname, 1, 1)))) as initial,bkg_booking_type, rtg_customer_date,
                    CONCAT(c1.cty_name, ' - ', GROUP_CONCAT(c2.cty_name SEPARATOR ' - ')) as cities
                    FROM ratings
                    INNER JOIN booking ON rtg_booking_id=bkg_id AND rtg_customer_overall>=4 AND rtg_customer_review_approve_status=1
                    INNER JOIN booking_user ON bui_bkg_id=bkg_id
                    INNER JOIN booking_route ON brt_bkg_id=bkg_id AND brt_active=1
                    INNER JOIN cities c1 ON c1.cty_id=bkg_from_city_id
                    INNER JOIN cities c2 ON c2.cty_id=brt_to_city_id
                    INNER JOIN `zone_cities` zc1 ON zc1.zct_cty_id=bkg_from_city_id AND zc1.zct_active=1
                    INNER JOIN `zone_cities` zc2 ON zc2.zct_cty_id=brt_to_city_id AND zc2.zct_active=1
                    WHERE LENGTH(rtg_customer_review)>10 AND ratings.rtg_active=1
                    AND zc1.zct_zon_id IN
                    (
                        SELECT zone_cities.zct_zon_id
                        FROM `zone_cities` WHERE zone_cities.zct_active=1
                        AND zone_cities.zct_cty_id=$toCityId
                    )
                    GROUP BY rtg_id
                    ORDER BY rtg_customer_overall DESC, rtg_customer_date DESC, brt_id ASC
                    LIMIT 0,$limit";
		$recordSet4	 = DBUtil::queryAll($sqlQuery4, DBUtil::SDB());
		if (count($recordSet4) == $limit)
		{
			return $recordSet4;
		}
		$sqlQuery5	 = "
			            SELECT 
						rtg_id,
						rtg_active,
						booking.bkg_booking_id, 
						rtg_customer_review,
						CONCAT(trim(bkg_user_fname), ' ',trim(booking_user.bkg_user_lname)) as user_name,
						UPPER(CONCAT(trim(SUBSTRING(bkg_user_fname, 1, 1)), '', trim(SUBSTRING(booking_user.bkg_user_lname, 1, 1)))) as initial,
						bkg_booking_type, rtg_customer_date,
						Replace(Replace(Replace(Replace(booking.bkg_route_city_names,'[',''),']',''),'\"',''),',',' - ') AS cities
						FROM ratings
						INNER JOIN booking ON rtg_booking_id=bkg_id AND rtg_customer_overall>=4 AND rtg_customer_review_approve_status=1
						INNER JOIN booking_user ON bui_bkg_id=bkg_id
						WHERE LENGTH(rtg_customer_review)>10 AND ratings.rtg_active=1
						ORDER BY rtg_customer_overall DESC, rtg_customer_date DESC
						LIMIT 0,$limit";
		$recordSet5	 = DBUtil::queryAll($sqlQuery4, DBUtil::SDB());
		if (count($recordSet5) == $limit)
		{
			return $recordSet5;
		}
	}

	public function getRatingCarouselList($pages = 1, $number = 3)
	{
		$numbers			 = $pages * $number;
		$dataprovider		 = $this->getTopRatings($numbers, 2);
		$modelTestimonial	 = $dataprovider->getData();
		$testimonialList	 = [];
		foreach ($modelTestimonial as $key => $testimonial)
		{
			$mod							 = $key % $number;
			$page							 = floor($key / $number);
			$testimonialList[$page][$mod]	 = [
				'initialname'	 => $testimonial['initial'],
				'username'		 => $testimonial['user_name'],
				'text'			 => $testimonial['rtg_customer_review'],
				'date'			 => date('jS M Y', strtotime($testimonial['rtg_customer_date']))
			];
		}
		return $testimonialList;
	}

	/**
	 * 
	 * @param Array $data
	 * @param integer $user_id
	 * @return boolean
	 */
	public function addVendorRating($data, $user_id)
	{
		$bkgId	 = $data['rtg_booking_id'];
		$success = false;
		$model	 = Ratings::model()->find("rtg_booking_id=$bkgId");
		if ($model == null)
		{
			$model = new Ratings();
		}
		$model->attributes			 = $data;
		$model->rtg_vendor_review	 = Filter::sanitize($model->rtg_vendor_review);
		;
		$model->rtg_vendor_date		 = new CDbExpression('NOW()');
		$bkgModel					 = Booking::model()->findByPk($bkgId);
		try
		{
			if (empty($bkgModel))
			{
				throw new Exception("Booking not validate.\n\t\t" . json_encode($bkgModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			if (!$model->save())
			{
				throw new Exception("Rating not validate.\n\t\t" . json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			$vendorProfile						 = new VendorProfile();
			$vendorProfile->vnp_user_id			 = $user_id;
			$vendorProfile->vnp_booking_id		 = $bkgId;
			$vendorProfile->vnp_attribute_type	 = VendorProfile::TYPE_RATING;
			$vendorProfile->vnp_value_str		 = Filter::sanitize($model->rtg_vendor_review);
			$vendorProfile->vnp_value_int		 = VendorProfile::TYPE_RATING;
			if (!$vendorProfile->save())
			{
				throw new Exception("VendorProfile not validate.\n\t\t" . json_encode($vendorProfile->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			$desc = "Ratings given by vendor";
			if ($model->rtg_vendor_review != '' && $model->rtg_vendor_review != NULL)
			{
				$desc .= " - " . $model->rtg_vendor_review;
			}
			$userInfo	 = UserInfo::getInstance();
			$eventId	 = BookingLog::BOOKING_REVIEWED_BY_VENDOR;
			BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId, $bkgModel);
			$success	 = true;
		}
		catch (Exception $ex)
		{
			Logger::error($ex);
			$success = false;
		}
		return $success;
	}

	public function add($data, $user_id)
	{
		$booking_id	 = $data['rtg_booking_id'];
		$model		 = Ratings::model()->find("rtg_booking_id=$booking_id");
		if ($model == null)
		{
			$model = new Ratings();
		}
		$success				 = false;
		$model->attributes		 = $data;
		$model->rtg_vendor_date	 = new CDbExpression('NOW()');
		if ($model->validate())
		{
			$success = $model->save();
			$Data	 = ['model' => $model];
			$bkgid	 = $booking_id;

			$vendorProfile						 = new VendorProfile();
			$vendorProfile->vnp_user_id			 = $user_id;
			$vendorProfile->vnp_booking_id		 = $booking_id;
			$vendorProfile->vnp_attribute_type	 = VendorProfile::TYPE_RATING;
			$vendorProfile->vnp_value_str		 = $model->rtg_vendor_review;
			$vendorProfile->vnp_value_int		 = VendorProfile::TYPE_RATING;
			$vendorProfile->save();

			$bkgmodel	 = Booking::model()->findByPk($booking_id);
			$desc		 = "Ratings given by vendor";
			$userInfo	 = UserInfo::getInstance();
			$eventid	 = BookingLog::BOOKING_REVIEWED_BY_VENDOR;
			BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $bkgmodel);
		}
		else
		{

			$Data = ['errors' => $model->getErrors()];
		}
		$result = ['success' => $success] + $Data;
		return $result;
	}

	public function fetchList($type = '')
	{
		$selectSql = "SELECT ratings.rtg_id, ratings.rtg_csr_date, ratings.rtg_csr_review, ratings.rtg_csr_vendor,
        ratings.rtg_csr_customer, ratings.rtg_customer_recommend, ratings.rtg_active, ratings.rtg_vendor_reply_status,
        ratings.rtg_booking_id, ratings.rtg_customer_overall, ratings.rtg_customer_driver, ratings.rtg_customer_csr,
        ratings.rtg_customer_car, ratings.rtg_customer_review, ratings.rtg_review_desc, ratings.rtg_vendor_customer, ratings.rtg_vendor_csr,
        ratings.rtg_vendor_review, ratings.rtg_customer_date, ratings.rtg_customer_reply_status, vnd.vnd_name AS vendor_name,
        drv.drv_name as driver_name, 
		booking.bkg_booking_id,vnd.vnd_code,drv.drv_code,
		IF(agents.agt_company != '', agents.agt_company, '') AS agent_name
        FROM `ratings`";
	    $joinSql =  " JOIN `booking` ON booking.bkg_id = ratings.rtg_booking_id AND booking.bkg_active = 1
        INNER JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking_cab.bcb_active = 1
        LEFT JOIN `agents` ON agents.agt_id = booking.bkg_agent_id AND agents.agt_active = 1
        INNER JOIN `drivers` drv ON drv.drv_id = booking_cab.bcb_driver_id AND drv.drv_id = drv.drv_ref_code
        INNER JOIN `vendors` vnd ON vnd.vnd_id = booking_cab.bcb_vendor_id AND vnd.vnd_id = vnd.vnd_ref_code
         ";
		$condSql = " WHERE 1 ";
		if (isset($this->vnd_id) && $this->vnd_id > 0)
		{
			$condSql		 .= " AND booking_cab.bcb_vendor_id='$this->vnd_id' ";
		}
		if (isset($this->vnd_region) && $this->vnd_region != '')
		{
			$condSql		 .= " AND (fromState.stt_zone='$this->vnd_region' OR toState.stt_zone='$this->vnd_region') ";
			$joinSql         .= " INNER JOIN `cities` fromCity ON booking.bkg_from_city_id = fromCity.cty_id
								  INNER JOIN `cities` toCity ON booking.bkg_to_city_id = toCity.cty_id
								  INNER JOIN `states` fromState ON fromState.stt_id = fromCity.cty_state_id
								  INNER JOIN `states` toState ON toState.stt_id = toCity.cty_state_id ";
		}
		if (isset($this->channel_partner_id) && $this->channel_partner_id > 0)
		{
			$condSql		 .= " AND booking.bkg_agent_id='$this->channel_partner_id'";
		}
		if ($this->rtg_create_date1 != "" && $this->rtg_create_date2 != "")
		{
			$condSql		 .= " AND (DATE(ratings.rtg_create_date) BETWEEN '{$this->rtg_create_date1}' AND '{$this->rtg_create_date2}' )";
		}
		if($this->category > 0 || $this->strTags!='')
		{
			if($this->category > 0)
			{
				$condSql		 .= " AND cp.cpr_category = {$this->category}";
			}
			if($this->strTags!=''){
			$searchTags = explode(',',$this->strTags);
			if(count($searchTags) > 0)
			{
				$arr = [];
				foreach($searchTags as $tags)
				{
					$arr[] = "FIND_IN_SET($tags,REPLACE(ctt_tags,' ',''))";
				}
				$search2[] = "(" . implode(' OR ', $arr) . ")";

				$condSql		 .= " AND " . implode(" AND ", $search2);
			}
			}
			$joinSql         .= " INNER JOIN booking_user ON bui_bkg_id = bkg_id
								  INNER JOIN users ON user_id = bkg_user_id AND usr_active = 1
								  INNER JOIN contact_profile cpr ON cpr.cr_is_consumer = user_id AND cpr.cr_status = 1
								  INNER JOIN contact ON ctt_id = cpr.cr_contact_id
								  INNER JOIN contact_pref cp ON cpr.cr_contact_id = cp.cpr_ctt_id
								";
		}
		$sql = $selectSql.$joinSql.$condSql;
		$sqlCount	 = "SELECT count(1) FROM `ratings`".$joinSql.$condSql;
		if ($type == 'export')
		{
			return DBUtil::query($sql . " ORDER BY rtg_id DESC", DBUtil::SDB());
		}
		$pageSize		 = 100;
		$count			 = DBUtil::command($sqlCount, DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 =>
			['attributes'	 =>
				['rtg_booking_id', 'rtg_customer_recommend', 'rtg_customer_overall', 'rtg_customer_driver',
					'rtg_customer_csr', 'rtg_customer_car', 'rtg_customer_review',
					'rtg_customer_date', 'rtg_csr_customer', 'rtg_vendor_customer', 'rtg_vendor_csr', 'rtg_vendor_review',
					'rtg_csr_vendor', 'rtg_csr_review', 'rtg_csr_date'],
				'defaultOrder'	 => 'rtg_id DESC',
			],
			'keyField'		 => 'bkg_id',
			'pagination'	 => ['pageSize' => $pageSize],
		]);
		return $dataprovider;
	}

	public function npsReport($date1 = '', $date2 = '', $type = '')
	{
		$sql = " SELECT 
				SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6, 1, 0)) AS detractors,
				SUM(IF(rtg_customer_recommend BETWEEN 7 AND 8, 1, 0)) AS passives, 
				SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10, 1, 0)) AS promotors,
				TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10, 1, 0)) - SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6, 1, 0))) / SUM(IF(rtg_customer_recommend IS NOT NULL, 1, 0))) * 100, 2) AS nps, SUM(IF(rtg_customer_recommend IS NOT NULL, 1, 0)) AS responded
				FROM   ratings
				WHERE 1  ";
		if ($date1 != '' && $date2 != '')
		{
			$sql .= " AND rtg_customer_date BETWEEN '$date1 00:00:00' AND '$date2 23:59:59'";
		}

		if ($type == 'command')
		{
			return DBUtil::query($sql, DBUtil::SDB());
		}
		else
		{
			$dataprovider = new CSqlDataProvider($sql, [
				'totalItemCount' => 1,
				'sort'			 => ['attributes'	 => [],
					'defaultOrder'	 => ''
				],
				'pagination'	 => ['pageSize' => 1],
			]);
			return $dataprovider;
		}
	}

	public function businesstrendNps()
	{
		$array					 = [];
		$sqlNpsCurrentWeek		 = "SELECT  IFNULL(SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6,1,0)),0) as detractors,
									IFNULL(SUM(IF(rtg_customer_recommend BETWEEN 7 AND 8,1,0)),0) as passives,
									IFNULL(SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10,1,0)),0) as promotors,
									IFNULL(TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10,1,0))-SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6,1,0)))/SUM(IF(rtg_customer_recommend IS NOT NULL,1,0)))*100,2),0) as nps,
									IFNULL(SUM(IF(rtg_customer_recommend IS NOT NULL,1,0)),0) as responded,
									(SELECT IFNULL(COUNT(1),0) FROM `booking_log`  WHERE `blg_event_id` IN ('16,18') AND (blg_created) BETWEEN CONCAT((DATE_SUB( CURDATE(), INTERVAL (dayofweek(CURDATE())-2) DAY)),' 00:00:00') AND CONCAT(CURDATE(),' 23:59:59')) as requested
									FROM ratings WHERE  (rtg_customer_date) BETWEEN CONCAT((DATE_SUB( CURDATE( ) , INTERVAL (dayofweek(CURDATE())-2) DAY )),' 00:00:00') AND CONCAT(CURDATE(),' 23:59:59')";
		$data					 = DBUtil::queryRow($sqlNpsCurrentWeek, DBUtil::SDB());
		$array['cw_destractors'] = $data['detractors'];
		$array['cw_passives']	 = $data['passives'];
		$array['cw_promotors']	 = $data['promotors'];
		$array['cw_nps']		 = $data['nps'];
		$array['cw_responded']	 = $data['responded'];
		$array['cw_requested']	 = $data['requested'];

		$sqlNpsLastWeek			 = "SELECT IFNULL(SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6,1,0)),0) as detractors,
									IFNULL(SUM(IF(rtg_customer_recommend BETWEEN 7 AND 8,1,0)),0) as passives,
									IFNULL(SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10,1,0)),0) as promotors,
									IFNULL(TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10,1,0))-SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6,1,0)))/SUM(IF(rtg_customer_recommend IS NOT NULL,1,0)))*100,2),0) as nps,
									IFNULL(SUM(IF(rtg_customer_recommend IS NOT NULL,1,0)),0) as responded,
									(SELECT IFNULL(COUNT(1),0) FROM `booking_log` WHERE `blg_event_id` IN ('16,18') AND (blg_created) BETWEEN CONCAT((DATE_SUB( CURDATE() , INTERVAL (dayofweek(CURDATE())+5) DAY )),' 00:00:00') AND  CONCAT(DATE_SUB(CURDATE() , INTERVAL (dayofweek(CURDATE())-1) DAY ),' 23:59:59')) as requested
									FROM ratings WHERE (rtg_customer_date) BETWEEN CONCAT(DATE_SUB( CURDATE( ) , INTERVAL (dayofweek(CURDATE())+5) DAY ), ' 00:00:00') AND CONCAT( DATE_SUB(CURDATE() , INTERVAL (dayofweek(CURDATE())-1) DAY ),' 23:59:59')";
		$data1					 = DBUtil::queryRow($sqlNpsLastWeek, DBUtil::SDB());
		$array['lw_destractors'] = $data1['detractors'];
		$array['lw_passives']	 = $data1['passives'];
		$array['lw_promotors']	 = $data1['promotors'];
		$array['lw_nps']		 = $data1['nps'];
		$array['lw_responded']	 = $data1['responded'];
		$array['lw_requested']	 = $data1['requested'];
		$sqlNpsCurrentMonth		 = "SELECT IFNULL(SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6,1,0)),0) as detractors,
									IFNULL(SUM(IF(rtg_customer_recommend BETWEEN 7 AND 8,1,0)),0) as passives,
									IFNULL(SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10,1,0)),0) as promotors,
									IFNULL(TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10,1,0))-SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6,1,0)))/SUM(IF(rtg_customer_recommend IS NOT NULL,1,0)))*100,2),0) as nps,
									IFNULL(SUM(IF(rtg_customer_recommend IS NOT NULL,1,0)),0) as responded,
									(SELECT IFNULL(COUNT(1),0) FROM `booking_log` WHERE `blg_event_id` IN ('16,18') AND blg_created BETWEEN CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'), ' 00:00:00') AND NOW()) as requested
									FROM ratings
									WHERE rtg_customer_date BETWEEN  CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'), ' 00:00:00') AND NOW()";
		$data2					 = DBUtil::queryRow($sqlNpsCurrentMonth, DBUtil::SDB());
		$array['cm_destractors'] = $data2['detractors'];
		$array['cm_passives']	 = $data2['passives'];
		$array['cm_promotors']	 = $data2['promotors'];
		$array['cm_nps']		 = $data2['nps'];
		$array['cm_responded']	 = $data2['responded'];
		$array['cm_requested']	 = $data2['requested'];
		$sqlNpsLastMonth		 = "SELECT 
									IFNULL(SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6, 1, 0)), 0)   AS detractors,
									IFNULL(SUM(IF(rtg_customer_recommend BETWEEN 7 AND 8, 1, 0)), 0)   AS passives,
									IFNULL(SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10, 1, 0)), 0)  AS promotors,
									IFNULL(TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10, 1, 0)) - SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6, 1, 0))) / SUM(IF(rtg_customer_recommend IS NOT NULL, 1, 0))) * 100, 2), 0) AS nps,
									IFNULL(SUM(IF(rtg_customer_recommend IS NOT NULL, 1, 0)), 0) AS responded,
									(SELECT IFNULL(COUNT(1), 0) FROM   `booking_log`  WHERE  `blg_event_id` IN ('16,18') AND (blg_created) BETWEEN DATE_SUB(LAST_DAY(DATE_SUB(NOW(), INTERVAL 1 MONTH)),INTERVAL DAY(LAST_DAY(DATE_SUB(NOW(), INTERVAL 1 MONTH)))-1 DAY) AND CONCAT(LAST_DAY(DATE_SUB(NOW(), INTERVAL 1 MONTH)), ' 23:59:59')) AS requested
									FROM   ratings
									WHERE  (rtg_customer_date) 
									BETWEEN DATE_SUB(LAST_DAY(DATE_SUB(NOW(), INTERVAL 1 MONTH)),INTERVAL DAY(LAST_DAY(DATE_SUB(NOW(), INTERVAL 1 MONTH)))-1 DAY) AND CONCAT(LAST_DAY(DATE_SUB(NOW(), INTERVAL 1 MONTH)), ' 23:59:59')";
		$data3					 = DBUtil::queryRow($sqlNpsLastMonth, DBUtil::SDB());
		$array['lm_destractors'] = $data3['detractors'];
		$array['lm_passives']	 = $data3['passives'];
		$array['lm_promotors']	 = $data3['promotors'];
		$array['lm_nps']		 = $data3['nps'];
		$array['lm_responded']	 = $data3['responded'];
		$array['lm_requested']	 = $data3['requested'];
		return $array;
	}

	public function getDetailsByRatingId($rtgId)
	{
		$sql = "SELECT
				ratings.rtg_id,
				ratings.rtg_customer_overall,
				ratings.rtg_customer_recommend,
				ratings.rtg_customer_review,
				CONCAT(users.usr_name, ' ', users.usr_lname) AS usr_name,
				users.usr_refer_code,
				booking_user.bkg_user_email,
				booking.bkg_booking_id AS booking_id,
				booking.bkg_agent_id agent_id,
				booking.bkg_agent_id corporate_id,
				booking.bkg_id,
				vendors.vnd_name as vendor_name,
				d1.drv_name as driver_name
			FROM  `ratings`
			INNER JOIN `booking` ON booking.bkg_id = ratings.rtg_booking_id AND booking.bkg_active = 1
			INNER JOIN `booking_user` ON booking.bkg_id=booking_user.bui_bkg_id
			INNER JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking_cab.bcb_active = 1
			INNER JOIN `users` ON users.user_id = booking_user.bkg_user_id 
			INNER JOIN `drivers` d1 ON d1.drv_id=booking_cab.bcb_driver_id 
			JOIN `vendors` ON vendors.vnd_id=booking_cab.bcb_vendor_id
			WHERE ratings.rtg_id = '$rtgId'  LIMIT 0,1";
		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	public function getDriverVendorDetailsById($rtgId)
	{

		$sql = "SELECT
				ratings.rtg_id,
				ratings.rtg_booking_id,
				ratings.rtg_customer_recommend,
				ratings.rtg_customer_review,
				ratings.rtg_customer_overall,
				ratings.rtg_customer_car,
				ratings.rtg_customer_csr,
				ratings.rtg_customer_driver,
				ratings.rtg_csr_cmt,
				ratings.rtg_driver_cmt,
				ratings.rtg_car_cmt,
				ratings.rtg_platform_exp_cmt,
				ratings.rtg_customer_date,
				vendors.vnd_id,
				vendors.vnd_name,
				IF(contact.ctt_user_type=1,IF(contact.ctt_first_name IS NOT NULL && contact.ctt_last_name IS NOT NULL,CONCAT(contact.ctt_first_name, ' ',contact.ctt_last_name),contact.ctt_first_name),contact.ctt_business_name) AS vnd_owner,
				contact_phone.phn_phone_no AS vnd_phone,
				contact_email.eml_email_address AS vnd_email,
				vendors.vnd_code,
				drivers.drv_id,
				drivers.drv_name,
				eml.eml_email_address AS drv_email,
				phn.phn_phone_no AS drv_phone,
				drivers.drv_approved,
				drivers.drv_code,
				vehicles.vhc_id,
				vehicles.vhc_number,
				vehicles.vhc_code,
				vehicle_types.vht_make,
                vehicle_types.vht_model,
				booking_user.bkg_user_email,
				CONCAT(booking_user.bkg_user_fname,' ',booking_user.bkg_user_lname) AS user_name,
				booking.bkg_booking_id,
				booking.bkg_id,
				booking.bkg_agent_id,
				booking_user.bkg_contact_no,
				booking_user.bkg_user_id,
				booking_user.bkg_country_code,
				IF(booking.bkg_agent_id > 0,CONCAT(agents.agt_fname,' ',agents.agt_lname),'') AS agt_name,
				IF(vendor_stats.vrs_vnd_overall_rating > 0,vendor_stats.vrs_vnd_overall_rating,0) AS vendor_rating,
				IF(drivers.drv_overall_rating > 0,drivers.drv_overall_rating,0) AS driver_rating,
				IF(vehicles.vhc_overall_rating > 0,vehicles.vhc_overall_rating,0) AS cab_rating,
				IF(drivers.drv_approved = 1, 'Y', 'N') AS driver_is_approved,
				IF(vehicles.vhc_approved = 1,'Y','N') AS vehicle_is_approved,
				IF(vehicles.vhc_is_commercial = 1,'Y','N') AS vhc_is_commercial,
				CONCAT(c1.cty_name,' - ',c2.cty_name) AS customer_route
				FROM `ratings`
				INNER JOIN `booking` ON booking.bkg_id = ratings.rtg_booking_id AND booking.bkg_active = 1
				INNER JOIN `booking_user` ON booking.bkg_id = booking_user.bui_bkg_id
				INNER JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking_cab.bcb_active = 1
				LEFT JOIN `agents` ON agents.agt_id = booking.bkg_agent_id
				JOIN `vendors` AS v ON v.vnd_id = booking_cab.bcb_vendor_id
				JOIN vendors ON vendors.vnd_id = v.vnd_ref_code AND vendors.vnd_active =1 
				JOIN `vendor_stats` ON vendor_stats.vrs_vnd_id = vendors.vnd_id				
				JOIN contact_profile AS vcp ON vcp.cr_is_vendor = vendors.vnd_id AND vcp.cr_status =1 
				JOIN contact ON contact.ctt_id = vcp.cr_contact_id AND contact.ctt_active =1 AND contact.ctt_id = contact.ctt_ref_code
				LEFT JOIN contact_phone ON contact_phone.phn_contact_id = contact.ctt_id AND contact_phone.phn_is_primary=1
				LEFT JOIN contact_email ON contact_email.eml_contact_id = contact.ctt_id AND contact_email.eml_is_primary=1	
				JOIN `drivers` AS d ON d.drv_id = booking_cab.bcb_driver_id 
				JOIN drivers ON drivers.drv_id = d.drv_ref_code AND d.drv_active =1
				JOIN contact_profile AS dvcp ON dvcp.cr_is_driver = drivers.drv_id AND dvcp.cr_status=1 
				JOIN contact AS ctt ON ctt.ctt_id = dvcp.cr_contact_id AND ctt.ctt_active =1 AND ctt.ctt_id = ctt.ctt_ref_code
				LEFT JOIN contact_phone phn ON phn.phn_contact_id = ctt.ctt_id AND phn.phn_is_primary=1
				LEFT JOIN contact_email eml ON eml.eml_contact_id = ctt.ctt_id AND eml.eml_is_primary=1
				JOIN `vehicles` ON vehicles.vhc_id = booking_cab.bcb_cab_id 
				JOIN `vehicle_types` ON vehicle_types.vht_id = vehicles.vhc_type_id
				JOIN `cities` c1 ON c1.cty_id= booking.bkg_from_city_id 
				JOIN `cities` c2 ON c2.cty_id= booking.bkg_to_city_id
				WHERE ratings.rtg_id = '$rtgId'  LIMIT 0,1";
		return DBUtil::queryRow($sql);
	}

	public function getListByVendorId($qry)
	{
		$vendorId	 = $qry['vendorId'];
		$driverName	 = ($qry['driverName'] != '') ? $qry['driverName'] : '';
		$bookingId	 = ($qry['bookingId'] != '') ? $qry['bookingId'] : '';
		$sql		 = "SELECT ratings.*, booking.bkg_booking_id,drv_name
                FROM `ratings`
                INNER JOIN `booking` ON booking.bkg_id=ratings.rtg_booking_id AND booking.bkg_active=1
                INNER JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1
                JOIN `drivers` ON drv_id=booking_cab.bcb_driver_id
				WHERE 1 AND booking_cab.bcb_vendor_id=$vendorId";
		if ($driverName != '')
		{
			$sql .= " AND drv_name LIKE '%$driverName%'";
		}
		if ($bookingId != '')
		{
			$sql .= " AND booking.bkg_booking_id='$bookingId'";
		}
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['bkg_booking_id', 'drv_name', 'rtg_customer_date'],
				'defaultOrder'	 => 'rtg_id DESC'], 'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function getCommentByOverallRating($rtg_overall)
	{
		switch ($rtg_overall)
		{
			case 1:
				$comment = 'Bad';
				break;
			case 2:
				$comment = 'Poor';
				break;
			case 3:
				$comment = 'Average';
				break;
			case 4:
				$comment = 'Good';
				break;
			case 5:
				$comment = 'Excellent';
				break;
		}
		return $comment;
	}

	public function updateDriverInfo($drvId)
	{
		if ($this->rtg_driver_ontime != '' && $this->rtg_driver_ontime != NULL && $this->rtg_driver_ontime != 0)
		{
			Drivers::model()->updateOntimeCount($drvId);
		}
		if ($this->rtg_driver_softspokon != '' && $this->rtg_driver_softspokon != NULL && $this->rtg_driver_softspokon != 0)
		{
			Drivers::model()->updateSoftspokonCount($drvId);
		}
		if ($this->rtg_driver_respectfully != '' && $this->rtg_driver_respectfully != NULL && $this->rtg_driver_respectfully != 0)
		{
			Drivers::model()->updateRespectfullyCount($drvId);
		}
		if ($this->rtg_driver_safely != '' && $this->rtg_driver_safely != NULL && $this->rtg_driver_safely != 0)
		{
			Drivers::model()->updateSafelyCount($drvId);
		}
		if ($this->rtg_driver_helpful != '' && $this->rtg_driver_helpful != NULL && $this->rtg_driver_helpful != 0)
		{
			Drivers::model()->updateHelpfulCount($drvId);
		}
		$rtnSt = ($drvId > 0) ? true : false;
		return $rtnSt;
	}

	public function updateVendorInfo($vndId)
	{
		if ($this->rtg_driver_vendor_mismatch != '' && $this->rtg_driver_vendor_mismatch != NULL && $this->rtg_driver_vendor_mismatch != 0)
		{
			Vendors::model()->updateDriverMismatchCount($vndId);
		}
		if ($this->rtg_car_vendor_mismatch != '' && $this->rtg_car_vendor_mismatch != NULL && $this->rtg_car_vendor_mismatch != 0)
		{
			Vendors::model()->updateCarMismatchCount($vndId);
		}
		$rtnSt = ($drvId > 0) ? true : false;
		return $rtnSt;
	}

	public function updateCabInfo($vhcId)
	{
		if ($this->rtg_car_clean != '' && $this->rtg_car_clean != NULL && $this->rtg_car_clean != 0)
		{
			Vehicles::model()->updatCleanCount($vhcId);
		}
		if ($this->rtg_car_good_cond != '' && $this->rtg_car_good_cond != NULL && $this->rtg_car_good_cond != 0)
		{
			Vehicles::model()->updatGoodConditionCount($vhcId);
		}
		if ($this->rtg_car_commercial != '' && $this->rtg_car_commercial != NULL && $this->rtg_car_commercial != 0)
		{
			Vehicles::model()->updatCommercialCount($vhcId);
		}
		$rtnSt = ($vhcId > 0) ? true : false;
		return $rtnSt;
	}

	public function getNpsList_OLD()
	{
		$sql			 = "SELECT   
							SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6, 1, 0)) AS detractors,
							SUM(IF(rtg_customer_recommend BETWEEN 7 AND 8, 1, 0)) AS passives,
							SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10, 1, 0)) AS promotors,
							TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10, 1, 0)) - SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6, 1, 0))) / SUM(IF(rtg_customer_recommend IS NOT NULL, 1, 0))) * 100, 2) AS nps, 
							SUM(IF(rtg_customer_recommend IS NOT NULL, 1, 0)) AS responded, 
							MONTHNAME(ratings.rtg_customer_date) AS monthName, 
							MONTH(ratings.rtg_customer_date) AS monthId, 
							YEAR(ratings.rtg_customer_date) AS yearName
							FROM     `ratings`
							JOIN `booking` ON booking.bkg_id = ratings.rtg_booking_id AND booking.bkg_active = 1
							WHERE    ratings.rtg_customer_date BETWEEN CONCAT(DATE_SUB(DATE_FORMAT(NOW(), '%Y-%m-01'), INTERVAL 1 YEAR), ' 00:00:00') AND CONCAT(DATE_FORMAT(NOW(), '%Y-%m-31'),' 23:59:59')
							GROUP BY MONTH(ratings.rtg_customer_date),YEAR(ratings.rtg_customer_date)
							ORDER BY YEAR(ratings.rtg_customer_date) DESC, MONTH(ratings.rtg_customer_date) DESC";
		$sqlCount		 = "SELECT   
							ratings.rtg_id
							FROM     `ratings`
							JOIN `booking` ON booking.bkg_id = ratings.rtg_booking_id AND booking.bkg_active = 1
							WHERE ratings.rtg_customer_date BETWEEN CONCAT(DATE_SUB(DATE_FORMAT(NOW(), '%Y-%m-01'), INTERVAL 1 YEAR), ' 00:00:00') AND CONCAT(DATE_FORMAT(NOW(), '%Y-%m-31'),' 23:59:59')
							GROUP BY MONTH(ratings.rtg_customer_date),YEAR(ratings.rtg_customer_date)";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => [''],
				'defaultOrder'	 => ''],
			'pagination'	 => ['pageSize' => 100],
		]);
		return $dataprovider;
	}

	public function getNpsByRegion($month, $year, $zoneIds)
	{
		DBUtil::getINStatement($zoneIds, $bindString, $params);
		$sql = "SELECT 	TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10,1,0))-SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6,1,0)))/SUM(IF(rtg_customer_recommend IS NOT NULL,1,0)))*100,2) as nps
                FROM `ratings`
                LEFT JOIN `booking` ON booking.bkg_id=ratings.rtg_booking_id AND booking.bkg_active=1
                JOIN `cities` ON cities.cty_id=booking.bkg_from_city_id
                JOIN `states` ON states.stt_id=cities.cty_state_id
                WHERE YEAR(ratings.rtg_customer_date)=$year
                AND MONTH(ratings.rtg_customer_date)=$month
                AND states.stt_zone IN ($bindString)
                GROUP BY states.stt_zone";
		$nps = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return ($nps != '') ? $nps : 0;
	}

	public function getNpsByRegionLastYear_OLD()
	{
		$sql = "SELECT YEAR(ratings.rtg_customer_date) year,
				MONTH(ratings.rtg_customer_date) month,
				states.stt_zone,
				TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10,1,0))-SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6,1,0)))/SUM(IF(rtg_customer_recommend IS NOT NULL,1,0)))*100,2) as nps
				FROM `ratings`
				JOIN `booking` ON booking.bkg_id=ratings.rtg_booking_id AND booking.bkg_active=1
				JOIN `cities` ON cities.cty_id=booking.bkg_from_city_id
				JOIN `states` ON states.stt_id=cities.cty_state_id
				where  ratings.rtg_customer_date BETWEEN CONCAT(DATE_SUB(DATE_FORMAT(NOW(), '%Y-%m-01'), INTERVAL 1 YEAR), ' 00:00:00') AND CONCAT(DATE_FORMAT(NOW(), '%Y-%m-31'),' 23:59:59')
				GROUP BY YEAR(ratings.rtg_customer_date), MONTH(ratings.rtg_customer_date),states.stt_zone";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function getNpsByRegionLastYear()
	{
		$where		 = '';
		$fromDate	 = $this->rtg_date1 . " 00:00:00";
		$toDate		 = $this->rtg_date2 . " 23:59:59";
		$groupBy	 = $this->groupvar;

		if ($fromDate != '' && $toDate != '')
		{
			$where .= " AND (ratings.rtg_customer_date BETWEEN '{$fromDate}' AND '{$toDate}')";
		}
		if (count($this->bkgtypes) > 0)
		{
			$bkgTypeStr	 = implode(",", $this->bkgtypes);
			$where		 .= " AND booking.bkg_booking_type IN ({$bkgTypeStr}) ";
		}

		$sql = "SELECT 
				DATE_FORMAT(ratings.rtg_customer_date, '%Y-%m-%d') AS date,
				DATE_FORMAT(ratings.rtg_customer_date, '%x-%v') AS week, 
				DATE_FORMAT(ratings.rtg_customer_date, '%Y-%m') AS month,
				states.stt_zone,
				TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10,1,0))-SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6,1,0)))/SUM(IF(rtg_customer_recommend IS NOT NULL,1,0)))*100,2) as nps
				FROM `ratings` 
				JOIN `booking` ON booking.bkg_id=ratings.rtg_booking_id AND booking.bkg_active=1 
				JOIN `cities` ON cities.cty_id=booking.bkg_from_city_id 
				JOIN `states` ON states.stt_id=cities.cty_state_id 
				WHERE 1 $where 
				GROUP BY {$groupBy}, states.stt_zone";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function getNpsList()
	{
		$where		 = '';
		$fromDate	 = $this->rtg_date1 . " 00:00:00";
		$toDate		 = $this->rtg_date2 . " 23:59:59";
		$groupBy	 = $this->groupvar;
		$orderBy	 = $this->groupvar;
		if ($fromDate != '' && $toDate != '')
		{
			$where .= " AND (ratings.rtg_customer_date BETWEEN '{$fromDate}' AND '{$toDate}')";
		}
		if (count($this->bkgtypes) > 0)
		{
			$bkgTypeStr	 = implode(",", $this->bkgtypes);
			$where		 .= " AND booking.bkg_booking_type IN ({$bkgTypeStr}) ";
		}
		$sql			 = "SELECT 
							SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6, 1, 0)) AS detractors,
							SUM(IF(rtg_customer_recommend BETWEEN 7 AND 8, 1, 0)) AS passives,
							SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10, 1, 0)) AS promotors,
							TRUNCATE(((SUM(IF(rtg_customer_recommend BETWEEN 9 AND 10, 1, 0)) - SUM(IF(rtg_customer_recommend BETWEEN 1 AND 6, 1, 0))) / SUM(IF(rtg_customer_recommend IS NOT NULL, 1, 0))) * 100, 2) AS nps, 
							SUM(IF(rtg_customer_recommend IS NOT NULL, 1, 0)) AS responded, 
							DATE_FORMAT(ratings.rtg_customer_date, '%Y-%m-%d') AS date,
							DATE_FORMAT(ratings.rtg_customer_date, '%x-%v') AS week, 
							DATE_FORMAT(ratings.rtg_customer_date, '%Y-%m') AS month,
							CONCAT(DATE_FORMAT(ratings.rtg_customer_date, '%x-%v'), '\n',DATE_FORMAT(MIN(ratings.rtg_customer_date), '%D %b'),' - ',DATE_FORMAT(MAX(ratings.rtg_customer_date), '%D %b')) as weekLabel 
							FROM `ratings`
							INNER JOIN `booking` ON booking.bkg_id = ratings.rtg_booking_id AND booking.bkg_active = 1
							WHERE 1 $where 
							GROUP BY {$groupBy} 
							ORDER BY {$orderBy} DESC";
		$sqlCount		 = "SELECT ratings.rtg_id, 
							DATE_FORMAT(ratings.rtg_customer_date, '%Y-%m-%d') AS date, 
							DATE_FORMAT(ratings.rtg_customer_date, '%x-%v') AS week, 
							DATE_FORMAT(ratings.rtg_customer_date, '%Y-%m') AS month 
							FROM `ratings` 
							INNER JOIN `booking` ON booking.bkg_id = ratings.rtg_booking_id AND booking.bkg_active = 1 
							WHERE 1 $where
							GROUP BY {$groupBy}";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes' => [''], 'defaultOrder' => ''],
			'pagination'	 => ['pageSize' => 100],
		]);
		return $dataprovider;
	}

	public function getVendorIdByBookingId($bkg_id)
	{
		$sql = "SELECT vnd.vnd_id FROM vendors vnd INNER JOIN booking_cab bcb ON bcb.bcb_vendor_id=vnd.vnd_id INNER JOIN booking bkg ON bkg.bkg_bcb_id=bcb.bcb_id WHERE bkg.bkg_id= $bkg_id LIMIT 0,1";
		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	public function getCarAveragerating($vhc_id)
	{
		$where = "";
		if ($vhc_id > 0)
		{
			$where = " AND bcb_cab_id = $vhc_id";
		}
		$defaultDays	 = 60;
		$defaultRating	 = 8;
		$minNoReview	 = 7;
		$sql			 = "SELECT bcb_cab_number, bcb_cab_id, rtg_booking_id, bkg_id, COUNT(rtg_booking_id) cnt, SUM(rtg_customer_car), AVG(rtg_customer_car) avgRating,
                                    IF(COUNT(rtg_booking_id) < $minNoReview, ((SUM(rtg_customer_car) + (($minNoReview - COUNT(rtg_booking_id)) * $defaultRating))/$minNoReview), AVG(rtg_customer_car)) calcRate,
                                    SUM(IF(TIMESTAMPDIFF(DAY, rtg_customer_date, CURRENT_DATE)>$defaultDays,1,0)) AS old60,
                                    SUM(IF(TIMESTAMPDIFF(DAY, rtg_customer_date, CURRENT_DATE)<=$defaultDays,1,0)) AS new60,
                                    SUM(IF(TIMESTAMPDIFF(DAY, rtg_customer_date, CURRENT_DATE)>$defaultDays,rtg_customer_car,0)) AS sumold60,
                                    SUM(IF(TIMESTAMPDIFF(DAY, rtg_customer_date, CURRENT_DATE)<=$defaultDays,rtg_customer_car,0)) AS sumnew60,
                                    (SUM(IF(TIMESTAMPDIFF(DAY, rtg_customer_date, CURRENT_DATE)>$defaultDays,rtg_customer_car,0))/ SUM(IF(TIMESTAMPDIFF(DAY, rtg_customer_date, CURRENT_DATE)>$defaultDays,1,0))) AS avgold60,
                                    (SUM(IF(TIMESTAMPDIFF(DAY, rtg_customer_date, CURRENT_DATE)<=$defaultDays,rtg_customer_car,0))/ SUM(IF(TIMESTAMPDIFF(DAY, rtg_customer_date, CURRENT_DATE)<=$defaultDays,1,0))) AS avgnew60
                            FROM (
                                    SELECT bcb_cab_number, bcb_id, bcb_cab_id, rtg_booking_id, bkg_id,
                                                    CASE IF(rtg_customer_car > 0, rtg_customer_car, rtg_customer_overall)
                                                            WHEN 5 THEN 10.0
                                                            WHEN 4 THEN 7.5
                                                            WHEN 3 THEN 5.0
                                                            WHEN 2 THEN 2.5
                                                            WHEN 1 THEN 0.0
                                                    END AS rtg_customer_car,
                                                    rtg_customer_date, TIMESTAMPDIFF(DAY, rtg_customer_date, NOW()) rtgdatediff
                                    FROM `ratings` rtg
                                    JOIN booking bkg ON bkg.bkg_id = rtg.rtg_booking_id AND bkg.bkg_status IN (6, 7)
                                    JOIN booking_cab ON bcb_id = bkg.bkg_bcb_id AND bcb_active = 1 AND bcb_cab_id > 0  $where
                                    WHERE rtg_customer_car > 0 OR rtg_customer_overall > 0
                            ) b
                            GROUP BY bcb_cab_id
                            ORDER BY cnt ASC";
		$data			 = DBUtil::queryAll($sql, DBUtil::SDB());
		$i				 = 0;
		foreach ($data as $val)
		{
			$cabModel = CabStats::model()->saveScore($val);
			$i++;
		}
		return $i;
	}

	public function getDriverAveragerating($drv_id = 0)
	{
		$where = "";
		if ($drv_id > 0)
		{
			$where = " AND bcb_driver_id = $drv_id";
		}
		$defaultDays	 = 60;
		$defaultRating	 = 8;
		$minNoReview	 = 7;
		$sql			 = "SELECT bcb_driver_id, rtg_booking_id, bkg_id, COUNT(rtg_booking_id) cnt, SUM(rtg_customer_driver), AVG(rtg_customer_driver) avgRating,
                                    IF(COUNT(rtg_booking_id) < $minNoReview, ((SUM(rtg_customer_driver) + (($minNoReview - COUNT(rtg_booking_id)) * $defaultRating))/$minNoReview), AVG(rtg_customer_driver)) calcRate,
                                    SUM(IF(TIMESTAMPDIFF(DAY, rtg_customer_date, CURRENT_DATE)>$defaultDays,1,0)) AS old60,
                                    SUM(IF(TIMESTAMPDIFF(DAY, rtg_customer_date, CURRENT_DATE)<=$defaultDays,1,0)) AS new60,
                                    SUM(IF(TIMESTAMPDIFF(DAY, rtg_customer_date, CURRENT_DATE)>$defaultDays,rtg_customer_driver,0)) AS sumold60,
                                    SUM(IF(TIMESTAMPDIFF(DAY, rtg_customer_date, CURRENT_DATE)<=$defaultDays,rtg_customer_driver,0)) AS sumnew60,
                                    (SUM(IF(TIMESTAMPDIFF(DAY, rtg_customer_date, CURRENT_DATE)>$defaultDays,rtg_customer_driver,0))/ SUM(IF(TIMESTAMPDIFF(DAY, rtg_customer_date, CURRENT_DATE)>$defaultDays,1,0))) AS avgold60,
                                    (SUM(IF(TIMESTAMPDIFF(DAY, rtg_customer_date, CURRENT_DATE)<=$defaultDays,rtg_customer_driver,0))/ SUM(IF(TIMESTAMPDIFF(DAY, rtg_customer_date, CURRENT_DATE)<=$defaultDays,1,0))) AS avgnew60
                            FROM (
                                    SELECT  bcb_id, IFNULL(drv_ref_code, drv_id) as bcb_driver_id, rtg_booking_id, bkg_id,
                                                    CASE IF(rtg_customer_driver > 0, rtg_customer_driver, rtg_customer_overall)
                                                            WHEN 5 THEN 10.0
                                                            WHEN 4 THEN 7.5
                                                            WHEN 3 THEN 5.0
                                                            WHEN 2 THEN 2.5
                                                            WHEN 1 THEN 0.0
                                                    END AS rtg_customer_driver,
                                                    rtg_customer_date, TIMESTAMPDIFF(DAY, rtg_customer_date, NOW()) rtgdatediff
                                    FROM `ratings` rtg
                                    JOIN booking bkg ON bkg.bkg_id = rtg.rtg_booking_id AND bkg.bkg_status IN (6, 7)
                                    JOIN booking_cab ON bcb_id = bkg.bkg_bcb_id AND bcb_active = 1 AND bcb_driver_id > 0
									INNER JOIN drivers ON drv_id=bcb_driver_id  $where 
                                    WHERE rtg_customer_driver > 0 OR rtg_customer_overall > 0
                            ) b where 1  $where 
                            GROUP BY bcb_driver_id";

		$data	 = DBUtil::query($sql, DBUtil::SDB());
		$i		 = 0;
		foreach ($data as $val)
		{
			$drvModel = DriverStats::model()->saveScore($val);
			$i++;
		}
		return $i;
	}

	public function getVendorAveragerating($vnd_id = 0)
	{
		$where = "";
		if ($vnd_id > 0)
		{
			$where = " AND bcb_vendor_id = $vnd_id";
		}
		$defaultDays	 = 60;
		$defaultRating	 = 8;
		$minNoReview	 = 7;
		$sql			 = "SELECT  bcb_vendor_id,rtg_booking_id, bkg_id,
            COUNT(rtg_booking_id) cnt,
            SUM(rtg_vendor),
            AVG(rtg_vendor) avgRating,
IF(COUNT(rtg_booking_id) < $minNoReview, ((SUM(rtg_vendor) + (($minNoReview - COUNT(rtg_booking_id)) * $defaultRating))/$minNoReview), AVG(rtg_vendor)) calcRate,
SUM(IF(TIMESTAMPDIFF(DAY, rtg_customer_date, CURRENT_DATE)>$defaultDays,1,0)) AS old60,
SUM(IF(TIMESTAMPDIFF(DAY, rtg_customer_date, CURRENT_DATE)<=$defaultDays,1,0)) AS new60,
SUM(IF(TIMESTAMPDIFF(DAY, rtg_customer_date, CURRENT_DATE)>$defaultDays,rtg_vendor,0)) AS sumold60,
SUM(IF(TIMESTAMPDIFF(DAY, rtg_customer_date, CURRENT_DATE)<=$defaultDays,rtg_vendor,0)) AS sumnew60,
(SUM(IF(TIMESTAMPDIFF(DAY, rtg_customer_date, CURRENT_DATE)>$defaultDays,rtg_vendor,0))/ SUM(IF(TIMESTAMPDIFF(DAY, rtg_customer_date, CURRENT_DATE)>$defaultDays,1,0))) AS avgold60,
(SUM(IF(TIMESTAMPDIFF(DAY, rtg_customer_date, CURRENT_DATE)<=$defaultDays,rtg_vendor,0))/ SUM(IF(TIMESTAMPDIFF(DAY, rtg_customer_date, CURRENT_DATE)<=$defaultDays,1,0))) AS avgnew60
FROM (
SELECT  IFNULL(vnd_ref_code, vnd_id) as bcb_vendor_id, rtg_booking_id, bkg_id,
 rtg_csr_vendor,
       if(rtg_customer_driver > 0, rtg_customer_driver, IFNULL(rtg_customer_overall,0))
          AS rtg_customer_driver,
       if(rtg_customer_car > 0, rtg_customer_car, IFNULL(rtg_customer_overall,0))
          AS rtg_customer_car,
       rtg_customer_date,
       CASE ceil(
        IF(
          rtg_csr_vendor > 0,
          (rtg_csr_vendor +
           ((IF(
               rtg_customer_driver > 0,
               rtg_customer_driver,
               IFNULL(rtg_customer_overall,0)) +
             IF(
               rtg_customer_car > 0,
               rtg_customer_car,
               IFNULL(rtg_customer_overall,0))) /
            2)) /
          2,
          ((IF(
              rtg_customer_driver > 0,
              rtg_customer_driver,
              IFNULL(rtg_customer_overall,0)) +
            IF(
              rtg_customer_car > 0,
              rtg_customer_car,
              IFNULL(rtg_customer_overall,0))) /
           2)))
                WHEN 5 THEN 10.0
                WHEN 4 THEN 7.5
                WHEN 3 THEN 5.0
                WHEN 2 THEN 2.5
                WHEN 1 THEN 0.0
            END AS rtg_vendor,
 TIMESTAMPDIFF(DAY, rtg_customer_date, NOW()) rtgdatediff
FROM `ratings` rtg
INNER JOIN booking bkg ON bkg.bkg_id = rtg.rtg_booking_id AND bkg.bkg_status IN (6, 7)
INNER JOIN booking_cab ON bcb_id = bkg.bkg_bcb_id AND bcb_active = 1 AND bcb_vendor_id > 0
INNER JOIN vendors ON vnd_id=bcb_vendor_id AND vnd_active IN(1,2) $where 
 WHERE ((rtg_csr_vendor > 0 OR rtg_customer_driver>0  OR rtg_customer_car>0) OR rtg_customer_overall>0) AND rtg_create_date IS NOT NULL
) b
where 1  $where
GROUP BY bcb_vendor_id 
ORDER BY cnt ASC";
		$data			 = DBUtil::query($sql, DBUtil::SDB());
		$i				 = 0;
		foreach ($data as $val)
		{
			$vendorModel = VendorStats::model()->saveScore($val);

			$i++;
		}
		return $i;
	}

	/**
	 * 
	 * @param string $uniqueId
	 * @param string $referCode
	 * @param string $comment
	 * @return array
	 */
	public static function reviewButtonLink($uniqueId = '', $referCode = '', $comment)
	{
		$reviewLink			 = ($uniqueId != '') ? 'https://' . Yii::app()->params['host'] . '/r/' . $uniqueId : '';
		$reviewLink			 = Filter::shortUrl($reviewLink);
		$signupLink			 = 'https://' . Yii::app()->params['host'] . '/signup';
		$signupLink			 = Filter::shortUrl($signupLink);
		$inviteLink			 = ($referCode != '') ? 'https://' . Yii::app()->params['host'] . '/invite/' . $referCode : '';
		$inviteLink			 = Filter::shortUrl($inviteLink);
		$tripAdviserLink	 = "https://goo.gl/OW4gUc";
		$googleShareLink	 = "https://goo.gl/2NfzbR";
		$text				 = 'I just traveled with Gozo Cabs and I loved it. I just wrote my review on GozoCabs. See my review on this link. ';
		$text				 .= 'My review is "' . $comment . '" Read my full review at ' . $reviewLink . '';
		$whatappShareLink	 = "whatsapp://send?text=" . urlencode($text) . "";
		return array('reviewLink'		 => $reviewLink,
			'signupLink'		 => $signupLink,
			'inviteLink'		 => $inviteLink,
			'tripAdviserLink'	 => $tripAdviserLink,
			'googleShareLink'	 => $googleShareLink,
			'whatappShareLink'	 => $whatappShareLink);
	}

	public static function CalculateRating($values, $vendorID = null)
	{
		//$values =array("old60"=>0,"new60"=>3,"cnt"=>3,"avgold60"=>null,"avgnew60"=>5,"sumnew60"=>15,"sumold60"=>0);
		//$values = array("old60"=>1,"new60"=>2,"cnt"=>3,"avgold60"=>5,"avgnew60"=>5,"sumnew60"=>10,"sumold60"=>5);
		$date				 = date('Y-m-d h:i:s');
		$defaultRating		 = 8;
		$percentageBefore60	 = 55; /* Changed according to Mantis 469 */
		$percentageAfter60	 = 45; /* Changed according to Mantis 469 */
		$count_old60		 = ($values['old60'] > 0) ? round($values['old60'], 2) : 0; // count is going to be 0 or more 
		$count_new60		 = ($values['new60'] > 0) ? round($values['new60'], 2) : 0; // count is going to be 0 or more 
		$total_reviewcount	 = $values['cnt'];
		$avg_old60			 = ($values['avgold60'] > 0) ? round($values['avgold60'], 2) : 0; // avg is going to be 0 or more 
		$avg_new60			 = ($values['avgnew60'] > 0) ? round($values['avgnew60'], 2) : 0; // avg is going to be 0 or more 
		$sum_new60			 = round($values['sumnew60'], 2);
		$sum_old60			 = round($values['sumold60'], 2);

		if ($total_reviewcount < 7)
		{

			$adjustcnt			 = 7 - $count_old60 - $count_new60;
			$newDefaultReview	 = ($adjustcnt * $defaultRating);
			$count_new60		 += $adjustcnt;
			$sum_new60			 += $newDefaultReview;

			/* $adjustcnt			 = 7 - $count_old60;
			  $oldDefaultReview	 = ($adjustcnt * $defaultRating);
			  $count_old60				 += $adjustcnt;
			  $sum_old60		 += $oldDefaultReview; */
		}

		$actual_avg_new60	 = ($sum_new60 > 0) ? ($sum_new60 / $count_new60) : 0;
		$avg_old60			 = ($sum_old60 > 0) ? ($sum_old60 / $count_old60) : 0;

		$chkBoostRatingValidity = VendorPref::model()->showtmpRatingVldityDate($vendorID);
		if ($chkBoostRatingValidity > $date)
		{
			$avg_new60		 = max(0.8 * (MAX($avg_old60, 5)), $actual_avg_new60);
			$avg_old60		 = max(3.5, $avg_old60); //[DSA 2022-02-02]- if his old60 is below 3.5, lets make it 3.5 while he has TR_boost
			$calcrating_Old	 = ($avg_old60 * $percentageBefore60) / 100;
			$calcrating_New	 = ($avg_new60 * $percentageAfter60) / 100;
			#$rating = ($calcrating_Old + $calcrating_New);
			//$calcrating_Old = 55% of BEFORE_60 
			//$calcrating_New = 45% of max(( 0.8(MAX(BEFORE_60, 5), AFTER_60)

			goto end;
		}

		//adjust his avg_new60 to not drop too drastically below his avg_old60, to prevent a 4* guy from dropping drastically because he got his 1st and only 1* in last 30 days. So if actual_avg_new60 is too low then bump it up to 100% of old_60

		$avg_new60		 = max($avg_old60, $actual_avg_new60);
		$calcrating_Old	 = ($avg_old60 * $percentageBefore60) / 100;
		$calcrating_New	 = ($avg_new60 * $percentageAfter60) / 100;

		end:
		if ($count_old60 == 0 && $count_new60 == 0) // no ratings received so you are at $defaultRating
		{
			$rating = $defaultRating;
		}
		else if ($count_old60 == 0 && $count_new60 > 0) // new vendor so has no no old ratings  or old vendor who has received no ratings yet
		{
			$rating = $avg_new60;
		}
		else
		{
			$rating = ($calcrating_Old + $calcrating_New); // the normal use-case
			// and also the same if ($count_old60 > 0 && $count_new60 == 0) example: vendors who receive no ratings in last 30 days will see their ratings drop slowly by slowly
		}

		SWITCH ($rating)
		{
			CASE ($rating >= 0 && $rating < 3):
				$star	 = 1; // star is an int so just need whole numbers
				break;
			CASE ($rating >= 3.0 && $rating < 5):
				$star	 = 2;
				break;
			CASE ($rating >= 5.0 && $rating < 7):
				$star	 = 3;
				break;
			CASE ($rating >= 7.0 && $rating < 9):
				$star	 = 4;
				break;
			CASE ($rating >= 9.0 && $rating <= 10.0):
				$star	 = 5;
				break;
			default:
				$star	 = 1;
				break;
		}
		return ["star" => $star, "rating" => $rating, "countOld60" => $count_old60, "countNew60" => $count_new60];
	}

	public function getRatingAttributes($type)
	{
		if ($type == 'fend')
		{
			$sql			 = " SELECT * FROM rating_attributes where ratt_active=1 order by RAND() ";
			$all_attributes	 = DBUtil::queryAll($sql, DBUtil::SDB());
			$data_array		 = array();
			foreach ($all_attributes as $val)
			{
				if ($val['ratt_applicable_to'] == '1')
				{
					if ($val['ratt_type'] == '1')
					{
						$data_array['driver']['good'][] = $val;
					}
					if ($val['ratt_type'] == '2')
					{
						$data_array['driver']['bad'][] = $val;
					}
				}
				if ($val['ratt_applicable_to'] == '2')
				{
					if ($val['ratt_type'] == '1')
					{
						$data_array['csr']['good'][] = $val;
					}
					if ($val['ratt_type'] == '2')
					{
						$data_array['csr']['bad'][] = $val;
					}
				}
				if ($val['ratt_applicable_to'] == '3')
				{
					if ($val['ratt_type'] == '1')
					{
						$data_array['car']['good'][] = $val;
					}
					if ($val['ratt_type'] == '2')
					{
						$data_array['car']['bad'][] = $val;
					}
				}
			}
			return $data_array;
		}
		if ($type == 'admin')
		{
			$sql			 = "SELECT * FROM rating_attributes ";
			$all_attributes	 = DBUtil::queryAll($sql, DBUtil::SDB());
			$data_array		 = array();
			foreach ($all_attributes as $val)
			{
				$data_array[$val['ratt_id']] = array('ratt_name' => $val['ratt_name'], 'ratt_name_bad' => $val['ratt_name_bad']);
			}
			return $data_array;
		}
	}

	public function changeRatingFormat($listValues)
	{
		return array_values(array_diff($listValues, array("null", "")));
	}

	/**
	 * 
	 * @param integer $bkg_id
	 * @return integer
	 */
	public static function checkDriverAppUseBooking($bkg_id)
	{
		$sql = "SELECT IF(booking_trail.btr_drv_score>=70,1,0) as driverAppUse
				FROM booking
				INNER JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1 AND booking.bkg_active=1
				INNER JOIN `booking_track` ON booking_track.btk_bkg_id=booking.bkg_id AND booking_track.bkg_ride_start =1 AND booking_track.bkg_ride_complete =1  
				INNER JOIN `booking_trail` ON booking_trail.btr_bkg_id=booking.bkg_id
				WHERE booking.bkg_id='$bkg_id' AND booking.bkg_status IN (6,7)
                GROUP BY booking.bkg_id";
		return DBUtil::command($sql, DBUtil::SDB())->queryScalar();
	}

	public static function routeRating($from_city_id, $to_city_id)
	{
		$limit			 = 120;
		$sql_result		 = ZoneCities::model()->getRelatedcities($from_city_id);
		$allFromCityId	 = $sql_result['all_city'];
		if ($allFromCityId == '')
		{
			return false;
		}
		$sql_result	 = ZoneCities::model()->getRelatedcities($to_city_id);
		$allToCityId = $sql_result['all_city'];
		if ($allToCityId == '')
		{
			return false;
		}
		$data = Ratings::getZoneToStateList($allFromCityId, $to_city_id);
		if ($data && count($data) > $limit)
		{
			goto result;
		}
		$data = Ratings::getZoneList($allFromCityId);
		result:
		return $data;
	}

	public static function routeRatingforSchema($from_city_id, $to_city_id, $limit = 5)
	{
		$sql_result		 = ZoneCities::model()->getRelatedcities($from_city_id);
		$allFromCityId	 = $sql_result['all_city'];
		if ($allFromCityId == '')
		{
			return false;
		}
		$sql_result	 = ZoneCities::model()->getRelatedcities($to_city_id);
		$allToCityId = $sql_result['all_city'];
		if ($allToCityId == '')
		{
			return false;
		}
		$data = Ratings::getZoneToStateListforSchema($allFromCityId, $to_city_id, $limit);
		if ($data && $data->getRowCount() >= $limit)
		{
			goto result;
		}

		$data = Ratings::getZoneListforSchema($allFromCityId, $limit);
		result:
		return $data;
	}

	public static function getRouteSummary($fromCityId = "", $toCityId = "", $limit = 120)
	{
		Logger::profile("Ratings::getRouteSummary START");
		$key	 = "getRouteSummary::" . $fromCityId . '_' . $toCityId;
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			goto result;
		}
		$sql_result		 = ZoneCities::getRelatedcities($fromCityId);
		$allFromCityId	 = $sql_result['all_city'];
		if ($allFromCityId == '')
		{
			return false;
		}
		$sql_result	 = ZoneCities::getRelatedcities($toCityId);
		$allToCityId = $sql_result['all_city'];
		if ($allToCityId == '')
		{
			return false;
		}
		$data = Ratings::getZoneToStateSummary($allFromCityId, $toCityId, $limit);
		if (!empty($data))
		{
			goto skipZoneSummary;
		}
		$data = Ratings::getZoneSummary($allFromCityId, $limit);
		skipZoneSummary:
		Yii::app()->cache->set($key, $data, 24 * 7 * 60 * 60, new CacheDependency("ratings"));

		result:
		Logger::profile("Ratings::getRouteSummary END");
		return $data;
	}

	public static function getCitySummary($cityId, $limit = 120)
	{
		$key	 = "getCitySummary::" . $cityId;
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			goto result;
		}
		$sql_result	 = ZoneCities::getRelatedcities($cityId);
		$allCityId	 = $sql_result['all_city'];
		if ($allCityId == '')
		{
			return false;
		}
		$data = Ratings::getZoneSummary($allCityId, $limit);
		Yii::app()->cache->set($key, $data, 24 * 7 * 60 * 60, new CacheDependency("ratings"));

		result:
		return $data;
	}

	public static function getCityList($cityId)
	{
		$sql_result	 = ZoneCities::model()->getRelatedcities($cityId);
		$allCityId	 = $sql_result['all_city'];
		if ($allCityId == '')
		{
			return false;
		}
		$data = Ratings::getZoneList($allCityId);
		if ($allCityId == '')
		{
			return false;
		}

		return $data;
	}

	public static function getZonetoZoneSummary($allFromCityId, $allToCityId, $limit)
	{
		DBUtil::getINStatement($allFromCityId, $bindString1, $params1);
		DBUtil::getINStatement($allToCityId, $bindString2, $params2);

		$sql	 = "SELECT COUNT(*) as cnt, ROUND(AVG(ratings.rtg_customer_overall),1) as ratings FROM booking
							  INNER JOIN ratings ON ratings.rtg_booking_id = booking.bkg_id AND ratings.rtg_customer_overall>0 
							  WHERE bkg_id IN (SELECT DISTINCT bkg_id FROM booking
							INNER JOIN booking_route ON booking.bkg_id = booking_route.brt_bkg_id AND bkg_status IN (6,7) AND booking_route.brt_active=1 AND bkg_from_city_id IN ($bindString1) 
							AND booking_route.brt_to_city_id IN ($bindString2))
							AND (ratings.rtg_customer_overall>3 OR (ratings.rtg_customer_overall<=3 AND LENGTH(ratings.rtg_customer_review)<50))";
		$data	 = DBUtil::queryRow($sql, DBUtil::SDB(), array_merge($params1, $params2));

		if ($data != false && $data['cnt'] >= $limit)
		{
			return $data;
		}
		return false;
	}

	/** @return CDbDataReader */
	public static function getZonetoZoneList($allFromCityId, $allToCityId)
	{
		$data = false;
		if ($allFromCityId == '' || $allToCityId == '')
		{
			goto result;
		}
		DBUtil::getINStatement($allFromCityId, $bindString1, $params1);
		DBUtil::getINStatement($allToCityId, $bindString2, $params2);
		$sql = "SELECT rtg_id, rtg_active, booking.bkg_booking_id, rtg_customer_review, rtg_customer_overall, CONCAT(trim(booking_user.bkg_user_fname), ' ', trim(booking_user.bkg_user_lname)) as user_name,
					UPPER(CONCAT(trim(SUBSTRING(bkg_user_fname, 1, 1)), '', trim(SUBSTRING(booking_user.bkg_user_lname, 1, 1)))) as initial,
					bkg_booking_type, rtg_customer_date, CONCAT(c1.cty_name, ' - ', GROUP_CONCAT(c2.cty_name SEPARATOR ' - ')) as cities
                    FROM ratings
					INNER JOIN booking ON rtg_booking_id=bkg_id  AND rtg_customer_review_approve_status=1
                    INNER JOIN booking_user ON bui_bkg_id=bkg_id
					INNER JOIN booking_route ON brt_bkg_id=bkg_id AND brt_active=1
					INNER JOIN cities c1 ON c1.cty_id=bkg_from_city_id
					INNER JOIN cities c2 ON c2.cty_id=brt_to_city_id
					WHERE  ratings.rtg_active=1 AND booking.bkg_status IN (6,7) AND rtg_customer_overall>=0
					AND (ratings.rtg_customer_overall>3 OR (ratings.rtg_customer_overall<=3 AND LENGTH(ratings.rtg_customer_review)<50))
					AND bkg_id IN (SELECT DISTINCT bkg_id FROM booking
								INNER JOIN booking_route ON booking.bkg_id = booking_route.brt_bkg_id AND bkg_status IN (6,7) AND booking_route.brt_active=1
									AND bkg_from_city_id IN ($bindString1) 
									AND booking_route.brt_to_city_id IN ($bindString2))
                      GROUP BY rtg_id  
					ORDER BY rtg_customer_date DESC";

		$data = DBUtil::query($sql, DBUtil::SDB(), array_merge($params1, $params2));
		result:
		return $data;
	}

	public static function getZoneToStateSummary($allFromCityId, $toCityId, $limit)
	{
		$state = States::model()->getByCityId($toCityId);
		DBUtil::getINStatement($allFromCityId, $bindString, $params);
		if ((trim($state) == null || trim($state) == ""))
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$sql = "SELECT COUNT(*) as cnt, ROUND(AVG(ratings.rtg_customer_overall),1) as ratings FROM booking
						  INNER JOIN ratings ON ratings.rtg_booking_id = booking.bkg_id AND ratings.rtg_customer_overall>0 
						  WHERE bkg_id IN (SELECT DISTINCT bkg_id FROM booking
								INNER JOIN booking_route ON booking.bkg_id = booking_route.brt_bkg_id AND bkg_status IN (6,7) AND booking_route.brt_active=1 AND bkg_from_city_id IN ($bindString)
								INNER JOIN cities ON brt_to_city_id=cty_id AND cty_state_id=$state
						)
						AND (ratings.rtg_customer_overall>3 OR (ratings.rtg_customer_overall<=3 AND LENGTH(ratings.rtg_customer_review)<50))";

		$data = DBUtil::queryRow($sql, DBUtil::SDB(), $params);

		if ($data != false && $data['cnt'] >= $limit)
		{
			return $data;
		}
		return false;
	}

	public static function getZoneToStateList($allFromCityId, $toCityId)
	{
		$data = false;
		if ($allFromCityId == '' || $toCityId == '')
		{
			goto result;
		}
		$state = States::model()->getByCityId($toCityId);
		if ((trim($state) == null || trim($state) == ""))
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		DBUtil::getINStatement($allFromCityId, $bindString, $params);
		$sql = "SELECT rtg_id, rtg_active, booking.bkg_booking_id, rtg_customer_review, rtg_customer_overall, 
					CONCAT(trim(booking_user.bkg_user_fname), ' ', trim(booking_user.bkg_user_lname)) as user_name,
					UPPER(CONCAT(trim(SUBSTRING(bkg_user_fname, 1, 1)), '', trim(SUBSTRING(booking_user.bkg_user_lname, 1, 1)))) as initial,
					bkg_booking_type, rtg_customer_date, CONCAT(c1.cty_name, ' - ', GROUP_CONCAT(c2.cty_name SEPARATOR ' - ')) as cities
                    FROM ratings
					INNER JOIN booking ON rtg_booking_id=bkg_id  AND rtg_customer_review_approve_status=1
                    INNER JOIN booking_user ON bui_bkg_id=bkg_id
					INNER JOIN booking_route ON brt_bkg_id=bkg_id AND brt_active=1
					INNER JOIN cities c1 ON c1.cty_id=bkg_from_city_id
					INNER JOIN cities c2 ON c2.cty_id=brt_to_city_id
					WHERE  ratings.rtg_active=1 AND booking.bkg_status IN (6,7) AND rtg_customer_overall>=0
					AND (ratings.rtg_customer_overall>3 OR (ratings.rtg_customer_overall<=3 AND LENGTH(ratings.rtg_customer_review)<50))
					AND bkg_id IN (SELECT DISTINCT bkg_id FROM booking
						  INNER JOIN booking_route ON booking.bkg_id = booking_route.brt_bkg_id AND bkg_status IN (6,7) AND booking_route.brt_active=1
								AND bkg_from_city_id IN ($bindString)
						  INNER JOIN cities ON brt_to_city_id=cty_id AND cty_state_id=$state)
                      GROUP BY rtg_id  
					ORDER BY rtg_customer_date DESC";

		$data = DBUtil::queryAll($sql, DBUtil::SDB(), $params);
		result:
		return $data;
	}

	public static function getZoneToStateListforSchema($allFromCityId, $toCityId, $limit = 5)
	{
		$data = false;
		if ($allFromCityId == '' || $toCityId == '')
		{
			goto result;
		}
		$state = States::model()->getByCityId($toCityId);
		if ((trim($state) == null || trim($state) == ""))
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		DBUtil::getINStatement($allFromCityId, $bindString, $params);
		$sql = "SELECT rtg_id, rtg_active, booking.bkg_booking_id, rtg_customer_review, rtg_customer_overall, 
					CONCAT(trim(booking_user.bkg_user_fname), ' ', trim(booking_user.bkg_user_lname)) as user_name,
					UPPER(CONCAT(trim(SUBSTRING(bkg_user_fname, 1, 1)), '', trim(SUBSTRING(booking_user.bkg_user_lname, 1, 1)))) as initial,
					bkg_booking_type, rtg_customer_date, CONCAT(c1.cty_name, ' - ', GROUP_CONCAT(c2.cty_name SEPARATOR ' - ')) as cities
                    FROM ratings
					INNER JOIN booking ON rtg_booking_id=bkg_id  AND rtg_customer_review_approve_status=1
                    INNER JOIN booking_user ON bui_bkg_id=bkg_id
					INNER JOIN booking_route ON brt_bkg_id=bkg_id AND brt_active=1
					INNER JOIN cities c1 ON c1.cty_id=bkg_from_city_id
					INNER JOIN cities c2 ON c2.cty_id=brt_to_city_id
					WHERE  ratings.rtg_active=1 AND booking.bkg_status IN (6,7) 
					AND (ratings.rtg_customer_overall>3   AND LENGTH(ratings.rtg_customer_review)>5)
					AND bkg_id IN (SELECT DISTINCT bkg_id FROM booking
						  INNER JOIN booking_route ON booking.bkg_id = booking_route.brt_bkg_id AND bkg_status IN (6,7) AND booking_route.brt_active=1
								AND bkg_from_city_id IN ($bindString)
						  INNER JOIN cities ON brt_to_city_id=cty_id AND cty_state_id=$state)
                      GROUP BY rtg_id  
					ORDER BY rtg_customer_date DESC LIMIT 0,$limit ";

		$data = DBUtil::query($sql, DBUtil::SDB(), $params);
		result:
		return $data;
	}

	public static function getZoneSummary($allFromCityId, $limit)
	{
		DBUtil::getINStatement($allFromCityId, $bindString, $params);
		$sql	 = "SELECT COUNT(*) as cnt, ROUND(AVG(ratings.rtg_customer_overall),1) as ratings FROM booking
				INNER JOIN ratings ON ratings.rtg_booking_id = booking.bkg_id AND ratings.rtg_customer_overall>0 
				WHERE bkg_id IN (SELECT DISTINCT bkg_id FROM booking
				INNER JOIN booking_route ON booking.bkg_id = booking_route.brt_bkg_id AND bkg_status IN (6,7) AND booking_route.brt_active=1 AND bkg_from_city_id IN ($bindString))
				AND (ratings.rtg_customer_overall>3 OR (ratings.rtg_customer_overall<=3 AND LENGTH(ratings.rtg_customer_review)<50))";
		$data	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		if ($data != false && $data['cnt'] >= $limit)
		{
			return $data;
		}
		return false;
	}

	public static function getZoneList($allFromCityId)
	{

		DBUtil::getINStatement($allFromCityId, $bindString, $params);
		$data = false;
		if ($allFromCityId == '')
		{
			goto result;
		}
		$sql = "SELECT rtg_id, rtg_active, booking.bkg_booking_id, rtg_customer_review, rtg_customer_overall, CONCAT(trim(booking_user.bkg_user_fname), ' ', trim(booking_user.bkg_user_lname)) as user_name,
					UPPER(CONCAT(trim(SUBSTRING(bkg_user_fname, 1, 1)), '', trim(SUBSTRING(booking_user.bkg_user_lname, 1, 1)))) as initial,
					bkg_booking_type, rtg_customer_date, CONCAT(c1.cty_name, ' - ', GROUP_CONCAT(c2.cty_name SEPARATOR ' - ')) as cities
                                        FROM ratings
					INNER JOIN booking ON rtg_booking_id=bkg_id  AND rtg_customer_review_approve_status=1
                    INNER JOIN booking_user ON bui_bkg_id=bkg_id
					INNER JOIN booking_route ON brt_bkg_id=bkg_id AND brt_active=1
					INNER JOIN cities c1 ON c1.cty_id=bkg_from_city_id
					INNER JOIN cities c2 ON c2.cty_id=bkg_to_city_id
					WHERE  ratings.rtg_active=1 AND booking.bkg_status IN (6,7) AND rtg_customer_overall>=0
					AND (ratings.rtg_customer_overall>3 OR (ratings.rtg_customer_overall<=3 AND LENGTH(ratings.rtg_customer_review)<50))
					AND bkg_from_city_id IN ($bindString)
                    GROUP BY rtg_id  
					ORDER BY rtg_customer_date DESC";

		$data = DBUtil::queryAll($sql, DBUtil::SDB(), $params);
		result:
		return $data;
	}

	public static function getZoneListforSchema($allFromCityId, $limit = 2)
	{

		DBUtil::getINStatement($allFromCityId, $bindString, $params);
		$data = false;
		if ($allFromCityId == '')
		{
			goto result;
		}
		$sql	 = "SELECT rtg_id, rtg_active, booking.bkg_booking_id, rtg_customer_review, rtg_customer_overall, CONCAT(trim(booking_user.bkg_user_fname), ' ', trim(booking_user.bkg_user_lname)) as user_name,
					UPPER(CONCAT(trim(SUBSTRING(bkg_user_fname, 1, 1)), '', trim(SUBSTRING(booking_user.bkg_user_lname, 1, 1)))) as initial,
					bkg_booking_type, rtg_customer_date, CONCAT(c1.cty_name, ' - ', GROUP_CONCAT(c2.cty_name SEPARATOR ' - ')) as cities
                                        FROM ratings
					INNER JOIN booking ON rtg_booking_id=bkg_id  AND rtg_customer_review_approve_status=1
                    INNER JOIN booking_user ON bui_bkg_id=bkg_id
					INNER JOIN booking_route ON brt_bkg_id=bkg_id AND brt_active=1
					INNER JOIN cities c1 ON c1.cty_id=bkg_from_city_id
					INNER JOIN cities c2 ON c2.cty_id=bkg_to_city_id
					WHERE  ratings.rtg_active=1 AND booking.bkg_status IN (6,7)  
					AND (ratings.rtg_customer_overall>3  AND LENGTH(ratings.rtg_customer_review)> 5)
					AND bkg_from_city_id IN ($bindString)
                    GROUP BY rtg_id  
					ORDER BY rtg_customer_date DESC LIMIT 0,$limit";
		echo $sql;
		exit;
		$data	 = DBUtil::queryAll($sql, DBUtil::SDB(), $params);
		result:
		return $data;
	}

	public static function getStateSummary($fromCityId)
	{
		$state = States::model()->getByCityId($fromCityId);
		if ((trim($state) == null || trim($state) == ""))
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$sql	 = "SELECT COUNT(*) as cnt, ROUND(AVG(ratings.rtg_customer_overall),1) as ratings FROM booking
						  INNER JOIN ratings ON ratings.rtg_booking_id = booking.bkg_id AND ratings.rtg_customer_overall>0 
						  WHERE bkg_id IN (SELECT DISTINCT bkg_id FROM booking
						INNER JOIN booking_route ON booking.bkg_id = booking_route.brt_bkg_id AND bkg_status IN (6,7) AND booking_route.brt_active=1 INNER JOIN cities ON brt_to_city_id=cty_id AND cty_state_id=$state
						)
						AND (ratings.rtg_customer_overall>3 OR (ratings.rtg_customer_overall<=3 AND LENGTH(ratings.rtg_customer_review)<50))";
		$data	 = DBUtil::queryRow($sql, DBUtil::SDB());
		if ($data != false && $data['cnt'] != "")
		{
			return $data;
		}
		return false;
	}

	public static function getStateList($fromCityId, $limit)
	{
		$state = States::model()->getByCityId($fromCityId);
		if ((trim($state) == null || trim($state) == ""))
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$sql = "SELECT rtg_id, rtg_active, booking.bkg_booking_id, rtg_customer_review, rtg_customer_overall, CONCAT(trim(booking_user.bkg_user_fname), ' ', trim(booking_user.bkg_user_lname)) as user_name,
					UPPER(CONCAT(trim(SUBSTRING(bkg_user_fname, 1, 1)), '', trim(SUBSTRING(booking_user.bkg_user_lname, 1, 1)))) as initial,
					bkg_booking_type, rtg_customer_date, CONCAT(c1.cty_name, ' - ', GROUP_CONCAT(c2.cty_name SEPARATOR ' - ')) as cities
                    FROM ratings
					INNER JOIN booking ON rtg_booking_id=bkg_id  AND rtg_customer_review_approve_status=1
                    INNER JOIN booking_user ON bui_bkg_id=bkg_id
					INNER JOIN booking_route ON brt_bkg_id=bkg_id AND brt_active=1
					INNER JOIN cities c1 ON c1.cty_id=bkg_from_city_id
					INNER JOIN cities c2 ON c2.cty_id=brt_to_city_id
					WHERE  ratings.rtg_active=1 AND booking.bkg_status IN (6,7) AND rtg_customer_overall>=0
					AND (ratings.rtg_customer_overall>3 OR (ratings.rtg_customer_overall<=3 AND LENGTH(ratings.rtg_customer_review)<50))
					AND bkg_id IN (
					    SELECT DISTINCT bkg_id FROM booking
						INNER JOIN booking_route ON booking.bkg_id = booking_route.brt_bkg_id AND bkg_status IN (6,7) AND booking_route.brt_active=1
						INNER JOIN cities ON brt_to_city_id=cty_id AND cty_state_id=$state)   GROUP BY rtg_id  	ORDER BY rtg_customer_date DESC";

		$data = DBUtil::queryAll($sql, DBUtil::SDB());
		if ($data != false && $data['cnt'] >= $limit)
		{
			return $data;
		}
		return false;
	}

	public function addPenaltyByCustomerReview()
	{
		try
		{
			$bkgId				 = $this->rtg_booking_id;
			$vendorId			 = $this->rtgBooking->bkgBcb->bcb_vendor_id;
			$cabId				 = $this->rtgBooking->bkgBcb->bcb_cab_id;
			$driverBadReviewId	 = $this->rtg_driver_bad_attr;
			$carBadReviewId		 = $this->rtg_car_bad_attr;
			$reviewIds			 = [];
			if ($driverBadReviewId != '' || $driverBadReviewId != NULL)
			{
				$reviewIds[] = $driverBadReviewId;
			}
			if ($carBadReviewId != '' || $carBadReviewId != NULL)
			{
				$reviewIds[] = $carBadReviewId;
			}
			If (in_array("40", explode(',', $carBadReviewId)))
			{
				Vehicles::model()->updateCng($cabId, 1);
			}
			$reviewIdStr = implode(',', $reviewIds);
			if ($reviewIdStr != '')
			{
				$recordset = $this->getAmountByReviewId($reviewIdStr);
				foreach ($recordset as $recordVal)
				{
					if ($recordVal['penaltyAmt'] > 0)
					{
						$penaltyArr[]	 = round($recordVal['penaltyAmt']);
						$rat_name_bad[]	 = $recordVal['badReviewName'] . ' <span>&#x20b9</span>' . round($recordVal['penaltyAmt']);
						$rattId[]		 = $recordVal['ratt_id'];
					}
				}
			}
			$ratt_review			 = implode(', ', $rat_name_bad);
			$rattPenaltyids			 = implode(', ', $rattId);
			$this->rtg_penalty_attr	 = $rattPenaltyids;
			if ($this->validate())
			{
				$this->save();
			}
			$penalty			 = max($penaltyArr);
			$bmodel				 = Booking::model()->findByPk($this->rtg_booking_id);
			$bkg_booking_id		 = $bmodel->bkg_booking_id;
			$remarks			 = "Auto-Penalized of booking #$bkg_booking_id - " . $ratt_review . ", ( Actual auto-penalty <span>&#x20b9</span>" . $penalty . ")";
			$getAppliedPanelty	 = AccountTransactions::model()->getAppliedPenalty($bkgId, $vendorId);
			
			if ($penalty > 0 && $penalty > $getAppliedPanelty)
			{
				if ($getAppliedPanelty > 0)
				{
					//$removeAppliedPenalty = AccountTransactions::model()->removeAppliedPenalty($bkgId);
					$desc = "Penalty Amount : Rs. $getAppliedPanelty  reverted, due to customer review penalty Rs. $penalty is greater than applied penalty Rs. $getAppliedPanelty";
					AccountTransactions::model()->addVendorPenalty($bkgId, $vendorId, (-1*$getAppliedPanelty), $desc);
				}
				$penaltyType = PenaltyRules::PTYPE_CUSTOMER_REVIEW;
				AccountTransactions::model()->addVendorPenalty($bkgId, $vendorId, $penalty, $remarks, '', $penaltyType);
			}
		}
		catch (Exception $ex)
		{
			$ex->getMessage();
		}
	}

	public function getAmountByReviewId($reviewIds)
	{
		$sql		 = "SELECT ratt_id,ratt_penalty_amount penaltyAmt,ratt_bonus_amount bonusAmt,ratt_name_bad badReviewName,ratt_name goodReviewName FROM rating_attributes where ratt_id IN($reviewIds) AND ratt_active=1";
		$recordset	 = DBUtil::queryAll($sql, DBUtil::SDB());
		return $recordset;
	}

	public function addDriverBonusByCustomerReview()
	{
		try
		{
			$bkgId				 = $this->rtg_booking_id;
			$driverId			 = $this->rtgBooking->bkgBcb->bcb_driver_id;
			$driverGoodReviewId	 = $this->rtg_driver_good_attr;

			if ($driverGoodReviewId != '' || $driverGoodReviewId != NULL)
			{
				$recordset = $this->getAmountByReviewId($driverGoodReviewId);
				foreach ($recordset as $recordVal)
				{
					if ($recordVal['bonusAmt'] > 0)
					{
						$bonusArr[]		 = round($recordVal['bonusAmt']);
						$rat_name_good[] = $recordVal['goodReviewName'] . ' <span>&#x20b9</span>' . round($recordVal['bonusAmt']);
						$rattId[]		 = $recordVal['ratt_id'];
					}
				}
				$ratt_review			 = implode(',', $rat_name_good);
				$rattBonusids			 = implode(',', $rattId);
				$this->rtg_bonus_attr	 = $rattBonusids;
				if ($this->validate())
				{
					$this->save();
				}
				$bonus			 = max($bonusArr);
				$bmodel			 = Booking::model()->findByPk($this->rtg_booking_id);
				$bkg_booking_id	 = $bmodel->bkg_booking_id;
				$remarks		 = "Auto-bonus of booking #$bkg_booking_id - " . $ratt_review . ", ( Actual Auto-bonus <span>&#x20b9</span>" . $bonus . ")";
				if ($bonus > 0)
				{
					AccountTransactions::model()->AddDriverBonus($bonus, $bkgId, $driverId, UserInfo::getInstance(), 1, $remarks);
				}
			}
		}
		catch (Exception $ex)
		{
			$ex->getMessage();
		}
	}

	public function getRatingsByBookingType($fromDate, $toDate, $type = 'Command')
	{
		$sql = "SELECT 
        CONCAT(MONTHNAME(bkg_pickup_date), ' ', year(bkg_pickup_date)) AS month,
              SUM(IF((bkg_booking_type = 1), 1, 0))				    AS OW_count,
              SUM(IF((bkg_booking_type IN(2,3)), 1, 0))			  AS RT_count,
              SUM(IF((bkg_booking_type = 4), 1, 0))			      AS AT_count,
              SUM(IF((bkg_booking_type IN(9,10,11)), 1, 0))		AS DR_count,  
			  SUM(IF((bkg_booking_type IN(15)), 1, 0))		AS LT_count,
              ROUND(((SUM(IF(bkg_booking_type IN(1) ,ratings.rtg_customer_recommend,0))/SUM(IF((bkg_booking_type IN(1)), 1, 0)))/2),1) as Average_rating_OW,
              ROUND(((SUM(IF(bkg_booking_type IN(2,3) ,ratings.rtg_customer_recommend,0))/SUM(IF((bkg_booking_type IN(2,3)), 1, 0)))/2),1) as Average_rating_RT,
              ROUND(((SUM(IF(bkg_booking_type IN(4) ,ratings.rtg_customer_recommend,0))/SUM(IF((bkg_booking_type IN(4)), 1, 0)))/2),1) as Average_rating_AT,
              ROUND(((SUM(IF(bkg_booking_type IN(9,10,11) ,ratings.rtg_customer_recommend,0))/SUM(IF((bkg_booking_type IN(9,10,11)), 1, 0)))/2),1) as Average_rating_DR,
			  ROUND(((SUM(IF(bkg_booking_type IN(15) ,ratings.rtg_customer_recommend,0))/SUM(IF((bkg_booking_type IN(15)), 1, 0)))/2),1) as Average_rating_LT
			   FROM booking
			   INNER JOIN ratings ON booking.bkg_id = ratings.rtg_booking_id AND ratings.rtg_customer_recommend IS NOT NULL
			   WHERE booking.bkg_status IN(6,7) AND bkg_pickup_date BETWEEN '$fromDate' AND '$toDate'
         GROUP BY month(bkg_pickup_date), year(bkg_pickup_date)";

		if ($type == 'Command')
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) a")->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['Average_rating_OW', 'Average_rating_RT', 'Average_rating_MW', 'Average_rating_AT', 'Average_rating_DR'],
					'defaultOrder'	 => 'year(bkg_pickup_date) DESC,month(bkg_pickup_date) DESC'], 'pagination'	 => ['pageSize' => 20],
			]);
			return $dataprovider;
		}
		else
		{
			$recordSet = DBUtil::query($sql, DBUtil::SDB());
			return $recordSet;
		}
	}

	public static function getNewInstance()
	{
		$model = new Ratings('new');
		return $model;
	}

	public static function findByBkgId($bkgId)
	{
		$model = Ratings::model()->find('rtg_booking_id =:bkgId ', ['bkgId' => $bkgId]);
		return $model;
	}

	public static function getOverallTop($limit = 5)
	{
		$key	 = "getOverallTop";
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			goto result;
		}
		$sql	 = "SELECT
					rtg_customer_review, 
					rtg_customer_overall, 
					CONCAT(trim(booking_user.bkg_user_fname), ' ', trim(booking_user.bkg_user_lname)) AS user_name,
					rtg_customer_date 
				 	FROM ratings						 
					INNER JOIN booking_user ON bui_bkg_id=ratings.rtg_booking_id
					 WHERE ratings.rtg_active = 1 AND ratings.rtg_customer_review_approve_status=1
					 AND ratings.rtg_customer_overall >= 3 and LENGTH(ratings.rtg_customer_review)>100
					ORDER BY rtg_customer_overall DESC, rtg_customer_date DESC
					LIMIT  0,$limit";
		$data	 = DBUtil::queryAll($sql, DBUtil::SDB());
		Yii::app()->cache->set($key, $data, 24 * 30 * 60 * 60, new CacheDependency("ratings"));

		result:
		return $data;
	}

	public static function getOrganisationSummary($minRating = 3)
	{
		$param	 = ['minRating' => $minRating];
		$key	 = "Rating::getOrganisationSummary_{$minRating}";
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			goto result;
		}
		$sql	 = "SELECT COUNT(1) AS count,
							ROUND(AVG(ratings.rtg_customer_overall), 1) AS rating
					 FROM ratings						   
					 WHERE ratings.rtg_active = 1 
						AND ratings.rtg_customer_review_approve_status=1
					 AND ratings.rtg_customer_overall >= :minRating 
					";
		$data	 = DBUtil::queryRow($sql, DBUtil::SDB(), $param);

		Yii::app()->cache->set($key, $data, 24 * 7 * 60 * 60, new CacheDependency("ratings"));
		result:
		return $data;
	}

	public static function getCityOverAllTop($fcity, $limit = 5)
	{
		$key	 = "getCityOverAllTop_{$fcity}";
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			goto result;
		}
		$param	 = ['fcity' => $fcity];
		$sql	 = "SELECT
					rtg_customer_review, 
					rtg_customer_overall, 
					CONCAT(trim(booking_user.bkg_user_fname), ' ', trim(booking_user.bkg_user_lname)) AS user_name,
					rtg_customer_date 
				 	FROM ratings						 
					INNER JOIN booking_user ON bui_bkg_id=ratings.rtg_booking_id
					INNER JOIN booking bkg ON bkg_id=ratings.rtg_booking_id
					 WHERE ratings.rtg_active = 1 AND ratings.rtg_customer_review_approve_status=1
					 AND ratings.rtg_customer_overall >= 3 and LENGTH(ratings.rtg_customer_review)>100
					 AND  bkg.bkg_from_city_id=:fcity					 
					ORDER BY rtg_customer_overall DESC, rtg_customer_date DESC
					LIMIT  0,$limit";
		$data	 = DBUtil::queryAll($sql, DBUtil::SDB(), $param);
		if (sizeof($data) == 0)
		{
			$data = Ratings::getOverAllTop($limit);
		}
		Yii::app()->cache->set($key, $data, 24 * 30 * 60 * 60, new CacheDependency("ratings"));

		result:
		return $data;
	}

	public static function getRouteOverAllTop($fcity, $tcity, $limit = 5)
	{
		$key	 = "getRouteOverAllTop_{$fcity}_{$tcity}";
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			goto result;
		}
		$param	 = ['fcity' => $fcity, 'tcity' => $tcity];
		$sql	 = "SELECT
					rtg_customer_review, 
					rtg_customer_overall, 
					CONCAT(trim(booking_user.bkg_user_fname), ' ', trim(booking_user.bkg_user_lname)) AS user_name,
					rtg_customer_date 
				 	FROM ratings						 
					INNER JOIN booking_user ON bui_bkg_id=ratings.rtg_booking_id
					INNER JOIN booking bkg ON bkg_id=ratings.rtg_booking_id
					 WHERE ratings.rtg_active = 1 AND ratings.rtg_customer_review_approve_status=1
					 AND ratings.rtg_customer_overall >= 3 and LENGTH(ratings.rtg_customer_review)>100
					 AND  bkg.bkg_from_city_id=:fcity
					 AND  bkg.bkg_to_city_id=:tcity
					ORDER BY rtg_customer_overall DESC, rtg_customer_date DESC
					LIMIT  0,$limit";
		$data	 = DBUtil::queryAll($sql, DBUtil::SDB(), $param);
		if (sizeof($data) == 0)
		{
			$data = Ratings::getOverAllTop($limit);
		}
		Yii::app()->cache->set($key, $data, 24 * 30 * 60 * 60, new CacheDependency("ratings"));

		result:
		return $data;
	}

	/**
	 * This function is used to return driver detail with rating of a particular booking
	 * @param integer $rtgId primary key of ratings table
	 * @return array of single row
	 * @author Ramala
	 */
	public static function getDriverRatingByRtgId($rtgId)
	{
		$sql = "SELECT rtg_customer_overall, bkg_booking_id,bkg_id, eml_email_address drv_email,
				IFNULL(ctt_name, ctt_first_name) drv_name,bcb_driver_id drv_id
				FROM   booking
					   INNER JOIN ratings ON bkg_id = rtg_booking_id AND rtg_id = :rtgId
					   INNER JOIN booking_cab ON bcb_id = bkg_bcb_id AND bcb_active = 1
					   INNER JOIN contact_profile ON cr_is_driver = bcb_driver_id AND cr_status = 1
					   INNER JOIN contact ON ctt_id = cr_contact_id AND ctt_active = 1
					   INNER JOIN contact_email ON contact.ctt_id = eml_contact_id
				WHERE  1 AND bkg_active = 1  AND eml_active = 1 AND eml_is_primary = 1";
		$row = DBUtil::queryRow($sql, DBUtil::SDB(), ['rtgId' => $rtgId]);
		if (!$row)
		{
			/**
			 * @todo remove this if block after complete removal of drv_contact_id from driver table
			 */
			$row = Ratings::model()->getDriverVendorDetailsById($rtgId);
		}
		return $row;
	}

	/**
	 * This function is used to return all booking id where rating is not being provide after 1 day  completion of booking  having booking type value+ select+ and select
	 * @return query Objects
	 */
	public static function getAutoFurRating($fromdate, $todate)
	{
		$param	 = ['fromDate' => $fromdate, 'toDate' => $todate];
		$sql	 = "SELECT 
					booking.bkg_id,
					booking_user.bkg_user_id,
					booking_user.bkg_country_code,
					booking_user.bkg_contact_no
					FROM booking
					INNER JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id
					INNER JOIN booking_user ON booking_user.bui_bkg_id = booking.bkg_id and booking.bkg_agent_id IS NULL
					INNER JOIN svc_class_vhc_cat scv ON scv.scv_id = bkg_vehicle_type_id
				        INNER JOIN service_class scc ON scc.scc_id = scv.scv_scc_id  AND scc_id IN (2,4,5)
					LEFT JOIN ratings ON ratings.rtg_booking_id = booking.bkg_id 	AND rtg_customer_overall IS NULL
					WHERE 1
					AND booking.bkg_status IN (6, 7)	
					AND btr_mark_complete_date BETWEEN  :fromDate AND :toDate";
		return DBUtil::query($sql, DBUtil::SDB(), $param);
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @return type
	 */
	public static function isLinkExpired($bkgId)
	{
		$sql = "SELECT COUNT(1) as cnt FROM `booking` WHERE booking.bkg_id=:bookingId AND DATE_ADD(booking.bkg_pickup_date ,INTERVAL 90 DAY) < NOW() AND booking.bkg_status IN (6,7)";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), ['bookingId' => $bkgId]);
	}

	public static function linkExpiredMsg($bkgId)
	{
		$sql = "SELECT  DATEDIFF(ratings.rtg_create_date,booking.bkg_pickup_date) as cnt FROM `booking` 
				INNER JOIN ratings ON ratings.rtg_booking_id= booking.bkg_id
				WHERE booking.bkg_id=:bookingId
				AND booking.bkg_status IN (6,7)";

		return DBUtil::queryRow($sql, DBUtil::MDB(), ['bookingId' => $bkgId]);
	}

	/**
	 * function to count vendor booking rating within one month
	 * @param type $vndId
	 * @return type booking count Int
	 */
	public static function getVendorRatingCount($vndId, $days = null)
	{
		$params = ['vndId' => $vndId];
		if ($days != null)
		{
			$condition = " AND bkg.bkg_pickup_date BETWEEN (NOW() - INTERVAL $days DAY) AND NOW()";
		}

		$sql = "SELECT  count(rtg_id) as ratingCount FROM `ratings` rtg
				JOIN booking bkg ON bkg.bkg_id = rtg.rtg_booking_id AND bkg.bkg_status IN (6, 7) AND bkg.bkg_pickup_date BETWEEN (NOW() - INTERVAL 30 DAY) AND NOW()
				JOIN booking_cab ON bcb_id = bkg.bkg_bcb_id AND bcb_active = 1 
				INNER JOIN vendors ON vnd_id=bcb_vendor_id  AND bcb_vendor_id = :vndId
				WHERE 1 and rtg_customer_driver > 0 OR rtg_customer_overall > 0  $condition
				ORDER BY `rtg`.`rtg_customer_date` DESC";

		$cnt = DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
		return $cnt;
	}

	public static function setData($postData)
	{
		$data				 = $postData->getParam('Ratings');
		$ratingDriverGood	 = $postData->getParam('Ratings_driver_good');
		$ratingDriverBad	 = $postData->getParam('Ratings_driver_bad');
		$ratingCsrGood		 = $postData->getParam('Ratings_csr_good');
		$ratingCsrBad		 = $postData->getParam('Ratings_csr_bad');
		$ratingCarGood		 = $postData->getParam('Ratings_car_good');
		$ratingCarBad		 = $postData->getParam('Ratings_car_bad');

		if (isset($ratingDriverGood))
		{
			$rating_view_db = Ratings::model()->changeRatingFormat($ratingDriverGood);
			if (!empty($rating_view_db))
			{
				$data['rtg_driver_good_attr'] = implode(",", $rating_view_db);
			}
		}
		if (isset($ratingDriverBad))
		{
			$rating_view_db = Ratings::model()->changeRatingFormat($ratingDriverBad);
			if (!empty($rating_view_db))
			{
				$data['rtg_driver_bad_attr'] = implode(",", $rating_view_db);
			}
		}

		if (isset($ratingCsrGood))
		{
			$rating_view_db = Ratings::model()->changeRatingFormat($ratingCsrGood);
			if (!empty($rating_view_db))
			{
				$data['rtg_csr_good_attr'] = implode(",", $rating_view_db);
			}
		}
		if (isset($ratingCsrBad))
		{
			$rating_view_db = Ratings::model()->changeRatingFormat($ratingCsrBad);
			if (!empty($rating_view_db))
			{
				$data['rtg_csr_bad_attr'] = implode(",", $rating_view_db);
			}
		}

		if (isset($ratingCarGood))
		{
			$rating_view_db = Ratings::model()->changeRatingFormat($ratingCarGood);
			if (!empty($rating_view_db))
			{
				$data['rtg_car_good_attr'] = implode(",", $rating_view_db);
			}
		}
		if (isset($ratingCarBad))
		{
			$rating_view_db = Ratings::model()->changeRatingFormat($ratingCarBad);
			if (!empty($rating_view_db))
			{
				$data['rtg_car_bad_attr'] = implode(",", $rating_view_db);
			}
		}
		return $data;
	}

	/**
	 * 
	 * @param type $reviewDesc
	 * @return type
	 */
	public static function parseReviewDescJSON($reviewDesc)
	{

		if (is_string($reviewDesc))
		{
			$reviewArr = json_decode($reviewDesc, true);
		}
		if (is_array($reviewDesc))
		{
			$reviewArr = $reviewDesc;
		}
		$arr = [];
		foreach ($reviewArr as $k => $row)
		{
			self::getParseDesc($arr, $k, $row);
		}
		//var_dump($arr);
		$str = implode("\n", $arr);

		return $str;
	}

	/**
	 * 
	 * @param type $arr
	 * @param type $k
	 * @param type $row
	 * @return type
	 */
	public static function getParseDesc(&$arr, $k, $row)
	{

		if (is_array($row))
		{
			foreach ($row as $k1 => $row1)
			{
				if ($k1 == 'id')
				{
					unset($row['id']);
					if ($row['followup'])
					{
						self::getParseDesc($arr, $k, $row['followup'][0]);
					}
					$value	 = (is_array($row['value'])) ? implode(', ', $row['value']) : $row['value'];
					$arr[]	 = "Title : " . $row['title'] . ", Value : " . $value;
				}
				return self::getParseDesc($arr, $k1, $row1);
			}
		}

		if (!is_numeric($row))
		{
			$arr[] = $row;
		}
	}

}

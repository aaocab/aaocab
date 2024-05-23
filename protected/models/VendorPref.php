<?php

/**
 * This is the model class for table "vendor_pref".
 *
 * The followings are the available columns in table 'vendor_pref':
 * @property integer $vnp_id
 * @property integer $vnp_vnd_id
 * @property string $vnp_preferred_time_slots
 * @property integer $vnp_booking_type
 * @property integer $vnp_is_attached
 * @property integer $vnp_is_freeze
 * @property integer $vnp_is_orientation
 * @property integer $vnp_orientation_type
 * @property integer $vnp_cod_freeze
 * @property integer $vnp_credit_limit_freeze
 * @property integer $vnp_low_rating_freeze
 * @property integer $vnp_doc_pending_freeze
 * @property integer $vnp_manual_freeze
 * @property integer $vnp_is_dormant
 * @property integer $vnp_mod_day
 * @property string $vnp_invoice_date
 * @property string $vnp_settle_date
 * @property string $vnp_home_zone
 * @property string $vnp_accepted_zone
 * @property string $vnp_excluded_cities
 * @property string $vnp_notes
 * @property integer $vnp_cars_own
 * @property integer $vnp_sedan_count
 * @property integer $vnp_compact_count
 * @property integer $vnp_suv_count
 * @property integer $vnp_multi_link
 * @property integer $vnp_deny_tire_upgrade
 * @property integer $vnp_oneway
 * @property integer $vnp_round_trip
 * @property integer $vnp_multi_trip
 * @property integer $vnp_airport
 * @property integer $vnp_package
 * @property integer $vnp_flexxi
 * @property integer $vnp_daily_rental
 * @property integer $vnp_lastmin_booking
 * @property integer $vnp_tempo_traveller
 * @property string $vnp_vnd_requested_services
 * @property string $vnp_admin_approved_services
 * @property integer $vnp_min_sd_req_amt
 * The followings are the available model relations:
 * @property Vendors $vnpVnd
 */
class VendorPref extends CActiveRecord
{

	public $zonRegion, $zon_name, $value, $valueplus, $plus, $select;

	const SERVICE_TYPE_ONEWAY		 = 1;
	const SERVICE_TYPE_ROUND_MULTI = 2;
	const SERVICE_TYPE_AIRPORT	 = 3;
	const SERVICE_TYPE_PACKAGE	 = 4;
	const SERVICE_TYPE_DAILYRENTAL = 5;
	const serviceTypeList			 = [
		1	 => 'One Way',
		2	 => 'Round Trip/Multi City',
		3	 => 'Airport Transfer',
		4	 => 'Package',
		5	 => 'Day Rental'
	];
	const serviceFieldList		 = [
		1	 => 'vnp_oneway',
		2	 => 'vnp_round_trip',
		3	 => 'vnp_airport',
		4	 => 'vnp_package',
		5	 => 'vnp_daily_rental'
	];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vendor_pref';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vnp_vnd_id', 'required'),
			array('vnp_vnd_id, vnp_booking_type, vnp_is_attached, vnp_is_freeze, vnp_cod_freeze, vnp_mod_day, vnp_cars_own, vnp_sedan_count, vnp_compact_count, vnp_suv_count, vnp_oneway, vnp_round_trip, vnp_multi_trip,vnp_airport,vnp_package,vnp_flexxi,vnp_daily_rental,vnp_lastmin_booking,vnp_tempo_traveller,vnp_min_sd_req_amt', 'numerical', 'integerOnly' => true),
			array('vnp_preferred_time_slots, vnp_home_zone', 'length', 'max' => 200),
			array('vnp_accepted_zone', 'length', 'max' => 5000),
			array('vnp_excluded_cities', 'length', 'max' => 1024),
			array('vnp_notes', 'length', 'max' => 5000),
			array('vnp_invoice_date, vnp_settle_date,vnp_vnd_requested_services,vnp_admin_approved_services,vnp_min_sd_req_amt', 'safe'),
			array('vnp_home_zone', 'required', 'on' => 'updateHomeZone'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vnp_id, vnp_vnd_id, vnp_preferred_time_slots, vnp_booking_type, vnp_is_attached, vnp_is_freeze, vnp_is_orientation, vnp_orientation_type, vnp_cod_freeze, vnp_credit_limit_freeze, vnp_low_rating_freeze, vnp_doc_pending_freeze, vnp_manual_freeze, vnp_is_dormant, vnp_mod_day, vnp_invoice_date, vnp_settle_date, vnp_home_zone, vnp_accepted_zone, vnp_excluded_cities, vnp_notes, vnp_cars_own, vnp_sedan_count, vnp_compact_count, vnp_suv_count,vnp_multi_link,vnp_deny_tire_upgrade,vnp_oneway,vnp_round_trip,vnp_multi_trip,vnp_airport,vnp_package,vnp_flexxi,vnp_daily_rental,vnp_lastmin_booking,vnp_tempo_traveller,vnp_min_sd_req_amt', 'safe', 'on' => 'search'),
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
			'vnpVnd' => array(self::BELONGS_TO, 'Vendors', 'vnp_vnd_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'vnp_id'					 => 'Vnp',
			'vnp_vnd_id'				 => 'Vnp Vnd',
			'vnp_preferred_time_slots'	 => 'Preferred Time Slots',
			'vnp_booking_type'			 => 'Booking Type',
			'vnp_is_attached'			 => 'Is Attached',
			'vnp_is_freeze'				 => 'Is Freeze',
			'vnp_is_orientation'		 => 'Is Orientation',
			'vnp_cod_freeze'			 => 'Cod Freeze',
			'vnp_mod_day'				 => 'Mod Day',
			'vnp_invoice_date'			 => 'Invoice Date',
			'vnp_settle_date'			 => 'Settle Date',
			'vnp_home_zone'				 => 'Home Zone',
			'vnp_accepted_zone'			 => 'Accepted Zone',
			'vnp_excluded_cities'		 => 'Excluded Cities',
			'vnp_notes'					 => 'Notes',
			'vnp_cars_own'				 => 'Cars Own',
			'vnp_sedan_count'			 => 'Sedan Count',
			'vnp_compact_count'			 => 'Compact Count',
			'vnp_suv_count'				 => 'Suv Count',
			'vnp_oneway'				 => 'One Way',
			'vnp_round_trip'			 => 'Round Trip/Multi City',
			'vnp_multi_trip'			 => 'Multi Trip',
			'vnp_airport'				 => 'Airport Transfer',
			'vnp_package'				 => 'Package',
			'vnp_flexxi'				 => 'Flexxi',
			'vnp_daily_rental'			 => 'Day Rental',
			'vnp_lastmin_booking'		 => 'Last Min Booking',
			'vnp_tempo_traveller'		 => 'Tempo Traveller',
			'vnp_min_sd_req_amt'		 => 'Minimum Security Deposit Required'
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

		$criteria->compare('vnp_id', $this->vnp_id);
		$criteria->compare('vnp_vnd_id', $this->vnp_vnd_id);
		$criteria->compare('vnp_preferred_time_slots', $this->vnp_preferred_time_slots, true);
		$criteria->compare('vnp_booking_type', $this->vnp_booking_type);
		$criteria->compare('vnp_is_attached', $this->vnp_is_attached);
		$criteria->compare('vnp_is_freeze', $this->vnp_is_freeze);
		$criteria->compare('vnp_is_orientation', $this->vnp_is_orientation);
		$criteria->compare('vnp_cod_freeze', $this->vnp_cod_freeze);
		$criteria->compare('vnp_mod_day', $this->vnp_mod_day);
		$criteria->compare('vnp_invoice_date', $this->vnp_invoice_date, true);
		$criteria->compare('vnp_settle_date', $this->vnp_settle_date, true);
		$criteria->compare('vnp_home_zone', $this->vnp_home_zone, true);
		$criteria->compare('vnp_accepted_zone', $this->vnp_accepted_zone, true);
		$criteria->compare('vnp_excluded_cities', $this->vnp_excluded_cities, true);
		$criteria->compare('vnp_notes', $this->vnp_notes, true);
		$criteria->compare('vnp_cars_own', $this->vnp_cars_own);
		$criteria->compare('vnp_sedan_count', $this->vnp_sedan_count);
		$criteria->compare('vnp_compact_count', $this->vnp_compact_count);
		$criteria->compare('vnp_suv_count', $this->vnp_suv_count);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VendorPref the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getByVendorId($vndId)
	{
		return $this->find('vnp_vnd_id=:vnd', ['vnd' => $vndId]);
	}

	public function getStatusDesc()
	{
		$desc = "";
		if ($this->vnp_is_freeze != 0)
		{
			$desc .= 'Freezed, ';
		}
		if ($this->vnpVnd->vnd_active == 2)
		{
			$desc .= 'Blocked, ';
		}
		if ($this->vnpVnd->vnd_active == 3)
		{
			$desc .= 'Unapproved, ';
		}
		$desc = rtrim($desc, ', ');
		return $desc;
	}

	public function getApproveSatus()
	{
		$success = false;
		if ($this->vnpVnd->vnd_active == 1)
		{
			$success = true;
		}
		return $success;
	}

	public function updateVendorPref($vendorId, $data)
	{
		//$serviceResult            = $this->updateServiceTypeById($vendorId);
		$modelOld					 = $this->getByVendorId($vendorId);
		$model						 = $this->getByVendorId($vendorId);
		$model->vnp_sedan_count		 = $data['vnd_sedan_count'];
		$model->vnp_compact_count	 = $data['vnd_compact_count'];
		$model->vnp_suv_count		 = $data['vnd_suv_count'];
		$model->vnp_notes			 = $data['vnd_notes'];
		$serviceType				 = json_decode($data['serviceType']);
		$cabType					 = json_decode($data['cabType']);
		$serviceRequestFlag			 = 0;

		if (count($serviceType) > 0)
		{

			if (in_array("1", $serviceType))
			{
				if ($modelOld->vnp_oneway == -1)
				{
					$serviceRequestFlag	 = 1;
					$model->vnp_oneway	 = 0;
				}
				else
				{
					$model->vnp_oneway = $modelOld->vnp_oneway;
				}
			}
			else
			{
				$model->vnp_oneway = -1;
			}
			if (in_array("2", $serviceType))
			{
				if ($modelOld->vnp_round_trip == -1 || $modelOld->vnp_multi_trip == -1)
				{
					$serviceRequestFlag		 = 1;
					$model->vnp_round_trip	 = 0;
					$model->vnp_multi_trip	 = 0;
				}
				else
				{
					$model->vnp_round_trip	 = $modelOld->vnp_round_trip;
					$model->vnp_multi_trip	 = $modelOld->vnp_multi_trip;
				}
			}
			else
			{
				$model->vnp_round_trip	 = -1;
				$model->vnp_multi_trip	 = -1;
			}
//            if (in_array("3", $serviceType))
//            {
//                if ($modelOld->vnp_round_trip == -1 || $modelOld->vnp_multi_trip == -1)
//                {
//                    $serviceRequestFlag    = 1;
//                    $model->vnp_round_trip = 0;
//                    $model->vnp_multi_trip = 0;
//                }
//                else
//                {
//                    $model->vnp_round_trip = $modelOld->vnp_round_trip;
//                    $model->vnp_multi_trip = $modelOld->vnp_multi_trip;
//                }
//            }
//            else
//            {
//                $model->vnp_round_trip = -1;
//                $model->vnp_multi_trip = -1;
//            }
			if (in_array("4", $serviceType))
			{
				if ($modelOld->vnp_airport == -1)
				{
					$serviceRequestFlag	 = 1;
					$model->vnp_airport	 = 0;
				}
				else
				{
					$model->vnp_airport = $modelOld->vnp_airport;
				}
			}
			else
			{
				$model->vnp_airport = -1;
			}
			if (in_array("5", $serviceType))
			{
				if ($modelOld->vnp_package == -1)
				{
					$serviceRequestFlag	 = 1;
					$model->vnp_package	 = 0;
				}
				else
				{
					$model->vnp_package = $modelOld->vnp_package;
				}
			}
			else
			{
				$model->vnp_package = -1;
			}
			if (in_array("6", $serviceType))
			{
				if ($modelOld->vnp_flexxi == -1)
				{
					$serviceRequestFlag	 = 1;
					$model->vnp_flexxi	 = 0;
				}
				else
				{
					$model->vnp_flexxi = $modelOld->vnp_flexxi;
				}
			}
			else
			{
				$model->vnp_flexxi = -1;
			}
			if (in_array("9", $serviceType))
			{
				if ($modelOld->vnp_daily_rental == -1)
				{
					$serviceRequestFlag		 = 1;
					$model->vnp_daily_rental = 0;
				}
				else
				{
					$model->vnp_daily_rental = $modelOld->vnp_daily_rental;
				}
			}
			else
			{
				$model->vnp_daily_rental = -1;
			}
			if (in_array("0", $serviceType))
			{
				if ($modelOld->vnp_lastmin_booking == -1)
				{
					$serviceRequestFlag			 = 1;
					$model->vnp_lastmin_booking	 = 0;
				}
				else
				{
					$model->vnp_lastmin_booking = $modelOld->vnp_lastmin_booking;
				}
			}
			else
			{

				$model->vnp_lastmin_booking = -1;
			}
		}
		if (count($cabType) > 0)
		{
			if (in_array("4", $cabType))
			{
				if ($modelOld->vnp_tempo_traveller == -1)
				{
					$serviceRequestFlag			 = 1;
					$model->vnp_tempo_traveller	 = 0;
				}
				else
				{
					$model->vnp_tempo_traveller = $modelOld->vnp_tempo_traveller;
				}
			}
			else
			{
				$model->vnp_tempo_traveller = -1;
			}
		}
		else
		{
			$model->vnp_tempo_traveller = -1;
		}

		if (count($serviceType) == 0 && count($cabType) == 0)
		{
			$serviceResult = $this->updateServiceTypeById($vendorId);
		}
		$model->save();
		if ($serviceRequestFlag == 1 && ServiceCallQueue::isVendorServiceExists($vendorId) == 0)
		{
			ServiceCallQueue::autoFURVendorUpdateService($vendorId);
		}
	}

	public function updateServiceTypeById($vendorId)
	{
		$modelVendPref						 = VendorPref::model()->find('vnp_vnd_id=:id', ['id' => $vendorId]);
		$modelVendPref->vnp_oneway			 = -1;
		$modelVendPref->vnp_round_trip		 = -1;
		$modelVendPref->vnp_multi_trip		 = -1;
		$modelVendPref->vnp_airport			 = -1;
		$modelVendPref->vnp_package			 = -1;
		$modelVendPref->vnp_flexxi			 = -1;
		$modelVendPref->vnp_daily_rental	 = -1;
		$modelVendPref->vnp_lastmin_booking	 = -1;
		$modelVendPref->vnp_tempo_traveller	 = -1;
		$updateVendor						 = $modelVendPref->update();
		return $updateVendor;
	}

	public function sendLinkOtp($vndId)
	{
		$vendorPref = VendorPref::model()->find('vnp_vnd_id=:vnd_id', ['vnd_id' => $vndId]);
//		if ($vendorPref->vnp_user_link_otp == '')
//		{

		$vendorPref->vnp_user_link_otp = rand(100100, 999999);
		//}
		if ($vendorPref->save())
		{

			return $vendorPref->vnp_user_link_otp;
		}
		else
		{
			return 0;
		}
	}

	public function updateVendorPrefByVendorId($vndId)
	{
		$sql = "Update `vendor_pref` set vnp_multi_link=0 WHERE vnp_vnd_id=$vndId";
		$cnt = DBUtil::command($sql)->execute();
		return $cnt;
	}

	public function verifyUserLinkByOTP($vndId, $otp)
	{
		$success	 = false;
		$errors		 = [];
		$transaction = DBUtil::beginTransaction();
		try
		{

			$vendorPref = VendorPref::model()->find('vnp_vnd_id=:vnd_id', ['vnd_id' => $vndId]);
//			if ($vendorPref->vnp_is_user_link_verified == 1)
//			{
//				$errors = "Your OTP already verified.";
//				throw new Exception($errors);
//			}
			if ($vendorPref->vnp_user_link_otp != $otp)
			{
				$errors = "Invalid OTP.";
				throw new Exception($errors);
			}
			else
			{
				$vendorPref->vnp_is_user_link_verified = 1;
				if ($vendorPref->save())
				{
					DBUtil::commitTransaction($transaction);
					$success = true;
					$message = "OTP verified successfully.";
				}
				else
				{
					$errors = $vendorPref->getErrors();
				}
			}
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$message = $errors;
		}
		return ['success' => $success, 'errors' => $message];
	}

	public static function getListOrientationReq()
	{
		$sql = "SELECT v2.vnd_id, contact.ctt_id
				FROM `vendors` v2
				INNER JOIN contact_profile cp ON cp.cr_is_vendor = v2.vnd_id AND v2.vnd_id = v2.vnd_ref_code AND cp.cr_status =1
				INNER JOIN contact ON contact.ctt_id= cp.cr_contact_id AND contact.ctt_active =1 and contact.ctt_id = contact.ctt_ref_code
				JOIN `vendor_pref` ON vendor_pref.vnp_vnd_id = v2.vnd_id
				JOIN `vendor_stats` ON vendor_stats.vrs_vnd_id = v2.vnd_id
				LEFT JOIN `vendor_agreement` ON vendor_agreement.vag_vnd_id = v2.vnd_id AND vendor_agreement.vag_active = 1 AND vendor_agreement.vag_digital_flag =1 AND vendor_agreement.vag_digital_ver >= '171219'
				WHERE v2.vnd_active = 3 AND vendor_pref.vnp_is_orientation = 0 AND vendor_stats.vrs_docs_r4a = 1
				GROUP BY v2.vnd_ref_code";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	/**
	 * 
	 * @param integer $vendorId
	 * @param string $contactName
	 * @param string $userInfo
	 * @return string
	 * @throws Exception
	 */
	public function unsetOrientationFlag($vendorId, $contactName, $userInfo, $vendorLog = '')
	{
		$success	 = false;
		$message	 = '';
		$userInfo	 = UserInfo::getInstance();
		try
		{
			$modelVendPref = VendorPref::model()->find('vnp_vnd_id=:id', ['id' => $vendorId]);
			if ($vendorId > 0 && $contactName != '' && $modelVendPref->vnp_is_orientation == 1)
			{
				$modelVendPref->vnp_is_orientation	 = 2;
				$modelVendPref->vnp_orientation_type = 1;
				if (!$modelVendPref->save())
				{
					$var = "Failed to save => " . json_encode($modelVendPref->getErrors());
					throw new Exception($var);
				}
				if ($modelVendPref->save())
				{
					$model = Vendors::model()->findByPk($vendorId);
					if ($model->vnd_active != 1)
					{
						$eventId			 = VendorsLog::VENDOR_APPROVE;
						$desc				 = "Vendor [ " . $contactName . " ] : " . VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_APPROVE);
						$descOrientation	 = "Vendor [ " . $contactName . " ] call is complete : " . $vendorLog;
						$model->vnd_active	 = 1;
						if (!$model->save())
						{
							$var = "Failed to save => " . json_encode($model->getErrors());
							throw new Exception($var);
						}
						$model->save();
						VendorsLog::model()->createLog($vendorId, $descOrientation, $userInfo, VendorsLog::VENDOR_ORIENTATION_OFF, false, false);
						VendorsLog::model()->createLog($vendorId, $desc, $userInfo, $eventId, false, false);
					}
					else
					{
						$eventId = VendorsLog::VENDOR_ORIENTATION_OFF;
						$desc	 = "Vendor [ " . $contactName . " ] call is complete :" . VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_ORIENTATION_OFF);
						VendorsLog::model()->createLog($vendorId, $desc, $userInfo, $eventId, false, false);
					}
				}
				$success = true;
				$message = $desc;
			}
			else
			{
				throw new Exception("Vendor or contact name not exists.");
			}
		}
		catch (Exception $ex)
		{
			$message = $ex->getMessage();
		}
		return ['success' => $success, 'message' => $message];
	}

	public function setOrientationFlag($vendorId, $contactId)
	{
		$success	 = false;
		$message	 = '';
		$userInfo	 = UserInfo::getInstance();
		$transaction = DBUtil::beginTransaction();
		try
		{
			if ($vendorId > 0 && $contactId > 0)
			{
				$modelVendPref	 = VendorPref::model()->find('vnp_vnd_id=:id', ['id' => $vendorId]);
				$contactData	 = Contact::model()->getNameById($vendorId);
				if (!$modelVendPref)
				{
					$modelVendPref				 = new VendorPref();
					$modelVendPref->vnp_vnd_id	 = $vendorId;
				}
				$modelVendPref->vnp_is_orientation = 1;
				if (!$modelVendPref->save())
				{
					throw new Exception("Failed to save => " . json_encode($modelVendPref->getErrors()));
				}
				if ($modelVendPref->save())
				{
					$params	 = ['vnd_id'			 => $vendorId,
						'full_name'			 => $contactData['ownername'],
						'email'				 => ContactEmail ::getPrimaryEmail($contactId),
						'video_link'		 => 'https://youtu.be/AfbwgIJN0H0',
						'app_link'			 => 'https://play.google.com/store/apps/details?id=com.gozocabs.vendor&hl=en',
						'driver_app_link'	 => 'https://play.google.com/store/apps/details?id=com.gozocabs.driver&hl=en_US'
					];
					emailWrapper::mailToApproveVendor($params);
					$desc	 = "Vendor [ " . $contactData['ownername'] . " ] Modified : " . VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_ORIENTATION_ON);
					VendorsLog::model()->createLog($vendorId, $desc, $userInfo, VendorsLog:: VENDOR_ORIENTATION_ON, false, false);
					echo $desc . "\n";
				}
				$success = DBUtil::commitTransaction($transaction);
				$message = $desc;
			}
			else
			{
				throw new Exception("Vendor or contact id not exists.");
			}
		}
		catch (Exception $ex)
		{
			$message = $ex->getMessage();
			DBUtil::rollbackTransaction($transaction);
		}
		return ['success' => $success, 'message' => $message];
	}

	public function updateDormantFlag()
	{
		$sql = "SELECT
				vendors.vnd_id,
				last_login,
				apt_logout,
				DATE_SUB(NOW(), INTERVAL 100 DAY) AS interval_100_days,
				last_create_date,
				DATE_SUB(NOW(), INTERVAL 60 DAY) AS interval_60_days
			FROM
				`vendors`
			INNER JOIN `vendor_pref` ON vendor_pref.vnp_vnd_id = vendors.vnd_id
			INNER JOIN(
				SELECT
					app_tokens.apt_entity_id,
					COUNT(1) AS cnt,
					MAX(app_tokens.apt_last_login) AS last_login,
					app_tokens.apt_logout
				FROM
					`app_tokens`
				WHERE
					app_tokens.apt_user_type = 2 AND app_tokens.apt_entity_id IS NOT NULL
				GROUP BY
					app_tokens.apt_entity_id
			) AS token
			ON
				token.apt_entity_id = vendors.vnd_id AND vendor_pref.vnp_is_freeze = 0 AND vendors.vnd_active = 1
			INNER JOIN(
				SELECT
					booking_vendor_request.bvr_vendor_id,
					MAX(
						booking_vendor_request.bvr_created_at
					) AS last_create_date
				FROM
					booking_vendor_request
				GROUP BY
					booking_vendor_request.bvr_vendor_id
			) AS bidding
			ON
				bidding.bvr_vendor_id = vendors.vnd_id
			WHERE
				last_login < DATE_SUB(NOW(), INTERVAL 100 DAY) OR last_create_date < DATE_SUB(NOW(), INTERVAL 60 DAY) AND apt_logout IS NULL AND vendors.vnd_active = 1
			ORDER BY
				last_login,
				last_create_date
			DESC";

		$result = DBUtil::queryAll($sql);
		if ($result != '')
		{
			foreach ($result as $key => $value)
			{

				/* @var $prefModel VendorPref */
				$prefModel					 = VendorPref::model()->getByVendorId($value['vnd_id']);
				$prefModel->vnp_is_dormant	 = 1;
				if ($prefModel->save())
				{
					echo " \n Updated Dormant flag for vendor ID -" . $value['vnd_id'];
				}
			}
		}
		else
		{
			echo 'No Vendors Found';
		}
	}

	public function resetDormantFlag()
	{
		$sql	 = "SELECT
					vendors.vnd_id,
					COUNT(`bkg_id`) AS count_booking
				FROM
					`booking`
				INNER JOIN booking_cab ON booking_cab.bcb_bkg_id1 = booking.bkg_id
				INNER JOIN vendors ON booking_cab.bcb_vendor_id = vendors.vnd_id
				INNER JOIN vendor_pref ON booking_cab.bcb_vendor_id = vendor_pref.vnp_vnd_id AND vendor_pref.vnp_is_dormant = 1
				WHERE
					booking.bkg_pickup_date BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND  DATE(NOW()) 
					AND booking.bkg_status IN (3,4,5,6,7)
				GROUP BY
					vendors.vnd_id  
				ORDER BY `count_booking`  DESC";
		$result	 = DBUtil::queryAll($sql);
		if ($result != '')
		{
			foreach ($result as $key => $value)
			{
				if ($value['count_booking'] >= 10)
				{
					$modelPref					 = $this->getByVendorId($value['vnd_id']);
					$modelPref->vnp_is_dormant	 = 0;
					if ($modelPref->save())
					{
						echo " \n Reset Dormant Flag for Vendor ID:" . $value['vnd_id'];
					}
				}
			}
		}
		else
		{
			echo 'No Vendors Found';
		}
	}

	public function getDormantVendorReport($zoneID = 0, $phone = '', $type = '')
	{
		$where = '';
		if ($zoneID > 0)
		{
			$where .= ' AND vendor_pref.vnp_home_zone =' . $zoneID;
		}
		if ($phone != '')
		{
			$where .= ' AND contact_phone.phn_phone_no LIKE "%' . $phone . '%"';
		}
		$sql = "SELECT
				vendors.vnd_id,
				vendors.vnd_name,
				contact_phone.phn_phone_no,
				zones.zon_name AS home_zone,
				vrs_last_logged_in AS last_login,
				bidding.last_create_date AS last_bidding_date,
				vendor_stats.vrs_last_bkg_cmpleted AS last_booking_completed
				FROM vendor_pref
				JOIN vendors ON vendors.vnd_id = vendor_pref.vnp_vnd_id AND vendors.vnd_id = vendors.vnd_ref_code AND vendors.vnd_active >0
				JOIN contact_profile cp ON cp.cr_is_vendor = vendors.vnd_id AND cp.cr_status = 1
				JOIN contact ctt ON ctt.ctt_id = cp.cr_contact_id AND ctt.ctt_id = ctt.ctt_ref_code AND ctt.ctt_active =1
				JOIN vendor_stats ON vendor_stats.vrs_vnd_id = vendor_pref.vnp_vnd_id
				JOIN contact_phone ON contact_phone.phn_contact_id = ctt.ctt_id
				JOIN zones ON vendor_pref.vnp_home_zone = zones.zon_id and vendor_pref.vnp_is_dormant = 1
				LEFT JOIN
				(
				SELECT booking_vendor_request.bvr_vendor_id, MAX(booking_vendor_request.bvr_created_at) AS last_create_date
				FROM booking_vendor_request
				INNER JOIN vendor_pref ON booking_vendor_request.bvr_vendor_id = vendor_pref.vnp_vnd_id AND vendor_pref.vnp_is_dormant = 1
				WHERE booking_vendor_request.bvr_bid_amount > 0 GROUP BY booking_vendor_request.bvr_vendor_id
				) AS bidding ON bidding.bvr_vendor_id = vendors.vnd_id
				WHERE 1 $where	GROUP BY vendors.vnd_id ";

		$sqlCount = "SELECT
					vendors.vnd_id
					FROM vendor_pref
					JOIN vendors ON vendors.vnd_id = vendor_pref.vnp_vnd_id AND vendors.vnd_id = vendors.vnd_ref_code AND vendors.vnd_active >0
					JOIN contact_profile cp ON cp.cr_is_vendor = vendors.vnd_id AND cp.cr_status = 1
					JOIN contact ctt ON ctt.ctt_id = cp.cr_contact_id AND ctt.ctt_id = ctt.ctt_ref_code AND ctt.ctt_active =1
					JOIN vendor_stats ON vendor_stats.vrs_vnd_id = vendor_pref.vnp_vnd_id
					JOIN contact_phone ON contact_phone.phn_contact_id = ctt.ctt_id
					INNER JOIN zones ON vendor_pref.vnp_home_zone = zones.zon_id and vendor_pref.vnp_is_dormant = 1
					WHERE 1 $where GROUP BY vendors.vnd_id";

		if ($type == 'command')
		{
			return DBUtil::query($sql, DBUtil::SDB());
		}
		else
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, array(
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => array('attributes'	 => array('last_bidding_date', 'last_login', 'last_booking_completed'),
					'defaultOrder'	 => 'last_login DESC'),
				'pagination'	 => array('pageSize' => 50),
			));
			return $dataprovider;
		}
	}

	public static function setBidFloated()
	{
		try
		{
			$bookingsArr = BookingSub::getFloatedBookingsToBid();

			foreach ($bookingsArr as $booking)
			{
				$zoneArray = ZoneCities::model()->getZoneByCityId($booking['bkg_from_city_id']);

				$tot					 = VendorPref::getTotVendorsEligible($zoneArray, $booking['bkg_from_city_id'], false);
				$totExcl				 = VendorPref::getExcludedDCOCount($zoneArray, $booking['bkg_from_city_id'], $booking['bcb_start_time'], $booking['bcb_end_time'], false);
				$totLoggedIn			 = VendorPref::getTotVendorsEligible($zoneArray, $booking['bkg_from_city_id']);
				$totLoggedInExcl		 = VendorPref::getExcludedDCOCount($zoneArray, $booking['bkg_from_city_id'], $booking['bcb_start_time'], $booking['bcb_end_time']);
				$bidCountFloated		 = $tot - $totExcl;
				$bidCountFloatedLoggenIn = $totLoggedIn - $totLoggedInExcl;
				if ($bidCountFloatedLoggenIn > $booking['btr_bid_floated_logged_id'])
				{
					DBUtil::command("UPDATE booking_trail SET btr_bid_floated_logged_id = {$bidCountFloatedLoggenIn} WHERE btr_bkg_id = {$booking['bkg_id']}")->execute();
					DBUtil::command("UPDATE booking_trail SET btr_bid_floated = {$bidCountFloated} WHERE btr_bkg_id = {$booking['bkg_id']}")->execute();
					echo "booking ID: " . $booking['bkg_id'] . ", bid floated count: " . $bidCountFloated . "( out of " . $tot . ")" . ", bid floated count logged in: " . $bidCountFloatedLoggenIn . " ( out of " . $totLoggedIn . ")" . "<br>";
				}
			}
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
		}
	}

	public static function getTotVendorsEligible($zoneArr, $city, $isLoggedIn = true)
	{
		$cndLoggedIn = "";
		$str1		 = "";
		if ($isLoggedIn)
		{
			$cndLoggedIn = " INNER JOIN app_tokens ON apt_entity_id = vnd_id AND apt_user_type = 2 AND apt_last_login >= DATE_SUB(NOW(),INTERVAL 7 DAY) AND apt_status = 1";
		}
		$sql1 = "SELECT COUNT(DISTINCT vnd_id) FROM vendors INNER JOIN vendor_pref ON vnp_vnd_id = vnd_id AND vnd_active = 1 
                 $cndLoggedIn WHERE  NOT FIND_IN_SET('{$city}', IFNULL(vnp_excluded_cities,'')) AND";
		foreach ($zoneArr as $zone)
		{
			$str1 .= " OR (FIND_IN_SET('$zone',IFNULL(vnp_accepted_zone,'')) OR FIND_IN_SET('$zone',IFNULL(vnp_home_zone,'')))";
		}
		$sql					 = rtrim($sql1 . ltrim($str1, ' OR'), ' AND');
		//  echo $sql."<br><br>";
		$bidCountFloatedLoggenIn = DBUtil::command($sql)->queryScalar();
		return $bidCountFloatedLoggenIn;
	}

	public static function getExcludedDCOCount($zoneArr, $city, $tripStart, $tripEnd, $isLoggedIn = true)
	{
		$cndLoggedIn			 = " ";
		$excludeDCOLoggedInCount = 0;
		$str1					 = "";
		if ($isLoggedIn)
		{
			$cndLoggedIn = " INNER JOIN app_tokens ON apt_entity_id = vnd_id AND apt_user_type = 2 AND apt_last_login >= DATE_SUB(NOW(),INTERVAL 7 DAY) AND apt_status = 1";
		}
		$sqlDCO1 = "SELECT GROUP_CONCAT(DISTINCT vnd_id) FROM vendors 
					INNER JOIN vendor_pref ON vnp_vnd_id = vnd_id AND vnd_active = 1 AND vnd_cat_type = 1
					$cndLoggedIn
					WHERE  NOT FIND_IN_SET('{$city}', IFNULL(vnp_excluded_cities,'')) AND";

		foreach ($zoneArr as $zone)
		{
			$str1 .= " OR (FIND_IN_SET('$zone',IFNULL(vnp_accepted_zone,'')) OR FIND_IN_SET('$zone',IFNULL(vnp_home_zone,'')))";
		}
		$sqlDCO						 = rtrim($sqlDCO1 . ltrim($str1, ' OR'), ' AND');
		//  echo $sqlDCO."<br><br>";
		$bidCountFloatedLoggenInDCO	 = DBUtil::command($sqlDCO)->queryScalar();

		if ($bidCountFloatedLoggenInDCO != "")
		{
			$excludeDCOSql			 = "SELECT count(DISTINCT bcb_vendor_id) 
							FROM   booking_cab bcb
							INNER JOIN booking ON  bcb.bcb_id = booking.bkg_bcb_id AND bkg_status IN (3, 5, 6, 7) AND bcb_vendor_id IN({$bidCountFloatedLoggenInDCO}) AND (bcb.bcb_start_time > NOW() OR bcb.bcb_end_time > NOW())
							WHERE  bcb.bcb_active = 1 AND ('{$tripStart}' BETWEEN bcb.bcb_start_time AND bcb.bcb_end_time OR '{$tripEnd}' BETWEEN bcb.bcb_start_time AND bcb.bcb_end_time)";
			$excludeDCOLoggedInCount = DBUtil::command($excludeDCOSql)->queryScalar();
		}
		return $excludeDCOLoggedInCount;
	}

	public function checkStatusServiceType($vnd_id)
	{
		$sql		 = "SELECT COUNT(*) AS serviceType 
                        FROM vendor_pref WHERE 1 
                        AND vnp_vnd_id =:vnp_vnd_id 
                        AND 
                        (			
                            vnp_oneway IN (0,1)
                            OR vnp_round_trip IN (0,1)
                            OR vnp_multi_trip IN (0,1)
                            OR vnp_airport IN (0,1)
                            OR vnp_package IN (0,1)
                            OR vnp_flexxi IN (0,1)
                            OR vnp_daily_rental IN (0,1)
                            OR vnp_lastmin_booking IN (0,1)
                            OR vnp_tempo_traveller IN (0,1)				
                        )";
		$serviceType = DBUtil::queryScalar($sql, DBUtil::SDB(), ['vnp_vnd_id' => $vnd_id]);
		$status		 = false;
		if ($serviceType >= 1)
		{
			$status = true;
		}
		return $status;
	}

	/**
	 * This function is used for adding vendor preferences
	 * 
	 * @param int $vendorId		-	vnd_id		 -	Mandatory
	 * @param  $zoneData		-	ZoneId		 -	Mandatory
	 * @param type $carOwnCount -	Count of car -	Mandatory
	 * 
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public static function addVendorPref($vendorId, $zoneData, $carOwnCount)
	{
		$returnset = new ReturnSet();
		try
		{
			if (empty($vendorId))
			{
				throw new Exception("Data not passed", ReturnSet::ERROR_INVALID_DATA);
			}

			$vendorPrefModel				 = new VendorPref();
			$vendorPrefModel->vnp_vnd_id	 = $vendorId;
			$vendorPrefModel->vnp_cars_own	 = $carOwnCount;

			if (!empty($zoneData))
			{
				$vendorPrefModel->vnp_home_zone = $zoneData["zon_id"];
			}
			else
			{
				$vendorPrefModel->vnp_home_zone = '';
			}

			if (!$vendorPrefModel->save())
			{
				throw new Exception(json_encode($vendorPrefModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			if (!empty($zoneData))
			{
				$vendorModel			 = Vendors::model()->findByPk($vendorId);
				$vendorModel->vnd_id	 = $vendorId;
				$vendorModel->vnd_name	 = $vendorModel->vnd_name . "_" . $zoneData["zon_name"];
				$vendorModel->save();
			}

			$returnset->setStatus(true);
			$returnset->setMessage("Vendor preference created");
		}
		catch (Exception $e)
		{
			Logger::error($e->getMessage());
			$returnset->setException($e);
		}

		return $returnset;
	}

	/**
	 * @param int $returnType DBUtil::ReturnType_*
	 * @return mixed Based on $returnType
	 */
	public function getVehicleTierCountByZone($returnType = DBUtil::ReturnType_Provider)
	{

		$sql		 = "SELECT zones.zon_id, zones.zon_name, zones.zon_lat, zones.zon_long, zon_region, vnp_home_zone,
						COUNT(DISTINCT IF(FIND_IN_SET('1', vnp.vnp_is_allowed_tier), IFNULL(vnd_ref_code, vnd_id), null)) as cntValueVendors,
						COUNT(DISTINCT IF(FIND_IN_SET('2', vnp.vnp_is_allowed_tier), IFNULL(vnd_ref_code, vnd_id), null)) as cntValuePlusVendors,
						COUNT(DISTINCT IF(FIND_IN_SET('3', vnp.vnp_is_allowed_tier), IFNULL(vnd_ref_code, vnd_id), null)) as cntPlusVendors,
						COUNT(DISTINCT IF(FIND_IN_SET('4', vnp.vnp_is_allowed_tier), IFNULL(vnd_ref_code, vnd_id), null)) as cntSelectVendors,
						COUNT(DISTINCT IF(FIND_IN_SET('1', vhc_is_allowed_tier), vhc_id, null)) as cntValueVehicles,
						COUNT(DISTINCT IF(FIND_IN_SET('2', vhc_is_allowed_tier), vhc_id, null)) as cntValuePlusVehicles,
						COUNT(DISTINCT IF(FIND_IN_SET('3', vhc_is_allowed_tier), vhc_id, null)) as cntPlusVehicles,
						COUNT(DISTINCT IF(FIND_IN_SET('4', vhc_is_allowed_tier), vhc_id, null)) as cntSelectVehicles
					  FROM vendors vnd
									  INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd.vnd_id AND vnd_active=1
									  INNER JOIN zones ON zones.zon_id = vnp.vnp_home_zone
					  INNER JOIN vendor_vehicle ON  vendor_vehicle.vvhc_vnd_id = vnp_vnd_id AND vvhc_active = 1 
					  INNER JOIN vehicles ON vvhc_vhc_id = vhc_id AND vhc_active = 1 AND vhc_approved = 1
				";
		$sqlCount	 = "SELECT vnd.vnd_id
					FROM vendors vnd
					INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd.vnd_id AND vnd_active=1
					INNER JOIN zones ON zones.zon_id = vnp.vnp_home_zone
					INNER JOIN vendor_vehicle ON  vendor_vehicle.vvhc_vnd_id = vnp_vnd_id AND vvhc_active = 1 
					INNER JOIN vehicles ON vvhc_vhc_id = vhc_id AND vhc_active = 1 AND vhc_approved = 1
				";

		if ($this->vnp_home_zone != '')
		{
			$sql		 .= " AND zon_id IN ({$this->vnp_home_zone})";
			$sqlCount	 .= " AND zon_id IN ({$this->vnp_home_zone})";
		}
		if ($this->zonRegion != '')
		{
			$sql		 .= " AND zon_region LIKE '%{$this->zonRegion}%'";
			$sqlCount	 .= " AND zon_region LIKE '%{$this->zonRegion}%'";
		}

		$sql		 .= " GROUP BY zones.zon_id";
		$sqlCount	 .= " GROUP BY zones.zon_id";

		$return = DBUtil::command($sql, DBUtil::SDB());

		if ($returnType == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($return, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 =>
				['attributes'	 =>
					['zon_name'],
					'defaultOrder'	 => ''],
				'pagination'	 => [],
			]);
			$return			 = $dataprovider;
		}
		if ($returnType == DBUtil::ReturnType_Query)
		{
			$recordset	 = DBUtil::query($sql, DBUtil::SDB());
			$return		 = $recordset;
		}
		return $return;
	}

	public function updateBoostCount($vendorId)
	{

		$criteria	 = new CDbCriteria;
		$criteria->compare('vnp_vnd_id', $vendorId);
		$model		 = $this->find($criteria);

		if ($model)
		{
			$model->vnp_vhc_boost_count	 = $model->vnp_vhc_boost_count + 1;
			$model->vnp_boost_enabled	 = 1;
			$success					 = $model->save();
			return $success;
		}
		else
		{
			return false;
		}
	}

	public function modifyBoostCount($vendorId)
	{

		$criteria	 = new CDbCriteria;
		$criteria->compare('vnp_vnd_id', $vendorId);
		$model		 = $this->find($criteria);

		if ($model)
		{
			$model->vnp_vhc_boost_count = $model->vnp_vhc_boost_count - 1;
			if ($model->vnp_vhc_boost_count == 0)
			{
				$model->vnp_boost_enabled = 0;
			}
			$success = $model->save();
			return $success;
		}
		else
		{
			return false;
		}
	}

	public static function updateHomeZone()
	{
		$sql	 = "SELECT vnd.vnd_id,zon_id FROM vendors vnd 
					INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd.vnd_id
					INNER JOIN zones ON zon_name = vnp.vnp_home_zone
					WHERE vnp.vnp_home_zone REGEXP '[A-Za-z]' AND vnd.vnd_active =1";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($result as $val)
		{
			try
			{
				$params		 = array("vnd_id" => $val['vnd_id'], 'vnp_home_zone' => $val['zon_id']);
				$sqlupdate	 = "UPDATE vendor_pref SET vendor_pref.vnp_home_zone = :vnp_home_zone WHERE 1 AND vendor_pref.vnp_vnd_id=:vnd_id";
				DBUtil::execute($sqlupdate, $params);
			}
			catch (Exception $ex)
			{
				Logger::writeToConsole($ex->getMessage());
			}
		}
	}

	/** check vendor frozen or not
	 * 
	 * @param type $vendorId
	 * @return boolean
	 */
	public static function checkfrozen($vendorId)
	{
		$params	 = ['vendorId' => $vendorId];
		$sql	 = "SELECT vnp_is_freeze FROM vendor_pref WHERE vnp_vnd_id=:vendorId";
		$result	 = DBUtil::queryRow($sql, null, $params);
		return $result[vnp_is_freeze];
	}

	/**
	 * This function is used to make temporary rating of vendor
	 * @param type $vndId
	 * @param type $userInfo
	 */
	public function tempRatings($vndId, $userInfo)
	{

		$date				 = date('Y-m-d');
		$params				 = array("vnd_id" => $vndId);
		$userId				 = $userInfo->userId;
		$userName			 = Admins::model()->getFullNameById($userId);
		$event_id			 = VendorsLog::VENDOR_TEMP_RATINGS;
		$tmpRatingValidity	 = $this->showtmpRatingVldityDate($vndId);
		$tmpRatingLimit		 = date('Y-m-d', strtotime($tmpRatingValidity . ' + 180 days'));
		if ($tmpRatingValidity == null || $tmpRatingLimit < $date)
		{
			$validityDate	 = "DATE(DATE_ADD(NOW(), INTERVAL 60 DAY))";
			$sqlupdate		 = "UPDATE vendor_pref SET vendor_pref.vnp_tmp_rating_validity = $validityDate WHERE 1 AND vendor_pref.vnp_vnd_id=:vnd_id";
			DBUtil::execute($sqlupdate, $params);
			$tmpRatingDate	 = date('Y-m-d', strtotime($date . ' + 60 days'));
			$desc			 = $userName . " gave TR_boost until  " . $tmpRatingDate . " successfully";
			VendorsLog::model()->createLog($vndId, $desc, $userInfo, $event_id, false, false);
			//send notification
			$payLoadData	 = ['EventCode' => $event_id];
			$message		 = " You have received a temporary ratings boost by the Gozo Supply team. This is a chance for you to get as many FIVE STAR RATINGS as possible. Make sure you give amazing service and request every customer to give you 5* ratings. Temporary ratings boost can only be received one time so make full use of this benefit to improve your ratings";
			AppTokens::model()->notifyVendor($vndId, $payLoadData, $message, "Temporary ratings boost");
			$model			 = VendorStats::model()->getbyVendorId($vndId);
			$previousRating	 = $model->vrs_vnd_overall_rating;

			Vendors::model()->updateDetails($vndId);
			$model			 = VendorStats::model()->getbyVendorId($vndId);
			$currentRating	 = $model->vrs_vnd_overall_rating;
			if ($currentRating > $previousRating)
			{
				$ratingDesc = "vendor current rating after boost " . $currentRating . " AND Previous rating " . $previousRating;
				VendorsLog::model()->createLog($vndId, $ratingDesc, $userInfo, $event_id, false, false);
			}
			return true;
		}
		$desc = $userName . " gave temporary boost failed";
		VendorsLog::model()->createLog($vndId, $desc, $userInfo, $event_id, false, false);
		return false;
	}

	/**
	 * This function is used to show temporary rating validity date
	 * @param type $vndId
	 * @return type
	 */
	public function showtmpRatingVldityDate($vndId)
	{
		$params	 = array("vnd_id" => $vndId);
		$sql	 = "SELECT vnp_tmp_rating_validity FROM vendor_pref WHERE vnp_vnd_id=:vnd_id";
		$result	 = DBUtil::queryRow($sql, null, $params);

		$tmpRatingValidity = $result['vnp_tmp_rating_validity'];
		return $tmpRatingValidity;
	}

	public static function getServiceList($all = false)
	{
		$list = [
			'vnp_oneway',
			'vnp_round_trip',
			'vnp_multi_trip',
			'vnp_airport',
			'vnp_package',
			'vnp_flexxi',
			'vnp_daily_rental',
			'vnp_lastmin_booking',
			'vnp_tempo_traveller'];

		$disabled = ['vnp_multi_trip', 'vnp_flexxi'];
		if (!$all)
		{
			foreach ($disabled as $delVal)
			{
				if (($key = array_search($delVal, $list)) !== false)
				{
					unset($list[$key]);
				}
			}
		}
		return $list;
	}

	public static function getRequestedServiceList($vndid)
	{
		$list		 = self::getServiceList();
		$params		 = ['vndId' => $vndid];
		$serviceStr	 = implode(',', $list);
		$sql		 = "SELECT $serviceStr from vendor_pref WHERE vnp_vnd_id=:vndId";
		$res		 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		$resStr		 = json_encode($res);

		return $resStr;
	}

	public static function updateRequestedServices($vndid)
	{
		$serviceStr	 = self::getRequestedServiceList($vndid);
		$params		 = ['vndId' => $vndid];
		$sql		 = "UPDATE vendor_pref SET vnp_vnd_requested_services='$serviceStr' WHERE vnp_vnd_id=:vndId";
		$cnt		 = DBUtil::execute($sql, $params);
		return $cnt;
	}

	public static function setDefaultAdminApprovedServices($vndid)
	{
		$serviceStr	 = self::getRequestedServiceList($vndid);
		$cnt		 = self::updateAdminApprovedServices($vndid, $serviceStr);
		return $cnt;
	}

	public static function updateAdminApprovedServices($vndid, $serviceStr)
	{
		if ($serviceStr != '')
		{
			$params	 = ['vndId' => $vndid];
			$sql	 = "UPDATE vendor_pref SET vnp_admin_approved_services='$serviceStr' WHERE vnp_vnd_id=:vndId";
			$cnt	 = DBUtil::execute($sql, $params);
		}
		return $cnt;
	}

	public static function getEffectiveServiceList($vndid)
	{
		$params	 = ['vndId' => $vndid];
		$sql	 = "SELECT vnp_vnd_requested_services,vnp_admin_approved_services from vendor_pref WHERE vnp_vnd_id=:vndId";
		$res	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);

		$vendorRequestedList = json_decode($res['vnp_vnd_requested_services'], true);
		$adminApprovedList	 = json_decode($res['vnp_admin_approved_services'], true);
		$list				 = self::getServiceList();
		$resServeList		 = [];
		foreach ($list as $key)
		{
			$resServeList[$key] = ( in_array($vendorRequestedList[$key], ['1', '-1']) && in_array($adminApprovedList[$key], ['1', '-1'])) ? '1' : '0';
		}
		return $resServeList;
	}

	public static function checkApprovedService($vndid, $bookingType)
	{

		if ($bookingType == 1 || $bookingType == 15)
		{
			$condition = 'vnp_oneway';
		}
		if ($bookingType == 2 || $bookingType == 8)
		{
			$condition = 'vnp_round_trip';
		}
		if ($bookingType == 3 || $bookingType == 8)
		{
			$condition = 'vnp_multi_trip';
		}
		if ($bookingType == 4 || $bookingType == 12)
		{
			$condition = 'vnp_airport';
		}
		if ($bookingType == 5)
		{
			$condition = 'vnp_package';
		}
		if ($bookingType == 9 || $bookingType == 10 || $bookingType == 11)
		{
			$condition = 'vnp_daily_rental';
		}
		$params = ['vndId' => $vndid];

		$sql = "SELECT COUNT(*) FROM vendor_pref WHERE $condition = 1 AND vnp_vnd_id = :vndId";

		$dataCount = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $dataCount;
	}

	/**
	 * modify gnow status according to snooze time
	 * @param type $vndId
	 * @param type $gnowStat
	 */
	public static function updateGnowStatus($vndId, $gnowStat, $snoozeTime = null)
	{

		if ($gnowStat == 1 || $snoozeTime == "" || $snoozeTime == null)
		{
			$condition = 'vnp_snooze_time =NULL';
		}
		else
		{
			$condition = 'vnp_snooze_time ="' . $snoozeTime . '"';
		}
		$currentdate = DBUtil::getCurrentTime();
		$params		 = ['vndId' => $vndId, 'currentDate' => $currentdate, 'status' => $gnowStat];
		$sql		 = "UPDATE vendor_pref SET vnp_gnow_status=:status, vnp_gnow_modify_time= :currentDate, $condition  WHERE vnp_vnd_id=:vndId";
		$cnt		 = DBUtil::execute($sql, $params);

		return $cnt;
	}

	public static function getGozoNowVendorList()
	{
		$sql = "SELECT vnp_vnd_id FROM vendor_pref WHERE 1 AND vendor_pref.vnp_gnow_status=0";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	/**
	 * show gnow notification status
	 * @param type $vendorId
	 * @return int
	 */
	public static function getGNowNotificationStatus($vendorId)
	{
		$res		 = VendorPref::model()->getByVendorId($vendorId);
		$isGozoNow	 = $res->vnp_gnow_status;
		$snoozeTime	 = $res->vnp_snooze_time;
		if ( ($isGozoNow == 0) &&($snoozeTime != NULL) && (strtotime($snoozeTime) < strtotime('now')))
		{
			$isGozoNow = 1;
		}

		return $isGozoNow;
	}

	public static function getInfoById($vndId)
	{
		$param	 = ['vndId' => $vndId];
		$sql	 = "SELECT vnd_cat_type,vnp_is_freeze,vnp_cod_freeze,vnd_is_dco, 
						if(vnp_accepted_zone='',-1,vnp_accepted_zone) vnp_accepted_zone, 
						if(vnp_home_zone='',-1,vnp_home_zone) vnp_home_zone, 
						if(vnp_excluded_cities='',-1,vnp_excluded_cities) vnp_excluded_cities, 
						vnp_oneway, vnp_round_trip, vnp_multi_trip, vnp_airport, vnp_package, 
						vnp_flexxi,vnp_daily_rental,ifnull(vnp_is_allowed_tier,'1,2,4,5,6') vnp_is_allowed_tier,vnd_active
					FROM vendors 
					INNER JOIN vendor_pref ON vnp_vnd_id = vnd_id 
					WHERE vnd_id =:vndId";
		return DBUtil::queryRow($sql, DBUtil::SDB(), $param);
	}

	public static function updateLowRatingFreeze($vndId, $status)
	{
		$params	 = ['vndId' => $vndId, 'status' => $status];
		$sql	 = "UPDATE vendor_pref SET vnp_low_rating_freeze=:status WHERE vnp_vnd_id=:vndId";
		$cnt	 = DBUtil::execute($sql, $params);
		return $cnt;
	}

	/**
	 * function to modify vendor home zone
	 * @param type $vndid
	 * @param type $zoneId
	 * @return type
	 */
	public static function addHomeZone($vndId, $zoneId)
	{
		$params	 = ['vndId' => $vndId, 'zoneId' => $zoneId];
		$sql	 = "UPDATE vendor_pref SET vnp_home_zone=:zoneId WHERE vnp_vnd_id=:vndId";
		$cnt	 = DBUtil::execute($sql, $params);
		return $cnt;
	}

	public static function actVndBlnkHomeZone()
	{
		$sql	 = "SELECT vnp_vnd_id, vnp_home_zone FROM vendor_pref 
			INNER JOIN app_tokens ON app_tokens.apt_entity_id = vendor_pref.vnp_vnd_id AND apt_user_type =2
			WHERE vnp_home_zone IS NULL AND DATE(apt_last_login) > (NOW() - INTERVAL 30 DAY) GROUP by vnp_vnd_id";
		$results = DBUtil::query($sql);
		return $results;
	}

	/**
	 * 
	 * @param type $data
	 * @param type $val
	 * @return type
	 */
	public static function getServiceListByValue($data, $val = 1)
	{
		$service			 = [];
		$serviceFieldList	 = self::serviceFieldList;

		foreach ($serviceFieldList as $key => $value)
		{
			if ($data[$value] == $val)
			{
				$service[] = $key;
			}
		}
		return $service;
	}

	/**
	 * 
	 * @param \Beans\vendor\Preference $preferenceRequest
	 * @param int $vndId
	 */
	public static function updatePreferenceService($preferenceRequest, $vndId)
	{
		$serviceList		 = self::serviceFieldList;
		$vnpModel			 = VendorPref::model()->getByVendorId($vndId);
		$requestedServices	 = $preferenceRequest->requestedTripType;
		$removedServices	 = $preferenceRequest->removedTripType;
		foreach ($requestedServices as $v)
		{
			$fieldName = $serviceList[$v];
			if ($fieldName && $vnpModel->$fieldName != 1)
			{
				$vnpModel->$fieldName = 0;
			}
		}
		foreach ($removedServices as $v)
		{
			$fieldName = $serviceList[$v];
			if (!$fieldName)
			{
				break;
			}
			$vnpModel->$fieldName = -1;
		}
		if (!$vnpModel->validate())
		{
			throw new Exception(json_encode($vnpModel->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
		return $vnpModel->save();
	}

	public function getGNowSnoozed()
	{

		$where		 = '';
		$dateRange	 = '';

		$sql = "SELECT vnd.vnd_id, vnd.vnd_name,vnd.vnd_code,
			contact_phone.phn_phone_no AS vnd_phone,
			vrs.vrs_last_logged_in,
			vpr.vnp_gnow_modify_time 
			from vendors v1 
			INNER JOIN vendors vnd ON vnd.vnd_id = v1.vnd_ref_code AND vnd.vnd_id = vnd.vnd_ref_code
			INNER JOIN vendor_pref vpr ON vpr.vnp_vnd_id = vnd.vnd_id
			INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd.vnd_id
			INNER JOIN contact_profile cpr ON cpr.cr_is_vendor = vnd.vnd_id
			LEFT JOIN contact_phone ON contact_phone.phn_contact_id = cpr.cr_contact_id
			AND contact_phone.phn_is_verified = 1 AND contact_phone.phn_is_primary = 1 		
			WHERE vpr.vnp_gnow_status = 0
			GROUP BY vnd.vnd_id  ";

		$params			 = [];
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'params'		 => $params,
			'totalItemCount' => $count,
			'sort'			 => [
				'attributes'	 => ['vnd_name', 'vrs_last_logged_in', 'vnp_gnow_modify_time', 'vnp_gnow_modify_time'],
				'defaultOrder'	 => 'vpr.vnp_gnow_modify_time'
			],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}
	
	
	
	public static function checkForceCounter($driverId)
	{
		
		$param	 = ['drvId' => $driverId];
		$sql =  "SELECT count(vendor_driver.vdrv_drv_id)as counter FROM `vendor_driver`  
				INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id = vendor_driver.vdrv_vnd_id AND vendor_pref.vnp_home_zone IN (394,22,46)
				WHERE vendor_driver.vdrv_drv_id =:drvId";
		$dataCount = DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
		return $dataCount;
	}

}

<?php

use components\Event\Events;
use components\Event\EventSchedule;
use components\Event\EventReceiver;
use CActiveRecord;

/**
 * This is the model class for table "booking_temp".
 *
 * The followings are the available columns in table 'booking_temp':
 * @property integer $bkg_id
 * @property string $bkg_booking_id
 * @property integer $bkg_ref_booking_id
 * @property integer $bkg_user_id
 * @property string $bkg_user_name
 * @property string $bkg_user_lname
 * @property string $bkg_pickup_date
 * @property string $bkg_pickup_time
 * @property string $bkg_return_date
 * @property string $bkg_return_time
 * @property integer $bkg_route_id
 * @property integer $bkg_booking_type
 * @property integer $bkg_transfer_type
 * @property integer $bkg_from_city_id
 * @property integer $bkg_to_city_id
 * @property string $bkg_pickup_address
 * @property string $bkg_drop_address
 * @property string $bkg_trip_distance
 * @property string $bkg_trip_duration
 * @property string $bkg_file_path
 * @property string $bkg_instruction_to_driver_vendor
 * @property string $bkg_pickup_lat
 * @property string $bkg_pickup_long
 * @property string $bkg_country_code
 * @property string $bkg_contact_no
 * @property string $bkg_alt_country_code
 * @property string $bkg_alternate_contact
 * @property string $bkg_user_email
 * @property integer $bkg_vehicle_type_id
 * @property integer $bkg_no_person
 * @property integer $bkg_driver_id
 * @property integer $bkg_vehicle_id
 * @property integer $bkg_vendor_id
 * @property string $bkg_extdriver_name
 * @property string $bkg_extdriver_contact
 * @property string $bkg_extvehicle_number
 * @property string $bkg_extvehicle_type
 * @property integer $bkg_is_approved
 * @property string $bkg_approved_date
 * @property string $bkg_user_ip
 * @property string $bkg_user_device
 * @property integer $bkg_platform
 * @property integer $bkg_amount
 * @property string $bkg_drop_date
 * @property string $bkg_drop_time
 * @property string $bkg_verification_code
 * @property integer $bkg_rate_per_km
 * @property integer $bkg_rate_per_km_extra
 * @property integer $bkg_advance
 * @property string $bkg_flight_no
 * @property string $bkg_flight_info
 * @property string $bkg_info_source
 * @property string $bkg_remark
 * @property string $bkg_promo_code
 * @property integer $bkg_discount
 * @property integer $bkg_net_charge
 * @property string $bkg_delete_reason
 * @property integer $bkg_rating
 * @property string $bkg_modified_on
 * @property string $bkg_user_last_updated_on
 * @property integer $bkg_status
 * @property integer $bkg_active
 * @property string $bkg_create_date
 * @property string $bkg_log_type
 * @property integer $bkg_lead_source
 * @property string $bkg_log_comment
 * @property string $bkg_log_phone
 * @property string $bkg_log_email
 * @property integer $bkg_follow_up_status
 * @property string $bkg_follow_up_on
 * @property integer $bkg_follow_up_by
 * @property string $bkg_follow_up_comment
 * @property string $bkg_follow_up_reminder
 * @property integer $bkg_tnc
 * @property string $bkg_tnc_time
 * @property string $bkg_locked_by
 * @property string $bkg_lock_timeout
 * @property string $bkg_assigned_to
 * @property string $bkg_user_type
 * @property integer $bkg_agent_id
 * @property integer $bkg_package_id
 * @property integer $bkg_shuttle_id
 * @property string $bkg_route_data
 * @property string $bkg_tags
 * @property integer $bkg_is_related_lead
 * @property double $bkg_igst
 * @property double $bkg_cgst
 * @property double $bkg_sgst
 * @property string $bkg_flexxi_time_slot
 * @property integer $bkg_traveller_type

 * The followings are the available model relations:
 * @property Admins $bkgFollowUpBy
 * @property Admins $bkgLockedBy
 * @property Admins $bkgAssignedTo

 * @property integer $bkg_send_email
 * @property integer $bkg_send_sms
 * @property integer $bkg_admin_id
 * @property integer $bkg_num_small_bag
 * @property integer $bkg_num_large_bag
 * @property integer $bkg_flexxi_base_amount
 * @property integer $bkg_fp_id
 * @property integer $bkg_flexxi_type
 * @property string  $bkg_last_cron_lead_followup
 * @property integer $bkg_cron_lead_followup_ctr
 * @property integer $bkg_lead_followup_link_open_cnt
 * @property string $bkg_lead_followup_link_open_first_time
 * @property integer $bkg_vht_id
 * @property integer $bkg_is_gozonow
 * @property string $bkg_partner_ref_id
 *
 * @property SvcClassVhcCat $bkgSvcClassVhcCat
 * @property BookingRoute[] $bookingRoutes
 */
class BookingTemp extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_temp';
	}

	public $requiredKMs				 = 0;
	public $booking_types			 = ['1' => 'OW', '2' => 'RT'];
	public $bkgAirport, $bkgTransferLoc, $fullContactNumber;
	public $bkg_pickup_date_date, $bkg_pickup_date_time, $bkg_create_date_show, $remindDate,
			$pickup1, $pickup2, $pickup3, $firstname, $lastname, $countrycode, $preData, $preRutData, $agentNotifyData,
			$contactnumber, $bkg_verification_code1, $bkg_follow_up_reminder_date1,
			$bkg_create_date1, $bkg_lead_status, $bkg_follow_up_status_txt, $bkg_lead_source_txt, $bkg_keyword_txt,
			$bkg_from_city_id_txt, $bkg_to_city_id_txt, $bkg_create_date2, $bkg_pickup_date1, $bkg_keyword,
			$bkg_pickup_date2, $altcountrycode, $fileImage, $new_follow_up_comment,
			$bkg_booking_duration, $bkg_return_date_date, $bkg_return_date_time,
			$bkg_follow_up_reminder_date, $bkg_follow_up_reminder_time, $trip_duration_format, $trip_distance_format,
			$bkg_base_amount, $bkg_total_amount, $bkg_discount_amount, $bkgAddonDetails, $bkgTravellBy,
			$bkg_cp_comm_type, $bkg_cp_comm_value,
			$bookingRoutes, $hash, $bktyp, $bkg_flight_chk, $brt_from_city_id, $brt_to_city_id;
	public $pickup_terms_flexxi		 = 1;
	public $pickupLat, $pickupLon, $dropLat, $dropLon, $latlonSet;
	public $locale_from_date, $locale_from_time, $locale_to_date, $locale_to_time;
	public $bkg_trvl_sendupdate, $agentBkgAmountPay, $agentCreditAmount,
			$bkg_copybooking_name, $bkg_copybooking_email, $bkg_copybooking_country,
			$bkg_copybooking_phone, $bkg_copybooking_ismail,
			$bkg_copybooking_issms,
			$bkg_trvl_email,
			$bkg_trvl_phone,
			$bkg_shuttle_seat_count,
			$bkg_trvl_isemail, $bkgGender,
			$bkg_trvl_issms, $bkg_trvl_isapp, $bkg_send_app, $bkgvhtid;
	public $bkg_gozo_base_amount, $bkg_toll_tax, $bkg_state_tax, $bkg_additional_charge,
			$bkg_advance_amount, $bkg_refund_amount, $bkg_chargeable_distance, $bkg_vendor_amount,
			$bkg_service_tax_rate, $bkg_service_tax, $bkg_due_amount, $bkg_gozo_amount, $bkg_night_pickup_included, $bkg_night_drop_included,
			$bkg_is_toll_tax_included, $bkg_is_state_tax_included, $bkg_flexxi_quick_booking = 0, $time1, $time2,
			$bkg_driver_allowance_amount, $bkg_agent_markup, $bkg_quoted_vendor_amount, $bkg_garage_time, $pickup_later_chk, $drop_later_chk, $flexxi_fs_chk, $bkg_pickup_date_date1, $bkg_pickup_date_time1, $bkg_ddbp_base_amount, $bkg_ddbp_surge_factor, $bkg_manual_base_amount, $bkg_surge_applied, $bkg_ddbp_route_flag, $bkg_regular_base_amount, $bkg_surge_differentiate_amount, $bkg_ddbp_master_flag;
	public $is_luxury_from_city, $is_luxury_to_city, $from_city, $min_nights, $max_nights, $bkg_extra_toll_tax, $bkg_member_addon_discount;
	public $booking_type_url		 = ['oneway' => '1', 'roundtrip' => '2', 'multitrip' => '3', 'airport' => '4', 'dayrental4' => '9', 'dayrental8' => '10', 'dayrental12' => '11', '9' => 'Day Rental (4hr-40km)', '10' => 'Day Rental (8hr-80km)', '11' => 'Day Rental (12hr-120km)', 'dayrental' => '9', 'localtransfer' => '15'];
	public $showAssigned			 = 0;
	public $ids						 = '0';
	public $flashBooking, $bkg_cav_id, $cavhash, $stepOver				 = 0;
	public $isNewUser				 = 0;
	public $bkg_vnd_compensation, $newBookingRoutes;
	public $additionalMarkup;
	public $isconvertedToDR			 = 0;
//public $bookingRoutes = [];
	/** @var BookingRoute[] $bookingRoutes */
//public $bookingRoutes;

	/** @var Quote $quotes */
	public $quotes;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
		return array(
////Booking Related
			['isNewUser', 'required', 'message' => 'Please select user type', 'on' => 'checkUser'],
			['bkg_from_city_id, bkg_to_city_id, bkg_booking_type, bkg_pickup_date', 'required', 'except' => 'partnerRedirected'],
			['bkg_booking_type,  bkg_vehicle_type_id, bkg_return_date, bkg_agent_id', 'required', 'on' => 'redirectedBooking'],
			['bkg_booking_type, bkg_pickup_date, bkg_agent_id', 'required', 'on' => 'partnerRedirected'],
			['bkg_pickup_date_date, bkg_pickup_date_time', 'required', 'on' => 'type1,t1,type2,validate'],
			['bkg_transfer_type', 'validateTransferType', 'on' => 'multiroute,multiroute1,agentquote'],
			['bkg_id', 'validateSpam', 'on' => 'multiroute,validateStep1'],
			['bkg_contact_no', 'validatePhone', 'on' => 'multiroute,multiroute1,validateStep1'],
			['bkg_pickup_date_date', 'validateTime', 'on' => 'multiroute,multiroute1,agentquote,step1,type1,t1,new,insert,validateStep1,redirectedBooking,partnerRedirected'],
			/////lead Realated
			array('bkg_pickup_time', 'required', 'on' => 'step1'),
			array('bkg_vehicle_type_id', 'required', 'on' => 'step2'),
			array('bkg_pickup_address', 'required', 'on' => 'step3,cabRate,dropLater'),
			array('bkg_drop_address', 'required', 'on' => 'step3,cabRate,pickLater'),
			array('bkg_user_name,bkg_user_lname', 'required', 'on' => 'step4,cabRate,later,pickLater,dropLater'),
			array('bkg_id,bkg_lock_timeout,bkg_locked_by', 'required', 'on' => 'lead_lock'),
			array('bkg_id,bkg_follow_up_status,bkg_follow_up_comment,bkg_follow_up_on', 'required', 'on' => 'update_followup'),
			array('bkg_id', 'required', 'on' => 'lead_unlock'),
			array('bkg_pickup_date_date, bkg_pickup_date_date1', 'type', 'type' => 'date', 'message' => 'Please enter valid date.', 'dateFormat' => 'dd/MM/yyyy'),
			array('bkg_pickup_date', 'type', 'type' => 'date', 'message' => 'Please enter valid date.', 'dateFormat' => 'yyyy-MM-dd HH:mm:ss'),
			array('bkg_is_related_lead', 'required', 'on' => 'updaterelated'),
			['bkg_route_data', 'validateRoute', 'on' => 'validateStep1,redirectedBooking'],
			['bkg_contact_no, bkg_alternate_contact', 'validatePhone', 'on' => 'step1'],
			['bkg_contact_no', 'validateContact'],
			['bkg_pickup_address', 'validateAddressLatLong', 'on' => 'cabRate'],
			//array('bkg_user_email', 'email', 'message' => 'Please enter valid email address', 'checkMX' => true),
			//array('bkg_user_email', 'email', 'message' => 'Please enter valid email address', 'checkMX' => true),
			array('bkg_user_email', 'validateEmail', 'on' => 'validateStep1'),
			array('bkg_from_city_id, bkg_to_city_id,bkg_pickup_date_date, bkg_pickup_date_time', 'required', 'on' => 'lead_convert'),
			array('bkg_user_name', 'CRegularExpressionValidator', 'pattern' => '/^[a-zA-Z0-9 .]*$/', 'message' => "First Name should contain only alphanumeric characters", 'allowEmpty' => false),
			array('bkg_user_lname', 'CRegularExpressionValidator', 'pattern' => '/^[a-zA-Z0-9 .]*$/', 'message' => "Last Name should contain only alphanumeric characters", 'allowEmpty' => false),
//array('bkg_user_name', 'match', 'pattern'=>'/^[\w\s,]+$/', 'message'=>'Tags can only contain word characters.'),
			array('bkg_alternate_contact,bkg_contact_no,contactnumber,bkg_user_id, bkg_route_id, bkg_booking_type, bkg_from_city_id, bkg_to_city_id,bkg_id,
                bkg_vehicle_type_id,bkg_country_code, bkg_no_person, bkg_driver_id, bkg_vehicle_id, bkg_vendor_id,bkg_platform, bkg_is_approved, bkg_amount,
                bkg_follow_up_by, bkg_locked_by, bkg_assigned_to,bkg_advance, bkg_rating, bkg_status, bkg_discount, bkg_net_charge,bkg_active,bkg_contact_no,bkg_follow_up_status,bkg_lead_source,', 'numerical', 'integerOnly' => true),
			array('bkg_booking_id, bkg_user_name, bkg_pickup_address, bkg_drop_address, bkg_user_email, bkg_extvehicle_type, bkg_user_device, bkg_info_source, bkg_remark, bkg_delete_reason', 'length', 'max' => 255),
			array('bkg_pickup_lat, bkg_pickup_long, bkg_extvehicle_number, bkg_user_ip, bkg_promo_code', 'length', 'max' => 100),
			array('bkg_trip_distance, bkg_trip_duration', 'length', 'max' => 250),
			array('bkg_return_date_date', 'checkReturnDate', 'on' => 'lead_edit, validate, lead_convert'),
			array('bkg_follow_up_status', 'checkLeadFollowupStatus', 'on' => 'lead_edit'),
			array('bkg_pickup_date1', 'checkPickupReportDate', 'on' => 'lead_report'),
			['new_follow_up_comment', 'required', 'on' => 'followup'],
			array('bkg_follow_up_reminder_date', 'checkReminderDate', 'on' => 'lead_edit,followup'),
			array('bkg_lead_source', 'required', 'on' => 'lead_create,lead_edit'),
			array('bkg_assigned_to', 'required', 'on' => 'assigncsr'),
			array('bkg_instruction_to_driver_vendor', 'length', 'max' => 250),
			array('bkg_extdriver_name, bkg_extdriver_contact', 'length', 'max' => 200),
			array('bkg_tnc', 'required', 'on' => 'tnc'),
			array('bkg_follow_up_comment', 'length', 'max' => 4000),
			array('bkg_verification_code', 'length', 'max' => 15),
			array('bkg_return_date, bkg_user_lname,bkg_pickup_date_date,bkg_pickup_date_time, bkg_user_last_updated_on, bkg_alt_country_code,bkg_create_date1,bkg_create_date2,bkg_pickup_date1,bkg_pickup_date2,
                bkg_vehicle_type_id,bkg_follow_up_reminder_date,bkg_follow_up_reminder_time,bkg_follow_up_reminder, bkg_return_time, bkg_approved_date,bkg_follow_up_status, bkg_drop_date, bkg_drop_time,bkg_keyword', 'safe'),
			array('bkg_id,bkg_return_date_date,bkg_return_date_time, bkg_booking_id,bkg_follow_up_by, bkg_locked_by, bkg_assigned_to, bkg_user_id, bkg_user_name, bkg_user_lname, bkg_pickup_date, bkg_pickup_time, bkg_return_date, bkg_return_time,
                bkg_base_amount,bkg_total_amount,bkg_discount_amount,bkg_route_data,bkg_tags,bkg_rate_per_km_extra,bkg_transfer_type,
                bkg_route_id, bkg_booking_type, bkg_from_city_id, bkg_to_city_id, bkg_pickup_address, bkg_drop_address,bkg_platform, bkg_trip_distance, bkg_trip_duration, bkg_file_path, bkg_instruction_to_driver_vendor,
                bkg_pickup_lat, bkg_pickup_long, bkg_country_code, bkg_contact_no, bkg_alt_country_code, bkg_alternate_contact, bkg_user_email, bkg_vehicle_type_id,bkg_lock_timeout,
                bkg_is_approved, bkg_approved_date, bkg_user_ip, bkg_user_device, bkg_amount, bkg_drop_date, bkg_drop_time, bkg_verification_code, bkg_rate_per_km,bkg_ref_booking_id,bkg_follow_up_reminder_date1,
                bkg_advance, bkg_info_source, bkg_remark,bkg_is_related_lead, bkg_promo_code, bkg_discount, bkg_net_charge, bkg_delete_reason, bkg_rating, bkg_modified_on, bkg_status, new_follow_up_comment,
                bkg_active, bkg_create_date,bkg_lead_source,bkg_flight_no,bkg_flight_info, bkg_log_type, bkg_log_comment, bkg_log_phone, bkg_log_email,  bkg_follow_up_by,bkg_follow_up_on, bkg_follow_up_reminder,
                bkg_tnc, bkg_tnc_time,bkg_user_country,bkg_user_city,bkg_tnc_id,bkg_user_type,bkg_lead_source_txt,bkg_follow_up_status_txt,bkg_keyword_txt,bkg_from_city_id_txt,bkg_to_city_id_txt,bkg_agent_id,
				bkg_gozo_base_amount, bkg_toll_tax, bkg_state_tax, bkg_additional_charge,bkgAirport, bkgTransferLoc,
				bkg_advance_amount, bkg_refund_amount, bkg_chargeable_distance, bkg_vendor_amount,bkg_package_id,bkg_shuttle_id,bkg_cav_id,bkg_is_gozonow,
				bkg_service_tax_rate, bkg_service_tax, bkg_due_amount, bkg_gozo_amount,bkg_create_date1,bkg_create_date2,bkg_pickup_date1,bkg_pickup_date2,
				bkg_is_toll_tax_included, bkg_is_state_tax_included,agentCreditAmount,locale_from_date, locale_from_time, locale_to_date, locale_to_time,
				bkg_driver_allowance_amount, bkg_agent_markup,bkg_garage_time, bkg_quoted_vendor_amount,bkg_send_email,bkg_send_sms,bkg_igst,bkg_cgst,bkg_sgst,bkg_admin_id,bkg_num_small_bag,bkg_num_large_bag,bkg_flexxi_base_amount,bkg_fp_id,bkg_flexxi_type,bkg_pickup_date_date1,bkg_pickup_date_time1,
				bkg_last_cron_lead_followup,bkg_cron_lead_followup_ctr,bkg_lead_followup_link_open_cnt,bkg_lead_followup_link_open_first_time,bkg_vht_id,bkg_route_data,bkg_traveller_type
                ', 'safe'),
			array('bkg_user_name, bkg_user_lname, bkg_pickup_address, bkg_drop_address, bkg_instruction_to_driver_vendor'
				, 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),
		);
	}

	public function validateRoute($attribute, $params)
	{
		$success = true;
		if ($this->scenario != "redirectedBooking")
		{
			$errors = BookingRoute::validateRoutes($this->bookingRoutes, $this->bkg_booking_type, $this->bkg_transfer_type);
		}
		$errors = array_filter($errors, function ($value) {
			return $value !== false;
		});
		if (count($errors) > 0)
		{
			$this->addError($attribute, $errors);
			$success = false;
			goto end;
		}
		$this->setRoutes($this->bookingRoutes);
		if (in_array($this->bkg_booking_type, [2, 3]) && count($this->bookingRoutes) < 2)
		{
			$this->addError($attribute, "More than 1 route is required for Multi city / round trip");
			$success = false;
		}
		else if (count($this->bookingRoutes) < 1)
		{
			$this->addError($attribute, "Atleast 1 route is required for this trip");
			$success = false;
		}
		else if ($this->bkg_booking_type == 14 && ($this->bookingRoutes[0]->from_place == '' || $this->bookingRoutes[0]->to_place == ''))
		{
			$this->addError($attribute, "Pickup and drop both locations are required");
			$success = false;
		}
		end:
		return $success;
	}

	public function checkGraceTime($gracetime = 0)
	{
		$gracetime	 = ($gracetime > 0) ? $gracetime : 0;
		$pickup_date = $this->bkg_pickup_date;
		$diff		 = floor((strtotime($pickup_date) - time()) / 60);
		$totMin		 = (4 * 60) + $gracetime;
		$hour		 = floor(($totMin) / 60);
		$min		 = $totMin % 60;
		$strTime	 = $hour . ' hour(s)' . (($min > 0) ? ' ' . $min . ' min(s)' : '');
		if ($diff < $totMin)
		{
			$this->addError('bkg_id', "Departure time should be at least $strTime hence");
			return false;
		}
		return true;
	}

	public function validateRouteTime($attribute, $params = [])
	{
		$count		 = count($this->bookingRoutes);
		$est_date1	 = '';
		for ($i = 0; $i < $count; $i++)
		{
			if ($i == 0)
			{
				$pickup_date = $this->bookingRoutes[$i]->brt_pickup_datetime;
				$diff		 = floor((strtotime($pickup_date) - time()) / 3600);
				$allowedDiff = 4;
				$allowedDiff = (($this->bkg_agent_id == 450 || $this->bkg_agent_id == 18190 )) ? 4 : $allowedDiff;
				if ($diff < $allowedDiff)
				{
					$this->addError($attribute, 'Departure time should be at least ' . $allowedDiff . ' hours hence');
					return false;
				}
				$arr						 = [];
				$arr[0]['date']				 = $this->bookingRoutes[$i]->brt_pickup_datetime;
				$arr[0]['drop_address']		 = $this->bookingRoutes[$i]->brt_to_location;
				$arr[0]['drop_city']		 = $this->bookingRoutes[$i]->brt_to_city_id;
				$arr[0]['drop_point']		 = $this->bookingRoutes[$i]->brt_to_pincode;
				$arr[0]['pickup_address']	 = $this->bookingRoutes[$i]->brt_from_location;
				$arr[0]['pickup_city']		 = $this->bookingRoutes[$i]->brt_from_city_id;
				$arr[0]['pickup_point']		 = $this->bookingRoutes[$i]->brt_from_pincode;
				$data						 = json_encode($arr);
				$data						 = json_decode($data);
				$pickupCity					 = $this->bookingRoutes[$i]->brt_from_city_id;
				$dropCity					 = $this->bookingRoutes[$i]->brt_to_city_id;
				$duration					 = Route::model()->getRouteDurationbyCities($pickupCity, $dropCity);
//				$result						 = Quotation::model()->calculateDistance($this->bkg_booking_type, $data);
				$est_date1					 = date('Y-m-d H:i:s', strtotime($this->bookingRoutes[$i]->brt_pickup_datetime . '+ ' . $duration . ' minute'));
			}
			else
			{
				$pickupCity	 = $this->bookingRoutes[$i]->brt_from_city_id;
				$dropCity	 = $this->bookingRoutes[$i]->brt_to_city_id;
				$duration	 = Route::model()->getRouteDurationbyCities($pickupCity, $dropCity);
				if ($this->bookingRoutes[$i]->brt_pickup_datetime != '')
				{
					$est_date2	 = new DateTime($this->bookingRoutes[$i]->brt_pickup_datetime);
					$est_date3	 = new DateTime($est_date1);

					if ($est_date2 < $est_date3 && $est_date2 != '')
					{
						$fromCityName	 = Cities::getName($this->bookingRoutes[$i]->brt_from_city_id);
						$toCityName		 = Cities::getName($this->bookingRoutes[$i]->brt_to_city_id);
						if ($this->bkg_booking_type == 2)
						{
							$est_date4 = date('Y-m-d H:i:s', strtotime($est_date1 . '+ ' . $duration . ' minute'));

							$message = "You are expected to arrive in $fromCityName at " . date('d/m/Y h:i A', strtotime($est_date1)) . '.';
							$message .= ' Return Time to ' . $toCityName . ' should be after ' . date('d/m/Y h:i A', strtotime($est_date4));
							$this->addError($attribute, $message);
						}
						if ($this->bkg_booking_type == 3)
						{
							$this->addError($attribute, 'Pickup Time for ' . $toCityName . ' should be after ' . date('d/m/Y h:i A', strtotime($est_date1)));
						}
						return false;
					}
				}
				$arr1						 = [];
				$arr1[0]['date']			 = $this->bookingRoutes[$i]->brt_pickup_datetime;
				$arr1[0]['drop_address']	 = $this->bookingRoutes[$i]->brt_to_location;
				$arr1[0]['drop_city']		 = $this->bookingRoutes[$i]->brt_to_city_id;
				$arr1[0]['drop_point']		 = $this->bookingRoutes[$i]->brt_to_pincode;
				$arr1[0]['pickup_address']	 = $this->bookingRoutes[$i]->brt_from_location;
				$arr1[0]['pickup_city']		 = $this->bookingRoutes[$i]->brt_from_city_id;
				$arr1[0]['pickup_point']	 = $this->bookingRoutes[$i]->brt_from_pincode;
				$data1						 = json_encode($arr1);
				$data1						 = json_decode($data1);

				$est_date1 = date('Y-m-d H:i:s', strtotime($this->bookingRoutes[$i]->brt_pickup_datetime . '+ ' . $duration . ' minute'));
			}
		}
		return true;
	}

	public function validateSpam($attribute, $params)
	{
		Logger::beginProfile("validateSpam");

		if (in_array($this->bkg_contact_no, ['7888999777', '6666554433', '9717637477', '6666554434', '7766554433', '8123456789']))
		{
			$this->addError('bkg_contact_no', 'Invalid contact');
			return false;
		}
		if (in_array($this->bkg_user_email, ['tstcrwl@gmail.com', 'tstcrwl12345@gmail.com', 'mukeshllingwal@gmail.com', 'tstcrwl123456@gmail.com', 'brajesh976165@gmail.com', 'tstcrwl1234@gmail.com', 'prakalpa78@gmail.com']))
		{
			$this->addError('bkg_user_email', 'Invalid contact');
			return false;
		}

		$validateSpam = Yii::app()->params['validateSpam'];
		if (!$validateSpam)
		{
			Logger::endProfile("validateSpam");
			return true;
		}
		$cnt = $this->checkDuplicateByIP($this->bkg_user_ip, $this->bkg_contact_no, $this->bkg_user_email);
		Logger::trace("User IP detected: " . $this->bkg_user_ip);

		$ips		 = [gethostbyname("gozotech1.ddns.net"), gethostbyname("gozotech.ddns.net")];
		$hostNames	 = Config::get('booking.checkSpam.hostName');
		if (!empty($hostNames))
		{
			$result		 = CJSON::decode($hostNames);
			$ips		 = array_merge($ips, $result);
			$arrUserIp	 = explode(',', $this->bkg_user_ip);
		}
		if (!in_array($this->bkg_user_ip, $ips) && $this->bkg_user_device == 'okhttp/3.12.0')
		{
//			$this->addError($attribute, 'Maximum request exceeded. Please contact our customer care to make booking or try again after 1 hour');
//			return false;
		}
		if ($cnt > Config::get('btemp.validateSpamCount') && !in_array($arrUserIp[0], $ips))
		{
			$this->addError($attribute, 'Maximum request exceeded. Please contact our customer care to make booking or try again after 30 minutes');
			return false;
		}
		Logger::endProfile("validateSpam");

		return TRUE;
	}

	public function checkDuplicateByIP($ip, $phone, $email)
	{
		$qry = '';
		if (trim($email) != '')
		{
			$strEmail	 = substr(trim($email), 0, 7);
			$qry		 .= " OR bkg_user_email LIKE '$strEmail%' ";
		}
		if (trim($phone) != '')
		{
			$qry .= " OR bkg_contact_no='$phone'";
		}
		$sql = "SELECT COUNT(*) as cnt FROM booking_temp
			    WHERE  ((bkg_user_ip='$ip') $qry)
                AND bkg_create_date>DATE_SUB(NOW(), INTERVAL 15 MINUTE)";

		$count = DBUtil::queryScalar($sql);

		return $count;
	}

	public function validateAddressLatLong($attribute, $params)
	{
		if ($this->pickupLat == '' || $this->pickupLon == '')
		{
			$fcityName = $this->bkgFromCity->cty_name;
			$this->addError('bkg_pickup_address', "Please enter correct pickup address for $fcityName as suggested");
			return false;
		}
		if ($this->dropLat == '' || $this->dropLon == '')
		{
			$tcityName = $this->bkgToCity->cty_name;
			$this->addError('bkg_drop_address', "Please enter correct drop address for $tcityName as suggested");
			return false;
		}
		return TRUE;
	}

	public function validatePhone($attribute, $params)
	{
		if (UserInfo::isLoggedIn() && (UserInfo::getUserType() == UserInfo::TYPE_CONSUMER || UserInfo::getUserType() == UserInfo::TYPE_AGENT))
		{
			$success = true;
		}
		else
		{
//            if (trim($this->bkg_contact_no) == '')
//            {
//                $this->addError($attribute, 'Please enter phone number');
//                $success = false;
//            }
			$success = true;
			if ($this->bkg_alternate_contact != '')
			{
				if (!Filter::validatePhoneNumber("+" . $this->bkg_alt_country_code . $this->bkg_alternate_contact))
				{
					$this->addError('bkg_alternate_contact', 'Please enter valid alternate phone number');
					$success = false;
				}
			}
		}
		if ($this->bkg_contact_no != '')
		{
			if (!Filter::validatePhoneNumber("+" . $this->bkg_country_code . $this->bkg_contact_no))
			{
				$this->addError('bkg_contact_no', 'Please enter valid phone number');
				$success = false;
			}
		}
		return $success;
	}

	public function validateAirportPickup($attribute, $params)
	{
		if ($this->bkg_booking_type == 4 && $this->bkg_flight_no == '')
		{
			$this->addError($attribute, 'Flight no is needed for Airport transfer');
			return FALSE;
		}
		return true;
	}

	public function validateTransferType($attribute, $params)
	{
		if ($this->bkg_booking_type == 4 && !$this->bkg_transfer_type)
		{
			$this->addError($attribute, 'Select a transfer type');
			return FALSE;
		}
		return true;
	}

	public function validateTime($attribute, $params)
	{
		if ($this->bkg_pickup_date != '')
		{

			$timeNow = time();

			//$testTime	 = '2023-06-30 07:30:00'; //delete after test
			//$timeNow	 = strtotime($testTime); //delete after test

			$diff = floor((strtotime($this->bkg_pickup_date) - $timeNow) / 60);

//			$response = self::checkTime($this);
			$response = $this->checkGozoNowEligibility();
			if ($response->isAllowed && $diff >= 2)
			{
				goto skipCheck;
			}
//$response->pickDiff 
			if ($diff <= $response->timeDifference || !$response->isAllowed)
			{

				if ($this->bkg_booking_type == 14 && $diff > Config::getMaxPickupTime($this->bkg_booking_type))
				{
					$this->addError($attribute, 'Departure time should be within ' . (Config::getMaxPickupTime($this->bkg_booking_type)) / 60 . ' hour from now.');
					return false;
				}
				$message = 'Departure time should be at least ' . $response->timeDifference . ' minutes from now.';
				if ($response->message != "")
				{
					$message = $response->message;
				}
				$this->addError($attribute, $message);
				return false;
			}

			skipCheck:
			$maxTime		 = Config::getMaxPickupTime($this->bkg_booking_type);
			$maxTimestamp	 = strtotime("+{$maxTime} minute");
			$maxPickDate	 = date('Y-m-d H:00:00', $maxTimestamp);
			$d1				 = new DateTime($this->bkg_pickup_date);
			$d2				 = new DateTime($maxPickDate);
			if ($d1 > $d2)
			{
				$maxPickDate = date('d-M-Y H:00:00', $maxTimestamp);

				$this->addError($attribute, 'Departure time should be before ' . $maxPickDate);
				return false;
			}
		}
		return true;
	}

	/**
	 * This function helps to determine the time difference of create and pickup booking
	 * @param BookingRoute $model
	 * @return int
	 */
	public static function checkTime($model)
	{
		$tier = 0;
		if ($model->bkg_vehicle_type_id > 0)
		{
			$svcModel	 = SvcClassVhcCat::model()->findByPk($model->bkg_vehicle_type_id);
			$tier		 = $svcModel->scv_scc_id;
		}
		$minTime					 = Config::getMinPickupDuration($model->bkg_agent_id, $model->bkg_booking_type, $tier);
		$response					 = new stdClass();
		$response->timeDifference	 = $minTime;

		$timeNow = time();

		//$testTime	 = '2023-06-30 07:30:00'; //delete after test
		//$timeNow	 = strtotime($testTime); //delete after test

		$diff				 = floor((strtotime($model->bkg_pickup_date) - $timeNow) / 60);
		$response->pickDiff	 = $diff;
		if ($model->bkg_booking_type == 7)
		{
			$response->timeDifference = 12 * 60;
		}
		if (UserInfo::getUserType() == UserInfo::TYPE_ADMIN)
		{
			$response->timeDifference = 60;
		}
		$response->isAllowed = true;
		if ($diff < $response->timeDifference)
		{
			$response->isAllowed = false;
		}

		return $response;
	}

	public function setContactNumber($phone)
	{
		try
		{
			$isValid = Filter::validatePhoneNumber($phone);
			if (!$isValid)
			{
				return false;
			}
			Filter::parsePhoneNumber($phone, $code, $number);
			$this->bkg_contact_no	 = $number;
			$this->bkg_country_code	 = $code;
			return true;
		}
		catch (Exception $exc)
		{
			Logger::exception($exc);
		}
		return false;
	}

	public function checkGozoNowEligibility()
	{
		$this->bkg_is_gozonow				 = 0;
		$response							 = $this->checkTime($this);
//		$response->maxGNowAllowedDuration	 = 6 * 60;
		$response->maxGNowAllowedDuration	 = 3 * 60;

		$timeNow = time();

		//$testTime	 = '2023-06-30 07:30:00'; //delete after test
		//$timeNow	 = strtotime($testTime); //delete after test


		$fromDate = new CDbExpression('NOW()');

		//$fromDate	 = $testTime; //delete after test
		$toDate = $this->bkg_pickup_date;

		$diff			 = floor((strtotime($this->bkg_pickup_date) - $timeNow) / 60);
		/* @var $zoneCategory TopZoneRoutes */
		$zoneCategory	 = TopZoneRoutes::getCategory($this->bkg_from_city_id, $this->bkg_to_city_id);
		if ($zoneCategory === '0')
		{
			$response->maxGNowAllowedDuration = 2 * 60;
		}
		$data = json_decode(Config::get("cabcategory.booking.pickup.min.duration"), true);
		if ($data[$this->bkg_vehicle_type_id] > 0)
		{
			$response->timeDifference = $data[$this->bkg_vehicle_type_id];
			if ($diff <= $response->timeDifference)
			{
				$response->isAllowed = 0;
				$hours				 = floor($response->timeDifference / 60);
				$response->message	 = 'Departure time should be at least ' . $hours . ' hours from now for tempo traveller';
			}
			return $response;
		}


		$isB2C = ($this->bkg_agent_id == null || $this->bkg_agent_id == 1249 );

		$minWorkingMinutesForGNowAllowable = ($this->bkg_booking_type == 4 || $this->bkg_booking_type == 14 ) ? 30 : 60;

		$workingMinutesDiff = Filter::CalcWorkingMinutes($fromDate, $toDate);

		$isGNowAllowedForWorkingHour = ($workingMinutesDiff >= $minWorkingMinutesForGNowAllowable);

		if ($response->isAllowed && (($response->pickDiff > $response->maxGNowAllowedDuration && $isGNowAllowedForWorkingHour ) || !$isB2C))
		{
			$response->isAllowed = ($this->bkg_booking_type == 14 ) ? false : $response->isAllowed;
			return $response;
		}

		$checkGozoNowEnabled = Config::checkGozoNowEnabled();

		$isApplicableBkgType = true; //(in_array($this->bkg_booking_type, [1, 4, 9, 10, 11]));
		$isApplicableZone	 = true;

		if ($checkGozoNowEnabled && $this->bkg_agent_id == '' && $isApplicableBkgType && $isApplicableZone)
		{
			if ($this->bkg_vehicle_type_id > 0)
			{
				$svcModel	 = SvcClassVhcCat::model()->findByPk($this->bkg_vehicle_type_id);
				$tier		 = $svcModel->scv_scc_id;
			}
			$gzminTime = Config::getMinGozoNowPickupDuration($this->bkg_booking_type, $tier, UserInfo::getUserType());

			$isAirportPickup			 = Cities::checkAirport($this->bkg_from_city_id);
			$minAirportPickupDuration	 = 15;
			$gzminTime					 = ($isAirportPickup) ? $minAirportPickupDuration : $gzminTime;
			$response->timeDifference	 = $gzminTime;
			if ($gzminTime <= $diff || $isGNowAllowedForWorkingHour)
			{
				$response->isAllowed	 = true;
				$this->bkg_is_gozonow	 = 1;
			}
			$allowedCityList = CJSON::decode(Config::get("isAllowed.cities"));
			$isFromCity		 = in_array($this->bkg_from_city_id, $allowedCityList);
			if($isFromCity == true && in_array($this->bkg_booking_type,[1,4,12]) && $gzminTime <= $diff && 60 <= $workingMinutesDiff)
			{
				$response->isAllowed	 = true;
				$this->bkg_is_gozonow	 = 0;
			}
		}
		return $response;
	}

	public function validateEmailPhone($attribute, $params)
	{
		if (UserInfo::isLoggedIn() && (UserInfo::getUserType() == UserInfo::TYPE_CONSUMER || UserInfo::getUserType() == UserInfo::TYPE_AGENT))
		{
			$success = true;
		}
		else
		{
			if (trim($this->bkg_user_email) == '')
			{
				$this->addError($attribute, 'Please enter email address');
				$success = false;
			}
			if (trim($this->bkg_user_email) != '')
			{
				$isValidate = Filter::validateEmail(trim($this->bkg_user_email));
				if (!$isValidate)
				{
					$this->addError($attribute, 'Please enter valid email address');
					$success = false;
				}
			}

			if ($this->bkg_contact_no != '')
			{
				if (!Filter::validatePhoneNumber("+" . $this->bkg_country_code . $this->bkg_contact_no))
				{
					$this->addError('bkg_contact_no', 'Please enter valid phone number');
					$success = false;
				}
			}
			if ($this->bkg_alternate_contact != '')
			{
				if (!Filter::validatePhoneNumber("+" . $this->bkg_alt_country_code . $this->bkg_alternate_contact))
				{
					$this->addError('bkg_alternate_contact', 'Please enter valid alternate phone number');
					$success = false;
				}
			}
		}
		return $success;
	}

	public function validateEmail($attribute, $params)
	{
		if (UserInfo::isLoggedIn() && (UserInfo::getUserType() == UserInfo::TYPE_CONSUMER || UserInfo::getUserType() == UserInfo::TYPE_AGENT))
		{
			$success = true;
		}
//		else
//		{
//			if (trim($this->bkg_user_email) == '')
//			{
//				$this->addError($attribute, 'Please enter email address');
//				$success = false;
//			}
//		}
		if (trim($this->bkg_user_email) != '')
		{
			$isValidate = Filter::validateEmail(trim($this->bkg_user_email));
			if (!$isValidate)
			{
				$this->addError($attribute, 'Please enter valid email address');
				$success = false;
			}
			else
			{
				$success = true;
			}
		}
		return $success;
	}

	public function validateContact($attribute, $params)
	{
//		if (trim($this->bkg_contact_no) == '' && trim($this->bkg_user_email) == '')
//		{
//			$this->addError($attribute, 'Phone/Email is required');
//			return false;
//		}
		return true;
	}

	public function checkLeadFollowupStatus($attribute, $params)
	{
		if ($this->bkg_follow_up_status > 3)
		{
			return true;
		}
		else
		{
			if ($this->bkg_from_city_id == '')
			{
				$this->addError('bkg_from_city_id', 'From city cannot be blank');
				return false;
			}
			if ($this->bkg_to_city_id == '')
			{
				$this->addError('bkg_to_city_id', 'To city cannot be blank');
				return false;
			}
			if ($this->bkg_pickup_date_date == '')
			{
				$this->addError('bkg_pickup_date_date', 'Pickup Date cannot be blank');
				return false;
			}
			if ($this->bkg_pickup_date_time == '')
			{
				$this->addError('bkg_pickup_date_time', 'Pickup Time cannot be blank');
				return false;
			}
			if ($this->new_follow_up_comment == '')
			{
				$this->addError('new_follow_up_comment', 'Comment cannot be blank');
				return false;
			}
			if ($this->bkg_lead_source == '')
			{
				$this->addError('bkg_lead_source', 'Source cannot be blank');
				return false;
			}
			if ($this->bkg_follow_up_status == 0)
			{
				$this->addError('bkg_follow_up_status', 'Follow up status needs to be changed.');
				return false;
			}
		}
		return true;
	}

	public function checkReturnDate($attribute, $params)
	{
		if ($this->bkg_booking_type == 2)
		{
			$date		 = DateTimeFormat::DatePickerToDate($this->bkg_pickup_date_date);
			$time		 = date('H:i:00', strtotime($this->bkg_pickup_date_time));
			$pickupDate	 = $date . ' ' . $time;
			$date1		 = DateTimeFormat::DatePickerToDate($this->bkg_return_date_date);
			$time1		 = date('H:i:00', strtotime($this->bkg_return_date_time));
			$returnDate	 = $date1 . ' ' . $time1;
			if ($this->bkg_return_date_date == '')
			{
				$this->addError($attribute, 'Please enter return date');
				return FALSE;
			}
			else if ($pickupDate >= $returnDate)
			{
				$this->addError($attribute, 'Return date cannot be earlier than Pickup date');
				return FALSE;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return true;
		}
	}

	public function checkReminderDate($attribute, $params)
	{
		if ($this->bkg_follow_up_reminder_date != '' && $this->bkg_follow_up_reminder_time != '')
		{
			$rDate = DateTimeFormat::DatePickerToDate($this->bkg_follow_up_reminder_date);
			if ($this->bkg_follow_up_reminder_time != null)
			{
				$time = DateTime::createFromFormat('h:i A', $this->bkg_follow_up_reminder_time)->format('H:i:00');
			}
			$reminderDate	 = $rDate . " " . $time;
			$today			 = date("Y-m-d H:i:s");

			if ($today > $reminderDate)
			{
				$this->addError($attribute, 'Set valid reminder date');
				return FALSE;
			}
		}
		return true;
	}

	public function checkPickupReportDate($attribute, $params)
	{
		if ($this->bkg_pickup_date2 != '')
		{
			$datefrom	 = DateTimeFormat::DatePickerToDate($this->bkg_pickup_date1);
			$dateto		 = DateTimeFormat::DatePickerToDate($this->bkg_pickup_date2);

			if ($datefrom > $dateto)
			{
				$this->addError($attribute, 'Pickup to date is earlier than pickup from date');
				return FALSE;
			}
		}
		return true;
	}

	public function checkPickupTime($attribute, $params)
	{

		$start_time	 = strtotime($this->bkg_pickup_date);
		$end_time	 = strtotime(date('Y-m-d H:i:s'));
		$difference	 = $start_time - $end_time;
		$diff_day	 = floor($difference / (3600 * 24));
		if ($diff_day < 2 && $this->bkg_contact_no == '')
		{
			$this->addError($attribute, 'Please provide contact number');
			return FALSE;
		}
		else if ($this->bkg_contact_no == '' && $this->bkg_user_email == '')
		{
			$this->addError($attribute, 'Please provide contact number or email address');
			return FALSE;
		}
		else
		{
			return true;
		}
	}

	public function getRoutes()
	{
		$this->bookingRoutes = [];

		if (trim($this->bkg_route_data) == '')
		{
			goto result;
		}
		try
		{
			$arrRouteModels = CJSON::decode($this->bkg_route_data);

			foreach ($arrRouteModels as $routeArray)
			{
				$routeModel								 = new BookingRoute();
				$routeModel->attributes					 = $routeArray;
				$routeModel->brt_from_is_airport		 = $routeArray['brt_from_is_airport'];
				$routeModel->brt_to_is_airport			 = $routeArray['brt_to_is_airport'];
				$routeModel->brt_from_place_id			 = $routeArray['brt_from_place_id'];
				$routeModel->brt_to_place_id			 = $routeArray['brt_to_place_id'];
				$routeModel->brt_from_formatted_address	 = $routeArray['brt_from_formatted_address'];
				$routeModel->brt_to_formatted_address	 = $routeArray['brt_to_formatted_address'];
				$routeModel->brt_from_location_cpy		 = $routeArray['brt_from_location_cpy'];
				$routeModel->brt_to_location_cpy		 = $routeArray['brt_to_location_cpy'];
				$routeModel->decodeAttributes();
				$this->getCityDetails($routeModel);
				$this->bookingRoutes[]					 = $routeModel;
			}
		}
		catch (Exception $ex)
		{
			
		}
		if (count($this->bookingRoutes) == 0)
		{
			$this->bookingRoutes[] = new BookingRoute();
		}
		result:
		return $this->bookingRoutes;
	}

	public function updateRoutes($routes)
	{
		$routeModels	 = $this->getRoutes();
		$arrRouteModels	 = CJSON::decode($this->bkg_route_data, true);
		$nxtFromLat		 = '';
		$nxtFromLong	 = '';
		$nxtFromLoc		 = '';
		foreach ($routes as $key => $value)
		{
			$routeModels[$key]->attributes				 = $value;
			$arrRouteModels[$key]['brt_from_location']	 = $value['brt_from_location'] != '' ? $value['brt_from_location'] : $nxtFromLoc;
			$arrRouteModels[$key]['brt_to_location']	 = $value['brt_to_location'];
			$arrRouteModels[$key]['brt_from_latitude']	 = $value['brt_from_latitude'] != '' ? $value['brt_from_latitude'] : $nxtFromLat;
			$arrRouteModels[$key]['brt_from_longitude']	 = $value['brt_from_longitude'] != '' ? $value['brt_from_longitude'] : $nxtFromLong;
			$arrRouteModels[$key]['brt_to_latitude']	 = $value['brt_to_latitude'];
			$arrRouteModels[$key]['brt_to_longitude']	 = $value['brt_to_longitude'];
			$nxtFromLat									 = $value['brt_to_latitude'];
			$nxtFromLong								 = $value['brt_to_longitude'];
			$nxtFromLoc									 = $value['brt_to_location'];
			if (!in_array($this->bkg_booking_type, [4, 7]))
			{
				if ($arrRouteModels[$key]['brt_from_latitude'] == '' && $arrRouteModels[$key]['brt_from_longitude'] == '')
				{
					$ctyModel									 = Cities::model()->findByPk($arrRouteModels[$key]['brt_from_city_id']);
					$arrRouteModels[$key]['brt_from_latitude']	 = $ctyModel->cty_lat;
					$arrRouteModels[$key]['brt_from_longitude']	 = $ctyModel->cty_long;
					$arrRouteModels[$key]['brt_from_location']	 = $ctyModel->cty_garage_address;
				}
				if ($nxtFromLat == '' && $nxtFromLong == '')
				{
					$ctyModel									 = Cities::model()->findByPk($arrRouteModels[$key]['brt_to_city_id']);
					$nxtFromLat									 = $arrRouteModels[$key]['brt_to_latitude']	 = $ctyModel->cty_lat;
					$nxtFromLong								 = $arrRouteModels[$key]['brt_to_longitude']	 = $ctyModel->cty_long;
					$nxtFromLoc									 = $arrRouteModels[$key]['brt_to_location']	 = $ctyModel->cty_garage_address;
				}
			}
			if ($this->bkg_booking_type == 7)
			{
				$arrRouteModels[$key]['brt_pickup_datetime'] = $value['brt_pickup_datetime'];
			}
			$arrRouteModels[$key]['brt_trip_distance']	 = '';
			$arrRouteModels[$key]						 = $arrRouteModels[$key] + $value;
		}
		$this->setRoutes($arrRouteModels);
	}

	public function setRoutes($params)
	{
		$this->bookingRoutes = [];
		if (!is_array($params))
		{
			goto end;
		}

		foreach ($params as $routeArray)
		{
			$route = $routeArray;
			if ($routeArray instanceof BookingRoute)
			{
				$routeModel = $routeArray;
				goto skipLoad;
			}

			$routeModel				 = new BookingRoute();
			$extValue				 = [];
			$routeModel->attributes	 = $route;
			if ($routeModel->brt_from_latitude == '' && $routeModel->brt_from_longitude == '')
			{
				$ctyModel						 = Cities::model()->findByPk($routeModel->brt_from_city_id);
				$routeModel->brt_from_latitude	 = $ctyModel->cty_lat;
				$routeModel->brt_from_longitude	 = $ctyModel->cty_long;
				$routeModel->brt_from_location	 = $ctyModel->cty_garage_address;
			}
			if ($routeModel->brt_to_latitude == '' && $routeModel->brt_to_longitude == '')
			{
				$ctyModel						 = Cities::model()->findByPk($routeModel->brt_to_city_id);
				$routeModel->brt_to_latitude	 = $ctyModel->cty_lat;
				$routeModel->brt_to_longitude	 = $ctyModel->cty_long;
				$routeModel->brt_to_location	 = $ctyModel->cty_garage_address;
			}
			if (in_array($this->bkg_booking_type, [9, 10, 11, 14]))
			{
				$routeModel->brt_to_city_id = $routeModel->brt_from_city_id;
			}
			if ((isset($route['brt_from_is_airport']) && $route['brt_from_is_airport'] > 0) || (isset($route['brt_to_is_airport']) && $route['brt_to_is_airport'] > 0))
			{
				$extValue = ['brt_from_is_airport' => $route['brt_from_is_airport'], 'brt_to_is_airport' => $route['brt_to_is_airport']];
			}
			if ((isset($route['brt_from_place_id']) && $route['brt_from_place_id'] != '') || (isset($route['brt_to_place_id']) && $route['brt_to_place_id'] != ''))
			{
				$extValue += ['brt_to_place_id' => $route['brt_to_place_id'], 'brt_from_place_id' => $route['brt_from_place_id']];
			}
			if ((isset($route['brt_from_formatted_address']) && $route['brt_from_formatted_address'] != '') || (isset($route['brt_to_formatted_address']) && $route['brt_to_formatted_address'] != ''))
			{
				$extValue += ['brt_from_formatted_address' => $route['brt_from_formatted_address'], 'brt_to_formatted_address' => $route['brt_to_formatted_address']];
			}
			if ((isset($route['brt_from_location_cpy']) && $route['brt_from_location_cpy'] != ''))
			{
				$extValue += ['brt_from_location_cpy' => $route['brt_from_location_cpy']];
			}
			if ((isset($route['brt_to_location_cpy']) && $route['brt_to_location_cpy'] != ''))
			{
				$extValue += ['brt_to_location_cpy' => $route['brt_to_location_cpy']];
			}
			$routeModel->bkg_ext_route_data = $extValue;
			skipLoad:
			$routeModel->encodeAttributes();

			$this->bookingRoutes[] = $routeModel;
		}

		$this->bkg_route_data = CJSON::encode(array_map(function ($value) {
							$attributes	 = $value->attributes + $value->bkg_ext_route_data;
							$attributes	 = array_filter($attributes, function ($val) {
								return ($val !== null);
							});
							return $attributes;
						}, $this->bookingRoutes
		));

		end:

		return $this->bookingRoutes;
	}

	public function swapRouteForAirportTransfer($routes)
	{
		$newRoutes	 = $routes;
		$isArr		 = 1;
		if ($this->bkg_transfer_type == 2)
		{
			foreach ($newRoutes as $key => $routeArray)
			{
				if ($routeArray instanceof BookingRoute)
				{
					$isArr = 0;
					goto skipLoad;
				}
				$routes[$key]['brt_from_city_id']			 = $routeArray['brt_to_city_id'];
				$routes[$key]['brt_from_location']			 = $routeArray['brt_to_location'];
				$routes[$key]['brt_from_latitude']			 = $routeArray['brt_to_latitude'];
				$routes[$key]['brt_from_longitude']			 = $routeArray['brt_to_longitude'];
				$routes[$key]['brt_from_place_id']			 = $routeArray['brt_to_place_id'];
				$routes[$key]['brt_from_formatted_address']	 = $routeArray['brt_to_formatted_address'];
				$routes[$key]['brt_from_is_airport']		 = $routeArray['brt_to_is_airport'];
				$routes[$key]['brt_from_location_cpy']		 = $routeArray['brt_to_location_cpy'];

				$routes[$key]['brt_to_city_id']				 = $routeArray['brt_from_city_id'];
				$routes[$key]['brt_to_location']			 = $routeArray['brt_from_location'];
				$routes[$key]['brt_to_latitude']			 = $routeArray['brt_from_latitude'];
				$routes[$key]['brt_to_longitude']			 = $routeArray['brt_from_longitude'];
				$routes[$key]['brt_to_place_id']			 = $routeArray['brt_from_place_id'];
				$routes[$key]['brt_to_formatted_address']	 = $routeArray['brt_from_formatted_address'];
				$routes[$key]['brt_to_is_airport']			 = $routeArray['brt_from_is_airport'];
				$routes[$key]['brt_to_location_cpy']		 = $routeArray['brt_from_location_cpy'];

				goto end;

				skipLoad:

				$routeModel				 = new BookingRoute();
				$routeModel->attributes	 = $routeArray->attributes;

				$routeModel->brt_from_city_id			 = $routeArray->brt_to_city_id;
				$routeModel->brt_from_location			 = $routeArray->brt_to_location;
				$routeModel->brt_from_latitude			 = $routeArray->brt_to_latitude;
				$routeModel->brt_from_longitude			 = $routeArray->brt_to_longitude;
				$routeModel->brt_from_place_id			 = $routeArray->brt_to_place_id;
				$routeModel->brt_from_formatted_address	 = $routeArray->brt_to_formatted_address;
				$routeModel->brt_from_is_airport		 = $routeArray->brt_to_is_airport;

				$routeModel->brt_to_city_id				 = $routeArray->brt_from_city_id;
				$routeModel->brt_to_location			 = $routeArray->brt_from_location;
				$routeModel->brt_to_latitude			 = $routeArray->brt_from_latitude;
				$routeModel->brt_to_longitude			 = $routeArray->brt_from_longitude;
				$routeModel->brt_to_place_id			 = $routeArray->brt_from_place_id;
				$routeModel->brt_to_formatted_address	 = $routeArray->brt_from_formatted_address;
				$routeModel->brt_to_is_airport			 = $routeArray->brt_from_is_airport;
				$routeModel->decodeAttributes();
				$this->getCityDetails($routeModel);

				$routeModels[] = $routeModel;
			}
		}
		end:
		return $isArr == 1 ? $routes : $routeModels;
	}

	public function createLead()
	{
		$this->save();
		if ($this->bkg_booking_id == '')
		{
			$this->bkg_booking_id = $this->generateBookingid($this);
		}
		$this->save(false);
	}

	public static function markDuplicateLead($leadId)
	{
		try
		{
			$model		 = BookingTemp::model()->findByPk($leadId);
			$userId		 = $model->bkg_user_id;
			$contactNo	 = $model->bkg_contact_no;
			$email		 = $model->bkg_user_email;
			$sql		 = "UPDATE booking_temp
							SET bkg_follow_up_status=14, bkg_is_related_lead=1
							WHERE bkg_id<>'{$leadId}' AND ((bkg_contact_no='{$contactNo}' AND bkg_contact_no<>'') 
								OR (bkg_user_email='{$email}' AND bkg_user_email<>'')
								OR (bkg_user_id='{$userId}' AND bkg_user_id<>''))
								AND bkg_create_date>=DATE_SUB(NOW(), INTERVAL 12 HOUR)
								AND (bkg_pickup_date BETWEEN DATE_SUB('{$model->bkg_pickup_date}', INTERVAL 12 HOUR) AND DATE_ADD('{$model->bkg_pickup_date}', INTERVAL 12 HOUR))
								AND bkg_follow_up_status IN (0, 1, 2, 3)";
			DBUtil::execute($sql);
		}
		catch (Exception $e)
		{
			
		}
		return true;
	}

	public function createLeadAndGetQuotes($isAllowed = false)
	{
// BookingTemp
		$savePBF = true;
		if ($this->bkg_id > 0)
		{
			$savePBF = false;
		}

		$this->createLead();
		BookingTemp::markDuplicateLead($this->bkg_id);
		BookingTemp::stopAutoAssignDuplicateQuote($this->bkg_id);
// Quote
		$quotes = $this->getQuote(null, false, $isAllowed);

// PrebookingPriceFactor
		if (!$savePBF)
		{
			goto end;
		}

		if ($this->bkg_vehicle_type_id > 0)
		{
			$lastQuote = $quotes[$this->bkg_vehicle_type_id];
		}

		if (!$lastQuote)
		{
			$lastQuote = end($quotes);
		}
		if ($lastQuote)
		{
			$pbpfModel = new PrebookingPriceFactor();
			$pbpfModel->updatePreQuote($lastQuote, $this->bkg_booking_id);
		}
		end:
		return $quotes;
	}

	public function confirmLead($bkgModel)
	{
		$this->bkg_ref_booking_id	 = $bkgModel->bkg_id;
		$this->bkg_return_date		 = $bkgModel->bkg_return_date;
		$this->bkg_status			 = 13;
		$this->bkg_lead_source		 = 8; //'Incomplete booking',
		$this->bkg_follow_up_status	 = 13;
		return $this->save();
	}

	/**
	 * @deprecated deprecated since 22 oct 2019 by chiranjit hazra
	 */
	public function setAirportTransfer($bkgModel, &$brtModel)
	{

		if ($bkgModel->bkg_transfer_type > 0 && $bkgModel->bkg_booking_type == 4)
		{
			if ($bkgModel->bkgAirport > 0 && $bkgModel->bkgTransferLoc > 0)
			{
				if ($bkgModel->bkg_transfer_type == 1)
				{
					$brtModel->brt_from_city_id	 = $bkgModel->bkgAirport;
					$brtModel->brt_to_city_id	 = $bkgModel->bkgTransferLoc;
					$bkgModel->bkg_from_city_id	 = $bkgModel->bkgAirport;
					$bkgModel->bkg_to_city_id	 = $bkgModel->bkgTransferLoc;
				}
				if ($bkgModel->bkg_transfer_type == 2)
				{
					$brtModel->brt_from_city_id	 = $bkgModel->bkgTransferLoc;
					$brtModel->brt_to_city_id	 = $bkgModel->bkgAirport;
					$bkgModel->bkg_from_city_id	 = $bkgModel->bkgTransferLoc;
					$bkgModel->bkg_to_city_id	 = $bkgModel->bkgAirport;
				}
			}
		}
	}

	public function getPackageQuotes($pckid, $packName, $cabType)
	{
		$pickupDateTime				 = $this->bkg_pickup_date;
		$routes						 = BookingRoute::model()->populateRouteByPackageId($pckid, $pickupDateTime);
		$quote						 = new Quote();
		$quote->routes				 = $routes;
		$quote->quoteDate			 = $this->bkg_create_date;
		$quote->pickupDate			 = $this->bkg_pickup_date;
		$quote->tripType			 = 5;
		$quote->packageID			 = $pckid;
		$quote->packageName			 = $packName;
		$quote->rateAddedPackageOnly = true;
		$quote->applyPromo			 = false;
		$partnerId					 = Yii::app()->params['gozoChannelPartnerId'];
		$quote->setCabTypeArr();
		$quote->partnerId			 = ($this->bkg_agent_id == null) ? $partnerId : $this->bkg_agent_id;
		$quotes						 = $quote->getQuote($cabType, true, true, false);

		return $quotes;
	}

	public function getQuote($cabType = null, $checkBestRate = false, $isAllowed = false)
	{
		$quote					 = new Quote();
		$quote->routes			 = $this->bookingRoutes;
		$quote->quoteDate		 = $this->bkg_create_date;
		$quote->pickupDate		 = $this->bkg_pickup_date;
		$quote->sourceQuotation	 = $this->bkg_platform;
		$quote->tripType		 = $this->bkg_booking_type;
		$quote->partnerId		 = $this->bkg_agent_id;
		$quote->flexxi_type		 = 1;
		if ($this->bkg_booking_type == 5)
		{
			$quote->packageID = $this->bkg_package_id;
		}

		$quote->applyPromo = false;
		$quote->setCabTypeArr();
		if ($this->bkg_platform == 1)
		{
			$quote->applyPromo = true;
		}
		if ($this->bkg_is_gozonow == 1)
		{
			$quote->gozoNow		 = true;
			$quote->catypeArr	 = SvcClassVhcCat::getCabListGNowQuote();
		}

		$partnerId			 = Yii::app()->params['gozoChannelPartnerId'];
		$quote->partnerId	 = ($this->bkg_agent_id == null) ? $partnerId : $this->bkg_agent_id;

		$quotes			 = $quote->getQuote($cabType, true, true, $checkBestRate, $isAllowed);
		$this->quotes	 = $quote;
		return $quotes;
	}

	public function populateFromQuote()
	{
		//$carType = VehicleTypes::model()->getVehicleTypeById($this->bkg_vehicle_type_id);
		$carType = $this->bkg_vehicle_type_id;
		$quotes	 = $this->getQuote($carType);

		$quote								 = $quotes[$carType];
		$routeRates							 = $quote->routeRates;
		$this->bkg_base_amount				 = $routeRates->baseAmount;
		$this->bkg_driver_allowance_amount	 = $routeRates->driverAllowance;
		$this->bkg_toll_tax					 = $routeRates->tollTaxAmount;
		$this->bkg_state_tax				 = $routeRates->stateTax;
		$this->bkg_total_amount				 = $routeRates->totalAmount;
		$this->bkg_service_tax				 = $routeRates->gst;
		$this->bkg_is_toll_tax_included		 = $routeRates->isTollIncluded;
		$this->bkg_is_state_tax_included	 = $routeRates->isStateTaxIncluded;
		$this->bkg_trip_distance			 = $quote->routeDistance->tripDistance;
		$this->bkg_trip_duration			 = $quote->routeDuration->tripDuration;
	}

	public function saveQuote()
	{
		$this->populateFromQuote();
		$this->save();
	}

	/**
	 *
	 * @param Booking $model
	 * @param array $userInfo
	 * @param integer $leadPhone
	 * @param string $leadEmail
	 * @return boolean
	 * @throws Exception
	 */
	public static function createLeadModel($model, $userInfo, $leadPhone, $leadEmail, $leadPhoneCode = 91, $platformType = NULL)
	{
		$success	 = false;
		$routes		 = $leadRoutes	 = [];
		foreach ($model->bookingRoutes as $val)
		{
			$routeModel						 = new BookingRoute();
			$routeModel->brt_from_city_id	 = $val->brt_from_city_id;
			$routeModel->brt_to_city_id		 = $val->brt_to_city_id;
			$routeModel->brt_from_location	 = $val->brt_from_location;
			$routeModel->brt_to_location	 = $val->brt_to_location;
			$routeModel->brt_from_pincode	 = $val->brt_from_pincode;
			$routeModel->brt_to_pincode		 = $val->brt_to_pincode;

			$routeModel->brt_from_latitude	 = $val->brt_from_latitude;
			$routeModel->brt_from_longitude	 = $val->brt_from_longitude;
			$routeModel->brt_to_latitude	 = $val->brt_to_latitude;
			$routeModel->brt_to_longitude	 = $val->brt_to_longitude;

			$routeModel->brt_pickup_datetime	 = $val->brt_pickup_datetime;
			$routeModel->brt_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker($routeModel->brt_pickup_datetime);
			$routeModel->brt_pickup_date_time	 = date('h:i A', strtotime($routeModel->brt_pickup_datetime));

			$routes[]		 = $routeModel;
			$leadRoutes[]	 = array_filter($routeModel->attributes);
		}
		if ($model->bkg_booking_type == 5 && $model->bkg_package_id > 0)
		{
			$model->bkg_pickup_date	 = $model->bkg_pickup_date . " " . Yii::app()->params['defaultPackagePickupTime'];
			$routes					 = BookingRoute::model()->populateRouteByPackageId($model->bkg_package_id, $model->bkg_pickup_date);
			$model->bookingRoutes	 = $routes;
		}

		$cityinfo							 = UserLog::model()->getCitynCountrycodefromIP(\Filter::getUserIP());
		$tempModel							 = new BookingTemp('new');
		$tempModel->bookingRoutes			 = $routes;
		$tempModel->bkg_route_data			 = CJSON::encode($leadRoutes);
		$tempModel->bkg_contact_no			 = $leadPhone;
		$tempModel->bkg_country_code		 = $leadPhoneCode;
		$tempModel->bkg_user_email			 = $leadEmail;
		$tempModel->bkg_from_city_id		 = $model->bkg_from_city_id;
		$tempModel->bkg_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker($model->bkg_pickup_date);
		$tempModel->bkg_pickup_date_time	 = date('h:i A', strtotime($model->bkg_pickup_date));
		$tempModel->bkg_booking_type		 = $model->bkg_booking_type;
		$tempModel->bkg_to_city_id			 = $model->bkg_to_city_id;
		#$tempModel->bkg_platform			 = $platformType ? $platformType : Booking::Platform_App;
		$tempModel->bkg_platform			 = Booking::Platform_App;
		$tempModel->bkg_user_ip				 = \Filter::getUserIP();
		$tempModel->bkg_user_city			 = $cityinfo['city'];
		$tempModel->bkg_user_country		 = $cityinfo['country'];
		$tempModel->bkg_user_device			 = UserLog::model()->getDevice();
		$tempModel->bkg_user_last_updated_on = new CDbExpression('NOW()');
		$tempModel->bkg_tnc_id				 = Terms::model()->getText(1);
		$tempModel->bkg_tnc_time			 = new CDbExpression('NOW()');
		$tempModel->bkg_transfer_type		 = 1;
		$tempModel->bkg_booking_id			 = 'temp';
		if ($model->bkg_booking_type == 5)
		{
			$tempModel->bkg_package_id = $model->bkg_package_id;
			$tempModel->setScenario('validateStep1');
		}
		else
		{
			$tempModel->setScenario('multiroute');
		}

		if (!$tempModel->save())
		{
			throw new Exception(CJSON::encode($tempModel->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
		$leadId						 = BookingTemp::model()->generateBookingid($tempModel);
		$tempModel->bkg_booking_id	 = $leadId;
		if (!$tempModel->save())
		{
			throw new Exception(CJSON::encode($tempModel->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
		BookingTemp::markDuplicateLead($tempModel->bkg_id);
		BookingTemp::stopAutoAssignDuplicateQuote($tempModel->bkg_id);
		LeadLog::model()->createLog($leadId, "Quote generated by user", $userInfo, '', '', BookingLog::BOOKING_CREATED);
		$success = true;
		return $tempModel;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
// NOTE: you may need to adjust the relation name and the related
// class name for the relations automatically generated below.
		return array(
			'bkgFollowUpBy'		 => array(self::BELONGS_TO, 'Admins', 'bkg_follow_up_by'),
			'bkgLockedBy'		 => array(self::BELONGS_TO, 'Admins', 'bkg_locked_by'),
			'bkgAssignedTo'		 => array(self::BELONGS_TO, 'Admins', 'bkg_assigned_to'),
			'bkgFromCity'		 => array(self::BELONGS_TO, 'Cities', 'bkg_from_city_id'),
			'bkgToCity'			 => array(self::BELONGS_TO, 'Cities', 'bkg_to_city_id'),
			'bkgRoute'			 => array(self::BELONGS_TO, 'Route', 'bkg_route_id'),
			'bkgSvcClassVhcCat'	 => array(self::BELONGS_TO, 'SvcClassVhcCat', 'bkg_vehicle_type_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'bkg_id'				 => 'Bkg',
			'bkg_booking_id'		 => 'Booking Id',
			'bkg_user_id'			 => 'User id',
			'bkg_keyword'			 => '',
			'bkg_keyword_txt'		 => '',
			'bkg_user_name'			 => 'First Name',
			'bkg_user_lname'		 => 'Last Name',
			'bkg_pickup_date'		 => 'Pickup Date',
			'bkg_pickup_time'		 => 'Pickup Time',
			'bkg_pickup_date_date'	 => 'Pickup Date',
			'bkg_pickup_date_time'	 => 'Pickup Time',
			'bkg_route_id'			 => 'Route',
			'bkg_booking_type'		 => 'Booking Type',
			'bkg_from_city_id'		 => 'Source City',
			'bkg_to_city_id'		 => 'Destination City',
			'bkg_pickup_address'	 => 'Pickup Address',
			'bkg_drop_address'		 => 'Drop Address',
			'bkg_pickup_lat'		 => 'Pickup Lat',
			'bkg_pickup_long'		 => 'Pickup Long',
			'bkg_country_code'		 => 'ISD Code',
			'bkg_contact_no'		 => 'Contact No',
			'contactnumber'			 => 'Contact number',
			'bkg_alternate_contact'	 => ' Alternate Contact',
			'bkg_user_email'		 => 'User Email',
			'bkg_vehicle_type_id'	 => 'Vehicle Type',
			'bkg_no_person'			 => 'No Person',
			'bkg_driver_id'			 => 'Driver',
			'bkg_vehicle_id'		 => 'Vehicle',
			'bkg_vendor_id'			 => 'Vendor',
			'bkg_extdriver_name'	 => 'Extdriver Name',
			'bkg_extdriver_contact'	 => 'Extdriver Contact',
			'bkg_extvehicle_number'	 => 'Extvehicle Number',
			'bkg_extvehicle_type'	 => 'Extvehicle Type',
			'bkg_is_approved'		 => 'Is Approved',
			'bkg_approved_date'		 => 'Approved Date',
			'bkg_user_ip'			 => 'User Ip',
			'bkg_user_device'		 => 'User Device',
			'bkg_platform'			 => 'Booking Platform',
			'bkg_amount'			 => 'Amount',
			'bkg_drop_date'			 => 'Drop Date',
			'bkg_drop_time'			 => 'Drop Time',
			'bkg_verification_code'	 => 'Verification Code',
			'bkg_verification_code1' => 'Verification Code',
			'bkg_rate_per_km'		 => 'Rate Per Km',
			'bkg_advance'			 => 'Advance',
			'bkg_info_source'		 => 'Info Source',
			'bkg_remark'			 => 'Remark',
			'bkg_delete_reason'		 => 'Delete Reason',
			'bkg_rating'			 => 'Rating',
			'bkg_modified_on'		 => 'Modified On',
			'bkg_status'			 => 'Status',
			'bkg_active'			 => 'Active',
			'bkg_create_date'		 => 'Create Date',
			'bkg_log_type'			 => 'Log Type',
			'bkg_lead_source'		 => 'Lead Source',
			'bkg_log_comment'		 => 'User Comment',
			'new_follow_up_comment'	 => 'Follow up comment',
			'bkg_log_phone'			 => 'Log Phone',
			'bkg_log_email'			 => 'Log Email',
			'bkg_assigned_to'		 => 'Assigned to',
			'bkg_follow_up_status'	 => 'Follow Up Status',
			'bkg_user_city'			 => 'User City',
			'bkg_user_country'		 => 'User Country',
			'bkg_follow_up_reminder' => 'Reminder Date/Time',
			'bkg_user_type'			 => 'user type',
			'bkg_agent_id'			 => 'agent ID',
			'bkg_transfer_type'		 => 'Transfer Type',
			'bkg_return_date'		 => 'End/Return Date'
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
		$criteria->compare('bkg_id', $this->bkg_id);
		$criteria->compare('bkg_booking_id', $this->bkg_booking_id, true);
		$criteria->compare('bkg_user_id', $this->bkg_user_id);
		$criteria->compare('bkg_keyword', $this->bkg_keyword, true);
		$criteria->compare('bkg_keyword_txt', $this->bkg_keyword_txt, true);
		$criteria->compare('bkg_user_name', $this->bkg_user_name, true);
		$criteria->compare('bkg_user_lname', $this->bkg_user_lname, true);
		$criteria->compare('bkg_pickup_date', $this->bkg_pickup_date, true);
		$criteria->compare('bkg_pickup_time', $this->bkg_pickup_time, true);
		$criteria->compare('bkg_return_date', $this->bkg_return_date, true);
		$criteria->compare('bkg_return_time', $this->bkg_return_time, true);
		$criteria->compare('bkg_route_id', $this->bkg_route_id);
		$criteria->compare('bkg_booking_type', $this->bkg_booking_type);
		$criteria->compare('bkg_from_city_id', $this->bkg_from_city_id);
		$criteria->compare('bkg_to_city_id', $this->bkg_to_city_id);
		$criteria->compare('bkg_pickup_address', $this->bkg_pickup_address, true);
		$criteria->compare('bkg_drop_address', $this->bkg_drop_address, true);
		$criteria->compare('bkg_trip_distance', $this->bkg_trip_distance, true);
		$criteria->compare('bkg_trip_duration', $this->bkg_trip_duration, true);
		$criteria->compare('bkg_file_path', $this->bkg_file_path, true);
		$criteria->compare('bkg_instruction_to_driver_vendor', $this->bkg_instruction_to_driver_vendor, true);
		$criteria->compare('bkg_pickup_lat', $this->bkg_pickup_lat, true);
		$criteria->compare('bkg_pickup_long', $this->bkg_pickup_long, true);
		$criteria->compare('bkg_country_code', $this->bkg_country_code, true);
		$criteria->compare('bkg_contact_no', $this->bkg_contact_no, true);
		$criteria->compare('bkg_alt_country_code', $this->bkg_alt_country_code, true);
		$criteria->compare('bkg_alternate_contact', $this->bkg_alternate_contact, true);
		$criteria->compare('bkg_user_email', $this->bkg_user_email, true);
		$criteria->compare('bkg_vehicle_type_id', $this->bkg_vehicle_type_id);
		$criteria->compare('bkg_no_person', $this->bkg_no_person);
		$criteria->compare('bkg_driver_id', $this->bkg_driver_id);
		$criteria->compare('bkg_vehicle_id', $this->bkg_vehicle_id);
		$criteria->compare('bkg_vendor_id', $this->bkg_vendor_id);
		$criteria->compare('bkg_extdriver_name', $this->bkg_extdriver_name, true);
		$criteria->compare('bkg_extdriver_contact', $this->bkg_extdriver_contact, true);
		$criteria->compare('bkg_extvehicle_number', $this->bkg_extvehicle_number, true);
		$criteria->compare('bkg_extvehicle_type', $this->bkg_extvehicle_type, true);
		$criteria->compare('bkg_is_approved', $this->bkg_is_approved);
		$criteria->compare('bkg_approved_date', $this->bkg_approved_date, true);
		$criteria->compare('bkg_user_ip', $this->bkg_user_ip, true);
		$criteria->compare('bkg_user_device', $this->bkg_user_device, true);
		$criteria->compare('bkg_platform', $this->bkg_platform);
		$criteria->compare('bkg_amount', $this->bkg_amount);
		$criteria->compare('bkg_drop_date', $this->bkg_drop_date, true);
		$criteria->compare('bkg_drop_time', $this->bkg_drop_time, true);
		$criteria->compare('bkg_verification_code', $this->bkg_verification_code, true);
		$criteria->compare('bkg_rate_per_km', $this->bkg_rate_per_km);
		$criteria->compare('bkg_advance', $this->bkg_advance);
		$criteria->compare('bkg_info_source', $this->bkg_info_source, true);
		$criteria->compare('bkg_remark', $this->bkg_remark, true);
		$criteria->compare('bkg_promo_code', $this->bkg_promo_code, true);
		$criteria->compare('bkg_discount', $this->bkg_discount);
		$criteria->compare('bkg_net_charge', $this->bkg_net_charge);
		$criteria->compare('bkg_delete_reason', $this->bkg_delete_reason, true);
		$criteria->compare('bkg_rating', $this->bkg_rating);
		$criteria->compare('bkg_modified_on', $this->bkg_modified_on, true);
		$criteria->compare('bkg_user_last_updated_on', $this->bkg_user_last_updated_on, true);
		$criteria->compare('bkg_status', $this->bkg_status);
		$criteria->compare('bkg_active', $this->bkg_active);
		$criteria->compare('bkg_create_date', $this->bkg_create_date, true);
		$criteria->compare('bkg_log_type', $this->bkg_log_type, true);
		$criteria->compare('bkg_log_comment', $this->bkg_log_comment, true);
		$criteria->compare('bkg_log_phone', $this->bkg_log_phone, true);
		$criteria->compare('bkg_log_email', $this->bkg_log_email, true);
		$criteria->compare('bkg_follow_up_status', $this->bkg_follow_up_status_txt, true);
		$criteria->compare('bkg_follow_up_on', $this->bkg_follow_up_on, true);
		$criteria->compare('bkg_follow_up_by', $this->bkg_follow_up_by, true);
		$criteria->compare('bkg_follow_up_comment', $this->bkg_follow_up_comment, true);
		$criteria->compare('bkg_follow_up_reminder', $this->bkg_follow_up_reminder, true);
		$criteria->compare('bkg_tnc', $this->bkg_tnc);
		$criteria->compare('bkg_tnc_time', $this->bkg_tnc_time, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingTemp the static model class
	 */
	public static function model($id = null, $className = __CLASS__)
	{
		$model = parent::model($className);
		if ($id !== null)
		{
			$model = $model->findByPk($id);
		}
		return $model;
	}

	public function getHash()
	{
		$hash = "";
		if ($this->bkg_id != null)
		{
			$hash = Yii::app()->shortHash->hash($this->bkg_id);
		}
		return $hash;
	}

	public function loadDefaultUser($userId)
	{
		if ($userId == '' || $userId < 1)
		{
			return;
		}
		$this->bkg_user_id	 = UserInfo::getUserId();
		$cttModel			 = Contact::model()->getByUserId($userId);
		if (!$cttModel)
		{
			Logger::exception(new Exception("Unable to fetch contact for UserID: {$userId}", ReturnSet::ERROR_INVALID_DATA));
			return;
		}
		$objPhoneNumber = ContactPhone::getPrimaryNumber($cttModel->ctt_id);
		if ($objPhoneNumber)
		{
			$this->bkg_country_code	 = $objPhoneNumber->getCountryCode();
			$this->bkg_contact_no	 = $objPhoneNumber->getNationalNumber();
		}
		$email = ContactEmail::getPrimaryEmail($cttModel->ctt_id);
		if ($email != '')
		{
			$this->bkg_user_email = $email;
		}

		$this->bkg_user_name	 = $cttModel->ctt_first_name;
		$this->bkg_user_lname	 = $cttModel->ctt_last_name;
	}

	public function loadDefaults()
	{
// Logged User
		if (UserInfo::isLoggedIn() && (UserInfo::getUserType() == UserInfo::TYPE_CONSUMER || UserInfo::getUserType() == UserInfo::TYPE_AGENT))
		{
			/** @var Users $userModel */
			$userModel = UserInfo::getUser()->loadUser();
			$this->loadDefaultUser($userModel->user_id);
		}

		// User IP & Other Info
		$cityinfo						 = UserLog::model()->getCitynCountrycodefromIP(\Filter::getUserIP());
		$this->bkg_user_ip				 = \Filter::getUserIP();
		$this->bkg_user_city			 = $cityinfo['city'];
		$this->bkg_user_country			 = $cityinfo['country'];
		$this->bkg_user_device			 = UserLog::model()->getDevice();
		$this->bkg_user_last_updated_on	 = new CDbExpression('NOW()');
		$this->bkg_platform				 = Booking::Platform_User;

		// TnC
		$tmodel					 = Terms::model()->getText(1);
		$this->bkg_tnc_id		 = $tmodel->tnc_id;
		$this->bkg_tnc_time		 = new CDbExpression('NOW()');
		$this->bkg_booking_id	 = 'temp';

		/** @var BookingRoute $route */
		$route				 = new BookingRoute();
		$dbDate				 = Filter::getDBDateTime();
		$fifteenMin			 = 15 * 60;
		$timeStr			 = (ceil(strtotime($dbDate . '+1 hour') / $fifteenMin)) * $fifteenMin;
		$defaultDate		 = date('Y-m-d H:i:s', $timeStr);
//		$defaultDate		 = date('Y-m-d H:i:s', strtotime('+2 days 9am'));
		$defaultReturnDate	 = date('Y-m-d H:i:s', strtotime('+2 days 10pm'));
		$minDuration		 = Config::getMinPickupTime($this->bkg_booking_type);
		$minDate			 = date('Y-m-d', strtotime("+{$minDuration} minute"));
		$route->brt_min_date = $minDate;

		$route->brt_pickup_date_date = DateTimeFormat::DateTimeToDatePicker($defaultDate);
		$route->brt_pickup_date_time = DateTimeFormat::DateTimeToTimePicker($defaultDate);
		if ($this->bkg_booking_type == 2 || $this->bkg_booking_type == 3)
		{
			$route->brt_return_date_date = DateTimeFormat::DateTimeToDatePicker($defaultReturnDate);
			$route->brt_return_date_time = DateTimeFormat::DateTimeToTimePicker($defaultReturnDate);
		}
		$this->setRoutes([$route]);
	}

	public function beforeValidate()
	{
		if (is_array($this->bookingRoutes) && count($this->bookingRoutes) > 0)
		{
			$route = $this->bookingRoutes[0];
			if ($route->brt_pickup_date_date == "" && $route->brt_pickup_datetime != "")
			{
				$route->parsePickupDateTime($route->brt_pickup_datetime);
			}

			$this->bkg_pickup_date_date	 = $this->bookingRoutes[0]->brt_pickup_date_date;
			$this->bkg_pickup_date_time	 = $this->bookingRoutes[0]->brt_pickup_date_time;
			$this->bkg_from_city_id		 = $this->bookingRoutes[0]->brt_from_city_id;
			$this->bkg_to_city_id		 = $this->bookingRoutes[(count($this->bookingRoutes) - 1)]->brt_to_city_id;
		}

		if (Booking::isDayRental($this->bkg_booking_type))
		{
			$this->bkg_to_city_id = $this->bkg_from_city_id;
		}
		$this->encodeAttributes();
		return parent::beforeValidate();
	}

	public function beforeSave()
	{
		parent::beforeSave();
		if (Yii::app()->request->cookies['bkgSource']->value == 'whatsapp')
		{
			$this->bkg_lead_source	 = 6;
			$this->bkg_info_source	 = 7;
		}
		if (Yii::app()->request->cookies['bkgSource']->value == 'whatsapp-hawaii')
		{
			$this->bkg_lead_source	 = 14;
			$this->bkg_info_source	 = 21;
		}
		if ($this->bkg_booking_type == 7 && $this->bkg_shuttle_id > 0)
		{
			$sltId						 = $this->bkg_shuttle_id;
			$sltDetail					 = Shuttle::model()->getDetailbyId($sltId);
			$this->bkg_pickup_date		 = $sltDetail['slt_pickup_datetime'];
			$this->bkg_pickup_time		 = date('H:i:s', strtotime($sltDetail['slt_pickup_datetime']));
			$this->bkg_pickup_address	 = $sltDetail['slt_pickup_location'];
			$this->bkg_drop_address		 = $sltDetail['slt_drop_location'];
			$this->bkg_pickup_lat		 = $sltDetail['slt_pickup_lat'];
			$this->bkg_pickup_long		 = $sltDetail['slt_pickup_long'];
			$brtModelArr				 = BookingRoute::model()->populateRouteByShuttleId($sltId);
			$routes[]					 = array_filter($brtModelArr[0]->attributes);
			$this->updateRoutes($routes);
		}
		else if ($this->bkg_pickup_time == "")
		{
			$this->bkg_pickup_time = date('H:i:s', strtotime($this->bkg_pickup_date));
		}
		if ($this->bkg_id === "")
		{
			$this->bkg_id = null;
		}
		return true;
	}

	public function afterFind()
	{
		parent::afterFind();
		$this->decodeAttributes();
	}

	public function encodeAttributes()
	{
		if ($this->bkg_pickup_time != "" && $this->bkg_pickup_date_time == "")
		{
			$this->bkg_pickup_date_time = $this->bkg_pickup_time;
		}
		if (DateTimeFormat::concatDateTime($this->bkg_pickup_date_date, $this->bkg_pickup_date_time, $pickupTime))
		{
			$this->bkg_pickup_date = $pickupTime;
		}
		if (DateTimeFormat::concatDateTime($this->bkg_return_date_date, $this->bkg_return_date_time, $returnTime))
		{
			$this->bkg_return_date = $returnTime;
		}
		if (DateTimeFormat::concatDateTime($this->bkg_follow_up_reminder_date, $this->bkg_follow_up_reminder_time, $followupTime))
		{
			$this->bkg_follow_up_reminder = $followupTime;
		}
	}

	public function decodeAttributes()
	{
		$this->parsePickupDateTime($this->bkg_pickup_date);
		$this->parseReturnDateTime($this->bkg_return_date);
		$this->parseFollowupDateTime($this->bkg_follow_up_reminder);
	}

	public function parsePickupDateTime($dateTime)
	{
		if (DateTimeFormat::parseDateTime($dateTime, $date, $time))
		{
			$this->bkg_pickup_date_date	 = $date;
			$this->bkg_pickup_date_time	 = $time;
		}
	}

	public function parseReturnDateTime($dateTime)
	{
		if (DateTimeFormat::parseDateTime($dateTime, $date, $time))
		{
			$this->bkg_return_date_date	 = $date;
			$this->bkg_return_date_time	 = $time;
		}
	}

	public function parseFollowupDateTime($dateTime)
	{
		if (DateTimeFormat::parseDateTime($dateTime, $date, $time))
		{
			$this->bkg_follow_up_reminder_date	 = $date;
			$this->bkg_follow_up_reminder_time	 = $time;
		}
	}

	public function getCountryCodes($stid = 0)
	{
		$countryModel1 = Countries::model()->find('id=:id', array('id' => $stid));
		return $countryModel1->country_phonecode;
	}

	public function getBookingType($bktype = 0)
	{
		$arrBktype = Booking::model()->getBookingType();

		if ($bktype != 0)
		{
			return $arrBktype[$bktype];
		}
		else
		{
			return $arrBktype;
		}
	}

	public function setVerificationCode($bkg_id)
	{
		$model = Booking::model()->setVerificationCode($bkg_id);
		return $model;
	}

	public function getDiscount($model, $pcode)
	{
		$userid			 = (Yii::app()->user->getId() > 0) ? Yii::app()->user->getId() : '';
		$discount		 = Promotions::model()->getPromoDiscount($pcode, $userid, $model->bkg_net_charge, $model->bkg_pickup_date, $model->bkg_platform, $model->bkg_from_city_id, $model->bkg_to_city_id);
		$discountAmount	 = ($discount > 0) ? $discount : 0;
		return $discountAmount;
	}

	public function assignedCsr()
	{
		$csrid	 = $this->bkg_assigned_to;
		$admin	 = Admins::model()->findById($csrid);
		return $admin->adm_fname;
	}

	public function feedbackReport($userid = NULL, $mycall = NULL)
	{
		$where				 = " (( bkg_lead_source IS NOT NULL AND bkg_lead_source > 0) OR (bkg_contact_no <> '' ) OR (bkg_log_email <> '' OR bkg_log_phone <> '' ))";
		$statusCategory		 = $this->bkg_lead_status;
		$csrId				 = Yii::app()->user->getId();
		$activeStatusArr	 = $this->getStatusList(1);
		$inactiveStatusList	 = $this->getStatusList(2);
		$statusList1		 = implode(',', (array_keys($this->getLeadStatus())));
		$activestatusList	 = implode(',', ($activeStatusArr));
		$inactivestatusList1 = implode(',', ($inactiveStatusList));
		$days				 = -5;
		if ($userid != '')
		{
			$where .= " AND bkg_assigned_to='$csrId'";
		}
		if ($this->showAssigned == 1)
		{
			$where .= " AND bkg_assigned_to='$csrId' and bkg_pickup_date > NOW() AND bkg_id IN ({$this->ids})";
		}
		else if ($this->bkg_id != "")
		{
			$where .= " AND bkg_id='$this->bkg_id'";
		}
		if (Yii::app()->controller->module->id == 'rcsr')
		{
			$where .= " AND  bkg_assigned_to='$csrId'";
		}
		if ($mycall == 1)
		{
			$where .= " AND ( bkg_follow_up_reminder < NOW() OR bkg_follow_up_reminder IS NULL OR trim(bkg_follow_up_reminder)= '') ";
		}
		if ($statusCategory == 1)
		{
			$where .= " AND (bkg_pickup_date >= CONCAT(DATE(DATE_ADD(CURDATE(), INTERVAL  " . $days . "  DAY)),' 00:00:00') AND bkg_follow_up_status IN ($activestatusList) ) ";
		}
		if ($statusCategory == 2)
		{
			$where .= " AND ((bkg_pickup_date < CONCAT(DATE(DATE_ADD(CURDATE(), INTERVAL  " . $days . "  DAY)),' 00:00:00') AND bkg_follow_up_status IN ($statusList1)) OR  (bkg_follow_up_status IN ($inactivestatusList1)))";
		}
		if ($statusCategory == 3)
		{
			$where .= " AND  ( bkg_pickup_date>= CONCAT(DATE(DATE_ADD(CURDATE(), INTERVAL  " . $days . "  DAY)),' 00:00:00') AND bkg_follow_up_status IN ($activestatusList) AND bkg_lead_source IN (8,9))";
		}
		if ($statusCategory == 4)
		{
			$where .= " AND ((bkg_create_date BETWEEN CONCAT(DATE_FORMAT(CURDATE(), '%Y-%m-%d'),' 00:00:00') AND CONCAT(DATE_FORMAT(CURDATE(), '%Y-%m-%d'),' 23:59:59'))  AND bkg_follow_up_status IN ($activestatusList) AND (bkg_follow_up_reminder < CONCAT(DATE(DATE_ADD(NOW(), INTERVAL 5 MINUTE)),' 00:00:00') OR bkg_follow_up_reminder IS NULL))";
		}
		if ($statusCategory == 5)
		{
			$where .= " AND  ( bkg_pickup_date >= DATE_ADD(CURDATE(), INTERVAL " . $days . " DAY) AND bkg_follow_up_status IN ($activestatusList) AND   (bkg_pickup_date BETWEEN CONCAT(DATE_FORMAT( DATE(DATE_ADD(NOW(),INTERVAL 48 HOUR)),'%Y-%m-%d'),' 00:00:00') AND CONCAT(DATE_FORMAT( DATE(DATE_ADD(NOW(),INTERVAL 48 HOUR)),'%Y-%m-%d'),' 23:59:59')))";
		}
		if ($statusCategory == 6)
		{
			$where .= " AND  ( bkg_pickup_date >= DATE_ADD(CURDATE(), INTERVAL " . $days . " DAY) AND bkg_follow_up_status IN ($activestatusList) AND ( bkg_follow_up_reminder BETWEEN CONCAT(DATE_FORMAT(CURDATE(), '%Y-%m-%d'),' 00:00:00') AND CONCAT(DATE_FORMAT(CURDATE(), '%Y-%m-%d'),' 23:59:59'))  )";
		}
		if ($statusCategory == 7)
		{
			$where .= " AND  (bkg_pickup_date >= DATE_ADD(CURDATE(), INTERVAL " . $days . " DAY) AND (bkg_follow_up_on BETWEEN CONCAT(DATE_FORMAT(CURDATE(), '%Y-%m-%d'),' 00:00:00') AND CONCAT(DATE_FORMAT(CURDATE(), '%Y-%m-%d'),' 23:59:59')))";
		}
		if ($this->bkg_create_date1 != '' && $this->bkg_create_date2 != '')
		{
			$where .= " AND ( bkg_create_date BETWEEN '$this->bkg_create_date1 00:00:00' AND '$this->bkg_create_date2 23:59:59' )";
		}
		if ($this->bkg_pickup_date1 != '')
		{
			$where .= " AND (bkg_pickup_date>='$this->bkg_pickup_date1 00:00:00')";
		}
		if ($this->bkg_pickup_date2 != '')
		{
			$where .= " AND (bkg_pickup_date<='$this->bkg_pickup_date2 00:00:00')";
		}
		if ($this->bkg_follow_up_reminder_date1 != '')
		{
			$where .= " AND (bkg_follow_up_reminder BETWEEN '$this->bkg_follow_up_reminder_date1 00:00:00' AND '$this->bkg_follow_up_reminder_date1 23:59:59')";
		}
		if ($this->bkg_from_city_id_txt != '' && $this->bkg_to_city_id_txt != '')
		{
			$where .= " AND (bkg_from_city_id ='$this->bkg_from_city_id_txt' AND bkg_to_city_id= '$this->bkg_to_city_id_txt')";
		}
		else if ($this->bkg_from_city_id_txt != '')
		{
			$where .= " AND (bkg_from_city_id='$this->bkg_from_city_id_txt')";
		}
		else if ($this->bkg_to_city_id_txt != '')
		{
			$where .= " AND (bkg_to_city_id='$this->bkg_from_city_id_txt')";
		}
		if ($this->bkg_lead_source_txt != '' && $this->bkg_lead_source_txt != 13)
		{
			$where .= " AND (bkg_lead_source='$this->bkg_lead_source_txt')";
		}
		if ($this->bkg_lead_source_txt == 13)
		{
			$where .= " AND (bkg_qr_id > 0)";
		}
		if ($this->bkg_follow_up_status_txt != '')
		{
			$where .= " AND (bkg_follow_up_status='$this->bkg_follow_up_status_txt')";
		}
		$search	 = $this->bkg_keyword_txt;
		$where1	 = "";
		if ($search != "")
		{
			$where3		 = "";
			$fields		 = ['bkg_id', 'bkg_user_name', 'bkg_user_lname', 'bkg_contact_no', 'bkg_alternate_contact', 'bkg_user_email', 'bkg_log_phone', 'bkg_log_email'];
			$arrSearch	 = array_filter(explode(" ", $search));
			$arrcount	 = 0;
			foreach ($arrSearch as $val)
			{
				$arrcount++;
				$where2			 = "";
				$arrFieldsCount	 = 0;
				foreach ($fields as $field)
				{
					$arrFieldsCount++;
					if (count($fields) == ($arrFieldsCount))
					{
						$where2 .= " $field like '%$val%' ";
					}
					else
					{
						$where2 .= " $field like '%$val%' OR";
					}
				}
				if (count($arrSearch) == ($arrcount))
				{
					$where3 .= "  ( $where2 ) ";
				}
				else
				{
					$where3 .= "  ( $where2 ) and ";
				}
			}
			$where1 .= " and (" . $where3 . ")";
		}
		$sql		 = "Select
			bkg_id,
			bkg_is_related_lead,
			bkg_vehicle_type_id,
			bkg_booking_type,
			bkg_lead_source,
			bkg_log_comment,
			bkg_assigned_to,
			bkg_log_email,
			bkg_log_phone,
		    bkg_user_city,
			bkg_user_country,
			bkg_user_ip,
			bkg_follow_up_reminder,
			bkg_locked_by,
			bkg_lock_timeout,
			bkg_follow_up_status,
			bkg_follow_up_on,
			bkg_follow_up_by,
			bkg_contact_no,
			bkg_booking_id,
			bkg_pickup_date,
			bkg_user_email,
			bkg_create_date,
			bkg_user_name,
			bkg_user_lname,
			IFNULL(bkg_follow_up_reminder, bkg_create_date) as remindDate,
			CASE
		WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date,NOW()) BETWEEN 15 AND 30 THEN 60
		WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date,NOW()) BETWEEN 30 AND 60 THEN 40
		WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date,NOW()) BETWEEN 60 AND 120 THEN 25
		WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date,NOW()) BETWEEN 120 AND 720 THEN 10
		ELSE 0
	    END AS timeRank
		    ,
			CASE
		WHEN TIMESTAMPDIFF(MINUTE, NOW(),bkg_pickup_date) BETWEEN 300 AND 1440 THEN 50
		WHEN TIMESTAMPDIFF(MINUTE, NOW(),bkg_pickup_date) BETWEEN 1440 AND 2880 THEN 40
		WHEN TIMESTAMPDIFF(MINUTE, NOW(),bkg_pickup_date) BETWEEN 2880 AND 5760 THEN 25
		WHEN TIMESTAMPDIFF(MINUTE, NOW(),bkg_pickup_date) BETWEEN 5760 AND 8640 THEN 10
		WHEN TIMESTAMPDIFF(MINUTE, NOW(),bkg_pickup_date) BETWEEN 8640 AND 11520 THEN 2
		ELSE 0
	    END AS pickupRank
		    ,
			IF(bkg_assigned_to='$csrId',1,IF(bkg_assigned_to=0 OR bkg_assigned_to IS NULL,0,-1)) as flagCSR,
			IF(bkg_pickup_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 6 HOUR), 1, 0) as immediatePickup,
            IF(bkg_follow_up_status=0,1,0) as flagNew,
			scc_VehicleCategory.vct_label AS vct_label,
			bkgFromCity.cty_name as from_city_name,
			bkgToCity.cty_name as to_city_name,
			AssignedTo.adm_fname as AssignedToadm_fname,
			FollowUpBy.adm_fname as FollowUpByadm_fname,
			FollowUpBy.adm_lname as FollowUpByadm_lname,
			sc.scc_label
			FROM booking_temp
			LEFT JOIN cities bkgFromCity	ON   (bkg_from_city_id = bkgFromCity.cty_id)   AND (bkgFromCity.cty_active = 1)
			LEFT JOIN cities bkgToCity	    ON   (bkg_to_city_id = bkgToCity.cty_id)	   AND (bkgToCity.cty_active = 1)
			LEFT  JOIN admins AssignedTo	ON   (bkg_assigned_to = AssignedTo.adm_id)     AND (AssignedTo.adm_active > 0)
			LEFT JOIN svc_class_vhc_cat bkgSvcClassVhcCat ON (bkg_vehicle_type_id = bkgSvcClassVhcCat.scv_id)
			LEFT JOIN service_class sc ON (sc.scc_id = bkgSvcClassVhcCat.scv_scc_id)
			LEFT JOIN vehicle_category scc_VehicleCategory ON (bkgSvcClassVhcCat.scv_vct_id = scc_VehicleCategory.vct_id)
			LEFT  JOIN admins FollowUpBy ON (bkg_follow_up_by = FollowUpBy.adm_id)  AND (FollowUpBy.adm_active > 0)
            WHERE 1 and  ( $where ) $where1";
		$sqlCount	 = " Select count(*)	FROM booking_temp  WHERE 1 and ( $where ) $where1";

		$count			 = DBUtil::queryScalar($sqlCount);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => [
				'attributes'	 => array(),
				'defaultOrder'	 => 'flagCSR DESC, flagNew DESC, (timeRank+pickupRank) DESC, bkg_pickup_date ASC, immediatePickup DESC, bkg_create_date DESC,remindDate ASC'
			],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function getNewLeads()
	{
		$qry		 = "select bkg_log_phone,bkg_log_email,bkg_user_name,bkg_user_lname,bkg_contact_no,bkg_user_email from booking_temp where ((bkg_lead_source IS NOT NULL) OR (bkg_modified_on < DATE_SUB( NOW() , INTERVAL 30 MINUTE))) AND bkg_follow_up_status = 0";
		$recordset	 = DBUtil::queryAll($qry);
		return $recordset;
	}

	public function findAdminNameList($id)
	{
		$model = Admins::model()->findByPk($id);
		return ucfirst($model->adm_user);
	}

	public function getSourceIndexed($type = '', $val = '')
	{
		$arrInfosource = [
			'1'	 => 'Call me to make booking',
			'2'	 => 'Changed my mind',
			'3'	 => 'Agent',
			'4'	 => 'Chat',
			'5'	 => 'Email',
			'6'	 => 'Sms/WhatsApp',
			'7'	 => 'Phone call',
			'8'	 => 'Incomplete booking',
			'9'	 => 'Unverified Booking',
			'10' => 'Upsell SMS',
			'11' => 'Quikr',
			'12' => 'Ixigo',
			'13' => 'Lead created by QR',
			'14' => 'WhatsApp Promo - H',
		];

		if ($type == 'edit')
		{
			$filteredList		 = array();
			$filteredListsource	 = ['3', '4', '5', '6', '7', '10', '11', '12', '14'];
			if ($val != '')
			{
				$filteredListsource[] = $val;
			}
			foreach ($filteredListsource as $i)
			{
				$filteredList[$i] = $arrInfosource[$i];
			}
			asort($filteredList);
			return $filteredList;
		}
		asort($arrInfosource);
		return $arrInfosource;
	}

	public function getSourceName($bkg_lead_source = "")
	{
		$source		 = ($bkg_lead_source == "" || $bkg_lead_source == null) ? $this->bkg_lead_source : $bkg_lead_source;
		$sourceList	 = $this->getSourceIndexed();
		return $sourceList[$source];
	}

	public function getInitialName()
	{
		return strtoupper(trim(substr($this->bkg_user_name, 0, 1)) . '' . trim(substr($this->bkg_user_lname, 0, 1)));
	}

	public function getUsername()
	{
		return trim($this->bkg_user_name) . ' ' . trim($this->bkg_user_lname);
	}

	public function getContactNumber()
	{
		$phone = '';
		if ($this->bkg_contact_no != '')
		{
			$phone = $this->bkg_country_code . $this->bkg_contact_no;
		}
		return $phone;
	}

	public function getAlternateNumber()
	{
		$phone = '';
		if ($this->bkg_alternate_contact != '')
		{
			$phone = $this->bkg_alt_country_code . $this->bkg_alternate_contact;
		}
		return $phone;
	}

	public function getPlatform()
	{
		$arr = Booking::model()->booking_platform;
		return $arr[$this->bkg_platform];
	}

	public function getFollowupStatus($bkg_follow_up_status)
	{
		$arr = $this->getLeadStatus();
		return $arr[$bkg_follow_up_status];
	}

	public function getDetailsbyId($bkgid)
	{
		/* @var $model BookingTemp */
		$criteria					 = new CDbCriteria;
		$criteria->compare('bkg_id', $bkgid);
		$criteria->with				 = ['bkgFromCity', 'bkgToCity', 'bkgSvcClassVhcCat', 'bkgRoute'];
		$criteria->together			 = TRUE;
		$model						 = $this->find($criteria);
		$data						 = $model->attributes;
		$data['consumer_name']		 = $model->getUsername();
		$data['consumer_phone']		 = $model->getContactNumber();
		$data['consumer_alt_phone']	 = $model->getAlternateNumber();
		$data['route_name']			 = $model->bkgFromCity->cty_name . '-' . $model->bkgToCity->cty_name;
		$data['pick_date']			 = DateTimeFormat::DateTimeToLocale($model->bkg_pickup_date);
		$data['return_date']		 = DateTimeFormat::DateTimeToLocale($model->bkg_return_date);
		$data['booking_type']		 = $model->getBookingType($model->bkg_booking_type);

		$data['from_city']	 = $model->bkgFromCity->cty_name;
		$data['to_city']	 = $model->bkgToCity->cty_name;
		unset($data['bkg_user_last_updated_on']);
		unset($data['bkg_follow_up_comment']);

		return $data;
	}

	public function isLeadlocked($isLeadlocked = "")
	{
		$locktime	 = ($isLeadlocked == "" || $isLeadlocked == null) ? $this->bkg_lock_timeout : $isLeadlocked;
		$now		 = date("Y-m-d H:i:s");
		if ($locktime > $now)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	public static function getRelatedIds($leadId)
	{
		$sql = "SELECT GROUP_CONCAT(bkg.bkg_id) as leadIds FROM booking_temp t, booking_temp bkg
				WHERE t.bkg_id=:leadId AND t.bkg_id<>bkg.bkg_id AND ((abs(TIMESTAMPDIFF(MINUTE, t.bkg_create_date, bkg.bkg_create_date))<240 AND
                  (((bkg.bkg_user_email <> '' AND
                    bkg.bkg_user_email = t.bkg_user_email) OR
                   (bkg.bkg_contact_no <> '' AND
                    bkg.bkg_contact_no = t.bkg_contact_no)))) OR
                   (abs(TIMESTAMPDIFF(MINUTE, t.bkg_create_date, bkg.bkg_create_date))<30
					AND bkg.bkg_user_ip = t.bkg_user_ip AND trim(bkg.bkg_user_ip) <> ''))";
		return DBUtil::command($sql)->queryScalar(['leadId' => $leadId]);
	}

	public static function assignRelatedIds($leadId, $csr)
	{
		$success = false;
		$leadIds = self::getRelatedIds($leadId);
		if (!$leadIds)
		{
			goto end;
		}
		$params	 = ['csr' => $csr];
		DBUtil::getINStatement($leadIds, $bindString, $params1);
		$sql	 = "UPDATE booking_temp SET bkg_assigned_to=:csr WHERE bkg_id IN ($bindString) AND bkg_agent_id IS NULL";
		$numrows = DBUtil::execute($sql, array_merge($params, $params1));
		if ($numrows == 0)
		{
			goto end;
		}

		$arrLead = explode(",", $leadIds);
		foreach ($arrLead as $lead)
		{
			$aname		 = Admins::model()->findByPk($csr)->getName();
			$desc		 = "Related Lead assigned to $aname (Source Lead: $leadId)";
			$userInfo	 = UserInfo::model();
			LeadLog::model()->createLog($lead, $desc, $userInfo);
		}
		$success = true;
		end:
		return $success;
	}

	public function assignCSR($bkid, $admin_id)
	{
		if ($bkid != '' && $bkid != 0 && $admin_id != 0)
		{
			$model					 = BookingTemp::model()->findByPk($bkid);
			$model->bkg_assigned_to	 = $admin_id;

			$model->scenario = 'assigncsr';
			if ($model->update())
			{
				return $model->bkg_id;
			}
			throw new Exception("Failed assigning csr: " . json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
	}

	public static function getLeadStatus($type = '', $val = '')
	{
		$activelead	 = array(0, 1, 2, 3, 15, 16, 20, 21);
		$status		 = array(
			0	 => 'Not followed up',
			1	 => 'Customer asked to call back later',
			2	 => 'Not Responding, Call back later',
			3	 => 'Interested, will book later',
			4	 => 'Has existing booking with GOZO',
			5	 => 'Not interested',
			6	 => 'Booked Somewhere Else',
			7	 => 'Invalid Lead',
			8	 => 'Unsupported city request',
			9	 => 'pickup time expired',
			10	 => 'pickup time expired, followup was late',
			13	 => 'Converted to Quote',
			14	 => 'Duplicate Lead',
			15	 => 'Price Related Issues, Lead Lost',
			16	 => 'Price Related Issues, Follow Up',
			20	 => 'Call customer. Auto-follow reply received',
			21	 => 'Auto-followup sent'
		);

		if ($type == 'active')
		{
			$vstatus = array();
			foreach ($activelead as $i)
			{
				$vstatus[$i] = $status[$i];
			}

			return $vstatus;
		}
		if ($type == 'inactive')
		{
			$vstatus = array();
			foreach ($status as $k => $v)
			{
				if (!in_array($k, $activelead))
				{
					$vstatus[$k] = $status[$k];
				}
			}

			return $vstatus;
		}
		if ($type == 'follow')
		{
			unset($status[0]);
			return $status;
		}
		return $status;
	}

	public static function getTabCategories()
	{
		$leadStatusOld	 = ['1'	 => 'Active', '2'	 => 'Inactive', '3'	 => 'Unverified Booking', '4'	 => 'Not Followed Up', '5'	 => 'Todays Pickup', '6'	 => 'Todays Reminder', '7'	 => 'Todays Followed Up'
//     '3' => 'Unverified'
		];
		$leadStatus		 = ['1'	 => 'Active', '2'	 => 'Inactive', '3'	 => 'Unverified', '4'	 => 'New', '5'	 => 'Pickup In Next 2 Days', '6'	 => 'Todays Reminder', '7'	 => 'Todays Followed Up'
//     '3' => 'Unverified'
		];
		return $leadStatus;
	}

	public static function getLeadStatusJSON()
	{
		$arr	 = array(
			0	 => 'Not followed up',
			1	 => 'Call back later',
			2	 => 'Not Responding, Call back later',
			3	 => 'Interested, will book later',
			4	 => 'Already Booked',
			5	 => 'Not interested',
			6	 => 'Booked Somewhere Else',
			10	 => 'Invalid Lead',
			13	 => 'Converted to Booking',
			15	 => 'Price Related Issues',
			20	 => 'Call customer. Auto-follow reply received',
			21	 => 'Auto-followup sent'
		);
		$arrJSON = array();
		foreach ($arr as $key => $val)
		{
			$arrJSON[] = array("id" => $key, "text" => $val);
		}
		$data = CJSON::encode($arrJSON);
		return $data;
	}

	public function getStatusList($state = '1')
	{
// $leadStatus = ['1' => 'Active', '2' => 'Inactive', '3' => 'Unverified'];
		$arr			 = ['1' => 'active', '2' => 'inactive'];
		$statusList		 = $this->getLeadStatus($arr[$state]);
		$strStatusKeys	 = array_keys($statusList);

		return $strStatusKeys;
	}

	public function generateBookingCodeid($bmodel)
	{
		$booking_id = $this->booking_types[$bmodel->bkg_booking_type] . date('Y') . str_pad($bmodel->bkg_id, 4, 0, STR_PAD_LEFT);
		return $booking_id;
	}

	public function getLeadbyRefBookingid($refbkgid)
	{
		$criteria = new CDbCriteria;
		$criteria->compare('bkg_ref_booking_id', $refbkgid);
		return $this->find($criteria);
	}

	public function markInvalid($bkid, $reason)
	{
		if ($bkid != '' && $bkid != 0 && $reason != '')
		{
			$model						 = BookingTemp::model()->findByPk($bkid);
			$model->bkg_delete_reason	 = ($model->bkg_delete_reason == '') ? $reason : $model->bkg_delete_reason . '; ' . $reason;

///////

			$prev_remark				 = $model->bkg_follow_up_comment;
			$dt							 = date('Y-m-d H:i:s');
			$user						 = Yii::app()->user->getId();
			$status						 = $model->bkg_status;
			$invalidStatus				 = 10;
			$model->bkg_follow_up_status = $invalidStatus;
			$followupStatus				 = $model->bkg_follow_up_status;

//   if ($new_remark != '') {
			if (is_string($prev_remark))
			{
				$newcomm = CJSON::decode($prev_remark);
				if ($prev_remark != '' && CJSON::decode($prev_remark) == '')
				{
					$newcomm = array(array(0 => '1', 1 => $model->bkg_create_date, 2 => $prev_remark, 3 => '2'));
				}
			}
			else if (is_array($prev_remark))
			{
				$newcomm = $prev_remark;
			}
			if ($newcomm == false)
			{
				$newcomm = array();
			}
			array_unshift($newcomm, array(0 => $user, 1 => $dt, 2 => $new_remark, 3 => $status, 4 => $followupStatus));
			$model->bkg_follow_up_comment = CJSON::encode($newcomm);
//   }
///////


			$model->scenario = 'mark_invalid';
			if ($model->validate())
			{
				$model->save();
				return $model->bkg_id;
			}
		}
		return false;
	}

	public function getLeadReport()
	{
		$sql = "SELECT
						COUNT(*) AS total_pending_leads,
						SUM(IF(bkg_lead_source = 9, 1, 0))  AS unverified_leads,
						SUM(IF(bkg_lead_source = 9, 0, 1))  AS other_leads,
						(
						   SELECT COUNT(*)
						   FROM   booking_temp
						   WHERE  ((bkg_lead_source IS NOT NULL AND bkg_lead_source > 0) OR (bkg_user_email <> '' OR bkg_contact_no <> '') OR (
						   bkg_log_email <> '' OR bkg_log_phone <> '')) AND (bkg_pickup_date) >= DATE_ADD(CURDATE(), INTERVAL -5 DAY) AND bkg_follow_up_reminder IS NULL
						 ) AS new_leads,
						(
						  SELECT COUNT(*)
						  FROM   booking_temp
						  WHERE  ((bkg_lead_source IS NOT NULL AND bkg_lead_source > 0) OR (bkg_user_email <> '' OR bkg_contact_no <> '') OR (
						  bkg_log_email <> '' OR bkg_log_phone <> '')) AND (bkg_pickup_date BETWEEN CURDATE() AND NOW()) AND  bkg_follow_up_reminder < NOW() AND bkg_follow_up_status IN (0, 1, 2, 3)
						) AS pickup_leads,
						(
						  SELECT COUNT(*)
						  FROM   booking
						  WHERE  bkg_status = 1 AND bkg_active = 1
						) AS new_unverified,
						(
						  SELECT COUNT(*)
						  FROM   booking_temp
						  WHERE  ((bkg_lead_source IS NOT NULL AND bkg_lead_source > 0) OR (bkg_user_email <> '' OR bkg_contact_no <> '') OR (
						  bkg_log_email <> '' OR bkg_log_phone <> '')) AND (bkg_follow_up_on BETWEEN CURDATE() AND NOW())
						) AS today_followed_up
						FROM   booking_temp
						WHERE   (
									(bkg_lead_source IS NOT NULL AND bkg_lead_source > 0) OR (bkg_user_email <> '' OR bkg_contact_no <> '') OR (bkg_log_email <> '' OR bkg_log_phone <> '')
								)
						AND (bkg_pickup_date) >= DATE_ADD(CURDATE(), INTERVAL -5 DAY)
						AND bkg_follow_up_status IN (0, 1, 2, 3)
						AND (bkg_follow_up_reminder IS NULL OR bkg_follow_up_reminder < NOW())";
		return DBUtil::queryRow($sql);
	}

	public function reportSnapshot()
	{
		$lastMonday	 = date('Y-m-d', strtotime("last Monday"));
		$bookingSql	 = "SELECT
						sum(IF(bkg_status=2,1,0)) new,
						sum(IF(bkg_status=3,1,0)) assigned,
						sum(IF(bkg_status=5,1,0)) onTheWay,
						sum(IF(bkg_status=6,1,0)) completed
						FROM booking";

		$leadSql = "SELECT
					sum(if((bkg_follow_up_status = 0) OR (date(bkg_follow_up_reminder) = CURDATE()),1,0)) pendingLeads
					FROM `booking_temp`
					WHERE (bkg_pickup_date) >= DATE_ADD(CURDATE(), INTERVAL -5 DAY)
					AND ((bkg_lead_source IS NOT NULL AND bkg_lead_source > 0) OR (bkg_user_email <> '' OR bkg_contact_no <> '' )
					OR (bkg_log_email <> '' OR bkg_log_phone <> '' ))
					AND bkg_follow_up_status IN (0,1,2,3)";

		$leadAvgClosure = "SELECT avg(ceil(TIMESTAMPDIFF(HOUR,bkg_create_date,bkg_follow_up_on)/24)) avgLeadClosingDay FROM `booking_temp` WHERE bkg_follow_up_status NOT IN (0,1,2,3) AND (bkg_follow_up_on) >= '" . $lastMonday . "'";

		$recordset1	 = DBUtil::queryRow($bookingSql, DBUtil::SDB());
		$recordset2	 = DBUtil::queryRow($leadSql, DBUtil::SDB());
		$recordset3	 = DBUtil::queryRow($leadAvgClosure, DBUtil::SDB());

		$recordset				 = $recordset1 + $recordset2 + $recordset3;
		$recordset['lastMonday'] = $lastMonday;
		return $recordset;
	}

	public function getLeadClosureTimeByDateRange($stdate, $endate)
	{
		$leadAvgClosure = "SELECT avg(ceil(TIMESTAMPDIFF(HOUR,bkg_create_date,bkg_follow_up_on)/24)) avgLeadClosingDay FROM `booking_temp` WHERE bkg_follow_up_status NOT IN (0,1,2,3) AND (bkg_follow_up_on) >= '" . $stdate . "' AND (bkg_follow_up_on) <= '" . $endate . "'";
		return DBUtil::queryRow($leadAvgClosure);
	}

	public function generateBookingid($bmodel)
	{
		$booking_id = 'LD' . date('y') . str_pad($bmodel->bkg_id, 7, 0, STR_PAD_LEFT);
		return $booking_id;
	}

	public static function setInactiveRelatedLeads($id)
	{
		try
		{
			$model		 = Booking::model()->findByPk($id);
			$userId		 = $model->bkgUserInfo->bkg_user_id;
			$contactNo	 = $model->bkgUserInfo->bkg_contact_no;
			$email		 = $model->bkgUserInfo->bkg_user_email;
			$sql		 = "UPDATE booking_temp
							SET bkg_follow_up_status=13, bkg_ref_booking_id='{$id}'
							WHERE ((bkg_contact_no='{$contactNo}' AND bkg_contact_no<>'') 
								OR (bkg_user_email='{$email}' AND bkg_user_email<>'')
								OR (bkg_user_id='{$userId}' AND bkg_user_id>0))
								AND bkg_create_date>=DATE_SUB(NOW(), INTERVAL 24 HOUR)
								AND bkg_follow_up_status IN (0, 1, 2, 3)";
			DBUtil::execute($sql);
		}
		catch (Exception $e)
		{
			ReturnSet::setException($e);
		}
		return true;
	}

	public static function stopAutoAssignDuplicateQuote($bkgId)
	{
		$success = true;
		try
		{
			$model = BookingTemp::model()->findByPk($bkgId);
			if (!$model)
			{
				return false;
			}

			$condition	 = [];
			$params		 = [];
			$sqlCond	 = "";
			if ($model->bkg_user_id != '' && $model->bkg_user_id != '0')
			{
				$condition[]		 = "bkg_user_id=:userId";
				$params["userId"]	 = $model->bkg_user_id;
			}

			if ($model->bkg_contact_no != '')
			{
				$condition[]		 = "bkg_contact_no=:contactNo";
				$params["contactNo"] = $model->bkg_contact_no;
			}

			if ($model->bkg_user_email != '')
			{
				$condition[]	 = "bkg_user_email=:email";
				$params["email"] = $model->bkg_user_email;
			}

			if (count($condition) > 0)
			{
				$sqlCond = " (" . implode(" OR ", $condition) . ")";
			}
			else
			{
				return false;
			}

			$sql = "UPDATE booking 
						INNER JOIN booking_user ON bkg_id=bui_bkg_id AND bkg_status=15
						INNER JOIN booking_trail ON btr_bkg_id=bkg_id AND (bkg_assign_csr=0 OR bkg_assign_csr IS NULL)
						SET bkg_assign_csr=-1
						WHERE bkg_agent_id IS NULL AND $sqlCond
					";
			DBUtil::execute($sql, $params);
		}
		catch (Exception $exc)
		{
			ReturnSet::setException($exc);
			$success = false;
		}
		return $success;
	}

	public function getRoutesInfobyCities($citiesInRoutes = [])
	{
		$city1	 = $citiesInRoutes[0];
		$city2	 = $citiesInRoutes[1];
		$data	 = [];
		if ($city1 != '' && $city2 != '')
		{
			$sql = "SELECT distinct rut_special_remarks FROM  route
				 WHERE (rut_from_city_id = $city1 AND rut_to_city_id = $city2)
                    OR (rut_from_city_id = $city2 AND rut_to_city_id = $city1)
                    AND rut_special_remarks IS NOT NULL
                GROUP BY rut_special_remarks";

			$data = DBUtil::queryAll($sql);
		}
		return $data;
	}

	public function getRoutesInfobyId($bkgid = '')
	{
		$bkid = 0;
		if ($bkgid != '' && $bkgid > 0)
		{
			$bkid = $bkgid;
		}
		else if ($this->bkg_id && $this->bkg_id > 0)
		{
			$bkid = $this->bkg_id;
		}
		$sql	 = "SELECT distinct rut.rut_special_remarks FROM  booking_temp bkg
				 LEFT JOIN route rut  ON rut.rut_from_city_id = bkg.bkg_from_city_id
				 and rut.rut_to_city_id = bkg.bkg_to_city_id
                where bkg.bkg_id =  $bkid
                AND rut.rut_special_remarks IS NOT NULL";
		$data	 = DBUtil::queryAll($sql);
		return $data;
	}

	public function updateRelated()
	{
		$data = false;
		try
		{
			$sql	 = "SELECT t.bkg_id, COUNT(*) as countRelated, GROUP_CONCAT(bkg.bkg_id) as relatedIds FROM booking_temp t, booking_temp bkg
							WHERE t.bkg_is_related_lead=0 AND t.bkg_create_date>DATE_SUB(NOW(), INTERVAL 10 HOUR) AND bkg.bkg_create_date>DATE_SUB(NOW(), INTERVAL 96 HOUR)
								AND ((bkg.bkg_from_city_id=t.bkg_from_city_id AND bkg.bkg_to_city_id=t.bkg_to_city_id
								AND (DATE(bkg.bkg_pickup_date)=DATE(t.bkg_pickup_date) OR DATE(bkg.bkg_create_date)=DATE(t.bkg_create_date))
								AND ((bkg.bkg_user_email<>'' AND bkg.bkg_user_email=t.bkg_user_email)
                                    OR (bkg.bkg_contact_no<>'' AND bkg.bkg_contact_no=t.bkg_contact_no)))
                                OR (DATE(bkg.bkg_create_date)=DATE(t.bkg_create_date)
                                AND bkg.bkg_user_ip=t.bkg_user_ip AND trim(bkg.bkg_user_ip)<>'' AND bkg.bkg_platform <> 2))
                                 GROUP BY t.bkg_id HAVING countRelated > 1";
			$data	 = DBUtil::queryAll($sql, DBUtil::SDB());
			foreach ($data as $row)
			{
				$btModel						 = BookingTemp::model()->findByPk($row["bkg_id"]);
				$btModel->bkg_is_related_lead	 = $row["countRelated"] + 1;
				$btModel->save();
			}
		}
		catch (Exception $e)
		{
			$desc = "Related Booking Update Failed: " . $e->getMessage();
			Logger::create($desc, CLogger::LEVEL_ERROR);
		}
		return $data;
	}

	public function getRelatedLeads($id)
	{
		$arrFollowupStatus	 = $this->getLeadStatus();
		$arrBktype			 = Booking::model()->getBookingType();
		$arrPlatform		 = Booking::model()->booking_platform;
		$followStatus		 = ' CASE bkg.bkg_follow_up_status ';
		foreach ($arrFollowupStatus as $k => $v)
		{
			$followStatus .= " WHEN " . $k . " THEN '" . $v . "'";
		}
		$followStatus .= ' END ';

		$btypecase = ' CASE bkg.bkg_booking_type ';
		foreach ($arrBktype as $k => $v)
		{
			$btypecase .= " WHEN " . $k . " THEN '" . $v . "'";
		}
		$btypecase .= ' END ';

		$platformType = ' CASE bkg.bkg_platform ';
		foreach ($arrPlatform as $k => $v)
		{
			$platformType .= " WHEN " . $k . " THEN '" . $v . "'";
		}
		$platformType			 .= ' END ';
		$arrActiveFollowupStatus = implode(',', $this->getStatusList());
		$activeLeads			 = " CASE
            WHEN bkg.bkg_follow_up_status IN (  $arrActiveFollowupStatus  ) THEN '1'
            ELSE '0' END ";
		$sql					 = "SELECT
                bkg.bkg_id bkg_id, bkg.bkg_booking_id,bkg.bkg_user_name,bkg.bkg_user_lname,
                bkg.bkg_country_code,bkg.bkg_contact_no,bkg.bkg_user_email,
                bkg.bkg_user_city,bkg.bkg_user_country,bkg.bkg_user_ip,

                DATE_FORMAT(bkg.bkg_pickup_date,'%d-%m-%Y') bkg_pickup_date_date,
                DATE_FORMAT(bkg.bkg_pickup_date,'%h:%i %p') bkg_pickup_date_time,

                DATE_FORMAT(bkg.bkg_create_date,'%d-%m-%Y') bkg_create_date_date,
                DATE_FORMAT(bkg.bkg_create_date,'%h:%i %p') bkg_create_date_time,

                DATE_FORMAT(bkg.bkg_follow_up_reminder,'%d-%m-%Y') bkg_follow_up_reminder_date,
                DATE_FORMAT(bkg.bkg_follow_up_reminder,'%h:%i %p') bkg_follow_up_reminder_time,

                DATE_FORMAT(bkg.bkg_follow_up_on,'%d-%m-%Y') bkg_follow_up_on_date,
                DATE_FORMAT(bkg.bkg_follow_up_on,'%h:%i %p') bkg_follow_up_on_time,

                DATE_FORMAT(bkg.bkg_follow_up_on,'%d-%m-%Y %h:%i %p') bkg_follow_up_on,
                bkg.bkg_route_data, adm.adm_fname,adm.adm_lname,

                $followStatus as bkg_follow_up_status_name,

                $btypecase as bkg_booking_type,

                $activeLeads AS activeLeads,

                $platformType AS bkg_platform,

                fcty.cty_name bkg_from_city_name,
                tcty.cty_name bkg_to_city_name

                FROM
                 booking_temp t,
                 booking_temp bkg
                JOIN cities fcty ON fcty.cty_id = bkg.bkg_from_city_id
                JOIN cities tcty ON tcty.cty_id = bkg.bkg_to_city_id
                LEFT JOIN admins adm ON bkg.bkg_follow_up_by = adm.adm_id
                WHERE
                t.bkg_id = $id AND

                 ((bkg.bkg_from_city_id = t.bkg_from_city_id AND
                   bkg.bkg_to_city_id = t.bkg_to_city_id AND
                  (DATE(bkg.bkg_pickup_date) = DATE(t.bkg_pickup_date) OR
                   DATE(bkg.bkg_create_date) = DATE(t.bkg_create_date)) AND
                  ((bkg.bkg_user_email <> '' AND
                    bkg.bkg_user_email = t.bkg_user_email) OR
                   (bkg.bkg_contact_no <> '' AND
                    bkg.bkg_contact_no = t.bkg_contact_no))) OR
                   (DATE(bkg.bkg_create_date) = DATE(t.bkg_create_date) AND
                    bkg.bkg_user_ip = t.bkg_user_ip AND
                  trim(bkg.bkg_user_ip) <> ''))

                ";
		$count					 = Yii::app()->db1->createCommand("SELECT COUNT(*) FROM ($sql) a")->queryScalar();
		$dataprovider			 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['bkg_id', 'bkg_booking_id', 'bkg_user_name'],
				'defaultOrder'	 => 'bkg_id DESC'],
		]);
		return $dataprovider;
	}

	public function inactivateDuplicateLeadById($bkid = 0)
	{
		if ($bkid > 0)
		{
			$model						 = BookingTemp::model()->findByPk($bkid);
			$model->bkg_follow_up_status = 14; //Duplicate Lead
			$model->bkg_follow_up_by	 = Yii::app() instanceof CConsoleApplication ? null : Yii::app()->user->getId();
			$model->bkg_follow_up_on	 = new CDbExpression("NOW()");
			if ($model->validate())
			{
				if ($model->save())
				{
					$logDesc	 = "Lead Marked Duplicate(By System)";
					$bkgid		 = $model->bkg_id;
					$desc		 = $logDesc;
					$userInfo	 = UserInfo::getInstance();
					LeadLog::model()->createLog($bkgid, $desc, $userInfo, '', $model->bkg_follow_up_status);
					return $model->bkg_id;
				}
			}
		}
		return false;
	}

	public function getServiceTaxRate()
	{
		$tax_rate = $this->bkg_service_tax_rate;
		if (!$tax_rate)
		{
			$tax_rate					 = $this->bkg_service_tax_rate	 = BookingInvoice::getGstTaxRate($this->bkg_agent_id, $this->bkg_booking_type);
		}
		return $tax_rate;
	}

	public static function getMyCallLead($csr, $leadtype, $unverified = 1, $new = 1, $highValue = 1)
	{
		$highScore		 = 0;
		$unverifiedScore = 0;
		$newScore		 = 0;
		if (!$highValue)
		{
			$highScore = "35";
		}
		if (!$unverified)
		{
			$unverifiedScore = "35";
		}
		if (!$new)
		{
			$newScore = "35";
		}

		$sql1	 = "
		SELECT   bkg_id,
				IF(bkg_assigned_to=$csr,50,0) as csrRank,
				CASE
				  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 20 AND 30 THEN (45 - ($newScore*2) - ($highScore))
				  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 30 AND 60 THEN (50 - ($newScore) - ($highScore*2/3))
				  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 60 AND 120 THEN (30 - ($newScore))
				  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 120 AND 720 THEN 20
				  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 720 AND 1440 THEN 0
				  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 1440 AND 2880 THEN -10
				  ELSE -25
				END AS timeRank,
				CASE
				  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 30 AND 400 THEN 50
				  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 400 AND 600 THEN 40
				  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 600 AND 2880 THEN 50
				  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 2880 AND 5760 THEN 35
				  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 5760 AND 8640 THEN 20
				  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 8640 AND 11520 THEN 10
				  ELSE 0
				END AS pickupRank, 0 AS advanceRank,
				IF(bkg_follow_up_status=20,15,0) AS followup_rank, 1 as type, 0 AS refType
		FROM     booking_temp
		WHERE  bkg_create_date<=DATE_SUB(NOW(), INTERVAL 20 MINUTE)
				AND (HOUR(NOW()) <= 21 OR (HOUR(NOW())>21 AND TIMESTAMPDIFF(MINUTE, bkg_create_date, now())<45))
				AND  (bkg_assigned_to = 0 OR bkg_assigned_to IS NULL OR bkg_assigned_to=$csr)
				AND bkg_follow_up_status IN (0,21, 20)
				AND bkg_pickup_date > NOW()
				AND (bkg_contact_no <> '' OR bkg_log_phone <> '')
		ORDER BY (csrRank + timeRank + advanceRank + pickupRank + followup_rank) DESC,
				bkg_pickup_date ASC,
				bkg_create_date DESC
				LIMIT 0,1
		";
		$sql2	 = "
		SELECT   bkg_id,
				IF(bkg_assigned_to=$csr,
				CASE
				  WHEN bkg_follow_up_status=1 THEN 30
				  WHEN bkg_follow_up_status=2 THEN 20
				  WHEN bkg_follow_up_status=3 THEN 40
				  ELSE 0
				END
				,IF(bkg_assigned_to IS NULL OR bkg_assigned_to=0,0, -50)) as csrRank,
				CASE
				  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 25 AND 35 THEN 0
				  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 35 AND 60 THEN 10
				  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 60 AND 720 THEN 10
				  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 720 AND 1440 THEN 5
				  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 1440 AND 2880 THEN 0
				  ELSE -15
				END AS timeRank,
				CASE
				  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 30 AND 1440 THEN 30
				  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 1440 AND 2880 THEN 20
				  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 2880 AND 5760 THEN 10
				  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 5760 AND 8640 THEN 0
				  ELSE -10
				END AS pickupRank,
					   0 AS advanceRank,
						CASE
				  WHEN TIMESTAMPDIFF(MINUTE, bkg_follow_up_reminder, NOW()) BETWEEN 0 AND 90 THEN 20
				  WHEN TIMESTAMPDIFF(MINUTE, bkg_follow_up_reminder, NOW()) BETWEEN 90 AND 720 THEN 15
				  WHEN TIMESTAMPDIFF(MINUTE, bkg_follow_up_reminder, NOW()) BETWEEN 720 AND 1440 THEN 10
				  WHEN TIMESTAMPDIFF(MINUTE, bkg_follow_up_reminder, NOW()) BETWEEN 1440 AND 2880 THEN 5
				  ELSE -15
				END AS followup_rank,
				1 as type, 0 AS refType
				FROM     booking_temp
				WHERE   bkg_create_date<=DATE_SUB(NOW(), INTERVAL 30 MINUTE)
					AND  (HOUR(NOW()) <= 21 OR (HOUR(NOW())>21 AND TIMESTAMPDIFF(MINUTE, bkg_create_date, now())<45))
					AND bkg_follow_up_status IN (1,2,3)
					AND (`bkg_follow_up_reminder`< NOW())
					AND bkg_pickup_date > NOW()
				AND (bkg_contact_no <> '' OR bkg_log_phone <> '')
				ORDER BY (csrRank + timeRank + advanceRank + pickupRank + followup_rank) DESC,
				bkg_pickup_date ASC,
				bkg_create_date DESC
				LIMIT 0,1
		";
		$sql3	 = "  SELECT   bkg_id,
		IF(bt.bkg_assign_csr=$csr OR (bt.bkg_create_user_type=4 AND bt.bkg_create_user_id=$csr),30,
		IF(bt.bkg_create_user_type<>4 AND (bt.bkg_assign_csr IS NULL OR bt.bkg_assign_csr=0), 0, -50)) as csrRank,
		CASE
		  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 15 AND 30 THEN (70  - ($unverifiedScore*3)  - ($newScore*3))
		  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 30 AND 60 THEN (60  - ($unverifiedScore*2) - ($newScore*2))
		  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 60 AND 120 THEN (45  - $unverifiedScore - ($newScore))
		  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 120 AND 720 THEN (35 - $unverifiedScore)
		  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 720 AND 1440 THEN (15)
		  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 1440 AND 2880 THEN -10
		  ELSE -20
		END AS timeRank,
		CASE
		  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 30 AND 1440 THEN (50   - ($unverifiedScore*2))
		  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 1440 AND 2880 THEN (45  - ($unverifiedScore))
		  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 2880 AND 5760 THEN (40   - ($unverifiedScore))
		  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 5760 AND 8640 THEN 30
		  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 8640 AND 11520 THEN 20
		  ELSE 0
		END AS pickupRank,
		CASE
		  WHEN bi.bkg_gozo_amount > 4000 THEN (10-$highScore)
		  WHEN bi.bkg_gozo_amount BETWEEN 1000 AND 3000 THEN (5)
		  WHEN bi.bkg_gozo_amount BETWEEN 500 AND 1000 THEN 3
		  WHEN bi.bkg_gozo_amount BETWEEN 300 AND 500 THEN 1
		  ELSE 0
		END AS advanceRank,
		IF(bt.bkg_follow_type_id=10 AND btr_unv_followup_by IS NULL , 30, 0) AS followup_rank,
			2 as type,
			0 AS refType
		FROM     booking
			INNER JOIN booking_trail bt ON booking.bkg_id= bt.btr_bkg_id
			INNER JOIN  booking_invoice bi ON bi.biv_bkg_id = bt.btr_bkg_id
		WHERE
		bkg_create_date<=DATE_SUB(NOW(), INTERVAL 20 MINUTE) AND  (HOUR(NOW()) <= 21
			OR (HOUR(NOW())>21 AND TIMESTAMPDIFF(MINUTE, bkg_create_date, now())<45))
			AND  (bt.bkg_assign_csr = 0 OR bt.bkg_assign_csr IS NULL OR bt.bkg_assign_csr=$csr)
			AND bkg_agent_id IS NULL
			AND bkg_status IN (1,15) AND bkg_pickup_date > NOW()
			AND ((bt.bkg_follow_type_id=10 OR bt.bkg_create_type=3)
			AND (btr_unv_followup_by IS NULL OR bkg_followup_date < DATE_SUB(NOW(), INTERVAL IF( bt.bkg_create_user_type=4
				AND bt.bkg_create_user_id=$csr,0,45) MINUTE) ))
		ORDER BY (csrRank + timeRank + advanceRank + pickupRank + followup_rank) DESC, bkg_pickup_date ASC,
				bkg_create_date DESC LIMIT 0,1
  ";

		$sql4	 = "
		SELECT fwp.fwp_id AS bkg_id,
				IF(fwp.fwp_assigned_csr = $csr,
					CASE
						WHEN fwp_follow_up_status=1 THEN 30
						WHEN fwp_follow_up_status=2 THEN 20
						ELSE 0
					END,
					IF(fwp.fwp_assigned_csr IS NULL OR fwp.fwp_assigned_csr=0,0, -50)) as csrRank,
				CASE
				  WHEN TIMESTAMPDIFF(MINUTE, fwp_created, NOW()) BETWEEN 0 AND 60 THEN 80
				  WHEN TIMESTAMPDIFF(MINUTE, fwp_created, NOW()) BETWEEN 60 AND 240 THEN 60
				  WHEN TIMESTAMPDIFF(MINUTE, fwp_created, NOW()) BETWEEN 240 AND 360 THEN 30
				  WHEN TIMESTAMPDIFF(MINUTE, fwp_created, NOW()) BETWEEN 360 AND 720 THEN 20
				  WHEN TIMESTAMPDIFF(MINUTE, fwp_created, NOW()) BETWEEN 720 AND 1080 THEN 10
				  WHEN TIMESTAMPDIFF(MINUTE, fwp_created, NOW()) BETWEEN 1080 AND 1440 THEN 0
				  ELSE -40
				END AS timeRank,
				CASE
				  WHEN bkg_pickup_date IS NULL THEN 0
				  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 30 AND 1440 THEN 20
				  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 1440 AND 2880 THEN 15
				  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 2880 AND 5760 THEN 10
				  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 5760 AND 14400 THEN 0
				  ELSE -20
				END AS pickupRank,
				0 AS advanceRank,  0 AS followup_rank, 3 AS type,
				fwp.fwp_ref_type AS refType
		 	FROM follow_ups fwp
		 	LEFT JOIN booking  ON fwp.fwp_ref_id = booking.bkg_id AND fwp.fwp_ref_type IN (1, 2) AND bkg_status IN (1,15)
            WHERE fwp.fwp_status = 1
					AND fwp.fwp_ref_type IN (1, 2) AND fwp.fwp_call_entity_type IN (1)
					AND (fwp.fwp_assigned_csr = $csr OR fwp.fwp_assigned_csr IS NULL OR fwp.fwp_assigned_csr = 0)
				AND TIMESTAMPDIFF(HOUR, fwp.fwp_prefered_time, NOW()) < 48
				AND fwp.fwp_follow_up_status <> 4
            ORDER BY csrRank DESC,(timeRank + pickupRank) DESC, fwp.fwp_prefered_time DESC
        	LIMIT  0, 1
		";
		$sql	 = "
		SELECT * FROM (
			(
				$sql1
			)
			UNION
			(
				$sql2
			)
			UNION
			(
				$sql3
			)
			UNION
			(
				$sql4
			)
		) a order by (timeRank + pickupRank) DESC, type DESC";
		$row	 = DBUtil::queryRow($sql);
		return $row;
	}

	/**
	 * Returns the row for the call me back follow up to be followed.
	 *
	 * @param integer $csr CSR id to be assigned.
	 * @return $row Array
	 */
	public function getTopLead($csr, $unverified = 1, $new = 1, $highValue = 1)
	{
		$highScore		 = 0;
		$unverifiedScore = 0;
		$newScore		 = 0;
		if (!$highValue)
		{
			$highScore = "35";
		}
		if (!$unverified)
		{
			$unverifiedScore = "35";
		}
		if (!$new)
		{
			$newScore = "35";
		}

		$sql = "SELECT * FROM (
			(
				SELECT   bkg_id,
					IF(bkg_assigned_to=$csr,50,0) as csrRank,
					CASE
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 20 AND 30 THEN (45 - ($newScore*2) - ($highScore))
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 30 AND 60 THEN (50 - ($newScore) - ($highScore*2/3))
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 60 AND 120 THEN (30 - ($newScore))
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 120 AND 720 THEN 20
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 720 AND 1440 THEN 0
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 1440 AND 2880 THEN -10
					  ELSE -25
					END AS timeRank,
					CASE
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 210 AND 400 THEN 50
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 400 AND 600 THEN 40
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 600 AND 2880 THEN 50
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 2880 AND 5760 THEN 35
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 5760 AND 8640 THEN 20
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 8640 AND 11520 THEN 10
					  ELSE 0
					END AS pickupRank, 0 AS advanceRank,
					IF(bkg_follow_up_status=20,15,0) AS followup_rank, 1 as type, 0 AS refType
				FROM     booking_temp
				LEFT JOIN service_call_queue  ON service_call_queue.scq_related_lead_id=bkg_id AND scq_follow_up_queue_type=16  AND scq_active=1 AND scq_ref_type=1 AND  DATE_SUB(NOW(),INTERVAL 48 HOUR) < scq_create_date
				WHERE  bkg_create_date<=DATE_SUB(NOW(), INTERVAL IF(HOUR(NOW())>21, 15, 
									IF(TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date)<480 OR CalcWorkingMinutes(NOW(), bkg_pickup_date)<120, 
											LEAST(ROUND(TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date)*0.4), 90), 30)) MINUTE) AND (HOUR(NOW()) <= 21 OR (HOUR(NOW())>21 AND TIMESTAMPDIFF(MINUTE, bkg_create_date, now())<45))
					AND scq_id IS NULL AND  (bkg_assigned_to = 0 OR bkg_assigned_to IS NULL OR bkg_assigned_to=$csr)
					AND bkg_follow_up_status IN (0,21, 20) AND bkg_pickup_date > NOW()
					AND (bkg_contact_no <> '' OR bkg_log_phone <> '')
				ORDER BY (csrRank + timeRank + advanceRank + pickupRank + followup_rank) DESC, bkg_pickup_date ASC, bkg_create_date DESC
				LIMIT 0,1
			)
			UNION
			(
				SELECT   bkg_id,
					IF(bkg_assigned_to=$csr, CASE
												WHEN bkg_follow_up_status=1 THEN 30
												WHEN bkg_follow_up_status=2 THEN 20
												WHEN bkg_follow_up_status=3 THEN 40
												ELSE 0
											  END
								,IF(bkg_assigned_to IS NULL OR bkg_assigned_to=0,0, -50)) as csrRank,
					CASE
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 25 AND 35 THEN 0
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 35 AND 60 THEN 10
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 60 AND 720 THEN 10
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 720 AND 1440 THEN 5
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 1440 AND 2880 THEN 0
					  ELSE -15
					END AS timeRank,
					CASE
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 210 AND 1440 THEN 30
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 1440 AND 2880 THEN 20
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 2880 AND 5760 THEN 10
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 5760 AND 8640 THEN 0
					  ELSE -10
					END AS pickupRank,
						   0 AS advanceRank,
							CASE
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_follow_up_reminder, NOW()) BETWEEN 0 AND 90 THEN 20
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_follow_up_reminder, NOW()) BETWEEN 90 AND 720 THEN 15
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_follow_up_reminder, NOW()) BETWEEN 720 AND 1440 THEN 10
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_follow_up_reminder, NOW()) BETWEEN 1440 AND 2880 THEN 5
					  ELSE -15
					END AS followup_rank,
					1 as type, 0 AS refType
				FROM     booking_temp
				 LEFT JOIN service_call_queue  ON service_call_queue.scq_related_lead_id=bkg_id AND scq_follow_up_queue_type=16  AND scq_active=1 AND scq_ref_type=1 AND  DATE_SUB(NOW(),INTERVAL 48 HOUR) < scq_create_date
				WHERE   bkg_create_date<=DATE_SUB(NOW(), INTERVAL 30 MINUTE) AND  (HOUR(NOW()) <= 21 OR (HOUR(NOW())>21 AND TIMESTAMPDIFF(MINUTE, bkg_create_date, now())<45))
						AND bkg_follow_up_status IN (1,2,3)
						AND (`bkg_follow_up_reminder`< NOW()) AND bkg_pickup_date > NOW()
						AND scq_id IS NULL
						AND ((bkg_assigned_to = 0 OR bkg_assigned_to IS NULL
							OR DATE_ADD(bkg_follow_up_reminder, INTERVAL IF(bkg_assigned_to=$csr,0,45) MINUTE) <NOW()))

				AND (bkg_contact_no <> '' OR bkg_log_phone <> '')
				ORDER BY (csrRank + timeRank + advanceRank + pickupRank + followup_rank) DESC, bkg_pickup_date ASC, bkg_create_date DESC
				LIMIT 0,1
			)
			UNION
			(
				SELECT   bkg_id,
					IF(bt.bkg_assign_csr=$csr OR (bt.bkg_create_user_type=4 AND bt.bkg_create_user_id=$csr),30,
					IF(bt.bkg_create_user_type<>4 AND (bt.bkg_assign_csr IS NULL OR bt.bkg_assign_csr=0), 0, -50)) as csrRank,
					CASE
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 15 AND 30 THEN (70  - ($unverifiedScore*3)  - ($newScore*3))
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 30 AND 60 THEN (60  - ($unverifiedScore*2) - ($newScore*2))
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 60 AND 120 THEN (45  - $unverifiedScore - ($newScore))
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 120 AND 720 THEN (35 - $unverifiedScore)
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 720 AND 1440 THEN (15)
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 1440 AND 2880 THEN -10
					  ELSE -20
					END AS timeRank,
					CASE
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 210 AND 1440 THEN (50   - ($unverifiedScore*2))
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 1440 AND 2880 THEN (45  - ($unverifiedScore))
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 2880 AND 5760 THEN (40   - ($unverifiedScore))
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 5760 AND 8640 THEN 30
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 8640 AND 11520 THEN 20
					  ELSE 0
					END AS pickupRank,
					CASE
					  WHEN bi.bkg_gozo_amount > 4000 THEN (10-$highScore)
					  WHEN bi.bkg_gozo_amount BETWEEN 1000 AND 3000 THEN (5)
					  WHEN bi.bkg_gozo_amount BETWEEN 500 AND 1000 THEN 3
					  WHEN bi.bkg_gozo_amount BETWEEN 300 AND 500 THEN 1
					  ELSE 0
					END AS advanceRank,
					IF(bt.bkg_follow_type_id=10 AND btr_unv_followup_by IS NULL , 30, 0) AS followup_rank,
					2 as type, 0 AS refType
				FROM     booking INNER JOIN booking_trail bt ON booking.bkg_id= bt.btr_bkg_id
					INNER JOIN  booking_invoice bi ON  bi.biv_bkg_id = bt.btr_bkg_id
					INNER JOIN booking_user bui on bui_bkg_id = bkg_id AND bui.bkg_contact_no <> ''
					LEFT JOIN service_call_queue  ON service_call_queue.scq_related_bkg_id=bkg_id AND scq_follow_up_queue_type=17  AND scq_active=1 AND scq_ref_type=2 AND  DATE_SUB(NOW(),INTERVAL 48 HOUR) < scq_create_date
				WHERE bkg_create_date<=DATE_SUB(NOW(), INTERVAL 20 MINUTE)
					AND  (HOUR(NOW()) <= 21  OR (HOUR(NOW())>21 AND TIMESTAMPDIFF(MINUTE, bkg_create_date, now())<45))
					AND ((bt.bkg_assign_csr = 0 OR bt.bkg_assign_csr IS NULL OR DATE_ADD(bkg_create_date, INTERVAL IF(bt.bkg_assign_csr=$csr,0,45) MINUTE) <NOW()))
					AND bkg_status IN (15) AND bkg_pickup_date > NOW() AND bkg_agent_id IS NULL
					AND  bt.bkg_create_type=3 AND scq_id IS NULL AND (bkg_followup_date IS NULL OR bkg_followup_date < NOW())
				ORDER BY (csrRank + timeRank + advanceRank + pickupRank + followup_rank) DESC, bkg_pickup_date ASC, bkg_create_date DESC
				LIMIT 0,1
			)) a order by (csrRank + timeRank + pickupRank) DESC, refType DESC LIMIT  0, 1";
		$row = DBUtil::queryRow($sql);
		Logger::trace($sql);
		return $row;
	}

	/**
	 * Returns the row for the call me back follow up to be followed.
	 *
	 * @param integer $csr CSR id to be assigned.
	 * @param integer $type 1=>New lead, 2=> Old Lead
	 * @return $row Array
	 */
	public static function getPendingLeads($csr, $limit, $type = 1, $unverified = 1, $new = 1, $highValue = 1)
	{
		if ($type == 1)
		{
			//         $createSql = " AND bkg_create_date>=DATE_SUB(NOW(), INTERVAL 36 HOUR)";
		}
		else
		{
			//       $createSql = " AND bkg_create_date<DATE_SUB(NOW(), INTERVAL 36 HOUR)";
		}

		$highScore		 = 0;
		$unverifiedScore = 0;
		$newScore		 = 0;
		if (!$highValue)
		{
			//         $highScore = "35";
		}
		if (!$unverified)
		{
			//         $unverifiedScore = "35";
		}
		if (!$new)
		{
			//         $newScore = "35";
		}

		$sql = "SELECT * FROM (
			(SELECT   bkg_id,
					  bkg_booking_id,
					  bkg_user_id,
					  bkg_country_code,
					  CONCAT(bkg_country_code,bkg_contact_no) as  bkg_contact_no,
					IF(bkg_assigned_to=$csr,50,0) as csrRank,
					IF(bkg_user_id>0,10,0) as userIdRank,
					CASE
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 30 AND 40 THEN (35 - ($newScore*2) - ($highScore))
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 40 AND 60 THEN (50 - ($newScore) - ($highScore*2/3))
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 60 AND 120 THEN (40 - ($newScore))
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 120 AND 720 THEN 30
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 720 AND 1440 THEN 0
					  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 1440 AND 2880 THEN -10
					  ELSE -25
					END AS timeRank,
					CASE
					  WHEN CalcWorkingMinutes(NOW(), bkg_pickup_date) < 180 THEN 5
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 150 AND 210 THEN 15
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 210 AND 400 THEN 20
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 400 AND 600 THEN 40
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 600 AND 1440 THEN 45
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 1440 AND 2880 THEN 60
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 2880 AND 5760 THEN 50
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 5760 AND 8640 THEN 35
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 8640 AND 11520 THEN 25
					  ELSE 0
					END AS pickupRank, 5 AS advanceRank,
					IF(bkg_follow_up_status=20,15,0) AS followup_rank, 1 as type, 0 AS refType
			FROM     booking_temp
			 WHERE  bkg_create_date<=DATE_SUB(NOW(), INTERVAL IF(HOUR(NOW())>21, 15, 
									IF(TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date)<480 OR CalcWorkingMinutes(NOW(), bkg_pickup_date)<120, 
											LEAST(ROUND(TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date)*0.4), 60), 30)) MINUTE) AND (HOUR(NOW()) <= 21 OR (HOUR(NOW())>21 AND TIMESTAMPDIFF(MINUTE, bkg_create_date, now())<45))
				AND  (bkg_assigned_to = 0 OR bkg_assigned_to IS NULL OR bkg_assigned_to=$csr)
				AND bkg_follow_up_status IN (0,21, 20) AND bkg_pickup_date > NOW() $createSql
				AND (bkg_contact_no <> '' OR bkg_log_phone <> '')
			ORDER BY (csrRank + userIdRank + timeRank + advanceRank + pickupRank + followup_rank) DESC, bkg_pickup_date ASC, bkg_create_date DESC
			LIMIT 0,10)
		UNION
		(
			SELECT   bkg_id,
                 bkg_booking_id,
			     bkg_user_id,
			     bkg_country_code,
			     CONCAT(bkg_country_code,bkg_contact_no) as  bkg_contact_no,
			IF(bkg_assigned_to=$csr,
			CASE
			  WHEN bkg_follow_up_status=1 THEN 30
			  WHEN bkg_follow_up_status=2 THEN 20
			  WHEN bkg_follow_up_status=3 THEN 40
			  ELSE 0
			END
			   ,IF(bkg_assigned_to IS NULL OR bkg_assigned_to=0,0, -50)) as csrRank,
			IF(bkg_user_id>0,10,0) as userIdRank,
			CASE
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 25 AND 35 THEN 0
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 35 AND 60 THEN 10
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 60 AND 720 THEN 10
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 720 AND 1440 THEN 5
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 1440 AND 2880 THEN 0
			  ELSE -15
			END AS timeRank,
			CASE
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 210 AND 1440 THEN 30
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 1440 AND 2880 THEN 20
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 2880 AND 5760 THEN 10
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 5760 AND 8640 THEN 0
			  ELSE -10
			END AS pickupRank,
				   0 AS advanceRank,
					CASE
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_follow_up_reminder, NOW()) BETWEEN 0 AND 90 THEN 20
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_follow_up_reminder, NOW()) BETWEEN 90 AND 720 THEN 15
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_follow_up_reminder, NOW()) BETWEEN 720 AND 1440 THEN 10
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_follow_up_reminder, NOW()) BETWEEN 1440 AND 2880 THEN 5
			  ELSE -15
			END AS followup_rank,
			1 as type, 0 AS refType
			FROM     booking_temp
            WHERE   bkg_create_date<=DATE_SUB(NOW(), INTERVAL 30 MINUTE) AND  (HOUR(NOW()) <= 21 OR (HOUR(NOW())>21 AND TIMESTAMPDIFF(MINUTE, bkg_create_date, now())<45))
					AND bkg_follow_up_status IN (1,2,3) $createSql
					AND (`bkg_follow_up_reminder`< NOW()) AND bkg_pickup_date > NOW()
					AND ((bkg_assigned_to = 0 OR bkg_assigned_to IS NULL
						OR DATE_ADD(bkg_follow_up_reminder, INTERVAL IF(bkg_assigned_to=$csr,0,45) MINUTE) <NOW()))
			AND (bkg_contact_no <> '' OR bkg_log_phone <> '')

			ORDER BY (csrRank + userIdRank + timeRank + advanceRank + pickupRank + followup_rank) DESC, bkg_pickup_date ASC, bkg_create_date DESC
			LIMIT 0,10
		)
		UNION
		(
			SELECT   bkg_id,
					 bkg_booking_id,
					 bkg_user_id,
					 bkg_country_code,
					 CONCAT(bkg_country_code,bkg_contact_no) as  bkg_contact_no,
			IF(bt.bkg_assign_csr=$csr OR (bt.bkg_create_user_type=4 AND bt.bkg_create_user_id=$csr),30,
			IF(bt.bkg_create_user_type<>4 AND (bt.bkg_assign_csr IS NULL OR bt.bkg_assign_csr=0), 0, -50)) as csrRank,
			IF(bkg_user_id>0,10,0) as userIdRank,
			CASE
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 20 AND 30 THEN (35  - ($unverifiedScore*3)  - ($newScore*3))
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 30 AND 60 THEN (50  - ($unverifiedScore*2) - ($newScore*2))
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 60 AND 120 THEN (45  - $unverifiedScore - ($newScore))
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 120 AND 720 THEN (40 - $unverifiedScore)
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 720 AND 1440 THEN (30)
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 1440 AND 2880 THEN 20
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 2880 AND 5760 THEN 0
			  ELSE -20
			END AS timeRank,
			CASE
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 180 AND 720 THEN (60   - ($unverifiedScore*2))
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 720 AND 1440 THEN (50   - ($unverifiedScore*2))
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 1440 AND 1440*3 THEN (55  - ($unverifiedScore))
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 1440*3 AND 1440*6 THEN (40   - ($unverifiedScore))
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 1440*6 AND 1440*10 THEN 35
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 1440*10 AND 1440*15 THEN 25			  
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 1440*15 AND 1440*21 THEN 15			  
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 150 AND 180 THEN (40)
			  ELSE 0
			END AS pickupRank,
			CASE
			  WHEN bi.bkg_gozo_amount > 3000 THEN (30-$highScore)
			  WHEN bi.bkg_gozo_amount BETWEEN 1000 AND 3000 THEN (20)
			  WHEN bi.bkg_gozo_amount BETWEEN 500 AND 1000 THEN 15
			  WHEN bi.bkg_gozo_amount BETWEEN 300 AND 500 THEN 5
			  ELSE 0
			END AS advanceRank,
			IF(bt.bkg_follow_type_id=10 AND btr_unv_followup_by IS NULL , 30, 0) AS followup_rank,
				2 as type,
				0 AS refType
			FROM     booking INNER JOIN booking_trail bt ON booking.bkg_id= bt.btr_bkg_id
			INNER JOIN  booking_invoice bi ON  bi.biv_bkg_id = bt.btr_bkg_id
			INNER JOIN booking_user bui on bui_bkg_id = bkg_id AND bui.bkg_contact_no <> ''
			WHERE
				bkg_create_date<=DATE_SUB(NOW(), INTERVAL 20 MINUTE) AND  (HOUR(NOW()) <= 21
				OR (HOUR(NOW())>21 AND TIMESTAMPDIFF(MINUTE, bkg_create_date, now())<45))
				AND (bt.bkg_assign_csr = 0 OR bt.bkg_assign_csr IS NULL)
				AND bkg_status IN (15) AND bkg_pickup_date > NOW()  $createSql
				AND bkg_agent_id IS NULL
				AND  bt.bkg_create_type=3
				AND (bkg_followup_date IS NULL OR bkg_followup_date < NOW())

			ORDER BY (csrRank + userIdRank + timeRank + advanceRank + pickupRank + followup_rank) DESC, bkg_pickup_date ASC,
					bkg_create_date DESC LIMIT 0,10)

		) a order by (csrRank + userIdRank + timeRank + pickupRank) DESC, refType DESC LIMIT  $limit, 5";
		return DBUtil::query($sql);
	}

	public function setLeadInfoOnEditBooking($lead_id, $bkg_id)
	{
		$leadModel						 = $this->findByPk($lead_id);
		$leadModel->bkg_ref_booking_id	 = $bkg_id;
		$leadModel->bkg_follow_up_status = 4;
		$leadModel->save();
	}

	public function convertedToBooking($leadid, $bkgid)
	{
		if ($leadid > 0)
		{
			$eventId						 = BookingLog::LEAD_CONVERTED_TO_BOOKING;
			$desc							 = "Lead Converted to Booking";
			$leadModel						 = $this->findByPk($leadid);
			$leadModel->bkg_ref_booking_id	 = $bkgid;
			$leadModel->bkg_follow_up_status = 4;
			$leadModel->bkg_status			 = 13;
			$leadModel->bkg_follow_up_status = 13;
			if ($leadModel->save())
			{
				$userInfo = UserInfo::getInstance();
				LeadLog::model()->createLog($leadid, $desc, $userInfo);
				return $eventId;
			}
		}
	}

	public function searchFlexxi($from = '', $to = '', $seats = 0, $bigBags = 0, $smallBags = 0, $pickup1 = '', $pickup2 = '', $gender = 0, $promoterBkgId = 0)
	{
		$quote				 = new Quote;
		$fsMultipleDiscount	 = $quote->disMultipleFS[$seats];
		if ($gender != 1 && $gender != 2)
		{
			$condition = "AND usr_gender IN (1,2)";
		}
		else
		{
			$condition = "AND usr_gender = $gender";
		}
		if ($promoterBkgId > 0)
		{
			$condition .= " AND bkg_id = $promoterBkgId";
		}
		$Val = '"';
		$sql = " SELECT bkg_id,
					bkg_bcb_id,
					CONCAT(bkgusr.bkg_user_fname,' ',bkgusr.bkg_user_lname) as custname,
					users.usr_gender gender,
					ROUND((bkginv.bkg_flexxi_base_amount/vct.vct_capacity)*1*1.25) subs_fare,
					SUM(IFNULL(bkgaddinfo.bkg_num_large_bag,0)),
					SUM(IFNULL(bkgaddinfo.bkg_num_small_bag,0)), SUM(IFNULL(bkgaddinfo.bkg_no_person,0)),
					bkg_pickup_date, bkg_trip_distance,
					bkg_vehicle_type_id, vct.vct_label, vct.vct_image,
					vct.vct_desc, vct.vct_capacity,
					bkg_pickup_date, bkg_booking_type,
					bkg_from_city_id,
					bkg_to_city_id,
					REPLACE(JSON_EXTRACT(`bkg_route_city_names`, '$[0]'), '$Val', '')  AS fromcity,
					REPLACE(JSON_EXTRACT(`bkg_route_city_names`, CONCAT('$[', JSON_LENGTH(`bkg_route_city_names`) - 1, ']')),'$Val','') AS tocity,
					users.usr_profile_pic,
					users.usr_profile_pic_path,
					bkg_status,
					bkg_flexxi_type,
					bkg_fp_id,
					(vct.vct_capacity - SUM(IFNULL(bkgaddinfo.bkg_no_person,0))) remainingSeats,
					(vct.vct_small_bag_capacity - SUM(IFNULL(bkgaddinfo.bkg_num_small_bag,0))) remainingSmallbags,
					(vct.vct_big_bag_capacity - SUM(IFNULL(bkgaddinfo.bkg_num_large_bag,0))) remainingLargebags
					FROM booking
					INNER JOIN svc_class_vhc_cat scv ON bkg_vehicle_type_id = scv.scv_id
					INNER JOIN vehicle_category vct ON scv.scv_id = vct.vct_id
					JOIN booking_user bkgusr ON bkg_id=bkgusr.bui_bkg_id
					JOIN booking_invoice bkginv ON bkg_id=bkginv.biv_bkg_id
					JOIN booking_add_info bkgaddinfo ON bkg_id=bad_bkg_id
					LEFT JOIN users ON users.user_id = bkgusr.bkg_user_id
					WHERE    bkg_booking_type = 1 AND bkg_flexxi_type IN(1,2) AND bkg_status IN (2,3,5) AND bkg_active=1 AND bkg_pickup_date BETWEEN '$pickup1' AND '$pickup2' AND
					bkg_from_city_id = $from AND bkg_to_city_id = $to $condition GROUP BY bkg_bcb_id
					HAVING   remainingSeats >= $seats  ORDER BY bkg_flexxi_type ASC LIMIT 20";
		return DBUtil::queryAll($sql);
	}

	public function createLeadForFlexxiBooking($bkgId)
	{
		$success					 = false;
		$bookingModel				 = Booking::model()->findByPk($bkgId);
		$btmodel					 = new BookingTemp();
		$btmodel->bkg_booking_type	 = $bookingModel->bkg_booking_type;
		$btmodel->bkg_trip_distance	 = $bookingModel->bkg_trip_distance;
		$btmodel->bkg_trip_duration	 = $bookingModel->bkg_trip_duration;

		$date								 = date_create($bookingModel->bkg_pickup_date);
		$brtModels							 = [];
		$arrRt								 = [];
		$arrRt[0]['brt_from_city_id']		 = $bookingModel->bkg_from_city_id;
		$arrRt[0]['brt_to_city_id']			 = $bookingModel->bkg_to_city_id;
		$arrRt[0]['brt_pickup_date_date']	 = date_format($date, 'd/m/Y');
		$arrRt[0]['brt_pickup_date_time']	 = date_format($date, 'g:i A');

		$btmodel->setScenario('multiroute');
		foreach ($arrRt as $route)
		{
			try
			{
				$rtModel						 = new BookingRoute();
				$rtModel->attributes			 = $route;
				$pickupDate1					 = DateTimeFormat::DatePickerToDate($rtModel->brt_pickup_date_date);
				$times1							 = DateTime::createFromFormat('h:i A', $rtModel->brt_pickup_date_time)->format('H:i:00');
				$rtModel->brt_pickup_datetime	 = $pickupDate1 . " " . $times1;
				$brtModels[]					 = $rtModel;
				if ($i == 0)
				{
					$btmodel->bkg_from_city_id		 = $rtModel->brt_from_city_id;
					$btmodel->bkg_pickup_date_date	 = $rtModel->brt_pickup_date_date;
					$btmodel->bkg_pickup_date_time	 = $rtModel->brt_pickup_date_time;
				}
				$rtModel->validate();
				if ($rtModel->hasErrors())
				{
					$errors = $rtModel->getErrors();
					foreach ($errors as $attribute => $error)
					{
						$attribute	 = ($attribute == 'brt_from_city_id') ? 'bkg_from_city_id' : $attribute;
						$attribute	 = ($attribute == 'brt_to_city_id') ? 'bkg_to_city_id' : $attribute;
						foreach ($error as $err)
						{
							if ($btmodel->bkg_booking_type != 2)
							{
								$btmodel->addError($attribute, $err);
							}
						}
					}
				}
			}
			catch (Exception $e)
			{
				$btmodel->addError($attribute, $e->getMessage());
				$result = CActiveForm::validate($btmodel, null, false);
			}
			$i++;
		}
		if (!$btmodel->hasErrors())
		{
			$btmodel->bookingRoutes	 = $brtModels;
			$btmodel->bkg_user_ip	 = \Filter::getUserIP();
			$btmodel->validateRouteTime('bkg_id');
			if (!$btmodel->hasErrors())
			{
				$cookie_name = 'gozo_mff';
				if (isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'mff')
				{
					$btmodel->bkg_tags = 1;
				}
				$btmodel->bookingRoutes	 = $brtModels;
				$btmodel->bkg_to_city_id = $rtModel->brt_to_city_id;
				$models					 = $brtModels + [$btmodel];
				$user_id				 = (Yii::app()->user->getId() > 0) ? Yii::app()->user->getId() : '';
				if ($user_id)
				{
					$usrmodel				 = Users::model()->findByPk($user_id);
					$btmodel->bkg_user_email = $usrmodel->usr_email;
					$btmodel->bkg_contact_no = $usrmodel->usr_mobile;
				}

				$result = CActiveForm::validate($btmodel, null, false);
				if ($result == '[]')
				{
					$btmodel->bkg_platform = Booking::Platform_User;
					if ($user_id)
					{
						$btmodel->bkg_user_id	 = $user_id;
						$usrmodel				 = new Users();
						$usrmodel->resetScope()->findByPk($user_id);
					}
					if (!Yii::app()->user->isGuest)
					{
						$user					 = Yii::app()->user->loadUser();
						$btmodel->bkg_user_id	 = $user->user_id;
						$btmodel->bkg_user_name	 = $user->usr_name;
						$btmodel->bkg_user_lname = $user->usr_lname;
					}
					$btmodel->bkg_user_ip				 = \Filter::getUserIP();
					$cityinfo							 = UserLog::model()->getCitynCountrycodefromIP(\Filter::getUserIP());
					$btmodel->bkg_user_city				 = $cityinfo['city'];
					$btmodel->bkg_user_country			 = $cityinfo['country'];
					$btmodel->bkg_user_device			 = UserLog::model()->getDevice();
					$btmodel->bkg_user_last_updated_on	 = new CDbExpression('NOW()');
					$tmodel								 = Terms::model()->getText(1);
					$btmodel->bkg_tnc_id				 = $tmodel->tnc_id;
					$btmodel->bkg_tnc_time				 = new CDbExpression('NOW()');
					$btmodel->bkg_booking_id			 = 'temp';
					$transaction						 = Yii::app()->db->beginTransaction();
					$result								 = CActiveForm::validate($btmodel, null, false);
					if ($result == '[]')
					{
						try
						{
							if ($btmodel->bkg_id == "")
							{
								$btmodel->bkg_id = null;
							}
							if (!$btmodel->save())
							{
								throw new Exception("Failed to create booking", 101);
							}

							$booking_id				 = BookingTemp::model()->generateBookingid($btmodel);
							$btmodel->bkg_booking_id = $booking_id;
							if (!$btmodel->save())
							{
								throw new Exception("Failed to create booking", 101);
							}
							$bkgid			 = $btmodel->bkg_id;
							$leadRouteArr	 = [];
							foreach ($arrRt as $route)
							{
								$rtModel						 = new BookingRoute();
								$rtModel->brt_bkg_id			 = $bkgid;
								$rtModel->attributes			 = $route;
								$pickupDate						 = DateTimeFormat::DatePickerToDate($route['brt_pickup_date_date']);
								$time							 = DateTime::createFromFormat('h:i A', $route['brt_pickup_date_time'])->format('H:i:00');
								$rtModel->brt_pickup_datetime	 = $pickupDate . " " . $time;
								$leadRouteArr[]					 = array_filter($rtModel->attributes);
							}
							$leadDataArr			 = CJSON::encode($leadRouteArr);
							$btmodel->bkg_route_data = $leadDataArr;

							$btmodel->save();
							$desc		 = "Quote generated by user.";
							$userInfo	 = UserInfo::getInstance();
							$eventid	 = BookingLog::BOOKING_CREATED;
							LeadLog::model()->createLog($bkgid, $desc, $userInfo, '', '', $eventid);
							$transaction->commit();
							$success	 = true;
						}
						catch (Exception $e)
						{
							$btmodel->addError('bkg_id', $e->getMessage());
							$transaction->rollback();
							$result = CActiveForm::validate($btmodel, null, false);
						}
					}
				}
				if (!$success)
				{
					$btmodel = [];
					$btmodel = ["success" => $success, "errors" => CJSON::decode($result)];
				}
			}
			else
			{
				$btmodel = [];
				$btmodel = ["success" => $success, "errors" => $btmodel->getErrors()];
			}
		}
		else
		{
			$btmodel = [];
			$btmodel = ["success" => $success, "errors" => $btmodel->getErrors()];
		}

		return $btmodel;
	}

	public static function getActiveLeads()
	{
		$sql = "SELECT
					booking_temp.bkg_id,
					booking_temp.bkg_pickup_date
				FROM
					`booking_temp`
				WHERE
					bkg_create_date BETWEEN DATE_SUB(NOW(), INTERVAL 90 MINUTE) AND DATE_SUB(NOW(), INTERVAL 30 MINUTE)
					AND booking_temp.bkg_pickup_date > DATE_ADD(NOW(), INTERVAL 6 HOUR)
					AND booking_temp.bkg_follow_up_status = 0
					AND booking_temp.bkg_cron_lead_followup_ctr = 0 
					GROUP BY booking_temp.bkg_user_id ORDER BY booking_temp.bkg_create_date DESC";
		return DBUtil::queryAll($sql);
	}

	public static function leadFollowup($bkgId, $sendEmail = false, $sendSMS = false)
	{
		$success	 = false;
		$transaction = DBUtil::beginTransaction();
		try
		{
			$ctr = 0;
			if ($sendEmail)
			{
				$ctr		 = 1;
				$emailCom	 = new emailWrapper();
				$return		 = $emailCom->leadFollowup($bkgId);
			}
			if ($sendSMS)
			{
				$ctr	 = 1;
				$ext	 = 91;
				$msgCom	 = new smsWrapper();
				$return	 = $msgCom->leadFollowup($ext, $bkgId);
			}
			/* @var $model BookingTemp */
			$model								 = BookingTemp::model()->findByPk($bkgId);
			$model->bkg_last_cron_lead_followup	 = new CDbExpression("NOW()");
			$model->bkg_cron_lead_followup_ctr	 += $ctr;
			$model->bkg_follow_up_status		 = LeadLog::AUTO_FOLLOWUP_SENT;
			$model->bkg_follow_up_on			 = new CDbExpression("NOW()");
			if (!$model->save())
			{
				throw new Exception(json_encode($model->getErrors()), 1);
			}
			$success = true;
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
		}
		return $success;
	}

	/**
	 *
	 * @param integer $bkgId
	 * @return array
	 * @throws Exception
	 */
	public static function updateLeadCountTime($bkgId, $userInfo)
	{
		$success		 = false;
		$linkOpenCount	 = 0;
		try
		{
			/* @var $model BookingTemp */
			$model									 = BookingTemp::model()->findByPk($bkgId);
			$model->bkg_lead_followup_link_open_cnt	 += 1;
			if (!$model->save())
			{
				throw new Exception(json_encode($model->getErrors()), 1);
			}
			$linkOpenCount = $model->bkg_lead_followup_link_open_cnt;
			if ($linkOpenCount == 1)
			{
				$model->bkg_lead_followup_link_open_first_time = new CDbExpression("NOW()");
				if (!$model->save())
				{
					throw new Exception(json_encode($model->getErrors()), 2);
				}
			}
			LeadLog::model()->createLog($bkgId, "Automated lead followup link opened", $userInfo, '', '', BookingLog::AUTOMATED_FOLLOWUP_OPEN);
			$success = true;
			$result	 = ['success' => $success, 'linkOpenCount' => $linkOpenCount];
		}
		catch (Exception $ex)
		{
			$errors		 = $ex->getMessage();
			$errorCode	 = $ex->getCode();
			$result		 = ['success' => $success, 'linkOpenCount' => $linkOpenCount, 'errors' => $errors, 'code' => $errorCode];
		}
		return $result;
	}

	/**
	 *
	 * @param integer $bkgId
	 * @param integer $follow_up_status
	 * @param string $follow_up_cmt
	 * @param integer $event_id
	 * @param array $userInfo
	 * @return array
	 */
	public function updateFollowup($bkgId, $follow_up_status, $follow_up_cmt, $event_id, $userInfo)
	{
		$success							 = false;
		/* @var $leadModel BookingTemp */
		$leadModel							 = BookingTemp::model()->findbyPk($bkgId);
		$userInfo->userId					 = $leadModel->bkg_user_id;
		$leadModel->bkg_follow_up_status	 = $follow_up_status;
		$leadModel->bkg_follow_up_comment	 = $follow_up_cmt;
		$leadModel->bkg_follow_up_on		 = new CDbExpression('NOW()');

		$leadModel->scenario = 'update_followup';
		if ($leadModel->validate() && $leadModel->save())
		{
			LeadLog::model()->createLog($leadModel->bkg_id, $follow_up_cmt, $userInfo, '', $follow_up_status, $event_id);
			$success	 = true;
			$message	 = '';
			$returnSet	 = ['success' => $success, 'message' => $message];
		}
		else
		{
			$errors		 = json_encode($leadModel->getErrors());
			$returnSet	 = ['success' => $success, 'errors' => $errors];
		}
		return $returnSet;
	}

	public function findFollowupStageBreakdown($adminid, $fromDate, $toDate, $state)
	{
		$statusListArr	 = BookingTemp::getLeadStatus($state);
		$strStatusKeys	 = array_keys($statusListArr);
		$statuslist		 = implode(',', $strStatusKeys);
		$sql			 = "select
					COUNT(blg_booking_id) as followupCnt,
					btemp.bkg_follow_up_status as followupStatus,
					if((btemp.bkg_follow_up_status >=0 and btemp.bkg_follow_up_status<=3) or btemp.bkg_follow_up_status=15 or btemp.bkg_follow_up_status=16,'Active',
						if(btemp.bkg_follow_up_status=13,'Conversion','Inactive')
					) as status
				from
					lead_log
				inner join admins on
					adm_id = blg_admin_id
				inner join booking_temp btemp on
					btemp.bkg_id = blg_booking_id
				where
					blg_created between '$fromDate 00:00:00' and '$toDate 23:59:59' and adm_id=$adminid and btemp.bkg_follow_up_status in($statuslist)
				group by
					btemp.bkg_follow_up_status";
		$result			 = DBUtil::queryAll($sql, DBUtil::SDB());
		return $result;
	}

	public function getCityDetails($routeModel)
	{
		$fromCity	 = $routeModel->brt_from_city_id; //$this->bkg_from_city_id;
		$toCity		 = $routeModel->brt_to_city_id; //$this->bkg_to_city_id;

		$fromCityDetails = Cities::model()->findByPk($fromCity);
		$toCityDetails	 = Cities::model()->findByPk($toCity);

		$routeModel->brt_from_city_is_airport	 = $fromCityDetails->cty_is_airport;
		$routeModel->brt_from_city_is_poi		 = $fromCityDetails->cty_is_poi;
//		if ($routeModel->brt_from_city_is_airport == 1 || $routeModel->brt_from_city_is_poi == 1)
//		{
//			$routeModel->brt_from_location = $fromCityDetails->cty_garage_address;
//		}
		$routeModel->brt_to_city_is_airport		 = $toCityDetails->cty_is_airport;
		$routeModel->brt_to_city_is_poi			 = $toCityDetails->cty_is_poi;
//		if ($routeModel->brt_to_city_is_airport == 1 || $routeModel->brt_to_city_is_poi == 1)
//		{
//			$routeModel->brt_to_location = $toCityDetails->cty_garage_address;
//		}
	}

	public function getRelatedPackageQuotes($cab = 0)
	{
		$quotePackages = [];
		if ($this->bkg_booking_type == 3 || $this->bkg_booking_type == 2 || $this->bkg_booking_type == 1)
		{
			$bkgRoutes	 = CJSON::decode($this->bkg_route_data);
			$routeArr[]	 = $bkgRoutes[0]['brt_from_city_id'];
			foreach ($bkgRoutes as $route)
			{
				$routeArr[] = $route['brt_to_city_id'];
			}
			$fdate			 = $bkgRoutes[0]['brt_pickup_datetime'];
			$ldate			 = $bkgRoutes[count($bkgRoutes) - 1]['brt_pickup_datetime'];
			$lduration		 = $bkgRoutes[count($bkgRoutes) - 1]['brt_trip_duration'];
			$dayCount		 = ceil((strtotime($ldate . "+ $lduration MINUTES") - strtotime($fdate)) / (60 * 60 * 24));
			$machingPackages = Package::getMatchingWithRoutes($routeArr, $this->bkg_pickup_date, $dayCount);
			if (sizeof($machingPackages) > 0)
			{
				foreach ($machingPackages as $pack)
				{
					$packId		 = $pack['pcd_pck_id'];
					$packName	 = $pack['pck_name'];
					$rateDetails = PackageRate::getCabRateAddedList($packId, $this->bkg_pickup_date, $cab);
					foreach ($rateDetails as $pckRate)
					{
						$quotePackage												 = $this->getPackageQuotes($packId, $packName, $pckRate['prt_package_cab_type']);
						$quotePackages[$packId][$pckRate['prt_package_cab_type']]	 = $quotePackage[$pckRate['prt_package_cab_type']];
					}
				}
			}
		}
		return $quotePackages;
	}

	public function deactivateRelatedLeads($id)
	{
		$model			 = Booking::model()->findByPk($id);
		$fromCityIds	 = ZoneCities::getRelatedcities($model->bkg_from_city_id);
		$fromAllCities	 = $fromCityIds['all_city'];
		$toCityIds		 = ZoneCities::getRelatedcities($model->bkg_to_city_id);
		$toAllCities	 = $toCityIds['all_city'];
		$userInfo		 = new UserInfo();
		$followStatus	 = 4;
		$eventid		 = 4;
		// Lead
		$sql			 = "SELECT bkg_id, bkg_follow_up_status FROM booking_temp
					WHERE bkg_from_city_id IN ($fromAllCities) AND bkg_to_city_id IN ($toAllCities)
					AND (DATE(bkg_create_date) = DATE(:bkgCreateDate) OR DATE(bkg_pickup_date) = DATE(:bkgPickupDate))
					AND ((bkg_contact_no=:bkgContactNo AND bkg_contact_no <> '') OR (bkg_user_email=:bkgUserEmail AND bkg_user_email <> ''))
					AND bkg_follow_up_status IN (0,1,2,3) AND (bkg_ref_booking_id != :bkgId OR bkg_ref_booking_id IS NULL)";

		$arrParams					 = [];
		$arrParams['bkgId']			 = $id;
		$arrParams['bkgCreateDate']	 = $model->bkg_create_date;
		$arrParams['bkgPickupDate']	 = $model->bkg_pickup_date;
		$arrParams['bkgContactNo']	 = $model->bkgUserInfo->bkg_contact_no;
		$arrParams['bkgUserEmail']	 = $model->bkgUserInfo->bkg_user_email;

		$models = $this->findAllBySql($sql, $arrParams);
		if (count($models) > 0)
		{
			foreach ($models as $model)
			{
				$model->bkg_follow_up_status = $followStatus;
				$model->save();
				$desc						 = "Booking created : " . $id;
				LeadLog::model()->createLog($model->bkg_id, $desc, $userInfo, '', $followStatus, $eventid, $id);
			}
		}
	}

	public function populateFromCabAvailabilities($cavid, $pickupDate = '')
	{
		$model				 = new BookingTemp();
		$cavData			 = CabAvailabilities::getDetails($cavid);
		$estimatedPickupTime = $cavData['cav_date_time'];
		if ($pickupDate != '')
		{
			$estimatedPickupTime = $pickupDate;
			$startDateObj		 = new DateTime($cavData['cav_date_time']);
			$endDateObj			 = new DateTime($cavData['cav_expire']);
			$pickDateObj		 = new DateTime($pickupDate);
			if ($pickDateObj < $startDateObj)
			{
				$estimatedPickupTime = $cavData['cav_date_time'];
			}
			else if ($pickDateObj > $endDateObj)
			{
				$estimatedPickupTime = $cavData['cav_expire'];
			}
		}
		$model->bkg_pickup_date	 = $estimatedPickupTime;
		$model->bkg_from_city_id = $cavData['cav_from_city'];
		$model->bkg_to_city_id	 = $cavData['cav_to_cities'];
		if ($model->bkgFromCity->cty_is_airport == 1)
		{
			$model->bkg_pickup_address = $cavData['cav_from_city_name'];
		}
		$model->bkg_cav_id			 = $cavid;
		$model->bkg_booking_type	 = 1;
		$model->bkg_vehicle_type_id	 = $cavData['cabType'];
		$model->bookingRoutes		 = BookingRoute::model()->populateByCavData($cavData, $estimatedPickupTime);
		return $model;
	}

	public function getUserbyId($bkgId, $agentId = 0)
	{
		$where	 = $agentId == 0 ? " " : "  AND bkg_agent_id=$agentId ";
		$sql	 = "SELECT 
					usr_created_at,
					bkg_id,
					CONCAT(bkg_user_name, ' ', bkg_user_lname) as user_name,
					`bkg_user_id`, 
					`bkg_user_name`, 
					`bkg_user_lname`,
					`bkg_country_code`, 
					`bkg_contact_no`, 
					`bkg_user_email`as email
					FROM booking_temp
					LEFT JOIN users on user_id=bkg_user_id
					WHERE bkg_id =:bkgId $where";
		return DBUtil::queryRow($sql, DBUtil::SDB(), ['bkgId' => $bkgId]);
	}

	public function getUserbyemailphone($email, $phone)
	{
		$params	 = ['phone' => $phone, 'email' => $email];
		$sql	 = "SELECT user_id  FROM `users` WHERE  usr_mobile = '$phone' OR usr_email ='$email'";
		$result	 = DBUtil::command($sql, DBUtil::SDB())->queryRow($params);
		return $result;
	}

	/**
	 * This function is used for getting the last Id
	 * @param type $userId
	 * @param type $email
	 * @param type $phone
	 * @return type
	 */
	public static function getLastIdByUserElement($userId, $email, $phone)
	{
		$params	 = [':userId' => $userId, ':phone' => $phone, ':email' => $email];
		$sql	 = "SELECT bkg_id
		FROM booking_temp
		WHERE bkg_contact_no =:phone AND bkg_user_email =:email AND bkg_user_id =:userId
		ORDER BY bkg_id DESC LIMIT 1";
		return DBUtil::queryRow($sql, DBUtil::SDB(), $params);
	}

	public function getLeadbyuserid($userid = NULL, $email, $phone)
	{

		$params	 = ['userid' => $userid, 'phone' => $phone];
		$sql	 = "SELECT bkg_id
		FROM booking_temp
		WHERE bkg_contact_no = '$phone'";
		$result	 = DBUtil::queryAll($sql, DBUtil:: SDB(), $params);

		$rows = [];
		foreach ($result as $val)
		{
			$rows[] = $val['bkg_id'];
		}

		//$rows;
		$results = implode(",", $rows);
		return $results;
	}

	public static function getActiveAssignedLeads($csrId)
	{
		$date1	 = date('Y-m-d', strtotime(' -1 day'));
		$date2	 = date('Y-m-d');
		$params	 = ['csrId' => $csrId, 'date1' => $date1, 'date2' => $date2];
		$sql	 = "SELECT  *   FROM   booking_temp
			WHERE ((bkg_lead_source IS NOT NULL AND bkg_lead_source > 0 )
				 OR bkg_contact_no <> ''  OR bkg_log_email <> '' OR bkg_log_phone <> '' )
				 AND    bkg_pickup_date >=  CONCAT(DATE(DATE_SUB(CURDATE(), INTERVAL 5 DAY)), ' 00:00:00')
				AND bkg_follow_up_status IN(0, 1, 2, 3, 15, 16, 20, 21)
			 AND ( bkg_follow_up_reminder < NOW() OR bkg_follow_up_reminder IS NULL OR trim(bkg_follow_up_reminder)= '')
                AND  bkg_create_date BETWEEN '$date1 00:00:00' AND '$date2 23:59:59'
                AND bkg_assigned_to = '$csrId'
                ORDER BY bkg_id desc";
		return $result	 = DBUtil::queryAll($sql, DBUtil:: SDB(true), $params);
	}

	public static function getCountPendingLeads($csrId)
	{
		$date1	 = date('Y-m-d', strtotime(' -1 day'));
		$date2	 = date('Y-m-d');
		$params	 = ['csrId' => $csrId, 'date1' => $date1, 'date2' => $date2];
		$sql	 = "SELECT  COUNT(*) AS cntleads FROM booking_temp
       WHERE ((bkg_lead_source IS NOT NULL AND bkg_lead_source > 0 )
				 OR bkg_contact_no <> ''  OR bkg_log_email <> '' OR bkg_log_phone <> '' )
				 AND    bkg_pickup_date >=  CONCAT(DATE(DATE_SUB(CURDATE(), INTERVAL 5 DAY)), ' 00:00:00')
				AND bkg_follow_up_status IN(0, 1, 2, 3, 15, 16, 20, 21)
			 AND ( bkg_follow_up_reminder < NOW() OR  bkg_follow_up_reminder IS NULL OR trim(bkg_follow_up_reminder)= '')
                AND  bkg_create_date BETWEEN '$date1 00:00:00' AND '$date2 23:59:59'

                AND bkg_assigned_to = '$csrId'
                ";
		return $result	 = DBUtil::queryAll($sql, DBUtil:: SDB(), $params);
	}

	public static function getRelatedLeadIds($userid, $useremail, $usercontact, $agentId = 0)
	{
		$where	 = $agentId == 0 ? " " : "  AND bkg.bkg_agent_id=$agentId ";
		$params	 = ['userid' => $userid, 'useremail' => $useremail, 'usercontact' => $usercontact];
		$sql	 = "SELECT  DISTINCT bkg.bkg_id
				FROM booking_temp bkg
				WHERE
					(
						(bkg.bkg_user_email <> '' AND bkg.bkg_user_email = :useremail)
						OR
						(bkg.bkg_contact_no <> '' AND bkg.bkg_contact_no = :usercontact)
						OR
						(bkg.bkg_user_id <> '' AND bkg.bkg_user_id = :userid)
					)
                    AND trim(bkg.bkg_user_ip) <> ''
					AND bkg.bkg_contact_no <> ''
					AND bkg_pickup_date > NOW()
					AND bkg_assigned_to IS NULL
					$where
					";

		return DBUtil::query($sql, DBUtil::MDB(), $params);
	}

	public static function assignedIds($leadList, $csr, $leadId, $agentId = 0)
	{
		$success = false;
		if (!$leadList)
		{
			goto end;
		}
		$aname = Admins::model()->findByPk($csr)->getName();

		$where = $agentId == 0 ? " AND bkg_agent_id IS NULL " : " AND bkg_agent_id=$agentId";
		foreach ($leadList as $leadArr)
		{
			$lead	 = $leadArr['bkg_id'];
			$sql	 = "UPDATE booking_temp SET bkg_assigned_to=:csr WHERE bkg_id =:leadid $where";
			$numrows = DBUtil::execute($sql, ['csr' => $csr, 'leadid' => $lead]);

			if ($numrows != 0)
			{

				$desc		 = "Related Lead assigned to $aname (Source Lead: $leadId)";
				$userInfo	 = UserInfo::model();
				LeadLog::model()->createLog($lead, $desc, $userInfo);
			}
		}
		$success = true;
		end:
		return $success;
	}

	public static function assignedIds1($leadIds, $csr, $leadId)
	{
		$success = false;
		if (!$leadIds)
		{
			goto end;
		}
		$params	 = ['csr' => $csr];
		DBUtil::getINStatement($leadIds, $bindString, $params1);
		$sql	 = "UPDATE booking_temp SET bkg_assigned_to=:csr WHERE bkg_id IN ($bindString) AND bkg_agent_id IS NULL";
		$numrows = DBUtil::execute($sql, array_merge($params, $params1));
		if ($numrows == 0)
		{
			goto end;
		}

		$arrLead = explode(",", $leadIds);
		foreach ($arrLead as $lead)
		{
			$aname		 = Admins::model()->findByPk($csr)->getName();
			$desc		 = "Related Lead assigned to $aname (Source Lead: $leadId)";
			$userInfo	 = UserInfo::model();
			LeadLog::model()->createLog($lead, $desc, $userInfo);
		}
		$success = true;
		end:
		return $success;
	}

	/**
	 * This function is used for assigning the lead bookings
	 * @param type $refId
	 * @param type $csr
	 */
	public static function assignLD($refId, $csr)
	{
		$bkgId		 = self::model()->assignCSR($refId, $csr);
		$admin		 = Admins::model()->findByPk($csr);
		$aname		 = $admin->adm_fname;
		$desc		 = "Lead assigned to $aname (Auto Assign)";
		$userInfo	 = UserInfo::getInstance();
		LeadLog::model()->createLog($bkgId, $desc, $userInfo);
		return $desc;
	}

	/**
	 * This function is used for getting the quote for
	 * @param type $jsonObj
	 * @return type
	 * @throws Exception
	 */
	public static function getQuoteForBot($jsonObj)
	{
		$returnSet = new ReturnSet();
		try
		{
			$jsonMapper		 = new JsonMapper();
			$userData		 = UserInfo::getInstance();
			/* @var $obj Stub\booking\UserQuoteRequest */
			$obj			 = $jsonMapper->map($jsonObj, new \Stub\booking\UserQuoteRequest());
			/** @var Booking $model */
			$model			 = $obj->getModelData();
			$leadPhone		 = $obj->userInfo->primaryContact->number;
			$leadEmail		 = $obj->userInfo->email;
			$leadPhoneCode	 = $obj->userInfo->primaryContact->code;
			$success		 = BookingTemp::createLeadModel($model, $userData, $leadPhone, $leadEmail, $leadPhoneCode, Booking::Platform_Bot);
			#$model->platform = Booking::Platform_Bot;
			$model->platform = Quote::Platform_Bot;
			if (!$success)
			{
				goto handleErrors;
			}
			$quotData				 = Quote::populateFromModel($model, $model->bkg_vehicle_type_id, $checkBestRate			 = false, $includeNightAllowance	 = true, $isAllowed				 = true);

			$response	 = new \Stub\booking\QuoteResponse();
			$response->setData($quotData);
			$data		 = Filter::removeNull($response);
			if ($data == null)
			{
				throw new Exception(CJSON::encode("No cabs are available."), ReturnSet::ERROR_VALIDATION);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($data);
			Logger::create("Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $e)
		{
			handleErrors:
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($e);
		}
		return $returnSet;
	}

	/**
	 * Function for archiving Booking Temp
	 */
	public function archiveBookingTempData($archiveDB, $upperLimit = 100000, $lowerLimit = 1000)
	{
		$i			 = 0;
		$chk		 = true;
		$totRecords	 = $upperLimit;
		$limit		 = $lowerLimit;
		while ($chk)
		{
			$transaction = "";
			try
			{
				$sql	 = "SELECT GROUP_CONCAT(bkg_id) AS bkg_id FROM (SELECT bkg_id FROM booking_temp WHERE 1 AND bkg_pickup_date   < CONCAT(DATE_SUB(CURDATE(), INTERVAL 1 Year), ' 00:00:00') ORDER BY bkg_id LIMIT 0, $limit) as temp";
				$resQ	 = DBUtil::queryScalar($sql);
				if (!is_null($resQ) && $resQ != '')
				{
					$result = LeadLog::archiveLeadLogData($archiveDB, $resQ);
					if ($result)
					{
						$transaction = DBUtil::beginTransaction();
						DBUtil::getINStatement($resQ, $bindString, $params);
						$sql		 = "INSERT INTO " . $archiveDB . ".booking_temp (SELECT * FROM booking_temp WHERE bkg_id IN ($bindString))";
						$rows		 = DBUtil::execute($sql, $params);
						if ($rows > 0)
						{
							$sql = "DELETE FROM `booking_temp` WHERE bkg_id IN ($bindString)";
							DBUtil::execute($sql, $params);
							DBUtil::commitTransaction($transaction);
						}
						else
						{
							DBUtil::rollbackTransaction($transaction);
						}
					}
				}

				$i += $limit;
				if (($resQ <= 0) || $totRecords <= $i)
				{
					break;
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
				echo $ex->getMessage() . "\n\n";
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	/**
	 * This function is used for UNassigning the lead bookings
	 * @param type $refId
	 * @param type $csr
	 * @return string description
	 */
	public static function unassignLD($refId, $csr)
	{
		$bkgId		 = self::model()->unassignCSR($refId);
		$admin		 = Admins::model()->findByPk($csr);
		$aname		 = $admin->adm_fname;
		$desc		 = "Lead unassigned from $aname";
		$userInfo	 = UserInfo::getInstance();
		LeadLog::model()->createLog($bkgId, $desc, $userInfo);
		return $desc;
	}

	/**
	 * This function is used for unassignment of  booking Temp
	 * @param integer $leads
	 * @param integer $csr
	 * @param integer $leadId
	 * @return boolean
	 */
	public static function unassignedIds($leads, $csr, $leadId, $agentId = 0)
	{
		$success = false;
		if (!$leads)
		{
			goto end;
		}
		$aname		 = Admins::model()->findByPk($csr)->getName();
		$leadList	 = explode(",", $leads);
		$where		 = $agentId == 0 ? " AND bkg_agent_id IS NULL " : " AND bkg_agent_id=$agentId ";
		foreach ($leadList as $lead)
		{
			$sql	 = "UPDATE booking_temp SET bkg_assigned_to=:csr WHERE bkg_id =:leadid $where ";
			$numrows = DBUtil::execute($sql, ['csr' => null, 'leadid' => $lead]);
			if ($numrows != 0)
			{

				$desc		 = "Related Lead unassigned from $aname (Source Lead: $leadId)";
				$userInfo	 = UserInfo::model();
				LeadLog::model()->createLog($lead, $desc, $userInfo);
			}
		}
		$success = true;
		end:
		return $success;
	}

	public function unassignCSR($bkid)
	{
		if ($bkid != '' && $bkid != 0)
		{
			$model					 = BookingTemp::model()->findByPk($bkid);
			$model->bkg_assigned_to	 = null;
			if ($model->update())
			{
				return $model->bkg_id;
			}
			throw new Exception("Failed to unassigning csr: " . json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
	}

	public static function getAllActiveAssignedLeads($bkgIds)
	{
		$sql = "SELECT  bkg_id FROM   booking_temp WHERE 1
                AND bkg_follow_up_status IN(0, 1, 2, 3, 15, 16, 20, 21)
                AND  bkg_create_date < NOW()
                AND ( bkg_follow_up_reminder < NOW() OR bkg_follow_up_reminder IS NULL OR trim(bkg_follow_up_reminder)= '')
                AND bkg_id IN ($bkgIds)";
		return DBUtil::query($sql, DBUtil:: MDB());
	}

	public static function markReadLead($leadList)
	{
		$success = false;
		foreach ($leadList as $lead)
		{
			$bkgid	 = $lead['bkg_id'];
			$sql	 = "UPDATE booking_temp SET bkg_follow_up_status=5 WHERE bkg_id =:leadid";
			$numrows = DBUtil::execute($sql, ['leadid' => $bkgid]);
		}
		$success = true;
		end:
		return $success;
	}

	/**
	 * Returns the row the from booking temp based on  Eligible For NewLead AND Lead  Eligible Score
	 * @param integer $limit.
	 * @param integer $isEligibleForNewLead.
	 * @param integer $elgibileScore.
	 * @return query objects
	 */
	public static function getPendingLeadsCron($limit, $isEligibleForNewLead, $elgibileScore, $agentId = 0)
	{
		$having = "";
		if ($isEligibleForNewLead)
		{
			$where	 = " AND (csrRank + timeRank + advanceRank + pickupRank + followup_rank+loginRank) >$elgibileScore ";
			$having	 = " HAVING (csrRank + timeRank + advanceRank + pickupRank + followup_rank+loginRank)>$elgibileScore";
		}
		else
		{
			$where	 = " AND (csrRank + timeRank + advanceRank + pickupRank + followup_rank+loginRank) <=$elgibileScore ";
			$having	 = " HAVING (csrRank + timeRank + advanceRank + pickupRank + followup_rank+loginRank)<=$elgibileScore";
		}
		$whereAgent	 = $agentId == 0 ? " AND bkg_agent_id IS NULL  " : " AND bkg_agent_id=$agentId ";
		$limit		 = $limit * 15;
		$sql		 = "SELECT * FROM (
		(SELECT
                    bkg_id,
					bkg_agent_id,
					IF(TIMESTAMPDIFF(MINUTE, bkg_create_date,bkg_pickup_date ) <=240,1,0) AS lastMinBooking,
                    bkg_booking_id,
                    bkg_user_id,
                    bkg_country_code,
                    CONCAT(bkg_country_code,bkg_contact_no) as  bkg_contact_no,
                     IF(bkg_user_id>0,10,0) AS loginRank,
                      0 as csrRank,
                      CASE
                        WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 20 AND 35 THEN (40)
                        WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 35 AND 45 THEN (50)
                        WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 45 AND 60 THEN (45)
                        WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 60 AND 120 THEN (40)
                        WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 120 AND 720 THEN 35
                        WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 720 AND 1440 THEN 25
                        WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 1440 AND 2880 THEN 10
                        ELSE -10
                      END AS timeRank,
                      CASE
                        WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 120 AND 150 THEN 20
                        WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 150 AND 180 THEN 35
                        WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 180 AND 400 THEN 50
                        WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 400 AND 600 THEN 55
                        WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 600 AND 2880 THEN 60
                        WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 2880 AND 5760 THEN 50
                        WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 5760 AND 8640 THEN 40
                        WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 8640 AND 11520 THEN 35
                        WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 11520 AND 28800 THEN 25
                        WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 28800 AND 43200 THEN 15
                        ELSE 0
                      END AS pickupRank, 0 AS advanceRank,
                      IF(bkg_follow_up_status=20,15,0) AS followup_rank, 1 as type, 0 AS refType
				FROM     booking_temp
				WHERE 1  AND bkg_follow_up_status <> 14 
					AND  bkg_create_date<=DATE_SUB(NOW(), INTERVAL IF(HOUR(NOW())>21, 15, 
									IF(TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date)<480 OR CalcWorkingMinutes(NOW(), bkg_pickup_date)<120, 
											LEAST(ROUND(TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date)*0.4), 60), 30)) MINUTE) 
					AND (HOUR(NOW()) <= 21 OR (HOUR(NOW())>21 AND TIMESTAMPDIFF(MINUTE, bkg_create_date, now())<45))
					AND  (bkg_assigned_to = 0 OR bkg_assigned_to IS NULL)
					AND bkg_follow_up_status IN (0,21, 20) AND bkg_pickup_date > NOW()
					AND (bkg_contact_no <> '' OR bkg_log_phone <> '')
					$whereAgent
					$having
				ORDER BY (csrRank + timeRank + advanceRank + pickupRank + followup_rank+loginRank) DESC, bkg_pickup_date ASC, bkg_create_date DESC
				LIMIT 0,6
		)
		UNION
		(
		SELECT   bkg_id,
				bkg_agent_id,
					IF(TIMESTAMPDIFF(MINUTE, bkg_create_date,bkg_pickup_date ) <=240,1,0) AS lastMinBooking,
                        bkg_booking_id,
                        bkg_user_id,
                        bkg_country_code,
                        CONCAT(bkg_country_code,bkg_contact_no) as  bkg_contact_no,
                        0 AS loginRank,
			0 as csrRank,
			CASE
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 25 AND 35 THEN 0
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 35 AND 60 THEN 20
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 60 AND 720 THEN 15
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 720 AND 1440 THEN 10
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 1440 AND 2880 THEN 0
			  ELSE -15
			END AS timeRank,
			CASE
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 210 AND 1440 THEN 30
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 1440 AND 2880 THEN 25
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 2880 AND 5760 THEN 20
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 5760 AND 8640 THEN 15
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 8640 AND 11520 THEN 15
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 11520 AND 28800 THEN 10
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 28800 AND 43200 THEN 5
			  ELSE -10
			END AS pickupRank,
			0 AS advanceRank,
			CASE
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_follow_up_reminder, NOW()) BETWEEN 0 AND 90 THEN 20
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_follow_up_reminder, NOW()) BETWEEN 90 AND 720 THEN 15
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_follow_up_reminder, NOW()) BETWEEN 720 AND 1440 THEN 5
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_follow_up_reminder, NOW()) BETWEEN 1440 AND 2880 THEN 0
			ELSE -15
			END AS followup_rank,
			1 as type, 0 AS refType
			FROM     booking_temp
            WHERE  1 AND bkg_follow_up_status <> 14 AND   bkg_create_date<=DATE_SUB(NOW(), INTERVAL 30 MINUTE) AND  (HOUR(NOW()) <= 21 OR (HOUR(NOW())>21 AND TIMESTAMPDIFF(MINUTE, bkg_create_date, now())<45))
					AND bkg_follow_up_status IN (1,2,3)
					AND (`bkg_follow_up_reminder`< NOW()) AND bkg_pickup_date > NOW()
					AND ((bkg_assigned_to = 0 OR bkg_assigned_to IS NULL
						OR DATE_ADD(bkg_follow_up_reminder, INTERVAL IF(bkg_assigned_to=0,0,45) MINUTE) <NOW()))
			AND (bkg_contact_no <> '' OR bkg_log_phone <> '')
			$whereAgent

			ORDER BY (csrRank + timeRank + advanceRank + pickupRank + followup_rank+loginRank) DESC, bkg_pickup_date ASC, bkg_create_date DESC
			LIMIT 0,6
		)
		UNION
		(
		SELECT
                bkg_id,
				bkg_agent_id,
				IF(TIMESTAMPDIFF(MINUTE, bkg_create_date,bkg_pickup_date ) <=240,1,0) AS lastMinBooking,	
                bkg_booking_id,
                bkg_user_id,
		bkg_country_code,
                CONCAT(bkg_country_code,bkg_contact_no) as  bkg_contact_no,
                 IF(bkg_user_id>0,10,0) AS loginRank,
		0 as csrRank,
		CASE
			WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 20 AND 30 THEN 35
			WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 30 AND 60 THEN 50
			WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 60 AND 120 THEN 45
			WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 120 AND 720 THEN 40
			WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 720 AND 1440 THEN 30
			WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 1440 AND 2880 THEN 20
			WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 2880 AND 5760 THEN 0
			ELSE -20
		  END AS timeRank,
		  CASE
			WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 180 AND 720 THEN 60
			WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 720 AND 1440 THEN 50
			WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 1440 AND 1440*3 THEN 55
			WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 1440*3 AND 1440*6 THEN 40
			WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 1440*6 AND 1440*10 THEN 35
			WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 1440*10 AND 1440*15 THEN 25			  
			WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 1440*15 AND 1440*21 THEN 15			  
			WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 150 AND 180 THEN 40
			ELSE 0
		  END AS pickupRank,
		CASE
		  WHEN bi.bkg_gozo_amount > 4000 THEN (25)
		  WHEN bi.bkg_gozo_amount BETWEEN 1000 AND 3000 THEN (20)
		  WHEN bi.bkg_gozo_amount BETWEEN 500 AND 1000 THEN 15
		  WHEN bi.bkg_gozo_amount BETWEEN 300 AND 500 THEN 10
		  ELSE 0
		END AS advanceRank,
		IF(bt.bkg_follow_type_id=10 AND btr_unv_followup_by IS NULL , 30, 0) AS followup_rank,
			2 as type,
			0 AS refType
		FROM     booking INNER JOIN booking_trail bt ON booking.bkg_id= bt.btr_bkg_id
		INNER JOIN  booking_invoice bi ON  bi.biv_bkg_id = bt.btr_bkg_id
		INNER JOIN booking_user bui on bui_bkg_id = bkg_id AND bui.bkg_contact_no <> ''
		WHERE
			bkg_create_date<=DATE_SUB(NOW(), INTERVAL 20 MINUTE) 
			AND  (HOUR(NOW()) <= 21 OR (HOUR(NOW())>21 AND TIMESTAMPDIFF(MINUTE, bkg_create_date, now())<45))
			AND (bt.bkg_assign_csr = 0 OR bt.bkg_assign_csr IS NULL)
			AND bkg_status IN (15) AND bkg_pickup_date > NOW()
			AND  bt.bkg_create_type=3
			AND (bkg_followup_date IS NULL OR bkg_followup_date < NOW())
			$whereAgent
		ORDER BY (csrRank + timeRank + advanceRank + pickupRank + followup_rank+loginRank) DESC, bkg_pickup_date ASC,
				bkg_create_date DESC LIMIT 0,6)

		) a  WHERE  1 $where  order by (csrRank + timeRank + pickupRank+loginRank) DESC, refType DESC LIMIT  $limit,10";
		return DBUtil::query($sql);
	}

	public static function checkAdminGozoNowEligibility($fcity, $tcity, $pickupDateTime, $bkgType, $bkgVehicleType, $agtId = '')
	{


		$timeNow						 = Filter::getDBDateTime();
		$diff							 = floor((strtotime($pickupDateTime) - strtotime($timeNow)) / 60);
		$tempModel						 = new BookingTemp();
		$tempModel->bkg_pickup_date		 = $pickupDateTime;
		$tempModel->bkg_from_city_id	 = $fcity;
		$tempModel->bkg_to_city_id		 = $tcity;
		$tempModel->bkg_booking_type	 = $bkgType;
		$tempModel->bkg_vehicle_type_id	 = $bkgVehicleType;
		$tempModel->bkg_agent_id		 = $agtId;
		$result							 = ['success' => false];
		if ($agtId > 0)
		{
			goto skipAll;
		}

		$gnowCabCatList = SvcClassVhcCat::getCabListGNowQuote();

		$gnowEleResponse = $tempModel->checkGozoNowEligibility();

		if ($tempModel->bkg_vehicle_type_id > 0)
		{
			$svcModel	 = SvcClassVhcCat::model()->findByPk($tempModel->bkg_vehicle_type_id);
			$tier		 = $svcModel->scv_scc_id;
		}
		$minPickTime = Config::getMinPickupDuration($tempModel->bkg_agent_id, $tempModel->bkg_booking_type, $tier);

		$normalValidity = $tempModel::checkTime($tempModel);

		$maxGNOw = min($minPickTime, $gnowEleResponse->maxGNowAllowedDuration);

		$minTime = min($gnowEleResponse->timeDifference, $minPickTime, $normalValidity->timeDifference);
		$maxTime = max($gnowEleResponse->timeDifference, $maxGNOw, $normalValidity->timeDifference);

		if ($diff >= $minTime && $diff <= $maxTime && $gnowEleResponse->isAllowed && in_array($tempModel->bkg_vehicle_type_id, $gnowCabCatList))
		{
			$result['success'] = true;
		}
		else
		{
			$amount		 = 100;
			$toCities[]	 = $tcity;
			$row		 = PriceSurge::getByCitynPickupDate($fcity, $toCities, $amount, $pickupDateTime, $bkgVehicleType, $bkgType);
			if ($row['prc_is_gnow_applicable'])
			{
				$result['success'] = true;
			}
		}

		skipAll:
		return $result;
	}

	public static function getBookingCountByRowIdentifier($rowIdentifier)
	{

		$params					 = array();
		$params['regionId']		 = (int) substr($rowIdentifier, 1, 2);
		$params['fromZone']		 = (int) substr($rowIdentifier, 3, 5);
		$params['toZone']		 = (int) substr($rowIdentifier, 8, 5);
		$params['bookingType']	 = (int) substr($rowIdentifier, 16, 2);

		$sql = "SELECT 
                COUNT(bkg_id) AS cntLead
                FROM booking_temp bkg
                JOIN cities a ON a.cty_id = bkg.bkg_from_city_id AND a.cty_active=1
                JOIN cities b ON b.cty_id = bkg.bkg_to_city_id   AND b.cty_active=1
                JOIN states stt ON stt.stt_id = a.cty_state_id  AND stt.stt_active='1'
                JOIN states s2 ON s2.stt_id = b.cty_state_id  AND s2.stt_active='1'
                JOIN zone_cities zc1 ON zc1.zct_cty_id = bkg.bkg_from_city_id AND zc1.zct_active=1
                JOIN zone_cities zc2 ON zc2.zct_cty_id = bkg.bkg_to_city_id  AND zc2.zct_active=1
                JOIN zones z1 ON z1.zon_id = zc1.zct_zon_id  AND z1.zon_active=1
                JOIN zones z2 ON z2.zon_id = zc2.zct_zon_id  AND z2.zon_active=1
                JOIN geo_zones1 gz1 ON z1.zon_id = gz1.zon_id  
                JOIN geo_zones1 gz2 ON z2.zon_id = gz2.zon_id
                WHERE 1 AND bkg.bkg_create_date BETWEEN (CURDATE() - INTERVAL 01 DAY) AND CURDATE() 
                AND stt.stt_zone=:regionId
                AND z1.zon_id=:fromZone
                AND z2.zon_id=:toZone
                AND bkg.bkg_booking_type=:bookingType";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}

	public static function getBookingCountByRowIdentifierDump($rowIdentifier, $fromDate, $toDate)
	{
//        Logger::info("\n****************** Booking Temp getBookingCountByRowIdentifierDump Start********************\n");
		try
		{
			$params					 = array();
			$params['regionId']		 = (int) substr($rowIdentifier, 1, 2);
			$params['fromZone']		 = (int) substr($rowIdentifier, 3, 5);
			$params['toZone']		 = (int) substr($rowIdentifier, 8, 5);
			$params['bookingType']	 = (int) substr($rowIdentifier, 16, 2);
			$params['fromDate']		 = $fromDate;
			$params['toDate']		 = $toDate;
//            Logger::info("\n****************** Booking TempgetBookingCountByRowIdentifierDump params=" . json_encode($params) . "********************\n");
			$sql					 = "SELECT 
                COUNT(bkg_id) AS cntLead
                FROM booking_temp bkg
                JOIN cities a ON a.cty_id = bkg.bkg_from_city_id AND a.cty_active=1
                JOIN cities b ON b.cty_id = bkg.bkg_to_city_id   AND b.cty_active=1
                JOIN states stt ON stt.stt_id = a.cty_state_id  AND stt.stt_active='1'
                JOIN states s2 ON s2.stt_id = b.cty_state_id  AND s2.stt_active='1'
                JOIN zone_cities zc1 ON zc1.zct_cty_id = bkg.bkg_from_city_id AND zc1.zct_active=1
                JOIN zone_cities zc2 ON zc2.zct_cty_id = bkg.bkg_to_city_id  AND zc2.zct_active=1
                JOIN zones z1 ON z1.zon_id = zc1.zct_zon_id  AND z1.zon_active=1
                JOIN zones z2 ON z2.zon_id = zc2.zct_zon_id  AND z2.zon_active=1
                JOIN geo_zones1 gz1 ON z1.zon_id = gz1.zon_id  
                JOIN geo_zones1 gz2 ON z2.zon_id = gz2.zon_id
                WHERE 1 AND bkg.bkg_create_date BETWEEN :fromDate AND :toDate  
                AND stt.stt_zone=:regionId
                AND z1.zon_id=:fromZone
                AND z2.zon_id=:toZone
                AND bkg.bkg_booking_type=:bookingType";
//            Logger::info("\n****************** Booking Temp  getBookingCountByRowIdentifierDump sql=" . $sql . "********************\n");
			return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		}
		catch (Exception $ex)
		{
			Logger::writeToConsole($ex->getMessage());
//            Logger::info("\n****************** Booking Temp getBookingCountByRowIdentifierDump  Exception=" . $ex->getMessage() . " *********************************************\n");
		}
//        Logger::info("\n****************** Booking Temp getBookingCountByRowIdentifierDump Ends********************\n");
	}

	/**
	 * Returns the row the from booking temp based on  Eligible For NewLead  where time diff between create and pickup is less than 240 min
	 * @param integer $limit.
	 * @return query objects
	 */
	public static function getLastMinPendingLeadsCron($limit)
	{
		$where	 = " AND TIMESTAMPDIFF(MINUTE, bkg_create_date,bkg_pickup_date ) <=240  ";
		$limit	 = $limit * 15;
		$sql	 = "SELECT * FROM
					(
						(
							SELECT
							bkg_id,
							IF(TIMESTAMPDIFF(MINUTE, bkg_create_date,bkg_pickup_date ) <=240,1,0) AS lastMinBooking,
							bkg_booking_id,
							bkg_user_id,
							bkg_country_code,
							CONCAT(bkg_country_code,bkg_contact_no) as  bkg_contact_no,
							IF(bkg_user_id>0,10,0) AS loginRank,
							0 as csrRank,
							CASE
								WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 15 AND 30 THEN (55)
								WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 30 AND 40 THEN (55)
								WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 40 AND 60 THEN (50)
								WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 60 AND 120 THEN (40)
								WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 120 AND 720 THEN 35
								WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 720 AND 1440 THEN 25
								WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 1440 AND 2880 THEN 10
								ELSE -30
							END AS timeRank,
							CASE
								WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 180 AND 400 THEN 55
								WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 120 AND 180 THEN 45
								WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 400 AND 600 THEN 55
								WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 600 AND 2880 THEN 60
								WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 2880 AND 5760 THEN 50
								WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 5760 AND 8640 THEN 40
								WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 8640 AND 11520 THEN 35
								WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 11520 AND 28800 THEN 25
								WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 28800 AND 43200 THEN 15
								ELSE 0
							END AS pickupRank, 0 AS advanceRank,
							IF(bkg_follow_up_status=20,15,0) AS followup_rank,
							1 as type, 
							0 AS refType
							FROM     booking_temp
							WHERE 1 
							$where
							AND bkg_follow_up_status <> 14 
							AND  bkg_create_date>=DATE_SUB(NOW(), INTERVAL  60 MINUTE)
							AND  (bkg_assigned_to = 0 OR bkg_assigned_to IS NULL)
							AND bkg_follow_up_status IN (0,21, 20) AND bkg_pickup_date > NOW()
							AND (bkg_contact_no <> '' OR bkg_log_phone <> '')
						    ORDER BY (csrRank + timeRank + advanceRank + pickupRank + followup_rank+loginRank) DESC, bkg_pickup_date ASC, bkg_create_date DESC
						    LIMIT 0,15
						)
						UNION
						(
							SELECT   
							bkg_id,
							IF(TIMESTAMPDIFF(MINUTE, bkg_create_date,bkg_pickup_date ) <=240,1,0) AS lastMinBooking,
							bkg_booking_id,
							bkg_user_id,
							bkg_country_code,
							CONCAT(bkg_country_code,bkg_contact_no) as  bkg_contact_no,
							0 AS loginRank,
							0 as csrRank,
							CASE
								WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 25 AND 35 THEN 0
								WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 35 AND 60 THEN 20
								WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 60 AND 720 THEN 15
								WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 720 AND 1440 THEN 10
								WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 1440 AND 2880 THEN 0
								ELSE -15
							END AS timeRank,
							CASE
								WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 210 AND 1440 THEN 30
								WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 1440 AND 2880 THEN 25
								WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 2880 AND 5760 THEN 20
								WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 5760 AND 8640 THEN 15
								WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 8640 AND 11520 THEN 15
								WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 11520 AND 28800 THEN 10
								WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 28800 AND 43200 THEN 5
								ELSE -10
							END AS pickupRank,
							0 AS advanceRank,
							CASE
								WHEN TIMESTAMPDIFF(MINUTE, bkg_follow_up_reminder, NOW()) BETWEEN 0 AND 90 THEN 20
								WHEN TIMESTAMPDIFF(MINUTE, bkg_follow_up_reminder, NOW()) BETWEEN 90 AND 720 THEN 15
								WHEN TIMESTAMPDIFF(MINUTE, bkg_follow_up_reminder, NOW()) BETWEEN 720 AND 1440 THEN 5
								WHEN TIMESTAMPDIFF(MINUTE, bkg_follow_up_reminder, NOW()) BETWEEN 1440 AND 2880 THEN 0
								ELSE -15
							END AS followup_rank,
							1 as type,
							0 AS refType
							FROM  booking_temp
							WHERE  1 
							$where
							AND bkg_follow_up_status <> 14 
							AND  bkg_create_date>=DATE_SUB(NOW(), INTERVAL  60 MINUTE)
							AND bkg_follow_up_status IN (1,2,3)
							AND (`bkg_follow_up_reminder`< NOW()) AND bkg_pickup_date > NOW()
							AND ((bkg_assigned_to = 0 OR bkg_assigned_to IS NULL	OR DATE_ADD(bkg_follow_up_reminder, INTERVAL IF(bkg_assigned_to=0,0,45) MINUTE) <NOW()))
							AND (bkg_contact_no <> '' OR bkg_log_phone <> '')
							ORDER BY (csrRank + timeRank + advanceRank + pickupRank + followup_rank+loginRank) DESC, bkg_pickup_date ASC, bkg_create_date DESC
							LIMIT 0,15
						)
						UNION
						(
							SELECT
							bkg_id,
							IF(TIMESTAMPDIFF(MINUTE, bkg_create_date,bkg_pickup_date ) <=240,1,0) AS lastMinBooking,
							bkg_booking_id,
							bkg_user_id,
							bkg_country_code,
							CONCAT(bkg_country_code,bkg_contact_no) as  bkg_contact_no,
							0 AS loginRank,
							0 as csrRank,
							CASE
								WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 15 AND 30 THEN (45)
								WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 30 AND 60 THEN (50)
								WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 60 AND 120 THEN (35)
								WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 120 AND 720 THEN (25)
								WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 720 AND 1440 THEN (15)
								WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 1440 AND 2880 THEN 5
								WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 2880 AND 5760 THEN 0
								ELSE -20
							END AS timeRank,
							CASE
								WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 210 AND 1440 THEN (70)
								WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 1440 AND 2880 THEN (65)
								WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 2880 AND 5760 THEN (60)
								WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 5760 AND 8640 THEN 50
								WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 8640 AND 11520 THEN 40
								WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 11520 AND 28800 THEN 30
								WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 28800 AND 43200 THEN 20
								ELSE 15
							END AS pickupRank,
							CASE
								WHEN bi.bkg_gozo_amount > 4000 THEN (25)
								WHEN bi.bkg_gozo_amount BETWEEN 1000 AND 3000 THEN (20)
								WHEN bi.bkg_gozo_amount BETWEEN 500 AND 1000 THEN 15
								WHEN bi.bkg_gozo_amount BETWEEN 300 AND 500 THEN 10
								ELSE 0
							END AS advanceRank,
							IF(bt.bkg_follow_type_id=10 AND btr_unv_followup_by IS NULL , 30, 0) AS followup_rank,
							2 as type,
							0 AS refType
							FROM     booking 
						INNER JOIN booking_trail bt ON booking.bkg_id= bt.btr_bkg_id
						INNER JOIN  booking_invoice bi ON  bi.biv_bkg_id = bt.btr_bkg_id
						INNER JOIN booking_user bui on bui_bkg_id = bkg_id AND bui.bkg_contact_no <> ''
						WHERE 1 
						$where
						AND bkg_create_date>=DATE_SUB(NOW(), INTERVAL  60 MINUTE)
						AND (bt.bkg_assign_csr = 0 OR bt.bkg_assign_csr IS NULL)
						AND bkg_status IN (15) AND bkg_pickup_date > NOW()
						AND bkg_agent_id IS NULL
						AND  bt.bkg_create_type=3
						AND (bkg_followup_date IS NULL OR bkg_followup_date < NOW())
						ORDER BY (csrRank + timeRank + advanceRank + pickupRank + followup_rank+loginRank) DESC, bkg_pickup_date ASC,bkg_create_date DESC LIMIT 0,15)
					) a  WHERE  1   order by (csrRank + timeRank + pickupRank+loginRank) DESC, refType DESC LIMIT  $limit,15";
		return DBUtil::query($sql);
	}

	/**
	 * This function is used to send notifications  for freeze/unfreeze Vendor
	 * @return None
	 */
	public static function notifyLeadFollowup($bkgId, $isSchedule = 0, $schedulePlatform = null)
	{
		$ctr = 0;
		if ($bkgId > 0)
		{
			$model = BookingTemp::model()->findByPk($bkgId);
		}
		if (!$model)
		{
			goto skipAll;
		}
		$phoneNo = $model->bkg_contact_no;
		if ($phoneNo == "")
		{
			goto skipAll;
		}
		Filter::parsePhoneNumber($phoneNo, $code, $number);
		$contentParams		 = array("primaryId" => $bkgId, "url" => Filter::shortUrl(LeadFollowup::getLeadURL($bkgId, 'p')));
		$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_CONSUMER, $model->bkg_user_id, WhatsappLog::REF_TYPE_BOOKING, $bkgId, $model->bkg_booking_id, $code, $number, $model->bkg_user_email, 0, null, SmsLog::SMS_LEAD_FOLLOWUP, null, 'mail2', null, null, EmailLog::EMAIL_LEAD_FOLLOWUP, EmailLog::Consumers, EmailLog::REF_BOOKING_ID, $model->bkg_id, EmailLog::SEND_CONSUMER_BATCH_EMAIL, 0);
		$eventScheduleParams = EventSchedule::setData($bkgId, ScheduleEvent::LEAD_REF_TYPE, ScheduleEvent::BOOKING_LEAD_FOLLOWUP, "Booking Lead Followups", $isSchedule, CJSON::encode(array('bkg_id' => $bkgId)), 10, $schedulePlatform);
		$responseArr		 = MessageEventMaster::processPlatformSequences(27, $contentParams, $receiverParams, $eventScheduleParams);
		foreach ($responseArr as $response)
		{
			if ($response['success'] && $response['type'] == 1)
			{
				$ctr++;
				$userInfo		 = UserInfo::getInstance();
				$followStatus	 = $model->bkg_follow_up_status;
				LeadLog::model()->createLog($model->bkg_id, "Automatic Lead followup SMS sent", $userInfo, '', $followStatus, BookingLog::SMS_SENT);
			}
			else if ($response['success'] && $response['type'] == 2)
			{
				$ctr++;
				$userInfo		 = UserInfo::getInstance();
				$followStatus	 = $model->bkg_follow_up_status;
				LeadLog::model()->createLog($model->bkg_id, "Automatic Lead followup SMS sent", $userInfo, '', $followStatus, BookingLog::SMS_SENT);
			}
			else if ($response['success'] && $response['type'] == 3)
			{
				$ctr++;
				$userInfo		 = UserInfo::getInstance();
				$followStatus	 = $model->bkg_follow_up_status;
				LeadLog::model()->createLog($model->bkg_id, "Automatic Lead followup email sent", $userInfo, '', $followStatus, BookingLog::EMAIL_SENT);
			}
		}
		skipAll:
		if ($ctr > 0)
		{
			$model->bkg_last_cron_lead_followup	 = new CDbExpression("NOW()");
			$model->bkg_cron_lead_followup_ctr	 += $ctr;
			$model->bkg_follow_up_status		 = LeadLog::AUTO_FOLLOWUP_SENT;
			$model->bkg_follow_up_on			 = new CDbExpression("NOW()");
			if (!$model->save())
			{
				throw new Exception(json_encode($model->getErrors()), 1);
			}
		}
	}
}

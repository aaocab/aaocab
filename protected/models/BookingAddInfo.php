<?php

/**
 * This is the model class for table "booking_add_info".
 *
 * The followings are the available columns in table 'booking_add_info':
 * @property integer $bad_id
 * @property integer $bad_bkg_id
 * @property integer $bkg_no_person
 * @property integer $bkg_num_large_bag
 * @property integer $bkg_num_small_bag
 * @property integer $bkg_spl_req_senior_citizen_trvl
 * @property integer $bkg_spl_req_kids_trvl
 * @property integer $bkg_spl_req_woman_trvl
 * @property string $bkg_spl_req_other
 * @property integer $bkg_spl_req_carrier
 * @property integer $bkg_spl_req_driver_hindi_speaking
 * @property integer $bkg_spl_req_driver_english_speaking
 * @property integer $bkg_spl_req_lunch_break_time
 * @property string $bkg_flight_no
 * @property string $bkg_flight_info
 * @property integer $bkg_user_trip_type
 * @property string $bkg_file_path
 * @property integer $bkg_info_source
 * @property string $bkg_info_source_desc
 * The followings are the available model relations:
 * @property Booking $baddInfoBkg
 */
class BookingAddInfo extends CActiveRecord
{

	public $bkg_flight_chk;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_add_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('bad_bkg_id,bkg_info_source', 'required'),
			array('bad_bkg_id', 'required'),
			array('bad_bkg_id, bkg_no_person, bkg_num_large_bag, bkg_num_small_bag, bkg_spl_req_senior_citizen_trvl, bkg_spl_req_kids_trvl, bkg_spl_req_woman_trvl, bkg_spl_req_carrier, bkg_spl_req_driver_hindi_speaking, bkg_spl_req_driver_english_speaking, bkg_spl_req_lunch_break_time, bkg_user_trip_type,bkg_info_source', 'numerical', 'integerOnly' => true),
			array('bkg_info_source', 'length', 'max' => 255),
			array('bkg_info_source_desc', 'length', 'max' => 250),
			array('bkg_spl_req_other, bkg_file_path', 'length', 'max' => 500),
			array('bkg_flight_no', 'length', 'max' => 25),
			array('bkg_flight_info', 'length', 'max' => 800),
			array('bkg_no_person', 'adminValidateAdditionInfo', 'on' => 'admininsert'),
			//array('bkg_no_person', 'validateAdditionInfo', 'on' => 'update'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bad_id, bad_bkg_id, bkg_no_person, bkg_num_large_bag, bkg_num_small_bag, bkg_spl_req_senior_citizen_trvl, bkg_spl_req_kids_trvl, bkg_spl_req_woman_trvl, bkg_spl_req_other, bkg_spl_req_carrier, bkg_spl_req_driver_hindi_speaking, bkg_spl_req_driver_english_speaking, bkg_spl_req_lunch_break_time, bkg_flight_no, bkg_flight_info, bkg_user_trip_type, bkg_file_path,bkg_info_source,bkg_info_source_desc', 'safe', 'on' => 'search'),
			array('bkg_no_person', 'adminValidateAdditionInfo', 'on' => 'admininsert,adminupdate,adminupdateuser,additionalInfo'),
			array('bkg_file_path,bkg_spl_req_other,bkg_spl_req_lunch_break_time', 'length', 'max' => 500),
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
			'baddInfoBkg' => array(self::BELONGS_TO, 'Booking', 'bad_bkg_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'bad_id'								 => 'Bad',
			'bad_bkg_id'							 => 'Bad Bkg',
			'bkg_no_person'							 => 'No. of Person',
			'bkg_num_large_bag'						 => 'Number of Large Bags',
			'bkg_num_small_bag'						 => 'Number of Small Bags',
			'bkg_spl_req_senior_citizen_trvl'		 => 'Senior Citizen Travelling',
			'bkg_spl_req_kids_trvl'					 => 'Kids on board',
			'bkg_spl_req_woman_trvl'				 => 'Women traveling',
			'bkg_spl_req_other'						 => 'Other',
			'bkg_spl_req_carrier'					 => 'Require vehicle with Carrier',
			'bkg_spl_req_driver_hindi_speaking'		 => 'Require hindi speaking driver',
			'bkg_spl_req_driver_english_speaking'	 => 'Require english speaking driver',
			'bkg_spl_req_lunch_break_time'			 => 'Journey Break',
			'bkg_flight_no'							 => 'Bkg Flight No',
			'bkg_flight_info'						 => 'Bkg Flight Info',
			'bkg_user_trip_type'					 => 'Bkg User Trip Type',
			'bkg_file_path'							 => 'Bkg File Path',
			'bkg_info_source'						 => 'Info Source',
			'bkg_info_source_desc'					 => 'Bkg Info Source Desc',
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

		$criteria->compare('bad_id', $this->bad_id);
		$criteria->compare('bad_bkg_id', $this->bad_bkg_id);
		$criteria->compare('bkg_no_person', $this->bkg_no_person);
		$criteria->compare('bkg_num_large_bag', $this->bkg_num_large_bag);
		$criteria->compare('bkg_num_small_bag', $this->bkg_num_small_bag);
		$criteria->compare('bkg_spl_req_senior_citizen_trvl', $this->bkg_spl_req_senior_citizen_trvl);
		$criteria->compare('bkg_spl_req_kids_trvl', $this->bkg_spl_req_kids_trvl);
		$criteria->compare('bkg_spl_req_woman_trvl', $this->bkg_spl_req_woman_trvl);
		$criteria->compare('bkg_spl_req_other', $this->bkg_spl_req_other, true);
		$criteria->compare('bkg_spl_req_carrier', $this->bkg_spl_req_carrier);
		$criteria->compare('bkg_spl_req_driver_hindi_speaking', $this->bkg_spl_req_driver_hindi_speaking);
		$criteria->compare('bkg_spl_req_driver_english_speaking', $this->bkg_spl_req_driver_english_speaking);
		$criteria->compare('bkg_spl_req_lunch_break_time', $this->bkg_spl_req_lunch_break_time);
		$criteria->compare('bkg_flight_no', $this->bkg_flight_no, true);
		$criteria->compare('bkg_flight_info', $this->bkg_flight_info, true);
		$criteria->compare('bkg_user_trip_type', $this->bkg_user_trip_type);
		$criteria->compare('bkg_file_path', $this->bkg_file_path, true);
		$criteria->compare('bkg_info_source', $this->bkg_info_source);
		$criteria->compare('bkg_info_source_desc', $this->bkg_info_source_desc, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingAddInfo the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function validateAdditionInfo($attribute, $params)
	{
		$success = true;
		if ($this->baddInfoBkg->bkg_vehicle_type_id > 0)
		{
			$svcModel		 = \SvcClassVhcCat::model()->getVctSvcList('object', 0, 0, $this->baddInfoBkg->bkg_vehicle_type_id);
			$sittingCapacity = $svcModel->vct_capacity;
			if ($this->bkg_no_person > $sittingCapacity)
			{
				$this->addError($attribute, 'Your selected cab can accomodate ' . $model->vct_capacity . ' passengers');
				$success = false;
			}
		}

		return $success;
	}

	public function adminValidateAdditionInfo($attribute, $params)
	{
		$success = true;
		if ($this->baddInfoBkg->bkg_vehicle_type_id > 0 && $this->baddInfoBkg->bkg_vehicle_type_id != '')
		{
			//$model = VehicleTypes::model()->findByPk($this->baddInfoBkg->bkg_vehicle_type_id);
			$model = VehicleCategory::model()->findByPk($this->baddInfoBkg->bkgSvcClassVhcCat->scv_vct_id);
			if (($this->bkg_no_person > $model->vct_capacity) && $model->vct_capacity != '')
			{
				$this->addError($attribute, 'Your selected cab can accomodate ' . $model->vct_capacity . ' passengers');
				$success = false;
			}
//            if (($this->bkg_num_small_bag > $model->vct_small_bag_capacity) && $model->vct_small_bag_capacity != '')
//            {
//                $this->addError('bkg_num_small_bag', 'The selected cab can accomodate ' . $model->vct_small_bag_capacity . ' small bags');
//                $success = false;
//            }
//            if (($this->bkg_num_large_bag > $model->vct_big_bag_capacity) && $model->vct_big_bag_capacity != '')
//            {
//                $this->addError('bkg_num_large_bag', 'The selected cab can accomodate ' . $model->vct_big_bag_capacity . ' big bags');
//                $success = false;
//            }

			if ($this->baddInfoBkg->bkgPref->bkg_send_email == 1 && $this->baddInfoBkg->bkgUserInfo->bkg_user_email == '')
			{
				$this->addError('bkg_send_email', "Email address is not provided");
				$success = false;
			}
			if ($this->baddInfoBkg->bkgPref->bkg_send_sms == 1 && $this->baddInfoBkg->bkgUserInfo->bkg_contact_no == '')
			{
				$this->addError('bkg_send_sms', "Contact number is not provided");
				$success = false;
			}
			/*
			  if ($this->bkg_send_email != 1 && $this->bkg_send_sms != 1) {
			  $this->addError('bkg_send_sms', "Please check one of the communication media to send notifications.");
			  $success = false;
			  } */
		}
		return $success;
	}

	public function getSpecialRequests($agtId = null, $isApp = 0)
	{
		if ($isApp == 0)
		{
			$heading = "<b>Customer special request</b>";
			$splReq	 = '';
			if ($this->bkg_spl_req_senior_citizen_trvl == 1)
			{
				$splReq .= '<li>' . $this->getAttributeLabel('bkg_spl_req_senior_citizen_trvl') . "</li>";
			}
			if ($this->bkg_spl_req_kids_trvl == 1)
			{
				$splReq .= '<li>' . $this->getAttributeLabel('bkg_spl_req_kids_trvl') . "</li>";
			}
			if ($this->bkg_spl_req_woman_trvl == 1)
			{
				$splReq .= '<li>' . $this->getAttributeLabel('bkg_spl_req_woman_trvl') . "</li>";
			}
			if ($this->bkg_spl_req_carrier == 1 && ($agtId != 450 || $agtId == null))
			{
				$splReq .= '<li>' . $this->getAttributeLabel('bkg_spl_req_carrier') . "</li>";
			}
			if ($this->bkg_spl_req_driver_hindi_speaking == 1)
			{
				$splReq .= '<li>' . $this->getAttributeLabel('bkg_spl_req_driver_hindi_speaking') . "</li>";
			}
			if ($this->bkg_spl_req_driver_english_speaking == 1)
			{
				$splReq .= '<li>' . $this->getAttributeLabel('bkg_spl_req_driver_english_speaking') . "</li>";
			}
			if ($this->bkg_spl_req_other != '')
			{
				$splReq .= '<li>' . $this->bkg_spl_req_other . "</li>";
			}
			if ($this->bkg_spl_req_lunch_break_time >0)
			{
				$splReq .= "<li>" . $this->bkg_spl_req_lunch_break_time ." Minutes journey break required"."</li>";
			}
			if ($splReq != '')
			{
				$splReq = rtrim($splReq, ', ');
			}
			if ($splReq)
			{
				$splReq = $heading . $splReq;
			}
			else
			{
				$splReq = $splReq;
			}
		}
		else
		{
			$heading = "Customer special request" . ', ';
			$splReq	 = '';
			if ($this->bkg_spl_req_senior_citizen_trvl == 1)
			{
				$splReq .= $this->getAttributeLabel('bkg_spl_req_senior_citizen_trvl') . ', ';
			}
			if ($this->bkg_spl_req_kids_trvl == 1)
			{
				$splReq .= $this->getAttributeLabel('bkg_spl_req_kids_trvl') . ', ';
			}
			if ($this->bkg_spl_req_woman_trvl == 1)
			{
				$splReq .= $this->getAttributeLabel('bkg_spl_req_woman_trvl') . ', ';
			}
			if ($this->bkg_spl_req_carrier == 1 && ($agtId != 450 || $agtId == null))
			{
				$splReq .= $this->getAttributeLabel('bkg_spl_req_carrier') . ', ';
			}
			if ($this->bkg_spl_req_driver_hindi_speaking == 1)
			{
				$splReq .= $this->getAttributeLabel('bkg_spl_req_driver_hindi_speaking') . ', ';
			}
			if ($this->bkg_spl_req_driver_english_speaking == 1)
			{
				$splReq .= $this->getAttributeLabel('bkg_spl_req_driver_english_speaking') . ', ';
			}
			if ($this->bkg_spl_req_other != '')
			{
				$splReq .= $this->bkg_spl_req_other . ', ';
			}
			if ($this->bkg_spl_req_lunch_break_time >0)
			{
				$splReq .= $this->bkg_spl_req_lunch_break_time .' Minutes journey break required'. ', ';
			}
			if ($splReq != '')
			{
				$splReq = rtrim($splReq, ', ');
			}
			if ($splReq)
			{
				$splReq = $heading . $splReq;
			}
			else
			{
				$splReq = $splReq;
			}
		}

		return $splReq;
	}

	//($spclInstruction != "") ? $spclInstruction : "&nbsp;" 
	public function getInfosource($user = '')
	{
		if ($user == 'user')
		{
			$userInfosource = [
				'1'	 => 'Google',
				'2'	 => 'Facebook',
				'3'	 => 'Print media',
				'4'	 => 'Radio',
				'5'	 => 'Friend',
				'6'	 => 'Other',
				'7'	 => 'Whatsapp Promo Message',
				'21' => 'Whatsapp Promo H'
			];
		}

		$arrInfosource = [
			'8'	 => 'Internet',
			'9'	 => 'Newspaper',
			'10' => 'Hoarding',
			'11' => 'SMS',
			'12' => 'Ixigo',
			'13' => 'Quikr',
			'14' => 'Upsell SMS',
			'15' => 'Leaflet',
			'16' => 'Just Dial',
			'17' => 'Kiosk',
			'18' => 'Word Of Mouth',
			'19' => 'Movie Theatre',
			'20' => 'Other Media',
			'1'	 => 'Google',
			'2'	 => 'Facebook',
			'3'	 => 'Print media',
			'4'	 => 'Radio',
			'5'	 => 'Friend',
			'6'	 => 'Other',
			'7'	 => 'Whatsapp Promo Message',
			'21' => 'Whatsapp Promo H'
		];
		if ($user == 'admin')
		{
			$arrInfosource = $arrInfosource + ['21' => 'Agent'];
		}
		else if ($user == 'user')
		{
			$arrInfosource = $userInfosource;
		}
		asort($arrInfosource);
		return $arrInfosource;
	}

	public function updateData($model, $bkgId)
	{

		if ($model)
		{
			$addInfoModel									 = $this->getByBookingID($bkgId);
			$addInfoModel->bad_bkg_id						 = $bkgId;
			$addInfoModel->bkg_spl_req_other				 = $model->bkg_spl_req_other;
			$addInfoModel->bkg_num_large_bag				 = $model->bkg_num_large_bag;
			$addInfoModel->bkg_num_small_bag				 = $model->bkg_num_small_bag;
			$addInfoModel->bkg_spl_req_carrier				 = $model->bkg_spl_req_carrier;
			$addInfoModel->bkg_spl_req_kids_trvl			 = $model->bkg_spl_req_kids_trvl;
			$addInfoModel->bkg_spl_req_senior_citizen_trvl	 = $model->bkg_spl_req_senior_citizen_trvl;
			$addInfoModel->bkg_spl_req_woman_trvl			 = $model->bkg_spl_req_woman_trvl;
			if (!$addInfoModel->save())
			{
				throw new Exception("Failed to update partner API data", ReturnSet::ERROR_FAILED);
			}
			else
			{
				return true;
			}
		}
	}

	public function getByBookingID($bkgId)
	{
		$model = $this->find("bad_bkg_id=:bkgId", ['bkgId' => $bkgId]);
		return $model;
	}

	public static function updataDataMMT($vehicleId, $model)
	{

		if ($vehicleId == VehicleCategory::SUV_ECONOMIC || $model->bkg_vehicle_type_id == VehicleCategory::ASSURED_INNOVA_ECONOMIC)
		{
			$splRemark								 = 'Require vehicle with Carrier';
			$model->bkgAddInfo->bkg_spl_req_carrier	 = 1;
			if ($model->save() && $model->bkgAddInfo->save())
			{
				$eventId = BookingLog::REMARKS_ADDED;
				$remark	 = $splRemark;
				BookingLog::model()->createLog($model->bkg_id, $remark, UserInfo::getInstance(), $eventId);
			}
		}
	}

	public static function updateTransferzData($model, $jsonObj)
	{
		$result								 = false;
		$model->bkgAddInfo->bkg_no_person	 = ($jsonObj->travellerInfo->passengerCount != $model->bkgAddInfo->bkg_no_person) ? $jsonObj->travellerInfo->passengerCount : $model->bkgAddInfo->bkg_no_person;
		$model->bkgAddInfo->bkg_flight_no	 = ($jsonObj->travellerInfo->flightNumber != $model->bkgAddInfo->bkg_flight_no) ? $jsonObj->travellerInfo->flightNumber : $model->bkgAddInfo->bkg_flight_no;
		if (count($jsonObj->addOns) > 0)
		{
			foreach ($jsonObj->addOns as $data)
			{
				$model->bkgAddInfo->bkg_spl_req_other .= "<br>" . $data;
				if ($data == "SPECIAL_LUGGAGE")
				{
					$model->bkgAddInfo->bkg_spl_req_carrier = 1;
				}
			}
		}

		if ($model->bkgAddInfo->save())
		{
			$result = true;
		}
		Logger::writeToConsole('BAI updateTransferzData Error: ' . json_encode($model->bkgAddInfo->getErrors()));
		return $result;
	}

}

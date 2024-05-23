<?php

/**
 * This is the model class for table "trip_otplog".
 *
 * The followings are the available columns in table 'trip_otplog':
 * @property integer $trl_id
 * @property integer $trl_bkg_id
 * @property integer $trl_drv_id
 * @property integer $trl_platform
 * @property string $trl_msg
 * @property string $trl_otp
 * @property string $trl_phNumber
 * @property string $trl_ip
 * @property string $trl_date
 * @property integer $trl_status
 */
class TripOtplog extends CActiveRecord
{

	public $statusArr = [1 => 'Valid', 2 => 'Wrong Otp', 3 => 'Wrong SMS format'];

	const platformArr = [1 => 'SMS', 2 => 'Driver APP', 3 => 'URL', 4 => 'Partner APP'];
	const Platform_SMS		 = 1;
	const Platform_DRIVERAPP	 = 2;
	const Platform_URL		 = 3;
	const Platform_PARTNERAPP	 = 4;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'trip_otplog';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('trl_bkg_id, trl_drv_id, trl_platform, trl_status', 'numerical', 'integerOnly' => true),
			array('trl_msg, trl_otp', 'length', 'max' => 200),
			array('trl_phNumber', 'length', 'max' => 255),
			array('trl_ip', 'length', 'max' => 50),
			array('trl_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('trl_id, trl_bkg_id, trl_drv_id, trl_platform, trl_msg, trl_otp, trl_phNumber, trl_ip, trl_date, trl_status', 'safe', 'on' => 'search'),
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
			'trl_id'		 => 'Trl',
			'trl_bkg_id'	 => 'Trl Bkg',
			'trl_drv_id'	 => 'Trl Drv',
			'trl_platform'	 => 'Trl Platform',
			'trl_msg'		 => 'Trl Msg',
			'trl_otp'		 => 'Trl Otp',
			'trl_phNumber'	 => 'Trl Ph Number',
			'trl_ip'		 => 'Trl Ip',
			'trl_date'		 => 'Trl Date',
			'trl_status'	 => 'Trl Status',
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

		$criteria->compare('trl_id', $this->trl_id);
		$criteria->compare('trl_bkg_id', $this->trl_bkg_id);
		$criteria->compare('trl_drv_id', $this->trl_drv_id);
		$criteria->compare('trl_platform', $this->trl_platform);
		$criteria->compare('trl_msg', $this->trl_msg, true);
		$criteria->compare('trl_otp', $this->trl_otp, true);
		$criteria->compare('trl_phNumber', $this->trl_phNumber, true);
		$criteria->compare('trl_ip', $this->trl_ip, true);
		$criteria->compare('trl_date', $this->trl_date, true);
		$criteria->compare('trl_status', $this->trl_status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TripOtplog the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function updateTripStart($bookingModel, $tripotp, $trlid = 0, $phoneNumber = '')
	{
		$success		 = false;
		//$bookingModel	 = Booking::model()->findByPk($bookingId);
		$bkg_booking_id	 = $bookingModel->bkg_booking_id;
		$bkgID			 = $bookingModel->bkg_id;
		$bkg_bcb_id		 = $bookingModel->bkg_bcb_id;
		$BookingCabModel = BookingCab::model()->findbypk($bkg_bcb_id);

		$driverID	 = $BookingCabModel->bcb_driver_id;
		$driverPhNo	 = $BookingCabModel->bcb_driver_phone;
		$drvName	 = $BookingCabModel->bcb_driver_name;
		$vendor_id	 = $BookingCabModel->bcb_vendor_id;
		if ($trlid > 0)
		{
			$model		 = TripOtplog::model()->findByPk($trlid);
			$receiveDt	 = $model->trl_date;
		}
		else
		{
			$model			 = new TripOtplog();
			$model->trl_date = new CDbExpression('NOW()');
		}

		$model->trl_bkg_id	 = $bkgID;
		$model->trl_drv_id	 = $driverID;
		$model->trl_otp		 = $tripotp;
		$model->save();
		$isAllowVerify		 = BookingPref::model()->isAllowTripVerify($bkgID);
		$bookingTrackModel	 = BookingTrack::model()->find('btk_bkg_id=:bkg AND bkg_trip_otp=:otp', ['bkg' => $bkgID, 'otp' => $tripotp]);
		if ($bookingTrackModel != '' && $isAllowVerify)
		{
			$flag = $bookingTrackModel->bkg_is_trip_verified;
			if ($tripotp)
			{
				$bookingTrackModel->bkg_is_trip_verified = 1;
				$bookingTrackModel->bkg_ride_start		 = 1;
				$bookingTrackModel->bkg_trip_start_time	 = new CDbExpression('NOW()');
			}
			else
			{
				$bookingTrackModel->bkg_is_trip_verified = 1;
			}
			$success = $bookingTrackModel->save();
			if ($phoneNumber != '')
			{
				$msgCom = new smsWrapper();
				$msgCom->MatchOTP($phoneNumber, $drvName, $bkg_booking_id);
			}

			BookingCab::model()->pushPartnerTripStart($bkgID, $receiveDt);
			$isOtpRequired = ($bookingModel->bkg_agent_id > 0) ? $bookingModel->bkgAgent->agt_otp_required : $bookingModel->bkgPref->bkg_trip_otp_required;
			
			if ($isOtpRequired == 1 && $bookingModel->bkg_booking_type != 7)
			{
				$penaltyType		 = PenaltyRules::PTYPE_LATE_OTP_VERIFICATION;
				$penaltyAmount		 = BookingTrack::getLateOTPVerifyPenalty($bkgID, $penaltyType);
				if ($penaltyAmount > 0 && $flag != 1 && $bookingTrackModel->bkg_is_trip_verified == 1)
				{
					$remarks		     = "Late OTP verification of booking #$bkg_booking_id";
					$result				 = AccountTransactions::checkAppliedPenaltyByType($bkgID, $penaltyType);
					if($result)
					{
					AccountTransactions::model()->addVendorPenalty($bkgID, $vendor_id, $penaltyAmount, $remarks,'', $penaltyType);
					}
				}
			}
		}
		else
		{
			$model->trl_status = 2;
			$model->save();
			if ($phoneNumber != '')
			{
				$msgCom = new smsWrapper();
				$msgCom->informDriverInvalidOTP($phoneNumber, $drvName, $bkg_booking_id);
			}
		}
		return $success;
	}

	public function updateTripStartByDriver($bookingModel, $tripotp, $trlid = 0, $phoneNumber = '')
	{
		$success		 = false;
		//$bookingModel	 = Booking::model()->findByPk($bookingId);
		$bkg_booking_id	 = $bookingModel->bkg_booking_id;
		$bkgID			 = $bookingModel->bkg_id;
		$bkg_bcb_id		 = $bookingModel->bkg_bcb_id;
		$BookingCabModel = BookingCab::model()->findbypk($bkg_bcb_id);

		$driverID	 = $BookingCabModel->bcb_driver_id;
		$driverPhNo	 = $BookingCabModel->bcb_driver_phone;
		$drvName	 = $BookingCabModel->bcb_driver_name;

		if ($trlid > 0)
		{
			$model		 = TripOtplog::model()->findByPk($trlid);
			$receiveDt	 = $model->trl_date;
		}
		else
		{
			$model			 = new TripOtplog();
			$model->trl_date = new CDbExpression('NOW()');
		}

		$model->trl_bkg_id	 = $bkgID;
		$model->trl_drv_id	 = $driverID;
		$model->trl_otp		 = $tripotp;
		$model->save();


		$bookingTrackModel = BookingTrack::model()->find('btk_bkg_id=:bkg AND bkg_trip_otp=:otp', ['bkg' => $bkgID, 'otp' => $tripotp]);
		if ($bookingTrackModel != '')
		{
			if ($tripotp)
			{
				$bookingTrackModel->bkg_is_trip_verified = 1;
				$bookingTrackModel->bkg_ride_start		 = 1;
				$bookingTrackModel->bkg_trip_start_time	 = new CDbExpression('NOW()');
			}
			else
			{
				$bookingTrackModel->bkg_is_trip_verified = 1;
			}
			$bookingTrackModel->save();
			if ($phoneNumber != '')
			{
				$msgCom = new smsWrapper();
				$msgCom->MatchOTP($phoneNumber, $drvName, $bkg_booking_id);
			}
			$success = true;
			BookingCab::model()->pushPartnerTripStart($bkgID, $receiveDt);
		}
		else
		{
			$model->trl_status = 2;
			$model->save();
			if ($phoneNumber != '')
			{
				$msgCom = new smsWrapper();
				$msgCom->informDriverInvalidOTP($phoneNumber, $drvName, $bkg_booking_id);
			}
		}
		return $success;
	}

	public function add($bkg_id, $platform, $otp, $msg = '', $phoneNumber = '')
	{
		$IP						 = \Filter::getUserIP();
		$tmodel					 = new TripOtplog();
		$tmodel->trl_date		 = new CDbExpression('NOW()');
		$tmodel->trl_platform	 = $platform;
		$tmodel->trl_ip			 = $IP;
		$tmodel->trl_msg		 = ($msg == '') ? UserLog::model()->getDevice() : $msg;
		$tmodel->trl_bkg_id		 = $bkg_id;
		$tmodel->trl_otp		 = $otp;
		$tmodel->trl_phNumber	 = $phoneNumber;
		$tmodel->save();
		return $tmodel;
	}

	/**
	 * This function add trip OTP log
	 * @param type $bkg_id
	 * @param type $userId
	 * @param type $platform
	 * @param type $otp
	 * @param type $msg
	 * @param type $phoneNumber
	 * @return \TripOtplog
	 */
	public function addNew($bkg_id, $userId, $platform, $otp, $msg = '', $phoneNumber = '')
	{
        $arr					 = [1 => 'SMS', 2 => 'Driver APP', 3 => 'URL', 4 => 'Partner APP'];
		$IP						 = \Filter::getUserIP();
		$tmodel					 = new TripOtplog();
		$tmodel->trl_date		 = new CDbExpression('NOW()');
		$tmodel->trl_drv_id		 = $userId;
		$tmodel->trl_platform	 = array_search($platform, $arr);
		$tmodel->trl_ip			 = $IP;
		$tmodel->trl_msg		 = ($msg == '') ? UserLog::model()->getDevice() : $msg;
		$tmodel->trl_bkg_id		 = $bkg_id;
		$tmodel->trl_otp		 = $otp;
		$tmodel->trl_phNumber	 = $phoneNumber;
		$tmodel->save();

		return $tmodel;
	}

}

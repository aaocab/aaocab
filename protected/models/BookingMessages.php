<?php

/**
 * This is the model class for table "booking_messages".
 *
 * The followings are the available columns in table 'booking_messages':
 * @property integer $bkg_msg_id
 * @property integer $bkg_booking_id
 * @property integer $bkg_event_id
 * @property integer $bkg_agent_email
 * @property integer $bkg_agent_sms
 * @property integer $bkg_agent_app
 * @property integer $bkg_agent_whatsapp
 * @property integer $bkg_trvl_email
 * @property integer $bkg_trvl_sms
 * @property integer $bkg_trvl_app
 * @property integer $bkg_trvl_whatsapp
 * @property integer $bkg_rm_email
 * @property integer $bkg_rm_sms
 * @property integer $bkg_rm_app
 * @property integer $bkg_rm_whatsapp
 * @property integer $bkg_active
 * @property string $bkg_created
 * @property string $bkg_modified
 */
class BookingMessages extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_messages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bkg_booking_id, bkg_event_id', 'required'),
			array('bkg_booking_id, bkg_event_id, bkg_agent_email, bkg_agent_sms, bkg_agent_app, bkg_trvl_email, bkg_trvl_sms, bkg_trvl_app, bkg_rm_email, bkg_rm_sms, bkg_rm_app, bkg_active', 'numerical', 'integerOnly' => true),
			array('bkg_created, bkg_modified', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bkg_msg_id, bkg_booking_id, bkg_event_id, bkg_agent_email, bkg_agent_sms, bkg_agent_app,bkg_agent_whatsapp, bkg_trvl_email, bkg_trvl_sms, bkg_trvl_app,bkg_trvl_whatsapp, bkg_rm_email, bkg_rm_sms, bkg_rm_app, bkg_rm_whatsapp, bkg_active, bkg_created, bkg_modified', 'safe'),
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
			'bkgMessages' => [self::BELONGS_TO, 'Booking', 'bkg_booking_id']
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'bkg_msg_id'		 => 'ID',
			'bkg_booking_id'	 => 'Booking ID',
			'bkg_event_id'		 => 'Event ID',
			'bkg_agent_email'	 => 'Agent Email',
			'bkg_agent_sms'		 => 'Agent Sms',
			'bkg_agent_app'		 => 'Agent App',
			'bkg_agent_whatsapp' => 'Agent WhatsApp',
			'bkg_trvl_email'	 => 'Traveller Email',
			'bkg_trvl_sms'		 => 'Traveller Sms',
			'bkg_trvl_app'		 => 'Traveller App',
			'bkg_trvl_whatsapp'	 => 'Traveller WhatsApp',
			'bkg_rm_email'		 => 'Relationship Email',
			'bkg_rm_sms'		 => 'Relationship Sms',
			'bkg_rm_app'		 => 'Relationship App',
			'bkg_rm_whatsapp'	 => 'Relationship WhatsApp',
			'bkg_active'		 => 'Status',
			'bkg_created'		 => 'Created',
			'bkg_modified'		 => 'Modified',
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

		$criteria->compare('bkg_msg_id', $this->bkg_msg_id);
		$criteria->compare('bkg_booking_id', $this->bkg_booking_id);
		$criteria->compare('bkg_event_id', $this->bkg_event_id);
		$criteria->compare('bkg_agent_email', $this->bkg_agent_email);
		$criteria->compare('bkg_agent_sms', $this->bkg_agent_sms);
		$criteria->compare('bkg_agent_app', $this->bkg_agent_app);
		$criteria->compare('bkg_trvl_email', $this->bkg_trvl_email);
		$criteria->compare('bkg_trvl_sms', $this->bkg_trvl_sms);
		$criteria->compare('bkg_trvl_app', $this->bkg_trvl_app);
		$criteria->compare('bkg_rm_email', $this->bkg_rm_email);
		$criteria->compare('bkg_rm_sms', $this->bkg_rm_sms);
		$criteria->compare('bkg_rm_app', $this->bkg_rm_app);
		$criteria->compare('bkg_active', $this->bkg_active);
		$criteria->compare('bkg_created', $this->bkg_created, true);
		$criteria->compare('bkg_modified', $this->bkg_modified, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingMessages the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getByEventAndBookingId($bkgId, $event)
	{
		return $this->find('bkg_booking_id=:bkgId AND bkg_event_id=:eventId', ['bkgId' => $bkgId, 'eventId' => $event]);
	}

	public function getMessageDefaults($agent, $key)
	{
		if ($key == AgentMessages::BOOKING_CONF_WITH_PAYMENTINFO || $key == AgentMessages::PAYMENT_CONFIRM || $key == AgentMessages::PAYMENT_FAILED || $key == AgentMessages::BOOKING_EDIT || $key == AgentMessages::CAB_ASSIGNED || $key == AgentMessages::INVOICE || $key == AgentMessages::RATING_AND_REVIEWS || $key == AgentMessages::RESCHEDULE_REQUEST || $key == AgentMessages::CAB_DRIVER_DETAIL || $key == AgentMessages::CANCEL_TRIP)
		{
			$this->bkg_agent_email		 = 1;
			$this->bkg_agent_sms		 = 1;
			$this->bkg_agent_whatsapp	 = 1;
		}
		if ($key == AgentMessages::BOOKING_CONF_WITHOUT_PAYMENTINFO || $key == AgentMessages::CAB_ASSIGNED)
		{
			$this->bkg_trvl_email	 = 1;
			$this->bkg_trvl_sms		 = 1;
			$this->bkg_trvl_whatsapp = 1;
		}
		$AgentMessages = AgentMessages::model()->getByEventAndAgent($agent, $key);
		if ($AgentMessages != '')
		{
			$this->bkg_agent_email		 = ($AgentMessages->agt_agent_email == 1) ? 1 : 0;
			$this->bkg_agent_sms		 = ($AgentMessages->agt_agent_sms == 1) ? 1 : 0;
			$this->bkg_agent_app		 = ($AgentMessages->agt_agent_app == 1) ? 1 : 0;
			$this->bkg_agent_whatsapp	 = ($AgentMessages->agt_agent_whatsapp == 1) ? 1 : 0;
			$this->bkg_trvl_email		 = ($AgentMessages->agt_trvl_email == 1) ? 1 : 0;
			$this->bkg_trvl_sms			 = ($AgentMessages->agt_trvl_sms == 1) ? 1 : 0;
			$this->bkg_trvl_app			 = ($AgentMessages->agt_trvl_app == 1) ? 1 : 0;
			$this->bkg_trvl_whatsapp	 = ($AgentMessages->agt_trvl_whatsapp == 1) ? 1 : 0;
			$this->bkg_rm_email			 = ($AgentMessages->agt_rm_email == 1) ? 1 : 0;
			$this->bkg_rm_sms			 = ($AgentMessages->agt_rm_sms == 1) ? 1 : 0;
			$this->bkg_rm_app			 = ($AgentMessages->agt_rm_app == 1) ? 1 : 0;
			$this->bkg_rm_whatsapp		 = ($AgentMessages->agt_rm_whatsapp == 1) ? 1 : 0;
		}
	}

	public function getMessageSettings($bkgId, $event)
	{

		$bookingModel	 = Booking::model()->findByPk($bkgId);
		//$bookingPrefModel	 = BookingPref::model()->getByBooking($bkgId);
		/** @var BookingMessages $bookingMessages */
		$bookingMessages = BookingMessages::model()->getByEventAndBookingId($bkgId, $event);

		if ($bookingMessages->bkg_agent_sms == 0 && $event == AgentMessages::BOOKING_CONF_WITHOUT_PAYMENTINFO)
		{
			$bookingMessages1				 = BookingMessages::model()->getByEventAndBookingId($bkgId, AgentMessages::BOOKING_CONF_WITH_PAYMENTINFO);
			$bookingMessages->bkg_agent_sms	 = $bookingMessages1->bkg_agent_sms;
		}
		$agentModel	 = Agents::model()->findByPk($bookingModel->bkg_agent_id);
		$userType	 = EmailLog::Agent;
		if ($agentModel->agt_type == 1)
		{
			$userType = EmailLog::Corporate;
		}
		$response = Contact::referenceUserData($bookingModel->bkgUserInfo->bui_id, 3);
		if ($response->getStatus())
		{
			$contactNo	 = $response->getData()->phone['number'];
			$countryCode = $response->getData()->phone['ext'];
			$email		 = $response->getData()->email['email'];
			$firstName	 = $response->getData()->email['firstName'];
			$lastName	 = $response->getData()->email['lastName'];
		}
		$emailArr	 = [];
		$phoneArr	 = [];
		$whatsappArr = [];
		if ($bookingModel->bkgPref->bkg_send_email == 1 && $bookingMessages != '')
		{
			if ($bookingModel->bkgUserInfo->bkg_crp_email != '' && $bookingMessages->bkg_agent_email == 1)
			{
				$emailArr[$userType] = ['email' => $bookingModel->bkgUserInfo->bkg_crp_email, 'name' => $bookingModel->bkgUserInfo->bkg_crp_name];
			}
			if ($bookingMessages->bkg_trvl_email == 1 && $email != '')
			{
				$emailArr[EmailLog::Consumers] = ['email' => $email, 'name' => $firstName . " " . $lastName];
			}

			if ($agentModel->agt_copybooking_admin_id != '')
			{
				$adminModel								 = Admins::model()->findByPk($agentModel->agt_copybooking_admin_id);
				$agentModel->agt_copybooking_admin_email = $adminModel->adm_email;
				// $arrNotify['gozomanager_phone'] = ''; //admin phone not exists;
				$adminName								 = $adminModel->adm_fname;
			}
			if ($bookingMessages->bkg_rm_email == 1 && $agentModel->agt_copybooking_admin_email != '')
			{
				$emailArr[EmailLog::Admin] = ['email' => $agentModel->agt_copybooking_admin_email, 'name' => $adminName];
			}
		}


		if ($bookingModel->bkgPref->bkg_send_sms == 1 && $bookingMessages != '')
		{
			if ($bookingMessages->bkg_agent_sms == 1 && $bookingModel->bkgUserInfo->bkg_crp_phone != '')
			{
				$phoneArr[$userType] = ['phone' => $bookingModel->bkgUserInfo->bkg_crp_phone, 'country_code' => $bookingModel->bkgUserInfo->bkg_crp_country_code];
			}
			if ($bookingMessages->bkg_rm_sms == 1 && $agentModel->agt_copybooking_admin_phone != '')
			{
				$phoneArr[SmsLog::Admin] = ['phone' => $agentModel->agt_copybooking_admin_phone, 'country_code' => '91'];
			}
			if ($bookingMessages->bkg_trvl_sms == 1 && $contactNo != '')
			{
				$phoneArr[SmsLog::Consumers] = ['phone' => $contactNo, 'country_code' => $countryCode];
			}
		}
		if ($bookingModel->bkgPref->bkg_send_sms == 1 && $bookingMessages != '')
		{
			if ($bookingMessages->bkg_agent_whatsapp == 1 && $bookingModel->bkgUserInfo->bkg_crp_phone != '')
			{
				$whatsappArr[UserInfo::TYPE_AGENT] = ['phone' => $bookingModel->bkgUserInfo->bkg_crp_phone, 'country_code' => $bookingModel->bkgUserInfo->bkg_crp_country_code];
			}
			if ($bookingMessages->bkg_rm_whatsapp == 1 && $agentModel->agt_copybooking_admin_phone != '')
			{
				$whatsappArr[UserInfo::TYPE_ADMIN] = ['phone' => $agentModel->agt_copybooking_admin_phone, 'country_code' => '91'];
			}
			if ($bookingMessages->bkg_trvl_whatsapp == 1 && $contactNo != '')
			{
				$whatsappArr[UserInfo::TYPE_CONSUMER] = ['phone' => $contactNo, 'country_code' => $countryCode];
			}
		}
		return ['email' => $emailArr, 'sms' => $phoneArr, 'whatsapp' => $whatsappArr];
	}

	public function setAgentNotificationDataForBooking($arrAgentNotifyOpt, $bkgId)
	{
		$arrEvents = AgentMessages::getEvents();
		foreach ($arrEvents as $key => $value)
		{
			$bookingMessages = $this->getByEventAndBookingId($bkgId, $key);
			if ($bookingMessages == '')
			{
				$bookingMessages = new BookingMessages();
			}
			$bookingMessages->bkg_booking_id	 = $bkgId;
			$bookingMessages->bkg_event_id		 = $key;
			$bookingMessages->bkg_agent_email	 = $arrAgentNotifyOpt['agt_agent_email'][$key];
			$bookingMessages->bkg_agent_sms		 = $arrAgentNotifyOpt['agt_agent_sms'][$key];
			$bookingMessages->bkg_agent_app		 = $arrAgentNotifyOpt['agt_agent_app'][$key];
			$bookingMessages->bkg_agent_whatsapp = $arrAgentNotifyOpt['agt_agent_whatsapp'][$key];
			$bookingMessages->bkg_trvl_email	 = $arrAgentNotifyOpt['agt_trvl_email'][$key];
			$bookingMessages->bkg_trvl_sms		 = $arrAgentNotifyOpt['agt_trvl_sms'][$key];
			$bookingMessages->bkg_trvl_app		 = $arrAgentNotifyOpt['agt_trvl_app'][$key];
			$bookingMessages->bkg_trvl_whatsapp	 = $arrAgentNotifyOpt['agt_trvl_whatsapp'][$key];
			$bookingMessages->bkg_rm_email		 = $arrAgentNotifyOpt['agt_rm_email'][$key];
			$bookingMessages->bkg_rm_sms		 = $arrAgentNotifyOpt['agt_rm_sms'][$key];
			$bookingMessages->bkg_rm_app		 = $arrAgentNotifyOpt['agt_rm_app'][$key];
			$bookingMessages->bkg_rm_whatsapp	 = $arrAgentNotifyOpt['agt_rm_whatsapp'][$key];
			$bookingMessages->save();
		}
	}

	public function setDefaultAgentNotificationForBooking($agentId, $bkgId)
	{
		$arrEvents = AgentMessages::getEvents();
		foreach ($arrEvents as $key => $value)
		{
			$bookingMessages = $this->getByEventAndBookingId($bkgId, $key);
			if ($bookingMessages == '')
			{
				$bookingMessages				 = new BookingMessages();
				$bookingMessages->getMessageDefaults($agentId, $key);
				$bookingMessages->bkg_booking_id = $bkgId;
				$bookingMessages->bkg_event_id	 = $key;
				$bookingMessages->save();
			}
		}
	}

	public function setDataByEditUserInfo($agentNotifyData, $bkgid)
	{
		if ($agentNotifyData != '' && $agentNotifyData != null && $agentNotifyData != 'null')
		{
			$arrAgentNotifyOpt	 = $agentNotifyData;
			$arrEvents			 = AgentMessages::getEvents();
			foreach ($arrEvents as $key => $value)
			{
				$bookingMessages = $this->getByEventAndBookingId($bkgid, $key);
				if ($bookingMessages == '')
				{
					$bookingMessages = new BookingMessages();
				}
				$bookingMessages->bkg_booking_id	 = $bkgid;
				$bookingMessages->bkg_event_id		 = $key;
				$bookingMessages->bkg_agent_email	 = $arrAgentNotifyOpt['agt_agent_email'][$key];
				$bookingMessages->bkg_agent_sms		 = $arrAgentNotifyOpt['agt_agent_sms'][$key];
				$bookingMessages->bkg_agent_app		 = $arrAgentNotifyOpt['agt_agent_app'][$key];
				$bookingMessages->bkg_trvl_email	 = $arrAgentNotifyOpt['agt_trvl_email'][$key];
				$bookingMessages->bkg_trvl_sms		 = $arrAgentNotifyOpt['agt_trvl_sms'][$key];
				$bookingMessages->bkg_trvl_app		 = $arrAgentNotifyOpt['agt_trvl_app'][$key];
				$bookingMessages->bkg_rm_email		 = $arrAgentNotifyOpt['agt_rm_email'][$key];
				$bookingMessages->bkg_rm_sms		 = $arrAgentNotifyOpt['agt_rm_sms'][$key];
				$bookingMessages->bkg_rm_app		 = $arrAgentNotifyOpt['agt_rm_app'][$key];

				$bookingMessages->bkg_rm_whatsapp	 = $arrAgentNotifyOpt['agt_rm_whatsapp'][$key];
				$bookingMessages->bkg_trvl_whatsapp	 = $arrAgentNotifyOpt['agt_trvl_whatsapp'][$key];
				$bookingMessages->bkg_rm_whatsapp	 = $arrAgentNotifyOpt['agt_rm_whatsapp'][$key];

				$bookingMessages->save();
			}

			BookingLog::model()->createLog($model->bkg_id, "Agent notification defaults changed", UserInfo::getInstance(), BookingLog::BOOKING_MODIFIED);
		}
		else
		{
			$arrEvents = AgentMessages::getEvents();
			foreach ($arrEvents as $key => $value)
			{
				$bookingMessages = $this->getByEventAndBookingId($bkgid, $key);
				if ($bookingMessages == '')
				{
					$bookingMessages				 = new BookingMessages();
					$bookingMessages->getMessageDefaults($model->bkg_agent_id, $key);
					$bookingMessages->bkg_booking_id = $bkgid;
					$bookingMessages->bkg_event_id	 = $key;
					$bookingMessages->save();
				}
			}
		}
	}

	public static function messageCommunicationAgentSettings($bkgId, $event)
	{

		$bookingModel	 = Booking::model()->findByPk($bkgId);
		$bookingMessages = BookingMessages::model()->getByEventAndBookingId($bkgId, $event);
		if ($bookingMessages->bkg_agent_sms == 0 && $event == AgentMessages::BOOKING_CONF_WITHOUT_PAYMENTINFO)
		{
			$bookingMessages1				 = BookingMessages::model()->getByEventAndBookingId($bkgId, AgentMessages::BOOKING_CONF_WITH_PAYMENTINFO);
			$bookingMessages->bkg_agent_sms	 = $bookingMessages1->bkg_agent_sms;
		}
		$agentModel	 = Agents::model()->findByPk($bookingModel->bkg_agent_id);
		$userType	 = EmailLog::Agent;
		if ($agentModel->agt_type == 1)
		{
			$userType = EmailLog::Corporate;
		}
		$response = Contact::referenceUserData($bookingModel->bkgUserInfo->bui_id, 3);
		if ($response->getStatus())
		{
			$contactNo	 = $response->getData()->phone['number'];
			$countryCode = $response->getData()->phone['ext'];
			$email		 = $response->getData()->email['email'];
			$firstName	 = $response->getData()->email['firstName'];
			$lastName	 = $response->getData()->email['lastName'];
		}

		$customerArr = [];
		$adminArr	 = [];
		$agentArr	 = [];

		if ($bookingModel->bkgPref->bkg_send_sms == 1 && $bookingMessages != '' && $bookingMessages->bkg_trvl_whatsapp == 1 && $contactNo != '')
		{
			$customerArr[TemplateMaster::SEQ_WHATSAPP_CODE] = ['phone' => $contactNo, 'country_code' => $countryCode, 'name' => $firstName . " " . $lastName];
		}

		if ($bookingModel->bkgPref->bkg_send_sms == 1 && $bookingMessages != '' && $bookingMessages->bkg_trvl_sms == 1 && $contactNo != '')
		{
			$customerArr[TemplateMaster::SEQ_SMS_CODE] = ['phone' => $contactNo, 'country_code' => $countryCode, 'name' => $firstName . " " . $lastName];
		}

		if ($bookingModel->bkgPref->bkg_send_email == 1 && $bookingMessages != '' && $bookingMessages->bkg_trvl_email == 1 && $email != '')
		{
			$customerArr[TemplateMaster::SEQ_EMAIL_CODE] = ['email' => $email, 'name' => $firstName . " " . $lastName];
		}

		if ($bookingModel->bkgPref->bkg_send_sms == 1 && $bookingMessages != '' && $bookingMessages->bkg_rm_whatsapp == 1 && $agentModel->agt_copybooking_admin_phone != '')
		{
			if ($agentModel->agt_copybooking_admin_id != '')
			{
				$adminModel								 = Admins::model()->findByPk($agentModel->agt_copybooking_admin_id);
				$agentModel->agt_copybooking_admin_email = $adminModel->adm_email;
				$adminName								 = $adminModel->adm_fname;
			}
			$adminArr[TemplateMaster::SEQ_WHATSAPP_CODE] = ['phone' => $agentModel->agt_copybooking_admin_phone, 'country_code' => '91', 'name' => $adminName];
		}

		if ($bookingModel->bkgPref->bkg_send_sms == 1 && $bookingMessages != '' && $bookingMessages->bkg_rm_sms == 1 && $agentModel->agt_copybooking_admin_phone != '')
		{
			if ($agentModel->agt_copybooking_admin_id != '')
			{
				$adminModel								 = Admins::model()->findByPk($agentModel->agt_copybooking_admin_id);
				$agentModel->agt_copybooking_admin_email = $adminModel->adm_email;
				$adminName								 = $adminModel->adm_fname;
			}
			$adminArr[TemplateMaster::SEQ_SMS_CODE] = ['phone' => $agentModel->agt_copybooking_admin_phone, 'country_code' => '91', 'name' => $adminName];
		}

		if ($bookingModel->bkgPref->bkg_send_email == 1 && $bookingMessages != '' && $bookingMessages->bkg_rm_email == 1 && $agentModel->agt_copybooking_admin_email != '')
		{
			if ($agentModel->agt_copybooking_admin_id != '')
			{
				$adminModel								 = Admins::model()->findByPk($agentModel->agt_copybooking_admin_id);
				$agentModel->agt_copybooking_admin_email = $adminModel->adm_email;
				$adminName								 = $adminModel->adm_fname;
			}
			$adminArr[TemplateMaster::SEQ_EMAIL_CODE] = ['email' => $agentModel->agt_copybooking_admin_email, 'name' => $adminName];
		}


		if ($bookingModel->bkgPref->bkg_send_sms == 1 && $bookingMessages != '' && $bookingMessages->bkg_agent_whatsapp == 1 && $bookingModel->bkgUserInfo->bkg_crp_phone != '')
		{
			$agentArr[TemplateMaster::SEQ_WHATSAPP_CODE] = ['phone' => $bookingModel->bkgUserInfo->bkg_crp_phone, 'country_code' => $bookingModel->bkgUserInfo->bkg_crp_country_code];
		}
		if ($bookingModel->bkgPref->bkg_send_sms == 1 && $bookingMessages != '' && $bookingMessages->bkg_agent_sms == 1 && $bookingModel->bkgUserInfo->bkg_crp_phone != '')
		{
			$agentArr[TemplateMaster::SEQ_SMS_CODE] = ['phone' => $bookingModel->bkgUserInfo->bkg_crp_phone, 'country_code' => $bookingModel->bkgUserInfo->bkg_crp_country_code];
		}
		if ($bookingModel->bkgPref->bkg_send_email == 1 && $bookingMessages != '' && $bookingModel->bkgUserInfo->bkg_crp_email != '' && $bookingMessages->bkg_agent_email == 1)
		{
			$agentArr[TemplateMaster::SEQ_EMAIL_CODE] = ['email' => $bookingModel->bkgUserInfo->bkg_crp_email, 'name' => $bookingModel->bkgUserInfo->bkg_crp_name];
		}
		return [UserInfo::TYPE_CONSUMER => $customerArr, UserInfo::TYPE_ADMIN => $adminArr, UserInfo::TYPE_AGENT => $agentArr];
	}

}

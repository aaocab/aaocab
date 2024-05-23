<?php

/**
 * This is the model class for table "email_log".
 *
 * The followings are the available columns in table 'email_log':
 * @property integer $id
 * @property integer $elg_mail_type
 * @property string $elg_from_name
 * @property string $elg_from_email
 * @property integer $elg_type
 * @property string $elg_ref_id
 * @property integer $elg_ref_type
 * @property string $address
 * @property string $to_name
 * @property string $subject
 * @property string $body
 * @property string $elg_file_path
 * @property string $created
 * @property string $booking_id
 * @property integer $recipient
 * @property string $delivered
 * @property integer $status
 * @property string $status_date
 * @property integer $elg_bkg_status
 * @property string $attachments
 */
class EmailLog extends CActiveRecord
{

	const Consumers							 = 1;
	const Vendor								 = 2;
	const Driver								 = 3;
	const Admin								 = 4;
	const MeterDown							 = 5;
	const Agent								 = 6;
	const Corporate							 = 7;
	const EMAIL_BOOKING_VERIFICATION_CODE		 = 1;  // verificationEmail()
	const EMAIL_BOOKING_CREATED				 = 2;  // gotBookingemail()
	const EMAIL_CAB_ASSIGNED					 = 3; // cabAssignemail()
	const EMAIL_BOOKING_CONFIRM				 = 4;  // confirmBookingemail()
	const EMAIL_MARK_COMPLETE					 = 5; // markComplete()
	const EMAIL_PROMO_CODE					 = 6;   // sendPromocode()
	const EMAIl_USER_ACCOUNT_CONFIRM			 = 7; // confirmBookingEmailByUserId();
	const EMAIL_INVOICE						 = 8;   //sendInvoice()
	const EMAIL_MISSING_DRIVER_CAR			 = 9;
	const EMAIL_RETURN_TRIP					 = 10;
	const EMAIL_BOOK_GOZO_AGAIN				 = 11;
	const EMAIL_PAYMENT_FAILED				 = 12;
	const RECONFIRM_BEFORE_PICKUP				 = 13;
	const EMAIL_RESCHEDULE_REQUEST			 = 14;
	const EMAIL_CANCEL_TRIP					 = 15;
	const EMAIL_REMINDER_ADVANCE				 = 16;
	const EMAIL_VENDOR_NOTIFY_CUSTOMER_MAIL	 = 17;
	const EMAIL_DRIVER_NOTIFY_CUSTOMER_MAIL	 = 18;
	const EMAIL_UNVERIFIED_FOLLOWUP			 = 19;
	const PRE_AUTO_CANCEL_BEFORE_PICKUP		 = 20;
	const POST_AUTO_CANCEL_BEFORE_PICKUP		 = 21;
	const EMAIL_REMIND_RETURN_TRIP			 = 22;
	const EMAIL_REG_COMPLETE_FASTER			 = 23;
	const EMAIL_PRICE_GUARANTEE				 = 24;
	const EMAIL_CAB_DRIVER_DETAIL				 = 25;
	const EMAIl_VENDOR_AGREEMENT				 = 26;
	const EMAIl_VENDOR_INVOICE				 = 27;
	const EMAIL_AGENT_FORGOT_PASS				 = 28;
	const EMAIL_FLEXXI_MATCHED				 = 29;
	const EMAIL_FLEXXI_ALERT					 = 30;
	const EMAIL_ROUTE_SUGGEST_RE1				 = 31;
	const EMAIL_CSR_NOTIFY_REVIEW_MAIL		 = 32;
	const EMAIL_USER_NOTIFY_REVIEW_MAIL		 = 33;
	const EMAIL_VENDOR_APPROVE				 = 34;
	const EMAIL_AGENT_AGREEMENT				 = 35;
	const EMAIL_ALERT_CRITICALITY				 = 36;
	const EMAIL_LEAD_FOLLOWUP					 = 37;
	const EMAIL_UNVERIFIED_FINAL_FOLLOWUP		 = 38;
	const EMAIL_AGENT_NOTIFY_CANCEL_FLAG		 = 39;
	const EMAIL_VOUCHER_CONFIRM				 = 40;
	const EMAIL_VOUCHER_SUBSCRIBER			 = 41;
	const EMAIL_REFUND_WALLET_TO_SOURCE		 = 42;
	const EMAIL_REFUND_WALLET_TO_BANK			 = 43;
	const EMAIL_CUSTOM						 = 44;
	//elg_ref_type
	const REF_BOOKING_ID						 = 1;
	const REF_USER_ID							 = 2;
	const REF_VENDOR_ID						 = 3;
	const REF_DRIVER_ID						 = 4;
	const REF_ADMIN_ID						 = 5;
	const REF_AGENT_ID						 = 6;
	//elg_ref_type

	const SEND_ACCOUNT_EMAIL			 = 1;   // sendAccountsEmail()
	const SEND_SERVICE_EMAIL			 = 2;   // sendServicesEmail()
	const SEND_VENDOR_BATCH_EMAIL		 = 3;   // sendAccountsEmail()
	const SEND_CONSUMER_BATCH_EMAIL	 = 4;   // sendAccountsEmail()
	const SEND_AGENT_EMAIL			 = 5;  // sendAgentEmail()
	const SEND_DRIVER_EMAIL			 = 6; // sendDriverEmail()
	const SEND_METERDOWN_EMAIL		 = 7; // sendMeterDownEmail()
	const SEND_ADMIN_EMAIL			 = 8;
	const SEND_DAILY_EMAIL			 = 9;
	const SEND_CUSTOM_EMAIL			 = 10;
	// elg_status
	const EMAIL_CONTENT_BLANK			 = 3;

	// elg_status

	public $recipient_arr	 = [
		'1'	 => 'Consumer',
		'2'	 => 'Vendor',
		'3'	 => 'Driver',
		'4'	 => 'Admin',
		'5'	 => 'MeterDown',
		'6'	 => 'Agent',
		'7'	 => 'Corporate',
	];
	public $emailTypeArr	 = ['1'	 => 'Sent Verification Code',
		'2'	 => 'Booking Created',
		'3'	 => 'Cab Assigned',
		'4'	 => 'Booking Confirmation',
		'5'	 => 'Booking Completed',
		'6'	 => 'Sent Promotion Code',
		'7'	 => 'User Account Confirmation',
		'8'	 => 'Email Invoice',
		'9'	 => 'Missing Driver/Car Information',
		'13' => 'Customer Before Pickup',
		'14' => 'Booking Plans Delayed,Please Reschedule',
		'15' => 'Cancellation Booking Email',
		'24' => 'Price Guarantee',
		'26' => 'Vendor Agreement',
		'27' => 'Vendor Invoice',
		'28' => 'Agent Forgot Password',
		'42' => 'Refund wallet to source',
		'43' => 'Refund wallet to bank'];
	public $username, $id, $address, $to_name, $subject, $body, $created, $booking_id, $recipient, $delivered, $status, $status_date, $attachments;
	public $sendDate1, $sendDate2;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'email_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{

		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('elg_address', 'required', 'on' => 'insert'),
			array('elg_type, elg_recipient', 'numerical', 'integerOnly' => true),
			array('elg_address', 'length', 'max' => 100),
			array('elg_subject', 'length', 'max' => 510),
			array('elg_booking_id', 'length', 'max' => 225),
			array('elg_delivered', 'length', 'max' => 255),
			array('elg_id, elg_address, elg_to_name, elg_attachments, elg_subject, elg_content, elg_file_path, elg_created, elg_booking_id, elg_recipient, elg_delivered, elg_type, elg_from_name, elg_from_email, elg_mail_type, elg_status_date, elg_bkg_status, elg_status,username,sendDate1, sendDate2', 'safe'),
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
			'elg_id'		 => 'ID',
			'elg_type'		 => 'Elg Type',
			'elg_address'	 => 'Address',
			'elg_subject'	 => 'Subject',
			'elg_content'	 => 'Body',
			'elg_created'	 => 'Created',
			'elg_booking_id' => 'Booking ID',
			'elg_recipient'	 => 'Recipient',
			'elg_delivered'	 => 'Delivered',
			'elg_type'		 => 'Email Type',
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

		$criteria->compare('elg_id', $this->elg_id);
		$criteria->compare('elg_type', $this->elg_type);
		$criteria->compare('elg_address', $this->elg_address, true);
		$criteria->compare('elg_subject', $this->elg_subject, true);
		$criteria->compare('elg_content', $this->elg_content, true);
		$criteria->compare('elg_created', $this->elg_created, true);
		$criteria->compare('elg_booking_id', $this->elg_booking_id, true);
		$criteria->compare('elg_recipient', $this->elg_recipient);
		$criteria->compare('elg_delivered', $this->elg_delivered, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EmailLog the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function fetchList($dbarchive = NULL)
	{

		$db = ($dbarchive == NULL ) ? ("email_log") : ("gozo_archive.email_log");

		$sql		 = "SELECT elg_id,elg_delivered,elg_recipient,elg_created,elg_content,elg_address,elg_subject,elg_booking_id FROM $db WHERE 1 ";
		$sqlCount	 = "SELECT elg_id FROM $db WHERE 1 ";
		if ($this->elg_address != '')
		{
			$emailAdressArr = explode("@", $this->elg_address);
			if (count($emailAdressArr) > 1)
			{
//				$sql		 .= " AND  MATCH(elg_address) AGAINST('$emailAdressArr[0]_$emailAdressArr[1]*' IN BOOLEAN MODE)";
//				$sqlCount	 .= " AND  MATCH(elg_address) AGAINST('$emailAdressArr[0]_$emailAdressArr[1]*' IN BOOLEAN MODE)";

				$sql		 .= " AND  MATCH(elg_address) AGAINST('\"$this->elg_address*\"' IN BOOLEAN MODE)";
				$sqlCount	 .= " AND  MATCH(elg_address) AGAINST('\"$this->elg_address*\"' IN BOOLEAN MODE)";
			}
			else
			{
				$sql		 .= " AND  MATCH(elg_address) AGAINST('$this->elg_address*' IN BOOLEAN MODE)";
				$sqlCount	 .= " AND  MATCH(elg_address) AGAINST('$this->elg_address*' IN BOOLEAN MODE)";
			}
		}
		if ($this->elg_subject != '')
		{
			$elg_subject = explode("@", $this->elg_subject);
			if (count($elg_subject) > 1)
			{
				$sql		 .= " AND  MATCH(elg_subject) AGAINST('\"$this->elg_subject*\"' IN BOOLEAN MODE)";
				$sqlCount	 .= " AND  MATCH(elg_subject) AGAINST('\"$this->elg_subject*\"' IN BOOLEAN MODE)";
			}
			else
			{
				$sql		 .= " AND MATCH(elg_subject)  AGAINST('$this->elg_subject*' IN BOOLEAN MODE)";
				$sqlCount	 .= " AND MATCH(elg_subject)  AGAINST('$this->elg_subject*' IN BOOLEAN MODE)";
			}
		}
		if ($this->elg_booking_id != '')
		{

			/* $elg_booking_i = explode("@", $this->elg_booking_id);
			  if (count($elg_booking_id) > 1)
			  {
			  $sql		 .= " AND  MATCH(elg_booking_id) AGAINST('\"$this->elg_booking_id*\"' IN BOOLEAN MODE)";
			  $sqlCount	 .= " AND  MATCH(elg_booking_id) AGAINST('\"$this->elg_booking_id*\"' IN BOOLEAN MODE)";
			  } */
			if (is_numeric(trim($this->elg_booking_id)))
			{
				$sql		 .= " AND  elg_ref_id = " . trim($this->elg_booking_id);
				$sqlCount	 .= " AND  elg_ref_id = " . trim($this->elg_booking_id);
			}
			else
			{
				$sql		 .= " AND  MATCH(elg_booking_id) AGAINST('$this->elg_booking_id*' IN BOOLEAN MODE)";
				$sqlCount	 .= " AND  MATCH(elg_booking_id) AGAINST('$this->elg_booking_id*' IN BOOLEAN MODE)";
			}
		}
		if ($this->elg_content != '')
		{
			$elg_content = explode("@", $this->elg_content);
			if (count($elg_content) > 1)
			{
				$sql		 .= " AND  MATCH(elg_content) AGAINST('\"$this->elg_content*\"' IN BOOLEAN MODE)";
				$sqlCount	 .= " AND  MATCH(elg_content) AGAINST('\"$this->elg_content*\"' IN BOOLEAN MODE)";
			}
			else
			{
				$sql		 .= " AND MATCH(elg_content) AGAINST('$this->elg_content*'  IN BOOLEAN MODE )";
				$sqlCount	 .= " AND MATCH(elg_content) AGAINST('$this->elg_content*'  IN BOOLEAN MODE )";
			}
		}
		if ($this->sendDate1 != '' && $this->sendDate2 != '')
		{
			$sql		 .= " AND elg_created BETWEEN '" . $this->sendDate1 . " 00:00:00' AND '" . $this->sendDate2 . " 23:59:59' ";
			$sqlCount	 .= " AND elg_created BETWEEN '" . $this->sendDate1 . " 00:00:00' AND '" . $this->sendDate2 . " 23:59:59' ";
		}
//		if ($dbarchive != NULL)
//		{
//			$sql		 .= " AND elg_ref_type = 2";
//			$sqlCount	 .= "  AND elg_ref_type = 2";
//		}
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes'	 => [],
				'defaultOrder'	 => 'elg_created DESC'
			],
			'pagination'	 => ['pageSize' => 100],
		]);
		return $dataprovider;
	}

	public function getRecipientName($elg_recipient = "")
	{
		$userArr = $this->recipient_arr;
		if ($elg_recipient != "")
		{
			return $userArr[$elg_recipient];
		}
		else
		{
			return $userArr[$this->elg_recipient];
		}
	}

	public function getEmailType($elgId)
	{
		$list = $this->emailTypeArr;
		return $list[$elgId];
	}

	public function ifDuplicate($elgId, $bkgcodeid)
	{
		$criteria = new CDbCriteria;
		$criteria->compare('elg_type', $elgId);
		$criteria->compare('elg_booking_id', $bkgcodeid);
		return $this->find($criteria);
	}

	public function statusUpdateById($elgId, $delivered = '', $from, $fromName)
	{
		$model					 = EmailLog::model()->resetScope()->findByPk($elgId);
		$model->elg_from_email	 = $from;
		$model->elg_from_name	 = $fromName;
		$model->elg_status		 = 1;
		$model->elg_delivered	 = $delivered;
		if ($model->update())
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function sentInactiveMails($mailType, $limit)
	{
		$query		 = "";
		$limitQuery	 = "";
		if ($mailType != '')
		{
			$query .= " AND elg_mail_type=$mailType";
		}
		if ($limit != '')
		{
			$limitQuery = " LIMIT 0, $limit";
		}

		// Server Id
		$serverId	 = Config::getServerID();
		$query		 .= " AND elg_file_path LIKE '/{$serverId}/mails/%' ";

		$sql = "SELECT email_log.elg_id, email_log.elg_status, email_log.elg_mail_type, email_log.elg_file_path,
					email_log.elg_content, email_log.elg_address, email_log.elg_type, email_log.elg_subject, email_log.elg_attachments,
					CONCAT(booking_user.bkg_user_fname,' ',booking_user.bkg_user_lname) as username
                FROM `email_log`
                LEFT JOIN `booking` ON booking.bkg_id=email_log.elg_ref_id
                LEFT JOIN booking_user ON booking_user.bui_bkg_id = booking.bkg_id
                WHERE email_log.elg_status=2 AND elg_s3_data<>'{}' AND email_log.elg_status_date <= NOW() $query ORDER BY `elg_status_date` ASC $limitQuery";

		$data	 = DBUtil::query($sql, DBUtil::SDB());
		$a		 = 0;

		foreach ($data as $d)
		{
			/** @var EmailLog $elgModel */
			$elgModel = EmailLog::model()->findByPk($d['elg_id']);

			if ($d['elg_status'] != 2)
			{
				continue;
			}

			$mail = EIMailer::getInstance($d['elg_mail_type']);
			//$mail->SMTPDebug = 2;

			$body		 = $elgModel->getContents();
			$userName	 = $d['username'];
			$email		 = strtolower(str_replace(' ', '', trim($d['elg_address'])));
			if (!Filter::validateEmail($email))
			{
				$param		 = ['elg_id' => $d['elg_id']];
				$updateQuery = "UPDATE email_log SET elg_status = 0 WHERE elg_id =:elg_id";
				DBUtil::execute($updateQuery, $param);
				continue;
			}
			$bccMail = ($d['elg_type'] == 26) ? Yii::app()->params['mail']['agreementMail']['Username'] : '';
			$mail->setBody($body);
			$mail->setTo($email, $userName);
			if (trim($bccMail) != '')
			{
				$mail->setBcc($bccMail);
			}
			$subject		 = $d['elg_subject'];
			$mail->setSubject($subject);
			$attachmentArray = json_decode($d['elg_attachments']);

			foreach ($attachmentArray as $attach)
			{
				if ($attach->URL != '')
				{
					$url		 = $attach->URL;
					$ch			 = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$attachPath	 = curl_exec($ch);
					curl_close($ch);
					$mail->setAttachment($attachPath);
				}
				else if ($attach->PATH != '')
				{
					$attachPath = $attach->PATH;
					$mail->setAttachment($attachPath);
				}
			}
			$isSend = $mail->sendMail(0);
			if ($isSend)
			{
				$delivered = $mail->Username . " Email sent successfully";
				Logger::create($delivered, CLogger::LEVEL_INFO);
				$this->statusUpdateById($d['elg_id'], $delivered, $mail->Username, $mail->FromName);
			}
			else
			{
				$delivered = "Email not sent - ELGID: " . $d['elg_id'] . " - BCC: " . $bccMail . " - EMAIL: " . $email . " - USERNAME: " . $userName . " - {$mail->getError()}";
				Logger::create($delivered, CLogger::LEVEL_ERROR);
			}
			echo $email . " - " . $userName . " - " . $delivered . " ({$d['elg_id']})";
		}
	}

	public function showRefTypes($bkgId, $refId)
	{
		$arrResults		 = DBUtil::command("SELECT DISTINCT email_log.elg_recipient FROM `email_log` WHERE elg_ref_id=$bkgId AND elg_id=$refId")->queryAll();
		$arrRecipients	 = $this->recipient_arr;
		$strRecipients	 = "";
		foreach ($arrResults as $value)
		{
			$strRecipients = $strRecipients . $arrRecipients[$value['elg_recipient']] . ", ";
		}
		return "(" . rtrim($strRecipients, ", ") . ")";
	}

	public function showRefTypesAgent($bkgId, $refId)
	{
		$arrResult = DBUtil::command("SELECT DISTINCT email_log.elg_type FROM `email_log` WHERE elg_ref_id=$bkgId AND elg_id=$refId")->queryRow();
		if ($arrResult['elg_type'] != '')
		{
			$arrResults = DBUtil::command("SELECT DISTINCT email_log.elg_recipient FROM `email_log` WHERE elg_ref_id=$bkgId AND elg_type={$arrResult['elg_type']}")->queryAll();
		}
		$arrRecipients	 = $this->recipient_arr;
		$strRecipients	 = "";
		foreach ($arrResults as $value)
		{
			$strRecipients = $strRecipients . $arrRecipients[$value['elg_recipient']] . ", ";
		}
		return "(" . rtrim($strRecipients, ", ") . ")";
	}

	public function checkIfAlreadySent($refId, $refType, $elgType, $interval = 0)
	{
		if ($interval != 0 && $interval != '')
		{
			$cond = " AND elg_created BETWEEN DATE_SUB(NOW(),INTERVAL $interval MINUTE) AND NOW()";
		}
		$query		 = "SELECT count(1) FROM `email_log` WHERE elg_ref_id=$refId AND elg_ref_type=$refType AND elg_type=$elgType AND elg_status=1 $cond";
		$totEmail	 = DBUtil::command($query)->queryScalar();
		if ($totEmail > 0)
		{
			return true;
		}
		return false;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @return integer
	 */
	public static function checkBookingConfirmed($bkgId)
	{
		$params	 = ['id' => $bkgId];
		$sql = "SELECT COUNT(1) as chkConfirmMail 
				FROM `booking`
                INNER JOIN `email_log` ON email_log.elg_ref_id=booking.bkg_id AND email_log.elg_type=4
                WHERE booking.bkg_active=1 AND email_log.elg_status=1
				AND booking.bkg_id='$id'";
		return DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);	
	}

	/**
	 * Function for archiving email log data
	 * @param $archiveDB
	 */
	public function archiveEmailContentData($archiveDB)
	{
		$status		 = EmailLog::EMAIL_CONTENT_BLANK;
		$i			 = 0;
		$chk		 = true;
		$totRecords	 = 100000;
		$limit		 = 1000;
		while ($chk)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$sql	 = "SELECT GROUP_CONCAT(`elg_id`) AS elg_id FROM (SELECT `elg_id` FROM email_log el  WHERE elg_status NOT IN ($status) AND el.elg_created < CONCAT(DATE_SUB(CURDATE(), INTERVAL 6 MONTH), ' 23:59:59') ORDER BY elg_id LIMIT 0, $limit) as temp";
				$resQ	 = DBUtil::command($sql, DBUtil::MDB())->queryScalar();
				if (!is_null($resQ) && $resQ != '')
				{
					$sql	 = "INSERT INTO " . $archiveDB . ".`email_log` (SELECT * FROM `email_log` WHERE elg_id IN ($resQ))";
					$rows	 = DBUtil::command($sql)->execute();
					if ($rows > 0)
					{
						$sql		 = "Update email_log set elg_content='',elg_status=$status WHERE elg_id IN ($resQ)  ";
						$rowsUpdate	 = DBUtil::command($sql)->execute();
					}
				}
				DBUtil::commitTransaction($transaction);
				$i += $limit;
				if (($resQ <= 0) || $totRecords <= $i)
				{
					break;
				}
			}
			catch (Exception $e)
			{
				echo $e->getMessage();
				echo "\r\n";
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	public function deleteEmail()
	{
		$status		 = EmailLog::EMAIL_CONTENT_BLANK;
		$i			 = 0;
		$chk		 = true;
		$totRecords	 = 100000;
		$limit		 = 1000;
		while ($chk)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{

				$sql	 = "SELECT GROUP_CONCAT(`elg_id`) AS elg_id FROM (SELECT `elg_id` FROM email_log el  WHERE elg_status IN ($status) AND el.elg_created < CONCAT(DATE_SUB(CURDATE(), INTERVAL 12 MONTH), ' 23:59:59') ORDER BY elg_id LIMIT 0, $limit) as temp";
				$resQ	 = DBUtil::command($sql, DBUtil::MDB())->queryScalar();
				if (!is_null($resQ) && $resQ != '')
				{
					$sql		 = "DELETE from email_log WHERE elg_id IN ($resQ)  ";
					$rowsUpdate	 = DBUtil::command($sql)->execute();
				}
				DBUtil::commitTransaction($transaction);
				$i += $limit;
				if (($resQ <= 0) || $totRecords <= $i)
				{
					break;
				}
			}
			catch (Exception $e)
			{
				echo $e->getMessage();
				echo "\r\n";
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	public function getBasePath()
	{
		$path			 = Yii::app()->basePath;
		$mainfoldername	 = $path . DIRECTORY_SEPARATOR . 'doc';
		return realpath($mainfoldername);
	}

	public function getFileName()
	{
		$fileName = $this->elg_ref_type . '_' . $this->elg_id . '_' . $this->elg_type . '.gml';
		return $fileName;
	}

	public function getFilePath()
	{
		$type = $this->elg_type;
		if ($type == '')
		{
			$type = 0;
		}

		$path = DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . Filter::createFolderPrefix(strtotime($this->elg_created));

		$filepath = $path . DIRECTORY_SEPARATOR . $this->getFileName();
		return $filepath;
	}

	public function getLocalPath()
	{
		$filePath = $this->getBasePath() . $this->elg_file_path;
		return $filePath;
	}

	public function getSpacePath()
	{
		$mailType = $this->elg_mail_type;
		if ($mailType == '')
		{
			$mailType = 0;
		}
		$type = $this->elg_type;
		if ($type == '')
		{
			$type = 0;
		}
		$refType = $this->elg_ref_type;
		if ($refType == '')
		{
			$refType = 0;
		}

		$dateString	 = DateTimeFormat::SQLDateTimeToDateTime($this->elg_created)->format("Y/m/d");
		$path		 = "mails/{$mailType}/{$refType}/{$type}/{$dateString}/{$this->elg_id}.eml";
		return $path;
	}

	/**
	 * @return Stub\common\SpaceFile
	 */
	public function uploadToSpace($removeLocal = true, $saveJSON = true)
	{
		$spaceFile		 = $this->getSpacePath();
		$localFile		 = $this->getLocalPath();
		$objSpaceFile	 = Storage::uploadText(Storage::getMailSpace(), $spaceFile, $localFile, $removeLocal);
		if ($saveJSON && $objSpaceFile != null)
		{
			$this->elg_s3_data = $objSpaceFile->toJSON();
			$this->save();
		}
		return $objSpaceFile;
	}

	/** @return SpacesAPI\File */
	public function getSpaceFile()
	{
		$objSpaceFile = Stub\common\SpaceFile::populate($this->elg_s3_data);
		return Storage::getFile($objSpaceFile->getSpace(), $objSpaceFile->key);
	}

	public function removeSpaceFile()
	{
		$objSpaceFile = Stub\common\SpaceFile::populate($this->elg_s3_data);
		return Storage::removeFile($objSpaceFile->bucket, $objSpaceFile->key);
	}

	public function getSignedURL()
	{
		$url = null;
		if ($this->elg_s3_data != null && $this->elg_s3_data != '{}')
		{
			$objSpaceFile		 = Stub\common\SpaceFile::populate($this->elg_s3_data);
			$url				 = $objSpaceFile->getURL(strtotime("+60 minute"));
			$this->elg_s3_data	 = $objSpaceFile->toJSON();
			$this->save();
		}
		return $url;
	}

	public static function uploadAllToS3($limit = 10000)
	{
		while ($limit > 0)
		{
			$limit1 = min([1000, $limit]);

			// Server Id
			$serverId = Config::getServerID();
			if ($serverId == '' || $serverId <= 0)
			{
				Logger::writeToConsole('Server ID not found!!!');
				break;
			}
			$cond = " AND elg_file_path LIKE '/{$serverId}/mails/%' ";

			$sql = "SELECT elg_id FROM email_log WHERE elg_s3_data IS NULL {$cond} ORDER BY elg_id DESC LIMIT 0, $limit1";

			$res = DBUtil::query($sql, DBUtil::MDB());
			if ($res->getRowCount() == 0)
			{
				break;
			}

			foreach ($res as $row)
			{
				/** @var EmailLog $elgModel */
				$elgModel = EmailLog::model()->findByPk($row["elg_id"]);
				$elgModel->uploadToS3();
				Logger::writeToConsole($elgModel->elg_s3_data);
			}

			$limit -= $limit1;
			Logger::flush();
		}
	}

	/** @return Stub\common\SpaceFile */
	public function uploadToS3()
	{
		$elgModel	 = $this;
		$path		 = $this->getLocalPath();

		if (!file_exists($path) || $this->elg_file_path == '')
		{
			if ($elgModel->elg_s3_data == '')
			{
				$elgModel->elg_s3_data = "{}";
				$elgModel->save();
			}
			return null;
		}
		$spaceFile = $elgModel->uploadToSpace();
		return $spaceFile;
	}

	public function getContents()
	{
		$elgModel = $this;
		if (file_exists($elgModel->getLocalPath()))
		{
			$body = file_get_contents($elgModel->getLocalPath());
		}
		elseif ($elgModel->elg_s3_data != '' && ($file = $elgModel->getSpaceFile()) != null)
		{
			$body = $file->getContents();
		}
		else
		{
			$body = nl2br($elgModel->elg_content);
		}

		return $body;
	}

	public function getExpairedDaysReviewLink($bookingcode)
	{
		$now		 = date('Y-m-d H:i:s');
		$sql		 = "SELECT	elg_created
			FROM email_log
			WHERE elg_booking_id = :bookingCode AND elg_type = :type";
		$arrResponse = DBUtil::queryRow($sql, DBUtil::SDB(), ['bookingCode' => $bookingcode, 'type' => EmailLog::EMAIL_INVOICE]);
		$emailDate	 = $arrResponse['elg_created'];
		$dateDiff	 = round((strtotime($now) - strtotime($emailDate)) / (60 * 60 * 24));
		return $dateDiff;
	}

}

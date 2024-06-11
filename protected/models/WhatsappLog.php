<?php

use components\Event\EventSchedule;

/**
 * This is the model class for table "whatsapp_log".
 *
 * The followings are the available columns in table 'whatsapp_log':
 * @property integer $whl_id
 * @property string $whl_wam_id
 * @property integer $whl_entity_type
 * @property integer $whl_entity_id
 * @property string $whl_phone_number
 * @property integer $whl_message_type
 * @property string $whl_message
 * @property string $whl_message_component
 * @property integer $whl_media_id
 * @property string $whl_media_type
 * @property integer $whl_wht_id
 * @property integer $whl_ref_type
 * @property integer $whl_ref_id
 * @property string $whl_created_by_name
 * @property integer $whl_created_by_type
 * @property integer $whl_created_by_id
 * @property string $whl_created_date
 * @property string $whl_sent_date
 * @property string $whl_sent_response
 * @property string $whl_delivered_date
 * @property string $whl_read_date
 * @property integer $whl_status
 * @property string $whl_gozo_phone_id
 * @property string $whl_gozo_phone_number
 * @property string $whl_replying_id
 * @property string $whl_payload
 */
class WhatsappLog extends CActiveRecord
{

	const MSG_TYPE_TEXT		 = 1;
	const MSG_TYPE_MEDIA		 = 2;
	const MSG_TYPE_CONTACT	 = 3;
	const MSG_TYPE_LOCATION	 = 4;
	const MSG_TYPE_REACTION	 = 5;
	const MSG_TYPE_BUTTON		 = 6;
	const MSG_TYPE_INTERACTIVE = 7;
	const MSG_TYPE_ORDER		 = 8;
	const MSG_TYPE_SYSTEM		 = 9;
	const MSG_TYPE_UNKNOWN	 = 10;
	const REF_TYPE_BOOKING	 = 1;
	const REF_TYPE_TRIP		 = 2;
	const REF_TYPE_PROMOTION	 = 3;
	const REF_TYPE_VENDOR		 = 11;
	const REF_TYPE_USER		 = 12;

	public $whl_created_on1;
	public $whl_created_on2;
	public $templatename;
	public $sendDate1, $sendDate2, $deliveryDate1, $deliveryDate2, $readDate1, $readDate2, $phoneno;
	public $createdByType	 = [1 => "Consumer/User", 2 => "Vendor", 3 => "Driver", 4 => "Admin", 5 => "System"];
	public $status			 = ["1" => "Pending", "2" => "Sent", "3" => "Failed", "4" => "Received"];
	public $lang_code		 = ["1" => "en_US", "2" => "hi", "3" => "ta", "4" => "te", "5" => "ml", "6" => "kn"];
	public $msg_type		 = ["1" => "TEXT", "2" => "MEDIA", "3" => "CONTACT", "4" => "LOCATION", "5" => "REACTION", "6" => "BUTTON", "7" => "INTERACTIVE", "8" => "ORDER", "9" => "SYSTEM", "10" => "UNKNOWN"];
	public $ref_type		 = ["1" => "Booking", "2" => "Trip", "3" => "Promotion"];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'whatsapp_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('whl_phone_number, whl_created_by_type, whl_created_date, whl_status', 'required'),
			array('whl_entity_type, whl_entity_id, whl_message_type, whl_media_id, whl_wht_id, whl_ref_type, whl_ref_id, whl_created_by_type, whl_created_by_id, whl_status', 'numerical', 'integerOnly' => true),
			array('whl_wam_id', 'length', 'max' => 200),
			array('whl_phone_number', 'length', 'max' => 20),
			array('whl_message, whl_sent_response', 'length', 'max' => 1000),
			array('whl_message_component, whl_sent_date, whl_delivered_date, whl_read_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('whl_id, whl_wam_id, whl_entity_type, whl_entity_id, whl_phone_number, whl_message_type, whl_message, whl_message_component, whl_media_id, whl_media_type, whl_wht_id, whl_ref_type, whl_ref_id, whl_created_by_name, whl_created_by_type, whl_created_by_id, whl_created_date, whl_sent_date, whl_sent_response, whl_delivered_date, whl_read_date, whl_status,whl_gozo_phone_id,whl_gozo_phone_number,whl_replying_id,whl_payload', 'safe', 'on' => 'search'),
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
			'whl_id'				 => 'Whl',
			'whl_wam_id'			 => 'Whl Wam',
			'whl_entity_type'		 => '1=> Consumer, 2=> Vendor, 3=> Driver, 4=> Admin',
			'whl_entity_id'			 => 'Whl Entity',
			'whl_phone_number'		 => 'Whl Phone Number',
			'whl_message_type'		 => 'Whl Message Type',
			'whl_message'			 => 'Whl Message',
			'whl_message_component'	 => 'Whl Message Component',
			'whl_media_id'			 => 'Whl Media Id',
			'whl_media_type'		 => 'Whl Media Type',
			'whl_wht_id'			 => 'Whl Template Id',
			'whl_ref_type'			 => '1=> Booking, 2=> Trip, 3=> Promotion',
			'whl_ref_id'			 => 'Id',
			'whl_created_by_name'	 => 'Whl Created By Name',
			'whl_created_by_type'	 => '1=> Consumer/User, 2=> Vendor, 3=> Driver, 4=> Admin, 5=> System',
			'whl_created_by_id'		 => 'Whl Created By',
			'whl_created_date'		 => 'Whl Created Date',
			'whl_sent_date'			 => 'Whl Sent Date',
			'whl_sent_response'		 => 'Whl Sent Response',
			'whl_delivered_date'	 => 'Whl Delivery Date',
			'whl_read_date'			 => 'Whl Read Date',
			'whl_status'			 => '1=> Pending, 2=> Sent, 3=> Failed',
			'whl_gozo_phone_id'		 => 'Gozo Phone Id',
			'whl_gozo_phone_number'	 => 'Gozo Phone Number',
			'whl_replying_id'		 => 'Replying Id',
			'whl_payload'			 => 'Button Payload ',
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

		$criteria->compare('whl_id', $this->whl_id);
		$criteria->compare('whl_wam_id', $this->whl_wam_id, true);
		$criteria->compare('whl_entity_type', $this->whl_entity_type);
		$criteria->compare('whl_entity_id', $this->whl_entity_id);
		$criteria->compare('whl_phone_number', $this->whl_phone_number, true);
		$criteria->compare('whl_message_type', $this->whl_message_type, true);
		$criteria->compare('whl_message', $this->whl_message, true);
		$criteria->compare('whl_message_component', $this->whl_message_component, true);
		$criteria->compare('whl_media_id', $this->whl_media_id, true);
		$criteria->compare('whl_media_type', $this->whl_media_type, true);
		$criteria->compare('whl_wht_id', $this->whl_wht_id);
		$criteria->compare('whl_ref_type', $this->whl_ref_type);
		$criteria->compare('whl_ref_id', $this->whl_ref_id);
		$criteria->compare('whl_created_by_name', $this->whl_created_by_name);
		$criteria->compare('whl_created_by_type', $this->whl_created_by_type);
		$criteria->compare('whl_created_by_id', $this->whl_created_by_id);
		$criteria->compare('whl_created_date', $this->whl_created_date, true);
		$criteria->compare('whl_sent_date', $this->whl_sent_date, true);
		$criteria->compare('whl_sent_response', $this->whl_sent_response, true);
		$criteria->compare('whl_delivered_date', $this->whl_delivered_date, true);
		$criteria->compare('whl_read_date', $this->whl_read_date, true);
		$criteria->compare('whl_status', $this->whl_status);
		$criteria->compare('whl_gozo_phone_id', $this->whl_gozo_phone_id, true);
		$criteria->compare('whl_gozo_phone_number', $this->whl_gozo_phone_number, true);
		$criteria->compare('whl_replying_id', $this->whl_replying_id, true);
		$criteria->compare('whl_payload', $this->whl_payload);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WhatsappLog the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function processLog($logData = array(), $wamId = '', $whlId = '')
	{
		try
		{
			$wLog = new WhatsappLog();

			if ($wamId != '')
			{
				$wLog = self::model()->findByAttributes(array('whl_wam_id' => $wamId));
			}
			elseif ($whlId > 0)
			{
				$wLog = self::model()->findByPk($whlId);
			}

			foreach ($logData as $key => $value)
			{
				$wLog->$key = $value;
			}
			$wLog->save();
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}

		return $wLog->whl_id;
	}

	/**
	 * This function is used the whatsapp template id by template name
	 * @param $templateName
	 * @param $fieldName
	 * @return array
	 */
	public static function findByTemplateName($templateName, $fieldName = '')
	{
		$sql = "SELECT * FROM whatsapp_templates WHERE wht_active = 1 AND wht_template_name = '{$templateName}'";
		$row = DBUtil::queryRow($sql, DBUtil::SDB());
		if ($row)
		{
			return (($fieldName != '' && isset($row[$fieldName])) ? $row[$fieldName] : $row);
		}

		return false;
	}

	/**
	 * This function is used the whatSapp template id by template name and language
	 * @param $templateName
	 * @param $lang
	 * @param $fieldName
	 * @return array
	 */
	public static function findByTemplateNameLang($templateName, $lang = 'en_US', $fieldName = '')
	{
		$sql = "SELECT * FROM whatsapp_templates WHERE wht_active = 1 AND wht_template_name = :templateName AND wht_lang_code = :lang";
		$row = DBUtil::queryRow($sql, DBUtil::SDB(), ["templateName" => $templateName, 'lang' => $lang], 60 * 60 * 24, CacheDependency::Type_Report_DashBoard);
		if ($row)
		{
			return (($fieldName != '' && isset($row[$fieldName])) ? $row[$fieldName] : $row);
		}

		return false;
	}

	public static function getWhatsappLogList($requestDetails = null)
	{
		$createDate1	 = $requestDetails->whl_created_on1;
		$createDate2	 = $requestDetails->whl_created_on2;
		$sendDate1		 = $requestDetails->sendDate1;
		$sendDate2		 = $requestDetails->sendDate2;
		$deliveryDate1	 = $requestDetails->deliveryDate1;
		$deliveryDate2	 = $requestDetails->deliveryDate2;
		$readDate1		 = $requestDetails->readDate1;
		$readDate2		 = $requestDetails->readDate2;
		$status			 = $requestDetails->whl_status;
		$phone			 = $requestDetails->whl_phone_number;
		$createdByType	 = $requestDetails->whl_created_by_type;
		$templateName	 = $requestDetails->templatename;
		$refType		 = $requestDetails->whl_ref_type;
		$refId			 = $requestDetails->whl_ref_id;

		$extraJoin	 = $wheresql	 = '';

		if (!empty($createDate1) && !empty($createDate2))
		{
			$wheresql .= " AND whl_created_date BETWEEN '" . $createDate1 . "' AND '" . $createDate2 . "'";
		}

		if (!empty($sendDate1) && !empty($sendDate2))
		{
			$wheresql .= " AND whl_sent_date BETWEEN '" . $sendDate1 . "' AND '" . $sendDate2 . "'";
		}

		if (!empty($deliveryDate1) && !empty($deliveryDate2))
		{
			$wheresql .= " AND whl_delivered_date BETWEEN '" . $deliveryDate1 . "' AND '" . $deliveryDate2 . "'";
		}

		if (!empty($readDate1) && !empty($readDate2))
		{
			$wheresql .= " AND whl_read_date BETWEEN '" . $readDate1 . "' AND '" . $readDate2 . "'";
		}

		if ($refType != '')
		{
			$wheresql .= " AND whatsapp_log.whl_ref_type = '$refType'";
		}

		if ($refId != '')
		{
			$wheresql .= " AND whatsapp_log.whl_ref_id = '$refId'";
		}

		if ($phone != '' && strlen($phone) >= 10)
		{
			$wheresql .= " AND whatsapp_log.whl_phone_number='$phone'";
		}
		else if ($phone != '' && strlen($phone) < 10)
		{
			$wheresql .= " AND whatsapp_log.whl_phone_number LIKE '%$phone%'";
		}

		if ($status)
		{
			$wheresql .= " AND whatsapp_log.whl_status IN($status)";
		}

		if ($createdByType)
		{
			$wheresql .= " AND whatsapp_log.whl_created_by_type IN($createdByType)";
		}

		if ($templateName)
		{
			$wheresql .= " AND whatsapp_log.whl_wht_id IN($templateName)";
		}

//		$join = "LEFT JOIN vendors vnd ON vnd.vnd_id = whatsapp_log.whl_entity_id
//                 LEFT JOIN drivers drv ON drv.drv_id = whatsapp_log.whl_entity_id";

		$getWhatsappLogSql		 = "SELECT  whl_id,
					CASE WHEN whl_entity_type=1 THEN 'Consumer'
						 WHEN whl_entity_type=2 THEN 'Vendor'
						 WHEN whl_entity_type=3 THEN 'Driver'
						 WHEN whl_entity_type=4 THEN 'Admin'
					END as whl_entity_type_name,whl_entity_type,
					whl_entity_id, whl_phone_number, whl_message,
					CASE WHEN whl_ref_type=1 THEN 'Booking'
						 WHEN whl_ref_type=2 THEN 'Trip'
						 WHEN whl_ref_type=3 THEN 'Promotion'
					END as whl_ref_type_name,whl_ref_type,
					whl_ref_id, 
					CASE WHEN whl_created_by_type=1 THEN 'Consumer/User'
						 WHEN whl_created_by_type=2 THEN 'Vendor'
						 WHEN whl_created_by_type=3 THEN 'Driver'
						 WHEN whl_created_by_type=4 THEN 'Admin'
						 WHEN whl_created_by_type=5 OR whl_created_by_type=10 THEN 'SYSTEM'
					END as whl_created_by_type, 
					whl_created_by_id,
					CASE WHEN whl_status=1 THEN 'Pending'
						 WHEN whl_status=2 THEN 'Sent'
						 WHEN whl_status=3 THEN 'Failed'
						 WHEN whl_status=4 THEN 'Received'
					END as whl_status, 
					whl_created_date, whl_sent_date, whl_delivered_date, whl_read_date, wht_template_name,
					if(whl_ref_type=1,bkg1.bkg_id,bkg2.bkg_id) bkg_id
					 FROM whatsapp_log 
					 	LEFT JOIN booking bkg1 ON whl_ref_id = bkg1.bkg_id AND whl_ref_type=1 
						LEFT JOIN booking bkg2 ON whl_ref_id = bkg2.bkg_bcb_id AND whl_ref_type=2
					 LEFT JOIN whatsapp_templates ON wht_id = whl_wht_id 
								 WHERE 1 
									$wheresql	";
		$getWhatsappLogCountSql	 = "SELECT  COUNT(*) FROM (SELECT whl_id FROM whatsapp_log WHERE 1=1 " . $wheresql . ") abc";
		$count					 = DBUtil::queryScalar($getWhatsappLogCountSql, DBUtil::SDB());
		$dataprovider			 = new CSqlDataProvider($getWhatsappLogSql, [
			"totalItemCount" => $count,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['defaultOrder' => 'whl_id DESC'],
			"pagination"	 =>
			[
				"pageSize" => 50
			],
		]);
		return $dataprovider;
	}

	/**
	 * This function is used to send message over WhatsApp
	 * @param $phoneNo
	 * @param $templateName
	 * @param $arrDBData
	 * @param $arrBody
	 * @param $arrButton
	 * @param $lang
	 * @return array
	 */
	public static function send($phoneNo, $templateName, $arrDBData = [], $arrBody = [], $arrButton = [], $lang = 'en_US', $isDelay = false, $skipPermission = false)
	{
		if (!self::allowSend($arrDBData, $templateName, $skipPermission))
		{
			return ['status' => 3, 'wamId' => '', 'error' => 'Whatsapp notification not allowed'];
		}

		$arrComponent['header']	 = [];
		$arrComponent['body']	 = $arrBody;
		$arrComponent['buttons'] = $arrButton;
		$arrComponent['lang']	 = $lang;
		if ($templateName != '')
		{
			$arrDBData['templateId'] = self::findByTemplateNameLang($templateName, $lang, 'wht_id');
		}
		$response = Whatsapp::sendMessage($phoneNo, $arrComponent, $templateName, $arrDBData, $lang, $isDelay);
		return $response;
	}

	/**
	 * This function is used to get whatsApp Template name
	 * @param type $whtId int
	 * @return string template name
	 */
	public static function getTemplateNameById($whtId)
	{
		$sql = "SELECT wht_template_name,wht_lang_code,wht_template_content,wht_lang_code  FROM whatsapp_templates WHERE wht_active = 1 AND wht_id  =:whtId";
		return DBUtil::queryRow($sql, DBUtil::SDB(), ['whtId' => $whtId]);
	}

	public static function getCountByDate($day = 7)
	{

		$sql	 = "  SELECT 
						DATE(whl_sent_date) sent , 
						count(whl_phone_number) countNumber, 
						SUM(IF(whl_delivered_date IS NULL,0,1)) delivered
						FROM `whatsapp_log` 
						WHERE whl_sent_date IS NOT NULL AND 
							whl_sent_date >= DATE_SUB(CURRENT_DATE, INTERVAL $day DAY)   
						GROUP BY sent ";
		$res	 = DBUtil::query($sql, DBUtil::SDB());
		$result	 = [];
		foreach ($res as $value)
		{
			$result[$value['sent']] = $value;
		}
		return $result;
	}

	public static function languageByLangCode($langCode)
	{
		$lang = "";
		switch ($langCode)
		{
			case 'en_US':
				$lang	 = "English(USA)";
				break;
			case 'ta':
				$lang	 = "Tamil";
				break;
			case 'hi':
				$lang	 = "Hindi";
				break;
			case 'te':
				$lang	 = "Telugu";
				break;
			case 'kn':
				$lang	 = "Kannada";
				break;
			case 'ml':
				$lang	 = "Malayalam";
				break;
			case 'en_GB':
				$lang	 = "English(UK)";
				break;
			default:
				$lang	 = "NA";
				break;
		}
		return $lang;
	}

	/**
	 * This function is used for updating the whatSapp every day in the night  
	 */
	public static function updateIsWhatsappVerified()
	{
		$sql = "UPDATE contact_phone
			INNER JOIN whatsapp_log ON contact_phone.phn_full_number=whatsapp_log.whl_phone_number
			SET contact_phone.phn_whatsapp_verified = 1
			WHERE 1 
			AND
			(
				( whl_status=2 AND (whl_delivered_date IS NOT NULL OR whl_read_date IS NOT NULL))
				 OR 
				( whl_status=4)
			)
			AND whatsapp_log.whl_created_date BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY), ' 00:00:00') AND  CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY), ' 23:59:59')
			AND contact_phone.phn_whatsapp_verified=0";
		DBUtil::execute($sql);
	}

	public static function getRecdWhatsappLogList($requestDetails = null)
	{
		$createDate1 = $requestDetails->whl_created_on1;
		$createDate2 = $requestDetails->whl_created_on2;
		$phone		 = $requestDetails->phoneno;

		$extraJoin	 = $wheresql	 = '';

		if (!empty($createDate1) && !empty($createDate2))
		{
			$wheresql .= " AND whl_created_date BETWEEN '" . $createDate1 . "' AND '" . $createDate2 . "'";
		}

		if ($phone != '')
		{
			$wheresql .= " AND whatsapp_log.whl_phone_number LIKE '%$phone%'";
		}
		$wheresql .= " AND whl_status=4";

		$join = "LEFT JOIN vendors vnd ON vnd.vnd_id = whatsapp_log.whl_entity_id
                 LEFT JOIN drivers drv ON drv.drv_id = whatsapp_log.whl_entity_id";

		$groupBy				 = " GROUP BY whl_phone_number";
		$getWhatsappLogSql		 = "SELECT  whl_id,
								whl_entity_id, whl_phone_number, whl_message,
								whl_ref_id, whl_created_by_name,
								whl_created_by_id, 
								whl_created_date, whl_sent_date, whl_delivered_date
								 FROM whatsapp_log 
								 $join
								 WHERE 1 
									$wheresql $groupBy";
		$getWhatsappLogCountSql	 = "SELECT  COUNT(*) FROM (SELECT whl_id FROM whatsapp_log $join WHERE 1=1 " . $wheresql . $groupBy . ") abc";
		$count					 = DBUtil::queryScalar($getWhatsappLogCountSql, DBUtil::SDB());
		$dataprovider			 = new CSqlDataProvider($getWhatsappLogSql, [
			"totalItemCount" => $count,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['defaultOrder' => 'whl_id DESC'],
			"pagination"	 =>
			[
				"pageSize" => 50
			],
		]);
		return $dataprovider;
	}

	/**
	 * 
	 * @param type $phoneNo
	 * @return type
	 */
	public static function getTemplateNameByPhone($phoneNo)
	{
		$sql = "SELECT whl_message, whl_created_date, wht_template_content, wht_lang_code, whl_phone_number,
				CASE WHEN whl_status=1 THEN 'Pending'
					WHEN whl_status=2 THEN 'Sent'
					WHEN whl_status=3 THEN 'Failed'
					WHEN whl_status=4 THEN 'Received'
			   END as whl_status 
				FROM whatsapp_log 
				LEFT JOIN whatsapp_templates wht ON wht.wht_id = whatsapp_log.whl_wht_id
				WHERE whl_status IN(2,4) AND whl_phone_number  =:phoneno";
		return DBUtil::query($sql, DBUtil::SDB(), ['phoneno' => $phoneNo]);
	}

	/**
	 * This function is used to check whether to send WhatSapp notification or not if that number is not been able  read/delivery notification for  in between given interval
	 * @param type $phoneNumber
	 * @param type $startInterval
	 * @param type $endsInterval
	 * @return type string
	 */
	public static function blockWhatsappforNonUsedCompliance($phoneNumber, $startInterval, $endsInterval)
	{
		DBUtil::getLikeStatement($phoneNumber, $bindString, $params, "%", "%");
		$param	 = array_merge($params, ['startInterval' => $startInterval, 'endsInterval' => $endsInterval]);
		$sql	 = "SELECT 
					whl_id
					FROM whatsapp_log
					WHERE 1
					AND whatsapp_log.whl_phone_number LIKE $bindString
					AND whatsapp_log.whl_created_date BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL :startInterval DAY), ' 23:59:59') AND  CONCAT(DATE_SUB(CURDATE(),INTERVAL :endsInterval DAY), ' 23:59:59')
					AND 
					(
						(whl_sent_date IS NULL AND whatsapp_log.whl_delivered_date IS NULL AND whatsapp_log.whl_read_date IS NULL)
						OR (whatsapp_log.whl_delivered_date IS NULL AND whatsapp_log.whl_read_date IS NULL)
						OR whatsapp_log.whl_read_date IS NULL
						OR whatsapp_log.whl_status=1
					 )
					 AND whl_wht_id IS NOT NULL
					 ORDER BY `whl_id` DESC";
		$whlId	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
		return $whlId > 0 ? true : false;
	}

	/**
	 * This function is used return all the details for particular WhatsApp response id
	 * @return type queryRow
	 */
	public static function detailsByWamId($whl_wam_id)
	{
		$sql = "SELECT  * FROM whatsapp_log WHERE 1	AND whl_wam_id=:whl_wam_id";
		return DBUtil::queryRow($sql, DBUtil::SDB(), ['whl_wam_id' => $whl_wam_id]);
	}

	/**
	 * This function is used return all the details for for qr code for user refer program
	 * @return type queryRow
	 */
	public static function sendReferLink()
	{
		$sql = "SELECT 
					COUNT(DISTINCT bkg_id) AS cnt,
					contact.ctt_id,
					users.user_id,
					qr_code.qrc_code 
				FROM booking
					INNER JOIN booking_user ON booking_user.bui_bkg_id=booking.bkg_id
					INNER JOIN users ON users.user_id=booking_user.bkg_user_id 
					INNER JOIN contact_profile ON contact_profile.cr_is_consumer= users.user_id 
					INNER JOIN qr_code ON qr_code.qrc_ent_id=users.user_id  
					INNER JOIN contact ON contact.ctt_id=contact_profile.cr_contact_id 
					LEFT JOIN whatsapp_log ON whatsapp_log.whl_entity_id=users.user_id  AND whatsapp_log.whl_entity_type=1 AND whl_wht_id=13
				WHERE 1 
					AND qr_code.qrc_active=1 
					AND qr_code.qrc_status=3 
					AND qrc_ent_type=1
					AND contact.ctt_id=contact.ctt_ref_code
					AND contact.ctt_active=1
					AND cr_status=1 
					AND users.usr_active=1
					AND bkg_user_id IS NOT NULL
					AND bkg_status IN (6,7) 
					AND bkg_agent_id IS NULL
					AND whatsapp_log.whl_id IS NULL
					AND bkg_pickup_date BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 18 MONTH), ' 00:00:00') AND CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY), ' 23:59:59')
				GROUP BY users.user_id
				ORDER BY cnt DESC
				LIMIT 0,100";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public static function getJsonTemplateName()
	{
		$sql	 = "SELECT  wht_id, wht_template_name, wht_lang_code FROM whatsapp_templates WHERE 1 AND wht_active=1";
		$data	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($data as $val)
		{
			$arrJSON[] = array("id" => $val['wht_id'], "text" => $val['wht_template_name'] . ' (' . $val['wht_lang_code'] . ')');
		}
		$result = CJSON::encode($arrJSON);
		return $result;
	}

	public static function sendPaymentRequestForBkg($bkgId, $bkgModel = null, $minPayExtra = 0)
	{
		if ($bkgModel == null)
		{
			$bkgModel = Booking::model()->findByPk($bkgId);
		}
		elseif (!$bkgModel instanceof Booking)
		{
			return false;
		}

		$minPerc = Config::getMinAdvancePercent($bkgModel->bkg_agent_id, $bkgModel->bkg_booking_type, $bkgModel->bkgSvcClassVhcCat->scc_ServiceClass->scc_id, $bkgModel->bkgPref->bkg_is_gozonow);
		$minPay	 = round($minPerc * $bkgModel->bkgInvoice->bkg_total_amount * 0.01);
		if ($minPayExtra > 0)
		{
			$minPay = $minPayExtra;
		}
		$userName		 = $bkgModel->bkgUserInfo->bkg_user_fname != null && !empty(trim($bkgModel->bkgUserInfo->bkg_user_fname)) ? $bkgModel->bkgUserInfo->bkg_user_fname : "User";
		$bookingId		 = Filter::formatBookingId($bkgModel->bkg_booking_id);
		$cabType		 = $bkgModel->bkgSvcClassVhcCat->scv_label;
		$fromCityName	 = $bkgModel->bkgFromCity->cty_name;
		$toCityName		 = $bkgModel->bkgToCity->cty_name;
		$tripType		 = $bkgModel->getBookingType($bkgModel->bkg_booking_type);
		$tripDistance	 = $bkgModel->bkg_trip_distance;
		$totalAmt		 = $bkgModel->bkgInvoice->bkg_total_amount;

		$datePickupDate	 = new DateTime($bkgModel->bkg_pickup_date);
		$pickupTime		 = $datePickupDate->format('j/M/y h:i A');

		$buttonUrl	 = ltrim(BookingUser::getPaymentLinkByPhone($bkgModel->bkg_id), '/');
		$paymentUrl	 = 'http://www.aaocab.com/' . $buttonUrl;

		$phoneNo = WhatsappLog::getPhoneNoByBookingId($bkgModel->bkg_id);
		$userId	 = $bkgModel->bkgUserInfo->bkg_user_id;

		$templateName	 = 'customer_booking_payment_request_v2';
		$lang			 = 'en_US';
		$arrWhatsAppData = [$userName, $bookingId, $cabType, $fromCityName, $toCityName, $pickupTime, $tripType, $tripDistance, Filter::moneyFormatter($totalAmt), Filter::moneyFormatter($minPay), $paymentUrl];
		$arrDBData		 = ['entity_type' => UserInfo::TYPE_CONSUMER, 'entity_id' => $userId, 'ref_type' => WhatsappLog::REF_TYPE_BOOKING, 'ref_id' => $bkgId];

		$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$arrButton	 = Whatsapp::buildComponentButton([$buttonUrl]);
		$response	 = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang, true);
		$success	 = ($response['status'] == 2 && $response['wamId'] != null) ? true : false;
		if ($success)
		{
			$isPolicyReminderCnt = self::isPolicyReimderSend($phoneNo, UserInfo::TYPE_CONSUMER, $userId, WhatsappLog::REF_TYPE_BOOKING, $bkgId);
			if ($isPolicyReminderCnt == 0)
			{
				self::policyReminderForUser($phoneNo, UserInfo::TYPE_CONSUMER, $userId, WhatsappLog::REF_TYPE_BOOKING, $bkgId, $lang);
			}
		}
		return $response;
	}

	public static function tripAssignedToVendor($tripId)
	{
		$bcbModel = false;
		if ($tripId > 0)
		{
			$bcbModel = BookingCab::model()->findByPk($tripId);
		}
		if (!$bcbModel)
		{
			return false;
		}

		$bkgModels = $bcbModel->bookings;
		if (!$bkgModels || count($bkgModels) > 2)
		{
			return false;
		}

		$vndId = $bcbModel->bcb_vendor_id;

		$arrWhatsAppData	 = [];
		$arrWhatsAppData[]	 = $tripId;
		$arrWhatsAppData[]	 = 'Rs. ' . $bcbModel->bcb_vendor_amount;

		foreach ($bkgModels as $bkgModel)
		{
			$bkgId			 = $bkgModel->bkg_id;
			$bookingId		 = Filter::formatBookingId($bkgModel->bkg_booking_id);
			$cabType		 = $bkgModel->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
			$fromCityName	 = $bkgModel->bkgFromCity->cty_name;
			$toCityName		 = $bkgModel->bkgToCity->cty_name;
			$tripType		 = $bkgModel->getBookingType($bkgModel->bkg_booking_type);
			$tripDistance	 = $bkgModel->bkg_trip_distance . ' KM';
			$amtToCollect	 = 'Rs. ' . $bkgModel->bkgInvoice->bkg_due_amount;

			$datePickupDate	 = new DateTime($bkgModel->bkg_pickup_date);
			$pickupTime		 = $datePickupDate->format('j/M/y h:i A');

			$hashBkgId	 = Yii::app()->shortHash->hash($bkgId);
			$hashVndId	 = Yii::app()->shortHash->hash($vndId);
			$bkvnLink	 = "http://www.aaocab.com/bkvn/{$hashBkgId}/{$hashVndId}";

			$arrWhatsAppData[]	 = $bookingId;
			$arrWhatsAppData[]	 = $tripType;
			$arrWhatsAppData[]	 = $cabType;
			$arrWhatsAppData[]	 = $pickupTime;
			$arrWhatsAppData[]	 = $fromCityName;
			$arrWhatsAppData[]	 = $toCityName;
			$arrWhatsAppData[]	 = $tripDistance;
			$arrWhatsAppData[]	 = $amtToCollect;
			$arrWhatsAppData[]	 = $bkvnLink;
		}

		$lang			 = 'en_US';
		$templateName	 = 'assigned_trip_to_vendor';
		if (count($bkgModels) > 1)
		{
			$templateName = 'assigned_match_trip_to_vendor';
		}

		$arrDBData = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $vndId, 'ref_type' => WhatsappLog::REF_TYPE_TRIP, 'ref_id' => $tripId];

		$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$arrButton	 = [];

		$contactId	 = ContactProfile::getByEntityId($vndId, UserInfo::TYPE_VENDOR);
		$phoneNo	 = self::getPhoneNoByContactId($contactId);
		if (!$phoneNo)
		{
			return ['status' => 3, 'wamId' => '', 'error' => 'No contact phone found'];
		}

		$response = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);

		return $response;
	}

	/**
	 * 
	 * @param type $bkgId
	 * @param type $type
	 * @return boolean
	 */
	public static function tripCancelToVendorDriver($bkgId, $type)
	{
		$bkgModel = false;
		if ($bkgId > 0)
		{
			$bkgModel = Booking::model()->findByPk($bkgId);
		}
		if (!$bkgModel)
		{
			return false;
		}

		$vndId		 = $bkgModel->bkgBcb->bcb_vendor_id;
		$drvId		 = $bkgModel->bkgBcb->bcb_driver_id;
		$entityId	 = ($type == 2) ? $vndId : $drvId;

		$bookingId		 = Filter::formatBookingId($bkgModel->bkg_booking_id);
		$tripType		 = $bkgModel->getBookingType($bkgModel->bkg_booking_type);
		$cabType		 = $bkgModel->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
		$datePickupDate	 = new DateTime($bkgModel->bkg_pickup_date);
		$pickupTime		 = $datePickupDate->format('j/M/y h:i A');
		$fromCityName	 = $bkgModel->bkgFromCity->cty_name;
		$toCityName		 = $bkgModel->bkgToCity->cty_name;

		$arrWhatsAppData = [$bookingId, $tripType, $cabType, $pickupTime, $fromCityName, $toCityName];

		$templateName	 = 'booking_cancelled_to_vendor';
		$lang			 = 'en_US';
		$arrDBData		 = ['entity_type' => $type, 'entity_id' => $entityId, 'ref_type' => WhatsappLog::REF_TYPE_BOOKING, 'ref_id' => $bkgId];

		$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$arrButton	 = [];

		$contactId	 = ContactProfile::getByEntityId($entityId, $type);
		$phoneNo	 = self::getPhoneNoByContactId($contactId);
		if (!$phoneNo)
		{
			return ['status' => 3, 'wamId' => '', 'error' => 'No contact phone found'];
		}

		$response = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);
		return $response;
	}

	public static function isCustomerOngoingTripExist($templateId, $refType, $refId)
	{
		$sql = "SELECT COUNT(whl_id) AS cnt FROM `whatsapp_log` WHERE 1 AND `whl_wht_id`=:templateId and `whl_ref_type`=:refType AND `whl_ref_id`=:refId";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), ['templateId' => $templateId, 'refType' => $refType, 'refId' => $refId]);
	}

	/**
	 * 
	 * @param type $bkgId
	 * @return boolean
	 */
	public static function sendDriverCabDetailsToCustomer($bkgId)
	{
		$bkgModel = false;
		if ($bkgId > 0)
		{
			$bkgModel = Booking::model()->findByPk($bkgId);
		}
		if (!$bkgModel)
		{
			return false;
		}
		$drvId = $bkgModel->bkgBcb->bcb_driver_id;
		if (!$drvId)
		{
			return false;
		}

		$userName = $bkgModel->bkgUserInfo->bkg_user_fname;

		$bookingId	 = Filter::formatBookingId($bkgModel->bkg_booking_id);
		$cabType	 = $bkgModel->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
		$cabNumber	 = $bkgModel->bkgBcb->bcb_cab_number;
		$driverName	 = $bkgModel->bkgBcb->bcbDriver->drv_name;

		$drvContactId	 = ContactProfile::getByEntityId($drvId, UserInfo::TYPE_DRIVER);
		$drvContact		 = ContactPhone::getContactPhoneById($drvContactId);
		$isPhone		 = Filter::parsePhoneNumber($drvContact, $drvCode, $drvNumber);
		$driverPhone	 = $drvCode . $drvNumber;

		$datePickupDate	 = new DateTime($bkgModel->bkg_pickup_date);
		$pickupTime		 = $datePickupDate->format('j/M/y h:i A');
		$fromCityName	 = $bkgModel->bkgFromCity->cty_name;
		$toCityName		 = $bkgModel->bkgToCity->cty_name;

		$buttonUrl	 = ltrim(BookingUser::getPaymentLinkByPhone($bkgModel->bkg_id), '/');
		$paymentUrl	 = 'http://www.aaocab.com/' . $buttonUrl;

		$arrWhatsAppData = [$userName, $bookingId, $cabType, $cabNumber, $driverName, $driverPhone, $pickupTime, $fromCityName, $paymentUrl];

		$phoneNo = $bkgModel->bkgUserInfo->bkg_country_code . $bkgModel->bkgUserInfo->bkg_contact_no;
		$userId	 = $bkgModel->bkgUserInfo->bkg_user_id;

		$lang			 = 'en_US';
		$templateName	 = 'driver_details_to_customer';

		$arrDBData		 = ['entity_type' => UserInfo::TYPE_CONSUMER, 'entity_id' => $userId, 'ref_type' => WhatsappLog::REF_TYPE_BOOKING, 'ref_id' => $bkgId];
		$skipPermission	 = false;
		if ($bkgModel->bkg_agent_id == Config::get('transferz.partner.id'))
		{
			$templateName	 = 'driver_details_to_customer_for_partner';
			$referenceCode	 = $bkgModel->bkg_agent_ref_code;
			$driverPhone	 = '+' . $drvCode . '-' . $drvNumber;
			if (is_numeric($bkgModel->bkg_agent_ref_code))
			{
				$partnerCode	 = TransferzOffers::getOffer($bkgModel->bkg_agent_ref_code);
				$referenceCode	 = ($partnerCode && isset($partnerCode['trb_trz_journey_code'])) ? $partnerCode['trb_trz_journey_code'] : $referenceCode;
			}
			$skipPermission		 = true;
			$refId				 = ($referenceCode != '') ? $referenceCode : Filter::formatBookingId($bkgModel->bkg_booking_id);
			$assistanceContact	 = '+91-8017233722';
			$arrWhatsAppData	 = [$userName, $pickupTime, $refId, $fromCityName, $toCityName, $cabNumber, $driverName, $driverPhone, $assistanceContact];
		}

		$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$arrButton	 = [];
		$response	 = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang, false, $skipPermission);
		return $response;
	}

	/*
	 * This function is used to get all the Ref Types of this model as in JSON format to show in select2
	 * return json array
	 */

	public function getJSONAllRefType()
	{
		$rows	 = $this->ref_type;
		$arrRef	 = array();
		foreach ($rows as $key => $row)
		{
			$arrRef[] = array("id" => $key, "text" => $row);
		}
		$data = CJSON::encode($arrRef);
		return $data;
	}

	/**
	 * 
	 * @param type $bkgId
	 * @param type $type
	 * @return boolean
	 */
	public static function sendTripDetailsToVendorDriver($bkgId, $type)
	{
		$bkgModel = false;
		if ($bkgId > 0)
		{
			$bkgModel = Booking::model()->findByPk($bkgId);
		}
		if (!$bkgModel)
		{
			return false;
		}

		$vndId		 = $bkgModel->bkgBcb->bcb_vendor_id;
		$drvId		 = $bkgModel->bkgBcb->bcb_driver_id;
		$entityId	 = ($type == 2) ? $vndId : $drvId;

		$bookingId		 = $bkgModel->bkg_booking_id;
		$tripType		 = $bkgModel->getBookingType($bkgModel->bkg_booking_type);
		$cabType		 = $bkgModel->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
		$datePickupDate	 = new DateTime($bkgModel->bkg_pickup_date);
		$pickupTime		 = $datePickupDate->format('j/M/y h:i A');
		$fromCityName	 = $bkgModel->bkgFromCity->cty_name;
		$toCityName		 = $bkgModel->bkgToCity->cty_name;
		$tripDistance	 = $bkgModel->bkg_trip_distance . ' KM';
		$amtToCollect	 = 'Rs. ' . $bkgModel->bkgInvoice->bkg_due_amount;

		$hashBkgId	 = Yii::app()->shortHash->hash($bkgId);
		$hashVndId	 = Yii::app()->shortHash->hash($vndId);
		$bkvnLink	 = "http://www.aaocab.com/bkvn/{$hashBkgId}/{$hashVndId}";

		$arrWhatsAppData = [Filter::formatBookingId($bookingId), $tripType, $cabType, $pickupTime, $fromCityName, $toCityName, $tripDistance, $amtToCollect, $bkvnLink];

		$contactId	 = ContactProfile::getByEntityId($entityId, $type);
		$phoneNo	 = self::getPhoneNoByContactId($contactId);
		if (!$phoneNo)
		{
			return ['status' => 3, 'wamId' => '', 'error' => 'No contact phone found'];
		}

		$templateName	 = 'trip_details_to_vendor_driver';
		$lang			 = 'en_US';

		$arrDBData = ['entity_type' => $type, 'entity_id' => $entityId, 'ref_type' => WhatsappLog::REF_TYPE_BOOKING, 'ref_id' => $bkgId];

		$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$arrButton	 = [];

		$response = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);
		return $response;
	}

	/**
	 * 
	 * @param type $contactId
	 * @param type $number
	 * @param type $otp
	 * @param type $templateStyle
	 * @param type $tempPkId
	 * @param type $userType
	 * @param type $refId
	 * @return \ReturnSet
	 */
	public static function updateVendorDriverPhoneNumber($contactId, $number, $otp, $templateStyle, $tempPkId, $userType, $refId, $vndId)
	{
		$returnset	 = new ReturnSet();
		$cttModel	 = Contact::model()->findByPk($contactId);

		if (!$cttModel || !$number)
		{
			return $returnset;
		}
		$code	 = null;
		$isPhone = Filter::parsePhoneNumber($number, $code, $number);
		$phoneNo = $code . $number;

		$userName = $cttModel->ctt_first_name . ' ' . $cttModel->ctt_last_name;
		if (!empty($cttModel->ctt_business_name))
		{
			$userName = $cttModel->ctt_business_name;
		}

		$cttHash			 = Yii::app()->shortHash->hash($contactId);
		$hashOtp			 = Yii::app()->shortHash->hash($otp);
		$templateStyleHash	 = Yii::app()->shortHash->hash($templateStyle);
		$tempPkHash			 = Yii::app()->shortHash->hash($tempPkId);

		$ext = ($ext != '') ? $ext : 91;

		$numHash		 = base64_encode($number);
		$arrUrlParams	 = ['id' => $cttHash, 'otp' => $hashOtp, 'ts' => $templateStyleHash, 'tpk' => $tempPkHash, 'num' => $numHash];
		if ($vndId)
		{
			$vndIdHash			 = Yii::app()->shortHash->hash($vndId);
			$arrUrlParams['vnd'] = $vndIdHash;
		}


		$verifyPhoneUrl	 = Yii::app()->createUrl('verifyPhone', $arrUrlParams);
		$url			 = Yii::app()->params['fullBaseURL'] . $verifyPhoneUrl;
		$buttonUrl		 = ltrim($verifyPhoneUrl, '/');

		$arrWhatsAppData = [$userName, $url];

		$templateName	 = 'update_vendor_driver_phoneno';
		$lang			 = 'en_US';

		$arrDBData = ['entity_type' => $userType, 'entity_id' => $refId];

		$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$arrButton	 = Whatsapp::buildComponentButton([$buttonUrl]);

		$response = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);
		if ($response['status'] == 2)
		{
			$returnset->setStatus(true);
		}
		return $returnset;
	}

	/**
	 * 
	 * @param type $vndId
	 * @param type $phone
	 * @param type $otp
	 * @return type
	 */
	public static function attachVendorSocialAccount($vndId, $phone, $otp)
	{
		$contactId = ContactProfile::getByEntityId($vndId, UserInfo::TYPE_VENDOR);
		if (!$contactId)
		{
			return false;
		}

		$cttModel	 = Contact::model()->findByPk($contactId);
		$userName	 = (!empty(trim($cttModel->ctt_business_name)) ? trim($cttModel->ctt_business_name) : ($cttModel->ctt_first_name . ' ' . $cttModel->ctt_last_name));

		$arrWhatsAppData = [$userName, $otp];

		$templateName	 = 'attach_vendor_social_account';
		$lang			 = 'en_US';

		$code	 = $number	 = null;
		$isPhone = Filter::parsePhoneNumber($phone, $code, $number);
		$phoneNo = $code . $number;

		$arrDBData = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $vndId];

		$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$arrButton	 = [];

		$response = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);
		return $response;
	}

	/**
	 * 
	 * @param type $bkgId
	 * @param type $phone
	 * @return boolean
	 */
	public static function unassignedTripFromVendor($bkgId, $phone)
	{
		$bkgModel = false;
		if ($bkgId > 0)
		{
			$bkgModel = Booking::model()->findByPk($bkgId);
		}
		if (!$bkgModel)
		{
			return false;
		}

		$vndId = $bkgModel->bkgBcb->bcb_vendor_id;

		$bookingId		 = $bkgModel->bkg_booking_id;
		$tripType		 = $bkgModel->getBookingType($bkgModel->bkg_booking_type);
		$cabType		 = $bkgModel->bkgSvcClassVhcCat->scv_label;
		$datePickupDate	 = new DateTime($bkgModel->bkg_pickup_date);
		$pickupTime		 = $datePickupDate->format('j/M/y h:i A');
		$fromCityName	 = $bkgModel->bkgFromCity->cty_name;
		$toCityName		 = $bkgModel->bkgToCity->cty_name;
		$tripDistance	 = $bkgModel->bkg_trip_distance . ' KM';
		$amtToCollect	 = 'Rs. ' . $bkgModel->bkgInvoice->bkg_due_amount;

		$arrWhatsAppData = [Filter::formatBookingId($bookingId), $tripType, $cabType, $pickupTime, $fromCityName, $toCityName, $tripDistance, $amtToCollect];

		$code	 = $number	 = null;
		$isPhone = Filter::parsePhoneNumber($phone, $code, $number);
		$phoneNo = $code . $number;

		$templateName	 = 'unassigned_trip_from_vendor';
		$lang			 = 'en_US';

		$arrDBData = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $vndId, 'ref_type' => WhatsappLog::REF_TYPE_BOOKING, 'ref_id' => $bkgId];

		$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$arrButton	 = [];

		$response = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);
		return $response;
	}

	/**
	 * 
	 * @param type $vndId
	 * @param type $contactId
	 * @return type
	 */
	public static function accountUnblocked($vndId, $contactId)
	{
		$cttModel	 = Contact::model()->findByPk($contactId);
		$userName	 = (!empty(trim($cttModel->ctt_business_name)) ? trim($cttModel->ctt_business_name) : ($cttModel->ctt_first_name . ' ' . $cttModel->ctt_last_name));

		$objPhoneNumber = ContactPhone::getPrimaryNumber($contactId);
		if (!$objPhoneNumber)
		{
			return ['status' => 3, 'wamId' => '', 'error' => 'No contact phone found'];
		}

		$code	 = $objPhoneNumber->getCountryCode();
		$number	 = str_replace(" ", "", $objPhoneNumber->getNationalNumber());
		$phoneNo = $code . $number;

		$templateName	 = 'account_unblocked';
		$lang			 = 'en_US';
		$arrWhatsAppData = [$userName];
		$arrDBData		 = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $vndId];

		$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$arrButton	 = [];

		$response = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);
		return $response;
	}

	/**
	 * 
	 * @param type $vndId
	 * @param type $contactId
	 * @return type
	 */
	public static function accountBlocked($vndId, $contactId)
	{
		$cttModel	 = Contact::model()->findByPk($contactId);
		$userName	 = (!empty(trim($cttModel->ctt_business_name)) ? trim($cttModel->ctt_business_name) : ($cttModel->ctt_first_name . ' ' . $cttModel->ctt_last_name));

		$objPhoneNumber = ContactPhone::getPrimaryNumber($contactId);
		if (!$objPhoneNumber)
		{
			return ['status' => 3, 'wamId' => '', 'error' => 'No contact phone found'];
		}

		$code	 = $objPhoneNumber->getCountryCode();
		$number	 = str_replace(" ", "", $objPhoneNumber->getNationalNumber());
		$phoneNo = $code . $number;

		$templateName	 = 'account_blocked';
		$lang			 = 'en_US';
		$arrWhatsAppData = [$userName];
		$arrDBData		 = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $vndId];

		$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$arrButton	 = [];

		$response = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);
		return $response;
	}

	public static function verifyPhoneno($userName, $contactId, $url, $refType, $refId)
	{
		$cttModel = Contact::model()->findByPk($contactId);

		$arrWhatsAppData = [$userName, $url];

		$phone	 = ContactPhone::getContactPhoneById($contactId);
		$isPhone = Filter::parsePhoneNumber($phone, $code, $number);
		$phoneNo = $code . $number;

		$templateName	 = 'verify_phoneno';
		$lang			 = 'en_US';

		$arrDBData = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $vndId, 'ref_type' => $refType, 'ref_id' => $refId];

		$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$arrButton	 = [];

		$response = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);
		return $response;
	}

	/**
	 * 
	 * @param type $drvId
	 * @param type $name
	 * @param type $phone
	 * @return type
	 */
	public static function alreadyRegistered($drvId, $name, $phone)
	{
		$arrWhatsAppData = [];

		$arrWhatsAppData = [$name];

		$isPhone	 = Filter::parsePhoneNumber($phone, $code, $number);
		$phoneNo	 = $code . $number;
		$contactId	 = ContactProfile::getByEntityId($drvId, UserInfo::TYPE_DRIVER);

		$templateName	 = 'already_registered';
		$lang			 = 'en_US';

		$arrDBData = ['entity_type' => UserInfo::TYPE_DRIVER, 'entity_id' => $drvId];

		$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$arrButton	 = [];

		$response = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);
		return $response;
	}

	/**
	 * 
	 * @param type $drvId
	 * @param type $name
	 * @param type $phone
	 * @return type
	 */
	public static function driverCompleteRegistrationReminder($drvId, $name, $phone)
	{
		$arrWhatsAppData = [];

		$arrWhatsAppData = [$name];

		$isPhone	 = Filter::parsePhoneNumber($phone, $code, $number);
		$phoneNo	 = $code . $number;
		$contactId	 = ContactProfile::getByEntityId($drvId, UserInfo::TYPE_DRIVER);

		$templateName	 = 'driver_complete_registration_reminder';
		$lang			 = 'en_US';

		$arrDBData = ['entity_type' => UserInfo::TYPE_DRIVER, 'entity_id' => $drvId];

		$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$arrButton	 = [];

		$response = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);
		return $response;
	}

	/** deprecated 
	 * new function in use Booking::newBookingFromChannelPartner
	 */
	public static function newBookingFromChannelPartner($partnerBkgId, $bookingId = '', $bkgId = '', $pickupDate = '')
	{
		$bkgModel = '';
		if ($bkgId > 0)
		{
			$bkgModel = Booking::model()->findByPk($bkgId);
		}
		$arrWhatsAppData = [];
		$partnerName	 = 'Transferz';
		$templateName	 = 'new_booking_from_channel_partner_v2';
		$lang			 = 'en_US';
		$cabRequired	 = $bkgModel->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
		$date			 = $bkgModel->bkg_pickup_date;
		$bookingId		 = ($bookingId != '' ? Filter::formatBookingId($bookingId) : '*No booking created*');
		$cabType		 = ($cabRequired != '' ? $cabRequired : '-');
		$pickupTime		 = '-';
		if ($bkgId > 0)
		{
			$datePickupDate	 = new DateTime($date, new \DateTimeZone('Asia/Kolkata'));
			$pickupTime		 = $datePickupDate->format('j/M/y h:i A');
		}
		else
		{
			$datePickupDate	 = new DateTime($pickupDate, new \DateTimeZone('Asia/Calcutta'));
			$pickupTime		 = $datePickupDate->format('j/M/y h:i A');
		}
		$fromCityName	 = ($bkgModel->bkgFromCity->cty_name != '' ? $bkgModel->bkgFromCity->cty_name : '-');
		$toCityName		 = ($bkgModel->bkgToCity->cty_name != '' ? $bkgModel->bkgToCity->cty_name : '-');
		$tripDistance	 = ($bkgModel->bkg_trip_distance != '' ? $bkgModel->bkg_trip_distance : '-');
		$bookingAmount	 = ($bkgModel->bkgInvoice->bkg_total_amount != '' ? 'Rs. ' . $bkgModel->bkgInvoice->bkg_total_amount : '-');
		$arrAdmins		 = ['311' => '919831100164', '53' => '919903430853', '13' => '919831859111', '544' => '919051153099', '455' => '918017233722'];
		foreach ($arrAdmins as $adminId => $phone)
		{
			$arrWhatsAppData = [$partnerName, $partnerBkgId, $bookingId, $cabType, $pickupTime, $fromCityName, $toCityName, $tripDistance, $bookingAmount];
			$arrDBData		 = ['entity_type' => UserInfo::TYPE_ADMIN, 'entity_id' => $adminId];
			if ($bkgId > 0)
			{
				$arrDBData['ref_type']	 = 1;
				$arrDBData['ref_id']	 = $bkgId;
			}
			$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
			$arrButton	 = Whatsapp::buildComponentButton([]);
			WhatsappLog::send($phone, $templateName, $arrDBData, $arrBody, $arrButton, $lang, false, true);
		}
	}

	/**
	 * 
	 * @param type $bkgId
	 * @param type $reasonId
	 * @return boolean
	 */
	public static function bookingCancelledByCustomer($bkgId, $reasonId, $custNumber)
	{
		if ($bkgId > 0)
		{
			$bkgModel = Booking::model()->findByPk($bkgId);
		}
		if (!$bkgModel)
		{
			return false;
		}
		//calculate cancel charge and refund amouunt for the customer;
		$cancelCharges		 = $bkgModel->calculateRefund();
		$refundAmount		 = $cancelCharges->refund;
		$cusPenalizedRule	 = CancelReasons::getCustomerPenalizeRuleById($reasonId);
		if ($cancelCharges->charges == 0 && ($bkgModel->bkgInvoice->bkg_net_advance_amount >= $bkgModel->bkgInvoice->bkg_admin_fee) && $bkgModel->bkgTrail->bkg_platform == 2 && $cusPenalizedRule > 1 && ($bkgModel->bkg_agent_id == '' || $bkgModel->bkg_agent_id == null))
		{
			$adminAssistedMarkup	 = $bkgModel->bkgInvoice->bkg_admin_fee;
			$cancelCharges->charges	 = $adminAssistedMarkup;
			$refundAmount			 = ($cancelCharges->refund - $adminAssistedMarkup);
		}
		// Booking User
		$arrUserDetails	 = self::getUserByBooking($bkgId, $bkgModel);
		$userId			 = $arrUserDetails['userId'];
		$userName		 = $arrUserDetails['userName'] != null && !empty(trim($arrUserDetails['userName'])) ? $arrUserDetails['userName'] : "User";
		// PhoneNo
		$phoneNo		 = self::getPhoneNoByBookingId($bkgId, $bkgModel);
		if (!$phoneNo)
		{
			return false;
		}
		$bookingId		 = $bkgModel->bkg_booking_id;
		$datePickupDate	 = new DateTime($bkgModel->bkg_pickup_date);
		$pickupDate		 = $datePickupDate->format('j/M/y');
		$pickupTime		 = $datePickupDate->format('h:i A');
		$fromCityName	 = $bkgModel->bkgFromCity->cty_name;
		$toCityName		 = $bkgModel->bkgToCity->cty_name;

		$amountDetails = AccountTransactions::getTotalWalletAmountRefunded($bkgId);
		if (!empty($amountDetails) && $amountDetails['amount'] < 0 && $amountDetails['apg_mode'] == null)
		{
			$refundstr = "The remaining amount of ₹{$refundAmount} has been credited to your Gozo wallet.";
		}
		else
		{
			$refundstr = "The remaining amount of ₹{$refundAmount} will be processed and credited back to your original payment method within 5-7 business days.";
		}

		$arrWhatsAppData = [$userName, Filter::formatBookingId($bookingId), $fromCityName, $toCityName, $pickupDate, $pickupTime, "₹" . $cancelCharges->charges, $refundstr];
		$templateName	 = 'booking_cancelled_to_customer_new';
		$lang			 = 'en_US';
		$arrDBData		 = ['entity_type' => UserInfo::TYPE_CONSUMER, 'entity_id' => $userId, 'ref_type' => WhatsappLog::REF_TYPE_BOOKING, 'ref_id' => $bkgId];
		$arrBody		 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$templateId		 = WhatsappLog::findByTemplateNameLang($templateName, $lang, 'wht_id');
		$arrButton		 = Whatsapp::buildComponentButton([$templateId], 'button', 'quick_reply', "payload");
		$response		 = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);
		$success		 = ($response['status'] == 2 && $response['wamId'] != null) ? true : false;
		if ($success)
		{
			$isPolicyReminderCnt = self::isPolicyReimderSend($phoneNo, UserInfo::TYPE_CONSUMER, $userId, WhatsappLog::REF_TYPE_BOOKING, $bkgId);
			if ($isPolicyReminderCnt == 0)
			{
				self::policyReminderForUser($phoneNo, UserInfo::TYPE_CONSUMER, $userId, WhatsappLog::REF_TYPE_BOOKING, $bkgId, $lang);
			}
			$templateId	 = WhatsappLog::findByTemplateNameLang($templateName, $lang, 'wht_id');
			$row		 = ["initiateBy" => WhatsappInitiateTrack::INITIATE_BY_GOZO, "initiateType" => WhatsappInitiateTrack::INITIATE_TYPE_USER, "templateId" => $templateId, "phoneNumber" => $phoneNo];
			WhatsappInitiateTrack::add($row);
			WhatsappInitiateTrack::updateStatus($row['initiateBy'], $row['initiateType'], $row['phoneNumber']);
		}
		return $response;
	}

	public static function bookingPaymentReceivedByCustomer($bkgId)
	{
		if ($bkgId > 0)
		{
			$bkgModel = Booking::model()->findByPk($bkgId);
		}
		if (!$bkgModel)
		{
			return false;
		}
		if ($bkgModel->bkgInvoice->bkg_advance_amount == 0)
		{
			return false;
		}

		/* $entityId	 = $bkgModel->bkgUserInfo->bkg_user_id;
		  $contactId	 = ContactProfile::getByEntityId($entityId, UserInfo::TYPE_CONSUMER);
		  $cttModel	 = Contact::model()->findByPk($contactId);
		  $userName	 = $cttModel->ctt_first_name . ' ' . $cttModel->ctt_last_name;
		  if (!empty($cttModel->ctt_business_name))
		  {
		  $userName = $cttModel->ctt_business_name;
		  } */

		// Booking User
		$arrUserDetails	 = self::getUserByBooking($bkgId, $bkgModel);
		$userId			 = $arrUserDetails['userId'];
		$userName		 = $arrUserDetails['userName'];

		// Phone No
		$phoneNo = self::getPhoneNoByBookingId($bkgId, $bkgModel);
		if (!$phoneNo)
		{
			return ['status' => 3, 'wamId' => '', 'error' => 'No contact phone found'];
		}

		$lastPaymentReceived = AccountTransactions::getLastPaymentReceived($bkgId);
		$paymentAmount		 = ($lastPaymentReceived > 0) ? 'Rs. ' . $lastPaymentReceived : 'Rs. ' . $bkgModel->bkgInvoice->bkg_advance_amount;

		$bookingId			 = $bkgModel->bkg_booking_id;
		$bookingAmt			 = 'Rs. ' . $bkgModel->bkgInvoice->bkg_total_amount;
		$totalAdvanceAmount	 = 'Rs. ' . $bkgModel->bkgInvoice->bkg_advance_amount;
		$dueAmount			 = 'Rs. ' . $bkgModel->bkgInvoice->bkg_due_amount;
		#$userId				 = $bkgModel->bkgUserInfo->bkg_user_id;

		$hash		 = Yii::app()->shortHash->hash($bkgId);
		$buttonUrl	 = 'bkpn/' . $bkgId . '/' . $hash;

		$arrWhatsAppData = [$userName, $paymentAmount, Filter::formatBookingId($bookingId), $bookingAmt, $totalAdvanceAmount, $dueAmount];

//		$phoneNo = self::getPhoneNoByContactId($contactId);
//		if (!$phoneNo)
//		{
//			$phoneNo = $bkgModel->bkgUserInfo->bkg_contact_no;
//		}
//		if (!$phoneNo || trim($phoneNo) == '')
//		{
//			return ['status' => 3, 'wamId' => '', 'error' => 'No contact phone found'];
//		}

		$templateName	 = 'booking_payment_received_to_customer';
		$lang			 = 'en_US';

		$arrDBData = ['entity_type' => UserInfo::TYPE_CONSUMER, 'entity_id' => $userId, 'ref_type' => WhatsappLog::REF_TYPE_BOOKING, 'ref_id' => $bkgId];

		$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$arrButton	 = Whatsapp::buildComponentButton([$buttonUrl]);

		$response = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);
		return $response;
	}

	public static function bookingPaymentReceivedByCustomer_OLD($bkgId)
	{
		if ($bkgId > 0)
		{
			$bkgModel = Booking::model()->findByPk($bkgId);
		}
		if (!$bkgModel)
		{
			return false;
		}
		if ($bkgModel->bkgInvoice->bkg_advance_amount == 0)
		{
			return false;
		}

		$entityId	 = $bkgModel->bkgUserInfo->bkg_user_id;
		$contactId	 = ContactProfile::getByEntityId($entityId, UserInfo::TYPE_CONSUMER);
		$cttModel	 = Contact::model()->findByPk($contactId);
		$userName	 = $cttModel->ctt_first_name . ' ' . $cttModel->ctt_last_name;
		if (!empty($cttModel->ctt_business_name))
		{
			$userName = $cttModel->ctt_business_name;
		}

		$lastPaymentReceived = AccountTransactions::getLastPaymentReceived($bkgId);
		$paymentAmount		 = ($lastPaymentReceived > 0) ? 'Rs. ' . $lastPaymentReceived : 'Rs. ' . $bkgModel->bkgInvoice->bkg_advance_amount;

		$bookingId			 = $bkgModel->bkg_booking_id;
		$bookingAmt			 = 'Rs. ' . $bkgModel->bkgInvoice->bkg_total_amount;
		$totalAdvanceAmount	 = 'Rs. ' . $bkgModel->bkgInvoice->bkg_advance_amount;
		$dueAmount			 = 'Rs. ' . $bkgModel->bkgInvoice->bkg_due_amount;
		$userId				 = $bkgModel->bkgUserInfo->bkg_user_id;

		$hash		 = Yii::app()->shortHash->hash($bkgId);
		$buttonUrl	 = 'bkpn/' . $bkgId . '/' . $hash;

		$arrWhatsAppData = [$userName, $paymentAmount, Filter::formatBookingId($bookingId), $bookingAmt, $totalAdvanceAmount, $dueAmount];

		$phoneNo = self::getPhoneNoByContactId($contactId);
		if (!$phoneNo)
		{
			$phoneNo = $bkgModel->bkgUserInfo->bkg_contact_no;
		}
		if (!$phoneNo || trim($phoneNo) == '')
		{
			return ['status' => 3, 'wamId' => '', 'error' => 'No contact phone found'];
		}

		$templateName	 = 'booking_payment_received_to_customer';
		$lang			 = 'en_US';

		$arrDBData = ['entity_type' => UserInfo::TYPE_CONSUMER, 'entity_id' => $userId, 'ref_type' => WhatsappLog::REF_TYPE_BOOKING, 'ref_id' => $bkgId];

		$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$arrButton	 = Whatsapp::buildComponentButton([$buttonUrl]);

		$response = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);
		return $response;
	}

	public static function bookingReviewToCustomer($bkgId)
	{
		if ($bkgId > 0)
		{
			$bkgModel = Booking::model()->findByPk($bkgId);
		}
		if (!$bkgModel)
		{
			return false;
		}

		// Check already sent
		$arrSearchData	 = ['ref_type' => WhatsappLog::REF_TYPE_BOOKING, 'ref_id' => $bkgId, 'template_name' => 'booking_review_to_customer_v2', 'create_date' => $bkgModel->bkg_pickup_date];
		$isAlreadySent	 = self::isMessageAlreadySent($arrSearchData);
		if ($isAlreadySent)
		{
			return true;
		}

		// Booking User
		$arrUserDetails	 = self::getUserByBooking($bkgId, $bkgModel);
		$userId			 = $arrUserDetails['userId'];
		$userName		 = $arrUserDetails['userName'];

		// Review Link
		$uniqueId	 = Booking::model()->generateLinkUniqueid($bkgId);
		$buttonUrl	 = 'r/' . $uniqueId;
		$reviewUrl	 = 'https://' . Yii::app()->params['host'] . '/' . $buttonUrl;

		$arrWhatsAppData = [$userName, $reviewUrl];

		$phoneNo = self::getPhoneNoByBookingId($bkgId, $bkgModel);
		if (!$phoneNo)
		{
			return false;
		}

		$templateName	 = 'booking_review_to_customer_v2';
		$lang			 = 'en_US';

		$arrDBData = ['entity_type' => UserInfo::TYPE_CONSUMER, 'entity_id' => $userId, 'ref_type' => WhatsappLog::REF_TYPE_BOOKING, 'ref_id' => $bkgId];

		$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$arrButton	 = Whatsapp::buildComponentButton([$buttonUrl]);

		$response = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);
		return $response;
	}

	/**
	 * This function is used to process Whatsapp call back button submit
	 * @param type $whatsappId 
	 * @param type $arrData array 
	 * @return type None
	 */
	public static function processButtonAction($whatsappId, $arrData)
	{
		if ($whatsappId > 0 && $arrData['message_type'] == WhatsappLog::MSG_TYPE_BUTTON && $arrData['templateId'] == 15 && $arrData['message'] != "Subscribe")
		{
			Whatsapp::processSubscribe($arrData);
			UnsubscribePhoneno::markUnSubscribe($arrData['phone_number'], 2, $arrData['templateId']);
		}
		else if ($whatsappId > 0 && $arrData['templateId'] == 15 && $arrData['message'] == "Subscribe")
		{
			Whatsapp::processTextMsg($arrData['replyingToMessageId'], $arrData['phone_number'], 'Thank you for subscribing to our "new trip request" WhatsApp updates!');
			UnsubscribePhoneno::markSubscribe($arrData['phone_number'], 2, $arrData['templateId']);
		}
		else if ($whatsappId > 0 && in_array($arrData['templateId'], [24, 37]) && $arrData['message'] == "Request a callback")
		{
			$msg		 = $arrData['templateId'] == 24 ? "Need help with my ongoing trip." : "Need help with my cancel booking.";
			$returnSet	 = ServiceCallQueue::autoFURCustomerOngoingTrip($arrData['refId'], $msg);
			if ($returnSet->getStatus())
			{
				Whatsapp::processTextMsg($arrData['replyingToMessageId'], $arrData['phone_number'], 'Your callback request has been registered sucessfully.Our team will contact you as soon as possible.');
			}
		}
		else if ($whatsappId > 0 && in_array($arrData['templateId'], [74]))
		{
			MarketingMessageTracker::updateStatus($arrData['refType'], $arrData['refId'], 48, ($arrData['message'] == "Yes ,Send me quote" ? 0 : 1));

		}
		else if ($whatsappId > 0 && in_array($arrData['templateId'], [75]))
		{
			$linkId = ($arrData['message'] == "I need a price-match" ? 0 : ($arrData['message'] == "Create a new quote" ? 1 : 2));
			MarketingMessageTracker::updateStatus($arrData['refType'], $arrData['refId'], 49, $linkId);
		}
//		else if ($whatsappId > 0 && in_array($arrData['templateId'], [76]))
//		{
//			MarketingMessageTracker::updateStatus($arrData['refType'], $arrData['refId'], 50, ($arrData['message'] == "Find a quick trip" ? 0 : 1));
//		}
		else if ($whatsappId > 0 && in_array($arrData['templateId'], [51]) && ($arrData['message'] == "New Boooking" || $arrData['message'] == "Existing Boooking" || $arrData['message'] == "Vendor Helpline" || $arrData['message'] == "Attach Your taxi"))
		{
			$queueType = "";
			switch ($arrData['message'])
			{
				case "New Boooking":
					$queueType	 = ServiceCallQueue::TYPE_NEW_BOOKING;
					break;
				case "Existing Boooking":
					$queueType	 = ServiceCallQueue::TYPE_EXISTING_BOOKING;
					break;
				case "Vendor Helpline":
					$queueType	 = ServiceCallQueue::TYPE_EXISTING_VENDOR;
					break;
				case "Attach Your taxi":
					$queueType	 = ServiceCallQueue::TYPE_NEW_VENDOR_ATTACHMENT;
					break;
			}
			$status = ServiceCallQueue::checkActiveCBRByPhone($arrData['phone_number'], $queueType) > 0 ? true : false;
			if ($status)
			{
				goto skipCheck;
			}
			if ($queueType == ServiceCallQueue::TYPE_NEW_BOOKING || $queueType == ServiceCallQueue::TYPE_NEW_VENDOR_ATTACHMENT)
			{
				$profileDetails									 = ContactProfile::getProfilebyPhone($arrData['phone_number']);
				$model											 = new ServiceCallQueue();
				$model->scq_created_by_type						 = 1;
				$model->scq_created_by_uid						 = $profileDetails['cr_is_consumer'];
				$model->scq_to_be_followed_up_with_type			 = 2;
				$model->scq_to_be_followed_up_with_value		 = $arrData['phone_number'];
				$model->scq_to_be_followed_up_with_contact		 = $profileDetails['cr_contact_id'];
				$model->scq_to_be_followed_up_with_entity_type	 = $queueType == ServiceCallQueue::TYPE_NEW_BOOKING ? UserInfo::TYPE_CONSUMER : UserInfo::TYPE_VENDOR;
				$model->scq_to_be_followed_up_with_entity_id	 = $queueType == ServiceCallQueue::TYPE_NEW_BOOKING ? $profileDetails['cr_is_consumer'] : $profileDetails['cr_is_vendor'];
				$model->scq_creation_comments					 = $queueType == ServiceCallQueue::TYPE_NEW_BOOKING ? "Customer need a booking please call him." : "Vendor want to attach his car to gozo.Please call the vendor to know the problem";
				$model->scq_follow_up_queue_type				 = $queueType == ServiceCallQueue::TYPE_NEW_BOOKING ? ServiceCallQueue::TYPE_NEW_BOOKING : ServiceCallQueue::TYPE_NEW_VENDOR_ATTACHMENT;
				$returnSet										 = ServiceCallQueue::model()->create($model, $model->scq_to_be_followed_up_with_entity_type, ServiceCallQueue::PLATFORM_WHATSAPP);
				$status											 = $returnSet->getStatus();
			}
			else if ($queueType == ServiceCallQueue::TYPE_EXISTING_BOOKING)
			{
				Whatsapp::processTextMsg($arrData['replyingToMessageId'], $arrData['phone_number'], 'Please provide your booking id. Example(OW303988065/3988065)');
			}
			else if ($queueType == ServiceCallQueue::TYPE_EXISTING_VENDOR)
			{
				Whatsapp::processTextMsg($arrData['replyingToMessageId'], $arrData['phone_number'], 'What is your booking id. Example(OW303988065/3988065)');
			}

			skipCheck:
			if ($status)
			{
				Whatsapp::processTextMsg($arrData['replyingToMessageId'], $arrData['phone_number'], 'Your callback request has been registered sucessfully.Our team will contact you as soon as possible.');
			}
		}
	}

	public static function bookingReviewCustomerOtherLinks($bkgId, $promoId = '')
	{
		if ($bkgId > 0)
		{
			$bkgModel = Booking::model()->findByPk($bkgId);
		}
		$entityId	 = $bkgModel->bkgUserInfo->bkg_user_id;
		$contactId	 = ContactProfile::getByEntityId($entityId, UserInfo::TYPE_CONSUMER);
		$cttModel	 = Contact::model()->findByPk($contactId);
		$userName	 = $cttModel->ctt_first_name;
		if ($promoId != '')
		{
			$promoModel	 = Promos::model()->findByPk($promoId);
			$code		 = $promoModel->prm_code;
			$discount	 = $promoModel->prm_desc;
		}

		$templateName	 = 'booking_review_to_customer_other_links';
		$lang			 = 'en_US';

		$arrWhatsAppData = [$userName, $discount, $code];
		$arrDBData		 = ['entity_type' => UserInfo::TYPE_CONSUMER, 'entity_id' => $entityId, 'ref_type' => WhatsappLog::REF_TYPE_BOOKING, 'ref_id' => $bkgId];

		$phone	 = ContactPhone::getContactPhoneById($contactId);
		$isPhone = Filter::parsePhoneNumber($phone, $code, $number);
		$phoneNo = $code . $number;

		$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$arrButton	 = [];
		$response	 = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);
		return $response;
	}

	/**
	 * This function is used to send WhatsApp notification for on going customer trip
	 * @param type $bkgId 
	 * @return type None
	 */
	public static function sendWhatsappForCustomerOngoingTrip($bkgId)
	{
		$model = Booking::model()->findByPk($bkgId);
		if (($model->bkg_agent_id == null || $model->bkg_agent_id == "") && in_array($model->bkg_booking_type, [1, 2, 3]))
		{
			try
			{
				$templateName	 = 'customer_ongoing_trip';
				$lang			 = 'en_US';
				$customerName	 = ($model->bkgUserInfo->bkg_user_fname . ' ' . $model->bkgUserInfo->bkg_user_lname);
				$phnumber		 = '';
				$code			 = '';
				$custPhone		 = $model->bkgUserInfo->bkg_contact_no;
				Filter::parsePhoneNumber($custPhone, $code, $phnumber);
				$bkpnURL		 = Filter::getBkpnURL($bkgId);
				$arrWhatsAppData = [$customerName, $bkpnURL, '9051877000', 'DISC20', '20%'];
				$arrDBData		 = ['entity_type' => UserInfo::TYPE_CONSUMER, 'entity_id' => $model->bkgUserInfo->bkg_user_id, 'ref_type' => WhatsappLog::REF_TYPE_BOOKING, 'ref_id' => $bkgId];
				$arrBody		 = Whatsapp::buildComponentBody($arrWhatsAppData);
				$templateId		 = WhatsappLog::findByTemplateNameLang($templateName, $lang, 'wht_id');
				$arrButton		 = Whatsapp::buildComponentButton([$templateId], 'button', 'quick_reply', "payload");
				$response		 = WhatsappLog::send(($code . $custPhone), $templateName, $arrDBData, $arrBody, $arrButton, $lang);
				$success		 = ($response['status'] == 2 && $response['wamId'] != null) ? true : false;
				if ($success)
				{
					$templateId	 = WhatsappLog::findByTemplateNameLang($templateName, $lang, 'wht_id');
					$row		 = ["initiateBy" => WhatsappInitiateTrack::INITIATE_BY_GOZO, "initiateType" => WhatsappInitiateTrack::INITIATE_TYPE_USER, "templateId" => $templateId, "phoneNumber" => $code . $custPhone];
					WhatsappInitiateTrack::add($row);
					WhatsappInitiateTrack::updateStatus($row['initiateBy'], $row['initiateType'], $row['phoneNumber']);
				}
			}
			catch (Exception $ex)
			{
				ReturnSet::setException($ex);
			}
		}
	}

	/**
	 * 
	 * @param type $vndId
	 * @param type $contactId
	 * @param type $flag
	 * @return boolean
	 */
	public static function sendWhatsappForFreezeUnfreezeVendor($vndId, $contactId, $status)
	{
		$cttModel	 = Contact::model()->findByPk($contactId);
		$userName	 = (!empty(trim($cttModel->ctt_business_name)) ? trim($cttModel->ctt_business_name) : ($cttModel->ctt_first_name . ' ' . $cttModel->ctt_last_name));

		$objPhoneNumber = ContactPhone::getPrimaryNumber($contactId);
		if (!$objPhoneNumber)
		{
			return ['status' => 3, 'wamId' => '', 'error' => 'No contact phone found'];
		}

		$code	 = $objPhoneNumber->getCountryCode();
		$number	 = str_replace(" ", "", $objPhoneNumber->getNationalNumber());
		$phoneNo = $code . $number;

		$arrWhatsAppData = [];
		if ($status == 1)
		{
			$templateName	 = 'operator_account_freezed';
			$arrWhatsAppData = [$userName, 'freezed'];
		}
		else
		{
			$templateName	 = 'operator_account_unfreezed';
			$arrWhatsAppData = [$userName];
		}

		$lang		 = 'en_US';
		$arrDBData	 = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $vndId];
		$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$arrButton	 = [];

		$response = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);
		return $response;
	}

	/**
	 * 
	 * @return boolean
	 */
	public static function sendHelloWorld()
	{
		$arrWhatsAppData = [];
		$phoneNo		 = "919831100164";
		$templateName	 = 'hello_world';
		$lang			 = 'en_US';
		$arrDBData		 = [];

		$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$arrButton	 = [];

		$response = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);
		return $response;
	}

	public static function notifyConsumerForGozonow($bkgId)
	{
		if ($bkgId > 0)
		{
			$bkgModel = Booking::model()->findByPk($bkgId);
		}
		if (!$bkgModel || $bkgModel->bkg_reconfirm_flag == 1)
		{
			return false;
		}
		try
		{
			$entityId	 = $bkgModel->bkgUserInfo->bkg_user_id;
			$contactId	 = ContactProfile::getByEntityId($entityId, UserInfo::TYPE_CONSUMER);
			$cttModel	 = Contact::model()->findByPk($contactId);
			$userName1	 = $cttModel->ctt_first_name . ' ' . $cttModel->ctt_last_name;
			if (!empty($cttModel->ctt_business_name))
			{
				$userName1 = $cttModel->ctt_business_name;
			}

			$userId = $bkgModel->bkgUserInfo->bkg_user_id;

			$hash		 = Yii::app()->shortHash->hash($bkgId);
			$buttonUrl10 = Yii::app()->params['fullBaseURL'] . '/gznow/' . $bkgId . '/' . $hash;
			$buttonUrl	 = 'gznow/' . $bkgId . '/' . $hash;

			$result = BookingVendorRequest::getGNowLastOffer($bkgId);
			if (!$result)
			{
				return false;
			}

			$cabType2		 = trim($result['vht_make'] . ' ' . $result['vht_model'] . ' (' . $result['cab_type'] . ')');
			$cabArriveAt3	 = DateTimeFormat::DateTimeToLocale($result['reachingAtTime']);
			$amount4		 = Filter::moneyFormatter($result['totalCalculated']);
			$bookingId5		 = $bkgModel->bkg_booking_id;
			$pickupTime6	 = DateTimeFormat::DateTimeToLocale($bkgModel->bkg_pickup_date);
			$pickupLoc7		 = $bkgModel->bkg_pickup_address;
			$dropLoc8		 = $bkgModel->bkg_drop_address != null and trim($bkgModel->bkg_drop_address) != "" ? $bkgModel->bkg_drop_address : $bkgModel->bkg_pickup_address;
			$distance9		 = $bkgModel->bkg_trip_distance . ' KM';

			$arrWhatsAppData = [$userName1, $cabType2, $cabArriveAt3, $amount4, $bookingId5, $pickupTime6, $pickupLoc7, $dropLoc8, $distance9, $buttonUrl10];

			$phone = ContactPhone::getContactPhoneById($contactId);
			if (!$phone)
			{
				$phone = $bkgModel->bkgUserInfo->bkg_contact_no;
			}
			if (!$phone || trim($phone) == '')
			{
				return false;
			}

			$code	 = $number	 = null;
			Filter::parsePhoneNumber($phone, $code, $number);
			$phoneNo = $code . $number;

			$templateName	 = 'booking_bid_received_to_customer';
			$lang			 = 'en_US';

			$arrDBData = ['entity_type' => UserInfo::TYPE_CONSUMER, 'entity_id' => $userId, 'ref_type' => WhatsappLog::REF_TYPE_BOOKING, 'ref_id' => $bkgId];

			$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
			$arrButton	 = Whatsapp::buildComponentButton([$buttonUrl]);
			$refId		 = $userId;
			$response	 = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);
			if ($response['status'] == 2)
			{
				if ($response['whlId'])
				{
					$refId = $response['whlId'];
				}
				BookingLog::missedGozoNowOfferNotified($bkgId, $refId, true);
			}
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			$response = ['status' => 3];
		}
		return $response;
	}

	public static function getPhoneNoByContactId($contactId)
	{
		$objPhoneNumber = ContactPhone::getPrimaryNumber($contactId);
		if (!$objPhoneNumber)
		{
			return false;
		}

		$code	 = $objPhoneNumber->getCountryCode();
		$number	 = str_replace(" ", "", $objPhoneNumber->getNationalNumber());
		$phoneNo = $code . $number;

		return $phoneNo;
	}

	public static function getPhoneNoByBookingId($bkgId = null, $bkgModel = null, $checkVerified = false)
	{
		$bUserModel = null;
		if ($bkgModel instanceof Booking)
		{
			$bUserModel = $bkgModel->bkgUserInfo;
		}
		if (!$bUserModel instanceof BookingUser && $bkgId > 0)
		{
			$bUserModel = BookingUser::model()->getByBkgId($bkgId);
		}
		if (!$bUserModel)
		{
			return false;
		}

		$phone = $bUserModel->bkg_contact_no;
		if ($phone && trim($phone) != '')
		{
			$phone = $bUserModel->bkg_country_code . $bUserModel->bkg_contact_no;
			goto skipAll;
		}

		$entityId	 = $bUserModel->bkg_user_id;
		$contactId	 = ContactProfile::getByEntityId($entityId, UserInfo::TYPE_CONSUMER);
		$cttModel	 = Contact::model()->findByPk($contactId);
		if (!$cttModel)
		{
			return false;
		}

		$phone = self::getPhoneNoByContactId($contactId);
		if (!$phone || trim($phone) == '')
		{
			return false;
		}

		skipAll:
		if ($checkVerified)
		{
			$isVerified = ContactPhone::checkVerified($phone);
			if (!$isVerified)
			{
				return false;
			}
		}


		$code	 = $number	 = null;
		Filter::parsePhoneNumber($phone, $code, $number);
		$phoneNo = $code . $number;

		return $phoneNo;
	}

	public static function getUserByBooking($bkgId = null, $bkgModel = null)
	{
		$bUserModel = null;
		if ($bkgModel instanceof Booking)
		{
			$bUserModel = $bkgModel->bkgUserInfo;
		}
		if (!$bUserModel instanceof BookingUser && $bkgId > 0)
		{
			$bUserModel = BookingUser::model()->getByBkgId($bkgId);
		}
		if (!$bUserModel)
		{
			return false;
		}

		$entityId	 = $bUserModel->bkg_user_id;
		$userName	 = $bUserModel->bkg_user_fname . ' ' . $bUserModel->bkg_user_lname;
		if (trim($userName) == '')
		{
			$entityId	 = $bUserModel->bkg_user_id;
			$contactId	 = ContactProfile::getByEntityId($entityId, UserInfo::TYPE_CONSUMER);
			$cttModel	 = Contact::model()->findByPk($contactId);
			$userName	 = $cttModel->ctt_first_name . ' ' . $cttModel->ctt_last_name;
		}

		return ['userId' => $entityId, 'userName' => $userName];
	}

	public static function allowSend($arrDBData, $templateName, $skipPermission = false)
	{
		$entityType			 = $arrDBData['entity_type'];
		#$entityId			 = $arrDBData['entity_id'];
		$refType			 = $arrDBData['ref_type'];
		$refId				 = $arrDBData['ref_id'];
		$restrictedPartners	 = [18190, 30228, 34928, 35108, 460020, 435251, 410145, 453597, 32833, 31538];
		$val				 = 1;
		if ($refType == WhatsappLog::REF_TYPE_BOOKING)
		{
			$bkgModel = Booking::model()->findByPk($refId);
			if (!$bkgModel || $bkgModel->bkg_agent_id == 1249 || $bkgModel->bkg_agent_id == null)
			{
				return true;
			}
			elseif (in_array($bkgModel->bkg_agent_id, $restrictedPartners) && !$skipPermission)
			{
				return false;
			}
			elseif ($bkgModel->bkg_agent_id > 0 && !$skipPermission)
			{
				$val = self::getMessagePermissionByTemplate($bkgModel->bkg_id, $templateName, $entityType);
			}

			return $val;
		}
		return true;
	}

	public static function accountApprovedToVendor($vndId)
	{
		$vndModel = Vendors::model()->findByPk($vndId);
		if (!$vndModel)
		{
			return ['status' => 3, 'wamId' => '', 'error' => 'No vendor found'];
		}

		$contactId	 = ContactProfile::getByEntityId($vndId, UserInfo::TYPE_VENDOR);
		$phoneNo	 = self::getPhoneNoByContactId($contactId);
		if (!$phoneNo)
		{
			return ['status' => 3, 'wamId' => '', 'error' => 'No contact phone found'];
		}

		$cttModel	 = Contact::model()->findByPk($contactId);
		$userName	 = (!empty(trim($cttModel->ctt_business_name)) ? trim($cttModel->ctt_business_name) : ($cttModel->ctt_first_name . ' ' . $cttModel->ctt_last_name));

		$partnerAppUrl	 = 'https://play.google.com/store/apps/details?id=com.gozocabs.vendor&hl=en&gl=US';
		$lang			 = 'en_US';
		$templateName	 = 'account_approve_to_vendor';
		$arrDBData		 = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $vndId];
		$arrWhatsAppData = [$userName, $partnerAppUrl];

		$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$arrButton	 = [];

		$response = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);

		return $response;
	}

	public static function reviewBonusToDriver($drvId, $bonusAmount)
	{
		$drvModel = Drivers::model()->findByPk($drvId);
		if (!$drvModel)
		{
			return ['status' => 3, 'wamId' => '', 'error' => 'No driver found'];
		}

		$contactId	 = ContactProfile::getByEntityId($drvId, UserInfo::TYPE_DRIVER);
		$phoneNo	 = self::getPhoneNoByContactId($contactId);
		if (!$phoneNo)
		{
			return ['status' => 3, 'wamId' => '', 'error' => 'No contact phone found'];
		}

		$cttModel	 = Contact::model()->findByPk($contactId);
		$userName	 = (!empty(trim($cttModel->ctt_business_name)) ? trim($cttModel->ctt_business_name) : ($cttModel->ctt_first_name . ' ' . $cttModel->ctt_last_name));

		$lang			 = 'en_US';
		$templateName	 = 'review_bonus_to_driver';
		$arrDBData		 = ['entity_type' => UserInfo::TYPE_DRIVER, 'entity_id' => $drvId];
		$amount			 = Filter::moneyFormatter($bonusAmount);
		$arrWhatsAppData = [$userName, $amount];

		$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$arrButton	 = [];

		$response = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);

		return $response;
	}

	public static function sendQuoteExpiryReminderToCustomer($bkgId)
	{
		$bkgModel = Booking::model()->findByPk($bkgId);
		if (!$bkgModel instanceof Booking)
		{
			return false;
		}

		$minPerc		 = Config::getMinAdvancePercent($bkgModel->bkg_agent_id, $bkgModel->bkg_booking_type, $bkgModel->bkgSvcClassVhcCat->scc_ServiceClass->scc_id, $bkgModel->bkgPref->bkg_is_gozonow);
		$minPay			 = round($minPerc * $bkgModel->bkgInvoice->bkg_total_amount * 0.01);
		$minPercentage	 = " ({$minPerc}%)";

		$userName		 = $bkgModel->bkgUserInfo->bkg_user_fname;
		$bookingId		 = $bkgModel->bkg_booking_id;
		$cabType		 = $bkgModel->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
		$fromCityName	 = $bkgModel->bkgFromCity->cty_name;
		$toCityName		 = $bkgModel->bkgToCity->cty_name;
		$tripType		 = $bkgModel->getBookingType($bkgModel->bkg_booking_type);
		$totalAmt		 = $bkgModel->bkgInvoice->bkg_total_amount;

		$datePickupDate	 = new DateTime($bkgModel->bkg_pickup_date);
		$pickupTime		 = $datePickupDate->format('j/M/y h:i A');

		$bkgExpiryDate	 = new DateTime($bkgModel->bkgTrail->bkg_quote_expire_date);
		$expiryDate		 = '*' . $bkgExpiryDate->format('j/M/y h:i A') . '*';

		$buttonUrl	 = ltrim(BookingUser::getPaymentLinkByPhone($bkgModel->bkg_id), '/');
		$paymentUrl	 = 'http://www.aaocab.com/' . $buttonUrl;

		$supportPhone = "+91-90518-77000";

		$phoneNo = $bkgModel->bkgUserInfo->bkg_country_code . $bkgModel->bkgUserInfo->bkg_contact_no;
		$userId	 = $bkgModel->bkgUserInfo->bkg_user_id;

		$checkVerified	 = true;
		$phoneNo		 = self::getPhoneNoByBookingId($bkgId, $bkgModel, $checkVerified);
		if (!$phoneNo)
		{
			return ['status' => 3, 'wamId' => '', 'error' => 'No contact phone found'];
		}
		$templateName	 = 'quote_expiry_to_customer';
		$lang			 = 'en_GB';
		$arrWhatsAppData = [$userName, $expiryDate, Filter::formatBookingId($bookingId), $tripType, $cabType, $pickupTime, $fromCityName, $toCityName, Filter::moneyFormatter($totalAmt), $minPercentage, Filter::moneyFormatter($minPay), Filter::moneyFormatter($minPay), $paymentUrl, $expiryDate, $supportPhone];
		$arrDBData		 = ['entity_type' => UserInfo::TYPE_CONSUMER, 'entity_id' => $userId, 'ref_type' => WhatsappLog::REF_TYPE_BOOKING, 'ref_id' => $bkgId];

		$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$arrButton	 = Whatsapp::buildComponentButton([$buttonUrl]);

		$response = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);

		return $response;
	}

	/**
	 * 
	 * @param type $bkgId
	 * @return boolean|int
	 */
	public static function bookingDetailsToCustomer($bkgId)
	{
		if ($bkgId > 0)
		{
			$bkgModel = Booking::model()->findByPk($bkgId);
		}
		if (!$bkgModel)
		{
			return false;
		}
		try
		{

			$arrUserDetails	 = self::getUserByBooking($bkgId, $bkgModel);
			$userId			 = $arrUserDetails['userId'];
			$userName		 = $arrUserDetails['userName'] != null && !empty(trim($arrUserDetails['userName'])) ? $arrUserDetails['userName'] : "User";

			// Phone No
			$phoneNo = self::getPhoneNoByBookingId($bkgId, $bkgModel);
			if (!$phoneNo)
			{
				return ['status' => 3, 'wamId' => '', 'error' => 'No contact phone found'];
			}
			$hash				 = Yii::app()->shortHash->hash($bkgId);
			$bookingId			 = Filter::formatBookingId($bkgModel->bkg_booking_id);
			$cabType			 = $bkgModel->bkgSvcClassVhcCat->scv_label;
			$pickupAddress		 = $bkgModel->bkgFromCity->cty_name;
			$dropAddress		 = $bkgModel->bkgToCity->cty_name;
			$pickupDate			 = DateTimeFormat::DateTimeToLocale($bkgModel->bkg_pickup_date);
			$tripType			 = trim($bkgModel->getBookingType($bkgModel->bkg_booking_type));
			$distance			 = $bkgModel->bkg_trip_distance . ' KM';
			$amount				 = Filter::moneyFormatter($bkgModel->bkgInvoice->bkg_total_amount);
			$advanceAmount		 = Filter::moneyFormatter($bkgModel->bkgInvoice->bkg_advance_amount);
			$drvId				 = $bkgModel->bkgBcb->bcb_driver_id;
			$cabId				 = $bkgModel->bkgBcb->bcb_cab_id;
			$pickupDiffMinutes	 = Filter::getTimeDiff($bkgModel->bkg_pickup_date);
			$link				 = Yii::app()->params['fullBaseURL'] . '/bkpn/' . $bkgId . '/' . $hash;
			$buttonUrl			 = 'bkpn/' . $bkgId . '/' . $hash;
			if (in_array($bkgModel->bkg_status, [5, 6, 7]) && $drvId > 0 && $cabId > 0)
			{
				if ($pickupDiffMinutes > 120)
				{
					$templateName	 = 'only_booking_details_to_customer';
					$lang			 = 'en_GB';
					$arrWhatsAppData = [$userName, $bookingId, $cabType, $pickupAddress, $dropAddress, $pickupDate, $tripType, $distance, $amount, $advanceAmount, $link];
				}
				else
				{
					$templateName	 = 'booking_details_to_customer';
					$lang			 = 'en_US';
					$cabNumber		 = $bkgModel->bkgBcb->bcb_cab_number;
					$driverName		 = $bkgModel->bkgBcb->bcb_driver_name;
					if (!$driverName && $bkgModel->bkgBcb->bcbDriver)
					{
						$drvDetails	 = Drivers::getByDriverId($drvId);
						$driverName	 = $drvDetails['ctt_first_name'] . ' ' . $drvDetails['ctt_last_name'];
					}
					$driverNumber	 = $bkgModel->bkgBcb->bcb_driver_phone;
					$arrWhatsAppData = [$userName, $bookingId, $cabType, $pickupAddress, $dropAddress, $pickupDate, $tripType, $distance, $amount, $advanceAmount, $cabNumber, $driverName, $driverNumber, $link];
				}
			}
			else
			{
				$templateName	 = 'only_booking_details_to_customer';
				$lang			 = 'en_GB';
				$arrWhatsAppData = [$userName, $bookingId, $cabType, $pickupAddress, $dropAddress, $pickupDate, $tripType, $distance, $amount, $advanceAmount, $link];
			}
			$arrDBData	 = ['entity_type' => UserInfo::TYPE_CONSUMER, 'entity_id' => $userId, 'ref_type' => WhatsappLog::REF_TYPE_BOOKING, 'ref_id' => $bkgId];
			$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
			$arrButton	 = Whatsapp::buildComponentButton([$buttonUrl]);
			$response	 = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			$response = ['status' => 3];
		}
		return $response;
	}

	/**
	 * 
	 * @param type $vndId
	 * @return boolean|int
	 */
	public static function informVendorsOnApprove($vndId, $isDelay = false)
	{
		if ($vndId > 0)
		{
			$vndModel = Vendors::model()->findByPk($vndId);
		}
		if (!$vndModel)
		{
			return false;
		}
		try
		{
			/** @var Vendors $vndModel */
			$contactId	 = ContactProfile::getByEntityId($vndId, UserInfo::TYPE_VENDOR);
			$cttModel	 = Contact::model()->findByPk($contactId);
			$userName	 = $cttModel->ctt_first_name . ' ' . $cttModel->ctt_last_name;
			if (!empty($cttModel->ctt_business_name))
			{
				$userName = $cttModel->ctt_business_name;
			}
			$link = 'https://play.google.com/store/apps/details?id=com.gozocabs.vendor&amp;hl=en&amp;gl=US';

			$arrWhatsAppData = [$userName, $link];

			$phone = ContactPhone::getContactPhoneById($contactId);
			if (!$phone || trim($phone) == '')
			{
				return false;
			}
			$code			 = $number			 = null;
			Filter::parsePhoneNumber($phone, $code, $number);
			$phoneNo		 = $code . $number;
			$templateName	 = 'account_approve_to_vendor';
			$lang			 = 'en_US';
			$arrDBData		 = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $vndId, 'ref_type' => WhatsappLog::REF_TYPE_VENDOR, 'ref_id' => $vndId];

			$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
			$arrButton	 = [];

			$response = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang, $isDelay);
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			$response = ['status' => 3];
		}
		return $response;
	}

	public function isMessageAlreadySent($arrSearchData = [])
	{
		if (isset($arrSearchData['template_name']) && trim($arrSearchData['template_name']) != '')
		{
			$templateId						 = self::findByTemplateName(trim($arrSearchData['template_name']), 'wht_id');
			$arrSearchData['template_id']	 = ($templateId > 0 ? $templateId : 0);
		}

		$qry = '';
		$qry .= ((isset($arrSearchData['entity_type']) && $arrSearchData['entity_type'] > 0) ? " AND whl_entity_type = {$arrSearchData['entity_type']}" : "");
		$qry .= ((isset($arrSearchData['entity_id']) && $arrSearchData['entity_id'] > 0) ? " AND whl_entity_id = {$arrSearchData['entity_id']}" : "");
		$qry .= ((isset($arrSearchData['template_id']) && $arrSearchData['template_id'] > 0) ? " AND whl_wht_id = {$arrSearchData['template_id']}" : "");
		$qry .= ((isset($arrSearchData['ref_type']) && $arrSearchData['ref_type'] > 0) ? " AND whl_ref_type = {$arrSearchData['ref_type']}" : "");
		$qry .= ((isset($arrSearchData['ref_id']) && $arrSearchData['ref_id'] > 0) ? " AND whl_ref_id = {$arrSearchData['ref_id']}" : "");
		$qry .= ((isset($arrSearchData['create_date']) && $arrSearchData['create_date'] != '') ? " AND whl_created_date >= '{$arrSearchData['create_date']}'" : "");
		$qry .= ((isset($arrSearchData['status']) && $arrSearchData['status'] > 0) ? " AND whl_status = {$arrSearchData['status']}" : "");

		if ($qry == '')
		{
			return false;
		}

		$sqlAlreadySent	 = "SELECT COUNT(1) cnt FROM whatsapp_log WHERE 1 {$qry}";
		$cntSent		 = DBUtil::queryScalar($sqlAlreadySent, DBUtil::SDB());
		if ($cntSent <= 0 || $cntSent == null)
		{
			return false;
		}

		return $cntSent;
	}

	public static function notifyVendorsByTemplate($vndId, $templateName)
	{
		$templateName = 'account_approve_to_vendor';
		if ($vndId > 0)
		{
			$vndModel = Vendors::model()->findByPk($vndId);
		}
		if (!$vndModel)
		{
			return false;
		}
		try
		{
			/** @var Vendors $vndModel */
			$contactId	 = ContactProfile::getByEntityId($vndId, UserInfo::TYPE_VENDOR);
			$cttModel	 = Contact::model()->findByPk($contactId);
			$userName	 = $cttModel->ctt_first_name . ' ' . $cttModel->ctt_last_name;
			if (!empty($cttModel->ctt_business_name))
			{
				$userName = $cttModel->ctt_business_name;
			}
			$link = 'https://play.google.com/store/apps/details?id=com.gozocabs.vendor&amp;hl=en&amp;gl=US';

			$arrWhatsAppData = [$userName, $link];

			$phone = ContactPhone::getContactPhoneById($contactId);
			if (!$phone || trim($phone) == '')
			{
				return false;
			}
			$code	 = $number	 = null;
			Filter::parsePhoneNumber($phone, $code, $number);
			$phoneNo = $code . $number;

			$lang		 = 'en_US';
			$arrDBData	 = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $vndId, 'ref_type' => WhatsappLog::REF_TYPE_VENDOR, 'ref_id' => $vndId];

			$arrBody	 = Whatsapp::buildComponentBody($arrWhatsAppData);
			$arrButton	 = [];

			$response = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			$response = ['status' => 3];
		}
		return $response;
	}

	public static function getMessagePermissionByTemplate($bkgid, $template, $userType = UserInfo::TYPE_CONSUMER)
	{
		switch ($userType)
		{
			case UserInfo::TYPE_CONSUMER:
				$column	 = 'bkg_trvl_whatsapp';
				break;
			case UserInfo::TYPE_AGENT:
				$column	 = 'bkg_agent_whatsapp';
				break;
			default:
				return 1;
		}

		$sql = "SELECT bm.$column FROM `whatsapp_templates` wht
			LEFT JOIN booking_messages bm ON wht.wht_message_event_id = bm.bkg_event_id AND bm.bkg_booking_id
			WHERE wht.wht_message_event_id IS NOT NULL 
			AND bm.bkg_booking_id = $bkgid 
			AND `wht_template_name` = '$template'";
		$val = DBUtil::queryScalar($sql, DBUtil::SDB());
		return $val;
	}

	/**
	 * This function is used to process send welcome message
	 * @param type $arrData array 
	 * @return type None
	 */
	public static function welcomeMessage($arrData)
	{
		$arrDBData	 = ['ref_replying_id' => $arrData['wamId'], 'ref_payload' => 51];
		$arrBody	 = Whatsapp::buildComponentBody([]);
		$arrButton	 = Whatsapp::buildComponentButton([51, 51, 51, 51], 'button', 'quick_reply,quick_reply,quick_reply,quick_reply', "payload,payload,payload,payload");
		return WhatsappLog::send($arrData['phone_number'], 'welecome_message', $arrDBData, $arrBody, $arrButton, 'en_US');
	}

	/**
	 * This function is used to check if it new message or other
	 * @param type $phone 
	 * @return type int
	 */
	public static function isExistWelcome($phone)
	{
		$sql = "SELECT 
					whl_id,
					IF( JSON_UNQUOTE(JSON_EXTRACT(whl_message, concat('$[', 0, '].text')))='Please provide your booking id. Example(OW303988065/3988065)',1,0) AS 'isCustomerExits',
					IF( JSON_UNQUOTE(JSON_EXTRACT(whl_message, concat('$[', 0, '].text')))='What is your booking id. Example(OW303988065/3988065)',1,0) AS 'isVendorExits'
				FROM  whatsapp_log 
				WHERE 1 
					AND  whl_phone_number=:phone
					AND whl_replying_id IS NOT NULL
					AND whl_status=2 
					AND 
				   (
						JSON_UNQUOTE(JSON_EXTRACT(whl_message, concat('$[', 0, '].text')))='Please provide your booking id. Example(OW303988065/3988065)'
							OR 
						JSON_UNQUOTE(JSON_EXTRACT(whl_message, concat('$[', 0, '].text')))='What is your booking id. Example(OW303988065/3988065)'
					) ORDER BY  whl_id DESC";
		return DBUtil::queryRow($sql, DBUtil::SDB(), ['phone' => $phone]);
	}

	/**
	 * This function is used to process welcome message
	 * @param type $arrData  
	 * @return type None
	 */
	public static function processWelcomeMessage($arrData)
	{
		if ($arrData['message_type'] == WhatsappLog::MSG_TYPE_TEXT)
		{
			$whlIdDetails = WhatsappLog::isExistWelcome($arrData['phone_number']);
			if (!$whlIdDetails)
			{
				WhatsappLog::welcomeMessage($arrData);
			}
			else
			{
				$bkgId = Booking::getBookingId($arrData['message']);
				if ($bkgId > 0 && ($whlIdDetails['isVendorExits'] == 1 || $whlIdDetails['isCustomerExits'] == 1))
				{
					$profileDetails = ContactProfile::getProfilebyPhone($arrData['phone_number']);
					if ($whlIdDetails['isVendorExits'] == 1 && $profileDetails['cr_is_vendor'] == null)
					{
						Whatsapp::processTextMsg($arrData['wamId'], $arrData['phone_number'], "We didn't find any vendor account linked with this phone number.");
					}
					else if ($whlIdDetails['isCustomerExits'] == 1 && $profileDetails['cr_is_consumer'] == null)
					{
						Whatsapp::processTextMsg($arrData['wamId'], $arrData['phone_number'], "We didn't find any customer account linked with this phone number.");
					}
					else
					{
						$model											 = new ServiceCallQueue();
						$model->scq_created_by_type						 = 1;
						$model->scq_created_by_uid						 = $profileDetails['cr_is_consumer'];
						$model->scq_to_be_followed_up_with_type			 = 2;
						$model->scq_related_bkg_id						 = $bkgId;
						$model->force									 = true;
						$model->scq_to_be_followed_up_with_value		 = $arrData['phone_number'];
						$model->scq_to_be_followed_up_with_contact		 = $profileDetails['cr_contact_id'];
						$model->scq_to_be_followed_up_with_entity_type	 = ($whlIdDetails['isCustomerExits'] == 1 && $whlIdDetails['isVendorExits'] == 0) ? UserInfo::TYPE_CONSUMER : UserInfo::TYPE_VENDOR;
						$model->scq_to_be_followed_up_with_entity_id	 = ($whlIdDetails['isCustomerExits'] == 1 && $whlIdDetails['isVendorExits'] == 0) ? $profileDetails['cr_is_consumer'] : $profileDetails['cr_is_vendor'];
						$model->scq_creation_comments					 = ($whlIdDetails['isCustomerExits'] == 1 && $whlIdDetails['isVendorExits'] == 0) ? "Customer is facing some problem with his booking :$bkgId.Please call to resolve his/her issue quickly." : "Vendor is facing some problem with his booking :$bkgId.Please call to resolve his/her issue quickly.";
						$model->scq_follow_up_queue_type				 = ($whlIdDetails['isCustomerExits'] == 1 && $whlIdDetails['isVendorExits'] == 0) ? ServiceCallQueue::TYPE_EXISTING_BOOKING : ServiceCallQueue::TYPE_EXISTING_VENDOR;
						$returnSet										 = ServiceCallQueue::model()->create($model, $model->scq_to_be_followed_up_with_entity_type, ServiceCallQueue::PLATFORM_WHATSAPP);
						if ($returnSet->getStatus())
						{
							Whatsapp::processTextMsg($arrData['wamId'], $arrData['phone_number'], 'Your callback request has been registered sucessfully.Our team will contact you as soon as possible.');
						}
						else
						{
							Whatsapp::processTextMsg($arrData['wamId'], $arrData['phone_number'], "Some error occured.Please try after some time.");
						}
					}
				}
				else if (WhatsappLog::countExistingBooking($arrData['phone_number'], $whlIdDetails['whl_id']) < 2)
				{
					Whatsapp::processTextMsg($arrData['wamId'], $arrData['phone_number'], 'We are unable to fetch details with your booking id.');
				}
				else
				{
					WhatsappLog::welcomeMessage($arrData);
				}
			}
		}
	}

	/**
	 * This function is used to check if it new message or other
	 * @param type $phone 
	 * @param type $whlId 
	 * @return type int
	 */
	public static function countExistingBooking($phone, $whlId)
	{
		$sql = "SELECT COUNT(*) from whatsapp_log WHERE 1 and whl_phone_number=:phone AND whl_id>:whlId  AND whl_status=4";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), ['phone' => $phone, 'whlId' => $whlId]);
	}

	public static function setWhatsappParams($phoneNumber, $entity_type = null, $entity_id = null, $ref_type = null, $ref_id = null, $isButton = 0, $buttonUrl = null)
	{
		return array(
			'entity_type'	 => $entity_type,
			'entity_id'		 => $entity_id,
			'ref_type'		 => $ref_type,
			'ref_id'		 => $ref_id,
			'is_button'		 => $isButton,
			'button_url'	 => $buttonUrl,
			'phoneNumber'	 => $phoneNumber
		);
	}

	/**
	 * This function is used process Whatapps  
	 * @param Object $obj
	 * @param Array $contentParams
	 * @param Object $receiverParams
	 * @param Object $eventScheduleParams
	 * @return boolean
	 */
	public static function process($obj, $contentParams = [], $receiverParams = null, $eventScheduleParams = null)
	{
		$returnSet	 = new ReturnSet();
		$time		 = ($eventScheduleParams->event_sequence == TemplateMaster::SEQ_WHATSAPP_CODE) ? $eventScheduleParams->schedule_time : 0;
		if ($time > 0 && $eventScheduleParams->event_schedule == 1)
		{
			ScheduleEvent::add($eventScheduleParams->ref_id, $eventScheduleParams->ref_type, $eventScheduleParams->event_id, $eventScheduleParams->remarks, $eventScheduleParams->addtional_data, $time, TemplateMaster::SEQ_WHATSAPP_CODE);
			$returnSet->setStatus(true);
			$returnSet->setData(['type' => TemplateMaster::SEQ_WHATSAPP_CODE]);
		}
		else
		{
			if ($obj->filename != null)
			{
				if (Yii::app() instanceof CConsoleApplication)
				{
					Logger::trace("Comand");
					$data = Yii::app()->command->renderFile(Yii::getPathOfAlias("application.components.Event.whatsapp.{$obj->filename}") . ".php", array('id' => $contentParams['primaryId'], 'arrayData' => $contentParams), true);
				}
				else
				{
					Logger::trace("web");
					$data = Yii::app()->controller->renderFile(Yii::getPathOfAlias("application.components.Event.whatsapp.{$obj->filename}") . ".php", array('id' => $contentParams['primaryId'], 'arrayData' => $contentParams), true);
				}
				$dataObj = json_decode($data);
				Logger::trace("Whatapp Data " . json_encode($dataObj));
				if (!$dataObj->status)
				{
					$returnSet->setStatus($dataObj->status);
					$returnSet->setData(['type' => TemplateMaster::SEQ_WHATSAPP_CODE]);
					goto skipAll;
				}
				$whatArr		 = $dataObj->data;
				Logger::trace("WhatData: " . json_encode($whatArr));
				$templateName	 = $dataObj->templateName;
				$lang			 = ($dataObj->lang != '') ? $dataObj->lang : $obj->language;
			}
			else
			{
				$templateName	 = $obj->name;
				$whatArr		 = TemplateMaster::getWhatsppVariables(json_decode($obj->variables, true), $contentParams);
				$lang			 = $obj->language;
			}
			//$lang			 = $obj->language;
			$whatAppParams	 = WhatsappLog::setWhatsappParams(($receiverParams->ext . $receiverParams->number), $receiverParams->entity_type, $receiverParams->entity_id, $receiverParams->ref_type, $receiverParams->ref_id, $receiverParams->is_button, $receiverParams->button_url);
			$arrBody		 = Whatsapp::buildComponentBody($whatArr);
			$btnCnt			 = 0;
			if ($whatAppParams['is_button'] == 0)
			{
				$arrButton = Whatsapp::buildComponentButton([]);
			}
			else if ($whatAppParams['is_button'] == 1 && is_array($whatAppParams['button_url']))
			{
				$whatsAppData	 = $whatAppParams['button_url'];
				$btnCnt			 = sizeof(explode(",", $whatsAppData['data']));
				$arrButton		 = Whatsapp::buildComponentButton(explode(",", $whatsAppData['data']), $whatsAppData['type'], $whatsAppData['subType'], $whatsAppData['text']);
			}
			else
			{
				$btnCnt		 = 1;
				$arrButton	 = Whatsapp::buildComponentButton([$whatAppParams['button_url']]);
			}
			$skipPermission		 = $contentParams['skipPermission'] ? true : false;
			$whatResponse		 = WhatsappLog::send($whatAppParams['phoneNumber'], $templateName, $whatAppParams, $arrBody, $arrButton, $lang, false, $skipPermission);
			$isPolicyReminderCnt = self::isPolicyReimderSend(($receiverParams->ext . $receiverParams->number), $receiverParams->entity_type, $receiverParams->entity_id, $receiverParams->ref_type, $receiverParams->ref_id);
			if ($obj->policy_reminder == 1 && $whatResponse['status'] == 2 && $whatResponse['wamId'] != null && $isPolicyReminderCnt == 0)
			{
				self::policyReminderForUser(($receiverParams->ext . $receiverParams->number), $receiverParams->entity_type, $receiverParams->entity_id, $receiverParams->ref_type, $receiverParams->ref_id, $lang);
			}
			Logger::trace("WhatResponse: " . json_encode($whatResponse));
			$whlId = ($whatResponse['status'] == 2 && $whatResponse['wamId'] != null) ? $whatResponse['whlId'] : 0;
			if ($whlId > 0 && $eventScheduleParams->ref_id != null && $eventScheduleParams->ref_id > 0)
			{
				MarketingMessageTracker::add($eventScheduleParams->ref_type, $eventScheduleParams->ref_id, $contentParams['eventId'], TemplateMaster::SEQ_WHATSAPP_CODE, $btnCnt);
			}
			$returnSet->setStatus($whlId > 0 ? true : false);
			$returnSet->setData(['type' => TemplateMaster::SEQ_WHATSAPP_CODE, 'id' => $whlId]);
			Logger::trace("returnSet :" . json_encode($returnSet));
		}
		skipAll:
		return $returnSet;
	}

	public static function getPendingLogs($whlId = '')
	{
		$condition = "";
		if ($whlId != '')
		{
			$condition = " AND `whl_id` ={$whlId}";
		}
		$sql	 = "SELECT
					whl.whl_id,
					whl.whl_phone_number,
					whl.whl_message_component,
					whl.whl_wht_id,
					wht.wht_template_name, 
					wht.wht_lang_code
				FROM `whatsapp_log` whl
				LEFT JOIN whatsapp_templates wht ON
					wht.wht_id = whl.whl_wht_id 						
				WHERE whl.whl_status = 1 $condition
				AND (whl_created_date BETWEEN DATE_SUB(NOW(),INTERVAL 240 MINUTE) AND DATE_SUB(NOW(),INTERVAL 60 MINUTE))";
		$result	 = DBUtil::query($sql);
		return $result;
	}

	public static function sendPendingMessages($whlId = '')
	{
		$dataSet = WhatsappLog::getPendingLogs($whlId);
		$c		 = count($dataSet);
		Logger::writeToConsole("Total $c records to update");
		foreach ($dataSet as $row)
		{
			try
			{
				$whlId			 = $row['whl_id'];
				$phoneNo		 = $row['whl_phone_number'];
				$arrComponent	 = json_decode($row['whl_message_component'], 1);
				$templateName	 = $row['wht_template_name'];
				if (!$arrComponent['lang'] && $row['wht_lang_code'])
				{
					$arrComponent['lang'] = $row['wht_lang_code'];
				}
				$arrStatus = WhatsappLog::processPendingMsg($whlId, $phoneNo, $arrComponent, $templateName);
			}
			catch (Exception $e)
			{
				Filter::writeToConsole($e->getMessage());
				Logger::error($e);
			}
		}
	}

	public static function processPendingMsg($whlId, $phoneNo, $arrComponent, $templateName)
	{
		$lang		 = $arrComponent['lang'];
		$arrStatus	 = Whatsapp::sendMessage($phoneNo, $arrComponent, $templateName, [], $lang, false, $whlId);
		return $arrStatus;
	}

	public static function sendPendingMsgById($whlId = 0)
	{
		$model = WhatsappLog::model()->findByPk($whlId);
		if (!$model)
		{
			return false;
		}
		/** @var WhatsappLog $model */
		$arrComponent	 = json_decode($model->whl_message_component, 1);
		$phoneNo		 = $model->whl_phone_number;
		$sql			 = "SELECT wht.wht_template_name,wht_lang_code from whatsapp_templates wht WHERE wht.wht_id = :whtid";
		$template		 = DBUtil::queryRow($sql, DBUtil::SDB(), ['whtid' => $model->whl_wht_id]);
		$lang			 = ($arrComponent['lang']) ? $arrComponent['lang'] : $template['wht_lang_code'];
		$templateName	 = $template['wht_template_name'];

		$arrStatus = Whatsapp::sendMessage($phoneNo, $arrComponent, $templateName, [], $lang, false, $whlId);
		return $arrStatus;
	}

	/**
	 * Function for archiving WhatsappLog 
	 */
	public function archiveData($archiveDB, $upperLimit = 100000, $lowerlimit = 1000)
	{
		Logger::writeToConsole("archiveData");
		$i			 = 0;
		$chk		 = true;
		$totRecords	 = $upperLimit;
		$limit		 = $lowerlimit;
		while ($chk)
		{
			Logger::writeToConsole("While");
			$transaction = DBUtil::beginTransaction();
			try
			{
				$sql	 = "SELECT GROUP_CONCAT(whl_id) AS whl_id FROM (
							SELECT whl_id FROM whatsapp_log 
							WHERE 1 AND (
							(whl_wht_id IN (1,2,3,4,5,6,7,9,14,15,17,24,26,31,32,34,46,49) AND whl_created_date < CONCAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH), ' 00:00:00')) OR 
							(whl_wht_id IN (19,20,21,22,23,25,28,29,35,40,41,44,45,47,48,56,58,61,68) AND whl_created_date < CONCAT(DATE_SUB(CURDATE(), INTERVAL 18 MONTH), ' 00:00:00')) OR 
							(whl_created_date < CONCAT(DATE_SUB(CURDATE(), INTERVAL 24 MONTH), ' 00:00:00')))
							ORDER BY whl_id LIMIT 0, $limit
							) as temp";
				$resQ	 = DBUtil::queryScalar($sql);
				if (!is_null($resQ) && $resQ != '')
				{
					Logger::writeToConsole("INSERT");
					$sql	 = "INSERT INTO " . $archiveDB . ".whatsapp_log (SELECT * FROM whatsapp_log WHERE whl_id IN ($resQ))";
					$rows	 = DBUtil::execute($sql);
					if ($rows > 0)
					{
						Logger::writeToConsole("DELETE");
						$sql = "DELETE FROM `whatsapp_log` WHERE whl_id IN ($resQ)";
						DBUtil::execute($sql);
					}
				}

				Logger::writeToConsole("COMMITTED");
				DBUtil::commitTransaction($transaction);
				$i += $limit;

				if (($resQ <= 0) || $totRecords <= $i)
				{
					Logger::writeToConsole("BREAK");
					break;
				}
			}
			catch (Exception $e)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::writeToConsole("ERROR: " . $e->getMessage());
				echo $e->getMessage() . "\n\n";
			}
		}
	}

	public static function notifyTaggedBooking($bkgId, $phone, $adminId)
	{
		try
		{
			$arrSearchData	 = ['ref_type' => WhatsappLog::REF_TYPE_BOOKING, 'ref_id' => $bkgId, 'template_name' => 'vip_booking_received_to_team', 'entity_type' => UserInfo::TYPE_ADMIN, 'entity_id' => $adminId];
			$isAlreadySent	 = self::isMessageAlreadySent($arrSearchData);
			if ($isAlreadySent)
			{
				return true;
			}
			$code			 = $number			 = null;
			Filter::parsePhoneNumber($phone, $code, $number);
			$phoneNo		 = $code . $number;
			$templateName	 = 'vip_booking_received_to_team';
			$arrDBData		 = ['entity_type' => UserInfo::TYPE_ADMIN, 'entity_id' => $adminId, 'ref_type' => WhatsappLog::REF_TYPE_BOOKING, 'ref_id' => $bkgId];
			$bkgTrail		 = BookingTrail::model()->getbyBkgId($bkgId);
			if ($bkgTrail->bkg_tags != '')
			{
				$strTag		 = '';
				$vipTagsArr	 = Tags::getListByids($bkgTrail->bkg_tags);
				foreach ($vipTagsArr as $key => $val)
				{
					if ($key > 0)
					{
						$strTag .= "/";
					}
					$strTag .= $val['tag_name'];
				}
			}
			$arrWhatsAppData = [$strTag, $bkgId];
			$arrBody		 = Whatsapp::buildComponentBody($arrWhatsAppData);
			$arrButton		 = [];
			$lang			 = 'en_US';
			$response		 = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			$response = ['status' => 3];
		}
		return $response;
	}

	/**
	 * 
	 * @param type $agentId
	 * @param type $bookingIds
	 * @return type
	 */
	public static function sendAlertForCreditLimitExhausted($agentId, $bookingIds)
	{
		try
		{
			$agtmodel		 = Agents::model()->findByPk($agentId);
			$agentName		 = $agtmodel->agt_company;
			$arrWhatsAppData = [];
			$lang			 = 'en_GB';
			$code			 = "91";
			$templateName	 = 'booking_going_for_auto_cancel_to_account_manager';
			$arrWhatsAppData = [$agentName, $bookingIds];
			$arrDBData		 = ['entity_type' => UserInfo::TYPE_AGENT, 'entity_id' => $agentId];
			$arrBody		 = Whatsapp::buildComponentBody($arrWhatsAppData);
			$arrButton		 = [];
			$arrAdmins		 = ['13' => '9831859111', '455' => '8017233722', '544' => '9051153099'];
			foreach ($arrAdmins as $adminId => $phone)
			{
				Filter::parsePhoneNumber($phone, $code, $number);
				$phoneNo	 = $code . $phone;
				$response	 = WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);
			}
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
		}
		return $response;
	}

	public static function checkIfAreadySent($refType, $refId, $whtId, $phone)
	{
		$params	 = ['refType' => $refType, 'refId' => $refId, 'whtId' => $whtId, 'phone' => $phone];
		$sql	 = "SELECT whl_id FROM `whatsapp_log` WHERE  whl_ref_type = :refType AND whl_ref_id = :refId  AND whl_wht_id = :whtId AND whl_phone_number=:phone AND whl_status<>3";
		$id		 = DBUtil::queryScalar($sql, NULL, $params);
		if ($id > 0)
		{
			return true;
		}
		return false;
	}

	public static function policyReminderForUser($phone, $entityType, $entityId, $refType, $refId, $lang)
	{
		if ($refType != 1)
		{
			goto skipCheck;
		}
		$model			 = Booking::model()->findByPk($refId);
		$cancelRuleId	 = $model->bkgPref->bkg_cancel_rule_id;
		if ($cancelRuleId == null)
		{
			goto skipCheck;
		}
		$rule = CancellationPolicy::getRule($cancelRuleId);
		if (!$rule["timeRules"]['workingMinuteBeforePickup'])
		{
			$hrs = ($rule["timeRules"]['minutesBeforePickup']) / 60;
		}
		else
		{
			$hrs = ($rule["timeRules"]['workingMinuteBeforePickup']) / 60;
		}
		if ($hrs == null)
		{
			goto skipCheck;
		}
		$bookingPerAmt	 = Booking::minBookingPercentageAmount($refId);
		$arrDBData		 = ['entity_type' => $entityType, 'entity_id' => $entityId, 'ref_type' => $refType, 'ref_id' => $refId];
		$arrBody		 = Whatsapp::buildComponentBody([$hrs . " hour", $hrs . " hours", $bookingPerAmt . '%']);
		$arrButton		 = Whatsapp::buildComponentButton([]);
		WhatsappLog::send($phone, 'v2_policy_reminder', $arrDBData, $arrBody, $arrButton, $lang);
		skipCheck:
	}

	public static function isPolicyReimderSend($phone, $entityType, $entityId, $refType, $refId)
	{
		$sql = "SELECT 
					COUNT(whl_id) 
				FROM `whatsapp_log` 
				WHERE 1
					AND `whl_ref_type`=:refType 
					AND `whl_ref_id` = :refId
					AND `whl_entity_type`=:entityType 
					AND `whl_entity_id` = :entityId
					AND `whl_phone_number`=:phone 
					AND whl_wht_id=70";

		return DBUtil::queryScalar($sql, DBUtil::SDB(), ['phone' => $phone, 'entityType' => $entityType, 'entityId' => $entityId, 'refType' => $refType, 'refId' => $refId]);
	}

}

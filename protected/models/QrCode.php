<?php

/**
 * This is the model class for table "qr_code".
 *
 * The followings are the available columns in table 'qr_code':
 * @property integer $qrc_id
 * @property string $qrc_code
 * @property integer $qrc_ent_type
 * @property integer $qrc_ent_id
 * @property string $qrc_allocate_date
 * @property integer $qrc_allocated_by
 * @property string $qrc_location_lat
 * @property string $qrc_location_long
 * @property string $qrc_location_name
 * @property string $qrc_location_pic
 * @property string $qrc_contact_name
 * @property string $qrc_contact_phone
 * @property string $qrc_contact_email
 * @property string $qrc_upi_number
 * @property string $qrc_contact_pic
 * @property string $qrc_otp
 * @property integer $qrc_activated_by
 * @property string $qrc_activated_date
 * @property integer $qrc_scanned_count
 * @property integer $qrc_click_count
 * @property integer $qrc_status
 * @property integer $qrc_active
 */
class QrCode extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public $qrCode, $qrStatus, $allocatedType, $custId, $vendId, $drvId, $adminId, $agntId, $gozens, $allocatedDate1, $allocatedDate2, $activatedDate1, $activatedDate2, $alloated, $qrApproveStatus;

	public function tableName()
	{
		return 'qr_code';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('qrc_code', 'required'),
			array('qrc_ent_type, qrc_ent_id, qrc_allocated_by, qrc_activated_by, qrc_scanned_count,qrc_click_count, qrc_status, qrc_active', 'numerical', 'integerOnly' => true),
			array('qrc_code, qrc_contact_phone', 'length', 'max' => 100),
			array('qrc_location_lat, qrc_location_long', 'length', 'max' => 150),
			array('qrc_location_pic, qrc_contact_name', 'length', 'max' => 255),
			array('qrc_contact_email, qrc_upi_number, qrc_contact_pic', 'length', 'max' => 250),
			array('qrc_otp', 'length', 'max' => 10),
			array('qrc_allocate_date, qrc_location_name, qrc_activated_date', 'safe'),
			array('qrc_id', 'required', 'on' => 'update'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('qrc_id, qrc_code, qrc_ent_type, qrc_ent_id, qrc_allocate_date, qrc_allocated_by, qrc_location_lat, qrc_location_long, qrc_location_name, qrc_location_pic, qrc_contact_name, qrc_contact_phone, qrc_contact_email, qrc_upi_number, qrc_contact_pic, qrc_otp, qrc_activated_by, qrc_activated_date, qrc_scanned_count, qrc_click_count, qrc_status, qrc_active', 'safe', 'on' => 'search'),
			array('qrc_id, qrc_code, qrc_ent_type, qrc_ent_id, qrc_allocate_date, qrc_allocated_by, qrc_location_lat, qrc_location_long, qrc_location_name, qrc_location_pic, qrc_contact_name, qrc_contact_phone, qrc_contact_email, qrc_upi_number, qrc_contact_pic, qrc_otp, qrc_activated_by, qrc_activated_date, qrc_scanned_count,qrc_click_count, qrc_status, qrc_active', 'safe'),
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
			'qrc_id'			 => 'Qrc',
			'qrc_code'			 => 'Qrc Code',
			'qrc_ent_type'		 => 'Qrc Ent Type',
			'qrc_ent_id'		 => 'Qrc Ent',
			'qrc_allocate_date'	 => 'Qrc Allocate Date',
			'qrc_allocated_by'	 => 'Qrc Allocated By',
			'qrc_location_lat'	 => 'Qrc Location Lat',
			'qrc_location_long'	 => 'Qrc Location Long',
			'qrc_location_name'	 => 'Qrc Location Name',
			'qrc_location_pic'	 => 'Qrc Location Pic',
			'qrc_contact_name'	 => 'Qrc Contact Name',
			'qrc_contact_phone'	 => 'Qrc Contact Phone',
			'qrc_contact_email'	 => 'Qrc Contact Email',
			'qrc_upi_number'	 => 'Qrc UPI Number',
			'qrc_contact_pic'	 => 'Qrc Contact Pic',
			'qrc_otp'			 => 'Qrc Otp',
			'qrc_activated_by'	 => 'Qrc Activated By',
			'qrc_activated_date' => 'Qrc Activated Date',
			'qrc_scanned_count'	 => 'Qrc Scanned Count',
			'qrc_click_count'	 => 'Qrc Click Count',
			'qrc_status'		 => '1 => Pending, 2=> Allocated, 3=> Activated',
			'qrc_active'		 => '0=> Inactive, 1=>Active',
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
		$criteria->compare('qrc_id', $this->qrc_id);
		$criteria->compare('qrc_code', $this->qrc_code, true);
		$criteria->compare('qrc_ent_type', $this->qrc_ent_type);
		$criteria->compare('qrc_ent_id', $this->qrc_ent_id);
		$criteria->compare('qrc_allocate_date', $this->qrc_allocate_date, true);
		$criteria->compare('qrc_allocated_by', $this->qrc_allocated_by);
		$criteria->compare('qrc_location_lat', $this->qrc_location_lat, true);
		$criteria->compare('qrc_location_long', $this->qrc_location_long, true);
		$criteria->compare('qrc_location_name', $this->qrc_location_name, true);
		$criteria->compare('qrc_location_pic', $this->qrc_location_pic, true);
		$criteria->compare('qrc_contact_name', $this->qrc_contact_name, true);
		$criteria->compare('qrc_contact_phone', $this->qrc_contact_phone, true);
		$criteria->compare('qrc_upi_number', $this->qrc_upi_number, true);
		$criteria->compare('qrc_contact_pic', $this->qrc_contact_pic, true);
		$criteria->compare('qrc_contact_email', $this->qrc_contact_email, true);
		$criteria->compare('qrc_otp', $this->qrc_otp, true);
		$criteria->compare('qrc_activated_by', $this->qrc_activated_by);
		$criteria->compare('qrc_activated_date', $this->qrc_activated_date, true);
		$criteria->compare('qrc_scanned_count', $this->qrc_scanned_count, true);
		$criteria->compare('qrc_click_count', $this->qrc_click_count, true);
		$criteria->compare('qrc_status', $this->qrc_status);
		$criteria->compare('qrc_active', $this->qrc_active);
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return QrCode the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Show all Qr code
	 * @param type $qrc_status
	 * @return type array 
	 * array contain qrcId,qrcCode
	 */
	public function getList($jsonObj, $limit, $qrc_status = 1)
	{
		$condition = "AND qrc_status IN(1,2)";
		if ($jsonObj !== "")
		{
			$condition .= " AND qrc_code LIKE '%$jsonObj->qry'";
		}

		$sql	 = "SELECT qrc_id FROM `qr_code`  WHERE qrc_active = 1 $condition";
		$resData = DBUtil::queryRow($sql, DBUtil::SDB());
		$qrcId	 = $resData['qrc_id'];
		if ($limit)
		{
			$limit = "ORDER BY qrc_id LIMIT 0,50";
		}
		if ($qrcId != "")
		{
			$sql1		 = "SELECT qrc_id,qrc_code
						FROM `qr_code` 
						WHERE qrc_active = 1 AND qrc_status IN(1,2) AND qrc_id >=$qrcId $limit";
			$listData	 = DBUtil::query($sql1);
		}
		return $listData;
	}

	/**
	 * this function is used for add allocated id and type 
	 * @param type $jsonObj
	 * @return type
	 */
	public static function allocate($jsonObj, $qr, $admin = null)
	{
		$params = [];
		if ($admin == null)
		{
			$admin = UserInfo::getUserId();
		}
		$params	 = ["qrId" => $qr, "entityType" => $jsonObj->entity_type, "entityId" => $jsonObj->entity_id, "status" => 2, "adminId" => $admin];
		$where	 = " AND qrc_id=:qrId";

		$sql	 = "UPDATE qr_code qrc SET  qrc.qrc_ent_type = :entityType , qrc_ent_id =:entityId ,qrc_status = :status,qrc_allocate_date =now(),qrc_allocated_by = :adminId
				WHERE  1 $where";
		$result	 = DBUtil::command($sql)->execute($params);

		return $result;
	}

	public static function showAllocatedTo($qrCodeId)
	{
		$sql = "SELECT qrc_ent_type,qrc_ent_id,qrc_status FROM `qr_code` WHERE qrc_active = 1 AND qrc_id= :qrId";
		return DBUtil::queryRow($sql, DBUtil::SDB(), ["qrId" => $qrCodeId]);
	}

	/**
	 * function is used to send otp
	 * @param type $qr
	 * @param type $contactNumber
	 * 
	 * @param type $name
	 * @return type
	 */
	public static function otpSend($qr, $contactNumber, $name)
	{

		$qrModel					 = QrCode::model()->findByPk($qr);
		$qrModel->qrc_contact_phone	 = $contactNumber;
		$otp						 = $qrModel->qrc_otp;

		if ($otp == "")
		{
			$otp				 = strtolower(rand(1001, 9999));
			$qrModel->qrc_otp	 = $otp;
			$qrModel->save();
		}

		#$ext				 = null;
		$msgCom		 = new smsWrapper();
		$msgStatus	 = $msgCom->sendQrOtp($qr, $contactNumber, $otp, $name);
		return $otp;
	}

	public static function otpVerify($qr, $otp)
	{
		$status		 = false;
		$qrModel	 = QrCode::model()->findByPk($qr);
		$systemotp	 = $qrModel->qrc_otp;

		if ($systemotp == $otp)
		{
			$status = true;
		}

		return $status;
	}

	public function addActivation($arrData, $adminId = null, $qrNumber = null)
	{
		if ($adminId == null)
		{
			$userInfo	 = UserInfo::getInstance();
			$adminId	 = $userInfo->userId;
		}
		$qrModel = QrCode::model()->findByPk($arrData['qr_id']);
		$jsonObj = json_decode(json_encode($arrData));

		$email	 = $jsonObj->qr_email;
		$phone	 = $jsonObj->qr_contact_number;

		/* $userid	 = Users::getUserIdByUserInfo($phone, $type	 = 2);
		  if (!$userid || $userid <= 0 || $userid == '')
		  {
		  $userid	 = Users::getUserIdByUserInfo($email, $type	 = 1);
		  } */
		$phone = Filter::processPhoneNumber($jsonObj->qr_contact_number);
		if (trim($email) != '')
		{
			$emailRecord = ContactEmail::getByEmail($email, '', '', '', 'limit 1');
		}

		if (trim($phone) != '')
		{
			$value		 = Filter::parsePhoneNumber($phone, $code, $number);
			$phoneRecord = ContactPhone::getByPhone($code . $number, '', '', '', 'limit 1');
		}

		$contactId = Contact::getIdByRecord($emailRecord, $phoneRecord);
		if ($contactId != "")
		{
			$userid = ContactProfile::getUserId($contactId);
		}

		if ($userid == "" || $contactId == "")
		{
			throw new Exception("No user Found");
		}
		if (!isset($arrData['qr_agent_id']) || $arrData['qr_agent_id'] == 0)
		{
			$arrData['qr_agent_id'] = Agents::createQrAgent($jsonObj, $userid);
		}
		$qrModel->qrc_agent_id		 = $arrData['qr_agent_id'];
		$qrModel->qrc_location_lat	 = $arrData['qr_loc_lat'];
		$qrModel->qrc_location_long	 = $arrData['qr_loc_long'];
		$qrModel->qrc_location_name	 = $arrData['qr_loc_name'];
		$qrModel->qrc_contact_name	 = $arrData['qr_contact_name'];
		$qrModel->qrc_contact_phone	 = $arrData['qr_contact_number'];

		if (isset($arrData['qr_email']) && trim($arrData['qr_email']) != '')
		{
			$qrModel->qrc_contact_email = $arrData['qr_email'];
		}
		if (isset($arrData['qr_contact_upi']) && trim($arrData['qr_contact_upi']) != '')
		{
			$qrModel->qrc_upi_number = $arrData['qr_contact_upi'];
		}

		$qrModel->qrc_activated_date = date("Y-m-d H:i:s");
		$qrModel->qrc_status		 = 3;
		if ($adminId > 0)
		{
			$qrModel->qrc_activated_by = $adminId;
		}

		$qrModel->qrc_allocate_date	 = date("Y-m-d H:i:s");
		$qrModel->qrc_allocated_by	 = $adminId;
		$qrModel->save();
		self::generateFileExistingQR($userid, $qrNumber, $contactId);
		return true;
	}

	/**
	 * create that function for generate file of existing QR after customer activation 
	 * @param type $userId
	 * @param type $qrCode
	 * @param type $contactId
	 * @throws Exception
	 */
	public static function generateFileExistingQR($userId, $qrCode, $contactId)
	{
		$userModel	 = Users::model()->findByPk($userId);
		$link		 = "https://gozo.cab/c/";
		$qrLink		 = $link . $qrCode;
		$dirFileName = qrCode::generateCode($qrLink, $qrCode, $contactId);

		if ($dirFileName != '')
		{
			$userModel->usr_qr_code_path = $dirFileName;
			if (!$userModel->save())
			{
				$returnSet->setErrors($userModel->getErrors(), 1);
				throw new Exception("Error For User Id: {$userId}");
			}
		}
		else
		{
			throw new Exception("Couldn't create QR link, Error For User Id: {$userId}");
		}
	}

	/**
	 * function used for save image 
	 * @param type $uploadedFile
	 * @param type $qrId
	 * @param type $type
	 * @return string
	 * @throws Exception
	 */
	public function saveImage($uploadedFile, $qrId, $type)
	{
		try
		{
			$path		 = "";
			$DS			 = '/';
			#$DS			 = DIRECTORY_SEPARATOR;
			$image		 = $uploadedFile->getName();
			$imagetmp	 = $uploadedFile->getTempName();

			$qrmodel = QrCode::model()->findByPk($qrId);
			$qrCode	 = $qrmodel->qrc_code;

			if ($image != '')
			{

				$image			 = $qrCode . "-" . $type . "-" . $image;
				$file_name		 = basename($image);
				$mainRoot		 = Yii::app()->basePath . $DS;
				$serverId		 = Config::getServerID();
				$dirFinal		 = 'QR' . $DS . $qrCode . $DS . $type;
				$file_path		 = $mainRoot . $DS . doc . $DS . $serverId . $DS . $dirFinal;
				$f				 = $file_path;
				$file_save_path	 = $file_path . $DS . $file_name;

				if (!is_dir($f))
				{
					mkdir($f, 0755, true);
				}
				if (Vehicles::model()->img_resize($imagetmp, 600, $f, $file_name))
				{
					$path	 = substr($file_save_path, strlen($mainRoot));
					$result	 = $path;
				}
			}
		}
		catch (Exception $e)
		{
			throw $e;
		}

		return $result;
	}

	/**
	 * save location and contact picture of QR code in database and call image upload function
	 * @param type $qrId
	 * @param type $fileName
	 * @param type $type
	 * @return type
	 */
	public static function saveQrDoc($qrId, $fileName, $type)
	{
		if ($type == "location")
		{
			$columm = "qrc_location_pic";
		}
		if ($type == "contact")
		{
			$columm = "qrc_contact_pic";
		}
		$res = QrCode::model()->saveImage($fileName, $qrId, $type);

		if ($res)
		{
			$where	 = "AND qrc.qrc_id=$qrId";
			$sql	 = "UPDATE qr_code qrc SET $columm= '" . $res . "' WHERE  1 $where";

			$result = DBUtil::command($sql)->execute();
		}
		return $result;
	}

	public function getQrList($command = false)
	{
		$cond = "";
		if ($this->qrCode != '')
		{
			$cond .= " AND qrc_code LIKE '" . "%$this->qrCode%'";
		}
		if ($this->qrStatus != '')
		{
			$cond .= " AND qrc_status = " . $this->qrStatus;
		}
		if ($this->qrApproveStatus != '')
		{
			$cond .= " AND qrc_approval_status = " . $this->qrApproveStatus;
		}
		if ($this->gozens != '')
		{
			$cond .= " AND qrc_activated_by = " . $this->gozens;
		}
		if ($this->allocatedType > 0)
		{
			switch ($this->allocatedType)
			{
				case 1:
					$cond	 .= " AND qrc_ent_type=$this->allocatedType AND qrc_ent_id=$this->custId ";
					break;
				case 2:
					$cond	 .= " AND qrc_ent_type=$this->allocatedType AND qrc_ent_id=$this->vendId ";
					break;
				case 3:
					$cond	 .= " AND qrc_ent_type=$this->allocatedType AND qrc_ent_id=$this->drvId ";
					break;
				case 4:
					$cond	 .= " AND qrc_ent_type=$this->allocatedType AND qrc_ent_id=$this->adminId ";
					break;
				case 5:
					$cond	 .= " AND qrc_ent_type=$this->allocatedType AND qrc_ent_id=$this->agntId ";
					break;

				default:
					break;
			}
		}
		if ($this->allocatedDate1 != '' && $this->allocatedDate2 != '')
		{
			$cond .= " AND qrc_allocate_date BETWEEN '" . $this->allocatedDate1 . " 00:00:00' AND '" . $this->allocatedDate2 . " 23:59:59' ";
		}
		if ($this->activatedDate1 != '' && $this->activatedDate2 != '')
		{
			$cond .= " AND qrc_activated_date BETWEEN '" . $this->activatedDate1 . " 00:00:00' AND '" . $this->activatedDate2 . " 23:59:59' ";
		}
		if ($this->qrc_agent_id != '')
		{
			$cond .= " AND qrc_agent_id = " . $this->qrc_agent_id;
		}

		$sqlSelect		 = "SELECT qrc.*,agt.agt_id,CONCAT( IFNULL(agt_company,''), IFNULL(IF(agt_company IS NOT NULL AND agt_company <> '',CONCAT(\" (\",agt_fname,' ',agt_lname,\")\"),CONCAT(agt_fname,' ',agt_lname)),''), IF(agt_type = 0,'-TRAVEL',IF(agt_type=1,'-CORPORATE','-RESELLER')) ) companyName,
								COUNT(DISTINCT bkg.bkg_id) bkgCnt, COUNT(DISTINCT btmp.bkg_id) bkgTempCnt,
								CASE
					WHEN qrc_approval_status = 0 THEN 'Pending'
					WHEN qrc_approval_status = 1 THEN 'Approved'
					WHEN qrc_approval_status = 2 THEN 'Rejected'
					END AS approvedStatus,
					CASE
					WHEN qrc_status = 1 THEN 'Pending'
					WHEN qrc_status = 2 THEN 'Allocated'
					WHEN qrc_status = 3 THEN 'Activated'
					END AS qrStatus, DATE_FORMAT(qrc_activated_date, '%e/%m/%Y %r') as activatedDate,DATE_FORMAT(qrc_approved_date, '%e/%m/%Y %r') as approvedDate,
					DATE_FORMAT(qrc_allocate_date, '%e/%m/%Y %r') as allocatedDate
					FROM qr_code qrc ";
		$sqlSelectCount	 = "SELECT count(*) FROM qr_code qrc WHERE 1 ";

		$sqlJoin = "LEFT JOIN booking bkg ON qrc.qrc_id = bkg.bkg_qr_id AND bkg.bkg_qr_id > 0 AND bkg.bkg_status IN (2,3,5,6,7,15)
					LEFT JOIN booking_temp btmp ON qrc.qrc_id = btmp.bkg_qr_id AND btmp.bkg_qr_id > 0
					LEFT JOIN agents agt ON agt.agt_id = qrc.qrc_agent_id
					WHERE 1 ";
		$groupBy = " GROUP BY qrc.qrc_id";

		$sql		 = $sqlSelect . $sqlJoin . $cond . $groupBy;
		$sqlCount	 = $sqlSelectCount . $cond;
		if ($command == false)
		{
			$pageSize		 = 100;
			$count			 = DBUtil::command($sqlCount, DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'db'			 => DBUtil::SDB(),
				'totalItemCount' => $count,
				'sort'			 =>
				['attributes'	 =>
					['qrc_ent_id', 'qrc_status', 'qrc_allocate_date', 'qrc_activated_date', 'qrc_activated_by', 'qrc_scanned_count', 'qrc_allocated_by', 'qrc_approval_status'],
					'defaultOrder'	 => 'qrc_scanned_count DESC',
				],
				'keyField'		 => 'qrc_id',
				'pagination'	 => ['pageSize' => $pageSize],
			]);
		}
		else
		{
			return DBUtil::queryAll($sql, DBUtil::SDB());
		}

		return $dataprovider;
	}

	public static function getQRCode($id)
	{
		$sql = "SELECT qrc_id,qrc_code FROM `qr_code` WHERE qrc_active = 1 AND qrc_id=:qrId";
		return DBUtil::queryRow($sql, DBUtil::SDB(), ["qrId" => $id]);
	}

	public static function getAgentId($code)
	{
		$sql = "SELECT
					qrc_id,
					qrc_code,
					qrc_agent_id,
					qrc_scanned_count
				FROM `qr_code` 
				WHERE 1
					AND qrc_active = 1
					AND qrc_code=:code";
		return DBUtil::queryRow($sql, DBUtil::SDB(), ['code' => $code]);
	}

	public static function updateScannedCount($qrcId)
	{
		$sql = "UPDATE qr_code SET qrc_scanned_count = qrc_scanned_count + 1 WHERE qrc_id =:qrcId";
		return DBUtil::execute($sql, ['qrcId' => $qrcId]);
	}

	public static function uploadAllToS3($limit = 1000)
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
			$locationPath	 = " (qrc_location_s3_data IS NULL AND qrc_location_pic LIKE '/doc/{$serverId}/QR/%') ";
			$contactPath	 = " (qrc_contact_s3_data IS NULL AND qrc_contact_pic LIKE '/doc/{$serverId}/QR/%') ";

			$sql = "SELECT qrc_id FROM qr_code
					WHERE qrc_active =1 AND qrc_status =3 AND qrc_activated_date IS NOT NULL AND ({$locationPath} OR {$contactPath})
					ORDER BY qrc_id DESC LIMIT 0, $limit1";

			$res = DBUtil::query($sql);
			if ($res->getRowCount() == 0)
			{
				break;
			}
			foreach ($res as $row)
			{
				/** @var QrCode $qrModel */
				$qrModel = QrCode::model()->findByPk($row["qrc_id"]);

				$qrModel->uploadLocationFileToSpace();
				Logger::writeToConsole($qrModel->qrc_location_s3_data);

				$qrModel->uploadContactFileToSpace();
				Logger::writeToConsole($docModel->qrc_contact_s3_data);
			}
			$limit -= $limit1;
			Logger::flush();
		}
	}

	public function uploadContactFileToSpace($removeLocal = true)
	{
		$spaceFile = null;
		try
		{
			$qrModel = $this;
			$path	 = $this->getContactPath();
			if (!file_exists($path) || $qrModel->qrc_contact_pic == '')
			{
				if ($qrModel->qrc_contact_s3_data == '')
				{
					$qrModel->qrc_contact_s3_data = "{}";
					$qrModel->save();
				}
				return null;
			}

			$spaceFile = $qrModel->uploadToSpace($path, $this->getSpacePath($path), $removeLocal);

			$qrModel->qrc_contact_s3_data = $spaceFile->toJSON();
			$qrModel->save();
		}
		catch (Exception $exc)
		{
			ReturnSet::setException($exc);
		}
		return $spaceFile;
	}

	public function uploadLocationFileToSpace($removeLocal = true)
	{
		$spaceFile = null;
		try
		{
			$qrModel = $this;
			$path	 = $this->getLocationPath();
			if (!file_exists($path) || $qrModel->qrc_location_pic == '')
			{
				if ($qrModel->qrc_location_s3_data == '')
				{
					$qrModel->qrc_location_s3_data = "{}";
					$qrModel->save();
				}
				return null;
			}

			$spaceFile = $qrModel->uploadToSpace($path, $this->getSpacePath($path), $removeLocal);

			$qrModel->qrc_location_s3_data = $spaceFile->toJSON();
			$qrModel->save();
		}
		catch (Exception $exc)
		{
			ReturnSet::setException($exc);
		}
		return $spaceFile;
	}

	public function getBaseDocPath()
	{
		return Yii::app()->basePath;
	}

	public function getContactPath()
	{
		$filePath	 = $this->qrc_contact_pic;
		$filePath	 = $this->getBaseDocPath() . $filePath;
		return $filePath;
	}

	public function getLocationPath()
	{
		$filePath	 = $this->qrc_location_pic;
		$filePath	 = $this->getBaseDocPath() . $filePath;
		return $filePath;
	}

	public function getSpacePath($localPath)
	{
		$fileName	 = basename($localPath);
		$id			 = $this->qrc_id;
		$date		 = $this->qrc_activated_date;
		$dateString	 = DateTimeFormat::SQLDateTimeToDateTime($date)->format("Y/m/d");
		$path		 = "/{$dateString}/{$id}_{$fileName}";
		return $path;
	}

	/**
	 * @return Stub\common\SpaceFile
	 */
	public function uploadToSpace($localFile, $spaceFile, $removeLocal = true)
	{
		$objSpaceFile = Storage::uploadFile(Storage::getQrSpace(), $spaceFile, $localFile, $removeLocal);
		return $objSpaceFile;
	}

	/**
	 * 
	 * @param type $qrId, $qrType
	 * @return Doc path link
	 */
	public static function getDocPathById($qrId, $qrType = '')
	{
		$path = '/images/no-image.png';

		$qrModel = QrCode::model()->findByPk($qrId);
		if (!$qrModel)
		{
			goto end;
		}
		$fieldName = ($qrType == 1) ? "qrc_location_s3_data" : "qrc_contact_s3_data";
		if ($qrType != '')
		{
			$s3Data = $qrModel->$fieldName;
			if ($qrType == 1)
			{
				$imgPath = $qrModel->getLocationPath();
			}

			if ($qrType == 2)
			{
				$imgPath = $qrModel->getContactPath();
			}

			if (file_exists($imgPath) && $imgPath != $qrModel->getBaseDocPath())
			{
				if (substr_count($imgPath, PUBLIC_PATH) > 0)
				{
					$path = substr($imgPath, strlen(PUBLIC_PATH));
				}
				else
				{
					$path = AttachmentProcessing::publish($imgPath);
				}
			}
			else if ($s3Data != '{}' && $s3Data != '')
			{
				$spaceFile	 = \Stub\common\SpaceFile::populate($s3Data);
				$path		 = $spaceFile->getURL();
				if ($spaceFile->isURLCreated())
				{
					$qrModel->$fieldName = $spaceFile->toJSON();
					$qrModel->save();
				}
			}
		}
		end:
		return $path;
	}

	public function getLeadListByQr($qrId)
	{
		if ($qrId != "")
		{
			$where	 .= " AND bkg_qr_id =:qrId ";
			$params	 = ['qrId' => $qrId];
		}
		$sql			 = "Select
			temp.*,
			bkg_id,
			bkg_vehicle_type_id,
			bkg_booking_type,
			bkg_locked_by,
			bkg_log_email,
			bkg_log_phone,
			bkg_lock_timeout,
			bkg_follow_up_status,
			bkg_follow_up_on,
			bkg_follow_up_by,
			bkg_contact_no,
			bkg_user_email,
			bkg_booking_id,
			bkg_pickup_date,
			bkg_create_date,
			scc_VehicleCategory.vct_label AS vct_label,
			bkgFromCity.cty_name as from_city_name,
			bkgToCity.cty_name as to_city_name,
			AssignedTo.adm_fname as AssignedToadm_fname,
			FollowUpBy.adm_fname as FollowUpByadm_fname,
			FollowUpBy.adm_lname as FollowUpByadm_lname,
			sc.scc_label
			FROM qr_code
			INNER JOIN booking_temp	ON bkg_qr_id = qrc_id
			LEFT JOIN
			(
				SELECT 
				bkg_id AS bkgIds,
				booking_user.bkg_user_id AS beneficiaryId,
				qr_code.qrc_ent_id AS benefactorId,
				if(
					btr.bkg_platform IN (1,3)
					AND bkg_confirm_user_type=1 
					AND bkg_create_user_type=1
					AND (bkg_create_user_id=bkg_confirm_user_id)
					AND bkg_confirm_user_id IS NOT NULL 
					AND bkg_create_user_id IS NOT NULL
					AND users.user_id <> booking_user.bkg_user_id
					AND bkg_agent_id IS NULL,1,0
				) AS isReferBooking
				FROM booking 
				INNER JOIN booking_trail as btr ON btr.btr_bkg_id=booking.bkg_id AND (booking.bkg_qr_id >0 AND booking.bkg_qr_id IS NOT NULL) 
				INNER JOIN booking_user ON booking_user.bui_bkg_id=booking.bkg_id
				INNER JOIN qr_code  ON qr_code.qrc_id=booking.bkg_qr_id AND qr_code.qrc_active=1 AND qr_code.qrc_status=3
				INNER JOIN users ON users.user_id=qr_code.qrc_ent_id AND qr_code.qrc_ent_type=1
				WHERE 1  AND bkg_active=1
			) temp ON temp.bkgIds=booking_temp.bkg_ref_booking_id
			LEFT JOIN cities bkgFromCity ON (bkg_from_city_id = bkgFromCity.cty_id)
			LEFT JOIN cities bkgToCity ON (bkg_to_city_id = bkgToCity.cty_id)   
			LEFT JOIN admins AssignedTo	ON (bkg_assigned_to = AssignedTo.adm_id)
			LEFT JOIN svc_class_vhc_cat bkgSvcClassVhcCat ON (bkg_vehicle_type_id = bkgSvcClassVhcCat.scv_id)
			LEFT JOIN service_class sc ON (sc.scc_id = bkgSvcClassVhcCat.scv_scc_id)
			LEFT JOIN vehicle_category scc_VehicleCategory ON (bkgSvcClassVhcCat.scv_vct_id = scc_VehicleCategory.vct_id)
			LEFT JOIN admins FollowUpBy ON (bkg_follow_up_by = FollowUpBy.adm_id)
            WHERE 1 $where";
		$sqlCount		 = " SELECT COUNT(*) FROM qr_code INNER JOIN booking_temp ON bkg_qr_id = qrc_id WHERE 1 $where ";
		$count			 = DBUtil::queryScalar($sqlCount, DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'params'		 => $params,
			'db'			 => DBUtil::SDB(),
			'sort'			 => [
				'attributes'	 => ['bkg_id', 'bkgIds', 'bkg_pickup_date', 'isReferBooking', 'bkg_create_date'],
				'defaultOrder'	 => 'bkg_pickup_date ASC,isReferBooking DESC'
			],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function setApprove($btnType, $qrId)
	{
		$transaction = DBUtil::beginTransaction();
		$userInfo	 = UserInfo::getInstance();
		$returnSet	 = new ReturnSet();
		$objModelQr	 = QrCode::model()->findByPK($qrId);
		try
		{
			$objModelQr->qrc_approval_status = $btnType == 'approve' ? 1 : 2;
			$objModelQr->qrc_approved_by	 = $userInfo->userId;
			$objModelQr->qrc_approved_date	 = date("Y-m-d H:i:s");

			$res = $objModelQr->save();
			if (!$res)
			{
				$returnSet->setErrors($this->getErrors(), 1);
				throw new CHttpException("Failed to approved QR", 1);
			}
			DBUtil::commitTransaction($transaction);
			$returnSet->setStatus(true);
			$returnSet->setMessage("This QR had been" . ' ' . $btnType . "d");
			$returnSet->setData(["id" => $message]);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			if ($returnSet->getErrorCode() == 0)
			{
				$returnSet->setErrorCode($ex->getCode());
				$returnSet->addError($ex->getMessage());
			}
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	/**
	 * @param type $userId
	 * @throws Exception
	 */
	public static function processData($userId)
	{
		$returnSet = new ReturnSet();
		try
		{
			$userModel = Users::model()->findByPk($userId);
			if (!$userModel)
			{
				return false;
			}
			$contactId = Users::getContactByUserId($userId);
			if ($contactId == 0)
			{
				throw new Exception("No Contact Found, Error For User Id: {$userId}");
			}
			$uniqueQrCode = QrCode::getCode($userId);
			if ($uniqueQrCode == null || $uniqueQrCode == "")
			{
				$uniqueQrCode = self::saveCodeById($userId, $contactId);
			}

			if ($userModel->usr_qr_code_path == '' || $userModel->usr_qr_code_path == NULL)
			{
				$qrLink		 = "https://gozo.cab/c/" . $uniqueQrCode;
				$dirFileName = self::generateCode($qrLink, $uniqueQrCode, $contactId);
				if ($dirFileName != '')
				{
					$userModel->usr_qr_code_path = $dirFileName;
					if (!$userModel->save())
					{
						Logger::writeToConsole(json_encode($userModel->getErrors()));
						$returnSet->setErrors($userModel->getErrors(), 1);
						throw new Exception("Error For User Id: {$userId}");
					}
					$returnSet->setStatus(true);
				}
				else
				{
					throw new Exception("Couldn't create QR Code, Error For User Id: {$userId}");
				}
			}
		}
		catch (Exception $ex)
		{
			Logger::writeToConsole($ex->getMessage());
			$returnSet->setStatus(false);
			$returnSet->setMessage($ex->getMessage());
		}

		return $returnSet;
	}

	/**
	 * @param type $userId
	 * @param type $contactId
	 * @return type
	 * @throws CHttpException
	 */
	public static function saveCodeById($userId, $contactId)
	{
		$qrCode				 = null;
		$qrModel			 = new QrCode();
		$qrModel->qrc_code	 = 'Qrcode';
		if (!$qrModel->save())
		{
			throw new CHttpException(1, json_encode($qrModel->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
		$arrcontact	 = Contact::model()->getContactDetails($contactId);
		$userModel	 = Users::model()->findByPk($userId);
		if ($arrcontact['ctt_first_name'] == '')
		{
			$arrcontact['ctt_first_name'] = $userModel->usr_name;
		}
		if ($arrcontact['ctt_last_name'] == '')
		{
			$arrcontact['ctt_last_name'] = $userModel->usr_lname;
		}
		if ($arrcontact['phn_phone_no'] == '')
		{
			$arrcontact['phn_phone_no'] = $userModel->usr_mobile;
		}
		if ($arrcontact['eml_email_address'] == '')
		{
			$arrcontact['eml_email_address'] = $userModel->usr_email;
		}
		$id = $qrModel->qrc_id;
		if ($id > 0 && $userId > 0)
		{
			$qrModel					 = QrCode::model()->findByPk($id);
			$unique						 = "CX";
			$date						 = date("ym");
			$string						 = str_pad($id, 5, "0", STR_PAD_LEFT);
			$uniqueQrCode				 = $unique . $date . $string;
			$qrModel->qrc_code			 = $uniqueQrCode;
			$qrModel->qrc_ent_id		 = $userId;
			$qrModel->qrc_ent_type		 = 1;
			$qrModel->qrc_status		 = 3;
			$qrModel->qrc_allocate_date	 = date("Y-m-d h:i:s");
			$qrModel->qrc_allocated_by	 = 77;
			$qrModel->qrc_activated_date = date("Y-m-d h:i:s");
			$qrModel->qrc_activated_by	 = 77;
			$qrModel->qrc_contact_name	 = trim($arrcontact['ctt_first_name']) . ' ' . trim($arrcontact['ctt_last_name']);
			$qrModel->qrc_contact_phone	 = trim($arrcontact['phn_phone_no']);
			$qrModel->qrc_contact_email	 = trim($arrcontact['eml_email_address']);
			if (!$qrModel->save())
			{
				Logger::trace("QRCODE Model Failed Msg : " . $qrModel->getErrors());
			}
			$qrCode = $uniqueQrCode;
		}
		return $qrCode;
	}

	/**
	 * 
	 * @param type $qrLink
	 * @param type $uniqueQrCode
	 * @param type $contactId
	 * @return string
	 */
	public static function generateCode($qrLink, $uniqueQrCode, $contactId)
	{
		try
		{
			$result		 = QrCodeBuilder::getQrCode($qrLink);
			$dirFinal	 = Filter::generateQRCodeFilePath(Config::getServerID(), 'qrcode', $contactId);
			$targetPath	 = Yii::app()->basePath . $dirFinal;
			$fileName	 = $uniqueQrCode . ".png";
			$dirName	 = $dirFinal . $fileName;
			if (!is_dir($targetPath))
			{
				mkdir($targetPath, 0777, true);
			}
			//$result->create($targetPath . $fileName);
			$result->saveToFile($targetPath . $fileName);
			return $dirName;
		}
		catch (Exception $ex)
		{
			echo "\r\nErrorGenerateCode: " . $ex->getMessage();
			return false;
		}
	}

	/**
	 * 
	 * @param type $qrCode
	 */
	public static function urlShorten($qrCode, $timeOut = 2000)
	{

//		$url		 = "https://www.gozocabs.com?sid={$qrCode}&loc=1";
//		$encodeUrl	 = urlencode($url);
//		$parameter	 = "&keyword=" . $qrCode;
//		$completeUrl = $encodeUrl . $parameter;
//		$slink		 = Filter::shortUrl($completeUrl, $timeOut);
		$url = "https://gozo.cab/c/" . $qrCode;
		return $url;
	}

	/**
	 * 
	 * @param type $qrCode
	 */
	public static function qrUrlShorten($qrCode, $timeOut = 2000)
	{
		$url						 = "https://www.gozocabs.com?sid={$qrCode}&loc=1";
		$encodeUrl					 = urlencode($url);
		$parameter					 = "&keyword=" . $qrCode;
		$completeUrl				 = $encodeUrl . $parameter;
		$apiUrl						 = "http://c.gozo.cab/yourls-api.php?signature=e881178cb0&action=shorturl&format=json&url=";
		$apiUrl						 .= $completeUrl;
		$ch							 = curl_init();
		curl_setopt($ch, CURLOPT_URL, $apiUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeOut);
		$response					 = curl_exec($ch);
		$httpcode					 = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$responseArr				 = json_decode($response, true);
		$responseArr['errorCode']	 = $httpcode;
		curl_close($ch);
		return $responseArr;
	}

	/**
	 * This function is used to get  QR Code by particular user Id for entity type user
	 * @param type $userid
	 * return string
	 */
	public static function getCode($userid)
	{
		$sql = "SELECT qrc_code FROM qr_code WHERE 1 AND qrc_ent_type=1 AND qrc_ent_id=:userid AND qrc_active=1 AND qrc_status=3";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), ['userid' => $userid]);
	}

	/**
	 * This function is used update the click count when your click the link
	 * @param type $qrcId
	 * return int
	 */
	public static function updateClickCount($qrcId)
	{
		$sql = "UPDATE qr_code SET qrc_click_count = qrc_click_count + 1 WHERE qrc_id =:qrcId";
		return DBUtil::execute($sql, ['qrcId' => $qrcId]);
	}

}

<?php

/**
 * This is the model class for table "booking_pay_docs".
 *
 * The followings are the available columns in table 'booking_pay_docs':
 * @property integer $bpay_id
 * @property integer $bpay_type
 * @property integer $bpay_app_type
 * @property integer $bpay_bkg_id
 * @property string $bpay_device_id
 * @property integer $bpay_image_no
 * @property string $bpay_image
 * @property string $bpay_image_name
 * @property string $bpay_checksum
 * @property integer $bpay_approved
 * @property integer $bpay_approved_by
 * @property string $bpay_approved_date
 * @property integer $bpay_status
 * @property string $bpay_date
 * @property string $bpay_device_info
 * The followings are the available model relations:
 * @property Booking $bpayBkg
 */
class BookingPayDocs extends CActiveRecord
{

	public $img1, $img2, $img3;
	public $docTypeArr = ['1'		 => 'State tax', '2'		 => 'Toll tax', '3'		 => 'Parking charge', '4'		 => 'Others',
		'5'		 => 'Duty Slip', '101'	 => 'StartOdometer',
		'104'	 => 'EndOdometer', '107'	 => 'Selfie', '108'	 => 'Sanitization Kit',
		'8'		 => 'Car FrontImage', '9'		 => 'Car BackImage', '10'	 => 'Car LeftImage', '11'	 => 'Car RightImage'];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_pay_docs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bpay_date', 'required'),
			array('bpay_type, bpay_app_type, bpay_bkg_id, bpay_image_no, bpay_approved, bpay_approved_by, bpay_status', 'numerical', 'integerOnly' => true),
			array('bpay_device_id, bpay_image_name', 'length', 'max' => 100),
			array('bpay_image, bpay_checksum', 'length', 'max' => 255),
			array('bpay_approved_date', 'safe'),
			['bpay_checksum', 'validateCheckSum', 'on' => 'checkSumValidity'],
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bpay_id, bpay_type, bpay_app_type, bpay_bkg_id, bpay_device_id, bpay_image_no, bpay_image, bpay_image_name, bpay_checksum, bpay_approved, bpay_approved_by, bpay_approved_date, bpay_status, bpay_date,bpay_machine_output', 'safe', 'on' => 'search'),
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
			'bpayBkg' => array(self::BELONGS_TO, 'Booking', 'bpay_bkg_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'bpay_id'			 => 'Bpay',
			'bpay_type'			 => ' 1=>\'State tax\',2=>\'Toll tax\',3=>\'Parking charge\',4=>\'Others\',5=>\'Duty Slip\',105=>\'Start Odometer\',106=>\'End Odometer\'',
			'bpay_app_type'		 => '2=>Vendor,5=>Driver',
			'bpay_bkg_id'		 => 'Bpay Bkg',
			'bpay_device_id'	 => 'Bpay Device',
			'bpay_image_no'		 => 'Bpay Image No',
			'bpay_image'		 => 'Bpay Image',
			'bpay_image_name'	 => 'Bpay Image Name',
			'bpay_checksum'		 => 'Bpay Checksum',
			'bpay_approved'		 => 'Bpay Approved',
			'bpay_approved_by'	 => 'Bpay Approved By',
			'bpay_approved_date' => 'Bpay Approved Date',
			'bpay_status'		 => 'Bpay Status',
			'bpay_date'			 => 'Bpay Date',
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

		$criteria->compare('bpay_id', $this->bpay_id);
		$criteria->compare('bpay_type', $this->bpay_type);
		$criteria->compare('bpay_app_type', $this->bpay_app_type);
		$criteria->compare('bpay_bkg_id', $this->bpay_bkg_id);
		$criteria->compare('bpay_device_id', $this->bpay_device_id, true);
		$criteria->compare('bpay_image_no', $this->bpay_image_no);
		$criteria->compare('bpay_image', $this->bpay_image, true);
		$criteria->compare('bpay_image_name', $this->bpay_image_name, true);
		$criteria->compare('bpay_checksum', $this->bpay_checksum, true);
		$criteria->compare('bpay_approved', $this->bpay_approved);
		$criteria->compare('bpay_approved_by', $this->bpay_approved_by);
		$criteria->compare('bpay_approved_date', $this->bpay_approved_date, true);
		$criteria->compare('bpay_status', $this->bpay_status);
		$criteria->compare('bpay_date', $this->bpay_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingPayDocs the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function saveVoucherDoc($params)
	{
		$success = false;
		$errors	 = [];
		try
		{
			$result1 = CActiveForm::validate($this);
			if ($result1 == [])
			{

				$this->bpay_date		 = new CDbExpression('NOW()');
				$this->bpay_bkg_id		 = $params['bkg_id'];
				$this->bpay_type		 = 2;
				//$this->bpay_image		 = $params['voucher_path'];
				$this->bpay_device_id	 = $params['device_id'];
				$this->bpay_status		 = 1;
				if ($this->save())
				{
					$success = true;
				}
				else
				{
					
				}
			}
			else
			{
				
			}
		}
		catch (Exception $ex)
		{
			
		}
		return ['success' => $success, 'errors' => $errors];
	}

	public function validateCheckSum()
	{
		$sql	 = "SELECT COUNT(*) as cnt FROM booking_pay_docs
                WHERE bpay_checksum='$this->bpay_checksum'  AND bpay_bkg_id = $this->bpay_bkg_id AND bpay_type IN(101,104,107,108)AND bpay_status =1";
		$count	 = DBUtil::command($sql)->queryScalar();

		if ($count > 0)
		{
			$this->addError('bpay_id', "Check Sum Already Exist");
			return false;
		}
		return true;
	}

	public function addVerificationDoc()
	{
		
	}

	public function getVoucherListType()
	{
		$voucherlist = [
			1	 => 'State tax',
			2	 => 'Toll tax',
			3	 => 'Parking charge',
			4	 => 'Others',
			5	 => 'Duty Slip',
			105  => 'Start Odometer',
			106  => 'End Odometer',
			8 => 'Car Front Image',
			9 => 'Car Back Image'
		];
		asort($voucherlist);
		return $voucherlist;
	}

	public function getTypeByVoucherId($voucherId)
	{
		$list = $this->getVoucherListType();
		return $list[$voucherId];
	}

	public function getVendorDocList($bkgId)
	{
		$sql = "SELECT
    (CASE WHEN bpay_type = 1 THEN 'Toll tax' WHEN bpay_type = 2 THEN 'State tax' WHEN bpay_type = 3 THEN 'Parking charge' ELSE 'Others'
	END)AS vouchertype,
	bpay_bkg_id,
	bpay_image,
	bpay_date
	FROM
		`booking_pay_docs`
	WHERE
		booking_pay_docs.bpay_bkg_id = $bkgId
	AND booking_pay_docs.bpay_status = 1
	ORDER BY
    `booking_pay_docs`.`bpay_id` ASC";
		return DBUtil::queryAll($sql);
	}

	public function getDutySlipBybookingId($bkgid, $approved = null)
	{
		if ($bkgid == null || $bkgid == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$params	 = array("bkgid" => $bkgid);
		$sql	 = "SELECT * FROM booking_pay_docs WHERE bpay_bkg_id=:bkgid AND bpay_status = 1 AND bpay_type NOT IN (8,9,10,11)";
		if ($approved != null && in_array($approved, [0, 1, 2]))
		{
			$params['bpay_approved'] = $approved;
			$sql					 .= " AND bpay_approved = :bpay_approved";
		}
		$result = DBUtil::queryAll($sql, DBUtil::SDB(), $params);
		return $result;
	}

	public function savePayDocs()
	{
		if ($this->bpay_checksum != "")
		{
			$this->scenario = 'checkSumValidity';
		}
		if (!$this->save())
		{
			throw new Exception(json_encode($this->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
		return true;
	}

	public function getRow($fileChecksum, $appType = '')
	{
		$sql = "SELECT bpay_id,bpay_bkg_id,bpay_type,bpay_s3_data FROM booking_pay_docs WHERE bpay_checksum = '$fileChecksum' AND bpay_status = 2 ORDER BY bpay_id DESC LIMIT 1";
		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	public function getVoucherByBkgId($bkgId, $eventId)
	{
		$sql = "SELECT  booking_pay_docs.bpay_image, booking_pay_docs.bpay_id 
                FROM    booking_pay_docs 
			    WHERE   booking_pay_docs.bpay_bkg_id = $bkgId 
			    AND     booking_pay_docs.bpay_type   = $eventId";

		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	public function getBpayId($bkg_id, $appType, $appId, $checkSum)
	{
		$sql = "SELECT booking_pay_docs.bpay_id FROM booking_pay_docs 
				INNER  JOIN booking_track_log ON booking_track_log.btl_bkg_id = booking_pay_docs.bpay_bkg_id 
				WHERE  booking_pay_docs.bpay_bkg_id     = $bkg_id 
				AND    booking_pay_docs.bpay_app_type   = $appType 
				AND    booking_track_log.btl_appsync_id = $appId 
				AND    booking_pay_docs.bpay_checksum   = booking_track_log.btl_doc_checksum 
				AND    booking_pay_docs.bpay_checksum   ='$checkSum'";

		$data = DBUtil::queryRow($sql, DBUtil::SDB());
		return $data;
	}

	public function removeVoucher($appId, $bkgId, $appType, $checkSum)
	{
		$success	 = false;
		$result		 = [];
		$bpayDocId	 = BookingPayDocs::model()->getBpayId($bkgId, $appType, $appId, $checkSum);
		if (!$bpayDocId)
		{
			$success = false;
			$message = "No Booking Pay Doc Id.";
		}
		$model		 = BookingPayDocs::model()->findByPk($bpayDocId['bpay_id']);
		$userInfo	 = UserInfo::getInstance();
		if ($model && ($model->bpay_status != 0))
		{
			$model->bpay_status	 = 0;
			unlink('.' . DIRECTORY_SEPARATOR . '.' . $model->bpay_image);
			$voucherTypeName	 = BookingPayDocs::model()->getTypeByVoucherId($model->bpay_type);
			$desc				 = "Voucher Type : " . $voucherTypeName . " Deleted Successfully .";
			$model->save();
			$success			 = true;
			//Fetching old booking log mapping
			$bookingLogEvent	 = BookingLog::mapEvents();
			$oldEventId			 = $bookingLogEvent[BookingTrack::VOUCHER_DELETED];
			BookingLog::model()->createLog($model->bpay_bkg_id, $desc, $userInfo, $oldEventId, false, false);
			$message			 = $desc;
		}
		else
		{
			$message = "Voucher Already Deleted  Successfully.";
			$success = false;
		}
		$result = ['message' => $message, 'success' => $success];
		return $result;
	}

	public function saveImage($image, $imagetmp, $bkgId, $type = 6)
	{
		try
		{
			$path = "";
			if ($image != '')
			{

//				$dir = PUBLIC_PATH . DIRECTORY_SEPARATOR.'attachments'.DIRECTORY_SEPARATOR.'bookings'.DIRECTORY_SEPARATOR.$bkgId.DIRECTORY_SEPARATOR.'odometer';
//				if (!is_dir($dir))
//				{
//					mkdir($dir);
//				}
				$dir = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
				if (!is_dir($dir))
				{
					mkdir($dir);
				}
				$dirFolderName = $dir . DIRECTORY_SEPARATOR . 'bookings';
				if (!is_dir($dirFolderName))
				{
					mkdir($dirFolderName);
				}
				$dirByBkgId = $dirFolderName . DIRECTORY_SEPARATOR . $bkgId;
				if (!is_dir($dirByBkgId))
				{
					mkdir($dirByBkgId);
				}
				$dirFinal = $dirByBkgId . DIRECTORY_SEPARATOR . odometer;
				if (!is_dir($dirFinal))
				{
					mkdir($dirFinal);
				}

				$file_path	 = $dirFinal;
				$file_name	 = basename($image);
				$f			 = $file_path;
				$file_path	 = $file_path . DIRECTORY_SEPARATOR . $file_name;
				if (Vehicles::model()->img_resize($imagetmp, 1200, $f, $file_name))
				{
					$path	 = substr($file_path, strlen(PUBLIC_PATH));
					$result	 = ['path' => $path];
				}
			}
		}
		catch (Exception $e)
		{
			Yii::log("Exception thrown: \n\t" . $e->getTraceAsString(), CLogger::LEVEL_ERROR, "system.api.images");
			Yii::log("Data Received: \n\t" . serialize(filter_input_array(INPUT_POST)), CLogger::LEVEL_ERROR, "system.api.images");
			throw $e;
		}
		return $result;
	}

	public function getDocId($bkg_id, $type)
	{
		$sql	 = "SELECT bpay_id FROM  booking_pay_docs where  bpay_type   = $type AND bpay_bkg_id=$bkg_id  ORDER  BY  bpay_id DESC LIMIT 1";
		$data	 = DBUtil::command($sql)->queryScalar();
		return $data;
	}

	public static function getByBkg($fileChecksum, $bkgId = 0, $deviceUniqueID, $type = 0, $eventValue = 0)
	{
		//$params = ["checksum" => $fileChecksum, "deviceUnique" => $deviceUniqueID];
		if ($bkgId != null)
		{
			$cond	 = "AND bpay_bkg_id=:bkgId AND bpay_type=:type ";
			$params	 = ["bkgId" => $bkgId, "type" => $type];

			if ($type == 503)
			{
				$cond	 = " AND bpay_bkg_id=:bkgId AND bpay_type=:eventValue ";
				$params	 = ["bkgId" => $bkgId, "eventValue" => $eventValue];
			}

			if ($type == 0 || $type == NULL)
			{
				$cond	 = " AND bpay_bkg_id=:bkgId AND bpay_checksum='$fileChecksum'";
				$params	 = ["bkgId" => $bkgId];
			}
		}
		//(bpay_checksum!=:checksum AND JSON_EXTRACT(bpay_device_info,$.uniqueId )=:deviceUnique) OR (bpay_checksum=:checksum)
		$sql = "SELECT * FROM booking_pay_docs WHERE 1=1 {$cond} ORDER BY  bpay_id DESC LIMIT 1";

		return DBUtil::queryRow($sql, DBUtil::MDB(), $params);
	}
	
	public static function getByBkgPayDocs($fileChecksum, $bkgId = 0, $deviceUniqueID, $type = 0, $eventValue = 0,$payDocId ="")
	{
		//$params = ["checksum" => $fileChecksum, "deviceUnique" => $deviceUniqueID];
		if ($bkgId != null)
		{
			$cond	 = "AND bpay_bkg_id=:bkgId AND bpay_type=:type ";
			$params	 = ["bkgId" => $bkgId, "type" => $type];
			
			if($payDocId!=0 && $payDocId!=" ")
			{
				$qry	 = "AND bpay_id = $payDocId ";
			}
			
			if ($type == 503)
			{
				$cond	 = " AND bpay_bkg_id=:bkgId AND bpay_type=:eventValue ";
				$params	 = ["bkgId" => $bkgId, "eventValue" => $eventValue];
			}

			if ($type == 0 || $type == NULL)
			{
				$cond	 = " AND bpay_bkg_id=:bkgId AND bpay_checksum='$fileChecksum'";
				$params	 = ["bkgId" => $bkgId];
			}
		}
		//(bpay_checksum!=:checksum AND JSON_EXTRACT(bpay_device_info,$.uniqueId )=:deviceUnique) OR (bpay_checksum=:checksum)
		$sql = "SELECT * FROM booking_pay_docs WHERE 1=1 {$cond} {$qry} ORDER BY  bpay_id DESC LIMIT 1";

		return DBUtil::queryRow($sql, DBUtil::MDB(), $params);
	}
	
	

	/** @param CUploadedFile $uploadedFile */
	public static function uploadAttachments($uploadedFile, $bkgId, $bpayModel, $bkgCrtDate)
	{

//      changes for path by filter

		$dirFinal	 = Filter::getBookingFilePath(Config::getServerID(), 'bookings', $bkgId, $bkgCrtDate);
		$fullPath	 = Yii::app()->basePath . $dirFinal;

		$tempFile	 = $uploadedFile->getTempName();
		$file_name	 = $bpayModel['bpay_type'] . '-' . $bpayModel['bpay_id'] . '-' . $uploadedFile->getName();

		$extention = strtolower($uploadedFile->getExtensionName());

		$filePath = $fullPath . $file_name;
		if (file_exists($filePath) || $bpayModel['bpay_s3_data'] != NULL)
		{
			goto skipResize;
		}
		if (!is_dir($fullPath))
		{
			mkdir($fullPath, 0755, true);
		}

		if (in_array($extention, ["jpg", "png", "jpeg", "gif"]))
		{
			Vehicles::model()->img_resize($tempFile, 1200, $fullPath, $file_name);
		}
		else
		{
			$uploadedFile->saveAs($fullPath . $file_name);
		}

		skipResize:
		return $dirFinal . $file_name;
	}
	/**
	 * @param CUploadedFile $uploadedFile
	 * @param string $bkgId BookingID of uploadedDOcument
	 * @throws Exception
	 *  */
	public static function uploadDocs($uploadedFile, $bkgId, $deviceUniqueID = "", $event = "", $discrepancies = "",$checksum="", $eventValue = "",$payDocsId="")
	{
		$dbChecksum		 = null;
		$returnSet		 = new ReturnSet();
		$booking		 = Booking::model()->findByPk($bkgId);
		//$bpayModelRow	 = BookingPayDocs::getByBkg('', $bkgId, $deviceUniqueID, $event, $eventValue);
		$bpayModelRow	 = BookingPayDocs::getByBkgPayDocs('', $bkgId, $deviceUniqueID, $event, $eventValue,$payDocsId);
		$dbDeviceJson	 = CJSON::decode($bpayModelRow['bpay_device_info']);
		if ($dbDeviceJson['uuid'] != $deviceUniqueID ||  count($discrepancies) > 0)
		{
			$uIDarr				 = ($dbDeviceJson['uniqueId'] != $deviceUniqueID) ? ['deviceID' => "Device Mismatch:" . $dbDeviceJson['uniqueId'] . "==" . $deviceUniqueID] : [];
			$remarksarr			 = json_encode(array_merge(["Event" => $bpayModelRow['bpay_type']], $uIDarr, $checkSumarr));
			$escalationRemark	 = "Manual inspection of Driver app images required.  Check if driver was trying to cheat the system. (Manual inspection needed by Dispatch team). If found OK then you can release payment. ";
			$oldRemarksarr		 = [];
			if ($booking->bkgTrail->btr_datadiscrepancy_remarks != null)
			{
				$oldRemarksarr = json_decode($booking->bkgTrail->btr_datadiscrepancy_remarks, true);
			}
			$oldRemarksarr[] = json_decode($remarksarr, true);

			$booking->bkgTrail->btr_is_datadiscrepancy		 = $booking->bkgTrail->btr_is_datadiscrepancy + 1;
			$booking->bkgTrail->btr_datadiscrepancy_remarks	 = json_encode(array_unique($oldRemarksarr, SORT_REGULAR));
			if ($booking->bkgTrail->btr_bkg_id == $bkgId)
			{
				$booking->bkgTrail->addDiscrepancy($bpayModelRow['bpay_type'], $escalationRemark, $booking->bkg_bcb_id, 0);
			}
			if ($remarksarr)
			{
				$arr = [];
				
				$getTrackLog = BookingTrackLog::model()->getdetailByEvent($bkgId, $bpayModelRow['bpay_type']);
				
				if ($getTrackLog['btl_discrepancy_remarks'])
				{
					$arr = json_decode($getTrackLog['btl_discrepancy_remarks'], true);
				}
				$arr[]			 = json_decode($remarksarr, true);
				$trackLogModel	 = BookingTrackLog::model()->findByPk($getTrackLog['btl_id']);
				if (!empty($trackLogModel))
				{
					$trackLogModel->btl_is_discrepancy		 = $getTrackLog['btl_is_discrepancy'] + 1;
					$trackLogModel->btl_discrepancy_remarks	 = json_encode(array_unique($arr, SORT_REGULAR));
					$trackLogModel->save();
				}
			}
		}
		$model				 = BookingPayDocs::model()->findByPk($bpayModelRow['bpay_id']);
		$path				 = BookingPayDocs::uploadAttachments($uploadedFile, $bkgId, $bpayModelRow, $booking->bkg_pickup_date);
		$model->bpay_image	 = $path;
		$model->bpay_status	 = 1;
		
		if (!$model->save())
		{
			throw new Exception(json_encode($this->getErrors()), 1);
		}

		$returnSet->setMessage("Successfully saved");
		$returnSet->setData($model, false);
		$returnSet->setStatus(true);

		return $returnSet;
	}


	/**
	 * @param CUploadedFile $uploadedFile
	 * @param string $bkgId BookingID of uploadedDOcument
	 * @throws Exception
	 *  */
	public static function uploadDocsByChecksum($uploadedFile, $bkgId, $deviceUniqueID = "", $event = "", $discrepancies = "", $checksum = "",$eventValue = "")
	{
		$dbChecksum		 = null;
		$returnSet		 = new ReturnSet();
		$fileChecksum	 = md5_file($uploadedFile->getTempName());
		#$eventValue = $event;
		$bpayModelRow	 = BookingPayDocs::getByBkg($fileChecksum, $bkgId, $deviceUniqueID, $event, $eventValue);
		if ($bpayModelRow)
		{
			$dbChecksum = $bpayModelRow['bpay_checksum'];
		}

		if ($fileChecksum != trim($checksum) && $fileChecksum != $dbChecksum)
		{
			throw new Exception("CHECKSUM mismatched. File Checksum: {$fileChecksum}; Booking ID: {$bkgId}", ReturnSet::ERROR_INVALID_DATA);
		}

		$booking		 = Booking::model()->findByPk($bkgId);
		$dbDeviceJson	 = CJSON::decode($bpayModelRow['bpay_device_info']);
		if ($checksum == "")
		{
			$checksum = $dbChecksum;
		}

		if ($dbDeviceJson['uniqueId'] != $deviceUniqueID || $checksum != $fileChecksum || count($discrepancies) > 0)
		{

			if ($discrepancies[0]->code == 1)
			{
				$checkSumarr = ['checksum' => $discrepancies[0]->remarks];
			}
			$uIDarr				 = ($dbDeviceJson['uniqueId'] != $deviceUniqueID) ? ['deviceID' => "Device Mismatch:" . $dbDeviceJson['uniqueId'] . "==" . $deviceUniqueID] : [];
			$checkSumarr		 = ($checksum != $fileChecksum) ? ['checksum' => "Checksum Mismatch:" . $bpayModelRow['bpay_checksum'] . "==" . $fileChecksum] : [];
			$remarksarr			 = json_encode(array_merge(["Event" => $bpayModelRow['bpay_type']], $uIDarr, $checkSumarr));
			$escalationRemark	 = "Manual inspection of Driver app images required.  Check if driver was trying to cheat the system. (Manual inspection needed by Dispatch team). If found OK then you can release payment. ";
			$oldRemarksarr		 = [];
			if ($booking->bkgTrail->btr_datadiscrepancy_remarks != null)
			{
				$oldRemarksarr = json_decode($booking->bkgTrail->btr_datadiscrepancy_remarks, true);
			}
			$oldRemarksarr[] = json_decode($remarksarr, true);

			$booking->bkgTrail->btr_is_datadiscrepancy		 = $booking->bkgTrail->btr_is_datadiscrepancy + 1;
			$booking->bkgTrail->btr_datadiscrepancy_remarks	 = json_encode(array_unique($oldRemarksarr, SORT_REGULAR));
			if ($booking->bkgTrail->btr_bkg_id == $bkgId)
			{
				$booking->bkgTrail->addDiscrepancy($bpayModelRow['bpay_type'], $escalationRemark, $booking->bkg_bcb_id, 0);
			}
			if ($remarksarr)
			{
				$arr = [];
				if ($event == 503)
				{
					$getTrackLog = BookingTrackLog::model()->getdetailsByChecksum($bkgId, $event, $bpayModelRow['bpay_checksum']);
				}
				else
				{
					$getTrackLog = BookingTrackLog::model()->getdetailByEvent($bkgId, $bpayModelRow['bpay_type']);
				}
				if ($getTrackLog['btl_discrepancy_remarks'])
				{
					$arr = json_decode($getTrackLog['btl_discrepancy_remarks'], true);
				}
				$arr[]			 = json_decode($remarksarr, true);
				$trackLogModel	 = BookingTrackLog::model()->findByPk($getTrackLog['btl_id']);
				if (!empty($trackLogModel))
				{
					$trackLogModel->btl_is_discrepancy		 = $getTrackLog['btl_is_discrepancy'] + 1;
					$trackLogModel->btl_discrepancy_remarks	 = json_encode(array_unique($arr, SORT_REGULAR));
					$trackLogModel->save();
				}
			}
		}
		$model				 = BookingPayDocs::model()->findByPk($bpayModelRow['bpay_id']);
		$path				 = BookingPayDocs::uploadAttachments($uploadedFile, $bkgId, $bpayModelRow, $booking->bkg_pickup_date);
		$model->bpay_image	 = $path;
		$model->bpay_status	 = 1;
		if ($checksum != "" && $dbChecksum != $checksum)
		{
			$model->bpay_checksum = $checksum;
		}
		if (!$model->save())
		{
			throw new Exception(json_encode($this->getErrors()), 1);
		}

		$returnSet->setMessage("Successfully saved");
		$returnSet->setData($model, false);
		$returnSet->setStatus(true);

		return $returnSet;
	}

	public static function uploadCarVerifyImage($package_type, $app_type, $bookingId, $image, $systemChkSum = null)
	{
		//update existing images

		BookingPayDocs::model()->updateExistingByIdType($bookingId, $package_type);

		$model					 = new BookingPayDocs();
		$model->bpay_type		 = $package_type;
		$model->bpay_app_type	 = $app_type;
		$model->bpay_bkg_id		 = $bookingId;
		$model->bpay_image		 = $image;
		$model->bpay_status		 = 1;
		$model->bpay_date		 = date('Y-m-d h:i:s');
		$model->bpay_checksum	 = $systemChkSum;
		if (!$model->save())
		{
			$success = false;
		}

		return $success;
	}

	public static function uploadCarVerifyImageV1($package_type, $app_type, $bookingId, $image, $systemChkSum = null)
	{
		$success		 = true;
		$bpayModelRow	 = BookingPayDocs::getByBkg($systemChkSum, $bookingId,'');
		// make inactive previous one
		$res = BookingPayDocs::updateExistingByIdType($bookingId,$package_type);
		if ($bpayModelRow['bpay_id'] != "")
		{
			$model = BookingPayDocs::model()->findByPk($bpayModelRow['bpay_id']);

			$model->bpay_image		 = $image;
			$model->bpay_status		 = 1;
			$model->bpay_date		 =  date('Y-m-d', strtotime(date('Y-m-d H:i:s')));
			$model->bpay_checksum	 = $systemChkSum;

			if (!$model->save())
			{
				$success = false;
			}
		}
		else
		{
			$model					 = new BookingPayDocs();
			$model->bpay_type		 = $package_type;
			$model->bpay_app_type	 = $app_type;
			$model->bpay_bkg_id		 = $bookingId;
			$model->bpay_image		 = $image;
			$model->bpay_status		 = 1;
			$model->bpay_date		 = date('Y-m-d', strtotime(date('Y-m-d H:i:s')));
			$model->bpay_checksum	 = $systemChkSum;
			if (!$model->save())
			{
				$success = false;
			}
			
		}
		return $success;
	}

	public function updateExistingByIdType($bookingId, $vhcType)
	{
		$sql = "UPDATE `booking_pay_docs` SET `bpay_status`=0 WHERE booking_pay_docs.bpay_bkg_id=$bookingId AND booking_pay_docs.bpay_type=$vhcType AND booking_pay_docs.bpay_status=1";

		$cdb		 = DBUtil::command($sql);
		$rowsUpdated = $cdb->execute();
		return $rowsUpdated;
	}

	public static function getVerifyImages($bookingId)
	{
		//echo $bookingId;
		$params	 = array("bkgId" => $bookingId);
		$sql	 = "SELECT bpay_id,bpay_image,bpay_type, bpay_s3_data FROM booking_pay_docs
                       WHERE bpay_bkg_id=:bkgId  AND bpay_status  = 1  AND bpay_type IN(8,9,10,11)";
		//echo $sql;
		$records = DBUtil::queryAll($sql, DBUtil::SDB(), $params);
		return $records;
	}

	public function getDocTypeText($var = 0)
	{
		$var	 = ($var > 0) ? $var : $this->bpay_type;
		$list	 = $this->doctypeTxt;
		return $list[$var];
	}

	public $doctypeTxt = [
		8	 => 'Car(Front Image)',
		9	 => 'Car(Back Image)',
		10	 => 'Car(Left Image)',
		11	 => 'Car(Right Image)'
	];

	public function getBoostDocsByBkgId($bkgId)
	{
		$params	 = array("bpayBkgId" => $bkgId);
		$sql	 = "SELECT bpay.bpay_id, bpay.bpay_bkg_id, bpay.bpay_type, bpay.bpay_image,bpay.bpay_approved_by, vhc.vhc_number, vhc.vhc_id
						FROM `booking_pay_docs`  bpay
						INNER JOIN booking bkg ON bkg.bkg_id = bpay.bpay_bkg_id
						INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id
						INNER JOIN vehicles vhc ON bcb.bcb_cab_id = vhc.vhc_id 
						WHERE bpay.bpay_bkg_id =:bpayBkgId AND bpay.bpay_type IN(8,9,10,11) AND bpay.bpay_approved=0 AND bpay.bpay_status=1 GROUP BY bpay.bpay_type ORDER BY bpay.bpay_id DESC ";
		$records = DBUtil::queryAll($sql, DBUtil::SDB(), $params);
		return $records;
	}
	
	public function getVhcImgByBkgId($bkgId)
	{
		
		$params	 = array("bpayBkgId" => $bkgId);
		$sql	 = "SELECT bpay.bpay_id, bpay.bpay_bkg_id, bpay.bpay_type, bpay.bpay_image,bpay.bpay_approved_by, vhc.vhc_number, vhc.vhc_id
						FROM `booking_pay_docs`  bpay
						INNER JOIN booking bkg ON bkg.bkg_id = bpay.bpay_bkg_id
						INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id
						INNER JOIN vehicles vhc ON bcb.bcb_cab_id = vhc.vhc_id 
						WHERE bpay.bpay_bkg_id =:bpayBkgId AND bpay.bpay_type IN(8,9,10,11) AND bpay.bpay_approved=0 AND bpay.bpay_status=1 GROUP BY bpay.bpay_type ORDER BY bpay.bpay_id DESC ";
		$records = DBUtil::queryAll($sql, DBUtil::SDB(), $params);
		return $records;
	}
	

	public function updateDocsById($Id, $status)
	{
		$success			 = false;
		$userInfo			 = UserInfo::getInstance();
		$userInfo->userId	 = UserInfo::getUserId();
		$bmodel				 = BookingPayDocs::model()->findByPk($Id);
		if (in_array($bmodel['bpay_type'], [8, 9, 10, 11]))
		{
			if ($status == 3)
			{
				$bmodel->bpay_approved = 1;
			}
			else
			{
				$bmodel->bpay_approved = $status;
			}
			$bmodel->bpay_approved_by	 = $userInfo->userId;
			$bmodel->bpay_approved_date	 = new CDbExpression('NOW()');
			if ($bmodel->save())
			{
				$success = true;
			}
		}
		return $success;
	}

	public function getLocalPath()
	{
		$filePath = realpath(Yii::app()->basePath . DIRECTORY_SEPARATOR . $this->bpay_image);
		if (!file_exists($filePath) && $this->bpay_image != '')
		{
			$filePath = PUBLIC_PATH . $this->bpay_image;
		}

		return $filePath;
	}

	public function getSpacePath()
	{
		$filename	 = basename($this->bpay_image);
		$dateString	 = DateTimeFormat::SQLDateTimeToDateTime($this->bpay_date)->format("Y/m/d");
		$path		 = "{$this->bpay_type}/{$dateString}/{$this->bpay_id}_{$this->bpay_bkg_id}_{$filename}";
		return $path;
	}

	/**
	 * @return Stub\common\SpaceFile
	 */
	public function uploadToSpace($removeLocal = true)
	{
		$localFile		 = $this->getLocalPath();
		$localFileExist	 = file_exists($localFile);
		$objSpaceFile	 = null;
		if (!$localFileExist)
		{
			$this->bpay_s3_data = "{}";
			$this->save();
			goto end;
		}
		try
		{
			$spaceFile			 = $this->getSpacePath();
			$objSpaceFile		 = Stub\common\SpaceFile::init(Storage::getBookingSpace()->getName(), $spaceFile);
			$objSpaceFile		 = Storage::uploadFile(Storage::getBookingSpace(), $spaceFile, $localFile, $removeLocal);
			$this->bpay_s3_data	 = $objSpaceFile->toJSON();
			$this->save();
		}
		catch (Exception $exc)
		{
			if ($localFileExist && !file_exists($localFile) && $objSpaceFile != null)
			{
				$this->bpay_s3_data = $objSpaceFile->toJSON();
				$this->save();
			}
			Logger::exception($exc);
		}

		end:
		return $objSpaceFile;
	}

	/** @return SpacesAPI\File */
	public function getSpaceFile()
	{
		return Storage::getFile(Storage::getBookingSpace(), $this->getSpacePath());
	}

	public function removeSpaceFile()
	{
		return Storage::removeFile(Storage::getBookingSpace(), $this->getSpacePath());
	}

	public function getSignedURL()
	{
		$url = null;
		if ($this->bpay_s3_data != null && $this->bpay_s3_data != '{}')
		{
			$objSpaceFile		 = Stub\common\SpaceFile::populate($this->bpay_s3_data);
			$url				 = $objSpaceFile->getURL(strtotime("+7 day"));
			$this->bpay_s3_data	 = $objSpaceFile->toJSON();
			$this->save();
		}
		return $url;
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
			$cond = " AND (bpay_image LIKE '%/attachments/{$serverId}/vehicles/%' OR bpay_image LIKE '%/doc/{$serverId}/vehicles/%' OR bpay_image LIKE '%/doc/{$serverId}/bookings/%') ";

			$sql = "SELECT bpay_id FROM booking_pay_docs WHERE bpay_s3_data IS NULL AND bpay_status=1 $cond ORDER BY bpay_id DESC LIMIT 0,$limit1";

			$res = DBUtil::query($sql);
			if ($res->getRowCount() == 0)
			{
				break;
			}
			foreach ($res as $row)
			{
				/** @var BookingPayDocs $bpayModel */
				$bpayModel = BookingPayDocs::model()->findByPk($row["bpay_id"]);
				$bpayModel->uploadToS3();
				Logger::writeToConsole($bpayModel->bpay_s3_data);
			}
			$limit -= $limit1;
			Logger::flush();
		}
	}

	/** @return Stub\common\SpaceFile */
	public function uploadToS3()
	{
		$bpayModel = $this;
		if (!file_exists($bpayModel->getLocalPath()) || $bpayModel->bpay_image == '')
		{
			if ($bpayModel->bpay_s3_data == '')
			{
				$bpayModel->bpay_s3_data = "{}";
				$bpayModel->save();
			}
			return null;
		}
		$spaceFile = $bpayModel->uploadToSpace();
		return $spaceFile;
	}

	/**
	 * Updating S3Data for cab verification which was updated in vehicle docs & booking pay docs both
	 * @param VehicleDocs $vhdModel
	 * @return boolean
	 */
	public static function updateS3DataFromVhcDoc($vhdModel)
	{
		if (!$vhdModel || !in_array($vhdModel->vhd_type, array(8, 9, 10, 11)) || $vhdModel->vhd_s3_data == null || $vhdModel->vhd_s3_data == '')
		{
			return false;
		}
		$vhdType = $vhdModel->vhd_type;
		$vhdFile = $vhdModel->vhd_file;
		$s3Data	 = $vhdModel->vhd_s3_data;

		$params	 = array("vhdType" => $vhdType, "vhdFile" => $vhdFile);
		$sql	 = "SELECT bpay_id FROM booking_pay_docs WHERE bpay_type =:vhdType AND bpay_image =:vhdFile AND (bpay_s3_data IS NULL OR bpay_s3_data = '{}' OR bpay_s3_data = '')";
		$res	 = DBUtil::query($sql, DBUtil::SDB(), $params);
		foreach ($res as $row)
		{
			$bpayModel				 = BookingPayDocs::model()->findByPk($row['bpay_id']);
			$bpayModel->bpay_s3_data = $s3Data;
			$bpayModel->save();
		}
	}

	/**
	 *
	 * @param type $bpayId
	 * @return Pay Docs path link
	 */
	public static function getDocPathById($bpayId)
	{
		$path = '/images/no-image.png';

		$bpayDocsModel = BookingPayDocs::model()->findByPk($bpayId);
		if (!$bpayDocsModel)
		{
			goto end;
		}
		$fieldName = "bpay_s3_data";

		$s3Data	 = $bpayDocsModel->$fieldName;
		$imgPath = $bpayDocsModel->getLocalPath();

		if (file_exists($imgPath) && $imgPath != $bpayDocsModel->getBaseDocPath())
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
				$bpayDocsModel->$fieldName = $spaceFile->toJSON();
				$bpayDocsModel->save();
			}
		}
		end:
		return $path;
	}

	public function getBaseDocPath()
	{
		return PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR;
	}

}

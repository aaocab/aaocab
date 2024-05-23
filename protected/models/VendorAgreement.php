<?php

/**
 * This is the model class for table "vendor_agreement".
 *
 * The followings are the available columns in table 'vendor_agreement':
 * @property integer $vag_id
 * @property integer $vag_vnd_id
 * @property string $vag_agmt_ver
 * @property string $vag_digital_ver
 * @property string $vag_digital_sign
 * @property string $vag_digital_device_id
 * @property string $vag_digital_ip
 * @property string $vag_digital_agreement
 * @property string $vag_draft_agreement
 * @property string $vag_digital_uuid
 * @property string $vag_digital_lat
 * @property string $vag_digital_long
 * @property string $vag_digital_os
 * @property integer $vag_digital_flag
 * @property string $vag_digital_date
 * @property string $vag_soft_ver
 * @property integer $vag_soft_path
 * @property integer $vag_soft_flag
 * @property string $vag_soft_exp_date
 * @property string $vag_soft_date
 * @property integer $vag_hard_flag
 * @property string $vag_hard_ver
 * @property string $vag_hard_date
 * @property integer $vag_active
 * @property integer $vag_digital_is_email
 * @property string $vag_created_at
 * @property string $vag_remarks
 * @property string $vag_approved_at
 * @property string $vag_approved_by
 * @property integer $vag_approved
 *
 * The followings are the available model relations:
 * @property Vendors $vagVnd
 */
class VendorAgreement extends CActiveRecord
{

	public $searchName;

	const DIGITAL_SIGN		 = 1;
	const DIGITAL_AGREEMENT	 = 2;
	const DRAFT_AGREEMENT		 = 3;
	const SOFT_PATH			 = 4;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vendor_agreement';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vag_vnd_id, vag_digital_flag, vag_soft_flag, vag_hard_flag, vag_active, vag_approved', 'numerical', 'integerOnly' => true),
			array('vag_agmt_ver, vag_digital_ver, vag_digital_device_id, vag_digital_ip', 'length', 'max' => 50),
			array('vag_digital_sign', 'length', 'max' => 255),
			array('vag_digital_date, vag_soft_date, vag_hard_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vag_id, vag_vnd_id, vag_agmt_ver, vag_digital_ver, vag_digital_sign, vag_digital_device_id, vag_digital_ip,  vag_digital_agreement, vag_draft_agreement, vag_digital_uuid, vag_digital_lat, vag_digital_long, vag_digital_os, vag_digital_flag, vag_digital_date, vag_soft_ver, vag_soft_path, vag_soft_flag, vag_soft_date, vag_hard_ver, vag_hard_flag, vag_hard_date, vag_active, vag_digital_is_email, vag_soft_exp_date, vag_created_at, vag_remarks, vag_approved_at, vag_approved_by', 'safe', 'on' => 'search'),
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
			'vagVnd' => array(self::BELONGS_TO, 'Vendors', 'vag_vnd_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'vag_id'				 => 'Vag Id',
			'vag_vnd_id'			 => 'Vag Vnd',
			'vag_agmt_ver'			 => 'Vag Agmt Ver',
			'vag_digital_ver'		 => 'Vag Digital Ver',
			'vag_digital_sign'		 => 'Vag Digital Sign',
			'vag_digital_device_id'	 => 'Vag Digital Device',
			'vag_digital_ip'		 => 'Vag Digital Ip',
			'vag_digital_agreement'	 => 'Vag Digital Agreement',
			'vag_draft_agreement'	 => 'Vag Draft Agreement',
			'vag_digital_uuid'		 => 'Vag Digital Uuid',
			'vag_digital_lat'		 => 'Vag Digital Lat',
			'vag_digital_long'		 => 'Vag Digital Long',
			'vag_digital_os'		 => 'Vag Digital Os',
			'vag_digital_flag'		 => 'Vag Digital Flag',
			'vag_digital_date'		 => 'Vag Digital Date',
			'vag_soft_ver'			 => 'Vag Soft Ver',
			'vag_soft_path'			 => 'Vag Soft Path',
			'vag_soft_flag'			 => 'Vag Soft Flag',
			'vag_soft_exp_date'		 => 'Vag Soft Exp Date',
			'vag_soft_date'			 => 'Vag Soft Date',
			'vag_hard_ver'			 => 'Vag Hard Ver',
			'vag_hard_flag'			 => 'Vag Hard Flag',
			'vag_hard_date'			 => 'Vag Hard Date',
			'vag_active'			 => 'Vag Active',
			'vag_digital_is_email'	 => 'Vag Digital Is Email',
			'vag_created_at'		 => 'Vag Created At',
			'vag_remarks'			 => 'Remarks',
			'vag_approved_at'		 => 'Approved At',
			'vag_approved_by'		 => 'Approved By',
			'vag_approved'			 => 'Approved Status',
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
		$criteria->compare('vag_id', $this->vag_id);
		$criteria->compare('vag_vnd_id', $this->vag_vnd_id);
		$criteria->compare('vag_agmt_ver', $this->vag_agmt_ver, true);
		$criteria->compare('vag_digital_ver', $this->vag_digital_ver, true);
		$criteria->compare('vag_digital_sign', $this->vag_digital_sign, true);
		$criteria->compare('vag_digital_device_id', $this->vag_digital_device_id, true);
		$criteria->compare('vag_digital_ip', $this->vag_digital_ip, true);
		$criteria->compare('vag_digital_agreement', $this->vag_digital_agreement, true);
		$criteria->compare('vag_draft_agreement', $this->vag_draft_agreement, true);
		$criteria->compare('vag_digital_uuid', $this->vag_digital_uuid, true);
		$criteria->compare('vag_digital_lat', $this->vag_digital_lat, true);
		$criteria->compare('vag_digital_long', $this->vag_digital_long, true);
		$criteria->compare('vag_digital_os', $this->vag_digital_os, true);
		$criteria->compare('vag_digital_flag', $this->vag_digital_flag);
		$criteria->compare('vag_digital_date', $this->vag_digital_date, true);
		$criteria->compare('vag_soft_ver', $this->vag_soft_ver, true);
		$criteria->compare('vag_soft_path', $this->vag_soft_path);
		$criteria->compare('vag_soft_flag', $this->vag_soft_flag);
		$criteria->compare('vag_soft_exp_date', $this->vag_soft_exp_date, true);
		$criteria->compare('vag_soft_date', $this->vag_soft_date, true);
		$criteria->compare('vag_hard_ver', $this->vag_hard_ver, true);
		$criteria->compare('vag_hard_flag', $this->vag_hard_flag);
		$criteria->compare('vag_hard_date', $this->vag_hard_date);
		$criteria->compare('vag_active', $this->vag_active);
		$criteria->compare('vag_digital_is_email', $this->vag_digital_is_email);
		$criteria->compare('vag_created_at', $this->vag_created_at, true);
		$criteria->compare('vag_remarks', $this->vag_remarks, true);
		$criteria->compare('vag_approved_at', $this->vag_approved_at, true);
		$criteria->compare('vag_approved_by', $this->vag_approved_by, true);
		$criteria->compare('vag_approved', $this->vag_approved);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VendorAgreement the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function updateSignature($vndId, $digSign = '')
	{
		if ($digSign != '')
		{
			$digDate	 = DATE('Y-m-d H:i:s');
			$sql		 = "INSERT INTO `vendor_agreement` (vag_vnd_id, vag_digital_sign, vag_digital_flag,vag_digital_date)
                        VALUES($vndId, '$digSign', '1', '$digDate') ON DUPLICATE KEY
                        UPDATE vag_vnd_id=$vndId, vag_digital_sign='$digSign', vag_digital_date='$digDate'";
			$cdb		 = DBUtil::command($sql);
			$rowsUpdated = $cdb->execute();
			$return		 = ($rowsUpdated > 0) ? 1 : 0;
			return $return;
		}
	}

	public function checkStatus($vndId)
	{
		$success	 = false;
		$userInfo	 = UserInfo::getInstance();
		$sql		 = "SELECT vag_id FROM vendor_agreement WHERE vag_vnd_id =$vndId AND vag_approved=3";
		$recordset	 = DBUtil::command($sql, DBUtil::SDB())->queryAll();
		if (count($recordset) > 0)
		{
			foreach ($recordset as $val)
			{
				$model = VendorAgreement::model()->findByPk($val['vag_id']);
				if ($model->vag_digital_flag == 0 && $model->vag_approved == 3)
				{
					$model->vag_approved = 0;
					if ($model->update())
					{
						$success	 = true;
						$descLog	 = "Modified Approved status of the vendor by App";
						$event_id	 = VendorsLog::VENDOR_AGREEMENT_REJECT_TO_NORMAL;
						VendorsLog::model()->createLog($model->vag_vnd_id, $descLog, $userInfo, $event_id, false, false);
					}
				}
			}
		}
		return $success;
	}

	public function getAgreementList($searchVndName = '')
	{
		$where = "AND vag.vag_approved NOT IN(1,3) AND vag.vag_digital_sign IS NOT NULL";
		if ($searchVndName != '')
		{
			$where .= " AND  vag.vag_vnd_id = $searchVndName";
		}

		$sql = "SELECT DISTINCT(vag.vag_id),
				vag.vag_soft_path,
				vag.vag_digital_sign,
				vag.vag_digital_agreement,
				vag.vag_vnd_id ,
				vag.vag_approved_at,
				vag.vag_approved_by,
				vag.vag_approved,
				vnd.vnd_id,
				vnd.vnd_contact_id,
				cnt.ctt_user_type,
				cnt.ctt_first_name,
				cnt.ctt_last_name,
				cnt.ctt_business_name,
				IF(ctt_user_type=1, CONCAT(cnt.ctt_first_name, ' ', cnt.ctt_last_name), cnt.ctt_business_name) AS name
				FROM vendor_agreement vag
				INNER JOIN vendors vnd ON vag.vag_vnd_id = vnd.vnd_id AND vnd.vnd_active NOT IN(0,2) AND vnd.vnd_id = vnd.vnd_ref_code
				INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd.vnd_id AND cp.cr_status =1
				INNER JOIN contact cnt ON cnt.ctt_id =cp.cr_contact_id AND cnt.ctt_active =1 AND cnt.ctt_id = cnt.ctt_ref_code
                $where ";

		$arr			 = array();
		$data			 = DBUtil::queryRow("SELECT COUNT(DISTINCT(vag.vag_id)) AS count
							FROM vendor_agreement vag
							INNER JOIN vendors vnd ON vag.vag_vnd_id = vnd.vnd_id AND vnd.vnd_active NOT IN(0,2) AND vnd.vnd_id = vnd.vnd_ref_code
							INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd.vnd_id AND cp.cr_status =1
							INNER JOIN contact cnt ON cnt.ctt_id =cp.cr_contact_id AND cnt.ctt_active =1 
							AND cnt.ctt_id = cnt.ctt_ref_code $where", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $data['count'],
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes'	 => ['vag_id'],
				'defaultOrder'	 => 'vnd_id DESC'], 'pagination'	 => ['pageSize' => 50],
		]);

		$arr[0]	 = $dataprovider;
		$arr[1]	 = $data;
		return $arr;
	}

	public function findAgreementByVndId($vndId)
	{
		$sql = "SELECT * FROM `vendor_agreement` WHERE vendor_agreement.vag_vnd_id ='$vndId'";
		return DBUtil::queryRow($sql);
	}

	public function checkVersionStatusByVndId($vndId)
	{
		// flag 1 :: normal procedure of agreement
		// flag 0 :: new agreement version upload and stay at middle page
		$newDigitalVersion	 = Yii::app()->params['digitalagmtversion'];
		//$sql = "SELECT vag_digital_ver, vag_digital_flag, vag_soft_flag FROM `vendor_agreement` WHERE vendor_agreement.vag_vnd_id=$vndId";
		//$sql = "SELECT IF((vendor_agreement.vag_agmt_ver>=$newDigitalVersion),1,0) FROM `vendor_agreement` WHERE vendor_agreement.vag_vnd_id=$vndId";
		//$sql = "SELECT IF((vendor_agreement.vag_digital_ver>=$newDigitalVersion),1,0) FROM `vendor_agreement` WHERE vendor_agreement.vag_vnd_id=$vndId";
		$sql				 = "SELECT IF((vendor_agreement.vag_digital_ver<$newDigitalVersion),0,1) FROM `vendor_agreement` WHERE vendor_agreement.vag_vnd_id ='$vndId'";
		$extDigitalVersion	 = DBUtil::command($sql)->queryScalar();
		return $extDigitalVersion;
	}

	public function findAllDigitalAgreementCopy()
	{
		$version = Yii::app()->params['digitalagmtversion'];
		$sql	 = "SELECT vag_vnd_id,vendors.vnd_name 
                    FROM `vendor_agreement`
                    INNER JOIN `vendors` ON vendors.vnd_id=vendor_agreement.vag_vnd_id and  vendors.vnd_id=vendors.vnd_ref_code	
					WHERE vendor_agreement.vag_active=1
                    AND (vendor_agreement.vag_digital_agreement IS NULL OR vendor_agreement.vag_draft_agreement IS NULL)
                    AND vendor_agreement.vag_digital_is_email=0
                    AND vendor_agreement.vag_digital_sign!=''
                    AND vendor_agreement.vag_digital_flag=1
                    AND vendor_agreement.vag_digital_ver=$version GROUP BY vendors.vnd_ref_code";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function findAllDigitalAgreementEmail()
	{
		$version = Yii::app()->params['digitalagmtversion'];
		$sql	 = "SELECT vag_vnd_id,vag_digital_is_email,v1.vnd_name ,vag_digital_agreement, vag_draft_agreement
                    FROM `vendor_agreement`
                    INNER JOIN `vendors` ON vendors.vnd_id=vendor_agreement.vag_vnd_id 
					INNER JOIN `vendors` v1 ON v1.vnd_id=vendors.vnd_ref_code	
                    WHERE vendor_agreement.vag_active=1
                    AND vendor_agreement.vag_digital_agreement IS NOT NULL
                    AND vendor_agreement.vag_draft_agreement IS NOT NULL
                    AND vendor_agreement.vag_digital_is_email=0
                    AND vendor_agreement.vag_digital_sign!=''
                    AND vendor_agreement.vag_digital_flag=1
                    AND vendor_agreement.vag_digital_ver=$version GROUP BY v1.vnd_ref_code ORDER BY vag_vnd_id DESC";
		$rows	 = DBUtil::queryAll($sql);
		return $rows;
	}

	public function findAgreementStatusByVndId($vndId)
	{
		$sql = "SELECT IF((vendor_agreement.vag_digital_sign!='' AND vendor_agreement.vag_digital_flag=1),1,0) as digital_flag,
                    IF(vendor_agreement.vag_soft_flag=2,1,0) as soft_flag_approval,
                    IF((vendor_agreement.vag_soft_path!='' AND vendor_agreement.vag_soft_flag=1),1,0) as soft_flag,
                    IF(vendor_agreement.vag_hard_flag=1,1,0) as hard_flag,vendor_agreement.vag_soft_date
                    FROM `vendor_agreement` WHERE vendor_agreement.vag_vnd_id ='$vndId'";
		$row = DBUtil::queryRow($sql);
		if ($row['hard_flag'] == 1)
		{
			return 4;
		}
		else if ($row['soft_flag_approval'] == 1)
		{
			return 2;
		}
		else if ($row['soft_flag'] == 1)
		{
			return 3;
		}
		else if ($row['digital_flag'] == 1)
		{
			return 1;
		}
		return 0;
	}

	public function findByVndId($vndId)
	{
		$criteria = new CDbCriteria();
		$criteria->compare('vag_vnd_id', $vndId);
		return VendorAgreement::model()->find($criteria);
	}

	public static function saveAgreement($vndid)
	{
		$model		 = new VendorAgreement();
		$modelAgmt	 = $model->findByVndId($vndid);
		if (!$modelAgmt)
		{
			$model->vag_vnd_id	 = $vndid;
			$model->vag_active	 = 0;
			$model->save();
		}
	}

	public function saveDocument($vendorId, $path, UserInfo $userInfo = null, $doc_type, $agreement_date = '')
	{
		$success = false;
		if ($path != '' && $vendorId != '')
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				if ($doc_type == 'agreement')
				{
					$model		 = Vendors::model()->findByPk($vendorId);
					$modelAgmt	 = VendorAgreement::model()->findByVndId($vendorId);
					if ($modelAgmt == '')
					{
						$modelAgmt2					 = new VendorAgreement();
						$modelAgmt2->vag_vnd_id		 = $vendorId;
						$modelAgmt2->vag_soft_date	 = $agreement_date;
						$modelAgmt2->vag_soft_path	 = $path;
						$modelAgmt2->vag_soft_ver	 = Yii::app()->params['digitalagmtversion'];
						$modelAgmt2->save();
					}
					else
					{
						$modelAgmt->vag_soft_path	 = $path;
						$modelAgmt->vag_soft_date	 = $agreement_date;
						$modelAgmt->vag_soft_ver	 = $modelAgmt->vag_digital_ver;
						$modelAgmt->save();
					}
				}
				$success	 = true;
				$event_id	 = VendorsLog::VENDOR_FILE_UPLOAD;
				$logArray	 = VendorsLog::model()->getLogByDocumentType($doc_type);
				$logDesc	 = VendorsLog::model()->getEventByEventId($logArray['upload']);
				VendorsLog::model()->createLog($vendorId, $logDesc, $userInfo, $event_id, false, false);
				$transaction = DBUtil::commitTransaction($transaction);
				return $success;
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
			}
		}

		return $success;
	}

	public static function uploadAllToS3($limit = 1000)
	{
		while ($limit > 0)
		{
			$limit1 = min([1000, $limit]);
			
			// Server Id
			$serverId = Config::getServerID();
			if($serverId == '' || $serverId <= 0)
			{
				Logger::writeToConsole('Server ID not found!!!');
				break;
			}
			
			$condFilePath  = " AND ( ";
			$condFilePath .= " (vag_digital_sign_s3_data IS NULL AND vag_digital_sign LIKE '%/attachments/{$serverId}/%') ";
			$condFilePath .= " OR (vag_digital_agreement_s3_data IS NULL AND vag_digital_agreement LIKE '%/attachments/vendors/{$serverId}/%') ";
			$condFilePath .= " OR (vag_draft_agreement_s3_data IS NULL AND vag_draft_agreement LIKE '%/attachments/vendors/{$serverId}/%') ";
			$condFilePath .= " OR (vag_soft_path_s3_data IS NULL AND vag_soft_path LIKE '%/attachments/{$serverId}/%') ";
			$condFilePath .= " ) ";

			$sql = "SELECT vag_id FROM vendor_agreement WHERE vag_active=1 {$condFilePath} ORDER BY vag_id DESC LIMIT 0,$limit1";

			$res = DBUtil::query($sql);
			if ($res->getRowCount() == 0)
			{
				break;
			}
			foreach ($res as $row)
			{
				/** @var Vendor Agreement $vagModel */
				$vagModel = VendorAgreement::model()->findByPk($row["vag_id"]);

				if (($vagModel->vag_digital_sign != '' && $vagModel->vag_digital_sign != NULL) && ($vagModel->vag_digital_sign_s3_data == '' || $vagModel->vag_digital_sign_s3_data == NULL))
				{
					$vagModel->uploadDigitalSignToSpace();
					Logger::writeToConsole($vagModel->vag_digital_sign_s3_data);
				}
				if (($vagModel->vag_digital_agreement != '' && $vagModel->vag_digital_agreement != NULL) && ($vagModel->vag_digital_agreement_s3_data == '' || $vagModel->vag_digital_agreement_s3_data == NULL))
				{
					$vagModel->uploadDigitalAgreementToSpace();
					Logger::writeToConsole($vagModel->vag_digital_agreement_s3_data);
				}
				if (($vagModel->vag_draft_agreement != '' && $vagModel->vag_draft_agreement != NULL) && ($vagModel->vag_draft_agreement_s3_data == '' || $vagModel->vag_draft_agreement_s3_data == NULL))
				{
					$vagModel->uploadDraftAgreementToSpace();
					Logger::writeToConsole($vagModel->vag_draft_agreement_s3_data);
				}
				if (($vagModel->vag_soft_path != '' && $vagModel->vag_soft_path != NULL) && ($vagModel->vag_soft_path_s3_data == '' || $vagModel->vag_soft_path_s3_data == NULL))
				{
					$vagModel->uploadSoftPathToSpace();
					Logger::writeToConsole($vagModel->vag_soft_path_s3_data);
				}
			}
			$limit -= $limit1;
			Logger::flush();
		}
	}

	/** @return Stub\common\SpaceFile */
	public function uploadDigitalSignToSpace($removeLocal = true)
	{
		$spaceFile = null;
		try
		{
			$vagModel		 = $this;
			$type			 = VendorAgreement::DIGITAL_SIGN;
			$digitalSignPath = $vagModel->getPath($vagModel->vag_digital_sign);

			if (!file_exists($digitalSignPath) || $vagModel->vag_digital_sign == '')
			{
				if ($vagModel->vag_digital_sign == '')
				{
					$vagModel->vag_digital_sign_s3_data = "{}";
					$vagModel->save();
				}
				return null;
			}

			$spaceFile = $vagModel->uploadToSpace($digitalSignPath, $vagModel->getSpacePath($this->vag_digital_sign, $type), $removeLocal);

			$vagModel->vag_digital_sign_s3_data = $spaceFile->toJSON();
			$vagModel->save();
		}
		catch (Exception $exc)
		{
			ReturnSet::setException($exc);
		}
		return $spaceFile;
	}

	/** @return Stub\common\SpaceFile */
	public function uploadDigitalAgreementToSpace($removeLocal = true)
	{
		$spaceFile = null;
		try
		{
			$vagModel				 = $this;
			$type					 = VendorAgreement::DIGITAL_AGREEMENT;
			$digitalAgreementPath	 = $vagModel->getPath($vagModel->vag_digital_agreement);
			if (!file_exists($digitalAgreementPath) || $vagModel->vag_digital_agreement == '')
			{
				if ($vagModel->vag_digital_agreement == '')
				{
					$vagModel->vag_digital_agreement_s3_data = "{}";
					$vagModel->save();
				}
				return null;
			}

			$spaceFile = $vagModel->uploadToSpace($digitalAgreementPath, $vagModel->getSpacePath($this->vag_digital_agreement, $type), $removeLocal);

			$vagModel->vag_digital_agreement_s3_data = $spaceFile->toJSON();
			$vagModel->save();
		}
		catch (Exception $exc)
		{
			ReturnSet::setException($exc);
		}
		return $spaceFile;
	}

	/** @return Stub\common\SpaceFile */
	public function uploadDraftAgreementToSpace($removeLocal = true)
	{
		$spaceFile = null;
		try
		{
			$vagModel			 = $this;
			$type				 = VendorAgreement::DRAFT_AGREEMENT;
			$draftAgreementPath	 = $vagModel->getPath($vagModel->vag_draft_agreement);
			if (!file_exists($draftAgreementPath) || $vagModel->vag_draft_agreement == '')
			{
				if ($vagModel->vag_draft_agreement == '')
				{
					$vagModel->vag_draft_agreement_s3_data = "{}";
					$vagModel->save();
				}
				return null;
			}

			$spaceFile = $vagModel->uploadToSpace($draftAgreementPath, $vagModel->getSpacePath($this->vag_draft_agreement, $type), $removeLocal);

			$vagModel->vag_draft_agreement_s3_data = $spaceFile->toJSON();
			$vagModel->save();
		}
		catch (Exception $exc)
		{
			ReturnSet::setException($exc);
		}
		return $spaceFile;
	}

	/** @return Stub\common\SpaceFile */
	public function uploadSoftPathToSpace($removeLocal = true)
	{
		$spaceFile = null;
		try
		{
			$vagModel	 = $this;
			$type		 = VendorAgreement::SOFT_PATH;
			$softUrlPath = $vagModel->getPath($vagModel->vag_soft_path);
			if (!file_exists($softUrlPath) || $vagModel->vag_soft_path == '')
			{
				if ($vagModel->vag_soft_path == '')
				{
					$vagModel->vag_soft_path_s3_data = "{}";
					$vagModel->save();
				}
				return null;
			}

			$spaceFile = $vagModel->uploadToSpace($softUrlPath, $vagModel->getSpacePath($this->vag_soft_path, $type), $removeLocal);

			$vagModel->vag_soft_path_s3_data = $spaceFile->toJSON();
			$vagModel->save();
		}
		catch (Exception $exc)
		{
			ReturnSet::setException($exc);
		}
		return $spaceFile;
	}

	public function getBaseDocPath()
	{
		return PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR;
	}

	public function getPath($filePath)
	{
		$filePath = implode("/", explode(DIRECTORY_SEPARATOR, $filePath));

		$filePath = ltrim($filePath, '/attachments');

		$filePath = implode(DIRECTORY_SEPARATOR, explode("/", $filePath));

		$filePath = $this->getBaseDocPath() . $filePath;

		if (!file_exists($filePath))
		{
			$filePath = APPLICATION_PATH . $filePath;
		}

		return $filePath;
	}

	public function getSpacePath($localPath, $type)
	{
		$fileName	 = basename($localPath);
		$id			 = $this->vag_id;
		$vndId		 = $this->vag_vnd_id;
		$date		 = $this->vag_created_at;
		$dateString	 = DateTimeFormat::SQLDateTimeToDateTime($date)->format("Y/m/d");
		$folderPath	 = Filter::s3FolderPath($vndId);
		$path		 = "/vendor/agreement/{$folderPath}/{$vndId}/{$type}_{$id}_{$fileName}";
		return $path;
	}

	/**
	 * @return Stub\common\SpaceFile
	 */
	public function uploadToSpace($localFile, $spaceFile, $removeLocal = true)
	{
		$objSpaceFile = Storage::uploadFile(Storage::getDocumentSpace(), $spaceFile, $localFile, $removeLocal);
		return $objSpaceFile;
	}

	/**
	 * 
	 * @param type $docId
	 * @return Doc path link
	 */
	public static function getPathById($vagId, $pathType = '')
	{
		$path = '/images/no-image.png';

		$vagModel = VendorAgreement::model()->findByPk($vagId);
		if (!$vagModel)
		{
			goto end;
		}
		if ($pathType == 1)
		{
			$imgPath	 = $vagModel->getPath($vagModel->vag_digital_sign);
			$fieldName	 = "vag_digital_sign_s3_data";
		}
		if ($pathType == 2)
		{
			$imgPath	 = $vagModel->getPath($vagModel->vag_digital_agreement);
			$fieldName	 = "vag_digital_agreement_s3_data";
		}
		if ($pathType == 3)
		{
			$imgPath	 = $vagModel->getPath($vagModel->vag_draft_agreement);
			$fieldName	 = "vag_draft_agreement_s3_data";
		}
		if ($pathType == 4)
		{
			$imgPath	 = $vagModel->getPath($vagModel->vag_soft_path);
			$fieldName	 = "vag_soft_path_s3_data";
		}
		$s3Data = $vagModel->$fieldName;

		if (file_exists($imgPath) && $imgPath != $vagModel->getBaseDocPath())
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
				$vagModel->$fieldName = $spaceFile->toJSON();
				$vagModel->save();
			}
		}
		end:
		return $path;
	}

}

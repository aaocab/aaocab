<?php

/**
 * This is the model class for table "call_status".
 *
 * The followings are the available columns in table 'call_status':
 * @property integer $cst_call_id
 * @property string $cst_id
 * @property string $cst_lead_id
 * @property int $cst_ref_type 
 * @property int $cst_ref_record
 * @property string $cst_phone_code
 * @property string $cst_phone
 * @property string $cst_did
 * @property string $cst_agent_name
 * @property string $cst_recording_file_name
 * @property string $cst_group
 * @property string $cst_camp
 * @property integer $cst_status
 * @property string $cst_created
 * @property string $cst_modified
 * @property integer $cst_type
 * @property integer $cst_csr_id
 */
class CallStatus extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'call_status';
	}

	const callstatus = [1 => 'Initiated', 2 => 'Dropped', 3 => 'Attended'];

	public $cst_created_check	 = 0;
	public $callTypeArr			 = [1 => 'LD', 2 => 'EB', 3 => 'VND', 4 => 'DRV', 5 => 'NEWVND'];
	public $call_type_name;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(//, cst_did, cst_group, cst_camp, cst_created, cst_modified
			array('cst_id, cst_phone_code, cst_phone', 'required'),
			array('cst_status, cst_type, cst_csr_id', 'numerical', 'integerOnly' => true),
			array('cst_id, cst_group, cst_camp', 'length', 'max' => 30),
			array('cst_lead_id, cst_phone_code, cst_phone, cst_did', 'length', 'max' => 15),
			array('cst_agent_name', 'length', 'max' => 50),
			array('cst_recording_file_name', 'length', 'max' => 100),
			array('cst_lead_id', 'required', 'on' => 'dailer'),
			// The following rule is used by search().
			array('cst_call_id, cst_id, cst_lead_id, cst_ref_id, cst_ref_record, cst_phone_code, cst_phone, cst_did, cst_agent_name, cst_recording_file_name, cst_group, cst_camp, cst_status, cst_created, cst_modified, cst_type, cst_csr_id', 'safe'),
			// @todo Please remove those attributes that should not be searched.
			array('cst_call_id, cst_id, cst_lead_id, cst_phone_code, cst_phone, cst_did, cst_agent_name, cst_recording_file_name, cst_group, cst_camp, cst_status, cst_created, cst_modified, cst_type, cst_csr_id', 'safe', 'on' => 'search'),
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
			'cst_id'					 => 'Cst',
			'cst_lead_id'				 => 'Cst Lead',
			'cst_phone_code'			 => 'Cst Phone Code',
			'cst_phone'					 => 'Cst Phone',
			'cst_did'					 => 'Cst Did',
			'cst_agent_name'			 => 'Cst Agent Name',
			'cst_recording_file_name'	 => 'Cst Recording File Name',
			'cst_group'					 => 'Cst Group',
			'cst_camp'					 => 'Cst Camp',
			'cst_status'				 => 'Cst Status',
			'cst_created'				 => 'Cst Created',
			'cst_modified'				 => 'Cst Modified',
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

		$criteria->compare('cst_id', $this->cst_id, true);
		$criteria->compare('cst_lead_id', $this->cst_lead_id, true);
		$criteria->compare('cst_phone_code', $this->cst_phone_code, true);
		$criteria->compare('cst_phone', $this->cst_phone, true);
		$criteria->compare('cst_did', $this->cst_did, true);
		$criteria->compare('cst_ref_id', $this->cst_ref_id, true);
		$criteria->compare('cst_agent_name', $this->cst_agent_name, true);
		$criteria->compare('cst_recording_file_name', $this->cst_recording_file_name, true);
		$criteria->compare('cst_group', $this->cst_group, true);
		$criteria->compare('cst_camp', $this->cst_camp, true);
		$criteria->compare('cst_status', $this->cst_status);
		$criteria->compare('cst_created', $this->cst_created, true);
		$criteria->compare('cst_modified', $this->cst_modified, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CallStatus the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getList($arr = [])
	{
		$where = ' WHERE 1';
		foreach ($arr as $key => $value)
		{
			if ($key == 'cst_created_check')
			{
				$where .= " AND date(cst_created) = CURDATE() ";
			}
			else
			{
				$where .= " AND $key LIKE '%" . $value . "%' ";
			}
		}


		$sql = "SELECT * FROM call_status  $where";

		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['cst_id', 'cst_lead_id', 'cst_phone_code', 'cst_phone', 'cst_did', 'cst_agent_name', 'cst_recording_file_name', 'cst_group', 'cst_camp', 'cst_status', 'cst_created', 'cst_modified'],
				'defaultOrder'	 => 'cst_created DESC'],
			'pagination'	 => ['pageSize' => 20],
		]);
		return $dataprovider;
	}

	public function Add($info, $call_type)
	{
		$model					 = new CallStatus();
		$model->cst_id			 = $this->callTypeArr[$call_type] . '00' . $info->bkg_id . '00' . 2;
		$model->cst_status		 = 1;
		$model->cst_lead_id		 = $info->bkg_id;
		$model->cst_phone_code	 = $info->bkg_country_code;
		$model->cst_phone		 = $info->bkg_contact_no;
		$model->cst_type		 = 2;

		$model->cst_csr_id = UserInfo::getUserId();
		$model->save();
		return $model;
	}

	public function getByRefId($refId)
	{
		$criteria		 = new CDbCriteria();
		$criteria->compare('cst_ref_id', $refId);
		$criteria->order = 'cst_created DESC';
		$model			 = $this->find($criteria);
		return $model;
	}

	public function getByAlgRefId($refId)
	{
		$criteria	 = new CDbCriteria();
		$criteria->compare('cst_ref_id', $refId);
		$criteria->compare('cst_type', 4);
		$model		 = $this->find($criteria);
		return $model;
	}

	public function getByCstId($refId)
	{
		$criteria	 = new CDbCriteria();
		$criteria->compare('cst_id', $refId);
		$model		 = $this->find($criteria);
		return $model;
	}

	/**
	 * This function is used for call status table entry for my call
	 * @param type $refId assinLogId
	 * @param type $refType assinLogType
	 * @param type $code phone code
	 * @param type $phoneNo
	 * @param type $isRecord
	 * @param type $callType callType
	 * @return \CallStatus
	 */
	public function addMyCall($refId, $refType, $callType, $code, $phoneNo, $isRecord = 1)
	{
		$returnSet = new ReturnSet();
		try
		{
			$cstRefCode	 = str_pad($refId, 9, 0, STR_PAD_LEFT);
			$callTypeArr = $this->callTypeArr;
			$cstId		 = $callTypeArr[$callType] . $cstRefCode . '00' . $refType . '00' . rand(100, 999);

			$model					 = new CallStatus();
			$model->cst_id			 = $cstId;
			$model->cst_csr_id		 = UserInfo::getUserId();
			$model->cst_lead_id		 = $refId;
			$model->cst_ref_id		 = $refId;
			$model->cst_ref_record	 = $isRecord;
			$model->cst_phone_code	 = $code;
			$model->cst_phone		 = $phoneNo;
			$model->cst_type		 = 4;
			$model->cst_status		 = 1;
			$model->cst_created		 = date("Y-m-d H:i:s");
			$model->cst_modified	 = date("Y-m-d H:i:s");

			if (!$model->save())
			{
				Logger::error("Call Status Params: " . json_encode($model->getAttributes()));
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_FAILED);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($model, false);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used for call status table entry
	 * @param type $refId
	 * @param type $refType
	 * @param type $code
	 * @param type $phoneNo
	 * @param type $isRecord
	 * @param type $isLead
	 * @return \CallStatus
	 */
	public function create($refId, $refType, $code, $phoneNo, $isRecord = 1, $isLead = 1)
	{
		$returnSet = new ReturnSet();

		try
		{
			$cstId = $this->callTypeArr[2] . '00' . $refId . '00' . 2 . $this->cst_call_id;
			if ($refType == 3)
			{
				$fwpRefType	 = FollowUps::getRefTypeById($refId);
				$cstId		 = $this->callTypeArr[$fwpRefType] . '00' . $refId . '00' . $fwpRefType . $this->cst_call_id;
			}

			$model = self::model()->getByCstId($cstId);
			if (empty($model))
			{
				$model			 = new CallStatus();
				$model->cst_id	 = $cstId;
			}

			$model->cst_csr_id		 = UserInfo::getUserId();
			#$model->cst_lead_id		 = ($refType == 3) ? NULL : $refId;
			$model->cst_lead_id		 = $refId;
			$model->cst_ref_id		 = $refId;
			$model->cst_ref_record	 = $isRecord;
			$model->cst_phone_code	 = $code;
			$model->cst_phone		 = $phoneNo;
			$model->cst_type		 = ($isLead) ? 2 : 1;
			$model->cst_status		 = 1;
			$model->cst_created		 = date("Y-m-d h:i:s");
			$model->cst_modified	 = date("Y-m-d h:i:s");

			if (!$model->save())
			{
				Logger::error("Call Status Params: " . json_encode($model->getAttributes()));
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_FAILED);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($model, false);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function checkExist($ldId, $csr, $call_sync_id)
	{
		//$csr = 682;
		$sql = "SELECT cst_call_id FROM  call_status WHERE cst_lead_id =$ldId AND cst_csr_id=$csr AND cst_id='$call_sync_id'";

		$cst_call_id = DBUtil::command($sql, DBUtil::SDB())->queryScalar();
		return $cst_call_id;
	}

	public static function getByCallSyncId($call_sync_id)
	{
		$param	 = ['call_sync_id' => $call_sync_id];
		$sql	 = "SELECT cst_call_id FROM  call_status WHERE   cst_id=:call_sync_id";

		$cst_call_id = DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
		return $cst_call_id;
	}

	public function updatebyAudio($ldId, $csr, $call_sync_id, $fileName)
	{
		return $callStatusId = CallStatus::model()->checkExist($ldId, $csr, $call_sync_id);
	}

	public function getAudios($arr = [], $command = false)
	{
		$where = ' AND 1';
		foreach ($arr as $key => $value)
		{
			if ($key == 'cst_created_check')
			{
				$where .= " AND date(cst_created) = CURDATE() ";
			}
			else
			{
				$where .= " AND $key LIKE '%" . $value . "%' ";
			}
		}


		$sql = "SELECT * FROM call_status WHERE cst_type=4 AND cst_status =3$where";

		if (!$command)
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['cst_id', 'cst_lead_id', 'cst_phone_code', 'cst_phone', 'cst_did', 'cst_agent_name', 'cst_recording_file_name', 'cst_group', 'cst_camp', 'cst_status', 'cst_created', 'cst_modified'],
					'defaultOrder'	 => 'cst_created DESC'],
				'pagination'	 => ['pageSize' => 20],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql . " ORDER BY cst_created DESC", DBUtil::SDB());
		}
	}

	/**
	 * This function is used for updating call status
	 * @param interger $refId
	 * @param interger $csr
	 * @return interger
	 */
	public static function updateStatus($refId, $csr)
	{
		$sql = "UPDATE call_status SET cst_status =0 WHERE cst_lead_id =:refId and cst_csr_id=:csr";
		return DBUtil::execute($sql, ['refId' => $refId, 'csr' => $csr]);
	}

	/**
	 * This function is used getting audio details
	 * @param interger $refId
	 * @param interger $csr
	 * @return array
	 */
	public static function getAudioDetails($refId, $csr)
	{
		$sql = "SELECT * FROM call_status WHERE cst_type=4 AND cst_status =3 AND cst_lead_id=:refId AND cst_csr_id=:csr";
		return DBUtil::queryRow($sql, DBUtil::SDB(), ['refId' => $refId, 'csr' => $csr]);
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
			$cond = " AND cst_recording_file_name LIKE '/{$serverId}/audio/DRV/%' ";

			$sql = "SELECT cst_call_id FROM call_status WHERE cst_id IS NOT NULL AND cst_status = 3 AND cst_s3_data IS NULL 
					{$cond}
					ORDER BY cst_call_id DESC LIMIT 0, $limit1";
			$res = DBUtil::query($sql);
			if ($res->getRowCount() == 0)
			{
				break;
			}
			foreach ($res as $row)
			{
				$cstatusModel = CallStatus::model()->findByPk($row['cst_call_id']);
				$cstatusModel->uploadToS3();
				Logger::writeToConsole($cstatusModel->cst_s3_data);
			}
			$limit -= $limit1;
			Logger::flush();
		}
	}

	public function uploadToS3($removeLocal = true)
	{
		$spaceFile = null;
		try
		{
			$cstatusModel	 = $this;
			$path			 = $this->getLocalPath();
			if (!file_exists($path) || $this->cst_recording_file_name == '')
			{
				if ($cstatusModel->cst_s3_data == '')
				{
					$cstatusModel->cst_s3_data = "{}";
					$cstatusModel->save();
				}
				return null;
			}
			$spaceFile					 = $cstatusModel->uploadToSpace($path, $this->getSpacePath($path), $removeLocal);
			$cstatusModel->cst_s3_data	 = $spaceFile->toJSON();
			$cstatusModel->save();
		}
		catch (Exception $exc)
		{
			ReturnSet::setException($exc);
		}
		return $spaceFile;
	}

	public function getLocalPath()
	{
		$filePath = $this->cst_recording_file_name;

		$filePath = implode("/", explode(DIRECTORY_SEPARATOR, $filePath));

		$filePath = implode(DIRECTORY_SEPARATOR, explode("/", $filePath));

		$filePath = $this->getBaseDocPath() . $filePath;

		return $filePath;
	}

	public function getBaseDocPath()
	{
		return APPLICATION_PATH . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR;
	}

	public function getSpacePath($localPath)
	{
		$fileName	 = basename($localPath);
		$id			 = $this->cst_call_id;
		$csrId		 = $this->cst_csr_id;
		if ($csrId == '')
		{
			$csrId = 0;
		}
		$date		 = $this->cst_created;
		$dateString	 = DateTimeFormat::SQLDateTimeToDateTime($date)->format("Y/m/d");
		$path		 = "/{$dateString}/{$csrId}/{$id}_{$fileName}";
		return $path;
	}

	public function uploadToSpace($localFile, $spaceFile, $removeLocal = true)
	{
		$objSpaceFile = Storage::uploadFile(Storage::getAudioDocSpace(), $spaceFile, $localFile, $removeLocal);
		return $objSpaceFile;
	}

	/**
	 * Function for archiving call status
	 */
	public function archiveData($archiveDB, $upperLimit = 1000000, $lowerLimit = 1000)
	{
		$i			 = 0;
		$chk		 = true;
		$totRecords	 = $upperLimit;
		$limit		 = $lowerLimit;
		while ($chk)
		{
			Logger::writeToConsole("While");
			$transaction = DBUtil::beginTransaction();
			try
			{
				$sql	 = "SELECT GROUP_CONCAT(cst_call_id) AS cst_call_id FROM (SELECT cst_call_id FROM call_status WHERE 1 AND cst_created <= DATE_SUB(CURDATE(), INTERVAL 18 MONTH) AND cst_status IN (0,2,3) ORDER BY cst_call_id ASC LIMIT 0, $limit) as temp";
				$resQ	 = DBUtil::queryScalar($sql);
				if (!is_null($resQ) && $resQ != '')
				{
					Logger::writeToConsole("INSERT");
					DBUtil::getINStatement($resQ, $bindString, $params);
					$sql	 = "INSERT INTO " . $archiveDB . ".call_status (SELECT * FROM call_status WHERE cst_call_id IN ($bindString))";
					$rows	 = DBUtil::execute($sql, $params);
					if ($rows > 0)
					{
						$sql = "DELETE FROM `call_status` WHERE cst_call_id IN ($bindString)";
						DBUtil::execute($sql, $params);
					}
				}
				Logger::writeToConsole("COMMITTED");
				DBUtil::commitTransaction($transaction);
				$i += $limit;
				if (($resQ <= 0) || $totRecords <= $i)
				{
					break;
				}
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::writeToConsole("ERROR: " . $ex->getMessage());
				echo $ex->getMessage() . "\n\n";
			}
		}
	}

}

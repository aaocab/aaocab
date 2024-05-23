<?php

/**
 * This is the model class for table "agent_agreement".
 *
 * The followings are the available columns in table 'agent_agreement':
 * @property integer $aag_id
 * @property integer $aag_agt_id
 * @property string $aag_agmt_ver
 * @property string $aag_digital_ver
 * @property string $aag_digital_sign
 * @property string $aag_digital_device_id
 * @property string $aag_digital_ip
 * @property string $aag_digital_agreement
 * @property string $aag_draft_agreement
 * @property string $aag_digital_uuid
 * @property string $aag_digital_lat
 * @property string $aag_digital_long
 * @property string $aag_digital_os
 * @property integer $aag_digital_flag
 * @property string $aag_digital_date
 * @property integer $aag_active
 * @property integer $aag_digital_is_email
 * @property string $aag_create_at
 * @property string $aag_approved_at
 * @property integer $aap_approved_by
 * @property string $aap_remarks
 * 
 * The followings are the available model relations:
 * @property Agents $aagAgt
 */
class AgentAgreement extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'agent_agreement';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('aag_agt_id', 'required'),
			array('aag_agt_id, aag_digital_flag, aag_active, aag_digital_is_email, aap_approved_by', 'numerical', 'integerOnly' => true),
			array('aag_agmt_ver, aag_digital_ver, aag_digital_device_id, aag_digital_ip', 'length', 'max' => 50),
			array('aag_digital_sign', 'length', 'max' => 255),
			array('aag_draft_agreement, aag_digital_uuid, aag_digital_lat, aag_digital_long, aag_digital_os, aap_remarks', 'length', 'max' => 100),
			array('aag_digital_agreement, aag_digital_date, aag_create_at, aag_approved_at,aag_digital_is_email', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('aag_id, aag_agt_id, aag_agmt_ver, aag_digital_ver, aag_digital_sign, aag_digital_device_id, aag_digital_ip, aag_digital_agreement, aag_draft_agreement, aag_digital_uuid, aag_digital_lat, aag_digital_long, aag_digital_os, aag_digital_flag, aag_digital_date, aag_active, aag_digital_is_email, aag_create_at, aag_approved_at, aap_approved_by, aap_remarks', 'safe', 'on' => 'search'),
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
			'aagAgt' => array(self::BELONGS_TO, 'Agents', 'aag_agt_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'aag_id'				 => 'Aag',
			'aag_agt_id'			 => 'Aag Agt',
			'aag_agmt_ver'			 => 'Aag Agmt Ver',
			'aag_digital_ver'		 => 'Aag Digital Ver',
			'aag_digital_sign'		 => 'Aag Digital Sign',
			'aag_digital_device_id'	 => 'Aag Digital Device',
			'aag_digital_ip'		 => 'Aag Digital Ip',
			'aag_digital_agreement'	 => 'Aag Digital Agreement',
			'aag_draft_agreement'	 => 'Aag Draft Agreement',
			'aag_digital_uuid'		 => 'Aag Digital Uuid',
			'aag_digital_lat'		 => 'Aag Digital Lat',
			'aag_digital_long'		 => 'Aag Digital Long',
			'aag_digital_os'		 => 'Aag Digital Os',
			'aag_digital_flag'		 => 'Aag Digital Flag',
			'aag_digital_date'		 => 'Aag Digital Date',
			'aag_active'			 => 'Aag Active',
			'aag_digital_is_email'	 => 'Aag Digital Is Email',
			'aag_create_at'			 => 'Aag Create At',
			'aag_approved_at'		 => 'Aag Approved At',
			'aap_approved_by'		 => 'Aap Approved By',
			'aap_remarks'			 => 'Aap Remarks',
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

		$criteria->compare('aag_id', $this->aag_id);
		$criteria->compare('aag_agt_id', $this->aag_agt_id);
		$criteria->compare('aag_agmt_ver', $this->aag_agmt_ver, true);
		$criteria->compare('aag_digital_ver', $this->aag_digital_ver, true);
		$criteria->compare('aag_digital_sign', $this->aag_digital_sign, true);
		$criteria->compare('aag_digital_device_id', $this->aag_digital_device_id, true);
		$criteria->compare('aag_digital_ip', $this->aag_digital_ip, true);
		$criteria->compare('aag_digital_agreement', $this->aag_digital_agreement, true);
		$criteria->compare('aag_draft_agreement', $this->aag_draft_agreement, true);
		$criteria->compare('aag_digital_uuid', $this->aag_digital_uuid, true);
		$criteria->compare('aag_digital_lat', $this->aag_digital_lat, true);
		$criteria->compare('aag_digital_long', $this->aag_digital_long, true);
		$criteria->compare('aag_digital_os', $this->aag_digital_os, true);
		$criteria->compare('aag_digital_flag', $this->aag_digital_flag);
		$criteria->compare('aag_digital_date', $this->aag_digital_date, true);
		$criteria->compare('aag_active', $this->aag_active);
		$criteria->compare('aag_digital_is_email', $this->aag_digital_is_email);
		$criteria->compare('aag_create_at', $this->aag_create_at, true);
		$criteria->compare('aag_approved_at', $this->aag_approved_at, true);
		$criteria->compare('aap_approved_by', $this->aap_approved_by);
		$criteria->compare('aap_remarks', $this->aap_remarks, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AgentAgreement the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getByAgentID($agt_id)
	{
		$sql = "SELECT * FROM `agent_agreement` WHERE `aag_agt_id`='$agt_id'";
		return DBUtil::queryRow($sql);
	}

	public function saveAgreement($image, $imagetmp, $agentId)
	{
		try
		{
			$path = "";
			if ($image != '')
			{
				$path		 = Yii::app()->basePath;
				$fileName	 = $agentId . "-" . date('YmdHis') . "." . $image;
				
				// Attachments
				$dir		 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
				if (!is_dir($dir))
				{
					mkdir($dir);
				}
				
				// Server Id
				$serverId			 = Config::getServerID();
				$serverFolderName	 = $dir . DIRECTORY_SEPARATOR . $serverId;
				if (!is_dir($serverFolderName))
				{
					mkdir($serverFolderName);
				}
				
				// Agent Id
				$dirByAgentId = $serverFolderName . DIRECTORY_SEPARATOR . $agentId;
				if (!is_dir($dirByAgentId))
				{
					mkdir($dirByAgentId);
				}
				
				$file_path	 = $dirByAgentId . DIRECTORY_SEPARATOR . $image;
				$folder_path = $dirByAgentId . DIRECTORY_SEPARATOR;
			}
			$file_name = basename($image);
			file_put_contents($file_path, file_get_contents($imagetmp));  // parameter1=> target, parameter2 => source
			#Yii::log("Image Path: \n\t Temp: " . $imagetmp . "\n\t Path: " . $file_path, CLogger::LEVEL_INFO, 'system.api.images');
			if (Vehicles::model()->img_resize($imagetmp, 3500, $folder_path, $file_name))
			{
				$path = substr($file_path, strlen(PUBLIC_PATH));
				$result = ['path' => $path];
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

	public function updateSignature($agtId, $digSign = '')
	{
		if ($digSign != '')
		{
			$digDate = DATE('Y-m-d H:i:s');
			$sql	 = "INSERT INTO `agent_agreement`( `aag_agt_id`,  `aag_digital_sign`, `aag_digital_flag`, `aag_digital_date`) VALUES ($agtId, '$digSign', '1', '$digDate')"
					. "ON DUPLICATE KEY "
					. "UPDATE aag_agt_id=$agtId, aag_digital_sign='$digSign', aag_digital_date='$digDate'";
			$rowsUpdated = DBUtil::command($sql)->execute();
			return $result		 = ($rowsUpdated > 0) ? 1 : 0;
		}
	}

	public function updateAgreement($agtId, $digitalLat, $digitalLong, $digitalVer)
	{
		$sql		 = "UPDATE `agent_agreement` SET `aag_digital_ver`=$digitalVer,`aag_digital_lat`=$digitalLat,`aag_digital_long`=$digitalLong,`aag_digital_flag`= 1 ,`aag_active`= 1,`aag_digital_is_email`=0 WHERE agent_agreement.aag_agt_id = $agtId";
		$rowsUpdated = DBUtil::command($sql)->execute();
		$return		 = ($rowsUpdated > 0) ? true : false;
		return $return;
	}
        public function emailForAgreementCopy($agentId)
	{
		$success = false;
		$model	 = Agents::model()->findByPk($agentId);
		/* var @model $model */
		$email	 = $model->agt_email;
		if ($email != '')
		{
			$agmtModel = $model->agtAag;
                        $agreementLink = Yii::app()->params['fullBaseURL']."/agent/users/cpagreement?agtid=".$agentId;
			$digitalAgmtLink = "<a href='" .$agreementLink. "' target'_blank'>Click for Digital Agreement</a>";
			

			$isAgreement = 1;
			$subject1	 = 'Your copy of updated GozoCabs Channel Partner Agreement dated: ' . date("d/m/Y", strtotime(DATE('Y-m-d'))) . '';
			$emailBody1	 = 'Dear ' . $model->agt_fname." ".$model->agt_lname. ',<br/><br/>
                                You have just accepted the attached version of our channel partner agreement. 
                                <br/><br/>This agreement was digitally signed and accepted by
                                <br/>Name : ' . $model->agt_fname." ".$model->agt_lname.'
                                <br/><br/>Agreement Link : ' . $digitalAgmtLink . '
                                <br/><br/>For any clarifications, please call us at 033-66283910 or email partners@gozocabs.in <mailto:partners@gozocabs.in>
                                <br/><br/>Thanks,
                                <br/><br/>Gozocabs';

			$emailCom = new emailWrapper();
			$emailCom->agentAgreementEmail($subject1, $emailBody1, $email, $ledgerPdf, $invoicePdf, $vendorId, $attachments, EmailLog::EMAIL_AGENT_AGREEMENT, $isAgreement);
			$agmtModel->aag_digital_is_email= 1;
                       
			$agmtModel->update();
			$success = true;
			Logger::create("Agreements has been sent (AGENT ID: $agentId)", CLogger::LEVEL_INFO);
		}
		else
		{
			Logger::create("Agreements not sent. Email ID is missing (AGENT ID: $agentId)", CLogger::LEVEL_WARNING);
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
			$condFilePath = " AND (aag_s3_data IS NULL AND aag_digital_sign LIKE '%/attachments/{$serverId}/%') ";

			$sql = "SELECT aag_id FROM agent_agreement WHERE aag_agt_id > 0 {$condFilePath} ORDER BY aag_id DESC LIMIT 0, $limit1";
			$res = DBUtil::query($sql);
			if ($res->getRowCount() == 0)
			{
				break;
			}
			foreach ($res as $row)
			{
				$agtAgreementModel = AgentAgreement::model()->findByPk($row['aag_id']);

				$agtAgreementModel->uploadToS3();

				Logger::writeToConsole($agtAgreementModel->aag_s3_data);
			}

			$limit -= $limit1;
			Logger::flush();
		}
	}

    /** @return Stub\common\SpaceFile */
	public function uploadToS3($removeLocal = true)
	{
		$spaceFile = null;
		try
		{
			$agtAgreementModel	 = $this;
			$path		 = $this->getLocalPath();

			if (!file_exists($path) || $this->aag_digital_sign == '')
			{
				if ($agtAgreementModel->aag_s3_data == '')
				{
					$agtAgreementModel->aag_s3_data = "{}";
					$agtAgreementModel->save();
				}
				return null;
			}
			$spaceFile = $agtAgreementModel->uploadToSpace($path, $this->getSpacePath($path), $removeLocal);

			$agtAgreementModel->aag_s3_data = $spaceFile->toJSON();
			$agtAgreementModel->save();
		}
		catch (Exception $exc)
		{
			ReturnSet::setException($exc);
		}
		return $spaceFile;
	}

    public function getLocalPath()
	{
		$filePath = $this->aag_digital_sign;

		$filePath = implode("/", explode(DIRECTORY_SEPARATOR, $filePath));

		$filePath = ltrim($filePath, '/attachments');
		
		$filePath = implode(DIRECTORY_SEPARATOR, explode("/", $filePath));
		
		$filePath = $this->getBaseDocPath() . $filePath;

		if (!file_exists($filePath))
		{
			$filePath = APPLICATION_PATH . $this->aag_digital_sign;
		}

		return $filePath;
	}
    
    public function getBaseDocPath()
	{
		return PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR;
	}

    /**
	 * @return Stub\common\SpaceFile
	 */
	public function uploadToSpace($localFile, $spaceFile, $removeLocal = true)
	{
		$objSpaceFile = Storage::uploadFile(Storage::getAgentAgreementSpace(), $spaceFile, $localFile, $removeLocal);
		return $objSpaceFile;
	}

    public function getSpacePath($localPath)
	{
		$fileName	 = basename($localPath);
		$id			 = $this->aag_id;
		$date		 = $this->aag_digital_date;
		$dateString	 = DateTimeFormat::SQLDateTimeToDateTime($date)->format("Y/m/d");
		$path		 = "/agent/agreement/{$dateString}/{$id}_{$fileName}";
		return $path;
	}
}

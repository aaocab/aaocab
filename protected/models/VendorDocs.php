<?php

/**
 * This is the model class for table "vendor_docs".
 *
 * The followings are the available columns in table 'vendor_docs':
 * @property integer $vd_id
 * @property integer $vd_vnd_id
 * @property integer $vd_type
 * @property integer $vd_sub_type
 * @property string $vd_file
 * @property string $vd_remarks
 * @property integer $vd_status
 * @property integer $vd_active
 * @property integer $vd_agmt_is_accept
 * @property string $vd_agmt_accept_date
 * @property integer $vd_agmt_is_ver
 * @property string $vd_created_at
 * @property string $vd_approved_at
 * @property integer $vd_approved_by
 *
 * The followings are the available model relations:
 * @property Vendors $vdVnd
 */
class VendorDocs extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vendor_docs';
	}

	public $docType			 = [
		1	 => 'Agreement File',
		2	 => 'Voter ID',
		3	 => 'Aadhaar',
		4	 => 'PAN Card',
		5	 => 'Licence',
		6	 => 'Memorendum'
	];
	public $docSubType		 = [
		1	 => 'Front Side',
		2	 => 'Back Side'
	];
	public $docTypeName		 = [
		1	 => 'agreement',
		2	 => 'voter',
		3	 => 'aadhaar',
		4	 => 'pan',
		5	 => 'licence',
		6	 => 'memorendum'
	];
	public $docSubTypeName	 = [
		1	 => 'front',
		2	 => 'back'
	];
	public $vndname;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
		return array(
			array('vd_file', 'length', 'max' => 255),
			array('vd_vnd_id, vd_file, vd_type', 'required', 'on' => 'saveDoc'),
			array('vd_remarks', 'required', 'on' => 'reject'),
			//array('vd_file', 'file', 'types' => 'jpg, gif, png', 'allowEmpty' => true),
			// The following rule is used by search().
// @todo Please remove those attributes that should not be searched.
			array('vd_id, vd_vnd_id, vd_type, vd_sub_type, vd_file, vd_remarks, vd_status, vndname,vd_active, vd_agmt_is_accept, vd_agmt_accept_date, vd_agmt_is_ver, vd_created_at, vd_approved_at, vd_approved_by', 'safe'),
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
			'vdVnd' => array(self::BELONGS_TO, 'Vendors', 'vd_vnd_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'vd_id'					 => 'Vd',
			'vd_vnd_id'				 => 'Vnd',
			'vd_type'				 => 'Type',
			'vd_sub_type'			 => 'Sub Type',
			'vd_file'				 => 'File',
			'vd_remarks'			 => 'Remarks',
			'vd_status'				 => 'Status',
			'vd_active'				 => 'Active',
			'vd_agmt_is_accept'		 => 'Agmt Is Accept',
			'vd_agmt_accept_date'	 => 'Agmt Accept Date',
			'vd_agmt_is_ver'		 => 'Agmt Is Ver',
			'vd_created_at'			 => 'Created At',
			'vd_approved_at'		 => 'Approved At',
			'vd_approved_by'		 => 'Approved By',
			'vndname'				 => 'Vendor Name'
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

		$criteria->compare('vd_id', $this->vd_id);
		$criteria->compare('vd_vnd_id', $this->vd_vnd_id);
		$criteria->compare('vd_type', $this->vd_type);
		$criteria->compare('vd_sub_type', $this->vd_sub_type);
		$criteria->compare('vd_file', $this->vd_file, true);
		$criteria->compare('vd_remarks', $this->vd_remarks, true);
		$criteria->compare('vd_status', $this->vd_status);
		$criteria->compare('vd_active', $this->vd_active);
		$criteria->compare('vd_agmt_is_accept', $this->vd_agmt_is_accept);
		$criteria->compare('vd_agmt_accept_date', $this->vd_agmt_accept_date, true);
		$criteria->compare('vd_agmt_is_ver', $this->vd_agmt_is_ver);
		$criteria->compare('vd_created_at', $this->vd_created_at, true);
		$criteria->compare('vd_approved_at', $this->vd_approved_at, true);
		$criteria->compare('vd_approved_by', $this->vd_approved_by);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VendorDocs the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function docType()
	{
		$arr[1][0]	 = 'Agreement File';
		$arr[2][1]	 = 'Voter Front';
		$arr[2][2]	 = 'Voter Back';
		$arr[3][1]	 = 'Aadhaar Front';
		$arr[3][2]	 = 'Aadhaar Back';
		$arr[4][1]	 = 'Pan Front';
		$arr[4][2]	 = 'Pan Back';
		$arr[5][1]	 = 'Licence Front';
		$arr[5][2]	 = 'Licence Back';
		$arr[6][0]	 = 'Memorandum';
		return $arr;
	}

	public function allowedDocType()
	{
		$allowedVndDoctype = [1 => [0], 2 => [1, 2], 3 => [1, 2], 4 => [1, 2], 5 => [1, 2], 6 => [0]];
		return $allowedVndDoctype;
	}

	public function findAllByVndId($vnd_id)
	{
		$sql = "SELECT voter.doc_file_front_path AS voterFontPath,voter.doc_file_back_path AS voterBackPath,
				pan.doc_file_front_path AS panFontPath,pan.doc_file_back_path AS panBackPath,
				aadher.doc_file_front_path AS aadherFontPath,aadher.doc_file_back_path AS aadherBackPath,
				licence.doc_file_front_path AS licenceFontPath,licence.doc_file_back_path AS licenceBackPath,
				memo.doc_file_front_path AS memoFontPath,
				vendor_agreement.vag_soft_path AS agreementPath
				FROM vendors 
				INNER JOIN contact_profile cp ON cp.cr_is_vendor = vendors.vnd_id AND cp.cr_status = 1
				INNER JOIN contact ON contact.ctt_id = cp.cr_contact_id AND contact.ctt_id = contact.ctt_ref_code AND contact.ctt_active = 1
				LEFT JOIN document voter ON voter.doc_id=contact.ctt_voter_doc_id
				LEFT JOIN document pan ON pan.doc_id=contact.ctt_pan_doc_id
				LEFT JOIN document aadher ON aadher.doc_id=contact.ctt_aadhar_doc_id
				LEFT JOIN document licence ON licence.doc_id=contact.ctt_license_doc_id
				LEFT JOIN document memo ON memo.doc_id=contact.ctt_memo_doc_id
				LEFT JOIN vendor_agreement ON vendor_agreement.vag_vnd_id=vendors.vnd_id
				WHERE 
				vendors.vnd_id='$vnd_id' AND vendors.vnd_active=1";
		return DBUtil::queryRow($sql);
	}

	public function updateExistingByIdType($vndId, $vdType, $vdSubType = '')
	{
		$sql		 = "UPDATE `vendor_docs` SET vendor_docs.vd_active=0 WHERE vendor_docs.vd_vnd_id=$vndId AND vendor_docs.vd_type=$vdType AND vendor_docs.vd_active=1";
		$sql		 .= ($vdSubType != '' && $vdSubType != NULL) ? " AND vendor_docs.vd_sub_type=$vdSubType" : '';
		$cdb		 = DBUtil::command($sql);
		$rowsUpdated = $cdb->execute();
		return $rowsUpdated;
	}

	public function saveVoterId($vendorId, $path, UserInfo $userInfo = null)
	{
		$success = false;
		try
		{
			if ($path != '' && $vendorId != '')
			{
				$model					 = new VendorDocs();
				$this->updateExistingByIdType($vendorId, 2, 1);
				$model->scenario		 = 'saveDoc';
				$model->vd_type			 = 2;
				$model->vd_sub_type		 = 1;
				$model->vd_file			 = $path;
				$model->vd_vnd_id		 = $vendorId;
				$model->vd_approved_at	 = NULL;
				$model->vd_approved_by	 = NULL;
				if ($model->validate())
				{
					if ($model->save())
					{
						$success	 = true;
						$errors		 = [];
						$event_id	 = VendorsLog::VENDOR_FILE_UPLOAD;
						$logDesc	 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_VOTERID_UPLOAD);
						VendorsLog::model()->createLog($vendorId, $logDesc, $userInfo, $event_id, false, false);
					}
					else
					{
						$getErrors = $model->getErrors();
						throw new Exception(json_encode($getErrors));
					}
				}
				else
				{
					$getErrors = $model->getErrors();
					throw new Exception(json_encode($getErrors));
				}
			}
		}
		catch (Exception $ex)
		{
			$errors = $ex->getMessage();
		}
		return $success;
	}

	public function saveVoterBackId($vendorId, $path, UserInfo $userInfo = null)
	{
		$success = false;
		try
		{
			if ($path != '' && $vendorId != '')
			{
				$model					 = new VendorDocs();
				$model->scenario		 = 'saveDoc';
				$this->updateExistingByIdType($vendorId, 2, 2);
				$model->vd_type			 = 2;
				$model->vd_sub_type		 = 2;
				$model->vd_file			 = $path;
				$model->vd_vnd_id		 = $vendorId;
				$model->vd_approved_at	 = NULL;
				$model->vd_approved_by	 = NULL;
				if ($model->validate())
				{
					if ($model->save())
					{
						$success	 = true;
						$errors		 = [];
						$event_id	 = VendorsLog::VENDOR_FILE_UPLOAD;
						$logDesc	 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_VOTERID_BACK_UPLOAD);
						VendorsLog::model()->createLog($vendorId, $logDesc, $userInfo, $event_id, false, false);
					}
					else
					{
						$getErrors = $model->getErrors();
						throw new Exception(json_encode($getErrors));
					}
				}
				else
				{
					$getErrors = $model->getErrors();
					throw new Exception(json_encode($getErrors));
				}
			}
		}
		catch (Exception $ex)
		{
			$errors = $ex->getMessage();
		}
		return $success;
	}

	public function savePanId($vendorId, $path, UserInfo $userInfo = null)
	{
		$success = false;
		try
		{
			if ($path != '' && $vendorId != '')
			{
				$model					 = new VendorDocs();
				$model->scenario		 = 'saveDoc';
				$this->updateExistingByIdType($vendorId, 4, 1);
				$model->vd_type			 = 4;
				$model->vd_sub_type		 = 1;
				$model->vd_file			 = $path;
				$model->vd_vnd_id		 = $vendorId;
				$model->vd_approved_at	 = NULL;
				$model->vd_approved_by	 = NULL;
				if ($model->validate())
				{
					if ($model->save())
					{
						$success	 = true;
						$errors		 = [];
						$event_id	 = VendorsLog::VENDOR_FILE_UPLOAD;
						$logDesc	 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_PAN_UPLOAD);
						VendorsLog::model()->createLog($vendorId, $logDesc, $userInfo, $event_id, false, false);
					}
					else
					{
						$getErrors = $model->getErrors();
						throw new Exception(json_encode($getErrors));
					}
				}
				else
				{
					$getErrors = $model->getErrors();
					throw new Exception(json_encode($getErrors));
				}
			}
		}
		catch (Exception $ex)
		{
			$errors = $ex->getMessage();
		}

		return $success;
	}

	public function savePanBackId($vendorId, $path, UserInfo $userInfo = null)
	{
		$success = false;
		try
		{
			if ($path != '' && $vendorId != '')
			{
				$model					 = new VendorDocs();
				$model->scenario		 = 'saveDoc';
				$this->updateExistingByIdType($vendorId, 4, 2);
				$model->vd_type			 = 4;
				$model->vd_sub_type		 = 2;
				$model->vd_file			 = $path;
				$model->vd_vnd_id		 = $vendorId;
				$model->vd_approved_at	 = NULL;
				$model->vd_approved_by	 = NULL;
				if ($model->validate())
				{
					if ($model->save())
					{
						$success	 = true;
						$errors		 = [];
						$event_id	 = VendorsLog::VENDOR_FILE_UPLOAD;
						$logDesc	 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_PAN_BACK_UPLOAD);
						VendorsLog::model()->createLog($vendorId, $logDesc, $userInfo, $event_id, false, false);
					}
					else
					{
						$getErrors = $model->getErrors();
						throw new Exception(json_encode($getErrors));
					}
				}
				else
				{
					$getErrors = $model->getErrors();
					throw new Exception(json_encode($getErrors));
				}
			}
		}
		catch (Exception $ex)
		{
			$errors = $ex->getMessage();
		}
		return $success;
	}

	public function saveAadharId($vendorId, $path, UserInfo $userInfo = null)
	{
		$success = false;
		try
		{
			if ($path != '' && $vendorId != '')
			{
				$model					 = new VendorDocs();
				$model->scenario		 = 'saveDoc';
				$this->updateExistingByIdType($vendorId, 3, 1);
				$model->vd_type			 = 3;
				$model->vd_sub_type		 = 1;
				$model->vd_file			 = $path;
				$model->vd_vnd_id		 = $vendorId;
				$model->vd_approved_at	 = NULL;
				$model->vd_approved_by	 = NULL;
				if ($model->validate())
				{
					if ($model->save())
					{
						$success	 = true;
						$errors		 = [];
						$event_id	 = VendorsLog::VENDOR_FILE_UPLOAD;
						$logDesc	 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_AADHAAR_UPLOAD);
						VendorsLog::model()->createLog($vendorId, $logDesc, $userInfo, $event_id, false, false);
					}
					else
					{
						$getErrors = $model->getErrors();
						throw new Exception(json_encode($getErrors));
					}
				}
				else
				{
					$getErrors = $model->getErrors();
					throw new Exception(json_encode($getErrors));
				}
			}
		}
		catch (Exception $ex)
		{

			$errors = $ex->getMessage();
		}

		return $success;
	}

	public function saveAadharBackId($vendorId, $path, UserInfo $userInfo = null)
	{
		$success = false;
		try
		{
			if ($path != '' && $vendorId != '')
			{
				$model					 = new VendorDocs();
				$model->scenario		 = 'saveDoc';
				$this->updateExistingByIdType($vendorId, 3, 2);
				$model->vd_type			 = 3;
				$model->vd_sub_type		 = 2;
				$model->vd_file			 = $path;
				$model->vd_vnd_id		 = $vendorId;
				$model->vd_approved_at	 = NULL;
				$model->vd_approved_by	 = NULL;
				if ($model->validate())
				{
					if ($model->save())
					{
						$success	 = true;
						$errors		 = [];
						$event_id	 = VendorsLog::VENDOR_FILE_UPLOAD;
						$logDesc	 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_AADHAAR_BACK_UPLOAD);
						VendorsLog::model()->createLog($vendorId, $logDesc, $userInfo, $event_id, false, false);
					}
					else
					{
						$getErrors = $model->getErrors();
						throw new Exception(json_encode($getErrors));
					}
				}
				else
				{
					$getErrors = $model->getErrors();
					throw new Exception(json_encode($getErrors));
				}
			}
		}
		catch (Exception $ex)
		{
			$errors = $ex->getMessage();
		}
		return $success;
	}

	public function saveLicenceFront($vendorId, $path, UserInfo $userInfo = null)
	{
		$success = false;
		try
		{
			if ($path != '' && $vendorId != '')
			{
				$model					 = new VendorDocs();
				$model->scenario		 = 'saveDoc';
				$this->updateExistingByIdType($vendorId, 5, 1);
				$model->vd_type			 = 5;
				$model->vd_sub_type		 = 1;
				$model->vd_file			 = $path;
				$model->vd_vnd_id		 = $vendorId;
				$model->vd_approved_at	 = NULL;
				$model->vd_approved_by	 = NULL;
				if ($model->validate())
				{
					if ($model->save())
					{
						$success	 = true;
						$errors		 = [];
						$event_id	 = VendorsLog::VENDOR_FILE_UPLOAD;
						$logDesc	 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_LICENSE_UPLOAD);
						VendorsLog::model()->createLog($vendorId, $logDesc, $userInfo, $event_id, false, false);
					}
					else
					{
						$getErrors = $model->getErrors();
						throw new Exception(json_encode($getErrors));
					}
				}
				else
				{
					$getErrors = $model->getErrors();
					throw new Exception(json_encode($getErrors));
				}
			}
		}
		catch (Exception $ex)
		{
			$errors = $ex->getMessage();
		}
		return $success;
	}

	public function saveLicenceBack($vendorId, $path, UserInfo $userInfo = null)
	{
		$success = false;
		try
		{
			if ($path != '' && $vendorId != '')
			{
				$model					 = new VendorDocs();
				$model->scenario		 = 'saveDoc';
				$this->updateExistingByIdType($vendorId, 5, 2);
				$model->vd_type			 = 5;
				$model->vd_sub_type		 = 2;
				$model->vd_file			 = $path;
				$model->vd_vnd_id		 = $vendorId;
				$model->vd_approved_at	 = NULL;
				$model->vd_approved_by	 = NULL;
				if ($model->validate())
				{
					if ($model->save())
					{
						$success	 = true;
						$errors		 = [];
						$event_id	 = VendorsLog::VENDOR_FILE_UPLOAD;
						$logDesc	 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_LICENSE_BACK_UPLOAD);
						VendorsLog::model()->createLog($vendorId, $logDesc, $userInfo, $event_id, false, false);
					}
					else
					{
						$getErrors = $model->getErrors();
						throw new Exception(json_encode($getErrors));
					}
				}
				else
				{
					$getErrors = $model->getErrors();
					throw new Exception(json_encode($getErrors));
				}
			}
		}
		catch (Exception $ex)
		{
			$errors = $ex->getMessage();
		}
		return $success;
	}

	public function saveMemorandum($vendorId, $path, UserInfo $userInfo = null)
	{
		$success = false;
		try
		{
			if ($path != '' && $vendorId != '')
			{
				$model					 = new VendorDocs();
				$model->scenario		 = 'saveDoc';
				$this->updateExistingByIdType($vendorId, 6);
				$model->vd_type			 = 6;
				$model->vd_file			 = $path;
				$model->vd_vnd_id		 = $vendorId;
				$model->vd_approved_at	 = NULL;
				$model->vd_approved_by	 = NULL;
				if ($model->validate())
				{
					if ($model->save())
					{
						$success	 = true;
						$errors		 = [];
						$event_id	 = VendorsLog::VENDOR_FILE_UPLOAD;
						$logDesc	 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_MEMORANDUM_UPLOAD);
						VendorsLog::model()->createLog($vendorId, $logDesc, $userInfo, $event_id, false, false);
					}
					else
					{
						$getErrors = $model->getErrors();
						throw new Exception(json_encode($getErrors));
					}
				}
				else
				{
					$getErrors = $model->getErrors();
					throw new Exception(json_encode($getErrors));
				}
			}
		}
		catch (Exception $ex)
		{
			$errors = $ex->getMessage();
		}
		return $success;
	}

	public function rejectDocument($vd_id, $remarks, UserInfo $userInfo = null)
	{
		$success	 = false;
		$transaction = Yii::app()->db->beginTransaction();
		try
		{
			$model = VendorDocs::model()->findByPk($vd_id);
			if ($model->vd_type == 1)
			{
				$event_id	 = VendorsLog::VENDOR_AGREEMENT_REJECT;
				$fileType	 = "#agreement";
			}
			else if ($model->vd_type == 2 && $model->vd_sub_type == 1)
			{
				$event_id	 = VendorsLog::VENDOR_VOTERID_REJECT;
				$fileType	 = "#voterid";
			}
			else if ($model->vd_type == 2 && $model->vd_sub_type == 2)
			{
				$event_id	 = VendorsLog::VENDOR_VOTERID_BACK_REJECT;
				$fileType	 = "#voterbackid";
			}
			else if ($model->vd_type == 3 && $model->vd_sub_type == 1)
			{
				$event_id	 = VendorsLog::VENDOR_AADHAAR_REJECT;
				$fileType	 = "#aadhaarid";
			}
			else if ($model->vd_type == 3 && $model->vd_sub_type == 2)
			{
				$event_id	 = VendorsLog::VENDOR_AADHAAR_BACK_REJECT;
				$fileType	 = "#aadhaarbackid";
			}
			else if ($model->vd_type == 4 && $model->vd_sub_type == 1)
			{
				$event_id	 = VendorsLog::VENDOR_PAN_REJECT;
				$fileType	 = "#panid";
			}
			else if ($model->vd_type == 4 && $model->vd_sub_type == 2)
			{
				$event_id	 = VendorsLog::VENDOR_PAN_BACK_REJECT;
				$fileType	 = "#panbackid";
			}
			else if ($model->vd_type == 5 && $model->vd_sub_type == 1)
			{
				$event_id	 = VendorsLog::VENDOR_LICENSE_REJECT;
				$fileType	 = "#licence";
			}
			else if ($model->vd_type == 5 && $model->vd_sub_type == 2)
			{
				$event_id	 = VendorsLog::VENDOR_LICENSE_BACK_REJECT;
				$fileType	 = "#licenceback";
			}
			else if ($model->vd_type == 6)
			{
				$event_id	 = VendorsLog::VENDOR_MEMORANDUM_REJECT;
				$fileType	 = "#memorandum";
			}
			$model->vd_remarks	 = $remarks;
			$model->vd_status	 = 2;
			if ($model->save())
			{
				if ($model->vd_type == 1)
				{
					$agmtModel					 = VendorAgreement::model()->findByVndId($model->vd_vnd_id);
					$agmtModel->vag_soft_flag	 = 0;
					$agmtModel->vag_soft_path	 = NULL;
					$agmtModel->vag_soft_ver	 = NULL;
					$agmtModel->vag_soft_date	 = NULL;
					if ($agmtModel->save())
					{
						VendorsLog::model()->createLog($model->vd_vnd_id, $remarks, $userInfo, $event_id, false, false);
						$success = true;
						$transaction->commit();
					}
					else
					{
						$errors = "Agreement not yet saved.\n\t\t" . json_encode($agmtModel->getErrors());
						throw new Exception($errors);
					}
				}
				else
				{
					VendorsLog::model()->createLog($model->vd_vnd_id, $remarks, $userInfo, $event_id, false, false);
					$success = true;
					$transaction->commit();
				}
			}
			else
			{
				$errors = "data not yet saved.\n\t\t" . json_encode($model->getErrors());
				throw new Exception($errors);
			}
		}
		catch (Exception $e)
		{
			$transaction->rollback();
		}
		$returnArray = array('success' => $success, 'fileType' => $fileType);
		return $returnArray;
	}

	public function saveDocument($vendorId, $path, UserInfo $userInfo = null, $doc_type, $agreement_date = '')
	{
		$success = false;
		if ($path != '' && $vendorId != '')
		{
			$this->setTypeByDocumentType($doc_type);
			$this->updateExistingByIdType($vendorId, $this->vd_type, $this->vd_sub_type);
			$this->vd_vnd_id		 = $vendorId;
			$this->vd_file			 = $path;
			$this->vd_approved_at	 = NULL;
			$this->vd_approved_by	 = NULL;
			if ($this->save())
			{
				if ($this->vd_type == 1)
				{
					/* @var $model Vendors */
					$model		 = Vendors::model()->findByPk($vendorId);
					/* @var $modelAgmt VendorAgreement */
					$modelAgmt	 = VendorAgreement::model()->findByVndId($vendorId);
					if ($modelAgmt == '')
					{
						/* @var $modelAgmt2 VendorAgreement */
						$modelAgmt2					 = new VendorAgreement();
						$modelAgmt2->vag_vnd_id		 = $vendorId;
						$modelAgmt2->vag_soft_date	 = $agreement_date;
						$modelAgmt2->vag_soft_path	 = $path;
						$modelAgmt2->vag_soft_flag	 = 1;
						$modelAgmt2->vag_soft_ver	 = Yii::app()->params['digitalagmtversion'];
						$modelAgmt2->save();
					}
					else
					{
						$modelAgmt->vag_soft_path	 = $path;
						$modelAgmt->vag_soft_date	 = $agreement_date;
						$modelAgmt->vag_soft_flag	 = 1;
						$modelAgmt->vag_soft_ver	 = $modelAgmt->vag_digital_ver;
						$modelAgmt->save();
					}
					$model->vnd_agreement_date = $agreement_date;
					$model->save();
				}
				$success	 = true;
				$event_id	 = VendorsLog::VENDOR_FILE_UPLOAD;
				$logArray	 = VendorsLog::model()->getLogByDocumentType($doc_type);
				$logDesc	 = VendorsLog::model()->getEventByEventId($logArray['upload']);
				VendorsLog::model()->createLog($vendorId, $logDesc, $userInfo, $event_id, false, false);
			}
			return $success;
		}
	}

	public function saveAgreementFile($vendorId, $path, UserInfo $userInfo = null, $agreement_date = '')
	{
		$success = false;
		if ($path != '' && $vendorId != '')
		{
			$this->updateExistingByIdType($vendorId, 1);
			$model					 = Vendors::model()->findByPk($vendorId);
			$this->vd_type			 = 1;
			$this->vd_file			 = $path;
			$this->vd_vnd_id		 = $vendorId;
			$this->vd_approved_at	 = NULL;
			$this->vd_approved_by	 = NULL;
			if ($this->save())
			{
				/* @var $modelAgmt VendorAgreement */
				$modelAgmt = VendorAgreement::model()->findByVndId($vendorId);
				if ($modelAgmt == '')
				{
					/* @var $modelAgmt2 VendorAgreement */
					$modelAgmt2					 = new VendorAgreement();
					$modelAgmt2->vag_vnd_id		 = $vendorId;
					$modelAgmt2->vag_soft_date	 = $agreement_date;
					$modelAgmt2->vag_soft_path	 = $path;
					$modelAgmt2->vag_soft_flag	 = 1;
					$modelAgmt2->vag_soft_ver	 = Yii::app()->params['digitalagmtversion'];
					$modelAgmt2->save();
				}
				else
				{
					$modelAgmt->vag_soft_path	 = $path;
					$modelAgmt->vag_soft_date	 = $agreement_date;
					$modelAgmt->vag_soft_flag	 = 1;
					$modelAgmt->vag_soft_ver	 = $modelAgmt->vag_digital_ver;
					$modelAgmt->save();
				}
				$success	 = true;
				$errors		 = [];
				$event_id	 = VendorsLog::VENDOR_FILE_UPLOAD;
				$logDesc	 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_AGREMENT_UPLOAD);
				VendorsLog::model()->createLog($vendorId, $logDesc, $userInfo, $event_id, false, false);
			}
			$model->vnd_agreement_date = $agreement_date;
			$model->save();
			return $success;
		}
		else
		{
			return $success;
		}
	}

	

	public function setTypeByDocumentType($type = 'agreement')
	{
		switch ($type)
		{
			case 'agreement':
				$this->vd_type		 = 1;
				$this->vd_sub_type	 = NULL;
				break;
			case 'voterid':
				$this->vd_type		 = 2;
				$this->vd_sub_type	 = 1;
				break;
			case 'voterbackid':
				$this->vd_type		 = 2;
				$this->vd_sub_type	 = 2;
				break;
			case 'adhar':
				$this->vd_type		 = 3;
				$this->vd_sub_type	 = 1;
				break;
			case 'adharback':
				$this->vd_type		 = 3;
				$this->vd_sub_type	 = 2;
				break;
			case 'pan':
				$this->vd_type		 = 4;
				$this->vd_sub_type	 = 1;
				break;
			case 'panback':
				$this->vd_type		 = 4;
				$this->vd_sub_type	 = 2;
				break;
			case 'license':
				$this->vd_type		 = 5;
				$this->vd_sub_type	 = 1;
				break;
			case 'licenseback':
				$this->vd_type		 = 5;
				$this->vd_sub_type	 = 2;
				break;
			case 'memorandum':
				$this->vd_type		 = 6;
				$this->vd_sub_type	 = NULL;
				break;
		}
	}

	public function getUnapproved($arr = [], $command = false)
	{
		$where = '';
		if (trim($arr['vndname']) != '')
		{
			$where .= "  AND LOWER(REPLACE(vnd.vnd_name,' ','')) LIKE '%" . strtolower(str_replace(' ', '', $arr['vndname'])) . "%'";
		}
		if (trim($arr['vd_type']) != '')
		{
			$where .= "  AND vd.vd_type = " . $arr['vd_type'];
		}
		if (trim($arr['vd_sub_type']) != '')
		{
			$where .= "  AND vd.vd_sub_type = " . $arr['vd_sub_type'];
		}
		$sql			 = "SELECT vd.*, vnd.*,
            if(bkg_id > 0, 1,0) hasBooking,
            if(bkg_pickup_date > NOW(),1,0) futureBooking,   bkg_id
                FROM vendor_docs vd
                JOIN vendors vnd ON vnd.vnd_id = vd.vd_vnd_id
                left JOIN booking_cab bcb ON bcb.bcb_vendor_id = vnd.vnd_id AND bcb.bcb_id IS NOT NULL
                left JOIN booking bkg ON bcb.bcb_id = bkg.bkg_bcb_id
                WHERE vd_status = 0 AND vd_active = 1 AND  vnd_active = 1 AND
                vd_file IS NOT NULL AND
                vd_file <> ''
                $where
                    GROUP BY vd.vd_id
                ";
		$defaultOrder	 = 'futureBooking DESC,hasBooking DESC,bkg.bkg_pickup_date ASC, vd_created_at asc';

		if ($command == false)
		{

			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['vndname'],
					'defaultOrder'	 => $defaultOrder],
				'pagination'	 => ['pageSize' => 20],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::queryAll($sql);
		}
	}

	public function getDocType()
	{
		$list = $this->docType;
		return $list[$this->vd_type];
	}

	public function getDocSubType()
	{
		$list = $this->docSubType;
		return $list[$this->vd_sub_type];
	}

	public function getDocTypeList()
	{
		$list = $this->docType;
		return $list;
	}

	public function checkApproveDocByVndId($vnd_id, $vnd_type)
	{
		$this->setTypeByDocumentType($vnd_type);
		$type		 = $this->vd_type;
		$sub_type	 = $this->vd_sub_type;
		$sql		 = "SELECT
                    IF(vendor_docs.vd_id>0,1,0) as check_approve
                    FROM
                    vendor_docs
                    WHERE
                    vendor_docs.vd_vnd_id = $vnd_id
                    AND vendor_docs.vd_type = $type
                    AND vendor_docs.vd_active = 1
                    AND vendor_docs.vd_status = 1";
		$sql		 .= ($type != 6) ? ' AND vendor_docs.vd_sub_type = ' . $sub_type . '' : '';
		$valApprove	 = DBUtil::command($sql)->queryScalar();
		$valApprove	 = ($valApprove > 0) ? $valApprove : 0;
		return $valApprove;
	}

	public function documentCheckedByVendor($model)
	{
		$vndid	 = $model->vnd_id;
		$venDocs = VendorDocs::model()->findAllByVndId($vndid);
		foreach ($venDocs as $vdoc)
		{
			switch ($vdoc['vd_type'])
			{
				case 1:
					//$model->vnd_agreement_file_link	 = ($vdoc['vd_status']<>2) ? $vdoc['vd_file'] : '';
					//$model->vnd_agreement_date		 = ($vdoc['vd_status']<>2) ? $vdoc['vd_created_at'] : '';
					$model->vnd_agreement_file_link	 = $vdoc['vd_file'];
					break;
				case 6:
					$model->vnd_firm_attach			 = $vdoc['vd_file'];
					break;
			}
		}
	}

	public function instantReadyForApproval($vndId, $totalDocs)
	{
		$userInfo = UserInfo::getInstance();
		if ($vndId > 0)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$modelv = VendorStats::model()->getbyVendorId($vndId);
				if (!$modelv)
				{
					$modelv				 = new VendorStats();
					$modelv->vrs_vnd_id	 = $vndId;
				}
				$modelv->vrs_docs_score	 = $totalDocs;
				$result					 = CActiveForm::validate($modelv, null, false);
				if ($modelv->validate())
				{
					if ($modelv->save())
					{

						$success = DBUtil::commitTransaction($transaction);
						if ($success)
						{
							$updateData = "Ready for approval vendor Id :: " . $modelv->vrs_vnd_id;
							echo $updateData . "\n";
							Logger::create('CODE DATA ===========>: ' . $updateData, CLogger::LEVEL_INFO);
						}
					}
				}
				else
				{
					$errors = $modelv->getErrors();
					throw new Exception("Validation Errors :: " . $errors);
				}
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::create('ERRORS =====> : ' . "Exception :" . json_encode($ex->getMessage()) . " Errors :" . json_encode($errors), CLogger::LEVEL_ERROR);
			}
		}
	}

	/**
	 * @deprecated since 2022
	 * This function not in use
	 */
	public function getListForApproval()
	{
		$sql = "SELECT
							vnd_id,
							SUM(IF(agreement>0,agreement,0) + docNumber) AS totalDocs,
							SUM(agreement_old + docNumber) AS totalDocs_old,
							IF(agreement>0,agreement,0) as agreement,
							is_agreement ,
							is_digital_agreement
						FROM
							(
							SELECT
								vendors.vnd_id,
								vendors.vnd_active,
								(CASE WHEN (is_agreement = 1 AND is_digital_agreement = 1) THEN '1' 
									  WHEN (is_agreement = 1 AND is_digital_agreement = 0) THEN '1' 
									  WHEN (is_agreement = 0 AND is_digital_agreement = 1) THEN '0' 	
								 END) as agreement,
								IF(is_agreement = 1 OR is_digital_agreement = 1,1,0) AS agreement_old,

								IF(
									is_agreement > 0,
									is_agreement,
									0
								) AS is_agreement,
								IF(
									is_digital_agreement > 0,
									is_digital_agreement,
									0
								) AS is_digital_agreement,
								IF(docNumber > 0, docNumber, 0) AS docNumber
							FROM
								`vendors`
							LEFT JOIN(
								SELECT
									IF(vendor_agreement.vag_id> 0, 1, 0) AS is_agreement,
									vendor_agreement.vag_vnd_id
								FROM
									`vendor_agreement` 
								WHERE
									(vendor_agreement.vag_soft_flag =1 OR vendor_agreement.vag_digital_flag=1) AND vendor_agreement.vag_active = 1
								GROUP BY
									vendor_agreement.vag_vnd_id
							) AS soft
							ON soft.vag_vnd_id = vendors.vnd_id
							LEFT JOIN
							(
								SELECT
									IF(
										vendor_agreement.vag_id > 0,
										1,
										0
									) AS is_digital_agreement,
									vendor_agreement.vag_vnd_id
								FROM
									`vendor_agreement`
								WHERE
									vendor_agreement.vag_digital_ver = '171219' AND vendor_agreement.vag_digital_flag = 1
								GROUP BY
									vendor_agreement.vag_vnd_id
							) AS digital
							ON digital.vag_vnd_id = vendors.vnd_id
							LEFT JOIN
							(
								SELECT
									vendors.vnd_id,
									vendors.vnd_active,
									IF(votter.doc_id IS NOT NULL, @rn := @rn + 1,@rn := @rn) AS vcount,
									IF(aadher.doc_id IS NOT NULL, @rn := @rn + 1,@rn := @rn) AS aacount,
									IF(pan.doc_id IS NOT NULL,@rn := @rn + 1,@rn := @rn) AS pcount,
									IF(licence.doc_id IS NOT NULL,@rn := @rn + 1,@rn := @rn) AS docNumber
									@rn :=0 AS constant
								FROM
									`contact`
								INNER JOIN `vendors` ON vendors.vnd_contact_id = contact.ctt_id
								LEFT JOIN `document` votter ON votter.doc_id = contact.ctt_voter_doc_id AND votter.doc_type=2
								AND votter.doc_status=0 AND votter.doc_active=1 AND votter.doc_file_front_path IS NOT NULL
								LEFT JOIN `document` aadher ON aadher.doc_id = contact.ctt_aadhar_doc_id AND aadher.doc_type=3
								AND aadher.doc_status=0 AND aadher.doc_active=1 AND aadher.doc_file_front_path IS NOT NULL
								LEFT JOIN `document` pan ON pan.doc_id = contact.ctt_pan_doc_id AND pan.doc_type=4
								AND pan.doc_status=0 AND pan.doc_active=1 AND pan.doc_file_front_path IS NOT NULL
								LEFT JOIN `document` licence ON licence.doc_id = contact.ctt_license_doc_id AND licence.doc_type=5
								AND licence.doc_status=0 AND licence.doc_active=1 AND licence.doc_file_front_path IS NOT NULL
								CROSS JOIN (SELECT @rn := 0) AS var_init_subquery
								WHERE
								 contact.ctt_active=1 AND vendors.vnd_active IN(3)
									
								GROUP BY
									vendors.vnd_id
								HAVING
								(
								docNumber > 0
								)
							) AS doc
							ON
								doc.vnd_id = vendors.vnd_id
							WHERE
								vendors.vnd_active = 3
							ORDER BY
								`digital`.`is_digital_agreement`
							) final
						GROUP BY
							vnd_id  
						ORDER BY totalDocs DESC";
		return DBUtil::queryAll($sql);
	}

	public function transferUnregData($unregModel, $vendorId)
	{

		$userInfo = UserInfo::getInstance();
		if ($unregModel->uvr_vnd_voter_id_front_path != '')
		{
			$success1 = $this->saveVoterId($vendorId, $unregModel->uvr_vnd_licence_front_path, $userInfo);
		}
		if ($unregModel->uvr_vnd_aadhaar_front_path != '')
		{
			$success2 = $this->saveAadharId($vendorId, $unregModel->uvr_vnd_licence_front_path, $userInfo);
		}
		if ($unregModel->uvr_vnd_pan_front_path != '')
		{
			$success3 = $this->savePanId($vendorId, $unregModel->uvr_vnd_licence_front_path, $userInfo);
		}
		if ($unregModel->uvr_vnd_licence_front_path != '')
		{
			$success4 = $this->saveLicenceFront($vendorId, $unregModel->uvr_vnd_licence_front_path, $userInfo);
		}
		return true;
	}

	public function getDocImage($vdId)
	{
		/* @var $model VendorDocs */
		$model	 = VendorDocs::model()->findByPk($vdId);
		$image	 = $model->vd_file;
		$ext	 = pathinfo($image, PATHINFO_EXTENSION);
		if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif')
		{
			$path = $model->vd_file;
		}
		else if ($ext == 'pdf')
		{
			$path = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'images/pdf.jpg';
		}
		else if ($ext == 'doc' || $ext == 'docx')
		{
			$path = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'images/doc.png';
		}

		return $path;
	}

	public function updateRejectedDoc($vendorId)
	{
		$sql = "UPDATE `vendor_docs` SET `vd_active`=0 WHERE `vd_vnd_id` = $vendorId AND `vd_status` = 2";

		$cdb		 = DBUtil::command($sql);
		$rowsUpdated = $cdb->execute();
		return $rowsUpdated;
	}

	public function checkDocumentExistance($vndid)
	{
		$sqlVnd = "SELECT DISTINCT vendor_docs.vd_id,
                        vendor_docs.vd_vnd_id,
                        vendor_docs.vd_type,
                        vendor_docs.vd_sub_type,
                        vendor_docs.vd_file,
                        vendor_docs.vd_remarks,
                        vendor_docs.vd_status,
                        vendor_docs.vd_active
						FROM 
						  `vendor_docs`
				   WHERE 
				    vd_vnd_id = $vndid AND vd_status IN(0, 1) AND vd_active = 1 AND vd_file != '' ORDER BY vd_type, vd_sub_type ASC";

		$drvResultSet = DBUtil::command($sqlVnd)->queryAll();
		return $drvResultSet;
	}

	public function checkdiffmulti($array1, $array2)
	{
		$result = array();
		foreach ($array1 as $key => $val)
		{
			if (isset($array2[$key]))
			{
				if (is_array($val) && $array2[$key])
				{
					$result[$key] = $this->checkdiffmulti($val, $array2[$key]);
				}
			}
			else
			{
				$result[$key] = $val;
			}
		}

		return $result;
	}

	public function getDocumentToUpload($vndId)
	{
		$vndResultSet	 = VendorDocs::model()->checkDocumentExistance($vndId);
		$vndResultSetArr = [];
		foreach ($vndResultSet as $vdttypes)
		{
			$vndResultSetArr[$vdttypes['vd_type']][] = $vdttypes['vd_sub_type'];
		}

		$allowedVndDoctype	 = VendorDocs::model()->allowedDocType();
		$docArr				 = VendorDocs::model()->checkDiffMulti($allowedVndDoctype, $vndResultSetArr);
		foreach ($vndResultSetArr as $key => $value)
		{
			foreach ($value as $k => $v)
			{
				if ($allowedVndDoctype[$key][$k] != $v && isset($allowedVndDoctype[$key][$k]))
				{
					unset($docArr[$key]);
					$docArr[$key][$k] = $allowedVndDoctype[$key][$k];
				}
			}
		}

		$vndResultSetArray = array_filter($docArr);
		return $vndResultSetArray;
	}

}

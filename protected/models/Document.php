<?php

/**
 * This is the model class for table "document".
 *
 * The followings are the available columns in table 'document':
 * @property integer $doc_id
 * @property integer $doc_type
 * @property string $doc_file_front_path
 * @property string $doc_file_back_path
 * @property string $doc_remarks
 * @property integer $doc_temp_approved
 * @property string $doc_temp_approved_at
 * @property integer $doc_status
 * @property integer $doc_active
 * @property integer $doc_approved_by
 * @property string $doc_created_at
 * @property string $doc_approved_at
 * 
 */
class Document extends CActiveRecord
{

	public $identity_no, $vndname, $contactname, $drv_id, $entity_type, $groupType,
			$appDate1, $appDate2,
			$entity_id, $local_doc_file_front_path, $local_doc_file_back_path;
	public $isDocsApp		 = false;
	public $prefixDocType	 = '';

	const Document_Agreement						 = 1;
	const Document_Voter							 = 2;
	const Document_Aadhar							 = 3;
	const Document_Pan							 = 4;
	const Document_Licence						 = 5;
	const Document_Memorandum						 = 6;
	const Document_Police_Verification_Certificate = 7;
	const Document_Car_Expired					 = 10;
	const Document_Driver_Expired					 = 11;
	const Document_Vendor_Expired					 = 12;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'document';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('', 'required'),
//			array('doc_file_front_path', 'file', 'allowEmpty'=>true,'types' => 'jpg,jpeg,png,pdf','except' => 'approved'),
//			array('doc_file_back_path', 'file','allowEmpty'=>true, 'types' => 'jpg,jpeg,png,pdf','except' => 'approved'),
			array('doc_type, doc_status, doc_active', 'numerical', 'integerOnly' => true),
			array('doc_file_front_path, doc_file_back_path, doc_remarks', 'length', 'max' => 255),
			array('doc_type', 'required', 'on' => 'saveDoc'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('doc_id, doc_type, doc_file_front_path, doc_file_back_path, doc_remarks, doc_temp_approved, doc_temp_approved_at, doc_status, doc_active, doc_approved_by, doc_created_at, doc_approved_at, entity_id, entity_type,doc_machine_output', 'safe', 'on' => 'search'),
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
			'doc_id'				 => 'Doc',
			'doc_type'				 => 'Doc Type',
			'doc_file_front_path'	 => 'Doc File Front Path',
			'doc_file_back_path'	 => 'Doc File Back Path',
			'doc_remarks'			 => 'Doc Remarks',
			'doc_temp_approved'		 => 'Doc Temp Approved',
			'doc_temp_approved_at'	 => 'Doc Temp Approved At',
			'doc_status'			 => 'Doc Status',
			'doc_active'			 => 'Doc Active',
			'doc_approved_by'		 => 'Doc Approved By',
			'doc_created_at'		 => 'Doc Created',
			'doc_approved_at'		 => 'Doc Approved',
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
		$criteria->compare('doc_id', $this->doc_id);
		$criteria->compare('doc_type', $this->doc_type);
		$criteria->compare('doc_file_front_path', $this->doc_file_front_path, true);
		$criteria->compare('doc_file_back_path', $this->doc_file_back_path, true);
		$criteria->compare('doc_remarks', $this->doc_remarks, true);
		$criteria->compare('doc_temp_approved', $this->doc_temp_approved, true);
		$criteria->compare('doc_temp_approved_at', $this->doc_temp_approved_at, true);
		$criteria->compare('doc_status', $this->doc_status);
		$criteria->compare('doc_active', $this->doc_active);
		$criteria->compare('doc_approved_by', $this->doc_approved_by);
		$criteria->compare('doc_created_at', $this->doc_created_at, true);
		$criteria->compare('doc_approved_at', $this->doc_approved_at, true);
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Document the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function beforeSave()
	{

		if (parent::beforeSave())
		{
			if ($this->doc_temp_approved == 1)
			{
				$this->doc_temp_approved_at = date('Y-m-d H:i:s');
			}
			return true;
		}
		return false;
	}

	public function docType()
	{
		$arr[2][1]	 = 'Voter Front';
		$arr[2][2]	 = 'Voter Back';
		$arr[3][1]	 = 'Aadhaar Front';
		$arr[3][2]	 = 'Aadhaar Back';
		$arr[4][1]	 = 'Pan Front';
		$arr[4][2]	 = 'Pan Back';
		$arr[5][1]	 = 'Licence Front';
		$arr[5][2]	 = 'Licence Back';
		$arr[6][1]	 = 'Memorandum';
		$arr[6][2]	 = '';
		$arr[7][1]	 = 'Police Verification Certificate';
		$arr[7][2]	 = '';
		return $arr;
	}

	/**
	 * 
	 * @param type $type
	 * @return string
	 */
	public static function docTypeList($type = 0)
	{
		$arr = [
			201	 => 'Voter (Front)',
			202	 => 'Voter (Back)',
			301	 => 'Aadhaar (Front)',
			302	 => 'Aadhaar (Back)',
			401	 => 'Pan (Front)',
			402	 => 'Pan (Back)',
			501	 => 'Licence (Front)',
			502	 => 'Licence (Back)',
			600	 => 'Memorandum',
			700	 => 'Police Verification Certificate'
		];
		if ($type > 0)
		{
			return $arr[$type];
		}
		return $arr;
	}

	public function allowedDocType()
	{
		$allowedVndDoctype = [1 => [0], 2 => [1, 2], 3 => [1, 2], 4 => [1, 2], 5 => [1, 2], 6 => [0]];
		return $allowedVndDoctype;
	}

	public function documentType()
	{
		$arr[1]	 = 'Agreement';
		$arr[2]	 = 'Voter';
		$arr[3]	 = 'Aadhar';
		$arr[4]	 = 'Pan';
		$arr[5]	 = 'Licence';
		$arr[6]	 = 'Memorandum';
		//$arr[7]	 = 'Police Verification Certificate';
		$arr[7]	 = 'PoliceVerificationCertificate';
		//print_r($arr);
		return $arr;
	}

	/**
	 * 
	 * @param type $type
	 * @return string
	 */
	public static function getFieldByType($type = '')
	{
		$arr	 = [];
		$arr[2]	 = 'ctt_voter_no';
		$arr[3]	 = 'ctt_aadhaar_no';
		$arr[4]	 = 'ctt_pan_no';
		$arr[5]	 = 'ctt_license_no';
		$arr[6]	 = 'ctt_memo_doc_id';
		$arr[7]	 = 'ctt_police_doc_id';
		if ($type > 0)
		{
			return $arr[$type];
		}
		return $arr;
	}

	public function getAllDocsbyContact($cttId, $viewType = "", $type = 1)
	{
		if (trim($cttId) == null || trim($cttId) == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$params = array('cttId' => trim($cttId));
		if ($viewType == "driver")
		{
			$sql	 = "Select ctt_id, docvoter.doc_id as doc_id2, docaadhar.doc_id as doc_id3, docpan.doc_id as doc_id4, doclicence.doc_id as doc_id5, 
				docpolicever.doc_id as doc_id7, docvoter.doc_type as doc_type2, docaadhar.doc_type as doc_type3, 
				docpan.doc_type as doc_type4, doclicence.doc_type as doc_type5, docpolicever.doc_type as doc_type7,
				docvoter.doc_status as doc_status2, docaadhar.doc_status as doc_status3, docpan.doc_status as doc_status4,
				doclicence.doc_status as doc_status5, docpolicever.doc_status as doc_status7,
				docvoter.doc_file_front_path as doc_file_front_path2,docvoter.doc_front_s3_data as doc_front_s3data2, docvoter.doc_file_back_path as doc_file_back_path2,docvoter.doc_back_s3_data as doc_back_s3data2,docvoter.doc_status as doc_voter_status, 
				docvoter.doc_created_at AS doc_created_at2, docvoter.doc_approved_by AS doc_approved_by2, 
				docaadhar.doc_file_front_path as doc_file_front_path3, docaadhar.doc_front_s3_data as doc_front_s3data3, docaadhar.doc_file_back_path as doc_file_back_path3,docaadhar.doc_back_s3_data as doc_back_s3data3,docaadhar.doc_status as doc_aadhar_status, 
				docaadhar.doc_created_at AS doc_created_at3, docaadhar.doc_approved_by AS doc_approved_by3,
				docpan.doc_file_front_path as doc_file_front_path4, docpan.doc_front_s3_data as doc_front_s3data4, docpan.doc_file_back_path as doc_file_back_path4, docpan.doc_back_s3_data as doc_back_s3data4, docpan.doc_status as doc_pan_status,
				docpan.doc_created_at AS doc_created_at4, docpan.doc_approved_by AS doc_approved_by4,
				doclicence.doc_file_front_path as doc_file_front_path5, doclicence.doc_front_s3_data as doc_front_s3data5, doclicence.doc_file_back_path as doc_file_back_path5, doclicence.doc_back_s3_data as doc_back_s3data5, doclicence.doc_status as doc_license_status,
				doclicence.doc_created_at AS doc_created_at5, doclicence.doc_approved_by AS doc_approved_by5,
				docpolicever.doc_file_front_path as doc_file_front_path7,docpolicever.doc_front_s3_data as doc_front_s3data7, docpolicever.doc_status as doc_police_status,
				docpolicever.doc_created_at AS doc_created_at7, docpolicever.doc_approved_by AS doc_approved_by7,
				docvoter.doc_remarks as doc_remarks2, docaadhar.doc_remarks as doc_remarks3,
				docpan.doc_remarks as doc_remarks4, doclicence.doc_remarks as doc_remarks5, docpolicever.doc_remarks as doc_remarks7
				from contact 
				LEFT JOIN document as docvoter ON contact.ctt_voter_doc_id = docvoter.doc_id AND  docvoter.doc_type = 2 AND docvoter.doc_active = 1 
				LEFT JOIN document as docaadhar ON contact.ctt_aadhar_doc_id = docaadhar.doc_id AND  docaadhar.doc_type = 3 AND docaadhar.doc_active = 1 
				LEFT JOIN document as docpan ON contact.ctt_pan_doc_id = docpan.doc_id AND  docpan.doc_type = 4 AND docpan.doc_active = 1 
				LEFT JOIN document as doclicence ON contact.ctt_license_doc_id = doclicence.doc_id AND  doclicence.doc_type = 5 AND doclicence.doc_active = 1 
				LEFT JOIN document as docpolicever ON contact.ctt_police_doc_id = docpolicever.doc_id AND  docpolicever.doc_type = 7 AND docpolicever.doc_active = 1 
				where contact.ctt_id = :cttId";
			$data	 = DBUtil::queryAll($sql, DBUtil::SDB(), $params);
		}
		else if ($viewType == "vendor")
		{
			if ($type == 2)
			{
				$cond = "contact.ctt_ref_code =:cttId";
			}
			else
			{
				$cond = "contact.ctt_id =:cttId";
			}
			$sql	 = "Select ctt_id,ctt_ref_code, docvoter.doc_id as doc_id2, docaadhar.doc_id as doc_id3, docpan.doc_id as doc_id4, doclicence.doc_id as doc_id5, 
					docmemo.doc_id as doc_id6, docvoter.doc_type as doc_type2, docaadhar.doc_type as doc_type3, 
					docpan.doc_type as doc_type4, doclicence.doc_type as doc_type5, docmemo.doc_type as doc_type6,
					docvoter.doc_status as doc_status2, docaadhar.doc_status as doc_status3, docpan.doc_status as doc_status4,
					doclicence.doc_status as doc_status5, docmemo.doc_status as doc_status6,
					docvoter.doc_file_front_path as doc_file_front_path2, docvoter.doc_file_back_path as doc_file_back_path2,docvoter.doc_created_at AS doc_created_at2,docvoter.doc_approved_by AS doc_approved_by2,
					docaadhar.doc_file_front_path as doc_file_front_path3, docaadhar.doc_file_back_path as doc_file_back_path3, docaadhar.doc_created_at AS doc_created_at3,docaadhar.doc_approved_by AS doc_approved_by3,
					docpan.doc_file_front_path as doc_file_front_path4, docpan.doc_file_back_path as doc_file_back_path4,docpan.doc_created_at AS doc_created_at4,docpan.doc_approved_by AS doc_approved_by4,
					doclicence.doc_file_front_path as doc_file_front_path5, doclicence.doc_file_back_path as doc_file_back_path5,doclicence.doc_created_at AS doc_created_at5,doclicence.doc_approved_by AS doc_approved_by5,
					docmemo.doc_file_front_path as doc_file_front_path6, docmemo.doc_created_at AS doc_created_at6, docmemo.doc_approved_by AS doc_approved_by6, docvoter.doc_remarks as doc_remarks2, docaadhar.doc_remarks as doc_remarks3,
					docpan.doc_remarks as doc_remarks4, doclicence.doc_remarks as doc_remarks5, docmemo.doc_remarks as doc_remarks6
					from contact 
					LEFT JOIN document as docvoter ON contact.ctt_voter_doc_id = docvoter.doc_id AND  docvoter.doc_type = 2 AND docvoter.doc_active = 1 
					LEFT JOIN document as docaadhar ON contact.ctt_aadhar_doc_id = docaadhar.doc_id AND  docaadhar.doc_type = 3 AND docaadhar.doc_active = 1 
					LEFT JOIN document as docpan ON contact.ctt_pan_doc_id = docpan.doc_id AND  docpan.doc_type = 4 AND docpan.doc_active = 1 
					LEFT JOIN document as doclicence ON contact.ctt_license_doc_id = doclicence.doc_id AND  doclicence.doc_type = 5 AND doclicence.doc_active = 1 
					LEFT JOIN document as docmemo ON contact.ctt_memo_doc_id = docmemo.doc_id AND  docmemo.doc_type = 6 AND docmemo.doc_active = 1 
					where $cond";
			$data	 = DBUtil::queryAll($sql, DBUtil::SDB(), $params);
		}
		else
		{
			$sql	 = "Select ctt_id, docvoter.doc_id as doc_id2, docaadhar.doc_id as doc_id3, docpan.doc_id as doc_id4, doclicence.doc_id as doc_id5, 
					docmemo.doc_id as doc_id6,docpolicever.doc_id as doc_id7, docvoter.doc_type as doc_type2, docaadhar.doc_type as doc_type3, 
					docpan.doc_type as doc_type4, doclicence.doc_type as doc_type5, docmemo.doc_type as doc_type6,docpolicever.doc_type as doc_type7,
					docvoter.doc_status as doc_status2, docaadhar.doc_status as doc_status3, docpan.doc_status as doc_status4,
					doclicence.doc_status as doc_status5, docmemo.doc_status as doc_status6,docpolicever.doc_status as doc_status7,
					docvoter.doc_file_front_path as doc_file_front_path2, docvoter.doc_file_back_path as doc_file_back_path2, 
					docaadhar.doc_file_front_path as doc_file_front_path3, docaadhar.doc_file_back_path as doc_file_back_path3,
					docpan.doc_file_front_path as doc_file_front_path4, docpan.doc_file_back_path as doc_file_back_path4,
					doclicence.doc_file_front_path as doc_file_front_path5, doclicence.doc_file_back_path as doc_file_back_path5,
					docmemo.doc_file_front_path as doc_file_front_path6,docpolicever.doc_file_front_path as doc_file_front_path7, docvoter.doc_remarks as doc_remarks2, docaadhar.doc_remarks as doc_remarks3,
					docpan.doc_remarks as doc_remarks4, doclicence.doc_remarks as doc_remarks5, docmemo.doc_remarks as doc_remarks6,docpolicever.doc_remarks as doc_remarks7
					from contact 
					LEFT JOIN document as docvoter ON contact.ctt_voter_doc_id = docvoter.doc_id AND  docvoter.doc_type = 2 AND docvoter.doc_active = 1 
					LEFT JOIN document as docaadhar ON contact.ctt_aadhar_doc_id = docaadhar.doc_id AND  docaadhar.doc_type = 3 AND docaadhar.doc_active = 1 
					LEFT JOIN document as docpan ON contact.ctt_pan_doc_id = docpan.doc_id AND  docpan.doc_type = 4 AND docpan.doc_active = 1 
					LEFT JOIN document as doclicence ON contact.ctt_license_doc_id = doclicence.doc_id AND  doclicence.doc_type = 5 AND doclicence.doc_active = 1 
					LEFT JOIN document as docmemo ON contact.ctt_memo_doc_id = docmemo.doc_id AND  docmemo.doc_type = 6 AND docmemo.doc_active = 1 
					LEFT JOIN document as docpolicever ON contact.ctt_police_doc_id = docpolicever.doc_id AND  docpolicever.doc_type = 7 AND docpolicever.doc_active = 1 
					where contact.ctt_id =:cttId";
			$data	 = DBUtil::queryAll($sql, DBUtil::SDB(), $params);
		}
		return $data;
	}

	public function getDocsByDrvId($drvId, $cttId)
	{
		$rows									 = $this->getAllDocsbyContact($cttId, 'driver');
		$data['drv_voter_id_img_path']			 = $data['drv_voter_id_img_path2']			 = $data['drv_pan_img_path']				 = $data['drv_pan_img_path2']				 = $data['drv_aadhaar_img_path']			 = $data['drv_aadhaar_img_path2']			 = $data['drv_licence_path']				 = $data['drv_licence_path2']				 = $data['drv_police_certificate']			 = '';
		$data['drv_voter_id']					 = $data['drv_voter_back_id']				 = $data['drv_pan_id']						 = $data['drv_pan_back_id']				 = $data['drv_aadhaar_id']					 = $data['drv_aadhaar_back_id']			 = $data['drv_licence_id']					 = $data['drv_licence_back_id']			 = $data['drv_police_ver']					 = '';
		$data['drv_voter_id_status']			 = $data['drv_voter_back_id_status']		 = $data['drv_pan_status']					 = $data['drv_pan_back_status']			 = '';
		$data['drv_aadhaar_status']				 = $data['drv_aadhaar_back_status']		 = $data['drv_licence_status']				 = $data['drv_licence_back_status']		 = $data['drv_police_certificate_status']	 = '';
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$data['drv_voter_id']			 = array('drd_id' => $drvId, 'drd_file' => $row['doc_file_front_path2'], 'drd_status' => $row['doc_voter_status'], 'drd_remarks' => $row['doc_remarks2']);
//				if (substr_count($row['doc_file_front_path2'], "attachments") > 0)
//				{
//					$data['drv_voter_id_img_path'] = $row['doc_file_front_path2'];
//				}
//				else
//				{
//					$data['drv_voter_id_img_path'] = AttachmentProcessing::ImagePath($row['doc_file_front_path2']);
//				}
				$data['drv_voter_id_img_path']	 = Document::getDocPathById($row['doc_id2'], 1);

				$data['drv_voter_id_status']	 = ($row['doc_file_front_path2'] == null) ? '' : $row['doc_voter_status'];
				$data['drv_voter_back_id']		 = array('drd_id' => $drvId, 'drd_file' => $row['doc_file_back_path2'], 'drd_status' => $row['doc_voter_status'], 'drd_remarks' => $row['doc_remarks2']);
//				if (substr_count($row['doc_file_back_path2'], "attachments") > 0)
//				{
//					$data['drv_voter_id_img_path2'] = $row['doc_file_back_path2'];
//				}
//				else
//				{
//					$data['drv_voter_id_img_path2'] = AttachmentProcessing::ImagePath($row['doc_file_back_path2']);
//				}
				$data['drv_voter_id_img_path2']	 = Document::getDocPathById($row['doc_id2'], 2);

				$data['drv_voter_back_id_status']	 = ($row['doc_file_back_path2'] == null) ? '' : $row['doc_voter_status'];
				$data['drv_voter_remarks']			 = ($row['doc_remarks2'] == null) ? '' : $row['doc_remarks2'];
				$data['drv_pan_id']					 = array('drd_id' => $drvId, 'drd_file' => $row['doc_file_front_path4'], 'drd_status' => $row['doc_pan_status'], 'drd_remarks' => $row['doc_remarks4']);
//				if (substr_count($row['doc_file_front_path4'], "attachments") > 0)
//				{
//					$data['drv_pan_img_path'] = $row['doc_file_front_path4'];
//				}
//				else
//				{
//					$data['drv_pan_img_path'] = AttachmentProcessing::ImagePath($row['doc_file_front_path4']);
//				}
				$data['drv_pan_img_path']			 = Document::getDocPathById($row['doc_id4'], 1);
				$data['drv_pan_status']				 = ($row['doc_file_front_path4'] == null) ? '' : $row['doc_pan_status'];
				$data['drv_pan_back_id']			 = array('drd_id' => $drvId, 'drd_file' => $row['doc_file_back_path4'], 'drd_status' => $row['doc_pan_status'], 'drd_remarks' => $row['doc_remarks4']);
//				if (substr_count($row['doc_file_back_path4'], "attachments") > 0)
//				{
//					$data['drv_pan_img_path2'] = $row['doc_file_back_path4'];
//				}
//				else
//				{
//					$data['drv_pan_img_path2'] = AttachmentProcessing::ImagePath($row['doc_file_back_path4']);
//				}
				$data['drv_pan_img_path2']			 = Document::getDocPathById($row['doc_id4'], 2);
				$data['drv_pan_back_status']		 = ($row['doc_file_back_path4'] == null) ? '' : $row['doc_pan_status'];
				$data['drv_pan_remarks']			 = ($row['doc_remarks4'] == null) ? '' : $row['doc_remarks4'];
				$data['drv_aadhaar_id']				 = array('drd_id' => $drvId, 'drd_file' => $row['doc_file_front_path3'], 'drd_status' => $row['doc_aadhar_status'], 'drd_remarks' => $row['doc_remarks3']);
//				if (substr_count($row['doc_file_front_path3'], "attachments") > 0)
//				{
//					$data['drv_aadhaar_img_path'] = $row['doc_file_front_path3'];
//				}
//				else
//				{
//					$data['drv_aadhaar_img_path'] = AttachmentProcessing::ImagePath($row['doc_file_front_path3']);
//				}
				$data['drv_aadhaar_img_path']		 = Document::getDocPathById($row['doc_id3'], 1);
				$data['drv_aadhaar_status']			 = ($row['doc_file_front_path3'] == null) ? '' : $row['doc_aadhar_status'];
				$data['drv_aadhaar_back_id']		 = array('drd_id' => $drvId, 'drd_file' => $row['doc_file_back_path3'], 'drd_status' => $row['doc_aadhar_status'], 'drd_remarks' => $row['doc_remarks3']);
//				if (substr_count($row['doc_file_back_path3'], "attachments") > 0)
//				{
//					$data['drv_aadhaar_img_path2'] = $row['doc_file_back_path3'];
//				}
//				else
//				{
//					$data['drv_aadhaar_img_path2'] = AttachmentProcessing::ImagePath($row['doc_file_back_path3']);
//				}
				$data['drv_aadhaar_img_path2']		 = Document::getDocPathById($row['doc_id3'], 2);
				$data['drv_aadhaar_back_status']	 = ($row['doc_file_back_path3'] == null) ? '' : $row['doc_aadhar_status'];
				$data['drv_aadhar_remarks']			 = ($row['doc_remarks3'] == '') ? '' : $row['doc_remarks3'];
				$data['drv_licence_id']				 = array('drd_id' => $drvId, 'drd_file' => $row['doc_file_front_path5'], 'drd_status' => $row['doc_license_status'], 'drd_remarks' => $row['doc_remarks5']);
//				if (substr_count($row['doc_file_front_path5'], "attachments") > 0)
//				{
//					$data['drv_licence_path'] = $row['doc_file_front_path5'];
//				}
//				else
//				{
//					$data['drv_licence_path'] = AttachmentProcessing::ImagePath($row['doc_file_front_path5']);
//				}
				$data['drv_licence_path']			 = Document::getDocPathById($row['doc_id5'], 1);
				$data['drv_licence_status']			 = ($row['doc_file_front_path5'] == null) ? '' : $row['doc_license_status'];

				$data['drv_licence_back_id']	 = array('drd_id' => $drvId, 'drd_file' => $row['doc_file_back_path5'], 'drd_status' => $row['doc_license_status'], 'drd_remarks' => $row['doc_remarks5']);
//				if (substr_count($row['doc_file_back_path5'], "attachments") > 0)
//				{
//					$data['drv_licence_path2'] = $row['doc_file_back_path5'];
//				}
//				else
//				{
//					$data['drv_licence_path2'] = AttachmentProcessing::ImagePath($row['doc_file_back_path5']);
//				}
				$data['drv_licence_path2']		 = Document::getDocPathById($row['doc_id5'], 2);
				$data['drv_licence_back_status'] = ($row['doc_file_back_path5'] == null) ? '' : $row['doc_license_status'];
				$data['drv_license_remarks']	 = ($row['doc_remarks5'] == null) ? '' : $row['doc_remarks5'];

				$data['drv_police_ver']					 = array('drd_id' => $drvId, 'drd_file' => $row['doc_file_front_path7'], 'drd_status' => $row['doc_police_status'], 'drd_remarks' => $row['doc_remarks7']);
//				if (substr_count($row['doc_file_front_path7'], "attachments") > 0)
//				{
//					$data['drv_police_certificate'] = $row['doc_file_front_path7'];
//				}
//				else
//				{
//					$data['drv_police_certificate'] = AttachmentProcessing::ImagePath($row['doc_file_front_path7']);
//				}
				$data['drv_police_certificate']			 = Document::getDocPathById($row['doc_id7'], 1);
				$data['drv_police_certificate_status']	 = ($row['doc_file_front_path7'] == null) ? '' : $row['doc_police_status'];
				$data['drv_police_verify_remarks']		 = ($row['doc_remarks7'] == null) ? '' : $row['doc_remarks7'];
			}
		}
		return $data;
	}

	public function getFetchList($arr = [], $command = false)
	{
		$where = $arr['cttId'] == NULL ? " " : "  and contact.ctt_id=" . $arr['cttId'];
		if (trim($arr['doc_type']) != '' && trim($arr['contactname']) != '')
		{
			if (trim($arr['doc_type']) == 2)
			{
				$where .= ' and docvoter.doc_id IS NOT NULL and docvoter.doc_type = 2 AND ((docvoter.doc_file_front_path IS NOT NULL and  docvoter.doc_file_front_path!="") OR (docvoter.doc_file_back_path IS NOT NULL and docvoter.doc_file_back_path!=""))';
			}
			else if (trim($arr['doc_type']) == 3)
			{
				$where .= ' and docaadhar.doc_id IS NOT NULL and  docaadhar.doc_type = 3 AND ((docaadhar.doc_file_front_path IS NOT NULL and  docaadhar.doc_file_front_path!="") OR (docaadhar.doc_file_back_path IS NOT NULL and docaadhar.doc_file_back_path!="")) ';
			}
			else if (trim($arr['doc_type']) == 4)
			{
				$where .= ' and docpan.doc_id IS NOT NULL and  docpan.doc_type = 4 AND ((docpan.doc_file_front_path IS NOT NULL and  docpan.doc_file_front_path!="") OR (docpan.doc_file_back_path IS NOT NULL and docpan.doc_file_back_path!=""))';
			}
			else if (trim($arr['doc_type']) == 5)
			{
				$where .= ' and doclicence.doc_id IS NOT NULL and doclicence.doc_type = 5 AND ((doclicence.doc_file_front_path IS NOT NULL and  doclicence.doc_file_front_path!="") OR (doclicence.doc_file_back_path IS NOT NULL and doclicence.doc_file_back_path!=""))';
			}
			else if (trim($arr['doc_type']) == 6)
			{
				$where .= ' and docmemo.doc_id IS NOT NULL and  docmemo.doc_type = 6 AND (docmemo.doc_file_front_path IS NOT NULL and  docmemo.doc_file_front_path!="")';
			}
			else if (trim($arr['doc_type']) == 7)
			{
				$where .= ' and docpolicever.doc_id IS NOT NULL and docpolicever.doc_type = 7 AND (docpolicever.doc_file_front_path IS NOT NULL and  docpolicever.doc_file_front_path!="")';
			}
			$where .= " and  ( (ctt_business_name LIKE '%" . $arr['contactname'] . "%') OR (ctt_first_name LIKE '%" . $arr['contactname'] . "%')
					OR (ctt_last_name LIKE '%" . $arr['contactname'] . "%'))";
		}
		else if (trim($arr['doc_type']) != '')
		{
			if (trim($arr['doc_type']) == 2)
			{
				$where .= ' and docvoter.doc_id IS NOT NULL and docvoter.doc_type = 2 AND ((docvoter.doc_file_front_path IS NOT NULL and  docvoter.doc_file_front_path!="") OR (docvoter.doc_file_back_path IS NOT NULL and docvoter.doc_file_back_path!=""))';
			}
			else if (trim($arr['doc_type']) == 3)
			{
				$where .= ' and docaadhar.doc_id IS NOT NULL and  docaadhar.doc_type = 3 AND ((docaadhar.doc_file_front_path IS NOT NULL and  docaadhar.doc_file_front_path!="") OR (docaadhar.doc_file_back_path IS NOT NULL and docaadhar.doc_file_back_path!="")) ';
			}
			else if (trim($arr['doc_type']) == 4)
			{
				$where .= ' and docpan.doc_id IS NOT NULL and  docpan.doc_type = 4 AND ((docpan.doc_file_front_path IS NOT NULL and  docpan.doc_file_front_path!="") OR (docpan.doc_file_back_path IS NOT NULL and docpan.doc_file_back_path!=""))';
			}
			else if (trim($arr['doc_type']) == 5)
			{
				$where .= ' and doclicence.doc_id IS NOT NULL and doclicence.doc_type = 5 AND ((doclicence.doc_file_front_path IS NOT NULL and  doclicence.doc_file_front_path!="") OR (doclicence.doc_file_back_path IS NOT NULL and doclicence.doc_file_back_path!=""))';
			}
			else if (trim($arr['doc_type']) == 6)
			{
				$where .= ' and docmemo.doc_id IS NOT NULL and  docmemo.doc_type = 6 AND (docmemo.doc_file_front_path IS NOT NULL and  docmemo.doc_file_front_path!="")';
			}
			else if (trim($arr['doc_type']) == 7)
			{
				$where .= ' and docpolicever.doc_id IS NOT NULL and docpolicever.doc_type = 7 AND (docpolicever.doc_file_front_path IS NOT NULL and  docpolicever.doc_file_front_path!="")';
			}
		}
		else if (trim($arr['contactname']) != '')
		{
			$where .= " AND   (
	          ((docvoter.doc_id IS NOT NULL)     AND    ((docvoter.doc_file_front_path IS NOT NULL and  docvoter.doc_file_front_path!='')    OR (docvoter.doc_file_back_path IS NOT NULL and docvoter.doc_file_back_path!='')))
           OR ((docaadhar.doc_id IS NOT NULL)    AND    ((docaadhar.doc_file_front_path IS NOT NULL and  docaadhar.doc_file_front_path!='')  OR (docaadhar.doc_file_back_path IS NOT NULL and docaadhar.doc_file_back_path!='')))
           OR ((docpan.doc_id IS NOT NULL)       AND    (( docpan.doc_file_front_path IS NOT NULL and docpan.doc_file_front_path!='')  OR (docpan.doc_file_back_path IS NOT NULL and docpan.doc_file_back_path!='')))
           OR ((doclicence.doc_id IS NOT NULL)   AND    (( doclicence.doc_file_front_path IS NOT NULL and  doclicence.doc_file_front_path!='' )      OR (doclicence.doc_file_back_path IS NOT NULL and doclicence.doc_file_back_path!='')))
           OR ((docmemo.doc_id IS NOT NULL)      AND    (docmemo.doc_file_front_path IS NOT NULL and  docmemo.doc_file_front_path!=''))
		   OR ((docpolicever.doc_id IS NOT NULL) AND    (docpolicever.doc_file_front_path IS NOT NULL and  docpolicever.doc_file_front_path!=''))
		   )   and  ( (ctt_business_name LIKE '%" . $arr['contactname'] . "%') OR (ctt_first_name LIKE '%" . $arr['contactname'] . "%')
					OR (ctt_last_name LIKE '%" . $arr['contactname'] . "%')) ";
		}
		else
		{
			$where .= "  AND (
	          ((docvoter.doc_id IS NOT NULL)     AND    ((docvoter.doc_file_front_path IS NOT NULL and  docvoter.doc_file_front_path!='')    OR (docvoter.doc_file_back_path IS NOT NULL and docvoter.doc_file_back_path!='')))
           OR ((docaadhar.doc_id IS NOT NULL)    AND    ((docaadhar.doc_file_front_path IS NOT NULL and  docaadhar.doc_file_front_path!='')  OR (docaadhar.doc_file_back_path IS NOT NULL and docaadhar.doc_file_back_path!='')))
           OR ((docpan.doc_id IS NOT NULL)       AND    (( docpan.doc_file_front_path IS NOT NULL and docpan.doc_file_front_path!='')  OR (docpan.doc_file_back_path IS NOT NULL and docpan.doc_file_back_path!='')))
           OR ((doclicence.doc_id IS NOT NULL)   AND    (( doclicence.doc_file_front_path IS NOT NULL and  doclicence.doc_file_front_path!='' )      OR (doclicence.doc_file_back_path IS NOT NULL and doclicence.doc_file_back_path!='')))
           OR ((docmemo.doc_id IS NOT NULL)      AND    (docmemo.doc_file_front_path IS NOT NULL and  docmemo.doc_file_front_path!=''))
		   OR ((docpolicever.doc_id IS NOT NULL) AND    (docpolicever.doc_file_front_path IS NOT NULL and  docpolicever.doc_file_front_path!=''))
		   )";
		}

		$sql = "Select ctt_id,
			TRIM(Replace(Replace(Replace(ctt_voter_no,'\t',''),'\n',''),'\r','')) as ctt_voter_no,
			TRIM(Replace(Replace(Replace(ctt_aadhaar_no,'\t',''),'\n',''),'\r','')) as ctt_aadhaar_no,
			TRIM(Replace(Replace(Replace(ctt_pan_no,'\t',''),'\n',''),'\r','')) as ctt_pan_no ,
			TRIM(Replace(Replace(Replace(ctt_license_no,'\t',''),'\n',''),'\r','')) as ctt_license_no,
			
			ctt_first_name,	ctt_last_name,ctt_business_name,ctt_user_type,ctt_business_type,ctt_is_verified,	ctt_active, docvoter.doc_id as doc_id2, docaadhar.doc_id as doc_id3,docpan.doc_id as doc_id4,doclicence.doc_id as doc_id5, docmemo.doc_id as doc_id6,docpolicever.doc_id as doc_id7,
		docvoter.doc_type as doc_type2,	docaadhar.doc_type as doc_type3, docpan.doc_type as doc_type4,doclicence.doc_type as doc_type5,docmemo.doc_type as doc_type6,docpolicever.doc_type as doc_type7,docvoter.doc_status as doc_status2,docaadhar.doc_status as doc_status3,docpan.doc_status as doc_status4,doclicence.doc_status as doc_status5,docmemo.doc_status as doc_status6,docpolicever.doc_status as doc_status7,docvoter.doc_file_front_path as doc_file_front_path2,docvoter.doc_file_back_path as doc_file_back_path2, 
		docaadhar.doc_file_front_path as doc_file_front_path3,docaadhar.doc_file_back_path as doc_file_back_path3,docpan.doc_file_front_path as doc_file_front_path4,	docpan.doc_file_back_path as doc_file_back_path4,doclicence.doc_file_front_path as doc_file_front_path5,	doclicence.doc_file_back_path as doc_file_back_path5,docmemo.doc_file_front_path as doc_file_front_path6,docpolicever.doc_file_front_path as doc_file_front_path7,docvoter.doc_remarks as doc_remarks2,
		docaadhar.doc_remarks as doc_remarks3,docpan.doc_remarks as doc_remarks4,doclicence.doc_remarks as doc_remarks5,docmemo.doc_remarks as doc_remarks6,docpolicever.doc_remarks as doc_remarks7	from contact 
        LEFT JOIN document as docvoter ON contact.ctt_voter_doc_id = docvoter.doc_id AND  docvoter.doc_type = 2 AND docvoter.doc_active = 1 AND docvoter.doc_status=0   
		LEFT JOIN document as docaadhar ON contact.ctt_aadhar_doc_id = docaadhar.doc_id AND  docaadhar.doc_type = 3 AND docaadhar.doc_active = 1 AND docaadhar.doc_status=0 
		LEFT JOIN document as docpan ON contact.ctt_pan_doc_id = docpan.doc_id AND  docpan.doc_type = 4 AND docpan.doc_active = 1   AND docpan.doc_status=0
		LEFT JOIN document as doclicence ON contact.ctt_license_doc_id = doclicence.doc_id AND  doclicence.doc_type = 5 AND doclicence.doc_active = 1  AND doclicence.doc_status=0   
		LEFT JOIN document as docmemo ON contact.ctt_memo_doc_id = docmemo.doc_id AND  docmemo.doc_type = 6 AND docmemo.doc_active = 1  AND docmemo.doc_status=0 
		LEFT JOIN document as docpolicever ON contact.ctt_police_doc_id = docpolicever.doc_id AND  docpolicever.doc_type = 7 AND docpolicever.doc_active = 1  AND docpolicever.doc_status=0 
		where contact.ctt_active<>0 $where ";

		$defaultOrder	 = 'ctt_id DESC';
		//	$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['ctt_id'],
				'defaultOrder'	 => $defaultOrder],
			'pagination'	 => ['pageSize' => 20],
		]);
		return $dataprovider;
	}

	public function rejectDocument($docId, $remarks, UserInfo $userInfo = null)
	{
		$success	 = false;
		$transaction = Yii::app()->db->beginTransaction();
		try
		{
			$model = Document::model()->findByPk($docId);
			if ($model->doc_type == 1)
			{
				$event_id	 = VendorsLog::VENDOR_AGREEMENT_REJECT;
				$fileType	 = "#agreement";
			}
			else if ($model->doc_type == 2)
			{
				$event_id	 = VendorsLog::VENDOR_VOTERID_REJECT;
				$fileType	 = "#voterid";
			}
			else if ($model->doc_type == 3)
			{
				$event_id	 = VendorsLog::VENDOR_AADHAAR_REJECT;
				$fileType	 = "#aadhaarid";
			}
			else if ($model->doc_type == 4)
			{
				$event_id	 = VendorsLog::VENDOR_PAN_REJECT;
				$fileType	 = "#panid";
			}
			else if ($model->doc_type == 5)
			{
				$event_id	 = VendorsLog::VENDOR_LICENSE_REJECT;
				$fileType	 = "#licence";
			}
			else if ($model->doc_type == 6)
			{
				$event_id	 = VendorsLog::VENDOR_MEMORANDUM_REJECT;
				$fileType	 = "#memorandum";
			}
			$model->doc_remarks	 = $remarks;
			$model->doc_status	 = 2;
			if ($model->update())
			{
				if ($model->doc_type == 1)
				{
					
				}
				else
				{
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

	public function add()
	{
		$transaction = DBUtil::beginTransaction();
		$success	 = false;
		try
		{

			$files		 = $this->uploadFiles($this->doc_type);
			$event_id	 = VendorsLog::VENDOR_FILE_UPLOAD;
			if (isset($files[0]))
			{
				$this->doc_file_front_path	 = $files[0];
				$this->doc_front_s3_data	 = NULL;
				if ($this->vndname != '')
				{
					switch ($this->doc_type)
					{
						case 2:
							$logDesc = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_VOTERID_UPLOAD);
							break;
						case 3:
							$logDesc = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_AADHAAR_UPLOAD);
							break;
						case 4:
							$logDesc = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_PAN_UPLOAD);
							break;
						case 5:
							$logDesc = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_LICENSE_UPLOAD);
							break;
						case 6:
							$logDesc = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_MEMORANDUM_UPLOAD);
							break;
						default:
							break;
					}
				}
				else if ($this->drv_id != '')
				{
					switch ($this->doc_type)
					{
						case 2:
							$logDesc = DriversLog::model()->getEventByEventId(DriversLog::DRIVER_VOTERID_UPLOAD);
							break;
						case 3:
							$logDesc = DriversLog::model()->getEventByEventId(DriversLog::DRIVER_AADHAAR_UPLOAD);
							break;
						case 4:
							$logDesc = DriversLog::model()->getEventByEventId(DriversLog::DRIVER_PAN_UPLOAD);
							break;
						case 5:
							$logDesc = DriversLog::model()->getEventByEventId(DriversLog::DRIVER_DL_UPLOAD);
							break;
						case 7:
							$logDesc = DriversLog::model()->getEventByEventId(DriversLog::DRIVER_PC_UPLOAD);
							break;
						default:
							break;
					}
				}

				if ($this->vndname != '')
				{
					$event_id = VendorsLog::VENDOR_FILE_UPLOAD;
					VendorsLog::model()->createLog($this->vndname, $logDesc, UserInfo::getInstance(), $event_id, false, false);
				}
				else if ($this->drv_id != '')
				{
					$event_id = DriversLog::DRIVER_FILE_UPLOAD;
					DriversLog::model()->createLog($this->drv_id, $logDesc, UserInfo::getInstance(), $event_id, false, false);
				}
			}
			if (isset($files[1]))
			{
				$this->doc_file_back_path	 = $files[1];
				$this->doc_back_s3_data		 = NULL;
				if ($this->vndname != '')
				{
					switch ($this->doc_type)
					{
						case 2:
							$logDesc = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_VOTERID_BACK_UPLOAD);
							break;
						case 3:
							$logDesc = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_AADHAAR_BACK_UPLOAD);
							break;
						case 4:
							$logDesc = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_PAN_BACK_UPLOAD);
							break;
						case 5:
							$logDesc = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_LICENSE_BACK_UPLOAD);
							break;
						case 6:
							$logDesc = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_MEMORANDUM_UPLOAD);
							break;
						default:
							break;
					}
				}
				else if ($this->drv_id != '')
				{
					switch ($this->doc_type)
					{
						case 2:
							$logDesc = DriversLog::model()->getEventByEventId(DriversLog::DRIVER_VOTERID_BACK_UPLOAD);
							break;
						case 3:
							$logDesc = DriversLog::model()->getEventByEventId(DriversLog::DRIVER_AADHAAR_BACK_UPLOAD);
							break;
						case 4:
							$logDesc = DriversLog::model()->getEventByEventId(DriversLog::DRIVER_PAN_BACK_UPLOAD);
							break;
						case 5:
							$logDesc = DriversLog::model()->getEventByEventId(DriversLog::DRIVER_DL_BACK_UPLOAD);
							break;
						case 7:
							$logDesc = DriversLog::model()->getEventByEventId(DriversLog::DRIVER_PC_UPLOAD);
							break;
						default:
							break;
					}
				}

				if ($this->vndname != '')
				{
					$event_id = VendorsLog::VENDOR_FILE_UPLOAD;
					VendorsLog::model()->createLog($this->vndname, $logDesc, UserInfo::getInstance(), $event_id, false, false);
				}
				else if ($this->drv_id != '')
				{
					$event_id = DriversLog::DRIVER_FILE_UPLOAD;
					DriversLog::model()->createLog($this->drv_id, $logDesc, UserInfo::getInstance(), $event_id, false, false);
				}
			}

			if ($this->isDocsApp == true && $this->doc_status == 2)
			{
				if (isset($files[0]))
				{
					$this->doc_file_back_path = '';
				}
				if (isset($files[1]))
				{
					$this->doc_file_front_path = '';
				}
				$this->doc_remarks	 = '';
				$this->doc_status	 = 0;
			}

			if ($this->doc_temp_approved == 1)
			{
				$this->doc_temp_approved_at = new CDbExpression('NOW()');
			}

			$success = $this->save();
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			$this->addError('doc_id', $ex->getMessage());
			DBUtil::rollbackTransaction($transaction);
		}
		return $success;
	}

	public function uploadFiles($type)
	{
		if ($this->isDocsApp == true)
		{
			$frontFile	 = ($this->local_doc_file_front_path != '') ? CUploadedFile::getInstanceByName($this->local_doc_file_front_path) : null;
			$backFile	 = ($this->local_doc_file_back_path != '') ? CUploadedFile::getInstanceByName($this->local_doc_file_back_path) : null;
		}
		else
		{
			$frontFile	 = CUploadedFile::getInstance($this, "doc_file_front_path");
			$backFile	 = CUploadedFile::getInstance($this, "doc_file_back_path");

			if ($this->prefixDocType != '')
			{
				$prefixDocType	 = $this->prefixDocType;
				$frontFile		 = CUploadedFile::getInstance($this, "[$prefixDocType]doc_file_front_path");
				$backFile		 = CUploadedFile::getInstance($this, "[$prefixDocType]doc_file_back_path");
			}
		}

		switch ($type)
		{
			case 2:
				$files	 = $this->uploadVoterID($this->entity_id, $frontFile, $backFile);
				break;
			case 3:
				$files	 = $this->uploadAadhaarCard($this->entity_id, $frontFile, $backFile);
				break;
			case 4:
				$files	 = $this->uploadPANCard($this->entity_id, $frontFile, $backFile);
				break;
			case 5:
				$files	 = $this->uploadDrivingLicense($this->entity_id, $frontFile, $backFile);
				break;
			case 6:
				$files	 = $this->uploadMOU($this->entity_id, $frontFile);
				break;
			case 7:
				$files	 = $this->uploadPoliceVerification($this->entity_id, $frontFile);
				break;
			default:
				break;
		}
		return $files;
	}

	public function uploadDrivingLicense($cttId, CUploadedFile $frontPhoto = null, CUploadedFile $backPhoto = null)
	{
		if ($frontPhoto != null)
		{
			$front = self::upload($cttId, "DL-FRONT", $frontPhoto);
		}
		if ($backPhoto != null)
		{
			$back = self::upload($cttId, "DL-BACK", $backPhoto);
		}
		return [$front, $back];
	}

	public function uploadPANCard($cttId, CUploadedFile $frontPhoto = null, CUploadedFile $backPhoto = null)
	{
		if ($frontPhoto != null)
		{
			$front = self::upload($cttId, "PAN-FRONT", $frontPhoto);
		}
		if ($backPhoto != null)
		{
			$back = self::upload($cttId, "PAN-BACK", $backPhoto);
		}
		return [$front, $back];
	}

	public function uploadVoterID($cttId, CUploadedFile $frontPhoto = null, CUploadedFile $backPhoto = null)
	{
		if ($frontPhoto != null)
		{
			$front = self::upload($cttId, "VOTER-FRONT", $frontPhoto);
		}
		if ($backPhoto != null)
		{
			$back = self::upload($cttId, "VOTER-BACK", $backPhoto);
		}
		return [$front, $back];
	}

	public function uploadAadhaarCard($cttId, CUploadedFile $frontPhoto = null, CUploadedFile $backPhoto = null)
	{
		if ($frontPhoto != null)
		{
			$front = self::upload($cttId, "UIDAI-FRONT", $frontPhoto);
		}
		if ($backPhoto != null)
		{
			$back = self::upload($cttId, "UIDAI-BACK", $backPhoto);
		}
		return [$front, $back];
	}

	public function uploadPoliceVerification($cttId, CUploadedFile $frontPhoto)
	{
		return [self::upload($cttId, "PVC", $frontPhoto)];
	}

	public function uploadMOU($cttId, CUploadedFile $file)
	{
		return [self::upload($cttId, "MOU", $file)];
	}

	public static function upload($cttId, $doctypeName, CUploadedFile $file, $maxWidth = 1200, $onlyImage = false)
	{

		if (!Filter::checkImage($file->getType()) && $onlyImage)
		{
			echo "Image type not supported";
			exit;
		}
		$DS			 = DIRECTORY_SEPARATOR;
		$fileName	 = $cttId . "-" . $doctypeName . "-" . date('YmdHis') . "." . pathinfo($file->name, PATHINFO_EXTENSION);

		$basePath		 = Yii::app()->basePath;
		$folderPrefix	 = floor($cttId / 1000);
		$serverId		 = Config::getServerID();
		$prefixPath		 = $DS . 'contact' . $DS . $serverId . $DS . 'document' . $DS . $folderPrefix;
		if (!is_dir($basePath . $prefixPath))
		{
			$checkFolderdir = mkdir($basePath . $prefixPath, 0755, true);
			chmod($basePath . $prefixPath, 0755);
			if (!$checkFolderdir)
			{
				echo "Failed to create dir: " . $basePath . $docPath;
				exit;
			}
		}

		$docPath = $DS . 'contact' . $DS . $serverId . $DS . 'document' . $DS . $folderPrefix . $DS . $cttId . $DS;
		if (!is_dir($basePath . $docPath))
		{
			$checkdir = mkdir($basePath . $docPath, 0755, true);
			if (!$checkdir)
			{
				echo "Failed to create dir: " . $basePath . $docPath;
				exit;
			}
		}

		$destinationPath = $basePath . $docPath . $fileName;

		if (Filter::checkImage($file->getType()))
		{
			Filter::resizeImage($file->tempName, $maxWidth, $destinationPath);
		}
		else
		{
			$file->saveAs($destinationPath);
		}

		return $docPath . $fileName;
	}

	public function uploadDocument($cttId, $doctypeName, $uploadedFrontPhoto, $uploadedBackPhoto)
	{
		$DS				 = DIRECTORY_SEPARATOR;
		$frontfileName	 = $cttId . "-" . $doctypeName . "front-" . date('YmdHis') . "." . pathinfo($uploadedFrontPhoto->name, PATHINFO_EXTENSION);
		$backFileName	 = $cttId . "-" . $doctypeName . "back-" . date('YmdHis') . "." . pathinfo($uploadedBackPhoto->name, PATHINFO_EXTENSION);

		$folderPrefix	 = floor($cttId / 1000);
		$path			 = Yii::app()->basePath;
		$serverId		 = Config::getServerID();
		$docType		 = $path . $DS . $serverId . $DS . 'contact' . $DS . 'document' . $DS . $folderPrefix . $DS . $cttId . $DS . $doctypeName;

		if (!is_dir($docType))
		{
			mkdir($docType, 775, true);
		}

		$foldertoupload		 = $docType . DIRECTORY_SEPARATOR . $frontfileName;
		$foldertouploadback	 = $docType . DIRECTORY_SEPARATOR . $backFileName;

		$extentionfront	 = pathinfo($uploadedFrontPhoto, PATHINFO_EXTENSION);
		$extentionback	 = pathinfo($uploadedBackPhoto, PATHINFO_EXTENSION);

		if (strtolower($extentionfront) == 'png' || strtolower($extentionfront) == 'jpg' || strtolower($extentionfront) == 'jpeg' || strtolower($extentionfront) == 'gif')
		{
			Filter::resizeImage($uploadedFrontPhoto->tempName, 1200, $docType . DIRECTORY_SEPARATOR, $frontfileName);
		}
		else
		{
			$uploadedFrontPhoto->saveAs($foldertoupload);
		}

		if ($uploadedBackPhoto != '')
		{
			if (strtolower($extentionback) == 'png' || strtolower($extentionback) == 'jpg' || strtolower($extentionback) == 'jpeg' || strtolower($extentionback) == 'gif')
			{
				Filter::resizeImage($uploadedBackPhoto->tempName, 1200, $docType . DIRECTORY_SEPARATOR, $backFileName);
			}
			else
			{
				$uploadedBackPhoto->saveAs($foldertouploadback);
			}

			$backPath = $DS . $serverId . $DS . 'contact' . $DS . 'document' . $DS . $folderPrefix . $DS . $cttId . $DS . $doctypeName . $DS . $backFileName;
		}

		$frontpath = $DS . $serverId . $DS . 'contact' . $DS . 'document' . $DS . $folderPrefix . $DS . $cttId . $DS . $doctypeName . $DS . $frontfileName;

		$result = [$frontpath, $backPath];
		return $result;
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
					$model->vnd_agreement_file_link	 = $vdoc['vd_file'];
					break;
				case 6:
					$model->vnd_firm_attach			 = $vdoc['vd_file'];
					break;
			}
		}
	}

	public function findAllByVndId($vendorId)
	{
		$sql = "SELECT
				vndagrmnt.vag_id,vndagrmnt.vag_soft_flag,vndagrmnt.vag_soft_path,vndagrmnt.vag_digital_agreement,vndagrmnt.vag_digital_sign,vndagrmnt.vag_active,vad.vd_agmt_status,
				if(votid.doc_file_front_path IS NOT NULL,votid.doc_file_front_path,'') as votfile_front,
				if(votid.doc_file_back_path IS NOT NULL,votid.doc_file_back_path,'') as votfile_back,
				if(votid.doc_remarks IS NOT NULL,votid.doc_remarks,'') as voter_remarks,
				votid.doc_id as voter_docid,
				votid.doc_status as votfile_status,
				if(licid.doc_file_front_path IS NOT NULL,licid.doc_file_front_path,'') as licfile_front,
				if(licid.doc_file_back_path IS NOT NULL,licid.doc_file_back_path,'') as licfile_back,
				if(licid.doc_remarks IS NOT NULL,licid.doc_remarks,'') as license_remarks,
				licid.doc_id as lic_docid,
				licid.doc_status as licfile_status,
				if(adhid.doc_file_front_path IS NOT NULL,adhid.doc_file_front_path,'') as adhfile_front,
				if(adhid.doc_file_back_path IS NOT NULL,adhid.doc_file_back_path,'') as adhfile_back,
				if(adhid.doc_remarks IS NOT NULL,adhid.doc_remarks,'') as aadhar_remarks,
				adhid.doc_id as adhid_docid,
				adhid.doc_status as adhfile_status,
				if(panid.doc_file_front_path IS NOT NULL,panid.doc_file_front_path,'') as panfile_front,
				if(panid.doc_file_back_path IS NOT NULL,panid.doc_file_back_path,'') as panfile_back,
				if(panid.doc_remarks IS NOT NULL,panid.doc_remarks,'') as pan_remarks,
				panid.doc_id as pan_docid,
				panid.doc_status as panfile_status,
				if(memoid.doc_file_front_path IS NOT NULL,memoid.doc_file_front_path,'') as memofile_front,
				if(memoid.doc_file_back_path IS NOT NULL,memoid.doc_file_back_path,'') as memofile_back,
				if(memoid.doc_remarks IS NOT NULL,memoid.doc_remarks,'') as memo_remarks,
				memoid.doc_id as memoid_docid,
				memoid.doc_status as memofile_status,
				IF(contact.ctt_profile_path IS NOT
				NULL, contact.ctt_profile_path, '') AS vnd_photo_path
				FROM vendors vnd
				INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd.vnd_id AND cp.cr_status = 1
				INNER JOIN contact ON contact.ctt_id = cp.cr_contact_id AND contact.ctt_id= contact.ctt_ref_code AND contact.ctt_active =1
				LEFT JOIN document votid ON ctt_voter_doc_id = votid.doc_id AND votid.doc_active = 1
				LEFT JOIN document licid ON ctt_license_doc_id = licid.doc_id AND licid.doc_active = 1
				LEFT JOIN document adhid ON ctt_aadhar_doc_id = adhid.doc_id AND adhid.doc_active = 1
				LEFT JOIN document panid ON ctt_pan_doc_id = panid.doc_id AND panid.doc_active = 1
				LEFT JOIN document memoid ON ctt_memo_doc_id = memoid.doc_id AND memoid.doc_active = 1
				LEFT JOIN vendor_agreement vndagrmnt ON vag_vnd_id = vnd_id
				LEFT JOIN vendor_agmt_docs vad ON vd_vnd_id =vnd_id
				WHERE vnd.vnd_id in (select v3.vnd_id
				FROM
				vendors v1
				INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code
				INNER JOIN vendors v3 ON v2.vnd_ref_code = v3.vnd_id
				WHERE
				v1.vnd_id ='$vendorId' )  AND ctt_active = 1";
		return DBUtil::queryAll($sql);
	}

	public function getUnapproved($arrVen)
	{
		$vendorIds = $arrVen['vendorIds'];

		// Select
		$selVoter	 = " , docvoter.doc_id as doc_id2, docvoter.doc_type as doc_type2, docvoter.doc_status as doc_status2, 
			docvoter.doc_file_front_path as doc_file_front_path2, docvoter.doc_file_back_path as doc_file_back_path2, 
			docvoter.doc_front_s3_data as doc_front_s3_data2, docvoter.doc_back_s3_data as doc_back_s3data2, docvoter.doc_remarks as doc_remarks2 ";
		$selAadhar	 = " , docaadhar.doc_id as doc_id3, docaadhar.doc_type as doc_type3, docaadhar.doc_status as doc_status3, 
			docaadhar.doc_file_front_path as doc_file_front_path3,docaadhar.doc_front_s3_data as doc_front_s3_data3,
			docaadhar.doc_file_back_path as doc_file_back_path3,docaadhar.doc_back_s3_data as doc_back_s3data3, docaadhar.doc_remarks as doc_remarks3 ";
		$selPan		 = " , docpan.doc_id as doc_id4, docpan.doc_type as doc_type4, docpan.doc_status as doc_status4, 
			docpan.doc_file_front_path as doc_file_front_path4, docpan.doc_front_s3_data as doc_front_s3_data4, 
			docpan.doc_file_back_path as doc_file_back_path4,docpan.doc_back_s3_data as doc_back_s3data4, docpan.doc_remarks as doc_remarks4 ";
		$selLicence	 = " , doclicence.doc_id as doc_id5, doclicence.doc_type as doc_type5, doclicence.doc_status as doc_status5, 
			doclicence.doc_file_front_path as doc_file_front_path5,doclicence.doc_front_s3_data as doc_front_s3_data5, 
			doclicence.doc_file_back_path as doc_file_back_path5,doclicence.doc_back_s3_data as doc_back_s3data5, doclicence.doc_remarks as doc_remarks5 ";
		$selMemo	 = " , docmemo.doc_id as doc_id6, docmemo.doc_type as doc_type6, docmemo.doc_status as doc_status6, 
			docmemo.doc_file_front_path as doc_file_front_path6,docmemo.doc_front_s3_data as doc_front_s3_data6, docmemo.doc_remarks as doc_remarks6 ";

		$sqlSelect = "SELECT ctt_id, IF(vnd1.vnd_ref_code IN ($vendorIds),1,0) AS vendorOrder,
			TRIM(Replace(Replace(Replace(ctt_voter_no,'\t',''),'\n',''),'\r','')) as ctt_voter_no,
			TRIM(Replace(Replace(Replace(ctt_aadhaar_no,'\t',''),'\n',''),'\r','')) as ctt_aadhaar_no,
			TRIM(Replace(Replace(Replace(ctt_pan_no,'\t',''),'\n',''),'\r','')) as ctt_pan_no ,
			TRIM(Replace(Replace(Replace(ctt_license_no,'\t',''),'\n',''),'\r','')) as ctt_license_no,
			ctt_first_name, ctt_last_name,ctt_business_name,ctt_user_type,ctt_business_type,ctt_is_verified, ctt_active ";

		// Join
		$joinVoter	 = " LEFT JOIN document as docvoter ON contact.ctt_voter_doc_id = docvoter.doc_id AND docvoter.doc_type = 2 AND docvoter.doc_active = 1 AND docvoter.doc_status=0 ";
		$joinAadhar	 = " LEFT JOIN document as docaadhar ON contact.ctt_aadhar_doc_id = docaadhar.doc_id AND docaadhar.doc_type = 3 AND docaadhar.doc_active = 1 AND docaadhar.doc_status=0 ";
		$joinPan	 = " LEFT JOIN document as docpan ON contact.ctt_pan_doc_id = docpan.doc_id AND docpan.doc_type = 4 AND docpan.doc_active = 1 AND docpan.doc_status=0 ";
		$joinLicence = " LEFT JOIN document as doclicence ON contact.ctt_license_doc_id = doclicence.doc_id AND doclicence.doc_type = 5 AND doclicence.doc_active = 1 AND doclicence.doc_status=0 ";
		$joinMemo	 = " LEFT JOIN document as docmemo ON contact.ctt_memo_doc_id = docmemo.doc_id AND docmemo.doc_type = 6 AND docmemo.doc_active = 1 AND docmemo.doc_status=0 ";

		$sqlJoin = " FROM vendors vnd1 
			INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd1.vnd_id AND vnd1.vnd_id = vnd1.vnd_ref_code AND cp.cr_status = 1 
			INNER JOIN contact ON contact.ctt_id = cp.cr_contact_id AND contact.ctt_active = 1 AND contact.ctt_id = contact.ctt_ref_code ";

		// Where
		$whereVoter		 = " ((docvoter.doc_id IS NOT NULL) AND ((docvoter.doc_file_front_path IS NOT NULL and  docvoter.doc_file_front_path!='') OR (docvoter.doc_file_back_path IS NOT NULL and docvoter.doc_file_back_path!=''))) ";
		$whereAadhar	 = " ((docaadhar.doc_id IS NOT NULL) AND ((docaadhar.doc_file_front_path IS NOT NULL and  docaadhar.doc_file_front_path!='') OR (docaadhar.doc_file_back_path IS NOT NULL and docaadhar.doc_file_back_path!=''))) ";
		$wherePan		 = " ((docpan.doc_id IS NOT NULL) AND (( docpan.doc_file_front_path IS NOT NULL and docpan.doc_file_front_path!='')  OR (docpan.doc_file_back_path IS NOT NULL and docpan.doc_file_back_path!=''))) ";
		$whereLicence	 = " ((doclicence.doc_id IS NOT NULL) AND (( doclicence.doc_file_front_path IS NOT NULL and  doclicence.doc_file_front_path!='' ) OR (doclicence.doc_file_back_path IS NOT NULL and doclicence.doc_file_back_path!=''))) ";
		$whereMemo		 = " ((docmemo.doc_id IS NOT NULL) AND (docmemo.doc_file_front_path IS NOT NULL and  docmemo.doc_file_front_path!='')) ";

		$where = ' WHERE vnd1.vnd_active>0 and contact.ctt_active <> 0 ';
		if (trim($this->doc_type) != '')
		{
			if (trim($this->doc_type) == 2)
			{
				$sqlSelect	 .= $selVoter;
				$sqlJoin	 .= $joinVoter;

				$where	 .= " AND docvoter.doc_type = 2 AND ";
				$where	 .= $whereVoter;
			}
			else if (trim($this->doc_type) == 3)
			{
				$sqlSelect	 .= $selAadhar;
				$sqlJoin	 .= $joinAadhar;

				$where	 .= " AND docaadhar.doc_type = 3 AND ";
				$where	 .= $whereAadhar;
			}
			else if (trim($this->doc_type) == 4)
			{
				$sqlSelect	 .= $selPan;
				$sqlJoin	 .= $joinPan;

				$where	 .= " AND docpan.doc_type = 4 AND ";
				$where	 .= $wherePan;
			}
			else if (trim($this->doc_type) == 5)
			{
				$sqlSelect	 .= $selLicence;
				$sqlJoin	 .= $joinLicence;

				$where	 .= " AND doclicence.doc_type = 5 AND ";
				$where	 .= $whereLicence;
			}
			else if (trim($this->doc_type) == 6)
			{
				$sqlSelect	 .= $selMemo;
				$sqlJoin	 .= $joinMemo;

				$where	 .= " AND docmemo.doc_type = 6 AND ";
				$where	 .= $whereMemo;
			}
		}
		else
		{
			$sqlSelect	 .= $selVoter . $selAadhar . $selPan . $selLicence . $selMemo;
			$sqlJoin	 .= $joinVoter . $joinAadhar . $joinPan . $joinLicence . $joinMemo;
			$where		 .= " AND (" . $whereVoter . " OR " . $whereAadhar . " OR " . $wherePan . " OR " . $whereLicence . " OR " . $whereMemo . ") ";
		}

		if (trim($this->contactname) != '')
		{
			$where .= " AND ((ctt_business_name LIKE '%" . $this->contactname . "%') OR (ctt_first_name LIKE '%" . $this->contactname . "%')
					OR (ctt_last_name LIKE '%" . $this->contactname . "%'))";
		}
		if (trim($arrVen['vnd_id']) != '')
		{
			$where .= " AND vnd1.vnd_id=" . $arrVen['vnd_id'];
		}

		$groupBy		 = " GROUP BY vnd1.vnd_ref_code ";
		$defaultOrder	 = " vendorOrder DESC, contact.ctt_modified_date ASC ";

		$sqlCount	 = "SELECT ctt_id " . $sqlJoin . $where . $groupBy;
		$sql		 = $sqlSelect . $sqlJoin . $where . $groupBy;

		if ($command == false)
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['vndname'],
					'defaultOrder'	 => $defaultOrder],
				'pagination'	 => ['pageSize' => 20],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql);
		}
	}

	public function getUnapproved_OLD($arrVen)
	{
		$where = ' and contact.ctt_active <> 0 ';
		if (trim($this->doc_type) != '')
		{
			if (trim($this->doc_type) == 2)
			{
				$where .= ' and docvoter.doc_type = 2';
			}
			else if (trim($this->doc_type) == 3)
			{
				$where .= ' and docaadhar.doc_type = 3';
			}
			else if (trim($this->doc_type) == 4)
			{
				$where .= ' and  docpan.doc_type = 4';
			}
			else if (trim($this->doc_type) == 5)
			{
				$where .= ' and doclicence.doc_type = 5';
			}
			else if (trim($this->doc_type) == 6)
			{
				$where .= ' and  docmemo.doc_type = 6';
			}
		}
		if (trim($this->contactname) != '')
		{
			$where .= " and  ((ctt_business_name LIKE '%" . $this->contactname . "%') OR (ctt_first_name LIKE '%" . $this->contactname . "%')
					OR (ctt_last_name LIKE '%" . $this->contactname . "%'))";
		}

		if (trim($arrVen['vnd_id']) != '')
		{
			$where .= " and vnd1.vnd_id=" . $arrVen['vnd_id'];
		}
		$vendorIds		 = $arrVen['vendorIds'];
		$sql			 = "SELECT ctt_id,
                        IF(vnd1.vnd_ref_code IN ($vendorIds),1,0) AS vendorOrder,
			TRIM(Replace(Replace(Replace(ctt_voter_no,'\t',''),'\n',''),'\r','')) as ctt_voter_no,
			TRIM(Replace(Replace(Replace(ctt_aadhaar_no,'\t',''),'\n',''),'\r','')) as ctt_aadhaar_no,
			TRIM(Replace(Replace(Replace(ctt_pan_no,'\t',''),'\n',''),'\r','')) as ctt_pan_no ,
			TRIM(Replace(Replace(Replace(ctt_license_no,'\t',''),'\n',''),'\r','')) as ctt_license_no,
			ctt_first_name, ctt_last_name,ctt_business_name,ctt_user_type,ctt_business_type,ctt_is_verified, ctt_active, docvoter.doc_id as doc_id2, docaadhar.doc_id as doc_id3,docpan.doc_id as doc_id4,doclicence.doc_id as doc_id5, docmemo.doc_id as doc_id6,
			docvoter.doc_type as doc_type2, docaadhar.doc_type as doc_type3, docpan.doc_type as doc_type4,doclicence.doc_type as doc_type5,docmemo.doc_type as doc_type6,docvoter.doc_status as doc_status2,docaadhar.doc_status as doc_status3,docpan.doc_status as doc_status4,doclicence.doc_status as doc_status5,docmemo.doc_status as doc_status6, docvoter.doc_file_front_path as doc_file_front_path2,docvoter.doc_file_back_path as doc_file_back_path2,docvoter.doc_front_s3_data as doc_front_s3_data2,docvoter.doc_back_s3_data as doc_back_s3data2,
			docaadhar.doc_file_front_path as doc_file_front_path3,docaadhar.doc_front_s3_data as doc_front_s3_data3,docaadhar.doc_file_back_path as doc_file_back_path3,docaadhar.doc_back_s3_data as doc_back_s3data3, docpan.doc_file_front_path as doc_file_front_path4, docpan.doc_front_s3_data as doc_front_s3_data4, docpan.doc_file_back_path as doc_file_back_path4,docpan.doc_back_s3_data as doc_back_s3data4, doclicence.doc_file_front_path as doc_file_front_path5,doclicence.doc_front_s3_data as doc_front_s3_data5, doclicence.doc_file_back_path as doc_file_back_path5,doclicence.doc_back_s3_data as doc_back_s3data5,docmemo.doc_file_front_path as doc_file_front_path6,docmemo.doc_front_s3_data as doc_front_s3_data6,docvoter.doc_remarks as doc_remarks2,

			docaadhar.doc_remarks as doc_remarks3,docpan.doc_remarks as doc_remarks4,doclicence.doc_remarks as doc_remarks5,docmemo.doc_remarks as doc_remarks6
			FROM vendors vnd1
			INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd1.vnd_id AND vnd1.vnd_id = vnd1.vnd_ref_code AND cp.cr_status = 1
			INNER JOIN contact ON contact.ctt_id = cp.cr_contact_id AND contact.ctt_active = 1 AND contact.ctt_id = contact.ctt_ref_code
			LEFT JOIN document as docvoter ON contact.ctt_voter_doc_id = docvoter.doc_id AND docvoter.doc_type = 2 AND docvoter.doc_active = 1 AND docvoter.doc_status=0
			LEFT JOIN document as docaadhar ON contact.ctt_aadhar_doc_id = docaadhar.doc_id AND docaadhar.doc_type = 3 AND docaadhar.doc_active = 1 AND docaadhar.doc_status=0
			LEFT JOIN document as docpan ON contact.ctt_pan_doc_id = docpan.doc_id AND docpan.doc_type = 4 AND docpan.doc_active = 1 AND docpan.doc_status=0
			LEFT JOIN document as doclicence ON contact.ctt_license_doc_id = doclicence.doc_id AND doclicence.doc_type = 5 AND doclicence.doc_active = 1 AND doclicence.doc_status=0
			LEFT JOIN document as docmemo ON contact.ctt_memo_doc_id = docmemo.doc_id AND docmemo.doc_type = 6 AND docmemo.doc_active = 1 AND docmemo.doc_status=0
			WHERE vnd1.vnd_active>0  $where  
					AND
					 (
	          ((docvoter.doc_id IS NOT NULL)     AND    ((docvoter.doc_file_front_path IS NOT NULL and  docvoter.doc_file_front_path!='')    OR (docvoter.doc_file_back_path IS NOT NULL and docvoter.doc_file_back_path!='')))
           OR ((docaadhar.doc_id IS NOT NULL)    AND    ((docaadhar.doc_file_front_path IS NOT NULL and  docaadhar.doc_file_front_path!='')  OR (docaadhar.doc_file_back_path IS NOT NULL and docaadhar.doc_file_back_path!='')))
           OR ((docpan.doc_id IS NOT NULL)       AND    (( docpan.doc_file_front_path IS NOT NULL and docpan.doc_file_front_path!='')  OR (docpan.doc_file_back_path IS NOT NULL and docpan.doc_file_back_path!='')))
           OR ((doclicence.doc_id IS NOT NULL)   AND    (( doclicence.doc_file_front_path IS NOT NULL and  doclicence.doc_file_front_path!='' )      OR (doclicence.doc_file_back_path IS NOT NULL and doclicence.doc_file_back_path!='')))
           OR ((docmemo.doc_id IS NOT NULL)      AND    (docmemo.doc_file_front_path IS NOT NULL and  docmemo.doc_file_front_path!=''))
		   )
                        GROUP BY vnd1.vnd_ref_code";
		$sqlCount		 = "SELECT ctt_id
							FROM vendors vnd1
							INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd1.vnd_id AND vnd1.vnd_id = vnd1.vnd_ref_code AND cp.cr_status = 1
							INNER JOIN contact ON contact.ctt_id = cp.cr_contact_id AND contact.ctt_active = 1 AND contact.ctt_id = contact.ctt_ref_code
							LEFT JOIN document as docvoter ON contact.ctt_voter_doc_id = docvoter.doc_id AND docvoter.doc_type = 2 AND docvoter.doc_active = 1 AND docvoter.doc_status=0
							LEFT JOIN document as docaadhar ON contact.ctt_aadhar_doc_id = docaadhar.doc_id AND docaadhar.doc_type = 3 AND docaadhar.doc_active = 1 AND docaadhar.doc_status=0
							LEFT JOIN document as docpan ON contact.ctt_pan_doc_id = docpan.doc_id AND docpan.doc_type = 4 AND docpan.doc_active = 1 AND docpan.doc_status=0
							LEFT JOIN document as doclicence ON contact.ctt_license_doc_id = doclicence.doc_id AND doclicence.doc_type = 5 AND doclicence.doc_active = 1 AND doclicence.doc_status=0
							LEFT JOIN document as docmemo ON contact.ctt_memo_doc_id = docmemo.doc_id AND docmemo.doc_type = 6 AND docmemo.doc_active = 1 AND docmemo.doc_status=0
							WHERE vnd1.vnd_active>0  $where  
							AND 
						   (
									((docvoter.doc_id IS NOT NULL)     AND    ((docvoter.doc_file_front_path IS NOT NULL and  docvoter.doc_file_front_path!='')    OR (docvoter.doc_file_back_path IS NOT NULL and docvoter.doc_file_back_path!='')))
									OR ((docaadhar.doc_id IS NOT NULL)    AND    ((docaadhar.doc_file_front_path IS NOT NULL and  docaadhar.doc_file_front_path!='')  OR (docaadhar.doc_file_back_path IS NOT NULL and docaadhar.doc_file_back_path!='')))
									OR ((docpan.doc_id IS NOT NULL)       AND    (( docpan.doc_file_front_path IS NOT NULL and docpan.doc_file_front_path!='')  OR (docpan.doc_file_back_path IS NOT NULL and docpan.doc_file_back_path!='')))
									OR ((doclicence.doc_id IS NOT NULL)   AND    (( doclicence.doc_file_front_path IS NOT NULL and  doclicence.doc_file_front_path!='' )      OR (doclicence.doc_file_back_path IS NOT NULL and doclicence.doc_file_back_path!='')))
									OR ((docmemo.doc_id IS NOT NULL)      AND    (docmemo.doc_file_front_path IS NOT NULL and  docmemo.doc_file_front_path!=''))
							)
							GROUP BY vnd1.vnd_ref_code";
		$defaultOrder	 = 'vendorOrder DESC,contact.ctt_modified_date ASC';

		if ($command == false)
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
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

	public function getUnapprovedDriver($arr)
	{
		$where			 = "";
		$selVoter		 = $selAadhar		 = $selPan			 = $selLicence		 = $selPolicever	 = "";
		$joinVoter		 = $joinAadhar		 = $joinPan		 = $joinLicence	 = $joinPolicever	 = "";
		$whereVoter		 = $whereAadhar	 = $wherePan		 = $whereLicence	 = $wherePolicever	 = "";

		if (trim($arr['doc_type']) == 2)
		{
			$selVoter	 = ", TRIM(Replace(Replace(Replace(ctt_voter_no,'\t',''),'\n',''),'\r','')) as ctt_voter_no, docvoter.doc_id as doc_id2, docvoter.doc_type as doc_type2, docvoter.doc_status as doc_status2, docvoter.doc_file_front_path as doc_file_front_path2, docvoter.doc_file_back_path as doc_file_back_path2, docvoter.doc_remarks as doc_remarks2 ";
			$joinVoter	 = "LEFT JOIN document as docvoter ON contact.ctt_voter_doc_id = docvoter.doc_id AND contact.ctt_voter_doc_id > 0 AND docvoter.doc_type = 2 AND docvoter.doc_active = 1 AND docvoter.doc_status=0 ";
			$whereVoter	 = "AND docvoter.doc_type = 2 AND ((docvoter.doc_id IS NOT NULL) AND ((docvoter.doc_file_front_path IS NOT NULL and docvoter.doc_file_front_path!='') OR (docvoter.doc_file_back_path IS NOT NULL and docvoter.doc_file_back_path!='')))";
		}
		else if (trim($arr['doc_type']) == 3)
		{
			$selAadhar	 = ", TRIM(Replace(Replace(Replace(ctt_aadhaar_no,'\t',''),'\n',''),'\r','')) as ctt_aadhaar_no, docaadhar.doc_id as doc_id3, docaadhar.doc_type as doc_type3, docaadhar.doc_status as doc_status3, docaadhar.doc_file_front_path as doc_file_front_path3, docaadhar.doc_file_back_path as doc_file_back_path3, docaadhar.doc_remarks as doc_remarks3 ";
			$joinAadhar	 = "LEFT JOIN document as docaadhar ON contact.ctt_aadhar_doc_id = docaadhar.doc_id AND contact.ctt_aadhar_doc_id > 0 AND docaadhar.doc_type = 3 AND docaadhar.doc_active = 1 AND docaadhar.doc_status=0 ";
			$whereAadhar = "AND docaadhar.doc_type = 3 AND ((docaadhar.doc_id IS NOT NULL) AND ((docaadhar.doc_file_front_path IS NOT NULL and  docaadhar.doc_file_front_path!='') OR (docaadhar.doc_file_back_path IS NOT NULL and docaadhar.doc_file_back_path!='')))";
		}
		else if (trim($arr['doc_type']) == 4)
		{
			$selPan		 = ", TRIM(Replace(Replace(Replace(ctt_pan_no,'\t',''),'\n',''),'\r','')) as ctt_pan_no, docpan.doc_id as doc_id4, docpan.doc_type as doc_type4, docpan.doc_status as doc_status4, docpan.doc_file_front_path as doc_file_front_path4, docpan.doc_file_back_path as doc_file_back_path4, docpan.doc_remarks as doc_remarks4 ";
			$joinPan	 = "LEFT JOIN document as docpan ON contact.ctt_pan_doc_id = docpan.doc_id AND contact.ctt_pan_doc_id > 0 AND docpan.doc_type = 4 AND docpan.doc_active = 1  AND docpan.doc_status=0 ";
			$wherePan	 = "AND docpan.doc_type = 4 AND ((docpan.doc_id IS NOT NULL) AND (( docpan.doc_file_front_path IS NOT NULL and docpan.doc_file_front_path!='') OR (docpan.doc_file_back_path IS NOT NULL and docpan.doc_file_back_path!='')))";
		}
		else if (trim($arr['doc_type']) == 5)
		{
			$selLicence		 = ", TRIM(Replace(Replace(Replace(ctt_license_no,'\t',''),'\n',''),'\r','')) as ctt_license_no, doclicence.doc_id as doc_id5, doclicence.doc_type as doc_type5, doclicence.doc_status as doc_status5, doclicence.doc_file_front_path as doc_file_front_path5, doclicence.doc_file_back_path as doc_file_back_path5, doclicence.doc_remarks as doc_remarks5 ";
			$joinLicence	 = "LEFT JOIN document as doclicence ON contact.ctt_license_doc_id = doclicence.doc_id AND contact.ctt_license_doc_id > 0 AND doclicence.doc_type = 5 AND doclicence.doc_active = 1  AND doclicence.doc_status=0 ";
			$whereLicence	 = "AND doclicence.doc_type = 5 AND ((doclicence.doc_id IS NOT NULL) AND (( doclicence.doc_file_front_path IS NOT NULL and  doclicence.doc_file_front_path!='' ) OR (doclicence.doc_file_back_path IS NOT NULL and doclicence.doc_file_back_path!='')))";
		}
		else if (trim($arr['doc_type']) == 7)
		{
			$selPolicever	 = ", docpolicever.doc_id as doc_id7, docpolicever.doc_type as doc_type7, docpolicever.doc_status as doc_status7, docpolicever.doc_file_front_path as doc_file_front_path7, docpolicever.doc_remarks as doc_remarks7 ";
			$joinPolicever	 = "LEFT JOIN document as docpolicever ON contact.ctt_police_doc_id = docpolicever.doc_id AND contact.ctt_police_doc_id > 0 AND docpolicever.doc_type = 7 AND docpolicever.doc_active = 1  AND docpolicever.doc_status=0 ";
			$wherePolicever	 = "AND docpolicever.doc_type = 7 AND ((docpolicever.doc_id IS NOT NULL) AND (docpolicever.doc_file_front_path IS NOT NULL and  docpolicever.doc_file_front_path!=''))";
		}

		if (trim($arr['contactname']) != '')
		{
			$where .= " AND ((ctt_business_name LIKE '%" . $arr['contactname'] . "%') OR (ctt_first_name LIKE '%" . $arr['contactname'] . "%') OR (ctt_last_name LIKE '%" . $arr['contactname'] . "%'))";
		}
		if (trim($arr['drv_id']) != '')
		{
			$where .= " AND drv.drv_id=" . $arr['drv_id'];
		}

		$driverIds = $arr['driverIds'];

		$sql = "SELECT ctt_id, ctt_first_name, ctt_last_name, ctt_business_name, ctt_user_type, ctt_business_type, 
				ctt_is_verified, ctt_active, IF(drv.drv_ref_code IN ($driverIds),1,0) AS driverOrder 
				{$selVoter} {$selAadhar} {$selPan} {$selLicence} {$selPolicever} 
				FROM drivers drv 
				INNER JOIN contact_profile AS cp ON cp.cr_is_driver = drv.drv_id AND cp.cr_status =1 AND drv.drv_id = drv.drv_ref_code 
				INNER JOIN contact AS contact ON contact.ctt_id = cp.cr_contact_id AND contact.ctt_id = contact.ctt_ref_code AND contact.ctt_active =1 
				{$joinVoter} {$joinAadhar} {$joinPan} {$joinLicence} {$joinPolicever} 
				WHERE drv.drv_active > 0 AND contact.ctt_active <> 0 {$where} 
				{$whereVoter} {$whereAadhar} {$wherePan} {$whereLicence} {$wherePolicever} 
				GROUP BY drv.drv_ref_code";
		if ($command == false)
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['drv_name'],
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

	public function getUnapprovedDriver_OLD($arr)
	{
		$where = ' and contact.ctt_active <> 0 ';
		if (trim($arr['doc_type']) != '')
		{
			if (trim($arr['doc_type']) == 2)
			{
				$where .= ' and docvoter.doc_type = 2';
			}
			else if (trim($arr['doc_type']) == 3)
			{
				$where .= ' and docaadhar.doc_type = 3';
			}
			else if (trim($arr['doc_type']) == 4)
			{
				$where .= ' and  docpan.doc_type = 4';
			}
			else if (trim($arr['doc_type']) == 5)
			{
				$where .= ' and doclicence.doc_type = 5';
			}
			else if (trim($arr['doc_type']) == 7)
			{
				$where .= ' and  docpolicever.doc_type = 7';
			}
		}
		if (trim($arr['contactname']) != '')
		{
			$where .= " and ((ctt_business_name LIKE '%" . $arr['contactname'] . "%') OR (ctt_first_name LIKE '%" . $arr['contactname'] . "%') OR (ctt_last_name LIKE '%" . $arr['contactname'] . "%'))";
		}

		if (trim($arr['drv_id']) != '')
		{
			$where .= " and drv.drv_id=" . $arr['drv_id'];
		}
		$driverIds		 = $arr['driverIds'];
		$sql			 = "SELECT   ctt_id,
                        IF(drv.drv_ref_code IN ($driverIds),1,0) AS driverOrder,
			TRIM(Replace(Replace(Replace(ctt_voter_no,'\t',''),'\n',''),'\r','')) as ctt_voter_no,
			TRIM(Replace(Replace(Replace(ctt_aadhaar_no,'\t',''),'\n',''),'\r','')) as ctt_aadhaar_no,
			TRIM(Replace(Replace(Replace(ctt_pan_no,'\t',''),'\n',''),'\r','')) as ctt_pan_no ,
			TRIM(Replace(Replace(Replace(ctt_license_no,'\t',''),'\n',''),'\r','')) as ctt_license_no,
			ctt_first_name,	ctt_last_name,ctt_business_name,ctt_user_type,ctt_business_type,ctt_is_verified,	ctt_active, docvoter.doc_id as doc_id2, docaadhar.doc_id as doc_id3,docpan.doc_id as doc_id4,doclicence.doc_id as doc_id5, docpolicever.doc_id as doc_id7,
		docvoter.doc_type as doc_type2,	docaadhar.doc_type as doc_type3, docpan.doc_type as doc_type4,doclicence.doc_type as doc_type5,docpolicever.doc_type as doc_type7,docvoter.doc_status as doc_status2,docaadhar.doc_status as doc_status3,docpan.doc_status as doc_status4,doclicence.doc_status as doc_status5,docpolicever.doc_status as doc_status7,docvoter.doc_file_front_path as doc_file_front_path2,docvoter.doc_file_back_path as doc_file_back_path2, 
		docaadhar.doc_file_front_path as doc_file_front_path3,docaadhar.doc_file_back_path as doc_file_back_path3,docpan.doc_file_front_path as doc_file_front_path4,	docpan.doc_file_back_path as doc_file_back_path4,doclicence.doc_file_front_path as doc_file_front_path5,	doclicence.doc_file_back_path as doc_file_back_path5,docpolicever.doc_file_front_path as doc_file_front_path7,docvoter.doc_remarks as doc_remarks2,
		docaadhar.doc_remarks as doc_remarks3,docpan.doc_remarks as doc_remarks4,doclicence.doc_remarks as doc_remarks5,docpolicever.doc_remarks as doc_remarks7
		FROM drivers drv   
		INNER JOIN contact_profile AS cp ON cp.cr_is_driver = drv.drv_id AND cp.cr_status =1 AND drv.drv_id = drv.drv_ref_code
		INNER JOIN contact AS contact ON contact.ctt_id = cp.cr_contact_id AND contact.ctt_id = contact.ctt_ref_code AND contact.ctt_active =1
		LEFT JOIN document as docvoter ON contact.ctt_voter_doc_id = docvoter.doc_id AND contact.ctt_voter_doc_id > 0 AND docvoter.doc_type = 2 AND docvoter.doc_active = 1 AND docvoter.doc_status=0  
		LEFT JOIN document as docaadhar ON contact.ctt_aadhar_doc_id = docaadhar.doc_id AND contact.ctt_aadhar_doc_id > 0 AND docaadhar.doc_type = 3 AND docaadhar.doc_active = 1 AND docaadhar.doc_status=0  
		LEFT JOIN document as docpan ON contact.ctt_pan_doc_id = docpan.doc_id AND contact.ctt_pan_doc_id > 0 AND docpan.doc_type = 4 AND docpan.doc_active = 1  AND docpan.doc_status=0 
		LEFT JOIN document as doclicence ON contact.ctt_license_doc_id = doclicence.doc_id AND contact.ctt_license_doc_id > 0 AND doclicence.doc_type = 5 AND doclicence.doc_active = 1  AND doclicence.doc_status=0 
		LEFT JOIN document as docpolicever ON contact.ctt_police_doc_id = docpolicever.doc_id AND contact.ctt_police_doc_id > 0 AND docpolicever.doc_type = 7 AND docpolicever.doc_active = 1  AND docpolicever.doc_status=0 
        WHERE drv.drv_active > 0  $where   AND (
	          ((docvoter.doc_id IS NOT NULL)     AND    ((docvoter.doc_file_front_path IS NOT NULL and  docvoter.doc_file_front_path!='')    OR (docvoter.doc_file_back_path IS NOT NULL and docvoter.doc_file_back_path!='')))
           OR ((docaadhar.doc_id IS NOT NULL)    AND    ((docaadhar.doc_file_front_path IS NOT NULL and  docaadhar.doc_file_front_path!='')  OR (docaadhar.doc_file_back_path IS NOT NULL and docaadhar.doc_file_back_path!='')))
           OR ((docpan.doc_id IS NOT NULL)       AND    (( docpan.doc_file_front_path IS NOT NULL and docpan.doc_file_front_path!='')  OR (docpan.doc_file_back_path IS NOT NULL and docpan.doc_file_back_path!='')))
           OR ((doclicence.doc_id IS NOT NULL)   AND    (( doclicence.doc_file_front_path IS NOT NULL and  doclicence.doc_file_front_path!='' )      OR (doclicence.doc_file_back_path IS NOT NULL and doclicence.doc_file_back_path!='')))
		   OR ((docpolicever.doc_id IS NOT NULL) AND    (docpolicever.doc_file_front_path IS NOT NULL and  docpolicever.doc_file_front_path!=''))
		   ) GROUP BY drv.drv_ref_code";
		$defaultOrder	 = 'driverOrder DESC,contact.ctt_modified_date ASC';
		$sqlCount		 = "SELECT  ctt_id
		FROM drivers drv   
		INNER JOIN contact_profile AS cp ON cp.cr_is_driver = drv.drv_id AND cp.cr_status =1 AND drv.drv_id = drv.drv_ref_code
		INNER JOIN contact AS contact ON contact.ctt_id = cp.cr_contact_id AND contact.ctt_id = contact.ctt_ref_code AND contact.ctt_active =1
        LEFT JOIN document as docvoter ON contact.ctt_voter_doc_id = docvoter.doc_id AND contact.ctt_voter_doc_id > 0 AND docvoter.doc_type = 2 AND docvoter.doc_active = 1 AND docvoter.doc_status=0  
		LEFT JOIN document as docaadhar ON contact.ctt_aadhar_doc_id = docaadhar.doc_id AND contact.ctt_aadhar_doc_id > 0 AND docaadhar.doc_type = 3 AND docaadhar.doc_active = 1 AND docaadhar.doc_status=0  
		LEFT JOIN document as docpan ON contact.ctt_pan_doc_id = docpan.doc_id AND contact.ctt_pan_doc_id > 0 AND docpan.doc_type = 4 AND docpan.doc_active = 1  AND docpan.doc_status=0 
		LEFT JOIN document as doclicence ON contact.ctt_license_doc_id = doclicence.doc_id AND contact.ctt_license_doc_id > 0 AND doclicence.doc_type = 5 AND doclicence.doc_active = 1  AND doclicence.doc_status=0 
		LEFT JOIN document as docpolicever ON contact.ctt_police_doc_id = docpolicever.doc_id AND contact.ctt_police_doc_id > 0 AND docpolicever.doc_type = 7 AND docpolicever.doc_active = 1  AND docpolicever.doc_status=0 
        WHERE drv.drv_active > 0  $where   AND (
	          ((docvoter.doc_id IS NOT NULL)     AND    ((docvoter.doc_file_front_path IS NOT NULL and  docvoter.doc_file_front_path!='')    OR (docvoter.doc_file_back_path IS NOT NULL and docvoter.doc_file_back_path!='')))
           OR ((docaadhar.doc_id IS NOT NULL)    AND    ((docaadhar.doc_file_front_path IS NOT NULL and  docaadhar.doc_file_front_path!='')  OR (docaadhar.doc_file_back_path IS NOT NULL and docaadhar.doc_file_back_path!='')))
           OR ((docpan.doc_id IS NOT NULL)       AND    (( docpan.doc_file_front_path IS NOT NULL and docpan.doc_file_front_path!='')  OR (docpan.doc_file_back_path IS NOT NULL and docpan.doc_file_back_path!='')))
           OR ((doclicence.doc_id IS NOT NULL)   AND    (( doclicence.doc_file_front_path IS NOT NULL and  doclicence.doc_file_front_path!='' )      OR (doclicence.doc_file_back_path IS NOT NULL and doclicence.doc_file_back_path!='')))
		   OR ((docpolicever.doc_id IS NOT NULL) AND    (docpolicever.doc_file_front_path IS NOT NULL and  docpolicever.doc_file_front_path!=''))
		   ) GROUP BY drv.drv_ref_code";
		echo $sql; exit;
		if ($command == false)
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['drv_name'],
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

	public $docType = [
		1	 => 'Agreement File',
		2	 => 'Voter ID',
		3	 => 'Aadhaar',
		4	 => 'PAN Card',
		5	 => 'Licence',
		6	 => 'Memorendum',
		7	 => 'Police Verification Certificate'
	];

	public function transferUnregData($unregModel, $vendorId, $contactId)
	{

		$userInfo = UserInfo::getInstance();
		if ($unregModel->uvr_vnd_voter_id_front_path != '')
		{
			$success1 = $this->saveVoterId($vendorId, $contactId, $unregModel->uvr_vnd_voter_id_front_path, $userInfo);
		}
		if ($unregModel->uvr_vnd_aadhaar_front_path != '')
		{
			$success2 = $this->saveAadharId($vendorId, $contactId, $unregModel->uvr_vnd_aadhaar_front_path, $userInfo);
		}
		if ($unregModel->uvr_vnd_pan_front_path != '')
		{
			$success3 = $this->savePanId($vendorId, $contactId, $unregModel->uvr_vnd_pan_front_path, $userInfo);
		}
		if ($unregModel->uvr_vnd_licence_front_path != '')
		{
			$success4 = $this->saveLicenceFront($vendorId, $contactId, $unregModel->uvr_vnd_licence_front_path, $userInfo);
		}
		return true;
	}

	public function saveVoterId($vendorId, $contactId, $path, UserInfo $userInfo = null)
	{
		$success = false;
		try
		{
			if ($path != '' && $vendorId != '' && $contactId != '')
			{
				$model						 = new Document();
				$this->updateExistingByIdType($vendorId, $contactId, 2);
				$model->scenario			 = 'saveDoc';
				$model->doc_type			 = 2;
				$model->doc_file_front_path	 = $path;
				$model->doc_approved_at		 = NULL;
				$model->doc_approved_by		 = NULL;
				if ($model->validate())
				{
					if ($model->save())
					{
						$modelContact					 = Contact::model()->findByPk($contactId);
						$modelContact->ctt_voter_doc_id	 = $model->doc_id;
						$modelContact->update();
						$success						 = true;
						$errors							 = [];
						$event_id						 = VendorsLog::VENDOR_FILE_UPLOAD;
						$logDesc						 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_VOTERID_UPLOAD);
						VendorsLog::model()->createLog($vendorId, $logDesc, UserInfo::getInstance(), $event_id, false, false);
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

	public function saveAadharId($vendorId, $contactId, $path, UserInfo $userInfo = null)
	{
		$success = false;
		try
		{
			if ($path != '' && $vendorId != '' && $contactId != '')
			{
				$model						 = new Document();
				$model->scenario			 = 'saveDoc';
				$this->updateExistingByIdType($vendorId, $contactId, 3);
				$model->doc_type			 = 3;
				$model->doc_file_front_path	 = $path;
				$model->doc_approved_at		 = NULL;
				$model->doc_approved_by		 = NULL;
				if ($model->validate())
				{
					if ($model->save())
					{
						$modelContact					 = Contact::model()->findByPk($contactId);
						$modelContact->ctt_aadhar_doc_id = $model->doc_id;
						$modelContact->update();
						$success						 = true;
						$errors							 = [];
						$event_id						 = VendorsLog::VENDOR_FILE_UPLOAD;
						$logDesc						 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_AADHAAR_UPLOAD);
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

	public function savePanId($vendorId, $contactId, $path, UserInfo $userInfo = null)
	{
		$success = false;
		try
		{
			if ($path != '' && $vendorId != '' && $contactId != '')
			{
				$model						 = new Document();
				$model->scenario			 = 'saveDoc';
				$this->updateExistingByIdType($vendorId, $contactId, 4);
				$model->doc_type			 = 4;
				$model->doc_file_front_path	 = $path;
				$model->doc_approved_at		 = NULL;
				$model->doc_approved_by		 = NULL;
				if ($model->validate())
				{
					if ($model->save())
					{
						$modelContact					 = Contact::model()->findByPk($contactId);
						$modelContact->ctt_pan_doc_id	 = $model->doc_id;
						$modelContact->update();
						$success						 = true;
						$errors							 = [];
						$event_id						 = VendorsLog::VENDOR_FILE_UPLOAD;
						$logDesc						 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_PAN_UPLOAD);
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

	public function saveLicenceFront($vendorId, $contactId, $path, UserInfo $userInfo = null)
	{
		$success = false;
		try
		{
			if ($path != '' && $vendorId != '' && $contactId != '')
			{
				$model						 = new Document();
				$model->scenario			 = 'saveDoc';
				$this->updateExistingByIdType($vendorId, $contactId, 5);
				$model->doc_type			 = 5;
				$model->doc_file_front_path	 = $path;
				$model->doc_approved_at		 = NULL;
				$model->doc_approved_by		 = NULL;
				if ($model->validate())
				{
					if ($model->save())
					{
						$modelContact						 = Contact::model()->findByPk($contactId);
						$modelContact->ctt_license_doc_id	 = $model->doc_id;
						$modelContact->update();
						$success							 = true;
						$errors								 = [];
						$event_id							 = VendorsLog::VENDOR_FILE_UPLOAD;
						$logDesc							 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_LICENSE_UPLOAD);
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

	public function updateExistingByIdType($vndId, $contactId, $vdType)
	{
		if ($vdType == 2)
		{
			$docType = "ctt_voter_doc_id";
		}
		if ($vdType == 3)
		{
			$docType = "ctt_aadhar_doc_id";
		}
		if ($vdType == 4)
		{
			$docType = "ctt_pan_doc_id";
		}
		if ($vdType == 5)
		{
			$docType = "ctt_license_doc_id";
		}
		if ($vdType == 6)
		{
			$docType = "ctt_memo_doc_id";
		}
		$sql		 = "UPDATE `document` SET document.doc_active=0 WHERE doc_active =1 AND doc_id = (SELECT $docType
						FROM vendors vnd
						INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd.vnd_id AND cp.cr_status =1 AND vnd.vnd_id = vnd.vnd_ref_code AND vnd.vnd_active > 0
						INNER JOIN contact ON contact.ctt_id = cp.cr_contact_id AND contact.ctt_active = 1 AND contact.ctt_id = contact.ctt_ref_code
						WHERE contact.ctt_id = $contactId AND vnd.vnd_id = $vndId)";
		$cdb		 = DBUtil::command($sql);
		$rowsUpdated = $cdb->execute();
		return $rowsUpdated;
	}

	public function getDocumnetByDocIdDocType($docId, $docType)
	{
		$model = Document::model()->find('doc_id=:docId AND doc_type=:docType', ['docId' => $docId, 'docType' => $docType]);
		return $model;
	}

	public static function getVendorDocListForR4A()
	{
		$sql = "SELECT vnd1.vnd_id,
				contact.ctt_id,
				contact.ctt_voter_doc_id,
				vdoc.doc_id as vdoc_id,
				contact.ctt_aadhar_doc_id,
				adoc.doc_id as adoc_id,
				contact.ctt_pan_doc_id,
				pdoc.doc_id as pdoc_id,
				contact.ctt_license_doc_id,
				ldoc.doc_id as ldoc_id,
				vendor_agreement.vag_id,
				vendor_agreement.vag_digital_ver,
				vendor_agreement.vag_digital_flag,
				vendor_agreement.vag_digital_date,
				vnd1.vnd_active,
				vendor_stats.vrs_approve_driver_count,
				vendor_stats.vrs_approve_car_count,
				IF(
				vendor_agreement.vag_id > 0
				AND vendor_stats.vrs_approve_driver_count > 0
				AND vendor_stats.vrs_approve_car_count > 0
				AND contact.ctt_pan_doc_id > 0 AND pdoc.doc_id > 0
				AND
				(
				(
				contact.ctt_voter_doc_id > 0 AND vdoc.doc_id > 0
				) OR(
				contact.ctt_aadhar_doc_id > 0 AND adoc.doc_id > 0
				) OR(
				contact.ctt_license_doc_id > 0 AND ldoc.doc_id > 0
				)
				),
				1,
				0
				) AS r4a
				FROM vendors vnd1
				INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd1.vnd_id AND cp.cr_status = 1 AND vnd1.vnd_ref_code = vnd1.vnd_id
				INNER JOIN contact ON contact.ctt_id = cp.cr_contact_id AND contact.ctt_id = contact.ctt_ref_code AND contact.ctt_active =1
				LEFT JOIN `document` AS pdoc ON pdoc.doc_id = contact.ctt_pan_doc_id AND pdoc.doc_active = 1 AND pdoc.doc_status = 1 AND pdoc.doc_type = 4
				LEFT JOIN `document` AS vdoc ON vdoc.doc_id = contact.ctt_voter_doc_id AND vdoc.doc_active = 1 AND vdoc.doc_status = 1 AND vdoc.doc_type = 2
				LEFT JOIN `document` AS adoc ON adoc.doc_id = contact.ctt_aadhar_doc_id AND adoc.doc_active = 1 AND adoc.doc_status = 1 AND adoc.doc_type = 3
				LEFT JOIN `document` AS ldoc ON ldoc.doc_id = contact.ctt_license_doc_id AND ldoc.doc_status = 1 AND ldoc.doc_active = 1 AND ldoc.doc_type = 5
				JOIN `vendor_stats` ON vendor_stats.vrs_vnd_id = vnd1.vnd_id
				LEFT JOIN `vendor_agreement` ON vendor_agreement.vag_vnd_id = vnd1.vnd_id AND vendor_agreement.vag_active = 1 AND vendor_agreement.vag_digital_flag = 1 AND vendor_agreement.vag_digital_ver >= '171219'
				WHERE vnd1.vnd_active =3
				Group by vnd1.vnd_ref_code
				ORDER BY r4a DESC";

		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	/**
	 * 
	 * @param type $docId
	 * @return boolean|Document
	 */
	public static function getById($docId)
	{
		$model = Document::model()->find('doc_id = :documentId and doc_active = 1', array('documentId' => $docId));
		if (!$model)
		{
			return false;
		}
		return $model;
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

	public function checkApproveDocById($docId = "")
	{
		$sql		 = "SELECT IF(document.doc_id > 0, 1, 0) as check_approve
                FROM   document
                WHERE  doc_active = 1 AND doc_status = 1 AND doc_id = $docId";
		$valApprove	 = DBUtil::command($sql)->queryScalar();
		$valApprove	 = ($valApprove > 0) ? $valApprove : 0;
		return $valApprove;
	}

	public function checkDocumentStatus($docId = "")
	{
		$sql = "SELECT doc_status FROM document WHERE  doc_active = 1  AND doc_id = " . $docId . "";

		$valApprove = DBUtil::command($sql)->queryScalar();
		return $valApprove;
	}

	public function findAllByVndId1($vnd_id)
	{
		$sql = "SELECT voter.doc_file_front_path AS voterFontPath,voter.doc_file_back_path AS voterBackPath,
				pan.doc_file_front_path AS panFontPath,pan.doc_file_back_path AS panBackPath,
				aadher.doc_file_front_path AS aadherFontPath,aadher.doc_file_back_path AS aadherBackPath,
				licence.doc_file_front_path AS licenceFontPath,licence.doc_file_back_path AS licenceBackPath,
				memo.doc_file_front_path AS memoFontPath,
				vendor_agreement.vag_soft_path AS agreementPath,
                vendor_agreement.vag_digital_agreement AS digitalAgreementPath
				FROM vendors
				INNER JOIN contact_profile cp ON cp.cr_is_vendor = vendors.vnd_id AND cp.cr_status = 1
				INNER JOIN contact ON ctt_id = cp.cr_contact_id AND ctt_id = ctt_ref_code AND ctt_active = 1
				LEFT JOIN document voter ON voter.doc_id=contact.ctt_voter_doc_id
				LEFT JOIN document pan ON pan.doc_id=contact.ctt_pan_doc_id
				LEFT JOIN document aadher ON aadher.doc_id=contact.ctt_aadhar_doc_id
				LEFT JOIN document licence ON licence.doc_id=contact.ctt_license_doc_id
				LEFT JOIN document memo ON memo.doc_id=contact.ctt_memo_doc_id
				LEFT JOIN vendor_agreement ON vendor_agreement.vag_vnd_id=vendors.vnd_id
				WHERE 
				vendors.vnd_id in (select v3.vnd_id
			    FROM vendors v1
				INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code
				INNER JOIN vendors v3 ON v2.vnd_ref_code = v3.vnd_id
			    WHERE	v1.vnd_id ='$vnd_id')  and  vendors.vnd_active=1";

		return DBUtil::queryRow($sql);
	}

	public function checkDocumentUpload($vndId)
	{
		$docData		 = $this->findAllByVndId($vndId);
		$documentUpload	 = 0;
		if (count($docData) > 0)
		{
			IF ($docData[0]['licfile_front'] != '' && ($docData[0]['votfile_front'] != '' || $docData[0]['adhfile_front'] != '' || $docData[0]['panfile_front'] != '' || $docData[0]['licfile_front'] != ''))
			{
				$documentUpload = 1;
			}
		}
		return $documentUpload;
	}

	public function updateRejectedDoc($vendorId)
	{
		$sql = "UPDATE `vendor_docs` SET `vd_active`=0 WHERE `vd_vnd_id` = $vendorId AND `vd_status` = 2";

		$cdb		 = DBUtil::command($sql);
		$rowsUpdated = $cdb->execute();
		return $rowsUpdated;
	}

	public function getDocTypeList()
	{
		$list = $this->docType;
		return $list;
	}

	public function rejectDriverDocument($doc_id, $drv_id, $vendor_ids = '', $userInfo = null)
	{
		$transaction = Yii::app()->db->beginTransaction();
		try
		{
			$success			 = false;
			$modeld				 = Document::model()->findByPk($doc_id);
			$msgData			 = $this->getMsgByType($modeld->doc_type);
			$modeld->doc_status	 = 2;
			$modeld->doc_remarks = $msgData['remarks'];
			if ($modeld->save())
			{
				DriversLog::model()->createLog($drv_id, $modeld->doc_remarks, $userInfo, $msgData['event_id'], false, false);
				if ($vendor_ids != '')
				{
					$vendors = Vendors::getVendorsByIds($vendor_ids);
					if (count($vendors) > 0)
					{
						$drvModel = Drivers::model()->findByPk($drv_id);
						foreach ($vendors as $val)
						{
							if (isset($val['vnd_id']) && $val['vnd_id'] > 0)
							{
								//$isLastLogin = AppTokens::model()->checkVendorLastLogin($val['vnd_id']);
								$message	 = " Document (" . $msgData['doc'] . ") for Driver " . $drvModel->drv_name . " has been rejected (" . $msgData['remarks'] . "). Please verify and re-upload document properly";
								//if ($isLastLogin == 1)
								//{
								$vendorName	 = ($val['vnd_owner'] != '') ? $val['vnd_owner'] . ',' : $val['vnd_name'] . ',';
								$smsMessage	 = "Dear " . $vendorName . $message . ' - Gozocabs';
								$payLoadData = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
								$success	 = AppTokens::model()->notifyVendor($val['vnd_id'], $payLoadData, $smsMessage, "LICENSE PAPER REJECTED");
								Logger::create("Notification->" . $smsMessage, CLogger::LEVEL_INFO);
								//}								
								//$sms		 = new smsWrapper();
								//$sms->sendAlertMessageVendor(91, $val['vnd_id'], $smsMessage, SmsLog::SMS_VENDOR_DRIVER_PAPER_REJECTED);
								//Logger::create("Sms->" . $smsMessage, CLogger::LEVEL_INFO);
							}
						}
					}
				}

				$success = true;
				if ($success == true)
				{
					$desc = $drv_id . " ### " . $msgData['doc'] . " Rejected\n";
					Logger::create($desc, CLogger::LEVEL_INFO);
					$transaction->commit();
				}
			}
			else
			{
				throw new Exception("Reject document not yet saved.\n\t\t" . json_encode($modeld->getErrors()));
			}
		}
		catch (Exception $e)
		{
			Logger::create("Not Reject.\n\t\t" . $e->getMessage(), CLogger::LEVEL_ERROR);
			$transaction->rollback();
		}
		return $success;
	}

	public function getMsgByType($type)
	{
		$returnData = [];
		if ($type == 5)
		{
			$returnData = ['remarks' => 'Driver License Paper expired. Upload latest papers with new expiration date', 'event_id' => DriversLog::DRIVER_DL_REJECT, 'doc' => 'Driver License'];
		}
		return $returnData;
	}

	public function getListReadyApproval()
	{
		$sql = "SELECT * FROM (SELECT 
				drv1.drv_id,
				IF(voter.doc_id IS NOT NULL,1,0)+IF(license.doc_id IS NOT NULL,1,0) totalDocScore,
				IF(voter.doc_id IS NOT NULL AND voter.doc_status = 0,1,0)+IF(license.doc_id IS NOT NULL AND license.doc_status = 0,1,0) updateDocScore
				FROM drivers drv1
                INNER JOIN drivers AS drv ON drv.drv_ref_code = drv1.drv_id AND drv.drv_active = 1
                INNER JOIN contact_profile cp ON cp.cr_is_driver = drv.drv_id AND cp.cr_status = 1
				INNER JOIN contact ON ctt_id = cp.cr_contact_id AND contact.ctt_active = 1 
				LEFT JOIN document voter ON ctt_voter_doc_id = voter.doc_id AND voter.doc_type = 2 AND voter.doc_file_front_path IS NOT NULL
				LEFT JOIN document license ON ctt_voter_doc_id = license.doc_id AND license.doc_type = 5 AND license.doc_file_front_path IS NOT NULL
				GROUP BY drv1.drv_ref_code) a
				where updateDocScore > 0
				ORDER BY updateDocScore DESC";
		return DBUtil::queryAll($sql);
	}

	public function findApproveList()
	{
		$sql = "SELECT drv1.drv_ref_code as drv_id,drv1.drv_name
				FROM drivers
				INNER JOIN drivers as drv1 ON drv1.drv_id = drivers.drv_ref_code AND drv1.drv_active=1
				INNER JOIN contact_profile as cp on cp.cr_is_driver = drv1.drv_id and cp.cr_status = 1
				INNER JOIN contact ON contact.ctt_id = cp.cr_contact_id and contact.ctt_active = 1 
				INNER JOIN contact_phone ON contact_phone.phn_contact_id = contact.ctt_id AND contact_phone.phn_is_verified = 1
				INNER JOIN document ON ctt_license_doc_id = doc_id AND doc_type = 5 AND doc_file_front_path IS NOT NULL AND doc_status = 1 AND doc_active = 1
				WHERE drv1.drv_approved IN (0,2,3)
				AND contact.ctt_is_name_dl_matched <> 2
				AND (CURDATE() < (ctt_license_exp_date) AND ctt_license_exp_date IS NOT NULL AND ctt_license_exp_date <> '1970-01-01')
				GROUP BY drv1.drv_ref_code ";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public function findDisapproveList()
	{
		$sql = "SELECT drv1.drv_ref_code as drv_id,drv1.drv_name,doc_id
				FROM drivers
				INNER JOIN drivers as drv1 on drv1.drv_id = drivers.drv_ref_code AND drv1.drv_active=1
				INNER JOIN contact_profile as cp on cp.cr_is_driver = drv1.drv_id and cp.cr_status = 1
				INNER JOIN contact on contact.ctt_id = cp.cr_contact_id and contact.ctt_id = contact.ctt_ref_code and contact.ctt_active = 1
				INNER JOIN document ON contact.ctt_license_doc_id = doc_id AND doc_type = 5 AND doc_file_front_path IS NOT NULL
				AND doc_status = 2 AND doc_active = 1
				WHERE drv1.drv_approved =1
				AND (contact.ctt_license_exp_date IS NULL OR contact.ctt_license_exp_date = '1970-01-01' OR contact.ctt_is_name_dl_matched = 2
				OR (contact.ctt_license_exp_date IS NOT NULL AND (CURDATE() > contact.ctt_license_exp_date))) GROUP BY drv1.drv_ref_code";

		return DBUtil::query($sql, DBUtil::SDB());
	}

	public function findAllByDrvId($cttId)
	{
		if (trim($cttId) == null || trim($cttId) == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$params		 = array('cttId' => trim($cttId));
		$sql		 = "Select contact.ctt_profile_path as drv_photo_path,docvoter.doc_id as doc_id2, docaadhar.doc_id as doc_id3, docpan.doc_id as doc_id4, doclicence.doc_id as doc_id5, 
				docpolicever.doc_id as doc_id7, docvoter.doc_type as doc_type2, docaadhar.doc_type as doc_type3, 
				docpan.doc_type as doc_type4, doclicence.doc_type as doc_type5, docpolicever.doc_type as doc_type7,
				docvoter.doc_status as doc_status2, docaadhar.doc_status as doc_status3, docpan.doc_status as doc_status4,
				doclicence.doc_status as doc_status5, docpolicever.doc_status as doc_status7,
				docvoter.doc_file_front_path as doc_file_front_path2, docvoter.doc_file_back_path as doc_file_back_path2,docvoter.doc_status as doc_voter_status, 
				docaadhar.doc_file_front_path as doc_file_front_path3, docaadhar.doc_file_back_path as doc_file_back_path3,docaadhar.doc_status as doc_aadhar_status,
				docpan.doc_file_front_path as doc_file_front_path4, docpan.doc_file_back_path as doc_file_back_path4,docpan.doc_status as doc_pan_status,
				doclicence.doc_file_front_path as doc_file_front_path5, doclicence.doc_file_back_path as doc_file_back_path5,doclicence.doc_status as doc_license_status,
				docpolicever.doc_file_front_path as doc_file_front_path7,docpolicever.doc_status as doc_police_status
				from contact 
				LEFT JOIN document as docvoter ON contact.ctt_voter_doc_id = docvoter.doc_id AND  docvoter.doc_type = 2 AND docvoter.doc_active = 1 
				LEFT JOIN document as docaadhar ON contact.ctt_aadhar_doc_id = docaadhar.doc_id AND  docaadhar.doc_type = 3 AND docaadhar.doc_active = 1 
				LEFT JOIN document as docpan ON contact.ctt_pan_doc_id = docpan.doc_id AND  docpan.doc_type = 4 AND docpan.doc_active = 1 
				LEFT JOIN document as doclicence ON contact.ctt_license_doc_id = doclicence.doc_id AND  doclicence.doc_type = 5 AND doclicence.doc_active = 1 
				LEFT JOIN document as docpolicever ON contact.ctt_police_doc_id = docpolicever.doc_id AND  docpolicever.doc_type = 7 AND docpolicever.doc_active = 1 
				where contact.ctt_id =:cttId";
		$contactAll	 = DBUtil::queryAll($sql, DBUtil::SDB(), $params);
		return $contactAll;
	}

	public function missingDocsByDrdIds($drdIds)
	{
		$sql = "SELECT GROUP_CONCAT(missing SEPARATOR ',') as missing_docs FROM (                    
                      SELECT CONCAT(IF(document.doc_type=5,'License',''),
                        IF(document.doc_type=2,'Voter Card',''),
                        IF(document.doc_type=4,'Pan Card',''),
                        IF(document.doc_type=3,'Aadhar Card',''),
                        IF(document.doc_type=7,'Police Verification','')
                        ) as missing FROM `document` WHERE document.doc_id IN ($drdIds)
                    )a";
		return DBUtil::command($sql)->queryScalar();
	}

	public function getUnapprovedDoc($drvId, $cttId)
	{
		$listDocs			 = Document::model()->findAllByDrvId($cttId);
		$voterStatus		 = $panStatus			 = $aadharStatus		 = $driLicenceStatus	 = $policeVerfStatus	 = 0;
		$count				 = 1;
		if (count($listDocs) > 0)
		{
			if (($listDocs[0]['doc_license_status'] == 0 || $listDocs[0]['doc_license_status'] == 1) && $listDocs[0]['doc_file_front_path5'] != "")
			{
				$driLicenceStatus	 = 1;
				$count				 = ($count - 1);
			}
		}
		return ['count' => $count, 'doc' => ['license' => $driLicenceStatus]];
	}

	public function resetTempApprovedDrivers()
	{
		$sql	 = "UPDATE document SET doc_temp_approved = 0, doc_temp_approved_at = NULL  "
				. "WHERE doc_temp_approved = 1 AND (TIMESTAMPDIFF(HOUR,doc_temp_approved_at,NOW())>23 OR doc_temp_approved_at IS NULL)";
		$result	 = DBUtil::command($sql)->execute();
	}

	public function setStatus($arrContactModel, $objModelContact, $arrDocumentModel, $objModeldocument, $btnType)
	{
		$transaction = DBUtil::beginTransaction();
		$userInfo	 = UserInfo::getInstance();
		$returnSet	 = new ReturnSet();
		try
		{
			switch ($arrDocumentModel['doc_type'])
			{
				case '2':
					$event_id	 = ($btnType == 'approve') ? ContactLog::CONTACT_VOTERID_APPROVE : ContactLog::CONTACT_VOTERID_REJECT;
					$desc		 = ($btnType == 'approve') ? "Voter Approved" : "Voter Rejected";
					Contact::model()->updateContact($arrDocumentModel['doc_id'], 2, $arrContactModel['ctt_id'], $arrContactModel['ctt_voter_no']);
					ContactLog::model()->createLog($arrContactModel['ctt_id'], $desc, $event_id, null);
					$returnSet	 = Document::model()->isApprove($arrContactModel, $arrDocumentModel, $objModeldocument, 2, $btnType, $userInfo);
					break;
				case '3':
					$event_id	 = ($btnType == 'approve') ? ContactLog::CONTACT_AADHAAR_APPROVE : ContactLog::CONTACT_AADHAAR_REJECT;
					$desc		 = ($btnType == 'approve') ? "Aadhaar Approved" : "Aadhaar Rejected";
					Contact::model()->updateContact($arrDocumentModel['doc_id'], 3, $arrContactModel['ctt_id'], $arrContactModel['ctt_aadhaar_no']);
					ContactLog::model()->createLog($arrContactModel['ctt_id'], $desc, $event_id, null);
					$returnSet	 = Document::model()->isApprove($arrContactModel, $arrDocumentModel, $objModeldocument, 3, $btnType, $userInfo);
					break;
				case '4':
					$event_id	 = ($btnType == 'approve') ? ContactLog::CONTACT_PAN_APPROVE : ContactLog::CONTACT_PAN_REJECT;
					$desc		 = ($btnType == 'approve') ? "Pan Approved" : "Pan Rejected";
					Contact::model()->updateContact($arrDocumentModel['doc_id'], 4, $arrContactModel['ctt_id'], $arrContactModel['ctt_pan_no']);
					ContactLog::model()->createLog($arrContactModel['ctt_id'], $desc, $event_id, null);
					$returnSet	 = Document::model()->isApprove($arrContactModel, $arrDocumentModel, $objModeldocument, 4, $btnType, $userInfo);
					break;
				case '5':
					$event_id	 = ($btnType == 'approve') ? ContactLog::CONTACT_LICENSE_APPROVE : ContactLog::CONTACT_LICENSE_REJECT;
					$desc		 = ($btnType == 'approve') ? "License  Approved" : "License Rejected";
					Contact::model()->updateContact($arrDocumentModel['doc_id'], 5, $arrContactModel['ctt_id'], $arrContactModel['ctt_license_no'], $arrContactModel['ctt_license_exp_date'], $arrContactModel['ctt_first_name'], $arrContactModel['ctt_last_name'], 1);
					ContactLog::model()->createLog($arrContactModel['ctt_id'], $desc, $event_id, null);
					$returnSet	 = Document::model()->isApprove($arrContactModel, $arrDocumentModel, $objModeldocument, 5, $btnType, $userInfo);
					break;
				case '6':
					$event_id	 = ($btnType == 'approve') ? ContactLog::CONTACT_MEMORANDUM_APPROVE : ContactLog::CONTACT_MEMORANDUM_REJECT;
					$desc		 = ($btnType == 'approve') ? "Memorandum  Approved" : "Memorandum Rejected";
					Contact::model()->updateContact($arrDocumentModel['doc_id'], 6, $arrContactModel['ctt_id'], "");
					ContactLog::model()->createLog($arrContactModel['ctt_id'], $desc, $event_id, null);
					$returnSet	 = Document::model()->isApprove($arrContactModel, $arrDocumentModel, $objModeldocument, 6, $btnType, $userInfo);
					break;
				case '7':
					$event_id	 = ($btnType == 'approve') ? ContactLog::CONTACT_POLICE_VERIFICATION_APPROVE : ContactLog::CONTACT_POLICE_VERIFICATION_REJECT;
					$desc		 = ($btnType == 'approve') ? "Police Verification Approved" : "Police Verification Rejected";
					Contact::model()->updateContact($arrDocumentModel['doc_id'], 7, $arrContactModel['ctt_id'], "");
					ContactLog::model()->createLog($arrContactModel['ctt_id'], $desc, $event_id, null);
					$returnSet	 = Document::model()->isApprove($arrContactModel, $arrDocumentModel, $objModeldocument, 7, $btnType, $userInfo);
					break;
			}
			DBUtil::commitTransaction($transaction);
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

	public function isApprove($arrContactModel, $arrDocumentModel, $objModeldocument, $docType, $btnType, $userInfo)
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		$message	 = "";
		try
		{
			$objModeldocument->doc_status		 = $btnType == 'approve' ? 1 : 2;
			$objModeldocument->doc_approved_by	 = $userInfo->userId;
			$objModeldocument->doc_approved_at	 = date("Y-m-d H:i:s");
			$objModeldocument->doc_remarks		 = $arrDocumentModel['doc_remarks'];
			if ($objModeldocument->doc_temp_approved == 1)
			{
				$objModeldocument->doc_temp_approved	 = 0;
				$objModeldocument->doc_temp_approved_at	 = NULL;
			}
			if ($btnType == 'approve' && $docType == 5)
			{
				$dmodel		 = Drivers::model()->findByDriverContactID($arrContactModel['ctt_id']);
				$trip_type	 = implode(',', $arrContactModel['ctt_trip_type']);
				if ($dmodel->drv_id > 0 && $trip_type != '')
				{
					Drivers::updateTrips($dmodel->drv_id, $trip_type);
				}
			}
			if ($docType == 2)
			{
				$message .= $btntype == 'approve' ? "Voter Id Approved" : "Voter id Rejected";
			}
			else if ($docType == 3)
			{
				$message .= $btntype == 'approve' ? "Aadhaar Approved" : "Aadhaar Rejected";
			}
			else if ($docType == 4)
			{
				$message .= $btntype == 'approve' ? "Pan Approved" : "Pan Rejected";
			}
			else if ($docType == 5)
			{
				$message .= $btntype == 'approve' ? "License Approved" : "License Rejected";
			}
			else if ($docType == 6)
			{
				$message .= $btntype == 'approve' ? "Memorandum Approved" : "Memorandum Rejected";
			}
			else if ($docType == 7)
			{
				$message .= $btntype == 'approve' ? "Police Verification Approved" : "Police Verification Rejected";
			}
			$res = $objModeldocument->save();
			if (!$res)
			{
				$returnSet->setErrors($this->getErrors(), 1);
				throw new CHttpException("Failed to approved document", 1);
			}
			DBUtil::commitTransaction($transaction);
			$returnSet->setStatus(true);
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

	public function updateDriverDocument($data, $photo, $photo_tmp)
	{

		$driverId					 = $data->id;
		$doc_type					 = $data->docType;
		$doc_subtype				 = $data->docSubType;
		#$doc_subtype=;
		$model						 = Drivers::model()->findByPk($driverId);
		$oldDocsData['photoFile']	 = $model->drvContact->ctt_profile_path;
		$returnSet					 = new ReturnSet();
		$returnSet->setStatus(true);
		$user_info					 = UserInfo::getInstance();
		$dr							 = Yii::app()->basePath;
		if ($photo != '' && $doc_type == 1)
		{
			//Drivers::model()->saveDocument($model->drv_id, $modelDocVoter->doc_file_front_path, $user_info, $type);
			/*$type								 = 'profile';
			$result1							 = Drivers::model()->saveDriverImage($photo, $photo_tmp, $model->drv_id, $model->drv_contact_id, $type);
			$path1								 = str_replace("\\", "\\\\", $result1['path']);
			$model->drvContact->ctt_profile_path = $path1;

			if (!$model->drvContact->save())
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors($model->drvContact->getErrors());
				$newDocsData['photoFile']	 = $result1['path'];
				$getOldDifferenceDocs		 = array_diff_assoc($oldDocsData, $newDocsData);
				$changesForLog				 = "<br> Old Values: Driver selfie " . $this->getModificationMSG($getOldDifferenceDocs, false);
				$event_id					 = DriversLog::DRIVER_MODIFIED;
				$desc						 = "Driver modified | ";
				$desc						 .= $changesForLog;
				DriversLog::model()->createLog($model->drv_id, $desc, UserInfo::getInstance(), $event_id, false, false);
				return $returnSet;
			}*/
			
			$cttId = $model->drvContact->ctt_id;
			$contactModel	 = Contact::model()->findByPk($cttId);
			$image	 = $photo;
			$profileImage	 = CUploadedFile::getInstanceByName('photo');
			if ($profileImage != "")
			{
				$path = $contactModel->saveDcoProfileImage($profileImage);
			}

		}

		if ($doc_type == 2)
		{

			if ($model->drvContact->ctt_voter_doc_id != "")
			{
				$checkApprove = $this->checkApproveDocById($model->drvContact->ctt_voter_doc_id);
			}
			if ($checkApprove == 1)
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors("Voter ID already exists.\n\t\t");
				return $returnSet;
			}

			if ($model->drvContact->ctt_voter_doc_id != "")
			{
				$modelDocVoter = Document::model()->findByPk($model->drvContact->ctt_voter_doc_id);
			}
			else
			{
				$modelDocVoter = new Document();
			}


			$modelDocVoter->isDocsApp	 = true;
			$modelDocVoter->entity_id	 = $model->drv_contact_id;
			$modelDocVoter->doc_type	 = $doc_type;

			$modelDocVoter->isDocsApp	 = true;
			$modelDocVoter->entity_id	 = $model->drv_contact_id;
			$modelDocVoter->doc_type	 = $doc_type;

			if ($doc_subtype == 'voter_id')
			{
				$type										 = 'voterid';
				$modelDocVoter->local_doc_file_front_path	 = $doc_subtype;

				$modelDocVoter->add();
				Contact::model()->updateContact($modelDocVoter->doc_id, $modelDocVoter->doc_type, $model->drv_contact_id, '');
				$success = Drivers::model()->saveDocument($model->drv_id, $modelDocVoter->doc_file_front_path, $user_info, $type);
				if (!file_exists($dr . $modelDocVoter->doc_file_front_path))
				{
					$success = false;
				}
				if (!$success)
				{
					$returnSet->setStatus(false);
					$returnSet->setErrors('Failed to upload voter front.');
					return $returnSet;
				}
			}
			if ($doc_subtype == 'voter_back_id')
			{
				$type									 = 'voterbackid';
				$modelDocVoter->local_doc_file_back_path = $doc_subtype;
				$modelDocVoter->add();
				Contact::model()->updateContact($modelDocVoter->doc_id, $modelDocVoter->doc_type, $model->drv_contact_id, '');
				$success								 = Drivers::model()->saveDocument($model->drv_id, $modelDocVoter->doc_file_back_path, $user_info, $type);
				if (!file_exists($dr . $modelDocVoter->doc_file_back_path))
				{
					$success = false;
				}
				if (!$success)
				{
					$returnSet->setStatus(false);
					$returnSet->setErrors('Failed to upload voter back.');
					return $returnSet;
				}
			}
		}
		if ($doc_type == 3)
		{
			if ($model->drvContact->ctt_aadhar_doc_id != "")
			{
				$checkApprove = $this->checkApproveDocById($model->drvContact->ctt_aadhar_doc_id);
			}
			if ($checkApprove == 1)
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors("Aadhaar Id already exists.\n\t\t");
				return $returnSet;
			}
			else
			{
				if ($model->drvContact->ctt_aadhar_doc_id != "")
				{
					$modelDocAadhar = Document::model()->findByPk($model->drvContact->ctt_aadhar_doc_id);
				}
				else
				{
					$modelDocAadhar = new Document();
				}
				$modelDocAadhar->isDocsApp	 = true;
				$modelDocAadhar->entity_id	 = $model->drv_contact_id;
				$modelDocAadhar->doc_type	 = $doc_type;

				if ($doc_subtype == 'aadhaar')
				{
					$type										 = 'aadhar';
					$modelDocAadhar->local_doc_file_front_path	 = $doc_subtype;
					$modelDocAadhar->add();
					Contact::model()->updateContact($modelDocAadhar->doc_id, $modelDocAadhar->doc_type, $model->drv_contact_id, '');
					$success									 = Drivers::model()->saveDocument($model->drv_id, $modelDocAadhar->doc_file_front_path, $user_info, $type);
					if (!file_exists($dr . $modelDocAadhar->doc_file_front_path))
					{
						$success = false;
					}
					if (!$success)
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors('Failed to upload aadhaar front.');
						return $returnSet;
					}
				}
				if ($doc_subtype == 'aadhaar_back')
				{
					$type										 = 'aadharback';
					$modelDocAadhar->local_doc_file_back_path	 = $doc_subtype;
					$modelDocAadhar->add();
					Contact::model()->updateContact($modelDocAadhar->doc_id, $modelDocAadhar->doc_type, $model->drv_contact_id, '');
					$success									 = Drivers::model()->saveDocument($model->drv_id, $modelDocAadhar->doc_file_back_path, $user_info, $type);
					if (!file_exists($dr . $modelDocAadhar->doc_file_back_path))
					{
						$success = false;
					}
					if (!$success)
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors('Failed to upload aadhaar back.');
						return $returnSet;
					}
				}
			}
		}
		if ($doc_type == 4)
		{
			if ($model->drvContact->ctt_pan_doc_id != "")
			{
				$checkApprove = $this->checkApproveDocById($model->drvContact->ctt_pan_doc_id);
			}
			if ($checkApprove == 1)
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors("Pan already exists.\n\t\t");
				return $returnSet;
			}
			else
			{
				if ($model->drvContact->ctt_pan_doc_id != "")
				{
					$modelDocPan = Document::model()->findByPk($model->drvContact->ctt_pan_doc_id);
				}
				else
				{
					$modelDocPan = new Document();
				}
				$modelDocPan->isDocsApp	 = true;
				$modelDocPan->entity_id	 = $model->drv_contact_id;
				$modelDocPan->doc_type	 = $doc_type;

				if ($doc_subtype == 'pan')
				{
					$type									 = 'pan';
					$modelDocPan->local_doc_file_front_path	 = $doc_subtype;
					$modelDocPan->add();
					Contact::model()->updateContact($modelDocPan->doc_id, $modelDocPan->doc_type, $model->drv_contact_id, '');
					$success								 = Drivers::model()->saveDocument($model->drv_id, $modelDocPan->doc_file_front_path, $user_info, $type);
					if (!file_exists($dr . $modelDocPan->doc_file_front_path))
					{
						$success = false;
					}
					if (!$success)
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors('Failed to upload pan front.');
						return $returnSet;
					}
				}
				if ($doc_subtype == 'pan_back')
				{
					$type									 = 'panback';
					$modelDocPan->local_doc_file_back_path	 = $doc_subtype;
					$modelDocPan->add();
					Contact::model()->updateContact($modelDocPan->doc_id, $modelDocPan->doc_type, $model->drv_contact_id, '');
					$success								 = Drivers::model()->saveDocument($model->drv_id, $modelDocPan->doc_file_back_path, $user_info, $type);
					if (!file_exists($dr . $modelDocPan->doc_file_back_path))
					{
						$success = false;
					}
					if (!$success)
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors('Failed to upload pan back.');
						return $returnSet;
					}
				}
			}
		}
		if ($doc_type == 5)
		{
			if ($model->drvContact->ctt_license_doc_id != "")
			{
				$checkApprove = $this->checkApproveDocById($model->drvContact->ctt_license_doc_id);
			}
			if ($checkApprove == 1)
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors("License already exists.\n\t\t");
				return $returnSet;
			}
			else
			{
				if ($model->drvContact->ctt_license_doc_id != "")
				{
					$modelDocLicense = Document::model()->findByPk($model->drvContact->ctt_license_doc_id);
				}
				else
				{
					$modelDocLicense = new Document();
				}
				$modelDocLicense->isDocsApp	 = true;
				$modelDocLicense->entity_id	 = $model->drv_contact_id;
				$modelDocLicense->doc_type	 = $doc_type;

				if ($doc_subtype == 'license')
				{
					$type										 = 'license';
					$modelDocLicense->local_doc_file_front_path	 = $doc_subtype;
					$modelDocLicense->add();
					Contact::model()->updateContact($modelDocLicense->doc_id, $modelDocLicense->doc_type, $model->drv_contact_id, '');
					$success									 = Drivers::model()->saveDocument($model->drv_id, $modelDocLicense->doc_file_front_path, $user_info, $type);
					if (!file_exists($dr . $modelDocLicense->doc_file_front_path))
					{
						$success = false;
					}
					if (!$success)
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors('Failed to upload license front.');
						return $returnSet;
					}
				}
				if ($doc_subtype == 'license_back')
				{
					$type										 = 'licenseback';
					$modelDocLicense->local_doc_file_back_path	 = $doc_subtype;
					$modelDocLicense->add();
					Contact::model()->updateContact($modelDocLicense->doc_id, $modelDocLicense->doc_type, $model->drv_contact_id, '');
					$success = Drivers::model()->saveDocument($model->drv_id, $modelDocLicense->doc_file_back_path, $user_info, $type);
					if (!file_exists($dr . $modelDocLicense->doc_file_back_path))
					{
						$success = false;
					}
					if (!$success)
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors('Failed to upload license back.');
						return $returnSet;
					}
				}
			}
		}
		if ($doc_type == 7)
		{
			if ($model->drvContact->ctt_police_doc_id != "")
			{
				$checkApprove = $this->checkApproveDocById($model->drvContact->ctt_police_doc_id);
			}
			if ($checkApprove == 1)
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors("Police verification already exists.\n\t\t");
				return $returnSet;
			}
			else
			{
				if ($model->drvContact->ctt_police_doc_id > 0)
				{
					$modelDocPoliceVerify = Document::model()->findByPk($model->drvContact->ctt_police_doc_id);
				}
				else
				{
					$modelDocPoliceVerify = new Document();
				}
				$modelDocPoliceVerify->local_doc_file_front_path = 'pvc_verification'; //trim($doc_subtype);
				$modelDocPoliceVerify->isDocsApp				 = true;
				$modelDocPoliceVerify->entity_id				 = $model->drv_contact_id;
				$modelDocPoliceVerify->doc_type					 = $doc_type;

				if ($doc_subtype == 'pvc_verification')
				{
					$type	 = 'policever';
					$modelDocPoliceVerify->add();
					Contact::model()->updateContact($modelDocPoliceVerify->doc_id, $modelDocPoliceVerify->doc_type, $model->drv_contact_id, '');
					$success = Drivers::model()->saveDocument($model->drv_id, $modelDocPoliceVerify->doc_file_front_path, $user_info, $type);
					if (!file_exists($dr . $modelDocPoliceVerify->doc_file_front_path))
					{
						$success = false;
					}
					if (!$success)
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors('Failed to upload police verification.');
						return $returnSet;
					}
				}
			}
		}
		return $returnSet;
	}

	public function updateDriverDoc($driverId, $photo, $photo_tmp, $doc_type, $doc_subtype)
	{
		$model = Drivers::model()->findByPk($driverId);

		$oldDocsData['photoFile']	 = $model->drvContact->ctt_profile_path;
		$returnSet					 = new ReturnSet();
		$returnSet->setStatus(true);
		$user_info					 = UserInfo::getInstance();
		if ($photo != '')
		{
			$type	 = 'profile';
			$result1 = Drivers::model()->saveDriverImage($photo, $photo_tmp, $model->drv_id, $model->drv_contact_id, $type);
			$path1	 = str_replace("\\", "\\\\", $result1['path']);

			$model->drvContact->ctt_profile_path = $path1;

			if (!$model->drvContact->save())
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors($model->drvContact->getErrors());
				$newDocsData['photoFile']	 = $result1['path'];
				$getOldDifferenceDocs		 = array_diff_assoc($oldDocsData, $newDocsData);
				$changesForLog				 = "<br> Old Values: Driver Selfie " . $this->getModificationMSG($getOldDifferenceDocs, false);
				$event_id					 = DriversLog::DRIVER_MODIFIED;
				$desc						 = "Driver modified | ";
				$desc						 .= $changesForLog;
				DriversLog::model()->createLog($model->drv_id, $desc, UserInfo::getInstance(), $event_id, false, false);
				return $returnSet;
			}
		}
		if ($doc_type == 2)
		{
			if ($model->drvContact->ctt_voter_doc_id != "")
			{
				$checkApprove = $this->checkApproveDocById($model->drvContact->ctt_voter_doc_id);
			}
			if ($checkApprove == 1)
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors("Voter Id already exists.\n\t\t");
				return $returnSet;
			}

			if ($model->drvContact->ctt_voter_doc_id != "")
			{
				$modelDocVoter = Document::model()->findByPk($model->drvContact->ctt_voter_doc_id);
			}
			else
			{
				$modelDocVoter = new Document();
			}
			$modelDocVoter->isDocsApp	 = true;
			$modelDocVoter->entity_id	 = $model->drv_contact_id;
			$modelDocVoter->doc_type	 = $doc_type;

			if ($doc_subtype == 'voter_id')
			{
				$type										 = 'voterid';
				$modelDocVoter->local_doc_file_front_path	 = $doc_subtype;
				$modelDocVoter->add();
				Contact::model()->updateContact($modelDocVoter->doc_id, $modelDocVoter->doc_type, $model->drv_contact_id, '');
				$success									 = Drivers::model()->saveDocument($model->drv_id, $modelDocVoter->doc_file_front_path, $user_info, $type);
				if (!$success)
				{
					$returnSet->setStatus(false);
					$returnSet->setErrors('Failed to upload voter front.');
					return $returnSet;
				}
			}
			if ($doc_subtype == 'voter_back_id')
			{
				$type									 = 'voterbackid';
				$modelDocVoter->local_doc_file_back_path = $doc_subtype;
				$modelDocVoter->add();
				Contact::model()->updateContact($modelDocVoter->doc_id, $modelDocVoter->doc_type, $model->drv_contact_id, '');
				$success								 = Drivers::model()->saveDocument($model->drv_id, $modelDocVoter->doc_file_back_path, $user_info, $type);
				if (!$success)
				{
					$returnSet->setStatus(false);
					$returnSet->setErrors('Failed to upload voter back.');
					return $returnSet;
				}
			}
		}
		if ($doc_type == 3)
		{
			if ($model->drvContact->ctt_aadhar_doc_id != "")
			{
				$checkApprove = $this->checkApproveDocById($model->drvContact->ctt_aadhar_doc_id);
			}
			if ($checkApprove == 1)
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors("Aadhaar Id already exists.\n\t\t");
				return $returnSet;
			}
			else
			{
				if ($model->drvContact->ctt_aadhar_doc_id != "")
				{
					$modelDocAadhar = Document::model()->findByPk($model->drvContact->ctt_aadhar_doc_id);
				}
				else
				{
					$modelDocAadhar = new Document();
				}
				$modelDocAadhar->isDocsApp	 = true;
				$modelDocAadhar->entity_id	 = $model->drv_contact_id;
				$modelDocAadhar->doc_type	 = $doc_type;

				if ($doc_subtype == 'aadhaar')
				{
					$type										 = 'aadhar';
					$modelDocAadhar->local_doc_file_front_path	 = $doc_subtype;
					$modelDocAadhar->add();
					Contact::model()->updateContact($modelDocAadhar->doc_id, $modelDocAadhar->doc_type, $model->drv_contact_id, '');
					$success									 = Drivers::model()->saveDocument($model->drv_id, $modelDocAadhar->doc_file_front_path, $user_info, $type);
					if (!$success)
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors('Failed to upload aadhaar front.');
						return $returnSet;
					}
				}
				if ($doc_subtype == 'aadhaar_back')
				{
					$type										 = 'aadharback';
					$modelDocAadhar->local_doc_file_back_path	 = $doc_subtype;
					$modelDocAadhar->add();
					Contact::model()->updateContact($modelDocAadhar->doc_id, $modelDocAadhar->doc_type, $model->drv_contact_id, '');
					$success									 = Drivers::model()->saveDocument($model->drv_id, $modelDocAadhar->doc_file_back_path, $user_info, $type);
					if (!$success)
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors('Failed to upload aadhaar back.');
						return $returnSet;
					}
				}
			}
		}
		if ($doc_type == 4)
		{
			if ($model->drvContact->ctt_pan_doc_id != "")
			{
				$checkApprove = $this->checkApproveDocById($model->drvContact->ctt_pan_doc_id);
			}
			if ($checkApprove == 1)
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors("Pan already exists.\n\t\t");
				return $returnSet;
			}
			else
			{
				if ($model->drvContact->ctt_pan_doc_id != "")
				{
					$modelDocPan = Document::model()->findByPk($model->drvContact->ctt_pan_doc_id);
				}
				else
				{
					$modelDocPan = new Document();
				}
				$modelDocPan->isDocsApp	 = true;
				$modelDocPan->entity_id	 = $model->drv_contact_id;
				$modelDocPan->doc_type	 = $doc_type;

				if ($doc_subtype == 'pan')
				{
					$type									 = 'pan';
					$modelDocPan->local_doc_file_front_path	 = $doc_subtype;
					$modelDocPan->add();
					Contact::model()->updateContact($modelDocPan->doc_id, $modelDocPan->doc_type, $model->drv_contact_id, '');
					$success								 = Drivers::model()->saveDocument($model->drv_id, $modelDocPan->doc_file_front_path, $user_info, $type);
					if (!$success)
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors('Failed to upload pan front.');
						return $returnSet;
					}
				}
				if ($doc_subtype == 'pan_back')
				{
					$type									 = 'panback';
					$modelDocPan->local_doc_file_back_path	 = $doc_subtype;
					$modelDocPan->add();
					Contact::model()->updateContact($modelDocPan->doc_id, $modelDocPan->doc_type, $model->drv_contact_id, '');
					$success								 = Drivers::model()->saveDocument($model->drv_id, $modelDocPan->doc_file_back_path, $user_info, $type);
					if (!$success)
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors('Failed to upload pan back.');
						return $returnSet;
					}
				}
			}
		}
		if ($doc_type == 5)
		{
			if ($model->drvContact->ctt_license_doc_id != "")
			{
				$checkApprove = $this->checkApproveDocById($model->drvContact->ctt_license_doc_id);
			}
			if ($checkApprove == 1)
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors("License already exists.\n\t\t");
				return $returnSet;
			}
			else
			{
				if ($model->drvContact->ctt_license_doc_id != "")
				{
					$modelDocLicense = Document::model()->findByPk($model->drvContact->ctt_license_doc_id);
				}
				else
				{
					$modelDocLicense = new Document();
				}
				$modelDocLicense->isDocsApp	 = true;
				$modelDocLicense->entity_id	 = $model->drv_contact_id;
				$modelDocLicense->doc_type	 = $doc_type;

				if ($doc_subtype == 'license')
				{
					$type										 = 'license';
					$modelDocLicense->local_doc_file_front_path	 = $doc_subtype;
					$modelDocLicense->add();
					Contact::model()->updateContact($modelDocLicense->doc_id, $modelDocLicense->doc_type, $model->drv_contact_id, '');
					$success									 = Drivers::model()->saveDocument($model->drv_id, $modelDocLicense->doc_file_front_path, $user_info, $type);
					if (!$success)
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors('Failed to upload license front.');
						return $returnSet;
					}
				}
				if ($doc_subtype == 'license_back')
				{
					$type										 = 'licenseback';
					$modelDocLicense->local_doc_file_back_path	 = $doc_subtype;
					$modelDocLicense->add();
					Contact::model()->updateContact($modelDocLicense->doc_id, $modelDocLicense->doc_type, $model->drv_contact_id, '');
					$success									 = Drivers::model()->saveDocument($model->drv_id, $modelDocLicense->doc_file_back_path, $user_info, $type);
					if (!$success)
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors('Failed to upload license back.');
						return $returnSet;
					}
				}
			}
		}
		if ($doc_type == 7)
		{
			if ($model->drvContact->ctt_police_doc_id != "")
			{
				$checkApprove = $this->checkApproveDocById($model->drvContact->ctt_police_doc_id);
			}
			if ($checkApprove == 1)
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors("Police verification already exists.\n\t\t");
				return $returnSet;
			}
			else
			{
				if ($model->drvContact->ctt_police_doc_id > 0)
				{
					$modelDocPoliceVerify = Document::model()->findByPk($model->drvContact->ctt_police_doc_id);
				}
				else
				{
					$modelDocPoliceVerify = new Document();
				}
				$modelDocPoliceVerify->local_doc_file_front_path = 'pvc_verification'; //trim($doc_subtype);
				$modelDocPoliceVerify->isDocsApp				 = true;
				$modelDocPoliceVerify->entity_id				 = $model->drv_contact_id;
				$modelDocPoliceVerify->doc_type					 = $doc_type;

				if ($doc_subtype == 'pvc_verification')
				{
					$type	 = 'policever';
					$modelDocPoliceVerify->add();
					Contact::model()->updateContact($modelDocPoliceVerify->doc_id, $modelDocPoliceVerify->doc_type, $model->drv_contact_id, '');
					$success = Drivers::model()->saveDocument($model->drv_id, $modelDocPoliceVerify->doc_file_front_path, $user_info, $type);
					if (!$success)
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors('Failed to upload police verification.');
						return $returnSet;
					}
				}
			}
		}
		return $returnSet;
	}

	public function updateVendorDoc($model, $photo, $photo_tmp, $doc_type, $doc_subtype)
	{
		Logger::trace("voter doc id" . $model->vndContact->ctt_voter_doc_id);
		$returnSet	 = new ReturnSet();
		$user_info	 = UserInfo::getInstance();
		$contactId	 = ContactProfile::getByEntityId($model->vnd_id, UserInfo::TYPE_VENDOR);
		if ($doc_type == 2)
		{
			if ($model->vndContact->ctt_voter_doc_id != "")
			{
				$checkApprove = $this->checkApproveDocById($model->vndContact->ctt_voter_doc_id);
			}
			if ($checkApprove == 1)
			{
				$errors = ("Voter Id already exists.\n\t\t");
				$returnSet->setStatus(false);
				$returnSet->setErrors($errors);
			}
			else
			{
				if ($model->vndContact->ctt_voter_doc_id != "")
				{
					$modelDocVoter = Document::model()->findByPk($model->vndContact->ctt_voter_doc_id);
				}
				else
				{
					$modelDocVoter = new Document();
				}
				$modelDocVoter->isDocsApp	 = true;
				$modelDocVoter->entity_id	 = $contactId ? $contactId : $model->vnd_contact_id;
				$modelDocVoter->doc_type	 = $doc_type;

				if ($doc_subtype == 'voter_id')
				{
					Logger::trace("voter id type" . $modelDocVoter->doc_id);
					$type										 = 'voterid';
					$modelDocVoter->local_doc_file_front_path	 = $doc_subtype;
					$success									 = $modelDocVoter->add();
					Contact::model()->updateContact($modelDocVoter->doc_id, $modelDocVoter->doc_type, $modelDocVoter->entity_id, '');
					if (!$success)
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors("Voter Id creation failed.");
					}
					else
					{
						Logger::trace("voter contact  id" . $contactId);
						$returnSet->setStatus(true);
					}
				}
				if ($doc_subtype == 'voter_back_id')
				{
					$type									 = 'voterbackid';
					$modelDocVoter->local_doc_file_back_path = $doc_subtype;
					$modelDocVoter->add();
					Contact::model()->updateContact($modelDocVoter->doc_id, $modelDocVoter->doc_type, $modelDocVoter->entity_id, '');
					$success								 = Vendors::model()->saveDocument($model->vnd_id, $modelDocVoter->doc_file_back_path, $user_info, $type);
					if (!$success)
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors("Voter Back creation failed.");
					}
					else
					{
						$returnSet->setStatus(true);
					}
				}
			}
		}
		if ($doc_type == 3)
		{
			if ($model->vndContact->ctt_aadhar_doc_id != "")
			{
				$checkApprove = $this->checkApproveDocById($model->vndContact->ctt_aadhar_doc_id);
			}
			if ($checkApprove == 1)
			{
				$success = false;
				$errors	 = ("Aadhaar Id already exists.\n\t\t");
				$returnSet->setStatus(false);
				$returnSet->setErrors($errors);
			}
			else
			{
				Logger::trace("ctt aadhar id" . $model->vndContact->ctt_aadhar_doc_id);
				if ($model->vndContact->ctt_aadhar_doc_id != "")
				{
					$modelDocAadhar = Document::model()->findByPk($model->vndContact->ctt_aadhar_doc_id);
				}
				else
				{
					$modelDocAadhar = new Document();
				}
				$modelDocAadhar->isDocsApp	 = true;
				$modelDocAadhar->entity_id	 = $contactId ? $contactId : $model->vnd_contact_id;
				$modelDocAadhar->doc_type	 = $doc_type;

				if ($doc_subtype == 'aadhaar')
				{
					Logger::trace("type" . $doc_subtype);
					$type										 = 'aadhar';
					$modelDocAadhar->local_doc_file_front_path	 = $doc_subtype;
					$modelDocAadhar->add();
					Contact::model()->updateContact($modelDocAadhar->doc_id, $modelDocAadhar->doc_type, $modelDocAadhar->entity_id, '');
					$success									 = Vendors::model()->saveDocument($model->vnd_id, $modelDocAadhar->doc_file_front_path, $user_info, $type);
					if (!$success)
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors("Aadhar Id creation failed.");
					}
					else
					{
						$returnSet->setStatus(true);
					}
				}
				if ($doc_subtype == 'aadhaar_back')
				{
					$type										 = 'aadharback';
					$modelDocAadhar->local_doc_file_back_path	 = $doc_subtype;
					$modelDocAadhar->add();
					Contact::model()->updateContact($modelDocAadhar->doc_id, $modelDocAadhar->doc_type, $modelDocAadhar->entity_id, '');
					$success									 = Vendors::model()->saveDocument($model->vnd_id, $modelDocAadhar->doc_file_back_path, $user_info, $type);
					if (!$success)
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors("Aadhar Back creation failed.");
					}
					else
					{
						$returnSet->setStatus(true);
					}
				}
			}
		}
		if ($doc_type == 4)
		{
			if ($model->vndContact->ctt_pan_doc_id != "")
			{
				$checkApprove = $this->checkApproveDocById($model->vndContact->ctt_pan_doc_id);
			}
			if ($checkApprove == 1)
			{
				$success = false;
				$errors	 = ("Pan already exists.\n\t\t");
				$returnSet->setStatus(false);
				$returnSet->setErrors($errors);
			}
			else
			{
				if ($model->vndContact->ctt_pan_doc_id != "")
				{
					$modelDocPan = Document::model()->findByPk($model->vndContact->ctt_pan_doc_id);
				}
				else
				{
					$modelDocPan = new Document();
				}
				$modelDocPan->isDocsApp	 = true;
				$modelDocPan->entity_id	 = $contactId ? $contactId : $model->vnd_contact_id;
				$modelDocPan->doc_type	 = $doc_type;

				if ($doc_subtype == 'pan')
				{
					$type									 = 'pan';
					$modelDocPan->local_doc_file_front_path	 = $doc_subtype;
					$modelDocPan->add();
					Contact::model()->updateContact($modelDocPan->doc_id, $modelDocPan->doc_type, $modelDocPan->entity_id, '');
					$success								 = Vendors::model()->saveDocument($model->vnd_id, $modelDocPan->doc_file_front_path, $user_info, $type);
					if (!$success)
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors("Pan-Id creation failed.");
					}
					else
					{
						$returnSet->setStatus(true);
					}
				}
				if ($doc_subtype == 'pan_back')
				{
					$type									 = 'panback';
					$modelDocPan->local_doc_file_back_path	 = $doc_subtype;
					$modelDocPan->add();
					Contact::model()->updateContact($modelDocPan->doc_id, $modelDocPan->doc_type, $modelDocPan->entity_id, '');
					$success								 = Vendors::model()->saveDocument($model->vnd_id, $modelDocPan->doc_file_back_path, $user_info, $type);
					if (!$success)
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors("Pan Back creation failed.");
					}
					else
					{
						$returnSet->setStatus(true);
					}
				}
			}
		}
		if ($doc_type == 5)
		{
			if ($model->vndContact->ctt_license_doc_id != "")
			{
				$checkApprove = $this->checkApproveDocById($model->vndContact->ctt_license_doc_id);
			}
			if ($checkApprove == 1)
			{
				$success = false;
				$errors	 = ("License already exists.\n\t\t");
				$returnSet->setStatus(false);
				$returnSet->setErrors($errors);
			}
			else
			{
				if ($model->vndContact->ctt_license_doc_id != "")
				{
					$modelDocLicense = Document::model()->findByPk($model->vndContact->ctt_license_doc_id);
				}
				else
				{
					$modelDocLicense = new Document();
				}
				$modelDocLicense->isDocsApp	 = true;
				$modelDocLicense->entity_id	 = $contactId ? $contactId : $model->vnd_contact_id;
				$modelDocLicense->doc_type	 = $doc_type;

				if ($doc_subtype == 'license')
				{
					$type										 = 'license';
					$modelDocLicense->local_doc_file_front_path	 = $doc_subtype;
					$modelDocLicense->add();
					Contact::model()->updateContact($modelDocLicense->doc_id, $modelDocLicense->doc_type, $modelDocLicense->entity_id, '');
					$success									 = Vendors::model()->saveDocument($model->vnd_id, $modelDocLicense->doc_file_front_path, $user_info, $type);
					if (!$success)
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors("License creation failed.");
					}
					else
					{
						$returnSet->setStatus(true);
					}
				}
				if ($doc_subtype == 'license_back')
				{
					$type										 = 'licenseback';
					$modelDocLicense->local_doc_file_back_path	 = $doc_subtype;
					$modelDocLicense->add();
					Contact::model()->updateContact($modelDocLicense->doc_id, $modelDocLicense->doc_type, $modelDocLicense->entity_id, '');
					$success									 = Vendors::model()->saveDocument($model->vnd_id, $modelDocLicense->doc_file_back_path, $user_info, $type);
					if (!$success)
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors("License Back creation failed.");
					}
					else
					{
						$returnSet->setStatus(true);
					}
				}
			}
		}
		if ($doc_type == 6)
		{
			if ($model->vndContact->ctt_memo_doc_id != "")
			{
				$checkApprove = $this->checkApproveDocById($model->vndContact->ctt_memo_doc_id);
			}
			if ($checkApprove == 1)
			{
				$success = false;
				$errors	 = ("Memo already exists.\n\t\t");
				$returnSet->setStatus(false);
				$returnSet->setErrors($errors);
			}
			else
			{
				if ($model->vndContact->ctt_memo_doc_id != "")
				{
					$modelDocMemoVerify = Document::model()->findByPk($model->vndContact->ctt_memo_doc_id);
				}
				else
				{
					$modelDocMemoVerify = new Document();
				}
				$modelDocMemoVerify->isDocsApp	 = true;
				$modelDocMemoVerify->entity_id	 = $contactId ? $contactId : $model->vnd_contact_id;
				$modelDocMemoVerify->doc_type	 = $doc_type;

				$type											 = 'memorandum';
				$modelDocMemoVerify->local_doc_file_front_path	 = $doc_subtype;
				$modelDocMemoVerify->add();
				Contact::model()->updateContact($modelDocMemoVerify->doc_id, $modelDocMemoVerify->doc_type, $modelDocMemoVerify->entity_id, '');

				$success = Vendors::model()->saveDocument($model->vnd_id, $modelDocMemoVerify->doc_file_front_path, $user_info, $type);
				if (!$success)
				{
					$returnSet->setStatus(false);
					$returnSet->setErrors("Trade License creation failed.");
				}
				else
				{
					$returnSet->setStatus(true);
				}
			}
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param type $img
	 * @param type $cttId
	 * @param type $type
	 * @return ReturnSet
	 */
	public static function saveDcoImage($img, $cttId, $type)
	{
		$returnSet = new ReturnSet();
		if ($type != "")
		{
			$fileType		 = floor($type / 100);
			$filePath		 = ($type % 100 == 2 ? 'doc_file_back_path' : 'doc_file_front_path');
			$doctypeName	 = self::docTypeList($type);
			$totalImagePath	 = self::upload($cttId, $doctypeName, $img);
			if ($totalImagePath)
			{
				$returnSet = self::updateContactDoc($filePath, $fileType, $cttId, $totalImagePath); // new functon need to create
			}
		}
		return $returnSet;
	}

	/**
	 * update contact and add or update document
	 * @param type $filePath
	 * @param type $fileType
	 * @param type $cttId
	 * @param type $totalImagePath
	 * @return ReturnSet
	 * @throws CHttpException
	 */
	public static function updateContactDoc($filePath, $fileType, $cttId, $totalImagePath)
	{
		$transaction = null;
		$returnSet	 = new ReturnSet();
		try
		{

			$docTypeInfo = Contact::getDocFieldInfo($fileType);
			$column		 = $docTypeInfo['docId'];

			if ($column == "")
			{
				throw new CHttpException("Invalid document type", 1);
			}
			$transaction = DBUtil::beginTransaction();
			$sql		 = "SELECT $column FROM contact WHERE ctt_id = $cttId";
			$docId		 = DBUtil::queryScalar($sql, DBUtil::SDB());

			$path = str_replace("\\", "\\\\", $totalImagePath);

			if ($docId != "")
			{
				//check front and back image both are present then new columm if not then edit
				$modelDoc = Document::model()->findByPk($docId);
				if ($modelDoc->doc_file_front_path != "" && $modelDoc->doc_file_back_path != "")
				{
					// make doc inactive
					$modelDoc->doc_active = 0;
					if (!$modelDoc->save())
					{
						throw new CHttpException("Error in document update", ReturnSet::ERROR_FAILED);
					}

					goto newmodel;
				}
				if ($filePath == 'doc_file_front_path')
				{
					$modelDoc->doc_front_s3_data = null;
				}
				if ($filePath == 'doc_file_back_path')
				{
					$modelDoc->doc_back_s3_data = null;
				}
				goto skip;
			}
			newmodel:
			$modelDoc = new Document();

			skip:

			$modelDoc->isDocsApp	 = true;
			$modelDoc->entity_id	 = $cttId;
			$modelDoc->doc_type		 = $fileType;
			$modelDoc->$filePath	 = $path;
			$modelDoc->doc_status	 = 0;
			if (!$modelDoc->save())
			{
				throw new CHttpException("Error in document update", ReturnSet::ERROR_FAILED);
			}
			$contactDoc			 = Contact::model()->findByPk($cttId);
			#$contactDoc->$column = ($docId == "" ? $modelDoc->doc_id : $docId);
			$contactDoc->$column = $modelDoc->doc_id;
			if (!$contactDoc->save())
			{
				throw new CHttpException("Error in contact update with docid", ReturnSet::ERROR_FAILED);
			}
			DBUtil::commitTransaction($transaction);
			$returnSet->setStatus(true);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public function saveVendorImage($image, $imagetmp, $vendorId, $cttid, $type)
	{
		try
		{
			$path = "";
			if ($image != '')
			{
				$path = Yii::app()->basePath;
				if ($type == 'agreement' || $type == 'digital_sign')
				{
					$fileName = $vendorId . "-" . $type . "-" . date('YmdHis') . "." . $image;

					// Attachments
					$dir = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
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

					// Vendor Id
					$dirByVendorId = $serverFolderName . DIRECTORY_SEPARATOR . $vendorId;
					if (!is_dir($dirByVendorId))
					{
						mkdir($dirByVendorId);
					}
					$file_path	 = $dirByVendorId . DIRECTORY_SEPARATOR . $image;
					$folder_path = $dirByVendorId . DIRECTORY_SEPARATOR;
				}
				else
				{
					$image = $cttid . "-" . $type . "-" . date('YmdHis') . "-" . $image;

					// Contact
					$dir = $path . DIRECTORY_SEPARATOR . 'contact';
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

					// Document
					$dirFolderName = $serverFolderName . DIRECTORY_SEPARATOR . 'document';
					if (!is_dir($dirFolderName))
					{
						mkdir($dirFolderName);
					}

					// Contact Id
					$dirByContactId = $dirFolderName . DIRECTORY_SEPARATOR . $cttid;
					if (!is_dir($dirByContactId))
					{
						mkdir($dirByContactId);
					}

					// Type
					$dirByType = $dirByContactId . DIRECTORY_SEPARATOR . $type;
					if (!is_dir($dirByType))
					{
						mkdir($dirByType);
					}

					$file_path	 = $dirByType . DIRECTORY_SEPARATOR . $image;
					$folder_path = $dirByType . DIRECTORY_SEPARATOR;
				}
				$file_name	 = basename($image);
				$f			 = $file_path;
				#$file_path1	 = $file_path . DIRECTORY_SEPARATOR;
				file_put_contents($f, file_get_contents($imagetmp));  // parameter1=> target, parameter2 => source
				#Yii::log("Image Path: \n\t Temp: " . $imagetmp . "\n\t Path: " . $f, CLogger::LEVEL_INFO, 'system.api.images');
				if (Vehicles::model()->img_resize($imagetmp, 1200, $folder_path, $file_name))
				{
					if ($type == 'agreement' || $type == 'digital_sign')
					{
						$path = substr($file_path, strlen(PUBLIC_PATH));
					}
					else
					{
						$path = substr($file_path, strlen($path));
					}
					$result = ['path' => $path];
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

	public function getModificationMSG($diff, $user = false)
	{
		$msg = '';
		if (count($diff) > 0)
		{
			if ($diff ['drv_name'])
			{
				$msg .= ' Driver name: ' . $diff['drv_name'] . ',';
			}
			if ($diff ['drv_phone'])
			{
				$msg .= ' Driver Phone: ' . $diff['drv_phone'] . ',';
			}
			if ($diff ['drv_lic_number'])
			{
				$msg .= ' Licence Number: ' . $diff['drv_lic_number'] . ',';
			}
			if ($diff['drv_issue_auth'])
			{
				$msg .= ' Issue Authorized by: ' . $diff['drv_issue_auth'] . ',';
			}
			if ($diff['drv_lic_exp_date'])
			{
				$msg .= ' Licence Exp Date: ' . $diff['drv_lic_exp_date'] . ',';
			}
			if ($diff['drv_address'])
			{
				$msg .= ' Address: ' . $diff['drv_address'] . ',';
			}
			if ($diff['drv_email'])
			{
				$msg .= ' Email: ' . $diff['drv_email'] . ',';
			}
			if ($diff['drv_dob_date'])
			{
				$msg .= ' Date of Birth: ' . $diff['drv_dob_date'] . ',';
			}
			if ($diff['drv_state'])
			{
				$smodel	 = States::model()->findByPk($diff['drv_state']);
				$msg	 .= ' State: ' . $smodel->stt_name . ',';
			}
			if ($diff['drv_city'])
			{
				$cmodel	 = Cities::model()->findByPk($diff['drv_city']);
				$msg	 .= ' City: ' . $cmodel->cty_name . ',';
			}
			if ($diff['drv_zip'])
			{
				$msg .= ' Zip: ' . $diff['drv_zip'] . ',';
			}
			if ($diff['photoFile'] != '')
			{
				$msg .= ' : ' . $diff['photoFile'] . ',';
			}
			if ($diff['voterCardFile'] != '')
			{
				$msg .= ' : ' . $diff['voterCardFile'] . ',';
			}
			if ($diff['panCardFile'] != '')
			{
				$msg .= ' : ' . $diff['panCardFile'] . ',';
			}
			if ($diff['aadhaarCardFile'] != '')
			{
				$msg .= ' : ' . $diff['aadhaarCardFile'] . ',';
			}
			if ($diff['licenseFile'] != '')
			{
				$msg .= ' : ' . $diff['licenseFile'] . ',';
			}
			if ($diff['policeFile'] != '')
			{
				$msg .= '  : ' . $diff['policeFile'] . ',';
			}
			$msg = rtrim($msg, ',');
		}
		return $msg;
	}

	/**
	 * 
	 * @param integer $cttId
	 * @param string $viewType
	 * @return type
	 */
	public static function getDocModels($cttId, $viewType = "")
	{
		$contactAll		 = self::getAllDocsbyContact($cttId, $viewType);
		$voterId		 = $contactAll[0]['doc_id2'] > 0 ? $contactAll[0]['doc_id2'] : 0;
		$aadharId		 = $contactAll[0]['doc_id3'] > 0 ? $contactAll[0]['doc_id3'] : 0;
		$panId			 = $contactAll[0]['doc_id4'] > 0 ? $contactAll[0]['doc_id4'] : 0;
		$licenceId		 = $contactAll[0]['doc_id5'] > 0 ? $contactAll[0]['doc_id5'] : 0;
		$memoId			 = $contactAll[0]['doc_id6'] > 0 ? $contactAll[0]['doc_id6'] : 0;
		$docpoliceverId	 = $contactAll[0]['doc_id7'] > 0 ? $contactAll[0]['doc_id7'] : 0;
		$docIds			 = [$voterId, $aadharId, $panId, $licenceId, $memoId, $docpoliceverId];
		if ($voterId == 0 && $aadharId == 0 && $panId == 0 && $licenceId == 0 && $memoId == 0 && $docpoliceverId == 0)
		{
			return null;
		}
		$docIds		 = array_filter($docIds);
		$docIdstr	 = implode(',', $docIds);

		$criteria	 = new CDbCriteria();
		$criteria->addCondition("doc_id IN ($docIdstr) AND doc_active=1");
		$models		 = Document::model()->findAll($criteria);
		return $models;
	}

	public static function getReviewDocs($contactId)
	{
		$sql = "SELECT cmg_ctt_id, ctt.*, GROUP_CONCAT(ctt_id) as cttIds, 
				IF(FIND_IN_SET(cmg_ctt_id,GROUP_CONCAT(ctt_id)),1,0) as primaryDoc, document.*
				FROM contact ctt
				INNER JOIN contact_merged cm ON ((ctt.ctt_id=cm.cmg_mrg_ctt_id AND cm.cmg_ctt_id=ctt.ctt_ref_code) OR (ctt.ctt_id=cmg_ctt_id)) AND cm.cmg_ctt_id=$contactId AND ctt.ctt_active = 1
				LEFT JOIN document ON ((cm.cmg_pan_flag=1 AND ctt.ctt_pan_doc_id=doc_id) OR (cm.cmg_voter_flag=1 AND ctt.ctt_voter_doc_id=doc_id) 
									OR (cm.cmg_adhaar_flag=1 AND ctt.ctt_aadhar_doc_id=doc_id) OR (cm.cmg_licence_flag=1 AND ctt.ctt_license_doc_id=doc_id)) AND doc_status IN (0,1)
				GROUP BY ctt_id, doc_id
				ORDER BY ctt.ctt_id, document.doc_type, primaryDoc DESC";

		$defaultOrder = 'ctt.ctt_id DESC';
		if ($command == false)
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['drv_name'],
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

	public static function isLouApproved($documentModel, $docStatus)
	{
		$success	 = false;
		$transaction = DBUtil::beginTransaction();
		$message	 = "";
		try
		{
			$docType						 = $documentModel->doc_type;
			$userInfo						 = UserInfo::getInstance();
			$userInfo->userId				 = UserInfo::getUserId();
			$documentModel->doc_status		 = $docStatus;
			$documentModel->doc_approved_by	 = $userInfo->userId;
			$documentModel->doc_approved_at	 = date("Y-m-d H:i:s");
			$documentModel->doc_active		 = 1;
			if ($documentModel->doc_temp_approved == 1)
			{
				$documentModel->doc_temp_approved	 = 0;
				$documentModel->doc_temp_approved_at = NULL;
			}
			if ($docType == 4)
			{
				$message .= $docStatus == '1' ? "Pan Approved" : "Pan Rejected";
			}
			else if ($docType == 5)
			{
				$message .= $docStatus == '1' ? "License Approved" : "License Rejected";
			}

			$documentModel->doc_remarks = $message;
			if (!$documentModel->save())
			{
				throw new CHttpException("Failed to approved document", 1);
			}
			$success = true;
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
		}
		return $success;
	}

	public static function isApproved($objModeldocument, $docType, $status)
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		$message	 = "";
		try
		{
			$objModeldocument->doc_status		 = $status == '1' ? 1 : 2;
			$objModeldocument->doc_approved_by	 = $userInfo->userId;
			$objModeldocument->doc_approved_at	 = date("Y-m-d H:i:s");
			if ($objModeldocument->doc_temp_approved == 1)
			{
				$objModeldocument->doc_temp_approved	 = 0;
				$objModeldocument->doc_temp_approved_at	 = NULL;
			}
			if ($docType == 2)
			{
				$message .= $status == '1' ? "Voter Id Approved" : "Voter id Rejected";
			}
			else if ($docType == 3)
			{
				$message .= $status == '1' ? "Aadhaar Approved" : "Aadhaar Rejected";
			}
			else if ($docType == 4)
			{
				$message .= $status == '1' ? "Pan Approved" : "Pan Rejected";
			}
			else if ($docType == 5)
			{
				$message .= $status == '1' ? "License Approved" : "License Rejected";
			}
			else if ($docType == 6)
			{
				$message .= $status == '1' ? "Memorandum Approved" : "Memorandum Rejected";
			}
			else if ($docType == 7)
			{
				$message .= $status == '1' ? "Police Verification Approved" : "Police Verification Rejected";
			}

			if (!$objModeldocument->save())
			{
				$returnSet->setErrors($this->getErrors(), 1);
				throw new CHttpException("Failed to approved document", 1);
			}
			DBUtil::commitTransaction($transaction);
			$returnSet->setStatus(true);
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

	public function uploadByChecksum($image, $imagetmp, $fileChecksum, $docId, $conctID, $docType)
	{


		$result		 = [];
		$success	 = false;
		$docModel	 = $this->getRow($fileChecksum, $docId, $docType);

		$path		 = $this->saveImage($image, $imagetmp, $docModel['vhd_vhc_id'], $docModel['vhd_type'], $conctID, $docType);
		$uploadModel = $this->updatePath($docId, $path['path']);
		if ($uploadModel)
		{
			$success = true;
			$docs	 = Document::documentType();
			$message = "$docs[$docType] Saved Successfully.";
		}
		else
		{
			$message = "Not Saved.";
		}

		//$message = "Invalid Checksum.";
		return ['success' => $success, 'message' => $message, 'model' => $uploadModel];
	}

	public function getRow($fileChecksum, $docId, $docType)
	{
		$params	 = ['doc_checksum' => $fileChecksum, 'doc_id' => $docId, 'doc_type' => $docType];
		$sql	 = "SELECT * FROM document WHERE doc_checksum =:doc_checksum AND doc_id =:doc_id AND doc_type = :doc_type";
		return DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		;
	}

	public function saveImage($image, $imagetmp, $vehicleId, $type, $conctID, $docType)
	{
		try
		{
			$path = "";

			if ($image != '')
			{
				$cttId	 = $conctID;
//$DS = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
				$DS		 = DIRECTORY_SEPARATOR;
				if ($docType == 4)
				{
					$fileName = $cttId . "-" . 'Pan' . "-" . date('YmdHis') . "." . $image;
				}
				else
				{
					$fileName = $cttId . "-" . 'Licence' . "-" . date('YmdHis') . "." . $image;
				}
				$folderPrefix	 = floor($cttId / 1000);
				$basePath		 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments'; //Yii::app()->basePath;
				$docPath		 = $DS . 'contact' . $DS . Config::getServerID() . $DS . 'document' . $DS . $folderPrefix . $DS . $cttId . $DS;

				if (!is_dir($docPath))
				{
					mkdir($basePath . $docPath, 0755, true);
//mkdir($docPath, 0755, true);
				}
				$file_path	 = $basePath . $docPath . $fileName;
				$f			 = $basePath . $docPath;
				if (Vehicles::model()->img_resize($imagetmp, 1200, $f, $fileName))
				{
					$path	 = 'attachments' . $DS . $docPath . $fileName; //substr($file_path, strlen($basePath));
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

	public function createFolderPath($cttId, $image)
	{
		try
		{
			$DS				 = DIRECTORY_SEPARATOR;
			$folderPrefix	 = floor($cttId / 1000);
			$basePath		 = Yii::app()->basePath;
			$docPath		 = $DS . 'contact' . $DS . Config::getServerID() . $DS . 'document' . $DS . $folderPrefix . $DS . $cttId . $DS;

			if (!is_dir($docPath))
			{
				mkdir($basePath . $docPath, 0755, true);
			}
			$file_path = $docPath . $image;
		}
		catch (Exception $e)
		{
			Yii::log("Exception thrown: \n\t" . $e->getTraceAsString(), CLogger::LEVEL_ERROR, "system.api.images");
			Yii::log("Data Received: \n\t" . serialize(filter_input_array(INPUT_POST)), CLogger::LEVEL_ERROR, "system.api.images");
			throw $e;
		}
		return $file_path;
	}

	public function updatePath($docID, $path)
	{
		$model						 = Document::model()->findByPk($docID);
		$model->doc_file_front_path	 = $path;
		$model->update();
		return $model;
	}

	public function saveDoc($doc, $type)
	{


		if (!$doc->id > 0)
		{
			$refvalue	 = $doc->refValue;
			$docId		 = Contact::model()->getDocId($refvalue, $type);
		}
		else
		{
			$docId = $doc->id;
		}
		if ($docId > 0)
		{
			$contactLicense = Document::model()->findByPk($docId);
		}
		else
		{
			$contactLicense = new Document();
		}
		if ($contactLicense->doc_file_front_path == NULL)
		{
			$contactLicense->doc_checksum		 = $doc->checksum;
			$contactLicense->doc_file_front_path = $doc->frontPath;
		}
		$contactLicense->doc_type = $type;
		$contactLicense->save();
		return $contactLicense;
	}

	public function getBaseDocPath()
	{
		return PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR;
	}

	public function getFrontPath()
	{
		$filePath = $this->doc_file_front_path;

		if (substr($filePath, 0, strlen('attachments')) == 'attachments')
		{
			$filePath = substr($filePath, strlen('attachments'));
		}
		$filePath = $this->getBaseDocPath() . $filePath;

		//	Logger::writeToConsole($filePath);
		if (!file_exists($filePath))
		{
			$filePath = APPLICATION_PATH . $this->doc_file_front_path;
		}

		return $filePath;
	}

	public function getBackPath()
	{
		$filePath = $this->doc_file_back_path;

		if (substr($filePath, 0, strlen('attachments')) == 'attachments')
		{
			$filePath = substr($filePath, strlen('attachments') - 1);
		}
		$filePath = $this->getBaseDocPath() . $filePath;
		//	Logger::writeToConsole($filePath);

		if (!file_exists($filePath))
		{
			$filePath = APPLICATION_PATH . $this->doc_file_back_path;
		}
		return $filePath;
	}

	public static function getImagePath($filePath)
	{
		$file = $filePath;

		if (substr($filePath, 0, strlen('attachments')) == 'attachments')
		{
			$filePath = substr($filePath, strlen('attachments'));
		}
		$filePath = Document::model()->getBaseDocPath() . $filePath;

		if (!file_exists($filePath))
		{
			$filePath = APPLICATION_PATH . $file;
		}
		$path = AttachmentProcessing::publish($filePath);
		return $path;
	}

	public function getSpacePath($localPath)
	{
		$fileName	 = basename($localPath);
		$id			 = $this->doc_id;
		$docType	 = $this->doc_type;
		if ($docType == '')
		{
			$docType = 0;
		}
		$date		 = $this->doc_created_at;
		$dateString	 = DateTimeFormat::SQLDateTimeToDateTime($date)->format("Y/m/d");
		$path		 = "/{$docType}/{$dateString}/{$id}_{$fileName}";
		return $path;
	}

	public function getFrontSpacePath()
	{
		return $this->getSpacePath($this->doc_file_front_path);
	}

	public function getBackSpacePath()
	{
		return $this->getSpacePath($this->doc_file_back_path);
	}

	/**
	 * @return Stub\common\SpaceFile
	 */
	public function uploadToSpace($localFile, $spaceFile, $removeLocal = true)
	{
		$objSpaceFile = Storage::uploadFile(Storage::getDocumentSpace(), $spaceFile, $localFile, $removeLocal);
		return $objSpaceFile;
	}

	/** @return Stub\common\SpaceFile */
	public function uploadFrontFileToSpace($removeLocal = true)
	{
		$spaceFile = null;
		try
		{
			$docModel = $this;

			if (!file_exists($docModel->getFrontPath()) || $docModel->doc_file_front_path == '')
			{
				if ($docModel->doc_front_s3_data == '')
				{
					$docModel->doc_front_s3_data = "{}";
					$docModel->save();
				}
				return null;
			}

			$spaceFile = $docModel->uploadToSpace($docModel->getFrontPath(), $docModel->getFrontSpacePath(), $removeLocal);

			$docModel->doc_front_s3_data = $spaceFile->toJSON();
			$docModel->save();
		}
		catch (Exception $exc)
		{
			ReturnSet::setException($exc);
		}
		return $spaceFile;
	}

	/** @return Stub\common\SpaceFile */
	public function uploadBackFileToSpace()
	{
		$spaceFile = null;
		try
		{
			$docModel = $this;
			if (!file_exists($docModel->getBackPath()) || $docModel->doc_file_back_path == '')
			{
				if ($docModel->doc_back_s3_data == '')
				{

					$docModel->doc_back_s3_data = "{}";
					$docModel->save();
				}
				return null;
			}

			$spaceFile					 = $docModel->uploadToSpace($docModel->getBackPath(), $docModel->getBackSpacePath(), $removeLocal);
			$docModel->doc_back_s3_data	 = $spaceFile->toJSON();
			$docModel->save();
		}
		catch (Exception $exc)
		{
			ReturnSet::setException($exc);
		}

		return $spaceFile;
	}

	/** @return SpacesAPI\File */
	public function getSpaceFile($spaceJSON)
	{
		if ($spaceJSON == '' || $spaceJSON == '{}')
		{
			return null;
		}
		return Stub\common\SpaceFile::populate($spaceJSON)->getFile();
	}

	/** @return SpacesAPI\File */
	public function getFrontSpaceFile()
	{
		return $this->getSpaceFile($this->doc_front_s3_data);
	}

	/** @return SpacesAPI\File */
	public function getBackSpaceFile()
	{
		return $this->getSpaceFile($this->doc_back_s3_data);
	}

	public function removeSpaceFile($spaceJSON)
	{
		if ($spaceJSON == '' || $spaceJSON == '{}')
		{
			return true;
		}
		$spaceFile = Stub\common\SpaceFile::populate($spaceJSON);

		return Storage::removeFile($spaceFile->bucket, $spaceFile->key);
	}

	public function removeFrontSpaceFile()
	{
		return $this->removeSpaceFile($this->doc_front_s3_data);
	}

	public function removeBackSpaceFile()
	{
		return $this->removeSpaceFile($this->doc_back_s3_data);
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
			$condFrontPath	 = " (doc_front_s3_data IS NULL AND doc_file_front_path LIKE '%/contact/{$serverId}/document/%') ";
			$condBackPath	 = " (doc_back_s3_data IS NULL AND doc_file_back_path LIKE '%/contact/{$serverId}/document/%') ";

			$sql = "SELECT doc_id FROM document
					WHERE doc_active=1 AND ({$condFrontPath} OR {$condBackPath})
					ORDER BY doc_id DESC LIMIT 0,$limit1";

			$res = DBUtil::query($sql);
			if ($res->getRowCount() == 0)
			{
				break;
			}
			foreach ($res as $row)
			{
				/** @var Document $docModel */
				$docModel = Document::model()->findByPk($row["doc_id"]);

				$docModel->uploadFrontFileToSpace();
				Logger::writeToConsole($docModel->doc_front_s3_data);

				$docModel->uploadBackFileToSpace();
				Logger::writeToConsole($docModel->doc_back_s3_data);
			}
			$limit -= $limit1;
			Logger::flush();
		}
	}

	/**
	 * 
	 * @param type $docId, $pathType
	 * @return Doc path link
	 */
	public static function getDocPathById($docId, $pathType = '')
	{
		$path = '/images/no-image.png';

		$docModel = Document::model()->findByPk($docId);
		if (!$docModel)
		{
			goto end;
		}
		$fieldName = ($pathType == 1) ? "doc_front_s3_data" : "doc_back_s3_data";
		if ($pathType != '')
		{
			$s3Data = $docModel->$fieldName;
			if ($pathType == 1)
			{
				$imgPath = $docModel->getFrontPath();
			}

			if ($pathType == 2)
			{
				$imgPath = $docModel->getBackPath();
			}

			if (file_exists($imgPath) && $imgPath != $docModel->getBaseDocPath())
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
					$docModel->$fieldName = $spaceFile->toJSON();
					$docModel->save();
				}
			}
		}
		end:
		return $path;
	}

	public static function getS3DocPathById($docId, $pathType = '')
	{
		$path = '/images/no-image.png';

		$docModel = Document::model()->findByPk($docId);
		if (!$docModel)
		{
			goto end;
		}
		$fieldName = ($pathType == 2) ? "doc_back_s3_data" : "doc_front_s3_data";
		if ($pathType != '')
		{
			$s3Data	 = $docModel->$fieldName;
			$imgPath = $s3Data;
			if ($pathType == 1 && !$s3Data)
			{
				$imgPath = $docModel->getFrontPath();
			}

			if ($pathType == 2 && !$s3Data)
			{
				$imgPath = $docModel->getBackPath();
			}


			if (file_exists($imgPath) && $imgPath != $docModel->getBaseDocPath())
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
					$docModel->$fieldName = $spaceFile->toJSON();
					$docModel->save();
				}
			}
		}
		end:
		return $path;
	}

	public static function uploadDocumentByContact($documents, $contact, $prefixDocType = '')
	{
		$cttId					 = $contact['ctt_id'];
		$docModel				 = new Document();
		$docModel->attributes	 = $documents;
		$docType				 = $docModel->doc_type;

		$frontFile	 = CUploadedFile::getInstance($docModel, "doc_file_front_path");
		$backFile	 = CUploadedFile::getInstance($docModel, "doc_file_back_path");
		if ($prefixDocType != '')
		{
			$docModel->prefixDocType = $prefixDocType;
			$frontFile				 = CUploadedFile::getInstance($docModel, "[$prefixDocType]doc_file_front_path");
			$backFile				 = CUploadedFile::getInstance($docModel, "[$prefixDocType]doc_file_back_path");
		}


		if (!$frontFile)
		{
			unset($documents['doc_file_front_path']);
		}
		if (!$backFile)
		{
			unset($documents['doc_file_back_path']);
		}

		$fieldName		 = Document::getFieldByType($docType);
		$docTypeInfArr	 = \Contact::getDocFieldInfo($docType);
		if ($fieldName != '')
		{

			$identityNo	 = $docTypeInfArr['identityNo'];
			$identity	 = $contact[$identityNo];
		}
		$contactModel	 = Contact::model()->findByPk($cttId);
		$docIdField		 = $docTypeInfArr['docId'];
		$transaction	 = DBUtil::beginTransaction();
		try
		{
			$docId = $contactModel->$docIdField;

			if ($docId > 0)
			{
				$docModel1 = Document::model()->findByPk($docId);
				if ($docModel1)
				{
					$docModel				 = $docModel1;
					$docModel->attributes	 = $documents;
					$docModel->prefixDocType = $prefixDocType;
				}
			}

			if (!$frontFile && !$backFile)
			{
				goto skipFileUpload;
			}

			$docModel->doc_temp_approved = $documents['doc_temp_approved'] == 1 ? 1 : 0;
			$docModel->entity_id		 = $cttId;

			$success = $docModel->add();
			if (!$success)
			{
				throw new CHttpException(1, json_encode($docModel->getErrors()));
			}
			skipFileUpload:
			$docId			 = $docModel->doc_id;
			$licenseExpDate	 = null;
			$flag			 = 0;
			if ($docType == Document::Document_Licence && $contact['ctt_license_exp_date'] != '')
			{
				$licenseExpDate	 = $contact['ctt_license_exp_date'];
				$flag			 = 1;
			}
			\Contact::model()->updateContact($docId, $docModel->doc_type, $cttId, $identity, $licenseExpDate, null, null, $flag);
			skipFiles:
			DBUtil::commitTransaction($transaction);
			$success = true;
		}
		catch (Exception $ex)
		{
			$success = false;
			DBUtil::rollbackTransaction($transaction);
		}
		return $success;
	}

	/**
	 * 
	 * @param int $docType
	 * @param int $faceType
	 * @return array()
	 */
	public static function docTypeToFaceType($docType, $faceType = 0)
	{
		$docTypeMasterList	 = \Document::model()->docType();
		$docTypeWithFaceType = null;
		$docTypeLabel		 = null;
		if (isset($docTypeMasterList[$docType]))
		{
			if ($faceType == 1)
			{
				$docTypeWithFaceType = ($docTypeMasterList[$docType][2] == '') ? (int) $docType . '00' : (int) $docType . '01';
			}
			if ($faceType == 2)
			{
				$docTypeWithFaceType = (int) $docType . '02';
			}
			$docTypeLabel = \Document::docTypeList($docTypeWithFaceType);
		}
		return ['docType' => $docTypeWithFaceType, 'docTypeLabel' => $docTypeLabel];
	}

	public function getData($type = '')
	{
		$params = [];

		$dateRangeDoc	 = " AND doc.doc_approved_at > DATE_SUB(CURRENT_DATE, INTERVAL 1 WEEK) ";
		$dateRangeVhd	 = " AND vhd.vhd_appoved_at > DATE_SUB(CURRENT_DATE, INTERVAL 1 WEEK) "; 
		if ($this->appDate1 && $this->appDate2)
		{
			$fromDate		 = $this->appDate1;
			$toDate			 = $this->appDate2;
			$dateRangeDoc	 = " AND doc.doc_approved_at <= '$toDate 23:59:59' AND doc.doc_approved_at>='$fromDate 00:00:00' ";
			$dateRangeVhd	 = " AND vhd.vhd_appoved_at <= '$toDate 23:59:59' AND vhd.vhd_appoved_at>='$fromDate 00:00:00' ";
		}
		$groupBy = 'approvedBy, appdate';
		$orderBy = 'appdate DESC,approvedBy';
		if ($this->groupType != '')
		{
			switch ($this->groupType)
			{
				case 1:
					$groupBy = 'adm_id';
					$orderBy = 'approvedBy';
					break;
				case 2:
					$groupBy = 'appdate';
					break;
				case 3:
					$groupBy = 'adm_id,appdate';
					break;
			}
		}

		$sql = "SELECT  GROUP_CONCAT(distinct catType) catType1,
						GROUP_CONCAT(distinct docType) docType,
					SUM(if(catType=1 AND docType = 2,totApproved,0)) approveVoter,
					SUM(if(catType=1 AND docType = 2,totRejected,0)) rejectVoter,
					SUM(if(catType=1 AND docType = 2,totCount,0)) totVoter,

					SUM(if(catType=1 AND docType = 3,totApproved,0)) approveAadhar,
					SUM(if(catType=1 AND docType = 3,totRejected,0)) rejectAadhar,
					SUM(if(catType=1 AND docType = 3,totCount,0)) totAadhar,

					SUM(if(catType=1 AND docType = 4,totApproved,0)) approvePAN,
					SUM(if(catType=1 AND docType = 4,totRejected,0)) rejectPAN,
					SUM(if(catType=1 AND docType = 4,totCount,0)) totPAN,

					SUM(if(catType=1 AND docType = 5,totApproved,0)) approveLicense,
					SUM(if(catType=1 AND docType = 5,totRejected,0)) rejectLicense,
					SUM(if(catType=1 AND docType = 5,totCount,0)) totLicense,	
					
					SUM(if(catType=2 AND docType = 1,totApproved,0)) approveInsurance,
					SUM(if(catType=2 AND docType = 1,totRejected,0)) rejectInsurance,
					SUM(if(catType=2 AND docType = 1,totCount,0)) totInsurance,
					
					SUM(if(catType=2 AND docType = 5,totApproved,0)) approveRC,
					SUM(if(catType=2 AND docType = 5,totRejected,0)) rejectRC,
					SUM(if(catType=2 AND docType = 5,totCount,0)) totRC,

					SUM(if(catType=2 AND docType = 4,totApproved,0)) approvePUC,
					SUM(if(catType=2 AND docType = 4,totRejected,0)) rejectPUC,
					SUM(if(catType=2 AND docType = 4,totCount,0)) totPUC,				
				
					SUM(if(catType=2 AND docType = 6,totApproved,0)) approvePermit,
					SUM(if(catType=2 AND docType = 6,totRejected,0)) rejectPermit,
					SUM(if(catType=2 AND docType = 6,totCount,0)) totPermit,

					SUM(if(catType=2 AND docType = 7,totApproved,0)) approveFitness,
					SUM(if(catType=2 AND docType = 7,totRejected,0)) rejectFitness,
					SUM(if(catType=2 AND docType = 7,totCount,0)) totFitness,
					
					SUM(totApproved) totApproved,
					SUM(totRejected) totRejected,
					SUM(totCount) totCount,
					appdate, adm_id,approvedBy 
					FROM 
					(SELECT 
						doc.doc_type AS docType,
						1 AS catType, 
						COUNT(distinct doc_id) totCount,
						SUM(IF(doc.doc_status = 1, 1, 0)) totApproved,
						SUM(IF(doc.doc_status = 2, 1, 0)) totRejected,
						DATE(doc.doc_approved_at) appdate,adm.adm_id,
						concat(adm.adm_fname,' ',adm.adm_lname) approvedBy
					FROM `document` doc
					JOIN admins adm ON adm.adm_id = doc.doc_approved_by
					WHERE doc.doc_status > 0 AND doc.doc_active = 1 
						AND doc.doc_type IN (2,3,4,5) $dateRangeDoc
					GROUP BY docType,$groupBy  
					UNION
					SELECT
						vhd.vhd_type AS docType,
						2 AS catType,
						COUNT(distinct vhd_id) totCount,
						SUM(IF(vhd.vhd_status = 1, 1, 0)) totApproved,
						SUM(IF(vhd.vhd_status = 2, 1, 0)) totRejected,
						DATE(vhd.vhd_appoved_at) appdate, adm.adm_id,
						concat(adm.adm_fname,' ',adm.adm_lname) approvedBy    
					FROM `vehicle_docs` vhd
					JOIN admins adm ON adm.adm_id = vhd.vhd_approve_by
					WHERE vhd.vhd_status > 0 AND vhd.vhd_active = 1 
						AND vhd.vhd_type IN (1,4,5,6,7) $dateRangeVhd   
					GROUP BY docType,$groupBy ) aa 
					GROUP BY $groupBy"; 
		if ($type == 'export')
		{
			return DBUtil::query($sql . " ORDER BY $orderBy", DBUtil::SDB3());
		}
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 =>
				['appdate', 'approvedBy', 'totAadhar', 'totRC', 'totPAN',
					'totLicense', 'totCount', 'totPUC', 'totInsurance',
					'totFitness', 'totVoter', 'totPermit'],
				'defaultOrder'	 => $orderBy],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

}

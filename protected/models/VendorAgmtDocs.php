<?php

/**
 * This is the model class for table "vendor_agmt_docs".
 *
 * The followings are the available columns in table 'vendor_agmt_docs':
 * @property integer $vd_agmt_id
 * @property integer $vd_vnd_id
 * @property integer $vd_agmt_img_no
 * @property string $vd_agmt_req_id
 * @property integer $vd_agmt_device_id
 * @property string $vd_agmt
 * @property integer $vd_agmt_status
 * @property string $vd_agmt_date

 *
 * The followings are the available model relations:
 * @property Vendors $vdVnd
 */
class VendorAgmtDocs extends CActiveRecord
{

	const AGREEMENT_FILE_TYPE = 5; //1,2,3,4 Used in VendorAgreement model

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vendor_agmt_docs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vd_agmt_req_id', 'length', 'max' => 50),
			array('vd_agmt', 'length', 'max' => 255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vd_agmt_id, vd_vnd_id, vd_agmt_req_id, vd_agmt_img_no, vd_agmt, vd_agmt_status, vd_agmt_device_id, vd_agmt_date', 'safe', 'on' => 'search'),
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
			'vd_agmt_id'		 => 'Vd Agmt',
			'vd_vnd_id'			 => 'Vd Vnd',
			'vd_agmt_img_no'	 => 'Vd Agmt Img No',
			'vd_agmt_req_id'	 => 'Vd Agmt Req',
			'vd_agmt_device_id'	 => 'Vd Agmt Device',
			'vd_agmt'			 => 'Vd Agmt',
			'vd_agmt_status'	 => 'Vd Agmt Status',
			'vd_agmt_date'		 => 'Vd Agmt Date',
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

		$criteria->compare('vd_agmt_id', $this->vd_agmt_id);
		$criteria->compare('vd_vnd_id', $this->vd_vnd_id);
		$criteria->compare('vd_agmt_img_no', $this->vd_agmt_img_no);
		$criteria->compare('vd_agmt_req_id', $this->vd_agmt_req_id, true);
		$criteria->compare('vd_agmt_device_id', $this->vd_agmt_device_id);
		$criteria->compare('vd_agmt', $this->vd_agmt, true);
		$criteria->compare('vd_agmt_status', $this->vd_agmt_status);
		$criteria->compare('vd_agmt_date', $this->vd_agmt_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VendorAgmtDocs the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function findByVndReqId($vendorId, $reqId)
	{
		$sql = "SELECT * FROM `vendor_agmt_docs` WHERE vendor_agmt_docs.vd_vnd_id  ='$vendorId'  AND vendor_agmt_docs.vd_agmt_req_id='$reqId' AND vendor_agmt_docs.vd_agmt_status=1
                    ORDER BY vendor_agmt_docs.vd_agmt_img_no";

		return DBUtil::queryAll($sql);
	}

	public function findAllByDate()
	{
		$sql = "SELECT  v1.vnd_name, vd_vnd_id, vd_agmt_req_id, vd_agmt_device_id
				FROM   `vendor_agmt_docs`
				INNER JOIN `vendors` v1 ON v1.vnd_id = vendor_agmt_docs.vd_vnd_id AND v1.vnd_id = v1.vnd_ref_code
				INNER JOIN `vendor_agreement` ON vendor_agreement.vag_vnd_id = v1.vnd_id
				WHERE    vendor_agmt_docs.vd_agmt_status = 1 AND vendor_agreement.vag_digital_is_email = 1
				GROUP BY vendor_agmt_docs.vd_vnd_id, vendor_agmt_docs.vd_agmt_req_id
				ORDER BY vendor_agmt_docs.vd_agmt_date DESC";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function deleteByVndReqDeviceId($vendorId, $reqId, $deviceId)
	{
		$sql = "DELETE FROM `vendor_agmt_docs` WHERE vendor_agmt_docs.vd_vnd_id  ='$vendorId'
                    AND vendor_agmt_docs.vd_agmt_req_id='$reqId'
                    AND vendor_agmt_docs.vd_agmt_device_id='$deviceId'";

		$sql		 .= " AND vendor_agmt_docs.vd_agmt_req_id NOT IN (
                        SELECT vd_agmt_req_id FROM (
                            SELECT DISTINCT vendor_agmt_docs.vd_agmt_req_id
                            FROM `vendor_agmt_docs`
                            WHERE vendor_agmt_docs.vd_agmt_date IN
                            (
                                SELECT MAX(vendor_agmt_docs.vd_agmt_date)
                                FROM `vendor_agmt_docs` WHERE vendor_agmt_docs.vd_vnd_id=$vendorId
                            )
                         )a
                    )";
		$cdb		 = DBUtil::command($sql);
		$rowsUpdated = $cdb->execute();
		return $rowsUpdated;
	}

	public function updateStatusByVndReqId($vendorId, $reqId, $totalCnt = 0)
	{
		$sql		 = "SELECT COUNT(1) as cnt
                            FROM `vendor_agmt_docs`
                            WHERE vendor_agmt_docs.vd_vnd_id  ='$vendorId'
                            AND vendor_agmt_docs.vd_agmt_req_id='$reqId'
                            AND vendor_agmt_docs.vd_agmt_status=0 ";
		$totCnt		 = DBUtil::command($sql)->queryScalar();
		$totalCnt	 = ($totCnt == $totalCnt) ? $totalCnt : $totCnt;
		if ($totalCnt > 0)
		{
			$sql = "UPDATE `vendor_agmt_docs`,(
                            SELECT COUNT(1) as cnt,vd_vnd_id,vd_agmt_req_id
                            FROM `vendor_agmt_docs`
                            WHERE vendor_agmt_docs.vd_vnd_id ='$vendorId'
                            AND vendor_agmt_docs.vd_agmt_req_id='$reqId'
                            AND vendor_agmt_docs.vd_agmt_status=0
                        )a SET vendor_agmt_docs.vd_agmt_status=1
                        WHERE vendor_agmt_docs.vd_vnd_id=a.vd_vnd_id AND vendor_agmt_docs.vd_agmt_req_id=a.vd_agmt_req_id
                        AND vendor_agmt_docs.vd_agmt_status=0 AND a.cnt=$totalCnt";
		}
		else
		{
			$sql = "UPDATE `vendor_agmt_docs`
                       SET vendor_agmt_docs.vd_agmt_status=3
                       WHERE vendor_agmt_docs.vd_vnd_id=$vendorId AND vendor_agmt_docs.vd_agmt_req_id='$reqId'";
		}
		$cdb		 = DBUtil::command($sql);
		$rowsUpdated = $cdb->execute();
		return $rowsUpdated;
	}

	public function updateVendorAgreement($agmt1, $agmt1_tmp, $vendorId, $agmt_file1_img_no, $agmt_req_id, $agmt2, $agmt2_tmp, $agmt_file2_img_no, $total_agmt_img_no, $userType = 2)
	{
		$userInfo	 = UserInfo::getInstance();
		$userId		 = $userInfo->getUserId();
		$app_row	 = AppTokens::model()->getByUserTypeAndUserId($userId, $userType);
		$deviceId	 = $app_row['apt_device'];
		$today		 = date("Y-m-d H:i:s");
		$type		 = 'agreement';
		$contactId	 = ContactProfile::getByEntityId($vendorId, UserInfo::TYPE_VENDOR);
		if (!$contactId)
		{
			$model		 = Vendors::model()->findByPk($vendorId);
			$contactId	 = $model->vnd_contact_id;
		}
		if (($total_agmt_img_no >= $agmt_file1_img_no))
		{
			if ($agmt1 != '')
			{
				$result1 = Document::model()->saveVendorImage($agmt1, $agmt1_tmp, $vendorId, $contactId, $type);
				$path1	 = str_replace("'\'", "\\\\", $result1['path']);

				$sql		 = "INSERT INTO `vendor_agmt_docs` (`vd_vnd_id`, `vd_agmt_img_no`, `vd_agmt_req_id`, `vd_agmt_device_id`, `vd_agmt`, `vd_agmt_status`, `vd_agmt_date`)
				VALUES ('$vendorId', '$agmt_file1_img_no', '$agmt_req_id', '$deviceId', '$path1', '0', '$today')";
				$recorset	 = DBUtil::command($sql)->execute();
			}
		}

		if (($total_agmt_img_no >= $agmt_file2_img_no))
		{
			if ($agmt2 != '')
			{
				$result2	 = Document::model()->saveVendorImage($agmt2, $agmt2_tmp, $vendorId, $contactId, $type);
				$path1		 = str_replace("'\'", "\\\\", $result2['path']);
				$sql		 = "INSERT INTO `vendor_agmt_docs` (`vd_vnd_id`, `vd_agmt_img_no`, `vd_agmt_req_id`, `vd_agmt_device_id`, `vd_agmt`, `vd_agmt_status`, `vd_agmt_date`)
				VALUES ('$vendorId', '$agmt_file2_img_no', '$agmt_req_id', '$deviceId', '$path1', '0', '$today')";
				$recorset	 = DBUtil::command($sql)->execute();
			}
		}
		return $recorset;
	}

	////////////////////////////////////////////////////////////////////
	////////////////////////////////////////
	public function getLocalAgreementPath()
	{
		$filePath = $this->vd_agmt;

		$filePath = implode("/", explode(DIRECTORY_SEPARATOR, $filePath));

		$filePath = ltrim($filePath, '/attachments');

		$filePath = implode(DIRECTORY_SEPARATOR, explode("/", $filePath));

		$filePath = $this->getAgreementLocalPath() . $filePath;

		if (!file_exists($filePath))
		{
			$filePath = APPLICATION_PATH . $this->vd_agmt;
		}

		return $filePath;
	}

	public function getAgreementLocalPath()
	{
		return PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR;
	}

	public function getAgreementSpacePath($localPath)
	{
		$fileName	 = basename($localPath);
		$vndId		 = $this->vd_vnd_id;
		if ($vndId == '')
		{
			$vndId = 0;
		}

		$fileType		 = self::AGREEMENT_FILE_TYPE;
		$fileName		 = $fileType . '_' . $this->vd_agmt_id . '_' . $fileName;
		$folderExtender	 = Filter::s3FolderPath($vndId);
		$path			 = "/vendor/agreement/{$folderExtender}/{$vndId}/{$fileName}";
		return $path;
	}

	public function getDigitalSpacePath()
	{
		return $this->getAgreementSpacePath($this->vd_agmt);
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
	public function uploadAgreementFileToSpace($removeLocal = true)
	{
		$spaceFile = null;
		try
		{
			$vdModel		 = $this;
			$localFilePath	 = $vdModel->getLocalAgreementPath();
			if (!file_exists($localFilePath) || $vdModel->vd_agmt == '')
			{
				if ($vdModel->vd_agmt_s3_data == '')
				{
					$vdModel->vd_agmt_s3_data = "{}";
					$vdModel->save();
					return null;
				}
			}
			$spaceFile = $vdModel->uploadToSpace($localFilePath, $vdModel->getDigitalSpacePath(), $removeLocal);

			$vdModel->vd_agmt_s3_data = $spaceFile->toJSON();
			$vdModel->save();
		}
		catch (Exception $exc)
		{
			ReturnSet::setException($exc);
		}
		return $spaceFile;
	}

	public static function uploadAllToS3($limit = 1000)
	{
		while ($limit > 0)
		{
			$limit1 = min([1000, $limit]);

			$sql = "SELECT vd_agmt_id, vd_agmt FROM vendor_agmt_docs 
					WHERE vd_agmt != '' AND vd_agmt_s3_data IS NULL  
					ORDER BY vd_agmt_id DESC LIMIT 0, $limit1";

			$res = DBUtil::query($sql);

			if ($res->getRowCount() == 0)
			{
				break;
			}
			foreach ($res as $row)
			{
				/** @var Document $docModel */
				$vdModel = VendorAgmtDocs::model()->findByPk($row["vd_agmt_id"]);
				$vdModel->uploadAgreementFileToSpace();
			}

			$limit -= $limit1;
			Logger::flush();
		}
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
	public function getVdAgmtSpaceFile()
	{
		return $this->getSpaceFile($this->vd_agmt_s3_data);
	}

}

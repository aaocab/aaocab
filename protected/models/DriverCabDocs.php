<?php

/**
 * This is the model class for table "driver_cab_docs".
 *
 * The followings are the available columns in table 'driver_cab_docs':
 * @property integer $dcd_id
 * @property integer $dcd_vhc_id
 * @property integer $dcd_drv_id
 * @property string  $dcd_vhc_doc_ids
 * @property string  $dcd_drv_doc_ids
 * @property integer $dcd_user_type
 * @property integer $dcd_user_id
 * @property integer $dcd_cat_count
 * @property integer $dcd_processed
 * @property string  $dcd_date_uploaded_on
 * @property integer $dcd_status
 */
class DriverCabDocs extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'driver_cab_docs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dcd_vhc_id, dcd_drv_id, dcd_user_type, dcd_user_id', 'required'),
			array('dcd_vhc_id, dcd_drv_id, dcd_user_type, dcd_user_id, dcd_cat_count,dcd_processed, dcd_status', 'numerical', 'integerOnly' => true),
			array('dcd_vhc_doc_ids, dcd_drv_doc_ids', 'length', 'max' => 200),
			array('dcd_date_uploaded_on', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('dcd_id, dcd_vhc_id, dcd_drv_id, dcd_vhc_doc_ids, dcd_drv_doc_ids, dcd_user_type, dcd_user_id, dcd_date_uploaded_on, dcd_cat_count,dcd_processed, dcd_status', 'safe', 'on' => 'search'),
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
			'dcd_id'				 => 'Dcd',
			'dcd_vhc_id'			 => 'Vhc',
			'dcd_drv_id'			 => 'Drv',
			'dcd_vhc_doc_ids'		 => 'Vhc Doc Ids',
			'dcd_drv_doc_ids'		 => 'Drv Doc Ids',
			'dcd_user_type'			 => 'User Type',
			'dcd_user_id'			 => 'User',
			'dcd_cat_count'			 => 'Category Count',
			'dcd_processed'			 => 'Dcd processed',
			'dcd_date_uploaded_on'	 => 'Date Uploaded On',
			'dcd_status'			 => 'Status',
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
		$criteria->compare('dcd_id', $this->dcd_id);
		$criteria->compare('dcd_vhc_id', $this->dcd_vhc_id);
		$criteria->compare('dcd_drv_id', $this->dcd_drv_id);
		$criteria->compare('dcd_vhc_doc_ids', $this->dcd_vhc_doc_ids, true);
		$criteria->compare('dcd_drv_doc_ids', $this->dcd_drv_doc_ids, true);
		$criteria->compare('dcd_user_type', $this->dcd_user_type);
		$criteria->compare('dcd_user_id', $this->dcd_user_id);
		$criteria->compare('dcd_cat_count', $this->dcd_cat_count);
		$criteria->compare('dcd_processed', $this->dcd_processed);
		$criteria->compare('dcd_date_uploaded_on', $this->dcd_date_uploaded_on, true);
		$criteria->compare('dcd_status', $this->dcd_status);
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DriverCabDocs the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function add($result)
	{
		$driverIDArr	 = $result['driverIDs'];
		$vehicleIdArr	 = $result['vehicleIds'];
		$driverIDs		 = implode(',', array_filter($driverIDArr));
		$vehicleIds		 = implode(',', array_filter($vehicleIdArr));
		$model			 = $this->findByDrvVhcid($result['dcd_drv_id'], $result['dcd_vhc_id']);
		if ($model)
		{
			$oldDrvDocs	 = $model->dcd_drv_doc_ids;
			$oldVhcDocs	 = $model->dcd_vhc_doc_ids;
			$driverIDs	 = trim($oldDrvDocs . ',' . $driverIDs, ',');
			$vehicleIds	 = trim($oldVhcDocs . ',' . $vehicleIds, ',');
		}
		else
		{
			$model = new DriverCabDocs();
		}
		$model->attributes		 = $result;
		$model->dcd_drv_doc_ids	 = $driverIDs;
		$model->dcd_vhc_doc_ids	 = $vehicleIds;
		$success				 = $model->save();
		if ($model->save())
		{
			if ($driverIDs || $vehicleIds)
			{
				$model->dcd_cat_count = 1;
			}
			if ($driverIDs && $vehicleIds)
			{
				$model->dcd_cat_count = 2;
			}
			$success = $model->save();
		}

		return $success;
	}

	public function findByDrvVhcid($drvid, $vhcid)
	{
		$criteria	 = new CDbCriteria;
		$criteria->compare('dcd_drv_id', $drvid);
		$criteria->compare('dcd_vhc_id', $vhcid);
		$model		 = $this->find($criteria);
		return $model;
	}

	public function createHash($drvid, $vhcid)
	{
		$dhash	 = Yii::app()->shortHash->hash($drvid . '0');
		$vhash	 = Yii::app()->shortHash->hash($vhcid . '1');
		return $dhash . $vhash;
	}

	public function getDriverDocsToUpload($drvid)
	{
		$allowedDrvDoctype	 = [1 => [1, 2], 2 => [1, 2],3=>[1,2], 4 => [1, 2], 5 => [0]];
		$sqlDrv				 = "SELECT DISTINCT drd.drd_type,drd.drd_sub_type
		FROM drivers LEFT JOIN driver_docs drd ON drd.drd_drv_id = drivers.drv_id 
		WHERE d2.drv_id in (SELECT d3.drv_id FROM drivers d1
          INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
          INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
          WHERE d1.drv_id=$drvid) AND drd_status IN (0, 1) AND drd_active = 1 AND drd.drd_file != ''
		ORDER BY drd.drd_type";
		$drvResultSet		 = DBUtil::command($sqlDrv)->queryAll();
		$drvResultSetArr	 = [];
		foreach ($drvResultSet as $dttypes)
		{
			$drvResultSetArr[$dttypes['drd_type']][] = $dttypes['drd_sub_type'];
		}
		$docArr = array_diff_assoc($allowedDrvDoctype, $drvResultSetArr);
		return $docArr;
	}

	public function getVehicleDocsToUpload($vhcid)
	{
		$allowedVhdtype	 = [1, 2, 3, 4, 5, 6, 7];
		$mandateDocs	 = [1, 5, 6];
		$strDocType		 = implode(',', $allowedVhdtype);
		$sqlDrv			 = "SELECT   DISTINCT vhd.vhd_type
		FROM  vehicles LEFT JOIN vehicle_docs vhd ON vhd.vhd_vhc_id = vehicles.vhc_id
		WHERE vhc_id = $vhcid AND   vhd_type IN (" . $strDocType . ") AND
		vhd_status IN (0, 1) AND vhd_active = 1 AND vhd.vhd_file != ''
		ORDER BY vhd.vhd_type;";
		$vhdResultSet	 = DBUtil::command($sqlDrv)->queryColumn();
		$docArr			 = array_diff($allowedVhdtype, $vhdResultSet);
		foreach ($docArr as $imp)
		{
			if (in_array($imp, $mandateDocs))
			{
				return $docArr;
			}
		}
		return [];
	}

	

	public function uploadAttachments($uploadedFile, $type, $vehicleId, $folderName)
	{
		$fileName	 = $vehicleId . "-" . $type . "-" . date('YmdHis') . "." . pathinfo($uploadedFile, PATHINFO_EXTENSION);
		$dir		 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
		if (!is_dir($dir))
		{
			mkdir($dir);
		}
		$dirFolderName = $dir . DIRECTORY_SEPARATOR . $folderName;
		if (!is_dir($dirFolderName))
		{
			mkdir($dirFolderName);
		}
		$dirByVehicleId = $dirFolderName . DIRECTORY_SEPARATOR . $vehicleId;
		if (!is_dir($dirByVehicleId))
		{
			mkdir($dirByVehicleId);
		}
		$foldertoupload	 = $dirByVehicleId . DIRECTORY_SEPARATOR . $fileName;
		$extention		 = pathinfo($uploadedFile, PATHINFO_EXTENSION);
		if (strtolower($extention) == 'png' || strtolower($extention) == 'jpg' || strtolower($extention) == 'jpeg' || strtolower($extention) == 'gif')
		{
			Vehicles::model()->img_resize($uploadedFile->tempName, 1200, $dirByVehicleId . DIRECTORY_SEPARATOR, $fileName);
		}
		else
		{
			$uploadedFile->saveAs($foldertoupload);
		}
		$path = DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . $folderName . DIRECTORY_SEPARATOR . $vehicleId . DIRECTORY_SEPARATOR . $fileName;
		return $path;
	}

	public function sendSMStoUnapprovedDriver()
	{
		$drvResultSet = DriverCabDocs::model()->getUnapprovedDriverCab();
		foreach ($drvResultSet as $drv)
		{
			$drvId	 = $drv['drv_id'];
			$vhcId	 = $drv['vhc_id'];
			$drvName = strtok($drv['drv_name'], " ");
			$lHash	 = DriverCabDocs::model()->createHash($drvId, $vhcId);
			$link	 = "gozocabs.com/u/" . $lHash;
			$msg	 = "Get Rs.150 from Gozo Cabs. Upload papers for Driver '" . $drvName . "' and Cab '" . $drv['vhc_number'] . "' in 24hr. 
Open: $link . Get approved. Make Rs.150";

			$msgCom	 = new smsWrapper();
			$phone	 = str_replace('-', '', str_replace(' ', '', $drv['drv_phone']));
			if (strlen($phone) >= 10)
			{
				echo $msg;echo "<br>";
				$phone = substr($phone, -10);
				$msgCom->sendSMStoDrivers($phone, $msg);
			}
		}
	}

	public function processApprovalStatus()
	{
		$dataArr		 = DriverCabDocs::model()->getApprovalStatus();
		$totData		 = 0;
		$totBonusAdded	 = 0;
		$bonusAmount	 = 150;
		$remarks		 = "Payment for GetApproved promotion";
		foreach ($dataArr as $data)
		{
			$totData++;
			$userinfo	 = UserInfo::getInstance();
			$driverId	 = $data['dcd_drv_id'];
			$success	 = AccountTransactions::model()->addDriverBonus($bonusAmount, '', $driverId, $userinfo, $smsSent	 = 0, $remarks);
			if ($success)
			{
				$dcdid	 = $data['dcd_id'];
				$sql	 = "UPDATE driver_cab_docs
						    SET  dcd_processed = 1
						    WHERE  dcd_id = $dcdid";
				DBUtil::command($sql)->execute();
				$totBonusAdded++;
			}
		}
		echo "Total data fetched : " . $totData;
		echo "<br>";
		echo "Total GetApproved promotion paid to : " . $totBonusAdded . "drivers";
	}

}

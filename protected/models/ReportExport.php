<?php

/**
 * This is the model class for table "report_export".
 *
 * The followings are the available columns in table 'report_export':
 * @property integer $rpe_id
 * @property integer $rpe_rpt_id
 * @property string $rpe_file_name
 * @property integer $rpe_access_type
 * @property string $rpe_access
 * @property string $rpe_params
 * @property string $rpe_download_link
 * @property string $rpe_expiry_time
 * @property string $rpe_create_date
 * @property integer $rpe_status
 * @property integer $rpe_isFile_created
 */
class ReportExport extends CActiveRecord
{

	public $create_date1, $create_date2;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'report_export';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rpe_rpt_id, rpe_file_name, rpe_access, rpe_create_date', 'required'),
			array('rpe_rpt_id, rpe_access_type, rpe_status,rpe_isFile_created', 'numerical', 'integerOnly' => true),
			array('rpe_file_name, rpe_access', 'length', 'max' => 255),
			array('rpe_expiry_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rpe_id, rpe_rpt_id,rpe_download_link,rpe_file_name, rpe_access_type, rpe_access,rpe_params, rpe_expiry_time, rpe_create_date, rpe_status,rpe_isFile_created', 'safe', 'on' => 'search'),
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
			'rpe_id'			 => 'Id',
			'rpe_rpt_id'		 => 'Report  Id',
			'rpe_file_name'		 => 'Report file name',
			'rpe_access_type'	 => 'Report Access Type',
			'rpe_access'		 => 'Report Access',
			'rpe_params'		 => 'Report Params',
			'rpe_expiry_time'	 => 'Report Expiry Time',
			'rpe_create_date'	 => 'Report Create Date',
			'rpe_download_link'	 => 'Report Download Link',
			'rpe_status'		 => 'Report Status',
			'rpe_isFile_created' => 'Report File Created Status',
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

		$criteria->compare('rpe_id', $this->rpe_id);
		$criteria->compare('rpe_rpt_id', $this->rpe_rpt_id, true);
		$criteria->compare('rpe_file_name', $this->rpe_file_name, true);
		$criteria->compare('rpe_access_type', $this->rpe_access_type);
		$criteria->compare('rpe_access', $this->rpe_access, true);
		$criteria->compare('rpe_params', $this->rpe_params, true);
		$criteria->compare('rpe_download_link', $this->rpe_download_link, true);
		$criteria->compare('rpe_expiry_time', $this->rpe_expiry_time, true);
		$criteria->compare('rpe_create_date', $this->rpe_create_date, true);
		$criteria->compare('rpe_status', $this->rpe_status, true);
		$criteria->compare('rpe_isFile_created', $this->rpe_isFile_created, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ReportExport the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function CreateRequest($jsonArr, $reportId, $filename, $expiryDate, $accessType, $access)
	{
		$returnSet							 = new ReturnSet();
		$requestModel						 = new ReportExport();
		$requestModel->rpe_rpt_id			 = $reportId;
		$requestModel->rpe_file_name		 = $filename;
		$requestModel->rpe_access_type		 = $accessType;
		$requestModel->rpe_access			 = $access;
		$requestModel->rpe_create_date		 = new CDbExpression("NOW()");
		$requestModel->rpe_params			 = json_encode($jsonArr);
		$requestModel->rpe_expiry_time		 = $expiryDate;
		$requestModel->rpe_status			 = 1;
		$requestModel->rpe_isFile_created	 = 1;
		if ($requestModel->save())
		{
			$returnSet->setStatus(true);
			$returnSet->setMessage("Report export request accepted successfully");
		}
		else
		{
			$error = $requestModel->errors;
			$returnSet->setStatus(false);
			$returnSet->setMessage("Request not accepted for processing!");
		}
		return $returnSet;
	}

	public static function createCsv($rowData)
	{
		$filename	 = $rowData['rpe_file_name'];
		$filePath	 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'ReportExported';
		if (!is_dir($filePath))
		{
			mkdir($filePath);
		}
		$backup_file = $filePath . DIRECTORY_SEPARATOR . $filename;
		$file		 = fopen($backup_file, 'w');
		$jsonDecode	 = json_decode($rowData['rpe_params'], true);
		fputcsv($file, $jsonDecode['keys']);
		switch ($rowData['rpe_rpt_id'])
		{
			case 16:
				$model							 = new CallStatus('search');
				$arr['cst_id']					 = $jsonDecode['params']['cst_id'];
				$arr['cst_lead_id']				 = $jsonDecode['params']['cst_lead_id'];
				$arr['cst_phone_code']			 = $jsonDecode['params']['cst_phone_code'];
				$arr['cst_phone']				 = $jsonDecode['params']['cst_phone'];
				$arr['cst_did']					 = $jsonDecode['params']['cst_did'];
				$arr['cst_agent_name']			 = $jsonDecode['params']['cst_agent_name'];
				$arr['cst_recording_file_name']	 = $jsonDecode['params']['cst_recording_file_name'];
				$arr['cst_created']				 = $jsonDecode['params']['cst_created'];
				$arr['cst_modified']			 = $jsonDecode['params']['cst_modified'];
				$rows							 = $model->getAudios(array_filter($arr), true);
				foreach ($rows as $row)
				{
					$rowArray							 = array();
					$rowArray['cst_id']					 = $row['cst_id'];
					$rowArray['cst_lead_id']			 = $row['cst_lead_id'];
					$rowArray['cst_phone_code']			 = (string) $row['cst_phone_code'];
					$rowArray['cst_phone']				 = (string) $row['cst_phone'];
					$rowArray['cst_did']				 = $row['cst_did'] . " Min";
					$rowArray['cst_agent_name']			 = $row['cst_agent_name'];
					$rowArray['cst_recording_file_name'] = "";
					if ($row['cst_recording_file_name'] != '')
					{
						$basePath							 = yii::app()->basePath;
						$rowArray['cst_recording_file_name'] = $basePath . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . $row['cst_recording_file_name'];
					}
					$rowArray['cst_status']		 = CallStatus::callstatus[$row['cst_status']];
					$rowArray['cst_created']	 = $row['cst_created'];
					$rowArray['cst_modified']	 = $row['cst_modified'];
					$row1						 = array_values($rowArray);
					fputcsv($file, $row1);
				}
				fclose($file);
				break;
			case 27:
				$model							 = new Booking();
				$model->bkg_create_date1		 = $jsonDecode['params']['bkg_create_date1'];
				$model->bkg_create_date2		 = $jsonDecode['params']['bkg_create_date2'];
				$model->bkg_pickup_date1		 = $jsonDecode['params']['bkg_pickup_date1'];
				$model->bkg_pickup_date2		 = $jsonDecode['params']['bkg_pickup_date2'];
				$model->tripAssignmnetFromTime	 = $jsonDecode['params']['tripAssignmnetFromTime'];
				$model->tripAssignmnetToTime	 = $jsonDecode['params']['tripAssignmnetToTime'];
				$model->bkg_service_class		 = explode(",", $jsonDecode['params']['bkg_service_class']);
				$param['is_advance_amount']		 = $jsonDecode['params']['is_advance_amount'];
				$param['is_dbo_applicable']		 = $jsonDecode['params']['is_dbo_applicable'];
				$param['is_reconfirm_flag']		 = $jsonDecode['params']['is_reconfirm_flag'];
				$param['is_New']				 = $jsonDecode['params']['is_New'];
				$param['is_Assigned']			 = $jsonDecode['params']['is_Assigned'];
				$param['is_Manual']				 = $jsonDecode['params']['is_Manual'];
				$rows							 = BookingSub::model()->autoAssignReport($model, $param, true);
				foreach ($rows as $row)
				{
					$rowArray									 = array();
					$rowArray['bcb_id']							 = $row['bcb_id'];
					$rowArray['bcb_bkg_id1']					 = $row['bcb_bkg_id1'];
					$rowArray['company']						 = $row['company'];
					$rowArray['pickup']							 = $row['pickup'];
					$rowArray['createdt']						 = $row['createdt'];
					$rowArray['reconfirm']						 = $row['reconfirm'];
					$rowArray['bid']							 = $row['bid'];
					$rowArray['dbapply']						 = $row['dbapply'];
					$rowArray['dboAmt']							 = $row['dboAmt'];
					$rowArray['cs']								 = $row['cs'];
					$rowArray['demsup_misfire']					 = $row['demsup_misfire'];
					$rowArray['ma']								 = $row['ma'];
					$rowArray['ca']								 = $row['ca'];
					$rowArray['bkg_advance_amount']				 = $row['baa'];
					$rowArray['MaxBid']							 = $row['bidCount'] . "/" . round($row['avgBid'], 2) . "/" . $row['maxBid'] . "/" . $row['minBid'];
					$rowArray['bva']							 = $row['bva'];
					$rowArray['bcb_vendor_amount']				 = $row['bcb_vendor_amount'];
					$rowArray['gozoAmount']						 = $row["gozoAmount"] . "/(" . round(($row['gozoAmount'] / $row["bcb_vendor_amount"]) * 100, 2) . "%)";
					$rowArray['bcb_max_allowable_vendor_amount'] = $row['bcb_max_allowable_vendor_amount'];
					$rowArray['bkg_assigned_at']				 = $row['bkg_assigned_at'];
					$row1										 = array_values($rowArray);
					fputcsv($file, $row1);
				}
				fclose($file);
				break;
			case 75:
				$model									 = new ServiceCallQueue();
				$csrTeam								 = $jsonDecode['params']['scq_to_be_followed_up_by_id'];
				$model->scq_to_be_followed_up_by_id		 = $csrTeam;
				$isFollowUpOpen							 = $jsonDecode['params']['isFollowUpOpen'] == 1 ? 1 : 0;
				$model->isFollowUpOpen					 = $isFollowUpOpen;
				$isDue24								 = $jsonDecode['params']['isDue24'] == 1 && $isFollowUpOpen == 1 ? 1 : 0;
				$model->isDue24							 = $isDue24;
				$model->search							 = $search									 = !empty($jsonDecode['params']['search']) ? $jsonDecode['params']['search'] : "";
				$model->requestedBy						 = $jsonDecode['params']['requestedBy'];
				$model->custId							 = $jsonDecode['params']['custId'];
				$model->vendId							 = $jsonDecode['params']['vendId'];
				$model->drvId							 = $jsonDecode['params']['drvId'];
				$model->adminId							 = $jsonDecode['params']['adminId'];
				$model->agntId							 = $jsonDecode['params']['agntId'];
				$model->scq_to_be_followed_up_by_type	 = $jsonDecode['params']['scq_to_be_followed_up_by_type'];
				$model->scq_to_be_followed_up_by_id		 = $jsonDecode['params']['scq_to_be_followed_up_by_id'];
				$model->isGozen							 = $jsonDecode['params']['isGozen'];
				$rows									 = $model->getInternals($isDue24, $search, $isFollowUpOpen, true);
				$teamarr								 = Teams::getList();

				foreach ($rows as $row)
				{
					$rowArray											 = array();
					$rowArray['scq_id']									 = $row['scq_id'];
					$detail												 = Admins::model()->getProfileData($row['scq_created_by_uid']);
					$rowArray['scq_created_by_uid']						 = $detail[0]['adm_fname'] . ' ' . $detail[0]['adm_lname'];
					$rowArray['scq_creation_comments']					 = $row['scq_creation_comments'];
					$rowArray['scq_to_be_followed_up_with_entity_type']	 = $row['callerType'] . "(" . $row['contactName'] . ")";
					$rowArray['scq_to_be_followed_up_by_type']			 = $row['scq_to_be_followed_up_by_type'] == 1 ? $teamarr[$row['scq_to_be_followed_up_by_type']] : "";
					$rowArray['gozen']									 = $row['gozen'];
					$rowArray['scq_disposition_comments']				 = $row['scq_disposition_comments'];
					$rowArray['scq_create_date']						 = date('Y-m-d H:i:s', strtotime($row['scq_create_date']));
					$rowArray['scq_follow_up_date_time']				 = date('Y-m-d H:i:s', strtotime($row['scq_follow_up_date_time']));
					$rowArray['scq_related_bkg_id']						 = $row['scq_related_bkg_id'];
					$rowArray['scq_status']								 = $row['scq_status'] == "2" ? "Completed" : ($row['scq_status'] == 3 ? "Partial Close" : "Open");
					$row1												 = array_values($rowArray);
					fputcsv($file, $row1);
				}
				fclose($file);
				break;
			case 76:
				$rows = BookingTrail::getBookingTrackDetails($jsonDecode['params']);
				foreach ($rows as $row)
				{
					$rowArray							 = array();
					$rowArray['bkg_agent_id']			 = Agents::model()->findByPk($row['bkg_agent_id'])->agt_company;
					$rowArray['bkg_booking_id']			 = $row['bkg_booking_id'];
					$rowArray['bkg_agent_ref_code']		 = $row['bkg_agent_ref_code'];
					$rowArray['bkg_status']				 = Booking::model()->getActiveBookingStatus($row['bkg_status']);
					$rowArray['bkg_route_city_names']	 = implode(" - ", json_decode($row['bkg_route_city_names']));
					$vndDetails							 = Vendors::model()->findByPk($row['bcb_vendor_id']);
					$drvDetails							 = Drivers::model()->findByPk($row['bcb_driver_id']);
					$rowArray['bcb_vendor_id']			 = $vndDetails->vnd_code;
					$rowArray['bcb_driver_id']			 = $drvDetails->drv_code;
					$rowArray['bkgPickupDate']			 = ($row['bkg_pickup_date']) ? date("d/m/Y H:i:s", strtotime($row['bkg_pickup_date'])) : '';
					$rowArray['bkgCreateDate']			 = ($row['bkg_create_date']) ? date("d/m/Y H:i:s", strtotime($row['bkg_create_date'])) : '';
					$rowArray['btrVendorAssignLdate']	 = ($row['btr_vendor_assign_ldate']) ? date("d/m/Y H:i:s", strtotime($row['btr_vendor_assign_ldate'])) : '';
					$rowArray['btrDriverAssignLdate']	 = ($row['btr_driver_assign_ldate']) ? date("d/m/Y H:i:s", strtotime($row['btr_driver_assign_ldate'])) : '';
					$rowArray['btrCabAssignLdate']		 = ($row['btr_cab_assign_ldate']) ? date("d/m/Y H:i:s", strtotime($row['btr_cab_assign_ldate'])) : '';
					$rowArray['bkgTripArriveTime']		 = ($row['bkg_trip_arrive_time']) ? date("d/m/Y H:i:s", strtotime($row['bkg_trip_arrive_time'])) : '';
					$rowArray['bkgTripStartTime']		 = ($row['bkg_trip_start_time']) ? date("d/m/Y H:i:s", strtotime($row['bkg_trip_start_time'])) : '';
					$rowArray['bkgTripEndTime']			 = ($row['bkg_trip_end_time']) ? date("d/m/Y H:i:s", strtotime($row['bkg_trip_end_time'])) : '';
					$rowArray['estPickupLatlong']		 = $row['estPickupLatlong'];
					$rowArray['estDropupLatlong']		 = $row['estDropupLatlong'];
					$row1								 = array_values($rowArray);
					fputcsv($file, $row1);
				}
				fclose($file);
				break;
			default:
				break;
		}
		$link								 = Yii::app()->params['fullBaseURL'] . '/report/index/downloadDocs?reportExportId=' . $rowData['rpe_id'] . '&filename=' . $filename;
		$requestModel						 = ReportExport::model()->findByPk($rowData['rpe_id']);
		$requestModel->rpe_isFile_created	 = 2;
		$requestModel->rpe_download_link	 = $link;
		$requestModel->save();
	}

	public static function getExportData()
	{
		$sql = "SELECT * FROM  report_export WHERE rpe_status =1 AND rpe_expiry_time>NOW() AND rpe_isFile_created=1";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public function getExportList()
	{
		$where = "";
		if ($this->create_date1 != '' && $this->create_date2 != '')
		{
			$fromTime		 = '00:00:00';
			$toTime			 = '23:59:59';
			$fromDateTime	 = $this->create_date1 . ' ' . $fromTime;
			$toDateTime		 = $this->create_date2 . ' ' . $toTime;
		}
		if (($this->create_date1 != '' && $this->create_date1 != '1970-01-01') && ($this->create_date2 != '' && $this->create_date2 != '1970-01-01'))
		{
			$where .= " AND rpe_create_date BETWEEN '$fromDateTime' AND '$toDateTime' ";
		}
		if ($this->rpe_isFile_created)
		{
			$where .= " AND rpe_isFile_created = $this->rpe_isFile_created ";
		}
		if ($this->rpe_file_name != '')
		{
			$where .= " AND rpe_file_name LIKE '%{$this->rpe_file_name}%' ";
		}
		$sql			 = "SELECT * FROM report_export WHERE 1 AND rpe_status=1 $where ";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes'	 => ['rpe_file_name', 'rpe_create_date'],
				'defaultOrder'	 => ''], 'pagination'	 => ['pageSize' => 50],
		]);

		return $dataprovider;
	}

	public static function getExpiryExportData()
	{
		$sql = "SELECT rpe_id,rpe_file_name FROM  report_export WHERE rpe_status =1 AND rpe_expiry_time<NOW() AND rpe_isFile_created=2 AND rpe_download_link IS NOT NULL";
		return DBUtil::query($sql, DBUtil::SDB());
	}

}

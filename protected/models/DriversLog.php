<?php

/**
 * This is the model class for table "drivers_log".
 *
 * The followings are the available columns in table 'drivers_log':
 * @property integer $dlg_id
 * @property integer $dlg_user_ref_id
 * @property integer $dlg_user_type
 * @property integer $dlg_drv_id
 * @property string $dlg_desc
 * @property integer $dlg_event_id
 * @property string $dlg_created
 * @property integer $dlg_active
 * @property integer $dlg_ref_id
 */
class DriversLog extends CActiveRecord
{

	const Consumers					 = 1;
	const Vendor						 = 2;
	const Driver						 = 3;
	const Admin						 = 4;
	const Agent						 = 5;
	const System						 = 10;
	const DRIVER_CREATED				 = 1;
	const DRIVER_MODIFIED				 = 2;
	const DRIVER_ACTIVE				 = 3;
	const DRIVER_DELETE				 = 4;
	const DRIVER_FREEZE				 = 5;
	const DRIVER_UNFREEZE				 = 6;
	const DRIVER_AADHAR_APPROVE		 = 7;
	const DRIVER_AADHAR_REJECT		 = 8;
	const DRIVER_PAN_APPROVE			 = 9;
	const DRIVER_PAN_REJECT			 = 10;
	const DRIVER_VOTER_APPROVE		 = 11;
	const DRIVER_VOTER_REJECT			 = 12;
	const DRIVER_DL_APPROVE			 = 13;
	const DRIVER_DL_REJECT			 = 14;
	const DRIVER_PC_APPROVE			 = 15;
	const DRIVER_PC_REJECT			 = 16;
	const DRIVER_DL_BACK_APPROVE		 = 17;
	const DRIVER_DL_BACK_REJECT		 = 18;
	const DRIVER_VOTERID_UPLOAD		 = 19;
	const DRIVER_AADHAAR_UPLOAD		 = 20;
	const DRIVER_PAN_UPLOAD			 = 21;
	const DRIVER_DL_UPLOAD			 = 22;
	const DRIVER_VOTERID_BACK_UPLOAD	 = 23;
	const DRIVER_AADHAAR_BACK_UPLOAD	 = 24;
	const DRIVER_PAN_BACK_UPLOAD		 = 25;
	const DRIVER_DL_BACK_UPLOAD		 = 26;
	const DRIVER_PC_UPLOAD			 = 27;
	const DRIVER_FILE_UPLOAD			 = 28;
	const DRIVER_VOTER_BACK_APPROVE	 = 29;
	const DRIVER_VOTER_BACK_REJECT	 = 30;
	const DRIVER_PAN_BACK_APPROVE		 = 31;
	const DRIVER_PAN_BACK_REJECT		 = 32;
	const DRIVER_AADHAR_BACK_APPROVE	 = 33;
	const DRIVER_AADHAR_BACK_REJECT	 = 34;
	const DRIVER_PENDING_APPROVAL		 = 35;
	const DRIVER_REJECTED				 = 36;
	const DRIVER_APPROVED				 = 37;
	const DRIVER_VENDOR_DELETE		 = 38;
	const DRIVER_READY_FOR_APPROVAL	 = 39;
	const DRIVER_SOCIAL_UNLINK		 = 40;
	const Driver_MERGE				 = 41;
	const DRIVER_REMARK_ADDED			 = 42;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'drivers_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dlg_id, dlg_user_ref_id, dlg_user_type, dlg_drv_id, dlg_event_id, dlg_active', 'numerical', 'integerOnly' => true),
			array('dlg_desc', 'length', 'max' => 4000),
			array('dlg_desc', 'required', 'on' => 'updateFreeze'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('dlg_id, dlg_user_ref_id, dlg_user_type, dlg_drv_id, dlg_desc, dlg_event_id, dlg_created, dlg_active, dlg_ref_id', 'safe', 'on' => 'search'),
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
			'dlg_id'			 => 'Dlg',
			'dlg_user_ref_id'	 => 'Dlg User Ref',
			'dlg_user_type'		 => 'Dlg User Type',
			'dlg_drv_id'		 => 'Dlg Drv',
			'dlg_event_id'		 => 'Dlg Event',
			'dlg_created'		 => 'Dlg Created',
			'dlg_active'		 => 'Dlg Active',
			'dlg_desc'			 => 'Comments'
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

		$criteria->compare('dlg_id', $this->dlg_id);
		$criteria->compare('dlg_user_ref_id', $this->dlg_user_ref_id);
		$criteria->compare('dlg_user_type', $this->dlg_user_type);
		$criteria->compare('dlg_drv_id', $this->dlg_drv_id);
		$criteria->compare('dlg_desc', $this->dlg_desc, true);
		$criteria->compare('dlg_event_id', $this->dlg_event_id);
		$criteria->compare('dlg_created', $this->dlg_created, true);
		$criteria->compare('dlg_active', $this->dlg_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DriversLog the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function eventList()
	{
		$eventlist = [
			1	 => 'Driver Created',
			2	 => 'Driver Modified',
			3	 => 'Driver Active',
			4	 => 'Driver Blocked',
			5	 => 'Freeze',
			6	 => 'Unfreeze',
			7	 => 'Aadhaar Card Approved',
			8	 => 'Aadhaar Card Rejected',
			9	 => 'Pan Card Approved',
			10	 => 'Pan Card Rejected',
			11	 => 'Voter Id Approved',
			12	 => 'Voter Id Rejected',
			13	 => 'Driver License (FRONT) Approved',
			14	 => 'Driver License (FRONT) Rejected',
			15	 => 'Police Verification Approved',
			16	 => 'Police Verification Rejected',
			17	 => 'Driver License (BACK) Approved',
			18	 => 'Driver License (BACK) Rejected',
			19	 => 'VoterId (Front) Upload',
			20	 => 'Aadhaar (Front) Upload',
			21	 => 'Pan (Front) Upload',
			22	 => 'DL (Front) Upload',
			23	 => 'VoterId (BACK)  Upload',
			24	 => 'Aadhaar (BACK) Upload',
			25	 => 'Pan (BACK) Upload',
			26	 => 'DL (BACK) Upload',
			27	 => 'Police Verification Upload',
			28	 => 'FILE Upload',
			29	 => 'Voter Id (BACK) Approved',
			30	 => 'Voter Id (BACK) Rejected',
			31	 => 'Pan Card (BACK) Approved',
			32	 => 'Pan Card (BACK) Rejected',
			33	 => 'Aadhaar Card (BACK) Approved',
			34	 => 'Aadhaar Card (BACK) Rejected',
			35	 => 'Driver Pending Approval',
			36	 => 'Driver Rejected',
			37	 => 'Driver Approved',
			38	 => 'Driver Removed ( Vendor )',
			39	 => 'Driver Ready For Approval',
			40	 => 'Driver Social Unlink',
			41	 => 'Driver Merge',
			42	 => 'Driver Remark Added'
		];
		asort($eventlist);
		return $eventlist;
	}

	public function getEventByEventId($eventId)
	{
		$list = $this->eventList();
		return $list[$eventId];
	}

	public function getLogByDocumentType($type = 'voterid')
	{
		$log = [];
		switch ($type)
		{
			case 'voterid':
				$log['upload']	 = DriversLog::DRIVER_VOTERID_UPLOAD;
				$log['reject']	 = DriversLog::DRIVER_VOTER_REJECT;
				$log['approve']	 = DriversLog::DRIVER_VOTER_APPROVE;
				break;
			case 'voterbackid':
				$log['upload']	 = DriversLog::DRIVER_VOTERID_BACK_UPLOAD;
				$log['reject']	 = DriversLog::DRIVER_VOTER_BACK_REJECT;
				$log['approve']	 = DriversLog::DRIVER_VOTER_BACK_APPROVE;
				break;
			case 'aadhar':
				$log['upload']	 = DriversLog::DRIVER_AADHAAR_UPLOAD;
				$log['reject']	 = DriversLog::DRIVER_AADHAR_REJECT;
				$log['approve']	 = DriversLog::DRIVER_AADHAR_APPROVE;
				break;
			case 'aadharback':
				$log['upload']	 = DriversLog::DRIVER_AADHAAR_BACK_UPLOAD;
				$log['reject']	 = DriversLog::DRIVER_AADHAR_BACK_REJECT;
				$log['approve']	 = DriversLog::DRIVER_AADHAR_BACK_APPROVE;
				break;
			case 'pan':
				$log['upload']	 = DriversLog::DRIVER_PAN_UPLOAD;
				$log['reject']	 = DriversLog::DRIVER_PAN_REJECT;
				$log['approve']	 = DriversLog::DRIVER_PAN_APPROVE;
				break;
			case 'panback':
				$log['upload']	 = DriversLog::DRIVER_PAN_BACK_UPLOAD;
				$log['reject']	 = DriversLog::DRIVER_PAN_BACK_REJECT;
				$log['approve']	 = DriversLog::DRIVER_PAN_BACK_APPROVE;
				break;
			case 'license':
				$log['upload']	 = DriversLog::DRIVER_DL_UPLOAD;
				$log['reject']	 = DriversLog::DRIVER_DL_REJECT;
				$log['approve']	 = DriversLog::DRIVER_DL_APPROVE;
				break;
			case 'licenseback':
				$log['upload']	 = DriversLog::DRIVER_DL_BACK_UPLOAD;
				$log['reject']	 = DriversLog::DRIVER_DL_BACK_REJECT;
				$log['approve']	 = DriversLog::DRIVER_DL_BACK_APPROVE;
				break;
			case 'policever':
				$log['upload']	 = DriversLog::DRIVER_PC_UPLOAD;
				$log['reject']	 = DriversLog::DRIVER_PC_REJECT;
				$log['approve']	 = DriversLog::DRIVER_PC_APPROVE;
				break;
		}
		return $log;
	}

	public function getByDriverId($drvId, $viewType = 0)
	{
		$pageSize = 25;
		if ($viewType == 1)
		{
			$pageSize = 10;
		}
		$sql			 = "SELECT drivers.drv_id, drivers_log.*,
                            (CASE drivers_log.dlg_user_type
                            WHEN 1 THEN CONCAT(admins.adm_fname,' ',admins.adm_lname)
                            WHEN 2 THEN CONCAT(admins.adm_fname,' ',admins.adm_lname) 
                            WHEN 3 THEN drivers.drv_name
                            WHEN 4 THEN CONCAT(admins.adm_fname,' ',admins.adm_lname)
                            WHEN 5 THEN CONCAT(admins.adm_fname,' ',admins.adm_lname)
                            ELSE 'System' END) AS name,
                (CASE drivers_log.dlg_user_type 
                    WHEN 1 then 'Consumers'
                            WHEN 2 THEN 'Vendor'
                            WHEN 3 then 'Driver'
                            WHEN 4 then 'Admin'
                            WHEN 5 then 'Agent'
                            WHEN 10 then 'System'
                            ELSE '' END) as type
                        FROM `drivers_log`
                        JOIN `drivers` ON drivers.drv_id=drivers_log.dlg_drv_id 
						LEFT JOIN `admins` ON admins.adm_id=drivers_log.dlg_user_ref_id
                        WHERE drivers_log.dlg_drv_id in (SELECT d3.drv_id FROM drivers d1
          INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
          INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
          WHERE d1.drv_id='$drvId')  and drivers.drv_ref_code=drivers.drv_id ";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['dlg_desc', 'dlg_event_id', 'dlg_created', 'name', 'type'],
				'defaultOrder'	 => 'dlg_created  DESC'], 'pagination'	 => ['pageSize' => $pageSize],
		]);

		return $dataprovider;
	}

	public function createLog($drv_id, $desc, UserInfo $userInfo = null, $event_id, $oldModel = false, $params = false)
	{
		/* @var $drvLog DriversLog  */
		/* @var $oldModel DriversLog  */
		$drvLog					 = new DriversLog();
		$drvLog->dlg_drv_id		 = $drv_id;
		$drvLog->dlg_desc		 = $desc;
		$drvLog->dlg_user_type	 = $userInfo->userType;
		$drvLog->dlg_user_ref_id = $userInfo->userId;
		$drvLog->dlg_event_id	 = $event_id;
		if ($oldModel)
		{
			$drvLog->dlg_active = $oldModel->dlg_active;
		}
		if ($params['dlg_created'] != ''):
			$drvLog->dlg_created = $params['dlg_created'];
		endif;
		if ($params['dlg_active'] != ''):
			$drvLog->dlg_active = $params['dlg_active'];
		endif;
		if ($drvLog->validate())
		{
			$drvLog->save();
		}
		else
		{
			echo json_encode($drvLog->getErrors());
			exit();
			echo "Error in Log";
			exit;
		}
	}

	public static function docTypeDCO($type = '')
	{
		$docArr = [
			201	 => 'voterid',
			202	 => 'voterbackid',
			301	 => 'aadhar',
			302	 => 'aadharback',
			401	 => 'pan',
			402	 => 'panback',
			501	 => 'license',
			502	 => 'licenseback',
			700	 => 'policever'
		];
		if ($type > 0)
		{
			return $docArr[$type];
		}
		return $docArr;
	}

}

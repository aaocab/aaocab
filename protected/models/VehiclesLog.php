<?php

/**
 * This is the model class for table "vehicles_log".
 *
 * The followings are the available columns in table 'vehicles_log':
 * @property integer $clg_ld
 * @property integer $clg_user_ref_id
 * @property integer $clg_user_type
 * @property integer $clg_vhc_id
 * @property string $clg_desc
 * @property integer $clg_event_id
 * @property string $clg_created
 * @property integer $clg_active
 */
class VehiclesLog extends CActiveRecord
{

	const Consumers							 = 1;
	const Vendor								 = 2;
	const Driver								 = 3;
	const Admin								 = 4;
	const Agent								 = 5;
	const System								 = 10;
	const VEHICLE_CREATED						 = 1;
	const VEHICLE_MODIFIED					 = 2;
	const VEHICLE_ACTIVE						 = 3;
	const VEHICLE_DELETE						 = 4;
	const VEHICLE_FREEZE						 = 5;
	const VEHICLE_UNFREEZE					 = 6;
	const VEHICLE_APPROVED					 = 7;
	const VEHICLE_PENDING_APPROVAL			 = 8;
	const VEHICLE_REJECTED					 = 9;
	const VEHICLE_INSURANCE_APPROVE			 = 10;
	const VEHICLE_INSURANCE_REJECT			 = 11;
	const VEHICLE_FRONT_LICENSE_APPROVE		 = 12;
	const VEHICLE_FRONT_LICENSE_REJECT		 = 13;
	const VEHICLE_REAR_LICENSE_APPROVE		 = 14;
	const VEHICLE_REAR_LICENSE_REJECT			 = 15;
	const VEHICLE_PUC_APPROVE					 = 16;
	const VEHICLE_PUC_REJECT					 = 17;
	const VEHICLE_REGISTRATION_APPROVE		 = 18;
	const VEHICLE_REGISTRATION_REJECT			 = 19;
	const VEHICLE_PERMITS_APPROVE				 = 20;
	const VEHICLE_PERMITS_REJECT				 = 21;
	const VEHICLE_FITNESS_APPROVE				 = 22;
	const VEHICLE_FITNESS_REJECT				 = 23;
	const VEHICLE_VENDOR_DELETE				 = 24;
	const VEHICLE_INSURANCE_UPLOAD			 = 25;
	const VEHICLE_FRONT_LICENSE_UPLOAD		 = 26;
	const VEHICLE_REAR_LICENSE_UPLOAD			 = 27;
	const VEHICLE_PUC_UPLOAD					 = 28;
	const VEHICLE_REGISTRATION_UPLOAD			 = 29;
	const VEHICLE_PERMITS_UPLOAD				 = 30;
	const VEHICLE_FITNESS_UPLOAD				 = 31;
	const VEHICLE_FILE_UPLOAD					 = 32;
	const VEHICLE_READY_APPROVAL				 = 33;
	const VEHICLE_INSURANCE_TEMP_APPROVE		 = 34;
	const VEHICLE_REGISTRATION_TEMP_APPROVE	 = 35;
	const VEHICLE_BOOST_REJECTED				 = 36;
	const VEHICLE_FILE_REJECTED				 = 37;
	const VEHICLE_CAR_FRONT_APPROVE			 = 38;
	const VEHICLE_CAR_FRONT_REJECT			 = 39;
	const VEHICLE_CAR_BACK_APPROVE			 = 40;
	const VEHICLE_CAR_BACK_REJECT				 = 41;
	const VEHICLE_CAR_LEFT_APPROVE			 = 42;
	const VEHICLE_CAR_LEFT_REJECT				 = 43;
	const VEHICLE_CAR_RIGHT_APPROVE			 = 44;
	const VEHICLE_CAR_RIGHT_REJECT			 = 45;
	const VEHICLE_CAR_APPROVE_BOOST_REJECT	 = 46;
	const VEHICLE_CAR_APPROVE_BOOST_ENABLE	 = 47;
	const VEHICLE_CAR_REJECT_BOOST_REJECT		 = 48;
	const VEHICLE_REMARK_ADDED				 = 49;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vehicles_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('clg_user_ref_id, clg_user_type, clg_vhc_id, clg_event_id, clg_active', 'numerical', 'integerOnly' => true),
			array('clg_desc', 'length', 'max' => 4000),
			array('clg_desc', 'required', 'on' => 'updateFreeze'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('clg_ld, clg_user_ref_id, clg_user_type, clg_vhc_id, clg_desc, clg_event_id, clg_created, clg_active', 'safe', 'on' => 'search'),
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
			'clg_ld'			 => 'Clg Ld',
			'clg_user_ref_id'	 => 'Clg User Ref',
			'clg_user_type'		 => 'Clg User Type',
			'clg_vhc_id'		 => 'Clg Vhc',
			'clg_event_id'		 => 'Clg Event',
			'clg_created'		 => 'Clg Created',
			'clg_active'		 => 'Clg Active',
			'clg_desc'			 => 'Comments'
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

		$criteria->compare('clg_ld', $this->clg_ld);
		$criteria->compare('clg_user_ref_id', $this->clg_user_ref_id);
		$criteria->compare('clg_user_type', $this->clg_user_type);
		$criteria->compare('clg_vhc_id', $this->clg_vhc_id);
		$criteria->compare('clg_desc', $this->clg_desc, true);
		$criteria->compare('clg_event_id', $this->clg_event_id);
		$criteria->compare('clg_created', $this->clg_created, true);
		$criteria->compare('clg_active', $this->clg_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VehiclesLog the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function eventList()
	{
		$eventlist = [
			1	 => 'Vehicle Created',
			2	 => 'Vehicle Modified',
			3	 => 'Vehicle Active',
			4	 => 'Vehicle Blocked',
			5	 => 'Freeze',
			6	 => 'Unfreeze',
			7	 => 'Vehicle Approved',
			8	 => 'Vehicle Pending Approval',
			9	 => 'Vehicle Rejected',
			10	 => 'Insurance Approved',
			11	 => 'Insurance Rejected',
			12	 => 'Front License Approved',
			13	 => 'Front License Rejected',
			14	 => 'Rear License Approved',
			15	 => 'Rear License Rejected',
			16	 => 'PUC Approved',
			17	 => 'PUC Rejected',
			18	 => 'Registration Approved',
			19	 => 'Registration Rejected',
			20	 => 'Commercial Permits Approved',
			21	 => 'Commercial Permits Rejected',
			22	 => 'Fitness Approved',
			23	 => 'Fitness Rejected',
			24	 => 'Vehicle Removed ( Vendor )',
			25	 => 'Insurance Upload',
			26	 => 'Front License Plate Upload',
			27	 => 'Rear License Plate Upload',
			28	 => 'PUC Upload',
			29	 => 'Registration Certificate Upload',
			30	 => 'Commercial Permits Upload',
			31	 => 'Fitness Certificate Upload',
			32	 => 'FILE Upload',
			33	 => 'Vehicle Ready For Approval',
			34	 => 'Insurance Temporary Approved',
			35	 => 'Registration Temporary Approved',
			36	 => 'Vehicle Boost Rejected',
			49	 => 'Vehicle Remark Added'
		];
		asort($eventlist);
		return $eventlist;
	}

	public function getEventByEventId($eventId)
	{
		$list = $this->eventList();
		return $list[$eventId];
	}

	public function createLog($vhc_id, $desc, UserInfo $userInfo = null, $event_id, $oldModel = false, $params = false)
	{
		/* @var $vhcLog VehiclesLog  */
		/* @var $oldModel VehiclesLog  */

		$vhcLog					 = new VehiclesLog();
		$vhcLog->clg_vhc_id		 = $vhc_id;
		$vhcLog->clg_desc		 = $desc;
		$vhcLog->clg_user_type	 = $userInfo->userType;
		if ($userInfo->userType == 4)
		{
			$vhcLog->clg_user_ref_id = $userInfo->userId;
		}
		else
		{
			$vhcLog->clg_user_ref_id = UserInfo::getEntityId();
		}
		$vhcLog->clg_event_id = $event_id;
		if ($oldModel):
			$vhcLog->clg_active = $oldModel->clg_active;
		endif;
		if ($params['clg_created'] != ''):
			$vhcLog->clg_created = $params['clg_created'];
		endif;
		if ($params['clg_active'] != ''):
			$vhcLog->clg_active = $params['clg_active'];
		endif;
		if ($vhcLog->validate())
		{
			$vhcLog->save();
		}
		else
		{
			echo json_encode($vhcLog->getErrors());
			exit();
			echo "Error in Log";
			exit;
		}
	}

	public function getByVehicleId($vhcId, $viewType = 0)
	{
		$sql			 = "SELECT
							vehicles_log.clg_desc,
							vehicles_log.clg_event_id, 
							vehicles_log.clg_created,
							vehicles_log.clg_user_ref_id,
							IF(vehicles_log.clg_user_type!=10,CONCAT(admins.adm_fname,' ',admins.adm_lname),'System') as name,
							(CASE vehicles_log.clg_user_type
								WHEN 1 then 'Consumers'
								WHEN 2 THEN 'Vendor'
								WHEN 3 then 'Driver'
								WHEN 4 then 'Admin'
								WHEN 5 then 'Agent'
								WHEN 10 then 'System'
								ELSE '' END) as type
							FROM `vehicles_log`
							JOIN `vehicles` ON vehicles.vhc_id=vehicles_log.clg_vhc_id
							LEFT JOIN `admins` ON admins.adm_id=vehicles_log.clg_user_ref_id
							WHERE vehicles_log.clg_vhc_id=$vhcId";
		$sqlCount		 = "SELECT
							COUNT(*)
							FROM `vehicles_log`
							JOIN `vehicles` ON vehicles.vhc_id=vehicles_log.clg_vhc_id
							WHERE vehicles_log.clg_vhc_id=$vhcId";
		$count			 = DBUtil::command($sqlCount, DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes' => ['clg_desc', 'clg_event_id', 'clg_created', 'name', 'type'], 'defaultOrder' => 'clg_created  DESC'], 'pagination'	 => ['pageSize' => 25],
		]);
		return $dataprovider;
	}

	public function getLogByDocumentType($type = 1)
	{
		$log = [];
		switch ($type)
		{
			case 1:
				$log['upload']	 = VehiclesLog::VEHICLE_INSURANCE_UPLOAD;
				$log['reject']	 = VehiclesLog::VEHICLE_INSURANCE_REJECT;
				$log['approve']	 = VehiclesLog::VEHICLE_INSURANCE_APPROVE;
				$log['doc']		 = VehicleDocs::model()->getDocType($type);
				break;
			case 2:
				$log['upload']	 = VehiclesLog::VEHICLE_FRONT_LICENSE_UPLOAD;
				$log['reject']	 = VehiclesLog::VEHICLE_FRONT_LICENSE_REJECT;
				$log['approve']	 = VehiclesLog::VEHICLE_FRONT_LICENSE_APPROVE;
				$log['doc']		 = VehicleDocs::model()->getDocType($type);

				break;
			case 3:
				$log['upload']	 = VehiclesLog::VEHICLE_REAR_LICENSE_UPLOAD;
				$log['reject']	 = VehiclesLog::VEHICLE_REAR_LICENSE_REJECT;
				$log['approve']	 = VehiclesLog::VEHICLE_REAR_LICENSE_APPROVE;
				$log['doc']		 = VehicleDocs::model()->getDocType($type);

				break;
			case 4:
				$log['upload']	 = VehiclesLog::VEHICLE_PUC_UPLOAD;
				$log['reject']	 = VehiclesLog::VEHICLE_PUC_REJECT;
				$log['approve']	 = VehiclesLog::VEHICLE_PUC_APPROVE;
				$log['doc']		 = VehicleDocs::model()->getDocType($type);
				break;
			case 5:
				$log['upload']	 = VehiclesLog::VEHICLE_REGISTRATION_UPLOAD;
				$log['reject']	 = VehiclesLog::VEHICLE_REGISTRATION_REJECT;
				$log['approve']	 = VehiclesLog::VEHICLE_REGISTRATION_APPROVE;
				$log['doc']		 = VehicleDocs::model()->getDocType($type);
				break;
			case 6:
				$log['upload']	 = VehiclesLog::VEHICLE_PERMITS_UPLOAD;
				$log['reject']	 = VehiclesLog::VEHICLE_PERMITS_REJECT;
				$log['approve']	 = VehiclesLog::VEHICLE_PERMITS_APPROVE;
				$log['doc']		 = VehicleDocs::model()->getDocType($type);
				break;
			case 7:
				$log['upload']	 = VehiclesLog::VEHICLE_FITNESS_UPLOAD;
				$log['reject']	 = VehiclesLog::VEHICLE_FITNESS_REJECT;
				$log['approve']	 = VehiclesLog::VEHICLE_FITNESS_APPROVE;
				$log['doc']		 = VehicleDocs::model()->getDocType($type);
				break;
			case 8:
				$log['upload']	 = VehiclesLog::VEHICLE_FILE_UPLOAD;
				$log['reject']	 = VehiclesLog::VEHICLE_FILE_REJECTED;
				$log['approve']	 = VehiclesLog::VEHICLE_READY_APPROVAL;
				$log['doc']		 = VehicleDocs::model()->getDocType($type);
				break;
			case 9:
				$log['upload']	 = VehiclesLog::VEHICLE_FILE_UPLOAD;
				$log['reject']	 = VehiclesLog::VEHICLE_FILE_REJECTED;
				$log['approve']	 = VehiclesLog::VEHICLE_READY_APPROVAL;
				$log['doc']		 = VehicleDocs::model()->getDocType($type);
				break;
			case 10:
				$log['upload']	 = VehiclesLog::VEHICLE_FILE_UPLOAD;
				$log['reject']	 = VehiclesLog::VEHICLE_FILE_REJECTED;
				$log['approve']	 = VehiclesLog::VEHICLE_READY_APPROVAL;
				$log['doc']		 = VehicleDocs::model()->getDocType($type);
				break;
			case 11:
				$log['upload']	 = VehiclesLog::VEHICLE_FILE_UPLOAD;
				$log['reject']	 = VehiclesLog::VEHICLE_FILE_REJECTED;
				$log['approve']	 = VehiclesLog::VEHICLE_READY_APPROVAL;
				$log['doc']		 = VehicleDocs::model()->getDocType($type);
				break;
			case 13:
				$log['upload']	 = VehiclesLog::VEHICLE_REGISTRATION_UPLOAD;
				$log['reject']	 = VehiclesLog::VEHICLE_REGISTRATION_REJECT;
				$log['approve']	 = VehiclesLog::VEHICLE_REGISTRATION_APPROVE;
				$log['doc']		 = VehicleDocs::model()->getDocType($type);
				break;
		}
		return $log;
	}

	public function getDocumentLogByVehicleId($vhcId, $viewType = 0)
	{
		$sql			 = "SELECT
							vehicles_log.clg_desc,
							vehicles_log.clg_event_id, 
							vehicles_log.clg_created,
							vehicles_log.clg_user_ref_id,
							IF(vehicles_log.clg_user_type!=10,CONCAT(admins.adm_fname,' ',admins.adm_lname),'System') as name,
							(CASE vehicles_log.clg_user_type
								WHEN 1 then 'Consumers'
								WHEN 2 THEN 'Vendor'
								WHEN 3 then 'Driver'
								WHEN 4 then 'Admin'
								WHEN 5 then 'Agent'
								WHEN 10 then 'System'
								ELSE '' END) as type
							FROM `vehicles_log`
							INNER JOIN `vehicles` ON vehicles.vhc_id=vehicles_log.clg_vhc_id
							LEFT JOIN `admins` ON admins.adm_id=vehicles_log.clg_user_ref_id
							WHERE vehicles_log.clg_vhc_id=$vhcId AND vehicles_log.clg_event_id BETWEEN 5 AND 22";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes' => ['clg_desc', 'clg_event_id', 'clg_created', 'name', 'type'],
			'defaultOrder' => 'clg_created  DESC'], 'pagination'	 => ['pageSize' => 25],
		]);
		return $dataprovider;
	}

}

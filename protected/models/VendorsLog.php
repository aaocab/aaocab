<?php

/**
 * This is the model class for table "vendors_log".
 *
 * The followings are the available columns in table 'vendors_log':
 * @property integer $vlg_id
 * @property integer $vlg_ref_id
 * @property integer $vlg_user_ref_id
 * @property integer $vlg_user_type
 * @property integer $vlg_vnd_id
 * @property string $vlg_desc
 * @property integer $vlg_event_id
 * @property string $vlg_created
 * @property integer $vlg_active
 */
class VendorsLog extends CActiveRecord
{

	const Consumers							 = 1;
	const Vendor								 = 2;
	const Driver								 = 3;
	const Admin								 = 4;
	const Agent								 = 5;
	const System								 = 10;
	const VENDOR_CREATED						 = 1;
	const VENDOR_MODIFIED						 = 2;
	const VENDOR_ACTIVE						 = 3;
	const VENDOR_INACTIVE						 = 4;
	const VENDOR_MARKED_BAD					 = 5;
	const VENDOR_FREEZE						 = 6;
	const VENDOR_UNFREEZE						 = 7;
	const MAIL								 = 8;
	const SMS									 = 9;
	const EFFECTIVE_CREDIT_LIMIT_SET			 = 10;
	const EFFECTIVE_CREDIT_LIMIT_UNSET		 = 11;
	const VENDOR_INVOICE_SENT					 = 12;
	const VENDOR_REMARK_ADDED					 = 13;
	const VENDOR_ADMINISTRATIVE_FREEZE		 = 14;
	const VENDOR_ADMINISTRATIVE_UNFREEZE		 = 15;
	const VENDOR_COD_FREEZE					 = 16;
	const VENDOR_COD_UNFREEZE					 = 17;
	const VENDOR_EDIT							 = 18;
	const VENDOR_MEMORANDUM_UPLOAD			 = 19;
	const VENDOR_PROFILE_UPLOAD				 = 20;
	const VENDOR_VOTERID_UPLOAD				 = 21;
	const VENDOR_AADHAAR_UPLOAD				 = 22;
	const VENDOR_PAN_UPLOAD					 = 23;
	const VENDOR_LICENSE_UPLOAD				 = 24;
	const VENDOR_VOTERID_BACK_UPLOAD			 = 25;
	const VENDOR_AADHAAR_BACK_UPLOAD			 = 26;
	const VENDOR_PAN_BACK_UPLOAD				 = 27;
	const VENDOR_LICENSE_BACK_UPLOAD			 = 28;
	const VENDOR_AGREMENT_UPLOAD				 = 29;
	const VENDOR_FILE_UPLOAD					 = 30;
	const VENDOR_AGREEMENT_APPROVE			 = 31;
	const VENDOR_AGREEMENT_REJECT				 = 32;
	const VENDOR_VOTERID_APPROVE				 = 33;
	const VENDOR_VOTERID_REJECT				 = 34;
	const VENDOR_VOTERID_BACK_APPROVE			 = 35;
	const VENDOR_VOTERID_BACK_REJECT			 = 36;
	const VENDOR_PAN_APPROVE					 = 37;
	const VENDOR_PAN_REJECT					 = 38;
	const VENDOR_PAN_BACK_APPROVE				 = 39;
	const VENDOR_PAN_BACK_REJECT				 = 40;
	const VENDOR_LICENSE_APPROVE				 = 41;
	const VENDOR_LICENSE_REJECT				 = 42;
	const VENDOR_LICENSE_BACK_APPROVE			 = 43;
	const VENDOR_LICENSE_BACK_REJECT			 = 44;
	const VENDOR_AADHAAR_APPROVE				 = 45;
	const VENDOR_AADHAAR_REJECT				 = 46;
	const VENDOR_AADHAAR_BACK_APPROVE			 = 47;
	const VENDOR_AADHAAR_BACK_REJECT			 = 48;
	const VENDOR_MEMORANDUM_APPROVE			 = 49;
	const VENDOR_MEMORANDUM_REJECT			 = 50;
	const VENDOR_DOC_APPROVE					 = 51;
	const VENDOR_DELETED						 = 52;
	const VENDOR_VEHICLE_DELETE				 = 53;
	const VENDOR_DIGITAL_AGREEMENT_APPROVE	 = 54;
	const VENDOR_DIGITAL_AGREEMENT_REJECT		 = 55;
	const VENDOR_DRIVER_DELETE				 = 56;
	const VENDOR_READY_APPROVAL				 = 57;
	const VENDOR_APPROVE						 = 60;
	const VENDOR_MERGE						 = 61;
	const VENDOR_SOCIAL_UNLINK				 = 62;
	const VENDOR_ORIENTATION_ON				 = 63;
	const VENDOR_ORIENTATION_OFF				 = 64;
	const VENDOR_GOLDEN_TIER					 = 65;
	const VENDOR_TIER_DENY					 = 66;
	const VENDOR_UPDATE_TRIP_STATUS_REMINDER	 = 65;
	const CREDIT_LIMIT_UNSET					 = 67;
	const PAYMENT_MADE						 = 70;
	const VENDOR_AGREEMENT_REJECT_TO_NORMAL	 = 71;
	const VENDOR_TEMP_RATINGS					 = 80;
	const VENDOR_SR							 = 81;
	const VENDOR_SECURITY_DEPOSIT				 = 82;
	const VENDOR_DEPENDENCY_BOOSTED           =85;

	public $name, $type, $vlg_orientation_type;
	public $vlg_create_date1, $vlg_create_date2;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vendors_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vlg_user_ref_id, vlg_user_type, vlg_vnd_id, vlg_event_id, vlg_active', 'numerical', 'integerOnly' => true),
			array('vlg_desc', 'length', 'max' => 4000),
			array('vlg_desc', 'required', 'on' => 'updateFreeze'),
			array('vlg_desc,vlg_orientation_type', 'required', 'on' => 'updateOrientation'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vlg_id,vlg_ref_id, vlg_user_ref_id, vlg_user_type, vlg_vnd_id, vlg_desc, vlg_event_id, vlg_created, vlg_active, vlg_orientation_type, name, type', 'safe', 'on' => 'search'),
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
			'vlg_id'				 => 'Vlg',
			'vlg_user_ref_id'		 => 'Vlg User Ref',
			'vlg_user_type'			 => 'Vlg User Type',
			'vlg_vnd_id'			 => 'Vlg Vnd',
			'vlg_desc'				 => 'Vlg Desc',
			'vlg_event_id'			 => 'Vlg Event',
			'vlg_created'			 => 'Vlg Created',
			'vlg_active'			 => 'Vlg Active',
			'vlg_desc'				 => 'Comments',
			'vlg_orientation_type'	 => 'Orientation Type'
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

		$criteria->compare('vlg_id', $this->vlg_id);
		$criteria->compare('vlg_user_ref_id', $this->vlg_user_ref_id);
		$criteria->compare('vlg_user_type', $this->vlg_user_type);
		$criteria->compare('vlg_vnd_id', $this->vlg_vnd_id);
		$criteria->compare('vlg_desc', $this->vlg_desc, true);
		$criteria->compare('vlg_event_id', $this->vlg_event_id);
		$criteria->compare('vlg_created', $this->vlg_created, true);
		$criteria->compare('vlg_active', $this->vlg_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VendorsLog the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function eventList()
	{
		$eventlist = [
			1	 => 'Vendor Created',
			2	 => 'Vendor Modified',
			3	 => 'Active',
			4	 => 'Blocked',
			5	 => 'Bad Marked',
			6	 => 'Freeze',
			7	 => 'Unfreeze',
			8	 => 'Mail Sent',
			9	 => 'SMS Sent',
			10	 => 'Effective Credit Limit Set',
			11	 => 'Effective Credit Limit Unset',
			12	 => 'Vendor Invoice Sent',
			13	 => 'Vendor Remark Added',
			14	 => 'Vendor Administrative Freeze',
			15	 => 'Vendor Administrative Unreeze',
			16	 => 'Vendor COD Freeze',
			17	 => 'Vendor COD Unfreeze',
			18	 => 'Vendor Edit',
			19	 => 'Vendor Memorandum Upload',
			20	 => 'Vendor Profile Upload',
			21	 => 'Vendor VoterId (Front) Upload',
			22	 => 'Vendor Aadhaar (Front) Upload',
			23	 => 'Vendor Pan (Front) Upload',
			24	 => 'Vendor License (Front) Upload',
			25	 => 'Vendor VoterId (Back) Upload',
			26	 => 'Vendor Aadhaar (Back) Upload',
			27	 => 'Vendor Pan (Back) Upload',
			28	 => 'Vendor License (Back) Upload',
			29	 => 'Vendor Agrement File Upload',
			30	 => 'Vendor File Upload',
			31	 => 'Agrement Approved',
			32	 => 'Agrement Rejected',
//			33	 => 'VoterId (Front) Approved',
//			34	 => 'VoterId (Front) Rejected',
//			35	 => 'VoterId (Back) Approved',
//			36	 => 'VoterId (Back) Rejected',
//			37	 => 'Pan (Front) Approved',
//			38	 => 'Pan (Front) Rejected',
//			39	 => 'Pan (Back) Approved',
//			40	 => 'Pan (Back) Rejected',
//			41	 => 'License (Front) Approved',
//			42	 => 'License (Front) Rejected',
//			43	 => 'License (Back) Approved',
//			44	 => 'License (Back) Rejected',
//			45	 => 'Aadhaar (Front) Approved',
//			46	 => 'Aadhaar (Front) Rejected',
//			47	 => 'Aadhaar (Back) Approved',
//			48	 => 'Aadhaar (Back) Rejected',
//			49	 => 'Memorandum Approved',
//			50	 => 'Memorandum Rejected',
			51	 => 'Document Approved',
			52	 => 'Deleted',
			53	 => 'Vehicle Removed ( Vendor )',
			54	 => 'Digital Agrement Approved',
			55	 => 'Digital Agrement Rejected',
			56	 => 'Driver Removed ( Vendor )',
			57	 => 'Ready for approval',
			60	 => 'Vendor Approved',
			61	 => 'Vendor Merge',
			62	 => 'Vendor Social Unlink',
			63	 => 'Vendor orientation required',
			64	 => 'Vendor orientation complete',
			65	 => 'Vendor Golden Tier',
			66	 => 'Vendor Tier Deny',
			70	 => 'Payment Made',
			80	 => 'Vendor Temporary Rating',
			81	 => 'Vendor Service Request For Vendor Approval',
			82	 => 'Vendor Security Deposit',
			85   => 'Boost Vendor Dependency'
		];
		asort($eventlist);
		return $eventlist;
	}

	public function getEventByEventId($eventId)
	{
		$list = $this->eventList();
		return $list[$eventId];
	}

	public function getByVendorId($vndId, $viewType = 0)
	{
		//$defaultSort = 'vlg_created  DESC';
		$pageSize = 25;
		if ($viewType == 1)
		{
			$pageSize = 20;
		}
		$sql			 = "SELECT vendors_log.*,
                (CASE vendors_log.vlg_user_type
                    WHEN 10 THEN 'System'
                    WHEN 4 THEN CONCAT(admins.adm_fname,' ',admins.adm_lname)
                    WHEN 2 THEN vendors.vnd_name
                    ELSE '' END
                ) as name,
                (CASE vendors_log.vlg_user_type WHEN 1 then 'Consumers'
                    WHEN 2 THEN 'Vendor'
                    WHEN 3 then 'Driver'
                    WHEN 4 then 'Admin'
                    WHEN 5 then 'Agent'
                    WHEN 10 then 'System'
                    ELSE '' END) as type
                FROM `vendors_log`
                INNER JOIN `vendors` ON vendors.vnd_id=vendors_log.vlg_vnd_id
                LEFT JOIN `admins` ON admins.adm_id=vendors_log.vlg_user_ref_id
                WHERE vendors_log.vlg_vnd_id IN (select v3.vnd_id FROM vendors v1 INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code 
				INNER JOIN vendors v3 ON v3.vnd_ref_code = v2.vnd_id  WHERE v1.vnd_id = '$vndId') AND vendors.vnd_id = vendors.vnd_ref_code ";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['vlg_desc', 'vlg_event_id', 'vlg_created', 'name', 'type'],
				'defaultOrder'	 => 'vlg_id  DESC'], 'pagination'	 => ['pageSize' => $pageSize],
		]);
		return $dataprovider;
	}

	public function getLogDetailsByRefId($refId, $eventId)
	{
		switch ($eventId)
		{
			case 8:
				$sql = "SELECT vendors_log.*, email_log.elg_ref_id, email_log.subject, email_log.body
                            FROM `vendors_log`
                            LEFT JOIN `email_log` ON email_log.id=vendors_log.vlg_ref_id
                            AND vendors_log.vlg_event_id=$eventId
                            WHERE vendors_log.vlg_ref_id=$refId";
				break;
			case 9:
				$sql = "SELECT vendors_log.*, sms_log.slg_type, sms_log.number, sms_log.message
                            FROM `vendors_log`
                            LEFT JOIN `sms_log` ON sms_log.id=vendors_log.vlg_ref_id
                            AND vendors_log.vlg_event_id=$eventId
                            WHERE vendors_log.vlg_ref_id=$refId";
				break;
		}
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) a")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['vlg_vnd_id', 'vlg_desc', 'vlg_ref_id'],
				'defaultOrder'	 => 'vlg_ref_id ASC'],
		]);
		return $dataprovider;
	}

	public function createLog($ven_id, $desc, UserInfo $userInfo = null, $event_id, $oldModel = false, $params = false)
	{
		/* @var $venLog VendorsLog  */
		$venLog = new VendorsLog();
		if ($userInfo == null)
		{
			$userInfo = UserInfo::model();
		}
		$venLog->vlg_vnd_id		 = $ven_id;
		$venLog->vlg_desc		 = $desc;
		$venLog->vlg_user_type	 = $userInfo->userType;
		$venLog->vlg_user_ref_id = $userInfo->userId;
		$venLog->vlg_event_id	 = $event_id;
		if ($oldModel)
		{
			$venLog->vlg_active = $oldModel->vlg_active;
		}
		if ($params['vlg_ref_id'] != ''):
			$venLog->vlg_ref_id = $params['vlg_ref_id'];
		endif;
		if ($params['vlg_created'] != ''):
			$venLog->vlg_created = $params['vlg_created'];
		endif;
		if ($params['vlg_active'] != ''):
			$venLog->vlg_active = $params['vlg_active'];
		endif;
		if ($venLog->validate())
		{

			$venLog->save();
		}
		else
		{
			echo json_encode($venLog->getErrors());
			exit();
			echo "Error in Log";
			exit;
		}
	}

	public function getVendorAssignmentTime($bkg_id)
	{
		$evenid	 = BookingLog::VENDOR_ASSIGNED;
		$sql	 = "SELECT vlg_created FROM vendors_log WHERE vlg_ref_id=$bkg_id AND vlg_event_id=$evenid";
		$data	 = DBUtil::queryRow($sql);
		return $data;
	}

	public function getLogByDocumentType($type = 'agreement')
	{
		$log = [];
		switch ($type)
		{
			case 'agreement':
				$log['upload']	 = VendorsLog::VENDOR_AGREMENT_UPLOAD;
				$log['reject']	 = VendorsLog::VENDOR_AGREEMENT_REJECT;
				$log['approve']	 = VendorsLog::VENDOR_AGREEMENT_APPROVE;
				break;
			case 'voterid':
				$log['upload']	 = VendorsLog::VENDOR_VOTERID_UPLOAD;
				$log['reject']	 = VendorsLog::VENDOR_VOTERID_REJECT;
				$log['approve']	 = VendorsLog::VENDOR_VOTERID_APPROVE;
				break;
			case 'voterbackid':
				$log['upload']	 = VendorsLog::VENDOR_VOTERID_BACK_UPLOAD;
				$log['reject']	 = VendorsLog::VENDOR_VOTERID_BACK_REJECT;
				$log['approve']	 = VendorsLog::VENDOR_VOTERID_BACK_APPROVE;
				break;
			case 'adhar':
				$log['upload']	 = VendorsLog::VENDOR_AADHAAR_UPLOAD;
				$log['reject']	 = VendorsLog::VENDOR_AADHAAR_REJECT;
				$log['approve']	 = VendorsLog::VENDOR_AADHAAR_APPROVE;
				break;
			case 'adharback':
				$log['upload']	 = VendorsLog::VENDOR_AADHAAR_BACK_UPLOAD;
				$log['reject']	 = VendorsLog::VENDOR_AADHAAR_BACK_REJECT;
				$log['approve']	 = VendorsLog::VENDOR_AADHAAR_BACK_APPROVE;
				break;
			case 'pan':
				$log['upload']	 = VendorsLog::VENDOR_PAN_UPLOAD;
				$log['reject']	 = VendorsLog::VENDOR_PAN_REJECT;
				$log['approve']	 = VendorsLog::VENDOR_PAN_APPROVE;
				break;
			case 'panback':
				$log['upload']	 = VendorsLog::VENDOR_PAN_BACK_UPLOAD;
				$log['reject']	 = VendorsLog::VENDOR_PAN_BACK_REJECT;
				$log['approve']	 = VendorsLog::VENDOR_PAN_BACK_APPROVE;
				break;
			case 'license':
				$log['upload']	 = VendorsLog::VENDOR_LICENSE_UPLOAD;
				$log['reject']	 = VendorsLog::VENDOR_LICENSE_REJECT;
				$log['approve']	 = VendorsLog::VENDOR_LICENSE_APPROVE;
				break;
			case 'licenseback':
				$log['upload']	 = VendorsLog::VENDOR_LICENSE_BACK_UPLOAD;
				$log['reject']	 = VendorsLog::VENDOR_LICENSE_BACK_REJECT;
				$log['approve']	 = VendorsLog::VENDOR_LICENSE_BACK_APPROVE;
				break;
			case 'memorandum':
				$log['upload']	 = VendorsLog::VENDOR_MEMORANDUM_UPLOAD;
				$log['reject']	 = VendorsLog::VENDOR_MEMORANDUM_REJECT;
				$log['approve']	 = VendorsLog::VENDOR_MEMORANDUM_APPROVE;
				break;
		}
		return $log;
	}

	public function getLogByDocTypeId($type, $vdSubType)
	{
		$log = [];
		switch ($type)
		{
			case '1':
				$log = VendorsLog::VENDOR_AGREMENT_UPLOAD;
				break;
			case '2':
				if ($vdSubType == 1)
				{
					$log = VendorsLog::VENDOR_VOTERID_UPLOAD;
				}
				if ($vdSubType == 2)
				{
					$log = VendorsLog::VENDOR_VOTERID_BACK_UPLOAD;
				}
				break;
			case '3':
				if ($vdSubType == 1)
				{
					$log = VendorsLog::VENDOR_AADHAAR_UPLOAD;
				}
				if ($vdSubType == 2)
				{
					$log = VendorsLog::VENDOR_AADHAAR_BACK_UPLOAD;
				}
				break;
			case '4':
				if ($vdSubType == 1)
				{
					$log = VendorsLog::VENDOR_PAN_UPLOAD;
				}
				if ($vdSubType == 2)
				{
					$log = VendorsLog::VENDOR_PAN_BACK_UPLOAD;
				}
				break;
			case '5':
				if ($vdSubType == 1)
				{
					$log = VendorsLog::VENDOR_LICENSE_UPLOAD;
				}
				if ($vdSubType == 2)
				{
					$log = VendorsLog::VENDOR_LICENSE_BACK_UPLOAD;
				}
				break;
			case '6':
				$log = VendorsLog::VENDOR_MEMORANDUM_UPLOAD;
				break;
		}
		return $log;
	}

	public static function getBlockReason($vndID)
	{
		$param	 = ['vndID' => $vndID, 'eventID' => VendorsLog::VENDOR_INACTIVE];
		$sql	 = "SELECT vlg_desc FROM `vendors_log` WHERE `vlg_vnd_id` = :vndID AND `vlg_event_id` = :eventID ORDER BY `vlg_id` DESC
				LIMIT 1";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), $param);
		return $result['vlg_desc'];
	}

	public static function docTypeDCO($type = '')
	{
		$docArr = [
			100	 => 'agreement',
			201	 => 'voterid',
			202	 => 'voterbackid',
			301	 => 'adhar',
			302	 => 'adharback',
			401	 => 'pan',
			402	 => 'panback',
			501	 => 'license',
			502	 => 'licenseback',
			600	 => 'memorandum'
		];
		if ($type > 0)
		{
			return $docArr[$type];
		}
		return $docArr;
	}

}

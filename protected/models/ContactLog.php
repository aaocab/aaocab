<?php

/**
 * This is the model class for table "contact_log".
 *
 * The followings are the available columns in table 'contact_log':
 * @property integer $clg_id
 * @property integer $clg_user_ref_id
 * @property integer $clg_user_type
 * @property integer $clg_ctt_id
 * @property string $clg_desc
 * @property integer $clg_event_id
 * @property string $clg_created
 * @property integer $clg_active
 *
 * The followings are the available model relations:
 * @property Contact $clgCtt
 */
class ContactLog extends CActiveRecord
{

	const Consumers							 = 1;
	const Vendor								 = 2;
	const Driver								 = 3;
	const Admin								 = 4;
	const Agent								 = 5;
	const System								 = 10;
	const CONTACT_CREATED						 = 1;
	const CONTACT_MODIFIED					 = 2;
	const CONTACT_ACTIVE						 = 3;
	const CONTACT_INACTIVE					 = 4;
	const CONTACT_AGREEMENT_APPROVE			 = 5;
	const CONTACT_AGREEMENT_REJECT			 = 6;
	const CONTACT_VOTERID_APPROVE				 = 7;
	const CONTACT_VOTERID_REJECT				 = 8;
	const CONTACT_AADHAAR_APPROVE				 = 9;
	const CONTACT_AADHAAR_REJECT				 = 10;
	const CONTACT_PAN_APPROVE					 = 11;
	const CONTACT_PAN_REJECT					 = 12;
	const CONTACT_LICENSE_APPROVE				 = 13;
	const CONTACT_LICENSE_REJECT				 = 14;
	const CONTACT_MEMORANDUM_APPROVE			 = 15;
	const CONTACT_MEMORANDUM_REJECT			 = 16;
	const CONTACT_DOC_APPROVE					 = 17;
	const CONTACT_MERGE						 = 18;
	const CONTACT_POLICE_VERIFICATION_APPROVE	 = 19;
	const CONTACT_POLICE_VERIFICATION_REJECT	 = 20;
	const CONTACT_PHONE_REMOVE				 = 21;
	const CONTACT_EMAIL_REMOVE				 = 22;
//
	const CONTACT_AGREEMENT_UPLOAD			 = 100;
	const CONTACT_VOTERID_FRONT_UPLOAD		 = 201;
	const CONTACT_VOTERID_BACK_UPLOAD			 = 202;
	const CONTACT_AADHAAR_FRONT_UPLOAD		 = 301;
	const CONTACT_AADHAAR_BACK_UPLOAD			 = 302;
	const CONTACT_PAN_FRONT_UPLOAD			 = 401;
	const CONTACT_PAN_BACK_UPLOAD				 = 402;
	const CONTACT_LICENSE_FRONT_UPLOAD		 = 501;
	const CONTACT_LICENSE_BACK_UPLOAD			 = 502;
	const CONTACT_MEMORANDUM_UPLOAD			 = 600;
	const CONTACT_POLICE_VERIFICATION_UPLOAD	 = 700;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'contact_log';
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
			array('clg_user_ref_id, clg_user_type, clg_ctt_id, clg_event_id, clg_active', 'numerical', 'integerOnly' => true),
			array('clg_desc', 'length', 'max' => 2000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('clg_id, clg_user_ref_id, clg_user_type, clg_ctt_id, clg_desc, clg_event_id, clg_created, clg_active', 'safe', 'on' => 'search'),
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
			'clgCtt' => array(self::BELONGS_TO, 'Contact', 'clg_ctt_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'clg_id'			 => 'Clg',
			'clg_user_ref_id'	 => 'Clg User Ref',
			'clg_user_type'		 => 'Clg User Type',
			'clg_ctt_id'		 => 'Clg Ctt',
			'clg_desc'			 => 'Clg Desc',
			'clg_event_id'		 => 'Clg Event',
			'clg_created'		 => 'Clg Created',
			'clg_active'		 => 'Clg Active',
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

		$criteria->compare('clg_id', $this->clg_id);
		$criteria->compare('clg_user_ref_id', $this->clg_user_ref_id);
		$criteria->compare('clg_user_type', $this->clg_user_type);
		$criteria->compare('clg_ctt_id', $this->clg_ctt_id);
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
	 * @return ContactLog the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function createLog($cttId, $desc, $event_id, UserInfo $userInfo = null, $oldModel = false, $params = false)
	{
		if ($userInfo == null)
		{
			$userInfo = UserInfo::getInstance();
		}
		/* @var $venLog VendorsLog  */
		$contactLog					 = new ContactLog();
		$contactLog->clg_ctt_id		 = $cttId;
		$contactLog->clg_desc		 = $desc;
		$contactLog->clg_user_type	 = $userInfo->userType;
		$contactLog->clg_user_ref_id = $userInfo->userId;
		$contactLog->clg_event_id	 = $event_id;
		if ($oldModel)
		{
			$contactLog->clg_active = $oldModel->clg_active;
		}
		if ($params['clg_created'] != ''):
			$contactLog->clg_created = $params['clg_created'];
		endif;
		if ($params['clg_active'] != ''):
			$contactLog->clg_active = $params['clg_active'];
		endif;
		if ($contactLog->validate())
		{
			$contactLog->save();
		}
		else
		{
			echo json_encode($contactLog->getErrors());
			exit();
			echo "Error in Log";
			exit;
		}
	}

	public function eventList($eventType = '')
	{
		$eventlist = [
			1	 => 'Contact Created',
			2	 => 'Contact Modified',
			3	 => 'Active',
			4	 => 'Blocked',
			5	 => 'Agrement Approved',
			6	 => 'Agrement Rejected',
			7	 => 'VoterId Approved',
			8	 => 'VoterId Rejected',
			9	 => 'Aadhaar Approved',
			10	 => 'Aadhaar Rejected',
			11	 => 'Pan Approved',
			12	 => 'Pan Rejected',
			13	 => 'License Approved',
			14	 => 'License Rejected',
			15	 => 'Memorandum Approved',
			16	 => 'Memorandum Rejected',
			17	 => 'Document Approved',
			18	 => 'Contact Merge',
			19	 => 'Police Verification Approved',
			20	 => 'Police Verification Rejected',
			21	 => 'Contact Phone Remove',
			22	 => 'Contact Email Remove',
			100	 => 'Agreement Uploaded',
			201	 => 'Voter (Front) Uploaded',
			202	 => 'Voter (Back)  Uploaded',
			301	 => 'Aadhaar (Front) Uploaded',
			302	 => 'Aadhaar (Back) Uploaded',
			401	 => 'Pan (Front) Uploaded',
			402	 => 'Pan (Back) Uploaded',
			501	 => 'Licence (Front) Uploaded',
			502	 => 'Licence (Back) Uploaded',
			600	 => 'Memorandum Uploaded',
			700	 => 'Police Verification Certificate Uploaded',
		];
		if ($eventType > 0)
		{
			return $eventlist[$eventType];
		}
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
		$sql			 = "SELECT contact_log.*,
                (CASE contact_log.clg_user_type
                    WHEN 10 THEN 'System'
                    WHEN 4 THEN CONCAT(admins.adm_fname,' ',admins.adm_lname)
                    WHEN 2 THEN vendors.vnd_name
                    ELSE '' END
                ) as name,
                (CASE contact_log.clg_user_type WHEN 1 then 'Consumers'
                    WHEN 2 THEN 'Vendor'
                    WHEN 3 then 'Driver'
                    WHEN 4 then 'Admin'
                    WHEN 5 then 'Agent'
                    WHEN 10 then 'System'
                    ELSE '' END) as type
                FROM `contact_log`
                INNER JOIN `contact_profile` ON contact_profile.cr_contact_id=contact_log.clg_ctt_id
				INNER JOIN `vendors` ON vendors.vnd_id=contact_profile.cr_is_vendor AND cr_status = 1
                LEFT JOIN `admins` ON admins.adm_id=contact_log.clg_user_ref_id
                WHERE vendors.vnd_id IN (select v3.vnd_id FROM vendors v1 INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code 
				INNER JOIN vendors v3 ON v3.vnd_ref_code = v2.vnd_id  WHERE v1.vnd_id = '$vndId') AND vendors.vnd_id = vendors.vnd_ref_code AND contact_log.clg_event_id BETWEEN 5 AND 22";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['clg_desc', 'clg_desc', 'clg_created', 'name', 'type'],
				'defaultOrder'	 => 'clg_created  DESC'], 'pagination'	 => ['pageSize' => $pageSize],
		]);
		return $dataprovider;
	}

	public function getByDriverId($drvId, $viewType = 0)
	{
		$pageSize = 25;
		if ($viewType == 1)
		{
			$pageSize = 20;
		}
		$sql			 = "SELECT contact_log.*,
                (CASE contact_log.clg_user_type
                    WHEN 10 THEN 'System'
                    WHEN 4 THEN CONCAT(admins.adm_fname,' ',admins.adm_lname)
                    WHEN 2 THEN drivers.drv_name
                    ELSE '' END
                ) as name,
                (CASE contact_log.clg_user_type WHEN 1 then 'Consumers'
                    WHEN 2 THEN 'Vendor'
                    WHEN 3 then 'Driver'
                    WHEN 4 then 'Admin'
                    WHEN 5 then 'Agent'
                    WHEN 10 then 'System'
                    ELSE '' END) as type
                FROM `contact_log`
                INNER JOIN `contact_profile` ON contact_profile.cr_contact_id=contact_log.clg_ctt_id
				INNER JOIN `drivers` ON drivers.drv_id=contact_profile.cr_is_driver AND cr_status = 1
                LEFT JOIN `admins` ON admins.adm_id=contact_log.clg_user_ref_id
                WHERE drivers.drv_id IN (select d3.drv_id FROM drivers d1 INNER JOIN drivers d2 ON d2.drv_id = d1.drv_ref_code 
				INNER JOIN drivers d3 ON d3.drv_ref_code = d2.drv_id  WHERE d1.drv_id = '$drvId') AND drivers.drv_id = drivers.drv_ref_code AND contact_log.clg_event_id BETWEEN 5 AND 22";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['clg_desc', 'clg_desc', 'clg_created', 'name', 'type'],
				'defaultOrder'	 => 'clg_created  DESC'], 'pagination'	 => ['pageSize' => $pageSize],
		]);
		return $dataprovider;
	}

}

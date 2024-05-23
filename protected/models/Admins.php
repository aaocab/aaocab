<?php

/**
 * This is the model class for table "admins".
 *
 * The followings are the available columns in table 'admins':
 * @property string $adm_id
 * @property integer $adm_pru_id
 * @property string $adm_fname
 * @property string $adm_lname
 * @property string $adm_user
 * @property string $adm_email
 * @property string $adm_passwd
 * @property integer $adm_attempt
 * @property integer $adm_chk_local
 * @property string $adm_last_login
 * @property string $adm_created_at
 * @property string $adm_last_password_change
 * @property integer $adm_active
 * @property string $adm_log
 * @property string $adm_teams
 * @property string $adm_region
 * @property string $adm_booking_type
 * @property integer $adm_role
 * @property string $adm_dailer_username
 * @property string $adm_dailer_password
 * @property integer $adm_opps_app_access
 * @property string $adm_phone
 * The followings are the available model relations:
 * @property AdminProfiles $admProfiles
 * @property BookingLog[] $bookingLogs
 * @property BookingTemp[] $bookingTemps
 * @property LeadLog[] $leadLogs
 * @property Ratings[] $ratings
 * @property Ratings[] $ratings1
 */
class Admins extends BaseActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public $_identity;
	public $adm_department_name	 = '', $adm_category_name	 = '';
	public $adm_mapping_id		 = 0;
	public $tea_name, $dpt_name, $cat_name, $adp_team_leader_id, $adp_hiring_date, $adp_emp_code, $des_name, $teamId, $isActive, $isAjax;

	public function tableName()
	{
		return 'admins';
	}

	public $repeat_password, $new_password, $old_password, $adm_passwd1;
	public $admActive, $from_date, $to_date;
	public $teamType	 = ['1' => 'Backoffice ', '2' => 'Field team'];
	public $regionArr	 = [
		1	 => 'North',
		2	 => 'West',
		3	 => 'Central',
		4	 => 'South',
		5	 => 'East',
		6	 => 'North East',
		7	 => 'South Kerala'
	];

	public function defaultScope()
	{
		$ta	 = $this->getTableAlias(true, false);
		$arr = array(
			'condition' => $ta . "." . "adm_active > 0",
		);
		return $arr;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{

		return array(
			array('adm_fname, adm_lname, adm_user, adm_email, adm_passwd1,adm_attempt', 'required', 'on' => 'insert'),
			array('adm_attempt', 'required'),
			array('adm_active', 'numerical', 'integerOnly' => true),
			array('adm_passwd1', 'length', 'min' => 3),
			array('adm_user', 'unique', 'on' => 'insert,update'),
			array('adm_fname, adm_lname, adm_user, adm_email', 'required', 'on' => 'update'),
			array('adm_fname, adm_lname, adm_user, adm_email, adm_passwd', 'length', 'max' => 200),
			array('adm_email', 'email', 'message' => 'Please enter valid email address', 'checkMX' => true),
			array('new_password', 'validatePassword', 'on' => 'checkPassword'),
			array('repeat_password, new_password, old_password', 'required', 'on' => 'change'),
			array('adm_active', 'required', 'on' => 'del'),
			array('gozen', 'unsafe'),
			array('adm_id, adm_pru_id, adm_fname, adm_lname, adm_user, adm_email, adm_passwd, adm_attempt, adm_chk_local, adm_last_login, adm_created_at, adm_active, adm_log, adm_last_password_change,adm_teams,adm_role,adm_region,adm_dailer_username,adm_dailer_password,adm_opps_app_access,adm_phone,adm_booking_type', 'safe'),
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
			'bookingLogs'	 => array(self::HAS_MANY, 'BookingLog', 'blg_admin_id'),
			'bookingTemps'	 => array(self::HAS_MANY, 'BookingTemp', 'bkg_follow_up_by'),
			'leadLogs'		 => array(self::HAS_MANY, 'LeadLog', 'blg_admin_id'),
			'ratings'		 => array(self::HAS_MANY, 'Ratings', 'rtg_review_approved_by'),
			'ratings1'		 => array(self::HAS_MANY, 'Ratings', 'rtg_csr_id'),
			'admuserLogs'	 => array(self::HAS_MANY, 'AdminLog', 'adm_log_user'),
			'admProfiles'	 => array(self::HAS_ONE, 'AdminProfiles', 'adp_adm_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'adm_id'				 => 'ID',
			'adm_pru_id'			 => 'Admin Chat Id',
			'adm_fname'				 => 'First Name',
			'adm_lname'				 => 'Last Name',
			'adm_user'				 => 'User ID',
			'adm_email'				 => 'Email',
			'adm_phone'				 => 'Phone',
			'adm_passwd'			 => 'Password',
			'adm_passwd1'			 => 'Password',
			'adm_attempt'			 => 'Login Role',
			'adm_chk_local'			 => 'Check Local',
			'adm_last_login'		 => 'Last Login',
			'adm_created_at'		 => 'Created At',
			'adm_log'				 => 'Admin Log',
			'adm_active'			 => 'Active',
			'adm_teams'				 => 'Teams',
			'adm_role'				 => 'Role',
			'adm_region'			 => 'Region',
			'adm_dailer_username'	 => 'Username',
			'adm_dailer_password'	 => 'Password',
			'adm_dailer_password1'	 => 'Password',
			'adm_opps_app_access'	 => 'Ops App Access',
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
		$criteria			 = new CDbCriteria;
		$criteria->select	 = ['t.*,tea_name,dpt_name,cat_name,adp_team_leader_id,adp_hiring_date,adp_emp_code,des_name'];
		$criteria->compare('adm_id', $this->adm_id, true);
		$criteria->compare('adm_fname', $this->adm_fname, true);
		$criteria->compare('adm_lname', $this->adm_lname, true);
		$criteria->compare('adm_user', $this->adm_user, true);
		$criteria->compare('adm_email', $this->adm_email, true);
		$criteria->compare('adm_phone', $this->adm_phone, true);
		$criteria->compare('adm_passwd', $this->adm_passwd, true);
		$criteria->compare('adm_attempt', $this->adm_attempt);
		$criteria->compare('adm_chk_local', $this->adm_chk_local);
		$criteria->compare('adm_last_login', $this->adm_last_login, true);
		$criteria->compare('adm_created_at', $this->adm_created_at, true);
		if ($this->isAjax == 1)
		{
			$criteria->compare('adm_active', $this->adm_active, true);
		}
		if ($this->isActive == 1)
		{
			$criteria->compare('adm_active', $this->isActive, true);
		}
		if ($this->isActive == 0)
		{
			$criteria->compare('adm_active', array(1, 2), true);
		}
		$criteria->compare('adm_log', $this->adm_log, true);
		$criteria->compare('adm_teams', $this->adm_teams, true);
		$criteria->compare('adm_dailer_username', $this->adm_teams, true);
		$criteria->compare('adm_dailer_password', $this->adm_teams, true);
		if ($this->adp_emp_code != null)
		{
			$criteria->addCondition("adp_emp_code like '%$this->adp_emp_code%' ");
		}
		if ($this->tea_name != null)
		{
			$criteria->addCondition("tea_name like '%$this->tea_name%' ");
		}

		if ($this->teamId != null)
		{
			$criteria->addInCondition('tea_id', $this->teamId);
		}

		if ($this->adp_team_leader_id != null)
		{
			$criteria->addInCondition('adp_team_leader_id', $this->adp_team_leader_id);
		}
		if ($this->dpt_name != null)
		{
			$criteria->addCondition("dpt_name like '%$this->dpt_name%' ");
		}
		if ($this->cat_name != null)
		{
			$criteria->addCondition("cat_name like '%$this->cat_name%' ");
		}
		$criteria->join		 = "LEFT JOIN `admin_profiles`  ON admin_profiles.adp_adm_id = adm_id
								LEFT JOIN
						        (
									SELECT 
									admin_profiles.adp_adm_id,
									JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtWeight'))) AS cdtWeight,
									JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtId'))) AS cdtId
									FROM admin_profiles
									JOIN pseudo_rows
									WHERE 1
									HAVING cdtId IS NOT NULL
						        ) temp ON temp.adp_adm_id=admin_profiles.adp_adm_id
					            LEFT JOIN `cat_depart_team_map` ON temp.cdtId=cat_depart_team_map.cdt_id
								LEFT JOIN teams tea ON cat_depart_team_map.cdt_tea_id = tea.tea_id
								LEFT JOIN departments ON cdt_dpt_id = departments.dpt_id
								LEFT JOIN designation ON des_id  = admin_profiles.adp_designation_id
								LEFT JOIN categories ON cdt_cat_id = categories.cat_id";
		$criteria->together	 = true;
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Admins the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function beforeSave()
	{
		parent::beforeSave();
		if ($this->adm_passwd1 != "")
		{
			$this->adm_passwd = CPasswordHelper::hashPassword($this->adm_passwd1, 10);
		}
		return true;
	}

	public function getName()
	{
		return $this->adm_fname . " " . $this->adm_lname . " ({$this->adm_user})";
	}

	/**
	 * Returns Admin model by its username
	 *
	 * @param string $email
	 * @access public
	 * @return Admins
	 */
	public function findByEmail($email)
	{
		return self::model()->findByAttributes(array('adm_user' => $email));
	}

	public function findById($id)
	{
		return self::model()->findByAttributes(array('adm_id' => $id));
	}

	public function findNameList()
	{
		$adminModels = $this->findAll();
		$adminList	 = array();
		foreach ($adminModels as $admin)
		{
			$adminList[$admin->adm_id] = $admin->adm_fname . " " . $admin->adm_lname;
		}
		return $adminList;
		//return CHtml::listData($admin, 'adm_id', 'adm_fname');
	}

	public function findAllActive()
	{
		return self::model()->findAll('adm_active=:status', array('status' => 1));
	}

	public function fetchList()
	{
		$criteria		 = new CDbCriteria;
		$criteria->compare('adm_fname', $this->adm_fname, true);
		$criteria->compare('adm_active', 1);
		$dataProvider	 = new CActiveDataProvider(Admins::model()->together(), ['criteria'	 => $criteria, 'sort'		 => array(
				'attributes'	 => ['adm_fname', 'adm_lname'],
				'defaultOrder'	 => 'adm_fname ASC'
			),]);
		return $dataProvider;
	}

	public function fetchListByActivity()
	{
//        $criteria = new CDbCriteria;
//        $criteria->select = ['adm_fname', 'adm_lname', 'adm_log_in_time', 'if(max(admlog.adm_log_in_time) >= DATE_SUB(NOW(), INTERVAL 960 MINUTE),1,0 ) as admActive '];
//        $criteria->compare('adm_fname', $this->adm_fname, true);
//        $criteria->where = 'JOIN admin_log admlog ON admlog.adm_log_user = adm_id';
//        $criteria->group = 'adm_log_user';
		$where	 = '';
		$name	 = $this->adm_fname;
		if ($name != '')
		{

			$where = "WHERE adm.adm_fname LIKE '%$name%'";
		}

		$sql = "select adm_id,adm_fname, adm_lname, if(t1.bkg_assigned_to > 0,1,0) as admActive from admins adm LEFT JOIN (
					select DISTINCT booking_temp.bkg_assigned_to FROM lead_log
					INNER JOIN booking_temp ON lead_log.blg_booking_id=booking_temp.bkg_id
					where blg_desc like '%Lead assigned%' AND lead_log.blg_created>DATE_SUB(NOW(), INTERVAL 6 HOUR))
                t1 ON t1.bkg_assigned_to = adm.adm_id $where ";

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc");
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['adm_fname', 'adm_lname'],
				'defaultOrder'	 => 'admActive DESC,adm_fname ASC'], 'pagination'	 => ['pageSize' => 10],
		]);

		return $dataprovider;
	}

	public function getJSON()
	{
		$adminList	 = $this->getAdminList();
		$JSONList	 = [];
		foreach ($adminList as $key => $val)
		{
			$JSONList[] = array("id" => $key, "text" => $val);
		}
		$data = CJSON::encode($JSONList);
		return $data;
	}

	public function adminLog($oldData, $newData)
	{
		if ($oldData)
		{
			$getDifference = array_diff_assoc($oldData, $newData);
			if (count($getDifference) > 0)
			{
				$remark	 = $this->adm_log;
				$dt		 = date('Y-m-d H:i:s');
				$user	 = Yii::app()->user->getId();
				if (is_string($remark))
				{
					$newcomm = CJSON::decode($remark);
				}
				else if (is_array($remark))
				{
					$newcomm = $remark;
				}
				if ($newcomm == false)
				{
					$newcomm = array();
				}
				while (count($newcomm) >= 50)
				{
					array_pop($newcomm);
				}
				array_unshift($newcomm, array(0 => $user, 1 => $dt, 2 => $getDifference));
				$adm_log = CJSON::encode($newcomm);
				return $adm_log;
			}
		}
	}

	public static function getLog($id)
	{
		$qry	 = "select `adm_log` from admins where adm_id = " . $id;
		$logList = DBUtil::queryRow($qry);
		return $logList;
	}

	public function getAll()
	{
		$criteria		 = new CDbCriteria;
		$criteria->order = "adm_fname";
		return $this->resetScope()->findAll($criteria);
	}

	public function getAdminList()
	{
		$adminModels = Admins::model()->getAll();
		$arrSkill	 = array();
		foreach ($adminModels as $sklModel)
		{
			$arrSkill[$sklModel->adm_id] = $sklModel->adm_fname . " " . $sklModel->adm_lname;
		}
		return $arrSkill;
	}

	public function getByName($name = "")
	{
		$where = '';

		if ($name != '')
		{

			$where = "AND gozen LIKE '%$name%'";
		}

		$sql = "select adm_id,gozen FROM admins WHERE 1 $where AND adm_active = 1";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public function getById($id = "", $cond = 0)
	{
		if ($id != '')
		{
			$where = "WHERE adm_id = $id";
		}
		if ($cond == 0)
		{
			$where .= " AND adm_active = 1";
		}
		$sql = "select adm_id,gozen FROM admins $where ";
		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	public static function getDashbordSource()
	{
		$dashbordSource = [
			201	 => 'Bookings with missing drivers (36 Hours)',
			202	 => 'Bookings still unassigned (next 48 Hours)',
			203	 => 'Low rating vendor for upcoming trip',
			204	 => 'Low rating driver for upcoming trip',
			205	 => 'Bookings need Accounts attention',
			206	 => 'Unverified bookings',
			207	 => 'Cars missing docs',
			208	 => 'Drivers missing docs',
			209	 => 'Active escalations',
			210	 => 'Vendors with doc missing (in system)',
			211	 => 'Vendors with bank details and/or PAN missing (in system)',
			212	 => 'Routes will toll & state missing (in system)',
			213	 => 'Cities with zones missing or incorrect classification (in system)',
			215	 => 'Undocumented cars in next 48 hours (Not Commercial)',
			216	 => 'Undocumented cars in next 48 hours (Commercial Verified, but not approved)',
			217	 => 'Bookings created > 2 days ago and not assigned still',
			218	 => 'Bookings not picked up by any vendor despite floating for 24hours',
			219	 => 'Bookings have  "Reconfirm Pending" in next 36hours',
			220	 => 'Not profitable bookings in system',
			221	 => 'Higher Vendor Amount',
			224	 => 'Delegated Assignment',
			225	 => 'Bookings in manual assignment',
			226	 => 'Bookings in critical assignment',
			227	 => 'SOS Alert',
			228	 => 'Demand Supply Misfired',
			229	 => 'Pickup Overdue',
			230	 => 'Completion Overdue',
			231	 => 'Refund Aproval Pending',
			232	 => 'Payment Block Vendors',
			234	 => 'Uncommon Route'
		];
		return $dashbordSource;
	}

	public static function getCancellationReason()
	{
		$source = [
			1	 => 'Customer',
			4	 => 'Admin',
			5	 => 'Agent',
			10	 => 'System',
		];
		return $source;
	}

	public function getSourceById($id)
	{
		$list = $this->getDashbordSource();
		return $list[$id];
	}

	public function getNameById($userID)
	{
		$criteria			 = new CDbCriteria;
		$criteria->select	 = 'adm_user'; // select fields which you want in output
		$criteria->compare('adm_id', $userID);
		return $this->find($criteria);
	}

	/** @deprecated */
	public static function csrPerformance($fromDate = '', $toDate = '')
	{
		if ($fromDate != '' && $toDate != '')
		{
			$sqlDate = " date(blg_created) BETWEEN '" . $fromDate . "' AND '" . $toDate . "'";
		}
		$select = "SELECT adm_fname, adm_lname, uniqueUnverified, totalUnverified,
					IFNULL(totalUnverifiedFollowup, 0) AS totalUnverifiedFollowup1, uniqueLead,
					newLeadFollow, oldLeadFollow, totalLead,
					totalDiscount, noOfDiscount,
					IFNULL(totalLeadFollowup, 0) AS totalLeadFollowup1, (IFNULL(totalUnverifiedFollowup, 0) + IFNULL(totalLeadFollowup, 0)) AS totalFollowup,
					cntBooking,activeBooking, total_amount, gozo_amount, service_tax, served_gozo_amount,unserved_gozo_amount,
					(served_gozo_amount + unserved_gozo_amount) as expected_gozo_amount,
					served_count,
					(gozo_amount - service_tax) AS net_gozo_amount, advance_count, unserved_advance_count,
					ROUND(((gozo_amount - service_tax) * 100) / total_amount) marginPercent, new as new1, verify, newLead,
					round(newLead * 100 / newLeadFollow, 2) as nlConvPer, oldLead, round(oldLead * 100 / oldLeadFollow, 2) as olConvPer, cancelled";

		$sql			 = "
				FROM   admins
				LEFT JOIN (SELECT   blg_user_id, COUNT(DISTINCT booking.bkg_id) AS uniqueUnverified,
							COUNT(DISTINCT booking.bkg_id, DATE(blg_created)) AS totalUnverified,
							SUM(IF(blg_event_id IN (29, 26), 1, 0)) AS totalUnverifiedFollowup
							FROM `booking_log` INNER JOIN booking ON booking.bkg_id = booking_log.blg_booking_id
							WHERE blg_user_type = 4 AND $sqlDate AND blg_event_id IN (29, 26) AND blg_booking_status IN (1, 13)
							GROUP BY blg_user_id
						) a ON admins.adm_id = a.blg_user_id
				LEFT JOIN (SELECT   blg_admin_id, COUNT(DISTINCT blg_booking_id) AS uniqueLead,
								COUNT(DISTINCT IF(DATE(lead_log.blg_created) = DATE(booking_temp.bkg_create_date), bkg_id, null)) as newLeadFollow,
								COUNT(DISTINCT IF(DATE(lead_log.blg_created) <> DATE(booking_temp.bkg_create_date), bkg_id, null)) as oldLeadFollow,
								COUNT(DISTINCT blg_booking_id, DATE(blg_created)) AS totalLead, COUNT(*) AS totalLeadFollowup
							FROM     `lead_log`
							INNER JOIN booking_temp ON lead_log.blg_booking_id = booking_temp.bkg_id AND lead_log.blg_desc NOT LIKE 'Lead assigned %'
								AND lead_log.blg_desc NOT LIKE 'Lead Locked %' AND lead_log.blg_desc NOT LIKE '%Duplicate%'
								AND lead_log.blg_desc NOT LIKE '%Invalid%' AND booking_temp.bkg_follow_up_status <> 7 AND $sqlDate
							INNER JOIN admins ON admins.adm_id = lead_log.blg_admin_id
							WHERE $sqlDate
							GROUP BY blg_admin_id) b ON adm_id = blg_admin_id
				LEFT JOIN (SELECT   blg_user_id, COUNT(*) AS cntBooking,
								SUM(IF(a.bkg_status<>9,1,0)) AS activeBooking,
								SUM(IF(a.bkg_status <> 9, biv.bkg_total_amount-biv.bkg_service_tax, 0)) AS total_amount,
								SUM(IF(a.bkg_status <> 9, biv.bkg_total_amount - vendorAmount, 0)) AS gozo_amount,
								SUM(IF(a.bkg_status <> 9 AND a.bkg_pickup_date<=NOW(), biv.bkg_total_amount-vendorAmount-biv.bkg_service_tax, 0)) AS served_gozo_amount,
								SUM(IF(a.bkg_status <> 9 AND a.bkg_pickup_date>NOW() AND biv.bkg_advance_amount>0, biv.bkg_total_amount-vendorAmount-biv.bkg_service_tax, 0)) AS unserved_gozo_amount,
								SUM(IF(a.bkg_status <> 9 AND a.bkg_pickup_date<NOW(), 1, 0)) AS served_count,
								SUM(IF(a.bkg_advance_amount=0, biv.bkg_discount_amount,0)) AS totalDiscount,
								SUM(IF(a.bkg_advance_amount=0 AND biv.bkg_discount_amount>0,1,0)) AS noOfDiscount,
								COUNT(DISTINCT IF(a.bkg_advance_amount>0,a.bkg_id,null)) as advance_count,
								COUNT(DISTINCT IF(a.bkg_advance_amount>0 AND a.bkg_pickup_date>NOW(),a.bkg_id,null)) as unserved_advance_count,
								SUM(IF(a.bkg_status <> 9,biv.bkg_service_tax,0)) AS service_tax,
								SUM(IF(blg_event_id = 3, 1, 0)) AS new, SUM(IF(blg_event_id = 5, 1, 0)) AS verify,
								SUM(IF(blg_event_id = 25 AND DATE(created) = DATE(booking_temp.bkg_create_date), 1, 0)) AS newLead,
								SUM(IF(blg_event_id = 25 AND DATE(created) <> DATE(booking_temp.bkg_create_date), 1, 0)) AS oldLead,
								SUM(IF(a.bkg_status = 9, 1, 0)) AS cancelled
							FROM     booking_log
								INNER JOIN booking ON booking.bkg_id = booking_log.blg_booking_id
								INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
							INNER JOIN
								(SELECT   bkg_id, bkg_status, biv.bkg_total_amount,IF(bcb_trip_type=0,bcb_vendor_amount,bkg_vendor_amount) as vendorAmount,
											bkg_pickup_date, biv.bkg_gozo_amount, max(blg_created) AS created, biv.bkg_advance_amount,biv.bkg_service_tax,biv.bkg_discount_amount,
												CASE
													WHEN FIND_IN_SET(25, GROUP_CONCAT(blg_event_id))
														THEN 25
													WHEN FIND_IN_SET(3, GROUP_CONCAT(blg_event_id))
														THEN 3
													ELSE 5
													END as eventId
										FROM     `booking_log`
										INNER JOIN booking ON booking.bkg_id = booking_log.blg_booking_id AND blg_user_type = 4 AND blg_event_id IN (3, 5, 25) AND bkg_agent_id IS NULL AND booking_log.blg_user_type = 4 AND $sqlDate AND bkg_status IN (2, 3, 5, 6, 7, 9)
										INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=bkg_id
										INNER JOIN booking_cab ON booking.bkg_bcb_id=bcb_id
										WHERE 1
										GROUP BY bkg_id
									) a ON booking_log.blg_booking_id = a.bkg_id AND blg_event_id=eventId AND blg_user_type = 4 AND blg_event_id IN (3, 5, 25)
							LEFT JOIN booking_temp ON a.bkg_id = booking_temp.bkg_ref_booking_id
							GROUP BY blg_user_id
						) c ON c.blg_user_id = adm_id
				WHERE  totalUnverified IS NOT NULL OR totalLead IS NOT NULL OR cntBooking IS NOT NULL";
		$count			 = DBUtil::queryScalar("SELECT COUNT(1) $sql", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($select . $sql, ['totalItemCount' => $count, 'pagination'	 => ['pageSize' => 50],
			'sort'			 => ['attributes'	 => ['adm_fname', 'adm_lname', 'uniqueUnverified', 'totalUnverified', 'totalUnverifiedFollowup1', 'uniqueLead', 'newLeadFollow', 'oldLeadFollow', 'totalLead',
					'totalLeadFollowup1', 'totalFollowup', 'cntBooking', 'total_amount', 'gozo_amount', 'service_tax', 'served_gozo_amount', 'expected_gozo_amount', 'served_count',
					'marginPercent', 'new1', 'verify', 'newLead', 'oldLead', 'cancelled', 'nlConvPer', 'olConvPer', 'advance_count', 'activeBooking', 'noOfDiscount'],
				'defaultOrder'	 => 'advance_count DESC'
			],
		]);
		return $dataprovider;
	}

	public function getAuthItemList()
	{
		$adminModels = Admins::model()->getAll();
		$arrSkill	 = array();
		foreach ($adminModels as $sklModel)
		{
			$arrSkill[$sklModel->adm_id] = $sklModel->adm_fname . " " . $sklModel->adm_lname;
		}
		return $arrSkill;
	}

	public static function getRolesList()
	{


		$typelist = array(
			"4 - OperationManager"		 => "4 - OperationManager",
			"1 - CSR"					 => "1 - CSR",
			"2 - Business Development"	 => "2 - Business Development",
			"2 - MarketingManager"		 => "2 - MarketingManager",
			"8 - AccountManager"		 => "8 - AccountManager",
			"10 - Vendor Manager"		 => "10 - Vendor Manager",
			"6 - Developer"				 => "6 - Developer",
			"9 - Field Executive"		 => "9 - Field Executive",
			"2 - CSRTeamLeader"			 => "2 - CSRTeamLeader",
			"5 - RegionalHeads"			 => "5 - RegionalHeads",
			//"LeadFresher"				 => "Lead Fresher",
			//"LeadSenior"				 => "Lead Senior",
			//"LeadCaller"				 => "Lead Caller",
			"01 - BD (Field team)"		 => "01 - BD (Field team)",
		);
		asort($typelist);

		return $typelist;
	}

	public function authorizeAdmin($token)
	{
		$appToken1 = AppTokens::model()->find('apt_token_id = :token and apt_status = 1', array('token' => $token));

		if (empty($appToken1))
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	public function updateLastLocation($data)
	{
		$adminId	 = UserInfo::getUserId();
		$adminModel	 = $this->findById($adminId);
		$adminName	 = $adminModel['adm_fname'] . ' ' . $adminModel['adm_lname'];
		$dir		 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'OpsAppTracking';
		if (!is_dir($dir))
		{
			mkdir($dir);
		}
		$dirTrip = $dir . DIRECTORY_SEPARATOR . $adminId;
		if (!is_dir($dirTrip))
		{
			mkdir($dirTrip);
		}
		$date		 = date("Y-d-m H:i:s");
		$dataResult	 = [];
		$transaction = Filter::beginTransaction();
		try
		{
			if ($data != '')
			{
				$updateRows	 = [];
				$file		 = $dirTrip . "/" . $adminName . ".csv";
				if (!file_exists($file))
				{
					$handle = fopen($file, 'w');
					fputcsv($handle, array("Admin ID", "Device_Id", "Latitude", "Longitude", "Received_On",));

					fputcsv($handle, array($adminId, $data['deviceId'], $data['lat'], $data['long'], $date));
					$updateRows[] = $adminId;

					fclose($handle);
				}
				else
				{
					$handle			 = fopen($file, 'a');
					fputcsv($handle, array($adminId, $data['deviceId'], $data['lat'], $data['long'], $date));
					$updateRows[]	 = $adminId;

					fclose($handle);
				}
				$success = true;
				$message = 'Last Row Id Inserted =' . $adminId;
			}
			Filter::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			Filter::rollbackTransaction($transaction);
			$success = false;
			$message = $ex->getMessage();
			Filter::createLog("Errors.\n\t\t" . $message, CLogger::LEVEL_ERROR);
		}

		$success = ($success == true) ? true : false;
		return $success;
	}

	public function teamleadList()
	{
		$sql		 = "SELECT adm_id,adm_fname,adm_lname,GROUP_CONCAT(itemname) itemname FROM admins
		LEFT JOIN AuthAssignment ON  userid=adm_id
		WHERE AuthAssignment.itemname IN ('2 - CSRTeamLeader','2 - Business Development',
		'2 - MarketingManager','4 - OperationManager','5 - ReginalHeads','6 - Developer',
		'7 - Admin','SuperAdmin') AND adm_active=1 
		Group BY adm_id";
		$adminModels = DBUtil::queryAll($sql);
		$adminList	 = array();
		foreach ($adminModels as $admin)
		{
			$adminList[$admin['adm_id']] = $admin['adm_fname'] . " " . $admin['adm_lname'];
		}
		return $adminList;
	}

	public function employeesList($type = 0)
	{
		$sql		 = "SELECT adm_id,adm_fname,adm_lname,adp_emp_code FROM admins
						LEFT JOIN admin_profiles  ON adm_id = adp_adm_id
						WHERE  adm_active=1 
						Group BY adm_id";
		$adminModels = DBUtil::query($sql, DBUtil::SDB());
		$adminList	 = array();
		foreach ($adminModels as $admin)
		{
			$addStr						 = $type == 1 ? "(" . $admin['adp_emp_code'] . ")" : "";
			$adminList[$admin['adm_id']] = $admin['adm_fname'] . " " . $admin['adm_lname'] . $addStr;
		}
		return $adminList;
	}

	public function getFullNameById($userID)
	{
		$adminModel	 = $this->findByPk($userID);
		$name		 = $adminModel->adm_fname . ' ' . $adminModel->adm_lname;
		return $name;
	}

	public function getCsrNotificationList()
	{
		$sql	 = "SELECT ad.adm_id, ad.adm_fname, ad.adm_lname 
				FROM admins ad 
				INNER JOIN app_tokens  apt ON apt.apt_user_id=ad.adm_id
				WHERE ad.adm_active=1 AND ad.adm_opps_app_access = 1 AND apt.apt_user_type = 6 AND apt.apt_status=1 Group BY ad.adm_id";
		$record	 = DBUtil::queryAll($sql);
		return $record;
	}

	/**
	 * This function is used for fetching the admin users
	 * @return CDDataReader
	 */
	public static function getAllByCDT()
	{

		$sql = "SELECT adm_id, adm_pru_id, adm_fname, adm_lname, adm_user,adm_email,adm_passwd,dpt_chat_server_id, cdt_chat_server_dpt_id
						FROM admins adm
						INNER JOIN admin_profiles adp ON adp.adp_adm_id = adm.adm_id
						INNER JOIN
						(
							SELECT 
							admin_profiles.adp_adm_id,
							JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtWeight'))) AS cdtWeight,
							JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtId'))) AS cdtId
							FROM admin_profiles
							JOIN pseudo_rows
							WHERE 1 
							HAVING cdtId IS NOT NULL
						) temp ON temp.adp_adm_id=adp.adp_adm_id
						INNER JOIN `cat_depart_team_map` cdt  ON temp.cdtId=cdt.cdt_id
						INNER JOIN departments dpt ON dpt.dpt_id = cdt.cdt_dpt_id
						WHERE adm_active = 1 AND adm_pru_id IS NULL AND dpt_status = 1";
		return DBUtil::query($sql);
	}

	/**
	 * This function is used for updating the admin table
	 * @param type $prosodyId
	 * @param type $dbId
	 */
	public static function updateProsodyId($dbId, $prosodyId)
	{
		$sql	 = " UPDATE admins SET adm_pru_id = $prosodyId WHERE adm_id = $dbId AND adm_active = 1";
		$result	 = DBUtil::command($sql)->execute();
	}

	/**
	 * This function is used for getting the prosody Id of the admin
	 * @param type $entityId
	 * @return integer
	 */
	public function getProsodyId($entityId)
	{
		$model = self::model()->findByPk($entityId);
		return $model->adm_pru_id;
	}

	public function getCsrNotificationListByRegionId($id)
	{
		$sql		 = "SELECT ad.adm_id, ad.adm_fname, ad.adm_lname 
				FROM admins ad 
				INNER JOIN app_tokens  apt ON apt.apt_user_id=ad.adm_id
				WHERE ad.adm_active=1 AND ad.adm_opps_app_access = 1 AND apt.apt_user_type = 6 AND apt.apt_status=1 AND ad.adm_region = $id Group BY ad.adm_id";
		$recordSet	 = DBUtil::queryAll($sql);
		return $recordSet;
	}

	public static function getProfile($admId, $sessionId)
	{
		$returnSet = new ReturnSet();
		try
		{

			if (empty($admId))
			{
				$returnSet->setMessage("Admin id not provided");
				goto skipAll;
			}
			$model = \Admins::model()->findByPk($admId);
			if (empty($model))
			{
				throw new Exception("No data found for the admin id", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$response = new \Stub\common\Admin();

			$response->setModel($model, $sessionId);
			$returnSet->setStatus(true);
			$returnSet->setData($response);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}

		skipAll:
		return $returnSet;
	}

	/**
	 * This function is used for getting the profile data of the admin
	 * @param type $admId
	 * @return Array
	 */
	public static function getProfileData($admId)
	{

		$sql = "SELECT `adm_id`, adm.adm_fname, adm.adm_lname, adm.adm_email, adm.adm_teams,cdt.cdt_id, teams.tea_id, teams.tea_name, departments.dpt_name, categories.cat_name
					FROM   admins adm
					LEFT JOIN `admin_profiles` adp ON adp.adp_adm_id = adm.adm_id					
					LEFT JOIN
					(
						SELECT 
						admin_profiles.adp_adm_id,
						JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtWeight'))) AS cdtWeight,
						JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtId'))) AS cdtId
						FROM admin_profiles
						JOIN pseudo_rows
						WHERE 1 AND  adp_adm_id = :admId
						HAVING cdtId IS NOT NULL
					) temp ON temp.adp_adm_id=adp.adp_adm_id
					LEFT JOIN `cat_depart_team_map` cdt  ON temp.cdtId=cdt.cdt_id
					LEFT JOIN teams ON cdt.cdt_tea_id = teams.tea_id
					LEFT JOIN departments ON cdt_dpt_id = departments.dpt_id
					LEFT JOIN categories ON cdt_cat_id = categories.cat_id
					WHERE  adm_id = :admId
					AND
					( 
						(tea_start_time IS NULL AND tea_stop_time IS NULL)
							OR           
						(tea_start_time < tea_stop_time AND CURRENT_TIME() BETWEEN tea_start_time AND tea_stop_time)
							OR
						(tea_stop_time < tea_start_time AND CURRENT_TIME() < tea_start_time AND CURRENT_TIME() < tea_stop_time)
							OR
						(tea_stop_time < tea_start_time AND CURRENT_TIME() > tea_start_time)
					)
					ORDER BY temp.cdtWeight ASC";
		return DBUtil::queryAll($sql, DBUtil::SDB(), ['admId' => $admId]);
	}

	/**
	 * This function is used for getting the Team Id of the admin
	 * @param type $admId
	 * @return integer
	 */
	public static function getTeamid($admId)
	{
		$params	 = ['admId' => $admId];
		$sql	 = "SELECT  teams.tea_id 
					FROM   admins adm
					LEFT JOIN `admin_profiles` adp ON adp.adp_adm_id = adm.adm_id
					LEFT JOIN
					(
						SELECT 
						admin_profiles.adp_adm_id,
						JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtWeight'))) AS cdtWeight,
						JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtId'))) AS cdtId
						FROM admin_profiles
						JOIN pseudo_rows
						WHERE 1 AND  adp_adm_id = :admId
						HAVING cdtId IS NOT NULL
					) temp ON temp.adp_adm_id=adp.adp_adm_id
					LEFT JOIN `cat_depart_team_map` cdt  ON temp.cdtId=cdt.cdt_id
					LEFT JOIN teams ON cdt.cdt_tea_id = teams.tea_id								   
					WHERE  adm_id = :admId
					AND
					( 
						(tea_start_time IS NULL AND tea_stop_time IS NULL)
							OR           
						(tea_start_time < tea_stop_time AND CURRENT_TIME() BETWEEN tea_start_time AND tea_stop_time)
							OR
						(tea_stop_time < tea_start_time AND CURRENT_TIME() < tea_start_time AND CURRENT_TIME() < tea_stop_time)
							OR
						(tea_stop_time < tea_start_time AND CURRENT_TIME() > tea_start_time)
					)
					ORDER BY temp.cdtWeight  ASC LIMIT 0,1";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), ['admId' => $admId]);
	}

	/**
	 * This function is used for getting the Admin Id of the admin
	 * @param type $admId
	 * @return integer
	 */
	public static function getId($admPhone)
	{
		$sql = "SELECT adm_id	FROM admins WHERE  adm_phone = :adm_phone AND adm_active=1";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), ['adm_phone' => $admPhone]);
	}

	public function validatePassword($attribute, $param)
	{
		$length = strlen($this->new_password);
		if ($this->new_password != '')
		{
			$pattern	 = '/\W/';
			$pattern2	 = '/^.*(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/';
			if ($length <= 9)
			{
				$this->addError($attribute, 'Password must contain at least 10 characters');
				return false;
			}
			if (!preg_match($pattern, $this->new_password))
			{
				$this->addError($attribute, 'Password must contain at least one special character');
				return false;
			}
			if (!preg_match($pattern2, $this->new_password))
			{
				$this->addError($attribute, 'New password must contain at least one lower and upper case character and a digit.');
				return false;
			}
		}
		return true;
	}

	public function csrList()
	{
		$list	 = array();
		$sql	 = "SELECT adm_id,adm_fname,adm_lname FROM admins
						WHERE  adm_active=1 
						";
		$models	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($models as $model)
		{
			$list[$model['adm_id']] = $model['adm_fname'] . " " . $model['adm_lname'];
		}
		return $list;
	}

	public static function checkFieldExecutive($admId)
	{
		$fieldExecutive	 = 0;
		$arrData		 = Yii::app()->authManager->getRoles($admId);
		if ($arrData)
		{
			$arrRoles = array_keys($arrData);
			if (in_array('9 - Field Executive', $arrRoles))
			{
				$fieldExecutive = 1;
			}
		}
		return $fieldExecutive;
	}

	public static function getCsrNotificationByTeam($teamId)
	{
		$sql = "SELECT adm.adm_id
				FROM admins adm
				INNER JOIN admin_profiles adp ON adp.adp_adm_id = adm.adm_id
				INNER JOIN
				(
					SELECT 
					admin_profiles.adp_adm_id,
					JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtWeight'))) AS cdtWeight,
					JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtId'))) AS cdtId
					FROM admin_profiles
					JOIN pseudo_rows
					WHERE 1 
					HAVING cdtId IS NOT NULL
				) temp ON temp.adp_adm_id=adp.adp_adm_id
				INNER JOIN `cat_depart_team_map` cdt  ON temp.cdtId=cdt.cdt_id
				INNER JOIN departments dpt ON dpt.dpt_id = cdt.cdt_dpt_id
				WHERE adm_active = 1   AND cdt.cdt_tea_id=:teamId";
		return DBUtil::query($sql, DBUtil::SDB(), ['teamId' => $teamId]);
	}

	/**
	 * This function is used return admin region id 
	 * @param type $admId
	 * @return string
	 */
	public static function getRegionId($admId)
	{
		$adminModel = Admins::model()->findByPk($admId);
		return ($adminModel->adm_region != null && $adminModel->adm_region != "") ? $adminModel->adm_region : "1,2,3,4,5,6,7";
	}

	/**
	 * This function is used return admin serving booking Type
	 * @param type $admId
	 * @return string
	 */
	public static function getAdminsServeBookingType($admId)
	{
		$adminModel = Admins::model()->findByPk($admId);
		return ($adminModel->adm_booking_type != null && $adminModel->adm_booking_type != "") ? $adminModel->adm_booking_type : null;
	}

	/**
	 * This function is used fetch all admins id based on their teams/served Region/BookingType
	 * @param type $teams
	 * @param type $region
	 * @param type $bookingType
	 * @return string
	 */
	public static function getAdminByRegionWiseBookingType($teams, $region, $bookingType)
	{
		$teams = is_string($teams) ? $teams : strval($teams);
		DBUtil::getINStatement($teams, $bindString, $params);

		$sql = "SELECT 
					adm.adm_id,
					adm.adm_phone
				FROM admins adm
					INNER JOIN admin_profiles adp ON adp.adp_adm_id = adm.adm_id
					INNER JOIN
					(
						SELECT 
						admin_profiles.adp_adm_id,
						JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtWeight'))) AS cdtWeight,
						JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtId'))) AS cdtId
						FROM admin_profiles
						JOIN pseudo_rows
						WHERE 1 
						HAVING cdtId IS NOT NULL
					) temp ON temp.adp_adm_id=adp.adp_adm_id
					INNER JOIN `cat_depart_team_map` cdt  ON temp.cdtId=cdt.cdt_id
					INNER JOIN  teams  ON teams.tea_id=cdt.cdt_tea_id 
				WHERE 1 
					AND adm_active = 1
					AND teams.tea_status=1
					AND cdt.cdt_status=1  
					AND cdt.cdt_tea_id IN ({$bindString}) 
					AND FIND_IN_SET(:region,adm.adm_region) 
					AND adm.adm_region IS NOT NULL
					AND FIND_IN_SET(:bookingType, adm.adm_booking_type)
					AND adm.adm_booking_type IS NOT NULL
					AND adm.adm_phone IS NOT NULL AND  adm.adm_phone <> ''
                GROUP BY adm.adm_id";
		return DBUtil::query($sql, DBUtil::SDB(), array_merge($params, ['region' => $region, 'bookingType' => $bookingType]));
	}

}

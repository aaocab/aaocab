<?php

/**
 * This is the model class for table "teams".
 *
 * The followings are the available columns in table 'teams':
 * @property integer $tea_id
 * @property string $tea_name
 * @property integer $tea_status
 * @property string $tea_start_time
 * @property string $tea_stop_time
 * @property string $tea_created
 * @property string $tea_modified
 */
class Teams extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'teams';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
		return array(
			array('tea_name, tea_created, tea_modified', 'required'),
			array('tea_status', 'numerical', 'integerOnly' => true),
			array('tea_name', 'length', 'max' => 200),
			// The following rule is used by search().
// @todo Please remove those attributes that should not be searched.
			array('tea_id, tea_name, tea_status, tea_created, tea_modified,tea_start_time,tea_stop_time', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
// NOTE: you may need to adjust the relation name and the related
// class name for the relations automatically generated below.
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'tea_id'		 => 'Tea',
			'tea_name'		 => 'Tea Name',
			'tea_status'	 => 'Tea Status',
			'tea_created'	 => 'Tea Created',
			'tea_modified'	 => 'Tea Modified',
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

		$criteria->compare('tea_id', $this->tea_id);
		$criteria->compare('tea_name', $this->tea_name, true);
		$criteria->compare('tea_status', $this->tea_status);
		$criteria->compare('tea_created', $this->tea_created, true);
		$criteria->compare('tea_modified', $this->tea_modified, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Teams the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This function is used for get the team master list
	 * @return array
	 */
	public static function getList()
	{
		$sql	 = "SELECT `tea_id` id,`tea_name` name FROM `teams` WHERE `tea_status`= 1";
		$rows	 = DBUtil::queryAll($sql, DBUtil::SDB());

		$arrResponse = [];
		foreach ($rows as $team)
		{
			$arrResponse[$team["id"]] = $team["name"];
		}
		return $arrResponse;
	}

	/**
	 * This function is used for get the Mapped team department Categories master list
	 * @return array
	 */
	public static function getMappedList()
	{
		$sql		 = "SELECT `cdt_id` id, teams.tea_name team,departments.dpt_name dept,categories.cat_name cat FROM `cat_depart_team_map`
				INNER JOIN teams ON cdt_tea_id = teams.tea_id
				INNER JOIN departments ON cdt_dpt_id = departments.dpt_id
				INNER JOIN categories ON cdt_cat_id = categories.cat_id
				ORDER BY cdt_id ASC";
		$rows		 = DBUtil::queryAll($sql, DBUtil::SDB());
		$arrResponse = [];
		foreach ($rows as $team)
		{
			$arrResponse[$team["id"]] = $team["team"] . " ( " . $team["dept"] . "/" . $team["cat"] . " )";
		}
		return $arrResponse;
	}

	/**
	 * This function is used for getting the team Id
	 * @todo - Need to build a relationship with follow up reasons
	 * @param type $refTypeId
	 * @return int
	 */
	public static function getByFollowUpRef($refTypeId)
	{
		$sql	 = "SELECT tqm_tea_id  AS team FROM  team_queue_mapping WHERE tqm_queue_id=:refTypeId AND tqm_active=1 ORDER BY tqm_priority ASC LIMIT 0,1 ";
		$result	 = DBUtil::queryScalar($sql, DBUtil::SDB(), ['refTypeId' => $refTypeId]);
		return $result > 0 ? $result : 0;
	}

	public static function getByID($id)
	{
		$sql	 = "SELECT `tea_name` name FROM `teams` WHERE `tea_status`= 1 AND `tea_id` = :id";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB(),['id'=>$id]);
		return $result['name'];
	}

	public function getNames($ids)
	{
		$ctr	 = 0;
		$teamIds = explode(',', $ids);
		foreach ($teamIds as $id)
		{
			$ctr	 = ($ctr + 1);
			$teams	 .= self::getByID($id) . '  ';
		}
		return $teams;
	}

	/**
	 * This function is used for finding the department Ids
	 * @param type $teamId
	 * @param int $idType - 1 - For GOZO DB ID / 2 - Chat Server DB ID
	 * @return array of ids
	 */
	public static function getCdtIdById($teamId, $idType = 1)
	{
		$params	 = ["teamId" => $teamId];
		$sql	 = "SELECT cdt.*  FROM `cat_depart_team_map` cdt
				INNER JOIN departments dpt
				ON cdt.cdt_dpt_id = dpt.dpt_id
				WHERE `cdt_tea_id`=:teamId AND dpt.dpt_status = 1 AND cdt_status = 1";
		$result	 = DBUtil::queryAll($sql, DBUtil:: SDB(), $params);
		switch ((int) $idType)
		{
			case 1:
				$data	 = array_column($result, 'cdt_id');
				break;
			case 2:
				$data	 = array_column($result, 'cdt_chat_server_dpt_id');
				break;
		}

		return $data;
	}

	/**
	 * This function is used for chat server
	 * @return type
	 */
	public static function getCdtList()
	{
		$sql = "SELECT cdt.cdt_id , dpt.dpt_name, tea.tea_name, cat.cat_name FROM `cat_depart_team_map` cdt
				INNER JOIN departments dpt ON cdt.cdt_dpt_id = dpt.dpt_id
                INNER JOIN teams tea ON tea.tea_id = cdt_tea_id
                INNER JOIN categories cat ON cat.cat_id = cdt.cdt_cat_id
				WHERE dpt.dpt_status = 1 AND cdt_status = 1 AND cdt.cdt_chat_server_dpt_id IS NULL AND cdt.cdt_chat_server_status = 0";
		return DBUtil::queryAll($sql, DBUtil:: SDB());
	}

	public static function getByLive()
	{
		$sql	 = "SELECT `tea_id` id,`tea_name` name FROM `teams` WHERE `tea_status` =1";
		$rows	 = DBUtil::queryAll($sql, DBUtil::SDB());

		$arrResponse = [];
		foreach ($rows as $team)
		{
			$arrResponse[$team["id"]] = $team["name"];
		}
		$arrResponse["-1"] = "Auto Dispatch";
		return $arrResponse;
	}

	/**
	 * This function is used for BotChat followupEntry
	 * @param type $refTypeId
	 * @return int
	 */
	public static function getByRefType($refTypeId)
	{
		$team = 0;
		switch ($refTypeId)
		{
			case 1:
				$team	 = 1; //Retail sales
				break;
			case 2:
				$team	 = 6; //Customer Chat Support
				break;
			case 3:
				$team	 = 3; //Vendor Onboarding
				break;
			case 4:
				$team	 = 9; //Vendor support
				break;
		}

		return $team;
	}

	/**
	 * This function is used get  team id from cache by queue id 
	 * @param type $refTypeId
	 * @return string
	 */
	public static function getTeamIdFromCached($refTypeId)
	{
		$key = "queue_{$refTypeId}";
		if (Yii::app()->cache->get($key) !== false)
		{
			goto result;
		}
		$sql	 = "SELECT tqm_tea_id FROM `team_queue_mapping` WHERE `tqm_active` = 1 AND tqm_queue_id=:refTypeId ORDER BY `tqm_priority` ASC LIMIT 0,1 ";
		$result	 = DBUtil::queryScalar($sql, DBUtil::MDB(), ['refTypeId' => $refTypeId]);
		Yii::app()->cache->set($key, $result, 86400, new CacheDependency("queue"));
		result:
		return Yii::app()->cache->get($key);
	}

	/**
	 * This function is used delete all queue cached
	 * @return None
	 */
	public static function deleteQueueCached()
	{
		$sql	 = "SELECT tqm_tea_id FROM `team_queue_mapping` WHERE `tqm_active` = 1";
		$result	 = DBUtil::query($sql);
		foreach ($result as $row)
		{
			$key = "queue_{$row['tqm_tea_id']}";
			Yii::app()->cache->delete($key);
		}
	}

	/**
	 * This function is used for getting the Team name of the employee code
	 * @param type $admId
	 * @return team details array
	 */
	public static function getDetails($employeeCode)
	{
		$sql = "SELECT  teams.*
				FROM   admins adm
				JOIN `admin_profiles` adp ON adp.adp_adm_id = adm.adm_id
				JOIN
				(
					SELECT 
					admin_profiles.adp_emp_code,
					admin_profiles.adp_adm_id,
					JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtWeight'))) AS cdtWeight,
					JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtId'))) AS cdtId
					FROM admin_profiles
					JOIN pseudo_rows
					WHERE 1 AND  adp_emp_code = :employeeCode 
					HAVING cdtId IS NOT NULL
				) temp ON temp.adp_adm_id=adp.adp_adm_id
			    LEFT JOIN `cat_depart_team_map` cdt ON temp.cdtId=cdt.cdt_id
				LEFT JOIN teams ON cdt.cdt_tea_id = teams.tea_id								   
				WHERE  adp.adp_emp_code = :employeeCode 
				AND 
				( 
					(tea_start_time IS NULL AND tea_stop_time IS NULL)
						OR           
					(tea_start_time < tea_stop_time AND CURRENT_TIME() BETWEEN tea_start_time AND tea_stop_time)
						OR
					(tea_stop_time < tea_start_time AND CURRENT_TIME() < tea_start_time AND CURRENT_TIME() < tea_stop_time)
						OR
					(tea_stop_time < tea_start_time AND CURRENT_TIME() > tea_start_time)
				) ORDER BY temp.cdtWeight ASC LIMIT 0,1";
		return DBUtil::queryRow($sql, DBUtil::SDB(), ['employeeCode' => $employeeCode]);
	}

	/**
	 * This function is used for getting the Team Id of the admin
	 * @param type $admId
	 * @return integer
	 */
	public static function getMultipleTeamid($admId)
	{
		$sql = "SELECT  teams.tea_id, teams.tea_name
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
				WHERE 1 
				AND  adm_id = :admId
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
		return DBUtil::query($sql, DBUtil::SDB(), ['admId' => $admId]);
	}

	/**
	 * This function is used return queue details by queue Id
	 * @param type $queueId
	 * @return row array
	 */
	public static function getQueueDetailsById($queueId)
	{
		$sql = "SELECT * FROM `team_queue_mapping` WHERE `tqm_active` = 1 AND tqm_queue_id=:refTypeId ORDER BY `tqm_priority` ASC LIMIT 0,1 ";
		return DBUtil::queryrow($sql, DBUtil::MDB(), ['refTypeId' => $queueId]);
	}

	public static function getByAllTeams()
	{
		$sql		 = "SELECT `tea_id` id,`tea_name` name FROM `teams` WHERE `tea_status` =1";
		$rows		 = DBUtil::queryAll($sql, DBUtil::SDB());
		$arrResponse = [];
		foreach ($rows as $team)
		{
			$arrResponse[] = ["id" => $team["id"], "text" => $team["name"]];
		}
		return $arrResponse;
	}

	public static function getTeamByBookingRegionType($regionType = 0)
	{
		$arrRegionType = [
			1	 => '30', //assignNorth
			2	 => '29', //assignWest
			3	 => '36', //assignCentral
			4	 => '27', //assignSouth
			5	 => '28', //assignEast
			6	 => '28', //assignNorthEast
			7	 => '27' //assignSouth
		];
		if ($regionType > 0)
		{
			return $arrRegionType[$regionType];
		}
		else
		{
			return $arrRegionType[1];
		}
	}

	public static function getRegionByTeam($teamId = 0)
	{
		$arrTeamArr = [
			'30' => '1', //assignNorth
			'29' => '2', //assignWest
			'36' => '3', //assignCentral
			'27' => '4', //assignSouth
			'28' => '5', //assignEast
			'28' => '6', //assignNorthEast
			'27' => '7' //assignSouth
		];
		if ($teamId > 0)
		{
			return $arrTeamArr[$teamId];
		}
		else
		{
			return $arrTeamArr[1];
		}
	}

}

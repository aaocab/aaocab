<?php

/**
 * This is the model class for table "admin_profiles".
 *
 * The followings are the available columns in table 'admin_profiles':
 * @property string $adp_id
 * @property string $adp_adm_id
 * @property string $adp_emp_code
 * @property string $adp_hiring_date
 * @property string $adp_depart_date
 * @property string $adp_designation_id
 * @property string $adp_team_leader_id
 * @property string $adp_cdt_id
 * @property string $adp_location
 * @property integer $adp_auto_allocated
 * @property integer $adp_status
 * @property string $adp_created
 * @property string $adp_modified
 * 
 * @property Admins $adm
 *  
 */
class AdminProfiles extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public $adp_department_name	 = '', $adp_category_name	 = '', $cdtId				 = "";

	public function tableName()
	{
		return 'admin_profiles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
		return array(
			array('adp_adm_id', 'required'),
			array('adp_emp_code', 'required', 'on' => 'update1'),
			array('adp_status', 'numerical', 'integerOnly' => true),
			array('adp_adm_id, adp_team_leader_id', 'length', 'max' => 10),
			array('adp_emp_code, adp_designation_id', 'length', 'max' => 100),
			array('adp_location', 'length', 'max' => 200),
			array('adp_hiring_date, adp_depart_date', 'safe'),
			// The following rule is used by search().
// @todo Please remove those attributes that should not be searched.
			array('adp_id, adp_adm_id, adp_emp_code, adp_hiring_date, adp_depart_date, adp_designation_id, adp_team_leader_id, adp_cdt_id, adp_location, adp_status, adp_created, adp_modified,adp_auto_allocated', 'safe', 'on' => 'search'),
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
			'adm' => array(self::BELONGS_TO, 'Admins', 'adp_adm_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'adp_id'			 => 'Adp',
			'adp_adm_id'		 => 'Adp Adm',
			'adp_emp_code'		 => 'Employee Code',
			'adp_hiring_date'	 => 'Hiring Date',
			'adp_depart_date'	 => 'Depart Date',
			'adp_designation_id' => 'Designation',
			'adp_team_leader_id' => 'Team Leader',
			'adp_cdt_id'		 => 'Mapping ID',
			'adp_location'		 => 'Location',
			'adp_auto_allocated' => 'Auto-allocated Lead',
			'adp_status'		 => 'Adp Status',
			'adp_created'		 => 'Created At',
			'adp_modified'		 => 'Modified On',
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

		$criteria->compare('adp_id', $this->adp_id, true);
		$criteria->compare('adp_adm_id', $this->adp_adm_id, true);
		$criteria->compare('adp_emp_code', $this->adp_emp_code, true);
		$criteria->compare('adp_hiring_date', $this->adp_hiring_date, true);
		$criteria->compare('adp_depart_date', $this->adp_depart_date, true);
		$criteria->compare('adp_designation_id', $this->adp_designation_id, true);
		$criteria->compare('adp_team_leader_id', $this->adp_team_leader_id, true);
		$criteria->compare('adp_cdt_id', $this->adp_cdt_id, true);
		$criteria->compare('adp_location', $this->adp_location, true);
		$criteria->compare('adp_auto_allocated', $this->adp_auto_allocated, true);
		$criteria->compare('adp_status', $this->adp_status);
		$criteria->compare('adp_created', $this->adp_created, true);
		$criteria->compare('adp_modified', $this->adp_modified, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AdminProfiles the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getByAdminID($adminID)
	{
		$criteria	 = new CDbCriteria;
		$criteria->compare('adp_adm_id', $adminID);
		$model		 = $this->find($criteria);
		return $model;
	}

	public function getByTeamID($teamId)
	{
		$sql	 = "SELECT cdt_id,cat.cat_name,dpt.dpt_name,teams.tea_name FROM `cat_depart_team_map` 
				INNER JOIN departments dpt ON cdt_dpt_id = dpt.dpt_id
				INNER JOIN categories cat ON cdt_cat_id = cat.cat_id
				INNER JOIN teams ON cdt_tea_id = teams.tea_id
				WHERE teams.tea_id = $teamId";
		$result	 = DBUtil::queryRow($sql);
		return $result;
	}

	public function updateData($admId, $data = [])
	{
		$model = $this->getByAdminID($admId);
		if (!$model)
		{
			$model = new AdminProfiles();
		}
		$model->attributes		 = $data;
		$model->adp_adm_id		 = $admId;
		$model->adp_hiring_date	 = DateTimeFormat::DatePickerToDate($data['adp_hiring_date']);
		$cdtIds					 = array();
		$i						 = 1;
		$adp_cdt_id				 = explode(",", $data['cdtId']);
		foreach ($adp_cdt_id as $cdt_ids)
		{
			$cdtIds[] = array('cdtId' => (int) $cdt_ids, 'cdtWeight' => (int) $i);
			$i++;
		}
		$model->adp_cdt_id			 = (!empty($cdtIds)) ? json_encode($cdtIds) : null;
		$model->adp_auto_allocated	 = $data['adp_auto_allocated'] != null ? $data['adp_auto_allocated'] : 0;
		$model->adp_status			 = 1;
		$model->adp_created			 = new CDbExpression('NOW()');
		$model->adp_team_leader_id	 = (!empty($data['adp_team_leader_id'])) ? $data['adp_team_leader_id'] : null;
		$model->save();
	}

	public static function getTabByTeam($adminId = null, $tab = 2)
	{
		$adminTeamId = 0;

		if ($adminId == null)
		{
			$userInfo	 = UserInfo::getInstance();
			$adminId	 = $userInfo->getUserId();
		}

		if ($adminId)
		{
			$adminTeams = Teams::getMultipleTeamid($adminId);
			if ($adminTeams)
			{
				foreach ($adminTeams as $adminTeam)
				{
					$adminTeamId = $adminTeam['tea_id'];
					break;
				}
			}
		}

		if ($adminTeamId > 0)
		{
			switch ($adminTeamId)
			{
				case 1:
					$tab = 15;
					break;
				case 4:
					$tab = 2;
					break;
				case 9:
					$tab = 3;
					break;
				default:
					$tab = 2;
					break;
			}
		}

		return $tab;
	}
	
	public static function getAdmIdByTeamLeader($teamLeaderId)
	{
		$adminIds	 = false;
		$sql		 = "SELECT GROUP_CONCAT(adp_adm_id) FROM admin_profiles WHERE (adp_adm_id = {$teamLeaderId} OR adp_team_leader_id = {$teamLeaderId}) ";
		$res		 = DBUtil::queryScalar($sql);
		if ($res && !is_null($res) && $res != '')
		{
			$adminIds = $res;
		}

		return $adminIds;
	}

}

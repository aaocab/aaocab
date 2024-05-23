<?php

/**
 * This is the model class for table "cat_depart_team_map".
 *
 * The followings are the available columns in table 'cat_depart_team_map':
 * @property integer $cdt_id
 * @property integer $cdt_cat_id
 * @property integer $cdt_dpt_id
 * @property integer $cdt_tea_id
 * @property string $cdt_chat_server_dpt_id
 * @property integer $cdt_chat_server_status
 * @property integer $cdt_status
 * @property string $cdt_created
 * @property string $cdt_modified
 * 
 *   @property Categories $cdtCat
 * @property Departments $cdtDpt
 * @property Teams $cdtTea
 * 
 */
class CatDepartTeamMap extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cat_depart_team_map';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cdt_cat_id, cdt_dpt_id, cdt_tea_id, cdt_created, cdt_modified', 'required'),
			array('cdt_cat_id, cdt_dpt_id, cdt_tea_id, cdt_chat_server_status, cdt_status', 'numerical', 'integerOnly' => true),
			array('cdt_chat_server_dpt_id', 'length', 'max' => 10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cdt_id, cdt_cat_id, cdt_dpt_id, cdt_tea_id, cdt_chat_server_dpt_id, cdt_chat_server_status, cdt_status, cdt_created, cdt_modified', 'safe', 'on' => 'search'),
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
			'cdtCat' => array(self::BELONGS_TO, 'Categories', 'cdt_cat_id'),
			'cdtDpt' => array(self::BELONGS_TO, 'Departments', 'cdt_dpt_id'),
			'cdtTea' => array(self::BELONGS_TO, 'Teams', 'cdt_tea_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cdt_id'				 => 'Cdt',
			'cdt_cat_id'			 => 'Cdt Cat',
			'cdt_dpt_id'			 => 'Cdt Dpt',
			'cdt_tea_id'			 => 'Cdt Tea',
			'cdt_chat_server_dpt_id' => 'Cdt Chat Server Dpt',
			'cdt_chat_server_status' => 'Cdt Chat Server Status',
			'cdt_status'			 => 'Cdt Status',
			'cdt_created'			 => 'Cdt Created',
			'cdt_modified'			 => 'Cdt Modified',
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

		$criteria->compare('cdt_id', $this->cdt_id);
		$criteria->compare('cdt_cat_id', $this->cdt_cat_id);
		$criteria->compare('cdt_dpt_id', $this->cdt_dpt_id);
		$criteria->compare('cdt_tea_id', $this->cdt_tea_id);
		$criteria->compare('cdt_chat_server_dpt_id', $this->cdt_chat_server_dpt_id, true);
		$criteria->compare('cdt_chat_server_status', $this->cdt_chat_server_status);
		$criteria->compare('cdt_status', $this->cdt_status);
		$criteria->compare('cdt_created', $this->cdt_created, true);
		$criteria->compare('cdt_modified', $this->cdt_modified, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CatDepartTeamMap the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This function is used for updating the chat server department Id
	 * @param type $chatDeptId
	 * @param type $dptId
	 * @return int
	 */
	public static function updateChatServerStatus($chatDeptId, $dptId)
	{
		if (empty($chatDeptId) || empty($dptId))
		{
			return 0;
		}

		$model							 = self::model()->findByPk($dptId);
		$model->cdt_chat_server_dpt_id	 = $chatDeptId;
		$model->cdt_chat_server_status	 = 1;

		if (!$model->save())
		{
			return 0;
		}
		return 1;
	}

	/**
	 * This function is used for getting top weight getCatdepatTeam Id
	 * @param type $cdt_ids
	 * @return int 
	 */
	public static function getCatdepatTeamId($cdt_ids)
	{
		DBUtil::getINStatement($cdt_ids, $bindString, $params);
		$sql = "SELECT cdt.cdt_id
			FROM admins adm
			LEFT JOIN `admin_profiles` adp ON adp.adp_adm_id = adm.adm_id
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
			) temp ON temp.adp_adm_id=adp.adp_adm_id
			LEFT JOIN `cat_depart_team_map` cdt  ON temp.cdtId=cdt.cdt_id
			LEFT JOIN teams ON cdt.cdt_tea_id = teams.tea_id
			WHERE cdt.cdt_id IN ($bindString)
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
			ORDER BY temp.cdtWeight ASC LIMIT 0,1";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}

}

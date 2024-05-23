<?php

/**
 * This is the model class for table "team_queue_mapping".
 *
 * The followings are the available columns in table 'team_queue_mapping':
 * @property integer $tqm_id
 * @property string $tqm_tea_name
 * @property integer $tqm_priority
 * @property integer $tqm_queue_weight
 * @property string $tqm_queue_name
 * @property integer $tqm_queue_id
 * @property string $tqm_tea_id
 * @property integer $tqm_entity_type
 * @property integer $tqm_active
 */
class TeamQueueMapping extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'team_queue_mapping';
    }

    public $csrList, $teamList, $queueName;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('tqm_queue_name', 'required', 'on' => 'insert'),
            array('tqm_priority, tqm_queue_weight, tqm_queue_id, tqm_entity_type, tqm_active', 'numerical', 'integerOnly' => true),
            array('tqm_tea_name, tqm_queue_name, tqm_tea_id', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('tqm_id, tqm_tea_name, tqm_priority, tqm_queue_weight, tqm_queue_name, tqm_queue_id, tqm_tea_id, tqm_entity_type, tqm_active', 'safe', 'on' => 'search'),
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
            'tqm_id'           => 'Tqm',
            'tqm_tea_name'     => 'Tqm Tea Name',
            'tqm_priority'     => 'Tqm Priority',
            'tqm_queue_weight' => 'Tqm Queue Weight',
            'tqm_queue_name'   => 'Tqm Queue Name',
            'tqm_queue_id'     => 'Tqm Queue',
            'tqm_tea_id'       => 'Tqm Tea',
            'tqm_entity_type'  => '1=> Team,2=>Csr',
            'tqm_active'       => 'Tqm Active',
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

        $criteria->compare('tqm_id', $this->tqm_id);
        $criteria->compare('tqm_tea_name', $this->tqm_tea_name, true);
        $criteria->compare('tqm_priority', $this->tqm_priority);
        $criteria->compare('tqm_queue_weight', $this->tqm_queue_weight);
        $criteria->compare('tqm_queue_name', $this->tqm_queue_name, true);
        $criteria->compare('tqm_queue_id', $this->tqm_queue_id);
        $criteria->compare('tqm_tea_id', $this->tqm_tea_id, true);
        $criteria->compare('tqm_entity_type', $this->tqm_entity_type);
        $criteria->compare('tqm_active', $this->tqm_active);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TeamQueueMapping the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * This function  is used to get the team queue mapping list based on CSR or teams Selection
     * @param type (int) $csrId
     * @param type (int) $teamId
     * @return \CSqlDataProvider
     */
    public function getTeamQueue($csrId, $teamId)
    {
        $teamId = ($teamId > 0) ? $teamId : '';
        $csrId  = ($csrId > 0) ? $csrId : '';
        if ($teamId != '' && $csrId == '')
        {
            $qry1 = " WHERE tqm_tea_id = $teamId";
            $sql  = "SELECT * FROM `team_queue_mapping` $qry1 ORDER BY `team_queue_mapping`.`tqm_priority` ASC,tqm_queue_weight DESC ";
        }
        elseif ($csrId != '' && $teamId == '')
        {
            $qry2 = " WHERE admins.adm_id = $csrId";
            $sql  = "SELECT * FROM `team_queue_mapping` WHERE tqm_tea_id IN (SELECT DISTINCT teams.tea_id AS ids
									FROM admins
									INNER JOIN `admin_profiles` adp ON adp.adp_adm_id=admins.adm_id 
											INNER JOIN 
											( 
												SELECT admin_profiles.adp_adm_id, 
												JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtWeight'))) AS cdtWeight, 
												JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtId'))) AS cdtId 
												FROM admin_profiles JOIN pseudo_rows WHERE 1 HAVING cdtId IS NOT NULL
											)temp ON temp.adp_adm_id=adp.adp_adm_id 
											INNER JOIN `cat_depart_team_map` cdt ON temp.cdtId=cdt.cdt_id 
											INNER JOIN teams ON cdt.cdt_tea_id = teams.tea_id
                                            INNER JOIN team_queue_mapping ON team_queue_mapping.tqm_tea_id = teams.tea_id
											$qry2) ORDER BY `team_queue_mapping`.`tqm_priority` ASC,tqm_queue_weight DESC";
        }
        elseif ($teamId != '' && $csrId != '')
        {
            $sql = "SELECT * FROM `team_queue_mapping` WHERE tqm_tea_id IN (SELECT DISTINCT teams.tea_id AS ids
									FROM admins
									INNER JOIN `admin_profiles` adp ON adp.adp_adm_id=admins.adm_id 
											INNER JOIN 
											( 
												SELECT admin_profiles.adp_adm_id, 
												JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtWeight'))) AS cdtWeight, 
												JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtId'))) AS cdtId 
												FROM admin_profiles JOIN pseudo_rows WHERE 1 HAVING cdtId IS NOT NULL
											)temp ON temp.adp_adm_id=adp.adp_adm_id 
											INNER JOIN `cat_depart_team_map` cdt ON temp.cdtId=cdt.cdt_id 
											INNER JOIN teams ON cdt.cdt_tea_id = teams.tea_id
                                            INNER JOIN team_queue_mapping ON team_queue_mapping.tqm_tea_id = teams.tea_id
											WHERE admins.adm_id = $csrId) AND tqm_tea_id = $teamId ORDER BY `team_queue_mapping`.`tqm_priority` ASC,tqm_queue_weight DESC";
        }
        else
        {
            $sql = "SELECT * FROM `team_queue_mapping` WHERE 1 ORDER BY `team_queue_mapping`.`tqm_priority` ASC,tqm_queue_weight DESC";
        }
        $count        = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
        $dataprovider = new CSqlDataProvider($sql, [
            'totalItemCount' => $count,
            'sort'           => ['attributes' => ['tqm_id'],
            ],
        ]);
        return $dataprovider;
    }

    /**
     * This function is used for get the team master list
     * @return array
     */
    public static function getList()
    {
        $sql         = "SELECT `tqm_queue_id` id,`tqm_queue_name` name FROM `team_queue_mapping`";
        $rows        = DBUtil::queryAll($sql, DBUtil::SDB());
        $arrResponse = [];
        foreach ($rows as $team)
        {
            $arrResponse[$team["id"]] = $team["name"];
        }
        return $arrResponse;
    }

    /**
     * This function is used for get maximum queue id 
     * @return int $maxId
     */
    public static function getMaxQueueId()
    {
        $sql   = "SELECT MAX(`tqm_queue_id`) id FROM `team_queue_mapping`";
        $maxId = DBUtil::queryScalar($sql, DBUtil::SDB());
        return $maxId;
    }

    public static function clearCache($tqmId = 0)
    {
        $sql  = "SELECT tqm_tea_id FROM `team_queue_mapping` WHERE tqm_id = $tqmId AND `tqm_active` = 1";
        $data = DBUtil::queryRow($sql, DBUtil::SDB());
        $key  = "queue_{$data['tqm_tea_id']}";
        Yii::app()->cache->delete($key);
    }
	 public static function getQueueIdByTeamId($teamId)
    {
        $sql  = "SELECT DISTINCT tqm_queue_id FROM `team_queue_mapping` WHERE tqm_tea_id = :teamId AND `tqm_active` = 1";
        return  DBUtil::query($sql, DBUtil::SDB(),['teamId'=>$teamId]);
        
    }

}

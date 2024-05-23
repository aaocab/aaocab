<?php

/**
 * This is the model class for table "attendance_stats".
 *
 * The followings are the available columns in table 'attendance_stats':
 * @property integer $ats_id
 * @property integer $ats_admin_id
 * @property double $ats_timediff
 * @property string $ats_create_date
 * @property string $ats_update_date
 * @property integer $ats_status
 */
class AttendanceStats extends CActiveRecord
{

    public $ats_create_date1, $ats_create_date2, $csrSearch, $teamList;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'attendance_stats';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('ats_admin_id,  ats_timediff', 'required'),
            array('ats_admin_id, ats_status', 'numerical', 'integerOnly' => true),
            array('ats_timediff', 'numerical'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('ats_id, ats_admin_id,  ats_timediff, ats_create_date, ats_update_date, ats_status', 'safe', 'on' => 'search'),
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
            'ats_id'          => 'Ats',
            'ats_admin_id'    => 'Ats Admin',
            'ats_timediff'    => 'Ats Timediff',
            'ats_create_date' => 'Ats Create Date',
            'ats_update_date' => 'Ats Update Date',
            'ats_status'      => 'Ats Status',
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

        $criteria->compare('ats_id', $this->ats_id);
        $criteria->compare('ats_admin_id', $this->ats_admin_id);
        $criteria->compare('ats_timediff', $this->ats_timediff);
        $criteria->compare('ats_create_date', $this->ats_create_date, true);
        $criteria->compare('ats_update_date', $this->ats_update_date, true);
        $criteria->compare('ats_status', $this->ats_status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AttendanceStats the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * This function is used making In/out Entry
     */
    public static function getAdminAttendance($csr)
    {
        $date1                  = date('Y-m-d', strtotime(' - 1 days')) . " 00:00:00";
        $date2                  = date('Y-m-d', strtotime(' - 1 days')) . " 23:59:59";
        $mintues                = AdminOnoff::getTotalOnlineBycsrId($csr, $date1, $date2);
        $model                  = new AttendanceStats();
        $model->ats_admin_id    = $csr;
        $model->ats_create_date = $date1;
        $model->ats_update_date = $date2;
        $model->ats_timediff    = round(($mintues / 60), 2);
        $model->ats_status      = 1;
        if (!$model->save())
        {
            throw new Exception(json_encode($model->getErrors()), 1);
        }
    }

    /**
     * This function is used for all the hour clocked by all gozen between given two given date
     * @param type $command
     * @return dataprovider
     */
    public function getAttendanceReport($type = DBUtil::ReturnType_Provider)
    {
        $condition = "";
        $innerJoin = "";
        $select    = "";
        if ($this->ats_create_date1 != '' && $this->ats_create_date2 != '')
        {
            $fromTime     = '00:00:00';
            $toTime       = '23:59:59';
            $fromDateTime = $this->ats_create_date1 . ' ' . $fromTime;
            $toDateTime   = $this->ats_create_date2 . ' ' . $toTime;
        }
        if (($this->ats_create_date2 != '' && $this->ats_create_date1 != '1970-01-01') && ($this->ats_create_date2 != '' && $this->ats_create_date2 != '1970-01-01'))
        {
            $condition .= " AND ats_create_date BETWEEN '$fromDateTime' AND '$toDateTime' ";
        }
        if ($this->csrSearch != null)
        {
            $csrSearch = implode(",", $this->csrSearch);
            $condition .= " AND  ats_admin_id IN ($csrSearch)";
        }
        if ($this->teamList > 0)
        {
            $teamList  = $this->teamList;
            $condition .= " AND teams.tea_id IN ($teamList)";
            $innerJoin .= "INNER JOIN `admin_profiles` adp ON adp.adp_adm_id=admins.adm_id 
                INNER JOIN 
                ( 
                        SELECT admin_profiles.adp_adm_id, 
                        JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtWeight'))) AS cdtWeight, 
                        JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtId'))) AS cdtId 
                        FROM admin_profiles JOIN pseudo_rows WHERE 1 HAVING cdtId IS NOT NULL ORDER BY cdtWeight ASC
                )temp ON temp.adp_adm_id=adp.adp_adm_id 
                INNER JOIN `cat_depart_team_map` cdt ON temp.cdtId=cdt.cdt_id 
                INNER JOIN teams ON cdt.cdt_tea_id = teams.tea_id";
            $select    = ",GROUP_CONCAT(DISTINCT teams.tea_name) AS teamsList,GROUP_CONCAT( DISTINCT teams.tea_id) AS teamsId";
        }
        $sql          = "Select admins.adm_id,admins.gozen,'$fromDateTime' AS fromDate, '$toDateTime' AS toDate,SUM(ats_timediff) AS totalHrs $select
                FROM admins
                INNER JOIN attendance_stats on attendance_stats.ats_admin_id =admins.adm_id 
                $innerJoin
                WHERE 1 $condition
                AND attendance_stats.ats_status=1
                AND admins.adm_active=1
                GROUP BY attendance_stats.ats_admin_id ";
		if ($type == DBUtil::ReturnType_Provider)
		{
			$dataProvider = new CSqlDataProvider($sql, [
				'totalItemCount' => DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB()),
				'db'             => DBUtil::SDB(),
				'sort'           => ['attributes' => ['totalHrs', 'fromDate'], 'defaultOrder' => 'fromDate  DESC'],
				'pagination'     => ['pageSize' => 250],
			]);
			return $dataProvider;
		}
		else{
			$result	 = DBUtil::query($sql, DBUtil::SDB());
			return $result;
		}
        

		
    }

    /**
     * This function is used for all the hour clocked by individual between given two given date
     * @param type $command
     * @return dataprovider
     */
    public function getAttendanceDetailsReport()
    {
        if ($this->ats_create_date1 != '' && $this->ats_create_date2 != '')
        {
            $fromTime     = '00:00:00';
            $toTime       = '23:59:59';
            $fromDateTime = $this->ats_create_date1 . ' ' . $fromTime;
            $toDateTime   = $this->ats_create_date2 . ' ' . $toTime;
        }
        if (($this->ats_create_date2 != '' && $this->ats_create_date1 != '1970-01-01') && ($this->ats_create_date2 != '' && $this->ats_create_date2 != '1970-01-01'))
        {
            $condition .= " AND ats_create_date BETWEEN '$fromDateTime' AND '$toDateTime' ";
        }
        if ($this->ats_admin_id > 0)
        {
            $condition .= " AND ats_admin_id=$this->ats_admin_id";
        }
        $sql = "Select adm_id,ats_create_date AS CreateDate,ats_timediff AS totalHrs
                FROM admins
                INNER JOIN attendance_stats on attendance_stats.ats_admin_id =admins.adm_id 
                WHERE 1 $condition
                AND admins.adm_active=1
                AND attendance_stats.ats_status=1
                GROUP BY date(ats_create_date) ";
        if ($command == false)
        {
            $dataProvider = new CSqlDataProvider($sql, [
                'totalItemCount' => DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB()),
                'db'             => DBUtil::SDB(),
                'sort'           => ['attributes' => ['totalHrs', 'adm_id', 'CreateDate'], 'defaultOrder' => 'CreateDate  ASC'],
                'pagination'     => ['pageSize' => 31],
            ]);
            return $dataProvider;
        }
    }

}

<?php

/**
 * This is the model class for table "admin_onoff".
 *
 * The followings are the available columns in table 'admin_onoff':
 * @property integer $ado_id
 * @property integer $ado_admin_id
 * @property integer $ado_status
 * @property string  $ado_time
 * @property integer $ado_lat
 * @property integer $ado_lng
 * @property integer $ado_logged_out_type
 * @property string  $ado_login_confirm_time
 */
class AdminOnoff extends CActiveRecord
{

    const ONOFF_BY_SYSTEM = 2;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'admin_onoff';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
        return array(
            array('ado_admin_id, ado_status, ado_time, ado_lat, ado_lng', 'required'),
            ['ado_status', 'validateShiftOnOff', 'on' => 'shiftOnOffValid'],
            ['ado_status, ado_admin_id, ado_lat, ado_lng', 'required', 'on' => 'shiftOnOffValid, shiftOnOffAlertValid'],
            ['ado_login_confirm_time', 'validateShiftOnOffAlert', 'on' => 'shiftOnOffAlertValid'],
            //array('ado_admin_id, ado_status, ado_lat, ado_lng', 'numerical', 'integerOnly'=>true),
// The following rule is used by search().
// @todo Please remove those attributes that should not be searched.
            array('ado_id, ado_admin_id, ado_status, ado_time, ado_lat, ado_lng', 'safe', 'on' => 'search'),
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
            'ado_id'                 => 'Ado',
            'ado_admin_id'           => 'Admin Id',
            'ado_status'             => 'Admin On Off Status',
            'ado_time'               => 'Ado Time',
            'ado_lat'                => 'Latitude',
            'ado_lng'                => 'Longitude',
            'ado_logged_out_type'    => 'Logged out by',
            'ado_login_confirm_time' => 'Confirm Time',
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

        $criteria->compare('ado_id', $this->ado_id);
        $criteria->compare('ado_admin_id', $this->ado_admin_id);
        $criteria->compare('ado_status', $this->ado_status);
        $criteria->compare('ado_time', $this->ado_time);
        $criteria->compare('ado_lat', $this->ado_lat);
        $criteria->compare('ado_lng', $this->ado_lng, true);
        $criteria->compare('ado_lng', $this->ado_logged_out_type);
        $criteria->compare('ado_login_confirm_time', $this->ado_login_confirm_time);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AdminOnoff the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function validateShiftOnOff($attribute, $params)
    {
        if ($this->ado_status == 0 || $this->ado_status == 1)
        {
            $data     = $this->getByAdmId($this->ado_admin_id);
            $oldModel = AdminOnoff::model()->findByPk($data['ado_id']);
            if ($oldModel != "")
            {
                if ($this->ado_status == 0)
                {
                    $state = "clocked out";
                }
                else
                {
                    $state = "clocked In";
                }
                if ($oldModel->ado_status == $this->ado_status)
                {
                    $this->addError($attribute, "You are already $state.");
                    return false;
                }
            }
            return true;
        }
        else
        {
            $this->addError('ado_status', 'Shift on off should be 0 or 1. ');
            return false;
        }
    }

    public function validateShiftOnOffAlert($attribute, $params)
    {
        if ($this->ado_status == 0 || $this->ado_status == 1)
        {
            $data     = $this->getByAdmId($this->ado_admin_id);
            $oldModel = AdminOnoff::model()->findByPk($data['ado_id']);
            if ($oldModel != "")
            {
                if ($this->ado_status == 0)
                {
                    if ($oldModel->ado_status == $this->ado_status)
                    {
                        $this->addError($attribute, "You are already clocked out.");
                        return false;
                    }
                }
                return true;
            }
            return true;
        }
        else
        {
            $this->addError('ado_status', 'Shift on off status should be 0 or 1. ');
            return false;
        }
        if ($this->ado_login_confirm_time == '')
        {
            $this->addError($attribute, 'Confirm time should not blank.');
            return false;
        }
    }

    public function addAdminOnOffStatus($data, $adminId)
    {

        $model = new AdminOnoff();

        $model->ado_admin_id = $adminId;
        $model->ado_status   = $data['ado_status'];
        $model->ado_time     = DBUtil::getCurrentTime();
        $model->ado_lat      = $data['ado_lat'];
        $model->ado_lng      = $data['ado_lng'];

        if ($model->save())
        {
            return array("success" => true, "data" => $data['ado_status']);
        }
        else
        {
            return array("success" => false, "data" => "No Data");
        }
    }

    public static function adminLogTime($csrId, $date1, $date2)
    {
        $params       = array('date1' => $date1, 'date2' => $date2, 'ado_admin_id' => $csrId);
        $sql          = "SELECT 
							ado_status,
							ado_time
							FROM admin_onoff
							WHERE  ado_admin_id=:ado_admin_id
							AND ado_time BETWEEN :date1 AND :date2";
        $count        = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) temp", DBUtil::SDB(), $params);
        $dataprovider = new CSqlDataProvider($sql, array(
            "totalItemCount" => $count,
            "params"         => $params,
            'db'             => DBUtil::SDB(),
            "pagination"     => array("pageSize" => 120),
            'sort'           => array('defaultOrder' => 'ado_time ASC')
        ));
        return $dataprovider;
    }

    public static function getTotalOnlineBycsrId($csrId, $date1, $date2)
    {
        $params = array('date1' => $date1, 'date2' => $date2, 'ado_admin_id' => $csrId);
        $sql    = " SELECT SUM(seconds_in) AS secs
							FROM (SELECT in_time,
										 IFNULL(out_time, NOW())
											AS out_time,
										   UNIX_TIMESTAMP(IFNULL(out_time, NOW()))
										 - UNIX_TIMESTAMP(in_time)
											AS seconds_in
							FROM (SELECT ado_time AS in_time,
											   (SELECT MIN(ado_time)
												FROM admin_onoff
												WHERE ado_status = 0 AND ado_time > t.ado_time  AND ado_admin_id = :ado_admin_id AND ado_time BETWEEN :date1	 AND :date2 )
												  AS out_time
										FROM admin_onoff t
										WHERE     ado_status = 1
											  AND t.ado_admin_id = :ado_admin_id
											  AND t.ado_time BETWEEN :date1	 AND :date2) AS in_out)
								 AS q1";

        $sec    = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
        $mintue = ($sec / 60);
        if ($mintue > 0)
        {
            return $mintue;
        }
        else
        {
            $params      = array('date1' => $date1, 'date2' => $date2, 'ado_admin_id' => $csrId);
            $sql         = "SELECT COUNT(*) FROM admin_onoff WHERE ado_status = 0 AND ado_admin_id = :ado_admin_id AND ado_time BETWEEN :date1 AND :date2";
            $logoutCount = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
            if ($logoutCount == 1)
            {
                $params = array('date1' => date_format(date_sub(date_create($date1), date_interval_create_from_date_string("01 days")), "Y-m-d") . " 00:00:00", 'date2' => $date2, 'ado_admin_id' => $csrId);
                $sql    = " SELECT SUM(seconds_in) AS secs
							FROM (SELECT in_time,
										 IFNULL(out_time, NOW())
											AS out_time,
										   UNIX_TIMESTAMP(IFNULL(out_time, NOW()))
										 - UNIX_TIMESTAMP(in_time)
											AS seconds_in
							FROM (SELECT ado_time AS in_time,
											   (SELECT MIN(ado_time)
												FROM admin_onoff
												WHERE ado_status = 0 AND ado_time > t.ado_time  AND ado_admin_id = :ado_admin_id AND ado_time BETWEEN :date1	 AND :date2 )
												  AS out_time
										FROM admin_onoff t
										WHERE     ado_status = 1
											  AND t.ado_admin_id = :ado_admin_id
											  AND t.ado_time BETWEEN :date1	 AND :date2) AS in_out)
								 AS q1";

                $sec    = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
                $mintue = ($sec / 60);
                return $mintue;
            }
            else
            {
                return 0;
            }
        }
    }

    public function getOnlineTimeStatus($adminId)
    {
        $sql1   = "SELECT ado_status,ado_time FROM `admin_onoff` WHERE ado_time BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND NOW() AND ado_admin_id =$adminId ORDER BY ado_id DESC LIMIT 0,1";
        $result = DBUtil::queryRow($sql1, DBUtil::SDB(), $params);

        if ($result['ado_status'] == 1)
        {
            $startTime = date('Y-m-d 00:00:00');
            $endTime   = date('Y-m-d H:i:s');
            $min       = $this->getTotalOnlineBycsrId($adminId, $startTime, $endTime);
            $hr        = $min / 60;
        }

        return $hr;
    }

    public static function chkPresentStatus($adminId)
    {

        $todaysDate = date('Y-m-d');
        $params     = array('adminId' => $adminId);
        $sql        = "SELECT ado_status FROM `admin_onoff` WHERE ado_time BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND NOW() AND ado_admin_id =:adminId ORDER BY ado_id DESC LIMIT 0,1";
        $status     = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
        return $status;
    }

    public function chkShiftPresentStatus($admId)
    {
        $params = array('adminId' => $admId);
        $sql    = "SELECT ado_status FROM `admin_onoff` WHERE ado_time AND ado_admin_id =:adminId ORDER BY ado_id DESC LIMIT 0,1";
        $status = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
        return $status;
    }

    public function getByAdmId($admId, $condition = false)
    {
        $where = "";
        if ($condition)
        {
            $where = " AND ado_time BETWEEN CONCAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY), ' 00:00:00') AND CONCAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY), ' 23:59:59')";
        }
        $sql = "SELECT ado_id, ado_admin_id, ado_time, ado_status, ado_lat, ado_lng, ado_logged_out_type, ado_login_confirm_time, TIMESTAMPDIFF(hour, ado_login_confirm_time, NOW()) AS calculateHour FROM `admin_onoff` WHERE ado_admin_id =:admId $where ORDER BY ado_time DESC LIMIT 0,1";
        return DBUtil::queryRow($sql, DBUtil::MDB(), ['admId' => $admId]);
    }

    /**
     * This function is used for Admin On Off status change or Admin On Off Alert 
     * @param int $data
     * @return $returnSet 
     */
    public function checkAlertStatus($data)
    {
        switch ($data)
        {
            case 1:
                $returnSet = $this->addShiftOnOffStatus();
                break;

            case 2:
                $returnSet = $this->addShiftOnOffAlert();
                break;
        }
        return $returnSet;
    }

    /**
     * This function is used for Admin On Off status change
     * @return $returnSet 
     */
    public function addShiftOnOffStatus()
    {
        $returnSet = new ReturnSet();
        try
        {
            $this->scenario = 'shiftOnOffValid';
            $csr            = $this->ado_admin_id;
            $data           = $this->getByAdmId($this->ado_admin_id);
            $oldModel       = AdminOnoff::model()->findByPk($data['ado_id']);
            $date1          = strtotime(date('Y-m-d', strtotime($oldModel->ado_time)));
            $date2          = strtotime(date('Y-m-d', strtotime($this->ado_time)));
            $timeDiff       = $date2 - $date1;
            if ($date1 != $date2 && (($timeDiff / 3600) <= 24) && $this->ado_status == 0)
            {
                $this->addInOutEntry($csr);
            }
            if (!$this->save())
            {
                throw new Exception(json_encode($this->getErrors()), ReturnSet::ERROR_VALIDATION);
            }
            if ($this->ado_status == 1)
            {
                $returnSet->setMessage("You're clocked in.");
            }
            else
            {
                ServiceCallQueue::processUnAssignment($csr);
                $returnSet->setMessage("You're clocked out.");
            }
            $returnSet->setData($this, false);
            $returnSet->setStatus(true);
        }
        catch (Exception $e)
        {
            $returnSet->setStatus(false);
            $returnSet = ReturnSet::setException($e);
        }
        return $returnSet;
    }

    /**
     * This function is used for Admin On Off Alert
     * @return $returnSet 
     */
    public function addShiftOnOffAlert()
    {
        $returnSet = new ReturnSet();
        try
        {
            $this->scenario = 'shiftOnOffAlertValid';
            $data           = $this->getByAdmId($this->ado_admin_id);
            $csr            = $this->ado_admin_id;
            $oldModel       = AdminOnoff::model()->findByPk($data['ado_id']);
            $date1          = strtotime(date('Y-m-d', strtotime($oldModel->ado_time)));
            $date2          = strtotime(date('Y-m-d', strtotime($this->ado_time)));
            $timeDiff       = $date2 - $date1;
            if ($date1 != $date2 && (($timeDiff / 3600) <= 24) && $this->ado_status == 0)
            {
                $this->addInOutEntry($csr);
            }
            if (!$data || !$oldModel)
            {
                throw new Exception(json_encode("No Record Found."), ReturnSet::ERROR_VALIDATION);
            }
            if ($this->ado_status == 1)
            {
                $oldModel->ado_login_confirm_time = $this->ado_login_confirm_time;
                $oldModel->ado_time               = $this->ado_time;
                if (!$oldModel->update())
                {
                    throw new Exception(json_encode($this->getErrors()), ReturnSet::ERROR_VALIDATION);
                }
                $returnSet->setMessage("Ok, Thanks.");
            }
            else
            {
                if (!$this->save())
                {
                    throw new Exception(json_encode($this->getErrors()), ReturnSet::ERROR_VALIDATION);
                }
                ServiceCallQueue::processUnAssignment($csr);
                $returnSet->setMessage("You're now clocked out.");
            }
            $returnSet->setData($this, false);
            $returnSet->setStatus(true);
        }
        catch (Exception $e)
        {
            $returnSet->setStatus(false);
            $returnSet = ReturnSet::setException($e);
        }
        return $returnSet;
    }

    public function updateShiftLoggedOut($admId)
    {
        $success = false;
        $data    = $this->getByAdmId($admId);
        if ($data != '')
        {
            $oldModel = AdminOnoff::model()->findByPk($data['ado_id']);
            $date1    = strtotime(date('Y-m-d', strtotime($oldModel->ado_time)));
            $date2    = strtotime(date('Y-m-d'));
            $timeDiff = $date2 - $date1;
            if ($date1 != $date2 && (($timeDiff / 3600) <= 24) && $oldModel->ado_status == 1)
            {
                $this->addInOutEntry($oldModel->ado_admin_id);
            }
            $model = new AdminOnoff();
            if ($oldModel->ado_status == 1)
            {
                $model->ado_logged_out_type    = AdminOnoff::ONOFF_BY_SYSTEM;
                $model->ado_time               = DBUtil::getDBDateTime();
                $model->ado_login_confirm_time = DBUtil::getDBDateTime();
                $model->ado_status             = 0;
                $model->ado_lat                = $oldModel->ado_lat;
                $model->ado_lng                = $oldModel->ado_lng;
                $model->ado_admin_id           = $oldModel->ado_admin_id;
                if ($model->save())
                {
                    $success = true;
                    ServiceCallQueue::processUnAssignment($admId);
                }
            }
        }
        return $success;
    }

    /**
     * This function is used for status change(Off) for those whose working for intra day
     */
    public function updateOpsUserShiftTimeOver()
    {
        $data = AppTokens::model()->opsUserIds();
        if ($data != '')
        {
            foreach ($data as $value)
            {
                $checkData = $this->getByAdmId($value['apt_user_id']);
                if ($checkData['ado_status'] == 1 && ($checkData['calculateHour'] >= 9 && $checkData['calculateHour'] != null))
                {
                    $result = $this->updateShiftLoggedOut($value['apt_user_id']);
//                    if ($result == 1)
//                    {
//                        AppTokens::model()->opsTimeOverLogOut($value['apt_user_id']);
//                        echo "ado Amdin Ids : " . $value['apt_user_id'];
//                    }
                }
            }
        }
    }

    /**
     * This function is used making In/out Entry
     */
    public function addInOutEntry($csr, $condition = false)
    {
        $transaction = DBUtil::beginTransaction();
        try
        {
            $data     = $this->getByAdmId($csr, $condition);
            $oldModel = AdminOnoff::model()->findByPk($data['ado_id']);
            $date1    = date('Y-m-d', strtotime($oldModel->ado_time)) . " 23:59:58";
            $date2    = date("Y-m-d", strtotime('+1 day', strtotime($date1))) . " 00:00:02";

            //insert new logout entry start//
            $model                      = new AdminOnoff();
            $model->ado_logged_out_type = AdminOnoff::ONOFF_BY_SYSTEM;
            $model->ado_time            = $date1;
            $model->ado_status          = 0;
            $model->ado_lat             = $oldModel->ado_lat;
            $model->ado_lng             = $oldModel->ado_lng;
            $model->ado_admin_id        = $oldModel->ado_admin_id;
            if ($model->save())
            {
                $success = true;
            }
            //insert new logout entry ends//
            //insert new login  entry start//
            $model                      = new AdminOnoff();
            $model->ado_logged_out_type = AdminOnoff::ONOFF_BY_SYSTEM;
            $model->ado_time            = $date2;
            $model->ado_status          = 1;
            $model->ado_lat             = $oldModel->ado_lat;
            $model->ado_lng             = $oldModel->ado_lng;
            $model->ado_admin_id        = $oldModel->ado_admin_id;
            if ($model->save())
            {
                $success = true;
            }
            DBUtil::commitTransaction($transaction);
        }
        catch (Exception $e)
        {
            DBUtil::rollbackTransaction($transaction);
        }
        //insert new login entry ends//
    }

    /**
     * This function is used get last login entry for each individual 
     */
    public function getLastAdminsDetails($admId, $type = 0)
    {
        $where = $type == 1 ? " AND ado_status=1 " : "";
        $sql   = "SELECT 
                IF(DATE_ADD(ado_login_confirm_time, INTERVAL 9 hour)<= NOW() AND ado_status=1,1,0) AS logoutType,
                ado_status,
                ado_lat,
                ado_lng,
                ado_admin_id,
                ado_login_confirm_time
                FROM admin_onoff
                WHERE  1
                AND ado_admin_id =:admId 
                $where
                AND ado_login_confirm_time IS NOT NULL      
                ORDER BY ado_time DESC  LIMIT 0, 1";
        return DBUtil::queryRow($sql, DBUtil::MDB(), ['admId' => $admId]);
    }

    /**
     * This function is used for status change(Off) for those whose working hour more than 9 Hours
     */
    public function ShiftTimeOver()
    {
        $details = Admins::model()->findAllActive();
        foreach ($details as $row)
        {
            try
            {
                $adminDetails = AdminOnoff::getLastAdminsDetails($row['adm_id']);
                if ($adminDetails['ado_status'] == 1 && $adminDetails['logoutType'] == 1)
                {
                    $this->ShiftLoggedOut($adminDetails);
                }
            }
            catch (Exception $ex)
            {
                Logger::exception($ex);
            }
        }
    }

    /**
     * This function is used for making an logout entry for particular admin Id
     */
    public function ShiftLoggedOut($row)
    {
        $success = false;
        if ($row != '')
        {
            $model                         = new AdminOnoff();
            $model->ado_admin_id           = $row['ado_admin_id'];
            $model->ado_status             = 0;
            $model->ado_time               = DBUtil::getCurrentTime();
            $model->ado_lat                = $row['ado_lat'];
            $model->ado_lng                = $row['ado_lng'];
            $model->ado_logged_out_type    = 0;
            $model->ado_login_confirm_time = DBUtil::getCurrentTime();
            if ($model->save())
            {
                $success = true;
                ServiceCallQueue::processUnAssignment($row['ado_admin_id']);
            }
        }
        return $success;
    }

}

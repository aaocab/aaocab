<?php

/**
 * This is the model class for table "driver_docs".
 *
 * The followings are the available columns in table 'driver_docs':
 * @property integer $drd_id
 * @property integer $drd_drv_id
 * @property integer $drd_type
 * @property integer $drd_sub_type
 * @property string $drd_file
 * @property string $drd_remarks
 * @property integer $drd_status
 * @property integer $drd_active
 * @property string $drd_created_at
 * @property string $drd_appoved_at
 * @property integer $drd_approve_by
 * @property integer $drd_temp_approved
 * @property string $drd_temp_approved_at
 *
 * The followings are the available model relations:
 * @property Drivers $drdDrv
 */
class DriverDocs extends CActiveRecord
{

    public $drd_desc, $drvname;
    public $doctype		 = [
	1	 => 'Voter ID',
	2	 => 'PAN Card',
	3	 => 'Aadhaar',
	4	 => 'Driver Licence',
	5	 => 'Police Verification Certificate'
    ];
    public $docSubType	 = [
	1	 => 'Front',
	2	 => 'Back',
    ];
    public $docArray	 = [0	 => 'voterid',
	1	 => 'voterbackid',
	2	 => 'aadhar',
	3	 => 'aadharback',
	4	 => 'pan',
	5	 => 'panback',
	6	 => 'panback',
	7	 => 'license',
	8	 => 'licenseback',
	9	 => 'policever'];

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
	return 'driver_docs';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
	// NOTE: you should only define rules for those attributes that
	// will receive user inputs.
	return array(
	    //array('drd_drv_id, drd_type, drd_status, drd_approve_by', 'numerical', 'integerOnly' => true),
	    array('drd_file', 'length', 'max' => 250),
	    array('drd_remarks', 'required', 'on' => 'reject'),
	    // The following rule is used by search().
	    // @todo Please remove those attributes that should not be searched.
	    array('drd_id, drd_drv_id, drd_type, drd_sub_type, drd_file, drd_remarks, drd_status, drd_active, drd_created_at, drd_appoved_at, drd_approve_by, drd_desc,drd_temp_approved,drd_temp_approved_at', 'safe', 'on' => 'search'),
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
	    'drdDrv' => array(self::BELONGS_TO, 'Drivers', 'drd_drv_id'),
	);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
	return array(
	    'drd_id'	 => 'Drd',
	    'drd_drv_id'	 => 'Driver',
	    'drd_type'	 => '1=> Voter Card, 2=> Pan Card, 3=> Aadhaar Card, 4=> Driver License, 5=>Police Verification Certificate',
	    'drd_file'	 => 'File',
	    'drd_status'	 => 'Status',
	    'drd_sub_type'	 => 'Sub Status',
	    'drd_active'	 => 'Active',
	    'drd_appoved_at' => 'Appoved At',
	    'drd_approve_by' => 'Approve By',
	    'drd_remarks'	 => 'Remarks',
	    'drvname'	 => 'Driver Name',
		'drd_temp_approved'	 => 'Driver Temporary Approved',
		'drd_temp_approved_at'	 => 'Driver Temporary Approved By'
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

	$criteria->compare('drd_id', $this->drd_id);
	$criteria->compare('drd_drv_id', $this->drd_drv_id);
	$criteria->compare('drd_type', $this->drd_type);
	$criteria->compare('drd_sub_type', $this->drd_sub_type);
	$criteria->compare('drd_file', $this->drd_file, true);
	$criteria->compare('drd_status', $this->drd_status);
	$criteria->compare('drd_active', $this->drd_active);
	$criteria->compare('drd_appoved_at', $this->drd_appoved_at, true);
	$criteria->compare('drd_approve_by', $this->drd_approve_by);

	return new CActiveDataProvider($this, array(
	    'criteria' => $criteria,
	));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return DriverDocs the static model class
     */
    public static function model($className = __CLASS__)
    {
	return parent::model($className);
    }

    public function findAllByDrvId($drvId)
    {
		$sql = "SELECT * FROM `driver_docs` WHERE driver_docs.drd_drv_id  in (SELECT d3.drv_id FROM drivers d1
          INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
          INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
          WHERE d1.drv_id=$drvId) AND driver_docs.drd_active=1";
	return DBUtil::queryAll($sql);
    }

    public function findDocsByDrvId($drvId)
    {
		$rows = $this->findAllByDrvId($drvId);
	if (count($rows) > 0)
	{
	    foreach ($rows as $row)
	    {
		//  if($row['drd_type']=1 && )
	    }
	}
    }

    public function getMsgByType($type, $sub_type)
    {
	$returnData = [];
	if ($type == 4 && $sub_type == 1)
	{
	    $returnData = ['remarks' => 'Front Driver License Paper expired. Upload latest papers with new expiration date', 'event_id' => DriversLog::DRIVER_DL_REJECT, 'doc' => 'Front Driver License'];
	}
	else if ($type == 4 && $sub_type == 2)
	{
	    $returnData = ['remarks' => 'Back Driver License Paper expired. Upload latest papers with new expiration date', 'event_id' => DriversLog::DRIVER_DL_BACK_REJECT, 'doc' => 'Back Driver License'];
	}
	return $returnData;
    }

    public function findApproveList()
    {
	$sql = "SELECT vendorIds,
                d2.drv_name,
				drd_drv_id, 
				d2.drv_approved,
                d2.drv_lic_exp_date , 
				drdIds
                FROM `drivers`
                INNER JOIN
                (
                    SELECT driver_docs.drd_drv_id, COUNT(1) as cnt ,
                    GROUP_CONCAT(driver_docs.drd_id SEPARATOR ',') as drdIds
                    FROM `driver_docs`
                    INNER JOIN `drivers` ON drivers.drv_id=driver_docs.drd_drv_id AND drivers.drv_active=1
					INNER JOIN drivers d2 ON d2.drv_id = drivers.drv_ref_code
                    WHERE (driver_docs.drd_type = 4 AND driver_docs.drd_sub_type = 1)
                    AND driver_docs.drd_status = 1
                    AND driver_docs.drd_active = 1
                    GROUP BY driver_docs.drd_drv_id
                    HAVING (cnt = 1)
                )ddocs ON ddocs.drd_drv_id=drivers.drv_id
                LEFT JOIN
                (
                    SELECT vendor_driver.vdrv_drv_id,
                    GROUP_CONCAT(DISTINCT vendor_driver.vdrv_vnd_id SEPARATOR ',') as vendorIds
                    FROM  `vendor_driver`
                    INNER JOIN `vendors` ON vendors.vnd_id=vendor_driver.vdrv_vnd_id
					INNER JOIN `vendors` v1 ON vendors.vnd_ref_code=v1.vnd_id
                    WHERE vendor_driver.vdrv_active=1
                    GROUP BY vendor_driver.vdrv_vnd_id
                )drv ON drv.vdrv_drv_id=drivers.drv_id
                WHERE drivers.drv_approved IN (0,2,3)
                AND drivers.drv_active=1
                AND drivers.drv_lic_exp_date IS NOT NULL
                GROUP BY drivers.drv_id
                ORDER BY drivers.drv_created  DESC";
	return DBUtil::queryAll($sql);
    }

    public function findDisapproveList()
    {
	$sql = "SELECT
                        vendorIds,
                        drivers.drv_name,
                        drd_drv_id,
                        drivers.drv_approved,
                        drivers.drv_lic_exp_date,
                        drdIds,
                        cntDriver
                        FROM
                            `drivers`
                        INNER JOIN
                        (
                            SELECT driver_docs.drd_drv_id,
                                COUNT(1) AS cnt,
                                GROUP_CONCAT(driver_docs.drd_id SEPARATOR ',') AS drdIds
                            FROM  `driver_docs`
                            INNER JOIN `drivers` ON drivers.drv_id = driver_docs.drd_drv_id AND drivers.drv_active = 1  
							INNER JOIN drivers d2 ON d2.drv_id = drivers.drv_ref_code
                            WHERE driver_docs.drd_type = 4 AND driver_docs.drd_status = 2 AND driver_docs.drd_active = 1
                            GROUP BY driver_docs.drd_drv_id
                            HAVING
                                (cnt = 1)
                        ) ddocs
                        ON
                            ddocs.drd_drv_id = drivers.drv_id 
                        LEFT JOIN
                        (
                            SELECT vendor_driver.vdrv_drv_id,
                                GROUP_CONCAT(
                                    DISTINCT vendor_driver.vdrv_vnd_id SEPARATOR ','
                                ) AS vendorIds
                            FROM
                                `vendor_driver`
                            INNER JOIN `vendors` ON vendors.vnd_id = vendor_driver.vdrv_vnd_id
							INNER JOIN `vendors` v1 ON vendors.vnd_ref_code=v1.vnd_id
                            WHERE
                                vendor_driver.vdrv_active = 1
                            GROUP BY
                                vendor_driver.vdrv_vnd_id
                        ) drv
                        ON
                            drv.vdrv_drv_id = drivers.drv_id
                        LEFT JOIN
                        (
                            SELECT booking_cab.bcb_driver_id,
                                COUNT(1) AS cntDriver
                            FROM
                                `booking_cab`
                            INNER JOIN `booking` ON booking.bkg_bcb_id = booking_cab.bcb_id AND booking.bkg_active = 1
                            WHERE
                                booking_cab.bcb_active = 1 AND booking.bkg_status IN(6, 7)
                            GROUP BY
                                booking_cab.bcb_driver_id
                        ) bkg
                        ON
                            bkg.bcb_driver_id = drivers.drv_id
                        WHERE
                            drivers.drv_approved IN(0, 1, 2) AND drivers.drv_active = 1
                        HAVING
                            (cntDriver > 3)";
	return DBUtil::queryAll($sql);
    }

    public function missingDocsByDrdIds($drdIds)
    {
	$sql = "SELECT GROUP_CONCAT(missing SEPARATOR ',') as missing_docs FROM (
                    SELECT CONCAT(IF(driver_docs.drd_type=4,'License',''),
                        IF(driver_docs.drd_type=1,'Voter Card',''),
                        IF(driver_docs.drd_type=2,'Pan Card',''),
                        IF(driver_docs.drd_type=3,'Aadhar Card',''),
                        IF(driver_docs.drd_type=5,'Police Verification','')
                        ) as missing FROM `driver_docs` WHERE driver_docs.drd_id IN ($drdIds)
                    )a";
	return DBUtil::command($sql)->queryScalar();
    }

    public function updateExistingByIdType($drvId, $drvType, $drdSubType)
    {
	$sql		 = "UPDATE `driver_docs` SET driver_docs.drd_active=0 WHERE driver_docs.drd_drv_id=$drvId AND driver_docs.drd_type=$drvType";
	$sql		 .= ($drdSubType != '' && $drdSubType != NULL) ? " AND driver_docs.drd_sub_type=$drdSubType" : '';
	$cdb		 = DBUtil::command($sql);
	$rowsUpdated	 = $cdb->execute();
	return $rowsUpdated;
    }

    public function getUnapproved($arr = [], $command = false)
    {
	$where = '';
	if (trim($arr['drvname']) != '')
	{
	    $where .= "  AND LOWER(REPLACE(d2.drv_name,' ','')) LIKE '%" . strtolower(str_replace(' ', '', $arr['drvname'])) . "%'";
	}
	if (trim($arr['drd_type']) != '')
	{
	    $where .= "  AND drd.drd_type = " . $arr['drd_type'];
	}
	$sql		 = "SELECT drd.*, d2.*,
            if(bkg_id > 0, 1,0) hasBooking,
            if(bkg_pickup_date > NOW(),1,0) futureBooking,
                bkg_id
                FROM driver_docs drd
                JOIN drivers drv ON drv.drv_id = drd.drd_drv_id
				LEFT JOIN drivers d2 ON d2.drv_id = drv.drv_ref_code
                left JOIN booking_cab bcb ON bcb.bcb_driver_id = d2.drv_id AND bcb.bcb_id IS NOT NULL
                left JOIN booking bkg ON bcb.bcb_id = bkg.bkg_bcb_id
                WHERE drd_status = 0 AND drd_active = 1 AND
                drd_file IS NOT NULL AND
                drd_file <> ''
                $where
                    GROUP BY drd.drd_id
                ";
	$defaultOrder	 = 'futureBooking DESC,hasBooking DESC,bkg.bkg_pickup_date ASC, drd_created_at asc';

	if ($command == false)
	{

	    $count		 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
	    $dataprovider	 = new CSqlDataProvider($sql, [
		'totalItemCount' => $count,
		'sort'		 => ['attributes'	 => ['drvname'],
		    'defaultOrder'	 => $defaultOrder],
		'pagination'	 => ['pageSize' => 20],
	    ]);
	    return $dataprovider;
	}
	else
	{
	    return DBUtil::queryAll($sql);
	}
    }

    public function getDocType()
    {
	$list = $this->doctype;
	return $list[$this->drd_type];
    }

    public function getDocTypeList()
    {
	$list = $this->doctype;
	return $list;
    }

    public function getDocSubType()
    {
	$list = $this->docSubType;
	return $list[$this->drd_sub_type];
    }

    public function getDocsByDrvId($drvId)
    {
	$rows					 = $this->findAllByDrvId($drvId);
	$data['drv_voter_id_img_path']		 = $data['drv_voter_id_img_path2']		 = $data['drv_pan_img_path']		 = $data['drv_pan_img_path2']		 = $data['drv_aadhaar_img_path']		 = $data['drv_aadhaar_img_path2']		 = $data['drv_licence_path']		 = $data['drv_licence_path2']		 = $data['drv_police_certificate']		 = '';
	$data['drv_voter_id']			 = $data['drv_voter_back_id']		 = $data['drv_pan_id']			 = $data['drv_pan_back_id']		 = $data['drv_aadhaar_id']			 = $data['drv_aadhaar_back_id']		 = $data['drv_licence_id']			 = $data['drv_licence_back_id']		 = $data['drv_police_ver']			 = '';
	$data['drv_voter_id_status']		 = $data['drv_voter_back_id_status']	 = $data['drv_pan_status']			 = $data['drv_pan_back_status']		 = '';
	$data['drv_aadhaar_status']		 = $data['drv_aadhaar_back_status']	 = $data['drv_licence_status']		 = $data['drv_licence_back_status']	 = $data['drv_police_certificate_status']	 = '';
	if (count($rows) > 0)
	{
	    foreach ($rows as $row)
	    {
		if ($row['drd_type'] == 1 && $row['drd_sub_type'] == 1)
		{
		    $data['drv_voter_id']		 = array('drd_id' => $row['drd_id'], 'drd_file' => $row['drd_file'], 'drd_status' => $row['drd_status'], 'drd_remarks' => $row['drd_remarks']);
		    $data['drv_voter_id_img_path']	 = $row['drd_file'];
		    $data['drv_voter_id_status']	 = $row['drd_status'];
		}
		else if ($row['drd_type'] == 1 && $row['drd_sub_type'] == 2)
		{
		    $data['drv_voter_back_id']		 = array('drd_id' => $row['drd_id'], 'drd_file' => $row['drd_file'], 'drd_status' => $row['drd_status'], 'drd_remarks' => $row['drd_remarks']);
		    $data['drv_voter_id_img_path2']		 = $row['drd_file'];
		    $data['drv_voter_back_id_status']	 = $row['drd_status'];
		}
		else if ($row['drd_type'] == 2 && $row['drd_sub_type'] == 1)
		{
		    $data['drv_pan_id']		 = array('drd_id' => $row['drd_id'], 'drd_file' => $row['drd_file'], 'drd_status' => $row['drd_status'], 'drd_remarks' => $row['drd_remarks']);
		    $data['drv_pan_img_path']	 = $row['drd_file'];
		    $data['drv_pan_status']		 = $row['drd_status'];
		}
		else if ($row['drd_type'] == 2 && $row['drd_sub_type'] == 2)
		{
		    $data['drv_pan_back_id']	 = array('drd_id' => $row['drd_id'], 'drd_file' => $row['drd_file'], 'drd_status' => $row['drd_status'], 'drd_remarks' => $row['drd_remarks']);
		    $data['drv_pan_img_path2']	 = $row['drd_file'];
		    $data['drv_pan_back_status']	 = $row['drd_status'];
		}
		else if ($row['drd_type'] == 3 && $row['drd_sub_type'] == 1)
		{
		    $data['drv_aadhaar_id']		 = array('drd_id' => $row['drd_id'], 'drd_file' => $row['drd_file'], 'drd_status' => $row['drd_status'], 'drd_remarks' => $row['drd_remarks']);
		    $data['drv_aadhaar_img_path']	 = $row['drd_file'];
		    $data['drv_aadhaar_status']	 = $row['drd_status'];
		}
		else if ($row['drd_type'] == 3 && $row['drd_sub_type'] == 2)
		{
		    $data['drv_aadhaar_back_id']	 = array('drd_id' => $row['drd_id'], 'drd_file' => $row['drd_file'], 'drd_status' => $row['drd_status'], 'drd_remarks' => $row['drd_remarks']);
		    $data['drv_aadhaar_img_path2']	 = $row['drd_file'];
		    $data['drv_aadhaar_back_status'] = $row['drd_status'];
		}
		else if ($row['drd_type'] == 4 && $row['drd_sub_type'] == 1)
		{
		    $data['drv_licence_id']		 = array('drd_id' => $row['drd_id'], 'drd_file' => $row['drd_file'], 'drd_status' => $row['drd_status'], 'drd_remarks' => $row['drd_remarks'], 'drd_temp_approved' => $row['drd_temp_approved']);
		    $data['drv_licence_path']	 = $row['drd_file'];
		    $data['drv_licence_status']	 = $row['drd_status'];
		}
		else if ($row['drd_type'] == 4 && $row['drd_sub_type'] == 2)
		{
		    $data['drv_licence_back_id']	 = array('drd_id' => $row['drd_id'], 'drd_file' => $row['drd_file'], 'drd_status' => $row['drd_status'], 'drd_remarks' => $row['drd_remarks']);
		    $data['drv_licence_path2']	 = $row['drd_file'];
		    $data['drv_licence_back_status'] = $row['drd_status'];
		}
		else if ($row['drd_type'] == 5)
		{
		    $data['drv_police_ver']			 = array('drd_id' => $row['drd_id'], 'drd_file' => $row['drd_file'], 'drd_status' => $row['drd_status'], 'drd_remarks' => $row['drd_remarks']);
		    $data['drv_police_certificate']		 = $row['drd_file'];
		    $data['drv_police_certificate_status']	 = $row['drd_status'];
		}
	    }
	}
	return $data;
    }

    public function listDocsByDrvId($drvId)
    {
	$docs = $this->getDocsByDrvId($drvId);

	$voterId	 = $docs['drv_voter_id']['drd_id'];
	$voterDoc	 = $docs['drv_voter_id']['drd_file'];
	$voterStatus	 = $docs['drv_voter_id']['drd_status'];
	$voterRemarks	 = $docs['drv_voter_id']['drd_remarks'];

	$voterBackId		 = $docs['drv_voter_back_id']['drd_id'];
	$voterBackDoc		 = $docs['drv_voter_back_id']['drd_file'];
	$voterBackStatus	 = $docs['drv_voter_back_id']['drd_status'];
	$voterBackRemarks	 = $docs['drv_voter_back_id']['drd_remarks'];

	$panId		 = $docs['drv_pan_id']['drd_id'];
	$panDoc		 = $docs['drv_pan_id']['drd_file'];
	$panStatus	 = $docs['drv_pan_id']['drd_status'];
	$panRemarks	 = $docs['drv_pan_id']['drd_remarks'];

	$panBackId	 = $docs['drv_pan_back_id']['drd_id'];
	$panBackDoc	 = $docs['drv_pan_back_id']['drd_file'];
	$panBackStatus	 = $docs['drv_pan_back_id']['drd_status'];
	$panBackRemarks	 = $docs['drv_pan_back_id']['drd_remarks'];

	$aadharId	 = $docs['drv_aadhaar_id']['drd_id'];
	$aadharDoc	 = $docs['drv_aadhaar_id']['drd_file'];
	$aadharStatus	 = $docs['drv_aadhaar_id']['drd_status'];
	$aadharRemarks	 = $docs['drv_aadhaar_id']['drd_remarks'];

	$aadharBackId		 = $docs['drv_aadhaar_back_id']['drd_id'];
	$aadharBackDoc		 = $docs['drv_aadhaar_back_id']['drd_file'];
	$aadharBackStatus	 = $docs['drv_aadhaar_back_id']['drd_status'];
	$aadharBackRemarks	 = $docs['drv_aadhaar_back_id']['drd_remarks'];

	$driverLicenseId	 = $docs['drv_licence_id']['drd_id'];
	$driverLicenseDoc	 = $docs['drv_licence_id']['drd_file'];
	$driverLicenseStatus	 = $docs['drv_licence_id']['drd_status'];
	$driverLicenseRemarks	 = $docs['drv_licence_id']['drd_remarks'];

	$driverLicenseId2	 = $docs['drv_licence_back_id']['drd_id'];
	$driverLicenseDoc2	 = $docs['drv_licence_back_id']['drd_file'];
	$driverLicenseStatus2	 = $docs['drv_licence_back_id']['drd_status'];
	$driverLicenseRemarks2	 = $docs['drv_licence_back_id']['drd_remarks'];

	$pcVerificationId	 = $docs['drv_police_ver']['drd_id'];
	$pcVerificationDoc	 = $docs['drv_police_ver']['drd_file'];
	$pcVerificationStatus	 = $docs['drv_police_ver']['drd_status'];
	$pcVerificationRemarks	 = $docs['drv_police_ver']['drd_remarks'];

	$aadharApproveStyle	 = ($aadharDoc != '' && $aadharStatus == 0) ? "display:block;" : "display:none;";
	$aadharRejectStyle	 = ($aadharDoc != '' && $aadharStatus < 2) ? "display:block;" : "display:none;";
	$aadharReloadStyle	 = ($aadharDoc != '' && $aadharStatus == 2) ? "display:block;" : "display:none;";
	if ($aadharDoc != '')
	{
	    if ($aadharStatus == 0)
	    {
		$aadhar = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	    }
	    else if ($aadharStatus == 1)
	    {
		$aadhar = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	    }
	    else if ($aadharStatus == 2)
	    {
		$aadhar = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	    }
	}
	else
	{
	    $aadhar = '';
	}

	$aadharApproveStyle2	 = ($aadharBackDoc != '' && $aadharBackStatus == 0) ? "display:block;" : "display:none;";
	$aadharRejectStyle2	 = ($aadharBackDoc != '' && $aadharBackStatus < 2) ? "display:block;" : "display:none;";
	$aadharReloadStyle2	 = ($aadharBackDoc != '' && $aadharBackStatus == 2) ? "display:block;" : "display:none;";
	if ($aadharBackDoc != '')
	{
	    if ($aadharBackDoc == 0)
	    {
		$aadhar2 = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	    }
	    else if ($aadharBackDoc == 1)
	    {
		$aadhar2 = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	    }
	    else if ($aadharBackDoc == 2)
	    {
		$aadhar2 = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	    }
	}
	else
	{
	    $aadhar2 = '';
	}

	$panApproveStyle = ($panDoc != '' && $panStatus == 0) ? "display:block;" : "display:none;";
	$panRejectStyle	 = ($panDoc != '' && $panStatus < 2) ? "display:block;" : "display:none;";
	$panReloadStyle	 = ($panDoc != '' && $panStatus == 2) ? "display:block;" : "display:none;";
	if ($panDoc != '')
	{
	    if ($panStatus == 0)
	    {
		$pan = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	    }
	    else if ($panStatus == 1)
	    {
		$pan = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	    }
	    else if ($panStatus == 2)
	    {
		$pan = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	    }
	}
	else
	{
	    $pan = '';
	}

	$panApproveStyle2	 = ($panBackDoc != '' && $panBackStatus == 0) ? "display:block;" : "display:none;";
	$panRejectStyle2	 = ($panBackDoc != '' && $panBackStatus < 2) ? "display:block;" : "display:none;";
	$panReloadStyle2	 = ($panBackDoc != '' && $panBackStatus == 2) ? "display:block;" : "display:none;";
	if ($panBackDoc != '')
	{
	    if ($panBackDoc == 0)
	    {
		$pan2 = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	    }
	    else if ($panBackDoc == 1)
	    {
		$pan2 = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	    }
	    else if ($panBackDoc == 2)
	    {
		$pan2 = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	    }
	}
	else
	{
	    $pan2 = '';
	}

	$voterApproveStyle	 = ($voterDoc != '' && $voterStatus == 0) ? "display:block;" : "display:none;";
	$voterRejectStyle	 = ($voterDoc != '' && $voterStatus < 2) ? "display:block;" : "display:none;";
	$voterReloadStyle	 = ($voterDoc != '' && $voterStatus == 2) ? "display:block;" : "display:none;";
	if ($voterDoc != '')
	{
	    if ($voterStatus == 0)
	    {
		$voter = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	    }
	    else if ($voterStatus == 1)
	    {
		$voter = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	    }
	    else if ($voterStatus == 2)
	    {
		$voter = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	    }
	}
	else
	{
	    $voter = '';
	}

	$voterApproveStyle2	 = ($voterBackDoc != '' && $voterBackStatus == 0) ? "display:block;" : "display:none;";
	$voterRejectStyle2	 = ($voterBackDoc != '' && $voterBackStatus < 2) ? "display:block;" : "display:none;";
	$voterReloadStyle2	 = ($voterBackDoc != '' && $voterBackStatus == 2) ? "display:block;" : "display:none;";
	if ($voterBackDoc != '')
	{
	    if ($voterBackStatus == 0)
	    {
		$voter2 = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	    }
	    else if ($voterBackStatus == 1)
	    {
		$voter2 = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	    }
	    else if ($voterBackStatus == 2)
	    {
		$voter2 = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	    }
	}
	else
	{
	    $voter2 = '';
	}

	$dlApproveStyle	 = ($driverLicenseDoc != '' && $driverLicenseStatus == 0) ? "display:block;" : "display:none;";
	$dlRejectStyle	 = ($driverLicenseDoc != '' && $driverLicenseStatus < 2) ? "display:block;" : "display:none;";
	$dlReloadStyle	 = ($driverLicenseDoc != '' && $driverLicenseStatus == 2) ? "display:block;" : "display:none;";
	if ($driverLicenseDoc != '')
	{
	    if ($driverLicenseStatus == 0)
	    {
		$dl = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	    }
	    else if ($driverLicenseStatus == 1)
	    {
		$dl = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	    }
	    else if ($driverLicenseStatus == 2)
	    {
		$dl = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	    }
	}
	else
	{
	    $dl = '';
	}

	$dlApproveStyle2 = ($driverLicenseDoc2 != '' && $driverLicenseStatus2 == 0) ? "display:block;" : "display:none;";
	$dlRejectStyle2	 = ($driverLicenseDoc2 != '' && $driverLicenseStatus2 < 2) ? "display:block;" : "display:none;";
	$dlReloadStyle2	 = ($driverLicenseDoc2 != '' && $driverLicenseStatus2 == 2) ? "display:block;" : "display:none;";
	if ($driverLicenseDoc2 != '')
	{
	    if ($driverLicenseStatus2 == 0)
	    {
		$dl2 = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	    }
	    else if ($driverLicenseStatus2 == 1)
	    {
		$dl2 = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	    }
	    else if ($driverLicenseStatus2 == 2)
	    {
		$dl2 = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	    }
	}
	else
	{
	    $dl2 = '';
	}

	$pcApproveStyle	 = ($pcVerificationDoc != '' && $pcVerificationStatus == 0) ? "display:block;" : "display:none;";
	$pcRejectStyle	 = ($pcVerificationDoc != '' && $pcVerificationStatus < 2) ? "display:block;" : "display:none;";
	$pcReloadStyle	 = ($pcVerificationDoc != '' && $pcVerificationStatus == 2) ? "display:block;" : "display:none;";
	if ($pcVerificationDoc != '')
	{
	    if ($pcVerificationStatus == 0)
	    {
		$pc = ['class' => 'label label-info', 'style' => 'display:block;', 'level' => 'Not Approved'];
	    }
	    else if ($pcVerificationStatus == 1)
	    {
		$pc = ['class' => 'label label-success', 'style' => 'display:block;', 'level' => 'Approved'];
	    }
	    else if ($pcVerificationStatus == 2)
	    {
		$pc = ['class' => 'label label-danger', 'style' => 'display:block;', 'level' => 'Rejected'];
	    }
	}
	else
	{
	    $pc = '';
	}

	$rtnArr = ['voter_id'		 =>
	    ['voterId' => $voterId, 'voterApproveStyle' => $voterApproveStyle, 'voterRejectStyle' => $voterRejectStyle, 'voterReloadStyle' => $voterReloadStyle, 'voter' => $voter],
	    'voter_back_id'		 =>
	    ['voterBackId' => $voterBackId, 'voterApproveStyle2' => $voterApproveStyle2, 'voterRejectStyle2' => $voterRejectStyle2, 'voterReloadStyle2' => $voterReloadStyle2, 'voter2' => $voter2],
	    'pan_id'		 =>
	    ['panId' => $panId, 'panApproveStyle' => $panApproveStyle, 'panRejectStyle' => $panRejectStyle, 'panReloadStyle' => $panReloadStyle, 'pan' => $pan],
	    'pan_back_id'		 =>
	    ['panBackId' => $panBackId, 'panApproveStyle2' => $panApproveStyle2, 'panRejectStyle2' => $panRejectStyle2, 'panReloadStyle2' => $panReloadStyle2, 'pan2' => $pan2],
	    'aadhar_id'		 =>
	    ['aadharId' => $aadharId, 'aadharApproveStyle' => $aadharApproveStyle, 'aadharRejectStyle' => $aadharRejectStyle, 'aadharReloadStylee' => $aadharReloadStyle, 'aadhar' => $aadhar],
	    'aadhar_back_id'	 =>
	    ['aadharBackId' => $aadharBackId, 'aadharApproveStyle2' => $aadharApproveStyle2, 'aadharRejectStyle2' => $aadharRejectStyle2, 'aadharReloadStylee2' => $aadharReloadStyle2, 'aadhar2' => $aadhar2],
	    'dl_id'			 =>
	    ['driverLicenseId' => $driverLicenseId, 'dlApproveStyle' => $dlApproveStyle, 'dlRejectStyle' => $dlRejectStyle, 'dlReloadStyle' => $dlReloadStyle, 'dl' => $dl],
	    'dl_back_id'		 =>
	    ['driverLicenseId2' => $driverLicenseId2, 'dlApproveStyle2' => $dlApproveStyle2, 'dlRejectStyle2' => $dlRejectStyle2, 'dlReloadStyle2' => $dlReloadStyle2, 'dl2' => $dl2],
	    'pc_verification'	 =>
	    ['pcVerificationId' => $pcVerificationId, 'pcApproveStyle' => $pcApproveStyle, 'pcRejectStyle' => $pcRejectStyle, 'pcReloadStyle' => $pcReloadStyle, 'dl2' => $pc]
	];
	return $rtnArr;
    }

    public function setTypeByDocumentType($doc_type)
    {
	switch ($doc_type)
	{
	    case 'voterid':
		$this->drd_type		 = 1;
		$this->drd_sub_type	 = 1;
		break;
	    case 'voterbackid':
		$this->drd_type		 = 1;
		$this->drd_sub_type	 = 2;
		break;
	    case 'pan':
		$this->drd_type		 = 2;
		$this->drd_sub_type	 = 1;
		break;
	    case 'panback':
		$this->drd_type		 = 2;
		$this->drd_sub_type	 = 2;
		break;
	    case 'aadhar':
		$this->drd_type		 = 3;
		$this->drd_sub_type	 = 1;
		break;
	    case 'aadharback':
		$this->drd_type		 = 3;
		$this->drd_sub_type	 = 2;
		break;
	    case 'license':
		$this->drd_type		 = 4;
		$this->drd_sub_type	 = 1;
		break;
	    case 'licenseback':
		$this->drd_type		 = 4;
		$this->drd_sub_type	 = 2;
		break;
	    case 'policever':
		$this->drd_type		 = 5;
		$this->drd_sub_type	 = NULL;
		break;
	}
    }

    public function saveDocument($driverId, $path, UserInfo $userInfo = null, $doc_type = null, $tempLicenceApprove = 0)
    {
	$success = false;
	if ($path != '' && $driverId != '')
	{
		$this->setTypeByDocumentType($doc_type);
	    $this->updateExistingByIdType($driverId, $this->drd_type, $this->drd_sub_type);
	    $this->drd_drv_id	 = $driverId;
	    $this->drd_file		 = $path;
	    $this->drd_status	 = 0;
		
	    $this->drd_active	 = 1;
	    $this->drd_appoved_at	 = NULL;
	    $this->drd_approve_by	 = NULL;
		if($tempLicenceApprove == 1 && in_array($doc_type,['license']))
		{				
			$this->drd_temp_approved       = $tempLicenceApprove;
			$this->drd_temp_approved_at	   = date('Y-m-d H:i:s');		
		}
		
	    if ($this->validate())
	    {
		if ($this->save())
		{
		    $success	 = true;
		    $event_id	 = DriversLog::DRIVER_FILE_UPLOAD;
		    $logArray	 = DriversLog::model()->getLogByDocumentType($doc_type);
		    $logDesc	 = DriversLog::model()->getEventByEventId($logArray['upload']);
		    DriversLog::model()->createLog($driverId, $logDesc, $userInfo, $event_id, false, false);
		}
		else
		{
		    $success = false;
		}
	    }
	    else
	    {
		$success = false;
		$errors	 = $this->getErrors();
	    }
	}
	return $success;
    }

    public function rejectDocument($drd_id, $vendor_ids = '', $userInfo = null)
    {
	$transaction = Yii::app()->db->beginTransaction();
	try
	{
	    $success		 = false;
	    $modeld			 = DriverDocs::model()->findByPk($drd_id);
	    $msgData		 = $this->getMsgByType($modeld->drd_type, $modeld->drd_sub_type);
	    $modeld->drd_status	 = 2;
	    $modeld->drd_remarks	 = $msgData['remarks'];
	    if ($modeld->save())
	    {
		DriversLog::model()->createLog($modeld->drd_drv_id, $modeld->drd_remarks, $userInfo, $msgData['event_id'], false, false);
		if ($vendor_ids != '')
		{
            $vendors = Vendors::getVendorsByIds($vendor_ids);
		    //$vendors = explode(',', $vendor_ids);
		    if (count($vendors) > 0)
		    {
			$drvModel = Drivers::model()->findByPk($modeld->drd_drv_id);
			foreach ($vendors as $val)
			{
			    if (isset($val['vnd_id']) && $val['vnd_id'] > 0)
			    {
				$isLastLogin	 = AppTokens::model()->checkVendorLastLogin($val['vnd_id']);
				$message	 = " Document (" . $msgData['doc'] . ") for Driver " . $drvModel->drv_name . " has been rejected (" . $msgData['remarks'] . "). Please verify and re-upload document properly";
								if ($isLastLogin == 1)
				{
				    $payLoadData	 = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
				    $success	 = AppTokens::model()->notifyVendor($val['vnd_id'], $payLoadData, $message, "LICENSE PAPER REJECTED");
				    Logger::create("Notification->" . $message, CLogger::LEVEL_INFO);
				}

				//$venModel	 = Vendors::model()->findByPk($vndId);
				$vendorName	 = ($val['vnd_owner'] != '') ? $val['vnd_owner'] . ',' : $val['vnd_name'] . ',';
				$smsMessage	 = "Dear " . $vendorName . $message . ' - Gozocabs';
								$sms		 = new smsWrapper();
				$sms->sendAlertMessageVendor(91, $val['vnd_id'], $smsMessage, SmsLog::SMS_VENDOR_DRIVER_PAPER_REJECTED);
				Logger::create("Sms->" . $smsMessage, CLogger::LEVEL_INFO);
			    }
			}
		    }
		}
		
		$success = true;
		if ($success == true)
		{
		    $desc = $modeld->drd_drv_id . " ### " . $msgData['doc'] . " Rejected\n";
		    Logger::create($desc, CLogger::LEVEL_INFO);
		    $transaction->commit();
		}
	    }
	    else
	    {
		throw new Exception("Reject document not yet saved.\n\t\t" . json_encode($modeld->getErrors()));
	    }
	}
	catch (Exception $e)
	{
	    Logger::create("Not Reject.\n\t\t" . $e->getMessage(), CLogger::LEVEL_ERROR);
	    $transaction->rollback();
	}
	return $success;
    }

    public function checkApproveDocByDrvId($drv_id, $drv_type)
    {
	$this->setTypeByDocumentType($drv_type);
	$type		 = $this->drd_type;
	$sub_type	 = $this->drd_sub_type;
	$sql		 = "SELECT
                    IF(driver_docs.drd_id>0,1,0) as check_approve
                    FROM
                    driver_docs
                    WHERE
                    driver_docs.drd_drv_id = $drv_id 
                    AND driver_docs.drd_type = $type 
                    AND driver_docs.drd_active = 1 
                    AND driver_docs.drd_status = 1";
	$sql		 .= ($type != 5) ? ' AND driver_docs.drd_sub_type = ' . $sub_type . '' : '';
	$valApprove	 = DBUtil::command($sql)->queryScalar();
	$valApprove	 = ($valApprove > 0) ? $valApprove : 0;
	return $valApprove;
    }

    public function getUnapprovedDoc($drvId)
    {
	$listDocs		 = DriverDocs::model()->findAllByDrvId($drvId);
	$voterStatus		 = $panStatus		 = $aadharStatus		 = $driLicenceStatus	 = $policeVerfStatus	 = 0;
	$count = 1;
	if (count($listDocs) > 0)
	{
	    foreach ($listDocs as $doc)
	    {
		
		switch ($doc['drd_type'])
		{
		    case 4:
			if (($doc['drd_status'] == 0 || $doc['drd_status'] == 1) && $doc['drd_sub_type'] == 1)
			{
			    $driLicenceStatus	 = 1;
			    $count			 = ($count - 1);
			}
			break;
		}
	    }
	}
	return ['count' => $count, 'doc' => [ 'license' => $driLicenceStatus]];
    }

    public function getListReadyApproval()
    {
	$sql = "SELECT
					drv_id,
					totalDocScore,
					updateDocScore
					FROM
						`drivers`
					INNER JOIN(
						SELECT
							driver_docs.drd_drv_id,
							COUNT(DISTINCT driver_docs.drd_id) AS totalDocScore,
							SUM(
								IF(
									driver_docs.drd_file <> '' AND driver_docs.drd_status = 0 AND(
										(
											driver_docs.drd_type = 1 AND driver_docs.drd_sub_type = 1
										) OR(
											driver_docs.drd_type = 4 AND driver_docs.drd_sub_type = 1
										)
									),
									1,
									0
								)
							) AS updateDocScore
						FROM
							`driver_docs`
						WHERE
							driver_docs.drd_active = 1
						GROUP BY
							driver_docs.drd_drv_id
					) AS doc
					ON
						doc.drd_drv_id = drivers.drv_id 
					WHERE
						drivers.drv_active > 0
					ORDER BY
						updateDocScore
					DESC";
	return DBUtil::queryAll($sql);
    }

    public function instantReadyForApproval($drvId, $docScore)
    {
	$userInfo = UserInfo::getInstance();
	if ($drvId > 0)
	{
	    $transaction = DBUtil::beginTransaction();
	    try
	    {
		$dmodel = DriverStats::model()->getbyDriverId($drvId);
		if (!$dmodel)
		{
		    $dmodel			 = new DriverStats();
		    $dmodel->scenario	 = 'updateReadyApproval';
		    $dmodel->drs_drv_id	 = $drvId;
		    $dmodel->drs_active	 = 1;
		}
		$dmodel->drs_doc_score = $docScore;
		if ($dmodel->validate())
		{
		    if ($dmodel->save())
		    {
			$success = DBUtil::commitTransaction($transaction);
			if ($success)
			{
			    $updateData = "Ready for approval Driver Id :: " . $dmodel->drs_drv_id . " - R4A Score :: " . $dmodel->drs_doc_score;
			    echo $updateData . "\n";
			    Logger::create('CODE DATA ===========>: ' . $updateData, CLogger::LEVEL_INFO);
			}
		    }
		    else
		    {
			$getErrors = $dmodel->getErrors();
			throw new Exception("DriverStats validation failed :: " . json_encode($getErrors));
		    }
		}
		else
		{
		    $getErrors = $dmodel->getErrors();
		    throw new Exception("DriverStats validation failed :: " . json_encode($getErrors));
		}
	    }
	    catch (Exception $ex)
	    {
		DBUtil::rollbackTransaction($transaction);
		Logger::create('ERRORS =====> : ' . " Errors :" . json_encode($getErrors), CLogger::LEVEL_ERROR);
	    }
	}
    }
	

}

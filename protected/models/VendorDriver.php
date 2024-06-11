<?php

/**
 * This is the model class for table "vendor_driver".
 *
 * The followings are the available columns in table 'vendor_driver':
 * @property integer $vdrv_id
 * @property integer $vdrv_vnd_id
 * @property integer $vdrv_drv_id
 * @property integer $vdrv_active
 * @property string $vdrv_created
 *
 * The followings are the available model relations:
 * @property Vendors $vdrvVnd
 * @property Drivers $vdrvDrv
 */
class VendorDriver extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vendor_driver';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vdrv_vnd_id, vdrv_drv_id', 'required'),
			array('vdrv_vnd_id, vdrv_drv_id, vdrv_active', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vdrv_id, vdrv_vnd_id, vdrv_drv_id, vdrv_active, vdrv_created', 'safe', 'on' => 'search'),
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
			'vdrvVnd'	 => array(self::BELONGS_TO, 'Vendors', 'vdrv_vnd_id'),
			'vdrvDrv'	 => array(self::BELONGS_TO, 'Drivers', 'vdrv_drv_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'vdrv_id'		 => 'ID',
			'vdrv_vnd_id'	 => 'Vendor ID',
			'vdrv_drv_id'	 => 'Driver ID',
			'vdrv_active'	 => 'Active',
			'vdrv_created'	 => 'Created',
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

		$criteria->compare('vdrv_id', $this->vdrv_id);
		$criteria->compare('vdrv_vnd_id', $this->vdrv_vnd_id);
		$criteria->compare('vdrv_drv_id', $this->vdrv_drv_id);
		$criteria->compare('vdrv_active', $this->vdrv_active);
		$criteria->compare('vdrv_created', $this->vdrv_created, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VendorDriver the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function checkExisting($data = [])
	{
		$criteria	 = new CDbCriteria;
		$criteria->compare('vdrv_vnd_id', $data['vendor']);
		$criteria->compare('vdrv_drv_id', $data['driver']);
		$exist		 = $this->find($criteria);

		return $exist;
	}

	public function checkAndSave($data = [])
	{
		$exist = $this->checkExisting($data);
		if (!$exist)
		{
			$model				 = new VendorDriver();
			$model->vdrv_drv_id	 = $data['driver'];
			$model->vdrv_vnd_id	 = $data['vendor'];
			if ($model->save())
			{
				return true;
			}
		}
		else
		{
			$exist->vdrv_active = 1;
			if ($exist->save())
			{
				return true;
			}
		}
		return false;
	}

	public function getVendorListbyDriverid($drvid, $search_txt = "")
	{
		$sql		 = "select distinct(vnd_id),vdrv_vnd_id,vnd_name,vnd_code from
				vendor_driver LEFT JOIN vendors on vdrv_vnd_id=vnd_id 
				where  vdrv_drv_id  in (SELECT d3.drv_id FROM drivers d1
          INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
          INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
          WHERE d1.drv_id='$drvid') ";
		$sql		 .= $search_txt != "" ? " and vnd_name like '%$search_txt%'" : "";
		$recordset	 = DBUtil::queryAll($sql, DBUtil::MDB());
		return $recordset;
	}

	public static function getDriverListbyVendorid($vndIds, $onlyDataSet = false)
	{
		$params	 = [];
		$sql	 = "Select distinct d.drv_id,d.drv_name,d.drv_code,phn_phone_no AS drv_phone,d.drv_is_freeze, cp.cr_contact_id, d.drv_contact_id, 
						IF(d.drv_active=1,'Active','InActive') AS drv_active 
						FROM vendor_driver 
						INNER JOIN drivers d ON vdrv_drv_id = drv_id AND vdrv_active = 1 AND drv_id = drv_ref_code 
						INNER JOIN contact_profile AS cp on cp.cr_is_driver = drv_id and cp.cr_status=1 
						INNER JOIN contact ON contact.ctt_id = cp.cr_contact_id and contact.ctt_active =1 and contact.ctt_id = contact.ctt_ref_code 
						LEFT JOIN contact_phone ON contact_phone.phn_contact_id = contact.ctt_id AND contact_phone.phn_active=1
						WHERE vdrv_vnd_id IN ($vndIds) 
						Group By drv_ref_code";
		if ($onlyDataSet)
		{
			$recordset = DBUtil::query($sql, DBUtil::SDB());
			return $recordset;
		}
		else
		{
			$recordset = DBUtil::queryAll($sql, DBUtil::SDB(), $params);
			return $recordset;
		}
	}

	public function findVndIdsByDrvId($drvId, $status = "")
	{
		$sql			 = "SELECT GROUP_CONCAT(vendor_driver.vdrv_vnd_id SEPARATOR ',') as vendor_ids FROM `vendor_driver` WHERE vendor_driver.vdrv_drv_id in (SELECT d3.drv_id FROM drivers d1
          INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
          INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
          WHERE d1.drv_id='$drvId') ";
		$sql			 .= $status != "" ? " and vendor_driver.vdrv_active=$status" : "";
		$recordVndIds	 = DBUtil::command($sql, DBUtil::MDB())->queryScalar();
		return $recordVndIds;
	}

	public function getActiveVendorListbyDriverId($drvid)
	{
		$sql		 = "Select distinct(vnd.vnd_id),vnd.vnd_name
					FROM vendor_driver vdrv
					LEFT JOIN vendors vnd on vdrv.vdrv_vnd_id=vnd.vnd_id AND vnd.vnd_id = vnd.vnd_ref_code
					LEFT JOIN contact_profile cp ON cp.cr_is_vendor = vnd.vnd_id AND cp.cr_status =1
					LEFT JOIN contact cntp ON cntp.ctt_id = cp.cr_contact_id AND cntp.ctt_id = cntp.ctt_ref_code AND cntp.ctt_active =1
					LEFT JOIN vendor_pref vndprf ON vnd.vnd_id = vndprf.vnp_vnd_id
					WHERE cntp.ctt_active = 1 AND vndprf.vnp_is_freeze = 0 AND vdrv.vdrv_drv_id in (SELECT d3.drv_id FROM drivers d1
					INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
					INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
					WHERE d1.drv_id='$drvid') AND vdrv.vdrv_active = 1";
		$recordset	 = DBUtil::queryAll($sql);
		return $recordset;
	}

	public function unlinkByVendorDriverId($vdrvId)
	{
		$success = false;
		$model	 = VendorDriver::model()->findByPk($vdrvId);

		if ($model->vdrv_active == 1)
		{
			$model->vdrv_active = 0;
			$model->save();
			if ($model->save())
			{
				$userInfo	 = UserInfo::getInstance();
				$descDriver	 = DriversLog::model()->getEventByEventId(DriversLog::DRIVER_VENDOR_DELETE);
				$descVendor	 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_DRIVER_DELETE);
				DriversLog::model()->createLog($model->vdrv_drv_id, $descDriver, $userInfo, DriversLog::DRIVER_VENDOR_DELETE, false, false);
				VendorsLog::model()->createLog($model->vdrv_vnd_id, $descVendor, $userInfo, VendorsLog::VENDOR_DRIVER_DELETE, false, false);
				$success	 = true;
			}
		}
		return $success;
	}

	public static function unlinkDriver($drvId)
	{
		$param	 = ['drvId' => $drvId];
		$sql	 = "UPDATE `vendor_driver` SET`vdrv_active`=0 WHERE `vdrv_drv_id` =:drvId";
		DBUtil::execute($sql, $param);
	}

	public static function getVndByDrvId($drvIds)
	{
		DBUtil::getINStatement($drvIds, $bindString, $params);
		$sql = "SELECT GROUP_CONCAT(DISTINCT vdrv_vnd_id SEPARATOR ',' ) AS vendorIds FROM vendor_driver
                WHERE 1 AND vdrv_active=1 AND vdrv_drv_id IN ($bindString)";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
	}

	/**
	 * @param integer $vndId
	 * @param integer $drvId
	 * @return array
	 */
	public static function getLinking($vndId, $drvId)
	{
		$params	 = ['vndId' => $vndId, 'drvId' => $drvId];
		$sql	 = "Select d.drv_id,d.drv_name,d.drv_code,vdrv_vnd_id
						FROM vendor_driver 
						INNER JOIN drivers d ON vdrv_drv_id = drv_id 											
						WHERE vdrv_active = 1 AND vdrv_vnd_id =:vndId AND vdrv_drv_id =:drvId";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $result;
	}

	/**
	 * count of driver for particular vendor
	 * @param type $vndId
	 * @return type
	 */
	public static function getDriverCountbyVendorid($vndId)
	{
		$params		 = ['vndId' => $vndId];
		$sql		 = "Select count(distinct(d.drv_id))
						FROM vendor_driver 
						INNER JOIN drivers d ON vdrv_drv_id = drv_id AND vdrv_active = 1 AND drv_id = drv_ref_code AND d.drv_active=1 AND d.drv_approved = 1
						INNER JOIN contact_profile AS cp on cp.cr_is_driver = drv_id and cp.cr_status=1 
						INNER JOIN contact ON contact.ctt_id = cp.cr_contact_id and contact.ctt_active =1 and contact.ctt_id = contact.ctt_ref_code 
						LEFT JOIN contact_phone ON contact_phone.phn_contact_id = contact.ctt_id AND contact_phone.phn_active=1
						WHERE vdrv_vnd_id =:vndId ";
		$recordset	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $recordset;
	}

	/**
	 * total number of drivers of particular vendor
	 * @param type $vndIds
	 * @return type
	 */
	public static function totalDriver($vndIds)
	{
		$params	 = ['vndId' => $vndIds];
		$sql	 = "SELECT count(distinct(vendor_driver.vdrv_drv_id)) FROM vendor_driver 
					INNER JOIN drivers ON drivers.drv_id=vendor_driver.vdrv_drv_id AND drivers.drv_active =1
					INNER JOIN vendors ON vendors.vnd_id = vendor_driver.vdrv_vnd_id 
					WHERE vendor_driver.vdrv_vnd_id IN (:vndId)
					AND vendor_driver.vdrv_active=1
					GROUP BY vendors.vnd_ref_code";
		$res	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);

		return $res;
	}

	/**
	 * number of rejected drivers of particular vendor
	 * @param type $vndIds
	 * @return type
	 */
	public static function rejectedDriver($vndIds)
	{
		$params	 = ['vndId' => $vndIds];
		$sql	 = "SELECT COUNT(DISTINCT vendor_driver.vdrv_drv_id) AS is_driver
                FROM `vendor_driver`
                INNER JOIN `vendors` ON vendors.vnd_id = vendor_driver.vdrv_vnd_id 
                INNER JOIN `drivers` ON drivers.drv_id= vendor_driver.vdrv_drv_id AND drivers.drv_approved =3 AND drivers.drv_active =1
                WHERE vendor_driver.vdrv_active = 1 AND vendors.vnd_id IN (:vndId) group by vendors.vnd_ref_code";

		$res = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);

		return $res;
	}

	public static function getVndDrvId($drvId, $vendorId)
	{
		$params	 = ['vndId' => $vendorId, 'drvId' => $drvId];
		$sql	 = "SELECT vdrv_id AS vendorDriverId FROM vendor_driver
                WHERE 1 AND vdrv_active=1 AND vdrv_drv_id  =:drvId AND vdrv_vnd_id =:vndId ";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
	}

	public static function checkApprovalStatus($vndId)
	{
		$approvalStatus	 = 1;
		$vndModel		 = Vendors::model()->findByPk($vndId);
		$active			 = $vndModel->vnd_active;
		$driverCount	 = VendorDriver::getDriverCountbyVendorid($vndId); //According to AK no driver checking needed for login.

		if ($vndModel->vnd_active != 1)
		{
			$approvalStatus = 0;
		}

		return $approvalStatus;
	}
}

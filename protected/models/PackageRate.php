<?php

/**
 * This is the model class for table "package_rate".
 *
 * The followings are the available columns in table 'package_rate':
 * @property integer $prt_id
 * @property integer $prt_pck_id
 * @property integer $prt_package_rate
 * @property integer $prt_state_tax
 * @property integer $prt_toll_tax
 * @property integer $prt_driver_allowance
 * @property string $prt_vendor_amt
 * @property string $prt_rate_per_km
 * @property integer $prt_isIncluded
 * @property integer $prt_package_cab_type
 * @property integer $prt_trip_type
 * @property string $prt_package_valid_from
 * @property string $prt_package_valid_to
 * @property integer $prt_isParkingIncluded
 * @property integer $prt_parking
 * @property string $prt_comment
 * @property integer $prt_status
 * @property string $prt_created_dt
 * @property string $prt_modified_dt
 * @property string $prt_log
 *
 * The followings are the available model relations:
 * @property Package $prtPck
 */
class PackageRate extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'package_rate';
	}

	public $locale_prt_package_valid_from, $locale_prt_package_valid_to;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('prt_trip_type,prt_package_rate,prt_vendor_amt,prt_driver_allowance', 'required'),
			array('prt_pck_id, prt_package_rate, prt_state_tax, prt_toll_tax, prt_driver_allowance, prt_isIncluded, prt_package_cab_type, prt_trip_type, prt_isParkingIncluded, prt_parking, prt_status', 'numerical', 'integerOnly' => true),
			array('prt_vendor_amt, prt_rate_per_km', 'length', 'max' => 255),
			array('prt_comment', 'length', 'max' => 1000),
			array('prt_log', 'length', 'max' => 4000),
			array('prt_package_valid_from, prt_package_valid_to', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('prt_id, prt_pck_id, prt_package_rate, prt_state_tax, prt_toll_tax, prt_driver_allowance, prt_vendor_amt, prt_rate_per_km, prt_isIncluded, prt_package_cab_type, prt_trip_type, prt_package_valid_from, prt_package_valid_to, prt_isParkingIncluded, prt_parking, prt_comment, prt_status, prt_created_dt, prt_modified_dt, prt_log,locale_prt_package_valid_from,locale_prt_package_valid_to', 'safe'),
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
			'prtPck' => array(self::BELONGS_TO, 'Package', 'prt_pck_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'prt_id'				 => 'Prt',
			'prt_pck_id'			 => 'Prt Pck',
			'prt_package_rate'		 => 'Prt Package Rate',
			'prt_state_tax'			 => 'Prt State Tax',
			'prt_toll_tax'			 => 'Prt Toll Tax',
			'prt_driver_allowance'	 => 'Prt Driver Allowance',
			'prt_vendor_amt'		 => 'Prt Vendor Amt',
			'prt_rate_per_km'		 => 'Prt Rate Per Km',
			'prt_isIncluded'		 => 'Prt Is Included',
			'prt_package_cab_type'	 => 'Prt Package Cab Type',
			'prt_trip_type'			 => 'Prt Trip Type',
			'prt_package_valid_from' => 'Prt Package Valid From',
			'prt_package_valid_to'	 => 'Prt Package Valid To',
			'prt_isParkingIncluded'	 => 'Prt Is Parking Included',
			'prt_parking'			 => 'Prt Parking',
			'prt_comment'			 => 'Prt Comment',
			'prt_status'			 => 'Prt Status',
			'prt_created_dt'		 => 'Prt Created Dt',
			'prt_modified_dt'		 => 'Prt Modified Dt',
			'prt_log'				 => 'Prt Log',
		);
	}

	public function beforeSave()
	{
		if ($this->locale_prt_package_valid_from != null)
		{
			$this->prt_package_valid_from = DateTimeFormat::DatePickerToDate($this->locale_prt_package_valid_from);
		}

		if ($this->locale_prt_package_valid_to != null)
		{
			$this->prt_package_valid_to = DateTimeFormat::DatePickerToDate($this->locale_prt_package_valid_to);
		}

		return parent::beforeValidate();
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

		$criteria->compare('prt_id', $this->prt_id);
		$criteria->compare('prt_pck_id', $this->prt_pck_id);
		$criteria->compare('prt_package_rate', $this->prt_package_rate);
		$criteria->compare('prt_state_tax', $this->prt_state_tax);
		$criteria->compare('prt_toll_tax', $this->prt_toll_tax);
		$criteria->compare('prt_driver_allowance', $this->prt_driver_allowance);
		$criteria->compare('prt_vendor_amt', $this->prt_vendor_amt, true);
		$criteria->compare('prt_rate_per_km', $this->prt_rate_per_km, true);
		$criteria->compare('prt_isIncluded', $this->prt_isIncluded);
		$criteria->compare('prt_package_cab_type', $this->prt_package_cab_type);
		$criteria->compare('prt_trip_type', $this->prt_trip_type);
		$criteria->compare('prt_package_valid_from', $this->prt_package_valid_from, true);
		$criteria->compare('prt_package_valid_to', $this->prt_package_valid_to, true);
		$criteria->compare('prt_isParkingIncluded', $this->prt_isParkingIncluded);
		$criteria->compare('prt_parking', $this->prt_parking);
		$criteria->compare('prt_comment', $this->prt_comment, true);
		$criteria->compare('prt_status', $this->prt_status);
		$criteria->compare('prt_created_dt', $this->prt_created_dt, true);
		$criteria->compare('prt_modified_dt', $this->prt_modified_dt, true);
		$criteria->compare('prt_log', $this->prt_log, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PackageRate the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getList($package = '', $pkgRate = '')
	{
		$sql = "SELECT CONCAT(vct.vct_label,' (',scc.scc_label,')') as cabtype , pr.*, pck.*
                  FROM package_rate pr
                   LEFT JOIN package pck ON pr.prt_pck_id = pck.pck_id
				   INNER JOIN svc_class_vhc_cat scv ON scv.scv_id = pr.prt_package_cab_type
                   INNER JOIN service_class scc ON scc.scc_id = scv.scv_scc_id
                   INNER JOIN vehicle_category vct ON vct.vct_id = scv.scv_vct_id
                   WHERE prt_status = 1  ";

		if ($package != '')
		{
			$sql .= " AND pck_id = $package";
		}

		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['prt_pck_id'],
				'defaultOrder'	 => 'prt_pck_id ASC'],
			'pagination'	 => ['pageSize' => 20],
		]);
		return $dataprovider;
	}

	public function updatePackageRate($prt_id)
	{
		$sql = "UPDATE `package_rate` SET `prt_status`=0 WHERE `prt_pck_id`=$prt_id";
		/* @var $cdb CDbCommand */
		$res = DBUtil::command($sql)->execute();
		return $res;
	}

	public function getPackageRate($pckageID, $cabtypeid, $pickup_date = '')
	{
		$pdate	 = DateTimeFormat::DatePickerToDate($pickup_date);
		$sql	 = "SELECT prt_package_rate,prt_state_tax,prt_toll_tax,prt_driver_allowance FROM `package_rate` WHERE DATE(`prt_package_valid_from`) <= '$pdate' AND DATE(`prt_package_valid_to`)>= '$pdate' AND prt_pck_id=$pckageID AND prt_package_cab_type=$cabtypeid AND prt_status =1";
		$result	 = DBUtil::queryRow($sql);
		return $result;
	}

	public function getDuplicate($cabTypeId, $packageID)
	{
		$rateCheck = $this->find('prt_pck_id = :pckid AND prt_package_cab_type = :cabid AND (prt_package_valid_from IS NULL OR prt_package_valid_to IS NULL) AND prt_status=1', array(':pckid' => $packageID, ':cabid' => $cabTypeId));
		return $rateCheck;
	}

	public static function getCabRateAddedList($pckid = '', $pickup_date = '', $pckCabType = '')
	{

		if ($pickup_date != '')
		{
			$dateAdd = " 
			AND	(prt_package_valid_from IS NULL OR '$pickup_date' > prt_package_valid_from) 
						AND (prt_package_valid_to IS NULL OR '$pickup_date' < prt_package_valid_to)";
		}
		$ext = '';	
		if($pckCabType > 0 && is_array($pckCabType) == false)
		{
			$ext = " AND prt_package_cab_type = $pckCabType";
		}
		if ($pckid != '')
		{
			$sql	 = "SELECT prt.prt_id,prt.prt_pck_id,prt.prt_package_cab_type,prt.prt_package_rate,prt.prt_state_tax,prt.prt_toll_tax,
				prt.prt_driver_allowance,prt.prt_vendor_amt               
                FROM package pck
					JOIN 
					(SELECT  max(prt_package_rate) prt_package_rate, prt_pck_id,prt_package_cab_type from package_rate 
						WHERE prt_status = 1  $dateAdd $ext
						GROUP BY prt_pck_id,prt_package_cab_type)prtMax  ON prtMax.prt_pck_id = pck.pck_id
					JOIN package_rate prt ON prtMax.prt_package_rate = prt.prt_package_rate AND  prt.prt_pck_id = pck.pck_id 
					WHERE pck.pck_active = 1 AND pck_id = $pckid
					GROUP BY pck_id,prt_package_cab_type";
			$result	 = DBUtil::queryAll($sql, DBUtil::SDB());
		}
		return $result;
	}

}

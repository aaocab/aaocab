<?php

/**
 * This is the model class for table "vendor_packages".
 *
 * The followings are the available columns in table 'vendor_packages':
 * @property integer $vpk_id
 * @property integer $vpk_vnd_id
 * @property integer $vpk_type
 * @property string $vpk_vhc_id
 * @property string $vpk_mailing_address
 * @property string $vpk_sent_date
 * @property integer $vpk_sent_count
 * @property integer $vpk_sent_by
 * @property integer $vpk_received_status
 * @property string $vpk_received_date
 * @property string $vpk_tracking_number
 * @property integer $vpk_delivered_by_courier
 * @property string $vpk_created_date
 */
class VendorPackages extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vendor_packages';
	}

	public $search;
	public $searchVehicleNumber;
	public $packagesSentDate;
	public $packagesSentTime;
	public $packagesReceivedDate;
	public $packagesReceivedTime;
	public $vpk_created_date1;
	public $vpk_created_date2;
	public $vpk_sentpackage_date1;
	public $vpk_sentpackage_date2;
	public $vpk_status;
	public $stickerReceivedTypes		 = ['0' => 'Pending', '1' => 'Received', '2' => 'Not Received'];
	public static $deliveredCourierArr	 = ['0' => 'No', '1' => 'Yes'];

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vpk_vnd_id, vpk_type, vpk_sent_count', 'required', 'on' => 'requestPackages'),
			array('vpk_vnd_id, vpk_type, vpk_sent_count, vpk_sent_by, vpk_received_status, vpk_delivered_by_courier', 'numerical', 'integerOnly' => true),
			array('vpk_vhc_id, vpk_tracking_number', 'length', 'max' => 150),
			array('vpk_mailing_address, vpk_sent_date, vpk_received_date', 'safe'),
			['vpk_sent_count', 'validateEdit', 'on' => 'updateBoost'],
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vpk_id, vpk_vnd_id, vpk_type, vpk_vhc_id, vpk_mailing_address, vpk_sent_date, vpk_sent_count, vpk_sent_by, vpk_received_status, vpk_received_date, vpk_tracking_number, vpk_delivered_by_courier, vpk_created_date', 'safe', 'on' => 'search'),
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
			'vpk_id'					 => 'Vpk',
			'vpk_vnd_id'				 => 'Vpk Vnd',
			'vpk_type'					 => 'Vpk Type',
			'vpk_vhc_id'				 => 'Vpk Vhc',
			'vpk_mailing_address'		 => 'Vpk Mailing Address',
			'vpk_sent_date'				 => 'Vpk Sent Date',
			'vpk_sent_count'			 => 'Vpk Sent Count',
			'vpk_sent_by'				 => 'Vpk Sent By',
			'vpk_received_status'		 => 'Vpk Received Status',
			'vpk_received_date'			 => 'Vpk Received Date',
			'vpk_tracking_number'		 => 'Vpk Tracking Number',
			'vpk_delivered_by_courier'	 => 'Vpk Delivered By Courier',
			'vpk_created_date'			 => 'Vpk Created Date',
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

		$criteria->compare('vpk_id', $this->vpk_id);
		$criteria->compare('vpk_vnd_id', $this->vpk_vnd_id);
		$criteria->compare('vpk_type', $this->vpk_type);
		$criteria->compare('vpk_vhc_id', $this->vpk_vhc_id, true);
		$criteria->compare('vpk_mailing_address', $this->vpk_mailing_address, true);
		$criteria->compare('vpk_sent_date', $this->vpk_sent_date, true);
		$criteria->compare('vpk_sent_count', $this->vpk_sent_count);
		$criteria->compare('vpk_sent_by', $this->vpk_sent_by);
		$criteria->compare('vpk_received_status', $this->vpk_received_status);
		$criteria->compare('vpk_received_date', $this->vpk_received_date, true);
		$criteria->compare('vpk_tracking_number', $this->vpk_tracking_number, true);
		$criteria->compare('vpk_delivered_by_courier', $this->vpk_delivered_by_courier);
		$criteria->compare('vpk_created_date', $this->vpk_created_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VendorPackages the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getType()
	{
		return array(1	 => 'Sticker',
			2	 => 'Cab Partition');
	}

	public function getStatus()
	{
		return array(1	 => 'Sent',
			2	 => 'Received',
			3	 => 'Not Send');
	}

	public function getJSON($arr = [])
	{
		$arrJSON = array();
		foreach ($arr as $key => $val)
		{
			$arrJSON[] = array("id" => $key, "text" => $val);
		}
		$data = CJSON::encode($arrJSON);
		return $data;
	}

	public function getList($arr = null, $city = '')
	{
		if ($arr != null && !empty($arr) && count($arr) > 0)
		{
			$search					 = $arr['search'];
			$searchVhcCode			 = $arr['searchVehicleNumber'];
			$searchPackagesStatus	 = $arr['vpk_status'];
			$packagesType			 = $arr['vpk_type'];
			$city					 = Yii::app()->request->getParam('Cities')['cty_id'];

			$date1	 = $arr['vpk_created_date1'];
			$date2	 = $arr['vpk_created_date2'];

			$packageSentdate1	 = $arr['vpk_sentpackage_date1'];
			$packageSentdate2	 = $arr['vpk_sentpackage_date2'];
		}
		$where = "";
		if ($search != '')
		{
			$where .= "AND ((vnd.vnd_code LIKE '%" . trim($search) . "%' ) OR (vnd.vnd_name LIKE '%" . trim($search) . "%'))";
		}
		if ($date1 != '' && $date2 != '')
		{
			$where .= " AND vpk.vpk_created_date BETWEEN '" . $date1 . " 00:00:00' AND '" . $date2 . " 23:59:59'";
		}
		if ($packageSentdate1 != '' && $packageSentdate2 != '')
		{
			$where .= " AND vpk.vpk_sent_date BETWEEN '" . $packageSentdate1 . " 00:00:00' AND '" . $packageSentdate2 . " 23:59:59'";
		}
		if ($searchVhcCode != '')
		{
			$search_txt	 = trim($searchVhcCode);
			$tsearch_txt = strtolower(str_replace(' ', '', $search_txt));
			$where		 .= " AND (REPLACE(LOWER(vhc.vhc_code),' ', '')  LIKE '%$tsearch_txt%') ";
		}
		if ($searchPackagesStatus != '')
		{
			if ($searchPackagesStatus == 1)
			{
				$searchStatus = " AND vpk.vpk_sent_count > 0";
			}
			else if ($searchPackagesStatus == 2)
			{
				$searchStatus = " AND vpk.vpk_received_status = 1";
			}
			else
			{
				$searchStatus = " AND vpk.vpk_sent_count = 0";
			}
		}
		if ($packagesType != '')
		{
			$where .= "AND vpk.vpk_type IN($packagesType)";
		}
		else
		{
			$where .= "AND vpk.vpk_type IN(1,2)";
		}

		if ($city != '')
		{
			$where .= " AND cnt.ctt_city = $city";
		}
		$sql = "SELECT DISTINCT(vpk.vpk_id),
					   vpk.vpk_vnd_id,
					   vpk.vpk_vhc_id, 
					   vpk.vpk_mailing_address,
					   vpk.vpk_sent_date, 
					   vpk.vpk_sent_count,
					   vpk.vpk_received_status, 
					   vpk.vpk_received_date,
					   vpk.vpk_tracking_number,
					   vpk.vpk_delivered_by_courier,
                       vpk.vpk_type,
                       vpk.vpk_created_date,
					   vnd.vnd_name,
					   vnd.vnd_code,
					   vnd.vnd_id,
					   vhc.vhc_number,
					   vhc.vhc_code,
						cnt.ctt_first_name,
						cnt.ctt_last_name,
						cnt.ctt_business_name
					   FROM vendor_packages vpk 

					   INNER JOIN vendors vnd ON vpk.vpk_vnd_id = vnd.vnd_id AND vnd.vnd_active > 0
					   INNER JOIN vehicles vhc ON vhc.vhc_id = vpk.vpk_vhc_id  AND vhc.vhc_active = 1
					   INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd.vnd_id AND cp.cr_status = 1
					   INNER JOIN contact cnt ON cnt.ctt_id = cp.cr_contact_id AND cnt.ctt_id = cnt.ctt_ref_code AND cnt.ctt_active = 1
                                           $where $searchStatus AND vpk_active = 1 ORDER BY vnd.vnd_id DESC, vpk.vpk_created_date DESC";

		$arr			 = array();
		$data			 = DBUtil::queryRow("SELECT COUNT(DISTINCT(vpk.vpk_id)) AS count 
					   FROM vendor_packages vpk 
					   INNER JOIN vendors vnd ON vpk.vpk_vnd_id = vnd.vnd_id AND vnd.vnd_active > 0
					   INNER JOIN vehicles vhc ON vhc.vhc_id = vpk.vpk_vhc_id AND vhc.vhc_active = 1
                       INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd.vnd_id AND cp.cr_status = 1
					   INNER JOIN contact cnt ON cnt.ctt_id = cp.cr_contact_id AND cnt.ctt_id = cnt.ctt_ref_code AND cnt.ctt_active = 1
					   $where $searchStatus AND vpk_active = 1 ORDER BY vnd.vnd_id DESC, vpk.vpk_created_date DESC", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $data['count'],
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes'	 => ['vpk_id'],
				'defaultOrder'	 => 'vpk_id DESC'], 'pagination'	 => ['pageSize' => 50],
		]);
		$result			 = array();
		$result[0]		 = $dataprovider;
		$result[1]		 = $data;
		return $result;
	}

	public static function sendList($vendorId)
	{
		$param		 = ['vendorId' => $vendorId];
		$sql		 = "SELECT vpk_id,vpk_vnd_id,vpk_type,vpk_tracking_number,vpk_sent_count,vpk_sent_date FROM `vendor_packages` WHERE vpk_sent_count>0 AND vpk_vnd_id=:vendorId AND `vpk_received_date` IS NULL";
		$recordset	 = DBUtil::query($sql, DBUtil:: SDB(), $param);
		return $recordset;
	}

	public function validateEdit($attribute, $params)
	{
		$success = true;
		if ($this->vpk_sent_count == '' || $this->vpk_sent_count == 0 || $this->vpk_sent_count < 0)
		{
			$this->addError($attribute, 'Please Enter No. of Stickers Sent greater than 0');
			$success = false;
		}
		if ($this->vpk_tracking_number == '')
		{
			$this->addError('vpk_tracking_number', 'Please Enter Tracking Number.');
			$success = false;
		}
		if ($this->vpk_sent_date == '')
		{
			$this->addError('vpk_sent_date', 'Please Enter Send Date.');
			$success = false;
		}

		if ($this->vpk_received_date != '')
		{
			if ((strtotime($this->vpk_received_date)) < (strtotime($this->vpk_sent_date)))
			{
				$this->addError('vpk_received_date', 'Please Enter Packages Received Date greater then Sent Date.');
				$success = false;
			}
		}
		return $success;
	}

	public static function checkExistence($car, $type, $vendorId)
	{

		$carId	 = array();
		$car_arr = explode(",", $car);
		$param	 = [
			"type"		 => $type,
			"vendorId"	 => $vendorId];
		$sql	 = "SELECT vpk_vhc_id FROM `vendor_packages` WHERE vpk_type=:type AND `vpk_vnd_id` =:vendorId";

		$recordset = DBUtil::queryAll($sql, DBUtil:: SDB(), $param);
		// print_r($recordset[0]['vpk_vhc_id']);
		foreach ($recordset as $car)
		{
			//echo $car['vpk_vhc_id'].'<br>';
			$dataCarId = explode(",", $car['vpk_vhc_id']);
			foreach ($car_arr as $id)
			{
				if (in_array($id, $dataCarId))
				{
					$duplicate_id[] = $id;
				}
			}
		}

		$duplicate_arr = array_unique($duplicate_id);
		if (!empty($duplicate_arr))
		{
			$result = array_diff($car_arr, $duplicate_arr);
		}
		else
		{
			$result = $car_arr;
		}

		return $result;
	}

}

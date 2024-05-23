<?php

/**
 * This is the model class for table "tnc_points".
 *
 * The followings are the available columns in table 'tnc_points':
 * @property integer $tnp_id
 * @property string $tnp_tier
 * @property integer $tnp_for
 * @property integer $tnp_trip_type
 * @property integer $tnp_c_type
 * @property integer $tnp_is_customer
 * @property integer $tnp_is_driver
 * @property integer $tnp_is_vendor
 * @property integer $tnp_position
 * @property string $tnp_text
 * @property string $tnp_created
 * @property string $tnp_modified
 * @property integer $tnp_active
 */
class TncPoints extends CActiveRecord
{

	const TNC_CANCELLATION	 = 4;
	const TNC_BORDING_CHECK	 = 1;
	const TNC_DOS_AND_DONTS	 = 2;
	const TNC_OTHER_TERMS		 = 3;
	const TNC_TYPE_CUSTOMER	 = 1;
	const TNC_TYPE_VENDOR		 = 2;
	const TNC_TYPE_DRIVER		 = 3;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tnc_points';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tnp_tier, tnp_for, tnp_text ', 'required'),
			array('tnp_created', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('tnp_id, tnp_tier, tnp_for, tnp_trip_type, tnp_c_type, tnp_is_customer, tnp_is_driver, tnp_is_vendor, tnp_position, tnp_text, tnp_created, tnp_modified, tnp_active', 'safe', 'on' => 'search'),
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

	public function defaultScope()
	{
		$arr = array(
			'condition' => "tnp_active=1",
		);
		return $arr;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'tnp_id'		 => 'Tnp',
			'tnp_tier'		 => 'Tnp Tier',
			'tnp_for'		 => 'Type',
			'tnp_trip_type'	 => 'Trip Type',
			'tnp_c_type'	 => 'Booking Section',
			'tnp_position'	 => 'Position',
			'tnp_text'		 => 'Text',
			'tnp_created'	 => 'Tnp Created',
			'tnp_modified'	 => 'Tnp Modified',
			'tnp_active'	 => 'Tnp Active',
		);
	}

	public static function getType()
	{
		return ['1' => 'Customer', '2' => 'Vendor', '3' => 'Driver'];
	}

	/**
	 *
	 * @param integer $id
	 * @return string
	 */
	public static function getCatTypeById($id)
	{
		$arr = self::getType();
		return $arr[$id];
	}

	/**
	 *
	 * @return JSON
	 */
	public static function getCType()
	{
		$arr	 = ['1' => 'Boarding checks', '2' => 'On trip dos and donts', '3' => 'Other terms', '4' => 'Cancellation'];
		$arrJSON = array();
		foreach ($arr as $key => $val)
		{
			$arrJSON[] = array("id" => $key, "text" => $val);
		}
		return CJSON::encode($arrJSON);
	}

	/**
	 *
	 * @param integer $id
	 */
	public static function getCat($id)
	{
		$params	 = [':id' => $id];
		$sql	 = 'SELECT * FROM `tnc_points` WHERE `tnp_id` = :id';
		$arr	 = DBUtil::query($sql, DBUtil::SDB(), $params);
	}

	/**
	 *
	 * @return JSON
	 */
	public static function getBookingTypeJSON()
	{
		$bookingList = Booking::model()->getBookingType();
		$jsonList	 = [];
		foreach ($bookingList as $key => $val)
		{
			$jsonList[] = array("id" => $key, "text" => $val);
		}
		return CHtml::listData($jsonList, "id", "text");
	}

	/**
	 *
	 * @param string $catType
	 * @return string
	 */
	public static function showCatType($catType)
	{
		$arrCatType	 = explode(',', $catType);
		$ctr		 = 1;
		$showCatType = '';
		foreach ($arrCatType as $arr)
		{
			$showCatType .= self::getCatTypeById($arr);
			if (count($arrCatType) != $ctr)
			{
				$showCatType .= ', ';
			}
			$ctr++;
		}
		return $showCatType;
	}

	/**
	 *
	 * @param string $tripType
	 * @return string
	 */
	public static function showTripType($tripType)
	{
		$arrTripType	 = explode(',', $tripType);
		$ctr			 = 1;
		$showTripType	 = '';
		foreach ($arrTripType as $arr)
		{
			$showTripType .= Booking::model()->getBookingType($arr);
			if (count($arrTripType) != $ctr)
			{
				$showTripType .= ', ';
			}
			$ctr++;
		}
		return $showTripType;
	}

	/**
	 *
	 * @param string $cType
	 * @return string
	 */
	public static function showCType($cType)
	{
		$showCType = '';
		switch ($cType)
		{
			case 1:
				$showCType	 = "Boarding checks";
				break;
			case 2:
				$showCType	 = "On trip dos and donts";
				break;
			case 3:
				$showCType	 = "Other terms";
				break;
			case 4:
				$showCType	 = "Cancellation";
				break;
		}
		return $showCType;
	}

	/**
	 *
	 * @param boolean $multiple
	 * @return type
	 */
	public static function getTypeJSON($multiple = false)
	{
		$typeList	 = self::getType();
		$jsonList	 = [];
		foreach ($typeList as $key => $val)
		{
			$jsonList[] = array("id" => $key, "text" => $val);
		}
		if ($multiple == true)
		{
			return CHtml::listData($jsonList, "id", "text");
		}
		else
		{
			return CJSON::encode($jsonList);
		}
	}

	/**
	 *
	 * @param integer $tnpFor
	 * @return \CSqlDataProvider
	 */
	public function getList($tnpFor)
	{
		$sql = 'SELECT * FROM `tnc_points` WHERE tnc_points.tnp_active=1 ';
		if ($tnpFor > 0)
		{
			$sql .= ' AND tnc_points.tnp_for IN (' . $tnpFor . ')';
		}
		$sql			 .= ' ORDER BY tnp_position ASC';
		$count			 = DBUtil::command("SELECT COUNT(1) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'pagination'	 => ['pageSize' => 100],
		]);
		return $dataprovider;
	}

	/**
	 *
	 * @param integer $type
	 * 1=> customer, 2=> vendor, 3=> driver
	 * @return type
	 */
	public static function getByType($type = 1)
	{
		$params	 = [':tnpFor' => $type];
		$sql	 = 'SELECT * FROM `tnc_points` WHERE tnc_points.tnp_for IN (:tnpFor) ORDER BY tnp_position , tnp_text ASC';
		return DBUtil::query($sql, DBUtil::SDB(), $params);
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

		$criteria->compare('tnp_id', $this->tnp_id);
		$criteria->compare('tnp_tier', $this->tnp_tier, true);
		$criteria->compare('tnp_for', $this->tnp_for);
		$criteria->compare('tnp_position', $this->tnp_position);
		$criteria->compare('tnp_text', $this->tnp_text, true);
		$criteria->compare('tnp_created', $this->tnp_created, true);
		$criteria->compare('tnp_modified', $this->tnp_modified, true);
		$criteria->compare('tnp_active', $this->tnp_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TncPoints the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getTncDescription($tnpFor, $tnpTripType, $tnpTierId, $tnpCType)
	{

		$cond = '';
		if (!empty($tnpFor) && $tnpFor != '')
		{
			$cond .= " AND tnp_for =" . $tnpFor;
		}
		if (!empty($tnpCType) && $tnpCType != '')
		{
			$cond .= " AND tnp_c_type =" . $tnpCType;
		}
		$sql			 = "SELECT * FROM `tnc_points`  WHERE 1=1 " . $cond . " order by tnp_position";
		$tncPoints		 = DBUtil::queryAll($sql, DBUtil::SDB(), $params);
		$tncPointsArr	 = [];
		if (!empty($tncPoints))
		{
			foreach ($tncPoints as $tnc)
			{
				$tnpTrType	 = explode(",", $tnc['tnp_trip_type']);
				$tnpTier	 = explode(",", $tnc['tnp_tier']);
				if (in_array($tnpTripType, $tnpTrType) && in_array($tnpTierId, $tnpTier))
				{
					$tncPointsArr[] = $tnc;
				}
			}
		}
		return $tncPointsArr;
	}

	public static function getTncIdsByStep($type)
	{
		switch ($type)
		{
			case '4':
				$tncType = '61,62,63,64,65,66,82,83,92,96,97';
				break;
			case '5':
				$tncType = '68,69,70,71,72,73,93,94,95,99,100';
				break;
			case '6':
				$tncType = '77,78,79,80,81';
				break;
			case '7':
				$tncType = '74,75,76,77';
				break;
			case '8':
				$tncType = '78,79,80,81';
				break;
			case '11':
				$tncType = '87';
				break;
			case '12':
				$tncType = '86';
				break;
		}
		return $tncType;
	}

	public static function getTypeContent($ids)
	{
		 $sql	 = 'SELECT tnp_id, tnp_text FROM `tnc_points`
					WHERE tnc_points.tnp_id IN(' . $ids . ')';
		$results = DBUtil::query($sql, DBUtil::SDB());
		foreach ($results as $val)
		{
			$arr[$val['tnp_id']] = $val['tnp_text'];
		}
		$jsonArr = json_encode($arr);
		return $jsonArr;
	}

	public static function getSuggestTripContentkayak($tripType)
	{
		switch ($tripType)
		{
			case 1:
				$tripDesc = "Chauffeur driven AC cab going from one city to another city. Direct transfer from pickup address to your drop-off address. You are not allowed for any unplanned pickups, drops or detours unless mentioned.";
				break;
			case 2:
				$tripDesc = "Chauffeur driven AC cab for a Time & Distance based journey between 2 cities or towns (A to B and back to A).";
				break;
			case 3:
				$tripDesc = "Chauffeur driven AC cab for a Time & Distance based multi-city journery that can go over 1 or more days. The trip is quoted specifically for your Pickup & Drop addresses, routes and cities listed in your itinerary.";
				break;
			case 10:
				$tripDesc = "Chauffeur driven AC cab for 8 hrs 80 kms. The travel must remain within city limits. Additional time or distance used to be billed extra as per actuals.";
				break;
			case 11:
				$tripDesc = "Chauffeur driven AC cab for 12 hrs 120 kms. The travel must remain within city limits. Additional time or distance used to be billed extra as per actuals.";
				break;
		}
		return $tripDesc;
	}

	public static function getSelectedTripContentkayak($tripType)
	{
		switch ($tripType)
		{
			case 1:
				$tripDesc = "You have selected a one way trip booking type in which you will get Going from one city to another city. Direct transfer from pickup address to your drop-off address. You are not allowed for any unplanned pickups, drops or detours unless mentioned";
				break;
			case 2:
				$tripDesc = "You have selected a round trip booking type in which you will get Chauffeur driven AC cab for a Time & Distance based journey between 2 cities or towns (A to B and back to A)";
				break;
			case 3:
				$tripDesc = "You have selected a multi trip booking type in which you will get Chauffeur driven AC cab for a Time & Distance based multi-city journery that can go over 1 or more days. The trip is quoted specifically for your Pickup & Drop addresses, routes and cities listed in your itinerary.";
				break;
			case 10:
				$tripDesc = "You have selected a day rental booking type in which you will get Chauffeur driven AC cab for 8 hrs 80 kms. The travel must remain within the city limits. Additional time or distance used to be billed extra as per actuals.";
				break;
			case 11:
				$tripDesc = "You have selected a day rental booking type in which you will get Chauffeur driven AC cab for 12 hrs 120 kms. The travel must remain within the city limits. Additional time or distance used to be billed extra as per actuals.";
				break;	
		}
		return $tripDesc;
	}

}

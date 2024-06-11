<?php

/**
 * This is the model class for table "destination_note".
 *
 * The followings are the available columns in table 'destination_note':
 * @property integer $dnt_id
 * @property integer $dnt_area_id
 * @property string $dnt_valid_from
 * @property string $dnt_valid_from_date
 * @property string $dnt_valid_to
 * @property string $dnt_note
 * @property integer $dnt_status
 * @property integer $dnt_created_by
 * @property integer $dnt_created_by_role
 * @property integer $dnt_approve_by
 * @property string $dnt_approve_date
 * @property string $dnt_created_date
 * @property string $dnt_valid_from_time
 * @property integar $dnt_active
 * @property integar $dnt_area_type
 * @property string $dnt_show_note_to
 * @property integer $isNew
 */
class DestinationNote extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public $dnt_valid_from_date;
	public $dnt_valid_to_date;
	public $dnt_valid_from_time;
	public $dnt_valid_to_time;
	public $isNew;

	public function tableName()
	{
		return 'destination_note';
	}

	public $areatype	 = array(
		5	 => 'Any area type',
		6	 => 'Applicable to all',
		1	 => 'Zone',
		2	 => 'State',
		3	 => 'City',
		4	 => 'Region'
	);
	//destination notes by Rituparana
	public $showNoteType = array(
		1	 => 'Consumer',
		2	 => 'Vendor',
		3	 => 'Driver',
			//5	 => 'Agent',
	);

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dnt_valid_from,dnt_valid_from_time, dnt_valid_to, dnt_note, dnt_show_note_to, dnt_created_by, dnt_created_by_role', 'required'),
			array('dnt_status, dnt_created_by, dnt_created_by_role, dnt_approve_by,dnt_active', 'numerical', 'integerOnly' => true),
			array('dnt_approve_date', 'safe'),
			array('dnt_active', 'safe'),
			//['dnt_area_id', 'validateGlobalArea',  'on' => 'areaGlobalValid'],
			['dnt_valid_from', 'validateDateTimeAndArea', 'on' => 'addValid'],
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('dnt_id, dnt_area_id, dnt_valid_from, dnt_valid_from_date,dnt_valid_to, dnt_note, dnt_status, dnt_created_by, dnt_created_by_role, dnt_approve_by, dnt_approve_date, dnt_created_date,dnt_active', 'safe', 'on' => 'search'),
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
			'dnt_id'				 => 'Dnt',
			'dnt_area_id'			 => 'Area',
			'dnt_area_type'			 => 'Area Type',
			'dnt_city'				 => 'City Name',
			'dnt_valid_from_date'	 => 'Valid From Date',
			'dnt_valid_to_date'		 => 'Valid To Date',
			'dnt_note'				 => 'Note',
			'dnt_status'			 => 'Status',
			'dnt_created_by'		 => 'Created By',
			'dnt_created_by_role'	 => 'Dnt Created By Role',
			'dnt_approve_by'		 => 'Dnt Approve By',
			'dnt_approve_date'		 => 'Dnt Approve Date',
			'dnt_created_date'		 => 'Dnt Created Date',
			'dnt_active'			 => 'Dnt Active'
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

		$criteria->compare('dnt_id', $this->dnt_id);
		$criteria->compare('dnt_area_id', $this->dnt_area_id);
		$criteria->compare('dnt_valid_from', $this->dnt_valid_from);
		$criteria->compare('dnt_valid_from', $this->dnt_valid_from);
		$criteria->compare('dnt_valid_to', $this->dnt_valid_to);
		$criteria->compare('dnt_note', $this->dnt_note, true);
		$criteria->compare('dnt_show_note_to', $this->dnt_show_note_to);
		$criteria->compare('dnt_status', $this->dnt_status);
		$criteria->compare('dnt_created_by', $this->dnt_created_by);
		$criteria->compare('dnt_created_by_role', $this->dnt_created_by_role);
		$criteria->compare('dnt_approve_by', $this->dnt_approve_by);
		$criteria->compare('dnt_approve_date', $this->dnt_approve_date, true);
		$criteria->compare('dnt_created_date', $this->dnt_created_date, true);
		$criteria->compare('dnt_active', $this->dnt_active, true);
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DestinationNote the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function validateDateTimeAndArea($attribute, $params)
	{
		if ($this->dnt_area_type == '')
		{
			$this->addError('dnt_area_type', 'Please Select Area Type');
			return false;
		}
		if ($this->dnt_area_type != 0)
		{
			if ($this->dnt_area_id == 0 || $this->dnt_area_id == '')
			{
				$this->addError('dnt_area_id', 'Please Select Area');
				return false;
			}
		}
		if ((strtotime($this->dnt_valid_from)) >= (strtotime($this->dnt_valid_to)))
		{
			$this->addError('dnt_valid_from, dnt_valid_to', 'From date Should be smaller than  to date');
			return false;
		}
	}

	public function fetchNotedDetalis($requestDetails = null)
	{
		$where = "";
		if (!empty($requestDetails))
		{
			if ($requestDetails['area_id'] != "")
			{
				$where .= "AND dnt_area_id IN (" . $requestDetails['area_id'] . ")";
			}

			if ($requestDetails['area_type'] != "" && $requestDetails['area_type'] != 5 && $requestDetails['area_type'] != 6)
			{
				$where .= " AND dnt_area_type='" . $requestDetails['area_type'] . "'";
			}
			if ($requestDetails['area_type'] == 6)
			{
				$where .= " AND dnt_area_type=0";
			}
			if ($requestDetails['show_note_to'] != "")
			{
				$showNoteArr = explode(',', $requestDetails['show_note_to']);
				if (count($showNoteArr) > 1)
				{
					$where .= " and ( ";
					foreach ($showNoteArr as $value)
					{
						$where .= "  FIND_IN_SET(" . $value . ", dnt.dnt_show_note_to) or ";
					}
					$where	 = rtrim($where, ' or');
					$where	 .= " )  ";
				}
				else
				{
					$where .= " AND FIND_IN_SET(" . $requestDetails['show_note_to'] . ", dnt.dnt_show_note_to)";
				}
			}
			if ($requestDetails['fromDate'] != "" && $requestDetails['toDate'] != "")
			{
				$where .= " AND (dnt_valid_from <='" . $requestDetails['fromDate'] . "' AND dnt_valid_to>='" . $requestDetails['toDate'] . "' )";
			}
			else if ($requestDetails['fromDate'] != "")
			{
				$where .= " AND dnt_valid_from<='" . $requestDetails['fromDate'] . "' AND dnt_valid_to>='" . $requestDetails['fromDate'] . "'";
			}
			else if ($requestDetails['toDate'] != "")
			{
				$where .= " AND dnt_valid_to>='" . $requestDetails['toDate'] . "'";
			}
			if ($requestDetails['createdDate'] != "")
			{
				$createdDate = $requestDetails['createdDate'];
				$where		 .= " AND (dnt_created_date BETWEEN '$createdDate 00.00.00' AND '$createdDate 23.59.59')";
			}
			if ($requestDetails['status'] == 1)
			{
				$where .= " AND dnt_status=0";
			}
		}
		$fetchNoteDetails = "SELECT 
							dnt.dnt_id,
							dnt_area_type,
							dnt.`dnt_area_id`,
							if(dnt.dnt_area_type=3,ct.cty_name, '') AS cty_name,
							if(dnt.dnt_area_type=2,st.stt_name, '') AS dnt_state_name,
							if(dnt.dnt_area_type=1,zn.zon_name, '') AS dnt_zone_name,
							dnt.dnt_valid_from,
							dnt.dnt_valid_to,
							dnt.dnt_note,
							dnt.dnt_show_note_to,
							dnt.dnt_status,
							if(dnt.dnt_status =1,CONCAT(ad.adm_fname, ' ',ad.adm_lname),'N/A') AS dnt_approve_by,
							if(dnt.dnt_created_by_role=1,CONCAT(ad.adm_fname, ' ',ad.adm_lname),'') AS dnt_approve_name,
							if(dnt.dnt_created_by_role = 2, CONCAT(ctt.ctt_first_name, ' ',ctt.ctt_last_name), '') AS dnt_vnd_approve_name, 
							if(dnt.dnt_created_by_role = 3, CONCAT(ctt.ctt_first_name, ' ',ctt.ctt_last_name), '') AS dnt_drv_approve_name,
							dnt.dnt_created_by_role,
							dnt.dnt_created_date
							FROM destination_note dnt
							LEFT JOIN cities ct ON ct.cty_id  = dnt.dnt_area_id
							LEFT JOIN states st ON st.stt_id  = dnt.dnt_area_id
							LEFT JOIN zones zn ON zn.zon_id  = dnt.dnt_area_id
							LEFT JOIN admins AS ad ON ad.adm_id  = dnt.dnt_created_by OR ad.adm_id = dnt.dnt_approve_by	
							LEFT JOIN contact_profile AS cr ON cr.cr_is_consumer = dnt.dnt_created_by
							LEFT JOIN contact AS ctt ON ctt.ctt_id = cr.cr_contact_id AND ctt.ctt_active>0 
							WHERE dnt_active=1  $where";

		$fetchNoteCount	 = "SELECT count(1)  FROM destination_note dnt WHERE dnt_active=1 $where";
		$count			 = DBUtil::command($fetchNoteCount, DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($fetchNoteDetails, array(
			"totalItemCount" => $count,
			'db'			 => DBUtil::SDB(),
			"pagination"	 => array("pageSize" => 50),
			'sort'			 => array('defaultOrder' => 'dnt_id DESC')
		));
		return $dataprovider;
	}

	public function showBookingNotes($locationArr, $dateArr, $showNote = '')
	{
		
		if ($showNote != '')
		{
			$cond = "AND FIND_IN_SET($showNote, dnt.dnt_show_note_to)";
		}
		
		if($showNote == 'both')
		{
			$cond = "AND (FIND_IN_SET(2, dnt.dnt_show_note_to) OR FIND_IN_SET(3, dnt.dnt_show_note_to))";
		}
		$cities = implode(",", $locationArr);
		if ($cities == null || $cities == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		DBUtil::getINStatement($cities, $bindString1, $params1);
		$sql = "SELECT group_concat(distinct cty_state_id) as state,
							group_concat(distinct stt_zone) as region
							FROM `cities`
							INNER JOIN states on cty_state_id=stt_id
							WHERE  cty_id IN($cities)  AND `cty_state_id` IS NOT NULL AND `stt_zone` IS NOT NULL";

		$res	 = DBUtil::queryRow($sql);
		$state	 = $res['state'] != null ? $res['state'] : -1;
		$region	 = $res['region'] != null ? $res['region'] : -1;
		DBUtil::getINStatement($state, $bindString2, $params2);
		DBUtil::getINStatement($region, $bindString3, $params3);
		#zone query

		$zones			 = ZoneCities::model()->findZoneByCityes($cities);
		$zones			 = implode(",", array_unique(explode(",", $zones)));
		$locatationQuery = "(dnt_area_type=3 AND dnt.dnt_area_id IN ($bindString1) ||  dnt_area_type=2 AND dnt.dnt_area_id IN ($bindString2) ||  dnt_area_type=1 AND dnt.dnt_area_id IN ($zones) || dnt_area_type=4 AND dnt.dnt_area_id IN ($bindString3)||  dnt_area_type=0)";
		
		$fromDate		 = $dateArr[0];
		#echo $fromDate;
		if (count($dateArr) > 1)
		{
			$toDate = $dateArr[1];
		}
		if ($fromDate != "" && $toDate != "")
		{
			$where = "AND (dnt_valid_from <='" . $fromDate . "' AND dnt_valid_to>='" . $toDate . "' )";
			
		}
		elseif ($fromDate != "")
		{
			$where .= " AND dnt_valid_from>='" . $fromDate . "'";
		}
		
		$sql = "SELECT dnt.dnt_id,
				   dnt.dnt_area_id,
				   dnt.dnt_area_type,
				   dnt.dnt_show_note_to,
                                   if(dnt.dnt_area_type=1,zn.zon_name, '') AS dnt_zone_name,
				    if(dnt.dnt_area_type=3,ct.cty_name, '') AS cty_name,
					if(dnt.dnt_area_type=2,st.stt_name, '') AS dnt_state_name,
				   dnt.dnt_valid_from,
				   dnt.dnt_valid_to,
				   dnt.dnt_note,
				   dnt.dnt_active
            FROM destination_note dnt
			LEFT JOIN cities ct ON ct.cty_id  = dnt.dnt_area_id
			LEFT JOIN states st ON st.stt_id  = dnt.dnt_area_id
                        LEFT JOIN zones zn ON zn.zon_id  = dnt.dnt_area_id 
			WHERE dnt_status=1 AND dnt_active=1 AND (" . $locatationQuery . ")  $where $cond ORDER BY ct.cty_name";
			
		#echo $sql;
		$record = DBUtil::queryAll($sql, DBUtil::SDB(), array_merge($params1, $params2, $params3));
		foreach ($record as $key => $val)
		{
			foreach ($val as $k => $v)
			{
				if ($k == 'dnt_valid_from')
				{
					$record[$key]['dnt_valid_from_date'] = date('Y-m-d', strtotime($v));
					$record[$key]['dnt_valid_from_time'] = date("H:i:00", strtotime($v));
				}
				if ($k == 'dnt_valid_to')
				{
					$record[$key]['dnt_valid_to_date']	 = date('Y-m-d', strtotime($v));
					$record[$key]['dnt_valid_to_time']	 = date("H:i:00", strtotime($v));
				}
			}
		}
		return $record;
	}

	public function showNoteApi($bkg_id, $showNoteTo)
	{
		$sql = "SELECT brt_from_city_id,brt_to_city_id,brt_pickup_datetime,brt_trip_duration
				FROM booking_route WHERE brt_bkg_id IN($bkg_id)";
		
		$record = DBUtil::query($sql);
		foreach ($record as $key => $bookingRoute)
		{
			$pickupCity[]	 = $bookingRoute['brt_from_city_id'];
			$dropCity[]		 = $bookingRoute['brt_to_city_id'];
			$pickup_date[]	 = $bookingRoute['brt_pickup_datetime'];
			
			$temp_last_date	 = strtotime($bookingRoute['brt_pickup_datetime']) + ($bookingRoute['brt_trip_duration']*60);
			$drop_date_time	 = date('Y-m-d H:i:s', $temp_last_date);
		}

		$pickup_date_time	 = $pickup_date[0];
		$locationArr		 = array_unique(array_merge($pickupCity, $dropCity));
		$dateArr			 = array($pickup_date_time, $drop_date_time);

		$noteArrList = $this->showBookingNotes($locationArr, $dateArr, $showNoteTo);
		return $noteArrList;
	}

	public function addNote($model)
	{
		$success	 = false;
		$errors		 = NULL;
		$userInfo	 = UserInfo::getInstance();
		if ($model == NULL)
		{
			$model = new DestinationNote();
		}
		$fromDate				 = $model->dnt_valid_from_date;
		$fromTime				 = $model->dnt_valid_from_time;
		$model->dnt_valid_from	 = $fromDate . ' ' . $fromTime;

		$toDate				 = $model->dnt_valid_to_date;
		$toTime				 = $model->dnt_valid_to_time;
		$model->dnt_valid_to = $toDate . ' ' . $toTime;

		$model->dnt_status			 = 0;
		$model->dnt_created_by		 = UserInfo::getUserId();
		$model->dnt_created_by_role	 = UserInfo::TYPE_DRIVER;
		$model->dnt_area_id			 = ($model->dnt_area_id > 0) ? $model->dnt_area_id : 0;

		$model->scenario = 'addValid';
		$errors			 = CActiveForm::validate($model, null, false);
		$errors			 = $model->getErrors();
		if ($errors == Array())
		{
			if ($model->validate())
			{
				$model->save();
				$success = true;
			}
		}
		$resultArr = ['error' => $errors, 'success' => $success];
		return $resultArr;
	}
	/**@deprected
	 * new function getArea
	 * due to used query all
	 * new function getArea
	 */
	public function getAreaList($areaType, $searchTxt)
	{
		if ($areaType == 'state')
		{
			if ($searchTxt != '')
			{
				$qry = " AND stt_name LIKE '%{$searchTxt}%'";
			}
			$query	 = " SELECT stt_id as areaID ,stt_name as areaName  FROM states WHERE stt_active ='1' " . $qry . " ";
			$result	 = DBUtil::queryAll($query);
		}
		else if ($areaType == 'city')
		{
			if ($areaType == 'city')
			{
				$qry = " AND cty_display_name LIKE '%{$searchTxt}%'";
			}
			$query	 = "SELECT city.`cty_id` as areaID, city.cty_display_name as areaName FROM `cities` city	WHERE city.cty_active=1 AND cty_service_active=1 " . $qry . " ORDER BY cty_name";
			$result	 = DBUtil::queryAll($query);
		}
		return $result;
	}

	/** This function is used for expire note according to id
	 * @param inter $noteid Description
	 * @return boolean
	 */
	public static function expNote($noteId)
	{
		$date	 = date('Y-m-d', strtotime(' -1 days'));
		$sql	 = "UPDATE destination_note SET dnt_valid_to=:date  
  					WHERE dnt_id =:noteId ";

		$numrows = DBUtil::execute($sql, ['noteId' => $noteId, 'date' => $date]);
		if ($numrows != 0)
		{
			return true;
		}
	}

	public static function getNotesByBkgId($bkgid, $userType)
	{
		$param		 = ['bkgid' => $bkgid];
		$sql		 = "SELECT concat(brt_from_city_id, ',', GROUP_CONCAT(brt_to_city_id)) rtList, 
				min(brt_pickup_datetime) pickup_datetime, 
				max(date_add(brt_pickup_datetime, INTERVAL brt_trip_duration MINUTE)) tripEndTime 
				FROM   booking_route
				WHERE  brt_bkg_id = :bkgid";
		$res		 = DBUtil::queryRow($sql, DBUtil::SDB(), $param);
		$dateArr	 = [$res['pickup_datetime'], $res['tripEndTime']];
		$cityList	 = explode(',', $res['rtList']);
		$noteArrList = DestinationNote::model()->showBookingNotes($cityList, $dateArr, $userType);
		return $noteArrList;
	}
	/**
	 * replace getAreaList
	 * @param type $areaType
	 * @param type $searchTxt
	 * @return type
	 */
	public function getArea($areaType, $searchTxt)
	{
		if ($areaType == 'state')
		{
			if ($searchTxt != '')
			{
				$qry = " AND stt_name LIKE '%{$searchTxt}%'";
			}
			$query	 = " SELECT stt_id as areaID ,stt_name as areaName  FROM states WHERE stt_active ='1' " . $qry . " ";
			$result	 = DBUtil::query($query);
		}
		else if ($areaType == 'city')
		{
			if ($searchTxt != '')
			{
				$qry = " AND cty_display_name LIKE '%{$searchTxt}%'";
				$query	 = "SELECT city.`cty_id` as areaID, city.cty_display_name as areaName FROM `cities` city	WHERE city.cty_active=1 AND cty_service_active=1 " . $qry . " ORDER BY cty_name";
				$result	 = DBUtil::query($query);
			}
			
		}
		return $result;
	}
	public function getModel($model= NULL)
	{
		
		if (!$model)
		{
			$model = new \DestinationNote();
		}
		
		$model->dnt_note			 = $this->note;
		$model->dnt_area_type		=(strtolower($this->noteAreaType) == "global" ) ? 0 : ((strtolower($this->noteAreaType) == "state") ? 2 : ((strtolower($this->noteAreaType) == "city") ? 3 : ((strtolower($this->noteAreaType) == "region") ? 4 :'')));
		$model->dnt_area_id			 = (int)($this->areaId==""?0:$this->areaId);
		$model->dnt_valid_from_date		 = $this->validFromDate;
		$model->dnt_valid_from_time		 = $this->validFromTime;
		$model->dnt_valid_to_date	     = $this->validToDate;
		$model->dnt_valid_to_time	     = $this->validToTime;

		return $model;
	}

}

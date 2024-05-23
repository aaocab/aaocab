<?php

/**
 * This is the model class for table "promo_date_filter".
 *
 * The followings are the available columns in table 'promo_date_filter':
 * @property integer $pcd_id
 * @property string $pcd_title
 * @property string $pcd_desc
 * @property string $pcd_weekdays_create
 * @property string $pcd_monthdays_create
 * @property string $pcd_months_create
 * @property string $pcd_weeks_create
 * @property string $pcd_weekdays_pickup
 * @property string $pcd_monthdays_pickup
 * @property string $pcd_months_pickup
 * @property string $pcd_weeks_pickup
 * @property integer $pcd_active
 *  @property integer $pcd_promo_id
 * @property string $pcd_modified
 * @property string $pcd_created
 */
class PromoDateFilter extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'promo_date_filter';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('pcd_title, pcd_desc', 'required'),
			array('pcd_active', 'numerical', 'integerOnly' => true),
			array('pcd_title, pcd_desc', 'length', 'max' => 250),
			array('pcd_weekdays_create, pcd_monthdays_create, pcd_months_create, pcd_weeks_create,pcd_weekdays_pickup, pcd_monthdays_pickup, pcd_months_pickup, pcd_weeks_pickup', 'length', 'max' => 50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pcd_id, pcd_title, pcd_desc, pcd_weekdays_create, pcd_monthdays_create, pcd_months_create, pcd_weeks_create,pcd_weekdays_pickup, pcd_monthdays_pickup, pcd_months_pickup, pcd_weeks_pickup, pcd_active, pcd_modified, pcd_created, pcd_promo_id', 'safe', 'on' => 'search'),
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
			'pcd_id'				 => 'Pcd',
			'pcd_title'				 => 'Title',
			'pcd_desc'				 => 'Description',
			'pcd_weekdays_create'	 => 'Weekdays',
			'pcd_monthdays_create'	 => 'Monthdays',
			'pcd_months_create'		 => 'Months',
			'pcd_weeks_create'		 => 'Weeks',
			'pcd_active'			 => 'Active',
			'pcd_modified'			 => 'Modified',
			'pcd_created'			 => 'Created',
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

		$criteria->compare('pcd_id', $this->pcd_id);
		$criteria->compare('pcd_title', $this->pcd_title, true);
		$criteria->compare('pcd_desc', $this->pcd_desc, true);
		$criteria->compare('pcd_weekdays', $this->pcd_weekdays, true);
		$criteria->compare('pcd_monthdays', $this->pcd_monthdays, true);
		$criteria->compare('pcd_months', $this->pcd_months, true);
		$criteria->compare('pcd_weeks', $this->pcd_weeks, true);
		$criteria->compare('pcd_active', $this->pcd_active);
		$criteria->compare('pcd_modified', $this->pcd_modified, true);
		$criteria->compare('pcd_created', $this->pcd_created, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PromoDateFilter the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getAllFilterDateCode()
	{
		$data	 = [];
		$sql	 = "SELECT pcd_id,pcd_title FROM promo_date_filter WHERE pcd_active=1";
		$res	 = DBUtil::queryAll($sql, DBUtil::SDB());
		if (count($res))
		{
			foreach ($res as $key => $value)
			{
				$data[$value['pcd_id']] = $value['pcd_title'];
			}
		}
		return $data;
	}

	public function getWeekDaysList($key = '')
	{
		$weekDays = [
			0	 => 'Sunday',
			1	 => 'Monday',
			2	 => 'Tuesday',
			3	 => 'Wednesday',
			4	 => 'Thursday',
			5	 => 'Friday',
			6	 => 'Saturday',
		];
		if ($key != '')
		{
			return $weekDays[$key];
		}
		return $weekDays;
	}

	public function getWeekList($key = '')
	{
		$week = [
			1	 => 'First',
			2	 => 'Second',
			3	 => 'Third',
			4	 => 'Fourth',
			5	 => 'Fifth',
		];
		if ($key != '')
		{
			return $week[$key];
		}
		return $week;
	}

	public function getMonthDaysList($key = '')
	{
		$monthDays = [
			1	 => '1',
			2	 => '2',
			3	 => '3',
			4	 => '4',
			5	 => '5',
			6	 => '6',
			7	 => '7',
			8	 => '8',
			9	 => '9',
			10	 => '10',
			11	 => '11',
			12	 => '12',
			13	 => '13',
			14	 => '14',
			15	 => '15',
			16	 => '16',
			17	 => '17',
			18	 => '18',
			19	 => '19',
			20	 => '20',
			21	 => '21',
			22	 => '22',
			23	 => '23',
			24	 => '24',
			25	 => '25',
			26	 => '26',
			27	 => '27',
			28	 => '28',
			29	 => '29',
			30	 => '30',
			31	 => '31',
		];
		if ($key != '')
		{
			return $monthDays[$key];
		}
		return $monthDays;
	}

	public function getMonthList($key = '')
	{
		$month = [
			1	 => 'January',
			2	 => 'February',
			3	 => 'March',
			4	 => 'April',
			5	 => 'May',
			6	 => 'June',
			7	 => 'July',
			8	 => 'August',
			9	 => 'September',
			10	 => 'October',
			11	 => 'November',
			12	 => 'December',
		];
		if ($key != '')
		{
			return $month[$key];
		}
		return $month;
	}

	public function getCreateDateFilterApplicable($date)
	{
		if ($this->pcd_active == 0)
		{
			return false;
		}
		$currentWeek	 = ceil((date("d", strtotime($date)) - date("w", strtotime($date)) - 1) / 7) + 1;
		$weekDays		 = date("w", strtotime($date));
		$currentMonth	 = date("n", strtotime($date));
		$monthDays		 = date("j", strtotime($date));
		if ($this->pcd_weeks_create != '')
		{
			$weeksArr = explode(',', $this->pcd_weeks_create);
			if (!in_array($currentWeek, $weeksArr))
			{
				return false;
			}
		}
		if ($this->pcd_weekdays_create != '')
		{
			$weekDaysArr = explode(',', $this->pcd_weekdays_create);
			if (!in_array($weekDays, $weekDaysArr))
			{
				return false;
			}
		}
		if ($this->pcd_months_create != '')
		{
			$monthArr = explode(',', $this->pcd_months_create);
			if (!in_array($currentMonth, $monthArr))
			{
				return false;
			}
		}
		if ($this->pcd_monthdays_create != '')
		{
			$monthDaysArr = explode(',', $this->pcd_monthdays_create);
			if (!in_array($monthDays, $monthDaysArr))
			{
				return false;
			}
		}
		return true;
	}

	public function getPickupDateFilterApplicable($date)
	{
		if ($this->pcd_active == 0)
		{
			return false;
		}
		$currentWeek	 = ceil((date("d", strtotime($date)) - date("w", strtotime($date)) - 1) / 7) + 1;
		$weekDays		 = date("w", strtotime($date));
		$currentMonth	 = date("n", strtotime($date));
		$monthDays		 = date("j", strtotime($date));
		if ($this->pcd_weeks_pickup != '')
		{
			$weeksArr = explode(',', $this->pcd_weeks_pickup);
			if (!in_array($currentWeek, $weeksArr))
			{
				return false;
			}
		}
		if ($this->pcd_weekdays_pickup != '')
		{
			$weekDaysArr = explode(',', $this->pcd_weekdays_pickup);
			if (!in_array($weekDays, $weekDaysArr))
			{
				return false;
			}
		}
		if ($this->pcd_months_pickup != '')
		{
			$monthArr = explode(',', $this->pcd_months_pickup);
			if (!in_array($currentMonth, $monthArr))
			{
				return false;
			}
		}
		if ($this->pcd_monthdays_pickup != '')
		{
			$monthDaysArr = explode(',', $this->pcd_monthdays_pickup);
			if (!in_array($monthDays, $monthDaysArr))
			{
				return false;
			}
		}
		return true;
	}

	public function getByPromoId($prmId)
	{
		return $this->find('pcd_promo_id=:prm', ['prm' => $prmId]);
	}

}

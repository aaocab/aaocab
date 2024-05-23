<?php

/**
 * This is the model class for table "holiday_events".
 *
 * The followings are the available columns in table 'holiday_events':
 * @property integer $hde_id
 * @property string $hde_name
 * @property string $hde_slug
 * @property string $hde_description
 * @property integer $hde_recurrs
 * @property string $hde_recurrs_rule
 * @property integer $hde_std_or_not
 * @property integer $hde_calendar_event_type
 * @property integer $hde_added_by_uid
 * @property integer $hde_approved_by_uid
 * @property string $hde_created_at
 * @property string $hde_modified_at
 * @property integer $hde_active
 * @property integer $hde_halo_previous_days
 * @property integer $hde_halo_next_days
 */
class HolidayEvents extends CActiveRecord
{

	public $allregion, $region, $source_mzone, $destination_mzone, $source_zone, $destination_zone, $source_state, $destination_state, $source_city, $destination_city, $previousHaloDays, $nextHaloDays,$margin;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'holiday_events';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hde_name,hde_slug,hde_created_at, hde_modified_at', 'required'),
			array('hde_recurrs, hde_std_or_not, hde_calendar_event_type, hde_added_by_uid,hde_approved_by_uid,hde_active,hde_halo_previous_days,hde_halo_next_days', 'numerical', 'integerOnly' => true),
			array('hde_name,hde_slug', 'length', 'max' => 255),
			array('hde_description,hde_recurrs_rule', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('hde_id, hde_name,hde_slug, hde_description, hde_recurrs,hde_recurrs_rule, hde_std_or_not, hde_calendar_event_type, hde_added_by_uid,hde_approved_by_uid, hde_created_at, hde_modified_at, hde_active,hde_halo_previous_days,hde_halo_next_days', 'safe', 'on' => 'search'),
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
			'hde_id'					 => 'Hoiday Id',
			'hde_name'					 => 'Name',
			'hde_slug'					 => 'Slug',
			'hde_description'			 => 'Description',
			'hde_recurrs'				 => 'is it recurring or one time….  yes / no',
			'hde_recurrs_rule'			 => 'Recurring rule for holiday',
			'hde_std_or_not'			 => 'all standard will be 1, all entered manually by admin will be 2',
			'hde_calendar_event_type'	 => '0-national_holiday, 1-regional_holiday, 3-elections, 4-social_unrest, 99-other',
			'hde_added_by_uid'			 => 'Holiday  Created By',
			'hde_approved_by_uid'		 => 'Holiday Approved By',
			'hde_created_at'			 => 'Created At',
			'hde_halo_previous_days'	 => 'Halo Previous days',
			'hde_halo_next_days'		 => 'Halo Next days',
			'hde_modified_at'			 => 'Modified At',
			'hde_active'				 => 'Active',
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

		$criteria->compare('hde_id', $this->hde_id);
		$criteria->compare('hde_name', $this->hde_name, true);
		$criteria->compare('hde_slug', $this->hde_slug, true);
		$criteria->compare('hde_description', $this->hde_description, true);
		$criteria->compare('hde_recurrs', $this->hde_recurrs);
		$criteria->compare('hde_recurrs_rule', $this->hde_recurrs_rule);
		$criteria->compare('hde_std_or_not', $this->hde_std_or_not);
		$criteria->compare('hde_calendar_event_type', $this->hde_calendar_event_type);
		$criteria->compare('hde_added_by_uid', $this->hde_added_by_uid);
		$criteria->compare('hde_approved_by_uid', $this->hde_approved_by_uid);
		$criteria->compare('hde_created_at', $this->hde_created_at, true);
		$criteria->compare('hde_halo_previous_days', $this->hde_halo_previous_days);
		$criteria->compare('hde_halo_next_days', $this->hde_halo_next_days, true);
		$criteria->compare('hde_modified_at', $this->hde_modified_at, true);
		$criteria->compare('hde_active', $this->hde_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HolidayEvents the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getHoliday($type = 0)
	{
		$sql	 = "SELECT 
                    hde_id,
                    holiday_events.hde_name
                    FROM `holiday_events` 
                    WHERE 1";
		$result	 = DBUtil::query($sql, DBUtil::SDB(), $params);
		if ($type == 1)
		{
			$holiday = [];
			foreach ($result as $row)
			{
				$holiday[$row['hde_id']] = "[" . $row['hde_id'] . "] " . $row['hde_name'];
			}
			return $holiday;
		}
		else
		{
			return $result;
		}
	}

	public static function isExist($slug)
	{
		$sql = "SELECT COUNT(1) AS cnt FROM `holiday_events` WHERE 1 AND hde_slug=:hde_slug";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), ['hde_slug' => $slug]);
	}

}

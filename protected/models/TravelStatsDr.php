<?php

/**
 * This is the model class for table "travel_stats_dr".
 *
 * The followings are the available columns in table 'travel_stats_dr':
 * @property integer $tsdr_id
 * @property string $tsdr_city_identifier
 * @property double $tsdr_min_travel_time_180
 * @property double $tsdr_max_travel_time_180
 * @property double $tsdr_avg_travel_time_180
 * @property double $tsdr_median_travel_time_180
 * @property double $tsdr_min_travel_time_90
 * @property double $tsdr_max_travel_time_90
 * @property double $tsdr_avg_travel_time_90
 * @property double $tsdr_median_travel_time_90
 * @property double $tsdr_min_cost_per_duration_180
 * @property double $tsdr_max_cost_per_duration_180
 * @property double $tsdr_avg_cost_per_duration_180
 * @property double $tsdr_median_cost_per_duration_180
 * @property double $tsdr_min_cost_per_duration_90
 * @property double $tsdr_max_cost_per_duration_90
 * @property double $tsdr_avg_cost_per_duration_90
 * @property double $tsdr_median_cost_per_duration_90
 * @property double $tsdr_min_cost_per_distance_180
 * @property double $tsdr_max_cost_per_distance_180
 * @property double $tsdr_avg_cost_per_distance_180
 * @property double $tsdr_median_cost_per_distance_180
 * @property double $tsdr_min_cost_per_distance_90
 * @property double $tsdr_max_cost_per_distance_90
 * @property double $tsdr_avg_cost_per_distance_90
 * @property double $tsdr_median_cost_per_distance_90
 * @property double $tsdr_min_vnd_cost_per_distance_90
 * @property double $tsdr_max_vnd_cost_per_distance_90
 * @property double $tsdr_avg_vnd_cost_per_distance_90
 * @property double $tsdr_median_vnd_cost_per_distance_90
 * @property double $tsdr_min_vnd_cost_per_distance_180
 * @property double $tsdr_max_vnd_cost_per_distance_180
 * @property double $tsdr_avg_vnd_cost_per_distance_180
 * @property double $tsdr_median_vnd_cost_per_distance_180
 * @property double $tsdr_min_vnd_cost_per_duration_90
 * @property double $tsdr_max_vnd_cost_per_duration_90
 * @property double $tsdr_avg_vnd_cost_per_duration_90
 * @property double $tsdr_median_vnd_cost_per_duration_90
 * @property double $tsdr_min_vnd_cost_per_duration_180
 * @property double $tsdr_max_vnd_cost_per_duration_180
 * @property double $tsdr_avg_vnd_cost_per_duration_180
 * @property double $tsdr_median_vnd_cost_per_duration_180
 * @property integer $tsdr_active
 * @property string $tsdr_create_at
 * @property string $tsdr_updated_at
 */
class TravelStatsDr extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'travel_stats_dr';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tsdr_city_identifier, tsdr_create_at, tsdr_updated_at', 'required'),
			array('tsdr_active', 'numerical', 'integerOnly' => true),
			array('tsdr_min_travel_time_180, tsdr_max_travel_time_180, tsdr_avg_travel_time_180, tsdr_median_travel_time_180, tsdr_min_travel_time_90, tsdr_max_travel_time_90, tsdr_avg_travel_time_90, tsdr_median_travel_time_90, tsdr_min_cost_per_duration_180, tsdr_max_cost_per_duration_180, tsdr_avg_cost_per_duration_180, tsdr_median_cost_per_duration_180, tsdr_min_cost_per_duration_90, tsdr_max_cost_per_duration_90, tsdr_avg_cost_per_duration_90, tsdr_median_cost_per_duration_90, tsdr_min_cost_per_distance_180, tsdr_max_cost_per_distance_180, tsdr_avg_cost_per_distance_180, tsdr_median_cost_per_distance_180, tsdr_min_cost_per_distance_90, tsdr_max_cost_per_distance_90, tsdr_avg_cost_per_distance_90, tsdr_median_cost_per_distance_90, tsdr_min_vnd_cost_per_distance_90, tsdr_max_vnd_cost_per_distance_90, tsdr_avg_vnd_cost_per_distance_90, tsdr_median_vnd_cost_per_distance_90, tsdr_min_vnd_cost_per_distance_180, tsdr_max_vnd_cost_per_distance_180, tsdr_avg_vnd_cost_per_distance_180, tsdr_median_vnd_cost_per_distance_180, tsdr_min_vnd_cost_per_duration_90, tsdr_max_vnd_cost_per_duration_90, tsdr_avg_vnd_cost_per_duration_90, tsdr_median_vnd_cost_per_duration_90, tsdr_min_vnd_cost_per_duration_180, tsdr_max_vnd_cost_per_duration_180, tsdr_avg_vnd_cost_per_duration_180, tsdr_median_vnd_cost_per_duration_180', 'numerical'),
			array('tsdr_city_identifier', 'length', 'max' => 23),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('tsdr_id, tsdr_city_identifier, tsdr_min_travel_time_180, tsdr_max_travel_time_180, tsdr_avg_travel_time_180, tsdr_median_travel_time_180, tsdr_min_travel_time_90, tsdr_max_travel_time_90, tsdr_avg_travel_time_90, tsdr_median_travel_time_90, tsdr_min_cost_per_duration_180, tsdr_max_cost_per_duration_180, tsdr_avg_cost_per_duration_180, tsdr_median_cost_per_duration_180, tsdr_min_cost_per_duration_90, tsdr_max_cost_per_duration_90, tsdr_avg_cost_per_duration_90, tsdr_median_cost_per_duration_90, tsdr_min_cost_per_distance_180, tsdr_max_cost_per_distance_180, tsdr_avg_cost_per_distance_180, tsdr_median_cost_per_distance_180, tsdr_min_cost_per_distance_90, tsdr_max_cost_per_distance_90, tsdr_avg_cost_per_distance_90, tsdr_median_cost_per_distance_90, tsdr_min_vnd_cost_per_distance_90, tsdr_max_vnd_cost_per_distance_90, tsdr_avg_vnd_cost_per_distance_90, tsdr_median_vnd_cost_per_distance_90, tsdr_min_vnd_cost_per_distance_180, tsdr_max_vnd_cost_per_distance_180, tsdr_avg_vnd_cost_per_distance_180, tsdr_median_vnd_cost_per_distance_180, tsdr_min_vnd_cost_per_duration_90, tsdr_max_vnd_cost_per_duration_90, tsdr_avg_vnd_cost_per_duration_90, tsdr_median_vnd_cost_per_duration_90, tsdr_min_vnd_cost_per_duration_180, tsdr_max_vnd_cost_per_duration_180, tsdr_avg_vnd_cost_per_duration_180, tsdr_median_vnd_cost_per_duration_180, tsdr_active, tsdr_create_at, tsdr_updated_at', 'safe', 'on' => 'search'),
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
			'tsdr_id'								 => 'Tsdr',
			'tsdr_city_identifier'					 => 'city_identifier(RegionId-FromCity-ToCity)',
			'tsdr_min_travel_time_180'				 => 'Minium travel time in Mintue over 180 Day',
			'tsdr_max_travel_time_180'				 => 'Maxmium travel time in Mintue over 180 Day',
			'tsdr_avg_travel_time_180'				 => 'Average travel time in Mintue over 180 Day',
			'tsdr_median_travel_time_180'			 => 'Median travel time in Mintue over 180 Day',
			'tsdr_min_travel_time_90'				 => 'Minium travel time in Mintue over 90 Day',
			'tsdr_max_travel_time_90'				 => 'Maxmium travel time in Mintue over 90 Day',
			'tsdr_avg_travel_time_90'				 => 'Average travel time in Mintue over 90 Day',
			'tsdr_median_travel_time_90'			 => 'Median travel time in Mintue over 90 Day',
			'tsdr_min_cost_per_duration_180'		 => 'Minium cost per duration for 180 Day ',
			'tsdr_max_cost_per_duration_180'		 => 'Max cost per duration for 180 Day ',
			'tsdr_avg_cost_per_duration_180'		 => 'Avg cost per duration for 180 Day ',
			'tsdr_median_cost_per_duration_180'		 => 'Median cost per duration for 180 Day ',
			'tsdr_min_cost_per_duration_90'			 => 'Minium cost per duration for 90 Day ',
			'tsdr_max_cost_per_duration_90'			 => 'Max cost per duration for 90 Day ',
			'tsdr_avg_cost_per_duration_90'			 => 'Avg cost per duration for 90 Day ',
			'tsdr_median_cost_per_duration_90'		 => 'Median cost per duration for 90 Day ',
			'tsdr_min_cost_per_distance_180'		 => 'Minium  cost per distance for  180 Day	',
			'tsdr_max_cost_per_distance_180'		 => 'Max cost per distance for  180 Day	',
			'tsdr_avg_cost_per_distance_180'		 => 'Avg cost per distance for  180 Day	',
			'tsdr_median_cost_per_distance_180'		 => 'Median  cost per distance for  180 Day	',
			'tsdr_min_cost_per_distance_90'			 => 'Minium  cost per distance for  90 Day	',
			'tsdr_max_cost_per_distance_90'			 => 'Max cost per distance for 90 Day	',
			'tsdr_avg_cost_per_distance_90'			 => 'Avg cost per distance for  90 Day	',
			'tsdr_median_cost_per_distance_90'		 => 'Median cost per distance for  90 Day	',
			'tsdr_min_vnd_cost_per_distance_90'		 => 'Minium vendor cost per distance for 90 Day',
			'tsdr_max_vnd_cost_per_distance_90'		 => 'Maxmium vendor cost per distance for 90 Day',
			'tsdr_avg_vnd_cost_per_distance_90'		 => 'Average vendor cost per distance for 90 Day',
			'tsdr_median_vnd_cost_per_distance_90'	 => 'Medain vendor cost per distance for 90 Day',
			'tsdr_min_vnd_cost_per_distance_180'	 => 'Minium vendor cost per distance for 180 Day',
			'tsdr_max_vnd_cost_per_distance_180'	 => 'Maxmium vendor cost per distance for 180 Day',
			'tsdr_avg_vnd_cost_per_distance_180'	 => 'Average vendor cost per distance for 180 Day',
			'tsdr_median_vnd_cost_per_distance_180'	 => 'Medain vendor cost per distance for 180 Day',
			'tsdr_min_vnd_cost_per_duration_90'		 => 'Minium vendor cost per duration for 90 Day',
			'tsdr_max_vnd_cost_per_duration_90'		 => 'Maxmium vendor cost per duration for 90 Day',
			'tsdr_avg_vnd_cost_per_duration_90'		 => 'Average vendor cost per duration for 90 Day',
			'tsdr_median_vnd_cost_per_duration_90'	 => 'Medain vendor cost per duration for 90 Day',
			'tsdr_min_vnd_cost_per_duration_180'	 => 'Minium vendor cost per duration for 180 Day',
			'tsdr_max_vnd_cost_per_duration_180'	 => 'Maxmium vendor cost per duration for 180 Day',
			'tsdr_avg_vnd_cost_per_duration_180'	 => 'Average vendor cost per duration for 180 Day',
			'tsdr_median_vnd_cost_per_duration_180'	 => 'Medain vendor cost per duration for 180 Day',
			'tsdr_active'							 => '0 => inactive, 1 => active',
			'tsdr_create_at'						 => 'create date time',
			'tsdr_updated_at'						 => 'update date time',
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

		$criteria->compare('tsdr_id', $this->tsdr_id);
		$criteria->compare('tsdr_city_identifier', $this->tsdr_city_identifier, true);
		$criteria->compare('tsdr_min_travel_time_180', $this->tsdr_min_travel_time_180);
		$criteria->compare('tsdr_max_travel_time_180', $this->tsdr_max_travel_time_180);
		$criteria->compare('tsdr_avg_travel_time_180', $this->tsdr_avg_travel_time_180);
		$criteria->compare('tsdr_median_travel_time_180', $this->tsdr_median_travel_time_180);
		$criteria->compare('tsdr_min_travel_time_90', $this->tsdr_min_travel_time_90);
		$criteria->compare('tsdr_max_travel_time_90', $this->tsdr_max_travel_time_90);
		$criteria->compare('tsdr_avg_travel_time_90', $this->tsdr_avg_travel_time_90);
		$criteria->compare('tsdr_median_travel_time_90', $this->tsdr_median_travel_time_90);
		$criteria->compare('tsdr_min_cost_per_duration_180', $this->tsdr_min_cost_per_duration_180);
		$criteria->compare('tsdr_max_cost_per_duration_180', $this->tsdr_max_cost_per_duration_180);
		$criteria->compare('tsdr_avg_cost_per_duration_180', $this->tsdr_avg_cost_per_duration_180);
		$criteria->compare('tsdr_median_cost_per_duration_180', $this->tsdr_median_cost_per_duration_180);
		$criteria->compare('tsdr_min_cost_per_duration_90', $this->tsdr_min_cost_per_duration_90);
		$criteria->compare('tsdr_max_cost_per_duration_90', $this->tsdr_max_cost_per_duration_90);
		$criteria->compare('tsdr_avg_cost_per_duration_90', $this->tsdr_avg_cost_per_duration_90);
		$criteria->compare('tsdr_median_cost_per_duration_90', $this->tsdr_median_cost_per_duration_90);
		$criteria->compare('tsdr_min_cost_per_distance_180', $this->tsdr_min_cost_per_distance_180);
		$criteria->compare('tsdr_max_cost_per_distance_180', $this->tsdr_max_cost_per_distance_180);
		$criteria->compare('tsdr_avg_cost_per_distance_180', $this->tsdr_avg_cost_per_distance_180);
		$criteria->compare('tsdr_median_cost_per_distance_180', $this->tsdr_median_cost_per_distance_180);
		$criteria->compare('tsdr_min_cost_per_distance_90', $this->tsdr_min_cost_per_distance_90);
		$criteria->compare('tsdr_max_cost_per_distance_90', $this->tsdr_max_cost_per_distance_90);
		$criteria->compare('tsdr_avg_cost_per_distance_90', $this->tsdr_avg_cost_per_distance_90);
		$criteria->compare('tsdr_median_cost_per_distance_90', $this->tsdr_median_cost_per_distance_90);
		$criteria->compare('tsdr_min_vnd_cost_per_distance_90', $this->tsdr_min_vnd_cost_per_distance_90);
		$criteria->compare('tsdr_max_vnd_cost_per_distance_90', $this->tsdr_max_vnd_cost_per_distance_90);
		$criteria->compare('tsdr_avg_vnd_cost_per_distance_90', $this->tsdr_avg_vnd_cost_per_distance_90);
		$criteria->compare('tsdr_median_vnd_cost_per_distance_90', $this->tsdr_median_vnd_cost_per_distance_90);
		$criteria->compare('tsdr_min_vnd_cost_per_distance_180', $this->tsdr_min_vnd_cost_per_distance_180);
		$criteria->compare('tsdr_max_vnd_cost_per_distance_180', $this->tsdr_max_vnd_cost_per_distance_180);
		$criteria->compare('tsdr_avg_vnd_cost_per_distance_180', $this->tsdr_avg_vnd_cost_per_distance_180);
		$criteria->compare('tsdr_median_vnd_cost_per_distance_180', $this->tsdr_median_vnd_cost_per_distance_180);
		$criteria->compare('tsdr_min_vnd_cost_per_duration_90', $this->tsdr_min_vnd_cost_per_duration_90);
		$criteria->compare('tsdr_max_vnd_cost_per_duration_90', $this->tsdr_max_vnd_cost_per_duration_90);
		$criteria->compare('tsdr_avg_vnd_cost_per_duration_90', $this->tsdr_avg_vnd_cost_per_duration_90);
		$criteria->compare('tsdr_median_vnd_cost_per_duration_90', $this->tsdr_median_vnd_cost_per_duration_90);
		$criteria->compare('tsdr_min_vnd_cost_per_duration_180', $this->tsdr_min_vnd_cost_per_duration_180);
		$criteria->compare('tsdr_max_vnd_cost_per_duration_180', $this->tsdr_max_vnd_cost_per_duration_180);
		$criteria->compare('tsdr_avg_vnd_cost_per_duration_180', $this->tsdr_avg_vnd_cost_per_duration_180);
		$criteria->compare('tsdr_median_vnd_cost_per_duration_180', $this->tsdr_median_vnd_cost_per_duration_180);
		$criteria->compare('tsdr_active', $this->tsdr_active);
		$criteria->compare('tsdr_create_at', $this->tsdr_create_at, true);
		$criteria->compare('tsdr_updated_at', $this->tsdr_updated_at, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TravelStatsDr the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getVendorCostPerDistanceDR($cityIdentifier)
	{
		$sql = "SELECT ROUND(SUM(travel_stats_dr.tsdr_max_vnd_cost_per_duration_180)/COUNT(travel_stats_dr.tsdr_id),2) AS vndCostPerDistance FROM travel_stats_dr WHERE 1 AND travel_stats_dr.tsdr_city_identifier =:cityIdentifier AND  travel_stats_dr.tsdr_active=1 ";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), array('cityIdentifier' => $cityIdentifier));
	}

}

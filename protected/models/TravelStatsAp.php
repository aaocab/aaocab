<?php

/**
 * This is the model class for table "travel_stats_ap".
 *
 * The followings are the available columns in table 'travel_stats_ap':
 * @property integer $tsa_id
 * @property string $tsa_city_identifier
 * @property double $tsa_min_travel_time_180
 * @property double $tsa_max_travel_time_180
 * @property double $tsa_avg_travel_time_180
 * @property double $tsa_median_travel_time_180
 * @property double $tsa_min_travel_time_90
 * @property double $tsa_max_travel_time_90
 * @property double $tsa_avg_travel_time_90
 * @property double $tsa_median_travel_time_90
 * @property double $tsa_min_cost_per_duration_180
 * @property double $tsa_max_cost_per_duration_180
 * @property double $tsa_avg_cost_per_duration_180
 * @property double $tsa_median_cost_per_duration_180
 * @property double $tsa_min_cost_per_duration_90
 * @property double $tsa_max_cost_per_duration_90
 * @property double $tsa_avg_cost_per_duration_90
 * @property double $tsa_median_cost_per_duration_90
 * @property double $tsa_min_cost_per_distance_180
 * @property double $tsa_max_cost_per_distance_180
 * @property double $tsa_avg_cost_per_distance_180
 * @property double $tsa_median_cost_per_distance_180
 * @property double $tsa_min_cost_per_distance_90
 * @property double $tsa_max_cost_per_distance_90
 * @property double $tsa_avg_cost_per_distance_90
 * @property double $tsa_median_cost_per_distance_90
 * @property double $tsa_min_vnd_cost_per_distance_90
 * @property double $tsa_max_vnd_cost_per_distance_90
 * @property double $tsa_avg_vnd_cost_per_distance_90
 * @property double $tsa_median_vnd_cost_per_distance_90
 * @property double $tsa_min_vnd_cost_per_distance_180
 * @property double $tsa_max_vnd_cost_per_distance_180
 * @property double $tsa_avg_vnd_cost_per_distance_180
 * @property double $tsa_median_vnd_cost_per_distance_180
 * @property double $tsa_min_vnd_cost_per_duration_90
 * @property double $tsa_max_vnd_cost_per_duration_90
 * @property double $tsa_avg_vnd_cost_per_duration_90
 * @property double $tsa_median_vnd_cost_per_duration_90
 * @property double $tsa_min_vnd_cost_per_duration_180
 * @property double $tsa_max_vnd_cost_per_duration_180
 * @property double $tsa_avg_vnd_cost_per_duration_180
 * @property double $tsa_median_vnd_cost_per_duration_180
 * @property integer $tsa_active
 * @property string $tsa_create_at
 * @property string $tsa_updated_at
 */
class TravelStatsAp extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'travel_stats_ap';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tsa_city_identifier, tsa_create_at, tsa_updated_at', 'required'),
			array('tsa_active', 'numerical', 'integerOnly' => true),
			array('tsa_min_travel_time_180, tsa_max_travel_time_180, tsa_avg_travel_time_180, tsa_median_travel_time_180, tsa_min_travel_time_90, tsa_max_travel_time_90, tsa_avg_travel_time_90, tsa_median_travel_time_90, tsa_min_cost_per_duration_180, tsa_max_cost_per_duration_180, tsa_avg_cost_per_duration_180, tsa_median_cost_per_duration_180, tsa_min_cost_per_duration_90, tsa_max_cost_per_duration_90, tsa_avg_cost_per_duration_90, tsa_median_cost_per_duration_90, tsa_min_cost_per_distance_180, tsa_max_cost_per_distance_180, tsa_avg_cost_per_distance_180, tsa_median_cost_per_distance_180, tsa_min_cost_per_distance_90, tsa_max_cost_per_distance_90, tsa_avg_cost_per_distance_90, tsa_median_cost_per_distance_90, tsa_min_vnd_cost_per_distance_90, tsa_max_vnd_cost_per_distance_90, tsa_avg_vnd_cost_per_distance_90, tsa_median_vnd_cost_per_distance_90, tsa_min_vnd_cost_per_distance_180, tsa_max_vnd_cost_per_distance_180, tsa_avg_vnd_cost_per_distance_180, tsa_median_vnd_cost_per_distance_180, tsa_min_vnd_cost_per_duration_90, tsa_max_vnd_cost_per_duration_90, tsa_avg_vnd_cost_per_duration_90, tsa_median_vnd_cost_per_duration_90, tsa_min_vnd_cost_per_duration_180, tsa_max_vnd_cost_per_duration_180, tsa_avg_vnd_cost_per_duration_180, tsa_median_vnd_cost_per_duration_180', 'numerical'),
			array('tsa_city_identifier', 'length', 'max' => 23),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('tsa_id, tsa_city_identifier, tsa_min_travel_time_180, tsa_max_travel_time_180, tsa_avg_travel_time_180, tsa_median_travel_time_180, tsa_min_travel_time_90, tsa_max_travel_time_90, tsa_avg_travel_time_90, tsa_median_travel_time_90, tsa_min_cost_per_duration_180, tsa_max_cost_per_duration_180, tsa_avg_cost_per_duration_180, tsa_median_cost_per_duration_180, tsa_min_cost_per_duration_90, tsa_max_cost_per_duration_90, tsa_avg_cost_per_duration_90, tsa_median_cost_per_duration_90, tsa_min_cost_per_distance_180, tsa_max_cost_per_distance_180, tsa_avg_cost_per_distance_180, tsa_median_cost_per_distance_180, tsa_min_cost_per_distance_90, tsa_max_cost_per_distance_90, tsa_avg_cost_per_distance_90, tsa_median_cost_per_distance_90, tsa_min_vnd_cost_per_distance_90, tsa_max_vnd_cost_per_distance_90, tsa_avg_vnd_cost_per_distance_90, tsa_median_vnd_cost_per_distance_90, tsa_min_vnd_cost_per_distance_180, tsa_max_vnd_cost_per_distance_180, tsa_avg_vnd_cost_per_distance_180, tsa_median_vnd_cost_per_distance_180, tsa_min_vnd_cost_per_duration_90, tsa_max_vnd_cost_per_duration_90, tsa_avg_vnd_cost_per_duration_90, tsa_median_vnd_cost_per_duration_90, tsa_min_vnd_cost_per_duration_180, tsa_max_vnd_cost_per_duration_180, tsa_avg_vnd_cost_per_duration_180, tsa_median_vnd_cost_per_duration_180, tsa_active, tsa_create_at, tsa_updated_at', 'safe', 'on' => 'search'),
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
			'tsa_id'								 => 'Tsa',
			'tsa_city_identifier'					 => 'city_identifier(RegionId-FromCity-ToCity)',
			'tsa_min_travel_time_180'				 => 'Minium travel time in Mintue over 180 Day',
			'tsa_max_travel_time_180'				 => 'Maxmium travel time in Mintue over 180 Day',
			'tsa_avg_travel_time_180'				 => 'Average travel time in Mintue over 180 Day',
			'tsa_median_travel_time_180'			 => 'Median travel time in Mintue over 180 Day',
			'tsa_min_travel_time_90'				 => 'Minium travel time in Mintue over 90 Day',
			'tsa_max_travel_time_90'				 => 'Maxmium travel time in Mintue over 90 Day',
			'tsa_avg_travel_time_90'				 => 'Average travel time in Mintue over 90 Day',
			'tsa_median_travel_time_90'				 => 'Median travel time in Mintue over 90 Day',
			'tsa_min_cost_per_duration_180'			 => 'Minium cost per duration for 180 Day ',
			'tsa_max_cost_per_duration_180'			 => 'Max cost per duration for 180 Day ',
			'tsa_avg_cost_per_duration_180'			 => 'Avg cost per duration for 180 Day ',
			'tsa_median_cost_per_duration_180'		 => 'Median cost per duration for 180 Day ',
			'tsa_min_cost_per_duration_90'			 => 'Minium cost per duration for 90 Day ',
			'tsa_max_cost_per_duration_90'			 => 'Max cost per duration for 90 Day ',
			'tsa_avg_cost_per_duration_90'			 => 'Avg cost per duration for 90 Day ',
			'tsa_median_cost_per_duration_90'		 => 'Median cost per duration for 90 Day ',
			'tsa_min_cost_per_distance_180'			 => 'Minium  cost per distance for  180 Day	',
			'tsa_max_cost_per_distance_180'			 => 'Max cost per distance for  180 Day	',
			'tsa_avg_cost_per_distance_180'			 => 'Avg cost per distance for  180 Day	',
			'tsa_median_cost_per_distance_180'		 => 'Median  cost per distance for  180 Day	',
			'tsa_min_cost_per_distance_90'			 => 'Minium  cost per distance for  90 Day	',
			'tsa_max_cost_per_distance_90'			 => 'Max cost per distance for 90 Day	',
			'tsa_avg_cost_per_distance_90'			 => 'Avg cost per distance for  90 Day	',
			'tsa_median_cost_per_distance_90'		 => 'Median cost per distance for  90 Day	',
			'tsa_min_vnd_cost_per_distance_90'		 => 'Minium vendor cost per distance for 90 Day',
			'tsa_max_vnd_cost_per_distance_90'		 => 'Maxmium vendor cost per distance for 90 Day',
			'tsa_avg_vnd_cost_per_distance_90'		 => 'Average vendor cost per distance for 90 Day',
			'tsa_median_vnd_cost_per_distance_90'	 => 'Medain vendor cost per distance for 90 Day',
			'tsa_min_vnd_cost_per_distance_180'		 => 'Minium vendor cost per distance for 180 Day',
			'tsa_max_vnd_cost_per_distance_180'		 => 'Maxmium vendor cost per distance for 180 Day',
			'tsa_avg_vnd_cost_per_distance_180'		 => 'Average vendor cost per distance for 180 Day',
			'tsa_median_vnd_cost_per_distance_180'	 => 'Medain vendor cost per distance for 180 Day',
			'tsa_min_vnd_cost_per_duration_90'		 => 'Minium vendor cost per duration for 90 Day',
			'tsa_max_vnd_cost_per_duration_90'		 => 'Maxmium vendor cost per duration for 90 Day',
			'tsa_avg_vnd_cost_per_duration_90'		 => 'Average vendor cost per duration for 90 Day',
			'tsa_median_vnd_cost_per_duration_90'	 => 'Medain vendor cost per duration for 90 Day',
			'tsa_min_vnd_cost_per_duration_180'		 => 'Minium vendor cost per duration for 180 Day',
			'tsa_max_vnd_cost_per_duration_180'		 => 'Maxmium vendor cost per duration for 180 Day',
			'tsa_avg_vnd_cost_per_duration_180'		 => 'Average vendor cost per duration for 180 Day',
			'tsa_median_vnd_cost_per_duration_180'	 => 'Medain vendor cost per duration for 180 Day',
			'tsa_active'							 => '0 => inactive, 1 => active',
			'tsa_create_at'							 => 'create date time',
			'tsa_updated_at'						 => 'update date time',
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

		$criteria->compare('tsa_id', $this->tsa_id);
		$criteria->compare('tsa_city_identifier', $this->tsa_city_identifier, true);
		$criteria->compare('tsa_min_travel_time_180', $this->tsa_min_travel_time_180);
		$criteria->compare('tsa_max_travel_time_180', $this->tsa_max_travel_time_180);
		$criteria->compare('tsa_avg_travel_time_180', $this->tsa_avg_travel_time_180);
		$criteria->compare('tsa_median_travel_time_180', $this->tsa_median_travel_time_180);
		$criteria->compare('tsa_min_travel_time_90', $this->tsa_min_travel_time_90);
		$criteria->compare('tsa_max_travel_time_90', $this->tsa_max_travel_time_90);
		$criteria->compare('tsa_avg_travel_time_90', $this->tsa_avg_travel_time_90);
		$criteria->compare('tsa_median_travel_time_90', $this->tsa_median_travel_time_90);
		$criteria->compare('tsa_min_cost_per_duration_180', $this->tsa_min_cost_per_duration_180);
		$criteria->compare('tsa_max_cost_per_duration_180', $this->tsa_max_cost_per_duration_180);
		$criteria->compare('tsa_avg_cost_per_duration_180', $this->tsa_avg_cost_per_duration_180);
		$criteria->compare('tsa_median_cost_per_duration_180', $this->tsa_median_cost_per_duration_180);
		$criteria->compare('tsa_min_cost_per_duration_90', $this->tsa_min_cost_per_duration_90);
		$criteria->compare('tsa_max_cost_per_duration_90', $this->tsa_max_cost_per_duration_90);
		$criteria->compare('tsa_avg_cost_per_duration_90', $this->tsa_avg_cost_per_duration_90);
		$criteria->compare('tsa_median_cost_per_duration_90', $this->tsa_median_cost_per_duration_90);
		$criteria->compare('tsa_min_cost_per_distance_180', $this->tsa_min_cost_per_distance_180);
		$criteria->compare('tsa_max_cost_per_distance_180', $this->tsa_max_cost_per_distance_180);
		$criteria->compare('tsa_avg_cost_per_distance_180', $this->tsa_avg_cost_per_distance_180);
		$criteria->compare('tsa_median_cost_per_distance_180', $this->tsa_median_cost_per_distance_180);
		$criteria->compare('tsa_min_cost_per_distance_90', $this->tsa_min_cost_per_distance_90);
		$criteria->compare('tsa_max_cost_per_distance_90', $this->tsa_max_cost_per_distance_90);
		$criteria->compare('tsa_avg_cost_per_distance_90', $this->tsa_avg_cost_per_distance_90);
		$criteria->compare('tsa_median_cost_per_distance_90', $this->tsa_median_cost_per_distance_90);
		$criteria->compare('tsa_min_vnd_cost_per_distance_90', $this->tsa_min_vnd_cost_per_distance_90);
		$criteria->compare('tsa_max_vnd_cost_per_distance_90', $this->tsa_max_vnd_cost_per_distance_90);
		$criteria->compare('tsa_avg_vnd_cost_per_distance_90', $this->tsa_avg_vnd_cost_per_distance_90);
		$criteria->compare('tsa_median_vnd_cost_per_distance_90', $this->tsa_median_vnd_cost_per_distance_90);
		$criteria->compare('tsa_min_vnd_cost_per_distance_180', $this->tsa_min_vnd_cost_per_distance_180);
		$criteria->compare('tsa_max_vnd_cost_per_distance_180', $this->tsa_max_vnd_cost_per_distance_180);
		$criteria->compare('tsa_avg_vnd_cost_per_distance_180', $this->tsa_avg_vnd_cost_per_distance_180);
		$criteria->compare('tsa_median_vnd_cost_per_distance_180', $this->tsa_median_vnd_cost_per_distance_180);
		$criteria->compare('tsa_min_vnd_cost_per_duration_90', $this->tsa_min_vnd_cost_per_duration_90);
		$criteria->compare('tsa_max_vnd_cost_per_duration_90', $this->tsa_max_vnd_cost_per_duration_90);
		$criteria->compare('tsa_avg_vnd_cost_per_duration_90', $this->tsa_avg_vnd_cost_per_duration_90);
		$criteria->compare('tsa_median_vnd_cost_per_duration_90', $this->tsa_median_vnd_cost_per_duration_90);
		$criteria->compare('tsa_min_vnd_cost_per_duration_180', $this->tsa_min_vnd_cost_per_duration_180);
		$criteria->compare('tsa_max_vnd_cost_per_duration_180', $this->tsa_max_vnd_cost_per_duration_180);
		$criteria->compare('tsa_avg_vnd_cost_per_duration_180', $this->tsa_avg_vnd_cost_per_duration_180);
		$criteria->compare('tsa_median_vnd_cost_per_duration_180', $this->tsa_median_vnd_cost_per_duration_180);
		$criteria->compare('tsa_active', $this->tsa_active);
		$criteria->compare('tsa_create_at', $this->tsa_create_at, true);
		$criteria->compare('tsa_updated_at', $this->tsa_updated_at, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TravelStatsAp the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getVendorCostPerDistanceAP($cityIdentifier)
	{
		$sql = "SELECT ROUND(SUM(travel_stats_ap.tsa_max_vnd_cost_per_duration_180)/COUNT(travel_stats_ap.tsa_id),2) AS vndCostPerDistance FROM travel_stats_ap WHERE 1 AND travel_stats_ap.tsa_city_identifier =:cityIdentifier AND  travel_stats_ap.tsa_active=1 ";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), array('cityIdentifier' => $cityIdentifier));
	}

}

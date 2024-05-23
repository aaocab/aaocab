<?php

/**
 * This is the model class for table "travel_stats_ow".
 *
 * The followings are the available columns in table 'travel_stats_ow':
 * @property integer $tso_id
 * @property string $tso_city_identifier
 * @property double $tso_min_travel_time_180
 * @property double $tso_max_travel_time_180
 * @property double $tso_avg_travel_time_180
 * @property double $tso_median_travel_time_180
 * @property double $tso_min_travel_time_90
 * @property double $tso_max_travel_time_90
 * @property double $tso_avg_travel_time_90
 * @property double $tso_median_travel_time_90
 * @property double $tso_min_cost_per_duration_180
 * @property double $tso_max_cost_per_duration_180
 * @property double $tso_avg_cost_per_duration_180
 * @property double $tso_median_cost_per_duration_180
 * @property double $tso_min_cost_per_duration_90
 * @property double $tso_max_cost_per_duration_90
 * @property double $tso_avg_cost_per_duration_90
 * @property double $tso_median_cost_per_duration_90
 * @property double $tso_min_cost_per_distance_180
 * @property double $tso_max_cost_per_distance_180
 * @property double $tso_avg_cost_per_distance_180
 * @property double $tso_median_cost_per_distance_180
 * @property double $tso_min_cost_per_distance_90
 * @property double $tso_max_cost_per_distance_90
 * @property double $tso_avg_cost_per_distance_90
 * @property double $tso_median_cost_per_distance_90
 * @property double $tso_min_vnd_cost_per_distance_90
 * @property double $tso_max_vnd_cost_per_distance_90
 * @property double $tso_avg_vnd_cost_per_distance_90
 * @property double $tso_median_vnd_cost_per_distance_90
 * @property double $tso_min_vnd_cost_per_distance_180
 * @property double $tso_max_vnd_cost_per_distance_180
 * @property double $tso_avg_vnd_cost_per_distance_180
 * @property double $tso_median_vnd_cost_per_distance_180
 * @property double $tso_min_vnd_cost_per_duration_90
 * @property double $tso_max_vnd_cost_per_duration_90
 * @property double $tso_avg_vnd_cost_per_duration_90
 * @property double $tso_median_vnd_cost_per_duration_90
 * @property double $tso_min_vnd_cost_per_duration_180
 * @property double $tso_max_vnd_cost_per_duration_180
 * @property double $tso_avg_vnd_cost_per_duration_180
 * @property double $tso_median_vnd_cost_per_duration_180
 * @property integer $tso_active
 * @property string $tso_create_at
 * @property string $tso_updated_at
 */
class TravelStatsOw extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'travel_stats_ow';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tso_city_identifier, tso_create_at, tso_updated_at', 'required'),
			array('tso_active', 'numerical', 'integerOnly' => true),
			array('tso_min_travel_time_180, tso_max_travel_time_180, tso_avg_travel_time_180, tso_median_travel_time_180, tso_min_travel_time_90, tso_max_travel_time_90, tso_avg_travel_time_90, tso_median_travel_time_90, tso_min_cost_per_duration_180, tso_max_cost_per_duration_180, tso_avg_cost_per_duration_180, tso_median_cost_per_duration_180, tso_min_cost_per_duration_90, tso_max_cost_per_duration_90, tso_avg_cost_per_duration_90, tso_median_cost_per_duration_90, tso_min_cost_per_distance_180, tso_max_cost_per_distance_180, tso_avg_cost_per_distance_180, tso_median_cost_per_distance_180, tso_min_cost_per_distance_90, tso_max_cost_per_distance_90, tso_avg_cost_per_distance_90, tso_median_cost_per_distance_90, tso_min_vnd_cost_per_distance_90, tso_max_vnd_cost_per_distance_90, tso_avg_vnd_cost_per_distance_90, tso_median_vnd_cost_per_distance_90, tso_min_vnd_cost_per_distance_180, tso_max_vnd_cost_per_distance_180, tso_avg_vnd_cost_per_distance_180, tso_median_vnd_cost_per_distance_180, tso_min_vnd_cost_per_duration_90, tso_max_vnd_cost_per_duration_90, tso_avg_vnd_cost_per_duration_90, tso_median_vnd_cost_per_duration_90, tso_min_vnd_cost_per_duration_180, tso_max_vnd_cost_per_duration_180, tso_avg_vnd_cost_per_duration_180, tso_median_vnd_cost_per_duration_180', 'numerical'),
			array('tso_city_identifier', 'length', 'max' => 23),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('tso_id, tso_city_identifier, tso_min_travel_time_180, tso_max_travel_time_180, tso_avg_travel_time_180, tso_median_travel_time_180, tso_min_travel_time_90, tso_max_travel_time_90, tso_avg_travel_time_90, tso_median_travel_time_90, tso_min_cost_per_duration_180, tso_max_cost_per_duration_180, tso_avg_cost_per_duration_180, tso_median_cost_per_duration_180, tso_min_cost_per_duration_90, tso_max_cost_per_duration_90, tso_avg_cost_per_duration_90, tso_median_cost_per_duration_90, tso_min_cost_per_distance_180, tso_max_cost_per_distance_180, tso_avg_cost_per_distance_180, tso_median_cost_per_distance_180, tso_min_cost_per_distance_90, tso_max_cost_per_distance_90, tso_avg_cost_per_distance_90, tso_median_cost_per_distance_90, tso_min_vnd_cost_per_distance_90, tso_max_vnd_cost_per_distance_90, tso_avg_vnd_cost_per_distance_90, tso_median_vnd_cost_per_distance_90, tso_min_vnd_cost_per_distance_180, tso_max_vnd_cost_per_distance_180, tso_avg_vnd_cost_per_distance_180, tso_median_vnd_cost_per_distance_180, tso_min_vnd_cost_per_duration_90, tso_max_vnd_cost_per_duration_90, tso_avg_vnd_cost_per_duration_90, tso_median_vnd_cost_per_duration_90, tso_min_vnd_cost_per_duration_180, tso_max_vnd_cost_per_duration_180, tso_avg_vnd_cost_per_duration_180, tso_median_vnd_cost_per_duration_180, tso_active, tso_create_at, tso_updated_at', 'safe', 'on' => 'search'),
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
			'tso_id'								 => 'Tso',
			'tso_city_identifier'					 => 'city_identifier(RegionId-FromCity-ToCity)',
			'tso_min_travel_time_180'				 => 'Minium travel time in Mintue over 180 Day',
			'tso_max_travel_time_180'				 => 'Maxmium travel time in Mintue over 180 Day',
			'tso_avg_travel_time_180'				 => 'Average travel time in Mintue over 180 Day',
			'tso_median_travel_time_180'			 => 'Median travel time in Mintue over 180 Day',
			'tso_min_travel_time_90'				 => 'Minium travel time in Mintue over 90 Day',
			'tso_max_travel_time_90'				 => 'Maxmium travel time in Mintue over 90 Day',
			'tso_avg_travel_time_90'				 => 'Average travel time in Mintue over 90 Day',
			'tso_median_travel_time_90'				 => 'Median travel time in Mintue over 90 Day',
			'tso_min_cost_per_duration_180'			 => 'Minium cost per duration for 180 Day ',
			'tso_max_cost_per_duration_180'			 => 'Max cost per duration for 180 Day ',
			'tso_avg_cost_per_duration_180'			 => 'Avg cost per duration for 180 Day ',
			'tso_median_cost_per_duration_180'		 => 'Median cost per duration for 180 Day ',
			'tso_min_cost_per_duration_90'			 => 'Minium cost per duration for 90 Day ',
			'tso_max_cost_per_duration_90'			 => 'Max cost per duration for 90 Day ',
			'tso_avg_cost_per_duration_90'			 => 'Avg cost per duration for 90 Day ',
			'tso_median_cost_per_duration_90'		 => 'Median cost per duration for 90 Day ',
			'tso_min_cost_per_distance_180'			 => 'Minium  cost per distance for  180 Day	',
			'tso_max_cost_per_distance_180'			 => 'Max cost per distance for  180 Day	',
			'tso_avg_cost_per_distance_180'			 => 'Avg cost per distance for  180 Day	',
			'tso_median_cost_per_distance_180'		 => 'Median  cost per distance for  180 Day	',
			'tso_min_cost_per_distance_90'			 => 'Minium  cost per distance for  90 Day	',
			'tso_max_cost_per_distance_90'			 => 'Max cost per distance for 90 Day	',
			'tso_avg_cost_per_distance_90'			 => 'Avg cost per distance for  90 Day	',
			'tso_median_cost_per_distance_90'		 => 'Median cost per distance for  90 Day	',
			'tso_min_vnd_cost_per_distance_90'		 => 'Minium vendor cost per distance for 90 Day',
			'tso_max_vnd_cost_per_distance_90'		 => 'Maxmium vendor cost per distance for 90 Day',
			'tso_avg_vnd_cost_per_distance_90'		 => 'Average vendor cost per distance for 90 Day',
			'tso_median_vnd_cost_per_distance_90'	 => 'Medain vendor cost per distance for 90 Day',
			'tso_min_vnd_cost_per_distance_180'		 => 'Minium vendor cost per distance for 180 Day',
			'tso_max_vnd_cost_per_distance_180'		 => 'Maxmium vendor cost per distance for 180 Day',
			'tso_avg_vnd_cost_per_distance_180'		 => 'Average vendor cost per distance for 180 Day',
			'tso_median_vnd_cost_per_distance_180'	 => 'Medain vendor cost per distance for 180 Day',
			'tso_min_vnd_cost_per_duration_90'		 => 'Minium vendor cost per duration for 90 Day',
			'tso_max_vnd_cost_per_duration_90'		 => 'Maxmium vendor cost per duration for 90 Day',
			'tso_avg_vnd_cost_per_duration_90'		 => 'Average vendor cost per duration for 90 Day',
			'tso_median_vnd_cost_per_duration_90'	 => 'Medain vendor cost per duration for 90 Day',
			'tso_min_vnd_cost_per_duration_180'		 => 'Minium vendor cost per duration for 180 Day',
			'tso_max_vnd_cost_per_duration_180'		 => 'Maxmium vendor cost per duration for 180 Day',
			'tso_avg_vnd_cost_per_duration_180'		 => 'Average vendor cost per duration for 180 Day',
			'tso_median_vnd_cost_per_duration_180'	 => 'Medain vendor cost per duration for 180 Day',
			'tso_active'							 => '0 => inactive, 1 => active',
			'tso_create_at'							 => 'create date time',
			'tso_updated_at'						 => 'update date time',
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

		$criteria->compare('tso_id', $this->tso_id);
		$criteria->compare('tso_city_identifier', $this->tso_city_identifier, true);
		$criteria->compare('tso_min_travel_time_180', $this->tso_min_travel_time_180);
		$criteria->compare('tso_max_travel_time_180', $this->tso_max_travel_time_180);
		$criteria->compare('tso_avg_travel_time_180', $this->tso_avg_travel_time_180);
		$criteria->compare('tso_median_travel_time_180', $this->tso_median_travel_time_180);
		$criteria->compare('tso_min_travel_time_90', $this->tso_min_travel_time_90);
		$criteria->compare('tso_max_travel_time_90', $this->tso_max_travel_time_90);
		$criteria->compare('tso_avg_travel_time_90', $this->tso_avg_travel_time_90);
		$criteria->compare('tso_median_travel_time_90', $this->tso_median_travel_time_90);
		$criteria->compare('tso_min_cost_per_duration_180', $this->tso_min_cost_per_duration_180);
		$criteria->compare('tso_max_cost_per_duration_180', $this->tso_max_cost_per_duration_180);
		$criteria->compare('tso_avg_cost_per_duration_180', $this->tso_avg_cost_per_duration_180);
		$criteria->compare('tso_median_cost_per_duration_180', $this->tso_median_cost_per_duration_180);
		$criteria->compare('tso_min_cost_per_duration_90', $this->tso_min_cost_per_duration_90);
		$criteria->compare('tso_max_cost_per_duration_90', $this->tso_max_cost_per_duration_90);
		$criteria->compare('tso_avg_cost_per_duration_90', $this->tso_avg_cost_per_duration_90);
		$criteria->compare('tso_median_cost_per_duration_90', $this->tso_median_cost_per_duration_90);
		$criteria->compare('tso_min_cost_per_distance_180', $this->tso_min_cost_per_distance_180);
		$criteria->compare('tso_max_cost_per_distance_180', $this->tso_max_cost_per_distance_180);
		$criteria->compare('tso_avg_cost_per_distance_180', $this->tso_avg_cost_per_distance_180);
		$criteria->compare('tso_median_cost_per_distance_180', $this->tso_median_cost_per_distance_180);
		$criteria->compare('tso_min_cost_per_distance_90', $this->tso_min_cost_per_distance_90);
		$criteria->compare('tso_max_cost_per_distance_90', $this->tso_max_cost_per_distance_90);
		$criteria->compare('tso_avg_cost_per_distance_90', $this->tso_avg_cost_per_distance_90);
		$criteria->compare('tso_median_cost_per_distance_90', $this->tso_median_cost_per_distance_90);
		$criteria->compare('tso_min_vnd_cost_per_distance_90', $this->tso_min_vnd_cost_per_distance_90);
		$criteria->compare('tso_max_vnd_cost_per_distance_90', $this->tso_max_vnd_cost_per_distance_90);
		$criteria->compare('tso_avg_vnd_cost_per_distance_90', $this->tso_avg_vnd_cost_per_distance_90);
		$criteria->compare('tso_median_vnd_cost_per_distance_90', $this->tso_median_vnd_cost_per_distance_90);
		$criteria->compare('tso_min_vnd_cost_per_distance_180', $this->tso_min_vnd_cost_per_distance_180);
		$criteria->compare('tso_max_vnd_cost_per_distance_180', $this->tso_max_vnd_cost_per_distance_180);
		$criteria->compare('tso_avg_vnd_cost_per_distance_180', $this->tso_avg_vnd_cost_per_distance_180);
		$criteria->compare('tso_median_vnd_cost_per_distance_180', $this->tso_median_vnd_cost_per_distance_180);
		$criteria->compare('tso_min_vnd_cost_per_duration_90', $this->tso_min_vnd_cost_per_duration_90);
		$criteria->compare('tso_max_vnd_cost_per_duration_90', $this->tso_max_vnd_cost_per_duration_90);
		$criteria->compare('tso_avg_vnd_cost_per_duration_90', $this->tso_avg_vnd_cost_per_duration_90);
		$criteria->compare('tso_median_vnd_cost_per_duration_90', $this->tso_median_vnd_cost_per_duration_90);
		$criteria->compare('tso_min_vnd_cost_per_duration_180', $this->tso_min_vnd_cost_per_duration_180);
		$criteria->compare('tso_max_vnd_cost_per_duration_180', $this->tso_max_vnd_cost_per_duration_180);
		$criteria->compare('tso_avg_vnd_cost_per_duration_180', $this->tso_avg_vnd_cost_per_duration_180);
		$criteria->compare('tso_median_vnd_cost_per_duration_180', $this->tso_median_vnd_cost_per_duration_180);
		$criteria->compare('tso_active', $this->tso_active);
		$criteria->compare('tso_create_at', $this->tso_create_at, true);
		$criteria->compare('tso_updated_at', $this->tso_updated_at, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TravelStatsOw the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getVendorCostPerDistanceOW($cityIdentifier)
	{
		$sql = "SELECT ROUND(SUM(travel_stats_ow.tso_max_vnd_cost_per_duration_180)/COUNT(travel_stats_ow.tso_id),2) AS vndCostPerDistance FROM travel_stats_ow WHERE 1 AND travel_stats_ow.tso_city_identifier =:cityIdentifier AND  travel_stats_ow.tso_active=1 ";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), array('cityIdentifier' => $cityIdentifier));
	}

}

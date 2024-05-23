<?php

/**
 * This is the model class for table "blocked_locations".
 *
 * The followings are the available columns in table 'blocked_locations':
 * @property integer $bll_id
 * @property string $bll_desc
 * @property integer $bll_city_id
 * @property string $bll_lat
 * @property string $bll_long
 * @property string $bll_from_date
 * @property string $bll_to_date
 * @property string $bll_bounds
 * @property integer $bll_active
 * @property string $bll_created_at
 */
class BlockedLocations extends CActiveRecord
{
	public $distance;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'blocked_locations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bll_created_at', 'required'),
			array('bll_city_id, bll_active', 'numerical', 'integerOnly'=>true),
			array('bll_desc', 'length', 'max'=>500),
			array('bll_lat, bll_long', 'length', 'max'=>10),
			array('bll_from_date, bll_to_date, bll_bounds', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bll_id, bll_desc, bll_city_id, bll_lat, bll_long, bll_from_date, bll_to_date, bll_bounds, bll_active, bll_created_at', 'safe', 'on'=>'search'),
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
			'bll_id' => 'Bll',
			'bll_desc' => 'Bll Desc',
			'bll_city_id' => 'Bll City',
			'bll_lat' => 'Bll Lat',
			'bll_long' => 'Bll Long',
			'bll_from_date' => 'Bll From Date',
			'bll_to_date' => 'Bll To Date',
			'bll_bounds' => 'Bll Bounds',
			'bll_active' => 'Bll Active',
			'bll_created_at' => 'Bll Created At',
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

		$criteria=new CDbCriteria;

		$criteria->compare('bll_id',$this->bll_id);
		$criteria->compare('bll_desc',$this->bll_desc,true);
		$criteria->compare('bll_city_id',$this->bll_city_id);
		$criteria->compare('bll_lat',$this->bll_lat,true);
		$criteria->compare('bll_long',$this->bll_long,true);
		$criteria->compare('bll_from_date',$this->bll_from_date,true);
		$criteria->compare('bll_to_date',$this->bll_to_date,true);
		$criteria->compare('bll_bounds',$this->bll_bounds,true);
		$criteria->compare('bll_active',$this->bll_active);
		$criteria->compare('bll_created_at',$this->bll_created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BlockedLocations the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 
	 * @param \Stub\common\Place $placeObj
	 * @param type $distance
	 * @param type $margin
	 * @return int | false 
	 */
	public static function getBlockedLocation($placeObj)
	{
		$sql	 = "SELECT bll_id FROM blocked_locations
					WHERE bll_lat BETWEEN (:lat - 0.1) AND (:lat + 0.1) 
						AND bll_long BETWEEN (:long - 0.1) AND (:long + 0.1)
						AND bll_active = 1 AND checkBounds(bll_bounds, :lat, :long, 0.01)";
		$params	 = [
			"lat"		 => $placeObj->coordinates->latitude,
			"long"		 => $placeObj->coordinates->longitude,
		];

		$locationId			 = DBUtil::queryScalar($sql, DBUtil::SDB2(), $params);
		
		return $locationId;
	}
		
	 
}

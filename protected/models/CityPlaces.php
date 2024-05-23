<?php

/**
 * This is the model class for table "city_places".
 *
 * The followings are the available columns in table 'city_places':
 * @property integer $cpl_id
 * @property integer $cpl_user_id
 * @property string $cpl_user_ip
 * @property integer $cpl_city_id
 * @property integer $cpl_category
 * @property string $cpl_places
 * @property string $cpl_url
 * @property integer $cpl_status
 * @property integer $cpl_active
 * @property string $cpl_datetime
 */
class CityPlaces extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'city_places';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cpl_city_id, cpl_category, cpl_places, cpl_status, cpl_active, cpl_datetime', 'required'),
			array('cpl_user_id, cpl_city_id, cpl_category, cpl_status, cpl_active', 'numerical', 'integerOnly' => true),
			array('cpl_user_ip', 'length', 'max' => 100),
			array('cpl_places', 'length', 'max' => 1500),
			array('cpl_url', 'length', 'max' => 150),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cpl_id, cpl_user_id, cpl_user_ip, cpl_city_id, cpl_category, cpl_places, cpl_url, cpl_status, cpl_active, cpl_datetime', 'safe', 'on' => 'search'),
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
			'cplCities'	 => array(self::BELONGS_TO, 'Cities', 'cpl_city_id'),
			'cplUser'	 => [self::BELONGS_TO, 'Users', 'cpl_user_id']
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cpl_id'		 => 'Cpl',
			'cpl_user_id'	 => 'Cpl User',
			'cpl_user_ip'	 => 'Cpl User Ip',
			'cpl_city_id'	 => 'Cpl City',
			'cpl_category'	 => 'Cpl Category',
			'cpl_places'	 => 'Place Name',
			'cpl_url'		 => 'Place Url',
			'cpl_status'	 => 'Cpl Status',
			'cpl_active'	 => 'Cpl Active',
			'cpl_datetime'	 => 'Cpl Datetime',
		);
	}

	public function getCategories($category = '')
	{
		$arrCategory = [1 => 'Food or Restaurents', 2 => 'Popular places'];
		if ($category != '')
		{
			return $arrCategory[$category];
		}
		return $arrCategory;
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

		$criteria->compare('cpl_id', $this->cpl_id);
		$criteria->compare('cpl_user_id', $this->cpl_user_id);
		$criteria->compare('cpl_user_ip', $this->cpl_user_ip, true);
		$criteria->compare('cpl_city_id', $this->cpl_city_id);
		$criteria->compare('cpl_category', $this->cpl_category);
		$criteria->compare('cpl_places', $this->cpl_places, true);
		$criteria->compare('cpl_url', $this->cpl_url, true);
		$criteria->compare('cpl_status', 0);
		$criteria->compare('cpl_active', 1);
		$criteria->compare('cpl_datetime', $this->cpl_datetime, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CityPlaces the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getCityplaces($cityid, $catid)
	{
		$criteria	 = new CDbCriteria();
		$criteria->compare('cpl_city_id', $cityid);
		$criteria->compare('cpl_category', $catid);
		$criteria->compare('cpl_status', 1);
		$rtt		 = $this->findAll($criteria);
       
		return $rtt;
	}

}

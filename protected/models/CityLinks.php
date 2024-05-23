<?php

/**
 * This is the model class for table "city_links".
 *
 * The followings are the available columns in table 'city_links':
 * @property integer $cln_id
 * @property integer $cln_user_id
 * @property string $cln_user_ip
 * @property integer $cln_city_id
 * @property integer $cln_category
 * @property string $cln_title
 * @property string $cln_url
 * @property integer $cln_status
 * @property integer $cln_active
 * @property string $cln_datetime
 */
class CityLinks extends CActiveRecord
{

	public $cln_city_id1;
	public $cln_city_id2;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'city_links';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cln_city_id, cln_category, cln_title, cln_url, cln_datetime', 'required'),
			array('cln_user_id, cln_city_id, cln_category, cln_status, cln_active', 'numerical', 'integerOnly' => true),
			array('cln_user_ip', 'length', 'max' => 100),
			array('cln_title, cln_url', 'length', 'max' => 250),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cln_id, cln_user_id, cln_user_ip, cln_city_id, cln_category, cln_title, cln_url, cln_status, cln_active, cln_datetime', 'safe', 'on' => 'search'),
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
			'clnCities'	 => array(self::BELONGS_TO, 'Cities', 'cln_city_id'),
			'clnUser'	 => [self::BELONGS_TO, 'Users', 'cln_user_id']
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cln_id'		 => 'Cln',
			'cln_user_id'	 => 'Cln User',
			'cln_user_ip'	 => 'Cln User Ip',
			'cln_city_id'	 => 'Cln City',
			'cln_category'	 => 'Cln Category',
			'cln_title'		 => 'Link Title',
			'cln_url'		 => 'Link Url',
			'cln_status'	 => 'Cln Status',
			'cln_active'	 => 'Cln Active',
			'cln_datetime'	 => 'Cln Datetime',
		);
	}

	public function getCategories($category = '')
	{
		$arrCategory = [1 => 'Link for more city information', 2 => 'Link for popular destination from the city'];
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

		$criteria->compare('cln_id', $this->cln_id);
		$criteria->compare('cln_user_id', $this->cln_user_id);
		$criteria->compare('cln_user_ip', $this->cln_user_ip, true);
		$criteria->compare('cln_city_id', $this->cln_city_id);
		$criteria->compare('cln_category', $this->cln_category);
		$criteria->compare('cln_title', $this->cln_title, true);
		$criteria->compare('cln_url', $this->cln_url, true);
		$criteria->compare('cln_status', $this->cln_status);
		$criteria->compare('cln_active', 1);
		$criteria->compare('cln_datetime', $this->cln_datetime, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CityLinks the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getCitylinks($cityid, $catid)
	{
		$criteria	 = new CDbCriteria();
		$criteria->compare('cln_city_id', $cityid);
		$criteria->compare('cln_category', $catid);
		$criteria->compare('cln_status', 1);
		$rtt		 = $this->findAll($criteria);

		return $rtt;
	}

}

<?php

/**
 * This is the model class for table "user_places".
 *
 * The followings are the available columns in table 'user_places':
 * @property string $user_place_id
 * @property integer $user_id
 * @property string $name
 * @property string $state
 * @property string $country
 * @property string $address1
 * @property string $address2
 * @property string $address3
 * @property integer $city
 * @property string $zip
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property Cities $city0
 */
class UserPlaces extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_places';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, name, address1, city, zip', 'required'),
			array('user_id, city,zip', 'numerical', 'integerOnly' => true),
			array('zip', 'length', 'max' => 6, 'min' => 6),
			array('name, state, country, address1, address2, zip', 'length', 'max' => 200),
			array('address3', 'length', 'max' => 255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_place_id, user_id, name, state, country, address1, address2, address3, city, zip', 'safe', 'on' => 'search'),
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
			'user'	 => array(self::BELONGS_TO, 'Users', 'user_id'),
			'city0'	 => array(self::BELONGS_TO, 'Cities', 'city'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_place_id'	 => 'User Place',
			'user_id'		 => 'User',
			'name'			 => 'Place Name',
			'state'			 => 'State',
			'country'		 => 'Country',
			'address1'		 => 'Address1',
			'address2'		 => 'Address2',
			'address3'		 => 'Nearby Landmark',
			'city'			 => 'City',
			'zip'			 => 'Zip',
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

		$criteria->compare('user_place_id', $this->user_place_id, true);
		$criteria->compare('user_id', $this->user_id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('state', $this->state, true);
		$criteria->compare('country', $this->country, true);
		$criteria->compare('address1', $this->address1, true);
		$criteria->compare('address2', $this->address2, true);
		$criteria->compare('address3', $this->address3, true);
		$criteria->compare('city', $this->city);
		$criteria->compare('zip', $this->zip, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserPlaces the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getPlacesbyUser($userid)
	{
		$criteria		 = new CDbCriteria;
		$criteria->compare('user_id', $userid);
		$criteria->with	 = 'city0';
		return $this->findAll($criteria);
	}

	public function getPlacesbyUserandCity($userid, $cityid)
	{
		$criteria		 = new CDbCriteria;
		$criteria->compare('user_id', $userid);
		$criteria->compare('city', $cityid);
		$criteria->with	 = 'city0';
		$places			 = $this->findAll($criteria);
		$placesarr		 = CHtml::listData($places, 'user_place_id', 'name');
		return $placesarr;
	}

}

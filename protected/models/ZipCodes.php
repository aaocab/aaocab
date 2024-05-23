<?php

/**
 * This is the model class for table "zip_codes".
 *
 * The followings are the available columns in table 'zip_codes':
 * @property integer $zip_id
 * @property string $zip_code
 * @property integer $zip_city_id
 * @property string $zip_lat
 * @property string $zip_long
 * @property integer $zip_active
 *
 * The followings are the available model relations:
 * @property Cities $zipCity
 */
class ZipCodes extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'zip_codes';
	}

	public function scopes()
	{
		return array(
			'orderByCode' => array(
				'order' => 'zip_code ASC',
			),
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('zip_city_id, zip_active', 'numerical', 'integerOnly' => true),
			array('zip_code', 'length', 'max' => 20),
			array('zip_lat, zip_long', 'length', 'max' => 255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('zip_id, zip_code, zip_city_id, zip_lat, zip_long, zip_active', 'safe', 'on' => 'search'),
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
			'zipCity' => array(self::BELONGS_TO, 'Cities', 'zip_city_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'zip_id'		 => 'Zip',
			'zip_code'		 => 'Zip Code',
			'zip_city_id'	 => 'City',
			'zip_lat'		 => 'Lat',
			'zip_long'		 => 'Long',
			'zip_active'	 => 'Active',
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

		$criteria->compare('zip_id', $this->zip_id);
		$criteria->compare('zip_code', $this->zip_code, true);
		$criteria->compare('zip_city_id', $this->zip_city_id);
		$criteria->compare('zip_lat', $this->zip_lat, true);
		$criteria->compare('zip_long', $this->zip_long, true);
		$criteria->compare('zip_active', $this->zip_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ZipCodes the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getByCity($stateid, $city)
	{
		$criteria			 = new CDbCriteria();
		$criteria->compare('cty_name', $city);
		$criteria->compare('cty_state_id', $stateid);
		$criteria->with		 = array('zipCity');
		$criteria->together	 = true;
		return ZipCodes::model()->orderByCode()->findAll($criteria);
	}

	public function getCityByZip($zip)
	{
		$zipModel			 = self::model()->find('zip_code=:zip', array('zip' => $zip));
		$cityModel			 = Cities::model()->findByPk($zipModel->zip_city_id);
		$stateModel			 = States::model()->findByPk($cityModel->cty_state_id);
		$countryModel		 = Countries::model()->findByPk($stateModel->stt_country_id);
		$arr['city']		 = $zipModel->zip_city_id;
		$arr['cityname']	 = $cityModel->cty_name;
		$arr['state']		 = $cityModel->cty_state_id;
		$arr['statename']	 = $stateModel->stt_name;
		$arr['country']		 = $stateModel->stt_country_id;
		$arr['countryName']	 = $countryModel->country_name;
		return $arr;
	}

}

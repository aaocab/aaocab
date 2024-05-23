<?php

/**
 * This is the model class for table "top_zone_routes".
 *
 * The followings are the available columns in table 'top_zone_routes':
 * @property integer $tzr_id
 * @property string $tzr_zon_route_details
 * @property integer $tzr_from_zon_id
 * @property integer $tzr_to_zon_id
 * @property integer $tzr_bkg_count
 * @property integer $tzr_category
 * @property integer $tzr_active
 * @property string $tzr_create_date
 */
class TopZoneRoutes extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'top_zone_routes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tzr_create_date', 'required'),
			array('tzr_from_zon_id, tzr_to_zon_id, tzr_bkg_count, tzr_category, tzr_active', 'numerical', 'integerOnly' => true),
			array('tzr_zon_route_details', 'length', 'max' => 57),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('tzr_id, tzr_zon_route_details, tzr_from_zon_id, tzr_to_zon_id, tzr_bkg_count, tzr_category, tzr_active, tzr_create_date', 'safe', 'on' => 'search'),
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
			'tzr_id'				 => 'Tzr',
			'tzr_zon_route_details'	 => 'Tzr Zon Route Details',
			'tzr_from_zon_id'		 => 'Tzr From Zon',
			'tzr_to_zon_id'			 => 'Tzr To Zon',
			'tzr_bkg_count'			 => 'Tzr Bkg Count',
			'tzr_category'			 => 'Tzr Category',
			'tzr_active'			 => 'Tzr Active',
			'tzr_create_date'		 => 'Tzr Create Date',
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

		$criteria->compare('tzr_id', $this->tzr_id);
		$criteria->compare('tzr_zon_route_details', $this->tzr_zon_route_details, true);
		$criteria->compare('tzr_from_zon_id', $this->tzr_from_zon_id);
		$criteria->compare('tzr_to_zon_id', $this->tzr_to_zon_id);
		$criteria->compare('tzr_bkg_count', $this->tzr_bkg_count);
		$criteria->compare('tzr_category', $this->tzr_category);
		$criteria->compare('tzr_active', $this->tzr_active);
		$criteria->compare('tzr_create_date', $this->tzr_create_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TopZoneRoutes the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 
	 * @param type $fromCity
	 * @param type $toCity
	 * @return int/false
	 */
	public static function getCategory($fromCity, $toCity)
	{
		$result = false;
		if ($fromCity != '' && $toCity != '')
		{
			$fromZone	 = Zones::model()->getByCityId($fromCity);
			$toZone		 = Zones::model()->getByCityId($toCity);
			if ($fromZone != '' && $toZone != '')
			{
				$sql	 = "SELECT tzr_category FROM top_zone_routes WHERE tzr_from_zon_id IN ({$fromZone}) AND tzr_to_zon_id IN ({$toZone}) AND tzr_active = 1 ORDER BY tzr_category ASC";
				$result	 = DBUtil::queryScalar($sql, DBUtil::SDB());
			}
		}
		return $result;
	}

}

<?php

/**
 * This is the model class for table "geo_place".
 *
 * The followings are the available columns in table 'geo_place':
 * @property integer $gpl_id
 * @property string $gpl_place_id
 * @property integer $gpl_ltg_id
 * @property string $gpl_address
 * @property string $gpl_name
 * @property string $gpl_alias
 * @property string $gpl_types
 * @property string $gpl_created_on
 * @property string $gpl_last_updated_on
 */
class GeoPlace extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'geo_place';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('gpl_place_id', 'required'),
			array('gpl_ltg_id', 'numerical', 'integerOnly' => true),
			array('gpl_place_id, gpl_name, gpl_alias', 'length', 'max' => 255),
			array('gpl_address', 'length', 'max' => 1000),
			array('gpl_types', 'length', 'max' => 100),
			array('gpl_last_updated_on', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('gpl_id, gpl_place_id, gpl_ltg_id, gpl_address, gpl_name, gpl_alias, gpl_types, gpl_created_on, gpl_last_updated_on', 'safe', 'on' => 'search'),
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
			'gplLatLong' => array(self::BELONGS_TO, 'LatLong', 'gpl_ltg_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'gpl_id'				 => 'Gpl',
			'gpl_place_id'			 => 'Gpl Place',
			'gpl_ltg_id'			 => 'Gpl Ltg',
			'gpl_address'			 => 'Gpl Address',
			'gpl_name'				 => 'Gpl Name',
			'gpl_alias'				 => 'Gpl Alias',
			'gpl_types'				 => 'Gpl Types',
			'gpl_created_on'		 => 'Gpl Created On',
			'gpl_last_updated_on'	 => 'Gpl Last Updated On',
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

		$criteria->compare('gpl_id', $this->gpl_id);
		$criteria->compare('gpl_place_id', $this->gpl_place_id, true);
		$criteria->compare('gpl_ltg_id', $this->gpl_ltg_id);
		$criteria->compare('gpl_address', $this->gpl_address, true);
		$criteria->compare('gpl_name', $this->gpl_name, true);
		$criteria->compare('gpl_alias', $this->gpl_alias, true);
		$criteria->compare('gpl_types', $this->gpl_types, true);
		$criteria->compare('gpl_created_on', $this->gpl_created_on, true);
		$criteria->compare('gpl_last_updated_on', $this->gpl_last_updated_on, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GeoPlace the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	
	
	public function checkPlace($objPlace, $ltg_id = null)
	{
		if ($objPlace->place_id == '')
		{
			return false;
		}

		$params	 = ["place" => $objPlace->place_id];
		$model	 = GeoPlace::model()->find("gpl_place_id=:place AND gpl_active=1", $params);
		if ($model)
		{
			goto result;
		}
		$model = GeoPlace::model()->add($objPlace, $ltg_id);
		result:
		return $model;
	}

	/**
	 * 
	 * @param obj $obj
	 * @param int $ltg_id
	 * @return \GeoPlace model
	 * @throws Exception
	 */
	public static function add($obj)
	{

		$decimalPoint		 = 4;
		$model				 = new GeoPlace();
		$model->gpl_place_id = $obj->place_id;
		$geoObjects			 = $obj;
		if (!$obj->geometry->location->lat || !$obj->geometry->location->lat)
		{
			$googleObjects	 = GoogleMapAPI::getObjectByPlaceId($obj->place_id);
			$geoObjects		 = $googleObjects->results[0];
		}

		$placeObj		 = Stub\common\Place::initGoogePlace($geoObjects);
		$ltgModel		 = LatLong::findNearest($placeObj, '0.00005');
		if(!$ltgModel)
		{
			$models = LatLong::addByGoogleObjects($googleObjects, $placeObj);
			if ($models && count($models) > 0)
			{
				$ltgModel = $models[0];
			}
		}
		if ($ltgModel)
		{
			$model->gpl_ltg_id = $ltgModel->ltg_id;
		}

		$model->gpl_lat				 = round($placeObj->coordinates->latitude, $decimalPoint);
		$model->gpl_lng				 = round($placeObj->coordinates->longitude, $decimalPoint);
		$model->gpl_name			 = $placeObj->name;
		$model->gpl_alias			 = $placeObj->alias;
		$model->gpl_address			 = $placeObj->address;
		$model->gpl_types			 = json_encode($placeObj->types);
		$model->gpl_bounds			 = json_encode($placeObj->bounds);
		$model->gpl_last_updated_on	 = new CDbExpression('NOW()');
		if (!$model->save())
		{
			throw new Exception("Error adding data." . json_encode($model->getErrors()));
		}
		return $model;
	}

	/**
	 * 
	 * @param string $place
	 * @return static
	 */
	public static function getByPlace($place, $createIfNotExist = true)
	{
		if ($place->place_id == '')
		{
			return false;
		}
		$params	 = ["place" => $place->place_id];
		$model	 = GeoPlace::model()->find("gpl_place_id=:place AND gpl_active=1", $params);
		if (!$model && $createIfNotExist)
		{
			$model = GeoPlace::add($place);
		}
		return $model;
	}

}

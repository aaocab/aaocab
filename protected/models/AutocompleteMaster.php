<?php

/**
 * This is the model class for table "autocomplete_master".
 *
 * The followings are the available columns in table 'autocomplete_master':
 * @property integer $atc_id
 * @property string $atc_keyword
 * @property integer $atc_city_id
 * @property integer $atc_source
 * @property string $atc_predictions
 * @property string $atc_create
 * @property string $atc_update
 */
class AutocompleteMaster extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'autocomplete_master';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('atc_keyword, atc_city_id', 'required'),
			array('atc_city_id, atc_source', 'numerical', 'integerOnly' => true),
			array('atc_keyword', 'length', 'max' => 255),
			array('atc_predictions, atc_update', 'safe'),
			['atc_id', 'validateZeroResult', 'on' => 'check_ZERO_RESULTS'],
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('atc_id, atc_keyword, atc_city_id, atc_source, atc_predictions, atc_create, atc_update', 'safe', 'on' => 'search'),
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
			'atc_id'			 => 'Atc',
			'atc_keyword'		 => 'Atc Keyword',
			'atc_city_id'		 => 'Atc City',
			'atc_source'		 => '1:google;2:MMI',
			'atc_predictions'	 => 'Act Predictions',
			'atc_create'		 => 'Act Create',
			'atc_update'		 => 'Act Update',
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

		$criteria->compare('atc_id', $this->atc_id);
		$criteria->compare('atc_keyword', $this->atc_keyword, true);
		$criteria->compare('atc_city_id', $this->atc_city_id);
		$criteria->compare('atc_source', $this->atc_source);
		$criteria->compare('atc_predictions', $this->atc_predictions, true);
		$criteria->compare('atc_create', $this->atc_create, true);
		$criteria->compare('atc_update', $this->atc_update, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AutocompleteMaster the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function validateZeroResult()
	{
		$var	 = json_decode($this->atc_predictions);
		$status	 = $var->status;
		if ($status != "ZERO_RESULTS")
		{
			return true;
		}
		$params = [
			'predictions'	 => $this->atc_predictions,
			'keyword'		 => $this->atc_keyword,
			'city_id'		 => $this->atc_city_id];

		$sql	 = "SELECT COUNT(*) as cnt FROM autocomplete_master
                WHERE atc_keyword = :keyword 
					AND atc_city_id = :city_id
					AND atc_predictions =:predictions";
		$count	 = DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
		if ($count > 0)
		{
			$this->addError('atc_predictions', "Already Exist with same predictions");
			return false;
		}
		return true;
	}

	/**
	 * 
	 * @param type $data
	 * @return arr
	 */
	public static function add($keyword, $cityId, $prediction, $source = 1, $atc_id = 0)
	{
		$model = new AutocompleteMaster();

		try
		{
			//  getFromAutocomplete
			if ($atc_id == 0)
			{
				goto skipExisting;
			}

			$autocompleteModel = Autocomplete::model()->findByPk($atc_id);
			if (!$autocompleteModel)
			{
				goto skipExisting;
			}



			$model = AutocompleteMaster::model()->findByPk($autocompleteModel->atc_id);

			if (!$model)
			{
				$model = new AutocompleteMaster();
			}

			$model->attributes	 = $autocompleteModel->attributes;
			$model->atc_id		 = $autocompleteModel->atc_id;
			$model->atc_create	 = $autocompleteModel->atc_create;

			skipExisting:
			$model->atc_keyword		 = $keyword;
			$model->atc_city_id		 = $cityId;
			$model->atc_predictions	 = json_encode(json_decode($prediction));
			$model->atc_source		 = $source;

			$model->scenario = "check_ZERO_RESULTS";
			$model->save();
		}
		catch (Exception $e)
		{
			$model->addError("atc_id", $e->getMessage());
			Logger::error($e);
		}

		return $model;
	}

	/**
	 * 
	 * @param type $city
	 * @param string $keyword
	 * @return type
	 * //non airport rectangle :28.4125, 76.8380|28.881338, 77.3484578
	 * //airport circle:radius@lat,lng
	 */
	public static function getAPIResponse($city, $keyword, $sessiontoken, $precision = 0, $atc_id = 0)
	{
		if (!trim($keyword))
		{
			throw new CHttpException(406, "keyword not found", 406);
		}


		$cityModel = Cities::model()->findByPk($city);

		if ($cityModel->cty_is_airport == 1)
		{
			$coordinates	 = "{$cityModel->cty_lat},{$cityModel->cty_long}";
			$radius			 = Cities::getCtyRadiusByCtyId($city);
			$circleRadius	 = $radius * 1000;
			$response		 = GoogleMapAPI::getInstance()->getAutocompleteByRadius($keyword, $coordinates, $circleRadius, $sessiontoken);
		}
		else
		{
			$bounds		 = Cities::getCityBoundByLatLong($city, 1);
			$response	 = GoogleMapAPI::getInstance()->getAutocompleteByBounds($keyword, $bounds, $sessiontoken, $precision);
		}

		$model = self::add($keyword, $city, $response, 1, $atc_id);
		return $model;
	}

	public static function getPrediction($keyword, $city)
	{
		$recordSet;
		$params		 = ['keyword' => $keyword, 'city' => $city];
		$sql		 = "SELECT atc_id ,atc_predictions, TIMESTAMPDIFF(DAY, atc_create, NOW()) as dayCount  FROM autocomplete_master WHERE atc_keyword= :keyword AND atc_city_id= :city";
		$recordSet	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);

		if (!empty($recordSet))
		{
			return $recordSet;
		}

		return false;
	}

	public static function getIds()
	{
		$sql = "SELECT GROUP_CONCAT(atc_id) as ids FROM autocomplete_master";
		return DBUtil::queryScalar($sql, DBUtil::SDB());
	}

	public static function remove($ids)
	{
		$sql = "DELETE FROM autocomplete_master WHERE atc_id IN ($ids)";
		return DBUtil::execute($sql);
	}
}

<?php

/**
 * This is the model class for table "autocomplete".
 *
 * The followings are the available columns in table 'autocomplete':
 * @property integer $atc_id
 * @property string $atc_keyword
 * @property integer $atc_city_id
 * @property integer $atc_source
 * @property string $atc_predictions
 * @property string $atc_create
 * @property string $atc_update
 */
class Autocomplete extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'autocomplete';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('atc_predictions, atc_create', 'required'),
			array('atc_city_id, atc_source', 'numerical', 'integerOnly' => true),
			array('atc_keyword', 'length', 'max' => 255),
			//array('atc_keyword', 'length', 'max'=>11),
			array('atc_update', 'safe'),
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
			'atc_source'		 => 'Act Source',
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
	 * @return Autocomplete the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function add($param)
	{
		$returnArr			 = [];
		$model				 = new Autocomplete();
		$model->attributes	 = $param;
		if (!$model->validate())
		{
			$success = false;
		}
		$success	 = ($model->save()) ? true : false;
		$returnArr	 = ['success' => $success, 'error' => json_encode($model->getErrors())];
		return $returnArr;
	}

	/**
	 * 
	 * @param string $keyword
	 * @param int $city
	 * @return boolean|array
	 */
	public static function getPrediction($keyword, $city)
	{
		$recordSet;

		$params		 = ['keyword' => $keyword, 'city' => $city];
		$sql		 = "SELECT atc_id ,atc_predictions, TIMESTAMPDIFF(DAY, atc_create, NOW()) as dayCount FROM autocomplete WHERE atc_keyword= :keyword AND atc_city_id= :city";
		$recordSet	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);

		if (!empty($recordSet))
		{
			return $recordSet;
		}

		return false;
	}

	public static function lookupKeyword($keyword, $city, $sessiontoken, $precision = 0)
	{

		$predictionValidUpto = Config::getValueByName("address.prediction.validUpto"); //need to set in config
		$data				 = '';
		$atc_id				 = 0;
		$dataSet			 = self::getPrediction($keyword, $city);
		$atc_id				 = $dataSet['atc_id'];
		if (!$dataSet || $dataSet['dayCount'] > $predictionValidUpto)
		{
			$dataSet = AutocompleteMaster::getPrediction($keyword, $city);
		}

		if ($dataSet && $predictionValidUpto >= $dataSet['dayCount'] && $dataSet['atc_predictions'])
		{
			$data	 = $dataSet['atc_predictions'];
			$atc_id	 = $dataSet['atc_id'];
			goto result;
		}

		$model	 = AutocompleteMaster::getAPIResponse($city, $keyword, $sessiontoken, $precision, $atc_id);
		$data	 = $model->atc_predictions;
		result:
		return $data;
	}

	public static function importFromMaster()
	{
		try
		{
			$ids = AutocompleteMaster::getIds();
			if (!$ids)
			{
				return;
			}

			$sql	 = "INSERT INTO autocomplete (`atc_keyword`, `atc_city_id`, `atc_source`, `atc_predictions`) 
						SELECT `atc_keyword`, `atc_city_id`, `atc_source`, `atc_predictions` FROM autocomplete_master 
						WHERE atc_id IN ($ids) ON DUPLICATE KEY UPDATE autocomplete.atc_predictions=autocomplete_master.atc_predictions";
			$result	 = DBUtil::execute($sql);

			if ($result)
			{
				AutocompleteMaster::remove($ids);
			}
		}
		catch (Exception $exc)
		{
			Logger::exception($exc);
		}
	}

	/**
	 * 
	 * @param string $keyword
	 * @param string $cityId
	 * @return boolean|model
	 */
//    public static function getFromAutocomplete($keyword, $cityId)
//    {
//        if ($keyword == "" || $cityId = "")
//        {
//            return false;
//        }
//        $criteria = new CDbCriteria();
//        $criteria->compare('atc_city_id', $cityId);
//        $criteria->compare('atc_keyword', $keyword);
//        $model    = Autocomplete::model()->find($criteria);
//        if ($model)
//        {
//            return $model;
//        }
//        return false;
//    }
}

// SELECT atc_predictions FROM autocomplete WHERE atc_keyword= 'tiru' AND atc_city_id= 30152 AND
        //SELECT * FROM `autocomplete` WHERE atc_city_id= 30152 AND (atc_create NOT BETWEEN DATE_SUB(CURDATE(),INTERVAL 365 DAY) AND CURDATE()) and atc_create<=NOW();
	//SELECT * FROM `autocomplete` WHERE atc_city_id= 30152 AND (atc_create >= DATE_SUB(CURDATE(),INTERVAL 365 DAY));
     //SELECT * FROM `autocomplete` WHERE atc_city_id= 30152 AND (atc_create NOT BETWEEN DATE_SUB(CURDATE(),INTERVAL 1 YEAR) AND CURDATE()) and atc_create<=NOW();
  //$dte1,$dbdate,$days
  //$dbdate  DATE_SUB(NOW(),INTERVAL 5 day)
//ON DUPLICATE UPDATE

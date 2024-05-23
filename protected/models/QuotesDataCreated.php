<?php

/**
 * This is the model class for table "quotes_data_created".
 *
 * The followings are the available columns in table 'quotes_data_created':
 * @property integer $qdc_id
 * @property integer $qdc_source
 * @property integer $qdc_from_city
 * @property integer $qdc_to_city
 * @property integer $qdc_from_zone
 * @property integer $qdc_to_zone
 * @property integer $qdc_vehicle_type_id
 * @property string $qdc_create_date
 * @property integer $qdc_create_week
 * @property integer $qdc_count
 * @property integer $qdc_status
 */
class QuotesDataCreated extends CActiveRecord
{

	public $source, $routes, $quoteDate, $pickupDate, $qdp_create_week, $cabType;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'quotes_data_created';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('qdc_source, qdc_from_city, qdc_to_city, qdc_vehicle_type_id, qdc_create_week, qdc_count, qdc_status', 'numerical', 'integerOnly' => true),
			array('qdc_create_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('qdc_id, qdc_source, qdc_from_city, qdc_to_city, qdc_vehicle_type_id, qdc_create_date, qdc_create_week, qdc_count, qdc_status, qdc_from_zone, qdc_to_zone', 'safe', 'on' => 'search'),
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
			'qdc_id'				 => 'Qdc',
			'qdc_source'			 => '1 => User, 2 => Admin, 3 => App, 4=>Agent, 5=>Partner Spot',
			'qdc_from_city'			 => 'Qdc From City',
			'qdc_to_city'			 => 'Qdc To City',
			'qdc_from_zone'			 => 'Qdc From Zone',
			'qdc_to_zone'			 => 'Qdc To Zone',
			'qdc_vehicle_type_id'	 => 'Qdc Vehicle Type',
			'qdc_create_date'		 => 'Qdc Create Date',
			'qdc_create_week'		 => 'Qdc Create Week',
			'qdc_count'				 => 'Qdc Count',
			'qdc_status'			 => 'Qdc Status',
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

		$criteria->compare('qdc_id', $this->qdc_id);
		$criteria->compare('qdc_source', $this->qdc_source);
		$criteria->compare('qdc_from_city', $this->qdc_from_city);
		$criteria->compare('qdc_to_city', $this->qdc_to_city);
		$criteria->compare('qdc_from_zone', $this->qdc_from_zone);
		$criteria->compare('qdc_to_zone', $this->qdc_to_zone);
		$criteria->compare('qdc_vehicle_type_id', $this->qdc_vehicle_type_id);
		$criteria->compare('qdc_create_date', $this->qdc_create_date, true);
		$criteria->compare('qdc_create_week', $this->qdc_create_week);
		$criteria->compare('qdc_count', $this->qdc_count);
		$criteria->compare('qdc_status', $this->qdc_status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return QuotesDataCreated the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function setData($data, $cabType)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$fromCity	 = $data->routes[0]->brt_from_city_id;
		$toCity		 = $data->routes[count($data->routes) - 1]->brt_to_city_id;
		$quoteDate	 = ($data->quoteDate == null) ? date('Y-m-d') : date('Y-m-d', strtotime($data->quoteDate));
		$source		 = $data->sourceQuotation;

		$model = $this::model()->find('qdc_from_city=:qdc_from_city AND qdc_to_city=:qdc_to_city AND qdc_create_date=:qdc_create_date AND qdc_source=:qdc_source AND qdc_vehicle_type_id=:qdc_vehicle_type_id ', ['qdc_from_city' => $fromCity, 'qdc_to_city' => $toCity, 'qdc_create_date' => $quoteDate, 'qdc_source' => $source, 'qdc_vehicle_type_id' => $cabType]);

		$count = ($model->qdc_count == null) ? 0 : $model->qdc_count;
		if (!$model)
		{
			$model						 = new QuotesDataCreated();
			$model->qdc_source			 = $source;
			$model->qdc_from_city		 = $fromCity;
			$model->qdc_to_city			 = $toCity;
			$model->qdc_create_date		 = ($data->quoteDate == null) ? date('Y-m-d') : $quoteDate;
			$model->qdc_count			 = $count;
			$model->qdc_create_week		 = date("W", strtotime($model->qdc_create_date));
			$model->qdc_status			 = 1;
			$model->qdc_vehicle_type_id	 = $cabType;
		}
		else
		{
			$model						 = QuotesDataCreated::model()->findByPk($model->qdc_id);
			$model->qdc_count			 = $count + 1;
			$model->qdc_vehicle_type_id	 = $cabType;
		}
		$model->save();
		QuotesDataPickup::model()->setData($data, $cabType);
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
	}

	/**
	 * Function for archiving Quotes Created 
	 */
	public function archiveQuotesData($archiveDB, $upperLimit = 100000, $lowerlimit = 1000)
	{
		#Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$i			 = 0;
		$chk		 = true;
		$totRecords	 = $upperLimit;
		$limit		 = $lowerlimit;
		while ($chk)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$sql	 = "SELECT GROUP_CONCAT(qdc_id) AS qdc_id FROM (SELECT qdc_id FROM quotes_data_created WHERE 1 AND qdc_create_date < CONCAT(DATE_SUB(CURDATE(), INTERVAL 1 Day), ' 00:00:00') ORDER BY qdc_id LIMIT 0, $limit) as temp";
				#Logger::info("Select Query => " . $sql);
				$resQ	 = DBUtil::queryScalar($sql);
				if (!is_null($resQ) && $resQ != '')
				{
					$sql	 = "INSERT INTO " . $archiveDB . ".quotes_data_created (SELECT * FROM quotes_data_created WHERE qdc_id IN ($resQ))";
					#Logger::info("Insert Query => " . $sql);
					$rows	 = DBUtil::execute($sql);
					#$a		 = $row;
					if ($rows > 0)
					{
						$sql = "DELETE FROM `quotes_data_created` WHERE qdc_id IN ($resQ)";
						DBUtil::execute($sql);
						#Logger::info("DELETE Query => " . $sql);
					}
				}
				DBUtil::commitTransaction($transaction);

				$i += $limit;
				#Logger::info("Commit => " . $i);
				if (($resQ <= 0) || $totRecords <= $i)
				{
					break;
				}
			}
			catch (Exception $e)
			{
				echo $e->getMessage() . "\n\n";
				DBUtil::rollbackTransaction($transaction);
			}
		}
		#Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
	}

}

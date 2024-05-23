<?php

/**
 * This is the model class for table "quotes_data_pickup".
 *
 * The followings are the available columns in table 'quotes_data_pickup':
 * @property integer $qdp_id
 * @property integer $qdp_source
 * @property integer $qdp_from_city
 * @property integer $qdp_to_city
 * @property integer $qdp_from_zone
 * @property integer $qdp_to_zone
 * @property integer $qdp_vehicle_type_id
 * @property string $qdp_pickup_date
 * @property integer $qdp_pickup_week
 * @property integer $qdp_count
 * @property integer $qdp_status
 */
class QuotesDataPickup extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'quotes_data_pickup';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('qdp_source, qdp_from_city, qdp_to_city, qdp_vehicle_type_id, qdp_pickup_week, qdp_count, qdp_status', 'numerical', 'integerOnly' => true),
			array('qdp_pickup_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('qdp_id, qdp_source, qdp_from_city, qdp_to_city, qdp_vehicle_type_id, qdp_pickup_date, qdp_pickup_week, qdp_count, qdp_status, qdp_from_zone, qdp_to_zone', 'safe', 'on' => 'search'),
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
			'qdp_id'				 => 'Qdp',
			'qdp_source'			 => '1 => User, 2 => Admin, 3 => App, 4=>Agent, 5=>Partner Spot',
			'qdp_from_city'			 => 'Qdp From City',
			'qdp_to_city'			 => 'Qdp To City',
			'qdp_from_zone'			 => 'Qdp From Zone',
			'qdp_to_zone'			 => 'Qdp To Zone',
			'qdp_vehicle_type_id'	 => 'Qdp Vehicle Type',
			'qdp_pickup_date'		 => 'Qdp Pickup Date',
			'qdp_pickup_week'		 => 'Qdp Pickup Week',
			'qdp_count'				 => 'Qdp Count',
			'qdp_status'			 => 'Qdp Status',
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
		$criteria->compare('qdp_id', $this->qdp_id);
		$criteria->compare('qdp_source', $this->qdp_source);
		$criteria->compare('qdp_from_city', $this->qdp_from_city);
		$criteria->compare('qdp_to_city', $this->qdp_to_city);
		$criteria->compare('qdp_from_zone', $this->qdp_from_zone);
		$criteria->compare('qdp_to_zone', $this->qdp_to_zone);
		$criteria->compare('qdp_vehicle_type_id', $this->qdp_vehicle_type_id);
		$criteria->compare('qdp_pickup_date', $this->qdp_pickup_date, true);
		$criteria->compare('qdp_pickup_week', $this->qdp_pickup_week);
		$criteria->compare('qdp_count', $this->qdp_count);
		$criteria->compare('qdp_status', $this->qdp_status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return QuotesDataPickup the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function setData($data, $cabType)
	{
		Logger::beginProfile("QuotesDataPickup::setData for CAB {$cabType}");
		$fromCity	 = $data->routes[0]->brt_from_city_id;
		$toCity		 = $data->routes[count($data->routes) - 1]->brt_to_city_id;
		$pickupDate	 = date('Y-m-d', strtotime($data->pickupDate));
		$source		 = $data->sourceQuotation;
		$model		 = $this::model()->find('qdp_from_city=:qdp_from_city AND qdp_to_city=:qdp_to_city AND qdp_pickup_date=:qdp_pickup_date AND qdp_source=:qdp_source AND qdp_vehicle_type_id=:qdp_vehicle_type_id ', ['qdp_from_city' => $fromCity, 'qdp_to_city' => $toCity, 'qdp_pickup_date' => $pickupDate, 'qdp_source' => $source, 'qdp_vehicle_type_id' => $cabType]);
		$count		 = ($model->qdp_count == null) ? 0 : $model->qdp_count + 1;
		if (!$model)
		{
			$model						 = new QuotesDataPickup();
			$model->qdp_source			 = $source;
			$model->qdp_from_city		 = $fromCity;
			$model->qdp_to_city			 = $toCity;
			$model->qdp_pickup_date		 = date('Y-m-d', strtotime($data->pickupDate));
			$model->qdp_count			 = $count;
			$model->qdp_pickup_week		 = date("W", strtotime($data->pickupDate));
			$model->qdp_status			 = 1;
			$model->qdp_vehicle_type_id	 = $cabType;
		}
		else
		{
			$model						 = QuotesDataPickup::model()->findByPk($model->qdp_id);
			$model->qdp_count			 = $count + 1;
			$model->qdp_vehicle_type_id	 = $cabType;
		}
		$model->save();
		Logger::endProfile("QuotesDataPickup::setData for CAB {$cabType}");
	}

	/**
	 * Function for archiving Quotes Pickup 
	 */
	public function archiveQuotesPickup($archiveDB = "", $upperLimit = 100000, $lowerLimit = 1000)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$i			 = 0;
		$chk		 = true;
		$totRecords	 = $upperLimit;
		$limit		 = $lowerLimit;
		while ($chk)
		{
			$transaction = "";
			try
			{
				$sql	 = "SELECT GROUP_CONCAT(qdp_id) AS qdp_id FROM (SELECT qdp_id FROM quotes_data_pickup WHERE 1 AND qdp_pickup_date < CONCAT(DATE_SUB(CURDATE(), INTERVAL 1 Day), ' 00:00:00') ORDER BY qdp_id LIMIT 0, $limit) as temp";
				#Logger::info("Select Query => " . $sql);
				$resQ	 = DBUtil::queryScalar($sql);
				#Logger::info("Qdp_id => " . $resQ);
				if (!is_null($resQ) && $resQ != '')
				{
					$transaction = DBUtil::beginTransaction();
					DBUtil::getINStatement($resQ, $bindString, $params);
					$sql		 = "INSERT INTO " . $archiveDB . ".quotes_data_pickup (SELECT * FROM quotes_data_pickup WHERE qdp_id IN ($bindString))";
					#Logger::info("Insert Query => " . $sql);
					$rows		 = DBUtil::execute($sql, $params);
					if ($rows > 0)
					{
						$sql = "DELETE FROM `quotes_data_pickup` WHERE qdp_id IN ($bindString)";
						DBUtil::execute($sql, $params);
						#Logger::info("DELETE Query => " . $sql);
						DBUtil::commitTransaction($transaction);
					}
					else
					{
						DBUtil::rollbackTransaction($transaction);
					}
				}
				$i += $limit;
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
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
	}

}

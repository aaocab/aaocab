<?php

/**
 * This is the model class for table "quotes_zone_situation".
 *
 * The followings are the available columns in table 'quotes_zone_situation':
 * @property integer $qzs_id
 * @property integer $qzs_demand_identifier
 * @property integer $qzs_quote_given_count
 * @property integer $qzs_confirmed_count
 * @property integer $qzs_cancelled_count
 * @property string $qzs_pickup_date
 * @property integer $qzs_capacity
 * @property string $qzs_create_date
 * @property string $qzs_updated_date
 * @property integer $qzs_status
 */
class QuotesZoneSituation extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'quotes_zone_situation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('qzs_demand_identifier, qzs_quote_given_count, qzs_confirmed_count, qzs_cancelled_count, qzs_capacity, qzs_status', 'numerical', 'integerOnly' => true),
			array('qzs_create_date, qzs_updated_date,qzs_pickup_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('qzs_id, qzs_demand_identifier, qzs_quote_given_count, qzs_confirmed_count, qzs_cancelled_count, qzs_pickup_date, qzs_capacity, qzs_create_date, qzs_updated_date, qzs_status', 'safe', 'on' => 'search'),
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
			'qzs_id'				 => 'Qds',
			'qzs_demand_identifier'	 => 'RegionId-FromZoneId-ToZoneId-ScvId-BoookingTpe',
			'qzs_quote_given_count'	 => 'Total quote create for any given rowidentifer for that pickup date',
			'qzs_confirmed_count'	 => 'Total quote confirm for any given rowidentifer for that pickup date',
			'qzs_cancelled_count'	 => 'Total quote cancelled for any given rowidentifer for that pickup date',
			'qzs_pickup_date'		 => 'quotes pickupdate',
			'qzs_capacity'			 => 'gozo capacity',
			'qzs_create_date'		 => 'create at',
			'qzs_updated_date'		 => 'modified at',
			'qzs_status'			 => 'srarus',
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

		$criteria->compare('qzs_id', $this->qzs_id);
		$criteria->compare('qzs_demand_identifier', $this->qzs_demand_identifier);
		$criteria->compare('qzs_quote_given_count', $this->qzs_quote_given_count);
		$criteria->compare('qzs_confirmed_count', $this->qzs_confirmed_count);
		$criteria->compare('qzs_cancelled_count', $this->qzs_cancelled_count);
		$criteria->compare('qzs_pickup_date', $this->qzs_pickup_date);
		$criteria->compare('qzs_capacity', $this->qzs_capacity);
		$criteria->compare('qzs_create_date', $this->qzs_create_date, true);
		$criteria->compare('qzs_updated_date', $this->qzs_updated_date, true);
		$criteria->compare('qzs_status', $this->qzs_status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return QuotesZoneSituation the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function setQuoteData($data, $cabType)
	{
		try
		{
			$fromCity			 = $data->routes[0]->brt_from_city_id;
			$toCity				 = $data->routes[count($data->routes) - 1]->brt_to_city_id;
			$pickupDate			 = ($data->pickupDate == null) ? date('Y-m-d') : date('Y-m-d', strtotime($data->pickupDate));
			$tripType			 = $data->tripType;
			$demandIdentifier	 = DynamicZoneSurge::getDemandIdentifier($fromCity, $tripType);
			$model				 = self::model()->find('qzs_demand_identifier=:demandIdentifier AND qzs_pickup_date=:pickupDate AND qzs_status=1', ['demandIdentifier' => $demandIdentifier, 'pickupDate' => $pickupDate]);
			$count				 = ($model->qzs_quote_given_count == null) ? 0 : $model->qzs_quote_given_count;
			if (!$model)
			{
				$model							 = new QuotesZoneSituation();
				$model->qzs_demand_identifier	 = $demandIdentifier;
				$model->qzs_quote_given_count	 = 1;
				$model->qzs_confirmed_count		 = 0;
				$model->qzs_cancelled_count		 = 0;
				$model->qzs_pickup_date			 = $pickupDate;
				$model->qzs_capacity			 = 0;
				$model->qzs_create_date			 = DBUtil::getCurrentTime();
				$model->qzs_updated_date		 = DBUtil::getCurrentTime();
				$model->qzs_status				 = 1;
			}
			else
			{
				$model							 = QuotesZoneSituation::model()->findByPk($model->qzs_id);
				$model->qzs_quote_given_count	 = $count + 1;
				$model->qzs_updated_date		 = DBUtil::getCurrentTime();
			}
			if (!$model->save())
			{
				throw Exception(json_encode($model->errors), ReturnSet::ERROR_VALIDATION);
			}
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
		}
	}

	public static function setConFirmData($bkgId)
	{
		try
		{
			$model				 = Booking::model()->findByPk($bkgId);
			$fromCity			 = $model->bkg_from_city_id;
			$toCity				 = $model->bkg_to_city_id;
			$pickupDate			 = ($model->bkg_pickup_date == null) ? date('Y-m-d') : date('Y-m-d', strtotime($model->bkg_pickup_date));
			$tripType			 = $model->bkg_booking_type;
			$cabType			 = $model->bkg_vehicle_type_id;
			$demandIdentifier	 = DynamicZoneSurge::getDemandIdentifier($fromCity, $tripType);
			$models				 = self::model()->find('qzs_demand_identifier=:demandIdentifier AND qzs_pickup_date=:pickupDate AND qzs_status=1', ['demandIdentifier' => $demandIdentifier, 'pickupDate' => $pickupDate]);
			$count				 = ($models->qzs_confirmed_count == null) ? 0 : $models->qzs_confirmed_count;
			if ($models)
			{
				$models->qzs_confirmed_count = $count + 1;
				$models->qzs_updated_date	 = DBUtil::getCurrentTime();
				if (!$models->save())
				{
					throw Exception(json_encode($models->errors), ReturnSet::ERROR_VALIDATION);
				}
			}
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
		}
	}

	public static function setCancelData($bkgId)
	{
		try
		{
			$model				 = Booking::model()->findByPk($bkgId);
			$fromCity			 = $model->bkg_from_city_id;
			$toCity				 = $model->bkg_to_city_id;
			$pickupDate			 = ($model->bkg_pickup_date == null) ? date('Y-m-d') : date('Y-m-d', strtotime($model->bkg_pickup_date));
			$tripType			 = $model->bkg_booking_type;
			$cabType			 = $model->bkg_vehicle_type_id;
			$demandIdentifier	 = DynamicZoneSurge::getDemandIdentifier($fromCity,$tripType);
			$models				 = self::model()->find('qzs_demand_identifier=:demandIdentifier AND qzs_pickup_date=:pickupDate AND qzs_status=1', ['demandIdentifier' => $demandIdentifier, 'pickupDate' => $pickupDate]);
			$count				 = ($models->qzs_cancelled_count == null) ? 0 : $models->qzs_cancelled_count;
			if ($models)
			{
				$models->qzs_cancelled_count = $count + 1;
				$models->qzs_updated_date	 = DBUtil::getCurrentTime();
				if (!$models->save())
				{
					throw Exception(json_encode($models->errors), ReturnSet::ERROR_VALIDATION);
				}
			}
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
		}
	}

	public static function updateDemandIdentifierMedainCapacity($demandIdentifier, $capacity)
	{
		Logger::warning('Model QuotesZoneSituation updateDemandIdentifierMedainCapacity', true);
		return false;
		
		$sql = "UPDATE quotes_zone_situation SET qzs_capacity=qzs_capacity+:capacity WHERE 1 AND qzs_demand_identifier=:demandIdentifier AND qzs_status=1";
		DBUtil::execute($sql, ['demandIdentifier' => $demandIdentifier, 'capacity' => $capacity]);
	}

	public static function getDevliveryCount($demandIdentifier, $pickupDate)
	{
		$sql = 'SELECT					
				(IFNULL(qzs_confirmed_count,0) - IFNULL(qzs_cancelled_count,0)) AS pickupCount,
				qzs_capacity AS capacity
				FROM quotes_zone_situation
				WHERE 1
				AND DATE(qzs_pickup_date)=DATE(:pickupDate)
				AND qzs_demand_identifier=:demandIdentifier 
				AND qzs_status=1
				ORDER BY qzs_id DESC LIMIT 0,1 ';
		return DBUtil::queryRow($sql, DBUtil::SDB(), ['demandIdentifier' => $demandIdentifier, 'pickupDate' => $pickupDate]);
	}

}

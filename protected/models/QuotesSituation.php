<?php

/**
 * This is the model class for table "quotes_situation".
 *
 * The followings are the available columns in table 'quotes_situation':
 * @property integer $qds_id
 * @property integer $qds_row_identifier
 * @property integer $qds_quote_given_count
 * @property integer $qds_confirmed_count
 * @property integer $qds_cancelled_count
 * @property string $qds_pickup_date
 * @property integer $qds_capacity
 * @property string $qds_create_date
 * @property string $qds_updated_date
 * @property integer $qds_status
 */
class QuotesSituation extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'quotes_situation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('qds_row_identifier, qds_quote_given_count, qds_confirmed_count, qds_cancelled_count, qds_capacity, qds_status', 'numerical', 'integerOnly' => true),
			array('qds_create_date, qds_updated_date,qds_pickup_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('qds_id, qds_row_identifier, qds_quote_given_count, qds_confirmed_count, qds_cancelled_count, qds_pickup_date, qds_capacity, qds_create_date, qds_updated_date, qds_status', 'safe', 'on' => 'search'),
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
			'qds_id'				 => 'Qds',
			'qds_row_identifier'	 => 'RegionId-FromZoneId-ToZoneId-ScvId-BoookingTpe',
			'qds_quote_given_count'	 => 'Total quote create for any given rowidentifer for that pickup date',
			'qds_confirmed_count'	 => 'Total quote confirm for any given rowidentifer for that pickup date',
			'qds_cancelled_count'	 => 'Total quote cancelled for any given rowidentifer for that pickup date',
			'qds_pickup_date'		 => 'quotes pickupdate',
			'qds_capacity'			 => 'gozo capacity',
			'qds_create_date'		 => 'create at',
			'qds_updated_date'		 => 'modified at',
			'qds_status'			 => 'srarus',
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

		$criteria->compare('qds_id', $this->qds_id);
		$criteria->compare('qds_row_identifier', $this->qds_row_identifier);
		$criteria->compare('qds_quote_given_count', $this->qds_quote_given_count);
		$criteria->compare('qds_confirmed_count', $this->qds_confirmed_count);
		$criteria->compare('qds_cancelled_count', $this->qds_cancelled_count);
		$criteria->compare('qds_pickup_date', $this->qds_pickup_date);
		$criteria->compare('qds_capacity', $this->qds_capacity);
		$criteria->compare('qds_create_date', $this->qds_create_date, true);
		$criteria->compare('qds_updated_date', $this->qds_updated_date, true);
		$criteria->compare('qds_status', $this->qds_status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return QuotesSituation the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function setQuoteData($data, $cabType)
	{
		try
		{
			$fromCity		 = $data->routes[0]->brt_from_city_id;
			$toCity			 = $data->routes[count($data->routes) - 1]->brt_to_city_id;
			$pickupDate		 = ($data->pickupDate == null) ? date('Y-m-d') : date('Y-m-d', strtotime($data->pickupDate));
			$tripType		 = $data->tripType;
			$rowIdentifier	 = DynamicZoneSurge::getRowIdentifier($fromCity, $toCity, $cabType, $tripType);
			$model			 = self::model()->find('qds_row_identifier=:rowIdentifier AND qds_pickup_date=:pickupDate AND qds_status=1', ['rowIdentifier' => $rowIdentifier, 'pickupDate' => $pickupDate]);
			$count			 = ($model->qds_quote_given_count == null) ? 0 : $model->qds_quote_given_count;
			if (!$model)
			{
				$model							 = new QuotesSituation();
				$model->qds_row_identifier		 = $rowIdentifier;
				$model->qds_quote_given_count	 = 1;
				$model->qds_confirmed_count		 = 0;
				$model->qds_cancelled_count		 = 0;
				$model->qds_pickup_date			 = $pickupDate;
				$model->qds_capacity			 = 0;
				$model->qds_create_date			 = DBUtil::getCurrentTime();
				$model->qds_updated_date		 = DBUtil::getCurrentTime();
				$model->qds_status				 = 1;
			}
			else
			{
				$model							 = QuotesSituation::model()->findByPk($model->qds_id);
				$model->qds_quote_given_count	 = $count + 1;
				$model->qds_updated_date		 = DBUtil::getCurrentTime();
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
			$model			 = Booking::model()->findByPk($bkgId);
			$fromCity		 = $model->bkg_from_city_id;
			$toCity			 = $model->bkg_to_city_id;
			$pickupDate		 = ($model->bkg_pickup_date == null) ? date('Y-m-d') : date('Y-m-d', strtotime($model->bkg_pickup_date));
			$tripType		 = $model->bkg_booking_type;
			$cabType		 = $model->bkg_vehicle_type_id;
			$rowIdentifier	 = DynamicZoneSurge::getRowIdentifier($fromCity, $toCity, $cabType, $tripType);
			$models			 = self::model()->find('qds_row_identifier=:rowIdentifier AND qds_pickup_date=:pickupDate AND qds_status=1', ['rowIdentifier' => $rowIdentifier, 'pickupDate' => $pickupDate]);
			$count			 = ($models->qds_confirmed_count == null) ? 0 : $models->qds_confirmed_count;
			if ($models)
			{
				$models->qds_confirmed_count = $count + 1;
				$models->qds_updated_date	 = DBUtil::getCurrentTime();
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
			$model			 = Booking::model()->findByPk($bkgId);
			$fromCity		 = $model->bkg_from_city_id;
			$toCity			 = $model->bkg_to_city_id;
			$pickupDate		 = ($model->bkg_pickup_date == null) ? date('Y-m-d') : date('Y-m-d', strtotime($model->bkg_pickup_date));
			$tripType		 = $model->bkg_booking_type;
			$cabType		 = $model->bkg_vehicle_type_id;
			$rowIdentifier	 = DynamicZoneSurge::getRowIdentifier($fromCity, $toCity, $cabType, $tripType);
			$models			 = self::model()->find('qds_row_identifier=:rowIdentifier AND qds_pickup_date=:pickupDate AND qds_status=1', ['rowIdentifier' => $rowIdentifier, 'pickupDate' => $pickupDate]);
			$count			 = ($models->qds_cancelled_count == null) ? 0 : $models->qds_cancelled_count;
			if ($models)
			{
				$models->qds_cancelled_count = $count + 1;
				$models->qds_updated_date	 = DBUtil::getCurrentTime();
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

	public static function updateRowIdentifierMedainCapacity($rowIdentifier, $capacity)
	{
		$sql = "UPDATE quotes_situation SET qds_capacity=:capacity WHERE 1 AND qds_row_identifier=:rowIdentifier AND qds_status=1";
		DBUtil::execute($sql, ['rowIdentifier' => $rowIdentifier, 'capacity' => $capacity]);
	}

	public static function getDevliveryCount($rowIdentifier, $pickupDate)
	{
		$sql = 'SELECT					
				(IFNULL(qds_confirmed_count,0) - IFNULL(qds_cancelled_count,0)) AS pickupCount,
				qds_capacity AS capacity
				FROM quotes_situation
				WHERE 1
				AND DATE(qds_pickup_date)=DATE(:pickupDate)
				AND qds_row_identifier=:rowIdentifier 
				AND qds_status=1
				ORDER BY qds_id DESC LIMIT 0,1 ';
		return DBUtil::queryRow($sql, DBUtil::SDB(), ['rowIdentifier' => $rowIdentifier, 'pickupDate' => $pickupDate]);
	}

}

<?php

/**
 * This is the model class for table "flash_sale".
 *
 * The followings are the available columns in table 'flash_sale':
 * @property integer $fls_id
 * @property string $fls_type
 * @property integer $fls_route_id
 * @property string $fls_sale_start_date
 * @property string $fls_sale_end_date
 * @property string $fls_pickup_date
 * @property string $fls_pickup_address
 * @property string $fls_drop_address
 * @property integer $fls_no_of_bookings
 * @property integer $fls_promo_id
 * @property integer $fls_active
 * @property integer $fls_sold_out 
 * 
 * The followings are the available model relations:
 * @property Route $flsRoute
 */
class FlashSale extends CActiveRecord
{

	public $fls_from_city, $fls_to_city;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'flash_sale';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fls_route_id', 'required'),
			array('fls_route_id, fls_no_of_bookings, fls_promo_id, fls_active', 'numerical', 'integerOnly' => true),
			array('fls_type', 'length', 'max' => 50),
			array('fls_pickup_address, fls_drop_address', 'length', 'max' => 255),
			array('fls_sale_start_date, fls_sale_end_date, fls_pickup_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('fls_id, fls_type, fls_route_id, fls_sale_start_date, fls_sale_end_date, fls_pickup_date, fls_pickup_address, fls_drop_address, fls_no_of_bookings, fls_sold_out,fls_promo_id, fls_active,fls_from_city,fls_to_city', 'safe', 'on' => 'search'),
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
			'flsRoute' => array(self::BELONGS_TO, 'Route', 'fls_route_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'fls_id'				 => 'Fls',
			'fls_type'				 => 'Fls Type',
			'fls_route_id'			 => 'Fls Route',
			'fls_sale_start_date'	 => 'Fls Sale Start Date',
			'fls_sale_end_date'		 => 'Fls Sale End Date',
			'fls_pickup_date'		 => 'Fls Pickup Date',
			'fls_pickup_address'	 => 'Fls Pickup Address',
			'fls_drop_address'		 => 'Fls Drop Address',
			'fls_sold_out'			 => 'Fls Sold Out',
			'fls_no_of_bookings'	 => 'Fls No Of Bookings',
			'fls_promo_id'			 => 'Fls Promo',
			'fls_active'			 => 'Fls Active',
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

		$criteria->compare('fls_id', $this->fls_id);
		$criteria->compare('fls_type', $this->fls_type, true);
		$criteria->compare('fls_route_id', $this->fls_route_id);
		$criteria->compare('fls_sale_start_date', $this->fls_sale_start_date, true);
		$criteria->compare('fls_sale_end_date', $this->fls_sale_end_date, true);
		$criteria->compare('fls_pickup_date', $this->fls_pickup_date, true);
		$criteria->compare('fls_pickup_address', $this->fls_pickup_address, true);
		$criteria->compare('fls_drop_address', $this->fls_drop_address, true);
		$criteria->compare('fls_no_of_bookings', $this->fls_no_of_bookings);
		$criteria->compare('fls_sold_out', $this->fls_sold_out);
		$criteria->compare('fls_promo_id', $this->fls_promo_id);
		$criteria->compare('fls_active', $this->fls_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FlashSale the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 
	 * @param $flsType
	 */
	public function getList($flsType, $pickupDate)
	{

		$where = '';
		switch ($flsType)
		{
			case 'RE1SALE':
				$Where	 = " AND flash_sale.fls_type = 'RE1SALE' AND '$pickupDate' BETWEEN flash_sale.fls_sale_start_date AND flash_sale.fls_sale_end_date";
				$OrderBy = " ORDER BY flash_sale.fls_sale_start_date ASC";
				$Limit	 = " LIMIT 0,8";
				break;
		}
		$sql = "SELECT
				flash_sale.*,
				route.rut_from_city_id,
				route.rut_to_city_id,
				c1.cty_name AS from_city_name,
				c2.cty_name AS to_city_name
				FROM
					`flash_sale`
			INNER JOIN route ON flash_sale.fls_route_id = route.rut_id
			INNER JOIN cities c1 ON
				route.rut_from_city_id = c1.cty_id
			INNER JOIN cities c2 ON
				route.rut_to_city_id = c2.cty_id
			WHERE
					flash_sale.fls_active = 1 $Where $OrderBy $Limit";
		return DBUtil::queryAll($sql);
	}

	public function checkSaleByflsId($flsId)
	{
		$fmodel		 = FlashSale::model()->findByPk($flsId);
		$pickupDate	 = $fmodel->fls_pickup_date;
		$frmCity	 = $fmodel->flsRoute->rutFromCity->cty_id;
		$toCity		 = $fmodel->flsRoute->rutToCity->cty_id;
		$isPayment	 = 0;
		$status		 = $this->findByRouteCities($pickupDate, $frmCity, $toCity, $isPayment);
		return $status;
	}

	public function findByRouteCities($pickupDate, $frmCity, $toCity, $isPayment)
	{
		$flashSaleBooking = 4;
		if ($isPayment == 1)
		{
			$flashSaleBooking = 5;
		}
		$sql = "SELECT
					IF(totBooking < $flashSaleBooking, 0, 1) AS saleStatus, totBooking
				FROM
					(
					SELECT
						COUNT(1) AS totBooking
					FROM
						`booking`
					INNER JOIN `booking_invoice` ON booking.bkg_id=booking_invoice.biv_bkg_id
					INNER JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id 
					AND booking_cab.bcb_active = 1 AND booking.bkg_active = 1
					WHERE
						booking.bkg_pickup_date = '$pickupDate' 
						AND booking.bkg_from_city_id = '$frmCity' 
						AND booking.bkg_to_city_id = '$toCity' 
						AND booking_invoice.bkg_promo1_code = 'FLATRE1' 
						AND booking.bkg_status = 2
				) a";
		$row = DBUtil::queryRow($sql);
		//$totBooking	 = ($isPayment == 1) ? ($row['totBooking'] + 1) : $row['totBooking'];
		/*
		  if ($isPayment > 0)
		  {
		  $saleStatus = ($totBooking < 5) ? 0 : 1;
		  }
		  else
		  {
		  $saleStatus = ($totBooking < 4) ? 0 : 1;
		  } */
		//$saleStatus = ($totBooking >=4) ? 1 : 0;
		//return $saleStatus;
		return $row['saleStatus'];
	}

	public function getDateList()
	{
		/*

		  $dateList = [
		  date('Y-m-d')										 => date('d/m/Y'),
		  date('Y-m-d', strtotime(date('Y-m-d') . ' + 1 day'))	 => date('d/m/Y', strtotime(date('Y-m-d') . ' + 1 day')),
		  date('Y-m-d', strtotime(date('Y-m-d') . ' + 2 day'))	 => date('d/m/Y', strtotime(date('Y-m-d') . ' + 2 day')),
		  date('Y-m-d', strtotime(date('Y-m-d') . ' + 3 day'))	 => date('d/m/Y', strtotime(date('Y-m-d') . ' + 3 day')),
		  date('Y-m-d', strtotime(date('Y-m-d') . ' + 4 day'))	 => date('d/m/Y', strtotime(date('Y-m-d') . ' + 4 day')),
		  date('Y-m-d', strtotime(date('Y-m-d') . ' + 5 day'))	 => date('d/m/Y', strtotime(date('Y-m-d') . ' + 5 day')),
		  '0'													 => 'My date or route is not visible'
		  ];
		 * 
		 */
		$dateList = [
			'1'	 => date('d/m/Y'),
			'2'	 => date('d/m/Y', strtotime(date('Y-m-d') . ' + 1 day')),
			'3'	 => date('d/m/Y', strtotime(date('Y-m-d') . ' + 2 day')),
			'4'	 => date('d/m/Y', strtotime(date('Y-m-d') . ' + 3 day')),
			'5'	 => date('d/m/Y', strtotime(date('Y-m-d') . ' + 4 day')),
			'6'	 => date('d/m/Y', strtotime(date('Y-m-d') . ' + 5 day')),
			'0'	 => 'My date or route is not visible'
		];
		return $dateList;
	}

	public function fetchDate($type)
	{
		$list = $this->getDateList();
		return $list[$type];
	}

	public function getDateRangeOnSale()
	{
		$sql = "SELECT
				DATE_FORMAT(MIN(fls_pickup_date),'%D %b %Y') AS startDate,
				DATE_FORMAT(MAX(fls_pickup_date),'%D %b %Y') AS endDate
				FROM
				(
					SELECT
						*
					FROM
						`flash_sale`
					WHERE flash_sale.fls_type = 'RE1SALE' AND DATE_ADD(
							flash_sale.fls_sale_start_date,
							INTERVAL 6 HOUR
						) > NOW() AND flash_sale.fls_active='1'
					LIMIT 0, 8
				) a";
		return DBUtil::queryRow($sql);
	}

	public function getByRouteId($routeId)
	{
		if ($routeId != '')
		{
			$criteria = new CDbCriteria;
			$criteria->compare('fls_route_id', $routeId);
			return $this->find($criteria);
		}
		return NULL;
	}

	public static function getFlashBaseAmount()
	{
		return Yii::app()->params['just199BaseAmount'];;
	}

}

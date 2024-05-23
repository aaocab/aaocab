<?php

/**
 * This is the model class for table "profitability_surge".
 *
 * The followings are the available columns in table 'profitability_surge':
 * @property integer $prs_area_type
 * @property integer $prs_from_area
 * @property integer $prs_to_area
 * @property integer $prs_cab_type_id
 * @property integer $prs_booking_type
 * @property integer $prs_count_bookings
 * @property integer $prs_count_profit_bookings
 * @property string $prs_surge
 * @property string $prs_booking_percentage
 */
class ProfitabilitySurge extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'profitability_surge';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('prs_area_type, prs_from_area, prs_to_area, prs_cab_type_id, prs_booking_type, prs_count_bookings, prs_count_profit_bookings, prs_surge, prs_booking_percentage', 'required'),
			array('prs_area_type, prs_from_area, prs_to_area, prs_cab_type_id, prs_booking_type, prs_count_bookings, prs_count_profit_bookings', 'numerical', 'integerOnly' => true),
			array('prs_surge, prs_booking_percentage', 'length', 'max' => 7),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('prs_area_type, prs_from_area, prs_to_area, prs_cab_type_id, prs_booking_type, prs_count_bookings, prs_count_profit_bookings, prs_surge, prs_booking_percentage', 'safe', 'on' => 'search'),
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
			'prs_area_type'				 => 'Prs Area Type',
			'prs_from_area'				 => 'Prs From Area',
			'prs_to_area'				 => 'Prs To Area',
			'prs_cab_type_id'			 => 'Prs Cab Type',
			'prs_booking_type'			 => 'Prs Booking Type',
			'prs_count_bookings'		 => 'Prs Count Bookings',
			'prs_count_profit_bookings'	 => 'Prs Count Profit Bookings',
			'prs_surge'					 => 'Prs Surge',
			'prs_booking_percentage'	 => 'Prs Booking Percentage',
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

		$criteria->compare('prs_area_type', $this->prs_area_type);
		$criteria->compare('prs_from_area', $this->prs_from_area);
		$criteria->compare('prs_to_area', $this->prs_to_area);
		$criteria->compare('prs_cab_type_id', $this->prs_cab_type_id);
		$criteria->compare('prs_booking_type', $this->prs_booking_type);
		$criteria->compare('prs_count_bookings', $this->prs_count_bookings);
		$criteria->compare('prs_count_profit_bookings', $this->prs_count_profit_bookings);
		$criteria->compare('prs_surge', $this->prs_surge, true);
		$criteria->compare('prs_booking_percentage', $this->prs_booking_percentage, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProfitabilitySurge the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function setData()
	{
		list($usec1, $sec1) = explode(" ", microtime());
		$time = ((float) $usec1 + (float) $sec1);

		$where	 = " bkg.bkg_pickup_date BETWEEN (NOW() - INTERVAL 30 DAY) AND NOW() 
				AND bkg.bkg_status IN (6, 7)";
		$sql1	 = "SELECT   a.*, round(((countBooking - countProfitBooking) * 100 / countBooking), 2) bookingPercentage, 1 areaType
			FROM     (SELECT      bkg.bkg_from_city_id AS fromArea, bkg.bkg_to_city_id AS toArea,
			bkg.bkg_vehicle_type_id, bkg_booking_type bkgtype, 
			COUNT(DISTINCT bkg_id) AS countBooking, 
			COUNT(if(biv.bkg_gozo_amount < 0, NULL, bkg_id)) AS countProfitBooking,
			((SUM(biv.bkg_gozo_amount) / SUM(biv.bkg_total_amount)) * 100) AS profit
				FROM     booking bkg
					JOIN booking_invoice biv ON biv.biv_bkg_id = bkg.bkg_id	
			WHERE $where
			GROUP BY bkg.bkg_from_city_id, bkg.bkg_to_city_id, bkg_vehicle_type_id, bkgtype) a 
		";
		$sql2	 = "SELECT   a.*, round(((countBooking - countProfitBooking) * 100 / countBooking), 2) bookingPercentage, 2 areaType
			FROM     (SELECT    zc1.zct_zon_id AS fromArea, zc2.zct_zon_id AS toArea,
			bkg.bkg_vehicle_type_id,bkg_booking_type bkgtype, 
			COUNT(DISTINCT bkg_id) AS countBooking,
			COUNT(DISTINCT if(biv.bkg_gozo_amount < 0, NULL, bkg_id)) AS countProfitBooking,
			((SUM(biv.bkg_gozo_amount) / SUM(biv.bkg_total_amount)) * 100) AS profit
				FROM     booking bkg
					JOIN booking_invoice biv ON biv.biv_bkg_id = bkg.bkg_id							
					JOIN zone_cities zc1 ON zc1.zct_cty_id = bkg.bkg_from_city_id AND zc1.zct_active = 1
					JOIN zone_cities zc2 ON zc2.zct_cty_id = bkg.bkg_to_city_id AND zc2.zct_active = 1
			WHERE $where
			GROUP BY   zc1.zct_zon_id, zc2.zct_zon_id, bkg_vehicle_type_id ,bkgtype) a 
		";
		$select	 = "SELECT
		`areaType`,		`fromArea`,			`toArea`,		`bkg_vehicle_type_id`,	`bkgtype`,			`countBooking`,			`countProfitBooking`,			round((profit * -1),2) profitSurge ,	`bookingPercentage`
			from ";

		$ins = "INSERT into profitability_surge (
		`prs_area_type`, `prs_from_area`,	`prs_to_area`,	`prs_cab_type_id`,		`prs_booking_type`, `prs_count_bookings`,	`prs_count_profit_bookings`,	`prs_surge`,	`prs_booking_percentage`)
				$select ";

		$commonFilterSql = " WHERE    a.profit < -5  HAVING bookingPercentage > 30";

		$trans = DBUtil::beginTransaction();
		try
		{
			$insCityRaw	 = $select . '(' . $sql1 . $commonFilterSql . ') b';
			$ins2		 = $ins . '(' . $sql2 . $commonFilterSql . ') b';
			$remIndex	 = "
					ALTER TABLE `profitability_surge` DROP INDEX IF EXISTS `prs_from_area`;
					ALTER TABLE `profitability_surge` DROP INDEX IF EXISTS `prs_to_area` ;
					ALTER TABLE `profitability_surge` DROP INDEX IF EXISTS `prs_area_type` ;
					ALTER TABLE `profitability_surge` DROP INDEX IF EXISTS `prs_cab_type_id` ;
					ALTER TABLE `profitability_surge` DROP INDEX IF EXISTS `prs_booking_type` ;";
			$addIndex	 = "
					ALTER TABLE `profitability_surge` ADD INDEX IF NOT EXISTS ( `prs_from_area`);
					ALTER TABLE `profitability_surge` ADD INDEX IF NOT EXISTS ( `prs_to_area`);
					ALTER TABLE `profitability_surge` ADD INDEX IF NOT EXISTS ( `prs_area_type`);
					ALTER TABLE `profitability_surge` ADD INDEX IF NOT EXISTS ( `prs_cab_type_id`);
					ALTER TABLE `profitability_surge` ADD INDEX IF NOT EXISTS ( `prs_booking_type`);";

			echo "Before query";

			ProfitabilitySurge::showDuration($time);
			DBUtil::command($remIndex)->execute();
			echo "After index remove";

			ProfitabilitySurge::showDuration($time);
			DBUtil::command("TRUNCATE profitability_surge")->execute();
			echo "After TRUNCATE";
			ProfitabilitySurge::showDuration($time);

			$dataReader	 = DBUtil::command($insCityRaw)->query();
			$ins1		 = ProfitabilitySurge::getCityDatatoInsert($dataReader);
			if ($ins1)
			{
				DBUtil::command($ins1)->execute();
				echo "After CityQuery";
				ProfitabilitySurge::showDuration($time);
			}
			if ($ins2)
			{
				DBUtil::command($ins2)->execute();
				echo "After ZoneQuery";
				ProfitabilitySurge::showDuration($time);
			}
			DBUtil::command($addIndex)->execute();
			echo "After add index";
			ProfitabilitySurge::showDuration($time);

			DBUtil::commitTransaction($trans);
			echo "Data updated successfully";
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($trans);
			echo "Got some error";
			echo "<BR>";
			echo $e->getMessage();
			echo "<BR>";
			echo $e->getCode();
		}
	}

	public static function showDuration($time)
	{
		if (isset($GLOBALS['timeDur']))
		{
			$statTime = $GLOBALS['timeDur'];
		}
		else
		{
			$GLOBALS['timeDur'] = 0;
		}

		list($usec1, $sec1) = explode(" ", microtime());
		$time1		 = ((float) $usec1 + (float) $sec1);
		$a			 = $time1 - $time;
		echo "\r\n";
		echo "duration : " . ( $a - $statTime);
		$statTime	 = $a;
		echo "\r\n";

		echo "total duration : " . $a;
		echo "\r\n";
		echo "\r\n";
		$GLOBALS['timeDur'] = $statTime;
	}

	public static function fetchData($fromCity, $toCity, $amount, $cabType = '', $tripType = '')
	{
		$sql_vehicle_type	 = ($cabType != '') ? " AND (prs_cab_type_id={$cabType} OR prs_cab_type_id IS NULL)" : "";
		$sql_trip_type		 = ($tripType != '') ? " AND (prs_booking_type={$tripType} OR prs_booking_type IS NULL)" : "";

		$stmt = " fct.cty_id = $fromCity AND tct.cty_id = $toCity $sql_vehicle_type $sql_trip_type   ";

		$select = "SELECT prs.*,
						fct.cty_id fctid, tct.cty_id tctid,  fzct.zct_zon_id fzon,tzct.zct_zon_id tzon,
						fct.cty_name AS fromCity,tct.cty_name AS toCity
				  FROM profitability_surge prs ";

		$sql = "SELECT  *, prs_area_type areaRank, ($amount * (prs_surge + 100)/100) totamount
				FROM   (
						$select
						JOIN zone_cities fzct
						  ON (prs_area_type = 1 AND fzct.zct_cty_id = prs_from_area)
						  OR (prs_area_type = 2 AND fzct.zct_zon_id = prs_from_area)
						JOIN zone_cities tzct
						  ON (prs_area_type = 1 AND tzct.zct_cty_id = prs_to_area)
						  OR (prs_area_type = 2 AND tzct.zct_zon_id = prs_to_area)
						JOIN cities fct
						  ON (prs_area_type = 1 AND fct.cty_id =  prs_from_area)
						  OR (prs_area_type = 2 AND fzct.zct_cty_id = fct.cty_id)
						JOIN cities tct
						  ON (prs_area_type = 1 AND tct.cty_id =  prs_to_area)
						  OR (prs_area_type = 2 AND tzct.zct_cty_id = tct.cty_id)
						JOIN states fstt ON (fstt.stt_id = fct.cty_state_id)
						JOIN states tstt ON (tstt.stt_id = tct.cty_state_id)
						WHERE  prs_area_type IN (1,2) AND $stmt
					) a
				ORDER BY areaRank ASC, totamount DESC LIMIT 1 ";

		$result = DBUtil::queryRow($sql, DBUtil::SDB(), [], 300, CacheDependency::Type_Surge);
		return $result;
	}

	public static function isApplicable()
	{
		$globalFlag = Yii::app()->params['profitabilitySurge'];

		return $globalFlag;
	}

	public static function getCityDatatoInsert($dataReader)
	{
		$dbNow	 = Filter::getDBDateTime();
		$insHead = "INSERT into profitability_surge (
`prs_area_type`, `prs_from_area`,`prs_to_area`, `prs_cab_type_id`, `prs_booking_type`, `prs_count_bookings`, `prs_count_profit_bookings`, `prs_surge`, `prs_booking_percentage`)
VALUES ";
		$insVal	 = '';
		if (sizeof($dataReader) == 0)
		{
			return false;
		}
		foreach ($dataReader as $dataRow)
		{
			$fromCity		 = $dataRow['fromArea'];
			$toCity			 = $dataRow['toArea'];
			$cabType		 = $dataRow['bkg_vehicle_type_id'];
			$tripType		 = $dataRow['bkgtype'];
			$lastUpdateDate	 = Rate::getlastUpdated($fromCity, $toCity, $cabType, $tripType);

			if ($lastUpdateDate != '')
			{
				$date1	 = new DateTime(date('Y-m-d', strtotime($lastUpdateDate)));
				$date2	 = new DateTime(date('Y-m-d', strtotime($dbNow)));

				$diffDate = $date1->diff($date2)->days;
				if ($diffDate <= 0)
				{
					$dataRow['profitSurge'] = 0;
				}
			}

			$areaType			 = $dataRow['areaType'];
			$fromArea			 = $dataRow['fromArea'];
			$toArea				 = $dataRow['toArea'];
			$bkg_vehicle_type_id = $dataRow['bkg_vehicle_type_id'];
			$bkgtype			 = $dataRow['bkgtype'];
			$countBooking		 = $dataRow['countBooking'];
			$countProfitBooking	 = $dataRow['countProfitBooking'];
			$profitSurge		 = "'" . $dataRow['profitSurge'] . "'";
			$bookingPercentage	 = "'" . $dataRow['bookingPercentage'] . "'";

			$insVal .= "($areaType,$fromArea,$toArea,$bkg_vehicle_type_id,$bkgtype,$countBooking,$countProfitBooking,$profitSurge,$bookingPercentage),";
		}

		$cityQry = $insHead . rtrim($insVal, ',') . ';';

		return $cityQry;
	}

}

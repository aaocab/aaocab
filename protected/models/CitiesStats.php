<?php

/**
 * This is the model class for table "cities_stats".
 *
 * The followings are the available columns in table 'cities_stats':
 * @property integer $cts_id
 * @property integer $cts_cty_id
 * @property integer $cts_airport_pickup_entry_fee
 * @property integer $cts_airport_drop_entry_fee
 * @property integer $cts_vnd_cnt
 * @property integer $cts_category
 * @property string $cts_created_date
 * @property string $cts_modified_date
 * @property integer $cts_active
 */
class CitiesStats extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cities_stats';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cts_cty_id, cts_vnd_cnt', 'required'),
			array('cts_cty_id, cts_cty_cnt, cts_active', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cts_id, cts_cty_id, cts_cty_cnt, cts_created_date, cts_modified_date, cts_active,cts_airport_pickup_entry_fee,cts_airport_drop_entry_fee,cts_category', 'safe', 'on' => 'search'),
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
			'cts_id'						 => 'Cts',
			'cts_cty_id'					 => 'Cts Cty',
			'cts_cty_cnt'					 => 'Cts vnd Cnt',
			'cts_airport_pickup_entry_fee'	 => 'airportPickupFee',
			'cts_airport_drop_entry_fee'	 => 'airportDropFee',
			'cts_category'					 => 'cts category',
			'cts_created_date'				 => 'Cts Created Date',
			'cts_modified_date'				 => 'Cts Modified Date',
			'cts_active'					 => 'Cts Active',
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

		$criteria->compare('cts_id', $this->cts_id);
		$criteria->compare('cts_cty_id', $this->cts_cty_id);
		$criteria->compare('cts_airport_pickup_entry_fee', $this->cts_airport_pickup_entry_fee);
		$criteria->compare('cts_airport_drop_entry_fee', $this->cts_airport_drop_entry_fee);
		$criteria->compare('cts_vnd_cnt', $this->cts_cty_cnt);
		$criteria->compare('cts_created_date', $this->cts_created_date, true);
		$criteria->compare('cts_modified_date', $this->cts_modified_date, true);
		$criteria->compare('cts_active', $this->cts_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CitiesStats the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function updateStats()
	{



		$sql = "SELECT ctt_city as ctyId,COUNT(ctt_id) AS cnt 
				FROM contact 
				INNER JOIN contact_profile cpr ON cpr.cr_contact_id = contact.ctt_id AND cpr.cr_status = 1
				INNER JOIN vendors ON vendors.vnd_id = cpr.cr_is_vendor AND vendors.vnd_id = vendors.vnd_ref_code   
				INNER JOIN 
				(
					SELECT DISTINCT vnd_id 
					FROM vendors   
					INNER JOIN app_tokens ON vendors.vnd_id = app_tokens.apt_entity_id AND app_tokens.apt_user_type=2 
					WHERE vendors.vnd_active = 1  
					AND app_tokens.apt_last_login >= DATE_SUB(NOW(),INTERVAL 30 DAY)

				) a ON vendors.vnd_id = a.vnd_id 

				GROUP BY ctt_city 
				HAVING cnt AND ctyId>0";



		$result		 = DBUtil::query($sql, DBUtil::SDB());
		$ctyIdStr	 = "";
		foreach ($result as $val)
		{
			$ctyIdStr	 .= $val['ctyId'] . ",";
			$params		 = array('cts_cty_id' => $val['ctyId'], 'cts_vnd_cnt' => $val['cnt']);
			$sql		 = "INSERT INTO cities_stats(cts_cty_id,cts_vnd_cnt) VALUES (:cts_cty_id,:cts_vnd_cnt) ON DUPLICATE KEY UPDATE cts_vnd_cnt =:cts_vnd_cnt";
			DBUtil::execute($sql, $params);
		}
		if ($ctyIdStr != null)
		{
			$ctyIdStr	 = trim($ctyIdStr, ",");
			DBUtil::getINStatement($ctyIdStr, $bindString, $params);
			$sqlUpdate	 = "Update cities_stats SET cts_vnd_cnt=0 WHERE cts_cty_id NOT IN($bindString)";
			DBUtil::execute($sqlUpdate, $params);
		}
	}

	/**
	 * This function is used for calculating airport entry charges based on pickup and drop both 
	 * @param int $cityId(pickup/drop)
	 * @param int $transferType   1=>Pickup Charge / 2=>Drop Charge 
	 * @return int
	 */
	public static function getAirportEntryCharge($cityId, $transferType = 1)
	{
		$params = ['cityId' => $cityId];
		switch ($transferType)
		{
			case 1:
				$entryFee	 = "cts_airport_pickup_entry_fee";
				break;
			case 2:
				$entryFee	 = "cts_airport_drop_entry_fee";
				break;
			default:
				break;
		}
		$sql	 = "SELECT $entryFee
               FROM   cities
               INNER JOIN cities_stats
               ON cities.cty_id = cities_stats.cts_cty_id
               WHERE  cty_id = :cityId
               AND cty_is_airport = 1 AND cty_active = 1";
		$charges = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return ($charges === false) ? 0 : $charges;
	}

	/**
	 * This function is used for calculating Airport entry Charges
	 * @param type (int) $fromCity
	 * @param type (int) $toCity
	 * @return \ReturnSet
	 */
	public static function getAirportEntryCharges($fromCity, $toCity = NULL)
	{
		$pickupCharge	 = self::getAirportEntryCharge($fromCity, 1);
		$dropCharge		 = self::getAirportEntryCharge($toCity, 2);

		$totalCharge = $pickupCharge + $dropCharge;
		return $totalCharge;
	}
    
    /**
	 * This function is used to get is c1 cities or not
	 * @param type (int) $fromCity
	 * @return \ReturnSet
	 */
    public static function getCategory($cityId)
    {
        $params = ['cityId' => $cityId];
        $sql = "SELECT cts_category FROM cities_stats WHERE cts_cty_id = :cityId";
        $result = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
        return $result['cts_category'];
    }

}

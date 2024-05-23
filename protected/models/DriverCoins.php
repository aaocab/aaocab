<?php

/**
 * This is the model class for table "driver_coins".
 *
 * The followings are the available columns in table 'driver_coins':
 * @property integer $drc_id
 * @property integer $drc_drv_id
 * @property integer $drc_type
 * @property integer $drc_value
 * @property string $drc_desc
 * @property integer $drc_ref_type
 * @property integer $drc_ref_id
 * @property integer $drc_active
 * @property integer $drc_user_id
 * @property integer $drc_user_type
 * @property datetime $drc_created_at
 * @property datetime $drc_modified_at
 */
class DriverCoins extends CActiveRecord
{

	// Type
	const TYPE_RATING			 = 1;
	const TYPE_DRIVER_ON_TIME	 = 2;
	const REF_BOOKING = 1;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'driver_coins';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('drc_drv_id, drc_type, drc_value, drc_modified_at', 'required'),
			array('drc_id, drc_drv_id, drc_type, drc_ref_type, drc_ref_id, drc_active, drc_user_id, drc_user_type', 'numerical', 'integerOnly' => true),
			array('drc_value', 'length', 'max' => 250),
			array('drc_desc, drc_created_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('drc_id, drc_drv_id, drc_type, drc_value, drc_desc, drc_ref_type, drc_ref_id, drc_active, drc_user_id, drc_user_type, drc_created_at, drc_modified_at', 'safe', 'on' => 'search'),
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
			'drc_id'			 => 'Drc',
			'drc_drv_id'		 => 'Drc Drv',
			'drc_type'			 => 'Drc Type',
			'drc_value'			 => 'Drc Value',
			'drc_desc'			 => 'Drc Desc',
			'drc_ref_type'		 => 'Drc Ref Type',
			'drc_ref_id'		 => 'Drc Ref',
			'drc_active'		 => 'Drc Active',
			'drc_user_id'		 => 'Drc User Id',
			'drc_user_type'		 => 'Drc User Type',
			'drc_created_at'	 => 'Drc Created At',
			'drc_modified_at'	 => 'Drc Modified At',
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

		$criteria->compare('drc_id', $this->drc_id);
		$criteria->compare('drc_drv_id', $this->drc_drv_id);
		$criteria->compare('drc_type', $this->drc_type);
		$criteria->compare('drc_value', $this->drc_value, true);
		$criteria->compare('drc_desc', $this->drc_desc, true);
		$criteria->compare('drc_ref_type', $this->drc_ref_type);
		$criteria->compare('drc_ref_id', $this->drc_ref_id);
		$criteria->compare('drc_active', $this->drc_active);
		$criteria->compare('drc_user_id', $this->drc_user_id);
		$criteria->compare('drc_user_type', $this->drc_user_type);
		$criteria->compare('drc_created_at', $this->drc_created_at, true);
		$criteria->compare('drc_modified_at', $this->drc_modified_at, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DriverCoins the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public Static function earnCoin($drvId, $type, $refId = null)
	{
		$success = false;
		if ($drvId > 0 && $type > 0)
		{
			if ($type == DriverCoins::TYPE_RATING)
			{
				$success = self::calcRatingCoins($drvId, $refId);
			}
		}
		return $success;
	}

	public static function calcRatingCoins($drvId, $bkgId)
	{
		$earnCoins = self::getConfig('rating');
		if ($earnCoins <= 0 || empty($earnCoins))
		{
			return false;
		}

		$type	 = DriverCoins::TYPE_RATING;
		$refType = DriverCoins::REF_BOOKING;
		$drvDesc = 'Driver coin added for 5 star rating';

		return self::add($drvId, $type, $earnCoins, $drvDesc, $refType, $bkgId);
	}

	public static function add($drvId, $type, $earnCoins, $drvDesc = null, $refType = null, $refId = null)
	{
		$trans = DBUtil::beginTransaction();
		try
		{
			$date					 = date('Y-m-d h:i:s');
			$model					 = new DriverCoins();
			$model->drc_drv_id		 = $drvId;
			$model->drc_type		 = $type;
			$model->drc_value		 = $earnCoins;
			$model->drc_desc		 = $drvDesc;
			$model->drc_ref_type	 = $refType;
			$model->drc_ref_id		 = $refId;
			$model->drc_user_id		 = (UserInfo::getUserId() > 0 ? UserInfo::getUserId() : NULL);
			$model->drc_user_type	 = (UserInfo::getUserType() > 0 ? UserInfo::getUserType() : NULL);
			$model->drc_created_at	 = $date;
			$model->drc_modified_at	 = $date;
			$result					 = CActiveForm::validate($model);

			if ($result != "[]")
			{
				throw new Exception("Validation failed to process driver coin!!!");
			}

			$model->save();
			DBUtil::commitTransaction($trans);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($trans);
			throw new Exception($e);
		}

		return true;
	}

	/**
	 * fetch coin constant from database according to type
	 * @param type $key
	 * @return type
	 */
	public static function getConfig($key = null)
	{
		$coinSettings	 = Config::get('driver.coin.settings');
		$arrSettings	 = json_decode($coinSettings, true);
		if ($key != null)
		{
			return (isset($arrSettings[$key]) ? $arrSettings[$key] : false);
		}
		return $arrSettings;
	}

	public static function countByBooking($type, $bookingId)
	{
		$params	 = ["bookingId" => $bookingId, 'type' => $type];
		$sql	 = "SELECT COUNT(drc_drv_id) as coinCount FROM driver_coins 
			WHERE drc_type = :type AND drc_ref_type=1 AND drc_ref_id=:bookingId ";
		$count	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $count;
	}

	/** 	 
	 * @param int $drvId
	 * @return type dataprovider
	 */
	public static function getCoinList($drvId)
	{
		$params			 = ["drvId" => $drvId];
		$sql			 = "SELECT drc_type,drc_value,drc_desc,drc_ref_type,drc_ref_id,drc_created_at FROM driver_coins WHERE drc_drv_id = :drvId AND drc_active=1";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'params'		 => $params,
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes' => ['drc_id '], 'defaultOrder' => 'drc_created_at DESC'],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	/**
	 * function used for total coin balance for driver
	 * @param type $drvId
	 * @return type
	 */
	public static function totalCoin($drvId)
	{
		$params = ["drvId" => $drvId];

		$sql = "SELECT SUM(drc_value) as totalCoin FROM driver_coins WHERE drc_drv_id =:drvId ";
		$res = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $res['totalCoin'];
	}

	public static function updateCoinDetails()
	{
		$sql	 = "SELECT GROUP_CONCAT(DISTINCT drc_drv_id) drvIds FROM driver_coins 
			WHERE drc_created_at >= DATE_SUB(NOW(), INTERVAL 3 DAY)";
		$drvIds	 = DBUtil::queryScalar($sql, DBUtil::SDB());
		if ($drvIds)
		{
			$sqlUpd	 = "SELECT drc_drv_id, SUM(drc_value) as totalCoin FROM `driver_coins` 
				WHERE drc_active = 1 AND drc_drv_id IN ({$drvIds}) GROUP BY drc_drv_id";
			$results = DBUtil::query($sqlUpd);
			foreach ($results as $row)
			{
				$drvId		 = $row['drc_drv_id'];
				$totalCoins	 = $row['totalCoin'];

				DriverStats::updateCoins($totalCoins, $drvId);
			}
		}
	}

	/**
	 * @param type $bkgId
	 * @param type $gozoAmount
	 * @param type $bkgTotalAmt
	 * @return type
	 */
	public static function calculateDriverRatingCoins($bkgId, $gozoAmount, $bkgTotalAmt)
	{
		$ratingRow = Ratings::getCustRatingbyBookingId($bkgId);
		if ($ratingRow['rtg_customer_overall'] == 5 && $ratingRow['rtg_customer_driver'] == 5 && $ratingRow['rtg_customer_car'] == 5)
		{
			$coins		 = round(min(max((($gozoAmount * 15) / 100), 50), (($bkgTotalAmt * 10) / 100), 200));
			$msg		 = 'Rating';
			return $resultArr	 = ['drvRatingCoins' => $coins, 'remarks' => $msg];
		}
		return false;
	}

	/**
	 * 
	 * @param type $drvId
	 * @param type $bkgId
	 * @param type $earnCoins
	 * @param type $drvDesc
	 * @param type $type
	 * @param type $refType
	 * @return type
	 */
	public static function addCoins($drvId, $bkgId, $earnCoins, $drvDesc, $type, $refType)
	{
		return self::add($drvId, $type, $earnCoins, $drvDesc, $refType, $bkgId);
	}

}

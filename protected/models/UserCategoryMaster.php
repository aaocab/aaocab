<?php

/**
 * This is the model class for table "user_category_master".
 *
 * The followings are the available columns in table 'user_category_master':
 * @property integer $ucm_id
 * @property string $ucm_label
 * @property string $ucm_cash_trip_types
 * @property integer $ucm_rel_manager
 * @property integer $ucm_gozocoin_percent
 * @property integer $ucm_coins
 * @property integer $ucm_active
 * @property string $ucm_modified
 * @property string $ucm_created
 */
class UserCategoryMaster extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_category_master';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ucm_label, ucm_modified, ucm_created', 'required'),
			array('ucm_rel_manager, ucm_gozocoin_percent, ucm_active', 'numerical', 'integerOnly' => true),
			array('ucm_label', 'length', 'max' => 300),
			array('ucm_cash_trip_types', 'length', 'max' => 11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ucm_id, ucm_label, ucm_cash_trip_types, ucm_rel_manager, ucm_gozocoin_percent, ucm_active, ucm_modified, ucm_created,ucm_coins', 'safe', 'on' => 'search'),
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
			'ucm_id'				 => 'Ucm',
			'ucm_label'				 => 'Ucm Label',
			'ucm_cash_trip_types'	 => 'Ucm Cash Trip Types',
			'ucm_rel_manager'		 => 'Ucm Rel Manager',
			'ucm_gozocoin_percent'	 => 'Ucm Gozocoin Percent',
			'ucm_coins'				 => 'Ucm Promocoin Percent',
			'ucm_active'			 => 'Ucm Active',
			'ucm_modified'			 => 'Ucm Modified',
			'ucm_created'			 => 'Ucm Created',
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

		$criteria->compare('ucm_id', $this->ucm_id);
		$criteria->compare('ucm_label', $this->ucm_label, true);
		$criteria->compare('ucm_cash_trip_types', $this->ucm_cash_trip_types, true);
		$criteria->compare('ucm_rel_manager', $this->ucm_rel_manager);
		$criteria->compare('ucm_gozocoin_percent', $this->ucm_gozocoin_percent);
		$criteria->compare('ucm_active', $this->ucm_active);
		$criteria->compare('ucm_modified', $this->ucm_modified, true);
		$criteria->compare('ucm_created', $this->ucm_created, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserCategoryMaster the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function catDropdownList()
	{
		$sql	 = "SELECT ucm_id,ucm_label FROM `user_category_master` WHERE ucm_active = 1";
		$result	 = DBUtil::query($sql);
		$arr	 = CHtml::listData($result, 'ucm_id', 'ucm_label');
		return $arr;
	}

	public static function getColorByid($id)
	{
		$arr = [1 => 'cat-1.png', 2 => 'cat-2.png', 3 => 'cat-3.png', 4 => 'cat-4.png'];
		return $arr[$id];
	}

	public static function isCashBookingAllowed($userId, $tripType)
	{
		$params	 = ['user' => $userId, 'tripType' => $tripType];
		$sql	 = "SELECT ucm_id FROM contact_profile cp 
				INNER JOIN contact ON ctt_id = cp.cr_contact_id AND ctt_active = 1
				INNER JOIN contact_pref cpr ON cp.cr_contact_id = cpr.cpr_ctt_id 
				INNER JOIN user_category_master ucm ON ucm.ucm_id = cpr.cpr_category WHERE cp.cr_status = 1 AND cp.cr_is_consumer = :user AND FIND_IN_SET(:tripType,ucm_cash_trip_types)";
		$id		 = DBUtil::queryScalar($sql, null, $params);
		if($id > 0)
		{
			return true;
		}
		return false;
	}

	public static function getByUserId($userId)
	{
		$params	 = ['user' => $userId];
		$sql	 = "SELECT ucm_id,ucm_label,ucm_cash_trip_types,ucm_rel_manager,ucm_gozocoin_percent,ucm_coins 
					FROM contact_profile cp 
				INNER JOIN contact ON ctt_id = cp.cr_contact_id AND ctt_active = 1
				INNER JOIN contact_pref cpr ON cp.cr_contact_id = cpr.cpr_ctt_id 
				INNER JOIN user_category_master ucm ON ucm.ucm_id = cpr.cpr_category 
				WHERE cp.cr_status = 1 AND cp.cr_is_consumer = :user AND ucm_active = 1";
		$arr	 = DBUtil::queryRow($sql, null, $params);
		return $arr;
	}

	public static function addCoinsOnComplete($bkgId, $userId, $kms)
	{
		$amount	 = 0;
		$ucmData = self::getByUserId($userId);
		if($ucmData['ucm_coins'] != '')
		{
			$promocoinSetting	 = json_decode($ucmData['ucm_coins'], true);
			$percent			 = $promocoinSetting['percent'];
			$coinPerKm			 = $promocoinSetting['coinperkm'];

			if($coinPerKm > 0)
			{
				$amount = round($kms * $coinPerKm);
			}
			if($percent > 0)
			{
				$amount = round($amount * (1 + $percent * 0.01));
			}
			$isExist = false;
			$isExist = self::checkDuplicateCoin($userId, UserCredits::CREDIT_BOOKING, $bkgId, BookingLog::BOOKING_PROMO);
			if($amount > 0 && !$isExist)
			{
				UserCredits::addCoins($userId, UserCredits::CREDIT_BOOKING, null, $amount, $bkgId, "coins given for {$ucmData['ucm_label']} customer", 1);
				BookingLog::model()->createLog($bkgId, "{$amount} GozoCoins credited on completing trip.", null, BookingLog::BOOKING_PROMO);
			}
		}
	}

	public static function checkDuplicateCoin($userId, $type, $bkgId, $eventId)
	{
		$params	 = ['userId' => $userId, 'type' => $type, 'refId' => $bkgId];
		$sql	 = "SELECT ucr_id FROM `user_credits` WHERE ucr_type = :type AND ucr_user_id = :userId AND ucr_ref_id =:refId";
		$ucrId	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		//	$logId	 = BookingLog::checkExistingLog($bkgId, $eventId);
		if($ucrId > 0)
		{
			return true;
		}
		return false;
	}
}

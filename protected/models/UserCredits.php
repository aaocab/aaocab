<?php

use Facebook\Facebook;

/**
 * This is the model class for table "user_credits".
 *
 * The followings are the available columns in table 'user_credits':
 * @property string $ucr_id
 * @property integer $ucr_user_id
 * @property integer $ucr_value
 * @property integer $ucr_used
 * @property string $ucr_validity
 * @property string $ucr_desc
 * @property integer $ucr_max_use
 * @property integer $ucr_type
 * @property integer $ucr_ref_id
 * @property integer $ucr_status
 * @property string $ucr_created
 * @property string $ucr_modified
 * @property string $user_name
 * @property integer $user_mobile
 * @property string $user_email
 * @property integer $ucr_maxuse_type
 * @property integer $CreditVal
 */
class UserCredits extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public $user_name, $CreditVal, $user_mobile, $user_email, $creditStatus;
	public $created, $amount, $description, $ptp_id, $activateType;
	public static $bookingCreditType = [1 => "promo", 2 => "refund"];

	const CREDIT_PROMO	 = 1;
	const CREDIT_REFUND	 = 2;
	const CREDIT_REFERRAL	 = 3;
	const CREDIT_BOOKING	 = 4;
	const CREDIT_OTHER	 = 5;
	const CREDIT_SIGNUP	 = 6;

	public function tableName()
	{
		return 'user_credits';
	}

	public function defaultScope()
	{

		return ['condition' => "ucr_status=1"];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ucr_user_id, ucr_value', 'required'),
			array('ucr_user_id, ucr_value, ucr_used, ucr_max_use, ucr_type, ucr_ref_id, ucr_status', 'numerical', 'integerOnly' => true),
			array('ucr_desc', 'length', 'max' => 500),
			array('ucr_desc,ucr_user_id,ucr_value,ucr_type,ucr_maxuse_type', 'required', 'on' => 'creditbyadmin'),
			array('ucr_validity, ucr_created, ucr_modified', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_name,ucr_id, ucr_user_id, ucr_value, ucr_used, ucr_validity, ucr_desc, ucr_max_use, ucr_type, ucr_ref_id, ucr_status, ucr_created, ucr_modified,CreditVal,created,amount,description,ptp_id,ucr_maxuse_type,user_mobile,user_email', 'safe'),
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
			'ucrUsers'		 => [self::BELONGS_TO, 'Users', 'ucr_user_id'],
			//   'ucrTransactions'=>[self::HAS_MANY,'Transactions','trans_ref_id'],
			'ucrBooking'	 => [self::BELONGS_TO, 'Booking', 'ucr_ref_id'],
			//'ucrPromo' => [self::BELONGS_TO, 'Promotions', 'ucr_ref_id'],
			'ucrReferral'	 => [self::BELONGS_TO, 'Users', 'ucr_ref_id'],
			'ucrRefund'		 => [self::BELONGS_TO, 'Transactions', 'ucr_ref_id'],
			'ucrAdmin'		 => [self::BELONGS_TO, 'Admins', 'ucr_ref_id'],
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ucr_id'			 => 'ID',
			'ucr_user_id'		 => 'User',
			'ucr_value'			 => 'Gozo Coins',
			'ucr_used'			 => 'Gozo Coins Used',
			'ucr_validity'		 => 'Validity',
			'ucr_desc'			 => 'Description',
			'ucr_max_use'		 => 'Gozo Coins Max Use',
			'ucr_maxuse_type'	 => 'Gozo Coins Max Use Type',
			'ucr_type'			 => 'Type',
			'ucr_ref_id'		 => 'Reference',
			'ucr_status'		 => 'Status',
			'ucr_created'		 => 'Created',
			'ucr_modified'		 => 'Modified',
			'user_name'			 => 'User',
			'user_mobile'		 => 'Phone',
			'user_email'		 => 'Email'
		);
	}

	protected function beforeSave()
	{
		parent::beforeSave();
		if ($this->ucr_max_use <= $this->ucr_value)
		{
			return TRUE;
		}
		return FALSE;
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

		$criteria->compare('ucr_id', $this->ucr_id, true);
		$criteria->compare('ucr_user_id', $this->ucr_user_id);
		$criteria->compare('ucr_value', $this->ucr_value);
		$criteria->compare('ucr_used', $this->ucr_used);
		$criteria->compare('ucr_validity', $this->ucr_validity, true);
		$criteria->compare('ucr_desc', $this->ucr_desc, true);
		$criteria->compare('ucr_max_use', $this->ucr_max_use);
		$criteria->compare('ucr_type', $this->ucr_type);
		$criteria->compare('ucr_ref_id', $this->ucr_ref_id);

		$criteria->with = ['ucrUsers'];
		if ($this->ucr_type == 1)
		{
			//$criteria->with = ['ucrPromo'];
			$criteria->with = ['ucrBooking'];
		}
		if ($this->ucr_type == 2)
		{
			$criteria->with = ['ucrRefund'];
		}
		if ($this->ucr_type == 3 || $this->ucr_type == 6)
		{
			$criteria->with = ['ucrReferral'];
		}
		if ($this->ucr_type == 4 || $this->ucr_type == 7 || $this->ucr_type == 8)
		{
			$criteria->with = ['ucrBooking'];
		}
		if ($this->ucr_type == 5)
		{
			$criteria->with = ['ucrAdmin'];
		}
		$criteria->compare('ucrUsers.usr_name', $this->user_name, true);
		$criteria->compare('ucrUsers.usr_mobile', $this->user_mobile, true);
		$criteria->compare('ucrUsers.usr_email', $this->user_email, true);
		return new CActiveDataProvider($this->with('ucrUsers'), array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserCredits the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getByUserID($userId)
	{
		$model = $this->find("ucr_user_id=:userId", ['userId' => $userId]);
		return $model;
	}

	public function getTypes($key = 0)
	{
		$typesArr = [1 => "promo", 2 => "refund", 3 => "referral", 4 => "booking", 5 => "others(Admin)", 6 => "referred", 7 => "booking(CREDITS PER KM RIDDEN)", 8 => "booking(CREDITS EQUALS COD AMOUNT)", 9 => "notification"];
		// 3=>refer to a friend else,  6=>referred by someone
		if ($key != 0)
		{
			return $typesArr[$key];
		}
		return $typesArr;
	}

	public function getMaxUseTypes($key = 0,$userId = 0)
	{
    
	    $usage	 = Config::getValue("gozocoin.promo.usage", 'percentage');
		$useType1percent = $usage;
		$useType2percent = 50;
		$useType4percent = 7;
        if($userId>0)
		{
			$rowUcm = UserCategoryMaster::getByUserId($userId);
			if($rowUcm['ucm_gozocoin_percent']>0)
			{
				$useType1percent = ($rowUcm['ucm_gozocoin_percent'] > $useType1percent)?$rowUcm['ucm_gozocoin_percent']:$useType1percent;
				$useType2percent = ($rowUcm['ucm_gozocoin_percent'] > $useType2percent)?$rowUcm['ucm_gozocoin_percent']:$useType2percent;
				$useType4percent = ($rowUcm['ucm_gozocoin_percent'] > $useType4percent)?$rowUcm['ucm_gozocoin_percent']:$useType4percent;
			}
		}
		//$typesArr = [1 => "10% of Total Booking Amount", 2 => "50 % of Total Credits", 3 => "Full Credits", 4 => "7% of Total Booking Amount"];
		$typesArr = [1 => "$useType1percent% of Base Amount", 2 => "$useType2percent% of Base Amount", 3 => "Full Credits", 4 => "$useType4percent% of Base Amount"];
		if ($key != 0)
		{
			return $typesArr[$key];
		}
		return $typesArr;
	}

	public function getCreditTypes($key = 0)
	{
		//$typesArr = [1 => "10% of Total Booking Amount", 2 => "50 % of Total Credits", 3 => "Full Credits", 4 => "7% of Total Booking Amount"];
		$typesArr = [1 => "Promo (Promo code cannot be used)", 4 => "Cash (Promo code can be used)"];
		if ($key != 0)
		{
			return $typesArr[$key];
		}
		return $typesArr;
	}

	public function getTotCreditsByUser_OLD($user, $amount, $usePromo = true, $fromCityId, $toCityId)
	{
		$isPromo	 = BookingSub::model()->getApplicable($fromCityId, $toCityId, 2);
		$tot		 = $tot1		 = 0;
		$promoUsed	 = false;
		if ($amount > 0 && $user > 0)
		{
			$sql = "SELECT LEAST(SUM(
                    CASE    
                    WHEN ucr_type=2  THEN LEAST(ucr_value, $amount, (ucr_value - IF(ucr_used IS NULL, 0, ucr_used))) ELSE 0 END
                 ),$amount) AS creditVal
                 FROM `user_credits` `t` WHERE (ucr_status=1) AND (ucr_user_id=$user) AND (ucr_validity>NOW() OR ucr_validity IS NULL)";

			$record1 = DBUtil::queryRow($sql);
			$tot	 = ($record1['creditVal'] > 0) ? $record1['creditVal'] : 0;
			if ($usePromo && $isPromo)
			{
				$sqlnew1 = "SELECT LEAST(SUM(b.CreditVal),$amount) CreditVal FROM (SELECT
  t1.ucr_id ucr_id
 ,t1.leastVal CreditVal
FROM
  (SELECT
     ucr_maxuse_type
    ,ucr_id
    ,least(
       if(
         ucr_max_use IS NULL
        ,creditVal
        ,ucr_max_use)
      ,ucr_remaining
      ,creditVal)
       leastVal
    ,ucr_validity
   FROM
     (SELECT
        *
       ,(ucr_value - ucr_used) ucr_remaining
       ,(CASE
           WHEN ucr_maxuse_type = 1 THEN ROUND($amount * 0.15)
           WHEN ucr_maxuse_type = 2 THEN ROUND(ucr_value * 0.5)
           WHEN ucr_maxuse_type = 3 THEN ucr_value
           WHEN ucr_maxuse_type = 4 THEN ROUND($amount * 0.07)
           ELSE 0
         END)
          creditVal
      FROM
        `user_credits`
      WHERE
        `ucr_user_id` = $user AND
        ucr_status = 1 AND
        ucr_type <> 2 AND
        (ucr_validity > NOW() OR
         ucr_validity IS NULL)
      HAVING
        ucr_remaining > 0) a
   HAVING
     leastVal > 0
   ORDER BY
     leastVal DESC
    ,ucr_validity ASC) t1
  INNER JOIN
  (SELECT
     ucr_maxuse_type
    ,ucr_id
    ,MAX(
       least(
         if(
           ucr_max_use IS NULL
          ,creditVal
          ,ucr_max_use)
        ,ucr_remaining
        ,creditVal))
       leastVal
    ,ucr_validity
   FROM
     (SELECT
        *
       ,(ucr_value - ucr_used) ucr_remaining
       ,(CASE
           WHEN ucr_maxuse_type = 1 THEN ROUND($amount * 0.15)
           WHEN ucr_maxuse_type = 2 THEN ROUND(ucr_value * 0.5)
           WHEN ucr_maxuse_type = 3 THEN ucr_value
           WHEN ucr_maxuse_type = 4 THEN ROUND($amount * 0.07)
           ELSE 0
         END)
          creditVal
      FROM
        `user_credits`
      WHERE
        `ucr_user_id` = $user AND
        ucr_status = 1 AND
        ucr_type <> 2 AND
        (ucr_validity > NOW() OR
         ucr_validity IS NULL)
      HAVING
        ucr_remaining > 0) a
   GROUP BY
     ucr_maxuse_type
   HAVING
     leastVal > 0
   ORDER BY
     leastVal DESC
    ,ucr_validity ASC) t2
GROUP BY
  t1.ucr_maxuse_type
ORDER BY
  t1.leastVal DESC
 ,t1.ucr_validity ASC) b";

				$record2	 = DBUtil::command($sqlnew1)->queryRow();
				$tot1		 = ($record2['CreditVal'] > 0) ? $record2['CreditVal'] : 0;
				$promoUsed	 = ($tot1 > 0);
			}
		}
		$totVal = $tot + $tot1;
		if ($totVal < $amount)
		{
			$totVal = $totVal;
		}
		else
		{
			$totVal = $amount;
		}
		return ["credits" => $totVal, "promoUsed" => $promoUsed, "refundCredits" => $tot];
	}

	/**
	 * 
	 * @param integer $user
	 * @param integer $amount
	 * @return integer
	 */
	public static function getMaxApplicableRefundCredits($user, $amount)
	{
		$sql = "SELECT LEAST(SUM(
						CASE    
							WHEN ucr_type=2  THEN LEAST(ucr_value, (ucr_value - IFNULL(ucr_used, 0))) 
							ELSE 0 
						END
						), $amount) AS maxApplicable
                 FROM `user_credits` `t` WHERE (ucr_status=1) AND (ucr_user_id=$user) AND (ucr_validity>NOW() OR ucr_validity IS NULL)";

		$maxAmount			 = DBUtil::queryScalar($sql);
		$totalRefundCoins	 = max([$maxAmount, 0]);
		return $totalRefundCoins;
	}

	/**
	 * 
	 * @param integer $user
	 * @param integer $amount
	 * @return array
	 */
	public static function getMaxApplicablePromoCredits($user, $amount, $usage = null)
	{
		$maxallowablePromoUsage	 = Config::getValue("gozocoin.promo.usage", 'maxallowable');
		$percentPromoUsage		 = Config::getValue("gozocoin.promo.usage", 'percentage');
		if ($usage == null && $percentPromoUsage > 0)
		{
			$usage = $percentPromoUsage / 100;
		}
		if ($usage === null)
		{
			$usage = 0.15;
		}
		$defMaxPercentType1 = $usage; //ucr_maxuse_type 1 = 15%,ucr_maxuse_type 2 = 50%,ucr_maxuse_type 3 = 100%,ucr_maxuse_type 4 = 7%
		$defMaxPercentType2 = 0.5;
		$defMaxPercentType4 = 0.07;
		$RowUCM = UserCategoryMaster::getByUserId($user);
		$percentUserCatWise = $RowUCM['ucm_gozocoin_percent']/100;
		if($percentUserCatWise>0)
		{
			if($percentUserCatWise > $defMaxPercentType1)
			{
				$defMaxPercentType1 = $percentUserCatWise;
			}
			if($percentUserCatWise > $defMaxPercentType2)
			{
				$defMaxPercentType2 = $percentUserCatWise;
			}
			if($percentUserCatWise > $defMaxPercentType4)
			{
				$defMaxPercentType4 = $percentUserCatWise;
			}
		}
		$params	 = ['userId' => $user, 'amount' => $amount, 'usage' => $defMaxPercentType1];
		$sql	 = "SELECT MIN(ucr_validity) as minValidity, MIN(ucr_created) createDate,
								ucr_maxuse_type, ucr_id, ucr_max_use, 
								LEAST((CASE
										WHEN ucr_maxuse_type = 1 THEN LEAST(ROUND(:amount * :usage),$maxallowablePromoUsage)
										WHEN ucr_maxuse_type = 2 THEN MAX(maxApplicable)
										WHEN ucr_maxuse_type = 3 THEN SUM(maxApplicable)
										WHEN ucr_maxuse_type = 4 THEN ROUND(:amount * {$defMaxPercentType4})
										ELSE 0
									END), IF(ucr_maxuse_type=2, MAX(maxApplicable), SUM(maxApplicable)), :amount) as totalMaxApplicable,
								SUM(ucr_remaining) AS totalAvailable
							FROM (
								SELECT *, (ucr_value - ucr_used) ucr_remaining,
									(CASE
										WHEN ucr_maxuse_type = 1 THEN  (ucr_value - ucr_used)
										WHEN ucr_maxuse_type = 2 THEN  LEAST(ROUND(ucr_value * {$defMaxPercentType2}),  (ucr_value - ucr_used))
										WHEN ucr_maxuse_type = 3 THEN  (ucr_value - ucr_used)
										WHEN ucr_maxuse_type = 4 THEN  (ucr_value - ucr_used)
										ELSE 0
									END)  maxApplicable
								FROM `user_credits` 
								WHERE `ucr_user_id` = :userId AND ucr_status = 1 AND ucr_type <> 2 AND (ucr_validity > NOW() OR ucr_validity IS NULL)
								HAVING ucr_remaining > 0
							) a 
							GROUP BY ucr_maxuse_type 
							HAVING totalMaxApplicable > 0 
							ORDER BY totalMaxApplicable DESC, minValidity ASC, createDate ASC";
		Logger::trace($sql);
		return DBUtil::queryRow($sql, DBUtil::SDB(), $params);
	}

	/**
	 * 
	 * @param integer $value
	 * @param integer $usePromo
	 * @param integer $ucrMaxType
	 * @param integer $user
	 * @return integer
	 */
	public static function applyCredits($value, $user, $usePromo = true, $ucrMaxType = 1)
	{
		$params = ['maxType' => $ucrMaxType, 'userId' => $user];
		if ($usePromo)
		{
			$cond = " AND  ucr_type<>2";
		}
		else
		{
			$cond = " AND ucr_type=2";
		}
		$sql		 = "SELECT ucr_id
				FROM `user_credits`
				WHERE ucr_maxuse_type = :maxType 
				AND ucr_user_id = :userId 
				$cond AND (ucr_value - ucr_used > 0)  
				AND ucr_status = 1 
				AND(ucr_validity > NOW() OR ucr_validity IS NULL)
				ORDER BY ucr_validity ASC, ucr_created ASC";
		$result		 = DBUtil::query($sql, DBUtil::MDB(), $params);
		$applyCoins	 = $value;
		foreach ($result as $row)
		{
			$coinsUsed	 = self::useCredits($row["ucr_id"], $value);
			$value		 -= $coinsUsed;
			if ($value <= 0)
			{
				break;
			}
		}
		$coinsApplied = $applyCoins - $value;
		return $coinsApplied;
	}

	/**
	 * 
	 * @param integer $ucrId
	 * @param integer $value
	 * @return integer
	 * @throws Exception
	 */
	public static function useCredits($ucrId, $value)
	{
		if ($value <= 0)
		{
			return 0;
		}
		$model = UserCredits::model()->findByPk($ucrId);

		$coins = min([$value, $model->ucr_value - $model->ucr_used]);

		if ($model->ucr_type == 2)
		{
			$coins = min([round($model->ucr_value * 0.5), $coins]);
		}
		$model->ucr_used += $coins;
		if (!$model->save())
		{
			throw new Exception("Unable to save credits", ReturnSet::ERROR_FAILED);
		}
		return $coins;
	}

	/**
	 * 
	 * @param integer $user
	 * @param integer $amount
	 * @param integer $usePromo
	 * @param integer $fromCityId
	 * @param integer $toCityId
	 * @return array
	 */
	public static function getApplicableCredits($user, $amount, $usePromo = true, $fromCityId, $toCityId)
	{

		$isPromoApplicable = BookingSub::model()->getApplicable($fromCityId, $toCityId, 2);

		Logger::trace("$user, $amount, $usePromo, $fromCityId, $toCityId == $isPromoApplicable");
		$totalRefundCoins	 = $totalPromoCoins	 = 0;
		$promoUsed			 = false;
		if ($amount > 0 && $user > 0)
		{
			$totalRefundCoins	 = self::getMaxApplicableRefundCredits($user, $amount);
			$totalPromoCoins	 = 0;
			if ($usePromo && $isPromoApplicable)
			{
				$row = self::getMaxApplicablePromoCredits($user, $amount);
				if ($row)
				{
					$totalPromoCoins = max([$row['totalMaxApplicable'], 0]);
				}
				$promoUsed = ($totalPromoCoins > 0);
			}
		}
		$totCoinsUsed		 = $totalRefundCoins + $totalPromoCoins;
		$totalCoinsApplied	 = min([$amount, $totCoinsUsed]);
		return ["credits" => $totalCoinsApplied, "promoUsed" => $promoUsed, "refundCredits" => $totalRefundCoins];
	}

	public function getMaxCreditsByUser($user, $amount, $usePromo = true)
	{
		$data	 = [];
		$sql	 = "SELECT ucr_id,leastVal as CreditVal FROM (
         SELECT ucr_maxuse_type,ucr_id,least(if(ucr_max_use IS NULL,creditVal,ucr_max_use),ucr_remaining,creditVal) leastVal, ucr_remaining,ucr_max_use,creditVal,ucr_validity from 
        (SELECT *, (ucr_value-ucr_used) ucr_remaining,
        (CASE 
        when ucr_maxuse_type =1 then ROUND($amount*0.12) 
        when ucr_maxuse_type =2 then ROUND(ucr_value*0.5) 
        when ucr_maxuse_type =3 then ucr_value 
        when ucr_maxuse_type =4 then ROUND($amount*0.07)  else 0 end ) creditVal 
        FROM `user_credits` WHERE `ucr_user_id` = $user  AND ucr_status=1 AND ucr_type=2 AND  (ucr_validity>NOW() OR ucr_validity IS NULL )  HAVING ucr_remaining>0) a 
                    having leastVal>0 ORDER BY  leastVal DESC,ucr_validity ASC) b";
		$data1	 = self::model()->findAllBySql($sql);
		if ($data1 != '' && count($data1) > 0)
		{
			$data = $data1;
		}

		if ($usePromo)
		{
			$sql1	 = " SELECT t1.ucr_id ucr_id,t1.leastVal CreditVal FROM ( SELECT ucr_maxuse_type,ucr_id,least(if(ucr_max_use IS NULL,creditVal,ucr_max_use),ucr_remaining,creditVal) leastVal,ucr_validity from 
        (SELECT *, (ucr_value-ucr_used) ucr_remaining,
        (CASE 
        when ucr_maxuse_type =1 then ROUND($amount*0.12) 
        when ucr_maxuse_type =2 then ROUND(ucr_value*0.5) 
        when ucr_maxuse_type =3 then ucr_value 
        when ucr_maxuse_type =4 then ROUND($amount*0.07)  else 0 end ) creditVal 
        FROM `user_credits` WHERE `ucr_user_id` = $user  AND ucr_status=1 AND ucr_type<>2 AND  (ucr_validity>NOW() OR ucr_validity IS NULL )  HAVING ucr_remaining>0) a 
                   having leastVal>0 ORDER BY  leastVal DESC,ucr_validity ASC ) t1
 INNER JOIN ( SELECT ucr_maxuse_type,ucr_id,MAX(least(if(ucr_max_use IS NULL,creditVal,ucr_max_use),ucr_remaining,creditVal)) leastVal,ucr_validity from 
        (SELECT *, (ucr_value-ucr_used) ucr_remaining,
        (CASE 
        when ucr_maxuse_type =1 then ROUND($amount*0.12) 
        when ucr_maxuse_type =2 then ROUND(ucr_value*0.5) 
        when ucr_maxuse_type =3 then ucr_value 
        when ucr_maxuse_type =4 then ROUND($amount*0.07)  else 0 end ) creditVal 
        FROM `user_credits` WHERE `ucr_user_id` = $user  AND ucr_status=1 AND ucr_type<>2 AND  (ucr_validity>NOW() OR ucr_validity IS NULL )  HAVING ucr_remaining>0) a 
                   GROUP BY ucr_maxuse_type having leastVal>0 ORDER BY  leastVal DESC,ucr_validity ASC ) t2 GROUP BY t1.ucr_maxuse_type ORDER BY  t1.leastVal DESC,t1.ucr_validity ASC";
			$data2	 = self::model()->findAllBySql($sql1);
			if ($data2 != '' && count($data2) > 0)
			{
				$data = array_merge($data, $data2);
			}
		}

		if ($data != '' && count($data) > 0)
		{
			return $data;
		}
		return false;
	}

	public function getCreditsList($status = '1', $user = 0, $flag = 0)
	{
		if ($user > 0)
		{
			$userId		 = $user;
			$pageSize	 = 10;
		}
		else
		{
			$userId		 = Yii::app()->user->getId();
			$pageSize	 = 20;
		}
		if (trim($userId) == "" || $userId == null)
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}

		if ($status == '1')
		{
			$sql = "SELECT
 *
FROM
 (
 (
SELECT DATE_FORMAT(b.ucr_created, '%d/%m/%Y %r') AS created,
 b.ucr_created AS created1,
 b.ucr_value AS amount,
 b.ucr_desc AS description,
 0 AS ptp_id,
 ucr_type,
 (CASE WHEN ucr_type=1 THEN 'promo' WHEN ucr_type=2 THEN 'refund' WHEN ucr_type=3 THEN 'referral' WHEN ucr_type=4 THEN 'booking' WHEN ucr_type=5 THEN 'others(Admin)' WHEN ucr_type=6 THEN 'referred' WHEN ucr_type=7 THEN 'booking(CREDITS PER KM RIDDEN)' WHEN ucr_type=8 THEN 'booking(CREDITS EQUALS COD AMOUNT)' WHEN ucr_type=9 THEN 'notification' ELSE 'promo' END) AS ucr_type_desc,
 b.ucr_maxuse_type,
 b.ucr_max_use,
 b.ucr_validity,   
 ucr_status AS STATUS,
ucr_user_id
FROM
 `user_credits` b
WHERE
 `ucr_user_id` = $userId AND `ucr_status` = 1
) UNION
 (
SELECT DATE_FORMAT(b.act_date, '%d/%m/%Y %r') AS created,
 b.act_date AS created1,
 b.act_amount AS amount, CONCAT(
 'Credits applied for Booking ID: ',
 booking.bkg_booking_id
) AS description, IF(a.adt_ledger_id = 36, 5, 0) AS ptp_id,
 4 AS ucr_type,
 'Booking' AS ucr_type_desc,
 0 AS ucr_maxuse_type,
 0 AS ucr_max_use,
 0 AS ucr_validity,
 adt_status AS STATUS,
bkg_user_id ucr_user_id
FROM
 account_trans_details a
 JOIN account_transactions b ON
 b.act_id = a.adt_trans_id AND b.act_active = 1 AND b.act_status = 1
LEFT JOIN booking ON booking.bkg_id = b.act_ref_id AND act_type = 1
 JOIN booking_invoice ON booking_invoice.biv_bkg_id = booking.bkg_id
 JOIN booking_user ON booking_user.bui_bkg_id = booking.bkg_id
WHERE
 a.adt_status = 1 AND a.adt_active = 1 AND a.adt_ledger_id = 36 AND booking.bkg_id IS NOT NULL AND booking_user.bkg_user_id = $userId
)
) a
ORDER BY
 created1 DESC";
		}
		else if ($status == '2')
		{
			$sql = "SELECT * FROM ((SELECT DATE_FORMAT(b.ucr_created,'%d/%m/%Y %r') as created,b.ucr_created as created1,b.ucr_value as amount,b.ucr_desc as description,0 as ptp_id,ucr_type,
				(CASE WHEN ucr_type=1 THEN 'promo' WHEN ucr_type=2 THEN 'refund' WHEN ucr_type=3 THEN 'referral' WHEN ucr_type=4 THEN 'booking' WHEN ucr_type=5 THEN 'others(Admin)' WHEN ucr_type=6 THEN 'referred' WHEN ucr_type=7 THEN 'booking(CREDITS PER KM RIDDEN)' WHEN ucr_type=8 THEN 'booking(CREDITS EQUALS COD AMOUNT)' WHEN ucr_type=9 THEN 'notification' ELSE 'promo' END) AS ucr_type_desc,
				b.ucr_maxuse_type,b.ucr_max_use,ucr_status as status,ucr_user_id  
                FROM `user_credits` b WHERE `ucr_user_id`='" . $userId . "' AND `ucr_status`='" . $status . "')) a order by created1 DESC";
		}

		if ($flag == 1)
		{
			$recordSet = DBUtil::command($sql, DBUtil::SDB())->setFetchMode(PDO::FETCH_OBJ)->queryAll();
			return $recordSet;
		}
		else
		{
			$recordSet = DBUtil::queryAll($sql);
		}

		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataProvider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['created', 'amount', 'description', 'ucr_type'],
				'defaultOrder'	 => 'created ASC'], 'pagination'	 => ['pageSize' => $pageSize],
		]);
		$totalAmount	 = 0;
		if (count($recordSet) > 0)
		{
			foreach ($recordSet as $rec)
			{
				if ($rec['ptp_id'] == 0)
				{
					$totalAmount = ($totalAmount + $rec['amount']);
				}
				else if ($rec['ptp_id'] == 5)
				{
					$totalAmount = ($totalAmount - $rec['amount']);
				}
			}
		}
		$data['dataProvider']	 = $dataProvider;
		$data['totalAmount']	 = $totalAmount;
		$data['recordSet']		 = $recordSet;
		$data['count']			 = $count;

		return $data;
	}

	public function getTotalActiveCredits($user = 0)
	{
		if ($user > 0)
		{
			$userId		 = $user;
			$pageSize	 = 10;
		}
		else
		{
			$userId		 = Yii::app()->user->getId();
			$pageSize	 = 20;
		}
//					$sql = "SELECT
//							*
//						   FROM
//							(
//							(
//						   SELECT DATE_FORMAT(b.ucr_created, '%d/%m/%Y %r') AS created,
//							b.ucr_created AS created1,
//							b.ucr_value AS amount,
//							b.ucr_desc AS description,
//							0 AS ptp_id,
//							ucr_type,
//							(CASE WHEN ucr_type=1 THEN 'promo' WHEN ucr_type=2 THEN 'refund' WHEN ucr_type=3 THEN 'referral' WHEN ucr_type=4 THEN 'booking' WHEN ucr_type=5 THEN 'others(Admin)' WHEN ucr_type=6 THEN 'referred' WHEN ucr_type=7 THEN 'booking(CREDITS PER KM RIDDEN)' WHEN ucr_type=8 THEN 'booking(CREDITS EQUALS COD AMOUNT)' WHEN ucr_type=9 THEN 'notification' ELSE 'promo' END) AS ucr_type_desc,
//							b.ucr_maxuse_type,
//							b.ucr_max_use,
//							b.ucr_validity,   
//							ucr_status AS STATUS
//						   FROM
//							`user_credits` b
//						   WHERE
//							`ucr_user_id` = $userId AND `ucr_status` = 1 AND (`ucr_validity`> NOW() OR `ucr_validity` IS NULL) 
//						   ) UNION
//							(
//						   SELECT DATE_FORMAT(b.act_date, '%d/%m/%Y %r') AS created,
//							b.act_date AS created1,
//							b.act_amount AS amount, CONCAT(
//							'Credits applied for Booking ID: ',
//							booking.bkg_booking_id
//						   ) AS description, IF(a.adt_ledger_id = 36, 5, 0) AS ptp_id,
//							4 AS ucr_type,
//							'Booking' AS ucr_type_desc,
//							0 AS ucr_maxuse_type,
//							0 AS ucr_max_use,
//							0 AS ucr_validity,
//							adt_status AS STATUS
//						   FROM
//							account_trans_details a
//						   LEFT JOIN account_transactions b ON
//							b.act_id = a.adt_trans_id AND b.act_active = 1 AND b.act_status = 1
//						   LEFT JOIN booking ON booking.bkg_id = b.act_ref_id AND act_type = 1
//						   LEFT JOIN booking_invoice ON booking_invoice.biv_bkg_id = booking.bkg_id
//						   LEFT JOIN booking_user ON booking_user.bui_bkg_id = booking.bkg_id
//						   WHERE
//							a.adt_status = 1 AND a.adt_active = 1 AND a.adt_ledger_id = 36 AND booking.bkg_id IS NOT NULL AND booking_user.bkg_user_id = $userId
//						   )
//						   ) a
//						   ORDER BY
//							created1 DESC";
//		$recordSet		 = DBUtil::queryAll($sql);
//		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
//
//		$totalAmount	 = 0;
//		if (count($recordSet) > 0)
//		{
//			foreach ($recordSet as $rec)
//			{
//				if ($rec['ptp_id'] == 0)
//				{
//					$totalAmount = ($totalAmount + $rec['amount']);
//				}
//				else if ($rec['ptp_id'] == 5)
//				{
//					$totalAmount = ($totalAmount - $rec['amount']);
//				}
//			}
//		}
		$sql				 = "SELECT SUM(ucr_value-ucr_used) FROM user_credits WHERE ucr_user_id = $userId AND ucr_status = 1 AND (ucr_validity> NOW() OR ucr_validity IS NULL)";
		$recordSet			 = DBUtil::command($sql)->queryScalar();
		$data['totalAmount'] = ($recordSet == '' || $recordSet == null) ? 0 : $recordSet;
		//$data['count']			 = $count;
		return $data['totalAmount'];
	}

	public function addCreditsBookingKm($totalkm, $user, $bookingId)
	{
		// user get credit point equals to km riding on each booking for each user.
		$model = UserCredits::model()->find('ucr_type=:type AND ucr_ref_id=:bookingId', ['type' => 7, 'bookingId' => $bookingId]);
		if ($model == '' || $model == null)
		{
			$model = new UserCredits();
		}
		$model->ucr_user_id	 = $user;
		$model->ucr_value	 = $totalkm;
		$model->ucr_desc	 = 'BOOKING CREDITS EQUALS KILOMETERS RIDDEN';
		$model->ucr_type	 = 7;
		$model->ucr_status	 = 2;
		$model->ucr_validity = date('Y-m-d H:i:s', strtotime('+1 years'));
		$model->ucr_ref_id	 = $bookingId;
		return $model->save();
	}

	public function add($user_id, $amount, $remarks, $refId = 0)
	{
		$this->ucr_user_id		 = $user_id;
		$this->ucr_value		 = (int) $amount;
		$this->ucr_desc			 = $remarks;
		$this->ucr_type			 = 1;
		$this->ucr_maxuse_type	 = 3;
		$this->ucr_status		 = 1;
		$this->ucr_ref_id		 = $refId;
		$this->ucr_validity		 = new CDbExpression("DATE_ADD(NOW(), INTERVAL 3 MONTH)");
		$this->save();
	}

	public function addGozocoinsByPromo($userId, $bkgId, $coins)
	{
		$model					 = new UserCredits();
		$model->ucr_user_id		 = $userId;
		$model->ucr_value		 = $coins;
		$model->ucr_desc		 = 'CREDITS AGAINST PROMO';
		$model->ucr_type		 = 1;
		$model->ucr_maxuse_type	 = 1;
		$model->ucr_status		 = 2;
		$model->ucr_validity	 = date('Y-m-d H:i:s', strtotime('+1 years'));
		$model->ucr_ref_id		 = $bkgId;
		$model->save();
	}

	public function addCashback($user_id, $bkg_id, $amount)
	{
		$this->ucr_user_id		 = $user_id;
		$this->ucr_value		 = $amount;
		$this->ucr_desc			 = 'CASH BACK AGAINST ADVANCE PAYMENT';
		$this->ucr_type			 = 1;
		$this->ucr_maxuse_type	 = 1; //Yii::app()->params['creditMaxUseType']; //3;
		$this->ucr_status		 = 2;
		$this->ucr_ref_id		 = $bkg_id;
		$this->ucr_validity		 = new CDbExpression("DATE_ADD(NOW(), INTERVAL 1 YEAR)");
		$this->save();
	}

	public function addGozocoins($user_id, $bkg_id, $amount)
	{
		$this->ucr_user_id		 = $user_id;
		$this->ucr_value		 = abs($amount);
		$this->ucr_desc			 = 'GOZOCOINS ADDED AGAINST REFUND';
		$this->ucr_type			 = 2;
		$this->ucr_maxuse_type	 = 3; //Yii::app()->params['creditMaxUseType']; //3;
		$this->ucr_status		 = 1;
		$this->ucr_ref_id		 = $bkg_id;
		$this->ucr_validity		 = new CDbExpression("DATE_ADD(NOW(), INTERVAL 1 YEAR)");
		$this->save();
	}

	/**
	 * 
	 * @param type $userId
	 * @param type $type
	 * @param type $amount
	 * @param type $remarks
	 * @param type $maxUseType
	 * @param type $refId
	 * @param type $validity
	 * @throws Exception
	 */
	public function creditGozoCoins($userId, $type, $amount, $remarks, $maxUseType, $refId, $validity)
	{
		$returnSet				 = new ReturnSet();
		$this->ucr_user_id		 = $userId;
		$this->ucr_value		 = abs($amount);
		$this->ucr_desc			 = $remarks;
		$this->ucr_type			 = $type;
		$this->ucr_maxuse_type	 = $maxUseType;
		$this->ucr_status		 = 1;
		$this->ucr_validity		 = $validity;
		$this->ucr_ref_id		 = $refId;
		try
		{
			if (!$this->save())
			{
				$returnSet->setErrors($this->getErrors(), 1);
				throw new Exception("Unable to update consumer credis.", ReturnSet::ERROR_FAILED);
			}
			$returnSet->setStatus(true);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function activateCreditsBookingKm($bookingId)
	{
		$model				 = UserCredits::model()->find('ucr_type=:type AND ucr_ref_id=:bookingId', ['type' => 7, 'bookingId' => $bookingId]);
		$model->ucr_status	 = 1;
		return $model->save();
	}

	public function activateOnBookingCompleted($bookingId, $userId)
	{
		$success	 = false;
		$modelarr	 = UserCredits::model()->resetScope()->findAll('ucr_type IN(1,2) AND ucr_ref_id=:bookingId AND ucr_user_id=:userId AND ucr_status=2', ['bookingId' => $bookingId, 'userId' => $userId]);
		foreach ($modelarr as $model)
		{
			$model->ucr_status	 = 1;
			$success			 = $model->save();
		}
		return $success;
	}

	public function addCreditsBookingCOD($codAmount, $user, $bookingId)
	{
		// user get credit point equals to km riding on each booking for each user.
		$model = UserCredits::model()->find('ucr_type=:type AND ucr_ref_id=:bookingId', ['type' => 8, 'bookingId' => $bookingId]);
		if ($model == '' || $model == null)
		{
			$model = new UserCredits();
		}
		$model->ucr_user_id	 = $user;
		$model->ucr_value	 = $codAmount;
		$model->ucr_desc	 = 'BOOKING CREDITS EQUALS COD AMOUNT';
		$model->ucr_type	 = 8;
		$model->ucr_status	 = 1;
		$model->ucr_validity = date('Y-m-d H:i:s', strtotime('+1 years'));
		$model->ucr_ref_id	 = $bookingId;
		return $model->save();
	}

	public function addCreditsForNotification($userId, $ntfId, $coinValue)
	{
		$unfModel = UserNotification::model()->findByUserAndNtf($userId, $ntfId);
		if ($unfModel->unf_credit_status == 0)
		{
			$model					 = new UserCredits();
			$model->ucr_user_id		 = $userId;
			$model->ucr_value		 = $coinValue;
			$model->ucr_desc		 = 'GOZO COINS ADDED AGAINST NOTIFICATION';
			$model->ucr_type		 = 9;
			$model->ucr_maxuse_type	 = 1;
			$model->ucr_validity	 = date('Y-m-d H:i:s', strtotime('+1 years'));
			$model->ucr_status		 = 1;
			if ($model->save())
			{
				$unfModel->unf_credit_status = 1;
				$unfModel->save();
				return true;
			}
		}
		else
		{
			return false;
		}
	}

	public function setCreditBookinginfo($bkgid, $creditmasusertype, $creditModel = NULL, $discountArr, $payment = false)
	{
		$bkgmodel		 = Booking::model()->findByPk($bkgid);
		$bkgpromocode	 = $bkgmodel->bkgInvoice->bkg_promo1_code;
		$bkguserid		 = $bkgmodel->bkgUserInfo->bkg_user_id;
		//$discount		 = Promotions::model()->getDiscount($bkgmodel->bkg_id, trim($bkgpromocode));
		//$discountArr	 = Promos::model()->applyPromoCode($bkgpromocode, $bkgmodel->bkg_pickup_date, $bkgmodel->bkg_create_date, $bkgmodel->bkg_from_city_id, $bkgmodel->bkg_to_city_id, $bkguserid, $bkgmodel->bkgInvoice->bkg_base_amount, $bkgmodel->bkgTrail->bkg_platform);
		//$promoModel		 = Promos::model()->getByCode($bkgpromocode);
		if ($creditModel == "" || $creditModel == NULL)
		{
			$creditModel = new UserCredits();
		}
		if ($discountArr != false)
		{
			if ($discountArr['coins'] > 0 && ($discountArr['prm_activate_on'] != 1 || $payment))
			{
				if ($discountArr['pcn_type'] == 2 || $discountArr['pcn_type'] == 3)
				{
					//  $this->bkg_discount_amount = 0;
					$creditModel->ucr_user_id		 = $bkguserid;
					$creditModel->ucr_value			 = $discountArr['coins'];
					$creditModel->ucr_desc			 = 'CREDITS AGAINST PROMO';
					$creditModel->ucr_type			 = 1;
					$creditModel->ucr_maxuse_type	 = 1;
					$creditModel->ucr_status		 = 2;
					$creditModel->ucr_validity		 = date('Y-m-d H:i:s', strtotime('+1 years'));
					$creditModel->ucr_max_use		 = $creditModel->ucr_value;
					$creditModel->ucr_ref_id		 = $bkgid;
					$creditModel->save();
				}
			}
		}
	}

	/**
	 * @deprecated since version number
	 * @param BookingInvoice $model
	 * @param boolean $isPayment
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function setCreditAgainstPromo(BookingInvoice $model, $isPayment = false)
	{
		$returnSet = new ReturnSet();
		try
		{
			if (!$model->bivBkg->bkgUserInfo->bkg_user_id)
			{
				throw new Exception("User unavailable.", ReturnSet::ERROR_INVALID_DATA);
			}
			$isPromoValidate = ($model->bkg_promo1_coins > 0 && $model->bivPromos->prm_activate_on != 1 && in_array($model->bivPromos->prmCal->pcn_type, [2, 3])) ? true : false;
			if ($isPromoValidate || $isPayment)
			{
				UserCredits::model()->addGozocoinsByPromo($model->bivBkg->bkgUserInfo->bkg_user_id, $model->bivBkg->bkg_id, $model->bkg_promo1_coins);
				$returnSet->setStatus(true);
			}
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function addCreditsForwinaday($userId, $randum_coin)
	{
		//check fb or google user or not
		//$userId = 80090;
		/* $sql ="SELECT * FROM ". Yii::app()->db->tablePrefix ."user_oauth WHERE user_id=".$userId."";

		  $userData	 = DBUtil::queryAll($sql);

		  if(empty($userData))
		  {
		  goto end;
		  }
		 */
		$insert			 = true;
		$ucr_type		 = 1;
		$ucr_maxuse_type = 1;
		$expire			 = date('Y-m-d H:i:s', strtotime('+12 months'));
		$sql			 = "SELECT ucr_created FROM user_credits WHERE ucr_type=" . $ucr_type . " AND ucr_user_id =" . $userId . " ORDER BY ucr_id DESC LIMIT 0,1 ";
		$creditData		 = DBUtil::queryAll($sql);

		if (!empty($creditData))
		{
			//check message created within the month or not
			$ucr_created	 = explode('-', $creditData[0]['ucr_created']);
			$credit_month	 = $ucr_created[1];
			$current_month	 = date('m');
			if ($credit_month == $current_month)
			{
				$insert = false;
			}
		}

		if ($insert == true)
		{

			$model					 = new UserCredits();
			$model->ucr_user_id		 = $userId;
			$model->ucr_value		 = $randum_coin;
			$model->ucr_desc		 = 'GOZOCOINS FOR WIN A DAY';
			$model->ucr_type		 = 1;
			$model->ucr_maxuse_type	 = 1;
			$model->ucr_validity	 = date('Y-m-d H:i:s', strtotime('+1 years'));
			$model->ucr_status		 = 1;
			if ($model->save())
			{
				return true;
			}
		}
		//end:
	}

	public static function checkDuplicateCashbackStatus($bookingId, $userId, $amount)
	{
		$sql	 = "SELECT COUNT(1) FROM user_credits WHERE ucr_user_id=$userId AND ucr_ref_id=$bookingId AND ucr_value=$amount AND ucr_type=1";
		$result	 = DBUtil::command($sql)->queryScalar();
		if ($result == 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function getGozocoinsUsesStatus($userId)
	{
		$params	 = ["userId" => $userId];
		$sql	 = "SELECT  
					IF(SUM(IF(user_credits.ucr_value-user_credits.ucr_used > 0,1,0))>0,1,0) AS status
				FROM 
				user_credits 
				WHERE 
				user_credits.ucr_type=2 AND user_credits.ucr_status=1 AND user_credits.ucr_user_id=:userId
				AND (user_credits.ucr_validity >= NOW() OR user_credits.ucr_validity IS NULL)";

		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}

	public function getUserCredits($userId, $status)
	{
		$this->ucr_user_id	 = $userId;
		$type				 = (($status) ? 1 : 2);
		$data				 = $this->getCreditsList($type, $userId);
		foreach ($data['recordSet'] as $key => $value)
		{
			unset($data['recordSet'][$key]['ucr_maxuse_type']);
			unset($data['recordSet'][$key]['STATUS']);
			unset($data['recordSet'][$key]['status']);
			$data['recordSet'][$key]['created_date'] = date('Y-m-d', strtotime($data['recordSet'][$key]['created1']));
			$data['recordSet'][$key]['created_time'] = date('H:i:s', strtotime($data['recordSet'][$key]['created1']));
			settype($data['recordSet'][$key]['amount'], "integer");
			settype($data['recordSet'][$key]['ptp_id'], "integer");
			settype($data['recordSet'][$key]['ucr_type'], "integer");
			settype($data['recordSet'][$key]['ucr_max_use'], "integer");
			settype($data['recordSet'][$key]['ucr_validity'], "integer");

			unset($data['recordSet'][$key]['created1']);
			unset($data['recordSet'][$key]['created']);
		}
		$totalAmount = $this->getTotalActiveCredits($userId);
		$result		 = array(
			'count'			 => (int) $data['count'],
			'recordSet'		 => $data['recordSet'],
			'totalAmount'	 => (int) $totalAmount
		);
		return $result;
	}

	/**
	 * 
	 * @param Booking $model
	 * @return integer
	 */
	public static function getMaxCredits($netBaseAmount, $userId)
	{
		$maxCredits	 = 0;
		$arrCredits	 = self::model()->getMaxCreditsByUser($userId, $netBaseAmount);
		if ($arrCredits && is_array($arrCredits))
		{
			$creditUsed	 = $arrCredits[0]->CreditVal;
			$maxCredits	 = min([$creditUsed, $netBaseAmount]);
		}
		return $maxCredits;
	}

	/**
	 * 
	 * @param Booking $model
	 * @return bkgInvoice
	 */
	public static function removeCoins($model)
	{
		$model->bkgInvoice->bkg_temp_credits = 0;
		$model->bkgInvoice->calculateTotal();
		return $model->bkgInvoice;
	}

	/**
	 * 
	 * @param integer $walletAmount
	 * @return integer
	 */
	public static function getMinWalletBalance($walletAmount)
	{
		return min($walletAmount, Yii::app()->params['minWalletAmount']);
	}

	/**
	 * 
	 * @param Booking $model
	 * @return bkgInvoice
	 */
	public static function removeWallet($model)
	{
		$walletUsed									 = $model->bkgInvoice->bkg_wallet_used;
		$model->bkgInvoice->bkg_wallet_used			 = 0;
		$model->bkgInvoice->bkg_is_wallet_selected	 = 0;
		$model->bkgInvoice->save();
		//$model->bkgInvoice->bkg_advance_amount		 = ($model->bkgInvoice->bkg_advance_amount - $walletUsed);
		$model->bkgInvoice->calculateTotal();
		return $model->bkgInvoice;
	}

	/**
	 * 
	 * @param integer $userId
	 * @param integer $type
	 * @param integer $amount
	 * @param string $remarks
	 * @param integer $maxUseType
	 * @param integer $refId
	 * @param string $validity
	 * @param array $params
	 * @return AccountTransactions
	 * @throws Exception
	 */
	public static function addAmount($userId, $type, $amount, $remarks, $maxUseType, $refId, $validity, $params = null)
	{
		$creditModel					 = new UserCredits();
		$creditModel->ucr_user_id		 = $userId;
		$creditModel->ucr_value			 = $amount;
		$creditModel->ucr_desc			 = $remarks;
		$creditModel->ucr_type			 = $type;
		$creditModel->ucr_maxuse_type	 = $maxUseType;
		$creditModel->ucr_status		 = 1;
		$creditModel->ucr_max_use		 = $creditModel->ucr_value;
		$creditModel->ucr_validity		 = $validity;
		$creditModel->ucr_ref_id		 = $refId;
		if (!$creditModel->save())
		{
			throw new Exception("Unable to update consumer credis.", ReturnSet::ERROR_FAILED);
		}
		switch ($type)
		{
			case 2:
			case 4:
				$ledgerId	 = Accounting::LI_BOOKING;
				break;
			case 3:
				$ledgerId	 = Accounting::LI_JOINING_BONUS;
				break;
			case 1:
			default:
				$ledgerId	 = Accounting::LI_PROMOTIONS_MARKETING;
				break;
		}
		$transModel = AccountTransactions::addGozoCoins($creditModel->ucr_created, $amount, $ledgerId, $refId, $userId, $remarks);
		if (!$transModel)
		{
			throw new Exception("Unable to add Gozo Coins", ReturnSet::ERROR_FAILED);
		}
		return $transModel;
	}

	/**
	 * 
	 * @param integer $userId
	 * @param integer $bkgId
	 * @param integer $promoCoins
	 * @return boolean
	 */
	public static function validatePromoCoins($userId, $bkgId, $promoCoins)
	{
		$sqlParams	 = ['usrId' => $userId, 'bookingId' => $bkgId, 'promoCoins' => $promoCoins];
		$sql		 = "SELECT
						COUNT(1) as cnt
						FROM `user_credits`
						WHERE user_credits.ucr_user_id = :usrId 
						AND user_credits.ucr_value = :promoCoins 
						AND user_credits.ucr_ref_id = :bookingId AND user_credits.ucr_type IN (1,4)";
		return DBUtil::command($sql, DBUtil::SDB())->queryScalar($sqlParams);
	}

	/**
	 * 
	 * @param int $userId
	 * @param int $type
	 * @param email $email
	 * @param int $amount
	 * @param int $refId
	 * @param string $remarks
	 */
	public static function addCoins($userId, $type, $email, $amount, $refId = null, $remarks = null, $maxUseType = null, $validity = null)
	{
		if ($remarks == null)
		{
			$remarks = "Bonus Added for " . $email;
		}
		if ($validity == null)
		{
			$validity = date('Y-m-d H:i:s', strtotime('+1 years'));
		}
		if ($maxUseType == null)
		{
			$maxUseType = Yii::app()->params['creditMaxUseType'];
		}
		return self::addAmount($userId, $type, $amount, $remarks, $maxUseType, $refId, $validity);
	}

	/**
	 * 
	 * @param integer $amount
	 * @param integer $userId
	 * @param integer $refId
	 * @param array $extraParams
	 * @return AccountTransactions
	 */
	public static function addReferralCoins($amount, $userId, $refId, $extraParams = null)
	{
		$email		 = Users::model()->findByPk($refId)->usr_email;
		$validity	 = date('Y-m-d H:i:s', strtotime('+1 years'));
		$maxUseType	 = Yii::app()->params['creditMaxUseType'];
		return self::addAmount($userId, 3, $amount, "Referral Bonus Added for $email", $maxUseType, $refId, $validity, $extraParams);
	}

	public static function appliedUserCredit($model, $arr1)
	{
		$model->usr_referred_code = trim($arr1['usr_referred_code']);
		if ($model->usr_referred_code != '')
		{
			$userModel = Users::model()->getByReferCode($model->usr_referred_code);
			if ($userModel != '')
			{
				//credits to invited
				$creditModel					 = new UserCredits();
				$creditModel->ucr_user_id		 = $model->user_id;
				$creditModel->ucr_value			 = Yii::app()->params['invitedAmount'];
				$creditModel->ucr_desc			 = 'JOINING CREDIT';
				$creditModel->ucr_type			 = 6;
				$creditModel->ucr_maxuse_type	 = Yii::app()->params['creditMaxUseType']; //3;
				$creditModel->ucr_status		 = 1;
				$creditModel->ucr_max_use		 = $creditModel->ucr_value;
				$creditModel->ucr_validity		 = date('Y-m-d H:i:s', strtotime('+1 years'));
				$creditModel->ucr_ref_id		 = $userModel->user_id;
				if ($creditModel->save())
				{
					Yii::app()->request->cookies['gozo_referred_code']	 = new CHttpCookie('gozo_referred_code', $model->usr_referred_code);
					$statusreferral										 = true;
					$creditVal											 = $creditModel->ucr_value;
				}
				//credits to invitor
				$creditModel1					 = new UserCredits();
				$creditModel1->ucr_user_id		 = $userModel->user_id;
				$creditModel1->ucr_value		 = Yii::app()->params['inviterAmount'];
				$creditModel1->ucr_desc			 = 'Refer A Friend';
				$creditModel1->ucr_type			 = 3;
				$creditModel1->ucr_maxuse_type	 = Yii::app()->params['creditMaxUseType']; //3;
				$creditModel1->ucr_status		 = 2;
				$creditModel1->ucr_validity		 = date('Y-m-d H:i:s', strtotime('+1 years'));
				$creditModel1->ucr_max_use		 = $creditModel1->ucr_value;
				$creditModel1->ucr_ref_id		 = $model->user_id;
				$creditModel1->save();
			}
			else
			{
				$model->usr_referred_code = '';
			}
		}
		if ($model->usr_email != '' && $userModel != '')
		{
			$email = new emailWrapper();
			$email->signupReferEmail($model->user_id, $creditVal);
		}
		else if ($model->usr_email != '')
		{
			$email = new emailWrapper();
			$email->signupEmail($model->user_id);
		}
	}

	/**
	 * @deprecated use BookingInvoice::redeemGozoCoins
	 * @param integer $baseFare
	 * @param integer $useCoins
	 * @param integer $bkgId
	 * @return model
	 */
	public static function processBookingCredits($baseFare, $bkgId, $useCoins = 0, $usePromoCoins = true)
	{
		/** @model Booking */
		$model			 = Booking::model()->findByPk($bkgId);
		$coinsApplied	 = $model->bkgInvoice->redeemGozoCoins($useCoins);
		return $coinsApplied;
	}

	/**
	 * 
	 * @param integer $baseFare
	 * @param integer $userId
	 * @param integer $useCoins
	 * @param integer $refType
	 * @param integer $refId
	 * @param integer $fromCityId
	 * @param integer $toCityId
	 * @return int
	 * @throws Exception
	 */
	public static function processCredits($baseFare, $userId, $useCoins, $refType, $refId, $fromCityId = null, $toCityId = null, $usePromoCoins = true, $coinsUsed = 0)
	{
		$totalCredits	 = self::getApplicableCredits($userId, $baseFare, $usePromoCoins, $fromCityId, $toCityId);
		$applyCoins		 = min([$useCoins, $totalCredits['credits']]);
		if ($applyCoins <= 0)
		{
			throw new Exception("No minimum credits available", ReturnSet::ERROR_FAILED);
		}

		$coinsApplied = 0;
		if ($totalCredits["promoUsed"])
		{
			$row		 = self::getMaxApplicablePromoCredits($userId, $baseFare);
			$ucrMaxType	 = $row["ucr_maxuse_type"];

			$maxApplicable = max(min($row["totalMaxApplicable"] - $coinsUsed, $applyCoins), 0);

			if ($maxApplicable == 0)
			{
				goto skipPromoCoins;
			}
			$coinsApplied	 = self::applyCredits($maxApplicable, $userId, true, $ucrMaxType);
			$applyCoins		 -= $coinsApplied;
		}

		skipPromoCoins:
		if ($applyCoins > 0)
		{
			$ucrMaxType		 = 3;
			$coinsApplied1	 = self::applyCredits($applyCoins, $userId, false, $ucrMaxType);
			$applyCoins		 -= $coinsApplied1;
			$coinsApplied	 += $coinsApplied1;
		}
		if ($coinsApplied > 0)
		{
			$remarks			 = "Gozo coins used (Confirmed)";
			$additionalParams	 = '{"TRANSACTION_MODE":2,"DESCRIPTION":"Gozo coins applied","STATUS":"SUCCESS"}';
			$transModel			 = AccountTransactions::chargeGozoCoins(new CDbExpression('NOW()'), $coinsApplied, $refType, $refId, $userId, $remarks, null, $additionalParams);
			if (!$transModel)
			{
				throw new Exception("Unable to apply Gozo Coins", ReturnSet::ERROR_FAILED);
			}
		}
		return $coinsApplied;
	}
/**
	 * 
	 * @param integer $userID|0
	 * @param integer $giftCoinAmount
	 * @return array $result
	 */
	public static function getEligibleUser($userID = 0, $giftCoinAmount)
	{
		$condition	 = '';
		$params		 = [];
		if ($userID > 0)
		{
			$params		 = ["userId" => $userID];
			$condition	 = " AND user_id =:userId ";
		}
		$sql	 = "SELECT ctt_id,ctt_name,SUM(ucr_value - ucr_used) as coinHave,user_id
				FROM `contact_profile` 
				INNER JOIN contact ON cr_contact_id = ctt_id AND ctt_id = ctt_ref_code 
				INNER JOIN users ON cr_is_consumer=user_id
				INNER JOIN user_credits ON ucr_user_id = user_id AND ucr_status = 1 AND (ucr_validity> NOW() OR ucr_validity IS NULL)
				WHERE cr_is_consumer IS NOT NULL 
				AND cr_status=1 AND ctt_active=1 
				$condition
				GROUP BY ctt_id
				HAVING ( coinHave >= 0 AND coinHave < $giftCoinAmount) ";
		#$result = DBUtil::command($sql, DBUtil::SDB())->query($params);
		#Logger::writeToConsole($sql);
		$result	 = DBUtil::query($sql, DBUtil::SDB(), $params);
		return $result;
	}
	/**
	 * 
	 * @param integer $userID|0
	 * @param integer $giftCoinAmount
	 * @return array $result
	 */
	public static function getEligibleUserForRecharge($userID = 0, $giftCoinAmount)
	{
		$condition	 = '';
		$params		 = [];
		if ($userID > 0)
		{
			$params		 = ["userId" => $userID];
			$condition	 = " AND user_id =:userId ";
		}

		$sql = "SELECT SUM(ucr_value - ucr_used) as coinHave, user_id, cr_contact_id
				FROM users 
				INNER JOIN booking_user ON bkg_user_id = user_id 
				INNER JOIN contact_profile ON cr_is_consumer = user_id AND usr_active = 1 
				INNER JOIN contact ON cr_contact_id = ctt_id AND ctt_active = 1
				LEFT JOIN user_credits ON ucr_user_id = user_id AND ucr_status = 1 AND (ucr_validity> NOW() OR ucr_validity IS NULL) 
				WHERE cr_is_consumer > 0 AND cr_status = 1 AND usr_active = 1 
				$condition 
				GROUP BY ctt_ref_code 
				HAVING (coinHave IS NULL OR coinHave < {$giftCoinAmount})";

		Logger::writeToConsole($sql);

		$result = DBUtil::query($sql, DBUtil::SDB(), $params);
		return $result;
	}

	public static function getUserCoin($userID)
	{
		$params		 = ["userId" => $userID];
		$sql		 = "SELECT SUM(ucr_value - ucr_used) as coinHave FROM `user_credits` WHERE `ucr_user_id` = :userId AND ucr_status = 1 AND (ucr_validity> NOW() OR ucr_validity IS NULL) ";
		$coinHave	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
		return $coinHave;
	}

	/**
	 * This function is used to added joining bonus for QrCode Scan for each Beneficiary
	 * @param integer $amount
	 * @param integer $userId Beneficiary User Id
	 * @param integer $refId  Benefactor User Id
	 * @param array $extraParams
	 * @return AccountTransactions
	 */
	public static function addBeneficaryReferralJoiningBonus($amount, $userId, $refId, $extraParams = [])
	{
		$validity			 = new CDbExpression('DATE_ADD(CURDATE(),INTERVAL 1 YEAR)');
		$maxUseType			 = Yii::app()->params['creditMaxUseType'];
		$beneficiaryName	 = Contact::model()->getByUserId($userId)->ctt_name;
		$benefactorName		 = Contact::model()->getByUserId($refId)->ctt_name;
		$beneficiaryBkgId	 = $extraParams['beneficiaryBkgId'];
		$benefactorBkgId	 = $extraParams['benefactorBkgId'];
		// $beneficiaryName(beneficairy-$userId-BKG-ID) revieved  Rs $amount  for  referral  from $benefactorName(benfactor-$refId-BKG-ID).
		$message			 = "$beneficiaryName(BeneficairyId:$userId - BkgId:$beneficiaryBkgId) revieved  Rs $amount  for  referral  from $benefactorName(BenfactorId:$refId - BkgId:$benefactorBkgId)";
		return self::addAmount($userId, 3, $amount, $message, $maxUseType, $refId, $validity, $extraParams);
	}

	/**
	 * This function is used to added joining bonus for QrCode Scan for each Benefactor
	 * @param integer $amount
	 * @param integer $refId  Benefactor User Id
	 * @param integer $userId Beneficiary User Id
	 * @param array $extraParams
	 * @return AccountTransactions
	 */
	public static function addBenefactorReferralJoiningBonus($amount, $refId, $userId, $extraParams = [])
	{
		$validity			 = new CDbExpression('DATE_ADD(CURDATE(),INTERVAL 1 YEAR)');
		$maxUseType			 = Yii::app()->params['creditMaxUseType'];
		$beneficiaryName	 = Contact::model()->getByUserId($userId)->ctt_name;
		$benefactorName		 = Contact::model()->getByUserId($refId)->ctt_name;
		$beneficiaryBkgId	 = $extraParams['beneficiaryBkgId'];
		$benefactorBkgId	 = $extraParams['benefactorBkgId'];
		// $benefactorName(BENEFACTOR-$refId-BKG-ID) revieved Rs $amount FOR referring  $beneficiaryName(BENFICAYY-$userId-BKG-ID)
		$message			 = "$benefactorName(BenefactorId:$refId - BkgId:$benefactorBkgId) revieved Rs $amount for referring  $beneficiaryName(BeneficiaryId:$userId - BkgId:$beneficiaryBkgId)";
		return self::addAmount($refId, 3, $amount, $message, $maxUseType, $userId, $validity, $extraParams);
	}

	public static function getUserCatWiseApplicablePercent($userId)
	{
		$rowUCM = UserCategoryMaster::getByUserId($userId);
		if($rowUCM!='')
		{
			if($rowUCM['ucm_gozocoin_percent']>0)
			{
				return $rowUCM['ucm_gozocoin_percent'];
			}
		}
		return 0;
	}

}

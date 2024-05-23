<?php

/**
 * This is the model class for table "promotions".
 *
 * The followings are the available columns in table 'promotions':
 * @property integer $prm_id
 * @property string $prm_desc
 * @property integer $prm_value_type
 * @property integer $prm_type
 * @property string $prm_code
 * @property integer $prm_min
 * @property integer $prm_max
 * @property integer $prm_value
 * @property string $prm_valid_from
 * @property string $prm_valid_upto
 * @property string $prm_valid_pickup_date_from
 * @property string $prm_valid_pickup_date_to
 * @property string $prm_valid_days
 * @property integer $prm_use_max
 * @property string $prm_used_counter
 * @property integer $prm_applicable_type
 * @property string $prm_source_type
 * @property integer $prm_applicable_user_type
 * @property integer $prm_applicable_trip_type
 * @property integer $prm_next_trip_apply
 * @property integer $prm_active
 * @property string $prm_modified
 * @property string $prm_created
 * @property integer  $prm_activate_on
 * @property string $prm_log
 */
class Promotions extends CActiveRecord
{

	public $prm_valid_from_time,
			$prm_valid_from_date,
			$prm_valid_upto_date,
			$prm_valid_upto_time, $prm_validity,
			$prm_source_type_show;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'promotions';
	}

	public function defaultScope()
	{
		$arr = array(
			'condition' => "prm_active=1",
		);
		return $arr;
	}

	public static $nextTrip				 = [1 => "Next booking", 0 => "Current booking"];
	public static $applicableType		 = [1 => "Auto Apply", 0 => "Manual Apply"];
	public static $applicableUserType	 = [1 => "Particular User", 0 => "All User"];
	public static $applicableTripType	 = [1 => "Particular Trip", 0 => "All Trip"];
	public static $valueType			 = [1 => "Percentage", 2 => "Amount"];
	public static $promoType			 = [1 => "Cash", 2 => "GozoCoins", 3 => "Both"];
	public static $source_type			 = ['1' => 'User', '2' => 'Admin', '3' => 'App'];
	public static $activateOn			 = [0 => 'Immediate', 1 => 'Advance payment'];

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('prm_desc, prm_value_type, prm_code,prm_value, prm_valid_from_date, prm_valid_upto_date, prm_use_max, prm_applicable_type, prm_next_trip_apply', 'required', 'on' => 'insert,update'),
			array('prm_code', 'required', 'on' => 'incrementCounter'),
			array('prm_value_type, prm_min, prm_max, prm_use_max, prm_applicable_type, prm_applicable_user_type, prm_applicable_trip_type, prm_next_trip_apply, prm_active', 'numerical', 'integerOnly' => true),
			array('prm_value', 'numerical'),
			array('prm_desc, prm_code', 'length', 'max' => 255),
			array('prm_valid_days', 'length', 'max' => 100),
			array('prm_used_counter', 'length', 'max' => 10),
			array('prm_source_type', 'length', 'max' => 20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('prm_id, prm_desc, prm_value_type, prm_code, prm_min,prm_validity, prm_max, prm_value, prm_valid_from, prm_valid_upto, prm_use_max, prm_applicable_type, prm_applicable_user_type, prm_applicable_trip_type,prm_source_type, prm_next_trip_apply, prm_active,prm_type,prm_activate_on', 'safe'),
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
			'prm_id'					 => 'Prm',
			'prm_desc'					 => 'Promo Description',
			'prm_value_type'			 => 'Promo Value Type',
			'prm_code'					 => 'Code',
			'prm_min'					 => 'Minimum Offer Amount',
			'prm_max'					 => 'Maximum Offer Amount',
			'prm_value'					 => 'Offer Value',
			'prm_valid_from'			 => 'Valid From',
			'prm_valid_upto'			 => 'Valid Upto',
			'prm_type'					 => 'Promo Type',
			'prm_use_max'				 => 'Promo can be used how much times',
			'prm_applicable_type'		 => 'Applicable Type',
			'prm_applicable_user_type'	 => 'Applicable User Type',
			'prm_applicable_trip_type'	 => 'Applicable Trip Type',
			'prm_next_trip_apply'		 => 'Is it applicable to Next Trip',
			'prm_active'				 => 'Active',
			'prm_source_type'			 => 'Source Type',
			'prm_modified'				 => 'Modified',
			'prm_created'				 => 'Created',
			'prm_activate_on'			 => 'Activate On'
		);
	}

	public function afterFind()
	{
		parent::afterFind();
		//    $date = strtotime($this->prm_valid_from);
		//    $this->prm_valid_from = DateTimeFormat::DateToLocale($this->prm_valid_from);
	}

	public function beforeSave()
	{
		parent::beforeSave();
		$this->prm_modified = new CDbExpression('NOW()');
		if ($this->prm_valid_from_date !== null)
		{
			$validFrom				 = DateTimeFormat::DatePickerToDate($this->prm_valid_from_date);
			$validFromTime			 = date('H:i:s', strtotime($this->prm_valid_from_time));
			$this->prm_valid_from	 = $validFrom . " " . $validFromTime;
		}
		if ($this->prm_valid_upto_date !== null)
		{
			$validUpto				 = DateTimeFormat::DatePickerToDate($this->prm_valid_upto_date);
			$validUptoTime			 = date('H:i:s', strtotime($this->prm_valid_upto_time));
			$this->prm_valid_upto	 = $validUpto . " " . $validUptoTime;
		}
		return true;
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
//        $criteria->compare('prm_id', $this->prm_id);
		$criteria->compare('prm_desc', $this->prm_desc, true);
		$criteria->compare('prm_value_type', $this->prm_value_type, true);
		$criteria->compare('prm_code', $this->prm_code, true);
		$criteria->compare('prm_min', $this->prm_min, true);
		$criteria->compare('prm_max', $this->prm_max, true);
		$criteria->compare('prm_value', $this->prm_value, true);
//        $criteria->compare('prm_valid_from', $this->prm_valid_from, true);
//        $criteria->compare('prm_valid_upto', $this->prm_valid_upto, true);
//        $criteria->compare('prm_valid_pickup_date_from', $this->prm_valid_pickup_date_from, true);
//        $criteria->compare('prm_valid_pickup_date_to', $this->prm_valid_pickup_date_to, true);
//        $criteria->compare('prm_valid_days', $this->prm_valid_days, true);
//        $criteria->compare('prm_use_max', $this->prm_use_max);
//        $criteria->compare('prm_used_counter', $this->prm_used_counter, true);
		$criteria->compare('prm_applicable_type', $this->prm_applicable_type, true);

		$criteria->compare('prm_applicable_user_type', $this->prm_applicable_user_type, true);
		$criteria->compare('prm_applicable_trip_type', $this->prm_applicable_trip_type, true);
		$criteria->compare('prm_next_trip_apply', $this->prm_next_trip_apply, true);
		$criteria->compare('prm_active', $this->prm_active);
		$val = $this->prm_validity;
		if ($val[0] != '1')
		{
			$criteria->addCondition("prm_valid_upto >= '" . date('Y-m-d H:i:s') . "'");
		}
		if ($this->prm_source_type == '0')
		{
			$criteria->addCondition("prm_source_type IN (1,2,3)");
		}
		else
		{
			$criteria->compare('prm_source_type', $this->prm_source_type, true);
		}

//        $criteria->compare('prm_modified', $this->prm_modified, true);
//        $criteria->compare('prm_created', $this->prm_created, true);
//                echo "<pre>";
//                print_r($criteria);
//                exit();
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Promotions the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getPromoRules($rule)
	{
		$promoRules = [
			1	 => ['GET10OFF', 'GET50PCB'], // 10% off 50% cashback
			2	 => ['GET75PEROFF', 'GET50PCB'], // 7.5% off 50% cashback
			3	 => ['GET5PDISC', 'GET50PCB'], // 5% off 50% cashback
			4	 => ['GET2P5PDISC', 'GET25PCB'], // 2.5% off 25% cashback
		];
		return $promoRules[$rule];
	}

	public function getTncByPromoCode($code)
	{
		$terms = [
			'GET10OFF'		 => 'showTcGozoCoins1()',
			'GET75PEROFF'	 => 'showTcGozoCoins1()',
			'GET5PDISC'		 => 'showTcGozoCoins1()',
			'GET2P5PDISC'	 => 'showTcGozoCoins2p5()',
			'GET50PCB'		 => 'showTcGozoCoins2()',
			'GET25PCB'		 => 'showTcGozoCoins25()'
		];
		return $terms[$code];
	}

	public function getValueArray()
	{
		return array(
			'prm_value_type'			 => [1 => '%', 2 => 'amount'],
			'prm_type'					 => [1 => 'Cash', 2 => 'GozoCoins', 3 => 'Both'],
			'prm_applicable_type'		 => [0 => 'Manual', 1 => 'Automatic Apply'],
			'prm_applicable_user_type'	 => [0 => 'All User', 1 => 'Selected User'],
			'prm_applicable_trip_type'	 => [0 => 'alltrip', 1 => 'for particular trip'],
			'prm_next_trip_apply'		 => [0 => 'Same Trip', 1 => 'Next Trip'],
		);
	}

	public function getApplicableSources()
	{
		$src	 = str_replace(',', ', ', $this->prm_source_type);
		$srctype = strtr($src, Promos::$source_type);
		return $srctype;
	}

	public function getPromoDiscount($promocode, $user_id, $total, $pickup_date, $platform, $fromCityId, $toCityId)
	{
		$isPromo = BookingSub::model()->getApplicable($fromCityId, $toCityId, 1);
		if ($isPromo)
		{
			$condPromo	 = " AND prm_code='$promocode'";
			$cdb		 = $this->getCommand($user_id, $pickup_date, $platform, $condPromo);
			//$cdb->andWhere("prm_code='$promocode'");
			//  echo $cdb->getText();exit;
			$row = DBUtil::command($cdb)->queryRow();
			//	$row = $cdb->queryRow();
			if (!$row)
			{
				return false;
			}
			return $this->calculate($row['prm_id'], $total);
		}
		else
		{
			return false;
		}
	}

	public function getPromoDiscountById($promoid, $user_id, $total, $pickup_date, $platform, $fromCityId, $toCityId)
	{
		$isPromo = BookingSub::model()->getApplicable($fromCityId, $toCityId, 1);
		if ($isPromo)
		{
			$cdb = $this->getCommand($user_id, $pickup_date, $platform);
			$cdb->andWhere("prm_id='$promoid'");

			//  echo $cdb->getText();exit;
			$row = $cdb->queryRow();
			if (!$row)
			{
				return false;
			}
			return $this->calculate($row['prm_id'], $total);
		}
		else
		{
			return false;
		}
	}

	public function calculate($promo_id, $cartAmount)
	{
		$promoModel = self::model()->findByPk($promo_id);
		if (!$promoModel)
		{
			return false;
		}
		$min			 = $promoModel->prm_min;
		$max			 = $promoModel->prm_max;
		$type			 = $promoModel->prm_value_type;
		$offerValue		 = $promoModel->prm_value;
		$discountValue	 = 0;
		if ($type == 1)
		{
			$discountValue = $cartAmount * $offerValue / 100;
			if ($max > 0 && $discountValue > $max)
			{
				$discountValue = $max;
			}
			if ($min > 0 && ($discountValue < $min))
			{
				$discountValue = $min;
			}
		}
		if ($type == 2)
		{
			$discountValue = $offerValue;
//            $discountValue = ($discountValue >= $max) ? $max : $discountValue;
//            $discountValue = ($discountValue <= $min) ? $min : $discountValue;
		}

		return round($discountValue);
	}

	private function getCommand($user_id = '', $pickup_date = '', $source = '', $discCond = '')
	{
//		$cdb		 = Yii::app()->db->createCommand();
//		$cdb->select(["*", "IF(prm_valid_upto IS NULL, 1, 0) as valid"]);
//		$cdb->from	 = 'promotions';	
//		$cdb->where("(CURRENT_TIMESTAMP >= `prm_valid_from` OR `prm_valid_from` IS NULL) AND (`prm_valid_upto`>=CURRENT_TIMESTAMP OR `prm_valid_upto` IS NULL)");
//		$cdb->andWhere("prm_applicable_user_type=0");
//		if ($source != '')
//		{
//			$cdb->andWhere("find_in_set($source,prm_source_type)");
//		}
//		$cdb->andWhere("(prm_use_max > prm_used_counter || prm_use_max = 0)");
//		$cdb->order = "valid, prm_valid_upto";
//		if ($pickup_date != '')
//		{
//			$cdb->andWhere("('$pickup_date' >= `prm_valid_pickup_date_from` OR `prm_valid_pickup_date_from` IS NULL) AND (`prm_valid_pickup_date_to`>='$pickup_date' OR `prm_valid_pickup_date_to` IS NULL)
//                            AND (prm_valid_days IS NULL OR FIND_IN_SET(DAYOFWEEK('$pickup_date'), prm_valid_days))
//                            ");
//		}
//		if ($user_id == '' || $user_id == null || $user_id == 0)
//		{
//			$cdb->andWhere("prm_type<>2");
//		}
//		return $cdb;

		$sourceCond		 = ($source != '') ? "  AND (find_in_set($source,prm_source_type)) " : "";
		$PickupDateCond	 = ($pickup_date != '') ? "	 AND ('$pickup_date' >= `prm_valid_pickup_date_from` OR `prm_valid_pickup_date_from` IS NULL) 
				 AND (`prm_valid_pickup_date_to` >= '$pickup_date' OR `prm_valid_pickup_date_to` IS NULL)
				 AND (prm_valid_days IS NULL OR FIND_IN_SET(DAYOFWEEK('$pickup_date'),prm_valid_days)) " : "";
		$userCond		 = ($user_id == '' || $user_id == null || $user_id == 0) ? " AND prm_type NOT IN(2,3)" : "";


		if ($source == 3)
		{
			$sqlS = "SELECT
				prm_id,
				prm_code,
				prm_desc,
				IF(
				  prm_valid_upto IS NULL,
				  1,
				  0)
				  AS valid ";
		}
		else
		{
			$sqlS = "SELECT
				*,
				IF(
				  prm_valid_upto IS NULL,
				  1,
				  0)
				  AS valid ";
		}
		$sql = " $sqlS FROM
				`promotions`
			   WHERE
				(CURRENT_TIMESTAMP >= `prm_valid_from` OR `prm_valid_from` IS NULL) 
				 AND (`prm_valid_upto` >= CURRENT_TIMESTAMP OR `prm_valid_upto` IS NULL)
				 AND prm_applicable_user_type = 0 
				 AND (prm_use_max > prm_used_counter OR prm_use_max = 0) 
                 $sourceCond
				 $PickupDateCond 
				 $userCond
				 $discCond
			   ORDER BY
				`valid`,
				`prm_valid_upto`";
		return $sql;
	}

//    public function validatePromocode($user_id) {
//        $sql = "SELECT *,  FROM `promotions` WHERE ((CURRENT_TIMESTAMP BETWEEN `prm_valid_from` AND `prm_valid_upto`) OR (`prm_valid_from` IS NULL AND `prm_valid_upto` IS NULL) OR (`prm_valid_from`<= CURRENT_TIMESTAMP AND `prm_valid_upto` IS NULL) OR (`prm_valid_upto`>=CURRENT_TIMESTAMP AND `prm_valid_from` IS NULL)) AND prm_active=1 ";
//    }

	public function getAutoApplyCodes($user_id, $pickupTime = '', $source = '')
	{
		//	$cdb = $this->getCommand($user_id, $pickupTime, $source);
		//         $this->andWhere(" prm_applicable_type=1 AND prm_active=1 ");

		$discCond	 = " AND prm_applicable_type=1 AND prm_active=1 ";
		$sql		 = $this->getCommand($user_id, $pickupTime, $source, $discCond);

		return DBUtil::queryAll($sql);
	}

	public function promoListing()
	{
		$criteria			 = new CDbCriteria;
		$criteria->together	 = TRUE;
		$dataProvider		 = new CActiveDataProvider($this->together(), array('criteria' => $criteria));
		return $dataProvider;
	}

	public function getAutoApplyCode($user_id)
	{
		$rows = $this->getAutoApplyPromos($user_id);
		if (count($rows) > 0)
		{
			$row = $rows[0];
			return $row;
		}
		return null;
	}

	public function calculateAutoApplyCode($user_id, $tripTotal)
	{
		$row = $this->getAutoApplyCode($user_id);
		if ($row != null)
		{
			$amount = $this->calculate($row['prm_id'], $tripTotal);
			return $amount;
		}
		return 0;
	}

	public function incrementCounter($promocode, $userid = '')
	{
		$criteria						 = new CDbCriteria;
		$criteria->compare('prm_code', $promocode);
		$promoModel						 = $this->find($criteria);
		$promoid						 = $promoModel->prm_id;
		$promoModel->prm_used_counter	 = $promoModel->prm_used_counter + 1;
		$promoModel->scenario			 = 'incrementCounter';
		$promoModel->save();
		if ($userid > 0)
		{
			PromoUsers::model()->incrementCounter($promoid, $userid);
		}
	}

	public function validateCode($promocode, $user_id = '', $pickupDate = '')
	{
		$condPromo	 = " AND prm_code='$promocode'";
		$cdb		 = $this->getCommand($user_id, '', '', $condPromo);
		//$cdb->andWhere("prm_code='$promocode'");
		//  echo $cdb->getText();exit;
		$row		 = DBUtil::command($cdb)->queryRow();
		if ($row)
		{
			return true;
		}
		return false;
	}

	public function getListJson($user_id = '')
	{
		$cdb = $this->getCommand($user_id);

		//  echo $cdb->getText();exit;
		$result	 = DBUtil::command($cdb)->queryAll();
		$arrJSON = array();
		foreach ($result as $val)
		{
			$arrJSON[] = array("id" => $val, "text" => $val);
		}

		$data = CJSON::encode($arrJSON);
		return $data;
	}

	public function getActivePromoCode()
	{
		$cdb		 = Yii::app()->db->createCommand();
		$cdb->select(["prm_code", "prm_desc", "IF(prm_valid_upto IS NULL, 1, 0) as valid"]);
		$cdb->from	 = 'promotions';
		$cdb->where("(`prm_valid_upto`>=CURRENT_TIMESTAMP OR `prm_valid_upto` IS NULL) AND prm_active=1");
		$cdb->order	 = "valid, prm_valid_upto";
		$result		 = $cdb->queryAll();
		return $result;
	}

	public function getActivePromoCodeJson()
	{
		$result	 = $this->getActivePromoCode();
		$arrJSON = array();
		foreach ($result as $val)
		{
			$arrJSON[] = array("id" => $val['prm_code'], "text" => $val['prm_code'] . ' (' . $val['prm_desc'] . ')');
		}
		$data = CJSON::encode($arrJSON);
		return $data;
	}

	public function getByCode($code)
	{
		$criteria = new CDbCriteria;
		$criteria->compare('prm_code', $code);
		return $this->find($criteria);
	}

	public function getByPromoId($promoid)
	{
		$criteria = new CDbCriteria;
		$criteria->compare('prm_id', $promoid);
		return $this->find($criteria);
	}

	public function getDiscount($bkgid, $pcode)
	{
		$model			 = Booking::model()->findbyPk($bkgid);
		$userid			 = ($model->bkgUserInfo->bkg_user_id > 0) ? $model->bkgUserInfo->bkg_user_id : '';
		$discount		 = $this->getPromoDiscount($pcode, $userid, $model->bkgInvoice->bkg_base_amount, $model->bkg_pickup_date, $model->bkgTrail->bkg_platform, $model->bkg_from_city_id, $model->bkg_to_city_id);
		$discountAmount	 = ($discount > 0) ? $discount : 0;
		return $discountAmount;
	}

	public function getDiscountById($bkgid, $promoid)
	{
		$model			 = Booking::model()->findbyPk($bkgid);
		$userid			 = ($model->bkgUserInfo->bkg_user_id > 0) ? $model->bkgUserInfo->bkg_user_id : '';
		$discount		 = $this->getPromoDiscountById($promoid, $userid, $model->bkgInvoice->bkg_base_amount, $model->bkg_pickup_date, $model->bkgTrail->bkg_platform, $model->bkg_from_city_id, $model->bkg_to_city_id);
		$discountAmount	 = ($discount > 0) ? $discount : 0;
		return $discountAmount;
	}

	public function addLog($oldData, $newData)
	{
		if ($oldData)
		{
			$getDifference	 = array_diff_assoc($oldData, $newData);
			$remark			 = $this->prm_log;
			$dt				 = date('Y-m-d H:i:s');
			$user			 = Yii::app()->user->getId();
			//if ($remark) {
			if (is_string($remark))
			{
				$newcomm = CJSON::decode($remark);
			}
			else if (is_array($remark))
			{
				$newcomm = $remark;
			}
			if ($newcomm == false)
			{
				$newcomm = array();
			}
			if (count($getDifference) > 0)
			{
				while (count($newcomm) >= 50)
				{
					array_pop($newcomm);
				}
				array_unshift($newcomm, array(0 => $user, 1 => $dt, 2 => $getDifference));
				$log = CJSON::encode($newcomm);
				return $log;
			}
		}
		return $remark;
	}

	public function getApplicableCodes($discCond, $userId = 0, $pickupTime = '', $source = '')
	{
		$promoArr = Promotions::model()->getAutoApplyCodes($userId, $pickupTime, $source);

		foreach ($promoArr as $key => $value)
		{
			if ($source == 1)
			{
				$promoArr[$key]['prm_desc'] = $value['prm_desc'] . " *<a href='#' onclick='" . $this->getTncByPromoCode($value['prm_code']) . "'>T&C</a> Apply";
			}
			if (!in_array($value['prm_code'], $this->getPromoRules($discCond)))
			{
				unset($promoArr[$key]);
			}
			if ($source == 3)
			{
				unset($promoArr[$key]['valid']);
			}
		}

		return $promoArr;
	}

	public function getExpTimeAdvPromo($createDate, $pickTime, $app = false)
	{
		$now				 = new DateTime(date('Y-m-d H:i:s'));
		$add8hrCreateDate	 = new DateTime(date('Y-m-d H:i:s', strtotime($createDate . '+8 hour')));
		$sub24pickDate		 = new DateTime(date('Y-m-d H:i:s', strtotime($pickTime . '-24 hour')));
		$sub12pickDate		 = new DateTime(date('Y-m-d H:i:s', strtotime($pickTime . '-12 hour')));
		$sub8pickdate		 = new DateTime(date('Y-m-d H:i:s', strtotime($pickTime . '-8 hour')));
		$sub18pickdate		 = new DateTime(date('Y-m-d H:i:s', strtotime($pickTime . '-18 hour')));

		if ($app)
		{
			if ($now < $sub24pickDate)
			{
				return 2;
			}
			if ($now < $sub12pickDate)
			{
				return 3;
			}
			else
			{
				return 4;
			}
			return 0;
		}

		if ($now < $add8hrCreateDate && $now < $sub8pickdate)
		{
			return 1;
		}
		if ($now < $sub18pickdate)
		{
			return 1;
		}
		else if ($now > $sub18pickdate && $now < $sub8pickdate)
		{
			return 3;
		}
		else if ($now > $sub8pickdate)
		{
			return 0;
		}

		return 0;
	}
}

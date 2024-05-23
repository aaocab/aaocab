<?php

/**
 * This is the model class for table "user_wallet".
 *
 * The followings are the available columns in table 'user_wallet':
 * @property integer $urw_id
 * @property integer $urw_user_id
 * @property integer $urw_ref_id
 * @property integer $urw_wallet_amount
 * @property integer $urw_locked_amount
 * @property string $urw_create_date
 * @property string $urw_modified_date
 * 
 */
class UserWallet extends BaseActiveRecord
{
//	public   $urw_net_balance

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_wallet';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
		return array(
			array('urw_user_id, urw_wallet_amount', 'required'),
			array('urw_user_id, urw_wallet_amount', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
// @todo Please remove those attributes that should not be searched.
			array('urw_id, urw_user_id, urw_wallet_amount, urw_create_date, urw_modified_date, urw_ref_id,urw_locked_amount', 'safe', 'on' => 'search'),
			['urw_net_balance', 'unsafe'],
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
// NOTE: you may need to adjust the relation name and the related
// class name for the relations automatically generated below.
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'urw_id'			 => 'ID',
			'urw_user_id'		 => 'User',
			'urw_ref_id'		 => 'Reference ID',
			'urw_wallet_amount'	 => 'Wallet Amount',
			'urw_create_date'	 => 'Create Date',
			'urw_modified_date'	 => 'Modified Date',
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

		$criteria->compare('urw_id', $this->urw_id);
		$criteria->compare('urw_user_id', $this->urw_user_id);
		$criteria->compare('urw_user_id', $this->urw_ref_id);
		$criteria->compare('urw_wallet_amount', $this->urw_wallet_amount);
		$criteria->compare('urw_create_date', $this->urw_create_date, true);
		$criteria->compare('urw_modified_date', $this->urw_modified_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserWallet the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getTransHistory($userId, $ledgerId)
	{
		$params			 = ['userId' => $userId, 'ledgerId' => $ledgerId];
		$sql			 = "SELECT act.act_id,act.act_date AS created, adt.adt_amount, act.act_remarks, adt.adt_id, adt.adt_trans_id    
				FROM   account_trans_details adt  
        JOIN account_transactions act  ON act.act_id = adt.adt_trans_id AND act.act_active = 1 AND act.act_status = 1 AND adt.adt_active = 1
        where adt.adt_trans_ref_id =:userId AND adt.adt_ledger_id =:ledgerId";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['defaultOrder' => 'act_id DESC'],
			'pagination'	 => ['pageSize' => 20],
			'params'		 => $params,
		]);
		return $dataprovider;
	}

	public static function getBalance($userId = '')
	{

		$param	 = ['userID' => $userId];
//		$balance = AccountTransDetails::getWalletBalancebyUserid($userId);
		$balance = 0;
		if ($userId > 0)
		{
			$sql	 = "SELECT  (IFNULL(urw_wallet_amount, 0) -IFNULL(urw_locked_amount, 0) ) balance  FROM `user_wallet` WHERE urw_user_id=:userID";
			$balance = DBUtil::queryScalar($sql, DBUtil::MDB(), $param);
			$balance = ($balance | 0);
		}
		return $balance;
	}

	public static function add($user, $amount)
	{
		$wallet	 = UserWallet::model()->find('urw_user_id=:user', ['user' => $user]);
		$success = true;
		if (!$wallet)
		{
			$wallet						 = new UserWallet();
			$wallet->urw_user_id		 = $user;
			$wallet->urw_wallet_amount	 = 0;
			$success					 = $wallet->save();
		}
		if (!$success)
		{
			throw new Exception("Unable to add wallet amount: " . json_decode($wallet->getErrors()), ReturnSet::ERROR_FAILED);
		}
		return $success;
	}

	public static function createIfNotExist($user)
	{
		$wallet	 = UserWallet::model()->find('urw_user_id=:user', ['user' => $user]);
		$success = true;
		if (!$wallet)
		{
			$wallet						 = new UserWallet();
			$wallet->urw_user_id		 = $user;
			$wallet->urw_wallet_amount	 = 0;
			$wallet->urw_locked_amount	 = 0;
			$success					 = $wallet->save();
		}
		if (!$success)
		{
			throw new Exception("Unable to add wallet amount: " . json_decode($wallet->getErrors()), ReturnSet::ERROR_FAILED);
		}
		return $success;
	}

	/**
	 * 
	 * @param int $amount			|  bonus Amount
	 * @param int $userId			|  
	 * @param int $referralUserId	| 
	 * @param string $params		| 
	 */
	public static function addReferralAmount($amount, $userId, $referralUserId, $params = null)
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			$walletModel = self::add($userId, $amount);
			if ($params["inviterBkgId"] > 0)
			{
				$usrModel	 = Users::model()->findByPk($userId);
				$refText	 = ($usrModel->usr_contact_id > 0) ? $usrModel->usrContact->getName() : $usrModel->usr_name . ' ' . $usrModel->usr_lname;

				$refText .= " (" . Booking::model()->getCodeById($params["inviterBkgId"]) . ")";
			}
			else if ($params["bkgId"] > 0)
			{
				$usrModel	 = Users::model()->findByPk($referralUserId);
				$refText	 = ($usrModel->usr_contact_id > 0) ? $usrModel->usrContact->getName() : $usrModel->usr_name . ' ' . $usrModel->usr_lname;

				$refText .= " (" . Booking::model()->getCodeById($params["bkgId"]) . ")";
			}
			$remarks = "Bonus added for referring user " . $refText;
			AccountTransactions::addReferralBonus(new CDbExpression("NOW()"), $amount, Accounting::LI_WALLET, $userId, $userId, $referralUserId, $remarks);
			DBUtil::commitTransaction($transaction);
			$returnSet->setStatus(true);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			DBUtil::rollbackTransaction($transaction);
		}

		return $returnSet;
	}

	/**
	 * 
	 * @param integer $userId  | User ID
	 * @param integer $vsbId   | Subscriber ID
	 * @return ReturnSet
	 */
	public static function redeemVoucherAmount($userId, $vsbId)
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			$vsbModel = VoucherSubscriber::model()->findByPk($vsbId);
			if (!$vsbModel)
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			$amount		 = $vsbModel->vsbVch->vch_wallet_amt;
			$walletModel = self::add($userId, $amount);
			$remarks	 = "Voucher redeemed from wallet Rs." . $amount;

			AccountTransactions::redeemVoucherWallet(new CDbExpression("NOW()"), $amount, $vsbId, $userId, $remarks);

			DBUtil::commitTransaction($transaction);

			$returnSet->setStatus(true);
			$returnSet->setMessage($remarks);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			DBUtil::rollbackTransaction($transaction);
			$returnSet->setStatus(false);
		}
		return $returnSet;
	}

	public static function useWallet($userId, $refId, $flagUse = true, $flagCommit = false, $amount = 0, $creditAmt = 0)
	{
		$returnSet			 = new ReturnSet();
		$totWalletBalance	 = UserWallet::getBalance($userId);
		$amount				 = max([min([$totWalletBalance, $amount]), 0]);
		$walletAmount		 = $amount;
		if ($amount > 0)
		{
			try
			{
				if ($flagCommit)
				{

					$accTransModel = AccountTransactions::userWalletToBooking(new CDbExpression('NOW()'), $amount, $userId, $refId, $userInfo);
				}
				if ($accTransModel != '' || !$flagCommit)
				{
					$booking			 = Booking::model()->findByPk($refId);
					$totWalletBalance	 = ($booking->bkgInvoice->bkg_due_amount < $totWalletBalance) ? $booking->bkgInvoice->bkg_due_amount : $totWalletBalance;
					$amount				 = ($amount > $totWalletBalance) ? $totWalletBalance : $amount;
					if (!$flagUse || $flagUse == 'false')
					{
						$amount = 0;
					}
					$isPromoApplied=false;
					if ($booking->bkgInvoice->bkg_promo1_id > 0)
					{
						$promoModel = Promos::model()->findByPk($booking->bkgInvoice->bkg_promo1_id);
						if ($promoModel)
						{
							$promoModel->promoCode	 = $booking->bkgInvoice->bkg_promo1_code;
							$promoModel->totalAmount = $booking->bkgInvoice->bkg_base_amount;
							$promoModel->createDate	 = $booking->bkg_create_date;
							$promoModel->pickupDate	 = $booking->bkg_pickup_date;
							$promoModel->fromCityId	 = $booking->bkg_from_city_id;
							$promoModel->toCityId	 = $booking->bkg_to_city_id;
							$promoModel->userId		 = $booking->bkgUserInfo->bkg_user_id;
							$promoModel->platform	 = $booking->bkgTrail->bkg_platform;
							$promoModel->carType	 = $booking->bkg_vehicle_type_id;
							$promoModel->bookingType = $booking->bkg_booking_type;
							$promoModel->noOfSeat	 = $booking->bkgAddInfo->bkg_no_person;
							$promoModel->bkgId		 = $booking->bkg_id;
							$promoModel->email		 = '';
							$promoModel->phone		 = '';
							$promoModel->imEfect	 = 1;
							$discountArr			 = $promoModel->applyPromoCode();
							if ($discountArr['cash'] > 0 || $discountArr['coins'] > 0)
							{
								$booking->bkgInvoice->bkg_discount_amount	 = $discountArr['cash'];
								$isPromoApplied								 = true;
								$promoCode									 = $promoModel->prm_code;
								$promoDesc									 = $promoModel->prm_desc;
								$promoType									 = $promoModel->prmCal->pcn_type;
								$promoId									 = $promoModel->prm_id;
								if ($discountArr['pcn_type'] == 1)
								{
									if ($discountArr['prm_activate_on'] == 1)
									{
										$msg = 'Promo ' . $booking->bkgInvoice->bkg_promo1_code . ' applied successfully .You will get discount worth ₹' . $discountArr["cash"] . ' when you make payment.';
									}
									else
									{
										$msg = 'Promo ' . $booking->bkgInvoice->bkg_promo1_code . ' used successfully.';
									}
								}
								if ($discountArr['pcn_type'] == 2)
								{
									$msg = "Promo applied successfully. You got Gozo Coins worth ₹" . $discountArr['coins'] . ". You may redeem these Gozo Coins against your future bookings with us.";
									$msg .= "*<a href='#' onclick='showTcGozoCoins2()'>T&C</a> Apply";
								}
								if ($discountArr['pcn_type'] == 3)
								{
									$msg = "Promo applied successfully. You will get discount worth ₹" . $discountArr['cash'] . " and Gozo Coins worth ₹" . $discountArr['coins'] . ".* You may redeem these Gozo Coins against your future bookings with us.";
									$msg .= "*<a href='#' onclick='showTcGozoCoins2()'>T&C</a> Apply";
								}
								if (isset($discountArr['nextTripApply']) && $discountArr['nextTripApply'] == 1)
								{
									$msg = "Promo applied successfully. You will be benefited on your next trip.";
								}
							}
						}
					}

					$usePromoCredits = ($isPromoApplied) ? false : true;
					$credits		 = UserCredits::getApplicableCredits($booking->bkgUserInfo->bkg_user_id, $booking->bkgInvoice->bkg_base_amount, $usePromoCredits, $booking->bkg_from_city_id, $booking->bkg_to_city_id);
					$creditVal		 = $credits['credits'] | 0;
					$refundCredits	 = $credits['refundCredits'] | 0;
					if (!$usePromoCredits)
					{
						$creditVal = $refundCredits;
					}
					$isCredit = ($creditVal > 0) ? true : false;

					if ($creditAmt > 0)
					{
						$booking->bkgInvoice->bkg_credits_used	 = $creditAmt;
						$isGozoCoinsApplied						 = true;
						$isCredit								 = false;
					}
					$booking->bkgInvoice->calculateTotal();
					$amount									 = ($amount > $booking->bkgInvoice->bkg_due_amount) ? $booking->bkgInvoice->bkg_due_amount : $amount;
					$booking->bkgInvoice->bkg_advance_amount += $amount;
					$booking->bkgInvoice->populateAmount();
					$data									 = [
						'base_amount'		 => $booking->bkgInvoice->bkg_base_amount,
						'total_amount'		 => $booking->bkgInvoice->bkg_total_amount,
						'due_amount'		 => $booking->bkgInvoice->bkg_due_amount,
						'service_tax'		 => $booking->bkgInvoice->bkg_service_tax,
						'amountWithConvFee'	 => $booking->bkgInvoice->bkg_total_amount,
						'convFee'			 => 0,
						'minPayable'		 => $booking->bkgInvoice->calculateMinPayment(),
						'discount'			 => $booking->bkgInvoice->bkg_discount_amount,
						'amtWalletUsed'		 => $amount,
						'credits_used'		 => $creditAmt,
						'isWalletApplied'	 => (!$flagUse || $flagUse == 'false') ? false : true,
						'isPromoApplied'	 => $isPromoApplied,
						'isGozoCoinsApplied' => $isGozoCoinsApplied,
						'message'			 => $msg,
						'promo_code'		 => $promoCode,
						'promo_desc'		 => $promoDesc,
						'promo_type'		 => $promoType,
						'promo_id'			 => $promoId,
						'refundCredits'		 => $refundCredits | 0,
						'totCredits'		 => $creditVal | 0,
						'isCredit'			 => $isCredit | false,
						'isRefundCredits'	 => ($refundCredits > 0) ? true : false
					];

					$returnSet->setStatus(true);
					$returnSet->setData($data);
					if ($flagCommit)
					{
						$bookingInvoice							 = BookingInvoice::model()->findByPk($booking->bkgInvoice->biv_id);
						$bookingInvoice->bkg_is_wallet_selected	 = 0;
						$bookingInvoice->bkg_wallet_used		 = 0;
						$bookingInvoice->save();
						//  $booking->bkgInvoice->save();
						$usrId									 = UserInfo::getInstance();
						$success								 = UserWallet::add($userId, -1 * $walletAmount);
						if ($success)
						{
							$returnSet->setStatus($success);
						}
						BookingLog::model()->createLog($refId, "Wallet ₹$walletAmount used successfully.", $usrId, BookingLog::DEDUCTED_CUSTOMER_WALLET);
					}
				}
			}
			catch (Exception $e)
			{
				$returnSet->setStatus(false);
				$returnSet->addError($e->getMessage());
			}
		}
		else
		{
			$returnSet->setStatus(false);
			$returnSet->addError("Amount can not be 0.");
		}
		return $returnSet;
	}

	public static function getByUser($userId)
	{
		$balance = 0;
		if ($userId > 0)
		{
			$sql	 = "SELECT  * FROM `user_wallet` WHERE urw_user_id='" . $userId . "'";
			$wallet	 = DBUtil::command($sql, DBUtil::MDB())->queryRow();
		}
		return $wallet;
	}

	public function getbyUserId($userId)
	{
		$criteria	 = new CDbCriteria;
		$criteria->compare('urw_user_id', $userId);
		$model		 = $this->find($criteria);
		if ($model)
		{
			return $model;
		}
		else
		{
			return false;
		}
	}

	public static function UpdateWalletBalance($defaultDays = 3)
	{
		$param	 = ['days' => $defaultDays];
		$sql	 = "SELECT DISTINCT adt.adt_trans_ref_id		
					FROM account_trans_details adt
					INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id AND adt.adt_ledger_id = 47
					WHERE
						adt.adt_modified BETWEEN DATE_SUB(NOW(), INTERVAL :days DAY) AND NOW()
						AND act.act_active = 1 AND adt.adt_active = 1 AND adt.adt_trans_ref_id IS NOT NULL
					";

		$result = DBUtil::query($sql, DBUtil::SDB(), $param);

		foreach ($result as $row)
		{
			try
			{
				$userid	 = $row['adt_trans_ref_id'];
				$param	 = ['userid' => $userid];
				$sql1	 = "SELECT IFNULL(sum(adt_amount),0) outstanding
							FROM   account_trans_details 
							INNER JOIN account_transactions ON account_transactions.act_id = account_trans_details.adt_trans_id
							WHERE  account_trans_details.adt_active = 1 AND account_transactions.act_active=1
								AND account_trans_details.adt_status = 1
								AND account_trans_details.adt_ledger_id = 47
								AND account_trans_details.adt_trans_ref_id =:userid";
				$result1 = DBUtil::queryScalar($sql1, DBUtil::SDB(), $param);

				$userWalletModel = UserWallet::model()->getbyUserId($userid);
				if (!$userWalletModel)
				{
					$userWalletModel				 = new UserWallet();
					$userWalletModel->urw_user_id	 = $userid;
				}
				$userWalletModel->urw_wallet_amount = -1 * $result1 | 0;
				$userWalletModel->save();
			}
			catch (Exception $e)
			{
				Logger::exception($e);
			}
		}
	}

	public static function getLockedBalance($userId)
	{
		$param	 = ['userID' => $userId];
		$sql	 = "SELECT   IFNULL(urw_locked_amount, 0)   lockedbalance  FROM `user_wallet` WHERE urw_user_id=:userID";
		$balance = DBUtil::queryScalar($sql, DBUtil::MDB(), $param);
		$balance = ($balance | 0);
		return $balance;
	}

	public static function lockBalance($userId, $amount)
	{

		$param = ['userID' => $userId, 'amount' => $amount];
		if ($userId > 0)
		{
			$sql	 = "UPDATE `user_wallet` SET `urw_locked_amount` = urw_locked_amount+:amount WHERE  urw_user_id= :userID ";
			$updated = DBUtil::execute($sql, $param);
		}
		return $updated;
	}

	public static function releaseLockedBalance($userId, $amount)
	{
		$amount	 = ($amount > 0) ? -1 * $amount : $amount;
		$param	 = ['userID' => $userId, 'amount' => $amount];
		if ($userId > 0)
		{
			$sql	 = "UPDATE `user_wallet` SET `urw_locked_amount` = urw_locked_amount+:amount WHERE  urw_user_id = :userID AND urw_locked_amount+:amount >=0";
			$updated = DBUtil::execute($sql, $param);
		}
		return $updated;
	}

	//wallet transaction history
	public function getTransHistoryCustomer($userId, $ledgerId)
	{
		/* $sql = "SELECT act_id,act_date AS created, adt_amount, act_remarks, adt_id, adt_trans_id,apg_booking_id,apg_amount, bkg_pickup_date  FROM `account_trans_details`
		  INNER JOIN account_transactions ON act_id=adt_trans_id
		  INNER JOIN payment_gateway ON apg_id=adt_trans_ref_id AND adt_ledger_id=apg_ledger_id AND apg_user_id=$userId
		  INNER JOIN booking ON bkg_id=apg_booking_id
		  WHERE act_active=1 AND apg_status =1 AND adt_active=1"; */
		$sql			 = "SELECT act_id,act_date AS created, adt_amount, act_remarks, adt_id, adt_trans_id,apg_booking_id,apg_amount, bkg_pickup_date    
				FROM   account_trans_details adt  
        JOIN account_transactions act  ON act.act_id = adt.adt_trans_id AND act.act_active = 1 AND act.act_status = 1 AND adt.adt_active = 1
			LEFT JOIN payment_gateway ON apg_id=adt_trans_ref_id AND adt_ledger_id=apg_ledger_id 
            LEFT JOIN booking ON bkg_id=apg_booking_id
        where adt.adt_trans_ref_id =$userId AND adt.adt_ledger_id =$ledgerId";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['defaultOrder' => 'act_id DESC'],
			'pagination'	 => ['pageSize' => 20],
			'params'		 => $params,
		]);
		return $dataprovider;
	}

}

<?php

/**
 * This is the model class for table "partner_pending_advance".
 *
 * The followings are the available columns in table 'partner_pending_advance':
 * @property integer $ppa_id
 * @property integer $ppa_bkg_id
 * @property integer $ppa_bkg_adv_amount
 * @property integer $ppa_err_count
 * @property string $ppa_last_error
 * @property integer $ppa_status
 * @property string $ppa_created_date
 * @property string $ppa_update_date
 */
class PartnerPendingAdvance extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'partner_pending_advance';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ppa_bkg_id, ppa_bkg_adv_amount, ppa_created_date', 'required'),
			array('ppa_bkg_id, ppa_bkg_adv_amount, ppa_err_count, ppa_status', 'numerical', 'integerOnly'=>true),
			array('ppa_last_error', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ppa_id, ppa_bkg_id, ppa_bkg_adv_amount, ppa_err_count, ppa_last_error, ppa_status, ppa_created_date, ppa_update_date', 'safe', 'on'=>'search'),
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
			'ppa_id' => 'Ppa',
			'ppa_bkg_id' => 'Ppa Bkg',
			'ppa_bkg_adv_amount' => 'Ppa Bkg Adv Amount',
			'ppa_err_count' => 'Ppa Err Count',
			'ppa_last_error' => 'Ppa Last Error',
			'ppa_status' => 'Ppa Status',
			'ppa_created_date' => 'Ppa Created Date',
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

		$criteria=new CDbCriteria;

		$criteria->compare('ppa_id',$this->ppa_id);
		$criteria->compare('ppa_bkg_id',$this->ppa_bkg_id);
		$criteria->compare('ppa_bkg_adv_amount',$this->ppa_bkg_adv_amount);
		$criteria->compare('ppa_err_count',$this->ppa_err_count);
		$criteria->compare('ppa_last_error',$this->ppa_last_error,true);
		$criteria->compare('ppa_status',$this->ppa_status);
		$criteria->compare('ppa_created_date',$this->ppa_created_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PartnerPendingAdvance the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	
	public static function add($bkgModel, $advAmt)
	{
		/** @var Booking $bkgModel */
		$objPPA = new PartnerPendingAdvance();
		$objPPA->ppa_bkg_id = $bkgModel->bkg_id;
		$objPPA->ppa_bkg_adv_amount = $advAmt;
		$objPPA->ppa_err_count = 0;
		$objPPA->ppa_status = 0;
		$objPPA->ppa_created_date = date("Y-m-d H:i:s");
		$objPPA->save();
	}

	/**
	 * @return Array
	 */
	public static function getPendingList()
	{
		$sql	 = "SELECT * FROM partner_pending_advance WHERE ppa_status IN(0,2) AND ppa_err_count <= 5 ORDER BY ppa_id DESC limit 100";
		$records = DBUtil::query($sql, DBUtil::MDB());	
		return $records;
	}	
	/**
	 * @return Array
	 */
	public static function getSuccessList()
	{
		$sql	 = "SELECT * FROM partner_pending_advance WHERE ppa_status = 1 AND ppa_update_date >= DATE_SUB(NOW(),INTERVAL 10 MINUTE)";
		$records = DBUtil::queryAll($sql, DBUtil::MDB());
		return $records;
	}

	public static function pendingAdvanceProcess()
	{
		$records = self::getPendingList();
		foreach ($records as $row)
		{
			$transaction = null;
			try
			{
				$amount		 = $row['ppa_bkg_adv_amount'];
				$bkg		 = $row['ppa_bkg_id'];
				$ppaid		 = $row['ppa_id'];
				$model		 = Booking::model()->findByPk($bkg);
				$modelppa	 = PartnerPendingAdvance::model()->findByPk($ppaid);
				if ($amount > 0)
				{
					$transaction = DBUtil::beginTransaction();
					$success = $model->updateAdvance($amount, $model->bkg_pickup_date, PaymentType::TYPE_AGENT_CORP_CREDIT, UserInfo::getInstance(), null, "Partner Wallet used");
					
					if ($success)
					{
						$modelppa->ppa_status = 1;
						$modelppa->ppa_update_date = new CDbExpression('NOW()');
						$modelppa->save();
					}
					else
					{
						$modelppa->ppa_status		 = 2;
						$modelppa->ppa_err_count	 += 1;
						$modelppa->ppa_last_error	 = CJSON::encode($model->getErrors());
						$modelppa->save();
						if ($modelppa->ppa_err_count > 5)
						{
							$model->setAccountingFlag("Partner pending advance update failed,Error count : {$modelppa->ppa_err_count})");
						}
					}
                                        $model->refresh();
					DBUtil::commitTransaction($transaction);
                                        
                                        if ($success)
					{
                                            if ($model->bkg_status == 9)
                                            {
                                            $cancelCharges	 = $model->calculateRefund();
                                            $refundAmount	 = $cancelCharges->refund;
                                            if ($refundAmount > 0)
                                            {	
                                            $dataArray	 = array('refundAmount' => $refundAmount);
                                            $refundData	 = CJSON::encode($dataArray);
                                            BookingScheduleEvent::add($model->bkg_id, BookingScheduleEvent::REFUND_PROCESS, "Refund on booking cancelation", $refundData);
                                            }

                                            //$cancelCharges_new = CancellationPolicy::initiateRequest($model);
                                                if ($cancelCharges->charges > 0)
                                                {       
                                                        $userInfo		 = UserInfo::getInstance();
                                                        $chargePartnerCredit = ($cancelCharges->refund < 0) ? abs($cancelCharges->refund) : 0;
                                                        if ($chargePartnerCredit > 0)
                                                        {
                                                                $model->updateAdvance1($chargePartnerCredit, $userInfo->userType, PaymentType::TYPE_AGENT_CORP_CREDIT);
                                                                $model->chargePartnerWalletOnCancellation($chargePartnerCredit, $model->bkg_pickup_date, UserInfo::model());
                                                        }
                                                        $model->bkgInvoice->processCancelCharge($cancelCharges->charges, $model->bkg_agent_id);
                                                }
                                            }
                                        }
				}
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				$modelppa->ppa_status		 = 2;
				$modelppa->ppa_err_count	 += 1;
				$modelppa->ppa_last_error	 = CJSON::encode($model->getErrors());
				$modelppa->save();
				if ($modelppa->ppa_err_count > 5)
				{
					$model->setAccountingFlag("Partner pending advance update failed,Error count : {$modelppa->ppa_err_count})");
				}
				Logger::exception($ex);
			}
		}
	}

	public static function updateBalancebyBookingid()
	{
		$records = self::getSuccessList();
		foreach ($records as $row)
		{
			$transaction = null;
			try
			{
				$bkg			 = $row['ppa_bkg_id'];
				$amount			 = $row['ppa_bkg_adv_amount'];
				$model			 = Booking::model()->findByPk($bkg);
				$balance		 = AccountTransDetails::getBalancebyBookingid($bkg);
				$bkgAdvance		 = $model->bkgInvoice->bkg_advance_amount;
				$accountAdvance	 = (int) $balance['advance'];
				$accountRefund	 = (int) $balance['refund'];
				$transaction	 = DBUtil::beginTransaction();
				if ($bkgAdvance != $accountAdvance && ($balance['advance'] != '' || $balance['advance'] != null))
				{

					$model->bkgInvoice->bkg_advance_amount	 = $accountAdvance;
					$model->bkgInvoice->bkg_refund_amount	 = $accountRefund;
					$model->bkgInvoice->calculateTotal();
					if (!$model->bkgInvoice->save())
					{
						throw new Exception("Failed to update bkg_advance_amount, advance not matched with account: " . json_encode($model->bkgInvoice->getErrors()));
					}
				}
				if ($bkgAdvance == 0 && ($balance['advance'] == null || $balance['advance'] == ''))
				{
					$success = $model->updateAdvance($amount, $model->bkg_pickup_date, PaymentType::TYPE_AGENT_CORP_CREDIT, UserInfo::getInstance(), null, "Partner Wallet used");
					if (!$success)
					{
						throw new Exception("Failed to update advance : " . $success);
					}
				}
				if ($bkgAdvance > 0 && ($balance['advance'] == null || $balance['advance'] == ''))
				{
					$model->bkgInvoice->bkg_advance_amount = 0;
					$model->bkgInvoice->calculateTotal();
					if ($model->bkgInvoice->save())
					{
						$success = $model->updateAdvance($amount, $model->bkg_pickup_date, PaymentType::TYPE_AGENT_CORP_CREDIT, UserInfo::getInstance(), null, "Partner Wallet used");
						if (!$success)
						{
							throw new Exception("Failed to update advance : " . $success);
						}
					}
				}
				/*
				 * mmt DoubleBack
				 */
//				if ($model->bkgInvoice->bkg_advance_amount > 0 && $model->bkg_reconfirm_flag == 1)
//				{
//					if($model->bkg_agent_id == 450 || $model->bkg_agent_id == 18190)
//					{
//						$isMmtC1Route = Route::getMmtC1RouteByCity($model->bkg_from_city_id,$model->bkg_to_city_id);
//						if($isMmtC1Route)
//						{
//						  $model->bkgTrail->updateDBO($model->bkg_pickup_date,$model->bkg_agent_id);
//						}
//					}
//				}
				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				$model->setAccountingFlag("Booking advance not matched with account: Booking amount <=> Account amount " . $bkgAdvance . " <=> " . $accountAdvance);
				Logger::exception($ex);
			}
		}
	}
}

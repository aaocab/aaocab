<?php

/**
 * This is the model class for table "gift_card_subscriber".
 *
 * The followings are the available columns in table 'gift_card_subscriber':
 * @property integer $gcs_id
 * @property string $gcs_card_code
 * @property integer $gcs_quantity
 * @property string $gcs_email_address
 * @property string $gcs_name
 * @property string $gcs_phone
 * @property string $gcs_message
 * @property integer $gcs_value_type
 * @property integer $gcs_value_amount
 * @property integer $gcs_user_type
 * @property integer $gcs_user_id
 * @property integer $gcs_promo_id
 * @property integer $gcs_cost_price
 * @property integer $gcs_redeem_by_type
 * @property integer $gcs_redeem_by
 * @property integer $gcs_active
 * @property string $gcs_purchase_date
 * @property string $gcs_create_date
 * @property string $gcs_modified_date
 */
class GiftCardSubscriber extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'gift_card_subscriber';
	}
	
	public static $type		= [1 => "Select"];
	public $gcs_promo_code;
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('gcs_name, gcs_email_address, gcs_quantity, gcs_value_amount', 'required'),
			array('gcs_quantity, gcs_value_type, gcs_value_amount, gcs_user_type, gcs_user_id, gcs_cost_price, gcs_redeem_by_type, gcs_redeem_by, gcs_active, gcs_phone', 'numerical', 'integerOnly'=>true),
			array('gcs_card_code', 'length', 'max'=>50),
			array('gcs_email_address, gcs_name', 'length', 'max'=>100),
			array('gcs_phone', 'length', 'max'=>50),
			array('gcs_phone', 'length', 'min' => 10, 'on' => 'insert,update', 'message' => 'Phone minimum length should be 10 character',),
			array('gcs_email_address', 'email', 'on' => 'insert,update', 'message' => 'Please enter valid email address', 'checkMX' => true),
			array('gcs_message', 'length', 'max'=>1000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('gcs_id, gcs_card_code, gcs_quantity, gcs_email_address, gcs_name, gcs_phone, gcs_message, gcs_value_type, gcs_value_amount, gcs_user_type, gcs_user_id, gcs_promo_id, gcs_cost_price, gcs_redeem_by_type, gcs_redeem_by, gcs_active, gcs_purchase_date, gcs_create_date, gcs_modified_date', 'safe', 'on'=>'search'),
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
			'gcs_id' => 'ID',
			'gcs_card_code' => 'Card Code',
			'gcs_quantity' => 'Quantity',
			'gcs_email_address' => 'Email Address',
			'gcs_name' => 'Name',
			'gcs_phone' => 'Phone',
			'gcs_message' => 'Message',
			'gcs_value_type' => 'Value Type',
			'gcs_value_amount' => 'Value Amount',
			'gcs_user_type' => 'User Type',
			'gcs_user_id' => 'User',
			'gcs_promo_id' => 'Promo',
			'gcs_cost_price' => 'Please select at least one gift card.Amount',
			'gcs_redeem_by_type' => 'Redeem By Type',
			'gcs_redeem_by' => 'Redeem By',
			'gcs_active' => 'Active',
			'gcs_purchase_date' => 'Purchase Date',
			'gcs_create_date' => 'Create Date',
			'gcs_modified_date' => 'Modified Date',
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

		$criteria->compare('gcs_id',$this->gcs_id);
		$criteria->compare('gcs_card_code',$this->gcs_card_code,true);
		$criteria->compare('gcs_quantity',$this->gcs_quantity);
		$criteria->compare('gcs_email_address',$this->gcs_email_address,true);
		$criteria->compare('gcs_name',$this->gcs_name,true);
		$criteria->compare('gcs_phone',$this->gcs_phone,true);
		$criteria->compare('gcs_message',$this->gcs_message,true);
		$criteria->compare('gcs_value_type',$this->gcs_value_type);
		$criteria->compare('gcs_value_amount',$this->gcs_value_amount);
		$criteria->compare('gcs_user_type',$this->gcs_user_type);
		$criteria->compare('gcs_user_id',$this->gcs_user_id);
		$criteria->compare('gcs_promo_id', $this->gcs_promo_id );
		$criteria->compare('gcs_cost_price',$this->gcs_cost_price);
		$criteria->compare('gcs_redeem_by_type',$this->gcs_redeem_by_type);
		$criteria->compare('gcs_redeem_by',$this->gcs_redeem_by);
		$criteria->compare('gcs_active',$this->gcs_active);
		$criteria->compare('gcs_purchase_date',$this->gcs_purchase_date,true);
		$criteria->compare('gcs_create_date',$this->gcs_create_date,true);
		$criteria->compare('gcs_modified_date',$this->gcs_modified_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GiftCardSubscriber the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function giftCardAmount($agentId)
	{
		$sql ="SELECT prm_code,pcn_value_cash,gcr_cost_price,prm_id,gcr_id
				FROM promos
				INNER JOIN promo_calculation ON promos.prm_id = promo_calculation.pcn_promo_id AND prm_user_type = 2
				INNER JOIN promo_gift_card ON gcr_promo_id = prm_id
				LEFT JOIN gift_card_partner ON prm_id = prp_promo_id AND prp_partner_id = $agentId AND prp_active = 1
				WHERE gcr_user_type = 1 AND (gcr_is_selected = 2 OR prp_id IS NOT NULL) AND NOW() BETWEEN prm_valid_from AND prm_valid_upto AND prm_active =1";
		return DBUtil::command($sql, DBUtil::SDB())->queryAll();
	}
	
	public function saveData($paymentOpt = 0)
	{
		$userInfo	 = UserInfo::getInstance();
		$returnSet	 = new ReturnSet();
		$isNew		 = $this->isNewRecord;
		$agentId	 = Yii::app()->user->getAgentId();
		//$transcode		 = Yii::app()->request->getParam('tinfo', '');
		$transaction = DBUtil::beginTransaction();
		try
		{
			$result = CActiveForm::validate($this, null, false);
			if ($result == '[]')
			{
				if (!$this->save())
				{
					throw new Exception('Failed to save gift card.');
				}
                $paybleAmt = $this->gcs_cost_price + Agents::model()->calculateProcessingFee($this->gcs_cost_price);
				if($paymentOpt == 1)
				{
					$paymentGateway						 = new PaymentGateway();
					$paymentGateway->apg_acc_trans_type	 = Accounting::AT_PARTNER;
					$paymentGateway->apg_trans_ref_id	 = $agentId;
					$paymentGateway->apg_ptp_id			 = PaymentType::TYPE_PAYTM;
					$paymentGateway->apg_amount			 = $paybleAmt;
					$paymentGateway->apg_remarks		 = "Payment Initiated";
					$paymentGateway->apg_ref_id			 = '';
					$paymentGateway->apg_user_type		 = UserInfo::TYPE_AGENT;
					$paymentGateway->apg_user_id		 = $agentId;
					$paymentGateway->apg_status			 = 0;
					$paymentGateway->apg_date			 = new CDbExpression("now()");
					$bankLedgerId						 = PaymentType::model()->ledgerList(PaymentType::TYPE_PAYTM);
					$transModel							 = $paymentGateway->payment($bankLedgerId);
					DBUtil::commitTransaction($transaction);
					if ($transModel->apg_id)
					{
						$params['blg_ref_id']	 = $transModel->apg_id;
						$url					 = Yii::app()->createUrl('paytm/partnerpaymentinitiate', ['acctransid' => $transModel->apg_id, 'giftId' => $this->gcs_id]);
						//$this->redirect($url);
						Yii::app()->request->redirect($url);
					}
				}
				if($paymentOpt == 2)
				{
					$paymentGateway						 = new PaymentGateway();
					$paymentGateway->apg_acc_trans_type	 = Accounting::AT_PARTNER;
					$paymentGateway->apg_trans_ref_id	 = $agentId;
					$paymentGateway->apg_ptp_id			 = PaymentType::TYPE_PAYUMONEY;
					$paymentGateway->apg_amount			 = $paybleAmt;
					$paymentGateway->apg_remarks		 = "Payment Initiated";
					$paymentGateway->apg_ref_id			 = '';
					$paymentGateway->apg_user_type		 = UserInfo::TYPE_AGENT;
					$paymentGateway->apg_user_id		 = $agentId;
					$paymentGateway->apg_status			 = 0;
					$paymentGateway->apg_date			 = new CDbExpression("now()");
					$bankLedgerId						 = PaymentType::model()->ledgerList(PaymentType::TYPE_PAYUMONEY);
					$transModel							 = $paymentGateway->payment($bankLedgerId);
					DBUtil::commitTransaction($transaction);
					if ($transModel->apg_id)
					{
						$params['blg_ref_id']	 = $transModel->apg_id;
						$url					 = Yii::app()->createUrl('payu/partnerpaymentinitiate', ['acctransid' => $transModel->apg_id, 'giftId' => $this->gcs_id]);
						Yii::app()->request->redirect($url);
					}
				}
				DBUtil::commitTransaction($transaction);
				//$returnSet = ['gcs_id' => $this->gcs_id];
				$returnSet->setStatus(true);
			}
			else
			{
				throw new Exception('Validation Failed.');
			}
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$this->addError("gft_id", $e->getMessage());
			$returnSet->setErrors($this->getErrors());
			DBUtil::rollbackTransaction($transaction);
			return $returnSet;
		}
		return $returnSet;
	}

    public function getByGiftCardCode($code)
	{
		$sql	 = "SELECT gcs_id
				FROM   gift_card_subscriber
				WHERE  gcs_redeem_by_type IS NULL AND  gcs_redeem_by IS NULL AND gcs_active = 1 AND gcs_card_code =:code";
		$gcs_id	 = DBUtil::command($sql, DBUtil::SDB())->bindParam(':code', $code)->queryScalar();
		if (!$gcs_id)
		{
			return false;
		}
		return $this->model()->findByPk($gcs_id);
	}

	public function redeemGiftCard($giftCardCode, $userId)
	{
		$gfcSubscriberModel = GiftCardSubscriber::model()->getByGiftCardCode($giftCardCode);
		if ($gfcSubscriberModel)
		{
				$gfcSubscriberModel->gcs_redeem_by		 = UserInfo::getUserId();
		        $gfcSubscriberModel->gcs_redeem_by_type	 = 2;
				if ($gfcSubscriberModel->save())
				{
					$refId								 =  $gfcSubscriberModel->gcs_id;
					$userWalletModel	= UserWallet::model()->find('urw_user_id=:user',['user'=>UserInfo::getUserId()]);
					if($userWalletModel == '')
					{
					  $userWalletModel					 = new UserWallet();
					}
					$userWalletModel->urw_user_id		 = UserInfo::getUserId();
                    $userWalletModel->urw_ref_id		 = $refId;
		//			$userWalletModel->urw_wallet_amount	 += $gfcSubscriberModel->gcs_value_amount;
                    $userWalletModel->urw_create_date = new CDbExpression('NOW()');
                    $userWalletModel->urw_modified_date = new CDbExpression('NOW()');
					
					if($userWalletModel->save())
					{
						 $accTransModel				 = new AccountTransactions();
						 $accTransModel->act_amount	 = -1 * $gfcSubscriberModel->gcs_value_amount;
						 $accTransModel->act_date	 = new CDbExpression('NOW()');
						 $accTransModel->act_type	 = Accounting::AT_GIFTCARD;
						 $accTransModel->act_ref_id	 = $gfcSubscriberModel->gcs_id;
						 $accTransModel->act_remarks = "Gift card redeemed to wallet Rs." . $gfcSubscriberModel->gcs_value_amount;
						 
						 $accTransModel->AddReceipt(Accounting::LI_WALLET, Accounting::LI_GIFTCARD, $userId, $refId, '{"TRANSACTION_MODE":2,"DESCRIPTION":"Giftcard redeemed to wallet.","STATUS":"SUCCESS"}', Accounting::AT_USER);
					     return true;     
					}
				}
		}
	    return false;
	}

}

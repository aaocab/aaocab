<?php

/**
 * This is the model class for table "voucher_subscriber".
 *
 * The followings are the available columns in table 'voucher_subscriber':
 * @property integer $vsb_id
 * @property integer $vsb_vor_id 
 * @property integer $vsb_vch_id
 * @property integer $vsb_apg_id
 * @property string $vsb_redeem_code
 * @property integer $vsb_redeen_date
 * @property string $vsb_date
 * @property integer $vsb_purchased_by
 * @property integer $vsb_redeemed_by
 * @property string $vsb_name
 * @property string $vsb_email
 * @property string $vsb_phone
 * @property string $vsb_expiry_date
 * @property integer $vsb_cost
 * @property integer $vsb_active
 *
 * The followings are the available model relations:
 * @property Vouchers $vsbVch
 */
class VoucherSubscriber extends CActiveRecord
{

    public $vsb_qty;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'voucher_subscriber';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('vsb_cost', 'required'),
            array('vsb_vch_id, vsb_purchased_by, vsb_redeemed_by, vsb_active', 'numerical', 'integerOnly' => true),
            array('vsb_redeem_code', 'length', 'max' => 70),
            array('vsb_name, vsb_email, vsb_phone', 'length', 'max' => 100),
            array('vsb_date, vsb_expiry_date', 'safe'),
            array('vsb_email', 'email', 'message' => 'Please enter valid email address'),
            array('vsb_name, vsb_email', 'required', 'on' => 'addToCart'),
            array('vsb_vch_id', 'validateMaxPurchase', 'on' => 'addToCart'),
            array('vsb_qty', 'validateQty', 'on' => 'addToCart'),
            array('vsb_vor_id, vsb_vch_id, vsb_name, vsb_email', 'required', 'on' => 'update'),
            array('vsb_vor_id, vsb_vch_id', 'required', 'on' => 'redeem-validate'),
            array('vsb_redeem_code', 'validateRedeemStatus', 'on' => 'redeem-validate'),
            array('vsb_vor_id, vsb_vch_id, vsb_redeemed_by, vsb_redeem_date', 'required', 'on' => 'redeem-save'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('vsb_id, vsb_vor_id, vsb_vch_id, vsb_apg_id, vsb_qty, vsb_redeem_code, vsb_redeen_date, vsb_date, vsb_purchased_by, vsb_redeemed_by, vsb_name, vsb_email, vsb_phone, vsb_expiry_date, vsb_cost, vsb_active', 'safe', 'on' => 'search'),
        );
    }

    public function validateRedeemStatus($attribute, $params)
    {

        if ($this->vsb_active == 2)
        {
            return true;
        }
        $this->addError($attribute, "Voucher code already redeemed or no longer valid");
        return false;
    }

    public function validateMaxPurchase($attribute, $params)
    {
        $sql               = "SELECT vouchers.vch_max_allowed_limit as maxAllowed FROM `vouchers` WHERE vouchers.vch_id = :voucherId";
        $params            = ['voucherId' => $this->vsb_vch_id];
        $maxAllowedLimit   = DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
        $userPurchaseCount = self::getPurchaseCount($this->vsb_vch_id);
        if ($maxAllowedLimit > $userPurchaseCount)
        {
            return true;
        }
        else
        {
            $message = "Sorry! Purchase Limit Per User has been exceeded for voucher : " . $this->vsbVch->vch_title;
            $this->addError($attribute, $message);
            return false;
        }
    }

    public function validateQty($attribute, $params)
    {
        if (trim($this->vsb_qty) && $this->vsb_qty > 0)
        {
            return true;
        }
        else
        {
            $this->addError($attribute, 'Quantity is Required');
            return false;
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'vsbVch' => array(self::BELONGS_TO, 'Vouchers', 'vsb_vch_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'vsb_id'           => 'Vsb',
            'vsb_vch_id'       => 'Vsb Vch',
            'vsb_redeem_code'  => 'Vsb Redeem Code',
            'vsb_redeen_date'  => 'Vsb Redeen Date',
            'vsb_date'         => 'Vsb Date',
            'vsb_purchased_by' => 'Vsb Purchased By',
            'vsb_redeemed_by'  => 'Vsb Redeemed By',
            'vsb_name'         => 'Name',
            'vsb_email'        => 'Email',
            'vsb_phone'        => 'Phone',
            'vsb_qty'          => 'Enter Quantity',
            'vsb_expiry_date'  => 'Vsb Expiry Date',
            'vsb_cost'         => 'Vsb Cost',
            'vsb_active'       => 'Vsb Active',
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

        $criteria->compare('vsb_id', $this->vsb_id);
        $criteria->compare('vsb_vch_id', $this->vsb_vch_id);
        $criteria->compare('vsb_redeem_code', $this->vsb_redeem_code, true);
        $criteria->compare('vsb_redeen_date', $this->vsb_redeen_date);
        $criteria->compare('vsb_date', $this->vsb_date, true);
        $criteria->compare('vsb_purchased_by', $this->vsb_purchased_by);
        $criteria->compare('vsb_redeemed_by', $this->vsb_redeemed_by);
        $criteria->compare('vsb_name', $this->vsb_name, true);
        $criteria->compare('vsb_email', $this->vsb_email, true);
        $criteria->compare('vsb_phone', $this->vsb_phone, true);
        $criteria->compare('vsb_expiry_date', $this->vsb_expiry_date, true);
        $criteria->compare('vsb_cost', $this->vsb_cost);
        $criteria->compare('vsb_active', $this->vsb_active);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return VoucherSubscriber the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * 
     * @param integer $vorId
     * @param integer $voucherId
     * @param integer $apgId
     * @param string $name
     * @param string $email
     * @param string $phone
     * @param float $cost
     * @return boolean
     * @throws Exception
     */
    public static function add($vorId, $voucherId, $apgId, $name, $email, $phone, $cost)
    {
        $model                  = new VoucherSubscriber();
        $model->vsb_vor_id      = $vorId;
        $model->vsb_vch_id      = $voucherId;
        $model->vsb_apg_id      = $apgId;
        $model->vsb_redeem_code = self::generateCode(20);
        $model->vsb_name        = $name;
        $model->vsb_email       = $email;
        $model->vsb_phone       = $phone;
        $model->vsb_cost        = $cost;
        $model->vsb_active      = 2;
        $model->scenario        = 'update';
        if (!$model->save())
        {
            throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
        }
        $elgId = emailWrapper::sendVoucherRedeemCode($model);
        return true;
    }

    public static function AddPromo($promoType, $voucherId, $name, $email, $phone, $userId)
    {
        $cost    = Vouchers::model()->findByPk($voucherId)->vch_cost;
        $success = self::add(null, $voucherId, null, $name, $email, $phone, $cost);
        if ($success)
        {
            $ledger = ($promoType == 1) ? Accounting::LI_PROMOTIONS_MARKETING : Accounting::LI_VOUCHER;
            AccountTransactions::addReferralBonus($cost, $userId, $refUserId, new CDBExpressions('NOW()'), $ledger, $params);
        }
    }

    public function getByOrderId($orderId)
    {
        $model = $this->find("vsb_vor_id=:orderId", ['orderId' => $orderId]);
        return $model;
    }

    /**
     * 
     * @param String $code
     * @return model
     * @throws Exception
     */
    public static function updateRedeemCredentials($code)
    {
        $vsbModel = VoucherSubscriber::model()->find('vsb_redeem_code=:code', ['code' => $code]);
        if (!$vsbModel)
        {
            throw new Exception("Invalid Code", ReturnSet::ERROR_VALIDATION);
        }
        $vsbModel->vsb_redeemed_by = UserInfo::getUserId();
        $vsbModel->vsb_redeem_date = new CDbExpression('NOW()');
        $vsbModel->vsb_active      = 1;
        $vsbModel->scenario        = 'redeem-update';
        if (!$vsbModel->save())
        {
            throw new Exception(json_encode($vsbModel->getErrors()), ReturnSet::ERROR_VALIDATION);
        }
        return $vsbModel;
    }

    /**
     * 
     * @param string $code
     * @param integer $userId
     * @return ReturnSet
     * @throws Exception
     */
    public static function redeem($code, $userId)
    {
        $returnSet   = new ReturnSet();
        $transaction = DBUtil::beginTransaction();
        try
        {
			/** @var $vsbModel VoucherSubscriber */
            $vsbModel = VoucherSubscriber::model()->find('vsb_redeem_code=:code', ['code' => $code]);
            if (!$vsbModel)
            {
                throw new Exception("Invalid Code", ReturnSet::ERROR_VALIDATION);
            }
            $vsbModel->scenario = 'redeem-validate';
            if (!$vsbModel->validate())
            {
                throw new Exception(json_encode($vsbModel->getErrors()), ReturnSet::ERROR_VALIDATION);
            }
            $vsbModel->vsb_redeemed_by = $userId;
            $vsbModel->vsb_redeem_date = new CDbExpression('NOW()');
            $vsbModel->vsb_active      = 1;
            $vsbModel->scenario        = 'redeem-save';
            if (!$vsbModel->save())
            {
                throw new Exception(json_encode($vsbModel->getErrors()), ReturnSet::ERROR_VALIDATION);
            }
            $model = Vouchers::model()->findByPk($vsbModel->vsb_vch_id);
            switch ($model->vch_type)
            {
                case 1:
                    $validFrom = $model->vch_valid_from;
                    $validTo   = $model->vch_valid_to;
                    $promoId   = $model->vch_promo_id;
                    $resultSet = Vouchers::redeemPromotions($promoId, $userId, $validFrom, $validTo);
                    if ($resultSet)
                    {
                        $msg = "Voucher ($code) has been successfully applied.";
                    }
                    break;
                case 2:
                    $amount    = $vsbModel->vsb_cost;
                    $msg       = "Voucher ($code) redeemed to wallet for ₹" . $amount . ".";
                    $resultSet = AccountTransactions::redeemWalletVoucher(new CDbExpression("NOW()"), $amount, $vsbModel->vsb_id, $userId, $msg);
                    break;
            }
            $returnSet->setStatus(true);
            $returnSet->setMessage($msg);
            DBUtil::commitTransaction($transaction);
        }
        catch (Exception $ex)
        {

            $returnSet = ReturnSet::setException($ex);
            DBUtil::rollbackTransaction($transaction);
        }

        skipRedeem:
        return $returnSet;
    }

    public function getByCode($code)
    {
        $sql   = "SELECT vsb_id 
					FROM   voucher_subscriber				
					WHERE  vsb_active = 2 AND vsb_redeem_code =:code";
        $vsbId = DBUtil::command($sql, DBUtil::SDB())->bindParam(':code', $code)->queryScalar();
        if (!$vsbId)
        {
            return false;
        }
        return $this->findByPk($vsbId);
    }

    public static function getType($vid)
    {
        $sql = "SELECT vch_type FROM vouchers where vch_id =:vid";
        return DBUtil::command($sql, DBUtil::SDB())->bindParam(':vid', $vid)->queryScalar();
    }

    public static function setWallet($vouSubModel)
    {
        $refId           = $vouSubModel->vsb_id;
        $userWalletModel = UserWallet::model()->find('urw_user_id=:user', ['user' => UserInfo::getUserId()]);
        if ($userWalletModel == '')
        {
            $userWalletModel = new UserWallet();
        }
        $userWalletModel->urw_user_id       = UserInfo::getUserId();
        $userWalletModel->urw_ref_id        = $refId;
 //       $userWalletModel->urw_wallet_amount += $vouSubModel->vsb_cost;
        $userWalletModel->urw_create_date   = new CDbExpression('NOW()');
        $userWalletModel->urw_modified_date = new CDbExpression('NOW()');
        if ($userWalletModel->save())
        {
            $accTransModel              = new AccountTransactions();
            $accTransModel->act_amount  = -1 * $vouSubModel->vsb_cost;
            $accTransModel->act_date    = new CDbExpression('NOW()');
            $accTransModel->act_type    = Accounting::LI_VOUCHER;
            $accTransModel->act_ref_id  = $refId;
            $accTransModel->act_remarks = "Voucher redeemed to wallet Rs." . $vouSubModel->vsb_cost;
            $accTransModel->AddReceipt(Accounting::LI_WALLET, Accounting::LI_VOUCHER, UserInfo::getUserId(), $refId, '{"TRANSACTION_MODE":2,"DESCRIPTION":"Giftcard redeemed to wallet.","STATUS":"SUCCESS"}', Accounting::AT_USER);
            return true;
        }
    }

    public static function getDetails($vid)
    {
        $sql = "SELECT * FROM vouchers where vch_id =:vid";
        return DBUtil::command($sql, DBUtil::SDB())->bindParam(':vid', $vid)->queryRow();
    }

    public static function setPromo($res, $userId)
    {
        $promoId   = $res['vch_promo_id'];
        $validFrom = ($res['vch_valid_from']) ? $res['vch_valid_from'] : date('Y-m-d') . " 00:00:00";
        $validTo   = ($res['vch_valid_to']) ? $res['vch_valid_to'] : date('Y-m-d', strtotime('+1 month')) . " 00:00:00";
        $model     = PromoUsers::addUser($promoId, $userId, 0, 1, $validFrom, $validTo, 1, 1);
        return true;
    }

    /**
     * 
     * @return string
     */
    public static function generateCode($digits = 12)
    {
        $code    = Filter::getRandomCode($digits);
        $sql     = "SELECT count(1) as cnts FROM `voucher_subscriber` WHERE voucher_subscriber.vsb_redeem_code=:code";
        $isExist = DBUtil::command($sql, DBUtil::SDB())->bindParam(':code', $code)->queryScalar();
        if ($isExist)
        {
            self::generateCode($digits);
        }
        return $code;
    }

    public static function getRedeemCodeDetails($vorId)
    {
        $sql = "SELECT vs.vsb_redeem_code,vb.vch_code,vb.vch_title FROM voucher_subscriber as vs Inner Join vouchers as vb on vb.vch_id = vs.vsb_vch_id where vs.vsb_vor_id =:vid";
        return DBUtil::command($sql, DBUtil::SDB())->bindParam(':vid', $vorId)->queryAll();
    }

    public static function getUserCount($vid)
    {
        $sql    = "SELECT count(vsb_id) FROM voucher_subscriber where vsb_vch_id =:vid AND vsb_redeemed_by = :uid";
        $params = ['vid' => $vid, 'uid' => UserInfo::getUserId()];
        return DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
    }

    /**
     * 
     * @param integer $vchId
     * @return integer
     */
    public static function getPurchaseCount($vchId)
    {
        $sql               = "SELECT COUNT(1) as cnt
				FROM  `voucher_subscriber` 
				LEFT JOIN `voucher_order` on voucher_order.vor_id=voucher_subscriber.vsb_vor_id
				WHERE voucher_subscriber.vsb_vch_id=:voucherId 
				AND  voucher_order.vor_user_id=:userId AND voucher_order.vor_active=1
				ORDER By voucher_subscriber.vsb_id DESC";
        $params            = ['voucherId' => $vchId, 'userId' => UserInfo::getUserId()];
        $userPurchaseCount = DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
        return $userPurchaseCount;
    }

}

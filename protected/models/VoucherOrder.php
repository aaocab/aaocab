<?php

/**
 * This is the model class for table "voucher_order".
 *
 * The followings are the available columns in table 'voucher_order':
 * @property integer $vor_id
 * @property string $vor_number
 * @property integer $vor_apg_id
 * @property integer $vor_user_id
 * @property string $vor_sess_id
 * @property string $vor_name
 * @property string $vor_email
 * @property string $vor_phone
 * @property double $vor_total_price
 * @property string $vor_bill_fullname
 * @property string $vor_bill_contact
 * @property string $vor_bill_email
 * @property string $vor_bill_address
 * @property string $vor_bill_country
 * @property string $vor_bill_state
 * @property string $vor_bill_city
 * @property string $vor_bill_postalcode
 * @property string $vor_bill_bankcode
 * @property string $vor_date
 * @property integer $vor_active
 *
 * The followings are the available model relations:
 * @property VoucherOrderDetails[] $voucherOrderDetails
 * @property VoucherOrderInvoice[] $voucherOrderInvoices
 */
class VoucherOrder extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public $partialPayment, $paymentType, $vor_tnc;

    public function tableName()
    {
        return 'voucher_order';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('vor_apg_id, vor_active', 'numerical', 'integerOnly' => true),
            array('vor_total_price', 'numerical'),
            array('vor_number, vor_name, vor_email, vor_phone', 'length', 'max' => 50),
            array('vor_sess_id, vor_bill_fullname, vor_bill_contact, vor_bill_email, vor_bill_address, vor_bill_country, vor_bill_state, vor_bill_city, vor_bill_postalcode, vor_bill_bankcode', 'length', 'max' => 255),
            array('vor_bill_fullname, vor_bill_contact, vor_bill_email, vor_bill_address,  vor_bill_country, vor_bill_city', 'required', 'on' => 'pay'),
            array('vor_id', 'validateTotalAmount', 'on' => 'pay'),
            //	array('vor_bill_postalcode', 'validateBillPostalcode', 'on' => 'pay'),
            //	array('vor_bill_state', 'validateStateId', 'on' => 'pay'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('vor_id, vor_number, vor_apg_id, vor_user_id, vor_sess_id, vor_name, vor_email, vor_phone, vor_total_price, vor_bill_fullname, vor_bill_contact, vor_bill_email, vor_bill_address, vor_bill_country, vor_bill_state, vor_bill_city, vor_bill_postalcode, vor_bill_bankcode, vor_date, vor_active, vor_tnc, paymentType', 'safe', 'on' => 'search'),
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
            'voucherOrderDetails'  => array(self::HAS_MANY, 'VoucherOrderDetails', 'vod_ord_id'),
            'voucherOrderInvoices' => array(self::HAS_MANY, 'VoucherOrderInvoice', 'voi_vor_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'vor_id'              => 'Vor',
            'vor_number'          => 'Vor Number',
            'vor_apg_id'          => 'Vor Apg',
            'vor_user_id'         => 'Vor User',
            'vor_sess_id'         => 'Vor Sess',
            'vor_name'            => 'Vor Name',
            'vor_email'           => 'Email',
            'vor_phone'           => 'Phone',
            'vor_total_price'     => 'Amount',
            'vor_bill_fullname'   => 'Fullname',
            'vor_bill_contact'    => 'Contact',
            'vor_bill_email'      => 'Email',
            'vor_bill_address'    => 'Address',
            'vor_bill_country'    => 'Country',
            'vor_bill_state'      => 'State',
            'vor_bill_city'       => 'City',
            'vor_bill_postalcode' => 'Postalcode',
            'vor_bill_bankcode'   => 'Vor Bill Bankcode',
            'vor_date'            => 'Vor date',
            'vor_active'          => 'Vor Active',
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

        $criteria->compare('vor_id', $this->vor_id);
        $criteria->compare('vor_number', $this->vor_number, true);
        $criteria->compare('vor_apg_id', $this->vor_apg_id);
        $criteria->compare('vor_sess_id', $this->vor_sess_id, true);
        $criteria->compare('vor_name', $this->vor_name, true);
        $criteria->compare('vor_email', $this->vor_email, true);
        $criteria->compare('vor_phone', $this->vor_phone, true);
        $criteria->compare('vor_total_price', $this->vor_total_price);
        $criteria->compare('vor_bill_fullname', $this->vor_bill_fullname, true);
        $criteria->compare('vor_bill_contact', $this->vor_bill_contact, true);
        $criteria->compare('vor_bill_email', $this->vor_bill_email, true);
        $criteria->compare('vor_bill_address', $this->vor_bill_address, true);
        $criteria->compare('vor_bill_country', $this->vor_bill_country, true);
        $criteria->compare('vor_bill_state', $this->vor_bill_state, true);
        $criteria->compare('vor_bill_city', $this->vor_bill_city, true);
        $criteria->compare('vor_bill_postalcode', $this->vor_bill_postalcode, true);
        $criteria->compare('vor_bill_bankcode', $this->vor_bill_bankcode, true);
        $criteria->compare('vor_active', $this->vor_active);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return VoucherOrder the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function validateBillPostalcode($attribute, $params)
    {
        if (trim($this->vor_bill_country) != 'HK' && trim($this->vor_bill_postalcode) == '')
        {
            $this->addError($attribute, 'Postal Code is Required');
            return false;
        }
        return true;
    }

    public function validateStateId($attribute, $params)
    {
        if (trim($this->vor_bill_country) == 'IN' && trim($this->vor_bill_state) == '')
        {
            $this->addError($attribute, 'Billing State is Required');
            return false;
        }
        return true;
    }

    public function validateTotalAmount($attribute, $params)
    {
        $sessionCart = Vouchers::getCartSessData();
        $cartBalance = round(Vouchers::getCartBalance($sessionCart));
        if (round($this->vor_total_price) != $cartBalance)
        {
            $this->addError($attribute, 'Total amount mismatched.');
            return false;
        }
        return true;
    }

    public function getByCode($vchNumber)
    {
        $model = $this->find("vor_number=:vchNumber", ['vchNumber' => $vchNumber]);
        return $model;
    }

    public function getBySessId($sessionId)
    {
        return $this->find("vor_sess_id=:sessionId", ['sessionId' => $sessionId]);
    }

    /**
     * 
     * @param integer $vorId
     * @param integer $apgId
     * @return \ReturnSet
     * @throws Exception
     */
    public static function confirm($vorId, $apgId = 0)
    {
        $transaction = DBUtil::beginTransaction();
        $returnSet   = new ReturnSet();
        try
        {
            /* @var $vorModel VoucherOrder */
            $vorModel              = VoucherOrder::model()->findByPk($vorId);
            $vorModel->vor_name    = $vorModel->vor_bill_fullname;
            $vorModel->vor_email   = $vorModel->vor_bill_email;
            $vorModel->vor_phone   = $vorModel->vor_bill_contact;
            $vorModel->vor_apg_id  = $apgId;
            $vorModel->vor_user_id = UserInfo::getUserId();
            $vorModel->vor_active  = 1;
            $vorModel->vor_date    = new CDbExpression('NOW()');
            if (!$vorModel->save())
            {
                throw new Exception(json_encode($vorModel->getErrors()), ReturnSet::ERROR_VALIDATION);
            }
            $orderDetails = $vorModel->voucherOrderDetails;
            foreach ($orderDetails as $order)
            {
                $orderQuantity = $order->vod_vch_qty;
                $sellingPrice  = ($order->vod_vch_price / $order->vod_vch_qty);
                $subsData      = Vouchers::getCartRowData($order->vod_vch_id);
                $subsName      = $subsData['name'];
                $subsEmail     = $subsData['email'];
                $subsPhone     = $subsData['phone'];
                for ($i = 0; $i < $orderQuantity; $i++)
                {
                    if ($orderQuantity != null)
                    {
                        VoucherSubscriber::add($vorModel->vor_id, $order->vod_vch_id, $apgId, $subsName, $subsEmail, $subsPhone, $sellingPrice);
                    }
                }
            }
            $elgId = emailWrapper::sendVoucherConfirmation($vorModel);
            self::unsetCartSession();
            $returnSet->setStatus(true);
            DBUtil::commitTransaction($transaction);
        }
        catch (Exception $ex)
        {
            Logger::exception($ex);
            $returnSet = ReturnSet::setException($ex);
            DBUtil::rollbackTransaction($transaction);
        }
        return $returnSet;
    }

    /**
     * 
     * @param array $cartData[]
     * @param integer $cartBalance
     * @param string $sessionId
     * @return \ReturnSet
     * @throws Exception
     */
    public static function updateData($cartData, $cartBalance, $sessionId)
    {
        $returnSet   = new ReturnSet();
        $transaction = DBUtil::beginTransaction();
        try
        {
            $modelOrder = VoucherOrder::model()->getBySessId($sessionId);
            if (!$modelOrder)
            {
                $modelOrder              = new VoucherOrder();
                $modelOrder->vor_sess_id = $sessionId;
                $modelOrder->vor_number  = self::getOrderNumber();
                $modelOrder->vor_user_id = UserInfo::getUserId();
            }
            $modelOrder->vor_total_price = $cartBalance;
            $modelOrder->vor_active      = 2;
            if (!$modelOrder->save())
            {
                throw new Exception("Failed to save Order", ReturnSet::ERROR_VALIDATION);
            }
            if ($modelOrder)
            {
                VoucherOrderDetails::deleteByOrderId($modelOrder->vor_id);
            }
            foreach ($cartData as $c)
            {
                $modelOrderDetails                = new VoucherOrderDetails();
                $modelOrderDetails->vod_vch_id    = Yii::app()->shortHash->unHash($c['id']);
                $modelOrderDetails->vod_vch_price = $c['price'];
                $modelOrderDetails->vod_vch_qty   = $c['qty'];
                $modelOrderDetails->vod_ord_id    = $modelOrder->vor_id;
                $modelOrderDetails->scenario      = 'checkPurchaseLimit';
                if (!$modelOrderDetails->save())
                {
                    throw new Exception(CJSON::encode($modelOrderDetails->getErrors()), ReturnSet::ERROR_VALIDATION);
                }
            }
			
            $modelInvoice = VoucherOrderInvoice::model()->getByOrderId($modelOrder->vor_id);
            if (!$modelInvoice)
            {
                $modelInvoice               = new VoucherOrderInvoice();
                $modelInvoice->voi_vor_id   = $modelOrder->vor_id;
                //$modelInvoice->voi_platform = UserInfo::getUserType();
            }
            $modelInvoice->voi_total_amount = $cartBalance;
            if (!$modelInvoice->save())
            {
                throw new Exception("Failed to save Invoice.", ReturnSet::ERROR_VALIDATION);
            }
            $returnSet->setStatus(true);
            $returnSet->setData(['orderNumber' => $modelOrder->vor_number]);
            DBUtil::commitTransaction($transaction);
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
     * @param integer $userId
     */
    public function updateBillingDetails($userId)
    {
        /* @var $usrModel Users */
        $usrModel                  = Users::model()->findByPk($userId);
        $this->vor_bill_fullname   = $usrModel->usr_name . ' ' . $usrModel->usr_lname;
        $this->vor_bill_email      = $usrModel->usr_email;
        $this->vor_bill_contact    = $usrModel->usr_mobile;
        $this->vor_bill_postalcode = $usrModel->usr_zip;
        $this->vor_bill_country    = $usrModel->usr_country;
        $this->vor_bill_city       = $usrModel->usr_city;
        $this->vor_bill_address    = $usrModel->usr_address1;
        $this->save();
    }

    /**
     * 
     * @return string
     */
    public static function getOrderNumber()
    {
        $code    = Filter::getRandomCode(11);
        $sql     = "SELECT count(1) as cnt FROM `voucher_order` WHERE voucher_order.vor_number=:code";
        $isExist = DBUtil::command($sql, DBUtil::SDB())->bindParam(':code', $code)->queryScalar();
        if ($isExist)
        {
            self::getOrderNumber();
        }
        return $code;
    }

    public static function unsetCartSession()
    {
        $session = Yii::app()->session;
        $session->remove('_voucher_sess_id');
        $session->remove('_voucher_cart');
        $session->remove('_voucher_sub_name');
        $session->remove('_voucher_sub_email');
        $session->remove('_voucher_sub_phone');
    }

    /**
     * 
     * @param integer $userId
     * @return array
     */
    public static function getHistory($userId, $params = null)
    {
        $sqlParams = [':usrId' => $userId];
        $sql       = "SELECT
					vouchers.vch_id,
					vouchers.vch_title,
					vouchers.vch_code,
					vouchers.vch_desc,
					vouchers.vch_selling_price,
					vouchers.vch_type,
					voucher_order.vor_number,
					voucher_order.vor_bill_fullname,
					voucher_order.vor_bill_contact,
					voucher_order.vor_bill_email,
					voucher_order.vor_bill_address,
					voucher_order.vor_bill_country,
					voucher_order.vor_bill_state,
					voucher_order.vor_bill_city,
					voucher_order.vor_bill_postalcode,
					voucher_order.vor_active,
					voucher_order.vor_date,
					voucher_order_details.vod_vch_qty,
					voucher_order_details.vod_vch_price
				FROM  `voucher_order_details`
				INNER JOIN `voucher_order` ON voucher_order.vor_id = voucher_order_details.vod_ord_id
				LEFT JOIN `vouchers` ON vouchers.vch_id = voucher_order_details.vod_vch_id 
				LEFT JOIN `payment_gateway` ON payment_gateway.apg_id=voucher_order.vor_apg_id
				WHERE voucher_order.vor_user_id=:usrId
				ORDER BY voucher_order.vor_date DESC";
        if (!empty($params))
        {
            $sql .= " LIMIT " . $params['pageNumber'] . ", " . $params['limitRange'];
        }
        return DBUtil::queryAll($sql, DBUtil::SDB(), $sqlParams);
    }

    /**
     * 
     * @return array
     */
    public static function getStatusList()
    {
        $statusList = [
            1 => 'Completed',
            2 => 'Pending',
        ];
        return $statusList;
    }

    /**
     * 
     * @param integer $type
     * @return string
     */
    public static function getStatus($type)
    {
        $list = self::getStatusList();
        return $list[$type];
    }

}

<?php

/**
 * This is the model class for table "vouchers".
 *
 * The followings are the available columns in table 'vouchers':
 * @property integer $vch_id
 * @property string $vch_code
 * @property string $vch_title
 * @property string $vch_desc
 * @property integer $vch_type
 * @property integer $vch_selling_price
 * @property integer $vch_promo_id
 * @property integer $vch_wallet_amt
 * @property integer $vch_is_all_partner
 * @property integer $vch_is_all_users
 * @property integer $vch_max_allowed_limit
 * @property integer $vch_redeem_user_limit
 * @property integer $vch_user_purchase_limit
 * @property integer $vch_partner_purchase_limit
 * @property integer $vch_sold_ctr
 * @property string $vch_valid_from
 * @property string $vch_valid_to
 * @property integer $vch_active
 *
 * The followings are the available model relations:
 * @property VoucherPartner[] $voucherPartners
 * @property VoucherSubscriber[] $voucherSubscribers
 * @property VoucherUsers[] $voucherUsers
 * @property VoucherUsers $vch
 */
class Vouchers extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vouchers';
	}

	public $voucherType = ['1' => 'Promo', '2' => 'Wallet'];

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vch_code, vch_title,vch_type', 'required', 'on' => 'add,edit'),
			array('vch_code', 'unique', 'on' => 'add'),
			array('vch_type, vch_selling_price, vch_promo_id, vch_wallet_amt, vch_is_all_partner, vch_is_all_users, vch_max_allowed_limit, vch_redeem_user_limit, vch_user_purchase_limit, vch_partner_purchase_limit, vch_sold_ctr, vch_active', 'numerical', 'integerOnly' => true),
			array('vch_code', 'length', 'max' => 70),
			array('vch_title', 'length', 'max' => 100),
			array('vch_valid_from, vch_valid_to', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vch_id, vch_code, vch_title, vch_desc, vch_type, vch_selling_price, vch_promo_id, vch_wallet_amt, vch_is_all_partner, vch_is_all_users, vch_max_allowed_limit, vch_redeem_user_limit, vch_user_purchase_limit, vch_partner_purchase_limit, vch_sold_ctr, vch_valid_from, vch_valid_to, vch_active', 'safe', 'on' => 'search'),
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
			'voucherPartners'	 => array(self::HAS_MANY, 'VoucherPartner', 'vpr_vch_id'),
			'voucherSubscribers' => array(self::HAS_MANY, 'VoucherSubscriber', 'vsb_vch_id'),
			'voucherUsers'		 => array(self::HAS_MANY, 'VoucherUsers', 'vus_vch_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'vch_id'					 => 'Vch',
			'vch_code'					 => 'Voucher Code',
			'vch_title'					 => 'Voucher Title',
			'vch_desc'					 => 'Voucher Description',
			'vch_type'					 => 'Voucher Type',
			'vch_selling_price'			 => 'Voucher Selling Price',
			'vch_promo_id'				 => 'Voucher Promo',
			'vch_wallet_amt'			 => 'Voucher Wallet Amt',
			'vch_is_all_partner'		 => 'Vch Is All Partner',
			'vch_is_all_users'			 => 'Vch Is All Users',
			'vch_max_allowed_limit'		 => 'Vch Max Allowed Ctr',
			'vch_redeem_user_limit'		 => 'Vch Redeem User Ctr',
			'vch_user_purchase_limit'	 => 'Vch Buy User Ctr',
			'vch_partner_purchase_limit' => 'Vch Partner User Ctr',
			'vch_sold_ctr'				 => 'Vch Sold Ctr',
			'vch_valid_from'			 => 'Vch Valid From',
			'vch_valid_to'				 => 'Vch Valid To',
			'vch_active'				 => 'Vch Active',
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

		$criteria->compare('vch_id', $this->vch_id);
		$criteria->compare('vch_code', $this->vch_code, true);
		$criteria->compare('vch_title', $this->vch_title, true);
		$criteria->compare('vch_desc', $this->vch_desc, true);
		$criteria->compare('vch_type', $this->vch_type);
		$criteria->compare('vch_selling_price', $this->vch_selling_price);
		$criteria->compare('vch_promo_id', $this->vch_promo_id);
		$criteria->compare('vch_wallet_amt', $this->vch_wallet_amt);
		$criteria->compare('vch_is_all_partner', $this->vch_is_all_partner);
		$criteria->compare('vch_is_all_users', $this->vch_is_all_users);
		$criteria->compare('vch_max_allowed_limit', $this->vch_max_allowed_limit);
		$criteria->compare('vch_redeem_user_limit', $this->vch_redeem_user_limit);
		$criteria->compare('vch_user_purchase_limit', $this->vch_user_purchase_limit);
		$criteria->compare('vch_partner_purchase_limit', $this->vch_partner_purchase_limit);
		$criteria->compare('vch_sold_ctr', $this->vch_sold_ctr);
		$criteria->compare('vch_valid_from', $this->vch_valid_from, true);
		$criteria->compare('vch_valid_to', $this->vch_valid_to, true);
		$criteria->compare('vch_active', $this->vch_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Vouchers the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 
	 * @return array
	 */
	public static function getRecords()
	{
		$sql = "SELECT * FROM `vouchers` WHERE vouchers.vch_active=1 AND (vouchers.vch_valid_to IS NULL OR vouchers.vch_valid_to > NOW())";
		$sql .= " ORDER BY vouchers.vch_valid_to ASC";
		return DBUtil::queryAll($sql);
	}

	/**
	 * 
	 * @return object
	 */
	public function findAll($command = false)
	{
		$sql			 = "SELECT * FROM `vouchers` WHERE vouchers.vch_active=1 AND (vouchers.vch_valid_to IS NULL OR vouchers.vch_valid_to > NOW())";
		$defaultOrder	 = 'vouchers.vch_valid_to ASC';
		if ($command == false)
		{
			$dataprovider = new CSqlDataProvider($sql, [
				'sort' => ['attributes'	 => ['vch_valid_to'],
					'defaultOrder'	 => $defaultOrder]
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::queryAll($sql);
		}
	}

	public function getList($type = null)
	{
		$sql = self::getQuery();
		if ($type == 'command')
		{
			return DBUtil::queryAll($sql);
		}
		else
		{
			$count			 = DBUtil::command("SELECT COUNT(1) FROM ($sql) abc")->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
	}

	public function getQuery()
	{
		if ($this->vch_code != '')
		{
			$cond .= " AND vch_code like'%" . $this->vch_code . "%'";
		}
		if ($this->vch_title != '')
		{
			$cond .= " AND vch_title like '%" . $this->vch_title . "%'";
		}
		if ($this->vch_type != "")
		{
			if (is_array($this->vch_type))
			{
				$cond .= " AND vch_type IN(" . implode(',', $this->vch_type) . ")";
			}
			else
			{
				$cond .= " AND vch_type IN(" . $this->vch_type . ")";
			}
		}

		$sql = "SELECT * FROM vouchers	WHERE 1" . $cond;
		$sql .= " ORDER BY vch_id DESC";
		return $sql;
	}

	/**
	 * 
	 * @param int $qty
	 * @param int $price
	 * @param Vouchers $model
	 * @param string $sessionId
	 */
	public static function setCart($qty, $price, $model, $sessionId)
	{
		Yii::app()->session['_voucher_sub_name']	 = $model->vsb_name;
		Yii::app()->session['_voucher_sub_email']	 = $model->vsb_email;
		Yii::app()->session['_voucher_sub_phone']	 = $model->vsb_phone;
		$cartData									 = isset(Yii::app()->session['_voucher_cart']) ? Yii::app()->session['_voucher_cart'] : [];
		$cartData[$model->vsb_vch_id]				 = [
			"id"		 => $model->vsb_vch_id,
			"quantity"	 => $qty,
			"price"		 => $price,
			"name"		 => $model->vsb_name,
			"email"		 => $model->vsb_email,
			"phone"		 => $model->vsb_phone,
		];
		Yii::app()->session['_voucher_cart']		 = ($cartData);
		Yii::app()->session['_voucher_sess_id']		 = $sessionId;
	}

	/**
	 * 
	 * @param array $cartData
	 * @param boolean $sessionSet
	 * @return array
	 */
	public static function getCart($cartData, $sessionSet = false)
	{
		if ($sessionSet)
		{
			Yii::app()->session['_voucher_cart'] = ($cartData);
		}
		$cart = [];
		foreach ($cartData as $cData)
		{
			/** @var $model Vouchers * */
			$model	 = Vouchers::model()->findByPk($cData['id']);
			$cart[]	 = [
				'id'	 => Yii::app()->shortHash->hash($model->vch_id),
				'code'	 => $model->vch_code,
				'title'	 => $model->vch_title,
				'desc'	 => $model->vch_desc,
				'qty'	 => $cData['quantity'],
				'price'	 => ($cData['quantity'] * $cData['price']),
				'name'	 => $cData['name'],
				'email'	 => $cData['email'],
				'phone'	 => $cData['phone']];
		}
		return $cart;
	}

	public static function getCartSessData()
	{
		return Yii::app()->session['_voucher_cart'];
	}

	/**
	 * 
	 * @param integer $voucherId
	 * @return array
	 */
	public static function getCartRowData($voucherId)
	{
		$cartData = self::getCartSessData();
		return $cartData[$voucherId];
	}

	/**
	 * 
	 * @param array $cartData
	 * @return integer
	 */
	public static function getCartBalance($cartData)
	{
		$cart	 = self::getCart($cartData);
		$cartBal = 0;
		foreach ($cart as $c)
		{
			$cartBal = $cartBal + $c['price'];
		}
		return $cartBal;
	}

	public static function getAllVoucherForSpecificUserJSON($query)
	{
		$sql = "SELECT vch_id,vch_code FROM `vouchers`  where  vch_is_all_users = 0 AND  vch_active = 1";
		if ($query != "")
		{
			$sql .= " AND vch_code LIKE '%{$query}%' ";
		}
		echo $sql;
		$rows		 = DBUtil::queryAll($sql, DBUtil::SDB());
		$arrVouchers = array();
		foreach ($rows as $row)
		{
			$arrVouchers[] = array("id" => $row['vch_id'], "text" => $row['vch_code']);
		}
		$data = CJSON::encode($arrVouchers);
		return $data;
	}

	/**
	 * 
	 * @return array
	 */
	public static function getAllType()
	{
		$eventlist = [
			1	 => 'Promo',
			2	 => 'Wallet',
		];
		return $eventlist;
	}

	/**
	 * 
	 * @param integer $type
	 * @return string
	 */
	public static function getType($type)
	{
		$list = self::getAllType();
		return $list[$type];
	}
        
        /** 
         * 
         * @param type $promoId
         * @param type $userId
         * @param type $validFrom
         * @param type $validTo
         * @return type
         */
        public static function redeemPromotions($promoId, $userId, $validFrom, $validTo)
        {
             $model = PromoUsers::addUser($promoId, $userId, 0, 1, $validFrom, $validTo, 1, 1);
             return $model;
        }

}

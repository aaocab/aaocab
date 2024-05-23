<?php

/**
 * This is the model class for table "voucher_order_details".
 *
 * The followings are the available columns in table 'voucher_order_details':
 * @property integer $vod_id
 * @property integer $vod_ord_id
 * @property integer $vod_vch_id
 * @property string $vod_sess_id
 * @property integer $vod_vch_qty
 * @property double $vod_vch_price
 *
 * The followings are the available model relations:
 * @property VoucherOrder $vodOrd
 * @property Vouchers $vodVch
 */
class VoucherOrderDetails extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'voucher_order_details';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vod_ord_id, vod_vch_id, vod_vch_qty', 'numerical', 'integerOnly' => true),
			array('vod_vch_price', 'numerical'),
			array('vod_id', 'validateMaxPurchase', 'on' => 'checkPurchaseLimit'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vod_id, vod_ord_id, vod_vch_id, vod_sess_id, vod_vch_qty, vod_vch_price', 'safe', 'on' => 'search'),
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
			'vodOrd' => array(self::BELONGS_TO, 'VoucherOrder', 'vod_ord_id'),
			'vodVch' => array(self::BELONGS_TO, 'Vouchers', 'vod_vch_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'vod_id'		 => 'Vod',
			'vod_ord_id'	 => 'Vod Ord',
			'vod_vch_id'	 => 'Vod Vch',
			'vod_sess_id'	 => 'Vod Session Id',
			'vod_vch_qty'	 => 'Vod Vch Qty',
			'vod_vch_price'	 => 'Vod Vch Price',
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

		$criteria->compare('vod_id', $this->vod_id);
		$criteria->compare('vod_ord_id', $this->vod_ord_id);
		$criteria->compare('vod_vch_id', $this->vod_vch_id);
		$criteria->compare('vod_sess_id', $this->vod_sess_id);
		$criteria->compare('vod_vch_qty', $this->vod_vch_qty);
		$criteria->compare('vod_vch_price', $this->vod_vch_price);
		$criteria->compare('vod_active', $this->vod_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function validateMaxPurchase($attribute, $params)
	{
		$sql			 = "SELECT vouchers.vch_max_allowed_limit as maxAllowed FROM `vouchers` WHERE vouchers.vch_id = :voucherId";
		$params			 = ['voucherId' => $this->vod_vch_id];
		$maxAllowedLimit = DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
		$userPurchaseCount = VoucherSubscriber::getPurchaseCount($this->vod_vch_id);
		if ($maxAllowedLimit > $userPurchaseCount)
		{
			return true;
		}
		else
		{
			$message = "Sorry! Purchase Limit Per User has been exceeded for voucher : ".$this->vodVch->vch_title;
			$this->addError($attribute, $message);
			return false;
		}
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VoucherOrderDetails the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 
	 * @param integer $orderId
	 * @return integer
	 */
	public static function deleteByOrderId($orderId)
	{
		$sql	 = "DELETE FROM `voucher_order_details` WHERE vod_ord_id = '$orderId'";
		$rowsDel = DBUtil::command($sql)->execute();
		return $rowsDel;
	}

	public function getByOrderId($orderId)
	{
		$model = $this->find("vod_ord_id=:orderId", ['orderId' => $orderId]);
		return $model;
	}

	public function getInfoByOrderId($orderId)
	{
		$models			 = $this->getByOrderId($orderId);
		$sessionCartData = Yii::app()->session['_voucher_cart'];
		foreach ($models as $model)
		{
			
		}
	}

}

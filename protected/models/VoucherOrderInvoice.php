<?php

/**
 * This is the model class for table "voucher_order_invoice".
 *
 * The followings are the available columns in table 'voucher_order_invoice':
 * @property integer $voi_id
 * @property integer $voi_vor_id
 * @property integer $voi_base_amount
 * @property integer $voi_discount_amount
 * @property integer $voi_total_amount
 * @property integer $voi_gozo_amount
 * @property integer $voi_corporate_credit
 * @property integer $voi_credits_used
 * @property integer $voi_advance_amount
 * @property integer $voi_refund_amount
 * @property integer $voi_cancel_refund
 * @property integer $voi_refund_approval_status
 * @property integer $voi_due_amount
 * @property integer $voi_additional_charge
 * @property string $voi_additional_charge_remark
 * @property integer $voi_convenience_charge
 * @property integer $voi_service_tax
 * @property integer $voi_service_tax_rate
 * @property double $voi_igst
 * @property double $voi_cgst
 * @property double $voi_sgst
 * @property integer $voi_extra_charge
 * @property integer $voi_cancel_charge
 * @property integer $voi_corporate_discount
 * @property integer $voi_platform
 * @property integer $voi_promo1_id
 * @property string $voi_promo1_code
 * @property integer $voi_promo1_amt
 * @property integer $voi_promo1_coins
 * @property integer $voi_promo2_id
 * @property string $voi_promo2_code
 * @property integer $voi_promo2_amt
 * @property integer $voi_price_surge_id
 * @property integer $voi_agent_commission
 * @property integer $voi_cp_comm_type
 * @property string $voi_cp_comm_value
 * @property integer $voi_chargeable_distance
 * @property integer $voi_corporate_remunerator
 * @property integer $voi_partner_commission
 * @property integer $voi_wallet_used
 * @property integer $voi_temp_credits
 *
 * The followings are the available model relations:
 * @property VoucherOrder $voiVor
 */
class VoucherOrderInvoice extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'voucher_order_invoice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('voi_vor_id', 'required'),
			array('voi_vor_id, voi_base_amount, voi_discount_amount, voi_total_amount, voi_gozo_amount, voi_corporate_credit, voi_credits_used, voi_advance_amount, voi_refund_amount, voi_cancel_refund, voi_refund_approval_status, voi_due_amount, voi_additional_charge, voi_convenience_charge, voi_service_tax, voi_service_tax_rate, voi_extra_charge, voi_cancel_charge,  voi_corporate_discount, voi_platform, voi_promo1_id, voi_promo1_amt, voi_promo1_coins, voi_promo2_id, voi_promo2_amt, voi_price_surge_id, voi_agent_commission, voi_cp_comm_type, voi_chargeable_distance, voi_corporate_remunerator, voi_partner_commission, voi_wallet_used, voi_temp_credits', 'numerical', 'integerOnly'=>true),
			array('voi_igst, voi_cgst, voi_sgst', 'numerical'),
			array('voi_additional_charge_remark', 'length', 'max'=>250),
			array('voi_promo1_code, voi_promo2_code', 'length', 'max'=>100),
			array('voi_cp_comm_value', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('voi_id, voi_vor_id, voi_base_amount, voi_discount_amount, voi_total_amount, voi_gozo_amount, voi_corporate_credit, voi_credits_used, voi_advance_amount, voi_refund_amount, voi_cancel_refund, voi_refund_approval_status, voi_due_amount, voi_additional_charge, voi_additional_charge_remark, voi_convenience_charge, voi_service_tax, voi_service_tax_rate, voi_igst, voi_cgst, voi_sgst, voi_extra_charge, voi_cancel_charge,  voi_corporate_discount, voi_platform, voi_promo1_id, voi_promo1_code, voi_promo1_amt, voi_promo1_coins, voi_promo2_id, voi_promo2_code, voi_promo2_amt, voi_price_surge_id, voi_agent_commission, voi_cp_comm_type, voi_cp_comm_value, voi_chargeable_distance, voi_corporate_remunerator, voi_partner_commission, voi_wallet_used, voi_temp_credits', 'safe', 'on'=>'search'),
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
			'voiVor' => array(self::BELONGS_TO, 'VoucherOrder', 'voi_vor_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'voi_id' => 'Voi',
			'voi_vor_id' => 'Voi Vor',
			'voi_base_amount' => 'Voi Base Amount',
			'voi_discount_amount' => 'Voi Discount Amount',
			'voi_total_amount' => 'Voi Total Amount',
			'voi_gozo_amount' => 'Voi Gozo Amount',
			'voi_corporate_credit' => 'Voi Corporate Credit',
			'voi_credits_used' => 'Voi Credits Used',
			'voi_advance_amount' => 'Voi Advance Amount',
			'voi_refund_amount' => 'Voi Refund Amount',
			'voi_cancel_refund' => 'Voi Cancel Refund',
			'voi_refund_approval_status' => 'Voi Refund Approval Status',
			'voi_due_amount' => 'Voi Due Amount',
			'voi_additional_charge' => 'Voi Additional Charge',
			'voi_additional_charge_remark' => 'Voi Additional Charge Remark',
			'voi_convenience_charge' => 'Voi Convenience Charge',
			'voi_service_tax' => 'Voi Service Tax',
			'voi_service_tax_rate' => 'Voi Service Tax Rate',
			'voi_igst' => 'Voi Igst',
			'voi_cgst' => 'Voi Cgst',
			'voi_sgst' => 'Voi Sgst',
			'voi_extra_charge' => 'Voi Extra Charge',
			'voi_cancel_charge' => 'Voi Cancel Charge',
			'voi_corporate_discount' => 'Voi Corporate Discount',
                        'voi_platform' => 'Voi Platform',
			'voi_promo1_id' => 'Voi Promo1',
			'voi_promo1_code' => 'Voi Promo1 Code',
			'voi_promo1_amt' => 'Voi Promo1 Amt',
			'voi_promo1_coins' => 'Voi Promo1 Coins',
			'voi_promo2_id' => 'Voi Promo2',
			'voi_promo2_code' => 'Voi Promo2 Code',
			'voi_promo2_amt' => 'Voi Promo2 Amt',
			'voi_price_surge_id' => 'Voi Price Surge',
			'voi_agent_commission' => 'Voi Agent Commission',
			'voi_cp_comm_type' => 'Voi Cp Comm Type',
			'voi_cp_comm_value' => 'Voi Cp Comm Value',
			'voi_chargeable_distance' => 'Voi Chargeable Distance',
			'voi_corporate_remunerator' => 'Voi Corporate Remunerator',
			'voi_partner_commission' => 'Voi Partner Commission',
			'voi_wallet_used' => 'Voi Wallet Used',
			'voi_temp_credits' => 'Voi Temp Credits',
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

		$criteria->compare('voi_id',$this->voi_id);
		$criteria->compare('voi_vor_id',$this->voi_vor_id);
		$criteria->compare('voi_base_amount',$this->voi_base_amount);
		$criteria->compare('voi_discount_amount',$this->voi_discount_amount);
		$criteria->compare('voi_total_amount',$this->voi_total_amount);
		$criteria->compare('voi_gozo_amount',$this->voi_gozo_amount);
		$criteria->compare('voi_corporate_credit',$this->voi_corporate_credit);
		$criteria->compare('voi_credits_used',$this->voi_credits_used);
		$criteria->compare('voi_advance_amount',$this->voi_advance_amount);
		$criteria->compare('voi_refund_amount',$this->voi_refund_amount);
		$criteria->compare('voi_cancel_refund',$this->voi_cancel_refund);
		$criteria->compare('voi_refund_approval_status',$this->voi_refund_approval_status);
		$criteria->compare('voi_due_amount',$this->voi_due_amount);
		$criteria->compare('voi_additional_charge',$this->voi_additional_charge);
		$criteria->compare('voi_additional_charge_remark',$this->voi_additional_charge_remark,true);
		$criteria->compare('voi_convenience_charge',$this->voi_convenience_charge);
		$criteria->compare('voi_service_tax',$this->voi_service_tax);
		$criteria->compare('voi_service_tax_rate',$this->voi_service_tax_rate);
		$criteria->compare('voi_igst',$this->voi_igst);
		$criteria->compare('voi_cgst',$this->voi_cgst);
		$criteria->compare('voi_sgst',$this->voi_sgst);
		$criteria->compare('voi_extra_charge',$this->voi_extra_charge);
		$criteria->compare('voi_cancel_charge',$this->voi_cancel_charge);
		$criteria->compare('voi_extra_km_charge',$this->voi_extra_km_charge);
		$criteria->compare('voi_corporate_discount',$this->voi_corporate_discount);
                $criteria->compare('voi_platform', $this->voi_platform);
		$criteria->compare('voi_promo1_id',$this->voi_promo1_id);
		$criteria->compare('voi_promo1_code',$this->voi_promo1_code,true);
		$criteria->compare('voi_promo1_amt',$this->voi_promo1_amt);
		$criteria->compare('voi_promo1_coins',$this->voi_promo1_coins);
		$criteria->compare('voi_promo2_id',$this->voi_promo2_id);
		$criteria->compare('voi_promo2_code',$this->voi_promo2_code,true);
		$criteria->compare('voi_promo2_amt',$this->voi_promo2_amt);
		$criteria->compare('voi_price_surge_id',$this->voi_price_surge_id);
		$criteria->compare('voi_agent_commission',$this->voi_agent_commission);
		$criteria->compare('voi_cp_comm_type',$this->voi_cp_comm_type);
		$criteria->compare('voi_cp_comm_value',$this->voi_cp_comm_value,true);
		$criteria->compare('voi_chargeable_distance',$this->voi_chargeable_distance);
		$criteria->compare('voi_corporate_remunerator',$this->voi_corporate_remunerator);
		$criteria->compare('voi_partner_commission',$this->voi_partner_commission);
		$criteria->compare('voi_wallet_used',$this->voi_wallet_used);
		$criteria->compare('voi_temp_credits',$this->voi_temp_credits);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VoucherOrderInvoice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getByOrderId($orderId)
	{
		$model = $this->find("voi_vor_id=:orderId", ['orderId' => $orderId]);
		return $model;
	}
}

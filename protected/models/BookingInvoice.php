<?php

/**
 * This is the model class for table "booking_invoice".
 *
 * The followings are the available columns in table 'booking_invoice':
 * @property integer $biv_id
 * @property integer $biv_bkg_id
 * @property integer $bkg_gozo_base_amount
 * @property integer $bkg_base_amount
 * @property integer $bkg_flexxi_base_amount
 * @property integer $bkg_discount_amount
 * @property integer $bkg_extra_discount_amount
 * @property integer $bkg_total_amount
 * @property integer $bkg_quoted_vendor_amount
 * @property integer $bkg_vendor_amount
 * @property integer $bkg_vendor_actual_collected
 * @property integer $bkg_vendor_collected
 * @property integer $bkg_gozo_amount
 * @property integer $bkg_corporate_credit
 * @property integer $bkg_credits_used
 * @property integer $bkg_advance_amount
 * @property integer $bkg_refund_amount
 * @property integer $bkg_cancel_refund
 * @property integer $biv_refund_approval_status
 * @property integer $bkg_due_amount
 * @property integer $bkg_driver_allowance_amount
 * @property integer $bkg_additional_charge
 * @property string $bkg_additional_charge_remark
 * @property integer $bkg_convenience_charge
 * @property integer $bkg_is_toll_tax_included
 * @property integer $bkg_toll_tax
 * @property double $bkg_extra_toll_tax
 * @property integer $bkg_is_state_tax_included
 * @property integer $bkg_state_tax
 * @property integer $bkg_is_parking_included
 * @property integer $bkg_parking_charge
 * @property integer $bkg_extra_state_tax
 * @property integer $bkg_service_tax
 * @property integer $bkg_service_tax_rate
 * @property integer $bkg_airport_entry_fee
 * @property integer $bkg_is_airport_fee_included
 * @property double $bkg_igst
 * @property double $bkg_cgst
 * @property double $bkg_sgst
 * @property integer $bkg_extra_charge
 * @property integer $bkg_cancel_charge
 * @property integer $bkg_cancel_gst
 * @property string $bkg_rate_per_km
 * @property string $bkg_rate_per_km_extra
 * @property integer $bkg_extra_km
 * @property integer $bkg_extra_total_km
 * @property integer $bkg_extra_km_charge
 * @property integer $bkg_corporate_discount
 * @property integer $bkg_agent_markup
 * @property integer $bkg_gozo_markup
 * @property integer $bkg_promo1_id
 * @property string $bkg_promo1_code
 * @property integer $bkg_promo1_amt
 * @property integer $bkg_promo1_coins
 * @property integer $bkg_promo2_id
 * @property string $bkg_promo2_code
 * @property integer $bkg_promo2_amt
 * @property integer $bkg_price_surge_id
 * @property integer $bkg_surge_amt
 * @property integer $bkg_markup_amt
 * @property integer $bkg_agent_commission
 * @property integer $bkg_cp_comm_type
 * @property integer $bkg_cp_comm_value
 * @property integer $bkg_chargeable_distance
 * @property integer $bkg_corporate_remunerator
 * @property integer $bkg_night_pickup_included
 * @property integer $bkg_night_drop_included
 * @property integer $bkg_surge_differentiate_amount
 * @property integer $bkg_is_wallet_selected
 * @property integer $bkg_wallet_used
 * @property integer $bkg_temp_credits
 * @property float $biv_quote_base_rate_km
 * @property integer $bkg_extra_total_min_charge
 * @property integer $bkg_extra_per_min_charge
 * @property integer $bkg_extra_min
 * @property float $bkg_vnd_compensation
 * @property string $bkg_vnd_compensation_date
 * @property integer $bkg_cust_compensation_amount
 * The followings are the available model relations:
 * @property Booking $bivBkg
 * @property Promos $bivPromos
 * @property integer $bkg_addon_charges
 * @property string $bkg_addon_ids
 * @property string $bkg_addon_details
 * @property integer $bkg_admin_fee
 * @property integer $bkg_partner_extra_commission
 * @property string $bkg_extra_charge_details

 */
class BookingInvoice extends CActiveRecord
{

	public $bkg_gozo_due, $bkg_vendor_due, $bkg_promo_code, $addonLabel		 = "", $bkg_addon_cab, $pickupdate;
	public $bkgFlexxiMinPay	 = 0;
	public $partialPayment, $isAdvPromoPaynow, $payubolt;
	public $optPaymentOptions, $paymentType, $ebsOpt, $optUseCredits;
	public $agentCreditAmount, $agentId, $gstCityId;
	public $valueType		 = [1 => "Percentage", 2 => "Amount"];
	public $bkg_extra_discount_reason;
	public $bkgZoneType, $from_date, $to_date, $bkgTypes, $sourcezone, $region, $state, $create_from_date, $create_to_date, $assignCount, $assignCountDrop, $lossCountDrop, $lossCount, $netMarginDrop, $netMargin, $vendorAmount, $b2cbookings, $mmtbookings, $nonAPIPartner, $excludeAT, $bkg_vehicle_type_id;

//	public $bkg_net_base_amount, $bkg_partner_commission;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_invoice';
	}

	public function save($runValidation = true)
	{
		return parent::save($runValidation, $this->getSafeAttributeNames());
	}

	public function update($attributes = null)
	{
		if ($attributes == null)
		{
			$attributes = $this->getSafeAttributeNames();
		}
		return parent::update($attributes);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
		return array(
			array('biv_bkg_id, bkg_advance_amount, bkg_refund_amount, bkg_due_amount, bkg_igst, bkg_cgst, bkg_sgst, bkg_surge_amt, bkg_markup_amt, bkg_agent_commission', 'required'),
			array('biv_bkg_id, bkg_gozo_base_amount, bkg_base_amount, bkg_flexxi_base_amount, bkg_discount_amount, bkg_total_amount, bkg_quoted_vendor_amount, bkg_vendor_amount, bkg_vendor_actual_collected, bkg_vendor_collected, bkg_gozo_amount, bkg_corporate_credit, bkg_credits_used, bkg_advance_amount, bkg_refund_amount, bkg_due_amount, bkg_driver_allowance_amount, bkg_additional_charge, bkg_convenience_charge, bkg_is_toll_tax_included, bkg_toll_tax, bkg_is_state_tax_included, bkg_state_tax, bkg_is_parking_included, bkg_parking_charge, bkg_extra_state_tax, bkg_service_tax, bkg_service_tax_rate, bkg_extra_charge, bkg_cancel_charge, bkg_extra_km, bkg_extra_total_km, bkg_extra_km_charge, bkg_corporate_discount, bkg_agent_markup, bkg_gozo_markup, bkg_promo1_amt, bkg_promo2_id, bkg_promo2_amt, bkg_price_surge_id, bkg_surge_amt, bkg_markup_amt, bkg_agent_commission, bkg_chargeable_distance, bkg_corporate_remunerator', 'numerical', 'integerOnly' => true),
			array('bkg_extra_toll_tax', 'numerical'),
			array('bkg_additional_charge_remark', 'length', 'max' => 250),
			array('bkg_rate_per_km, bkg_rate_per_km_extra', 'length', 'max' => 6),
			// The following rule is used by search().
// @todo Please remove those attributes that should not be searched.
			array('biv_id, biv_bkg_id, bkg_gozo_base_amount, bkg_base_amount, bkg_flexxi_base_amount, bkg_discount_amount, bkg_total_amount, bkg_quoted_vendor_amount, bkg_vendor_amount, bkg_vendor_actual_collected, bkg_vendor_collected, bkg_gozo_amount, bkg_corporate_credit, bkg_credits_used, bkg_advance_amount, bkg_refund_amount, bkg_due_amount, bkg_driver_allowance_amount, bkg_additional_charge, bkg_additional_charge_remark, bkg_convenience_charge, bkg_is_toll_tax_included, bkg_toll_tax, bkg_extra_toll_tax, bkg_is_state_tax_included, bkg_state_tax, bkg_is_parking_included, bkg_parking_charge, bkg_extra_state_tax, bkg_service_tax, bkg_service_tax_rate, bkg_igst, bkg_cgst, bkg_sgst, bkg_extra_charge, bkg_cancel_charge, bkg_rate_per_km, bkg_rate_per_km_extra, bkg_extra_km, bkg_extra_total_km, bkg_extra_km_charge, bkg_corporate_discount, bkg_agent_markup, bkg_gozo_markup, bkg_promo1_id, bkg_promo1_amt, bkg_promo2_id, bkg_promo2_amt, bkg_price_surge_id, bkg_surge_amt, bkg_markup_amt, bkg_agent_commission, bkg_chargeable_distance, bkg_corporate_remunerator,bkg_promo1_code,bkg_promo2_code', 'safe', 'on' => 'search'),
			// array('partialPayment', 'checkPartialPayment', 'on' => 'step3,advance_pay,lazypay'),
			array('bkg_advance_amount, bkg_due_amount', 'required', 'on' => 'updateadvance'),
			['agentCreditAmount', 'validateAgentCredit', 'on' => 'agentCreditUpdate'],
			['bkg_gozo_amount, bkg_vendor_collected', 'required', 'on' => 'vendor_collected_update'],
			array('bkg_discount_amount', 'checkDiscount', 'on' => 'step3, admininsert'),
			array('partialPayment', 'required', 'on' => 'step3Paytm'),
			array('bkg_additional_charge_remark', 'length', 'max' => 250),
			array('bkg_promo1_code,bkg_promo2_code', 'length', 'max' => 100),
			array('bkg_refund_amount', 'required', 'on' => 'updaterefund'),
			array('bkg_due_amount, bkg_advance_amount, bkg_refund_amount, bkg_service_tax, bkg_service_tax_rate, bkg_igst, bkg_cgst, bkg_sgst', 'numerical'),
			array('bkg_additional_charge_remark', 'length', 'max' => 250),
			array('bkg_promo_code', 'length', 'max' => 100),
			array('bkg_total_amount', 'required', 'on' => 'accountupdate'),
			array('partialPayment', 'checkPartialPayment', 'on' => 'step3,advance_pay,lazypay'),
			array('search,biv_id, biv_bkg_id, bkg_gozo_base_amount, bkg_base_amount, bkg_flexxi_base_amount, bkg_discount_amount, bkg_extra_discount_amount, bkg_total_amount,
 bkg_quoted_vendor_amount, bkg_vendor_amount, bkg_vendor_actual_collected, bkg_vendor_collected, bkg_gozo_amount, bkg_corporate_credit,bkg_cancel_refund,
bkg_credits_used, bkg_advance_amount, bkg_refund_amount, biv_refund_approval_status, bkg_due_amount, bkg_driver_allowance_amount, bkg_additional_charge,
bkg_additional_charge_remark, bkg_convenience_charge, bkg_is_toll_tax_included, bkg_toll_tax, bkg_extra_toll_tax, bkg_is_state_tax_included,
bkg_state_tax, bkg_is_parking_included, bkg_parking_charge, bkg_extra_state_tax,bkg_is_airport_fee_included,bkg_airport_entry_fee, bkg_service_tax, bkg_service_tax_rate, bkg_igst,
bkg_cgst, bkg_sgst, bkg_extra_charge, bkg_cancel_charge, bkg_cancel_gst, bkg_rate_per_km, bkg_rate_per_km_extra, bkg_extra_km, bkg_extra_total_km,
bkg_extra_km_charge, bkg_corporate_discount, bkg_agent_markup, bkg_gozo_markup, bkg_promo1_id, bkg_promo1_code, bkg_promo1_amt, bkg_promo1_coins,
bkg_promo2_id, bkg_promo2_code, bkg_promo2_amt, bkg_price_surge_id, bkg_surge_amt, bkg_markup_amt, bkg_agent_commission,
bkg_cp_comm_type, bkg_cp_comm_value, bkg_chargeable_distance, bkg_corporate_remunerator, bkg_night_pickup_included,
bkg_night_drop_included, bkg_ddbp_base_amount, bkg_ddbp_surge_factor, bkg_is_ddbp_surge, bkg_manual_base_amount,
bkg_is_manual_surge, bkg_surge_differentiate_amount,bkg_is_wallet_selected,bkg_wallet_used,bkg_temp_credits,bkg_cancel_addon_charge,bkg_addon_ids,biv_quote_base_rate_km,bkg_extra_min,bkg_extra_total_min_charge,bkg_extra_per_min_charge,bkg_admin_fee,bkg_addon_charges,bkg_addon_details,bkg_partner_extra_commission,bkg_vnd_compensation,bkg_vnd_compensation_date,bkg_extra_charge_details,bkg_cust_compensation_amount', 'safe'),
			['bkg_net_base_amount,bkg_net_discount_amount,bkg_partner_commission,bkg_net_advance_amount', 'unsafe'],
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
			'bivBkg'	 => array(self::BELONGS_TO, 'Booking', 'biv_bkg_id'),
			'bivPromos'	 => array(self::BELONGS_TO, 'Promos', 'bkg_promo1_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'biv_id'						 => 'Biv',
			'biv_bkg_id'					 => 'Biv Bkg',
			'bkg_gozo_base_amount'			 => 'Gozo Base Amount',
			'bkg_base_amount'				 => 'Base Amount',
			'bkg_flexxi_base_amount'		 => 'Flexxi Base Amount',
			'bkg_discount_amount'			 => 'Discount',
			'bkg_extra_discount_amount'		 => 'Extra Discount Amount',
			'bkg_total_amount'				 => 'Amount',
			'bkg_quoted_vendor_amount'		 => 'Quoted Vendor Amount',
			'bkg_vendor_amount'				 => 'Vendor Amount',
			'bkg_vendor_actual_collected'	 => 'Vendor Actual Collected',
			'bkg_vendor_collected'			 => 'Vendor Collected Amount',
			'bkg_gozo_amount'				 => 'Gozo Amount',
			'bkg_corporate_credit'			 => 'Corporate Credit',
			'bkg_credits_used'				 => 'Credits Used',
			'bkg_advance_amount'			 => 'Advance Amount',
			'bkg_refund_amount'				 => 'Refund Amount',
			'biv_refund_approval_status'	 => 'Refund Approval Status',
			'bkg_due_amount'				 => 'Due Amount',
			'bkg_driver_allowance_amount'	 => 'Driver Allowance Amount',
			'bkg_additional_charge'			 => 'Additional Charge',
			'bkg_additional_charge_remark'	 => 'Additional Charge Remark',
			'bkg_convenience_charge'		 => 'Convenience Charge',
			'bkg_is_toll_tax_included'		 => 'Is Toll Tax Included',
			'bkg_toll_tax'					 => 'Toll Tax',
			'bkg_extra_toll_tax'			 => 'Extra Toll Tax',
			'bkg_is_state_tax_included'		 => 'Is State Tax Included',
			'bkg_state_tax'					 => 'State Tax',
			'bkg_is_parking_included'		 => 'Is Parking Included',
			'bkg_parking_charge'			 => 'Parking',
			'bkg_extra_state_tax'			 => 'Extra State Tax',
			'bkg_is_airport_fee_included'	 => 'Is Airport Entry Fee Included',
			'bkg_airport_entry_fee'			 => 'Airport Entry Charges',
			'bkg_service_tax'				 => 'Service Tax',
			'bkg_service_tax_rate'			 => 'Service Tax Rate',
			'bkg_igst'						 => 'Igst',
			'bkg_cgst'						 => 'Cgst',
			'bkg_sgst'						 => 'Sgst',
			'bkg_extra_charge'				 => 'Extra Charge',
			'bkg_cancel_charge'				 => 'Cancel Charge',
			'bkg_rate_per_km'				 => 'Rate Per Km',
			'bkg_rate_per_km_extra'			 => 'Rate for extra km',
			'bkg_extra_km'					 => 'Extra Km',
			'bkg_extra_total_km'			 => 'Extra Total Km',
			'bkg_extra_km_charge'			 => 'Extra Km Charge',
			'bkg_corporate_discount'		 => 'Corporate Discount',
			'bkg_agent_markup'				 => 'Agent Markup',
			'bkg_gozo_markup'				 => 'gozo markup',
			'bkg_promo1_id'					 => 'Promo1',
			'bkg_promo1_code'				 => 'Promo1 Code',
			'bkg_promo1_amt'				 => 'Promo1 Amt',
			'bkg_promo2_id'					 => 'Promo2',
			'bkg_promo2_code'				 => 'Promo1 Code',
			'bkg_promo2_amt'				 => 'Promo2 Amt',
			'bkg_price_surge_id'			 => 'price Surge',
			'bkg_surge_amt'					 => 'Surge Amt',
			'bkg_markup_amt'				 => 'Markup Amt',
			'bkg_agent_commission'			 => 'Agent Commission',
			'bkg_chargeable_distance'		 => 'Chargeable Distance',
			'bkg_corporate_remunerator'		 => 'corporate remunerator',
			'bkg_is_wallet_selected'		 => 'Is wallet selected',
			'bkg_addon_charges'				 => 'Addon Charge',
			'bkg_addon_ids'					 => 'Addon Id',
			'bkg_vnd_compensation'			 => 'Vendor Compensation',
			'bkg_vnd_compensation_date'		 => 'Vendor Compensation Date',
			'bkg_cust_compensation_amount'	 => 'Customer Compensation'
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

		$criteria->compare('biv_id', $this->biv_id);
		$criteria->compare('biv_bkg_id', $this->biv_bkg_id);
		$criteria->compare('bkg_gozo_base_amount', $this->bkg_gozo_base_amount);
		$criteria->compare('bkg_base_amount', $this->bkg_base_amount);
		$criteria->compare('bkg_flexxi_base_amount', $this->bkg_flexxi_base_amount);
		$criteria->compare('bkg_discount_amount', $this->bkg_discount_amount);
		$criteria->compare('bkg_total_amount', $this->bkg_total_amount);
		$criteria->compare('bkg_quoted_vendor_amount', $this->bkg_quoted_vendor_amount);
		$criteria->compare('bkg_vendor_amount', $this->bkg_vendor_amount);
		$criteria->compare('bkg_vendor_actual_collected', $this->bkg_vendor_actual_collected);
		$criteria->compare('bkg_vendor_collected', $this->bkg_vendor_collected);
		$criteria->compare('bkg_gozo_amount', $this->bkg_gozo_amount);
		$criteria->compare('bkg_corporate_credit', $this->bkg_corporate_credit);
		$criteria->compare('bkg_credits_used', $this->bkg_credits_used);
		$criteria->compare('bkg_advance_amount', $this->bkg_advance_amount);
		$criteria->compare('bkg_refund_amount', $this->bkg_refund_amount);
		$criteria->compare('bkg_cancel_refund', $this->bkg_cancel_refund);
		$criteria->compare('biv_refund_approval_status', $this->biv_refund_approval_status);
		$criteria->compare('bkg_due_amount', $this->bkg_due_amount);
		$criteria->compare('bkg_driver_allowance_amount', $this->bkg_driver_allowance_amount);
		$criteria->compare('bkg_additional_charge', $this->bkg_additional_charge);
		$criteria->compare('bkg_additional_charge_remark', $this->bkg_additional_charge_remark, true);
		$criteria->compare('bkg_convenience_charge', $this->bkg_convenience_charge);
		$criteria->compare('bkg_is_toll_tax_included', $this->bkg_is_toll_tax_included);
		$criteria->compare('bkg_toll_tax', $this->bkg_toll_tax);
		$criteria->compare('bkg_extra_toll_tax', $this->bkg_extra_toll_tax);
		$criteria->compare('bkg_is_state_tax_included', $this->bkg_is_state_tax_included);
		$criteria->compare('bkg_state_tax', $this->bkg_state_tax);
		$criteria->compare('bkg_is_parking_included', $this->bkg_is_parking_included);
		$criteria->compare('bkg_parking_charge', $this->bkg_parking_charge);
		$criteria->compare('bkg_extra_state_tax', $this->bkg_extra_state_tax);
		$criteria->compare('bkg_service_tax', $this->bkg_service_tax);
		$criteria->compare('bkg_service_tax_rate', $this->bkg_service_tax_rate);
		$criteria->compare('bkg_is_airport_fee_included', $this->bkg_is_airport_fee_included);
		$criteria->compare('bkg_airport_entry_fee', $this->bkg_airport_entry_fee);
		$criteria->compare('bkg_igst', $this->bkg_igst);
		$criteria->compare('bkg_cgst', $this->bkg_cgst);
		$criteria->compare('bkg_sgst', $this->bkg_sgst);
		$criteria->compare('bkg_extra_charge', $this->bkg_extra_charge);
		$criteria->compare('bkg_cancel_charge', $this->bkg_cancel_charge);
		$criteria->compare('bkg_rate_per_km', $this->bkg_rate_per_km, true);
		$criteria->compare('bkg_rate_per_km_extra', $this->bkg_rate_per_km_extra, true);
		$criteria->compare('bkg_extra_km', $this->bkg_extra_km);
		$criteria->compare('bkg_extra_total_km', $this->bkg_extra_total_km);
		$criteria->compare('bkg_extra_km_charge', $this->bkg_extra_km_charge);
		$criteria->compare('bkg_corporate_discount', $this->bkg_corporate_discount);
		$criteria->compare('bkg_agent_markup', $this->bkg_agent_markup);
		$criteria->compare('bkg_gozo_markup', $this->bkg_gozo_markup);
		$criteria->compare('bkg_promo1_id', $this->bkg_promo1_id);
		$criteria->compare('bkg_promo1_code', $this->bkg_promo1_code);
		$criteria->compare('bkg_promo1_amt', $this->bkg_promo1_amt);
		$criteria->compare('bkg_promo2_id', $this->bkg_promo2_id);
		$criteria->compare('bkg_promo2_code', $this->bkg_promo2_code);
		$criteria->compare('bkg_promo2_amt', $this->bkg_promo2_amt);
		$criteria->compare('bkg_price_surge_id', $this->bkg_price_surge_id);
		$criteria->compare('bkg_surge_amt', $this->bkg_surge_amt);
		$criteria->compare('bkg_markup_amt', $this->bkg_markup_amt);
		$criteria->compare('bkg_agent_commission', $this->bkg_agent_commission);
		$criteria->compare('bkg_chargeable_distance', $this->bkg_chargeable_distance);
		$criteria->compare('bkg_corporate_remunerator', $this->bkg_corporate_remunerator);
		$criteria->compare('bkg_night_pickup_included', $this->bkg_night_pickup_included, true);
		$criteria->compare('bkg_night_drop_included', $this->bkg_night_drop_included);
		$criteria->compare('bkg_addon_charges', $this->bkg_addon_charges);
		$criteria->compare('bkg_addon_ids', $this->bkg_addon_ids);
		$criteria->compare('bkg_vnd_compensation', $this->bkg_vnd_compensation);
		$criteria->compare('bkg_vnd_compensation_date', $this->bkg_vnd_compensation_date);
		$criteria->compare('bkg_cust_compensation_amount', $this->bkg_cust_compensation_amount);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingInvoice the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/** @return BookingInvoice */
	public function getByBookingID($bkgId)
	{
		$model = $this->find("biv_bkg_id=:bkgId", ['bkgId' => $bkgId]);
		return $model;
	}

	public function add()
	{
		if ($this->biv_bkg_id == null)
		{
			throw new Exception("Booking ID not linked", 500);
		}
		$this->biv_bkg_id	 = $this->bivBkg->bkg_id;
		$this->populateAmount(true, true, true, false, $this->bivBkg->bkg_agent_id);
		$result				 = $this->save();
		if (!$result)
		{
			throw new Exception(CJSON::encode($this->getErrors()), 1);
		}
		return true;
	}

	public function populateAmount($markup = true, $cod = false, $totamt = true, $vndamt = true, $agentId = '',$arr='')
	{
		$this->initializePartnerCommission($agentId);
		if ($markup)
		{
			$this->calculateAgentMarkup($agentId);
		}
		if ($cod)
		{
			$this->calculateConvenienceFee();
		}
		if ($vndamt)
		{
			$this->calculateVendorAmount();
		}
		if ($totamt)
		{
			$this->calculateTotal($arr);
		}
	}

	/**
	 *
	 * @param integer $cancelAmount
	 * @return $this
	 */
	public function populateCancelCharges($cancelAmount, $partnerId, $tripType)
	{
		//$tax_rate				 = $this->getServiceTaxRate();
		$tax_rate				 = self::getGstTaxRate($partnerId, $tripType);
		$baseCanceCharge		 = round($cancelAmount / (1 + $tax_rate / 100));
		$cancelGST				 = round($cancelAmount - $baseCanceCharge);
		$this->bkg_cancel_gst	 = $cancelGST;
		$this->bkg_cancel_charge = $baseCanceCharge;
		return $this;
	}

	/**
	 *
	 * @param integer $cancelCharge
	 * @return boolean
	 */
	public function processCancelCharge($cancelCharge, $partnerId)
	{
		$success	 = false;
		$transaction = DBUtil::beginTransaction();
		try
		{
			$this->populateCancelCharges($cancelCharge, $partnerId, $this->bivBkg->bkg_booking_type);
			$this->save();

			$cancelDate	 = $this->bivBkg->bkgTrail->btr_cancel_date;
			AccountTransactions::AddCancellationCharge($this->biv_bkg_id, $cancelDate, $cancelCharge);
			DBUtil::commitTransaction($transaction);
			$success	 = true;
		}
		catch (Exception $exc)
		{
			DBUtil::rollbackTransaction($transaction);
			Logger::exception($exc);
			$this->bivBkg->bkgPref->setAccountingFlag($exc->getMessage(), null);
		}
		return $success;
	}

	public function calculateAgentMarkup($agentId = '')
	{

		$this->bkg_agent_markup = 0;
		if ($agentId)
		{
			$agtModel = Agents::model()->findByPk($agentId);
			$this->calculateGozoMarkup($agtModel);
		}
	}

	public function initializePartnerCommission($agentId)
	{

		if ($this->isNewRecord && $agentId > 0)
		{
			$ptsModel = PartnerSettings::getValueById($agentId);
			if ($ptsModel != '')
			{
				$arrRules		 = CJSON::decode(trim($ptsModel['pts_additional_param'], "'"));
				$conVal			 = Config::get('booking.local.type');
				$bkglocalType	 = CJSON::decode($conVal);

				$this->bkg_cp_comm_type	 = $arrRules['outstation']['commissionType'];
				$this->bkg_cp_comm_value = $arrRules['outstation']['commissionValue'];

				if (in_array($this->bivBkg->bkg_booking_type, $bkglocalType))
				{
					$this->bkg_cp_comm_type	 = $arrRules['local']['commissionType'];
					$this->bkg_cp_comm_value = $arrRules['local']['commissionValue'];
				}
			}
			else
			{
				$agtModel = Agents::model()->findByPk($agentId);
				if ($agtModel->agt_type == 2)
				{
					$this->bkg_cp_comm_type	 = $agtModel->agt_commission_value;
					$this->bkg_cp_comm_value = $agtModel->agt_commission;
				}
			}
		}
	}

	public function calculateConvenienceFee($fee = '',$arr='')
	{
		$this->bkg_convenience_charge	 = 0;
//  $fee = 0; //to set COD Zero
		$gross_amount					 = $this->calculateGrossAmount();
//        $conFee1 = $gross_amount * 0.15;
//        $conFee2 = 499;
		$conFee1						 = $gross_amount * 0.05;
		$conFee2						 = 249;
//        $conFee1 = $gross_amount * 0.10;
//        $conFee2 = 499;
		if ($conFee1 > $conFee2)
		{
			$conFee = $conFee2;
		}
		else
		{
			$conFee = $conFee1;
		}
		if ($fee === '')
		{
			$this->bkg_convenience_charge = round($conFee);
		}
		else
		{
			$this->bkg_convenience_charge = round($fee);
		}
		$this->calculateServiceTax($arr);
	}

	public function calculateTotal($arr="")
	{
		$this->calculateServiceTax($arr);
		$this->bkg_driver_allowance_amount	 = ($this->bkg_driver_allowance_amount == '') ? 0 : $this->bkg_driver_allowance_amount;
		$this->bkg_total_amount				 = $this->calculateGrossAmount() + $this->getTotalTaxes() + $this->bkg_parking_charge + $this->bkg_driver_allowance_amount;
		$this->calculateDues();
	}

	public function getTotalTaxes()
	{
		$taxes = $this->bkg_service_tax + $this->bkg_toll_tax + $this->bkg_state_tax + $this->bkg_extra_toll_tax + $this->bkg_extra_state_tax + $this->bkg_airport_entry_fee;
		return $taxes;
	}

	public function calculateDues()
	{
		$this->bkg_vendor_due	 = $this->bkg_vendor_amount - $this->bkg_vendor_collected;
		$this->bkg_gozo_amount	 = $this->bkg_total_amount - $this->bkg_vendor_amount - $this->bkg_service_tax - $this->bkg_partner_commission;
		$this->bkg_gozo_due		 = $this->bkg_gozo_amount + $this->bkg_service_tax + $this->bkg_partner_commission - $this->getAdvanceReceived();
		$this->bkg_due_amount	 = $this->bkg_total_amount - $this->getTotalPayment();
	}

	public function calculateVendorAmount()
	{
		if ($this->bkg_vendor_amount == '')
		{
			$this->bkg_vendor_amount = round(($this->bkg_gozo_base_amount * 0.9) + $this->bkg_additional_charge + $this->bkg_driver_allowance_amount + ($this->bkg_extra_km_charge * 0.9));
			$this->bkg_vendor_amount = round($this->bkg_vendor_amount + $this->getVendorShareExtraCharges());
		}
	}

	public function calculateGrossAmount()
	{
		#print_r($this);exit;
		$this->bkg_net_discount_amount		 = ($this->bkg_net_discount_amount == '') ? 0 : $this->bkg_net_discount_amount;
		$this->bkg_additional_charge		 = ($this->bkg_additional_charge == '') ? 0 : $this->bkg_additional_charge;
		$this->bkg_driver_allowance_amount	 = ($this->bkg_driver_allowance_amount == '') ? 0 : $this->bkg_driver_allowance_amount;
		$this->bkg_extra_km_charge			 = ($this->bkg_extra_km_charge == '') ? 0 : $this->bkg_extra_km_charge;
		$this->bkg_addon_charges			 = ($this->bkg_addon_charges == '') ? 0 : $this->bkg_addon_charges;
		$this->bkg_extra_total_min_charge	 = ($this->bkg_extra_total_min_charge == '') ? 0 : $this->bkg_extra_total_min_charge;
		#echo $this->getTotalDiscount();exit;
		#print_r($this);
		$gross_amount						 = $this->bkg_base_amount + $this->bkg_additional_charge - $this->getTotalDiscount() + $this->bkg_extra_km_charge + $this->bkg_addon_charges + $this->bkg_extra_total_min_charge + $this->bkg_extra_charge;
		#echo $gross_amount;
		#exit;
		if ($this->bkg_convenience_charge != '')
		{
			$gross_amount = $gross_amount + $this->bkg_convenience_charge;
		}
		return $gross_amount;
	}

	public function getTotalDiscount()
	{
		$totalDiscountAmt = ($this->bkg_discount_amount + $this->bkg_extra_discount_amount);
		return $totalDiscountAmt;
	}

	public function calculateGozoMarkup($agtModel)
	{
		$this->bkg_gozo_markup = 0;
		if ($agtModel->agt_id)
		{
			$gross_amount			 = $this->calculateGrossAmount();
			$gozoCommision			 = $agtModel->agt_gozo_commission | 0;
			$this->bkg_gozo_markup	 = ($agtModel->agt_gozo_commission_value == 1) ? round(($gozoCommision * $gross_amount) / 100) : $gozoCommision;
		}
	}

	public function calculateServiceTax($arr='')
	{
        $arr;
        $bkg_agent_id     = ($this->bivBkg->bkg_agent_id == null) ? $arr['bkg_agent_id'] : $this->bivBkg->bkg_agent_id;
        $bkg_booking_type = ($this->bivBkg->bkg_booking_type == null) ? $arr['bkg_booking_type'] : $this->bivBkg->bkg_booking_type;
        $bkg_pickup_date = ($this->bivBkg->bkg_pickup_date == null) ? $arr['bkg_pickup_date'] : $this->bivBkg->bkg_pickup_date;

        //$tax_rate				 = $this->getServiceTaxRate();
		//$tax_rate				 = self::getGstTaxRate($this->bivBkg->bkg_agent_id, $this->bivBkg->bkg_booking_type);
        $tax_rate				 = self::getGstTaxRate($bkg_agent_id, $bkg_booking_type);
		$gross_amount			 = $this->calculateGrossAmount();
		//$pickupDate				 = ($this->bivBkg->bkg_pickup_date == '') ? $this->pickupdate : $this->bivBkg->bkg_pickup_date;
        $pickupDate				 = ($bkg_pickup_date == '') ? $this->pickupdate : $bkg_pickup_date;
		$checkNewGstPickupTime	 = Booking::model()->checkNewGstPickupTime($pickupDate);
		if ($checkNewGstPickupTime)
		{
			/* by ankesh */
			$gross_amount = $gross_amount + $this->bkg_toll_tax + $this->bkg_state_tax + $this->bkg_extra_toll_tax + $this->bkg_extra_state_tax + $this->bkg_driver_allowance_amount + $this->bkg_parking_charge + $this->bkg_airport_entry_fee;
		}
		else
		{
			$gross_amount = $gross_amount + $this->bkg_driver_allowance_amount;
		}
		$this->bkg_service_tax		 = round($gross_amount * $tax_rate * 0.01);
		$this->bkg_service_tax_rate	 = $tax_rate;
		$this->getGSTRate();
	}

	public function getServiceTaxRate()
	{
		$tax_rate = $this->bkg_service_tax_rate;

		if (!$tax_rate)
		{
			$tax_rate	 = $this->bkg_service_tax_rate	 = self::getGstTaxRate($this->bivBkg->bkg_agent_id, $this->bivBkg->bkg_booking_type);
		}
		return $tax_rate;
	}

	public function getGSTRate()
	{
// GST
		$this->bkg_cgst	 = 0;
		$this->bkg_sgst	 = 0;
		$this->bkg_igst	 = ($this->bkg_service_tax == 0) ? 0 : Yii::app()->params['igst'];
		if ($this->gstCityId == Yii::app()->params['taxes']['GST']['city'])
		{
			$this->bkg_cgst	 = Yii::app()->params['cgst'];
			$this->bkg_sgst	 = Yii::app()->params['sgst'];
			$this->bkg_igst	 = 0;
		}
	}

	public function getTotalPayment()
	{
		$includeTempCredits	 = ($this->bkg_temp_credits > 0 || $this->bkg_credits_used > 0) ? true : false;
		$total_payment		 = $this->getAdvanceReceived($includeTempCredits) + $this->bkg_vendor_collected;
		return $total_payment;
	}

	public function getExtraCollected()
	{
		$extra		 = $this->grossExtraCharges();
		$staxrate	 = self::getGstTaxRate($this->bivBkg->bkg_agent_id, $this->bivBkg->bkg_booking_type);
		$extra		 += round($this->bkg_extra_km_charge * $staxrate * 0.01);
		return $extra;
	}

	public function getVendorShareExtraCharges()
	{
		$extra	 = $this->grossExtraCharges();
		$extra	 -= ($this->bkg_extra_km_charge * 0.10);
		return $extra;
	}

	public function grossExtraCharges()
	{

		return ($this->bkg_extra_km_charge + $this->bkg_parking_charge + $this->bkg_extra_state_tax + $this->bkg_extra_toll_tax + $this->bkg_extra_total_min_charge);
	}

	public function getAdvanceReceived($includeGozoCoins = true)
	{
		$isConfirmed = false;
		if ($bkgModel	 = $this->bivBkg)
		{

			$isConfirmed = (in_array($bkgModel->bkg_status, [2, 3, 5, 6, 7, 9]) || $bkgModel->bkg_reconfirm_flag == 1);
		}
		$gozoCoins = ($this->bkg_temp_credits > 0 && !$isConfirmed) ? $this->bkg_temp_credits : $this->bkg_credits_used;
		if ($includeGozoCoins == false)
		{
			$gozoCoins = 0;
		}
		$advance_received = ($this->bkg_advance_amount + $gozoCoins) - $this->bkg_refund_amount;
		return $advance_received;
	}

	/* ----Service Tier Phase 2 Checked------- */

	public function calculateMinPayment($total = 0, $chkWallet = true)
	{
		$amount	 = 0;
		$model	 = clone $this;

		if ($model->bivBkg->bkg_status == 15 && $model->bkg_discount_amount == 0)
		{
			$model->evaluatePromo($this->bivPromos);
		}

		if ($total > 0)
		{
			$amount = $total;
		}
		else
		{
			$amount = $model->bkg_due_amount;
		}

		$minPayPerc = (Config::getMinAdvancePercent($model->bivBkg->bkg_agent_id, $model->bivBkg->bkg_booking_type, $model->bivBkg->bkgSvcClassVhcCat->scc_ServiceClass->scc_id, $model->bivBkg->bkgPref->bkg_is_gozonow)) / 100;
		if ($this->bivBkg->bkg_cav_id > 0)
		{
			$minPayPerc = 0.5;
		}

		$minPay			 = round($amount * $minPayPerc);
		$walletApplied	 = 0;
		if ($model->bkg_wallet_used > 0 && $model->bkg_is_wallet_selected == 1 && $chkWallet)
		{
			$walletApplied			 = $model->bkg_wallet_used;
			$dueWithoutWalletApply	 = $amount + $walletApplied;
			$minPayReCalculate		 = round($dueWithoutWalletApply * $minPayPerc);
			$minPay					 = max([$minPayReCalculate - $walletApplied, 0]);
		}


		$bookingPref = BookingPref::model()->find('bpr_bkg_id=:bkg_id', ['bkg_id' => $this->bivBkg->bkg_id]);
		if ($bookingPref != '' && $bookingPref->bkg_isfullpayment == 1 && ($this->bivBkg->bkg_agent_id == '' || $this->bivBkg->bkg_agent_id == 0))
		{
			$minPay = round($amount * 0.30);
		}
		$scvVctId = SvcClassVhcCat::model()->getCatIdBySvcid($this->bivBkg->bkg_vehicle_type_id);
		if ($this->bivBkg->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC)
		{
			if ($this->bivBkg->bkg_flexxi_type == 1)
			{
				$result	 = $model->calculatePromoterFare();
				$minPay	 = $result->totalAmount;
			}
			else
			{
//$minPay = $this->bkg_total_amount;
				if ($this->bkgFlexxiMinPay == 0)
				{
					$minPay = $model->bkg_total_amount - $model->bkg_credits_used - $model->bkg_discount_amount;
				}
				else
				{
					$minPay = $model->bkg_total_amount;
				}
			}
		}
		return $minPay;
	}

	/**
	 * function calculate extra time cap
	 * @param type $sccId service tier id
	 * @return type
	 */
	public static function calculateExtraTimeCap($sccId)
	{
		$defaultClass	 = \Yii::app()->params['defaultClass'];
		$svcClassId		 = ($defaultClass == 1 ? 0 : $sccId);
		$timeDiff		 = json_decode(\Config::get("dayRental.timeSlot"));
		$extraTimeCap	 = $timeDiff->$svcClassId;
		return $extraTimeCap;
	}

	public function calculatePromoterFare($subsBkgId = 0, $cancel = false)
	{
//$vtData = VehicleTypes::model()->getModelDetailsbyId($this->bivBkg->bkg_vehicle_type_id);
		$vtData = VehicleCategory::model()->getModelDetailsById($this->bivBkg->bkgSvcClassVhcCat->scv_vct_id);
		if ($subsBkgId > 0)
		{
			$totUnusedSeatsBefore	 = 0;
			$subNoPerson			 = 0;
// $sql                  = 'SELECT SUM(bkg_no_person) totSubsSeats FROM booking WHERE bkg_fp_id=' . $this->bkg_id . ' AND bkg_active=1 AND bkg_flexxi_type=2 AND bkg_status IN(2,3,5) AND bkg_id<>' . $subsBkgId . ' GROUP BY bkg_fp_id';
			$sql					 = 'SELECT SUM(bkadd.bkg_no_person) totSubsSeats FROM `booking` bk INNER JOIN `booking_add_info` bkadd ON bk.bkg_id=bkadd.bad_bkg_id WHERE bk.bkg_fp_id=' . $this->biv_bkg_id . ' AND bk.bkg_active=1 AND bk.bkg_flexxi_type=2 AND bk.bkg_status IN(2,3,5) AND bk.bkg_id<>' . $subsBkgId . ' GROUP BY bk.bkg_fp_id';
			if ($cancel)
			{
				$subNoPerson = Booking::model()->findByPk($subsBkgId)->bkgAddInfo->bkg_no_person;
			}
			$subsBookingBefore		 = DBUtil::command($sql)->queryScalar();
			$val					 = (!$subsBookingBefore) ? '0' : $subsBookingBefore;
			$totUnusedSeatsBefore	 = $vtData['vct_capacity'] - ($val + $subNoPerson);

			$totUnusedSeats	 = 0;
// $sql            = 'SELECT SUM(bkg_no_person) totSubsSeats FROM booking WHERE bkg_fp_id=' . $this->bkg_id . ' AND bkg_active=1 AND bkg_flexxi_type=2 AND bkg_status IN(2,3,5) GROUP BY bkg_fp_id';
			$sql			 = 'SELECT SUM(bkadd.bkg_no_person) totSubsSeats FROM `booking` bk INNER JOIN `booking_add_info` bkadd ON bk.bkg_id=bkadd.bad_bkg_id WHERE bk.bkg_fp_id=' . $this->biv_bkg_id . ' AND bk.bkg_active=1 AND bk.bkg_flexxi_type=2 AND bk.bkg_status IN(2,3,5) GROUP BY bk.bkg_fp_id';
			$subsBooking	 = DBUtil::command($sql)->queryScalar();
			$val			 = (!$subsBooking) ? '0' : $subsBooking;
			$totUnusedSeats	 = $vtData['vct_capacity'] - $val;
		}

		$quoteModel								 = new Quote();
		$quoteModel->flexxi_type				 = 1;
		$quoteModel->routeRates					 = new RouteRates();
		$quoteModel->routeRates->baseAmount		 = $this->bkg_flexxi_base_amount;
		$quoteModel->routeRates->vendorAmount	 = $this->bkg_vendor_amount;
		$quoteModel->routeRates->tollTaxAmount	 = $this->bkg_toll_tax;
		$quoteModel->routeRates->stateTax		 = $this->bkg_state_tax;
		$quoteModel->routeRates->driverAllowance = $this->bkg_driver_allowance_amount;
		if ($totUnusedSeatsBefore > 0)
		{
			$quoteModel->routeRates->tollTaxAmount	 = ROUND(($this->bkg_toll_tax / $totUnusedSeatsBefore) * $vtData['vct_capacity']);
			$quoteModel->routeRates->stateTax		 = ROUND(($this->bkg_state_tax / $totUnusedSeatsBefore) * $vtData['vct_capacity']);
			$quoteModel->routeRates->driverAllowance = ROUND(($this->bkg_driver_allowance_amount / $totUnusedSeatsBefore) * $vtData['vct_capacity']);
		}
		$quoteModel->routeRates->totalAmount = $this->bkg_total_amount;
		$cabtypeid							 = $this->bivBkg->bkg_vehicle_type_id;
		$noofperson							 = $this->bivBkg->bkgAddInfo->bkg_no_person;
		if ($totUnusedSeats > 0)
		{
			$noofperson = $totUnusedSeats;
		}
		$quoteModel->calculateFlexxiFare($cabtypeid, $noofperson);
		return $quoteModel->routeRates;
	}

	public function calculateAmount()
	{

		$net_amount					 = $this->bkg_base_amount;
		$net_amount					 = ($net_amount == '') ? 0 : $net_amount;
		$this->bkg_additional_charge = ($this->bkg_additional_charge == '') ? 0 : $this->bkg_additional_charge;
		$this->bkg_discount_amount	 = ($this->bkg_discount_amount == '') ? 0 : $this->bkg_discount_amount;
		$this->bkg_refund_amount	 = ($this->bkg_refund_amount == '') ? 0 : $this->bkg_refund_amount;
		$tax_rate					 = $this->bkg_service_tax_rate;

		if (!$tax_rate)
		{
			$tax_rate					 = $this->bkg_service_tax_rate	 = BookingInvoice::getGstTaxRate($this->bivBkg->bkg_agent_id, $this->bivBkg->bkg_booking_type);
		}
		$preSTax				 = $net_amount + $this->bkg_toll_tax + $this->bkg_state_tax + $this->bkg_extra_toll_tax + $this->bkg_extra_state_tax + $this->bkg_driver_allowance_amount + $this->bkg_parking_charge + $this->bkg_additional_charge - $this->bkg_discount_amount;
		$tax_amount				 = $preSTax * $tax_rate / 100;
		$this->bkg_service_tax	 = $tax_amount;
		$this->bkg_vendor_amount = ($this->bkg_vendor_amount > 0) ? $this->bkg_vendor_amount : round(($this->bkg_base_amount + $this->bkg_additional_charge ) * 0.9);

		$total_amount = $preSTax + $tax_amount;

		$this->bkg_credits_used	 = $credits_used			 = ($this->bkg_credits_used != '' && $this->bkg_credits_used ) ? $this->bkg_credits_used : 0;
		$this->bkg_total_amount	 = $total_amount;
		$this->bkg_due_amount	 = $this->bkg_total_amount - $this->getTotalPayment();
	}

	public function getAmountCalculationfromGozoBaseAmount()
	{
		$gozo_base_amount					 = $this->bkg_base_amount;
		$this->bkg_discount_amount			 = ($this->bkg_discount_amount == '') ? 0 : $this->bkg_discount_amount;
		$this->bkg_refund_amount			 = ($this->bkg_refund_amount == '') ? 0 : $this->bkg_refund_amount;
		$this->bkg_advance_amount			 = ($this->bkg_advance_amount == '') ? 0 : $this->bkg_advance_amount;
		$this->bkg_driver_allowance_amount	 = ($this->bkg_driver_allowance_amount == '') ? 0 : $this->bkg_driver_allowance_amount;
		$tax_rate							 = $this->getServiceTaxRate();
		//$tax_rate							 = self::getGstTaxRate($this->bivBkg->bkg_agent_id);

		$this->bkg_credits_used	 = $credits_used			 = ($this->bkg_credits_used != '' && $this->bkg_credits_used ) ? $this->bkg_credits_used : 0;
		$gozo_markup			 = 0;
		$agent_markup			 = 0;
		if ($this->bivBkg->bkg_agent_id && $this->bivBkg->bkgAgent)
		{
			$commisionType	 = $this->bivBkg->bkgAgent->agt_commission_value;
			$commision		 = $this->bivBkg->bkgAgent->agt_commission | 0;
			if ($this->bivBkg->bkgAgent->agt_type == 0 || $this->bivBkg->bkgAgent->agt_type == 1)
			{
				$commision = 0;
			}
			$gozoCommisionType	 = $this->bivBkg->bkgAgent->agt_gozo_commission_value;
			$gozoCommision		 = $this->bivBkg->bkgAgent->agt_gozo_commission | 0;
			$agent_markup		 = ($commisionType == 1) ? round(($commision * $gozo_base_amount) / 100) : $commision;
			$gozo_markup		 = ($gozoCommisionType == 1) ? round(($gozoCommision * $gozo_base_amount) / 100) : $gozoCommision;
		}
		$this->bkg_agent_markup	 = $agent_markup;
		$this->bkg_gozo_markup	 = $gozo_markup;
		$this->bkg_base_amount	 = $gozo_base_amount + $agent_markup + $gozo_markup;
		$preSTax				 = $this->bkg_base_amount + $this->bkg_additional_charge - $this->bkg_discount_amount + $this->bkg_driver_allowance_amount;
		$this->bkg_service_tax	 = round(($preSTax + $this->bkg_toll_tax + $this->bkg_state_tax + $this->bkg_extra_toll_tax + $this->bkg_extra_state_tax + $this->bkg_driver_allowance_amount + $this->bkg_parking_charge) * $tax_rate / 100);
		$this->bkg_vendor_amount = round(($this->bkg_gozo_base_amount * 0.9) + $this->bkg_additional_charge + $this->bkg_driver_allowance_amount);
		$this->bkg_total_amount	 = $preSTax + $this->bkg_service_tax;
		$this->bkg_gozo_amount	 = $this->bkg_total_amount - $this->bkg_vendor_amount;
		$this->bkg_due_amount	 = $this->bkg_total_amount - $this->getTotalPayment();
	}

	/* ----Service Tier Phase 2 Checked------- */

	public function getFlexxiProfitAmount($bcbId)
	{
//$sql    = 'SELECT ROUND((SUM(bkg_base_amount)-ROUND(AVG(bkg_flexxi_base_amount)))*0.25)   FROM `booking` WHERE bkg_vehicle_type_id=114 AND bkg_bcb_id = ' . $bcbId . ' AND bkg_status NOT IN(8,9,10,1,13) AND bkg_active=1 GROUP BY bkg_bcb_id';
		$sql	 = 'SELECT ROUND((SUM(bki.bkg_base_amount)-ROUND(AVG(bki.bkg_flexxi_base_amount)))*0.25) FROM `booking` as bkg INNER JOIN `booking_invoice` as bki ON bkg.bkg_id = bki.biv_bkg_id WHERE bkg.bkg_vehicle_type_id=' . VehicleCategory::SHARED_SEDAN_ECONOMIC . ' AND bkg.bkg_bcb_id = ' . $bcbId . ' AND bkg.bkg_status NOT IN(8,9,10,1,13) AND bkg.bkg_active=1 GROUP BY bkg.bkg_bcb_id';
		$profit	 = DBUtil::command($sql)->queryScalar();
		return ($profit > 0) ? (int) $profit : 0;
	}

	public function validateAgentCredit($attribute, $params)
	{
		if ($this->agentCreditAmount > $this->bkg_total_amount)
		{
			$this->addError('agentCreditAmount', 'Agent payment amount exceeding total booking amount');
			return FALSE;
		}
	}

	public function checkDiscount($attribute, $params)
	{
		if ($this->bkg_discount_amount > 0)
		{
			$dueamount = ($this->bkg_advance_amount > 0) ? $this->bkg_due_amount : $this->bkg_base_amount;
			if ($this->bkg_discount_amount > $dueamount)
			{
				$this->addError($attribute, 'Discount amount is greater than amount to be paid');
				return false;
			}
		}
		return true;
	}

	public function checkPartialPayment($attribute, $params)
	{
		$this->refresh();
		$modelZeroConv = clone $this;
		if ($modelZeroConv->bkg_promo1_id > 0)
		{
//$promoModel1 = Promos::model()->getByCode($modelZeroConv->bkg_promo1_code);
			$promoModel1 = Promos::model()->findByPk($modelZeroConv->bkg_promo1_id);
			if (!$promoModel1)
			{
				throw new Exception('Invalid Promo code');
			}
			if (($promoModel1->prm_activate_on == 1 || $promoModel1->prm_applicable_nexttrip == 1) && ($promoModel1->prmCal->pcn_type == 1 || $promoModel1->prmCal->pcn_type == 3) && $modelZeroConv->isAdvPromoPaynow != 1)
			{
				$promoModel1->promoCode		 = $modelZeroConv->bkg_promo1_code;
				$promoModel1->totalAmount	 = $modelZeroConv->bkg_base_amount;
				$promoModel1->createDate	 = $modelZeroConv->bivBkg->bkg_create_date;
				$promoModel1->pickupDate	 = $modelZeroConv->bivBkg->bkg_pickup_date;
				$promoModel1->fromCityId	 = $modelZeroConv->bivBkg->bkg_from_city_id;
				$promoModel1->toCityId		 = $modelZeroConv->bivBkg->bkg_to_city_id;
				$promoModel1->userId		 = $modelZeroConv->bivBkg->bkgUserInfo->bkg_user_id;
				$promoModel1->platform		 = $modelZeroConv->bivBkg->bkgTrail->bkg_platform;
				$promoModel1->carType		 = $modelZeroConv->bivBkg->bkg_vehicle_type_id;
				$promoModel1->bookingType	 = $modelZeroConv->bivBkg->bkg_booking_type;
				$promoModel1->noOfSeat		 = $modelZeroConv->bivBkg->bkgAddInfo->bkg_no_person;
				$promoModel->bkgId			 = $modelZeroConv->bivBkg->bkg_id;
				$promoModel1->email			 = '';
				$promoModel1->phone			 = '';
				$promoModel1->imEfect		 = '';

				$discountArr = $promoModel1->applyPromoCode();
				if ($discountArr != false)
				{
					if (isset($discountArr['nextTripApply']) && $discountArr['nextTripApply'] == 1)
					{
						$discountArr['cash']	 = 0;
						$discountArr['coins']	 = 0;
					}
					$discount = $discountArr['cash'];
				}
				else
				{
					$discount = 0;
				}
				$modelZeroConv->bkg_discount_amount	 = ($modelZeroConv->bkg_discount_amount > 0) ? $modelZeroConv->bkg_discount_amount : $discount;
				$modelZeroConv->bkg_promo1_amt		 = $discount;
			}
		}
		if ($modelZeroConv->bkg_advance_amount == 0 && $modelZeroConv->bkg_is_wallet_selected == 1 && UserInfo::getUserId() > 0 && $modelZeroConv->bkg_wallet_used > 0)
		{
			$walletBalance						 = UserWallet::getBalance(UserInfo::getUserId());
			$walletUsed							 = ($modelZeroConv->bkg_wallet_used > $walletBalance) ? $walletBalance : $modelZeroConv->bkg_wallet_used;
			$walletUsed							 = ($walletUsed > $modelZeroConv->bkg_due_amount) ? $modelZeroConv->bkg_due_amount : $walletUsed;
			$modelZeroConv->bkg_advance_amount	 = $walletUsed;
		}
		$modelZeroConv->applyPromoCode($this->bkg_promo1_code);
		$modelZeroConv->calculateConvenienceFee(0);
		$modelZeroConv->calculateTotal();
		$amount	 = ($modelZeroConv->bkg_advance_amount > 0 || $modelZeroConv->bkg_credits_used > 0) ? $modelZeroConv->bkg_due_amount : $modelZeroConv->bkg_total_amount;
		$min	 = $modelZeroConv->calculateMinPayment();
		if ($this->partialPayment > 0)
		{
			if ($this->partialPayment > $amount)
			{
				$this->addError($attribute, 'Payment cannot exceed the amount payable.');
				return false;
			}
			if ($this->partialPayment < $min && $this->bivBkg->bkgPref->bpr_rescheduled_from == 0)
			{
				$minPayPerc = Config::getMinAdvancePercent($this->bivBkg->bkg_agent_id, $this->bivBkg->bkg_booking_type, $this->bivBkg->bkgSvcClassVhcCat->scc_ServiceClass->scc_id, $this->bivBkg->bkgPref->bkg_is_gozonow);
				$this->addError($attribute, "Payment should be at " . $minPayPerc . "% of amount payable.");
				return false;
			}
		}
		return true;
	}

	public function addCorporateCredit()
	{
		if ($this->bivBkg->bkg_agent_id > 0 && $this->bkg_corporate_remunerator == 2)
		{
			$agentsModel = Agents::model()->findByPk($this->bivBkg->bkg_agent_id);
			if ($agentsModel != '' && $agentsModel->agt_type == 1)
			{
				$this->bkg_corporate_credit	 = $this->bkg_total_amount;
				$this->bkg_due_amount		 = $this->bkg_total_amount - $this->bkg_advance_amount + round($this->bkg_refund_amount) - $this->bkg_credits_used - $this->bkg_vendor_collected;
			}
		}
	}

	public function addCorporateAmount()
	{
		$this->bkg_base_amount = $this->bkg_base_amount - $this->bkg_corporate_discount;
	}

	public function populateCorporateAmount($corporateId)
	{
// $this->calculateCorporateMarkup($corporateId);
		$this->bivBkg->bkg_agent_id = $corporateId;
		$this->addCorporateAmount();
		$this->calculateServiceTax();
		$this->calculateTotal();
		$this->addCorporateCredit();
	}

	public function changeAgentMarkup()
	{
		if ($this->bivBkg->bkg_agent_id && $this->bivBkg->bkgAgent && $this->bivBkg->bkgAgent->agt_type == 2)
		{
			if ($this->bkg_agent_markup > $this->bkg_gozo_amount)
			{
				$this->bkg_agent_markup = $this->bkg_gozo_amount;
			}
		}
	}

	public static function getInvoiceId($bkgId, $bkgPickupDate = '')
	{
		if ($bkgPickupDate == '')
		{
			$model			 = Booking::model()->findByPk($bkgId);
			$bkgPickupDate	 = $model->bkg_pickup_date;
		}
		$invoice = date('ym', strtotime($bkgPickupDate)) . '/' . strtoupper(Yii::app()->shortHash->hash($bkgId));
		return $invoice;
	}

	/**
	 *
	 * @param integer $bkgId
	 * @return string
	 */
	public static function getInvoiceUrl($bkgId)
	{
		$model	 = Booking::model()->findByPk($bkgId);
		$hash	 = Yii::app()->shortHash->hash($model->bkg_id);
		return Yii::app()->params['fullBaseURL'] . '/invoice/' . $model->bkg_id . '/' . $hash;
	}

	/**
	 *
	 * @param integer $bkgId
	 * @return string
	 */
	public static function getReviewUrl($bkgId)
	{
		$model		 = Booking::model()->findByPk($bkgId);
		$uniqueid	 = Booking::model()->generateLinkUniqueid($model->bkg_id);
		return Yii::app()->params['fullBaseURL'] . '/r/' . $uniqueid;
	}

	/**
	 *
	 * @param integer $bkgId
	 * @return Html
	 * @throws Exception
	 */
	public static function generateHTMLInvoice($model)
	{
		if (!$model)
		{
			throw new Exception("Invalid Booking", ReturnSet::ERROR_VALIDATION);
		}
		$invoiceList		 = Booking::model()->getInvoiceByBooking($model->bkg_id);
		$totPartnerCredit	 = AccountTransDetails::getTotalPartnerCredit($model->bkg_id);
		$totAdvance			 = PaymentGateway::model()->getTotalAdvance($model->bkg_id);
		$totAdvanceOnline	 = PaymentGateway::model()->getTotalOnlinePayment($model->bkg_id);

		$view		 = '//invoice/invoice';
		$htmlObj	 = new CController($view);
		$htmlView	 = $htmlObj->renderPartial($view, array(
			'invoiceList'		 => $invoiceList,
			'totPartnerCredit'	 => $totPartnerCredit,
			'totAdvance'		 => $totAdvance,
			'totAdvanceOnline'	 => $totAdvanceOnline,
			'isPDF'				 => false), true);
		return $htmlView;
	}

	/**
	 *
	 * @param integer $bkgId
	 * @throws mPdf
	 */
	public static function generatePDFInvoice($bkgId)
	{
		$model = Booking::model()->findByPk($bkgId);
		if (!$model)
		{
			throw Exception('Invalid Booking', ReturnSet::ERROR_VALIDATION);
		}
		$html2pdf					 = Yii::app()->ePdf->mPdf();
		$css						 = file_get_contents(Yii::getPathOfAlias('webroot.assets.css') . '/vendorpdf.css');
		$html2pdf->writeHTML($css, 1);
		$html2pdf->setAutoTopMargin	 = 'stretch';
		$html2pdf->setHTMLFooter('<table id="footer" style="width: 100%"> <tr><td style="text-align: center"><hr>www.gozocabs.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;info@gozocabs.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9051 877 000</td></tr></table>');

		$htmlView	 = BookingInvoice::generateHTMLInvoice($model);
		$html2pdf->writeHTML($htmlView);
		$filename	 = $model->bkg_booking_id . date('Ymd', strtotime($model->bkg_pickup_date)) . '.pdf';
		ob_start();
		$html2pdf->Output($filename, 'D');
	}

	public static function updateGozoAmount($bcb_id)
	{
//		$sql	 = "UPDATE booking_invoice biv, (
//						SELECT bkg_id, bkg_status, bkg_bcb_id, agt_commission, agt_commission_value, agt_type
//						FROM booking
//						LEFT JOIN agents ON agents.agt_id = booking.bkg_agent_id
//						WHERE bkg_bcb_id=$bcb_id
//					) bkg1, (
//							SELECT bcb_id,  bcb_vendor_amount,
//							(SUM( bkg_total_amount -bkg_service_tax -
//							CPCommission(bkg_base_amount, bkg_discount_amount, agt_commission_value, agt_commission, agt_type)
//					) - bcb_vendor_amount) as netGozoAmount,
//				SUM(bkg_vendor_amount) as QuotedVendorAmount
//					FROM booking_cab
//					INNER JOIN booking ON booking.bkg_bcb_id = booking_cab.bcb_id AND bcb_id=$bcb_id AND bkg_status IN (2,3,5)
//					INNER JOIN booking_invoice ON booking_invoice.biv_bkg_id =  bkg_id
//					LEFT JOIN agents ON agents.agt_id = booking.bkg_agent_id
//					GROUP BY bcb_id
//					) bkg
//				SET biv.bkg_gozo_amount=bkg_total_amount - bkg_service_tax -
//					CPCommission(bkg_base_amount, bkg_discount_amount, agt_commission_value, agt_commission, agt_type)
//					- ROUND(bcb_vendor_amount * bkg_vendor_amount/QuotedVendorAmount)
//				WHERE bkg_id=biv_bkg_id AND bkg.bcb_id=bkg_bcb_id AND bkg_status IN (2,3,5)";
//
//		$result	 = DBUtil::command($sql)->execute();
		$sql	 = "select 	UpdateGozoAmount($bcb_id) from dual";
		$result	 = DBUtil::command($sql)->queryScalar();
		return $result;
	}

	public function changeCPCommission($bivArr, $bkg_id)
	{
		$success					 = false;
		$transaction				 = DBUtil::beginTransaction();
		$bivModel					 = BookingInvoice::model()->getByBookingID($bkg_id);
		$oldData					 = $bivModel->attributes;
		$bivModel->attributes		 = $bivArr;
		$commission					 = BookingInvoice::model()->getCPCommission($bivArr['bkg_cp_comm_type'], $bivArr['bkg_cp_comm_value'], $bkg_id);
		$bivModel->bkg_agent_markup	 = $commission;
		if ($bivModel->save())
		{
			$newData		 = $bivModel->attributes;
			$diffNewVal		 = array_diff_assoc($newData, $oldData);
			$diffOldVal		 = array_diff_assoc($oldData, $newData);
			$valueTypeArr	 = BookingInvoice::model()->valueType;
			$oldValueType	 = $valueTypeArr[$oldData['bkg_cp_comm_type']];
			$newValueType	 = $valueTypeArr[$newData['bkg_cp_comm_type']];
			if (sizeof($diffOldVal))
			{
				$valueChangedStr = (isset($diffNewVal['bkg_cp_comm_value'])) ? "Commission value changed from {$diffOldVal['bkg_cp_comm_value']} $oldValueType to {$diffNewVal['bkg_cp_comm_value']} $newValueType" : "";
				$desc			 = "Partner Commission Changed. $valueChangedStr";
				BookingLog::model()->createLog($bkg_id, $desc, UserInfo::getInstance(), BookingLog::PARTNER_COMMISSION_CHANGED);
			}
			$bcb_id	 = Booking::getTripId($bkg_id);
			BookingTrail::updateProfitFlag($bcb_id);
			DBUtil::commitTransaction($transaction);
			$success = true;
		}
		else
		{
			DBUtil::rollbackTransaction($transaction);
		}
		return $success;
	}

	public function setCPCommissionFirstTime($cpId = '')
	{
		$cpId = ($cpId == '' ) ? $this->bivBkg->bkg_agent_id : $cpId;
		if ($cpId == '' || $cpId == 0 || $cpId == 1249)
		{
			return 0;
		}
		$agentModel				 = Agents::model()->findByPk($cpId);
		$this->bkg_cp_comm_type	 = 0;
		$this->bkg_cp_comm_value = 0;
		if ($agentModel->agt_type == 2)
		{
			$this->bkg_cp_comm_type	 = $agentModel->agt_commission_value;
			$this->bkg_cp_comm_value = $agentModel->agt_commission;
		}
		$discount_amount = $this->bkg_discount_amount;
		$base_amount	 = $this->getnetBaseFare();
		$agt_type		 = $agentModel->agt_type;
		$commission		 = $this->calcCPCommission($base_amount, $discount_amount, $this->bkg_cp_comm_type, $this->bkg_cp_comm_value, $agt_type);

		$this->bkg_agent_markup = $commission;
	}

	public function getnetBaseFare()
	{
		$getnetbasefare = round(($this->bkg_base_amount + ($this->bkg_additional_charge | 0) + ($this->bkg_extra_km_charge | 0)));
		return $getnetbasefare;
	}

	public function getCPCommission($comm_type, $comm_value, $bkgid)
	{
		$bkgModel	 = Booking::model()->findByPk($bkgid);
		$model		 = $bkgModel->bkgInvoice;
		$cpId		 = $bkgModel->bkg_agent_id;
		if ($cpId == '' || $cpId == 0 || $cpId == 1249)
		{
			return 0;
		}
		$agentModel		 = Agents::model()->findByPk($bkgModel->bkg_agent_id);
		$discount_amount = $model->bkg_discount_amount;
		$base_amount	 = $bkgModel->getnetBaseFare();
		$agt_type		 = $agentModel->agt_type;
		$commission		 = $this->calcCPCommission($base_amount, $discount_amount, $comm_type, $comm_value, $agt_type);
		return $commission;
	}

	public function calcCPCommission($base_amount, $discount_amount, $comm_type, $comm_value, $agt_type)
	{
		if ($agt_type != 2)
		{
			$comm_type	 = 0;
			$comm_value	 = 0;
		}
		$sql		 = "SELECT
					CPCommission($base_amount, $discount_amount, $comm_type, $comm_value, $agt_type )
					  AS commission FROM	 dual";
		$commission	 = DBUtil::command($sql)->queryScalar();

		return $commission;
	}

	/**
	 *
	 * @param string $fromDate
	 * @param string $toDate
	 * @param array|null $params
	 * @return \CSqlDataProvider
	 */
	public function getFinancialSumByPickupDate($fromDate, $toDate, $params = null)
	{
		$where				 = "";
		$includeCondition	 = [];

		if (isset($params['b2cbookings']) && $params['b2cbookings'] == 1)
		{
			$includeCondition[] = "(bkg_agent_id IS NULL OR bkg_agent_id = 1249 OR bkg_agent_id = '')";
		}
		if (isset($params['otherAPIPartner']) && $params['otherAPIPartner'] == 1)
		{
			$includeCondition[] = "((btr.bkg_platform IN (9,10) OR bkg_agent_id IN (35108)) AND bkg_agent_id IS NOT NULL)";
		}
		if (isset($params['nonAPIPartner']) && $params['nonAPIPartner'] == 1)
		{
			$includeCondition[] = "(btr.bkg_platform NOT IN (7,9,10) AND bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN (35108))";//bkg_platform=> 7=MMT,9=>Mobisign,10=>Other Partners(EMT, MYN, SugarBox, GlobalNRI, Upcurve)
		}
		if (isset($params['mmtbookings']) && $params['mmtbookings'] == 1)
		{
			$includeCondition[] = "(bkg_agent_id IN (450,18190))";
		}
		if (count($includeCondition) > 0)
		{
			$where .= " AND (" . implode(" OR ", $includeCondition) . ") ";
		}

		if (isset($params['restrictToDate']) && $params['restrictToDate'] == 1)
		{
			$tillDay = date("d", strtotime($toDate));
			$where	 .= " AND (DATE_FORMAT(booking.bkg_pickup_date, '%d') <= $tillDay) ";
		}

		$sql = "SELECT DATE_FORMAT(bkg_pickup_date, '%Y-%m') as date,
					COUNT(1) as cntCreated,
					SUM(IF(booking.bkg_status=9,1,0)) as cancelled,
					COUNT(DISTINCT IF(booking.bkg_status IN (6, 7),bkg_id,NULL)) as completed,
					SUM(IF(booking.bkg_status IN (6, 7),bkg_total_amount,0)) as completedAmount,
					SUM(IF(booking.bkg_status<>9,bkg_gozo_amount-IFNULL(bkg_credits_used,0),0)) as gozoAmount,
					SUM(IF(booking.bkg_status<>9,IFNULL(bkg_net_base_amount+IFNULL(bkg_convenience_charge,0),0),0)) as totalBaseFare,
					SUM(IF(booking.bkg_status<>9 AND booking.bkg_agent_id IS NOT NULL,bkg_partner_commission,0)) as partnerCommission,
					SUM(IF(booking.bkg_status=9, bkg_net_advance_amount-IFNULL(bkg_credits_used,0),0)) as cancelCharge
				FROM booking
				INNER JOIN booking_invoice ON booking_invoice.biv_bkg_id = booking.bkg_id
				INNER JOIN booking_cab ON bcb_id=bkg_bcb_id
				INNER JOIN booking_trail btr ON btr.btr_bkg_id = booking.bkg_id
				WHERE booking.bkg_status IN (2,3,5,6,7,9) AND booking.bkg_pickup_date BETWEEN '$fromDate 00:00:00' AND '$toDate 23:59:59' {$where}
				GROUP BY date ORDER BY date DESC ";
		return DBUtil::query($sql, DBUtil::SDB3());
	}

	/**
	 *
	 * @param string $fromDate
	 * @param string $toDate
	 * @return \CSqlDataProvider
	 */
	public function getFinancialReportByPickup($fromDate, $toDate)
	{
		$sql = "SELECT DATE_FORMAT(bkg_pickup_date, '%Y-%m') as date,
				COUNT(1) as cntCreated,
				SUM(IF(booking.bkg_agent_id IS NULL,1,0)) as createdB2C,
				SUM(IF(booking.bkg_agent_id IS NOT NULL,1,0)) as createdB2B,
				SUM(IF(booking.bkg_agent_id IS NOT NULL AND bkg_agent_id IN (450,18190),1,0)) as 'MMT+IBIBO createdB2B',
				SUM(IF(booking.bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN (450,18190),1,0)) as 'B2B Other createdB2B',
				SUM(IF(booking.bkg_status=9,1,0)) as cancelled,
				SUM(IF(booking.bkg_status=9 AND booking.bkg_agent_id IS NULL,1,0)) as cancelledB2C,

				SUM(IF(booking.bkg_status=9 AND booking.bkg_agent_id IS NOT NULL,1,0)) as cancelledB2B,
				SUM(IF(booking.bkg_status=9 AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id IN (450,18190) ,1,0)) as 'MMT+IBIBO cancelledB2B',
				SUM(IF(booking.bkg_status=9 AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN (450,18190) ,1,0)) as 'B2B Other  cancelledB2B',

				COUNT(DISTINCT IF(booking.bkg_status IN (6, 7),bkg_id,NULL)) as completed,
				COUNT(DISTINCT IF(booking.bkg_status IN (6, 7)  AND booking.bkg_agent_id IS NULL,bkg_id,NULL)) as completedB2C,

				COUNT(DISTINCT IF(booking.bkg_status IN (6, 7) AND booking.bkg_agent_id IS NOT NULL,bkg_id,NULL)) as completedB2B,
				COUNT(DISTINCT IF(booking.bkg_status IN (6, 7) AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id IN (450,18190) ,bkg_id,NULL)) as 'MMT+IBIBO completedB2B',
				COUNT(DISTINCT IF(booking.bkg_status IN (6, 7) AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN (450,18190) ,bkg_id,NULL)) as 'B2B Other  completedB2B',

				COUNT(DISTINCT IF(booking.bkg_status IN (6, 7) AND booking.bkg_agent_id  IN (450,454,1273,18190),bkg_id,NULL)) as completedB2BAPI,
				SUM(bkg_total_amount) as createdAmount,
				SUM(IF(booking.bkg_agent_id IS NULL,bkg_total_amount,0)) as createdAmountB2C,

				SUM(IF(booking.bkg_agent_id IS NOT NULL,bkg_total_amount,0)) as createdAmountB2B,
				SUM(IF(booking.bkg_agent_id IS NOT NULL AND bkg_agent_id IN (450,18190) ,bkg_total_amount,0)) as  'MMT+IBIBO createdAmountB2B',
				SUM(IF(booking.bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN (450,18190) ,bkg_total_amount,0)) as  'B2B Other createdAmountB2B',

				SUM(IF(booking.bkg_status IN (6, 7),bkg_total_amount,0)) as completedAmount,
				SUM(IF(booking.bkg_status IN (6, 7) AND booking.bkg_agent_id IS NULL,bkg_total_amount,0)) as completedAmountB2C,

				SUM(IF(booking.bkg_status IN (6, 7) AND booking.bkg_agent_id IS NOT NULL,bkg_total_amount,0)) as completedAmountB2B,
				SUM(IF(booking.bkg_status IN (6, 7) AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id IN (450,18190) ,bkg_total_amount,0)) as 'MMT+IBIBO completedAmountB2B',
				SUM(IF(booking.bkg_status IN (6, 7) AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN (450,18190) ,bkg_total_amount,0)) as 'B2B Other completedAmountB2B',

				SUM(IF(booking.bkg_status<>9,bkg_gozo_amount-IFNULL(bkg_credits_used,0),0)) as gozoAmount,
				SUM(IF(booking.bkg_status<>9 AND booking.bkg_agent_id IS NULL,bkg_gozo_amount-IFNULL(bkg_credits_used,0),0)) as gozoAmountB2C,

				SUM(IF(booking.bkg_status<>9 AND booking.bkg_agent_id IS NOT NULL,bkg_gozo_amount-IFNULL(bkg_credits_used,0),0)) as gozoAmountB2B,
				SUM(IF(booking.bkg_status<>9 AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id  IN (450,18190) ,bkg_gozo_amount-IFNULL(bkg_credits_used,0),0)) as 'MMT+IBIBO gozoAmountB2B',
				SUM(IF(booking.bkg_status<>9 AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN (450,18190) ,bkg_gozo_amount-IFNULL(bkg_credits_used,0),0)) as 'B2B Other gozoAmountB2B',

				SUM(IF(booking.bkg_status<>9,IFNULL(bkg_net_base_amount+IFNULL(bkg_convenience_charge,0),0),0)) as totalBaseFare,
				ROUND(SUM(IF(booking.bkg_status<>9,IFNULL(IFNULL(bkg_toll_tax,0)+bkg_extra_toll_tax+IFNULL(bkg_state_tax,0)+bkg_extra_state_tax+bkg_parking_charge+bkg_airport_entry_fee,0),0))) as totalTollAndStateTax,
				SUM(IF(booking.bkg_status<>9,IFNULL(bkg_driver_allowance_amount,0),0)) as totalDriverAllowance,
				SUM(IF(booking.bkg_status<>9,IFNULL(bkg_service_tax,0),0)) as totalGst,

				SUM(IF(booking.bkg_status<>9 AND booking.bkg_agent_id IS NOT NULL,bkg_partner_commission,0)) as partnerCommission,
				MAX(IF(booking.bkg_status<>9,bkg_total_amount,0)) as maxTicketSize,
				MAX(IF(booking.bkg_status<>9 AND booking.bkg_agent_id IS NULL,bkg_total_amount,0)) as maxTicketSizeB2C,

				MAX(IF(booking.bkg_status<>9 AND booking.bkg_agent_id IS NOT NULL,bkg_total_amount,0)) as maxTicketSizeB2B,
				MAX(IF(booking.bkg_status<>9 AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id IN (450,18190) ,bkg_total_amount,0)) as 'MMT+IBIBO maxTicketSizeB2B',
				MAX(IF(booking.bkg_status<>9 AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN (450,18190),bkg_total_amount,0)) as 'B2B Other maxTicketSizeB2B',

				MIN(IF(booking.bkg_status<>9,bkg_total_amount,0)) as minTicketSize,
				MIN(IF(booking.bkg_status<>9 AND booking.bkg_agent_id IS NULL,bkg_total_amount,0)) as minTicketSizeB2C,

				MIN(IF(booking.bkg_status<>9 AND booking.bkg_agent_id IS NOT NULL,bkg_total_amount,0)) as minTicketSizeB2B,
				MIN(IF(booking.bkg_status<>9 AND booking.bkg_agent_id IS NOT NULL  AND bkg_agent_id IN (450,18190) ,bkg_total_amount,0)) as 'MMT+IBIBO minTicketSizeB2B',
				MIN(IF(booking.bkg_status<>9 AND booking.bkg_agent_id IS NOT NULL  AND bkg_agent_id NOT IN (450,18190) ,bkg_total_amount,0)) as 'B2B Other minTicketSizeB2B',

				COUNT(DISTINCT IF(booking.bkg_status<>9 AND bcb_trip_type=1,bcb_id, null)) as matchedTrips,
				COUNT(DISTINCT IF(booking.bkg_status<>9 AND bcb_trip_type=0,bcb_id, null)) as singleTrips,
				SUM(IF(booking.bkg_status<>9 AND bcb_trip_type=1,bkg_gozo_amount-IFNULL(bkg_credits_used,0), 0)) as matchedTripsGozoAmount,
				SUM(IF(booking.bkg_status<>9 AND bcb_trip_type=0,bkg_gozo_amount-IFNULL(bkg_credits_used,0), 0)) as singleTripsGozoAmount,
				SUM(IF(booking.bkg_status IN (6, 7), bkg_total_amount-bkg_service_tax-bkg_gozo_amount-bkg_partner_commission,0)) as vendorAmountCompleted,

				SUM(IF(booking.bkg_status IN (6, 7) AND booking.bkg_agent_id IS NOT NULL,bkg_total_amount-bkg_service_tax-bkg_gozo_amount-bkg_partner_commission,0)) as vendorAmountB2B,
				SUM(IF(booking.bkg_status IN (6, 7) AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id  IN (450,18190),bkg_total_amount-bkg_service_tax-bkg_gozo_amount-bkg_partner_commission,0)) as 'MMT+IBIBO vendorAmountB2B',
				SUM(IF(booking.bkg_status IN (6, 7) AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN (450,18190),bkg_total_amount-bkg_service_tax-bkg_gozo_amount-bkg_partner_commission,0)) as 'B2B Other vendorAmountB2B',

				SUM(IF(booking.bkg_status IN (6, 7) AND booking.bkg_agent_id IS NULL, bkg_total_amount-bkg_service_tax-bkg_gozo_amount-bkg_partner_commission, 0)) as vendorAmountB2C,
				SUM(IF(booking.bkg_status IN (6, 7) AND bcb_trip_type=1, bkg_total_amount-bkg_service_tax-bkg_gozo_amount-bkg_partner_commission, 0)) as vendorAmountMatched,
				SUM(IF(booking.bkg_status IN (6, 7) AND bcb_trip_type=0, bkg_total_amount-bkg_service_tax-bkg_gozo_amount-bkg_partner_commission, 0)) as vendorAmountUnmatched,
				SUM(IF(booking.bkg_status=9, bkg_gozo_amount,0)) as cancelledGozoAmount,
				SUM(IF(booking.bkg_status=9 AND booking.bkg_agent_id IS NULL, bkg_gozo_amount-IFNULL(bkg_credits_used,0),0)) as cancelledB2CGozoAmount,

				SUM(IF(booking.bkg_status=9 AND booking.bkg_agent_id IS NOT NULL, bkg_gozo_amount-IFNULL(bkg_credits_used,0),0)) as cancelledB2BGozoAmount,
				SUM(IF(booking.bkg_status=9 AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id IN (450,18190) , bkg_gozo_amount-IFNULL(bkg_credits_used,0),0)) as 'MMT+IBIBO cancelledB2BGozoAmount',
				SUM(IF(booking.bkg_status=9 AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN (450,18190) , bkg_gozo_amount-IFNULL(bkg_credits_used,0),0)) as 'B2B Other cancelledB2BGozoAmount',

				SUM(IF(booking.bkg_status=9, bkg_net_advance_amount-IFNULL(bkg_credits_used,0),0)) as cancelCharge,
				SUM(IF(booking.bkg_status=9 AND booking.bkg_agent_id IS NULL, bkg_net_advance_amount-IFNULL(bkg_credits_used,0),0)) as cancelChargeB2C,

				SUM(IF(booking.bkg_status=9 AND booking.bkg_agent_id IS NOT NULL, bkg_net_advance_amount-IFNULL(bkg_credits_used,0),0)) as cancelChargeB2B
				,SUM(IF(booking.bkg_status=9 AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id IN (450,18190) , bkg_net_advance_amount-IFNULL(bkg_credits_used,0),0)) as 'MMT+IBIBO cancelChargeB2B'
				,SUM(IF(booking.bkg_status=9 AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN (450,18190) , bkg_net_advance_amount-IFNULL(bkg_credits_used,0),0)) as 'B2B Other cancelChargeB2B'

				,COUNT(DISTINCT IF( booking.bkg_status in (6,7) and  drivers.drv_id=drivers.drv_ref_code ,drivers.drv_id, NULL)) as driverCount
				,COUNT(DISTINCT IF( booking.bkg_status in (6,7) and users.usr_active=1  and users.user_id is not null , booking_user.bkg_user_id, NULL)) as UserCount
				,COUNT(DISTINCT IF( booking.bkg_status in (6,7) and vehicles.vhc_active in (1,2,3) , booking_cab.bcb_cab_id, NULL)) as VehicleCount
				,COUNT(DISTINCT IF(rtg_customer_overall<3, bkg_id, NULL)) AS Count3starbookings
				,COUNT(DISTINCT IF(rtg_customer_overall is not null, bkg_id, NULL)) AS bookingsRatingsReceived
				,COUNT(DISTINCT IF(booking_invoice.bkg_gozo_amount<0 and booking.bkg_status<>9 ,bkg_id, NULL)) as CountLossbookings
				,SUM(IF(booking_invoice.bkg_gozo_amount<0 and booking.bkg_status<>9 ,abs(booking_invoice.bkg_gozo_amount), 0)) as totalLossAmount
				,COUNT(DISTINCT IF(bkg_trip_start_time <= booking.bkg_pickup_date, btk_bkg_id,NULL)) AS CountArrivedOnTime,
				SUM(IF(booking.bkg_status IN (2,3,5,6,7) AND booking.bkg_agent_id IN (450, 18190), 1, 0)) as createdMMT,
				SUM(IF(booking.bkg_status<>9 AND booking.bkg_agent_id IN (450, 18190), bkg_partner_commission, 0)) as partnerCommissionMMT

				FROM booking
				INNER JOIN booking_invoice ON booking_invoice.biv_bkg_id = booking.bkg_id AND booking.bkg_pickup_date BETWEEN '$fromDate 00:00:00' AND '$toDate 23:59:59'
				INNER JOIN booking_cab ON bcb_id=bkg_bcb_id
				INNER JOIN booking_user ON booking_user.bui_bkg_id = booking.bkg_id
				LEFT JOIN users ON users.user_id =booking_user.bkg_user_id
				LEFT JOIN  drivers on drivers.drv_id = booking_cab.bcb_driver_id
				LEFT JOIN vehicles on vehicles.vhc_id = booking_cab.bcb_cab_id
				LEFT JOIN ratings ON ratings.rtg_booking_id =  booking.bkg_id
				LEFT JOIN booking_track btl ON btl.btk_bkg_id = booking.bkg_id
				LEFT JOIN booking_trail btr ON btr.btr_bkg_id = booking.bkg_id
				WHERE booking.bkg_status IN (2,3,5,6,7,9) AND booking.bkg_pickup_date BETWEEN '$fromDate 00:00:00' AND '$toDate 23:59:59'
				GROUP BY date Order by date DESC ";
		return DBUtil::query($sql, DBUtil::SDB3());
	}

	/**
	 *
	 * @param string $fromDate
	 * @param string $toDate
	 * @return \CSqlDataProvider
	 */
	public function getFinancialReportByCreateDate($fromDate, $toDate)
	{
		$sql = "SELECT DATE_FORMAT(bkg_create_date, '%Y-%m') as date, COUNT(1) as cntCreated,
				SUM(IF(booking.bkg_agent_id IS NULL,1,0)) as createdB2C,

				SUM(IF(booking.bkg_agent_id IS NOT NULL ,1,0)) as createdB2B,
				SUM(IF(booking.bkg_agent_id IS NOT NULL AND bkg_agent_id IN (450,18190),1,0)) AS 'MMT+IBIBO createdB2B',
				SUM(IF(booking.bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN (450,18190),1,0)) AS 'B2B Other createdB2B',

				SUM(IF(bkg_status=9,1,0)) as cancelled,
				SUM(IF(bkg_status=9 AND booking.bkg_agent_id IS NULL,1,0)) as cancelledB2C,

				SUM(IF(bkg_status=9 AND booking.bkg_agent_id IS NOT NULL,1,0)) as cancelledB2B,
				SUM(IF(bkg_status=9 AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id IN (450,18190),1,0)) as 'MMT+IBIBO cancelledB2B',
				SUM(IF(bkg_status=9 AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN (450,18190),1,0)) as 'B2B Other cancelledB2B',

				SUM(IF(bkg_status IN (6,7),1,0)) as completed,
				SUM(IF(bkg_status IN (6,7) AND booking.bkg_agent_id IS NULL,1,0)) as completedB2C,

				SUM(IF(bkg_status IN (6,7) AND booking.bkg_agent_id IS NOT NULL,1,0)) as completedB2B,
				SUM(IF(bkg_status IN (6,7) AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id IN (450,18190) ,1,0)) as 'MMT+IBIBO completedB2B',
				SUM(IF(bkg_status IN (6,7) AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN (450,18190) ,1,0)) as 'B2B Other completedB2B',

				SUM(bkg_total_amount) as createdAmount,
				SUM(IF(booking.bkg_agent_id IS NULL,bkg_total_amount,0)) as createdAmountB2C,

				SUM(IF(booking.bkg_agent_id IS NOT NULL,bkg_total_amount,0)) as createdAmountB2B,
				SUM(IF(booking.bkg_agent_id IS NOT NULL AND bkg_agent_id IN (450,18190) ,bkg_total_amount,0)) as 'MMT+IBIBO createdAmountB2B',
				SUM(IF(booking.bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN (450,18190) ,bkg_total_amount,0)) as 'B2B Other  createdAmountB2B',

				SUM(IF(bkg_status IN (6, 7),bkg_total_amount,0)) as completedAmount,
				SUM(IF(bkg_status IN (6, 7) AND booking.bkg_agent_id IS NULL,bkg_total_amount,0)) as completedAmountB2C,

				SUM(IF(bkg_status IN (6, 7) AND booking.bkg_agent_id IS NOT NULL,bkg_total_amount,0)) as completedAmountB2B,
				SUM(IF(bkg_status IN (6, 7) AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id IN (450,18190) ,bkg_total_amount,0)) as 'MMT+IBIBO completedAmountB2B',
				SUM(IF(bkg_status IN (6, 7) AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN (450,18190) ,bkg_total_amount,0)) as 'B2B Other completedAmountB2B',

				SUM(IF(bkg_status<>9,bkg_gozo_amount-IFNULL(bkg_credits_used,0),0)) as gozoAmount,
				SUM(IF(bkg_status<>9 AND booking.bkg_agent_id IS NULL,bkg_gozo_amount-IFNULL(bkg_credits_used,0),0)) as gozoAmountB2C,

				SUM(IF(bkg_status<>9,IFNULL(bkg_net_base_amount+IFNULL(bkg_convenience_charge,0),0),0)) as totalBaseFare,
				ROUND(SUM(IF(bkg_status<>9,IFNULL(IFNULL(bkg_toll_tax,0)+bkg_extra_toll_tax+IFNULL(bkg_state_tax,0)+bkg_extra_state_tax+bkg_parking_charge+bkg_airport_entry_fee,0),0))) as totalTollAndStateTax,
				SUM(IF(bkg_status<>9,IFNULL(bkg_driver_allowance_amount,0),0)) as totalDriverAllowance,
				SUM(IF(booking.bkg_status<>9,IFNULL(bkg_service_tax,0),0)) as totalGst,

				SUM(IF(bkg_status<>9 AND booking.bkg_agent_id IS NOT NULL,bkg_gozo_amount-IFNULL(bkg_credits_used,0),0)) as gozoAmountB2B,
				SUM(IF(bkg_status<>9 AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id IN (450,18190) ,bkg_gozo_amount-IFNULL(bkg_credits_used,0),0)) as  'MMT+IBIBO  gozoAmountB2B',
				SUM(IF(bkg_status<>9 AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN (450,18190) ,bkg_gozo_amount-IFNULL(bkg_credits_used,0),0)) as  'B2B Other  gozoAmountB2B',

				SUM(IF(bkg_status<>9 AND booking.bkg_agent_id IS NOT NULL,bkg_partner_commission,0)) as partnerCommission,
				MAX(IF(bkg_status<>9,bkg_total_amount,0)) as maxTicketSize,
				MAX(IF(bkg_status<>9 AND booking.bkg_agent_id IS NULL,bkg_total_amount,0)) as maxTicketSizeB2C,

				MAX(IF(bkg_status<>9 AND booking.bkg_agent_id IS NOT NULL,bkg_total_amount,0)) as maxTicketSizeB2B,
				MAX(IF(bkg_status<>9 AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id IN (450,18190) ,bkg_total_amount,0)) as 'MMT+IBIBO maxTicketSizeB2B',
				MAX(IF(bkg_status<>9 AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN (450,18190) ,bkg_total_amount,0)) as 'B2B Other maxTicketSizeB2B',

				MIN(IF(bkg_status<>9,bkg_total_amount,0)) as minTicketSize,
				MIN(IF(bkg_status<>9 AND booking.bkg_agent_id IS NULL,bkg_total_amount,0)) as minTicketSizeB2C,

				MIN(IF(bkg_status<>9 AND booking.bkg_agent_id IS NOT NULL,bkg_total_amount,0)) as minTicketSizeB2B,
				MIN(IF(bkg_status<>9 AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id IN (450,18190),bkg_total_amount,0)) as 'MMT+IBIBO minTicketSizeB2B',
				MIN(IF(bkg_status<>9 AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN (450,18190) ,bkg_total_amount,0)) as 'B2B Other minTicketSizeB2B',

				COUNT(DISTINCT IF(bkg_status<>9 AND bcb_trip_type=1,bcb_id, null)) as matchedTrips,
				COUNT(DISTINCT IF(bkg_status<>9 AND bcb_trip_type=0,bcb_id, null)) as singleTrips,
				SUM(IF(bkg_status<>9 AND bcb_trip_type=1,bkg_gozo_amount-IFNULL(bkg_credits_used,0), 0)) as matchedTripsGozoAmount,
				SUM(IF(bkg_status<>9 AND bcb_trip_type=0,bkg_gozo_amount-IFNULL(bkg_credits_used,0), 0)) as singleTripsGozoAmount,
				SUM(IF(bkg_status IN (6, 7), bkg_total_amount-bkg_service_tax-bkg_gozo_amount-bkg_partner_commission,0)) as vendorAmountCompleted,

				SUM(IF(bkg_status IN (6, 7) AND booking.bkg_agent_id IS NOT NULL,bkg_total_amount-bkg_service_tax-bkg_gozo_amount-bkg_partner_commission,0)) as vendorAmountB2B,
				SUM(IF(bkg_status IN (6, 7) AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id  IN (450,18190) ,bkg_total_amount-bkg_service_tax-bkg_gozo_amount-bkg_partner_commission,0)) as  'MMT+IBIBO vendorAmountB2B',
				SUM(IF(bkg_status IN (6, 7) AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN (450,18190) ,bkg_total_amount-bkg_service_tax-bkg_gozo_amount-bkg_partner_commission,0)) as 'B2B Other vendorAmountB2B',

				SUM(IF(bkg_status IN (6, 7) AND booking.bkg_agent_id IS NULL,bkg_total_amount-bkg_service_tax-bkg_gozo_amount-bkg_partner_commission,0)) as vendorAmountB2C,
				SUM(IF(bkg_status IN (6, 7) AND bcb_trip_type=1,bkg_total_amount-bkg_service_tax-bkg_gozo_amount-bkg_partner_commission,0)) as vendorAmountMatched,
				SUM(IF(bkg_status IN (6, 7) AND bcb_trip_type=0,bkg_total_amount-bkg_service_tax-bkg_gozo_amount-bkg_partner_commission,0)) as vendorAmountUnmatched,
				SUM(IF(bkg_status=9, bkg_gozo_amount-IFNULL(bkg_credits_used,0),0)) as cancelledGozoAmount,
				SUM(IF(bkg_status=9 AND booking.bkg_agent_id IS NULL, bkg_gozo_amount-IFNULL(bkg_credits_used,0),0)) as cancelledB2CGozoAmount,

				SUM(IF(bkg_status=9 AND booking.bkg_agent_id IS NOT NULL, bkg_gozo_amount-IFNULL(bkg_credits_used,0),0)) as cancelledB2BGozoAmount,
				SUM(IF(bkg_status=9 AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id  IN (450,18190) , bkg_gozo_amount-IFNULL(bkg_credits_used,0),0)) as 'MMT+IBIBO cancelledB2BGozoAmount',
				SUM(IF(bkg_status=9 AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN (450,18190) , bkg_gozo_amount-IFNULL(bkg_credits_used,0),0)) as 'B2B Other cancelledB2BGozoAmount',

				SUM(IF(bkg_status=9, bkg_net_advance_amount-IFNULL(bkg_credits_used,0),0)) as cancelCharge,
				SUM(IF(bkg_status=9 AND booking.bkg_agent_id IS NULL, bkg_net_advance_amount-IFNULL(bkg_credits_used,0),0)) as cancelChargeB2C,

				SUM(IF(bkg_status=9 AND booking.bkg_agent_id IS NOT NULL, bkg_net_advance_amount-IFNULL(bkg_credits_used,0),0)) as cancelChargeB2B,
				SUM(IF(bkg_status=9 AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id  IN (450,18190) , bkg_net_advance_amount-IFNULL(bkg_credits_used,0),0)) as 'MMT+IBIBO  cancelChargeB2B',
				SUM(IF(bkg_status=9 AND booking.bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN (450,18190) , bkg_net_advance_amount-IFNULL(bkg_credits_used,0),0)) as 'B2B Other  cancelChargeB2B',

				Sum(if(stt_zone=1,1,0)) as 'North',
				Sum(if(stt_zone=2,1,0)) as 'West',
				Sum(if(stt_zone=3,1,0)) as 'Central',
				Sum(if(stt_zone=4 or stt_zone=7 ,1,0)) as 'South',
				Sum(if(stt_zone=5,1,0)) as 'East',
				Sum(if(stt_zone=6,1,0)) as 'NorthEast',
				SUM(IF(booking.bkg_status IN (2,3,5,6,7) AND booking.bkg_agent_id IN (450, 18190), 1, 0)) as createdMMT,
				SUM(IF(bkg_status<>9 AND booking.bkg_agent_id IN (450, 18190), bkg_partner_commission, 0)) as partnerCommissionMMT
				FROM booking
				INNER JOIN booking_invoice ON booking_invoice.biv_bkg_id = booking.bkg_id
				INNER JOIN booking_cab ON bcb_id=bkg_bcb_id
				INNER JOIN  cities on booking.bkg_from_city_id=cities.cty_id and cities.cty_active = 1
				INNER JOIN states on states.stt_id=cities.cty_state_id
				WHERE bkg_status IN (2,3,5,6,7,9)
				AND booking.bkg_create_date BETWEEN '$fromDate 00:00:00' AND '$toDate 23:59:59'
				GROUP BY date Order by date DESC";
		return DBUtil::query($sql, DBUtil::SDB3());
	}

	/**
	 *
	 * @param string $fromDate
	 * @param string $toDate
	 * @return \CSqlDataProvider
	 */
	public function getFinancialReportPenaltyByDate($fromDate, $toDate, $params = [])
	{
		$includeCondition	 = [];
		$bkgJoin			 = "";
		$cond				 = "";
		if (isset($params['restrictToDate']) && $params['restrictToDate'] == 1)
		{
			$tillDay = date("d", strtotime($toDate));
			$cond	 .= " AND (DATE_FORMAT(act_date, '%d') <= $tillDay) ";
		}

		if (isset($params['b2cbookings']) && $params['b2cbookings'] == 1)
		{
			$includeCondition[]	 = "(bkg.bkg_id IS NOT NULL AND (bkg.bkg_agent_id IS NULL OR bkg.bkg_agent_id = 1249))";
			$includeCondition[]	 = "(bkg1.bkg_id IS NOT NULL AND (bkg1.bkg_agent_id IS NULL OR bkg1.bkg_agent_id = 1249))";
		}
		if (isset($params['otherAPIPartner']) && $params['otherAPIPartner'] == 1)
		{
			$includeCondition[]	 = "(btr.bkg_platform NOT IN (7,9,10) AND bkg.bkg_agent_id IS NOT NULL)";
			$includeCondition[]	 = "(btr1.bkg_platform NOT IN (7,9,10) AND bkg1.bkg_agent_id IS NOT NULL)";
			$includeCondition[]	 = "(atd1.adt_ledger_id=15 AND atd1.adt_trans_ref_id NOT IN (450,18190))";
		}
		if (isset($params['nonAPIPartner']) && $params['nonAPIPartner'] == 1)
		{
			$includeCondition[]	 = "(btr.bkg_platform NOT IN (7,9,10) AND bkg.bkg_agent_id IS NOT NULL)";
			$includeCondition[]	 = "(btr1.bkg_platform NOT IN (7,9,10) AND bkg1.bkg_agent_id IS NOT NULL)";
			$includeCondition[]	 = "(atd1.adt_ledger_id=15 AND atd1.adt_trans_ref_id NOT IN (450,18190))";
		}
		if (isset($params['mmtbookings']) && $params['mmtbookings'] == 1)
		{
			$includeCondition[]	 = "(bkg.bkg_agent_id IN (450,18190))";
			$includeCondition[]	 = "(bkg1.bkg_agent_id IN (450,18190))";
			$includeCondition[]	 = "(atd1.adt_ledger_id=15 AND atd1.adt_trans_ref_id IN (450,18190))";
		}
		if (count($includeCondition) > 0)
		{
			$bkgJoin = "
				LEFT JOIN booking bkg ON bkg.bkg_id=atd.adt_trans_ref_id AND atd.adt_type=1
				LEFT JOIN booking_trail btr ON bkg.bkg_id=btr.btr_bkg_id 
				LEFT JOIN booking_cab bcb ON bcb.bcb_id=atd.adt_trans_ref_id AND atd.adt_type=5
				LEFT JOIN booking bkg1 ON bkg1.bkg_id=bcb.bcb_bkg_id1
				LEFT JOIN booking_trail btr1 ON bkg1.bkg_id=btr1.btr_bkg_id 
				";

			$cond .= " AND (" . implode(" OR ", $includeCondition) . ") ";
		}


		$sql = "SELECT DATE_FORMAT(act_date,'%Y-%m') as date,
					SUM(IF(atd1.adt_ledger_id=15 AND atd.adt_ledger_id=27,IF(abs(atd.adt_amount)>abs(atd1.adt_amount), atd1.adt_amount *-1, atd.adt_amount),0)) as partnerCompensation,
					SUM(IF(atd1.adt_ledger_id=14 AND atd.adt_ledger_id=27,IF(abs(atd.adt_amount)>abs(atd1.adt_amount), atd1.adt_amount *-1, atd.adt_amount),0)) as operatorCompensation,
					SUM(IF(atd1.adt_ledger_id=14 AND atd.adt_ledger_id=28,IF(abs(atd.adt_amount)>abs(atd1.adt_amount), atd1.adt_amount *-1, atd.adt_amount),0) * -1) as operatorPenalty
				FROM account_trans_details atd
				INNER JOIN account_transactions act ON atd.adt_trans_id=act.act_id AND act.act_active=1 AND atd.adt_active=1 AND atd.adt_ledger_id IN (27,28)
				INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_id<>atd.adt_id AND atd1.adt_active=1
				$bkgJoin
				WHERE act_date BETWEEN '$fromDate 00:00:00' AND '$toDate 23:59:59' AND act_date>'2018-04-01 00:00:00' 
					AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0)) $cond
				GROUP BY date ORDER BY  date desc";
		return DBUtil::query($sql, DBUtil::SDB3());
	}

	public static function getPenaltyArrayByDate($fromDate, $toDate, $params = [])
	{
		$arr = [];
		$res = self::model()->getFinancialReportPenaltyByDate($fromDate, $toDate, $params);
		foreach ($res as $row)
		{
			$arr[$row['date']] = $row;
		}
		return $arr;
	}

	public function populateFromQuote(Quote $quote)
	{
		$routeRates		 = $quote->routeRates;
		$routeDistance	 = $quote->routeDistance;

		$this->bkg_base_amount					 = $routeRates->baseAmount;
		$this->bkg_gozo_base_amount				 = $routeRates->baseAmount;
		$this->bkg_driver_allowance_amount		 = $routeRates->driverAllowance;
		$this->bkg_is_toll_tax_included			 = $routeRates->isTollIncluded | 0;
		$this->bkg_is_state_tax_included		 = $routeRates->isStateTaxIncluded | 0;
		$this->bkg_toll_tax						 = $routeRates->tollTaxAmount | 0;
		$this->bkg_state_tax					 = $routeRates->stateTax | 0;
		$this->bkg_airport_entry_fee			 = $routeRates->airportEntryFee | 0;
		$this->bkg_is_airport_fee_included		 = $routeRates->isAirportEntryFeeIncluded | 0;
		$this->bkg_rate_per_km_extra			 = $routeRates->ratePerKM;
		$this->bkg_extra_per_min_charge			 = $routeRates->extraPerMinCharge;
		$this->bkg_extra_min					 = $routeRates->extraPerMin;
		$this->bkg_rate_per_km					 = $routeRates->ratePerKM;
		$this->bkg_total_amount					 = $routeRates->totalAmount;
		$this->bkg_service_tax					 = $routeRates->gst;
		$this->bkg_night_pickup_included		 = $routeRates->isNightPickupIncluded | 0;
		$this->bkg_night_drop_included			 = $routeRates->isNightDropIncluded | 0;
		$this->bkg_vendor_amount				 = round($routeRates->vendorAmount | 0);
		$this->bkg_quoted_vendor_amount			 = round($routeRates->vendorAmount | 0);
		$this->bkg_surge_differentiate_amount	 = $routeRates->differentiateSurgeAmount;
		$this->bkg_chargeable_distance			 = $routeDistance->tripDistance;
	}

	public function populateFromShuttle($shuttleData)
	{
		$indVendorAmount					 = round($shuttleData['slt_vendor_amount'] / $shuttleData['slt_seat_availability']);
		$this->bkg_gozo_base_amount			 = $shuttleData['slt_base_fare'];
		$this->bkg_base_amount				 = $shuttleData['slt_base_fare'];
		$this->bkg_driver_allowance_amount	 = $shuttleData['slt_driver_allowance'];
		$this->bkg_chargeable_distance		 = $shuttleData['trip_distance'];
		$this->bkg_vendor_amount			 = $indVendorAmount;
		$this->bkg_is_toll_tax_included		 = 1;
		$this->bkg_is_state_tax_included	 = 1;
		$this->bkg_toll_tax					 = $shuttleData['slt_toll_tax'];
		$this->bkg_state_tax				 = $shuttleData['slt_state_tax'];
		$this->bkg_quoted_vendor_amount		 = $indVendorAmount;
	}

	public function chargeInternationFee($countryCode, $sendSMS)
	{
		if ($countryCode != '91' && $sendSMS == 1)
		{
			$this->bkg_additional_charge		 = 99;
			$this->bkg_additional_charge_remark	 = "International SMS fee of Rs.99";
		}
	}

	public function getInvoiceCity(Booking $bkgModel)
	{
		$this->gstCityId = $bkgModel->bkg_from_city_id;
		if ($bkgModel->bkg_agent_id > 0)
		{
			$agtModel		 = Agents::model()->findByPk($bkgModel->bkg_agent_id);
			$this->gstCityId = $agtModel->agt_city;
		}
	}

	public static function counterRefundApproval()
	{
		$returnSet = Yii::app()->cache->get('counterRefundApproval');
		if ($returnSet === false)
		{
			$sql = "SELECT
				IFNULL(COUNT(DISTINCT booking.bkg_id),0) AS cnt,
				IFNULL(SUM(
					IF(
						booking.bkg_agent_id = 450,
						1,
						0
					)
				),0) AS countMMT,
				IFNULL(SUM(
					IF((booking.bkg_agent_id > 0 AND booking.bkg_agent_id != 450), 1, 0)
				),0) AS countB2B,
				IFNULL(SUM(
					IF(
						booking.bkg_agent_id IS NULL,
						1,
						0
					)
				),0) AS countB2C
				FROM `booking`
				INNER JOIN `booking_invoice` ON booking.bkg_id = booking_invoice.biv_bkg_id
                WHERE booking_invoice.biv_refund_approval_status = 1 AND bkg_status = 9 LIMIT 0,1";

			$returnSet = DBUtil::queryRow($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('counterRefundApproval', $returnSet, 600);
		}
		return $returnSet;
	}

	public function autoInitiateRefund()
	{
		$sql		 = 'SELECT
				`biv_bkg_id`
					FROM
						`booking`
					INNER JOIN booking_invoice ON booking_invoice.biv_bkg_id = booking.bkg_id
					WHERE
						booking_invoice.biv_refund_approval_status = 3';
		$resultSet	 = DBUtil::queryAll($sql);
		foreach ($resultSet as $value)
		{
			$model			 = Booking::model()->findByPk($value['biv_bkg_id']);
			$refundAmount	 = $model->bkgInvoice->bkg_cancel_refund;
			Logger::create("BOOKING ID =>" . $model->bkg_id . "  REFUND AMOUNT =>" . $refundAmount, CLogger::LEVEL_PROFILE);
			if ($refundAmount > 0)
			{
				$response										 = $model->refund($refundAmount, "Refund on booking cancelation", UserInfo::model());
				$emailObj										 = new emailWrapper();
				$emailObj->cancellationRefundMail($model->bkg_id, $refundAmount);
				$model->refresh();
				$model->bkgInvoice->biv_refund_approval_status	 = 4; //refund proccessed
				$model->bkgInvoice->save();
			}
			else
			{
				$emailObj										 = new emailWrapper();
				$emailObj->cancellationWithoutRefundMail($model->bkg_id, $refundAmount);
				$model->bkgInvoice->biv_refund_approval_status	 = 5; //no refund to be proccessed
				$model->bkgInvoice->save();
			}
			echo ' \nRefund booking:' . $model->bkg_booking_id . ' has been refunded with amount:' . $refundAmount;
		}
	}

	public function unsetRefundApprovalFlag($bkgId)
	{
		$sql	 = 'UPDATE `booking_invoice` SET `biv_refund_approval_status`= 0 WHERE `biv_bkg_id`=' . $bkgId;
		$rows	 = DBUtil::command($sql)->execute();
		return $rows;
	}

	public function addExtraCharge()
	{
		$success				 = true;
		$userInfo				 = UserInfo::getInstance();
		$bkgExtraCharge			 = $this->bkg_extra_km_charge;
		$bkgExtraTotalKm		 = $this->bkg_extra_km;
		$bkgExtraTollTax		 = $this->bkg_extra_toll_tax;
		$bkgExtraStateTax		 = $this->bkg_extra_state_tax;
		$bkgParkingCharge		 = $this->bkg_parking_charge;
		$vendorActualCollected	 = $this->bkg_vendor_actual_collected;
		$response				 = BookingSub::model()->addExtraCharges($this->biv_bkg_id, $bkgExtraCharge, $bkgExtraTotalKm, $bkgExtraTollTax, $bkgExtraStateTax, $bkgParkingCharge, $userInfo, $vendorActualCollected);
		if (!$response)
		{
			$success = false;
		}

		return $success;
	}

	public function getProfitabilityByZone($param, $type = '')
	{
		if ($param == '')
		{
			$cond = ' AND bkg.bkg_pickup_date BETWEEN (NOW() - INTERVAL 30 DAY) AND NOW()';
		}
		else
		{
			$cond = $param;
		}
		$sql = "SELECT
				CASE
				WHEN stt.stt_zone = 1 THEN 'North'
				WHEN stt.stt_zone = 2 THEN 'West'
				WHEN stt.stt_zone = 3 THEN 'Central'
				WHEN stt.stt_zone = 4 THEN 'South'
				WHEN stt.stt_zone = 5 THEN 'East'
				WHEN stt.stt_zone = 6 THEN 'North East'
				WHEN stt.stt_zone = 7 THEN 'South'
				ELSE '-'
				END
				   AS Region,
				z1.zon_name
				   AS fromZone,
				z2.zon_name
				   AS toZone,
				     COUNT(bkg_id) AS CountBooking,
				((SUM(biv.bkg_gozo_amount) / SUM(biv.bkg_total_amount)) * 100)     AS Profit
				FROM booking bkg
					 INNER JOIN booking_invoice biv ON biv.biv_bkg_id = bkg.bkg_id
					 INNER JOIN booking_trail btr ON btr.btr_bkg_id = bkg.bkg_id
					 INNER JOIN svc_class_vhc_cat scvc  ON scvc.scv_id = bkg.bkg_vehicle_type_id
					 INNER JOIN vehicle_category vhc ON scvc.scv_vct_id = vhc.vct_id
					 JOIN cities a ON a.cty_id = bkg.bkg_from_city_id
					 JOIN cities b ON b.cty_id = bkg.bkg_to_city_id
					 JOIN states stt ON stt.stt_id = a.cty_state_id
					 JOIN states s2 ON s2.stt_id = b.cty_state_id
					 JOIN zone_cities zc1 ON zc1.zct_cty_id = bkg.bkg_from_city_id
					 JOIN zone_cities zc2 ON zc2.zct_cty_id = bkg.bkg_to_city_id
					 JOIN zones z1 ON z1.zon_id = zc1.zct_zon_id
					 JOIN zones z2 ON z2.zon_id = zc2.zct_zon_id
				WHERE 1  $cond AND bkg.bkg_status IN (6, 7)
				GROUP BY stt.stt_zone, z1.zon_id, z2.zon_id";
		if ($type == 'Command')
		{
			$sqlCount		 = "SELECT
				        COUNT(bkg_id) AS CountBooking
						FROM booking bkg
						INNER JOIN booking_invoice biv ON biv.biv_bkg_id = bkg.bkg_id
						INNER JOIN booking_trail btr ON btr.btr_bkg_id = bkg.bkg_id
						INNER JOIN svc_class_vhc_cat scvc  ON scvc.scv_id = bkg.bkg_vehicle_type_id
						INNER JOIN vehicle_category vhc ON scvc.scv_vct_id = vhc.vct_id
						JOIN cities a ON a.cty_id = bkg.bkg_from_city_id
						JOIN cities b ON b.cty_id = bkg.bkg_to_city_id
						JOIN states stt ON stt.stt_id = a.cty_state_id
						JOIN states s2 ON s2.stt_id = b.cty_state_id
						JOIN zone_cities zc1 ON zc1.zct_cty_id = bkg.bkg_from_city_id
						JOIN zone_cities zc2 ON zc2.zct_cty_id = bkg.bkg_to_city_id
						JOIN zones z1 ON z1.zon_id = zc1.zct_zon_id
						JOIN zones z2 ON z2.zon_id = zc2.zct_zon_id
						WHERE 1 $cond AND bkg.bkg_status IN (6, 7)
						GROUP BY stt.stt_zone, z1.zon_id, z2.zon_id";
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['CountBooking', 'Profit'],
					'defaultOrder'	 => 'Profit DESC'], 'pagination'	 => ['pageSize' => 25],
			]);
			return $dataprovider;
		}
		else
		{
			$recordset = DBUtil::queryAll($sql, DBUtil::SDB());
			return $recordset;
		}
	}

	public function getProfitabilityZone($param, $type = '')
	{
		if ($param == '')
		{
			$cond = ' AND bkg_pickup_date BETWEEN (NOW() - INTERVAL 30 DAY) AND NOW()';
		}
		else
		{
			$cond = $param;
		}
		$sql = "SELECT
				CASE
				WHEN stt.stt_zone = 1 THEN 'North'
				WHEN stt.stt_zone = 2 THEN 'West'
				WHEN stt.stt_zone = 3 THEN 'Central'
				WHEN stt.stt_zone = 4 THEN 'South'
				WHEN stt.stt_zone = 5 THEN 'East'
				WHEN stt.stt_zone = 6 THEN 'North East'
				WHEN stt.stt_zone = 7 THEN 'South'
				ELSE '-'
				END  AS region,
				stt.stt_name As stateName,
				z1.zon_name	AS sourceZone,
				COUNT(bkg_id) AS CountBooking,
				SUM(IF(bkg_status IN(6,7), 1, 0)) AS totalCompleted,
				SUM(IF(bkg_status IN(2,3,5,6,7,9), 1, 0)) AS totalConverted,
				SUM(IF(bkg_status = 9, 1, 0)) AS totalCancelled,
				SUM(IF(bkg_status <> 9 AND bkg_reconfirm_flag=1, (bkg_net_base_amount), 0)) AS bookingAmount,
				SUM(IF(bkg_status <> 9 AND bkg_reconfirm_flag=1, bkg_gozo_amount- IFNULL(bkg_credits_used,0),0)) AS gozoAmount,
				SUM(IF(bkg_status <> 9 AND bkg_reconfirm_flag=1, bkg_total_amount-bkg_quoted_vendor_amount-IFNULL(bkg_credits_used,0)-IFNULL(bkg_service_tax,0)-IFNULL(bkg_partner_commission,0), 0)) AS quote_amount
				FROM booking
				JOIN booking_invoice biv ON biv.biv_bkg_id = bkg_id
				JOIN booking_trail btr ON btr.btr_bkg_id = bkg_id
				JOIN svc_class_vhc_cat scvc  ON scvc.scv_id = bkg_vehicle_type_id
				JOIN service_class  ON scvc.scv_scc_id = scc_id
				JOIN vehicle_category vhc ON scvc.scv_vct_id = vhc.vct_id
				JOIN cities a ON a.cty_id = bkg_from_city_id
				JOIN states stt ON stt.stt_id = a.cty_state_id
				JOIN zone_cities zc1 ON zc1.zct_cty_id = bkg_from_city_id
				JOIN zones z1 ON z1.zon_id = zc1.zct_zon_id
				WHERE 1  $cond
				GROUP BY stt.stt_zone, z1.zon_id";
		if ($type == 'Command')
		{
			$sqlCount		 = "SELECT
								COUNT(bkg_id) AS CountBooking
								FROM booking
								JOIN booking_invoice biv ON biv.biv_bkg_id = bkg_id
								JOIN booking_trail btr ON btr.btr_bkg_id = bkg_id
								JOIN svc_class_vhc_cat scvc  ON scvc.scv_id = bkg_vehicle_type_id
								JOIN service_class  ON scvc.scv_scc_id = scc_id
								JOIN vehicle_category vhc ON scvc.scv_vct_id = vhc.vct_id
								JOIN cities a ON a.cty_id = bkg_from_city_id
								JOIN states stt ON stt.stt_id = a.cty_state_id
								JOIN zone_cities zc1 ON zc1.zct_cty_id = bkg_from_city_id
								JOIN zones z1 ON z1.zon_id = zc1.zct_zon_id
								WHERE 1 $cond
								GROUP BY stt.stt_zone, z1.zon_id";
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['CountBooking', 'totalCompleted', 'totalConverted', 'totalCancelled', 'bookingAmount', 'gozoAmount', 'quote_amount'],
					'defaultOrder'	 => 'gozoAmount ASC'], 'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			$recordset = DBUtil::query($sql, DBUtil::SDB());
			return $recordset;
		}
	}

	public function getProfitabilityByCabType($param)
	{
		if ($param == '')
		{
			$cond = ' AND bkg.bkg_pickup_date BETWEEN (NOW() - INTERVAL 30 DAY) AND NOW()';
		}
		else
		{
			$cond = $param;
		}
		$sql		 = "SELECT
				vhc.vct_label,
				(
					(
						SUM(biv.bkg_gozo_amount) / SUM(biv.bkg_total_amount)
					) * 100
				) AS 'Profit'
			FROM
				booking bkg
			INNER JOIN booking_invoice biv ON
				biv.biv_bkg_id = bkg.bkg_id
			INNER JOIN booking_trail btr ON
				btr.btr_bkg_id = bkg.bkg_id
			INNER JOIN svc_class_vhc_cat scvc ON
				scvc.scv_id = bkg.bkg_vehicle_type_id
			INNER JOIN vehicle_category vhc ON
				scvc.scv_vct_id = vhc.vct_id
            JOIN cities a ON a.cty_id = bkg.bkg_from_city_id
            JOIN cities b ON b.cty_id = bkg.bkg_to_city_id
            JOIN states stt ON stt.stt_id = a.cty_state_id
			WHERE 1
				$cond AND bkg.bkg_status IN(6, 7)
			GROUP BY
				scvc.scv_vct_id";
		$recordset	 = DBUtil::queryAll($sql, DBUtil::SDB());
		return $recordset;
	}

	public function getProfitabilityByServiceTier($param)
	{
		if ($param == '')
		{
			$cond = ' AND bkg.bkg_pickup_date BETWEEN (NOW() - INTERVAL 30 DAY) AND NOW()';
		}
		else
		{
			$cond = $param;
		}
		$sql		 = "SELECT sc.scc_label,
						((SUM(biv.bkg_gozo_amount) / SUM(biv.bkg_total_amount)) * 100)   AS Profit
				 FROM booking bkg
					  INNER JOIN booking_invoice biv ON biv.biv_bkg_id = bkg.bkg_id
					  INNER JOIN booking_trail btr ON btr.btr_bkg_id = bkg.bkg_id
					  INNER JOIN svc_class_vhc_cat scvc
						 ON scvc.scv_id = bkg.bkg_vehicle_type_id
					  INNER JOIN service_class sc ON scvc.scv_scc_id = sc.scc_id
                      JOIN cities a ON a.cty_id = bkg.bkg_from_city_id
                      JOIN cities b ON b.cty_id = bkg.bkg_to_city_id
                      JOIN states stt ON stt.stt_id = a.cty_state_id
				 WHERE  1   $cond
					   AND bkg.bkg_status IN (6, 7);";
		$recordset	 = DBUtil::queryAll($sql, DBUtil::SDB());
		return $recordset;
	}

	

	/**
	 *
	 * @param integer $bkgId
	 * @return boolean
	 */
	public static function isPayable($bkgId)
	{
		$params	 = ['bookingId' => $bkgId];
		$sql = "SELECT
				IF((booking_trail.bkg_payment_expiry_time > NOW() AND booking_invoice.bkg_due_amount > 0),1,0) AS isPayable
				FROM `booking`
				INNER JOIN `booking_trail` ON booking_trail.btr_bkg_id = booking.bkg_id 
				INNER JOIN `booking_pref` ON booking_pref.bpr_bkg_id=booking.bkg_id 	
				INNER JOIN `booking_invoice` ON booking_invoice.biv_bkg_id = booking.bkg_id
				WHERE ((booking_pref.bkg_is_gozonow=1 AND booking.bkg_status=2 AND booking.bkg_reconfirm_flag=1) OR (booking_pref.bkg_is_gozonow=0 AND booking.bkg_status IN (1,15))) AND booking.bkg_id = :bookingId";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}


	

	/**
	 * @param string $promoCode
	 * @return Promos
	 * @throws Exception
	 */
	public function applyPromoCode($promoCode)
	{
		if ($promoCode == '')
		{
			return false;
		}

		$bkgModel = $this->bivBkg;

		/* @var $prmModel Promos */
		$prmModel = Promos::validateCode($bkgModel, $promoCode);
		if (!$prmModel)
		{
			$this->addError("bkg_promo1_code", "Invalid promo code");
			throw new Exception(json_encode($this->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
		if ($prmModel->hasErrors())
		{
			throw new Exception(json_encode($prmModel->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
		$this->evaluatePromo($prmModel);
		return $prmModel;
	}

	/**
	 *
	 * @param Promos $promoModel
	 */
	public function addPromo(Promos $promoModel)
	{
		$data					 = $promoModel->prmCal->calculate($this->bkg_base_amount);
		$this->bkg_promo1_id	 = $promoModel->prm_id;
		$this->bkg_promo1_code	 = $promoModel->prm_code;
		$this->bkg_promo1_amt	 = $data['cash'];
		$this->bkg_promo1_coins	 = $data['coins'];
		$this->bivPromos		 = $promoModel;
		return true;
	}

	/** @param Promos $promoModel */
	public function evaluatePromo($promoModel = null)
	{
		if ($promoModel)
		{
			$this->addPromo($promoModel);
		}
		$this->bkg_discount_amount = $this->bkg_promo1_amt;
		$this->calculateTotal();
		return true;
	}

	public function addGozoCoins($creditUsed = 0)
	{
		$this->bkg_credits_used = $creditUsed;
		$this->calculateTotal();
	}

	/**
	 *
	 * @param integer $coins
	 * @return boolean|\BookingInvoice
	 */
	public function evaluateGozoCoins($coins)
	{
		$model	 = $this;
		$userId	 = $model->bivBkg->bkgUserInfo->bkg_user_id;
		if ($userId == '')
		{
			return false;
		}
		$promoUsed		 = ($model->bkg_promo1_code != '' || $model->bkg_discount_amount > 0);
		$netBaseAmt = ($model->bkg_net_discount_amount>0 && $model->bkg_discount_amount == 0)?($model->bkg_net_base_amount + $model->bkg_net_discount_amount):$model->bkg_net_base_amount;
		$data			 = UserCredits::getApplicableCredits($userId, $netBaseAmt, !$promoUsed, $model->bivBkg->bkg_from_city_id, $model->bivBkg->bkg_to_city_id);
		$maxCredits		 = $data["credits"];
		$coinsApplied	 = min([$coins, $maxCredits]);
		$model->saveGozoCoins($coinsApplied);
		return $model;
	}

	public function saveGozoCoins($creditUsed = 0)
	{
		$this->bkg_temp_credits = ($creditUsed == null) ? 0 : $creditUsed;
	}

	/**
	 *
	 * @param integer $code
	 * @param integer $coins
	 */
	public function savePromoCoins($code, $coins)
	{
		if ($code == null || $code == '')
		{
			goto applyCoins;
		}

		$prmModel	 = Promos::validateCode($this->bivBkg, $code);
		$model		 = $this;
		if (!$prmModel || $prmModel->hasErrors())
		{
			goto applyCoins;
		}
		$this->addPromo($prmModel);
		applyCoins:
		if (UserInfo::getUser()->isGuest || $coins == 0 || $coins == '')
		{
			goto skipCoins;
		}

		$data				 = UserCredits::getApplicableCredits(UserInfo::getUserId(), $this->bkg_net_base_amount, !($this->bkg_discount_amount > 0), $this->bivBkg->bkg_from_city_id, $this->bivBkg->bkg_to_city_id);
		$creditUsed			 = $data['credits'];
		$refundCreditUsed	 = $data['refundCredits'];
		$totalMaxApplicable	 = $creditUsed + $refundCreditUsed;
		$this->saveGozoCoins(min([$creditUsed, $coins]));

		skipCoins:
		$this->save();

		if ($this->bkg_promo1_id > 0)
		{
			$params['blg_ref_id'] = BookingLog::REF_PROMO_USE;
			BookingLog::model()->createLog($this->bivBkg->bkg_id, "Promo '" . $this->bkg_promo1_code . "' applied", UserInfo::getInstance(), BookingLog::BOOKING_PROMO, false, $params);
		}

		if ($this->bkg_temp_credits > 0)
		{
			$params['blg_ref_id'] = BookingLog::REF_PROMO_GOZOCOINS_USE;
			BookingLog::model()->createLog($this->bivBkg->bkg_id, "Applied ₹" . $this->bkg_temp_credits . " credit against GozoCoins redeemed", UserInfo::getInstance(), BookingLog::BOOKING_PROMO, false, $params);
		}
	}

	/**
	 *
	 * @param string $code
	 * @param integer $coins
	 * @return type
	 * @throws Exception
	 */
	public function applyPromoCoins()
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$success	 = false;
		$transaction = DBUtil::beginTransaction();
		try
		{
			if ($this->bkg_promo1_id == '' && $this->bkg_promo1_code == "")
			{
				$this->bkg_discount_amount = 0;
				goto skipPromo;
			}

			if ($this->bkg_promo1_id != '')
			{
				$prmModel = Promos::model()->findByPk($this->bkg_promo1_id);
				if ($prmModel)
				{
					$code = $prmModel->prm_code;
				}
			}
			elseif ($this->bkg_promo1_code != "")
			{
				$code = $this->bkg_promo1_code;
			}

			if ($code == '')
			{
				$this->bkg_discount_amount = 0;
				goto skipPromo;
			}
			$prmModel = Promos::validateCode($this->bivBkg, $code);
			$this->evaluatePromo($prmModel);

			// Apply promo
			if ($this->bkg_discount_amount > 0)
			{
				Promos::model()->incrementCounter($this->bkg_promo1_id, $this->bivBkg->bkgUserInfo->bkg_user_id, $this->bivBkg->bkg_id);
				$params['blg_ref_id'] = BookingLog::REF_PROMO_APPLIED;
				BookingLog::model()->createLog($this->bivBkg->bkg_id, "Promo '" . $this->bkg_promo1_code . "' applied successfully. (confirmed)", UserInfo::getInstance(), BookingLog::BOOKING_PROMO, false, $params);
			}

			skipPromo:
			$coins = $this->bkg_temp_credits;
			if ($coins == 0 || $coins == '')
			{
				goto skipCoins;
			}

			$this->redeemGozoCoins($coins);
			$this->bkg_temp_credits = 0;

			skipCoins:
			if (!$this->save())
			{
				throw new Exception(json_encode($this->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			$success = DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			ReturnSet::setException($ex);
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $success;
	}

	/**
	 *
	 * @param Booking $model
	 * @param type $promoCode
	 * @return BookingInvoice
	 */
	public function removePromo()
	{
		$this->bkg_promo1_id		 = 0;
		$this->bkg_promo1_code		 = null;
		$this->bkg_promo1_amt		 = 0;
		$this->bkg_promo1_coins		 = 0;
		$this->bkg_discount_amount	 = 0;
		$this->bivPromos			 = null;

		$this->calculateTotal();
	}

	public static function updateOnAdditionalKm($distance, $duration, $bkgModel, $fare = [])
	{
		$additional_km									 = $distance - $bkgModel->bkg_trip_distance;
		$extra_charge									 = ROUND($additional_km * $bkgModel->bkgInvoice->bkg_rate_per_km_extra) | 0;
		$bkgModel->bkgInvoice->bkg_chargeable_distance	 = $distance;
		$bkgModel->bkg_trip_distance					 = $distance;
		$bkgModel->bkg_trip_duration					 = ($duration > $bkgModel->bkg_trip_duration) ? $duration : $bkgModel->bkg_trip_duration;
		$oldBaseFare									 = $bkgModel->bkgInvoice->bkg_base_amount;
		$bkgModel->bkgInvoice->bkg_base_amount			 = $bkgModel->bkgInvoice->bkg_base_amount + $extra_charge;

		$isFixedPrice							 = ($bkgModel->bkg_booking_type == 12) ? 0.80 : 0.85;
		$vendorExtraKmCharge					 = round($extra_charge * $isFixedPrice);
		$bkgModel->bkgInvoice->bkg_vendor_amount = $bkgModel->bkgInvoice->bkg_vendor_amount + $vendorExtraKmCharge;

		$bkgModel->bkgInvoice->calculateTotal();
		if (!$bkgModel->save())
		{
			throw new Exception("Error occurred while saving address");
		}
		if (!$bkgModel->bkgInvoice->save())
		{
			throw new Exception("Error occurred while saving address");
		}
		$fare = [
			'baseFare'			 => $bkgModel->bkgInvoice->bkg_base_amount,
			'discount'			 => $bkgModel->bkgInvoice->bkg_discount_amount,
			'netBaseFare'		 => ($bkgModel->bkgInvoice->bkg_base_amount - $bkgModel->bkgInvoice->bkg_discount_amount),
			'driverAllowance'	 => $bkgModel->bkgInvoice->bkg_driver_allowance_amount,
			'tollTax'			 => $bkgModel->bkgInvoice->bkg_toll_tax,
			'stateTax'			 => $bkgModel->bkgInvoice->bkg_state_tax,
			'gst'				 => $bkgModel->bkgInvoice->bkg_service_tax,
			'totalAmount'		 => $bkgModel->bkgInvoice->bkg_total_amount,
			'customerPaid'		 => $bkgModel->bkgInvoice->bkg_advance_amount,
			'gozoCoins'			 => $bkgModel->bkgInvoice->bkg_credits_used,
			'dueAmount'			 => $bkgModel->bkgInvoice->bkg_due_amount,
			'tollIncluded'		 => $bkgModel->bkgInvoice->bkg_is_toll_tax_included,
			'stateTaxIncluded'	 => $bkgModel->bkgInvoice->bkg_is_state_tax_included,
			'minPay'			 => $bkgModel->bkgInvoice->calculateMinPayment()
		];
		return ['additional_km' => $additional_km, 'extra_charge' => $extra_charge, 'oldBaseFare' => $oldBaseFare, 'fare' => $fare, 'status' => $bkgModel->bkg_status];
	}

	public static function processPartnerWallet()
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		AccountTransactions::processPendingPartnerWallet();
		$userInfo	 = UserInfo::model();
		$sql		 = "SELECT bkg.bkg_agent_id AS partnerId, SUM(atd1.adt_amount) AS creditRequired
						FROM booking bkg
						INNER JOIN account_trans_details atd ON atd.adt_trans_ref_id = bkg.bkg_id AND atd.adt_ledger_id = 13 AND atd.adt_active = 1
						INNER JOIN account_transactions act ON act.act_id = atd.adt_trans_id AND act.act_active = 1
						INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id = act.act_id AND atd1.adt_ledger_id = 49 AND atd1.adt_active = 1 AND atd1.adt_status = 1
						INNER JOIN booking_pref bp ON bp.bpr_bkg_id = bkg.bkg_id
						WHERE bkg_status IN (2,3,5,9) AND bkg.bkg_active = 1 AND atd1.adt_amount > 0  AND bkg.bkg_agent_id IS NOT NULL
							AND bkg.bkg_pickup_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 12 HOUR)
						GROUP BY bkg_agent_id
						ORDER BY bkg.bkg_pickup_date DESC";
		$data		 = DBUtil::query($sql, DBUtil::MDB());
		foreach ($data as $bkgData)
		{

			$date				 = DBUtil::getCurrentTime(); //date('Y-m-d H:i:s');
			$partnerId			 = $bkgData['partnerId'];
			$creditRequired		 = $bkgData['creditRequired'];
			Logger::trace("processPartnerWallet: partnerId" . $partnerId . "creditRequired" . $creditRequired);
			$availableLimit		 = Agents::getAvailableLimit($partnerId);
			$walletBalance		 = AccountTransactions::checkPartnerWalletBalance($partnerId, $date);
			Logger::trace("processPartnerWallet: availableLimit" . $availableLimit . "walletBalance" . $walletBalance);
			$netAvailableLimit	 = $availableLimit + $walletBalance;
			Logger::trace("processPartnerWallet: netAvailableLimit" . $netAvailableLimit);
			$creditAllowed		 = min([$creditRequired - $walletBalance, $availableLimit]);
			$creditAllowed		 = max([$creditAllowed, 0]);
			Logger::trace("processPartnerWallet: creditAllowed" . $creditAllowed);
			if ($creditAllowed > 0)
			{
				$actModel		 = AccountTransactions::issuePartnerWallet($partnerId, $creditAllowed, '', "Amount added to wallet", UserInfo::model());
				$walletBalance	 += $creditAllowed;
				$availableLimit	 -= $creditAllowed;
			}
			Logger::trace("processPartnerWallet: actModel" . $actModel . "walletBalance" . $walletBalance . "availableLimit" . $availableLimit);
			self::validateWalletBalance($partnerId, $walletBalance, $availableLimit);
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
	}

	public static function validateWalletBalance($partnerId, $walletBalance, $availableLimit)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		Logger::trace("validateWalletBalance: partnerId" . $partnerId . "walletBalance" . $walletBalance . "availableLimit" . $availableLimit);
		$arr	 = [];
		$sql	 = "SELECT bkg.bkg_id AS bkgId, bkg.bkg_booking_id, bkg_status,
						SUM(atd1.adt_amount) AS creditApplied,
						SUM(IF(atd1.adt_status=1,atd1.adt_amount,0)) AS creditUsed,
						SUM(IF(atd1.adt_status=0,atd1.adt_amount,0)) AS creditPending
					FROM booking bkg
					INNER JOIN account_trans_details atd ON atd.adt_trans_ref_id = bkg.bkg_id AND atd.adt_ledger_id = 13 AND atd.adt_active = 1
					INNER JOIN account_transactions act ON act.act_id = atd.adt_trans_id AND act.act_active = 1
					INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id = act.act_id AND atd1.adt_ledger_id = 49
								AND atd1.adt_active = 1
					INNER JOIN booking_pref bp ON bp.bpr_bkg_id = bkg.bkg_id
					WHERE bkg_status IN(2,3,5,9) AND bkg.bkg_active = 1  AND bkg_agent_id=:agentId
						AND bkg.bkg_agent_id IS NOT NULL
						AND bkg.bkg_pickup_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 12 HOUR)
					GROUP BY bkg_id
					ORDER BY bkg.bkg_pickup_date ASC, bkg_id ASC";
		$rows	 = DBUtil::query($sql, DBUtil::MDB(), ['agentId' => $partnerId]);
		foreach ($rows as $row)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$creditUsed	 = $row['creditUsed'];
				$bkgId		 = $row['bkgId'];
				Logger::trace("validateWalletBalance: creditUsed" . $creditUsed . "bkgId" . $bkgId);
				if ($row['bkg_status'] == 9)
				{
					$walletBalance -= $row["creditApplied"];
					continue;
				}
				Logger::trace("validateWalletBalance: walletBalance" . $walletBalance . "bkgId" . $bkgId . "creditPending" . $row["creditPending"] . "creditApplied" . $row["creditApplied"]);
				if (($walletBalance > 0 && $row["creditPending"] == 0) || $row["creditApplied"] == 0)
				{
					Logger::trace("Set cancellation flag OFF: walletBalance" . $walletBalance . "bkgId" . $bkgId);
					$desc			 = "Set cancellation flag OFF";
					$btrModel		 = BookingTrail::updateCancelStatus($bkgId, 0, $desc);
					DBUtil::commitTransaction($transaction);
					$walletBalance	 -= $row["creditApplied"];
					continue;
				}
				Logger::trace("Set cancellation flag ON: walletBalance" . $walletBalance . "bkgId" . $bkgId);
				$desc		 = "Set cancellation flag ON";
				$btrModel	 = BookingTrail::updateCancelStatus($bkgId, 1, $desc);
				//$walletBalance	 += $creditUsed;
				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $e)
			{
				DBUtil::rollbackTransaction($transaction);
				ReturnSet::setException($e);
			}
			$btrModel->btrBkg->refresh();
			if ($btrModel->btr_auto_cancel_value == 1 && $btrModel->btrBkg->bkg_status != 9 && $btrModel->btr_auto_cancel_reason_id == 33)
			{
				$arr[]	 = $row['bkgId'];
				$arr1[]	 = $row['bkg_booking_id'];
			}
		}
		if (count($arr) > 0)
		{
			$BookingIds		 = implode(',', $arr);
			$BookingIds_1	 = implode(', ', $arr1);
			emailWrapper::sendCancelFlagNotificationToPartner($BookingIds, $partnerId);
			WhatsappLog::sendAlertForCreditLimitExhausted($partnerId, $BookingIds_1);
			Logger::trace("sendCancelFlagNotificationToPartner: walletBalance" . $walletBalance . "bkgId" . $BookingIds . "btr_auto_cancel_value" . $btrModel->btr_auto_cancel_value . "bkg_status" . $btrModel->btrBkg->bkg_status . "btr_auto_cancel_reason_id" . $btrModel->btr_auto_cancel_reason_id);
			Logger::warning("Auto Cancel Flag ON for BookingIds: {$BookingIds} walletBalance: {$walletBalance} partnerId: {$partnerId}", true);
		}
		Logger::trace("End process: walletBalance" . $walletBalance . "btr_auto_cancel_value" . $btrModel->btr_auto_cancel_value . "bkg_status" . $btrModel->btrBkg->bkg_status . "btr_auto_cancel_reason_id" . $btrModel->btr_auto_cancel_reason_id);
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
	}

	/**
	 * This function is used for generating the invoice link
	 * @param type $bkgCode
	 * @return type
	 */
	public static function getLink($bkgCode)
	{
		$response = Booking::validateUser($bkgCode);
		if (!$response->getStatus())
		{
			return $response;
		}
		$hash		 = Yii::app()->shortHash->hash($response->getData());
		$fileLink	 = Yii::app()->createAbsoluteUrl('booking/invoice?bkgId=' . $response->getData() . '&hash=' . $hash . '&email=1');
		$response->setData($fileLink);
		return $response;
	}

	public function saveInvoice()
	{

		$response = $this->addExtraCharge();
		if (!$response)
		{
			throw new Exception("Failed to save invoice.", ReturnSet::ERROR_FAILED);
		}
		return $response;
	}

	/**
	 *
	 * @param BookingInvoice $model
	 * @return type
	 */
	public static function calculateBonus($model)
	{
		$bonus = (($model->bkg_base_amount * Yii::app()->params['bonusPercentage'] ) / 100);
		return round($bonus);
	}

	/**
	 *
	 * @param integer $bkgId
	 * @return boolean
	 */
	public function getBonusAmount($bkgId)
	{
		$model = $this->getByBookingID($bkgId);
		if ($model->bkg_promo1_coins > 0 && $model->bivPromos->prm_activate_on != 1 && in_array($model->bivPromos->prmCal->pcn_type, [2, 3]))
		{
			return $model->bkg_promo1_coins;
		}
		else
		{
			return false;
		}
	}

	/**
	 *
	 * @return array
	 */
	public static function gozoCoinsPending()
	{
		$sql = "SELECT booking.bkg_id,booking_invoice.bkg_credits_used,booking_invoice.bkg_temp_credits, booking.bkg_pickup_date
					FROM `booking`
					INNER JOIN `booking_invoice` on booking_invoice.biv_bkg_id=booking.bkg_id
					WHERE booking.bkg_status IN (2,3,5)
					AND booking.bkg_pickup_date > NOW() AND bkg_due_amount >= booking_invoice.bkg_temp_credits
					AND booking_invoice.bkg_temp_credits > 0 AND  booking_invoice.bkg_credits_used=0";
		return DBUtil::queryAll($sql, DBUtil::MDB());
	}

	public function getAppliedGozoCoins()
	{
		$gozoCoins = $this->bkg_credits_used;
		if (in_array($this->bivBkg->bkg_status, [15]))
		{
			$gozoCoins = $this->bkg_temp_credits;
		}
		return $gozoCoins;
	}

	public static function updatePromoCoins()
	{
		$records = self::gozoCoinsPending();
		foreach ($records as $row)
		{
			$model = BookingInvoice::model()->getByBookingID($row['bkg_id']);
			$model->applyPromoCoins();
			echo "Gozo coins updated for Booking Id : " . $model->biv_bkg_id . " \n";
		}
	}

	/**
	 *
	 * @return array
	 */
	public static function pendingCashbackCoins()
	{
		$sql = "SELECT booking.bkg_id,booking_invoice.bkg_promo1_id, booking_invoice.bkg_promo1_code, user_credits.ucr_id, booking_invoice.bkg_promo1_amt,bkg_status, bkg_pickup_date, bkg_create_date
				FROM `booking`
				INNER JOIN `booking_invoice` ON booking_invoice.biv_bkg_id=booking.bkg_id AND booking_invoice.bkg_promo1_code='CASHBACK20'
				INNER JOIN `booking_user` on booking_user.bui_bkg_id=booking.bkg_id
				LEFT JOIN `user_credits` ON user_credits.ucr_user_id=booking_user.bkg_user_id AND user_credits.ucr_ref_id=booking.bkg_id AND user_credits.ucr_type=4
				WHERE booking.bkg_status IN (6,7) AND user_credits.ucr_id IS NULL";
		return DBUtil::queryAll($sql, DBUtil::MDB());
	}

	public static function updateCashbackCoins()
	{
		$records = self::pendingCashbackCoins();
		foreach ($records as $row)
		{
			$model = new BookingInvoice();
			$model->processPromoCoins($row['bkg_id']);
//BookingInvoice::processPromoCoins($row['bkg_id']);
			echo "Promocoins updated for Booking Id : " . $row['bkg_id'] . " <br>";
		}
	}

	/**
	 *
	 * @param BookingInvoice $model
	 */
	public static function calculatePromoCoins($model)
	{
		$bkInvModel = clone $model;
		if ($bkInvModel->bivBkg->bkgPref->bkg_is_gozonow == 1)
		{
			goto skipPromoCoins;
		}

		if ($bkInvModel->bkg_promo1_id > 0 || $bkInvModel->bkg_promo1_coins > 0)
		{
			$coins = $bkInvModel->bkg_promo1_coins;
		}
		else if ($bkInvModel->bivBkg->bkg_agent_id == 0)
		{
			$coins = round(($bkInvModel->bkg_net_base_amount * 0.1));
		}
		skipPromoCoins:

		return $coins;
	}

	/**
	 *
	 * @param type $bkgId
	 * @return boolean
	 */
	public function processPromoCoins($bkgId)
	{
		$model		 = BookingInvoice::model()->getByBookingID($bkgId);
		$userId		 = $model->bivBkg->bkgUserInfo->bkg_user_id;
		$promoCode	 = $model->bkg_promo1_code;

		if ($model->bkg_promo1_id > 0 || $model->bkg_promo1_coins > 0)
		{
			$amount = $model->bkg_promo1_coins;
		}
		else if ($model->bivBkg->bkg_agent_id == 0 && $model->bivBkg->bkgPref->bkg_is_gozonow == 0)
		{
			$amount = round(($model->bkg_net_base_amount * 0.1));
		}
		$cntCredits = UserCredits::validatePromoCoins($userId, $bkgId, $amount);
		if ($cntCredits > 0)
		{
			return false;
		}
		if ($model->bivBkg->bkg_agent_id > 0)
		{
			return false;
		}
		if (($amount == null || $amount == '0'))
		{
			return false;
		}
		Logger::trace("Promo Code :" . $model->bkg_promo1_code);
		Logger::trace("Promo Coins :" . $model->bkg_promo1_coins);
		if ($model->bkg_promo1_id > 0)
		{
			$promoMessage = "Coins added against promotion: " . $promoCode;
		}
		else
		{
			$promoMessage = "Coins added against bookingID: " . $model->bivBkg->bkg_booking_id;
		}
		return UserCredits::addCoins($userId, UserCredits::CREDIT_PROMO, null, $amount, $bkgId, $promoMessage, 1);
	}

	public static function validateDateRestriction($pickupDate)
	{
		$success		 = true;
		$isRestricted	 = Config::model()->getAccess('accounts.restriction.maxdatetime');
		if ($pickupDate <= $isRestricted)
		{
			$success = false;
		}
		return $success;
	}

	/**
	 *
	 * @return array
	 */
	public function getTopOffer()
	{
		$discount	 = $promoCoins	 = 0;
		$type		 = $value		 = 0;

		$model		 = $this;
		$bkgModel	 = $model->bivBkg;
		$rows		 = Promos::allApplicableCodes($bkgModel);
		if ($row		 = $rows->read())
		{
			$discount	 = $row['cashAmount'];
			$promoCoins	 = $row['coinsAmount'];
			$type		 = 1;
			$value		 = $row["prm_code"];
		}
		$promoUsed	 = ($bkgModel->bkgInvoice->bkg_promo1_code != '');
		$data		 = UserCredits::getApplicableCredits($bkgModel->bkgUserInfo->bkg_user_id, $model->bkg_base_amount, !$promoUsed, $bkgModel->bkg_from_city_id, $bkgModel->bkg_to_city_id);
		$maxCredits	 = $data['credits'];
		if ($maxCredits > $discount)
		{
			$type	 = 3;
			$value	 = $maxCredits;
		}

		return ["type" => $type, "value" => $value];
	}

	/**
	 *
	 * @param integer $bookingId
	 * @param integer $eventType		[ 1=>Apply Promo, 2=>Remove Promo, 3=>Apply Coins, 4=> Remove Coins , 5=> Apply Wallet, 6=> Remove Wallet ]
	 * @param integer $userId
	 * @param string $promoCode
	 * @param integer $walletAmount
	 * @param integer $gozocoins
	 * @return array
	 * @throws Exception
	 */
	public static function applyPromo($bookingId, $eventType, $userId, $promoCode, $walletAmount, $gozocoins)
	{
		/** @var $model BookingInvoice */
		$model = BookingInvoice::model()->getByBookingID($bookingId);

		if (!$model)
		{
			throw new Exception("Invalid Booking ", ReturnSet::ERROR_INVALID_DATA);
		}
		if ($model->bivBkg->bkgPref->bkg_is_gozonow == 1 && $eventType == 1)
		{
			throw new Exception("Promo can't be applicable to this booking.", ReturnSet::ERROR_INVALID_DATA);
		}
		if ($model->bivBkg->bkgPref->bkg_is_gozonow == 1 && $eventType == 3)
		{
			throw new Exception("Gozo coins can't be applicable to this booking.", ReturnSet::ERROR_INVALID_DATA);
		}

		if ($eventType == 0)
		{
			$result		 = $model->getTopOffer();
			$eventType	 = $result["value"];
			switch ($result["type"])
			{

				case 1:
					$promoCode	 = $result["value"];
					$eventType	 = '1';
					break;
				case 3:
					$gozocoins	 = $result["value"];
					$eventType	 = '3';
					break;
				default:
					break;
			}
		}

		// Temporary check added for User Ids
		if (in_array($userId, array(515581, 1143647)))
		{
			$walletAmount = 0;
		}


		// apply or remove promo code
		if ($promoCode == '' || in_array($eventType, [2, 3]))
		{
			$model->removePromo();
			goto skipPromo;
		}
		if ($promoCode != '' && !in_array($eventType, [2, 3]))
		{
			$model->applyPromoCode($promoCode);
		}

		skipPromo:

		// apply or remove gozoCoins
		if ($userId == '' || $gozocoins == 0 || $gozocoins == '' || in_array($eventType, [1, 4]))
		{
			goto skipCoins;
		}
		$model->evaluateGozoCoins($gozocoins);
		skipCoins:
		return ['model' => $model, 'eventType' => $eventType];
	}

	/**
	 *
	 * @param Booking $bkgModel Description
	 * @param integer $eventType
	 * @param integer $gozoCoins
	 * @param string $promoCode
	 * @param boolean $saveCode
	 * @throws Exception
	 */
	public static function evaluatePromoCoins($bkgModel, $eventType, $gozoCoins, $promoCode, $saveCode = true)
	{
		$model = clone $bkgModel;
		if ($model->bkgPref->bkg_is_gozonow == 1 && $eventType == 1)
		{
			$bkgModel->bkgInvoice->removePromo();
			$bkgModel->bkgInvoice->bkg_temp_credits = 0;
			$bkgModel->bkgInvoice->calculateTotal();
			$model->addError("bkg_id", "Promotions are not applicable in this booking.");
			throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
		if ($model->bkgPref->bkg_is_gozonow == 1 && $eventType == 3)
		{
			$bkgModel->bkgInvoice->removePromo();
			$bkgModel->bkgInvoice->bkg_temp_credits = 0;
			$bkgModel->bkgInvoice->calculateTotal();
			$model->addError("bkg_id", "Gozo coins can't be applied in this booking.");
			throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
		}

		if ($model->bkgPref->bkg_is_gozonow == 1)
		{
			$bkgModel->bkgInvoice->removePromo();
			$bkgModel->bkgInvoice->bkg_temp_credits = 0;
			$bkgModel->bkgInvoice->calculateTotal();
			return;
		}

		$bkgInvoice	 = clone $bkgModel->bkgInvoice;
		$userId		 = $model->bkgUserInfo->bkg_user_id;

		// apply or remove promo code
		if ($promoCode == '' || in_array($eventType, [2, 3]))
		{
			$bkgInvoice->removePromo();
			goto skipPromo;
		}
		if ($promoCode != '' && !in_array($eventType, [2]))
		{
			$bkgInvoice->applyPromoCode($promoCode);
		}

		$bkgInvoice->calculateTotal();

		skipPromo:
		// apply or remove gozoCoins
		if ($userId == '' || $gozoCoins == 0 || $gozoCoins == '' || in_array($eventType, [4]))
		{
			$bkgInvoice->bkg_temp_credits = 0;
			$bkgInvoice->calculateTotal();
			goto skipCoins;
		}

		$bkgInvoice->evaluateGozoCoins($gozoCoins);
		$bkgInvoice->calculateTotal();

		skipCoins:
		$bkgModel->bkgInvoice->bkg_promo1_code	 = $bkgInvoice->bkg_promo1_code;
		$bkgModel->bkgInvoice->bkg_promo1_id	 = $bkgInvoice->bkg_promo1_id;
		$bkgModel->bkgInvoice->bkg_temp_credits	 = $bkgInvoice->bkg_temp_credits;
		$bkgModel->bkgInvoice->bkg_promo1_amt	 = $bkgInvoice->bkg_promo1_amt;
		$bkgModel->bkgInvoice->bkg_promo1_coins	 = $bkgInvoice->bkg_promo1_coins;
		if (in_array($eventType, [1, 2, 3]) && ($bkgModel->bkgInvoice->bkg_promo1_id != $bkgInvoice->bkg_promo1_id || $bkgModel->bkgInvoice->bkg_discount_amount != $bkgInvoice->bkg_discount_amount))
		{
			$bkgModel->bkgInvoice->bkg_discount_amount = $bkgInvoice->bkg_discount_amount;
		}
		$bkgModel->bkgInvoice->calculateTotal();
		if ($saveCode)
		{
			$success = $bkgModel->bkgInvoice->save();
			if (!$success)
			{
				throw new Exception(json_encode($bkgModel->bkgInvoice->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
		}
		$bkgModel->bkgInvoice = $bkgInvoice;
	}

	/**
	 *
	 * @param integer $bookingId
	 * @param integer $eventType [ 5=> Apply Wallet, 6=> Remove Wallet ]
	 * @param integer $userId
	 * @param integer $walletAmount
	 * @return array
	 * @throws Exception
	 */
	public static function applyWallet($bookingId, $eventType, $userId, $walletAmount)
	{
		/** @var $model BookingInvoice */
		$model = BookingInvoice::model()->getByBookingID($bookingId);
		if (!$model)
		{
			throw new Exception("Invalid Booking ", ReturnSet::ERROR_INVALID_DATA);
		}

		if ($eventType == 6 || $userId == '' || $walletAmount == 0)
		{
			$model->removeWallet();
		}

		if ($userId > 0 && $walletAmount > 0)
		{
			$model->evaluateWallet($walletAmount);
		}
		return ['model' => $model, 'eventType' => $eventType];
	}

	/**
	 *
	 * @param integer $bkgId
	 * @param integer $useCoins
	 * @return ReturnSet
	 * @throws Exception
	 */
	public static function applyGozocoins($bkgId, $useCoins)
	{
		$returnSet = new ReturnSet();
		try
		{
			$model = BookingInvoice::model()->getByBookingID($bkgId);
			if (!$model)
			{
				throw new Exception("Invalid Booking", ReturnSet::ERROR_INVALID_DATA);
			}

			$model->redeemGozoCoins($useCoins);
			$returnSet->setStatus(true);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			Logger::exception($ex);
		}
		return $returnSet;
	}

	/**
	 *
	 * @param Booking $model
	 * @param integer $useCoins
	 * @return type
	 */
	public function calculateCoins($model, $useCoins)
	{

		if ($useCoins > $model->bkg_total_amount)
		{
			$useCoins = $model->bkg_total_amount;
		}

		if (UserInfo::getUserType() == 4)
		{
			$model->bkg_credits_used = ($model->bkg_credits_used + $useCoins);
		}
		else
		{
			$useCoins				 = ($model->bkg_credits_used > 0) ? $model->bkg_credits_used : $useCoins;
			$model->bkg_credits_used = $useCoins;
		}
		$model->calculateTotal();
		return $model;
	}

	/**
	 * @param integer $amount
	 * @return bkgInvoice
	 */
	public function evaluateWallet($amount)
	{
		$model							 = $this;
		$userId							 = $model->bivBkg->bkgUserInfo->bkg_user_id;
		$totWalletBalance				 = UserWallet::getBalance($userId);
		$getWalletAmount				 = min([$totWalletBalance, $amount, $model->bkg_due_amount]);
		$model->bkg_wallet_used			 = $getWalletAmount; //self::getMinWalletBalance($getWalletAmount);
		$model->bkg_is_wallet_selected	 = ($getWalletAmount > 0) ? 1 : 0;
		$model->save();
		$model->bkg_advance_amount		 += $model->bkg_wallet_used;
		$model->calculateTotal();
		return $model;
	}

	/**
	 *
	 * @param Booking $model
	 * @return bkgInvoice
	 */
	public function removeWallet()
	{
		$model							 = $this;
		$walletUsed						 = $model->bkg_wallet_used;
		$model->bkg_wallet_used			 = 0;
		$model->bkg_is_wallet_selected	 = 0;
		$model->save();
		$model->calculateTotal();
		return $model;
	}

	/**
	 *
	 * @param integer $amount
	 * @param string $validity
	 * @return AccountTransactions
	 * @throws Exception
	 */
	public function refundGozoCoins($amount, $validity = null, UserInfo $userInfo = null)
	{
		if ($userInfo == null)
		{
			$userInfo = UserInfo::getInstance();
		}
		$remarks	 = "Gozocoins ($amount) refunded for booking id - " . $this->bivBkg->bkg_booking_id;
		$transModel	 = UserCredits::addCoins($this->bivBkg->bkgUserInfo->bkg_user_id, UserCredits::CREDIT_BOOKING, null, $amount, $this->bivBkg->bkg_id, $remarks, 1, $validity);
		if ($transModel)
		{
			BookingLog::model()->createLog($this->biv_bkg_id, $remarks, $userInfo, BookingLog::REFUND_GOZOCOIN_COMPLETED);
		}
		return $transModel;
	}

	/**
	 *
	 * @param integer $useCoins
	 * @return int
	 * @throws Exception
	 */
	public function redeemGozoCoins($useCoins)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$transaction = DBUtil::beginTransaction();
		try
		{
			$model = $this->bivBkg;
			if (!$model)
			{
				throw new Exception("Invalid Booking", ReturnSet::ERROR_FAILED);
			}
			$userId					 = $model->bkgUserInfo->bkg_user_id;
			$coinsUsed				 = $this->bkg_credits_used;
			Logger::trace("temp_credits_used : " . $this->bkg_temp_credits);
			Logger::trace("try for credits_used : " . $this->bkg_credits_used);
			$usePromoCoins			 = $this->bkg_discount_amount == 0;
			$coinsApplied			 = UserCredits::processCredits($this->bkg_net_base_amount, $userId, $useCoins, Accounting::LI_BOOKING, $model->bkg_id, $model->bkg_from_city_id, $model->bkg_to_city_id, $usePromoCoins, $coinsUsed);
			$this->bkg_credits_used	 += $coinsApplied;
			$this->calculateDues();
			Logger::trace("after redeemed credits_used : " . $this->bkg_credits_used . " for bkg Id " . $this->biv_bkg_id);
			Logger::trace("after redeemed due amount : " . $this->bkg_due_amount);
			$success				 = $this->save();
			BookingLog::model()->createLog($this->biv_bkg_id, "Gozo coins worth ₹" . $coinsApplied . " redeemed ", UserInfo::getInstance(), BookingLog::BOOKING_PROMO, false, ['blg_ref_id' => BookingLog::REF_PROMO_GOZOCOINS_APPLIED]);
			if (!$success)
			{
				throw new Exception(json_encode($this->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $exc)
		{
			DBUtil::rollbackTransaction($transaction);
			throw $exc;
		}

		return $coinsApplied;
	}

	/**
	 *
	 * @param integer $bcbId
	 * @return ReturnSet
	 * @throws Exception
	 */
	public static function getBKGAmount($bcbId)
	{
		$sql	 = "SELECT booking_invoice.bkg_vendor_amount FROM booking
			    INNER JOIN booking_invoice ON biv_bkg_id=bkg_id
			    INNER JOIN booking_cab ON bcb_id=bkg_bcb_id AND bcb_id=" . $bcbId;
		$result	 = DBUtil::command($sql)->queryScalar();
		return $result;
	}

	/**
	 * This function used to return price details of applicable addons
	 * @param type $addons array of addons
	 * @param type $bkgInvoice BookingInvoice
	 */
	public static function getRatesWithAddons($addons, $bkgInvoice)
	{
		$rates = [];
		foreach ($addons as $addon)
		{
			$bkgInvoiceCln						 = clone $bkgInvoice;
			$bkgInvoiceCln->bkg_addon_charges	 = $addon['addOnCharge'];
			$bkgInvoiceCln->calculateTotal();
			$rates[$addon['id']]				 = $bkgInvoiceCln;
		}
		return $rates;
	}

	public function applyAddon($addonId, $addonType = 1)
	{
		$addonDetails = json_decode($this->bkg_addon_details, true);
		if ($addonType == 1 || $addonDetails[0]['adn_type'] == 1 || $addonDetails[1]['adn_type'] == 1)
		{
			$addonArray	 = AddonCancellationPolicy::getById($addonId, $this->bkg_base_amount);
			$addonKey	 = array_search(1, array_column($addonDetails, 'adn_type'));
			$addonKey	 = ($addonKey == null) ? 0 : $addonKey;

			if ((count($addonDetails) > 0) && in_array(1, array_column($addonDetails, 'adn_type')))
			{
				$addonKey = array_search(1, array_column($addonDetails, 'adn_type'));
			}
			if ((count($addonDetails) > 0) && !in_array(1, array_column($addonDetails, 'adn_type')))
			{
				$addonKey = 1;
			}
		}
		if ($addonType == 2 || $addonDetails[0]['adn_type'] == 2 || $addonDetails[1]['adn_type'] == 2)
		{

			$addonModelArray = AddonCabModels::getById($addonId, $this->bkg_base_amount);
			$addonKeyModel	 = array_search(2, array_column($addonDetails, 'adn_type'));
			$addonKeyModel	 = ($addonKeyModel == null) ? 0 : $addonKeyModel;

			if ((count($addonDetails) > 0) && in_array(2, array_column($addonDetails, 'adn_type')))
			{
				$addonKeyModel = array_search(2, array_column($addonDetails, 'adn_type'));
			}
			if ((count($addonDetails) > 0) && !in_array(2, array_column($addonDetails, 'adn_type')))
			{
				$addonKeyModel = 1;
			}
		}


		if ($addonType == 1)
		{
			$addonDetails[$addonKey]['adn_type']	 = 1;
			$addonDetails[$addonKey]['adn_id']		 = $addonArray['id'];
			$addonDetails[$addonKey]['adn_value']	 = $addonArray['cost'];
		}
		if ($addonType == 2)
		{
			$addonDetails[$addonKeyModel]['adn_type']	 = 2;
			$addonDetails[$addonKeyModel]['adn_id']		 = $addonModelArray['id'];
			$addonDetails[$addonKeyModel]['adn_value']	 = $addonModelArray['cost'];
		}
		if ($addonId == 0 && $addonType == 1)
		{
			array_splice($addonDetails, $addonKey, 1);
		}
		if ($addonId == 0 && $addonType == 2)
		{
			array_splice($addonDetails, $addonKeyModel, 1);
		}
		$this->bkg_addon_charges = $addonDetails[0]['adn_value'] + $addonDetails[1]['adn_value'];
		$this->bkg_addon_details = (!empty($addonDetails)) ? json_encode($addonDetails) : null;
		$this->calculateTotal();
		$this->save();
	}

	public function applyExtraDiscount($bkgId, $disAmount, $disReason)
	{
		$model		 = BookingInvoice::model()->getByBookingID($bkgId);
		$transaction = DBUtil::beginTransaction();
		try
		{
			if ($disAmount > 0 && $model->bkg_total_amount > $disAmount)
			{
				$model->bkg_extra_discount_amount = $disAmount;
				if ($model->save())
				{
					$desc	 = "One-Time Price: $disAmount Added (Reason: " . $disReason . ")";
					$eventId = BookingLog::BOOKING_ONE_TIME_ADJUSTMENT;
					BookingLog::model()->createLog($bkgId, $desc, UserInfo:: getInstance(), $eventId, false);
					$model->refresh();
					$model->calculateTotal();
					$model->save();
					DBUtil::commitTransaction($transaction);
				}
				else
				{
					return false;
				}
			}
			else
			{
				$return['message'] = $this->getError($bkgId);
				return false;
			}
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$this->addError($bkgId, $e->getMessage());
			$return['error'] = $this->getErrors();
			return false;
		}
		return true;
	}

	/**
	 * @param int|BookingInvoice $bkgId BookingID/BookingInvoice Model
	 * @param \Stub\common\ExtraCharges $extraCharges
	 * @return BookingInvoice
	 * @throws ModelValidationException
	 */
	public static function prepareInvoice($bkgId, $extraCharges, $driverCollected)
	{
		Logger::setModelCategory(_CLASS_, _FUNCTION_);
		$bivModel = $bkgId;
		if (!($bkgId instanceof BookingInvoice))
		{
			$bivModel = BookingInvoice::model()->getByBookingID($bkgId);
		}

		BookingInvoice::addExtraCharges($bivModel, $extraCharges);
		$bivModel->updateInvoice($driverCollected);
		return $bivModel;
		Logger::unsetModelCategory(_CLASS_, _FUNCTION_);
	}

	public function updateInvoice($driverCollected = 0)
	{
		Logger::setModelCategory(_CLASS_, _FUNCTION_);
		$this->updateCreditDuty($driverCollected);
		$this->refresh();
		$this->bkg_vendor_actual_collected	 = round($driverCollected);
		$this->bkg_vendor_collected			 = round($this->bkg_total_amount - $this->bkg_net_advance_amount);
		$vendorCollectedDiff				 = ($this->bkg_vendor_actual_collected - $this->bkg_vendor_collected);
		if ($vendorCollectedDiff >= -2 && $vendorCollectedDiff <= 2)
		{
			$this->bkg_vendor_actual_collected = $this->bkg_vendor_collected;
		}

		Logger::trace("vendor actual collected " . $this->bkg_vendor_actual_collected);
		Logger::trace("vendor collected " . $this->bkg_vendor_collected);

		$totalAmount = $this->bkg_total_amount;
		$serviceTaxRate = BookingInvoice::getGstTaxRate($this->bivBkg->bkg_agent_id, $this->bivBkg->bkg_booking_type);

		$this->bkg_due_amount = $this->bkg_total_amount - $this->bkg_net_advance_amount - $this->bkg_vendor_collected;
		$this->calculateTotal();

		if($this->bkg_due_amount >= -2 && $this->bkg_due_amount >= 2)
		{
			$this->bkg_total_amount = $totalAmount;
			$this->bkg_due_amount = $totalAmount - $this->getTotalPayment();
			$this->bkg_advance_amount = $this->bkg_advance_amount;
			$staxRate =  round($this->bkg_total_amount - $this->bkg_total_amount / (1 + (0.01 * $serviceTaxRate)));
			$this->bkg_service_tax = $staxRate;
		}

		Logger::trace("after calculation vendor actual collected " . $this->bkg_vendor_actual_collected);
		Logger::trace("after calculation vendor collected " . $this->bkg_vendor_collected);

		if (!$this->save())
		{
			throw new ModelValidationException($this);
		}

		$desc = "Invoice Prepared.";
		if ($driverCollected > 0)
		{
			$desc = " Driver Collected: ₹$driverCollected";
		}

		$userInfo	 = UserInfo::model();
		$eventid	 = BookingLog::REMARKS_ADDED;
		BookingLog::model()->createLog($this->biv_bkg_id, $desc, $userInfo, $eventid);
		Logger::unsetModelCategory(_CLASS_, _FUNCTION_);
	}

	/**
	 * @param int|BookingInvoice $bkgId BookingID/BookingInvoice Model
	 * @param \Stub\common\ExtraCharges $extraCharges
	 * @return BookingInvoice
	 * @throws ModelValidationException
	 */
	public static function addExtraCharges($bkgId, $extraCharges)
	{
		Logger::setModelCategory(_CLASS_, _FUNCTION_);
		#print_r($extraCharges);exit;
		$transaction = null;
		try
		{
			$bivModel = $bkgId;
			if (!($bkgId instanceof BookingInvoice))
			{
				$bivModel = BookingInvoice::model()->getByBookingID($bkgId);
			}

			$oldCharges		 = $bivModel->grossExtraCharges();
			$oldKMCharge	 = $bivModel->bkg_extra_km_charge;
			$oldMinCharge	 = $bivModel->bkg_extra_total_min_charge;

			$bivModel->bkg_extra_km_charge			 = $extraCharges->kmCharges;
			$bivModel->bkg_extra_km					 = $extraCharges->km;
			$bivModel->bkg_extra_state_tax			 = $extraCharges->stateTax;
			$bivModel->bkg_extra_toll_tax			 = $extraCharges->tollTax;
			$bivModel->bkg_parking_charge			 = $bivModel->bkg_parking_charge + $extraCharges->parking;
			$bivModel->bkg_extra_min				 = $extraCharges->extraMin;
			$bivModel->bkg_extra_total_min_charge	 = $extraCharges->extraMinCharges;

			Logger::trace("extra km charges" . $bivModel->bkg_extra_km_charge);
			Logger::trace("extra km state tax" . $bivModel->bkg_extra_state_tax);
			Logger::trace("extra km toll tax" . $bivModel->bkg_extra_toll_tax);
			Logger::trace("parking charge" . $bivModel->bkg_parking_charge);
			Logger::trace("extra km parking charge" . $extraCharges->parking);

			$bivModel->calculateTotal();

			if ($bivModel->grossExtraCharges() == $oldCharges)
			{
				goto end;
			}

			$serviceTaxRate	 = self::getGstTaxRate($bivModel->bivBkg->bkg_agent_id, $bivModel->bivBkg->bkg_booking_type);
			$staxRate		 = (1 + ($serviceTaxRate / 100));

			$bivModel->bkg_extra_km_charge	 = round($bivModel->bkg_extra_km_charge / $staxRate);
			$newExtraKmCharge				 = ($bivModel->bkg_extra_km_charge - $oldKMCharge) | 0;
			$isFixedPrice					 = ($bivModel->bivBkg->bkg_booking_type == 12) ? 0.80 : 0.85;
			$vendorExtraKmCharge			 = round($newExtraKmCharge * $isFixedPrice);
			#$totalVendorExtraCharge             = ($bivModel->grossExtraCharges() - $oldCharges) - $newExtraCharge + $vendorExtraKmCharge; // TO BE REMOVED

			$bivModel->bkg_extra_total_min_charge	 = ($bivModel->bkg_service_tax == 0) ? round($bivModel->bkg_extra_total_min_charge) : round($bivModel->bkg_extra_total_min_charge / $staxRate);
			$newExtraMinCharge						 = ($bivModel->bkg_extra_total_min_charge - $oldMinCharge);
			$vendorExtraMinCharge					 = round($newExtraMinCharge * $isFixedPrice);

			$newExtraCharge = (($newExtraKmCharge > 0) ? $newExtraKmCharge : $newExtraMinCharge) | 0;

			$totalVendorExtraCharge		 = ($bivModel->grossExtraCharges() - $oldCharges) - $newExtraCharge + $vendorExtraKmCharge + $vendorExtraMinCharge;
			$bivModel->bkg_vendor_amount += $totalVendorExtraCharge;

			Logger::trace("without gst extra km charges" . $bivModel->bkg_extra_km_charge);
			Logger::trace("without gst extra km state tax" . $bivModel->bkg_extra_state_tax);
			Logger::trace("without gst extra km toll tax" . $bivModel->bkg_extra_toll_tax);
			Logger::trace("without gst parking charge" . $bivModel->bkg_parking_charge);
			Logger::trace("without gst extra km parking charge" . $extraCharges->parking);

			$bivModel->calculateTotal();

			Logger::trace("after calculate extra km charges" . $bivModel->bkg_extra_km_charge);
			Logger::trace("after calculate extra km state tax" . $bivModel->bkg_extra_state_tax);
			Logger::trace("after calculate extra km toll tax" . $bivModel->bkg_extra_toll_tax);
			Logger::trace("after calculate parking charge" . $bivModel->bkg_parking_charge);
			Logger::trace("after calculate extra km parking charge" . $extraCharges->parking);

			$transaction = DBUtil::beginTransaction();
			if (!$bivModel->save())
			{
				throw new ModelValidationException($bivModel);
			}

			$vendorShareText = "";
			if ($totalVendorExtraCharge > 0)
			{
				$tripAmount		 = $bivModel->bivBkg->bkgBcb->bcb_vendor_amount;
				$bivModel->bivBkg->bkgBcb->updateTripAmount($tripAmount + $totalVendorExtraCharge);
				$vendorShareText = ", Vendor Share for Extra Charges: $totalVendorExtraCharge";
			}
			$desc		 = $bivModel->getExtraChargesText() . $vendorShareText;
			$userInfo	 = UserInfo::getInstance();
			$eventid	 = BookingLog::BOOKING_AMOUNT_MODIFICATION;
			BookingLog::model()->createLog($bivModel->biv_bkg_id, $desc, $userInfo, $eventid);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			throw $e;
		}
		end:
		return $bivModel;
		Logger::unsetModelCategory(_CLASS_, _FUNCTION_);
	}

	public function getExtraChargesText()
	{
		$bivModel = $this;

		$desc = " Extra Charges - KM:" . $bivModel->bkg_extra_km . ", ExtraCharges: " . $bivModel->bkg_extra_km_charge
				. "Extra Time - MIN:" . $bivModel->bkg_extra_min . ", ExtraChargesMinute:" . $bivModel->bkg_extra_total_min_charge
				. ",  Toll:" . $bivModel->bkg_extra_toll_tax . ", StateTax: " . $bivModel->bkg_extra_state_tax
				. ", Parking " . $bivModel->bkg_parking_charge;

		return $desc;
	}

	public function verifyInvoice()
	{
		$minCap					 = 200;
		$maxCap					 = 4000;
		$midCapPercent			 = 25;
		$bkgAmountCapPercent	 = round(($this->bkg_total_amount * $midCapPercent) / 100);
		$maxExtraChargesAllowed	 = min(max($bkgAmountCapPercent, $minCap), $maxCap);
		$totalExtraCharge		 = $this->grossExtraCharges();
		$vendorActualCollected	 = $this->bkg_vendor_actual_collected;

		$arrDesc = [];
		if ($totalExtraCharge > $maxExtraChargesAllowed)
		{
			$arrDesc[] = "Extra Charges crossed max limit";
		}

		if ($vendorActualCollected != $this->bkg_vendor_collected)
		{
			$arrDesc[] = "Amount collected by driver does not match with due amount";
		}

		if ($this->bkg_is_toll_tax_included && $this->bkg_extra_toll_tax > 0)
		{
			$arrDesc[] = "Extra toll tax collected";
		}

		if ($this->bkg_is_state_tax_included && $this->bkg_extra_state_tax > 0)
		{
			$arrDesc[] = "Extra state tax collected";
		}

		if (count($arrDesc) == 0)
		{
			return false;
		}

		$desc = "Verify invoice amount (" . implode('. ', $arrDesc) . ")";
		// AS per direction given by sanjay sir we need to stop this process
		//ServiceCallQueue::addBookingCompleteReview($this->biv_bkg_id, $this->bivBkg->bkgBcb->bcb_driver_id, $desc);
		return true;
	}

	public function updateCreditDuty($driverCollected)
	{
		$amountDue = $this->bkg_total_amount - $this->bkg_net_advance_amount - $driverCollected;

		if ($this->bkg_corporate_remunerator != 2 || $amountDue <= 0)
		{
			return;
		}

		$date		 = new CDbExpression("NOW()");
		$bkgId		 = $this->biv_bkg_id;
		$bkgAgentId	 = $this->bivBkg->bkg_agent_id;

		$overdue	 = true;
		$accStatus	 = 1;
		$remarks	 = "Partner wallet used for due amount";
		AccountTransactions::usePartnerWallet($date, $amountDue, $bkgId, $bkgAgentId, $remarks, null, $overdue, $accStatus);

//update invoice
		$this->bkg_due_amount		 = 0;
		$this->bkg_vendor_collected	 = $driverCollected;
		$this->bkg_advance_amount	 += $amountDue;
		$this->bkg_corporate_credit	 += $amountDue;
		if (!$this->save())
		{
			throw new ModelValidationException($this);
		}
	}

	public static function getQuoteRateKm()
	{
		$sql = "SELECT
                    booking.bkg_id,
                    ROUND(booking_invoice.bkg_base_amount/booking.bkg_trip_distance,2) AS 'RatePerKilometer'
                    FROM booking
                    INNER JOIN booking_invoice ON  booking_invoice.biv_bkg_id=booking.bkg_id
                    WHERE 1
                    AND booking.bkg_trip_distance <> 0
                    AND biv_quote_base_rate_km  IS NULL
                    AND booking.bkg_create_date BETWEEN  CURDATE() AND  NOW() ORDER BY booking.bkg_id ASC";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public static function getInvoiceExtraTime($bkg_id)
	{

		$params	 = array('bkg_id' => $bkg_id);
		$sql	 = "SELECT bkg_extra_km,bkg_extra_km_charge,bkg_extra_min, bkg_extra_total_min_charge FROM  booking_invoice  where  biv_bkg_id  = :bkg_id ";
		$data	 = DBUtil::queryRow($sql, DBUtil::MDB(), $params);
		return $data;
	}

	public static function populateOnAdditionalKm($distance, $duration, $bkgModel, $fare = [])
	{

		$oldCharges = $bkgModel->bkgInvoice->grossExtraCharges();

		$additional_km									 = $distance - $bkgModel->bkg_trip_distance;
		$extra_charge									 = ROUND($additional_km * $bkgModel->bkgInvoice->bkg_rate_per_km_extra) | 0;
		$bkgModel->bkgInvoice->bkg_chargeable_distance	 = $distance;
		$bkgModel->bkg_trip_distance					 = $distance;
		$bkgModel->bkg_trip_duration					 = ($duration > $bkgModel->bkg_trip_duration) ? $duration : $bkgModel->bkg_trip_duration;
		$oldBaseFare									 = $bkgModel->bkgInvoice->bkg_base_amount;
		$bkgModel->bkgInvoice->bkg_base_amount			 = $bkgModel->bkgInvoice->bkg_base_amount + $extra_charge;

		$isFixedPrice							 = ($bkgModel->bkg_booking_type == 12) ? 0.80 : 0.85;
		$vendorExtraKmCharge					 = round($extra_charge * $isFixedPrice);
		$bkgModel->bkgInvoice->bkg_vendor_amount = $bkgModel->bkgInvoice->bkg_vendor_amount + $vendorExtraKmCharge;

		$bkgModel->bkgInvoice->calculateTotal();

		$fare = [
			'baseFare'			 => $bkgModel->bkgInvoice->bkg_base_amount,
			'discount'			 => $bkgModel->bkgInvoice->bkg_discount_amount,
			'netBaseFare'		 => ($bkgModel->bkgInvoice->bkg_base_amount - $bkgModel->bkgInvoice->bkg_discount_amount),
			'driverAllowance'	 => $bkgModel->bkgInvoice->bkg_driver_allowance_amount,
			'tollTax'			 => $bkgModel->bkgInvoice->bkg_toll_tax,
			'stateTax'			 => $bkgModel->bkgInvoice->bkg_state_tax,
			'gst'				 => $bkgModel->bkgInvoice->bkg_service_tax,
			'totalAmount'		 => $bkgModel->bkgInvoice->bkg_total_amount,
			'customerPaid'		 => $bkgModel->bkgInvoice->bkg_advance_amount,
			'gozoCoins'			 => $bkgModel->bkgInvoice->bkg_credits_used,
			'dueAmount'			 => $bkgModel->bkgInvoice->bkg_due_amount,
			'tollIncluded'		 => $bkgModel->bkgInvoice->bkg_is_toll_tax_included,
			'stateTaxIncluded'	 => $bkgModel->bkgInvoice->bkg_is_state_tax_included,
			'minPay'			 => $bkgModel->bkgInvoice->calculateMinPayment()
		];
		return ['additional_km' => $additional_km, 'extra_charge' => $extra_charge, 'oldBaseFare' => $oldBaseFare, 'fare' => $fare, 'status' => $bkgModel->bkg_status, 'model' => $bkgModel];
	}

	/**
	 *
	 * @param type $fromDate
	 * @param type $toDate
	 * @return type
	 */
	public static function getRevenueReportByDate($params, $groupBy = 'date', $status = '')
	{

		$fromDate			 = $params['from_date'] . ' 00:00:00';
		$toDate				 = $params['to_date'] . ' 23:59:59';
		$nonAPIPartner		 = $params['nonAPIPartner'];
		$b2cbookings		 = $params['b2cbookings'];
		$partnerId			 = $params['bkg_agent_id'];
		$dateFormat			 = ['date' => '%Y-%m-%d', 'week' => '%x-%v', 'month' => '%Y-%m'];
		$date				 = $dateFormat[$groupBy];
		$params				 = [];
		$params['format']	 = $date;
		$params['fromDate']	 = $fromDate;
		$params['toDate']	 = $toDate;

		$where = '';
		if ($status)
		{
			$where .= " AND bkg_status IN ($status)";
		}

		if ($b2cbookings)
		{
			$includeCondition[] = "(bkg_agent_id IS NULL OR bkg_agent_id = 1249 OR bkg_agent_id = '')";
		}

		if ($partnerId > 0)
		{
			$includeCondition[] = "(bkg_agent_id IN ($partnerId))";
		}

		if ($nonAPIPartner == 1)
		{
			$includeCondition[] = "(btr.bkg_platform NOT IN (7,9,10) AND bkg_agent_id IS NOT NULL)";
		}

		if (count($includeCondition) > 0)
		{
			$where .= " AND (" . implode(" OR ", $includeCondition) . ")";
		}

		$sql			 = "SELECT *, (cancelCharge - cancelBaseFare) as cancelGST, (gst + (cancelCharge - cancelBaseFare)) totalGst
				FROM (
					SELECT DATE_FORMAT(bkg_pickup_date, :format) as date,
					SUM(IF(bkg_status IN (2, 3, 5, 6, 7),bkg_total_amount,0)) as completedAmount,
					SUM(IF(bkg_status IN (2, 3, 5, 6, 7), bkg_net_base_amount + IFNULL(bkg_convenience_charge,0) + IFNULL(bkg_extra_total_min_charge,0), 0)) as netBaseAmount,
					SUM(IF(bkg_status IN (2, 3, 5, 6, 7), bkg_net_advance_amount, 0)) as advance, 
					SUM(IF(bkg_status IN (2, 3, 5, 6, 7), bkg_credits_used, 0)) as creditUsed,
					ROUND(SUM(IF(bkg_status IN (2, 3, 5, 6, 7), (IFNULL(bkg_toll_tax,0) + IFNULL(bkg_extra_toll_tax,0) + IFNULL(bkg_airport_entry_fee,0)), 0))) as totalTollTax,
					ROUND(SUM(IF(bkg_status IN (2, 3, 5, 6, 7), (IFNULL(bkg_state_tax,0) + IFNULL(bkg_extra_state_tax,0)), 0))) as totalStateTax,
					SUM(IF(bkg_status IN (2, 3, 5, 6, 7),IFNULL(bkg_driver_allowance_amount,0),0)) as totalDriverAllowance,
					ROUND(SUM(IF(bkg_status IN (2, 3, 5, 6, 7),IFNULL(bkg_parking_charge,0),0))) as parkingCharge,
					SUM(IF(bkg_status IN (2, 3, 5, 6, 7),IFNULL(bkg_service_tax,0),0)) as gst,
					ROUND(SUM(IF(bkg_status=9, (bkg_net_advance_amount - IFNULL(bkg_credits_used, 0)), 0))) as cancelCharge,
					ROUND(SUM(IF(bkg_status=9, ((bkg_net_advance_amount - IFNULL(bkg_credits_used,0)) / 1.05), 0))) as cancelBaseFare
					FROM booking
					INNER JOIN booking_invoice ON biv_bkg_id = booking.bkg_id
					INNER JOIN booking_trail btr ON btr.btr_bkg_id = bkg_id
					WHERE booking.bkg_pickup_date BETWEEN :fromDate AND :toDate $where
					GROUP BY date
				) a";
		$command		 = DBUtil::command($sql, DBUtil::SDB());
		$command->params = $params;
		$count			 = DBUtil::queryScalar("SELECT COUNT(1) FROM ({$command->getText()} ) temp", DBUtil::SDB(), $command->params);
		$dataProvider	 = new CSqlDataProvider($command, [
			"params"		 => $params,
			"totalItemCount" => $count,
			"params"		 => $command->params,
			'db'			 => DBUtil::SDB(),
			'pagination'	 => array('pageSize' => 50),
			'sort'			 => ['attributes'	 => ['date', 'completedAmount'],
				'defaultOrder'	 => 'date DESC'
			],
		]);
		return $dataProvider;
	}

	public function updateAddonsData($addondetails, $bkgModel)
	{
		$addondata			 = json_decode($addondetails, TRUE);
		$totalAddonCharge	 = 0;
		foreach ($addondata as $addonvalue)
		{
			$obj = json_decode($addonvalue);
			if ($obj->id > 0)
			{
				$addonArray[]		 = ['adn_type' => $obj->type, 'adn_id' => $obj->id, 'adn_value' => $obj->charge];
				$totalAddonCharge	 = $totalAddonCharge + $obj->charge;
				if ($obj->type == 1)
				{
					$cancelRuleId = AddonCancellationPolicy::getCancelRuleById($obj->id);
				}
			}
		}
		$transaction = Filter::beginTransaction();
		try
		{
			$bkgModel->bkgInvoice->bkg_addon_details = json_encode($addonArray);
			$bkgModel->bkgInvoice->bkg_addon_charges = $totalAddonCharge;
			$bkgModel->bkgInvoice->calculateTotal();
			$bkgModel->bkgInvoice->save();

			if ($cancelRuleId)
			{
				$bkgModel->bkgPref->bkg_cancel_rule_id = $cancelRuleId;
				$bkgModel->bkgPref->save();
			}

			$desc = 'Addons added successfully';
			BookingLog::model()->createLog($bkgModel->bkg_id, $desc, UserInfo::getInstance(), BookingLog::BOOKING_CREATED, false);
			Filter::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			Filter::rollbackTransaction($transaction);
			return false;
		}

		return true;
	}

	public function setAdminFee()
	{
		$this->bkg_admin_fee = Config::get('admin.assisted.markup');
	}

	public function processAdminFee($processOnce = true)
	{
		if ($this->bkg_admin_fee == 0 || !$processOnce)
		{
			$this->setAdminFee();
			$this->bkg_base_amount += $this->bkg_admin_fee;
		}
	}

	public function removeAdminFee()
	{
		if ($this->bkg_admin_fee > 0)
		{
			$this->bkg_admin_fee	 = 0;
			$this->bkg_base_amount	 -= $this->bkg_admin_fee;
		}
	}

	public static function getGozoAmountByTripId($tripId)
	{
		$sql = "SELECT SUM(bkg_gozo_amount - IFNULL(bkg_credits_used,0)) gozoAmount
				FROM booking
				INNER JOIN booking_invoice biv ON biv.biv_bkg_id = bkg_id
				INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg_bcb_id
				WHERE  bkg_status IN (2,3,5,6,7) AND bcb_id = {$tripId} AND bkg_reconfirm_flag = 1
				GROUP by bcb_id";

		$gozoAmount = DBUtil::queryScalar($sql);
		return $gozoAmount;
	}

	public function getZoneProfitability()
	{
		$fromDate			 = $toDate				 = $createFromDate		 = $createToDate		 = "";
		$type				 = $this->bkgZoneType;
		$bkgTypes			 = $this->bkgTypes;
		$sourcezone			 = $this->sourcezone;
		$region				 = $this->region;
		$state				 = $this->state;
		$assignCountDrop	 = $this->assignCountDrop;
		$assignCount		 = $this->assignCount;
		$lossCountDrop		 = $this->lossCountDrop;
		$lossCount			 = $this->lossCount;
		$netMarginDrop		 = $this->netMarginDrop;
		$netMargin			 = $this->netMargin;
		$b2cbookings		 = $this->b2cbookings;
		$mmtbookings		 = $this->mmtbookings;
		$nonAPIPartner		 = $this->nonAPIPartner;
		$excludeAT			 = $this->excludeAT;
		$bkg_vehicle_type_id = $this->bkg_vehicle_type_id;

		if ($this->from_date != '' && $this->to_date != '')
		{
			$fromDate	 = $this->from_date . ' 00:00:00';
			$toDate		 = $this->to_date . ' 23:59:59';
		}
		if ($this->create_from_date != '' && $this->create_to_date != '')
		{
			$createFromDate	 = $this->create_from_date . ' 00:00:00';
			$createToDate	 = $this->create_to_date . ' 23:59:59';
		}





		$params						 = [];
		$params['fromDate']			 = $fromDate;
		$params['toDate']			 = $toDate;
		$params['createFromDate']	 = $createFromDate;
		$params['createToDate']		 = $createToDate;
		$params['type']				 = $type;
		$params['bkgTypes']			 = $bkgTypes;
		$params['sourcezone']		 = $sourcezone;
		$params['region']			 = $region;
		$params['state']			 = $state;
		$params['assignCountDrop']	 = $assignCountDrop;
		$params['assignCount']		 = $assignCount;
		$params['lossCountDrop']	 = $lossCountDrop;
		$params['lossCount']		 = $lossCount;
		$params['netMarginDrop']	 = $netMarginDrop;
		$params['netMargin']		 = $netMargin;

		$select	 = "SELECT z.zon_id, z.zon_name fromZone, COUNT(DISTINCT bkg_id) as cnt,
			COUNT(DISTINCT IF(bkg_status IN (2), bkg_id, NULL)) as newCnt,
			COUNT(DISTINCT IF(bkg_status IN (2) AND bpr.bkg_manual_assignment=1 AND bpr.bkg_critical_assignment=0, bkg_id, NULL)) as newManualCnt,
			COUNT(DISTINCT IF(bkg_status IN (2) AND bpr.bkg_critical_assignment=1, bkg_id, NULL)) as newCriticalCnt,
			COUNT(DISTINCT IF(bkg_status IN (3,5,6,7), bkg_id, NULL)) as assignedCnt,
			COUNT(DISTINCT IF((biv.bkg_gozo_amount-biv.bkg_credits_used)<0, bkg_id, NULL)) AS lossCount,
			COUNT(DISTINCT IF(bkg_status IN (2) AND (biv.bkg_gozo_amount-biv.bkg_credits_used)<0, bkg_id, NULL)) AS newLossCount,
			COUNT(DISTINCT IF(bkg_status IN (3,5,6,7) AND (biv.bkg_gozo_amount-biv.bkg_credits_used)<0, bkg_id, NULL)) AS assignLossCount,
			(((SUM(biv.bkg_gozo_amount - biv.bkg_credits_used))*100)/SUM(biv.bkg_net_base_amount)) AS netMargin,
			((SUM(IF((biv.bkg_gozo_amount - biv.bkg_credits_used) < 0, (biv.bkg_gozo_amount - biv.bkg_credits_used), 0)) * 100) / SUM(IF((biv.bkg_gozo_amount - biv.bkg_credits_used) < 0, (biv.bkg_net_base_amount), 0))) netLossMargin,
			((SUM(IF((biv.bkg_gozo_amount - biv.bkg_credits_used) > 0, (biv.bkg_gozo_amount - biv.bkg_credits_used), 0)) * 100) / SUM(IF((biv.bkg_gozo_amount - biv.bkg_credits_used) > 0, (biv.bkg_net_base_amount), 0))) netProfitMargin,
			GROUP_CONCAT(DISTINCT IF((biv.bkg_gozo_amount-biv.bkg_credits_used)<0, bkg.bkg_id, NULL) SEPARATOR ', ') lossBkgIds,
			GROUP_CONCAT(DISTINCT IF(((biv.bkg_gozo_amount-biv.bkg_credits_used)*100/bkg_net_base_amount)>15, bkg.bkg_id, NULL) SEPARATOR ', ') highMarginBkgIds ";
		$sqlJoin = " FROM booking bkg
                    INNER JOIN svc_class_vhc_cat scv ON scv.scv_id = bkg.bkg_vehicle_type_id AND scv.scv_active=1
					INNER JOIN booking_invoice biv ON biv.biv_bkg_id=bkg.bkg_id
					INNER JOIN booking_trail btr ON btr.btr_bkg_id = bkg_id
					INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id=bkg.bkg_id
					INNER JOIN zone_cities zc ON zc.zct_cty_id=bkg.bkg_from_city_id AND zc.zct_active=1
					INNER JOIN zones z ON z.zon_id=zc.zct_zon_id AND z.zon_active=1 ";
		$where	 = " WHERE bkg_status IN (2,3,5,6,7) AND bkg_reconfirm_flag = 1 ";
		$groupBy = " GROUP BY z.zon_id ";
		if (count($bkg_vehicle_type_id) > 0)
		{
			$vtype	 = implode(",", $bkg_vehicle_type_id); //Added the code block
			$where	 .= " AND (scv_id IN ($vtype) OR scv_parent_id IN ($vtype))";
		}
		if ($fromDate != '' && $toDate != '')
		{
			$where .= " AND (bkg_pickup_date BETWEEN '{$fromDate}' AND '{$toDate}') ";
		}
		if ($createFromDate != '' && $createToDate != '')
		{
			$where .= " AND (bkg_create_date BETWEEN '{$createFromDate}' AND '{$createToDate}') ";
		}
		if ($type == 2)
		{
			$select	 .= ", tz.zon_name toZone ";
			$sqlJoin .= "INNER JOIN zone_cities tzc ON tzc.zct_cty_id=bkg.bkg_to_city_id AND tzc.zct_active=1
						INNER JOIN zones tz ON tz.zon_id=tzc.zct_zon_id AND tz.zon_active=1 ";
			$groupBy .= ", tz.zon_id ";
		}
		elseif ($type == 3)
		{
			$select	 .= ", s.stt_name toZone ";
			$sqlJoin .= "INNER JOIN cities c ON c.cty_id=bkg.bkg_to_city_id AND c.cty_active=1
						INNER JOIN states s ON s.stt_id=c.cty_state_id AND s.stt_active = '1' ";
			$groupBy .= ", s.stt_id";
		}
		elseif ($type == 4)
		{
			$select	 .= ", scv.scv_label cabType ";
			//$sqlJoin .= "INNER JOIN svc_class_vhc_cat scv ON scv.scv_id=bkg.bkg_vehicle_type_id AND scv.scv_active=1 ";
			$groupBy .= ", scv.scv_vct_id";
		}
		elseif ($type == 5)
		{
			$select	 .= ", scv.scv_label cabType, tz.zon_name toZone ";
			$sqlJoin .= "
						INNER JOIN zone_cities tzc ON tzc.zct_cty_id=bkg.bkg_to_city_id AND tzc.zct_active=1
						INNER JOIN zones tz ON tz.zon_id=tzc.zct_zon_id AND tz.zon_active=1 ";
			$groupBy .= ", scv.scv_vct_id , tz.zon_id";
		}
		if ($bkgTypes != '')
		{
			$strBkgTypes = implode(',', $bkgTypes);
			$where		 .= " AND bkg_booking_type IN ($strBkgTypes) ";
		}
		if ($region != '' || $state != '')
		{
			$sqlJoin .= "INNER JOIN cities c1 ON c1.cty_id=bkg.bkg_from_city_id AND c1.cty_active=1
						INNER JOIN states s1 ON s1.stt_id=c1.cty_state_id AND s1.stt_active = '1' ";

			if ($region != '')
			{
				$strRegion	 = implode(',', $region);
				$sqlJoin	 .= " AND s1.stt_zone IN ($strRegion) ";
			}
			if ($state != '')
			{
				$strState	 = implode(',', $state);
				$sqlJoin	 .= " AND s1.stt_id IN ($strState) ";
			}
		}
		if ($sourcezone > 0)
		{
//			$strSourcezone	 = implode(',', $sourcezone);
			$where .= " AND z.zon_id IN ($sourcezone) ";
		}
		if ($assignCountDrop > 0)
		{
			if ($assignCountDrop == 1)
			{
				$where2 .= " AND assignedCnt > $assignCount";
			}
			else
			{
				$where2 .= " AND assignedCnt < $assignCount";
			}
		}
		if ($lossCountDrop > 0)
		{
			if ($lossCountDrop == 1)
			{
				$where2 .= " AND lossCount > $lossCount";
			}
			else
			{
				$where2 .= " AND lossCount < $lossCount";
			}
		}
		if ($netMarginDrop > 0)
		{
			if ($netMarginDrop == 1)
			{
				$where2 .= " AND netMargin > $netMargin";
			}
			else
			{
				$where2 .= " AND netMargin < $netMargin";
			}
		}
		if ($assignCountDrop > 0 || $lossCountDrop > 0 || $netMarginDrop > 0)
		{
			$having = " HAVING (1 " . $where2 . ")";
		}

		if ($excludeAT)
		{
			$where .= " AND bkg_booking_type NOT IN (4,12) ";
		}

		$includeCondition = [];

		if ($b2cbookings)
		{
			$includeCondition[] = "(bkg_agent_id IS NULL OR bkg_agent_id = 1249 OR bkg_agent_id = '')";
		}
		if ($mmtbookings == 1)
		{
			$includeCondition[] = "(bkg_agent_id IN (450,18190))";
		}
		if ($nonAPIPartner == 1)
		{
			$includeCondition[] = "(btr.bkg_platform NOT IN (7,9,10) AND bkg_agent_id IS NOT NULL)";
		}

		if (count($includeCondition) > 0)
		{
			$where .= " AND (" . implode(" OR ", $includeCondition) . ")";
		}


		$sql			 = $select . $sqlJoin . $where . $groupBy . $having;
		//print_r($sql);exit;
		$command		 = DBUtil::command($sql, DBUtil::SDB3());
		$command->params = $params;
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ({$command->getText()} ) temp", DBUtil::SDB(), $command->params);
		$dataProvider	 = new CSqlDataProvider($command, [
			"params"		 => $params,
			"totalItemCount" => $count,
			"params"		 => $command->params,
			'db'			 => DBUtil ::SDB3(),
			'pagination'	 => array('pageSize' => 100),
			'sort'			 => [
				'attributes'	 => ['cnt', 'newCnt', 'assignedCnt', 'lossCount', 'newLossCount', 'assignLossCount', 'netMargin', 'netLossMargin', 'netProfitMargin'],
				'defaultOrder'	 => 'cnt DESC'
			],
		]);

		return $dataProvider;
	}

	/**
	 * function to check gozo need to pay that vendor  for that particular booking
	 * @param type $bkg_id
	 * @param type $accptVendorAmount
	 * @return type
	 */
	public static function getVendorReceivable($bcbId, $accptVendorAmount)
	{

		$dueAmount			 = self::calculateDueAmount($bcbId);
		$vendorReceivable	 = $accptVendorAmount - $dueAmount;
		return $vendorReceivable;
	}

	/**
	 * calculate total due amount against trip id
	 * @param type $bcbId
	 * @return type
	 */
	public static function calculateDueAmount($bcbId)
	{
		$sql	 = "SELECT SUM(bkg_total_amount - bkg_net_advance_amount) as customerPayableAmount
				FROM booking bkg
				INNER JOIN booking_invoice biv ON biv.biv_bkg_id = bkg.bkg_id
				INNER JOIN booking_cab ON bcb_id=bkg_bcb_id
				WHERE bcb_id=:bcbId AND bkg.bkg_active = 1
				GROUP  BY bcb_id";
		$params	 = ['bcbId' => $bcbId];
		$rows	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);

		return $rows;
	}

	public function updateVendorAmount($bkgModel, $vndAmount, $vndReason)
	{
		$model		 = BookingInvoice::model()->getByBookingID($bkgModel->bkg_id);
		$oldAmount	 = $model->bkg_vendor_amount;
		$modelCab	 = BookingCab::model()->findByPk($bkgModel->bkg_bcb_id);
		$transaction = DBUtil::beginTransaction();
		try
		{
			if ($vndAmount > 0)
			{
				$model->bkg_vendor_amount = $vndAmount;
				if ($model->save())
				{
					$modelCab->bcb_vendor_amount = $vndAmount;
					if ($modelCab->save())
					{
						BookingInvoice::updateGozoAmount($bkgModel->bkg_bcb_id);
						$desc	 = "Trip vendor amount modified: old vendor amount $oldAmount, new vendor amount: $vndAmount (Reason: " . $vndReason . ")";
						$eventId = BookingLog::VENDOR_AMOUNT_RESET;
						BookingLog::model()->createLog($bkgModel->bkg_id, $desc, UserInfo:: getInstance(), $eventId, false);
					}
					DBUtil::commitTransaction($transaction);
				}
				else
				{
					return false;
				}
			}
			else
			{
				$return['message'] = $this->getError($bkgId);
				return false;
			}
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$this->addError($bkgId, $e->getMessage());
			$return['error'] = $this->getErrors();
			return false;
		}
		return true;
	}

	public function getAddonDetailsByInvoiceModel()
	{
		$addonsJson		 = $this->bkg_addon_details;
		$arrAddonDetails = [];
		if ($addonsJson != null)
		{

			$addonsArr	 = json_decode($addonsJson, true);
			$key		 = array_search(1, array_column($addonsArr, 'adn_type'));
			if ($key >= 0)
			{
				$acpData			 = DBUtil::queryRow("SELECT acp_cr_to FROM `addon_cancellation_policy` WHERE `acp_id` = :id", DBUtil::SDB(), ['id' => $addonsArr[$key]['adn_id']]);
				$CPdetails			 = CancellationPolicyDetails::model()->findByPk($acpData['acp_cr_to']);
				$arrAddonDetails[0]	 = [
					'id'				 => $addonsArr[$key]['adn_id'],
					'typeId'			 => 1,
					'cost'				 => $addonsArr[$key]['adn_value'],
					'label'				 => trim($CPdetails->cnp_label),
					'longDescription'	 => trim($CPdetails->cnp_desc),
					'shortDescription'	 => trim($CPdetails->cnp_code),
					'type'				 => 'CancellationType'
				];
			}

			$key1 = array_search(2, array_column($addonsArr, 'adn_type'));
			if ($key1 >= 0)
			{
				$acmData			 = DBUtil::queryRow("SELECT acm_svc_id_to FROM `addon_cab_models` WHERE `acm_id` = :id", DBUtil::MDB(), ['id' => $addonsArr[$key1]['adn_id']]);
				$svcModel			 = SvcClassVhcCat::model()->findByPk($acmData['acm_svc_id_to']);
				$label				 = $svcModel->scv_label;
				$labelArr			 = explode("(", $label);
				$label				 = $labelArr[0];
				$arrAddonDetails[1]	 = [
					'id'				 => $addonsArr[$key1]['adn_id'],
					'typeId'			 => 2,
					'cost'				 => $addonsArr[$key1]['adn_value'],
					'label'				 => $label,
					'longDescription'	 => $label,
					'type'				 => "CabType"
				];
			}
		}
		return $arrAddonDetails;
	}

	public function getExtraChargeRule()
	{

//		$arrRule = [];
//
//$type="Km";
//bkg_rate_per_km_extra  bkg_rate_per_km_extra
//
//$type="Min";
//bkg_extra_per_min_charge   bkg_extra_per_min_charge
//
//
//		return $arrAddonDetails;
	}

	/**
	 *
	 * @param type $filter
	 */
	public static function calculateExtramount($filter)
	{

		$bkgId				 = $filter->bkgId;
		$model				 = Booking::model()->findByPk($bkgId);
		$bkgStatus			 = $model->bkg_status;
		$bookingInvoice		 = $model->bkgInvoice;
		$totalExtraCharge	 = 0;
		$totalExtraKmcharge	 = 0;
		$totalExtraMinCharge = 0;
		$extraKm             = 0;
        $timegap             = 0;
		$isExtraKmApplied	 = BookingRoute::checkExtraKmApplied($bkgId, $filter->coordinates->lat, $filter->coordinates->lng);
		if ($isExtraKmApplied == 1)
		{
			$eventTypeId	 = BookingTrack::TRIP_START;
			#$trackmodel		 = BookingTrack::checkSOSTrigger($bkgId, $eventTypeId);
			$trackmodel		 = $model->bkgTrack;
			$startOdometre	 = $trackmodel->bkg_start_odometer;
			$endOdometre	 = $filter->endOdometre;
			$totalKm		 = $endOdometre - $startOdometre;
			if ($totalKm > $model->bkg_trip_distance)
			{
				$extraKm			 = $totalKm - $model->bkg_trip_distance;
				$extraPerKmCharge	 = $bookingInvoice->bkg_rate_per_km_extra;
				$totalExtraKmcharge	 = $extraKm * $extraPerKmCharge;
			}
		}
		if ($model->bkg_booking_type == 9 || $model->bkg_booking_type == 10 || $model->bkg_booking_type == 11)
		{
			$bkg_pickup_date	 = $model->bkg_pickup_date;
			$diff				 = (DBUtil::getTimeDiff($bkg_pickup_date, DBUtil::getCurrentTime()));
			$endTimeDifference	 = $diff - $model->bkg_trip_duration;
			$endTimeDifference	 = ($endTimeDifference<1 ? 0 :$endTimeDifference);
			$scvId				 = $model->bkgSvcClassVhcCat->scv_scc_id;
			$time_cap			 = json_decode(Config::get('dayRental.timeSlot'));
			$defaultClass		 = Yii::app()->params['defaultClass'];
			$svcClassId			 = ($defaultClass == 1 ? 0 : $scvId);
			$total				 = $time_cap->$svcClassId * $model->bkgInvoice->bkg_extra_per_min_charge;
			$timegap			 = ceil($endTimeDifference / $time_cap->$svcClassId);
			$totalExtraMinCharge = $timegap * $total;
		}
		$bookingInvoice->bkg_extra_km                = $extraKm;	
		$bookingInvoice->bkg_extra_min               = $endTimeDifference;	
		$bookingInvoice->bkg_extra_total_min_charge	 = $totalExtraMinCharge;
		$bookingInvoice->bkg_extra_km_charge		 = $totalExtraKmcharge;
		$result										 = array("invoicedata" => $bookingInvoice, "status" => $bkgStatus);
		return $result;
	}

	/**
	 *
	 * @param type $model booking model
	 * @return int
	 * @throws Exception
	 */
	public static function calculateVendorCompensation($model)
	{
		$ruleVC				 = Config::get('booking.vendorCompensation.settings');
		$obj				 = json_decode($ruleVC);
		$ruleVCobj			 = (in_array($model->bkg_booking_type, [4, 12, 9, 10, 11, 15])) ? ($obj[1]) : ($obj[0]);
		$remarks			 = '';
		$isVndCompensation	 = 0;

		$bkgId		 = $model->bkg_id;
		$bookingAmt	 = $model->bkgInvoice->bkg_total_amount;
		$vndAmt		 = $model->bkgBcb->bcb_vendor_amount;
		$matchTrip	 = $model->bkgBcb->bcb_trip_type;

		if ($matchTrip == 1)
		{
			throw new Exception(serialize(['msg' => 'No vendor compensation for matched trip, booking id: ' . $bkgId, 'status' => true]), ReturnSet::ERROR_VALIDATION);
		}

		// Compensation Amount
		$compensationBaseAmt = min($bookingAmt, $vndAmt);
		$compensationAmt	 = round($compensationBaseAmt * $ruleVCobj->basePercentageVC);

		if ($compensationAmt < $ruleVCobj->minVC)
		{
			$compensationAmt = $ruleVCobj->minVC;
		}
		else if ($compensationAmt > $ruleVCobj->maxVC)
		{
			$compensationAmt = $ruleVCobj->maxVC;
		}

		// Customer Cancellation Charges
		$getCustomerCancellationAmt = AccountTransactions::getCancellationCharge($bkgId);
		if ($getCustomerCancellationAmt <= 0)
		{
			throw new Exception(serialize(['msg' => 'No vendor compensation as no customer cancellation charges, booking id: ' . $bkgId, 'status' => true]), ReturnSet::ERROR_VALIDATION);
		}

		$drvEventId		 = $model->bkgTrack->btk_last_event;
		$tripTimeDiff	 = Filter::getTimeDiff($model->bkg_pickup_date, $model->bkgTrail->btr_cancel_date);
		$totalAdvance	 = $model->bkgInvoice->bkg_advance_amount;

		if ($totalAdvance > 0)
		{
			if ($tripTimeDiff <= 60)
			{
				$remarks			 = '1 hr before pickup, no app use required';
				$isVndCompensation	 = 1;
			}
			else if ($drvEventId != null && $tripTimeDiff <= 120)
			{
				$remarks			 = '2 hr before pickup,driver app used';
				$isVndCompensation	 = 1;
			}
		}

		//remarks generated on runtime
		if ($model->bkg_cancel_id == 21)
		{
			$remarks = 'Driver reported arrived on the pick up point, customer no show';
		}

		$arr = ["isVndCompensation" => $isVndCompensation, "compensationAmt" => $compensationAmt, "remarks" => $remarks];
		return $arr;
	}

	public static function getCompensationData($params, $command = DBUtil::ReturnType_Provider)
	{
		$fromPickupDate			 = $params['fromPickupDate'];
		$toPickupDate			 = $params['toPickupDate'];
		$vndId					 = $params['vndId'];
		$bkgId					 = $params['bkgId'];
		$status					 = $params['status'];
		$fromcompensationDate	 = $params['fromCompensationDate'];
		$tocompensationDate		 = $params['tocompensationDate'];
		$where					 = '';
		if ($fromPickupDate != '' && $toPickupDate != '')
		{
			$createDate = " AND (bkg.bkg_pickup_date BETWEEN '$fromPickupDate 00:00:00' AND '$toPickupDate 23:59:59')";
		}

		if ($fromcompensationDate != '' && $tocompensationDate != '')
		{
			#$compensationDate = " AND (biv.bkg_vnd_compensation_date BETWEEN '$fromcompensationDate 00:00:00' AND '$tocompensationDate 23:59:59')";
			$compensationDate = " AND (act.act_date BETWEEN '$fromcompensationDate 00:00:00' AND '$tocompensationDate 23:59:59')";
		}

		if ($bkgId != '')
		{
			$where .= " AND (bkg.bkg_id = '$bkgId' OR bkg.bkg_booking_id = '$bkgId')";
		}

		if ($vndId != '')
		{
			$where .= " AND vnd.vnd_id =" . $vndId;
		}
		if ($status != '')
		{
			$where .= " AND bkg.bkg_status IN(" . $status . ")";
		}

//		$dataSelect = "SELECT bkg.bkg_id AS bkgId, bkg.bkg_booking_id AS bookingId, bkg.bkg_pickup_date, btr.btr_cancel_date,
//						bcb.bcb_vendor_amount, vnd.vnd_name, biv.bkg_vnd_compensation AS vndCompensation, bkg.bkg_status,
//						biv.bkg_vnd_compensation_date AS vndCompensationDate, (IFNULL(biv.bkg_cancel_charge,0) + IFNULL(biv.bkg_cancel_gst, 0)) as cancelcharge";
//
//		$sqlBody = " FROM booking bkg
//					INNER JOIN booking_invoice biv ON biv.biv_bkg_id = bkg.bkg_id
//                    INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id
//					INNER JOIN booking_trail btr ON btr.btr_bkg_id = bkg.bkg_id AND btr.btr_active = 1
//                    LEFT JOIN vendors vnd ON vnd.vnd_id = bcb.bcb_vendor_id
//					INNER JOIN account_trans_details atd ON bkg.bkg_id = atd.adt_trans_ref_id AND atd.adt_ledger_id IN(25,27)
//					INNER JOIN account_transactions act ON atd.adt_trans_id = act.act_id
//					INNER JOIN account_trans_details atd1 ON act.act_id = atd1.adt_trans_id
//                    WHERE bkg.bkg_status IN(6,7,9) AND atd1.adt_active = 1 AND atd.adt_active = 1 AND act.act_active = 1
//					AND atd1.adt_status = 1 AND atd.adt_status = 1 AND atd1.adt_ledger_id IN(13,14)
//					$createDate $where $compensationDate  GROUP BY bkg.bkg_id";
//
//		$countSelect = "SELECT bkg.bkg_id as bkgId";


		$dataSelect = "SELECT bkg.bkg_id AS bkgId, bkg.bkg_booking_id AS bookingId, bkg.bkg_pickup_date, btr.btr_cancel_date,
						bcb.bcb_vendor_amount, vnd.vnd_name, biv.bkg_vnd_compensation AS vndCompensation, bkg.bkg_status,
						biv.bkg_vnd_compensation_date AS vndCompensationDate, (IFNULL(biv.bkg_cancel_charge,0) + IFNULL(biv.bkg_cancel_gst, 0)) as cancelcharge ";

		$sqlBody = "FROM account_transactions act
					INNER JOIN account_trans_details atd ON act.act_id = atd.adt_trans_id AND atd.adt_ledger_id IN (27)
						AND atd.adt_active = 1 AND act.act_active = 1 AND atd.adt_status = 1 {$compensationDate}
					INNER JOIN account_trans_details atd1 ON act.act_id = atd1.adt_trans_id AND atd1.adt_ledger_id IN (14)
						AND atd1.adt_active = 1 AND atd1.adt_status = 1
					INNER JOIN vendors vnd ON vnd.vnd_id = atd1.adt_trans_ref_id
					INNER JOIN booking_cab bcb ON bcb.bcb_vendor_id = vnd.vnd_id AND bcb.bcb_bkg_id1 IN (atd.adt_trans_ref_id)
					INNER JOIN booking bkg ON bkg.bkg_id = atd.adt_trans_ref_id
					INNER JOIN booking_invoice biv ON biv.biv_bkg_id = bkg.bkg_id
					INNER JOIN booking_trail btr ON btr.btr_bkg_id = bkg.bkg_id AND btr.btr_active = 1
					WHERE bkg.bkg_status IN (6,7,9) {$createDate} {$where} {$compensationDate} ";

		$countSelect = "SELECT bkg.bkg_id as bkgId ";

		$sqlData	 = "SELECT * FROM ( " . $dataSelect . $sqlBody . " ) a GROUP BY bkgId";
		$sqlCount	 = "SELECT * FROM ( " . $countSelect . $sqlBody . " ) a GROUP BY bkgId";

		if ($command == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB2());
			$dataprovider	 = new CSqlDataProvider($sqlData, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB2(),
				'sort'			 => ['attributes'	 => ['bkg_pickup_date', 'vndCompensationDate'],
					'defaultOrder'	 => "bkgId DESC"],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		else
		{
			$orderBY = " ORDER BY bkgId DESC ";
			return DBUtil::query($sqlData . $orderBY, DBUtil::SDB2());
		}
	}

	/**
	 *
	 * @param Booking $model
	 * @param integer $type
	 * @return \CSqlDataProvider
	 */
	public static function lossAssignmentBkgs($model, $type = DBUtil::ReturnType_Provider)
	{
		$sqlBookingType	 = "";
		$sqlPickupDate	 = "";
		if ($model->bkg_booking_type != null)
		{
			DBUtil::getINStatement($model->bkg_booking_type, $bindString1, $params);
			$sqlBookingType = " AND booking.bkg_booking_type IN ($bindString1)";
		}
		if ($model->bkg_pickup_date1 != '' && $model->bkg_pickup_date2 != '')
		{
			$sqlPickupDate			 = " AND booking.bkg_pickup_date BETWEEN :pickupDate1 AND :pickupDate2";
			$params["pickupDate1"]	 = $model->bkg_pickup_date1;
			$params["pickupDate2"]	 = $model->bkg_pickup_date2;
		}
		$sql = "SELECT
				bkg_id AS BookingId,
				bkg_booking_type,
				booking_pref.bpr_zone_type AS ZoneType,
				z1.zon_name AS SourceZone,
				z2.zon_name AS DestinationZone,
				ROUND((TIMESTAMPDIFF(MINUTE, bkg_create_date ,bkg_pickup_date)/60),2) AS C2P_bucket,
				IF(blg1.blg_created IS NULL,0,ROUND((TIMESTAMPDIFF(MINUTE, blg1.blg_created, bkg_pickup_date)/60),2)) AS First_A2P_bucket,
				IF(blg.blg_created IS NULL,0,ROUND((TIMESTAMPDIFF(MINUTE, blg.blg_created, bkg_pickup_date)/60),2)) AS Last_A2P_bucket,
				IF(bkg_booking_type IS NOT NULL,IF(bkg_booking_type IN (4,9,10,11,12),'Local','Out Station'),0) AS IsLocal,
				(bkg_gozo_amount- IFNULL(bkg_credits_used,0)) AS LossAmount
				FROM `booking`
				INNER JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking.bkg_active = 1 AND booking.bkg_status IN (3,5,6,7)
				INNER JOIN `booking_pref` ON booking_pref.bpr_bkg_id=booking.bkg_id
				INNER JOIN `booking_invoice` ON booking_invoice.biv_bkg_id=booking.bkg_id
				INNER JOIN `booking_trail` ON booking_trail.btr_bkg_id=booking.bkg_id
				INNER JOIN `cities` c1 ON c1.cty_id=booking.bkg_from_city_id
				INNER JOIN `cities` c2 ON c2.cty_id=booking.bkg_to_city_id
				INNER JOIN `zone_cities` zc1 ON zc1.zct_cty_id=c1.cty_id
				INNER JOIN `zone_cities` zc2 ON zc2.zct_cty_id=c2.cty_id
				INNER JOIN `zones` z1 ON z1.zon_id=zc1.zct_zon_id
				INNER JOIN `zones` z2 ON z2.zon_id=zc2.zct_zon_id
				JOIN `booking_log` AS blg1 ON bkg_id = blg1.blg_booking_id AND blg1.blg_id = (SELECT MIN(blg_id) FROM booking_log WHERE blg_event_id = 7 AND blg_booking_id = bkg_id AND blg_active= 1 AND blg_vendor_assigned_id > 0 ORDER BY blg_id ASC LIMIT 0, 1)
				JOIN `booking_log` AS blg ON bkg_id = blg.blg_booking_id AND blg.blg_id = (SELECT MAX(blg_id) FROM booking_log WHERE blg_event_id = 7 AND blg_booking_id = bkg_id AND blg_active =1 AND blg_vendor_assigned_id > 0 ORDER BY blg_id DESC LIMIT 0, 1)
				WHERE booking_trail.bkg_assigned_at BETWEEN :createDate1 AND :createDate2 $sqlBookingType $sqlPickupDate
				AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<=0
				GROUP BY booking_cab.bcb_id";

		$sqlCount				 = "SELECT COUNT(1) as cnt FROM (
							SELECT
							bkg_id AS BookingId
							FROM `booking`
							INNER JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking.bkg_active = 1 AND booking.bkg_status IN (3,5,6,7)
							INNER JOIN `booking_pref` ON booking_pref.bpr_bkg_id=booking.bkg_id
							INNER JOIN `booking_invoice` ON booking_invoice.biv_bkg_id=booking.bkg_id
							INNER JOIN `booking_trail` ON booking_trail.btr_bkg_id=booking.bkg_id
							INNER JOIN `cities` c1 ON c1.cty_id=booking.bkg_from_city_id
							INNER JOIN `cities` c2 ON c2.cty_id=booking.bkg_to_city_id
							INNER JOIN `zone_cities` zc1 ON zc1.zct_cty_id=c1.cty_id
							INNER JOIN `zone_cities` zc2 ON zc2.zct_cty_id=c2.cty_id
							INNER JOIN `zones` z1 ON z1.zon_id=zc1.zct_zon_id
							INNER JOIN `zones` z2 ON z2.zon_id=zc2.zct_zon_id
							JOIN `booking_log` AS blg1 ON bkg_id = blg1.blg_booking_id AND blg1.blg_id = (SELECT MIN(blg_id) FROM booking_log WHERE blg_event_id = 7 AND blg_booking_id = bkg_id AND blg_active= 1 AND blg_vendor_assigned_id > 0 ORDER BY blg_id ASC LIMIT 0, 1)
							JOIN `booking_log` AS blg ON bkg_id = blg.blg_booking_id AND blg.blg_id = (SELECT MAX(blg_id) FROM booking_log WHERE blg_event_id = 7 AND blg_booking_id = bkg_id AND blg_active =1 AND blg_vendor_assigned_id > 0 ORDER BY blg_id DESC LIMIT 0, 1)
							WHERE 1
							AND booking_trail.bkg_assigned_at BETWEEN :createDate1 AND :createDate2 $sqlBookingType $sqlPickupDate
							AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<=0
							GROUP BY booking_cab.bcb_id
						) a";
		$params["createDate1"]	 = $model->bkg_create_date1;
		$params["createDate2"]	 = $model->bkg_create_date2;
		if ($type == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar($sqlCount, DBUtil::SDB3(), $params);
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB3(),
				"params"		 => $params,
				'sort'			 => ['attributes' => ['ZoneType', 'SourceZone', 'DestinationZone', 'LossAmount', 'IsLocal', 'bkg_booking_type', 'BookingId'], 'defaultOrder' => ''],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3(), $params);
		}
	}

	/**
	 * Report from BI
	 * @param Booking $model
	 * @param integer $type
	 * @return \CSqlDataProvider
	 */
	public static function getFulfilmentProfit($model, $type = DBUtil::ReturnType_Provider)
	{
		$sql					 = "SELECT
					c1.cty_display_name AS fromCityName,
					c2.cty_display_name AS toCityName,
					states.stt_name AS FromStateName,
					COUNT(bkg_id) AS cntInquired,
					COUNT(
						IF(
							bkg_status IN(2, 3, 5, 6, 7, 9),
							1,
							NULL
						)
					) AS cntCreated,
					COUNT(IF(bkg_status IN(6, 7),
					1,
					NULL)) AS cntCompleted,
					(
						COUNT(
							IF(
								bkg_status IN(2, 3, 5, 6, 7, 9),
								1,
								NULL
							)
						) / COUNT(bkg_id)
					) * 100 AS pct_conversion,
					(
						COUNT(IF(bkg_status IN(6, 7),
						1,
						NULL)) / COUNT(
							IF(
								bkg_status IN(2, 3, 5, 6, 7, 9),
								1,
								NULL
							)
						)
					) * 100 AS pct_fulfilment,
					SUM(
						IF(
							bkg_status IN(6, 7),
							bkg_total_amount,
							0
						)
					) AS totalAmount,
					SUM(
						IF(
							bkg_status IN(6, 7),
							(
								bkg_gozo_amount - IFNULL(bkg_credits_used, 0)
							),
							0
						)
					) AS totalGozoAmount,
					(
						SUM(
							IF(
								bkg_status IN(6, 7),
								(
									bkg_gozo_amount - IFNULL(bkg_credits_used, 0)
								),
								0
							)
						) / SUM(
							IF(
								bkg_status IN(6, 7),
								bkg_total_amount,
								0
							)
						)
					) * 100 AS pct_profit,
					MIN(bkg_create_date) AS firstBookingCreateDate,
					MAX(bkg_create_date) AS lastBookingCreateDate
				FROM `booking`
				INNER JOIN `booking_invoice` ON booking_invoice.biv_bkg_id = booking.bkg_id
				INNER JOIN `cities` c1 ON c1.cty_id = booking.bkg_from_city_id
				INNER JOIN `states` ON states.stt_id = c1.cty_state_id
				INNER JOIN `cities` c2 ON c2.cty_id = booking.bkg_to_city_id
				WHERE booking.bkg_create_date BETWEEN :createDate1 AND :createDate2
				GROUP BY
					booking.bkg_from_city_id,
					booking.bkg_to_city_id
				HAVING
					cntInquired > 5";
		$params["createDate1"]	 = $model->bkg_create_date1;
		$params["createDate2"]	 = $model->bkg_create_date2;
		if ($type == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql ) temp", DBUtil::SDB3(), $params);
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'params'		 => $params,
				'db'			 => DBUtil::SDB3(),
				'sort'			 => ['attributes' => ['cntInquired', 'cntCreated', 'cntCompleted', 'totalAmount', 'totalGozoAmount', 'pct_conversion', 'pct_fulfilment', 'pct_profit', 'firstBookingCreateDate', 'lastBookingCreateDate'], 'defaultOrder' => ''],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3(), $params);
		}
	}

	/**
	 * Report from BI
	 * @param Booking $model
	 * @param integer $command
	 * @return \CSqlDataProvider
	 */
	public static function getTotalAmountListByPickupDate($model, $command = DBUtil::ReturnType_Provider)
	{
		$sql		 = "SELECT
				date(booking.bkg_pickup_date) as pickupDate,
				COUNT(1) AS totalCnt,
				COUNT(IF(bkg_total_amount<1000,1,NULL)) AS \"BookingAmount<1000\",
				COUNT(IF(bkg_total_amount>=1000 AND bkg_total_amount<2000 ,1,NULL)) AS \"1000<=BookingAmount>2000\",
				COUNT(IF(bkg_total_amount>=2000 AND bkg_total_amount<3000 ,1,NULL)) AS \"2000<=BookingAmount>3000\",
				COUNT(IF(bkg_total_amount>=3000 AND bkg_total_amount<4000 ,1,NULL)) AS \"3000<=BookingAmount>4000\",
				COUNT(IF(bkg_total_amount>=4000 AND bkg_total_amount<5000 ,1,NULL)) AS \"4000<=BookingAmount>5000\",
				COUNT(IF(bkg_total_amount>=5000 ,1,NULL)) AS \"BookingAmount>=5000\"
				FROM `booking`
				INNER JOIN `booking_cab` on booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1
				INNER JOIN `booking_invoice` on booking_invoice.biv_bkg_id = booking.bkg_id
				WHERE 1 AND bkg_reconfirm_flag=1 AND bkg_status IN (2,3,5,6,7)
				AND booking.bkg_pickup_date BETWEEN '$model->bkg_pickup_date1' AND '$model->bkg_pickup_date2'
				GROUP BY pickupDate
				ORDER BY pickupDate DESC";
		$sqlCount	 = "SELECT SUM(IF(pickupDate!='0000-00-00',1,0)) as totalCount FROM
					(
						SELECT
						date(booking.bkg_pickup_date) as pickupDate
						FROM `booking`
						INNER JOIN `booking_cab` on booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1
						INNER JOIN `booking_invoice` on booking_invoice.biv_bkg_id = booking.bkg_id
						WHERE 1 AND bkg_reconfirm_flag=1 AND bkg_status IN (2,3,5,6,7)
						AND booking.bkg_pickup_date BETWEEN '$model->bkg_pickup_date1' AND '$model->bkg_pickup_date2'
						GROUP BY pickupDate
					) a";

		if ($command == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar($sqlCount, DBUtil::SDB3());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB3(),
				'sort'			 => ['attributes' => ['totalCnt', 'pickupDate'], 'defaultOrder' => 'pickupDate DESC'],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3());
		}
	}

	/**
	 * Report from BI
	 * @param Booking $model
	 * @param integer $command
	 * @return \CSqlDataProvider
	 */
	public static function getTotalAmountListByCreateDate($model, $command = DBUtil::ReturnType_Provider)
	{
		$sql		 = "SELECT date(booking.bkg_create_date) as createDate,
				COUNT(1) AS totalCnt,
				COUNT(IF(bkg_total_amount<1000,1,NULL)) AS \"BookingAmount<1000\",
				COUNT(IF(bkg_total_amount>=1000 AND bkg_total_amount<2000 ,1,NULL)) AS \"1000<=BookingAmount>2000\",
				COUNT(IF(bkg_total_amount>=2000 AND bkg_total_amount<3000 ,1,NULL)) AS \"2000<=BookingAmount>3000\",
				COUNT(IF(bkg_total_amount>=3000 AND bkg_total_amount<4000 ,1,NULL)) AS \"3000<=BookingAmount>4000\",
				COUNT(IF(bkg_total_amount>=4000 AND bkg_total_amount<5000 ,1,NULL)) AS \"4000<=BookingAmount>5000\",
				COUNT(IF(bkg_total_amount>=5000 ,1,NULL)) AS \"BookingAmount>=5000\"
				FROM `booking`
				INNER JOIN `booking_cab` on booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1
				INNER JOIN `booking_invoice` on booking_invoice.biv_bkg_id = booking.bkg_id
				WHERE 1 AND bkg_reconfirm_flag=1 AND bkg_status IN (2,3,5,6,7)
				AND booking.bkg_create_date BETWEEN '$model->bkg_create_date1' AND '$model->bkg_create_date2'
				GROUP BY createDate
				ORDER BY createDate DESC";
		$sqlCount	 = "SELECT SUM(IF(createDate!='0000-00-00',1,0)) as totalCount FROM
					(
						SELECT
						date(booking.bkg_create_date) as createDate
						FROM `booking`
						INNER JOIN `booking_cab` on booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1
						INNER JOIN `booking_invoice` on booking_invoice.biv_bkg_id = booking.bkg_id
						WHERE 1 AND bkg_reconfirm_flag=1 AND bkg_status IN (2,3,5,6,7)
						AND booking.bkg_create_date BETWEEN '$model->bkg_create_date1' AND '$model->bkg_create_date2'
						GROUP BY createDate
					) a";
		if ($command == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar($sqlCount, DBUtil::SDB3());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB3(),
				'sort'			 => ['attributes' => ['totalCnt', 'createDate'], 'defaultOrder' => 'createDate DESC'],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3());
		}
	}

	/**
	 * Report from BI
	 * @param Booking $model
	 * @param integer $command
	 * @return \CSqlDataProvider
	 */
	public static function getSalesAssistedByTier($model, $command = DBUtil::ReturnType_Provider)
	{
		$sql = "SELECT
					DATE(booking.bkg_create_date) AS `bkg_create_date`,
					service_class.scc_label,
					COUNT(*) AS `count`,
					SUM(booking_invoice.bkg_gozo_amount) AS `total_sum`
				FROM `booking`
				INNER JOIN `booking_invoice`  ON booking.bkg_id = booking_invoice.biv_bkg_id
				INNER JOIN `svc_class_vhc_cat` ON booking.bkg_vehicle_type_id =  svc_class_vhc_cat.scv_id
				INNER JOIN `service_class` ON svc_class_vhc_cat.scv_scc_id = service_class.scc_id
				INNER JOIN `admin_profiles` ON booking.bkg_admin_id=admin_profiles.adp_adm_id
				WHERE
					(
						(
						   booking.bkg_status IN (2,3,5,6,7)
						)
						AND booking.bkg_pickup_date BETWEEN '$model->bkg_pickup_date1' AND '$model->bkg_pickup_date2'
						AND admin_profiles.adp_team_leader_id=467
					)
				GROUP BY
					DATE(booking.bkg_create_date),
					 service_class.scc_label";

		$sqlCount = "SELECT COUNT(1) as countTotal FROM (
							SELECT
								DATE(booking.bkg_create_date) AS `bkg_create_date`,
								service_class.scc_label,
								COUNT(*) AS `count`,
								SUM(booking_invoice.bkg_gozo_amount) AS `total_sum`
							FROM `booking`
							INNER JOIN `booking_invoice`  ON booking.bkg_id = booking_invoice.biv_bkg_id
							INNER JOIN `svc_class_vhc_cat` ON booking.bkg_vehicle_type_id =  svc_class_vhc_cat.scv_id
							INNER JOIN `service_class` ON svc_class_vhc_cat.scv_scc_id = service_class.scc_id
							INNER JOIN `admin_profiles` ON booking.bkg_admin_id=admin_profiles.adp_adm_id
							WHERE
								(
									(
									   booking.bkg_status IN (2,3,5,6,7)
									)
									AND booking.bkg_pickup_date BETWEEN '$model->bkg_pickup_date1' AND '$model->bkg_pickup_date2'
									AND admin_profiles.adp_team_leader_id=467
								)
							GROUP BY
								DATE(booking.bkg_create_date),
								 service_class.scc_label
							ORDER BY
								DATE(booking.bkg_create_date) DESC, service_class.scc_label ASC
								) a";

		if ($command == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar($sqlCount, DBUtil::SDB3());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB3(),
				'sort'			 => ['attributes' => ['bkg_create_date', 'count', 'total_sum'], 'defaultOrder' => ''],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3());
		}
	}

	public static function calculateLockAmount($bkgId, $bidAmount, $vendorId)
	{
		$minPaymentRequired	 = 0;
		$model				 = BookingInvoice::model()->getByBookingID($bkgId);
		$vendorModel		 = Vendors::model()->findByPk($vendorId);
		$vnpCreditFreeze	 = $vendorModel->vendorPrefs->vnp_credit_limit_freeze;
		$vendorDue			 = 0;
		$totalAmount		 = $model->bkg_total_amount;
		$advanceAmount		 = $model->bkg_advance_amount;
		$vendorDue			 = $totalAmount - $advanceAmount - $bidAmount;
		if ($vendorDue < 0 && $vnpCreditFreeze == 0)
		{
			goto end;
		}

		$processingFees		 = Config::get("vendor.account.processingFee");
		$outstandingBalance	 = VendorStats::calculateOutstandingBalance($vendorId);

		$minReqOutstandingDue = $outstandingBalance;
		if ($outstandingBalance < 0)
		{
			$minReqOutstandingDue = min(round($outstandingBalance * 1), -200); //may be modify
		}

		$return = $vendorDue - $minReqOutstandingDue;

		$minPaymentRequired = round($return + $return * $processingFees);

		end:
		return $minPaymentRequired;
	}

	public static function checkCODBkg($bkgId, $vndCodFreeze)
	{
		$status			 = false;
		$invoiceModel	 = BookingInvoice::model()->getByBookingID($bkgId);
		$totalAdvance	 = $invoiceModel->bkg_net_advance_amount;

		if ($vndCodFreeze == 0 || $totalAdvance > 0)
		{
			$status = true;
		}
		return $status;
	}

	/**
	 *
	 * @param type $jsonObj
	 * @return type
	 */
	public function updateTRFBookingAmount($jsonObj, $agentId)
	{
		$bkgId		 = self::getIdByRefCode($jsonObj->order_reference_number, $agentId);
		$model		 = Booking::model()->findByPk($bkgId);
		$oldData	 = $model->getDetailsbyId($model->bkg_id);
		$oldModel	 = clone $model;

		$serviceTaxRate	 = BookingInvoice::getGstTaxRate($model->bkg_agent_id, $model->bkg_booking_type);
		$staxRate		 = ($serviceTaxRate / 100);

		$driverAllowenceGst							 = round($jsonObj->fare_details->total_driver_charges * $staxRate);
		$gst										 = round($jsonObj->fare_details->base_fare * $staxRate);
		$model->bkgInvoice->bkg_base_amount			 = round($jsonObj->fare_details->base_fare - $gst);
		$model->bkgInvoice->bkg_service_tax			 = $gst + $driverAllowenceGst;
		$model->bkgInvoice->bkg_total_amount		 = $jsonObj->fare_details->total_fare;
		$model->bkgInvoice->bkg_vendor_amount		 = round($jsonObj->fare_details->total_fare * 0.75);
		$model->bkgInvoice->bkg_extra_toll_tax		 = $jsonObj->fare_details->extra_charges->toll_charges->amount | 0;
		$model->bkgInvoice->bkg_extra_state_tax		 = $jsonObj->fare_details->extra_charges->state_tax->amount | 0;
		$model->bkgInvoice->bkg_parking_charge		 = $jsonObj->fare_details->extra_charges->parking_charges->amount | 0;
		$model->bkgInvoice->bkg_airport_entry_fee	 = $jsonObj->fare_details->extra_charges->airport_entry_fee->amount | 0;
		$model->bkgInvoice->save();

		$newData			 = $model->getDetailsbyId($model->bkg_id);
		$getOldDifference	 = array_diff_assoc($oldData, $newData);
		$changesForLog		 = $model->getModificationMSG($getOldDifference, 'log');

		$logDesc = "Booking modified";
		$eventid = BookingLog::BOOKING_MODIFIED;

		$desc		 = $logDesc . " Old Values: " . ($changesForLog == "" ? ' No Value Modified' : ($changesForLog));
		$bkgid		 = $model->bkg_id;
		$userInfo	 = UserInfo::getInstance();
		BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $oldModel);

		return $model;
	}

	/**
	 * @param int $referenceId
	 * @return int | false
	 *  */
	public static function getIdByRefCode($referenceId, $agentId)
	{
		$sql = "SELECT bkg_id FROM booking
                WHERE bkg_agent_ref_code =:referenceId AND bkg_agent_id =:agentId";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), ['referenceId' => $referenceId, 'agentId' => $agentId]);
	}

	public static function getGstTaxRate($partnerId, $tripType)
	{
		$gstRate = Filter::getServiceTaxRate();
		if ($partnerId != null && $partnerId != 1249)
		{
			$partnerSetting = PartnerSettings::getValueById($partnerId);
			if ($partnerSetting != null)
			{
				$data = json_decode($partnerSetting['pts_gst_rate'], true);

				$partnerData = (isset($data[$tripType]) ? $data[$tripType] : null);
				$gstRate	 = ($partnerData === null) ? $gstRate : $partnerData;
			}
		}

		return $gstRate;
	}

	// copy function for mmt gst revert
	public function calculateTotal_1()
	{
		$this->calculateServiceTax_1();
		$this->bkg_driver_allowance_amount	 = ($this->bkg_driver_allowance_amount == '') ? 0 : $this->bkg_driver_allowance_amount;
		$this->bkg_total_amount				 = $this->calculateGrossAmount() + $this->getTotalTaxes() + $this->bkg_parking_charge + $this->bkg_driver_allowance_amount;
		$this->calculateDues();
	}

	// copy function for mmt gst revert
	public function calculateServiceTax_1()
	{
		$tax_rate				 = 0; //$this->getServiceTaxRate();
		//$tax_rate				 = self::getGstTaxRate($this->bivBkg->bkg_agent_id);
		$gross_amount			 = $this->calculateGrossAmount();
		$checkNewGstPickupTime	 = Booking::model()->checkNewGstPickupTime($this->bivBkg->bkg_pickup_date);
		if ($checkNewGstPickupTime)
		{
			/* by ankesh */
			$gross_amount = $gross_amount + $this->bkg_toll_tax + $this->bkg_state_tax + $this->bkg_extra_toll_tax + $this->bkg_extra_state_tax + $this->bkg_driver_allowance_amount + $this->bkg_parking_charge + $this->bkg_airport_entry_fee;
		}
		else
		{
			$gross_amount = $gross_amount + $this->bkg_driver_allowance_amount;
		}
		$this->bkg_service_tax = round($gross_amount * $tax_rate * 0.01);
		$this->getGSTRate();
	}

	public function calculateRescheduleCharge($cancellationCharge, $prevPickupTime)
	{
		$prevPickupTimeLess1Hr	 = date('Y-m-d H:i:s', strtotime("-60 minutes", strtotime($prevPickupTime)));
		$id						 = 1;
		if (date('Y-m-d H:i:s') > $prevPickupTimeLess1Hr && date('Y-m-d H:i:s') < $prevPickupTime)
		{
			$id = 2;
		}
		$data				 = Filter::getExtraChargeDetails($id);
		$rescheduleCharge	 = round($cancellationCharge * ($data['value'] / 100));
		return $rescheduleCharge;
//		$this->bkg_extra_charge += ($cancellationCharge > 0)?$rescheduleCharge:0;
//		$this->bkg_extra_charge_details = json_encode([["id"=>$data['id'],'desc'=>$data['desc'],'value'=>$rescheduleCharge]]);
//		$this->calculateTotal();
	}

	/**
	 * @param integer $bkgId
	 * @param integer $amount | Wallet Amount
	 * @param integer $minAmount | Amount to apply wallet
	 * @return boolean | ReturnSet
	 * @throws Exception
	 */
	public function processUserWallet($amount, $minAmount)
	{
		$returnSet = new ReturnSet();
		try
		{
			$model = $this;
			if (!$model)
			{
				throw new Exception("Invalid Booking ", ReturnSet::ERROR_INVALID_DATA);
			}
			$userId			 = $model->bivBkg->bkgUserInfo->bkg_user_id;
			$getWalletAmount = UserWallet::getBalance($userId);
			if ($getWalletAmount == 0)
			{
				goto skipProcessWallet;
			}

			if ($userId > 0 && $amount > 0)
			{
				$amount			 = ($getWalletAmount <= $amount) ? $amount : $getWalletAmount;
				$amount			 = min($amount, $minAmount);
				$bkgInvoiceModel = $model->evaluateWallet($amount);
			}

			if ($bkgInvoiceModel->bkg_wallet_used >= $minAmount)
			{
				$walletUsed	 = $model->redeemWallet($bkgInvoiceModel);
				$amountToPay = 0;
			}
			else if (($bkgInvoiceModel->bkg_wallet_used < $minAmount) && $bkgInvoiceModel->bkg_wallet_used > 0)
			{
				$walletUsed	 = $bkgInvoiceModel->bkg_wallet_used;
				$amountToPay = ($minAmount - $bkgInvoiceModel->bkg_wallet_used);
			}
			skipProcessWallet:
			$returnSet->setStatus(true);
			$returnSet->setData(['walletUsed' => $walletUsed, 'amountToPay' => $amountToPay]);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			Logger::error($ex);
		}
		return $returnSet;
	}

	/**
	 *
	 * @param BookingInvoice $bkgInvModel
	 * @return type
	 * @throws Exception
	 */
	public function redeemWallet($bkgInvModel)
	{
		$transaction = DBUtil::beginTransaction();
		try
		{
			if (!$bkgInvModel)
			{
				throw new Exception('Invalid Data', ReturnSet::ERROR_INVALID_DATA);
			}
			$bkgInvModel->refresh();
			$model			 = $bkgInvModel->bivBkg;
			$totalAdvance	 = $bkgInvModel->bkg_net_advance_amount + $bkgInvModel->bkg_wallet_used;
			$minPayment		 = $bkgInvModel->calculateMinPayment();
			if (($totalAdvance > $minPayment))
			{
				$result2 = CActiveForm::validate($bkgInvModel);
				if ($result2 !== '[]')
				{
					throw new Exception(json_encode($bkgInvModel->getErrors()), ReturnSet::ERROR_VALIDATION);
				}
				if ($bkgInvModel->bkg_is_wallet_selected == 1 && $bkgInvModel->bkg_wallet_used > 0)
				{
					$walletUsed	 = $bkgInvModel->bkg_wallet_used;
					$retSet		 = $model->useWalletPayment();
					if (!$retSet->getStatus())
					{
						throw new Exception(json_encode($retSet->getErrors()), $retSet->getErrorCode());
					}
					$bkgInvModel->refresh();
					$model->refresh();
					$model->bkgUserInfo->bkg_user_last_updated_on = new CDbExpression('NOW()');
					$model->save();
					$model->bkgInvoice->save();
					$model->confirm(true, true);
//					if ($model->bkg_status == 2)
//					{
//						$model->confirmMessages();
//					}
					DBUtil::commitTransaction($transaction);
					return $walletUsed;
				}
				else
				{
					throw new Exception("Payment mode validation failed..", ReturnSet::ERROR_VALIDATION);
				}
			}
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
		}
	}

	public static function getAccountCollectionMismatchData($bkgmodel)
	{
		$where		 = '';
		$dateRange	 = '';
		$bkgTypes	 = $bkgmodel->bkgtypes;
		if (!$bkgmodel->bkg_pickup_date1 || !$bkgmodel->bkg_pickup_date2)
		{
			$dateRange = " AND bkg.bkg_pickup_date > DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) ";
		}
		else
		{
			$fromDate	 = $bkgmodel->bkg_pickup_date1;
			$toDate		 = $bkgmodel->bkg_pickup_date2;
			$dateRange	 = " AND bkg.bkg_pickup_date<= '$toDate 23:59:59' AND bkg.bkg_pickup_date>= '$fromDate 00:00:00' ";
		}
		if (count($bkgTypes) > 0)
		{
			if (in_array('3', $bkgTypes))
			{
				$bkgTypes[] = '2';
			}
			$bkgtype = implode(',', $bkgTypes);
			$where	 = " AND bkg.bkg_booking_type IN($bkgtype)";
		}
		if ($bkgmodel->diffCollectionType > 0)
		{
			$diffSign = ($bkgmodel->diffCollectionType == 1) ? '>' : '<';

			$where = " AND biv.bkg_vendor_collected $diffSign biv.bkg_vendor_actual_collected";
		}
		$sql = "SELECT bkg_id,bkg_booking_id,bkg_create_date,bkg_pickup_date,
				bkg_vendor_collected,bkg_vendor_actual_collected 
			FROM booking bkg
			INNER JOIN booking_track btr 
				ON btr.btk_bkg_id = bkg.bkg_id 
					AND btr.bkg_ride_complete = 1 
			JOIN booking_invoice biv 
				ON  biv.biv_bkg_id = bkg.bkg_id 
					AND biv.bkg_vendor_collected IS NOT NULL 
					AND biv.bkg_vendor_actual_collected IS NOT NULL 
					AND biv.bkg_vendor_collected <> biv.bkg_vendor_actual_collected					
			WHERE bkg_status IN (6,7) 
				AND ABS(biv.bkg_vendor_collected - biv.bkg_vendor_actual_collected ) > 10
			 $dateRange $where";

		$params			 = [];
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'params'		 => $params,
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['bkg_booking_id', 'bkg_vendor_collected',
					'bkg_vendor_actual_collected',
					'bkg_create_date', 'bkg_pickup_date'],
				'defaultOrder'	 => 'bkg_id DESC'], 'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	/**
	 *
	 * @param \BookingInvoice $model
	 * @param \Beans\transaction\AdvanceSlabs $selectedSlab
	 * @param integer $walletUsed
	 * @return boolean
	 */
	public static function getSlabs(\BookingInvoice $model, \Beans\transaction\AdvanceSlabs $selectedSlab = null, $walletUsed = 0)
	{
		if ($model == null)
		{
			return false;
		}
		if (in_array($model->bivBkg->bkg_status, [2, 3, 5, 6, 7]))
		{
			$model->refresh();
		}
		$minPerc				 = Config::getMinAdvancePercent($model->bivBkg->bkg_agent_id, $model->bivBkg->bkg_booking_type, $model->bivBkg->bkgSvcClassVhcCat->scc_ServiceClass->scc_id, $model->bivBkg->bkgPref->bkg_is_gozonow);
		BookingPref::updateMinAdvanceParams($model->biv_bkg_id, $minPerc, $model->bkg_total_amount);
		$maxPaymentWithDiscount	 = round($model->bkg_total_amount) - $model->getAdvanceReceived();
		$maxPaymentWithDiscount	 = (int) ($maxPaymentWithDiscount + $walletUsed);
		$arrPartPayPercent		 = array_unique([$minPerc, 50, 100]);
		$paymentOptions			 = Config::get('payment.setting');
		$arrPaymentOptions		 = json_decode($paymentOptions, true);
		$ctr					 = 0;
		foreach ($arrPartPayPercent as $key => $value)
		{
			$key		 = array_search($value, array_column($arrPaymentOptions, 'percentage'));
			$payOption	 = $arrPaymentOptions[$key];
			$percentage	 = $payOption['percentage'];
			$value		 = round($maxPaymentWithDiscount * $payOption['percentage'] / 100);
			$label		 = $payOption['label'] . " (" . $payOption['percentage'] . "%)";
			if (!empty($selectedSlab))
			{
				$isSelected	 = ($selectedSlab->percentage == $percentage) ? 1 : 0;
				$value		 = ($value >= $walletUsed) ? ($value - $walletUsed) : 0;
				if ($selectedSlab->value > 0 && ($selectedSlab->percentage == $percentage))
				{
					$isSelected	 = 1;
					$value		 = $selectedSlab->value;
					$label       = "Pay";
				}
			}
			else
			{
				$isSelected = ($ctr == 0) ? 1 : 0;
			}

			$data = ['percentage' => $percentage,
				'value'		 => $value,
				'label'		 => $label,
				'isSelected' => $isSelected];

			$ctr++;
			$obj			 = new \Beans\transaction\AdvanceSlabs();
			$paymentLevels[] = \Beans\transaction\AdvanceSlabs::setElement($data);
		}

		$isAllowedCash = BookingPref::isFullCashAllowed($model->bivBkg->bkg_id);
		if ($isAllowedCash)
		{
			$cashData = ['percentage' => 0, 'value' => 0, 'label' => 'Pay in cash', 'isSelected' => 0];
			array_push($paymentLevels, \Beans\transaction\AdvanceSlabs::setElement($cashData));
		}

		return $paymentLevels;
	}

	public function checkAccountMisMatch()
	{
		$this->refresh();
		$estimatedVendorCollected	 = $this->bkg_vendor_collected;
		$actualVendorCollected		 = $this->bkg_vendor_actual_collected;
		return ($estimatedVendorCollected > 0 && $actualVendorCollected > 0 && abs($actualVendorCollected - $estimatedVendorCollected) > 10);
	}

	public function getListWithExtraCharges($bkgmodel)
	{
		$where		 = '';
		$dateRange	 = '';

		if (!$bkgmodel->bkg_pickup_date1 || !$bkgmodel->bkg_pickup_date2)
		{
			$dateRange = " AND bkg.bkg_pickup_date > DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) ";
		}
		else
		{
			$fromDate	 = $bkgmodel->bkg_pickup_date1;
			$toDate		 = $bkgmodel->bkg_pickup_date2;
			$dateRange	 = " AND bkg.bkg_pickup_date<= '$toDate 23:59:59' AND bkg.bkg_pickup_date>= '$fromDate 00:00:00' ";
		}
		$bkgTypes = $bkgmodel->bkgtypes;
		if (count($bkgTypes) > 0)
		{
			if (in_array('3', $bkgTypes))
			{
				$bkgTypes[] = '2';
			}
			$bkgtype = implode(',', $bkgTypes);
			$where	 = " AND bkg.bkg_booking_type IN($bkgtype)";
		}
		$sql = "SELECT bkg.bkg_id, bkg.bkg_booking_id, bkg.bkg_pickup_date, bkg.bkg_create_date,
			inv.bkg_extra_km_charge, inv.bkg_parking_charge, inv.bkg_extra_state_tax, round(inv.bkg_extra_toll_tax) bkg_extra_toll_tax, inv.bkg_extra_total_min_charge
			FROM booking bkg
			JOIN booking_invoice inv ON inv.biv_bkg_id = bkg.bkg_id
			WHERE bkg.bkg_status IN (6,7) AND 
		(inv.bkg_extra_km_charge + inv.bkg_parking_charge + inv.bkg_extra_state_tax + 
			inv.bkg_extra_toll_tax + inv.bkg_extra_total_min_charge) > 0
			 $dateRange $where";

		$params			 = [];
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'params'		 => $params,
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['bkg_booking_id', 'bkg_create_date', 'bkg_pickup_date'],
				'defaultOrder'	 => 'bkg_id DESC'], 'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

}

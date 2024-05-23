<?php

/**
 * This is the model class for table "partner_settings".
 *
 * The followings are the available columns in table 'partner_settings':
 * @property integer $pts_id
 * @property integer $pts_agt_id
 * @property integer $pts_is_stop_vendor_payment
 * @property string $pts_create_date
 * @property integer $pts_send_invoice_to
 * @property integer $pts_generate_invoice_to
 * @property float $pts_local_count
 * @property float $pts_outstattion_count
 * @property string $pts_additional_param
 * @property integer $pts_drv_share_min
 * @property integer $pts_rotating_credit_limit
 * @property integer $pts_is_payment_lock
 * @property integer $pts_extra_comm_display
 * @property string $pts_referral_urls
 * @property integer $pts_mask_customer_no
 * @property integer $pts_mask_driver_no
 * @property string $pts_gst_rate
 */


class PartnerSettings extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'partner_settings';
	}

	public $sendInvoiceLinkTo	 = array(
		0	 => 'Partner',
		1	 => 'Customer'
	);
	public $generateInvoice		 = array(
		2	 => 'Partner',
		1	 => 'Customer'
	);

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('pts_agt_id, pts_create_date', 'required'),
			array('pts_agt_id, pts_is_stop_vendor_payment', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pts_id, pts_agt_id, pts_is_stop_vendor_payment, pts_create_date, pts_send_invoice_to, pts_generate_invoice_to,pts_outstattion_count,pts_local_count,pts_additional_param,pts_drv_share_min,pts_rotating_credit_limit,pts_is_payment_lock,pts_extra_comm_display,pts_mask_customer_no,pts_mask_driver_no,pts_gst_rate', 'safe', 'on' => 'search'),
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
			'pts_id'					 => 'Pts',
			'pts_agt_id'				 => 'Pts Agt',
			'pts_is_stop_vendor_payment' => 'Pts Is Stop Vendor Payment',
			'pts_create_date'			 => 'Pts Create Date',
			'pts_send_invoice_to'		 => 'Pts Send Invoice',
			'pts_generate_invoice_to'	 => 'Pts generate Invoice',
			'pts_drv_share_min'			 => 'Pts Driver Share Minute',
			'pts_rotating_credit_limit'	 => 'Agent Rotating Credit Limit'
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

		$criteria->compare('pts_id', $this->pts_id);
		$criteria->compare('pts_agt_id', $this->pts_agt_id);
		$criteria->compare('pts_is_stop_vendor_payment', $this->pts_is_stop_vendor_payment);
		$criteria->compare('pts_create_date', $this->pts_create_date, true);
		$criteria->compare('pts_send_invoice_to', $this->pts_send_invoice_to);
		$criteria->compare('pts_drv_share_min', $this->pts_drv_share_min);
		$criteria->compare('pts_rotating_credit_limit', $this->pts_rotating_credit_limit);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PartnerSettings the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 
	 * @param type $agtId
	 * @return type
	 */
	public static function getValueById($agtId)
	{
		$param	 = ['agtId' => $agtId];
		$sql	 = "SELECT * FROM partner_settings WHERE pts_agt_id =:agtId";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), $param);
		return $result;
	}

	public function getbyPartnerId($agtid)
	{
		$criteria	 = new CDbCriteria;
		$criteria->compare('pts_agt_id', $agtid);
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

	/**
	 * This function is used to update additional parameter in partner setting
	 * @return queryObject array
	 */
	public static function updateAdditonalParam($outstationCount, $localCount, $additionalParam, $partnerIds, $type = 0)
	{
		$sql = "";
		if ($type == 0)
		{
			$sql = "UPDATE partner_settings SET pts_additional_param=:additionalParam , pts_local_count=:localCount,pts_outstation_count=:outstationCount   WHERE pts_agt_id IN ($partnerIds)";
			DBUtil::execute($sql, ['outstationCount' => $outstationCount, 'localCount' => $localCount, 'additionalParam' => $additionalParam]);
		}
		else if ($type == 1)
		{
			$sql = "UPDATE partner_settings
                    INNER JOIN 
                    (
                        SELECT agt_id, 
						prc1.prc_id AS out_comm_id,
                        prc1.prc_commission_type AS  out_comm_type,
                        prc1.prc_commission_value  AS out_comm_value, 
						prc2.prc_id AS local_comm_id,
                        prc2.prc_commission_type  AS local_comm_type, 
                        prc2.prc_commission_value AS local_comm_value
                        FROM agents
                        INNER JOIN partner_rule_commission prc1 ON agt_id = prc1.prc_agent_id AND prc1.prc_booking_type = 1 
                        INNER JOIN partner_rule_commission prc2 ON agt_id = prc2.prc_agent_id AND prc2.prc_booking_type = 2 
                        WHERE 1 AND agents.agt_active=1 AND prc1.prc_active=1  AND prc2.prc_active=1
                    )temp ON  temp.agt_id=partner_settings.pts_agt_id
                    SET pts_additional_param = JSON_REPLACE
                    (
                       pts_additional_param,
                       '$.outstation.isApplied',0,
                       '$.outstation.id',temp.out_comm_id,
                       '$.outstation.commissionType',temp.out_comm_type,
                       '$.outstation.commissionValue',temp.out_comm_value,
                       '$.local.isApplied',0,
                       '$.local.id',temp.local_comm_id,
                       '$.local.commissionType',temp.local_comm_type,
                       '$.local.commissionValue',temp.local_comm_value
                    ),
                    pts_outstation_count=:outstationCount ,
                    pts_local_count=:localCount
                    WHERE 1 AND pts_agt_id NOT IN ($partnerIds)";
			DBUtil::execute($sql, ['outstationCount' => $outstationCount, 'localCount' => $localCount]);
		}
	}

}

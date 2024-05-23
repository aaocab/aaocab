<?php

/**
 * This is the model class for table "payment_type".
 *
 * The followings are the available columns in table 'payment_type':
 * @property integer $ptp_id
 * @property integer $ptp_type_id
 * @property string $ptp_name
 * @property string $ptp_display_name
 * @property string $ptp_detail
 * @property string $ptp_created
 * @property string $ptp_modified
 * @property integer $ptp_active
 */
class PaymentType extends CActiveRecord
{

	const TYPE_CASH				 = 1;
	const TYPE_BANK				 = 2;
	const TYPE_PAYTM				 = 3;
	const TYPE_EBS				 = 4;
	const TYPE_GOZO_COINS			 = 5;
	const TYPE_PAYUMONEY			 = 6;
	const TYPE_JOURNAL			 = 7;
	const TYPE_SETTLE				 = 8;
	const TYPE_INTERNATIONAL_CARD	 = 9; //BTREE
	const TYPE_MOBIKWIK			 = 10;
	const TYPE_ZAAKPAY			 = 11;
	const TYPE_FREECHARGE			 = 12;
	const TYPE_AGENT_CORP_CREDIT	 = 13;
	const TYPE_LAZYPAY			 = 14;
	const TYPE_EPAYLATER			 = 15;
	const TYPE_PAYNIMO			 = 16;
	const TYPE_WALLET				 = 17;
	const TYPE_GIFTCARD			 = 18;
	const TYPE_OTHERS				 = 99;
	const TYPE_HDFC				 = 19;
	const TYPE_ICICI				 = 20;
	const TYPE_RAZORPAY			 = 21;
	const TYPE_PAYTM_APP			 = 22;
	const TYPE_EASEBUZZ			 = 23;
	const PAYMENT_SUCCESS			 = 200;
	const PAYMENT_ERROR			 = 201;
	const PAYMENT_WAITING			 = 202;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'payment_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ptp_name, ptp_detail', 'required'),
			array('ptp_type_id, ptp_active', 'numerical', 'integerOnly' => true),
			array('ptp_name', 'length', 'max' => 100),
			array('ptp_display_name', 'length', 'max' => 255),
			array('ptp_detail', 'length', 'max' => 1000),
			array('ptp_created, ptp_modified', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ptp_id, ptp_type_id, ptp_name, ptp_display_name, ptp_detail, ptp_created, ptp_modified, ptp_active', 'safe', 'on' => 'search'),
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
			'ptp_id'			 => 'Ptp',
			'ptp_type_id'		 => 'Type',
			'ptp_name'			 => 'Name',
			'ptp_display_name'	 => 'Display Name',
			'ptp_detail'		 => 'Detail',
			'ptp_created'		 => 'Created',
			'ptp_modified'		 => 'Modified',
			'ptp_active'		 => 'Active',
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

		$criteria->compare('ptp_id', $this->ptp_id);
		$criteria->compare('ptp_type_id', $this->ptp_type_id);
		$criteria->compare('ptp_name', $this->ptp_name, true);
		$criteria->compare('ptp_display_name', $this->ptp_display_name, true);
		$criteria->compare('ptp_detail', $this->ptp_detail, true);
		$criteria->compare('ptp_created', $this->ptp_created, true);
		$criteria->compare('ptp_modified', $this->ptp_modified, true);
		$criteria->compare('ptp_active', $this->ptp_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PaymentType the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function ledgerList($ptpId = '')
	{
		$eventlist = [
			1							 => 1,
			2							 => 23,
			3							 => 16,
			4							 => 21,
			5							 => 36, //Gozocoins
			6							 => 20,
			7							 => 33, //TYPE_JOURNAL
			8							 => 33, //TYPE_SETTLE
			9							 => 32, //TYPE_INTERNATIONAL_CARD
			10							 => 17,
			11							 => 19,
			12							 => 18,
			13							 => 49,
			14							 => 39,
			15							 => 42,
			16							 => 46,
			17							 => 47, //Wallet
			18							 => 48, //GiftCard
			19							 => 29, //HDFC
			20							 => 30, //ICICI
			99							 => 0, //other
			PaymentType::TYPE_RAZORPAY	 => Accounting::LI_RAZORPAY,
			PaymentType::TYPE_EASEBUZZ	 => Accounting::LI_EASEBUZZ
		];
		if ($ptpId != '')
		{
			return $eventlist[$ptpId];
		}
		asort($eventlist);
		return $eventlist;
	}

	public static function getLedgerId($id)
	{
		$list = self::model()->ledgerList();
		return $list[$id];
	}

	public function ptpList($ledgeId)
	{
		$eventlist = [
			1						 => 1,
			23						 => 2,
			16						 => 3,
			21						 => 4,
			36						 => 5, //Gozocoins
			20						 => 6,
			33						 => 7, //TYPE_JOURNAL
			0						 => 8, //TYPE_SETTLE
			32						 => 9, //TYPE_INTERNATIONAL_CARD
			17						 => 10,
			19						 => 11,
			18						 => 12,
			49						 => 13,
			39						 => 14,
			42						 => 15,
			46						 => 16,
			47						 => 17, //Wallet
			48						 => 18,
			0						 => 99, //other
			29						 => 19,
			30						 => 20,
			Accounting::LI_RAZORPAY	 => PaymentType::TYPE_RAZORPAY,
			Accounting::LI_EASEBUZZ	 => PaymentType::TYPE_EASEBUZZ
		];
		if ($ledgeId != '')
		{
			return $eventlist[$ledgeId];
		}
		asort($eventlist);
		return $eventlist;
	}

	public function getList($comm = true, $credits = true)
	{
		$list = [
			1	 => 'Cash',
			2	 => 'Bank',
			3	 => 'PayTM',
			4	 => 'EBS',
			5	 => 'GozoCoins',
			6	 => 'PayUMoney',
			7	 => 'Journal',
			8	 => 'Settle',
			9	 => 'International',
			10	 => 'Mobikwik',
			11	 => 'Zaakpay',
			12	 => 'Freecharge',
			13	 => 'Partner Wallet',
			14	 => 'LazyPay',
			15	 => 'EPayLater',
			16	 => 'PayNimo',
			17	 => 'Wallet',
			18	 => 'GiftCard',
			20	 => 'Payu',
			21	 => 'RazorPay',
			23	 => 'EaseBuzz'
				//	99 => 'Others'
		];
		if (!$comm)
		{
			unset($list[3]);
			unset($list[4]);
			//unset($list[6]);
			unset($list[9]);
			unset($list[10]);
			unset($list[11]);
			unset($list[12]);
			unset($list[13]);
			unset($list[14]);
			unset($list[15]);
			unset($list[16]);
			//unset($list[7]);
		}
		if ($credits == false)
		{
			unset($list[5]);
			//unset($list[13]);
		}
		return $list;
	}

	public function initiate($bkgid)
	{
		$bkgmodel				 = Booking::model()->findByPk($bkgid);
		$model					 = new PaymentType();
		$model->ptp_type_id		 = 3;
		$model->ptp_display_name = $bkgmodel->bkg_booking_id;
		$model->ptp_name		 = $bkgmodel->bkg_booking_id;
		$model->ptp_detail		 = $bkgmodel->bkg_booking_id . ' Gozo Cabs';
		$sucess					 = false;
		if ($model->validate())
		{
			if ($model->save())
			{
				return $model->ptp_id;
			}
		}
		return $sucess;
	}

	public static function isOnline($pType)
	{
		$onlinePaymentGateway = [
			PaymentType::TYPE_PAYTM,
			PaymentType::TYPE_EBS,
			PaymentType::TYPE_PAYUMONEY,
			PaymentType::TYPE_MOBIKWIK,
			PaymentType::TYPE_INTERNATIONAL_CARD,
			PaymentType::TYPE_FREECHARGE,
			PaymentType::TYPE_LAZYPAY,
			PaymentType::TYPE_EPAYLATER,
			PaymentType::TYPE_PAYNIMO,
			PaymentType::TYPE_RAZORPAY,
			PaymentType::TYPE_EASEBUZZ
		];
		if (in_array($pType, $onlinePaymentGateway))
		{
			return true;
		}
		return false;
	}

	public function payentTypeFromLedger($ledger)
	{
		$eventlist = [
			1						 => 1,
			23						 => 2,
			29						 => 19,
			30						 => 20,
			16						 => 3,
			21						 => 4,
			0						 => 5, //Gozocoins
			20						 => 6,
			0						 => 7, //TYPE_JOURNAL
			0						 => 8, //TYPE_SETTLE
			0						 => 9, //TYPE_INTERNATIONAL_CARD
			17						 => 10,
			19						 => 11,
			18						 => 12,
			49						 => 13,
			39						 => 14,
			42						 => 15,
			46						 => 16,
			47						 => 17,
			48						 => 18,
			0						 => 99, //other
			Accounting::LI_RAZORPAY	 => PaymentType::TYPE_RAZORPAY,
			54						 => 22,
			Accounting::LI_EASEBUZZ	 => PaymentType::TYPE_EASEBUZZ,
		];
		if ($ledger != '')
		{
			return $eventlist[$ledger];
		}
		asort($eventlist);
		return $eventlist;
	}

}

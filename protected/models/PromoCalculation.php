<?php

/**
 * This is the model class for table "promo_calculation".
 *
 * The followings are the available columns in table 'promo_calculation':
 * @property integer $pcn_id
 * @property string $pcn_title
 * @property string $pcn_desc
 * @property integer $pcn_value_type_cash
 * @property integer $pcn_value_cash
 * @property integer $pcn_value_type_coins
 * @property integer $pcn_value_coins
 * @property integer $pcn_max_cash
 * @property integer $pcn_min_cash
 * @property integer $pcn_min_coins
 * @property integer $pcn_max_coins
 * @property integer $pcn_fixed_price
 * @property integer $pcn_type
 * @property integer $pcn_active
 * @property integer $pcn_promo_id
 * @property string $pcn_modified
 * @property string $pcn_created
 */
class PromoCalculation extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'promo_calculation';
	}

	public function defaultScope()
	{
		$arr = array(
			'condition' => "pcn_active > 0",
		);
		return $arr;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('pcn_title, pcn_desc', 'required'),
			array('pcn_value_type_cash, pcn_value_cash, pcn_value_type_coins, pcn_value_coins, pcn_max_cash, pcn_min_cash, pcn_min_coins, pcn_max_coins, pcn_type, pcn_active', 'numerical', 'integerOnly' => true),
			array('pcn_title', 'length', 'max' => 250),
			array('pcn_desc', 'length', 'max' => 500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pcn_id, pcn_title, pcn_desc, pcn_value_type_cash, pcn_value_cash, pcn_value_type_coins, pcn_value_coins, pcn_max_cash, pcn_min_cash, pcn_min_coins, pcn_max_coins, pcn_type,pcn_active, pcn_modified, pcn_created, pcn_activate_on, pcn_promo_id,pcn_fixed_price', 'safe'),
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
			'pcn_id'				 => 'Pcn',
			'pcn_title'				 => 'Title',
			'pcn_desc'				 => 'Description',
			'pcn_value_type_cash'	 => 'Value Type Cash',
			'pcn_value_cash'		 => 'Value Cash',
			'pcn_value_type_coins'	 => 'Value Type Coins',
			'pcn_value_coins'		 => 'Value Coins',
			'pcn_max_cash'			 => 'Max Cash',
			'pcn_min_cash'			 => 'Min Cash',
			'pcn_min_coins'			 => 'Min Coins',
			'pcn_max_coins'			 => 'Max Coins',
			'pcn_type'				 => '1:cash,2:coin,3:both',
			'pcn_active'			 => 'Active',
			'pcn_modified'			 => 'Modified',
			'pcn_created'			 => 'Created',
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

		$criteria->compare('pcn_id', $this->pcn_id);
		$criteria->compare('pcn_title', $this->pcn_title, true);
		$criteria->compare('pcn_desc', $this->pcn_desc, true);
		$criteria->compare('pcn_value_type_cash', $this->pcn_value_type_cash);
		$criteria->compare('pcn_value_cash', $this->pcn_value_cash);
		$criteria->compare('pcn_value_type_coins', $this->pcn_value_type_coins);
		$criteria->compare('pcn_value_coins', $this->pcn_value_coins);
		$criteria->compare('pcn_max_cash', $this->pcn_max_cash);
		$criteria->compare('pcn_min_cash', $this->pcn_min_cash);
		$criteria->compare('pcn_min_coins', $this->pcn_min_coins);
		$criteria->compare('pcn_max_coins', $this->pcn_max_coins);
		$criteria->compare('pcn_type', $this->pcn_type);
		$criteria->compare('pcn_active', $this->pcn_active);
		$criteria->compare('pcn_modified', $this->pcn_modified, true);
		$criteria->compare('pcn_created', $this->pcn_created, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PromoCalculation the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getAllCalculationCode()
	{
		$data	 = [];
		$sql	 = "SELECT pcn_id,pcn_title FROM promo_calculation WHERE pcn_active=1";
		$res	 = DBUtil::queryAll($sql, DBUtil::SDB());
		if (count($res))
		{
			foreach ($res as $key => $value)
			{
				$data[$value['pcn_id']] = $value['pcn_title'];
			}
		}
		return $data;
	}

	public function calculate($cartAmount)
	{
		$cashAmount	 = 0;
		$coinAmount	 = 0;
		if ($this->pcn_type == 1)
		{
			$min		 = $this->pcn_min_cash;
			$max		 = $this->pcn_max_cash;
			$type		 = $this->pcn_value_type_cash;
			$offerValue	 = $this->pcn_value_cash;
			$cashAmount	 = $this->calculatePromoAmount($max, $min, $type, $offerValue, $cartAmount);
		}
		else if ($this->pcn_type == 2)
		{
			$min		 = $this->pcn_min_coins;
			$max		 = $this->pcn_max_coins;
			$type		 = $this->pcn_value_type_coins;
			$offerValue	 = $this->pcn_value_coins;
			$coinAmount	 = $this->calculatePromoAmount($max, $min, $type, $offerValue, $cartAmount);
		}
		else if ($this->pcn_type == 4)
		{
			$cashAmount	 = $cartAmount - $this->pcn_fixed_price;
		}
		else
		{
			$min		 = $this->pcn_min_cash;
			$max		 = $this->pcn_max_cash;
			$type		 = $this->pcn_value_type_cash;
			$offerValue	 = $this->pcn_value_cash;
			$cashAmount	 = $this->calculatePromoAmount($max, $min, $type, $offerValue, $cartAmount);
			$min1		 = $this->pcn_min_coins;
			$max1		 = $this->pcn_max_coins;
			$type1		 = $this->pcn_value_type_coins;
			$offerValue1 = $this->pcn_value_coins;
			$coinAmount	 = $this->calculatePromoAmount($max1, $min1, $type1, $offerValue1, $cartAmount);
		}
		$amountArr['cash']		 = $cashAmount;
		$amountArr['coins']		 = $coinAmount;
		return $amountArr;
	}

	public function calculatePromoAmount($max, $min, $type, $offerValue, $cartAmount)
	{
		$discountValue = 0;
		if ($type == 1)
		{
			$discountValue = $cartAmount * $offerValue / 100;
			if ($max > 0 && $discountValue > $max)
			{
				$discountValue = $max;
			}
			if ($min > 0 && ($discountValue < $min))
			{
				$discountValue = $min;
			}
		}
		if ($type == 2)
		{
			$discountValue = $offerValue;
//            $discountValue = ($discountValue >= $max) ? $max : $discountValue;
//            $discountValue = ($discountValue <= $min) ? $min : $discountValue;
		}


		return round($discountValue);
	}

	public function getByPromoId($prmId)
	{
		return $this->find('pcn_promo_id=:prm', ['prm' => $prmId]);
	}

}

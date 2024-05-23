<?php

/**
 * This is the model class for table "addons".
 *
 * The followings are the available columns in table 'addons':
 * @property integer $adn_id
 * @property integer $adn_cancel_rule_id
 * @property integer $adn_advance_rule_id
 * @property integer $adn_charge_type
 * @property integer $adn_charge
 * @property integer $adn_max_charge
 * @property integer $adn_min_charge
 * @property integer $adn_active
 * @property string $adn_desc
 * @property string $adn_created
 * @property string $adn_modified
 */
class Addons extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'addons';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('adn_created', 'required'),
			array('adn_cancel_rule_id, adn_advance_rule_id, adn_charge_type, adn_charge, adn_max_charge, adn_min_charge, adn_active', 'numerical', 'integerOnly'=>true),
			array('adn_modified', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('adn_id, adn_cancel_rule_id, adn_advance_rule_id, adn_charge_type, adn_charge, adn_max_charge, adn_min_charge, adn_active, adn_created, adn_modified, adn_desc', 'safe', 'on'=>'search'),
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
			'adn_id' => 'Adn',
			'adn_cancel_rule_id' => 'Adn Cancel Rule',
			'adn_advance_rule_id' => 'Adn Advance Rule',
			'adn_charge_type' => 'Adn Charge Type',
				'adn_charge' => 'Adn Charge',
			'adn_max_charge' => 'Adn Max Charge',
			'adn_min_charge' => 'Adn Min Charge',
			'adn_active' => 'Adn Active',
			'adn_desc' => 'Adn Desc',
			'adn_created' => 'Adn Created',
			'adn_modified' => 'Adn Modified',
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

		$criteria->compare('adn_id',$this->adn_id);
		$criteria->compare('adn_cancel_rule_id',$this->adn_cancel_rule_id);
		$criteria->compare('adn_advance_rule_id',$this->adn_advance_rule_id);
		$criteria->compare('adn_charge_type',$this->adn_charge_type);
		$criteria->compare('adn_charge',$this->adn_charge);
		$criteria->compare('adn_max_charge',$this->adn_max_charge);
		$criteria->compare('adn_min_charge',$this->adn_min_charge);
		$criteria->compare('adn_active',$this->adn_active);
		$criteria->compare('adn_created',$this->adn_created,true);
		$criteria->compare('adn_modified',$this->adn_modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Addons the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
	 * Calculate applicable amount
	 * @param type $addOnId
	 * @param type $baseAmount
	 * @return boolean or integer
	 */
	public static function getApplicableCharge($addOnId,$baseAmount)
	{
		$model	 = self::model()->findByPk($addOnId);
		if(empty($model)){
		  return false;
		}
		if ($model->adn_charge_type == 1 && $baseAmount > 0)
		{
			$amount	 = round($baseAmount * $model->adn_charge / 100);
			$amount	 = ($model->adn_max_charge > 0 && $amount > $model->adn_max_charge) ? $model->adn_max_charge : $amount;
			$amount	 = ($model->adn_min_charge > 0 && $amount < $model->adn_min_charge) ? $model->adn_min_charge : $amount;
		}
		else
		{
			$amount = min([$model->adn_charge, $baseAmount]);
		}
		return $amount;
	}

	/*
	 * Get Addon Charge
	 * @param type $addOnId
	 */
	public static function getChargeById($addOnId)
	{
		$param	 = ['id' => $addOnId];
		$sql = "select adn_charge from addons where adn_id=:id";
		$value = DBUtil::command($sql, DBUtil::MDB())->queryScalar($param);
		return $value;
	}
}

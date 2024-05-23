<?php

/**
 * This is the model class for table "gift_card_partner".
 *
 * The followings are the available columns in table 'gift_card_partner':
 * @property integer $prp_id
 * @property integer $prp_promo_id
 * @property integer $prp_partner_id
 * @property integer $prp_max_use
 * @property integer $prp_active
 * @property string $prp_created
 */
class GiftCardPartner extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'gift_card_partner';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('', 'required'),
			array('prp_promo_id, prp_partner_id, prp_active', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('prp_id, prp_promo_id, prp_partner_id, prp_max_use, prp_active, prp_created', 'safe', 'on'=>'search'),
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
			'prp_id' => 'Prp',
			'prp_promo_id' => 'Prp Promo',
			'prp_partner_id' => 'Prp Partner',
			'prp_max_use'  => 'Max Use',
			'prp_active' => 'Prp Active',
			'prp_created' => 'Prp Created',
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

		$criteria->compare('prp_id',$this->prp_id);
		$criteria->compare('prp_promo_id',$this->prp_promo_id);
		$criteria->compare('prp_partner_id',$this->prp_partner_id);
		$criteria->compare('prp_max_use', $this->prp_max_use);
		$criteria->compare('prp_active',$this->prp_active);
		$criteria->compare('prp_created',$this->prp_created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GiftCardPartner the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function saveGiftCardInfo()
	{
		$success	 = false;
		$transaction = DBUtil::beginTransaction();
		try
		{
			$result	 = CActiveForm::validate($this, null, false);
			if ($result == '[]')
			{
				$this->prp_created		 = new CDbExpression('NOW()');
				if($this->save())
				{
					$success = true;
				}
				else
				{
					throw new Exception('Failed to create gift card partner');
				}
				DBUtil::commitTransaction($transaction);
			}
		} catch (Exception $ex) {
			$error = "Failed to create gift card partner";
			DBUtil::rollbackTransaction($transaction);
		}
		return ['success' => $success, 'error' => $error];
	}
	
	public function getGiftCardPartnerId($promoId, $agentId)
	{
		$sql = "select prp_id from gift_card_partner where prp_promo_id = $promoId AND prp_partner_id = $agentId"; 
		return DBUtil::command($sql, DBUtil::SDB())->queryScalar();
	}
}

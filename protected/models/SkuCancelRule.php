<?php

/**
 * This is the model class for table "sku_cancel_rule".
 *
 * The followings are the available columns in table 'sku_cancel_rule':
 * @property integer $scr_id
 * @property string $scr_sku_code
 * @property integer $scr_rule_id
 * @property integer $scr_status
 * @property integer $scr_refund_time
 */
class SkuCancelRule extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sku_cancel_rule';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('scr_rule_id, scr_status, scr_refund_time', 'numerical', 'integerOnly'=>true),
			array('scr_sku_code', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('scr_id, scr_sku_code, scr_rule_id, scr_status, scr_refund_time', 'safe', 'on'=>'search'),
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
			'scr_id' => 'Scr',
			'scr_sku_code' => 'Scr Sku Code',
			'scr_rule_id' => 'Scr Rule',
			'scr_status' => 'Scr Status',
			'scr_refund_time' => 'Scr Refund Time',
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

		$criteria->compare('scr_id',$this->scr_id);
		$criteria->compare('scr_sku_code',$this->scr_sku_code,true);
		$criteria->compare('scr_rule_id',$this->scr_rule_id);
		$criteria->compare('scr_status',$this->scr_status);
		$criteria->compare('scr_refund_time',$this->scr_refund_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SkuCancelRule the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    public static function getData($sku, $partnerId)
    {
       return self::model()->find("scr_sku_code=:code AND scr_partner_id=:partnerId", ["code" => $sku, "partnerId" => $partnerId]);
    }

}

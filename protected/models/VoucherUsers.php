<?php

/**
 * This is the model class for table "voucher_users".
 *
 * The followings are the available columns in table 'voucher_users':
 * @property integer $vus_id
 * @property integer $vus_user_id
 * @property integer $vus_vch_id
 * @property integer $vus_used_ctr
 * @property integer $vus_max_allowed
 * @property string $vus_valid_till
 * @property integer $vus_active
 *
 * The followings are the available model relations:
 * @property Vouchers $vusVch
 * @property Vouchers $vouchers
 */
class VoucherUsers extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'voucher_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vus_vch_id', 'required', 'on' => 'add'),
			array('vus_user_id, vus_vch_id, vus_used_ctr, vus_max_allowed, vus_active', 'numerical', 'integerOnly'=>true),
			array('vus_valid_till', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vus_id, vus_user_id, vus_vch_id, vus_used_ctr, vus_max_allowed, vus_valid_till, vus_active', 'safe', 'on'=>'search'),
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
			'vusVch' => array(self::BELONGS_TO, 'Vouchers', 'vus_vch_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'vus_id' => 'Vus',
			'vus_user_id' => 'Customer',
			'vus_vch_id' => 'Voucher',
			'vus_used_ctr' => 'Vus Used Ctr',
			'vus_max_allowed' => 'Vus Max Allowed',
			'vus_valid_till' => 'Vus Valid Till',
			'vus_active' => 'Vus Active',
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

		$criteria->compare('vus_id',$this->vus_id);
		$criteria->compare('vus_user_id',$this->vus_user_id);
		$criteria->compare('vus_vch_id',$this->vus_vch_id);
		$criteria->compare('vus_used_ctr',$this->vus_used_ctr);
		$criteria->compare('vus_max_allowed',$this->vus_max_allowed);
		$criteria->compare('vus_valid_till',$this->vus_valid_till,true);
		$criteria->compare('vus_active',$this->vus_active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VoucherUsers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public static function checkIfVoucherExists($uid,$vid)
    {
		$sql	 = "SELECT count(vus_id) as cnt FROM `voucher_users`  where  vus_user_id = $uid AND vus_vch_id = $vid  AND vus_active = 1";		
		return DBUtil::queryRow($sql, DBUtil::SDB());
	}
	public static function countVouchers($vid)
    {
		$sql	 = "SELECT count(vus_id) as cnt FROM `voucher_users`  where vus_active = 1  AND  vus_vch_id = $vid";		
		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	public static function getVoucherList($uid)
    {
		$sql	 = "select vch_id, vch_code from vouchers where vch_id IN(SELECT vus_vch_id  FROM `voucher_users` where vus_user_id = $uid  AND vus_active = 1)";
		$count	 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		return new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'		 =>
			['attributes'	 =>['vch_code'],			
			'defaultOrder'	 => $defaultOrder],
			'pagination'	 => ['pageSize' => 10],
		]);
    }
}

<?php

/**
 * This is the model class for table "voucher_partner".
 *
 * The followings are the available columns in table 'voucher_partner':
 * @property integer $vpr_id
 * @property integer $vpr_partner_id
 * @property integer $vpr_vch_id
 * @property integer $vpr_used_ctr
 * @property integer $vpr_max_allowed
 * @property string $vpr_valid_till
 * @property integer $vpr_active
 *
 * The followings are the available model relations:
 * @property Vouchers $vprVch
 */
class VoucherPartner extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'voucher_partner';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vpr_partner_id', 'required', 'on' => 'add'),
			array('vpr_partner_id, vpr_vch_id, vpr_used_ctr, vpr_max_allowed, vpr_active', 'numerical', 'integerOnly'=>true),
			array('vpr_valid_till', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vpr_id, vpr_partner_id, vpr_vch_id, vpr_used_ctr, vpr_max_allowed, vpr_valid_till, vpr_active', 'safe', 'on'=>'search'),
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
			'vprVch' => array(self::BELONGS_TO, 'Vouchers', 'vpr_vch_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'vpr_id' => 'Vpr',
			'vpr_partner_id' => 'Voucher Partner',
			'vpr_vch_id' => 'Vpr Vch',
			'vpr_used_ctr' => 'Vpr Used Ctr',
			'vpr_max_allowed' => 'Vpr Max Allowed',
			'vpr_valid_till' => 'Vpr Valid Till',
			'vpr_active' => 'Vpr Active',
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

		$criteria->compare('vpr_id',$this->vpr_id);
		$criteria->compare('vpr_partner_id',$this->vpr_partner_id);
		$criteria->compare('vpr_vch_id',$this->vpr_vch_id);
		$criteria->compare('vpr_used_ctr',$this->vpr_used_ctr);
		$criteria->compare('vpr_max_allowed',$this->vpr_max_allowed);
		$criteria->compare('vpr_valid_till',$this->vpr_valid_till,true);
		$criteria->compare('vpr_active',$this->vpr_active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VoucherPartner the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function checkIfPartnerExists($pid,$vid)
    {
		$sql	 = "SELECT count(vpr_id) as cnt FROM `voucher_partner`  where  vpr_partner_id = $pid AND vpr_vch_id = $vid  AND vpr_active = 1";		
		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	public static function getPartnerList($vid)
    {
		$sql	 = "SELECT agt.agt_id, trim(if((agt.agt_company=''||agt.agt_company IS NULL),concat(agt.agt_fname, ' ',agt.agt_lname),concat(agt.agt_company,' (',agt.agt_fname, ' ',agt.agt_lname,')',
					IF(agt_type = 0,'-TRAVEL',IF(agt_type=1,'-CORPORATE','-RESELLER'))))) as agt_name
					FROM agents agt
					where agt.agt_id IN(SELECT vpr_partner_id  FROM `voucher_partner` where vpr_vch_id = $vid  AND vpr_active = 1)";
		$count	 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		return new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'		 =>
			['attributes'	 =>['agt_name'],			
			'defaultOrder'	 => $defaultOrder],
			'pagination'	 => ['pageSize' => 10],
		]);
    }
	
	public static function countPartners($vid)
    {
		$sql	 = "SELECT count(vpr_id) as cnt FROM `voucher_partner`  where vpr_active = 1  AND  vpr_vch_id = $vid";		
		return DBUtil::queryRow($sql, DBUtil::SDB());
	}


}

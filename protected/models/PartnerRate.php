<?php

/**
 * This is the model class for table "partner_rate".
 *
 * The followings are the available columns in table 'partner_rate':
 * @property integer $prt_id
 * @property integer $prt_partner_id
 * @property integer $prt_vehicletype_id
 * @property integer $prt_route_id
 * @property integer $prt_trip_type
 * @property integer $prt_is_toll_included
 * @property integer $prt_toll_tax
 * @property integer $prt_is_state_included
 * @property integer $prt_state_tax
 * @property integer $prt_vendor_amount
 * @property integer $prt_total_amount
 * @property integer $prt_night_charge
 * @property string $prt_create_date
 * @property integer $prt_status
 * @property string $prt_log
 */
class PartnerRate extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'partner_rate';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('prt_partner_id, prt_route_id, prt_total_amount', 'required'),
			array('prt_partner_id, prt_vehicletype_id, prt_route_id, prt_trip_type, prt_is_toll_included, prt_toll_tax, prt_is_state_included, prt_state_tax, prt_vendor_amount, prt_total_amount, prt_night_charge, prt_status', 'numerical', 'integerOnly' => true),
			array('prt_log', 'length', 'max' => 4000),
			array('prt_create_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('prt_id, prt_partner_id, prt_vehicletype_id, prt_route_id, prt_trip_type, prt_is_toll_included, prt_toll_tax, prt_is_state_included, prt_state_tax, prt_vendor_amount, prt_total_amount, prt_night_charge, prt_create_date, prt_status, prt_log', 'safe', 'on' => 'search'),
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
			'prt_id'				 => 'Prt',
			'prt_partner_id'		 => 'Partner',
			'prt_vehicletype_id'	 => 'Vehicletype',
			'prt_route_id'			 => 'Route',
			'prt_trip_type'			 => 'Trip Type',
			'prt_is_toll_included'	 => 'Is Toll Included',
			'prt_toll_tax'			 => 'Toll Tax',
			'prt_is_state_included'	 => 'Is State Included',
			'prt_state_tax'			 => 'State Tax',
			'prt_vendor_amount'		 => 'Vendor Amount',
			'prt_total_amount'		 => 'Total Amount',
			'prt_night_charge'		 => 'Night Charge',
			'prt_create_date'		 => 'Create Date',
			'prt_status'			 => 'Status',
			'prt_log'				 => 'Log',
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

		$criteria->compare('prt_id', $this->prt_id);
		$criteria->compare('prt_partner_id', $this->prt_partner_id);
		$criteria->compare('prt_vehicletype_id', $this->prt_vehicletype_id);
		$criteria->compare('prt_route_id', $this->prt_route_id);
		$criteria->compare('prt_trip_type', $this->prt_trip_type);
		$criteria->compare('prt_is_toll_included', $this->prt_is_toll_included);
		$criteria->compare('prt_toll_tax', $this->prt_toll_tax);
		$criteria->compare('prt_is_state_included', $this->prt_is_state_included);
		$criteria->compare('prt_state_tax', $this->prt_state_tax);
		$criteria->compare('prt_vendor_amount', $this->prt_vendor_amount);
		$criteria->compare('prt_total_amount', $this->prt_total_amount);
		$criteria->compare('prt_night_charge', $this->prt_night_charge);
		$criteria->compare('prt_create_date', $this->prt_create_date, true);
		$criteria->compare('prt_status', $this->prt_status);
		$criteria->compare('prt_log', $this->prt_log, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PartnerRate the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getRates($from_city, $to_city, $cabType, $partnerId)
	{

		$sql	 = "SELECT *
				FROM   partner_rate prt 
				JOIN route rut ON prt.prt_route_id = rut.rut_id AND rut.rut_active = 1
				AND  rut.rut_from_city_id  = $from_city 
				AND  rut.rut_to_city_id  = $to_city 
				 WHERE prt_vehicletype_id = $cabType
				AND prt_partner_id = $partnerId
				AND prt_status = 1
				";
		$data	 = DBUtil::queryRow($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_Rates);
		return $data;
	}

}

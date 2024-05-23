<?php

/**
 * This is the model class for table "vehicle_region_tax_pref".
 *
 * The followings are the available columns in table 'vehicle_region_tax_pref':
 * @property integer $vrt_id
 * @property integer $vrt_source_type
 * @property integer $vrt_source_id
 * @property integer $vrt_destination_type
 * @property integer $vrt_destination_id
 * @property string $vrt_e_pass
 * @property integer $vrt_compac_state_tax
 * @property integer $vrt_sedan_state_tax
 * @property integer $vrt_suv_state_tax
 * @property integer $vrt_tempotraveller_state_tax
 * @property integer $vrt_active
 * @property string $vrt_created_date
 * @property string $vrt_modified_date
 * @property integer $vrt_created_by
 */
class VehicleRegionTaxPref extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vehicle_region_tax_pref';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vrt_source_type, vrt_source_id, vrt_destination_type, vrt_destination_id,  vrt_created_by', 'required'),
			array('vrt_source_type, vrt_source_id, vrt_destination_type, vrt_destination_id, vrt_compac_state_tax, vrt_sedan_state_tax, vrt_suv_state_tax, vrt_tempotraveller_state_tax, vrt_active, vrt_created_by', 'numerical', 'integerOnly'=>true),
			array('vrt_e_pass', 'length', 'max'=>1),
			array('vrt_modified_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vrt_id, vrt_source_type, vrt_source_id, vrt_destination_type, vrt_destination_id, vrt_e_pass, vrt_compac_state_tax, vrt_sedan_state_tax, vrt_suv_state_tax, vrt_tempotraveller_state_tax, vrt_active, vrt_created_date, vrt_modified_date, vrt_created_by', 'safe', 'on'=>'search'),
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
			'vrt_id' => 'Vrt',
			'vrt_source_type' => '1:region,2:state,3:city',
			'vrt_source_id' => 'Vrt Source',
			'vrt_destination_type' => '1:region,2:state,3:city',
			'vrt_destination_id' => 'Vrt Destination',
			'vrt_e_pass' => 'Vrt E Pass',
			'vrt_compac_state_tax' => 'Vrt Compac State Tax',
			'vrt_sedan_state_tax' => 'Vrt Sedan State Tax',
			'vrt_suv_state_tax' => 'Vrt Suv State Tax',
			'vrt_tempotraveller_state_tax' => 'Vrt Tempotraveller State Tax',
			'vrt_active' => 'Vrt Active',
			'vrt_created_date' => 'Vrt Created Date',
			'vrt_modified_date' => 'Vrt Modified Date',
			'vrt_created_by' => 'Vrt Created By',
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

		$criteria->compare('vrt_id',$this->vrt_id);
		$criteria->compare('vrt_source_type',$this->vrt_source_type);
		$criteria->compare('vrt_source_id',$this->vrt_source_id);
		$criteria->compare('vrt_destination_type',$this->vrt_destination_type);
		$criteria->compare('vrt_destination_id',$this->vrt_destination_id);
		$criteria->compare('vrt_e_pass',$this->vrt_e_pass,true);
		$criteria->compare('vrt_compac_state_tax',$this->vrt_compac_state_tax);
		$criteria->compare('vrt_sedan_state_tax',$this->vrt_sedan_state_tax);
		$criteria->compare('vrt_suv_state_tax',$this->vrt_suv_state_tax);
		$criteria->compare('vrt_tempotraveller_state_tax',$this->vrt_tempotraveller_state_tax);
		$criteria->compare('vrt_active',$this->vrt_active);
		$criteria->compare('vrt_created_date',$this->vrt_created_date,true);
		$criteria->compare('vrt_modified_date',$this->vrt_modified_date,true);
		$criteria->compare('vrt_created_by',$this->vrt_created_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VehicleRegionTaxPref the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
    public static function saveVehicleTaxPref()
    {
        $sql = "SELECT b.stt_id as destination_id, c.stt_id as source_id
                FROM states b, states c 
                WHERE b.`stt_active` = '1' AND b.stt_country_id =99 AND c.`stt_active` = '1' 
                AND c.stt_country_id =99 AND b.stt_id!=c.stt_id";
        
        $resultset	 = DBUtil::queryAll($sql);
        $userInfo	 = UserInfo::getInstance();
        
        foreach ($resultset as $row)
        {
                $model                               = new VehicleRegionTaxPref();
                $model->vrt_source_type		 = 2;
                $model->vrt_source_id		 = $row['source_id'];
                $model->vrt_destination_type	 = 2;
                $model->vrt_destination_id           = $row['destination_id'];
                $model->vrt_created_by		 = $userInfo->userId;
                
                if (!$model->save())
                {
                        throw new Exception("Error adding state.");
                }
        }
        return true;
    }
}

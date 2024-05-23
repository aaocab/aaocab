<?php

/**
 * This is the model class for table "area_service_rule".
 *
 * The followings are the available columns in table 'area_service_rule':
 * @property integer $asr_id
 * @property integer $asr_source_city
 * @property integer $asr_destination_city
 * @property integer $asr_source_zone
 * @property integer $asr_destination_zone
 * @property integer $asr_source_state
 * @property integer $asr_destination_state
 * @property integer $asr_region
 * @property integer $asr_scc_id
 * @property integer $asr_vct_id
 * @property integer $asr_sct_id
 * @property integer $asr_vht_id
 * @property integer $asr_inc_flag
 * @property integer $asr_active
 * @property string $asr_create_date
 * @property string $asr_modified_date
 */
class AreaServiceRule extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'area_service_rule';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('asr_create_date, asr_modified_date', 'required'),
			array('asr_source_city, asr_destination_city, asr_source_zone, asr_destination_zone, asr_source_state, asr_destination_state, asr_region, asr_scc_id, asr_vct_id, asr_sct_id, asr_vht_id, asr_inc_flag, asr_active', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('asr_id, asr_source_city, asr_destination_city, asr_source_zone, asr_destination_zone, asr_source_state, asr_destination_state, asr_region, asr_scc_id, asr_vct_id, asr_sct_id, asr_vht_id, asr_inc_flag, asr_active, asr_create_date, asr_modified_date', 'safe', 'on'=>'search'),
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
			'asr_id' => 'Asr',
			'asr_source_city' => 'Asr Source City',
			'asr_destination_city' => 'Asr Destination City',
			'asr_source_zone' => 'Asr Source Zone',
			'asr_destination_zone' => 'Asr Destination Zone',
			'asr_source_state' => 'Asr Source State',
			'asr_destination_state' => 'Asr Destination State',
			'asr_region' => 'Asr Region',
			'asr_scc_id' => 'Asr Scc',
			'asr_vct_id' => 'Asr Vct',
			'asr_sct_id' => 'Asr Sct',
			'asr_vht_id' => 'Asr Vht',
			'asr_inc_flag' => 'Asr Inc Flag',
			'asr_active' => 'Asr Active',
			'asr_create_date' => 'Asr Create Date',
			'asr_modified_date' => 'Asr Modified Date',
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

		$criteria->compare('asr_id',$this->asr_id);
		$criteria->compare('asr_source_city',$this->asr_source_city);
		$criteria->compare('asr_destination_city',$this->asr_destination_city);
		$criteria->compare('asr_source_zone',$this->asr_source_zone);
		$criteria->compare('asr_destination_zone',$this->asr_destination_zone);
		$criteria->compare('asr_source_state',$this->asr_source_state);
		$criteria->compare('asr_destination_state',$this->asr_destination_state);
		$criteria->compare('asr_region',$this->asr_region);
		$criteria->compare('asr_scc_id',$this->asr_scc_id);
		$criteria->compare('asr_vct_id',$this->asr_vct_id);
		$criteria->compare('asr_sct_id',$this->asr_sct_id);
		$criteria->compare('asr_vht_id',$this->asr_vht_id);
		$criteria->compare('asr_inc_flag',$this->asr_inc_flag);
		$criteria->compare('asr_active',$this->asr_active);
		$criteria->compare('asr_create_date',$this->asr_create_date,true);
		$criteria->compare('asr_modified_date',$this->asr_modified_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AreaServiceRule the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

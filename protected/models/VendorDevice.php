<?php

/**
 * This is the model class for table "vendor_device".
 *
 * The followings are the available columns in table 'vendor_device':
 * @property integer $vdc_id
 * @property integer $vdc_vnd_id
 * @property string $vdc_device
 * @property string $vdc_os_version
 * @property string $vdc_device_uuid
 * @property string $vdc_apk_version
 * @property string $vdc_mac_address
 * @property string $vdc_serial
 *
 * The followings are the available model relations:
 * @property Vendors $vdcVnd
 */
class VendorDevice extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vendor_device';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vdc_vnd_id', 'required'),
			array('vdc_vnd_id', 'numerical', 'integerOnly'=>true),
			array('vdc_device', 'length', 'max'=>200),
			array('vdc_os_version, vdc_device_uuid, vdc_apk_version, vdc_mac_address, vdc_serial', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vdc_id, vdc_vnd_id, vdc_device, vdc_os_version, vdc_device_uuid, vdc_apk_version, vdc_mac_address, vdc_serial', 'safe', 'on'=>'search'),
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
			'vdcVnd' => array(self::BELONGS_TO, 'Vendors', 'vdc_vnd_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'vdc_id' => 'Vdc',
			'vdc_vnd_id' => 'Vdc Vnd',
			'vdc_device' => 'Vdc Device',
			'vdc_os_version' => 'Vdc Os Version',
			'vdc_device_uuid' => 'Vdc Device Uuid',
			'vdc_apk_version' => 'Vdc Apk Version',
			'vdc_mac_address' => 'Vdc Mac Address',
			'vdc_serial' => 'Vdc Serial',
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

		$criteria->compare('vdc_id',$this->vdc_id);
		$criteria->compare('vdc_vnd_id',$this->vdc_vnd_id);
		$criteria->compare('vdc_device',$this->vdc_device,true);
		$criteria->compare('vdc_os_version',$this->vdc_os_version,true);
		$criteria->compare('vdc_device_uuid',$this->vdc_device_uuid,true);
		$criteria->compare('vdc_apk_version',$this->vdc_apk_version,true);
		$criteria->compare('vdc_mac_address',$this->vdc_mac_address,true);
		$criteria->compare('vdc_serial',$this->vdc_serial,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VendorDevice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

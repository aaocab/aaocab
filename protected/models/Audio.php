<?php

/**
 * This is the model class for table "audio".
 *
 * The followings are the available columns in table 'audio':
 * @property integer $ado_id
 * @property integer $ado_type
 * @property integer $ado_type_id
 * @property string $ado_audio_path
 * @property integer $ado_called_by
 * @property string $ado_create_date
 * @property integer $ado_active
 */
class Audio extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'audio';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ado_type, ado_type_id, ado_audio_path, ado_called_by, ado_create_date, ado_active', 'required'),
			array('ado_type, ado_type_id, ado_called_by, ado_active', 'numerical', 'integerOnly'=>true),
			array('ado_audio_path', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ado_id, ado_type, ado_type_id, ado_audio_path, ado_called_by, ado_create_date, ado_active', 'safe', 'on'=>'search'),
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
			'ado_id' => 'Ado',
			'ado_type' => 'callType:1.lead 2.existbooking,3.vendor,4.driver',
			'ado_type_id' => 'leadID/bookingID',
			'ado_audio_path' => 'Ado Audio Path',
			'ado_called_by' => 'caller/csr-adminID',
			'ado_create_date' => 'Ado Create Date',
			'ado_active' => 'Ado Active',
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

		$criteria->compare('ado_id',$this->ado_id);
		$criteria->compare('ado_type',$this->ado_type);
		$criteria->compare('ado_type_id',$this->ado_type_id);
		$criteria->compare('ado_audio_path',$this->ado_audio_path,true);
		$criteria->compare('ado_called_by',$this->ado_called_by);
		$criteria->compare('ado_create_date',$this->ado_create_date,true);
		$criteria->compare('ado_active',$this->ado_active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Audio the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

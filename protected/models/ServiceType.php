<?php

/**
 * This is the model class for table "service_type".
 *
 * The followings are the available columns in table 'service_type':
 * @property integer $sct_id
 * @property string $sct_label
 * @property string $sct_desc
 * @property integer $sct_active
 * @property string $sct_create_date
 * @property string $sct_modified_date
 */
class ServiceType extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'service_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sct_active, sct_create_date, sct_modified_date', 'required'),
			array('sct_active', 'numerical', 'integerOnly'=>true),
			array('sct_label', 'length', 'max'=>100),
			array('sct_desc', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('sct_id, sct_label, sct_desc, sct_active, sct_create_date, sct_modified_date', 'safe', 'on'=>'search'),
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
			'sct_id' => 'Sct',
			'sct_label' => 'Sct Label',
			'sct_desc' => 'Sct Desc',
			'sct_active' => 'Sct Active',
			'sct_create_date' => 'Sct Create Date',
			'sct_modified_date' => 'Sct Modified Date',
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

		$criteria->compare('sct_id',$this->sct_id);
		$criteria->compare('sct_label',$this->sct_label,true);
		$criteria->compare('sct_desc',$this->sct_desc,true);
		$criteria->compare('sct_active',$this->sct_active);
		$criteria->compare('sct_create_date',$this->sct_create_date,true);
		$criteria->compare('sct_modified_date',$this->sct_modified_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ServiceType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

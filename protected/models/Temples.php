<?php

/**
 * This is the model class for table "temples".
 *
 * The followings are the available columns in table 'temples':
 * @property integer $tmpl_id
 * @property integer $tmpl_city_id
 * @property string $tmpl_code
 * @property string $tmpl_name
 * @property string $tmpl_photo_1
 * @property string $tmpl_photo_2
 * @property string $tmpl_photo_3
 * @property string $tmpl_photo_4
 * @property integer $tmpl_active
 * @property string $tmpl_created
 * @property string $tmpl_modified
 *
 * The followings are the available model relations:
 * @property Cities $tmplCity
 */
class Temples extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'temples';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tmpl_city_id, tmpl_code, tmpl_name, tmpl_created, tmpl_modified', 'required'),
			array('tmpl_city_id, tmpl_active', 'numerical', 'integerOnly'=>true),
			array('tmpl_code', 'length', 'max'=>50),
			array('tmpl_name, tmpl_photo_1, tmpl_photo_2, tmpl_photo_3, tmpl_photo_4', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('tmpl_id, tmpl_city_id, tmpl_code, tmpl_name, tmpl_photo_1, tmpl_photo_2, tmpl_photo_3, tmpl_photo_4, tmpl_active, tmpl_created, tmpl_modified', 'safe', 'on'=>'search'),
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
			'tmplCity' => array(self::BELONGS_TO, 'Cities', 'tmpl_city_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'tmpl_id' => 'Tmpl',
			'tmpl_city_id' => 'Tmpl City',
			'tmpl_code' => 'Tmpl Code',
			'tmpl_name' => 'Tmpl Name',
			'tmpl_photo_1' => 'Tmpl Photo 1',
			'tmpl_photo_2' => 'Tmpl Photo 2',
			'tmpl_photo_3' => 'Tmpl Photo 3',
			'tmpl_photo_4' => 'Tmpl Photo 4',
			'tmpl_active' => 'Tmpl Active',
			'tmpl_created' => 'Tmpl Created',
			'tmpl_modified' => 'Tmpl Modified',
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

		$criteria->compare('tmpl_id',$this->tmpl_id);
		$criteria->compare('tmpl_city_id',$this->tmpl_city_id);
		$criteria->compare('tmpl_code',$this->tmpl_code,true);
		$criteria->compare('tmpl_name',$this->tmpl_name,true);
		$criteria->compare('tmpl_photo_1',$this->tmpl_photo_1,true);
		$criteria->compare('tmpl_photo_2',$this->tmpl_photo_2,true);
		$criteria->compare('tmpl_photo_3',$this->tmpl_photo_3,true);
		$criteria->compare('tmpl_photo_4',$this->tmpl_photo_4,true);
		$criteria->compare('tmpl_active',$this->tmpl_active);
		$criteria->compare('tmpl_created',$this->tmpl_created,true);
		$criteria->compare('tmpl_modified',$this->tmpl_modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Temples the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

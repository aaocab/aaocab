<?php

/**
 * This is the model class for table "tracking".
 *
 * The followings are the available columns in table 'tracking':
 * @property integer $tkg_id
 * @property integer $tkg_aff_id
 * @property string $tkg_referrer
 * @property integer $tkg_ref_type
 * @property integer $tkg_count
 * @property string $tkg_created_at
 */
class Tracking extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tracking';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tkg_aff_id, tkg_ref_type', 'required'),
			array('tkg_aff_id, tkg_ref_type, tkg_count', 'numerical', 'integerOnly' => true),
			array('tkg_referrer', 'length', 'max' => 1000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('tkg_id, tkg_aff_id, tkg_referrer, tkg_ref_type, tkg_count, tkg_desc', 'safe'),
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
			'tkg_id'		 => 'Tkg',
			'tkg_aff_id'	 => 'Tkg Aff',
			'tkg_referrer'	 => 'Tkg Referrer',
			'tkg_ref_type'	 => 'Tkg Ref Type',
			'tkg_count'		 => 'Tkg Count',
			'tkg_created_at' => 'Tkg Created At',
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

		$criteria->compare('tkg_id', $this->tkg_id);
		$criteria->compare('tkg_aff_id', $this->tkg_aff_id);
		$criteria->compare('tkg_referrer', $this->tkg_referrer, true);
		$criteria->compare('tkg_ref_type', $this->tkg_ref_type);
		$criteria->compare('tkg_count', $this->tkg_count);
		$criteria->compare('tkg_created_at', $this->tkg_created_at, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tracking the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function add($data)
	{
		$model				 = new Tracking();
		$model->attributes	 = $data;
		$success			 = $model->save();
		return $success;
	}

}

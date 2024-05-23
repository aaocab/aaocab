<?php

/**
 * This is the model class for table "driver_merged".
 *
 * The followings are the available columns in table 'driver_merged':
 * @property integer $dmg_id
 * @property integer $dmg_drv_id
 * @property integer $dmg_drv_merged_id
 * @property integer $dmg_added_by
 * @property string $dmg_created
 */
class DriverMerged extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'driver_merged';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dmg_drv_id, dmg_drv_merged_id, dmg_added_by', 'required'),
			array('dmg_drv_id, dmg_drv_merged_id, dmg_added_by', 'numerical', 'integerOnly' => true),
			array('dmg_created', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('dmg_id, dmg_drv_id, dmg_drv_merged_id, dmg_added_by, dmg_created', 'safe', 'on' => 'search'),
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
			'dmg_id'			 => 'Dmg',
			'dmg_drv_id'		 => 'Dmg Drv',
			'dmg_drv_merged_id'	 => 'Dmg Drv Merged',
			'dmg_added_by'		 => 'Dmg Added By',
			'dmg_created'		 => 'Dmg Created',
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

		$criteria->compare('dmg_id', $this->dmg_id);
		$criteria->compare('dmg_drv_id', $this->dmg_drv_id);
		$criteria->compare('dmg_drv_merged_id', $this->dmg_drv_merged_id);
		$criteria->compare('dmg_added_by', $this->dmg_added_by);
		$criteria->compare('dmg_created', $this->dmg_created, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DriverMerged the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function addMergedData($newDriver, $oldDriver, $user)
	{
		$model						 = new DriverMerged();
		$model->dmg_drv_id			 = $newDriver;
		$model->dmg_drv_merged_id	 = $oldDriver;
		$model->dmg_added_by		 = $user;
		$success					 = $model->save();
		return $success;
	}

}

<?php

/**
 * This is the model class for table "merged_cabs".
 *
 * The followings are the available columns in table 'merged_cabs':
 * @property integer $mcab_id
 * @property integer $mcab_active_cab_id
 * @property integer $mcab_dup_cab_id
 * @property string $mcab_vehicle_name
 */
class MergedCabs extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'merged_cabs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('mcab_active_cab_id, mcab_dup_cab_id', 'required'),
			array('mcab_active_cab_id, mcab_dup_cab_id', 'numerical', 'integerOnly' => true),
			array('mcab_vehicle_name', 'length', 'max' => 100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('mcab_id, mcab_active_cab_id, mcab_dup_cab_id, mcab_vehicle_name', 'safe', 'on' => 'search'),
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
			'mcab_id'			 => 'Mcab',
			'mcab_active_cab_id' => 'Mcab Active Cab',
			'mcab_dup_cab_id'	 => 'Mcab Dup Cab',
			'mcab_vehicle_name'	 => 'Mcab Vehicle Name',
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

		$criteria->compare('mcab_id', $this->mcab_id);
		$criteria->compare('mcab_active_cab_id', $this->mcab_active_cab_id);
		$criteria->compare('mcab_dup_cab_id', $this->mcab_dup_cab_id);
		$criteria->compare('mcab_vehicle_name', $this->mcab_vehicle_name, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MergedCabs the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function saveData($activeId, $inactiveId, $vName)
	{
		if (empty($activeId) && empty($inactiveId))
		{
			throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
		}
		$model						 = new MergedCabs();
		$model->mcab_active_cab_id	 = $activeId;
		$model->mcab_dup_cab_id		 = $inactiveId;
		$model->mcab_vehicle_name	 = $vName;
		if (!$model->save())
		{
			throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_FAILED);
		}
	}

}

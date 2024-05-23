<?php

/**
 * This is the model class for table "inventory_stats".
 *
 * The followings are the available columns in table 'inventory_stats':
 * @property integer $inv_stats_id
 * @property integer $inv_area_type
 * @property integer $inv_area_id
 * @property integer $inv_type
 * @property integer $inv_type_value
 * @property integer $inv_active_count
 * @property integer $inv_total_count
 */
class InventoryStats extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'inventory_stats';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('inv_area_type, inv_area_id, inv_type', 'required'),
			array('inv_area_type, inv_area_id, inv_type, inv_type_value, inv_active_count, inv_total_count', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('inv_stats_id, inv_area_type, inv_area_id, inv_type, inv_type_value, inv_active_count, inv_total_count', 'safe', 'on' => 'search'),
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
			'inv_stats_id'		 => 'Inv Stats',
			'inv_area_type'		 => 'Inv Area Type',
			'inv_area_id'		 => 'Inv Area',
			'inv_type'			 => 'Inv Type',
			'inv_type_value'	 => 'Inv Type Value',
			'inv_active_count'	 => 'Inv Active Count',
			'inv_total_count'	 => 'Inv Total Count',
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

		$criteria->compare('inv_stats_id', $this->inv_stats_id);
		$criteria->compare('inv_area_type', $this->inv_area_type);
		$criteria->compare('inv_area_id', $this->inv_area_id);
		$criteria->compare('inv_type', $this->inv_type);
		$criteria->compare('inv_type_value', $this->inv_type_value);
		$criteria->compare('inv_active_count', $this->inv_active_count);
		$criteria->compare('inv_total_count', $this->inv_total_count);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return InventoryStats the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 
	 * @param integer $areaType
	 * @param integer $areaId
	 * @param integer $type
	 * @param integer $typeValue
	 * @return InventoryStats $model
	 */
	public function findByParams($areaType, $areaId, $type, $typeValue = null)
	{
		if($typeValue == null)
		{
			$params	 = ['areaType' => $areaType, 'areaId' => $areaId, 'type' => $type];
			$model	 = self::model()->find('inv_area_type=:areaType AND inv_area_id=:areaId AND inv_type=:type  AND inv_type=:type', $params);
		}
		else
		{
			$params	 = ['areaType' => $areaType, 'areaId' => $areaId, 'type' => $type, 'typeValue' => $typeValue];
			$model	 = self::model()->find('inv_area_type=:areaType AND inv_area_id=:areaId AND inv_type=:type  AND inv_type=:type AND inv_type_value=:typeValue', $params);
		}
		return $model;
	}

	/** 
	 * 
	 * @param integer $areaType		| 1 => 'Zone', 2 => 'State', 3 => 'City'	
	 * @param integer $areaId
	 * @param integer $type			| 1 => vendor, 2 vehicle, 3=driver
	 * @param integer $typeValue
	 * @param integer $totalCount
	 * @param integer $activeCount
	 */
	public static function addInventory($areaType, $areaId, $type, $typeValue=null, $totalCount, $activeCount)
	{
		$model = self::model()->findByParams($areaType, $areaId, $type, $typeValue);
		if (!$model)
		{
			$model = new InventoryStats();
		}
		$model->inv_area_type	 = $areaType;
		$model->inv_area_id		 = (int) $areaId;
		$model->inv_type		 = $type;
		$model->inv_type_value	 = $typeValue;
		$model->inv_total_count	 = (int) $totalCount;
		$model->inv_active_count = (int) $activeCount;
		if(!$model->save())
		{
			throw new Exception(json_encode($model->getErrors()));
		}		
	}

}

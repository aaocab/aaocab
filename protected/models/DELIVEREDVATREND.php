<?php

/**
 * This is the model class for table "DELIVERED_VA_TREND".
 *
 * The followings are the available columns in table 'DELIVERED_VA_TREND':
 * @property string $id
 * @property string $rowIdentifier
 * @property integer $year
 * @property string $weekDate
 * @property integer $pickupDayOfWeek
 * @property integer $pickUpWeek
 * @property double $maxVendorAmount
 * @property double $minVendorAmount
 * @property double $avgVendorAmount
 * @property double $medVendorAmount
 * @property double $maxQuotedVendorAmount
 * @property double $minQuotedVendorAmount
 * @property double $avgQuotedVendorAmount
 * @property double $medQuotedVendorAmount
 * @property string $createDate
 * @property string $updateDate
 * @property integer $active
 */
class DELIVEREDVATREND extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'DELIVERED_VA_TREND';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('createDate, updateDate', 'required'),
			array('year, pickupDayOfWeek, pickUpWeek, active', 'numerical', 'integerOnly' => true),
			array('maxVendorAmount, minVendorAmount, avgVendorAmount, medVendorAmount', 'numerical'),
			array('rowIdentifier', 'length', 'max' => 20),
			array('weekDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, rowIdentifier, year, weekDate, pickupDayOfWeek, pickUpWeek, maxVendorAmount, minVendorAmount, avgVendorAmount, medVendorAmount, createDate, updateDate, active,maxQuotedVendorAmount,minQuotedVendorAmount,avgQuotedVendorAmount,medQuotedVendorAmount', 'safe', 'on' => 'search'),
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
			'id'				 => 'ID',
			'rowIdentifier'		 => 'Row Identifier',
			'year'				 => 'Year',
			'weekDate'			 => 'Week Date',
			'pickupDayOfWeek'	 => 'Pickup Day Of Week',
			'pickUpWeek'		 => 'Pick Up Week',
			'maxVendorAmount'	 => 'Max Vendor Amount',
			'minVendorAmount'	 => 'Min Vendor Amount',
			'avgVendorAmount'	 => 'Avg Vendor Amount',
			'medVendorAmount'	 => 'Med Vendor Amount',
			'createDate'		 => 'Create Date',
			'updateDate'		 => 'Update Date',
			'active'			 => 'Active',
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

		$criteria->compare('id', $this->id, true);
		$criteria->compare('rowIdentifier', $this->rowIdentifier, true);
		$criteria->compare('year', $this->year);
		$criteria->compare('weekDate', $this->weekDate, true);
		$criteria->compare('pickupDayOfWeek', $this->pickupDayOfWeek);
		$criteria->compare('pickUpWeek', $this->pickUpWeek);
		$criteria->compare('maxVendorAmount', $this->maxVendorAmount);
		$criteria->compare('minVendorAmount', $this->minVendorAmount);
		$criteria->compare('avgVendorAmount', $this->avgVendorAmount);
		$criteria->compare('medVendorAmount', $this->medVendorAmount);
		$criteria->compare('createDate', $this->createDate, true);
		$criteria->compare('updateDate', $this->updateDate, true);
		$criteria->compare('active', $this->active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DELIVEREDVATREND the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getAvgVendorGoingRate($rowIdentifier)
	{
		$sql = "SELECT ROUND((((SUM(IFNULL(DELIVERED_VA_TREND.avgVendorAmount,0))/COUNT(1)+SUM(IFNULL(DELIVERED_VA_TREND.medVendorAmount,0))/COUNT(1)))/2),2) AS avgGoingRate
			    FROM `DELIVERED_VA_TREND` WHERE 1 AND `rowIdentifier`=:rowIdentifier";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), ['rowIdentifier' => $rowIdentifier]);
	}

	public static function getAvgQuotedVendorGoingRate($rowIdentifier)
	{
		$sql = "SELECT ROUND((((SUM(IFNULL(DELIVERED_VA_TREND.avgQuotedVendorAmount,0))/COUNT(1)+SUM(IFNULL(DELIVERED_VA_TREND.medQuotedVendorAmount,0))/COUNT(1)))/2),2) AS avgQuotedGoingRate
			    FROM `DELIVERED_VA_TREND` WHERE 1 AND `rowIdentifier`=:rowIdentifier";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), ['rowIdentifier' => $rowIdentifier]);
	}

}

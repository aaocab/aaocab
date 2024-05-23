<?php

/**
 * This is the model class for table "report_category".
 *
 * The followings are the available columns in table 'report_category':
 * @property integer $rpc_id
 * @property string $rpc_name
 * @property string $rpc_cat_icons
 * @property string $rpc_categories
 * @property string $rpc_pareant_categories
 * @property string $rpc_create_date
 * @property integer $rpc_status
 * @property integer $rpc_sort
 */
class ReportCategory extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'report_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rpc_status,rpc_sort', 'numerical', 'integerOnly' => true),
			array('rpc_name, rpc_categories, rpc_pareant_categories', 'length', 'max' => 255),
			array('rpc_create_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rpc_id, rpc_name, rpc_categories, rpc_pareant_categories, rpc_create_date, rpc_status,rpc_sort,rpc_cat_icons', 'safe', 'on' => 'search'),
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
			'rpc_id'			 => 'Category Id',
			'rpc_name'			 => 'Category Name',
			'rpc_sort'			 => 'Category Sort',
			'rpc_create_date'	 => 'Category Create Date',
			'rpc_status'		 => 'Category Status',
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

		$criteria->compare('rpc_id', $this->rpc_id);
		$criteria->compare('rpc_name', $this->rpc_name, true);
		$criteria->compare('rpc_sort', $this->rpc_sort, true);
		$criteria->compare('rpc_create_date', $this->rpc_create_date, true);
		$criteria->compare('rpc_status', $this->rpc_status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ReportCategory the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getAllCategory()
	{
		$sql = "SELECT rpc_name,rpc_id,rpc_cat_icons FROM `report_category` WHERE 1 AND rpc_status=1 ORDER BY rpc_sort ASC";
		return DBUtil::query($sql, DBUtil::SDB(), [], true, 60 * 60 * 24 * 10, CacheDependency::Type_Report_DashBoard);
	}

}

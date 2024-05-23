<?php

/**
 * This is the model class for table "calendar".
 *
 * The followings are the available columns in table 'calendar':
 * @property string $cln_date
 * @property integer $cln_category
 * @property integer $cln_pre_assignment
 * @property string $cln_remarks
 */
class Calendar extends CActiveRecord
{

	public $catList = [
		1	 => 'Festival',
		2	 => 'New Year'
	];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'calendar';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cln_date', 'required'),
			array('cln_category, cln_pre_assignment', 'numerical', 'integerOnly' => true),
			array('cln_remarks', 'length', 'max' => 255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cln_date, cln_category, cln_pre_assignment, cln_remarks', 'safe', 'on' => 'search'),
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
			'cln_date'			 => 'Date',
			'cln_category'		 => 'Category',
			'cln_pre_assignment' => 'Pre Assignment',
			'cln_remarks'		 => 'Remarks',
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

		$criteria->compare('cln_date', $this->cln_date, true);
		$criteria->compare('cln_category', $this->cln_category);
		$criteria->compare('cln_pre_assignment', $this->cln_pre_assignment);
		$criteria->compare('cln_remarks', $this->cln_remarks, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Calendar the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getList()
	{

		$sql			 = "SELECT * FROM calendar";
		$defaultOrder	 = "cln_date ASC";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 =>
			['attributes'	 =>
				['cln_date', 'cln_category', 'cln_pre_assignment', 'cln_remarks'],
				'defaultOrder'	 => $defaultOrder],
			'pagination'	 => ['pageSize' => 20],
		]);
		return $dataprovider;
	}

	public function getPreAssignmentbyDate($date)
	{
		$sql	 = "SELECT cln_pre_assignment FROM calendar where cln_date = '$date'";
		$res	 = DBUtil::command($sql)->queryScalar();
		$result	 = $res | 0;
		return $result;
	}

}

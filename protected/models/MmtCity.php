<?php

/**
 * This is the model class for table "mmt_city".
 *
 * The followings are the available columns in table 'mmt_city':
 * @property integer $mmt_cty_id
 * @property integer $mmt_ref_id
 * @property string $mmt_cty_code
 * @property string $mmt_cty_name
 * @property string $mmt_latitude
 * @property string $mmt_longitude
 */
class MmtCity extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'mmt_city';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mmt_ref_id', 'numerical', 'integerOnly' => true),
			array('mmt_cty_code', 'length', 'max' => 16),
			array('mmt_cty_name', 'length', 'max' => 35),
			array('mmt_latitude, mmt_longitude', 'length', 'max' => 500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('mmt_cty_id, mmt_ref_id, mmt_cty_code, mmt_cty_name, mmt_latitude, mmt_longitude', 'safe', 'on' => 'search'),
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
			'mmt_cty_id'	 => 'Mmt Cty',
			'mmt_ref_id'	 => 'Mmt Ref',
			'mmt_cty_code'	 => 'Mmt Cty Code',
			'mmt_cty_name'	 => 'Mmt Cty Name',
			'mmt_latitude'	 => 'Mmt Latitude',
			'mmt_longitude'	 => 'Mmt Longitude',
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

		$criteria->compare('mmt_cty_id', $this->mmt_cty_id);
		$criteria->compare('mmt_ref_id', $this->mmt_ref_id);
		$criteria->compare('mmt_cty_code', $this->mmt_cty_code, true);
		$criteria->compare('mmt_cty_name', $this->mmt_cty_name, true);
		$criteria->compare('mmt_latitude', $this->mmt_latitude, true);
		$criteria->compare('mmt_longitude', $this->mmt_longitude, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MmtCity the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getCityId($mmtCode)
	{
		$sql		 = "SELECT mmt_ref_id FROM mmt_city WHERE mmt_cty_code='{$mmtCode}'";
		$mmt_ref_id	 = DBUtil::command($sql)->queryScalar();
		return $mmt_ref_id;
	}

	public function getMmtCode($id)
	{
		$sql			 = "SELECT mmt_cty_code FROM mmt_city WHERE mmt_ref_id='{$id}'";
		$mmt_cty_code	 = DBUtil::command($sql)->queryScalar();
		return $mmt_cty_code;
	}

}

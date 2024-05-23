<?php

/**
 * This is the model class for table "vendors_summary".
 *
 * The followings are the available columns in table 'vendors_summary':
 * @property integer $vsm_id
 * @property integer $vsm_vnd_id
 * @property double $vsm_avg10
 * @property double $vsm_avg30
 * @property integer $vsm_active
 * @property string $vsm_modified

 */
class VendorsSummary extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vendors_summary';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vsm_vnd_id, vsm_active', 'numerical', 'integerOnly' => true),
			array('vsm_avg10, vsm_avg30', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vsm_id, vsm_vnd_id, vsm_avg10, vsm_avg30, vsm_active, vsm_modified', 'safe', 'on' => 'search'),
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
			'vsm_id'	 => 'Vsm',
			'vsm_vnd_id' => 'Vsm Vnd',
			'vsm_avg10'	 => 'Vsm Avg10',
			'vsm_avg30'	 => 'Vsm Avg30',
			'vsm_active' => 'Vsm Active',
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

		$criteria->compare('vsm_id', $this->vsm_id);
		$criteria->compare('vsm_vnd_id', $this->vsm_vnd_id);
		$criteria->compare('vsm_avg10', $this->vsm_avg10);
		$criteria->compare('vsm_avg30', $this->vsm_avg30);
		$criteria->compare('vsm_active', $this->vsm_active);
		$criteria->compare('vsm_modified', $this->vsm_modified, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VendorsSummary the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getByVendor($vnd_id)
	{
		return $this->find('vsm_vnd_id=:vnd_id', ['vnd_id' => $vnd_id]);
	}

}

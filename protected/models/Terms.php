<?php

/**
 * This is the model class for table "terms".
 *
 * The followings are the available columns in table 'terms':
 * @property integer $tnc_id
 * @property string $tnc_text
 * @property integer $tnc_cat
 * @property string $tnc_version
 * @property string $tnc_updated_at
 * @property string $tnc_created_at
 * @property integer $tnc_active
 */
class Terms extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'terms';
	}

	public function defaultScope()
	{
		$arr = array(
			'condition' => "tnc_active=1",
		);
		return $arr;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tnc_cat,tnc_text,tnc_active', 'required'),
			array('tnc_id, tnc_cat, tnc_active', 'numerical', 'integerOnly' => true),
			array('tnc_version', 'length', 'max' => 100),
			array('tnc_text, tnc_updated_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('tnc_id, tnc_text, tnc_cat, tnc_version, tnc_updated_at, tnc_created_at, tnc_active', 'safe', 'on' => 'search'),
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
			'tnc_id'		 => 'Tnc',
			'tnc_text'		 => 'Tnc Text',
			'tnc_cat'		 => 'Tnc Cat',
			'tnc_version'	 => 'Tnc Version',
			'tnc_updated_at' => 'Tnc Updated At',
			'tnc_created_at' => 'Tnc Created At',
			'tnc_active'	 => 'Tnc Active',
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

		$criteria->compare('tnc_id', $this->tnc_id);
		$criteria->compare('tnc_text', $this->tnc_text, true);
		$criteria->compare('tnc_cat', $this->tnc_cat);
		$criteria->compare('tnc_version', $this->tnc_version, true);
		$criteria->compare('tnc_updated_at', $this->tnc_updated_at, true);
		$criteria->compare('tnc_created_at', $this->tnc_created_at, true);
		$criteria->compare('tnc_active', $this->tnc_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Terms the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function termsListing()
	{
		$criteria			 = new CDbCriteria;
		$criteria->together	 = TRUE;
		$dataProvider		 = new CActiveDataProvider($this->together(), array('criteria' => $criteria));
		return $dataProvider;
	}

	public function getText($cat)
	{
		$criteria			 = new CDbCriteria;
		$criteria->compare('tnc_cat', $cat);
		$criteria->order	 = 'tnc_updated_at  DESC';
		$criteria->limit	 = 1;
		$criteria->together	 = TRUE;
		return Terms::model()->find($criteria);
	}

}

<?php

/**
 * This is the model class for table "faqs".
 *
 * The followings are the available columns in table 'faqs':
 * @property integer $faq_id
 * @property integer $faq_type
 * @property string $faq_question
 * @property string $faq_answer
 * @property integer $faq_active
 */
class Faqs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'faqs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('faq_type', 'required'),
			array('faq_type, faq_active', 'numerical', 'integerOnly'=>true),
			array('faq_question', 'length', 'max'=>1024),
			array('faq_answer', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('faq_id, faq_type, faq_question, faq_answer, faq_active', 'safe', 'on'=>'search'),
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
			'faq_id' => 'Faq',
			'faq_type' => '1=>book-taxi,2=>car-rental',
			'faq_question' => 'Faq Question',
			'faq_answer' => 'Faq Answer',
			'faq_active' => 'Faq Active',
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

		$criteria=new CDbCriteria;

		$criteria->compare('faq_id',$this->faq_id);
		$criteria->compare('faq_type',$this->faq_type);
		$criteria->compare('faq_question',$this->faq_question,true);
		$criteria->compare('faq_answer',$this->faq_answer,true);
		$criteria->compare('faq_active',$this->faq_active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Faqs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public static function getDetails($faqtype)
	{
		$params = ['faqtype' => $faqtype];
		$sql = "SELECT * FROM faqs WHERE faq_type =:faqtype AND faq_active = 1";
		return DBUtil::query($sql, DBUtil::SDB(), $params);
	}
}

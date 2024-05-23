<?php

/**
 * This is the model class for table "bot_faq".
 *
 * The followings are the available columns in table 'bot_faq':
 * @property integer $bof_id
 * @property string $bof_category
 * @property string $bof_keywords
 * @property string $bof_question
 * @property string $bof_answer
 */
class BotFaq extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'bot_faq';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bof_category, bof_keywords, bof_question, bof_answer', 'required'),
			array('bof_category', 'length', 'max' => 30),
			array('bof_keywords', 'length', 'max' => 60),
			array('bof_question', 'length', 'max' => 255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bof_id, bof_category, bof_keywords, bof_question, bof_answer', 'safe', 'on' => 'search'),
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
			'bof_id'		 => 'Bof',
			'bof_category'	 => 'Bof Category',
			'bof_keywords'	 => 'Bof Keywords',
			'bof_question'	 => 'Bof Question',
			'bof_answer'	 => 'Bof Answer',
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

		$criteria->compare('bof_id', $this->bof_id);
		$criteria->compare('bof_category', $this->bof_category, true);
		$criteria->compare('bof_keywords', $this->bof_keywords, true);
		$criteria->compare('bof_question', $this->bof_question, true);
		$criteria->compare('bof_answer', $this->bof_answer, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BotFaq the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getKeyBasedData($key)
	{
		$key	 = ($key == null || $key == "") ? "" : $key;
		$params	 = array("key" => $key);
		$sql	 = "SELECT
               bof_id,
               MATCH(bof_keywords) AGAINST (:key IN NATURAL LANGUAGE MODE) AS score,
               bof_question 
               FROM
               bot_faq having score > 0 ORDER BY score DESC";
		return DBUtil::query($sql, DBUtil::SDB(), $params);
	}

	public static function getColumnValue($columnName, $id)
	{
		$sql = "SELECT {$columnName} FROM bot_faq WHERE bof_id=:id";
		return DBUtil::command($sql, DBUtil::SDB())->queryScalar(["id" => $id]);
	}

	public static function getCategory($limit = '')
	{
		$sql = "SELECT
               bof_category,bof_id 
               FROM
               bot_faq  WHERE bof_category != '' GROUP BY bof_category ORDER BY bof_id ASC";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public static function getCategoryData($category, $limit = '')
	{
		if ($category == 'General questions')
		{
			$cond = " (bof_category LIKE '%$category%' OR bof_category = '' OR bof_category IS NULL)";
		}
		else
		{
			$cond = " (bof_category LIKE '%$category%')";
		}
		
		if($limit != '')
		{
			$catLimit = "LIMIT {$limit}";
		}
		$sql = "SELECT
               bof_id,bof_category,bof_question,bof_answer 
               FROM
               bot_faq  WHERE $cond AND bof_active = 1 ORDER BY bof_id ASC $catLimit";
		return DBUtil::query($sql, DBUtil::SDB());
	}

}

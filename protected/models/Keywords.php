<?php

/**
 * This is the model class for table "keywords".
 *
 * The followings are the available columns in table 'keywords':
 * @property integer $keyword_id
 * @property string $keyword_name
 * @property integer $keyword_active
 */
class Keywords extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'keywords';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('keyword_active', 'numerical', 'integerOnly' => true),
			array('keyword_name', 'length', 'max' => 255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('keyword_id, keyword_name, keyword_active', 'safe', 'on' => 'search'),
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
			'keyword_id'	 => 'Keyword',
			'keyword_name'	 => 'Keyword Name',
			'keyword_active' => 'Keyword Active',
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

		$criteria->compare('keyword_id', $this->keyword_id);
		$criteria->compare('keyword_name', $this->keyword_name, true);
		$criteria->compare('keyword_active', $this->keyword_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Keywords the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * This model function is used for validating the keywords and
	 * adding the new keywords in the keywords table
	 * @param type $keywordsArray
	 */
	public static function validateAndAddKeywords($keywordsArray)
	{
		$keyword_list = [];
		$keyword_chk = $this -> getKeywordList();
		$RecordType	 = array_diff(explode(",", $keywordsArray), $keyword_chk);
		
		//Saving the keywords details which are new and missing in the keywords list
		foreach ($RecordType as $val)
		{
			$modelkeyword = new Keywords();

			$modelkeyword -> keyword_name	 = $val;
			$modelkeyword -> save();
		}
	}

	public function getKeywordList()
	{
		$rows	 = array();
		$sql	 = "SELECT keyword_id, keyword_name FROM keywords WHERE keyword_active = 1";
		$rows	 = DBUtil::queryAll($sql);
		foreach ($rows as $row)
		{
			$arrType[] = $row['keyword_name'];
		}
		return $arrType;
	}

	public function getKeyList()
	{
//        $sql = "SELECT keyword_id, keyword_name FROM keywords WHERE keyword_active = 1";
//        $recordset = DBUtil::queryAll($sql);
//        return $recordset;

		$criteria			 = new CDbCriteria();
		$criteria->select	 = "keyword_id, keyword_name";
		$criteria->compare('keyword_active', 1);
		$criteria->order	 = "keyword_name";
		$comments			 = Keywords::model()->findAll($criteria);
		return $comments;
	}
	
	//+Code Block: START
	/**
	 * This model function returns the keyword ids based on keyword names
	 * @param [array] $keywordsArray
	 * @return [string] $dbIdList
	 */
	public static function getKeyWordIdList($keywordsArray)
	{
		//Adding the keyword list ids in the routes table
		$arrKeywordDetails = explode(",", $keywordsArray);
		foreach ($arrKeywordDetails as $val)
		{
			$rut_keywords = $this ->getKeywordIdsByName($val);
			array_push($keyword_list, $rut_keywords[0]);
		}

		$keywordList = implode(',', $keyword_list);
		$dbIdList = str_replace(' ', '', $keywordList);

		return $dbIdList;
	}
	//-Code Block: END

	public function getKeywordIdsByName($keywordName)
	{
		$sql		 = "SELECT keyword_id FROM keywords WHERE keyword_name = '" . $keywordName . "'";
		$recordset	 = DBUtil::queryAll($sql);
		foreach ($recordset as $recordset)
		{
			$arrType[] = $recordset['keyword_id'];
		}
		return $arrType;
	}

}

<?php

/**
 * This is the model class for table "followup_type".
 *
 * The followings are the available columns in table 'followup_type':
 * @property integer $follow_type_id
 * @property string $follow_type
 * @property integer $follow_type_duration
 * @property string $follow_type_duration_txt
 * @property integer $follow_type_active
 */
class FollowupType extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'followup_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('follow_type_duration, follow_type_active', 'numerical', 'integerOnly' => true),
			array('follow_type, follow_type_duration_txt', 'length', 'max' => 100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('follow_type_id, follow_type, follow_type_duration, follow_type_duration_txt, follow_type_active', 'safe', 'on' => 'search'),
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
			'follow_type_id'			 => 'Follow Type',
			'follow_type'				 => 'Follow Type',
			'follow_type_duration'		 => 'Follow Type Duration',
			'follow_type_duration_txt'	 => 'Follow Type Duration Txt',
			'follow_type_active'		 => 'Follow Type Active',
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

		$criteria->compare('follow_type_id', $this->follow_type_id);
		$criteria->compare('follow_type', $this->follow_type, true);
		$criteria->compare('follow_type_duration', $this->follow_type_duration);
		$criteria->compare('follow_type_duration_txt', $this->follow_type_duration_txt, true);
		$criteria->compare('follow_type_active', $this->follow_type_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FollowupType the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getAll()
	{
		$criteria			 = new CDbCriteria;
		$criteria->select	 = "follow_type_id, follow_type";
		$criteria->compare('follow_type_active', 1);
		$criteria->order	 = "follow_type_id";
		return $this->findAll($criteria);
	}

	public function getList($all)
	{
		$followModels	 = FollowupType::model()->getAll();
		$arrSkill		 = array();
		foreach ($followModels as $follow)
		{
			$arrSkill[$follow->follow_type_id] = $follow->follow_type;
		}
		return $arrSkill;
	}

	public static function getJSON($all = '', $flag = NULL)
	{
        $arrJSON = [];
		if ($flag)
		{
			foreach ($all as $key => $val)
			{
				$arrJSON[] = array("id" => $key, "text" => $val);
			}
			$data = CJSON::encode($arrJSON);
			goto skipData;
		}
		$arrZone = $this->getList();
		if ($all != '')
		{
			$arrJSON[] = array_merge(array("id" => '0', "text" => "All"), $arrJSON);
		}
		foreach ($arrZone as $key => $val)
		{
			if ($val != '')
			{
				$arrJSON[] = array("id" => $key, "text" => $val);
			}
		}
		$data = CJSON::encode($arrJSON);
        skipData:
		return $data;
	}

}

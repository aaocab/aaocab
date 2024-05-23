<?php

/**
 * This is the model class for table "countries".
 *
 * The followings are the available columns in table 'countries':
 * @property integer $id
 * @property string $country_code
 * @property string $country_name
 * @property integer $country_phonecode
 * @property integer $active
 *
 * The followings are the available model relations:
 * @property States[] $states
 */
class Countries extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'countries';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('active', 'required'),
			array('country_phonecode, active', 'numerical', 'integerOnly' => true),
			array('country_code', 'length', 'max' => 4),
			array('country_name', 'length', 'max' => 100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, country_code, country_name, country_phonecode, active', 'safe', 'on' => 'search'),
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
			'states' => array(self::HAS_MANY, 'States', 'stt_country_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'				 => 'ID',
			'country_code'		 => 'Country Code',
			'country_name'		 => 'Country Name',
			'country_phonecode'	 => 'Country Phonecode',
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

		$criteria->compare('id', $this->id);
		$criteria->compare('country_code', $this->country_code, true);
		$criteria->compare('country_name', $this->country_name, true);
		$criteria->compare('country_phonecode', $this->country_phonecode);
		$criteria->compare('active', $this->active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Countries the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function counrtyMobileCode()
	{
		$countryMobileCode = array(
			'+91'	 => '+91',
			'+1'	 => '+1',
			'+61'	 => '+61',
		);
		return $countryMobileCode;
	}

	public function getCodeJSON($default = '')
	{
		$i			 = 1;
		/* @var  Services  */
		$arrService	 = array();
		$modelData	 = $this->getCodeList($default);
		foreach ($modelData as $val)
		{
			$arrService[] = array('id' => $val ['id'], 'name' => $val['country_name'] . " (" . $val['country_phonecode'] . ")", 'pcode' => $val['country_phonecode'], 'order' => "$i");
			// $arrService1[] = array('id' => $val ['id'], 'pcode' => $val['country_phonecode']);
			$i++;
		}
		$data = CJSON::encode($arrService);
		return $data;
	}

	public function getCodeFromPhoneCode($phoneCode)
	{
		$sql	 = "SELECT country_code from countries where country_phonecode = $phoneCode";
		$code	 = DBUtil::command($sql)->queryScalar();
		return $code;
	}

	public function getCodeData($default = '')
	{
		$sql		 = "SELECT `id`,`country_code`,`country_name`,`country_phonecode` FROM `countries` WHERE country_phonecode IS NOT NULL GROUP BY country_phonecode,country_name ORDER BY country_phonecode ASC, country_name ASC";
		$modelData	 = DBUtil::queryAll($sql);
		return $modelData;
	}

	public function getCodeList($default = '')
	{
		// $default = 91;
		$sql		 = "SELECT `id`,`country_code`,`country_name`,`country_phonecode` FROM `countries` WHERE country_phonecode != '' OR country_phonecode IS NOT NULL GROUP BY country_phonecode ORDER BY country_name ASC";
		$modelData	 = DBUtil::queryAll($sql);
		$arrService	 = array();
		$i			 = 1;
		/* @var  Services  */
		foreach ($modelData as $val)
		{
			$arrService[$val ['country_phonecode']] = "+" . $val['country_phonecode'] . " (" . $val['country_name'] . ")";
		}
		if ($default > 0)
		{
			$defvalue[$default]	 = $arrService[$default];
			unset($arrService[$default]);
			$arrService			 = $defvalue + $arrService;
		}

//		 var_dump($arrService);
		return $arrService;
	}

	public function getCodeListMini($default = '')
	{
		// $default = 91;
		$sql		 = "SELECT `id`,`country_code`,`country_name`,`country_phonecode` FROM `countries` WHERE country_phonecode != '' OR country_phonecode IS NOT NULL GROUP BY country_phonecode ORDER BY country_name ASC";
		$modelData	 = DBUtil::queryAll($sql);
		$arrService	 = array();
		$i			 = 1;
		/* @var  Services  */
		foreach ($modelData as $val)
		{
			$arrService[$val ['country_phonecode']] = "+" . $val['country_phonecode'];
		}
		if ($default > 0)
		{
			$defvalue[$default]	 = $arrService[$default];
			unset($arrService[$default]);
			$arrService			 = $defvalue + $arrService;
		}

		// var_dump($arrService);
		return $arrService;
	}

	public function getCountryName($id)
	{
		$name = "";
		if ($id != '')
		{
			$name = $this->findByPk($id)->country_name;
		}
		return $name;
	}
    
    public function getCountryList($default = '')
	{
		// $default = 91;
		$sql		 = "SELECT `id`, `country_name`,`country_phonecode` FROM `countries` WHERE country_phonecode != '' OR country_phonecode IS NOT NULL GROUP BY country_phonecode ORDER BY country_name ASC";
		$data	 = DBUtil::queryAll($sql);
		return $data;
	}

	public static function getByCode($code)
	{
		$params = ['code'=>$code];
		$sql	 = "SELECT country_name from countries where country_code =:code";
		return	DBUtil::queryScalar($sql, null, $params);
	}
	public static function getByName($name)
	{
		$params = ['name'=>$name];
		$sql	 = "SELECT country_code from countries where country_name =:name";
		return	DBUtil::queryScalar($sql, null, $params);
	}
	public function getCountriesList()
    {
        $criteria             = new CDbCriteria();
        $criteria->select     = "id, country_name";
        $criteria->compare('active', 1);
        $criteria->order     = "country_name";
        $comments             = Countries::model()->findAll($criteria);
        return $comments;
    }
	public function getList($all = null)
    {
        $stateModels = Countries::model()->getCountriesList();
        $arrSkill     = Filter::ObjectArrayToArrayList($stateModels, "id", "country_name");
        return $arrSkill;
    }
}

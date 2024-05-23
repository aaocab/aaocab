<?php

/**
 * This is the model class for table "service_class".
 *
 * The followings are the available columns in table 'service_class':
 * @property integer $scc_id
 * @property integer $scc_type
 * @property string $scc_label
 * @property string $scc_title
 * @property string $scc_desc
 * @property string $scc_vnd_desc
 * @property integer $scc_is_cng
 * @property integer $scc_is_petrol_diesel
 * @property integer $scc_model_year
 * @property integer $scc_markup_type
 * @property integer $scc_markup
 * @property string  $scc_image
 * @property integer $scc_odometer
 * @property integer $scc_active
 * @property string $scc_create_date
 * @property string $scc_modified_date
 * @property integer $scc_rank
 */
class ServiceClass extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'service_class';
	}

	const CLASS_ECONOMIC		 = 1;
	const CLASS_VLAUE_PLUS	 = 2;
	const CLASS_PLUS			 = 3;
	const CLASS_SELECT		 = 4;
	const CLASS_SELECT_PLUS	 = 5;
	const CLASS_VALUE_CNG		 = 6;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('scc_label,scc_model_year', 'required', 'except' => 'statuschange'),
			array('scc_type, scc_active', 'numerical', 'integerOnly' => true),
			array('scc_label', 'length', 'max' => 100),
			array('scc_desc', 'length', 'max' => 255),
			array('scc_label', 'required', 'on' => 'statuschange'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('scc_id, scc_type, scc_label, scc_title, scc_desc, scc_vnd_desc, scc_is_cng, scc_is_petrol_diesel, scc_model_year,scc_markup_type,scc_markup, scc_image, scc_odometer, scc_active, scc_create_date, scc_modified_date,scc_rank', 'safe', 'on' => 'search'),
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
			'scc_SvcClassVhcCat' => array(self::HAS_MANY, 'SvcClassVhcCat', 'scv_scc_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'scc_id'				 => 'Sec',
			'scc_type'				 => 'service type',
			'scc_label'				 => 'service label',
			'scc_title'				 => 'Service Title',
			'scc_desc'				 => 'description',
			'scc_vnd_desc'			 => 'Vendor Description',
			'scc_is_cng'			 => 'cng',
			'scc_is_petrol_diesel'	 => 'petrol diesel',
			'scc_model_year'		 => 'model year',
			'scc_markup_type'		 => 'markup type',
			'scc_image'				 => 'Service Image',
			'scc_odometer'			 => 'Odometer',
			'scc_markup'			 => 'markup',
			'scc_active'			 => 'Scc Active',
			'scc_create_date'		 => 'Scc Create Date',
			'scc_modified_date'		 => 'Scc Modified Date',
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

		$criteria->compare('scc_id', $this->scc_id);
		$criteria->compare('scc_type', $this->scc_type);
		$criteria->compare('scc_label', $this->scc_label, true);
		$criteria->compare('scc_desc', $this->scc_desc, true);
		$criteria->compare('scc_vnd_desc', $this->scc_vnd_desc, true);
		$criteria->compare('scc_is_cng', $this->scc_is_cng, true);
		$criteria->compare('scc_is_petrol_diesel', $this->scc_is_petrol_diesel, true);
		$criteria->compare('scc_model_year', $this->scc_model_year, true);
		$criteria->compare('scc_active', $this->scc_active);
		$criteria->compare('scc_create_date', $this->scc_create_date, true);
		$criteria->compare('scc_modified_date', $this->scc_modified_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ServiceClass the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getJSON($arr = [])
	{
		$arrJSON = array();
		foreach ($arr as $key => $val)
		{
			$arrJSON[$val['scc_id']] = $val['scc_label'];
		}
		return $arrJSON;
	}

	public function getYearsList()
	{
		$currentYear = date('Y');
		$yearFrom	 = $currentYear - 10;
		$yearsRange	 = range($yearFrom, $currentYear);
		$arrJSON	 = array();
		foreach ($yearsRange as $key => $val)
		{
			$arrJSON[] = array("id" => $val, "text" => "" . $val . "");
		}
		$data = CJSON::encode($arrJSON);
		return $data;
	}

	public function getList($type = '')
	{
		$cond	 = "";
		$sql	 = "SELECT *   FROM service_class ";
		if ($this->scc_label != '')
		{
			$cond .= " AND scc_label LIKE '%" . $this->scc_label . "%'";
		} if ($this->scc_desc != '')
		{
			$cond .= " AND scc_desc LIKE '%" . $this->scc_desc . "%'";
		}
		$sql .= ' WHERE 1 AND scc_active =1' . $cond;
		if ($type == '')
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'db'			 => DBUtil::SDB(),
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['scc_label', 'scc_desc', 'scc_is_cng', 'scc_is_petrol_diesel', 'scc_active'],
					'defaultOrder'	 => 'scc_rank ASC'],
				'pagination'	 => ['pageSize' => 10],
			]);
			return $dataprovider;
		}
		if ($type == 'array')
		{
			return DBUtil::queryAll($sql, DBUtil::SDB(), ['scc_id', 'scc_label']);
		}
		if ($type == 'filter')
		{
			$rows		 = DBUtil::queryAll($sql, DBUtil::SDB());
			$arrResponse = [];
			foreach ($rows as $class)
			{
				$arrResponse[$class["scc_id"]] = $class["scc_label"];
			}
			return $arrResponse;
		}
	}

	public static function getMarkUp($svcId, $amount)
	{
		$sql	 = "SELECT scc_markup_type,scc_markup FROM service_class INNER JOIN svc_class_vhc_cat ON scv_scc_id = scc_id WHERE scv_id = $svcId";
		$result	 = DBUtil::command($sql, DBUtil::SDB(), 43200, CacheDependency::Type_CabTypes)->queryRow();
		$markup	 = $result['scc_markup'];
		if ($result['scc_markup_type'] == 1)
		{
			$markup = ROUND($amount * ($result['scc_markup'] / 100));
		}
		return $markup;
	}

	public static function getPriceRuleWithMarkUp($svcId, $priceRule)
	{
		$sql							 = "SELECT scc_markup_type,scc_markup FROM service_class INNER JOIN svc_class_vhc_cat ON scv_scc_id = scc_id WHERE scv_id = $svcId";
		$result							 = DBUtil::command($sql, DBUtil::SDB(), 60 * 60 * 24 * 5, CacheDependency::Type_CabTypes)->queryRow();
		$priceRule->basePriceRule		 = clone $priceRule;
		$priceRule->prr_min_base_amount	 = self::addMarkup($result['scc_markup_type'], $result['scc_markup'], $priceRule->prr_min_base_amount);
		if ($result['scc_markup_type'] == 1)
		{
			$priceRule->prr_rate_per_km				 = self::addMarkup($result['scc_markup_type'], $result['scc_markup'], $priceRule->prr_rate_per_km);
			$priceRule->prr_rate_per_km_extra		 = self::addMarkup($result['scc_markup_type'], $result['scc_markup'], $priceRule->prr_rate_per_km_extra);
			$priceRule->prr_day_driver_allowance	 = self::addMarkup($result['scc_markup_type'], $result['scc_markup'], $priceRule->prr_day_driver_allowance);
			$priceRule->prr_night_driver_allowance	 = self::addMarkup($result['scc_markup_type'], $result['scc_markup'], $priceRule->prr_night_driver_allowance);
			$priceRule->prr_rate_per_minute			 = self::addMarkup($result['scc_markup_type'], $result['scc_markup'], $priceRule->prr_rate_per_minute);
			$priceRule->prr_rate_per_minute_extra	 = self::addMarkup($result['scc_markup_type'], $result['scc_markup'], $priceRule->prr_rate_per_minute_extra);
		}
		return $priceRule;
	}

	public static function addMarkup($type, $value, $amount)
	{
		if ($type == 1)
		{
			$amount = $amount * (100 + $value) / 100;
		}
		else
		{
			$amount = $amount + $value;
		}
		return Filter::round_half_up($amount);
	}

	public static function getAll()
	{
		$sql	 = "SELECT * FROM service_class WHERE scc_active = 1 ORDER BY scc_rank ASC";
		$result	 = DBUtil::query($sql, DBUtil::SDB(), [], 5 * 24 * 60 * 60, CacheDependency::Type_CabTypes);
		return $result;
	}

	public static function getTier()
	{
		$sql = "SELECT scc_id, scc_label FROM service_class WHERE scc_active = 1";
		$arr = DBUtil::query($sql, DBUtil::SDB());
		return CHtml::listData($arr, "scc_id", "scc_label");
	}

	/**
	 * 
	 * @param string $ids
	 * @return string
	 */
	public static function getTierByIds($ids)
	{
		$sql = "SELECT GROUP_CONCAT(scc_label SEPARATOR ' , ') as tier FROM service_class WHERE service_class.scc_id IN ($ids)";
		return DBUtil::queryScalar($sql, DBUtil::SDB());
	}

	/**
	 * 
	 * @param int $cabType
	 * @return int
	 */
	public static function getUpperClass($class)
	{
		switch ($class)
		{
			case 6:
				$return	 = 1;
				break;
			case 1:
				$return	 = 2;
				break;
			case 2:
				$return	 = 4;
				break;
			case 4:
				$return	 = 4;
				break;
		}
		return $return;
	}

}

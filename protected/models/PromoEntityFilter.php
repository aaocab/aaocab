<?php

/**
 * This is the model class for table "promo_entity_filter".
 *
 * The followings are the available columns in table 'promo_entity_filter':
 * @property integer $pef_id
 * @property string $pef_title
 * @property string $pef_desc
 * @property integer $pef_area_type_from
 * @property integer $pef_area_type_to
 * @property integer $pef_area_type
 * @property string $pef_area_id
 * @property string $pef_area_from_id
 * @property string $pef_area_to_id
 * @property integer $pef_active
 * @property integer $pef_promo_id
 * @property string $pef_log
 * @property string $pef_modified
 * @property string $pef_created
 * @property integer $pef_booking_type
 * @property integer $pef_cab_type
 */
class PromoEntityFilter extends CActiveRecord
{

	public $pef_area_from_id1, $pef_area_from_id2, $pef_area_from_id3, $pef_area_from_id4, $pef_area_to_id1, $pef_area_to_id2, $pef_area_to_id3, $pef_area_to_id4;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'promo_entity_filter';
	}

	public function defaultScope()
	{
		$arr = array(
			'condition' => "pef_active > 0",
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
			//array('pef_title, pef_desc', 'required'),
			array('pef_area_type_from, pef_area_type_to,pef_area_type, pef_active', 'numerical', 'integerOnly' => true),
			array('pef_title', 'length', 'max' => 250),
			array('pef_desc, pef_log', 'length', 'max' => 500),
			array('pef_area_from_id, pef_area_to_id,pef_area_id', 'length', 'max' => 1000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pef_id, pef_title, pef_desc, pef_area_type_from,pef_area_type,pef_area_id, pef_area_type_to, pef_area_from_id, pef_area_to_id, pef_active, pef_log, pef_modified, pef_created, pef_booking_type, pef_cab_type, pef_promo_id', 'safe', 'on' => 'search'),
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
			'pef_id'			 => 'Pef',
			'pef_title'			 => 'Title',
			'pef_desc'			 => 'Description',
			'pef_area_type_from' => 'Area Type From',
			'pef_area_type_to'	 => 'Area Type To',
			'pef_area_from_id'	 => 'Area From',
			'pef_area_to_id'	 => 'Area To',
			'pef_active'		 => 'Active',
			'pef_log'			 => 'Log',
			'pef_modified'		 => 'Modified',
			'pef_created'		 => 'Created',
			'pef_booking_type'	 => 'Booking Type',
			'pef_cab_type'		 => 'Cab Type',
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

		$criteria->compare('pef_id', $this->pef_id);
		$criteria->compare('pef_title', $this->pef_title, true);
		$criteria->compare('pef_desc', $this->pef_desc, true);
		$criteria->compare('pef_area_type_from', $this->pef_area_type_from);
		$criteria->compare('pef_area_type_to', $this->pef_area_type_to);
		$criteria->compare('pef_area_from_id', $this->pef_area_from_id);
		$criteria->compare('pef_area_to_id', $this->pef_area_to_id);
		$criteria->compare('pef_active', $this->pef_active);
		$criteria->compare('pef_log', $this->pef_log, true);
		$criteria->compare('pef_modified', $this->pef_modified, true);
		$criteria->compare('pef_created', $this->pef_created, true);
		$criteria->compare('pef_booking_type', $this->pef_booking_type, true);
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PromoEntityFilter the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getAllEntityFilterCode()
	{
		$data	 = [];
		$sql	 = "SELECT pef_id,pef_title FROM promo_entity_filter WHERE pef_active=1";
		$res	 = DBUtil::queryAll($sql, DBUtil::SDB());
		if (count($res))
		{
			foreach ($res as $key => $value)
			{
				$data[$value['pef_id']] = $value['pef_title'];
			}
		}
		return $data;
	}

	public function getApplicable($promoId, $fromCity, $toCity, $bkgType, $cabType)
	{
		$entityModel = $this->getByPromoId($promoId);
		$where		 = "";
		$having      = "";
		if ($cabType > 0)
		{
			$sqlCab	 = "SELECT vct_id FROM svc_class_vhc_cat,vehicle_category WHERE  scv_vct_id = vct_id AND scv_id = $cabType";
			$resCab	 = DBUtil::command($sqlCab)->queryScalar();
			$where	 = " AND (FIND_IN_SET($resCab,pef_cab_type) OR pef_cab_type IS NULL)";
		}
		if($entityModel->pef_area_type == '' && $entityModel->pef_area_id == '')
		{
			$having = " (FIND_IN_SET(fromAreaId,pef_area_from_id) OR pef_area_from_id IS NULL OR pef_area_from_id='') 
				AND (FIND_IN_SET(toAreaId,pef_area_to_id) OR pef_area_to_id IS NULL OR pef_area_from_id='')";
		}
		else
		{
			$having = " (FIND_IN_SET(areaIdFrom,pef_area_id) OR pef_area_id IS NULL OR pef_area_id='') OR 
					(FIND_IN_SET(areaIdTo,pef_area_id) OR pef_area_id IS NULL OR pef_area_id='')";
		}

		$sql = "SELECT CASE WHEN pef_area_type_from=1 THEN fromCity.zct_zon_id WHEN pef_area_type_from=2 THEN fromCity.stt_id WHEN pef_area_type_from=3 THEN fromCity.cty_id 
				WHEN pef_area_type_from=4 THEN fromCity.stt_zone ELSE 0 END AS fromAreaId,
				CASE WHEN pef_area_type_to=1 THEN toCity.zct_zon_id WHEN pef_area_type_to=2 THEN toCity.stt_id WHEN pef_area_type_to=3 THEN toCity.cty_id 
				WHEN pef_area_type_to=4 THEN toCity.stt_zone ELSE 0 END AS toAreaId,
				CASE WHEN pef_area_type=1 THEN toCity.zct_zon_id WHEN pef_area_type=2 THEN toCity.stt_id WHEN pef_area_type=3 THEN toCity.cty_id 
				WHEN pef_area_type=4 THEN toCity.stt_zone ELSE 0 END AS areaIdTo,
				CASE WHEN pef_area_type=1 THEN fromCity.zct_zon_id WHEN pef_area_type=2 THEN fromCity.stt_id WHEN pef_area_type=3 THEN fromCity.cty_id 
				WHEN pef_area_type=4 THEN fromCity.stt_zone ELSE 0 END AS areaIdFrom,
				pef_area_from_id,pef_area_to_id,pef_area_id
				FROM
				promo_entity_filter pef
				LEFT JOIN 
				(SELECT cty_id,zct_zon_id,stt_id,stt_zone FROM cities 
				LEFT JOIN zone_cities ON cities.cty_id=zone_cities.zct_cty_id
				LEFT JOIN `states` ON states.stt_id=cities.cty_state_id
				WHERE
				cities.cty_id = $fromCity) fromCity ON 1
				LEFT JOIN 
				(SELECT cty_id,zct_zon_id,stt_id,stt_zone FROM cities 
				LEFT JOIN zone_cities ON cities.cty_id=zone_cities.zct_cty_id
				LEFT JOIN `states` ON states.stt_id=cities.cty_state_id
				WHERE
				cities.cty_id = $toCity) toCity ON 1
				WHERE
				(FIND_IN_SET($bkgType,pef_booking_type) OR pef_booking_type IS NULL)
				$where
				AND pef_active =1
				AND pef_promo_id=$promoId
				HAVING $having";
		$res = DBUtil::queryRow($sql);
		if ($res == false)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	public function getByPromoId($prmId)
	{
		return $this->find('pef_promo_id=:prm', ['prm' => $prmId]);
	}

}

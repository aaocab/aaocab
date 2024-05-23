<?php

/**
 * This is the model class for table "hawkeye_price_list".
 *
 * The followings are the available columns in table 'hawkeye_price_list':
 * @property integer $hpl_id
 * @property integer $hpl_route_id
 * @property string $hpl_pickup_date
 * @property integer $hpl_compact
 * @property integer $hpl_sedan
 * @property integer $hpl_suv
 * @property integer $hpl_tempo_traveller
 * @property string $hpl_last_update
 * @property integer $hpl_surge_applied
 * @property integer $hpl_status
 */
class Hawkeye extends CActiveRecord
{

	public $fromCity, $toCity , $hpl_pickup_date1,$hpl_pickup_date2,$id;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'hawkeye_price_list';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('hpl_tempo_traveller', 'required'),
			array('hpl_route_id, hpl_compact, hpl_sedan, hpl_suv, hpl_tempo_traveller, hpl_surge_applied, hpl_status', 'numerical', 'integerOnly' => true),
			array('hpl_pickup_date, hpl_last_update', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('hpl_id, hpl_route_id, hpl_pickup_date, hpl_compact, hpl_sedan, hpl_suv, hpl_tempo_traveller, hpl_last_update, hpl_surge_applied, hpl_status', 'safe', 'on' => 'search'),
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
			'hpl_id'				 => 'Hpl',
			'hpl_route_id'			 => 'Route',
			'hpl_pickup_date'		 => 'Pickup Date',
			'hpl_compact'			 => 'Compact',
			'hpl_sedan'				 => 'Sedan',
			'hpl_suv'				 => 'Suv',
			'hpl_tempo_traveller'	 => 'Tempo Traveller',
			'hpl_last_update'		 => 'Last Update',
			'hpl_surge_applied'		 => 'Surge Applied',
			'hpl_status'			 => 'Status',
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

		$criteria->compare('hpl_id', $this->hpl_id);
		$criteria->compare('hpl_route_id', $this->hpl_route_id);
		$criteria->compare('hpl_pickup_date', $this->hpl_pickup_date, true);
		$criteria->compare('hpl_compact', $this->hpl_compact);
		$criteria->compare('hpl_sedan', $this->hpl_sedan);
		$criteria->compare('hpl_suv', $this->hpl_suv);
		$criteria->compare('hpl_tempo_traveller', $this->hpl_tempo_traveller);
		$criteria->compare('hpl_last_update', $this->hpl_last_update, true);
		$criteria->compare('hpl_surge_applied', $this->hpl_surge_applied);
		$criteria->compare('hpl_status', $this->hpl_status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Hawkeye the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getData($data)
	{
		$i = 0;
		foreach ($data as $val)
		{
			$frmCityId	 = Cities::model()->getCtyNameByCtyId($val['src_city']);
			$toCityId	 = Cities::model()->getCtyNameByCtyId($val['dst_city']);
			$rutId		 = Route::model()->getRutidbyCities($frmCityId, $toCityId);

			$compactPrice = $val['compact_price'];
			$sedanPrice   = $val['sedan_price'];
			$suvPrice     = $val['suv_price'];
			$pickupDate	 = $val['pickup_date'];
			$hawkEyeList = $this->checkDuplicate($rutId,$pickupDate,$compactPrice,$sedanPrice,$suvPrice);
			Logger::create("Request test dfffgfg ====>" . $rutId . "===" . $i . "====", CLogger::LEVEL_PROFILE);
		}
	}

	public function getlist($date1 = '', $date2 = '', $from = '', $to = '')
	{
		$sql = "SELECT hpl_route_id,hpl.hpl_pickup_date,hpl.hpl_compact,hpl.hpl_sedan,hpl.hpl_suv,hpl.hpl_tempo_traveller,hpl.hpl_surge_applied,frmCity.cty_name as fromCity,frmCity.cty_id as fromCityId, toCity.cty_name as toCity,toCity.cty_id as toCityId
				FROM hawkeye_price_list hpl
				INNER JOIN route rut ON rut.rut_id = hpl.hpl_route_id
				LEFT JOIN cities frmCity ON frmCity.cty_id = rut.rut_from_city_id AND frmCity.cty_active=1  
				LEFT JOIN cities toCity ON toCity.cty_id = rut.rut_to_city_id AND toCity.cty_active=1 WHERE 1=1";

		if (($date1 != '' && $date1 != '') && ($date2 != '' && $date2 != ''))
		{
			$sql .= " AND (date(hpl.hpl_pickup_date) BETWEEN '" . $date1 . "' AND '" . $date2 . "') ";
		}
		if (isset($from) && $from != '')
		{
			$sql .= " AND frmCity.cty_id='" . $from . "'";
		}
		if (isset($to) && $to != '')
		{
			$sql .= " AND toCity.cty_id ='" . $to . "'";
		}
		if ($count === null)
		{
			$count = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		}
		$dataprovider = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 =>
			['attributes'	 => ['hpl_route_id', 'hpl_pickup_date', 'hpl_compact', 'hpl_sedan', 'hpl_suv', 'hpl_tempo_traveller', 'hpl_surge_applied', 'hpl_last_update'],
				'defaultOrder'	 => 'hpl_last_update DESC'],
			'pagination'	 => ['pageSize' => 10],
		]);
		return $dataprovider;
	}

	public function checkDuplicate($rutId,$pickupDate,$compactPrice=0,$sedanPrice=0,$suvPrice=0)
	{
		$lastUpdate = date("Y-m-d H:i:s", strtotime("now"));
		$sql = "SELECT * FROM hawkeye_price_list WHERE hpl_route_id = $rutId AND hpl_pickup_date = '$pickupDate' AND (hpl_compact != 0 OR hpl_sedan != 0 or hpl_suv != 0) ";
		$row = DBUtil::queryRow($sql, DBUtil::SDB());
		if (!$row)
		{
			$hawkEyeList					 = new Hawkeye();
			$hawkEyeList->hpl_route_id		 = $rutId;
			$hawkEyeList->hpl_pickup_date	 = $pickupDate;
			$hawkEyeList->hpl_compact        = $compactPrice;
			$hawkEyeList->hpl_sedan			 = $sedanPrice;
			$hawkEyeList->hpl_suv            = $suvPrice;
			$hawkEyeList->hpl_status         = 1;
		}
		else
		{
			$hawkEyeList                    = Hawkeye::model()->findByPk($row['hpl_id']);
			$priceChange = 0;
			if ($hawkEyeList->hpl_compact != $compactPrice && $compactPrice != 0)
			{
				$hawkEyeList->hpl_compact			 = $compactPrice;
			}
			if ($hawkEyeList->hpl_sedan != $sedanPrice && $sedanPrice != 0)
			{
				$hawkEyeList->hpl_sedan				 = $sedanPrice;
			}
			if ($hawkEyeList->hpl_suv != $suvPrice && $suvPrice != 0)
			{
				$hawkEyeList->hpl_suv				 = $suvPrice;
			}
		}
		$hawkEyeList->hpl_last_update = $lastUpdate;
//		if ($priceChange == 1)
//		{
//			$hawkEyeList->hpl_compact			 = $compactPrice;
//			$hawkEyeList->hpl_sedan				 = $sedanPrice;
//			$hawkEyeList->hpl_suv				 = $suvPrice;
//			$hawkEyeList->hpl_surge_applied		 = 0;
//
//		}
		$hawkEyeList->save();
	}
}

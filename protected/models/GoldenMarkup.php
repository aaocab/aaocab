<?php

/**
 * This is the model class for table "golden_markup".
 *
 * The followings are the available columns in table 'golden_markup':
 * @property integer $glm_id
 * @property integer $glm_area_type
 * @property integer $glm_from_area
 * @property integer $glm_to_area
 * @property integer $glm_apply_hour_duration
 * @property integer $glm_markup_type
 * @property integer $glm_markup_value
 * @property integer $glm_maximum_amount
 * @property integer $glm_cab_type
 * @property integer $glm_trip_type
 * @property integer $glm_active
 * @property string $glm_created_at
 */
class GoldenMarkup extends CActiveRecord
{

	public $areaTypeArr = [1 => 'City', 2 => 'Zone', 3 => 'State', 4 => 'Region', 5 => 'Default'];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'golden_markup';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('glm_area_type, glm_from_area, glm_to_area, glm_apply_hour_duration, glm_markup_type, glm_markup_value', 'required'),
			array('glm_area_type, glm_from_area, glm_to_area, glm_apply_hour_duration, glm_markup_type, glm_markup_value, glm_maximum_amount, glm_cab_type, glm_trip_type, glm_active', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('glm_id, glm_area_type, glm_from_area, glm_to_area, glm_apply_hour_duration, glm_markup_type, glm_markup_value, glm_maximum_amount, glm_cab_type, glm_trip_type, glm_active, glm_created_at', 'safe', 'on' => 'search'),
			array('glm_id, glm_area_type, glm_from_area, glm_to_area, glm_apply_hour_duration, glm_markup_type, glm_markup_value, glm_maximum_amount, glm_cab_type, glm_trip_type, glm_active, glm_created_at', 'safe'),
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
			'glm_id'					 => 'Glm',
			'glm_area_type'				 => 'Area Type',
			'glm_from_area'				 => 'From Area',
			'glm_to_area'				 => 'To Area',
			'glm_apply_hour_duration'	 => 'Apply Hour Duration',
			'glm_markup_type'			 => 'Markup Type',
			'glm_markup_value'			 => 'Markup Value',
			'glm_maximum_amount'		 => 'Maximum Amount',
			'glm_cab_type'				 => 'Cab Type',
			'glm_trip_type'				 => 'Trip Type',
			'glm_active'				 => 'Active',
			'glm_created_at'			 => 'Created At',
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

		$criteria->compare('glm_id', $this->glm_id);
		$criteria->compare('glm_area_type', $this->glm_area_type);
		$criteria->compare('glm_from_area', $this->glm_from_area);
		$criteria->compare('glm_to_area', $this->glm_to_area);
		$criteria->compare('glm_apply_hour_duration', $this->glm_apply_hour_duration);
		$criteria->compare('glm_markup_type', $this->glm_markup_type);
		$criteria->compare('glm_markup_value', $this->glm_markup_value);
		$criteria->compare('glm_maximum_amount', $this->glm_maximum_amount);
		$criteria->compare('glm_cab_type', $this->glm_cab_type);
		$criteria->compare('glm_trip_type', $this->glm_trip_type);
		$criteria->compare('glm_active', $this->glm_active);
		$criteria->compare('glm_created_at', $this->glm_created_at, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GoldenMarkup the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function fetchData($fromCity, $toCity, $pickupDate, $vndAmount, $cabType, $tripType)
	{
		if (($toCity == "" || $toCity == null) || ($fromCity == "" || $fromCity == null) || ($pickupDate == "" || $pickupDate == null))
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$sql_vehicle_type	 = ($cabType != '') ? " AND (glm_cab_type={$cabType} OR glm_cab_type IS NULL)" : "";
		$sql_trip_type		 = ($tripType != '') ? " AND (glm_trip_type={$tripType} OR glm_trip_type IS NULL)" : "";

//		$pickdiff	 = " TIMESTAMPDIFF(HOUR, NOW(), '$pickupDate') ";
		$pickdiff	 = " CalcWorkingHour( NOW(),'$pickupDate') ";
		$stmt		 = " fct.cty_id = $fromCity AND glm.glm_active=1  AND tct.cty_id = $toCity $sql_vehicle_type $sql_trip_type 
						GROUP BY glm.glm_id 
						HAVING glm_apply_hour_duration >= pickdiff";

		$select = "SELECT  glm.glm_active,  glm.glm_id, glm.glm_apply_hour_duration, glm.glm_markup_value,glm.glm_markup_type,glm.glm_maximum_amount,
         $pickdiff pickdiff, glm.glm_area_type, glm.glm_from_area, glm.glm_to_area,glm.glm_trip_type,glm.glm_cab_type,
          fct.cty_id fctid, tct.cty_id tctid,  fzct.zct_zon_id fzon,fct.cty_name AS fromCity,tct.cty_name AS toCity,
           tzct.zct_zon_id tzon, fstt.stt_id fstate, tstt.stt_id tstate,
          fstt.stt_zone fregion, tstt.stt_zone tregion
		FROM golden_markup glm ";

		$sql = " SELECT   *, 
					IF(  glm_trip_type IS NOT NULL,1 ,0) As tripRank,
					IF(  glm_cab_type IS NOT NULL,1 ,0) As cabRank,  
					CASE
						WHEN glm_area_type = 1   THEN 4
						WHEN glm_area_type = 2   THEN 3
						WHEN glm_area_type = 3   THEN 2
						WHEN glm_area_type = 4   THEN 1
						WHEN glm_area_type = 5   THEN 0
					END AS areaRank,
					if(glm_markup_type = 1, if(glm_maximum_amount > 0, least(($vndAmount * (glm_markup_value + 100) / 100), glm_maximum_amount), ($vndAmount * (glm_markup_value + 100) / 100)), $vndAmount + glm_markup_value) totamount
				FROM   ((
					$select
						JOIN zone_cities fzct
						  ON (glm_area_type = 1 AND fzct.zct_cty_id = glm_from_area) 
						  OR (glm_area_type = 2 AND fzct.zct_zon_id = glm_from_area)
						JOIN zone_cities tzct
						  ON (glm_area_type = 1 AND tzct.zct_cty_id = glm_to_area) 
						  OR (glm_area_type = 2 AND tzct.zct_zon_id = glm_to_area)
						JOIN cities fct
						  ON (glm_area_type = 1 AND fct.cty_id = glm.glm_from_area) 
						  OR (glm_area_type = 2 AND fzct.zct_cty_id = fct.cty_id) 
						JOIN cities tct
						  ON (glm_area_type = 1 AND tct.cty_id = glm.glm_to_area) 
						  OR (glm_area_type = 2 AND tzct.zct_cty_id = tct.cty_id)  
						JOIN states fstt ON (fstt.stt_id = fct.cty_state_id)
						JOIN states tstt ON (tstt.stt_id = tct.cty_state_id)
					 WHERE  glm_area_type IN (1,2) AND $stmt 
				)  UNION (		
						$select
						JOIN cities fct ON (fct.cty_state_id = glm_from_area)
						JOIN cities tct ON (tct.cty_state_id = glm_to_area)
						JOIN zone_cities fzct ON (fzct.zct_cty_id = fct.cty_id)
						JOIN zone_cities tzct ON (tzct.zct_cty_id = tct.cty_id)
						JOIN states fstt ON (fstt.stt_id = fct.cty_state_id)
						JOIN states tstt ON (tstt.stt_id = tct.cty_state_id)
						WHERE    glm_area_type = 3 AND  $stmt   
				) UNION ( 
					$select
					JOIN states fstt ON ( fstt.stt_zone = glm.glm_from_area)
					JOIN states tstt ON ( tstt.stt_zone = glm.glm_to_area)
					JOIN cities fct ON (fstt.stt_id = fct.cty_state_id  AND fct.cty_state_id = fstt.stt_id)
					JOIN cities tct ON (tstt.stt_id = tct.cty_state_id  AND tct.cty_state_id = tstt.stt_id)
					JOIN zone_cities fzct ON (fzct.zct_cty_id = fct.cty_id)
					JOIN zone_cities tzct ON ( tzct.zct_cty_id = tct.cty_id)
					WHERE    glm_area_type = 4 AND $stmt 
		) UNION ( 
			$select
			JOIN zone_cities fzct ON (glm_area_type = 5 AND fzct.zct_cty_id = $fromCity AND glm_from_area = 0)
			JOIN zone_cities tzct ON (glm_area_type = 5 AND tzct.zct_cty_id =$toCity  AND glm_to_area = 0) 			   
			JOIN cities fct ON fzct.zct_cty_id = fct.cty_id
			JOIN cities tct ON   tct.cty_id =  tzct.zct_cty_id 
			JOIN states fstt ON (fstt.stt_id = fct.cty_state_id)
			JOIN states tstt ON (tstt.stt_id = tct.cty_state_id)
			WHERE    glm_area_type = 5 AND $stmt )
		)a  
		ORDER BY areaRank DESC,tripRank desc,cabRank desc,glm_apply_hour_duration ASC,totamount DESC LIMIT 1
		";

		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	public function getList($package = '')
	{
		$sql = "SELECT glm_area_type,glm_from_area,glm_to_area,
			group_concat(glm_apply_hour_duration) glm_apply_hour_duration,group_concat(glm_markup_value) glm_markup_value
                  FROM golden_markup gm
                   
                   WHERE glm_active = 1  ";

		if (isset($this->glm_from_area) && $this->glm_from_area != "")
		{
			$sql .= " AND glm_from_area = $this->glm_from_area";
		}
		if (isset($this->glm_to_area) && $this->glm_to_area != "")
		{
			$sql .= " AND glm_to_area = $this->glm_to_area";
		}
		$sql			 .= " GROUP BY glm_area_type,glm_from_area,glm_to_area ";
		//echo $sql; die();
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql ) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['glm_id'],
				'defaultOrder'	 => ' glm_apply_hour_duration ASC'],
			'pagination'	 => ['pageSize' => 20],
		]);
		return $dataprovider;
	}

	public function getAreaType($areaType = 0)
	{
		$areaList	 = $this->areaTypeArr;
		$areaId		 = $this->glm_area_type;
		if ($areaId)
		{
			$areaType = $areaId;
		}
		$result = ($areaType == 0) ? $areaList : $areaList[$areaType];
		return $result;
	}

	public function getListVal()
	{
		$sql	 = " SELECT   glm_area_type, glm_from_area, glm_to_area,  glm_apply_hour_duration,  glm_markup_value
FROM     golden_markup gm
WHERE    glm_active = 1 
ORDER BY  glm_from_area, glm_to_area, glm_apply_hour_duration
  
 ";
		$result	 = DBUtil::queryAll($sql);
		$resArr	 = [];
		foreach ($result as $value)
		{
//			$arr  =[$value['glm_from_area'],$value['glm_to_area']];
//			$resArr[] = ['from'		 => $value['glm_from_area'], 'to'		 => $value['glm_to_area'],
//				[ 'apply_hour' => $value['glm_apply_hour_duration'], 'markup'	 => $value['glm_markup_value']]];


			$resArr[$value['glm_from_area'] . '-' . $value['glm_to_area']][] = [$value['glm_apply_hour_duration'], $value['glm_markup_value']];
		}

		return $resArr;
	}

	public function getLowestPriceDurationRange($fromCityId, $toCityId, $pickupDate, $tripType, $cabType)
	{
		if (($toCityId == "" || $toCityId == null) || ($fromCityId == "" || $fromCityId == null) || ($pickupDate == "" || $pickupDate == null))
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$pickdiff	 = " CalcWorkingHour( NOW(),'$pickupDate') ";
		$sql		 = "  	
		SELECT     glm1.glm_apply_hour_duration, hour_duration, glm_markup_value, pickdiff,
		 addWorkingMinutes (glm1.glm_apply_hour_duration*60,now()) glmDateTime,
		 addWorkingMinutes (36*60,now()) glmDateTime36,
		 addWorkingMinutes (60*60,now()) glmDateTime60,
		 
		CASE
			WHEN glm_area_type = 1   THEN 4
			WHEN glm_area_type = 2   THEN 3
			WHEN glm_area_type = 3   THEN 2
			WHEN glm_area_type = 4   THEN 1
			WHEN glm_area_type = 5   THEN 0
		END AS areaRank
from golden_markup glm1,(
SELECT     glm_apply_hour_duration hour_duration, glm_markup_value markup_value, glm_from_area from_area , glm_to_area to_area,
$pickdiff pickdiff,  
		CASE
			WHEN glm_area_type = 1   THEN 4
			WHEN glm_area_type = 2   THEN 3
			WHEN glm_area_type = 3   THEN 2
			WHEN glm_area_type = 4   THEN 1
			WHEN glm_area_type = 5   THEN 0
		END AS areaRank
FROM     golden_markup WHERE   
((glm_area_type <> 1 AND glm_from_area = 0 AND glm_to_area = 0 ) 
OR (glm_area_type = 1 AND glm_from_area = $fromCityId AND glm_to_area = $toCityId) )
	HAVING glm_apply_hour_duration > pickdiff
ORDER BY areaRank DESC,glm_markup_value, glm_apply_hour_duration  DESC
limit 1) glm2
WHERE      (glm_apply_hour_duration <=   hour_duration AND  glm_apply_hour_duration > pickdiff )
AND ((glm_area_type <> 1 AND glm_from_area = 0 AND glm_to_area = 0 ) 
OR (glm_area_type = 1 AND glm_from_area = from_area AND glm_to_area = to_area)  )
ORDER BY areaRank DESC,glm_markup_value ASC, glm_apply_hour_duration DESC
limit 2";

		$result = DBUtil::queryAll($sql);

//		var_dump($result);
		if (count($result) > 0)
		{
			$lowestPricing = false;
			if (count($result) == 1)
			{
				$durationTo	 = $result[0]['glm_apply_hour_duration'];
				$markupTo	 = $result[0]['glm_markup_value'];
				if ($markupTo == 0)
				{
					$return['lowestPricing'] = true;
					if ($durationTo < 36)
					{
						$durationFrom	 = 36;
						$durationTo		 = 60;
						$durationFrom++;
//						$durationStartDate	 =   date("Y-m-d H:i:s", strtotime("+ $durationFrom HOUR"));
//						$durationEndDate	 = date("Y-m-d H:i:s", strtotime("+ $durationTo HOUR"));

						$durationStartDate	 = Filter::addWorkingMinutes($durationFrom * 60); //date("Y-m-d H:i:s", strtotime("+ $durationFrom HOUR"));
						$durationEndDate	 = Filter::addWorkingMinutes($durationTo * 60); //  date("Y-m-d H:i:s", strtotime("+ $durationTo HOUR"));

						$durationStart	 = date("d/m/Y, ga", strtotime($durationStartDate));
						$durationEnd	 = date("d/m/Y, ga", strtotime($durationEndDate));
						$return			 = [
							'durationStart'	 => $durationStart,
							'durationEnd'	 => $durationEnd,
							'durationFrom'	 => $durationFrom . '',
							'durationTo'	 => $durationTo . '',
							'lowestPricing'	 => false];
					}
				}
				else
				{
					$durationFrom		 = $result[0]['glm_apply_hour_duration'];
					$durationTo			 = $result[0]['glm_apply_hour_duration'] + 36;
					$durationStartDate	 = Filter::addWorkingMinutes($durationFrom * 60); //date("Y-m-d H:i:s", strtotime("+ $durationFrom HOUR"));
					$durationEndDate	 = Filter::addWorkingMinutes($durationTo * 60); //date("Y-m-d H:i:s", strtotime("+ $durationTo HOUR"));
					$durationStart		 = date("d/m/Y, ga", strtotime($durationStartDate));
					$durationEnd		 = date("d/m/Y, ga", strtotime($durationEndDate));

					$return = [
						'durationStart'	 => $durationStart,
						'durationEnd'	 => $durationEnd,
						'durationFrom'	 => $durationFrom . '',
						'durationTo'	 => $durationTo . '',
						'lowestPricing'	 => $lowestPricing
					];
				}
			}
			if (count($result) == 2)
			{
				$durationFrom	 = $result[1]['glm_apply_hour_duration'];
				$durationTo		 = $result[0]['glm_apply_hour_duration'];
				$markupFrom		 = $result[1]['glm_markup_value'];
				$markupTo		 = $result[0]['glm_markup_value'];

				if ($durationFrom < 36)
				{
					$durationFrom	 = 36;
					$durationTo		 = 60;
				}

				if ($pickupDate >= $durationStartDate && $durationEndDate >= $pickupDate && $markupTo == 0)
				{
					$lowestPricing = true;
				}
				else
				{
					if ($markupTo != 0)
					{
						$durationFrom	 = $durationTo;
						$durationTo		 = $durationFrom + 36;
					}
				}
				$durationFrom++;
				$durationStartDate	 = Filter::addWorkingMinutes($durationFrom * 60); // date("Y-m-d H:i:s", strtotime("+ $durationFrom HOUR"));
				$durationEndDate	 = Filter::addWorkingMinutes($durationTo * 60); // date("Y-m-d H:i:s", strtotime("+ $durationTo HOUR"));
				$durationStart		 = date("d/m/Y, ga", strtotime($durationStartDate));
				$durationEnd		 = date("d/m/Y, ga", strtotime($durationEndDate));
				$return				 = [
					'durationStart'	 => $durationStart,
					'durationEnd'	 => $durationEnd,
					'durationFrom'	 => $durationFrom . '',
					'durationTo'	 => $durationTo . '',
					'markupFrom'	 => $markupFrom . '',
					'markupTo'		 => $markupTo . '',
					'lowestPricing'	 => $lowestPricing
				];
			}
		}
		else
		{
			$sql1			 = " SELECT $pickdiff pickdiff  from dual";
			$resultPickdiff	 = DBUtil::command($sql1)->queryScalar();
			$return			 = ['lowestPricing' => true];
			if ($resultPickdiff < 36)
			{
				$lowestPricing		 = false;
				$durationFrom		 = 36;
				$durationTo			 = 60;
				$durationFrom++;
				$durationStartDate	 = Filter::addWorkingMinutes($durationFrom * 60); //date("Y-m-d H:i:s", strtotime("+ $durationFrom HOUR"));
				$durationEndDate	 = Filter::addWorkingMinutes($durationTo * 60); //date("Y-m-d H:i:s", strtotime("+ $durationTo HOUR"));
				$durationStart		 = date("d/m/Y, ga", strtotime($durationStartDate));
				$durationEnd		 = date("d/m/Y, ga", strtotime($durationEndDate));
				$return				 = [
					'durationStart'	 => $durationStart,
					'durationEnd'	 => $durationEnd,
					'durationFrom'	 => $durationFrom . '',
					'durationTo'	 => $durationTo . '',
					'markupFrom'	 => $markupFrom . '',
					'markupTo'		 => $markupTo . '',
					'lowestPricing'	 => $lowestPricing
				];
			}
		}
		return $return;
	}

}

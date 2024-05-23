<?php

/**
 * This is the model class for table "home_service_zones".
 *
 * The followings are the available columns in table 'home_service_zones':
 * @property integer $hsz_id
 * @property integer $hsz_home_id
 * @property integer $hsz_service_id
 * @property integer $hsz_active
 */
class HomeServiceZones extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'home_service_zones';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hsz_service_id', 'required'),
			array('hsz_home_id, hsz_service_id, hsz_active', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('hsz_id, hsz_home_id, hsz_service_id, hsz_active', 'safe', 'on' => 'search'),
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
			'hsz_id'		 => 'Hsz',
			'hsz_home_id'	 => 'Hsz Home',
			'hsz_service_id' => 'Hsz Service',
			'hsz_active'	 => 'Hsz Active',
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

		$criteria->compare('hsz_id', $this->hsz_id);
		$criteria->compare('hsz_home_id', $this->hsz_home_id);
		$criteria->compare('hsz_service_id', $this->hsz_service_id);
		$criteria->compare('hsz_active', $this->hsz_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HomeServiceZones the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/*
	 * this function is used to get all the Home services zone by Zone Id
	 * @param int $zoneid
	 * @return dataprovider
	 * 
	 */

	public function getHomeServiceZoneById($zoneid)
	{
		$sql			 = "select hsz.hsz_id ,zh.zon_id as home_zon_id,zh.zon_name as home_zon_name ,zs.zon_id as service_zon_id,zs.zon_name as service_zon_name
					from home_service_zones as hsz
					join zones as zh on hsz.hsz_home_id = zh.zon_id
					join zones as zs on hsz.hsz_service_id = zs.zon_id
					WHERE 1=1 AND zh.zon_active=1 AND hsz.hsz_active = 1 AND  hsz.hsz_home_id = " . $zoneid;
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['id', 'home_zon_name', 'service_zon_name'],
				'defaultOrder'	 => 'service_zon_name ASC'], 'pagination'	 => ['pageSize' => 25],
		]);
		return $dataprovider;
	}

	/*
	 * This function is used to populate multiple selection for the Zone list in manage home service zone page
	 * it populates all the zone except currently allocated service zones
	 * @param int $zoneid
	 * 
	 * return array of data
	 */

	public function getServiceZoneList($zoneid)
	{
		$zoneModels	 = Zones::model()->getZoneList();
		$arrSkill	 = array();
		$sql		 = "select group_concat(hsz_service_id) as serviceid from home_service_zones where hsz_home_id = " . $zoneid." AND hsz_active = 1 ";
		
		$hszones	 = DBUtil::queryScalar($sql);
		if (!empty($hszones))
		{
			$hszonearr	 = explode(",", $hszones);
			$hszonearr[] = $zoneid;
		}
		foreach ($zoneModels as $sklModel)
		{
			if (!in_array($sklModel->zon_id, $hszonearr))
			{
				$arrSkill[$sklModel->zon_id] = $sklModel->zon_name;
			}
		}
		return $arrSkill;
	}

	/*
	 * This function is used to Remove Service zone from the Home service Zone. its just update the active status, not deleted
	 * @param int $hszId
	 * @return result
	 * 
	 */

	public static function removeServiceZone($hszId)
	{
		$sql1	 = "UPDATE home_service_zones SET hsz_active = 0 WHERE hsz_id=:hszId";
		$result	 = DBUtil::execute($sql1, ["hszId" => $hszId]);
		return $result;
	}

	/*
	 * This function is used to Save multiple home service zone to perticular zone id
	 * @param postdata as model
	 *  
	 */

	public function saveHomeServiceZone()
	{
		$returnSet = new ReturnSet();
		try
		{
			$hszonelist = $this->attributes;
			if (empty($hszonelist['hsz_home_id']))
			{
				throw new Exception("Invalid Home zone", ReturnSet::ERROR_INVALID_DATA);
			}
			
			if ($hszonelist['hsz_home_id'] != "" && !empty($hszonelist['hsz_service_id']) && is_array($hszonelist['hsz_service_id']) && count($hszonelist['hsz_service_id']) > 0)
			{
				foreach ($hszonelist['hsz_service_id'] as $serviceid)
				{
					$qry = " select * from home_service_zones  where hsz_service_id = ".$serviceid." and hsz_home_id = ".$hszonelist['hsz_home_id'];
					$hszobj	 = DBUtil::queryRow($qry);
					if(!empty($hszobj)){
						$hszoneobj = HomeServiceZones::model()->findbyPk($hszobj['hsz_id']);
						$hszoneobj->hsz_active = 1;						
					}
					else{
					$hszoneobj					 = new HomeServiceZones();
					$hszoneobj->hsz_home_id		 = $hszonelist['hsz_home_id'];
					$hszoneobj->hsz_active		 = $hszonelist['hsz_active'];
					$hszoneobj->hsz_service_id	 = $serviceid;
					}
					
					if (!($hszoneobj->save()))
					{
						throw new Exception(json_encode($hszoneobj->getErrors()), ReturnSet::ERROR_VALIDATION);
					}
				}
			}
			$returnSet->setStatus(true);
			$returnSet->setData($hszoneobj->hsz_id);
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnSet->setException($ex);
		}
		return $returnSet;
	}

}

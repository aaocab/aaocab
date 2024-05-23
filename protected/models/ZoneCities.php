<?php

/**
 * This is the model class for table "zone_cities".
 *
 * The followings are the available columns in table 'zone_cities':
 * @property integer $zct_id
 * @property integer $zct_zon_id
 * @property integer $zct_cty_id
 * @property integer $zct_active
 * @property string $zct_created_at
 * @property integer $zct_masterzone_id
 * @property integer $zct_region_id
 *
 * The followings are the available model relations:
 * @property Zones $zctZon
 * @property Cities $zctCty
 */
class ZoneCities extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'zone_cities';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('zct_zon_id, zct_cty_id', 'required'),
            array('zct_zon_id, zct_cty_id, zct_active', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('zct_id, zct_zon_id, zct_cty_id, zct_active, zct_created_at,zct_masterzone_id,zct_region_id', 'safe', 'on' => 'search'),
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
            'zctZon' => array(self::BELONGS_TO, 'Zones', 'zct_zon_id'),
            'zctCty' => array(self::BELONGS_TO, 'Cities', 'zct_cty_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'zct_id'         => 'Zct',
            'zct_zon_id'     => 'Zct Zon',
            'zct_cty_id'     => 'Zct Cty',
            'zct_active'     => '1 => Active, 0 => Inactive',
            'zct_created_at' => 'Zct Created At',
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

        $criteria->compare('zct_id', $this->zct_id);
        $criteria->compare('zct_zon_id', $this->zct_zon_id);
        $criteria->compare('zct_cty_id', $this->zct_cty_id);
        $criteria->compare('zct_active', $this->zct_active);
        $criteria->compare('zct_created_at', $this->zct_created_at, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ZoneCities the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /*
      public function getCityList() {
      $criteria = new CDbCriteria();
      $criteria->order = "zon_name";
      $criteria->with = ['zctZon'];
      $criteria->compare('zon_active', 1);
      $criteria->with = ['zctCty' => ['select' => 'cty_name']];
      return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
      ));
      }

     */

    public function getCityByZone($zone_id)
    {
        $cdb              = Yii::app()->db->createCommand();
        $cdb->select      = "cty_id, cty_name";
        $cdb->from        = "zone_cities";
        $cdb->leftJoin("cities", "cities.cty_id = zone_cities.zct_cty_id");
        $cdb->where("zct_zon_id IN ($zone_id) AND cty_id IS NOT NULL");
        $cdb->order       = "cty_name";
        $param[':zoneid'] = $zone_id;
        return $cdb->queryAll($param);
    }

    /** @deprecated use ZoneCities::getCitiesByZones() */
    public function getCityListByZoneId($zoneId)
    {
        $qry       = "SELECT GROUP_CONCAT(zct_cty_id) as cities FROM zone_cities WHERE zct_zon_id=$zoneId AND zct_active=1";
        $recordset = DBUtil::queryAll($qry);
        return $recordset;
    }

    public static function getCitiesByZones($zoneIds)
    {
        $key  = "getCitiesByZones_$zoneIds";
        $data = Yii::app()->cache->get($key);
        if ($data !== false)
        {
            goto result;
        }

        $qry  = "SELECT GROUP_CONCAT(DISTINCT zct_cty_id) as cities FROM zone_cities		
						INNER JOIN zones ON zct_zon_id=zon_id AND zon_active=1
						INNER JOIN cities ON cty_id=zct_cty_id AND cty_active=1
						WHERE zct_zon_id IN ({$zoneIds}) AND zct_active=1";
        $data = DBUtil::queryScalar($qry, DBUtil::SDB(), [], 60*60*24, CacheDependency::Type_Zones);
		
        Yii::app()->cache->set($key, $data, 60 * 60 * 24 * 1, new CacheDependency("Routes"));
        result:
        return $data;
    }

    public function getCityBasedOnZoneId($zoneId)
    {
        $qry = "SELECT GROUP_CONCAT(cities.cty_name) as cities FROM zone_cities join cities on zone_cities.zct_cty_id = cities.cty_id  WHERE zct_zon_id=$zoneId AND zct_active=1";
        return DBUtil::queryRow($qry);
    }

    public static function getZonesByCity($city_id)
    {
        $qry    = "SELECT GROUP_CONCAT(zct_zon_id) as zones FROM zone_cities 
						INNER JOIN zones ON zon_id=zct_zon_id AND zon_active=1
						WHERE zct_cty_id=:city AND zct_active=1";
        $params = [":city" => $city_id];
        $zones  = DBUtil::queryScalar($qry, DBUtil::SDB(), $params, 60*60*24, CacheDependency::Type_Zones);
        return $zones;
    }

    public function findZoneByCity($city_id)
    {
        $qry       = "SELECT GROUP_CONCAT(zct_zon_id) as zones FROM zone_cities WHERE zct_cty_id=$city_id AND zct_active=1";
        $recordset = DBUtil::command($qry)->queryScalar();
        return $recordset;
    }
    public function findZoneByCityes($city_ids)
    {
        $qry       = "SELECT GROUP_CONCAT(zct_zon_id) as zones FROM zone_cities WHERE zct_cty_id IN($city_ids) AND zct_active=1";
        $recordset = DBUtil::command($qry)->queryScalar();
        return $recordset;
    }

    public function getApplyByZone($city_id, $flag)
    {
        $zones        = self::getZonesByCity($city_id);
        $promoCounter = true;
        $coinCounter  = true;
        $codCounter   = true;
        foreach ($zones as $key => $value)
        {
            $zoneModel = Zones::model()->findByPk($value);
            if ($flag == 1)
            {
                if ($zoneModel->zon_is_promo_code_apply == 0)
                {
                    $promoCounter = false;
                    return $promoCounter;
                }
            }
            if ($flag == 2)
            {
                if ($zoneModel->zon_is_promo_gozo_coins_apply == 0)
                {
                    $coinCounter = false;
                    return $coinCounter;
                }
            }
            if ($flag == 3)
            {
                if ($zoneModel->zon_is_cod_apply == 0)
                {
                    $codCounter = false;
                    return $codCounter;
                }
            }
        }
        return true;
    }

    public function getZoneByCities($city_ids)
    {
        $ids       = implode(',', $city_ids);
        $qry       = "SELECT GROUP_CONCAT(DISTINCT zct_zon_id) as zones FROM zone_cities WHERE zct_cty_id IN ($ids) AND zct_active=1 GROUP BY zct_active=1";
        $recordset = DBUtil::queryRow($qry);
        return $recordset;
    }

    public function findZoneByCitiesState($city_ids)
    {
        $ids       = implode(',', $city_ids);
        $qry       = "SELECT GROUP_CONCAT( DISTINCT stt.stt_zone) as zones FROM cities cty JOIN states stt ON cty.cty_state_id=stt.stt_id"
                . " WHERE cty.cty_id IN ($ids) GROUP BY stt.stt_active='1' ";
        $recordset = DBUtil::queryRow($qry);
        return $recordset;
    }

    public function getByZoneCity($zone_id, $city_id)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('zct_cty_id', $city_id);
        $criteria->compare('zct_zon_id', $zone_id);
        return $this->find($criteria);
        //$model = self::model()->resetScope()->find($criteria);
        //return $model;
    }

    public function add($zones, $cty_id)
    {
        $zonesstring = implode(',', $zones);
        $qry         = "UPDATE zone_cities SET zct_active = 0 WHERE zct_cty_id = $cty_id AND zct_zon_id NOT IN ($zonesstring)";
        $recordset1  = DBUtil::command($qry)->execute();
        foreach ($zones as $value)
        {
            $zctModel = ZoneCities::model()->getByZoneCity($value, $cty_id);
            if (!$zctModel)
            {
                $zctModel = new ZoneCities();
            }
            $zctModel->zct_zon_id = (int) $value;
            $zctModel->zct_cty_id = (int) $cty_id;
            $zctModel->zct_active = 1;
            $zctModel->save();
        }
    }

    public static function addZoneCities($ctyId)
    {
        $zoneDatas = Zones::model()->getNearestZonesbyCity($ctyId, 50);
        foreach ($zoneDatas as $key => $zoneData)
        {
            $zctModel = ZoneCities::model()->getByZoneCity($zoneData['zoneIds'], $ctyId);
            if (!$zctModel)
            {
                $zctModel = new ZoneCities();
            }
            $zctModel->zct_zon_id = (int) $zoneData['zoneIds'];
            $zctModel->zct_cty_id = (int) $ctyId;
            $zctModel->zct_active = 1;
            $zctModel->save();
        }
    }

    public function getExcludedCabTypes($cityId)
    {
        $zones   = self::getZonesByCity($cityId);
        $counter = 0;
        $cabs    = '';
		if($zones!='' && !is_array($zones)){
			$zones = explode(',',$zones);
		}
        foreach ($zones as $key => $value)
        {
            $zoneModel = Zones::model()->findByPk($value);
            if ($counter > 0 && $cabs != '')
            {
                $cabs = $cabs . ',';
            }
            $cabs = $cabs . $zoneModel->zon_excluded_cabtypes;
            $counter++;
        }
        $cabTypeArray = [];
        if ($cabs != '')
        {
            $cabTypeArray = explode(',', $cabs);
        }
        return $cabTypeArray;
    }

    public function getZonByCtyId($cityId)
    {
        $sql       = "SELECT zon_id, zon_name,zct_masterzone_id FROM zones
				INNER JOIN zone_cities ON zone_cities.zct_zon_id = zones.zon_id AND zone_cities.zct_active =1
				WHERE zone_cities.zct_cty_id = $cityId AND zones.zon_active=1";
        $recordset = DBUtil::queryRow($sql);
        return $recordset;
    }

    public function getZonIdByCtyId($ctyId)
    {
        $sql       = "SELECT zct_zon_id FROM zone_cities WHERE zct_cty_id = $ctyId";
        $recordset = DBUtil::queryRow($sql);
        return $recordset;
    }

    public static function getRelatedcities($cityId)
    {
        $key = "relatedCitiesForCity_{$cityId}";

        $recordset = Yii::app()->cache->get($key);
        if ($recordset !== false)
        {
            goto result;
        }
        $param     = ['id' => $cityId];
        $sql       = "SELECT SUBSTRING_INDEX(GROUP_CONCAT(zc1.zct_cty_id),',',10) AS all_city, cty_name, zones.zon_name FROM zone_cities
				  INNER JOIN zones ON zones.zon_id = zone_cities.zct_zon_id AND zone_cities.zct_active=1 AND zones.zon_active=1 
						  AND zone_cities.zct_cty_id=:id
				  INNER JOIN zone_cities zc1 ON zones.zon_id = zc1.zct_zon_id AND zc1.zct_active=1
				  INNER JOIN cities ON zc1.zct_cty_id=cty_id";
        $recordset = DBUtil::queryRow($sql, DBUtil::SDB(), $param);
        Yii::app()->cache->set($key, $recordset, 60 * 60, new CacheDependency("zones"));

        result:
        return $recordset;
    }

    public function getZoneByCityId($city_id)
    {
        $qry       = "SELECT zct_zon_id FROM zone_cities WHERE zct_cty_id=$city_id AND zct_active=1";
        $recordset = DBUtil::command($qry)->queryColumn();
        return $recordset;
    }

    public function getMZoneByCityId($cty_id)
    {
        $qry = "SELECT GROUP_CONCAT(DISTINCT zone_cities.zct_masterzone_id) as mzones FROM zone_cities WHERE zct_cty_id=:cty_id AND zct_active=1";
        return DBUtil::queryScalar($qry, DBUtil::SDB(), ['cty_id' => $cty_id]);
    }

	public function getCitiesByZoneId($zoneId)
    {
        $qry       = "SELECT GROUP_CONCAT(zct_cty_id) as cities FROM zone_cities WHERE zct_zon_id=:zoneid AND zct_active=1";
        return DBUtil::queryScalar($qry, DBUtil::SDB(), ['zoneid' => $zoneId]);
    }
}

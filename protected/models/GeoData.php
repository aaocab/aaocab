<?php

/**
 * This is the model class for table "geo_data".
 *
 * The followings are the available columns in table 'geo_data':
 * @property integer $gdt_id
 * @property integer $gdt_city_id
 * @property string $gdt_type
 * @property double $gdt_lat
 * @property double $gdt_lng
 * @property string $gdt_coordinates
 * @property string $gdt_polygon
 * @property string $gdt_area
 * @property string $gdt_geometryText
 * @property string $gdt_geometryType
 * @property string $gdt_properties
 * @property string $gdt_admin_level
 * @property string $gdt_name
 * @property string $gdt_local_name
 * @property string $gdt_name_en
 */
class GeoData extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'geo_data';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('gdt_area', 'required'),
			array('gdt_city_id', 'numerical', 'integerOnly' => true),
			array('gdt_lat, gdt_lng', 'numerical'),
			array('gdt_type', 'length', 'max' => 50),
			array('gdt_area', 'length', 'max' => 20),
			array('gdt_geometryType', 'length', 'max' => 100),
			array('gdt_admin_level', 'length', 'max' => 10),
			array('gdt_name, gdt_local_name, gdt_name_en', 'length', 'max' => 250),
			array('gdt_coordinates, gdt_polygon, gdt_geometryText, gdt_properties', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('gdt_id, gdt_city_id, gdt_type, gdt_lat, gdt_lng, gdt_coordinates, gdt_polygon, gdt_area, gdt_geometryText, gdt_geometryType, gdt_properties, gdt_admin_level, gdt_name, gdt_local_name, gdt_name_en', 'safe', 'on' => 'search'),
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
			'gdt_id'			 => 'Gdt',
			'gdt_city_id'		 => 'Gdt City',
			'gdt_type'			 => 'Gdt Type',
			'gdt_lat'			 => 'Gdt Lat',
			'gdt_lng'			 => 'Gdt Lng',
			'gdt_coordinates'	 => 'Gdt Coordinates',
			'gdt_polygon'		 => 'Gdt Polygon',
			'gdt_area'			 => 'Gdt Area',
			'gdt_geometryText'	 => 'Gdt Geometry Text',
			'gdt_geometryType'	 => 'Gdt Geometry Type',
			'gdt_properties'	 => 'Gdt Properties',
			'gdt_admin_level'	 => 'Gdt Admin Level',
			'gdt_name'			 => 'Gdt Name',
			'gdt_local_name'	 => 'Gdt Local Name',
			'gdt_name_en'		 => 'Gdt Name En',
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

		$criteria->compare('gdt_id', $this->gdt_id);
		$criteria->compare('gdt_city_id', $this->gdt_city_id);
		$criteria->compare('gdt_type', $this->gdt_type, true);
		$criteria->compare('gdt_lat', $this->gdt_lat);
		$criteria->compare('gdt_lng', $this->gdt_lng);
		$criteria->compare('gdt_coordinates', $this->gdt_coordinates, true);
		$criteria->compare('gdt_polygon', $this->gdt_polygon, true);
		$criteria->compare('gdt_area', $this->gdt_area, true);
		$criteria->compare('gdt_geometryText', $this->gdt_geometryText, true);
		$criteria->compare('gdt_geometryType', $this->gdt_geometryType, true);
		$criteria->compare('gdt_properties', $this->gdt_properties, true);
		$criteria->compare('gdt_admin_level', $this->gdt_admin_level, true);
		$criteria->compare('gdt_name', $this->gdt_name, true);
		$criteria->compare('gdt_local_name', $this->gdt_local_name, true);
		$criteria->compare('gdt_name_en', $this->gdt_name_en, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GeoData the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getByPlaceObj(Stub\common\Place $placeObj)
	{
		$data = false;
		try
		{
			$params	 = ["lat"	 => $placeObj->coordinates->latitude,
				"lng"	 => $placeObj->coordinates->longitude,
				"bounds" => $placeObj->bounds];
			$sql	 = "SELECT gd.gdt_id, gd.gdt_local_name, 
							ST_CONTAINS(gd.gdt_polygon, POINT(:lng, :lat)) as CoordContains,
							ST_CONTAINS(gd.gdt_polygon, GetPolygonByBounds(:bounds)) as PolyContains, 
							MBRContains(gd.gdt_polygon, GetPolygonByBounds(:bounds)) as MBRPolyContains 
						FROM geo_data gd WHERE gd.gdt_admin_level=6
								AND gd.gdt_lat BETWEEN :lat-0.6 AND :lat+0.6
								AND gd.gdt_lng BETWEEN :lng-0.6 AND :lng+0.6
								AND ST_INTERSECTS(gd.gdt_polygon, GETPolygonByBounds(:bounds))
						ORDER BY CoordContains DESC, PolyContains DESC, MBRPolyContains DESC
					";

			$res = DBUtil::query($sql, DBUtil::SDB(), $params);

			$count = $res->getRowCount();
			foreach ($res as $row)
			{
				if ($row["CoordContains"] == 1 && ($row["PolyContains"] == 1 || $count == 1))
				{
					$data = $row;
					break;
				}

				if ($row["CoordContains"] == 1 && ($row["MBRPolyContains"] == 1))
				{
					$data = $row;
				}
				else if ($data !== false)
				{
					break;
				}
			}
			if ($data)
			{
				goto result;
			}

			$sql	 = "SELECT gd.gdt_id, gd.gdt_local_name, 
					ST_CONTAINS(gd.gdt_polygon, POINT(:lng, :lat)) as CoordContains,
					ST_CONTAINS(gd.gdt_polygon, GetPolygonByBounds(:bounds)) as PolyContains, 
					MBRContains(gd.gdt_polygon, GetPolygonByBounds(:bounds)) as MBRPolyContains 
				FROM geo_data gd
				INNER JOIN (
                        SELECT MAX(ST_AREA(ST_INTERSECTION(gd.gdt_polygon, GetPolygonByBounds(:bounds)))) as maxArea 
						FROM geo_data gd 
						WHERE gd.gdt_admin_level=6
								AND gd.gdt_lat BETWEEN :lat-0.6 AND :lat+0.6
								AND gd.gdt_lng BETWEEN :lng-0.6 AND :lng+0.6
								AND ST_INTERSECTS(gd.gdt_polygon, GETPolygonByBounds(:bounds))
                    ) a ON (a.maxArea*0.7)<(ST_AREA(ST_INTERSECTION(gd.gdt_polygon, GetPolygonByBounds(:bounds))))  
						AND ST_CONTAINS(gd.gdt_polygon, POINT(:lng, :lat))
				WHERE gd.gdt_admin_level=6
						AND gd.gdt_lat BETWEEN :lat-0.6 AND :lat+0.6
						AND gd.gdt_lng BETWEEN :lng-0.6 AND :lng+0.6
						AND ST_INTERSECTS(gd.gdt_polygon, GETPolygonByBounds(:bounds)) 
			";
			$data	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
			if ($data)
			{
				goto result;
			}

			$sql	 = "SELECT gd.gdt_id, gd.gdt_local_name, 
							ST_CONTAINS(gd.gdt_polygon, POINT(:lng, :lat)) as CoordContains,
							ST_CONTAINS(gd.gdt_polygon, GetPolygonByBounds(:bounds)) as PolyContains, 
							MBRContains(gd.gdt_polygon, GetPolygonByBounds(:bounds)) as MBRPolyContains 
						FROM geo_data gd
						INNER JOIN (
								SELECT MAX(ST_AREA(ST_INTERSECTION(gd.gdt_polygon, GetPolygonByBounds(:bounds)))) as maxArea 
								FROM geo_data gd 
								WHERE gd.gdt_admin_level=6
										AND gd.gdt_lat BETWEEN :lat-0.6 AND :lat+0.6
										AND gd.gdt_lng BETWEEN :lng-0.6 AND :lng+0.6
										AND ST_INTERSECTS(gd.gdt_polygon, GETPolygonByBounds(:bounds))
							) a ON (a.maxArea)=(ST_AREA(ST_INTERSECTION(gd.gdt_polygon, GetPolygonByBounds(:bounds))))
						WHERE gd.gdt_admin_level=6
								AND gd.gdt_lat BETWEEN :lat-0.6 AND :lat+0.6
								AND gd.gdt_lng BETWEEN :lng-0.6 AND :lng+0.6
								AND ST_INTERSECTS(gd.gdt_polygon, GETPolygonByBounds(:bounds))
					";
			$data	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		}
		catch (Exception $exc)
		{
			Logger::exception($exc);
		}

		result:
		return $data;
	}

	public static function getPolygonBoundsByCityid($ctyid)
	{
		$params	 = ["ctyid" => $ctyid];
		$sql	 = "SELECT ST_AsGeoJSON(GetPolygonByBounds(cty_bounds))   
					FROM cities WHERE cty_id=:ctyid ";
		$data	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $data;
	}

	public static function getPolygonTextByCity($ctyid)
	{

		$data = GeoData::getPolygonBoundsByCityid($ctyid);

		$dataArr = json_decode($data);
		$ltdata	 = $dataArr->coordinates[0];
		$ltStr	 = '';
		foreach ($ltdata as $k => $ltval)
		{
			if ($k != 0)
			{
				$ltStr .= ' ';
			}
			$ltStr .= implode(', ', $ltval);
		}
		return $ltStr;
	}

}

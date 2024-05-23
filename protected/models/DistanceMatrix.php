<?php

/**
 * This is the model class for table "distance_matrix".
 *
 * The followings are the available columns in table 'distance_matrix':
 * @property integer $dmx_id
 * @property integer $dmx_source_ltg_id
 * @property integer $dmx_destination_ltg_id
 * @property integer $dmx_distance
 * @property integer $dmx_duration
 * @property integer $dmx_active
 * @property string $dmx_created_on
 * @property LatLong $dmxSource
 * @property LatLong $dmxDestination
 */
class DistanceMatrix extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'distance_matrix';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dmx_source_ltg_id, dmx_destination_ltg_id, dmx_distance, dmx_duration', 'required'),
			array('dmx_source_ltg_id, dmx_destination_ltg_id, dmx_distance, dmx_duration, dmx_active', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('dmx_id, dmx_source_ltg_id, dmx_destination_ltg_id, dmx_distance, dmx_duration, dmx_active, dmx_created_on', 'safe', 'on' => 'search'),
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
			'dmxSource'		 => array(self::BELONGS_TO, 'LatLong', 'dmx_source_ltg_id'),
			'dmxDestination' => array(self::BELONGS_TO, 'LatLong', 'dmx_destination_ltg_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'dmx_id'				 => 'Dmx',
			'dmx_source_ltg_id'		 => 'Dmx Source Ltg',
			'dmx_destination_ltg_id' => 'Dmx Destination Ltg',
			'dmx_distance'			 => 'Dmx Distance',
			'dmx_duration'			 => 'Dmx Duration',
			'dmx_active'			 => 'Dmx Active',
			'dmx_created_on'		 => 'Dmx Created On',
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

		$criteria->compare('dmx_id', $this->dmx_id);
		$criteria->compare('dmx_source_ltg_id', $this->dmx_source_ltg_id);
		$criteria->compare('dmx_destination_ltg_id', $this->dmx_destination_ltg_id);
		$criteria->compare('dmx_distance', $this->dmx_distance);
		$criteria->compare('dmx_duration', $this->dmx_duration);
		$criteria->compare('dmx_active', $this->dmx_active);
		$criteria->compare('dmx_created_on', $this->dmx_created_on, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DistanceMatrix the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getRecord($LatLngIdArr, $returnType = 'arr')
	{

		$sourceId	 = $LatLngIdArr[0];
		$destId		 = $LatLngIdArr[1];

		$sql = "SELECT * FROM distance_matrix WHERE dmx_source_ltg_id = '$sourceId' AND dmx_destination_ltg_id = '$destId' AND dmx_active = 1";
		if ($returnType == 'arr')
		{
			$record = DBUtil::queryRow($sql, DBUtil::SDB());
		}
		if ($returnType == 'obj')
		{
			$record = DistanceMatrix::model()->findBySql($sql);
		}

		return $record;
	}

	public static function getBySourceDestPoints($sourceId, $destId)
	{
		$sql = "SELECT * FROM distance_matrix WHERE dmx_source_ltg_id = '$sourceId' AND dmx_destination_ltg_id = '$destId' AND dmx_active = 1";
		return DistanceMatrix::model()->findBySql($sql);
	}

	public function addData($APIres, $LatLngIdArr)
	{
		$sourceId	 = $LatLngIdArr[0];
		$destId		 = $LatLngIdArr[1];
		$distance	 = $APIres['dist'];
		$duration	 = $APIres['time'];

		$model							 = new DistanceMatrix();
		$model->dmx_source_ltg_id		 = $sourceId;
		$model->dmx_destination_ltg_id	 = $destId;
		$model->dmx_distance			 = $distance;
		$model->dmx_duration			 = $duration;
		if ($model->save())
		{
			return true;
		}
	}

	public static function saveData($sourceId, $destId, $distance, $duration)
	{
		$dmxId = self::getDataBySourcDestPoints($sourceId, $destId);
		if ($dmxId != '')
		{
			$model				 = DistanceMatrix::model()->findByPk($dmxId);
			$model->dmx_active	 = 1;
		}
		else
		{
			$model = new DistanceMatrix();
		}
		$model->dmx_source_ltg_id		 = $sourceId;
		$model->dmx_destination_ltg_id	 = $destId;
		$model->dmx_distance			 = $distance;
		$model->dmx_duration			 = $duration;
		if (!$model->save())
		{
			throw new Exception("Error adding distance and time.");
		}
		return $model;
	}

	public static function getDetails($srcLtLngModel, $dstLtLngModel)
	{
		Logger::profile("DistanceMatrix::getDetails {$srcLtLngModel->ltg_id}, {$dstLtLngModel->ltg_id}  Started");

		$distMatrixModel = false;
		if ($srcLtLngModel->ltg_id == $dstLtLngModel->ltg_id)
		{
			goto result;
		}
		$distMatrixModel = DistanceMatrix::getBySourceDestPoints($srcLtLngModel->ltg_id, $dstLtLngModel->ltg_id);
		if (!$distMatrixModel)
		{
			$result = GoogleMapAPI::getInstance()->getDrivingDistancebyLatLong($srcLtLngModel->ltg_lat, $srcLtLngModel->ltg_long, $dstLtLngModel->ltg_lat, $dstLtLngModel->ltg_long);
			if ($result["success"])
			{
				Logger::info("Distance matrix fetched via Google API");
				$distMatrixModel = DistanceMatrix::saveData($srcLtLngModel->ltg_id, $dstLtLngModel->ltg_id, $result['distance'][0]['dist'], $result['distance'][0]['time']);
			}
		}
		result:
		Logger::profile("DistanceMatrix::getDetails {$srcLtLngModel->ltg_id}, {$dstLtLngModel->ltg_id} Done");
		return $distMatrixModel;
	}

	/**
	 * @param float $srcLat - Latitude of source location
	 * @param float $srcLong - Longitude of source location
	 * @param float $destLat - Latitude of destination location
	 * @param float $destLong - Longitude of destination location
	 * @return object - returns DistanceMatrix model object
	 */
	public static function calculate($srcLtLngModel, $dstLtLngModel)
	{
		try
		{
			$distanceMatrixModel = DistanceMatrix::getDetails($srcLtLngModel, $dstLtLngModel);
		}
		catch (Exception $e)
		{
			Logger::error($e);
			throw $e;
		}
		return $distanceMatrixModel;
	}

	/**
	 * @param float $srcLat - Latitude of source location
	 * @param float $srcLong - Longitude of source location
	 * @param float $destLat - Latitude of destination location
	 * @param float $destLong - Longitude of destination location
	 * @return mixed the row (in terms of an array) of the result, false if no result.
	 *  */
	public static function findNearest($srcLat, $srcLong, $destLat, $destLong)
	{
		$params	 = [
			'slat'	 => $srcLat, 'slng'	 => $srcLong,
			'dlat'	 => $destLat, 'dlng'	 => $destLong
		];
		$sql	 = "SELECT *, abs(CalcDistance(sl.ltg_lat, sl.ltg_long, dl.ltg_lat, dl.ltg_long) - CalcDistance(:slat, :slng, :dlat, :dlng)) as diff 
				FROM distance_matrix dmx
				INNER JOIN lat_long sl ON dmx.dmx_source_ltg_id=sl.ltg_id 
						AND sl.ltg_lat BETWEEN (:slat-0.005) AND (:slat+0.005) AND sl.ltg_long BETWEEN (:slng-0.005) AND (:slng+0.005)
						AND CalcDistance(sl.ltg_lat, sl.ltg_long, :slat, :slng)<0.5 AND sl.ltg_active=1
				INNER JOIN lat_long dl ON dmx.dmx_destination_ltg_id=dl.ltg_id
						AND dl.ltg_lat BETWEEN (:dlat-0.005) AND (:dlat+0.005) AND dl.ltg_long BETWEEN (:dlng-0.005) AND (:dlng+0.005)
						AND CalcDistance(dl.ltg_lat, dl.ltg_long, :dlat, :dlng)<=0.5 AND dl.ltg_active=1
				WHERE (dmx_distance>50 OR dmx_created_on>=DATE_SUB(NOW(), INTERVAL 365 DAY)) AND dmx_active=1 HAVING diff <=1 ORDER BY diff ASC
			";

		$row = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $row;
	}

	/**
	 * @param Stub\common\Place $sourcePlace - Source Place object with coordinates
	 * @param Stub\common\Place $destPlace - Destination Place object with coordinates
	 * @return DistanceMatrix|null the record found. Null if none is found.
	 *  */
	public static function getByCoordinates($sourcePlace, $destPlace)
	{
		if ($sourcePlace->coordinates->latitude == null || $destPlace->coordinates->latitude == null)
		{
			goto skipNearest;
		}

		$row = self::findNearest($sourcePlace->coordinates->latitude, $sourcePlace->coordinates->longitude, $destPlace->coordinates->latitude, $destPlace->coordinates->longitude);
		if ($row)
		{
			$dModel = DistanceMatrix::model()->findByPk($row['dmx_id']);
			goto result;
		}

		skipNearest:
		$srcLtLngModel	 = LatLong::model()->getDetailsByPlace($sourcePlace);
		$dstLtLngModel	 = LatLong::model()->getDetailsByPlace($destPlace);
		$dModel			 = null;
		if ($srcLtLngModel && $dstLtLngModel)
		{
			$dModel = self::getDetails($srcLtLngModel, $dstLtLngModel);
		}
		result:
		return $dModel;
	}

	/**
	 * 
	 * @param type $sourceId
	 * @param type $destId
	 * @return id
	 */
	public static function getDataBySourcDestPoints($sourceId, $destId)
	{
		$params	 = ['srcLat' => $sourceId, 'destLat' => $destId];
		$sql	 = "SELECT dmx_id, IF(dmx_active = 1, 99, dmx_active) AS rank
					FROM distance_matrix WHERE dmx_source_ltg_id = :srcLat AND dmx_destination_ltg_id = :destLat ORDER BY rank DESC LIMIT 0,1";
		$dmxId	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $dmxId;
	}

	public static function refreshGoogleAPI($id)
	{
		Logger::writeToConsole("Id: {$id}");
		
		/** @var DistanceMatrix $dModel */
		$dModel = DistanceMatrix::model()->findByPk($id);
		try
		{
			$srcLtLngModel	 = $dModel->dmxSource;
			$dstLtLngModel	 = $dModel->dmxDestination;

			$result = GoogleMapAPI::getInstance()->getDrivingDistancebyLatLong($srcLtLngModel->ltg_lat, $srcLtLngModel->ltg_long, $dstLtLngModel->ltg_lat, $dstLtLngModel->ltg_long);
			if (!$result["success"])
			{
				throw new Exception("Google API Error: {$result['errorCode']}: {$result['errorMessage']}");
			}

			Logger::info("Distance matrix fetched via Google API");

			$dModel->dmx_source_ltg_id		 = $srcLtLngModel->ltg_id;
			$dModel->dmx_destination_ltg_id	 = $dstLtLngModel->ltg_id;
			$dModel->dmx_distance			 = $result['distance'][0]['dist'];
			$dModel->dmx_duration			 = $result['distance'][0]['time'];
			$dModel->dmx_created_on			 = new CDbExpression("NOW()");
			$dModel->dmx_active				 = 1;
			if (!$dModel->save())
			{
				throw new Exception("Error adding distance and time - " . json_encode($dModel->getErrors()));
			}
		}
		catch (Exception $e)
		{
			Logger::writeToConsole("\nFailed: - " . json_encode($row));
			Logger::exception($e);
			$dModel->dmx_active = 2;
			$dModel->save();
		}
		return $dModel;
	}

}

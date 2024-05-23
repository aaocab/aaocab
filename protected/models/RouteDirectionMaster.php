<?php

/**
 * This is the model class for table "route_direction_master".
 *
 * The followings are the available columns in table 'route_direction_master':
 * @property integer $rdm_id
 * @property string $rdm_source_coordinates
 * @property string $rdm_destination_coordinates
 * @property string $rdm_route_json
 * @property integer $rdm_source
 * @property string $rdm_create
 * @property string $rdm_update
 */
class RouteDirectionMaster extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'route_direction_master';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		//	array('rdm_create', 'required'),
			array('rdm_source', 'numerical', 'integerOnly'=>true),
			array('rdm_source_coordinates, rdm_destination_coordinates', 'length', 'max'=>255),
			array('rdm_route_json, rdm_update', 'safe'),
           ['rdm_id', 'validateResult', 'on' => 'uniqueByInput'],
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rdm_id, rdm_source_coordinates, rdm_destination_coordinates, rdm_route_json, rdm_source, rdm_create, rdm_update', 'safe', 'on'=>'search'),
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
			'rdm_id' => 'Rdm',
			'rdm_source_coordinates' => 'Rdm Source Coordinates',
			'rdm_destination_coordinates' => 'Rdm Destination Coordinates',
			'rdm_route_json' => 'Rdm Route Json',
			'rdm_source' => '1:google;2:MMI',
			'rdm_create' => 'Rdm Create',
			'rdm_update' => 'Rdm Update',
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

		$criteria=new CDbCriteria;

		$criteria->compare('rdm_id',$this->rdm_id);
		$criteria->compare('rdm_source_coordinates',$this->rdm_source_coordinates,true);
		$criteria->compare('rdm_destination_coordinates',$this->rdm_destination_coordinates,true);
		$criteria->compare('rdm_route_json',$this->rdm_route_json,true);
		$criteria->compare('rdm_source',$this->rdm_source);
		$criteria->compare('rdm_create',$this->rdm_create,true);
		$criteria->compare('rdm_update',$this->rdm_update,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RouteDirectionMaster the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * 
	 * @return boolean
	 */
	public function validateResult()
	{
		$sql	 = "SELECT COUNT(*) as cnt FROM route_direction_master
                WHERE rdm_source_coordinates = '$this->rdm_source_coordinates' AND rdm_destination_coordinates = '$this->rdm_destination_coordinates'
				AND rdm_source ='$this->rdm_source'";
		$count	 = DBUtil::command($sql)->queryScalar();
		if ($count > 0)
		{
			$this->addError('rdm_id ', "Already Exist with same coordinates with same source");
			return false;
		}
		return true;
	}

	/**
	 * 
	 * @param type $coordinates
	 * @param type $response
	 * @param type $medium
	 * @return \RouteDirectionMaster
	 */
	public static function add($coordinateArr, $response, $medium)
	{
		$model = new RouteDirectionMaster();
		try
		{
			//$var								 = explode(';', $coordinates);
			$model->rdm_source_coordinates		 = $coordinateArr[0]['lat'] . ',' . $coordinateArr[0]['lng'];;
			$model->rdm_destination_coordinates	 = $coordinateArr[1]['lat'] . ',' . $coordinateArr[1]['lng'];
			$model->rdm_route_json				 = json_encode(json_decode($response));
			$model->rdm_source					 = $medium;
			$model->scenario					 = "uniqueByInput";
			$model->save();
		}
		catch (Exception $e)
		{
			$model->addError("rdm_id ", $e->getMessage());
		}
		return $model;
	}
	/**
	 * 
	 * @param string $param
	 * @return type
	 * @throws CHttpException
	 */
	public static function getDirections($param)
	{
		$returnSet = new ReturnSet();
		if (!$param)
		{
			throw new CHttpException(406, "Coordinates not found", 406);
		}
		$data = [];
		try
		{
			$medium	 = Config::get('RouteDirectionAPI.source.default');
			$row	 = self::getData($param, $medium);
			if (!$row)
			{
				/* @var $model RouteDirectionMaster */
				$returnSet = RouteDirectionMaster::getAPIResponse($param, $medium);
				goto end;
			}
			$dbRouts		 = CJSON::decode($row['rdm_route_json']);
			$data['route']	 = $dbRouts['routes'][0];
			$data['medium']	 = $row['rdm_source'];
			$returnSet->setData($data);
			$returnSet->setStatus(true);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			Logger::exception($ex);
		}
		end:
		return $returnSet;
	}

	/**
	 * 
	 * @param string $coordinates
	 * @param int $medium
	 * @return []
	 */
	public static function getData($dataArr, $medium = '')
	{

        $origin		 = $dataArr[0]['lat'] . ',' . $dataArr[0]['lng'];
		$destination = $dataArr[1]['lat'] . ',' . $dataArr[1]['lng'];
		//$coordinates = $origin . ';' . $destination;


		//$var		 = explode(";", $coordinates);
		$where		 = "";
		$condition	 = "";
		$params		 = ['source' => $origin, 'destination' => $destination];

		if ($medium != '')
		{
			$params['medium']	 = $medium;
			$where				 = " AND rdm_source= :medium";
		}
		$condition	 = " ORDER BY rdm_id DESC LIMIT 0,1";
		$sql		 = "SELECT rdm_route_json,rdm_source FROM route_direction_master WHERE rdm_source_coordinates= :source AND rdm_destination_coordinates= :destination {$where} {$condition} ";
		return DBUtil::queryRow($sql, DBUtil::SDB(), $params);
	}

	/**
	 * 
	 * @param string $coordinates
	 * @param int $medium 1:Google|2:MapMyIndia
	 * @return type
	 * @throws CHttpException
	 */
	public static function getAPIResponse($coordinates, $medium = '')
	{
		$returnSet = new ReturnSet();
		if (!$coordinates)
		{
			throw new CHttpException(406, "Coordinates not found", 406);
		}
		try
		{
			if ($medium == 2)
			{
				$response	 = MapMyIndiaApi::getInstance()->getRouteBycoordinates($coordinates);
				$var		 = json_decode($response);
				if ($var->code == "Ok")
				{
					goto saveResponse;
				}
				switch ($var->responsecode)
				{
					case 204:
						$errorMsg	 = "DB Connection error.";
						break;
					case 400:
						$errorMsg	 = "Bad Request, User made an error while creating a valid request.";
						break;
					case 401:
						$errorMsg	 = "Unauthorized, Developer’s key is not allowed to send a request.";
						break;
					case 403:
						$errorMsg	 = "Forbidden, Developer’s key has hit its daily/hourly limit or IP or domain not white-listed.";
						break;
					case 404:
						$errorMsg	 = "HTTP not found";
						break;
					case 412:
						$errorMsg	 = "Precondition Failed, i.e. Mandatory parameter is missing";
						break;
					case 500:
						$errorMsg	 = "Internal Server Error, the request caused an error in our systems.";
						break;
					case 503:
						$errorMsg	 = "Service Unavailable, during our maintenance break or server down-times.";
						break;
					default:
						break;
				}
			
				$returnSet->setErrors([$errorMsg]);
				$returnSet->setErrorCode($var->responsecode);
				$returnSet->setMessage($var->error_description);

				goto returnEnd;
			}

			if ($medium == 1)
			{

				$response	 = GoogleMapAPI::getInstance()->getRouteBycoordinates($coordinates);
				$var		 = json_decode($response);

				if ($var->status == "OK")
				{
					goto saveResponse;
				}
				$errorMsg = ($var->error_message == '') ? "At least one of the locations specified in the request's origin, destination, or waypoints could not be geocoded" : $var->error_message;
				$returnSet->setErrors([$errorMsg]);
				$returnSet->setErrorCode(ReturnSet::ERROR_NO_RECORDS_FOUND);
				$returnSet->setMessage($errorMsg);
				goto returnEnd;
			}
			saveResponse:
			$model = self::add($coordinates, $response, $medium);
			if ($model)
			{
				$dbRouts		 = CJSON::decode($model->rdm_route_json);
				$data['route']	 = $dbRouts['routes'][0];
				$data['medium']	 = $model->rdm_source;

				$returnSet->setData($data);
				$returnSet->setStatus(true);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			Logger::exception($ex);
		}
		returnEnd:
		return $returnSet;
	}
}


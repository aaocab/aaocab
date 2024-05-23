<?php

/**
 * This is the model class for table "api_tracking".
 *
 * The followings are the available columns in table 'api_tracking':
 * @property integer $atr_id
 * @property string $atr_url
 * @property string $atr_session_id
 * @property string $atr_request_params
 * @property string $atr_api_url
 * @property string $atr_api_params
 * @property string $atr_response
 * @property string $atr_stack_trace
 * @property string $atr_date
 * @property integer $atr_platform
 * @property integer $atr_type
 */
class ApiTracking extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'api_tracking';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
		return array(
			array('atr_stack_trace, atr_date, atr_platform', 'required'),
			array('atr_platform, atr_type', 'numerical', 'integerOnly' => true),
			array('atr_url', 'length', 'max' => 255),
			array('atr_session_id, atr_api_url', 'length', 'max' => 100),
			array('atr_request_params, atr_response, atr_stack_trace', 'length', 'max' => 2000),
			array('atr_api_params', 'length', 'max' => 500),
			// The following rule is used by search().
// @todo Please remove those attributes that should not be searched.
			array('atr_id, atr_url, atr_session_id, atr_request_params, atr_api_url, atr_api_params, atr_response, atr_stack_trace, atr_date, atr_platform, atr_type', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
// NOTE: you may need to adjust the relation name and the related
// class name for the relations automatically generated below.
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'atr_id'			 => 'Atr',
			'atr_url'			 => 'Atr Url',
			'atr_session_id'	 => 'Atr Session',
			'atr_request_params' => 'Atr Request Params',
			'atr_api_url'		 => 'Atr Api Url',
			'atr_api_params'	 => 'Atr Api Params',
			'atr_response'		 => 'Atr Response',
			'atr_stack_trace'	 => 'Atr Stack Trace',
			'atr_date'			 => 'Atr Date',
			'atr_platform'		 => 'Atr Platform',
			'atr_type'			 => 'Atr Type',
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

		$criteria->compare('atr_id', $apiTracking->atr_id);
		$criteria->compare('atr_url', $apiTracking->atr_url, true);
		$criteria->compare('atr_session_id', $apiTracking->atr_session_id, true);
		$criteria->compare('atr_request_params', $apiTracking->atr_request_params, true);
		$criteria->compare('atr_api_url', $apiTracking->atr_api_url, true);
		$criteria->compare('atr_api_params', $apiTracking->atr_api_params, true);
		$criteria->compare('atr_response', $apiTracking->atr_response, true);
		$criteria->compare('atr_stack_trace', $apiTracking->atr_stack_trace, true);
		$criteria->compare('atr_date', $apiTracking->atr_date, true);
		$criteria->compare('atr_platform', $apiTracking->atr_platform);
		$criteria->compare('atr_type', $apiTracking->atr_type);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ApiTracking the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function callStack($stacktrace)
	{

		$i					 = 1;
		$stacktracestring	 = "";
		foreach ($stacktrace as $node)
		{
			$stacktracestring .= "$i. " . basename($node['file']) . ":" . $node['function'] . "(" . $node['line'] . ")\n";
			$i++;
		}
		return $stacktracestring;
	}

	public static function add($url, $type)
	{
		try
		{
			$apiTracking					 = new ApiTracking();
			$apiTracking->atr_date			 = DBUtil::getCurrentTime();
			$apiTracking->atr_type			 = $type;
			$apiTracking->atr_stack_trace	 = substr(json_encode(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 6)), 0, 1900);
			$apiTracking->atr_api_params	 = substr(parse_url($url, PHP_URL_QUERY), 0, 450);
			$apiTracking->atr_api_url		 = substr($url, 0, 100);


			if (UserInfo::getUserType() == 10)
			{
				$apiTracking->atr_platform		 = 3;
				$apiTracking->atr_request_params = substr($_SERVER['argv'][$_SERVER['argc'] - 1], 0, 1900);
				$apiTracking->atr_url			 = $_SERVER['argv'][$_SERVER['argc'] - 2];
			}
			else if (UserInfo::getUserType() != null)
			{
				$apiTracking->atr_platform		 = 1;
				$apiTracking->atr_request_params = substr(json_encode($_REQUEST), 0, 1900);
				$apiTracking->atr_url			 = Yii::app()->request->requestUri;
				$apiTracking->atr_session_id	 = Yii::app()->getSession()->getSessionId();
			}
			else
			{
				$apiTracking->atr_platform		 = 2;
				$apiTracking->atr_request_params = substr(Yii::app()->request->rawBody, 0, 1900);
				if (empty(Yii::app()->request->rawBody))
				{
					$apiTracking->atr_api_params = substr(Yii::app()->request->getParam('data'), 0, 1900);
				}
				$apiTracking->atr_url		 = Yii::app()->request->requestUri;
				$apiTracking->atr_session_id = Yii::app()->getSession()->getSessionId();
			}

			if (Yii::app() instanceof CWebApplication && !empty(Yii::app()->request->rawBody))
			{
				$apiTracking->atr_request_params = substr(Yii::app()->request->rawBody, 0, 1900);
			}
			
			if ($apiTracking->validate())
			{
				$apiTracking->save();
				$apiTracking->refresh();
				return $apiTracking->atr_id;
			}
			else
			{
				return 0;
			}
		}
		catch (Exception $ex)
		{
			return 0;
		}
	}

	public static function updates($atr_id, $response)
	{
		try
		{
			$apiTracking				 = ApiTracking::model()->findByPk($atr_id);
			$apiTracking->atr_response	 = substr($response, 0, 2000);
			if ($apiTracking->validate())
			{
				$apiTracking->save();
			}
		}
		catch (Exception $ex)
		{
			
		}
	}

	/**
	 * Function for archiving API Tracking
	 */
	public function archiveData($archiveDB, $upperLimit = 1000000, $lowerLimit = 1000)
	{
		$i			 = 0;
		$chk		 = true;
		$totRecords	 = $upperLimit;
		$limit		 = $lowerLimit;
		while ($chk)
		{
			$transaction = "";
			try
			{
				$sql	 = "SELECT GROUP_CONCAT(atr_id) AS atr_id FROM (SELECT atr_id FROM api_tracking WHERE 1 AND atr_date < CONCAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH), ' 00:00:00') ORDER BY atr_id  LIMIT 0, $limit) as temp";
				$resQ	 = DBUtil::queryScalar($sql);
				if (!is_null($resQ) && $resQ != '')
				{
					$transaction = DBUtil::beginTransaction();
					DBUtil::getINStatement($resQ, $bindString, $params);
					$sql		 = "INSERT INTO " . $archiveDB . ".api_tracking (SELECT * FROM api_tracking WHERE atr_id IN ($bindString))";
					$rows		 = DBUtil::execute($sql, $params);
					if ($rows > 0)
					{
						$sql = "DELETE FROM `api_tracking` WHERE atr_id IN ($bindString)";
						DBUtil::execute($sql, $params);
						DBUtil::commitTransaction($transaction);
					}
					else
					{
						DBUtil::rollbackTransaction($transaction);
					}
				}

				$i += $limit;
				if (($resQ <= 0) || $totRecords <= $i)
				{
					break;
				}
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::exception($ex);
				echo $ex->getMessage() . "\n\n";
			}
		}
	}

}

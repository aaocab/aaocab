<?php

/**
 * This is the model class for table "drv_unsync_log".
 *
 * The followings are the available columns in table 'drv_unsync_log':
 * @property integer $dul_id
 * @property integer $dul_drv_id
 * @property integer $dul_user_id
 * @property integer $dul_bkg_id
 * @property integer $dul_event_id
 * @property string $dul_drv_appToken
 * @property string $dul_data
 * @property string $dul_url
 * @property integer $dul_status
 * @property string $dul_error
 * @property string $dul_event_date
 * @property string $dul_created_date
 */
class DrvUnsyncLog extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'drv_unsync_log';
	}
	public $searchDrvName;
		public $search;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dul_bkg_id, dul_event_id', 'required'),
			array('dul_drv_id, dul_user_id, dul_bkg_id, dul_event_id, dul_status', 'numerical', 'integerOnly' => true),
			array('dul_drv_appToken, dul_url', 'length', 'max' => 100),
			array('dul_data, dul_error, dul_event_date, dul_created_date', 'safe'),
['dul_bkg_id,dul_event_id', 'validateStoreError', 'on' => 'validateStoreError'],
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('dul_id, dul_drv_id, dul_user_id, dul_bkg_id, dul_event_id, dul_drv_appToken, dul_data, dul_url, dul_status, dul_error, dul_event_date, dul_created_date', 'safe', 'on' => 'search'),
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
			'dul_id'			 => 'Dul',
			'dul_drv_id'		 => 'Dul Drv',
			'dul_user_id'		 => 'Dul User',
			'dul_bkg_id'		 => 'Dul Bkg',
			'dul_event_id'		 => 'Dul Event',
			'dul_drv_appToken'	 => 'Dul Drv App Token',
			'dul_data'			 => 'Dul Data',
			'dul_url'			 => 'Dul Url',
			'dul_status'		 => 'Dul Status',
			'dul_error'			 => 'Dul Error',
			'dul_event_date'	 => 'Dul Event Date',
			'dul_created_date'	 => 'Dul Created Date',
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

		$criteria->compare('dul_id', $this->dul_id);
		$criteria->compare('dul_drv_id', $this->dul_drv_id);
		$criteria->compare('dul_user_id', $this->dul_user_id);
		$criteria->compare('dul_bkg_id', $this->dul_bkg_id);
		$criteria->compare('dul_event_id', $this->dul_event_id);
		$criteria->compare('dul_drv_appToken', $this->dul_drv_appToken, true);
		$criteria->compare('dul_data', $this->dul_data, true);
		$criteria->compare('dul_url', $this->dul_url, true);
		$criteria->compare('dul_status', $this->dul_status);
		$criteria->compare('dul_error', $this->dul_error, true);
		$criteria->compare('dul_event_date', $this->dul_event_date, true);
		$criteria->compare('dul_created_date', $this->dul_created_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DrvUnsyncLog the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	public function validateStoreError($param="")
	{
$param	 = ['dul_bkg_id' => $this->dul_bkg_id, 'dul_event_id' => $this->dul_event_id];
		$sql = "SELECT COUNT(*) as cnt FROM drv_unsync_log
                WHERE dul_bkg_id=:dul_bkg_id  AND dul_event_id = :dul_event_id ";
		$count=  DBUtil::queryScalar($sql, null, $param);


		if ($count > 0)
		{
			$this->addError('dul_bkg_id', "Error with this bookingID and this event  Already Exist");
			return false;
		}
		return true;
	}

	public function add($bkg, $event, $userInfo, $syncDetails, $eventResponse, $hitUrl)
	{
		$returnSet = new ReturnSet();

		//$transaction = DBUtil::beginTransaction();
		try
		{
			if ($eventResponse->getMessage())
			{
				$var = $eventResponse->getMessage();
			}
			else
			{
				$var = CJSON::encode($eventResponse->getErrors());
			}

			$returnSet->setStatus(false);
			$model					 = new DrvUnsyncLog();
			$model->dul_drv_id		 = UserInfo::getEntityId();
			$model->dul_user_id		 = UserInfo::getUserId();
			$model->dul_bkg_id		 = $bkg;
			$model->dul_event_id	 = $event;
			$model->dul_drv_appToken = Yii::app()->request->getAuthorizationCode();
			$model->dul_data		 = $syncDetails;
			$model->dul_url			 = $hitUrl;
			$model->dul_status		 = 0;
			$model->dul_error		 = $var;
			$model->dul_event_date	 = new CDbExpression("NOW()");
			$model->scenario		 = "validateStoreError";
			if ($model->save())
			{
				$returnSet->setStatus(true);
			}
			else
			{
				
                $returnSet->setErrors($model->errors);
				$returnSet->setStatus(false);
			}
			//DBUtil::commitTransaction($transaction);
		}
		catch (Exception $exc)
		{
			throw new Exception("Failed to save driver synclog.", ReturnSet::ERROR_FAILED);
			//DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	public function getList($search=false, $searchDrvName= false)
	{
		$pageSize = 50;
		$where = "";
		if ($search != '')
		{
			$where .=  "AND ((dul.dul_bkg_id LIKE '%" . trim($search) . "%') OR (bkg.bkg_booking_id LIKE '%" . trim($search) . "%'))";
		}
		 if ($searchDrvName != '')
		{
			$where .=  "AND (drv_name LIKE '%" . trim($searchDrvName) . "%')";
		}
		$sql			 = "SELECT dul.dul_id,
			               dul.dul_drv_id,
						   dul.dul_user_id,
						   dul.dul_bkg_id,
						   dul.dul_event_id,
						   dul.dul_drv_appToken,
						   dul.dul_data ,
						   dul.dul_url,
						   dul.dul_status,
						   dul.dul_error,
						   dul.dul_event_date,
						   dul.dul_created_date,
						    drv.drv_name,
							bkg.bkg_booking_id
						  
			                FROM `drv_unsync_log` dul
							LEFT JOIN `drivers` drv ON dul.dul_drv_id = drv.drv_id 
							INNER JOIN booking bkg ON dul.dul_bkg_id = bkg.bkg_id
							WHERE  dul_status IN(0,1,5) $where ORDER BY dul_id DESC ";
		//dul_status IN(0,1)
		$count			 = DBUtil::command("SELECT COUNT(*) FROM  ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'pagination'	 => ['pageSize' => $pageSize],
		]);
		return $dataprovider;
	}
	public function checkExist($bkg, $event)
	{
		$param	 = ['dul_bkg_id' => $bkg, 'dul_event_id' => $event];
		$sql	 = "UPDATE `drv_unsync_log` SET `dul_status` = '5' WHERE dul_bkg_id=:dul_bkg_id  AND dul_event_id = :dul_event_id";
		return DBUtil::execute($sql, $param);
	}

}

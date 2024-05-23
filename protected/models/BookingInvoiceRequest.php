<?php

/**
 * This is the model class for table "booking_invoice_request".
 *
 * The followings are the available columns in table 'booking_invoice_request':
 * @property integer $bir_id
 * @property integer $bir_agent_id
 * @property string $bir_bkg_pickup_date_from
 * @property string $bir_bkg_pickup_date_to
 * @property integer $bir_booking_count
 * @property integer $bir_request_status
 * @property integer $bir_request_type
 * @property string $bir_request_date
 * @property string $bir_request_cond
 * @property integer $bir_request_user_id
 * @property string $bir_request_user_email
 * @property string $bir_download_link
 */
class BookingInvoiceRequest extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_invoice_request';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bir_bkg_pickup_date_from, bir_bkg_pickup_date_to, bir_booking_count, bir_request_type, bir_request_user_id, bir_request_user_email', 'required'),
			array('bir_agent_id, bir_booking_count, bir_request_status, bir_request_type, bir_request_user_id', 'numerical', 'integerOnly' => true),
			array('bir_request_user_email, bir_download_link', 'length', 'max' => 255),
			array('bir_request_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bir_id, bir_agent_id, bir_bkg_pickup_date_from, bir_bkg_pickup_date_to, bir_booking_count, bir_request_status, bir_request_type, bir_request_date, bir_request_cond, bir_request_user_id, bir_request_user_email, bir_download_link', 'safe', 'on' => 'search'),
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
			'bir_id'					 => 'Bir',
			'bir_agent_id'				 => 'Bir Agent',
			'bir_bkg_pickup_date_from'	 => 'Bir Bkg Pickup Date From',
			'bir_bkg_pickup_date_to'	 => 'Bir Bkg Pickup Date To',
			'bir_booking_count'			 => 'Bir Booking Count',
			'bir_request_status'		 => 'Bir Request Status',
			'bir_request_type'			 => 'Bir Request Type',
			'bir_request_date'			 => 'Bir Request Date',
			'bir_request_cond'			 => 'Bir Request Condition',
			'bir_request_user_id'		 => 'Bir Request User',
			'bir_request_user_email'	 => 'Bir Request User Email',
			'bir_download_link'			 => 'Bir Download Link',
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

		$criteria->compare('bir_id', $this->bir_id);
		$criteria->compare('bir_agent_id', $this->bir_agent_id);
		$criteria->compare('bir_bkg_pickup_date_from', $this->bir_bkg_pickup_date_from, true);
		$criteria->compare('bir_bkg_pickup_date_to', $this->bir_bkg_pickup_date_to, true);
		$criteria->compare('bir_booking_count', $this->bir_booking_count);
		$criteria->compare('bir_request_status', $this->bir_request_status);
		$criteria->compare('bir_request_type', $this->bir_request_type);
		$criteria->compare('bir_request_date', $this->bir_request_date, true);
		$criteria->compare('bir_request_cond', $this->bir_request_cond, true);
		$criteria->compare('bir_request_user_id', $this->bir_request_user_id);
		$criteria->compare('bir_request_user_email', $this->bir_request_user_email, true);
		$criteria->compare('bir_download_link', $this->bir_download_link, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingInvoiceRequest the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function CreateRequest($date1, $date2, $agtid, $btnType, $paramArray)
	{
		$success	 = false;
		$error		 = '';
		$userinfo	 = UserInfo::getInstance();
		$admin		 = Admins::model()->findByPk($userinfo->userId);
		$adminEmail	 = $admin->adm_email;
		if ($btnType == 'invoice')
		{
			$reqType = 1;
		}
		if ($btnType == 'sheet')
		{
			$reqType = 2;
		}
		if ($btnType == 'proforma')
		{
			$reqType = 3;
		}
		$requestModel	 = new BookingInvoiceRequest();

		$totalbkg		 = $requestModel::getTotalRequest($date1, $date2, $agtid, $reqType, $paramArray);

		$frmdate		 = $date1 . ' ' . '00:00:00';
		$todate			 = $date2 . ' ' . '23:23:59';
		$dataDiff		 = date_diff(date_create($frmdate), date_create($todate));
		$dateDiff		 = $dataDiff->days;
		$dayDiff		 = 1;
		if ($totalbkg > 0)
		{
			if (($totalbkg <= 500) || ($totalbkg > 500 && $dateDiff == $dayDiff) || $reqType == 2)
			{
				$requestModel->bir_agent_id				 = $agtid;
				$requestModel->bir_bkg_pickup_date_from	 = $frmdate;
				$requestModel->bir_bkg_pickup_date_to	 = $todate;
				$requestModel->bir_booking_count		 = $totalbkg;
				$requestModel->bir_request_date			 = new CDbExpression("NOW()");
				$requestModel->bir_request_type			 = $reqType;
				$requestModel->bir_request_user_email	 = $adminEmail;
				$requestModel->bir_request_user_id		 = $userinfo->userId;

				$arrReqCond = [];
				if(isset($paramArray['bkgtypes']) && count($paramArray['bkgtypes']) > 0)
				{
					$arrReqCond['bkgTypes'] = implode(',', $paramArray['bkgtypes']);
				}
				if(isset($arrReqCond) && count($arrReqCond) > 0)
				{
					$requestModel->bir_request_cond = json_encode($arrReqCond);
				}

				$requestModel->validate();
				if ($requestModel->save())
				{
					$requestModel->refresh();
					
					$modelBkgInvProcess = BookingInvoiceProcess::CreateProcess($requestModel);

					if ($modelBkgInvProcess)
					{
						if ($reqType == 2)
						{
							BookingInvoiceProcess::createCsv($requestModel->bir_id);
						}
						$success = true;
						$error	 = "Job ID " . $requestModel->bir_id . " " . $btnType . " Request received for pickup date range " . $frmdate . " to " . $todate . ". We will notify you by email when job is complete.";
					}
					else
					{
						$success = false;
						$error	 = "Request not accepted for processing!";
					}
				}
			}
			else
			{
				$success = false;
				$error	 = "Pickup date rage is not acceblable! Total requested booking : " . $totalbkg;
			}
		}
		else
		{
			$success = false;
			$error	 = "No bookings found in this pickup date range";
		}
		$msg = ['success' => $success, 'error' => $error];
		return $msg;
	}

	public static function getTotalRequest($date1, $date2, $agtid, $reqType, $paramArray)
	{
		$frmdate	 = $date1 . ' ' . '00:00:00';
		$todate		 = $date2 . ' ' . '23:23:59';
		$dateRange	 = " AND bkg_pickup_date BETWEEN '$frmdate' AND '$todate' ";
		if ($reqType == 1)
		{
			$status = ' AND bkg_status IN (6,7,9) ';
		}
		if ($reqType == 2)
		{
			$status = ' AND bkg_status IN (2,3,5,6,7,9) ';
		}
		if ($reqType == 3)
		{
			$status = ' AND bkg_status IN (2,3,5) ';
		}
//		echo "<pre><br>BB";
//		print_r($paramArray);
		$cond = '';
		if (count($paramArray) > 0 && isset($paramArray['bkgtypes']))
		{
			$strBkgTypes = implode(',', $paramArray['bkgtypes']);
			$cond = " AND bkg_booking_type IN ({$strBkgTypes}) ";
		}
//		echo "<br>CC";
//		var_dump($paramArray);

		$sql = "SELECT COUNT(*) FROM booking 
				WHERE bkg_agent_id = '$agtid' AND bkg_active = 1 {$status} {$dateRange} {$cond} ";
		
		return DBUtil::command($sql, DBUtil::SDB())->queryScalar();
	}

}

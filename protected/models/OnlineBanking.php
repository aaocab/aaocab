<?php

/**
 * This is the model class for table "online_banking".
 *
 * The followings are the available columns in table 'online_banking':
 * @property integer $onb_id
 * @property string $onb_code
 * @property string $onb_payment_user_id
 * @property integer $onb_payee_type
 * @property integer $onb_payee_id
 * @property string $onb_payee_account
 * @property string $onb_payee_ifsc
 * @property string $onb_amount
 * @property string $onb_txn_type
 * @property string $onb_payee_name
 * @property string $onb_remarks
 * @property integer $onb_status
 * @property string $onb_response
 * @property string $onb_response_message
 * @property string $onb_response_code
 * @property integer $onb_active
 * @property integer $onb_requested_ref_type
 * @property integer $onb_requested_by
 * @property string $onb_request_time
 * @property string $onb_process_started_on
 * @property string $onb_processed_on
 */
class OnlineBanking extends CActiveRecord
{

	const statusList = [0 => 'Pending', 1 => 'Success', 2 => 'Failure', 3 => 'Need manual action'];

	public $payeename, $payeetype;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'online_banking';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('onb_code, onb_payment_user_id, onb_payee_id, onb_payee_account, onb_payee_ifsc, onb_amount, onb_txn_type, onb_payee_name ', 'required'),
			array('onb_payee_type, onb_payee_id, onb_status, onb_active, onb_requested_ref_type, onb_requested_by', 'numerical', 'integerOnly' => true),
			array('onb_code', 'length', 'max' => 40),
			array('onb_payment_user_id, onb_payee_ifsc', 'length', 'max' => 32),
			array('onb_payee_account', 'length', 'max' => 34),
			array('onb_amount', 'length', 'max' => 10),
			array('onb_txn_type', 'length', 'max' => 11),
			array('onb_payee_name', 'length', 'max' => 50),
			array('onb_remarks, onb_response_message', 'length', 'max' => 255),
			array('onb_response', 'length', 'max' => 500),
			array('onb_response_code', 'length', 'max' => 100),
			array('onb_payee_id', 'checkPendingRow', 'on' => 'insert'),
			array('onb_process_started_on, onb_processed_on', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('onb_id, onb_code, onb_payment_user_id, onb_payee_type, onb_payee_id, onb_payee_account, onb_payee_ifsc, onb_amount, onb_txn_type, onb_payee_name, onb_remarks, onb_status, onb_response, onb_response_message, onb_response_code, onb_active, onb_requested_ref_type, onb_requested_by, onb_request_time, onb_process_started_on, onb_processed_on', 'safe', 'on' => 'search'),
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
			'onb_id'				 => 'Onb',
			'onb_code'				 => 'Onb Code',
			'onb_payment_user_id'	 => 'Onb Payment User',
			'onb_payee_type'		 => 'Onb Payee Type',
			'onb_payee_id'			 => 'Onb Payee',
			'onb_payee_account'		 => 'Onb Payee Account',
			'onb_payee_ifsc'		 => 'Onb Payee Ifsc',
			'onb_amount'			 => 'Onb Amount',
			'onb_txn_type'			 => 'Onb Txn Type',
			'onb_payee_name'		 => 'Onb Payee Name',
			'onb_remarks'			 => 'Onb Remarks',
			'onb_status'			 => 'Onb Status',
			'onb_response'			 => 'Onb Response',
			'onb_response_message'	 => 'Onb Response Message',
			'onb_response_code'		 => 'Onb Response Code',
			'onb_active'			 => 'Onb Active',
			'onb_requested_ref_type' => 'Onb Requested Ref Type',
			'onb_requested_by'		 => 'Onb Requested By',
			'onb_request_time'		 => 'Onb Request Time',
			'onb_process_started_on' => 'Onb Process Started On',
			'onb_processed_on'		 => 'Onb Processed On',
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

		$criteria->compare('onb_id', $this->onb_id);
		$criteria->compare('onb_code', $this->onb_code, true);
		$criteria->compare('onb_payment_user_id', $this->onb_payment_user_id, true);
		$criteria->compare('onb_payee_type', $this->onb_payee_type);
		$criteria->compare('onb_payee_id', $this->onb_payee_id);
		$criteria->compare('onb_payee_account', $this->onb_payee_account, true);
		$criteria->compare('onb_payee_ifsc', $this->onb_payee_ifsc, true);
		$criteria->compare('onb_amount', $this->onb_amount, true);
		$criteria->compare('onb_txn_type', $this->onb_txn_type, true);
		$criteria->compare('onb_payee_name', $this->onb_payee_name, true);
		$criteria->compare('onb_remarks', $this->onb_remarks, true);
		$criteria->compare('onb_status', $this->onb_status);
		$criteria->compare('onb_response', $this->onb_response, true);
		$criteria->compare('onb_response_message', $this->onb_response_message, true);
		$criteria->compare('onb_response_code', $this->onb_response_code, true);
		$criteria->compare('onb_active', $this->onb_active);
		$criteria->compare('onb_requested_ref_type', $this->onb_requested_ref_type);
		$criteria->compare('onb_requested_by', $this->onb_requested_by);
		$criteria->compare('onb_request_time', $this->onb_request_time, true);
		$criteria->compare('onb_process_started_on', $this->onb_process_started_on, true);
		$criteria->compare('onb_processed_on', $this->onb_processed_on, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OnlineBanking the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function addNew($arr, $entityArr, UserInfo $userInfo = null)
	{
		$model							 = new OnlineBanking();
		$model->onb_code				 = $arr['UNIQUEID'];
		$model->onb_payment_user_id		 = $arr['USERID'];
		$model->onb_payee_account		 = $arr['CREDITACC'];
		$model->onb_payee_ifsc			 = $arr['IFSC'];
		$model->onb_amount				 = $arr['AMOUNT'];
		$model->onb_txn_type			 = $arr['TXNTYPE'];
		$model->onb_payee_name			 = $arr['PAYEENAME'];
		$model->onb_remarks				 = $arr['REMARKS'];
		$model->onb_payee_type			 = $entityArr['entity_type'];
		$model->onb_payee_id			 = $entityArr['entity_id'];
		$model->onb_requested_ref_type	 = $userInfo->getUserType();
		$model->onb_requested_by		 = $userInfo->getUserId();
		$success						 = false;
		if ($model->validate())
		{
			$success = $model->save();
		}

		return $success;
	}

	function checkPendingRow()
	{
		if ($this->onb_payee_type != '' && $this->onb_payee_id != '')
		{
			$res = $this->getPendingEntry($this->onb_payee_type, $this->onb_payee_id);
			if ($res)
			{
				$this->addError('onb_payee_type', "Pending entry already exist");
				return false;
			}
			else
			{
				return true;
			}
		}
		return false;
	}

	function getPendingEntry($payeeType, $payeeId)
	{
		$params	 = ['payeeType' => $payeeType, 'payeeId' => $payeeId];
		$sql	 = "select onb_id from online_banking  where onb_payee_type=:payeeType and onb_payee_id =:payeeId AND onb_status IN (0)";
		$res	 = DBUtil::queryRow($sql, $db, $params);
		return $res;
	}

	function getByUniqueId($uniqueId)
	{
		$criteria = new CDbCriteria;
		$criteria->compare('onb_code', $uniqueId);

		$model = $this->find($criteria);
		return $model;
	}

	public static function updateStatusByUniqueId($uniqueId, $response)
	{
		/** @var PaymentResponse $response */
		$params = [
			'status'	 => $response->payment_status,
			'response'	 => $response->response,
			'message'	 => $response->message,
			'code'		 => $response->response_code . ' ',
			'uniqueId'	 => $uniqueId
		];

		$sql	 = "UPDATE online_banking set 
onb_status =:status,	
  			onb_response =:response,	
			onb_response_message =:message,
			onb_response_code=:code,
			onb_processed_on=NOW() 
			WHERE onb_code =:uniqueId";
		$sucess	 = DBUtil::command($sql)->execute($params);
		return $sucess;
	}

	public static function fetchUniqueIds()
	{
		$paystatus	 = OnlineBanking::statusList;
		$sql		 = "SELECT onb_code,onb_status,onb_request_time from online_banking where onb_active = 1 AND onb_status <>2";
		$res		 = DBUtil::query($sql, DBUtil::SDB());
		$data		 = [];
		foreach ($res as $key => $value)
		{
			$data[$value['onb_code']] = $value['onb_request_time'] . ' - ' . $value['onb_code'] . ' - ' . $paystatus[$value['onb_status']];
		}

		return $data;
	}

	public static function fetchList($type = "")
	{

		$sql = "SELECT   vnd.vnd_id, usr.user_id, 
				if(onb_payee_type = 1, concat(usr.usr_name, ' ', usr.usr_lname), vnd.vnd_name) payeename, 
				if(onb_payee_type = 1, 'Customer', 'Operator') payeetype, 
				onb.*
				FROM     `online_banking` onb
						 LEFT JOIN users usr ON usr.user_id = onb_payee_id AND onb_payee_type = 1
						 LEFT JOIN vendors vnd ON vnd.vnd_id = onb_payee_id AND onb_payee_type = 2
				WHERE    onb_payee_type <> 0
				ORDER BY onb_id DESC";
		if($type == 'command')
		{
			return DBUtil::query($sql, DBUtil::SDB());
		}
		else{
			$res = DBUtil::queryAll($sql, DBUtil::SDB());

			$dataprovider = new CArrayDataProvider($res, array(
				'sort'		 => ['attributes'	 =>
					['payeename'], 'defaultOrder'	 => ''],
				'pagination' => array('pageSize' => 50),
					)
			);
			return $dataprovider;
		}
	}

	public function getListtoProcess()
	{
		$sql = "SELECT *
FROM   online_banking
WHERE  onb_status IN (0) AND (onb_payee_type IS NOT NULL AND onb_payee_type <> 0)";
		$res = $this->findAllBySql($sql);
		return $res;
	}

	public static function processPendingPayments()
	{
		$datalist = OnlineBanking::model()->getListtoProcess();

		foreach ($datalist as $model)
		{
			$model->processPayment();
		}
	}

	public function getCIBParams()
	{
		$PayDetails				 = Yii::app()->icici->getTransactionDefaults();
		$PayDetails['UNIQUEID']	 = $this->onb_code;
		$PayDetails['USERID']	 = $this->onb_payment_user_id;
		$PayDetails['CREDITACC'] = $this->onb_payee_account;
		$PayDetails['IFSC']		 = $this->onb_payee_ifsc;
		$PayDetails['AMOUNT']	 = $this->onb_amount;
		$PayDetails['TXNTYPE']	 = $this->onb_txn_type;
		$PayDetails['PAYEENAME'] = $this->onb_payee_name;
		if (trim($this->onb_remarks) != '')
		{
			$PayDetails['REMARKS'] = $this->onb_remarks;
		}
		return $PayDetails;
	}

	public function processPayment()
	{
        Logger::setModelCategory(__CLASS__, __FUNCTION__);
        
		$refId		 = $this->onb_payee_id;
		$uniqueId	 = $this->onb_code;
		if ($this->onb_payee_type == 2)
		{


			$vndStats = VendorStats::model()->getbyVendorId($refId);
			$vndStats->setLockedAmount();
			if ($vndStats->vrs_withdrawable_balance != $this->onb_amount)
			{
				$responseArr = ['payment_status' => 2, 'response' => 'Amount need to be checked', 'message' => 'Amount need to be checked', 'response_code' => ''];
				$response	 = json_decode(json_encode($responseArr), false);
				goto updateresponse;
			}
			if ($vndStats->vrs_withdrawable_balance <= 0)
			{
				$responseArr = ['payment_status' => 2, 'response' => 'No payeble amount', 'message' => 'No payeble amount', 'response_code' => ''];
				$response	 = json_decode(json_encode($responseArr), false);
				goto updateresponse;
			}
		}

		try
		{

			$this->onb_process_started_on = new CDbExpression('NOW()');
			$this->save();

			$PayDetails	 = $this->getCIBParams();
			$response	 = Yii::app()->icici->transaction($PayDetails);

			updateresponse:

			$amount		 = $this->onb_amount;
			$userInfo	 = UserInfo::model($this->onb_requested_ref_type, $this->onb_requested_by);

			if ($response->payment_status != 1)
			{
				$result = false;
				goto skipAccountTrans;
			}

			$success		 = false;
			$requestArr		 = ['code' => $this->onb_code, 'payment_user_id' => $this->onb_payment_user_id, 'payee_type' => $this->onb_payee_type, 'payee_id' => $this->onb_payee_id, 'payee_account' => $this->onb_payee_account, 'payee_ifsc' => $this->onb_payee_ifsc, 'amount' => $this->onb_amount, 'txn_type' => $this->onb_txn_type, 'payee_name' => $this->onb_payee_name, 'remarks' => $this->onb_remarks];
			$responseData	 = [
				'requestData'	 => $requestArr,
				'responseData'	 => json_decode($response->response)
			];
			$responseString	 = json_encode($responseData);
			$paymentRefId	 = $this->onb_id;
			switch ($this->onb_payee_type)
			{
				case 1:
					$result = AccountTransactions::paidToConstomer(new CDbExpression('NOW()'), $amount, $refId, $response->payment_code, $paymentRefId, $responseString, '', $userInfo);
					if ($result)
					{
						$success = Users::processPayment($refId, $amount, true);
                        $refType = 1;
					}
					break;
				case 2:
					$result = AccountTransactions::paidToOperator(new CDbExpression('NOW()'), $amount, $refId, $response->payment_code, $paymentRefId, $responseString, '', $userInfo);
					if ($result)
					{
						$success = Vendors::processPayment($refId, $amount, true);
                        $refType = 2;
					}
					break;
				default:
					break;
			}
            
            

			if (!$success)
			{
				$response->payment_status = 3;
			}
			skipAccountTrans:
			$updated = OnlineBanking::updateStatusByUniqueId($uniqueId, $response);
            Logger::trace("user id: ".$refId. "success msg: ". $success);
            if($success)
            {
                $paymentType = 'Bank';
                $emailObj	 = new emailWrapper();
                Logger::trace("email content val: ".$refId."==".$amount."==".$response->payment_code."==".$paymentType."==".$refType);
                $emailObj->refundFromWalletToBank($refId,$amount,$response->payment_code,$paymentType,$refType);
            }
		}
		catch (Exception $e)
		{
			Logger::exception($e);
		}
        Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
	}

}

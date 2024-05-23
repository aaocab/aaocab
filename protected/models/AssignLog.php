<?php

/**
 * This is the model class for table "assign_log".
 *
 * The followings are the available columns in table 'assign_log':
 * @property integer $alg_id
 * @property integer $alg_user_id
 * @property integer $alg_event_id
 * @property integer $alg_role_id
 * @property integer $alg_role_contact_id
 * @property integer $alg_ref_id
 * @property integer $alg_ref_type
 * @property string $alg_associated_record
 * @property integer $alg_adm_user_id
 * @property integer $alg_adm_user_type
 * @property string $alg_desc
 * @property string $alg_notes
 * @property string $alg_created
 * @property integer $alg_active
 * @property integer $alg_status
 * @property integer $alg_csr_rank
 */
class AssignLog extends CActiveRecord
{

	const ASSIGNED_LEAD_CSR		 = 100;
	const ASSIGNED_BOOKING_CSR	 = 101;
	const ASSIGNED_VENDOR_CSR		 = 102;
	const ASSIGNED_DRIVER_CSR		 = 103;
	const ASSIGNED_NEW_VENDOR_CSR	 = 104;
	const TYPE_LEAD				 = 1;
	const TYPE_BOOKING			 = 2;
	const TYPE_FOLLOWUP			 = 3;
	const TYPE_EXISTING_BOOKING	 = 4;
	const TYPE_NEW_VENDOR			 = 5;
	const TYPE_DRIVER				 = 6;
	const call_type_arr			 = [
		1	 => 'Lead',
		2	 => 'Quotation',
		3	 => 'Followup',
		4	 => 'Existing Booking',
		5	 => 'New Vendor',
		6	 => 'Driver'
	];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'assign_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('alg_event_id, alg_ref_id, alg_ref_type, alg_adm_user_id, alg_adm_user_type', 'required'),
			array('alg_user_id, alg_event_id, alg_role_id, alg_role_contact_id, alg_ref_id, alg_ref_type, alg_adm_user_id, alg_adm_user_type, alg_active, alg_status', 'numerical', 'integerOnly' => true),
			array('alg_associated_record, alg_desc, alg_notes', 'length', 'max' => 255),
			//array('alg_status', 'required', 'on' => 'insert'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('alg_id, alg_user_id, alg_event_id, alg_role_id, alg_role_contact_id, alg_ref_id, alg_ref_type, alg_associated_record, alg_adm_user_id, alg_adm_user_type, alg_desc, alg_notes, alg_created, alg_active, alg_status alg_csr_rank', 'safe', 'on' => 'search'),
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
			'alg_id'				 => 'Alg',
			'alg_user_id'			 => 'Alg User',
			'alg_event_id'			 => 'Alg Event',
			'alg_role_id'			 => 'Alg Role',
			'alg_role_contact_id'	 => 'Alg Role Contact',
			'alg_ref_id'			 => 'Alg Ref',
			'alg_ref_type'			 => '1:lead;2:booking;3:callmeback',
			'alg_associated_record'	 => 'Alg Associated Record',
			'alg_adm_user_id'		 => 'Alg Adm User',
			'alg_adm_user_type'		 => 'Alg Adm User Type',
			'alg_desc'				 => 'Alg Desc',
			'alg_notes'				 => 'Alg Notes',
			'alg_created'			 => 'Alg Created',
			'alg_active'			 => 'Alg Active',
			'alg_status'			 => 'Alg Status',
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

		$criteria->compare('alg_id', $this->alg_id);
		$criteria->compare('alg_user_id', $this->alg_user_id);
		$criteria->compare('alg_event_id', $this->alg_event_id);
		$criteria->compare('alg_role_id', $this->alg_role_id);
		$criteria->compare('alg_role_contact_id', $this->alg_role_contact_id);
		$criteria->compare('alg_ref_id', $this->alg_ref_id);
		$criteria->compare('alg_ref_type', $this->alg_ref_type);
		$criteria->compare('alg_associated_record', $this->alg_associated_record, true);
		$criteria->compare('alg_adm_user_id', $this->alg_adm_user_id);
		$criteria->compare('alg_adm_user_type', $this->alg_adm_user_type);
		$criteria->compare('alg_desc', $this->alg_desc, true);
		$criteria->compare('alg_notes', $this->alg_notes, true);
		$criteria->compare('alg_created', $this->alg_created, true);
		$criteria->compare('alg_active', $this->alg_active);
		$criteria->compare('alg_status', $this->alg_status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AssignLog the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function createLog($refid, $reftype, $desc, $eventid, $csrRank)
	{
		$success	 = false;
		$errors		 = '';
		$userInfo	 = UserInfo::getInstance();

		$assignLog						 = new AssignLog();
		$assignLog->alg_ref_id			 = $refid;
		$assignLog->alg_ref_type		 = $reftype;
		$assignLog->alg_desc			 = $desc;
		$assignLog->alg_event_id		 = $eventid;
		$assignLog->alg_adm_user_type	 = $userInfo->userType;
		$assignLog->alg_adm_user_id		 = $userInfo->userId;
		$assignLog->alg_csr_rank		 = $csrRank;


		if ($assignLog->validate())
		{

			if ($assignLog->save())
			{
				$success = true;
			}
			else
			{
				$getErrors = json_encode($assignLog->getErrors());
			}
		}
		else
		{
			$getErrors = json_encode($assignLog->getErrors());
		}
		return $assignLog;
	}

	public static function getAssignTime($refType, $bkg_id, $csr, $event)
	{
		$params	 = ['$refType' => $refType, 'bkgId' => $bkg_id, 'csr' => $csr, 'event' => $event];
		$sql	 = "SELECT alg_created FROM assign_log 
				WHERE alg_ref_type =:refType AND alg_ref_id =:bkgId 
					AND alg_adm_user_id=:csr AND alg_event_id=:event 
					AND alg_active=1 AND alg_status=1 
				ORDER BY alg_id DESC LIMIT 1";
		$dt		 = DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
		return $dt;
	}

	public static function getCountByCsr($csr)
	{
		$params	 = ['csr' => $csr];
		$sql	 = "SELECT COUNT(*) as tot FROM assign_log 
				WHERE alg_adm_user_id=:csr AND DATE(alg_created)= CURDATE() AND alg_active = 1";
		$count	 = DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
		return $count;
	}

	public static function getAssignLeadFromOpsapp($csr)
	{
		$params	 = ['csr' => $csr];
		$sql	 = "SELECT * FROM assign_log WHERE alg_adm_user_id=:csr AND alg_active=1 AND alg_status = 1 ORDER BY alg_id DESC LIMIT 1";
		$result	 = DBUtil::command($sql)->queryRow(true, $params);
		return $result;
	}

	public static function assignQT($refId, $csrRank, $csr)
	{
		$desc = Booking::assignQT($refId, $csr);
		return self::validateData($refId, 2, $desc, $csrRank, AssignLog::ASSIGNED_BOOKING_CSR);
	}

	public static function assignLD($refId, $csrRank, $csr)
	{
		$desc = BookingTemp::assignLD($refId, $csr);
		return self::validateData($refId, 1, $desc, $csrRank, AssignLog::ASSIGNED_LEAD_CSR);
	}

	public static function validateData($refId, $refType, $desc, $csrRank, $csrType)
	{
		$assignLogModel = self::model()->find('alg_active = 1 AND alg_ref_id=:ref AND alg_status=:sts AND alg_ref_type=:refType', ['ref' => $refId, 'sts' => 1, 'refType' => $refType]);
		if ($assignLogModel == '')
		{
			$assignLogModel = self::createLog($refId, $refType, $desc, $csrType, $csrRank);
		}
		return $assignLogModel;
	}

	public static function deactivateEntry($algid)
	{
		$sql = "UPDATE assign_log SET alg_status =0, `alg_closed_at` = NOW() 
            WHERE alg_id =:algid";
		return DBUtil::execute($sql, ['algid' => $algid]);
	}

	public static function checkExistingAssignment($csr)
	{
		$success = false;
		if ($csr == '')
		{
			goto end;
		}
		$params = ['csr' => $csr];

		$sql	 = "SELECT COUNT(1) FROM assign_log WHERE alg_adm_user_id=:csr AND alg_active=1 AND alg_status = 1 ";
		$count	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		$success = ($count > 0);
		end:
		return $success;
	}

	public static function getAssignedLead($csr)
	{
		$returnSet = new ReturnSet();
		try
		{
			if ($csr == 0)
			{
				$returnSet->setMessage("You are not logged in.");
				goto skipAll;
			}
			$assignModel = AssignLog::getAssignLeadFromOpsapp($csr);
			$algId		 = $assignModel["alg_id"];
			if (empty($assignModel["alg_ref_id"]))
			{
				$returnSet->setMessage("No leads fetched/ assigned to you.");
				goto skipAll;
			}
			$leadID			 = $assignModel["alg_ref_id"];
			$leadType		 = (int) $assignModel['alg_ref_type'];
//			$callStatusModel = CallStatus::model()->getByRefId($leadID);
			$callStatusModel = CallStatus::model()->getByAlgRefId($algId);
			if (!$callStatusModel)
			{
				$callStatusModel = CallStatus::model()->getByRefId($leadID);
			}
			if (!$callStatusModel)
			{
				$callType = 0;
				if ($leadType == 3)
				{
					/* @var $fwpModel FollowUps */
					$fwpModel	 = FollowUps::findByPk($leadID);
					$callType	 = $fwpModel->fwp_ref_type;
				}
				else
				{
					$callType = $leadType;
				}
				if ($leadType == 2)
				{
					$result = Booking::model()->getUserbyIdNew($leadID);
				}
				if ($leadType == 1)
				{
					$result = BookingTemp::model()->getUserbyId($leadID);
				}

//				$returnSetCall	 = CallStatus::model()->create($leadID, 1, 91, $result['bkg_contact_no'], 1, $leadType);
				$returnSetCall = CallStatus::model()->addMyCall($algId, $leadType, $callType, 91, $result['bkg_contact_no'], 1);

				$callStatusModel = $returnSetCall->getData();
			}

			$response = new \Stub\common\AssignCall();
			$response->setData($callStatusModel, $leadID, $leadType);
			$returnSet->setStatus(true);
			$returnSet->setData($response);
		}
		catch (Exception $ex)
		{
			$returnSet = $returnSet->setException($ex);
			Logger::exception($ex);
		}
		skipAll:
		return $returnSet;
	}

	public static function assignLeadToCsr($csr, $teamId = null)
	{
		$returnSet = new ReturnSet();
		try
		{
			if (empty($csr))
			{
				Logger::trace("CSR not found");
				$returnSet->setMessage("CSR not found");
				goto skipAll;
			}

			$leadDetails = self::evaluate($csr, $teamId);

			$topLeadId	 = $leadDetails->topLeadId;
			$topLeadType = $leadDetails->topLeadType;
			$type		 = $leadDetails->type;
			$csrRank	 = $leadDetails->csrRank;
			$isNew		 = (int) $leadDetails->isNew;
			switch ($isNew)
			{
				case 1:

					if (empty($topLeadId))
					{
						throw new Exception("You have no lead to call, please press next", ReturnSet::ERROR_NO_RECORDS_FOUND);
					}
					switch ((int) $teamId)
					{
						case 1:
							$returnSet	 = self::assignLead($csr, $topLeadType, $topLeadId, $csrRank);
							break;
						case 5:
							$returnSet	 = self::assignExistingBookings($csr, $topLeadType, $topLeadId, $csrRank);
							break;
						case 3:
							$returnSet	 = self::assignNewVendorcall($csr, $topLeadType, $topLeadId, $csrRank);
							break;
						case 9:
							$returnSet	 = self::assignVendorcall($csr, $topLeadType, $topLeadId, $csrRank);
							break;
						default :
							throw new Exception("No call can be assigned to you. Contact your team leader.", ReturnSet::ERROR_FAILED);
					}

					if (!$returnSet->isSuccess())
					{
						throw new Exception("Error assigning lead. please call tech support", ReturnSet::ERROR_FAILED);
					}
					$data = $returnSet->getData();
					break;

				default:
					$csr		 = UserInfo::getUserId();
					$assignModel = AssignLog::getAssignLeadFromOpsapp($csr);
					$algId		 = $assignModel['alg_id'];
					Logger::trace("default::assignModel " . json_encode($assignModel));
					if (empty($assignModel["alg_ref_id"]))
					{
						throw new Exception("No leads assigned to you yet.", ReturnSet::ERROR_FAILED);
					}
					$data = CallStatus::model()->getByAlgRefId($algId);
					if ($data == null)
					{
						$data = CallStatus::model()->getByRefId($topLeadId);
					}
					break;
			}
			/* @var $response Stub\common\AssignCall */
			$response = new Stub\common\AssignCall();

			$response->setData($data, $topLeadId, $topLeadType);
			$returnSet->setStatus(true);
			$returnSet->setData($response);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
			Logger::pushTraceLogs();
		}

		skipAll:
		return $returnSet;
	}

	/**
	 * 
	 * @param type $csr
	 * @return \stdClass
	 */
	public static function evaluate($csr, $teamId = null)
	{
		$assignLogModel	 = AssignLog::getAssignLeadFromOpsapp($csr);
		Logger::trace("assignLogModel: " . json_encode($assignLogModel));
		$leadFresher	 = Yii::app()->user->checkAccess('LeadFresher');
		$leadSenior		 = Yii::app()->user->checkAccess('LeadSenior');

		$newAccess			 = ($leadFresher ) ? 0 : 1;
		$highValueAccess	 = ($leadFresher) ? 0 : ($leadSenior) ? 0 : 1;
		$unverifiedAccess	 = ($leadFresher) ? 0 : ($leadSenior) ? 0 : 1;
		$row				 = [];
		$isNew				 = 0;
		if (empty($assignLogModel))
		{

//			Logger::beginProfile('cmb_assignlog_evaluate');
			switch ((int) $teamId)
			{
				case 1://Retail Sales
					$row		 = BookingTemp::model()->getTopLead($csr, $unverifiedAccess, $newAccess, $highValueAccess, $teamId);
					break;
				case 5://Customer support
					$row		 = AssignLog::getTopExistingBooking($csr);
					$topLeadId	 = $row["refId"];
					$topLeadType = $row["refType"];
					$csrRank	 = $row["csrRank"];
					$isNew		 = 1;
					goto sendData;
					break;
				case 3://Vendor Onboarding				
					$row		 = FollowUps::assignNewVendor($csr);
					$topLeadId	 = $row["refId"];
					$topLeadType = $row["refType"];
					$type		 = $row["type"];
					$csrRank	 = $row["csrRank"];
					$isNew		 = 1;
					goto sendData;
					break;
				case 9://Vendor support
					$row		 = FollowUps::assignExistingVendor($csr);
					$topLeadId	 = $row["refId"];
					$topLeadType = 3; //$row["refType"];
					$csrRank	 = $row["csrRank"];
					$isNew		 = 1;
					goto sendData;
					break;
				default:
					$row		 = [];
					goto sendData;
					break;
			}

			Logger::trace("getTopLead: " . json_encode($row));
//			Logger::endProfile('cmb_assignlog_evaluate');
			#$topLeadId	 = ($row["type"] < 3) ? $row["bkg_id"] : $row["fwpId"];
			$topLeadId	 = $row["bkg_id"];
			$topLeadType = $row["type"];
			$csrRank	 = $row["csrRank"];
			$isNew		 = 1;
			goto sendData;
		}

		$topLeadId	 = $assignLogModel['alg_ref_id'];
		$topLeadType = $assignLogModel['alg_ref_type'];
		$csrRank	 = $assignLogModel['alg_csr_rank'];

		sendData:
		$data				 = new stdClass();
		$data->topLeadId	 = $topLeadId;
		$data->topLeadType	 = $topLeadType;
		$data->type			 = $type;
		$data->csrRank		 = $csrRank;
		$data->isNew		 = $isNew;

		return $data;
	}

	public static function assignTopLeadContact($csr)
	{
		$returnSet = new ReturnSet();
		try
		{

			if (empty($csr))
			{
				$returnSet->setMessage("CSR not found");
				goto skipAll;
			}

			$assignLogModel	 = AssignLog::getAssignLeadFromOpsapp($csr);
			Logger::trace("assignLogModel: " . json_encode($assignLogModel));
			$leadFresher	 = Yii::app()->user->checkAccess('LeadFresher');
			$leadSenior		 = Yii::app()->user->checkAccess('LeadSenior');

			$newAccess			 = ($leadFresher ) ? 0 : 1;
			$highValueAccess	 = ($leadFresher) ? 0 : ($leadSenior) ? 0 : 1;
			$unverifiedAccess	 = ($leadFresher) ? 0 : ($leadSenior) ? 0 : 1;

			$isNew = 0;
			if (empty($assignLogModel))
			{
				$row = FollowUps::getTopContact($csr);

				Logger::trace("getTopLead: " . json_encode($row));
				$topLeadId	 = $row["bkg_id"];
				$topLeadType = $row["type"];
				$csrRank	 = $row["csrRank"];
				$isNew		 = 1;

				goto sendData;
			}

			$topLeadId	 = $assignLogModel['alg_ref_id'];
			$topLeadType = $assignLogModel['alg_ref_type'];
			$csrRank	 = $assignLogModel['alg_csr_rank'];

			sendData:
			$data				 = new stdClass();
			$data->topLeadId	 = $topLeadId;
			$data->topLeadType	 = $topLeadType;
			$data->csrRank		 = $csrRank;
			$data->isNew		 = $isNew;



			switch ($isNew)
			{
				case 1:
					if (empty($topLeadId))
					{
						throw new Exception("You have no lead to call, please press next", ReturnSet::ERROR_NO_RECORDS_FOUND);
					}

					$data = self::assignLead($csrRank, $topLeadType, $topLeadId, $csr);
					if (empty($data))
					{
						throw new Exception("Error assigning lead. please call tech support", ReturnSet::ERROR_FAILED);
					}

					break;

				default:
					$csr		 = UserInfo::getUserId();
					$assignModel = AssignLog::getAssignLeadFromOpsapp($csr);
					Logger::trace("default::assignModel " . json_encode($assignModel));
					if (empty($assignModel["alg_ref_id"]))
					{
						throw new Exception("No leads assigned to you yet.", ReturnSet::ERROR_FAILED);
					}
					$data = CallStatus::model()->getByRefId($topLeadId);
					break;
			}
			/* @var $response Stub\common\AssignCall */
			$response = new Stub\common\AssignCall();


			$response->setData($data, $topLeadId, $topLeadType);
			$returnSet->setStatus(true);
			$returnSet->setData($response);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}

		skipAll:
		return $returnSet;
	}

	/**
	 *  @return ReturnSet 
	 */
	public static function assignLead($csr, $refType, $refId, $csrRank)
	{
		Logger::trace("Lead details:: csr: $csr, refType : $refType, refId: $refId, csrRank: $csrRank");
		switch ((int) $refType)
		{
			case 1: //1-Lead (AlgRefId)
//				$assignLogModel = AssignLog::assignLD($refId, $csrRank, $csr);


				$admin	 = Admins::model()->findByPk($csr);
				$aname	 = $admin->adm_fname;
				$desc	 = "Lead assigned to $aname (Auto Assign)";

				$assignLogModel = AssignLog::validateData($refId, 1, $desc, $csrRank, AssignLog::ASSIGNED_LEAD_CSR);

				if ($assignLogModel->alg_adm_user_id != $csr)
				{
					throw new Exception("No lead assigned, please refresh again.", ReturnSet::ERROR_NO_RECORDS_FOUND);
				}

				BookingTemp::assignLD($refId, $csr);

				$algId			 = $assignLogModel->alg_id;
				$resultLD		 = BookingTemp::model()->getUserbyId($refId);
				$custUserId		 = $resultLD["bkg_user_id"];
				$custUserEmail	 = $resultLD["email"];
				$custUserPhone	 = $resultLD['bkg_contact_no'];

				Logger::trace("Lead assigned $refId");
//				$getRelatedQuoteIds = Booking::getRelatedIds($custUserId, $custUserEmail, $custUserPhone);
//				Booking::assignedIds($getRelatedQuoteIds, $csr, $refId);

				$getRelatedLeadIds = BookingTemp::getRelatedLeadIds($custUserId, $custUserEmail, $custUserPhone);
				BookingTemp::assignedIds($getRelatedLeadIds, $csr, $refId);

				$returnSet	 = CallStatus::model()->addMyCall($algId, $refType, 1, 91, $resultLD['bkg_contact_no'], $isRecord	 = 1);
				break;

			case 2:  //2-Booking (AlgRefId)
//				$assignLogModel	 = AssignLog::assignQT($refId, $csrRank, $csr);

				$admin			 = Admins::model()->findByPk($csr);
				$aname			 = $admin->adm_fname;
				$desc			 = "CSR ($aname) Auto Assigned";
				$assignLogModel	 = AssignLog::validateData($refId, 2, $desc, $csrRank, AssignLog::ASSIGNED_BOOKING_CSR);

				if ($assignLogModel->alg_adm_user_id != $csr)
				{
					throw new Exception("No lead assigned, please refresh again.", ReturnSet::ERROR_NO_RECORDS_FOUND);
				}
				Booking::assignQT($refId, $csr);


				$algId			 = $assignLogModel->alg_id;
				$resultQT		 = Booking::model()->getUserbyIdNew($refId);
				$custUserId		 = $resultQT["bkg_user_id"];
				$custUserEmail	 = $resultQT["bkg_user_email"];
				$custUserPhone	 = $resultQT['bkg_contact_no'];

//				$getRelatedQuoteIds = Booking::getRelatedIds($custUserId, $custUserEmail, $custUserPhone);
//				Booking::assignedIds($getRelatedQuoteIds, $csr, $refId);

				$getRelatedLeadIds = BookingTemp::getRelatedLeadIds($custUserId, $custUserEmail, $custUserPhone);
				BookingTemp::assignedIds($getRelatedLeadIds, $csr, $refId);

				FollowUps::toggle($csr, $refId, 1);
				$returnSet = CallStatus::model()->addMyCall($algId, $refType, 2, 91, $resultQT['bkg_contact_no'], 1);

				break;

			case 3: //1-FollowUp OR CallmeBack (AlgRefId)
				$returnSet = FollowUps::assign($refId, $csrRank, $csr);
				break;
		}

		return $returnSet;
	}

	/**
	 *  @return ReturnSet 
	 */
	public static function assignNewVendorcall($csr, $refType, $refId, $csrRank)
	{

		switch ((int) $refType)
		{
			case 3: //1-FollowUp OR CallmeBack (AlgRefId)
				$returnSet		 = FollowUps::assign($refId, $csrRank, $csr);
				break;
			case 0:  //Pending vendor
				$admin			 = Admins::model()->findByPk($csr);
				$aname			 = $admin->adm_fname;
				$desc			 = "CSR $aname (Auto Assign)";
				$assignLogModel	 = AssignLog::validateData($refId, AssignLog::TYPE_NEW_VENDOR, $desc, $csrRank, AssignLog::ASSIGNED_NEW_VENDOR_CSR);
				$algId			 = $assignLogModel->alg_id;
				$vndId			 = $assignLogModel->alg_ref_id;
				$entityType		 = UserInfo::TYPE_VENDOR;
				$contactId		 = ContactProfile::getByEntityId($vndId, $entityType);


				$phone = ContactPhone::getContactNumber($contactId);
				Filter::parsePhoneNumber($phone, $code, $custUserPhone);

				$returnSet = CallStatus::model()->addMyCall($algId, AssignLog::TYPE_NEW_VENDOR, AssignLog::TYPE_NEW_VENDOR, $code, $custUserPhone, 1);

				break;
		}
//////////


		return $returnSet;
	}

	/**
	 *  @return ReturnSet 
	 */
	public static function assignVendorcall($csr, $refType, $refId, $csrRank)
	{
		$assignLog = AssignLog::getAssignLeadFromOpsapp($csr);

		$fwpModel	 = FollowUps::model()->findByPk($refId);
		$callTypeVal = $fwpModel->fwp_ref_type;

		$contactId = $fwpModel->fwp_contact_id;
		if ($fwpModel->fwp_contact_type == 1)
		{

			$arrProfile	 = \ContactProfile::getEntityById($contactId, UserInfo::TYPE_VENDOR);
			$vndid		 = $arrProfile['id'];
			if ($vndid == null)
			{
				throw new Exception("There is no vendor found for this contact.", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$vndModel		 = \Vendors::model()->getViewDetailbyId($vndid);
			$custUserPhone	 = $vndModel['vnd_contact_number'];
			$code			 = $vndModel['vnd_phone_country_code'];
		}
		if ($fwpModel->fwp_contact_phone_no != null)
		{
			$phoneNumber = $fwpModel->fwp_contact_phone_no;
			\Filter::parsePhoneNumber($phoneNumber, $code, $custUserPhone);
		}

		$admin			 = Admins::model()->findByPk($csr);
		$aname			 = $admin->adm_fname;
		$desc			 = "CSR ($aname) Auto Assigned";
		$assignLogModel	 = AssignLog::validateData($refId, 3, $desc, $csrRank, AssignLog::ASSIGNED_VENDOR_CSR);

		if ($assignLogModel->alg_adm_user_id != $csr)
		{
			throw new Exception("No lead assigned, please refresh again.", ReturnSet::ERROR_NO_RECORDS_FOUND);
		}
		$algId = $assignLogModel->alg_id;
		switch ($callTypeVal)
		{
			case 4:
				$callType	 = 3;
				break;
			case 3:
				$callType	 = 5;
				break;
			default:
				$callType	 = $callTypeVal;
				break;
		}
		FollowUps::assignCSR($refId, $csr);
		$returnSet = CallStatus::model()->addMyCall($algId, 3, $callType, $code, $custUserPhone, 1);

		return $returnSet;
	}

	public static function getTopExistingBooking_back($csr)
	{
		$sql = ' SELECT * from (
			(
			SELECT   fwp_id refId,  
			3 refType, 
			IF(bt.bkg_assign_csr = :csr OR (bt.bkg_create_user_type = 4 AND bt.bkg_create_user_id = :csr), 30,
				IF((bt.bkg_assign_csr IS NULL OR bt.bkg_assign_csr = 0), 10, 0)) AS csrRank,
			CASE
			  WHEN TIMESTAMPDIFF(MINUTE, fwp_created, NOW()) > 1440 THEN (5)
			  WHEN TIMESTAMPDIFF(MINUTE, fwp_created, NOW()) BETWEEN 720 AND 1440 THEN (30)
			  WHEN TIMESTAMPDIFF(MINUTE, fwp_created, NOW()) BETWEEN 360 AND 720 THEN (25)
			  WHEN TIMESTAMPDIFF(MINUTE, fwp_created, NOW()) BETWEEN 180 AND 360 THEN (20)
			  WHEN TIMESTAMPDIFF(MINUTE, fwp_created, NOW()) BETWEEN 60 AND 180 THEN (15)
			  WHEN TIMESTAMPDIFF(MINUTE, fwp_created, NOW()) < 60 THEN 10
			ELSE -20 END AS timeRank,
			CASE
			  WHEN bkg_status IN (6, 7) THEN 0
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) < 300 THEN (50)
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 300 AND 720 THEN (40)
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 720 AND 1440 THEN (20)
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 1440 AND 2880 THEN 10 
			ELSE 0
			END AS pickupRank,
			IF(fwp_id IS NOT NULL AND fwp_follow_up_status = 0, 20, 0) AS typeRank 
		FROM     booking
		INNER JOIN booking_trail bt ON booking.bkg_id = bt.btr_bkg_id   AND bkg_status IN (2, 3, 5, 6, 7, 9)
		INNER JOIN booking_user ON bui_bkg_id = bkg_id
		INNER JOIN contact_profile ON bkg_user_id = contact_profile.cr_is_consumer    
		INNER JOIN follow_ups fwp ON  fwp.fwp_ref_id = booking.bkg_id
			AND fwp.fwp_ref_type IN (2)
			AND fwp.fwp_status = 1
			AND fwp.fwp_contact_id > 0
			AND fwp.fwp_call_entity_type IN (1)
			AND fwp.fwp_prefered_time < NOW()
			AND fwp.fwp_follow_up_status <> 4 AND fwp.fwp_contact_id = contact_profile.cr_contact_id
		LEFT JOIN assign_log asl ON asl.alg_role_contact_id = contact_profile.cr_contact_id
			AND asl.alg_ref_id = fwp.fwp_id AND asl.alg_status = 1
		WHERE  (asl.alg_id IS NULL OR asl.alg_adm_user_id=:csr )
			   AND                  
				(
				  fwp.fwp_assigned_csr = :csr OR
				  fwp.fwp_assigned_csr IS NULL OR
				  fwp.fwp_assigned_csr = 0                                                
			)
		ORDER BY (csrRank + timeRank + pickupRank) DESC, bkg_pickup_date ASC, bkg_followup_date ASC  
		LIMIT 0,1 
		)
		UNION (
		SELECT   bkg_id refId,  
			2 refType,
			IF(bt.bkg_assign_csr = :csr OR (bt.bkg_create_user_type = 4 AND bt.bkg_create_user_id = :csr), 30, IF((bt.bkg_assign_csr IS NULL OR bt.bkg_assign_csr = 0), 0, -5)) AS csrRank, 
			CASE 
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_followup_date, NOW()) > 1440 THEN (30) 
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_followup_date, NOW()) BETWEEN 720 AND 1440 THEN (20) 
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_followup_date, NOW()) BETWEEN 360 AND 720 THEN (15) 
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_followup_date, NOW()) BETWEEN 180 AND 360 THEN (10) 
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_followup_date, NOW()) BETWEEN 60 AND 180 THEN (5) 
			  WHEN TIMESTAMPDIFF(MINUTE, bkg_followup_date, NOW()) < 60 THEN 0 
			  ELSE -20 
			END AS timeRank, 
			CASE 
			  WHEN bkg_status IN (6, 7) THEN -10 
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) < 300 THEN (50) 
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 300 AND 720 THEN (40) 
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 720 AND 1440 THEN (20) 
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 1440 AND 2880 THEN 10 
			  ELSE 0 
			END AS pickupRank, 
			0 AS typeRank
		FROM     booking
		 INNER JOIN booking_trail bt ON booking.bkg_id = bt.btr_bkg_id 
				AND bkg_pickup_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
		 INNER JOIN booking_user ON bui_bkg_id = bkg_id AND bkg_contact_no IS NOT NULL AND bkg_contact_no <> ""  
		 INNER JOIN contact_profile ON bkg_user_id = contact_profile.cr_is_consumer
		 LEFT JOIN assign_log asl
		   ON asl.alg_ref_type = 2 
			AND asl.alg_role_contact_id = contact_profile.cr_contact_id 
			AND asl.alg_status = 1
		WHERE    asl.alg_id IS NULL 
			AND bkg_pickup_date > DATE_SUB(NOW(), INTERVAL 7 DAY) 
			AND bt.bkg_follow_type_id = 10 
			AND bt.bkg_followup_active = 1 AND bkg_agent_id IS NULL
			AND bkg_status IN (2, 3, 5, 6, 7, 9) 
			AND  (bkg_assign_csr = 0 OR bkg_assign_csr IS NULL OR bkg_assign_csr=:csr) 
			AND bkg_followup_date < DATE_SUB(NOW(), INTERVAL IF(bt.bkg_create_user_type = 4 AND bt.bkg_create_user_id = :csr, 0, 45) MINUTE)
		ORDER BY (csrRank + timeRank + pickupRank) DESC, bkg_pickup_date ASC, bkg_followup_date ASC
		LIMIT 0, 1
		)
		) a order by csrRank DESC, refType DESC,(timeRank + pickupRank) DESC ';
		$row = DBUtil::queryRow($sql, null, ['csr' => $csr]);
		return $row;
	}
	/**
	 * Returns the row for the call me back follow up to be followed.
	 * 
	 * @param integer $csr CSR id to be assigned.
	 * @return $row Array
	 */
	public static function getTopExistingBooking($csr)
	{
		$sql = ' SELECT * from (
			(
			SELECT   fwp_id refId,  
			3 refType, 
			IF(bt.bkg_assign_csr = :csr OR (bt.bkg_create_user_type = 4 AND bt.bkg_create_user_id = :csr), 30,
				IF((bt.bkg_assign_csr IS NULL OR bt.bkg_assign_csr = 0), 10, 0)) AS csrRank,
			CASE
			  WHEN TIMESTAMPDIFF(MINUTE, fwp_created, NOW()) > 1440 THEN (5)
			  WHEN TIMESTAMPDIFF(MINUTE, fwp_created, NOW()) BETWEEN 720 AND 1440 THEN (30)
			  WHEN TIMESTAMPDIFF(MINUTE, fwp_created, NOW()) BETWEEN 360 AND 720 THEN (25)
			  WHEN TIMESTAMPDIFF(MINUTE, fwp_created, NOW()) BETWEEN 180 AND 360 THEN (20)
			  WHEN TIMESTAMPDIFF(MINUTE, fwp_created, NOW()) BETWEEN 60 AND 180 THEN (15)
			  WHEN TIMESTAMPDIFF(MINUTE, fwp_created, NOW()) < 60 THEN 10
			ELSE -20 END AS timeRank,
			CASE
			  WHEN bkg_status IN (6, 7) THEN 0
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) < 300 THEN (50)
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 300 AND 720 THEN (40)
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 720 AND 1440 THEN (20)
			  WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 1440 AND 2880 THEN 10 
			ELSE 0
			END AS pickupRank,
			IF(fwp_id IS NOT NULL AND fwp_follow_up_status = 0, 20, 0) AS typeRank 
		FROM     booking
		INNER JOIN booking_trail bt ON booking.bkg_id = bt.btr_bkg_id   AND bkg_status IN (2, 3, 5, 6, 7, 9)
		INNER JOIN booking_user ON bui_bkg_id = bkg_id
		INNER JOIN contact_profile ON bkg_user_id = contact_profile.cr_is_consumer    
		INNER JOIN follow_ups fwp ON  fwp.fwp_ref_id = booking.bkg_id
			AND fwp.fwp_ref_type IN (2)
			AND fwp.fwp_status = 1
			AND fwp.fwp_contact_id > 0
			AND fwp.fwp_call_entity_type IN (1)
			AND fwp.fwp_prefered_time < NOW() 
			AND (fwp.fwp_contact_type = 1 OR fwp.fwp_contact_type IS NULL)
			AND fwp.fwp_follow_up_status <> 4 AND fwp.fwp_contact_id = contact_profile.cr_contact_id
		LEFT JOIN assign_log asl ON asl.alg_role_contact_id = contact_profile.cr_contact_id
			AND asl.alg_ref_id = fwp.fwp_id AND asl.alg_status = 1
		WHERE  (asl.alg_id IS NULL OR asl.alg_adm_user_id=:csr )
				AND ((fwp_prefered_time<NOW() AND HOUR(NOW()) BETWEEN 5 AND 21) 
            OR ((HOUR(NOW())>21 OR HOUR(NOW())<5) AND TIMESTAMPDIFF(MINUTE, fwp_prefered_time, now()) BETWEEN 0 AND 45 )) 
			   AND                  
				(
				  fwp.fwp_assigned_csr = :csr OR
				  fwp.fwp_assigned_csr IS NULL OR
				  fwp.fwp_assigned_csr = 0                                                
			)
		ORDER BY (IF(fwp_platform=7,-30,0) +csrRank + timeRank + pickupRank) DESC, bkg_pickup_date ASC, bkg_followup_date ASC  
		LIMIT 0,1 
		)		 
		) a order by csrRank DESC, refType DESC,(timeRank + pickupRank) DESC ';
		$row = DBUtil::queryRow($sql, null, ['csr' => $csr]);
		return $row;
	}

	/**
	 *  @return ReturnSet 
	 */
	public static function assignExistingBookings($csr, $refType, $refId, $csrRank)
	{
		switch ((int) $refType)
		{
			case 2:  //2-Booking (AlgRefId)
				$assignLogModel	 = AssignLog::assignQT($refId, $csrRank, $csr);
				$algId			 = $assignLogModel->alg_id;
				$resultQT		 = Booking::model()->getUserbyIdNew($refId);
				$custUserId		 = $resultQT["bkg_user_id"];
				$custUserEmail	 = $resultQT["bkg_user_email"];
				$custUserPhone	 = $resultQT['bkg_contact_no'];

				$getRelatedExistings = Booking::getRelatedExistings($custUserId, $custUserEmail, $custUserPhone);
				Booking::assignRelatedExisting($getRelatedExistings, $csr, $refId);

				FollowUps::toggle($csr, $refId, 2);
				$returnSet = CallStatus::model()->addMyCall($algId, $refType, 2, 91, $resultQT['bkg_contact_no'], 1);

				break;

			case 3: //1-FollowUp OR CallmeBack (AlgRefId)
				$returnSet = FollowUps::assign($refId, $csrRank, $csr);
				break;
		}

		return $returnSet;
	}

	public static function getCallTypeName($refId, $refType)
	{
		$callTypeName = '';
		switch ((int) $refType)
		{
			case 1: //Lead
				$callTypeName	 = "Lead";
				break;
			case 2:
				/** @var Booking $bModel */
				$bModel			 = Booking::model()->findByPk($refId);
				if (in_array($bModel->bkg_status, [1, 15]))
				{
					$callTypeName = "Quotation";
				}
				if (in_array($bModel->bkg_status, [2, 3, 5, 6, 7, 9]))
				{
					$callTypeName = "Existing Booking Pending Followup";
				}
				break;
			case 3:
				/** @var FollowUps $fwpModel */
				$fwpModel		 = FollowUps::model()->findByPk($refId);
				$callTypeStr	 = FollowUps::getReasonList($fwpModel->fwp_ref_type);
				$callTypeName	 = $callTypeStr . ' Callback Request';
				break;
			case 5:
				/** @var Vendors $vndModel */
				$vndModel		 = Vendors::model()->findByPk($refId);
				$callTypeStr	 = AssignLog::call_type_arr[$refType];
				$callTypeName	 = $callTypeStr . ' Callback Request';
				break;
		}
		return $callTypeName;
	}

	public static function getAssignableSOS()
	{
		$sql = "SELECT count(bkg_id) cnt ,  GROUP_CONCAT( DISTINCT refType) ref  FROM (
		(
    SELECT   bkg_id, 1 AS refType
		FROM     booking_temp
		LEFT JOIN assign_log alg ON alg.alg_ref_id=bkg_id AND alg.alg_ref_type=1  AND alg_status=1 AND alg_adm_user_id <> 0
		WHERE  bkg_create_date<=DATE_SUB(NOW(), INTERVAL 20 MINUTE) AND (HOUR(NOW()) <= 21 OR (HOUR(NOW())>21 AND TIMESTAMPDIFF(MINUTE, bkg_create_date, now())<45)) 
			AND alg.alg_id IS NULL
			AND  (bkg_assigned_to = 0 OR bkg_assigned_to IS NULL  ) 
				AND bkg_follow_up_status IN (0,21, 20) AND bkg_pickup_date > NOW()
				AND (bkg_contact_no <> '' OR bkg_log_phone <> '')
	 	)
		UNION
		(
		SELECT   bkg_id, 2 AS refType
			FROM     booking_temp
			LEFT JOIN assign_log alg ON alg.alg_ref_id=bkg_id AND alg.alg_ref_type=1  AND alg_status=1 AND alg_adm_user_id <> 0
			WHERE   bkg_create_date<=DATE_SUB(NOW(), INTERVAL 30 MINUTE) AND  (HOUR(NOW()) <= 21 OR (HOUR(NOW())>21 AND TIMESTAMPDIFF(MINUTE, bkg_create_date, now())<45)) 
					AND bkg_follow_up_status IN (1,2,3) 
					AND (`bkg_follow_up_reminder`< NOW()) AND bkg_pickup_date > NOW()						 
					AND alg.alg_id IS NULL
					AND ((bkg_assigned_to = 0 OR bkg_assigned_to IS NULL 
						OR DATE_ADD(bkg_follow_up_reminder, INTERVAL IF(bkg_assigned_to=0,0,45) MINUTE) <NOW()))
			AND (bkg_contact_no <> '' OR bkg_log_phone <> '')			 
		)
		UNION		 
		(
		SELECT   bkg_id, 
			3 AS refType
		FROM     booking INNER JOIN booking_trail bt ON booking.bkg_id= bt.btr_bkg_id 
			INNER JOIN  booking_invoice bi ON 
			 bi.biv_bkg_id = bt.btr_bkg_id 
			INNER JOIN booking_user bui on bui_bkg_id = bkg_id AND bui.bkg_contact_no <> ''
			LEFT JOIN follow_ups ON fwp_ref_id = bkg_id AND fwp_ref_type = 1
			LEFT JOIN assign_log alg ON alg.alg_ref_id=bkg_id AND alg.alg_ref_type=2  AND alg_status=1 AND alg_adm_user_id <> 0
		WHERE 		
		bkg_create_date<=DATE_SUB(NOW(), INTERVAL 20 MINUTE) AND  (HOUR(NOW()) <= 21 
			OR (HOUR(NOW())>21 AND TIMESTAMPDIFF(MINUTE, bkg_create_date, now())<45)) 
			 
			AND ((bt.bkg_assign_csr = 0 OR bt.bkg_assign_csr IS NULL 
				OR DATE_ADD(bkg_create_date, INTERVAL IF(bt.bkg_assign_csr=0,0,45) MINUTE) <NOW()))	
				AND alg.alg_id IS NULL
			AND bkg_status IN (15) AND bkg_pickup_date > NOW()  
			AND bkg_agent_id IS NULL 
			AND  bt.bkg_create_type=3 AND fwp_id IS NULL
			AND (bkg_followup_date IS NULL OR bkg_followup_date < NOW())
	)
 )a";
		$cnt = DBUtil::queryScalar($sql, DBUtil::SDB());
		return $cnt;
	}

}

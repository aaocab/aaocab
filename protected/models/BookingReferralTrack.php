<?php

/**
 * This is the model class for table "booking_referral_track".
 *
 * The followings are the available columns in table 'booking_referral_track':
 * @property integer $brk_id
 * @property integer $brk_beneficiary_id
 * @property integer $brk_benefactor_id
 * @property integer $brk_isfirst_beneficiary
 * @property integer $brk_last_benefactor_bkgId
 * @property integer $brk_beneficiary_bkgId
 * @property string $brk_beneficiary_bkg_complete_date
 * @property string $brk_beneficiary_payout_amt
 * @property string $brk_benefactor_payout_amt
 * @property string $brk_beneficiary_payout_date
 * @property integer $brk_beneficiary_payout_status
 * @property string $brk_benefactor_payout_date
 * @property integer $brk_benefactor_payout_status
 * @property integer $brk_beneficiary_payout_ledgerId
 * @property integer $brk_benefactor_payout_ledgerId
 * @property integer $brk_beneficiarybenefit_received
 * @property integer $brk_benefactorbenefit_received
 * @property string $brk_create_date
 * @property string $brk_modified_on
 * @property integer $brk_active
 */
class BookingReferralTrack extends CActiveRecord
{

	public $bkg_create_date1;
	public $bkg_create_date2;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_referral_track';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('brk_beneficiary_id, brk_benefactor_id, brk_beneficiary_bkgId, brk_create_date', 'required'),
			array('brk_beneficiary_id, brk_benefactor_id, brk_isfirst_beneficiary, brk_last_benefactor_bkgId, brk_beneficiary_bkgId, brk_beneficiary_payout_status, brk_benefactor_payout_status, brk_beneficiary_payout_ledgerId, brk_benefactor_payout_ledgerId, brk_active,brk_beneficiarybenefit_received,brk_benefactorbenefit_received', 'numerical', 'integerOnly' => true),
			array('brk_beneficiary_payout_amt, brk_benefactor_payout_amt', 'length', 'max' => 10),
			array('brk_beneficiary_bkg_complete_date, brk_beneficiary_payout_date, brk_benefactor_payout_date, brk_modified_on', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('brk_id, brk_beneficiary_id, brk_benefactor_id, brk_isfirst_beneficiary, brk_last_benefactor_bkgId, brk_beneficiary_bkgId, brk_beneficiary_bkg_complete_date, brk_beneficiary_payout_amt, brk_benefactor_payout_amt, brk_beneficiary_payout_date, brk_beneficiary_payout_status, brk_benefactor_payout_date, brk_benefactor_payout_status, brk_beneficiary_payout_ledgerId, brk_benefactor_payout_ledgerId, brk_create_date, brk_modified_on, brk_active,brk_beneficiarybenefit_received,brk_benefactorbenefit_received', 'safe', 'on' => 'search'),
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
			'brk_id'							 => 'Brt',
			'brk_beneficiary_id'				 => 'Beneficiary Id',
			'brk_benefactor_id'					 => 'Benefactor Id',
			'brk_isfirst_beneficiary'			 => 'Isfirst Beneficiary',
			'brk_last_benefactor_bkgId'			 => 'Last Benefactor Booking Id',
			'brk_beneficiary_bkgId'				 => 'Beneficiary Booking Id',
			'brk_beneficiary_bkg_complete_date'	 => 'Beneficiary Booking Complete Date',
			'brk_beneficiary_payout_amt'		 => 'Beneficiary Payout Amount',
			'brk_benefactor_payout_amt'			 => 'Benefactor Payout Amount',
			'brk_beneficiary_payout_date'		 => 'Beneficiary Payout Date',
			'brk_beneficiary_payout_status'		 => '1:Paid,0:not paid;',
			'brk_benefactor_payout_date'		 => 'Benefactor Payout Date',
			'brk_benefactor_payout_status'		 => '1:Paid,0:not paid;',
			'brk_beneficiary_payout_ledgerId'	 => 'Beneficiary Payout Ledger',
			'brk_benefactor_payout_ledgerId'	 => 'Benefactor Payout Ledger',
			'brk_beneficiarybenefit_received'	 => 'Beneficiary Payout Received',
			'brk_benefactorbenefit_received'	 => 'Benefactor Payout Received',
			'brk_create_date'					 => 'Create Date',
			'brk_modified_on'					 => 'Modified On',
			'brk_active'						 => '0=>Inactive,1=>Active ',
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

		$criteria->compare('brk_id', $this->brk_id);
		$criteria->compare('brk_beneficiary_id', $this->brk_beneficiary_id);
		$criteria->compare('brk_benefactor_id', $this->brk_benefactor_id);
		$criteria->compare('brk_isfirst_beneficiary', $this->brk_isfirst_beneficiary);
		$criteria->compare('brk_last_benefactor_bkgId', $this->brk_last_benefactor_bkgId);
		$criteria->compare('brk_beneficiary_bkgId', $this->brk_beneficiary_bkgId);
		$criteria->compare('brk_beneficiary_bkg_complete_date', $this->brk_beneficiary_bkg_complete_date, true);
		$criteria->compare('brk_beneficiary_payout_amt', $this->brk_beneficiary_payout_amt, true);
		$criteria->compare('brk_benefactor_payout_amt', $this->brk_benefactor_payout_amt, true);
		$criteria->compare('brk_beneficiary_payout_date', $this->brk_beneficiary_payout_date, true);
		$criteria->compare('brk_beneficiary_payout_status', $this->brk_beneficiary_payout_status);
		$criteria->compare('brk_benefactor_payout_date', $this->brk_benefactor_payout_date, true);
		$criteria->compare('brk_benefactor_payout_status', $this->brk_benefactor_payout_status);
		$criteria->compare('brk_beneficiary_payout_ledgerId', $this->brk_beneficiary_payout_ledgerId);
		$criteria->compare('brk_benefactor_payout_ledgerId', $this->brk_benefactor_payout_ledgerId);
		$criteria->compare('brk_beneficiarybenefit_received', $this->brk_beneficiarybenefit_received);
		$criteria->compare('brk_benefactor_payout_ledgerId', $this->brk_benefactor_payout_ledgerId);
		$criteria->compare('brk_create_date', $this->brk_create_date, true);
		$criteria->compare('brk_modified_on', $this->brk_modified_on, true);
		$criteria->compare('brk_active', $this->brk_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingReferralTrack the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This function is used for fetching all booking type where referral may applies
	 * @return queryObject
	 */
	public static function getBookingForReferal($bkgId = null)
	{
		$where	 = "";
		$params	 = array();
		if ($bkgId != null)
		{
			$params['bkgId'] = $bkgId;
			$where			 .= " AND booking.bkg_id=:bkgId ";
		}
		else
		{
			$where .= " 
						AND bkg_status IN (2,3,5,6,7) 
						AND btr.bkg_platform IN (1,3)
						AND bkg_create_date BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 00:00:00') AND CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 23:59:59')
						AND bkg_confirm_user_type=1 
						AND bkg_create_user_type=1
						AND (bkg_create_user_id=bkg_confirm_user_id)
						AND bkg_confirm_user_id IS NOT NULL 
						AND bkg_create_user_id IS NOT NULL
					";
		}
		$sql = "SELECT 
					bkg_id,
					booking_user.bkg_user_id AS beneficiaryId,
					qr_code.qrc_ent_id AS benefactorId
				FROM booking 
					INNER JOIN booking_trail as btr ON btr.btr_bkg_id=booking.bkg_id AND  (booking.bkg_qr_id >0 AND booking.bkg_qr_id IS NOT NULL) 
					INNER JOIN booking_user ON booking_user.bui_bkg_id=booking.bkg_id
					INNER JOIN qr_code  ON qr_code.qrc_id=booking.bkg_qr_id AND qr_code.qrc_active=1 AND qr_code.qrc_status=3
					INNER JOIN users ON users.user_id=qr_code.qrc_ent_id AND qr_code.qrc_ent_type=1
				WHERE 1  $where
				AND users.user_id <> booking_user.bkg_user_id
				AND bkg_agent_id IS NULL
				ORDER BY bkg_create_date ASC";
		return DBUtil::query($sql, DBUtil::SDB(), $params);
	}

	/**
	 * This function is used for data setup for booking referral track
	 * @param type array
	 * @return retuenSet
	 * @throws Exception
	 */
	public static function add($row)
	{
		$returnSet	 = new ReturnSet();
		$success	 = false;
		try
		{
			$model									 = new BookingReferralTrack();
			$model->brk_beneficiary_id				 = $row['beneficiaryId'];
			$model->brk_benefactor_id				 = $row['benefactorId'];
			$model->brk_isfirst_beneficiary			 = 0;
			$model->brk_beneficiary_bkgId			 = $row['bkg_id'];
			$model->brk_last_benefactor_bkgId		 = null;
			$model->brk_beneficiary_payout_amt		 = null;
			$model->brk_beneficiary_payout_status	 = 0;
			$model->brk_benefactor_payout_amt		 = null;
			$model->brk_benefactor_payout_status	 = 0;
			$model->brk_create_date					 = DBUtil::getCurrentTime();
			$model->brk_modified_on					 = DBUtil::getCurrentTime();
			$model->brk_active						 = 1;
			if (!$model->save())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			$data = [];
			if ($model->brk_id > 0)
			{
				$returnSet->setData(['autoBookingReferalId' => $model->brk_id, 'beneficiaryBkgId' => $row['bkg_id']]);
				$success = true;
			}
			$returnSet->setStatus($success);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			
		}
		return $returnSet;
	}

	/* This function is check if it is first beneficiary for given pair of $beneficiary_id
	 * @param type benefactor_id
	 * @return type status
	 */

	public static function isFirstBeneficiary($beneficiary_id)
	{
		$params	 = ['beneficiaryId' => $beneficiary_id];
		$sql	 = "SELECT COUNT(*) FROM booking_referral_track WHERE 1 AND brk_isfirst_beneficiary=1 AND brk_beneficiary_id =:beneficiaryId  AND brk_active=1";
		$count	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		$status	 = 0;
		if ($count == 0)
		{
			$status = 1;
		}
		return $status;
	}

	/* This function is update beneficiary payout status/Date/
	 * @param type $bkgRefId=>bkg_referal_track Id
	 * @param type $payAmt=> payout amount
	 * @param type $actId=>Account Transaction Id
	 * @param type $bkgCompDate=> booking Complete Date
	 * @return type count
	 */

	public static function updateBeneficiaryData($bkgRefId, $payAmt, $actId, $bkgCompDate)
	{
		$sql	 = "UPDATE booking_referral_track SET brk_beneficiary_bkg_complete_date=:bkgCompDate,brk_beneficiary_payout_date=NOW(),brk_beneficiary_payout_status=1,brk_beneficiary_payout_ledgerId=:actId,brk_beneficiary_payout_amt=:payAmt WHERE 1 AND brk_id=:bkgRefId";
		$count	 = DBUtil::execute($sql, ['bkgCompDate' => $bkgCompDate, 'bkgRefId' => $bkgRefId, 'actId' => $actId, 'payAmt' => $payAmt]);
		return $count;
	}

	/* This function is update Benefactor payout status
	 * @param type $bkgRefId=>bkg_referal_track Id
	 * @param type $payAmt=> payout amount
	 * @param type $actId=>Account Transaction Id
	 * @param type $bkgCompDate=> booking Complete Date
	 * @return type count
	 */

	public static function updateBenefactorData($bkgRefId, $payAmt, $actId)
	{
		$params	 = ['bkgRefId' => $bkgRefId, 'actId' => $actId, 'payAmt' => $payAmt];
		$sql	 = "UPDATE booking_referral_track SET brk_benefactor_payout_date=NOW(),brk_benefactor_payout_status=1,brk_benefactor_payout_ledgerId=:actId ,brk_benefactor_payout_amt=:payAmt WHERE 1 AND brk_id=:bkgRefId";
		$count	 = DBUtil::execute($sql, ['bkgRefId' => $bkgRefId, 'actId' => $actId, 'payAmt' => $payAmt]);
		return $count;
	}

	/**
	 * This function is used for fetching all booking type where referral may applies
	 * @return queryObject
	 */
	public static function getBookingPayout($bkgId = null)
	{
		$where	 = "";
		$params	 = array();
		if ($bkgId != null)
		{
			$params['bkgId'] = $bkgId;
			$where			 .= " 
								 AND booking.bkg_id=:bkgId 
								 AND bkg_status IN (6,7) 
								";
		}
		else
		{
			$where .= " 
						AND bkg_status IN (6,7) 
						AND btr_mark_complete_date BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 5 DAY),' 00:00:00') AND CONCAT(DATE_SUB(CURDATE(),INTERVAL 5 DAY),' 23:59:59')
					  ";
		}
		$sql = "SELECT 
					bkg_id,
					btr_mark_complete_date,
					bkg_net_base_amount,
					brk_beneficiary_id,
					brk_benefactor_id,
					booking_referral_track.brk_id
				FROM booking 
					INNER JOIN booking_referral_track ON booking_referral_track.brk_beneficiary_bkgId=booking.bkg_id AND brk_beneficiarybenefit_received=0 AND brk_benefactorbenefit_received=0
					INNER JOIN booking_trail ON booking_trail.btr_bkg_id=booking.bkg_id 
					INNER JOIN booking_invoice ON booking_invoice.biv_bkg_id=booking.bkg_id 
				WHERE 1 $where
				AND brk_active=1 
				AND brk_beneficiary_payout_status=0
				AND brk_benefactor_payout_status=0
				ORDER BY btr_mark_complete_date ASC";
		return DBUtil::query($sql, DBUtil::SDB(), $params);
	}

	/* This function is update beneficiary/benefactor payout status in each booking id
	 * @param type $bkgId
	 * @param type $referralTrackId
	 * @param type $beneficiaryReceived
	 * @param type $benefactorReceived
	 * @return type status
	 */

	public static function updateData($referralTrackId, $beneficiaryReceived, $benefactorReceived)
	{
		$updateSet	 = "";
		$params		 = ['ReferralId' => $referralTrackId];
		if ($beneficiaryReceived == 1)
		{
			$params['beneficiaryReceived']	 = $beneficiaryReceived;
			$updateSet						 .= " brk_beneficiarybenefit_received=:beneficiaryReceived ";
		}
		else if ($benefactorReceived == 1)
		{
			$params['benefactorReceived']	 = $benefactorReceived;
			$updateSet						 .= " brk_benefactorbenefit_received=:benefactorReceived ";
		}
		$sql = "UPDATE booking_referral_track SET $updateSet WHERE 1 AND brk_id  =:ReferralId";
		return DBUtil::execute($sql, $params);
	}

	/* This function is used to process out beneficiary/benefactor payout processs
	 * @param type $row array
	 * @return type null
	 */

	public static function processPayout()
	{
		$result = BookingReferralTrack::getBookingPayout();
		foreach ($result as $row)
		{
			try
			{
				BookingReferralTrack::beneficiaryProcessPayout($row);
				BookingReferralTrack::benefactorProcessPayout($row);
				UsersReferral::updateStatus($row['brk_beneficiary_id'], $row['brk_benefactor_id']);
			}
			catch (Exception $ex)
			{
				ReturnSet::setException($ex);
			}
		}
	}

	/* This function is used to process out beneficiary payout related items
	 * @param type $row array
	 * @return type null
	 */

	public static function beneficiaryProcessPayout($row)
	{
		$details	 = BookingUser::getLastCompletedBkgId($row['brk_benefactor_id']);
		$transaction = DBUtil::beginTransaction();
		try
		{
			$isFirstBeneficiary		 = BookingReferralTrack::setFirstBeneficiary($row['brk_beneficiary_id'], $row['brk_id']);
			$beneficiaryPayoutAmt	 = BookingReferralTrack::setBeneficiaryPayoutAmount($isFirstBeneficiary, $row['bkg_net_base_amount']);
			$ledgerId				 = UserCredits::addBeneficaryReferralJoiningBonus((int) $beneficiaryPayoutAmt, $row['brk_beneficiary_id'], $row['brk_benefactor_id'], ['beneficiaryBkgId' => $row['bkg_id'], 'benefactorBkgId' => $details['bkg_id']]);
			BookingReferralTrack::updateBeneficiaryData($row['brk_id'], $beneficiaryPayoutAmt, $ledgerId->act_id, $row['btr_mark_complete_date']);
			BookingReferralTrack::updateData($row['brk_id'], 1, 0);
			DBUtil::commitTransaction($transaction);
			notificationWrapper::userReferral($row['brk_beneficiary_id'], $beneficiaryPayoutAmt);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			ReturnSet::setException($ex);
		}
	}

	/* This function is used to process out benefactor payout related items
	 * @param type $row array
	 * @return type null
	 */

	public static function benefactorProcessPayout($row)
	{
		$details				 = BookingUser::getLastCompletedBkgId($row['brk_benefactor_id']);
		$lastBookingCompletedCnt = BookingUser::getCompletedBookingByUserCount($row['brk_benefactor_id']);
		$transaction			 = DBUtil::beginTransaction();
		try
		{
			if ($lastBookingCompletedCnt > 0)
			{
				BookingReferralTrack::setBenefactorLastBkgId($row['brk_id'], $details['bkg_id']);
				$isBenefactorbenefitReceived = BookingReferralTrack::getBenefactorReceived($row['brk_benefactor_id']);
				$benefactorPayoutAmt		 = BookingReferralTrack::setBenefactorPayoutAmount($isBenefactorbenefitReceived, $details['bkg_net_base_amount']);
				$ledgerId					 = UserCredits::addBenefactorReferralJoiningBonus((int) $benefactorPayoutAmt, $row['brk_benefactor_id'], $row['brk_beneficiary_id'], ['beneficiaryBkgId' => $row['bkg_id'], 'benefactorBkgId' => $details['bkg_id']]);
				BookingReferralTrack::updateBenefactorData($row['brk_id'], $benefactorPayoutAmt, $ledgerId->act_id);
				BookingReferralTrack::updateData($row['brk_id'], 0, 1);
				DBUtil::commitTransaction($transaction);
				notificationWrapper::userReferred($row['brk_benefactor_id'], $row['brk_beneficiary_id']);
			}
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			ReturnSet::setException($ex);
		}
	}

	/* This function is update is first beneficiary flag in Booking Referral Track
	 * @param type $beneficiaryId
	 * @return type int
	 * @throws Exception
	 */

	public static function setFirstBeneficiary($beneficiaryId, $brtId)
	{
		$lastBookingCompletedCnt					 = BookingUser::getCompletedBookingByUserCount($beneficiaryId);
		$isfirst_beneficiary						 = $lastBookingCompletedCnt == 0 ? 1 : 0;
		$bkgRefTrackModel							 = BookingReferralTrack::model()->findByPk($brtId);
		$bkgRefTrackModel->brk_isfirst_beneficiary	 = $isfirst_beneficiary;
		if (!$bkgRefTrackModel->save())
		{
			throw new Exception(CJSON::encode($bkgRefTrackModel->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
		return $isfirst_beneficiary;
	}

	/* This function is update is first beneficiary flag   in Booking Referral Track
	 * @param type $beneficiaryId
	 * @return type int
	 */

	public static function setBeneficiaryPayoutAmount($isFirstbeneficiary, $amount)
	{
		$payoutAmount = $isFirstbeneficiary == 1 ? max(($amount * 0.1), 250) : min(125, $amount * 0.05);
		return $payoutAmount;
	}

	/* This function is update last bkg id for benefactor
	 * @param type brtId
	 * @return type bkgId
	 * @return type int
	 * @throws Exception
	 */

	public static function setBenefactorLastBkgId($brtId, $bkgId)
	{
		$bkgRefTrackModel							 = BookingReferralTrack::model()->findByPk($brtId);
		$bkgRefTrackModel->brk_last_benefactor_bkgId = $bkgId;
		if (!$bkgRefTrackModel->save())
		{
			throw new Exception(CJSON::encode($bkgRefTrackModel->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
		return $bkgRefTrackModel;
	}

	/* This function is update is first beneficiary flag   in Booking Referral Track
	 * @param type $beneficiaryId
	 * @return type int
	 * @throws Exception
	 */

	public static function setBenefactorPayoutAmount($benefactorBenefitReceived, $amount)
	{
		$payoutAmount = $benefactorBenefitReceived == 1 ? min(125, $amount * 0.05) : min(($amount * 0.1), 250);
		return $payoutAmount;
	}

	/* This function is used to get whether Benefactor Benefit Received or not for last   in Booking Referral Track
	 * @param type $beneficiaryId
	 * @return type int
	 * @throws Exception
	 */

	public static function getBenefactorReceived($benefactorId)
	{
		$sql = "SELECT brk_benefactorbenefit_received FROM booking_referral_track WHERE 1  AND brk_benefactor_id =:benefactorId  AND brk_active=1 ORDER BY brk_id DESC ";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), ['benefactorId' => $benefactorId]);
	}

	/* This function is used to get whether Beneficairy booking exists or not   in Booking Referral Track
	 * @param type $beneficiaryBkgId
	 * @return type int
	 * @throws Exception
	 */

	public static function isBeneficiaryBookingExistsByBkgId($beneficiaryBkgId)
	{
		$sql = "SELECT COUNT(*) AS cnt FROM booking_referral_track WHERE 1  AND brk_beneficiary_bkgId =:beneficiaryBkgId  AND brk_active=1";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), ['beneficiaryBkgId' => $beneficiaryBkgId]);
	}

	/* This function is used to get report based on booking referrak track 
	 * @param type $fromDate
	 * @param type $toDate
	 * @param type $type
	 * @return type dataprovider/array
	 */

	public static function fetchList($fromDate, $toDate, $type = DBUtil::ReturnType_Provider)
	{
		$where	 = "";
		$params	 = array();
		if ($fromDate != '' && $toDate != '')
		{
			$params['fromDate']	 = $fromDate;
			$params['toDate']	 = $toDate;
			$where				 = " AND  brk_create_date BETWEEN :fromDate AND :toDate ";
		}
		$sql = "SELECT
					brk_id,
					brk_beneficiary_id,
					brk_benefactor_id,
					brk_isfirst_beneficiary,
					brk_last_benefactor_bkgId,
					brk_beneficiary_bkgId,
					brk_beneficiary_bkg_complete_date,
					brk_beneficiary_payout_amt,
					brk_beneficiary_payout_date,
					brk_beneficiary_payout_status,
					brk_benefactor_payout_amt,
					brk_benefactor_payout_date,
					brk_benefactor_payout_status,
					brk_beneficiarybenefit_received,
					brk_benefactorbenefit_received,
					brk_create_date
				FROM booking_referral_track WHERE 1 $where ";

		if ($type == DBUtil::ReturnType_Provider)
		{
			$sqlCount		 = "SELECT brk_id FROM booking_referral_track WHERE 1 $where ";
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB(), $params);
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'params'		 => $params,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['brk_create_date'],
					'defaultOrder'	 => 'brk_id DESC'
				],
				'pagination'	 => ['pageSize' => 50],
			]);

			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB(), $params);
		}
	}

}

<?php

/**
 * This is the model class for table "ola_booking_update".
 *
 * The followings are the available columns in table 'ola_booking_update':
 * @property integer $obu_id
 * @property string $obu_bkg_booking_id
 * @property integer $obu_bkg_trip_distance
 * @property integer $obu_bkg_base_amount
 * @property integer $obu_bkg_driver_allowance_amount
 * @property integer $obu_bkg_toll_tax
 * @property integer $obu_bkg_state_tax
 * @property integer $obu_bkg_service_tax_rate
 * @property integer $obu_bkg_vendor_amount
 * @property integer $obu_bkg_vendor_collected
 * @property string $obu_status
 * @property integer $obu_active
 * @property string $obu_uplaoded_on
 * @property integer $obu_partner_id
 * @property integer $obu_uploaded_by
 * @property string $obu_updated_on
 * @property string $obu_old_data
 */
class OlaBookingUpdate extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ola_booking_update';
	}

	public $fileImage, $obu_upload_from_date, $obu_upload_to_date, $obu_updated_from_date, $obu_updated_to_date;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('obu_uplaoded_on', 'required'),
			array('obu_partner_id', 'required', 'message' => 'Please enter the value for partner Name', 'on' => 'uploaddata'),
			array('obu_bkg_trip_distance, obu_bkg_base_amount, obu_bkg_driver_allowance_amount, obu_bkg_toll_tax, obu_bkg_state_tax, obu_bkg_service_tax_rate, obu_bkg_vendor_amount, obu_bkg_vendor_collected, obu_active, obu_uploaded_by', 'numerical', 'integerOnly' => true),
			array('obu_bkg_booking_id', 'length', 'max' => 50),
			array('obu_status', 'length', 'max' => 255),
			array('obu_old_data', 'length', 'max' => 1000),
			array('obu_updated_on', 'safe'),
			array('fileImage',
				'file', 'types'		 => 'csv',
				'maxSize'	 => 5242880,
				'allowEmpty' => true,
				'wrongType'	 => 'Only csv allowed.',
				'tooLarge'	 => 'File too large! 5MB is the limit'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('obu_id, obu_bkg_booking_id, obu_bkg_trip_distance, obu_bkg_base_amount, fileImage,obu_bkg_driver_allowance_amount, obu_bkg_toll_tax, obu_bkg_state_tax, obu_bkg_service_tax_rate, obu_bkg_vendor_amount, obu_bkg_vendor_collected, obu_status, obu_active, obu_uplaoded_on, obu_uploaded_by, obu_updated_on, obu_old_data', 'safe', 'on' => 'search'),
			array('obu_id, obu_bkg_booking_id, obu_upload_from_date,obu_upload_to_date,obu_updated_from_date,obu_updated_to_date,obu_bkg_trip_distance, obu_bkg_base_amount, fileImage,obu_bkg_driver_allowance_amount, obu_bkg_toll_tax, obu_bkg_state_tax, obu_bkg_service_tax_rate, obu_bkg_vendor_amount, obu_bkg_vendor_collected, obu_status, obu_active, obu_uplaoded_on, obu_uploaded_by, obu_updated_on, obu_old_data ,obu_partner_id', 'safe'),
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
			'obu_id'							 => 'Obu',
			'obu_bkg_booking_id'				 => 'Obu Bkg Booking',
			'obu_bkg_trip_distance'				 => 'Obu Bkg Trip Distance',
			'obu_bkg_base_amount'				 => 'Obu Bkg Base Amount',
			'obu_bkg_driver_allowance_amount'	 => 'Obu Bkg Driver Allowance Amount',
			'obu_bkg_toll_tax'					 => 'Obu Bkg Toll Tax',
			'obu_bkg_state_tax'					 => 'Obu Bkg State Tax',
			'obu_bkg_service_tax_rate'			 => 'Obu Bkg Service Tax Rate',
			'obu_bkg_vendor_amount'				 => 'Obu Bkg Vendor Amount',
			'obu_bkg_vendor_collected'			 => 'Obu Bkg Vendor Collected',
			'obu_status'						 => 'Obu Status',
			'obu_active'						 => 'Obu Active',
			'obu_uplaoded_on'					 => 'Obu Uplaoded On',
			'obu_uploaded_by'					 => 'Obu Uploaded By',
			'obu_updated_on'					 => 'Obu Updated On',
			'obu_old_data'						 => 'Obu Old Data',
			'obu_partner_id'					 => 'Obu Partner Name'
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

		$criteria->compare('obu_id', $this->obu_id);
		$criteria->compare('obu_bkg_booking_id', $this->obu_bkg_booking_id, true);
		$criteria->compare('obu_bkg_trip_distance', $this->obu_bkg_trip_distance);
		$criteria->compare('obu_bkg_base_amount', $this->obu_bkg_base_amount);
		$criteria->compare('obu_bkg_driver_allowance_amount', $this->obu_bkg_driver_allowance_amount);
		$criteria->compare('obu_bkg_toll_tax', $this->obu_bkg_toll_tax);
		$criteria->compare('obu_bkg_state_tax', $this->obu_bkg_state_tax);
		$criteria->compare('obu_bkg_service_tax_rate', $this->obu_bkg_service_tax_rate);
		$criteria->compare('obu_bkg_vendor_amount', $this->obu_bkg_vendor_amount);
		$criteria->compare('obu_bkg_vendor_collected', $this->obu_bkg_vendor_collected);
		$criteria->compare('obu_status', $this->obu_status, true);
		$criteria->compare('obu_active', $this->obu_active);
		$criteria->compare('obu_uplaoded_on', $this->obu_uplaoded_on, true);
		$criteria->compare('obu_uploaded_by', $this->obu_uploaded_by);
		$criteria->compare('obu_updated_on', $this->obu_updated_on, true);
		$criteria->compare('obu_old_data', $this->obu_old_data, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OlaBookingUpdate the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getUnexecutedData($command = 0, $start = 0, $limit = 250)
	{
		Logger::create("command.olaBookingUpdate.getUnexecutedData start " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);
		$sql	 = "SELECT obu_id from ola_booking_update where obu_active = 0 LIMIT $start,$limit ";
		$data	 = DBUtil::queryAll($sql);
		$data	 = json_decode(json_encode($data), FALSE);
		Logger::create("command.olaBookingUpdate.getUnexecutedData start " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);
		return $data;
	}

	public function preExistBooking($booking)
	{
		$sql = "SELECT count(obu_id) cnt from ola_booking_update
		WHERE obu_bkg_booking_id ='$booking' AND obu_active IN(1,2)";

		$data = DBUtil::command($sql)->queryScalar();
		return $data;
	}

	public function executeUploaded($command = 0, $start = 0, $limit = 250)
	{
		Logger::create("command.ola.executeUploaded start " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);

		Logger::create("command.ola.getUnexecutedData start " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);
		$olaData	 = OlaBookingUpdate::model()->getUnexecutedData($command, 0, $limit);
		Logger::create("command.ola.getUnexecutedData ends " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);
		$transaction = null;
		$totMsg		 = '';
		foreach ($olaData as $obj)
		{
			$transaction = DBUtil::beginTransaction();
			$rowObj		 = self::model()->findByPk($obj->obu_id);
			$msg		 = '';
			$userInfo	 = UserInfo::model(UserInfo::TYPE_ADMIN, $rowObj->obu_uploaded_by);
			Logger::create("command.ola.getByCode start " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);
			$model		 = Booking::model()->getByCode($rowObj->obu_bkg_booking_id);
			Logger::create("command.ola.getByCode Ends " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);
			$booking	 = $rowObj->obu_bkg_booking_id;
			try
			{
				$preExistBooking = 0;
				if (!$model)
				{
					$remarks = "Booking not found in database";
					$status	 = 0;
					$active	 = 2;
					goto updateStatus;
				}

				$remarks = "Booking cannot be mark completed ";
				$status	 = 0;
				$active	 = 2;

				$isRestricted = BookingInvoice::validateDateRestriction($model->bkg_pickup_date);
				if ($isRestricted == false)
				{
					$remarks = "booking can not edit.Date restriction.";
					goto updateStatus;
				}
				if ($model->bkg_agent_id != $rowObj->obu_partner_id)
				{
					$remarks = "Booking does not belongs to the given partner";
					$active	 = 2;
					goto updateStatus;
				}

				if (!in_array($model->bkg_status, [2, 3, 5, 6]))
				{
					$remarks = "Booking status not valid";
					$active	 = 2;
					goto updateStatus;
				}
				if ($model->bkg_status == 6)
				{
					Logger::create("command.ola.undoActions start " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);
					$oldStatus = Booking::model()->undoActions($model->bkg_id, $userInfo);
					Logger::create("command.ola.undoActions Ends " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);

					Logger::create("command.ola.removeBookingPartnerCoinUsed start " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);
					$success = AccountTransactions::removeBookingPartnerCoinUsed($model->bkg_id);
					Logger::create("command.ola.removeBookingPartnerCoinUsed Ends " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);
					$model->refresh();
				}

				$oldModel		 = clone $model;
				$vendorAmount	 = $rowObj->obu_bkg_vendor_amount;
				Logger::create("command.ola.getDetailsbyId start " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);
				$oldData		 = Booking::model()->getDetailsbyId($model->bkg_id);
				Logger::create("command.ola.getDetailsbyId ends " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);
				if ($success)
				{
					$model->bkgInvoice->bkg_advance_amount	 = 0;
					$model->bkgInvoice->bkg_corporate_credit = 0;
				}
				$model->bkg_trip_distance						 = $rowObj->obu_bkg_trip_distance;
				$model->bkgInvoice->bkg_base_amount				 = $rowObj->obu_bkg_base_amount;
				$model->bkgInvoice->bkg_discount_amount			 = 0;
				$model->bkgInvoice->bkg_additional_charge		 = 0;
				$model->bkgInvoice->bkg_driver_allowance_amount	 = $rowObj->obu_bkg_driver_allowance_amount;
				$model->bkgInvoice->bkg_toll_tax				 = $rowObj->obu_bkg_toll_tax;
				$model->bkgInvoice->bkg_state_tax				 = $rowObj->obu_bkg_state_tax;
				$model->bkgInvoice->bkg_vendor_amount			 = $vendorAmount;
				$model->bkgInvoice->bkg_vendor_collected		 = $rowObj->obu_bkg_vendor_collected;
				$model->bkgInvoice->bkg_service_tax_rate		 = $rowObj->obu_bkg_service_tax_rate;
				$model->bkgInvoice->calculateTotal();

				Logger::create("command.ola.bkgInvoice start " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);
				$model->bkgInvoice->update();
				Logger::create("command.ola.bkgInvoice Ends " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);

				$model->bkgBcb->bcb_vendor_amount				 = $vendorAmount;

				Logger::create("command.ola.bkgBcb start " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);
				$model->bkgBcb->update();
				Logger::create("command.ola.bkgBcb ends " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);

				Logger::create("command.ola.olabookimgupdate start " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);
				$model->update();
				Logger::create("command.ola.olabookimgupdate ends " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);

				Logger::create("command.ola.updateProfitFlag start " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);
				BookingTrail::updateProfitFlag($model->bkg_bcb_id);
				Logger::create("command.ola.updateProfitFlag Ends " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);

				$active				 = 1;
				$remarks			 = "Accounts Updated";

				Logger::create("command.ola.getDetailsbyId start " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);
				$newData			 = Booking::model()->getDetailsbyId($model->bkg_id);
				Logger::create("command.ola.getDetailsbyId ends " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);

				$getOldDifference	 = array_diff_assoc($oldData, $newData);
				$getNewDifference	 = array_diff_assoc($newData, $oldData);

				$changesForLog			 = " Old Values: " . $this->getModificationMSG($getOldDifference, 'log') . " :: New Values: " . $this->getModificationMSG($getNewDifference, 'log');
				$evtList				 = BookingLog::model()->eventList();
				$rowObj->obu_old_data	 = json_encode($changesForLog);
				$bkgid					 = $model->bkg_id;
				$eventid				 = BookingLog::ACCOUNTS_DETAILS_MODIFIED;
				$logDesc				 = $evtList[$eventid];
				$desc					 = $logDesc . ' (through auto upload) ' . 'uploaded against ' . $rowObj->obu_id . $changesForLog;

				Logger::create("command.ola.createLog start " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);
				BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $oldModel);
				Logger::create("command.ola.createLog ends " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);

				if ($model->bkg_status != 5)
				{
					goto updateStatus;
				}
				Logger::create("command.ola.markComplete start " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);
				if (Booking::model()->markComplete($model->bkg_id, '', $rowObj->obu_id, $userInfo))
				{
					$remarks .= ", Marked Complete.";
				}
				Logger::create("command.ola.markComplete ends " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);

				updateStatus:
				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $e)
			{
				DBUtil::rollbackTransaction($transaction);
				$remarks = "Some error : " . $e->getMessage();
				$active	 = 0;
			}
			finally
			{
				$rowObj->obu_status		 = $remarks;
				$rowObj->obu_active		 = $active;
				$rowObj->obu_updated_on	 = new CDbExpression('NOW()');
				$rowObj->save();
			}

			if ($command == 0)
			{
				$msg .= "<div>";
			}

			$msg .= " Booking Id: " . $booking . " : " . $remarks;

			if ($command == 0)
			{
				$msg	 .= "</div>";
				$msg	 .= "<br>";
				$totMsg	 .= $msg;
			}
			if ($command == 1)
			{
				echo "\n";
				//$msg .= "\n";
				echo $msg;
			}
		}
		Logger::create("command.ola.executeUploaded Ends " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);
		return $totMsg;
	}

	public function executeUploaded1($command = 0)
	{
		$userInfo = UserInfo::getInstance();

		$olaData = OlaBookingUpdate::model()->getUnexecutedData();
		$msg	 = '';
		foreach ($olaData as $obuModel1)
		{
			$dataOla = [];

//            $bkgModel                 = new Booking();
//            $bkgModel->bkg_booking_id = $obuModel1->obu_bkg_booking_id;

			$userid	 = $obuModel1->obu_uploaded_by;
			$model	 = Booking::model()->findByBookingid($obuModel1->obu_bkg_booking_id);

			$booking		 = $obuModel1->obu_bkg_booking_id;
			$preExistBooking = OlaBookingUpdate::model()->preExistBooking($booking);
			if (!$model)
			{
				//ola_missing_booking
				$remarks = "Booking not found in database";
				$status	 = 0;
				$active	 = 2;
			}
			else
			{
				$remarks = "Booking cannot be mark completed ";
				$status	 = 0;
				$active	 = 0;

				if ($model->bkg_status == 6)
				{
					$remarks = "Booking is already marked complete";
					$active	 = 2;
				}

				if ($model->bkg_status == 5 && $preExistBooking == 0)
				{
					$trans = Yii::app()->db->beginTransaction();
					try
					{
						$oldData					 = Booking::model()->getDetailsbyId($model->bkg_id);
						$model->bkg_trip_distance	 = $obuModel1->obu_bkg_trip_distance;
						$model->bkg_base_amount		 = $obuModel1->obu_bkg_base_amount;

						$model->bkg_driver_allowance_amount	 = $obuModel1->obu_bkg_driver_allowance_amount;
						$model->bkg_toll_tax				 = $obuModel1->obu_bkg_toll_tax;
						$model->bkg_state_tax				 = $obuModel1->obu_bkg_state_tax;
						$model->bkg_vendor_amount			 = $obuModel1->obu_bkg_vendor_amount;
						$model->bkg_vendor_collected		 = $obuModel1->obu_bkg_vendor_collected;
						$model->bkg_service_tax_rate		 = $obuModel1->obu_bkg_service_tax_rate;


						$model->calculateTotal();


						$model->bkg_status = Booking::STATUS_COMPLETED;

						$model->update();

						$cabmodel = $model->getBookingCabModel();

						$cabmodel->bcb_trip_status	 = BookingCab::STATUS_TRIP_PARTIALLY_COMPLETED;
						$cabmodel->bcb_vendor_amount = $model->bkg_vendor_amount;
						$cabmodel->bcb_bkg_id1		 = $model->bkg_id;
						$cabmodel->setScenario('updatetripstatus');
						$cabmodel->save();

						$bkgamt					 = $model->bkg_total_amount;
						//$amtdue			 = $bkgamt - $model->getTotalPayment();
						$vndamt					 = $cabmodel->bcb_vendor_amount;
						$gzamount1				 = $model->bkg_gozo_amount;
						$gzamount				 = ($gzamount1 == '') ? $bkgamt - $vndamt : $gzamount1;
						// $gzdue				 = $gzamount - $model->getAdvanceReceived();
						$model->scenario		 = 'vendor_collected_update';
						$model->bkg_gozo_amount	 = round($gzamount);
						//$model->bkg_vendor_collected	 = round($amtdue);
						//$model->bkg_due_amount		 = $model->bkg_total_amount - $model->getTotalPayment();
						$partnerUltimateCredit	 = $model->bkg_total_amount - $model->getTotalPayment();
						$model->bkg_due_amount	 = 0;
						$success1				 = false;
						if ($model->validate())
						{

							$success = $model->save();
							$desc	 = "Booking marked as completed.";


							$eventid = BookingLog::BOOKING_MARKED_COMPLETED;
							BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventid, $oldModel);
							AccountTransactions::model()->AddVendorCollection($vndamt, $model->bkg_vendor_collected, $cabmodel->bcb_id, $model->bkg_id, $cabmodel->bcb_vendor_id);
						}

//header('Content-type: text/csv');

						if ($model->bkg_agent_id > 0 && $success1)
						{
							if ($partnerUltimateCredit != 0)
							{
								$bkid		 = $model->bkg_id;
								$agentid	 = $model->bkg_agent_id;
								$amount		 = $partnerUltimateCredit; //$model->bkg_agent_markup;
								$remarks	 = "Partner credit adjusted with the customer due";
								$transAmount = $amount;

								$agtcomm = $model->updateAdvance($transAmount, $model->bkg_pickup_date, PaymentType::TYPE_AGENT_CORP_CREDIT, $userInfo, null, $remarks);
								if ($agtcomm)
								{

									$eventid				 = BookingLog::AGENT_CREDIT_APPLIED;
									$desc					 = $remarks;
									$params['blg_ref_id']	 = $model->bkg_agent_id;
									BookingLog::model()->createLog($bkid, $desc, $userInfo, $eventid, $oldModel, $params);
								}
							}

							$remarks			 = "Executed";
							$active				 = 1;
							$newData			 = Booking::model()->getDetailsbyId($model->bkg_id);
							$getOldDifference	 = array_diff_assoc($oldData, $newData);
							$getNewDifference	 = array_diff_assoc($newData, $oldData);

							$changesForLog = " Old Values: " . $this->getModificationMSG($getOldDifference, 'log') .
									" :: New Values: " . $this->getModificationMSG($getNewDifference, 'log');

							$obuModel1->obu_old_data = json_encode($changesForLog);
							$trans->commit();
						}
					}
					catch (Exception $e)
					{
						$trans->rollback();

						$remarks = "Some error : " . $e->getMessage();
						$active	 = 0;
					}
				}
				if ($preExistBooking > 0)
				{
					$remarks = "Booking already processed before";
					$active	 = 2;
				}
			}
			$obuModel1->obu_status		 = $remarks;
			$obuModel1->obu_active		 = $active;
			$obuModel1->obu_updated_on	 = new CDbExpression('NOW()');
			$obuModel1->save();
			if ($command == 0)
			{
				$msg .= "<div>";
			}

			$msg .= " Booking Id: " . $booking . " : " . $remarks;

			if ($command == 0)
			{
				$msg .= "</div>";
				$msg .= "<br>";
			}
			if ($command == 1)
			{
				$msg .= "\n\n";
				echo $msg;
			}
		}
		return $msg;
	}

	public function getModificationMSG($diff)
	{
		$msg = '';
		if (count($diff) > 0)
		{

			if ($diff['bkg_additional_charge'])
			{
				$msg .= ' Additional Charge: ' . $diff['bkg_additional_charge'] . ',';
			}
			if ($diff['payable_amount'])
			{
				$msg .= ' Payable Amount: ' . $diff['payable_amount'] . ',';
			}
			if ($diff['bkg_driver_allowance_amount'])
			{
				$msg .= ' Driver allowance: ' . $diff['bkg_driver_allowance_amount'] . ',';
			}
			if ($diff['bkg_rate_per_km_extra'])
			{
				$msg .= ' Extra rate: ' . $diff['bkg_rate_per_km_extra'] . ',';
			}

			if ($diff['bkg_vendor_amount'])
			{
				$msg .= ' Vendor Amount: ' . $diff['bkg_vendor_amount'] . ',';
			}
			if ($diff['bkg_total_amount'])
			{
				$msg .= ' Booking Amount: ' . $diff['bkg_total_amount'] . ',';
			}
			if ($diff['bkg_gozo_amount'])
			{
				$msg .= ' Gozo Amount: ' . $diff['bkg_gozo_amount'] . ',';
			}
			if ($diff['bkg_advance_amount'])
			{
				$msg .= ' Customer Advance: ' . round($diff['bkg_advance_amount']) . ',';
			}
			if ($diff['bkg_vendor_collected'])
			{
				$msg .= ' Vendor Collected: ' . $diff['bkg_vendor_collected'] . ',';
			}
			if ($diff['bkg_refund_amount'])
			{
				$msg .= ' Amount Refunded: ' . $diff['bkg_refund_amount'] . ',';
			}
			if ($diff['bkg_due_amount'])
			{
				$msg .= ' Customer Payment due: ' . round($diff['bkg_due_amount']) . ',';
			}
			if ($diff['bkg_trip_distance'])
			{
				$msg .= ' Kms Driven: ' . $diff['bkg_trip_distance'] . ',';
			}
			if ($diff['bkg_convenience_charge'] != '')
			{
				$msg .= ' COD Charge: ' . round($diff['bkg_convenience_charge']) . ',';
			}
			if ($diff['bkg_driver_allowance_amount'] != '')
			{
				$msg .= ' Driver Allowance: ' . round($diff['bkg_driver_allowance_amount']) . ',';
			}
			if ($diff['bkg_credits_used'])
			{
				$msg .= ' Credits Used: ' . $diff['bkg_credits_used'] . ',';
			}
			if ($diff['bkg_base_amount'])
			{
				$msg .= ' Base Amount: ' . $diff['bkg_base_amount'] . ',';
			}

			$msg = rtrim($msg, ',');
		}
		return $msg;
	}

	public function getList($qry = [])
	{
		$sql = "SELECT obu.* , adm_fname adm_name
                from ola_booking_update obu JOIN admins on obu.obu_uploaded_by = adm_id WHERE 1 ";
		if ($this->obu_bkg_booking_id != '')
		{
			$bkgidArr	 = array_filter(explode(',', $this->obu_bkg_booking_id));
			$con		 = '';
			foreach ($bkgidArr as $key => $value)
			{
				$con .= "OR obu_bkg_booking_id LIKE '%$value%' ";
			}
			$con = ltrim($con, 'OR');
			$sql .= " AND $con";
		}
		if ($this->obu_status != '')
		{
			$arrList = $this->getStatusListArr();
			$stat	 = $arrList[$this->obu_status];
			$sql	 .= " AND obu_status LIKE '%$stat%'";
		}

		if ($this->obu_upload_from_date != '' && $this->obu_upload_to_date != '')
		{
			$sql .= " AND (DATE(obu_uplaoded_on) BETWEEN '{$this->obu_upload_from_date}' AND '{$this->obu_upload_to_date}' )";
		}
		if ($this->obu_updated_from_date != '' && $this->obu_updated_to_date != '')
		{
			$sql .= " AND (DATE(obu_updated_on) BETWEEN '{$this->obu_updated_from_date}' AND '{$this->obu_updated_to_date}' )";
		}


		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => [' obu_bkg_booking_id'],
				'defaultOrder'	 => 'obu_id DESC'], 'pagination'	 => ['pageSize' => 25],
		]);
		return $dataprovider;
	}

	public function getStatusList()
	{
		$sql	 = "SELECT distinct obu_status from ola_booking_update group by obu_status";
		$stList	 = DBUtil::queryAll($sql);
		$arr	 = [];
		foreach ($stList as $val)
		{
			$a		 = trim($val['obu_status']);
			$arr[$a] = $a;
		}
		var_dump($arr);
		exit;
		return $arr;
	}

	public function getStatusListArr()
	{
		$arr = ['1'	 => 'Booking already processed before',
			'2'	 => 'Booking cannot be mark completed',
			'3'	 => 'Booking is already marked complete',
			'4'	 => 'Executed',
			'5'	 => 'Executed after rectification'
		];
		return $arr;
	}

}

<?php

class OlaController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';
	public $email_receipient;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			//    ['allow', 'actions' => ['updateRateByOla'], 'roles' => ['updateRateByOla']],
			['allow', 'actions' => ['updateRateByOla', 'uploaddata', 'executeuploaded', 'list'], 'roles' => ['bulkInvoiceUpdate']],
			//['allow', 'actions' => [], 'users' => ['*']],
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function loadModel($id)
	{
		$model = Booking::model()->findByPk($id);
		if ($model === null)
		{
			throw new CHttpException(404, 'The requested page does not exist.');
		}
		return $model;
	}

	public function actionUpdateRateByOla()
	{
		//echo "proj";
		exit;
		header('Content-Disposition: attachment; filename="demo.csv"');
		header('Pragma: no-cache');
		header('Expires: 0');
		$fileExport	 = fopen('php://output', 'w');
		fputcsv($fileExport, array('BookingID', 'Status', 'Remarks'));
		$file		 = "olaDoc/dataOla.csv";
		if (($handle		 = fopen($file, 'r')) !== FALSE)
		{
			$row		 = 0;
			$firstRow	 = [];
			while (($data		 = fgetcsv($handle, 1000, ',')) !== FALSE)
			{
				$data1 = [];
				$row++;
				//$col_count	 = count($data);
				if ($row == 1)
				{
					$firstRow = $data;
					continue;
				}
				else
				{
					foreach ($data as $key => $value)
					{
						$data1[$firstRow[$key]] = $value;
					}
					$bkgModel						 = new Booking();
					$bkgModel->bkg_booking_id		 = $data1['bkg_booking_id'];
					$bkgModel->bkg_rate_per_km_extra = $data1['bkg_rate_per_km_extra'];
					$bkgModel->bkg_trip_distance	 = $data1['bkg_trip_distance'];
					$bkgModel->bkg_base_amount		 = $data1['bkg_base_amount'];

					$bkgModel->bkg_driver_allowance_amount	 = $data1['bkg_driver_allowance_amount'];
					$bkgModel->bkg_toll_tax					 = $data1['bkg_toll_tax'];
					$bkgModel->bkg_state_tax				 = $data1['bkg_state_tax'];
					$bkgModel->bkg_vendor_amount			 = $data1['bkg_vendor_amount'];
					$bkgModel->bkg_vendor_collected			 = $data1['bkg_vendor_collected'];
					$bkgModel->bkg_service_tax_rate			 = $data1['bkg_service_tax_rate'];
					$model									 = Booking::model()->findByBookingid($bkgModel->bkg_booking_id);
					$booking								 = $bkgModel->bkg_booking_id;
					if (!$model)
					{
						//ola_missing_booking
						$remarks = "Booking not found in database";
						$status	 = 0;
					}
					else
					{
						$remarks = "Booking cannot be mark completed ";
						$status	 = 0;
						if ($model->bkg_status == 5)
						{
							$trans = DBUtil::beginTransaction();
							try
							{
								$bkgModel->bkg_discount_amount		 = $model->bkg_discount_amount;
								$bkgModel->bkg_convenience_charge	 = $model->bkg_convenience_charge;
								$bkgModel->bkg_additional_charge	 = $model->bkg_additional_charge;

								$bkgModel->calculateTotal();
								$data1['bkg_service_tax']	 = $bkgModel->bkg_service_tax;
								$data1['bkg_total_amount']	 = $bkgModel->bkg_total_amount;
								$data1['bkg_due_amount']	 = $bkgModel->bkg_due_amount;
								$model->bkg_status			 = Booking::STATUS_COMPLETED;
								// unset($data1['bkg_booking_id']);
								$model->attributes			 = $data1;
								$model->update();

								$cabmodel = $model->getBookingCabModel();

								$cabmodel->bcb_trip_status	 = BookingCab::STATUS_TRIP_PARTIALLY_COMPLETED;
								$cabmodel->bcb_vendor_amount = $data1['bkg_vendor_amount'];
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
									$success	 = $model->save();
									$desc		 = "Booking marked as completed.";
									$userInfo	 = UserInfo::getInstance();
									$eventid	 = BookingLog::BOOKING_MARKED_COMPLETED;
									BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventid, $oldModel);

									AccountTransactions::model()->AddVendorCollection($vndamt, $model->bkg_vendor_collected, $cabmodel->bcb_id, $model->bkg_id, $cabmodel->bcb_vendor_id);
								}
//
//header('Content-type: text/csv');

								if ($model->bkg_agent_id > 0 && $success1)
								{
									if ($partnerUltimateCredit != 0)
									{
										$bkid		 = $model->bkg_id;
										$agentid	 = $model->bkg_agent_id;
										$amount		 = $partnerUltimateCredit; //$model->bkg_agent_markup;
										$remarks	 = "Partner wallet adjusted with the customer due";
										$transAmount = $amount;
										$agtcomm	 = $model->updateAdvance($transAmount, $model->bkg_pickup_date, PaymentType::TYPE_AGENT_CORP_CREDIT, $userInfo, null, $remarks);
//										if (!$agtcomm)
//										{
//											throw new Exception("Booking failed as partner credit limit exceeded.");
//										}

										if ($agtcomm)
										{
											$user_id				 = Yii::app()->user->getId();
											$eventid				 = BookingLog::AGENT_CREDIT_APPLIED;
											$desc					 = $remarks;
											$params['blg_ref_id']	 = $model->bkg_agent_id;
											BookingLog::model()->createLog($bkid, $desc, $userInfo, $eventid, $oldModel, $params);
										}
									}

									$remarks = "Done";
									$status	 = 1;
								}
								DBUtil::commitTransaction($trans);
							}
							catch (Exception $e)
							{
								DBUtil::rollbackTransaction($trans);
								$remarks = "Some problem in booking ";
								$status	 = 0;
							}
						}
					}
				}
				$row++;
				$rowexport = array($booking, $status, $remarks);
				fputcsv($fileExport, $rowexport);
			}
			fclose($handle);
		}
	}

	public function actionUploaddata()
	{
		exit;
		$model		 = new OlaBookingUpdate('uploaddata');
		$errorMsg	 = '';
		$arr1		 = Yii::app()->request->getParam('OlaBookingUpdate');
		if (isset($arr1))
		{
			$agtid				 = $arr1['obu_partner_id'];
			$model->fileImage	 = $arr1;
			$file				 = CUploadedFile::getInstance($model, "fileImage");
			$fp					 = fopen($file->tempName, 'r');
			if ($fp)
			{
				$row		 = 0;
				$firstRow	 = [];
				while (($data		 = fgetcsv($fp, 1000, ',')) !== FALSE)
				{
					$data1 = [];
					if ($row == 0)
					{
						$firstRow = $data;
						$row++;
						continue;
					}
					else
					{
						$userInfo = UserInfo::getInstance();
						foreach ($data as $key => $value)
						{
							$data1[$firstRow[$key]] = $value;
						}

						$obuModel									 = new OlaBookingUpdate();
						$obuModel->obu_bkg_booking_id				 = $data1['bkg_booking_id'];
						$obuModel->obu_bkg_trip_distance			 = $data1['bkg_trip_distance'];
						$obuModel->obu_bkg_base_amount				 = $data1['bkg_base_amount'];
						$obuModel->obu_partner_id					 = $agtid;
						$obuModel->obu_bkg_driver_allowance_amount	 = $data1['bkg_driver_allowance_amount'];
						$obuModel->obu_bkg_toll_tax					 = $data1['bkg_toll_tax'];
						$obuModel->obu_bkg_state_tax				 = $data1['bkg_state_tax'];
						$obuModel->obu_bkg_vendor_amount			 = round($data1['bkg_vendor_amount']);
						$obuModel->obu_bkg_vendor_collected			 = round($data1['bkg_vendor_collected']);
						$obuModel->obu_bkg_service_tax_rate			 = $data1['bkg_service_tax_rate'];
						$obuModel->obu_uplaoded_on					 = new CDbExpression('NOW()');
						$obuModel->obu_uploaded_by					 = $userInfo->getUserId();
						if ($obuModel->save())
						{
							$row++;
						}
					}
				}
				$errorMsg = "Total " . ($row - 1) . " records uploaded";
			}
			else
			{
				$errorMsg = "No file uploaded";
			}
		}
		$olaData = OlaBookingUpdate::model()->getUnexecutedData();

		$this->render('olaupload', ['model' => $model, 'olaData' => count($olaData), 'errorMsg' => $errorMsg, 'msg' => $msg]);
	}

	public function actionExecuteuploaded()
	{
		exit;
		$msg = OlaBookingUpdate::model()->executeUploaded();
		echo '<div><a type="button" href="/admpnl/ola/uploaddata"  class="btn btn-primary">Upload more data</a></div>';
		echo "<br>" . "<br>";
		echo $msg;

		//$this->forward('ola/uploaddata', true);
//	$this->redirect(array('uploaddata', 'msg' => $msg));
		exit;
	}

	public function actionList($qry = [])
	{

		$this->pageTitle = "Ola Booking List";
		$pageSize		 = Yii::app()->params['listPerPage'];

		/* @var $model ol */
		$model = new OlaBookingUpdate();
		if ($_REQUEST['OlaBookingUpdate'])
		{

			$qry				 = Yii::app()->request->getParam('OlaBookingUpdate');
			$model->attributes	 = array_filter($qry);
		}


		$dataProvider = $model->getList($qry);

		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
		$this->render('list', array('model' => $model, 'dataProvider' => $dataProvider, 'qry' => $qry));
	}

}

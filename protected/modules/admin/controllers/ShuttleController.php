<?php

class ShuttleController extends Controller
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
			'accessControl', //perform access control for CRUD operations
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
			array('allow', 'actions'	 => ['add', 'getTimeSlot', 'calculatefare', 'getdetails'],
				'users'		 => array('@'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionAdd()
	{
		$this->pageTitle = "Create new Shuttle schedule";
		$model			 = new Shuttle('addnew');
		if (isset($_POST['Shuttle']))
		{
			$timeslot					 = Yii::app()->request->getParam('timeSlot');
			$shuttleRequest				 = Yii::app()->request->getParam('Shuttle');
			$pickup_start				 = $shuttleRequest['pickup_start'];
			$model->attributes			 = $shuttleRequest;
			$model->slt_pickup_datetime	 = $pickup_start . ' 00:00:00';
			$result						 = CActiveForm::validate($model);
			$return						 = [];

			if ($result == '[]')
			{
				$totRecords		 = 0;
				$entry			 = 0;
				$availability	 = $shuttleRequest['slt_availability'];
				$pickup_start	 = $shuttleRequest['pickup_start'];
				$pickup_end		 = $shuttleRequest['pickup_end'];

				while ($pickup_start <= $pickup_end)
				{
					foreach ($timeslot as $value)
					{
						for ($a = 0; $a < $availability; $a++)
						{
							$model						 = new Shuttle();
							$model->attributes			 = Yii::app()->request->getParam('Shuttle');
							$model->slt_pickup_datetime	 = $pickup_start . ' ' . date('H:i:s', strtotime($value));
							$model->slt_created_at		 = new CDbExpression('NOW()');
							$model->slt_created_by		 = UserInfo::getUserId();
							$totRecords++;
							if ($model->save())
							{
								$entry++;
							}
						}
					}
					$pickup_start = date('Y-m-d', strtotime($pickup_start . " +1 days"));
				}
				$data = ['success' => true, 'message' => "Total $totRecords of $entry saved"];
			}
			else
			{
				$data = ['success' => false, 'errors' => CJSON::decode($result)];
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				echo json_encode($data);
				Yii::app()->end();
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('add', array('model' => $model), false, $outputJs);
	}

	public function actionGetTimeSlot()
	{
		$slotType	 = Yii::app()->request->getParam('slotType');
		$interval	 = Shuttle::model()->SplitTime($slotType);
		echo json_encode($interval);
	}

	public function actionCalculatefare()
	{
		$shuttleRequest	 = Yii::app()->request->getParam('Shuttle');
		$tollTax		 = $shuttleRequest['slt_toll_tax'] | 0;
		$stateTax		 = $shuttleRequest['slt_state_tax'] | 0;
		$vendorAmt		 = $shuttleRequest['slt_vendor_amount'] | 0;
		$noOfSeat		 = $shuttleRequest['slt_seat_availability'];
		$mapCabType		 = [4 => 2, 6 => 3];
		$vht			 = $mapCabType[$noOfSeat];
		$defMarkup		 = Quotation::model()->getCabDefaultMarkup($vht);
		$gstPerc		 = Filter::getServiceTaxRate();
		$vndBaseAmount	 = $vendorAmt - ($tollTax + $stateTax);
		$rockPerc		 = round($vndBaseAmount * (1 + (2 / 100)));
		$sellBaseAmount	 = round($rockPerc * (1 + ($defMarkup / 100)));

		$gst		 = round($sellBaseAmount * ($gstPerc / 100));
		$totalAmount = $sellBaseAmount + $gst + $tollTax + $stateTax;

		$rate = ['success'		 => true,
			'totalAmount'	 => $totalAmount,
			'gst'			 => $gst,
			'sellBaseAmount' => $sellBaseAmount
		];
		echo CJSON::encode($rate);
	}

	public function actionGetdetails()
	{
		$fcity		 = Yii::app()->request->getParam('fromCity');
		$tcity		 = Yii::app()->request->getParam('toCity');
		$noOfSeat	 = Yii::app()->request->getParam('noOfSeat');
		$mapCab		 = Shuttle::map_cab_type();
		$mapCabType	 = Shuttle::map_cab_type();
		$rateModel	 = Rate::model()->getRatebyCitiesnVehicletype($fcity, $tcity, $mapCab[$noOfSeat]);
		$rate		 = ['success' => false];

		$fulltollTax		 = $rateModel->rte_toll_tax | 0;
		$fullstateTax		 = $rateModel->rte_state_tax | 0;
		$fullvendorAmt		 = $rateModel->rte_vendor_amount | 0;
		$fulldriverAllowance = 250;
		$perSeatPart		 = $noOfSeat - 1;

		$tollTax		 = round($fulltollTax / $perSeatPart);
		$stateTax		 = round($fullstateTax / $perSeatPart);
		$vendorAmt		 = round($fullvendorAmt / $perSeatPart);
		$driverAllowance = round($fulldriverAllowance / $perSeatPart);

		$vht			 = $mapCabType[$noOfSeat];
		$defMarkup		 = Quotation::model()->getCabDefaultMarkup($vht);
		$vndBaseAmount	 = $vendorAmt - ($tollTax + $stateTax);
		$rockPerc		 = round($vndBaseAmount * (1 + (2 / 100)));
		$sellBaseAmount	 = round($rockPerc * (1 + ($defMarkup / 100)));

		$bkgInv = new BookingInvoice();

		$bkgInv->bkg_driver_allowance_amount = $driverAllowance;
		$bkgInv->bkg_base_amount			 = $sellBaseAmount;
		$bkgInv->bkg_toll_tax				 = $tollTax;
		$bkgInv->bkg_state_tax				 = $stateTax;
		$bkgInv->calculateTotal();
		$totalAmount						 = $bkgInv->bkg_total_amount;
		$gst								 = $bkgInv->bkg_service_tax;

		if ($rateModel)
		{
			$rate = [
				'success'			 => true,
				'toll_tax'			 => $tollTax,
				'state_tax'			 => $stateTax,
				'vendor_amount'		 => $vendorAmt,
				'full_vendor_amount' => $fullvendorAmt,
				'driver_allowance'	 => $driverAllowance,
				'total_amount'		 => $totalAmount,
				'sell_base_amount'	 => $sellBaseAmount,
				'gst'				 => $gst
			];
		}
		echo CJSON::encode($rate);
	}
}

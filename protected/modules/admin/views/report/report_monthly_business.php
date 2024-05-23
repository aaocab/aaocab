<style>
    .panel-body {
        padding-top: 0;
        padding-bottom: 0;
    }
    .table>tbody>tr>th {
        vertical-align: middle
    }
    .table>tbody>tr>td, .table>tbody>tr>th {
        padding: 7px;
        line-height: 1.5em;
    }
</style>
<?php
if (isset($data['report1']))
{
	$totalAmount = $data['report1']['total_amount']; // Total GMV
}
if (isset($data['report2']) && count($data['report2']) > 0)
{
	$onewayTrips			 = $data['report2']['oneway_trips'] > 0 ? $data['report2']['oneway_trips'] : '0';
	$roundTrips				 = $data['report2']['round_trips'] > 0 ? $data['report2']['round_trips'] : '0';
	$onewayTripDays			 = $data['report2']['oneway_trip_days'];
	$roundTripDays			 = $data['report2']['round_trip_days'];
	$dedicatedVendorTrips	 = $data['report2']['dedicated_vendor_trips'] > 0 ? $data['report2']['dedicated_vendor_trips'] : '0';
	$floatingVendorTrips	 = $data['report2']['floating_vendor_trips'] > 0 ? $data['report2']['floating_vendor_trips'] : '0';
}

$totalTrips		 = trim($onewayTrips + $roundTrips);
$totalTripDays	 = trim($onewayTripDays + $roundTripDays);
$onewayPercent	 = (($onewayTrips * 100) / $totalTrips);
$roundwayPercent = (($roundTrips * 100) / $totalTrips);

if (isset($data['report4']) && count($data['report4']) > 0)
{

	//$user_id = (Yii::app()->user->getId() > 0) ? Yii::app()->user->getId() : '';
	$driver1s	 = trim($data['report4']['drivers1s']) > 0 ? trim($data['report4']['drivers1s']) : '0';
	$driver2s	 = trim($data['report4']['drivers2s']) > 0 ? trim($data['report4']['drivers2s']) : '0';
	$driver3s	 = trim($data['report4']['drivers3s']) > 0 ? trim($data['report4']['drivers3s']) : '0';
	$driver4s	 = trim($data['report4']['drivers4s']) > 0 ? trim($data['report4']['drivers4s']) : '0';
	$driver5s	 = trim($data['report4']['drivers5s']) > 0 ? trim($data['report4']['drivers5s']) : '0';
	$driverTotal = round(($driver1s + $driver2s + $driver3s + $driver4s + $driver5s), 2);

	$car1s		 = trim($data['report4']['car1s']) > 0 ? trim($data['report4']['car1s']) : '0';
	$car2s		 = trim($data['report4']['car2s']) > 0 ? trim($data['report4']['car2s']) : '0';
	$car3s		 = trim($data['report4']['car3s']) > 0 ? trim($data['report4']['car3s']) : '0';
	$car4s		 = trim($data['report4']['car4s']) > 0 ? trim($data['report4']['car4s']) : '0';
	$car5s		 = trim($data['report4']['car5s']) > 0 ? trim($data['report4']['car5s']) : '0';
	$carTotal	 = round(($car1s + $car2s + $car3s + $car4s + $car5s), 2);

	$vendor1s	 = trim($data['report4']['vendor1s']);
	$vendor2s	 = trim($data['report4']['vendor2s']);
	$vendor3s	 = trim($data['report4']['vendor3s']);
	$vendor4s	 = trim($data['report4']['vendor4s']);
	$vendor5s	 = trim($data['report4']['vendor5s']);
	$vendorTotal = round(($vendor1s + $vendor2s + $vendor3s + $vendor4s + $vendor5s), 2);
}

if (isset($data['report5']) && count($data['report5']) > 0)
{
	$bookingTotal		 = trim($data['report5']['TotalBooking']);
	$bookingByApp		 = trim($data['report5']['ByApp']) > 0 ? trim($data['report5']['ByApp']) : '0';
	$bookingByWeb		 = trim($data['report5']['ByWeb']) > 0 ? trim($data['report5']['ByWeb']) : '0';
	$bookingByAdmin		 = trim($data['report5']['ByPhone']) > 0 ? trim($data['report5']['ByPhone']) : '0';
	$bookingByAgent		 = trim($data['report5']['ByAgent']) > 0 ? trim($data['report5']['ByAgent']) : '0';
	$unqueCustomers		 = trim($data['report5']['uniqueCustomer']);
	$newCustomers		 = trim($data['report5']['countNewCustomer']);
	$repeatCustomers	 = trim($data['report5']['repeatCustomer']);
	$repeatCustomerTrips = trim($data['report5']['repeatCustomerTrips']);
}

if (isset($data['report6']) && count($data['report6']) > 0)
{
	$review1s		 = trim($data['report6']['review1s']) > 0 ? trim($data['report6']['review1s']) : '0';
	$review2s		 = trim($data['report6']['review2s']) > 0 ? trim($data['report6']['review2s']) : '0';
	$review3s		 = trim($data['report6']['review3s']) > 0 ? trim($data['report6']['review3s']) : '0';
	$review4s		 = trim($data['report6']['review4s']) > 0 ? trim($data['report6']['review4s']) : '0';
	$review5s		 = trim($data['report6']['review5s']) > 0 ? trim($data['report6']['review5s']) : '0';
	$totalReview	 = trim($data['report6']['totalReview']) > 0 ? trim($data['report6']['totalReview']) : '0';
	$totalRequest	 = trim($data['report6']['totalRequest']);
}

if (isset($data['report6']) && count($data['report6']) > 0)
{
	$totalVendorRatingCount	 = 0;
	$vendor1s				 = 0;
	$vendor2s				 = 0;
	$vendor3s				 = 0;
	$vendor4s				 = 0;
	$vendor5s				 = 0;
	foreach ($data['report7'] as $vendors)
	{
		if ($vendors['vendorRating'] != NULL)
		{
			if ($vendors['vendorRating'] == '1' || $vendors['vendorRating'] == '2' || $vendors['vendorRating'] == '3' || $vendors['vendorRating'] == '4' || $vendors['vendorRating'] == '5')
			{
				$totalVendorRatingCount = ($totalVendorRatingCount + $vendors['vendorRatingCount']);
			}
			if ($vendors['vendorRating'] == '1')
			{
				$vendor1s = $vendors['vendorRatingCount'];
			}
			if ($vendors['vendorRating'] == '2')
			{
				$vendor2s = $vendors['vendorRatingCount'];
			}
			if ($vendors['vendorRating'] == '3')
			{
				$vendor3s = $vendors['vendorRatingCount'];
			}
			if ($vendors['vendorRating'] == '4')
			{
				$vendor4s = $vendors['vendorRatingCount'];
			}
			if ($vendors['vendorRating'] == '5')
			{
				$vendor5s = $vendors['vendorRatingCount'];
			}
		}
	}
}
$dataProvider = $data['report3'];
?><div class="row m0">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-body">
				<?php
				if ($email != 1)
				{
					?>
					<div class="row">
						<?php
						$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
							'id'					 => 'monthly-report-form', 'enableClientValidation' => true,
							'clientOptions'			 => array(
								'validateOnSubmit'	 => true,
								'errorCssClass'		 => 'has-error'
							),
							// Please note: When you enable ajax validation, make sure the corresponding
							// controller action is handling ajax validation correctly.
							// See class documentation of CActiveForm for details on this,
							// you need to use the performAjaxValidation()-method described there.
							'enableAjaxValidation'	 => false,
							'errorMessageCssClass'	 => 'help-block',
							'htmlOptions'			 => array(
								'class' => '',
							),
						));
						/* @var $form TbActiveForm */
						?>
						<div class="col-xs-12 col-sm-4 col-md-3">
							<div class="form-group">
								<label class="control-label">Select Month</label>
								<?php
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'month',
									'val'			 => $month,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression(Booking::model()->getMonthList()), 'allowClear' => true),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Month')
								));
								?>
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-3">
							<div class="form-group">
								<label class="control-label">Select Year</label>
								<?php
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'year',
									'val'			 => $year,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression(Booking::model()->getYearList()), 'allowClear' => true),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Year')
								));
								?>
							</div>
						</div>
						<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">
							<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?>
						</div>
						<?php $this->endWidget(); ?>
					</div>
				<?php } ?>
                <div id="emailText">
                    <div class="row" style="margin-top: 5px">
                        <div class="col-xs-12 col-sm-7 col-md-4">
                            <table class="table table-bordered" style="">
                                <thead>
                                    <tr style="color: black;background: whitesmoke">
                                        <th><u>Month</u></th>
										<th><u>Report</u></th>
									</tr>
                                </thead>
                                <tbody id="count_booking_row">
                                    <tr >
                                        <td style="border-top : 1px solid grey;font-style: italic; width:50%">Total GMV</td>
                                        <td style="border-top : 1px solid grey;"><i class="fa fa-inr"><?php echo $data['report1']['total_amount'] > 0 ? $data['report1']['total_amount'] : '0'; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border-top : 1px solid grey;font-style: italic;">Total Gozo amount</td>
                                        <td style="border-top : 1px solid grey;"><i class="fa fa-inr"></i><?php echo $data['report1']['gozo_amount'] > 0 ? $data['report1']['gozo_amount'] : '0'; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border-top : 1px solid grey;font-style: italic;">Gozo Revenue % </td>
                                        <td style="border-top : 1px solid grey;"></i><?php echo round(100 * ($data['report1']['gozo_amount'] / $data['report1']['total_amount']), 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border-top : 1px solid grey;font-style: italic;">Bookings  #Completed</td>
                                        <td style="border-top : 1px solid grey;"><?php echo $data['report1']['book_completed'] > 0 ? $data['report1']['book_completed'] : '0'; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border-top : 1px solid grey;font-style: italic;">Bookings  #Canceled</td>
                                        <td style="border-top : 1px solid grey;"><?php echo $data['report1']['book_canceled'] > 0 ? $data['report1']['book_canceled'] : '0'; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border-top : 1px solid grey;font-style: italic;">Bookings  #Pending</td>
                                        <td style="border-top : 1px solid grey;"><?php echo $data['report1']['book_pending'] > 0 ? $data['report1']['book_pending'] : '0'; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border-top : 1px solid grey;font-style: italic;">Bookings  #Deleted</td>
                                        <td style="border-top : 1px solid grey;"><?php echo $data['report1']['book_deleted'] > 0 ? $data['report1']['book_deleted'] : '0'; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border-top : 1px solid grey;font-style: italic;">Trip Days</td>
                                        <td style="border-top : 1px solid grey;"><?php echo $totalTripDays; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border-top : 1px solid grey;font-style: italic;">Km Driven</td>
                                        <td style="border-top : 1px solid grey;"><?php echo $data['report1']['book_trip_distance'] > 0 ? $data['report1']['book_trip_distance'] : '0'; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border-top : 1px solid grey;font-style: italic;">Total Cities active</td>
                                        <td style="border-top : 1px solid grey;"><?php echo ($dataProvider->totalItemCount) > 0 ? ($dataProvider->totalItemCount) : '0'; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border-top : 1px solid grey;font-style: italic;">Source Cities active</td>
                                        <td style="border-top : 1px solid grey;"><?php echo $data['report9']['source_city'] > 0 ? $data['report9']['source_city'] : '0'; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border-top : 1px solid grey;font-style: italic;">Destination Cities active</td>
                                        <td style="border-top : 1px solid grey;"><?php echo $data['report9']['destination_city'] > 0 ? $data['report9']['destination_city'] : '0'; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-xs-12 col-sm-5 col-md-8">

                            <table width="300" class="table table-bordered" style="">
                                <tr>
                                    <td style="text-align: center;">&nbsp;</td>
                                    <td style="text-align: center;"><b>count</b></td>
                                    <td style="text-align: center;"><b>%</b></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;"><b>One Way trips</b></td>
                                    <td style="text-align: center;"><?php echo $onewayTrips; ?></td>
                                    <td style="text-align: center;"><?php echo round($onewayPercent, 2); ?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;"><b>Round trips</b></td>
                                    <td style="text-align: center;"><?php echo $roundTrips; ?></td>
                                    <td style="text-align: center;"><?php echo round($roundwayPercent, 2); ?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;"><b>Total</b></td>
                                    <td style="text-align: center;"><?php echo $totalTrips; ?></td>
                                    <td>&nbsp;</td>
                                </tr>
                            </table>
                            <div><b>#Avg size of trip in days:</b>&nbsp; <?php echo round(($totalTripDays / $totalTrips), 2); ?><br></div>
                            <div><b>#Avg ticket size this month:</b>&nbsp;<i class="fa fa-inr"></i><?php echo round(($totalAmount / $totalTrips), 2); ?><br></div>
                            <div><b># of Trips breakdown: </b></div>
                            <div><b>#Floating Vendor Trips:</b>&nbsp; <?php echo $floatingVendorTrips; ?><br></div>
                            <div><b>#Dedicated Vendor Trips:</b>&nbsp; <?php echo $dedicatedVendorTrips; ?><br></div>
                            <div><b>Cities metrics:</b></div>
                            <div id="citieMetricTable" class="table table-bordered">
								<?php
								if (!empty($dataProvider))
								{
									/* @var $dataProvider TbGridView */
									$params									 = array_filter($_REQUEST);
									$dataProvider->getPagination()->params	 = $params;
									$dataProvider->getSort()->params		 = $params;
									$this->widget('booster.widgets.TbGridView', array(
										'responsiveTable'	 => true,
										'dataProvider'		 => $dataProvider,
										'pager'				 => ['maxButtonCount' => 5, 'class' => 'booster.widgets.TbPager'],
										'id'				 => 'reportCitieMetriGrid',
										'template'			 => "<div class='panel-heading'><div class='row m0'>
                                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                                    </div></div>
                                                    <div class='panel-body table-responsive'>{items}</div>
                                                    ",
										'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
										'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
										'columns'			 => array(
											array('name' => 'cty_name', 'value' => '$data[cty_name]', 'sortable' => true, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array('class' => 'col-xs-2 text-center'), 'header' => 'City name'),
											array('name'	 => 'totalCityTrips', 'value'	 => function($data) {
													echo $data['totalCityTrips'] <> NULL ? $data['totalCityTrips'] : '0';
												}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Total trips'),
											array('name'	 => 'fromCityTrips', 'value'	 => function($data) {
													echo $data['fromCityTrips'] <> NULL ? $data['fromCityTrips'] : '0';
												}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Source count'),
											array('name'	 => 'toCityTrips', 'value'	 => function($data) {
													echo $data['toCityTrips'] <> NULL ? $data['toCityTrips'] : '0';
												}, 'sortable'								 => true, 'htmlOptions'							 => array('class' => 'text-center'), 'headerHtmlOptions'						 => array('class' => 'col-xs-2 text-center'), 'header'								 => 'Destination count'),
									)));
								}
								?>
                            </div>
                            <div><b>Number of attached vendors :</b><?php echo $data['report9']['attach_vendor_count'] > 0 ? $data['report9']['attach_vendor_count'] : '0'; ?></div>
                            <div><b>Number of operators in system:</b><?php echo $data['report9']['floating_vendor_count'] > 0 ? $data['report9']['floating_vendor_count'] : '0'; ?></div>
                            <div><b>Average Number of cars per operator:</b>&nbsp;<?php
								$avgCarVendor	 = round(($data['report9']['floating_car_count'] / $data['report9']['floating_vendor_count']), 1);
								echo $avgCarVendor > 0 ? $avgCarVendor : '0';
								?></div>
                            <div><b>Numbers of cars in system:</b>&nbsp; <?php echo $data['report9']['vehicle_count'] > 0 ? $data['report9']['vehicle_count'] : '0'; ?><br></div>
                            <div><b>Numbers of drivers  in system:</b>&nbsp;<?php echo $data['report9']['driver_count'] > 0 ? $data['report9']['driver_count'] : '0'; ?><br></div>
                            <div><b>Reviews:</b></div>
                            <div>
                                <table width="500" class="table table-bordered" style="">
                                    <tr>
                                        <td style="text-align: center;"><b>Requested</b></td>
                                        <td style="text-align: center;"><b>Received</b></td>
                                        <td style="text-align: center;"><b>#1s</b></td>
                                        <td style="text-align: center;"><b>#2s</b></td>
                                        <td style="text-align: center;"><b>#3s</b></td>
                                        <td style="text-align: center;"><b>#4s</b></td>
                                        <td style="text-align: center;"><b>#5s</b></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;"><?php echo $totalRequest; ?></td>
                                        <td style="text-align: center;"><?php echo $totalReview; ?></td>
                                        <td style="text-align: center;"><?php echo $review1s; ?></td>
                                        <td style="text-align: center;"><?php echo $review2s; ?></td>
                                        <td style="text-align: center;"><?php echo $review3s; ?></td>
                                        <td style="text-align: center;"><?php echo $review4s; ?></td>
                                        <td style="text-align: center;"><?php echo $review5s; ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div><b>Ratings:</b></div>
                            <div>
                                <table width="500" class="table table-bordered" style="">
                                    <tr>
                                        <td style="text-align: center;"><b>&nbsp;</b></td>
                                        <td style="text-align: center;"><b>Total</b></td>
                                        <td style="text-align: center;"><b>#1s</b></td>
                                        <td style="text-align: center;"><b>#2s</b></td>
                                        <td style="text-align: center;"><b>#3s</b></td>
                                        <td style="text-align: center;"><b>#4s</b></td>
                                        <td style="text-align: center;"><b>#5s</b></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;"><b>Drivers</b></td>
                                        <td style="text-align: center;"><?php echo $driverTotal; ?></td>
                                        <td style="text-align: center;"><?php echo $driver1s; ?></td>
                                        <td style="text-align: center;"><?php echo $driver4s; ?></td>
                                        <td style="text-align: center;"><?php echo $driver3s; ?></td>
                                        <td style="text-align: center;"><?php echo $driver4s; ?></td>
                                        <td style="text-align: center;"><?php echo $driver5s; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;"><b>Cars</b></td>
                                        <td style="text-align: center;"><?php echo $carTotal; ?></td>
                                        <td style="text-align: center;"><?php echo $car1s; ?></td>
                                        <td style="text-align: center;"><?php echo $car2s; ?></td>
                                        <td style="text-align: center;"><?php echo $car3s; ?></td>
                                        <td style="text-align: center;"><?php echo $car4s; ?></td>
                                        <td style="text-align: center;"><?php echo $car5s; ?></td>

                                    </tr>
                                    <tr>
                                        <td style="text-align: left;"><b>Vendors</b></td>
                                        <td style="text-align: center;"><?php echo $totalVendorRatingCount; ?></td>
                                        <td style="text-align: center;"><?php echo $vendor1s; ?></td>
                                        <td style="text-align: center;"><?php echo $vendor2s; ?></td>
                                        <td style="text-align: center;"><?php echo $vendor3s; ?></td>
                                        <td style="text-align: center;"><?php echo $vendor4s; ?></td>
                                        <td style="text-align: center;"><?php echo $vendor5s; ?></td>

                                    </tr>
                                </table>
                            </div>
                            <div><b>Customers:</b></div>
                            <div><b>#Total unique customers:</b>&nbsp;<?php echo $unqueCustomers; ?></div>
                            <div><b>#New customers acquired this month:</b>&nbsp;<?php echo $data['report8']['newCustomers'] > 0 ? $data['report8']['newCustomers'] : '0'; ?></div>
                            <div><b>#Trips from repeat customers:</b>&nbsp;<?php echo $repeatCustomerTrips; ?> </div>
                            <div><b>#Returning customers:</b>&nbsp;<?php echo $repeatCustomers; ?></div>
                            <div>&nbsp;</div>
                            <div><b>Bookings by platform</b></div>
                            <table width="500" class="table table-bordered" style="">
                                <tr>
                                    <td style="text-align: center;"><b>Total Bookings</b></td>
                                    <td style="text-align: center;"><b>By App</b></td>
                                    <td style="text-align: center;"><b>By web</b></td>
                                    <td style="text-align: center;"><b>By phone</b></td>
                                    <td style="text-align: center;"><b>Via Agent</b></td>
                                    <td style="text-align: center;"><b>Via agent</b></td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;"><?php echo $bookingTotal; ?></td>
                                    <td style="text-align: center;"><?php echo $bookingByApp; ?></td>
                                    <td style="text-align: center;"><?php echo $bookingByWeb; ?></td>
                                    <td style="text-align: center;"><?php echo $bookingByAdmin; ?></td>
                                    <td style="text-align: center;"><?php echo $bookingByAgent; ?></td>
                                    <td style="text-align: center;"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$version		 = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>
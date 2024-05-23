<div class="container">
	<div class="row">
		<div id="flashdiv" class="col-12">
			<div class="row">
				<div class="col-12 mt-1">
					<div class="card">
						<div class="card-body pt15 pb15">
							<?php
							$autoAddressJSVer = Yii::app()->params['autoAddressJSVer'];
							Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/v3/bookingRoute.js?v=$autoAddressJSVer");

							$form				 = $this->beginWidget('CActiveForm', array(
								'id'					 => 'quote_request_search', 'enableClientValidation' => true,
								'clientOptions'			 => array(
									'validateOnSubmit'	 => true,
									'errorCssClass'		 => 'has-error'
								),
								'enableAjaxValidation'	 => false,
								'errorMessageCssClass'	 => 'help-block',
								'htmlOptions'			 => array(
									'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data'
								),
							));
							?>
							<div class="row ">
								<div class="col-12 col-md-3">
									<div class="form-group">
										<label class="control-label">Source</label>
										<?php
										$widgetId			 = $ctr . "_" . random_int(99999, 10000000);
										$this->widget('application.widgets.BRCities', array(
											'type'				 => 1,
											'enable'			 => ($index == 0),
											'widgetId'			 => $widgetId,
											'model'				 => $model,
											'attribute'			 => 'cav_from_city',
											'useWithBootstrap'	 => true,
											"placeholder"		 => "Select City",
										));
										?> 
									</div>
								</div>
								<div class="col-12 col-md-3"> 
									<div class="form-group">
										<label class="control-label">Destination</label>
										<?php
										$this->widget('application.widgets.BRCities', array(
											'type'				 => 2,
											'widgetId'			 => $widgetId,
											'model'				 => $model,
											'attribute'			 => 'cav_to_cities',
											'useWithBootstrap'	 => true,
											"placeholder"		 => "Select City",
										));
										?> 
									</div>
								</div>
								<div class="col-12 col-md-3"> 
									<label class="control-label">Date Of Travel</label>
									<div class="input-group"><div class="input-group-prepend"><span class="input-group-text"><img src="/images/bx-calendar.svg" width="18"></span></div>
										<?php
										$minDate			 = date('Y-m-d');
										$formattedMinDate	 = DateTimeFormat::DateToDatePicker($minDate);
										echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
											'model'			 => $model,
											'attribute'		 => 'from_date',
											'options'		 => array('autoclose' => true, 'dateFormat' => 'dd/mm/yy', 'minDate' => $formattedMinDate),
											'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Date of travel', 'readonly'		 => 'readonly',
												'value'			 => $model->from_date, 'id'			 => 'CabAvailabilities_from_date' . $widgetId,
												'class'			 => 'form-control input-style')
												), true);
										?> 
									</div>
								</div>
								<div class="col-12 col-md-2 text-center"> 
									<input type="submit" name="submit" value="Filter" class='btn btn-primary pl-5 pr-5' style="margin-top: 23px;">  
								</div>
							</div>
							<?php $this->endWidget(); ?>
						</div>
					</div>
				</div>

			</div>

		</div>
	</div>
	<div class="row mb-1" style="display: flex; flex-wrap: wrap; justify-content:center;">
		<?php
		$cntBookings		 = count($models);
		foreach($models as $key => $value)
		{
			?>

			<div class="col-xl-3 col-md-6 col-sm-12 mb20">
				<div class="card text-center pt5 flex4">
					<div class="fls text-uppercase">flashsale</div>
					<span class="text-center"><img src="/images/cabs/car-indica.jpg" width="150" class="img-fluid" alt="singleminded"></span>
					<div class="sales-info-content">
						<h6 class="mb-0 font-14 weight600 heading-line-2 text-uppercase"><?= $value['cabModel'] ?></h6>
					</div>
					<div class="text-center p10 pb0">
						<p class="text-center mb0 weight500"><?= $value['sourceCity'] ?><img src="/images/bx-arrowright.svg" alt="img" width="13" height="13"> <?= $value['destinationCity'] ?></p>
						<div class="badge badge-pill mt5 bg-gray3 color-black"><img src="/images/bx-calendar.svg" alt="img" width="12" height="12"> On: <b class="weight500"><!--June 07, 2022--> <?= date('F d, Y', strtotime($value['start'])) ?></b></div>
					</div>
					<div class="card-body text-left p15" style="min-height: 90px; height: 90px;">
						<div class="d-flex justify-content-between mb-1">
							<div class="sales-info d-flex align-items-center">
								<div class="badge badge-circle badge-circle-md badge-circle-light-secondary badge-circle-gray mr10">
									<img src="/images/bx-alarm.svg" alt="img" width="13" height="13">
								</div>
								<div class="sales-info-content">
									<small class="text-muted">Must Depart Between</small>
									<h6 class="mb-0 weight500 font-12"><?= date('h:i A', strtotime($value['start'])) ?> and <?= date('h:i A', strtotime($value['expiry'])) ?></h6>
								</div>
							</div>
						</div>
						<!--						<div class="d-flex justify-content-between mb-1">
													<div class="sales-info d-flex align-items-center">
														<div class="badge badge-circle badge-circle-md badge-circle-light-secondary badge-circle-gray mr10">
															<img src="/images/bx-car3.svg" alt="img" width="13" height="13">
														</div>
														<div class="sales-info-content">
															<small class="text-muted">Car available</small>
															<h6 class="mb-0 weight500 font-12"><?= $value['cabModel'] ?></h6>
														</div>
													</div>
												</div>-->
						<div class="d-flex justify-content-between">
							<div class="sales-info d-flex align-items-center">
								<div class="badge badge-circle badge-circle-md badge-circle-light-secondary badge-circle-gray mr10">
									<img src="/images/bx-gas-pump.svg" alt="img" width="13" height="13">
								</div>
								<div class="sales-info-content">
									<small class="text-muted">Fuel Type</small>
									<h6 class="mb-0 weight500 font-12"><?= (($value['fuelType'] != '') ? " Fuel Type: " . $value['fuelType'] : '') ?></h6>
								</div>
							</div>
						</div>
					</div>
					<?php
					$routeRate	 = CabAvailabilities::calculateQuoteRate($value, '', true);
					$hash		 = Yii::app()->shortHash->hash($value['cavid']);
					?>
					<div class="card-footer bg-blue4 p10 mb10 text-left">
						<div class="d-flex justify-content-between">
							<div class="sales-info d-flex align-items-center">
								<div class="sales-info-content">
									<small class="text-muted"><span class="font-13 del-diagonal color-gray ">₹ <?= $routeRate->totalAmount ?></span></small>
									<h6 class="mb-0"><span class="font-16 weight600">₹<?= $value['Amount'] ?></span> <span class="font-11">(inc. GST)</span></h6>
								</div>
							</div>
							<h6 class="mb-0"><a href="javascript:void(0);" class="btn btn-primary pl-1 pr-1 font-14 mt5 showcabdetails"  onclick="validateFlashSale('<?= $hash ?>');">Book Now</a></h6>
						</div>
					</div>
				</div>
			</div>

			<?php
		}
		if($cntBookings == 0)
		{
			echo '<span class="color-red">No results found</span>';
		}
		?>
	</div>
    <div class="col-12 mt20 mb20">
		<?php
		$this->widget('booster.widgets.TbPager', array('pages' => $usersList->pagination));
		?>
    </div>
</div>
<script type="text/javascript">
	
	function validateFlashSale(hash) { //debugger;
		var cavhash = hash;
		$('#BookingTemp_hash').val(cavhash);
		var csrf = $('#quote_request_search').find("INPUT[name=YII_CSRF_TOKEN]").val();
		$href = "/booking/infoNew";
		jQuery.ajax({type: 'POST',
			url: $href,
			data: {"cavhash": cavhash, "flashBooking": 1, YII_CSRF_TOKEN: csrf},
			"dataType": "html", "async": false,
			"success": function (data1) { //debugger;
				data = JSON.parse(data1);
				location.href = data.data.url;
			},
			error: function (xhr, ajaxOptions, thrownError)
			{
				if (xhr.status == "403")
				{
					handleException(xhr, function ()
					{
						validateFlashSale(hash);
					});
				}
			}
		});
	}

</script>
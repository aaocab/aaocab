
<style>
	.pagination {
		margin: 0px 0 !important;
	}
</style>
<div class="app-content content">
	<div class="content-overlay"></div>
	<div class="content-wrapper">
		<div class="content-body">
			<section>
				<div class="container-fluid">
					<div class="row d-flex justify-content-center">
						<div class="col-12 mob-view">
							<div class="card">
								<div class="card-body">
									<?php
									$url = Yii::app()->createUrl('supplier/booking/list');
									if ($model->bkg_status == 2)
									{
										$status	 = 'new';
										$url	 = Yii::app()->createUrl('supplier/booking/list', ['status' => 'new']);
									}
									else if ($model->bkg_status == 3)
									{
										$status	 = 'assigned';
										$url	 = Yii::app()->createUrl('supplier/booking/list', ['status' => 'assigned']);
									}
									else if ($model->bkg_status == 5)
									{
										$status	 = 'allocated';
										$url	 = Yii::app()->createUrl('supplier/booking/list', ['status' => 'allocated']);
									}
									else if ($model->bkg_status == 6)
									{
										$status	 = 'completed';
										$url	 = Yii::app()->createUrl('supplier/booking/list', ['status' => 'completed']);
									}
									else if ($model->bkg_status == 9)
									{
										$status	 = 'cancelled';
										$url	 = Yii::app()->createUrl('supplier/booking/list', ['status' => 'cancelled']);
									}
									else if ($model->bkg_status == 'partnernew')
									{
										$status	 = 'partnernew';
										$url	 = Yii::app()->createUrl('supplier/booking/list', ['status' => 'partnernew']);
									}

									$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
										'id'					 => 'bookingviewfilter',
										'enableClientValidation' => true,
										'clientOptions'			 => array(
											'validateOnSubmit'	 => true,
											'errorCssClass'		 => 'has-error',
									),
										// Please note: When you enable ajax validation, make sure the corresponding
										// controller action is handling ajax validation correctly.
										// See class documentation of CActiveForm for details on this,
										// you need to use the performAjaxValidation()-method described there.
										'enableAjaxValidation'	 => false,
										'errorMessageCssClass'	 => 'help-block',
										'htmlOptions'			 => array('class' => '',),
										'action'				 => $url,
									));
									/* @var $form TbActiveForm */
									?>
									<div class="row">
										<input type="hidden" id="user-id" name="Booking[bkg_status]" value="<?= $model->bkg_status ?>">
										<div class="col-12 col-lg-4 col-xl-3">
											<?= $form->textFieldGroup($model, 'trip_id', array('label' => 'Booking Id/ Trip Id', 'htmlOptions' => array('placeholder' => 'Search By Booking Id/Trip Id'))) ?>
										</div>
										<?php
										if ($model->bkg_status != 2 && $model->bkg_status != 'partnernew')
										{
											?>
											<div class="col-12 col-lg-4 col-xl-3">
												<div class="form-group">
													<?= $form->textFieldGroup($model, 'bkg_name', array('label' => 'Name', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Name', 'class' => 'nameFilterMask')))) ?>
												</div>
											</div>
											<? } ?>
										<div class="col-12 col-lg-4 col-xl-6">
													<?= $form->textFieldGroup($model, 'search', array('label' => 'Others(Pickup/Drop Address,Instruction to Driver/Vendor)', 'htmlOptions' => array('placeholder' => 'search by  other information'))) ?>
										</div>
										<div class="col-12 col-lg-4 col-xl-3">
											<div class="form-group">
												<label class="control-label">Pickup Date</label>
												<?php
												$daterang			 = "Select Pickup Date Range";
												$bkg_pickup_date1	 = ($model->bkg_pickup_date1 == '') ? '' : $model->bkg_pickup_date1;
												$bkg_pickup_date2	 = ($model->bkg_pickup_date2 == '') ? '' : $model->bkg_pickup_date2;
												if ($bkg_pickup_date1 != '' && $bkg_pickup_date2 != '')
												{
													$daterang = date('F d, Y', strtotime($bkg_pickup_date1)) . " - " . date('F d, Y', strtotime($bkg_pickup_date2));
												}
												?>
												<div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
													<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
													<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
												</div>
												<?= $form->hiddenField($model, 'bkg_pickup_date1'); ?>
<?= $form->hiddenField($model, 'bkg_pickup_date2'); ?>

											</div>
										</div>
										<?php
										if ($model->bkg_status != 2 && $model->bkg_status != 'partnernew')
										{
											?>
											<div class="col-12 col-lg-4 col-xl-3">
												<div class="form-group">
													<?php
													$daterang	 = "Select Assigned Date Range";
													$createdate1 = ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1;
													$createdate2 = ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2;
													if ($createdate1 != '' && $createdate2 != '')
													{
														$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
													}
													?>
													<label  class="control-label">Assigned Date</label>
													<div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
														<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
														<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
													</div>
													<?php
													echo $form->hiddenField($model, 'bkg_create_date1');
													echo $form->hiddenField($model, 'bkg_create_date2');
													?>
												</div>
											</div>
<? } ?>
										<div class="col-12 col-lg-2 col-xl-3">
											<div class="form-group">
												<label class="control-label">Cab Type</label>
												<?php
												$returnType		 = "listCategory";
												$vehicleList	 = SvcClassVhcCat::getVctSvcList($returnType);
												$this->widget('booster.widgets.TbSelect2', array(
													'model'			 => $model,
													'attribute'		 => 'bkg_vehicle_type_id',
													'val'			 => $model->bkg_vehicle_type_id,
													'data'			 => $vehicleList,
													'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
														'placeholder'	 => 'Select Car Type')
												));
												?>
											</div>
										</div>
										<div class="col-12 col-lg-2 col-xl-3">
											<div class="form-group">
												<label class="control-label">Booking Type</label>
												<?php
												$bookingTypesArr = $model->booking_type;
												unset($bookingTypesArr[2]);
												$this->widget('booster.widgets.TbSelect2', array(
													'model'			 => $model,
													'attribute'		 => 'bkgtypes',
													'val'			 => $model->bkgtypes,
													'data'			 => $bookingTypesArr,
													//'asDropDownList' => FALSE,
													//'options' => array('data' => new CJavaScriptExpression($datacity), 'allowClear' => true,),
													'htmlOptions'	 => array('style'			 => 'width:100%',
														'multiple'		 => 'multiple',
														'placeholder'	 => 'Booking Type')
												));
												?>
											</div>
										</div>
										<div class="col-12 text-right">
											<button type="submit" class="btn btn-primary round position-relative">Search</button>
										</div>

									</div>
<?php $this->endWidget(); ?>
								</div>
							</div>
						</div>
						<div class="col-12 col-xl-12 mob-view" id="bookingDetailsView">
							<?php
							if (!empty($dataProvider))
							{
								$this->widget('booster.widgets.TbGridView', array(
									'responsiveTable'	 => true,
									'dataProvider'		 => $dataProvider,
									'selectableRows'	 => 2,
									'id'				 => 'driverListGrid',
									'template'			 => "<div class='card-heading mt20'><div class='row m0'>
										<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6'>{pager}</div>
										</div></div>
										<div class='card-body'>{items}</div>
										<div class='card-footer'><div class='row m0'><div class='col-xs-12 col-sm-6'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'card table-responsive panel panel-default compact'),
									//'ajaxType' => 'POST',
									'columns'			 => array(
										array('name' => 'bcb_id', 'value' => 'CHtml::link($data["bcb_id"], Yii::app()->createUrl("supplier/booking/tripdetails",array("tripId"=>$data["bcb_id"])), array("target"=>"_blank"))','type'=>'raw', 'header' => 'Trip Id'),
										array('name'	 => 'bkg_booking_type', 'value'	 => function($data) {
												echo Booking::model()->booking_type[$data['bkg_booking_type']];
											}, 'header' => 'Trip type'),
										array('name' => 'scv_label', 'value' => '$data["scv_label"]', 'header' => 'Cab type'),
										array('name'	 => 'bkg_pickup_date', 'value'	 => function($data) {
												$date			 = new DateTime($data['bkg_pickup_date']);
												$formattedDate	 = $date->format('d/m/Y h:i A');
												echo $formattedDate;
											}, 'header'			 => 'Pickup date'),
										array('name'	 => 'route_name', 'value'	 => function($data) {
												echo BookingRoute::model()->getRouteNameByBcb($data['bcb_id']);
											}, 'header' => 'Routes'),
										array('name' => 'bkg_vendor_amount', 'value' => '($data["bkg_status"]==2)?"₹".$data["recommended_vendor_amount"]:"₹".$data["bkg_vendor_amount"]', 'header' => 'Your amount','htmlOptions'=>['style'=>'text-align:right;']),
										array('name'		 => 'bcb_cab_number',
											'visible'	 => in_array($model->bkg_status, [5, 6]),
											'value'		 => '$data["bcb_cab_number"]', 'header'	 => 'Cab number'),
										array('name' => 'drv_name', 'visible' => in_array($model->bkg_status, [5, 6]), 'value' => '$data["drv_name"]', 'header' => 'Driver name'),
										array('name' => 'agt_name', 'visible' => ($model->bkg_status == "partnernew"), 'value' => function($data){
												$agtdata = Agents::getById($data['bkg_agent_id']);
												if($agtdata['agt_company']!='')
												{
													echo $agtdata['agt_company'];	
												}
												else
												{
													echo $agtdata['agt_fname'].' '.$agtdata['agt_lname'];
												}
										}, 'header' => 'Partner name'),
										array('name' => 'bkg_status', 'visible' => $status == '', 'value' => function($data){
											if($data['bkg_status'] == 3)
											{
												echo 'Pending Assignment';
											}
											if($data['bkg_status'] == 5)
											{
												echo 'Upcoming/On Going Trip';
											}
											if($data['bkg_status'] == 6 || $data['bkg_status'] == 7)
											{
												echo 'Completed';
											}
											if($data['bkg_status'] == 9)
											{
												echo 'Cancelled';
											}
										}, 'header' => 'Status'),
										array(
											'header'			 => 'Action',
											'class'				 => 'CButtonColumn',
											'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center'),
											'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
											'template'			 => '{view}{bid}{bidaccept}{biddeny}',
											'buttons'			 => array(
												'view'			 => array(
													'url'		 => 'Yii::app()->createUrl("supplier/booking/tripdetails", array("tripId"=>$data[bcb_id]))',
													'imageUrl'	 => false,
													'label'		 => '<img src="/images/supplier/icon-6.svg" alt="img">',
													'options'	 => array('style' => 'margin-right: 4px', 'class' => 'p5 btnview', 'title' => 'Details','target'=>'_blank'),
												),
												'bid'		 => array(
													'click'		 => 'function(e){
															
															$href = $(this).attr(\'href\');
															let arrhref = $href.split("_");
															$("#recommAmt").val(arrhref[0]);
															$("#bcbId").val(arrhref[1]);
															if(arrhref[2]>0){
															$("#prevBidAmt").html("Your previous bid amount is &#x20B9;"+arrhref[2]);
															}
															e.preventDefault();
													}',
												    'url'		 => '$data[recommended_vendor_amount]."_".$data[bcb_id]."_".$data[bvr_bid_amount]',
													'imageUrl'	 => false,
													'visible'	 => '$data[bkg_status] == 2 && ($data[is_biddable]==0 || $data[is_biddable]==1)',
													'label'		 => '<img src="/images/supplier/icon-4.svg" alt="img">',
													'options'	 => array('data-toggle' => 'modal', 'data-target'=>'#default', 'style' => 'margin-right: 4px', 'class' => 'btnbid p5', 'title' => 'Bid'),
												),
												'bidaccept'		 => array(
													'click'		 => 'function(e){
															
															$href = $(this).attr(\'href\');
															let arrhref = $href.split("_");
															$("#acceptAmt").html(arrhref[0]);
															$("#acceptAmtVal").val(arrhref[0]);
															$("#bcbIdAcceptBid").val(arrhref[1]);
															e.preventDefault();
													}',
												    'url'		 => '$data[acptAmount]."_".$data[bcb_id]',
													'imageUrl'	 => false,
													'visible'	 => '$data[bkg_status] == 2 && $data[is_biddable]==0',
													'label'		 => '<img src="/images/supplier/icon-5.svg" alt="img">',
													'options'	 => array('data-toggle' => 'modal', 'data-target'=>'#acceptBid', 'style' => 'margin-right: 4px', 'class' => 'p5 btnbidaccept', 'title' => 'Accept Bid'),
												),
												'biddeny'		 => array(
													'click'		 => 'function(e){
															
															var con = confirm("Are you sure to deny this bid?");
															if(con){
															
																	$href = $(this).attr(\'href\');
																	 jQuery.ajax({  type: \'POST\',
																					url: $href,
																					"dataType": "json",
																					data: {"YII_CSRF_TOKEN": $(\'input[name="YII_CSRF_TOKEN"]\').val()},
																					success: function (data1)
																					   { debugger;
																							if (data1.success) 
																							 {
																								alert(data1.message);
																								location.reload();
																							 } 
																						   else 
																							{
																								alert(data1.errors[0]);
																							}
																					  }
																				  });
																}
															e.preventDefault();
													}',
												    'url'		 => 'Yii::app()->createUrl("supplier/booking/bidaccept", array(\'tripId\' => $data[bcb_id],\'action\' => 0))',
													'imageUrl'	 => false,
													'visible'	 => '$data[bkg_status] == 2  && ($data[is_biddable]==0 || $data[is_biddable]==1)',
													'label'		 => '<img src="/images/supplier/icon-7.svg" alt="img">',
													'options'	 => array( 'style' => 'margin-right: 4px', 'class' => 'p5 btnbiddeny', 'title' => 'Deny Bid'),
												),
												'htmlOptions'	 => array('class' => 'center', 'style' => 'margin-left: 4px;'),
											))
									)
								  )
								);
							}
							?>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>
<div class="modal modal-borderless text-left" id="default" tabindex="-1" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="false">
		<div class="modal-dialog modal-dialog-scrollable">
			<div class="modal-content">
			<form id="bidform">
				<div class="modal-header">
					<h3 class="modal-title" id="myModalLabel1">Bid your best price</h3>
					<button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
						<i class="bx bx-x"></i>
					</button>
 
				</div>
				<div class="modal-body">
					<div class="col-12">
					    <input type="hidden" name="YII_CSRF_TOKEN" value= "<?= Yii::app()->request->csrfToken ?>">
						<input type="hidden" name="action" value="1">
						<input type="hidden" name="tripId" id="bcbId">
						<input type="number" title="Please enter exactly 6 digits"  name="amount" id="recommAmt" class="form-control"  onkeydown="limit(this, 6);" onkeyup="limit(this, 6);" required>
					</div>
					<div class="col-12"><span id="prevBidAmt"></span></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary ml-1" data-dismiss="modal" onclick="checkBid(1);">
						<i class="bx bx-check d-block d-sm-none"></i>
						<span class="d-none d-sm-block">Bid</span>
					</button>
				</div>
			</form>
			</div>
		</div>
</div>

<div class="modal text-left" id="acceptBid" tabindex="-1" aria-labelledby="myModalLabel19" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm">
		<div class="modal-content">
		<form id="bidacceptform">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel19">Are you sure ?</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i class="bx bx-x"></i>
				</button>
			</div>
			<div class="modal-body">
				<input type="hidden" name="YII_CSRF_TOKEN" value= "<?= Yii::app()->request->csrfToken ?>">
				<input type="hidden" name="action" value="2">
				<input type="hidden" name="tripId" id="bcbIdAcceptBid">
				<input type="hidden" name="amount" id="acceptAmtVal">
				Accept this trip at &#x20B9;<span id="acceptAmt"></span>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light-secondary btn-sm" data-dismiss="modal">
					<i class="bx bx-x d-block d-sm-none"></i>
					<span class="d-sm-block d-none">Close</span>
				</button>
				<button type="button" class="btn btn-primary ml-1 btn-sm" data-dismiss="modal" onclick="checkBid(2);">
					<i class="bx bx-check d-block d-sm-none"></i>
					<span class="d-sm-block d-none">Accept</span>
				</button>
			</div>
		</form>
		</div>
	</div>
</div>

<script>
    $(document).ready(function () {
		changeBookingCount();

    });


    var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
    var end = '<?= date('d/m/Y'); ?>';

    $('#bkgCreateDate').daterangepicker(
            {
                locale: {
                    format: 'DD/MM/YYYY',
                    cancelLabel: 'Clear'
                },
                "showDropdowns": true,
                "alwaysShowCalendars": true,
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                }
            }, function (start1, end1) {
        $('#Booking_bkg_create_date1').val(start1.format('YYYY-MM-DD'));
        $('#Booking_bkg_create_date2').val(end1.format('YYYY-MM-DD'));
        $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#bkgCreateDate span').html('Select Booking Date Range');
        $('#Booking_bkg_create_date1').val('');
        $('#Booking_bkg_create_date2').val('');
    });


    $('#bkgPickupDate').daterangepicker(
            {
                locale: {
                    format: 'DD/MM/YYYY',
                    cancelLabel: 'Clear'
                },
                "showDropdowns": true,
                "alwaysShowCalendars": true,
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Tomorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
                    'Next 7 Days': [moment(), moment().add(6, 'days')],
                    'Next 15 Days': [moment(), moment().add(15, 'days')],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
                }
            }, function (start1, end1) {
        $('#Booking_bkg_pickup_date1').val(start1.format('YYYY-MM-DD'));
        $('#Booking_bkg_pickup_date2').val(end1.format('YYYY-MM-DD'));
        $('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#bkgPickupDate span').html('Select Pickup Date Range');
        $('#Booking_bkg_pickup_date1').val('');
        $('#Booking_bkg_pickup_date2').val('');
    });

    function changeBookingCount()
    {
        let totalItemCnt = "<?=$dataProvider->totalItemCount?>"
		<?php if ($model->bkg_status == 2)
		{
			?>
					$('#cntNewBooking').html(totalItemCnt);
		<?php
		}
		else if ($model->bkg_status == 3)
		{
			?>
					$('#cntAssignedBooking').html(totalItemCnt);
		<?php
		}
		else if ($model->bkg_status == 5)
		{
			?>
					$('#cntAllocatedBooking').html(totalItemCnt);
		<?php
		}
		else if ($model->bkg_status == 6)
		{
			?>
					$('#cntCompletedBooking').html(totalItemCnt);
		<?php
		}
		else if ($model->bkg_status == 9)
		{
			?>
					$('#cntCancelledBooking').html(totalItemCnt);
		<?php } 
		else if($model->bkg_status == 'partnernew')
		{ ?>
					$('#cntNewPartnerBooking').html(totalItemCnt);
		<?php }else{ ?>
				$('#cntAllBooking').html(totalItemCnt);
		<?php }?>
    }
	
	function checkBid(type)
	{
		let formData = $("#bidform").serialize();
		if(type == 2)
		{
			formData = $("#bidacceptform").serialize();
		}
		$.ajax({
			"type": "POST",
			"url": "<?= Yii::app()->createUrl('supplier/booking/bidaccept')?>",
			'dataType': "json",
			"data": formData,
			"success": function (data1) { 
				if (data1.success) 
				{
					alert(data1.message);
//					if(data1.data.isDirectAccept)
//					{
//						location.href = '<?//=Yii::app()->createUrl('supplier/booking/list?status=assigned')?>';
//					}
//					else
//					{
						location.reload();
					//}
				} 
				else
				{
					alert(data1.errors[0]);
				}
				return;
			}
		});
		return;
	}
	
	function limit(element, max) {    
    var max_chars = max;
    if(element.value.length > max_chars) {
        element.value = element.value.substr(0, max_chars);
    } 
}
</script>

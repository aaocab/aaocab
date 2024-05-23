<?
$statusList		 = Booking::model()->getActiveBookingStatus();
$statusList[2]	 = 'Confirmed';
$statusJSON		 = VehicleTypes::model()->getJSON($statusList);
?>
<style type="text/css">
    .pagination{
        margin: 0
    }
    @media (min-width: 992px){
        .modal-lg {
            width: 95%!important;
        }
    }
    @media (min-width: 768px){
        .modal-lg {
            width: 100%;
        }
    }
    .bordered {
        border: 1px solid #ddd;
        min-height: 45px;
        line-height: 1.2em;
        margin-bottom: 10px;
        margin-left: 0;
        margin-right: 0;
        padding-bottom: 10px;

    }
</style>
<script>
    if ($.fn.yiiGridView != undefined && $.fn.yiiGridView.settings['booking-list'] != undefined) {
        $(document).off('click.yiiGridView', $.fn.yiiGridView.settings['booking-list'].updateSelector);
    }
</script>
<div id="agent-content" class="panel-advancedoptions" >
	<div class="panel ">    
		<div class="panel-body panel-border panel-gall-box">
			<div class="row">

				<div class="col-xs-12 col-sm-5 col-md-4  ">
					<div class="row bordered  ">
						<div class="col-xs-12 text-center h3  mb10">        
							<?= $agtData['agt_fname'] . ' ' . $agtData['agt_lname'] ?>
						</div>
						<div class="col-xs-12   pt10">
							<div class="row">
								<div class="col-xs-8 col-lg-7"><b>Total Credit Applied: </b></div>
								<div class="col-xs-4 col-lg-5"><i class="fa fa-inr"></i><?= $agtData['totCredit'] | 0; ?></div>
							</div>
						</div>                   
						<div class="col-xs-12   pt10">
							<div class="row">
								<div class="col-xs-8 col-lg-7 "><b>Total Commission: </b></div>
								<div class="col-xs-4 col-lg-5"><i class="fa fa-inr"></i><?= $agtData['totCommission'] | 0; ?></div>
							</div>
						</div>
						<?
						$payto			 = '';
						if ($agtData['agtPayable'] == 0)
						{
							$payto = '';
						}
						else if ($agtData['agtPayable'] < 0)
						{
							$payto = ' to Agent';
						}
						else
						{
							$payto = ' from Agent';
						}
						?>
						<div class="col-xs-12    pt10">
							<div class="row">
								<div class="col-xs-8 col-lg-7 "><b>Payable <?= $payto ?>: </b></div>
								<div class="col-xs-4 col-lg-5"><i class="fa fa-inr"></i><?= $agtData['agtPayable'] | 0; ?></div>
							</div></div>
						<div class="col-xs-12   pt10">
							<div class="row">
								<div class="col-xs-8 col-lg-7 "><b>Total Booking taken: </b></div>
								<div class="col-xs-4 col-lg-5"> <?= $agtData['totBookings'] | 0; ?></div>
							</div></div>
						<div class="col-xs-12     pt10">
							<div class="row">
								<div class="col-xs-8 col-lg-7"><b>Active Bookings: </b></div>
								<div class="col-xs-4 col-lg-5"></i><?= $agtData['totActiveBookings'] | 0; ?></div>
							</div></div>
					</div>

				</div>

				<div class="col-xs-12 col-sm-7 col-md-8 ">
					<div class="row bordered pt10">
						<?php
						$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
							'id'					 => 'booking-form', 'enableClientValidation' => true,
							'clientOptions'			 => array(
								'validateOnSubmit'	 => true,
								'errorCssClass'		 => 'has-error',
								'afterValidate'		 => 'js:function(form,data,hasError){
                        if(!hasError){
                                $.ajax({
                                "type":"POST",
                                "dataType":"html",
                                "url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
                                "data":form.serialize(),
                                "success":function(data1){                               
                                        $("#agent-content").parent().html(data1);
                                    
                                    },
                                });
                                
                                }
                        }'
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

						<div class="col-xs-8  col-sm-8 col-md-8  col-lg-6">
							<?= $form->textFieldGroup($model, 'search', array('label' => "Search by Booking or Traveller's  information", 'htmlOptions' => array('placeholder' => 'search by booking id or other information'))) ?>

							<? //= $form->textFieldGroup($model, 'bkg_booking_id', array('htmlOptions' => array())) ?>
						</div>
						<? /*
						  <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 hide">
						  <?= $form->textFieldGroup($model, 'traveller_name', array('htmlOptions' => array())) ?>
						  </div>
						  <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 hide">
						  <?= $form->textFieldGroup($model, 'bkg_contact_no1', array('htmlOptions' => array())) ?>
						  </div>
						  <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 hide">
						  <?= $form->textFieldGroup($model, 'bkg_user_email1', array('htmlOptions' => array())) ?>
						  </div>
						 */ ?>
						<div class="col-xs-4 col-sm-4 col-md-4 col-lg-5 ">
							<div class="form-group">
								<label class="control-label">Booking Status</label>
								<?php
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'bkg_status',
									'val'			 => $model->bkg_status,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($statusJSON1), 'allowClear' => true),
									'htmlOptions'	 => array('style' => 'width:100%', 'class' => 'form-control border-radius p0', 'placeholder' => 'Select Status')
								));
								?>
							</div>
						</div>
						<div class="col-xs-6 col-md-6 col-sm-6  col-lg-2 ">
							<?= $form->textFieldGroup($model, 'bkg_from_city', array('htmlOptions' => array())) ?>
						</div>
						<div class="col-xs-6 col-md-6 col-sm-6  col-lg-2">
							<?= $form->textFieldGroup($model, 'bkg_to_city', array('htmlOptions' => array())) ?>
						</div>
						<div class="col-xs-12 col-lg-8 ">
							<div class="row">
								<div class="col-xs-6">
									<div class="form-group">
										<label  class="control-label">Booking Date</label>
										<?
										$daterang	 = "Select Booking Date Range";
										$createdate1 = ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1;
										$createdate2 = ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2;
										if ($createdate1 != '' && $createdate2 != '')
										{
											$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
										}
										?>

										<div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
											<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
											<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
										</div>
										<?
										echo $form->hiddenField($model, 'bkg_create_date1');
										echo $form->hiddenField($model, 'bkg_create_date2');
										?>
									</div></div>



								<div class="col-xs-6 col-sm-6  ">
									<div class="form-group">
										<label  class="control-label">Pickup Date</label>
										<?
										$daterange	 = "Select Pickup Date Range";
										$pickupdate1 = ($model->bkg_pickup_date1 == '') ? '' : $model->bkg_pickup_date1;
										$pickupdate2 = ($model->bkg_pickup_date2 == '') ? '' : $model->bkg_pickup_date2;
										if ($pickupdate1 != '' && $pickupdate2 != '')
										{
											$daterange = date('F d, Y', strtotime($pickupdate1)) . " - " . date('F d, Y', strtotime($pickupdate2));
										}
										?>

										<div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
											<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
											<span style="min-width: 240px"><?= $daterange ?></span> <b class="caret"></b>
										</div>
										<?
										echo $form->hiddenField($model, 'bkg_pickup_date1');
										echo $form->hiddenField($model, 'bkg_pickup_date2');
										?>
									</div>
								</div>
							</div> </div>

						<div class="col-xs-12 text-center ">  
							<button class="btn btn-primary mt5" type="submit" style="width: 185px;"  name="bookingSearch">Search</button>
						</div>

						<?php $this->endWidget(); ?>
					</div>
				</div>
			</div> 
		</div>
	</div>

	<div class="row">
		<div class="col-md-12 ">
			<?php
			if (!empty($dataProvider))
			{
				$this->widget('booster.widgets.TbGridView', array(
					'id'				 => 'booking-list',
					'responsiveTable'	 => true,
					'dataProvider'		 => $dataProvider,
					//'filter' => $model,
					'template'			 => "<div class='panel-heading'><div class='row m0'>
                        <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                        </div></div>
                        <div class='panel-body'>{items}</div>
                        <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
					'itemsCssClass'		 => 'table table-striped table-bordered mb0',
					'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
					'columns'			 => array(
						['name'	 => 'bkg_booking_id',
							'type'	 => 'raw',
							'value'	 => function($data) {
								if ($data['bkg_booking_id'] != '')
								{
									echo CHtml::link($data['bkg_booking_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "viewBooking", "onclick" => "return viewBooking(this)"]);
								}
							},
							'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Booking ID'],
						['name' => 'bkg_user_name', 'value' => '$data["bkg_user_name"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Name'],
						['name' => 'bkg_user_email', 'value' => '$data["bkg_user_email"]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Email'],
						['name' => 'bkg_contact_no', 'value' => '$data["bkg_contact_no"]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Phone'],
						['name' => 'bkg_from_city', 'value' => '$data["fromCities"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'From City'],
						['name' => 'bkg_to_city', 'value' => '$data["toCities"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'To City'],
						['name'	 => 'bkg_total_amount', 'value'	 => function($data) {
								echo '<i class="fa fa-inr"></i>' . round($data['bkg_total_amount']);
							}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Amount'],
						['name'	 => 'bkg_corporate_credit', 'value'	 => function($data) {
								if ($data['bkg_corporate_credit'] > 0)
								{
									echo '<nobr><i class="fa fa-inr"></i>' . round($data['bkg_corporate_credit']) . '</nobr';
								}
								else
								{
									echo '<i class="fa fa-inr"></i>' . '0';
								}
							}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Agent Credit'],
						['name'	 => 'bkg_agent_markup', 'value'	 => function($data) {
								$status = (in_array($data['bkg_status'], [1, 8, 9, 13])) ? ' <span class="text-danger">(NA)</span>' : '';

								if ($data['bkg_agent_markup'] > 0)
								{
									echo '<i class="fa fa-inr"></i>' . round($data['bkg_agent_markup']) . $status;
								}
								else
								{
									echo '<i class="fa fa-inr"></i>' . '0';
								}
							}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Remuneration'],
						['name'	 => 'bkg_create_date',
							'value'	 => function ($data) {
								echo DateTimeFormat::DateTimeToDatePicker($data['bkg_create_date'])
								. "<br>" . DateTimeFormat::DateTimeToTimePicker($data['bkg_create_date']);
								//echo DateTimeFormat::DateTimeToLocale($data['bkg_create_date']);
							}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Booking Date/Time'],
						['name'	 => 'bkg_pickup_date',
							'value'	 => function ($data) {
								echo DateTimeFormat::DateTimeToDatePicker($data['bkg_pickup_date'])
								. "<br>" . DateTimeFormat::DateTimeToTimePicker($data['bkg_pickup_date']);
								//echo DateTimeFormat::DateTimeToLocale($data['bkg_pickup_date']);
							},
							'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Pickup Date/Time'],
						['name'	 => 'bkg_status_name', 'value'	 => function($data) {
								if ($data['bkg_status'] == 2)
								{
									echo 'Confirmed';
								}
								else
								{
									echo Booking::model()->getActiveBookingStatus($data['bkg_status']);
								}
							}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array(), 'header'			 => 'Status'],
				)));
			}
			?>
		</div>
	</div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
    });
    function viewBooking(obj) {
        var href2 = $(obj).attr("href");
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {

                var box = bootbox.dialog({
                    message: data,
                    title: 'Booking Details',
                    size: 'large',
                    onEscape: function () {
                        // user pressed escape
                    },
                });
            }
        });
        return false;
    }
    function openDialog(obj)
    {
        try
        {
            $href = $(obj).attr("href");
            jQuery.ajax({type: "GET", "dataType": "html", url: $href, success: function (data)
                {
                    bootbox.dialog({
                        message: data,
                        title: $(obj).attr("modaltitle"),
                    });
                }
            });
        } catch (e) {
            alert(e);
        }
        return false;
    }
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
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Tommorow': [moment().add(1, 'days'), moment().add(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Next 7 Days': [moment(), moment().add(6, 'days')],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
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
</script>
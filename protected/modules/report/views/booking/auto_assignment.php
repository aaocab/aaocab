<style>
    .panel-body{
        padding-top: 0 ;
        padding-bottom: 0;
    }
    .table>tbody>tr>th
    {
        vertical-align: middle
    }

    .table>tbody>tr>td, .table>tbody>tr>th{
        padding: 7px;
        line-height: 1.5em;
    }
</style>

<div class="row">
    <div class="col-xs-12">      
        <div class="panel panel-default">
            <div class="panel-body">
				<!----------------------------------------------------------------------------->
				<?php
				$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'booking-form', 'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error'
					),
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array(
						'class' => '',
					),
				));
				/* @var $form TbActiveForm */
				?>
				<div class="row"> 
					<div class="col-xs-6 col-sm-3 col-lg-3">
						<?php
						$daterang	 = "Select Date Range";
						$createdate1 = $model->bkg_create_date1; //$_REQUEST['Booking']['bkg_create_date1'];
						$createdate2 = $model->bkg_create_date2; //$_REQUEST['Booking']['bkg_create_date2'];
						if ($createdate1 != '' && $createdate2 != '')
						{
							$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
						}
						//						else
						//						{
						//							$createdate1 = date('Y-m-d', strtotime('-7 days'));
						//							$createdate2 = date('Y-m-d');
						//							$daterang	 = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
						//						}
						?>
						<label  class="control-label">From & To Create Date Selection</label>
						<div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
							<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
							<span><?= $daterang ?></span> <b class="caret"></b>
						</div>
						<? ?>
						<input name="Booking[bkg_create_date1]" id="Booking_bkg_create_date1" type="hidden" value="<?= $model->bkg_create_date1 ?>">
						<input name="Booking[bkg_create_date2]" id="Booking_bkg_create_date2" type="hidden" value="<?= $model->bkg_create_date2 ?>">
					</div>
					<div class="col-xs-12 col-sm-3 col-md-3" style="">
                        <div class="form-group">
                            <label class="control-label">From & To Pickup Date Selection</label>
							<?php
							$daterang			 = "Select Pickup Date Range";
							$bkg_pickup_date1	 = $model->bkg_pickup_date1; //$_REQUEST['Booking']['bkg_pickup_date1'];
							$bkg_pickup_date2	 = $model->bkg_pickup_date2; //$_REQUEST['Booking']['bkg_pickup_date2'];

							if ($bkg_pickup_date1 != '' && $bkg_pickup_date2 != '')
							{
								$daterang = date('F d, Y', strtotime($bkg_pickup_date1)) . " - " . date('F d, Y', strtotime($bkg_pickup_date2));
							}
//							else
//							{
//								$bkg_pickup_date2	 = date('Y-m-d', strtotime('+7 days'));
//								$bkg_pickup_date1	 = date('Y-m-d');
//								$daterang			 = date('F d, Y', strtotime($bkg_pickup_date1)) . " - " . date('F d, Y', strtotime($bkg_pickup_date2));
//							}
							?>
                            <div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>

							<input name="Booking[bkg_pickup_date1]" id="Booking_bkg_pickup_date1" type="hidden" value="<?= $model->bkg_pickup_date1 ?>">
							<input name="Booking[bkg_pickup_date2]" id="Booking_bkg_pickup_date2" type="hidden" value="<?= $model->bkg_pickup_date2 ?>">
                        </div>
					</div>

					<div class="col-xs-12 col-sm-1 col-md-3" id="assigndt" > 

						<div class="form-group">
                            <label class="control-label">From & To Assignment Date Selection</label>
							<?php
							$assigndaterang		 = "Select Assignment Date Range";
							$bkg_assign_fdate	 = $model->tripAssignmnetFromTime; //$_REQUEST['Booking']['tripAssignmnetFromTime'];
							$bkg_assign_tdate	 = $model->tripAssignmnetToTime; // $_REQUEST['Booking']['tripAssignmnetToTime'];
							$preData			 = $_REQUEST['Booking']['preData'];
							if ($bkg_assign_fdate != '' && $bkg_assign_tdate != '')
							{
								$assigndaterang = date('F d, Y', strtotime($bkg_assign_fdate)) . " - " . date('F d, Y', strtotime($bkg_assign_tdate));
							}
							else if ($_REQUEST['Booking']['preData'] == 0 || $_REQUEST['Booking']['preData'] == null)
							{

								$bkg_assign_tdate	 = date('Y-m-d');
								$bkg_assign_fdate	 = date('Y-m-d');
								$assigndaterang		 = date('F d, Y', strtotime($bkg_assign_fdate)) . " - " . date('F d, Y', strtotime($bkg_assign_tdate));
							}
							?>
                            <div id="bkgAssignDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $assigndaterang ?></span> <b class="caret"></b>
                            </div>
							<input name="Booking[preData]" id="Booking_preData" type="hidden" value="1">
							<input name="Booking[tripAssignmnetFromTime]" id="Booking_tripAssignmnetFromTime" type="hidden" value="<?= $model->tripAssignmnetFromTime ?>">
							<input name="Booking[tripAssignmnetToTime]" id="Booking_tripAssignmnetToTime" type="hidden" value="<?= $model->tripAssignmnetToTime ?>">
                        </div>
					</div>

					<div class="col-xs-12 col-sm-3 ">
						<div class="form-group">
							<label class="control-label">Service Tier</label>
							<div>
								<?php
								$returnType			 = "filter";
								$serviceClassList	 = ServiceClass::model()->getList($returnType);
								if (count($serviceClassList) > 0)
								{
									foreach ($serviceClassList as $k => $s)
									{
										$checkService = false;
										if ($model->bkg_service_class[$k] == 1)
										{
											$checkService = true;
										}
										echo $form->checkBox($model, 'bkg_service_class[' . $k . ']', ['label' => '  ', 'checked' => $checkService]) . '  ' . $s . ' ';
									}
								}
								?>
							</div>
						</div>
					</div>

                </div>
				<div class="row"> 

					<div class="col-xs-12 col-sm-2 col-md-2 pt20 "> 
						<input type="checkbox" name="is_reconfirm_flag" id="is_reconfirm_flag" <?php
						if ($_REQUEST['is_reconfirm_flag'] || $_REQUEST == NULL)
						{
							echo "checked";
						}
						?>>
						Reconfirmed
					</div>

					<div class="col-xs-12 col-sm-2 col-md-2  pt20 "> 
						<input type="checkbox" name="is_advance_amount" id="is_advance_amount" <?php
						if ($_REQUEST['is_advance_amount'] || $_REQUEST == NULL)
						{
							echo "checked";
						}
						?>>
						Advance Amount Received
					</div>
					<div class="col-xs-12 col-sm-1 col-md-2 pt20 "> 
						<input type="checkbox" name="is_dbo_applicable" id="is_dbo_applicable" <?php
						if ($_REQUEST['is_dbo_applicable'])
						{
							echo "checked";
						}
						?>>
						Double Back Enabled
					</div>


					<div class="col-xs-12 col-sm-1 col-md-2 pt20  "> 
						<input   type="checkbox" name="is_New" id="is_New" <?php
						if ($_REQUEST['is_New'])
						{
							echo "checked";
						}
						?>>
						New Bookings
					</div>

					<div class="col-xs-12 col-sm-1 col-md-2 pt20 "> 
						<input class="chk" type="checkbox" name="is_Assigned" id="is_Assigned" <?php
						if ($_REQUEST['is_Assigned'] || $_REQUEST == NULL)
						{
							echo "checked";
						}
						?>>
						Auto Assigned Bookings
					</div>

					<div class="col-xs-12 col-sm-1 col-md-2 pt20 "> 

						<input class="chk" type="checkbox" name="is_Manual" id="is_Manual" <?php
						if ($_REQUEST['is_Manual'])
						{
							echo "checked";
						}
						?>>
						Manual Assigned Bookings
					</div>


				</div>

				<div class="row">
					<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
				</div>
				<?php $this->endWidget(); ?>
				<!------------------------------------------------------------------------------------------------------------->
				<?php
				if (!empty($dataProvider))
				{
					$params									 = array_filter($_REQUEST);
					$dataProvider->getPagination()->params	 = $params;
					$dataProvider->getSort()->params		 = $params;
					$this->widget('booster.widgets.TbGridView', array(
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataProvider,
						'template'			 => "
                            <div class='panel-heading'><div class='row m0'>
                                <div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
                                <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                            </div></div>
                            <div class='panel-body'>{items}</div>
                            <div class='panel-footer'><div class='row m0'>
                                <div class='col-xs-12 col-sm-6 p5'>{summary}</div>
                                <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                            </div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
						'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
						//       'ajaxType' => 'POST',
						'columns'			 => array(
							//array('name' => 'Trip ID', 'value' => '$data["bcb_id"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Trip ID'),
							array('name'	 => 'Trip ID', 'value'	 => function($data) {
									echo CHtml::link($data["bcb_id"], Yii::app()->createUrl("admin/booking/triprelatedbooking", ["tid" => $data['bcb_id']]), ["class" => "", "onclick" => "return viewTripDetail(this)", 'target' => '_blank']);
								}, 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Trip ID'),
							array('name'	 => 'Booking ID', 'value'	 => function($data) {
									echo CHtml::link($data["bbkID"], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bcb_bkg_id1']]), ["class" => "", "onclick" => "return viewDetail(this)", 'target' => '_blank']);
								}, 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Booking ID'),
							//array('name' => 'Booking ID', 'value' => '$data["bbkID"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Booking ID'),
							array('name' => 'Company', 'value' => '$data["company"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Company'),
							array('name' => 'bkg_pickup_date', 'value' => '$data["pickup"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Pick Up Date'),
							array('name' => 'bkg_create_date', 'value' => '$data["createdt"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Create Date'),
							array('name' => 'reconfirm', 'value' => '$data["reconfirm"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'ReConfirm Flag'),
							array('name' => 'bid', 'value' => '$data["bid"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'In Round1 '),
							array('name' => 'dbapply', 'value' => '$data["dbapply"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Double Back Flag'),
							array('name' => 'btr_dbo_amount', 'value' => '$data["dboAmt"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Double Back Amount'),
							array('name' => 'bkg_critical_score', 'value' => '$data["cs"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Criticality Score'),
							array('name' => 'demsup_misfire', 'value' => '$data["demsup_misfire"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'DemSup Missfire'),
							array('name' => 'ma', 'value' => '$data["ma"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'In Round2 '),
							array('name' => 'ca', 'value' => '$data["ca"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'In Round3'),
							array('name' => 'bkg_advance_amount', 'value' => '$data["baa"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Advanced Amount'),
							array('name'	 => 'MaxBid', 'value'	 =>
								function($data) {
									echo $data['bidCount'] . "/" . round($data['avgBid'], 2) . "/" . $data['maxBid'] . "/" . $data['minBid'];
								},
								'sortable'								 => true, 'headerHtmlOptions'						 => array('class' => 'col-xs-1'), 'header'								 => 'Count/Avg/Max/Min (Bids)'),
							array('name' => 'bkg_vendor_amount', 'value' => '$data["bva"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Booking VA'),
							array('name' => 'bcb_vendor_amount', 'value' => '$data["bcb_vendor_amount"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Trip VA'),
							array('name'	 => 'gozoAmount', 'value'	 =>
								function($data) {
									echo $data["gozoAmount"] . "/(" . round(($data['gozoAmount'] / $data["bcb_vendor_amount"]) * 100, 2) . "%)";
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Gozo Amount/Gozo Percentage'),
							array('name' => 'bcb_max_allowable_vendor_amount', 'value' => '$data["bcb_max_allowable_vendor_amount"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Max Allowable VA'),
							array('name' => 'bkg_assigned_at', 'value' => '$data["bkg_assigned_at"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Assignment Datetime'),
					)));
				}
				?> 
            </div>  

        </div>  
    </div>
</div>
<script>
    $(document).ready(function () {


        $("#assigndt").hide();
        if ($("#is_Assigned").is(":checked")) {
            $("#assigndt").show();
        }
        $(".chk").click(function () {

            $("#assigndt").hide();
            if ($("#is_Assigned").is(":checked")) {
                $("#assigndt").show();
            }
            if ($("#is_Manual").is(":checked")) {
                $("#assigndt").show();
            }
        });



        var start = '<?= date('1/m/Y'); ?>';
        var end = '<?= date('d/m/Y', strtotime('+1 month')); ?>';

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
            $('#bkgCreateDate span').html('Select Date Range');
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

        $('#bkgAssignDate').daterangepicker(
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
            $('#Booking_tripAssignmnetFromTime').val(start1.format('YYYY-MM-DD'));
            $('#Booking_tripAssignmnetToTime').val(end1.format('YYYY-MM-DD'));
            $('#bkgAssignDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgAssignDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgAssignDate span').html('Select Assignment Date Range');
            $('#Booking_tripAssignmnetFromTime').val('');
            $('#Booking_tripAssignmnetToTime').val('');
        });



        function viewDetail(obj) {
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
                    if ($('body').hasClass("modal-open"))
                    {
                        box.on('hidden.bs.modal', function (e) {
                            $('body').addClass('modal-open');
                        });
                    }

                }
            });
            return false;
        }
        function viewTripDetail(obj) {
            var href2 = $(obj).attr("href");
            $.ajax({
                "url": href2,
                "type": "POST",
                "dataType": "html",
                "success": function (data) {
                    var box = bootbox.dialog({
                        message: data,
                        title: 'Trip Details',
                        size: 'large',
                        onEscape: function () {
                            // user pressed escape
                        },
                    });
                    if ($('body').hasClass("modal-open"))
                    {
                        box.on('hidden.bs.modal', function (e) {
                            $('body').addClass('modal-open');
                        });
                    }

                }
            });
            return false;
        }



    });

</script>

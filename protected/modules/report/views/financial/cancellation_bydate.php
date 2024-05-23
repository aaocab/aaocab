<?php
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
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

<div class="row m0">
    <div class="col-xs-12">
        <div class="text-right">
        </div>    
        <div class="panel panel-default">
            <div class="panel-body">
				<?php
				$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'booking-form', 'enableClientValidation' => true,
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


                <div id="row" class="row">


					<div class="col-xs-12 col-sm-3 "><label class="control-label">Region</label>
						<?php
						$regionList			 = VehicleTypes::model()->getJSON(Vendors::model()->getRegionList());
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'bkg_region',
							'val'			 => $model->bkg_region,
							'asDropDownList' => FALSE,
							'options'		 => array('data' => new CJavaScriptExpression($regionList), 'allowClear' => true),
							'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Select Region')
						));
						?></div>
					<div class="col-xs-12 col-sm-3 "><?
						//$daterang = date('F d, Y') . " - " . date('F d, Y');
						$daterang			 = "Select Date Range";
						$createdate1		 = ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1;
						$createdate2		 = ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2;
						if ($createdate1 != '' && $createdate2 != '')
						{
							$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
						}
						?>
						<label  class="control-label">From & To Date Selection</label>
						<div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
							<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
							<span><?= $daterang ?></span> <b class="caret"></b>
						</div>
						<?
						echo $form->hiddenField($model, 'bkg_create_date1');
						echo $form->hiddenField($model, 'bkg_create_date2');
						?></div>
					<div class="col-xs-12 col-sm-3 ">
						<div class="form-group">
							<label class="control-label">Service Tier</label>
							<?php
							$returnType			 = "filter";
							$serviceClassList	 = ServiceClass::model()->getList($returnType);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkg_service_class',
								'val'			 => $model->bkg_service_class,
								'data'			 => $serviceClassList,
								'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
									'placeholder'	 => 'Select Service Class')
							));
							?>
						</div>
					</div>



					<div class="col-xs-12 col-sm-3 col-md-3"> 
						<div class="form-group cityinput">
							<label class="control-label">Channel Partner</label>
							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $agents,
								'attribute'			 => 'agt_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Channel Partner",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width' => '100%'),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                              populatePartner(this, '{$agents->agt_id}');
                                            }",
							'load'			 => "js:function(query, callback){
                                            loadPartner(query, callback);
                                            }",
							'render'		 => "js:{
                                            option: function(item, escape){
                                            return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
                                            },
                                            option_create: function(data, escape){
                                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                            }
                                            }",
								),
							));
							?>
						</div> 
					</div>




				</div>

				<div class="row">
					<div class="col-xs-12 col-sm-2 form-group">
						<input class="form-control" type="checkbox" name="btocbooking" id="btocbooking" value="1" <?= ($btocbooking == 1) ? 'checked' : '' ?>>B2C Booking only		
					</div>

					<div class="col-xs-12 col-sm-1 form-group pr0"><?php
						$checkCustomer		 = true;
						if ($model->bkgCancelCustomer == 1)
						{
							$checkCustomer = true;
						}
						else if ($model->bkgCancelCustomer == 0)
						{
							$checkCustomer = false;
						}
						echo $form->checkBox($model, 'bkgCancelCustomer', ['label' => '  ', 'checked' => $checkCustomer]);
						?> Customer
					</div>
					<div class="col-xs-12 col-sm-1 form-group"><?php
						$checkAdmin = true;
						if ($model->bkgCancelAdmin == 1)
						{
							$checkAdmin = true;
						}
						else if ($model->bkgCancelAdmin == 0)
						{
							$checkAdmin = false;
						}

						echo $form->checkBox($model, 'bkgCancelAdmin', ['label' => '  ', 'checked' => $checkAdmin]);
						?> Admin
					</div>
					<div class="col-xs-12 col-sm-1 form-group"><?php
						$checkAgent = true;
						if ($model->bkgCancelAgent == 1)
						{
							$checkAgent = true;
						}
						else if ($model->bkgCancelAgent == 0)
						{
							$checkAgent = false;
						}
						echo $form->checkBox($model, 'bkgCancelAgent', ['label' => '  ', 'checked' => $checkAgent]);
						?> Agent
					</div>
					<div class="col-xs-12 col-sm-1 form-group"><?php
						$checkSystem = true;
						if ($model->bkgCancelSystem == 1)
						{
							$checkSystem = true;
						}
						else if ($model->bkgCancelSystem == 0)
						{
							$checkSystem = false;
						}
						echo $form->checkBox($model, 'bkgCancelSystem', ['label' => '  ', 'checked' => $checkSystem]);
						?> System
					</div>


					<div class="col-xs-12 col-sm-3 mt10 n"><?= $form->checkboxGroup($model, 'IsCustomerCancel', array('widgetOptions' => array('htmlOptions' => []), 'labelOptions' => ['class' => 'pl0'])) ?>
					</div>
					<div class="col-xs-12 col-sm-3 mt10 n">  <?= $form->checkboxGroup($model, 'IsGozoCancel', array('widgetOptions' => array('htmlOptions' => []), 'labelOptions' => ['class' => 'pl0'])) ?>
					</div>	
				</div>
				<div class="row" >
					<div class="col-xs-12 col-sm-2 mb20"><?php
						$checkIsDbo = true;
						if ($model->searchIsDBO == 1)
						{
							$checkIsDbo = true;
						}
						else if ($model->searchIsDBO == 0)
						{
							$checkIsDbo = false;
						}
						echo $form->checkBox($model, 'searchIsDBO', ['label' => '  ', 'checked' => $checkIsDbo]);
						?> Show only DBO bookings
					</div>



					<div class="col-xs-12"><?php
						$sameDayCancellation = true;
						if ($model->sameDayCancellation == 1)
						{
							$sameDayCancellation = true;
						}
						else if ($model->sameDayCancellation == 0)
						{
							$sameDayCancellation = false;
						}
						echo $form->radioButtonListGroup($model, 'sameDayCancellation', array('label' => 'Show same day cancellations?', 'widgetOptions' => array('htmlOptions' => [], 'data' => [0 => 'BOTH', 1 => 'ONLY', 2 => 'NO']), 'inline' => true))
						?>
					</div>


				</div>



				<div class="row"> 
					<div class="col-xs-6 col-sm-4 col-md-2 text-center ">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?>
					</div>
				</div>
				<?php $this->endWidget(); ?>
				<div class="row" style="margin-top: 10px">  <div class="col-xs-12 col-sm-7 col-md-5">       
                        <table class="table table-bordered" style="">
                            <thead>
                                <tr style="color: black;background: whitesmoke">
									<th><u>Done by</u></th>
									<th><u>Customer initiated</u></th>
									<th><u>Gozo initiated</u></th>
									<th><u>Count</u></th>
                                </tr>
                            </thead>
                            <tbody id="count_booking_row">                         
								<?php
								$total			 = 0;
								$customerTotal	 = 0;
								$gozoTotal		 = 0;
								foreach ($summary as $s)
								{
									?>
									<tr>
										<td><?= $s['cancellation_type'] ?></td>
										<td><?= $s['CustomerCancel'] != null ? $s['CustomerCancel'] : 0 ?></td>
										<td><?= $s['GozoCancel'] != null ? $s['GozoCancel'] : 0 ?></td>
										<td><?= $s['cnt'] ?></td>
									</tr>
									<?php
									$total			 = ($total + $s['cnt']);
									$customerTotal	 = ($customerTotal + $s['CustomerCancel']);
									$gozoTotal		 = ($gozoTotal + $s['GozoCancel']);
								}
								?>
                                <tr style="color: black;background: whitesmoke">
									<td style="align-content: center"><b>Total  </b></td>
									<td><?= $customerTotal != null ? $customerTotal : 0 ?></td>
									<td><?= $gozoTotal != null ? $gozoTotal : 0 ?></td>
									<td><?= $total ?></td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xs-12 col-sm-5 col-md-5">
						<?php
						$checkExportAccess = false;
						if ($roles['rpt_export_roles'] != null)
						{
							$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
						}
						if ($checkExportAccess)
						{
							?>

							<?= CHtml::beginForm(Yii::app()->createUrl('report/financial/cancellations'), "post", ['style' => "margin-bottom: 10px;"]); ?>
							<input type="hidden" id="export1" name="export1" value="true"/>
							<input type="hidden" id="export_bkg_create_date1" name="export_bkg_create_date1" value="<?= ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1 ?>"/>
							<input type="hidden" id="export_bkg_create_date2" name="export_bkg_create_date2" value="<?= ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2 ?>"/>
							<input type="hidden" id="export_bkg_region" name="export_bkg_region" value="<?= $model->bkg_region ?>"/>
							<input type="hidden" id="export_bkgCancelCustomer" name="export_bkgCancelCustomer" value="<?= $model->bkgCancelCustomer ?>"/>
							<input type="hidden" id="export_bkgCancelAdmin" name="export_bkgCancelAdmin" value="<?= $model->bkgCancelAdmin ?>"/>
							<input type="hidden" id="export_bkgCancelAgent" name="export_bkgCancelAgent" value="<?= $model->bkgCancelAgent ?>"/>
							<input type="hidden" id="export_bkgCancelSystem" name="export_bkgCancelSystem" value="<?= $model->bkgCancelSystem ?>"/>
							<input type="hidden" id="export_searchIsDBO" name="export_searchIsDBO" value="<?= $model->searchIsDBO ?>"/>
							<input type="hidden" id="export_IsGozoCancel" name="export_IsGozoCancel" value="<?= $model->IsGozoCancel ?>"/>
							<input type="hidden" id="export_IsCustomerCancel" name="export_IsCustomerCancel" value="<?= $model->IsCustomerCancel ?>"/>
							<input type="hidden" id="export_sameDayCancellation" name="export_sameDayCancellation" value="<?= $model->sameDayCancellation ?>"/>
							<input type="hidden" id="export_bkg_service_class" name="export_bkg_service_class" value="<?= implode(",", $model->bkg_service_class) ?>"/>
							<input type="hidden" id="export_agt_id" name="export_agt_id" value="<?= $agents->agt_id ?>"/>
							<input type="hidden" id="export_btocbooking" name="export_btocbooking" value="<?= $btocbooking ?>"/>
							<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>

							<?php
							echo CHtml::endForm();
						}
						?>
                    </div>
                </div>



				<?php
				if (!empty($dataProvider))
				{

					$serviceTypeList						 = ServiceClass::model()->getList('filter');
					$params									 = array_filter($_REQUEST);
					$dataProvider->getPagination()->params	 = $params;
					$dataProvider->getSort()->params		 = $params;
					$this->widget('booster.widgets.TbGridView', array(
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataProvider,
						'template'			 => "<div class='panel-heading'><div class='row m0'>
                                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                                    </div></div>
                                                    <div class='panel-body table-responsive'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
						'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
						'columns'			 => array(
							array('name'	 => 'stt_zone', 'value'	 => function ($data) {
									echo States::findUniqueZone($data['stt_zone']);
								},
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
								'htmlOptions'		 => array('class' => 'text-center'),
								'header'			 => 'Region'),
							array('name'	 => 'bkg_booking_id', 'value'	 => function ($data) use ($serviceTypeList) {
									echo CHtml::link($data["bkg_booking_id"], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "", "onclick" => "return viewDetail(this)"]);

									echo "<br>";

									if ($serviceTypeList[$data['scv_scc_id']] == 'Value')
									{
										echo '<img src="/images/icon/Value.png"  style="cursor:pointer" title="Value">';
									}
									if ($serviceTypeList[$data['scv_scc_id']] == 'Value+')
									{
										echo '<img src="/images/icon/Value+.png"  style="cursor:pointer" title="Value+">';
									}
									if ($serviceTypeList[$data['scv_scc_id']] == 'Select')
									{
										echo '<img src="/images/icon/Select.png" style="cursor:pointer" title="Select">';
									}
									if ($serviceTypeList[$data['scv_scc_id']] == 'Select Plus')
									{
										echo '<img src="/images/icon/select+.png" style="cursor:pointer" title="Select">';
									}
									echo "<br>";
								}, 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Booking ID'),
							array('name'				 => 'bkg_agent_ref_code', 'value'				 => '$data[bkg_agent_ref_code]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'),
								'header'			 => 'Partner Ref Code'),
							array('name'	 => 'bkg_booking_type', 'value'	 => function ($data) {
									echo Booking::model()->getBookingType($data[bkg_booking_type]);
								},
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Booking Type'),
							array('name'	 => 'bkg_agent_id', 'value'	 =>
								function ($data) {
									if ($data["bkg_agent_id"] != NULL)
									{
										echo ($data['agt_company'] != NULL || $data['agt_company'] != '') ? $data['agt_company'] : $data['agent_name'];
									}
									else
									{
										echo 'B2C';
									}
								},
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'),
								'header'			 => 'Partner Type'),
							array('name'				 => 'booking_route', 'value'				 => '$data[booking_route]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'),
								'header'			 => 'Booking Route'),
							array('name'				 => 'bkg_create_date', 'value'				 => 'date("d-m-Y H:i:s",strtotime($data[bkg_create_date]))',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Booking Date/Time'),
							array('name'				 => 'bkg_pickup_date', 'value'				 => 'date("d-m-Y H:i:s",strtotime($data[bkg_pickup_date]))',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-left'),
								'header'			 => 'Pickup Date/Time'),
							array('name'	 => 'workingHour', 'value'	 => function ($data) {
									$fromDate	 = $data['bkg_create_date'];
									$toDate		 = $data['bkg_pickup_date'];
									echo DBUtil::CalcWorkingHour($fromDate, $toDate);
								},
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-left'),
								'header'			 => 'Working Hour'),
							array('name'	 => 'arrive_time',
								'value'	 => function ($data) {
									if ($data['arrive_time'] != null)
									{
										return date("d-m-Y H:i:s", strtotime($data['arrive_time']));
									}
									else
									{
										return '';
									}
								},
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-left'),
								'header'			 => 'Arrival Date/Time'),
							array('name'				 => 'btr_cancel_date', 'value'				 => 'date("d-m-Y H:i:s",strtotime($data[btr_cancel_date]))',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Cancellation Date/Time'),
							array('name'				 => 'cnr_reason', 'value'				 => '$data[cnr_reason]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Cancel Reason'),
							array('name'				 => 'bkg_cancel_delete_reason', 'value'				 => '$data[bkg_cancel_delete_reason]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'),
								'header'			 => 'Cancel Description'),
							array('name'	 => 'bkg_cancel_charge', 'value'	 => function ($data) {
									echo number_format($data['bkg_cancel_charge'], 2);
								},
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
								'htmlOptions'		 => array('class' => 'text-right'),
								'header'			 => 'Cancellation Charge'),
							array('name'	 => 'bkg_total_amount', 'value'	 => function ($data) {
									echo number_format($data['bkg_total_amount'], 2);
								},
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
								'htmlOptions'		 => array('class' => 'text-right'),
								'header'			 => 'Amount'),
							array('name'				 => 'is_dbo',
								'value'				 => '$data[is_dbo]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'DBO Status'),
							array('name'	 => 'refund_amount',
								'value'	 => function ($data) {
									echo number_format($data['refund_amount'], 2);
								},
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'htmlOptions'		 => array('class' => 'text-right'),
								'header'			 => 'DBO refund amount'),
							array('name'	 => 'cancel_by', 'value'	 => function ($data) {
									echo $data['cancelBy'];
								},
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
								'htmlOptions'		 => array('class' => 'text-left'),
								'header'			 => 'Cancel By'),
					)));
				}
				?> 
            </div>  

        </div>  
    </div>
</div>
<script>
    $(document).ready(function () {


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
            $('#bkgCreateDate span').html('Select Date Range');
            $('#Booking_bkg_create_date1').val('');
            $('#Booking_bkg_create_date2').val('');

        });


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
    $('#Agents_agt_id').on('change', function () {
        var val = $(this).val();
        if (val != '')
        {
            $('#btocbooking').attr("disabled", true);
        } else
        {
            $('#btocbooking').removeAttr("disabled");
        }
    });

    $('#btocbooking').on('change', function () {
        if (this.checked)
        {
            $('#Agents_agt_id').attr("disabled", 'disabled');
        } else
        {
            $('#Agents_agt_id').attr("disabled", false);
        }
    });
</script>


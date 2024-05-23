<style type="text/css">
	.help-block.error{
		color: #ff0000;
	}
</style>

<?
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<div class="panel-advancedoptions" >
	<div class="bg bg-light p10 mb10">
		<div class="row">
			<?php
			$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'pricesurgesearch-form', 'enableClientValidation' => true,
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


			<div class="col-xs-12 col-sm-4 col-md-3  "  >
				<div class="form-group  ">
					<label class="control-label">Create Date</label>
					<?php
					$daterang			 = "Select Create Date Range";
					$bkg_create_date1	 = ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1;
					$bkg_create_date2	 = ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2;
					if ($bkg_create_date1 != '' && $bkg_create_date2 != '')
					{
						$daterang = date('F d, Y', strtotime($bkg_create_date1)) . " - " . date('F d, Y', strtotime($bkg_create_date2));
					}
					?>
					<div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
						<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
					</div>
					<?= $form->hiddenField($model, 'bkg_create_date1'); ?>
					<?= $form->hiddenField($model, 'bkg_create_date2'); ?>
				</div>
				<?= $form->error($model, 'bkg_create_date1'); ?>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3  "  >
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
				<?= $form->error($model, 'bkg_pickup_date1'); ?>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
				<div class="form-group cityinput"> 
					<?php // echo $form->drop($model,'cpm_vehicle_type'); ?>
					<label>Channel Partner</label>
					<?php
					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model,
						'attribute'			 => 'bkg_agent_id',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select Channel Partner",
						'fullWidth'			 => false,
						'htmlOptions'		 => array('width' => '100%'),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
                                  populatePartner(this, '{$model->bkg_agent_id}');
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
					<?= $form->error($model, 'bkg_agent_id'); ?>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-2  text-center mt20">   
				<?php echo CHtml::submitButton('Search', array('class' => 'btn btn-primary full-width')); ?>
			</div>

			<?php $this->endWidget(); ?>
		</div>
	</div>

    <div class="row">
		<div class="  col-xs-12 col-sm-4 col-md-6   mt10">   
			<?= CHtml::beginForm(Yii::app()->createUrl('admin/agent/exportbookings'), "post", ['style' => "margin-bottom: 10px; margin-top: 10px; margin-left: 20px;"]); ?>
			<input type="hidden" id="export1" name="export1" value="true"/>
			<input type="hidden" id="bkg_pickup_date1" name="bkg_pickup_date1" value="<?= $model->bkg_pickup_date1 ?>">
			<input type="hidden" id="bkg_pickup_date2" name="bkg_pickup_date2" value="<?= $model->bkg_pickup_date2 ?>">
			<input type="hidden" id="bkg_create_date1" name="bkg_create_date1" value="<?= $model->bkg_create_date1 ?>">
			<input type="hidden" id="bkg_create_date2" name="bkg_create_date2" value="<?= $model->bkg_create_date2; ?>">
			<input type="hidden" id="bkg_agent_id" name="bkg_agent_id" value="<?= $model->bkg_agent_id; ?>"> 
			<button class="btn btn-primary" type="submit" style="width: 185px;">Export Below Table</button>

			<?= CHtml::endForm() ?>
		</div>

		<div class="col-xs-12 col-sm-8 col-md-6 text-right">
			<?php
			$form = $this->beginWidget('CActiveForm', array(
				'id'					 => 'frmCSVImport',
				'action'				 => '/admpnl/agent/importbookings',
				'enableAjaxValidation'	 => true,
				'htmlOptions'			 => array('enctype' => 'multipart/form-data'),
			));
			?>
			<div class="bg bg-light p10 mb10">
				<div class="row">
					<div class="col-xs-12 col-sm-6 text-right mt10">
						<input type="hidden" id="partnerId" name="partnerId" value="<?= $model->bkg_agent_id; ?>"   required="required"  >

						<input type="file" name="file" id="file" accept=".csv" required="required" class="form-control">
					</div>
					<div class="col-xs-12 col-sm-5 mt10">
						<button class="btn btn-primary" type="submit" style="width: 185px;" name="import" >Import CSV File</button> 
					</div>
				</div>
			</div>
			<?php
			$this->endWidget();
			?>

		</div>

	</div>

    <div class="row">
        <div class="col-md-12">
			<?php
			if (!empty($dataProvider))
			{
				$this->widget('booster.widgets.TbGridView', array(
					'id'				 => 'partnerBookingList',
					'responsiveTable'	 => true,
					'dataProvider'		 => $dataProvider,
					//  'filter' => $model,
					'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
					'itemsCssClass'		 => 'table table-striped table-bordered mb0',
					'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
					'columns'			 => array(
//										array(
//											'class'			 => 'CCheckBoxColumn', 'header'		 => 'html',
//											'id'			 => "booking_list_id",
//											'selectableRows' => '{items}',
//											'selectableRows' => 2,
//											'value'			 => '$data["bkg_id"]',
//											'headerTemplate' => '<label>{item}<span></span></label>',
//											'htmlOptions'	 => array('style' => 'width: 20px'),
//										),
						array('name' => 'bkg_booking_id', 'filter' => false, 'value' => '$data[bkg_booking_id]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Booking Id'),
						array('name' => 'bkg_create_date', 'filter' => false, 'value' => 'date("d/m/Y h:iA",strtotime($data[bkg_create_date]))', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Create Date'),
						array('name' => 'bkg_pickup_date', 'filter' => false, 'value' => 'date("d/m/Y h:iA",strtotime($data[bkg_pickup_date]))', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Pickup Date'),
						array('name' => 'bkg_agent_id', 'filter' => false, 'value' => '$data[agt_company]." (". $data[agt_fname]." ".$data[agt_lname].")"', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Channel Partner'),
						array('name' => 'route', 'filter' => false, 'value' => '$data[from_city]." -> ".$data[to_city]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Route'),
						array('name' => 'bkg_base_amount', 'filter' => false, 'value' => '"₹".$data[bkg_base_amount]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Base Amount'),
						array('name' => 'bkg_driver_allowance_amount', 'filter' => false, 'value' => '"₹".$data[bkg_driver_allowance_amount]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Driver Allowance'),
						array('name' => 'bkg_discount_amount', 'filter' => false, 'value' => '"₹".$data[bkg_discount_amount]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center text-danger'), 'header' => 'Discount Amount'),
						array('name' => 'bkg_additional_charge', 'filter' => false, 'value' => '"₹".$data[bkg_additional_charge]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Additional Amount'),
						array('name' => 'bkg_extra_km_charge', 'filter' => false, 'value' => '"₹".$data[bkg_extra_km_charge]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Extra Charge'),
						array('name' => 'bkg_service_tax', 'filter' => false, 'value' => '"₹".$data[bkg_service_tax]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'GST'),
						array('name' => 'bkg_toll_tax', 'filter' => false, 'value' => '"₹".$data[bkg_toll_tax]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Toll Tax'),
						array('name' => 'bkg_state_tax', 'filter' => false, 'value' => '"₹".$data[bkg_state_tax]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'State Tax'),
						array('name' => 'bkg_parking_charge', 'filter' => false, 'value' => '"₹".$data[bkg_parking_charge]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Parking Charge'),
						array('name' => 'bkg_total_amount', 'filter' => false, 'value' => '"₹".$data[bkg_total_amount]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;font-weight : bold'), 'header' => 'Total Amount'),
				)));
			}
			?>
		</div>
    </div>
	<div class="col-xs-12">
		<button type="button" class="btn btn-success hide" onclick="exportSelected();">Export Selected</button>
		<a type="button"  href="" id="expUrl" urlval="/admpnl/agent/exportbookings"  class="btn btn-primary hide">Export records</a>

	</div>
</div>

<script type="text/javascript">

	checkCounter = 0;
	var checkedVal = [];
	function exportSelected() {
		checkedVal = [];
		checkCounter = 0;
		$('input[name="booking_list_id[]"]').each(function (i) {
			if (this.checked) {
				checkedVal.push(this.value);
				checkCounter++;
			}
		});
		if (checkedVal.length == 0) {
			bootbox.alert("Please select a booking for mark complete.");
			return false;
		}
		else {
			exportFile(checkedVal);
		}
	}



	function exportFile(checkedIds) {

		alert("Exporting " + checkCounter.toString() + " of " + checkedVal.length.toString() + "");
		var href = '<?= Yii::app()->createUrl("admin/agent/exportbookings"); ?>';
		$.ajax({
			'type': 'GET',
			'url': href,
			'dataType': 'csv',
			global: false,
			data: {"bkIds": checkedIds.toString()},
			success: function (data) {
				if (data.success) {
					alert(data.valll);
				}
			},
			error: function (xhr, status, error) {
				ajaxindicatorstop();
				checkCounter = 0;
				alert(xhr.error);
			}
		});

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
					'Past 2 Days': [moment().subtract(2, 'days'), moment().subtract(1, 'days')],
					'Past 3 Days': [moment().subtract(3, 'days'), moment().subtract(1, 'days')],

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
					'Next 2 Days': [moment().add(1, 'days'), moment().add(2, 'days')],
					'Next 3 Days': [moment().add(1, 'days'), moment().add(3, 'days')],
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

	$('input[name="booking_list_id[]"]').change(function () {
		checkedVal = [];
		$('input[name="booking_list_id[]"]').each(function (i) {
			if (this.checked) {
				checkedVal.push(this.value);
			}
		});
		var uval = $('#expUrl').attr('urlVal') + '?bkIds=' + checkedVal.toString();
		$('#expUrl').attr('href', uval);
	});

	$('#Booking_bkg_agent_id').change(function () {
		$('#partnerId').val($('#Booking_bkg_agent_id').val());

	});
</script>
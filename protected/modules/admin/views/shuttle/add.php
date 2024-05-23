<?php
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<style type="text/css">
	.selectize-input {
        min-width: 0px !important;
    }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    } 
    .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
</style>
<div class="panel panel-border">
	<div class="panel panel-heading">Shuttle Schedule Information</div>
	<div class="panel-body">
		<?php
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'shuttle',
			'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
				'afterValidate'		 => 'js:function(form,data,hasError){
						$res=countCheckbox();
                        if(!hasError && $res){					
						 $.ajax({
							 "type":"POST",
							 "dataType":"json",
							"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/shuttle/add')) . '",
							 "data":form.serialize(),
							 "success":function(data1){
							 if(data1.success){
								alert(data1.message);
								location.reload();
								$("#countres").show();
								 $("#msgval").removeClass("alert-danger");
								 $("#msgval").addClass("alert-success");
								 $("#msgval").html(data1.message);								 
							}else{
								  $("#countres").hide();
							var errors = data1.errors;
                            settings=form.data(\'settings\');
                             $.each (settings.attributes, function (i) {
                                $.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
                              });
                              $.fn.yiiactiveform.updateSummary(form, errors);                            
								 }
							 },
						 });
					 } }'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class' => 'form-horizontal'
			),
		));
		/* @var $form TbActiveForm */
		?>
		<?=
		$form->errorSummary($model);
		echo CHtml::errorSummary($model)
		?>
		<div  id="countres" style="display: none">
			<div class="col-xs-12 alert alert-success" id="msgval">					 
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-4 col-md-4" style="">
				<div class="form-group">
					<label class="control-label">Pickup Date Range</label>
					<?php
					$daterang			 = "Select Pickup Date Range";
					$pickup_start		 = $_REQUEST['Shuttle']['pickup_start'];
					$pickup_end			 = $_REQUEST['Shuttle']['pickup_end'];
					if ($pickup_start != '' && $pickup_end != '')
					{
						$daterang = date('F d, Y', strtotime($pickup_start)) . " - " . date('F d, Y', strtotime($pickup_end));
					}
					else
					{
						$pickup_start	 = date('Y-m-d');
						$pickup_end		 = date('Y-m-d');
						$daterang		 = date('F d, Y', strtotime($pickup_start)) . " - " . date('F d, Y', strtotime($pickup_end));
					}
					?>
					<div id="pickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
						<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
					</div>
					<?= $form->hiddenField($model, 'pickup_start'); ?>
					<?= $form->hiddenField($model, 'pickup_end'); ?>
					<span class="has-error" style="line-height: normal"><? echo $form->error($model, 'slt_pickup_datetime'); ?></span>
				</div></div>
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
				<?= $form->numberFieldGroup($model, 'slt_availability', array('widgetOptions' => array('htmlOptions' => array('min' => 1, 'max' => 20)))) ?></div>
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
				<?= $form->dropDownListGroup($model, 'slt_seat_availability', array('label' => "No of seats per Shuttle", 'widgetOptions' => array('data' => ['' => 'Select No of seats', 4 => '4', 6 => '6'], 'htmlOptions' => array('min' => 4, 'max' => 20)))) ?></div>

		</div>

		<div class="row">

			<div class="col-xs-12 col-md-4 col-sm-6">
				<?=
				$form->radioButtonListGroup($model, 'slt_time_slot', array(
					'label'			 => '', 'widgetOptions'	 => array(
						'data' => Shuttle::model()->timeslot_arr,
					),
					'inline'		 => true,
						)
				);
				?>
			</div>
			<div class="col-xs-12  col-sm-3 mt10 text-left" id="checkPanel">
				<input type="checkbox" class="selectall" />
				<span id="chkSelect">[Select all]</span>
			</div> 
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 text-center">
				<div class="row" id="slotSegment"></div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
				<div class="form-group">
					<label  class="control-label">From City</label>
					<?php
					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model,
						'attribute'			 => 'slt_from_city',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select Source City",
						'fullWidth'			 => false,
						'htmlOptions'		 => array('width'	 => '100%',
							'id'	 => 'Shuttle_slt_from_city'
						),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
                                            populateSourceCity(this, '{$model->slt_from_city}');
                                                }",
					'load'			 => "js:function(query, callback){
                                            loadSourceCity(query, callback);
                                            }",
					'render'		 => "js:{
                                            option: function(item, escape){
                                            return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                                            },
                                            option_create: function(data, escape){
                                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                            }
                                            }",
						)
					));
					?>
					<span class="has-error" style="line-height: normal"><? echo $form->error($model, 'slt_from_city'); ?></span>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-3">
				<?= $form->textFieldGroup($model, 'slt_pickup_location', array('label' => "Pickup Location", 'widgetOptions' => array('htmlOptions' => array()))) ?>
			</div>
			<div class="col-xs-12 col-md-5 col-lg-6">
				<div class="row">

					<div class="col-xs-12 col-sm-6  ">
						<?= $form->textFieldGroup($model, 'slt_pickup_lat', array('prepend' => '<i class="fa fa-map-marker"></i>', 'label' => "Pickup latitude", 'widgetOptions' => array('htmlOptions' => array()))) ?>
					</div>
					<div class="col-xs-12 col-sm-6  ">
						<?= $form->textFieldGroup($model, 'slt_pickup_long', array('prepend' => '<i class="fa fa-map-marker"></i>', 'label' => "Pickup longitude", 'widgetOptions' => array('htmlOptions' => array()))) ?>
					</div>
				</div>
			</div>	
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
				<div class="form-group">
					<label  class="control-label" for="Shuttle_slt_to_city">To City</label>
					<?php
					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model,
						'attribute'			 => 'slt_to_city',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select Destination City",
						'fullWidth'			 => false,
						'htmlOptions'		 => array('width'	 => '100%',
							'id'	 => 'Shuttle_slt_to_city'
						),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
                                            populateSourceCity(this, '{$model->slt_to_city}');
                                                }",
					'load'			 => "js:function(query, callback){
                                            loadSourceCity(query, callback);
                                            }",
					'render'		 => "js:{
                                            option: function(item, escape){
                                            return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                                            },
                                            option_create: function(data, escape){
                                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                            }
                                            }",
						),
					));
					?>
					<span class="has-error" style="line-height: normal"><? echo $form->error($model, 'slt_to_city'); ?></span>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-3">
				<?= $form->textFieldGroup($model, 'slt_drop_location', array('label' => "Drop Location", 'widgetOptions' => array('htmlOptions' => array()))) ?>
			</div>
			<div class="col-xs-12 col-md-5 col-lg-6">
				<div class="row">
					<div class="col-xs-12 col-sm-6  ">
						<?= $form->textFieldGroup($model, 'slt_drop_lat', array('prepend' => '<i class="fa fa-map-marker"></i>', 'label' => "Drop latitude", 'widgetOptions' => array('htmlOptions' => array()))) ?>
					</div>
					<div class="col-xs-12 col-sm-6  ">
						<?= $form->textFieldGroup($model, 'slt_drop_long', array('prepend' => '<i class="fa fa-map-marker"></i>', 'label' => "Drop longitude", 'widgetOptions' => array('htmlOptions' => array()))) ?>
					</div>
				</div>
			</div>	
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-8 col-md-3 col-lg-3">
				<?= $form->numberFieldGroup($model, 'slt_price_per_seat', array('prepend' => '<i class="fa fa-inr"></i>', 'label' => "Per seat selling Price(With GST)", 'widgetOptions' => array('htmlOptions' => array('min' => 0)))) ?>
			</div>
			<div class="col-xs-12 col-md-9 col-lg-9">
				<div class="row"> 
					<div class="col-xs-12 col-sm-6  col-md-3">
						<?= $form->numberFieldGroup($model, 'slt_base_fare', array('prepend' => '<i class="fa fa-inr"></i>', 'widgetOptions' => array('htmlOptions' => array('min' => 0, 'readonly' => 'readonly')))) ?>
					</div>
					<div class="col-xs-12 col-sm-6  col-md-2">
						<?= $form->numberFieldGroup($model, 'slt_gst', array('prepend' => '<i class="fa fa-inr"></i>', 'widgetOptions' => array('htmlOptions' => array('min' => 0, 'readonly' => 'readonly')))) ?>
					</div>
					<div class="col-xs-12 col-sm-6  col-md-3">
						<?= $form->numberFieldGroup($model, 'slt_driver_allowance', array('prepend' => '<i class="fa fa-inr"></i>', 'widgetOptions' => array('htmlOptions' => array('min' => 0)))) ?>
					</div>

					<div class="col-xs-12 col-sm-6  col-md-2">
						<?= $form->numberFieldGroup($model, 'slt_toll_tax', array('prepend' => '<i class="fa fa-inr"></i>', 'widgetOptions' => array('htmlOptions' => array('min' => 0, 'readonly' => 'readonly')))) ?>
					</div>

					<div class="col-xs-12 col-sm-6  col-md-2">
						<?= $form->numberFieldGroup($model, 'slt_state_tax', array('prepend' => '<i class="fa fa-inr"></i>', 'widgetOptions' => array('htmlOptions' => array('min' => 0, 'readonly' => 'readonly')))) ?>
					</div> 
				</div>
			</div>
		</div>
		<div class="row">

			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
				<div class="form-group">
					<label class="control-label">Vendor</label>
					<?
					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model,
						'attribute'			 => 'slt_vnd_id',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select Vendor",
						'fullWidth'			 => false,
						'options'			 => array('allowClear' => true),
						'htmlOptions'		 => array('width' => '100%',
						//  'id' => 'from_city_id1'
						),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
                                  populateVendor(this, '{$model->slt_vnd_id}');
                                                }",
					'load'			 => "js:function(query, callback){
                        loadVendor(query, callback);
                        }",
					'render'		 => "js:{
                            option: function(item, escape){
                            return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
                            },
                            option_create: function(data, escape){
                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                            }
                        }", 'allowClear'	 => true
						),
					));
					?>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6  col-md-3">
				<?= $form->numberFieldGroup($model, 'slt_vendor_amount', array('prepend' => '<i class="fa fa-inr"></i>', 'widgetOptions' => array('htmlOptions' => array('min' => 0)))) ?>
			</div>
			<div class="col-xs-12 pt10 ">
				<?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary pl30 pr30')); ?>
			</div>
			<?php $this->endWidget(); ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		$("#checkPanel").hide();
		$(".selectall").click(function () {
			$(".individual").prop("checked", $(this).prop("checked"));
			if ($(this).prop("checked"))
			{
				$("#chkSelect").text("[Deselect all]");
			} else {
				$("#chkSelect").text("[Select all]");
			}
		});
	});
	var start = '<?= date('d/m/Y'); ?>';
	var end = '<?= date('d/m/Y', strtotime('+1 month')); ?>';
	$('#pickupDate').daterangepicker(
			{
				locale: {
					format: 'DD/MM/YYYY',
					cancelLabel: 'Clear'
				},
				"showDropdowns": true,
				"alwaysShowCalendars": true,
				startDate: start,
				minDate:start,
				endDate: end,
				ranges: {
					'Today': [moment(), moment()],
					'Tomorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
					'Next 7 Days': [moment(), moment().add(7, 'days')],
					'Next 15 Days': [moment(), moment().add(15, 'days')],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
				}
			}, function (start1, end1) {
		$('#Shuttle_pickup_start').val(start1.format('YYYY-MM-DD'));
		$('#Shuttle_pickup_end').val(end1.format('YYYY-MM-DD'));
		$('#pickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
	});
	$('#pickupDate').on('cancel.daterangepicker', function (ev, picker) {
		$('#pickupDate span').html('Select Pickup Date Range');
		$('#Shuttle_pickup_start').val('');
		$('#Shuttle_pickup_end').val('');
	});

	$('#Shuttle_bkg_from_city_id1').change(function ()
	{
		$('#Shuttle_bkg_from_city_id').val($('#Shuttle_bkg_from_city_id1').val()).change();
	});
	$('#Shuttle_slt_time_slot_0').click(function () {
		if ($('#Shuttle_slt_time_slot_0').is(':checked') == true)
		{
			getTimeSegmentation(60);
		}
	});
	$('#Shuttle_slt_time_slot_1').click(function () {
		if ($('#Shuttle_slt_time_slot_1').is(':checked') == true)
		{
			getTimeSegmentation(30);
		}
	});
	$('#Shuttle_slt_time_slot_2').click(function () {
		if ($('#Shuttle_slt_time_slot_2').is(':checked') == true)
		{
			getTimeSegmentation(15);
		}
	});
	function getTimeSegmentation(interval)
	{
		var slotSegmentHtml = "";
		$.ajax({
			"type": "GET",
			"dataType": "html",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/shuttle/getTimeSlot')) ?>",
			"data": {"slotType": interval},
			// "async": false,
			"success": function (data1)
			{
				data = JSON.parse(data1);
				var i = 0;
				$.each(data, function (key, value) {

					slotSegmentHtml = slotSegmentHtml + '<div class="col-xs-1"><label for="timeSlot_' + i + '"><span  class="btn btn-success "><input type="checkbox" class="individual" name="timeSlot[]"  id="timeSlot_' + i + '" value=' + key + ' > ' + value + '</span></label></div>';
					i++;
				});
				$("#slotSegment").hide();
				$('#slotSegment').html(slotSegmentHtml);
				$("#checkPanel").show('slow');
				$("#slotSegment").slideDown("slow");
			}
		});
	}
	function countCheckbox() {

		var $checkboxes = $('#shuttle input[type="checkbox"]');
		if ($checkboxes.filter(":checked").length == 0)
		{
			$("#countres").show();
			$("#msgval").removeClass("alert-success");
			$("#msgval").addClass("alert-danger");
			$("#msgval").html("No slot is chosen for Shuttle");
			return false;
		}
		return true;
	}

	$("#Shuttle_slt_from_city").change(function () {
		getRouterate();
	});
	$("#Shuttle_slt_to_city").change(function () {
		getRouterate();
	});
	$("#Shuttle_slt_seat_availability").change(function () {
		getRouterate();
	});


	$("#Shuttle_slt_base_fare").change(function () {
		calculateTotFare();
	});
	$("#Shuttle_slt_toll_tax").change(function () {
		calculateTotFare();
	});
	$("#Shuttle_slt_state_tax").change(function () {
		calculateTotFare();
	});
	$("#Shuttle_slt_driver_allowance").change(function () {
		calculateTotFare();
	});
	$("#Shuttle_slt_price_per_seat").change(function () {
		calculateBaseFare();
	});

	function getRouterate()
	{

		var fromCity = $("#Shuttle_slt_from_city").val();
		var toCity = $("#Shuttle_slt_to_city").val();
		var noOfSeat = $("#Shuttle_slt_seat_availability").val();
		if (fromCity > 0 && toCity > 0 && noOfSeat > 0)
		{

			$.ajax({
				url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/shuttle/getdetails')) ?>',
				type: 'POST',

				data: {"fromCity": fromCity, 'toCity': toCity, 'noOfSeat': noOfSeat, "YII_CSRF_TOKEN": $('input[name="YII_CSRF_TOKEN"]').val()},
				dataType: "json",
				success: function (data1) {
					if (data1.success)
					{
						$("#Shuttle_slt_toll_tax").val(data1.toll_tax);
						$("#Shuttle_slt_state_tax").val(data1.state_tax);
						$("#Shuttle_slt_vendor_amount").val(data1.full_vendor_amount);
						$("#Shuttle_slt_price_per_seat").val(data1.total_amount);
						$("#Shuttle_slt_gst").val(data1.gst);
						$("#Shuttle_slt_base_fare").val(data1.sell_base_amount);
						$("#Shuttle_slt_driver_allowance").val(data1.driver_allowance);
					} else {
						alert('The rate is not entered for the route')
					}
				},
			});
		}
	}

	function generateFare() {
		$.ajax({
			"type": "POST",
			"dataType": "json",
			"url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/shuttle/calculatefare')) ?>',
			"data": $('#shuttle').serialize(),
			"success": function (data1) {
				if (data1.success) {

				}
			},
		});
	}
	function calculateTotFare() {
		var ST = <?= Filter::getServiceTaxRate() ?>;
		var base_fare = parseInt($("#Shuttle_slt_base_fare").val()) | 0;
		var toll_tax = parseInt($("#Shuttle_slt_toll_tax").val()) | 0;
		var state_tax = parseInt($("#Shuttle_slt_state_tax").val()) | 0;
		var da = parseInt($("#Shuttle_slt_driver_allowance").val()) | 0;
		var sTax = Math.round((base_fare + toll_tax + state_tax + da) * (ST / 100));
		$("#Shuttle_slt_gst").val(sTax);
		var tot_fare = base_fare + sTax + toll_tax + state_tax + da;
		if (tot_fare < da) {
			alert('Driver allowance is greater than total seat charge.');
			$("#Shuttle_slt_driver_allowance").val('');
			da = 0;
			calculateBaseFare();
		}
		$("#Shuttle_slt_price_per_seat").val(base_fare + sTax + toll_tax + state_tax + da);
	}
	function calculateBaseFare() {
		var ST = parseInt(<?= Filter::getServiceTaxRate() ?>);
		var tot_fare = parseInt($("#Shuttle_slt_price_per_seat").val()) | 0;
		var toll_tax = parseInt($("#Shuttle_slt_toll_tax").val()) | 0;
		var state_tax = parseInt($("#Shuttle_slt_state_tax").val()) | 0;
		var da = parseInt($("#Shuttle_slt_driver_allowance").val()) | 0;
		if (tot_fare < da) {
			alert('Driver allowance is greater than total seat charge.');
			$("#Shuttle_slt_driver_allowance").val('');
			da = 0;
			calculateBaseFare();
		}
		//var efectiveBase = tot_fare - (toll_tax + state_tax + da);
		//var base_fare = Math.round(efectiveBase * (100 / (100 + ST)));
		//$("#Shuttle_slt_gst").val(efectiveBase - base_fare);
		//$("#Shuttle_slt_base_fare").val(base_fare);
		var base_fare = Math.round(((tot_fare)/(1+(ST/100))) - da - toll_tax - state_tax);
		var gst = Math.round((base_fare + toll_tax + state_tax + da)*ST/100);
		$("#Shuttle_slt_base_fare").val(base_fare);
		$("#Shuttle_slt_gst").val(gst);
	}


</script>  

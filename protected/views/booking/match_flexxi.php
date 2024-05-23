<style>
    .selectize-input {
        border-radius: 0 !important;
        /* min-width: 70px !important; */
        width: 100%;
    }
    .style_input .selectize-input{ min-width:120px!important;}
</style>
<?php
if ($arr1['error'] != 0)
{
	?>
	<div class="panel">            
		<div class="panel-body pt0 pb0">   
			<h3>Some error occurred. Please Try again later</h3>
		</div>
	</div>
	<?
}
?>

<?php
$ptime	 = date('h:i A', strtotime('6am'));
$timeArr = Filter::getTimeDropArr($ptime, false);

$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'bookingFlexxiTrip', 'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error'
	),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class' => 'form-horizontal',
	),
		));
/* @var $form TbActiveForm */
?>
<div class="col-xs-12 col-sm-12 col-md-11 float-none marginauto book-panel pb0">
	<div class="panel panel-default border-radius box-shadow1">
		<div class="panel-body p20">
			<h3 class="m0 text-uppercase mb10"><i class="fa fa-pencil-square-o"></i> LOOKING FOR:</h3>
			<div class="row">
				<div class="col-xs-12 col-sm-9">
					<div class="row pl15">
						<div class="col-xs-12 col-md-3">
							<?= $form->hiddenField($model, 'bkg_from_city_id'); ?>
							<?= $form->hiddenField($model, 'bkg_to_city_id'); ?>
							<?= $form->hiddenField($model, 'bkg_booking_type'); ?>
							<?= $form->hiddenField($model, 'bkg_id'); ?>
							<?php
							if (isset($_GET['id']) && $_GET['id'] != '' && !isset($_GET['alertid']))
							{
								?>
								<input type="hidden" name="promotorBkgId" id="promotorBkgId" value="<?= $_GET['id'] ?>">
								<input type="hidden" name="checkSearchValidation" id="checkSearchValidation" value ="0">
							<? } ?>
							<?php
							if ($model->bkg_no_person == '' || $model->bkg_no_person == 0)
							{
								$model->bkg_no_person = 1;
							}
							?>
							<?= $form->numberFieldGroup($model, 'bkg_no_person', array('label' => 'No. of Seats', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "No. of Seats ", 'id' => CHtml::activeId($model, "bkg_no_person"), 'min' => 1, 'max' => 3, 'required' => 'required', 'onchange' => 'baggage_info()']))) ?>
							<?= $form->error($model, 'bkg_no_person'); ?>
						</div>
						<div class="col-xs-12 col-md-3">
							<label>Gender :</label><br>
							<?php
							echo $form->dropDownList($model, 'bkgGender', Users::model()->genderList, ['id' => "bkgGender", 'class' => "form-control", 'required' => true]);
							?>
						</div>
						<div class="col-xs-6"><label></label><br><br><b>Allowed only <span id='bagunit'>1</span> Bag Unit per seat</b>  <i class="fa fa-info-circle text-info fa-lg' style='font-size:13px;"></i></div>
					</div>
					<div class="row">
						<div class="col-xs-12 style_input">
							<h5>Departing Between:</h5>
							<div class="row pl15">
								<div class="col-xs-6 col-md-3">
									<?php
									echo $form->datePickerGroup($model, 'locale_from_date', array('label'			 => '', 'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 
												'format'	 => 'dd/mm/yyyy', 'minDate'	 => '0', 'maxDate'	 => '0'), 'htmlOptions'	 => ['placeholder' => "Departure Date", 'required'])))
									?>    
								</div>
								<div class="col-xs-6 col-md-2">
									<div class="input-group timer-control">
										<?php
										echo $form->dropDownList($model, 'locale_from_time', $timeArr, ['class' => 'form-control', 'required']);
										?> 
									</div>
								</div>
								<div class="col-xs-12 col-md-1 mt10 mb10 text-center"><b>AND</b></div>
								<div class="col-xs-6 col-md-2">
									<?php
									echo $form->datePickerGroup($model, 'locale_to_date', array('label'			 => '', 'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 
												'format'	 => 'dd/mm/yyyy', 'minDate'	 => '0', 'maxDate'	 => '+2'), 'htmlOptions'	 => ['placeholder' => "Departure Date", 'value' => $pdate, 'required'])));
									?>  
								</div>
								<div class="col-xs-6 col-md-3">
									<div class="input-group timer-control">
										<?php
										echo $form->dropDownList($model, 'locale_to_time', $timeArr, ['class' => 'form-control', 'required']);
										?> 
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-3">
					<table align="center">
						<tr><th style='padding:8px; border: 1px solid black; font-size: 13px'>Type of Bag</th>
							<th style='padding:8px; border: 1px solid black; font-size: 13px'>Bag Units</th></tr>
						<tr><td style='padding:8px; border: 1px solid black; font-size: 13px'>1 Backpack</td>
							<td style='padding:8px; border: 1px solid black; font-size: 13px'>1 Bag Unit</td></tr>
						<tr><td style='padding:8px; border: 1px solid black; font-size: 13px'>1 Small Bag</td>
							<td style='padding:8px; border: 1px solid black; font-size: 13px'>2 Bag Units</td></tr>
						<tr><td style='padding:8px; border: 1px solid black; font-size: 13px'>1 Big Bags</td>
							<td style='padding:8px; border: 1px solid black; font-size: 13px'>4 Bag Units</td></tr>
					</table>
				</div>
			</div>
			<div class="col-xs-12 mt10 mb10 pl0 text-center">
				<?= CHtml::button('SEARCH', array('class' => 'btn next-btn', 'onclick' => 'dispsearch();')); ?>
			</div>
		</div>
	</div>             
</div>
<?php $this->endWidget(); ?>
<div class="panel">
	<div class="panel-body">
		<div class="col-xs-12" id="search_results">
		</div>
	</div>
</div>

<script>
	$(document).ready(function ()
	{
		//  $('#bkg_pickup_date_time_mf1').selectize();
		//   $('#bkg_pickup_date_time_mf2').selectize();
<?
if (isset($_GET['id']) && $_GET['id'] != '' && !isset($_GET['alertid']))
{
	?>
			dispsearch();
<? } ?>

<?
if ($model->bkg_flexxi_quick_booking == 1 || $model->bkg_flexxi_quick_booking == 2)
{
	?>
			dispsearch();
<? } ?>

<?
if (isset($_GET['id']) && $_GET['id'] != '' && isset($_GET['alertid']) && $_GET['alertid'] != '' && $model->bkg_flexxi_quick_booking == 0)
{
	?>
			$('#search_results').append(
					'<p style="font-size:16px;text-align:center;color:red;">Sorry you have already created a booking through this url. To create another booking <a href="https://<?= $_SERVER['HTTP_HOST'] ?>">Click here</a></p>'
					);
<? } ?>
	});
	function dispsearch()
	{
		if ($('#<?= CHtml::activeId($model, "bkg_no_person") ?>').val() == '' || $('#BookingTemp_bkg_pickup_date_date_mf1').val() == '' || $('#BookingTemp_bkg_pickup_date_date_mf2').val() == '')
		{
			box = bootbox.dialog({
				message: 'All fields are mandatory',
				title: 'Input Error',
				size: 'medium',
				onEscape: function ()
				{
				}
			});
			return false;
		}
		var data = $('#bookingFlexxiTrip').serialize();
		$('#checkSearchValidation').val(1);
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/flexxisearch')) ?>",
			"data": data,
			"success": function (data1)
			{
				$('#search_results').html(data1);
				return;
			},
			error: function (error)
			{
			}
		});
		return;
	}
	function baggage_info()
	{
		var seat = $('#<?= CHtml::activeId($model, "bkg_no_person") ?>').val();
		if (seat == 0 || seat == '')
		{
			$('#bagunit').text(0);
		}
		else if (seat == 1)
		{
			$('#bagunit').text(1);
		}
		else if (seat == 2)
		{
			$('#bagunit').text(2);
		}
		else
		{
			$('#bagunit').text(3);
		}
	}
</script>
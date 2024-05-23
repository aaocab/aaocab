<style type="text/css">
	.form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
	.car_result{ 
		background: #fff; padding: 15px;
		-webkit-box-shadow: 0px 0px 6px 0px rgba(0,0,0,0.14);
		-moz-box-shadow: 0px 0px 6px 0px rgba(0,0,0,0.14);
		box-shadow: 0px 0px 6px 0px rgba(0,0,0,0.14);
		-webkit-border-radius: 4px;
		-moz-border-radius: 4px;
		border-radius: 4px;
	}
	.green-color{ color: #00a388;}
</style>
<div class="container mt50">
    <div class="  spot-panel">
		<?php
		$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'bookseat', 'enableClientValidation' => FALSE,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'action'				 => Yii::app()->createUrl('agent/booking/spot'),
			'htmlOptions'			 => array(
				'class' => 'form-horizontal'
			),
		));
		/* @var $form TbActiveForm */
		echo $form->hiddenField($model, 'bkg_booking_type');
		echo $form->hiddenField($model, 'bkg_shuttle_id');

		echo $form->hiddenField($model, 'step', ['value' => '16']);
		// echo $form->errorSummary($model);
		echo $form->hiddenField($model, 'preData', ['value' => json_encode($model->preData)]);
		$staxrate						 = BookingInvoice::getGstTaxRate($model->bkg_agent_id, $model->bkg_booking_type);
		?>
		<?= $form->errorSummary($model); ?>		 

        <input type="hidden" name="step" value="16">

		<div class="row">
			<div class="col-xs-12 col-sm-4 mt30 h3 col-sm-offset-4  "  >
				Select Seats
			</div>	
			<div class="col-xs-12 col-sm-4 col-sm-offset-4 mb20"  >
				<div class="car_result  ">

					<div class="row pt10 car_bottom">
						<div class="col-xs-7">
							<div class="h4 m0 text-uppercase"><b>  Fare Per Seat</b></div><?= $taxStr ?>
						</div>
						<div class="col-xs-5 text-right">
							<div class="h4 m0 text-uppercase green-color">
								<i class="fa fa-inr" style="font-size: 16px; padding-right: 2px;"></i><b><?= $shuttle['slt_price_per_seat'] ?></b>
							</div>

						</div>
					</div>


					<div class="row pt5">
						<div class="col-xs-4">Base Fare:</div>
						<div class="col-xs-8 text-right"><i class="fa fa-inr"></i><?= $shuttle['slt_base_fare'] ?></div>
					</div>
					<?
					//$staxrate	 = Filter::getServiceTaxRate();
					$taxLabel	 = ($staxrate == 5) ? 'GST' : 'Service Tax ';
					?>


					<div class="row pt5">
						<div class="col-xs-6"><?= $taxLabel ?> (<?= $staxrate; ?>%):</div>
						<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $shuttle['slt_gst'] ?></div>
					</div>
					<div class="row pt5">
						<div class="col-xs-6">Toll-Tax</div>
						<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $shuttle['slt_toll_tax'] ?></div>
					</div>
					<div class="row pt5">
						<div class="col-xs-6">State-Tax:</div>
						<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $shuttle['slt_state_tax'] ?></div>
					</div>

					<div class="row pt5">
						<div class="col-xs-6">Driver Allowance:</div>
						<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $shuttle['slt_driver_allowance'] ?></div>
					</div>
					<div class="row pt5">
						<div class="col-xs-5">Capacity</div>
						<div class="col-xs-7 text-right"><?= $shuttle['slt_seat_availability']?> Passengers + Driver</div>
					</div>
					<div class="row pt5">
						<div class="col-xs-5">Luggage Allowed</div>
						<div class="col-xs-7 text-right">1 small backpack</div>
					</div>




				</div>
			</div>
		</div>
        <div class="row">
			<div class="col-xs-12 col-sm-4 col-sm-offset-4 ">
				<div class="form-group">
					<label class=" control-label"> Select # of seats </label>
					<div  >
						<select class="form-control  " name="Booking[bkg_no_of_seats]"  
								id="Booking_bkg_no_of_seats" >
							<option value="">Select Number of Seat</option>
							<?
							for ($i = 1; $i <= $shuttle['available_seat']; $i++)
							{
								echo "<option value='" . $i . "'>" . $i . "</option>";
							}
							?>
						</select>
						<span class="has-error"><? echo $form->error($model, 'bkg_pickup_address'); ?></span>
					</div>
				</div>
			</div>
		</div><div class="row">
			<div class="col-xs-12 text-right mt30">
				<button type="submit" class="pull-left  btn btn-danger btn-lg pl25 pr25 pt30  pb30" name="step16ToStep15"><b> <i class="fa fa-arrow-left"></i> Previous</b></button> <button type="submit" class="btn btn-primary btn-lg pl50 pr50 pt30  pb30"  name="step16submit"><b>Next <i class="fa fa-arrow-right"></i></b></button>
			</div> </div>
		<?php $this->endWidget(); ?>
    </div>
</div>

<script>
	history.pushState(null, null, location.href);
	window.onpopstate = function () {
		history.go(1);
	};




</script>

<div id="optServicesPanel" role="tabpanel" data-parent="#accordionWrapa1" aria-labelledby="optServices" class="collapse" style="">
	<a type="button" href="/operator/register" class="col-md-12 font-weight-bold p5"><i class="bx bx-arrow-back float-left "> </i> Go back </a>
	<div class="row">
		<a type="button" href="/operator/register" class="col-md-12">
			<div class="list-group-item pl10">
				<i class="bx bx-chevrons-left float-left text-success "></i> Operating Services</div> 
		</a>
	</div>
	<div class="card card-body ">
		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'operatingService',
			'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
				'afterValidate'		 => 'js:function(form,data,hasError){
										if(!hasError){ } }'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'action'				 => '/operator/updateServices',
			'htmlOptions'			 => array(
				'class' => 'form-horizontal',
			),
		));

		/* @var $form TbActiveForm */
		?> 
		<?php echo $form->hiddenField($cttModel, 'ctt_id', array()) ?> 
		<?php echo $form->hiddenField($vndPref, 'vnp_id', array()) ?> 
		<input type="hidden" name="formType" value="pan">
		<div class="row">
			<div class="col-sm-12">

				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6"> 
						<label>Check applicable operating services: </label>

						<?php
						$checkedOutstation	 = ($vndPref->vnp_oneway > 0) ? "checked" : 0;
						$checkedLocal		 = ($vndPref->vnp_daily_rental > 0) ? "checked" : 0;
						?>

						<div class="checkbox mt10 ">
							<div class="form-group">
								<input name="VendorPref[vnp_oneway]" id="VendorPref_vnp_oneway" value="1" type="checkbox" <?php echo $checkedOutstation ?>>
								<label  class="control-label btn p0" for="VendorPref_vnp_oneway"> Outstation (One way/ Multi city/ Round Trip) </label>
							</div>
						</div>

						<div class="checkbox  mt10">
							<div class="form-group">
								<input name="VendorPref[vnp_daily_rental]" id="VendorPref_vnp_daily_rental" value="1" type="checkbox" <?php echo $checkedLocal ?>>
								<label  class="control-label btn p0" for="VendorPref_vnp_daily_rental"> Local (Airport/ Daily Rental) </label>
							</div>
						</div>

					</div>
				</div>
				<div class="" style="text-align: center">
					<?php
					echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary'));
					?>
				</div>
			</div>
		</div>
		<?php
		$this->endWidget();
		?>
	</div>
</div>



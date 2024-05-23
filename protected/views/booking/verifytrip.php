<style type="text/css">
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>
<div class="row">
	<div class="col-xs-12  mt20 col-lg-8 col-lg-offset-2">
		<div class="panel panel-default"> 
			<div class="panel panel-body">
				<div class="row">
					<div class="col-xs-12 col-xs-offset-0 col-lg-10 col-lg-offset-1    text-center">
						<?
						if ($isAllowVerify == 1)
						{
							if ($isAlreadyVerified != 1)
							{
								if ($success == 1)
								{
									?>
									<label style="color: #008855">Trip verified successfully !!!</label>
									<?
								}
								else
								{
									?>
									<?php
									$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
										'id'					 => 'verify-trip-form', 'enableClientValidation' => true,
										'clientOptions'			 => array(
											'validateOnSubmit'	 => true,
											'errorCssClass'		 => 'has-error'
										),
										'enableAjaxValidation'	 => true,
										'errorMessageCssClass'	 => 'help-block',
										'htmlOptions'			 => array(
											'class' => 'form-inline',
										),
									));
									/* @var $form TbActiveForm */
									?> 
									<?
									if ($success == 2)
									{
										?>
										<label style="color: #FF0000">Please enter valid OTP</label>
									<? }
									?>
									<div class="col-xs-12 text-center"><label>BOOKING ID: <?= $BookingId ?></label></div>
									<div class="col-xs-12">
										<div class="row  mt20">
											<div class="col-xs-12  col-md-6">
												Please enter OTP here:
											</div>
											<div class="col-xs-12 col-md-6">
												<input type="number" name="otp"  class="form-control" placeholder="Enter OTP">
											</div>
										</div>
										<div class="row  mt20">
											<div class="col-xs-12  col-md-6">
												Start Odometer Reading :
											</div>
											<div class="col-xs-12 col-md-6">
												<input type="number" name="odoreading" min="1000" class="form-control" placeholder="Enter Odometer Reading">
											</div>
										</div>

									</div>
									<div class="col-xs-12  mt20 text-center">
										<button class="btn btn-info pr20 pl20" type="submit">Submit</button>
									</div>

									<?php $this->endWidget(); ?>
									<?
								}
							}
							else
							{
								?>
								<label style="color: #FF0000">Trip already verified !!!</label>  
								<?
							}
						}
						else
						{
							?>
							<label style="color: #FF0000">You are allowed to verify trip just 5 minutes before pickup time.</label>    
						<? }
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('form').on('focus', 'input[type=number]', function (e)
	{
		$(this).on('mousewheel.disableScroll', function (e)
		{
			e.preventDefault()
		})
		$(this).on("keydown", function (event)
		{
			if (event.keyCode === 38 || event.keyCode === 40)
			{
				event.preventDefault();
			}
		});
	});
	$('form').on('blur', 'input[type=number]', function (e)
	{
		$(this).off('mousewheel.disableScroll');
		$(this).off('keydown');
	});
</script>

	<?php
	$form = $this->beginWidget('CActiveForm', array(
		'id'					 => 'view-form', 'enableClientValidation' => true,
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
			'class' => 'form-horizontal',
		),
	));
	/* @var $form CActiveForm */
	?>
	<div class="col-12 col-lg-6 offset-lg-3">
		<div class="card">
			<div class="card-body">
				<div class="badge-circle badge-circle-lg badge-circle-light-success mx-auto my-1 mt0">
					<img src="/images/bxs-coupon.svg" alt="img" width="22" height="22">
				</div>
				<h5 class="card-title mb5">Enter Voucher Code</h5>
				<input type="text" name="vCode" id="vCode" required="true" class="form-control">
				<div class="help-block error" id="vCode_error" style="display:none"></div>
				<div class="mt10"><button type="submit" id="btnRedeem" class="btn btn-sm btn-primary text-uppercase hvr-push" name="btnRedeem">Redeem</button></div>
			</div>
		</div>
	</div>
	<?php $this->endWidget(); ?>
    <div class="col-12 text-center mt30">
		<?php if (Yii::app()->user->hasFlash('success')): ?>
			<div class="alert alert-success" style="font-size: 18px;" role="alert">
				<?php echo Yii::app()->user->getFlash('success'); ?>
			</div>
		<?php endif; ?>
		<?php if (Yii::app()->user->hasFlash('error')): ?>
			<div class="alert alert-danger" style="font-size: 18px;" role="alert">
				<?php echo Yii::app()->user->getFlash('error'); ?>
			</div>
		<?php endif; ?>
	</div>
<script type="text/javascript">
	$('#btnRedeem').click(function ()
    {
		var redeemval = $('#vCode').val();
		var countRedeemStr  = redeemval.length;
		if(countRedeemStr < '1')
		{
			$('#vCode_error').html('Please enter  valid code');
			$('#vCode_error').css('display', 'block');
			$('#vCode_error').css('color', 'red');
			return false;
		}	
	});
</script>

<style>
	.giftcard input{
		display: inline-block !important;
		padding: 6px 6px !important;
		width: 70%;
	} 

</style>
<div class="row">
	<?
	$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
	/* @var $form TbActiveForm */
	?>
	<div class="col-xs-12 giftcard">
		<span class="col-xs-3">Enter 16 digit Gift Card Code</span>
		<input type="text" name="gcc1" id="gcc1" required="true" maxlength="16" class="form-control">
		<div class="col-xs-9 col-xs-offset-3 help-block error" id="gccl_error" style="display:none"></div>
		<div class="col-xs-12 text-center mt10"><button type="submit" id="btnRedeem" class="btn btn-info" name="btnRedeem">Redeem</button></div>
	</div>
	<?php $this->endWidget(); ?>
    <div class="col-xs-12 text-center mt30">
		<?php if (Yii::app()->user->hasFlash('success')): ?>
			<div class="bg-success p10 text-success" style="font-size: 18px;">
				<?php echo Yii::app()->user->getFlash('success'); ?>
			</div>
		<?php endif; ?>
		<?php if (Yii::app()->user->hasFlash('error')): ?>
			<div class="bg-danger p10 text-danger" style="font-size: 18px;">
				<?php echo Yii::app()->user->getFlash('error'); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
<script type="text/javascript">
	$('#btnRedeem').click(function ()
    {
		var redeemval = $('#gcc1').val();
		var countRedeemStr  = redeemval.length;
		if(countRedeemStr < '16')
		{
			$('#gccl_error').html('Please enter 16 digit valid code');
			$('#gccl_error').css('display', 'block');
			$('#gccl_error').css('color', 'red');
			return false;
		}
	});
</script>

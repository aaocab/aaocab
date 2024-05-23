<style>
	.giftcard input{
		display: inline-block !important;
		padding: 6px 6px !important;
		width: 70%;
	} 

</style>
<?php
$this->layout	 = 'column1';
?>
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
	<div class="content-boxed-widget">
		<div class="accordion-content" style="display: block; min-height:100%;">
				<div class="content p0 bottom-5 text-center">
					<h3 class="color-black ultrabold top-10 bottom-5 text-center"> Redeem Voucher</h3>
					<div class="display-ini"><img src="/images/sack.svg" width="80" alt=""></div>
					<label class="uppercase color-highlight text-left mt30">Enter Voucher Code:</label>
					<div class="input-simple-1 has-icon input-green bottom-20"><input placeholder="Enter Code Here" class="form_control" type="text" name="vCode" id="vCode" required="true"></div>
					<div class="help-block error" id="vCode_error" style="display:none"></div>
                    <div class="content-padding p5 mb10 text-center"><button type="submit" id="btnRedeem" class="uppercase btn-green p15 mr5 font-18" name="btnRedeem">Redeem</button></div>  
					<div class="clear"></div>
				</div>
		</div>
		<div class="text-center mt30">
			<?php if (Yii::app()->user->hasFlash('success')): ?>
				<div class="content-boxed-widget text-center color-green3-dark font-16" style="font-size: 16px;">
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
	<?php $this->endWidget(); ?>
    
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

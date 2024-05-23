<style>
    .rating-cancel {
        display: none !important;
        visibility: hidden !important;
    }
    .rounded {
        border:1px solid #ddd;
        border-radius: 10px;
    }
    .padded {
        padding-bottom: 5px;
        padding-top: 5px;
    }
    .fset {
        padding: 5px;
        margin:5px;
        border:1px solid #ddd;
    }
    .lgend {
        border-bottom: 0;
        font-size: 1em;
        width: 78px;
        padding-left: 2px
    }
    .review {
        margin-top: 20px;
        color: #f00;
        font-size: 13px;
        display: none;
        text-align: center;
    }
</style>
<script src="https://apis.google.com/js/platform.js" async defer></script>
<?php
$hash		 = Yii::app()->shortHash->hash($model->bkg_id);
$styleSubmit = 'style="display:none"';

if (isset($type) && $type == 3)
{
	$styleFollowupYes	 = 'style="display:block"';
	$classFollowupYes	 = 'mt5 btn btn-primary';
	$styleSubmit		 = 'style="display:block"';
}
else
{
	$styleFollowupYes	 = 'style="display:none"';
	$classFollowupYes	 = 'mt5 btn btn-danger';
}
if (isset($type) && $type == 4)
{
	$styleFollowupNo = 'style="display:block"';
	$classFollowupNo = 'mt5 btn btn-success';
	$styleSubmit	 = 'style="display:block"';
}
else
{
	$styleFollowupNo = 'style="display:none"';
	$classFollowupNo = 'mt5 btn btn-success';
}
/* @var $model Booking */
?>
<section style="color:#555555">
    <div class="container">
		<?php
		if ($success == true)
		{
			if ($message != '')
			{
				echo "<p class=\"text-center\">" . $message . "</p>";
			}
			else
			{
				?>
				<div class="row">
					<h3 class="text-uppercase text-center m0 mb10 weight400 mt10 text-danger">We found a great price for your booking (<?= Filter::formatBookingId($model->bkg_booking_id); ?>)</h3>
					<div class="col-xs-10 float-none marginauto  p5">
						<div class="panel" style="">
							<?php
							$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
								'id'					 => 'finalFollowupForm',
								'enableClientValidation' => true,
								'clientOptions'			 => array(
									'validateOnSubmit'	 => true,
									'errorCssClass'		 => 'has-error',
								),
								// Please note: When you enable ajax validation, make sure the corresponding
								// controller action is handling ajax validation correctly.
								// See class documentation of CActiveForm for details on this,
								// you need to use the performAjaxValidation()-method described there.
								'enableAjaxValidation'	 => false,
								'errorMessageCssClass'	 => 'help-block',
								'htmlOptions'			 => array(
									'class' => 'form-horizontal'
								),
							));
							/* @var $form TbActiveForm */
							?>
							<div class="panel-body ">
								<div class="panel-scroll1">
									<div class="col-xs-12 mb20 text-center">
										<b>If you book before <?php echo LeadFollowup::showTravelDate($model->bkg_id); ?>, you can travel for ₹<?= ($model->bkgInvoice->bkg_base_amount); ?>.<br>
										Click one of the buttons below to let us know how you want to proceed.</b> 
									</div>
									<div class="row">
										<div class="col-xs-12 mb20 text-center" style="color:#666666">
											<a href="<?= $paymentUrl; ?>"><span id="btnFollowupYes" class="<?= $classFollowupNo; ?>" style="cursor:pointer;" >YES, I'm interested in booking now</span></a>
											<input type="submit" id="btnFollowupNo" class="<?= $classFollowupYes; ?>" style="cursor:pointer;" value="NO, I'm not interested"> 
										</div>
									</div>
								</div>
							</div>

							<?= CHtml::hiddenField('bkg_id', $model->bkg_id); ?>
							<?= CHtml::hiddenField('lfu_id', $leadModel->lfu_id); ?>
							<?php $this->endWidget(); ?>
						</div>
					</div>
				</div>
				<?php
			}
			
		}
		else
		{
			echo "<p class=\"text-center\"><b>" . $errors . "</b></p>";
		}
		?>
    </div>
</section>
<script>
    $(document).ready(function () {

        $("#btnFollowupNo").click(function ()
        {
            $("#btnFollowupNo").removeClass('mt5 btn btn-warning');
            $("#btnFollowupNo").addClass('mt5 btn btn-primary');
            $("#btnFollowupYes").removeClass('mt5 btn btn-primary');
            $("#btnFollowupYes").addClass('mt5 btn btn-warning');
        });

        $("#btnFollowupYes").click(function () {
            $("#btnFollowupNo").removeClass('mt5 btn btn-primary');
            $("#btnFollowupNo").addClass('mt5 btn btn-warning');
            $("#btnFollowupYes").removeClass('mt5 btn btn-warning');
            $("#btnFollowupYes").addClass('mt5 btn btn-primary');
        });


    });


</script>

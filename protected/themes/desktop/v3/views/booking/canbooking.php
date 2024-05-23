<style>

    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }

    .modal-header{
        display:block;
		.modal-title{ display: contents;}
    }
</style>
<?php
$rDetail = CancelReasons::model()->getListbyUserType(1);
$reasonList = ['' => '< Select a reason >'] + $rDetail[0];
$reasonPHList = $rDetail[1];
$jsReasonPHList = json_encode($reasonPHList);


$model	 = Booking::model()->findByPk($bkid);
$cancelFee			 = CancellationPolicy::initiateRequest($model);
$cancelCharge = $cancelFee->charges;
$advanceAmt			 = PaymentGateway::model()->getTotalAdvance($bkid);
$totalAdvance		 = ($advanceAmt != NULL) ? $advanceAmt : 0;
$refund	 = $totalAdvance - $cancelCharge;
$message = "Your total advance is  ₹" . round($totalAdvance) . " and If you cancel booking, your cancellation fees will be: ₹" . round($cancelCharge) . " and refund amount will be ₹" . round($refund);
?>
<div class="panel-advancedoptions">
    <div class="row">
        <div class="col-12 col-md-12 col-lg-12 p0"> 
		
            <?= CHtml::beginForm(Yii::app()->createUrl('booking/canbooking'), "post", ['accept-charset' => "UTF-8", 'id' => "cancelForm", 'class' => "form", 'data-replace' => ".error-message p"]); ?>
            <?= CHtml::hiddenField("bk_id", $bkid, ['id' => "bk_id"]) ?>

			<div class="form-group">
                <div class="col-12">
					<?php 
						if($totalAdvance > 0)
						{
					?>
						<span class="color-red"><?php echo $message; ?></span><hr>
						<?php }?>
                    <label for="delete"><b>Reason for cancellation : </b></label>
                    <?php //= CHtml::textArea('bkreason', '', ['id' => "bkreason", 'placeholder' => "Please write message", 'class' => "form-control", 'rows' => "3", 'cols' => "50"]) ?>
                    <?php //= CHtml::dropDownList('bkreason', '', ['' => '< Select a reason >'] + Booking::model()->getCancelReasonListForCustomer('cancel'), ['id' => "bkreason", 'class' => "form-control", 'required' => true]) ?>
                    <?= CHtml::dropDownList('bkreason', '', $reasonList, ['id' => "bkreason", 'class' => "form-control", 'required' => true]) ?>
                </div>
                <div class="col-12 mt10" id="reasontext" style="display: none">
                    <?= CHtml::textArea('bkreasontext', '', ['id' => "bkreasontext", 'class' => "form-control", 'placeholder' => 'Description'])
                    ?>
                </div>
            </div>
            <div class="Submit-button text-center mb20">
				<?php
				if ($model->bkg_agent_id == Config::get('Kayak.partner.id'))
				{
					?>
					<?= CHtml::hiddenField("bkpnlogin", $isBkpn, ['id' => "bkpnlogin"]) ?>
					<?php echo CHtml::Button("SUBMIT", ['class' => "btn btn-primary text-uppercase gradient-green-blue font-16 border-none mt5 cancelbtn"]); ?>
					<?php
				}
				else
				{
					?>
					<?= CHtml::hiddenField("bkpnaction", $isBkpnAction, ['id' => "bkpnaction"]) ?>
					<?php echo CHtml::submitButton("SUBMIT", ['class' => "btn btn-primary text-uppercase gradient-green-blue font-16 border-none mt5"]); ?>
				<?php } ?>
            </div>
            <?= CHtml::endForm() ?>




        </div>
    </div>
</div>
<script>
    $(function () {
        var rpList = [];
        rpList = <?= $jsReasonPHList ?>;
        $("#bkreason").change(function () {
            var reason = $("#bkreason").val();
            if (reason != '') {
                $("#bkreasontext").attr('placeholder', rpList[reason]);
                $("#reasontext").show();
                $("#bkreasontext").attr('required', 'required');
            }
//            if (reason != '') {
//                $href = '<? //= Yii::app()->createUrl('booking/getcanceldesctext')  ?>';
//                jQuery.ajax({"dataType": "json", data: {"rval": reason}, url: $href,
//                    success: function (data1) {
//                        $("#bkreasontext").attr('placeholder', data1.rtext);
//
//                        $("#reasontext").show();
//                        $("#bkreasontext").attr('required', 'required');
//                    }
//
//
//                });
//            }
        });
    });
	
	
	$('.cancelbtn').click(function(){
		//alert('here');//return false;
		var form = $("form#cancelForm");
		$href = "<?php echo Yii::app()->createUrl('booking/canbooking') ?>";
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": $href,
			"data": $(form).serialize(),
			"success": function (data2)
			{
                //debugger;
				$("html,body").animate({scrollTop: 180}, "slow");
				var data = "";
				var isJSON = false;
				try
				{
					data = JSON.parse(data2);
					isJSON = true;
				} catch (e)
				{

				}
				if (!isJSON)
				{
					$('#cancelBookingModal').removeClass('fade');
					$('#cancelBookingModal').css('display', 'block');
					$('#cancelBookingModelContent').html(data2);
					$('#cancelBookingModal').modal('show');
					//$("form#otpverify-form").parent().html(data2);
				} else
				{
					
				}
			},
			error: function (xhr, ajaxOptions, thrownError)
			{
			}
		});

		return false;
	});
</script>

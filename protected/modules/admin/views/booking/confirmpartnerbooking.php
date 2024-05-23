<style>
input {vertical-align:text-bottom;}
.panel{ box-shadow: 0 0 0 0!important; border-bottom: none;}
</style>
<div class="panel mb0 p0">
	<div class="panel-body" style="border: #dcdcdc 1px solid;">
<?php
/* @var $form TbActiveForm */
$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'paymentForm', 'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error',
		'afterValidate'		 => 'js:function(form,data,hasError){
                if(!hasError){
               
                    $.ajax({
                    "type":"POST",
                    "dataType":"json",
                    async: false,
                    "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/confirmpartnerbooking')) . '",
                    "data":form.serialize(),
                    "beforeSend": function () {
                        ajaxindicatorstart("");
                    },
                    "complete": function () {                     
                        ajaxindicatorstop();
                    },
                    "success":function(data1){
							if(data1.success)
							{
								alert("Booking confirmed successfully!");
								location.reload();
							}
							else
							{ 
								alert("Error occurred!");
							}
                        },
                     error: function(xhr, status, error){
                      
                         }
                    });

                    }
                }'
	),
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'onkeydown'	 => "return event.key != 'Enter';",
		'class'		 => '',
	),
		));
?>
		<div class="row">
			<div class="col-sm-12 text-center text-danger" id="errSummary">

			</div>
		</div>
		<div class="row mb10"  style="font-weight: bold">
			<div class="col-xs-6">
				Total Amount: 
				<input type="hidden" name="bkgid" value="<?=$model->bkg_id?>">
			</div>
			<div class="col-xs-6 text-right"  id="due_amount">
				<i class="fa fa-inr"></i><?=$model->bkgInvoice->bkg_due_amount;?>
			</div>
		</div>
		<div class="row" >
			<div class="col-xs-5">
				<p>Amount Paid By Partner:</p> 
			</div>
			<div class="col-xs-2 p0 pt5">
				<label class="checkbox-inline pt0" id="agt_paid_standard">
					<input type="radio" name="agentpaidrad" value="100" checked="checked" class="pay-focus"> 100%
				</label>
			</div>
			<div class="col-xs-2 p0 pt5">
				<label class="checkbox-inline pt0" id="agt_paid_custom">
					<input type="radio" name="agentpaidrad" value="0" class="pay-focus"> Other
				</label>
			</div>
			<div class="col-xs-3 pl0 text-right">
				<?
				if ($model->agentCreditAmount == '')
				{
					$model->agentCreditAmount = $model->bkgInvoice->bkg_due_amount;
				}
				?>
				<?= $form->numberFieldGroup($model, 'agentCreditAmount', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control pay-focus', 'placeholder' => "Partner Advance Paid", 'min' => 0]))) ?>
			</div> 
		</div>
		<div class="row text-danger" id="div_due_amount">
			<div class="col-xs-6">
				Amount Paid By Customer:
			</div>
			<div class="col-xs-6 text-right">
		       <i class="fa fa-inr"></i><span id="id_due_amount">0</span>
			</div>
		</div>
		<div class="row">
		<div class="col-sm-12 text-center">
				<button type='button' class='btn btn-primary btn-payment pl20 pr20 mt10'>Confirm</button>
		</div>
		</div>
<?php $this->endWidget(); ?>
	</div>
</div>
<script>
$totalAmt = '<?php echo $model->bkgInvoice->bkg_due_amount;?>';
$('input[name="agentpaidrad"]').click(function (event) {
		var percentVal = $(event.currentTarget).val();
		$('#errSummary').text("");
		if(percentVal == 100)
		{
			$('#Booking_agentCreditAmount').val($totalAmt).change();
			$('#id_due_amount').text(0);
		}
		else
		{
			$('#Booking_agentCreditAmount').val(0).change();
			$('#id_due_amount').text($totalAmt);
		}
	});
	$(".btn-payment").click(function () 
	{
		$('#errSummary').text("");
		var percentVal = $('input[name="agentpaidrad"]:checked').val();
		if(percentVal == 100 && $('#Booking_agentCreditAmount').val()!=$totalAmt)
		{
			$('#errSummary').text("Amount paid by Agent should be "+$totalAmt);
			return false;
		}
//		if(percentVal == 0 && $('#Booking_agentCreditAmount').val() == 0)
//		{
//			$('#errSummary').text("Amount paid by Agent should be greater than 0");
//			return false;
//		}
		$("#paymentForm").submit();
	});
$(document).on('input', '#Booking_agentCreditAmount', function(){
    var coins = $("#Booking_agentCreditAmount").val();
   $('#id_due_amount').text(($totalAmt - coins));
});
</script>


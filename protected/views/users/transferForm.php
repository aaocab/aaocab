

<style type="text/css">

    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>
<?php
/* @var $bank  \Stub\common\Bank */
?>
<div class="row1" >
    <div class="  pb10" style="float: none; margin: auto">
        <div style="text-align:center;" class="col-xs-12">


			<div class="h5"><label>Please confirm your bank details. The payment will be sent to this account. </label></div>
			<div class=" text-muted h5">Your Bank account #: <?php echo str_repeat('X', strlen($bank->accountNumber) - 4) . substr($bank->accountNumber, -4); ?></div>
			<div class=" text-muted h5">Account owner/Beneficiary name: <?php echo $bank->beneficiaryName; ?></div>
			<a id="showBankDetails" class="text-left btn btn-info mb5">Edit Bank Details</a>
			<div class="panel panel-default">

				<form class="form form-horizontal" name="payform" id="payform"  method="POST" action="/users/paytransfer">

					<div class="panel-body">
						<input type="hidden" name="YII_CSRF_TOKEN"  >  
						<div class="row mb10">
							<div class="col-xs-6 text-right "><label class="form-l " for="Pay_amount">Amount to send </label></div>
							<div class="col-xs-6  ">
								<input type="number" min="1" max="<?php echo $amount ?>" id="Pay_amount" name="Pay[AMOUNT]" value="<?php echo $amount ?>" required="required" placeholder="AMOUNT" class="form-control border-radius">

							</div>
						</div><div class="row mb10">
							<div class="col-xs-6  text-right"><label class="form-l " for="Pay_amount">Remarks</label></div>
							<div class="col-xs-6  ">
								<input type="text" size="35" id="Pay_remarks" name="Pay[REMARKS]"   placeholder="REMARKS(OPTIONAL)" class="form-control border-radius">

							</div>
						</div>
						<div class=" text-muted  ">Maximum transferable amount =  <i class="fa fa-inr"></i><?php echo $amount; ?></div>

					</div>


					<div class="panel-footer" style="text-align: center">
						<input class="btn  btn-primary" type="submit" name="yt0">	
					</div>




				</form> 
			</div>
		</div>

	</div>  
</div><?php
$script = "$(document).ready(function(){
	$('input[name=YII_CSRF_TOKEN]').val('" . $this->renderDynamicDelay('Filter::getToken') . "');
});";
Yii::app()->clientScript->registerScript('updateYiiCSRF', $script, CClientScript::POS_END);
?>

<script>

	$('#payform').on('submit', function () {
		$(this).find('input[type="submit"]').attr('disabled', 'disabled');
	});
	$('#modal_title').text('<?php echo $pagetitle ?>');

	$('#showBankDetails').click(function () {

		$.ajax({
			"type": "GET",

			"url": "<?php echo Yii::app()->createUrl('users/transfer') ?>",
			"data": {"showbankDetails": 1},
			"dataType": "html",
			"success": function (data) {

				$('#transform').removeClass('fade');
				$('#transform').css("display", "block");
				$('#transformbody').html(data);
				$('#transform').modal('show');
			}

		});
	});

</script>

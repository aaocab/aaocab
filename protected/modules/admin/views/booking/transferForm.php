
<div id="transformbody">
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
	<div class="panel-body p0" >
		<div class=" row pb10" style="float: none; margin: auto">
			<div   class="col-xs-12"  >
				<div class="h4">Choose the mode of refund :</div>
				<span id="refundSelectionMode ">
					<ul style="list-style: none">
						<li>
							<input value="1" id="refundSelectionMode_0"  type="radio" name="refundSelectionMode"> 
							<label for="refundSelectionMode_0">Original Payment Sources</label>
						</li>
						<li>
							<input value="2" id="refundSelectionMode_1" type="radio" name="refundSelectionMode"> 
							<label for="refundSelectionMode_1">Bank Account of Customer</label>
						</li>
					</ul>

				</span>

				<div id="pgBlock" class="refundMode" style="display: none;">

					<div class="row mb10">
						<div class="col-xs-6">Total Online Payment : </div>
						<div class="col-xs-6"><?php echo $onlineRefundable['balance'] ?></div>
						<div class="col-xs-6">Total Wallet Balance : </div>
						<div class="col-xs-6"><?php echo $walletBalance ?></div>
					</div>
					<form class="form form-horizontal" name="refundOnlineform" id="payform"  method="POST" 
						  action="/admpnl/booking/pgrefund">
						<input type="hidden" name="bkg_id" value="<?php echo $bkgId ?>">
						<div class="panel panel-default mb0">
							<div class="panel-body p0">
								<input type="hidden" name="YII_CSRF_TOKEN"  >  
								<div class="row mb10">
									<div class="col-xs-6  ">
										<label class="form-l " for="Pay_amount">Amount to refund </label></div>
									<div class="col-xs-6  ">
										<input type="number" min="1" max="<?php echo $amount ?>" id="Pay_amount" name="Pay[AMOUNT]" value="<?php echo $amount ?>" required="required" placeholder="AMOUNT" class="form-control border-radius">

									</div>
								</div> 
								<div class=" text-muted pb5 ">Maximum transferable amount =  <i class="fa fa-inr"></i><?php echo $amount; ?></div>

							</div>


							<div class="panel-footer" style="text-align: center">
								<input class="btn  btn-primary" type="submit" name="yt0">	
							</div>
						</div>
					</form> 

				</div>
				<div id ="bankBlock" class="text-center refundMode " style="display: none;">
					<?php
					echo $bkgId;
					if ($model->accountNumber == '' || $model->ifsc == '')
					{
						$view			 = 'bankdetails';
						$pagetitle		 = 'Please provide bank account details for customer';
						$this->pageTitle = $pagetitle;
						$outputJs		 = Yii::app()->request->isAjaxRequest;
						$method			 = "render" . ($outputJs ? "Partial" : "");
						$this->renderPartial($view, array('model' => $model, 'bank' => $bank, 'pagetitle' => $pagetitle, 'bkgId' => $bkgId), false, $outputJs);
					}
					else
					{
						?>

						<div class="h5"><label>Please confirm customer's bank details before proceed. The payment will be sent to this account. </label></div>
						<div class=" text-muted h5">Customer's Bank account #: <?php echo str_repeat('X', strlen($bank->accountNumber) - 4) . substr($bank->accountNumber, -4); ?></div>
						<div class=" text-muted h5">Account owner/Beneficiary name: <?php echo $bank->beneficiaryName; ?></div>

						<a id="showBankDetails" onclick = "showBankDetails()" class="text-left btn btn-info mb5">Edit Bank Details</a>
						<div class="panel panel-default mb0">

							<form class="form form-horizontal" name="payform" id="payform"  method="POST" action="/admpnl/booking/walletrefund">
								<input type="hidden" name="bkg_id" value="<?php echo $bkgId ?>">
								<div class="panel-body p0">
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
									<div class=" text-muted pb5 ">Maximum transferable amount =  <i class="fa fa-inr"></i><?php echo $amount; ?></div>

								</div>


								<div class="panel-footer" style="text-align: center">
									<input class="btn  btn-primary" type="submit" name="yt0">	
								</div>


							</form> 
						</div>
					<?php } ?>
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

//		$('#showBankDetails').click(function () {
//
//			$.ajax({
//				"type": "GET",
//
//				"url": "<? //php echo Yii::app()->createUrl('admin/booking/refundFromWallet')                              ?>",
//				"data": {"showbankDetails": 1, "bkg_id":<? //php echo $bkgId                              ?>},
//				"dataType": "html",
//				"success": function (data) {
//					$('#transformbody').parent().html(data).change();
//				}
//
//			});
//		});
		function showBankDetails() {



			$href = "<?php echo Yii::app()->createUrl('admin/booking/savecustbankdetails') ?>";
			jQuery.ajax({type: 'GET',
				url: $href,
				data: {"bkg_id": '<?php echo $bkgId ?>', "showbankDetails": 1},
				success: function (data) {
					bankbox = bootbox.dialog({
						message: data,
						title: 'Modify Bank Details',
						onEscape: function () {

						}
					});
					bankbox.on('hidden.bs.modal', function (e) {
						$('body').addClass('modal-open');
					});
				}
			});
		}
		$('input[name="refundSelectionMode"]').click(function () {

			var inputValue = $(this).attr("value");
			if (inputValue == '1') {
				$(".refundMode").not('#pgBlock').hide();
				$('#pgBlock').show();
			}
			if (inputValue == '2') {
				$(".refundMode").not('#bankBlock').hide();
				$('#bankBlock').show();
			}

//                    var targetBox = $("." + inputValue); 
//                    $(".selectt").not(targetBox).hide(); 
//                    $(targetBox).show(); 
//                    alert("Radio button " + inputValue + " is selected"); 
		});
	</script>
</div>
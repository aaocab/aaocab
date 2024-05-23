<style>

	.form-control.textarea{
		height:auto !important
	}
	input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
	.bg-gray{ background: #f6f6f6;}
</style>
<?
$accountBalance	 = ($getBalance['pts_ledger_balance'] - $getBalance['pts_wallet_balance']);
if ($accountBalance >= 0)
{
	$accountBalanceLabel = 'Receivable to Gozo';
}
else
{
	$accountBalanceLabel = 'Payable by Gozo';
}
?>
<div class="  col-xs-12   mb10 text-center bold">
	<?php echo $transinfo ?>
</div>
<div class="col-md-offset-2 col-xs-offset-0 col-xs-12 col-md-10 mb10 text-center bold">
	<div class="col-md-10">
		<h3><b>Account Balance :</b> 
			<i class="fa fa-inr" style="font-size: 18px;"></i><b><?= abs($accountBalance) ?> (<?php echo $accountBalanceLabel; ?>)</b>
		</h3>
	</div>
</div>

<div class='col-md-offset-4 col-xs-offset-0 col-xs-12 col-md-4'>
    <div class="panel  ">

        <div class="panel-body pb30">

			<?php
			$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'payment-form1',
				'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error',
					'afterValidate'		 => 'js:function(form,data,hasError){
								  
							if(!hasError){
									$.ajax({
							"type":"POST",
							"dataType":"json",
							"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl("agent/recharge/process", [])) . '",
							"data":form.serialize(),
							 "beforeSend": function(){
								ajaxindicatorstart("");
								},
								"complete": function(){
									ajaxindicatorstop();
								},
								"success":function(data1){
									if(data1.success){ 
										if(data1.url != "")
										{
											location.href=data1.url;
												return false;
											}
											alert("hhhh");
										}
										 
									},
								});
							}
						}'
				),
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => array(
					'class'		 => '', 'enctype'	 => 'multipart/form-data'
				),
			));
			?>

			<input  name="payubolt" id="payubolt" type="hidden">
			<div class="hide" id="billdetails">
				<div class="row "  >
					<div class="col-xs-12 heading-part mb10">
						<b>Billing Information</b>
					</div>
				</div>
				<div class="row  pt5" id="fullname ">
					<div class="col-xs-12  ">
						<div class="m0 form-group has-success">
							<label class="control-label" for="fullname">Full Name</label>
						</div>
						<div class="form-control disabled bg bg-gray">
							<?php echo $paymentData['name'] ?>		
						</div>
					</div>	

				</div>
				<div class="row  pt10" id="contact_info">
					<div class="col-xs-12  ">
						<div class="m0 form-group has-success">
							<label class="control-label" for="mobile">Mobile</label>
						</div>
						<div class="form-control disabled bg bg-gray">
							<?php echo $paymentData['mobile'] ?>		
						</div>
					</div>	
					<div class="col-xs-12  ">
						<div class="m0 form-group has-success">
							<label class="control-label" for="email">email</label>
						</div>
						<div class="form-control disabled bg bg-gray">
							<?php echo $paymentData['email'] ?>		
						</div>
					</div>	
				</div>
				<div class="row  pt10" id="address ">
					<div class="col-xs-12  ">
						<div class="m0 form-group has-success">
							<label class="control-label" for="address">Address</label>
						</div>
						<div class=" form-control disabled textarea bg bg-gray">
							<?php echo $paymentData['billing_address'] ?>		
						</div>
					</div>	

				</div>
			</div>
			<div class="row  pt10" id="amount">
				<div class="col-xs-12  ">
					<div class="m0 form-group has-success">
						<label class="control-label" for="amount"><b>Amount to recharge (minimum = ₹500)</b></label>

						<input class="form-control input-lg rounded-0 " placeholder="Recharge amount " min="500"   name="amount" id="amount" type="number"  >	
					</div>

				</div>	
				<div class='col-xs-12 font-11'>Note: If payment will be done through UPI then <b>2%</b> processing fee will not be charged</div>
			</div>

			<div class="row  pt10">
				<div class="col-xs-12 text-right  ">

					<input type="button" value="Proceed" class="btn proceed-new-btn text-uppercase  btn-primary  "   id="payubtn" >
				</div>

			</div>


			<?php $this->endWidget(); ?>

		</div>

	</div>
</div> 
<script>
	function IsPopupBlocker() {

		var oWin = window.open("", "testpopupblocker", "width=100,height=50,top=5000,left=5000");
		if (oWin == null || typeof (oWin) == "undefined") {
			return true;
		} else {
			oWin.close();
			return false;
		}
	}
	function validate() {
		var recharge_amount = $("[name='amount']").val();
		if (recharge_amount == '') {
			alert("Please enter amount");
			return false;
		}
		if (recharge_amount < 500) {
			alert("Recharge amount must be greater than equal to 500.");
			return false;
		}
		$('#agent-recharge').submit();
		return true;
	}
	$(document).ready(function ()
	{
		$defPayuBolt = '<?= Yii::app()->params['enablePayuBolt'] ?>';
		$enablePayuBolt = $defPayuBolt;

		if (IsPopupBlocker()) {
			$enablePayuBolt = 0;
		}

		$("#payubolt").val($enablePayuBolt);

	});



	$("#payubtn").on("click", function (event)
	{
		if (!validate()) {
			$("[name='amount']").focus();
			return false;
		}
		launchBOLT();
	});
	function launchBOLT()
	{
		$.ajax({
			"type": "POST",
			"dataType": "json",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('agent/recharge/process')); ?>",
			"data": $("#payment-form1").serialize(),
			"success": function (data2) {

				if (data2.success) {
					if ($enablePayuBolt == 0) {
						location.href = data2.url;
					} else {
						launchBOLT1(data2);
					}
				}
			}
		});
	}
	function launchBOLT1(data3)
	{
		$atxnid = data3.txnid;
		bolt.launch({
			key: data3.key,
			txnid: $atxnid,
			hash: data3.hash,
			amount: data3.amount,
			firstname: data3.firstname,
			email: data3.email,
			phone: data3.phone,
			productinfo: data3.productinfo,
			surl: data3.surl,
			furl: data3.furl,
			mode: 'dropout'
		}, {responseHandler: function (BOLT) {
				if (BOLT.response.txnStatus == 'CANCEL')
				{
					BOLT.response.txnid = $atxnid;
				}
				$.ajax({
					"type": "POST",
					"dataType": "json",
					"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('payment/response')); ?>",
					"data": {'response': BOLT.response, 'ptpid': 6, 'bolt': 1},
					"success": function (data4) {
						alert(data4.message);
					}
				});
			},
			catchException: function (BOLT) {
				alert(BOLT.message);
			}
		});
	}


</script>
<?
if (Yii::app()->params['enablePayuBolt'] == 1)
{
	?>
	<script id="bolt" src="<?= Yii::app()->payu->boltjsSrc ?>" bolt-color="1a4ea2" 
			bolt-logo="http://gozocabs.com/images/1024_1024_new.png">
	</script>
<? } ?>
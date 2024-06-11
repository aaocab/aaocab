
<?
$result = AccountTransDetails::accountTotalSummary(Yii::app()->user->getAgentId());

if ($result['totAmount'] >= 0)
{
	$balance = 0;
}
else
{
	$balance = abs($result['totAmount']);
}
$getBalance		 = PartnerStats::getBalance(Yii::app()->user->getAgentId());
$ledgerBalance	 = $getBalance['pts_ledger_balance'];
$walletBalance	 = $getBalance['pts_wallet_balance'];
$accountBalance	 = ($ledgerBalance + $walletBalance);
if ($accountBalance >= 0)
{
	$accountBalanceLabel = 'Payable To Gozo';
}
else
{
	$accountBalanceLabel = 'Receivable From Gozo';
}
?>

<div class="col-md-offset-2 col-xs-offset-0 col-xs-12 col-md-10 mb10 text-center bold"><div class="col-md-10"><h3><b>Account Balance Rs:</b> <i class="fa fa-inr" style="font-size: 18px;"></i><b><?= abs($accountBalance) ?> (<?php echo $accountBalanceLabel; ?>)</b></h3></div></div>
<?php
$enablePayu = false;
if ($enablePayu)
{
	?>
	<div class='col-md-offset-4 col-xs-offset-0 col-xs-12 col-md-4'>
	    <div class="panel text-center">

	        <div class="panel-body pb30">
	            <p class="mt0 mb5" style="font-size: 16px; text-transform: uppercase;"><b>Pay using direct link</b></p>
				<div class='pm-button mb5'><a href='http://www.payumoney.com/paybypayumoney/#/9ACE29032F473FA855B4C6DF15C19A0F'><img src='http://www.payumoney.com/media/images/payby_payumoney/new_buttons/13.png' /></a></div>
	            If payment will be done through UPI then <b>2%</b> processing fee will not be charged
	        </div>

	    </div>
	</div>

	<div class='col-md-offset-4 col-xs-offset-0 col-xs-12 col-md-4 text-center mb15'><span style="background: #fff; border-radius: 100px; width: 40px; height: 40px; display: inline-block; font-size: 16px; padding-top: 8px; -webkit-box-shadow: 0px 5px 5px 0px rgba(0,0,0,0.18); -moz-box-shadow: 0px 5px 5px 0px rgba(0,0,0,0.18); box-shadow: 0px 5px 5px 0px rgba(0,0,0,0.18);"><b>OR</b></span></div>
<?php } ?>
<div class='col-md-offset-4 col-xs-offset-0 col-xs-12 col-md-4'>
    <div class="panel">
        <div class="panel-body pb20">
            <div class="col-md-offset-2 col-xs-offset-0 col-xs-12 col-md-8 text-center bold"><?= $transinfo ?></div>
            <div class="col-xs-12 col-md-8 col-md-offset-2 col-xs-offset-0" >
                <div class="mb5" style="font-size: 16px;"><b>RECHARGE</b></div>
				<?
				$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'agent-recharge', 'enableClientValidation' => FALSE,
					'clientOptions'			 => array('validateOnSubmit' => true, 'errorCssClass' => 'has-error'),
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data'),
				));
				?>
                <input type='text' required='true' name='recharge_amount' class='form-control' min='500' placeholder="Enter Amount">
                <select class='form-control mt5' name='paymentOpt'>
                    <option value='1'>on my PayTM account</option>
                    <option value='2'>my credit card</option>
                </select>
                <div class='col-xs-12 mt10 mb5 pl0' style='font-size: 12px'><b>Note: 2% processing fee will be charged</b></div>
                <div class='col-xs-12 text-center'>
                    <button type='button' class='btn btn-primary mt10' name='rechargesubmit' onClick='return validate()'>RECHARGE</button>
                </div>
				<?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>
<script>
	function validate() {
		var recharge_amount = $("[name='recharge_amount']").val();
		if (recharge_amount < 500) {
			alert("Recharge amount must be greater than equal to 500.");
			return false;
		}
		$('#agent-recharge').submit();
		return true;
	}
</script>
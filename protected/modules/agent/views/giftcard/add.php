<style>
	.form-horizontal .form-group{ margin: 0;}
</style>
<?php
$agentId	 = Yii::app()->user->getAgentId();
?>
<div class="row m0">
    <div class="col-lg-offset-1 col-lg-6 col-md-6 col-sm-8 pt20" style="float: none; margin: auto">
        <div class="row">
            <div class="col-xs-12">
				<?php
				$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'gift-card-form', 'enableClientValidation' => TRUE,
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
						'class' => 'form-horizontal'
					),
				));
				/* @var $form TbActiveForm */
				?>
				<?= $message; ?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-xs-12">
							<?php //echo CHtml::errorSummary($model);   ?>                           
							<div class="row">
                                <div class="mt10 n" style="color:#da4455"></div>
                            </div>


							<div class="bs-example">

								<?php
//								$i			 = 0;
//								foreach ($getAmount as $amount)
//								{
								?>
								<!--									<div class="btn-group btn-group-toggle mr5 gftParentClass" data-toggle="buttons">
																		<label class="btn box-shadow pl10 pr20"  style="background-color: #F9EBEA">
																			 <img src="/images/logo2_new.png" height="20px;"><br>
																			<input class="gftamt" data-id="<? //= $amount['gcr_id']                ?>" data-promo="<? //= $amount['prm_id']               ?>"  data-cash="<? //= $amount['pcn_value_cash']                ?>" value="<? //= $amount['gcr_cost_price'];                ?>" type="radio" name="giftcard" autocomplete="off"><span class="text-danger"><? //=$amount['prm_code']               ?></span><br><span class="text-danger"> Original Price: <span>&#x20B9</span><? //= $amount['pcn_value_cash'];                ?> </span><br><span class="text-danger"> You Pay: <span>&#x20B9</span><? //= $amount['gcr_cost_price'];                ?></span> 
																		</label>
																	</div>-->
								<?php
//									$i++;
//								}
								?>
								<input type="hidden" value="" id="gftCardAmt" name="gftCardAmt">
								<input type="hidden" value="" id="gftPromoId" name="gftPromoId">
                                                                <input type="hidden" value="0" id="costPrice" name="costPrice">
                                                                <input type="hidden" value="0" id="promoAmt" name="promoAmt">
							</div>
							<div class="row mt20 mb20">
								<div class="col-xs-12 col-md-6">
									<?= $form->textFieldGroup($models, 'gcs_value_amount', array('label' => 'Enter Amount', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Amount')))) ?>
								</div>
								<div class="col-xs-12 col-md-6">
									<?= $form->numberFieldGroup($models, 'gcs_quantity', array('value' => '1', 'label' => 'Select Quantity', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Days']))) ?>
								</div>
							</div>
							<div class="row mt20 mb20">
								<div class="col-xs-12 col-md-6">
									<?= $form->textFieldGroup($models, 'gcs_name', array('label' => 'Enter Name Of Recipient', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Name')))) ?>
								</div>
								<div class="col-xs-12 col-md-6">
									<?= $form->textFieldGroup($models, 'gcs_email_address', array('label' => 'Enter Email Address', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Email Address')))) ?>
								</div>    
							</div>
							<div class="row mb20">
								<div class="col-xs-12 col-md-6">
									<?= $form->textFieldGroup($models, 'gcs_phone', array('label' => 'Enter Phone Of Recipient', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Phone')))) ?>
								</div>
								<div class="col-xs-12 col-md-6">
									<?= $form->textAreaGroup($models, 'gcs_message', array('label' => 'Enter Your Message', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Enter Your Message"]))) ?>
								</div>
							</div>
							<div class="row mb20">
								<div class="col-xs-12 bg-primary text-right">
									<label class="control-label" for=""><b>Total Payable</b>:</label>
									<i class="fa fa-inr"></i><label readonly="readonly" name = "gcs_total_payable" id="gcs_total_payable">0</label>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-md-6">
									<label class="control-label" for="GiftCardSubscriber_gcs_cost_price">Choose Payment Option</label>
									<select class='form-control mt5' name='paymentOpt'>
										<option value='1'>On My PayTM Account</option>
										<option value='2'>My Credit Card</option>
									</select>
								</div>	
							</div>
							<div class="row mt20 mb20">
								<div class="col-xs-6 col-md-6">
									<?= $form->textFieldGroup($models, 'gcs_promo_code', array('label' => 'Enter Promo Code', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Promo Code')))) ?>

								</div>
								<div class="col-xs-6 col-md-6" style="margin-top: 30px;">
									<label class="col-xs-12 col-md-12" style="color: #32CD32" id="gcs_value_type"></label>
								</div>
							</div>
							<div class="row mt20 mb20">
								<div class="col-xs-12 bg-primary text-left">
									<label class="control-label" for="GiftCardSubscriber_gcs_cost_price"><b>Final Amount</b>:</label><i class="fa fa-inr"></i><label class="" readonly="readonly" name = "gcs_cost_price" id="gcs_cost_price">0</label><br>
                                    bank charge(2%): <i class="fa fa-inr"></i><span id="bankcharge">0</span><br>
								</div>
								<div class="col-xs-12 bg-primary text-right">
									<label class="control-label" ><b>Final Payable</b>: <i class="fa fa-inr"></i><span id="finalpayble">0</span></label></div>
							</div>
							<div class="row">
								<div class="col-xs-12 mt20 mb20" style="text-align: center; border: none;">
									<?php echo CHtml::submitButton("Proceed To Buy", array('class' => 'btn btn-success btn-md', 'id' => 'proceedBuy')); ?>
								</div>
							</div>	
						</div>

					</div>
				</div>

			</div>
			<?php $this->endWidget(); ?>
		</div>
	</div>
</div>
<?php echo CHtml::endForm(); ?>

<script type="text/javascript">
	$('#GiftCardSubscriber_gcs_value_amount').keyup(function () {
		applyPromo();
		calculateFinalPrice();
	});

	$('#GiftCardSubscriber_gcs_quantity').change(function () {
		applyPromo();
		calculateFinalPrice();
	});

	$("#GiftCardSubscriber_gcs_promo_code").change(function () {
		applyPromo();
		calculateFinalPrice();
	});

	$('#proceedBuy').click(function () {
		var quantity = $('#GiftCardSubscriber_gcs_quantity').val();
		if (quantity < 1)
		{
			$('#GiftCardSubscriber_gcs_quantity_em_').html("Quantity not less than 1");
			$("#GiftCardSubscriber_gcs_quantity_em_").css('display', 'block');
			$("#GiftCardSubscriber_gcs_quantity_em_").css('color', '#e73d4a');
			$("#GiftCardSubscriber_gcs_quantity_em_").css('border-color', '#e73d4a');
			return false;
		}

	});

	function  calculateFinalPrice() {
		var costPrice = $('#GiftCardSubscriber_gcs_value_amount').val() * $('#GiftCardSubscriber_gcs_quantity').val();
		$('#costPrice').val(costPrice);
		$('#gcs_total_payable').html(costPrice);
		var discount = $('#promoAmt').val();
		if (discount > 0)
		{
			costPrice = (costPrice - discount);
		}
		var bankcharge = Math.round(((Math.abs(costPrice) * 0.02) >= 1) ? (Math.abs(costPrice) * 0.02) : 1);
		$('#gcs_cost_price').html((costPrice));
		$('#bankcharge').html(bankcharge);
		$("#gftCardAmt").val(costPrice);
		$('#finalpayble').html((costPrice + bankcharge));
	}

	function applyPromo()
	{
		$("#gcs_value_type").html("");
		$('#promoAmt').val(0);
		var promoCode = $('#GiftCardSubscriber_gcs_promo_code').val();
		var gftAmt = $('#GiftCardSubscriber_gcs_value_amount').val();
		var gftQt = $('#GiftCardSubscriber_gcs_quantity').val();
		var totPrice = $('#GiftCardSubscriber_gcs_value_amount').val() * $('#GiftCardSubscriber_gcs_quantity').val();
		if (promoCode != '')
		{
			var href2 = '<?= Yii::app()->createUrl("agent/giftcard/promoCodeVerify"); ?>';
			$.ajax({
				"url": href2,
				"type": "GET",
				"dataType": "json",
				"data": {"prmcode": promoCode, "agtid": "<?= $agentId; ?>", "totprice": totPrice, "qty": gftQt},
				"success": function (data) {
					if (data.success)
					{
						prmId = data.prmId;
						discount = data.prmamt;
						costPrice = data.costPrice;
						prmdesc = data.prmdesc;
						if (discount > 0 && (costPrice-discount)>0)
						{
							$('#costPrice').val(costPrice);
							$('#promoAmt').val(discount);
							$("#gftPromoId").val(prmId);
							$("#GiftCardSubscriber_gcs_promo_code_em_").css('display', 'none');
							if (prmId != '' && prmdesc != '') {
								$("#gcs_value_type").html(prmdesc + " - Applied");
							}
							calculateFinalPrice();
							return;
						}

						$('#GiftCardSubscriber_gcs_promo_code_em_').html("Invalid Promo Code!");
						$("#GiftCardSubscriber_gcs_promo_code_em_").css('display', 'block');
						$("#GiftCardSubscriber_gcs_promo_code_em_").css('color', '#e73d4a');
						$("#GiftCardSubscriber_gcs_promo_code_em_").css('border-color', '#e73d4a');
						return false;
					} else {
						alert('Failed to apply promocode.');
					}
				}
			});
		}
	}

</script>
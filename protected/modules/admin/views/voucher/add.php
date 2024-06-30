<style>
    .checkbox-inline{
        padding-left: 0 !important;
    }
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
    .new-booking-list label{ font-size: 11px;}
	.usertype,
	.cash,
	.coin,
	.fixed{ 
		padding: 10px; 
		margin: 10px; 
		border: 1px solid silver; 
	}
</style>
<?php
$version	 = Yii::app()->params['siteJSVersion'];
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/promo.js?v=' . $version);
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/city.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/plugins/form-typeahead/typeahead.bundle.min.js');
$jsrefresh	 = "
if($.isFunction(window.redirectList))
{
window.redirectList();
}
else
{
window.location.reload();
}
";
$dateFrom	 = $model->vch_valid_from != '' ? $model->vch_valid_from : date('Y-m-d H:i:s');
$dateTo		 = $model->vch_valid_to != '' ? $model->vch_valid_to : date('Y-m-d H:i:s', strtotime('+1 month 6am'));  // +1 year 6am
?>
 <!--<div class="text-left"><a class="btn btn-warning mb10" href="/aaohome/voucher/list" style="text-decoration: none">List</a></div> -->
 
<div class="row">
    <div class="col-xs-12 col-md-11 col-lg-11  new-booking-list" style="float: none; margin: auto">
		<?php
		$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'voucher-form', 'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
				'afterValidate'		 => 'js:function(form,data,hasError){
						if(!hasError){
							let type = $(".vouchType:checked").val();
							let promoSelected = $.trim($("#Vouchers_vch_promo_id").val());
							let walletAmt = $.trim($("#Vouchers_vch_wallet_amt").val());							
							if(type == 1 &&  promoSelected =="")
							{
								alert("Please select promo.");
								return false;
							}
							if(type == 2 &&  walletAmt =="")
							{
								alert("Please provide wallet amount.");
								return false;
							}

							
							$.ajax({
							"type":"POST",
							"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/voucher/add')) . '",
							"data":form.serialize(),
									"dataType": "json",
									"success":function(data1){
											if(data1.success)
											{
												alert("Voucher Saved Sucessfully");
												window.location.reload(true);
											}
											else
											{
												alert(data1.error);
											}
									},
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
				'class' => 'form-horizontal',
			),
		));
		/* @var $form TbActiveForm */
		?>
		<input type="hidden" id="voucherid" name="voucherid" value="<?= $model->vch_id; ?>">
		<div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default panel-border">
                    <div class="panel-body">
						<div class="row mb15">
							<?= CHtml::errorSummary($model); ?> 
							<div class="col-xs-12 col-sm-4">
								<label>Voucher Code *</label>
								<?php								
									$readonly = ($model->vch_id == 0 || $model->vch_id == '') ? "": "readonly";
								?>
								<?= $form->textFieldGroup($model, 'vch_code', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('readonly' => $readonly)))) ?>
							</div>
							<div class="col-xs-12 col-sm-4">
								<label>Name *</label>
								<?=$form->textFieldGroup($model, 'vch_title', array('label' => ''))?>
							</div>
							<div class="col-xs-12 col-md-4">
								<label>Selling Price</label>
								<?=$form->textFieldGroup($model, 'vch_selling_price', array('label' => ''))?>
							</div>
						</div>
						
						<div class="row mb15">
							<div class="col-xs-12 col-sm-12">
								<?= $form->textAreaGroup($model, 'vch_desc', array('label' => 'Voucher Description', 'widgetOptions' => array('htmlOptions' => array('style' =>'min-height:50px', 'placeholder' => 'Enter voucher description')))) ?>
							</div>
							
						</div>
						
                        <div class="row mb15">
							<div class="col-xs-12 col-sm-4">
							<label>Total voucher available</label>
								<?=$form->textFieldGroup($model, 'vch_max_allowed_limit', array('label' => ''))?>
							</div>
							<div class="col-xs-12 col-md-4">								
								<label>Type*</label>								
								<?= $form->radioButtonListGroup($model, 'vch_type', array('label' => '', 'widgetOptions' => array('htmlOptions' => ["class"=>"vouchType"], 'data' => $voucherType), 'inline' => true)) ?>				
							</div>
							<div class="col-xs-12 col-sm-4">
								<?php 								
								$show = (($model->vch_type != "" && $model->vch_type==1)?"":"none");  
								?>
								<div class="vou_pro" style="display:<?=$show?>">
								<label>Promo</label>
								
								<?php										
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'vch_promo_id',
											'val'			 => $model->vch_promo_id,
											'asDropDownList' => FALSE,
											'options'		 => array('data' => new CJavaScriptExpression($promoList)),
											'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Promo', 'class' => 'input-group')
										));
										?>
								</div>
								<?php 								
								$show = ($model->vch_type != "" && $model->vch_type==2)?"":"none";  
								?>
								<div class="vou_wall" style="display:<?=$show?>">
								<label>Wallet Amount</label>
								<?=$form->textFieldGroup($model, 'vch_wallet_amt', array('label' => ''))?>
								</div>
							</div>
						</div>
						<div class="row mb15">
							<div class="col-xs-12 col-md-4"><label>Voucher Partner</label>							
								<?= $form->radioButtonListGroup($model, 'vch_is_all_partner', array('label' => '', 'widgetOptions' => array('htmlOptions' => [], 'data' => $voucherPartner), 'inline' => true)) ?>					
							</div>
							<div class="col-xs-12 col-md-4"><label>Voucher User</label>							
								<?= $form->radioButtonListGroup($model, 'vch_is_all_users', array('label' => '', 'widgetOptions' => array('htmlOptions' => [], 'data' => $voucherUser), 'inline' => true)) ?>					
							</div>
							<div class="col-xs-12 col-sm-4">
								
							</div>							
						</div>
						<div class="row mb15">
							<div class="col-xs-12 col-md-4"><label>Max Redeem Per User</label>								
								<?=$form->textFieldGroup($model, 'vch_redeem_user_limit', array('label' => ''))?>								
							</div>
							<div class="col-xs-12 col-sm-4">
								<label>Max purchase per user</label>
								<?=$form->textFieldGroup($model, 'vch_user_purchase_limit', array('label' => ''))?>	
							</div>
							<div class="col-xs-12 col-sm-4">
								<label>Max purchase per partner</label>
								<?=$form->textFieldGroup($model, 'vch_partner_purchase_limit', array('label' => ''))?>	
							</div>	
								
						</div>
						<div class="row mb15">
							<?php 								
								$show = ($model->vch_valid_from)?"":"none"; 								
								$checked = ($model->vch_valid_from)?"checked":""; 
							?>
							<div class="col-xs-12 col-sm-3">
								<label>Available to purchase from</label>
								<div class="row ">
									<div class="col-xs-12 col-sm-7 pr5">										
										<input type="checkbox" name="offerValidityFrom" class="offerValidityFrom" value="1"  <?=$checked?>>
									
									</div>									
								</div>
							</div>
							
							
							<div class="col-xs-12 col-sm-3 ">
								<div class="validDatesF" style="display:<?=$show?>">
									<label>From Date</label>
									<div class="row ">
										<div class="col-xs-12 col-sm-7 pr5">
											<?=
											$form->datePickerGroup($model, 'vch_valid_from_date', array('label' => '','widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => true, 'value' => date('d/m/Y', strtotime($dateFrom)))), 'prepend' => '<i class="fa fa-calendar"></i>'));
											?>
										</div>									
									</div>
								</div>		
							</div>

							<?php 								
								$show = ($model->vch_valid_to)?"":"none";								
								$checked = ($model->vch_valid_to)?"checked":""; 
							?>

							
								<div class="col-xs-12 col-md-3"><label>Available to purchase upto</label>								
									<div class="row ">
										<div class="col-xs-12 col-sm-7 pr5">
										<input type="checkbox" name="offerValidityTo" class="offerValidityTo" value="1" <?=$checked?>>
										</div>

									</div>								
								</div>
							
							
							<div class="col-xs-12 col-md-3">
								<div class="validDatesT" style="display:<?=$show?>">
									<label>Upto Date</label>								
									<div class="row ">
										<div class="col-xs-12 col-sm-7 pr5">
											<?=
											$form->datePickerGroup($model, 'vch_valid_to_date', array('label' => '','widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => true, 'value' => date('d/m/Y', strtotime($dateTo)))), 'prepend'=> '<i class="fa fa-calendar"></i>'));
											?>
										</div>

									</div>								
								</div>	
							</div>
						</div>

						<!--  -->
						<div class="row">
							<div class="col-xs-12 text-center pb10">
								<input type="submit" value="Create voucher" name="yt0" id="promosubmit" class="btn btn-primary pl30 pr30">
							</div>
						</div>				
                    </div>
                </div>
            </div>
        </div>
		<?php $this->endWidget(); ?>
    </div>
</div>

<script>
 $(document).ready(function (){      
	
 });
$(".vouchType").change(function(){	
	if(this.value == 2)
	{
		$(".vou_wall").show();
		$(".vou_pro").hide();
		$('#Vouchers_vch_promo_id').val(null).trigger('change');
	} else {
		$(".vou_pro").show();		
		$(".vou_wall").hide();
	}
});
$(".offerValidityFrom").change(function(){	
	if(this.checked)	{
		$(".validDatesF").show();		
	} else {		
		$(".validDatesF").hide();
	}
});
$(".offerValidityTo").change(function(){	
	if(this.checked)	{
		$(".validDatesT").show();		
	} else {		
		$(".validDatesT").hide();
	}
});
</script>
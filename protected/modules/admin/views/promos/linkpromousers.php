<style>
	.form-group {
		margin: 0px !important;
	}
</style>
<?php
$datefrom	 = $promoUserModel->pru_valid_from != '' ? $promoUserModel->pru_valid_from : date('Y-m-d H:i:s');
$dateTo		 = $promoUserModel->pru_valid_upto != '' ? $promoUserModel->pru_valid_upto : date('Y-m-d H:i:s', strtotime('+1 year 6am'));
?>
<div class="row">
    <div class="col-xs-12 col-md-12 col-lg-12" style="float: none; margin: auto">
		<div class="panel">
			<div class="panel panel-body" style="background-color:antiquewhite;">
				<?php
				$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'rate-form', 'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error',
						'afterValidate'		 => 'js:function(form,data,hasError){
						if(!hasError){
							$.ajax({
							"type":"POST",
							"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/promos/linkPromoUsers')) . '",
							"data":form.serialize(),
									"dataType": "json",
									"success":function(data1){
											if(data1.success)
											{
												alert("Data saved successfully");
												bootbox.hideAll();
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
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array(
						'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data'
					),
				));
				/* @var $form TbActiveForm */
				?>
				<?= $form->hiddenField($promoUserModel, 'pru_ref_id', array('value' => $refId)); ?>
				<?= $form->hiddenField($promoUserModel, 'pru_promo_id', array('value' => $promoId)); ?>
				<input type="hidden" value="<?= $pruId ?>" name="pruId">
				<div class="row mb15 hide">
					<div class="col-xs-12 col-md-6">
						<div class="form-group">
							<label class="control-label">Type</label>
							<?php
							$userTypeArr = PromoUsers::$userCategoty;
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $promoUserModel,
								'attribute'		 => 'pru_ref_type',
								'val'			 => $promoUserModel->pru_ref_type,
								'data'			 => $userTypeArr,
								//'asDropDownList' => FALSE,
								//'options'		 => array('data' => new CJavaScriptExpression($weekDaysArr)),
								'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => '',
									'placeholder'	 => 'Select Type')
							));
							?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-6">
						<?= $form->numberFieldGroup($promoUserModel, 'pru_use_max', array('label' => 'Use Maximum', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Use max']))) ?>
					</div>
					<div class="col-xs-12 col-md-6" style="padding: 20px;">
						<?= $form->radioButtonListGroup($promoUserModel, 'pru_auto_apply', array('label' => 'Auto Apply', 'widgetOptions' => array('htmlOptions' => [], 'data' => [0 => 'No', 1 => 'Yes']), 'inline' => true)) ?>
					</div>
				</div>
				<div class="row mb15">
					<div class="col-xs-12 col-md-6"><label>Offer Valid From</label>
						<div class="row ">
							<div class="col-xs-12 col-sm-7 pr5">
								<?=
								$form->datePickerGroup($promoUserModel, 'pru_valid_from_date', array('label'			 => '',
									'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => true, 'value' => date('d/m/Y', strtotime($datefrom)))), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
								?>
							</div>
						</div>
					</div>

					<div class="col-xs-12 col-md-6"><label>Offer Valid Upto</label>
						<div class="row">
							<div class="col-xs-12 col-sm-7 pr5">
								<?=
								$form->datePickerGroup($promoUserModel, 'pru_valid_upto_date', array('label'			 => '',
									'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => true, 'value' => date('d/m/Y', strtotime($dateTo)))), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
								?>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 text-center pb10">
						<input type="submit" value="Submit" name="yt0" class="btn btn-primary pl30 pr30">
					</div>
				</div>
				<?php $this->endWidget(); ?>
			</div>
		</div>
	</div>
</div>
<script>
//    $('#PromoUsers_pru_ref_type').click(function ()
//    {
//
//        var userType = $('#PromoUsers_pru_ref_type').val();
//        if (userType == '0')
//        {
//            $('.user').removeClass('hide');
//        }
//        else
//        {
//            $('.user').addClass('hide');
//			$('#PromoUsers_pru_ref_type').val('0');
//			$('#PromoUsers_pru_ref_type').change();
//			$('#PromoUsers_pru_ref_type').click();
//            alert('Please select user');
//        }
//    });

</script>
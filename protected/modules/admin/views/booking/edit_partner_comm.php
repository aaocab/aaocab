
<? ?>
<div class="row">
    <div class=" " style="float: none; margin: auto">
		<label  class="col-xs-12 text-center   h4">For  <?= $model->bkgAgent->agt_company ?></label>
		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'cpcommission-form', 'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
				'afterValidate'		 => 'js:function(form,data,hasError){
					if(!hasError){
					$.ajax({
					"type":"POST",
					"dataType":"json",
					"url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
						"data":form.serialize(),
						"success":function(data1){
						if(data1.success){	
						alert("Partner Commission Updated");
						tranbox.modal("hide");						
						}
						else{
							$(".paymenttransaction").css("pointer-events","auto");
                                                
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
        <div class="panel panel-default pb0">
            <div class="  panel-body">
                <div class="row">
					<?= $form->errorSummary($model); ?>
					<?= CHtml::errorSummary($model); ?>
					<?= $form->hiddenField($model, 'bkg_id') ?>

					<div class="col-xs-12">
						<div class="row">
							<div class="col-xs-12 col-sm-4 ">Commission Type</div>
							<div class="col-xs-12 col-sm-7 ">
								<?=
								$form->dropDownListGroup($bivmodel, 'bkg_cp_comm_type', ['label'			 => '',
									'widgetOptions'	 => ['data'			 => [0 => 'Select Type', 1 => 'Percentage', 2 => 'Fixed Value'],
										'htmlOptions'	 => []]])
								?>
							</div>
						</div>
						<div class="row mt20">
							<div class="col-xs-12 col-sm-4 ">Commission Value</div>
							<div class="col-xs-12 col-sm-4 "> 
								<?= $form->textFieldGroup($bivmodel, 'bkg_cp_comm_value', array('label' => '', 'widgetOptions' => array('htmlOptions' => []))) ?>
							</div>
						</div>
						<div class="row mt20">
							<div class="col-xs-12 col-sm-4 ">Estimated Value</div>
							<div class="col-xs-12 col-sm-4 " id="cpCommEst"> 
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="  panel-footer">
				<div class="  text-center  ">
					<?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary pl30 pr30  ')); ?>
				</div>
			</div>

		</div>
		<?php $this->endWidget(); ?>

	</div>
</div> 
<script type="text/javascript">

	function calcComm() {
		var comm_type = parseInt($('#BookingInvoice_bkg_cp_comm_type').val());
		var comm_val = parseInt($('#BookingInvoice_bkg_cp_comm_value').val());
		var bkgid = parseInt($('#Booking_bkg_id').val());

		$href = "<?= Yii::app()->createUrl('admin/booking/getcommission') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			dataType: "json",
			data: {"bkg_id": bkgid, 'comm_type': comm_type, 'comm_val': comm_val},
			success: function (data) {
				$('#cpCommEst').text(data.commission);

			}
		});
	}
	$('#BookingInvoice_bkg_cp_comm_type').on('change', function () {
		calcComm();
	});
	$('#BookingInvoice_bkg_cp_comm_value').on('change', function () {
		calcComm();
	});
	$(document).ready(function ()
	{
		calcComm();
	});
</script>
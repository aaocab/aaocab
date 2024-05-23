<div class="panel-advancedoptions " >
    <div class="row">
        <div class="col-xs-12">            
            <div class="panel" >
                <div class="panel-body panel-body p0">
                    <div class="panel-scroll1">
                        <div>
							<?php
							$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
								'id'					 => 'packagedesc', 'enableClientValidation' => false,
								'clientOptions'			 => array(
									'validateOnSubmit'	 => true,
									'errorCssClass'		 => 'has-error',
								),
								'enableAjaxValidation'	 => false,
								'errorMessageCssClass'	 => 'help-block',
								'htmlOptions'			 => array(
									'class' => 'form-horizontal',
								),
							));
							/* @var $form TbActiveForm */
							?>
                            <div class="col-xs-12">
                                <span class="has-error text-danger" id = 'vnderror' style="display: none"></span>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12 mt10" >
									<label class="control-label">Booking Type</label>
									<?php
									$filters = [
										1	 => 'Outstation',
										2	 => 'Local',
									];
									$dataPay = Filter::getJSON($filters);
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'prc_booking_type',
										'val'			 => $model->prc_booking_type,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
										'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'required' => 'required', 'placeholder' => 'Select booking type')
									));
									?>	
                                </div>
                            </div>
							<div class="form-group">
                                <div class="col-xs-12" >
									<label class="control-label">Commission Type</label>
									<?php
									$filters = [
										1	 => 'Percentage',
										2	 => 'Fixed',
									];
									$dataPay = Filter::getJSON($filters);
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'prc_commission_type',
										'val'			 => $model->prc_commission_type,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
										'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'placeholder' => 'Select commission type')
									));
									?>	
                                </div>
                            </div>
							<div class="form-group">
                                <div class="col-xs-12" >
									<?= $form->numberFieldGroup($model, 'prc_commission_value', array('label' => "Commission Value", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter commission value', 'class' => ' m0')))) ?>  
                                </div>
                            </div>
                            <div class="Submit-button text-center" >
                                <button type="button" class="btn btn-primary mt10 submitCommission" >SUBMIT</button>
                            </div>
							<?php $this->endWidget(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	$(document).on('click', '.submitCommission', function () {
		$.ajax({
			"type": "POST",
			"url": "<?= Yii::app()->createUrl('admpnl/agent/addPartnerCommission', ['agtId' => $agtId, 'ruleId' => $ruleId]) ?>",
			'dataType': "json",
			"data": $("form").serialize(),
			"success": function (data1) {
				if (data1.success) {

					bootbox.confirm({
						message: data1.massage,
						callback: function () {
							location.reload();
						}
					})
				} else {
					bootbox.alert(data1.massage);
					return false;
				}
			}
		});
		return false;
	});
</script>

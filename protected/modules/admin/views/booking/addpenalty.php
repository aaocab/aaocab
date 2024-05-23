<style type="text/css">

    .form-group {
        margin-bottom: 7px;
        margin-top: 15px;

        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    .form-horizontal .checkbox-inline{
        padding-top: 0;
    }
    #BookingCab_chk_user_msg{
        margin-left: 10px
    }
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
    .selectize-input {
        min-width: 100px!important;
        width: 100%!important;      
    }
	.checkbox_style .checkbox-inline{ margin: 0 0 6px 0;}
</style>

<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-xs-12">
			<?php
			$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'refundForm', 'enableClientValidation' => true,
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
									alert(JSON.stringify(data1.error));
									//alert(JSON.parse(data1.success));
                                         cabBox.modal("hide");
										}
                                    else{   
                                          alert(JSON.stringify(data1.error)); 
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
					'class'	 => 'form-horizontal',
					'action' => Yii::app()->createUrl('admin/booking/addpenalty', ['booking_id' => $bkgid]),
				),
			));

			/* @var $form TbActiveForm */
			?>
            <div class="panel panel-default mb0">
                <div class="panel-body">
                    <div class="row">
						<div class="col-sm-12">
							<?php //echo $error."123"; ?>
							<?php // $form->hiddenField($model, 'bkg_bcb_id')							
							?>
							<?php
							//$model->act_ref_id = 43;
							?>

							<div class="form-group row">
								<label  class="col-xs-12 col-sm-4 col-md-3 control-label">Select Vendor : </label>
								<div class=" col-xs-12 col-sm-7 ">
									<div class="input-group cityinput col-sm-12 ">		
										<?php
										$data	 = $vendorJSON;
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'act_ref_id',
											'val'			 => $model->act_ref_id,
											'asDropDownList' => FALSE,
											'options'		 => array('data' => new CJavaScriptExpression($data)),
											'htmlOptions'	 => array('style' => 'width: 100%', 'placeholder' => 'Select Vendor')
										));
										?>
									</div>

									<div class="has-error"><?= $form->error($model, 'act_ref_id') ?></div>
								</div>
							</div>

							<div class="form-group row">
								<label  class="col-xs-12 col-sm-4 col-md-3 control-label">Select Reason : </label>
								<div class=" col-xs-12 col-sm-7 ">
									<div class="input-group cityinput col-sm-12 ">		
										<?php
										$data	 = PenaltyRules::model()->getRulesJSON();
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'penalty_rule_reason',
											'val'			 => $model->penalty_rule_reason,
											'asDropDownList' => FALSE,
											'options'		 => array('data' => new CJavaScriptExpression($data), 'multiple' => true),
											'htmlOptions'	 => array('style' => 'width: 100%', 'multiple' => 'multiple', 'placeholder' => 'Select Reason')
										));
										?>
									</div>

									<div class="has-error"><?= $form->error($model, 'penalty_rule_reason') ?></div>
								</div>
							</div>


							<div class="row">
								<label  class="col-xs-12 col-sm-4  col-md-3 control-label">Penalty : </label>
								<div class="col-xs-12 col-sm-8 checkbox_style">
									<div id="category_id">
										<?php
										//print_r($PenaltyReason);
										$i		 = 0;
										$j		 = 0;
										foreach ($PenaltyReason as $key => $Reasonval)
										{
											echo "<Input type='Checkbox' id='AccountTransactions_act_amount_$i' name='AccountTransactions[act_amount][]' value=$key>" . ' '
											. "<label id='reason_$j' for=$Reasonval>$Reasonval</label></br>";
											$i++;
											$j++;
										}
										?>
									</div>
									<input type="checkbox" id="penalty_other_reason" name="AccountTransactions[penalty_other_reason][]" value="1" onclick="penalty_charges();" > Others

								</div>
								<?= $form->hiddenField($model, 'total_penalty') ?>
								<?= $form->hiddenField($model, 'act_remarks') ?>

							</div>		


							<div class="row">

								<div class="col-xs-12  " id="penaltyAmount" style="display:none;"> 
									<?= $form->textFieldGroup($model, 'penalty_amount', array('label' => 'Amount', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control', 'placeholder' => "Enter Amount"]))) ?>
								</div>
							</div>		

							<div class="row">
								<div class="col-xs-12  "> 
									<?= $form->textAreaGroup($model, 'additional_remarks', array('label' => 'Additional Remarks', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control', 'placeholder' => "Additional Remarks"]))) ?>
								</div>
							</div>		

						</div>
					</div>
					<!--					<div class="row">
											<div  class="col-xs-12  " id="amt_id" style="display:none;" >
												<div class="row">								
													<div  class="col-xs-12   text-left">
														<strong>Total Amount By Penalty Reason :</strong> <i class="fa fa-inr"></i><span id='total_penalty_value'></span>
													</div>
												</div>
												<div class="row">
													<div  class="col-xs-12   text-left">
														<strong>Penalty Reason :</strong>  <span id='total_penalty_name'></span>
													</div>
												</div>
											</div>
										</div>-->
                </div>
                <div class="panel-footer text-center">
					<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary pl30 pr30')); ?>
                </div>
            </div>
			<?php $this->endWidget(); ?>
        </div>
    </div>
</div>

<script>


	jQuery(document).ready(function ($) {

		$('#category_id :checkbox').click(function () {
			var sum = 0;
			var select_data = "";
			$('#category_id :checkbox:checked').each(function (i) {
				var value = $(this).attr("value");
				sum += parseFloat(value);
				var hh = "";
				if (i > 0)
				{
					hh = ', ';
				}
				select_data += hh + $(this).next().html();
			});
			//alert(sum);
			$('#amt_id').show();
			$('#AccountTransactions_total_penalty').val(sum);
			$('#total_penalty_value').html(sum);


			//var select_data = new Array();

			//Reference the CheckBoxes and insert the checked CheckBox value in Array.
			/*$('#category_id :checkbox:checked').each(function (k) {
			 var hh = "#AccountTransactions_act_amount_" + k;
			 console.log(hh);
			 selected.push($(hh).text());
			 });*/


			//Display the selected CheckBox values.
			//if (select_data.length > 0) {
			//var remarks = select_data.join(",");
			//alert("Selected values: " + selected.join(","));
			//alert(remarks);
			//}
			//var labeltext = $("label[for='AccountTransactions_act_amount_0']");
			$('#AccountTransactions_act_remarks').val(select_data);
			$('#total_penalty_name').html(select_data);
		});

	});

	function penalty_charges()
	{
		//alert("dsdf");
		var checkBox = document.getElementById("penalty_other_reason");
		if (checkBox.checked == true)
		{
			document.getElementById("penaltyAmount").style.display = "block";
		} else
		{
			document.getElementById("penaltyAmount").style.display = "none";
		}

	}
//	$('#refundForm').one('submit', function () {
//        $(this).find('input[type="submit"]').attr('disabled', 'disabled');
//    });
</script>


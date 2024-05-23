
<style>

    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
	.form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
</style>


<div class="panel-advancedoptions" >

	<div id="enquirySec">

		<div class="row">
			<div class="col-xs-12"><h2>Reason for doing Manual Cancellation</h2></div>
			<div class="col-xs-12">
				<?php
				$bkModel = Booking::model()->findByPk($bkid);
				$form1	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'nmi-form', 'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error',
						'afterValidate'		 => 'js:function(form,data,hasError){
                                            if(!hasError){
                                                $.ajax({
                                                "type":"POST",
                                                "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/addnmi', ['bkg_id' => $bkid])) . '",
                                                "data":form.serialize(),
                                                            "dataType": "json",
                                                            "success":function(data1){
                                                                //alert("huhjuhu");
                                                                if(data1.success){
                                                                    $("#cancelBookingSec").show();
                                                                    $("#enquirySec").hide();

                                                                }else{
																	
                                                                   $("#errorMSg").text(data1.error);
                                                                 }
                                                            },
                                                });
                                                }
                                            }'
					),
								
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'action'				 => array(),
					'htmlOptions'			 => array(
						'class' => 'form-horizontal',
					),
				));
				/* @var $form TbActiveForm */
				?>
				<div class="col-xs-12 col-md-12 ">
					<label for="delete"><b>Do we need to have more vendors serving this region: </b>
						<span class="required"></span></label>
					<?php
								
if ($isNMIcheckedZone > 0)
{
	echo "<br />"."NMI already saved.";
}
else
{
	echo $form1->radioButtonListGroup($bkModel->bkgTrail, 'btr_nmi_flag', array(
		'label'			 => '', 'widgetOptions'	 => array(
			'data' => [1 => 'Yes', 0 => 'No'],
		), 'groupOptions'	 => ['class' => 'pl20']
			//'inline'		 => true,
			)
	);
}
?>
				</div>
				<div class="col-xs-12 col-md-12  ">

					<label for="delete"><b>Reason for doing manual cancellation: </b>
					</label>
					<?=
					$form1->radioButtonListGroup($bkModel->bkgTrail, 'btr_nmi_reason', array(
						'label'			 => '', 'widgetOptions'	 => array(
							'data' => BookingTrail::model()->nmiReason,
						),'groupOptions'=>['class'=>'pl20']
								
							)
					);
					?> 
					<span for="delete" id="errorMSg" class="required"></span>

				</div>
				<div class="col-xs-12"> 
					<div class="Submit-button" >
						<?php echo CHtml::submitButton('Note to booking', array('class' => 'btn btn-warning')); ?>

					</div></div>
				<?php $this->endWidget(); ?>
			</div>
		</div>
	</div>



	<div id="cancelBookingSec"style=" display:none; " >
		<div class="row">
			<div class="col-xs-12"> 
				<div class="panel mb0">                  

					<div class="panel-heading text-center pt0" style="color: #000000"><?= $bkgCode ?></div>
                   
					<div style="width: 100%; padding: 3px; overflow: auto; line-height: 10px; font: normal arial; border-radius: 5px; -moz-border-radius: 5px; border: 1px #aaa solid;color: #444;">
						<?= CHtml::beginForm(Yii::app()->createUrl('admin/booking/canbooking'), "post", ['accept-charset' => "UTF-8", 'id' => "deleteForm", 'class' => "form", 'data-replace' => ".error-message p"]); ?>
						<?= CHtml::hiddenField("bk_id", $bkid, ['id' => "bk_id"]) ?>
						<div class="panel-body panel-no-padding">
<? if($showAskPanel==1){?>
						<div class="form-group" id="checkEscalation">
					<label style="line-height:1.6em"><b>Before you cancel,<br /> do you want to remove all escalation and accounting flag from this booking? </b>
					<br />
                <?php
              if($escalation == 1)
			  {
               echo CHtml::checkBoxList('offEscalation', '', array(
             1 => 'Remove Escalation'), array('id'=>'checkbox-list-id','class'=>'checkboxlist', 'required')).'<br />';
			  }

           if($accounts == 1)
			  {
               echo CHtml::checkBoxList('offAccounts', '', array(
             1 => 'Remove Accounts Flag'), array('id'=>'checkbox-list-id','class'=>'checkboxlist', 'required'));
			  }
                ?>

					</label>
						</div>
<? }?>

							<div class="form-group">

								<label for="delete"><b>Reason for cancellation : </b></label>
								<? //= CHtml::dropDownList('bkreason', '', ['' => '< Select a reason >'] + Booking::model()->getCancelReasonList('cancel'), ['id' => "bkreason", 'class' => "form-control", 'required' => true]) ?>
								<?= CHtml::dropDownList('bkreason', '', ['' => '< Select a reason >'] + CancelReasons::model()->getListbyUserType(2), ['id' => "bkreason", 'class' => "form-control", 'required' => true]) ?>
							</div>
							<div class="form-group">
								<div class="mt10" id="reasontext" style="display: none">
									<?= CHtml::textArea('bkreasontext', '', ['id' => "bkreasontext", 'class' => "form-control", 'placeholder' => 'Enter reason'])
									?>

								</div>
							</div>
							<div class="Submit-button text-center" >
								<? echo CHtml::submitButton("SUBMIT", ['class' => "btn btn-primary mt10"]); ?>

							</div></div>
						<?= CHtml::endForm() ?>


					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<script>
    $(function () {
        $("#bkreason").change(function () {
            $("#reasontext").show();
            $("#bkreasontext").attr('required', 'required');
        });
    });

    $('input[type="submit"]').click(function () {
        if ($('#bkreason').val() != '' && $('#bkreasontext').val() != '')
        {
            $('input[type="submit"]').css('pointer-events', 'none');
        }

    });
	
	
	
</script>

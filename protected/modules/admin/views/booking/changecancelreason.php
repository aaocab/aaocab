
<style>
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
    .modal-backdrop{
        height: 650px !important;
    }   

    .review
    {
        margin-top: 20px;color: #f00;font-size: 13px;display: none;text-align: center;
    }
    .rounded {
        border:1px solid #ddd;
        border-radius: 10px;

    }
</style>
    <div class="row">       
			 <div class="col-xs-12 text-center pb20">CHANGE CANCEL REASON</div>
					<?php
								$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
									'id'					 => 'changecancelreason-form',
									'enableAjaxValidation'	 => true,
									'clientOptions'			 => array(
										'validateOnSubmit'	 => true,
										'afterValidate'		 => 'js:function(form,data,hasError){
                                            
                                      
                                        }'
									),
								));
								?>
								<?= $form->errorSummary($model); ?>
                                <input type="hidden" name="bkg_id" value="<?php echo $model->bkg_id;?>">
                                <div class="col-xs-12">
								      <?php $cancelReasons = CancelReasons::model()->getById($model->bkg_cancel_id); ?>
								     <label style="font-weight: bold;padding-right: 10px;">Current reason for cancellation:  </label><br><?php echo $cancelReasons["cnr_reason"]; ?>
								</div>							
								<div class="col-xs-12 pt20">

									<label for="delete"><b>Choose reason for cancellation : </b></label>
									<? //= CHtml::dropDownList('bkreason', '', ['' => '< Select a reason >'] + Booking::model()->getCancelReasonList('cancel'), ['id' => "bkreason", 'class' => "form-control", 'required' => true]) ?>
									<?= CHtml::dropDownList('bkreason', '', ['' => '< Select a reason >'] + CancelReasons::model()->getListbyUserType(2), ['id' => "bkreason", 'class' => "form-control", 'required' => true]) ?>
								</div>
								<div class="col-xs-12 pt10">
                                <div class="mt10" id="reasontext" style="display: none">
									<?= CHtml::textArea('bkreasontext', '', ['id' => "bkreasontext", 'class' => "form-control", 'placeholder' => 'Enter reason'])
									?>
								</div>
								</div>
								<div class="col-xs-12 p15 text-center ">
									<button class="btn btn-primary" type="submit" value="cancelreason" tabindex="2" >change</button>
								</div>
								<?php $this->endWidget(); ?>
        </div>
</div>
<script>
    $('#changecancelreason-form').submit(function (event) {

            $.ajax({
                "type": 'POST',
                "dataType": "json",
                "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/changecancelreason')) ?>",
                data: $('#changecancelreason-form').serialize(),
                success: function (data)
                { 
                    if (data.success) 
					{
                        location.reload();
                    }
                }


            });
        event.preventDefault();
    });

		$("#bkreason").change(function () {
		 $("#reasontext").show();
		 $("#bkreasontext").attr('required', 'required');
        });
</script>
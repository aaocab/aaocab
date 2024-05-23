
<style>

    .review
    {
        margin-top: 20px;color: #f00;font-size: 13px;display: none;text-align: center;
    }
</style>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-xs-12">

			<?
			$error		 = '';
			$errorshow	 = ($error == '') ? 'hide' : '';
			?>
            <div class="panel" >                
                <div class="panel-body panel-body panel-no-padding">
                    <div class="panel-scroll1">

                        <div style="width: 100%; padding: 3px; overflow: auto; line-height: 10px; font: normal arial; border-radius: 5px; -moz-border-radius: 5px; border: 1px #aaa solid;color: #444;">
                            <div class="form" >
								<?php
								$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
									'id'					 => 'customer-reply-form',
									'enableAjaxValidation'	 => true,
									'clientOptions'			 => array(
										'validateOnSubmit'	 => true,
										'afterValidate'		 => 'js:function(form,data,hasError){
                                            
                                      
                                        }'
									),
								));
								?>
								<?= $form->errorSummary($model); ?>
								<?= $form->hiddenField($model, 'rtg_id') ?>
								<?= $form->hiddenField($model, 'customer_email', array('value' => $bmodel->bkgUserInfo->bkg_user_email)); ?>
								<?= $form->hiddenField($model, 'customer_name', array('value' => $bmodel->bkgUserInfo->bkg_user_fname . ' ' . $bmodel->bkgUserInfo->bkg_user_lname)); ?>
								<?= $form->hiddenField($model, 'booking_id', array('value' => $bmodel->bkg_booking_id)); ?>
                                <div class="col-xs-12" style="min-height:100px">
                                    <div class="form-group">
                                        <label for="name"><b>Name : <?= $bmodel->bkgUserInfo->bkg_user_fname . ' ' . $bmodel->bkgUserInfo->bkg_user_lname ?></b></label>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone"><b>Email : <?= $bmodel->bkgUserInfo->bkg_user_email ?></b></label>
                                    </div>
                                    <div class="form-group pt20">
										<?= $form->textAreaGroup($model, 'rtg_customer_reply', array('widgetOptions' => array('htmlOptions' => array('style' => 'width : 450px; height : 100px;')))) ?>
                                    </div> 
                                    <div class="col-xs-12 p15 text-center">
                                        <button class="btn btn-primary" type="submit" value="Submit" tabindex="2" >Submit</button>
                                    </div>
                                </div>
								<?php $this->endWidget(); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    $('#customer-reply-form').submit(function (event) {

        $.ajax({
            type: 'POST',
            "dataType": "json",
            "url": "<?= Yii::app()->createUrl('admin/rating/ajaxcustreply') ?>",
            "data": $('#customer-reply-form').serialize(),
            success: function (data)
            {
                if (data.result == true) {
                    $(".bootbox").hide();
                    $("#rating-grid").yiiGridView("update");
                } else {
                    alert('Something went wrong');
                }
            }

        });

        event.preventDefault();
    });






</script>
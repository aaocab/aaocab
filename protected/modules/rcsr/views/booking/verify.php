
<div class="row mb10">
    <div class="col-xs-12">
        <p class="weight400 text-center">Please enter the verification code you received on <br>
            <?if($model->bkg_contact_no!=''){?><b>Phone: +<?= $model->bkg_country_code ?><?= $model->bkg_contact_no ?></b> 
            <br>OR <br>
            <?}?>
            <b>Email: <?=($model->bkg_user_email!="")?$model->bkg_user_email:""?></b>
        </p>
    </div>
</div>
<div class="row">
    <div class="col-xs-12  book-panel2 float-none marginauto">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php
                $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'verify-form', 'enableClientValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                        'errorCssClass' => 'has-error',
                        'afterValidate' => 'js:function(form,data,hasError){
                                                    if(!hasError){
                                                   $.ajax({
						"type":"POST",

						"dataType":"json",
						"url":"' .Yii::app()->createUrl('rcsr/booking/confirmmobile'). '",
						"data":form.serialize(),
                       
						"success":function(data2){                     
                            
                            if(data2.success){
                               alert("Booking confirmed successfully");
                               location.href = "'.Yii::app()->createUrl('rcsr/booking/view',['id'=>'']).'"+data2.bkgid;
                           
                            }else{
                            $("#errorshow").show();
                            $("#moberrordiv").html("Verification code did not match! Booking cannot be verified");
                            
                                }
							
						},
						error: function (xhr, ajaxOptions, thrownError) 
						{
								alert(xhr.status);
								alert(thrownError);
						}
					});
                            
                        }
                    }'
                    ),
                    // Please note: When you enable ajax validation, make sure the corresponding
                    // controller action is handling ajax validation correctly.
                    // See class documentation of CActiveForm for details on this,
                    // you need to use the performAjaxValidation()-method described there.
                    //  'enableAjaxValidation' => false,
                    'enableAjaxValidation' => false,
                    'errorMessageCssClass' => 'help-block',
                    //  'action' => Yii::app()->createUrl('index/confirm'),
                    'htmlOptions' => array(
                        'class' => 'form-inline',
                    ),
                ));
                /* @var $form TbActiveForm */
                ?> 
                <?php echo CHtml::errorSummary($model); ?>
                <?= $form->hiddenField($model, 'bkg_id') ?>
                <?= $form->hiddenField($model, 'hash') ?>

                <div id="errorshow" class="row " style="display: none">

                    <div class="col-xs-12 header" style="color: #f00000" id="moberrordiv">verification code did not match!</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                            <?= $form->textFieldGroup($model, 'bkg_verification_code1', array('label' => 'Phone', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control full-width border-radius']))) ?>
                    </div>
                     <div class="col-sm-6">
                            <?= $form->textFieldGroup($model, 'bkg_verification_code2', array('label' => 'Email', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control full-width border-radius']))) ?>
                    </div>
                    <div class="col-sm-12 mt10">
                        <div class="form-group full-width">
                            <button class="btn btn-primary border-radius full-width orange-bg border-none" id="sbmtbtn" type="submit" value="Verify">Apply</button>
                        </div>
                    </div>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div> 
    </div>

</div>

<?
if($smsExceed){
?>
<div class="row">
    <div class="col-xs-12">
       (verification code already sent multiple times)
    </div> 
</div>
<?}?>
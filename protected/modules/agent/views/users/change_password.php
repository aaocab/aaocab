<style>
    .booking_new .form-group{
        padding-bottom: 0px !important;
    }
    .panel{
     box-shadow: 0 0px 0px rgba(0,0,0,.1), 0 0px 0px rgba(0,0,0,.18) !important; 
   }


</style>
<div class="panel"> 
    <div class="panel-body">
        <?php
        $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id' => 'vendorform',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'errorCssClass' => 'has-error',
                'afterValidate' => 'js:function(form,data,hasError){
                        if(!hasError){
                                $.ajax({
                                "type":"POST",
                                "dataType":"html",
                                "url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
                                "data":form.serialize(),
                                "success":function(data1){
                                 var data = $.parseJSON(data1);

                                      if(!data.success){
                                         $("#Users_old_password_em_error").html(data.err_messages);
                                         $("#Users_old_password_em_error").show();
                                       }else{
                                          $("#Users_old_password_em_error").hide();
                                          bootbox.hideAll();
                                          location.href = "' . CHtml::normalizeUrl(Yii::app()->createUrl('agent/index/logout')).'";
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
            'enableAjaxValidation' => false,
            'errorMessageCssClass' => 'help-block',
            'htmlOptions' => array(
                'class' => '',
            ),
        ));
        /* @var $form TbActiveForm */
        ?>  
        <div class="booking_new"></div>
        <?= $form->passFieldGroup($model, 'old_password', array('label' => "Current Password *", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Password'))))
        ?>
        <span class="text-danger" id="Users_old_password_em_error" style="display: none"></span>
        <?= $form->passFieldGroup($model, 'new_password', array('label' => "New Password *", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Password'))))
        ?>
        <?= $form->passFieldGroup($model, 'repeat_password', array('label' => "Repeat Password *", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Repeat Password'))))
        ?>
        <div class="col-sm-12 text-center">
            <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-danger')); ?>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>
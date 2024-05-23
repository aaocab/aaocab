
<div class="panel">
    <div class="panel panel-heading"></div>
    <div class="panel panel-body">
		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'agent-transaction-form', 'enableClientValidation' => true,
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
                    "beforeSend": function () {
                        ajaxindicatorstart("");
                    },
                    "complete": function () {  
                        ajaxindicatorstop();
                    },
                    "success":function(data1){
                        if(data1.success){                       
                                 alert("New user added success");
                                $(\'#listtolink\').yiiGridView(\'update\');
                                addnewuserbox.hide();
                        } 
                        else{                      
                        var errors = data1.errors;                                           
                        settings=form.data(\'settings\');
                         $.each (settings.attributes, function (i) {                            
                            $.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
                          });
                          $.fn.yiiactiveform.updateSummary(form, errors);
                        } 
                    },
                    error: function(xhr, status, error){                     
                       var x= confirm("Network Error Occurred. Do you want to retry?");
                       if(x){
                                $("#edit-booking-form").submit();
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
        <input type="hidden" name="agt_id" value="<?= $agt_id ?>">
        <div class="col-xs-5"><? echo $form->textFieldGroup($model, 'usr_name', array('label' => "First Name", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter first name')))); ?></div>
        <div class="col-xs-5 col-xs-offset-2"><? echo $form->textFieldGroup($model, 'usr_lname', array('label' => "Last Name", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter last name')))); ?></div>
        <div class="col-xs-5"><? echo $form->textFieldGroup($model, 'usr_email', array('label' => "Email", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Email (will be used for login)')))); ?></div>
        <div class="col-xs-5  col-xs-offset-2"><? echo $form->textFieldGroup($model, 'usr_mobile', array('label' => "Mobile", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter mobile number')))); ?></div>
        <div class="col-xs-12 text-center"><button class="btn btn-info" type="submit">Add</button></div>
		<? $this->endWidget(); ?>
	</div>
</div>
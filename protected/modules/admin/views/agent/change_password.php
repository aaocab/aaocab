

<?php
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'driver-register-form',
	'enableClientValidation' => true,
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
                           console.log(data1.err_messages);
                                if(data1.success==true){
                                 alert("Password Changed Successfully");
                                    bootbox.hideAll();
                                }else{
                                     alert(data1.err_messages);
                                }
                              },
                                error: function(xhr, status, error){
                                                                    alert(\'Sorry error occured\');
                                                                }
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
		'class' => 'form-horizontal'
	),
		));
/* @var $form TbActiveForm */
?>  
<div class="row pt10"> 
	<div class="col-sm-12">
		<input type="hidden" value="<?= $agent ?>" name="agent">
		<?= $form->passFieldGroup($model, 'new_password', array('label' => "Password *", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Password', 'autocomplete' => 'new-password',))))
		?>
	</div>
	<div class="col-sm-12">
		<?= $form->passFieldGroup($model, 'repeat_password', array('label' => "Confirm Password *", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Confirm Password', 'autocomplete' => 'new-password1',))))
		?>
	</div>
	<div class="col-sm-12 text-center">
		<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?>
	</div>
</div>
<?php
$this->endWidget();

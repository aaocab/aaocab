<?
    $version		 = Yii::app()->params['siteJSVersion'];
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.js?v=' . $version);
	
?><div class="content-boxed-widget">
<div style="margin-bottom: 10px;color: <?=($status)?'#008000':'#B80606';?>">  
<?php echo $message; ?>
</div>
<h3 class="mb10">
Change Password
</h3>

                        <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'id' => 'cpass-form', 'enableClientValidation' => true,
                            'clientOptions' => array(
                                'validateOnSubmit' => true,
                                'errorCssClass' => 'has-error'
                            ),
                            // Please note: When you enable ajax validation, make sure the corresponding
                            // controller action is handling ajax validation correctly.
                            // See class documentation of CActiveForm for details on this,
                            // you need to use the performAjaxValidation()-method described there.
                            'enableAjaxValidation' => false,
                            'errorMessageCssClass' => 'help-block',
                            'htmlOptions' => array(
                                'class' => 'form-horizontal',
                            ),
                        ));
                        if ($status == 'no') {
                            $form->addError($model->old_password, 'The token must contain letters or digits.');
                        }
                       
                        ?>


                        <div class="input-simple-1 has-icon input-blue bottom-30">
                            <em for="old_password">Current Password</em>
                            <?= $form->passwordField($model, 'old_password', array('label' => '', 'widgetOptions' => array('htmlOptions' => [ 'required' => TRUE, 'placeholder' => "Current Password"]))) ?>
                        </div>
                        <div class="input-simple-1 has-icon input-blue bottom-30">
                            <em for="new_password">New Password</em>
                            <?= $form->passwordField($model, 'new_password', array('label' => '', 'widgetOptions' => array('htmlOptions' => [ 'required' => TRUE, 'placeholder' => "New Password"]))) ?>
                        </div>
                        <div class="input-simple-1 has-icon input-blue bottom-30">
                            <em for="repeat_password">Confirm Password</em>
                            <?= $form->passwordField($model, 'repeat_password', array('label' => '', 'widgetOptions' => array('htmlOptions' => [ 'required' => TRUE, 'placeholder' => "Confirm Password"]))) ?>
                        </div>                        
                        <div class="text-center pb10">
                            <input class="uppercase btn-orange shadow-medium"  type="submit" name="changepassword" value="Change Password"/>
                        </div>
                        <?php $this->endWidget(); ?>
            </div>
<script type="text/javascript">
	$jsBookNow = new BookNow();
	$( "#cpass-form" ).submit(function( event ) {	
		var is_error = 0;
		var msg = "";		
		if ($.trim($("#Users_old_password").val()) == "")
		{
			msg += "Old Password cannot be blank<br/>";
			is_error++;
		}
		if ($.trim($("#Users_new_password").val()) == "")
		{
			msg += "New Password cannot be blank<br/>";
			is_error++;
		}
		if ($.trim($("#Users_repeat_password").val()) != $.trim($("#Users_new_password").val()))
		{
			msg += "Repeat Password does not match<br/>";
			is_error++;
		}
		
		if(is_error > 0) {				
			$jsBookNow.showErrorMsg(msg);
			event.preventDefault();			
		}	
	});
</script>
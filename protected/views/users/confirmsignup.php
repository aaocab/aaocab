<style>
    .form-group {
        margin-bottom: 7px;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

</style>
<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-8 book-panel2">
        <div class="panel panel-primary">
            <div class="panel-body">
				<?php
				$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'signup-form', 'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error'
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

                <div class="row">
                    <div style="text-align:center; padding:0 20px ;">

						<?
						if ($status == 'errors')
						{
							echo "<span style='color:#ff0000;'>Password didn't match.</span>";
						}
						elseif ($status == 'emlext')
						{
							echo "<span style='color:#B80606;'>This Email addresss is already registered. Please enter a new email address.</span>";
						}
						elseif ($status == 'error')
						{
							echo "<span style='color:#ff0000;'>Please Try Again.</span>";
						}
						else
						{
							
						}
						?>
                    </div>
					<? //php echo CHtml::errorSummary($model);   ?>
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <label>First Name<span style="color: red;font-size: 15px;">*</span></label>
							<?= $form->textFieldGroup($contactModel, 'ctt_first_name', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Enter your name"]))) ?>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <label>Last Name<span style="color: red;font-size: 15px;">*</span></label>
							<?= $form->textFieldGroup($contactModel, 'ctt_last_name', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Enter your last name"]))) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <label>Email<span style="color: red;font-size: 15px;">*</span></label>
							<?= $form->emailFieldGroup($emailModel, 'eml_email_address', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Email (will be used for login) "]))) ?>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <label>Mobile<span style="color: red;font-size: 15px;">*</span></label>
                            <div class="row">
                                <div class="col-xs-4 col-sm-4 col-md-4 isd-input">
									<?php //$form->textFieldGroup($model, 'usr_country_code', array('label' => '', 'widgetOptions' => array('htmlOptions' => [ 'placeholder' => ""])))  ?>
									<?php
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $phoneModel,
										'attribute'			 => 'phn_phone_country_code',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "Code",
										'fullWidth'			 => false,
										'htmlOptions'		 => array(
										),
										'defaultOptions'	 => array(
											'create'			 => false,
											'persist'			 => false,
											'selectOnTab'		 => true,
											'createOnBlur'		 => true,
											'dropdownParent'	 => 'body',
											'optgroupValueField' => 'id',
											'optgroupLabelField' => 'pcode',
											'optgroupField'		 => 'pcode',
											'openOnFocus'		 => true,
											'labelField'		 => 'pcode',
											'valueField'		 => 'pcode',
											'searchField'		 => 'name',
											//   'sortField' => 'js:[{field:"order",direction:"asc"}]',
											'closeAfterSelect'	 => true,
											'addPrecedence'		 => false,
											'onInitialize'		 => "js:function(){
                            this.load(function(callback){
                            var obj=this;                                
                            xhr=$.ajax({
                     url:'" . CHtml::normalizeUrl(Yii::app()->createUrl('index/country')) . "',
                     dataType:'json',                  
                     success:function(results){
                         obj.enable();
                         callback(results.data);     
                         obj.setValue('91');
                     },                    
                     error:function(){
                         callback();
                     }});
                    });
                    }",
											'render'			 => "js:{
                     option: function(item, escape){                      
                     return '<div><span class=\"\">' + escape(item.name) +'</span></div>';
                },
				option_create: function(data, escape){                
                return '<div>' +'<span class=\"\">' + escape(data.pcode) + '</span></div>';
				}}",
										),));
									?>
                                </div>
                                <div class="col-xs-8 col-sm-8 col-md-8">
									<?= $form->textFieldGroup($phoneModel, 'phn_phone_no', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Mobile No.(will be used for verification)"]))) ?>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <label>Password<span style="color: red;font-size: 15px;">*</span></label>
							<?= $form->passwordFieldGroup($model, 'new_password', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Password"]))) ?>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <label>Repeat Password<span style="color: red;font-size: 15px;">*</span></label>
							<?= $form->passwordFieldGroup($model, 'repeat_password', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Repeat Password"]))) ?>
                        </div>
                    </div> 
                    <div class="form-group">
                        <div class="form-group text-center">
							<?= CHtml::submitButton("UPDATE", ['class' => "btn btn-primary white-color", 'tabindex' => "4"]); ?>
                        </div>
                    </div>
                    <div class="clr"></div>
                </div>
				<?php $this->endWidget(); ?>
            </div>
            <div class="panel-footer mr0 ml0 mb0 text-center hide" >
                <div class="mt10">
                    <a class="forgotpass2" onclick="bKloginHandler()">Forgot my password?</a>
                </div>
                <hr class="m10">
                <div style="text-align: center;">
                    <label class="mr10"><strong>Connect with</strong></label>
                    <a class="btn btn-lg btn-social btn-facebook pl15 pr15" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook')); ?>"><i class="fa fa-facebook"></i></a>
                    <a class="btn btn-lg btn-social btn-googleplus pl15 pr15" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google')); ?>"><i class="fa fa-google-plus"></i></a>
                    <a class="btn btn-lg btn-social btn-linkedin pl15 pr15" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'LinkedIn')); ?>"><i class="fa fa-linkedin"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4 col-md-4">
        <div class="register-add">
            <img src="<?= Yii::app()->baseUrl . DIRECTORY_SEPARATOR; ?>images/add5.jpg" alt="India">
        </div>
    </div>            
</div>
<script type="text/javascript">
    $(document).ready(function () {
    });
    function validateCheckHandlerss() {
        if ($("#formId").validation({errorClass: 'validationErr'})) {
            return true;
        } else {
            return false;
        }
    }
</script>

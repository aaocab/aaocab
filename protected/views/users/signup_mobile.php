<?php
 $this->renderPartial('../index/head_mobile');
?>
<? $imgVer	 = Yii::app()->params['imageVersion']; ?>
<div class="page-login header-clear-large page-login-full">
	<h3 class="ultrabold top-30 bottom-0 text-center">Sign Up to aaocab</h3>
	<p class="text-center mb0">Already on aaocab? <a href="signin">Log In</a></p>
	<div class="line-f3 line-height20 text-center uppercase mt20">
		<a  class="button-round button-icon shadow-small regularbold bg-facebook button-s" target="_blank" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook')); ?>"><i class="fab fa-facebook"></i> Connect with Facebook</a>
		
	</div>
	<div class="line-f3 line-height20 text-center uppercase mb30">
		<a class="button-round button-icon shadow-small regularbold bg-google button-s pl40 pr40" target="_blank"  href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google')); ?>"><i class="fab fa-google"></i> Connect with Google</a>
	</div>

	<div class="decoration decoration-margins ml0 mr0"><span class="or_style">OR</span></div>
	<?php
                $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'signup-form', 'enableClientValidation' => true,
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
                /* @var $form TbActiveForm */
        ?>
		<div class="line-f3">
			<div class="input-simple-1 has-icon input-green bottom-20"><strong>Required Field</strong><em>First name</em><i class="fas fa-user-alt"></i><?= $form->textFieldGroup($model, 'usr_name', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Enter your name"]))) ?></div>
		</div>
		<div class="line-f3">
			<div class="input-simple-1 has-icon input-green bottom-20"><strong>Required Field</strong><em>Last name</em><i class="fas fa-user-alt"></i><?= $form->textFieldGroup($model, 'usr_lname', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Enter your last name"]))) ?></div>
		</div>
		<div class="line-f3">
			<div class="input-simple-1 has-icon input-green bottom-20"><strong>Required Field</strong><em>Email*</em><i class="fas fa-envelope"></i>
				<?= $form->emailFieldGroup($model, 'usr_email', array('label' => '', 'widgetOptions' => array('htmlOptions' => [ 'placeholder' => "Email (will be used for login) "]))) ?>
			</div>
		</div>
		<div class="line-f3">
			<div class="input-simple-1 has-icon input-blue bottom-30"><strong>Required Field</strong><em>Phone Number (incl. country code)</em><i class="fa fa-phone"></i>
				<div class="from-left bottom-30"><?php
					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model' => $model,
						'attribute' => 'usr_country_code',
						'useWithBootstrap' => true,
						"placeholder" => "Code",
						'fullWidth' => false,
						'htmlOptions' => array(
						),
						'defaultOptions' => array(
							'create' => false,
							'persist' => false,
							'selectOnTab' => true,
							'createOnBlur' => true,
							'dropdownParent' => 'body',
							'optgroupValueField' => 'id',
							'optgroupLabelField' => 'pcode',
							'optgroupField' => 'pcode',
							'openOnFocus' => true,
							'labelField' => 'pcode',
							'valueField' => 'pcode',
							'searchField' => 'name',
							//   'sortField' => 'js:[{field:"order",direction:"asc"}]',
							'closeAfterSelect' => true,
							'addPrecedence' => false,
							'onInitialize' => "js:function(){
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
							'render' => "js:{
										 option: function(item, escape){                      
										 return '<div><span class=\"\">' + escape(item.name) +'</span></div>';
									},
									option_create: function(data, escape){                
									return '<div>' +'<span class=\"\">' + escape(data.pcode) + '</span></div>';
									}}",
						),));
					?></div>
				<div class="from-right bottom-30">
					<br>
					<?= $form->textFieldGroup($model, 'usr_mobile', array('label' => '', 'widgetOptions' => array('htmlOptions' => [ 'placeholder' => "Mobile No.(will be used for verification)"]))) ?></div>
			</div>
		</div>
		<div class="line-f3">
			<div class="input-simple-1 has-icon input-green bottom-20"><strong>Required Field</strong><em>Password</em><i class="fas fa-lock"></i>
			<?= $form->passwordFieldGroup($model, 'new_password', array('label' => '', 'widgetOptions' => array('htmlOptions' => [ 'placeholder' => "Password"]))) ?>
			</div>
		</div>
		<div class="line-f3">
			<div class="input-simple-1 has-icon input-green bottom-20"><strong>Required Field</strong><em>Repeat Password</em><i class="fas fa-lock"></i>
				<?= $form->passwordFieldGroup($model, 'repeat_password', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Repeat Password"]))) ?>
			</div>
		</div>
		<div class="line-f3">
			<div class="input-simple-1 has-icon input-green bottom-20"><em>Referral Code</em><?
				$cookieReferredCode = Yii::app()->request->cookies['gozo_referred_code']->value;
				if ($model->usr_referred_code != '') {
					$model->usr_referred_code = $model->usr_referred_code;
				} else if ($cookieReferredCode != '') {
					$model->usr_referred_code = $cookieReferredCode;
				}
				?>
                <?= $form->textFieldGroup($model, 'usr_referred_code', array('label' => '', 'widgetOptions' => array('htmlOptions' => [ 'placeholder' => "Refferal Code"]))) ?>
                        </div>
		<div class="line-f3 text-center mb20">
			<?= CHtml::submitButton("Create Account", ['class' => "uppercase btn-orange shadow-medium", 'tabindex' => "4"]); ?>
		</div>
		<?php $this->endWidget(); ?>
	
</div>
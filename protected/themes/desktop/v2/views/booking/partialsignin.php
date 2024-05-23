
<div class="row">
    <div class="col-12 mt0" id="loginsection">

        <?php
        $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id' => 'plogin-form1', 'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'errorCssClass' => 'has-error',
                'afterValidate' => 'js:function(form,data,hasError){
                        if(!hasError){             
                            $.ajax({
                                "type":"POST",
                                "dataType":"json",
                                "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('users/partialsignin')) . '",
                                "data":form.serialize(),
                                "success":function(data1){
									if(!$.isEmptyObject(data1) && data1.success==true){
									var userinfo = JSON.parse(data1.userdata);
                                    $userid = data1.id;				   									
									$jsLogin.fillUserform2(userinfo);
									$jsLogin.fillUserform13(userinfo);
									window.refreshNavbar(data1);
									}
									else{
										settings=form.data(\'settings\');
										var data = data1.data;
										$.each (settings.attributes, function (i) {
										  $.fn.yiiactiveform.updateInput (settings.attributes[i], data, form);
										});
										$.fn.yiiactiveform.updateSummary(form, data1);
									}
								},
							});
						}
					}'
            ),
            // Please note: When you enable ajax validatio n, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // See class documentation of CActiveForm for details on this,
            // you need to use the performAjaxValidation()-method described there.
            'enableAjaxValidation' => false,
            'errorMessageCssClass' => 'help-block',
            'action' => Yii::app()->createUrl('users/partialsignin'),
            'htmlOptions' => array(
                'class' => 'form-horizontal',
            ),
        ));
        /* @var $form TbActiveForm */
        ?>

        <div class="row">
            <div class="col-12">     
                <?php echo CHtml::errorSummary($model); ?>
            </div>
        </div> 
        <? if ($isFlexxi) { ?>
            <div class="row">
                <!--<div class="col-12 mb20 connect-fb">
                    <a href="#"><span class="text-uppercase" onclick="$jsLogin.openFbDialog(<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook', 'isFlexxi' => true)); ?>);"><i class="fab fa-facebook pr10 mr10"></i> Connect with Facebook</span></a>
                </div>-->
            </div>
        <? } else { ?>
            <div class="row">
				<div class="col-12 mt10 mb20 font-20 text-center">OR</div>
                <div class="col-12 text-left">Email Address<span class="red-color">*</span></div>
                <div class="col-12 mb20">                        
                    <?= $form->emailFieldGroup($model, 'usr_email', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['required' => TRUE, 'placeholder' => "Enter email"]), 'groupOptions' => ['class' => 'm0'])) ?>   
                </div>
                <div class="col-12 text-left">Password<span class="red-color">*</span></div>
                <div class="col-12">                        
                    <?= $form->passwordFieldGroup($model, 'usr_password', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['required' => TRUE, 'placeholder' => "**********"]), 'groupOptions' => ['class' => 'm0'])) ?>   
                </div> 
                <div class="col-12">&nbsp;</div>
                <div class="col-12">
                            <input class="btn text-uppercase gradient-green-blue font-14 pt10 pb10 pl30 pr30 border-none mt15" type="submit" name="signin" value="Sign In"/>
<!--                        <div class="col-6">
                            <a class="btn next3-btn text-uppercase col-xs-12" onclick="$jsLogin.callSignupbox('<?=Yii::app()->createUrl('users/partialsignup', ['callback' => 'refreshNavbar(data1)']) ?>')" role="button"><b>Sign Up</b></a>
                        </div>-->
                </div>
            </div>
        <? } ?>
        <?php $this->endWidget(); ?>         
    </div>

</div>

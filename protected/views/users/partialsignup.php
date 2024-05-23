<style>
    .selectize-input {
        min-width: 0px !important; 
        width: 100% !important;
    }
    .form-horizontal .form-group{
        margin: 0;
    }
    .yii-selectize.selectize-dropdown
    {z-index: 9999 !important;}
</style>

<?php
$callback	 = Yii::app()->request->getParam('callback', 'loadList');
$title		 = ($model->isNewRecord) ? "Add" : "Edit";
$js			 = "window.$callback;";


if (Yii::app()->request->isAjaxRequest)
{
	$cls = "";
}
else
{
	$cls = "col-lg-4 col-md-6 col-sm-8 col-xs-10";
}
?>
<div class="row">
    <div class="<?= $cls ?>" style="float: none; margin: auto">
        <div class="panel panel-white" id="signupDiv">
            <div class="panel-body pt0">
				<?php
				$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'psignup-form', 'enableClientValidation' => true,
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
                                if(!$.isEmptyObject(data1) && data1.success==true){
                                    $userid = data1.id;
                                    ' . $js . '
									 bootbox.hideAll();
                                    }
                                else{
                                    settings=form.data(\'settings\');
                                    var data = data1.data;
                                    $.each (settings.attributes, function (i) {
                                      $.fn.yiiactiveform.updateInput (settings.attributes[i], data, form);
                                    });
                                    $.fn.yiiactiveform.updateSummary(form, data1);
                                    }},
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
                <div class="col-xs-12">


                    <div class="row">

                        <div class="col-xs-12 col-sm-6">

							<?= $form->textFieldGroup($contactModel, 'ctt_first_name', array('label' => 'First Name', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Enter your name"]))) ?>
                        </div>
                        <div class="col-xs-12 col-sm-6">                            
							<?= $form->textFieldGroup($contactModel, 'ctt_last_name', array('label' => 'Last Name', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Enter your last name"]))) ?>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 ">                        
							<?= $form->emailFieldGroup($emailModel, 'eml_email_address', array('label' => 'Email', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Email (will be used for login) "]))) ?>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <label class="control-label" for="ContactPhone_phn_phone_no">Mobile (Optional)</label>
                            <div class="row">
                                <div class="col-xs-3 isd-input">								

									<?php //$form->textFieldGroup($model, 'usr_country_code', array('label' => '', 'widgetOptions' => array('htmlOptions' => [ 'placeholder' => ""]))) ?>
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
                          obj.setValue('{$phoneModel->phn_phone_country_code}');
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
                                <div class="col-xs-9">
									<?= $form->textFieldGroup($phoneModel, 'phn_phone_no', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Mobile No."]))) ?>
                                </div>
                            </div>
                        </div> 
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">                           
							<?= $form->passwordFieldGroup($model, 'new_password', array('label' => 'Password', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Password"]))) ?>
                        </div>
                        <div class="col-xs-12 col-sm-6">                          
							<?= $form->passwordFieldGroup($model, 'repeat_password', array('label' => 'Repeat Password', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Repeat Password"]))) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 text-center mt20">
                            <input class="btn btn-primary white-color" type="submit" value="REGISTER"  tabindex="4"/>
                        </div>
                    </div>
                </div>
				<?php $this->endWidget(); ?>
            </div>

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

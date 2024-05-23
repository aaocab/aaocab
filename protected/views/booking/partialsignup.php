<style>
    .selectize-input {
        min-width: 0px !important; 
        width: 100% !important;
    }

    .yii-selectize.selectize-dropdown
    {z-index: 9999 !important;}
</style>
<?php
?>
<div class="row">
    <div class="col-xs-12">

        <?php
        $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id' => 'psignup-form', 'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'errorCssClass' => 'has-error',
                'afterValidate' => 'js:function(form,data,hasError){
                        if(!hasError){             
                            $.ajax({
                                "type":"POST",
                                "dataType":"json",
                                "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('users/partialsignup')) . '",
                                "data":form.serialize(),
                                "success":function(data1){
                                if(!$.isEmptyObject(data1) && data1.success==true){
                                    $userid = data1.id;
                                    window.refreshUserdata();
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
            'enableAjaxValidation' => false,
            'errorMessageCssClass' => 'help-block',
            'action' => Yii::app()->createUrl('users/partialsignup'),
            'htmlOptions' => array(
                'class' => 'form-horizontal',
            ),
        ));
        /* @var $form TbActiveForm */
        ?>
        <?= $form->hiddenField($model, 'usr_acct_type', ['value' => 0]) ?>


        <div class="row">

            <div class="col-xs-12 col-sm-6">

                <?= $form->textFieldGroup($model, 'usr_name', array('label' => 'First Name<span class="red-color">*</span>', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Enter first name"]))) ?>
            </div>
            <div class="col-xs-12 col-sm-6">                            
                <?= $form->textFieldGroup($model, 'usr_lname', array('label' => 'Last Name<span class="red-color">*</span>', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Enter last name"]))) ?>
            </div>

        </div>
        <div class="row">
            <div class="col-xs-12">                        
                <?= $form->emailFieldGroup($model, 'usr_email', array('label' => 'Email Address<span class="red-color">*</span>', 'widgetOptions' => array('htmlOptions' => [ 'placeholder' => "Enter email address"]))) ?>
            </div>
            <div class="col-xs-12">
                <label class="control-label" for="Users_usr_mobile">Mobile<span class="red-color">*</span></label>
                <div class="row">
                    <div class="col-xs-3 isd-input">
                        <?php //$form->textFieldGroup($model, 'usr_country_code', array('label' => '', 'widgetOptions' => array('htmlOptions' => [ 'placeholder' => ""])))  ?>
                        <?php
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
                          obj.setValue('{$model->usr_country_code}');
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
                        ?>
                    </div>
                    <div class="col-xs-9">
                        <?= $form->textFieldGroup($model, 'usr_mobile', array('label' => '', 'widgetOptions' => array('htmlOptions' => [ 'placeholder' => "Mobile No."]))) ?>
                    </div>
                </div>
            </div> 
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6">                           
                <?= $form->passwordFieldGroup($model, 'new_password', array('label' => 'Password<span class="red-color">*</span>', 'widgetOptions' => array('htmlOptions' => [ 'placeholder' => "Password"]))) ?>
            </div>
            <div class="col-xs-12 col-sm-6">                          
                <?= $form->passwordFieldGroup($model, 'repeat_password', array('label' => 'Repeat Password<span class="red-color">*</span>', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Repeat Password"]))) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 mt20">
                <input class="btn btn-success pl30 pr30 text-uppercase" type="submit" value="REGISTER"  tabindex="4"/>
            </div>
        </div>

        <?php $this->endWidget(); ?>
    </div>

</div>


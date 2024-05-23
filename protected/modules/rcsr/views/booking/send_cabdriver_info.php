<style type="text/css">
    .dlgComments .dijitDialogPaneContent {
        overflow: auto;
    }
    div .comments {
        border-bottom:1px #333 solid;
        padding:3px;
        line-height: 14px;
        font-weight: normal;
    }
    div .comments .comment {
        padding:3px;
    }
    div .comments .footer {
        padding:2px 5px;
        color: #888;
        text-align: right;
        font-style: italic;
        font-size: 0.85em;
        height: auto;
        width: auto;
    }
    .remarkbox {
        width: 100%;
        padding: 3px;
        overflow: auto;
        line-height: 14px;
        font: normal arial;
        border-radius: 5px;
        -moz-border-radius: 5px;
        border: 1px #aaa solid;
    }
    .selectize-input{ min-width: 80px!important;}
    .selectize-input{ min-width: 80px!important;}
</style>
<style type="text/css">
    .form-group {
        margin-bottom: 7px;
        margin-top: 15px;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    .form-horizontal .checkbox-inline {
        padding-top: 0;
    }
    #Booking_chk_user_msg {
        margin-left: 10px
    }
    .dlgComments .dijitDialogPaneContent {
        overflow: auto;
    }
</style>
<script type="text/javascript">
    $(document).ready(function ()
    {
        $('.bootbox').removeAttr('tabindex');
    });
</script>
<?php
$adminlist = Admins::model()->findNameList();
$statuslist = Booking::model()->getActiveBookingStatus();
$date = date('Y-m-d H:i:s');
?>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-xs-12">
            <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'send-cabdriver-form', 'enableClientValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                        'errorCssClass' => 'has-error',
                        'afterValidate' => 'js:function(form,data,hasError)
                         {
                            if(!hasError)
                            {
                                $.ajax({
                                "type":"POST",
                                "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('rcsr/booking/sendcabdriverinfo', ['booking_id' => $model->bkg_id])) . '",
                                "data":form.serialize(),
                                    "dataType": "json",
                                    "success":function(data1)
                                    {
                                        if(data1.success)
                                        {
                                            cabdriverInfoSent(data1.oldStatus);
                                        }
                                        else
                                        {
                                             var errors = data1.errors;
                                            settings=form.data(\'settings\');
                                            $.each (settings.attributes, function (i) 
                                            {
                                                $.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
                                            });
                                            $.fn.yiiactiveform.updateSummary(form, errors);
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
                        'class' => 'form-horizontal'
                    ),
                ));
                /* @var $form TbActiveForm */
                ?>
                    <div class="panel panel-default panel-border">
                        <div class="panel-body pt0">
                            <div class="row mb5">
                                <label for="inputEmail" class="control-label col-sm-3 checkbox-inline"><?= $form->checkboxGroup($model, 'bkg_user_email_chkbox', array('label'=>'','widgetOptions' => array('htmlOptions' => []))) ?><b>Customer Email </b></label>
                                <div class="col-sm-9">
                                    <?= $form->textFieldGroup($model, 'bkg_user_email', array('label' => '', 'widgetOptions' => array('htmlOptions' => array()))) ?>
                                </div>
                            </div>
                            <div class="row mb5">
                                <label for="inputEmail" class="control-label col-sm-3 checkbox-inline"><?= $form->checkboxGroup($model, 'bkg_contact_no_chkbox', array('label'=>'','widgetOptions' => array('htmlOptions' => []))) ?><b>Customer Phone </b></label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-xs-3 mt15">
                                            <?php
                                            $this->widget('ext.yii-selectize.YiiSelectize', array(
                                                'model' => $model,
                                                'attribute' => 'bkg_country_code',
                                                'useWithBootstrap' => true,
                                                "placeholder" => "Code",
                                                'fullWidth' => false,
                                                'htmlOptions' => array(
                                                    'style' => 'width: 25%',
                                                ),
                                                'defaultOptions' => array(
                                                    'create' => false,
                                                    'persist' => true,
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
                                        $('#Booking_bkg_country_code')[0].selectize.setValue({$model->bkg_country_code});
                                        },                    
                                        error:function(){
                                        callback();
                                        }});
                                        });
                                        }",
                                                    'render' => "js:{
                                        option: function(item, escape){  
                                        var class1 = (item.pcode == 91) ? '':'pl20';
                                        return '<div><span class=\"\">' + escape(item.name) +'</span></div>';

                                        },
                                                    option_create: function(data, escape){
                                      $('#countrycode').val(data.pcode);

                                                     return '<div>' +'<span class=\"\">' + escape(data.pcode) + '</span></div>';
                                                                                          }
                                                            }",
                                                ),
                                            ));
                                            ?>
                                        </div>
                                        <div class="col-xs-9">
                                            <?= $form->textFieldGroup($model, 'bkg_contact_no', array('label' => '', 'widgetOptions' => array('htmlOptions' => array()))) ?> 
                                        </div>
                                        
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row mb5">
                                <div class="col-sm-12"><?= $form->error($model, 'bkg_contact_no_chkbox') ?></div> 
                            </div>
                            <div class="row mb5">
                                <div class="Submit-button" style="margin-top: 5px;">
                                    <?php echo CHtml::submitButton('Send details to customer', array('class' => 'btn btn-primary')); ?>
                                </div>
                            </div>  
                        </div>
                    </div>
                <?php $this->endWidget(); ?>
        </div>
    </div>
</div>
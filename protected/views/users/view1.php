<style>



    #Users_usr_email , #Users_usr_gender{
        border: 1px #434A54 solid;
    }
</style>
<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        /* display: none; <- Crashes Chrome on hover */
        -webkit-appearance: none;
        margin:0;/* <-- Apparently some margin are still there even though it's hidden */
    }

    .selectize-input {
        min-width: 0px !important; 
        width: 50% !important; 

        color: #000 !important;
    }
</style>
<?
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-typeahead/typeahead.bundle.min.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
$cityList = ['' => 'Select City'] + Cities::model()->getAllCityList();
//var_dump($model->attributes);
?>
<div class="row">
    <div class="col-xs-12 col-sm-10 col-md-9 col-lg-8">
        <div class="row">
            <?
            $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                'id' => 'view-form', 'enableClientValidation' => true,
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

            <div class="panel panel-default" >

                <div class="panel-body"> 
                    <?php echo CHtml::errorSummary($model); ?>
                    <div class="row">  
                        <p class="page-header" style="margin-top: 5px">Personal Information</p></div>
                    <div class="row">
                        <div class="col-xs-12">
                            <?php if (Yii::app()->user->hasFlash('success')): ?>
                                <div class="alert alert-success" style="padding: 10px">
                                    <?php echo Yii::app()->user->getFlash('success'); ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-offset-2 col-xs-8">
                            <?= $form->textFieldGroup($model, 'usr_name', array('label' => '')) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-offset-2 col-xs-8">
                            <?= $form->textFieldGroup($model, 'usr_email', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('readOnly' => true)))) ?>
                        </div>
                    </div>
                    <div class="row"><div class="col-xs-offset-2 col-xs-8">
                            <?= $form->dropDownListGroup($model, 'usr_gender', array('label' => '', 'widgetOptions' => array('data' => array('' => 'Select Gender', '1' => 'Male', '2' => 'Female')))) ?>
                        </div>
                    </div>


                    <div class="row ">
                        <div class="col-xs-offset-2 col-xs-2 pr0">
                            <?php // $form->textFieldGroup($model, 'usr_country_code', array('label' => '', 'widgetOptions' => array('htmlOptions' => [ 'placeholder' => ""]))) ?>

                            <?php
                            $this->widget('ext.yii-selectize.YiiSelectize', array(
                                'model' => $model,
                                'attribute' => 'usr_country_code',
                                'useWithBootstrap' => true,
                                "placeholder" => "Code",
                                'fullWidth' => false,
                                'htmlOptions' => array(
                                //'style' => 'width: 5%',
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
                                    'searchField' => 'pcode',
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
                          $('#Users_usr_country_code')[0].selectize.setValue({$model->usr_country_code});
                     },                    
                     error:function(){
                         callback();
                     }});
                            });

                           }",
                                    'render' => "js:{
                         option: function(item, escape){  
                         var class1 = (item.pcode == 91) ? '':'pl20';
                           return '<div><span class=\"' + class1 + '\">' + escape(item.pcode) +'('+escape(item.code)+ ')</span></div>';
                          
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
                        <div class="col-xs-6">
                            <?= $form->textFieldGroup($model, 'usr_mobile', array('label' => '', 'widgetOptions' => array('htmlOptions' => [ 'placeholder' => "Mobile No.(will be used for verification)"]))) ?>
                        </div>
                    </div>

                    <div class="row hide">
                        <div class="col-xs-offset-2 col-xs-8">
                            <?= $form->textFieldGroup($model, 'usr_mobile', array('label' => '')) ?>
                        </div>
                    </div>
                    <div class="row"><div class="col-xs-offset-2 col-xs-8">
                            <?= $form->textFieldGroup($model, 'usr_address1', array('label' => '')) ?>
                        </div>
                    </div>
                    <div class="row"><div class="col-xs-offset-2 col-xs-8">
                            <?= $form->textFieldGroup($model, 'usr_address2', array('label' => '')) ?>
                        </div>
                    </div>
                    <div class="row"><div class="col-xs-offset-2 col-xs-8">
                            <?= $form->textFieldGroup($model, 'usr_address3', array('label' => '')) ?>
                        </div>
                    </div>
                    <div class="row"><div class="col-xs-offset-2 col-xs-8">
                            <?= $form->textFieldGroup($model, 'usr_zip', array('label' => '')) ?>
                        </div>
                    </div>

                    <div class="row"><div class="col-xs-offset-2 col-xs-8">
                            <?php
                            $criteria = new CDbCriteria();
                            $criteria->order = 'country_order DESC';
                            $countryList = CHtml::listData(Countries::model()->findAll($criteria), 'id', 'country_name');
                            $this->widget('ext.yii-selectize.YiiSelectize', array(
                                'model' => $model,
                                'attribute' => 'usr_country',
                                'data' => $countryList,
                                'useWithBootstrap' => true,
                                'placeholder' => 'Country',
                                'htmlOptions' => array('onchange' => "changeCountry(this)", 'style' => 'width: 200%',),
                                'defaultOptions' => array(
                                    'create' => false,
                                    'persist' => false,
                                    'createOnBlur' => true,
                                    'closeAfterSelect' => true,
                                    'addPrecedence' => true,
                                ),
                            ));
                            ?>



                            <input type="hidden" id="countryname" name="countryname" value="<?= $model->usr_country; ?>">
                        </div>
                    </div>
                    <div class="row" id="statetextdiv"><div class="col-xs-offset-2 col-xs-8">


                            <?= $form->textFieldGroup($model, 'usr_state_text', array('label' => '')) ?>


                        </div></div>
                    <div class="row" id="statediv"><div class="col-xs-offset-2 col-xs-8">

                            <?php
                            $this->widget('ext.yii-selectize.YiiSelectize', array(
                                'model' => $model,
                                'attribute' => 'usr_state',
                                'useWithBootstrap' => true,
                                "placeholder" => "State",
                                'fullWidth' => false,
                                'htmlOptions' => array(
                                    'style' => 'width: 200%',
                                ),
                                'defaultOptions' => array(
                                    'create' => false,
                                    'persist' => false,
                                    'selectOnTab' => true,
                                    'createOnBlur' => true,
                                    'dropdownParent' => 'body',
                                    'optgroupValueField' => 'id',
                                    'optgroupLabelField' => 'id',
                                    'optgroupField' => 'id',
                                    'openOnFocus' => true,
                                    'labelField' => 'text',
                                    'valueField' => 'id',
                                    'searchField' => 'text',
                                    'closeAfterSelect' => true,
                                    'addPrecedence' => false,
                                    'onInitialize' => "js:function(){
                            this.load(function(callback){
                            var obj=this;    
                            
                             xhr=$.ajax({
                     url:'" . CHtml::normalizeUrl(Yii::app()->createUrl('users/state', ['id' => $model->usr_country])) . "',
                     dataType:'json',                  
                     success:function(results){
                         obj.enable();
                         callback(results.data);
                          $('#Users_usr_state')[0].selectize.setValue({$model->usr_state});
                     },                    
                     error:function(){
                         callback();
                       
                     }});
                            });

                           }",
                                    'render' => "js:{
                         option: function(item, escape){  
                       
                           return '<div><span class=\"\">' + escape(item.text) +'</span></div>';
                          
                    },
				option_create: function(data, escape){
                                $('#countryname').val(escape(data.id));
				 return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
								      }
					}",
                                ),
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="row"><div class="col-xs-offset-2 col-xs-8"></div></div>
                    <div class="row"><div class="col-xs-offset-2 col-xs-8">

                            <?= $form->textFieldGroup($model, 'usr_city', array('label' => '')) ?>

                        </div>
                    </div>
                    <div class="row"><div class="col-xs-offset-2 col-xs-8"></div></div>
                    <div class="panel-footer text-center mt20">
                        <input class="btn btn-primary" type="submit" name="sub" value="Submit" />
                    </div>
                </div>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $state = '<?= $model->usr_state ?>';
        $country = '<?= $model->usr_country ?>';
        if ($country == 99)
        {
            $('#statetextdiv').hide();
            $('#statediv').show();
        } else {
            $('#statetextdiv').show();
            $('#statediv').hide();
        }


        changestate($('#countryname').val());




        $(window).on('beforeunload', function() {
            $(window).scrollTop(0);
        });
        $('#<?= CHtml::activeId($model, 'usr_mobile') ?>').mask("9999999999");
        $('#<?= CHtml::activeId($model, 'usr_zip') ?>').mask("999999");
        //        $('#<?= CHtml::activeId($model, 'usr_alternative_phone') ?>').mask("(999) 999-9999");
        //        $("#mobileVerify").bind('click', mobileVerifyHandler);
        //        $("#emvrify").bind('click', emalVrifyHandler);
        //        $("#<?= CHtml::activeId($model, 'email') ?>").bind('change', emailVerifyHandler);



        $("#view-form").submit(function(event) {



        });

        $("#Users_usr_state1").change(function() {

            var stid = $("#Users_usr_state").val();
            var href2 = '<?= Yii::app()->createUrl("users/cityfromstate"); ?>';
            $.ajax({
                "url": href2,
                "type": "GET",
                "dataType": "json",
                "data": {"id": stid},
                "success": function(data1) {

                    $data2 = data1;
                    var placeholder = $('#<?= CHtml::activeId($model, "usr_city") ?>').attr('placeholder');
                    $('#<?= CHtml::activeId($model, "usr_city") ?>').select2({data: $data2, placeholder: placeholder});
                }
            });
        });
    });
    function changestate(selectizeControl)
    {

//        var href2 = '<?= Yii::app()->createUrl("users/countrytostate"); ?>';
//        $.ajax({
//            "url": href2,
//            "type": "GET",
//            "dataType": "json",
//            "data": {"countryid": selectizeControl},
//            "success": function(data1) {
//
//
//                $data2 = data1;
//                var placeholder = $('#<?= CHtml::activeId($model, "usr_state") ?>').attr('placeholder');
//                $('#<?= CHtml::activeId($model, "usr_state") ?>').select2({data: $data2, placeholder: placeholder});
//            }
//        });
    }

    function changeCountry(obj)
    {
        var selectize = $('#Users_usr_state')[0].selectize;
        alert(obj.value);
        var country = obj.value;
        if (country != 99)
        {
            $('#statetextdiv').show();
            $('#statediv').hide();
        } else {

            $('#statetextdiv').hide();
            $('#statediv').show();

            var href2 = '<?= Yii::app()->createUrl("users/countrytostate"); ?>';
            $.ajax({
                "url": href2,
                "type": "POST",
                "dataType": "json",
                "data": {"countryid": country},
                "success": function(data1) {
                    $data2 = data1;

                    // var placeholder = $('#<?= CHtml::activeId($model, "usr_state") ?>').attr('placeholder');
                    // $('#<?= CHtml::activeId($model, "usr_state") ?>').selectize({data: $data2, placeholder: placeholder});

                    selectize.clearOptions();
                    selectize.addOption(data1);
                    selectize.refreshOptions(false);
                    //$('select').selectize(options);
                }
            });
        }
    }
</script>



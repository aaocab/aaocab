<style>
    .table_new table{ width: 99%;};
</style>
<?
$selectizeOptions = ['create' => false, 'persist' => true, 'selectOnTab' => true, 'createOnBlur' => true, 'dropdownParent' => 'body',
    'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'sortField' => 'text', 'optgroupField' => 'id', 'openOnFocus' => true, 'preload' => false,
    'labelField' => 'text', 'valueField' => 'id', 'searchField' => 'text', 'closeAfterSelect' => true,
    'addPrecedence' => false,];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
?>
    <div class="row">
        <div id="AgentOuterDiv">
            <div class="col-xs-12">
                <div class="h4" style="color: #de6a1e;"><span id="AgentOuterDivText"></span></div>
            </div>
        </div>  
        <?
        if (Yii::app()->user->hasFlash('success')) {
            $showdiv = 'none';
        } else {
            $showdiv = 'block';
        }
        ?>
        <? if (Yii::app()->controller->action->id == 'corpjoin') { ?>
            <!--<h1 class="m0 mb10 pb5 border-bottom weight400 text-uppercase">Join Gozo's Business Travel program</h1>   --> 
        <? } else { ?>
            <!--<h1 class="m0 mb10 pb5 border-bottom weight400 text-uppercase">Join Gozo's Travel Partner family</h1>-->     
        <? } ?>
        <div class="col-xs-12 mb20 mt20" style="color:#008a00;text-align: center">
            <b><?php echo Yii::app()->user->getFlash('success'); ?></b>
        </div>
        <div class="col-xs-12 mb20" style="color:#F00;text-align: center">
            <?php echo Yii::app()->user->getFlash('error'); ?>
        </div>
        <div id="AgentInnerDiv" style="display: <?= $showdiv ?>">
            <div class="panel panel-white panel-border box-shadow-none"> 
                <div class="panel-body">
                    <?php
                    $form = $this->beginWidget('booster.widgets.TbActiveForm', array('id' => 'agent-form', 'enableClientValidation' => TRUE,
                        'clientOptions' => array(
                            'validateOnSubmit' => true,
                            'errorCssClass' => 'has-error'
                        ),
                        'enableAjaxValidation' => false,
                        'errorMessageCssClass' => 'help-block',
                        'htmlOptions' => array(
                            'class' => 'form-horizontal', 'enctype' => 'multipart/form-data'
                        ),
                    ));
                    /* @var $form TbActiveForm */
                    ?>
                    <div class="col-xs-12 col-sm-6">
                        <?if($passwordEmailSent){?><div class="row text-danger">You have already registered. We have sent you your username and password once again.</div><?}?>
                        <div class="row main_time border-blueline p20">
                                <div class="col-xs-12">
                                    <? if (Yii::app()->controller->action->id == 'corpjoin') { ?>
                                        <?= $form->textFieldGroup($model, 'agt_company', array('label' => "Business Entity Name", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Business Entity Name')))) ?> 
                                    <? } ?>
                                </div>
                                <div class="col-xs-12">
                                    <?= $form->textFieldGroup($model, 'agt_fname', array('label' => "First name", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Your First Name')))) ?> 
                                </div>
                                <div class="col-xs-12">
                                    <?= $form->textFieldGroup($model, 'agt_lname', array('label' => "Last name", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Your Last Name')))) ?> 
                                </div>
                                <div class="col-xs-12">
                                    <?= $form->textFieldGroup($model, 'agt_email', array('label' => "Email*", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter primary email')))) ?>
                                </div>
                                <div class="col-xs-12">
                                    <div class="row">
                                        <label class="control-label pl0">Phone*</label>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-2 isd-input pl0">
                                            <?php
                                            $model->agt_phone_country_code = ($model->agt_phone_country_code == '') ? '91' : $model->agt_phone_country_code;

                                            $this->widget('ext.yii-selectize.YiiSelectize', array(
                                                'model' => $model,
                                                'attribute' => 'agt_phone_country_code',
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
                                                                    obj.setValue('{$model->agt_phone_country_code}');
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
                                                            }
                                                            }",
                                                ),
                                            ));
                                            ?>
                                            <span class="has-error"><? echo $form->error($model, 'agt_phone_country_code'); ?></span>
                                        </div>  
                                        <div class="col-xs-9 col-xs-offset-1 pl0">
                                            <?= $form->textFieldGroup($model, 'agt_phone', array('label' => "", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter primary phone number', 'max' => 10, 'min' => 10)))) ?>
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-xs-12 ">
                                    <div class="form-group">
                                    <label for="city"><b>Select City in which your business is located</b></label>
                                    <?php
                                    $this->widget('ext.yii-selectize.YiiSelectize', array(
                                        'model' => $model,
                                        'attribute' => 'agt_city',
                                        'useWithBootstrap' => true,
                                        "placeholder" => "Enter Your City",
                                        'fullWidth' => true,
                                        'htmlOptions' => array('width' => '50%', ''
                                        ),
                                        'defaultOptions' => $selectizeOptions + array(
                                    'onInitialize' => "js:function(){
                                        populateSource(this, '{$model->agt_city}');
                                    }",
                                    'load' => "js:function(query, callback){
                                            loadSource(query, callback);
                                        }",
                                    'render' => "js:{
                                        option: function(item, escape){
                                        return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                                        },
                                        option_create: function(data, escape){
                                        return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                        }
                                        }",
                                        ),
                                    ));
                                    ?>
                                    <?php
//                                    $this->widget('booster.widgets.TbSelect2', array(
//                                        'model' => $model,
//                                        'attribute' => 'agt_city',
//                                        'val' => $model->agt_city,
//                                        'asDropDownList' => FALSE,
//                                        'options' => array('data' => new CJavaScriptExpression(VehicleTypes::model()->getJSON(Cities::model()->getAllCityList(true))), 'allowClear' => true),
//                                        'htmlOptions' => array('style' => 'width:105%;', 'placeholder' => 'Enter Your City')
//                                    ));
                                    ?>
									<span class="has-error"><? echo $form->error($model, 'agt_city'); ?></span>
                                </div></div>
                                <div class="col-xs-12">
                                    <?= $form->checkboxGroup($model, 'agt_tnc', array("label" => 'I Agree to the <a href="/terms/channelpartner" target="_block">Terms and Conditions Channel Partner</a>')); ?>    
                                </div>
                                <div class="Submit-button col-xs-12 text-center" id="vendorSubmitDiv">
                                    <?= CHtml::submitButton('Create Account', ['class' => "btn next-btn", 'name' => 'submit']) ?>
                                </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 pl30">
                        <div><b>Gozo Travel partner program</b></div>
                            <div><p>Travel Agents, Hotels travel desks, Shopkeepers... Offer convenience to your customers and make money. Its simple! Join now and instantly start creating bookings for your customers</p></div>
                            <div><b>Benefits of joining Gozo's Travel network…</b></div>
                            <div>
                                <ul class="pl15">
                                    <li> Get direct access to India's largest network of intercity AC Taxi</li>
                                    <li> Offer convenience of outstation taxi bookings to your customers</li>
                                    <li> Buy bookings at very low pricing - and sell them to create profits</li>
                                    <li> Easily create pre-paid or post-paid bookings using our kiosk</li>
                                    <li> Get 24x7 support from our travel desk & service center</li>
                                    <li> Just like our other partners you can generate business of Rs. 50,000 to Rs. 1Lac every month</li>                                    
                                </ul>
                            </div>
                            
                        <h2 class="mt0"><span style="color:#096dc4">Any questions? Contact our Travel Partner team</span></h2>
                        <div class="row main_time border-blueline pl20 pr20 border-gray mt0 pt0" style="margin: 0; font-size: 16px; color: #000;">
							<div class="col-xs-12" style="">
                            <p><figure><img src="<?= Yii::app()->baseUrl ?>/images/india-flag.png" alt="INDIA" class="mr10 mb5">(+91) 90518 77000 (24x7)</figure></p>
                            <p><i class="fa fa-envelope mr10 mb10" style="font-size:18px;"></i> <a href="mailto:channel@aaocab.in" style="color:#000;">channel@aaocab.in</a></p>
                        </div>
						</div>
                        <br>
                        <h2 class="mt0"><span style="color:#096dc4">DCO's and Taxi Operators...<a href="http://www.aaocab.com/vendor/join">Attach your taxi here</a></span></h2>
                          
                    </div>
                    <div id="loading"></div>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
        <? if ($verifyEmail == 1) { ?>
            <div class="col-xs-12">
                <div class="panel panel-white" >
                    <div class="panel-body">
                        <div class="col-xs-12 mb5">
                            <h1 class="text-center">Verification Code</h1>
                            <p class="text-center mb0 pb0" style="line-height: 28px; ">  Enter 6 digit verification code send to register email address </p>
                            <p class="text-center mt0 pt0" style="color: #182db2"><b><?= $model->agt_email ?></b></p>
                        </div>
                        <div class="col-xs-12 text-center mb5 mt30">
                            <input type="hidden" name="emailtoverify" id="emailtoverify" value="<?= $emailToVerify ?>">
                        </div>
                        <div class="col-xs-12 col-md-3 text-center mb10 float-none marginauto">
                            <input type="text" name="emailvercode" id="emailvercode" required="true" class="form-control"  style="height:50px;">
                        </div>
                        <div class="col-xs-12 text-center mb20">
                            <button class="btn btn-info btn-lg pl50 pr50" style="width: 300px;" onclick="verifyEmail();">Enter Code</button>
                        </div> 
                        <!--                        <div class="text-center">
                                                    <a class="pr50" style="color: #1050b7">Resend Code</a>
                                                </div>-->
                    </div>
                </div>
            </div>
        <? } ?>
    </div>



<script type="text/javascript">
    $(document).ready(function () {
        $('#phone').mask('9999999999');
        $('#AgentOuterDiv').hide();
    });

    function validateCheckHandlerss() {
        if ($('#formId').val() != "") {
            var pattern = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/
            var retVal = pattern.test($('#Agents_agt_email').val());
            if (retVal == false)
            {
                $('#errId').html("The email address you have entered is invalid.");
                return false;
            } else
            {
                $('#errId').html("");
                return true;
            }
        }
        return true;

    }

    function opentns() {
        $href = '<?= Yii::app()->createUrl('index/termsagent') ?>';
        jQuery.ajax({type: 'GET', url: $href,
            success: function (data) {
                box = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'large',
                    onEscape: function () {
                        box.modal('hide');
                    }
                });
            }
        });
    }

    function verifyEmail() {
        var code = $('#emailvercode').val();
        var email = $('#emailtoverify').val();
        href = '<?= Yii::app()->createUrl('index/verifyemail') ?>';
        jQuery.ajax({type: 'GET', data: {"code": code, "email": email}, url: href, dataType: "json",
            success: function (data) {

                if (data.success) {
                    location.href = '<?= Yii::app()->createUrl('agent/index/index', ['emailverified' => 1]) ?>';
                } else {
                    alert("Invalid Verification Code");
                }

            }
        });
    }

    function loadSource(query, callback) {
        //	if (!query.length) return callback();
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',

            error: function () {
                callback();
            },
            success: function (res) {
                callback(res);
            }
        });
    }
    $sourceList = null;
    function populateSource(obj, cityId) {
        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>',
                    dataType: 'json',
                    data: {
                        city: cityId
                    },
                    //  async: false,
                    success: function (results) {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue('<?= $model->agt_city ?>');
                    },
                    error: function () {
                        callback();
                    }
                });
            } else {
                obj.enable();
                callback($sourceList);
                obj.setValue('<?= $model->agt_city ?>');
            }
        });
    }
</script>

<?
$selectizeOptions = ['create'		 => false, 'persist'		 => true, 'selectOnTab'		 => true, 'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
    'optgroupValueField'	 => 'id', 'optgroupLabelField'	 => 'text', 'sortField'		 => 'text', 'optgroupField'		 => 'id', 'openOnFocus'		 => true, 'preload'		 => false,
    'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
    'addPrecedence'		 => false,];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
?>

<div class="row m0">
    <div class="col-12">
	<div class="container mt30 mb50">
	    <div class="row">
		<div class="col-12"><h1 class="font-22 mb20"><b>Become or travel agent with Gozo. Join Gozo's travel partner family..</b></h1></div>
                <div class="col-sm-5" style="display: flex;">
		    <div class="bg-white-box p20">
			<div id="AgentOuterDiv">
			    <div class="col-12">
				<div class="h4" style="color: #de6a1e;"><span id="AgentOuterDivText"></span></div>
			    </div>
			</div>  
			<?
			if (Yii::app()->user->hasFlash('success'))
			{
			    $showdiv = 'none';
			}
			else
			{
			    $showdiv = 'block';
			}
			?>
			<?
			if (Yii::app()->controller->action->id == 'corpjoin')
			{
			    ?>
    			<!--<h1 class="m0 mb10 pb5 border-bottom weight400 text-uppercase">Join Gozo's Business Travel program</h1>   --> 
			    <?
			}
			else
			{
			    ?>
    			<!--<h1 class="m0 mb10 pb5 border-bottom weight400 text-uppercase">Join Gozo's Travel Partner family</h1>-->     
			<? } ?>
			<div class="col-12 mb20 mt20" style="color:#008a00;text-align: center">
			    <b><?php echo Yii::app()->user->getFlash('success'); ?></b>
			</div>
			<div class="col-12 mb20" style="color:#F00;text-align: center">
			<?php echo Yii::app()->user->getFlash('error'); ?>
			</div>
			<div id="AgentInnerDiv" style="display: <?= $showdiv ?>">
			    <?php
			    $form = $this->beginWidget('CActiveForm', array('id' => 'agent-form', 'enableClientValidation' => TRUE,
				'clientOptions'		 => array(
				    'validateOnSubmit'	 => true,
				    'errorCssClass'		 => 'has-error'
				),
				'enableAjaxValidation'	 => false,
				//'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'		 => array(
				    'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data'
				),
			    ));
			    /* @var $form CActiveForm */
			    ?>
			    <?
			    if ($passwordEmailSent)
			    {
				?><div class="row text-danger">You have already registered. We have sent you your username and password once again.</div><? } ?>
			    <div class="row">
				<div class="col-12">
				    <?
				    if (Yii::app()->controller->action->id == 'corpjoin')
				    {
					?>
					<?= $form->textField($model, 'agt_company',  array('placeholder' => 'Enter Business Entity Name','class' => "form-control")) ?> 
				    <? } ?>
				</div>
				<div class="col-12">
				   <div class="form-group">
                    <label class="control-label" for="Agents_agt_fname">First name</label>
				    <?= $form->textField($model, 'agt_fname', array('placeholder' => 'Your First Name','class' => "form-control")) ?> 
                    <?php echo $form->error($model, 'agt_fname',['class' => 'help-block error']); ?>
                   </div>
				</div>
				<div class="col-12">
				    <div class="form-group">
                    <label class="control-label" for="Agents_agt_lname">Last name</label>
				    <?= $form->textField($model, 'agt_lname', array('placeholder' => 'Your Last Name','class' => "form-control")) ?> 
                    <?php echo $form->error($model, 'agt_lname',['class' => 'help-block error']); ?>
                   </div>
				</div>
				<div class="col-12">
				  <div class="form-group">
                   <label class="control-label" for="Agents_agt_email">Email*</label>
				   <?= $form->textField($model, 'agt_email',array('placeholder' => 'Enter primary email','class' => "form-control")) ?>
                   <?php echo $form->error($model, 'agt_email',['class' => 'help-block error']); ?>
                  </div>
				</div>
				<div class="col-12">
				    <div class="form-group">
					<label class="control-label pl0">Phone*</label>

					<?php
					$model->agt_phone_country_code = ($model->agt_phone_country_code == '') ? '91' : $model->agt_phone_country_code;
					$this->widget('ext.intlphoneinput.IntlPhoneInput', array(
					    'model'			 => $model,
					    'attribute'		 => 'agt_phone',
					    'codeAttribute'		 => 'agt_phone_country_code',
					    'numberAttribute'	 => 'agt_phone',
					    'options'		 => array(// optional
						'separateDialCode'	 => true,
						'autoHideDialCode'	 => true,
						'initialCountry'	 => 'in'
					    ),
					    'htmlOptions'		 => ['class' => 'form-control', 'id' => 'fullContactNumber'],
					    'localisedCountryNames'	 => false, // other public properties
					));
					?>
					<span class="has-error"><? echo $form->error($model, 'agt_phone_country_code',['class' => 'help-block error']); ?></span>
					<span class="has-error"><?php echo $form->error($model, 'agt_phone',['class' => 'help-block error']); ?></span>
				    </div>
				</div> 
				<div class="col-12 ">
				    <div class="form-group">
					<label for="city"><b>Select City in which your business is located</b></label>
					<?php
					$this->widget('ext.yii-selectize.YiiSelectize', array(
					    'model'			 => $model,
					    'attribute'		 => 'agt_city',
					    'useWithBootstrap'	 => true,
					    "placeholder"		 => "Enter Your City",
					    'fullWidth'		 => false,
					    'htmlOptions'		 => array('width' => '50%', ''
					    ),
					    'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
                                        populateSource(this, '{$model->agt_city}');
                                    }",
					'load'		 => "js:function(query, callback){
                                            loadSource(query, callback);
                                        }",
					'render'	 => "js:{
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
					<span class="has-error"><? echo $form->error($model, 'agt_city',['class' => 'help-block error']); ?></span>
				    </div></div>
				<div class="col-12">
				   <div class="form-group">
                    <?= $form->checkBox($model, 'agt_tnc'); ?>    
			        I Agree to the <a href="/terms/channelpartner" target="_block">Terms and Conditions Channel Partner</a>
			        <?php echo $form->error($model, 'agt_tnc',['class' => 'help-block error']); ?>	    
                   </div>
				</div>
				<div class="Submit-button col-12" id="vendorSubmitDiv">
<?= CHtml::submitButton('Create Account', ['class' => "btn text-uppercase gradient-green-blue font-20 border-none", 'name' => 'submit']) ?>
				</div>
			    </div>
			</div>
		    </div></div>
                <div class="col-12 col-sm-7 pl30 ul-style-c" style="display: flex;">
                    <div class="bg-white-box p20">
		    <h2 class="font-16"><b>Gozo Travel partner program</b></h2>
		    <div><p>Travel Agents, Hotels travel desks, Shopkeepers... Offer convenience to your customers and make money. Its simple! Join now and instantly start creating bookings for your customers</p></div>
		    <h2 class="font-16"><b>Benefits of joining Gozo's Travel network…</b></h2>
		    <div>
			<ul class="pl15">
			    <li><i class="fas fa-check-circle mr10"></i>Get direct access to India's largest network of intercity AC Taxi</li>
			    <li><i class="fas fa-check-circle mr10"></i>Offer convenience of outstation taxi bookings to your customers</li>
			    <li><i class="fas fa-check-circle mr10"></i>Buy bookings at very low pricing - and sell them to create profits</li>
			    <li><i class="fas fa-check-circle mr10"></i>Easily create pre-paid or post-paid bookings using our kiosk</li>
			    <li><i class="fas fa-check-circle mr10"></i>Get 24x7 support from our travel desk & service center</li>
			    <li><i class="fas fa-check-circle mr10"></i>Just like our other partners you can generate business of Rs. 50,000 to Rs. 1Lac every month</li>                                    
			</ul>
		    </div>

		    <h2 class="mt0 font-24"><b>Any questions? Contact our Travel Partner team</b></h2>
		    <div class="row font-18">
			<div class="col-12">
			    <p class="hide"><img src="<?= Yii::app()->baseUrl ?>/images/india-flag.png" alt="INDIA" class="mr10 mb5">(+91) 90518 77000 (24x7)</p>
			    <p><i class="fa fa-envelope mr10 mb10 color-green"></i> <a href="mailto:channel@gozocabs.in">channel@gozocabs.in</a></p>
			</div>
		    </div>
<!--		    <div class="row">
                        <div class="col-12">
                            <p class="mb5 font-16"><b>Click the link below for YouTube Videos (Hindi Version)</b></p>
                            <div class="video-panel">
                            <ul>
                                <li><i class="fab fa-youtube mr5" style="color: #ff0202;"></i><a href="http://www.youtube.com/watch?v=3T12L7XWnyo&&list=PLtO3n8NwlGMQsJ_KDX9hyHOZqqlX0uo-5&&index=5" target="_blank">Attach your cab & upload your documents</a></li>
                                <li><i class="fab fa-youtube mr5" style="color: #ff0202;"></i><a href="http://www.youtube.com/watch?v=AfbwgIJN0H0&&list=PLtO3n8NwlGMQsJ_KDX9hyHOZqqlX0uo-5&&index=11" target="_blank">Vendor Registration and documents Upload</a></li>
                                <li><i class="fab fa-youtube mr5" style="color: #ff0202;"></i><a href="http://www.youtube.com/watch?v=4630FwpTMsE&&list=PLtO3n8NwlGMQsJ_KDX9hyHOZqqlX0uo-5&&index=33" target="_blank">How to Add CAB, Upload DOCs, Sign the LOU</a></li>
                                <li><i class="fab fa-youtube mr5" style="color: #ff0202;"></i><a href="http://www.youtube.com/watch?v=etKRxPYYjLw&&list=PLtO3n8NwlGMQsJ_KDX9hyHOZqqlX0uo-5&&index=35" target="_blank">Partner App - Full Vendor App Functionality</a></li>
                            </ul>
                            </div>
                            <div class="video-panel">
                                <ul>
                                            <li><a href="#" title="Attach your cab & upload your documents">
                                                    <img src="/images/file.svg" width="35" alt="">
                                        </a></li>
                                            <li><a href="#" title="Attach your cab & upload your documents">
                                                    <img src="/images/registration.svg" width="35" alt="">
                                        </a></li>
                                            <li><a href="#" title="Attach your cab & upload your documents">
                                                    <img src="/images/taxi-stop.svg" width="35" alt="">
                                        </a></li>
                                            <li><a href="#" title="Attach your cab & upload your documents">
                                                    <img src="/images/partner-icon.png" width="35" alt="">
                                        </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>-->
		    <h2 class="mt0 font-24"><b>DCO's and Taxi Operators... <a href="http://www.aaocab.com/vendor/join">Attach your taxi here</a></b></h2>

		</div>
		<div id="loading"></div>
<?php $this->endWidget(); ?>

	    </div>
            </div>





<?
if ($verifyEmail == 1)
{
    ?>
    	    <div class="col-12">
    		<div class="panel panel-white" >
    		    <div class="panel-body">
    			<div class="col-12 mb5">
    			    <h1 class="text-center">Verification Code</h1>
    			    <p class="text-center mb0 pb0" style="line-height: 28px; ">  Enter 6 digit verification code send to register email address </p>
    			    <p class="text-center mt0 pt0" style="color: #182db2"><b><?= $model->agt_email ?></b></p>
    			</div>
    			<div class="col-12 text-center mb5 mt30">
    			    <input type="hidden" name="emailtoverify" id="emailtoverify" value="<?= $emailToVerify ?>">
    			</div>
    			<div class="col-12 col-md-3 text-center mb10 float-none marginauto">
    			    <input type="text" name="emailvercode" id="emailvercode" required="true" class="form-control"  style="height:50px;">
    			</div>
    			<div class="col-12 text-center mb20">
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
    </div>
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



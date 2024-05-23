<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<?
$selectizeOptions = [
'create'  => false, 
'persist'			 => true, 
'selectOnTab'		 => true, 
'createOnBlur'		 => true, 
'dropdownParent'	 => 'body',
'optgroupValueField' => 'id', 
'optgroupLabelField' => 'text', 
'sortField'			 => 'text', 
'optgroupField'		 => 'id', 
'openOnFocus'		 => true, 
'preload'			 => false,
'labelField'		 => 'text', 
'valueField'		 => 'id', 
'searchField'		 => 'text', 
'closeAfterSelect'	 => true,
'addPrecedence'		 => false,
					];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
?>

<div class="content-boxed-widget">

	<div id="AgentOuterDiv">
		<div class="h4" style="color: #de6a1e;"><span id="AgentOuterDivText"></span></div>
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
	<? if (Yii::app()->controller->action->id == 'corpjoin')
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
	<div class="content p0 bottom-20" style="color:#008a00;text-align: center">
		<b><?php echo Yii::app()->user->getFlash('success'); ?></b>
	</div>
	<div class="content p0 bottom-20" style="color:#F00;text-align: center">
		<?php echo Yii::app()->user->getFlash('error'); ?>
	</div>
	<div id="AgentInnerDiv" style="display: <?= $showdiv ?>">
		<?php
		$form = $this->beginWidget('CActiveForm', array('id' => 'agent-form', 'enableClientValidation' => TRUE,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data'
			),
		));
		/* @var $form CActiveForm */
		?>
		

			
				<h3 class="mb0 font-16">Become or travel agent with Gozo. Join Gozo's travel partner family..</h3>
				<div class="content p0 bottom-0">
					<div class="input-simple-1 has-icon input-blue bottom-20"><em>First name</em><i class="fas fa-user-alt"></i>
<?= $form->textField($model, 'agt_fname', array('placeholder' => 'Your First Name','class' => "form-control")) ?> 
<?php echo $form->error($model, 'agt_fname',['class' => 'help-block error']); ?>
					</div>
				</div>
				<div class="content p0 bottom-0">
					<div class="input-simple-1 has-icon input-blue bottom-20"><em>Last name</em><i class="fas fa-user-alt"></i>
<?= $form->textField($model, 'agt_lname', array('placeholder' => 'Your Last Name','class' => "form-control")) ?> 
<?php echo $form->error($model, 'agt_lname',['class' => 'help-block error']); ?>
					</div>
				</div>
				<div class="content p0 bottom-0">
					<div class="input-simple-1 has-icon input-blue bottom-20"><strong>Required</strong><em>Email*</em><i class="fas fa-envelope"></i>
<?= $form->textField($model, 'agt_email', array('placeholder' => 'Enter primary email','class' => "form-control")) ?>
<?php echo $form->error($model, 'agt_email',['class' => 'help-block error']); ?>
					</div>
				</div>
				<div class="content p0 bottom-0">
					<div class="input-simple-1 has-icon input-blue bottom-50"><strong>Required</strong><em>Phone Number (incl. country code)</em><i class="fa fa-phone"></i>

							<?php
							$this->widget('ext.intlphoneinput.IntlPhoneInput', array(
								'model'					 => $model,
								'attribute'				 => 'agt_phone',
								'codeAttribute'			 => 'agt_phone_country_code',
								'numberAttribute'		 => 'agt_phone',
								'options'				 => array(// optional
									'separateDialCode'	 => true,
									'autoHideDialCode'	 => true,
									'initialCountry'	 => 'in'
								),
								'htmlOptions'			 => ['class' => 'form-control', 'id' => 'fullContactNumber', 'value' => ''],
								'localisedCountryNames'	 => false, // other public properties
							));
							?> 
<?php echo $form->error($model, 'agt_phone_country_code',['class' => 'help-block error']); ?>
<?php echo $form->error($model, 'agt_phone',['class' => 'help-block error']); ?>
					</div>
				</div>
				<div class="content p0 bottom-0">
					<div class="select-box-1 mt30 mb20 line-height18">
						<em>Select City in which your business is located</em>
						<?php
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $model,
							'attribute'			 => 'agt_city',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "Enter Your City",
							'fullWidth'			 => true,
							'htmlOptions'		 => array('width' => '100%', ''
							),
							'defaultOptions'	 => $selectizeOptions + array(
						'onInitialize'	 => "js:function(){
                                        populateSource(this, '{$model->agt_city}');
                                    }",
						'load'			 => "js:function(query, callback){
                                            loadSource(query, callback);
                                        }",
						'render'		 => "js:{
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
						<span class="has-error"><? echo $form->error($model, 'agt_city',['class' => 'help-block error']); ?></span>

					</div>
				</div>
				<div class="content p0 bottom-0 checkboxes-demo mb20">
					<div class="fac-checkbox">
<?= $form->checkBox($model, 'agt_tnc'); ?>    
I Agree to the <a href="/terms/channelpartner">Terms and Conditions Channel Partner</a>
						<!--								<label for="box3-fac-checkboxs3">I Agree to the <a href="#">Terms and Conditions</a> Channel Partner</label>-->
					</div>
				</div>
				<div class="content p0 bottom-0 text-center mb20" id="vendorSubmitDiv">
<?= CHtml::submitButton('Create Account', ['class' => "uppercase btn-orange shadow-medium", 'name' => 'submit']) ?>

				</div>
<?php $this->endWidget(); ?>
	</div>
	</div>
<? if ($verifyEmail == 1) { ?>
	<div class="content-boxed-widget">
		
			<h1 class="text-center">Verification Code</h1>
			<p class="text-center line-height16">  Enter 6 digit verification code send to register email address </p>
			<p class="text-center" style="color: #182db2"><b><?= $model->agt_email ?></b></p>
		
		
		<input type="hidden" name="emailtoverify" id="emailtoverify" value="<?= $emailToVerify ?>">
		<div class="input-simple-1 has-icon input-blue bottom-20">
			<input type="text" name="emailvercode" id="emailvercode" placeholder="Enter Code" required="true" class="form-control"  style="height:50px;">
		</div>
		<div class="col-xs-12 text-center mb20">
			<button class="uppercase btn-green3 shadow-medium" onclick="verifyEmail();">Enter Code</button>
		</div> 
		<!--                        <div class="text-center">
									<a class="pr50" style="color: #1050b7">Resend Code</a>
								</div>-->
	</div>
        <? } ?>

<div class="content-boxed-widget">
	<div class="content p0 bottom-0">
		<h4 class="ultrabold font-16">Gozo Travel partner program</h4>
		<p>Travel Agents, Hotels travel desks, Shopkeepers... Offer convenience to your customers and make money. Its simple! Join now and instantly start creating bookings for your customers</p>

		<h4 class="ultrabold mt20 font-16">Benefits of joining Gozo's Travel network…</h4>
		<ol class="line-height18 mb0">
			<li>Get direct access to India's largest network of intercity AC cabs</li>
			<li>Offer convenience of outstation taxi bookings to your customers</li>
			<li>Buy bookings at very low pricing - and sell them to create profits</li>
			<li>Easily create pre-paid or post-paid bookings using our kiosk</li>
			<li>Get 24x7 support from our travel desk & service center</li>
			<li>Just like our other partners you can generate business of Rs. 50,000 to Rs. 1Lac every month</li>
		</ol>
	</div>
</div>
<div class="content-boxed-widget">
	<div class="content p0 bottom-0">
		<h4 class="ultrabold font-16">Any questions? Contact our Travel Partner team</h4>
		<p class="mb10 hide"><img src="<?= Yii::app()->baseUrl ?>/images/india-flag.png" alt="INDIA" class="display-ini mr10"><a href="tel:9051877000">(+91) 90518 77000 (24x7)</a></p>
		<p><i class="fa fa-envelope mr0" style="font-size:14px; color: #fb6523;"></i> <a href="mailto:channel@gozocabs.in" style="color:#000;">channel@gozocabs.in</a></p>
		<h4 class="ultrabold font-16 mt30">DCO's and Cab Operators...</h4>
		<a href="<?php echo Yii::app()->createUrl('/vendor/join'); ?>" class="uppercase btn-green pl10 pr10 mr5 default-link">Attach your cab here</a>
	</div>
</div>

<div class="clear"></div>
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
		if(code){
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
		else{
			alert("Enter Verification Code");
		}
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

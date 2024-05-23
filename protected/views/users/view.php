<style>
    #Contact_eml_email_address , #Users_usr_gender{
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

</style>
<div>
	<?
//    $userId    = Yii::app()->user->getId();
//    $userModel = Users::model()->findByPk($userId);
//    $userpic  = $userModel->usr_profile_pic;
//    echo '<img src="' . $userpic . '" height="50px">';
	Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
	$cityList = ['' => 'Select City'] + Cities::model()->getAllCityList();
	if ($flag)
	{
		$readOnly = array();
	}
	else
	{
		$readOnly = array('readOnly' => true);
	}

	//var_dump($model->attributes);
	?>
	<?
	$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'view-form', 'enableClientValidation' => true,
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
        <div class="col-xs-12">
			<?php echo CHtml::errorSummary($model); ?>
        </div>
        <div class="col-xs-12 text-center">
			<?php if (Yii::app()->user->hasFlash('success')): ?>
				<div class="alert alert-success" style="padding: 10px">
					<?php echo Yii::app()->user->getFlash('success'); ?>
				</div>
			<?php endif; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-8 float-none marginauto">
            <div class="col-xs-12 col-sm-6 pl20 pr20 ">
                <div class="form-group">
                    <label class="control-label">Name</label>
					<?= $form->textFieldGroup($contactModel, 'ctt_first_name', array('label' => '', 'class' => 'form-control border-radius')) ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 pl20 pr20">
                <div class="form-group">
                    <label class="control-label" for="Contact_ctt_last_name">Last name</label>
<!--                    <input type="text" class="form-control border-radius" id="exampleInputCompany6" placeholder="Last Name">-->
					<?= $form->textFieldGroup($contactModel, 'ctt_last_name', array('label' => '', 'class' => 'form-control border-radius')) ?>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <h4 class="m0 weight400">Contacts Details</h4>
    <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-8">
            <div class="panel-body">
                <div class="form-group">
                    <label for="ContactEmail_eml_email_address" class="col-sm-4 control-label">Email ID</label>
                    <div class="col-sm-8">
						<?= $form->textFieldGroup($emailModel, 'eml_email_address', array('class' => 'form-control border-radius', 'label' => '', 'widgetOptions' => array('htmlOptions' => $readOnly))) ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="usr_gender" class="col-sm-4 control-label">Gender<span style="color: #F00"> *</span></label>
                    <div class="col-sm-8">
						<?= $form->dropDownListGroup($model, 'usr_gender', array('label' => '', 'widgetOptions' => array('data' => array('' => 'Select Gender', '1' => 'Male', '2' => 'Female')))) ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="ContactPhone_phn_phone_no" class="col-sm-4 control-label">Mobile Number<span style="color:#F00"> *</span></label>
                    <div class="col-xs-12 col-sm-8"><div class="form-group">
							<?php
							$this->widget('ext.intlphoneinput.IntlPhoneInput', array(
								'model'					 => $phoneModel,
								'attribute'				 => 'phn_phone_no',
								'codeAttribute'			 => 'phn_phone_country_code',
								'numberAttribute'		 => 'phn_phone_no',
								'options'				 => array(// optional
									'separateDialCode'	 => true,
									'autoHideDialCode'	 => true,
									'initialCountry'	 => 'in'
								),
								'htmlOptions'			 => ['class' => 'form-control', 'id' => 'fullContactNumber3'],
								'localisedCountryNames'	 => false, // other public properties
							));
							?> 

						</div></div>
                </div>
                <div class="form-group">
                    <label for="Contact_ctt_address" class="col-sm-4 control-label">Address</label>
                    <div class="col-sm-8">
						<?= $form->textFieldGroup($contactModel, 'ctt_address', array('label' => '', 'class' => 'form-control border-radius')) ?>
                    </div>
                </div>
				<!--                <div class="form-group">
									<label for="ctt_address" class="col-sm-4 control-label">Address Line2</label>
									<div class="col-sm-8">
				<?= $form->textFieldGroup($contactModel, 'ctt_address', array('label' => '', 'class' => 'form-control border-radius')) ?> </div>
								</div>
								<div class="form-group">
									<label for="ctt_address" class="col-sm-4 control-label">Nearby Landmark</label>
									<div class="col-sm-8">
				<?= $form->textFieldGroup($contactModel, 'ctt_address', array('label' => '', 'class' => 'form-control border-radius')) ?>  </div>
								</div>-->
                <div class="form-group">
                    <label for="usr_zip" class="col-sm-4 control-label">Zip Code</label>
                    <div class="col-sm-8">
						<?= $form->textFieldGroup($model, 'usr_zip', array('label' => '', 'class' => 'border-radius')) ?>   </div>
                </div>
                <div class="form-group">
                    <label for="usr_country" class="col-sm-4 control-label">Country</label>
                    <div class="col-sm-8 p0">
						<?php
						$criteria		 = new CDbCriteria();
						$criteria->order = 'country_order DESC';
						$countryList	 = CHtml::listData(Countries::model()->findAll($criteria), 'id', 'country_name');
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $model,
							'attribute'			 => 'usr_country',
							'data'				 => $countryList,
							'useWithBootstrap'	 => true,
							'placeholder'		 => 'Country',
							'htmlOptions'		 => array('onchange' => "changeCountry(this)"),
							'defaultOptions'	 => array(
								'create'			 => false,
								'persist'			 => false,
								'createOnBlur'		 => true,
								'closeAfterSelect'	 => true,
								'addPrecedence'		 => true,
							),
						));
						?>
                        <input type="hidden" id="countryname" name="countryname" value="<?= $model->usr_country; ?>">
                    </div>
                </div>
                <div class="form-group" id="statetextdiv">
                    <label for="usr_state_text" class="col-sm-4 control-label">State</label>
                    <div class="col-sm-8">
						<?= $form->textFieldGroup($model, 'usr_state_text', array('label' => '')) ?>
                    </div>
                </div>
                <div class="form-group" id="statediv">
                    <label for="ctt_state" class="col-sm-4 control-label">State</label>
                    <div class="col-sm-8 p0">
						<?php
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $contactModel,
							'attribute'			 => 'ctt_state',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "State",
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
								'optgroupLabelField' => 'id',
								'optgroupField'		 => 'id',
								'openOnFocus'		 => true,
								'labelField'		 => 'text',
								'valueField'		 => 'id',
								'searchField'		 => 'text',
								'closeAfterSelect'	 => true,
								'addPrecedence'		 => false,
								'onInitialize'		 => "js:function(){
                            this.load(function(callback){
                            var obj=this;    
                            
                             xhr=$.ajax({
                     url:'" . CHtml::normalizeUrl(Yii::app()->createUrl('users/countrytostate', ['countryid' => $model->usr_country])) . "',
                     dataType:'json',                  
                     success:function(results){
                         obj.enable();
                         callback(results);
                          $('#Contact_ctt_state')[0].selectize.setValue({$contactModel->ctt_state});
                     },                    
                     error:function(){
                         callback();
                         }});
                     });
                     }",
								'render'			 => "js:{
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
                <div class="form-group">
                    <label for="ctt_city" class="col-sm-4 control-label">City</label>
                    <div class="col-sm-8">
						<?= $form->textFieldGroup($contactModel, 'ctt_city', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('value' => $city['cty_name'])))) ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="  col-sm-offset-7 col-sm-5 col-xs-offset-4 col-xs-8">
                        <button type="submit" class="btn next-btn border-none profilesave"  name="sub" value="Submit">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $state = '<?= $contactModel->ctt_state ?>';
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




        $(window).on('beforeunload', function () {
            $(window).scrollTop(0);
        });
        $('#<?= CHtml::activeId($phoneModel, 'phn_phone_no') ?>').mask("9999999999");
        $('#<?= CHtml::activeId($model, 'usr_zip') ?>').mask("999999");
        //        $('#<?= CHtml::activeId($model, 'usr_alternative_phone') ?>').mask("(999) 999-9999");
        //        $("#mobileVerify").bind('click', mobileVerifyHandler);
        //        $("#emvrify").bind('click', emalVrifyHandler);
        //        $("#<?= CHtml::activeId($model, 'email') ?>").bind('change', emailVerifyHandler);



        $("#view-form").submit(function (event) {



        });

        $("#Users_usr_state1").change(function () {

            var stid = $("#Users_usr_state").val();
            var href2 = '<?= Yii::app()->createUrl("users/cityfromstate"); ?>';
            $.ajax({
                "url": href2,
                "type": "GET",
                "dataType": "json",
                "data": {"id": stid},
                "success": function (data1) {

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
        var selectize = $('#Contact_ctt_state')[0].selectize;
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
                "type": "GET",
                "dataType": "json",
                "data": {"countryid": country},
                "success": function (data1) {
                    selectize.clearOptions();
                    selectize.addOption(data1);
                    selectize.refreshOptions(false);
                    //$('select').selectize(options);
                }
            });
        }
    }

    $('.profilesave').click(function (event) {
        
        var profcontact = $.trim($('#fullContactNumber3').val());
        var cont = profcontact.replace(/\s/g, '');
        if (profcontact == "")
        {
            alert('Mobile no cannot be blank');
            return false;
        } else if (cont.length < 10 || cont.length > 12 || isInteger(profcontact) == false)
        {
            alert('Invalid mobile no');
            return false;
        } else
        {
            return true;
        }
    });

    function isInteger(s) {
        var i;
        s = s.toString();
        for (i = 0; i < s.length; i++) {
            var c = s.charAt(i);
            if (isNaN(c)) {
                return false;
            }
        }
        return true;
    }

</script>


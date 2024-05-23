<?php
$version	 = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask1.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/booking.js?v=' . $version);

$infosource	 = BookingAddInfo::model()->getInfosource('admin');
if (Yii::app()->request->isAjaxRequest)
{
	$cls = "";
}
else
{
	$cls = "col-lg-6 col-md-8 col-sm-10 col-sm-12 pb10";
}
$bookingType = Booking::model()->booking_type;
$locked		 = ' <i class="fa fa-lock"></i>';
?>
<style type="text/css">
    @media (min-width: 992px){
        .modal-lg {
            width: 95%!important;
        }
    }
    @media (min-width: 768px){
        .modal-lg {
            width: 100%;
        }
    }
    .form-group {
        margin-bottom: 7px;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    .bootstrap-timepicker-widget input  {
        border: 1px #555555 solid;color: #555555;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin:0;
    }
    .selectize-input {
        min-width: 0px !important; 
        width: 30% !important; 
    }

    .border-none{
        border: 0!important;
    }
    .datepicker.datepicker-dropdown.dropdown-menu ,
    .bootstrap-timepicker-widget.dropdown-menu,
    .yii-selectize.selectize-dropdown
    {z-index: 9999 !important;}

    td, th {
        padding: 10px  !important ; 
    }
</style>
<div class="row">
    <div class="col-xs-12 text-center h2 mt0">
        <label for="type" class="control-label"><span style="font-weight: normal; font-size: 30px;">Booking Id:</span> </label>
        <b><?= $model->bkg_booking_id ?></b><label><?
			if ($model->bkg_agent_id > 0)
			{
				$agentsModel = Agents::model()->findByPk($model->bkg_agent_id);
				if ($agentsModel->agt_type == 1)
				{
					echo "(Corporate)";
				}
				else
				{
					echo "(Partner)";
				}
			}
			?></label>
    </div>
</div>
<div class="">
	<?php
	$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'edit-booking-form', 'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => 'js:function(form,data,hasError){
                if(!hasError){
                    $("#ebtnsbmt").prop( "disabled", true );
                    if(!validateEditBooking())
					{
                        $("#ebtnsbmt").prop( "disabled", false );
                        return false;                         
					}
                    $.ajax({
                    "type":"POST",
                    "dataType":"json",                  
                    "url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
                    "data":form.serialize(),
                    "beforeSend": function () {
                        ajaxindicatorstart("");
                    },
                    "complete": function () {  
                        ajaxindicatorstop();
                    },
                    "success":function(data1){            
                     $("#diverr").hide();
                        if(data1.success){
                        alert(data1.message);
                        location.href=data1.url;
                            return false;
                        } else{
                        $("#ebtnsbmt").prop( "disabled", false );
                            var errors = data1.errors;
                            var errStr="";
                            var customerrors = data1.customerror;  
                            
                            for(var err in customerrors){
                            errStr+= "<li>"+customerrors[err]+"</li>";
                             alert(customerrors[err]);
                            }                     
                            settings=form.data(\'settings\');
                             $.each (settings.attributes, function (i) {                            
                                $.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
                                 
                              });
                              $.fn.yiiactiveform.updateSummary(form, errors);
                            } 
                            if(errStr!="")
                            {
                            errStr="<ul>"+errStr+"</ul>"
                            var errstring = $("#edit-booking-form_es_").text();
                                if(errstring.trim()== "Please fix the following input errors:")
                                {                               
                                    $("#diverr").show();                                   
                                    $("#diverr").html(errStr);
                                }
                                else
                                {                                
                                    $("#edit-booking-form_es_").html(errstring+errStr);
                                }
                            }
                        },
                     error: function(xhr, status, error){
                     
                       var x= confirm("Network Error Occurred. Do you want to retry?");
                       if(x){
                                $("#edit-booking-form").submit();
                            }
                            else{
                            $("#ebtnsbmt").prop( "disabled", false );
                            }
                         }
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
			'class' => '',
		),
	));
	?>
    <div class="row">
        <div class="col-xs-12">
			<?php
			echo $form->errorSummary($model);
			echo CHtml::errorSummary($model)
			?>
			<?= $form->hiddenField($model, 'bkg_id', array('readonly' => true)) ?>
			<?= $form->hiddenField($model->bkgUserInfo, 'bkg_user_id', array('readonly' => true)) ?>
        </div>


        <div class="col-xs-12">

            <div class="panel panel-default panel-border">
                <div class="col-xs-12 alert alert-block alert-danger" href="er" id = "diverr" style="display: none"></div>
                <h3 class="pl15">Personal Information</h3>
                <div class="panel-body pt0">
                    <div class="row">

                        <div class=" col-md-4">                    
                            <div class="row">
                                <div class="col-sm-6">
									<?= $form->textFieldGroup($model->bkgUserInfo, 'bkg_user_fname', array('label' => 'First Name', 'widgetOptions' => array('htmlOptions' => array('class'=>'nameFilterMask')))) ?>
                                </div>
                                <div class="col-sm-6">
									<?= $form->textFieldGroup($model->bkgUserInfo, 'bkg_user_lname', array('label' => 'Last Name', 'widgetOptions' => array('htmlOptions' => array('class'=>'nameFilterMask')))) ?>
                                    <div id="errordivemail" style="color:#da4455"></div>
                                </div>
                            </div>
                        </div>

                        <div class=" col-md-8">
                            <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <label class="control-label" >Contact Number</label>
                                    <div class="row">
                                        <div class="form-group ">
                                            <div class="col-xs-3 col-sm-4"> 
												<?php
												$this->widget('ext.yii-selectize.YiiSelectize', array(
													'model'				 => $model->bkgUserInfo,
													'attribute'			 => 'bkg_country_code',
													'useWithBootstrap'	 => true,
													"placeholder"		 => "Code",
													'fullWidth'			 => false,
													'htmlOptions'		 => array(
														'style' => 'width: 60%',
													),
													'defaultOptions'	 => array(
														'create'			 => false,
														'persist'			 => true,
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
                                    $('#BookingUser_bkg_country_code')[0].selectize.setValue({$model->bkgUserInfo->bkg_country_code});
                                    },                    
                                    error:function(){
                                    callback();
                                    }});
                                    });
                                    }",
														'render'			 => "js:{
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
                                            <div class="col-xs-9 col-sm-8 pl0">
												<?= $form->textFieldGroup($model->bkgUserInfo, 'bkg_contact_no', array('label' => '', 'widgetOptions' => array('class' => '', 'reuqired' => 'required'))) ?>
                                                <div id="errordivmob" style="color:#da4455"></div>
                                            </div>                                        
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <label class="control-label">Alternate Contact Number</label>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-xs-3 col-sm-4">
												<?php
												$this->widget('ext.yii-selectize.YiiSelectize', array(
													'model'				 => $model->bkgUserInfo,
													'attribute'			 => 'bkg_alt_country_code',
													'useWithBootstrap'	 => true,
													"placeholder"		 => "Code",
													'fullWidth'			 => false,
													'htmlOptions'		 => array(
														'style' => 'width: 50%',
													),
													'defaultOptions'	 => array(
														'create'			 => false,
														'persist'			 => true,
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
                                         $('#BookingUser_bkg_alt_country_code')[0].selectize.setValue({$model->bkgUserInfo->bkg_alt_country_code});
                                     },                    
                                     error:function(){
                                         callback();
                                     }});
                                            });
                                           }",
														'render'			 => "js:{
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
                                            <div class="col-xs-9 col-sm-8 pl0">
												<?= $form->textFieldGroup($model->bkgUserInfo, 'bkg_alt_contact_no', array('label' => '', 'widgetOptions' => array())) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
									<?= $form->emailFieldGroup($model->bkgUserInfo, 'bkg_user_email', array('label' => 'Email', 'widgetOptions' => array())) ?>
                                    <div id="errordivemail" style="color:#da4455"></div>
                                </div>
                            </div></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 text-center pb10">
				<?= CHtml::submitButton('Submit', array('style' => 'font-size:1.4em', 'class' => 'btn btn-primary btn-lg pl50 pr50', 'id' => 'ebtnsbmt')); ?>
            </div>

        </div>
    </div>
    <div id="driver1"></div>
	<?php $this->endWidget(); ?>
	<?php echo CHtml::endForm(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function ()
    {
<?
if ($model->bkg_status != 1)
{
	?>
	        $('.clsReadOnly').attr('readOnly', true);
	        $('.selectReadOnly').select2('readonly', true);
<? } ?>
        $('.bootbox').removeAttr('tabindex');
        $('.glyphicon').addClass('fa').removeClass('glyphicon');
        $('.glyphicon-time').addClass('fa-clock-o').removeClass('glyphicon-time');
        //  fillDistance();
        $(document).on('hidden.bs.modal', function (e)
        {
            $('body').addClass('modal-open');
        });
    });
    function validateEditBooking()
    {
        var ck_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;
        var primaryPhone = $('#BookingUser_bkg_contact_no').val();
        var email = $('#BookingUser_bkg_user_email').val();
        var $select = $("#BookingUser_bkg_country_code").selectize({
        });
        var selectizeControl = $select[0].selectize;
        var country_code = selectizeControl.getItem(selectizeControl.getValue()).text();
        error = 0;
        $("#errordivmob").text('');
        $("#errordivemail").text('');
        $("#errordivrate").text('');
        $("#errordivreturn").text('');
        if ((primaryPhone == '' || primaryPhone == null) && (email == '' || email == null))
        {
            error += 1;
            $("#errordivmob").text('');
            $("#errordivemail").text('');
            $("#errordivmob").text('Please enter mobile number or email address.');
        } else
        {
            if (primaryPhone != '')
            {
                if (country_code == '' || country_code == null)
                {
                    error += 1;
                    $("#errordivmob").text("please select country code.");
                } else
                {
                    var ck_indian_mobile = /^[0-9]+$/;
                    if (country_code == '91')
                    {
                        var message = 'Contact Number can contain only [0-9].';
                    }
                    if (!ck_indian_mobile.test(primaryPhone))
                    {
                        error += 1;
                        $("#errordivmob").text('');
                        $("#errordivemail").text('');
                        $("#errordivmob").text(message);
                    } else
                    {
                        error += 0;
                        $("#errordivmob").text('');
                        $("#errordivemail").text('');
                    }
                }
            } else
            {
                if (email != '')
                {
                    if (!ck_email.test(email))
                    {
                        error += 1;
                        $("#errordivmob").text('');
                        $("#errordivemail").text('');
                        $("#errordivemail").text('Invalid email address');
                    }
                }
            }
        }
        if (error > 0)
        {
            return false;
        }
        return true;
    }

 
    

    $('form').on('focus', 'input[type=number]', function (e)
    {
        $(this).on('mousewheel.disableScroll', function (e)
        {
            e.preventDefault()
        })
        $(this).on("keydown", function (event)
        {
            if (event.keyCode === 38 || event.keyCode === 40)
            {
                event.preventDefault();
            }
        });
    });
    $('form').on('blur', 'input[type=number]', function (e)
    {
        $(this).off('mousewheel.disableScroll');
        $(this).off('keydown');
    });


</script>
<input id="map_canvas" type="hidden">
<?
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>
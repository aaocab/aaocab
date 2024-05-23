<?php
$version	 = Yii::app()->params['siteJSVersion'];
//Yii::app()->clientScript->registerScriptFile("https://maps.googleapis.com/maps/api/js?v=3.1exp&sensor=false&");
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
//$bookingType = array(1 => 'One way', 2 => 'Round', 3 => 'Multi City');
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
        border: 1px #555555 solid;
		color: #555555;
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
    {
		z-index: 9999 !important;
	}

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
<div class="row">
    <div class="col-xs-12 mb20">
        <div style="text-align: center">
			<? $button_type = 'label-user-edit'; ?>
			<?= $model->getActionButton([], $button_type);
			?>
        </div>
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
			<?= $form->hiddenField($model, 'lead_id', array('readonly' => true)) ?>
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
									<?= $form->textFieldGroup($model->bkgUserInfo, 'bkg_user_fname', array('label' => 'First Name', 'widgetOptions' => array('htmlOptions' => array('class' => 'nameFilterMask')))) ?>
                                </div>
                                <div class="col-sm-6">
									<?= $form->textFieldGroup($model->bkgUserInfo, 'bkg_user_lname', array('label' => 'Last Name', 'widgetOptions' => array('htmlOptions' => array('class' => 'nameFilterMask')))) ?>
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
												<?= $form->textFieldGroup($model->bkgUserInfo, 'bkg_contact_no', array('label' => '', 'widgetOptions' => array('class' => ''))) ?>
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

        <div class="col-xs-12">
            <div class="panel panel-default panel-border">
                <h3 class="pl15">Additional Information</h3>
                <div class="panel-body pt0">
                    <div class="row">  
                        <div class="col-xs-6">
							<?
							if ($model->bkg_agent_id > 0)
							{
								$agentsModel = Agents::model()->findByPk($model->bkg_agent_id);
								if ($agentsModel->agt_type != 1)
								{
									?>
									<div class="row">  
										<div class="col-xs-12">
											<div class="form-group">
												<label class="control-label">Link to Partner Account</label>
												<?php
												$disable1	 = ['disabled' => 'disabled'];
												$dataagents	 = Agents::model()->getAgentsFromBooking();
												$this->widget('booster.widgets.TbSelect2', array(
													'model'			 => $model,
													'attribute'		 => 'bkg_agent_id',
													'val'			 => $model->bkg_agent_id,
													'asDropDownList' => FALSE,
													'options'		 => array('data' => new CJavaScriptExpression($dataagents), 'allowClear' => true),
													'htmlOptions'	 => array(
												'id'			 => 'bkg_agent_id',
												'style'			 => 'width:100%',
												'placeholder'	 => 'Agent name') + $disable1
												));
												?>
											</div> 
										</div> 
									</div>
									<?
								}
							}
							?>

                            <div class="row">
								<?
								if ($model->bkg_agent_id > 0)
								{
									$agentsModel = Agents::model()->findByPk($model->bkg_agent_id);
									if ($agentsModel->agt_type == 1)
									{
										?>
										<div class="col-xs-12"> 
											<div class="form-group"> 
												<label >Link To Corporate Account</label>
												<div class="form-control"><?= $agentsModel->agt_company . " (" . $agentsModel->agt_referral_code . ")"; ?></div>
											</div>
										</div>  
										<div class="col-xs-12" >
											<div class="col-xs-4">To be paid by</div>
											<div class="col-xs-8">
												<label class="checkbox-inline ">
													<div class="form-control"><?= ($model->bkgInvoice->bkg_corporate_remunerator == 2) ? "Company/Agent" : "Customer" ?></div>
												</label>
											</div>
										</div> 
									<? } ?>
									<div class="col-xs-12">
										<input type="hidden" id="agentnotifydata" name="agentnotifydata" value='<?= json_encode($model->agentNotifyData); ?>'>
										<button class="btn btn-info" type="button" onclick="shownotifyopt();">Change Notification Defaults</button>
									</div>
								<? }
								?>
                                <div class="col-xs-12"> 
                                    <div class="form-group"> 
                                        <label class="control-label">TAGS</label>
										<?php
//										$SubgroupArray2	 = Booking::model()->getTags();
//										$this->widget('booster.widgets.TbSelect2', array(
//											'name'			 => 'bkg_tags',
//											'model'			 => $model,
//											'data'			 => $SubgroupArray2,
//											'value'			 => explode(',', $model->bkgTrail->bkg_tags),
//											'htmlOptions'	 => array(
//												'multiple'		 => 'multiple',
//												'placeholder'	 => 'Enter Tags',
//												'width'			 => '100%',
//												'style'			 => 'width:100%',
//											),
//										));
										$SubgroupArray2	 = Tags::getListByType(Tags::TYPE_BOOKING);
										$this->widget('booster.widgets.TbSelect2', array(
											//'name'			 => 'bkg_tags',
											'attribute'		 => 'bkg_tags',
											'model'			 => $model->bkgTrail,
											'data'			 => $SubgroupArray2,
											'val'			 => explode(',', $model->bkgTrail->bkg_tags),
											// 'value' => explode(',', $model->bkg_tags),
											'htmlOptions'	 => array(
												'multiple'		 => 'multiple',
												'placeholder'	 => 'Add keywords that you may use to search for this booking later',
												//'class'			 => 'selectReadOnly',
												'style'			 => 'width:100%',
											),
										));
										?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label" style="text-align: left;" for="car"><nobr>How did you hear about Gozo cabs?</nobr></label>
										<?php
										$datainfo		 = VehicleTypes::model()->getJSON($infosource);
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model->bkgAddInfo,
											'attribute'		 => 'bkg_info_source',
											'val'			 => "'" . $model->bkgAddInfo->bkg_info_source . "'",
											'asDropDownList' => FALSE,
											'options'		 => array('data' => new CJavaScriptExpression($datainfo)),
											'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Infosource')
										));
										?>
                                    </div>
                                </div>
                                <div class="col-sm-6 pl0">
                                    <label class="control-label" for="exampleInputName6"></label>
									<?= $form->checkboxGroup($model->bkgPref, 'bkg_tentative_booking', array('widgetOptions' => array('htmlOptions' => []))) ?>
                                </div>
								<? $agentshow		 = ($model->bkgAddInfo->bkg_info_source == 'Agent') ? '' : 'hide' ?>
                                <div class="col-sm-6 <?= $agentshow ?>" id="agent_show">
                                    <!--                                    <div class="form-group">
                                                                            <label class="control-label" for="type">Partner</label>
									<?php
//                                        $this->widget('booster.widgets.TbSelect2', array(
//                                            'model' => $model,
//                                            'attribute' => 'bkg_agent_id',
//                                            'val' => $model->bkg_agent_id,
//                                            'options' => array('data' => new CJavaScriptExpression($agentlist)),
//                                            'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Select Agent', 'label' => 'Agent')
//                                        ));
									?>
                                                                        </div>
                                                                        <span class="has-error"><? // echo $form->error($model, 'bkg_agent_id');                                   ?></span>-->
                                </div>
								<? $sourceDescShow	 = ($model->bkgAddInfo->bkg_info_source == 'Friend' || $model->bkgAddInfo->bkg_info_source == 'Other') ? '' : 'hide'; ?>
                                <div class="col-sm-6 <?= $sourceDescShow ?>" id="source_desc_show">
                                    <div class="form-group">
                                        <label class="control-label" for="type">&nbsp;</label>
										<?= $form->textFieldGroup($model->bkgAddInfo, 'bkg_info_source_desc', array('label' => "", 'widgetOptions' => array('htmlOptions' => array('placeholder' => '')))) ?>										
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
									<?php
									$str			 = explode("\\", $model->bkgAddInfo->bkg_file_path);
									?>
                                    <label class="control-label" for="vendor">Attach Files</label>
                                    <a href="<?= Yii::app()->getBaseUrl(true) . $model->bkgAddInfo->bkg_file_path ?>"  target="blank"><?= $str[2] ?></a><br />
									<?= $form->fileFieldGroup($model, 'fileImage', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control']))) ?>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
										<?= $form->textFieldGroup($model->bkgAddInfo, 'bkg_flight_no', array('label' => 'Flight Number', 'widgetOptions' => array('htmlOptions' => array()))) ?>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-sm-12">
									<?= $form->textAreaGroup($model, 'new_remark', array('widgetOptions' => array('htmlOptions' => array()))) ?>
                                </div>
                                <div class="col-sm-12">
									<?= $form->textAreaGroup($model, 'bkg_instruction_to_driver_vendor', array('label' => 'Additional Instruction to Vendor/Driver', 'widgetOptions' => array('htmlOptions' => array()))) ?>
                                </div>
                                <div class="col-sm-12">
									<?= $form->checkboxGroup($model->bkgPref, 'bkg_invoice', array('widgetOptions' => array('htmlOptions' => []))) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label for="inputEmail" class="control-label col-xs-5">Customer Type</label>
                                    <div class="col-xs-7">
										<?=
										$form->radioButtonListGroup($model->bkgAddInfo, 'bkg_user_trip_type', array(
											'label'			 => '', 'widgetOptions'	 => array(
												'data' => Booking::model()->userTripList
											), 'inline'		 => true,)
										);
										?>
                                    </div>
                                </div></div>
                            <div class="row">
                                <div class="form-group">
                                    <label for="inputEmail" class="control-label col-xs-5 checkbox">Send booking confirmations to user by</label>
                                    <div class="col-xs-7">
                                        <label class="checkbox-inline ">
											<?= $form->checkboxGroup($model->bkgPref, 'bkg_send_email', ['label' => 'Email']) ?>
                                        </label>
                                        <label class="checkbox-inline ">
											<?= $form->checkboxGroup($model->bkgPref, 'bkg_send_sms', ['label' => 'Phone']) ?>
                                        </label>
                                    </div>
                                </div> 
                            </div>
                        </div>

                        <div class="col-xs-6">
                            <div class="row">
                                <div class="col-xs-12 special_request">
                                    <label class="control-label" >Special Requests</label>

                                    <div class="col-xs-12">
										<?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_senior_citizen_trvl', []) ?>
										<?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_kids_trvl', []) ?>
										<?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_woman_trvl', []) ?>
										<?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_carrier', []) ?>
										<?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_driver_hindi_speaking', []) ?>
										<?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_driver_english_speaking', []) ?>
										<?
										$checkedother	 = ($model->bkgAddInfo->bkg_spl_req_other != '') ? "'checked'=>'checked'" : '';
										?>
										<?= $form->checkboxGroup($model, 'bkg_chk_others', ['label' => 'Others', 'widgetOptions' => array('htmlOptions' => [$checkedother])]) ?>
                                        <div id="othreq" style="display: <? echo ($model->bkgAddInfo->bkg_spl_req_other != '') ? '' : 'none' ?>">
											<?= $form->textFieldGroup($model->bkgAddInfo, 'bkg_spl_req_other', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Other Requests"]), 'groupOptions' => ['class' => 'm0'])) ?>  
                                        </div>
                                    </div> 
                                </div> 
                            </div>
                            <div class="row mt10">
								<?php
								$readOnly		 = [];
								$scvVctId		 = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);
								if ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC)
								{
									$readOnly = ['readOnly' => 'readOnly'];
								}
								?>

                                <label for="inputEmail" class="control-label col-xs-5">Number of Passengers</label>
                                <div class="col-xs-7">
									<?= $form->numberFieldGroup($model->bkgAddInfo, 'bkg_no_person', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Number of Passengers", 'min' => 1, 'max' => 10] + $readOnly), 'groupOptions' => ['class' => 'm0'])) ?>                      
                                </div>

                            </div>
                            <div class="row mt10">
                                <label for="inputEmail" class="control-label col-xs-5">Number of large suitcases</label>
                                <div class="col-xs-7">
									<?= $form->numberFieldGroup($model->bkgAddInfo, 'bkg_num_large_bag', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Number of large suitcases", 'min' => 0, 'max' => 10] + $readOnly), 'groupOptions' => ['class' => 'm0'])) ?>                      
                                </div> 
                            </div>
                            <div class="row mt10">
                                <div class="form-group">
                                    <label for="inputEmail" class="control-label col-xs-5">Number of small bags</label>
                                    <div class="col-xs-7">
										<?= $form->numberFieldGroup($model->bkgAddInfo, 'bkg_num_small_bag', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Number of small bags", 'min' => 0, 'max' => 10] + $readOnly), 'groupOptions' => ['class' => 'm0'])) ?>                      
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-xs-12 text-center pb10">
				<?= CHtml::submitButton('Submit', array('style' => 'font-size:1.4em', 'class' => 'btn btn-primary btn-lg pl50 pr50', 'id' => 'ebtnsbmt')); ?>
            </div>

        </div>
        <div class="row">
            <div class="col-xs-12">
                <label class="control-label"><h3>Booking Log</h3></label>
				<?
				Yii::app()->runController('admin/booking/showlog/booking_id/' . $model->bkg_id);
				?>
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


	$("#BookingAddInfo_bkg_info_source").change(function ()
	{
		var infosource = $("#BookingAddInfo_bkg_info_source").val();
		extraAdditionalInfo(infosource);
		/*
		 if (infosource == 'Agent') {
		 $("#agent_show").removeClass('hide');
		 }
		 if (infosource != 'Agent') {
		 $("#agent_show").addClass('hide');
		 $("#Booking_bkg_agent_id").val('');
		 }*/
	});
	$('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').change(function ()
	{
		if ($('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').is(':checked'))
		{
			$("#othreq").show();
		} else
		{
			$("#othreq").hide();
		}
	});
	function extraAdditionalInfo(infosource)
	{
		$("#agent_show").addClass('hide');
		$("#source_desc_show").addClass('hide');
		if (infosource == 'Agent')
		{
			$("#BookingAddInfo_bkg_info_source_desc").val('');
			$("#agent_show").removeClass('hide');
			$("#source_desc_show").addClass('hide');
		} else
		{
			$("#Booking_bkg_agent_id").val('');
			if (infosource == 'Friend')
			{
				$("#source_desc_show").removeClass('hide');
				$("#agent_show").addClass('hide');
				$("#BookingAddInfo_bkg_info_source_desc").attr('placeholder', "Friend's email please");
			} else if (infosource == 'Other')
			{
				$("#source_desc_show").removeClass('hide');
				$("#agent_show").addClass('hide');
				$("#BookingAddInfo_bkg_info_source_desc").attr('placeholder', "");
			}
		}

	}

	function  getDateobj(pdpdate, ptptime)
	{
		var date = pdpdate;
		var time = ptptime;
		var dateArr = date.split("/");
		var timeArr = time.split(" ");
		var mer = timeArr[1];
		var temp = timeArr[0].split(":");
		var hour = Number(temp[0]);
		var min = Number(temp[1]);
		if (mer == "PM")
		{
			if (hour != 12)
			{
				hour = 12 + hour;
			}
		} else if (hour == 12)
		{
			hour = 0;
		}
		//  var currDateTime = new Date();
		var dateObj = new Date(Number(dateArr[2]), Number(dateArr[1]) - 1, Number(dateArr[0]), hour, min, 0);
		return dateObj;
	}


	$('#<?= CHtml::activeId($model->bkgAddInfo, 'bkg_flight_no') ?>').mask('XXXX-XXXXXX', {
		translation: {
			'Z': {
				pattern: /[0-9]/, optional: true
			},
			'X': {
				pattern: /[0-9A-Za-z]/, optional: true
			},
		},
		placeholder: "__ __ __ ____",
		clearIfNotMatch: true
	});
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

	function shownotifyopt()
	{
		var agent_id = $("#bkg_agent_id").select2("val");
		var agentnotifydata = $('#agentnotifydata').val();
		jQuery.ajax({type: 'POST',
			url: '<?= Yii::app()->createUrl('admin/agent/bookingmsgdefaults') ?>',
			dataType: 'html',
			data: {"agent_id": agent_id, "notifydata": agentnotifydata, "YII_CSRF_TOKEN": $('input[name="YII_CSRF_TOKEN"]').val()},
			success: function (data)
			{
				shownotifydiag = bootbox.dialog({
					message: data,
					title: '',
					size: 'large',
					onEscape: function ()
					{
					}
				});
				shownotifydiag.on('hidden.bs.modal', function (e)
				{
					$('body').addClass('modal-open');
				});
				return true;
			},
			error: function (x)
			{
				alert(x);
			}
		});
	}

	function savenotifyoptions()
	{
		jQuery.ajax({type: 'POST',
			url: '<?= Yii::app()->createUrl('admin/agent/bookingmsgdefaults') ?>',
			dataType: 'json',
			data: $('#agent-notification-form').serialize(),
			success: function (data)
			{
				$('#agentnotifydata').val(JSON.stringify(data.data));
				shownotifydiag.hide();
				alert('Notification details saved successfully.');
				return false;
			},
			error: function (x)
			{
				alert(x);
			}
		});
		return false;
	}


</script>
<input id="map_canvas" type="hidden">
<?
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>
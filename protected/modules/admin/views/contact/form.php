<style>
    .select2-container-multi .select2-choices {
        min-height: 50px;
    }
    .new-booking-list .form-horizontal .form-group{
		margin-left: 0;
		margin-right: 0;
	}
    .modal {
		overflow-y:auto;
    }
</style>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
$stateList			 = array("" => "Select state") + CHtml::listData(States::model()->findAll('stt_active = :act AND stt_country_id = :con order by stt_name', array(':act' => '1', ':con' => '99')), 'stt_id', 'stt_name');
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];

$option = array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data');
if (Yii::app()->request->isAjaxRequest)
{
	$option = $option + array('onsubmit' => "return false;", 'onkeypress' => " if(event.keyCode == 13){ send(); } ");
}
?>
<div class="row">
    <div class="col-lg-8 col-md-6 col-sm-8 pb10 new-booking-list" style="float: none; margin: auto">
        <div class="row">    
			<?php
			$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'add_contact_form',
				'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error'
				),
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => $option,
			));
			?><div class="panel panel-default panel-border">
				<div class="panel-body">
					<h3 class="pb10 mt0">Personal Information</h3>
					<div id="msg">
						<?php
						if ($returns->hasErrors())
						{
							?>
							<div class="alert alert-block alert-danger">
								<p>Please fix the following input errors:</p>
								<ul>										
									<li>
										<?php
										if (count($returns->getError('ctt_id')))
										{
											echo "<li>" . $returns->getError('ctt_id')[0] . "</li>";
										}
										if (count($returns->getError('contactEmails')))
										{
											$json = json_decode($returns->getError('contactEmails')[0], true);
											echo "<li>" . $json['ContactEmail_eml_email_address'][0] . "</li>";
										}
										if (count($returns->getError('contactPhones')))
										{
											$json = json_decode($returns->getError('contactPhones')[0], true);
											echo "<li>" . $json['ContactPhone_phn_phone_no'][0] . "</li>";
										}
										if (count($returns->getError('ctt_city')))
										{
											echo "<li>" . $returns->getError('ctt_city')[0] . "</li>";
										}
										?>
									</li>
								</ul>
							</div>
						<?php } ?>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6">
							<label>User Type</label>
							<?= $form->radioButtonListGroup($model, 'ctt_user_type', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Individual', 2 => 'Business')), 'inline' => true)) ?>
							<div id="errorstate" class="mt0" style="color:#da4455"></div>
						</div>
					</div>			
					<div class="row" id="individual" <?php echo $model->ctt_user_type == 2 ? 'style="display:none"' : "" ?>>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<?= $form->textFieldGroup($model, 'ctt_first_name', array()) ?>
							<span id="errorctyname" style="color:#da4455">
							</span>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<?= $form->textFieldGroup($model, 'ctt_last_name', array()) ?>
						</div>
					</div>
					<div class="row" <?php echo $model->ctt_user_type == 1 ? 'style="display:none"' : '' ?> id="business_type">
						<div class="col-xs-12 col-sm-6 col-md-6">
							<label>Business Type</label>
							<?php
							$businessTypesArr = Contact::model()->getJSON([1 => 'Sole Propitership', 2 => 'Partner', 3 => 'Private Limited', 4 => 'Limited']);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'ctt_business_type',
								'val'			 => $model->ctt_business_type,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($businessTypesArr)),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Business Type')
							));
							?>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<?= $form->textFieldGroup($model, 'ctt_business_name', array()) ?>
							<span id="errorctyname" style="color:#da4455">
							</span>
						</div>
					</div>
					<div class="row ">
						<?php $this->renderPartial('emailwidget', ['model' => $model, 'form' => $form]); ?>
						<?php $this->renderPartial('phonewidget', ['model' => $model, 'form' => $form]); ?>
					</div>
					<div class="row">
						<div class="col-xs-6">
							<div class="row">
								<div class="col-xs-12 col-md-12 ">
									<?=
									$form->fileFieldGroup($model, 'ctt_profile_path', array('label' => 'Profile Image', 'widgetOptions' => array()));
									if ($model->ctt_profile_path != '')
									{
										?>
										<div class="row ">
											<div class="col-xs-12 mb15">
												<?php
												if (substr_count($model->ctt_profile_path, "attachments") > 0)
												{
													?>
													<a href="<?= $model->ctt_profile_path ?>" target="_blank"><?= basename($model->ctt_profile_path); ?></a>
													<?php
												}
												else
												{
													?>
													<a href="<?= AttachmentProcessing::ImagePath($model->ctt_profile_path) ?>" target="_blank"><?= basename($model->ctt_profile_path); ?></a>
												<?php } ?>

											</div>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="row">
								<div class="col-xs-12 col-md-12 ">
									<?= $form->textAreaGroup($model, 'ctt_address', array()) ?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6">
							<label class="control-label">State</label>
							<?php
							$dataState = VehicleTypes::model()->getJSON($stateList);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'ctt_state',
								'val'			 => $model->ctt_state,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataState)),
								'htmlOptions'	 => array('style' => 'width:100%', 'id' => 'contact_ctt_state', 'placeholder' => 'Select State')
							));
							?>
							<div id="errorstate" class="mt0" style="color:#da4455">

							</div>
						</div>
						<div class="col-xs-12 c0l-sm-8 col-lg-6 mt5">
							<div class="form-group cityinput">
								<?php
								if (Yii::app()->request->getParam('type') == 3)
								{
									echo '<label class="control-label">City <span class="required">*</span></label>';
								}
								else
								{
									echo '<label>City</label>';
								}

								if ($model->ctt_city != '')
								{

									//$cityList = CHtml::listData(Cities::getName($model->ctt_city), 'ctt_id', 'cty_name');
									$cityList	 = Cities::model()->getCityNameById($model->ctt_city);
									$cityList1	 = CHtml::listData(Cities::model()->getCityNameById($model->ctt_city), 'cty_id', 'cty_name');
								}
								else
								{
									$cityList1 = array("" => "--Select City--");
								}
								echo $form->dropDownListGroup($model, 'ctt_city', array(
									'label'			 => '', 'widgetOptions'	 => array(
										'data'				 => $cityList1,
										'model'				 => $model,
										'attribute'			 => 'ctt_city',
										'useWithBootstrap'	 => true,
										'val'				 => $model->ctt_city,
										'fullWidth'			 => false,
										"placeholder"		 => "Select Source City",
										'htmlOptions'		 => array('width' => '100%',
										),
										'defaultOptions'	 => $selectizeOptions + array(
									'onInitialize'	 => "js:function(){
                                            populateSource(this, '{$model->ctt_city}');
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
								)));

//								$this->widget('ext.yii-selectize.YiiSelectize', array(
//									'model'				 => $model,
//									'attribute'			 => 'ctt_city',
//									'useWithBootstrap'	 => true,
//									"placeholder"		 => "Select Source City",
//									'fullWidth'			 => false,
//									'htmlOptions'		 => array('width'	 => '100%',
//										'id'	 => 'Contact_ctt_city',
//									'data' => $cityList1,
//									),
//									'defaultOptions'	 => $selectizeOptions + array(
//								'onInitialize'	 => "js:function(){
//                                            populateSource(this, '{$model->ctt_city}');
//                                                }",
//								'load'			 => "js:function(query, callback){
//                                            loadSource(query, callback);
//                                            }",
//								'render'		 => "js:{
//                                            option: function(item, escape){
//                                            return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
//                                            },
//                                            option_create: function(data, escape){
//                                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
//                                            }
//                                            }",
//									),
//								));
								?>

							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6">
							<div class="form-group">
								<label>Select Preferred Language</label>
								<?php
								$langArr	 = Contact::model()->language();
								$language	 = Contact::model()->getJSON($langArr);
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'ctt_preferred_language',
									'val'			 => $model->ctt_preferred_language,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($language)),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Preferred Language')
								));
								?>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="form-group">
								<label>Tags</label>
								<?php
								$tagList	 = Tags::getListByType(Tags::TYPE_USER);
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'strTags',
									'val'			 => explode(',',$model->ctt_tags),
									'data'			 => $tagList,
									'htmlOptions'	 => array(
										'multiple'		 => 'multiple',
										'placeholder'	 => 'Add tags keywords ',
										'style'			 => 'width:100%'
									),));
								?>
							</div>
						</div>
						<div class="col-xs-6">

							<div class="form-group">
								<label>Select Known Language</label>
								<?php
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'arr_known_language',
									'val'			 => $model->arr_known_language,
									'data'			 => Contact::language(),
									'htmlOptions'	 => array(
										'multiple'		 => 'multiple',
										'placeholder'	 => 'Known Language',
										'width'			 => '100%',
										'style'			 => 'width:100%',
									),
								));
								?>
								<span class="has-error"><? echo $form->error($model, 'arr_known_language'); ?></span>
							</div>

						</div>
					</div>
				</div>
			</div>				
			<div class="panel panel-default panel-border">
				<div class="panel-body">
					<h3 class="pb10 mt0">Document Info</h3>
					<div class="row">
						<div class="col-xs-12 col-sm-6 ">
							<?= $form->textFieldGroup($model, 'ctt_voter_no', array()) ?>
						</div>
						<div class="col-xs-12 col-sm-6 ">
							<?= $form->textFieldGroup($model, 'ctt_aadhaar_no', array()) ?>
						</div>
					</div> 
					<div class="row">
						<div class="col-xs-12 col-sm-6 ">
							<?= $form->textFieldGroup($model, 'ctt_pan_no', array()) ?>
						</div>
						<div class="col-xs-12 col-sm-6 ">
							<?=
							$form->textFieldGroup($model, 'ctt_license_no',
									array('attribute' => 'ctt_license_no')
							)
							?>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-6">

							<div class="form-group">
								<?=
								$form->datePickerGroup($model, 'locale_license_issue_date', array(
									'label'			 => 'License Issue date',
									'widgetOptions'	 => array(
										'options'		 => array('autoclose'	 => true,
											'endDate'	 => '+0d',
											'format'	 => 'dd/mm/yyyy'),
										'attribute'		 => 'locale_license_issue_date',
										'htmlOptions'	 => array('readonly' => true)), 'groupOptions'	 => ['class' => 'm0'], 'prepend'		 => '<i class="fa fa-calendar"></i>'));
								?>                            
							</div> 
						</div>
						<div class="col-xs-12 col-sm-6 ">


							<div class="form-group">
								<?=
								$form->datePickerGroup($model, 'locale_license_exp_date', array(
									'label'			 => 'License Expiry date',
									'widgetOptions'	 => array(
										'options'		 => array('autoclose'	 => true,
											'attribute'	 => 'locale_license_exp_date',
											'startDate'	 => $model->locale_license_issue_date == "" ? date("d/m/Y") : $model->locale_license_issue_date, 'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array('readonly' => true
										)), 'groupOptions'	 => ['class' => 'm0'], 'prepend'		 => '<i class="fa fa-calendar"></i>'));
								?>                            
							</div>
						</div>
					</div>


					<div class="row">
						<div class="col-xs-12 col-sm-6 ">
							<label class="control-label">License Issuing Authority</label>
							<?php
							$dataState	 = VehicleTypes::model()->getJSON($stateList);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'ctt_dl_issue_authority',
								'val'			 => $model->ctt_dl_issue_authority,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataState)),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select License Issuing Authority')
							));
							?>
						</div>
					</div>

				</div>
			</div>
			<div class="panel panel-default panel-border">
				<div class="panel-body">
					<h3 class="pb10 mt0">Bank Details</h3>
					<div class="row">
						<div class="col-xs-12 col-sm-6 ">
							<?= $form->textFieldGroup($model, 'ctt_bank_name', array()) ?>
						</div>
						<div class="col-xs-12 col-sm-6 ">
							<?= $form->textFieldGroup($model, 'ctt_bank_account_no', array()) ?>
						</div>
					</div> 
					<div class="row">
						<div class="col-xs-12 col-sm-6 ">
							<?= $form->textFieldGroup($model, 'ctt_bank_branch', array()) ?>
						</div>
						<div class="col-xs-12 col-sm-6 ">
							<?= $form->textFieldGroup($model, 'ctt_bank_ifsc', array()) ?>
						</div>
					</div> 
					<div class="row">
						<div class="col-xs-12 col-sm-6 ">
							<label>Account Type</label>
							<?= $form->radioButtonListGroup($model, 'ctt_account_type', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Current', 0 => 'Savings')), 'inline' => true)) ?>
						</div>
					</div>							
					<div class="row">
						<div class="col-xs-12 col-sm-6 ">
							<?= $form->textFieldGroup($model, 'ctt_beneficiary_name', array()) ?>
						</div>
						<div class="col-xs-12 col-sm-6">
							<?= $form->textFieldGroup($model, 'ctt_beneficiary_id', array()) ?>

						</div>
					</div> 
				</div>
			</div>				
			<div class="" style="text-align: center">
				<?php
				if (Yii::app()->request->isAjaxRequest && $type != 4)
				{
					echo CHtml::Button("Add", array('class' => 'btn btn-primary', 'onclick' => 'send();'));
				}
				else
				{
					if ($type != 4):
						echo CHtml::submitButton($isNew, array('class' => 'btn btn-primary'));
					endif;
				}
				?>
			</div>
			<?php $this->endWidget(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		$(".bootbox").removeAttr("tabindex");

		$('#contact_ctt_state').change(function () {
			$state = $('#contact_ctt_state').val();

			$('#Contact_ctt_city').html("");
			$('#Contact_ctt_city').append($('<option>').text("--Select City --").attr('value', ""));
			if ($state != "")
			{
				$.ajax({
					"type": "GET",
					"dataType": "json",
					"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/getcitybystate')) ?>",
					"data": {"state": $state},
					"success": function (data) {
						$.each(data, function (key, value) {
							$('#Contact_ctt_city').append($('<option>').text(value).attr('value', key));
						});
					}
				});
			}
		});
<?php
if ($type == 4)
{
	?>
			$.each($('#add_contact_form').serializeArray(), function (index, value) {
				$('[name="' + value.name + '"]').attr('readonly', 'readonly');
			});
			$("#add_contact_form input[type=radio]").attr('disabled', true);
			$("#add_contact_form input[type=select]").attr('disabled', true);
			$("#fieldBeforeEmail").remove();
			$("#fieldAfterEmail").remove();
			$("#fieldAfterPhone").remove();
			$("#fieldBeforePhone").remove();
<?php } ?>
		$("#errorctyname").text('');
		$("#errorstate").text('');
		$("#Contact_ctt_user_type_0").click(function () {
			$("#individual").show();
			$("#business").hide();
			$("#business_type").hide();
		});
		$("#Contact_ctt_user_type_1").click(function () {
			$("#individual").hide();
			$("#business").show();
			$("#business_type").show();
		});
		$("#fieldAfterEmail").click(function () {
			var elems = $("div.clsRouteEmail");
			var len = elems.length;
			var colValues = "";
			if (len > 0) {
				colValues = $("#ContactEmail_eml_email_address" + parseInt(len - 1)).val();
			} else {
				colValues = $("#ContactEmail_eml_email_address").val();
			}
			if (colValues != "") {
				$('#fieldBeforeEmail').show();
				$('#insertBeforeEmail').before('<div class="clsRouteEmail"><div class="form-group"><label class="control-label required" for="ContactEmail_eml_email_address">Secondary Email <span class="required">*</span></label><input class="form-control" placeholder="Email" name="ContactEmail[][eml_email_address]" id="ContactEmail_eml_email_address' + len + '" type="text" maxlength="100" onblur="checkEmail(' + (len + 1) + ')"><div class="help-block error" id="ContactEmail_eml_email_address_' + len + '" style="display:none"></div></div><div class="form-group"> <input id="ytContactEmail_' + (len + 1) + '_eml_is_primary" type="hidden" value="" name="ContactEmail[' + (len + 1) + '] [eml_is_primary]"><span id="ContactEmail_' + (len + 1) + '_eml_is_primary"><label class="checkbox-inline"><div class="radio" id="uniform-ContactEmail_' + (len + 1) + '_eml_is_primary_0"><span><input onclick="checkPrimaryEmail(' + (len + 1) + ')" placeholder="[' + (len + 1) + ']phn Is Primary" id="ContactEmail_' + (len + 1) + '_eml_is_primary_0" value="0" type="radio" name="ContactEmail[' + (len + 1) + '][eml_is_primary]"></span></div>Primary Email</label></span><div class="help-block error" id="ContactEmail_' + (len + 1) + '_eml_is_primary_em_" style="display:none">   <input id="ytContactEmail_' + (len + 1) + '_eml_type" type="hidden" value="" name="ContactEmail[' + (len + 1) + '][eml_type]"><span id="ContactEmail_' + (len + 1) + '_eml_type"><label class="checkbox-inline"><div class="radio" id="uniform-ContactEmail_' + (len + 1) + '_eml_is_type_0"><span><input onclick="checkPrimaryEmail(' + (len + 1) + ')" placeholder="[' + (len + 1) + ']phn Is Primary" id="ContactEmail_' + (len + 1) + '_eml_type_0" value="0" type="radio" name="ContactEmail[' + (len + 1) + '][eml_type]"></span></div>Primary Email</label></span><div class="help-block error" id="ContactEmail_' + (len + 1) + '_eml_em_" style="display:none"></div></div></div>');
			} else {
				bootbox.alert("Please enter email address");
			}
		});
		$("#fieldBeforeEmail").click(function () {

			var elems = $("div.clsRouteEmail");
			var len = elems.length;
			if (len < 2) {
				$('#fieldBeforeEmail').hide();
			}
			$($(".clsRouteEmail")[len - 1]).remove();
		});
		$("#fieldAfterPhone").click(function () {
			var elems = $("div.clsRoutePhone");
			var len = elems.length;
			if (len > 0) {
				colValues = $("#ContactPhone_phn_phone_no" + parseInt(len - 1)).val();
			} else {
				colValues = $("#ContactPhone_phn_phone_no").val();
			}
			if (colValues != "") {
				$('#fieldBeforePhone').show();
				$('#insertBeforePhone').before('<div class="clsRoutePhone"><div class="form-group"><label class="control-label required" for="ContactPhone_phn_phone_no">Secondary Phone <span class="required">*</span></label><input class="form-control" placeholder="Phone" name="ContactPhone[][phn_phone_no]" onblur="checkPhone(' + (len + 1) + ')" id="ContactPhone_phn_phone_no' + len + '" type="text" maxlength="50"><div class="help-block error" id="ContactPhone_phn_phone_no_' + len + '" style="display:none"></div></div><div class="form-group"> <input id="ytContactPhone_' + (len + 1) + '_phn_is_primary" type="hidden" value="" name="ContactPhone[' + (len + 1) + '][phn_is_primary]"><span id="ContactPhone_' + (len + 1) + '_phn_is_primary"><label class="checkbox-inline"><div class="radio" id="uniform-ContactPhone_' + (len + 1) + '_phn_is_primary_0"><span><input onclick="checkPrimaryPhone(' + (len + 1) + ')" placeholder="[' + (len + 1) + ']phn Is Primary" id="ContactPhone_' + (len + 1) + '_phn_is_primary_0" value="0" type="radio" name="ContactPhone[' + (len + 1) + '][phn_is_primary]"></span></div>Primary Phone</label></span><div class="help-block error" id="ContactPhone_' + (len + 1) + '_phn_is_primary_em_" style="display:none"></div></div>    <div class="form-group"> <input id="ytContactPhone_' + (len + 1) + '_phn_type" type="hidden" value="" name="ContactPhone[' + (len + 1) + '][phn_type]"><span id="ContactPhone_' + (len + 1) + '_phn_type"><label class="checkbox-inline"><div class="radio" id="uniform-ContactPhone_' + (len + 1) + '_phn_type_0"><span><input onclick="checkPrimaryPhone(' + (len + 1) + ')" placeholder="[' + (len + 1) + ']phn Is Primary" id="ContactPhone_' + (len + 1) + '_phn_type_0" value="0" type="radio" name="ContactPhone[' + (len + 1) + '][phn_type]"></span></div>Primary Phone</label></span><div class="help-block error" id="ContactPhone_' + (len + 1) + '_phn_type_em_" style="display:none"></div></div></div>');
			} else {
				bootbox.alert("Please enter phone number");

			}

		});
		$("#fieldBeforePhone").click(function () {
			var elems = $("div.clsRoutePhone");
			var len = elems.length;
			if (len < 2) {
				$('#fieldBeforePhone').hide();
			}
			$($(".clsRoutePhone")[len - 1]).remove();
		});

<?php
if ($drvconttype == "1")
{
	?>   $("#Contact_ctt_user_type_1").attr('disabled', true);
<?php } ?>
	});
	$sourceList1 = null;
	function populateSource(obj, cityId) {
		obj.load(function (callback)
		{
			var obj = this;
			if ($sourceList1 == null)
			{
				xhr = $.ajax({
					url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1])) ?>',
					dataType: 'json',
					data: {
						// city: cityId
					},
					//  async: false,
					success: function (results)
					{
						$sourceList1 = results;
						obj.enable();
						callback($sourceList1);
						obj.setValue(cityId);
					},
					error: function ()
					{
						callback();
					}
				});
			} else
			{
				obj.enable();
				callback($sourceList1);
				obj.setValue(cityId);
			}
		});
	}
	function loadSource(query, callback) {
		//	if (!query.length) return callback();
		$.ajax({
			url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=1&q=' + encodeURIComponent(query),
			type: 'GET',
			dataType: 'json',
			global: false,
			error: function ()
			{
				callback();
			},
			success: function (res)
			{
				callback(res);
			}
		});
	}
	function checkEmail(textFieldslength) {
		var elems = $("div.clsRouteEmail");
		var len = elems.length;
		for (i = 0; i < len; i++) {
			var textId = textFieldslength == 0 ? "" : parseInt(textFieldslength - 1);
			if (i == 0) {
				var colValues = $("#ContactEmail_eml_email_address").val();
				var Values = $("#ContactEmail_eml_email_address" + textId).val();
				if (textFieldslength != i && colValues.trim() == Values.trim()) {
					bootbox.dialog({
						title: 'Alert',
						message: '<div class="alert alert-danger"><strong>Warning!</strong> "' + Values.trim() + '" already present in list</div>',
						closeButton: false,
						size: 'medium',
						buttons: {
							Ok: {
								label: "Ok",
								className: 'btn-primary',
								callback: function () {
									$("#ContactEmail_eml_email_address" + textId).val('');
								}
							}
						}
					});
					break;
				}
			} else {
				var colValues = $("#ContactEmail_eml_email_address" + parseInt(i - 1)).val();
				var Values = $("#ContactEmail_eml_email_address" + textId).val();
				if (textFieldslength != i && colValues.trim() == Values.trim()) {
					bootbox.dialog({
						title: 'Alert',
						message: '<div class="alert alert-danger"><strong>Warning!</strong> "' + Values.trim() + '" already present in list</div>',
						closeButton: false,
						size: 'medium',
						buttons: {
							Ok: {
								label: "Ok",
								className: 'btn-primary',
								callback: function () {
									$("#ContactEmail_eml_email_address" + textId).val('');
								}
							}
						}
					});
					break;
				}
			}
		}
	}
	function checkPhone(textFieldslength) {
		var elems = $("div.clsRoutePhone");
		var len = elems.length;
		for (i = 0; i < len; i++) {
			var textId = textFieldslength == 0 ? "" : parseInt(textFieldslength - 1);
			if (i == 0) {
				var colValues = $("#ContactPhone_phn_phone_no").val();
				var Values = $("#ContactPhone_phn_phone_no" + textId).val();
				if (textFieldslength != i && colValues.trim() == Values.trim()) {
					bootbox.dialog({
						title: 'Alert',
						message: '<div class="alert alert-danger"><strong>Warning!</strong> "' + Values.trim() + '" already present in list</div>',
						closeButton: false,
						size: 'medium',
						buttons: {
							Ok: {
								label: "Ok",
								className: 'btn-primary',
								callback: function () {
									$("#ContactPhone_phn_phone_no" + textId).val('');
								}
							}
						}
					});
					break;
				}
			} else {
				var colValues = $("#ContactPhone_phn_phone_no" + parseInt(i - 1)).val();
				var Values = $("#ContactPhone_phn_phone_no" + textId).val();
				if (textFieldslength != i && colValues.trim() == Values.trim()) {
					bootbox.dialog({
						title: 'Alert',
						message: '<div class="alert alert-danger"><strong>Warnng!</strong> "' + Values.trim() + '" already present in list</div>',
						closeButton: false,
						size: 'medium',
						buttons: {
							Ok: {
								label: "Ok",
								className: 'btn-primary',
								callback: function () {
									$("#ContactPhone_phn_phone_no" + textId).val('');
								}
							}
						}
					});
					break;
				}
			}
		}
	}

	function send()
	{
		var form = $("#add_contact_form");
		var formData = new FormData(form[0]);
		let licenceNumber = $('#Contact_ctt_license_no').val();
		let licenceissueDate = $('#Contact_locale_license_issue_date').val();
		let licenceAuthority = $('#Contact_ctt_dl_issue_authority').val();
		let licExpDate = $('#Contact_locale_license_exp_date').val();
		if (licenceNumber === "")
		{
			alert("License Number should not be blank");
			return false;
		} else if (licenceissueDate === "")
		{
			alert("License Issue Date should not be blank");
			return false;
		} else if (licExpDate === "")
		{
			alert("License Expiry Date should not be blank");
			return false;
		} else if (licenceAuthority === "")
		{
			alert("License Authority should not be blank");
			return false;
		}
//        else if(emailAddress === "")
//        {
//            alert("Email Address should not be blank");
//        }
		else
		{

			$.ajax({
				type: 'POST',
				url: '<?php echo CHtml::normalizeUrl(Yii::app()->request->url); ?>',
				data: formData,
				dataType: 'json',
				processData: false,
				contentType: false,
				success: function (data)
				{
					if (data.success)
					{
						$("#msg").html("");
						$("#msg").html('<div class="alert alert-success"><p></p><ul><li>Contact Added Successfully</li></ul></div>');
						$('#Vendors_vnd_contact_name').val(data.ContactName);
						$('#Vendors_vnd_contact_id').val(data.ContacId);
						$('#Drivers_drv_contact_name').val(data.ContactName);
						$('#Drivers_drv_contact_id').val(data.ContacId);
						$("#contactDetails").text(data.ContactName + ' | ' + data.ContactEmail + ' | ' + data.ContactPhone + ' | License: ' + data.ContactLicense);
						$(".contact_div_details").removeClass('hide');
						$(".viewcontctsearch").removeClass('hide');
						$('.addContact').hide();
					} else
					{
						$("#msg").html("");
						$("#msg").html('<div class="alert alert-block alert-danger"><p>Please fix the following input errors:</p><ul><li>' + data.message + '</li></ul></div>');
					}
				},
				error: function ()
				{
					alert("Error occured.please try again");
				},
			});

		}
	}
</script>

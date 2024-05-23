
<?php
$stateList			 = array("" => "Select state") + CHtml::listData(States::model()->findAll('stt_active = :act AND stt_country_id = :con order by stt_name', array(':act' => '1', ':con' => '99')), 'stt_id', 'stt_name');
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>

<div class="container p15">
	<div class="row">
		<div class="col-12">  
			<div class="card">  
				<div  class="list-group-item list-group-item-action pl10"><a href="/operator/register"><i class="bx bx-chevrons-left float-left text-success "></i></a> Basic Info</div> 

				<div id="basicInfoPanel" class="card-body">
					<div class="formBody">
						<?php
						$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
							'id'					 => 'add_contact_form',
							'enableClientValidation' => true,
							'clientOptions'			 => array(
								'validateOnSubmit'	 => true,
								'errorCssClass'		 => 'has-error',
								'afterValidate'		 => 'js:function(form,data,hasError){
			if(!hasError){
				$.ajax({
					"type":"POST",
					"dataType":"json",
					async: false,
					"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('operator/validatebasic')) . '",
					"data":form.serialize(),
					"beforeSend": function () {
						ajaxindicatorstart("");
					},
					"complete": function () {                     
						ajaxindicatorstop();
					},
					"success":function(data1){
						if(data1.success){
						alert(data1.message); 
						location.href = data1.url;
					 												 
							 return false;
						} else{						 
							alert("no error shown"); 
						} 
					},                    
				});
			}									
		}'
							),
							'enableAjaxValidation'	 => false,
							'errorMessageCssClass'	 => 'help-block',
							'htmlOptions'			 => $option,
						));
						?>  
						<input type="hidden" name="isDCO" value="<?php echo $isDCO ?>">
						<div class="row">
							<div class="col-sm-12">
								<div class="row">
									<div class="col-xs-12 col-sm-6 col-md-6">
										<?= $form->textFieldGroup($cttmodel, 'ctt_first_name', array()) ?>
										<span id="errorctyname" style="color:#da4455"></span>
									</div>
									<div class="col-xs-12 col-sm-6 col-md-6">
										<?= $form->textFieldGroup($cttmodel, 'ctt_last_name', array()) ?>
									</div>
								</div>
								<div class="row" <?php echo $cttmodel->ctt_user_type == 1 ? 'style="display:none"' : '' ?> id="business_type">
									<div class="col-xs-12 col-sm-6 col-md-6">
										<label>Business Type</label>
										<?php
										$businessTypesArr	 = Contact::model()->getJSON([1 => 'Sole Propitership', 2 => 'Partner', 3 => 'Private Limited', 4 => 'Limited']);
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $cttmodel,
											'attribute'		 => 'ctt_business_type',
											'val'			 => $cttmodel->ctt_business_type,
											'asDropDownList' => FALSE,
											'options'		 => array('data' => new CJavaScriptExpression($businessTypesArr)),
											'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Business Type')
										));
										?>
									</div>
									<div class="col-xs-12 col-sm-6 col-md-6">
										<?php echo $form->textFieldGroup($cttmodel, 'ctt_business_name', array()) ?>
										<span id="errorctyname" style="color:#da4455">
										</span>
									</div>
								</div> 
								<div class="row hide">
									<div class="col-sm-12">
										<div class="form-group">
											<input type="checkbox" id="showLicense" name="isDCO" value="1"  > <label class="control-label"> Is Driver Cum Operator</label>
										</div>
									</div>
								</div>
								<div class="row hide" id="licenseBlock" style="display: none">
									<div class="col-sm-12">
										<?php echo $form->textFieldGroup($cttmodel, 'ctt_license_no', array()) ?>
										<span id="errorctyname" style="color:#da4455"></span>
									</div>
								</div>
								<div class="row ">
									<div class="col-xs-12 col-sm-6 col-md-6">
										<div class="form-group">
											<label class="control-label" for="">Email*</label>
											<fieldset class="form-group position-relative has-icon-left">
												<?php
												if (trim($cttmodel->contactEmails[0]->eml_email_address) == '')
												{
													?>
													<input type="text" value="" placeholder="Email" id="ContactEmail" name="contactEmails[eml_email_address]"  class="form-control">
													<?php
												}
												else
												{
													?>
													<div  class="form-control disabled"><?php echo $cttmodel->contactEmails[0]->eml_email_address ?></div>
												<?php }
												?><div class="form-control-position">
													<i class='bx bx-envelope'></i>
												</div>
											</fieldset>
										</div>
									</div>
									<div class="col-xs-12 col-sm-6 col-md-6">
										<div class="form-group">
											<label class="control-label" for="">Phone*</label>
											<fieldset class="form-group position-relative has-icon-left">
												<div  class="form-control disabled"><?php echo $cttmodel->contactPhones[0]->phn_phone_no ?></div>
												<div class="form-control-position">
													<i class="bx bx-phone-call"></i>
												</div>
											</fieldset>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12 col-md-12 ">
										<?= $form->textAreaGroup($cttmodel, 'ctt_address', array()) ?>
									</div>
								</div> 
								<div class="row">
									<div class="col-xs-12 col-sm-6 col-md-6">
										<div class="form-group cityinput">
											<label class="control-label">State</label>
											<?php
											$dataState = VehicleTypes::model()->getJSON($stateList);
											$this->widget('booster.widgets.TbSelect2', array(
												'model'			 => $cttmodel,
												'attribute'		 => 'ctt_state',
												'val'			 => $cttmodel->ctt_state,
												'asDropDownList' => FALSE,
												'options'		 => array('data' => new CJavaScriptExpression($dataState)),
												'htmlOptions'	 => array('style' => 'width:100%', 'id' => 'contact_ctt_state', 'class' => 'form-control', 'placeholder' => 'Select State')
											));
											?>
											<div id="errorstate" class="mt0" style="color:#da4455">
											</div>
										</div>
									</div>
									<div class="col-xs-12 col-lg-6 ">
										<div class="form-group cityinput">
											<label class="control-label hide">City <span class="required">*</span></label> 
											<?php
											if ($cttmodel->ctt_city != '')
											{
												$cityList	 = Cities::model()->getCityNameById($cttmodel->ctt_city);
												$cityList1	 = CHtml::listData(Cities::model()->getCityNameById($cttmodel->ctt_city), 'cty_id', 'cty_name');
											}
											else
											{
												$cityList1 = array("" => "--Select City--");
											}
											echo $form->dropDownListGroup($cttmodel, 'ctt_city', array(
												'label'			 => 'City', 'widgetOptions'	 => array(
													'data'				 => $cityList1,
													'model'				 => $cttmodel,
													'attribute'			 => 'ctt_city',
													'useWithBootstrap'	 => true,
													'val'				 => $cttmodel->ctt_city,
													'fullWidth'			 => false,
													"placeholder"		 => "Select Source City",
													'htmlOptions'		 => array('style'	 => 'width:100%', 'class'	 => 'select2-input'
													),
													'defaultOptions'	 => $selectizeOptions + array(
												'onInitialize'	 => "js:function(){
                                            populateSource(this, '{$cttmodel->ctt_city}');
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
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="" style="text-align: center">
							<?php
							echo CHtml::submitButton('Save', array('class' => 'btn btn-primary'));
							?>
						</div>
						<?php $this->endWidget(); ?>
					</div>
				</div>
			</div>
		</div>

	</div>
</div> 
<script type="text/javascript">
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

</script>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/dco/register.js?" . rand(1, 999));
?>
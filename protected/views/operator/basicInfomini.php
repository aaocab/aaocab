<style type="text/css">
    .cityinput > .selectize-control>.selectize-input, .cityinput>.selectize-input{
        width:100% !important;
    }
	.selectize-dropdown [data-selectable] {
		cursor: pointer;
		overflow: hidden;
		padding: 5px;
	}


</style>
<?php
//$cttModel->ctt_city	 = null;
$stateList			 = array("" => "Select state") + CHtml::listData(States::model()->findAll('stt_active = :act AND stt_country_id = :con order by stt_name', array(':act' => '1', ':con' => '99')), 'stt_id', 'stt_name');
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>

<div id="basicInfoPanel" role="tabpanel" data-parent="#accordionWrapa1" aria-labelledby="basicInfo" class="collapse" style="">
	<a type="button" href="/operator/register" class="col-md-12 font-weight-bold p5"><i class="bx bx-arrow-back float-left "> </i> Go back </a>
	<div class="row">
		<a type="button" href="/operator/register" class="col-md-12">
			<div class="list-group-item pl10">
				<i class="bx bx-chevrons-left float-left text-success "></i>Basic Info </div> </a>
	</div>
	<div class="card card-body  ">

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
								location.href = data1.url;
									 return false;
								} else{	
									if (data1.hasOwnProperty("errors")) 
									{
				                        errors = data1.errors.join("</li><li>");
				                    }                    
									var message = "<div class=\'errorSummary\'><ul style=\'list-style-type: none\'><li>" + errors + "</li></ul></div>";
				                    showInfo(message); 
								}
							},
						});
					}									
				}'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class' => 'form-horizontal',
			),
		));
		?>  
		<input type="hidden" name="formType" value="opserv">
		<input type="hidden" name="isDCO" value="<?php echo $isDCO ?>">
		<div class="row">
			<div class="col-sm-12">
				<?
				$form->errorSummary($cttModel);
//				echo CHtml::errorSummary($cttModel);
				?>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">
						<?= $form->textFieldGroup($cttModel, 'ctt_first_name', array("label" => "First Name*")) ?>

					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">
						<?= $form->textFieldGroup($cttModel, 'ctt_last_name', array("label" => "Last Name*")) ?>
					</div>
				</div>
				<div class="row ">
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group">
							<label class="control-label" for="">Email</label>
							<fieldset class="form-group position-relative has-icon-left">
								<?php
								if (trim($cttModel->contactEmails[0]->eml_email_address) == '')
								{
									?>
									<input type="text" value="" placeholder="Email" id="ContactEmail" name="contactEmails[eml_email_address]"  class="form-control">
									<?php
								}
								else
								{
									?>
									<div  class="form-control disabled"><?php echo $cttModel->contactEmails[0]->eml_email_address ?></div>
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
								<?php
								if (trim($cttModel->contactPhones[0]->phn_phone_no) == '')
								{
									?>
									<input type="text" value="" placeholder="Phone" id="ContactPhone" name="contactPhone[phn_phone_no]"  class="form-control">
									<?php
								}
								else
								{
									?>
									<div  class="form-control disabled"><?php echo $cttModel->contactPhones[0]->phn_phone_no ?></div>
								<?php }
								?>
								<div class="form-control-position">
									<i class="bx bx-phone-call"></i>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-12 ">
						<?= $form->textAreaGroup($cttModel, 'ctt_address', array()) ?>
					</div>
				</div> 
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group cityinput">
							<input type="hidden" id="temp_state" value="<?= $cttModel->ctt_state ?>">
							<label  for="Contact_ctt_state"> State *</label>

							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $cttModel,
								'attribute'			 => 'ctt_state',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "-Select State-",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width'	 => '100%',
									'id'	 => 'Contact_ctt_state'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){  populateState(this, '{$cttModel->ctt_state}'); }",
							'onChange'		 => "js:function(value) { populateCitylist(value, \$dest_city); }",
							'render'		 => "js:{
											option: function(item, escape){
											return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
											},
											option_create: function(data, escape){
											return '<div><span class=\"\">' + escape(data.text) + '</span></div>';
											} }",
								),
							));
							?> 
							<div class="help-block error" id="Contact_ctt_state_em_" style="display:none"></div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group cityinput">
							<label for="Contact_ctt_city"> City*</label>
							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $cttModel,
								'attribute'			 => 'ctt_city',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "-Select City-",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width' => '50%'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){ \$dest_city=this; }",
							'load'			 => "js:function(query, callback){
												loadCity(query, callback);
												}",
							'render'		 => "js:{
												option: function(item, escape){
												return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';                          
												},
												option_create: function(data, escape){
												 return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(data.text) + '</span></div>';
											   }}",
								),
							));
							?>
							<span class="has-error"><?php echo $form->error($cttModel, 'ctt_city'); ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="" style="text-align: center">
			<?php
			echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary'));
			?>
		</div>
		<?php $this->endWidget(); ?>
	</div>

</div>
<script type="text/javascript">
	$sourceList = null;
	function populateState(obj, stateId) {
		obj.load(function (callback) {
			var obj = this;
			if ($sourceList == null) {
				xhr = $.ajax({
					url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allStatelist')) ?>',
					dataType: 'json',
					data: {
					},
					success: function (results) {
						$sourceList = results;
						obj.enable();
						callback($sourceList);
						obj.setValue(stateId);
					},
					error: function () {
						callback();
					}
				});
			} else {
				obj.enable();
				callback($sourceList);
				obj.setValue(stateId);
			}
		});
	}

	function populateCitylist(value, obj, query = '')
	{
		if (!value.length)
			return;
		var existingValue = '';
		var tempState = $('#temp_state').val();

		if (existingValue == '')
		{
			existingValue = '<?= $cttModel->ctt_city ?>';
		}
		if (tempState != '' && tempState != value)
		{
			existingValue = '';
		}
		obj.disable();
		obj.clearOptions();
		obj.load(function (callback)
		{
			//  xhr && xhr.abort();
			xhr = $.ajax({
				url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylistbyState')) ?>',
				dataType: 'json',
				data: {
					state: value, cityId: existingValue
				},
				success: function (results)
				{
					obj.enable();
					callback(results);
					obj.setValue(existingValue);
				},
				error: function ()
				{
					callback();
				}
			});
		});
	}
	function loadCity(query, callback)
	{

		var value = $('#Contact_ctt_state').val();
		if (!value) {
			var message = "<div class=\'errorSummary\'><ul style=\'list-style-type: none\'><li>" + 'First select state' + "</li></ul></div>";
			showInfo(message);      
			return;
		}
		var seachTxt = encodeURIComponent(query);
		$.ajax({
			url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylistbyState')) ?>',
			type: 'GET',
			dataType: 'json',
			data: {
				state: value, seachTxt: seachTxt
			},
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
	function showInfo(message) {
		toastr["error"](message, "Failed to proceed !", {
			                        closeButton: true,
			                        tapToDismiss: false,
			                        timeout: 500000
		                    });
	}
</script>

<?php
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/aao/chat.js?v=' . $version);
?>

<style>
	.form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
	.zones-style input{ height: 50px;}
</style>
<?php
if (Yii::app()->request->isAjaxRequest)
{
	$cls = "";
}
else
{
	$cls = "col-lg-7 col-md-8 col-sm-12 pb10";
}
?>
<div class="row">
    <div class="<?= $cls ?> new-booking-list" style="float: none; margin: auto">
		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'chatForm',
			'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
				'afterValidate'		 => 'js:function(form,data,hasError)
				{
						$.ajax({
							"type":"POST",
							"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/broadcastNotification/add')) . '",
							"data":form.serialize(),
							"dataType": "json",
							"success":function(data1)
							{
								if(data1.success)
								{
									
									getRefresh();
								}
								else
								{
									
								}
							}
						});
				}'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class' => '',
			)
		));
		/* @var $form TbActiveForm */
		?>
		<?php echo CHtml::errorSummary($model); ?>
        <div class="panel panel-white">
            <div class="panel-body">
				<h2 id ='msg'></h2>
				<?php echo CHtml::errorSummary($model); ?>
                <div class="row mb20">
					<div class="col-xs-12 mb20">
						<div class="row zones-style">
							<div class="col-xs-12 col-md-5"><label> Send Message To: </label></div>
							<div class="col-xs-12 col-md-7">
								<?php
								if ($model->isNewRecord)
								{
									$model->bcn_user_type = 1;
								}
								?>
								<?= $form->radioButtonListGroup($model, 'bcn_user_type', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Vendor', 2 => 'Driver', 3 => 'Consumer')), 'inline' => true), array(1 => 'checked')); ?>

							</div>
						</div>
					</div>
					<div class="col-xs-12 mb20" id="vndName">
						<div class="row">
							<div class="col-xs-12 col-md-5"><label> Select Vendor: (If blank it will not apply) </label></div>
							<div class="col-xs-12 col-md-7">
								<?php
								$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
									'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
									'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
									'openOnFocus'		 => true, 'preload'			 => false,
									'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
									'addPrecedence'		 => false,];
								$this->widget('ext.yii-selectize.YiiSelectize', array(
									'model'				 => $model,
									'attribute'			 => 'bcn_vendor',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Select Vendor",
									'fullWidth'			 => false,
									'htmlOptions'		 => array('width' => '100%','multiple'		 => 'multiple',),
									'defaultOptions'	 => $selectizeOptions + array(
								'onInitialize'	 => "js:function(){
                                  populateVendor(this, '{$model->bcn_vendor}');
                                }",
								'load'			 => "js:function(query, callback){
                                loadVendor(query, callback);
                                }",
								'render'		 => "js:{
                                option: function(item, escape){
                                return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
                                },
                                option_create: function(data, escape){
                                return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                }
                                }",
									),
								));
								?>

								<span class="has-error"><? echo $form->error($model, 'bcn_vendor'); ?></span>
							</div>
						</div>
					</div>
					<div class="col-xs-12 mb20">
						<div class="row zones-style">
							<div class="col-xs-12 col-md-5"><label> Region: (If blank it will not apply)</label></div>
							<div class="col-xs-12 col-md-7">
								<?php
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'bcn_region',
									'val'			 => $model->bcn_region,
									//'asDropDownList' => FALSE,
									'data'			 => States::model()->findRegionName(),
									//'options' => array('data' => new CJavaScriptExpression($regionList), 'allowClear' => true),
									'htmlOptions'	 => array('class'			 => 'p0', 'multiple'		 => 'multiple',
										'style'			 => 'width: 100%', 'placeholder'	 => 'Select Region')
								));
								?>
							</div>
						</div>
					</div>
					<div id="vendor_driver">
						<div class="col-xs-12 mb20" id="zone_driver">
							<div class="row zones-style">
								<div class="col-xs-12 col-md-5"><label> Select Zones: (If blank it will not apply) </label></div>
								<div class="col-xs-12 col-md-7">
									<?php
									$loc				 = Zones::model()->getZoneList();
									$SubgroupArray		 = CHtml::listData(Zones::model()->getZoneList(), 'zon_id', function ($loc) {
												return $loc->zon_name;
											});
									$this->widget('booster.widgets.TbSelect2', array(
										'attribute'		 => 'bcn_zon_name',
										'model'			 => $model,
										'data'			 => $SubgroupArray,
										'val'			 => explode(',', $model->bcn_zon_name),
										'htmlOptions'	 => array(
											'multiple'		 => 'multiple',
											'placeholder'	 => 'Zones',
											'width'			 => '100%',
											'style'			 => 'width:100%;'
										),
									));
									?>
									<span class="has-error"><? echo $form->error($model, 'bcn_zon_name'); ?></span>
								</div>
							</div>
						</div>
						<div class="col-xs-12 mb20">
							<div class="row">
								<div class="col-xs-12 col-md-5">
									<label>Who Have Loggedin</label>
								</div>
								<div class="col-xs-12 col-md-3">
									<?php
									if ($model->isNewRecord)
									{
										$model->bcn_loggedIn_option = 1;
									}
									?>
									<?php
									$vndLoginOption = BroadcastNotification::model()->getLoginOptions();
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'bcn_loggedIn_option',
										'val'			 => $model->bcn_loggedIn_option,
										'asDropDownList' => FALSE,
										'options'		 => array(
											'data'			 => new CJavaScriptExpression(VehicleTypes::model()->getJSON($vndLoginOption)), 'allowClear'	 => true, 'placeholder'	 => 'Have'),
										'htmlOptions'	 => array('style' => 'width:100%', array(1 => 'checked'))
									));
									?>
								</div>
								<div class="col-xs-12 col-md-4">
									<?php
									if ($model->isNewRecord)
									{
										$model->bcn_last_loggedIn = 60;
									}
									?>
									<?php
									$loggedInData = BroadcastNotification::model()->getLoggedInData();
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'bcn_last_loggedIn',
										'val'			 => $model->bcn_last_loggedIn,
										'asDropDownList' => FALSE,
										'options'		 => array(
											'data'			 => new CJavaScriptExpression(VehicleTypes::model()->getJSON($loggedInData)), 'allowClear'	 => False, 'placeholder'	 => 'Select Last Login'),
										'htmlOptions'	 => array('style' => 'width:100%')
									));
									?>
								</div>
							</div>
						</div>


						<div class="col-xs-12 mb20">
							<div class="row">
								<div class="col-xs-12 col-md-5">
									<label>Current Rating</label>
								</div>
								<div class="col-xs-3">
									<?php
									if ($model->isNewRecord)
									{
										$model->bcn_rating_option = 1;
									}
									?>
									<?php
									$currentRatingOption = BroadcastNotification::model()->getRatingOption();
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'bcn_rating_option',
										'val'			 => $model->bcn_rating_option,
										'asDropDownList' => FALSE,
										'options'		 => array(
											'data'			 => new CJavaScriptExpression(VehicleTypes::model()->getJSON($currentRatingOption)), 'allowClear'	 => true, 'placeholder'	 => 'More then'),
										'htmlOptions'	 => array('style' => 'width:100%', array(1 => 'checked'))
									));
									?>
								</div>
								<div class="col-xs-4">
									<?php
									$vndRatingData		 = BroadcastNotification::model()->getRating();
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'bcn_current_rating',
										'val'			 => $model->bcn_current_rating,
										'asDropDownList' => FALSE,
										'options'		 => array(
											'data'			 => new CJavaScriptExpression(VehicleTypes::model()->getJSON($vndRatingData)), 'allowClear'	 => true, 'placeholder'	 => 'Rating'),
										'htmlOptions'	 => array('style' => 'width:100%')
									));
									?>
								</div>

							</div>
						</div>
					</div>

					<div id="customer" >
						<div class="col-xs-12 mb20">
							<div class="row">
								<div class="col-xs-12 col-md-5">
									<label>Who Have Loggedin</label>
								</div>
								<div class="col-xs-12 col-md-3">
									<?php
									if ($model->isNewRecord)
									{
										$model->bcn_customer_loggedIn_option = 1;
									}
									?>
									<?php
									$custLoginOption	 = BroadcastNotification::model()->getLoginOptions();
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'bcn_customer_loggedIn_option',
										'val'			 => $model->bcn_customer_loggedIn_option,
										'asDropDownList' => FALSE,
										'options'		 => array(
											'data'			 => new CJavaScriptExpression(VehicleTypes::model()->getJSON($custLoginOption)), 'allowClear'	 => true, 'placeholder'	 => 'Have'),
										'htmlOptions'	 => array('style' => 'width:100%', array(1 => 'checked'))
									));
									?>
								</div>
								<div class="col-xs-12 col-md-4">
									<?php
									$custLoggedInData	 = BroadcastNotification::model()->getCustomerLoggedInData();
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'bcn_customer_last_loggedIn',
										'val'			 => $model->bcn_customer_last_loggedIn,
										'asDropDownList' => FALSE,
										'options'		 => array(
											'data'			 => new CJavaScriptExpression(VehicleTypes::model()->getJSON($custLoggedInData)), 'allowClear'	 => true, 'placeholder'	 => 'Select Last Login'),
										'htmlOptions'	 => array('style' => 'width:100%')
									));
									?>
								</div>
							</div>
						</div>
					</div>

					<div class="col-xs-12 mb20">
						<div class="row zones-style">
							<div class="col-xs-12 col-md-5"><label>Link</label></div>
							<div class="col-xs-12 col-md-7">
								<?= $form->textFieldGroup($model, 'bcn_link', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Link"]))) ?>                      
							</div>
						</div>
					</div>

					<div class="col-xs-12 mb20">
						<div class="row zones-style">
							<div class="col-xs-12 col-md-5"><label>Title</label></div>
							<div class="col-xs-12 col-md-7">
								<?= $form->textFieldGroup($model, 'bcn_title', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Title"]))) ?>                      
							</div>
						</div>
					</div>

					<div class="col-xs-12 mb20">
						<div class="row zones-style">
							<div class="col-xs-12 col-md-5"><label>Message Text</label></div>
							<div class="col-xs-12 col-md-7">
								<?= $form->textFieldGroup($model, 'bcn_message', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['required' => true, 'placeholder' => "Message"]))) ?>                      
							</div>
						</div>
					</div>
					<?php
					if ($model->isNewRecord)
					{
						$model->bcn_chk_app = 1;
					}
					?>
					<?= $form->hiddenField($model, 'bcn_chk_app'); ?>
					<!--<div class="col-xs-12 mb20">
						<div class="row zones-style">
							<div class="col-xs-12 col-md-5"></div>
							<div class="col-xs-12 col-md-7">
					<?php
//								if ($model->isNewRecord)
//								{
//									$model->bcn_chk_app = 1;
//								}
//								
					?>
					<? //= $form->checkboxGroup($model, 'bcn_chk_app', array('label' => "Send notification", 'widgetOptions' => array(1 => 'checked'), 'placeholder' => 'Send Notification'))  ?>
							</div>
						</div>
					</div>-->

					<div class="col-xs-12 mb20">
						<div class="row">
							<div class="col-xs-12 col-md-5">
								<label>Send at</label>
							</div>
							<div class="col-xs-12 col-md-3">
								<?php $bcnDate = $model->bcn_date != '' ? $noteModel->bcn_date : date('Y-m-d H:i:s'); ?>
								<?= $form->datePickerGroup($model, 'bcn_date', array('label' => 'Date', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date('d/m/Y'), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => true, 'value' => date('d/m/Y', strtotime($bcnDate)))), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
							</div>
							<div class="col-xs-12 col-md-4">
								<? echo $form->timePickerGroup($model, 'bcn_time', array('label' => 'Time', 'widgetOptions' => array('id' => CHtml::activeId($model, "bcn_time"), 'options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Time', 'value' => date('h:i A', strtotime($bcnDate)), 'class' => 'input-group border-gray full-width')))); ?>
							</div>
						</div>
					</div>

					<div class="col-xs-12 text-center pb10">

						<?php echo CHtml::submitButton('Schedule', array('class' => 'btn btn-primary', 'id' => 'btnSendChat')); ?>
					</div>
				</div>
			</div>



		</div>
		<?php $this->endWidget(); ?>
	</div>

	<script>
		$(document).ready(function () {
			$("#vendor_driver").show();
			$("#customer").hide();
			$("#BroadcastNotification_bcn_user_type_0").click(function () {
				$("#vendor_driver").show();
				$("#customer").hide();
				$("#zone_driver").show("slow");
				$("#vndName").show("slow");
			});
			$("#BroadcastNotification_bcn_user_type_1").click(function () {
				$("#vendor_driver").show();
				$("#customer").hide();
				$("#zone_driver").hide("slow");
				$("#vndName").hide("slow");
			});
			$("#BroadcastNotification_bcn_user_type_2").click(function () {
				$("#vendor_driver").hide();
				$("#customer").show();
				$("#vndName").hide("slow");
			});
		});
		function getRefresh()
		{

			//$("#chatForm")[0].reset();
			//$("#BroadcastNotification_bcn_zon_name").val(0);	
			bootbox.dialog({
				message: "Data Saved Successfully",
				title: 'Data saved',
				onEscape: function () {
					location.reload();
				},
			});
		}
	</script>
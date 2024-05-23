
<style type="text/css">


    .selectize-input {
        min-width: 0px !important;
        width: 30% !important
    }
</style>
<?php
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
$cityList	 = CHtml::listData(Cities::model()->findAll(array('order' => 'cty_name', 'condition' => 'cty_active=:act', 'params' => array(':act' => '1'))), 'cty_id', 'cty_name');
//$vehicleList = Vehicles::model()->vehicleList();
$vendorList	 = CHtml::listData(Vendors::model()->getAll(array('order' => 'vnd_name')), 'vnd_id', 'vnd_name');

$stateList = array("" => "Select state") + CHtml::listData(States::model()->findAll('stt_active = :act AND stt_country_id = :con order by stt_name', array(':act' => '1', ':con' => '99')), 'stt_id', 'stt_name');
?>
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-8 pb10" style="float: none; margin: auto">
        <div style="text-align:center;" class="col-xs-12">
			<?php
			if ($status == "emlext")
			{
				echo "<span style='color:#ff0000;'>This email address is already registered. Please try again using a new email address.</span>";
			}
			elseif ($status == "added")
			{
				echo "<span style='color:#00aa00;'>Driver added successfully.</span>";
			}
			elseif ($status == "updated")
			{
				echo "<span style='color:#00aa00;'>Driver Modified Successfully.</span>";
			}
			else
			{
				//do nothing
			}
			?>
        </div>
        <div class="row">
            <div class="upsignwidt11">
                <div class="col-xs-12">
					<?php
					$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'driver-register-form', 'enableClientValidation' => TRUE,
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
							'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data'
						),
					));
					/* @var $form TbActiveForm */
					?>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="col-xs-12">
								<?php echo CHtml::errorSummary($model); ?>
								<?= $form->textFieldGroup($model, 'drv_name', array('label' => '')) ?>
								<?= $form->textFieldGroup($model, 'drv_username', array('label' => '')) ?>
								<?= $form->passwordFieldGroup($model, 'drv_password1', array('label' => '')) ?>
                                <div class="row">
                                    <div class="col-xs-12"> <label>Upload Photo</label> 
                                    </div>
                                    <div class="col-xs-6">
										<?= $form->hiddenField($model, 'vhd_temp_id') ?>
										<?= $form->fileFieldGroup($model, 'drvPhoto', array('label' => '')); ?>

                                    </div> 
                                    <div class="col-xs-6">
										<?
										if ($model->drv_photo_path != '')
										{
											?>
											<a href="<?= Yii::app()->createAbsoluteUrl($model->drv_photo_path) ?>" target="_blank"><?= basename($model->drv_photo_path) ?></a>
										<? } ?>
                                    </div>
                                </div>
								<?= $form->emailFieldGroup($model, 'drv_email', array('label' => '')) ?>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="row">
                                            <div class="col-xs-2 pl0">
												<?php
												$this->widget('ext.yii-selectize.YiiSelectize', array(
													'model'				 => $model,
													'attribute'			 => 'drv_country_code',
													'useWithBootstrap'	 => true,
													"placeholder"		 => "Code",
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
																},
																error:function(){
																callback();
																}});
																});
																}",
														'render'			 => "js:{
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
                                            </div>
                                            <div class='col-xs-10'>
												<?= $form->textFieldGroup($model, 'drv_phone', array('label' => '')) ?>
                                            </div> </div>
                                    </div>
                                </div>
                                <div class="form-group">
									<?php
									$data = Vendors::model()->getJSON();
//									$this->widget('booster.widgets.TbSelect2', array(
//										'model'			 => $model,
//										'attribute'		 => 'drv_vendor_id1',
//										'val'			 => $model->drv_vendor_id1,
//										'asDropDownList' => FALSE,
//										'options'		 => array('data' => new CJavaScriptExpression($data)),
//										'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Vendor')
//									));
									$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
													$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $model,
					'attribute'			 => 'drv_vendor_id1',
					'useWithBootstrap'	 => true,
					"placeholder"		 => "Select Vendor",
					'fullWidth'			 => false,
					'htmlOptions'		 => array('width' => '100%'),
					'defaultOptions'	 => $selectizeOptions + array(
				'onInitialize'	 => "js:function(){
                                              populateVendor(this, '{$model->drv_vendor_id1}');
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
                                    <span class="has-error"><? echo $form->error($model, 'drv_vendor_id1'); ?></span>
                                </div>

								<? //= $form->dropDownListGroup($model, 'drv_vendor_id', array('label' => '', 'widgetOptions' => array('data' => $vendorList)))    ?>
								<?php
								if ($model->drv_doj)
								{
									$model->drv_doj = DateTimeFormat::DateToDatePicker($model->drv_doj);
								}
								echo $form->datePickerGroup($model, 'drv_doj', array('label'			 => '',
									'widgetOptions'	 => array('options' => array('autoclose' => true, 'endDate' => '+1d', 'format' => 'dd/mm/yyyy'))
								));
								?>

								<?= $form->textFieldGroup($model, 'drv_lic_number', array('label' => '')) ?>
								<?= $form->textFieldGroup($model, 'drv_issue_auth', array('label' => '')) ?>
								<?php
								if ($model->drv_lic_exp_date)
								{
									$model->drv_lic_exp_date = DateTimeFormat::DateToDatePicker($model->drv_lic_exp_date);
								}
								echo $form->datePickerGroup($model, 'drv_lic_exp_date', array('label'			 => '',
									'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => '+1d', 'format' => 'dd/mm/yyyy'))
								));
								?>

								<?= $form->textAreaGroup($model, 'drv_address', array('label' => '')) ?>


                                <div class="form-group">
									<?php
									$dataState = VehicleTypes::model()->getJSON($stateList);
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'drv_state',
										'val'			 => $model->drv_state,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($dataState)),
										'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select State')
									));
									?>
                                </div>


								<? //= $form->dropDownListGroup($model, 'drv_state1', array('label' => '', 'widgetOptions' => array('data' => $stateList)))  ?>

								<?php
//                            if ($model->drv_city) {
//                                $cityId = $model->drv_city;
//                                $cityName = $cityList[$cityId];
//                            }
//                            else {
//                                $cityName = "Select city";
//                            }
								?>
                                <div id="cityDiv">
									<? //= $form->dropDownListGroup($model, 'drv_city', array('label' => '', 'widgetOptions' => array('data' => array($cityId => $cityName))))  ?>
                                </div>
                                <div class="form-group">
									<?php
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'drv_city',
										'val'			 => $model->drv_city,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression('[]')),
										'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select City')
									));
									?>
                                </div>

								<?= $form->textFieldGroup($model, 'drv_zip', array('label' => '')) ?>
								<?=
								$form->select2Group($model, 'assigned_vhc_id', array('label'			 => '',
									'widgetOptions'	 => array('data'			 => $vehicleList, 'htmlOptions'	 => array('multiple' => 'multiple', 'placeholder' => "Assign vehicle", 'value' => $model->assigned_vhc_id, 'title' => "Route To"),
									), 'options'		 => array('multiple' => true),
								));
								?>

								<?php //= $form->dropDownListGroup($model, 'assigned_vhc_id', array('label' => '', 'widgetOptions' => array('data' => $vehicleList)))          ?>
                                <div class="row">
                                    <div class="col-xs-12"><label >Upload Address proof1</label></div>
                                    <div class="col-xs-6"><?= $form->fileFieldGroup($model, 'drvdoc1', array('label' => '')); ?>  </div>
                                    <div class="col-xs-6">      
										<?
										if ($model->drv_aadhaar_img_path != '')
										{
											?>
											<a href="<?= Yii::app()->createAbsoluteUrl($model->drv_aadhaar_img_path) ?>" target="_blank"><?= basename($model->drv_aadhaar_img_path) ?></a>
										<? } ?>
                                    </div>  
                                </div>

                                <div class="row">
                                    <div class="col-xs-12"><label >Upload Address proof2</label></div>
                                    <div class="col-xs-6">  <?= $form->fileFieldGroup($model, 'drvdoc2', array('label' => '')); ?></div>
                                    <div class="col-xs-6">      
										<?
										if ($model->drv_pan_img_path != '')
										{
											?>
											<a href="<?= Yii::app()->createAbsoluteUrl($model->drv_pan_img_path) ?>" target="_blank"><?= basename($model->drv_pan_img_path) ?></a>
										<? } ?>
                                    </div>  
                                </div>

                                <div class="row">
                                    <div class="col-xs-12"><label >Upload Address proof3</label></div>
                                    <div class="col-xs-6">    <?= $form->fileFieldGroup($model, 'drvdoc3', array('label' => '')); ?></div>
                                    <div class="col-xs-6">      
										<?
										if ($model->drv_voter_id_img_path != '')
										{
											?>
											<a href="<?= Yii::app()->createAbsoluteUrl($model->drv_voter_id_img_path) ?>" target="_blank"><?= basename($model->drv_voter_id_img_path) ?></a>
										<? } ?>
                                    </div>  
                                </div>
                                <div style="margin-left: 20px;margin-top: -10px" >
									<?= $form->checkboxGroup($model, 'drv_bg_checked', array('value' => '1', 'uncheckValue' => '0', 'label' => 'Background checked')); ?>
                                </div>
                                <div style="margin-left: 20px;margin-top: -10px" >
									<?= $form->checkboxGroup($model, 'drv_is_attached', array('value' => '1', 'uncheckValue' => '0', 'label' => 'Is Attached')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer" style="text-align: center">
							<?php echo CHtml::submitButton('Add', array('class' => 'btn btn-primary')); ?>
                        </div>

                    </div>
					<?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo CHtml::endForm(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#Drivers_drv_phone').mask('9999999999');
        var availableTags = [];
        var front_end_height = $(window).height();
        var footer_height = $(".footer").height();
        var header_height = $(".header").height();

        if ($("#Drivers_drv_state").val() != '') {
            var id = $("#Drivers_drv_state").val();
            getCityList(id);
        }

        $("#Drivers_drv_state").change(function () {
            var stid = $("#Drivers_drv_state").val();
            var href2 = '<?= Yii::app()->createUrl("admin/driver/cityfromstate1"); ?>';
            $.ajax({
                "url": href2,
                "type": "GET",
                "dataType": "json",
                "data": {"id": stid},
                "success": function (data1) {
                    $data2 = data1;
                    var placeholder = $('#<?= CHtml::activeId($model, "drv_city") ?>').attr('placeholder');
                    $('#<?= CHtml::activeId($model, "drv_city") ?>').select2({data: $data2, placeholder: placeholder});
                }
            });
        });
        $("#Drivers_assigned_vhc_id1").change(function () {
            var vhcid = $("#Drivers_assigned_vhc_id1").val();
            var drvid = $("#Drivers_drv_id").val();
            var href2 = '<?= Yii::app()->createUrl("admin/driver/checkvehiclestatus"); ?>';
            $.ajax({
                "url": href2,
                "type": "GET",
                "dataType": "json",
                "data": {"vhcid": vhcid, "drvid": drvid},
                "success": function (data) {
                    if (data) {
                        if (confirm("The vehicle is allocated to other driver. Do you want to assign the vehicle to this driver?") == true) {

                        } else {
                            $("#Drivers_assigned_vhc_id").val('');
                        }
                    }

                }
            });
        });


        $("#<?= CHtml::activeId($model, 'files') ?>").on("change", function ()
        {
            var files = !!this.files ? this.files : [];
            if (!files.length || !window.FileReader)
                return;
            if (/^image/.test(files[0].type)) {
                var reader = new FileReader();
                reader.readAsDataURL(files[0]);
                reader.onloadend = function () {
                    $(".driverslicenceImg").attr("src", this.result);
                }
            }
        });


        $("#<?= CHtml::activeId($model, 'files') ?>").on("change", function ()
        {
            var files = !!this.files ? this.files : [];
            if (!files.length || !window.FileReader)
                return;
            if (/^image/.test(files[0].type)) {
                var reader = new FileReader();
                reader.readAsDataURL(files[0]);
                reader.onloadend = function () {
                    $(".driverslicenceImg").attr("src", this.result);
                }
            }
        });
        $("#<?= CHtml::activeId($model, 'files1') ?>").on("change", function ()
        {
            var files = !!this.files ? this.files : [];
            if (!files.length || !window.FileReader)
                return;
            if (/^image/.test(files[0].type)) {
                var reader = new FileReader();
                reader.readAsDataURL(files[0]);
                reader.onloadend = function () {
                    $(".driverslicenceImg1").attr("src", this.result);
                }
            }
        });
        $("#<?= CHtml::activeId($model, 'files2') ?>").on("change", function ()
        {
            var files = !!this.files ? this.files : [];
            if (!files.length || !window.FileReader)
                return;
            if (/^image/.test(files[0].type)) {
                var reader = new FileReader();
                reader.readAsDataURL(files[0]);
                reader.onloadend = function () {
                    $(".driverslicenceImg2").attr("src", this.result);
                }
            }
        });
    });

    $drv_city = <?= ($model->drv_city == '') ? 0 : $model->drv_city ?>;

    function getCityList(stateId) {
        var href2 = '<?= Yii::app()->createUrl("admin/driver/cityfromstate1"); ?>';
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "json",
            "data": {"id": stateId},
            "success": function (data1) {
                $data2 = data1;
                var placeholder = $('#<?= CHtml::activeId($model, "drv_city") ?>').attr('placeholder');
                $('#<?= CHtml::activeId($model, "drv_city") ?>').select2({data: $data2, placeholder: placeholder});
                $('#<?= CHtml::activeId($model, "drv_city") ?>').select2("val", $drv_city);
            }
        });
    }

    function validateCheckHandlerss() {
        if ($("#formId").validation({errorClass: 'validationErr'})) {

            if ($('#drv_email').val() != "") {
                var pattern = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/
                var retVal = pattern.test($('#drv_email').val());
                if (retVal == false)
                {
                    $('#errId').html("The email address you have entered is invalid.");
                    return false;
                }
            }
            return true;
        } else
        {
            return false;
        }
    }
</script>

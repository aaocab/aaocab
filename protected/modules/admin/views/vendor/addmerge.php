<?php
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
?>
<style type="text/css">

    .select2-container-multi .select2-choices {
        min-height: 50px;
    }
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
    label.error {
        margin-top: 0;
    }
</style>
<div class="row">
    <div class="col-lg-10 col-md-8 col-sm-10 pb10 new-booking-list" style="float: none; margin: auto">
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
			else
			{
				//do nothing
			}
			?>


        </div>

		<?php
		if ($message != '')
		{
			echo '<h2>' . $message . '</h2>';
		}
		else
		{

			$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'vendors-register-form', 'enableClientValidation' => true,
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
	        <div class="row col-md-12 col-lg-9" style="float: left;">           
	            <div class="col-xs-12 col-md-12 col-lg-6 new-booking-list">
	                <div class="row">
	                    <div class="col-xs-12">
	                        <div class="panel panel-default panel-border">
	                            <div class="panel-body">
									<?php echo CHtml::errorSummary($model); ?>
	                                <h3 class="pb10 mt0">Personal Information</h3>
									<div class="row">
	                                    <div class="col-xs-12 col-sm-6">
	                                        <label>Name</label>
											<?= $form->textFieldGroup($model, 'vnd_name', array('label' => '')) ?>
	                                    </div>
	                                </div>
	                                <div class="row">
	                                    <div class="col-xs-12">
	                                        <label>Vendor Type</label>
											<?= $form->radioButtonListGroup($model, 'vnd_cat_type', array('label' => '', 'widgetOptions' => array('data' => array('1' => 'DCO', '2' => 'Vendor')), 'inline' => true,)); ?>
	                                    </div>
	                                </div>

	                                <div class="row hide" id="contactTypeText">
										<div class="col-xs-12 col-sm-6 ">
	                                        <label>Contact Info</label>
											<?php echo $form->hiddenField($model, 'vnd_contact_id'); ?>
											<?= $form->textFieldGroup($model, 'vnd_contact_name', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Contact Name', 'readonly' => 'readonly')))) ?>  
										</div>
										<?php if ($isNew != 'Approve')
										{ ?> 
		                                    <div class="col-xs-4 col-sm-3 viewcontctsearch" style="<?= $contactViewSearch; ?>;">
												<label>&nbsp;</label>
												<div>
													<button class="btn btn-info viewContact" type="button">View Contact</button></div>
		                                    </div>

											<?php if ($model->vnd_id != "")
											{ ?>

												<div class="col-xs-4 col-sm-3">
													<label>&nbsp;</label>
													<div>
														<a class="btn btn-info modifyContact" target="_blank" href="<?= Yii::app()->createUrl('admin/contact/form', array('ctt_id' => $model->vnd_contact_id)) ?>" >Modify Contact</a></div>
												</div>
											<?php } ?>
											<div class="col-xs-4 col-sm-3 ">
												<label>&nbsp;</label>
												<div><button class="btn btn-info searchContact" type="button">Select Contact</button></div>
											</div>
		                                    <div class="col-xs-4 col-sm-3 ">
												<label>&nbsp;</label>
												<div> 
													<a class="btn btn-primary  weight400 font-bold addContact" title="Add Contact">Add Contact</a>
												</div>
											</div>
											<? }else {?>
											<div class="col-xs-4 col-sm-3">
												<label>&nbsp;</label>
												<div>
													<a class="btn btn-info modifyContact" target="_blank" href="<?= Yii::app()->createUrl('admin/contact/add', array('ctt_id' => $model->vnd_contact_id)) ?>" >Modify Contact</a></div>
											</div>
										<?php } ?>
	                                </div>

	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-xs-12">
	                        <div class="panel panel-default panel-border">
	                            <div class="panel-body">
	                                <h3 class="pb10 mt0">Inventory Information</h3>
	                                <div class="row">
	                                    <div class="col-xs-12 col-sm-6 ">
	                                        <div class="row ">
	                                            <div class="col-xs-12">
	                                                <label>Sedan Count</label>
													<?= $form->textFieldGroup($modelVendPref, 'vnp_sedan_count', array('label' => '')) ?>
	                                            </div>
	                                        </div>
	                                    </div>
										<div class="col-xs-12 col-sm-6 ">
	                                        <label>Compact Count</label>
											<?= $form->textFieldGroup($modelVendPref, 'vnp_compact_count', array('label' => '')) ?>
	                                    </div>
	                                </div>

	                                <div class="row">
	                                    <div class="col-xs-12 col-sm-6 ">
	                                        <label>SUV Count</label>
											<?= $form->textFieldGroup($modelVendPref, 'vnp_suv_count', array('label' => '')) ?>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
						<div class="col-xs-12">
	                        <div class="panel panel-default panel-border">
	                            <div class="panel-body">
	                                <div class="row">
	                                    <div class="col-xs-12 col-sm-6 ">
	                                        <label>Relationship Manager</label>
											<?php
											$adminList = Admins::model()->getJSON();
											$this->widget('booster.widgets.TbSelect2', array(
												'model'			 => $model,
												'attribute'		 => 'vnd_rm',
												'val'			 => $model->vnd_rm,
												'asDropDownList' => FALSE,
												'options'		 => array('data' => new CJavaScriptExpression($adminList), 'allowClear' => true),
												'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Relationship Manager')
											));
											?>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <div class="col-xs-12 col-md-12 col-lg-6">
	                <div class="row">
	                    <div class="col-xs-12">
	                        <div class="panel panel-default panel-border">
	                            <div class="panel-body">
	                                <h3 class="pb10 mt0">Account Information</h3>
	                                <div class="row">
	                                    <div class="col-xs-12 col-sm-6 ">                                        
											<?= $form->numberFieldGroup($modelVendStats, 'vrs_security_amount', array('widgetOptions' => array('htmlOptions' => ['class' => 'form-control', 'min' => 0]))) ?>
	                                    </div>
	                                    <div class="col-xs-12 col-sm-6">
											<?php
											if ($modelVendStats->vrs_security_receive_date)
											{
											$modelVendStats->vrs_security_receive_date1 = DateTimeFormat::DateToDatePicker($modelVendStats->vrs_security_receive_date);
											}
											?>
	                                        <div class="form-group">
												<?=
												$form->datePickerGroup($modelVendStats, 'vrs_security_receive_date1', array('label'			 => 'Security Receive Date', 'widgetOptions'	 => array('options'		 => array('autoclose'	 => true,
															'endDate'	 => '+0d', 'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array(
														)), 'groupOptions'	 => ['class' => 'm0'], 'prepend'		 => '<i class="fa fa-calendar"></i>'));
												?>                            
	                                        </div> 
	                                    </div>
	                                </div>
	                                <div class="row">
	                                    <div class="col-xs-12 col-sm-6 ">   
											<?php
											if ($modelVendStats->vrs_credit_limit == null)
											{
												$modelVendStats->vrs_credit_limit = 500;
											}
											?>                                     
											<?= $form->numberFieldGroup($modelVendStats, 'vrs_credit_limit', array()) ?>
	                                    </div>
	                                    <div class="col-xs-12 col-sm-6">                                       
											<?= $form->numberFieldGroup($modelVendStats, 'vrs_credit_throttle_level', array('widgetOptions' => array('htmlOptions' => ['max' => 100]))) ?>

	                                    </div>
	                                </div>
	                                <div class="row">
	                                    <div class="col-xs-12 col-sm-6 ">

	                                        <label>Agreement date</label>
											<?
											if ($model->vndAgreement->vag_soft_date)
											{
											$model->vnd_agreement_date1 = DateTimeFormat::DateTimeToDatePicker($model->vndAgreement->vag_soft_date);
											}
											echo $form->datePickerGroup($model, 'vnd_agreement_date1', array('label'			 => '',
											'widgetOptions'	 => array('options' => array('autoclose' => true, 'format' => 'dd/mm/yyyy', 'autoclose' => true, 'endDate' => '+0d',))
											));
											?>
	                                    </div>
	                                    <div class="col-xs-12 col-sm-6">

	                                        <label>Agreement file</label>
											<?= $form->fileFieldGroup($model, 'vnd_agreement_file_link', array('label' => '', 'widgetOptions' => array())); ?>

											<?
											if ($model->vndAgreement->vag_soft_path != '')
											{
                                               $softPath =  VendorAgreement::getPathById($model->vndAgreement->vag_id, VendorAgreement::SOFT_PATH);
											?><div class="row ">
												<div class="col-xs-12 mb15">
													<a href="<?= $softPath ?>" target="_blank"><?= $softPath; ?></a>
												</div>
											</div>
											<? } ?>
	                                    </div>
	                                </div>
	                                <div class="row mb10">
	                                    <div class="col-xs-12 col-sm-6 ">
	                                        <div class="row ">
	                                            <div class="col-xs-12">
	                                                <label>Home Zone (Select Home zone where vendor is located)</label>
	                                            </div>
	                                            <div class="col-xs-12 ">  <?php
													$zoneListJson = Zones::model()->getJSON();

													$this->widget('booster.widgets.TbSelect2', array(
														'model'			 => $modelVendPref,
														'attribute'		 => 'vnp_home_zone',
														'val'			 => "{$modelVendPref->vnp_home_zone}",
														'asDropDownList' => FALSE,
														'options'		 => array('data' => new CJavaScriptExpression($zoneListJson), 'allowClear' => true),
														'htmlOptions'	 => array('style' => 'width:100%;', 'placeholder' => 'Home Zone')
													));
													?>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="col-xs-12 col-sm-6">
	                                        <div class="row">
	                                            <div class="col-xs-12 "><label> One Way Zones (Accepted Zones)</label>
	                                            </div>
	                                            <div class="col-xs-12"> <?php
													$loc2			 = Zones::model()->getZoneList();
													$SubgroupArray2	 = CHtml::listData(Zones::model()->getZoneList(), 'zon_id', function($loc2) {
																return $loc2->zon_name;
															});
													$this->widget('booster.widgets.TbSelect2', array(
														'name'			 => 'vnp_accepted_zone',
														'model'			 => $modelVendPref,
														'data'			 => $SubgroupArray2,
														'value'			 => explode(',', $modelVendPref->vnp_accepted_zone),
														'htmlOptions'	 => array(
															'multiple'		 => 'multiple',
															'placeholder'	 => 'One Way Zones',
															'width'			 => '100%',
															'style'			 => 'width:100%',
														),
													));
													?>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                                <div class="row mb10">
										<!--                                    <div class="col-xs-12 col-sm-6 ">
																				<div class="row ">
																					<div class="col-xs-12"><label>Return Zones</label>
																					</div>
																					<div class="col-xs-12"><?php
//												$loc3			 = Zones::model()->getZoneList();
//												$SubgroupArray3	 = CHtml::listData($loc3, 'zon_id', function($loc3)
//														{
//															return $loc3->zon_name;
//														});
//												$vall = explode(',', $model->vnd_return_zone);
//
//												$this->widget('booster.widgets.TbSelect2', array(
//													'name'			 => 'vnd_return_zone',
//													'model'			 => $model,
//													'data'			 => $SubgroupArray3,
//													'value'			 => $vall,
//													'htmlOptions'	 => array(
//														'multiple'		 => 'multiple',
//														'placeholder'	 => 'Return Zones',
//														'width'			 => '100%',
//														'style'			 => 'width:100%',
//													),
//												));
													?>
																					</div>
																				</div>
																			</div>-->
	                                    <div class="col-xs-12 col-sm-6">
	                                        <div class="row">
	                                            <div class="col-xs-12 "><label>Excluded Cities</label></div>
	                                            <div class="col-xs-12">  <?php
									$this->widget('booster.widgets.TbSelect2', array(
										'name'			 => 'vnp_excluded_cities',
										'model'			 => $modelVendPref,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression('[]'), 'multiple' => true),
										//      'value' => explode(',', $model->vnd_excluded_cities),
										'htmlOptions'	 => array(
											'multiple'		 => 'multiple',
											'placeholder'	 => 'Excluded Cities',
											'width'			 => '100%',
											'style'			 => 'width:100%',
										),
									));
													?>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                                <div class="row">
	                                    <div class="col-xs-12 col-sm-6 ">


	                                        <label> Is Attached(Y/N)</label> &nbsp;
											<?= $form->radioButtonListGroup($modelVendPref, 'vnp_is_attached', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Yes', 0 => 'No')), 'inline' => true), array(1 => 'checked')) ?>


	                                    </div>
										<!--                                    <div class="col-xs-12 col-sm-6">
																				<div class="row ">
																					<div class="col-xs-12">
																					   <label> Operates one-way(Y/N)</label> &nbsp;
																						<?//= $form->radioButtonListGroup($modelVendPref, 'vnp_booking_type', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Yes', 2 => 'No')), 'inline' => true)) ?>
																					   </div>
																				</div>
																			</div>-->
	                                </div>

	                                <div class="row">
										<div class="col-xs-12 col-sm-6 mt5 ">
											<?= $form->checkboxGroup($modelVendPref, 'vnp_oneway', array()) ?>
										</div>
										<div class="col-xs-12 col-sm-6 mt5">
											<?= $form->checkboxGroup($modelVendPref, 'vnp_round_trip', array()) ?>
										</div>
										<div class="col-xs-12 col-sm-6 mt5">
											<?= $form->checkboxGroup($modelVendPref, 'vnp_multi_trip', array()) ?>
										</div>
										<div class="col-xs-12 col-sm-6 mt5">
											<?= $form->checkboxGroup($modelVendPref, 'vnp_package', array()) ?>
										</div>
										<div class="col-xs-12 col-sm-6 mt5">
											<?= $form->checkboxGroup($modelVendPref, 'vnp_daily_rental', array()) ?>
										</div>
										<div class="col-xs-12 col-sm-6 mt5">
											<?= $form->checkboxGroup($modelVendPref, 'vnp_airport', array()) ?>
										</div>
									</div>

	                                <div class="row">                                       
	                                    <div class="col-xs-12">
	                                        <label>Notes</label>
											<?= $form->textAreaGroup($modelVendPref, 'vnp_notes', array('label' => '')) ?>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
				<input type="hidden" name="type" id="type" value="<?= $type; ?>" >
	            <div class="row">
	                <div class="col-xs-12 text-center pb10">
						<?php echo CHtml::Button("Submit", array('class' => 'btn btn-primary')); ?>
	                </div>
	            </div>
	        </div>
			<?php $this->endWidget(); ?>
			<?php if (count($modelContactMerge) > 0)
			{ ?>
				<div class="col-md-12 col-lg-3" style="float: right;padding-right:50px;">
					<div class="row"><h4>List Vendor to be merged : </h4>
						<ul style="padding-left:10px">
							<?php for ($i = 0; $i < count($modelContactMerge); $i++)
							{ ?>
								<div class="col-xs-12 panel panel-default panel-border" style="color: #666">
									<li><?= ($i + 1) ?><b>. Vendor Assigned </b></li>
									<li><b>Vendor Id</b>: <?= $modelVendorMerge[$i]['vnd_id'] ?> &nbsp;&nbsp; <a href="javascript:void()" onclick='viewDetail(<?= $i ?>)'>Show Details</a></li>
									<li><b>Vendor Type</b>: <?php echo Vendors::getTypeByVendorType($modelVendorMerge[$i]['vnd_cat_type']); ?></li>
									<li><b>Name</b> :<?= $modelVendorMerge[$i]['vnd_name'] ?></li>
									<li><b>Phone</b> : <?= $modelContactMerge[$i]['phn_phone_no'] ?> <span id = "docapprove" class="label label-success">Primary</span></li>
									<li><b>Email</b> : <?= $modelContactMerge[$i]['eml_email_address'] ?> <span id = "docapprove" class="label label-success">Primary</span></li>
									<li><b>Vendor Code</b> : <?= $modelVendorMerge[$i]['vnd_code'] ?></li>
									<li><b>Vendor T&C</b> : <?php echo $modelVendorMerge[$i]['vnd_tnc'] == 1 ? '<span id = "docapprove" class="label label-success"> Accepted</span>' : '<span class="label label-danger"> Not Accepted</span>'; ?></li>
									<li><b>Vendor Security Amt.</b> : <?php echo $modelVendStatsMerge[$i]['vrs_security_amount']; ?></li>
									<li><b>Voter No</b> : <?= $modelContactMerge[$i]['ctt_voter_no'] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if ($modelContactMerge[$i]['doc_status2'] == 0)
								{ ?>	<span id = "docnotapprove" class="label label-info"> Not Approved</span><?php }
								elseif ($modelContactMerge[$i]['doc_status2'] == '1')
								{ ?>	<span id = "docapprove" class="label label-success"> Approved</span><?php }
								elseif ($modelContactMerge[$i]['doc_status2'] == 2)
								{ ?>	<span class="label label-danger"> Rejected</span>	<?php } ?></li>
									<li><b>Aadhaar No.</b> : <?= $modelContactMerge[$i]['ctt_aadhaar_no'] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if ($modelContactMerge[$i]['doc_status3'] == 0)
								{ ?>	<span id = "docnotapprove" class="label label-info"> Not Approved</span><?php }
								elseif ($modelContactMerge[$i]['doc_status3'] == '1')
								{ ?>	<span id = "docapprove" class="label label-success"> Approved</span><?php }
								elseif ($modelContactMerge[$i]['doc_status3'] == 2)
								{ ?>	<span class="label label-danger"> Rejected</span>	<?php } ?></li>
									<li><b>Pan No</b> : <?= $modelContactMerge[$i]['ctt_pan_no'] ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if ($modelContactMerge[$i]['doc_status4'] == 0)
			{ ?>	<span id = "docnotapprove" class="label label-info"> Not Approved</span><?php }
			elseif ($modelContactMerge[$i]['doc_status4'] == '1')
			{ ?>	<span id = "docapprove" class="label label-success"> Approved</span><?php }
			elseif ($modelContactMerge[$i]['doc_status4'] == 2)
			{ ?>	<span class="label label-danger"> Rejected</span>	<?php } ?></li>
									<li><b>License No.</b> : <?= $modelContactMerge[$i]['ctt_license_no'] ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php if ($modelContactMerge[$i]['doc_status5'] == 0)
			{ ?>	<span id = "docnotapprove" class="label label-info"> Not Approved</span><?php }
			elseif ($modelContactMerge[$i]['doc_status5'] == '1')
			{ ?>	<span id = "docapprove" class="label label-success"> Approved</span><?php }
			elseif ($modelContactMerge[$i]['doc_status5'] == 2)
			{ ?>	<span class="label label-danger"> Rejected</span>	<?php } ?></li>
									<li><b>Last Login</b> : <?php $vendorLastLogin = AppTokens::model()->find('apt_entity_id=:id order by apt_last_login desc', ['id' => $modelVendorMerge[$i]['vnd_id']]);
			echo $vendorLastLogin->apt_last_login; ?></li>
									<li><b>Vendor Booking Count</b> : <?= $modelVendStatsMerge[$i]['vrs_vnd_total_trip'] != NULL ? $modelVendStatsMerge[$i]['vrs_vnd_total_trip'] : 0 ?></li>
									<li><button class="btn btn-primary btn-sm" style="text-align: center" onclick='copyVendor(<?= $i ?>)' >Copy Vendor</button></li>
									<br>
								</div>
		<?php } ?>
						</ul>
					</div>
				</div>
	<?php
	}
}
?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('input[type="button"]').click(function () {
            bootbox.confirm({message: "Are you sure you want to merge Vendor?",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if (result) {
                        $('#vendors-register-form').submit();
                        return false;
                    }
                }
            });
        });


<?php if ($model->vnd_cat_type == 2)
{ ?>
	        $('#Vendors_vnd_cat_type_1').click();
	        $('#Vendors_vnd_cat_type_1').attr('checked', 'checked');
	        $('#Vendors_vnd_cat_type_1').parent().addClass('checked');
	        $("#Vendors_vnd_contact_name").val('<?= $model->vnd_contact_name ?>');
<?php }
else if ($model->vnd_cat_type == 1)
{ ?>
	        $('#Vendors_vnd_cat_type_0').click();
	        $('#Vendors_vnd_cat_type_0').attr('checked', 'checked');
	        $('#Vendors_vnd_cat_type_0').parent().addClass('checked');
	        $("#Vendors_vnd_contact_name").val('<?= $model->vnd_contact_name ?>');
<?php } ?>
<?php if ($_GET['type'] != 'unreg')
{ ?>    citylist();<?php } ?>
<?php if ($isNew == 'Approve')
{ ?>
	        $("#Vendors_vnd_cat_type_0").attr('disabled', 'true');
	        $("#Vendors_vnd_cat_type_1").attr('disabled', 'true');
<?php } ?>

    });
    $('form').on('focus', 'input[type=number]', function (e) {
        $(this).on('mousewheel.disableScroll', function (e) {
            e.preventDefault()
        })
        $(this).on("keydown", function (event) {
            if (event.keyCode === 38 || event.keyCode === 40) {
                event.preventDefault();
            }
        });
    });
    $('form').on('blur', 'input[type=number]', function (e)
    {
        $(this).off('mousewheel.disableScroll');
        $(this).off('keydown');
    });
    function checkDuplicateUser(obj, utype) {
        cttid = $(obj).val();
        var href = '<?= Yii::app()->createUrl("admin/vendor/checkuser"); ?>';
        $.ajax({
            "url": href,
            "type": "GET",
            "dataType": "json",
            "data": {cttid: cttid},
            "success": function (data)
            {
                //alert(data);
                if (data == 1) {
                    //var conf = confirm('This Email Address is alredy exist? Do you want to link the same user in vendor.')
                    var conf = confirm('This Contact Address is alredy registered by vendor.')
                    if (conf) {
                        if (utype == 'company') {
                            $("#s2id_Contact_ctt_id").select2("val", "");
                        } else {
                            $("#s2id_Contact_ctt_owner_id").select2("val", "");
                        }

                    }
                }
            }
        });
    }
    $('#Vendors_vnd_phone').mask('9999999999');
    $('#vnp_home_zone').on("change", function () {
        $('#vnp_excluded_cities').unbind("select2-focus").on("select2-focus", function ()
        {
            citylist();
        });
    });
    $('#vnp_accepted_zone').on("change", function () {
        $('#vnp_excluded_cities').unbind("select2-focus").on("select2-focus", function ()
        {
            citylist();
        });
    });
    $excludedCities = [<?= $modelVendPref->vnp_excluded_cities ?>];
    $openOnFocus = false;
<?php if ($model->vnd_id != "")
{ ?>
	    $(".searchContact").hide();
	    $(".addContact").hide();
	    $(".viewcontctsearch").hide();
	    $("#contactTypeText").removeClass('hide');
	    $("#Vendors_vnd_cat_type_0").click(function () {
	        $("#contactTypeText").removeClass('hide');
	    });
	    $("#Vendors_vnd_cat_type_1").click(function () {
	        $("#contactTypeText").removeClass('hide');
	    });
<?php }
else
{ ?>
	    $("#Vendors_vnd_cat_type_0").click(function () {
	        $("#contactTypeText").removeClass('hide');
	        $("#Vendors_vnd_contact_name").val('');
	        $(".viewcontctsearch").hide();
	    });
	    $("#Vendors_vnd_cat_type_1").click(function () {
	        $("#contactTypeText").removeClass('hide');
	        $("#Vendors_vnd_contact_name").val('');
	        $(".viewcontctsearch").hide();
	    });
<?php } ?>
    function selectFirmType(type)
    {
        if (type == '1')
        {
            $("#ownerContText").show()
            $("#companyContText").hide()
        }
        if (type == '2' || type == '3' || type == '4')
        {
            $("#ownerContText").show()
            $("#companyContText").show()
        }

    }
    $("#Vendors_vnd_cat_type_0").click(function ()
    {
        var val = $("#Vendors_vnd_cat_type_0").val();
        selectFirmType(val);
    });
    $("#Vendors_vnd_cat_type_1").click(function ()
    {
        var val = $("#Vendors_vnd_cat_type_1").val();
        selectFirmType(val);
    });
    $("#Vendors_vnd_cat_type_2").click(function ()
    {
        var val = $("#Vendors_vnd_cat_type_2").val();
        selectFirmType(val);
    });
    $("#Vendors_vnd_cat_type_3").click(function ()
    {
        var val = $("#Vendors_vnd_cat_type_3").val();
        selectFirmType(val);
    });
    $('#VendorPref_vnp_is_attached').change(function ()
    {
        if ($("#VendorPref_vnp_is_attached .checked input").val() == 1)
        {
            // alert("roy");
            $('#VendorPref_vnp_booking_type input').prop('checked', true);
        }
    });
    function validateCheckHandlerss() {
        if ($("#formId").validation({errorClass: 'validationErr'}))
        {

            if ($('#vnd_email').val() != "")
            {
                var pattern = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/
                var retVal = pattern.test($('#vnd_email').val());
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
    function citylist() {
        var total = '0';
        var home = $('#vnp_home_zone').val();
        var accepted = $('#vnp_accepted_zone').val();
        if (home != null)
        {
            total = total + "," + home.toString();
        }
        if (accepted != null)
        {
            total = total + "," + accepted.toString();
        }
        var href = '<?= Yii::app()->createUrl("admin/vendor/zonecity"); ?>';
        $.ajax({
            "url": href,
            "type": "GET",
            "dataType": "json",
            "data": {zoneid: total},
            "success": function (data)
            {
                $data2 = data;
                $('#vnp_excluded_cities').select2('destroy');
                $('#vnp_excluded_cities').select2({data: $data2, multiple: true});
                $('#vnp_excluded_cities').unbind("select2-focus");
                if ($openOnFocus)
                {
                    $('#vnp_excluded_cities').select2("open");
                } else
                {
                    $('#vnp_excluded_cities').select2('val', $excludedCities);
                }
                $openOnFocus = true;
            }
        });
    }
    $sourceList = null;
    function populateSource(obj, cityId) {

        obj.load(function (callback)
        {
            var obj = this;
            if ($sourceList == null)
            {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
                    dataType: 'json',
                    data: {
                        // city: cityId
                    },
                    //  async: false,
                    success: function (results)
                    {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
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
                callback($sourceList);
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
    function editBooking(booking_id, errors) {
        $href = $adminUrl + "/booking/edit";
        var $booking_id = booking_id;
        jQuery.ajax({type: 'GET',
            url: $href,
            //data: {"bookingID": $booking_id, 'errors': errors},
            success: function ()
            {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Edit Booking',
                    size: 'large',
                    onEscape: function () {
                        // user pressed escape
                    },
                });

            }
        });
    }
    $('.viewContact').click(function () {
        $href = '<?= Yii::app()->createUrl('admin/contact/add') ?>';
        var contid = $("#Vendors_vnd_contact_id").val();
        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"ctt_id": contid, "vndtype": "viewcont"},
            success: function (data)
            {
                box = bootbox.dialog({
                    message: data,
                    title: 'Contact View',
                    size: 'large',
                    onEscape: function () {

                        // user pressed escape
                    },
                });
            }
        });
    });
    $('.searchContact').click(function () {
        $href = '<?= Yii::app()->createUrl('admin/contact/list') ?>';
        var contype = $("input[name='Vendors[vnd_cat_type]']:checked").val();
        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"ctype": contype, "vndtype": "asgncont"},
            success: function (data)
            {
                box = bootbox.dialog({
                    message: data,
                    title: 'Contact List',
                    size: 'large',
                    onEscape: function () {
                        $('.bootbox.modal').modal('hide');
                    },
                });
            }
        });
    });
    $('.addContact').click(function () {
        $href = '<?= Yii::app()->createUrl('admin/contact/add') ?>';
        jQuery.ajax({type: 'GET', url: $href, data: {"type": "vndctt"},
            success: function (data) {
                box = bootbox.dialog({
                    message: data,
                    title: 'Add Contact',
                    size: 'large',
                    onEscape: function () {
                        $('.bootbox.modal').modal('hide');
                    },
                });
            }});
    });


    function copyVendor(index) {
        var modelContactMerge = <?php echo json_encode($modelContactMerge); ?>;
        var modelVendorMerge = <?php echo ($rowsVendorMerge); ?>;
        var modelVendPrefMerge = <?php echo ($rowsVendPrefMerge); ?>;
        var modelVendStatsMerge = <?php echo ($rowsVendStatsMerge); ?>;
        var modelVendArgMerge = <?php echo ($rowsVendArgMerge) ?>;
        $("#Vendors_vnd_name").val(modelVendorMerge[index].vnd_name !== null ? modelVendorMerge[index].vnd_name : "");
        if (modelVendorMerge[index].vnd_cat_type == "1") {
            $("#Vendors_vnd_cat_type_0").val(1);
            $("#ytVendors_vnd_cat_type_0").val(1);
            $("#uniform-Vendors_vnd_cat_type_0 span").addClass("checked");
            $("#uniform-Vendors_vnd_cat_type_1 span").removeClass("checked");
        } else {
            $("#Vendors_vnd_cat_type_1").val(2);
            $("#ytVendors_vnd_cat_type_1").val(2);
            $("#uniform-Vendors_vnd_cat_type_1 span").addClass("checked");
            $("#uniform-Vendors_vnd_cat_type_0 span").removeClass("checked");
        }

        if (modelVendPrefMerge[index].vnp_is_attached == "1") {
            $("#VendorPref_vnp_is_attached_0").val(1);
            $("#ytVendorPref_vnp_is_attached").val(1);
            $("#uniform-VendorPref_vnp_is_attached_0 span").addClass("checked");
            $("#uniform-VendorPref_vnp_is_attached_1 span").removeClass("checked");
        } else {
            $("#VendorPref_vnp_is_attached_1").val(2);
            $("#ytVendorPref_vnp_is_attached").val(2);
            $("#uniform-VendorPref_vnp_is_attached_1 span").addClass("checked");
            $("#uniform-VendorPref_vnp_is_attached_0 span").removeClass("checked");
        }

        if (modelVendPrefMerge[index].vnp_booking_type == "1") {
            $("#VendorPref_vnp_booking_type_0").val(1);
            $("#ytVendorPref_vnp_booking_type").val(1);
            $("#uniform-VendorPref_vnp_booking_type_0 span").addClass("checked");
            $("#uniform-VendorPref_vnp_booking_type_1 span").removeClass("checked");
        } else {
            $("#ytVendorPref_vnp_booking_type").val(2);
            $("#VendorPref_vnp_booking_type_1").val(2);
            $("#uniform-VendorPref_vnp_booking_type_1 span").addClass("checked");
            $("#uniform-VendorPref_vnp_booking_type_0 span").removeClass("checked");
        }

        var contactInfo = modelContactMerge[index].contactperson !== null ? modelContactMerge[index].contactperson + " | " : "";
        contactInfo += modelContactMerge[index].eml_email_address !== null ? modelContactMerge[index].eml_email_address + " | " : "";
        contactInfo += modelContactMerge[index].phn_phone_no !== null ? modelContactMerge[index].phn_phone_no : ""
        $("#Vendors_vnd_contact_name").val(contactInfo);
        $("#Vendors_vnd_contact_id").val(modelContactMerge[index].ctt_id);
        $("#VendorPref_vnp_sedan_count").val(modelVendPrefMerge[index].vnp_sedan_count == null ? "" : modelVendPrefMerge[index].vnp_sedan_count);
        $("#VendorPref_vnp_compact_count").val(modelVendPrefMerge[index].vnp_compact_count == null ? "" : modelVendPrefMerge[index].vnp_compact_count);
        $("#VendorPref_vnp_suv_count").val(modelVendPrefMerge[index].vnp_suv_count == null ? "" : modelVendPrefMerge[index].vnp_suv_count);
        $("#Vendors_vnd_rm").select2("val", modelVendorMerge[index].vnd_rm !== null ? modelVendorMerge[index].vnd_rm : "");
        $("#VendorStats_vrs_security_amount").val(modelVendStatsMerge[index].vrs_security_amount == null ? 0 : modelVendStatsMerge[index].vrs_security_amount);
        $("#VendorStats_vrs_security_receive_date1").val(moment(modelVendStatsMerge[index].vrs_security_receive_date, 'YYYY-MM-DD', true).isValid() ? moment(modelVendStatsMerge[index].vrs_security_receive_date, 'YYYY-MM-DD', true).format('DD/MM/YYYY') : '');
        $('#VendorStats_vrs_security_receive_date1').datepicker('update');
        $("#VendorStats_vrs_credit_limit").val(modelVendStatsMerge[index].vrs_credit_limit == null ? "" : modelVendStatsMerge[index].vrs_credit_limit);
        $("#VendorStats_vrs_credit_throttle_level").val(modelVendStatsMerge[index].vrs_credit_throttle_level == null ? "" : modelVendStatsMerge[index].vrs_credit_throttle_level);
        $("#Vendors_vnd_agreement_date1").val(moment(modelVendArgMerge[index].vag_soft_date, 'YYYY-MM-DD HH:mm:ss', true).isValid() ? moment(modelVendArgMerge[index].vag_soft_date, 'YYYY-MM-DD HH:mm:ss', true).format('DD/MM/YYYY') : '');
        $('#Vendors_vnd_agreement_date1').datepicker('update');
        $("#VendorPref_vnp_notes").val(modelVendPrefMerge[index].vnp_notes == null ? "" : modelVendPrefMerge[index].vnp_notes);
        $("#VendorPref_vnp_home_zone").select2("val", modelVendPrefMerge[index].vnp_home_zone !== null ? modelVendPrefMerge[index].vnp_home_zone : "");
        $("#VendorPref_vnp_home_zone").select2("val", modelVendPrefMerge[index].vnp_home_zone !== null ? modelVendPrefMerge[index].vnp_home_zone : "");
        $('#vnp_accepted_zone').val(modelVendPrefMerge[index].vnp_accepted_zone.split(','));
        $('#vnp_accepted_zone').trigger('change');

        $excludedCities = [modelVendPrefMerge[index].vnp_excluded_cities];
        $openOnFocus = false;
        var total = '0';
        var home = modelVendPrefMerge[index].vnp_home_zone;
        var accepted = modelVendPrefMerge[index].vnp_accepted_zone;
        if (home != null) {
            total = total + "," + home.toString();
        }
        if (accepted != null) {
            total = total + "," + accepted.toString();
        }
        var href = '<?= Yii::app()->createUrl("admin/vendor/zonecity"); ?>';
        $.ajax({
            "url": href,
            "type": "GET",
            "dataType": "json",
            "data": {zoneid: total},
            "success": function (data)
            {
                $('#vnp_excluded_cities').select2('destroy');
                $('#vnp_excluded_cities').select2({data: data, multiple: true});
                $('#vnp_excluded_cities').unbind("select2-focus");
                if ($openOnFocus)
                {
                    $('#vnp_excluded_cities').select2("open");
                } else
                {
                    $('#vnp_excluded_cities').select2('val', $excludedCities);
                }
                $openOnFocus = true;
            }
        });

        var url = '<?= Yii::app()->createUrl("aaohome/contact/add?ctt_id="); ?>' + modelContactMerge[index].ctt_id;
        $(".modifyContact").attr("href", url);
    }

    function viewDetail(index) {
        var modelVendorMerge = <?php echo ($rowsVendorMerge); ?>;
        var href = '<?= Yii::app()->createUrl("aaohome/vendor/view?id=") ?>' + modelVendorMerge[index].vnd_id;
        $.ajax({
            "url": href,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Vendor Details',
                    size: 'large',
                    onEscape: function () {
                        // user pressed escape
                    },
                });
                if ($('body').hasClass("modal-open"))
                {
                    box.on('hidden.bs.modal', function (e) {
                        $('body').addClass('modal-open');
                    });
                }

            }
        });
        return false;
    }

</script>

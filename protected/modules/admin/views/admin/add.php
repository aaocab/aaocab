<?php
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');

$typelist = Admins::getRolesList();
foreach ($typelist as $key => $type_list)
{
	$arrtypelist[] = array("id" => $key, "text" => $type_list);
}
?>
<div class="row">
    <div class="col-lg-8 col-md-6 col-sm-8 pb10" style="float: none; margin: auto">
        <div style="text-align:center;" class="col-xs-12">
        </div>
        <div class="row">
            <div class="upsignwidt">
                <div class="col-xs-12">
					<?php
					$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'admins-register-form', 'enableClientValidation' => true,
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
					?>
                    <div class="panel panel-default">
                        <div class="panel-body">
							<div>	<?php echo CHtml::errorSummary($model); ?></div>
                            <div class="row">
                                <div class="col-xs-6 pl20 pr20">  <?php echo $form->textFieldGroup($model, 'adm_fname', array('label' => 'First Name')) ?></div>
                                <div class="col-xs-6 pl20 pr20"> <?php echo $form->textFieldGroup($model, 'adm_lname', array('label' => 'Last Name')) ?></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 pl20 pr20">  <?php echo $form->textFieldGroup($model, 'adm_user', array('label' => 'User ID')) ?></div>
                                <div class="col-xs-6 pl20 pr20"> <?php echo $form->passwordFieldGroup($model, 'adm_passwd1', array('label' => 'Password')) ?></div>
                            </div>
							<div class="row">
                                <div class="col-xs-6 pl20 pr20">
									<? $strpickdate = ($pmodel->adp_hiring_date == '') ? date('Y-m-d') : $pmodel->adp_hiring_date; ?>
									<?php
									echo
									$form->datePickerGroup($pmodel, 'adp_hiring_date', array('label'			 => 'Hiring Date',
										'widgetOptions'	 => array('options' => array('autoclose' => true, 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Hiring Date', 'value' => DateTimeFormat::DateToDatePicker($strpickdate), 'class' => 'input-group border-gray full-width route-focus')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
									?>
								</div>
								<div class="col-xs-6 pl20 pr20">
									<div class="form-group"> 
										<label>Designation</label>
										<?php
										$desigArr	 = Designation::getList();
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $pmodel,
											'attribute'		 => 'adp_designation_id',
											'val'			 => $pmodel->adp_designation_id, //explode(',', $model->adm_teams),
											'data'			 => $desigArr,
											'htmlOptions'	 => array('style'			 => 'width:100%',
												'placeholder'	 => 'Select Designation', 'style'			 => 'width: 100%')
										));
										?>
									</div>
								</div>
                            </div>
							<div class="row">
								<div class="col-xs-6 pl20 pr20"> <?php echo $form->textFieldGroup($pmodel, 'adp_emp_code', array('label' => 'Employee ID Code')) ?></div>
								<div class="col-xs-6 pl20 pr20">
									<div class="form-group"> 
										<label>Assigned Team Lead</label>
										<?php
										$leadArr	 = Admins::model()->getAdminList();
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $pmodel,
											'attribute'		 => 'adp_team_leader_id',
											'val'			 => $pmodel->adp_team_leader_id,
											'data'			 => $leadArr,
											'htmlOptions'	 => array('style'			 => 'width:100%',
												'placeholder'	 => 'Select Team Lead', 'style'			 => 'width: 100%')
										));
										?>
									</div>
								</div>
							</div>
                            <div class="row">
                                <div class="col-xs-6 pl20 pr20">  <?php echo $form->textFieldGroup($model, 'adm_email', array('label' => 'Email')) ?></div>
								<div class="col-xs-6 pl20 pr20">  <?php echo $form->textFieldGroup($model, 'adm_phone', array('label' => 'Phone')) ?></div>
								<div class="form-group"> 
									<div class="col-xs-6 pl20 pr20"> 
										<label class="control-label">Access Role(s)</label>
										<?php
										if ($_REQUEST['admid'] != "")
										{
											$auth		 = Yii::app()->authManager;
											$authassign	 = $_POST[Admins][adm_attempt];
											$arr1		 = $auth->getAuthAssignments($_REQUEST['admid']);

											$arr2 = "";
											foreach ($arr1 as $key => $value)
											{
												$arr2 .= ',' . $key;
											}
											$arr = explode(',', $arr2);
										}
										else
										{

											$arr = explode(',', $model->adm_attempt);
										}
										$model->adm_attempt	 = $arr;
										$data				 = CJSON::encode($arrtypelist);
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'adm_attempt',
											'val'			 => $model->adm_attempt,
											'asDropDownList' => FALSE,
											'options'		 => array('data' => new CJavaScriptExpression($data), 'multiple' => true),
											'htmlOptions'	 => array('multiple' => 'multiple', 'style' => 'width: 100%', 'placeholder' => "Assign Role", 'label' => '')
										));
										?>
										<span class="has-error"><?php echo $form->error($model, 'adm_attempt'); ?></span>
									</div>
								</div>
                            </div>



							<div class="row">
								<div class="col-xs-4 p10 pr20">
									<label>Is this person part of field team or back-office?</label>
									<?php
									echo
									$form->radioButtonListGroup($model, 'adm_role', array(
										'label'			 => '', 'widgetOptions'	 => array(
											'data' => Admins::model()->teamType,
										),
										'inline'		 => true,
											)
									);
									?>
								</div>
								<div class="col-xs-4 p10 pr20">
									<label>If field team, then which regions is this person involved with:</label>
									<?php
									//	$regionarr			 = Promos::model()->getRegion();
									$regionarr			 = Admins::model()->regionArr;
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'adm_region',
										'val'			 => explode(',', $model->adm_region),
										'data'			 => $regionarr,
										'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
											'placeholder'	 => 'Select Region', 'style'			 => 'width: 100%')
									));
									?>
								</div>


								<div class="col-xs-4 p10 pr20">

									<label class="control-label">Booking Type:</label>
									<?php
									$bookingTypesArr	 = Booking::model()->booking_type;
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'adm_booking_type',
										'val'			 => explode(',', $model->adm_booking_type),
										'data'			 => $bookingTypesArr,
										'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
											'placeholder'	 => 'Booking Type')
									));
									?>

								</div>
							</div>


							<div class="row">
								<div class="col-xs-6 p10 pr20">
									<label>Team</label>
									<?php
									$teamarr			 = Teams::getMappedList();
									$cdtData			 = json_decode($pmodel->adp_cdt_id);
									$cdtId				 = "";
									foreach ($cdtData as $cdt)
									{
										$cdtId .= $cdt->cdtId . ",";
									}
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $pmodel,
										'attribute'		 => 'adp_cdt_id',
										'val'			 => explode(',', rtrim($cdtId, ",")),
										'data'			 => $teamarr,
										'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
											'placeholder'	 => 'Select team should act', 'style'			 => 'width: 100%', 'required'		 => true)
									));
									?>
								</div>
								<div class="col-xs-6 p10"><?php echo $form->checkboxGroup($model, 'adm_opps_app_access', ['label' => 'Ops App access', 'widgetOptions' => ['htmlOptions' => []]]) ?></div>
								<div class="col-xs-6 p10"><?php echo $form->checkboxGroup($pmodel, 'adp_auto_allocated', ['label' => 'Auto allocated Lead', 'widgetOptions' => ['htmlOptions' => []]]) ?></div>

								<?php
								echo $form->hiddenField($pmodel, 'cdtId');
								?>
							</div>



                            <div class="col-xs-12"> 
								<div class="panel-footer" style="text-align: center">
									<?php echo CHtml::submitButton($isNew, array('class' => 'btn  btn-primary')); ?>
                                </div>
							</div>

						</div><?php $this->endWidget(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php echo CHtml::endForm(); ?>
	<script>
        array = [<?php echo trim($cdtId, ","); ?>];
        $("#AdminProfiles_cdtId").val("<?php echo trim($cdtId, ","); ?>");
        $("#Admins_adm_role_1").click(function () {
            $('#Admins_adm_region').select2("val", '');
        });
        $("#Admins_adm_role_0").click(function () {
            $('#Admins_adm_region').select2("val", '7');
        });


        $('#AdminProfiles_adp_cdt_id').on('change', function (evt) {
            if ("added" in evt)
            {
                array.push(evt.added.id);
                $("#AdminProfiles_cdtId").val(array.join(","));
            }
            if ("removed" in evt)
            {
                array = array.filter(function (event) {
                    return event != evt.removed.id;
                });
                $("#AdminProfiles_cdtId").val(array.join(","));
            }
        });

	</script>



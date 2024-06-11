<style>
    .checkbox-inline{
        padding-left: 0 !important;
    }
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
    .new-booking-list label{ font-size: 11px;}
	.usertype,
	.cash,
	.coin,
	.fixed{ 
		padding: 10px; 
		margin: 10px; 
		border: 1px solid silver; 
	}
</style>
<?
$version	 = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/promo.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/city.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/plugins/form-typeahead/typeahead.bundle.min.js');
$jsrefresh	 = "
if($.isFunction(window.redirectList))
{
window.redirectList();
}
else
{
window.location.reload();
}
";
$datefrom	 = $model->prm_valid_from != '' ? $model->prm_valid_from : date('Y-m-d H:i:s');
$dateTo		 = $model->prm_valid_upto != '' ? $model->prm_valid_upto : date('Y-m-d H:i:s', strtotime('+1 year 6am'));
?>

<div class="row">
    <div class="col-xs-12 col-md-11 col-lg-11  new-booking-list" style="float: none; margin: auto">
		<?php
		$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'promo-form', 'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
				'afterValidate'		 => 'js:function(form,data,hasError){
						if(!hasError){
							$.ajax({
							"type":"POST",
							"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/promos/add')) . '",
							"data":form.serialize(),
									"dataType": "json",
									"success":function(data1){
											if(data1.success)
											{
												alert("Promo Created Sucessfully");
												window.location.reload(true);
											}
											else
											{
												alert(data1.error);
											}
									},
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
				'class' => 'form-horizontal',
			),
		));
		/* @var $form TbActiveForm */
		?>
		<input type="hidden" id="promoid" name="promoid" value="<?= $model->prm_id; ?>">
		<div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default panel-border">
                    <div class="panel-body">
						<div class="row mb15">
							<?= CHtml::errorSummary($model); ?> 
							<div class="col-xs-12 col-sm-3">
								<label>Promo Code *</label>
								<?php
								if ($model->prm_id == 0 || $model->prm_id == '')
								{
									$readonly = '';
								}
								else
								{
									$readonly = 'readonly';
								}
								?>
								<?= $form->textFieldGroup($model, 'prm_code', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('readonly' => $readonly)))) ?>
							</div>
							<div class="col-xs-12 col-sm-5">

								<label>Description *</label>
								<?= $form->textAreaGroup($model, 'prm_desc', array('label' => '','widgetOptions' => array('htmlOptions' => array('style' => 'height:75px;')))) ?>
							</div>
							<div class="col-xs-12 col-md-4">
								<label>Applicable Platform</label>
								<?=
								$form->checkboxListGroup($model, 'prm_applicable_platform', array('label'			 => '',
									'widgetOptions'	 => array('data' => Promos::$source_type), 'inline'		 => true, 'htmlOptions'	 => ['class' => 'p0']));
								?>
							</div>
						</div>

						<div class="row mb15">
							<div class="col-xs-12 col-md-6">
								<label> Promo Applicable For</label>
								<?= $form->radioButtonListGroup($model, 'prm_user_type', array('label' => '', 'widgetOptions' => array('htmlOptions' => [], 'data' => Promos::$promoUseType), 'inline' => true)) ?>
							</div>
						</div>

                        <div class="row mb15">
							<div class="col-xs-12 col-md-6"><label>Offer Valid From</label>
								<div class="row ">
									<div class="col-xs-12 col-sm-7 pr5">
										<?=
										$form->datePickerGroup($model, 'prm_valid_from_date', array('label'			 => '',
											'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => true, 'value' => date('d/m/Y', strtotime($datefrom)))), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
										?>
									</div>

									<div class="col-xs-12 col-sm-5 pl0">
										<?php
										if ($model->prm_valid_from != '')
										{
											$ptime = date('h:i A', strtotime($model->prm_valid_from));
										}
										else
										{
											$ptime = '00:00:00';
										}
										$timeArr = Filter::getTimeDropArr($ptime);
										echo $form->dropDownList($model, 'prm_valid_from_time', $timeArr, ['id' => 'prm_valid_from_time_mf1', 'class' => 'form-control', 'required']);
										?>
									</div>
								</div>
							</div>

							<div class="col-xs-12 col-md-6"><label>Offer Valid Upto</label>
								<div class="row">
									<div class="col-xs-12 col-sm-7 pr5">
										<?=
										$form->datePickerGroup($model, 'prm_valid_upto_date', array('label'			 => '',
											'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => true, 'value' => date('d/m/Y', strtotime($dateTo)))), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
										?>
									</div>
									<div class="col-xs-12 col-sm-5 pl0">
										<?php
										if ($model->prm_valid_upto != '')
										{
											$ptime = date('h:i A', strtotime($model->prm_valid_upto));
										}
										else
										{
											$ptime = '00:00:00';
										}
										$timeArr	 = Filter::getTimeDropArr($ptime);
										echo $form->dropDownList($model, 'prm_valid_upto_time', $timeArr, ['id' => 'prm_valid_upto_time_mf1', 'class' => 'form-control', 'required']);
										?>

									</div>
								</div>
							</div>
						</div>
						<div class="row mb15">
							<div class="col-xs-12 col-md-2">
								<label>Use Max</label>
								<?= $form->numberFieldGroup($model, 'prm_use_max', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Max user']))) ?>
							</div>
							<div class="col-xs-12 col-md-2">
								<label>Applicable User</label>
								<?=
								$form->checkboxListGroup($model, 'prm_applicable_user', array('label'			 => '',
									'widgetOptions'	 => array('data' => [0 => 'All user']), 'inline'		 => true, 'htmlOptions'	 => ['class' => 'p0']));
								?>
							</div>
							<div class="col-xs-12 col-md-2">
								<label>Activated On</label>
								<?=
								$form->checkboxListGroup($model, 'prm_activate_on', array('label'			 => '',
									'widgetOptions'	 => array('data' => [0 => 'Immediate']), 'inline'		 => true, 'htmlOptions'	 => ['class' => 'p0']));
								?>
							</div>

							<div class="col-xs-12 col-md-2">
								<label>Applicable Type</label>
								<?=
								$form->checkboxListGroup($model, 'prm_applicable_type', array('label'			 => '',
									'widgetOptions'	 => array('data' => [0 => 'Manual Apply']), 'inline'		 => true, 'htmlOptions'	 => ['class' => 'p0']));
								?>
							</div>
							<div class="col-xs-12 col-md-2">
								<label>Next Trip Applicable</label>
								<?=
								$form->checkboxListGroup($model, 'prm_applicable_nexttrip', array('label'			 => '',
									'widgetOptions'	 => array('data' => [1 => 'Yes']), 'inline'		 => true, 'htmlOptions'	 => ['class' => 'p0']));
								?>
							</div>
							<div class="col-xs-12 col-md-2">
								<label class="minbaseamt">Minimum Base Amount</label>
								<label class="mingftamt hide">Minimum Gift Card Amount</label>
								<?= $form->numberFieldGroup($model, 'prm_min_base_amount', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Minimum Base Amount']))) ?>
							</div>
						</div>
						<div class="row mb15">
							<div class="col-xs-12 col-md-2">
								<label>User Logged In</label>
								<?=
								$form->checkboxListGroup($model, 'prm_logged_in', array('label'			 => '',
									'widgetOptions'	 => array('data' => [1 => 'Yes']), 'inline'		 => true, 'htmlOptions'	 => ['class' => 'p0']));
								?>
							</div>
							<div class="bookinginfo">
								<div class="col-xs-12 col-md-2">
									<label>Minimum Booking</label>
									<?= $form->numberFieldGroup($model, 'prm_booked_min', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Minimum Booking']))) ?>
								</div>
								<div class="col-xs-12 col-md-2">
									<label>Maximum Booking</label>
									<?= $form->numberFieldGroup($model, 'prm_booked_max', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Maximum Booking']))) ?>
								</div>
								<div class="col-xs-12 col-md-2">
									<label>Minimum Complete Booking</label>
									<?= $form->numberFieldGroup($model, 'prm_complete_min', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Minimum Complete Booking']))) ?>
								</div>
								<div class="col-xs-12 col-md-2">
									<label>Maximum Complete Booking</label>
									<?= $form->numberFieldGroup($model, 'prm_complete_max', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Maximum Complete Booking']))) ?>
								</div>
								<div class="col-xs-12 col-md-2">
									<label>Not Travelled</label>
									<?= $form->numberFieldGroup($model, 'prm_not_travelled', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Days']))) ?>
								</div>
							</div>	
							<div class="col-xs-12 col-md-4">
								<label>Applicable user category</label>
								<?php
								    echo $form->checkboxListGroup($model, 'prm_usr_cat_type', array('label'			 => '',
									'widgetOptions'	 => array('data' => UserCategoryMaster::catDropdownList()), 'inline'		 => true, 'htmlOptions'	 => ['class' => 'p0']));
								?>
							</div>
							<div class="col-xs-12 col-md-2">
								<label>Allow Negative Addon</label>
								<?=
								$form->checkboxListGroup($model, 'prm_allow_negative_addon', array('label'			 => '',
									'widgetOptions'	 => array('data' => [1 => 'Yes']), 'inline'		 => true, 'htmlOptions'	 => ['class' => 'p0']));
								?>
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>
		<div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default panel-border">
                    <div class="panel-body">
						<div class="row mb15">
							<div class="col-xs-12 col-md-6">
								<label> Discount Type</label>
								<?= $form->radioButtonListGroup($calModel, 'pcn_type', array('label' => '', 'widgetOptions' => array('htmlOptions' => [], 'data' => Promos::$promoType), 'inline' => true)) ?>
							</div>
						</div>

						<div class="row mb15">
							<fieldset class="cash">
								<legend>Cash:</legend>
								<div class="col-xs-12 col-md-2">
									<label> Discount Value Type(Cash)</label>
									<?= $form->radioButtonListGroup($calModel, 'pcn_value_type_cash', array('label' => '', 'widgetOptions' => array('htmlOptions' => [], 'data' => Promos::$valueType), 'inline' => true)) ?>
								</div>
								<div class="col-xs-12 col-md-4">
									<label> Discount Value(Cash)</label>
									<?= $form->numberFieldGroup($calModel, 'pcn_value_cash', array('label' => '')) ?>
								</div>
								<div class="col-xs-12 col-md-6">
									<div class="row">
										<div class="col-xs-12 col-sm-6 pr5">
											<label> Minimum discount given(Cash)</label>
											<?= $form->numberFieldGroup($calModel, 'pcn_min_cash', array('label' => '')) ?>
										</div>
										<div class="col-xs-12 col-sm-6 pl0">
											<label> Discount cannot exceeded(Cash)</label>
											<?= $form->numberFieldGroup($calModel, 'pcn_max_cash', array('label' => '')) ?>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
						<div class="row mb15">
							<fieldset class='coin hide'>
								<legend>Coins:</legend>
								<div class="col-xs-12 col-md-2">
									<label> Discount Value Type(Coins)</label>
									<?= $form->radioButtonListGroup($calModel, 'pcn_value_type_coins', array('label' => '', 'widgetOptions' => array('htmlOptions' => [], 'data' => Promos::$valueType), 'inline' => true)) ?>
								</div>
								<div class="col-xs-12 col-md-4">
									<label> Discount Value(Coins)</label>
									<?= $form->numberFieldGroup($calModel, 'pcn_value_coins', array('label' => '')) ?>
								</div>
								<div class="col-xs-12 col-md-6">
									<div class="row">
										<div class="col-xs-12 col-sm-6 pr5">
											<label> Minimum discount given(Coins)</label>
											<?= $form->numberFieldGroup($calModel, 'pcn_min_coins', array('label' => '')) ?>
										</div>
										<div class="col-xs-12 col-sm-6 pl0">
											<label> Discount cannot exceeded(Coins)</label>
											<?= $form->numberFieldGroup($calModel, 'pcn_max_coins', array('label' => '')) ?>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
						<div class="row mb15">
							<fieldset class='fixed hide'>
								<legend>Fixed Price:</legend>
								<div class="col-xs-12 col-md-4">
									<label> Price</label>
									<?= $form->numberFieldGroup($calModel, 'pcn_fixed_price', array('label' => '')) ?>
								</div>
							</fieldset>
						</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default panel-border">
                    <div class="panel-body area-filter">
						<div class="row mb15">
							<div class="col-xs-12 col-md-4">
								<div class="form-group">
									<label class="control-label">Area Type Source</label>
									<?php
									$areaTypeArr = Promos::$areaType;
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $entityModel,
										'attribute'		 => 'pef_area_type_from',
										'val'			 => $entityModel->pef_area_type_from,
										'data'			 => $areaTypeArr,
										//'asDropDownList' => FALSE,
										//'options'		 => array('data' => new CJavaScriptExpression($weekDaysArr)),
										'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => '',
											'placeholder'	 => 'Select Area type')
									));
									?>
								</div>
							</div>

							<div class="col-xs-12 col-md-8 hide" id="fromArea">
								<div class="form-group">
									<label class="control-label">Area</label>
									<?php
									$areaFromArr = '[]';
									if ($entityModel->pef_area_type_from == 1)
									{
										$areaFromArr = Zones::model()->getJSON();
									}
									else if ($entityModel->pef_area_type_from == 2)
									{
										$areaFromArr = States::model()->getJSON();
									}
									else if ($entityModel->pef_area_type_from == 3)
									{
										$areaFromArr = Cities::getAllCityListDrop();
									}
									else if ($entityModel->pef_area_type_from == 4)
									{
										$areaFromArr = Promos::getRegionJSON();
									}
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $entityModel,
										'attribute'		 => 'pef_area_from_id',
										'val'			 => [$entityModel->pef_area_from_id],
										//'data'			 => $areaFromArr,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($areaFromArr), 'multiple' => true),
										'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
											'placeholder'	 => 'Select Area')
									));
									?>
								</div>
							</div>
						</div>
						<div class="row mb15">
							<div class="col-xs-12 col-md-4">
								<div class="form-group">
									<label class="control-label">Area Type Destination</label>
									<?php
									$areaTypeArr = Promos::$areaType;
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $entityModel,
										'attribute'		 => 'pef_area_type_to',
										'val'			 => $entityModel->pef_area_type_to,
										'data'			 => $areaTypeArr,
										//'asDropDownList' => FALSE,
										//'options'		 => array('data' => new CJavaScriptExpression($weekDaysArr)),
										'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => '',
											'placeholder'	 => 'Select Area type')
									));
									?>
								</div>
							</div>

							<div class="col-xs-12 col-md-8 hide" id="toArea">
								<div class="form-group">
									<label class="control-label">Area</label>
									<?php
									$areaToArr	 = '[]';
									if ($entityModel->pef_area_type_to == 1)
									{
										$areaToArr = Zones::model()->getJSON();
									}
									else if ($entityModel->pef_area_type_to == 2)
									{
										$areaToArr = States::model()->getJSON();
									}
									else if ($entityModel->pef_area_type_to == 3)
									{
										$areaToArr = Cities::getAllCityListDrop();
									}
									else if ($entityModel->pef_area_type_to == 4)
									{
										$areaToArr = Promos::getRegionJSON();
									}
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $entityModel,
										'attribute'		 => 'pef_area_to_id',
										'val'			 => [$entityModel->pef_area_to_id],
										//'data'			 => $areaToArr,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($areaToArr), 'multiple' => true),
										'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
											'placeholder'	 => 'Select Area')
									));
									?>
								</div>
							</div>
						</div>
						<div class="row mb15">
							<div class="col-xs-12 col-md-4">
								<div class="form-group">
									<label class="control-label">Area Type (Source/Destination)</label>
									<?php
									$areaTypeArr = Promos::$areaType;
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $entityModel,
										'attribute'		 => 'pef_area_type',
										'val'			 => $entityModel->pef_area_type,
										'data'			 => $areaTypeArr,
										//'asDropDownList' => FALSE,
										//'options'		 => array('data' => new CJavaScriptExpression($weekDaysArr)),
										'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => '',
											'placeholder'	 => 'Select Area type')
									));
									?>
								</div>
							</div>

							<div class="col-xs-12 col-md-8 hide" id="fromToArea">
								<div class="form-group">
									<label class="control-label">Area (Source/Destination)</label>
									<?php
									$areaFromArr = '[]';
									if ($entityModel->pef_area_type == 1)
									{
										$areaFromArr = Zones::model()->getJSON();
									}
									else if ($entityModel->pef_area_type == 2)
									{
										$areaFromArr = States::model()->getJSON();
									}
									else if ($entityModel->pef_area_type == 3)
									{
										$areaFromArr = Cities::getAllCityListDrop();
									}
									else if ($entityModel->pef_area_type == 4)
									{
										$areaFromArr = Promos::getRegionJSON();
									}
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $entityModel,
										'attribute'		 => 'pef_area_id',
										'val'			 => [$entityModel->pef_area_id],
										//'data'			 => $areaFromArr,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($areaFromArr), 'multiple' => true),
										'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
											'placeholder'	 => 'Select Area')
									));
									?>
								</div>
							</div>
						</div>
						<div class="row mb15">
							<div class="col-xs-12 col-md-6"><label> Booking Create Range Between</label>
								<div class="row ">
									<div class="col-xs-12 col-sm-7 pr5">
										<?=
										$form->datePickerGroup($model, 'prm_createdate_from_date', array('label'			 => '',
											'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => false, 'value' => ($model->prm_createdate_from != '' ? date('d/m/Y', strtotime($model->prm_createdate_from)) : ''), 'placeholder' => 'Create Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
										?>
									</div>

									<div class="col-xs-12 col-sm-5 pl0">
										<?php
										if ($model->prm_createdate_from != '')
										{
											$ptime = date('h:i A', strtotime($model->prm_createdate_from));
										}
										else
										{
											$ptime = '00:00:00';
										}
										$timeArr = Filter::getTimeDropArr($ptime);
										echo $form->dropDownList($model, 'prm_createdate_from_time', $timeArr, ['id' => 'prm_createdate_from_time_mf1', 'class' => 'form-control', 'required']);
										?>
									</div>
								</div>
							</div>

							<div class="col-xs-12 col-md-6"><label> To</label>
								<div class="row">
									<div class="col-xs-12 col-sm-7 pr5">
										<?=
										$form->datePickerGroup($model, 'prm_createdate_to_date', array('label'			 => '',
											'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => false, 'value' => ($model->prm_createdate_to != '' ? date('d/m/Y', strtotime($model->prm_createdate_to)) : ''), 'placeholder' => 'Create Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
										?>
									</div>
									<div class="col-xs-12 col-sm-5 pl0">
										<?php
										if ($model->prm_createdate_to != '')
										{
											$ptime = date('h:i A', strtotime($model->prm_createdate_to));
										}
										else
										{
											$ptime = '00:00:00';
										}
										$timeArr		 = Filter::getTimeDropArr($ptime);
										echo $form->dropDownList($model, 'prm_createdate_to_time', $timeArr, ['id' => 'prm_createdate_to_time_mf1', 'class' => 'form-control', 'required']);
										?>
									</div>
								</div>
							</div>
						</div>
						<div class="row mb15">
							<div class="col-xs-12 col-md-6">
								<div class="form-group">
									<input type="checkbox" name="createdateChk" id="createdateChk">Createdate Validation
								</div>
							</div>
						</div>
						<div class="row mb15 createDate hide">
							<div class="col-xs-12 col-md-6">
								<div class="form-group">
									<label class="control-label">Days in a Week(Create date)</label>
									<?php
									$weekDaysArr	 = PromoDateFilter::getWeekDaysList();
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $dateModel,
										'attribute'		 => 'pcd_weekdays_create',
										'val'			 => $dateModel->pcd_weekdays_create,
										'data'			 => $weekDaysArr,
										//'asDropDownList' => FALSE,
										//'options'		 => array('data' => new CJavaScriptExpression($weekDaysArr)),
										'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
											'placeholder'	 => 'Select Week Days')
									));
									?>
								</div>
							</div>

							<div class="col-xs-12 col-md-6">
								<div class="form-group">
									<label class="control-label">Weeks(Create date)</label>
									<?php
									$weekArr		 = PromoDateFilter::getWeekList();
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $dateModel,
										'attribute'		 => 'pcd_weeks_create',
										'val'			 => $dateModel->pcd_weeks_create,
										'data'			 => $weekArr,
										//'asDropDownList' => FALSE,
										//'options'		 => array('data' => new CJavaScriptExpression($weekDaysArr)),
										'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
											'placeholder'	 => 'Select Week')
									));
									?>
								</div>
							</div>
						</div>
						<div class="row mb15 createDate hide">
							<div class="col-xs-12 col-md-6">
								<div class="form-group">
									<label class="control-label">Days in a Month(Create date)</label>
									<?php
									$monthDaysArr	 = PromoDateFilter::getMonthDaysList();
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $dateModel,
										'attribute'		 => 'pcd_monthdays_create',
										'val'			 => $dateModel->pcd_monthdays_create,
										'data'			 => $monthDaysArr,
										//'asDropDownList' => FALSE,
										//'options'		 => array('data' => new CJavaScriptExpression($weekDaysArr)),
										'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
											'placeholder'	 => 'Select Month Days')
									));
									?>
								</div>
							</div>

							<div class="col-xs-12 col-md-6">
								<div class="form-group">
									<label class="control-label">Months(Create date)</label>
									<?php
									$monthArr		 = PromoDateFilter::getMonthList();
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $dateModel,
										'attribute'		 => 'pcd_months_create',
										'val'			 => $dateModel->pcd_months_create,
										'data'			 => $monthArr,
										//'asDropDownList' => FALSE,
										//'options'		 => array('data' => new CJavaScriptExpression($weekDaysArr)),
										'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
											'placeholder'	 => 'Select Month')
									));
									?>
								</div>
							</div>
						</div>
						<div class="row mb15">
							<div class="col-xs-12 col-md-6"><label> Booking Pickup Range Between</label>
								<div class="row ">
									<div class="col-xs-12 col-sm-7 pr5">
										<?=
										$form->datePickerGroup($model, 'prm_pickupdate_from_date', array('label'			 => '',
											'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => false, 'value' => ($model->prm_pickupdate_from != '' ? date('d/m/Y', strtotime($model->prm_pickupdate_from)) : ''), 'placeholder' => 'Pickup Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
										?>
									</div>

									<div class="col-xs-12 col-sm-5 pl0">
										<?php
										if ($model->prm_pickupdate_from != '')
										{
											$ptime = date('h:i A', strtotime($model->prm_pickupdate_from));
										}
										else
										{
											$ptime = '00:00:00';
										}
										$timeArr = Filter::getTimeDropArr($ptime);
										echo $form->dropDownList($model, 'prm_pickupdate_from_time', $timeArr, ['id' => 'prm_pickupdate_from_time_mf1', 'class' => 'form-control', 'required']);
										?>
									</div>
								</div>
							</div>

							<div class="col-xs-12 col-md-6"><label> To</label>
								<div class="row">
									<div class="col-xs-12 col-sm-7 pr5">
										<?=
										$form->datePickerGroup($model, 'prm_pickupdate_to_date', array('label'			 => '',
											'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => false, 'value' => ($model->prm_pickupdate_to != '' ? date('d/m/Y', strtotime($model->prm_pickupdate_to)) : ''), 'placeholder' => 'Pickup Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
										?>
									</div>
									<div class="col-xs-12 col-sm-5 pl0">
										<?php
										if ($model->prm_pickupdate_to != '')
										{
											$ptime = date('h:i A', strtotime($model->prm_pickupdate_to));
										}
										else
										{
											$ptime = '00:00:00';
										}
										$timeArr		 = Filter::getTimeDropArr($ptime);
										echo $form->dropDownList($model, 'prm_pickupdate_to_time', $timeArr, ['id' => 'prm_pickupdate_to_time_mf1', 'class' => 'form-control', 'required']);
										?>
									</div>
								</div>
							</div>
						</div>
						<div class="row mb15">
							<div class="col-xs-12 col-md-6">
								<div class="form-group">
									<input type="checkbox" name="pickupdateChk" id="pickupdateChk">Pickupdate Validation
								</div>
							</div>
						</div>
						<div class="row mb15 pickupDate hide">
							<div class="col-xs-12 col-md-6">
								<div class="form-group">
									<label class="control-label">Days in a Week(Pickup date)</label>
									<?php
									$weekDaysArr	 = PromoDateFilter::getWeekDaysList();
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $dateModel,
										'attribute'		 => 'pcd_weekdays_pickup',
										'val'			 => $dateModel->pcd_weekdays_pickup,
										'data'			 => $weekDaysArr,
										//'asDropDownList' => FALSE,
										//'options'		 => array('data' => new CJavaScriptExpression($weekDaysArr)),
										'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
											'placeholder'	 => 'Select Week Days')
									));
									?>
								</div>
							</div>

							<div class="col-xs-12 col-md-6">
								<div class="form-group">
									<label class="control-label">Weeks(Pickup date)</label>
									<?php
									$weekArr		 = PromoDateFilter::getWeekList();
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $dateModel,
										'attribute'		 => 'pcd_weeks_pickup',
										'val'			 => $dateModel->pcd_weeks_pickup,
										'data'			 => $weekArr,
										//'asDropDownList' => FALSE,
										//'options'		 => array('data' => new CJavaScriptExpression($weekDaysArr)),
										'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
											'placeholder'	 => 'Select Week')
									));
									?>
								</div>
							</div>
						</div>
						<div class="row mb15 pickupDate hide">
							<div class="col-xs-12 col-md-6">
								<div class="form-group">
									<label class="control-label">Days in a Month(Pickup date)</label>
									<?php
									$monthDaysArr	 = PromoDateFilter::getMonthDaysList();
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $dateModel,
										'attribute'		 => 'pcd_monthdays_pickup',
										'val'			 => $dateModel->pcd_monthdays_pickup,
										'data'			 => $monthDaysArr,
										//'asDropDownList' => FALSE,
										//'options'		 => array('data' => new CJavaScriptExpression($weekDaysArr)),
										'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
											'placeholder'	 => 'Select Month Days')
									));
									?>
								</div>
							</div>

							<div class="col-xs-12 col-md-6">
								<div class="form-group">
									<label class="control-label">Months(Pickup date)</label>
									<?php
									$monthArr		 = PromoDateFilter::getMonthList();
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $dateModel,
										'attribute'		 => 'pcd_months_pickup',
										'val'			 => $dateModel->pcd_months_pickup,
										'data'			 => $monthArr,
										//'asDropDownList' => FALSE,
										//'options'		 => array('data' => new CJavaScriptExpression($weekDaysArr)),
										'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
											'placeholder'	 => 'Select Month')
									));
									?>
								</div>
							</div>
						</div>
						<div class="row mb15">
							<div class="col-xs-12 col-md-6">
								<div class="form-group">
									<label class="control-label">Booking Type</label>
									<?php
									$bookingTypeArr	 = Booking::model()->booking_type;
									unset($bookingTypeArr[2]);
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $entityModel,
										'attribute'		 => 'pef_booking_type',
										'val'			 => $entityModel->pef_booking_type,
										'data'			 => $bookingTypeArr,
										//'asDropDownList' => FALSE,
										//'options'		 => array('data' => new CJavaScriptExpression($weekDaysArr)),
										'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
											'placeholder'	 => 'Select Booking Type')
									));
									?>
								</div>
							</div>
							<div class="col-xs-12 col-md-6">
								<div class="form-group">
									<label class="control-label">Car Type</label>
									<?php
									$returnType					 = "list";
									$vehcleList					 = CHtml::listData(VehicleCategory::model()->findAll(), 'vct_id', 'vct_label');
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $entityModel,
										'attribute'		 => 'pef_cab_type',
										'val'			 => $entityModel->pef_cab_type,
										'data'			 => $vehcleList,
										//'asDropDownList' => FALSE,
										//'options'		 => array('data' => new CJavaScriptExpression($weekDaysArr)),
										'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
											'placeholder'	 => 'Select Car Type')
									));
									?>
								</div>
							</div>
						</div>
						<div class="row mb15">
							<div class="col-xs-12 col-sm-3">
								<label>Minimum create time for promo apply(in hour)*</label>
								<?= $form->textFieldGroup($model, 'prm_createtime_before', array('label' => '')) ?>
							</div>
							<div class="col-xs-12 col-sm-3">
								<label>Maximum create time for promo apply(in hour)*</label>
								<?= $form->textFieldGroup($model, 'prm_createtime_after', array('label' => '')) ?>
							</div>
							<div class="col-xs-12 col-sm-3">
								<label>Minimum pickup time for promo apply(in working hour) *</label>
								<?= $form->textFieldGroup($model, 'prm_pickuptime_before', array('label' => '')) ?>
							</div>
							<div class="col-xs-12 col-sm-3">
								<label>Maximum pickup time for promo apply(in working hour) *</label>
								<?= $form->textFieldGroup($model, 'prm_pickuptime_after', array('label' => '')) ?>
							</div>
						</div>
						
					</div>
					<div class="row">
							<div class="col-xs-12 text-center pb10">
								<input type="submit" value="Submit" name="yt0" id="promosubmit" class="btn btn-primary pl30 pr30">
							</div>
					</div>
				</div>
			</div>
		</div>


		<?php $this->endWidget(); ?>
    </div>
</div>

<script>
    var promo = new Promo();
    var city = new City();
    $(document).ready(function ()
    {
        if ($('#PromoEntityFilter_pef_area_type_from').val() != '')
        {
            $('#fromArea').removeClass('hide');
        }
        if ($('#PromoEntityFilter_pef_area_type_to').val() != '')
        {
            $('#toArea').removeClass('hide');
        }
		if ($('#PromoEntityFilter_pef_area_type').val() != '')
        {
            $('#fromToArea').removeClass('hide');
        }
        if ($('#Promos_prm_use_max_1').is(':checked'))
        {
            $('#useMaxOther').removeClass('hide');
        }
        promo.selectType();
        promo.selectValueTypeCash();
        promo.selectValueTypeCoins();
		promo.selectValueTypeGiftCard();
        if ($('#PromoDateFilter_pcd_weekdays_create').val() != null || $('#PromoDateFilter_pcd_weeks_create').val() != null || $('#PromoDateFilter_pcd_monthdays_create').val() != null || $('#PromoDateFilter_pcd_months_create').val() != null)
        {
            $('#createdateChk').attr('checked', true);
            $('#createdateChk').click();
        }
        if ($('#PromoDateFilter_pcd_weekdays_pickup').val() != null || $('#PromoDateFilter_pcd_weeks_pickup').val() != null || $('#PromoDateFilter_pcd_monthdays_pickup').val() != null || $('#PromoDateFilter_pcd_months_pickup').val() != null)
        {
            $('#pickupdateChk').attr('checked', true);
            $('#pickupdateChk').click();
        }
    });
	
    $('#<?= CHtml::activeId($calModel, 'pcn_type') ?>').click(function ()
    {
        promo.selectType();
    });

    $('#<?= CHtml::activeId($calModel, 'pcn_value_type_cash') ?>').click(function ()
    {
        promo.selectValueTypeCash();
    });

    $('#<?= CHtml::activeId($calModel, 'pcn_value_type_coins') ?>').click(function ()
    {
        promo.selectValueTypeCoins();
    });
	
	$('#<?= CHtml::activeId($model, 'prm_user_type') ?>').click(function ()
    {
        promo.selectValueTypeGiftCard();
    });

    $('#<?= CHtml::activeId($entityModel, 'pef_area_type_from') ?>').change(function ()
    {
        var model = {}
        var area = $('#<?= CHtml::activeId($entityModel, 'pef_area_type_from') ?>').val();
        $('#fromArea').removeClass('hide');
        model.area = area;
        model.id = 'PromoEntityFilter_pef_area_from_id';
        city.model = model;
        city.showArea();
    });
    $('#<?= CHtml::activeId($entityModel, 'pef_area_type_to') ?>').change(function ()
    {
        var model = {};
        var area = $('#<?= CHtml::activeId($entityModel, 'pef_area_type_to') ?>').val();
        $('#toArea').removeClass('hide');
        model.area = area;
        model.id = 'PromoEntityFilter_pef_area_to_id';
        city.model = model;
        city.showArea();
    });
	$('#<?= CHtml::activeId($entityModel, 'pef_area_type') ?>').change(function ()
    {
        var model = {}
        var area = $('#<?= CHtml::activeId($entityModel, 'pef_area_type') ?>').val();
        $('#fromToArea').removeClass('hide');
        model.area = area;
        model.id = 'PromoEntityFilter_pef_area_id';
        city.model = model;
        city.showArea();
    });

    $('#pickupdateChk').click(function ()
    {
        if ($('#pickupdateChk').is(':checked') == true)
        {
            $('.pickupDate').removeClass('hide');
        }
        else
        {
            $('.pickupDate').addClass('hide');
        }
    });

    $('#createdateChk').click(function ()
    {
        if ($('#createdateChk').is(':checked') == true)
        {
            $('.createDate').removeClass('hide');
        }
        else
        {
            $('.createDate').addClass('hide');
        }
    });

    $('#Promos_prm_applicable_nexttrip_0').click(function ()
    {
        if ($('#Promos_prm_applicable_nexttrip_0').is(':checked'))
        {
            if (!$('#Promos_prm_applicable_user_0').is(':checked'))
            {
                $('#Promos_prm_applicable_user_0').click();
                $('#Promos_prm_applicable_user_0').attr('disabled', true);
            }
            else
            {
                $('#Promos_prm_applicable_user_0').attr('disabled', true);
            }
        }
        else
        {
            $('#Promos_prm_applicable_user_0').attr('disabled', false);
        }
    });
	
	$('#promosubmit').click(function()
	{
		$('#PromoGiftCard_gcr_cost_price_em_').html('');
		$("#PromoGiftCard_gcr_cost_price_em_").css('display','none');
		$("#PromoGiftCard_gcr_cost_price").css('border-color','#3c763d');
		var gcrCostPrice = $('#PromoGiftCard_gcr_cost_price').val();
		var userType     = $('#Promos_prm_user_type_1').is(':checked');
		if(gcrCostPrice == '' && userType == true )
		{
			$('#PromoGiftCard_gcr_cost_price_em_').html('Please enter cost price');
			$("#PromoGiftCard_gcr_cost_price_em_").css('display','block');
			$("#PromoGiftCard_gcr_cost_price_em_").css('color','#a94442');
			$("#PromoGiftCard_gcr_cost_price").css('border-color','#a94442');
			return false;
		}
	});

</script>
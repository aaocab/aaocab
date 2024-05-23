<?php
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/moment.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/daterangepicker.js', CClientScript::POS_HEAD);
?><style>
    .compact.panel .pagination {
		margin: 0;
	}
	.compact.panel .panel-heading+.panel-body {
		padding: 0;
	}
</style>
<div class="container-fluid p0"><div class="panel panel-white"><div class="panel-body">
			<div class="panel-advancedoptions" >

				<div class="row">
					<?php
					$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'priceanalysis-form', 'enableClientValidation' => true,
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
							'class' => '',
						),
					));
					/* @var $form TbActiveForm */
					?>

					<div class="col-xs-12 col-sm-4 col-lg-3">
						<label class="control-label">Date Range</label>
						<?
						$daterang			 = "Select Pickup Date Range";
						$aat_pickup_date1	 = ($model->aat_pickup_date1 == '') ? '' : $model->aat_pickup_date1;
						$aat_pickup_date2	 = ($model->aat_pickup_date2 == '') ? '' : $model->aat_pickup_date2;
						if ($aat_pickup_date1 != '' && $aat_pickup_date2 != '')
						{
							$daterang = date('F d, Y', strtotime($aat_pickup_date1)) . " - " . date('F d, Y', strtotime($aat_pickup_date2));
						}
						?>
						<div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
							<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
							<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
						</div>
						<?= $form->hiddenField($model, 'aat_pickup_date1'); ?>
						<?= $form->hiddenField($model, 'aat_pickup_date2'); ?>
					</div>
					<div class="col-xs-12 col-sm-4  col-lg-2">
						<label class="control-label">Booking Type</label>
						<?php
						$dataState = AgentApiTracking::getBookingType();
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'aat_booking_type',
							'val'			 => $model->aat_booking_type,
							'asDropDownList' => FALSE,
							'options'		 => array('data' => new CJavaScriptExpression($dataState), 'allowClear' => true),
							'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Type')
						));
						?>
					</div>
					<div class="col-xs-12 col-sm-4  col-lg-1"> 
<?= $form->textFieldGroup($model, 'aat_hours', array('widgetOptions' => ['htmlOptions' => ['onchange' => 'numberValidation()']])) ?>
					</div>
					<div class="col-xs-6 col-sm-4  col-md-3 col-lg-2">
						<div class="form-group">
							<label>Source Zone</label>
							<?php
							$zoneListJson	 = Zones::model()->getJSON();
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'sourcezone',
								'val'			 => $model->sourcezone,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($zoneListJson), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Source Zone')
							));
							?>
						</div>
					</div>

					<div class="col-xs-6 col-sm-4  col-md-3 col-lg-2">
						<div class="form-group">
							<label class="control-label">Destination Zone</label>
							<?php
							$zoneListJson	 = Zones::model()->getJSON();
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'destinationzone',
								'val'			 => $model->destinationzone,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($zoneListJson), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Destination Zone')
							));
							?>
						</div></div>
					<div class="col-xs-12 col-sm-4  col-lg-2">
						<label class="control-label">Filter Type</label>
						<?php
						$datafor1 = AgentApiTracking::getPriceAnalysisListFilterOption();
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'datafor',
							'val'			 => $model->datafor,
							'asDropDownList' => FALSE,
							'options'		 => array('data' => new CJavaScriptExpression($datafor1), 'allowClear' => false),
							'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Type')
						));
						?>
					</div>

					<!--<div class="col-xs-12 col-sm-4 col-md-4 col-lg-2" >
						<div class="form-group">
							<label class="control-label">Region </label>
							<?php
							/*$regionList			 = VehicleTypes::model()->getJSON(Vendors::model()->getRegionList());
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'region',
								'val'			 => $model->region,
								//'asDropDownList' => FALSE,
								'data'			 => Vendors::model()->getRegionList(),
								//'options' => array('data' => new CJavaScriptExpression($regionList), 'allowClear' => true),
								'htmlOptions'	 => array('class'			 => 'p0', 'multiple'		 => 'multiple',
									'style'			 => 'width: 100%', 'placeholder'	 => 'Select Region')
							));*/
							?>
						</div></div>-->

					<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
					<?php echo CHtml::submitButton('Search', array('class' => 'btn btn-primary full-width')); ?></div>
<?php $this->endWidget(); ?>
				</div>
<?php
		if ($model->datafor == 'cities')
		{
?>
				<div><b>Analysis By Cities</b></div>
				<div class="row">
					<div class="col-md-12">
						<div class="panel" >
							<div class="panel-body panel-no-padding p0 pt10">
								<div class="panel-scroll1">
									<div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
										<?php
										if (!empty($dataProvider))
										{
											$arr = [];
											if (is_array($dataProvider->getPagination()->params))
											{
												$arr = $dataProvider->getPagination()->params;
											}
											$params1							 = $arr + array_filter($_GET + $_POST);
											/* @var $dataProvider CActiveDataProvider */
											$dataProvider->pagination->pageSize	 = 30;
											$dataProvider->setPagination(['params' => $params1]);

											$this->widget('booster.widgets.TbGridView', array(
												'id'				 => 'pricesurgelist',
												'responsiveTable'	 => true,
												'dataProvider'		 => $dataProvider,
												'filter'			 => $model,
												'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
												'itemsCssClass'		 => 'table table-striped table-bordered mb0',
												'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
												'ajaxType'			 => 'POST',
												'columns'			 => array(
													array('name' => 'rutName', 'filter' => false, 'value' => '$data[rutName]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Route Name'),
													array('name' => 'totalRequest', 'filter' => false, 'value' => '$data[totalRequest]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Request'),
													array('name' => 'totalHold', 'filter' => false, 'value' => '$data[totalHold]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Hold'),
													array('name' => 'totalConfirmed', 'filter' => false, 'value' => '$data[totalConfirmed]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Confirmed'),
													array('name' => 'totalHoldCompact', 'filter' => false, 'value' => '$data[totalHoldCompact]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Hold Compact'),
													array('name' => 'totalConfirmedCompact', 'filter' => false, 'value' => '$data[totalConfirmedCompact]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Confirmed Compact'),
													array('name' => 'totalHoldSUV', 'filter' => false, 'value' => '$data[totalHoldSUV]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Hold SUV'),
													array('name' => 'totalConfirmedSUV', 'filter' => false, 'value' => '$data[totalConfirmedSUV]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Confirmed SUV'),
													array('name' => 'totalHoldSedan', 'filter' => false, 'value' => '$data[totalHoldSedan]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Hold Sedan'),
													array('name' => 'totalConfirmedSedan', 'filter' => false, 'value' => '$data[totalConfirmedSedan]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Confirmed Sedan'),
													array('name' => 'totalHoldAssuredSedan', 'filter' => false, 'value' => '$data[totalHoldAssuredSedan]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Hold Assured Sedan'),
													array('name' => 'totalConfirmedAssuredSedan', 'filter' => false, 'value' => '$data[totalConfirmedAssuredSedan]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Confirmed Assured Sedan'),
													array('name' => 'totalHoldAssuredInnova', 'value' => '$data[totalHoldAssuredInnova]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Hold Assured Innova')
											)));
										}
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
<?php
		}
		elseif ($model->datafor == 'zones')
		{
?>
				<div><b>Analysis By Zones</b></div>
				<div class="row">
					<div class="col-md-12">
						<div class="panel" >
							<div class="panel-body panel-no-padding p0 pt10">
								<div class="panel-scroll1">
									<div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
										<?php
										if (!empty($dataProvider1))
										{
											$arr = [];
											if (is_array($dataProvider1->getPagination()->params))
											{
												$arr = $dataProvider1->getPagination()->params;
											}
											$params1							 = $arr + array_filter($_GET + $_POST);
											$dataProvider1->setPagination(['params' => $params1]);

											/* @var $dataProvider CActiveDataProvider */
											$dataProvider1->pagination->pageSize	 = 30;
											$this->widget('booster.widgets.TbGridView', array(
												'id'				 => 'pricesurgelist1',
												'responsiveTable'	 => true,
												'dataProvider'		 => $dataProvider1,
												'filter'			 => $model,
												'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
												'itemsCssClass'		 => 'table table-striped table-bordered mb0',
												'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
												'ajaxType'			 => 'POST',
												'columns'			 => array(
													array('name' => 'rutName', 'filter' => false, 'value' => '$data[rutName]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Route Name'),
													array('name' => 'totalRequest', 'filter' => false, 'value' => '$data[totalRequest]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Request'),
													array('name' => 'totalHold', 'filter' => false, 'value' => '$data[totalHold]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Hold'),
													array('name' => 'totalConfirmed', 'filter' => false, 'value' => '$data[totalConfirmed]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Confirmed'),
													array('name' => 'totalHoldCompact', 'filter' => false, 'value' => '$data[totalHoldCompact]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Hold Compact'),
													array('name' => 'totalConfirmedCompact', 'filter' => false, 'value' => '$data[totalConfirmedCompact]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Confirmed Compact'),
													array('name' => 'totalHoldSUV', 'filter' => false, 'value' => '$data[totalHoldSUV]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Hold SUV'),
													array('name' => 'totalConfirmedSUV', 'filter' => false, 'value' => '$data[totalConfirmedSUV]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Confirmed SUV'),
													array('name' => 'totalHoldSedan', 'filter' => false, 'value' => '$data[totalHoldSedan]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Hold Sedan'),
													array('name' => 'totalConfirmedSedan', 'filter' => false, 'value' => '$data[totalConfirmedSedan]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Confirmed Sedan'),
													array('name' => 'totalHoldAssuredSedan', 'filter' => false, 'value' => '$data[totalHoldAssuredSedan]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Hold Assured Sedan'),
													array('name' => 'totalConfirmedAssuredSedan', 'filter' => false, 'value' => '$data[totalConfirmedAssuredSedan]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Confirmed Assured Sedan'),
													array('name' => 'totalHoldAssuredInnova', 'value' => '$data[totalHoldAssuredInnova]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Hold Assured Innova')
											)));
										}
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
<?php
		}
		elseif ($model->datafor == 'zone')
		{
?>	
				<div><b>Analysis By Zone</b></div>
				<div class="row">
					<div class="col-md-12">
						<div class="panel" >
							<div class="panel-body panel-no-padding p0 pt10">
								<div class="panel-scroll1">
									<div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
										<?php
										if (!empty($dataProvider2))
										{
											$arr = [];
											if (is_array($dataProvider2->getPagination()->params))
											{
												$arr = $dataProvider2->getPagination()->params;
											}
											$params1							 = $arr + array_filter($_GET + $_POST);
											$dataProvider2->setPagination(['params' => $params1]);
											/* @var $dataProvider CActiveDataProvider */
											$dataProvider2->pagination->pageSize	 = 30;
											$this->widget('booster.widgets.TbGridView', array(
												'id'				 => 'pricesurgelist2',
												'responsiveTable'	 => true,
												'dataProvider'		 => $dataProvider2,
												'filter'			 => $model,
												'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
												'itemsCssClass'		 => 'table table-striped table-bordered mb0',
												'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
												'ajaxType'			 => 'POST',
												'columns'			 => array(
													array('name' => 'rutName', 'filter' => false, 'value' => '$data[rutName]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Route Name'),
													array('name' => 'totalRequest', 'filter' => false, 'value' => '$data[totalRequest]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Request'),
													array('name' => 'totalHold', 'filter' => false, 'value' => '$data[totalHold]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Hold'),
													array('name' => 'totalConfirmed', 'filter' => false, 'value' => '$data[totalConfirmed]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Confirmed'),
													array('name' => 'totalHoldCompact', 'filter' => false, 'value' => '$data[totalHoldCompact]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Hold Compact'),
													array('name' => 'totalConfirmedCompact', 'filter' => false, 'value' => '$data[totalConfirmedCompact]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Confirmed Compact'),
													array('name' => 'totalHoldSUV', 'filter' => false, 'value' => '$data[totalHoldSUV]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Hold SUV'),
													array('name' => 'totalConfirmedSUV', 'filter' => false, 'value' => '$data[totalConfirmedSUV]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Confirmed SUV'),
													array('name' => 'totalHoldSedan', 'filter' => false, 'value' => '$data[totalHoldSedan]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Hold Sedan'),
													array('name' => 'totalConfirmedSedan', 'filter' => false, 'value' => '$data[totalConfirmedSedan]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Confirmed Sedan'),
													array('name' => 'totalHoldAssuredSedan', 'filter' => false, 'value' => '$data[totalHoldAssuredSedan]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Hold Assured Sedan'),
													array('name' => 'totalConfirmedAssuredSedan', 'filter' => false, 'value' => '$data[totalConfirmedAssuredSedan]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Confirmed Assured Sedan'),
													array('name' => 'totalHoldAssuredInnova', 'value' => '$data[totalHoldAssuredInnova]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Hold Assured Innova')
											)));
										}
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
<?php
		}
?>
			</div>
		</div>
	</div></div>
<SCRIPT language=Javascript>
	        var start = new Date('<?= $model->aat_pickup_date1 ?>');
        var end = new Date('<?= $model->aat_pickup_date2 ?>');
    function numberValidation()
    {
        var number = parseInt($('#AgentApiTracking_aat_hours').val());
        if (isNaN(number) || (number < 0 && number > 48)) {
            alert("Enter Valid number between 1 and 48")
            return false;
        }
        return true;
    }
	
	$('#bkgPickupDate').daterangepicker(
                {
                    locale: {
                        format: 'DD/MM/YYYY',
                        cancelLabel: 'Clear'
                    },
                    "showDropdowns": true,
                    "alwaysShowCalendars": true,
                    startDate: start,
                    endDate: end,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Tomorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
                        'Next 7 Days': [moment(), moment().add(6, 'days')],
                        'Next 15 Days': [moment(), moment().add(15, 'days')],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
                    }
                }, function (start1, end1) {
            $('#AgentApiTracking_aat_pickup_date1').val(start1.format('YYYY-MM-DD'));
            $('#AgentApiTracking_aat_pickup_date2').val(end1.format('YYYY-MM-DD'));
            $('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgPickupDate span').html('Select Pickup Date Range');
            $('#AgentApiTracking_aat_pickup_date1').val('');
            $('#AgentApiTracking_aat_pickup_date2').val('');
        });
</SCRIPT>
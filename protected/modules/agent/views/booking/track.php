
<style>
    .pagination{
        margin: 0
    }
    .actBtn img{
        height: 20px;
    }
</style>
<div class="">
	<?php
	$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'booking-form', 'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
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
	<div class="panel ">    
		<div class="panel-body panel-border panel-info ">      
			<div class="col-lg-10 col-lg-offset-1 ">
				<div class="row bordered pt10">

					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<?= $form->datePickerGroup($model, 'vtr_visit_date', array('label' => 'Visitor Date', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'yyyy-mm-dd'), 'htmlOptions' => array('placeholder' => 'Visitor Date')), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label  ">Referral Domains</label>
								<?php
								$filters     = VisitorTrack::getReferralDomain();
								$dataDomain	 = Filter::getJSON($filters);

								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'vtr_referal_url',
									'val'			 => $model->vtr_referal_url,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($dataDomain), 'allowClear' => false),
									'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'placeholder' => 'Select Referal Domain')
								));
								?>
							</div>
						</div>

						<div class="col-sm-8 ">
							<button class="btn btn-primary" type="submit" style="width: 140px;"  name="bookingSearch">Search</button>
						</div>
					</div> 
					<?php $this->endWidget(); ?>
				</div>
			</div>


		</div> 
	</div>
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-12 mt10">
				<?php
				if (!empty($dataProvider))
				{
					$params									 = array_filter($_REQUEST);
					$dataProvider->getPagination()->params	 = $params;
					$dataProvider->getSort()->params		 = $params;
					$this->widget('booster.widgets.TbGridView', array(
						'id'				 => 'booking-list2',
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataProvider,
						'template'			 => "<div class='panel-heading'><div class='row m0'>
														<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
												</div></div>
												<div class='panel-body'>{items}</div>
												<div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered mb0',
						'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
						'columns'			 => array(
							['name' => 'vtr_visit_date', 'value' => '$data["vtr_visit_date"]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-4', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Visitor Date'],
							['name' => 'vtr_referal_url', 'value' => '$data["vtr_referal_url"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-4', 'class' => 'text-left'), 'htmlOptions' => array('style' => 'text-align: left;'), 'header' => 'Visitor Url'],
							['name'	 => 'vtr_visit_data', 'value'	 => function($data) {
									$jsonData = json_decode($data["vtr_visit_data"], true);

									unset($jsonData['YII_CSRF_TOKEN']);
									unset($jsonData['rdata']);
									unset($jsonData['adtData']);
									unset($jsonData['splData']);
									unset($jsonData['XDEBUG_SESSION_START']);
									unset($jsonData['step']);
									unset($jsonData['rid']);
									unset($jsonData['ctr']);
									unset($jsonData['pageID']);
									unset($jsonData['hash']);
									unset($jsonData['apikey']);

									foreach ($jsonData as $key => $row)
									{
										if ($key == 'BookingTemp')
										{
											$row = json_encode($jsonData['BookingTemp']);
										}
										if ($key == 'BookingRoute')
										{
											$row = json_encode($jsonData['BookingRoute']);
										}
										if ($key == 'min_time')
										{
											$row = json_encode($jsonData['min_time']);
										}
										if ($key == 'tierCheckbox')
										{
											$row = json_encode($jsonData['tierCheckbox']);
										}
										echo '<li>' . $key . ':' . '&nbsp;' . $row . '</li>';
									}
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-4', 'class' => 'text-left'), 'htmlOptions'		 => array('style' => 'text-align: left;'), 'header'			 => 'Visit Data'],
					)));
				}
				?>
			</div>
		</div>
	</div>

</div>

<script>
//    $(document).ready(function () {
//        $("#VisitorTrack_vtr_referal_url").val(0).trigger('change');
//    });
</script>

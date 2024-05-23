<div class="col-xs-12">
	<?php
	$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'vendorsearchlist', 'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error'
		),
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			'class' => '',
		),
	));
	?>
	<div class="row">
		<div class="col-xs-12 col-sm-4 col-md-3"> 
			<div class="form-group">
                <label class="control-label">From</label>
				<?php
				$datacity	 = Cities::model()->getCityByBooking1();
				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $model,
					'attribute'		 => 'from_city',
					'val'			 => $model->from_city,
					'asDropDownList' => FALSE,
					'options'		 => array('data' => new CJavaScriptExpression($datacity), 'allowClear' => true),
					'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'From')
				));
				?>
            </div> </div>
		<div class="col-xs-12 col-sm-4 col-md-3"> 
			<div class="form-group">
                <label class="control-label">To</label>
				<?php
				$datacity	 = Cities::model()->getCityByBooking1();
				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $model,
					'attribute'		 => 'to_city',
					'val'			 => $model->to_city,
					'asDropDownList' => FALSE,
					'options'		 => array('data' => new CJavaScriptExpression($datacity), 'allowClear' => true),
					'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'To')
				));
				?>
            </div> </div>
		<div class="col-xs-12 col-sm-4 col-md-3">
			<?=
			$form->datePickerGroup($model, 'pickup_date', array('label'			 => 'Pickup Date',
				'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Pickup Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
			?>  
		</div>
		<div class="col-xs-12 col-sm-4 col-md-3"> 
			<div class="form-group">
				<label class="control-label">Booking Type</label>
				<?php
				$btype		 = Booking::model()->booking_type;
				$datainfo	 = VehicleTypes::model()->getJSON($btype);
				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $model,
					'attribute'		 => 'booking_type',
					'val'			 => $model->booking_type,
					'asDropDownList' => FALSE,
					'options'		 => array('data' => new CJavaScriptExpression($datainfo), 'allowClear' => true),
					'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Booking Type')
				));
				?>
			</div> </div>
		<div class="col-xs-12 col-sm-12 text-center pb15">
			<button class="btn btn-primary" type="submit" style="width: 185px;"  name="vendorSearch">Search</button>
		</div>
	</div>
	<?php $this->endWidget(); ?>
</div>
<div class="col-xs-12">
	<?php
	if (!empty($dataProvider))
	{
		$params									 = array_filter($_REQUEST);
		$dataProvider->getPagination()->params	 = $params;
		$dataProvider->getSort()->params		 = $params;
		$this->widget('booster.widgets.TbGridView', array(
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
				array('name' => 'vnd_name', 'value' => '$data["vnd_name"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Vendor Name'),
				array('name' => 'vnd_phone', 'value' => '$data["vnd_phone"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Vendor Phone'),
		)));
	}
	?>
</div>
<script>
    $(document).ready(function () {
        var front_end_height = parseInt($(window).outerHeight(true));
        var footer_height = parseInt($("#footer").outerHeight(true));
        var header_height = parseInt($("#header").outerHeight(true));
        var ch = (front_end_height - (header_height + footer_height + 23));
        $("#content").attr("style", "height:" + ch + "px;");
    });
</script>
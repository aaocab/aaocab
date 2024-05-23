<div class="panel panel-default">
    <div class="panel-body">
		<?php
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'booking-form', 'enableClientValidation' => true,
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
		/* @var $form TbActiveForm */
		?>
        <div class="col-xs-6">
			<?= $form->datePickerGroup($model, 'blg_created1', array('label' => 'Lead Start', 'widgetOptions' => array('options' => array('autoclose' => true, 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Created Date', 'value' => ($model->blg_created1 == '') ? DateTimeFormat::DateToDatePicker(date('Y-m-d')) : DateTimeFormat::DateToDatePicker($model->blg_created1))), 'prepend' => '<i class="fa fa-calendar"></i>'));
			?>
        </div>
        <div class="col-xs-6">
			<?= $form->datePickerGroup($model, 'blg_created2', array('label' => 'Lead End', 'widgetOptions' => array('options' => array('autoclose' => true, 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Created Date', 'value' => ($model->blg_created2 == '') ? DateTimeFormat::DateToDatePicker(date('Y-m-d')) : DateTimeFormat::DateToDatePicker($model->blg_created2))), 'prepend' => '<i class="fa fa-calendar"></i>'));
			?>
        </div>
        <div class="col-xs-1">
			<?php echo CHtml::submitButton('Search', array('class' => 'btn btn-primary btn-5x pr30 pl30')); ?>
        </div>
		<?php $this->endWidget(); ?>
		<div class="col-xs-1">
			<?php
			$checkExportAccess	 = false;
			if ($roles['rpt_export_roles'] != null)
			{
				$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
			}
			if ($checkExportAccess)
			{
				echo CHtml::beginForm(Yii::app()->createUrl('report/financial/DailyAssignedReport'), "post", []);
				?>
				<input type="hidden" id="blg_created1" name="blg_created1" value="<?= $model->blg_created1 ?>"/>
				<input type="hidden" id="blg_created2" name="blg_created2" value="<?= $model->blg_created2 ?>"/>
				<input type="hidden" id="export" name="export" value="true"/>
				<button class="btn btn-default btn-5x pr30 pl30 mt28" type="submit" style="width: 185px;">Export</button>
				<?php echo CHtml::endForm(); ?>	
			<?php } ?>
		</div>

	</div>
</div>

<?php
if ($dataProvider != "")
{
	$this->widget('booster.widgets.TbGridView', [
		'id'				 => 'credits-grid',
		'dataProvider'		 => $dataProvider,
		'responsiveTable'	 => true,
		'filter'			 => $model,
		'ajaxUrl'			 => Yii::app()->createUrl('admpnl/report/dailyassignedreport', ['blg_created1' => $model->blg_created1, 'blg_created2' => $model->blg_created2]),
		'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
		'itemsCssClass'		 => 'table table-striped table-bordered mb0',
		'template'			 => "<div class='panel-heading'><div class='row m0'>
            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
            </div></div>
            <div class='panel-body'>{items}</div>
            <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
		'columns'			 => [
			['name' => 'adm_fname', 'value' => '$data[adm_fname]', 'headerHtmlOptions' => ['class' => 'col-xs-2'], 'header' => 'Fname'],
			['name' => 'adm_lname', 'value' => '$data[adm_lname]', 'headerHtmlOptions' => ['class' => 'col-xs-2'], 'header' => 'Lname'],
			['name' => 'total_assigned', 'filter' => false, 'value' => '$data[total_assigned]', 'headerHtmlOptions' => ['class' => 'col-xs-2'], 'header' => 'Vendor Assigned'],
			['name'	 => 'vendor_name', 'filter' => false, 'value'	 => function ($data) {
					$vals		 = array_count_values(explode(',', $data['vendor_name']));
					$strvendors	 = "";
					foreach ($vals as $key => $value)
					{
						$strvendors = $strvendors . " " . $key . "(" . $value . " times)";
					}
					return $strvendors;
				}, 'headerHtmlOptions'	 => ['class' => 'col-xs-2'], 'header'			 => 'Vendors'],
		]
	]);
}
?>







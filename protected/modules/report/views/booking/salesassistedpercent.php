<div class='row p15'>
	<div class="col-xs-1">
		<?php
		$checkExportAccess	 = false;
		if ($roles['rpt_export_roles'] != null)
		{
			$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
		}
		if ($checkExportAccess)
		{
			echo CHtml::beginForm(Yii::app()->createUrl('report/booking/salesAssistedPercentByTier'), "post", ['style' => "margin-bottom: 10px;margin-top: 10px;"]);
			?>
			<input type="hidden" id="export" name="export" value="true"/>
			<button class="btn btn-default btn-5x pr30 pl30 mt10" type="submit" style="">Export</button>
			<?php
			echo CHtml::endForm();
		}
		?>
	</div>
</div>

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
								<div class='panel-body table-responsive'>{items}</div>
								<div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
		'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
		'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
		'columns'			 =>
		array
			(
			array('name' => 'bkg_create_date', 'value' => $data['bkg_create_date'], 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Create Date'),
			array('name' => 'Service Class__scc_label', 'value' => $data['Service Class__scc_label'], 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Service Class'),
			array('name' => 'count', 'value' => $data['count'], 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Count'),
		)
	));
}
?>


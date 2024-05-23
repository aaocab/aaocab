
<div class="row">
    <div class="panel panel-white">
		<?php
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'smartMatchForm', 'enableClientValidation' => true,
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
        <div class="panel-body">
            <div class="row col-xs-12">
				<div class="well pb20">
					<? $cls				 = "col-xs-6 col-sm-4 col-md-3 col-lg-2"; ?>
					<div class="row">
						<div class="<?= $cls ?>"> 
							<?= $form->textFieldGroup($model, 'trip_id', array('widgetOptions' => ['htmlOptions' => []])) ?>
						</div>   

					</div>
					<div class="row">
						<div class="<?= $cls ?>">
							<?php echo $form->checkBox($model, 'bkg_smart_broken', array('value' => 1, 'uncheckValue' => 0, 'style' => 'margin-top:7px;')); ?>Show Broken
						</div>
						<div class="<?= $cls ?>">
							<?php echo $form->checkBox($model, 'bkg_smart_successful', array('value' => 1, 'uncheckValue' => 0, 'style' => 'margin-top:7px;')); ?>Show Successful
						</div> 
					</div>
					<div class="row">
						<div class="<?= $cls ?> text-center mt20 pt5">
							<button class="btn btn-primary" type="submit" style="width: 185px;"  name="matchSearch">Search</button>
						</div>
					</div>
				</div>
            </div>
        </div>
		<?php $this->endWidget(); ?>
    </div>    
</div>   
<?php
$checkExportAccess	 = false;
if ($roles['rpt_export_roles'] != null)
{
	$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
}
if ($checkExportAccess)
{
	?>
	<?= CHtml::beginForm(Yii::app()->createUrl('report/booking/matchlist'), "post", ['style' => "margin-bottom: 10px;"]); ?>
	<input type="hidden" id="export1" name="export1" value="true"/>
	<input type="hidden" id="export_smart_broken" name="export_smart_broken" value="<?= $model->bkg_smart_broken ?>"/>
	<input type="hidden" id="export_smart_successful" name="export_smart_successful" value="<?= $model->bkg_smart_successful ?>"/>
	<button class="btn btn-default" type="submit" style="width: 185px; ">Export Below Table</button>
	<?= CHtml::endForm() ?>
	<?php
}
?>
<div class="row">
    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{

			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'>
                                        <div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
                                            <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                        </div>
                                    </div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'>
                                        <div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 p5'>{summary}</div>
                                            <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                        </div>
                                    </div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
					array('name'				 => 'trip_id', 'value'				 => $data['trip_id'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Trip Id'),
					array('name'				 => 'booking_ids', 'value'				 => $data['booking_ids'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
						'htmlOptions'		 => array('class' => 'text-left'),
						'header'			 => 'Booking Id(s)'),
					array('name'				 => 'from_city_ids', 'value'				 => $data['from_city_ids'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
						'htmlOptions'		 => array('class' => 'text-left'),
						'header'			 => 'From City(s)'),
					array('name'				 => 'to_city_ids', 'value'				 => $data['to_city_ids'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
						'htmlOptions'		 => array('class' => 'text-left'),
						'header'			 => 'To City(s)'),
					array('name'				 => 'trip_amount', 'value'				 => $data['trip_amount'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-right'),
						'header'			 => 'Trip Amount'),
					array('name'				 => 'vendor_amount_original', 'value'				 => $data['vendor_amount_original'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-right'),
						'header'			 => 'Vendor Amount Original'),
					array('name'				 => 'vendor_amount_smart_match', 'value'				 => $data['vendor_amount_smart_match'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-right'),
						'header'			 => 'Vendor Amount Matched'),
					array('name'				 => 'service_tax_amount', 'value'				 => $data['service_tax_amount'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-right'),
						'header'			 => 'Service Tax'),
					array('name'				 => 'gozo_amount_original', 'value'				 => $data['gozo_amount_original'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-right'),
						'header'			 => 'Gozo Amount'),
					array('name'				 => 'gozo_amount_smart_match', 'value'				 => $data['gozo_amount_smart_match'], 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-right'),
						'header'			 => 'Gozo Amount Matched'),
					array('name'	 => 'margin_original', 'value'	 => function ($data) {
							echo ($data['margin_original'] * 100) . '%';
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-right'),
						'header'			 => 'Margin Original'),
					array('name'	 => 'margin_smart_match', 'value'	 => function ($data) {
							echo ($data['margin_smart_match'] * 100) . '%';
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-right'),
						'header'			 => 'Margin Matched'),
					array('name'	 => 'match_date', 'value'	 => function ($data) {
							echo ($data['match_date']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-right'),
						'header'			 => 'Date'),
					array('name'	 => 'matchtype', 'value'	 => function ($data) {
							echo ($data['matchtype']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-right'),
						'header'			 => 'Match Type'),
					array('name'	 => 'name', 'value'	 => function ($data) {
							echo ($data['name']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-right'),
						'header'			 => 'Matched By'),
			)));
		}
		?>
    </div>
</div>


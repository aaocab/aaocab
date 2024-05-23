<?php
$datazone			 = Zones::model()->getZoneArrByFromBooking();
?>
<div class="row">
    <div class="col-xs-12">
		<?php
		/* @var $model Vendors */
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'regionPerfForm', 'enableClientValidation' => true,
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

        <div class="col-xs-12 col-sm-4 col-md-3"> 
			<?=
			$form->datePickerGroup($model, 'bkg_pickup_date', array('label'			 => 'Date',
				'widgetOptions'	 => array('options'		 => array(
						'autoclose'	 => true, 'startDate'	 => date(), 'format'	 => 'dd/mm/yyyy'),
					'htmlOptions'	 => array('placeholder' => 'Date')),
				'prepend'		 => '<i class="fa fa-calendar"></i>'));
			?>
        </div>
        <div class="col-xs-6  col-sm-4 col-md-3 col-lg-2">
			<div class="form-group">
				<label class="control-label">Region </label>
				<?php
				$regionList			 = VehicleTypes::model()->getJSON(Vendors::model()->getRegionList());
				$nbkg_region		 = explode(",", $model->bkg_region);
				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $model,
					'attribute'		 => 'bkg_region',
					//'val' => $model->bkg_region,
					'val'			 => $nbkg_region,
					//'asDropDownList' => FALSE,
					'data'			 => Vendors::model()->getRegionList(),
					//'options' => array('data' => new CJavaScriptExpression($regionList), 'allowClear' => true),
					'htmlOptions'	 => array('class'			 => 'p0', 'multiple'		 => 'multiple',
						'style'			 => 'width: 100%', 'placeholder'	 => 'Select Region')
				));
				?>
			</div></div>
		<div class="col-xs-6 col-sm-4  col-md-3 col-lg-2">
			<div class="form-group">
				<label>Source Zone</label>
				<?php
				$nsource			 = explode(",", $model->sourcezone);
				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $model,
					'attribute'		 => 'sourcezone',
					//'val' => $model->sourcezone,
					'val'			 => $nsource,
					'data'			 => $datazone,
					//'asDropDownList' => FALSE,
					//'options' => array('data' => new CJavaScriptExpression($datazone), 'allowClear' => true,),
					'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
						'placeholder'	 => 'Source Zone')
				));
				?>
			</div></div>
		<div class="col-xs-6 col-sm-4  col-md-3 col-lg-2">
			<div class="form-group">
				<label class="control-label">Destination Zone</label>
				<?php
				$ndestination		 = explode(",", $model->destinationzone);
				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $model,
					'attribute'		 => 'destinationzone',
					//'val' => $model->destinationzone,
					'val'			 => $ndestination,
					'data'			 => $datazone,
					//  'asDropDownList' => FALSE,
					// 'options' => array('data' => new CJavaScriptExpression($datazone), 'allowClear' => true,),
					'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
						'placeholder'	 => 'Destination Zone')
				));
				?>
			</div></div>


		<div class="col-xs-6 col-sm-4  col-md-3 col-lg-2">
			<div class="form-group">
				<label class="control-label">Vehicle Type</label>
				<?php
				$returnType			 = "list";
				$vehcleList			 = SvcClassVhcCat::getVctSvcList($returnType);
				$nvht_type			 = explode(",", $model->bkg_vehicle_type_id);
				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $model,
					'attribute'		 => 'bkg_vehicle_type_id',
					//'val' => $model->bkg_vehicle_type_id,
					'val'			 => $nvht_type,
					'data'			 => $vehcleList,
					'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
						'placeholder'	 => 'Select Vehicle Type')
				));
				?>
			</div></div>

        <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20" style="padding: 4px;">
            <button class="btn btn-primary" type="submit" style="width: 185px;"  name="Search">Search</button> 
        </div>
		<?php $this->endWidget(); ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
		<?php
		$checkExportAccess	 = false;
		if ($roles['rpt_export_roles'] != null)
		{
			$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
		}
		if ($checkExportAccess)
		{
			?>
			<?= CHtml::beginForm(Yii::app()->createUrl('report/route/demandreport'), "post", ['style' => "margin-bottom: 10px; margin-top: 10px; margin-left: 20px;"]); ?>
			<input type="hidden" id="export" name="export" value="true"/>
			<input type="hidden" id="export_from" name="export_from" value="<?= $model->bkg_pickup_date ?>"/>

			<input type="hidden" id="bkg_region2" name="bkg_region2" value="<?= $model->bkg_region ?>"/>
			<input type="hidden" id="sourcezone2" name="sourcezone2" value="<?= $model->sourcezone ?>"/>
			<input type="hidden" id="destinationzone2" name="destinationzone2" value="<?= $model->destinationzone ?>"/>
			<input type="hidden" id="bkg_vehicle_type_id2" name="bkg_vehicle_type_id2" value="<?= $model->bkg_vehicle_type_id ?>"/>

			<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>
			<?= CHtml::endForm() ?>
			<?php
		}
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
					array('name'				 => 'bkg_pickup_date', 'value'				 => 'date("d-m-Y H:i:s",strtotime($data[bkg_pickup_date]))',
						'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'header'			 => 'Date'),
					array('name' => 'from_zone', 'value' => $data['from_zone'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'From Zone'),
					array('name' => 'to_zone', 'value' => $data['to_zone'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'To Zone'),
					array('name' => 'bkg_vehicle_type_id', 'value' => $data['bkg_vehicle_type_id'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Vehicle Type'),
					array('name' => 'up_count', 'value' => $data['up_count'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'UP COUNT'),
					array('name' => 'down_count', 'value' => $data['down_count'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'DOWN COUNT'),
					array('name' => 'up_confirmed', 'value' => $data['up_confirmed'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'UP Confirmed'),
					array('name' => 'down_confirmed', 'value' => $data['down_confirmed'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'DOWN Confirmed'),
			)));
		}
		?>
    </div>
</div>
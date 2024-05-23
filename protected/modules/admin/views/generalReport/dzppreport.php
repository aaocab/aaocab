<?php
$statusList		 = Booking::model()->getBookingStatus();
$datazone		 = Zones::model()->getZoneArrByFromBooking();
?>
<style>
    .checkbox{
        display:inline;
    }
</style>
<div class="panel panel-default">
    <div class="panel-body  " >
        <div class="row"> 
			<?php
			$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'gnow-form', 'enableClientValidation' => true,
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




            <div class="col-xs-12 col-sm-4 col-md-2" >
                <div class="form-group">
                    <label class="control-label">Region </label>
					<?php
					$regionList		 = VehicleTypes::model()->getJSON(Vendors::model()->getRegionList());
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'dzs_regionid',
						'val'			 => $model->dzs_regionid,
						//'asDropDownList' => FALSE,
						'data'			 => Vendors::model()->getRegionList(),
						//'options' => array('data' => new CJavaScriptExpression($regionList), 'allowClear' => true),
						'htmlOptions'	 => array('class'			 => 'p0', 'multiple'		 => 'multiple',
							'style'			 => 'width: 100%', 'placeholder'	 => 'Select Region')
					));
					?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-2 col-md-2">
                <div class="form-group">
                    <label>Source Zone</label>
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'dzs_fromzoneid',
						'val'			 => $model->dzs_fromzoneid,
						'data'			 => $datazone,
						'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
							'placeholder'	 => 'Source Zone')
					));
					?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-2 col-md-2">
                <div class="form-group">
                    <label>Destination Zone</label>
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'dzs_tozoneid',
						'val'			 => $model->dzs_tozoneid,
						'data'			 => $datazone,
						'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
							'placeholder'	 => 'Destination Zone')
					));
					?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-2 col-md-2">
                <div class="form-group">
                    <label class="control-label">Cab Type</label>
					<?php
					$returnType		 = "list";
					$vehicleList	 = SvcClassVhcCat::getVctSvcList($returnType);
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'dzs_scv_id',
						'val'			 => $model->dzs_scv_id,
						'data'			 => $vehicleList,
						'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
							'placeholder'	 => 'Select Cab Type')
					));
					?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-2 col-md-2">
                <div class="form-group">
                    <label class="control-label">Booking Type</label>

					<?php
					$bookingTypesArr = Booking::model()->booking_type;
					unset($bookingTypesArr[2]);
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'dzs_booking_type',
						'val'			 => $model->dzs_booking_type,
						'data'			 => $bookingTypesArr,
						'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
							'placeholder'	 => 'Booking Type')
					));
					?>
                </div>
            </div>
			<div class="col-xs-12 col-sm-2 col-md-2">
                <div class="form-group">
                    <label class="control-label">Zone Type</label>
					<?php
					$ZoneTypesArr	 = DynamicZoneSurge::model()->zone_type;
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'dzs_zone_type',
						'val'			 => $model->dzs_zone_type,
						'data'			 => $ZoneTypesArr,
						'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
							'placeholder'	 => 'Zone Type')
					));
					?>
                </div>
            </div>

			<div class="col-xs-12 col-sm-2 col-md-2" >
				<div class="form-group">
					<label class="control-label">State </label>
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'dzs_state',
						'val'			 => $model->dzs_state,
						'data'			 => States::model()->getStateList1(),
						'htmlOptions'	 => array('class'			 => 'p0', 'multiple'		 => 'multiple',
							'style'			 => 'width: 100%', 'placeholder'	 => 'Select State')
					));
					?>
				</div></div>


            <div class=" col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 col-lg-1 text-center mt20 ">   
				<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width mt5')); ?></div>
				<?php $this->endWidget(); ?>
        </div>
    </div></div>
<div class="panel panel-default">
    <div class="panel-body p0" >
		<?php
		$params									 = array_filter($_REQUEST);
		$dataProvider->getPagination()->pageSize = 1000;
		$dataProvider->getPagination()->params	 = $params;
		$dataProvider->getSort()->params		 = $params;
		$this->widget('booster.widgets.TbGridView', array(
			'id'				 => 'requestVendorGrid',
			'responsiveTable'	 => true,
			'dataProvider'		 => $dataProvider,
			'template'			 => "<div class='panel-heading'><div class='row '>
							<div class='col-xs-12 col-sm-5'>{summary}</div>
							<div class='col-xs-12 col-sm-7 pr0'>{pager}</div>
							</div></div>
							<div class='panel-body'>{items}</div>
							<div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-5 p5'>{summary}</div><div class='col-xs-12 col-sm-7 pr0'>{pager}</div></div></div>",
			'itemsCssClass'		 => 'table table-striped table-bordered mb0',
			'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
			'columns'			 => array(
				array('name'	 => 'RowIdentifier', 'value'	 => function ($data) {
						if ($data['RowIdentifier'] != null)
						{
							echo CHtml::link($data['RowIdentifier'], Yii::app()->createUrl("admpnl/generalReport/DzppDetailReport", ["id" => $data['RowIdentifier']]), ['target' => '_blank']);
						}
					}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Row Identifier'),
				array('name' => 'Region', 'value' => '$data["Region"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Region'),
				array('name' => 'FromZoneName', 'value' => '$data["FromZoneName"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Source Zone'),
				array('name' => 'ToZoneName', 'value' => '$data["ToZoneName"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Destination Zone'),
				array('name' => 'CountBooking', 'value' => '$data["CountBooking"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Booking Count'),
				array('name' => 'ZoneType', 'value' => '$data["ZoneType"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Zone Type'),
				array('name' => 'Profit', 'value' => '$data["Profit"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Profit'),
				array('name' => 'scv_label', 'value' => '$data["scv_label"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Service Type'),
				array('name' => 'DZPP', 'value' => '$data["DZPP"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'DZPP'),
				array('name' => 'rateUpdateDays', 'value' => '$data["rateUpdateDays"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Rate Update(Days)'),
				array('name' => 'finalDZPP', 'value' => '$data["finalDZPP"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Final DZPP'),
				array('name' => 'dzs_cntInquiry', 'value' => '$data["dzs_cntInquiry"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Inquiry Count'),
				array('name' => 'dzs_cntCreated', 'value' => '$data["dzs_cntCreated"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Created Count'),
				array('name' => 'dzs_conversionPer', 'value' => '$data["dzs_conversionPer"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Conversion(%)'),
				array('name' => 'dzs_completionPer', 'value' => '$data["dzs_completionPer"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Completion(%)'),
				array('name' => 'dzs_va', 'value' => '$data["dzs_va"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Releaized VA'),
				array('name' => 'dzs_ca', 'value' => '$data["dzs_ca"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Releaized CA'),
				array('name' => 'dzs_suggested_va', 'value' => '$data["dzs_suggested_va"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Suggested VA'),
				array('name' => 'dzs_suggested_ca', 'value' => '$data["dzs_suggested_ca"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Suggested CA'),
		)));
		?>
    </div>
</div>
<script type="text/javascript">
</script>
<div class="row" >
    <div class="col-xs-12">
		<div class="row">
			<?php
			/* @var $model Vendors */
			$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'bookingFrm', 'enableClientValidation' => true,
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
			<div class="col-xs-3 col-sm-4 col-md-4"> 
					<label class="control-label">Zone</label><br>
					<?php
					$dataZone			 = Filter::getJSON(Zones::model()->getZoneArrByFromBooking());
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'sourcezone',
						'val'			 => $model->sourcezone,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($dataZone), 'allowClear' => true),
						'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%', 'placeholder' => 'Select Requested', 'id' => 'requestedBy')
					));
					?>	
			</div>

			<div class="col-xs-3 col-sm-4 col-md-4" >
				<label class="control-label">Cab Type</label>
				<?php
				$returnType			 = "listCategory";
				$vehicleList		 = SvcClassVhcCat::getVctSvcList($returnType);
				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $model,
					'attribute'		 => 'bkg_vehicle_type_id',
					'val'			 => $model->bkg_vehicle_type_id,
					'data'			 => $vehicleList,
					'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
						'placeholder'	 => 'Select Car Type')
				));
				?>
			</div> 

			<div class="col-xs-3 col-sm-6 col-md-2 col-lg-1 mr15 mt20"  >
				<button class="btn btn-primary" type="submit" style="width: 125px;" >Search</button> 
			</div>
			<?php $this->endWidget(); ?>
			<div class="col-xs-3 col-sm-4 col-md-4 form-group ">
				<?php
				$checkExportAccess	 = false;
				if ($roles['rpt_export_roles'] != null)
				{
					$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
				}
				if ($checkExportAccess)
				{
					echo CHtml::beginForm(Yii::app()->createUrl('report/vendor/profileReport'), "post", []);
					?>
					<input type="hidden" id="sourcezone" name="sourcezone" value="<?= $model->sourcezone; ?>"/>
					<input type="hidden" id="bkg_vehicle_type_id" name="bkg_vehicle_type_id" value="<?= $vehicleTypeId; ?>"/>
					<input type="hidden" id="export" name="export" value="true"/>
					<button class="btn btn-default btn-5x pr30 pl30 mt20" type="submit" style="width: 185px;">Export</button>
				<?php echo CHtml::endForm();  
				} ?>
			</div>
		</div>

		

        <div class="row">
            <div class="col-xs-12">
                <div class="  table table-bordered">
					<?php
					if (!empty($dataProvider))
					{
						$params									 = array_filter($_REQUEST);
						$dataProvider->getPagination()->params	 = $params;
						$dataProvider->getSort()->params		 = $params;
						$this->widget('booster.widgets.TbGridView', array(
							'id'				 => 'vendorListGrid',
							'responsiveTable'	 => true,
							'dataProvider'		 => $dataProvider,
							'template'			 => "<div class='panel-heading'><div class='row m0'>
							<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
							</div></div>
							<div class='panel-body table-responsive'>{items}</div>
							<div class='panel-footer'>
							<div class='row'><div class='col-xs-12 col-sm-6 p5'>{summary}</div>
							<div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
							'itemsCssClass'		 => 'table  table-bordered dataTable mb0',
							'htmlOptions'		 => array('class' => 'panel panel-primary compact'),
							'columns'			 => array(
								array('name'	 => 'vnd_name', 'value'	 => function ($data) 
								{
										echo CHtml::link($data["vnd_name"], Yii::app()->createUrl("admin/vendor/view", ["id" => $data['vnd_id']]), ["class" => "", "target" => "_blank"]);
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Operator Name'),
								array('name' => 'scv_label', 'value' => '$data[scv_label]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-left'), 'header' => 'Service Class'),
								array('name' => 'homeZone', 'value' => '$data[homeZone]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Home Zone'),
								array('name' => 'vendor_status', 'value' => '$data[vendor_status]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Vendor Status'),
								array('name' => 'vrs_total_trips', 'value' => '$data[vrs_total_trips]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Total Trip'),
								array('name' => 'vrs_vnd_overall_rating', 'value' => '$data[vrs_vnd_overall_rating]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Rating'),
								array('name' => 'Total_Unassign_Count', 'value' => '$data[Total_Unassign_Count]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Total Unassign Count'),
								array('name' => 'vrs_denied_duty_cnt', 'value' => '$data[vrs_denied_duty_cnt]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Denied Duty Cnt'),
								array('name' => 'vrs_docs_score', 'value' => '$data[vrs_docs_score]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Doc Socre'),
								array('name' => 'vrs_approve_driver_count', 'value' => '$data[vrs_approve_driver_count]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Approve Car Cnt'),
								array('name' => 'vrs_approve_car_count', 'value' => '$data[vrs_approve_car_count]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Approve Car Cnt'),
								array('name' => 'vrs_last_bkg_cmpleted', 'value' => '$data[vrs_last_bkg_cmpleted]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Last Booking Completed'),
						)));
					}
					?> 
                </div>
            </div>  
        </div> 
    </div>  
</div>
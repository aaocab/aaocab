<div class="row m0">
    <div class="col-xs-12">
        <div class="text-right">
        </div>    
        <div class="panel panel-default">
            <div class="panel-body">

				<?php
				$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'otpreport-form', 'enableClientValidation' => true,
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
				// @var $form TbActiveForm 
				?>
				<div class="row"> 
                    <div class="col-xs-12 col-sm-3">
                        <div class="form-group">
                            <label class="control-label">Booking ID/Booking Ref ID</label>
							<?= $form->textFieldGroup($model, 'bkg_booking_id', ['label' => '']); ?>
                        </div> 
                    </div>
					<div class="col-xs-12 col-sm-3">
						<?
						$statusList			 = [0 => 'All'] + Booking::model()->getBookingStatus();
						$allowed			 = [0, 5, 6, 7, 9, 10];
						$filtered_statusList = array_filter(
								$statusList,
								function ($key) use ($allowed) {
									return in_array($key, $allowed);
								},
								ARRAY_FILTER_USE_KEY
						);
						?>
						<div class="form-group">
                            <label class="control-label">Booking Status</label>
							<?=
							$form->select2Group($model, 'bkg_status', array('label'			 => '',
								'widgetOptions'	 => array('data' => $filtered_statusList, 'options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Select Booking Status', 'class' => 'p0', 'style' => 'max-width: 100%'))));
							?>  
						</div>
					</div>
                    <div class="col-xs-12 col-sm-3" style="">
                        <div class="form-group">
                            <label class="control-label">Pickup Date</label>
							<?php
							$daterang			 = "Select Pickup Date Range";
							$bkg_pickup_date1	 = ($model->bkg_pickup_date1 == '') ? date('Y-m-d H:i:s', strtotime("-1 days")) : $model->bkg_pickup_date1;
							$bkg_pickup_date2	 = ($model->bkg_pickup_date2 == '') ? date('Y-m-d H:i:s', strtotime("-1 days")) : $model->bkg_pickup_date2;
							if ($bkg_pickup_date1 != '' && $bkg_pickup_date2 != '')
							{
								$daterang = date('F d, Y', strtotime($bkg_pickup_date1)) . " - " . date('F d, Y', strtotime($bkg_pickup_date2));
							}
							?>
                            <div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>
							<?= $form->hiddenField($model, 'bkg_pickup_date1'); ?>
							<?= $form->hiddenField($model, 'bkg_pickup_date2'); ?>

                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3">
                        <div class="form-group">
                            <label class="control-label">Channel Partner</label>
							<?php
							$dataagents = Agents::model()->getAgentsFromBooking();
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkg_agent_id',
								'val'			 => $model->bkg_agent_id,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataagents), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Partner name')
							));
							?>
                        </div> 
                    </div>

				</div>
				<!--                   
				 <div class="col-xs-12 col-sm-3  col-md-4  mt20 pt5">
						<input type="checkbox"  name="Booking[late_started]" value="1" <?php //if($checked['late_started']) { echo 'checked';}                        ?>> Trip Started by 30 minutes late or more
				</div>-->

				<div class="col-xs-12 col-sm-3">
					<div class="form-group">
                        <input type="checkbox"  name="Booking[late_arrival]" value="1" <?php
						if ($checked['late_arrival'])
						{
							echo 'checked';
						}
						?>> Driver Arrival Time by 30 minutes late or more
					</div>
				</div>
				<div class="col-xs-12 col-sm-3">
					<input type="checkbox"  name="Booking[start_location_mismatch]" value="1" <?php
					if ($checked['start_location_mismatch'])
					{
						echo 'checked';
					}
					?>> Trip Start Location Mismatch
				</div>
				<div class="col-xs-12 col-sm-3">
					<input type="checkbox"  name="Booking[end_location_mismatch]" value="1" <?php
					if ($checked['end_location_mismatch'])
					{
						echo 'checked';
					}
					?>> Trip End Location Mismatch
				</div>

				<div class="col-xs-12 col-sm-3 ">   
					<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?></div>

			</div><Br>
			<?php
			$this->endWidget();
			$checkExportAccess = false;
			if ($roles['rpt_export_roles'] != null)
			{
				$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
			}
			if ($checkExportAccess)
			{
				?>
				<?= CHtml::beginForm(Yii::app()->createUrl('report/admin/DemoLink'), "post", ['style' => "margin-bottom: 10px;"]); ?>
				<input type="hidden" id="export1" name="export1" value="true"/>
				<input type="hidden" id="export_from1" name="export_from1" value="<?= $model->bkg_pickup_date1 ?>"/>
				<input type="hidden" id="export_to1" name="export_to1" value="<?= $model->bkg_pickup_date2 ?>"/>

				<input type="hidden" id="bkg_booking_id" name="bkg_booking_id" value="<?= $model->bkg_booking_id ?>"/>
				<input type="hidden" id="bkg_status" name="bkg_status" value="<?= $model->bkg_status ?>"/>
				<input type="hidden" id="bkg_agent_id" name="bkg_agent_id" value="<?= $model->bkg_agent_id ?>"/>

				<input type="hidden" id="late_arrival" name="late_arrival" value="<?= $checked['late_arrival'] ?>"/>
				<input type="hidden" id="start_location_mismatch" name="start_location_mismatch" value="<?= $checked['start_location_mismatch'] ?>"/>
				<input type="hidden" id="end_location_mismatch" name="end_location_mismatch" value="<?= $checked['end_location_mismatch'] ?>"/>
				<input type="hidden" id="bkg_booking_type" name="bkg_booking_type" value=""/>
				<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>

				<?php
				echo CHtml::endForm();
			}


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
                                                    <div class='panel-body table-responsive table-bordered'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
					'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
					'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
					//    'ajaxType' => 'POST',
					'columns'			 => array(
						array('name'	 => 'bkg_agent_id', 'value'	 => function ($data) {
								return Agents::model()->findByPk($data['bkg_agent_id'])->agt_company;
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Partner Name'),
						array('name'	 => 'bkg_booking_id', 'value'	 => function ($data) {
								echo CHtml::link($data['bkg_booking_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Booking ID'),
						array('name' => 'bkg_agent_ref_code', 'value' => '$data[bkg_agent_ref_code]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Ref Booking ID'),
						array('name'	 => 'bkg_status', 'value'	 => function ($data) {
								echo Booking::model()->getActiveBookingStatus($data['bkg_status']);
								$arr = BookingTrack::model()->trackLogList();
								echo ($data['btk_last_event'] != NULL) ? '<br>(' . $arr[$data['btk_last_event']] . ')' : '';
							}, 'sortable'								 => true, 'headerHtmlOptions'						 => array('class' => 'col-xs-1'), 'header'								 => 'Status'),
						array('name'	 => 'bkg_route_city_names', 'value'	 => function ($data) {
								if ($data['bkg_route_city_names'] != "")
								{
									$data['bkg_route_city_names'] = implode(" - ", json_decode($data['bkg_route_city_names']));
								}
								return $data['bkg_route_city_names']; //implode(" - ",$data['bkg_route_city_names']);
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Booking Route'),
						array('name'	 => 'bcb_vendor_id', 'value'	 => function ($data) {
								$vndDetails = Vendors::model()->findByPk($data['bcb_vendor_id']);
								echo CHtml::link($vndDetails->vnd_code, Yii::app()->createUrl("admin/vendor/view", ["code" => $vndDetails->vnd_code]), ["onclick" => "", 'target' => '_blank']);
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Vendor Code'),
						array('name'	 => 'bcb_driver_id', 'value'	 => function ($data) {
								$drvDetails = Drivers::model()->findByPk($data['bcb_driver_id']);
								echo CHtml::link($drvDetails->drv_code, Yii::app()->createUrl("admin/driver/view", ["code" => $drvDetails->drv_code]), ["onclick" => "", 'target' => '_blank']);
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Driver Code'),
						array('name' => 'bkg_pickup_date', 'value' => 'date("d/m/Y H:i:s",strtotime($data[bkg_pickup_date]))', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Pickup Time'),
						array('name' => 'bkg_create_date', 'value' => 'date("d/m/Y H:i:s",strtotime($data[bkg_create_date]))', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Create Time'),
						array('name' => 'btr_vendor_assign_ldate', 'value' => '($data[btr_vendor_assign_ldate]!="")?date("d/m/Y H:i:s",strtotime($data[btr_vendor_assign_ldate])):""', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Vendor Assign Time'),
						array('name' => 'btr_driver_assign_ldate', 'value' => '($data[btr_driver_assign_ldate]!="")?date("d/m/Y H:i:s",strtotime($data[btr_driver_assign_ldate])):""', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Driver Assign Time'),
						array('name' => 'btr_cab_assign_ldate', 'value' => '($data[btr_cab_assign_ldate]!="")?date("d/m/Y H:i:s",strtotime($data[btr_cab_assign_ldate])):""', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Cab Assign Time'),
						array('name' => 'bkg_trip_arrive_time', 'value' => '($data[bkg_trip_arrive_time]!="")?date("d/m/Y H:i:s",strtotime($data[bkg_trip_arrive_time])):""', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Driver Arrival Time'),
						array('name' => 'bkg_trip_start_time', 'value' => '($data[bkg_trip_start_time]!="")?date("d/m/Y H:i:s",strtotime($data[bkg_trip_start_time])):""', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Trip Start Time'),
						array('name' => 'bkg_trip_end_time', 'value' => '($data[bkg_trip_end_time]!="")?date("d/m/Y H:i:s",strtotime($data[bkg_trip_end_time])):""', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Trip End Time'),
						/* array('name' => 'estPickupLatlong', 'value' => function($data){
						  echo	CHtml::link($data['estPickupLatlong'],"https://maps.google.com/?q=".$data['estPickupLatlong'],array('target'=>'_blank'));
						  }, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Expected Start Cordinates'), */
						array('name'	 => 'bkg_trip_start_coordinates', 'value'	 => function ($data) {
								echo $data['estPickupLatlong'] . '<br/><br/>';
								echo CHtml::link($data['bkg_trip_start_coordinates'], "https://google.com/maps/dir/?api=1&origin=" . $data['estPickupLatlong'] . "&destination=" . $data['bkg_trip_start_coordinates'], array('target' => '_blank'));
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Trip Start Cordinates'),
						/* array('name' => 'estDropupLatlong', 'value' =>function($data){
						  echo	CHtml::link($data['estDropupLatlong'],"https://maps.google.com/?q=".$data['estDropupLatlong'],array('target'=>'_blank'));
						  }, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2 text-wrap'), 'header' => 'Expected End Cordinates'), */
						array('name'	 => 'bkg_trip_end_coordinates', 'value'	 => function ($data) {
								echo $data['estDropupLatlong'] . '<br/><br/>';
								echo CHtml::link($data['bkg_trip_end_coordinates'], "https://google.com/maps/dir/?api=1&origin=" . $data['estDropupLatlong'] . "&destination=" . $data['bkg_trip_end_coordinates'], array('target' => '_blank'));
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Trip End Cordinates'),
				)));
			}
			?> 
		</div>  
	</div>  
</div>
</div>
<script type="text/javascript">
    $(document).ready(function ()
    {
        //--- changed 1311 --///
        var start = '<?= date('d/m/Y'); ?>';
        //var startval = '<? ($model->bkg_create_date1 == '') ? '' : DateTimeFormat::DateToDatePicker($model->bkg_create_date1) ?>';
        var end = '<?= date('d/m/Y'); ?>';
        //var endval = '<? ($model->bkg_create_date2 == '') ? '' : DateTimeFormat::DateToDatePicker($model->bkg_create_date2) ?>';

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
                    maxDate: moment(),
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Previous 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Previous 15 Days': [moment().subtract(15, 'days'), moment()]
                    }
                }, function (start1, end1) {
            $('#Booking_bkg_pickup_date1').val(start1.format('YYYY-MM-DD'));
            $('#Booking_bkg_pickup_date2').val(end1.format('YYYY-MM-DD'));
            $('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgPickupDate span').html('Select Pickup Date Range');
            $('#Booking_bkg_pickup_date1').val('');
            $('#Booking_bkg_pickup_date2').val('');
        });
    })
</script>
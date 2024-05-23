<style>
    .panel-body{
        padding-top: 0 ;
        padding-bottom: 0;
    }
    .table>tbody>tr>th
    {
        vertical-align: middle
    }

    .table>tbody>tr>td, .table>tbody>tr>th{
        padding: 7px;
        line-height: 1.5em;
    }
</style>
<div class="row m0">
    <div class="col-xs-12">
        <div class="text-right">
        </div>    
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row"> 
					<?php
					$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'booking-form', 'enableClientValidation' => true,
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
						<?= $form->datePickerGroup($model, 'bkg_create_date1', array('label' => 'From Date', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'From Date')), 'prepend' => '<i class="fa fa-calendar"></i>')); ?></div>
                    <div class="col-xs-12 col-sm-4 col-md-3">
						<?=
						$form->datePickerGroup($model, 'bkg_create_date2', array('label'			 => 'To Date',
							'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'To Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
						?>  
                    </div>
					<div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
						<?php
						$typeList			 = ['1' => 'Create Date', '2' => 'Pickup Date'];
						echo $form->select2Group($model, 'dateType', array('label'			 => 'Select Date Type',
							'widgetOptions'	 => array('data' => $typeList, 'options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Select Date Type', 'class' => 'p0', 'style' => 'max-width: 100%'))));
						?>  
                    </div>
                    <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
					<?php $this->endWidget(); ?>
                </div>
				<?php
				$checkExportAccess	 = false;
				if ($roles['rpt_export_roles'] != null)
				{
					$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
				}
				if ($checkExportAccess)
				{
					echo CHtml::beginForm(Yii::app()->createUrl('report/financial/runningtotal'), "post", ['style' => "margin-bottom: 10px;margin-top: 10px;"]);
					?>
					<input type="hidden" id="export2" name="export2" value="true"/>
					<input type="hidden" id="export_from2" name="export_from2" value="<?= $model->bkg_create_date1 ?>"/>
					<input type="hidden" id="export_to2" name="export_to2" value="<?= $model->bkg_create_date2 ?>"/>
					<input type="hidden" id="export_dateType" name="export_dateType" value="<?= $model->dateType ?>"/>
					<button class="btn btn-default" type="submit" style="width: 100px;">Export</button>
					<?php
					echo CHtml::endForm();
				}
				?>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">   
						<h4> Create Date is selected then the column  will mean</h4>
						<p>1. Total bookings:Created this date and pickup any ...all status</p>
						<p>2. Avg booking amount of those bookings</p>
						<p>3. Trips booked created: This date and pickup any... 2 & 6 status only</p>
						<p>4. Trips started: Created on this date and are started on this pickup date</p>
						<p>5. Trips completed: Created on this date and completed on this pickup date</p>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">   
						<h4>Pickup date is selected then the column  will mean</h4>
						<p>1. Total bookings: Created any  and pickup this date</p>
						<p>2. Avg booking amount of those bookings</p>
						<p>3. Trips booked: On any create date with this pickup date ... 2 & 6 status only</p>
						<p>4. Trips started: Created any date on this pickup date</p>
						<p>5. Trips completed : Created any date and completed on this pickup date</p>
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
						//    'ajaxType' => 'POST',
						'columns'			 => array(
							array('name' => 'date', 'value' => 'date("d/m/Y",strtotime($data[date]))', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Date'),
							array('name' => 'b_count', 'value' => '$data[b_count]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Total Bookings'),
							array('name' => 'avg_booking_amount', 'value' => '$data[avg_booking_amount]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Avg Booking Amount'),
							array('name' => 'trips_booked', 'value' => '$data[trips_booked]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Trips Booked'),
							array('name' => 'trips_started', 'value' => '$data[trips_started]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Trips Started'),
							array('name' => 'trips_completed', 'value' => '$data[trips_completed]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Trips Completed'),
					)));
				}
				?> 
			</div>  

		</div>  
	</div>
</div>
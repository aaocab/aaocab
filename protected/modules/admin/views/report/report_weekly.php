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
					$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
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

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
						<?= $form->datePickerGroup($model, 'bkg_create_date1', array('label' => 'From Date', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'From Date')), 'prepend' => '<i class="fa fa-calendar"></i>')); ?></div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
						<?=
						$form->datePickerGroup($model, 'bkg_create_date2', array('label'			 => 'To Date',
							'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'To Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
						?>  
                    </div>


                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
						<?
						$statusList	 = [0 => 'All'] + Booking::model()->getBookingStatus();
						?>
						<?=
						$form->select2Group($model, 'bkg_vendor_status', array('label'			 => 'Select Status',
							'widgetOptions'	 => array('data' => $statusList, 'options' => array('allowClear' => true), 'htmlOptions' => array('placeholder' => 'Select Status', 'class' => 'p0', 'style' => 'max-width: 100%'))));
						?>  
                    </div>
                    <div class="col-xs-12 col-sm-3  col-md-2 col-lg-1 mt20 pt5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?></div>
					<?php $this->endWidget(); ?>
                </div>
                <div class="row" style="margin-top: 10px">  <div class="col-xs-12 col-sm-7 col-md-5">       
                        <table class="table table-bordered">
                            <thead>
                                <tr style="color: black;background: whitesmoke">
                                    <th><u>Total Bookings</u></th>
                                    <th><u>Total Trip Days</u></th>
                                    <th><u>Total Booking Amount</u></th>
                                    <th><u>Gozo Commission Due</u></th>
                                </tr>
                            </thead>
                            <tbody id="count_booking_row">                         

								<?
								if ($countReport != null)
								{
									?>

									<tr>
										<td><?= $countReport['b_count'] ?></td>
										<td><?= $countReport['t_days'] ?></td>
										<td><?= $countReport['b_amount'] ?></td>
										<td><?= $countReport['commission'] ?></td>
									</tr>
								<? } ?>
                            </tbody>
                        </table>
                    </div></div>
				<?php
				$checkExportAccess = Yii::app()->user->checkAccess("Export");
				if ($checkExportAccess)
				{
					?>
					<?= CHtml::beginForm(Yii::app()->createUrl('admin/report/weekly'), "post", ['style' => "margin-bottom: 10px;"]); ?>
					<input type="hidden" id="export1" name="export1" value="true"/>
					<input type="hidden" id="export_from1" name="export_from1" value="<?= $model->bkg_create_date1 ?>"/>
					<input type="hidden" id="export_to1" name="export_to1" value="<?= $model->bkg_create_date2 ?>"/>
					<input type="hidden" id="export_status1" name="export_status1" value="<?= $model->bkg_vendor_status ?>"/>
					<button class="btn btn-default" type="submit" style="width: 185px;">Export Above Table</button>
					<?= CHtml::endForm() ?>
					<?= CHtml::beginForm(Yii::app()->createUrl('admin/report/weekly'), "post", ['style' => "margin-bottom: 10px;"]); ?>
					<input type="hidden" id="export2" name="export2" value="true"/>
					<input type="hidden" id="export_from2" name="export_from2" value="<?= $model->bkg_create_date1 ?>"/>
					<input type="hidden" id="export_to2" name="export_to2" value="<?= $model->bkg_create_date2 ?>"/>
					<input type="hidden" id="export_status2" name="export_status2" value="<?= $model->bkg_vendor_status ?>"/>
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
                                                    <div class='panel-body table-responsive'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
						'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
						//    'ajaxType' => 'POST',
						'columns'			 => array(
							array('header' => 'Sl No.',
								'value'	 => '++$row',
							),
							array('name' => 'bkg_booking_id', 'value' => '$data[bkg_booking_id]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Booking ID'),
							array('name' => 'fromCity', 'value' => '$data[fromCity]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'From City'),
							array('name' => 'toCity', 'value' => '$data[toCity]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'To City'),
							array('name' => 'no_of_days', 'value' => '$data[no_of_days]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'No of days'),
							array('name' => 'vht_model', 'value' => '$data[serviceClass]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Cab Type'),
							array('name' => 'bkg_status', 'value' => 'Booking::model()->getBookingStatus($data[bkg_status])', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Status'),
							array('name' => 'bkg_total_amount', 'value' => '$data[bkg_total_amount]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Amount'),
							array('name' => 'drv_name', 'value' => '$data[drv_name]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Driver Name'),
							array('name' => 'commission', 'value' => '$data[commission]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Gozo Commission due'),
							array('name' => 'cities', 'value' => '$data[cities]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Route'),
							array('name' => 'bkg_vnd_name', 'value' => '$data[vendor_name]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Vendor Name'),
					)));
				}
				?> 
            </div>  

        </div>  
    </div>
</div>




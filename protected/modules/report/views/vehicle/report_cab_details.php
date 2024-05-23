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
					//CHtml::beginForm(Yii::app()->createUrl('admin/report/cabdetails'), "post", ['style' => "margin-bottom: 10px;margin-top: 10px;"]);
					$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'booking-form1', 'enableClientValidation' => true,
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
					<input type="hidden" id="export2" name="export2" value="true"/>
					<input type="hidden" id="export_from2" name="export_from2" value="<?= $model->bkg_create_date1 ?>"/>
					<input type="hidden" id="export_to2" name="export_to2" value="<?= $model->bkg_create_date2 ?>"/>
					<button class="btn btn-default" type="submit" style="width: 100px;">Export</button>
					<?php
					$this->endWidget();
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
							array('name' => 'bkg_booking_id', 'value' => '$data->bkg_booking_id', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Booking ID'),
							array('name' => 'from_city_name', 'value' => '$data->bkgFromCity->cty_name', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'From City'),
							array('name' => 'to_city_name', 'value' => '$data->bkgToCity->cty_name', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'To City'),
							array('name'	 => 'bkg_vehicle_type', 'value'	 => function ($data) {
									$cab = $data->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . '(' . $data->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . ')';
									return $cab;
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Cab Type'),
							array('name' => 'bkg_status', 'value' => '$data->getBookingStatus($data->bkg_status)', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Status'),
							array('name' => 'cab_free_time', 'value' => 'date("d/m/Y H:i:s",strtotime($data->cab_free_time))', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Cab Free Time'),
					)));
				}
				?>
            </div>  

        </div>  
    </div>
</div>










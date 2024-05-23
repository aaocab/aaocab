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
<?php 
$timeUsed    = ['1' => 'First Time Used', '2' => 'Last Time Used'];
$EntityType  = ['0' => 'Cab', '1' => 'Driver'];
$timeUsedJson = VehicleTypes::model()->getJSON($timeUsed);
$EntityTypeJson	 = VehicleTypes::model()->getJSON($EntityType);

?>
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
					<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="cab_used_time">Used Time</label>
									<?php
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'used_time',
										'val'			 => $model->used_time,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($timeUsedJson), 'allowClear' => true),
										'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Used Time')
									));
									?>
                                </div>
                    
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="cab_entity_type">Entity Type</label>
									<?php
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'entity_type',
										'val'			 => $model->entity_type,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($EntityTypeJson), 'allowClear' => true),
										'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Entity Type')
									));
									?>
                                </div>
                    
					</div>
                    <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p4">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
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
							echo CHtml::beginForm(Yii::app()->createUrl('report/driver/UnapprovedCabdriver'), "post", []);
							?>
							<input type="hidden" id="bkg_create_date1" name="bkg_create_date1" value="<?= $model->bkg_create_date1 ?>"/>
							<input type="hidden" id="bkg_create_date2" name="bkg_create_date2" value="<?= $model->bkg_create_date2 ?>"/>
							<input type="hidden" id="used_time" name="used_time" value="<?= $model->used_time ?>"/>
							<input type="hidden" id="entity_type" name="entity_type" value="<?= $model->entity_type ?>"/>
							<input type="hidden" id="export" name="export" value="true"/>
							<button class="btn btn-default btn-5x pr30 pl30 mt20" type="submit" style="width: 185px;">Export</button>
							<?php echo CHtml::endForm(); ?>	
						<?php } ?>
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
							array('name' => 'entity_type', 'value' => $entity_type, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Entity type'),
							array('name' => 'entity_id', 'value' => '$data[entity_id]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Entity ID'),
							array('name' => 'vnd_name', 'value' => '$data[vnd_name]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Vendor Name'),
							array('name' => 'total_trips', 'value' => '$data[total_trips]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Total Count When Used'),
							array('name' => 'first_time_used', 'value' => '$data[first_time_used]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'First time use date'),
							array('name' => 'last_time_used', 'value' => '$data[last_time_used]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Last time used date'),
							array('name' => 'current_status', 'value' => '$data[current_status]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Current Status'),
					)));
				}
				?>
            </div>  

        </div>  
    </div>
</div>










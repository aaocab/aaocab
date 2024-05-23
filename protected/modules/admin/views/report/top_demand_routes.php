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
$stateList		 = CHtml::listData(States::model()->findAll('stt_active = :act AND stt_country_id = :con order by stt_name', array(':act' => '1', ':con' => '99')), 'stt_id', 'stt_name');
$dataState		 = VehicleTypes::model()->getJSON($stateList);
$regions		 = States::model()->findRegionName();
$dataRegions	 = VehicleTypes::model()->getJSON($regions);
?>
<div class="row m0">
    <div class="col-xs-12">
        <div class="text-right">
        </div>    
        <div class="panel panel-default">
            <div class="panel-body">
				<div class="row"> 
					<?php
					$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'top_demand_routes', 'enableClientValidation' => true,
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
						<div class="form-group">
							<label class="control-label" style="margin-left:5px;">From Zone</label>
							<?php
							$zoneListJson	 = Zones::model()->getJSON();
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'from_zone',
								'val'			 => $model->from_zone,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($zoneListJson), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%;margin-left:5px;', 'placeholder' => 'From Zone')
							));
							?>
						</div>
					</div>
					
					
					<div class="col-xs-12 col-sm-4 col-md-3"> 
						<div class="form-group">
							<label class="control-label" style="margin-left:5px;">To Zone</label>
							<?php
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'to_zone',
								'val'			 => $model->to_zone,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($zoneListJson), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%;margin-left:5px;', 'placeholder' => 'To Zone')
							));
							?>
						</div>
					</div>
					
					<div class="col-xs-12 col-sm-4 col-md-3"> 
						<div class="form-group">
							<label class="control-label">From State</label>
							<?php
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'from_state',
								'val'			 => $model->from_state,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataState), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'From State')
							));
							?>
						</div>
					</div>
					
					<div class="col-xs-12 col-sm-4 col-md-3"> 
						<div class="form-group">
							<label class="control-label">To State</label>
							<?php
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'to_state',
								'val'			 => $model->to_state,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataState), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'To State')
							));
							?>
						</div>
					</div>
					
					<div class="col-xs-12 col-sm-4 col-md-3"> 
						<div class="form-group">
							<label class="control-label">Region</label>
							<?php
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'region',
								'val'			 => $model->region,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataRegions), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Region')
							));
							?>
						</div>
					</div>
                    
                    <div class="col-xs-12 col-sm-3  col-md-2 col-lg-1 mt20 pt5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?></div>
					<?php $this->endWidget(); ?>
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
							array('header' => 'Sl No.',
								'value'	 => '++$row',
							),
							array('name' => 'from_zone_name', 'value' => '$data[from_zone_name]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'From Zone'),
							array('name' => 'to_zone_name', 'value' => '$data[to_zone_name]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'To Zone'),
							array('name' => 'total_request', 'value' => '$data[total_request]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Total Request'),
							array('name' => 'total_confirmed', 'value' => '$data[total_confirmed]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Total Confirmed'),
							array('name' => 'day1_request', 'value' => '$data[day1_request]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => $arrDateRange[0]."_Request"),
							array('name' => 'day1_confirmed', 'value' => '$data[day1_confirmed]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => $arrDateRange[0]."_Confirmed"),
							array('name' => 'day2_request', 'value' => '$data[day2_request]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => $arrDateRange[1]."_Request"),
							array('name' => 'day2_confirmed', 'value' => '$data[day2_confirmed]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => $arrDateRange[1]."_Confirmed"),
							array('name' => 'day3_request', 'value' => '$data[day3_request]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => $arrDateRange[2]."_Request"),
							array('name' => 'day3_confirmed', 'value' => '$data[day3_confirmed]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => $arrDateRange[2]."_Confirmed"),
							array('name' => 'day4_request', 'value' => '$data[day4_request]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => $arrDateRange[3]."_Request"),
							array('name' => 'day4_confirmed', 'value' => '$data[day4_confirmed]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => $arrDateRange[3]."_Confirmed"),
							array('name' => 'day5_request', 'value' => '$data[day5_request]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => $arrDateRange[4]."_Request"),
							array('name' => 'day5_confirmed', 'value' => '$data[day5_confirmed]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => $arrDateRange[4]."_Confirmed"),
							array('name' => 'day6_request', 'value' => '$data[day6_request]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => $arrDateRange[5]."_Request"),
							array('name' => 'day6_confirmed', 'value' => '$data[day6_confirmed]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => $arrDateRange[5]."_Confirmed"),
							array('name' => 'day7_request', 'value' => '$data[day7_request]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => $arrDateRange[6]."_Request"),
							array('name' => 'day7_confirmed', 'value' => '$data[day7_confirmed]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => $arrDateRange[6]."_Confirmed"),
							array('name' => 'day8_request', 'value' => '$data[day8_request]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => $arrDateRange[7]."_Request"),
							array('name' => 'day8_confirmed', 'value' => '$data[day8_confirmed]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => $arrDateRange[7]."_Confirmed"),
							array('name' => 'day9_request', 'value' => '$data[day9_request]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => $arrDateRange[8]."_Request"),
							array('name' => 'day9_confirmed', 'value' => '$data[day9_confirmed]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => $arrDateRange[8]."_Confirmed"),
							array('name' => 'day10_request', 'value' => '$data[day10_request]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => $arrDateRange[9]."_Request"),
							array('name' => 'day10_confirmed', 'value' => '$data[day10_confirmed]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => $arrDateRange[9]."_Confirmed"),
							
					)));
				}
				?> 
            </div>  

        </div>  
    </div>
</div>






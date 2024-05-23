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
					$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'drvAppUsage-form', 'enableClientValidation' => true,
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
                    <div class="col-xs-12 col-sm-3 col-md-2"> 
                        <div class="form-group">
                            <label class="control-label" style="margin-left:5px;">Search By Zone</label>
							<?php
							$zoneListJson	 = Zones::model()->getJSON();
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'zon_id',
								'val'			 => $model->zon_id,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($zoneListJson), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%;margin-left:5px;', 'placeholder' => 'Zone List')
							));
							?>
                        </div>
                    </div>


					<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20" style="padding: 4px;">
                        <button class="btn btn-primary" type="submit" style="width: 185px;"  name="zoneSearch">Search</button> 
                    </div>
					<?php
					$this->endWidget();
					$checkExportAccess = false;
					if ($roles['rpt_export_roles'] != null)
					{
						$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
					}
					if ($checkExportAccess)
					{
						echo CHtml::beginForm(Yii::app()->createUrl('report/area/zonesupplydensity'), "post", ['style' => "margin-bottom: 10px;margin-top: 10px;"]);
						?>
						<div class="col-xs-12 col-sm-2 col-md-2">   
							<label class="control-label"></label>
							<input type="hidden" id="export" name="export" value="true"/>
							<input type="hidden" id="zon_id" name="zon_id" value="<?= $model->zon_id ?>"/>
							<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>
						</div>
						<?php
						echo CHtml::endForm();
					}
					?>
                </div>
				<?php
				if (!empty($dataProvider))
				{
					$params									 = array_filter($_REQUEST);
					$dataProvider->getPagination()->params	 = $params;
					$dataProvider->getSort()->params		 = $params;
					$this->widget('booster.widgets.TbGridView', array(
						'responsiveTable'	 => false,
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
							array('name' => 'zon_name', 'value' => '$data[zon_name]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Zone'),
							array('name'	 => 'active_vendors', 'value'	 =>
								function ($data) {
									echo CHtml::link(($data['active_vendors'] != '') ? $data['active_vendors'] : 0, Yii::app()->createUrl("admin/generalreport/ZoneSupplyDensityVendorsList", ["zid" => $data['zon_id'], "type" => 1]), ["class" => "", "target" => "_blank"]);
								},
								'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Count of active vendors serving'),
							array('name'	 => 'home_zone_vendors', 'value'	 =>
								function ($data) {
									echo CHtml::link(($data['home_zone_vendors'] != '') ? $data['home_zone_vendors'] : 0, Yii::app()->createUrl("admin/generalreport/ZoneSupplyDensityVendorsList", ["zid" => $data['zon_id'], "type" => 2]), ["class" => "", "target" => "_blank"]);
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Count of home-zone vendors'),
					)));
				}
				?>
            </div>  

        </div> 


	</div>
</div>
<script>
    var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
    var end = '<?= date('d/m/Y'); ?>';
    function setFilter(obj)
    {
        $('#export_filter1').val(obj.value);
    }
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
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 15 Days': [moment().subtract(15, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
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
</script>
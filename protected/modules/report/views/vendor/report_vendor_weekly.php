<style type="text/css">
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
<?
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
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

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
						<?php
						$daterang			 = "Select Booking Date Range";
						$createdate1		 = ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1;
						$createdate2		 = ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2;
						if ($createdate1 != '' && $createdate2 != '')
						{
							$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
						}
						?>
                        <label  class="control-label">Booking Date</label>
						<div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
							<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
							<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
						</div>	
						<?= $form->hiddenField($model, 'bkg_create_date1'); ?>
						<?= $form->hiddenField($model, 'bkg_create_date2'); ?>			
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
						<?
						$statusList = [0 => 'All'] + Booking::model()->getBookingStatus();
						?>
						<?=
						$form->select2Group($model, 'bkg_vendor_status', array('label'			 => 'Select Status',
							'widgetOptions'	 => array('data' => $statusList, 'options' => array('allowClear' => true), 'htmlOptions' => array('placeholder' => 'Select Status', 'class' => 'p0', 'style' => 'max-width: 100%'))));
						?>  
                    </div>
                    <div class="col-xs-12 col-sm-6  col-md-6 col-lg-4 col-md-offset-2 col-lg-offset-0">
						<div class="form-group cityinput">
							<label class="control-label">Vendor</label>
							<?
							//			$vendorList	 = [0 => 'All'] + Vendors::model()->getVendorList();
							//			
							//			
							?>
							<?
							//=
							//			$form->select2Group($model, 'bkg_vendor', 
							//			array('label'		 => 'Select Vendor',
							//			    'widgetOptions'	 =>
							//			    array('data'		 => $vendorList,
							//				'options'	 => array('allowClear' => true),
							//				'htmlOptions'	 => array('placeholder'	 => 'Select Vendor',
							//				    'class'		 => 'p0',
							//				    'style'		 => 'max-width: 100%'))));

							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'bkg_vendor',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Vendor",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width' => '100%'),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
							populateVendor(this, '{$model->bkg_vendor}');
							}",
							'load'			 => "js:function(query, callback){
							loadVendor(query, callback);
							}",
							'render'		 => "js:{
							option: function(item, escape){
							return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
							},
							option_create: function(data, escape){
							return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
							}
							}",
								),
							));
							?>  
						</div></div>
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
				$checkExportAccess = false;
				if ($roles['rpt_export_roles'] != null)
				{
					$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
				}
				if ($checkExportAccess)
				{
					?>
					<?= CHtml::beginForm(Yii::app()->createUrl('report/financial/vendorweekly'), "post", ['style' => "margin-bottom: 10px;"]); ?>
					<input type="hidden" id="export1" name="export1" value="true"/>
					<input type="hidden" id="export_from1" name="export_from1" value="<?= $model->bkg_create_date1 ?>"/>
					<input type="hidden" id="export_to1" name="export_to1" value="<?= $model->bkg_create_date2 ?>"/>
					<input type="hidden" id="export_id1" name="export_id1" value="<?= $model->bkg_vendor ?>"/>
					<input type="hidden" id="export_status1" name="export_status1" value="<?= $model->bkg_vendor_status ?>"/>
					<button class="btn btn-default" type="submit" style="width: 185px;">Export Above Table</button>
					<?= CHtml::endForm() ?>
					<?= CHtml::beginForm(Yii::app()->createUrl('report/financial/vendorweekly'), "post", ['style' => "margin-bottom: 10px;"]); ?>

					<input type="hidden" id="export2" name="export2" value="true"/>
					<input type="hidden" id="export_from2" name="export_from2" value="<?= $model->bkg_create_date1 ?>"/>
					<input type="hidden" id="export_to2" name="export_to2" value="<?= $model->bkg_create_date2 ?>"/>
					<input type="hidden" id="export_id2" name="export_id2" value="<?= $model->bkg_vendor ?>"/>
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
							array('name' => 'from_city_name', 'value' => '$data[fromCity]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'From City'),
							array('name' => 'to_city_name', 'value' => '$data[toCity]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'To City'),
							array('name' => 'no_of_days', 'value' => '$data[no_of_days]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'No of days'),
							array('name' => 'bkg_vehicle_id', 'value' => '$data[serviceClass]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Cab Type'),
							array('name' => 'bkg_status', 'value' => 'Booking::model()->getBookingStatus($data[bkg_status])', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Status'),
							array('name' => 'bkg_amount', 'value' => '$data[bkg_total_amount]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Amount'),
							array('name' => 'bkg_drv_name', 'value' => '$data[drv_name]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Driver Name'),
							array('name'	 => 'commission', 'value'	 => function ($data) {
									echo $data['commission'] . "(" . round((($data['commission'] / $data['bkg_total_amount']) * 100), 2) . "%)";
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Gozo Commission due'),
							array('name'	 => 'gozo_amount',
								'value'	 => function ($data) {
									echo $data['gozo_amount'] . "(" . round((($data['gozo_amount'] / $data['bkg_total_amount']) * 100), 2) . "%)";
								},
								'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Gozo Amount'),
					)));
				}
				?> 
            </div>  

        </div>  
    </div>
</div>
<script>
    $(document).ready(function ()
    {
        var start = '<?= date('d/m/Y'); ?>';
        var end = '<?= date('d/m/Y', strtotime('+7 days')); ?>';
        $('#bkgCreateDate').daterangepicker(
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
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    }
                }, function (start1, end1) {
            $('#Booking_bkg_create_date1').val(start1.format('YYYY-MM-DD'));
            $('#Booking_bkg_create_date2').val(end1.format('YYYY-MM-DD'));
            $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgCreateDate span').html('Select Booking Date Range');
            $('#Booking_bkg_create_date1').val('');
            $('#Booking_bkg_create_date2').val('');
        });
    });

</script>



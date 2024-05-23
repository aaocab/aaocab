
<?

$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/city.js?v=' . $version);
$selectizeOptions = ['create' => false, 'persist' => true, 'selectOnTab' => true,
'createOnBlur' => true, 'dropdownParent' => 'body',
'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField' => 'id',
'openOnFocus' => true, 'preload' => false,
'labelField' => 'text', 'valueField' => 'id', 'searchField' => 'text', 'closeAfterSelect' => true,
'addPrecedence' => false];

?>
<style>
    .panel-body
    {
        padding-top: 0 ;
        padding-bottom: 0;
    }

    .table>tbody>tr>th
    {
        vertical-align: middle;
    }

    .table>tbody>tr>td, .table>thead>tr>th
    {
        padding: 7px;
        line-height: 1.5em;
    }

    .table-bordered>tbody>tr>td, .table-bordered>thead>tr>th
    {
        border: 1px solid #cccccc !important;
        border-collapse: collapse;
        text-align: center;
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
					$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'agent-accounts-form', 'enableClientValidation' => true,
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
							<label class="control-label">Search By Booking ID</label>
							<?= $form->textField($model, 'search', array('label' => 'Search By Booking ID', 'placeholder' => "search by booking id", 'class' => 'form-control')) ?>
                        </div>   
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-3"> 
                        <div class="form-group">
                            <label class="control-label">From</label>

							<?php
//                    echo $form->hiddenField($model, 'bkg_from_city_id');
//                    echo $form->hiddenField($model, 'bkg_to_city_id');

							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'bkg_from_city_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Source City",
								'fullWidth'			 => false,
								'options'			 => array('allowClear' => true),
								'htmlOptions'		 => array('width'	 => '100%',
									'id'	 => 'Booking_bkg_from_city_id'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                            populateSource(this, '{$model->bkg_from_city_id}');
                                                }",
							'load'			 => "js:function(query, callback){
                                            loadSource(query, callback);
                                            }",
							'render'		 => "js:{
                                            option: function(item, escape){
                                            return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                                            },
                                            option_create: function(data, escape){
                                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                            }
                                            }",
								),
							));
							?>

                        </div> </div>
                    <div class="col-xs-12 col-sm-4 col-md-3"> 
                        <div class="form-group">
                            <label class="control-label">To</label>
							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'bkg_to_city_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Destination City",
								'fullWidth'			 => false,
								'options'			 => array('allowClear' => true),
								'htmlOptions'		 => array('width'	 => '100%',
									'id'	 => 'Booking_bkg_to_city_id'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                            populateSource(this, '{$model->bkg_to_city_id}');
                                                }",
							'load'			 => "js:function(query, callback){
                                            loadSource(query, callback);
                                            }",
							'render'		 => "js:{
                                            option: function(item, escape){
                                            return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                                            },
                                            option_create: function(data, escape){
                                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                            }
                                            }",
								),
							));
							?>
                        </div> </div>
                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="form-group">
                            <label class="control-label">Booking Status</label>
							<?php
							$arrJSON1	 = array();
							$arr1		 = ['1' => 'Pending', '2' => 'Completed', '3' => 'Cancelled'];
							foreach ($arr1 as $key => $val)
							{
								$arrJSON1[] = array("id" => $key, "text" => $val);
							}
							$statuslist = CJSON::encode($arrJSON1);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkg_status_name',
								'val'			 => $model->bkg_status_name,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($statuslist), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Status'),
							));
							?>
						</div> </div>
					<br>

					<div class="col-sm-4">
						<div class="form-group">
							<label  class="control-label">Booking Date</label>
							<?
							$daterang = "Select Booking Date Range";
							$createdate1 = ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1;
							$createdate2 = ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2;
							if ($createdate1 != '' && $createdate2 != '') {
							$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
							}
							?>

							<div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
								<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
								<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
							</div>
							<?
							echo $form->hiddenField($model, 'bkg_create_date1');
							echo $form->hiddenField($model, 'bkg_create_date2');
							?>
						</div>
					</div>

					<div class="col-sm-4 ">
						<div class="form-group">
							<label  class="control-label">Pickup Date</label>
							<?
							$daterange = "Select Pickup Date Range";
							$pickupdate1 = ($model->bkg_pickup_date1 == '') ? '' : $model->bkg_pickup_date1;
							$pickupdate2 = ($model->bkg_pickup_date2 == '') ? '' : $model->bkg_pickup_date2;
							if ($pickupdate1 != '' && $pickupdate2 != '') {
							$daterange = date('F d, Y', strtotime($pickupdate1)) . " - " . date('F d, Y', strtotime($pickupdate2));
							}
							?>

							<div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
								<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
								<span style="min-width: 240px"><?= $daterange ?></span> <b class="caret"></b>
							</div>
							<?
							echo $form->hiddenField($model, 'bkg_pickup_date1');
							echo $form->hiddenField($model, 'bkg_pickup_date2');
							?>
						</div>
					</div>

                    <div class="col-xs-12 text-center">
                        <button class="btn btn-primary mt5" type="submit" style="width: 140px;">Search</button>
                    </div>
				<?php $this->endWidget(); ?>
                </div>
<?= CHtml::beginForm(Yii::app()->createUrl('agent/booking/accountsdashboard'), "post", ['style' => "margin-bottom: 10px;"]); ?>
				<input type="hidden" id="export" name="export" value="true"/>
				<input type="hidden" id="export_from_city" name="export_from_city" value="<?= $model->bkg_from_city_id ?>"/>
				<input type="hidden" id="export_to_city" name="export_to_city" value="<?= $model->bkg_to_city_id ?>"/>
				<input type="hidden" id="export_search" name="export_search" value="<?= $model->search ?>"/>
				<input type="hidden" id="export_status" name="export_status" value="<?= $model->bkg_status_name ?>"/>
				<input type="hidden" id="export_from_date" name="export_from_date" value="<?= $model->bkg_pickup_date1 ?>"/>
				<input type="hidden" id="export_to_date" name="export_to_date" value="<?= $model->bkg_pickup_date2 ?>"/>
				<button class="btn btn-default" type="submit" style="width: 185px;">Export Table</button>
				<?= CHtml::endForm() ?>
				<?php
				if (!empty($dataProvider))
				{
					$params									 = array_filter($_REQUEST);
					$dataProvider->getPagination()->params	 = $params;
					$dataProvider->getSort()->params		 = $params;
					$display = ($agentId == Config::get('spicejet.partner.id')) ? true : false;

					$this->widget('booster.widgets.TbGridView', array(
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataProvider,
						'template'			 => "<div class='panel-heading'><div class='row m0'>
                                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                                    </div></div>
                                                    <div class='panel-body table-responsive p0'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
						'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
						'columns'			 => array(
							array('name'				 => 'bkg_booking_id', 'value'				 => '$data[bkg_booking_id]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Gozo Bkg ID'),
							array('name'				 => 'bkg_agent_ref_code', 'value'				 => '$data[bkg_agent_ref_code]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Partner Ref ID'),
							array('name'				 => 'bkg_create_date', 'value'				 => 'date("d-m-Y H:i:s",strtotime($data[bkg_create_date]))', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Create Date / Time'),
							array('name'				 => 'bkg_pickup_date', 'value'				 => 'date("d-m-Y H:i:s",strtotime($data[bkg_pickup_date]))', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Pickup Date / Time'),
							array('name'				 => 'bkg_base_amount', 'value'				 => '$data[bkg_base_amount]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Base Fare'),
							array('name'				 => 'bkg_additional_charge', 'value'				 => '$data[bkg_additional_charge]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Extra Charges'),
							array('name'				 => 'bkg_extra_km_charge', 'value'				 => '($data[bkg_extra_km_charge] > 0) ?$data[bkg_extra_km_charge]." (".$data[bkg_extra_total_km]." km)" : "0"', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Extra Charges (Km)'),
							array('name'				 => 'bkg_extra_min', 'value'				 => '$data[bkg_extra_min]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Extra Minutes'),
							array('name'				 => 'bkg_extra_total_min_charge', 'value'				 => '$data[bkg_extra_total_min_charge]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Extra Minutes Charges'),
							array('name'				 => 'bkg_discount_amount', 'value'				 => '$data[bkg_discount_amount]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'discount'),
							array('name'				 => 'bkg_driver_allowance_amount', 'value'				 => '$data[bkg_driver_allowance_amount]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'DA'),
							array('name'				 => 'bkg_toll_tax', 'value'				 => '$data[bkg_toll_tax]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Toll'),
							array('name'				 => 'bkg_state_tax', 'value'				 => '$data[bkg_state_tax]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'State'),
							array('name'				 => 'bkg_service_tax', 'value'				 => '$data[bkg_service_tax]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'GST'),
							array('name'				 => 'bkg_airport_entry_fee', 'value'				 => '$data[bkg_airport_entry_fee]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'AirportEntryCharge'),
							array('name'				 => 'bkg_total_amount', 'value'				 => '$data[bkg_total_amount]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Total Fare'),

							array('name'			 => 'bkg_partner_commission', 
								'value'			 => function($data) {
								
										echo $data['bkg_partner_commission']; 
								},	
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Partner Commission'),

								
							array('name'			 => 'bkg_partner_extra_commission', 
								'value'			 => function($data) {
										echo $data['bkg_partner_extra_commission'];
								},	
								'sortable'			 => true,
								'visible'		 => $display ,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Partner extra commission'),

							array('name'			 => 'commissionOnGst', 'value'				 => '$data[commissionGst]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Commission On GST'),
							array('name'				 => 'bkg_advance_amount', 'value'				 => '$data[bkg_advance_amount]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Advance'),
							array('name'				 => 'bkg_refund_amount', 'value'				 => '$data[bkg_refund_amount]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Refund'),
							array('name'				 => 'status', 'value'				 => '$data[status]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Status'),
							array('name'				 => 'settled', 'value'				 => '$data[settled]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Settled'),
							array(
								'header'			 => 'Action',
								'class'				 => 'CButtonColumn',
								'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
								'template'			 => '{addRemark}{marksettle}',
								'buttons'			 => array(
									'addRemark'		 => array(
										'click'			 => 'function(e){                                                        
                                            try
                                            {
                                                $href = $(this).attr("href");
                                                jQuery.ajax({type:"GET",url:$href,success:function(data)
                                                {
                                                    bootbox.dialog({ 
                                                    message: data, 
                                                    className:"bootbox-sm",
                                                    title:"Add Remark",
                                                    success: function(result){
                                                        if(result.success)
                                                        {
                                                        }
                                                        else
                                                        {
                                                        alert(\'Sorry error occured\');
                                                        }
                                                },
                                                error: function(xhr, status, error){
                                                    alert(\'Sorry error occured\');
                                                }
                                            });
                                        }}); 
                                    }
                                    catch(e)
                                    { 
                                        alert(e); 
                                    }
                                    return false;

                                    }',
										'url'			 => 'Yii::app()->createUrl("agent/booking/addremark", array(\'bkg_id\' => $data["bkg_id"]))',
										'imageUrl'		 => Yii::app()->request->baseUrl . '\images\icon\edit_booking.png',
										'htmlOptions'	 => array('style' => 'height:20px'),
										'options'		 => array('data-toggle' => 'ajaxModal', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs remark p0 actBtn', 'title' => 'Add Remark/ Remarks List')
									),
									'marksettle'	 => array(
										'click'			 => 'function(){
                                            var con = confirm("Are you sure you want to mark settle this booking?");
                                            return con;
                                        }',
										'url'			 => 'Yii::app()->createUrl("agent/booking/marksettled", array(\'bkg_id\' => $data["bkg_id"]))',
										'imageUrl'		 => Yii::app()->request->baseUrl . '\images\icon\reconfirmed.png',
										'htmlOptions'	 => array('style' => 'height:20px'),
										'visible'		 => '($data[bkg_settled_flag] == 0)',
										'options'		 => array('data-toggle' => 'ajaxModal', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs settle p0 actBtn', 'title' => 'Mark Settled'),
									),
									'htmlOptions'	 => array('class' => 'center'),
								))
					)));
				}
				?> 
            </div>
        </div>  
    </div>
</div>
<script>
	$sourceList = null;
	function populateSource(obj, cityId)
	{

		obj.load(function (callback)
		{
			var obj = this;
			if ($sourceList == null)
			{
				xhr = $.ajax({
					url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
					dataType: 'json',
					success: function (results)
					{
						$sourceList = results;
						obj.enable();
						callback($sourceList);
						obj.setValue(cityId);
					},
					error: function ()
					{
						callback();
					}
				});
			} else
			{
				obj.enable();
				callback($sourceList);
				obj.setValue(cityId);
			}
		});
	}
	function loadSource(query, callback)
	{
		//	if (!query.length) return callback();
		$.ajax({
			url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=1&q=' + encodeURIComponent(query),
			type: 'GET',
			dataType: 'json',
			global: false,
			error: function ()
			{
				callback();
			},
			success: function (res)
			{
				callback(res);
			}
		});
	}

	var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
	var end = '<?= date('d/m/Y'); ?>';

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
					'Tommorow': [moment().add(1, 'days'), moment().add(1, 'days')],
					'Last 7 Days': [moment().subtract(6, 'days'), moment()],
					'Next 7 Days': [moment(), moment().add(6, 'days')],
					'Last 30 Days': [moment().subtract(29, 'days'), moment()],
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
	$('#btnreset').click(function () {
		$(".agtSelect2").select2('val', '').trigger('change')
		$('#bkgPickupDate span').html('Select Pickup Date Range');
		$('#Booking_bkg_pickup_date1').val('');
		$('#Booking_bkg_pickup_date2').val('');
		$('#bkgCreateDate span').html('Select Booking Date Range');
		$('#Booking_bkg_create_date1').val('');
		$('#Booking_bkg_create_date2').val('');
//        $('#agtTransactionDate span').html('Select Transaction Date Range');
//        $('#Booking_agt_trans_created1').val('');
//        $('#Booking_agt_trans_created2').val('');

	});
</script>

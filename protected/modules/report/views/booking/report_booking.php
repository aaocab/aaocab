<?php
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<style type="text/css">
    .cityinput > .selectize-control>.selectize-input{
        width:100% !important;
    }
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


                    <div class="col-xs-6 col-sm-4 col-md-4" style="">
                        <div class="form-group">
                            <label class="control-label">Create date range</label>
							<?php
							$daterang			 = "Select Date Range";
							$bkg_create_date1	 = ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1;
							$bkg_create_date2	 = ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2;
							if ($bkg_create_date1 != '' && $bkg_create_date2 != '')
							{
								$daterang = date('F d, Y', strtotime($bkg_create_date1)) . " - " . date('F d, Y', strtotime($bkg_create_date2));
							}
							?>
                            <div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>
							<?= $form->hiddenField($model, 'bkg_create_date1'); ?>
							<?= $form->hiddenField($model, 'bkg_create_date2'); ?>

                        </div></div>





                    <div class="col-xs-12 col-sm-4 col-md-3"> 
                        <div class="form-group cityinput">
                            <label class="control-label">From</label>
							<?php
//                            $datacity = Cities::model()->getCityByFromBooking1();
//                            $this->widget('booster.widgets.TbSelect2', array(
//                                'model' => $model,
//                                'attribute' => 'bkg_from_city_id',
//                                'val' => $model->bkg_from_city_id,
//                                'asDropDownList' => FALSE,
//                                'options' => array('data' => new CJavaScriptExpression($datacity), 'allowClear' => true),
//                                'htmlOptions' => array('class' => 'p0', 'style' => 'width: 100%', 
//                                'placeholder' => 'From')
//                            ));
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'bkg_from_city_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "From",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width' => '100%',
								//  'id' => 'from_city_id1'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                  populateSourceCity(this, '{$model->bkg_from_city_id}');
                                                }",
							'load'			 => "js:function(query, callback){
                                loadSourceCity(query, callback);
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
                        <div class="form-group cityinput">

                            <label class="control-label">To</label>
							<?php
//                            $datacity = Cities::model()->getCityByToBooking1();
//                            $this->widget('booster.widgets.TbSelect2', array(
//                                'model' => $model,
//                                'attribute' => 'bkg_to_city_id',
//                                'val' => $model->bkg_to_city_id,
//                                'asDropDownList' => FALSE,
//                                'options' => array('data' => new CJavaScriptExpression($datacity), 'allowClear' => true),
//                                'htmlOptions' => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'To')
//                            ));
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'bkg_to_city_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "To",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width' => '100%',
								//  'id' => 'from_city_id1'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                  populateSourceCity(this, '{$model->bkg_to_city_id}');
                                                }",
							'load'			 => "js:function(query, callback){
                                loadSourceCity(query, callback);
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
                        <div class="form-group cityinput">
                            <label class="control-label">Vendor</label>
							<?php
//                            $vendorListJson = Vendors::model()->getJSON();
//                            $this->widget('booster.widgets.TbSelect2', array(
//                                'model' => $model,
//                                'attribute' => 'bcb_vendor_id',
//                                'val' => $model->bcb_vendor_id,
//                                'asDropDownList' => FALSE,
//                                'options' => array('data' => new CJavaScriptExpression($vendorListJson), 'allowClear' => true),
//                                'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Vendor')
//                            ));
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'bcb_vendor_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Vendor",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width' => '100%'),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                  populateVendor(this, '{$model->bcb_vendor_id}');
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
                        </div> </div>
                    <div class="col-xs-12 col-sm-4 col-md-3"> 
                        <div class="form-group">
                            <label class="control-label">Platform</label>
							<?php
							$platform			 = BookingTrail::model()->booking_platform;
							$datainfo			 = VehicleTypes::model()->getJSON($platform);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $trialModel,
								'attribute'		 => 'bkg_platform',
								'val'			 => $trialModel->bkg_platform,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($datainfo), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Platform')
							));
							?>
                        </div> 
                    </div>


                    <div class="col-xs-12 col-sm-4 col-md-3"> 
                        <div class="form-group">
                            <label class="control-label">Booking Status</label>
							<?php
							$bookingStatusArr	 = ['' => 'ALL'] + Booking::model()->getBookingStatus();
							$datainfo			 = VehicleTypes::model()->getJSON($bookingStatusArr);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkg_status',
								'val'			 => $model->bkg_status,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($datainfo), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Status')
							));
							?>
                        </div> 
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3"> 
                        <div class="form-group cityinput">
                            <label class="control-label">Channel Partner</label>
							<?php
//                            $dataagents = Agents::model()->getAgentsFromBooking();
//                            $this->widget('booster.widgets.TbSelect2', array(
//                                'model' => $model,
//                                'attribute' => 'bkg_agent_id',
//                                'val' => $model->bkg_agent_id,
//                                'asDropDownList' => FALSE,
//                                'options' => array('data' => new CJavaScriptExpression($dataagents), 'allowClear' => true),
//                                'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Partner Name')
//                            ));
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'bkg_agent_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Partner Name",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width' => '100%'),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                  populatePartner(this, '{$model->bkg_agent_id}');
                                }",
							'load'			 => "js:function(query, callback){
                                loadPartner(query, callback);
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
                        </div> 
                    </div>
                    <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
					<?php $this->endWidget(); ?>
                </div>
                <div class="row" style="margin-top: 10px"><div class="col-xs-12 col-sm-7 col-md-5">       
                        <table class="table table-bordered" style="">
                            <thead>
                                <tr style="color: black;background: whitesmoke">
                                    <th><u>Status</u></th>
                                    <th><u>Count</u></th>
                                    <th><u>Amount</u></th>
                                </tr>
                            </thead>
                            <tbody id="count_booking_row">                         
								<?php
								$total				 = 0;
								$genuine			 = 0;
								$totalSUM			 = 0;
								$genuineSUM			 = 0;
								$bookingStatus		 = Booking::model()->getBookingStatus();
								foreach ($countReport as $value)
								{
									$total		 += $value['count'];
									$totalSUM	 += $value['sum'];
									if (in_array($value['bkg_status'], [2, 3, 5, 6, 7]))
									{
										$genuine	 += $value['count'];
										$genuineSUM	 += $value['sum'];
									}
									?>
									<tr>
										<td><?= $bookingStatus[$value['bkg_status']] ?></td>
										<td><?= $value['count'] ?></td>
										<td><?= $value['sum'] ?></td>
									</tr>

									<?
									$total_booking	 = $value['total_count'];
									$total_amount	 = $value['total_amount'];
								}
								?>
								<tr><td style="border-top : 1px solid grey;font-style: italic;">Total</td><td style="border-top : 1px solid grey;"><?= $total ?></td><td style="border-top : 1px solid grey;"><?= $totalSUM ?></td></tr>
								<tr><td style="border-top : 1px solid grey;font-style: italic;">Genuine Total</td><td style="border-top : 1px solid grey;"><?= $genuine ?></td><td style="border-top : 1px solid grey;"><?= $genuineSUM ?></td></tr>

							</tbody>
						</table>
					</div>
				</div>
				<?php
				$checkExportAccess = false;
				if ($roles['rpt_export_roles'] != null)
				{
					$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
				}
				if ($checkExportAccess)
				{
					//CHtml::beginForm(Yii::app()->createUrl('admin/report/booking'), "post", ['style' => "margin-bottom: 10px;"]);
					?>
					<?= CHtml::beginForm(Yii::app()->createUrl('report/booking/booking'), "post", ['style' => "margin-bottom: 10px;"]); ?>
					<input type="hidden" id="export1" name="export1" value="true"/>
					<input type="hidden" id="export_from1" name="export_from1" value="<?= $model->bkg_create_date1 ?>"/>
					<input type="hidden" id="export_to1" name="export_to1" value="<?= $model->bkg_create_date2 ?>"/>
					<input type="hidden" id="export_from_city1" name="export_from_city1" value="<?= $model->bkg_from_city_id ?>"/>
					<input type="hidden" id="export_to_city1" name="export_to_city1" value="<?= $model->bkg_to_city_id ?>"/>
					<input type="hidden" id="export_vendor1" name="export_vendor1" value="<?= $model->bcb_vendor_id ?>"/>
					<input type="hidden" id="export_platform1" name="export_platform1" value="<?= $trialModel->bkg_platform ?>"/>
					<input type="hidden" id="export_agent1" name="export_agent1" value="<?= $model->bkg_agent_id ?>"/>
					<input type="hidden" id="export_status" name="export_status" value="<?= $model->bkg_status ?>"/>
					<button class="btn btn-default" type="submit" style="width: 185px; ">Export Above Table</button>
					<?= CHtml::endForm() ?>
					<?= CHtml::beginForm(Yii::app()->createUrl('report/booking/booking'), "post", ['style' => "margin-bottom: 10px;"]); ?>
					<input type="hidden" id="export2" name="export2" value="true"/>
					<input type="hidden" id="export_from2" name="export_from2" value="<?= $model->bkg_create_date1 ?>"/>
					<input type="hidden" id="export_to2" name="export_to2" value="<?= $model->bkg_create_date2 ?>"/>
					<input type="hidden" id="export_from_city2" name="export_from_city2" value="<?= $model->bkg_from_city_id ?>"/>
					<input type="hidden" id="export_to_city2" name="export_to_city2" value="<?= $model->bkg_to_city_id ?>"/>
					<input type="hidden" id="export_vendor2" name="export_vendor2" value="<?= $model->bcb_vendor_id ?>"/>
					<input type="hidden" id="export_platform2" name="export_platform2" value="<?= $trialModel->bkg_platform ?>"/>
					<input type="hidden" id="export_agent2" name="export_agent2" value="<?= $model->bkg_agent_id ?>"/>
					<input type="hidden" id="export_status2" name="export_status2" value="<?= $model->bkg_status ?>"/>
					<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>
					<?php
					echo CHtml::endForm();
				}
				?>
				<?php
				$checkContactAccess = Yii::app()->user->checkAccess("bookingContactAccess");
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
						//       'ajaxType' => 'POST',
						'columns'			 => array(
							array('name'	 => 'bkg_booking_id', 'value'	 => function ($data) {
									echo CHtml::link($data['bkg_booking_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
								}, 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Booking ID'),
							array('name'				 => 'book_type', 'value'				 => '$data[book_type]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Type'),
							array('name'				 => 'agent_name', 'value'				 => '$data[agent_name]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Partner Name'),
							array('name'	 => 'bkg_booking_type', 'value'	 => function ($data) {
									echo $data['serviceType'];
								},
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Booking Type'),
							array('name'				 => 'vht_model', 'value'				 => '$data[serviceClass]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Cab Type'),
							array('name'				 => 'from_city_name', 'value'				 => '$data[fromCity]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'From City'),
							array('name'				 => 'to_city_name', 'value'				 => '$data[toCity]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'To City'),
							array('name'				 => 'sourceZone', 'value'				 => '$data[sourceZone]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Source Zone'),
							array('name'				 => 'destinationZone', 'value'				 => '$data[destinationZone]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Destination Zone'),
							array('name'				 => 'region', 'value'				 => '$data[region]',
								'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Region'),
							array('name'				 => 'vendor_name', 'value'				 => '$data[vendor_name]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Vendor Name'),
							array('name'	 => 'base_fare', 'value'	 => function ($data) {
									echo $data['base_fare'];
								}, 'sortable'								 => true, 'headerHtmlOptions'						 => array('class' => 'col-xs-1'), 'header'								 => 'Base Fare'),
							array('name'	 => 'discount', 'value'	 => function ($data) {
									echo $data['discount'];
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Discount'),
							array('name'	 => 'ry_quote_vendor_amount', 'value'	 => function ($data) {
									echo round((($data['ry_quote_vendor_amount'] / $data['ry_booking_amount']) * 100), 2);
								},
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'QUOTED MARGIN (%)'),
							array('name'	 => 'ry_gozo_amount', 'value'	 => function ($data) {
									echo round((($data['ry_gozo_amount'] / $data['ry_booking_amount']) * 100), 2);
								},
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'REALIZED MARGIN (%)'),
							array('name'				 => 'driver_name', 'value'				 => '$data[driver_name]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Driver Name'),
							array('name'				 => 'cab_number', 'value'				 => '$data[cab_number]',
								'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Cab Number'),
							array('name'				 => 'route_name', 'value'				 => '$data[cities]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Route'),
							array('name'				 => 'bkg_status', 'value'				 => 'Booking::model()->getBookingStatus($data[bkg_status])',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Status'),
							array('name'	 => 'cancellation_datetime', 'value'	 => function ($data) {
									$can_datetime = ($data['bkg_status'] == 9) ? date("d-m-Y H:i:s", strtotime($data['cancellation_datetime'])) : '';
									echo $can_datetime;
								},
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
								'header'			 => 'Cancellation Date/Time'),
							array('name'	 => 'bkg_total_amount', 'value'	 => function ($data) {
									echo round($data['bkg_total_amount']);
								},
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
								'htmlOptions'		 => array('class' => 'text-right'),
								'header'			 => 'Amount'),
							array('name'	 => 'bkg_vendor_amount', 'value'	 => function ($data) {
									echo round($data['bkg_vendor_amount']);
								},
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
								'htmlOptions'		 => array('class' => 'text-right'),
								'header'			 => 'Vendor Amount'),
							array('name'	 => 'bkg_advance_amount', 'value'	 => function ($data) {
									echo round($data['bkg_advance_amount']);
								},
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
								'htmlOptions'		 => array('class' => 'text-right'),
								'header'			 => 'Advanced Received'),
							array('name'	 => 'bkg_due_amount', 'value'	 => function ($data) {
									echo round($data['bkg_due_amount']);
								},
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
								'htmlOptions'		 => array('class' => 'text-right'),
								'header'			 => 'Amount Due'),
							array('name'	 => 'bkg_gozo_amount', 'value'	 => function ($data) {
									echo round($data['ry_gozo_amount']);
								},
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
								'htmlOptions'		 => array('class' => 'text-right'),
								'header'			 => 'Gozo Amount'),
							array('name'				 => 'bkg_pickup_date', 'value'				 => 'date("d-m-Y H:i:s",strtotime($data[bkg_pickup_date]))',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
								'header'			 => 'Pickup Date/Time'),
							array('name'				 => 'bkg_pickup_address', 'value'				 => '$data[bkg_pickup_address]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Pickup Address'),
							array('name'	 => 'bkg_return_date',
								'value'	 => function ($data) {
									$value = $data["bkg_booking_type"];
									if ($value == 1)
									{
										$valueType = "";
									}
									else
									{
										$valueType = date("d-m-Y H:i:s", strtotime($data[bkg_return_date]));
									}
									return $valueType;
								},
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Return Date/Time'),
							array('name'				 => 'bkg_drop_address', 'value'				 => '$data[bkg_drop_address]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Drop Off Address'),
							array('name'				 => 'bkg_create_date', 'value'				 => 'date("d-m-Y H:i:s",strtotime($data[bkg_create_date]))',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Booking Date/Time'),
							array('name'				 => 'bkg_info_source', 'value'				 => '$data[bkg_info_source]',
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Source'),
							array('name'				 => 'bkg_platform', 'value'				 => 'BookingTrail::model()->getPlatform($data[bkg_platform])',
								'sortable'			 => true,
								'htmlOptions'		 => array('class' => 'text-center'),
								'headerHtmlOptions'	 => array('class' => 'text-center'),
								'header'			 => 'Platform'),
							array('name'				 => 'bkg_vnd_phone', 'value'				 => '$data[vnd_phone]',
								'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Vendor Phone'),
							array('name'	 => 'TotalVendorAssignedCount',
								'value'	 => function ($data) {
									if ($data['bkg_status'] > 0)
									{
										$bookingCabDetails = BookingCab::getBookingCabDetailsByBkgID($data['bkg_id']);
										if ($bookingCabDetails)
										{
											echo $bookingCabDetails['TotalVendorAssignedCount'];
											$GLOBALS['LVendorAssignmentDate']	 = $bookingCabDetails['LVendorAssignmentDate'];
											$GLOBALS['FVendorAssignmentDate']	 = $bookingCabDetails['FVendorAssignmentDate'];
											$GLOBALS['LVendorAmount']			 = $bookingCabDetails['LVendorAmount'];
											$GLOBALS['FVendorAmount']			 = $bookingCabDetails['FVendorAmount'];
											$GLOBALS['LVendorID']				 = $bookingCabDetails['LVendorID'];
											$GLOBALS['FVendorID']				 = $bookingCabDetails['FVendorID'];
										}
									}
									else
									{
										$GLOBALS['LVendorAssignmentDate']	 = "";
										$GLOBALS['FVendorAssignmentDate']	 = "";
										$GLOBALS['LVendorAmount']			 = "";
										$GLOBALS['FVendorAmount']			 = "";
										$GLOBALS['LVendorID']				 = "";
										$GLOBALS['FVendorID']				 = "";
									}
								},
								'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Vendor Assigned Count'),
							array('name'	 => 'LVendorAssignmentDate',
								'value'	 => function ($data) {
									echo $GLOBALS['LVendorAssignmentDate'];
									unset($GLOBALS['LVendorAssignmentDate']);
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Last Vendor Assignment Date'),
							array('name'	 => 'FVendorAssignmentDate',
								'value'	 => function ($data) {
									echo $GLOBALS['FVendorAssignmentDate'];
									unset($GLOBALS['FVendorAssignmentDate']);
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'First Vendor Assignment Date'),
							array('name'	 => 'LVendorAmount',
								'value'	 => function ($data) {
									echo $GLOBALS['LVendorAmount'];
									unset($GLOBALS['LVendorAmount']);
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Last Vendor Amount'),
							array('name'	 => 'FVendorAmount',
								'value'	 => function ($data) {
									echo $GLOBALS['FVendorAmount'];
									unset($GLOBALS['FVendorAmount']);
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'First Vendor Amount'),
							array('name'	 => 'LVendorID',
								'value'	 => function ($data) {
									echo CHtml::link($GLOBALS['LVendorID'], Yii::app()->createUrl("admin/vendor/view/", ["id" => $GLOBALS['LVendorID']]), ["target" => "_blank"]);
									unset($GLOBALS['LVendorID']);
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Last Vendor Id'),
							array('name'	 => 'FVendorID',
								'value'	 => function ($data) {
									echo CHtml::link($GLOBALS['FVendorID'], Yii::app()->createUrl("admin/vendor/view/", ["id" => $GLOBALS['FVendorID']]), ["target" => "_blank"]);
									unset($GLOBALS['FVendorID']);
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'First Vendor Id'),
							array('name'	 => 'dzpp_surge',
								'value'	 => function ($data) {
									echo $data['dzpp_surge'] != null ? $data['dzpp_surge'] : 0;
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'DZPP Surge'),
					)));
				}
				?> 
			</div>  

		</div>  
	</div>
</div>
<script type="text/javascript">




    var start = '<?= date('d/m/Y'); ?>';
    var end = '<?= date('d/m/Y'); ?>';
    function setFilter(obj)
    {
        $('#export_filter1').val(obj.value);
    }
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
                    'Last 15 Days': [moment().subtract(15, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                }
            }, function (start1, end1) {
        $('#Booking_bkg_create_date1').val(start1.format('YYYY-MM-DD'));
        $('#Booking_bkg_create_date2').val(end1.format('YYYY-MM-DD'));
        $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#bkgCreateDate span').html('Select Create Date Range');
        $('#Booking_bkg_create_date1').val('');
        $('#Booking_bkg_create_date2').val('');
    });




    $sourceList = null;
    function populateVendor(obj, vndId) {
        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allvendorbyquery', ['onlyActive' => 0, 'vnd' => ''])) ?>' + vndId,
                    dataType: 'json',
                    data: {},
                    //  async: false,
                    success: function (results) {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(vndId);
                    },
                    error: function () {
                        callback();
                    }
                });
            } else {
                obj.enable();
                callback($sourceList);
                obj.setValue(vndId);
            }
        });
    }
    function loadVendor(query, callback) {
        //	if (!query.length) return callback();
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allvendorbyquery')) ?>?onlyActive=0&q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            global: false,
            error: function () {
                callback();
            },
            success: function (res) {
                callback(res);
            }
        });
    }
    function populatePartner(obj, agtId) {
        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allpartnerbyquery', ['onlyActive' => 0, 'agt' => ''])) ?>' + agtId,
                    dataType: 'json',
                    data: {},
                    //  async: false,
                    success: function (results) {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(agtId);
                    },
                    error: function () {
                        callback();
                    }
                });
            } else {
                obj.enable();
                callback($sourceList);
                obj.setValue(agtId);
            }
        });
    }
    function loadPartner(query, callback) {
        //	if (!query.length) return callback();
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allpartnerbyquery')) ?>?onlyActive=0&q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            global: false,
            error: function () {
                callback();
            },
            success: function (res) {
                callback(res);
            }
        });
    }



</script>
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

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row"> 
					<?php
					$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'booking-form',
						'enableClientValidation' => true,
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
						'htmlOptions'			 => array('onsubmit' => 'return checkDateDiff()',),
					));
					/* @var $form TbActiveForm */
					?>

                    <div class="col-xs-12 col-sm-4 col-md-3">
						<?=
						$form->datePickerGroup($model, 'bkg_create_date1', array('label'			 => 'From Date',
							'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy', 'required' => 'true'), 'htmlOptions' => array('placeholder' => 'From Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
						?>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3">
						<?= $form->datePickerGroup($model, 'bkg_create_date2', array('label' => 'To Date', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'To Date')), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>  
                    </div>

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
//                                'htmlOptions' => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'From')
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
								'model'			 => $trailModel,
								'attribute'		 => 'bkg_platform',
								'val'			 => $trailModel->bkg_platform,
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
                            <label class="control-label">Channel partners</label>
							<?php
                            $dataagents = Agents::model()->getAgentsFromBooking();
                            $this->widget('booster.widgets.TbSelect2', array(
                                'model' => $model,
                                'attribute' => 'bkg_agent_id',
                                'val' => $model->bkg_agent_id,
                                'asDropDownList' => FALSE,
                                'options' => array('data' => new CJavaScriptExpression($dataagents), 'allowClear' => true),
                                'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Partner name')
                            ));
//							$this->widget('ext.yii-selectize.YiiSelectize', array(
//								'model'				 => $model,
//								'attribute'			 => 'bkg_agent_id',
//								'useWithBootstrap'	 => true,
//								"placeholder"		 => "Partner Name",
//								'fullWidth'			 => false,
//								'htmlOptions'		 => array('width' => '100%'),
//								'defaultOptions'	 => $selectizeOptions + array(
//							'onInitialize'	 => "js:function(){
//                                  populatePartner(this, '{$model->bkg_agent_id}');
//                                }",
//							'load'			 => "js:function(query, callback){
//                                loadPartner(query, callback);
//                                }",
//							'render'		 => "js:{
//                                option: function(item, escape){
//                                return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
//                                },
//                                option_create: function(data, escape){
//                                return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
//                                }
//                                }",
//								),
//							));
							?>
                        </div> 
                    </div>
					<div class="col-xs-12 col-sm-4 col-md-2">
						<div class="form-group">
							<label class="control-label">Booking Type</label>

							<?php
							$bookingTypesArr	 = $model->booking_type;
							$bookingTypesArr[2]	 = 'Round Trip';
							$bookingTypesArr[3]	 = 'Multi City';
							$bookingTypesArr[12] = 'Airport Package';
							asort($bookingTypesArr);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkgtypes',
								'val'			 => $model->bkgtypes,
								'data'			 => $bookingTypesArr,
								'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
									'placeholder'	 => 'Booking Type')
							));
							?>
						</div>
					</div>
                    <div class="col-xs-12 col-sm-4 col-md-2">
						<div class="form-group">
							<label class="control-label">Service Tier</label>
							<?php
							$returnType			 = "filter";
							$serviceClassList	 = ServiceClass::model()->getList($returnType);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkg_service_class',
								'val'			 => $model->bkg_service_class,
								'data'			 => $serviceClassList,
								'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
									'placeholder'	 => 'Select Service Class')
							));
							?>
						</div>
					</div>
                    <div  class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?>

<!--                        <input class = 'btn btn-primary full-width' id="submitnow" name="submit" type="button" value="Submit">-->

                    </div>
					<?php $this->endWidget(); ?>
                </div>


                <div class="row" style="margin-top: 10px">  <div class="col-xs-12 col-sm-7 col-md-5">       
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
								$bookingStatus		 = Booking::model()->getBookingStatus();
								$countReport		 = $reportData['status'];
								$genuineAmount		 = 0;
								$genuineCount		 = 0;
								$totalAmount		 = 0;
								$totalCount			 = 0;
								foreach ($countReport as $value)
								{
									$totalAmount += $value['sum'];
									$totalCount	 += $value['count'];
									if (in_array($value['bkg_status'], [2, 3, 5, 6, 7]))
									{
										$genuineAmount	 += $value['sum'];
										$genuineCount	 += $value['count'];
									}
									?>
									<tr>
										<td><?= $bookingStatus[$value['bkg_status']] ?></td>
										<td><?= $value['count'] ?></td>
										<td><?= $value['sum'] ?></td>
									</tr>

									<?php
								}
								?>
                                <tr><td style="border-top : 1px solid grey;font-style: italic;">Total</td><td style="border-top : 1px solid grey;"><?= $totalCount ?></td><td style="border-top : 1px solid grey;"><?= $totalAmount ?></td></tr>
                                <tr><td style="border-top : 1px solid grey;font-style: italic;">Genuine Total</td><td style="border-top : 1px solid grey;"><?= $genuineCount ?></td><td style="border-top : 1px solid grey;"><?= $genuineAmount ?></td></tr>

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
					echo CHtml::beginForm(Yii::app()->createUrl('report/financial/pickup'), "post", ['style' => "margin-bottom: 10px;margin-top: 10px;"]);
					?>

					<input type="hidden" id="export2" name="export2" value="true"/>
					<input type="hidden" id="export_from2" name="export_from2" value="<?= $model->bkg_create_date1 ?>"/>
					<input type="hidden" id="export_to2" name="export_to2" value="<?= $model->bkg_create_date2 ?>"/>
					<input type="hidden" id="export_from_city2" name="export_from_city2" value="<?= $model->bkg_from_city_id ?>"/>
					<input type="hidden" id="export_to_city2" name="export_to_city2" value="<?= $model->bkg_to_city_id ?>"/>
					<input type="hidden" id="export_vendor2" name="export_vendor2" value="<?= $model->bcb_vendor_id ?>"/>
					<input type="hidden" id="export_platform2" name="export_platform2" value="<?= $trailModel->bkg_platform ?>"/>
					<input type="hidden" id="export_agent2" name="export_agent2" value="<?= $model->bkg_agent_id ?>"/>
					<input type="hidden" id="export_status" name="export_status" value="<?= $model->bkg_status ?>"/>
					<input type="hidden" id="export_booking_type" name="export_booking_type[]" value="<?= implode(",", $model->bkgtypes) ?>"/>
                    <input type="hidden" id="export_bkg_service_class" name="export_bkg_service_class[]" value="<?= implode(",", $model->bkg_service_class) ?>"/>
					<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>
					<?php
					echo CHtml::endForm();
				}

				if (!empty($dataProvider))
				{
					$checkContactAccess						 = Yii::app()->user->checkAccess("bookingContactAccess");
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
							array('name'				 => 'bkg_booking_id', 'value'				 => '$data[bkg_booking_id]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Booking ID'),
							array('name'	 => 'invoice_id', 'value'	 => function ($data) {
									if ($data['bkg_status'] == 6 || $data['bkg_status'] == 7)
									{
										echo BookingInvoice::getInvoiceId($data['bkg_id'], $data['bkg_pickup_date']);
									}
								}, 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Invoice ID'),
							array('name'				 => 'book_type', 'value'				 => '$data[book_type]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Type'),
							array('name'				 => 'bkg_status', 'value'				 => 'Booking::model()->getBookingStatus($data[bkg_status])',
								'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Status'),
							array('name'				 => 'bkg_user_name', 'value'				 => '$data[bkg_user_fname]." ".$data[bkg_user_lname]',
								'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'User Name'),
							array('name'				 => 'fromCity', 'value'				 => '$data[fromCity]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'From City'),
							array('name'				 => 'toCity', 'value'				 => '$data[toCity]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'To City'),
							array('name'				 => 'sourceZone', 'value'				 => '$data[sourceZone]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Source Zone'),
							array('name'				 => 'destinationZone', 'value'				 => '$data[destinationZone]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Destination Zone'),
							array('name'				 => 'region', 'value'				 => '$data[region]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Region'),
							array('name'	 => 'Cancellation Policy', 'value'	 => function ($data) {
									echo CancellationPolicyDetails::model()->findByPk($data[bkg_cancel_rule_id])->cnp_code; //CancellationPolicy::getPolicyType($data[bkg_cancel_rule_id], $data['bkg_agent_id']);
								}, 'sortable'								 => false,
								'headerHtmlOptions'						 => array('class' => 'col-xs-2'), 'header'								 => 'Cancelation Policy'),
							array('name'				 => 'vendor_name', 'value'				 => '$data[vendor_name]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Vendor Name'),
							array('name'				 => 'drv_name', 'value'				 => '$data[drv_name]', 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' =>
									'col-xs-2'), 'header'			 => 'Driver Name'),
							array('name'				 => 'vhc_number', 'value'				 => '$data[vhc_number]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Cab Number'),
							array('name'				 => 'vht_model', 'value'				 => '$data[vht_model]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Cab Model'),
							array('name'				 => 'desired_cab', 'value'				 => '$data[serviceClass]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Cab Type'),
                           array('name'				 => 'bkg_service_class', 'value'				 => '$data[serviceTire]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Service Tire'),
							array('name'				 => 'agent_name', 'value'				 => '$data[agent_name]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Partner Name'),
							array('name'				 => 'bkg_agent_ref_code', 'value'				 => function ($data) {echo $referenceCode = $data['bkg_agent_ref_code'];
										if($data['bkg_agent_id'] == Config::get('transferz.partner.id') && is_numeric($data['bkg_agent_ref_code']))
										{
											$partnerCode = TransferzOffers::getOffer($data['bkg_agent_ref_code']);
											echo $referenceCode = ($partnerCode && isset($partnerCode['trb_trz_journey_code'])) ? $partnerCode['trb_trz_journey_code'] : $referenceCode; 
										}}, 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Partner Ref Code'),
							array('name'				 => 'bkg_create_date', 'value'				 => 'date("d-m-Y H:i:s",strtotime($data[bkg_create_date]))',
								'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Booking Date/Time'),
							array('name'				 => 'bkg_pickup_date', 'value'				 => 'date("d-m-Y H:i:s",strtotime($data[bkg_pickup_date]))',
								'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Pickup Date/Time'),
							array('name'	 => 'bkg_return_date',
								'value'	 => function ($data) {
									$value = $data["bkg_booking_type"];

									if ($value == 1)
									{
										$valueType = "";
									}
									else
									{
										$valueType = date("d-m-Y H:i:s", strtotime($data["bkg_return_date"]));
									}
									return $valueType;
								},
								'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Return Date/Time'),
							array('name'	 => 'cancellation_datetime', 'value'	 => function ($data) {
									$can_datetime = ($data['bkg_status'] == 9) ? date("d-m-Y H:i:s", strtotime($data['cancellation_datetime'])) : '';
									echo $can_datetime;
								},
								'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
								'header'			 => 'Cancellation Date/Time'),
							array('name'				 => 'cancellation_reason', 'value'				 => '$data[cnr_reason]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Cancellation Reason'),
							array('name'				 => 'cancellation_remarks', 'value'				 => '$data[cancel_remarks]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Cancellation Remarks'),
							array('name'				 => 'bkg_pickup_address', 'value'				 => '$data[bkg_pickup_address]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Pickup Address'),
							array('name'				 => 'bkg_drop_address', 'value'				 => '$data[bkg_drop_address]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Drop Off Address'),
							array('name'				 => 'bkg_amount', 'value'				 => '$data[bkg_total_amount]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Amount'),
							array('name'				 => 'bkg_base_amount', 'value'				 => '$data[bkg_base_amount]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Base Fare'),
							array('name'				 => 'bkg_discount_amount', 'value'				 => '$data[bkg_discount_amount]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Discount'),
							array('name'				 => 'bkg_extra_discount_amount', 'value'				 => '$data[bkg_extra_discount_amount]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'One-Time Adjustment'),
							array('name'				 => 'bkg_vendor_amount', 'value'				 => '$data[bkg_vendor_amount]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Vendor Amount'),
							array('name'				 => 'bcb_vendor_amount', 'value'				 => '$data[bcb_vendor_amount]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Trip Vendor Amount'),
							array('name'				 => 'drv_allowance', 'value'				 => '$data[drv_allowance]', 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' =>
									'col-xs-2'), 'header'			 => 'Driver Allowance'),
							array('name'				 => 'bkg_toll_tax', 'value'				 => '$data[bkg_toll_tax]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Toll Taxes'),
							array('name'				 => 'bkg_extra_toll_tax', 'value'				 => '$data[bkg_extra_toll_tax]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Extra Toll Taxes'),
							array('name'				 => 'total_toll_tax', 'value'				 => '$data[total_toll_tax]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Total Toll Taxes'),
							array('name'				 => 'bkg_state_tax', 'value'				 => '$data[bkg_state_tax]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'State Taxes'),
							array('name'				 => 'bkg_extra_state_tax', 'value'				 => '$data[bkg_extra_state_tax]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Extra State Taxes'),
							array('name'				 => 'total_state_tax', 'value'				 => '$data[total_state_tax]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Total State Taxes'),
							array('name'				 => 'bkg_convenience_charge', 'value'				 => '$data[bkg_convenience_charge]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Convenience Charges'),
							array('name'				 => 'bkg_parking_charges', 'value'				 => '$data[bkg_parking_charge]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Parking Charge'),
							array('name'				 => 'bkg_additional_charge', 'value'				 => '$data[bkg_additional_charge]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Additional Charges'),
							array('name'				 => 'bkg_extra_km_charge', 'value'				 => '$data[bkg_extra_km_charge]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Extra KM Charge'),
							array('name'	 => 'bkg_extra_km',
								'value'	 => function ($data) {
									echo $data['bkg_extra_km'] != null ? $data['bkg_extra_km'] : 0;
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Extra KM'),
							array('name'				 => 'bkg_extra_per_min_charge', 'value'				 => '$data[bkg_extra_per_min_charge]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Extra Minutes Charge'),
							array('name'	 => 'bkg_extra_min',
								'value'	 => function ($data) {
									echo $data['bkg_extra_min'] != null ? $data['bkg_extra_min'] : 0;
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Extra Minutes'),
							array('name'				 => 'bkg_service_tax', 'value'				 => '$data[bkg_service_tax]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'GST'),
							array('name'				 => 'bkg_airport_entry_fee', 'value'				 => '$data[bkg_airport_entry_fee]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Airport Entry Fee'),
							array('name'				 => 'bkg_advance_amount', 'value'				 => '$data[bkg_advance_amount]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Advance Received'),
							array('name'				 => 'bkg_vendor_collected', 'value'				 => '$data[bkg_vendor_collected]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Driver Collected'),
							array('name'				 => 'bkg_refund_amount', 'value'				 => '$data[bkg_refund_amount]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Refund'),
							array('name'				 => 'bkg_credits_used', 'value'				 => '$data[bkg_credits_used]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Credit Applied'),
							array('name'	 => 'bkg_net_advance_amount', 'filter' => FALSE, 'value'	 => function ($data) {
									if ($data['bkg_status'] == 9)
									{
										echo $data['cancelCharge'];
									}
									else
									{
										echo '-';
									}
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Cancel Charge'),
							array('name'				 => 'bkg_due_amount', 'value'				 => '$data[bkg_due_amount]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Amount Due'),
							array('name'				 => 'bkg_corporate_credit', 'value'				 => '$data[adtPartnerWallet]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Partner Wallet'),
							array('name'				 => 'bkg_partner_payable', 'value'				 => '$data[partnerPayableAmount]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Partner Payable'),
							array('name'	 => 'bkg_partner_commission', 'value'	 =>
								function ($data) {
//                                                                if ($data['bkg_status'] == 9 && $data['bkg_agent_id'] > 0)
//                                                                {
//                                                                        $bookingModel	 = Booking::model()->findByPk($data['bkg_id']);
//                                                                        $cancelCharge	 = $bookingModel->bkgInvoice->bkg_advance_amount - $bookingModel->bkgInvoice->bkg_refund_amount;
//                                                                        $ruleId			 = $bookingModel->bkgAgent->agt_cancel_rule;
//                                                                        $commission		 = BookingPref::model()->calculateCancelCommission($ruleId, $cancelCharge, $bookingModel);
//                                                                        echo $commission;
//                                                                }
//                                                                else
//                                                                {
//                                                                        echo $data['bkg_partner_commission'];
//                                                                }
									if ($data['adtCommission'] < 0)
									{
										echo ($data['adtCommission'] * -1);
									}
									else
									{
										echo $data['bkg_partner_commission'];
									}
								},
								'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Partner Commission'),
							array('name'				 => 'bkg_partner_extra_commission', 'value'				 => '$data[bkg_partner_extra_commission]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Partner Extra Commission'),
							array('name'				 => 'bkg_info_source', 'value'				 => '$data[bkg_info_source]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Source'),
							array('name'				 => 'bkg_platform', 'value'				 => 'BookingTrail::model()->getPlatform($data[bkg_platform])', 'sortable'			 => true,
								'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Platform'),
							array('name'	 => 'payment_mode',
								'value'	 => function ($data) {
									$paymentMode = AccountTransDetails::model()->getPaymentModeByBkgId($data['bkg_id']);

									echo $paymentMode;
								},
								'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Payment Mode'),
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

    function checkDateDiff()
    {

        var date1 = $('#Booking_bkg_create_date1').val();
        var date2 = $('#Booking_bkg_create_date2').val();

        var mydate1 = moment(date1, 'DD/MM/YYYY');
        var mydate2 = moment(date2, 'DD/MM/YYYY');
        //format that date into a different format
        var newDateformate1 = moment(mydate1).format("MM/DD/YYYY");
        var newDateformate2 = moment(mydate2).format("MM/DD/YYYY");
        //alert(newDateformate);
        //outputs 11/15/2000
        var days = daysdifference(newDateformate1, newDateformate2);
        //alert(days);
        if (days > 366)
        {
            alert("Please select date range max 1 year");
            return false;
        }
        return true;
    }
    function daysdifference(firstDate, secondDate)
    {
        var startDay = new Date(firstDate);
        var endDay = new Date(secondDate);
        // alert(startDay);
        var millisBetween = startDay.getTime() - endDay.getTime();
        var days = millisBetween / (1000 * 3600 * 24);
        return Math.round(Math.abs(days));
    }


</script>
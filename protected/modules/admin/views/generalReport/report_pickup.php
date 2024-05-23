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
						<?= $form->textFieldGroup($model, 'trip_id', array('label' => 'Booking Id/Trip Id/Booking Partner Id', 'htmlOptions' => array('placeholder' => 'Search By Booking Id/Trip Id/Booking Partner Id'))) ?>
                    </div>
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
                        <div class="form-group">
                            <label class="control-label">Booking Status</label>
							<?php
							$bookingStatusArr	 = Booking::model()->getBookingStatus();
							unset($bookingStatusArr[1], $bookingStatusArr[15], $bookingStatusArr[13], $bookingStatusArr[8], $bookingStatusArr[4], $bookingStatusArr[10], $bookingStatusArr[11], $bookingStatusArr[12], $bookingStatusArr[13]);
							$datainfo			 = VehicleTypes::model()->getJSON($bookingStatusArr);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkg_status',
								'val'			 => explode(",", $model->bkg_status),
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($datainfo), 'allowClear' => true, 'multiple' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Status', 'multiple' => 'multiple')
							));
							?>
                        </div> 
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-3"> 
                        <div class="form-group cityinput">
                            <label class="control-label">From City</label>
							<?php
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
                            <label class="control-label">To City</label>
							<?php
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
                            <label class="control-label">Channel partners</label>
							<?php
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
					<div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
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
                    <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?>
                    </div>
					<?php $this->endWidget(); ?>
                </div>
				<?php
				$checkExportAccess	 = Yii::app()->user->checkAccess("Export");
				if ($checkExportAccess)
				{
					echo CHtml::beginForm(Yii::app()->createUrl('admin/generalReport/pickup'), "post", ['style' => "margin-bottom: 10px;margin-top: 10px;"]);
					?>

					<input type="hidden" id="export2" name="export2" value="true"/>
					<input type="hidden" id="export_trip_id" name="export_trip_id" value="<?= $model->trip_id ?>"/>
					<input type="hidden" id="export_from2" name="export_from2" value="<?= $model->bkg_create_date1 ?>"/>
					<input type="hidden" id="export_to2" name="export_to2" value="<?= $model->bkg_create_date2 ?>"/>
					<input type="hidden" id="export_from_city2" name="export_from_city2" value="<?= $model->bkg_from_city_id ?>"/>
					<input type="hidden" id="export_to_city2" name="export_to_city2" value="<?= $model->bkg_to_city_id ?>"/>
					<input type="hidden" id="export_vendor2" name="export_vendor2" value="<?= $model->bcb_vendor_id ?>"/>
					<input type="hidden" id="export_platform2" name="export_platform2" value="<?= $trailModel->bkg_platform ?>"/>
					<input type="hidden" id="export_agent2" name="export_agent2" value="<?= $model->bkg_agent_id ?>"/>
					<input type="hidden" id="export_status" name="export_status" value="<?= $model->bkg_status ?>"/>
					<input type="hidden" id="export_booking_type" name="export_booking_type[]" value="<?= implode(",", $model->bkgtypes) ?>"/>
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
							array('name'	 => 'bkg_booking_id', 'filter' => FALSE, 'value'	 => function ($data) {
									$referenceCode = $data['bkg_agent_ref_code'];
									if($data['bkg_agent_id'] == Config::get('transferz.partner.id') && is_numeric($data['bkg_agent_ref_code']))
									{
										$partnerCode = TransferzOffers::getOffer($data['bkg_agent_ref_code']);
										$referenceCode = ($partnerCode && isset($partnerCode['trb_trz_journey_code'])) ? $partnerCode['trb_trz_journey_code'] : $referenceCode; 
									}
									echo $data['bkg_booking_id'] . " <br />" . $referenceCode . " <br />" . $data['agent_name'];
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Booking ID /<br /> Partner Booking Id /<br /> Partner Name'),
							array('name'	 => 'bkg_booking_type', 'filter' => FALSE, 'value'	 => function ($data) {
									echo Booking::model()->getBookingType($data["bkg_booking_type"]) . " <br />" .  $data['serviceClass'] . " <br /> KM: " . $data['bkg_trip_distance'];
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Booking Type /<br /> cab Type /<br/>Total Distance'),
							array('name'				 => 'bkg_status', 'value'				 => 'Booking::model()->getBookingStatus($data[bkg_status])',
								'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Status'),
							array('name'				 => 'bkg_create_date', 'value'				 => 'date("d-m-Y H:i:s",strtotime($data[bkg_create_date]))',
								'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Booking Date/Time'),
							array('name'				 => 'bkg_pickup_date', 'value'				 => 'date("d-m-Y H:i:s",strtotime($data[bkg_pickup_date]))',
								'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Pickup Date/Time'),
							array('name'				 => 'bkg_total_amount', 'value'				 => '$data[bkg_total_amount]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Amount'),
							array('name'				 => 'bkg_base_amount', 'value'				 => '$data[bkg_base_amount]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Base Fare'),
							array('name'				 => 'bkg_discount_amount', 'value'				 => '$data[bkg_discount_amount]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Discount'),
							array('name'				 => 'bkg_extra_discount_amount', 'value'				 => '$data[bkg_extra_discount_amount]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Extra Discount Amount'),
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
							array('name'				 => 'drv_allowance', 'value'				 => '$data[drv_allowance]', 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' =>
									'col-xs-2'), 'header'			 => 'Driver Allowance'),
							array('name'				 => 'bkg_convenience_charge', 'value'				 => '$data[bkg_convenience_charge]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Convenience Charges'),
							array('name'				 => 'bkg_parking_charges', 'value'				 => '$data[bkg_parking_charge]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Parking Charges'),
							array('name'				 => 'bkg_additional_charge', 'value'				 => '$data[bkg_additional_charge]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Additional Charges'),
							array('name'				 => 'bkg_airport_entry_fee', 'value'				 => '$data[bkg_airport_entry_fee]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Airport Entry Fee'),
							array('name'				 => 'bkg_extra_km_charge', 'value'				 => '$data[bkg_extra_km_charge]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Extra KM Charges'),
							array('name'	 => 'bkg_extra_km',
								'value'	 => function ($data) {
									echo $data['bkg_extra_km'] != null ? $data['bkg_extra_km'] : 0;
								}, 'sortable'								 => true, 'headerHtmlOptions'						 => array('class' => 'col-xs-2'), 'header'								 => 'Extra KM'),
							array('name'				 => 'bkg_extra_total_min_charge', 'value'				 => '$data[bkg_extra_total_min_charge]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Extra Minutes Charges'),
							array('name'	 => 'bkg_extra_min',
								'value'	 => function ($data) {
									echo $data['bkg_extra_min'] != null ? $data['bkg_extra_min'] : 0;
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Extra Minutes'),
							array('name'				 => 'bkg_service_tax', 'value'				 => '$data[bkg_service_tax]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'GST'),
							array('name'				 => 'bkg_advance_amount', 'value'				 => '$data[bkg_advance_amount]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Advance Received'),
							array('name'				 => 'bkg_credits_used', 'value'				 => '$data[bkg_credits_used]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Credit Applied'),
							array('name'				 => 'bkg_due_amount', 'value'				 => '$data[bkg_due_amount]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Amount Due'),
							array('name'				 => 'bkg_vendor_collected', 'value'				 => '$data[bkg_vendor_collected]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Driver Collected'),
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
							array('name'				 => 'bkg_refund_amount', 'value'				 => '$data[bkg_refund_amount]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Refund'),
							array('name'	 => 'bkg_cancel_rule_id', 'filter' => FALSE, 'value'	 => function ($data) {
									echo CancellationPolicyDetails::model()->findByPk($data['bkg_cancel_rule_id'])->cnp_code;
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Cancellation Policy Type'),
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
							array('name'				 => 'adtPartnerWallet', 'value'				 => '$data[adtPartnerWallet]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Partner Wallet'),
							array('name'				 => 'bkg_partner_payable', 'value'				 => '$data[partnerPayableAmount]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Partner Payable'),
							array('name'	 => 'bkg_partner_commission', 'value'	 =>
								function ($data) {
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
							array('name'				 => 'bkg_user_name', 'value'				 => '$data[bkg_user_fname]." ".$data[bkg_user_lname]',
								'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Consumer Name'),
							array('name'				 => 'bcb_vendor_amount', 'value'				 => '$data[bcb_vendor_amount]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Trip Vendor Amount'),
							array('name'				 => 'fromCity', 'value'				 => '$data[fromCity]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'From City'),
							array('name'				 => 'toCity', 'value'				 => '$data[toCity]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'To City'),
							array('name'	 => 'bkg_arrived_for_pickup', 'filter' => FALSE, 'value'	 => function ($data) {
									echo $data['bkg_arrived_for_pickup'] == 1 ? date("d-m-Y H:i:s", strtotime($data["bkg_trip_arrive_time"])) : '-';
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Driver Arrived'),
							array('name'	 => 'bkg_ride_start', 'filter' => FALSE, 'value'	 => function ($data) {
									echo $data['bkg_ride_start'] == 1 ? date("d-m-Y H:i:s", strtotime($data["bkg_trip_start_time"])) : '-';
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Trip Started'),
							array('name'	 => 'bkg_ride_complete', 'filter' => FALSE, 'value'	 => function ($data) {
									echo $data['bkg_ride_complete'] == 1 ? date("d-m-Y H:i:s", strtotime($data["bkg_trip_end_time"])) : '-';
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Trip Stop'),
							array('name'	 => 'bkg_is_no_show', 'filter' => FALSE, 'value'	 => function ($data) {
									echo $data['bkg_is_no_show'] == 1 ? date("d-m-Y H:i:s", strtotime($data["bkg_no_show_time"])) : '-';
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Customer No Show'),
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
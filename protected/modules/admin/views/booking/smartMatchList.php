<div id="content" class="mt0 " style="width: 100%!important">
    <div class="row mb50">
        <div id="userView1">
            <div class=" col-xs-12">
                <div class="projects">
                    <div class="panel panel-default">
                        <div class="panel-body">
							<?php
								$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
									'id'					 => 'vendorCollectionList', 'enableClientValidation' => true,
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
                            <div class="row">
                                <div class="col-xs-12">
                                    <table>
                                        <tr>
                                            <th class="pb0" style="font-size: 16px"><u>Status</u></th>
                                        </tr>
                                        <tr>
                                            <td><b>N</b> &nbsp;&nbsp;&nbsp;NEW</td>
                                        </tr>
                                        <tr>
                                            <td><b>A</b> &nbsp;&nbsp;&nbsp;ASSIGNED</td>
                                        </tr>
                                        <tr>
                                            <td><b>O</b> &nbsp;&nbsp;&nbsp;ALLOCATED</td>
                                        </tr>
                                    </table>
                                </div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-sm-4 col-md-3"> 
									<div class="form-group">
										<label class="control-label" style="margin-left:5px;">Search By Matched Trip Id</label>
										<?= $form->textFieldGroup($model, 'matchedTripId', array('label' => '', 'widgetOptions' => ['htmlOptions' => [array('placeholder' => 'Matched Trip Id')]])) ?>
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-3"> 
									<div class="form-group">
										<label class="control-label" style="margin-left:5px;">Search By Up Booking Id</label>
										<?= $form->textFieldGroup($model, 'up_bkg_booking_id', array('label' => '', 'widgetOptions' => ['htmlOptions' => [array('placeholder' => 'Operator Name')]])) ?>
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-3"> 
									<div class="form-group">
										<label class="control-label" style="margin-left:5px;">Search By Down Booking Id</label>
										<?= $form->textFieldGroup($model, 'down_bkg_booking_id', array('label' => '', 'widgetOptions' => ['htmlOptions' => [array('placeholder' => 'Operator Name')]])) ?>
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-3" style="padding-top: 20px;"> 
									<button class="btn btn-primary" type="submit" style="width: 185px;"  name="matchSearch">Search</button>
								</div>
							</div>
                            
							<?php $this->endWidget(); ?>
                        </div>
                    </div>
                    <a class="btn btn-sm btn-info" title="Smart Match" href="/aaohome/booking/createTrip">Create Trip</a>
                    <div class="panel panel-default">
                        <div class="panel-body" >
							<?php
							if (!empty($dataProvider))
							{
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'match-booking-grid',
									'responsiveTable'	 => true,
									'filter'			 => $model,
									'dataProvider'		 => $dataProvider,
									'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
									'columns'			 => array(
										array('name'	 => 'bsm_bcb_id', 'filter' => false, 'type'	 => 'raw', 'value'	 => function($data) {
												echo $data["bsm_bcb_id"] . "<br>";
											}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Matched Trip id'),
										array('name' => 'up_bkg_bcb_id', 'filter' => false, 'type' => 'raw', 'value' => 'CHtml::link($data["up_bkg_bcb_id"], Yii::app()->createUrl("admin/booking/triprelatedbooking",["tid"=>$data["up_bkg_bcb_id"]]),["class"=>"viewBooking", "onclick"=>"return viewBooking(this)"])', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Up Trip ID'),
										array('name'	 => 'up_bkg_booking_id', 'filter' => false, 'type'	 => 'raw', 'value'	 => function($data) {
												echo CHtml::link($data["up_bkg_booking_id"], Yii::app()->createUrl("admin/booking/view", ["id" => $data["up_bkg_id"]]), ["class" => "viewBooking", "onclick" => "return viewBooking(this)"]) . "<br>";
												if ($data["up_booking_confirm"] == 0)
												{
													$confirm = "RECONFIRM PENDING";
												}
												elseif ($data["up_booking_confirm"] == 1)
												{
													$confirm = "RECONFIRMED";
												}
												if ($data["vnd_name"] != '')
												{
													$vnd_name = explode("-", $data["vnd_name"]);
													echo $vnd_name[0] . '-' . $data["vendor_city"] . '-' . $data["vendor_total_trip"] . '-' . $data["vendor_rating"] . '-' . $confirm;
												}
												else
												{
													echo $confirm;
												}
												
											}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Up Booking ID'),
										array('name'	 => 'bkg1_total_amount', 'filter' => false, 'type'	 => 'raw', 'value'	 => function($data) {
												echo $data["bkg1_total_amount"] . '/' . $data['bkg1_advance_amount'] . "<br>";
											}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Total / Advance'),
										array('name'	 => 'up_bkg_status', 'filter' => false, 'type'	 => 'raw', 'value'	 => function ($data) {
												return Booking::model()->getSmartMatchBookingStatus($data['up_bkg_status']);
											}, 'sortable'			 => false, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Up Status'),
										array('name' => 'down_bkg_bcb_id', 'filter' => false, 'type' => 'raw', 'value' => 'CHtml::link($data["down_bkg_bcb_id"], Yii::app()->createUrl("admin/booking/triprelatedbooking",["tid"=>$data["down_bkg_bcb_id"]]),["class"=>"viewRelatedBooking", "onclick"=>"return viewBooking(this)"])', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Down Trip ID'),
										//array('name' => 'down_bkg_booking_id', 'type' => 'raw', 'value' => 'CHtml::link($data["down_bkg_booking_id"], Yii::app()->createUrl("admin/booking/view",["id"=>$data["down_bkg_id"]]),["class"=>"viewBooking", "onclick"=>"return viewBooking(this)"])', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Down Booking ID'),
										array('name'	 => 'down_bkg_booking_id', 'filter' => false, 'type'	 => 'raw', 'value'	 => function($data) {
												echo CHtml::link($data["down_bkg_booking_id"], Yii::app()->createUrl("admin/booking/view", ["id" => $data["down_bkg_id"]]), ["class" => "viewBooking", "onclick" => "return viewBooking(this)"]) . "<br>";
												if ($data["dn_booking_confirm"] == 0)
												{
													$confirm = "RECONFIRM PENDING";
												}
												elseif ($data["dn_booking_confirm"] == 1)
												{
													$confirm = "RECONFIRMED";
												}
												if ($data["vnd_name"] != '')
												{
													$vnd_name = explode("-", $data["vnd_name"]);
													echo $vnd_name[0] . '-' . $data["vendor_city"] . '-' . $data["vendor_total_trip"] . '-' . $data["vendor_rating"] . '-' . $confirm;
												}
												else
												{
													echo $confirm;
												}
											}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Down Booking ID'),
										array('name'	 => 'bkg2_total_amount', 'filter' => false, 'type'	 => 'raw', 'value'	 => function($data) {
												echo $data["bkg2_total_amount"] . '/' . $data['bkg2_advance_amount'] . "<br>";
											}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Total / Advance'),
										array('name'	 => 'down_bkg_status', 'filter' => false, 'type'	 => 'raw', 'value'	 => function ($data) {
												return Booking::model()->getSmartMatchBookingStatus($data['down_bkg_status']);
											}, 'sortable'			 => false, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Down Status'),
										
													
										array('name'	 => 'up_bkg_from_city_id', 'filter' => false, 'type'	 => 'raw', 'value'	 => function ($data) {
												if ($data['up_bkg_from_city_id'] != '' && $data['up_bkg_to_city_id'] != '')
												{
													return $data['up_bkg_from_city'] . ' to ' . $data['up_bkg_to_city'];
												}
											}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Up Route'),
													
													
										array('name'	 => 'down_bkg_from_city_id', 'filter' => false, 'type'	 => 'raw', 'value'	 => function ($data) {
												if ($data['down_bkg_from_city_id'] != '' && $data['down_bkg_to_city_id'] != '')
												{
													return $data['down_bkg_from_city'] . ' to ' . $data['down_bkg_to_city'];
												}
											}, 'sortable'			 => false, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Down Route'),
													
													
										array('name' => 'up_vht_model', 'filter' => false, 'type' => 'raw', 'value' => '$data["up_vht_make"]', 'sortable' => false, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Up Cab Type'),
										array('name' => 'down_vht_model', 'filter' => false, 'type' => 'raw', 'value' => '$data["down_vht_make"]', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Down Cab Type'),
													
										array('name'	 => 'up_bkg_pickup_date', 'filter' => false,
											'value'	 => function ($data) {
												return DateTimeFormat::DateTimeToLocale($data["up_bkg_pickup_date"]) . "   ( " . Filter::getDurationbyMinute($data["up_booking_duration"]) . " )";
											},
											'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'UP Pickup Date/Time'),
										array('name'	 => 'down_bkg_pickup_date', 'filter' => false,
											'value'	 => function ($data) {
												return DateTimeFormat::DateTimeToLocale($data["down_bkg_pickup_date"]);
											},
											'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'DN Pickup Date/Time'),
										array('name'	 => 'MatchScore', 'type'	 => 'raw', 'value'	 => function($data) {
												//.'<br>'."DS:{$data['DurationScore']} - CS:{$data['same_cab_type']} - SS:{$data['source_matching_dest']} - DS{$data['dest_matching_source']} - SAS:{$data['source_matching_adv_amt']} - DAS:{$data['dest_matching_adv_amt']}";
												return $data['MatchScore']; //
											}, 'filter'			 => false,
											'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Match Score (%)'),
										
										array('name'	 => 'bcbTypeMatched', 'filter' => CHtml::activeCheckBoxList($model, 'bcbTypeMatched', array('0' => 'To Be Match', '1' => 'Matched')),
											'value'	 => function($data) {
												if ($data["bcbTypeMatched"] == 1)
												{
													$bcbType = 'Matched';
												}
												else
												{
													$bcbType = 'To Be Matched';
												}
												return $bcbType;
											},
											'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Status'),
										array(
											'header'			 => 'Action',
											'class'				 => 'CButtonColumn',
											'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center'),
											'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
											'template'			 => '{matchtrip}',
											'buttons'			 => array(
												'matchtrip'		 => array(
													'click'		 => 'function(e){
                                                            $href = $(this).attr(\'href\');
                                                            jQuery.ajax({type: "GET",
                                                                url: $href,
                                                                success: function (data){
                                                                    box = bootbox.dialog({
                                                                        message: data,
                                                                        title: "Match List",
                                                                        size: "large",
                                                                        onEscape: function () {

                                                                            // user pressed escape
                                                                        },
                                                                    });
                                                                }
                                                            });
                                                                    return false;
                                                        }',
													'url'		 => 'Yii::app()->createUrl("admin/booking/matchtrip", array("bsm_id" => $data["bsm_id"]))',
													'imageUrl'	 => false,
													'visible'	 => '$data["bcbTypeMatched"] == 0',
													'label'		 => '<i class="fa fa-check"></i>',
													'options'	 => array('style' => 'margin: 4px', 'data-placement' => 'left', 'class' => 'btn btn-primary btn-sm mb5 mr5', 'title' => 'Match Trip'),
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
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
    });
    function viewBooking(obj) {
        var href2 = $(obj).attr("href");
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Booking Details',
                    size: 'large',
                    onEscape: function () {
                        // user pressed escape
                    },
                });
            }
        });
        return false;
    }


</script>
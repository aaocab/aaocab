<?
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask1.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/booking.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/route.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/city.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/promo.js?v=' . $version);

$selectizeOptions = ['create' => false, 'persist' => true, 'selectOnTab' => true,
    'createOnBlur' => true, 'dropdownParent' => 'body',
    'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField' => 'id',
    'openOnFocus' => true, 'preload' => false,
    'labelField' => 'text', 'valueField' => 'id', 'searchField' => 'text', 'closeAfterSelect' => true,
    'addPrecedence' => false];
?>
<style>
    .pagination{
        margin: 0
    }
    .actBtn img{
        height: 20px;
    }
</style>
<div class="">
  <?php
                            $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                                'id' => 'booking-form', 'enableClientValidation' => true,
                                'clientOptions' => array(
                                    'validateOnSubmit' => true,
                                    'errorCssClass' => 'has-error',
                                ),
                                // Please note: When you enable ajax validation, make sure the corresponding
                                // controller action is handling ajax validation correctly.
                                // See class documentation of CActiveForm for details on this,
                                // you need to use the performAjaxValidation()-method described there.
                                'enableAjaxValidation' => false,
                                'errorMessageCssClass' => 'help-block',
                                'htmlOptions' => array(
                                    'class' => '',
                                ),
                            ));
                            /* @var $form TbActiveForm */
                            ?>
            <div class="container">
				<div class="panel ">    
                <div class="panel-body panel-border panel-info ">      
                    <div class="col-lg-10 col-lg-offset-1 ">
                        <div class="row bordered pt10">
                          
							<div class="row">
                            <div class="col-sm-4 col-md-4">
							<label class="control-label">Search by Booking or Traveler's  information</label>
                                <?= $form->textField($model, 'search', array('label' => "Search by Booking or Traveller's  information", 'placeholder' => 'search by booking id or other information','class'=>'form-control')) ?>

                                <? //= $form->textFieldGroup($model, 'bkg_booking_id', array('htmlOptions' => array())) ?>
                            </div>
                            <div class="col-xs-4 col-md-4">
							<label class="control-label">From City</label>
                                <?php 		
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
								
                    
                            </div>
                            <div class="col-xs-4 col-md-4">
							<label class="control-label">To City</label>
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
								
                            </div>
							</div>
							<br>
                                <div class="row">
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
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Booking Status</label>
                                    <?php
                                    $arrJSON1 = array();
                        $arr1 = ['1' => 'Confirmed', '2' => 'Completed', '3' => 'Cancelled'];
                        foreach ($arr1 as $key => $val) {
                            $arrJSON1[] = array("id" => $key, "text" => $val);
                        }
                        $statuslist = CJSON::encode($arrJSON1);
                        $this->widget('booster.widgets.TbSelect2', array(
                            'model' => $model,
                            'attribute' => 'bkg_status',
                            'val' => $model->bkg_status,
                            'asDropDownList' => FALSE,
                            'options' => array('data' => new CJavaScriptExpression($statuslist), 'allowClear' => true),
                            'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Status'),
                        ));
                                    ?>
                                </div>
                            </div>
                                </div> 
                            <div class="col-xs-12 text-center ">  
                                <button class="btn btn-warning mt5" type="reset" style="width: 140px;"  name="reset" id="btnreset">Clear</button>
                                <button class="btn btn-primary mt5" type="submit" style="width: 140px;"  name="bookingSearch">Search</button>
                            </div>
 <?php $this->endWidget(); ?>
                        </div>
                    </div>


                </div> 
            </div>
			</div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						<div class="row">
							<div class="col-xs-6"> 
								<a href="<?= Yii::app()->createUrl('agent/booking/createquote') ?>"><div class="btn btn-info"><i class="fa fa-plus"></i> New Booking</div></a>
							</div>
							<div class="col-xs-6 text-right"> 
								<?= CHtml::beginForm(Yii::app()->createUrl('agent/booking/list'), "post", ['style' => "margin-bottom: 10px;"]); ?>
								<input type="hidden" id="export" name="export" value="true"/>
								<input type="hidden" id="export_from_city" name="export_from_city" value="<?= $model->bkg_from_city_id ?>"/>
								<input type="hidden" id="export_to_city" name="export_to_city" value="<?= $model->bkg_to_city_id ?>"/>
								<input type="hidden" id="export_search" name="export_search" value="<?= $model->search ?>"/>
								<input type="hidden" id="export_status" name="export_status" value="<?= $model->bkg_status ?>"/>
								<input type="hidden" id="export_from_date" name="export_from_date" value="<?= $model->bkg_pickup_date1 ?>"/>
								<input type="hidden" id="export_to_date" name="export_to_date" value="<?= $model->bkg_pickup_date2 ?>"/>
								<input type="hidden" id="create_from_date" name="create_from_date" value="<?= $model->bkg_create_date1 ?>"/>
								<input type="hidden" id="create_to_date" name="create_to_date" value="<?= $model->bkg_create_date2 ?>"/>
								<button class="btn btn-default" type="submit" style="width: 185px;">Export Table</button>
								<?= CHtml::endForm() ?>
							</div>
						</div>
					</div>
					<div class="col-md-12 mt10">
						<?php
						if (!empty($dataProvider)) {
						$display = ($agentId == Config::get('spicejet.partner.id')) ? true : false;
						$params = array_filter($_REQUEST);
						$dataProvider->getPagination()->params = $params;
						$dataProvider->getSort()->params = $params;
							$this->widget('booster.widgets.TbGridView', array(
								'id' => 'booking-list2',
								'responsiveTable' => true,
								'dataProvider' => $dataProvider,
								//'ajaxUrl'			 => CHtml::normalizeUrl(Yii::app()->createUrl('admin/account/accountlist', $dataProvider->getPagination()->params)),
								//'filter' => $model,
								'template' => "<div class='panel-heading'><div class='row m0'>
														<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
												</div></div>
												<div class='panel-body'>{items}</div>
												<div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
								'itemsCssClass' => 'table table-striped table-bordered mb0',
								'htmlOptions' => array('class' => 'table-responsive panel panel-primary  compact'),
								'columns' => array(
									['name' => 'bkg_booking_id',
										'type' => 'raw',
										'value' => function($data) {
											if ($data['bkg_booking_id'] != '') 
											{
												if(empty($data["bkg_agent_ref_code"]))
												{
													$displayText = "";
												}
												else
												{
													$displayText = "<br>(". $data["bkg_agent_ref_code"] .")" ;
												}

												echo CHtml::link($data['bkg_booking_id'], Yii::app()->createUrl("agent/booking/view", ["id" => $data['bkg_id']]), ["class" => "viewBooking", "onclick" => "return viewBooking(this)"]);
												echo $displayText;
											}
										},
										'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Booking ID'],
									['name' => 'bkg_user_name', 'value' =>
                                      //  '$data["bkg_user_name"]'
                                      function($data) {
											echo 'Name : ' . $data["bkg_user_name"].'<br />Email : ' . $data["bkg_user_email"].'<br />Phone : ' . $data["bkg_contact_no"];
										}
                                        ,
                                        
                                        'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: left;'), 'header' => 'Name/Email/Phone'],
								//	['name' => 'bkg_user_email', 'value' => '$data["bkg_user_email"]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Email'],
								//	['name' => 'bkg_contact_no', 'value' => '$data["bkg_contact_no"]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Phone'],
									['name' => 'bkg_from_city', 'value' => '$data["fromCities"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'From City'],
									['name' => 'bkg_to_city', 'value' => '$data["toCities"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'To City'],
									['name' => 'bkg_total_amount', 'value' => function($data) {
											echo '<i class="fa fa-inr"></i>' . round($data['bkg_total_amount']);
										}, 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Amount'],
									['name' => 'bkg_corporate_credit', 'value' => function($data) {
											if ($data['bkg_corporate_credit'] != 0) {
												echo '<nobr><i class="fa fa-inr"></i>' . round($data['bkg_corporate_credit']) . '</nobr';
											} else {
												echo '<i class="fa fa-inr"></i>' . 0;
											}
										}, 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Partner Credit'],
									['name' => 'bkg_partner_commission',
										'value' => function($data) {
											if ($data['bkg_booking_id']) {
												echo '<i class="fa fa-inr"></i>' . round($data["bkg_partner_commission"]);
											} else {
												echo "N A";
											}
										}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2 text-center'), 'htmlOptions' => array('style' => 'text-align: center;', 'class' => ''), 'header' => 'Partner Commission'],

									['name' => 'bkg_partner_extra_commission',
										'value' => function($data) {
											if ($data['bkg_booking_id']) {
												echo '<i class="fa fa-inr"></i>' . round($data['bkg_partner_extra_commission']);
											} else {
												echo "N A";
											}
										},
										'visible'            => $display,
										'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2 text-center'), 'htmlOptions' => array('style' => 'text-align: center;', 'class' => ''), 'header' => 'Extra partner Commission'],

									['name' => 'commissionOnGst',
										'value' => function($data) {
											if ($data['bkg_booking_id']) {
												echo '<i class="fa fa-inr"></i>' .$data["commissionGst"];
											} else {
												echo "N A";
											}
										}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2 text-center'), 'htmlOptions' => array('style' => 'text-align: center;', 'class' => ''), 'header' => 'Commission On GST'],

									['name' => 'bkg_advance_amount', 'value' => function($data) {
											if ($data['bkg_advance_amount'] > 0) {
												echo '<i class="fa fa-inr"></i>' . round($data['bkg_advance_amount']);
											} else {
												echo '<i class="fa fa-inr"></i>' . '0';
											}
										}, 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Advance Paid'],
									['name' => 'bkg_create_date',
										'value' => function ($data) {
											echo DateTimeFormat::DateTimeToDatePicker($data['bkg_create_date'])
											. "<br>" . DateTimeFormat::DateTimeToTimePicker($data['bkg_create_date']);
											//echo DateTimeFormat::DateTimeToLocale($data['bkg_create_date']);
										}, 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Booking Date/Time'],
									['name' => 'bkg_pickup_date',
										'value' => function ($data) {
											echo DateTimeFormat::DateTimeToDatePicker($data['bkg_pickup_date'])
											. "<br>" . DateTimeFormat::DateTimeToTimePicker($data['bkg_pickup_date']);
											//echo DateTimeFormat::DateTimeToLocale($data['bkg_pickup_date']);
										},
										'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Pickup Date/Time'],
									['name' => 'bkg_status_name', 'value' => function($data) {
											if (in_array($data["bkg_status"],[2,3,5])) {
												echo 'Confirmed';
											} 
											else if(in_array($data["bkg_status"],[6,7]))
											{
												echo 'Completed';
											}
											else if($data["bkg_status"] == 9)
											{
												echo 'Cancelled';
											}
//											else {
//												echo Booking::model()->getActiveBookingStatus($data['bkg_status']);
//											}
										}, 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array(), 'header' => 'Status'],
									[
										'header' => 'Action',
										'class' => 'CButtonColumn',
										'htmlOptions' => array('style' => 'white-space:nowrap;text-align: center;min-height:30px', 'class' => 'action_box'),
										'headerHtmlOptions' => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px'),
										'template' => '{verify}{cancelbooking}{addcredit}{showinvoice}',
										'buttons' => array(
											'verify' => array(
												'click' => 'function(){
													var con = confirm("Are you sure you want to verify this booking?"); 
													  if(con){
															$href = $(this).attr("href");
															$.ajax({
																url: $href,
																dataType: "json",
																success: function(result){                                                    
																if(result.success){                                                    
																$(\'#booking-list2\').yiiGridView(\'update\');
																}else{
																errorMsg=(result.errors=="")?"Sorry error occured":result.errors;
																  alert(errorMsg);
															   }
														},
														error: function(xhr, status, error){
																alert(\'Sorry error occured\');
														}
													});
													}
													return false;
																}',
												'url' => 'Yii::app()->createUrl("agent/booking/verifybooking", array(\'bkid\' => $data["bkg_id"]))',
												'imageUrl' => Yii::app()->request->baseUrl . '\images\icon\reconfirmed.png',
												'visible' => '(in_array($data["bkg_status"],[1]))? true:false;',
												'htmlOptions' => array('style' => 'height:20px'),
												'options' => array('data-toggle' => 'ajaxModal', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs  ver p0 actBtn', 'title' => 'Verify Booking')
											),
											'addcredit' => array(
												'url' => 'Yii::app()->createUrl("agent/booking/addagentcredit", array(\'booking_id\' => $data["bkg_id"]))',
												'imageUrl' => Yii::app()->request->baseUrl . '\images\icon\add_credits.png',
												'label' => '<i class="fa fa-add"></i>',
												'visible' => '(in_array($data["bkg_status"],[2,3,5]))? true:false;',
												'options' => array('data-toggle' => 'modal', 'rel' => 'popover', 'onclick' => 'return openDialog(this)', 'data-placement' => 'left', 'class' => 'btn btn-xs credit p0 actBtn', 'modaltitle' => 'Add Agent Credit', 'title' => 'Add Partner Credit'),
											),
											'cancelbooking' => array(
												'url' => 'Yii::app()->createUrl("agent/booking/canbooking", array(\'booking_id\' => $data["bkg_id"]))',
												'imageUrl' => Yii::app()->request->baseUrl . '/images/deleteImg.png',
												'label' => '<i class="fa fa-times"></i>',
												'visible' => '(in_array($data["bkg_status"],[1,2,3,5]))? true:false;',
												'options' => array('data-toggle' => 'modal', 'rel' => 'popover', 'onclick' => 'return openDialog(this)', 'data-placement' => 'left', 'class' => 'btn btn-xs cancel p0 actBtn', 'modaltitle' => 'Cancel Booking', 'title' => 'Cancel Booking'),
											),
											 'showinvoice' => array(
												'url' => 'Yii::app()->createUrl("agent/booking/invoiceDownload", array(\'bkgId\' => $data["bkg_id"],  "hash" => Yii::app()->shortHash->hash($data["bkg_id"]),"email" => 1))',
												'imageUrl' => Yii::app()->request->baseUrl . '/images/icon/receipt2.png',
												'label' => 'Download Invoice',
												'visible' => '(in_array($data["bkg_status"],[6,7,9]))? true:false;',
												//'options' => array('data-toggle' => 'modal', 'rel' => 'popover', 'onclick' => 'return openDialog(this)', 'data-placement' => 'left', 'class' => 'btn btn-xs receipt p0 actBtn', 'modaltitle' => 'Invoice', 'title' => 'Invoice'),
											),
											'htmlOptions' => array('class' => 'center'),
										)]
							)));
						}
						?>
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
    function openDialog(obj)
    {
        ajaxindicatorstart("");
        try
        {
            $href = $(obj).attr("href");
            jQuery.ajax({type: "GET", "dataType": "html", url: $href,
                success: function (data)
                {
                    bootbox.dialog({
                        message: data,
                        title: $(obj).attr("modaltitle"),
                    });
                }
            });
        } catch (e) {
            alert(e);
        }
        return false;
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
</script>
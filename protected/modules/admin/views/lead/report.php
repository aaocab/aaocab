<?php
$version				 = Yii::app()->params['siteJSVersion'];
$source					 = BookingTemp::model()->getSourceIndexed();
$datainfo				 = VehicleTypes::model()->getJSON($source);
$followupStatus			 = BookingTemp::model()->getLeadStatusJSON();
$selectizeOptions		 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
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

<?php
$tab					 = ($tab == "") ? "1" : $tab;
${'tabactive' . $tab}	 = 'active ';
?>
<div class="row">
    <div class="col-xs-12">
        <div class="text-right">
        </div>    
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row mb15" style=""> 
					<?php
					$form					 = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
                    <div class="col-xs-12">
                        <div class="row pb10">
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <label class="control-label">Booking Date</label>
								<?php
								$daterang				 = date('F d, Y') . " - " . date('F d, Y');
								$createdate1			 = ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1;
								$createdate2			 = ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2;
								if ($createdate1 != '' && $createdate2 != '')
								{
									$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
								}
								?>
<!--                                         <input class="form-control" type="text" name="daterange" value="" placeholder="select date range"/>-->
                                <div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 8px; border: 1px solid #ccc; width: 100%">
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                    <span><?= $daterang ?></span> <b class="caret"></b>
                                </div>
								<?= $form->hiddenField($model, 'bkg_create_date1', ['value' => ($model->bkg_create_date1 == '') ? '' : DateTimeFormat::DateToDatePicker($model->bkg_create_date1)]); ?>
								<?= $form->hiddenField($model, 'bkg_create_date2', ['value' => ($model->bkg_create_date2 == '') ? '' : DateTimeFormat::DateToDatePicker($model->bkg_create_date2)]); ?>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <label class="control-label">Pickup Date</label>

								<?php
								$daterang			 = "Select Pickup Date Range";
								$bkg_pickup_date1	 = ($model->bkg_pickup_date1 == '') ? '' : $model->bkg_pickup_date1;
								$bkg_pickup_date2	 = ($model->bkg_pickup_date2 == '') ? '' : $model->bkg_pickup_date2;
								if ($bkg_pickup_date1 != '' && $bkg_pickup_date2 != '')
								{
									$daterang = date('F d, Y', strtotime($bkg_pickup_date1)) . " - " . date('F d, Y', strtotime($bkg_pickup_date2));
								}
								?>
                                <div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 8px; border: 1px solid #ccc; width: 100%">
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                    <span><?= $daterang ?></span> <b class="caret"></b>
                                </div>
								<?= $form->hiddenField($model, 'bkg_pickup_date1', ['value' => ($model->bkg_pickup_date1 == '') ? '' : DateTimeFormat::DateToDatePicker($model->bkg_pickup_date1)]); ?>
								<?= $form->hiddenField($model, 'bkg_pickup_date2', ['value' => ($model->bkg_pickup_date2 == '') ? '' : DateTimeFormat::DateToDatePicker($model->bkg_pickup_date2)]); ?>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
								<?=
								$form->datePickerGroup($model, 'bkg_follow_up_reminder_date1', array('label'			 => 'Reminder Date',
									'widgetOptions'	 => array('options' => array('autoclose' => true, 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Reminder Date', 'value' => ($model->bkg_follow_up_reminder_date1 == '') ? '' : DateTimeFormat::DateToDatePicker($model->bkg_follow_up_reminder_date1))), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
								?>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
								<?= $form->textFieldGroup($model, 'bkg_keyword_txt', array('label' => 'Keywords', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Email /Phone number/ Lead ID')))) ?>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <div class="form-group cityinput">
                                    <label class="control-label">From City</label>
									<?php
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $model,
										'attribute'			 => 'bkg_from_city_id_txt',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "From City",
										'fullWidth'			 => false,
										'htmlOptions'		 => array('width' => '100%',
										//  'id' => 'from_city_id1'
										),
										'defaultOptions'	 => $selectizeOptions + array(
									'onInitialize'	 => "js:function(){
                                  populateSource(this, '{$model->bkg_from_city_id_txt}');
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
                                </div></div>
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <div class="form-group cityinput">
                                    <label class="control-label">To City</label>
									<?php
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $model,
										'attribute'			 => 'bkg_to_city_id_txt',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "To City",
										'fullWidth'			 => false,
										'htmlOptions'		 => array('width' => '100%',
										//  'id' => 'from_city_id1'
										),
										'defaultOptions'	 => $selectizeOptions + array(
									'onInitialize'	 => "js:function(){
                                  populateSource(this, '{$model->bkg_to_city_id_txt}');
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
                                </div></div>
                            <div class="col-xs-12 col-sm-6  col-md-3">
                                <label class="control-label">Lead Source</label>
								<?php
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									//    'attribute' => 'bkg_log_type_txt',
									//   'val' => "'" . $model->bkg_log_type_txt . "'",
									'attribute'		 => 'bkg_lead_source_txt',
									'val'			 => $model->bkg_lead_source_txt,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($datainfo), 'allowClear' => true),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Source')
								));
								?>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <label class="control-label">Followup Status</label>
								<?php
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'bkg_follow_up_status_txt',
									'val'			 => "'" . $model->bkg_follow_up_status_txt . "'",
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($followupStatus), 'allowClear' => true),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Follow up status')
								));
								?>

                            </div>
                            <div class="col-xs-12 col-md-4 col-lg-12 pt10 text-center">
								<?php echo CHtml::submitButton('Search', array('class' => 'btn btn-primary pl50 pr50')); ?>
                            </div>
                        </div>
                    </div>
					<?php $this->endWidget(); ?>
                </div>
                <div class="row">
                    <div class="col-xs-12 pull-right pt5">
						<div class="pull-right">

                        </div>
                        <div class="pull-right">
							<?php /*
							<a href="<?= Yii::app()->createUrl("admin/lead/autoAssign") ?>" onclick="return autoAssign(this);" class="btn btn-sm btn-animate-side btn-warning">Auto Assign Lead</a>
							*/
							?>
							<?php // CHtml::link('Unverified Bookings', Yii::app()->createUrl('admin/booking/list/tab/1'), ['class' => "btn btn-sm btn-info", 'title' => "Unverified Bookings"]) ?>
                            <a data-toggle="ajaxModal" id="lead" rel="popover" data-placement="left" class="btn btn-sm btn-primary" title="Follow" href="<?= Yii::app()->createUrl("/admin/lead/leadfollow") ?>">Create Lead</a>
                        </div>
                        <div class="pull-right mr5">
							<?= CHtml::beginForm(Yii::app()->createUrl('admin/lead/report'), "post", []); ?>
                            <input type="hidden" id="export2" name="export2" value="true"/>
                            <input type="hidden" id="export_from2" name="export_from2" value="<?= $model->bkg_create_date1 ?>"/>
                            <input type="hidden" id="export_to2" name="export_to2" value="<?= $model->bkg_create_date2 ?>"/>
                            <input type="hidden" id="from_city" name="from_city" value="<?= $model->bkg_from_city_id_txt ?>"/>
                            <input type="hidden" id="to_city" name="to_city" value="<?= $model->bkg_to_city_id_txt ?>"/>
                            <input type="hidden" id="hid_lead_source" name="hid_lead_source" value="<?= $model->bkg_lead_source ?>"/>                                
                            <input type="hidden" id="hid_follow_up_status" name="hid_follow_up_status" value="<?= $model->bkg_follow_up_status_txt ?>"/>
                            <input type="hidden" id="hid_keyword" name="hid_keyword" value="<?= $model->bkg_keyword_txt ?>"/>
                            <input type="hidden" id="pickup_date1" name="pickup_date1" value="<?= $model->bkg_pickup_date1 ?>"/>
                            <input type="hidden" id="pickup_date2" name="pickup_date2" value="<?= $model->bkg_pickup_date2 ?>"/>
                            <button class="btn btn-default btn-sm pl30 pr30" type="submit" >Export</button>
							<?= CHtml::endForm() ?>
                        </div>
                    </div>
                    <div class="col-xs-12 pt5">
                        <div class="row">
                            <ul class="nav nav-tabs" id="myTab">
								<?php
								$params1 = array_filter($_GET + $_POST);
								/* @var $model BookingTemp */
								/* @var $dataProvider CActiveDataProvider */

								$params			 = array_filter($params1);
								$leadStatusOld	 = ['1'	 => 'Active', '2'	 => 'Inactive', '3'	 => 'Unverified Booking', '4'	 => 'Not Followed Up', '5'	 => 'Todays Pickup', '6'	 => 'Todays Reminder', '7'	 => 'Todays Followed Up'
										//     '3' => 'Unverified'
								];
								$leadStatus		 = ['1'	 => 'Active', '2'	 => 'Inactive', '3'	 => 'Unverified', '4'	 => 'New', '5'	 => 'Pickup In Next 2 Days', '6'	 => 'Todays Reminder', '7'	 => 'Todays Followed Up'
										//     '3' => 'Unverified'
								];

//  $bgcolor = ['warning', 'success', 'info', 'danger'];
								$bgcolor = 'default';

								foreach ($dataProviders as $bid => $dataProvider)
								{
									?>
									<li class='<?= ${"tabactive" . $bid} ?>'><a data-toggle="tab" class="bg-white" href="#sec<?= $bid ?>"><?= $dataProvider['label'] ?> <span class="font-bold" style="font-size: 1.2em">(<?= $label . $dataProvider['count'] ?>)</span></a></li>
									<?
								}
								?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="tab-content p0">
						<?php
						foreach ($dataProviders as $bid => $dataProvider)
						{
							?>
							<div id="<?= 'sec' . $bid ?>" class="tab-pane <?= ${'tabactive' . $bid} ?>">
								<?php
								$this->renderPartial("grid_feedback_report", ['status' => $bid, 'provider' => $dataProvider]);
								$time = Filter::getExecutionTime();

								$GLOBALS['time'][8][$bid] = $time;
								?>
							</div> 
							<?php
						}
						?>
                    </div> 
                </div>  
            </div>  </div>
    </div>
</div>
<script type="text/javascript">
    var csrBox;
    $(document).ready(function () {


        //--- changed 1311 --///
        var start = '<?= date('d/m/Y'); ?>';
        //var startval = '<? ($model->bkg_create_date1 == '') ? '' : DateTimeFormat::DateToDatePicker($model->bkg_create_date1) ?>';
        var end = '<?= date('d/m/Y'); ?>';
        //var endval = '<? ($model->bkg_create_date2 == '') ? '' : DateTimeFormat::DateToDatePicker($model->bkg_create_date2) ?>';

        //$('#BookingTemp_bkg_create_date1').val(startval);
        //$('#BookingTemp_bkg_create_date2').val(endval);

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
            $('#BookingTemp_bkg_create_date1').val(start1.format('DD/MM/YYYY'));
            $('#BookingTemp_bkg_create_date2').val(end1.format('DD/MM/YYYY'));
            $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgCreateDate span').html('Select Create Date Range');
            $('#BookingTemp_bkg_create_date1').val('');
            $('#BookingTemp_bkg_create_date2').val('');
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
                        'Tomorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
                        'Next 7 Days': [moment(), moment().add(6, 'days')],
                        'Next 15 Days': [moment(), moment().add(15, 'days')],
                        'All upcoming': [moment(), moment().add(11, 'month')],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
                    }
                }, function (start1, end1) {
            $('#BookingTemp_bkg_pickup_date1').val(start1.format('DD/MM/YYYY'));
            $('#BookingTemp_bkg_pickup_date2').val(end1.format('DD/MM/YYYY'));
            $('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgPickupDate span').html('Select Pickup Date Range');
            $('#BookingTemp_bkg_pickup_date1').val('');
            $('#BookingTemp_bkg_pickup_date2').val('');
        });

    });

    function updateGrid(id)
    {
        if ($.fn.yiiGridView != undefined && $.fn.yiiGridView.settings['leadGrid' + id] != undefined) {
            $url = $('#leadGrid' + id).yiiGridView('getUrl');
            $('#sec' + id).load($url);
            //          addTabCache(id);
        }
    }

    function assignCSR(obj, tab = 1) {
        //  box.modal('hide');
        $href = $(obj).attr('href');
        jQuery.ajax({
            type: 'GET',
            "dataType": "json",
            url: $href,
            success: function (data1) {
//                csrBox.hide();
                csrBox.remove();
                updateGrid(tab);
            }
        });
        return false;
    }

    function addCsr(obj) {
        try
        {
            $href = $(obj).attr("href");
            jQuery.ajax({type: "GET", "dataType": "html", url: $href, success: function (data)
                {
                    csrBox = bootbox.dialog({
                        message: data,
                        title: "Assign CSR",
                        className: "bootbox-lg",
                    });
                }});
        } catch (e)
        {
            alert(e);
        }
        return false;
    }
    function changeLock(obj, type, tab) {
        var con = confirm("Do you want to " + type + " this lead?");
        if (con) {
            $href = $(obj).attr('href');
            $.ajax({
                url: $href,
                success: function (result) {
                    if (result != null && result != "")
                    {
                        if (result.trim() == "true") {
                            updateGrid(tab);
                        } else {
                            alert('Sorry error occured');
                        }
                    }
                },
                error: function (xhr, status, error) {
                    alert('Sorry error occured');
                }
            });
        }
        return false;
    }
    function follow(obj)
    {
        try
        {
            $href = $(obj).attr("href");
            jQuery.ajax({type: "GET", "dataType": "html", url: $href, success: function (data)
                {
                    csrBox = bootbox.dialog({
                        message: data,
                        title: "Lead follow up",
                        size: 'large',
                    });
                }});
        } catch (e)
        {
            alert(e);
        }
        return false;
    }
    function followUp(obj)
    {
        try
        {
            $href = $(obj).attr("href");
            jQuery.ajax({type: "GET", "dataType": "html", url: $href, success: function (data)
                {
                    bootbox.dialog({
                        message: data,
                        size: 'large',
                        title: "Add follow up",
                    });
                }});
        } catch (e)
        {
            alert(e);
        }
        return false;
    }

    function showLog(obj)
    {
        try
        {
            $href = $(obj).attr("href");
            jQuery.ajax({type: "GET", "dataType": "html", url: $href, success: function (data)
                {
                    bootbox.dialog({
                        message: data,
                        title: "Show Log",
                        className: "bootbox-lg",
                        callback: function () {
                        },
                    });
                }});
        } catch (e)
        {
            alert(e);
        }
        return false;
    }

    function autoAssign(obj)
    {
        $href = $(obj).attr("href");
        $.ajax({
            url: $href,
            type: 'GET',
            "dataType": "json",
            success: function (result) {
				if(result.hasOwnProperty("url"))
				{
					window.location = result.url;
				}
				elseif(result.hasOwnProperty("success") && result.success==false)
				{
					alert(JSON.stringify(result.errors));
				}
            },
            error: function (xhr, status, error) {
                alert('Sorry error occured');
            }
        });
        return false;
    }

    function markRead(obj)
    {
        var con = confirm("Are you sure you want to mark this read?");
        if (con) {
            $href = $(obj).attr("href");
            $.ajax({
                url: $href,
                success: function (result) {
                    if (result != null && result != "")
                    {
                        if (result.trim() == "true") {
                            updateGrid('.$status.');
                        } else {
                            alert('Sorry error occured');
                        }
                    }
                },
                error: function (xhr, status, error) {
                    alert('Sorry error occured');
                }
            });
        }
        return false;
    }

    function markInvalid(obj)
    {
        try
        {
            $href = $(obj).attr("href");
            jQuery.ajax({type: "GET", "dataType": "html", url: $href, success: function (data)
                {
                    bootbox.dialog({
                        message: data,
                        title: "Mark Invalid",
                        callback: function () {
                        },
                    });
                }});
        } catch (e)
        {
            alert(e);
        }
        return false;
    }
    function showRelated(obj)
    {
        try
        {
            $href = $(obj).attr("href");
            jQuery.ajax({type: "GET", "dataType": "html", url: $href, success: function (data)
                {
                    bootbox.dialog({
                        message: data,
                        title: "Related Leads",
                        size: 'large',
                        callback: function () {
                        },
                    });
                }});
        } catch (e)
        {
            alert(e);
        }
        return false;
    }
    $sourceList = null;
    function populateSource(obj, cityId) {

        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
                    dataType: 'json',
                    data: {
                        // city: cityId
                    },
                    //  async: false,
                    success: function (results) {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(cityId);
                    },
                    error: function () {
                        callback();
                    }
                });
            } else {
                obj.enable();
                callback($sourceList);
                obj.setValue(cityId);
            }
        });
    }
    function loadSource(query, callback) {
        //	if (!query.length) return callback();
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=1&q=' + encodeURIComponent(query),
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
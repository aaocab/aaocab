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
$pageno				 = Yii::app()->request->getParam('page');
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];

if (!isset(Yii::app()->request->getParam('VendorPackages')['vpk_type']))
	$model['vpk_type']	 = '';
?>

<?php
$vendorCity			 = (Cities::model()->getCityOnlyByBooking1());
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
				<?php
				$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'vendorPackageList', 'enableClientValidation' => true,
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

                <div class="row mt10">
                    <div class="col-xs-12 col-sm-6 col-md-3"> 
						<?= $form->textFieldGroup($model, 'search', array('label' => 'Search:', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Search By Vendor Code,Vendor Name')))) ?>
                    </div>
					<div class="col-xs-12 col-sm-6 col-md-3">
                        <div class="form-group">
                            <label class="control-label">Created Date Range</label>
							<?php
							$daterang			 = "Select Created Date Range";
							$vpk_created_date1	 = ($model->vpk_created_date1 == '') ? '' : $model->vpk_created_date1;
							$vpk_created_date2	 = ($model->vpk_created_date2 == '') ? '' : $model->vpk_created_date2;
							if ($vpk_created_date1 != '' && $vpk_created_date2 != '')
							{
								$daterang = date('F d, Y', strtotime($vpk_created_date1)) . " - " . date('F d, Y', strtotime($vpk_created_date2));
							}
							?>
                            <div id="vpkCreatedDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>
							<?= $form->hiddenField($model, 'vpk_created_date1'); ?>
							<?= $form->hiddenField($model, 'vpk_created_date2'); ?>
                        </div></div>

					<div class="col-xs-12 col-sm-6 col-md-3">
                        <div class="form-group">
                            <label class="control-label">Package Sent Date Range</label>
							<?php
							$daterang				 = "Select Package Sent Date Range";
							$vpk_sentpackage_date1	 = ($model->vpk_sentpackage_date1 == '') ? '' : $model->vpk_sentpackage_date1;
							$vpk_sentpackage_date2	 = ($model->vpk_sentpackage_date2 == '') ? '' : $model->vpk_sentpackage_date2;
							if ($vpk_sentpackage_date1 != '' && $vpk_sentpackage_date2 != '')
							{
								$daterang = date('F d, Y', strtotime($vpk_sentpackage_date1)) . " - " . date('F d, Y', strtotime($vpk_sentpackage_date2));
							}
							?>
                            <div id="vpkPackageSentDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>
							<?= $form->hiddenField($model, 'vpk_sentpackage_date1'); ?>
							<?= $form->hiddenField($model, 'vpk_sentpackage_date2'); ?>
                        </div></div>

                    <div class="col-xs-12   col-md-3"> 
						<?= $form->textFieldGroup($model, 'searchVehicleNumber', array('label' => 'Car Code(s):', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Search By Car Code(s)')))) ?>
                    </div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-3">
                        <div class="form-group">
                            <label class="control-label">Packages Status</label>
							<?php
							$statusList	 = VendorPackages::model()->getStatus();
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'vpk_status',
								'val'			 => $model->vpk_status,
								'asDropDownList' => FALSE,
								'options'		 => array(
									'data'		 => new CJavaScriptExpression(VendorPackages::model()->getJSON($statusList)),
									'allowClear' => true
								),
								'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Select Packages Status')
							));
							?>
                        </div>
					</div>


                    <div class="col-xs-12 col-md-3"> 
                        <div class="form-group">
                            <label class="control-label">Packages Type</label>
							<?php
							$typeList	 = VendorPackages::model()->getType();
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'vpk_type',
								'val'			 => $model->vpk_type,
								'asDropDownList' => FALSE,
								'options'		 => array(
									'data' => new CJavaScriptExpression(VendorPackages::model()->getJSON($typeList)),
								//'allowClear' => true
								),
								'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Select Packages Type')
							));
							?>
                        </div>
                    </div>

                    <div class="col-xs-12  col-md-3"> 
                        <div class="form-group cityinput">
                            <label class="control-label">Search By Home City</label>
							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $cityModel,
								'attribute'			 => 'cty_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select City",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width' => '100%',),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                                    populateSourceCity(this, '{$cityModel->cty_id}');
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
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-2 mt20">
                        <button class="btn btn-primary full-width" type="submit"  name="packagesSearch">Search</button>
                    </div>
                </div> 
				<?php $this->endWidget(); ?>
                <div class="row"> 
					<?php
					if (!empty($dataProvider))
					{
						$params									 = array_filter($_REQUEST);
						$dataProvider->getPagination()->params	 = $params;
						$dataProvider->getSort()->params		 = $params;
						$this->widget('booster.widgets.TbGridView', array(
							'responsiveTable'	 => true,
							'dataProvider'		 => $dataProvider,
							'id'				 => 'packageList',
							'template'			 => "<div class='panel-heading'><div class='row m0'>
                                        <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                </div></div>
                                <div class='panel-body table-responsive'>{items}</div>
                                <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
							'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
							'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
							'columns'			 => array(
								array('name' => 'vpk_id', 'value' => '$data[vpk_id]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Id'),
								array('name'	 => 'vnd_code', 'value'	 => function ($data) {
										$vndName = $data["vnd_name"];
										$vndCode = $data["vnd_code"];
										if ($data["ctt_first_name"] != "" && $data["ctt_last_name"] != "")
										{
											$vndName = $data["ctt_first_name"] . $data["ctt_last_name"];
										}
										else if ($data["ctt_business_name"] != "")
										{
											$vndName = $data["ctt_business_name"];
										}
										$icon				 = '<img src="/images/icon/eye.png"  style="cursor:pointer ;height:16px; width:16px;" title="Value">';
										echo CHtml::link($vndCode, Yii::app()->createUrl("admin/vendor/view", ["id" => $data['vnd_id']]), ["class" => "", "onclick" => "return viewDetail(this)"]);
										echo CHtml::link($icon, Yii::app()->createUrl("admin/vendor/profile", ["id" => $data['vnd_id']]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
										echo ($vndName != '') ? "<br>( " . $vndName . " )" : '';
									}, 'headerHtmlOptions'						 => array('class' => 'col-xs-1'), 'header'								 => 'Vendor'),
								array('name'	 => 'carCount', 'value'	 => function($data) {
										$ctr	 = 0;
										$ids	 = $data["vpk_vhc_id"];
										$vhcIds	 = explode(',', $ids);
										foreach ($vhcIds as $id)
										{
											$ctr	 = ($ctr + 1);
											$vhcCode .= Vehicles::getCodeById($id) . ' , ';
										}
										return $ctr;
									}, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Car Count'),
								array('name'	 => 'vhc_number', 'value'	 => function($data) {
										$ctr	 = 0;
										$ids	 = $data["vpk_vhc_id"];
										$vhcIds	 = explode(',', $ids);
										foreach ($vhcIds as $id)
										{
											$ctr	 = ($ctr + 1);
											$vhcCode .= Vehicles::getCodeById($id) . ' , ';
										}
										return $vhcCode;
									}, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Car Code(s)'),
								array('name' => 'vpk_mailing_address', 'value' => '$data["vpk_mailing_address"]', 'headerHtmlOptions' => array('class' => 'col-xs-3'), 'header' => 'Mailing Address'),
								array('name'	 => 'vpk_created_date', 'value'	 => function ($data) {
										echo (DateTimeFormat::DateTimeToLocale($data["vpk_created_date"]));
									}, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Date of Packages Created'),
								array('name'	 => 'vpk_sent_date', 'value'	 => function ($data) {
										$vpkId	 = $data['vpk_id'];
										$chkSend = $data['vpk_sent_count'];
										if ($chkSend > 0)
										{
											echo (DateTimeFormat::DateTimeToLocale($data["vpk_sent_date"]));
											echo "<input type='checkbox' style='border:5px;' id='chkSend' onclick='sendPackages(0, $vpkId, $chkSend)' checked='true' disabled='disabled' />";
										}
										else
										{
											echo "<input type='checkbox' style='border:5px;' id='chkSend' onclick='sendPackages(0, $vpkId, $chkSend)'  />";
										}
									}, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Date of Packages Sent', 'id'				 => 'checkbox'),
								array('name'	 => 'vpk_type', 'value'	 => function ($data) {
										$packageType = $data['vpk_type'];
										if ($packageType == 1)
										{
											echo "Sticker";
										}
										else
										{
											echo "Cab Partition";
										}
									}, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Type of Packages'),
								array('name' => 'vpk_sent_count', 'value' => '$data["vpk_sent_count"]', 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'No. of Packages Sent'),
								array('name' => 'vpk_tracking_number', 'value' => '$data["vpk_tracking_number"]', 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Tracking Number'),
								array('name'	 => 'vpk_received_date', 'value'	 => function ($data) {
										$vpkId		 = $data['vpk_id'];
										$chkReceived = $data['vpk_received_status'];
										$chkSent	 = 0;
										$chkSent	 = $data['vpk_sent_count'];
										if ($chkReceived == 1 && ( $chkReceived != NULL || $chkReceived != '' || $chkReceived != 0))
										{
											echo (DateTimeFormat::DateTimeToLocale($data["vpk_received_date"]));
											echo "<input type='checkbox' id='chkReceive' onclick='receivedPackages(1, $vpkId, $chkReceived, $chkSent)' checked='true' disabled='disabled' />";
										}
										else
										{
											echo "<input type='checkbox' id='chkReceive' onclick='receivedPackages(1, $vpkId, $chkReceived, $chkSent)'/>";
										}
									}, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Date of Packages Received by Vendor'),
								array('name'	 => 'vpk_delivered_by_courier', 'value'	 => function($data) {
										$courier	 = $data["vpk_delivered_by_courier"];
										$typeCourier = VendorPackages::$deliveredCourierArr[$courier];
										return $typeCourier;
									}, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Delivered by courier'),
								array(
									'header'			 => 'Action',
									'class'				 => 'CButtonColumn',
									'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center'),
									'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
									'template'			 => '{boostEdit}{delete}',
									'buttons'			 => array(
										'boostEdit'		 => array(
											'url'		 => 'Yii::app()->createUrl("admin/vendor/editPackages", array("packageId" => $data["vpk_id"]))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\zone\edit_booking.png',
											'label'		 => '<i class="fa fa-edit"></i>',
											'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'Edit Boost'),
										),
										'delete'		 => array(
											'click'		 => 'function(){
                                                                    var con = confirm("Are you sure you want to dacativate this packages?");
                                                                    return con;
                                                                }',
											'url'		 => 'Yii::app()->createUrl("admin/vendor/delPackages", array(\'vpk_id\' => $data["vpk_id"] ))',
											'imageUrl'	 => false,
											'label'		 => '<i class="fa fa-remove"></i>',
											'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 8px', 'class' => 'btn btn-xs btn-danger condelete', 'title' => 'Delete'),
										),
										'htmlOptions'	 => array('class' => 'center'))),
						)));
					}
					else
					{
						echo '<div class="col-xs-12"><div class="table-responsive panel panel-primary compact" id="packagesList"><div class="panel-heading"><div class="row m0"><div class="col-xs-12 col-sm-6 pt5"></div><div class="col-xs-12 col-sm-6 pr0"></div></div></div><div class="panel-body"><table class="table table-striped table-bordered mb0 table"><thead><tr><th id="boostlist_c0">Vendor</th><th id="packagesList_c1">Car Code(s)</th><th id="packagesList_c2">Mailling address</th><th id="packagesList_c3">Packages Sent</th><th id="packagesList_c4">Packages Received Date</th><th class="col-xs-1 text-center" style="min-width: 100px;" id="packagesList_c5">Action</th></tr></thead><tbody><tr><td colspan="6" class="empty"><span class="empty">No results found.</span></td></tr></tbody></table></div><div class="panel-footer"><div class="row m0"><div class="col-xs-12 col-sm-6 p5"></div><div class="col-xs-12 col-sm-6 pr0"></div></div></div><div class="keys" style="display:none" title="/admpnl/vendor/packagesList"></div></div></div>';
					}
					?> 
                </div> 

            </div>  

        </div>  
    </div>
</div>
<script type="text/javascript">
    var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
    var end = '<?= date('d/m/Y'); ?>';
    //vpkPackageSentDate
    $('#vpkPackageSentDate').daterangepicker(
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
        $('#VendorPackages_vpk_sentpackage_date1').val(start1.format('YYYY-MM-DD'));
        $('#VendorPackages_vpk_sentpackage_date2').val(end1.format('YYYY-MM-DD'));
        $('#vpkPackageSentDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#vpkPackageSentDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#vpkPackageSentDate span').html('Select Package Sent Date Range');
        $('#VendorPackages_vpk_sentpackage_date1').val('');
        $('#VendorPackages_vpk_sentpackage_date2').val('');
    });

    //vpkCreatedDate 
    $('#vpkCreatedDate').daterangepicker(
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
        $('#VendorPackages_vpk_created_date1').val(start1.format('YYYY-MM-DD'));
        $('#VendorPackages_vpk_created_date2').val(end1.format('YYYY-MM-DD'));
        $('#vpkCreatedDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#vpkCreatedDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#vpkCreatedDate span').html('Select Created Date Range');
        $('#VendorPackages_vpk_created_date1').val('');
        $('#VendorPackages_vpk_created_date2').val('');
    });
    function sendPackages(obj, vpkId, chkSend)
    {
        if (chkSend > 0)
        {
            alert('Already Send.');
        } else
        {
            bootbox.confirm({
                title: "Packages Sent",
                message: '<div class="form-group"> ' +
                        '<div style="margin-top:5px;"> ' +
                        '<label class="control-label" for="project-description">How many packages you sent ?</label> ' +
                        '<input type="number" id="packagesCount" name="packagesCount" min="1" style="border:1px solid #eee"> ' + '</br>' +
                        '</div>' +
                        '<div style="margin-top:5px;"> ' +
                        '<label class="control-label" for="project-description">Please Enter Tracking Number</label> ' +
                        '<input type="text" id="trackingNumber" name="trackingNumber" style="border:1px solid #eee"> ' +
                        '</div>' +
                        '</div>',

                buttons: {
                    confirm: {
                        label: 'OK',
                        className: 'btn-info'
                    },
                    cancel: {
                        label: 'CANCEL',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if (result) {
                        var packagesSentCount = $("#packagesCount").val();
                        var trackingNumber = $("#trackingNumber").val();
                        if (packagesSentCount == '' || packagesSentCount < 0 || packagesSentCount == 0)
                        {
                            alert("Please Enter package you want(greater than 0).");
                        } else if (trackingNumber == '')
                        {
                            alert("Please Enter Tracking Number.");
                        } else
                        {
                            var href = '<?= Yii::app()->createUrl("admin/vendor/sentPackages"); ?>';
                            jQuery.ajax({'type': 'GET', 'url': href,
                                'data': {'vpkId': vpkId, "status": obj, "packagesSentCount": packagesSentCount, "trackingNumber": trackingNumber},
                                success: function (data)
                                {
                                    bootbox.hideAll()
                                    window.location.reload(true);

                                }
                            });
                        }
                    }
                }
            });
        }
    }
    function receivedPackages(obj, vpkId, chkReceived, chkSent)
    {
        if (chkReceived == 1 && (chkReceived != NULL || chkReceived != '')) {

            alert('Already Received.');
        } else if (chkSent == 0)
        {
            alert('You have to send the Packages.');
        } else {
            bootbox.confirm({
                title: "Packages Received",
                message: "Is Packages Received By Vendor?",
                buttons: {
                    confirm: {
                        label: 'OK',
                        className: 'btn-info'
                    },
                    cancel: {
                        label: 'CANCEL',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if (result) {
                        var href1 = '<?= Yii::app()->createUrl("admin/vendor/receivedPackages"); ?>';
                        jQuery.ajax({'type': 'GET', 'url': href1,
                            'data': {'vpkId': vpkId, "status": obj},
                            success: function (data)
                            {
                                bootbox.hideAll()
                                window.location.reload(true);

                            }
                        });
                    }
                }
            });
        }
    }
</script>